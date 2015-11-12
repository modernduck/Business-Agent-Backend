<?php
namespace app\controllers;
use Yii;
use app\models\Brand;
use app\models\Branch;
use app\models\Inventory;
use app\models\Employee;

use yii\rest\ActiveController;
use yii\web\Request ;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\web\Response;

class PosController extends ActiveController
{
    public $modelClass = 'app\models\Branch';
    public $checkActions = [];
    const TOKEN_NAME = 'token';
     public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
                'class' =>QueryParamAuth::className(),
                'only' => $this->checkActions,
                'except' => ['branch', 'authen', 'token','inventories'],
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
        unset( $actions['update']);
        unset( $actions['get']);
        unset( $actions['index']);

//        unset( $actions['get']);

        // customize the data provider preparation with the "prepareDataProvider()" method
      //  $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        
        return $actions;
    }

    public function actionBranch($brand_id)
    {
        $branches = Branch::find()->where(['brand_id' => $brand_id])->all();
        return $branches;
    }

    public function actionAuthen($branch_id)
    {

        $employee = Employee::find()->where([
            'branch_id' => $branch_id,
            'name' => $_GET['name'],
            'password' => $_GET['password'],
        ])->one();
        $token = $employee->getToken();
        if($employee !== null)
            return array(
                'status'=>'complete',
                'data' => $employee,
                'token' => $token,
                );
        
        else
            return array(
                'status' => 'fail'
            );
    }

    public function actionToken()
    {
        $token = $_GET['token'];
        $employee = Employee::findIdentityByAccessToken($token);
        
        if($employee !== null)
            return array(
                'status'=>'complete',
                'data' => $employee,
                );
        
        else
            return array(
                'status' => 'fail'
            );
    }

  public function actionInventories()
  {
        $token = $_GET['token'];
        $employee = Employee::findIdentityByAccessToken($token);  
        if($employee == null)
            return null;
        $inventories = Inventory::find()->where(['branch_id' => $employee->branch_id])->all();
        return $inventories;
  }

  public function actionSale()
  {
    
    
  }

  public function actionRefund()
  {

  }







}

?>