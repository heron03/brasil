<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Mensalidade> $mensalidades
 */
?>
<div class="mensalidades index content">
    <?= $this->Html->link(__('New Mensalidade'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Mensalidades') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('irmao_id') ?></th>
                    <th><?= $this->Paginator->sort('mes_referencia') ?></th>
                    <th><?= $this->Paginator->sort('valor') ?></th>
                    <th><?= $this->Paginator->sort('pago') ?></th>
                    <th><?= $this->Paginator->sort('data_pagamento') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('deleted') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mensalidades as $mensalidade): ?>
                <tr>
                    <td><?= $this->Number->format($mensalidade->id) ?></td>
                    <td><?= $mensalidade->has('irmao') ? $this->Html->link($mensalidade->irmao->nome, ['controller' => 'Irmaos', 'action' => 'view', $mensalidade->irmao->id]) : '' ?></td>
                    <td><?= h($mensalidade->mes_referencia) ?></td>
                    <td><?= $this->Number->format($mensalidade->valor) ?></td>
                    <td><?= h($mensalidade->pago) ?></td>
                    <td><?= h($mensalidade->data_pagamento) ?></td>
                    <td><?= h($mensalidade->created) ?></td>
                    <td><?= h($mensalidade->modified) ?></td>
                    <td><?= h($mensalidade->deleted) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $mensalidade->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $mensalidade->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $mensalidade->id], ['confirm' => __('Are you sure you want to delete # {0}?', $mensalidade->id)]) ?>
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
