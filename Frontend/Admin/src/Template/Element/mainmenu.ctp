<div class="top_nav">

        <div class="nav_menu">
          <nav class="" role="navigation">
            <div class="nav toggle">
              <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
              <li class="">
                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                
				  
				<?php if($this->request->session()->read('Auth.User.image')!=''){ ?> 

				<?php  echo $this->Html->image('files/Admins/image/'.$this->request->session()->read('Auth.User.image')); ?>
				<?php }else{ ?> 

				<?php  echo $this->Html->image('files/Noimage/no-man.png'); ?>	
				<?php } ?>
				  
				  
				  
<?php echo ucfirst($this->request->session()->read('Auth.User.firstname')).' '.ucfirst($this->request->session()->read('Auth.User.lastname')); ?>
                  <span class=" fa fa-angle-down"></span>
                </a>
                <ul class="dropdown-menu dropdown-usermenu pull-right">
                  <li>
				  
				  <?= $this->Html->link(__('Profile'), ['controller'=>'MyProfile', 'action' => 'index']) ?>
				  
                  </li>
                  <li>
				  
				  <?= $this->Html->link(__('Change password'), ['controller'=>'MyProfile', 'action' => 'changepass']) ?>
				  
                  </li>
				  <style>
				  #signout{position:relative}
				  .signouticon{position:absolute; top:14px; right:20px;}
				  </style>
	 <li id="signout">			   
	<?php echo $this->Html->link('Log Out', array('controller' => 'logins', 'action' => 'logout'), array('title' => 'Logout')); ?> <i class="fa fa-sign-out pull-right signouticon"></i></li>
	
	<!--<li><a href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a>-->
                  </li>
                </ul>
              </li>

             <!-- <li role="presentation" class="dropdown">
                <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                  <i class="fa fa-envelope-o"></i>
                  <span class="badge bg-green">6</span>
                </a>
                <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                  <li>
                    <a>
                      <span class="image">
                                        <img src="images/img.jpg" alt="Profile Image" />
                                    </span>
                      <span>
                                        <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                      </span>
                      <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                    </a>
                  </li>
                  <li>
                    <a>
                      <span class="image">
                                        <img src="images/img.jpg" alt="Profile Image" />
                                    </span>
                      <span>
                                        <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                      </span>
                      <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                    </a>
                  </li>
                  <li>
                    <a>
                      <span class="image">
                                        <img src="images/img.jpg" alt="Profile Image" />
                                    </span>
                      <span>
                                        <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                      </span>
                      <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                    </a>
                  </li>
                  <li>
                    <a>
                      <span class="image">
                                        <img src="images/img.jpg" alt="Profile Image" />
                                    </span>
                      <span>
                                        <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                      </span>
                      <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                    </a>
                  </li>
                  <li>
                    <div class="text-center">
                      <a>
                        <strong>See All Alerts</strong>
                        <i class="fa fa-angle-right"></i>
                      </a>
                    </div>
                  </li>
                </ul>
              </li>-->

            </ul>
          </nav>
        </div>

      </div>