<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Datasource\EntityInterface;
use Cake\Error\ExceptionRendererInterface;
use Cake\Event\EventInterface;
use Cake\Http\Response;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;

class AppController extends Controller
{
    public $paginate = [];

    public array $allowedActions = [];

    public array $saveOptions = [];

    public array $patchOptions = [];

    public array $associatedOptions = [];

    public $formField;

    public string $title = 'Registro';

    public string $renderAdd = 'form';

    public string $renderEdit = 'form';

    public string $renderView = 'form';

    public array $breadcrumb = [
        'index' => null,
        'add' => null,
        'edit' => null,
        'view' => null,
    ];

    public function beforeFilter(EventInterface $event): void
    {
        if ($this->request->is('ajax')) {
            $this->viewBuilder()->setLayout(null);
        }

        parent::beforeFilter($event);

        $this->set('title', $this->title);
        $this->setBreadcrumb();
    }

    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);
    }

    public function initialize(): void
    {
        parent::initialize();

        // $this->loadComponent('UserLog.UserLog');
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Authorization.Authorization');
        $this->viewBuilder()->setLayout('MetronicV4.demo5');
        $this->viewBuilder()->addHelpers([
            'Html',
            'Url',
            'Form',
            'Flash',
            'MetronicV4.Metronic' => ['update' => '#content'],
            'Time',
            'Pdf.Report',
            'Pdf.Document',
        ]);
    }

    public function index(): void
    {
        $entity = $this->setEntityAuthorization();
        $this->set(compact('entity'));
        $this->paginate['conditions'] = $this->paginateConditions();
        $session = $this->getRequest()->getSession();
        $session->write(['indexUrl' =>  '/' . $this->request->getParam('controller')]);

        try {
            $this->set(Inflector::variable($this->getModelName()), $this->paginate());
        } catch (ExceptionRendererInterface $e) {
            $this->redirect(['controller' => $this->request->getParam('controller'), 'action' => 'index']);
        }

        $this->setFilters();
    }

    public function add(): void
    {
        $entity = $this->setEntityAuthorization();

        if ($this->request->is('post')) {
            $this->beforeInsert();
            $saved = $this->saveGetData($entity);
            if ($saved) {
                $this->Flash->bootstrapNotifyMessage($this->getFlashMessage('add'), [
                    'plugin' => 'MetronicV4',
                    'key' => 'success',
                ]);
                $this->afterAdd($saved);
            }
        } else {
            $this->beforeAdd();
        }

        $this->set($this->getEntityName(), $entity);
        $this->setFields();
        $this->render($this->renderAdd);
    }

    public function edit(?int $id = null): void
    {
        if (empty($id) && !empty($this->request->getData('id'))) {
            $id = $this->request->getData('id');
        }
        $entity = $this->getEditEntity(intval($id));
        $this->Authorization->authorize($entity);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $this->beforeUpdate();
            $entity = $this->{$this->getModelName()}->patchEntity($entity, $this->request->getData(), $this->patchOptions);
            $saved = $this->{$this->getModelName()}->save($entity, $this->saveOptions);
            if ($saved) {
                $this->Flash->bootstrapNotifyMessage($this->getFlashMessage('edit'), [
                    'plugin' => 'MetronicV4',
                    'key' => 'success',
                ]);
                $this->afterEdit($saved);
            }
        } else {
            $this->beforeEdit();
        }

        $this->set($this->getEntityName(), $entity);
        $this->setFields();
        $this->render($this->renderEdit);
    }

    public function getEditEntity(int $id): EntityInterface
    {
        $entity = $this->{$this->getModelName()}->newEmptyEntity();

        if ($id != null) {
            $entity = $this->{$this->getModelName()}->get($id);
        }

        return $entity;
    }

    public function view(int $id): void
    {
        $entity = $this->getViewEntity(intval($id));
        $this->Authorization->authorize($entity);
        $this->set($this->getEntityName(), $entity);
        $disabled = true;
        $this->setFields(null, $disabled);

        $this->render($this->renderView);
    }

    public function delete(?int $id = null): void
    {
        $entity = $this->setEntityAuthorization();
        $this->Authorization->authorize($entity);

        $ids = Hash::extract($this->request->getData(), $this->getModelName() . '.{n}.id');

        if (!empty($id) && empty($ids)) {
            $ids = [$id];
        }

        $salvo = false;

        if (is_array($ids)) {
            foreach ($ids as $id) {
                $entity = $this->{$this->getModelName()}->get($id);
                $this->Authorization->authorize($entity);

                if (!$this->{$this->getModelName()}->excluir($entity)) {
                    $this->Flash->bootstrapNotifyMessage('Não foi possível excluir ' . $this->title, [
                        'plugin' => 'MetronicV4',
                        'key' => 'danger',
                    ]);
                } else {
                    // $this->UserLog->add([
                    //     'action_id' => $id,
                    // ]);
                    $this->afterDeleteUnicoNoBanco($entity);
                    $salvo = true;
                }
            }
        }

        if ($salvo) {
            $message = $this->getFlashMessage('delete');

            if (is_countable($ids)) {
                if (count($ids) > 1) {
                    $message = count($ids) . ' exclusões realizadas com sucesso!';
                }
            }

            $this->Flash->bootstrapNotifyMessage($message, [
                'plugin' => 'MetronicV4',
                'key' => 'success',
            ]);
        }
        $this->afterDelete();
    }

    public function getViewEntity(int $id): EntityInterface
    {
        return $this->{$this->getModelName()}->get($id);
    }

    
    public function report(): void
    {
        $this->setEntityAuthorization();
        $this->viewBuilder()->setLayout('ajax');
        $this->response = $this->response->withType('pdf');
        $this->reportTitle();
        $this->set($this->getControllerName(), $this->reportRecords());
    }

    public function reportTitle(): void
    {
        $this->set('reportTitle', null);
    }

    public function reportRecords(): array
    {
        $conditions = $this->reportConditions();
        $fields = $this->reportFields();
        $order = $this->reportOrder();
        $contain = $this->reportContain();
        $joins = $this->reportJoins();
        $reportRecords = $this->{$this->getModelName()}
            ->find(
                'all',
                compact(
                    'conditions',
                    'fields',
                    'order',
                    'contain',
                    'joins',
                ),
            )
            ->toArray();

        return $reportRecords;
    }

    public function reportConditions(): array
    {
        return $this->paginateConditions();
    }

    public function reportFields(): array
    {
        return $this->paginate['fields'];
    }

    public function reportOrder(): array
    {
        return $this->paginateOrder();
    }

    public function reportContain(): array
    {
        $contain = false;

        if (!empty($this->paginate['contain'])) {
            $contain = $this->paginate['contain'];
        }

        return $contain;
    }

    public function reportJoins(): ?array
    {
        $joins = null;

        if (!empty($this->paginate['joins'])) {
            $joins = $this->paginate['joins'];
        }

        return $joins;
    }

    public function paginateConditions(): array
    {
        if (!empty($this->request->getQuery('sort'))) {
            $this->request->getSession()->write($this->getModelName() . 'Sort', $this->request->getQuery('sort'));
        }

        if (!empty($this->request->getQuery('direction'))) {
            $this->request->getSession()->write(
                $this->getModelName() . 'Direction',
                $this->request->getQuery('direction'),
            );
        }


        if (empty($paginateConditions)) {
            $paginateConditions = [];
        }

        return $paginateConditions;
    }

    public function paginateOrder(): array
    {
        $order = [];
        $sort = $this->request->getSession()->read($this->getModelName() . 'Sort');
        $direction = $this->request->getSession()->read($this->getModelName() . 'Direction');

        if (empty($direction)) {
            $direction = 'asc';
        }

        if (empty($sort)) {
            $order = $this->paginate['order'];
        } else {
            $order = [$sort => $direction];
        }

        return $order;
    }

    public function dataCondition(string $fieldName, ?int $default = null): ?string
    {
        $dataCondition = $this->request->getData($fieldName);

        if (empty($dataCondition) && !empty($default)) {
            $dataCondition = $default;
        }
        $this->request->getSession()->write($fieldName, $dataCondition);

        return $dataCondition;
    }

    public function sessionCondition(string $fieldName, ?array $default = null): ?string
    {
        $sessionCondition = $this->request->getSession()->read($fieldName);

        if (empty($sessionCondition) && !empty($default)) {
            $sessionCondition = $default;
            $this->request->getSession()->write($fieldName, $sessionCondition);
        }

        return $sessionCondition;
    }

    public function beforeAdd(): void
    {
    }

    /** @return \Cake\Datasource\EntityInterface|false  */
    public function saveGetData(EntityInterface $entity)
    {
        $entity = $this->{$this->getModelName()}->patchEntity($entity, $this->request->getData(), $this->patchOptions);
        $saved = $this->{$this->getModelName()}->save($entity, $this->saveOptions);

        return $saved;
    }

    public function beforeInsert(): void
    {
    }

    public function afterAdd(?EntityInterface $saved = null): void
    {
        $this->redirect($this->indexUrl());
    }

    public function beforeEdit(): void
    {
    }

    public function afterEdit(?EntityInterface $saved = null): void
    {
        $this->redirect($this->indexUrl());
    }

    public function beforeUpdate(): void
    {
    }

    public function afterDelete(): void
    {
        $this->redirect($this->indexUrl());
    }

    public function afterDeleteUnicoNoBanco(?EntityInterface $saved = null): void
    {
        $this->redirect($this->indexUrl());
    }

    public function setEntityAuthorization(): EntityInterface
    {
        $entity = $this->{$this->getModelName()}->newEmptyEntity();
        $this->Authorization->authorize($entity);

        return $entity;
    }


    public function setFilters(): void
    {
        $this->loadFormField();

        if (!empty($this->formField)) {
            $this->formField->setFilters();
        }
    }

    public function setFields(?string $entidade = null, bool $disabled = false): void
    {
        $this->loadFormField($entidade);

        if (!empty($this->formField)) {
            $this->formField->setFields($disabled);
        }
    }

    public function loadFormField(?string $entidade = null): void
    {
        if ($entidade == null) {
            $entidade = $this->getModelName();
        }
        if (empty($this->formField)) {
            $formFieldComponentName = $entidade . 'FormField';
            if (!empty($this->formFieldName)) {
                $formFieldComponentName = $this->formFieldName . 'FormField';
            }
            if (file_exists(APP . 'Controller' . DS . 'Component' . DS . $formFieldComponentName . 'Component.php')) {
                $this->loadComponent($formFieldComponentName);
                $this->formField = $this->{$formFieldComponentName};
            }
        }
    }

    public function indexUrl()
    {
        $indexUrl = $this->request->getSession()->read('indexUrl');

        if (empty($indexUrl)) {
            $indexUrl = $this->paginateUrl($this->request->getParam('controller'), 'index');
        } else {
            $this->request->getSession()->delete('indexUrl');
        }

        return $indexUrl;
    }

    public function paginateUrl(string $controller, string $action, ?int $id = null): array
    {
        $url = compact('controller', 'action');

        if (!empty($id)) {
            $url[] = $id;
        }

        $query = $this->request->getQueryParams();

        if (!empty($query)) {
            $url = array_merge($url, ['?' => $query]);
        }

        return $url;
    }

    public function autoComplete(?string $query = null)
    {
        $this->setEntityAuthorization();
        $items = $this->{$this->getModelName()}->autoComplete($query);
        $response = $this->response->withType('application/json')
            ->withStringBody($items);

        return $response;
    }

    public function getFlashMessage(string $method): string
    {
        $message = null;
        $maleMessages = [
            'add' => $this->title . ' gravado com sucesso!',
            'edit' => $this->title . ' alterado com sucesso!',
            'delete' => $this->title . ' excluido com sucesso!',
        ];
        $femaleMessages = [
            'add' => $this->title . ' gravada com sucesso!',
            'edit' => $this->title . ' alterada com sucesso!',
            'delete' => $this->title . ' excluída com sucesso!',
        ];
        $message = $maleMessages[$method];
        [$first,] = explode(' ', $this->title);

        if (substr($first, -1, 1) == 'a') {
            $message = $femaleMessages[$method];
        }

        return $message;
    }

    public function getEntityName(): string
    {
        return Inflector::variable(Inflector::singularize($this->getControllerName()));
    }

    public function getControllerName(): string
    {
        return $this->request->getParam('controller');
    }

    public function getModelName(): string
    {
        return $this->request->getParam('controller');
    }

    public function setBreadcrumb(): void
    {
        $breadcrumb = $this->request->getSession()->read('breadcrumb');
        $this->request->getSession()->delete('breadcrumb');
        $action = $this->request->getParam('action');

        if (empty($breadcrumb)) {
            $breadcrumb = [];
        } else {
            if (!empty($this->breadcrumb[$action]) && $breadcrumb[0] == $this->breadcrumb[$action][0]) {
                unset($this->breadcrumb[$action][0]);
            }
        }

        if (!empty($this->breadcrumb[$action])) {
            $breadcrumb = array_merge($breadcrumb, $this->breadcrumb[$action]);
        }

        $this->set('breadcrumbItems', $breadcrumb);
    }

    public function reportPage()
    {
        return $this->request->getQuery('page') ?? 1;
    }
    
    public function reportLimit()
    {
        return $this->request->getQuery('limit') ?? $this->paginate['limit'];
    }

    public function reportJoin()
    {
        return $this->paginate['join'] ?? [];
    }

    public function getUsuarioLogado(): array
    {
        $identity = $this->Authentication->getIdentity();

        return !empty($identity) ? (array)$identity->getOriginalData() : [];
    }
}