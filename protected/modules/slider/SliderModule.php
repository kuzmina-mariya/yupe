<?php
/**
 * SliderModule основной класс модуля slider
 */

use yupe\components\WebModule;

/**
 * Class SliderModule
 */
class SliderModule extends WebModule
{
    /**
     *
     */
    const VERSION = '0.1';

    /**
     * @var string
     */
    public $uploadPath = 'slider';
    /**
     * @var string
     */
    public $allowedExtensions = 'jpg,jpeg,png,gif';
    /**
     * @var int
     */
    public $minSize = 0;
    /**
     * @var int
     */
    public $maxSize = 5368709120;
    /**
     * @var int
     */
    public $maxFiles = 1;

    /**
     * показать или нет модуль в панели управления
     *
     * @return bool
     */
    public function getIsShowInAdminMenu()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function getInstall()
    {
        if (parent::getInstall()) {
            @mkdir(Yii::app()->uploadManager->getBasePath() . DIRECTORY_SEPARATOR . $this->uploadPath, 0755);
        }

        return false;
    }

    /**
     * @return array|bool
     */
    public function checkSelf()
    {
        $messages = [];

        $uploadPath = Yii::app()->uploadManager->getBasePath() . DIRECTORY_SEPARATOR . $this->uploadPath;

        if (!is_writable($uploadPath)) {
            $messages[WebModule::CHECK_ERROR][] = [
                'type' => WebModule::CHECK_ERROR,
                'message' => Yii::t(
                    'SliderModule.slider',
                    'Directory "{dir}" is not accessible for write! {link}',
                    [
                        '{dir}' => $uploadPath,
                        '{link}' => CHtml::link(
                            Yii::t('SliderModule.slider', 'Change settings'),
                            [
                                '/yupe/backend/modulesettings/',
                                'module' => 'slider',
                            ]
                        ),
                    ]
                ),
            ];
        }

        return (isset($messages[WebModule::CHECK_ERROR])) ? $messages : true;
    }

    /**
     * @return array
     */
    public function getParamsLabels()
    {
        return [
            'uploadPath' => Yii::t(
                'SliderModule.slider',
                'Uploading files catalog (relatively {path})',
                [
                    '{path}' => Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . Yii::app()->getModule(
                            "yupe"
                        )->uploadPath,
                ]
            ),
            'allowedExtensions' => Yii::t('SliderModule.slider', 'Accepted extensions (separated by comma)'),
            'minSize' => Yii::t('SliderModule.slider', 'Minimum size (in bytes)'),
            'maxSize' => Yii::t('SliderModule.slider', 'Maximum size (in bytes)'),
        ];
    }

    /**
     * @return array
     */
    public function getEditableParams()
    {
        return [
            'uploadPath',
            'allowedExtensions',
            'minSize',
            'maxSize',
        ];
    }

    /**
     * @return array
     */
    public function getEditableParamsGroups()
    {
        return [
            'images' => [
                'label' => Yii::t('SliderModule.slider', 'Images settings'),
                'items' => [
                    'uploadPath',
                    'allowedExtensions',
                    'minSize',
                    'maxSize',
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * @return bool
     */
    public function getIsInstallDefault()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return Yii::t('SliderModule.slider', 'Content');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return Yii::t('SliderModule.slider', 'Slider');
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return Yii::t('SliderModule.slider', 'Module for creating slides');
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return Yii::t('SliderModule.slider', 'oriole');
    }

    /**
     * @return string
     */
    public function getAuthorEmail()
    {
        return Yii::t('SliderModule.slider', 'orriole@yandex.ru');
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return '#';
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return 'fa fa-fw fa-list-alt';
    }

    /**
     * @return string
     */
    public function getAdminPageLink()
    {
        return '#';
    }

    /**
     * @return array
     */
    public function getNavigation()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getModuleNavigation($entityType, $entityId)
    {
        return [
            [
                'icon' => 'fa fa-fw fa-list-alt',
                'label' => Yii::t('SliderModule.slider', 'Slides list'),
                'url' => ['/slider/slideBackend/index', 'type' => $entityType, 'entity_id' => $entityId],
            ],
            [
                'icon' => 'fa fa-fw fa-plus-square',
                'label' => Yii::t('SliderModule.slider', 'Create slide'),
                'url' => ['/slider/slideBackend/create', 'type' => $entityType, 'entity_id' => $entityId],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getModelNavigation($model)
    {
        return
            array_merge(
                $this->getModuleNavigation($model->type_of_slide, $model->entity_id),
                [
                    ['label' => Yii::t('SliderModule.slider', 'Slide') . ' «' . mb_substr($model->title, 0, 32) . '»'],
                    [
                        'icon' => 'fa fa-fw fa-pencil',
                        'label' => Yii::t('SliderModule.slider', 'Edit slide'),
                        'url' => [
                            '/slider/slideBackend/update/',
                            'id' => $model->id,
                        ],
                    ],
                    [
                        'icon' => 'fa fa-fw fa-trash-o',
                        'label' => Yii::t('SliderModule.slider', 'Remove slide'),
                        'url' => '#',
                        'linkOptions' => [
                            'submit' => ['/slider/slideBackend/delete', 'id' => $model->id],
                            'params' => [Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken],
                            'confirm' => Yii::t('SliderModule.slider', 'Do you really want to remove the slide?'),
                            'csrf' => true,
                        ],
                    ],
                ]
            );
    }

    /**
     *
     */
    public function init()
    {
        parent::init();

        $this->setImport(
            [
                'slider.models.*',
            ]
        );
    }

    /**
     * @return array
     */
    public function getAuthItems()
    {
        return [
            [
                'name' => 'Slider.SliderManager',
                'description' => Yii::t('SliderModule.slider', 'Manage slides'),
                'type' => AuthItem::TYPE_TASK,
                'items' => [
                    [
                        'type' => AuthItem::TYPE_OPERATION,
                        'name' => 'Slider.SliderBackend.Create',
                        'description' => Yii::t('SliderModule.slider', 'Creating slides'),
                    ],
                    [
                        'type' => AuthItem::TYPE_OPERATION,
                        'name' => 'Slider.SliderBackend.Delete',
                        'description' => Yii::t('SliderModule.slider', 'Removing slides'),
                    ],
                    [
                        'type' => AuthItem::TYPE_OPERATION,
                        'name' => 'Slider.SliderBackend.Index',
                        'description' => Yii::t('SliderModule.slider', 'List of slides'),
                    ],
                    [
                        'type' => AuthItem::TYPE_OPERATION,
                        'name' => 'Slider.SliderBackend.Update',
                        'description' => Yii::t('SliderModule.slider', 'Editing slides'),
                    ],
                    [
                        'type' => AuthItem::TYPE_OPERATION,
                        'name' => 'Slider.SliderBackend.View',
                        'description' => Yii::t('SliderModule.slider', 'Viewing slides'),
                    ],
                ],
            ],
        ];
    }
}
