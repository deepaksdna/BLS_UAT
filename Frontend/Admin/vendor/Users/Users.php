<?php
namespace Users;

use Cake\Datasource\ConnectionManager;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Network\Session\DatabaseSession;

class Users
{
	public $controller = null;
    public $session = null;

	public $cacheKey;

    public function __construct()
    {
        $this->cacheKey = Configure::read('Session.handler.cache');
        //parent::__construct();
    }

	public function login_token($uid){
		$connection = ConnectionManager::get('default');
		$sqlQryU='SELECT * FROM `users` WHERE users.id='.$uid;			
		$getUsers = @$connection->execute($sqlQryU)->fetchAll('assoc')[0];	

		$user_token = md5($getUsers['email'].$getUsers['id'].$getUsers['status'].'abc@xyz');
		return $user_token;
	
	}

   

	
}

?>