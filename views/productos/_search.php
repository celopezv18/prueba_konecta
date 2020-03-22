<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProductosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="productos-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'referencia') ?>

    <?= $form->field($model, 'precio') ?>

    <?= $form->field($model, 'peso') ?>

    <?= $form->field($model, 'categoria') ?>

    <?php // echo $form->field($model, 'stock') ?>

    <?php // echo $form->field($model, 'fecha_creacion') ?>

    <?php // echo $form->field($model, 'fecha_venta') ?>

    <?php // echo $form->field($model, 'nombre') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
