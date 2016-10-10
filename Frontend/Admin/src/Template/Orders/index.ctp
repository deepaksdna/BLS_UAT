<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
			 <?php  
							echo '<div  id="completedOrdersDiv" class="btn btn-success status" >Completed Orders</div>' ;
							
							echo '<div id="nonCompletedOrdersDiv"  class="btn btn-success status">Non Completed Orders</div>' ;
							 
							?>
                <div class="x_title">
                  <h2><?= __('Orders') ?> </h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>

                <div class="x_content">
				
    <table class="table table-striped responsive-utilities jambo_table bulk_action completed">
	<thead>
                <tr class="headings">
                <th class="column-title"><?= $this->Paginator->sort('user_id') ?></th>
				<th class="column-title">Total Price</th>
				<th class="column-title">Total Price with GST</th>
				<th class="column-title"><?= $this->Paginator->sort('status') ?></th>
                <th class="column-title"><?= $this->Paginator->sort('created') ?></th>
               
				<th colspan="3" class="column-title text-center no-link last"><span class="nobr">Action</span>
                        </th>
                        <th class="bulk-actions" colspan="8">
                          <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                        </th>
            </tr>
        </thead>
        <tbody>
            <?php 
			foreach ($orders as $order):  
			
			foreach($order->orders_products as $order_pro):
			$price_sum[] = $order_pro->final_price ;
			$price_gst_rate = $order_pro->gst_rate ;
			endforeach;
	
			$price_total = array_sum($price_sum);
			$price_total_gst = $price_total + $price_total*$price_gst_rate/100 ;
			
			
			$order_stat=array();
			foreach ($order->order_update_statuses as $key => $order_status):
			$order_stat[$key]['name'] = $order_status->order_status->name ;
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
					 $status ="completed";
				  } else {
					  $status ="noncompleted" ;  
				  }
								
			?>
			
           <tr class="even pointer status_<?php echo $status;?>">
			<td><?= $order->has('user') ? $this->Html->link(ucfirst($order->user->user_detail->firstname)." ".ucfirst($order->user->user_detail->lastname), ['controller' => 'Users', 'action' => 'view', $order->user->id]) : '' ?></td>
			<td><?= h( $price_total) ?></td>
			<td><?= h( $price_total_gst) ?></td>
			<td><?= h( ucfirst($order_stat[0]['name'])) ?></td>					
            <td><?= h($order->created) ?></td>
            
				<td class="last"><?= $this->Html->link(__('View'), ['action' => 'view', $order->id]) ?></td>
				<td class="last"><?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $order->id], ['confirm' => __('Are you sure you want to delete # {0}?', $order->id)]) ?></td>
                
            </tr>
			  <?php  unset($price_sum); unset($order_status_new1); 

		endforeach; ?>
        </tbody>
		       
    </table>

    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
              </div>
            </div>
</div>

<script>
$(document).ready(function(){
	$('#completedOrdersDiv').on('click',function(){ 
		$(".status_completed").show(); $(".status_noncompleted").hide();
		$(".status_completed").hide(); $(".status_completed").show();
	});
	
	$('#nonCompletedOrdersDiv').on('click',function(){  
		$(".status_completed").hide(); $(".status_noncompleted").show();
		$(".status_completed").hide(); $(".status_completed").hide();
        
	});
});



</script>
