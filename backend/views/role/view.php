<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Role $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="role-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (Yii::$app->user->can('edit_role') || Yii::$app->user->identity->username === 'admin') {
            echo Html::a(Yii::t('app', 'Update'), ['update', 'name' => $model->name], ['class' => 'btn btn-primary']);
        } ?>
        <?php if (Yii::$app->user->can('delete_role') || Yii::$app->user->identity->username === 'admin') {
            echo Html::a(Yii::t('app', 'Delete'), ['delete', 'name' => $model->name], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]);
        } ?>
    </p>

    <?= DetailView::widget(['model' => $model,
        'attributes' => [
            'name',
//            'type',
            'description:ntext',
//            'rule_name',
//            'data',
//            'created_at',
//            'updated_at',
        ],
    ]) ?>

</div>
