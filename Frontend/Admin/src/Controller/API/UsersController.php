<?php
namespace App\Controller\API;
use Cake\Datasource\ConnectionManager;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use Users\Users;
use Cake\Routing\Router;
use Promotions\Promotions;
/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
	public $imagePath = IMG_PATH_PRODUCTS;
	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

	public function beforeFilter(Event $event)
    { 
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		$this->Auth->allow(['view','register','registertest','add','getLogout','getUserAccountDetails','ChangeUserPasswordForgot','ChangeUserPasswordAccount','getWishlist','addToWishlist','updateAccountDetails','getUserAddressBook','saveUserAddressBook','contactUs','setPasswordForgot']);
    }
	  /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function contactUs()
    {
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		$template=array();
		$Configrations = TableRegistry::get('Configrations');
		$arrConfig = $Configrations->find();
		$configration=$arrConfig->first();
		$ADMIN_FEEDBACK_EMAIL = $configration->adm_fdbk_email;
		//$ADMIN_FEEDBACK_EMAIL ='deepak.rathi@sdnainfotech.com';

		

		$template['name']= @$_REQUEST['name'];
		$template['email']=@$_REQUEST['email'];
		$template['block_no']=@$_REQUEST['block_no'];
		$template['unit_no']=@$_REQUEST['unit_no'];
		$template['street']=@$_REQUEST['street'];
		//$template['country']=@$_REQUEST['country'];
		$template['country']='SINGAPORE';
		$template['postal_code']=@$_REQUEST['postal_code'];
		$template['telephone_no']=@$_REQUEST['telephone_no'];
		$template['fax_no']=@$_REQUEST['fax_no'];
		$template['website']=@$_REQUEST['website'];
		$template['yourmessage']=@$_REQUEST['yourmessage'];

		if(!empty($template['fax_no'])){
			$template['fax_flag']=1;		
		}else{
			$template['fax_flag']=0;
		}
		
		if(isset($_REQUEST)){
	
				if(empty($template['name'])){
					$response['msg']='Please enter your name.';
					$response['response_code']='0';			
					echo json_encode($response);
					exit;
				}

				if(empty($template['email'])){
					$response['msg']='Email can`t be empty.';
					$response['response_code']='0';			
					echo json_encode($response);
					exit;
				}

				if (!filter_var($template['email'], FILTER_VALIDATE_EMAIL)) { //INVALID EMAIL CHECK.
					$mesg['msg']='The email is not a valid email. Please, try again.';
					$mesg['response_code']='0';
					echo json_encode($mesg);
					die();
			   }
				
				

				if(empty($template['telephone_no'])){
					$response['msg']='Please enter your contact no.';
					$response['response_code']='0';			
					echo json_encode($response);
					die;
				}

				if(strlen($template['telephone_no']) > 20){
				$mesg['msg']='Maximum 20 characters can be used for telephone no.';
				$mesg['response_code']='0';
				echo json_encode($mesg);
				die();
			    }

				if(empty($template['yourmessage'])){
					$response['msg']='Please enter your comments.';
					$response['response_code']='0';			
					echo json_encode($response);
					die;
				}



				if(empty($template['email'])){
					//$response['msg']='Email can`t be empty.';
					//$response['response_code']='0';			
					//echo json_encode($response);
					//die;
				}else{
				
					//SEND EMAIL...............................
					//http://book.cakephp.org/3.0/en/core-libraries/email.html
						$emailer = new Email();
						$emailer->transport('mailjet');
						$emailer->viewVars(['template' =>$template]);
						//$emailto='deepak.rathi@sdnainfotech.com';
						//$emailto=$email;
						$emailer->viewVars(['logo_link' =>Router::url('/', true)]);

							
						try {

						$res = $emailer->from([''.$template['email'].''])
							->template('contactus', 'htmlemail')
							->emailFormat('html')
							->to([$ADMIN_FEEDBACK_EMAIL => 'BLS-Contact User'])
							->subject('Feedback/Enquiry from Contact-us section')                   
							->send();

						

						}catch (Exception $e){
							$mesg['msg'][0] ='Exception : '.$e->getMessage(). "\n";
							$mesg['response_code']='0';

						}

						
					//----------------------------------------

						$mesg['msg']='You have been contacted with site admin.Your reply will be soon.';
						$mesg['response_code']='1';
						echo json_encode($mesg);
						die;
						die($ADMIN_FEEDBACK_EMAIL);
				
				}
				
			
		}else{
						$mesg['msg']='Form is not submitted.';
						$mesg['response_code']='0';
						echo json_encode($mesg);
		}

	}
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $users = $this->paginate($this->Users);	
        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

	public function updateAccountDetails($token=null,$user_id=null){

		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');

		//CHECK LOGIN USER
		$session = $this->request->session();
		$current_user = $this->request->session()->read('Current_User');

	//	$emailAddress = @$_REQUEST['email'];
		$password = @$_REQUEST['password'];
		$cnfpassword = @$_REQUEST['confirm_password'];
		$firstname = @$_REQUEST['firstname'];
		$lastname = @$_REQUEST['lastname'];
		$login_user_id = $user_id;	

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

			
			if(strcmp($password,$cnfpassword)!=0){
				$mesg['msg']='Confirm password does not match.Please try again.';
				$mesg['response_code']='0';	
				echo json_encode($mesg);
				die;	
			}

			$Users = TableRegistry::get('Users');
			$UserDetails = TableRegistry::get('UserDetails');

			

			$current_user = $Users->get($user_id); 
		
			$current_user->password = md5($password);
			$Users->save($current_user); //save/update users table.
			
			//echo $user_id;
			
			$current_user_details = $UserDetails->find('all', ['conditions'=>['UserDetails.user_id'=>$user_id]]);	
			$userInfo = $current_user_details->first();
			
			$userInfo->firstname=$firstname;
			$userInfo->lastname=$lastname;
				
			$UserDetails->save($userInfo); //save/update users details table.

			
			
			$userInfoDisplay = array();
			$userInfoDisplay['email']=$current_user->email;
			$userInfoDisplay['firstname']=$userInfo->firstname;
			$userInfoDisplay['lastname']=$userInfo->lastname;
			$userInfoDisplay['msg']='Your account details has been updated.';
			$userInfoDisplay['response_code']='1';	
			echo json_encode($userInfoDisplay);
			die;

		}

		
		
	}

	public function addToWishlist($token=null,$user_id=null,$prod_id){
		 header('Content-Type: application/json');
		 header('Access-Control-Allow-Origin: *');		

		 $Wishlists = TableRegistry::get('Wishlists');

		 $wish_list =$Wishlists->newEntity();

		 //$wish_list->user_id='19';
		 $wish_list->product_id=$prod_id;

		$session = $this->request->session();
		$current_user = $this->request->session()->read('Current_User');

		$wish_list->user_id = $user_id;
		// GET USER TOKEN
		require_once(ROOT .DS. "Vendor" . DS  . "Users" . DS . "Users.php");		
		$Users = new Users;
		$user_token = $Users->login_token($user_id);

		//echo strcmp((string)$token,(string)$user_token);

		//------------------------------------

		if(strcmp((string)$token,(string)$user_token)!=0){

			$response['msg']='Unauthorized access. Please login to get details.';
			$response['response_code']='0';			
			 $this->set(array(
            'response' => $response,
            '_serialize' => array('response')
			));
		
		}else{
			if ($Wishlists->save($wish_list)) {
				$response['msg']='Product has been added to wish list.';
				$response['response_code']='1';			
				$this->set(array(
				'response' => $response,
				'_serialize' => array('response')
				));
			} else {
				$response['msg']='Product not added to list. Duplicate will also not be added';
				$response['response_code']='0';			
				$this->set(array(
				'response' => $response,
				'_serialize' => array('response')
				));
			}
		}

		

	}

	public function getWishlist($token=null,$user_id=null){
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');		
		$connection = ConnectionManager::get('default');

		$session = $this->request->session();
		$current_user = $this->request->session()->read('Current_User');

		// GET USER TOKEN
		require_once(ROOT .DS. "Vendor" . DS  . "Users" . DS . "Users.php");		
		$Users = new Users;
		$user_token = $Users->login_token($user_id);

		//echo strcmp((string)$token,(string)$user_token);



		if(strcmp((string)$token,(string)$user_token)!=0){
			$response['msg']='Unauthorized access. Please login to get details.';
			$response['response_code']='0';			
			 $this->set(array(
            'response' => $response,
            '_serialize' => array('response')
			));
		
		}else{

			$login_user_id = $user_id;	
			$sql = "
			SELECT 
				Wishlist.id as WISHLIST_id,
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
				ProductsImages.*,
				IF(ProductsImages.image ='', 'empty-product-img.png',ProductsImages.image) as image,
				ProductsMarketingImages.*,
				IF(ProductsMarketingImages.image ='', 'empty-product-img.png',ProductsMarketingImages.image) as image,
				ProductsPrices.*,
				ProductsRelated.*,
				ProductsCategory.*
  			FROM 
				wishlists as Wishlist
				LEFT JOIN products as Product ON (Product.id=Wishlist.product_id)
                LEFT JOIN products_attrs as ProductsAttr ON (ProductsAttr.product_id=Product.id)
				LEFT JOIN products_images as ProductsImages ON (ProductsImages.product_id=Product.id)
				LEFT JOIN products_marketing_images as ProductsMarketingImages ON (ProductsMarketingImages.product_id=Product.id)
				LEFT JOIN products_prices as ProductsPrices ON (ProductsPrices.product_id=Product.id)
				LEFT JOIN products_relateds as ProductsRelated ON (ProductsRelated.product_id=Product.id)
				LEFT JOIN products_categories as ProductsCategory ON (ProductsCategory.product_id=Product.id)
				
			WHERE				
				Wishlist.user_id=".$login_user_id." GROUP BY Product.id
			";
			$products['products'] = $connection->execute($sql)->fetchAll('assoc');
			
			require_once(ROOT .DS. "Vendor" . DS  . "Promotions" . DS . "Promotions.php");		
			$Promotions = new Promotions;

			//GET PRODUCT PROMO INFORMATION
			foreach($products['products'] as $key=>$product){

			$products['products'][$key]['PRODUCT_PROMOTION'] = $Promotions->getDiscountDisplayProduct($product['PRODUCT_ID'],1);
		
			}
			//-----------------------------
			$products['PRODUCTS_IMG_PATH'] = Router::url('/',true).$this->imagePath;
			echo json_encode($products);
			die;
		
		}
	
	}

	/**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function ChangeUserPasswordForgot()
    {
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');

		$connection = ConnectionManager::get('default');
        $password = @$_REQUEST['password'];
		$cnfpassword = @$_REQUEST['confirm_password'];
		$user_email = @$_REQUEST['user_email'];

		//$password='deepak';
		//$cnfpassword='deepak';
		//$user_email='himani.arora@sdnainfotech.com';

			if(strcmp($password,$cnfpassword)!=0){
			$mesg['msg']='Confirm password does not match.Please try again.';
			$mesg['response_code']='0';			
			$this->set(array(
			'message' => $mesg,
			'_serialize' => array('message')
			));	


		}else{

			if(empty($user_email)){
				$mesg['msg']='User email is blank. Password change not allowed.';
				$mesg['response_code']='0';			
				$this->set(array(
				'message' => $mesg,
				'_serialize' => array('message')
				));
			}else{

				//GET TOKEN
				
				//------------
				//Update Password..
				$sqlUpdate = "UPDATE `users` SET `password` = MD5('".$password."') WHERE `users`.`email` = '".$user_email."'";
				//-----------------
				$results = $connection->execute($sqlUpdate);
				$mesg['msg']='Password has been updated.';
				$mesg['response_code']='1';			
				$this->set(array(
				'message' => $mesg,
				'_serialize' => array('message')
				));
			}
				
		}
    }

	/**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function setPasswordForgot()
    {
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');

		$connection = ConnectionManager::get('default');
        $password = @$_REQUEST['password'];
		$cnfpassword = @$_REQUEST['confirm_password'];
		//$user_email = @$_REQUEST['user_email'];
		$token = @$_REQUEST['token'];

		
	
		//$password='deepak';
		//$cnfpassword='deepak';
		//$user_email='himani.arora@sdnainfotech.com';

		if(empty($token)){
				$mesg['msg']='Links is not valid.';
				$mesg['response_code']='0';	
				echo json_encode($mesg);
				die;
		}

		if(strcmp($password,$cnfpassword)!=0){
			$mesg['msg']='Confirm password does not match.Please try again.';
			$mesg['response_code']='0';	
			echo json_encode($mesg);
			die;
			/*
			$this->set(array(
			'message' => $mesg,
			'_serialize' => array('message')
			));	*/


		}else{

				//GET TOKEN
				$user_email = base64_decode($token);
				//------------
				
			if(empty($user_email)){
				$mesg['msg']='User email is blank. Password change not allowed.';
				$mesg['response_code']='0';			
				$this->set(array(
				'message' => $mesg,
				'_serialize' => array('message')
				));
			}else{

				
				//Update Password..
				$sqlUpdate = "UPDATE `users` SET `password` = MD5('".$password."') WHERE `users`.`email` = '".$user_email."'";
				//-----------------
				$results = $connection->execute($sqlUpdate);
				$mesg['msg']='Password has been updated.';
				$mesg['response_code']='1';			
				

				echo json_encode($mesg);
				die;
			}
				
		}
    }

	/**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function ChangeUserPasswordAccount($token=null,$user_id=null)
    {
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');

		$connection = ConnectionManager::get('default');
		$old_password = md5(@$_REQUEST['old_password']);
        $password = @$_REQUEST['password'];
		$cnfpassword = @$_REQUEST['confirm_password'];
		
		if($user_id==NULL){
			if(empty($token)){
				$mesg['msg']='Given token for link is not valid.';
				$mesg['response_code']='0';	
				echo json_encode($mesg);
				die;
			}else{

				$getEmailId = base64_decode(@$_REQUEST['token']);
				
			}
		
		}

		// GET USER TOKEN
		require_once(ROOT .DS. "Vendor" . DS  . "Users" . DS . "Users.php");		
		$Users = new Users;
		$user_token = $Users->login_token($user_id);

		//echo strcmp((string)$token,(string)$user_token);



		if(strcmp((string)$token,(string)$user_token)!=0){
			$mesg['msg']='Unauthorized access. Please login to get details.';
			$mesg['response_code']='0';		
			
			 echo json_encode($mesg);
			 die;
		
		}

		//$old_password = md5('dee123');
        //$password = 'dee1234';
		//$cnfpassword = 'dee1234';  
		//-------------------------------------------
			//get Users..
			$sqlUsers = "SELECT * FROM users WHERE `users`.password = '".$old_password."' AND `users`.id=".$user_id;
			//-------------------------------------------------------------------------------------------------------
			$userInfo  = $connection->execute($sqlUsers)->fetchAll('assoc');
			
			
			if(empty($userInfo)){				
				$mesg['msg']='Old password does not match.Please try again.';
				$mesg['response_code']='0';	
				echo json_encode($mesg);
				die;
				
			}

		
		//-------------------------------------------


		

		//$password='deepak';
		//$cnfpassword='deepak';
		//$user_email='himani.arora@sdnainfotech.com';

		if(empty($user_id)){
				$mesg['msg']='User id is blank. Password change not allowed.';
				$mesg['response_code']='0';			
				echo json_encode($mesg);
				die;				
		}

		if(strcmp($password,$cnfpassword)!=0){

				$mesg['msg']='Confirm password does not match.Please try again.';
				$mesg['response_code']='0';			
				echo json_encode($mesg);
				die;

		}else{

				//Update Password..
				$sqlUpdate = "UPDATE `users` SET `password` = MD5('".$password."') WHERE `users`.id=".$user_id;
				//---------------------------------------------------------------------------------------------
				$results = $connection->execute($sqlUpdate);
				$mesg['msg']='Password has been updated.';
				$mesg['response_code']='1';			
				echo json_encode($mesg);
				die;

			
				
		}
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
        $user = $this->Users->get($id, [
            'contain' => ['Carts', 'Orders', 'UserAddresses', 'UserDetails']
        ]);

       
		//echo json_encode($user);
		$data = json_decode(json_encode($user));
		
	
	
    }

	public function getLogout(){

		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');

		$session = $this->request->session();
		$this->request->session()->destroy('Current_User');
		$this->request->session()->destroy('CART_INFO');
		$cartDetails = $this->request->session()->read('CART_INFO');
		
		$mesg['msg']='User successfully logged out from system.';
		$mesg['response_code']='1';
		echo json_encode($mesg);
		die;
	}

	/**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function registertest()
    {
			header('Content-Type: application/json');
			header('Access-Control-Allow-Origin: *');		
		

			$email=@$_REQUEST['email'];
			$password=@$_REQUEST['password'];
			$fname=@$_REQUEST['fname'];
			$lname=@$_REQUEST['lname'];
			$blockno=@$_REQUEST['blockno'];
			$unitno=@$_REQUEST['unitno'];
			$street=@$_REQUEST['street'];
			$country='Singapore';
			$postalcode=@$_REQUEST['postalcode'];
			$telephoneno=@$_REQUEST['telephoneno'];
			$fax_no=@$_REQUEST['fax_no'];
			$compname=@$_REQUEST['compname'];
			$position=@$_REQUEST['position'];
			$search =$email; 
			
			
			
			
			if(empty($email)){ //EMPTY CHECK
			
				$mesg['msg']='The email is empty11. Please, try again.';
				$mesg['response_code']='0';
				echo json_encode($mesg);
				die();
			}

			
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //INVALID EMAIL CHECK.
				$mesg['msg']='The email is not a valid email. Please, try again.';
				$mesg['response_code']='0';
				echo json_encode($mesg);
				die();
			}

        $user = $this->Users->newEntity();
		$getArray = array(
			'email'=>$email,
			'password'=>$password,			
			'status'=>'1');

		$user = $this->Users->patchEntity($user,$getArray);		

		$query = $this->Users->find('all', [
			'conditions' => ['Users.email LIKE '=>'%' .$search. '%'],
			'limit' => 1
			])->all();
		
			$counts = $query->count(); 

			if ($counts=='1') {  //DUPLICATE MAIL CHECK
				$mesg['msg']='The email already in use. Please, try again.';
				$mesg['response_code']='0';
				echo json_encode($mesg);
				die();
			} 



		if ($this->Users->save($user)) {

			//print_r($user->id);
			$userAddress = TableRegistry::get('UserAddresses');
			$UserDetails = TableRegistry::get('UserDetails');

			$user_address = $userAddress->newEntity();
			
			
			$user_address->user_id = $user->id;
			$user_address->street_address = $street;
			//$user_address->country = $country;
			$user_address->country = 'Singapore';
			$user_address->postalcode = $postalcode;
			$user_address->telephone = $telephoneno;
			$user_address->fax_no = $fax_no;

			

			if ($userAddress->save($user_address)) {		
				$userAddress_lastid = $user_address->id;
			}

			$User_details = $UserDetails->newEntity();

			$User_details->user_id = $user->id;
			$User_details->firstname = $fname;
			$User_details->lastname = $lname;
			$User_details->blockno = $blockno;
			$User_details->unitno = $unitno;
			$User_details->company = $compname;
			$User_details->position = $position;

			if ($UserDetails->save($User_details)) {		
				//$User_details_lastid = $UserDetails->getLastInsertID();
			}

			//SEND EMAIL...............................
			//http://book.cakephp.org/3.0/en/core-libraries/email.html
				$emailer = new Email();
				$emailer->transport('mailjet');

				$emailer->viewVars(['last_name' =>$lname]);
				//$emailto='deepak.rathi@sdnainfotech.com';
				$emailto=$email;
				try {
				$res = $emailer->from(['bls@admin.com'])
				->template('welcome', 'fancy')
				 ->emailFormat('html')
				->to([$emailto => 'BLS-New User'])
				->subject('Membership activation')                   
				->send('test-email...');

				}catch (Exception $e){

				echo 'Exception : ',  $e->getMessage(), "\n";
				}
			//----------------------------------------

			$mesg['msg']='The user has been saved.';
			$mesg['response_code']='1';
		} else {
			$mesg['msg']='The user could not be saved. Please, try again.';
			$mesg['response_code']='0';
		}

		echo json_encode($mesg);
		die();
    }

	/**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function register()
    {
			header('Content-Type: application/json');
			header('Access-Control-Allow-Origin: *');	

			$email=@$_REQUEST['email'];
			$password=@$_REQUEST['password'];
			$fname=@$_REQUEST['fname'];
			$lname=@$_REQUEST['lname'];
			$blockno=@$_REQUEST['blockno'];
			$unitno=@$_REQUEST['unitno'];
			$street=@$_REQUEST['street'];
			$country='Singapore';
			$postalcode=@$_REQUEST['postalcode'];
			$telephoneno=@$_REQUEST['telephoneno'];
			$fax_no=@$_REQUEST['fax_no'];
			$compname=@$_REQUEST['compname'];
			$position=@$_REQUEST['position'];
			$search =$email; 
			
			
		
			if(empty($email)){ //EMPTY CHECK			
				$mesg['msg']='The email is empty. Please, try again.';
				$mesg['response_code']='0';
				echo json_encode($mesg);
				die();
			}

			//die($email);
			if (!filter_var($email,FILTER_VALIDATE_EMAIL)) { //INVALID EMAIL CHECK.
				$mesg['msg']='The email is not a valid email1111. Please, try again.';
				$mesg['response_code']='0';
				echo json_encode($mesg);
				die();
			}

			if ( !preg_match('/^[a-z0-9A-Z]*$/',$password) )
			{
			
				$mesg['msg']='The password that you have given is not valid.It should be alphanumeric.Please try again.';
				$mesg['response_code']='0';
				echo json_encode($mesg);
				die();
			} 
			
			if(strlen($password) < 6){
				$mesg['msg']='Minimum 6 characters required for password.';
				$mesg['response_code']='0';
				echo json_encode($mesg);
				die();
			}

			if(!ctype_digit($telephoneno)){
				$mesg['msg']='Given telephone number is not a numeric.';
				$mesg['response_code']='0';
				echo json_encode($mesg);
				die();
			}

			if(strlen($telephoneno) > 20){
				$mesg['msg']='Maximum 20 characters can be used for telephone no.';
				$mesg['response_code']='0';
				echo json_encode($mesg);
				die();
			}
		
			if(!empty($fax_no)){
				if(!ctype_digit($fax_no)){
					$mesg['msg']='Given fax number is not a numeric.';
					$mesg['response_code']='0';
					echo json_encode($mesg);
					die();
				}

				if(strlen($fax_no) > 20){
					$mesg['msg']='Maximum 20 characters can be used for fax no.';
					$mesg['response_code']='0';
					echo json_encode($mesg);
					die();
				}
			}

        $user = $this->Users->newEntity();
		$getArray = array(
			'email'=>$email,
			'password'=>md5($password),			
			'status'=>'1');

		$user = $this->Users->patchEntity($user,$getArray);		

		$query = $this->Users->find('all', [
			'conditions' => ['Users.email LIKE '=>'%' .$search. '%'],
			'limit' => 1
			])->all();
		
			$counts = $query->count(); 

			if ($counts=='1') {  //DUPLICATE MAIL CHECK
				$mesg['msg']='The email already in use. Please, try again.';
				$mesg['response_code']='0';
				echo json_encode($mesg);
				die();
			} 



		if ($this->Users->save($user)) {

			//print_r($user->id);
			$userAddress = TableRegistry::get('UserAddresses');
			$UserDetails = TableRegistry::get('UserDetails');

			$user_address = $userAddress->newEntity();
			
			
			$user_address->user_id = $user->id;
			$user_address->street_address = $street;
			//$user_address->country = $country;
			$user_address->country = 'Singapore';
			$user_address->postalcode = $postalcode;
			$user_address->telephone = $telephoneno;
			$user_address->fax_no = $fax_no;

			

			if ($userAddress->save($user_address)) {		
				$userAddress_lastid = $user_address->id;
			}

			$User_details = $UserDetails->newEntity();

			$User_details->user_id = $user->id;
			$User_details->firstname = $fname;
			$User_details->lastname = $lname;
			$User_details->blockno = $blockno;
			$User_details->unitno = $unitno;
			$User_details->company = $compname;
			$User_details->position = $position;

			if ($UserDetails->save($User_details)) {		
				//$User_details_lastid = $UserDetails->getLastInsertID();
			}

			//SEND EMAIL...............................
			//http://book.cakephp.org/3.0/en/core-libraries/email.html
				$emailer = new Email();
				$emailer->transport('mailjet');

				$emailer->viewVars(['last_name' =>$lname]);
				$emailer->viewVars(['logo_img_path' =>Router::url('/',true).LOGO_IMAGE_LINK]);
				$emailto=$email;
				
				try {

				$res = $emailer->from(['bls@admin.com'])
				->template('welcome', 'fancy')
				 ->emailFormat('html')
				->to([$emailto => 'BLS-New User'])
				->subject('Membership activation')                   
				->send();

				}catch (Exception $e){

				echo 'Exception : ',  $e->getMessage(), "\n";
				}
			//----------------------------------------

			$mesg['msg']='The user has been saved.';
			$mesg['response_code']='1';
		} else {
			$mesg['msg']='The user could not be saved. Please, try again.';
			$mesg['response_code']='0';
		}

		echo json_encode($mesg);
		die();
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    public function getUserAccountDetails($token=null,$user_id=null){
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		
		
		$connection = ConnectionManager::get('default');

		$session = $this->request->session();
		$current_user = $this->request->session()->read('Current_User');

		// GET USER TOKEN
		require_once(ROOT .DS. "Vendor" . DS  . "Users" . DS . "Users.php");		
		$Users = new Users;
		if($user_id!=null)
		$user_token = $Users->login_token($user_id);
		else
		$user_token='';
			
		//------------------------------------

		if(strcmp((string)@$token,(string)@$user_token)!=0){
			
			$response['msg']='Unauthorized access. Please login to get details.';
			$response['response_code']='0';			
			 $this->set(array(
            'response' => $response,
            '_serialize' => array('response')
			));
		
		}else{
			$login_user_id = $user_id;	
			$sql = "
			SELECT 
				Usr.id as USER_ID,
				Usr.email as USER_EMAIL,
				Usr.status as USER_STATUS,
				UsrAddress.street_address as STREET_ADDRESS,
				UsrAddress.city as CITY,
				UsrAddress.state as STATE,
				UsrAddress.country as COUNTRY,
				UsrAddress.postalcode as POSTAL_CODE,
				UsrAddress.telephone as TELEPHONE,
				UsrAddress.fax_no as FAX_NO,
				UsrDetails.*
			FROM 
				`users` as Usr
				LEFT JOIN user_addresses as UsrAddress ON (UsrAddress.user_id = Usr.id)
				LEFT JOIN user_details as UsrDetails ON (UsrDetails.user_id = Usr.id)
			WHERE 
				Usr.id=".$login_user_id."
			";
			if($user_id!=null)
			$userDetails = $connection->execute($sql)->fetchAll('assoc');
			else
			$userDetails=array();

			$this->set(array(
			'userDetails' => $userDetails,
			'_serialize' => array('userDetails')
			));
		}
		
	}

	public function getUserAddressBook($token=null,$user_id=null){
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		  
		
		$connection = ConnectionManager::get('default');
		$session = $this->request->session();
		$current_user = $this->request->session()->read('Current_User');

		// GET USER TOKEN
		require_once(ROOT .DS. "Vendor" . DS  . "Users" . DS . "Users.php");		
		$Users = new Users;
		$user_token = $Users->login_token($user_id);

			//echo strcmp((string)$token,(string)$user_token);
			//die;
		//------------------------------------
		
		if(strcmp((string)$token,(string)$user_token)!=0){
			$response['msg']='Unauthorized access. Please login to get details.';
			$response['response_code']='0';			
			 $this->set(array(
            'response' => $response,
            '_serialize' => array('response')
			));
		
		}else{

			$login_user_id = $user_id;	
			$sql = "
			SELECT 
				Usr.id as USER_ID,
				Usr.email as USER_SHIPPING_EMAIL,
				Usr.status as USER_STATUS,
				UsrAddress.street_address as USER_SHIPPING_ADDRESS,
				UsrAddress.city as USER_SHIPPING_CITY,
				UsrAddress.state as USER_SHIPPING_STATE,
				UsrAddress.country as USER_SHIPPING_COUNTRY,
				UsrAddress.postalcode as USER_SHIPPING_POSTAL_CODE,
				UsrAddress.telephone as USER_SHIPPING_CONTACT_NO,
				UsrAddress.fax_no as USER_SHIPPING_FAX_NO,
				UsrDetails.firstname as USER_SHIPPING_FIRSTNAME,
				UsrDetails.lastname as USER_SHIPPING_LASTNAME,
				CONCAT(UsrDetails.firstname,' ',UsrDetails.lastname) as USER_SHIPPING_RECEIVER_NAME,
				UsrDetails.mobile as USER_SHIPPING_MOBILE,
				UsrDetails.blockno as USER_SHIPPING_BLOCK,
				UsrDetails.unitno as USER_SHIPPING_UNITNO,
				UsrBillingAddress.sender_name as USER_BILLING_SENDERNAME,
				UsrBillingAddress.telephone_no as USER_BILLING_CONTACT_NO,
				UsrBillingAddress.fax_no as USER_BILLING_FAXNO,
				UsrBillingAddress.email_address as USER_BILLING_EMAIL,
				UsrBillingAddress.block_no as USER_BILLING_BLOCK_NO,
				UsrBillingAddress.unit_no as USER_BILLING_UNIT_NO,
				UsrBillingAddress.street as USER_BILLING_STREET_NAME,
				UsrBillingAddress.postalcode as USER_BILLING_POSTAL_CODE,
				UsrBillingAddress.country as USER_BILLING_COUNTRY
			FROM 
				`users` as Usr
				LEFT JOIN user_addresses as UsrAddress ON (UsrAddress.user_id = Usr.id)
				LEFT JOIN user_billings as UsrBillingAddress ON (UsrBillingAddress.user_id = Usr.id)
				LEFT JOIN user_details as UsrDetails ON (UsrDetails.user_id = Usr.id)
			WHERE 
				Usr.id=".$login_user_id." GROUP BY Usr.id
			";

			
			
			$userDetails = $connection->execute($sql)->fetchAll('assoc');
			
			$this->set(array(
			'userDetails' => $userDetails,
			'_serialize' => array('userDetails')
			));
		}
		
	}

	function saveUserAddressBook($token=null,$user_id=null){
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');	  
		
		$connection = ConnectionManager::get('default');
		$session = $this->request->session();
		$current_user = $this->request->session()->read('Current_User');

		// GET USER TOKEN
		require_once(ROOT .DS. "Vendor" . DS  . "Users" . DS . "Users.php");		
		$Users = new Users;
		$user_token = $Users->login_token($user_id);

			//echo strcmp((string)$token,(string)$user_token);
			//die;
		//------------------------------------
		
		if(strcmp((string)$token,(string)$user_token)!=0){
			$response['msg']='Unauthorized access. Please login to get details.';
			$response['response_code']='0';			
			 $this->set(array(
            'response' => $response,
            '_serialize' => array('response')
			));
		
		}else{
				//UPDATE BILLING ADDRESS BOOK
			
				$UserBillings = TableRegistry::get('UserBillings');
				$billing = $UserBillings->newEntity();

				$billing['user_id']= @$user_id;
				$billing['sender_name']= @$_REQUEST['USER_BILLING_SENDERNAME'];
				$billing['telephone_no']=@$_REQUEST['USER_BILLING_CONTACT_NO'];
				$billing['fax_no']=@$_REQUEST['USER_BILLING_FAXNO'];
				$billing['email_address']=@$_REQUEST['USER_BILLING_EMAIL'];
				$billing['block_no']=@$_REQUEST['USER_BILLING_BLOCK_NO'];
				$billing['unit_no']=@$_REQUEST['USER_BILLING_UNIT_NO'];
				$billing['street']=@$_REQUEST['USER_BILLING_STREET_NAME'];
				$billing['postalcode']=@$_REQUEST['USER_BILLING_POSTAL_CODE'];
				$billing['country']='SINGAPORE';
			
				$arrUserBillings = $UserBillings->find('all')->where(['user_id'=>$user_id]);
				//CHECK IF USER'S DATA EXISTS.
			
				
				if(empty($arrUserBillings->toArray())){
				
					//SAVE
					$UserBillings->save($billing);
					$response['msg_1']='Billing Address Inserted.';
				    $response['response_code']='1';
				}else{
					
					//Update ....
					$UserBillingsRS = $UserBillings->get(@$arrUserBillings->toArray()[0]->id);
					//pre($UserBillingsRS->id);
					$billing['id']= @$UserBillingsRS->id;
					$billing['sender_name']= @$_REQUEST['USER_BILLING_SENDERNAME'];
					$billing['telephone_no']=@$_REQUEST['USER_BILLING_CONTACT_NO'];
					$billing['fax_no']=@$_REQUEST['USER_BILLING_FAXNO'];
					$billing['email_address']=@$_REQUEST['USER_BILLING_EMAIL'];
					$billing['block_no']=@$_REQUEST['USER_BILLING_BLOCK_NO'];
					$billing['unit_no']=@$_REQUEST['USER_BILLING_UNIT_NO'];
					$billing['street']=@$_REQUEST['USER_BILLING_STREET_NAME'];
					$billing['postalcode']=@$_REQUEST['USER_BILLING_POSTAL_CODE'];
					$billing['country']='SINGAPORE';
					
					$UserBillings->save($billing);
					//-----------
					$response['msg']='Billing Address book updated.';
				    $response['response_code']='1';			
				}


				//UPDATE SHIPPING ADDRESS BOOK

				$UsrAddress = TableRegistry::get('UserAddresses');
				$shipping = $UsrAddress->newEntity();

				$shipping['user_id']= @$user_id;
				$shipping['receiver_name']= @$_REQUEST['USER_SHIPPING_RECEIVER_NAME'];
				$shipping['street_address']=@$_REQUEST['USER_SHIPPING_ADDRESS'];
				$shipping['city']=@$_REQUEST['USER_SHIPPING_EMAIL'];
				$shipping['state']=@$_REQUEST['USER_SHIPPING_STATE'];
				$shipping['country']='SINGAPORE';
				$shipping['postalcode']=@$_REQUEST['USER_SHIPPING_POSTAL_CODE'];
				$shipping['telephone']=@$_REQUEST['USER_SHIPPING_CONTACT_NO'];				
				$shipping['fax_no']=@$_REQUEST['USER_SHIPPING_FAX_NO'];

			
				$arrUsrAddress = $UsrAddress->find('all')->where(['user_id'=>$user_id]);
				//CHECK IF USER'S DATA EXISTS.
				
				if(empty($arrUsrAddress)){

					//SAVE
					$UsrAddress->save($shipping);
					
				}else{

					//Update ....
					$UserShippingRS = $UsrAddress->get($arrUsrAddress->toArray()[0]->id);
					//pre($UserBillingsRS->id);
					$shipping['id']= @$UserBillingsRS->id;
					$shipping['receiver_name']= @$_REQUEST['USER_SHIPPING_RECEIVER_NAME'];
					$shipping['street_address']=@$_REQUEST['USER_SHIPPING_ADDRESS'];
					$shipping['city']=@$_REQUEST['USER_SHIPPING_EMAIL'];
					$shipping['state']=@$_REQUEST['USER_SHIPPING_STATE'];
					$shipping['country']='SINGAPORE';
					$shipping['postalcode']=@$_REQUEST['USER_SHIPPING_POSTAL_CODE'];
					$shipping['telephone']=@$_REQUEST['USER_SHIPPING_CONTACT_NO'];				
					$shipping['fax_no']=@$_REQUEST['USER_SHIPPING_FAX_NO'];
					
					$UsrAddress->save($shipping);
					//-------------------------------------------------------------------------
					
				}

				//---------------End Shipping -------------------------------------------------
				
					$response['msg']='Address Book updated.';
				    $response['response_code']='1';	

					echo json_encode($response);
					die;
				
				//-----------------------------------------------------------------------------
				

		}
			
	}
	
}
