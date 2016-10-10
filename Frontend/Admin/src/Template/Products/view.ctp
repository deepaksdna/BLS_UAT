<nav class="large-3 medium-4 columns mbot15" id="actions-sidebar">
    <ul class="nav nav-pills links-admin">
     <li><?= $this->Html->link(__('Edit Product'), ['action' => 'edit', $product->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Product'), ['action' => 'delete', $product->id], ['confirm' => __('Are you sure you want to delete # {0}?', $product->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Products'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Product'), ['action' => 'add']) ?> </li>
    </ul>
</nav>


<div class="row">
     <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
  <div class="x_title">
	  <h2><?php  echo $product->item_code; ?></h2> 
	  <div style="margin:5px 0px 0px 87px;">
		<?php  if($product->status=='N'){
		  echo '<small style="padding:7px 7px 7px 6px;" class="label label-success ">Available</small>' ;
		}elseif($product->status=='Y'){
		  echo '<small style="padding:7px 7px 7px 6px;" class="label label-danger ">Unavailable</small>' ;
		} 
	  ?>
	  </div>	
		  <ul class="nav navbar-right panel_toolbox">
				<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
				</li>
				<li><a class="close-link"><i class="fa fa-close"></i></a>
				</li>
				
		  </ul>
	  <div class="clearfix"></div>
  </div>

<div class="x_content">

<div class="col-md-12 col-sm-12 col-xs-12">

          <div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Basics</a>
                        </li>												
                        <li role="presentation" class=""><a href="#tab_content4" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Images $ Colors</a>
                        </li>
						 </li>
                        <li role="presentation" class=""><a href="#tab_content5" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Related & Main Promos</a>
                        </li>
                      </ul>
					  
		<div id="myTabContent" class="tab-content">
					<div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">

					<!-- start Product Basics -->		
						<h4><?= __('Product Basics') ?></h4>
					
					<blockquote>
                      <p>
					  <h3><?= h($product->title) ?></h3> 
					 <h4> <?= h($product->product_desc) ?><h4></p>
                    </blockquote>

					<table class="table table-bordered table-striped table-hover">
														
														<tr>
															<th><?= ucfirst('model') ?></th>
															<td><?= h($product->products_attr->model) ?></td>	
														</tr>
														<tr>
															<th><?= ucfirst('video link') ?></th>
															<td><?= h($product->products_attr->video_link) ?></td>	
														</tr>
														<tr>
															<th><?= ucfirst('size') ?></th>
															<td><?= h($product->products_attr->size) ?></td>	
														</tr>
														<tr>
															<th><?= ucfirst('weight') ?></th>
															<td><?= h($product->products_attr->weight) ?></td>	
														</tr>
														<tr>
															<th><?= ucfirst('packaging') ?></th>
															<td><?= h($product->products_attr->packaging) ?></td>	
														</tr>
														<tr>
															<th><?= ucfirst('uom') ?></th>
															<td><?= h($product->products_attr->uom) ?></td>	
														</tr>
														<tr>
															<th><?= ucfirst('quantity') ?></th>
															<td><?= h($product->products_attr->quantity) ?></td>	
														</tr>
														<tr>
															<th><?= ucfirst('Price') ?></th>
															<td><?= h($this->Number->currency($product->products_price->list_price, 'SGD')) ?></td>	
														
														</tr>
														
														<tr>
															<th><?= ucfirst('Sub - Category') ?></th>
															<td>
																<?php if (!empty($product->categories)): ?>
																		<?php foreach ($product->categories as $categories): ?>
																				<?= h($categories->name) ?>
																		<?php endforeach; ?>
																<?php endif; ?>
															</td>	
														
														</tr>
														
														<tr>
															<th><?= ucfirst('Brand') ?></th>
															<td>
																<?php if (!empty($product->products_attr->brand)): ?>

																		<?= $this->Html->image('files/Brands/image/'.$product->products_attr->brand->image, ['height' => '75px']); ?>
																		<?= h($product->products_attr->brand->name) ?>
																<?php endif; ?>	
															
															
															
															
															</td>	
														
														</tr>
														
														
														
													</table>		
					
					
					<!-- end Product Basics-->
					</div>
					
					
							
					
					
					
	<div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tab">
			
			
			   <div class="col-xs-12"><div class="row">
					     <div class="profile_title lead"> <div class="col-xs-12"><h4>Marketing Images</h4></div></div>
						  
						  
									<?php if (!empty($product->products_marketing_images)): ?>     
									<?php foreach ($product->products_marketing_images as $productsMarketingImages): ?>         
									<div class="col-xs-2">
						     <div class="box-thumb">
									<div class="thumb-product"><?= $this->Html->image('files/productsMarketingImages/image/'.$productsMarketingImages->image, ['class'=>'img-responsive']); ?>	</div>	
							 </div>
						  </div>				
									<?php endforeach; ?>
									<?php endif; ?>
							
			   </div></div>
			   <div class="clearfix"></div><hr>
			  <div class="col-xs-12">
			  
				<div class="row">
					     <div class="profile_title lead"> <div class="col-xs-12"><h4>Colored Images</h4></div></div>
						  
						 
						   
							    <?php if (!empty($product->products_images)): ?>
												<?php foreach ($product->products_images as $productsImages): ?>
					<div class="col-xs-2 ">
							 <div class="box-thumb">					
												
							<div class="thumb-product">
							<small><?= $productsImages->product_code; ?></small>	
							<?= $this->Html->image('files/ProductsImages/image/'.$productsImages->image, ['class'=>'img-responsive']); ?>
							</div>
							<div class="clearfix"></div>		
							<div class="col-xs-12 text-right action">
							<?= $this->Html->image('files/Colors/image/'.$productsImages->color->image, ['alt'=>'Color','height' => 20,'width' => 20]); ?>
								</div>
							<div class="clearfix"></div>			
																	
																					
					</div>			
							</div>							
												<?php endforeach; ?>
												<?php endif; ?>	
													
						
						  </div>
			   </div>
	<!-- end Images $ Colors -->
					</div>
				
				
					<div role="tabpanel" class="tab-pane fade" id="tab_content5" aria-labelledby="profile-tab">
								<!-- start Related Products -->
									<div class="col-xs-12 col-sm-5 col-md-4">
                                        <h4>Related Products</h4>
									</div> 
									<div class="col-xs-12 col-sm-5 col-md-4">
                                        <h4>Main Promos Pages</h4>
									</div> 
								    <div class="clearfix"></div>
										<hr>
									<div class="col-xs-12 col-sm-5 col-md-4">
									
										<?php if (!empty($product->products_related->relatedproduct1)): ?>
												<p><?= h($product->products_related->relatedproduct1->item_code) ?>	</p>
										<?php endif; ?>	
										<?php if (!empty($product->products_related->relatedproduct2)): ?>
											<p>	<?= h($product->products_related->relatedproduct2->item_code) ?></p>
										<?php endif; ?>
										<?php if (!empty($product->products_related->relatedproduct3)): ?>
											<p>	<?= h($product->products_related->relatedproduct3->item_code) ?></p>
										<?php endif; ?>
															
									</div> 
									<div class="col-xs-12 col-sm-5 col-md-4">
									
											<?php if($product->products_attr->main_promo_1 !== '0'): ?>
													<p><?= h($product->products_attr->main_promo_1) ?></p>
											<?php endif; ?>	
											<?php if($product->products_attr->main_promo_2 !== '0'): ?>
													<p><?= h($product->products_attr->main_promo_2) ?></p>
											<?php endif; ?>	
											<?php if($product->products_attr->main_promo_3 !== '0'): ?>
													<p><?= h($product->products_attr->main_promo_3) ?></p>
											<?php endif; ?>	
											
															
									</div> 						
								
					</div>
			
	
	
	
    </div>
    </div>
    
	
	
	
				</div>
			</div>
		</div>
	</div>
</div>
