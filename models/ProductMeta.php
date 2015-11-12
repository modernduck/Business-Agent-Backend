<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "brand_config".
 *
 * @property integer $brand_id
 * @property string $name
 * @property string $value
 * @property string $create_time
 */
class ProductMeta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_meta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['product_id', 'name', 'value'], 'required'],
           [['product_id'], 'integer'],
           [['create_time'], 'safe'],
           [['name'], 'string', 'max' => 100],
           [[ 'value'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'name' => 'Name',
            'value' => 'Value',
             'create_time' => 'Create Time', 
        ];
    }

    public function getProduct()
   {
       return $this->hasOne(Product::className(), ['id' => 'product_id']);
   }

    public static function get($product_id, $name)
    {
        $config = self::find()->where(['product_id' => $product_id, 'name' => $name])->one();
        if($config == null)
            return null;
        else
            return $config->value;
    }
}
