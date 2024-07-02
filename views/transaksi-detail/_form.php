<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TransaksiDetail $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="transaksi-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idTransaksi')->textInput() ?>

    <?= $form->field($model, 'idProduk')->textInput() ?>

    <?= $form->field($model, 'jumlah')->textInput() ?>

    <?= $form->field($model, 'harga')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
