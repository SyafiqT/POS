<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Transaksi */
/* @var $products array */
/* @var $total float */

$this->title = 'Cetak Struk';
?>

<div class="print-struk">

    <h1>Struk Transaksi</h1>

    <p>Kode Transaksi: <?= Html::encode($model->kode_transaksi) ?></p>
    <p>Tanggal: <?= Html::encode($model->tanggal) ?></p>
    <p>Total: <?= Html::encode($model->total) ?></p>

    <h3>Detail Produk</h3>
    <ul>
        <?php foreach ($products as $product): ?>
            <?php $produk = \app\models\Produk::findOne($product['idProduk']); ?>
            <li><?= Html::encode($produk->nama) ?> - <?= Html::encode($product['jumlah']) ?> x <?= Html::encode($product['harga']) ?> = <?= Html::encode($product['jumlah'] * $product['harga']) ?></li>
        <?php endforeach; ?>
    </ul>

    <p>Uang Diberikan: <?= Html::encode(Yii::$app->request->post('uang_diberikan', 0)) ?></p>
    <p>Uang Kembalian: <?= Html::encode($model->uang_kembalian) ?></p>

</div>
