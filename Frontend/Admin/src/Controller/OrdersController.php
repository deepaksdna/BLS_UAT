<?php
namespace App\Controller;
use Cake\I18n\Time;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Fpdf\Fpdf;

/**
 * Orders Controller
 *
 * @property \App\Model\Table\OrdersTable $Orders
 */
class OrdersController extends AppController
{
	
	public $helpers = ['Form', 'Html'];
	public function initialize()
		{
			parent::initialize();
			
			// Set the layout
			$this->viewBuilder()->layout('admin');
			$this->loadComponent('RequestHandler');
  
		}

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {   
		 $this->paginate = [
		'contain' => ['Users.UserAddresses', 'Users.UserDetails', 'OrdersProducts','OrderUpdateStatuses.OrderStatuses'], 
		]; 
        $orders = $this->paginate($this->Orders);

        $this->set(compact('orders'));
        $this->set('_serialize', ['orders']);
    }


    /**
     * View method
     *
     * @param string|null $id Order id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
		$order_status = TableRegistry::get('OrderStatuses');
		
		
		$user = $this->Auth->user('id');
		//debug($user);die;
        $order = $this->Orders->get($id, [
            'contain' => ['Users','Users.UserDetails', 'OrdersProducts.Products','OrdersProducts.OrderProductPromotions', 'OrderUpdateStatuses', 'OrdersShippings','OrdersBillings','OrderUpdateStatuses.OrderStatuses','OrderUpdateStatuses.Admins']
        ]);
		
		
		 if ($this->request->is(['patch', 'post', 'put'])) {
			 $order_update_status = TableRegistry::get('OrderUpdateStatuses');
			  $order_entity = $order_update_status->newEntity();
		
			  $this->request->data['order_id']=$id;
			   $this->request->data['admin_id'] = $user;
			   $status =  $this->request->data;
			
            $orderUpdateStatus = $order_update_status->patchEntity($order_entity, $status);
		
            if ($order_update_status->save($orderUpdateStatus)) {
                $this->Flash->success(__('The order update status has been saved.'));
                return $this->redirect(['action' => 'view',$id]);
            } else {
                $this->Flash->error(__('The order update status could not be saved. Please, try again.'));
            }
        }
		$orderStatuses = $order_status->find('list', ['limit' => 200]);
		//debug($orderStatuses);
		$this->set('orderStatuses', $orderStatuses);
        $this->set('order', $order);
        $this->set('_serialize', ['order']);
    }

   
    /**
     * Delete method
     *
     * @param string|null $id Order id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $order = $this->Orders->get($id);
        if ($this->Orders->delete($order)) {
            $this->Flash->success(__('The order has been deleted.'));
        } else {
            $this->Flash->error(__('The order could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
	
	public function invoice($id = NULL){
		
		$order = $this->Orders->get($id, [
            'contain' => ['Users.UserAddresses','Users.UserDetails', 'OrdersProducts.Products','OrdersProducts.OrderProductPromotions', 'OrderUpdateStatuses', 'OrdersShippings','OrderUpdateStatuses.OrderStatuses','OrderUpdateStatuses.Admins']
        ]);
		
		
		
		  
			
		
		
		

			require_once(ROOT .DS. "vendor" . DS  . "fpdf" . DS . "Fpdf.php");		
			$pdf = new FPDF;
			$html = '';	
			$html .= '<table align="center" border="0">
			
			<tr>
			<td align="center"  width="700" height="60"><b>Boon Lay Stationary</b></td>
			</tr>
			
			
			<tr>
			<td align="center"  width="700" height="60"><b>Order Details</b></td>
			</tr>

			<tr>
			
			<tr>
				<td width="100" height="30">Transaction No.</td>
				<td width="100" height="30" bgcolor="#D0D0FF">'.$order['transactionCode'].'</td>
			</tr>
			
			
			<tr>
				<td width="100" height="30">Refrence No.</td>
				<td width="100" height="30" bgcolor="#D0D0FF">'.$order['refrenceCode'].'</td>
			</tr>
			
			
			<tr>
				<td width="100" height="30">Invoice No. </td>
				<td width="100" height="30" bgcolor="#D0D0FF">'.$order['invoiceCode'].'</td>
			</tr>
			
			<tr>
				<td width="100" height="30">Order Time</td>
				<td width="100" height="30" bgcolor="#D0D0FF">'.$order['created'].'</td>
			</tr>
			
			<tr>
				<td width="100" height="30">User Comments</td>
				<td width="100" height="30" bgcolor="#D0D0FF">'.$order['user_comments'].'</td>
			</tr>
			
			</table>
			
			<br>
			<br>
			
			<table align="center"   border="0">
			<tr>
			<td align="center"  width="700" height="60"><b>User Details</b></td>
			</tr>
			
			<tr>
				<td  width="200" height="30">User Name</td>
				<td width="200" height="30" bgcolor="#D0D0FF">'.ucfirst($order['user']['user_detail']['firstname']).' '.ucfirst($order['user']['user_detail']['lastname']).'</td>
			</tr>
			<tr>
				<td width="200" height="30">Email</td>
				<td width="200" height="30" bgcolor="#D0D0FF">'.$order['user']['email'].'</td>
			</tr>
			</table>

			<br>
			<br>';
			
			$html.= '<table  align="center"  border="1">
            <tr>
				<td align="center" width="85">Item Code</td>
				<td align="center" width="85">Quantity</td>
				<td align="center" width="85">Product Price</td>
				<td  align="center" width="85">Promotion Applied</td>
				<td  align="center" width="85">Promotion Code</td>
				<td align="center" width="85">Promotion Discounted Price</td>	
				<td align="center" width="85">GST rate</td>
				<td align="center" width="85">Final Price</td>
			</tr>';
		
		
		foreach($order->orders_products as $products){ 
         
		$html .= '<tr>
		   
		   <td width="85">'.$products->product->item_code.'</td>
		   <td width="85">'.$products->product_quantity.'</td>
		   <td width="85">'.$products->price.'</td>
		   
		   <td width="85">'; 
				
					if($products->promotion_applied == "Y"){
					$html .= "Yes";
					}elseif($products->promotion_applied == "N"){
					$html .= "No";	
					}
			
		 $html .= '</td>
				
			<td width="85">
				'.$products->order_product_promotion->promo_code.'</td>
			
			<td width="85">';
				
				if($products->promotion_applied == "Y"){ 
				 $html .= $products->discounted_price; 
				}
				
		  $html .=	'</td>
				
				<td width="85">'.$products->gst_rate.'</td>
				<td width="85">'.$products->final_price.'</td>
				</tr>';
			 }
			
			$html .= '</table>'; 


			$pdf->SetFont('Arial','',8);
			$pdf->AddPage();
			$pdf->WriteHTML($html);

			$pdf->Output();
			die;
	}
	
	
	
	

	
	
		
}
