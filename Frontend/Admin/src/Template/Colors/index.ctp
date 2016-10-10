<nav class="large-3 medium-4 columns mbot15" id="actions-sidebar">
    <ul class="nav nav-pills links-admin">
        <li><?= $this->Html->link(__('New Color'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2><?=  __('Colors') ?>  </h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div><div class="x_content">
                  <br />
  <table class="table table-striped responsive-utilities jambo_table bulk_action">
                    <thead>
                      <tr class="headings">
          <!--  <th>
                          <input type="checkbox" id="check-all" class="flat">
                        </th>-->
               <!-- <th><?= $this->Paginator->sort('id') ?></th>-->
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= $this->Paginator->sort('image') ?></th>
             <!--   <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('modified') ?></th>-->
                <!--<th class="actions"><?= __('Actions') ?></th>-->
				<th colspan="3" class="column-title text-center no-link last"><span class="nobr">Action</span>
                        </th>
                        <th class="bulk-actions" colspan="8">
                          <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                        </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($colors as $color): ?>
            <tr class="even pointer">
			<td><?= ucfirst(h($color->name)) ?></td>
                <td><?= $this->Html->image('files/Colors/image/'.$color->image, ['alt'=>'color',  'width'=>'100px']); ?> </td>
                <td class="last">  <?= $this->Html->link(__(''), ['action' => 'edit', $color->id], ['class'=>'fa fa-pencil']) ?></td>
						<td><?= $this->Form->postLink(__(''), ['action' => 'delete', $color->id], ['class'=>'fa fa-trash', 'confirm' => __('Are you sure you want to delete # {0}?', $color->id)]) ?></td>
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
