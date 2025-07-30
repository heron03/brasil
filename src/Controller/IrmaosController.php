<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Irmaos Controller
 *
 * @property \App\Model\Table\IrmaosTable $Irmaos
 * @method \App\Model\Entity\Irmao[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class IrmaosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Lojas'],
        ];
        $irmaos = $this->paginate($this->Irmaos);

        $this->set(compact('irmaos'));
    }

    /**
     * View method
     *
     * @param string|null $id Irmao id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $irmao = $this->Irmaos->get($id, [
            'contain' => ['Lojas', 'Mensalidades', 'Presencas'],
        ]);

        $this->set(compact('irmao'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $irmao = $this->Irmaos->newEmptyEntity();
        if ($this->request->is('post')) {
            $irmao = $this->Irmaos->patchEntity($irmao, $this->request->getData());
            if ($this->Irmaos->save($irmao)) {
                $this->Flash->success(__('The irmao has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The irmao could not be saved. Please, try again.'));
        }
        $lojas = $this->Irmaos->Lojas->find('list', ['limit' => 200])->all();
        $this->set(compact('irmao', 'lojas'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Irmao id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $irmao = $this->Irmaos->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $irmao = $this->Irmaos->patchEntity($irmao, $this->request->getData());
            if ($this->Irmaos->save($irmao)) {
                $this->Flash->success(__('The irmao has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The irmao could not be saved. Please, try again.'));
        }
        $lojas = $this->Irmaos->Lojas->find('list', ['limit' => 200])->all();
        $this->set(compact('irmao', 'lojas'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Irmao id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $irmao = $this->Irmaos->get($id);
        if ($this->Irmaos->delete($irmao)) {
            $this->Flash->success(__('The irmao has been deleted.'));
        } else {
            $this->Flash->error(__('The irmao could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
