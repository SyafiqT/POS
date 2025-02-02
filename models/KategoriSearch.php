<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Kategori;

/**
 * KategoriSearch represents the model behind the search form of `app\models\Kategori`.
 */
class KategoriSearch extends Kategori
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idKategori'], 'integer'],
            [['kode_kategori'], 'safe'],
            [['NamaKategori'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Kategori::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'idKategori' => $this->idKategori,
        ]);

        $query->andFilterWhere(['like', 'kode_kategori', $this->kode_kategori]);
        $query->andFilterWhere(['like', 'NamaKategori', $this->NamaKategori]);

        return $dataProvider;
    }
}
