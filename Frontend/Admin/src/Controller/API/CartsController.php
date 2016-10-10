<?php
namespace App\Controller\API;
use Cake\Datasource\ConnectionManager;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Carts\Carts;
use Promotions\Promotions;
use Users\Users;
/**
 * Carts Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class CartsController extends AppController
{
	
	public function beforeFilter(Event $event)
    {      
		$this->Auth->allow(['detailsToCart','addToCart','doEmptyCart','cartDetails','removeProductCart','getDiscountDisplayProduct','getDiscountDisplayProduct2','cartTest']);
    }

	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
		//echo '-->>';
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function addToCart($productId,$qunatity,$token=null,$user_id=null)
    {
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');  		
		
		//SET SESSION...
		$session = $this->request->session();
		$arrListProducts=array();
		$arrAddedProduct=array();
		$cartDetails = array();
		//$this->request->session()->destroy('CART');

		if($user_id!=null){
		//GET LOGIN TOKEN
		 require_once(ROOT .DS. "Vendor" . DS  . "Users" . DS . "Users.php");		
		 $Users = new Users;
		 
		 $user_token = $Users->login_token($user_id);
		//-----------------------------------------
		}

		if($token!=null){

			if(strcmp((string)$token,(string)$user_token)!=0){
				$response['msg']='Unauthorized access. Please login to get details.';
				$response['response_code']='0';			
				echo json_encode($response);
				die;

			}
		}

		if(is_array($this->request->session()->read('CART_INFO.CART'))){

			$arrToMerge = $this->request->session()->read('CART_INFO.CART.Product');
			$arrAddedProduct = array('PRODUCT_ID'=>$productId,'PRODUCT_QUANTITY'=>$qunatity);

			foreach($arrToMerge as $key=>$cartProduct){

					// CHECK IF PRODUCT ALREADY IN CART

					if($cartProduct['PRODUCT_ID']==$productId){ //Product already added in cart.
						$arrToMerge[$key]['PRODUCT_QUANTITY']=$qunatity; //Update previous quantity if change.					
						$arrAddedProduct = array();
					}
					
			}
			
		
			if(!empty($arrAddedProduct)){
				$arrListProducts = array_merge($arrToMerge,array($arrAddedProduct));
				$products = $arrListProducts;
			}else{
				$products = $arrToMerge;				
			}

		}else{
			$arrListProducts = array(
									'PRODUCT_ID'=>$productId,
									'PRODUCT_QUANTITY'=>$qunatity
							    );
			$products = array($arrListProducts);
			
		}

			
		//add to cart
		$session->write('CART_INFO.CART.Product',$products); 

		if(!empty($this->request->session()->read('CART_INFO.CART'))){
			$cartDetails = $this->request->session()->read('CART_INFO');
			$cartDetails['msg']='Product added to cart';
			$cartDetails['response_code']=1;
		}else{
			$cartDetails['msg']='Cart unable to add product.';
			$cartDetails['response_code']=0;
		}

		
		//CHECK IF USER LOGGED IN 
		
		
		$session = $this->request->session();
		$current_user = $this->request->session()->read('Current_User');
		
		if($token!=null){
		//GET CURRENT USER IF TOKEN IS SET.
				 $current_user = $Users->getCurrentUser($user_id);
		//--------------------------------------------------
		}
		
		if(!is_null($current_user)){

			if(!empty(@$current_user[0]['id'])){
				$user_id = $current_user[0]['id'];
			}

			//Save CART to DB...
			//ADD CART DETAILS TO DB...
			 require_once(ROOT .DS. "Vendor" . DS  . "Carts" . DS . "Carts.php");	
			 $Carts = new Carts;
			 $arr=array(
			 'user_id'=>$user_id,
			 'product_id'=>$productId,
			 'quantity'=>$qunatity
			 );

			 $Carts->insertCartToDB($arr);
		}
		
		 
		//----------------------------
		//pre($cartDetails);
		echo json_encode($cartDetails);
		exit;
    }

	
	public function doEmptyCart()
    {
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		$this->request->session()->destroy('CART_INFO');
		//DELETE FROM DATA BASE
		
		//CHECK IF USER LOGGED IN 		
		
		$session = $this->request->session();
		$current_user = $this->request->session()->read('Current_User');
		
		if(!is_null($current_user)){
			 $user_id = $current_user[0]['id'];
			//ADD CART DETAILS TO DB...
			 require_once(ROOT .DS. "Vendor" . DS  . "Carts" . DS . "Carts.php");	
			 $Carts = new Carts;
			 $arr=array(
			 'user_id'=>$user_id,
			 'product_id'=>$productId,
			 'quantity'=>$qunatity
			 );
			$Carts->emptyCartFromDB($arr);
		}
		//-----------------------------
		$cartDetails['msg']='Cart is empty.';
		$cartDetails['response_code']=1;
		echo json_encode($cartDetails);
		exit;
	}

	public function removeProductCart($productId)
    {
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');

		$session = $this->request->session();
		$cartDetails = $this->request->session()->read('CART_INFO');
	
		$qunatity=1;
		if(is_array($this->request->session()->read('CART_INFO.CART'))){

			
			$arrToMerge = $this->request->session()->read('CART_INFO.CART.Product');
			
			foreach($arrToMerge as $key=>$cartProduct){

					// CHECK IF PRODUCT ALREADY IN CART

					if($cartProduct['PRODUCT_ID']==$productId){ //Product already added in cart.			
						//pre($this->request->session()->read('CART_INFO.CART.Product.'.$key.''));
						$this->request->session()->delete('CART_INFO.CART.Product.'.$key.'');
					}
					
			}

		}


		//CHECK IF USER LOGGED IN 		
		
		$session = $this->request->session();
		$current_user = $this->request->session()->read('Current_User');		
		if(!is_null($current_user)){
			 $user_id = $current_user[0]['id'];
			//ADD CART DETAILS TO DB...
			 require_once(ROOT .DS. "Vendor" . DS  . "Carts" . DS . "Carts.php");	
			 $Carts = new Carts;
			 $arr=array(
			 'user_id'=>$user_id,
			 'product_id'=>$productId
			 );
			$Carts->removecartProductFromDB($arr);
		}
		//-----------------------------

		$cartDetails=array();

		if(empty($this->request->session()->read('CART_INFO.CART.Product'))){
			$this->request->session()->destroy('CART');
			$cartDetails['msg']='Cart is empty.';
			$cartDetails['response_code']=1;			
		}else{
			
			$cartDetails['msg']='Product id :'.$productId.' has been removed.';
			$cartDetails['response_code']=1;
		
		}
		echo json_encode($cartDetails);
		exit;
	}
    
    public function cartDetails($token=null,$user_id=null)
    {
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');	

		if($user_id!=null){

			// GET USER TOKEN
			require_once(ROOT .DS. "Vendor" . DS  . "Users" . DS . "Users.php");		
			$Users = new Users;
			$user_token = $Users->login_token($user_id);

			//------------------------------------------

		}

		

		//echo strcmp((string)$token,(string)$user_token);
		
		//CHECK LOGIN DETAILS.....
			$session = $this->request->session();
			$current_user = $this->request->session()->read('Current_User');


			if($token!=null){

				if(strcmp((string)$token,(string)$user_token)!=0){
					$response['msg']='Unauthorized access. Please login to get details.';
					$response['response_code']='0';			
					echo json_encode($response);
					die;
			
				}

				//GET CURRENT USER IF TOKEN IS SET.
				 $current_user = $Users->getCurrentUser($user_id);
				//--------------------------------------------------

			


				//pre($current_user);

			}

			require_once(ROOT .DS. "Vendor" . DS  . "Promotions" . DS . "Promotions.php");		
			$Promotions = new Promotions;
			
			
			//CART UPDATE 
			require_once(ROOT .DS. "Vendor" . DS  . "Carts" . DS . "Carts.php");	
			$Carts = new Carts;

			$cartDetails = $this->request->session()->read('CART_INFO');
			
			
		
			if(empty($cartDetails['CART']['Product'])){
			
				//CHECK IF USER LOGGED IN

				

				if(!is_null($current_user)){

					$user_id = $current_user[0]['id'];
					
					//GET CART FROM USERDATABASE
					$arrCartFromUser = $Carts->getCartFromUser($user_id);
					//------------------------------------------------------
					
					//add to cart
					$session->write('CART_INFO.CART.Product',$arrCartFromUser); 
				}
				$cartDetails = $this->request->session()->read('CART_INFO');
				//-----------------------
				
			}else{

				
				//CHECK USER LOGGED IN
				if(!is_null($current_user)){
					
					//echo 'user logged in';
					$user_id = $current_user[0]['id'];
					//UPDATE USER CART FROM SESSION...
				
					$Carts->cartUpdateFromSession();

					//CHECK IF USER ADDED CART........



					//--------------------------------
					
					//--------------------------------
					//GET CART FROM USERDATABASE
					$arrCartFromUser = $Carts->getCartFromUser($user_id);
					//---------------------------------------------------				
					//add to cart
					$session->write('CART_INFO.CART.Product',$arrCartFromUser); 
					$cartDetails = $this->request->session()->read('CART_INFO');
				}else{
					//echo 'user not logged in';
				
				}
				//--------------------
				
			}
		
		
		require_once(ROOT .DS. "Vendor" . DS  . "Promotions" . DS . "Promotions.php");		
		$Promotions = new Promotions;

		
		if(is_array($cartDetails)){
			//--------------GET PROMOTIONS WITH PRODUCTS ------------------
			foreach($cartDetails['CART']['Product'] as $key=>$cartItems){

					$PRODUCT_ID = $cartItems['PRODUCT_ID'];
					$PRODUCT_QUANTITY = $cartItems['PRODUCT_QUANTITY'];
					$cartDetails['CART']['Product'][$key]['PRODUCT_DETAILS'] = $Promotions->getProductInfo($PRODUCT_ID);
					$cartDetails['CART']['Product'][$key]['PROMOTION'] = $Promotions->getDiscountDisplayProduct($PRODUCT_ID,$PRODUCT_QUANTITY,$cartDetails);	

					@$cartDetails['CART']['Product'][$key]['PRODUCT_DETAILS']['BEST_DISCOUNTED_AMOUNT_WITHOUT_GST']=$cartDetails['CART']['Product'][$key]['PROMOTION']['APPLIED_PROMOTIONS']['BEST_DISCOUNT_AMT_WITHOUT_GST'];


					if(@$cartDetails['CART']['Product'][$key]['PRODUCT_DETAILS']['BEST_DISCOUNTED_AMOUNT_WITHOUT_GST']==null){
						@$cartDetails['CART']['Product'][$key]['PRODUCT_DETAILS']['BEST_DISCOUNTED_AMOUNT_WITHOUT_GST']=$cartDetails['CART']['Product'][$key]['PRODUCT_DETAILS']['list_price'];
					}

					@$cartDetails['CART']['Product'][$key]['PRODUCT_DETAILS']['BEST_DISCOUNT_AMT_WITH_GST']=$cartDetails['CART']['Product'][$key]['PROMOTION']['APPLIED_PROMOTIONS']['BEST_DISCOUNT_AMT_WITH_GST'];

					if(@$cartDetails['CART']['Product'][$key]['PRODUCT_DETAILS']['BEST_DISCOUNT_AMT_WITH_GST']==null){								
								//pre($cartDetails['CART']['Product'][$key]['PRODUCT_DETAILS']['list_price']);die('xxxxxxx');
								$amount = @$cartDetails['CART']['Product'][$key]['PRODUCT_DETAILS']['list_price'];
								@$cartDetails['CART']['Product'][$key]['PRODUCT_DETAILS']['BEST_DISCOUNT_AMT_WITH_GST']=$Promotions->getAmountWithGST($amount);
						
					}


					@$cartDetails['CART']['Product'][$key]['PRODUCT_DETAILS']['FINAL_ITEM_PRICE_WITHOUT_GST']=@$cartDetails['CART']['Product'][$key]['PRODUCT_DETAILS']['BEST_DISCOUNTED_AMOUNT_WITHOUT_GST'];


					@$cartDetails['CART']['Product'][$key]['PRODUCT_DETAILS']['FINAL_ITEM_PRICE_WITH_GST'] = @$cartDetails['CART']['Product'][$key]['PRODUCT_DETAILS']['BEST_DISCOUNT_AMT_WITH_GST'];



			
			}
			//---------------------------------------------------------------
		}
		
	
		echo json_encode($cartDetails);
		exit;
	}
	
	public function getDiscountDisplayProduct($productID,$quantity,$cartArray=null,$token=null,$user_id=null){
		  require_once(ROOT .DS. "Vendor" . DS  . "Promotions" . DS . "Promotions.php");		
		  $Promotions = new Promotions;
		  
		  if($cartArray==null){
			$session = $this->request->session();
			$cartArray = $this->request->session()->read('CART_INFO');
		  }

		

		  if($user_id!=null){
			//GET CART FROM DATABASE


			//----------------------
		  }

		 
		  $arrGetPromoData = $Promotions->getDiscountDisplayProduct($productID,$quantity,$cartArray);
		  echo json_encode($arrGetPromoData);
		  die;

	}

	public function getDiscountDisplayProduct2($productID,$quantity,$cartArray=null,$token=null,$user_id=null){
		  require_once(ROOT .DS. "Vendor" . DS  . "Promotions" . DS . "Promotions.php");		
		  $Promotions = new Promotions;
		  
		  if($cartArray==null){
			$session = $this->request->session();
			$cartArray = $this->request->session()->read('CART_INFO');
		  }

		   

		  if($user_id!=null){
			//GET CART FROM DATABASE...


			//-------------------------
		  }

		 
		  $arrGetPromoData = $Promotions->getDiscountDisplayProduct($productID,$quantity,$cartArray);
		  echo json_encode($arrGetPromoData);
		  die;

	}

	public function cartTest()
    {
		require_once(ROOT .DS. "Vendor" . DS  . "Carts" . DS . "Carts.php");	
		$Carts = new Carts;
		
		//$session = $this->request->session();
		//$cartDetails = $this->request->session()->read('CART_INFO');
		$Carts->getCartFromUser(12);
		exit;
	}

	
	public function productPromotionsCount($productID=NULL,$quantity){


		
	}
	
	
	
//////////////////////////////
////// Cart APi By Mukesh ////
////// 30-09-2016         ////
//////////////////////////////	
	
	
	//************************************************
// Method to fetch all details for checkout Front-End page
//************************************************
	
	public function detailsToCart($token=null,$user_id=null){
		
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *'); 

	
		//GET LOGIN TOKEN
		 require_once(ROOT .DS. "Vendor" . DS  . "Users" . DS . "Users.php");		
		 $Users = new Users;
		 $user_token = $Users->login_token($user_id);
		//-----------------------------------------
		
if(strcmp((string)$token,(string)$user_token)!=0){
$response['msg']='Unauthorized access. Please login to get details.';
$response['response_code']='0';			
echo json_encode($response);
die;

}else{		
	


	

		$Configrations = TableRegistry::get('Configrations');
		$Configration = $Configrations->get(1);
		
		$Carts = TableRegistry::get('Carts');
		$cartDetails = $Carts->find('all',[
		'conditions'=>['Carts.user_id'=>$user_id],
		'contain' => ['CartProducts.Products.ProductsAttrs.Brands','CartProducts.Products.ProductsPrices','CartProducts.Products.ProductsMarketingImages' , 'Users.UserDetails', 'CartProducts.Products.Promotions']
		]);
		$cartDetail = $cartDetails->first();

		$image_marketing_path = Router::url('/', true).'webroot/img/files/ProductsMarketingImages/image/';
		$GrandPrices=array();

		if($cartDetail==null){
			
		$cartDetail['user']['PuschaseLimits']='Purchase111 S$'.$Configration->min_amt_free_delivery.' more to get free delivery';	
		$cartDetail['user']['totalCartItems']=0;	
		echo json_encode($cartDetail);
		exit;
	
		}
		$cartDetail['user']['totalCartItems']=	count($cartDetail->cart_products);
		foreach($cartDetail->cart_products as $key => $products ){
			
			foreach($products->product->products_marketing_images as $image){
			$image = $image_marketing_path.$image['image'];	
			}
		
		$cartDetail['cart_products'][$key]['IMGPATH'] = $image;
		
				$productId = $cartDetail['cart_products'][$key]['prod_id'];
				$quantity   = $cartDetail['cart_products'][$key]['prod_quantity'];
				$cartId   = $cartDetail['id'];
				$prices=array();
				
				
$promostCount = count($products->product->promotions);
if($promostCount>=1){
	
//************************************************/	
//****** Getting Setisfy Promorion id Starts ******/ 
//************************************************/		
$promoId  = $this->satisfyPromoId($productId, $quantity, $cartId);
//***********************************************/	
//****** Getting Setisfy Promorion id Ends  ******/ 
//***********************************************/	
if($promoId==NULL){
goto emptyPromo;	
}
				$Promotions = TableRegistry::get('Promotions');
				$PromotionsDetails = $Promotions->get($promoId, [
				'contain'=>['ChildProducts.ProductsMarketingImages']
				]);
				@$assciatedProductID = @$PromotionsDetails['child_product_id'];
				$cartDetail['cart_products'][$key]['product']['promotions']=$PromotionsDetails;
				
				
				foreach($PromotionsDetails->child_product->products_marketing_images as $childimage){
					
					
					$childimage = $image_marketing_path.$childimage['image'];	
					
				}
		$cartDetail['cart_products'][$key]['CHILDIMGPATH'] = $childimage;
		

		switch(ucwords($PromotionsDetails->discount_type)){
			case 'D' :
				     $cartDetail['cart_products'][$key]['final_prices'] = $this->calculatePromosDirectDiscount($productId, $promoId, $quantity);
				break;
			case 'B' :
					 $cartDetail['cart_products'][$key]['final_prices'] = $this->calculatePromosBundleDeal($productId, $promoId, $quantity);
				break;
			case 'P' :				
					 $cartDetail['cart_products'][$key]['final_prices'] = $this->calculatePromosPurchaseWithPurchase($cartId,$productId,$assciatedProductID,  $promoId,$quantity);	 
				break;		
			case 'F' :
					 $cartDetail['cart_products'][$key]['final_prices'] = $this->calculatePromosFreeOfCost($cartId,$productId,$assciatedProductID,  $promoId,$quantity);
				break;	
			default:	
				break;
			
			}
		}else{
		emptyPromo:
			 $prices=array();
					$prices['list_price']=$cartDetail['cart_products'][$key]['product']['products_price']['list_price'];
					$prices['final_item_price']=$prices['list_price'];
					$prices['maximumDiscount']=0;
					$prices['maximumDiscount_child']=0;
					$prices['final_price']=$prices['final_item_price'] * $cartDetail['cart_products'][$key]['prod_quantity'];
					$cartDetail['cart_products'][$key]['final_prices'] = $prices;
			 
		}
		
		
		
		$cartDetail['user']['TotalProductPrice']+= $cartDetail['cart_products'][$key]['final_prices']['final_price'];
		$cartDetail['user']['TotalProductDiscount']+=$cartDetail['cart_products'][$key]['final_prices']['maximumDiscount_child'];
		$cartDetail['user']['grandTotalPriceWithOutGST']=$cartDetail['user']['TotalProductPrice']-$cartDetail['user']['TotalProductDiscount'];
		
		
		
		$cartDetail['user']['minimumAmountToFreeDeleivery']= $Configration->min_amt_free_delivery;
		$cartDetail['user']['GstRate'] = $Configration->gst;
		$cartDetail['user']['calucatedGST'] = ($cartDetail['user']['grandTotalPriceWithOutGST'] * $Configration->gst)/100;
		$cartDetail['user']['grandTotalPriceWithGST'] = $cartDetail['user']['grandTotalPriceWithOutGST']+$cartDetail['user']['calucatedGST'];
		if($cartDetail['user']['grandTotalPriceWithGST']>=$cartDetail['user']['minimumAmountToFreeDeleivery']){
			$cartDetail['user']['PuschaseLimits']='Free Delivery ';
			$cartDetail['user']['DeliveryCharge']='Free';
			$cartDetail['user']['grandTotalPriceWithGstAndDelivery']=$cartDetail['user']['grandTotalPriceWithGST'];
		}else{
			$cartDetail['user']['PuschaseLimits']='Purchase S$'.($cartDetail['user']['minimumAmountToFreeDeleivery'] - $cartDetail['user']['grandTotalPriceWithGST']).' more to get free delivery';
			$cartDetail['user']['DeliveryCharge']='S$'.$Configration->delivery_charge;
			$cartDetail['user']['grandTotalPriceWithGstAndDelivery'] = $cartDetail['user']['grandTotalPriceWithGST']+$Configration->delivery_charge;
		}
	
		}
		echo json_encode($cartDetail);
		exit;	
	}

	}
	
	
//************************************************
// Method to calculate Satisfy PromoId based on Product Id & Quantity.
//************************************************	
	public function satisfyPromoId($productId, $quantity, $cartId){
		$Products = TableRegistry::get('Products');
		$ProductsDetails = $Products->get($productId,[
		'contain' => [ 'ProductsPrices','Promotions']
		]);

		$Carts = TableRegistry::get('Carts');
		$cartDetails2 = $Carts->get($cartId,[
		'contain' => ['CartProducts.Products.Promotions','CartProducts.Products.ProductsPrices']
		]);
		
		foreach($ProductsDetails['promotions'] as $keyss => $promotions){
			
			if(strtotime($promotions['start_date']) <= strtotime(date("Y-m-d")) AND strtotime(date("Y-m-d")) <= strtotime($promotions['end_date'])) {
				
		
				$assciatedProductID = $promotions->child_product_id;

				if($promotions->quantity_parent<$quantity || $promotions->quantity_parent=$quantity){
					$pormoIDs[$keyss]['id']=$promotions->id;
					if($promotions->discount_type=="D" || $promotions->discount_type=="B" ){
					if($promotions->discount_parent_in=="$"){
					$pormoIDs[$keyss]['discount'] = $promotions->discounted_amount_parent*$quantity;
					}elseif($promotions->discount_parent_in=="%")
					{
					$discountPercentagePrice = ($ProductsDetails->products_price->list_price * $promotions->discounted_amount_parent)/100;	
					$pormoIDs[$keyss]['discount'] = $discountPercentagePrice*$quantity;
					}
					}elseif($promotions->discount_type=="P" || $promotions->discount_type=="F"){
							foreach($cartDetails2['cart_products'] as $key => $child_product){
								
								if($assciatedProductID == $child_product['prod_id'])
								{
										if($child_product->prod_quantity>=$promotions->quantity_child)
										{
											if($promotions->discount_child_in=="$"){
											$pormoIDs[$keyss]['discount'] = $promotions->discounted_amount_child * $promotions->quantity_child;
											}elseif($promotions->discount_child_in=="%")
											{
												
											$discountPercentagePrice = ($child_product->product->products_price->list_price * $promotions->discounted_amount_child)/100;	
											$pormoIDs[$keyss]['discount'] = $discountPercentagePrice*$promotions->quantity_child;
											}

										}else{
											$pormoIDs[$keyss]['discount'] = 0;
											
										}							
								}else{
											$pormoIDs[$keyss]['discount'] = 0;
											
								} 
							}				
					}			
				}else{
					goto smallQuantity;
				}
			}else{
				
				goto notNiDate;
			}
	
		}
	
	$price = array();
	foreach ($pormoIDs as $key => $row)
	{
	$price[$key] = $row['discount'];
	}
	array_multisort($price, SORT_DESC, $pormoIDs);	
		
	//debug($pormoIDs);	
	//die;
	//debug($pormoIDs[0]['id']);
		
	return $pormoIDs[0]['id'];
	smallQuantity:
	notNiDate:
	return $pormoIDs = NULL;
	} 

	
	
	
//************************************************
// Method to calculate Without Any Promos
//************************************************	
	public function calculateWithOutPromos($productId, $quantity){
		$prices=array();
		$Promotions = TableRegistry::get('Promotions');
		$PromotionsDetails = $Promotions->get($promoId,[
		'contain' => [ 'Products.ProductsPrices']
		]);
		
		$prices['list_price']=$PromotionsDetails->product->products_price->list_price;
		$prices['quantity']=$quantity;
		if($PromotionsDetails->discount_parent_in=="$"){
		
		$prices['final_item_price'] = $PromotionsDetails->product->products_price->list_price - $PromotionsDetails->discounted_amount_parent;
		$prices['maximumDiscount'] = $PromotionsDetails->discounted_amount_parent*$prices['quantity'];
		}elseif($PromotionsDetails->discount_parent_in=="%")
		{
		$discountPercentagePrice = ($PromotionsDetails->product->products_price->list_price * $PromotionsDetails->discounted_amount_parent)/100;	
		$prices['final_item_price'] =  $PromotionsDetails->product->products_price->list_price - $discountPercentagePrice;
		$prices['maximumDiscount'] = $discountPercentagePrice*$prices['quantity'];
		}
		$prices['final_price'] = $prices['final_item_price']*$prices['quantity'];
		
		RETURN $prices;
	} 

	
	
//************************************************
// Method to calculate promos basesd on Direct Discount
//************************************************	
	public function calculatePromosDirectDiscount($productId, $promoId, $quantity){
		$prices=array();
		$Promotions = TableRegistry::get('Promotions');
		$PromotionsDetails = $Promotions->get($promoId,[
		'contain' => [ 'Products.ProductsPrices']
		]);
		
		$prices['list_price']=$PromotionsDetails->product->products_price->list_price;
		$prices['quantity']=$quantity;
		if($PromotionsDetails->discount_parent_in=="$"){
		
		$prices['final_item_price'] = $PromotionsDetails->product->products_price->list_price - $PromotionsDetails->discounted_amount_parent;
		$prices['maximumDiscount'] = $PromotionsDetails->discounted_amount_parent*$prices['quantity'];
		}elseif($PromotionsDetails->discount_parent_in=="%")
		{
		$discountPercentagePrice = ($PromotionsDetails->product->products_price->list_price * $PromotionsDetails->discounted_amount_parent)/100;	
		$prices['final_item_price'] =  $PromotionsDetails->product->products_price->list_price - $discountPercentagePrice;
		$prices['maximumDiscount'] = $discountPercentagePrice*$prices['quantity'];
		}
		$prices['final_price'] = $prices['final_item_price']*$prices['quantity'];
		$prices['maximumDiscount_child']=0;
		RETURN $prices;
	} 

//************************************************
// Method to calculate promos basesd on Bundle Deal
//************************************************	
	public function calculatePromosBundleDeal($productId, $promoId, $quantity){
		$prices=array();
		$Promotions = TableRegistry::get('Promotions');
		$PromotionsDetails = $Promotions->get($promoId,[
		'contain' => [ 'Products.ProductsPrices']
		]);
		
		$prices['list_price']=$PromotionsDetails->product->products_price->list_price;
		$prices['quantity']=$quantity;
		if($PromotionsDetails->quantity_parent<=$quantity){
			if($PromotionsDetails->discount_parent_in=="$"){
			
			$prices['final_item_price'] = $PromotionsDetails->product->products_price->list_price - $PromotionsDetails->discounted_amount_parent;
			$prices['maximumDiscount'] = $PromotionsDetails->discounted_amount_parent*$prices['quantity'];
			}elseif($PromotionsDetails->discount_parent_in=="%")
			{
			$discountPercentagePrice = ($PromotionsDetails->product->products_price->list_price * $PromotionsDetails->discounted_amount_parent)/100;	
			$prices['final_item_price'] =  $PromotionsDetails->product->products_price->list_price - $discountPercentagePrice;
			$prices['maximumDiscount'] = $discountPercentagePrice*$prices['quantity'];
			}
			$prices['final_price'] = $prices['final_item_price']*$prices['quantity'];
		}else{
			
			$prices['maximumDiscount']=0;
			$prices['final_price']=$prices['list_price']*$prices['quantity'];
		}
		
		$prices['maximumDiscount_child']=0;
		RETURN $prices;
	} 

//************************************************
// Method to calculate promos basesd on Purchase With Purchase
//************************************************	
	public function calculatePromosPurchaseWithPurchase($cartId, $productId, $assciatedProductID,  $promoId,$quantity){	

		$Carts = TableRegistry::get('Carts');
		$cartDetails = $Carts->get($cartId,[
		'contain' => ['CartProducts.Products.Promotions','CartProducts.Products.ProductsPrices']
		]);
	
		
		$Promotions = TableRegistry::get('Promotions');
		$PromotionsDetails = $Promotions->get($promoId,[
		'contain' => [ 'Products.ProductsPrices']
		]);
			 
		
		$cart_prices = array();
		$prices['quantity'] = $quantity;
		$final_prices=array();
			if($PromotionsDetails->quantity_parent>$quantity){
			
			$prices['final_item_price'] = $PromotionsDetails->product->products_price->list_price;
			$prices['maximumDiscount'] = 0;
			$prices['maximumDiscount_child']= 0;
			$prices['final_price'] = $prices['final_item_price']*$prices['quantity'];
			}else{
			
			$prices['final_item_price'] = $PromotionsDetails->product->products_price->list_price;
			$prices['maximumDiscount'] = 0;
			$prices['final_price'] = $prices['final_item_price']*$prices['quantity'];
		
				foreach($cartDetails['cart_products'] as $key => $child_product){
					if($assciatedProductID == $child_product['prod_id'])
					{
					
						if($child_product->prod_quantity>=$PromotionsDetails->quantity_child)
						{

							if($PromotionsDetails->discount_child_in=="$"){

							
							$prices['maximumDiscount_child'] = $PromotionsDetails->discounted_amount_child * $PromotionsDetails->quantity_child;
							}elseif($PromotionsDetails->discount_child_in=="%")
							{
							$discountPercentagePrice = ($child_product->product->products_price->list_price * $PromotionsDetails->discounted_amount_child)/100;	
							$prices['maximumDiscount_child'] = $discountPercentagePrice*$PromotionsDetails->quantity_child;
							}

						}else{
							$prices['maximumDiscount_child'] = 0;
							
						}
					
					}
				}		
		}			
						

	return $prices;
	
	} 

//************************************************
// Method to calculate promos basesd on Free Of Cost
//************************************************	
	public function calculatePromosFreeOfCost($cartId, $productId, $assciatedProductID,  $promoId,$quantity){	
		
		$Carts = TableRegistry::get('Carts');
		$cartDetails = $Carts->get($cartId,[
		'contain' => ['CartProducts.Products.Promotions','CartProducts.Products.ProductsPrices']
		]);
		
		
		$Promotions = TableRegistry::get('Promotions');
		$PromotionsDetails = $Promotions->get($promoId,[
		'contain' => [ 'Products.ProductsPrices']
		]);
		
		
		$cart_prices = array();
		$prices['quantity'] = $quantity;
		$final_prices=array();
			if($PromotionsDetails->quantity_parent>=$quantity){
			
			$prices['final_item_price'] = $PromotionsDetails->product->products_price->list_price;
			$prices['maximumDiscount'] = 0;
			$prices['final_price'] = $prices['final_item_price']*$prices['quantity'];
			}else{
			
			$prices['final_item_price'] = $PromotionsDetails->product->products_price->list_price;
			$prices['maximumDiscount'] = 0;
			$prices['final_price'] = $prices['final_item_price']*$prices['quantity'];
			

				foreach($cartDetails['cart_products'] as $key => $child_product){
					if($assciatedProductID == $child_product['prod_id'])
					{

					if($child_product->prod_quantity>=$PromotionsDetails->quantity_child){
						
						$qant = $PromotionsDetails->quantity_child;
					}elseif($child_product->prod_quantity<=$PromotionsDetails->quantity_child){
						$qant = $child_product->prod_quantity;
					}

					$discountPercentagePrice = ($child_product->product->products_price->list_price * 100)/100;	
					$prices['maximumDiscount_child'] = $discountPercentagePrice*$qant;
						
					
					}
				}	
		}			
						

	return $prices;
	} 
	
	
	
	
	
	
	
	
	
	
	
	
}
