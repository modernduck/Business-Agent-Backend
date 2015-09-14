<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sale".
 *
 * @property integer $id
 * @property integer $branch_id
 * @property string $create_time
 *
 * @property Branch $branch
 * @property SaleHasProduct[] $saleHasProducts
 * @property Product[] $products
 */
class Sale extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['branch_id'], 'required'],
            [['branch_id'], 'integer'],
            [['create_time'], 'safe']
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
            'create_time' => 'Create Time',
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
    public function getSaleHasProducts()
    {
        return $this->hasMany(SaleHasProduct::className(), ['sale_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('sale_has_product', ['sale_id' => 'id']);
    }
}
