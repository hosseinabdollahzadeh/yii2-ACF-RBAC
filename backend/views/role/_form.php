<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Role $model */
/** @var yii\widgets\ActiveForm $form */
?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-4">
        <div class="role-form">


            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <!--    --><?php //= $form->field($model, 'type')->textInput() ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

            <!--    --><?php //= $form->field($model, 'rule_name')->textInput(['maxlength' => true]) ?>

            <!--    --><?php //= $form->field($model, 'data')->textInput() ?>

            <!--    --><?php //= $form->field($model, 'created_at')->textInput() ?>

            <!--    --><?php //= $form->field($model, 'updated_at')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>


        </div>
    </div>
    <div class="col-md-8">

        <?php
        $roles = $model->getAllRoles();
        foreach ($roles as $k => $v):
            ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= $k ?></h3>
                    <hr>
                </div>
                <div class="panel-body">
                    <?php
                    foreach ($v as $item) {
                        echo Html::checkbox("Items[{$item['name']}]", $item['checked'], ['label' => $item['label']]);
                        echo '&nbsp;&nbsp;&nbsp;';
                    }
                    ?>
                </div>
            </div>
            <hr>
            <br>
        <?php
        endforeach;
        ?>
    </div>
    <div class="row">
        <?php ActiveForm::end(); ?>
