<?php
namespace app\controllers;
use Yii;
use yii\rest\ActiveController;
use app\models\Brand;
use app\models\Product;
use app\models\ProductType;
use app\models\ProductMeta;
use app\models\BrandConfig;
use app\models\Woocommerce;
use yii\web\Request ;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
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
                'except' => ['index',self::TOKEN_NAME, 'check', 'create' ,  'delete', 'config','import', 'import-sale'],
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
    		return array(
                "result" => "success",
                "data" => $brand,
                "token" => $_GET[self::TOKEN_NAME]
            );
    	else
    		return array(
                "result" => "failed",
                "token" => $_GET[self::TOKEN_NAME]
            );
    	
    }

    public function _getToken($brand)
    {
        if(isset($brand))
        {   
            $token = $brand->password;
            if(isset($_GET['password']) && Brand::encodePassword($_GET['password'], $brand->name) == $token)
                return array(
                    "result" => "success",
                    "data" => array(
                        "id" => $brand->id,
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

    public function actionTokenByEmail($email)
    {
        $brand = Brand::find()->where(['owner_email' => $email])->one();
        return $this->_getToken($brand);
    }

    public function actionToken($id)
    {
    	$brand = Brand::findOne($id);
    	return $this->_getToken($brand);
    }

   public function actionTest()
   {

    $options = array(
        'debug'           => true,
        'return_as_array' => false,
        'validate_url'    => false,
        'timeout'         => 30,
        'ssl_verify'      => false,
    );
        $client = new \WC_API_Client( 'http://www.jetpilot.co.th', 'ck_dcbc1be1c7eb230f2b86cbed56009d80e8d08d69', 'cs_b793e2d8f0bc20f67684e7d21d0963c9746c3408', $options );
        $items = $client->products->get();
        $result = array();
        foreach ($items->products as $product) {
            # code...
            $obj = new \stdClass();
            
            $obj->id = $product->id;
            $obj->name = $product->title;
            $obj->type = $product->categories[0];

            $obj->image = $product->featured_src;
            $obj->description = $product->description;
            $obj->create_time = $product->created_at;
            //$obj->update_time = $product->update_at;
            array_push($result, $obj);
        }
        
    /*[
        {"id":5,
        "name":"Wordpress template",
        "product_type":"Develop Website",
        "product_type_id":14,
        "price":3000,
        "description":"cool na",
        "image":"http://localhost/npop.in.th/newpos/service/web/uploads/5.jpg",
        "create_time":"2015-09-23 12:33:34"
        },{"id":6,"name":"Kalapakos X","product_type":"Develop Website","product_type_id":14,"price":9000,"description":"kalapakos course","image":"http://192.168.1.100/npop.in.th/newpos/service/web/uploads/6.jpg","create_time":"2015-09-21 14:35:08"},{"id":7,"name":"POS System","product_type":"Cloud Service","product_type_id":16,"price":12000,"description":"monthly payment","image":null,"create_time":"2015-09-21 14:24:07"}]
    */
    return  $result;
   }
  
   public function actionConfig($id)
   {
         //$brand_configs = BrandConfig::find()->where(['brand_id' => $id])->all();
         if(BrandConfig::get($id, 'is_connect_woocommerce'))
        {
            $options = array(
                'debug'           => true,
                'return_as_array' => false,
                'validate_url'    => false,
                'timeout'         => 30,
                'ssl_verify'      => false,
            );
            $client = new \WC_API_Client( BrandConfig::get($id, 'woocommerce_url'), BrandConfig::get($id, 'woocommerce_key'), BrandConfig::get($id, 'woocommerce_secret'), $options );

            $items_a = $client->products->get(null, array(
                "filter"=> array(
                    "limit" => 138
                )
            ));
            return $items_a;
            /*$url = BrandConfig::get($id, 'woocommerce_url_2');
            $user = BrandConfig::get($id, 'woocommerce_user');
            $password = BrandConfig::get($id, 'woocommerce_password');
            $helper = new Woocommerce("{$url}/xmlrpc.php", $user, $password);
            return $helper->getCategory("hello");*/
        }else
        return "in valid";

   }

   public function actionImportSale($id)
   {
         if(BrandConfig::get($id, 'is_connect_woocommerce'))
        {
            $options = array(
                'debug'           => true,
                'return_as_array' => false,
                'validate_url'    => false,
                'timeout'         => 30,
                'ssl_verify'      => false,
            );
            $client = new \WC_API_Client( BrandConfig::get($id, 'woocommerce_url'), BrandConfig::get($id, 'woocommerce_key'), BrandConfig::get($id, 'woocommerce_secret'), $options );
            $orders = $client->orders->get(null, array(
                "status" => "completed"
            )); 

            return $orders;
        }

   }

   public function actionImport($id)
   {
     if(BrandConfig::get($id, 'is_connect_woocommerce'))
        {
            $options = array(
                'debug'           => true,
                'return_as_array' => false,
                'validate_url'    => false,
                'timeout'         => 30,
                'ssl_verify'      => false,
            );
            $client = new \WC_API_Client( BrandConfig::get($id, 'woocommerce_url'), BrandConfig::get($id, 'woocommerce_key'), BrandConfig::get($id, 'woocommerce_secret'), $options );

            $items = $client->products->get(null, array(
                "filter"=> array(
                    "limit" => 138
                )
            ));


            $message = array();
            $result = array();

            foreach ($items->products as $product) {
                # code...
                $p = new Product();

                /*
                @property integer $id
                 * @property string $name
                 * @property integer $product_type_id
                 * @property integer $price
                 * @property string $description
                 * @property string $image
                 * @property string $create_time*/
                $p->name = $product->title;
                $p->description = $product->description;
                $p->image = $product->featured_src;
                $p->price = $product->price;

                
                foreach ($product->categories as $category_name) {
                    # code...
                    if(!ProductType::isCreated($id,  $category_name))
                    {
                        $product_type = new ProductType;
                        $product_type->name = $category_name;
                        $product_type->brand_id = $id;
                        $product_type->save();
                        array_push($message, "Add product type {$category_name} -> id {$product_type->id}");
                    }else
                    
                        array_push($message, "need to find product id for {$category_name}");


                }
                $single_type = $product->categories[0];
                $product_type = ProductType::find()->where(['name'=> $single_type])->one();
                $p->product_type_id = $product_type->id;
                $json = json_encode($p->errors);
                //check category if dont have category create one;
                //$obj->update_time = $product->update_at;
                if(!$p->validate())
                    array_push($message, json_encode($p->errors));
                if($p->save())
                {
                    array_push($message, "add product {$p->name} -> id {$p->id}");

                    $product_meta = new ProductMeta();
                    $product_meta->product_id = $p->id;
                    $product_meta->name = "woocommerce_id";
                    $product_meta->value = $product->id."";

                    if(!$product_meta->validate())
                        array_push($message, json_encode($product_meta->errors));
                    if(!(ProductMeta::find()->where(["product_id" => $p->id, "name"=> "woocommerce_id"])->one() != null))
                        $product_meta->save();
                    

                }else
                    array_push($message, "cant insrt cause {$json} {$p->price} {$p->name} {$p->image} {$p->description} {$p->product_type_id} ");
            }
            return $message;

        }
   }

    
}

?>