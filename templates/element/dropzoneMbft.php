<?php
/**
 * @var \App\View\AppView $this
 * @var array $credencial
 * @var mixed $field
 * @var array $imageSrc
 * @var mixed $label
 * @var mixed $message
 * @var array $orgao
 */
use Cake\Utility\Inflector;

$dropClass = 'm-dropzone--primary';
$modelName = $this->request->getParam('controller');
$fieldName = $modelName . Inflector::Camelize($field);
$errorMessage = $hint = '';
$class = '';

if ($this->Form->isFieldError($modelName . '.' . $field)) {
    $dropClass = 'm-dropzone--danger';
    $class .= 'error has-danger';
    $errorMessage = $this->Form->error($modelName . '.' . $field, null, ['wrap' => 'span', 'class' => 'form-control-feedback']);
}

if (!empty($message)) {
    $hint = $this->Html->tag('span', $message, ['class' => 'm-form__help']);
}

$action = 'uploadMbft';
$controller = 'infracoes';
$remover = '';
$fotos = null;
$diretorio = null;

$imagePreview = '';

if (!empty($infraco['mbft'])) {
    if (substr($infraco['mbft'], -3) == 'pdf') {
        $img = 'img/pdf.jpg';
    }
}
$this->Metronic->buffer("$('.m-dropzone').has('.dz-preview').addClass('dz-started dz-max-files-reached');");

if (!empty($img)) {
    $tagImagem = $this->Html->tag('img', '', ['src' => $img, 'data-dz-thumbnail' => true]);
}

if (!empty($tagImagem)) {
    $imagePreview = $this->Html->div(
        'dz-preview dz-image-preview dz-processing dz-success dz-complete',
        $this->Html->div('dz-image', $tagImagem) .
        $this->Metronic->link($this->Metronic->icon('la la-trash', 'Remover'), [
            'class' => 'm-link m-link--state m-link--danger m--margin-top-10 m--margin-bottom-10 previewRemoveButton',
            'data-dz-remove' => true,
            'url' => 'javascript:undefined;',
            'data-field' => $field,
        ]),
    );
    $this->Metronic->buffer("$('.m-dropzone').has('.dz-preview').addClass('dz-started dz-max-files-reached');");
}


echo $this->Html->div(
    $class,
    $this->Html->tag('label', $label) .
    $this->Html->div(
        'm-dropzone dropzone ' . $dropClass,
        $this->Html->div(
            'm-dropzone__msg dz-message needsclick',
            $this->Html->para('m-dropzone-image', $this->Metronic->icon('la la-cloud-upload', '', ['style' => 'font-size: 4em;'])) .
            $this->Html->para('m-dropzone__msg-title m--font-primary', 'Arraste ou clique aqui para adicionar a fotos.') .
            $this->Html->tag('span', 'Somente arquivos .JPG, .PNG ou .PDF', ['class' => 'm-dropzone__msg-desc']),
        ) .
        $imagePreview,
        ['action' => $this->Url->build([
            'controller' => $controller,
            'action' => $action,
            $field,
        ]), 'id' => 'documento-imagem', 'data-field' => $field]
    ) .
    $errorMessage . $hint
);
