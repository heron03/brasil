<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Lojas Controller
 *
 * @property \App\Model\Table\LojasTable $Lojas
 * @method \App\Model\Entity\Loja[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LojasController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $lojas = $this->paginate($this->Lojas);

        $this->set(compact('lojas'));
    }

    /**
     * View method
     *
     * @param string|null $id Loja id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $loja = $this->Lojas->get($id, [
            'contain' => ['Irmaos', 'MovimentacoesCaixa'],
        ]);

        $this->set(compact('loja'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $loja = $this->Lojas->newEmptyEntity();
        if ($this->request->is('post')) {
            $loja = $this->Lojas->patchEntity($loja, $this->request->getData());
            if ($this->Lojas->save($loja)) {
                $this->Flash->success(__('The loja has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The loja could not be saved. Please, try again.'));
        }
        $this->set(compact('loja'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Loja id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $loja = $this->Lojas->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $loja = $this->Lojas->patchEntity($loja, $this->request->getData());
            if ($this->Lojas->save($loja)) {
                $this->Flash->success(__('The loja has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The loja could not be saved. Please, try again.'));
        }
        $this->set(compact('loja'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Loja id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $loja = $this->Lojas->get($id);
        if ($this->Lojas->delete($loja)) {
            $this->Flash->success(__('The loja has been deleted.'));
        } else {
            $this->Flash->error(__('The loja could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
