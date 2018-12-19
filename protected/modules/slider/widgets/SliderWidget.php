<?php
/**
 * Виджет вывода слайдов
 *
 **/
Yii::import('application.modules.slider.models.*');

class SliderWidget extends yupe\widgets\YWidget
{
    /**
     * @var array
     */
    public $slides = [];
    /**
     * @var string
     */
    public $view = 'default';

    /**
     *
     */
    public function run()
    {
        if (empty($this->slides)) {
            return;
        }

        $this->render($this->view, ['models' => $this->slides]);
    }
}