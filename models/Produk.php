<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Produk extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%produk}}';
    }

    public function rules()
    {
        return [
            [['nama', 'harga', 'idKategori', 'stok'], 'required'],
            [['harga'], 'number'],
            [['idKategori', 'stok'], 'integer'],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'idProduk' => 'ID Produk',
            'nama' => 'Nama',
            'harga' => 'Harga',
            'idKategori' => 'Kategori',
            'stok' => 'Stok',
        ];
    }

    public function getKategori()
    {
        return $this->hasOne(Kategori::className(), ['idKategori' => 'idKategori']);
    }
}
