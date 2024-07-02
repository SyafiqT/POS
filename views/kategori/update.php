<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Kategori $model */

$this->title = 'Update Kategori: ' . $model->idKategori;
$this->params['breadcrumbs'][] = ['label' => 'Kategoris', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idKategori, 'url' => ['view', 'idKategori' => $model->idKategori]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="kategori-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
