<?php
/* @var $model Slide */

switch ($model->type_of_slide) {
    case 'Page':
        $subtitle = 'Страница «' . $model->entity->title . '»';
        break;

    default:
        $subtitle = Yii::t('SliderModule.slider', 'management');
        break;
}

$this->breadcrumbs = [
    Yii::t('SliderModule.slider', 'Slides') => ['/slider/slideBackend/index', 'type' => $model->type_of_slide, 'entity_id' => $model->entity_id],
    Yii::t('SliderModule.slider', 'List'),
];

$this->pageTitle = Yii::t('SliderModule.slider', 'Slides list');

$this->menu = $this->getModule()->getModuleNavigation($model->type_of_slide, $model->entity_id);
?>
    <div class="page-header">
        <h1>
            <?= Yii::t('SliderModule.slider', 'Slides'); ?>
            <small><?= $subtitle; ?></small>
        </h1>
    </div>

<?= $this->renderPartial('_grid', ['model' => $model]); ?>