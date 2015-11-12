<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "brand_config".
 *
 * @property integer $id
 * @property string $name
 * @property string $value
 */
class BrandConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'value', 'brand_id'], 'required'],
            [['value'], 'string'],
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
            'brand_id' => 'Brand ID',
            'name' => 'name',
            'value' => 'Value',
        ];
    }

    public static function get($brand_id, $name)
    {
        $config = self::find()->where(['brand_id' => $brand_id, 'name' => $name])->one();
        if($config == null)
            return null;
        else
            return $config->value;
    }
}
