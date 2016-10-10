<?php
namespace App\Controller\API;
use Cake\Datasource\ConnectionManager;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Routing\Router;
use Cake\Mailer\Email;
/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class LoginsController extends AppController
{
	public function beforeFilter(Event $event)
    {      
		$this->Auth->allow(['login','forgot','allcategories','getuseraccess','getuseraccess2','logintest']);
    }
	

	public function login(){
		ob_start();
		
		header('Accept: application/json');
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');  
	
        
		if(!empty(@$this->request->query['username'])){
			$login = $this->request->query['username'];
		}else{

			if(!empty($_REQUEST['username'])){
				$login = $_REQUEST['username'];
			}else{
				$login='';
			}
			
		}
					
		if(!empty(@$this->request->query['password'])){
			$password = md5(trim(@$this->request->query['password']));
		}else{

			if(!empty($_REQUEST['password'])){
				$password =  md5(trim(@$_REQUEST['password']));
			}else{
				$password='';
			}
			
		}
			//echo json_encode($this->request->data['password']);die;
		
		$connection = ConnectionManager::get('default');

		$sql = "SELECT id,email,status,created,modified  FROM `users`
		WHERE `email` = '".trim($login)."' AND `password`='".trim($password)."'";
		$results = $connection->execute($sql)->fetchAll('assoc');
		

		$mssage = array();
			
		
		if(@$results[0]['status']=='1'){
			
			$this->Auth->setUser($results);			
			//SET SESSION...
			$session = $this->request->session();
			$session->write('Current_User',$results); 
			$getUsers = $this->request->session()->read('Current_User')[0];
			//pre($getUsers);
			//--------------
			$mssage['token'] = md5($getUsers['email'].$getUsers['id'].$getUsers['status'].USER_SALT);
			$mssage['msg'] = 'Login Successfull';
			$mssage['response_code'] = '1';
			$mssage['getUsers'] = $getUsers;
			

		}else{
			//echo json_encode($this->request->data);die;			
			$mssage['msg'] = 'Invalid username or password, try again.';
			$mssage['response_code'] = '0';
		}
		
		echo json_encode($mssage);		
		die;
	}

	public function logintest(){
		ob_start();
		
		header('Accept: application/json');
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');  
	
        
		if(!empty(@$this->request->query['username'])){
			$login = $this->request->query['username'];
		}else{

			if(!empty($_REQUEST['username'])){
				$login = $_REQUEST['username'];
			}else{
				$login='';
			}
			
		}
					
		if(!empty(@$this->request->query['password'])){
			$password = md5(trim(@$this->request->query['password']));
		}else{

			if(!empty($_REQUEST['password'])){
				$password =  md5(trim(@$_REQUEST['password']));
			}else{
				$password='';
			}
			
		}
			//echo json_encode($this->request->data['password']);die;
		
		$connection = ConnectionManager::get('default');

		$sql = "SELECT id,email,status,created,modified  FROM `users`
		WHERE `email` = '".trim($login)."' AND `password`='".trim($password)."'";
		$results = $connection->execute($sql)->fetchAll('assoc');
		

		$mssage = array();
			
		
		if(@$results[0]['status']=='1'){
			
			$this->Auth->setUser($results);			
			//SET SESSION...
			$session = $this->request->session();
			$session->write('Current_User',$results); 
			$getUsers = $this->request->session()->read('Current_User');
			//print_r($getUsers);
			//--------------
			$mssage['msg'] = 'Login Successfull';
			$mssage['response_code'] = '1';

		}else{
			//echo json_encode($this->request->data);die;			
			$mssage['msg'] = 'Invalid username or password, try again.';
			$mssage['response_code'] = '0';
		}
		
		echo json_encode($mssage);		
		die;
	}

	/**
     * Allow a user to request a password reset.
     * @return
     */
     function forgot() {

		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		$userInfo = TableRegistry::get('Users');
		$userDetails = TableRegistry::get('UserDetails');
	
		
	if(isset($_REQUEST['email'])){
     $user_email =  $_REQUEST['email'];
		
	  if (!empty($user_email)) {
			$users = $userInfo->find('all', ['conditions'=>['Users.email'=>$user_email]]);
			$user = $users->first();

			$user_details = @$userDetails->find('all', ['conditions'=>['UserDetails.user_id'=>@$user->id]]);
			$userInfo = $user_details->first();
			
			//echo $user->email;die;
			
			if(!$user){
				
				$mesg['msg']='Sorry, the username entered was not found.';
				$mesg['response_code']='0';
				echo json_encode($mesg);die;
								
			}else{

				//Time::$defaultLocale = 'es-ES';
               // $link = BASE_SERVER_URL.'logins/forgotpassform';
				$time = Time::now('Asia/Kolkata');
				$time_new = $time->i18nFormat('yyyy-MM-dd HH:mm:ss');
					$token = $user->id."+".$user->email."+".$time_new;
					$token_new = base64_encode($user->email);

					$reset_link = Router::url('/', true).FORGOT_PAGE_LINK.'?token='.$token_new;

					//$reset_link = 'http://localhost:62614/index.html#/setpassword'.'?token='.$token_new;	
					$reset_link = 'http://58.185.95.114:8282/Frontend/index.html#/setpassword'.'?token='.$token_new;	
					//$reset_link = 'http://www.nanoappstore.com:8282/index.html#/setpassword'.'?token='.$token_new;
							//$email = new Email('mailjet');
							$email = new Email();
							$email->transport('mailjet');
							$email->viewVars(['last_name' =>$userInfo->lastname]);
							$email->viewVars(['reset_link' =>$reset_link]);
							$email->viewVars(['logo_img_path' =>Router::url('/',true).LOGO_IMAGE_LINK]);

							$email->from(['bls@gmail.com' => 'BLS-ADMIN'])
							->template('forgot', 'htmlemail')	
							->emailFormat('html')
							->to($user->email)
							->subject('Forgot Password')
							->send();	 

							if($email){
								$mesg['msg']='Mail has been sent. Please  check your inbox.';
								$mesg['response_code']='1';
								echo json_encode($mesg);die;
							}
				
				}
			
			
            }
	
			}
				
        } 
		

	public function allcategories(){

		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');  
		

		$connection = ConnectionManager::get('default');
		$sql = "SELECT *  FROM `categories`
		LEFT JOIN category_details ON categories.id = category_details.category_id
		WHERE `parent_id` = NULL";

		$results = $connection->execute($sql)->fetchAll('assoc');
		$i=0;
		foreach($results as $key=>$records){
			$results[$key]['image_url_path'] = $this->imagePath.$records['image'];
			$i=$i+1;
		}		
		echo json_encode($results);
		die;
	}
	

	public function getuseraccess2(){

		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');  
		$admin = TableRegistry::get('Categories');
		$admis = $admin->find('all');
		$session = $this->request->session();
		$getUsers = $this->request->session()->read('Current_User');
		print_r($getUsers);		
		//echo json_encode($admis);
		die;

	}
	
	
		
  
}
