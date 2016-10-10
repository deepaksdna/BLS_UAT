<?php
namespace App\Controller\API;
use Cake\Datasource\ConnectionManager;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class SlidersController extends AppController
{
	
	public function beforeFilter(Event $event)
    {      
		$this->Auth->allow(['sliderimages']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function sliderimages()
    {
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');  
        //$SliderImages = TableRegistry::get('SliderImages');
		//$SliderImages->find('all');
		//echo json_encode($SliderImages);
		//$data = json_decode(json_encode($SliderImages));
		//$password = md5($pass);
		$connection = ConnectionManager::get('default');
		$sql = "SELECT * FROM `slider_images`";
		$results = $connection->execute($sql)->fetchAll('assoc');
		//$results['IMAGE_PATH']=$this->imagePath."/img/files/SliderImages/image/";
		$image_path=Router::url('/', true)."/img/files/SliderImages/image/";
		
		$this->set(array(
            'results' => $results,
			'image_path' => $image_path,
            '_serialize' => array('results','image_path')
        ));
		//die;
	
    }

    
    
	
	
}
