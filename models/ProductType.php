<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_type".
 *
 * @property integer $id
 * @property string $name
 * @property integer $brand_id
 * @property string $create_time
 * @property string $update_time
 *
 * @property Product[] $products
 * @property Brand $brand
 */
class ProductType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'brand_id'], 'required'],
            [['brand_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 45]
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
            'brand_id' => 'Brand ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*public function getProducts()
    {
        return $this->hasMany(Product::className(), ['product_type_id' => 'id']);
    }*/

      public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('product_has_product_type', ['product_type_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    public function beforeSave($insert)
    {
        $this->update_time = $this->update_time = date('Y-m-d H:i:s');
        if(BrandConfig::get($this->brand_id, 'is_connect_woocommerce'))
        {
            $url = BrandConfig::get($this->brand_id, 'woocommerce_url_2');
            $user = BrandConfig::get($this->brand_id, 'woocommerce_user');
            $password = BrandConfig::get($this->brand_id, 'woocommerce_password');
            $helper = new Woocommerce("{$url}/xmlrpc.php", $user, $password);
            if($insert)
                $helper->ensureCategory($this->name);
            else
            {
                $test = self::findOne($this->id);
                $helper->changeCategoryName($test->name, $this->name);
            }

        }

        return parent::beforeSave($insert);
    }

    public function beforeDelete()
    {
         if(BrandConfig::get($this->brand_id, 'is_connect_woocommerce'))
        {
            $url = BrandConfig::get($this->brand_id, 'woocommerce_url_2');
            $user = BrandConfig::get($this->brand_id, 'woocommerce_user');
            $password = BrandConfig::get($this->brand_id, 'woocommerce_password');
            $helper = new Woocommerce("{$url}/xmlrpc.php", $user, $password);
            $helper->deleteCategory($this->name);
        }
        
        return parent::beforeDelete();
    }

    public static function isCreated($brand_id, $name)
    {
        $type = self::find()->where(['brand_id' => $brand_id, 'name' => $name])->one();
        if($type !== null)
            return true;
        else
            return false;
    }

    
}
