<?php

namespace backend\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property int $type
 * @property string|null $description
 * @property string|null $rule_name
 * @property resource|null $data
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property Role[] $children
 * @property Role[] $parents
 * @property AuthRule $ruleName
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::class, 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'description' => Yii::t('app', 'Description'),
            'rule_name' => Yii::t('app', 'Rule Name'),
            'data' => Yii::t('app', 'Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[AuthAssignments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::class, ['item_name' => 'name']);
    }

    /**
     * Gets query for [[AuthItemChildren]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::class, ['parent' => 'name']);
    }

    /**
     * Gets query for [[AuthItemChildren0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::class, ['child' => 'name']);
    }

    /**
     * Gets query for [[Children]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Role::class, ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * Gets query for [[Parents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(Role::class, ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    }

    /**
     * Gets query for [[RuleName]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::class, ['name' => 'rule_name']);
    }

    private function all_roles()
    {
        return [
            'User' => [
                ['name' => 'view_user', 'checked' => 0, 'label' => 'View User'],
                ['name' => 'index_user', 'checked' => 0, 'label' => 'Index User'],
                ['name' => 'add_user', 'checked' => 0, 'label' => 'Add User'],
                ['name' => 'edit_user', 'checked' => 0, 'label' => 'Edit User'],
                ['name' => 'delete_user', 'checked' => 0, 'label' => 'Delete User'],
            ],
            'Role' => [
                ['name' => 'view_role', 'checked' => 0, 'label' => 'View Role'],
                ['name' => 'index_role', 'checked' => 0, 'label' => 'Index Role'],
                ['name' => 'add_role', 'checked' => 0, 'label' => 'Add Role'],
                ['name' => 'edit_role', 'checked' => 0, 'label' => 'Edit Role'],
                ['name' => 'delete_role', 'checked' => 0, 'label' => 'Delete Role'],
            ],
        ];
    }

    public function getAllRoles()
    {
        $roles = $this->all_roles();
        if (!$this->isNewRecord) {
            $db_all_roles = (new Query())
                ->select(['child'])
                ->from('auth_item_child')
                ->where(['parent' => $this->name])
                ->all();

            $db_roles = [];
            foreach ($db_all_roles as $k => $v) {
                array_push($db_roles, $v['child']);
            }

            foreach ($roles as $kr => $vr) {
                foreach ($vr as $ki => $item) {
                    if (in_array($item['name'], $db_roles)){
                        $roles[$kr][$ki]['checked'] = 1;
                    }
                }
            }
        }

        return $roles;
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $t = time();

        $sql = "DELETE FROM `auth_item_child` WHERE `parent` = '{$this->name}'";
        Yii::$app->db->createCommand($sql)->query();

        $items = Yii::$app->request->post('Items');
        $sql = "INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('{$this->name}', 1, '{$this->description}', NULL, NULL, $t, $t)";
        Yii::$app->db->createCommand($sql)->query();

        if (!empty($items)) {
            foreach ($items as $k => $v) {

                $sql = "INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('{$k}', 2, '{$k}', NULL, NULL, $t, $t)";
                Yii::$app->db->createCommand($sql)->query();

                $sql = "INSERT IGNORE INTO `auth_item_child` (`parent`, `child`) VALUES ('{$this->name}', '{$k}')";
                Yii::$app->db->createCommand($sql)->query();
            }
        }

        return true;
    }
}
