<?php

declare(strict_types=1);

namespace App\Controller;

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
        $nome = $this->request->is('post') ?
            $this->dataCondition('Irmaos.nome') :
            $this->sessionCondition('Irmaos.nome');

        if (!empty($nome)) {
            $conditions['Irmaos.nome LIKE'] = "%{$nome}%";
        }

        return $conditions;
    }

    public function initialize(): void
    {
        parent::initialize();
        $this->Authentication->allowUnauthenticated(['login', 'hashHelper', 'edit']);
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
                ?? ['controller' => 'Mensalidades', 'action' => 'index'];
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
}
