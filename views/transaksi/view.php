<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\TransaksiDetail;
use app\models\Produk;

/** @var yii\web\View $this */
/** @var app\models\Transaksi $model */

$this->title = $model->idTransaksi;
$this->params['breadcrumbs'][] = ['label' => 'Transaksis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="transaksi-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'idTransaksi' => $model->idTransaksi], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'idTransaksi' => $model->idTransaksi], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Print Receipt', ['print-receipt', 'idTransaksi' => $model->idTransaksi], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idTransaksi',
            'kode_transaksi',
            'tanggal',
            'total',
            'uang_diberikan',
            'uang_kembalian',
        ],
    ]) ?>

    <h2>Transaksi Details</h2>
    <?= GridView::widget([
        'dataProvider' => new yii\data\ArrayDataProvider([
            'allModels' => TransaksiDetail::find()->where(['idTransaksi' => $model->idTransaksi])->with('produk')->all(),
            'pagination' => false,
        ]),
        'columns' => [
            [
                'attribute' => 'idProduk',
                'value' => function ($model) {
                    return $model->produk ? $model->produk->nama : 'Unknown';
                },
            ],
            'jumlah',
            'harga',
            [
                'label' => 'Total',
                'value' => function ($model) {
                    return $model->jumlah * $model->harga;
                },
            ],
        ],
    ]) ?>

</div>
