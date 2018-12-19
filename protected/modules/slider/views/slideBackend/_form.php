<?php
/**
 * @var $this SlideBackendController
 * @var $model Slide
 * @var $form \yupe\widgets\ActiveForm
 */
?>

<?php
/**
 * Отображение для default/_form:
 **/
$form = $this->beginWidget(
    'yupe\widgets\ActiveForm',
    [
        'id' => 'slide-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'htmlOptions' => ['class' => 'well', 'enctype' => 'multipart/form-data'],
    ]
); ?>
<div class="alert alert-info">
    <?= Yii::t('SliderModule.slider', 'Fields with'); ?>
    <span class="required">*</span>
    <?= Yii::t('SliderModule.slider', 'are required.'); ?>
</div>

<?= $form->errorSummary($model); ?>

<div class="row">
    <div class="col-sm-7">
        <?= $form->textFieldGroup(
            $model,
            'title',
            [
                'widgetOptions' => [
                    'htmlOptions' => [
                        'data-original-title' => $model->getAttributeLabel('title'),
                        'data-content' => $model->getAttributeDescription('title')
                    ],
                ],
            ]
        ); ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-7">
        <?= $form->textFieldGroup(
            $model,
            'link',
            [
                'widgetOptions' => [
                    'htmlOptions' => [
                        'data-original-title' => $model->getAttributeLabel('link'),
                        'data-content' => $model->getAttributeDescription('link')
                    ],
                ],
            ]
        ); ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-3">
        <?= $form->dropDownListGroup(
            $model,
            'status',
            [
                'widgetOptions' => [
                    'data' => $model->statusList,
                    'htmlOptions' => [
                        'class' => 'popover-help',
                        'empty' => Yii::t('SliderModule.slider', '--choose--'),
                        'data-original-title' => $model->getAttributeLabel('status'),
                        'data-content' => $model->getAttributeDescription('status'),
                        'data-container' => 'body',
                    ],
                ],
            ]
        ); ?>
    </div>
</div>

<div class='row'>
    <div class="col-sm-7">
        <?= !$model->isNewRecord && $model->image
            ? CHtml::image(
                $model->getImageUrl(150),
                $model->title,
                [
                    'class' => 'preview-image',
                    'style' => !$model->isNewRecord && $model->image ? '' : 'display:none',
                ]
            )
            : '';
        ?>

        <?= $form->fileFieldGroup(
            $model,
            'image',
            [
                'widgetOptions' => [
                    'htmlOptions' => [
                        'onchange' => 'readURL(this);',
                        'style' => 'background-color: inherit;',
                    ],
                ],
            ]
        ); ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 popover-help" data-original-title='<?= $model->getAttributeLabel('full_text'); ?>'
         data-content='<?= $model->getAttributeDescription('full_text'); ?>'>
        <?= $form->labelEx($model, 'full_text'); ?>
        <?php
        $this->widget(
            $this->module->getVisualEditor(),
            [
                'model' => $model,
                'attribute' => 'full_text',
            ]
        );
        ?>
    </div>
</div>

<br/><br/>

<?php
$this->widget(
    'bootstrap.widgets.TbButton',
    [
        'buttonType' => 'submit',
        'context' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('SliderModule.slider', 'Create slide and continue') : Yii::t(
            'SliderModule.slider',
            'Save slide and continue'
        ),
    ]
); ?>

<?php
$this->widget(
    'bootstrap.widgets.TbButton',
    [
        'buttonType' => 'submit',
        'htmlOptions' => ['name' => 'submit-type', 'value' => 'index'],
        'label' => $model->isNewRecord ? Yii::t('SliderModule.slider', 'Create slide and close') : Yii::t(
            'SliderModule.slider',
            'Save slide and close'
        ),
    ]
); ?>

<?php $this->endWidget(); ?>
