<nav class="large-3 medium-4 columns mbot15" id="actions-sidebar">
    <ul class="nav nav-pills links-admin">
        <li><?= $this->Form->postLink(__('Delete Customer'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Customer'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Customer'), ['action' => 'add']) ?> </li>
    </ul>
</nav>


   <div class="row">
     <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
  <div class="x_title">
	  <h2>Customer Profile 

	  <?php  if($user->status==1){
		  echo '<small class="label label-success">Active</small>' ;
		}else{
		  echo '<small class="label label-danger">Inactive</small>' ;
		} 
	  
	  ?>
		</h2>
		  <ul class="nav navbar-right panel_toolbox">
				<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
				</li>
					<!--<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
					  <ul class="dropdown-menu" role="menu">
							<li><a href="#">Settings 1</a>
							</li>
							<li><a href="#">Settings 2</a>
						</li>
					  </ul>
					</li>-->
				<li><a class="close-link"><i class="fa fa-close"></i></a>
				</li>
		  </ul>
	  <div class="clearfix"></div>
  </div>

<div class="x_content">
<div class="col-md-3 col-sm-3 col-xs-12 profile_left">

                    <div class="profile_img">

				
					
					
                      <!-- end of image cropping -->
                      <div id="crop-avatar">
                        <!-- Current avatar -->
                        <div class="avatar-view">
						<?php if(@$user->user_detail->image!=''){ ?> 
							<?= $this->Html->image('files/Userdetails/image/'.@$user->user_detail->image, ['alt'=>'User Image']); ?>		
						<?php }else{ ?> 
							<?= $this->Html->image('files/Noimage/no-man.png', ['alt'=>'Avatar']); ?>		
						<?php } ?>
										  
                        </div>
                        <!-- Loading state -->
                       
                      </div>
                      <!-- end of image cropping -->

                    </div>
                    <h3><?= h(ucfirst(@$user->user_detail->firstname)) ?> <?= h(ucfirst(@$user->user_detail->lastname)) ?></h3>

                    <ul class="list-unstyled user_data">
                      <li><i class="fa fa-map-marker user-profile-icon"></i> <?= h(@$user->user_detail->dob) ?>
                      </li>

                      <li>
                        <i class="fa fa-briefcase user-profile-icon"></i> <?= h(@$user->user_detail->mobile) ?>
                      </li>

                      <li class="m-top-xs">
                        <i class="fa fa-external-link user-profile-icon"></i>
                        <?= h($user->email) ?>
                      </li>
					  
					  <li class="m-top-xs">
                        <i class="fa fa-external-link user-profile-icon"></i>
						
				<?php  if(@$user->user_detail->gender=='Male'){
				  echo 'Male' ;	
				}elseif(@$user->user_detail->gender=='Female'){
				  echo 'Female' ;
				} 
			  ?>
		</li>
                    </ul>
					<?= $this->Html->link(__('Edit Customer Details'), ['controller'=>'UserDetails', 'action' => 'edit', $user->user_detail->id], ['class'=>'btn btn-success']) ?>
                   <!-- <a class="btn btn-success"><i class="fa fa-edit m-right-xs"></i>Edit Profile</a>-->
   		
			
					<br />

                    

                  </div>
				  
				  
				  
				  
    <div class="col-md-9 col-sm-9 col-xs-12">

             
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Addresses</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Cart</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Orders</a>
                        </li>
                      </ul>
					  
                      <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">

    <!-- start user addresses -->
	 <?php if (!empty($user->user_addresses)){ ?>
        <table class="data table table-striped no-margin">
			<thead>
                              <tr>
                                <th>#</th>
                                <th><?= __('Street Address') ?></th>
                                <th><?= __('City') ?></th>
                                <th><?= __('State') ?></th>
								 <th><?= __('Country') ?></th>
								 <th><?= __('Pincode') ?></th>
								  <th colspan="2" class="text-center"><?= __('Action') ?></th>
                              </tr>
			</thead>
						
							
		<tbody>				
		<?php foreach ($user->user_addresses as $userAddress): ?>
            <tr>
                <td><?= h($userAddress->id) ?></td>
                <td><?= h($userAddress->street_address) ?></td>
                <td><?= h($userAddress->city) ?></td>
                <td><?= h($userAddress->state) ?></td>
                <td><?= h($userAddress->country) ?></td>
                <td><?= h($userAddress->postalcode) ?></td>
                <td class="actions text-center">
                    <?= $this->Html->link(__('Edit'), ['controller' => 'UserAddresses', 'action' => 'edit', $userAddress->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'UserAddresses', 'action' => 'delete', $userAddress->id], ['confirm' => __('Are you sure you want to delete # {0}?', $userAddress->id)]) ?>
                </td>
            </tr>
		<?php endforeach; ?>
			
		</tbody>
	</table> 
	<?= $this->Html->link(__('Add New Address'), ['controller' => 'UserAddresses', 'action' => 'add',$user->id ], ['class'=>'btn btn-success']) ?>
	 <?php }else {
		 echo 'Address not added!'; 
			 echo '<br>'.$this->Html->link(__('Add New Address'), ['controller' => 'UserAddresses', 'action' => 'add',$user->id ], ['class'=>'btn btn-success']);		 
		} ?>
    <!-- end user Addresses-->

                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">

    <!-- start user Cart -->
			<?php if (!empty($user->cart)){ ?>
                          <table class="data table table-striped no-margin">
                            <thead>
                              <tr>
                               
                                <th>Product Description</th>
                                <th>Quantity</th>
                                <th>Item Price</th>
                                <th>Items Price</th>
                              </tr>
                            </thead>
							
						
                            <tbody>
                             <?php
                                foreach($user->cart->cart_products as $details){ ?>
								 <tr>
                                <td><?= h($details['product']->title) ?></td>
                                <td><?= h($details->prod_quantity) ?></td>
                                <td ><?= h($details['product']->products_price->list_price) ?></td>
                                <td><?= h($details->prod_quantity*$details['product']->products_price->list_price) ?></td>
								</tr>
								<?php 	} ?>
                              
                             
                            </tbody>
						
                          </table>
			<?php }else{
				
				echo "Cart is Empty!!!";
				
			} ?>					  				  
    <!-- end user Orders -->
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                           <!-- start user Orders -->
			<?php if (!empty($user->orders)){ ?>
                          <table class="data table table-striped no-margin">
                            <thead>
                              <tr>
                               
                                <th>Product Description</th>
                                <th>Quantity</th>
                                <th>Item Price</th>
                                <th>Items Price</th>
                              </tr>
                            </thead>
							
						
                            <tbody>
                             <?php
							 foreach($user->orders as $Ordersdetails){
                                foreach($Ordersdetails->orders_products as $details){ ?>
								 <tr>
                                <td><?= h($details['product']->title) ?></td>
                                <td><?= h($details->product_quantity) ?></td>
                                <td ><?= h($details->price) ?></td>
                                <td><?= h($details->product_quantity*$details->price) ?></td>
								</tr>
							 <?php 	} }?>
                              
                             
                            </tbody>
						
                          </table>
			<?php }else{
				
				echo "You have not purchased yet!!!";
				
			} ?>					  				  
    <!-- end user Orders -->
                        </div>
                      </div>
                    </div>
    </div>
    </div>
    </div>
    </div>
    </div>