<?php
Yii::import("application.modules.page.PageModule");
Yii::import("application.modules.page.models.Page");

class PageSlide extends Slide
{
    /**
     * Совпадает с префиксом подкласса слайда и именем класса сущности
     */
    const TYPE_OF_SLIDE = 'Page';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return PageSlide the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     *
     * @param string $scenario
     */
    public function __construct($scenario = 'insert')
    {
        // устанавливаем наш тип полю базового класса
        $this->type_of_slide = self::TYPE_OF_SLIDE;
        parent::__construct($scenario);
    }

    /**
     * Ссылка на нашего специфического владельца
     * @return array
     */
    public function relations()
    {
        return array_merge(parent::relations(), [
            'entity' => [self::BELONGS_TO, self::TYPE_OF_SLIDE, 'entity_id'],
        ]);
    }
}