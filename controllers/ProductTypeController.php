<?php
namespace app\controllers;
use Yii;
use app\models\Brand;
use app\models\ProductType;
use yii\rest\ActiveController;
use yii\web\Request ;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class ProductTypeController extends ActiveController
{
    public $modelClass = 'app\models\ProductType';
    const TOKEN_NAME = 'token';
    public $checkActions = [   'update', 'view'];
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
        $model = ProductType::findOne($id);
        if(Brand::isGodToken($token))
        {
            return $model->delete();
        }else if(!isset($brand) )
            throw  new \yii\web\HttpException(403, "you shall not pass fcker!(Not brand)");
        else if(!isset($model) )
            throw  new \yii\web\HttpException(403, "you shall not pass fcker!(Not modelll)");
        else if( $brand->id == $model->brand_id)
            return $model->delete();
    }

    public function findModel($id)
    {
        $request = Yii::$app->request;
        $token = $request->get(self::TOKEN_NAME);
        $brand = Brand::findIdentityByAccessToken($token);
        $product_type = ProductType::findOne($id);
        if($product_type !==null)
        {
            if(Brand::isGodToken($token))
            {
                return $product_type;
            }else if(!isset($brand) )
                throw  new \yii\web\HttpException(403, "you shall not pass fcker!(Not brand)");
            else if($product_type->brand_id != $brand->id)
                throw  new \yii\web\HttpException(403, "you shall not pass fcker!(Not Model ?)");
            else
                return $product_type;
        }
        return null;
    }

    public function prepareDataProvider()
    {
        $request = Yii::$app->request;
        // prepare and return a data provider for the "index" action
        $token = $request->get(self::TOKEN_NAME);
        $brand = Brand::findIdentityByAccessToken($token);
        return ProductType::find()->where(['brand_id' => $brand->id])->all();
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $token = $request->get(self::TOKEN_NAME);
        $brand = Brand::findIdentityByAccessToken($token);
        $productType =  new ProductType();
        $productType->name = $request->getBodyParam('name');
        $productType->brand_id = $brand->id;
        
        if($productType->save())
            return $productType;
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
	    		$model2 = ProductType::findOne($id);
             
                if(Brand::isGodToken($token))
                {

                }else if(!isset($brand) )
					throw  new \yii\web\HttpException(403, "you shall not pass fcker!(Not brand)");
                else if(!isset($model2) )
                    throw  new \yii\web\HttpException(403, "you shall not pass fcker!(Not Model) :".$request->getUrl());
                else if( $brand->id != $model->brand_id)
                    throw  new \yii\web\HttpException(403, "you shall not pass fcker!");
			}
    	
    }

}

?>