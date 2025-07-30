<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MovimentacoesCaixa $movimentacoesCaixa
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Movimentacoes Caixa'), ['action' => 'edit', $movimentacoesCaixa->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Movimentacoes Caixa'), ['action' => 'delete', $movimentacoesCaixa->id], ['confirm' => __('Are you sure you want to delete # {0}?', $movimentacoesCaixa->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Movimentacoes Caixa'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Movimentacoes Caixa'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="movimentacoesCaixa view content">
            <h3><?= h($movimentacoesCaixa->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Loja') ?></th>
                    <td><?= $movimentacoesCaixa->has('loja') ? $this->Html->link($movimentacoesCaixa->loja->nome, ['controller' => 'Lojas', 'action' => 'view', $movimentacoesCaixa->loja->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Tipo') ?></th>
                    <td><?= h($movimentacoesCaixa->tipo) ?></td>
                </tr>
                <tr>
                    <th><?= __('Origem') ?></th>
                    <td><?= h($movimentacoesCaixa->origem) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($movimentacoesCaixa->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Valor') ?></th>
                    <td><?= $this->Number->format($movimentacoesCaixa->valor) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data Movimentacao') ?></th>
                    <td><?= h($movimentacoesCaixa->data_movimentacao) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($movimentacoesCaixa->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($movimentacoesCaixa->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Deleted') ?></th>
                    <td><?= h($movimentacoesCaixa->deleted) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Descricao') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($movimentacoesCaixa->descricao)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>
