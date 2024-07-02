<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "transaksi_detail".
 *
 * @property int $id
 * @property int $idTransaksi
 * @property int $idProduk
 * @property int $jumlah
 * @property float $harga
 *
 * @property Transaksi $transaksi
 * @property Produk $produk
 */
class TransaksiDetail extends ActiveRecord
{
    public static function tableName()
    {
        return 'transaksi_detail';
    }

    public function rules()
    {
        return [
            [['idTransaksi', 'idProduk', 'jumlah', 'harga'], 'required'],
            [['idTransaksi', 'idProduk', 'jumlah'], 'integer'],
            [['harga'], 'number'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idTransaksi' => 'ID Transaksi',
            'idProduk' => 'ID Produk',
            'jumlah' => 'Jumlah',
            'harga' => 'Harga',
        ];
    }

    public function getTransaksi()
    {
        return $this->hasOne(Transaksi::class, ['idTransaksi' => 'idTransaksi']);
    }

    public function getProduk()
    {
        return $this->hasOne(Produk::class, ['idProduk' => 'idProduk']);
    }
}
