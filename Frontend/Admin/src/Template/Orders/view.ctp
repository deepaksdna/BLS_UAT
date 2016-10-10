

<nav class="large-3 medium-4 columns mbot15" id="actions-sidebar">
    <ul class="nav nav-pills links-admin">
        <li><?= $this->Form->postLink(__('Delete Order'), ['action' => 'delete', $order->id], ['confirm' => __('Are you sure you want to delete # {0}?', $order->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Orders'), ['action' => 'index']) ?> </li>
		
	<li><?= $this->Html->link(__('Download Invoice'), ['action' => 'invoice', $order->id]); ?></li>
    </ul>
</nav>

  <div class="row">
     <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
  <div class="x_title">
	  <h2>Order Details</h2>
		  <ul class="nav navbar-right panel_toolbox">
				<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
				</li>
					
				<li><a class="close-link"><i class="fa fa-close"></i></a>
				</li>
		  </ul>
	  <div class="clearfix"></div>
  </div>

<div class="x_content">
 <br /><?php 
 

 
			$order_stat=array();
			foreach ($order->order_update_statuses as $key => $order_status):
			$order_stat[$key]['id'] = $order_status->order_status->id ;
			$order_stat[$key]['created'] = $order_status->created->i18nFormat('yyyy-MM-dd HH:mm:ss') ;
			endforeach;
	

			$sta = array();
			foreach ($order_stat as $key => $row)
			{
			$sta[$key] = $row['created'];
			}
			array_multisort($sta, SORT_DESC, $order_stat);
			
			if($order_stat[0]['id']==4){
			$update='0';	
			}else{
				$update='1';
			}

	
			
 ?>
 
				 	 <div class="col-sm-12">
					  <!--<h3><?= h($order->id) ?></h3>-->
					<table class="table table-striped responsive-utilities jambo_table bulk_action">
					 <!--<tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($order->id) ?></td>
        </tr>-->
		<tr>
            <th class="column-title"><?= __('User') ?></th>
            <td><?= $order->has('user') ? $this->Html->link(ucfirst($order->user->user_detail->firstname)." ".ucfirst($order->user->user_detail->lastname), ['controller' => 'Users', 'action' => 'view', $order->user->id]) : '' ?></td>
        </tr>
       
        
        <tr>
            <th class="column-title"><?= __('User Comments') ?></th>
            <td><?= h($order->user_comments) ?></td>
        </tr>
       <!-- <tr>
            <th class="column-title"><?= __('Admin Comments') ?></th>
            <td><?= h($order->admin_comments) ?></td>
        </tr>-->
		
		<tr>
            <th class="column-title"><?= __('TransactionCode') ?></th>
            <td>
				<?= $this->Number->format($order->transactionCode) ?>
		</td>
        </tr>
		
        <tr>
            <th class="column-title"><?=  __('RefrenceCode') ?></th>
            <td><?= $this->Number->format($order->refrenceCode) ?></td>
        </tr>
        <tr>
            <th class="column-title"><?=__('InvoiceCode') ?></th>
            <td><?= $this->Number->format($order->invoiceCode) ?></td>
        </tr>
		<tr>
            <th class="column-title"><?=__('OtherCode') ?></th>
            <td><?= $this->Number->format($order->otherCode) ?></td>
        </tr>
		<tr>
            <th class="column-title"><?= __('Created') ?></th>
            <td><?= h($order->created) ?></td>
        </tr>
		
		<tr>
            <th class="column-title"><?= __('Update Order Status') ?></th>
			<?= $this->Form->create("order_update_status") ?>
            <td>
			<?php 
			if($update==0){		 
				 echo "Completed";
			}else{
				echo $this->Form->input('order_status_id', ['options' => $orderStatuses, 'default' => $order_stat[0]['id'] , 'required'=> true, 'label' => false, 'class'=>'form-control col-md-4 col-xs-12']); ?><?= $this->Form->button(__('Submit'), ['class'=>'btn btn-success']); 
			}
			?>
			
			
			</td>
			
			<?= $this->Form->end() ?>
        </tr>
    </table>
	</div>
	
	<div class="col-sm-12">
    
        <h2><?= __('Update Statuses') ?></h2>
		<hr>
        <?php if (!empty($order->order_update_statuses)): ?>
        <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <tr>

                <th><?= __('Order Status Id') ?></th>
                <th><?= __('Order Processer') ?></th>
                <th><?= __('Created') ?></th>
              
            </tr>
            <?php foreach ($order->order_update_statuses as $orderUpdateStatuses): 
			?>
            <tr>

                <td><?= h($orderUpdateStatuses->order_status->name) ?></td>
                <td><?= h($orderUpdateStatuses->admin->firstname." ".$orderUpdateStatuses->admin->lastname) ?></td>
                <td><?= h($orderUpdateStatuses->created) ?></td>
            
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="col-sm-12">
        <h2><?= __('Delivery Address') ?></h2>
		<hr>
        <?php if (!empty($order->orders_shippings)): ?>
        <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <tr>
                
                <th><?= __('Firstname') ?></th>
                <th><?= __('Lastname') ?></th>
                <th><?= __('Email') ?></th>
                <th><?= __('Street Address') ?></th>
                <th><?= __('City') ?></th>
                <th><?= __('State') ?></th>
                <th><?= __('Country') ?></th>
                <th><?= __('Postal code') ?></th>
                <th><?= __('Telephone') ?></th>
                <th><?= __('Fax No') ?></th>
                <th><?= __('Created') ?></th>
               
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($order->orders_shippings as $ordersShippings): ?>
            <tr>
              
                <td><?= h($order->user->user_detail->firstname) ?></td>
                <td><?= h($order->user->user_detail->lastname) ?></td>
                <td><?= h($order->user->email) ?></td>
                <td><?= h($ordersShippings->street_address) ?></td>
                <td><?= h($ordersShippings->city) ?></td>
                <td><?= h($ordersShippings->state) ?></td>
                <td><?= h($ordersShippings->country) ?></td>
                <td><?= h($ordersShippings->postalcode) ?></td>
                <td><?= h($ordersShippings->telephone) ?></td>
                <td><?= h($ordersShippings->fax_no) ?></td>
                <td><?= h($ordersShippings->created) ?></td>
         
                <td class="actions">
                    <?= $this->Html->link(__('Edit'), ['controller' => 'OrdersShippings', 'action' => 'edit', $ordersShippings->id]) ?>
                   
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
	
	<div class="col-sm-12">
        <h2><?= __('Billing Address') ?></h2>
		<hr>
        <?php if (!empty($order->orders_shippings)): ?>
        <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <tr>
                
               <th><?= __('Firstname') ?></th>
                <th><?= __('Lastname') ?></th>
                <th><?= __('Email') ?></th>
                <th><?= __('Street Address') ?></th>
                <th><?= __('City') ?></th>
                <th><?= __('State') ?></th>
                <th><?= __('Country') ?></th>
                <th><?= __('Postal code') ?></th>
                <th><?= __('Telephone') ?></th>
                <th><?= __('Fax No') ?></th>
                <th><?= __('Created') ?></th>
               
              
            </tr>
            <?php foreach ($order->orders_billings as $OrdersBillings): ?>
            <tr>
              
                <td><?= h($order->user->user_detail->firstname) ?></td>
                <td><?= h($order->user->user_detail->lastname) ?></td>
                <td><?= h($order->user->email) ?></td>
                <td><?= h($OrdersBillings->street_address) ?></td>
                <td><?= h($OrdersBillings->city) ?></td>
                <td><?= h($OrdersBillings->state) ?></td>
                <td><?= h($OrdersBillings->country) ?></td>
                <td><?= h($OrdersBillings->postalcode) ?></td>
                <td><?= h($OrdersBillings->telephone) ?></td>
                <td><?= h($OrdersBillings->fax_no) ?></td>
                <td><?= h($OrdersBillings->created) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
	
	
	
	
    <div class="col-sm-12">
        <h2><?= __('Products') ?></h2>
		<hr>
        <?php if (!empty($order->orders_products)): 

		?>
        <table class="table table-striped responsive-utilities jambo_table bulk_action">
            <tr>
               
                <th><?= __('Item Code') ?></th>
				<th><?= __('Quantity') ?></th>
				<th><?= __('Product Price') ?></th>
				<th><?= __('Promotion Applied') ?></th>
				<th><?= __('Promotion Code') ?></th>
				<th><?= __('Promotion Discounted Price') ?></th>		
				<th><?= __('GST rate') ?></th>
				<th><?= __('Final Price') ?></th>
            
				<th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($order->orders_products as $products): 
			?>
            <tr>
                
				<td><?= h($products->product->item_code) ?></td>
				<td><?= h($products->product_quantity) ?></td>
				<td><?= h($products->price) ?></td>
				<td><?php 
				
				if($products->promotion_applied == 'Y'){
				 echo "Yes";
				}elseif($products->promotion_applied == 'N'){
					 echo "No";	
				}
				?></td>
				
				
				<td>
				<?php if($products->promotion_applied == "Y"){ ?>
				<!--<?= $this->Html->link(__(h($products->order_product_promotion->promo_code)), ['controller' => 'OrderProductPromotion', 'action' => 'view', $products->order_product_promotion->id]) ?>-->
				
			
				<p class='btn btn-success' type="button" onclick="callModal('<?php echo $products->order_product_promotion->id; ?>')" id="btnShow-<?php echo $products->order_product_promotion->id; ?>" ><?php echo $products->order_product_promotion->promo_code; ?></p>
<div class="dialog-div" id="dialog-<?php echo $products->order_product_promotion->id; ?>" style="display: none" align = "center">
    
	<table class="table table-striped responsive-utilities jambo_table bulk_action">
		<tr>
			<td>Display message : </td><td><?php echo $products->order_product_promotion-> 	formal_message_display; ?></td>
		</tr>
		<tr>
			<td>Dis. Amount Parent: </td><td><?php echo $products->order_product_promotion->discounted_amount_parent; ?></td>
		</tr>
		<tr>
			<td>Dis. Parent IN : </td><td><?php echo $products->order_product_promotion->discount_parent_in; ?></td>
		</tr>
		<tr>
			<td>Start Date : </td><td><?php echo $products->order_product_promotion->start_date; ?></td>
		</tr>
		<tr>
			<td>End Date : </td><td><?php echo $products->order_product_promotion->end_date; ?></td>
		</tr>
		
		<tr>
			<td>Associated Product : </td><td><?php echo $products->order_product_promotion->associated_product; ?></td>
		</tr>
		<tr>
			<td>Child Product Id : </td><td><?php echo $products->order_product_promotion->child_product_id; ?></td>
		</tr>
		<tr>
			<td>Dis. Amount Child : </td><td><?php echo $products->order_product_promotion->discounted_amount_child; ?></td>
		</tr>
		<tr>
			<td>Quantity Child : </td><td><?php echo $products->order_product_promotion->quantity_child; ?></td>
		</tr>
		<tr>
			<td>Dis. Child IN : </td><td><?php echo $products->order_product_promotion->discount_child_in; ?></td>
		</tr>
		<tr>
			<td>Dis. Type : </td><td><?php echo $products->order_product_promotion->discount_type; ?></td>
		</tr>
	</table>
	
	
</div>
				 
				
				
				<?php } ?>
				</td>
				
				<td><?php if($products->promotion_applied == "Y"){ ?>
				<?= h($products->discounted_price) ?>
				<?php } ?></td>
				
				<td><?= h($products->gst_rate) ?></td>
				<td><?= h($products->final_price) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Products', 'action' => 'view', $products->product_id]) ?>
                    <!--<?= $this->Html->link(__('Edit'), ['controller' => 'OrdersProducts', 'action' => 'edit', $products->id]) ?>-->
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'OrdersProducts', 'action' => 'delete', $products->id], ['confirm' => __('Are you sure you want to delete # {0}?', $products->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
</div>
</div>
</div>
<?php //debug($order)?>

<?= $this->Html->script('admin/1.7.2/jquery.min.js');?>
<?= $this->Html->script('admin/1.8.9/jquery-ui.js');?> 
<?= $this->Html->css('admin/1.8.9/jquery-ui.css') ?>	
	
<script type="text/javascript">
    $(function () {
		$(".dialog-div").each(function(i,val){ var id = $(this).attr("id");
		var id_arr = id.split("-"); 
        $("#dialog-"+id_arr[1]).dialog({
            modal: true,
            autoOpen: false,
            title: "Promotion Details",
            width: 500,
            height: 500
        });  });
        
    });
	function callModal(id){ 
		$('#dialog-'+id).dialog('open');
		
	}
</script>

