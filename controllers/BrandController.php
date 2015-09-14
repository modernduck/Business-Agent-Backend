<?php
namespace app\controllers;
use Yii;
use yii\rest\ActiveController;
use app\models\Brand;
use yii\web\Request ;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\filters\AccessControl;
class BrandController extends ActiveController
{
    public $modelClass = 'app\models\Brand';
    const TOKEN_NAME = 'token';
    public $checkActions = [ 'update', 'view'];
    public $godActions = ['create', 'delete'];
   public function behaviors()
    {
    	$behaviors = parent::behaviors();
    	$behaviors['authenticator'] = [
                'class' =>QueryParamAuth::className(),
                'only' => $this->checkActions,
                'except' => ['index',self::TOKEN_NAME, 'check', 'create' ,  'delete'],
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

    public function checkAccess($action, $model = null, $params = [])
    {
    	$request = Yii::$app->request;
    	

        foreach ($this->checkActions as $checkAction) {
        	if($action == $checkAction)
        	{
        		$id = $request->getBodyParam('id');
        		$token = $request->get(self::TOKEN_NAME);
        		$brand = Brand::findIdentityByAccessToken($token);
        		if(!isset($brand) || $brand->id != $id)
    				throw  new \yii\web\HttpException(403, "you shall not pass fcker!");
    		}
    	}
        foreach ($this->godActions as $godAction) 
            if($action == $godAction)
            {
                $id = $request->getBodyParam('id');
                $token = $request->get(self::TOKEN_NAME);
                
                if(!Brand::isGodToken($token))
                    throw  new \yii\web\HttpException(403, "Only God could pass: $token");
            }
        
    }


    public function actionCheck($id)
    {
    	$brand = Brand::findIdentityByAccessToken($_GET[self::TOKEN_NAME]);	
    	if($brand!== null)
    		$result = $brand;
    	else
    		$result = "failed";
    	return array(
    		"result" => $result,
    		"token" => $_GET[self::TOKEN_NAME]
    	);
    }

    public function actionToken($id)
    {
    	$brand = Brand::findOne($id);
    	if(isset($brand))
    	{	
    		$token = $brand->password;
    		if(isset($_GET['password']) && Brand::encodePassword($_GET['password'], $brand->name) == $token)
	    		return array(
	    			"result" => "success",
	    			"data" => array(
	    				"token" => $brand->getToken()
	    			),
	    		);
	    	return array(
	    		"result" => "error",
	    		"data" => array(
	    			"message" => "Wrong authenticate or password is missing",
	    			"password" => $_GET['password'],
	    			"date" => time()	
	    			
	    		),
	    	);

    	}
    	return array(
    		"result" => "error",
    		"data" => array(
    			"message" => "Brand Not found"
    		),
    	);
    }

   
   
    
}

?>