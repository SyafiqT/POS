<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\TransaksiDetail $model */

$this->title = $model->idDetail;
$this->params['breadcrumbs'][] = ['label' => 'Transaksi Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="transaksi-detail-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'idDetail' => $model->idDetail], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'idDetail' => $model->idDetail], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idDetail',
            'idTransaksi',
            'idProduk',
            'jumlah',
            'harga',
        ],
    ]) ?>

</div>
