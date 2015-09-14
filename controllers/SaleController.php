<?php
namespace app\controllers;
use Yii;
use app\models\Brand;
use app\models\ProductType;
use app\models\Product;
use app\models\Sale;
use app\models\SalesOrder;
use yii\rest\ActiveController;
use yii\web\Request ;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class SaleController extends ActiveController
{
    public $modelClass = 'app\models\Sale';
    const TOKEN_NAME = 'token';
    public $checkActions = [   'update'];
    public $brand_free_actions = ['index', 'create'];
    //public $save_keys = ['branch_id'];
    public $save_keys_sales_order = ['count', 'product_id'];
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
        unset( $actions['update']);
        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function actionDelete($id)
    {
        
        $request = Yii::$app->request;
        $token = $request->get(self::TOKEN_NAME);
        $brand = Brand::findIdentityByAccessToken($token);
        $model = Sale::findOne($id);
        $owner_brand_id = $model->branch->brand_id;
        if(Brand::isGodToken($token))
        {
            return $model->delete();
        }else if(!isset($brand) )
            throw  new \yii\web\HttpException(403, "you shall not pass fcker!(Not brand)");
        else if(!isset($model) )
            throw  new \yii\web\HttpException(403, "you shall not pass fcker!(Not modelll)");
        else if( $brand->id == $owner_brand_id)
            return Sale::undo($model->id);
            //return $model->delete();
    }



    public function prepareDataProvider()
    {
        $request = Yii::$app->request;
        // prepare and return a data provider for the "index" action
        $token = $request->get(self::TOKEN_NAME);
        $brand = Brand::findIdentityByAccessToken($token);

        //filter by time[date / month / year] | branchs
        
        return Sale::find()->joinWith("branch")->joinWith('products')->where(['branch.brand_id' => $brand->id])->all();
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $token = $request->get(self::TOKEN_NAME);
        $brand = Brand::findIdentityByAccessToken($token);
        
        $branch_id = $request->getBodyParam('branch_id');
        $orders = $request->getBodyParam('orders');
        $result = Sale::sell($branch_id, $orders);
        
        if($result !== null)
            return $result;
        else
            throw new \yii\web\HttpException(422, "Cant be sold abort transection");
        
            
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
                $model = Sale::findOne($id);

                if(Brand::isGodToken($token))
                {

                }else if(!isset($brand) )
                    throw  new \yii\web\HttpException(403, "you shall not pass fcker!(Not brand)");
                else if(!isset($model) )
                    throw  new \yii\web\HttpException(403, "you shall not pass fcker!(Not Model) :". json_encode($params));
                else if( $brand->id != $model->branch->brand_id)
                    throw  new \yii\web\HttpException(403, "you shall not pass fcker!");
            }
        
    }

}

?>