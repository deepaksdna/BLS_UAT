<?php
namespace App\Controller\API;
use Cake\Datasource\ConnectionManager;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
/**
 * ConfigController Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class ConfigController extends AppController
{
	
	public function beforeFilter(Event $event)
    {      
		$this->Auth->allow(['allConfigDetails']);
    }

	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
		//echo '-->>';
    }

    public function allConfigDetails(){
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		$Configrations = TableRegistry::get('Configrations');
	    $configrations = $Configrations->find();
		$configration=$configrations->first();		
		echo json_encode($configration);
		die;
	}
	
	
}
