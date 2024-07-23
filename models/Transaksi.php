<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "transaksi".
 *
 * @property int $idTransaksi
 * @property string $tanggal
 * @property string $kode_transaksi
 * @property float $total
 * @property float $uang_diberikan
 * @property float $uang_kembalian
 *
 * @property TransaksiDetail[] $transaksiDetails
 */
class Transaksi extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaksi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tanggal', 'kode_transaksi', 'total', 'uang_diberikan', 'uang_kembalian'], 'required'],
            [['tanggal'], 'safe'],
            [['total', 'uang_diberikan', 'uang_kembalian'], 'number'],
            [['kode_transaksi'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idTransaksi' => 'ID Transaksi',
            'tanggal' => 'Tanggal',
            'kode_transaksi' => 'Kode Transaksi',
            'total' => 'Total',
            'uang_diberikan' => 'Uang Diberikan',
            'uang_kembalian' => 'Uang Kembalian',
        ];
    }

    /**
     * Gets query for [[TransaksiDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class, ['idTransaksi' => 'idTransaksi']);
    }

    private function generateKodeTransaksi()
{
    // Generate a unique kode_transaksi, e.g., "TRX-YYYYMMDD-XXX"
    $prefix = 'TRX-' . date('Ymd') . '-';
    $lastTransaksi = Transaksi::find()
        ->where(['like', 'kode_transaksi', $prefix])
        ->orderBy(['idTransaksi' => SORT_DESC])
        ->one();
    $lastNumber = $lastTransaksi ? intval(substr($lastTransaksi->kode_transaksi, -3)) : 0;
    $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    $kodeTransaksi = $prefix . $nextNumber;
    
    // Debugging line
    Yii::info("Generated Kode Transaksi: " . $kodeTransaksi, __METHOD__);

    return $kodeTransaksi;
}

}
