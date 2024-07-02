<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "transaksi".
 *
 * @property int $idTransaksi
 * @property string $tanggal
 * @property float $total
 *
 * @property TransaksiDetail[] $transaksiDetails
 */
class Transaksi extends ActiveRecord
{
    public static function tableName()
    {
        return 'transaksi';
    }

    public function rules()
    {
        return [
            [['tanggal'], 'safe'],
            [['total'], 'number'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'idTransaksi' => 'ID Transaksi',
            'tanggal' => 'Tanggal',
            'total' => 'Total',
        ];
    }

    public function getTransaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class, ['idTransaksi' => 'idTransaksi']);
    }
}
