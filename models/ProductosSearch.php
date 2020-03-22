<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Productos;

/**
 * ProductosSearch represents the model behind the search form of `app\models\Productos`.
 */
class ProductosSearch extends Productos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'precio', 'peso', 'categoria', 'stock'], 'integer'],
            [['referencia', 'fecha_creacion', 'fecha_venta', 'nombre'], 'safe'],
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
        $query = Productos::find();

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
            'id' => $this->id,
            'precio' => $this->precio,
            'peso' => $this->peso,
            'categoria' => $this->categoria,
            'stock' => $this->stock,
            'fecha_creacion' => $this->fecha_creacion,
            'fecha_venta' => $this->fecha_venta,
        ]);

        $query->andFilterWhere(['like', 'referencia', $this->referencia])
            ->andFilterWhere(['like', 'nombre', $this->nombre]);

        return $dataProvider;
    }
}
