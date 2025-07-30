<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Mensalidade $mensalidade
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Mensalidade'), ['action' => 'edit', $mensalidade->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Mensalidade'), ['action' => 'delete', $mensalidade->id], ['confirm' => __('Are you sure you want to delete # {0}?', $mensalidade->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Mensalidades'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Mensalidade'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="mensalidades view content">
            <h3><?= h($mensalidade->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Irmao') ?></th>
                    <td><?= $mensalidade->has('irmao') ? $this->Html->link($mensalidade->irmao->nome, ['controller' => 'Irmaos', 'action' => 'view', $mensalidade->irmao->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($mensalidade->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Valor') ?></th>
                    <td><?= $this->Number->format($mensalidade->valor) ?></td>
                </tr>
                <tr>
                    <th><?= __('Mes Referencia') ?></th>
                    <td><?= h($mensalidade->mes_referencia) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data Pagamento') ?></th>
                    <td><?= h($mensalidade->data_pagamento) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($mensalidade->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($mensalidade->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Deleted') ?></th>
                    <td><?= h($mensalidade->deleted) ?></td>
                </tr>
                <tr>
                    <th><?= __('Pago') ?></th>
                    <td><?= $mensalidade->pago ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
