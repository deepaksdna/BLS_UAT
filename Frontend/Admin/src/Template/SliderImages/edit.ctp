<nav class="large-3 medium-4 columns mbot15" id="actions-sidebar">
    <ul class="nav nav-pills links-admin">
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $sliderImage->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $sliderImage->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Slider Images'), ['action' => 'index']) ?></li>
    </ul>
</nav>


<div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2><?= __('Edit slider Image') ?>  </h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
    <?= $this->Form->create($sliderImage, ['type'=>'file', 'class'=>'form-horizontal form-label-left']) ?>
    
					
					 <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Image<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
						<?php 
						echo $this->Html->image('files/sliderImages/image/'.@$sliderImage->image, ['alt'=>'Slider Image', 'class'=>'img-thumbnail', 'width'=>'300px', 'height'=>'75px']);
						echo $this->Form->input('image', ['type'=>'file', 'required'=> true, 'label' => false, 'class'=>'form-control col-md-7 col-xs-12']);
							echo $this->Form->input('image_dir', ['type'=>'hidden']); ?>
                      </div>
                    </div>
    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                       <?= $this->Form->button(__('Submit'), ['class'=>'btn btn-success']) ?>
    
                      </div>
                    </div>
    <?= $this->Form->end() ?>
 </div>
              </div>
            </div>
          </div>
