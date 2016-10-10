<?php
namespace Carts;

use Cake\Datasource\ConnectionManager;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Network\Session\DatabaseSession;

class Carts
{
	public $controller = null;
    public $session = null;

	public $cacheKey;

    public function __construct()
    {
        $this->cacheKey = Configure::read('Session.handler.cache');
        //parent::__construct();
    }

   public function cartDetails()
    {
		$session = $this->request->session();
		$cartDetails = $this->request->session()->read('CART_INFO');
		exit;
	}

   public function getCartFromUser($user_id){
		$connection = ConnectionManager::get('default');
		$Carts = TableRegistry::get('Carts');
		$cart = $Carts->newEntity();
		
		$cart_id = $this->getCartId($user_id);
		$getCartId = $cart_id;

		$CartProducts = TableRegistry::get('CartProducts');
		$cartP = $CartProducts->newEntity();
		
		 if(!empty($getCartId)){

		 $sqlQryU='SELECT prod_id as PRODUCT_ID,prod_quantity as PRODUCT_QUANTITY FROM `cart_products` WHERE cart_id='.$cart_id;
		//------------
			
		 $getCartProduct = @$connection->execute($sqlQryU)->fetchAll('assoc');

		 }else{
			$getCartProduct = array();
		 }
		
		return $getCartProduct;
		
   }

  public function insertCartToDB($arr){

		// Adding to Cart

		$Carts = TableRegistry::get('Carts');
		$cp = $Carts->newEntity();

		$cp->user_id = $arr['user_id'];

		if ($Carts->save($cp)) {			
			$last_cart_id = $cp->id;			
			//echo 'Saving new1';
		}else{
			//echo 'Update old1';
		}

		// Adding to Cart Products

		$CartProducts = TableRegistry::get('CartProducts');
		$cartP = $CartProducts->newEntity();
		$connection = ConnectionManager::get('default');
			
		if(empty($last_cart_id)){
			////echo 'Update old1';			
			$update_cart_id = $Carts->findByUserId($arr['user_id'])->first()->id;	
			//$update_cartProduct_id = $CartProducts->findByCartId($update_cart_id)->first()->id;
			$cartP->cart_id = $update_cart_id;

			
			//GET OLD DATA 
			$sqlQryU='SELECT * FROM `cart_products` WHERE prod_id='.$arr['product_id'].' AND cart_id='.$update_cart_id;
			//------------
			
			$getCartProduct = @$connection->execute($sqlQryU)->fetchAll('assoc')[0];
			
			if(empty($getCartProduct)){

					
					//INSERT NEW CART PRODUCT..
					$connection->insert('cart_products', [
					'cart_id' => $update_cart_id,
					'prod_id' => $arr['product_id'],
					'prod_quantity' => $arr['quantity'],
					'created'=>date('Y-m-d h:i:s')]
					);

					//die('insert');

			}else{

				
					//UPDATE CART PRODUCT
					$connection->update('cart_products',
						['prod_quantity' => $arr['quantity'],'modified'=>date('Y-m-d h:i:s')], ['id' =>$getCartProduct['id']]);

					//die('updated');
			}



		}else{

			//INSERT NEW CART PRODUCT..
				$connection->insert('cart_products', [
				'cart_id' => $last_cart_id,
				'prod_id' => $arr['product_id'],
				'prod_quantity' => $arr['quantity'],
				'created'=>date('Y-m-d h:i:s')]
			);

		
			//die('out insert');
		}

			
/*
		if(empty($last_cart_id)){
			////echo 'Update old1';			
			$update_cart_id = $Carts->findByProdId($arr['product_id'])->first()->id;	
			$update_cartProduct_id = $CartProducts->findByCartId($update_cart_id)->first()->id;
			$cartP->cart_id = $update_cart_id;
			
			//GET OLD DATA 
			$cartP = $CartProducts->get($update_cartProduct_id); 
			//------------
			//$cartP->prod_id = $arr['product_id'];
			$cartP->prod_quantity = $arr['quantity'];
			$CartProducts->save($cartP);



		}else{
			//echo 'Saving new 2';
			$cartP->cart_id = $last_cart_id;
			$cartP->prod_id = $arr['product_id'];
			$cartP->prod_quantity = $arr['quantity'];
			$CartProducts->save($cartP);
		}

		
		*/
		
		
		
		

  }
  function emptyCartFromDB($arr){
		
	$connection = ConnectionManager::get('default');	
	$cart_id = $this->getCartId($arr['user_id']);
	//DELETE CART PRODUCTS
	$connection->delete('cart_products', ['cart_id' =>$cart_id]);	
	//DELETE CART
	$connection->delete('carts', ['user_id' =>$arr['user_id']]);
  
  }

  function removecartProductFromDB($arr){
		$product_id = $arr['product_id'];
		$connection = ConnectionManager::get('default');
		$cart_id = $this->getCartId($arr['user_id']);
		$connection->delete('cart_products', ['prod_id' =>$product_id,'cart_id'=>$cart_id]);

		//CHECK IF NOT ANY PRODUCT IN CART
			$CartProducts = TableRegistry::get('CartProducts');
			$cart_products = $CartProducts->newEntity();
			$arrCartProducts = $CartProducts->findAllByCartId($cart_id)->toArray();
			//pre($arrCartProducts);

			if(empty($arrCartProducts)){
				$connection->delete('carts', ['user_id' =>$arr['user_id']]);
			}
		//---------------------------------
  
  }

  function cartUpdateFromSession(){
	
	@session_start();
	
	$connection = ConnectionManager::get('default');
	//pre($_SESSION['CART_INFO']['CART']['Product']);
	if(!empty(@$_SESSION['Current_User'])){
		$user_id = $_SESSION['Current_User'][0]['id'];
		$getCarArray = @$_SESSION['CART_INFO']['CART']['Product'];
		
		
		//GET CART ID 
		$cart_id = $this->getCartId($user_id);
		//CHECK USER PREVIOUSLY IN CART
		if($cart_id==NULL){
			$cart_id = $this->setCartId($user_id);
		}

		
		//-------------------------------------
		if(is_array($getCarArray)){
		
			foreach($getCarArray as $key=>$arrCart){


			$sqlQryU='SELECT * FROM `cart_products` WHERE prod_id='.$arrCart['PRODUCT_ID'].' AND cart_id='.$cart_id;
			//------------
			$getCartProduct = @$connection->execute($sqlQryU)->fetchAll('assoc')[0];

				if(empty($getCartProduct)){
						$connection->insert('cart_products', [
						'cart_id' => $cart_id,
						'prod_id' =>$arrCart['PRODUCT_ID'],
						'prod_quantity' =>$arrCart['PRODUCT_QUANTITY'],
						'created'=>date('Y-m-d h:i:s')]
						);
				}else{

						$connection->update('cart_products', [
						'cart_id' => $cart_id,
						'prod_id' =>$arrCart['PRODUCT_ID'],
						'prod_quantity' =>$arrCart['PRODUCT_QUANTITY'],
						'modified'=>date('Y-m-d h:i:s')],
						 ['id' =>$getCartProduct['id']]
						);
				
				}
			
						
			}
		}

	}
	//GET USER LOGIN
	 //$user_id = $current_user[0]['id'];
	//if(!empty($user_id)){

		//GET CART FROM SESSION
			//pre($cartDetails);
		//----------------------------------------

	//}

	//--------------------------
  
  }

  public function setCartId($user_id){
		$Carts = TableRegistry::get('Carts');
		$cp = $Carts->newEntity();

		$cp->user_id = $user_id;

		if ($Carts->save($cp)) {			
			$last_cart_id = $cp->id;			
			return $last_cart_id;
		}else{
			die('DATABASE ERROR : This user does not exists in Database.');
		}
  }

  function getCartId($user_id){
	  $Carts = TableRegistry::get('Carts');
	  $cart = $Carts->newEntity();
	  $arrCartId = @$Carts->findAllByUserId($user_id)->first()->id;
	  
	  if(!empty($arrCartId)){
		return $arrCartId;
	  }else{
		  return NULL;
	  }
  
  }

   function getLastQuery() {
        Configure::write('debug',false);
        $dbo = $this->getDatasource();
        $logs = $dbo->getLog();
        $lastLog = end($logs['log']);
        $latQuery = $lastLog['query'];
        echo "<pre>";
        print_r($latQuery);
    }

	
}

?>