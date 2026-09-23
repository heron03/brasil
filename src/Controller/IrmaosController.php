<?php

declare(strict_types=1);

namespace App\Controller;


use App\Model\Entity\Irmao;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;

class IrmaosController extends AppController
{
    public $paginate = [
        'fields' => ['id', 'nome', 'cim', 'cpf', 'loja_id', 'ativo'],
        'contain' => [
            'Lojas' => ['fields' => ['id', 'nome']],
        ],
        'order' => ['Irmaos.nome' => 'asc'],
        'limit' => 20,
    ];

    public function paginateConditions(): array
    {
        $conditions = parent::paginateConditions();
        $session = $this->getRequest()->getSession();
        if (!Irmao::temAcessoGestao($session->read('Auth.nivel'))) {
            $conditions[] = ["Irmaos.id" => $session->read('Auth.id')];
        }
        $conditions[] = $this->Irmaos->condicaoVisivel();
        $nome = $this->request->is('post') ?
            $this->dataCondition('Irmaos.nome') :
            $this->sessionCondition('Irmaos.nome');

        if (!empty($nome)) {
            $conditions['Irmaos.nome LIKE'] = "%{$nome}%";
        }
        $conditions[] = ["Irmaos.deleted IS NULL"];

        return $conditions;
    }

    public function initialize(): void
    {
        parent::initialize();
        $this->Authentication->allowUnauthenticated(['login', 'hashHelper', 'edit', 'cadastroAcesso']);
    }

    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);

        $action = (string)$this->request->getParam('action');
        if (in_array($action, ['login', 'logout', 'hashHelper', 'edit'], true)) {
            $this->Authorization->skipAuthorization();
        }
    }

    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();

        if ($result->isValid()) {
            $redirect = $this->request->getQuery('redirect')
                ?? ['controller' => 'Irmaos', 'action' => 'login_redirect'];
            return $this->redirect($redirect);
        }
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->set(
                $this->getErrorMessage($result->getErrors()),
                ['plugin' => 'MetronicV4', 'key' => 'danger', 'element' => 'message']
            );
        }
        $this->setFields();
    }

    public function cadastroAcesso()
    {
        $this->Authorization->skipAuthorization();
        $irmaosTable = $this->fetchTable('Irmaos');

        $passo = 'buscar';
        $irmao = null;

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $passo = $data['passo'] ?? 'buscar';

            // PASSO 1 – BUSCA PELO CIM
            if ($passo === 'buscar') {
                $cim = trim((string)($data['cim'] ?? ''));

                if ($cim === '') {
                    $this->Flash->error('Informe o CIM.');
                } else {
                    $irmao = $irmaosTable->find()
                        ->where([
                            'Irmaos.cim' => $cim,
                            'Irmaos.ativo' => 1,
                            'Irmaos.deleted IS' => null,
                        ])
                        ->first();

                    if (!$irmao) {
                        $this->Flash->error('Nenhum irmão encontrado com esse CIM.');
                    } elseif (!empty($irmao->senha)) {
                        $this->Flash->warning(
                            'Este irmão já possui acesso. Use a tela de login ou recuperação de senha.'
                        );
                    } else {
                        // Vai para o passo de preenchimento dos dados
                        $passo = 'dados';
                    }
                }
            }

            // PASSO 2 – SALVAR DADOS DE ACESSO
            if ($passo === 'dados' && isset($data['irmao_id'])) {
                $irmaoId = (int)$data['irmao_id'];
                $irmao = $irmaosTable->get($irmaoId);
            
                $email = trim((string)($data['email'] ?? ''));
                $telefone = trim((string)($data['telefone'] ?? ''));
                $senha = (string)($data['senha'] ?? '');
                $senhaConfirm = (string)($data['senha_confirm'] ?? '');
            
                $erros = [];
            
                if ($email === '') {
                    $erros[] = 'Informe um e-mail.';
                }
            
                if ($senha === '' || $senhaConfirm === '') {
                    $erros[] = 'Informe a senha e a confirmação.';
                } elseif ($senha !== $senhaConfirm) {
                    $erros[] = 'A confirmação da senha não confere.';
                }
            
                if (!empty($erros)) {
                    foreach ($erros as $msg) {
                        $this->Flash->error($msg);
                    }
                    $passo = 'dados'; // mantém no segundo passo
                } else {
            
                    // Aqui a senha é passada "crua". O _setSenha do Entity vai hashear.
                    $irmao = $irmaosTable->patchEntity(
                        $irmao,
                        [
                            'email'    => $email,
                            'telefone' => $telefone,
                            'senha'    => $senha,
                        ],
                        [
                            'fields' => ['email', 'telefone', 'senha'],
                        ]
                    );
            
                    if ($irmaosTable->save($irmao)) {
                        $this->Flash->success('Cadastro de acesso realizado com sucesso! Você já pode fazer login.');
                        return $this->redirect(['action' => 'login']);
                    } else {
                        $this->Flash->error('Não foi possível salvar seus dados. Tente novamente.');
                        $passo = 'dados';
                    }
                }
            }
        }

        $this->set(compact('passo', 'irmao'));
    }

    protected function getErrorMessage(array $errors): string
    {
        $message = 'Credenciais inválidas.';

        return $message;
    }

    public function logout()
    {
        $this->request->allowMethod(['get', 'post']);
        $this->Authentication->logout();
        return $this->redirect(['action' => 'login']);
    }

    public function hashHelper()
    {
        $this->Authorization->skipAuthorization();
        $plain = $this->request->getQuery('p') ?? '123456';
        $hasher = new \Authentication\PasswordHasher\DefaultPasswordHasher();
        $this->set('hash', $hasher->hash($plain));
        $this->viewBuilder()->setOption('serialize', ['hash']);
    }

    protected ?string $senhaCadastroMensagem = null;

    public function beforeInsert(): void
    {
        $this->protegerNivel();
        $this->validarSenhaNovoIrmao();
    }

    public function beforeUpdate(): void
    {
        $this->protegerNivel();
        $data = $this->request->getData();
        unset($data['senha'], $data['confirma_senha'], $data['senha_atual']);
        $this->request = $this->request->withParsedBody($data);
    }

    public function saveGetData(EntityInterface $entity)
    {
        if ($this->senhaCadastroMensagem !== null) {
            $entity->setError('senha', $this->senhaCadastroMensagem);
            $this->Flash->bootstrapNotifyMessage($this->senhaCadastroMensagem, [
                'plugin' => 'MetronicV4',
                'key' => 'danger',
            ]);

            return false;
        }

        return parent::saveGetData($entity);
    }

    protected function validarSenhaNovoIrmao(): void
    {
        $data = $this->request->getData();
        $senha = (string)($data['senha'] ?? '');
        $confirma = (string)($data['confirma_senha'] ?? '');
        unset($data['confirma_senha']);

        if ($senha === '' && $confirma === '') {
            unset($data['senha']);
        } elseif ($senha === '' || $confirma === '') {
            unset($data['senha']);
            $this->senhaCadastroMensagem = 'Informe a senha e a confirmação.';
        } elseif ($senha !== $confirma) {
            unset($data['senha']);
            $this->senhaCadastroMensagem = 'A confirmação da senha não confere.';
        }

        $this->request = $this->request->withParsedBody($data);
    }

    protected function protegerNivel(): void
    {
        $session = $this->getRequest()->getSession();
        $data = $this->request->getData();
        if ($session->read('Auth.nivel') !== Irmao::NIVEL_DESENVOLVEDOR) {
            unset($data['nivel']);
        }
        if (!Irmao::temAcessoGestao($session->read('Auth.nivel'))) {
            unset(
                $data['ativo'],
                $data['loja_id'],
                $data['desconto_valor'],
                $data['desconto_mutua'],
                $data['desconto_capitacao'],
                $data['desconto_diversos'],
                $data['desconto_reserva']
            );
        }
        $this->request = $this->request->withParsedBody($data);
    }

    public function getEditEntity(int $id): EntityInterface
    {
        $entity = $this->{$this->getModelName()}->newEmptyEntity();

        if ($id != null) {
            $entity = $this->{$this->getModelName()}->get($id);
        }
        $entity->set('senha', null);
        $entity->setDirty('senha', false);

        return $entity;
    }

    public function editSenha(?int $id = null): void
    {
        $session = $this->request->getSession();
        $authId = (int)$session->read('Auth.id');
        if ($id === null) {
            $id = $authId;
        }
        $irmao = $this->Irmaos->get($id);
        $this->Authorization->authorize($irmao);
        $exigirSenhaAtual = $id === $authId;

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $atual = (string)($data['senha_atual'] ?? '');
            $nova = (string)($data['senha'] ?? '');
            $confirma = (string)($data['confirma_senha'] ?? '');
            $hasher = new DefaultPasswordHasher();

            if ($nova === '' || $confirma === '' || ($exigirSenhaAtual && $atual === '')) {
                $mensagem = 'Informe a senha atual, a nova senha e a confirmação.';
                if (!$exigirSenhaAtual) {
                    $mensagem = 'Informe a nova senha e a confirmação.';
                }
            } elseif ($exigirSenhaAtual && !$hasher->check($atual, (string)$irmao->get('senha'))) {
                $mensagem = 'A senha atual não confere.';
            } elseif ($nova !== $confirma) {
                $mensagem = 'A confirmação da nova senha não confere.';
            } else {
                $irmao = $this->Irmaos->patchEntity($irmao, ['senha' => $nova], [
                    'fields' => ['senha'],
                ]);
                if ($this->Irmaos->save($irmao)) {
                    $this->Flash->bootstrapNotifyMessage('Senha alterada com sucesso.', [
                        'plugin' => 'MetronicV4',
                        'key' => 'success',
                    ]);
                    $this->redirect(['action' => 'index']);

                    return;
                }
                $mensagem = 'Não foi possível alterar a senha. Tente novamente.';
            }

            $this->Flash->bootstrapNotifyMessage($mensagem, [
                'plugin' => 'MetronicV4',
                'key' => 'danger',
            ]);
        }

        $irmao->set('senha', null);
        $irmao->setDirty('senha', false);
        $this->set(compact('irmao', 'exigirSenhaAtual'));
        $this->setFields();
    }

    public function loginRedirect()
    {
        $this->Authorization->skipAuthorization();
        $this->layout = false;
    }
}
