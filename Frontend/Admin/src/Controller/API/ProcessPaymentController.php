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
use Cake\Network\Http\Client;

/**
 * Process Payment Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class ProcessPaymentController extends AppController
{
	
	public function beforeFilter(Event $event)
    {      
		$this->Auth->allow(['dopayment', 'success']);
    }

	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
		//echo '-->>';
    }


	
//////////////////////////////
////// Cart APi By Mukesh ////
////// 30-09-2016         ////
//////////////////////////////	
	
	
//************************************************
// Method to fetch all details for checkout Front-End page
//************************************************
	
public function dopayment($token=null,$user_id=null){
		
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *'); 

		$session = $this->request->session();
		$current_user = $this->request->session()->read('Current_User');		

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
			
		$cartDetail['user']['PuschaseLimits']='Purchase S$'.$Configration->min_amt_free_delivery.' more to get free delivery';	
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
		
			$session = $this->request->session();
			$session->write('payment_user_id',$user_id); 
		
		
		/* $ordersDetails=array();
		
		$details=array();
		switch('paypal'){
			case 'paypal' :
				     $ordersDetails = $this->paypal($details);
				break;
			case 'enets' :
					 $cartDetail['cart_products'][$key]['final_prices'] = $this->calculatePromosBundleDeal($productId, $promoId, $quantity);
				break;
			default:	
				break;
			} */
		
		

		
		/* $this->redirect( $ordersDetails );
		*/
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
		$cartDetails = $Carts->get($cartId,[
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
							foreach($cartDetails['cart_products'] as $key => $child_product){
								
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
			$prices['final_item_price']=$prices['list_price'];
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

//************************************************
// Method Paypal
//************************************************		
 	
	public function paypal($details){
	
						$paypal_url='https://www.sandbox.paypal.com/cgi-bin/webscr'; // Test Paypal API URL
								$paypal_id='mukesh.kaushal@sdnainfotech.com'; // Business email ID
								$image=Router::url('/', true).'webroot/img/files/Configrations/logo/'.LOGO;
						$details = 'business='.$paypal_id.'&cmd=_xclick&item_name=Tables&item_name=Tables2&item_number=PIDD1&credits=510&userid=1&amount=10&cpp_header_image= $image&no_shipping=5&currency_code=SGD&handling=0&cancel_return=http://localhost/payment_with_paypal/cancel.php&return=http://localhost.com/payment_with_paypal/success.php';
								
							header("Location: $paypal_url?$details"); 	
							die;
	}
	
//************************************************
// Method Enets
//************************************************		
	public function enets(){
						debug($_REQUEST);
						die('enets');
	} 	
	

//************************************************
// Method return url on Success checkout
//************************************************		
	public function success(){
		
		$session = $this->request->session();
		$payment_user_id = $this->request->session()->read('payment_user_id');		
		$Configrations = TableRegistry::get('Configrations');
		$Configration = $Configrations->get(1);
		
		$Carts = TableRegistry::get('Carts');
		$cartDetails = $Carts->find('all',[
		'conditions'=>['Carts.user_id'=>$payment_user_id],
		'contain' => ['CartProducts.Products.ProductsAttrs.Brands','CartProducts.Products.ProductsPrices','CartProducts.Products.ProductsMarketingImages' , 'Users.UserAddresses', 'Users.UserDetails', 'Users.UserBillings', 'CartProducts.Products.Promotions']
		]);
		$cartDetail = $cartDetails->first();
		if($cartDetail==null){
		$cartDetail['user']['totalCartItems']=0;	
		echo json_encode($cartDetail);
		exit;
	
		}
		
		
		$image_marketing_path = Router::url('/', true).'webroot/img/files/ProductsMarketingImages/image/';
		$GrandPrices=array();
		
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
		
		
		$Configrations = TableRegistry::get('Configrations');
		$Configration = $Configrations->get(1);
		$cartDetail['user']['minimumAmountToFreeDeleivery']= $Configration->min_amt_free_delivery;
		$cartDetail['user']['GstRate'] = $Configration->gst;
		$cartDetail['user']['calucatedGST'] = ($cartDetail['user']['grandTotalPriceWithOutGST'] * $Configration->gst)/100;
		$cartDetail['user']['grandTotalPriceWithGST'] = $cartDetail['user']['grandTotalPriceWithOutGST']+$cartDetail['user']['calucatedGST'];
		if($cartDetail['user']['grandTotalPriceWithGST']>=$cartDetail['user']['minimumAmountToFreeDeleivery']){
			$cartDetail['user']['DeliveryCharge']='Free';
			$cartDetail['user']['grandTotalPriceWithGstAndDelivery']=$cartDetail['user']['grandTotalPriceWithGST'];
		}else{
			$cartDetail['user']['DeliveryCharge']='S$'.$Configration->delivery_charge;
			$cartDetail['user']['grandTotalPriceWithGstAndDelivery'] = $cartDetail['user']['grandTotalPriceWithGST']+$Configration->delivery_charge;
		}
	
		}

	
		$this->saveOrderDetails($cartDetail);
		exit;
	}
	
	
	
//************************************************
// Method save success order details
//************************************************		
	public function saveOrderDetails($cartDetail){

		$Orders = TableRegistry::get('Orders');
		$OrderUpdateStatuses = TableRegistry::get('OrderUpdateStatuses');
		$OrdersBillings = TableRegistry::get('OrdersBillings');
		$OrdersShippings = TableRegistry::get('OrdersShippings');
		$OrdersProducts = TableRegistry::get('OrdersProducts');
		$OrderProductPromotions = TableRegistry::get('OrderProductPromotions');

		$order = $Orders->newEntity();
		$orderUpdateStatus = $OrderUpdateStatuses->newEntity();
		$ordersBilling = $OrdersBillings->newEntity();
		$ordersShipping = $OrdersShippings->newEntity();

//Save order
			
			$this->request->data['order']['user_id']=$cartDetail->user->id;
			$this->request->data['order']['user_comments']='';
			$this->request->data['order']['admin_comments']='';
			$this->request->data['order']['transactionCode']='';
			$this->request->data['order']['refrenceCode']='';
			$this->request->data['order']['invoiceCode']='';
			$this->request->data['order']['otherCode']='';
			$order = $Orders->patchEntity($order, $this->request->data['order']);
			$OrderResult = $Orders->save($order);
if($OrderResult){
			$orderID = $OrderResult->id;

				
//Save order Sataus				
			$this->request->data['order_update_status']['order_id'] = $orderID;
			$this->request->data['order_update_status']['order_status_id']=1;
			$this->request->data['order_update_status']['admin_id']=NULL;
			$orderUpdateStatus = $OrderUpdateStatuses->patchEntity($orderUpdateStatus, $this->request->data['order_update_status']);
			$orderUpdateStatusResult = $OrderUpdateStatuses->save($orderUpdateStatus);
			
//Save order Billings	
			unset($cartDetail->user->user_billing->id);
			$this->request->data['orders_billing']['order_id'] = $orderID ;
			$this->request->data['orders_billing']['user_id']=$cartDetail->user->user_billing->user_id;
			$this->request->data['orders_billing']['sender_name']=$cartDetail->user->user_billing->sender_name;
			$this->request->data['orders_billing']['street_address']=$cartDetail->user->user_billing->street_address;
			$this->request->data['orders_billing']['city']=$cartDetail->user->user_billing->city;
			$this->request->data['orders_billing']['state']=$cartDetail->user->user_billing->state;
			$this->request->data['orders_billing']['country']=$cartDetail->user->user_billing->country;
			$this->request->data['orders_billing']['postalcode']=$cartDetail->user->user_billing->postalcode;
			$this->request->data['orders_billing']['telephone']=$cartDetail->user->user_billing->telephone;
			$this->request->data['orders_billing']['fax_no']=$cartDetail->user->user_billing->fax_no;

			$ordersBilling = $OrdersBillings->patchEntity($ordersBilling, $this->request->data['orders_billing']);
			$ordersBillingResult = $OrdersBillings->save($ordersBilling);
			
//Save order Shippings	
			$this->request->data['orders_shipping']['order_id'] = $orderID ;
			$this->request->data['orders_shipping']['user_id']=$cartDetail->user->user_billing->user_id;
			$this->request->data['orders_shipping']['sender_name']='';
			$this->request->data['orders_shipping']['street_address']='';
			$this->request->data['orders_shipping']['city']='';
			$this->request->data['orders_shipping']['state']='';
			$this->request->data['orders_shipping']['country']='';
			$this->request->data['orders_shipping']['postalcode']='';
			$this->request->data['orders_shipping']['telephone']='';
			$this->request->data['orders_shipping']['fax_no']='';

			$ordersShipping = $OrdersShippings->patchEntity($ordersShipping, $this->request->data['orders_shipping']);
			$ordersShippingResult = $OrdersShippings->save($ordersShipping);	
			
			
//Save order Products		
			foreach($cartDetail->cart_products as $key => $product ){
					$ordersProduct = $OrdersProducts->newEntity();
					$this->request->data['orders_product']['order_id'] = $orderID ;
					$this->request->data['orders_product']['product_id']=$product['prod_id'];
					$this->request->data['orders_product']['product_quantity']=$product['prod_quantity'];
					$this->request->data['orders_product']['price']=$product['final_prices']['list_price'];
					$this->request->data['orders_product']['discounted_price']=$product['final_prices']['final_item_price'];
					$this->request->data['orders_product']['gst_rate']=$cartDetail->user->GstRate;
					$this->request->data['orders_product']['promotion_applied']=@$product['product']['promotions']['id'];
					$ordersProduct = $OrdersProducts->patchEntity($ordersProduct, $this->request->data['orders_product']);
					$ordersProductResult = $OrdersProducts->save($ordersProduct);
					
		if($ordersProductResult){		
					if(isset($product['product']['promotions']['id']))
					{
	$orderProductPromotion = $OrderProductPromotions->newEntity();
	unset($product['product']['promotions']['id']);
	$this->request->data['order_product_promotion']['orders_product_id']=$ordersProductResult->id;
	$this->request->data['order_product_promotion']['promo_code']=$product['product']['promotions']['promo_code'];
	$this->request->data['order_product_promotion']['formal_message_display']=$product['product']['promotions']['formal_message_display'];
	$this->request->data['order_product_promotion']['discounted_amount_parent']=$product['product']['promotions']['discounted_amount_parent'];
	$this->request->data['order_product_promotion']['quantity_parent']=$product['product']['promotions']['quantity_parent'];
	$this->request->data['order_product_promotion']['start_date']=$product['product']['promotions']['start_date'];
	$this->request->data['order_product_promotion']['end_date']=$product['product']['promotions']['end_date'];
	$this->request->data['order_product_promotion']['associated_product']=$product['product']['promotions']['associated_product'];
	$this->request->data['order_product_promotion']['child_product_id']=$product['product']['promotions']['child_product_id'];
	$this->request->data['order_product_promotion']['discounted_amount_child']=$product['product']['promotions']['discounted_amount_child'];
	$this->request->data['order_product_promotion']['quantity_child']=$product['product']['promotions']['quantity_child'];
	$this->request->data['order_product_promotion']['discount_parent_in']=$product['product']['promotions']['discount_parent_in'];
	$this->request->data['order_product_promotion']['discount_child_in']=$product['product']['promotions']['discount_child_in'];
	$this->request->data['order_product_promotion']['discount_type']=$product['product']['promotions']['discount_type'];
			$orderProductPromotion = $OrderProductPromotions->patchEntity($orderProductPromotion, $this->request->data['order_product_promotion']);
					$orderProductPromotionResult = $OrderProductPromotions->save($orderProductPromotion);			
					} 
				}
			}
		$this->emptyCart($cartDetail->id);		
			
}
			
return true;
	}	
	
//************************************************
// Method to Empty cart on Success checkout
//************************************************		
	public function emptyCart($id){
		$Carts = TableRegistry::get('Carts');
		 $cart = $Carts->get($id);
        if ($this->Orders->delete($cart)) {
		return true;
		}	
	}	

//************************************************
// Method return url on unsuccessful checkout
//************************************************		
	public function cancel(){
			
	}	
	
	
	
	
	
}
