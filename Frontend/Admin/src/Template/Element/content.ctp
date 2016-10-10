<div class="col-md-12 col-sm-12 col-xs-12">
              <div class="dashboard_graph x_panel">
                <div class="row x_title">
                  <div class="col-md-6">
                    <!--<h3><?= $this->fetch('title') ?></h3>-->
                  </div>
                  
                </div>
				
					<!-- Flash message -->
					<?= $this->Flash->render() ?>
				<!-- Flash message -->
				
                <div class="x_content">
						<?= $this->fetch('content') ?>
                </div>
              </div>
            </div>