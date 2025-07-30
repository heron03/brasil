<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\MovimentacoesCaixa> $movimentacoesCaixa
 */
?>
<div class="movimentacoesCaixa index content">
    <?= $this->Html->link(__('New Movimentacoes Caixa'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Movimentacoes Caixa') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('loja_id') ?></th>
                    <th><?= $this->Paginator->sort('tipo') ?></th>
                    <th><?= $this->Paginator->sort('valor') ?></th>
                    <th><?= $this->Paginator->sort('data_movimentacao') ?></th>
                    <th><?= $this->Paginator->sort('origem') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('deleted') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movimentacoesCaixa as $movimentacoesCaixa): ?>
                <tr>
                    <td><?= $this->Number->format($movimentacoesCaixa->id) ?></td>
                    <td><?= $movimentacoesCaixa->has('loja') ? $this->Html->link($movimentacoesCaixa->loja->nome, ['controller' => 'Lojas', 'action' => 'view', $movimentacoesCaixa->loja->id]) : '' ?></td>
                    <td><?= h($movimentacoesCaixa->tipo) ?></td>
                    <td><?= $this->Number->format($movimentacoesCaixa->valor) ?></td>
                    <td><?= h($movimentacoesCaixa->data_movimentacao) ?></td>
                    <td><?= h($movimentacoesCaixa->origem) ?></td>
                    <td><?= h($movimentacoesCaixa->created) ?></td>
                    <td><?= h($movimentacoesCaixa->modified) ?></td>
                    <td><?= h($movimentacoesCaixa->deleted) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $movimentacoesCaixa->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $movimentacoesCaixa->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $movimentacoesCaixa->id], ['confirm' => __('Are you sure you want to delete # {0}?', $movimentacoesCaixa->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
