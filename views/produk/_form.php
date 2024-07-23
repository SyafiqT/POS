<?php

// views/produk/_form.php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Produk */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="produk-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'harga')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stok')->textInput() ?>

    <?= $form->field($model, 'idKategori')->dropDownList(
        \yii\helpers\ArrayHelper::map(\app\models\Kategori::find()->all(), 'idKategori', 'NamaKategori'),
        ['prompt' => 'Select Kategori']
    ) ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
