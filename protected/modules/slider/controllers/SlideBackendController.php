<?php

/**
 * SlideBackendController контроллер для работы со слайдами в панели управления
 *
 */
class SlideBackendController extends yupe\components\controllers\BackController
{
    /**
     *
     * @var integer
     */
    public $entity_id;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @return array
     */
    public function accessRules()
    {
        return [
            ['allow', 'roles' => ['admin']],
            ['allow', 'actions' => ['index'], 'roles' => ['Slider.SlideBackend.Index']],
            ['allow', 'actions' => ['create'], 'roles' => ['Slider.SlideBackend.Create']],
            ['allow', 'actions' => ['update', 'inline'], 'roles' => ['Slider.SlideBackend.Update']],
            ['allow', 'actions' => ['delete', 'multiaction'], 'roles' => ['Slider.SlideBackend.Delete']],
            ['deny']
        ];
    }

    /**
     *
     * @return array
     */
    public function actions()
    {
        return [
            'inline' => [
                'class' => 'yupe\components\actions\YInLineEditAction',
                'model' => $this->type . 'Slide',
                'validAttributes' => ['title', 'link', 'sort', 'status']
            ],
        ];
    }

    /**
     *
     * @param CAction $action
     * @return boolean
     * @throws CHttpException
     */
    protected function beforeAction($action)
    {
        $actions = ['index', 'create'];
        if (in_array($action->id, $actions)) {
            $this->type = Yii::app()->getRequest()->getParam('type');
            $this->entity_id = Yii::app()->getRequest()->getParam('entity_id');
            if (!$this->entity_id) {
                throw new CHttpException(
                    400,
                    'Не установлен ID записи'
                );
            }
            if (!$this->type) {
                throw new CHttpException(
                    400,
                    'Не установлен тип слайдов'
                );
            }
        }
        return parent::beforeAction($action);
    }

    /**
     * Manages all models.
     *
     * @return void
     */
    public function actionIndex()
    {
        $model = $this->createModel();
        $model->setScenario('search');
        $modelName = get_class($model);

        $model->unsetAttributes();

        $model->setAttributes(
            Yii::app()->getRequest()->getParam(
                $modelName,
                []
            )
        );

        $model->entity_id = $this->entity_id;

        $this->render(
            'index',
            [
                'model' => $model,
            ]
        );
    }

    /**
     *
     */
    public function actionCreate()
    {
        $model = $this->createModel();
        $modelName = get_class($model);

        if (isset($_POST[$modelName])) {

            $model->attributes = $_POST[$modelName];
            $this->beforeCreate($model);

            if ($model->save()) {

                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('SliderModule.slider', 'Slide was created!')
                );

                $this->redirect(
                    Yii::app()->getRequest()->getPost('submit-type') == ''
                        ? (array)Yii::app()->getRequest()->getPost(
                        'submit-type', ['create', 'type' => $model->type_of_slide, 'entity_id' => $model->entity_id]
                    )
                        : ['index', 'type' => $model->type_of_slide, 'entity_id' => $model->entity_id]
                );
            }
        }

        $this->render('create', ['model' => $model]);
    }

    /**
     *
     * @param integer $id
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        $modelName = get_class($model);

        if (isset($_POST[$modelName])) {

            $model->attributes = $_POST[$modelName];

            if ($model->save()) {

                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('SliderModule.slider', 'Slide was updated!')
                );

                $this->redirect(
                    Yii::app()->getRequest()->getPost('submit-type') == ''
                        ? (array)Yii::app()->getRequest()->getPost(
                        'submit-type', ['update', 'id' => $model->id]
                    )
                        : ['index', 'type' => $model->type_of_slide, 'entity_id' => $model->entity_id]
                );
            }
        }

        $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page
     *
     * @param int $id - record ID
     *
     * @return void
     *
     * @throws CHttpException
     */
    public function actionDelete($id = null)
    {
        if (Yii::app()->getRequest()->getIsPostRequest()) {

            $model = $this->loadModel($id);

            $returnUrl = (array)Yii::app()->getRequest()->getPost(
                'returnUrl',
                ['index', 'type' => $model->type_of_slide, 'entity_id' => $model->entity_id]
            );

            $model->delete();

            Yii::app()->getUser()->setFlash(
                yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                Yii::t('SliderModule.slider', 'Slide was removed!')
            );

            Yii::app()->getRequest()->getParam('ajax') !== null || $this->redirect($returnUrl);
        } else {
            throw new CHttpException(
                404,
                Yii::t('SliderModule.slider', 'Bad request. Please don\'t repeat similar requests anymore!')
            );
        }
    }

    /**
     *
     * @param Slide $model
     */
    public function beforeCreate($model)
    {
        $criteria = new CDbCriteria;
        $criteria->select = new CDbExpression('MAX(sort) as sort');
        $criteria->condition = 't.entity_name = :type AND t.entity_id = :entity_id';
        $criteria->params = [
            ':type' => $model->type_of_slide,
            ':entity_id' => $model->entity_id,
        ];
        $max = $model->find($criteria);
        $model->sort = $max->sort + 100;
    }

    /**
     *
     * @return Slide
     */
    public function createModel()
    {
        $className = $this->type . 'Slide';
        $model = new $className();
        $model->entity_id = $this->entity_id;

        if (!$model->entity) {
            throw new CHttpException(
                404,
                'Сущность не найдена'
            );
        }

        return $model;
    }

    /**
     *
     * @param integer $id
     * @return Slide
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $className = $this->type . 'Slide';
        if (($model = CActiveRecord::model($className)->findByPk($id)) === null) {
            throw new CHttpException(
                404,
                'Страница не найдена'
            );
        }

        return $model;
    }
}