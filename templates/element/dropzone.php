<?php
use Cake\Utility\Inflector;
$dropClass = 'm-dropzone--primary';
$modelName = $this->request->getParam('controller');
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

$this->request->getSession()->read($field);
$action = 'upload';
$remover = '';

if (!empty($infraco)) {
    $fotos = $infraco['documentos'];
}
if (!empty($infraco)) {
    $fotos = $infraco['documentos'];
}

$diretorio = DOCUMENTOS;

if (!empty($imageSrc)) {
    $tagImagem = $this->Html->tag('img', '', ['src' => 'data:image/jpeg;base64,' . $imageSrc['id'], 'data-dz-thumbnail' => true]);
}
$imagePreview = '';
$this->Metronic->buffer("$('.m-dropzone').has('.dz-preview').addClass('dz-started dz-max-files-reached');");

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

if ($fotos != null) {
    foreach ($fotos as $key => $value) {
        if ($this->request->getParam('action') == 'edit') {
            $remover = $this->Metronic->link($this->Metronic->icon('la la-trash', 'Remover'), [
                'class' => 'm-link m-link--state m-link--danger text-center d-flex justify-content-center m--margin-top-10 m--margin-bottom-10 previewRemoveButton',
                'data-dz-remove' => true,
                'get-url' => '/nit/Documentos/deleteDocumento/' . $value['id'],
            ]);
        }
        $download = $this->Html->link(
            $this->Metronic->icon('download', $value['nome']),
            '/documentos/download/' . $value['nome'],
            ['class' => 'btn default btn-xs mr-0 ml-0 d-flex ', 'escape' => false]
        );
        $img = '/webroot/img/documentos/' . $value['nome'];
        if (substr($value['nome'], -3) == 'pdf') {
            $img = '/webroot/img/pdf.jpg';
        }
        if (substr($value['nome'], -3) == 'csv') {
            $img = '/webroot/img/csv.png';
        }
        if (substr($value['nome'], -4) == 'xlsx') {
            $img = '/webroot/img/xlsx.png';
        }
        $tagImagem = $this->Html->image($img, ['class' => 'img-responsive']);
        $imagePreview .= $this->Html->div(
            'dz-preview col-md-2 dz-image-preview dz-processing dz-success dz-complete',
            $this->Html->div('imageDropzone', $tagImagem) . $remover . $download
        );
        $this->Metronic->buffer("$('.m-dropzone').has('.dz-preview').addClass('dz-started dz-max-files-reached');");
    }
}
echo $this->Html->div(
    $class,
    $this->Html->tag('label', $label) .
    $this->Html->div(
        'm-dropzone dropzone ' . $dropClass,
        $this->Html->div(
            'm-dropzone__msg dz-message needsclick',
            $this->Html->para('m-dropzone-image', $this->Metronic->icon('la	la-cloud-upload', '', ['style' => 'font-size: 4em;'])) .
            $this->Html->para('m-dropzone__msg-title m--font-primary', 'Arraste ou clique aqui para adicionar a foto.') .
            $this->Html->tag('span', 'Somente arquivos .JPG, .PNG ou .PDF', ['class' => 'm-dropzone__msg-desc']),
        ) .
        $imagePreview,
        ['action' => $this->Url->build([
            'controller' => 'documentos',
            'action' => $action,
        ]), 'id' => 'documento-imagem']
    ) .
    $errorMessage . $hint
);
