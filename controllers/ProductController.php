<?php
namespace app\controllers;
use Yii;
use app\models\Brand;
use app\models\ProductType;
use app\models\Product;
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
    public $brand_free_actions = ['index', 'create'];
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
            return Product::find()->joinWith('productType')->where(['brand_id' => $brand->id, 'product_type_id' => $product_type_id])->all();
        else
            return Product::find()->joinWith('productType')->where(['brand_id' => $brand->id])->all();
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
            return $product;
        else
            throw new \yii\web\HttpException(422, json_encode($productType));
            
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
        if(move_uploaded_file( $_FILES['file']['tmp_name'] , $destination ))
        {
            $product->image = Url::home(true).$destination;
            if($product->save())
                return array(
                    "result" => "success",
                    "data" => $product
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