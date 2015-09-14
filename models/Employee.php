<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee".
 *
 * @property integer $id
 * @property integer $branch_id
 * @property string $name
 * @property string $surname
 * @property string $password
 * @property string $create_time
 * @property string $update_time
 *
 * @property Branch $branch
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['branch_id', 'name', 'surname', 'password', 'update_time'], 'required'],
            [['branch_id'], 'integer'],
            [['password'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['name', 'surname'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'branch_id' => 'Branch ID',
            'name' => 'Name',
            'surname' => 'Surname',
            'password' => 'Password',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
    }
}
