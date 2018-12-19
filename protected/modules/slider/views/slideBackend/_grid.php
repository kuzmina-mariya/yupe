<?php
/* @var $model Slide */
?>

<?php
$this->widget(
    'yupe\widgets\CustomGridView',
    [
        'id' => 'slide-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'actionsButtons' => [
            CHtml::link(
                Yii::t('YupeModule.yupe', 'Add'),
                ['/slider/slideBackend/create', 'type' => $model->type_of_slide, 'entity_id' => $model->entity_id],
                ['class' => 'btn btn-success pull-right btn-sm']
            )
        ],
        'columns' => [
            [
                'name' => 'image',
                'type' => 'raw',
                'value' => '$data->image ? CHtml::image($data->getImageUrl(150), $data->title)  : ""',
                'htmlOptions' => ['style' => 'width:150px'],
                'filter' => false,
                'sortable' => false
            ],
            [
                'name' => 'title',
                'class' => 'bootstrap.widgets.TbEditableColumn',
                'filter' => CHtml::activeTextField($model, 'title', ['class' => 'form-control']),
                'editable' => [
                    'url' => $this->createUrl('inline'),
                    'mode' => 'inline',
                    'params' => [
                        Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken
                    ]
                ],
            ],
            [
                'name' => 'link',
                'class' => 'bootstrap.widgets.TbEditableColumn',
                'filter' => CHtml::activeTextField($model, 'link', ['class' => 'form-control']),
                'editable' => [
                    'url' => $this->createUrl('inline'),
                    'mode' => 'inline',
                    'params' => [
                        Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken
                    ]
                ],
            ],
            [
                'name' => 'sort',
                'class' => 'bootstrap.widgets.TbEditableColumn',
                'htmlOptions' => array('style' => 'width:80px'),
                'editable' => array(
                    'url' => $this->createUrl('inline'),
                    'mode' => 'inline',
                    'params' => [
                        Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken
                    ],
                    'placement' => 'right',
                    'success' => 'js: function(response, newValue) {
                        $.fn.yiiGridView.update("slide-grid");
                    }'
                ),
                'filter' => CHtml::activeTextField($model, 'sort', ['class' => 'form-control']),
            ],
            [
                'class' => 'yupe\widgets\EditableStatusColumn',
                'name' => 'status',
                'url' => $this->createUrl('inline'),
                'source' => $model->getStatusList(),
                'options' => [
                    Slide::STATUS_PUBLISHED => ['class' => 'label-success'],
                    Page::STATUS_DRAFT => ['class' => 'label-default'],
                ],
            ],
            [
                'class' => 'yupe\widgets\CustomButtonColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]
);
?>