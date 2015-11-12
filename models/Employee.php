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
    const SPLITER = "C139A";
    const IDSPLITTER = "9138B";
    const TIMELIMIT = 86400;
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
            [['branch_id', 'name', 'password'], 'required'],
            [['branch_id'], 'integer'],
            [['password'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 100],

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

    public function beforeSave($insert)
    {
        $this->update_time = date('Y-m-d H:i:s');
        return parent::beforeSave($insert);
    }

    public static function encodePassword($password, $salt)
    {
        return base64_encode($password.$salt);
    }

    public function getToken()
    {
        $key = self::encodePassword($this->password, time());
        $key = substr($key, count($key) - 10);
        return $key.self::SPLITER.time().SELF::IDSPLITTER.$this->id;
    }

    public static function findIdentityByAccessToken($token)
    {
        $data = explode(self::IDSPLITTER, $token);

         if( count($data) != 2)
            return null;
        $id = $data[1];
        $key = $data[0];
        $data = explode(self::SPLITER, $key);
        if( count($data) != 2)
            return null;
        $key = $data[0];
        $old_time = $data[1];
        $diff_time = (time() - $old_time) ;
        if($diff_time >= self::TIMELIMIT)
            return null;
        //decode from url
        $key = urldecode($key);
        $key_time = $data[1];
        $employee = static::findOne($id);
        $real_key = self::encodePassword($employee->password, $key_time);
        $real_key = substr($real_key, count($real_key) - 10);
        if($real_key != $key)
            return null;
        
        return $employee;
    }

    public static function isValidToken($token)
    {

    }

    
}
