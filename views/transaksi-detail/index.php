<?php

use app\models\TransaksiDetail;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Transaksi Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaksi-detail-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Transaksi Detail', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idDetail',
            'idTransaksi',
            'idProduk',
            'jumlah',
            'harga',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, TransaksiDetail $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'idDetail' => $model->idDetail]);
                 }
            ],
        ],
    ]); ?>


</div>
