<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Presenca $presenca
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Presenca'), ['action' => 'edit', $presenca->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Presenca'), ['action' => 'delete', $presenca->id], ['confirm' => __('Are you sure you want to delete # {0}?', $presenca->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Presencas'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Presenca'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="presencas view content">
            <h3><?= h($presenca->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Irmao') ?></th>
                    <td><?= $presenca->has('irmao') ? $this->Html->link($presenca->irmao->nome, ['controller' => 'Irmaos', 'action' => 'view', $presenca->irmao->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Tipo Sessao') ?></th>
                    <td><?= h($presenca->tipo_sessao) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($presenca->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data Sessao') ?></th>
                    <td><?= h($presenca->data_sessao) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($presenca->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($presenca->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Deleted') ?></th>
                    <td><?= h($presenca->deleted) ?></td>
                </tr>
                <tr>
                    <th><?= __('Presente') ?></th>
                    <td><?= $presenca->presente ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
