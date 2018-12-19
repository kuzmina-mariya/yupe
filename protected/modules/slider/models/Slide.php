<?php
/**
 * Slide основная модель для слайда
 *
 */

/**
 * This is the model class for table "Slide".
 *
 * The followings are the available columns in table 'Slide':
 * @property integer $id
 * @property string $entity_module_name
 * @property string $entity_name
 * @property integer $entity_id
 * @property string $title
 * @property string $link
 * @property string $full_text
 * @property string $image
 * @property integer $status
 * @property integer $sort
 * @property string $create_time
 * @property string $update_time
 */
class Slide extends yupe\models\YModel
{
    /**
     * @var string
     */
    public $type_of_slide = '';

    /**
     *
     */
    const STATUS_DRAFT = 0;
    /**
     *
     */
    const STATUS_PUBLISHED = 1;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{slider_slide}}';
    }

    /**
     * Для автовыбора классов при поиске
     *
     * @param array $attributes
     * @return mixed
     */
    protected function instantiate($attributes)
    {
        // Класс выбирается по полю entity_name
        $class = (key_exists('entity_name', $attributes) ? $attributes['entity_name'] : '') . 'Slide';
        $model = new $class(null);
        return $model;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return Slide the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['entity_id', 'required'],

            // закрываем поля
            ['entity_module_name, entity_name, entity_id', 'unsafe', 'on' => 'insert'],

            ['entity_module_name, entity_name, title, link, full_text', 'filter', 'filter' => 'trim'],
            ['entity_module_name, entity_name, title, link', 'filter', 'filter' => 'strip_tags'],

            ['entity_id, sort, status', 'numerical', 'integerOnly' => true],
            ['title, link', 'length', 'max' => 250],
            ['status', 'in', 'range' => array_keys($this->statusList)],
            ['id, title, link, full_text, status', 'safe', 'on' => 'search'],
        ];
    }

    /**
     *
     * @return array
     */
    public function behaviors()
    {
        $module = Yii::app()->getModule('slider');
        return [
            'CTimestampBehavior' => [
                'class' => 'zii.behaviors.CTimestampBehavior',
                'setUpdateOnCreate' => true,
            ],
            'imageUpload' => [
                'class' => 'yupe\components\behaviors\ImageUploadBehavior',
                'attributeName' => 'image',
                'requiredOn' => 'insert,update',
                'minSize' => $module->minSize,
                'maxSize' => $module->maxSize,
                'types' => $module->allowedExtensions,
                'uploadPath' => $module->uploadPath,
            ],
        ];
    }

    /**
     *
     * @return array
     */
    public function scopes()
    {
        return [
            'published' => [
                'condition' => $this->tableAlias . '.status = :status',
                'params' => [':status' => self::STATUS_PUBLISHED],
            ],
        ];
    }

    // scope
    public function entity($id)
    {
        if ($id) {
            $this->getDbCriteria()->mergeWith([
                'condition' => $this->tableAlias . '.entity_id = :id',
                'params' => [':id' => $id],
            ]);
        }
        return $this;
    }

    // scope
    public function type($type)
    {
        if ($type) {
            $this->getDbCriteria()->mergeWith([
                'condition' => $this->tableAlias . '.entity_name = :type',
                'params' => [':type' => $type],
            ]);
        }
        return $this;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('SliderModule.slider', 'Id'),
            'title' => Yii::t('SliderModule.slider', 'Title'),
            'image' => Yii::t('SliderModule.slider', 'Image'),
            'link' => Yii::t('SliderModule.slider', 'Link'),
            'full_text' => Yii::t('SliderModule.slider', 'Full text'),
            'status' => Yii::t('SliderModule.slider', 'Status'),
            'sort' => Yii::t('SliderModule.slider', 'Item order number'),
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $criteria = new CDbCriteria();

        $criteria->compare('t.id', $this->id);

        if ($this->type_of_slide) {
            $criteria->compare('t.entity_name', $this->type_of_slide);
        } else {
            $criteria->compare('t.entity_name', $this->entity_name);
        }

        $criteria->compare('t.entity_id', $this->entity_id);
        $criteria->compare('t.link', $this->title, true);
        $criteria->compare('t.link', $this->link, true);
        $criteria->compare('t.full_text', $this->full_text, true);
        $criteria->compare('t.status', $this->status);

        return new CActiveDataProvider(get_class($this), [
            'criteria' => $criteria,
            'sort' => ['defaultOrder' => 't.sort'],
        ]);
    }

    /**
     *
     * @return array
     */
    public function getStatusList()
    {
        return [
            self::STATUS_PUBLISHED => Yii::t('SliderModule.slider', 'Published'),
            self::STATUS_DRAFT => Yii::t('SliderModule.slider', 'Draft'),
        ];
    }

    /**
     *
     * @return string
     */
    public function getStatus()
    {
        $data = $this->getStatusList();
        return isset($data[$this->status]) ? $data[$this->status] : Yii::t('SliderModule.slider', '*unknown*');
    }

    // Переопределяем для поиска по типу

    /**
     * @param string $condition
     * @param array $params
     * @return Slide
     */
    public function find($condition = '', $params = [])
    {
        $this->type($this->type_of_slide);
        return parent::find($condition, $params);
    }

    /**
     * @param string $condition
     * @param array $params
     * @return Slide[]
     */
    public function findAll($condition = '', $params = [])
    {
        $this->type($this->type_of_slide);
        return parent::findAll($condition, $params);
    }

    /**
     * @param array $attributes
     * @param string $condition
     * @param array $params
     * @return Slide[]
     */
    public function findAllByAttributes($attributes, $condition = '', $params = [])
    {
        $this->type($this->type_of_slide);
        return parent::findAllByAttributes($attributes, $condition, $params);
    }

    /**
     * @param string $condition
     * @param array $params
     * @return string
     */
    public function count($condition = '', $params = [])
    {
        $this->type($this->type_of_slide);
        return parent::count($condition, $params);
    }

    /**
     *
     * @return boolean
     */
    protected function beforeSave()
    {
        $this->initType();

        if (!$this->entity_name) {
            return false;
        }

        return parent::beforeSave();
    }

    /**
     *
     */
    protected function initType()
    {
        if (!$this->entity_name) {
            $this->entity_name = $this->type_of_slide;
        }
    }
}