<?php
$this->extend('MetronicV4.Pages/index');
$this->assign('pageTitle', 'Presenças');
$this->assign(
    'singleActions',
    $this->Metronic->deleteButton()
);

$this->assign('addButton', $this->Metronic->addButton());

$this->assign(
    'filter',
    $this->Metronic->input('Presencas.sessao_id') .
    $this->Html->div('col-sm-5', $this->Metronic->filterButton())
);

$sessaoHeader = $this->Metronic->pageSort('Sessoes.data_sessao', 'Sessão');
$irmaoHeader = $this->Metronic->pageSort('Irmaos.nome', 'Irmão');
$presenteHeader = $this->Metronic->pageSort('presente', 'Presente');

$tableHeaders = [
    $sessaoHeader,
    $irmaoHeader,
    $presenteHeader,
];

array_unshift($tableHeaders, [$this->Metronic->allRowCheckbox() => ['width' => '5%']]);
array_push($tableHeaders, ['' => ['width' => '5%']]);

$this->assign('tableHeaders', $this->Html->tableHeaders($tableHeaders, ['role' => 'row', 'class' => '']));

$cells = [];
foreach ($presencas as $i => $presenca) {
    $cells[] = [
        h($presenca->sessao->data_sessao->format('d/m/Y')),
        h($presenca->irmao->nome ?? '-'),
        $presenca->presente ? 'Sim' : 'Não',
    ];
    array_unshift($cells[$i], $this->Metronic->rowCheckbox("Presencas.$i.id", $presenca->id));
    array_push($cells[$i], $this->Metronic->editButton($presenca->id));
}

$this->assign('tableCells', $this->Html->tableCells(
    $cells,
    ['role' => 'row', 'class' => 'odd'],
    ['role' => 'row', 'class' => 'even']
));
