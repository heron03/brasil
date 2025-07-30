<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\MovimentacoesCaixa $movimentacoesCaixa
 * @var \Cake\Collection\CollectionInterface|string[] $lojas
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Movimentacoes Caixa'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="movimentacoesCaixa form content">
            <?= $this->Form->create($movimentacoesCaixa) ?>
            <fieldset>
                <legend><?= __('Add Movimentacoes Caixa') ?></legend>
                <?php
                    echo $this->Form->control('loja_id', ['options' => $lojas]);
                    echo $this->Form->control('tipo');
                    echo $this->Form->control('descricao');
                    echo $this->Form->control('valor');
                    echo $this->Form->control('data_movimentacao');
                    echo $this->Form->control('origem');
                    echo $this->Form->control('deleted', ['empty' => true]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
