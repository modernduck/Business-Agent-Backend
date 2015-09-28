<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $name
 * @property integer $product_type_id
 * @property integer $price
 * @property string $description
 * @property string $image
 * @property string $create_time
 *
 * @property Inventory[] $inventories
 * @property Branch[] $branches
 * @property ProductType $productType
 * @property SaleHasProduct[] $saleHasProducts
 * @property Sale[] $sales
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'product_type_id', 'price'], 'required'],
            [['product_type_id', 'price'], 'integer'],
            [['description', 'image'], 'string'],
            [['create_time'], 'safe'],
            [['name'], 'string', 'max' => 45]
        ];
    }

     public function fields()
    {
        return [
            // field name is the same as the attribute name
            'id',
            'name',
            // field name is "email", the corresponding attribute name is "email_address"
            
            'product_type' => function($model)
            {
                return $model->productType->name;
            },
            'product_type_id',
            // field name is "name", its value is defined by a PHP callback
            'price',
            'description',
            'image',
            'create_time'
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
            'product_type_id' => 'Product Type ID',
            'price' => 'Price',
            'description' => 'Description',
            'image' => 'Image',
            'create_time' => 'Create Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventories()
    {
        return $this->hasMany(Inventory::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranches()
    {
        return $this->hasMany(Branch::className(), ['id' => 'branch_id'])->viaTable('inventory', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductType()
    {
        return $this->hasOne(ProductType::className(), ['id' => 'product_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleHasProducts()
    {
        return $this->hasMany(SaleHasProduct::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(Sale::className(), ['id' => 'sale_id'])->viaTable('sale_has_product', ['product_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        $this->update_time = date('Y-m-d H:i:s');
        return parent::beforeSave($insert);
    }
}
