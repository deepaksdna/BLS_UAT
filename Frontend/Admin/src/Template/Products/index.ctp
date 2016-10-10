<nav class="large-3 medium-4 columns mbot15" id="actions-sidebar">
    <ul class="nav nav-pills links-admin"> 
        <li><?= $this->Html->link(__('New Product'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">

	<div class="x_panel">
                <div class="x_title">
                  <h2><?= __('Products') ?> </h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>

                <div class="x_content">

   
  <!-- <table class="table table-striped responsive-utilities jambo_table bulk_action">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('title') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $this->Number->format($product->id) ?></td>
                <td><?= h($product->title) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $product->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $product->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $product->id], ['confirm' => __('Are you sure you want to delete # {0}?', $product->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	-->
	
	<table class="table table-striped responsive-utilities jambo_table bulk_action">
	  
                    <thead>
                      <tr class="headings">
                        <!--<th>
                          <input type="checkbox" id="check-all" class="flat">
                        </th>-->
                        <th class="column-title"><?= $this->Paginator->sort('item_code','Item Code') ?></th>
						<th class="column-title"><?= $this->Paginator->sort('product_desc','Product Description') ?></th>
						<th class="column-title"><?= $this->Paginator->sort('ParentCategories.name','Category') ?></th>
						 <th class="column-title"><?= $this->Paginator->sort('Categories.name','Sub Category') ?></th>
						 <th class="column-title"><?= $this->Paginator->sort('Brands.name', 'Brand') ?></th>
						 <th class="column-title"><?= $this->Paginator->sort('status', 'Unavailable Flag') ?></th>
                    
                        <th colspan="3" class="column-title text-center no-link last"><span class="nobr">Action</span>
                        </th>
                        <th class="bulk-actions" colspan="8">
                          <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                        </th>
                      </tr>
                    </thead>

                    <tbody>
					
	
					
					 <?php foreach ($products as $product): 
					 ?>		
                      <tr class="even pointer">
                        <td class=" "><?= h($product->item_code) ?></td>
						<td class=" "><?= h($product->title) ?></td>
						
						<?php if(!empty($product->products_category->category->parent_category->name)){
							$cat=$product->products_category->category->parent_category->name;
						}else{
							$cat='Category not assigned';
						}?>
						<td class=" "><?= h($cat) ?></td>
						
						
						<?php if(!empty($product->products_category->category->name)){
							$subcat=$product->products_category->category->name;
						}else{
							$subcat='Sub-Category not assigned';
						}?>
						<td class=" "><?= h($subcat) ?></td>
						<td class=" "><?= h($product->products_attr->brand->name) ?></td>
						 <td class=" ">
							<?php  if($product->status=='Y'){
							echo '<div id="status'.$product->id.'" onclick="changeStatus('.$product->id.');" status="'.$product->status.'" class="btn btn-danger">Unavailable</div>' ;
							}else{
							echo '<div id="status'.$product->id.'" onclick="changeStatus('.$product->id.');" status="'.$product->status.'" class="btn btn-success">Available</div>' ;
							} 
							?>
						</td>

						<td class="last"><?= $this->Html->link(__(''), ['action' => 'view', $product->id], ['class'=>'fa fa-desktop']) ?></td>
						<td class="last">  <?= $this->Html->link(__(''), ['action' => 'edit', $product->id], ['class'=>'fa fa-pencil']) ?></td>
						<!--<td class="last"><?= $this->Form->postLink(__(''), ['action' => 'delete', $product->id], ['class'=>'fa fa-trash','confirm' => __('Are you sure you want to delete # {0}?', $product->id)]) ?></td>-->
                      </tr>
					  <?php endforeach; ?>
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
function changeStatus(id){
	var current_status = $('#status'+id).attr("status");

	//alert("<?php echo $this->Url->build(["controller" => "Users","action" => "ChangeStatus"]);?>");
	 $.ajax({
                type:"POST",
				url:"<?php echo $this->Url->build(["controller" => "Products","action" => "changestatus"]);?>",
				data: { id: id }, 
                dataType: 'text',
                async:false,
                success: function(data){ 
					if(current_status =='N'){
						$('#status'+id).removeClass("btn-success");
						$('#status'+id).addClass("btn-danger");
						$('#status'+id).text("Unavailable");
						$('#status'+id).attr("status","Y");
					}else if(current_status =='Y'){
						$('#status'+id).removeClass("btn-danger");
						$('#status'+id).addClass("btn-success");
						$('#status'+id).text("Available");
						$('#status'+id).attr("status", "N");
					}
				
                },
                
            });
	
}
</script>
