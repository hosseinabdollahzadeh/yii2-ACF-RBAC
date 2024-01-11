<?php

use backend\models\Role;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?php if (Yii::$app->user->id !== $model->id) {
        echo $form->field($model, 'status')->checkbox(['checked' => $model->status === 10]);
    } else {
        // Add a hidden input with the previous value
        echo $form->field($model, 'status')->hiddenInput(['value' => 1])->label(false);

    }
    ?>


    <div class="form-group field-user-auth_item">
        <label class="control-label" for="user-auth_item">Role</label>
        <?php
        echo Html::activeDropDownList($model, 'auth_item', ArrayHelper::map(Role::findAll(['type' => 1]), 'name', 'name'), ['class' => 'form-control']);
        ?>
        <div class="help-block"></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
