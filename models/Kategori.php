<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Kategori extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%kategori}}';
    }

    public function rules()
    {
        return [
            [['NamaKategori', 'kode_kategori'], 'required'],
            [['NamaKategori', 'kode_kategori'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'idKategori' => 'ID Kategori',
            'NamaKategori' => 'Nama Kategori',
            'kode_kategori' => 'Kode Kategori',
        ];
    }
}
