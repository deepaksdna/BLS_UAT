<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
/**
 * Promotions Controller
 *
 * @property \App\Model\Table\PromotionsTable $Promotions
 */
class PromotionsController extends AppController
{	
	
	public $helpers = ['Form', 'Html'];
	public function initialize()
		{
			parent::initialize();
			
			// Set the layout
			$this->viewBuilder()->layout('admin');
			$this->loadComponent('Cewi/Excel.Import');
			//$this->Auth->allow();  
		}
	

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Products', 'ChildProducts']
        ];
        $promotions = $this->paginate($this->Promotions);

        $this->set(compact('promotions'));
        $this->set('_serialize', ['promotions']);
    }

    /**
     * View method
     *
     * @param string|null $id Promotion id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $promotion = $this->Promotions->get($id, [
            'contain' => ['Products', 'ChildProducts']
        ]);

        $this->set('promotion', $promotion);
        $this->set('_serialize', ['promotion']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $promotion = $this->Promotions->newEntity();
        if ($this->request->is('post')) {
            $promotion = $this->Promotions->patchEntity($promotion, $this->request->data);
            if ($this->Promotions->save($promotion)) {
                $this->Flash->success(__('The promotion has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The promotion could not be saved. Please, try again.'));
            }
        }
        $products = $this->Promotions->Products->find('list', ['limit' => 200]);
        $childProducts = $this->Promotions->ChildProducts->find('list', ['limit' => 200]);
        $this->set(compact('promotion', 'products', 'childProducts'));
        $this->set('_serialize', ['promotion']);
    }
	
	public function bulkaddpromo(){
	$Products = TableRegistry::get('Products');

		if ($this->request->is('post')) {
			$error='';
			$datas = $this->Import->prepareEntityData($this->request->data['content']['tmp_name']);
			
			
			
		foreach($datas as $key => $dataArray){
			$count = $key+1;
				$this->request->data['promo_code'] =  $dataArray['Promotion Code'];
				$this->request->data['formal_message_display'] =  $dataArray['Message'];
				$this->request->data['discounted_amount_parent'] =  $dataArray['Discounted Amount Parent'];
				$this->request->data['quantity_parent'] = (int)$dataArray['Quantity Parent'];
				$this->request->data['discount_parent_in'] =  $dataArray['Discount Parent In'];
				$this->request->data['discount_type'] =  $dataArray['Discount Type'];
				

				
				$phpexcepStartDate = $dataArray['Start Date']-25569; //to offset to Unix epoch
				$this->request->data['start_date'] =   date('d-m-Y', strtotime("+$phpexcepStartDate days", mktime(0,0,0,1,1,1970)));
				
				$phpexcepEndDate =$dataArray['End Date']-25569; //to offset to Unix epoch
				$this->request->data['end_date'] =   date('d-m-Y', strtotime("+$phpexcepEndDate days", mktime(0,0,0,1,1,1970)));
	
			
				$associtaed_product = strtolower($dataArray['Associated Product']);
				if($associtaed_product == 'y'){
							$this->request->data['associated_product']=0;
							$exists_ItemCodeChild = $Products->exists(['item_code' => $dataArray['Child Product']]);
							if($exists_ItemCodeChild){
							$productChild = $Products->find()->where(['item_code' => $dataArray['Child Product']]); 
							$idByItemCodeChild = $productChild->first();
							$this->request->data['child_product_id'] =  $idByItemCodeChild->id;	
							}else{
								
								$error .= 'Promotion - '. $count . ': Child Product item code :' .$dataArray['Child Product'] . ' not found, ';
								$this->request->data['child_product_id'] =  NULL;
							}	
							
							
							$this->request->data['discounted_amount_child'] =  $dataArray['Discounted Amount Child'];
							$this->request->data['quantity_child'] =  $dataArray['Quantity Child'];
							$this->request->data['discount_child_in'] =  $dataArray['Discount Child In'];
				}elseif($associtaed_product == 'n'){
							$this->request->data['associated_product']=1;
							$this->request->data['child_product_id'] =  NULL;
							$this->request->data['discounted_amount_child'] =  NULL;
							$this->request->data['quantity_child'] =  NULL;
							$this->request->data['discount_child_in'] =  NULL;
				}
				
				
				$exists_promocode = $this->Promotions->exists(['promo_code' => $dataArray['Promotion Code']]);
		
		if($exists_promocode){
////////////////////////
//Update Exsisting promo
////////////////////////
				$promo_data = $this->Promotions->find()->where(['promo_code' => $dataArray['Promotion Code']]); 
				$idByPromoCode = $promo_data->first();	 
					
			///////////////////
			//If Product exsist
			///////////////////
			$exists_ItemCode = $Products->exists(['item_code' => $dataArray['Item Code']]);
			if($exists_ItemCode){
				
						$product = $Products->find()->where(['item_code' => $dataArray['Item Code']]); 
						$idByItemCode = $product->first();
						$this->request->data['product_id'] =  $idByItemCode->id;
						
				$promotion = $this->Promotions->get($idByPromoCode->id);
				$promotion = $this->Promotions->patchEntity($promotion, $this->request->data);
				if ($this->Promotions->save($promotion)) {
				$error .= 'Promotion - '. $count.': ' .$dataArray['Promotion Code'] . ' updated'; 		
				}else{
				$error .= 'Promotion - '. $count . ': ' .$dataArray['Promotion Code'] . ' not updated';		
				}
			}else{
				
				$error .= 'Promotion - '. $count . ': Item Code -' .$dataArray['Item Code'] . ' not found';
			}
			
		}else{
///////////////
//Add new promo
///////////////

			///////////////////
			//If Product exsist
			///////////////////
			$exists_ItemCode = $Products->exists(['item_code' => $dataArray['Item Code']]);
			if($exists_ItemCode){
						$product = $Products->find()->where(['item_code' => $dataArray['Item Code']]); 
						$idByItemCode = $product->first();	
						$this->request->data['product_id'] =  $idByItemCode->id;
					
						$promotion = $this->Promotions->newEntity();
						$promotion = $this->Promotions->patchEntity($promotion, $this->request->data);
						if ($this->Promotions->save($promotion)) {
						$error .= 'Promotion - '. $count.': ' .$dataArray['Promotion Code'] . ' added'; 		
						}else{
						$error .= 'Promotion - '. $count . ': ' .$dataArray['Promotion Code'] . ' not added';		
						}
			}else{
				$error .= 'Promotion - '. $count . ': Item Code -' .$dataArray['Item Code'] . ' not found';
			}	
		}
			
			$error .= '</br>';	
			} 	$Logs = TableRegistry::get('Logs');
				$logArray=array();
				$logArray['errors']=$error;
				$logArray['modified_by']= $this->Auth->User('id');
				$Log = $Logs->get(2);
				$Log = $Logs->patchEntity($Log, $logArray); 
				$Logs->save($Log);	
				return $this->redirect(['controller'=>'Logs', 'action' => 'index']);
		}
		
	}
	
	public function addPromotion(){
     $promotion = $this->Promotions->newEntity();
        if ($this->request->is('post')) {
			
			//debug($this->request->data);
			//die;
			
            $promotion = $this->Promotions->patchEntity($promotion, $this->request->data);
            if ($this->Promotions->save($promotion)) {
                $this->Flash->success(__('The promotion has been saved.'));
				
            } else {
                $this->Flash->error(__('The promotion could not be saved. Please, try again.'));
            }
		return $this->redirect( Router::url( $this->referer(), true ) );	
        }  
    
	}
	

    /**
     * Edit method
     *
     * @param string|null $id Promotion id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $promotion = $this->Promotions->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $promotion = $this->Promotions->patchEntity($promotion, $this->request->data);
            if ($this->Promotions->save($promotion)) {
                $this->Flash->success(__('The promotion has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The promotion could not be saved. Please, try again.'));
            }
        }
        $products = $this->Promotions->Products->find('list', ['limit' => 200]);
        $childProducts = $this->Promotions->ChildProducts->find('list', ['limit' => 200]);
        $this->set(compact('promotion', 'products', 'childProducts'));
        $this->set('_serialize', ['promotion']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Promotion id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $promotion = $this->Promotions->get($id);
        if ($this->Promotions->delete($promotion)) {
            $this->Flash->success(__('The promotion has been deleted.'));
        } else {
            $this->Flash->error(__('The promotion could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
