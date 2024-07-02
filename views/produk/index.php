<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProdukSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Produk';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="produk-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Produk', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idProduk',
            'nama',
            'harga',
            'stok',
            [
                'attribute' => 'idKategori',
                'value' => function ($model) {
                    return $model->kategori ? $model->kategori->NamaKategori : 'Not set';
                },
                'label' => 'Kategori',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to([$action, 'idProduk' => $model->idProduk]);
                },
            ],
        ],
    ]); ?>

</div>
