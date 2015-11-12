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
            [['product_type_id'], 'integer'],
            [['price'], 'double'],
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

    public function getWooObject()
    {
        $answer = array();
        $answer['regular_price'] = $this->price;
        $answer['description'] = $this->description;
        $answer['title'] = $this->name;
        /*
         "images": [
      {
        "src": "http://example.com/wp-content/uploads/2015/01/premium-quality-front.jpg",
        "position": 0
      },
        $answer['images'] = array();
        $obj = new \stdClass();
        $obj->src = $this->image;
        $obj->position = 0;

        array_push($answer['images'], $obj);
        */
        //get type fro
        return $answer;
    }


    public function beforeSave($insert)
    {

        $product_type = ProductType::findOne($this->product_type_id);
        if(BrandConfig::get( $product_type->brand_id, 'is_connect_woocommerce'))
        {
            $options = array(
                'debug'           => true,
                'return_as_array' => false,
                'validate_url'    => false,
                'timeout'         => 30,
                'ssl_verify'      => false,
            );
            $client = new \WC_API_Client( BrandConfig::get( $product_type->brand_id, 'woocommerce_url'), BrandConfig::get( $product_type->brand_id, 'woocommerce_key'), BrandConfig::get($product_type->brand_id, 'woocommerce_secret'), $options );
            $save_obj = $this->getWooObject();


            //if is new Create
            if($this->isNewRecord)
            {
             /*   $result = $client->products->create( $save_obj);
                $pm =  new ProductMeta();
                $pm->name = "woocommerce_id";
                $pm->value = $result->product->id;
                $pm->product_id = $this->id;
                $pm->save();*/
            }else
            {
                $woo_product_id = ProductMeta::get($this->id, "woocommerce_id");
                $client->products->update( $woo_product_id, $save_obj ) ;
            }
            //else update

        }
        $this->update_time = date('Y-m-d H:i:s');
        
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes )
    {
         $product_type = ProductType::findOne($this->product_type_id);
        if(BrandConfig::get( $product_type->brand_id, 'is_connect_woocommerce'))
        {
            $options = array(
                'debug'           => true,
                'return_as_array' => false,
                'validate_url'    => false,
                'timeout'         => 30,
                'ssl_verify'      => false,
            );
            $client = new \WC_API_Client( BrandConfig::get( $product_type->brand_id, 'woocommerce_url'), BrandConfig::get( $product_type->brand_id, 'woocommerce_key'), BrandConfig::get($product_type->brand_id, 'woocommerce_secret'), $options );
            $save_obj = $this->getWooObject();
            if($insert)
            {
                $result = $client->products->create( $save_obj);
                $pm =  new ProductMeta();
                $pm->name = "woocommerce_id";
                $pm->value = $result->product->id."";
                $pm->product_id = $this->id;
                if($pm->validate())
                    $pm->save();
                else
                    throw  new \yii\web\HttpException(403, json_encode($pm->errors));
            }
        }
        return parent::afterSave($insert, $changedAttributes );
    }


}
