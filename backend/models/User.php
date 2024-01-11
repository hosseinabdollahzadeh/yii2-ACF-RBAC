<?php

namespace backend\models;

use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $verification_token
 */
class User extends \yii\db\ActiveRecord
{
    public $auth_item;
    public $password;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'email', 'auth_item'], 'required', 'on' => 'new_user'],
            [['username', 'email', 'auth_item'], 'required', 'on' => 'edit_user'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'verification_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['email'], 'email'],
            [['username', 'email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['verification_token', 'password'], 'safe', 'on' => 'edit_user'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password' => Yii::t('app', 'Password'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'verification_token' => Yii::t('app', 'Verification Token'),
        ];
    }

    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::class, ['user_id' => 'id']);
    }

    public function getItemNames()
    {
        return $this->hasMany(Role::class, ['name' => 'item_name'])->viaTable('auth_assignment', ['user_id' => 'id']);
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $user = new \common\models\User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->status = $this->status == 1 ? 10 : 0;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        if ($user->save()) {
            $this->id = $user->id;
            if ($this->save_assignment()) {
                return true;
            }
        }
        return false;
    }

    private function save_assignment()
    {
        $assignment = new AuthAssignment();
        $assignment->user_id = $this->id;
        $assignment->item_name = $this->auth_item;
        if ($assignment->save()) {
            return true;
        }
        return false;
    }

    public function update($runValidation = true, $attributeNames = null)
    {
        if (!empty($this->password)) {
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        }

        $userRole = AuthAssignment::findOne(['user_id' => $this->id]);
        if ($userRole != null) {
            $userRole->item_name = $this->auth_item;
            $userRole->update();
        } else {
            $this->save_assignment();
        }

        $this->status = $this->status == 1 ? 10 : 0;
        return parent::update();
    }

}
