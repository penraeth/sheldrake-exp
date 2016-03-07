<?php

namespace frontend\controllers;

use Yii;
use app\models\StaringParticipant;
use app\models\StaringParticipantSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StaringParticipantController implements the CRUD actions for StaringParticipant model.
 */
class StaringParticipantController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all StaringParticipant models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StaringParticipantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StaringParticipant model.
     * @param integer $user_id
     * @param integer $exp_id
     * @return mixed
     */
    public function actionView($user_id, $exp_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($user_id, $exp_id),
        ]);
    }

    /**
     * Creates a new StaringParticipant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StaringParticipant();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'user_id' => $model->user_id, 'exp_id' => $model->exp_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing StaringParticipant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $user_id
     * @param integer $exp_id
     * @return mixed
     */
    public function actionUpdate($user_id, $exp_id)
    {
        $model = $this->findModel($user_id, $exp_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'user_id' => $model->user_id, 'exp_id' => $model->exp_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing StaringParticipant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $user_id
     * @param integer $exp_id
     * @return mixed
     */
    public function actionDelete($user_id, $exp_id)
    {
        $this->findModel($user_id, $exp_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StaringParticipant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $user_id
     * @param integer $exp_id
     * @return StaringParticipant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($user_id, $exp_id)
    {
        if (($model = StaringParticipant::findOne(['user_id' => $user_id, 'exp_id' => $exp_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
