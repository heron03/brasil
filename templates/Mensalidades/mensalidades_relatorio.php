<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $breadcrumbItems
 * @var \App\View\AppView $this
 * @var mixed $breadcrumbItems
 */
$flash = $this->Flash->render('danger') .
    $this->Flash->render('success') .
    $this->Flash->render('info') .
    $this->Flash->render('primary') .
    $this->Flash->render('warning');

if (!$this->request->is('ajax')) {
    $this->assign('title', $this->element('title'));
    $this->assign('header', $this->element('header'));
    $this->assign('aSideLeft', $this->element('aSideLeft'));
    $this->assign('footer', $this->element('footer'));
}

if (!isset($entity)) {
    $entity = null;
}

$form = $this->Metronic->filterFormCreate($entity, [
    'url' => ['action' => $this->request->getParam('action')], 'id' => 'indexForm',
]);
$session = $this->getRequest()->getSession();

$dataInicial = $session->read('Mensalidades.data_inicial');
$dataFinal = $session->read('Mensalidades.data_final');

$subheader = $this->Html->div('m-subheader', $this->Html->div(
    'row d-flex align-items-center',
    $this->Html->div(
        'col-sm-8 mr-auto',
        $this->Html->tag('h3', 'Mensalidades por Período', ['class' => 'm-subheader__title m-subheader__title--separator']) .
        $this->Metronic->breadcrumbs($breadcrumbItems),
    ),
));

$portlet = $this->Html->div(
    'm-portlet m-portlet--mobile',
    $this->Html->div('m-portlet__body', $this->Html->div(
        'row m--margin-bottom-20',
        $this->Form->hidden('Mensalidades.data_inicial', ['date-range-picker' => 'start', 'value' => $dataInicial]) .
        $this->Form->hidden('Mensalidades.data_final', ['date-range-picker' => 'end', 'value' => $dataFinal]) .
        $this->Html->div('col-md-3', $this->Metronic->dateRangePicker()) .
        $this->Html->div('col-md-3', $this->Metronic->input('Mensalidades.pago')),
    ) .
    $this->Html->div('', $this->Metronic->link('Imprimir', [
        'url' => '/mensalidades/relatorio',
        'class' => 'btn btn-primary',
        'data-toggle' => 'm-tooltip',
        'target' => '_blank',
    ]))),
);

echo $form;
echo $subheader;
echo $this->Html->div('m-content', $flash . $portlet);
echo $this->Form->end();

if ($this->request->is('ajax')) {
    echo $this->Metronic->writeBuffer();
}
