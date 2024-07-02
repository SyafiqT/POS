<?php

namespace app\controllers;

use Yii;
use app\models\Transaksi;
use app\models\TransaksiDetail;
use app\models\Produk;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Model;
use yii\db\Exception;
use app\helpers\ModelHelper;
use yii\helpers\ArrayHelper;

/**
 * TransaksiController implements the CRUD actions for Transaksi model.
 */
class TransaksiController extends Controller
{
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

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Transaksi::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($idTransaksi)
    {
        return $this->render('view', [
            'model' => $this->findModel($idTransaksi),
        ]);
    }

    public function actionCreate()
{
    $model = new Transaksi();
    $details = [new TransaksiDetail()];

    if ($model->load(Yii::$app->request->post())) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->tanggal = date('Y-m-d H:i:s');
            if ($model->save()) {
                $detailsData = json_decode(Yii::$app->request->post('TransactionDetailsJson'), true);
                foreach ($detailsData as $detailData) {
                    $detail = new TransaksiDetail();
                    $detail->idTransaksi = $model->idTransaksi;
                    $detail->idProduk = $detailData['idProduk'];
                    $detail->jumlah = $detailData['jumlah'];
                    $detail->harga = $detailData['harga'];
                    if (!$detail->save()) {
                        throw new \Exception('Failed to save transaction detail.');
                    }
                    $produk = Produk::findOne($detail->idProduk);
                    $produk->stok -= $detail->jumlah;
                    if (!$produk->save()) {
                        throw new \Exception('Failed to update product stock.');
                    }
                }
                $transaction->commit();
                return $this->redirect(['view', 'idTransaksi' => $model->idTransaksi]);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

    return $this->render('create', [
        'model' => $model,
        'details' => $details,
    ]);
}


public function actionUpdate($idTransaksi)
{
    $model = $this->findModel($idTransaksi);
    $details = $model->transaksiDetails;

    if ($model->load(Yii::$app->request->post())) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->save()) {
                TransaksiDetail::deleteAll(['idTransaksi' => $model->idTransaksi]);
                $detailsData = json_decode(Yii::$app->request->post('TransactionDetailsJson'), true);
                foreach ($detailsData as $detailData) {
                    $detail = new TransaksiDetail();
                    $detail->idTransaksi = $model->idTransaksi;
                    $detail->idProduk = $detailData['idProduk'];
                    $detail->jumlah = $detailData['jumlah'];
                    $detail->harga = $detailData['harga'];
                    if (!$detail->save()) {
                        throw new \Exception('Failed to save transaction detail.');
                    }
                    $produk = Produk::findOne($detail->idProduk);
                    $produk->stok -= $detail->jumlah;
                    if (!$produk->save()) {
                        throw new \Exception('Failed to update product stock.');
                    }
                }
                $transaction->commit();
                return $this->redirect(['view', 'idTransaksi' => $model->idTransaksi]);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

    return $this->render('update', [
        'model' => $model,
        'details' => $details,
    ]);
}


    public function actionDelete($idTransaksi)
    {
        $this->findModel($idTransaksi)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($idTransaksi)
    {
        if (($model = Transaksi::findOne(['idTransaksi' => $idTransaksi])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetProdukPrice($id)
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $produk = Produk::findOne($id);
    if ($produk) {
        return ['price' => $produk->harga];
    }
    return ['price' => 0];
}


}
