<?php

use app\models\Kategori;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\KategoriSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Kategoris';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kategori-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Kategori', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idKategori',
            'NamaKategori',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Kategori $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'idKategori' => $model->idKategori]);
                 }
            ],
        ],
    ]); ?>


</div>
