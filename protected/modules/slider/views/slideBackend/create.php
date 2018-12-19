<?php
/* @var $model Slide */

$this->breadcrumbs = [
    Yii::t('SliderModule.slider', 'Slides') => ['/slider/slideBackend/index', 'type' => $model->type_of_slide, 'entity_id' => $model->entity_id],
    Yii::t('SliderModule.slider', 'Create'),
];

$this->pageTitle = Yii::t('SliderModule.slider', 'Slides - create');

$this->menu = $this->getModule()->getModuleNavigation($model->type_of_slide, $model->entity_id);
?>
    <div class="page-header">
        <h1>
            <?= Yii::t('SliderModule.slider', 'Slides'); ?>
            <small><?= Yii::t('SliderModule.slider', 'create'); ?></small>
        </h1>
    </div>

<?= $this->renderPartial('_form', ['model' => $model]); ?>