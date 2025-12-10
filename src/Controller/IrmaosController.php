<?php

declare(strict_types=1);

namespace App\Controller;


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
        if ($session->read('Auth.nivel') != 'Gestor') {
            $conditions[] = ["Irmaos.id" => $session->read('Auth.id')];
        }
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

    public function getEditEntity(int $id): EntityInterface
    {
        $entity = $this->{$this->getModelName()}->newEmptyEntity();

        if ($id != null) {
            $entity = $this->{$this->getModelName()}->get($id);
        }

        if ($entity['data_nascimento'] != null) {
            $entity['data_nascimento'] = $entity['data_nascimento']->format('d/m/Y');
        }
        $entity['senha'] = null;

        return $entity;
    }

    public function loginRedirect()
    {
        $this->Authorization->skipAuthorization();
        $this->layout = false;
    }
}
