<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventory".
 *
 * @property integer $product_id
 * @property integer $branch_id
 * @property integer $count
 *
 * @property Branch $branch
 * @property Product $product
 */
class Inventory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inventory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'branch_id'], 'required'],
            [['product_id', 'branch_id', 'count'], 'integer']
        ];
    }

    public function fields()
    {
        return [
            // field name is the same as the attribute name
            'count',
            'product',
            'product_type' => function($model){
                return $this->product->productType->name;
            },
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'branch_id' => 'Branch ID',
            'count' => 'Count',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }




    public function beforeSave($insert)
    {
        $this->update_time = date('Y-m-d H:i:s');
        return parent::beforeSave($insert);
    }
}
