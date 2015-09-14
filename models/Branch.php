<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "branch".
 *
 * @property integer $id
 * @property string $name
 * @property integer $brand_id
 * @property string $description
 * @property string $address
 * @property string $district
 * @property string $province
 * @property string $zipcode
 * @property string $create_time
 * @property string $update_time
 *
 * @property Brand $brand
 * @property Employee[] $employees
 * @property Inventory[] $inventories
 * @property Product[] $products
 * @property Invoice[] $invoices
 * @property Receipt[] $receipts
 * @property Sale[] $sales
 */
class Branch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'branch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'brand_id', 'description', 'address', 'district', 'province', 'zipcode'], 'required'],
            [['brand_id'], 'integer'],
            [['description', 'address'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 45],
            [['district'], 'string', 'max' => 200],
            [['province', 'zipcode'], 'string', 'max' => 100]
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
            'description' => 'Description',
            'address' => 'Address',
            'district' => 'District',
            'province' => 'Province',
            'zipcode' => 'Zipcode',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventories()
    {
        return $this->hasMany(Inventory::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('inventory', ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasMany(Invoice::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceipts()
    {
        return $this->hasMany(Receipt::className(), ['branch_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(Sale::className(), ['branch_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        $this->update_time = date('Y-m-d H:i:s');
        return parent::beforeSave($insert);
    }
}
