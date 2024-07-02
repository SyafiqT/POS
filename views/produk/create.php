<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Produk $model */

$this->title = 'Create Produk';
$this->params['breadcrumbs'][] = ['label' => 'Produks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="produk-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
