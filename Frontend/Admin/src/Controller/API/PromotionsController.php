<?php
namespace App\Controller\API;
use Cake\Datasource\ConnectionManager;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Promotions\Promotions;
use Cake\Routing\Router;
use Images\Images;
/**
 * Products Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 */
class PromotionsController extends AppController
{
	public $cat_imagePath = CAT_IMG_PATH_PRODUCTS;

	public function beforeFilter(Event $event)
    {      

		$this->Auth->allow(['listallPromoOption','imagetest','getPromoPageValuePlus','getPromoPageSoloPlus','getPromoPagePwp','getDiscountDisplayProduct','carttestfunc1']);
    }

	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
		
    }

	
    
    public function getPromoPageValuePlus()
    {
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		
		$connection = ConnectionManager::get('default');
		$Configrations = TableRegistry::get('Configrations');

		$arrConf = $Configrations->find('all');

		$results = $arrConf->toArray();
		
		//GET VALUE ON PROMO PAGE1
		$promo_page_1=$results[0]->promo_page_1;
		//------------------------
		
		$sqlValuePlus = "SELECT categories.id as PID,categories.name as PARENT_NAME,category_details.*
		FROM categories 
		LEFT JOIN category_details ON categories.id = category_details.category_id
		WHERE categories.id IN (SELECT categories.parent_id as PID FROM categories WHERE categories.id IN (SELECT DISTINCT products_categories.category_id FROM `products_categories` WHERE products_categories.product_id IN (SELECT products_attrs.product_id FROM products_attrs 
		WHERE 
		products_attrs.main_promo_1='".$promo_page_1."'
		OR
		products_attrs.main_promo_2='".$promo_page_1."'
		OR
		products_attrs.main_promo_3='".$promo_page_1."'
		
		)))";
		
		$categories['parent_cat'] = $connection->execute($sqlValuePlus)->fetchAll('assoc');
	
		$i=0;
		foreach($categories['parent_cat'] as $key=>$records){
			$categories['parent_cat'][$key]['parent_cat_image_url_path'] = Router::url('/', true).$this->cat_imagePath.$records['image'];
			$catId = $records['PID'];			
			$sql2 = "SELECT categories.*,(SELECT name FROM `categories` WHERE id=".$catId.") as PARENT_CATEGORY,categories.id as CHILD_CATEGORY_ID FROM `categories` LEFT JOIN category_details ON categories.id = category_details.category_id 
			LEFT JOIN products_categories ON (products_categories.category_id=categories.id)
			WHERE
			products_categories.product_id IN (SELECT products_attrs.product_id FROM products_attrs
			WHERE
			products_attrs.main_promo_1='".$promo_page_1."'
			OR 
			products_attrs.main_promo_2='".$promo_page_1."'
			OR
			products_attrs.main_promo_3='".$promo_page_1."'
			
			)
			AND
			`parent_id` =".$catId." GROUP BY CHILD_CATEGORY_ID";
			
			$results2 = $connection->execute($sql2)->fetchAll('assoc');
			$arrSubCat = json_decode(json_encode($results2));
			$categories['parent_cat'][$key]['sub_cat'] = $arrSubCat;			
			
			$i=$i+1;
		}	
		
		echo json_encode($categories);
		die;
		
    }

	public function getPromoPageSoloPlus()
    {
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		
		$connection = ConnectionManager::get('default');
		$Configrations = TableRegistry::get('Configrations');

		$arrConf = $Configrations->find('all');

		$results = $arrConf->toArray();
	
		//GET VALUE ON PROMO PAGE1
		$promo_page=$results[0]->promo_page_2;
		
		//------------------------
		
		$sqlValuePlus = "SELECT categories.id as PID,categories.name as PARENT_NAME,category_details.*
		FROM categories 
		LEFT JOIN category_details ON categories.id = category_details.category_id
		WHERE categories.id IN (SELECT categories.parent_id as PID FROM categories WHERE categories.id IN (SELECT DISTINCT products_categories.category_id FROM `products_categories` WHERE products_categories.product_id IN (SELECT products_attrs.product_id FROM products_attrs
		WHERE
		products_attrs.main_promo_2='".$promo_page."'
		OR
		products_attrs.main_promo_1='".$promo_page."'
		OR
		products_attrs.main_promo_3='".$promo_page."'
		
		)))";
		
		
		$categories['parent_cat'] = $connection->execute($sqlValuePlus)->fetchAll('assoc');

		$i=0;
		foreach($categories['parent_cat'] as $key=>$records){
			$categories['parent_cat'][$key]['parent_cat_image_url_path'] = Router::url('/', true).$this->cat_imagePath.$records['image'];
			$catId = $records['PID'];			
			$sql2 = "SELECT categories.*,(SELECT name FROM `categories` WHERE id=".$catId.") as PARENT_CATEGORY,categories.id as CHILD_CATEGORY_ID FROM `categories` LEFT JOIN category_details ON categories.id = category_details.category_id 
			LEFT JOIN products_categories ON (products_categories.category_id=categories.id)
			WHERE
			products_categories.product_id IN (SELECT products_attrs.product_id FROM products_attrs WHERE 
			products_attrs.main_promo_2='".$promo_page."'
			OR
			products_attrs.main_promo_1='".$promo_page."'
			OR
			products_attrs.main_promo_3='".$promo_page."'			
			)
			AND
			`parent_id` =".$catId." GROUP BY CHILD_CATEGORY_ID";
			
			$results2 = $connection->execute($sql2)->fetchAll('assoc');
			$arrSubCat = json_decode(json_encode($results2));
			$categories['parent_cat'][$key]['sub_cat'] = $arrSubCat;
			$i=$i+1;
		}


		echo json_encode($categories);
		die;
		
    }

	public function getPromoPagePwp()
    {
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		
		$connection = ConnectionManager::get('default');
		$Configrations = TableRegistry::get('Configrations');

		$arrConf = $Configrations->find('all');

		$results = $arrConf->toArray();
	
		//GET VALUE ON PROMO PAGE1
		$promo_page=$results[0]->promo_page_3;
		
		//-------------------------------------
		
		$sqlValuePlus = "SELECT categories.id as PID,categories.name as PARENT_NAME,category_details.*
		FROM categories 
		LEFT JOIN category_details ON categories.id = category_details.category_id
		WHERE categories.id IN (SELECT categories.parent_id as PID FROM categories WHERE categories.id IN (SELECT DISTINCT products_categories.category_id FROM `products_categories` WHERE products_categories.product_id IN (SELECT products_attrs.product_id FROM products_attrs
		
		WHERE 
		products_attrs.main_promo_3='".$promo_page."'
		OR
		products_attrs.main_promo_2='".$promo_page."'
		OR
		products_attrs.main_promo_1='".$promo_page."'
		)))";
		
		
		$categories['parent_cat'] = $connection->execute($sqlValuePlus)->fetchAll('assoc');

		$i=0;
		foreach($categories['parent_cat'] as $key=>$records){
			$categories['parent_cat'][$key]['parent_cat_image_url_path'] = Router::url('/', true).$this->cat_imagePath.$records['image'];
			$catId = $records['PID'];			
			$sql2 = "SELECT categories.*,
			(
			SELECT name FROM `categories` WHERE id=".$catId.") as PARENT_CATEGORY,categories.id as CHILD_CATEGORY_ID FROM `categories` LEFT JOIN category_details ON categories.id = category_details.category_id 
			LEFT JOIN products_categories ON (products_categories.category_id=categories.id)
			WHERE
			products_categories.product_id IN (SELECT products_attrs.product_id FROM products_attrs 
			WHERE 
			products_attrs.main_promo_3='".$promo_page."'
			OR
			products_attrs.main_promo_2='".$promo_page."'
			OR
			products_attrs.main_promo_1='".$promo_page."'
			)
			AND
			`parent_id` =".$catId." GROUP BY CHILD_CATEGORY_ID";
			
			$results2 = $connection->execute($sql2)->fetchAll('assoc');
			$arrSubCat = json_decode(json_encode($results2));
			$categories['parent_cat'][$key]['sub_cat'] = $arrSubCat;
			$i=$i+1;
		}
		
		echo json_encode($categories);
		die;
		
    }

	public function getDiscountDisplayProduct($productID){
		
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');		
		$connection = ConnectionManager::get('default');

		$sqlPromotions = "SELECT * FROM `promotions` WHERE product_id=".$productID." GROUP BY promo_code";		
		$getPromotions = $connection->execute($sqlPromotions)->fetchAll('assoc');

		
		//pre($getPromotions);
		$promotionProducts = array();

		foreach($getPromotions as $key=>$promos){

			//GET PRODUCT DETAILS..
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
			WHERE Product.id=".$productID." GROUP By Product.id
			";
			$products['PRODUCT_DETAILS'] = $connection->execute($sqlProduct)->fetchAll('assoc')[0];		

			$product_price = $products['PRODUCT_DETAILS']['list_price'];

			//$promos['start_date']='2016-08-04';
			//$promos['end_date']='2016-08-31';

			$getPromotionStartDate = strtotime($promos['start_date']);
			$getPromotionEndDate = strtotime($promos['end_date']);
			$current_date = strtotime(date("Y-m-d"));

			if($getPromotionStartDate < $current_date AND $current_date < $getPromotionEndDate) {
				$active_product = 1;
				//MESSAGE GOES TO DISPLAY ABOUT PROMOTION
				$promotionProducts['PROMOTIONS_STATUS']='ACTIVE';
				$promotionProducts['PROMOTIONS_STATUS_CODE']=1;
				$promotionProducts['APPLIED_PROMOTIONS'][$key]['MESSAGE'] = $promos['formal_message_display'];
				$promotionProducts['APPLIED_PROMOTIONS'][$key]['DISCOUNT_TYPE']=$promos['discount_type'];

			}else{ 
				$active_product = 0;
			}

			
		

			//CALCULATE DISCOUNT 
			if($active_product==1){
				

				//---------------------------------------
				//die('ACTIVE');
			}else{
				//die('DE-ACTIVE');
			}
			//-------------------
		
			
		}//get promo code dipre($arrMsg);

			$this->set('promotionProducts', $promotionProducts);
			//return $promotionProducts;
			die;
	}

	public function listallPromoOption(){
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		$Configrations = TableRegistry::get('Configrations');
		$connection = ConnectionManager::get('default');
		$sqlPromos = "SELECT promo_page_1,promo_page_2,promo_page_3 FROM configrations";

		$promo['PROMOTION_OPTION_DROPDOWN'] = $connection->execute($sqlPromos)->fetchAll('assoc')[0];
		
		$arrPrm = array();

		$k=0;
		foreach($promo['PROMOTION_OPTION_DROPDOWN'] as $key=>$promoname){
			$arrPrm[$k]['name']=$promoname;
			$arrPrm[$k]['id']=$k;
			$k=$k+1;
		}
		
		//pre($promo['PROMOTION_OPTION_DROPDOWN']);
		echo json_encode($arrPrm);
		die;
	}

	public function imagetest(){

		  require_once(ROOT .DS. "Vendor" . DS  . "Images" . DS . "Images.php");		
		  $Images = new Images;

		  $ImagesChk = $Images->smart_resize_image("http://192.168.0.82/bls2/img/files/SliderImages/image/slider1.jpg",null,100,50,false,'C:\xampp\htdocs\bls2\webroot\img\files\SliderImages\image\slider1.jpg',false,false,100);	  	
		
			die($ImagesChk);
	}


	
	public function productPromotions(){
		
	}
	public function carttestfunc1(){
	
		  require_once(ROOT .DS. "Vendor" . DS  . "Promotions" . DS . "Promotions.php");		
		  $Promotions = new Promotions;

		   $allCartProdcuts=array(
			  'CART'=>array(
				'Product'=>array(
								'0'=>array(
									'PRODUCT_ID'=>86,
									'PRODUCT_QUANTITY'=>'4'
									),
								'1'=>array(
									'PRODUCT_ID'=>84,
									'PRODUCT_QUANTITY'=>'15'
									),
								'2'=>array(
									'PRODUCT_ID'=>85,
									'PRODUCT_QUANTITY'=>'50'
									),
								'3'=>array(
									'PRODUCT_ID'=>90,
									'PRODUCT_QUANTITY'=>'25'
									)
							)
			  )
		  );
		
		  $allCartProdcuts=array(
			  'CART'=>array(
				'Product'=>array(
								
								'0'=>array(
									'PRODUCT_ID'=>81,
									'PRODUCT_QUANTITY'=>'5'
									)
							)
			  )
		  );

		 
		 // $allCartProdcuts=array();
		  $arrGetPromoData = $Promotions->getDiscountDisplayProduct(87,1,$allCartProdcuts);	
		 // $arrGetPromoData = $Promotions->getProductInfoWithPromo(80);
		  
		  

		  
		  pre($arrGetPromoData);die;
		 // echo json_encode($arrGetPromoData);
		 die;
	   
	}



	
    
}
