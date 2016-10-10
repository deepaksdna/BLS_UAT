<nav class="large-3 medium-4 columns mbot15" id="actions-sidebar">
    <ul class="nav nav-pills links-admin">
        <li><?= $this->Html->link(__('Edit Brand'), ['action' => 'edit', $brand->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Brand'), ['action' => 'delete', $brand->id], ['confirm' => __('Are you sure you want to delete # {0}?', $brand->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Brands'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Brand'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2><?= h($brand->name) ?>  </h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div><div class="x_content">
                  <br />
				 <div class="col-sm-6">
					<table class="table table-striped responsive-utilities jambo_table bulk_action">
		<tr>
            <th class="column-title"><?= __('Name') ?></th>
            <td><?= h($brand->name) ?></td>
        </tr>
        <tr>
            <th class="column-title"><?= __('Image') ?></th>
            <td><?= $this->Html->image('files/Brands/image/'.$brand->image, ['alt'=>'Logo', 'class'=>'img-thumbnail', 'width'=>'100px']); ?></td>
        </tr>
        
		 <tr>
            <th class="column-title"><?= __('Template') ?></th>
            <td><?= h($brand->templates) ?></td>
        </tr>
		
        <tr>
            <th class="column-title"><?= __('Status') ?></th>
            <td><?= h($brand->status) ?></td>
        </tr>
        <tr>
            <th class="column-title"><?= __('Id') ?></th>
            <td><?= $this->Number->format($brand->id) ?></td>
        </tr>

		
        <tr>
            <th class="column-title"><?= __('Created') ?></th>
            <td><?= h($brand->created) ?></td>
        </tr>
        <tr>
            <th class="column-title"><?= __('Modified') ?></th>
            <td><?= h($brand->modified) ?></td>
        </tr>
    </table>
	</div>
 
   
</div>
</div>
</div>
</div>
