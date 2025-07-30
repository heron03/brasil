<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Loja $loja
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Loja'), ['action' => 'edit', $loja->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Loja'), ['action' => 'delete', $loja->id], ['confirm' => __('Are you sure you want to delete # {0}?', $loja->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Lojas'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Loja'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="lojas view content">
            <h3><?= h($loja->nome) ?></h3>
            <table>
                <tr>
                    <th><?= __('Nome') ?></th>
                    <td><?= h($loja->nome) ?></td>
                </tr>
                <tr>
                    <th><?= __('Logradouro') ?></th>
                    <td><?= h($loja->logradouro) ?></td>
                </tr>
                <tr>
                    <th><?= __('Numero') ?></th>
                    <td><?= h($loja->numero) ?></td>
                </tr>
                <tr>
                    <th><?= __('Complemento') ?></th>
                    <td><?= h($loja->complemento) ?></td>
                </tr>
                <tr>
                    <th><?= __('Bairro') ?></th>
                    <td><?= h($loja->bairro) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cidade') ?></th>
                    <td><?= h($loja->cidade) ?></td>
                </tr>
                <tr>
                    <th><?= __('Estado') ?></th>
                    <td><?= h($loja->estado) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cep') ?></th>
                    <td><?= h($loja->cep) ?></td>
                </tr>
                <tr>
                    <th><?= __('Telefone') ?></th>
                    <td><?= h($loja->telefone) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($loja->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($loja->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($loja->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($loja->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Deleted') ?></th>
                    <td><?= h($loja->deleted) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Irmaos') ?></h4>
                <?php if (!empty($loja->irmaos)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Loja Id') ?></th>
                            <th><?= __('Nome') ?></th>
                            <th><?= __('Data Nascimento') ?></th>
                            <th><?= __('Grau') ?></th>
                            <th><?= __('Logradouro') ?></th>
                            <th><?= __('Numero') ?></th>
                            <th><?= __('Complemento') ?></th>
                            <th><?= __('Bairro') ?></th>
                            <th><?= __('Cidade') ?></th>
                            <th><?= __('Estado') ?></th>
                            <th><?= __('Cep') ?></th>
                            <th><?= __('Telefone') ?></th>
                            <th><?= __('Email') ?></th>
                            <th><?= __('Ativo') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Deleted') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($loja->irmaos as $irmaos) : ?>
                        <tr>
                            <td><?= h($irmaos->id) ?></td>
                            <td><?= h($irmaos->loja_id) ?></td>
                            <td><?= h($irmaos->nome) ?></td>
                            <td><?= h($irmaos->data_nascimento) ?></td>
                            <td><?= h($irmaos->grau) ?></td>
                            <td><?= h($irmaos->logradouro) ?></td>
                            <td><?= h($irmaos->numero) ?></td>
                            <td><?= h($irmaos->complemento) ?></td>
                            <td><?= h($irmaos->bairro) ?></td>
                            <td><?= h($irmaos->cidade) ?></td>
                            <td><?= h($irmaos->estado) ?></td>
                            <td><?= h($irmaos->cep) ?></td>
                            <td><?= h($irmaos->telefone) ?></td>
                            <td><?= h($irmaos->email) ?></td>
                            <td><?= h($irmaos->ativo) ?></td>
                            <td><?= h($irmaos->created) ?></td>
                            <td><?= h($irmaos->modified) ?></td>
                            <td><?= h($irmaos->deleted) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Irmaos', 'action' => 'view', $irmaos->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Irmaos', 'action' => 'edit', $irmaos->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Irmaos', 'action' => 'delete', $irmaos->id], ['confirm' => __('Are you sure you want to delete # {0}?', $irmaos->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Movimentacoes Caixa') ?></h4>
                <?php if (!empty($loja->movimentacoes_caixa)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Loja Id') ?></th>
                            <th><?= __('Tipo') ?></th>
                            <th><?= __('Descricao') ?></th>
                            <th><?= __('Valor') ?></th>
                            <th><?= __('Data Movimentacao') ?></th>
                            <th><?= __('Origem') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Deleted') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($loja->movimentacoes_caixa as $movimentacoesCaixa) : ?>
                        <tr>
                            <td><?= h($movimentacoesCaixa->id) ?></td>
                            <td><?= h($movimentacoesCaixa->loja_id) ?></td>
                            <td><?= h($movimentacoesCaixa->tipo) ?></td>
                            <td><?= h($movimentacoesCaixa->descricao) ?></td>
                            <td><?= h($movimentacoesCaixa->valor) ?></td>
                            <td><?= h($movimentacoesCaixa->data_movimentacao) ?></td>
                            <td><?= h($movimentacoesCaixa->origem) ?></td>
                            <td><?= h($movimentacoesCaixa->created) ?></td>
                            <td><?= h($movimentacoesCaixa->modified) ?></td>
                            <td><?= h($movimentacoesCaixa->deleted) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'MovimentacoesCaixa', 'action' => 'view', $movimentacoesCaixa->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'MovimentacoesCaixa', 'action' => 'edit', $movimentacoesCaixa->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'MovimentacoesCaixa', 'action' => 'delete', $movimentacoesCaixa->id], ['confirm' => __('Are you sure you want to delete # {0}?', $movimentacoesCaixa->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
