<div class="profile">
            <div class="profile_pic">
			
		 <?php if($this->request->session()->read('Auth.User.image')!=''){ ?> 
		 
	<?php  echo $this->Html->image('files/Admins/image/'.$this->request->session()->read('Auth.User.image'), ['height'=>'57px', 'class'=>'img-circle profile_img']); ?>
	<?php }else{ ?> 

	<?php  echo $this->Html->image('files/Noimage/no-man.png', ['height'=>'57px' , 'class'=>'img-circle profile_img']); ?>	
						<?php } ?>
	
            </div>
            <div class="profile_info">
              <span>Welcome,</span>
              <h2><?php echo ucfirst($this->request->session()->read('Auth.User.firstname')).' '.ucfirst($this->request->session()->read('Auth.User.lastname')); ?></h2>
            </div>
          </div>
		 
		 
		 
		