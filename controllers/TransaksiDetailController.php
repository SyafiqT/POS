<?php

namespace app\controllers;

use app\models\TransaksiDetail;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TransaksiDetailController implements the CRUD actions for TransaksiDetail model.
 */
class TransaksiDetailController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all TransaksiDetail models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => TransaksiDetail::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'idDetail' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TransaksiDetail model.
     * @param int $idDetail ID Detail
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($idDetail)
    {
        return $this->render('view', [
            'model' => $this->findModel($idDetail),
        ]);
    }

    /**
     * Creates a new TransaksiDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new TransaksiDetail();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'idDetail' => $model->idDetail]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TransaksiDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $idDetail ID Detail
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($idDetail)
    {
        $model = $this->findModel($idDetail);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'idDetail' => $model->idDetail]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TransaksiDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $idDetail ID Detail
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($idDetail)
    {
        $this->findModel($idDetail)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TransaksiDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $idDetail ID Detail
     * @return TransaksiDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($idDetail)
    {
        if (($model = TransaksiDetail::findOne(['idDetail' => $idDetail])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
