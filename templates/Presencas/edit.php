<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Presenca $presenca
 * @var string[]|\Cake\Collection\CollectionInterface $irmaos
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $presenca->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $presenca->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Presencas'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="presencas form content">
            <?= $this->Form->create($presenca) ?>
            <fieldset>
                <legend><?= __('Edit Presenca') ?></legend>
                <?php
                    echo $this->Form->control('irmao_id', ['options' => $irmaos]);
                    echo $this->Form->control('data_sessao');
                    echo $this->Form->control('tipo_sessao');
                    echo $this->Form->control('presente');
                    echo $this->Form->control('deleted', ['empty' => true]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
