<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Loja> $lojas
 */
?>
<div class="lojas index content">
    <?= $this->Html->link(__('New Loja'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Lojas') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('nome') ?></th>
                    <th><?= $this->Paginator->sort('logradouro') ?></th>
                    <th><?= $this->Paginator->sort('numero') ?></th>
                    <th><?= $this->Paginator->sort('complemento') ?></th>
                    <th><?= $this->Paginator->sort('bairro') ?></th>
                    <th><?= $this->Paginator->sort('cidade') ?></th>
                    <th><?= $this->Paginator->sort('estado') ?></th>
                    <th><?= $this->Paginator->sort('cep') ?></th>
                    <th><?= $this->Paginator->sort('telefone') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('deleted') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lojas as $loja): ?>
                <tr>
                    <td><?= $this->Number->format($loja->id) ?></td>
                    <td><?= h($loja->nome) ?></td>
                    <td><?= h($loja->logradouro) ?></td>
                    <td><?= h($loja->numero) ?></td>
                    <td><?= h($loja->complemento) ?></td>
                    <td><?= h($loja->bairro) ?></td>
                    <td><?= h($loja->cidade) ?></td>
                    <td><?= h($loja->estado) ?></td>
                    <td><?= h($loja->cep) ?></td>
                    <td><?= h($loja->telefone) ?></td>
                    <td><?= h($loja->email) ?></td>
                    <td><?= h($loja->created) ?></td>
                    <td><?= h($loja->modified) ?></td>
                    <td><?= h($loja->deleted) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $loja->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $loja->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $loja->id], ['confirm' => __('Are you sure you want to delete # {0}?', $loja->id)]) ?>
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
