<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Produk;

/* @var $this yii\web\View */
/* @var $model app\models\Transaksi */
/* @var $details app\models\TransaksiDetail[] */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="transaksi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tanggal')->textInput(['readonly' => true, 'id' => 'transaction-date']) ?>
    <?= $form->field($model, 'kode_transaksi')->textInput(['readonly' => true]) ?>
    <?= $form->field($model, 'total')->textInput(['readonly' => true, 'id' => 'transaction-total']) ?>
    
    <?= $form->field($model, 'uang_diberikan')->textInput(['id' => 'uang-diberikan']) ?>
    <?= $form->field($model, 'uang_kembalian')->textInput(['readonly' => true, 'id' => 'uang-kembalian']) ?>

    <div class="panel panel-default">
        <div class="panel-heading"><h4><i class="glyphicon glyphicon-envelope"></i> Transaksi Details</h4></div>
        <div class="panel-body">
            <table class="table table-bordered" id="product-list">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th><button type="button" class="btn btn-success add-product">Add Product</button></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($details as $i => $detail): ?>
                        <tr>
                            <td>
                                <select class="form-control product-id" name="TransaksiDetail[<?= $i ?>][idProduk]">
                                    <?= Html::renderSelectOptions($detail->idProduk, ArrayHelper::map(Produk::find()->all(), 'idProduk', 'nama')) ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control quantity" name="TransaksiDetail[<?= $i ?>][jumlah]" value="<?= $detail->jumlah ?>" min="1" />
                            </td>
                            <td>
                                <input type="number" class="form-control price" name="TransaksiDetail[<?= $i ?>][harga]" value="<?= $detail->harga ?>" step="0.01" readonly />
                            </td>
                            <td>
                                <input type="number" class="form-control row-total" value="<?= $detail->jumlah * $detail->harga ?>" step="0.01" readonly />
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger remove-product">Remove</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?= Html::hiddenInput('TransactionDetailsJson', '', ['id' => 'transaction-details-json']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$productOptions = ArrayHelper::map(Produk::find()->all(), 'idProduk', 'nama');
$options = "";
foreach ($productOptions as $idProduk => $nama) {
    $options .= "<option value='{$idProduk}'>{$nama}</option>";
}

$this->registerJs(<<<JS
function updateRowTotal(row) {
    var quantity = parseFloat(row.find('.quantity').val());
    var price = parseFloat(row.find('.price').val());
    var rowTotal = quantity * price;
    row.find('.row-total').val(rowTotal.toFixed(2));
    updateTotal();
}

function updateTotal() {
    var total = 0;
    $('#product-list tbody .row-total').each(function () {
        total += parseFloat($(this).val());
    });
    $('#transaction-total').val(total.toFixed(2));
    
    // Update uang kembalian
    var uangDiberikan = parseFloat($('#uang-diberikan').val()) || 0;
    var uangKembalian = uangDiberikan - total;
    $('#uang-kembalian').val(uangKembalian.toFixed(2));
}

$(document).on('click', '.add-product', function () {
    var productRow = `<tr>
        <td>
            <select class="form-control product-id">
                $options
            </select>
        </td>
        <td>
            <input type="number" class="form-control quantity" value="1" min="1" />
        </td>
        <td>
            <input type="number" class="form-control price" value="0" step="0.01" readonly />
        </td>
        <td>
            <input type="number" class="form-control row-total" value="0" step="0.01" readonly />
        </td>
        <td>
            <button type="button" class="btn btn-danger remove-product">Remove</button>
        </td>
    </tr>`;
    $('#product-list tbody').append(productRow);
    updateTotal();
});

$(document).on('click', '.remove-product', function () {
    $(this).closest('tr').remove();
    updateTotal();
});

$(document).on('change', '.product-id', function () {
    var productId = $(this).val();
    var priceInput = $(this).closest('tr').find('.price');
    $.get('/transaksi/get-produk-price', { id: productId }, function (data) {
        priceInput.val(data.price);
        updateRowTotal(priceInput.closest('tr'));
    });
});

$(document).on('change', '.quantity', function () {
    updateRowTotal($(this).closest('tr'));
});

$(document).on('input', '#uang-diberikan', function() {
    updateTotal();
});

function updateDateTime() {
    var now = new Date();
    var formattedDate = now.getFullYear() + '-' +
        ('0' + (now.getMonth() + 1)).slice(-2) + '-' +
        ('0' + now.getDate()).slice(-2) + ' ' +
        ('0' + now.getHours()).slice(-2) + ':' +
        ('0' + now.getMinutes()).slice(-2) + ':' +
        ('0' + now.getSeconds()).slice(-2);
    document.getElementById('transaction-date').value = formattedDate;
}

setInterval(updateDateTime, 1000);
updateDateTime();

$('form').on('beforeSubmit', function() {
    var details = [];
    $('#product-list tbody tr').each(function() {
        var row = $(this);
        details.push({
            idProduk: row.find('.product-id').val(),
            jumlah: row.find('.quantity').val(),
            harga: row.find('.price').val()
        });
    });
    $('#transaction-details-json').val(JSON.stringify(details));
    return true;
});
JS
);
?>
