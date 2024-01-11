<?php

use backend\models\AuthAssignment;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if(Yii::$app->user->can('add_user') || Yii::$app->user->identity->username === 'admin'){
            echo Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']);
        } ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
//            'auth_key',
//            'password_hash',
//            'password_reset_token',
            'email:email',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->status == 10 ? 'Active' : 'Non Active';
                }
            ],
            [
                'attribute' => 'auth_item',
                'label' => 'Role',
                'value' => function ($model) {
                    return AuthAssignment::findOne(['user_id' => $model->id])->item_name ?? '-';
                }
            ],
            //'created_at',
            //'updated_at',
            //'verification_token',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, User $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'id' => $model->id]);
//                }
//            ],
            [
                'class' => ActionColumn::class,
                'template' => '{view} {update} {delete}', // Adjust the template based on your needs
                'buttons' => [
                    'view' => function ($url, $model) {
                        // Check if the user has 'view' permission
                        if (Yii::$app->user->can('view_user') || Yii::$app->user->identity->username === 'admin') {
                            return '<a href="' . Url::toRoute(['view', 'id' => $model->id]) . '"><button class="btn btn-info">View</button></a>';
                        }
                        return '';
                    },
                    'update' => function ($url, $model) {
                        // Check if the user has 'update' permission
                        if (Yii::$app->user->can('edit_user') || Yii::$app->user->identity->username === 'admin') {
                            return '<a href="' . Url::toRoute(['update', 'id' => $model->id]) . '"><button class="btn btn-warning">Edit</button></a>';
                        }
                        return '';
                    },
                    'delete' => function ($url, $model) {
                        // Check if the user has 'delete' permission
                        if ((Yii::$app->user->can('delete_user') || Yii::$app->user->identity->username === 'admin') && $model->username !== 'admin' && $model->id !== Yii::$app->user->id) {
                            $confirmationMessage = 'Are you sure you want to delete this item?';
                            $js = <<<JS
                                function confirmDelete() {
                                    return confirm("$confirmationMessage");
                                }
                            JS;
                            $this->registerJs($js, \yii\web\View::POS_HEAD);

                            return '<div class="btn-group">'
                                . Html::beginForm(['delete', 'id' => $model->id], 'post', ['onsubmit' => 'return confirmDelete();'])
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
