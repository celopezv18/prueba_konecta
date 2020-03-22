<?php

namespace app\models;

use Yii;

class Productos extends \yii\db\ActiveRecord
{
    //const SCENARIO_CREATE = 'create';
	/**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'productos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['referencia', 'precio', 'peso', 'categoria', 'stock', 'fecha_creacion', 'nombre'], 'required'],
            [['precio', 'peso', 'categoria', 'stock'], 'integer'],
            [['fecha_creacion', 'fecha_venta'], 'safe'],
            [['referencia'], 'string', 'max' => 50],
            [['nombre'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'referencia' => 'Referencia',
            'precio' => 'Precio',
            'peso' => 'Peso',
            'categoria' => 'Categoria',
            'stock' => 'Stock',
            'fecha_creacion' => 'Fecha Creacion',
            'fecha_venta' => 'Fecha Venta',
            'nombre' => 'Nombre',
        ];
    }
}
