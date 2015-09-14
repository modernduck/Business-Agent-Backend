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
use yii\web\Response;

class ProductController extends ActiveController
{
    public $modelClass = 'app\models\Product';
    const TOKEN_NAME = 'token';
    public $checkActions = [   'update'];
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
                    throw  new \yii\web\HttpException(403, "you shall not pass fcker!(Not Model) :". json_encode($params));
                else if($action != "delete" && $brand->id != $model->productType->brand_id)
                    throw  new \yii\web\HttpException(403, "you shall not pass fcker!");
            }
        
    }

}

?>