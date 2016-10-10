<?php
namespace App\Controller\API;
use Cake\Datasource\ConnectionManager;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
/**
 * Countries Controller
 *
 * @property \App\Model\Table\CountriesTable $Countries
 */
class CategoriesController extends AppController
{	
	public $imagePath = CAT_IMG_PATH_PRODUCTS;

	public function beforeFilter(Event $event)
    {
        $this->Auth->allow(['index','subcategories','allcategories','getAllSubCategories']);
	
    }
	
	public function index(){

		//$path = Router::url('/', true);

		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');  

		$connection = ConnectionManager::get('default');

		$sql = "SELECT *  FROM `categories`
		LEFT JOIN category_details ON categories.id = category_details.category_id
		WHERE `parent_id` = (SELECT id FROM `categories` WHERE categories.parent_id IS NULL)";

		$results = $connection->execute($sql)->fetchAll('assoc');
		$i=0;
		foreach($results as $key=>$records){
			$results[$key]['image_url_path'] = Router::url('/', true).$this->imagePath.$records['image'];
			$i=$i+1;
		}		
		echo json_encode($results);
		die;
	}

	public function allcategories(){

		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		$connection = ConnectionManager::get('default');
		$sql = "SELECT *  FROM `categories`
		LEFT JOIN category_details ON categories.id = category_details.category_id
		WHERE `parent_id` = (SELECT id FROM `categories` WHERE categories.parent_id IS NULL)";

		$results = $connection->execute($sql)->fetchAll('assoc');
		$i=0;
		foreach($results as $key=>$records){
			$results[$key]['image_url_path'] = Router::url('/', true).$this->imagePath.$records['image'];
			$i=$i+1;
		}		
		echo json_encode($results);
		die;
	}

	public function getAllSubCategories(){

		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');  

		$connection = ConnectionManager::get('default');

		$sql = "SELECT *,categories.id as cat_id  FROM `categories`
		LEFT JOIN category_details ON categories.id = category_details.category_id
		WHERE `parent_id` = (SELECT id FROM `categories` WHERE categories.parent_id IS NULL)";

		$results = $connection->execute($sql)->fetchAll('assoc');

		$i=0;
		foreach($results as $key=>$records){
			$results[$key]['image_url_path'] = Router::url('/', true).$this->imagePath.$records['image'];
			$catId = $records['cat_id'];			
			$sql2 = "SELECT 
			categories.parent_id,
			categories.lft,
			categories.rght,
			categories.name,
			category_details.id,
			category_details.category_id,
			category_details.image,
			IF(category_details.image ='', 'no-category-image',category_details.image) as image,
			category_details.image_dir,
			(SELECT name FROM `categories` WHERE id=".$catId.") as PARENT_CATEGORY,
			categories.id as CHILD_CATEGORY_ID
			FROM 
			`categories`
			LEFT JOIN category_details ON categories.id = category_details.category_id 
			WHERE `parent_id` =".$catId;
			$results2 = $connection->execute($sql2)->fetchAll('assoc');
			$arrSubCat = json_decode(json_encode($results2));
			$results[$key]['sub_cat'] = $arrSubCat;			
			
			$i=$i+1;
		}		
		//pre($results);die('xxx');
		echo json_encode($results);
		die;
	}

	

	public function subcategories($catId){
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		$connection = ConnectionManager::get('default');

		$sql = "SELECT *,(SELECT name FROM `categories` WHERE id=".$catId.") as PARENT_CATEGORY FROM `categories` LEFT JOIN category_details ON categories.id = category_details.category_id WHERE `parent_id` =".$catId;
		$results = $connection->execute($sql)->fetchAll('assoc');
		echo json_encode($results);
		die;
		
	}
}
?>
