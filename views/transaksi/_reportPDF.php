<?php
use yii\helpers\Html;

/* @var $models app\models\Transaksi[] */

?>
<h1>Transaction Report</h1>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Transaction ID</th>
            <th>Total</th>
            <th>Transaction Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($models as $model): ?>
        <tr>
            <td><?= Html::encode($model->idTransaksi) ?></td>
            <td><?= Html::encode($model->total) ?></td>
            <td><?= Html::encode($model->tanggal) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
