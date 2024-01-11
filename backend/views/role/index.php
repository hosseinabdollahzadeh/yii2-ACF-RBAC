<?php

use backend\models\Role;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Roles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (Yii::$app->user->can('add_role') || Yii::$app->user->identity->username === 'admin') {
            echo Html::a(Yii::t('app', 'Create Role'), ['create'], ['class' => 'btn btn-success']);
        } ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
//            'type',
            'description:ntext',
//            'rule_name',
//            'data',
            //'created_at',
            //'updated_at',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, Role $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'name' => $model->name]);
//                }
//            ],
            [
                'class' => ActionColumn::class,
                'template' => '{view} {update} {delete}', // Adjust the template based on your needs
                'buttons' => [
                    'view' => function ($url, $model) {
                        // Check if the user has 'view' permission
                        if (Yii::$app->user->can('view_role') || Yii::$app->user->identity->username === 'admin') {
                            return '<a href="' . Url::toRoute(['view', 'name' => $model->name]) . '"><button class="btn btn-info">View</button></a>';
                        }
                        return '';
                    },
                    'update' => function ($url, $model) {
                        // Check if the user has 'update' permission
                        if (Yii::$app->user->can('edit_role') || Yii::$app->user->identity->username === 'admin') {
                            return '<a href="' . Url::toRoute(['update', 'name' => $model->name]) . '"><button class="btn btn-warning">Edit</button></a>';
                        }
                        return '';
                    },
                    'delete' => function ($url, $model) {
                        // Check if the user has 'delete' permission
                        if ((Yii::$app->user->can('delete_role') || Yii::$app->user->identity->username === 'admin') && $model->name !== 'admin') {
                            $confirmationMessage = 'Are you sure you want to delete this item?';
                            $js = <<<JS
                                function confirmDelete() {
                                    return confirm("$confirmationMessage");
                                }
                            JS;
                            $this->registerJs($js, \yii\web\View::POS_HEAD);

                            return '<div class="btn-group">'
                                . Html::beginForm(['delete', 'name' => $model->name], 'post', ['onsubmit' => 'return confirmDelete();'])
                                . Html::submitButton('Delete', ['class' => 'btn btn-danger'])
                                . Html::endForm()
                                . '</div>';
                        }
                        return '';
                    },
                ],
            ],
        ],
    ]); ?>


</div>
