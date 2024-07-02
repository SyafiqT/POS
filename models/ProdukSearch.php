<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Produk;

class ProdukSearch extends Produk
{
    public $kategoriNama;

    public function rules()
    {
        return [
            [['idProduk', 'idKategori', 'stok'], 'integer'],
            [['nama', 'kategoriNama'], 'safe'],
            [['harga'], 'number'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Produk::find();
        $query->joinWith(['kategori']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['kategoriNama'] = [
            'asc' => ['kategori.NamaKategori' => SORT_ASC],
            'desc' => ['kategori.NamaKategori' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idProduk' => $this->idProduk,
            'harga' => $this->harga,
            'idKategori' => $this->idKategori,
            'stok' => $this->stok,
        ]);

        $query->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'kategori.NamaKategori', $this->kategoriNama]);

        return $dataProvider;
    }
}
