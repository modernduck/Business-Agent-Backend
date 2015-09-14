<?php
namespace app\controllers;
use Yii;
use app\models\Brand;
use app\models\Branch;
use app\models\Inventory;

use yii\rest\ActiveController;
use yii\web\Request ;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class BranchController extends ActiveController
{
    public $modelClass = 'app\models\Branch';
    const TOKEN_NAME = 'token';
    public $checkActions = [   'update', 'view'];
    public $brand_free_actions = ['index', 'create'];
    public $save_keys = ['name', 'description', 'address', 'district', 'province', 'zipcode'];
    public $save_keys_inventory = ['count', 'product_id'];

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
        $model = Branch::findOne($id);
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
        $product_type = Branch::findOne($id);
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
        return Branch::find()->where(['brand_id' => $brand->id])->all();
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $token = $request->get(self::TOKEN_NAME);
        $brand = Brand::findIdentityByAccessToken($token);
        $model =  new Branch();
        $model->brand_id = $brand->id;

        foreach ($this->save_keys as $key) {
            # code...
            $model->$key = $request->getBodyParam($key);
        }

        
        if($model->save())
            return $model;
        else
            throw new \yii\web\HttpException(422, json_encode($model));
            
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
                $model2 = Branch::findOne($id);
             
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

    public function branchCheckAccess($branch_id)
    {
        $request = Yii::$app->request;
        $token = $request->get(self::TOKEN_NAME);
        $brand = Brand::findIdentityByAccessToken($token);   

        $branch = Brand::findOne($branch_id);

        if(Brand::isGodToken($token))
        {

        }else if(!isset($brand) )
            throw  new \yii\web\HttpException(403, "you shall not pass fcker!(Not brand)");
        else if(!isset($branch) )
            throw  new \yii\web\HttpException(403, "you shall not pass fcker!(Not Model) :".$request->getUrl());
        else if( $branch->brand_id != $brand->id)
            throw  new \yii\web\HttpException(403, "you shall not pass fcker!");
    }

    /*
    Inventory
    */

    public function actionInventoryTest($id)
    {
       $inventories = Inventory::find()->where(['branch_id' => $id])->all();
        return $inventories;
    }

    public function actionInventoryIndex($id)
    {
        $inventories = Inventory::find()->where(['branch_id' => $id])->all();
        return $inventories;
    }

    public function actionInventoryCreate($id)
    {
        $request = Yii::$app->request;
         $this->branchCheckAccess($id);

        $model =  new Inventory();
        $model->branch_id = $id;
        foreach ($this->save_keys_inventory as $key) {
            # code...
            $model->$key = $request->getBodyParam($key);
        }
        
        if($model->save())
            return $model;
        else
            throw new \yii\web\HttpException(422, json_encode($model));
    }

    public function actionInventoryUpdate ($id)
    {
        $request = Yii::$app->request;
        $this->branchCheckAccess($id);

        $product_id = $request->getBodyParam('product_id');
        $model = Inventory::find()->where(['branch_id' => $id, 'product_id' => $product_id])->one();
        foreach ($this->save_keys_inventory as $key) {
            # code...
            $model->$key = $request->getBodyParam($key);
        }
         if($model->save())
            return $model;
        else
            throw new \yii\web\HttpException(422, json_encode($model));
    }

    public  function actionInventoryDelete($id)
    {
        $request = Yii::$app->request;
        $this->branchCheckAccess($id);

        $product_id = $request->getBodyParam('product_id');
        $model = Inventory::find()->where(['branch_id' => $id, 'product_id' => $product_id])->one();
          if($model->delete())
            return $model;
        else
            throw new \yii\web\HttpException(422, json_encode($model));
    }

}

?>