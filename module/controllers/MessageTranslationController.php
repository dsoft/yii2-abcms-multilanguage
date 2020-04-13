<?php

namespace abcms\multilanguage\module\controllers;

use Yii;
use abcms\multilanguage\models\MessageTranslation;
use abcms\multilanguage\module\models\MessageTranslationSearch;
use abcms\library\base\AdminController;
use yii\web\NotFoundHttpException;
use abcms\multilanguage\models\MessageSource;

/**
 * MessageTranslationController implements the CRUD actions for MessageTranslation model.
 */
class MessageTranslationController extends AdminController
{

    /**
     * Lists all MessageTranslation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MessageTranslationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MessageTranslation model.
     * @param integer $id
     * @param string $language
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $language)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $language),
        ]);
    }

    /**
     * Creates a new MessageTranslation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $source = $this->findSourceModel($id);
        $model = new MessageTranslation(['id' => $source->id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['message/view', 'id' => $source->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'source' => $source,
        ]);
    }

    /**
     * Updates an existing MessageTranslation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $language
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $language)
    {
        $model = $this->findModel($id, $language);
        $source = $this->findSourceModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['message/view', 'id' => $source->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'source' => $source
        ]);
    }

    /**
     * Deletes an existing MessageTranslation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param string $language
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $language)
    {
        $this->findModel($id, $language)->delete();

        return $this->redirect(['message/view', 'id' => $id]);
    }
    
    /**
     * Finds the MessageSource model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MessageSource the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findSourceModel($id)
    {
        if (($model = MessageSource::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('abcms.multilanguage', 'The requested page does not exist.'));
    }

    /**
     * Finds the MessageTranslation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $language
     * @return MessageTranslation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $language)
    {
        if (($model = MessageTranslation::findOne(['id' => $id, 'language' => $language])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('abcms.multilanguage', 'The requested page does not exist.'));
    }
}
