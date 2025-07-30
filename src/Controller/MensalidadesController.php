<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Mensalidades Controller
 *
 * @property \App\Model\Table\MensalidadesTable $Mensalidades
 * @method \App\Model\Entity\Mensalidade[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MensalidadesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Irmaos'],
        ];
        $mensalidades = $this->paginate($this->Mensalidades);

        $this->set(compact('mensalidades'));
    }

    /**
     * View method
     *
     * @param string|null $id Mensalidade id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $mensalidade = $this->Mensalidades->get($id, [
            'contain' => ['Irmaos'],
        ]);

        $this->set(compact('mensalidade'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $mensalidade = $this->Mensalidades->newEmptyEntity();
        if ($this->request->is('post')) {
            $mensalidade = $this->Mensalidades->patchEntity($mensalidade, $this->request->getData());
            if ($this->Mensalidades->save($mensalidade)) {
                $this->Flash->success(__('The mensalidade has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The mensalidade could not be saved. Please, try again.'));
        }
        $irmaos = $this->Mensalidades->Irmaos->find('list', ['limit' => 200])->all();
        $this->set(compact('mensalidade', 'irmaos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Mensalidade id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $mensalidade = $this->Mensalidades->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $mensalidade = $this->Mensalidades->patchEntity($mensalidade, $this->request->getData());
            if ($this->Mensalidades->save($mensalidade)) {
                $this->Flash->success(__('The mensalidade has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The mensalidade could not be saved. Please, try again.'));
        }
        $irmaos = $this->Mensalidades->Irmaos->find('list', ['limit' => 200])->all();
        $this->set(compact('mensalidade', 'irmaos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Mensalidade id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $mensalidade = $this->Mensalidades->get($id);
        if ($this->Mensalidades->delete($mensalidade)) {
            $this->Flash->success(__('The mensalidade has been deleted.'));
        } else {
            $this->Flash->error(__('The mensalidade could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
