<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sale_has_product".
 *
 * @property integer $sale_id
 * @property integer $product_id
 * @property integer $count
 *
 * @property Product $product
 * @property Sale $sale
 */
class SalesOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_has_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sale_id', 'product_id'], 'required'],
            [['sale_id', 'product_id', 'count'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sale_id' => 'Sale ID',
            'product_id' => 'Product ID',
            'count' => 'Count',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSale()
    {
        return $this->hasOne(Sale::className(), ['id' => 'sale_id']);
    }
}
