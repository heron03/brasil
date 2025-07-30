<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Mensalidade $mensalidade
 * @var string[]|\Cake\Collection\CollectionInterface $irmaos
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $mensalidade->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $mensalidade->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Mensalidades'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="mensalidades form content">
            <?= $this->Form->create($mensalidade) ?>
            <fieldset>
                <legend><?= __('Edit Mensalidade') ?></legend>
                <?php
                    echo $this->Form->control('irmao_id', ['options' => $irmaos]);
                    echo $this->Form->control('mes_referencia');
                    echo $this->Form->control('valor');
                    echo $this->Form->control('pago');
                    echo $this->Form->control('data_pagamento', ['empty' => true]);
                    echo $this->Form->control('deleted', ['empty' => true]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
