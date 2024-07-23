<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class TransaksiSearch extends Transaksi
{
    public $month;

    public function rules()
    {
        return [
            [['idTransaksi'], 'integer'],
            [['total'], 'number'],
            [['tanggal', 'month'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Transaksi::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->month) {
            $query->andWhere(['MONTH(tanggal)' => $this->month]);
        }

        $query->andFilterWhere([
            'idTransaksi' => $this->idTransaksi,
            'total' => $this->total,
        ]);

        $query->andFilterWhere(['like', 'tanggal', $this->tanggal]);

        return $dataProvider;
    }
}
