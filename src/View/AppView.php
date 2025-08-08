<?php
declare(strict_types=1);

namespace App\View;

use Cake\View\View;

/**
 * @property \MetronicV4\View\Helper\MetronicHelper $Metronic
 * @property \App\View\Helper\JsHelper $Js
 */
class AppView extends View
{
    public array $tamanhoCampo = [
        2 => 'col-sm-2',
        4 => 'col-sm-4',
        6 => 'col-sm-6',
    ];

    public function initialize(): void
    {
    }

    public function formConfiguracao($tituloAdicional = ''): void
    {
        $this->extend('MetronicV4.Pages/add');
        $this->assign('pageTitle', $this->{$this->getModelName()}->titulos[$this->request->getParam('action')] . $tituloAdicional);

        if (!$this->request->is('ajax')) {
            $this->assign('header', $this->element('header'));
        }
        if ($this->request->getParam('action') != 'view') {
            $formActions = $this->Metronic->saveButton($this->request->getParam('action'));
            $this->assign('formActions', $formActions);
        }
    }

    public function formulario(string $fields = 'fields'): string
    {
        $entidade = $this->getModelName();
        $linhas = '';
        $fields = $this->{$entidade}->fields[$fields];
        foreach ($fields as $value) {
            $linhas .= $this->linha($value);
        }

        return $this->Html->div('m-form__group', $linhas);
    }

    public function camposHidden(array $camposDaLinha): string
    {
        $campos = '';
        foreach ($camposDaLinha as $value) {
            $campos .= $this->Form->hidden($value['nome'], $value['valor']);
        }

        return $campos;
    }

    public function campo(string $campo): string
    {
        $campoInput = $this->Metronic->input($campo);
        if ($campo == 'foto') {
            $campoInput = $this->Html->div(
                'col-md-12 mt-3',
                $this->element('dropzone', ['field' => 'foto', 'label' => 'Fotos']),
            );
        }

        return $campoInput;
    }

    public function linha(array $camposDaLinha): string
    {
        $campos = '';
        foreach ($camposDaLinha as $value) {
            $campos .= $this->campo($value);
        }

        return $this->Html->div('form-group row', $campos);
    }

    public function getModelName(): string
    {
        return $this->request->getParam('controller');
    }

}
