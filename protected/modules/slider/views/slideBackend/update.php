<?php
/* @var $model Slide */

$this->breadcrumbs = [
    Yii::t('SliderModule.slider', 'Slides') => ['/slider/slideBackend/index', 'type' => $model->type_of_slide, 'entity_id' => $model->entity_id],
    Yii::t('SliderModule.slider', 'Edit'),
];

$this->pageTitle = Yii::t('SliderModule.slider', 'Slides - edit');

$this->menu = $this->getModule()->getModelNavigation($model);;
?>
    <div class="page-header">
        <h1>
            <?= Yii::t('SliderModule.slider', 'Edit slide'); ?><br/>
            <small>&laquo;<?= $model->title; ?>&raquo;</small>
        </h1>
    </div>

<?= $this->renderPartial('_form', ['model' => $model]); ?>