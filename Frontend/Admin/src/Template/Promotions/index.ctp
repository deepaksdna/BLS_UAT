<nav class="large-3 medium-4 columns mbot15" id="actions-sidebar">
    <ul class="nav nav-pills links-admin">
        <li><?= $this->Html->link(__('New Promotion'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Products'), ['controller' => 'Products', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Product'), ['controller' => 'Products', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2><?= __('Promotions') ?> </h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>

                <div class="x_content">

             

                  <table class="table table-striped responsive-utilities jambo_table bulk_action">
                    <thead>
                      <tr class="headings">
    
                <th class="column-title"><?= $this->Paginator->sort('id') ?></th>
                <th class="column-title"><?= $this->Paginator->sort('promo_code') ?></th>
                <th class="column-title"><?= $this->Paginator->sort('formal_message_display') ?></th>
                <th class="column-title"><?= $this->Paginator->sort('start_date') ?></th>
                <th class="column-title"><?= $this->Paginator->sort('end_date') ?></th>
                <!--<th class="column-title"><?= $this->Paginator->sort('associated_product') ?></th>-->
                <!--<th class="column-title"><?= $this->Paginator->sort('child_product_id') ?></th>-->
                <!--<th class="column-title"><?= $this->Paginator->sort('discounted_amount_child') ?></th>-->
               <!-- <th class="column-title"><?= $this->Paginator->sort('quantity_child') ?></th>-->
               <!-- <th class="column-title"><?= $this->Paginator->sort('discount_parent_in') ?></th>-->
                <!--<th class="column-title"><?= $this->Paginator->sort('discount_child_in') ?></th>-->
                <!--<th class="column-title"><?= $this->Paginator->sort('created') ?></th>-->
                <!--<th class="column-title"><?= $this->Paginator->sort('modified') ?></th>-->
                <th colspan="3" class="column-title text-center no-link last"><span class="nobr">Action</span>
                        </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($promotions as $promotion): ?>
            <tr>
                <td><?= $this->Number->format($promotion->id) ?></td>
                <td><?= h($promotion->promo_code) ?></td>
                 <td><?= h($promotion->formal_message_display) ?></td>
                <!--<td><?= h($promotion->discounted_amount_parent) ?></td>-->
                <!--<td><td><?= $this->Number->format($promotion->quantity_parent) ?></td>-->
                <td><?= h($promotion->start_date) ?></td>
                <td><?= h($promotion->end_date) ?></td>
                <!--<td><td><?= h($promotion->associated_product) ?></td>-->
                <!--<td><td><?= $this->Number->format($promotion->child_product_id) ?></td>-->
                <!--<td><td><?= h($promotion->discounted_amount_child) ?></td>-->
                <!--<td><td><?= $this->Number->format($promotion->quantity_child) ?></td>-->
                <!--<td><td><?= h($promotion->discount_parent_in) ?></td>-->
                <!--<td><td><?= h($promotion->discount_child_in) ?></td>-->
                <!--<td><td><?= h($promotion->created) ?></td>-->
               <!--<td> <td><?= h($promotion->modified) ?></td>-->
				<td class="last"><?= $this->Html->link(__(''), ['action' => 'view', $promotion->id], ['class'=>'fa fa-desktop']) ?></td>
						<td class="last">  <?= $this->Html->link(__(''), ['action' => 'edit', $promotion->id], ['class'=>'fa fa-pencil']) ?></td>
						<td class="last"><?= $this->Form->postLink(__(''), ['action' => 'delete', $promotion->id], ['class'=>'fa fa-trash', 'confirm' => __('Are you sure you want to delete # {0}?', $promotion->id)]) ?></td>
               
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

