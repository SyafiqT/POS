<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Kategori $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="kategori-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'NamaKategori')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'kode_kategori')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
