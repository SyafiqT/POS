<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TransaksiDetail $model */

$this->title = 'Create Transaksi Detail';
$this->params['breadcrumbs'][] = ['label' => 'Transaksi Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaksi-detail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
