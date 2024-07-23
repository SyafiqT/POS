<?php

// views/produk/view.php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Produk $model */

$this->title = $model->idProduk;
$this->params['breadcrumbs'][] = ['label' => 'Produk', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="produk-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'idProduk' => $model->idProduk], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'idProduk' => $model->idProduk], [
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
            'idProduk',
            'kode_barang',
            'nama',
            'harga',
            'stok',
            [
                'attribute' => 'idKategori',
                'value' => function ($model) {
                    return $model->kategori ? $model->kategori->NamaKategori : 'Not set';
                },
            ],
            [
                'attribute' => 'gambar_barang',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::img(Yii::getAlias('@web/') . $model->gambar_barang, ['width' => '100px']);
                },
            ],
        ],
    ]) ?>

</div>
