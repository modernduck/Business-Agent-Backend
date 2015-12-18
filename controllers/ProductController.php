<?php
namespace app\controllers;
use Yii;
use app\models\Brand;
use app\models\BrandConfig;
use app\models\ProductType;
use app\models\Product;
use app\models\ProductMeta;
use app\models\Woocommerce;
use yii\rest\ActiveController;
use yii\web\Request ;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\web\Response;
use yii\helpers\Url;

class ProductController extends ActiveController
{
    public $modelClass = 'app\models\Product';
    const TOKEN_NAME = 'token';
    public $checkActions = [   'update', 'upload'];
    public $brand_free_actions = ['index', 'create', 'add-type'];
     public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
                'class' =>QueryParamAuth::className(),
                'only' => $this->checkActions,
                'except' => [],
                'tokenParam' => self::TOKEN_NAME,
           ];


        $behaviors['bootstrap'] = [
                     'class' => ContentNegotiator::className(),
                    'formats' => [
                        'application/json' => Response::FORMAT_JSON,
                        'application/xml' => Response::FORMAT_XML,
                    ],
                ];
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
            ],
        ];
        return $behaviors;

    }

    public function actions()
    {
        $actions = parent::actions();
        // disable the "delete" and "create" actions
        unset( $actions['create']);
        unset( $actions['update']);
        unset( $actions['delete']);
        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        
        return $actions;
    }

    

    public function actionDelete($id)
    {
        
        $request = Yii::$app->request;
        $token = $request->get(self::TOKEN_NAME);
        $brand = Brand::findIdentityByAccessToken($token);
        $model = Product::findOne($id);
        $owner_brand_id = $model->productType->brand_id;
        if(Brand::isGodToken($token))
        {
            return $model->delete();
        }else if(!isset($brand) )
            throw  new \yii\web\HttpException(403, "you shall not pass fcker!(Not brand)");
        else if(!isset($model) )
            throw  new \yii\web\HttpException(403, "you shall not pass fcker!(Not modelll)");
        else if( $brand->id == $owner_brand_id)
            return $model->delete();
    }



    public function prepareDataProvider()
    {
        $request = Yii::$app->request;
        // prepare and return a data provider for the "index" action
        $token = $request->get(self::TOKEN_NAME);
        $brand = Brand::findIdentityByAccessToken($token);
        $product_type_id = $request->get('product_type_id');

       if(isset($product_type_id))
            return Product::find()->joinWith('productTypes')->where(['brand_id' => $brand->id, 'product_type_id' => $product_type_id])->all();
        else if(!isset($brand) )
            throw  new \yii\web\HttpException(403, "you shall not pass fcker!(Not brand)");
        else
            return Product::find()->joinWith('productTypes')->where(['brand_id' => $brand->id])->all();
           
    }

    public function actionUpdate()
    {
        $request = Yii::$app->request;
        $token = $request->get(self::TOKEN_NAME);
        $brand = Brand::findIdentityByAccessToken($token);
        
        $product = Product::findOne($request->getBodyParam('id'));
        $product->name = $request->getBodyParam('name');
        $product->unlinkAll('productTypes');
        $all = $request->getBodyParams();
        //$product_types = print_r($all['product_types'], true);

     
        
        $product->price = $request->getBodyParam('price');
        $product->description = $request->getBodyParam('description');
        $product->image = $request->getBodyParam('image');
        if($product->save())
        {
            $product_types_ids = $all['product_types'];
            foreach ($product_types_ids as $type_id) {
                # code...
                $product_type = ProductType::findOne($type_id);
                $product_type->link('products', $product);
            }


            return array(

                'product'=>$product,
                'types' => $all,
                'params' => $request->getBodyParams()
            );
        }else
            throw new \yii\web\HttpException(422, json_encode($productType));
           
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $token = $request->get(self::TOKEN_NAME);
        $brand = Brand::findIdentityByAccessToken($token);
        $product_type_id = $request->getBodyParam('product_type_id');

        $productType = ProductType::find()->where([
            'brand_id' => $brand->id, 
            'id' => $product_type_id
        ])->one();
        if($productType !== null)
        {
         
            $product =  new Product();
            $product->name = $request->getBodyParam('name');


            $product->product_type_id = $product_type_id;

            

            $product->price = $request->getBodyParam('price');
            $product->description = $request->getBodyParam('description');
            $product->image = $request->getBodyParam('image');
        }else
               throw new \yii\web\HttpException(422, "not found any");
        if($product->save())
        {
            $product->link('productTypes', $productType);
            return $product;
        }else
            throw new \yii\web\HttpException(422, json_encode($productType));
            
    }

    public function actionAddType()
    {
        $request = Yii::$app->request;
        $token = $request->get(self::TOKEN_NAME);
        $brand = Brand::findIdentityByAccessToken($token);
        $product_type_id = $request->getBodyParam('product_type_id');
        $product_id = $request->getBodyParam('product_id');
        $productType = ProductType::find()->where([
            'brand_id' => $brand->id, 
            'id' => $product_type_id
        ])->one();
        $product = Product::findOne($product_id);
        if($productType !== null && $product !== null)
        {
            $product->link('productTypes', $productType);
            return array(
                "status" => "success"

            );

        }else
           throw new \yii\web\HttpException(422, "not found any");
    }

    public function actionTest($id)
    {
        return array(
            'id' => $id
        );
    }

    public function actionUpload($id)
    {
        $product = Product::findOne($id);
        //$filename = $_FILES['file']['name']
        //return $_FILES;
        if(!isset($_FILES['file']))
            return false;
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);;
        $filename = $product->id.".".$ext;
        
        $destination = 'uploads/' . $filename;
        $binaryData = file_get_contents($_FILES['file']['tmp_name']);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['file']['tmp_name']);
        finfo_close($finfo);
        if(move_uploaded_file( $_FILES['file']['tmp_name'] , $destination ))
        {
            //$product->image = Url::home(true).$destination;
           $product->image =  Url::to("@web/uploads/{$filename}", 'http');
           $result = [];
             //$woo_product_id = ProductMeta::get($id, "woocommerce_id");
           //$params = array($product->id."-img", $mime, $binaryData, true, $woo_product_id);
           $brand_id = $product->productTypes[0]->brand_id;
            if(BrandConfig::get($brand_id, 'is_connect_woocommerce')){


                $woo_product_id = ProductMeta::get($id, "woocommerce_id");


                $url = BrandConfig::get($brand_id, 'woocommerce_url_2');
                $user = BrandConfig::get($brand_id, 'woocommerce_user');
                $password = BrandConfig::get($brand_id, 'woocommerce_password');
                $helper = new Woocommerce("{$url}/xmlrpc.php", $user, $password);
                $result = $helper->uploadFile($product->id, $mime, $binaryData, true, $woo_product_id);
                return array(
                    "result" => "success",
                    "yo" => "man",
                    "uploadResult" => $result

                );
            }
            //uploadFile( string $name, string $mime, string $bits, boolean $overwrite = null, integer $postId = null )
            
            if($product->save())
                return array(
                    "result" => "success",
                    "data" => $product,
                    'uploadResult' =>  $result,
                    
                );
            else
                return array(
                "result" => "failed",
                "message" => "cant save data"
            );    
        }else
            return array(
                "result" => "failed",
                "message" => "cant upload image"
            );

    }

    public function checkAccess($action, $model = null, $params = [])
    {
        $request = Yii::$app->request;
        $action_product_ids = $this->checkActions;
        

        for($i =0; $i < count($action_product_ids); $i++)
            if($action == $action_product_ids[$i])
            {
                $id = $request->getBodyParam('id');
                $token = $request->get(self::TOKEN_NAME);

                $brand = Brand::findIdentityByAccessToken($token);
                if($action != "delete")
                    $model = Product::find()->joinWith('productType')->where(['product.id'=>$id])->one();
                else
                    $model = Product::findOne($id);

                if(Brand::isGodToken($token))
                {

                }else if(!isset($brand) )
                    throw  new \yii\web\HttpException(403, "you shall not pass fcker!(Not brand)");
                else if(!isset($model) )
                    throw  new \yii\web\HttpException(403, "you shall not pass fcker!(Not Model) :". json_encode($_REQUEST));
                else if($action != "delete" && $brand->id != $model->productType->brand_id)
                    throw  new \yii\web\HttpException(403, "you shall not pass fcker!");
            }
        
    }

}

?>