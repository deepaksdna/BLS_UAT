<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Logs Controller
 *
 * @property \App\Model\Table\LogsTable $Logs
 */
class LogsController extends AppController
{
	public $helpers = ['Form', 'Html'];
	public function initialize()
		{
			parent::initialize();
			
			// Set the layout
			$this->viewBuilder()->layout('admin');
			//$this->Auth->allow();  
		}

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
   

    /**
     * View method
     *
     * @param string|null $id Log id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function index()
    {   $id=1;
		$promoid= 2;
        $log = $this->Logs->get($id, [
		'contain'=>['Admins']
		]);
		
		$log2 = $this->Logs->get($promoid, [
		'contain'=>['Admins']
		]);
		
        $this->set('log', $log);
		$this->set('log2', $log2);
        $this->set('_serialize', ['log', 'log2']);
    }

}
