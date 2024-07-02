<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ProdukSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="produk-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idProduk') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'harga') ?>

    <?= $form->field($model, 'idKategori') ?>

    <?= $form->field($model, 'stok') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
