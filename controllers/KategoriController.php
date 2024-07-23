<?php

namespace app\controllers;

use app\models\Kategori;
use app\models\KategoriSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KategoriController implements the CRUD actions for Kategori model.
 */
class KategoriController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Kategori models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new KategoriSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Kategori model.
     * @param int $idKategori Id Kategori
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($idKategori)
    {
        return $this->render('view', [
            'model' => $this->findModel($idKategori),
        ]);
    }

    /**
     * Creates a new Kategori model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Kategori();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'idKategori' => $model->idKategori]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Kategori model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $idKategori Id Kategori
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($idKategori)
    {
        $model = $this->findModel($idKategori);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'idKategori' => $model->idKategori]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Kategori model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $idKategori Id Kategori
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($idKategori)
    {
        $this->findModel($idKategori)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Kategori model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $idKategori Id Kategori
     * @return Kategori the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($idKategori)
    {
        if (($model = Kategori::findOne($idKategori)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
