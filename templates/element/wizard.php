<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $active
 */

// echo $this->Html->css(['all.min']);
// $this->Metronic->addScript('all.min');

$steps = [
    '1' => ['title' => 'Dados', 'url' => '', 'icon' => 'fas fa-universal-access'],
    '2' => ['title' => 'Dados', 'url' => '', 'icon' => 'fas fa-user-friends'],
];

echo $this->Metronic->wizardNavigator($steps, $active);
