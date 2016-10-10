<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Controller\Component\Auth;
use Cake\ORM\TableRegistry;
use \Crud\Controller\ControllerTrait;
use Cake\Routing\Router;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{	

	 public $components = [
        'RequestHandler',
        'Crud.Crud' => [
            'actions' => [
                'Crud.Index',
                'Crud.View',
                'Crud.Add',
                'Crud.Edit',
                'Crud.Delete'
            ],
            'listeners' => [
                'Crud.Api',
                'Crud.ApiPagination',
                'Crud.ApiQueryLog'
            ]
        ],
		'Acl' => [
        'className' => 'Acl.Acl'
		]
    ];

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
		$this->loadComponent('Auth', [
			'authenticate' => [
				'Form' => [
					'userModel' => 'Admins',
					'fields' => ['username' => 'email', 'password' => 'password']
					
				   ],
			],
			'authorize' => [
				'Acl.Actions' => ['actionPath' => 'controllers/', 'userModel' => 'Admins']
			],
			
			'loginAction' => [
                        'controller' => 'Logins',
                        'action' => 'index'
            ],
			'loginRedirect' => [
						'plugin' => false,
						'controller' => 'Configrations',
						'action' => 'index'
			],
			'unauthorizedRedirect' => [
						'controller' => 'MyProfile',
						'action' => 'index',
						'prefix' => false
			],
			 'logoutRedirect' => [
						'plugin' => false,
						'controller' => 'Logins',
						'action' => 'index'
			],
			'authError' => 'You are not authorized to access that location.',
			'flash' => [
						'element' => 'error',
						'key'=>'location'
						]
        ]);
	
	
	$config = TableRegistry::get('Configrations');
	$configs = $config->find();
	$cons = $configs->first();
	$path = Router::url('/', true);
		if (!defined('STORE_NAME')) define('STORE_NAME',$cons->store_name);
		if (!defined('LOGO')) define('LOGO',$cons->logo);
		if (!defined('TITLE')) define('TITLE',$cons->title);

		if (!defined('FACEBOOK_LINK')) define('FACEBOOK_LINK',$cons->facebook_link);
		if (!defined('HOME_LINK')) define('HOME_LINK', $path);
		if (!defined('CURRENCY')) define('CURRENCY', $cons->currency);
		if (!defined('GST')) define('GST',$cons->gst);
		if (!defined('DELIVERY_CHARGE')) define('DELIVERY_CHARGE',$cons->delivery_charge);
		if (!defined('MIN_AMT_FREE_DELIVERY')) define('MIN_AMT_FREE_DELIVERY',$cons->min_amt_free_delivery);
		if (!defined('ADM_FDBK_EMAIL')) define('ADM_FDBK_EMAIL', $cons->adm_fdbk_email);
		if (!defined('MIN_AMT_FOR_PROMOTION')) define('MIN_AMT_FOR_PROMOTION',$cons->min_amt_for_promotion);
		if (!defined('INVOICE_MSG')) define('INVOICE_MSG',$cons->min_amt_for_promotion);
	
		
    }
	
	public function beforeFilter(Event $event)
    {
        $this->Auth->allow(['forget','reset','AfterForget']);
	
    } 
	

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
		
		
		
    }


}
