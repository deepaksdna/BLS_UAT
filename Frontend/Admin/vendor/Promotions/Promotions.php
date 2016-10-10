<?php
namespace Promotions;
use Cake\Datasource\ConnectionManager;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class Promotions
{
	public $imagePath = IMG_PATH_PRODUCTS;

    
	 public function getOutVarInfo($productID){

		$productDetails = $this->getProductInfo($productID);
		$productDiscountDetails = $this->getDiscountDisplayProduct($productID,1);
		$arrExtrVars=array();

		 if(is_array($productDiscountDetails['APPLIED_PROMOTIONS']['LIST'])){

							

						@$arrExtrVars['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$productDiscountDetails['APPLIED_PROMOTIONS']['LIST']['BEST_DISCOUNT_AMT_WITHOUT_GST'];

						@$arrExtrVars['FINAL_DISPLAY_PRICE_WITH_GST']=$productDiscountDetails['APPLIED_PROMOTIONS']['LIST']['BEST_DISCOUNT_AMT_WITH_GST'];
				}else{
					
					@$arrExtrVars['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$product['list_price'];
					@$arrExtrVars['FINAL_DISPLAY_PRICE_WITH_GST']=@$arrExtrVars['FINAL_DISPLAY_PRICE_WITHOUT_GST']*(100+GST)/100;
				}
		
				if(@$arrExtrVars['FINAL_DISPLAY_PRICE_WITHOUT_GST']==null){
					@$arrExtrVars['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$product['list_price'];
				}
				if(@$arrExtrVars['FINAL_DISPLAY_PRICE_WITH_GST']==null){
					@$arrExtrVars['FINAL_DISPLAY_PRICE_WITH_GST']=@$arrExtrVars['FINAL_DISPLAY_PRICE_WITHOUT_GST']*(100+GST)/100;

				}

				@$arrExtrVars['PRODUCT_DISCOUNT_TYPE']=$productDiscountDetails['PROMOTIONS_DISCOUNT_TYPE'];

			
		//	pre(@$arrExtrVars);
		return @$arrExtrVars;			
	} //10-101 

	

	 public function getDiscountDisplayProduct($productID,$cartQuantity=NULL,$allCartProdcuts=array()){
		ob_start();
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');		
		$connection = ConnectionManager::get('default');

		$sqlPromotions = "SELECT * FROM `promotions` WHERE product_id=".$productID." GROUP BY promo_code";		
		$getPromotions = $connection->execute($sqlPromotions)->fetchAll('assoc');

			//GET PRODUCT DETAILS..
			$sqlProduct = "
				SELECT 
				Product.id as PRODUCT_ID,
				Product.item_code as PRODUCT_ITEMCODE,
				Product.title as PRODUCT_TITLE,
				Product.product_desc as PRODUCT_DESC,
				Product.created as PRODUCT_CREATED,
				ProductsAttr.brand_id as PRODUCT_BRANDID,
				ProductsAttr.model as PRODUCT_MODEL,
				ProductsAttr.video_link as PRODUCT_VIDEOLINK,
				ProductsAttr.size as PRODUCT_SIZE,
				ProductsAttr.weight as PRODUCT_WEIGHT,
				ProductsAttr.packaging as PRODUCT_PACKAGING,
				ProductsAttr.uom as PRODUCT_UOM,
				ProductsAttr.quantity as PRODUCT_QUANTITY,
				ProductsAttr.main_promo_1 as PRODUCT_PROMO1,
				ProductsAttr.main_promo_2 as PRODUCT_PROMO2,
				ProductsAttr.main_promo_3 as PRODUCT_PROMO3,
				ProductsMarketingImages.image as PRODUCT_MARKETING_IMAGE,
				ProductsMarketingImages.image_dir as PRODUCT_MARKETING_IMAGE_DIR,
				ProductsMarketingImages.*,
				ProductsPrices.*,
				ProductsCategory.*
			FROM
				products as Product
				LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
				LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
				LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
				LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
			WHERE Product.id=".$productID." 

			GROUP By Product.id
			";
			$products['PRODUCT_DETAILS'] = @$connection->execute($sqlProduct)->fetchAll('assoc')[0];
		
		//pre($products['PRODUCT_DETAILS']);
		$promotionProducts = array();

		$promo_counter = 0;
		foreach($getPromotions as $key=>$promos){

			$product_price = $products['PRODUCT_DETAILS']['list_price'];

			//$promos['start_date']='2016-08-04';
			//$promos['end_date']='2016-08-31';

			//pre($promos);die('xsss');

			$getPromotionStartDate = strtotime($promos['start_date']);
			$getPromotionEndDate = strtotime($promos['end_date']);
			$current_date = strtotime(date("Y-m-d"));
			
			if($getPromotionStartDate <= $current_date AND $current_date <= $getPromotionEndDate) {
				$active_product = 1;
				
				//MESSAGE GOES TO DISPLAY ABOUT PROMOTION
				$promotionProducts['PROMOTIONS_STATUS']='ACTIVE';
				$promotionProducts['PROMOTIONS_STATUS_CODE']=1;
				$promotionProducts['PROMOTIONS_DISCOUNT_TYPE']=$promos['discount_type'];
				$promotionProducts['APPLIED_PROMOTIONS'][$key]['MESSAGE'] = $promos['formal_message_display'];
				$promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNT_TYPE']=@$promos['discount_type'];

				//pre($promos);
						$childProductInfo = $this->getProductInfo($promos['child_product_id']);
						$CHILD_PRODUCT_NAME = @$childProductInfo['PRODUCT_TITLE'];
						$UNIT_LIST_PRICE_CHILD = @$childProductInfo['list_price'];
						$parentProductInfo = $this->getProductInfo($promos['product_id']);
						$PARENT_PRODUCT_NAME = $parentProductInfo['PRODUCT_TITLE'];
						$UNIT_LIST_PRICE_PARENT = $parentProductInfo['list_price'];
						$parentProductId = (int)$promos['product_id'];
						$desiredQuantityParent = (int)$promos['quantity_parent'];
						$discountPrcParent = $promos['discounted_amount_parent'];
						$discountPrcChild = $promos['discounted_amount_child'];

				
				
				switch(@$promos['discount_type']){
					case 'D':			
							//pre($promos);
							$desiredQuantity = (int)$promos['quantity_parent'];
							$discountAmountParent = $promos['discounted_amount_parent'];
							$discountTypeIn = $promos['discount_parent_in'];
							$selectedQuantity = $cartQuantity;
							$promotionProducts['APPLIED_PROMOTIONS'][$key]['SHOW_POPUP_FOR_CHILD_PRODUCT']=0;

							$TOTAL_LIST_PRICE = $selectedQuantity * $products['PRODUCT_DETAILS']['list_price'];
							if($selectedQuantity >= $desiredQuantity){
								//CHECK CART AMOUNT......
								
								if($discountTypeIn=='%'){
								//-----------------------
									$discountedAmountWithoutGst = $TOTAL_LIST_PRICE *(100-$discountAmountParent)/100;
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_LISTPRICE_WITHOUT_GST']= $discountedAmountWithoutGst;
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['DIRECT_DISCOUNT_STATUS']=1;
									
									// DIRECT DISCOUNT WITH GST
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_LISTPRICE_WITH_GST'] = ($promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_LISTPRICE_WITHOUT_GST']*(100+GST)/100);
								//---------------------------
								}else if($discountTypeIn=='$'){
									
									//-----------------------
									$discountedAmountWithoutGst = $TOTAL_LIST_PRICE - $discountAmountParent;
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_LISTPRICE_WITHOUT_GST']= $discountedAmountWithoutGst;
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['DIRECT_DISCOUNT_STATUS']=1;
									
									// DIRECT DISCOUNT WITH GST
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_LISTPRICE_WITH_GST'] = ($promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_LISTPRICE_WITHOUT_GST']*(100+GST)/100);
								//---------------------------
								}
								

							}else{
									$discountedAmountWithoutGst = $TOTAL_LIST_PRICE;
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_LISTPRICE_WITHOUT_GST']= $discountedAmountWithoutGst;
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['DIRECT_DISCOUNT_STATUS']=1;
									
									// DIRECT DISCOUNT WITH GST
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_LISTPRICE_WITH_GST'] = ($promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_LISTPRICE_WITHOUT_GST']*(100+GST)/100);
							}

							
							
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_AMOUNT_PARENT']= $discountAmountParent;
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_AMOUNT_PARENT_IN']= $discountTypeIn;
							
						break;
					case 'B':

					//	pre($promos);
						$desiredQuantity = (int)$promos['quantity_parent'];
						$discountPrc = $promos['discounted_amount_parent'];
						$discountTypeIn = $promos['discount_parent_in'];

						$promotionProducts['APPLIED_PROMOTIONS'][$key]['SHOW_POPUP_FOR_CHILD_PRODUCT']=0;

						if($cartQuantity!=NULL){

							if($cartQuantity >= $desiredQuantity){

								if($discountTypeIn=='%'){

									$total_amount_price = $cartQuantity * (int)$products['PRODUCT_DETAILS']['list_price'];
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_AMOUNT_FOR_BUNDLE_DEAL_WITHOUT_GST'] = $total_amount_price * (100-$discountPrc)/100;

									// BUNDLE DISCOUNT WITH GST
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_AMOUNT_FOR_BUNDLE_DEAL_WTIH_GST'] = ($promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_AMOUNT_FOR_BUNDLE_DEAL_WITHOUT_GST']*(100+GST)/100);
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['BUNDLE_DEAL_STATUS']=1;

							    }else if($discountTypeIn=='$'){

									$total_amount_price = $cartQuantity * (int)$products['PRODUCT_DETAILS']['list_price'];
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_AMOUNT_FOR_BUNDLE_DEAL_WITHOUT_GST'] = $total_amount_price - $discountPrc;
									// BUNDLE DISCOUNT WITH GST
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_AMOUNT_FOR_BUNDLE_DEAL_WTIH_GST'] = ($promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_AMOUNT_FOR_BUNDLE_DEAL_WITHOUT_GST']*(100+GST)/100);
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['BUNDLE_DEAL_STATUS']=1;

								
								}

							}else{
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['BUNDLE_DEAL_STATUS']=0;
									//No Discount Here..
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_AMOUNT_FOR_BUNDLE_DEAL_WITHOUT_GST'] = $cartQuantity * $products['PRODUCT_DETAILS']['list_price'];
									// BUNDLE DISCOUNT WITH GST
									$promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_AMOUNT_FOR_BUNDLE_DEAL_WTIH_GST'] = ($promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_AMOUNT_FOR_BUNDLE_DEAL_WITHOUT_GST']*(100+GST)/100);

							}



							
								$promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_AMOUNT_PARENT']= $discountPrc;
								$promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNTED_AMOUNT_PARENT_IN']= $discountTypeIn;

						
						}

						
						

						//LAYOUT IMAGE DISPLAY
						//$promotionProducts['OVERLAY_IMAGE']['DISPLAY']='20%';
						//--------------------
						break;
					case 'P':
						
						$current_parent_product_quantity = $cartQuantity;
						$promotionProducts['APPLIED_PROMOTIONS'][$key]['PARENT_PRODUCT_INFO']=$parentProductInfo;
						$promotionProducts['APPLIED_PROMOTIONS'][$key]['CHILD_PRODUCT_INFO']=$childProductInfo;
						$promotionProducts['APPLIED_PROMOTIONS'][$key]['SHOW_POPUP_FOR_CHILD_PRODUCT']=1;
						$promotionProducts['APPLIED_PROMOTIONS'][$key]['CHILD_OVERLAY_IMAGE']['DISPLAY']=$discountPrcChild.$promos['discount_child_in'];


						
						if(count($allCartProdcuts) > 0){
							$child_product_id = (int)$promos['child_product_id'];

							$applied_child_product_quantity = (int)$promos['quantity_child'];
							//pre($allCartProdcuts);
							foreach($allCartProdcuts['CART']['Product'] as $cart_products){

								

								//CHILD PRODUCT HANDLING...

								if($cart_products['PRODUCT_ID']==$child_product_id){

										$current_child_product_quantity = $cart_products['PRODUCT_QUANTITY'];

										//PARENT PRODUCT IN CART
										//CHECK HOW MUCH PARENT QUANTITY...

										foreach($allCartProdcuts['CART']['Product'] as $cart_products_for_parent){

												if($cart_products_for_parent['PRODUCT_ID']==$parentProductId){

														$current_parent_product_quantity = $cart_products_for_parent['PRODUCT_QUANTITY'];

												}
										}
											
											
												//FOR PARENT

												$numberNDPs =  (int)$current_parent_product_quantity % $desiredQuantityParent;
												$numberDPs	= (int)($current_parent_product_quantity - $numberNDPs)/$desiredQuantityParent;

										//-----------------------------------------------------------------------
										// CHECK HOW MANY CHILDS IN CART

										
										

										$promotionProducts['APPLIED_PROMOTIONS'][$key]['PWP_NOTICE_1']='You are entitled '.$discountPrcChild.''.$promos['discount_child_in'].' discount on '.$CHILD_PRODUCT_NAME.' along with for buying '.$desiredQuantityParent.' or more '.$PARENT_PRODUCT_NAME.'.';

										//####### CALCULATE CHILD PRODUCT COST ############## 

										// APPLY PROMOCODE IF CONDITION TRUE
										if($numberDPs > 0){


											$TOTAL_PRODUCTS_ON_DISCOUNT_CAN_BE = $applied_child_product_quantity * $numberDPs;

											$TOTAL_PRODUCTS_IN_CART = $cart_products['PRODUCT_QUANTITY'];

											if($cart_products['PRODUCT_QUANTITY'] > $TOTAL_PRODUCTS_ON_DISCOUNT_CAN_BE){

												$TOTAL_CHILD_NON_DISCOUNTED_PRODUCTS = $cart_products['PRODUCT_QUANTITY'] - $TOTAL_PRODUCTS_ON_DISCOUNT_CAN_BE;
												$TOTAL_CHILD_DISCOUNTED_PRODUCTS = $TOTAL_PRODUCTS_ON_DISCOUNT_CAN_BE;
											
											}else{
												$TOTAL_CHILD_NON_DISCOUNTED_PRODUCTS = 0;
												$TOTAL_CHILD_DISCOUNTED_PRODUCTS = $cart_products['PRODUCT_QUANTITY'];
											}

											

											
											$CHILDS_NOT_DISCOUNTED = $cart_products['PRODUCT_QUANTITY'] % $applied_child_product_quantity;
											$CHILDS_ON_DISCOUNTED = $cart_products['PRODUCT_QUANTITY'] - $CHILDS_NOT_DISCOUNTED;

											$CHILDS_ON_DISCOUNTED_PACKET = $CHILDS_ON_DISCOUNTED/$applied_child_product_quantity;
											$CHILDS_CAN_BE_DISCOUNTED_PACKET = $CHILDS_NOT_DISCOUNTED/$applied_child_product_quantity;
											//echo $numberDPs;

											//CALCULATED DISCOUNTED PACKAGE...

											$REQUIRED_CHILDS_ITEMS = ($numberDPs * $applied_child_product_quantity) - $cart_products['PRODUCT_QUANTITY'];

											//--------------------------------

											//$CHILDS_DISCOUNTED_BUNDLE = $CHILDS_ON_DISCOUNTED / $applied_child_product_quantity;
											
											$NON_DISCOUNTED_CHILD_PRODUCTS = $cart_products['PRODUCT_QUANTITY'] - $CHILDS_ON_DISCOUNTED;

											

											if($NON_DISCOUNTED_CHILD_PRODUCTS < 0){
												$NON_DISCOUNTED_CHILD_PRODUCTS = abs($NON_DISCOUNTED_CHILD_PRODUCTS);
											}

											
											// CHECK DISCOUNT IN % or $
												
											if($promos['discount_child_in']=='%'){
												//die('111');

												$LISTPRICE_TOTAL_DISCOUNTED_CHILDS = $TOTAL_CHILD_DISCOUNTED_PRODUCTS * $UNIT_LIST_PRICE_CHILD;

												$DISCOUNTED_PRICE_CHILD_PRODUCTS_TOTAL =  $LISTPRICE_TOTAL_DISCOUNTED_CHILDS * (100-$promos['discounted_amount_child'])/100;

												$NON_DISCOUNTED_CHILD_PRODUCTS_TOTAL = $TOTAL_CHILD_NON_DISCOUNTED_PRODUCTS * $UNIT_LIST_PRICE_CHILD;


												
												$TOTAL_COST_CHILD_PRODUCTS_WITHOUT_GST = $DISCOUNTED_PRICE_CHILD_PRODUCTS_TOTAL + $NON_DISCOUNTED_CHILD_PRODUCTS_TOTAL;

												$promotionProducts['APPLIED_PROMOTIONS'][$key]['PWP_CHILD_PRODUCT_DISCOUNTED_AMT_WITHOUT_GST']=$TOTAL_COST_CHILD_PRODUCTS_WITHOUT_GST;

												$TOTAL_COST_CHILD_PRODUCTS_WITH_GST = $TOTAL_COST_CHILD_PRODUCTS_WITHOUT_GST*(100+GST)/100;

												$promotionProducts['APPLIED_PROMOTIONS'][$key]['PWP_CHILD_PRODUCT_DISCOUNTED_AMT_WITH_GST']=$TOTAL_COST_CHILD_PRODUCTS_WITH_GST;

												$promotionProducts['APPLIED_PROMOTIONS'][$key]['PWP_CHILD_PRODUCT_DISCOUNTED_STATUS']=1;

											}else if($promos['discount_child_in']=='$'){	

												//die('222');
												//echo $TOTAL_PRODUCTS_ON_DISCOUNT_CAN_BE;

													if($cart_products['PRODUCT_QUANTITY'] < $TOTAL_PRODUCTS_ON_DISCOUNT_CAN_BE){
														//echo $numberDPs;
														$TOTAL_DISCOUNT_TO_BE_DEDUCTED = '';

														$TOTAL_NONDOSCOUNTED_CHILD_PRODUCT_Quantity = $cart_products['PRODUCT_QUANTITY'] % $applied_child_product_quantity;
														$TOTAL_DISCOUNTED_CHILD_PRODUCT_Quantity = $cart_products['PRODUCT_QUANTITY'] - $TOTAL_NONDOSCOUNTED_CHILD_PRODUCT_Quantity;

														$TOTAL_NONDOSCOUNTED_CHILD_PRODUCT_AMT = $TOTAL_NONDOSCOUNTED_CHILD_PRODUCT_Quantity * $UNIT_LIST_PRICE_CHILD;


														if($TOTAL_DISCOUNTED_CHILD_PRODUCT_Quantity > 0){
															$TOTAL_DISCOUNTED_CHILD_PRODUCT_AMT = ($TOTAL_DISCOUNTED_CHILD_PRODUCT_Quantity * $UNIT_LIST_PRICE_CHILD)-$promos['discounted_amount_child'];
														}else{
															$TOTAL_DISCOUNTED_CHILD_PRODUCT_AMT=0;
														}

														

														//--------------------------------------------------------------
													}else{
														$TOTAL_DISCOUNT_TO_BE_DEDUCTED = $numberDPs * $promos['discounted_amount_child'];
														$TOTAL_DISCOUNTED_CHILD_PRODUCT_AMT = ($TOTAL_PRODUCTS_ON_DISCOUNT_CAN_BE * $UNIT_LIST_PRICE_CHILD) - $TOTAL_DISCOUNT_TO_BE_DEDUCTED;
														$TOTAL_NONDOSCOUNTED_CHILD_PRODUCT_AMT = ($cart_products['PRODUCT_QUANTITY']-$TOTAL_PRODUCTS_ON_DISCOUNT_CAN_BE) * $UNIT_LIST_PRICE_CHILD;
													
													}

													$LISTPRICE_TOTAL_DISCOUNTED_CHILDS = $TOTAL_CHILD_DISCOUNTED_PRODUCTS * $UNIT_LIST_PRICE_CHILD;
													//echo '-->'.$TOTAL_CHILD_DISCOUNTED_PRODUCTS;
													$TOTAL_COST_CHILD_PRODUCTS_WITHOUT_GST = $TOTAL_DISCOUNTED_CHILD_PRODUCT_AMT + $TOTAL_NONDOSCOUNTED_CHILD_PRODUCT_AMT;

													$promotionProducts['APPLIED_PROMOTIONS'][$key]['PWP_CHILD_PRODUCT_DISCOUNTED_AMT_WITHOUT_GST']=$TOTAL_COST_CHILD_PRODUCTS_WITHOUT_GST;

													$TOTAL_COST_CHILD_PRODUCTS_WITH_GST = $TOTAL_COST_CHILD_PRODUCTS_WITHOUT_GST*(100+GST)/100;

													$promotionProducts['APPLIED_PROMOTIONS'][$key]['PWP_CHILD_PRODUCT_DISCOUNTED_AMT_WITH_GST']=$TOTAL_COST_CHILD_PRODUCTS_WITH_GST;
													$promotionProducts['APPLIED_PROMOTIONS'][$key]['PWP_CHILD_PRODUCT_DISCOUNTED_STATUS']=1;
													
											}


											//###################### END CHILD PRODUCTS ##########

											
										}else{
											
											//IF PARENT PRODUCTS LESS THAN DESIRED PRODUCTS ...
											$promotionProducts['APPLIED_PROMOTIONS'][$key]['PWP_CHILD_PRODUCT_DISCOUNTED_AMT_WITHOUT_GST'] = $cart_products['PRODUCT_QUANTITY'] * $UNIT_LIST_PRICE_CHILD;	

											$promotionProducts['APPLIED_PROMOTIONS'][$key]['PWP_CHILD_PRODUCT_DISCOUNTED_AMT_WITH_GST']= $promotionProducts['APPLIED_PROMOTIONS'][$key]['PWP_CHILD_PRODUCT_DISCOUNTED_AMT_WITHOUT_GST']*(100+GST)/100;
											$promotionProducts['APPLIED_PROMOTIONS'][$key]['PWP_CHILD_PRODUCT_DISCOUNTED_STATUS']=0;
												
											//-------------------------------------------------
										}

										
											//####### CALCULATE PARENT PRODUCT COST ##############

											if($promos['discount_parent_in']=='%'){

											    						
												$Total_Parent_Product_Price = $UNIT_LIST_PRICE_PARENT * $current_parent_product_quantity;
												$DISCOUNTED_AMOUNT_PARENT_WITHOUT_GST = $Total_Parent_Product_Price *(100-$discountPrcParent)/100;

												$promotionProducts['APPLIED_PROMOTIONS'][$key]['PWP_PARENT_PRODUCT_DISCOUNTED_AMT_WITHOUT_GST'] = $DISCOUNTED_AMOUNT_PARENT_WITHOUT_GST;
												$promotionProducts['APPLIED_PROMOTIONS'][$key]['PWP_PARENT_PRODUCT_DISCOUNTED_AMT_WITH_GST'] =$DISCOUNTED_AMOUNT_PARENT_WITHOUT_GST*(100+GST)/100;

											}else if($promos['discount_parent_in']=='$'){
												
												$Total_Parent_Product_Price = $UNIT_LIST_PRICE_PARENT * $current_parent_product_quantity;
												$DISCOUNTED_AMOUNT_PARENT_WITHOUT_GST = $Total_Parent_Product_Price - $discountPrcParent;

												$promotionProducts['APPLIED_PROMOTIONS'][$key]['PWP_PARENT_PRODUCT_DISCOUNTED_AMT_WITHOUT_GST'] = $DISCOUNTED_AMOUNT_PARENT_WITHOUT_GST;

												$promotionProducts['APPLIED_PROMOTIONS'][$key]['PWP_PARENT_PRODUCT_DISCOUNTED_AMT_WITH_GST'] =$DISCOUNTED_AMOUNT_PARENT_WITHOUT_GST*(100+GST)/100;
											}
									    //----------------------------------------------------
																					
										//-----------------------------------------------------
										if(@$REQUIRED_CHILDS_ITEMS > 0){

										$promotionProducts['APPLIED_PROMOTIONS'][$key]['PWP_NOTICE_2']="Buy  ".$REQUIRED_CHILDS_ITEMS." more to get ".$discountPrcChild."".$promos['discount_child_in']." discount on ".$CHILD_PRODUCT_NAME." along with for buying ".$desiredQuantityParent." or more ".$PARENT_PRODUCT_NAME."";

										}
										
								
								}//----------------------------------------------------------------

								//PARENT PRODUCT HANDLING...

								if($cart_products['PRODUCT_ID']==$parentProductId){
									//CHECK PARENT PRODUCT QNTITY



									//---------------------------
								
								}

								//End Parent Product Handling..


							
							}
							
						}else{ // COUNT CART PRODUCTS CONDITION

							
							$promotionProducts['APPLIED_PROMOTIONS'][$key]['PWP_STATUS']=0;

						}
						
					break;
					case 'F':
						//pre($promos);
						$childProductInfo = @$this->getProductInfo($promos['child_product_id']);
						$CHILD_PRODUCT_NAME = @$childProductInfo['PRODUCT_TITLE'];
						$UNIT_LIST_PRICE_CHILD = @$childProductInfo['list_price'];
						$parentProductInfo = $this->getProductInfo($promos['product_id']);
						$PARENT_PRODUCT_NAME = $parentProductInfo['PRODUCT_TITLE'];
						$UNIT_LIST_PRICE_PARENT = $parentProductInfo['list_price'];

						$parentProductId = (int)$promos['product_id'];
						$desiredQuantityParent = (int)$promos['quantity_parent'];

						$discountPrcParent = $promos['discounted_amount_parent'];

						$discountPrcChild = $promos['discounted_amount_child'];

						$current_parent_product_quantity = $cartQuantity;


						$promotionProducts['APPLIED_PROMOTIONS'][$key]['PARENT_PRODUCT_INFO']=$parentProductInfo;
						$promotionProducts['APPLIED_PROMOTIONS'][$key]['CHILD_PRODUCT_INFO']=$childProductInfo;
						$promotionProducts['APPLIED_PROMOTIONS'][$key]['SHOW_POPUP_FOR_CHILD_PRODUCT']=1;
						$promotionProducts['APPLIED_PROMOTIONS'][$key]['CHILD_OVERLAY_IMAGE']['DISPLAY']='Free';
						
						//$promotionProducts['APPLIED_PROMOTIONS'][$key]['FOC_PARENT_PRODUCT_DISCOUNTED_AMT_WITHOUT_GST'] = 'list_price';
						if(count($allCartProdcuts) > 0){
							$child_product_id = (int)$promos['child_product_id'];

							$applied_child_product_quantity = (int)$promos['quantity_child'];
						//	pre($allCartProdcuts);
							foreach($allCartProdcuts['CART']['Product'] as $cart_products){

								
		
								//CHILD PRODUCT HANDLING...

								if($cart_products['PRODUCT_ID']==$child_product_id){

										$current_child_product_quantity = $cart_products['PRODUCT_QUANTITY'];

										//PARENT PRODUCT IN CART
										//CHECK HOW MUCH PARENT QUANTITY...

										foreach($allCartProdcuts['CART']['Product'] as $cart_products_for_parent){

												if($cart_products_for_parent['PRODUCT_ID']==$parentProductId){

														$current_parent_product_quantity = $cart_products_for_parent['PRODUCT_QUANTITY'];

												}
										}
											
											
												//FOR PARENT

												$numberNDPs =  (int)$current_parent_product_quantity % $desiredQuantityParent;
												$numberDPs	= (int)($current_parent_product_quantity - $numberNDPs)/$desiredQuantityParent;

										//-----------------------------------------------------------------------
										// CHECK HOW MANY CHILDS IN CART

										
										

										$promotionProducts['APPLIED_PROMOTIONS'][$key]['FOC_NOTICE_1']='You are entitled '.$discountPrcChild.''.$promos['discount_child_in'].' discount on '.$CHILD_PRODUCT_NAME.' along with for buying '.$desiredQuantityParent.' or more '.$PARENT_PRODUCT_NAME.'.';

										//####### CALCULATE CHILD PRODUCT COST ############## 

										// APPLY PROMOCODE IF CONDITION TRUE
										if($numberDPs > 0){


											$TOTAL_PRODUCTS_ON_DISCOUNT_CAN_BE = $applied_child_product_quantity * $numberDPs;

											$TOTAL_PRODUCTS_IN_CART = $cart_products['PRODUCT_QUANTITY'];

											if($cart_products['PRODUCT_QUANTITY'] > $TOTAL_PRODUCTS_ON_DISCOUNT_CAN_BE){

												$TOTAL_CHILD_NON_DISCOUNTED_PRODUCTS = $cart_products['PRODUCT_QUANTITY'] - $TOTAL_PRODUCTS_ON_DISCOUNT_CAN_BE;
												$TOTAL_CHILD_DISCOUNTED_PRODUCTS = $TOTAL_PRODUCTS_ON_DISCOUNT_CAN_BE;
											
											}else{
												$TOTAL_CHILD_NON_DISCOUNTED_PRODUCTS = 0;
												$TOTAL_CHILD_DISCOUNTED_PRODUCTS = $cart_products['PRODUCT_QUANTITY'];
											}

											

											
											$CHILDS_NOT_DISCOUNTED = $cart_products['PRODUCT_QUANTITY'] % $applied_child_product_quantity;
											$CHILDS_ON_DISCOUNTED = $cart_products['PRODUCT_QUANTITY'] - $CHILDS_NOT_DISCOUNTED;

											$CHILDS_ON_DISCOUNTED_PACKET = $CHILDS_ON_DISCOUNTED/$applied_child_product_quantity;
											$CHILDS_CAN_BE_DISCOUNTED_PACKET = $CHILDS_NOT_DISCOUNTED/$applied_child_product_quantity;
											//echo $numberDPs;

											

											//CALCULATED DISCOUNTED PACKAGE...

											$REQUIRED_CHILDS_ITEMS = ($numberDPs * $applied_child_product_quantity) - $cart_products['PRODUCT_QUANTITY'];

											//--------------------------------

											//$CHILDS_DISCOUNTED_BUNDLE = $CHILDS_ON_DISCOUNTED / $applied_child_product_quantity;
											
											$NON_DISCOUNTED_CHILD_PRODUCTS = $cart_products['PRODUCT_QUANTITY'] - $CHILDS_ON_DISCOUNTED;

											

											if($NON_DISCOUNTED_CHILD_PRODUCTS < 0){
												$NON_DISCOUNTED_CHILD_PRODUCTS = abs($NON_DISCOUNTED_CHILD_PRODUCTS);
											}

											
											// CHECK DISCOUNT IN % or $
												
											if($promos['discount_child_in']=='%'){
												//die('111');

												$LISTPRICE_TOTAL_DISCOUNTED_CHILDS = $TOTAL_CHILD_DISCOUNTED_PRODUCTS * $UNIT_LIST_PRICE_CHILD;

												$DISCOUNTED_PRICE_CHILD_PRODUCTS_TOTAL =  $LISTPRICE_TOTAL_DISCOUNTED_CHILDS * (100-$promos['discounted_amount_child'])/100;

												$NON_DISCOUNTED_CHILD_PRODUCTS_TOTAL = $TOTAL_CHILD_NON_DISCOUNTED_PRODUCTS * $UNIT_LIST_PRICE_CHILD;
												
												$TOTAL_COST_CHILD_PRODUCTS_WITHOUT_GST = $DISCOUNTED_PRICE_CHILD_PRODUCTS_TOTAL + $NON_DISCOUNTED_CHILD_PRODUCTS_TOTAL;

												$promotionProducts['APPLIED_PROMOTIONS'][$key]['FOC_CHILD_PRODUCT_DISCOUNTED_AMT_WITHOUT_GST']=$TOTAL_COST_CHILD_PRODUCTS_WITHOUT_GST;

												$TOTAL_COST_CHILD_PRODUCTS_WITH_GST = $TOTAL_COST_CHILD_PRODUCTS_WITHOUT_GST*(100+GST)/100;

												$promotionProducts['APPLIED_PROMOTIONS'][$key]['FOC_CHILD_PRODUCT_DISCOUNTED_AMT_WITH_GST']=$TOTAL_COST_CHILD_PRODUCTS_WITH_GST;

												$promotionProducts['APPLIED_PROMOTIONS'][$key]['FOC_CHILD_PRODUCT_DISCOUNTED_STATUS']=1;

											}else if($promos['discount_child_in']=='$'){	

												//die('222');
												//echo $TOTAL_PRODUCTS_ON_DISCOUNT_CAN_BE;

													if($cart_products['PRODUCT_QUANTITY'] < $TOTAL_PRODUCTS_ON_DISCOUNT_CAN_BE){
														//echo $numberDPs;
														$TOTAL_DISCOUNT_TO_BE_DEDUCTED = '';

														$TOTAL_NONDOSCOUNTED_CHILD_PRODUCT_Quantity = $cart_products['PRODUCT_QUANTITY'] % $applied_child_product_quantity;
														$TOTAL_DISCOUNTED_CHILD_PRODUCT_Quantity = $cart_products['PRODUCT_QUANTITY'] - $TOTAL_NONDOSCOUNTED_CHILD_PRODUCT_Quantity;

														$TOTAL_NONDOSCOUNTED_CHILD_PRODUCT_AMT = $TOTAL_NONDOSCOUNTED_CHILD_PRODUCT_Quantity * $UNIT_LIST_PRICE_CHILD;


														if($TOTAL_DISCOUNTED_CHILD_PRODUCT_Quantity > 0){
															$TOTAL_DISCOUNTED_CHILD_PRODUCT_AMT = ($TOTAL_DISCOUNTED_CHILD_PRODUCT_Quantity * $UNIT_LIST_PRICE_CHILD)-$promos['discounted_amount_child'];
														}else{
															$TOTAL_DISCOUNTED_CHILD_PRODUCT_AMT=0;
														}

														

														//--------------------------------------------------------------
													}else{
														$TOTAL_DISCOUNT_TO_BE_DEDUCTED = $numberDPs * $promos['discounted_amount_child'];
														$TOTAL_DISCOUNTED_CHILD_PRODUCT_AMT = ($TOTAL_PRODUCTS_ON_DISCOUNT_CAN_BE * $UNIT_LIST_PRICE_CHILD) - $TOTAL_DISCOUNT_TO_BE_DEDUCTED;
														$TOTAL_NONDOSCOUNTED_CHILD_PRODUCT_AMT = ($cart_products['PRODUCT_QUANTITY']-$TOTAL_PRODUCTS_ON_DISCOUNT_CAN_BE) * $UNIT_LIST_PRICE_CHILD;
													
													}
													$LISTPRICE_TOTAL_DISCOUNTED_CHILDS = $TOTAL_CHILD_DISCOUNTED_PRODUCTS * $UNIT_LIST_PRICE_CHILD;
													//echo '-->'.$TOTAL_CHILD_DISCOUNTED_PRODUCTS;
													$TOTAL_COST_CHILD_PRODUCTS_WITHOUT_GST = $TOTAL_DISCOUNTED_CHILD_PRODUCT_AMT + $TOTAL_NONDOSCOUNTED_CHILD_PRODUCT_AMT;

													$promotionProducts['APPLIED_PROMOTIONS'][$key]['FOC_CHILD_PRODUCT_DISCOUNTED_AMT_WITHOUT_GST']=$TOTAL_COST_CHILD_PRODUCTS_WITHOUT_GST;

													$TOTAL_COST_CHILD_PRODUCTS_WITH_GST = $TOTAL_COST_CHILD_PRODUCTS_WITHOUT_GST*(100+GST)/100;

													$promotionProducts['APPLIED_PROMOTIONS'][$key]['FOC_CHILD_PRODUCT_DISCOUNTED_AMT_WITH_GST']=$TOTAL_COST_CHILD_PRODUCTS_WITH_GST;
													$promotionProducts['APPLIED_PROMOTIONS'][$key]['FOC_CHILD_PRODUCT_DISCOUNTED_STATUS']=1;
													
											}


											//###################### END CHILD PRODUCTS ##########

											
										}else{
											
											//IF PARENT PRODUCTS LESS THAN DESIRED PRODUCTS ...
											$promotionProducts['APPLIED_PROMOTIONS'][$key]['FOC_CHILD_PRODUCT_DISCOUNTED_AMT_WITHOUT_GST'] = $cart_products['PRODUCT_QUANTITY'] * $UNIT_LIST_PRICE_CHILD;	

											$promotionProducts['APPLIED_PROMOTIONS'][$key]['FOC_CHILD_PRODUCT_DISCOUNTED_AMT_WITH_GST']= $promotionProducts['APPLIED_PROMOTIONS'][$key]['FOC_CHILD_PRODUCT_DISCOUNTED_AMT_WITHOUT_GST']*(100+GST)/100;
											$promotionProducts['APPLIED_PROMOTIONS'][$key]['FOC_CHILD_PRODUCT_DISCOUNTED_STATUS']=0;
												
											//-------------------------------------------------
										}

										//####### CALCULATE PARENT PRODUCT COST ##############

											if($promos['discount_parent_in']=='%'){

											    						
												$Total_Parent_Product_Price = $UNIT_LIST_PRICE_PARENT * $current_parent_product_quantity;
												$DISCOUNTED_AMOUNT_PARENT_WITHOUT_GST = $Total_Parent_Product_Price *(100-$discountPrcParent)/100;

												$promotionProducts['APPLIED_PROMOTIONS'][$key]['FOC_PARENT_PRODUCT_DISCOUNTED_AMT_WITHOUT_GST'] = $DISCOUNTED_AMOUNT_PARENT_WITHOUT_GST;
												$promotionProducts['APPLIED_PROMOTIONS'][$key]['FOC_PARENT_PRODUCT_DISCOUNTED_AMT_WITH_GST'] =$DISCOUNTED_AMOUNT_PARENT_WITHOUT_GST*(100+GST)/100;

											}else if($promos['discount_parent_in']=='$'){
												
												$Total_Parent_Product_Price = $UNIT_LIST_PRICE_PARENT * $current_parent_product_quantity;
												$DISCOUNTED_AMOUNT_PARENT_WITHOUT_GST = $Total_Parent_Product_Price - $discountPrcParent;

												$promotionProducts['APPLIED_PROMOTIONS'][$key]['FOC_PARENT_PRODUCT_DISCOUNTED_AMT_WITHOUT_GST'] = $DISCOUNTED_AMOUNT_PARENT_WITHOUT_GST;

												$promotionProducts['APPLIED_PROMOTIONS'][$key]['FOC_PARENT_PRODUCT_DISCOUNTED_AMT_WITH_GST'] =$DISCOUNTED_AMOUNT_PARENT_WITHOUT_GST*(100+GST)/100;
											}
									    //----------------------------------------------------
										//@$REQUIRED_CHILDS_ITEMS = abs(@$REQUIRED_CHILDS_ITEMS);	
										//echo '=====>'.@$REQUIRED_CHILDS_ITEMS;											
										//-----------------------------------------------------
										if(@$REQUIRED_CHILDS_ITEMS > 0){

										$promotionProducts['APPLIED_PROMOTIONS'][$key]['PWP_NOTICE_2']="Add  ".$REQUIRED_CHILDS_ITEMS." more in cart to get ".$discountPrcChild."".$promos['discount_child_in']." discount on ".$CHILD_PRODUCT_NAME." along with for buying ".$desiredQuantityParent." or more ".$PARENT_PRODUCT_NAME."";

										}
										
								
								}//----------------------------------------------------------------
							}
							
						}else{ // COUNT CART PRODUCTS CONDITION

							//$promotionProducts['APPLIED_PROMOTIONS'][$key]['PWP_STATUS']=0;
						}
						break;
					case 'T_OLD': // FOC PROMOTIONS...................................................../
				
						//-------------------------------------
						break;
					default:
						break;
		
				}
			
					//--------------------- END PROMOTION PRODUCTS ........................./
			}else{ // NO PROMOTION IS ACTIVATED IN CURRENT DATE FOR THIS PRODUCT.
			
				$active_product = 0;
			}
			
			$promo_counter = $promo_counter + 1;
			
		}//get promo code end forloop;
		
		$cost_price_without_gst=array();
		$cost_price_with_gst=array();
		$overlay_display_amount=array();

		//pre($promotionProducts['APPLIED_PROMOTIONS']);
		
		
		
		
		
		if(!empty($promotionProducts['APPLIED_PROMOTIONS'])){

			
		// CHECK FOR BEST DEAL........
		foreach($promotionProducts['APPLIED_PROMOTIONS'] as $key=>$appliedPromo){

		

			switch($appliedPromo['DISCOUNT_TYPE']){
				case 'D':
					//pre($promos);
						if($appliedPromo['DIRECT_DISCOUNT_STATUS']==1){
							$cost_price_without_gst[] = $appliedPromo['DISCOUNTED_LISTPRICE_WITHOUT_GST'];
							$cost_price_with_gst[] = $appliedPromo['DISCOUNTED_LISTPRICE_WITH_GST'];
							$overlay_display_amount[] = $appliedPromo['DISCOUNTED_AMOUNT_PARENT'];
						
						}
					break;
				case 'B':
						if(@$appliedPromo['BUNDLE_DEAL_STATUS']==1){
							$cost_price_without_gst[] = $appliedPromo['DISCOUNTED_AMOUNT_FOR_BUNDLE_DEAL_WITHOUT_GST'];
							$cost_price_with_gst[] = $appliedPromo['DISCOUNTED_AMOUNT_FOR_BUNDLE_DEAL_WTIH_GST'];
							//$overlay_display_amount[] = $appliedPromo['DISCOUNTED_AMOUNT_PARENT'];
						}
						$overlay_display_amount[] = $appliedPromo['DISCOUNTED_AMOUNT_PARENT'];
					break;
				case 'P':
						
						$overlay_display_amount[]=0;
						$promotionProducts['OVERLAY_IMAGE']['DISPLAY_TEXT']='PWP';
					break;
				case 'F':
						$overlay_display_amount[]=0;
						$promotionProducts['OVERLAY_IMAGE']['DISPLAY_TEXT']='FOC';
					break;
				default:
					break;
			
			}
		
				
		}

		}//end check if APPLIED_PROMOTIONS array exists.						
		//----------------------------

		
		$cost_price_without_gst = $this->remove_array_zero($cost_price_without_gst);	
		$cost_price_with_gst = $this->remove_array_zero($cost_price_with_gst);	
		$overlay_display_amount = $this->remove_array_zero($overlay_display_amount);
	
		
		//pre($cost_price_without_gst);
		//pre($cost_price_with_gst);
		//pre($overlay_display_amount);

		if(!empty($promotionProducts['APPLIED_PROMOTIONS'])){

			
			if(count($cost_price_without_gst) > 1)
			$promotionProducts['APPLIED_PROMOTIONS']['BEST_DISCOUNT_AMT_WITHOUT_GST']=min($cost_price_without_gst);
			else
			$promotionProducts['APPLIED_PROMOTIONS']['BEST_DISCOUNT_AMT_WITHOUT_GST']=@$cost_price_without_gst[0];


			
			if(count($cost_price_with_gst) > 1)
			$promotionProducts['APPLIED_PROMOTIONS']['BEST_DISCOUNT_AMT_WITH_GST']=min($cost_price_with_gst);		
			else
			$promotionProducts['APPLIED_PROMOTIONS']['BEST_DISCOUNT_AMT_WITH_GST']=@$cost_price_with_gst[0];



			if(count($overlay_display_amount) > 1)
			$promotionProducts['OVERLAY_IMAGE']['DISPLAY']=max($overlay_display_amount);
			else
			$promotionProducts['OVERLAY_IMAGE']['DISPLAY']=@$overlay_display_amount[0];
			

		//	$promotionProducts['APPLIED_PROMOTIONS']['BEST_DISCOUNT_AMT_WITHOUT_GST']=min($cost_price_without_gst);
		//	$promotionProducts['APPLIED_PROMOTIONS']['BEST_DISCOUNT_AMT_WITH_GST']=min($cost_price_with_gst);
		//	$promotionProducts['OVERLAY_IMAGE']['DISPLAY']=max($overlay_display_amount);


			if(!empty($promotionProducts['OVERLAY_IMAGE']['DISPLAY_TEXT'])){
					$promotionProducts['OVERLAY_IMAGE']['DISPLAY'] = $promotionProducts['OVERLAY_IMAGE']['DISPLAY_TEXT'];
			}

		}else{


			
		
		}

			if(is_array(@$promotionProducts['APPLIED_PROMOTIONS'])){

				//pre($promotionProducts['APPLIED_PROMOTIONS']);
				$i=0;
				foreach($promotionProducts['APPLIED_PROMOTIONS'] as $value){
					$arrGetPromoData1[$i]=$value;
					$i = (int)$i+1;
				
				}

				@$promotionProducts['APPLIED_PROMOTIONS']['LIST'] = $arrGetPromoData1;
					@$promotionProducts['APPLIED_PROMOTIONS']['LIST']['BEST_DISCOUNT_AMT_WITHOUT_GST']=@$promotionProducts['APPLIED_PROMOTIONS']['BEST_DISCOUNT_AMT_WITHOUT_GST'];
					@$promotionProducts['APPLIED_PROMOTIONS']['LIST']['BEST_DISCOUNT_AMT_WITH_GST']=@$promotionProducts['APPLIED_PROMOTIONS']['BEST_DISCOUNT_AMT_WITH_GST'];

			}else{
			
				@$promotionProducts['APPLIED_PROMOTIONS']['LIST']=$arrGetPromoData['APPLIED_PROMOTIONS'];
			}
			
			//$promotionProducts['FINAL_ITEM_PRICE_WITHOUT_GST'] = @$promotionProducts['APPLIED_PROMOTIONS']['BEST_DISCOUNT_AMT_WITHOUT_GST'];
			//$promotionProducts['FINAL_ITEM_PRICE_WITH_GST'] = @$promotionProducts['APPLIED_PROMOTIONS']['BEST_DISCOUNT_AMT_WITH_GST'];
				//$promotionProducts['FINAL_PRICE_WITHOUT_GST'] = @$promotionProducts['APPLIED_PROMOTIONS']['BEST_DISCOUNT_AMT_WITHOUT_GST'];
			//$promotionProducts['FINAL_ITEM_PRICE_WITH_GST'] = @$promotionProducts['APPLIED_PROMOTIONS']['BEST_DISCOUNT_AMT_WITH_GST'];
			
	return $promotionProducts;
			
	}

	function getProductInfo($productID){

		$connection = ConnectionManager::get('default');
		$sqlProduct = "
				SELECT 
				Product.id as PRODUCT_ID,
				Product.item_code as PRODUCT_ITEMCODE,
				Product.title as PRODUCT_TITLE,
				Product.status as PRODUCT_STATUS,
				Product.product_desc as PRODUCT_DESC,
				Product.created as PRODUCT_CREATED,
				ProductsAttr.brand_id as PRODUCT_BRANDID,
				ProductsAttr.model as PRODUCT_MODEL,
				ProductsAttr.video_link as PRODUCT_VIDEOLINK,
				ProductsAttr.size as PRODUCT_SIZE,
				ProductsAttr.weight as PRODUCT_WEIGHT,
				ProductsAttr.packaging as PRODUCT_PACKAGING,
				ProductsAttr.uom as PRODUCT_UOM,
				ProductsAttr.quantity as PRODUCT_QUANTITY,
				ProductsAttr.main_promo_1 as PRODUCT_PROMO1,
				ProductsAttr.main_promo_2 as PRODUCT_PROMO2,
				ProductsAttr.main_promo_3 as PRODUCT_PROMO3,
				ProductsMarketingImages.*,
				IF(ProductsMarketingImages.image ='', 'empty-product-img.png',ProductsMarketingImages.image) as image,
				ProductsPrices.*,	
				(ProductsPrices.list_price*(100+7)/100) as list_price_gst,
				ProductsCategory.*
			FROM
				products as Product
				LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
				LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
				LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
				LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
			WHERE Product.id=".$productID." GROUP By Product.id
			";

			//die($sqlProduct);

			$products['PRODUCT_DETAILS'] = @$connection->execute($sqlProduct)->fetchAll('assoc')[0];

			$products['PRODUCT_DETAILS']['IMAGE_HTTP_PATH']=Router::url('/', true).$this->imagePath.$products['PRODUCT_DETAILS']['image'];
			
			//pre($products['PRODUCT_DETAILS']);

			if(!empty($products['PRODUCT_DETAILS']['PRODUCT_BRANDID'])){

		//GET BRAND DETAILS
		
				$sqlBrandQry = "
				SELECT 
				name as BRAND_NAME,
				IF(image ='', 'empty-product-img.png',image) as image,
				templates as TEMPLATES		
				FROM `brands` WHERE id=".$products['PRODUCT_DETAILS']['PRODUCT_BRANDID'];

			
			
				$products['PRODUCT_DETAILS']['PRODUCT_BRAND_DETAILS'] = $connection->execute($sqlBrandQry)->fetchAll('assoc')[0];					
				$products['PRODUCT_DETAILS']['PRODUCT_BRAND_IMAGE_URL']=Router::url('/', true).IMG_PATH_BRANDS.$products['PRODUCT_DETAILS']['PRODUCT_BRAND_DETAILS']['image'];
			}else{
				$products['PRODUCT_DETAILS']['PRODUCT_BRAND_DETAILS'] =NULL;
			}
		
		/**/
		//-----------------

			return $products['PRODUCT_DETAILS'];
	}

	function getListAllProducts(){
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		$connection = ConnectionManager::get('default');

		$sql = "
			SELECT 
				Product.id as PRODUCT_ID,
				Product.item_code as PRODUCT_ITEMCODE,
				Product.title as PRODUCT_TITLE,
				Product.status as PRODUCT_STATUS,
				Product.product_desc as PRODUCT_DESC,
				Product.created as PRODUCT_CREATED,
				ProductsAttr.brand_id as PRODUCT_BRANDID,
				ProductsAttr.model as PRODUCT_MODEL,
				ProductsAttr.video_link as PRODUCT_VIDEOLINK,
				ProductsAttr.size as PRODUCT_SIZE,
				ProductsAttr.weight as PRODUCT_WEIGHT,
				ProductsAttr.packaging as PRODUCT_PACKAGING,
				ProductsAttr.uom as PRODUCT_UOM,
				ProductsAttr.quantity as PRODUCT_QUANTITY,
				ProductsAttr.main_promo_1 as PRODUCT_PROMO1,
				ProductsAttr.main_promo_2 as PRODUCT_PROMO2,
				ProductsAttr.main_promo_3 as PRODUCT_PROMO3,
				ProductsImages.*,
				ProductsMarketingImages.*,
				ProductsPrices.*,
				ProductsRelated.*,
				ProductsCategory.*
			FROM 
				products as Product
				LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
				LEFT JOIN products_images as ProductsImages ON (ProductsImages.product_id=Product.id)
				LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
				LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
				LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
				LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
		   GROUP BY Product.id;
		
		";
			
		$products['products'] = $connection->execute($sql)->fetchAll('assoc');
		
		
		foreach($products['products'] as $key=>$product){
			

			$products['products'][$key]['PRODUCT_PROMOTION'] = $this->getDiscountDisplayProduct($product['PRODUCT_ID'],1);
			
		
		}
		//print_r($products);
		//$products['IMAGE_PATH'] = Router::url('/',true).$this->imagePath;

		return $products['products'];
	}

	function remove_array_zero($arr){
		foreach($arr as $key=>$array_item){
            if($array_item==0){
				unset($arr[$key]);
	        }
		}

		//pre($arr);die('xxxx');

		return $arr;
	
	}

	function getAmountWithGST($amount){

		$DISCOUNTED_AMOUNT_PARENT_WITH_GST='';
		$DISCOUNTED_AMOUNT_PARENT_WITH_GST = $amount*(100+GST)/100;
		return $DISCOUNTED_AMOUNT_PARENT_WITH_GST;
	}

	function getProductInfoWithPromo($productID){

		$connection = ConnectionManager::get('default');
		//GET PROMO INFO
		$sqlPromotions = "
		SELECT 
		Promotion.formal_message_display as PROMOTION_MESSAGE,
		Promotion.discounted_amount_parent as PROMOTION_DISCOUNT_AS_PARENT,
		Promotion.discounted_amount_child as PROMOTION_DISCOUNT_AS_CHILD,
		Promotion.start_date as PROMOTION_START_DATE,
		Promotion.end_date as PROMOTION_END_DATE,
		Promotion.discount_parent_in as PROMOTION_DISCOUNT_PARENT_IN,
		Promotion.discount_child_in as PROMOTION_DISCOUNT_CHILD_IN,
		Promotion.discount_type as PROMOTION_DISCOUNT_TYPE
		FROM  
		promotions as Promotion
		LEFT JOIN products as Product ON (Product.id=Promotion.product_id)
		WHERE 
		Promotion.product_id=".$productID."
		OR
		Promotion.child_product_id=".$productID."
		AND
		Product.status='Y'
		";	

		$getPromotions = $connection->execute($sqlPromotions)->fetchAll('assoc');

		
		//--------------

		foreach($getPromotions as $key=>$promos){

			$getPromotionStartDate = strtotime($promos['PROMOTION_START_DATE']);
			$getPromotionEndDate = strtotime($promos['PROMOTION_END_DATE']);
			$current_date = strtotime(date("Y-m-d"));
			
			if($getPromotionStartDate <= $current_date AND $current_date <= $getPromotionEndDate) {
				
				switch($promos['PROMOTION_DISCOUNT_TYPE']){
				case 'D':
						
					break;
				case 'B':
					break;
				case 'P':
					break;
				case 'F':
					break;
				default:
					break;
				
				}

			}
		
		}
	
	}
}

?>