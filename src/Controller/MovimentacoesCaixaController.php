<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * MovimentacoesCaixa Controller
 *
 * @property \App\Model\Table\MovimentacoesCaixaTable $MovimentacoesCaixa
 * @method \App\Model\Entity\MovimentacoesCaixa[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MovimentacoesCaixaController extends AppController
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
        $movimentacoesCaixa = $this->paginate($this->MovimentacoesCaixa);

        $this->set(compact('movimentacoesCaixa'));
    }

    /**
     * View method
     *
     * @param string|null $id Movimentacoes Caixa id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $movimentacoesCaixa = $this->MovimentacoesCaixa->get($id, [
            'contain' => ['Lojas'],
        ]);

        $this->set(compact('movimentacoesCaixa'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $movimentacoesCaixa = $this->MovimentacoesCaixa->newEmptyEntity();
        if ($this->request->is('post')) {
            $movimentacoesCaixa = $this->MovimentacoesCaixa->patchEntity($movimentacoesCaixa, $this->request->getData());
            if ($this->MovimentacoesCaixa->save($movimentacoesCaixa)) {
                $this->Flash->success(__('The movimentacoes caixa has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The movimentacoes caixa could not be saved. Please, try again.'));
        }
        $lojas = $this->MovimentacoesCaixa->Lojas->find('list', ['limit' => 200])->all();
        $this->set(compact('movimentacoesCaixa', 'lojas'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Movimentacoes Caixa id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $movimentacoesCaixa = $this->MovimentacoesCaixa->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $movimentacoesCaixa = $this->MovimentacoesCaixa->patchEntity($movimentacoesCaixa, $this->request->getData());
            if ($this->MovimentacoesCaixa->save($movimentacoesCaixa)) {
                $this->Flash->success(__('The movimentacoes caixa has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The movimentacoes caixa could not be saved. Please, try again.'));
        }
        $lojas = $this->MovimentacoesCaixa->Lojas->find('list', ['limit' => 200])->all();
        $this->set(compact('movimentacoesCaixa', 'lojas'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Movimentacoes Caixa id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $movimentacoesCaixa = $this->MovimentacoesCaixa->get($id);
        if ($this->MovimentacoesCaixa->delete($movimentacoesCaixa)) {
            $this->Flash->success(__('The movimentacoes caixa has been deleted.'));
        } else {
            $this->Flash->error(__('The movimentacoes caixa could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
