<?php
namespace App\Controller\API;
use Cake\Datasource\ConnectionManager;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Promotions\Promotions;

// App::import('Controller', 'Users');
/**
 * Products Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 */
class ProductsController extends AppController
{
	
	public $imagePath = IMG_PATH_PRODUCTS;

	public function beforeFilter(Event $event)
    {      

		$this->Auth->allow(['getAllProducts','index','getBrandsByCategoryId','getProductsByBrand','getProductsByBrandWithCat','getProductDetails','getProductByCat','getProductByCat2','getColorsByProducts','getSearchProducts','getSearchPricerange','getProductDetails2','getColorImagesByProduct','testotherc','getProductInfo','getListKeywordProducts']);
    }
	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');		
    }

	public function getColorImagesByProduct($product_id,$color_id){
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		$connection = ConnectionManager::get('default');

		$sqlColor = "SELECT * FROM `products_images` WHERE color_id=".$color_id." AND product_id=".$product_id." LIMIT 0,4";
		//die($sqlColor);
		
		$products['products'] = $connection->execute($sqlColor)->fetchAll('assoc');
		$products['PRODUCTS_IMG_PATH'] = Router::url('/', true).PRODUCTS_IMG_PATH;

		echo json_encode($products);
		die;
	
	}

	
    /**
     * method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		$connection = ConnectionManager::get('default');

		$sqlSearch = "
			
		";
		
		$products['products'] = $connection->execute($sqlSearch)->fetchAll('assoc');

		echo json_encode($products);
		die;
    }

	/**
     * method
     *
     * @return \Cake\Network\Response|null
     */
    public function getSearchPricerange($brandId,$flag='l2h')
    {
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		$connection = ConnectionManager::get('default');

		switch($flag){
			case 'l2h':
				$strOrderBy ='ASC';
			break;
			case 'h2l':
				$strOrderBy ='DESC';
			break;
			default:
				$strOrderBy ='ASC';
			break;
		
		}
		
		$sqlSearch = "
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
				IF(ProductsImages.image ='', 'empty-product-img.png',ProductsImages.image) as image,
				ProductsMarketingImages.*,
				IF(ProductsMarketingImages.image ='', 'empty-product-img.png',ProductsMarketingImages.image) as image,
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
			WHERE 			
			ProductsAttr.brand_id=".$brandId."  GROUP By Product.id ORDER BY ProductsPrices.list_price ".$strOrderBy."
			";

			$products['products'] = $connection->execute($sqlSearch)->fetchAll('assoc');


		require_once(ROOT .DS. "Vendor" . DS  . "Promotions" . DS . "Promotions.php");		
		$Promotions = new Promotions;

		foreach($products['products'] as $key=>$product){

			$products['products'][$key]['PRODUCT_PROMOTION'] = $Promotions->getDiscountDisplayProduct($product['PRODUCT_ID'],1);
		
		}

			if(empty($products['products'])){
				$response['msg']='Not any product available for this brand.';
				$response['response_code']='0';			
				echo json_encode($response);
				die;
			}else{
				echo json_encode($products);
				die;
		   
			
			}
			
    }

	 /**
     * method
     *
     * @return \Cake\Network\Response|null
     */
    public function getSearchProducts($keyword)
    {
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		$connection = ConnectionManager::get('default');

		$sqlSearch = "
			SELECT 
				Product.id as PRODUCT_ID,
				Product.item_code as PRODUCT_ITEMCODE,
				Product.title as PRODUCT_TITLE,
				Product.status as PRODUCT_STATUS,
				Product.product_desc as PRODUCT_DESC,
				Product.status as PRODUCT_STATUS,
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
				IF(ProductsImages.image ='', 'empty-product-img.png',ProductsImages.image) as image,
				ProductsMarketingImages.*,
				IF(ProductsMarketingImages.image ='', 'empty-product-img.png',ProductsMarketingImages.image) as image,
				ProductsPrices.*,
				ProductsRelated.*,
				Category.name as CAT_NAME,
				Brand.name as BRAND_NAME,
				ProductsCategory.*
			FROM 
				products as Product
				LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
				LEFT JOIN products_images as ProductsImages ON (ProductsImages.product_id=Product.id)
				LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
				LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
				LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
				LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
				LEFT JOIN categories as Category ON (Category.id=ProductsCategory.category_id)
				LEFT JOIN brands as Brand ON (Brand.id=ProductsAttr.brand_id)
			WHERE 
			Category.name LIKE '%".$keyword."%'
			OR
			Product.title LIKE '%".$keyword."%'
			OR
			Brand.name LIKE '%".$keyword."%'
			OR
			Product.item_code LIKE '%".$keyword."%'


			GROUP BY Product.id
		
		";
		
		$products['products'] = $connection->execute($sqlSearch)->fetchAll('assoc');

		require_once(ROOT .DS. "Vendor" . DS  . "Promotions" . DS . "Promotions.php");		
		$Promotions = new Promotions;

		foreach($products['products'] as $key=>$product){

			$products['products'][$key]['PRODUCT_PROMOTION'] = $Promotions->getDiscountDisplayProduct($product['PRODUCT_ID'],1);
		
		}

		$products['PRODUCTS_IMG_PATH'] = Router::url('/',true).$this->imagePath;
		echo json_encode($products);
		die;
       
	}

	 /**
     * method
     *
     * @return \Cake\Network\Response|null
     */
    public function getAllProducts($mode=null)
    {
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
				IF(ProductsImages.image ='', 'empty-product-img.png',ProductsImages.image) as image,
				ProductsMarketingImages.*,
				IF(ProductsMarketingImages.image ='', 'empty-product-img.png',ProductsMarketingImages.image) as image,
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
		
		require_once(ROOT .DS. "Vendor" . DS  . "Promotions" . DS . "Promotions.php");		
		  $Promotions = new Promotions;

		foreach($products['products'] as $key=>$product){
			

			$products['products'][$key]['PRODUCT_PROMOTION'] = $Promotions->getDiscountDisplayProduct($product['PRODUCT_ID'],1);
			
		
		}
		//print_r($products);

		$products['IMAGE_PATH'] = Router::url('/',true).$this->imagePath;

		$this->set(array(
            'products' => $products,
            '_serialize' => array('products')
			));
        
    }

	 /**
     * method
     *
     * @return \Cake\Network\Response|null
     */
    public function getBrandsByCategoryId($catId)
    {
		
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		$connection = ConnectionManager::get('default');

		$sql = "
		select 
			brands.id as brand_id,
			brands.name as brand_name,
			products_categories.category_id,
			categories.name as category_name from brands
		left join products_attrs ON (products_attrs.brand_id=brands.id)
		left join products_categories ON (products_categories.product_id=products_attrs.product_id)
		left join categories ON (products_categories.category_id=categories.id)
		WHERE 
		products_categories.category_id=".$catId." GROUP BY brands.id";
		$results = $connection->execute($sql)->fetchAll('assoc');

        $this->set(array(
            'brands' => $results,
            '_serialize' => array('brands')
        ));
    }

	/**
     * method
     *
     * @return \Cake\Network\Response|null
     */
    public function getProductsByBrandWithCat($brandId,$catID)
    {
		
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		$connection = ConnectionManager::get('default');
		$brandId = (int)$brandId;
		$sql = "
			SELECT 
				Product.id as PRODUCT_ID,
				Product.item_code as PRODUCT_ITEMCODE,
				Product.title as PRODUCT_TITLE,
				Product.status as PRODUCT_STATUS,
				Product.product_desc as PRODUCT_DESC,
				Product.created as PRODUCT_CREATED,
				ProductsAttr.brand_id as PRODUCT_BRANDID,
				BRANDS.name as PRODUCT_BRANDNAME,
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
				IF(ProductsImages.image ='', 'empty-product-img.png',ProductsImages.image) as image,
				ProductsMarketingImages.*,
				IF(ProductsMarketingImages.image ='', 'empty-product-img.png',ProductsMarketingImages.image) as image,
				ProductsPrices.*,
				ProductsRelated.*,
				ProductsCategory.*

			from 
				products as Product
			LEFT JOIN promotions as Promotion ON (Promotion.product_id=Product.id)
			LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
			LEFT JOIN brands as BRANDS ON (BRANDS.id=ProductsAttr.brand_id)
			LEFT JOIN products_images as ProductsImages ON (ProductsImages.product_id=Product.id)
			LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
			LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
			LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
			LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
			";

			if($brandId < 0){

				$sql .= "WHERE ProductsCategory.category_id=".$catID." GROUP By Product.id
				";

			}else{
				$sql .= "WHERE ProductsAttr.brand_id=".$brandId." AND
				ProductsCategory.category_id=".$catID." GROUP By Product.id
				";
			}


			$sqlBrnds = "
			select brands.id as brand_id,brands.name as brand_name,brands.templates as BRAND_TEMPLATES,products_categories.category_id,categories.name as category_name from brands
			left join products_attrs ON (products_attrs.brand_id=brands.id)
			left join products_categories ON (products_categories.product_id=products_attrs.product_id)
			left join categories ON (products_categories.category_id=categories.id)
			WHERE 
			products_categories.category_id=".$catID." GROUP BY brands.id";
			$brands = $connection->execute($sqlBrnds)->fetchAll('assoc');

			$products['BRANDS_LIST_FOR_THIS_CAT'] = $brands;
			
		
		//die($sql);
		//$products['PRODUCT_IMG_URL'] = $this->;
		$products['products'] = $connection->execute($sql)->fetchAll('assoc');

		 require_once(ROOT .DS. "Vendor" . DS  . "Promotions" . DS . "Promotions.php");		
		  $Promotions = new Promotions;

		foreach($products['products'] as $key=>$product){
			$products['products'][$key]['PRODUCT_PROMOTION'] = $Promotions->getDiscountDisplayProduct($product['PRODUCT_ID'],1);

				if(is_array($products['products'][$key]['PRODUCT_PROMOTION']['APPLIED_PROMOTIONS']['LIST'])){

							

						$products['products'][$key]['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$products['products'][$key]['PRODUCT_PROMOTION']['APPLIED_PROMOTIONS']['LIST']['BEST_DISCOUNT_AMT_WITHOUT_GST'];

						$products['products'][$key]['FINAL_DISPLAY_PRICE_WITH_GST']=$products['products'][$key]['PRODUCT_PROMOTION']['APPLIED_PROMOTIONS']['LIST']['BEST_DISCOUNT_AMT_WITH_GST'];
				}else{
					
					$products['products'][$key]['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$product['list_price'];
					$products['products'][$key]['FINAL_DISPLAY_PRICE_WITH_GST']=@$products['products'][$key]['FINAL_DISPLAY_PRICE_WITHOUT_GST']*(100+GST)/100;
				}
		
				if($products['products'][$key]['FINAL_DISPLAY_PRICE_WITHOUT_GST']==null){
					$products['products'][$key]['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$product['list_price'];
				}
				if($products['products'][$key]['FINAL_DISPLAY_PRICE_WITH_GST']==null){
					$products['products'][$key]['FINAL_DISPLAY_PRICE_WITH_GST']=@$products['products'][$key]['FINAL_DISPLAY_PRICE_WITHOUT_GST']*(100+GST)/100;

				}

				$products['products'][$key]['PRODUCT_DISCOUNT_TYPE']=@$products['products'][$key]['PRODUCT_PROMOTION']['PROMOTIONS_DISCOUNT_TYPE'];
		}

		//$products['products']['FINAL_DISPLAY_PRICE']='';
		

		

		
		
		
		$sqlTemp = 'SELECT templates as TID FROM brands WHERE id='.$brandId;
		
		$products['TEMPLATE'] = $connection->execute($sqlTemp)->fetchAll('assoc');
		$products['IMAGE_PATH'] = Router::url('/',true).$this->imagePath;

		

		
		//pre($products['products']);
		//die('2222');
		if(empty($products['products'])){
			$response['msg']='Not any product available for this brand.';
			$response['response_code']='0';			
			echo json_encode($response);
			die;

		}else{
			echo json_encode($products);
			die;
       
		
		}
		
    }

	/**
     * method
     *
     * @return \Cake\Network\Response|null
     */
    public function getProductsByBrand($brandId)
    {
		
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
				BRANDS.name as PRODUCT_BRANDNAME,
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
				IF(ProductsImages.image ='', 'empty-product-img.png',ProductsImages.image) as image,
				ProductsMarketingImages.*,
				IF(ProductsMarketingImages.image ='', 'empty-product-img.png',ProductsMarketingImages.image) as image,
				ProductsPrices.*,
				ProductsRelated.*,
				ProductsCategory.*

			from 
				products as Product
			LEFT JOIN promotions as Promotion ON (Promotion.product_id=Product.id)
			LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
			LEFT JOIN brands as BRANDS ON (BRANDS.id=ProductsAttr.brand_id)
			LEFT JOIN products_images as ProductsImages ON (ProductsImages.product_id=Product.id)
			LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
			LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
			LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
			LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
			WHERE ProductsAttr.brand_id=".$brandId."  GROUP By Product.id
		";
		
		//die($sql);
		//$products['PRODUCT_IMG_URL'] = $this->;
		$products['products'] = $connection->execute($sql)->fetchAll('assoc');

		 require_once(ROOT .DS. "Vendor" . DS  . "Promotions" . DS . "Promotions.php");		
		  $Promotions = new Promotions;

		foreach($products['products'] as $key=>$product){
			$products['products'][$key]['PRODUCT_PROMOTION'] = $Promotions->getDiscountDisplayProduct($product['PRODUCT_ID'],1);

				if(is_array($products['products'][$key]['PRODUCT_PROMOTION']['APPLIED_PROMOTIONS']['LIST'])){

							

						$products['products'][$key]['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$products['products'][$key]['PRODUCT_PROMOTION']['APPLIED_PROMOTIONS']['LIST']['BEST_DISCOUNT_AMT_WITHOUT_GST'];

						$products['products'][$key]['FINAL_DISPLAY_PRICE_WITH_GST']=$products['products'][$key]['PRODUCT_PROMOTION']['APPLIED_PROMOTIONS']['LIST']['BEST_DISCOUNT_AMT_WITH_GST'];
				}else{
					
					$products['products'][$key]['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$product['list_price'];
					$products['products'][$key]['FINAL_DISPLAY_PRICE_WITH_GST']=@$products['products'][$key]['FINAL_DISPLAY_PRICE_WITHOUT_GST']*(100+GST)/100;
				}
		
				if($products['products'][$key]['FINAL_DISPLAY_PRICE_WITHOUT_GST']==null){
					$products['products'][$key]['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$product['list_price'];
				}
				if($products['products'][$key]['FINAL_DISPLAY_PRICE_WITH_GST']==null){
					$products['products'][$key]['FINAL_DISPLAY_PRICE_WITH_GST']=@$products['products'][$key]['FINAL_DISPLAY_PRICE_WITHOUT_GST']*(100+GST)/100;

				}

				$products['products'][$key]['PRODUCT_DISCOUNT_TYPE']=@$products['products'][$key]['PRODUCT_PROMOTION']['PROMOTIONS_DISCOUNT_TYPE'];
		}

		//$products['products']['FINAL_DISPLAY_PRICE']='';
		

		

		
		
		
		$sqlTemp = 'SELECT templates as TID FROM brands WHERE id='.$brandId;
		
		$products['TEMPLATE'] = $connection->execute($sqlTemp)->fetchAll('assoc');
		$products['IMAGE_PATH'] = Router::url('/',true).$this->imagePath;

		

		
		//pre($products['products']);
		//die('2222');
		if(empty($products['products'])){
			$response['msg']='Not any product available for this brand.';
			$response['response_code']='0';			
			echo json_encode($response);
			die;

		}else{
			echo json_encode($products);
			die;
       
		
		}
		
    }
	/**
     * method
     *
     * @return \Cake\Network\Response|null
     */
    public function getProductDetails2($productId)
    {
		//header('Content-Type: application/json');
		//header('Access-Control-Allow-Origin: *');

		die('OLD FUNCTION DEPRECIATED');
		/*

		$connection = ConnectionManager::get('default');
	
		$sql = "
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
			WHERE Product.id=".$productId." GROUP By Product.id
		";

		//CALCULATED PROMOCODE .......			
			require_once(ROOT .DS. "Vendor" . DS  . "Promotions" . DS . "Promotions.php");		
			$Promotions = new Promotions;
			$arrGetPromoData = $Promotions->getDiscountDisplayProduct($productId);
			$products['PRODUCT_PRMOTION']=$arrGetPromoData;
		//---------------------------
		
		$products['PRODUCT_DETAILS'] = $connection->execute($sql)->fetchAll('assoc');
		
		$categoryId = $products['PRODUCT_DETAILS'][0]['category_id'];
	
		$products['PRODUCTMARKETING_IMAGE_PATH'] = $this->imagePath;	

		//$products['PRODUCT_IMAGE_PATH'] = $this->imagePath;

		$products['PRODUCTS_IMG_PATH'] = PRODUCTS_IMG_PATH;

		$sqlImage = 'SELECT * FROM `products_images` WHERE product_id='.$productId." LIMIT 0,4";
		$products['PRODUCT_IMAGES'] = $connection->execute($sqlImage)->fetchAll('assoc');	
		
		$sqlRelated = 'SELECT * FROM `products_relateds` WHERE product_id='.$productId;

		//$products['RELATED_PRODUCTS'] = $connection->execute($sqlRelated)->fetchAll('assoc');
		$related_products = $connection->execute($sqlRelated)->fetchAll('assoc');	
		
		
		//RELATED_ONE_PRODUCT...
		
		if(empty(@$related_products[0]['related_product_1'])){  // IF RELATED PRODUCT NOT AVAILABLE FOR RELATED 1
				
				//GET PROMOTIONS PRODUCT...................................................................	
				
					$sqlQryPromotions = "
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
						ProductsMarketingImages.*,
						ProductsPrices.*,
						ProductsCategory.*,
						Promotion.*
					FROM
						products as Product
						LEFT JOIN promotions as Promotion ON (Promotion.product_id=Product.id)
						LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
						LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
						LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
						LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
						LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
					WHERE 
						Promotion.product_id IN (
						SELECT 
							promotions.product_id as PRODUCT_PROMOTION_ID
						FROM
							promotions
						WHERE 
							promotions.product_id IN (SELECT product_id FROM `products_categories` WHERE category_id=".$categoryId." AND products_categories.product_id NOT IN (".$productId.")) GROUP By promotions.product_id DESC
						) GROUP By Product.id LIMIT 0,1
					";// FIRST COLOMN PRODUCT


					@$products['RELATED_PRODUCTS'][0] = $connection->execute($sqlQryPromotions)->fetchAll('assoc')[0]; // GET PROMO PRODUCTS RELATED 1
					$products['PRODUCT_ORIGIN_FOR_RELATED_1'] = 'PROMO_PRODUCT';				
					
					if(empty($products['RELATED_PRODUCTS'][0])){  // IF PROMO PRODCUT NOT FOUND FOR RELATED 1, FIND IN SUB CAT
						
						// GET RANDOM SUB CATEGORY PRODUCT.
							$sqlQryCat = "
								
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
									ProductsMarketingImages.*,
									ProductsPrices.*,
									ProductsCategory.*,
									Promotion.*
								FROM
									products as Product
									LEFT JOIN promotions as Promotion ON (Promotion.product_id=Product.id)
									LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
									LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
									LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
									LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
									LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
								WHERE 
									Product.id IN (SELECT product_id FROM `products_categories` WHERE category_id=".$categoryId." AND product_id NOT IN (".$productId.")) GROUP By Product.id LIMIT 0,1
							
							";	
							
							
							@$products['RELATED_PRODUCTS'][0] = $connection->execute($sqlQryCat)->fetchAll('assoc')[0];
							$products['PRODUCT_ORIGIN_FOR_RELATED_1']  = 'SUB_CATEGORY';

							
							if(empty($products['RELATED_PRODUCTS'][0])){ // IF NOT ANY OF ONE PRODUCT AVAILABLE
									// IF STILL 
									$products['PRODUCT_ORIGIN_FOR_RELATED_1']  = 'NOT FOUND IN SUB_CATEGORY,PROMO AND RELATED';
									$products['RELATED_PRODUCT_1_FLAG']  = 0;
							}else{
									$products['RELATED_PRODUCT_1_FLAG']  = 1;
							}
									
					}
					
					//die('not p1');

				//-----------------------------------------------------------------------------------------
		}else{

				$sqlRel1 = "
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
						ProductsMarketingImages.*,
						ProductsPrices.*,
						ProductsCategory.*
					FROM
						products as Product
						LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
						LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
						LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
						LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
						LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
					WHERE 
						ProductsRelated.product_id=".@$related_products[0]['related_product_1']." GROUP By ProductsRelated.product_id
				";
				//echo $related_products_one[0]['related_product_1'];
				@$products['RELATED_PRODUCTS'][0] = @$connection->execute($sqlRel1)->fetchAll('assoc')[0];
				$products['PRODUCT_ORIGIN_FOR_RELATED_1']  = 'SYSTEM_RELATED_PRODUCTS';
				$products['RELATED_PRODUCT_1_FLAG']  = 1;
		
		}
		
	
		// START FOR RELATED 2 PRODUCT
		if(empty(@$related_products[0]['related_product_2'])){ // IF RELATED PRODUCT NOT AVAILABLE FOR RELATED 2

			$sqlQryPromotions = "
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
						ProductsMarketingImages.*,
						ProductsPrices.*,
						ProductsCategory.*,
						Promotion.*
					FROM
						products as Product
						LEFT JOIN promotions as Promotion ON (Promotion.product_id=Product.id)
						LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
						LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
						LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
						LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
						LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
					WHERE 
						Promotion.product_id IN (
						SELECT 
							promotions.product_id as PRODUCT_PROMOTION_ID
						FROM
							promotions
						WHERE 
							promotions.product_id IN (SELECT product_id FROM `products_categories` WHERE category_id=".$categoryId." AND products_categories.product_id NOT IN (".$productId.")) GROUP By promotions.product_id DESC
						) GROUP By Product.id LIMIT 1,1

					";// SECOND COLOMN PRODUCT


					@$products['RELATED_PRODUCTS'][1] = $connection->execute($sqlQryPromotions)->fetchAll('assoc')[0]; // GET PROMO PRODUCTS FOR RELATED 2.
					$products['PRODUCT_ORIGIN_FOR_RELATED_2'] = 'PROMO_PRODUCT';
					
					if(empty($products['RELATED_PRODUCTS'][1])){  // IF PROMO PRODCUT NOT FOUND FOR RELATED 2, FIND IN SUB CAT
						// GET RANDOM SUB CATEGORY PRODUCT.
							$sqlQryCat = "
								
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
									ProductsMarketingImages.*,
									ProductsPrices.*,
									ProductsCategory.*,
									Promotion.*
								FROM
									products as Product
									LEFT JOIN promotions as Promotion ON (Promotion.product_id=Product.id)
									LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
									LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
									LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
									LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
									LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
								WHERE 
									Product.id IN (SELECT product_id FROM `products_categories` WHERE category_id=".$categoryId." AND product_id NOT IN (".$productId.")) GROUP By Product.id LIMIT 1,1
							
							";	
							
							
							@$products['RELATED_PRODUCTS'][1] = $connection->execute($sqlQryCat)->fetchAll('assoc')[0];
							$products['PRODUCT_ORIGIN_FOR_RELATED_2']  = 'SUB_CATEGORY';

							
							if(empty($products['RELATED_PRODUCTS'][1])){ // IF NOT ANY OF ONE PRODUCT AVAILABLE
									// IF STILL 
									$products['PRODUCT_ORIGIN_FOR_RELATED_2']  = 'NOT FOUND IN SUB_CATEGORY,PROMO AND RELATED';
									$products['RELATED_PRODUCT_2_FLAG']  = 0;
							}else{
									$products['RELATED_PRODUCT_2_FLAG']  = 1;
							}

					}


		}else{
		
				//RELATED_TWO_PRODUCT

				$sqlRel2 = "
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
						ProductsMarketingImages.*,
						ProductsPrices.*,
						ProductsCategory.*
					FROM
						products as Product
						LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
						LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
						LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
						LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
						LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
					WHERE 
						ProductsRelated.product_id=".@$related_products[0]['related_product_2']." GROUP By ProductsRelated.product_id
				";

				//echo $related_products_one[0]['related_product_1'];
				@$products['RELATED_PRODUCTS'][1] = @$connection->execute($sqlRel2)->fetchAll('assoc')[0];
				$products['PRODUCT_ORIGIN_FOR_RELATED_2']  = 'SYSTEM_RELATED_PRODUCTS';
				$products['RELATED_PRODUCT_2_FLAG']  = 1;
		}
		
		// FINAL END FOR RELATED 2 PRODUCT
			
		if(empty(@$related_products[0]['related_product_3'])){  // IF RELATED PRODUCT NOT AVAILABLE FOR RELATED 3
			//GET PROMOTIONS PRODUCT..........

				$sqlQryPromotions = "
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
						ProductsMarketingImages.*,
						ProductsPrices.*,
						ProductsCategory.*,
						Promotion.*
					FROM
						products as Product
						LEFT JOIN promotions as Promotion ON (Promotion.product_id=Product.id)
						LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
						LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
						LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
						LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
						LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
					WHERE 
						Promotion.product_id IN (
						SELECT 
							promotions.product_id as PRODUCT_PROMOTION_ID
						FROM
							promotions
						WHERE 
							promotions.product_id IN (SELECT product_id FROM `products_categories` WHERE category_id=".$categoryId." AND products_categories.product_id NOT IN (".$productId.")) GROUP By promotions.product_id DESC
						) GROUP By Product.id LIMIT 2,1

					";// THIRD COLOMN PRODUCT


					@$products['RELATED_PRODUCTS'][2] = $connection->execute($sqlQryPromotions)->fetchAll('assoc')[0]; // GET PROMO PRODUCTS FOR RELATED 3.
					@$products['PRODUCT_ORIGIN_FOR_RELATED_3'] = 'PROMO_PRODUCT';

					if(empty($products['RELATED_PRODUCTS'][2])){  // IF PROMO PRODCUT NOT FOUND FOR RELATED 3, FIND IN SUB CAT
						// GET RANDOM SUB CATEGORY PRODUCT.

							$sqlQryCat = "								
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
									ProductsMarketingImages.*,
									ProductsPrices.*,
									ProductsCategory.*,
									Promotion.*
								FROM
									products as Product
									LEFT JOIN promotions as Promotion ON (Promotion.product_id=Product.id)
									LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
									LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
									LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
									LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
									LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
								WHERE 
									Product.id IN (SELECT product_id FROM `products_categories` WHERE category_id=".$categoryId." AND product_id NOT IN (".$productId.")) GROUP By Product.id LIMIT 2,1
							
							";	
							
							
							@$products['RELATED_PRODUCTS'][2] = $connection->execute($sqlQryCat)->fetchAll('assoc')[0];
							$products['PRODUCT_ORIGIN_FOR_RELATED_3']  = 'SUB_CATEGORY';

							
							if(empty($products['RELATED_PRODUCTS'][2])){ // IF NOT ANY OF ONE PRODUCT AVAILABLE
									// IF STILL 
									$products['PRODUCT_ORIGIN_FOR_RELATED_3']  = 'NOT FOUND IN SUB_CATEGORY,PROMO AND RELATED';
									$products['RELATED_PRODUCT_3_FLAG']  = 0;
							}else{
									$products['RELATED_PRODUCT_3_FLAG']  = 1;
							}

					}

			//--------------------------------
		}else{
		
		//RELATED_THREE_PRODUCT

		$sqlRel3 = "
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
				ProductsMarketingImages.*,
				ProductsPrices.*,
				ProductsCategory.*
			FROM
				products as Product
				LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
				LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
				LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
				LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
				LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
			WHERE 
				ProductsRelated.product_id=".@$related_products[0]['related_product_3']." GROUP By ProductsRelated.product_id
		";

			//echo $related_products_one[0]['related_product_1'];
			$products['RELATED_PRODUCTS'][2] = $connection->execute($sqlRel3)->fetchAll('assoc')[0];
			$products['PRODUCT_ORIGIN_FOR_RELATED_3']  = 'SYSTEM_RELATED_PRODUCTS';
			$products['RELATED_PRODUCT_3_FLAG']  = 1;
		}

		

		
	
		
		//$products['products'];
		//$brandId = $products['PRODUCT_DETAILS'][0]['PRODUCT_BRANDID'];

		//$sqlRelated = 'SELECT * FROM `brands` WHERE id='.$brandId;
		$sqlColor = "
		SELECT 
				Colors.id as COLOR_ID,
				Colors.name as COLOR_NAME,
				Colors.image as COLOR_IMAGE,
				Colors.image_dir as COLOR_IMG_DIR,
				ProductsImages.image as PROD_IMG,
				ProductsImages.image_dir as PROD_IMG_DIR
			FROM
				colors as Colors
				LEFT JOIN products_images as ProductsImages ON (ProductsImages.color_id=Colors.id)				
			WHERE 
				ProductsImages.product_id=".$productId." GROUP By Colors.id";

		$resultsColors = $connection->execute($sqlColor)->fetchAll('assoc');

		

		foreach($resultsColors as $key=>$colorlist){
			$sqlColorImages = "SELECT * FROM products_images WHERE color_id=".$colorlist['COLOR_ID']." AND product_id=".$productId." LIMIT 0,4";
			$resultsColorImages = $connection->execute($sqlColorImages)->fetchAll('assoc');
			$resultsColors[$key]['DISPLAY_IMAGES'] = $connection->execute($sqlColorImages)->fetchAll('assoc');
			// Add path for color image.
			$resultsColors[$key]['COLOR_IMAGES_URL']=COLOR_IMG_PATH_PRODUCTS;
			$resultsColors[$key]['COLOR_DROPDOWN_IMAGE_PATH']=COLOR_DROPDOWN_IMAGE_PATH;
			//--------------------------
		}

		$products['COLORS'] = $resultsColors;	

		//pre($products);die;
        echo json_encode($products);
		die();
		*/
	}



	public function getListKeywordProducts($keywords=''){

		$connection = ConnectionManager::get('default');
		
		if(!empty($keywords)){
		//GET PRODUCT NAMES

			$like_tag = "'".$keywords."%'";

			echo $sql = "SELECT title as PRODUCT_TITLE FROM `products` WHERE title LIKE ".$like_tag.";";
			$products = $connection->execute($sql)->fetchAll('assoc');

		}else{
			$sql = "SELECT title as PRODUCT_TITLE FROM `products`";
			$products = $connection->execute($sql)->fetchAll('assoc');		
		}

		 echo json_encode($products);
		 die;
	
	}







	//##########################################
	/**
	 * #########################################
     * method : getProductDetails
     *
     * @return \Cake\Network\Response|null
     */
    public function getProductDetails($productId)
    {
		
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
				IF(ProductsMarketingImages.image ='', 'empty-product-img.png',ProductsMarketingImages.image) as image,
				ProductsPrices.*,
				ProductsCategory.*
			FROM
				products as Product
				LEFT JOIN products_images as ProductsImage ON (ProductsImage.product_id=Product.id)
				LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
				LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
				LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
				LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
			WHERE Product.id=".$productId." GROUP By Product.id
		";

		//CALCULATED PROMOCODE .......			
			require_once(ROOT .DS. "Vendor" . DS  . "Promotions" . DS . "Promotions.php");		
			$Promotions = new Promotions;
			$arrGetPromoData = $Promotions->getDiscountDisplayProduct($productId,1);
			$products['PRODUCT_PROMOTION']=$arrGetPromoData;
		//---------------------------------------





		
		$products['PRODUCT_DETAILS'] = $connection->execute($sql)->fetchAll('assoc');

		

		$mainProductId = $products['PRODUCT_DETAILS'][0]['PRODUCT_ID'];

		//GET OUT VAR INFO START

		@$products['PRODUCT_DETAILS']['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$Promotions->getOutVarInfo($mainProductId)['FINAL_DISPLAY_PRICE_WITHOUT_GST'];

		@$products['PRODUCT_DETAILS']['FINAL_DISPLAY_PRICE_WITH_GST']=$Promotions->getOutVarInfo($mainProductId)['FINAL_DISPLAY_PRICE_WITH_GST'];

		@$products['PRODUCT_DETAILS']['PROMOTIONS_DISCOUNT_TYPE']=$Promotions->getOutVarInfo($mainProductId)['PRODUCT_DISCOUNT_TYPE'];

		//-------------END--------------

		//GET BRAND DETAILS
		


		if(!empty($products['PRODUCT_DETAILS'])){
		$sqlBrandQry = "
		SELECT 
		name as BRAND_NAME,
		IF(image ='', 'empty-product-img.png',image) as image,
		templates as TEMPLATES		
		FROM `brands` WHERE id=".$products['PRODUCT_DETAILS'][0]['PRODUCT_BRANDID'];

		$products['PRODUCT_BRAND_DETAILS'] = $connection->execute($sqlBrandQry)->fetchAll('assoc')[0];		
		$products['PRODUCT_BRAND_IMAGE_URL']=Router::url('/', true).IMG_PATH_BRANDS.$products['PRODUCT_BRAND_DETAILS']['image'];

		//-----------------------------------
		}	
		

		//End Brand Details.

		if(empty($products['PRODUCT_DETAILS'])){
			 $products['PRODUCT_DETAILS']=null;
			 $products['msg']='Product is not available.';
			 echo json_encode($products);
			 die();
		}
		
		$categoryId = $products['PRODUCT_DETAILS'][0]['category_id'];
	
		$products['PRODUCTMARKETING_IMAGE_PATH'] = Router::url('/', true).$this->imagePath;	

		//$products['PRODUCT_IMAGE_PATH'] = $this->imagePath;

		$products['PRODUCTS_IMG_PATH'] = Router::url('/', true).PRODUCTS_IMG_PATH;

		$sqlImage = 'SELECT *,IF(image = "","empty-product-img.png",image) as image FROM `products_images` WHERE product_id='.$productId." LIMIT 0,4";
		$products['PRODUCT_IMAGES'] = $connection->execute($sqlImage)->fetchAll('assoc');	

		//PRODUCT MARKETING IMAGES 
		$sqlImage = 'SELECT IF(image = "","empty-product-img.png",image) as image FROM `products_marketing_images` WHERE product_id='.$productId." LIMIT 0,4";

		$products['PRODUCT_MARKETING_IMAGES'] = $connection->execute($sqlImage)->fetchAll('assoc');	
		
		//------------------------
		
		$sqlRelated = 'SELECT * FROM `products_relateds` WHERE product_id='.$productId;

		//$products['RELATED_PRODUCTS'] = $connection->execute($sqlRelated)->fetchAll('assoc');
		$related_products = $connection->execute($sqlRelated)->fetchAll('assoc');	

		
		//################################### RELATED PRODUCT START ###############################################	
		
		//GET ALL RELATED PRODUCT PROMOTION.

		$sqlQryPromotions = "
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
						ProductsMarketingImages.*,
						IF(ProductsMarketingImages.image ='', 'empty-product-img.png',ProductsMarketingImages.image) as image,
						ProductsPrices.*,
						ProductsCategory.*,
						Promotion.*
					FROM
						products as Product
						LEFT JOIN promotions as Promotion ON (Promotion.product_id=Product.id)
						LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
						LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
						LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
						LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
						LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
					WHERE 
						Promotion.product_id IN (
						SELECT 
							promotions.product_id as PRODUCT_PROMOTION_ID
						FROM
							promotions
						WHERE 
							promotions.product_id IN (SELECT product_id FROM `products_categories` WHERE category_id=".$categoryId." AND products_categories.product_id NOT IN (".$productId.")) GROUP By promotions.product_id DESC
						) GROUP By Product.id
					";// FIRST COLOMN PRODUCT


					@$getAllRelatedPromotionsProducts = $connection->execute($sqlQryPromotions)->fetchAll('assoc'); // GET PROMO PRODUCTS RELATED 1
						



			//GET ALL RELATED PRODUCT SUB_CAT....
		
			// GET RANDOM SUB CATEGORY PRODUCT.
							$sqlQryCat = "
								
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
									ProductsMarketingImages.*,
									IF(ProductsMarketingImages.image ='', 'empty-product-img.png',ProductsMarketingImages.image) as image,
									ProductsPrices.*,
									ProductsCategory.*,
									Promotion.*
								FROM
									products as Product
									LEFT JOIN promotions as Promotion ON (Promotion.product_id=Product.id)
									LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
									LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
									LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
									LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
									LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
								WHERE 
									Product.id IN (SELECT product_id FROM `products_categories` WHERE category_id=".$categoryId." AND product_id NOT IN (".$productId.")) GROUP By Product.id
							
							";	
							
							
							@$getAllRelatedSubcatProducts = $connection->execute($sqlQryCat)->fetchAll('assoc');
							
							//ARRAy MERGE ALL.
							$getAllRelatedProducts = array_merge($getAllRelatedSubcatProducts,$getAllRelatedPromotionsProducts);

						//------------ GET PRODUCT POOL ----------------------------------------------------------------
								require_once(ROOT .DS. "Vendor" . DS  . "Promotions" . DS . "Promotions.php");		
								$Promotions = new Promotions;
								$arrListAllProducts = $Promotions->getListAllProducts();
						//-------------   ------------------------------------------------------------------------------
						
						
							$getAllRelatedProducts = array_merge($getAllRelatedProducts,$arrListAllProducts);
							//REMOVE DUPLICATE ARRAY ELEMENT...

							
							
							foreach($getAllRelatedProducts as $key=>$val){

								if($key > 0){
									if(@$getAllRelatedProducts[$key]['PRODUCT_ID']!=@$getAllRelatedProducts[$key+1]['PRODUCT_ID']){
										$arrAllRelatedProducts[$key]=$val;
									}
										
								}
							
							}
							
							
		//============================================================================================================================					
		
		//RELATED_ONE_PRODUCT...
		
		if(empty(@$related_products[0]['related_product_1'])){  // IF RELATED PRODUCT NOT AVAILABLE FOR RELATED 1			
				
				//GET PROMOTIONS PRODUCT...................................................................	
				
					$sqlQryPromotions = "
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
						ProductsMarketingImages.*,
						IF(ProductsMarketingImages.image ='', 'empty-product-img.png',ProductsMarketingImages.image) as image,
						ProductsPrices.*,
						ProductsCategory.*,
						Promotion.*
					FROM
						products as Product
						LEFT JOIN promotions as Promotion ON (Promotion.product_id=Product.id)
						LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
						LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
						LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
						LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
						LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
					WHERE 
						Promotion.product_id IN (
						SELECT 
							promotions.product_id as PRODUCT_PROMOTION_ID
						FROM
							promotions
						WHERE 
							promotions.product_id IN (SELECT product_id FROM `products_categories` WHERE category_id=".$categoryId." AND products_categories.product_id NOT IN (".$productId.")) GROUP By promotions.product_id DESC
						) GROUP By Product.id LIMIT 0,1
					";// FIRST COLOMN PRODUCT


					@$products['RELATED_PRODUCTS'][0] = $connection->execute($sqlQryPromotions)->fetchAll('assoc')[0]; // GET PROMO PRODUCTS RELATED 1

				
					if(!empty($products['RELATED_PRODUCTS'][0])){ 

						//CALCULATED PROMOCODE .......			
						$arrGetPromoData = $Promotions->getDiscountDisplayProduct(@$products['RELATED_PRODUCTS'][0]['PRODUCT_ID'],1);
						@$products['RELATED_PRODUCTS'][0]['PRODUCT_PROMOTION']=$arrGetPromoData;
						//---------------------------

						//GET OUT VAR INFO START

						@$products['RELATED_PRODUCTS'][0]['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][0]['PRODUCT_ID'])['FINAL_DISPLAY_PRICE_WITHOUT_GST'];



						@$products['RELATED_PRODUCTS'][0]['FINAL_DISPLAY_PRICE_WITH_GST']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][0]['PRODUCT_ID'])['FINAL_DISPLAY_PRICE_WITH_GST'];


						@$products['RELATED_PRODUCTS'][0]['PROMOTIONS_DISCOUNT_TYPE']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][0]['PRODUCT_ID'])['PRODUCT_DISCOUNT_TYPE'];

						//-------------END--------------


					}

					$products['PRODUCT_ORIGIN_FOR_RELATED_1'] = 'PROMO_PRODUCT';				
					
					if(empty($products['RELATED_PRODUCTS'][0])){  // IF PROMO PRODCUT NOT FOUND FOR RELATED 1, FIND IN SUB CAT
						
						// GET RANDOM SUB CATEGORY PRODUCT.
							$sqlQryCat = "
								
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
									ProductsMarketingImages.*,
									IF(ProductsMarketingImages.image ='', 'empty-product-img.png',ProductsMarketingImages.image) as image,
									ProductsPrices.*,
									ProductsCategory.*,
									Promotion.*
								FROM
									products as Product
									LEFT JOIN promotions as Promotion ON (Promotion.product_id=Product.id)
									LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
									LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
									LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
									LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
									LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
								WHERE 
									Product.id IN (SELECT product_id FROM `products_categories` WHERE category_id=".$categoryId." AND product_id NOT IN (".$productId.")) GROUP By Product.id LIMIT 0,1
							
							";	
							
							
							@$products['RELATED_PRODUCTS'][0] = $connection->execute($sqlQryCat)->fetchAll('assoc')[0];

							
							if(!empty($products['RELATED_PRODUCTS'][0])){ 
								//CALCULATED PROMOCODE .......			
								$arrGetPromoData = $Promotions->getDiscountDisplayProduct(@$products['RELATED_PRODUCTS'][0]['PRODUCT_ID'],1);
								@$products['RELATED_PRODUCTS'][0]['PRODUCT_PROMOTION']=$arrGetPromoData;
								//---------------------------

								//GET OUT VAR INFO START

								@$products['RELATED_PRODUCTS'][0]['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][0]['PRODUCT_ID'])['FINAL_DISPLAY_PRICE_WITHOUT_GST'];



								@$products['RELATED_PRODUCTS'][0]['FINAL_DISPLAY_PRICE_WITH_GST']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][0]['PRODUCT_ID'])['FINAL_DISPLAY_PRICE_WITH_GST'];


								@$products['RELATED_PRODUCTS'][0]['PROMOTIONS_DISCOUNT_TYPE']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][0]['PRODUCT_ID'])['PRODUCT_DISCOUNT_TYPE'];

								//-------------END--------------
							}

							$products['PRODUCT_ORIGIN_FOR_RELATED_1']  = 'SUB_CATEGORY';

							
							if(empty($products['RELATED_PRODUCTS'][0])){ // IF NOT ANY OF ONE PRODUCT AVAILABLE
									// IF STILL 
									$products['PRODUCT_ORIGIN_FOR_RELATED_1']  = 'NOT FOUND IN SUB_CATEGORY,PROMO AND RELATED';
									$products['RELATED_PRODUCT_1_FLAG']  = 0;
									$random_key = rand(0,count($arrAllRelatedProducts));
									@$products['RELATED_PRODUCTS'][0] = $arrAllRelatedProducts[$random_key];
							}else{
									$products['RELATED_PRODUCT_1_FLAG']  = 1;
							}
									
					}
					
					//die('not p1');

				//-----------------------------------------------------------------------------------------
		}else{

				$sqlRel1 = "
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
						ProductsCategory.*
					FROM
						products as Product
						LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
						LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
						LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
						LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
						LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
					WHERE 
						Product.id=".@$related_products[0]['related_product_1']." GROUP By Product.id
				";
				//echo $related_products_one[0]['related_product_1'];
				@$products['RELATED_PRODUCTS'][0] = @$connection->execute($sqlRel1)->fetchAll('assoc')[0];

				
					if(!empty($products['RELATED_PRODUCTS'][0])){ 
						//CALCULATED PROMOCODE .......			
						$arrGetPromoData = $Promotions->getDiscountDisplayProduct(@$products['RELATED_PRODUCTS'][0]['PRODUCT_ID'],1);
						@$products['RELATED_PRODUCTS'][0]['PRODUCT_PROMOTION']=$arrGetPromoData;

						//GET OUT VAR INFO START
						
						@$products['RELATED_PRODUCTS'][0]['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][0]['PRODUCT_ID'])['FINAL_DISPLAY_PRICE_WITHOUT_GST'];

					
					
						@$products['RELATED_PRODUCTS'][0]['FINAL_DISPLAY_PRICE_WITH_GST']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][0]['PRODUCT_ID'])['FINAL_DISPLAY_PRICE_WITH_GST'];

							
						@$products['RELATED_PRODUCTS'][0]['PROMOTIONS_DISCOUNT_TYPE']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][0]['PRODUCT_ID'])['PRODUCT_DISCOUNT_TYPE'];

						//-------------END--------------

						
					}
				$products['PRODUCT_ORIGIN_FOR_RELATED_1']  = 'SYSTEM_RELATED_PRODUCTS';
				$products['RELATED_PRODUCT_1_FLAG']  = 1;
		
		}
		
	
		// START FOR RELATED 2 PRODUCT
		if(empty(@$related_products[0]['related_product_2'])){ // IF RELATED PRODUCT NOT AVAILABLE FOR RELATED 2

			$sqlQryPromotions = "
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
						ProductsMarketingImages.*,
						IF(ProductsMarketingImages.image ='', 'empty-product-img.png',ProductsMarketingImages.image) as image,
						ProductsPrices.*,
						ProductsCategory.*,
						Promotion.*
					FROM
						products as Product
						LEFT JOIN promotions as Promotion ON (Promotion.product_id=Product.id)
						LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
						LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
						LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
						LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
						LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
					WHERE 
						Promotion.product_id IN (
						SELECT 
							promotions.product_id as PRODUCT_PROMOTION_ID
						FROM
							promotions
						WHERE 
							promotions.product_id IN (SELECT product_id FROM `products_categories` WHERE category_id=".$categoryId." AND products_categories.product_id NOT IN (".$productId.")) GROUP By promotions.product_id DESC
						) GROUP By Product.id LIMIT 1,1

					";// SECOND COLOMN PRODUCT


					@$products['RELATED_PRODUCTS'][1] = $connection->execute($sqlQryPromotions)->fetchAll('assoc')[0]; // GET PROMO PRODUCTS FOR RELATED 2.
						
					if(!empty($products['RELATED_PRODUCTS'][1])){ 

						//CALCULATED PROMOCODE .......			
						$arrGetPromoData = $Promotions->getDiscountDisplayProduct(@$products['RELATED_PRODUCTS'][1]['PRODUCT_ID'],1);
						@$products['RELATED_PRODUCTS'][1]['PRODUCT_PROMOTION']=$arrGetPromoData;
						//---------------------------

						//GET OUT VAR INFO START

@$products['RELATED_PRODUCTS'][1]['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][1]['PRODUCT_ID'])['FINAL_DISPLAY_PRICE_WITHOUT_GST'];



@$products['RELATED_PRODUCTS'][1]['FINAL_DISPLAY_PRICE_WITH_GST']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][1]['PRODUCT_ID'])['FINAL_DISPLAY_PRICE_WITH_GST'];


@$products['RELATED_PRODUCTS'][1]['PROMOTIONS_DISCOUNT_TYPE']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][1]['PRODUCT_ID'])['PRODUCT_DISCOUNT_TYPE'];

//-------------END--------------
					}

					$products['PRODUCT_ORIGIN_FOR_RELATED_2'] = 'PROMO_PRODUCT';
					
					if(empty($products['RELATED_PRODUCTS'][1])){  // IF PROMO PRODCUT NOT FOUND FOR RELATED 2, FIND IN SUB CAT
						// GET RANDOM SUB CATEGORY PRODUCT.
							$sqlQryCat = "
								
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
									ProductsMarketingImages.*,
									IF(ProductsMarketingImages.image ='', 'empty-product-img.png',ProductsMarketingImages.image) as image,
									ProductsPrices.*,
									ProductsCategory.*,
									Promotion.*
								FROM
									products as Product
									LEFT JOIN promotions as Promotion ON (Promotion.product_id=Product.id)
									LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
									LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
									LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
									LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
									LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
								WHERE 
									Product.id IN (SELECT product_id FROM `products_categories` WHERE category_id=".$categoryId." AND product_id NOT IN (".$productId.")) GROUP By Product.id LIMIT 1,1
							
							";	
							

							
							@$products['RELATED_PRODUCTS'][1] = $connection->execute($sqlQryCat)->fetchAll('assoc')[0];

							if(!empty($products['RELATED_PRODUCTS'][1])){ 

								//CALCULATED PROMOCODE .........			
								$arrGetPromoData = $Promotions->getDiscountDisplayProduct(@$products['RELATED_PRODUCTS'][1]['PRODUCT_ID'],1);
								@$products['RELATED_PRODUCTS'][1]['PRODUCT_PROMOTION']=$arrGetPromoData;
								//------------------------------
								//GET OUT VAR INFO START

@$products['RELATED_PRODUCTS'][1]['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][1]['PRODUCT_ID'])['FINAL_DISPLAY_PRICE_WITHOUT_GST'];



@$products['RELATED_PRODUCTS'][1]['FINAL_DISPLAY_PRICE_WITH_GST']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][1]['PRODUCT_ID'])['FINAL_DISPLAY_PRICE_WITH_GST'];


@$products['RELATED_PRODUCTS'][1]['PROMOTIONS_DISCOUNT_TYPE']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][1]['PRODUCT_ID'])['PRODUCT_DISCOUNT_TYPE'];

//-------------END--------------
							}

							$products['PRODUCT_ORIGIN_FOR_RELATED_2']  = 'SUB_CATEGORY';

							
							if(empty($products['RELATED_PRODUCTS'][1])){ // IF NOT ANY OF ONE PRODUCT AVAILABLE
									// IF STILL 
									$products['PRODUCT_ORIGIN_FOR_RELATED_2']  = 'NOT FOUND IN SUB_CATEGORY,PROMO AND RELATED';
									$products['RELATED_PRODUCT_2_FLAG']  = 0;
									$random_key = rand(0,count($arrAllRelatedProducts));
									@$products['RELATED_PRODUCTS'][1] = $arrAllRelatedProducts[$random_key];
							}else{
									$products['RELATED_PRODUCT_2_FLAG']  = 1;
							}

					}


		}else{
		
				//RELATED_TWO_PRODUCT

				$sqlRel2 = "
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
						ProductsCategory.*
					FROM
						products as Product
						LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
						LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
						LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
						LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
						LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
					WHERE 
						Product.id=".@$related_products[0]['related_product_2']." GROUP By Product.id
				";

				//echo $related_products_one[0]['related_product_1'];

					

				@$products['RELATED_PRODUCTS'][1] = @$connection->execute($sqlRel2)->fetchAll('assoc')[0];

				if(!empty($products['RELATED_PRODUCTS'][1])){ 

						//CALCULATED PROMOCODE .......			
							$arrGetPromoData = $Promotions->getDiscountDisplayProduct(@$products['RELATED_PRODUCTS'][1]['PRODUCT_ID'],1);
							@$products['RELATED_PRODUCTS'][1]['PRODUCT_PROMOTION']=$arrGetPromoData;
						//---------------------------
						//GET OUT VAR INFO START
						
						@$products['RELATED_PRODUCTS'][1]['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][1]['PRODUCT_ID'])['FINAL_DISPLAY_PRICE_WITHOUT_GST'];

					
					
						@$products['RELATED_PRODUCTS'][1]['FINAL_DISPLAY_PRICE_WITH_GST']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][1]['PRODUCT_ID'])['FINAL_DISPLAY_PRICE_WITH_GST'];

							
						@$products['RELATED_PRODUCTS'][1]['PROMOTIONS_DISCOUNT_TYPE']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][1]['PRODUCT_ID'])['PRODUCT_DISCOUNT_TYPE'];

						//-------------END--------------

				}
				$products['PRODUCT_ORIGIN_FOR_RELATED_2']  = 'SYSTEM_RELATED_PRODUCTS';
				$products['RELATED_PRODUCT_2_FLAG']  = 1;
		}
		
		// FINAL END FOR RELATED 2 PRODUCT
			
		if(empty(@$related_products[0]['related_product_3'])){  // IF RELATED PRODUCT NOT AVAILABLE FOR RELATED 3
			//GET PROMOTIONS PRODUCT..........................................

		
				$sqlQryPromotions = "
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
						ProductsMarketingImages.*,
						IF(ProductsMarketingImages.image ='', 'empty-product-img.png',ProductsMarketingImages.image) as image,
						ProductsPrices.*,
						ProductsCategory.*,
						Promotion.*
					FROM
						products as Product
						LEFT JOIN promotions as Promotion ON (Promotion.product_id=Product.id)
						LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
						LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
						LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
						LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
						LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
					WHERE 
						Promotion.product_id IN (
						SELECT 
							promotions.product_id as PRODUCT_PROMOTION_ID
						FROM
							promotions
						WHERE 
							promotions.product_id IN (SELECT product_id FROM `products_categories` WHERE category_id=".$categoryId." AND products_categories.product_id NOT IN (".$productId.")) GROUP By promotions.product_id DESC
						) GROUP By Product.id LIMIT 2,1

					";// THIRD COLOMN PRODUCT



						
					@$products['RELATED_PRODUCTS'][2] = $connection->execute($sqlQryPromotions)->fetchAll('assoc')[0]; // GET PROMO PRODUCTS FOR RELATED 3.

					if(!empty($products['RELATED_PRODUCTS'][2])){ 

						//CALCULATED PROMOCODE .......			
						$arrGetPromoData = $Promotions->getDiscountDisplayProduct(@$products['RELATED_PRODUCTS'][2]['PRODUCT_ID'],1);
						@$products['RELATED_PRODUCTS'][2]['PRODUCT_PROMOTION']=$arrGetPromoData;
						//---------------------------
						//GET OUT VAR INFO START

						@$products['RELATED_PRODUCTS'][2]['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][2]['PRODUCT_ID'])['FINAL_DISPLAY_PRICE_WITHOUT_GST'];



						@$products['RELATED_PRODUCTS'][2]['FINAL_DISPLAY_PRICE_WITH_GST']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][2]['PRODUCT_ID'])['FINAL_DISPLAY_PRICE_WITH_GST'];


						@$products['RELATED_PRODUCTS'][2]['PROMOTIONS_DISCOUNT_TYPE']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][2]['PRODUCT_ID'])['PRODUCT_DISCOUNT_TYPE'];

						//-------------END--------------
					}

					@$products['PRODUCT_ORIGIN_FOR_RELATED_3'] = 'PROMO_PRODUCT';

					if(empty($products['RELATED_PRODUCTS'][2])){  // IF PROMO PRODCUT NOT FOUND FOR RELATED 3, FIND IN SUB CAT
						// GET RANDOM SUB CATEGORY PRODUCT.

							$sqlQryCat = "								
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
									ProductsMarketingImages.*,
									IF(ProductsMarketingImages.image ='', 'empty-product-img.png',ProductsMarketingImages.image) as image,
									ProductsPrices.*,
									ProductsCategory.*,
									Promotion.*
								FROM
									products as Product
									LEFT JOIN promotions as Promotion ON (Promotion.product_id=Product.id)
									LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
									LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
									LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
									LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
									LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
								WHERE 
									Product.id IN (SELECT product_id FROM `products_categories` WHERE category_id=".$categoryId." AND product_id NOT IN (".$productId.")) GROUP By Product.id LIMIT 2,1
							
							";	
							
							
							@$products['RELATED_PRODUCTS'][2] = $connection->execute($sqlQryCat)->fetchAll('assoc')[0];


							if(!empty($products['RELATED_PRODUCTS'][2])){ 

								//CALCULATED PROMOCODE .......			
								$arrGetPromoData = $Promotions->getDiscountDisplayProduct(@$products['RELATED_PRODUCTS'][2]['PRODUCT_ID'],1);
								@$products['RELATED_PRODUCTS'][2]['PRODUCT_PROMOTION']=$arrGetPromoData;
								//---------------------------
								//GET OUT VAR INFO START

@$products['RELATED_PRODUCTS'][2]['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][2]['PRODUCT_ID'])['FINAL_DISPLAY_PRICE_WITHOUT_GST'];



@$products['RELATED_PRODUCTS'][2]['FINAL_DISPLAY_PRICE_WITH_GST']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][2]['PRODUCT_ID'])['FINAL_DISPLAY_PRICE_WITH_GST'];


@$products['RELATED_PRODUCTS'][2]['PROMOTIONS_DISCOUNT_TYPE']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][2]['PRODUCT_ID'])['PRODUCT_DISCOUNT_TYPE'];

//-------------END--------------
							}

							$products['PRODUCT_ORIGIN_FOR_RELATED_3']  = 'SUB_CATEGORY';

							
							if(empty($products['RELATED_PRODUCTS'][2])){ // IF NOT ANY OF ONE PRODUCT AVAILABLE
									// IF STILL 
									$products['PRODUCT_ORIGIN_FOR_RELATED_3']  = 'NOT FOUND IN SUB_CATEGORY,PROMO AND RELATED';
									$products['RELATED_PRODUCT_3_FLAG']  = 0;

									//PICKUP FROM POOL..
									$random_key = rand(0,count($arrAllRelatedProducts));
									@$products['RELATED_PRODUCTS'][2] = $arrAllRelatedProducts[$random_key];
									//------------------
							}else{
									$products['RELATED_PRODUCT_3_FLAG']  = 1;
							}

					}

			//--------------------------------
		}else{
		
		//RELATED_THREE_PRODUCT

		$sqlRel3 = "
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
				ProductsCategory.*
			FROM
				products as Product
				LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
				LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
				LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
				LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
				LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
			WHERE 
				Product.id=".@$related_products[0]['related_product_3']." GROUP By Product.id
		";

			//echo $related_products_one[0]['related_product_1'];
			$products['RELATED_PRODUCTS'][2] = $connection->execute($sqlRel3)->fetchAll('assoc')[0];

			if(!empty($products['RELATED_PRODUCTS'][2])){ 

						//CALCULATED PROMOCODE .......			
							$arrGetPromoData = $Promotions->getDiscountDisplayProduct(@$products['RELATED_PRODUCTS'][2]['PRODUCT_ID'],1);
							@$products['RELATED_PRODUCTS'][2]['PRODUCT_PROMOTION']=$arrGetPromoData;
						//---------------------------
						//GET OUT VAR INFO START
						
						@$products['RELATED_PRODUCTS'][2]['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][2]['PRODUCT_ID'])['FINAL_DISPLAY_PRICE_WITHOUT_GST'];

					
					
						@$products['RELATED_PRODUCTS'][2]['FINAL_DISPLAY_PRICE_WITH_GST']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][2]['PRODUCT_ID'])['FINAL_DISPLAY_PRICE_WITH_GST'];

							
						@$products['RELATED_PRODUCTS'][2]['PROMOTIONS_DISCOUNT_TYPE']=$Promotions->getOutVarInfo(@$products['RELATED_PRODUCTS'][2]['PRODUCT_ID'])['PRODUCT_DISCOUNT_TYPE'];

						//-------------END--------------
				}
			$products['PRODUCT_ORIGIN_FOR_RELATED_3']  = 'SYSTEM_RELATED_PRODUCTS';
			$products['RELATED_PRODUCT_3_FLAG']  = 1;
		}

		

	//##################### END RELATED PRODUCTS ##########################################################################################	
	
		
		
		//$brandId = $products['PRODUCT_DETAILS'][0]['PRODUCT_BRANDID'];

		//$sqlRelated = 'SELECT * FROM `brands` WHERE id='.$brandId;
		$sqlColor = "
		SELECT 
				Colors.id as COLOR_ID,
				Colors.name as COLOR_NAME,
				Colors.image as COLOR_IMAGE,
				Colors.image_dir as COLOR_IMG_DIR,
				ProductsImages.image as PROD_IMG,
				ProductsImages.image_dir as PROD_IMG_DIR
			FROM
				colors as Colors
				LEFT JOIN products_images as ProductsImages ON (ProductsImages.color_id=Colors.id)				
			WHERE 
				ProductsImages.product_id=".$productId." GROUP By Colors.id";

		$resultsColors = $connection->execute($sqlColor)->fetchAll('assoc');

		

		foreach($resultsColors as $key=>$colorlist){
			$sqlColorImages = "SELECT * FROM products_images WHERE color_id=".$colorlist['COLOR_ID']." AND product_id=".$productId." LIMIT 0,4";
			$resultsColorImages = $connection->execute($sqlColorImages)->fetchAll('assoc');
			$resultsColors[$key]['DISPLAY_IMAGES'] = $connection->execute($sqlColorImages)->fetchAll('assoc');
			// Add path for color image.
			$resultsColors[$key]['COLOR_IMAGES_URL']=Router::url('/', true).COLOR_IMG_PATH_PRODUCTS;
			$resultsColors[$key]['COLOR_DROPDOWN_IMAGE_PATH']=Router::url('/', true).COLOR_DROPDOWN_IMAGE_PATH;
			//--------------------------
		}

		$products['COLORS'] = $resultsColors;
		
		//pre($products);die;
        echo json_encode($products);
		die();
	
	}

	/**
     * method
     *
     * @return \Cake\Network\Response|null
     */
    public function getColorsByProducts($productId)
    {
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		$connection = ConnectionManager::get('default');

		$sql = "
		SELECT 
				Colors.id as COLOR_ID,
				Colors.name as COLOR_NAME,
				Colors.image as COLOR_IMAGE,
				Colors.image_dir as COLOR_IMG_DIR,
				ProductsImages.image as PROD_IMG,
				ProductsImages.image_dir as PROD_IMG_DIR
			FROM
				colors as Colors
				LEFT JOIN products_images as ProductsImages ON (ProductsImages.color_id=Colors.id)				
			WHERE 
				ProductsImages.product_id=".$productId." GROUP By Colors.id";

		$results = $connection->execute($sql)->fetchAll('assoc');

		$results['COLOR_PRODUCTS_IMG_PATH'] = Router::url('/', true).PRODUCTS_IMG_PATH;
		$results['COLOR_DROPDOWN_IMAGE_PATH'] = Router::url('/', true).COLOR_DROPDOWN_IMAGE_PATH;

		if(empty($results)){
			$response['msg']='Color display is unavailable.';
			$response['response_code']='0';	
			
			echo json_encode($response);
			die;
		}

		
        $this->set(array(
            'colors' => $results,
            '_serialize' => array('colors')
        ));
	}

	/**
     * method
     *
     * @return \Cake\Network\Response|null
     */
    public function getProductByCat($catId)
    {
		
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
				BRANDS.name as PRODUCT_BRANDNAME,
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
				ProductsMarketingImages.image,
				IF(ProductsMarketingImages.image ='', 'empty-product-img.png',ProductsMarketingImages.image) as image,
				ProductsPrices.*,
				ProductsRelated.*,
				ProductsCategory.*
			FROM
				products as Product
				LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
				LEFT JOIN brands as BRANDS ON (BRANDS.id=ProductsAttr.brand_id)
				LEFT JOIN products_images as ProductsImages ON (ProductsImages.product_id=Product.id)
				LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
				LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
				LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
				LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
				WHERE ProductsCategory.category_id=".$catId." GROUP By Product.id
		";
	
		 $products['products'] = $connection->execute($sql)->fetchAll('assoc');

		require_once(ROOT .DS. "Vendor" . DS  . "Promotions" . DS . "Promotions.php");		
		$Promotions = new Promotions;

		foreach($products['products'] as $key=>$product){

			$products['products'][$key]['PRODUCT_PROMOTION'] = $Promotions->getDiscountDisplayProduct($product['PRODUCT_ID'],1);
			
			
				//-----------------------------------
				if(is_array($products['products'][$key]['PRODUCT_PROMOTION']['APPLIED_PROMOTIONS']['LIST'])){

							

						$products['products'][$key]['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$products['products'][$key]['PRODUCT_PROMOTION']['APPLIED_PROMOTIONS']['LIST']['BEST_DISCOUNT_AMT_WITHOUT_GST'];

						$products['products'][$key]['FINAL_DISPLAY_PRICE_WITH_GST']=$products['products'][$key]['PRODUCT_PROMOTION']['APPLIED_PROMOTIONS']['LIST']['BEST_DISCOUNT_AMT_WITH_GST'];
				}else{
					
					$products['products'][$key]['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$product['list_price'];
					$products['products'][$key]['FINAL_DISPLAY_PRICE_WITH_GST']=@$products['products'][$key]['FINAL_DISPLAY_PRICE_WITHOUT_GST']*(100+GST)/100;
				}
		
				if($products['products'][$key]['FINAL_DISPLAY_PRICE_WITHOUT_GST']==null){
					$products['products'][$key]['FINAL_DISPLAY_PRICE_WITHOUT_GST']=$product['list_price'];
				}
				if($products['products'][$key]['FINAL_DISPLAY_PRICE_WITH_GST']==null){
					$products['products'][$key]['FINAL_DISPLAY_PRICE_WITH_GST']=@$products['products'][$key]['FINAL_DISPLAY_PRICE_WITHOUT_GST']*(100+GST)/100;

				}

				$products['products'][$key]['PRODUCT_DISCOUNT_TYPE']=@$products['products'][$key]['PRODUCT_PROMOTION']['PROMOTIONS_DISCOUNT_TYPE'];
			//-----------------------------------
		
		}
		 $allBrands=array();
		
		if(empty($products)){
			$response['msg']='Not any product available for this category.';
			$response['response_code']='0';			
			 $this->set(array(
            'response' => $response,
            '_serialize' => array('response')
			));

		}else{

		
			$sqlBrnds = "
			select brands.id as brand_id,brands.name as brand_name,brands.templates as BRAND_TEMPLATES,products_categories.category_id,categories.name as category_name from brands
			left join products_attrs ON (products_attrs.brand_id=brands.id)
			left join products_categories ON (products_categories.product_id=products_attrs.product_id)
			left join categories ON (products_categories.category_id=categories.id)
			WHERE 
			products_categories.category_id=".$catId." GROUP BY brands.id";
			$brands = $connection->execute($sqlBrnds)->fetchAll('assoc');



			$sqlCatImg = "
			SELECT 
			IF(image ='', 'NoCategoryImage.png',image) as image
			FROM
			`category_details`
			WHERE 
			category_id=".$catId;
			$cat_img = @$connection->execute($sqlCatImg)->fetchAll('assoc')[0]['image'];
			
		
			//$products['BRANDS_LIST_FOR_THIS_CAT'] = super_unique($allBrands);
			$products['BRANDS_LIST_FOR_THIS_CAT'] = $brands;
			
			$products['IMAGE_PATH'] = Router::url('/', true).IMG_PATH_PRODUCTS;
			$products['CATEGORY_IMG_PATH'] = Router::url('/', true).CAT_IMG_PATH_PRODUCTS.'banner_'.$cat_img;
			
			echo json_encode($products);
			die;			
		 
		}
		
		
	
	}
    
	/**
     * method
     *
     * @return \Cake\Network\Response|null
     */
    public function getProductByCat2($catId)
    {
		/*
		//pre($this->imagePath);die;
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');

		$connection = ConnectionManager::get('default');

		$sql = "
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
				WHERE ProductsCategory.category_id=".$catId." GROUP By Product.id
		";
	
		$products = $connection->execute($sql)->fetchAll('assoc');
		
		if(empty($products)){
			$response['msg']='Not any product available for this category.';
			$response['response_code']='0';			
			 $this->set(array(
            'response' => $response,
            '_serialize' => array('response')
			));

		}else{
			$i=0;
			foreach($products as $key=>$prods){
				$sqlBrands = 'SELECT * FROM `brands` WHERE id='.$prods['PRODUCT_BRANDID'];
				$allBrands[$i] = $connection->execute($sqlBrands)->fetchAll('assoc');
				//$products[$i]['IMAGE_PATH']=$this->imagePath;
				$i=$i+1;
			}

		
			
			$this->set(array(
			'products' => $products,
			'allBrands' => $allBrands,
			'image_path' => $this->imagePath,
			'_serialize' => array('products','allBrands','image_path')
			));
		
		}
		
		*/
	
	}

	public function testotherc(){
		  require_once(ROOT .DS. "Vendor" . DS  . "Promotions" . DS . "Promotions.php");		
		  $Promotions = new Promotions;
		  $arrGetPromoData = $Promotions->getDiscountDisplayProduct(80);
		  pre($arrGetPromoData);
		
		//echo $obj2->getProperty();
		exit;
	   

	}

	public function getProductInfo($productId){
	
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
				ProductsPrices.*,
				ProductsCategory.*
			FROM
				products as Product
				LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
				LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
				LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
				LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
			WHERE Product.id=".$productId." GROUP By Product.id
		";

		//CALCULATED PROMOCODE .......			
			//require_once(ROOT .DS. "Vendor" . DS  . "Promotions" . DS . "Promotions.php");		
			//$Promotions = new Promotions;
			//$arrGetPromoData = $Promotions->getDiscountDisplayProduct($productId);
			//$products['PRODUCT_PRMOTION']=$arrGetPromoData;
		//---------------------------
		
		$products['PRODUCT_DETAILS'] = $connection->execute($sql)->fetchAll('assoc');
		
		$categoryId = $products['PRODUCT_DETAILS'][0]['category_id'];
	
		$products['PRODUCTMARKETING_IMAGE_PATH'] = $this->imagePath;	

		//$products['PRODUCT_IMAGE_PATH'] = $this->imagePath;

		$products['PRODUCTS_IMG_PATH'] = Router::url('/', true).PRODUCTS_IMG_PATH;

		$sqlImage = "SELECT *,IF(image ='', 'empty-product-img.png',image) as image FROM `products_images` WHERE product_id=".$productId." LIMIT 0,4";
		$products['PRODUCT_IMAGES'] = $connection->execute($sqlImage)->fetchAll('assoc');	

		//PRODUCT MARKETING IMAGES 
		$sqlImage = "SELECT image as PRODUCT_MARKETING_IMAGE_NAME FROM `products_marketing_images` WHERE product_id=".$productId." LIMIT 0,4";

		$products['PRODUCT_MARKETING_IMAGES'] = $connection->execute($sqlImage)->fetchAll('assoc');	
		
		//CHECK PROMOTIONS ..

		$sqlImage ="SELECT start_date, FROM `promotions` ORDER BY `id` ASC ";

		//-----------------------------------------------------------------------------

		
	
	
	
	}
}
