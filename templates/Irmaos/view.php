<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Irmao $irmao
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Irmao'), ['action' => 'edit', $irmao->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Irmao'), ['action' => 'delete', $irmao->id], ['confirm' => __('Are you sure you want to delete # {0}?', $irmao->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Irmaos'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Irmao'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="irmaos view content">
            <h3><?= h($irmao->nome) ?></h3>
            <table>
                <tr>
                    <th><?= __('Loja') ?></th>
                    <td><?= $irmao->has('loja') ? $this->Html->link($irmao->loja->nome, ['controller' => 'Lojas', 'action' => 'view', $irmao->loja->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Nome') ?></th>
                    <td><?= h($irmao->nome) ?></td>
                </tr>
                <tr>
                    <th><?= __('Grau') ?></th>
                    <td><?= h($irmao->grau) ?></td>
                </tr>
                <tr>
                    <th><?= __('Logradouro') ?></th>
                    <td><?= h($irmao->logradouro) ?></td>
                </tr>
                <tr>
                    <th><?= __('Numero') ?></th>
                    <td><?= h($irmao->numero) ?></td>
                </tr>
                <tr>
                    <th><?= __('Complemento') ?></th>
                    <td><?= h($irmao->complemento) ?></td>
                </tr>
                <tr>
                    <th><?= __('Bairro') ?></th>
                    <td><?= h($irmao->bairro) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cidade') ?></th>
                    <td><?= h($irmao->cidade) ?></td>
                </tr>
                <tr>
                    <th><?= __('Estado') ?></th>
                    <td><?= h($irmao->estado) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cep') ?></th>
                    <td><?= h($irmao->cep) ?></td>
                </tr>
                <tr>
                    <th><?= __('Telefone') ?></th>
                    <td><?= h($irmao->telefone) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($irmao->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($irmao->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Data Nascimento') ?></th>
                    <td><?= h($irmao->data_nascimento) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($irmao->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($irmao->modified) ?></td>
                </tr>
                <tr>
                    <th><?= __('Deleted') ?></th>
                    <td><?= h($irmao->deleted) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ativo') ?></th>
                    <td><?= $irmao->ativo ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Mensalidades') ?></h4>
                <?php if (!empty($irmao->mensalidades)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Irmao Id') ?></th>
                            <th><?= __('Mes Referencia') ?></th>
                            <th><?= __('Valor') ?></th>
                            <th><?= __('Pago') ?></th>
                            <th><?= __('Data Pagamento') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Deleted') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($irmao->mensalidades as $mensalidades) : ?>
                        <tr>
                            <td><?= h($mensalidades->id) ?></td>
                            <td><?= h($mensalidades->irmao_id) ?></td>
                            <td><?= h($mensalidades->mes_referencia) ?></td>
                            <td><?= h($mensalidades->valor) ?></td>
                            <td><?= h($mensalidades->pago) ?></td>
                            <td><?= h($mensalidades->data_pagamento) ?></td>
                            <td><?= h($mensalidades->created) ?></td>
                            <td><?= h($mensalidades->modified) ?></td>
                            <td><?= h($mensalidades->deleted) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Mensalidades', 'action' => 'view', $mensalidades->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Mensalidades', 'action' => 'edit', $mensalidades->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Mensalidades', 'action' => 'delete', $mensalidades->id], ['confirm' => __('Are you sure you want to delete # {0}?', $mensalidades->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Presencas') ?></h4>
                <?php if (!empty($irmao->presencas)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Irmao Id') ?></th>
                            <th><?= __('Data Sessao') ?></th>
                            <th><?= __('Tipo Sessao') ?></th>
                            <th><?= __('Presente') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Deleted') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($irmao->presencas as $presencas) : ?>
                        <tr>
                            <td><?= h($presencas->id) ?></td>
                            <td><?= h($presencas->irmao_id) ?></td>
                            <td><?= h($presencas->data_sessao) ?></td>
                            <td><?= h($presencas->tipo_sessao) ?></td>
                            <td><?= h($presencas->presente) ?></td>
                            <td><?= h($presencas->created) ?></td>
                            <td><?= h($presencas->modified) ?></td>
                            <td><?= h($presencas->deleted) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Presencas', 'action' => 'view', $presencas->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Presencas', 'action' => 'edit', $presencas->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Presencas', 'action' => 'delete', $presencas->id], ['confirm' => __('Are you sure you want to delete # {0}?', $presencas->id)]) ?>
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
