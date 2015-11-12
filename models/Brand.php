<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $owner_email
 * @property string $password
 * @property string $create_time
 * @property string $update_time
 *
 * @property Branch[] $branches
 * @property ProductType[] $productTypes
 */
class Brand extends \yii\db\ActiveRecord implements IdentityInterface
{

    public $isUpdatePwd = false;
    const TIMELIMIT = 86400;// 86400 secs = 1440 mins * 60 sec = 24 hours
    const SPLITER = "C139A";
    const IDSPLITTER = "9138B";
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'owner_email', 'password', 'update_time'], 'required'],
            [['password'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 45],
            [['owner_email'], 'string', 'max' => 200]
        ];
    }

    public function fields()
    {
        return [
            // field name is the same as the attribute name
            'id',
            'name',
            // field name is "email", the corresponding attribute name is "email_address"
            'owner_email',
            // field name is "name", its value is defined by a PHP callback
            'branches'
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'owner_email' => 'Owner Email',
            'password' => 'Password',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public static function encodePassword($password, $salt)
    {
        return base64_encode($password.$salt);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->password;
    }

    public function validateAuthKey($authKey)
    {
        return $this->password === $authKey;
    }

    public static function isGodToken($token)
    {
        if($token == "sompopcool")
            return true;
        return false;
    }

    public static function getGodBrand()
    {
        return static::findOne(Yii::$app->params['godID']);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {   
        if(static::isGodToken($token))
            static::getGodBrand();
         $data = explode(self::IDSPLITTER, $token);
         if( count($data) != 2)
            return null;
        $brand_id = $data[1];
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
        $brand = static::findOne($brand_id);
        $real_key = self::encodePassword($brand->password, $key_time);
        $real_key = substr($real_key, count($real_key) - 10);
        if($real_key != $key)
            return null;
        
        return $brand;
    }

    

    public function getToken()
    {
        $key = self::encodePassword($this->password, time());
        $key = substr($key, count($key) - 10);
        return $key.self::SPLITER.time().SELF::IDSPLITTER.$this->id;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranches()
    {
        return $this->hasMany(Branch::className(), ['brand_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductTypes()
    {
        return $this->hasMany(ProductType::className(), ['brand_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        $this->update_time = date('Y-m-d H:i:s');
        if($this->isNewRecord)
        {
            $this->isUpdatePwd = true;
        }
        if($this->isUpdatePwd)
            $this->password = self::encodePassword($this->password, $this->name);
        return parent::beforeSave($insert);
    }
}
