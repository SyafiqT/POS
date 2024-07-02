<?php

namespace app\controllers;

use Yii;
use app\models\Produk;
use app\models\ProdukSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ProdukController implements the CRUD actions for Produk model.
 */
class ProdukController extends Controller
{
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

    public function actionIndex()
    {
        $searchModel = new ProdukSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($idProduk)
    {
        return $this->render('view', [
            'model' => $this->findModel($idProduk),
        ]);
    }

    public function actionCreate()
    {
        $model = new Produk();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'idProduk' => $model->idProduk]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($idProduk)
    {
        $model = $this->findModel($idProduk);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'idProduk' => $model->idProduk]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($idProduk)
    {
        $this->findModel($idProduk)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($idProduk)
    {
        if (($model = Produk::findOne($idProduk)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetHarga($idProduk)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $produk = Produk::findOne($idProduk);
        if ($produk !== null) {
            return $produk->harga;
        } else {
            return 'Produk not found.';
        }
    }
}
