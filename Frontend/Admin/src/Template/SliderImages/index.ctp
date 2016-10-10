<nav class="large-3 medium-4 columns mbot15" id="actions-sidebar">
    <ul class="nav nav-pills links-admin">
        <li><?= $this->Html->link(__('New Slider Image'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2><?= __('Slider Images') ?> </h2>
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
                <th class="column-title"><?= h('Image') ?></th> 
				
				<th colspan="2" class="column-title text-center no-link last"><span class="nobr">Action</span>
                        </th>				
            </tr>
        </thead>
		
			
        <tbody>
             <?php foreach ($sliderImages as $sliderImage): ?>
            <tr class="even pointer">
			
			 
			 <td><?= $this->Html->image('files/SliderImages/image/'.@$sliderImage->image, ['alt'=>'Slider Image', 'class'=>'img-thumbnail', 'width'=>'300px', 'height'=>'75px']); ?></td>
			 
			 
			 
                
                   
                   <td class="last"> <?= $this->Html->link(__(''), ['action' => 'edit', $sliderImage->id], ['class'=>'fa fa-pencil']) ?></td>
                    <td class="last"><?= $this->Form->postLink(__(''), ['action' => 'delete', $sliderImage->id], ['class'=>'fa fa-trash','confirm' => __('Are you sure you want to delete # {0}?', $sliderImage->id)]) ?>
                </td>
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

