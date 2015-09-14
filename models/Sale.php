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

    public function fields()
    {
        return [
            // field name is the same as the attribute name
            'id',
            'salesOrder',
            'products',
            'create_time',
            'branch_id'

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
    public function getSalesOrder()
    {
        return $this->hasMany(SalesOrder::className(), ['sale_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('sale_has_product', ['sale_id' => 'id']);
    }

    public static function search($brand_id, $fields = [])
    {
        return Sale::find()->joinWith("branch")->joinWith('products')->where(['branch.brand_id' => $brand_id])->all();
        /*$sql = "branch.brand_id = ".$branch_id;
        if(count($fields) == 0)
            return Sale::find()->joinWith("branch")->joinWith('products')->where(['branch.brand_id' => $brand_id])->all();
        foreach ($fields as $field) {
                    # code...
            if($field == "time")
            {
                //search by range from date A to Date B
            }
        }       
        return  Sale::find()->joinWith("branch")->joinWith('products')->where($sql)->all();*/
    }


    public static function undo($id)
    {
        $sale = Sale::findOne($id);
        $sale_orders = SalesOrder::find()->where(['sale_id' => $id])->all();
        foreach ($sale_orders as $sale_order) {
                $inventory = Inventory::find()->where(['product_id' => $sale_order->product_id, 'branch_id' => $sale->branch_id])->one();
                $inventory->count += $sale_order->count;
                $inventory->save();
        }
        if($sale->delete())
        {
                
            return $sale;
        }else
            return null;
    }

    /*
    * return sale object if sale success if not return null
    */
    public static function sell($branch_id, $orders)
    {
        $sale = new Sale();
        $sale->branch_id = $branch_id;
        $success_orders = [];

        if($sale->save())
        {
            $isAborting = false;
            foreach ($orders as $product_id => $count) {
                # code...
                $sale_order = new SalesOrder();
                $sale_order->sale_id = $sale->id;
                $sale_order->product_id = $product_id;
                $sale_order->count = $count;
                //check inventory 
                $inventory = Inventory::find()->where(['product_id' => $product_id, 'branch_id' => $branch_id])->one();
                if($inventory->count - $count > 0)
                {
                    $inventory->count -= $count;
                    $inventory->save();
                }else
                    $isAborting = true;
                if($sale_order->save() && !$isAborting)
                {
                    array_push($success_orders, $sale_order);
                    //remove inventory
                }else
                {
                    //abort operation
                    foreach ($success_orders as $item) {
                        # code...
                        $item->delete();
                    }

                    //go off the loop
                    break;
                    return null;
                }
            }
            return $sale;

        }else
            return null;
    }
}
