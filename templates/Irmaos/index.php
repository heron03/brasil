<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Irmao> $irmaos
 */
?>
<div class="irmaos index content">
    <?= $this->Html->link(__('New Irmao'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Irmaos') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('loja_id') ?></th>
                    <th><?= $this->Paginator->sort('nome') ?></th>
                    <th><?= $this->Paginator->sort('data_nascimento') ?></th>
                    <th><?= $this->Paginator->sort('grau') ?></th>
                    <th><?= $this->Paginator->sort('logradouro') ?></th>
                    <th><?= $this->Paginator->sort('numero') ?></th>
                    <th><?= $this->Paginator->sort('complemento') ?></th>
                    <th><?= $this->Paginator->sort('bairro') ?></th>
                    <th><?= $this->Paginator->sort('cidade') ?></th>
                    <th><?= $this->Paginator->sort('estado') ?></th>
                    <th><?= $this->Paginator->sort('cep') ?></th>
                    <th><?= $this->Paginator->sort('telefone') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('ativo') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('deleted') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($irmaos as $irmao): ?>
                <tr>
                    <td><?= $this->Number->format($irmao->id) ?></td>
                    <td><?= $irmao->has('loja') ? $this->Html->link($irmao->loja->nome, ['controller' => 'Lojas', 'action' => 'view', $irmao->loja->id]) : '' ?></td>
                    <td><?= h($irmao->nome) ?></td>
                    <td><?= h($irmao->data_nascimento) ?></td>
                    <td><?= h($irmao->grau) ?></td>
                    <td><?= h($irmao->logradouro) ?></td>
                    <td><?= h($irmao->numero) ?></td>
                    <td><?= h($irmao->complemento) ?></td>
                    <td><?= h($irmao->bairro) ?></td>
                    <td><?= h($irmao->cidade) ?></td>
                    <td><?= h($irmao->estado) ?></td>
                    <td><?= h($irmao->cep) ?></td>
                    <td><?= h($irmao->telefone) ?></td>
                    <td><?= h($irmao->email) ?></td>
                    <td><?= h($irmao->ativo) ?></td>
                    <td><?= h($irmao->created) ?></td>
                    <td><?= h($irmao->modified) ?></td>
                    <td><?= h($irmao->deleted) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $irmao->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $irmao->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $irmao->id], ['confirm' => __('Are you sure you want to delete # {0}?', $irmao->id)]) ?>
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
