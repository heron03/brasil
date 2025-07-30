<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Irmao $irmao
 * @var string[]|\Cake\Collection\CollectionInterface $lojas
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $irmao->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $irmao->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Irmaos'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="irmaos form content">
            <?= $this->Form->create($irmao) ?>
            <fieldset>
                <legend><?= __('Edit Irmao') ?></legend>
                <?php
                    echo $this->Form->control('loja_id', ['options' => $lojas]);
                    echo $this->Form->control('nome');
                    echo $this->Form->control('data_nascimento', ['empty' => true]);
                    echo $this->Form->control('grau');
                    echo $this->Form->control('logradouro');
                    echo $this->Form->control('numero');
                    echo $this->Form->control('complemento');
                    echo $this->Form->control('bairro');
                    echo $this->Form->control('cidade');
                    echo $this->Form->control('estado');
                    echo $this->Form->control('cep');
                    echo $this->Form->control('telefone');
                    echo $this->Form->control('email');
                    echo $this->Form->control('ativo');
                    echo $this->Form->control('deleted', ['empty' => true]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
