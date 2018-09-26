<div class="mainlogin">		
			<div class="panel-body main-class forgot_password">
				<div class="page-title" style="float:none!important;">
					<?php 
					if(isset($logo))
					{
						echo $this->Html->image($logo, ['style'=>'float:none!important;margin:0;']);
					}	
					?>
				</div>
				<?= $this->Flash->render() ?>
					<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
						<div class="center-main">	
														
							<div class="form-group loginp-username" style="margin-bottom: 0;">
								<?php echo $this->Form->input('username',array('name'=>'username','class'=>'form-control validate[required,custom[email]]','PlaceHolder'=>__('Email')));?>					
							</div>
							<div class="form-group loginp-phone">				
								<?php echo $this->Form->input('phone',array('name'=>'phone','class'=>'form-control validate[required,custom[onlyNumberSp]]','PlaceHolder'=> __('Phone Number ')));?>					
							</div>
							<div class="form-group loginp-password">
								<?php echo $this->Form->input('password',array('type'=>'password','name'=>'password','class'=>'form-control validate[required]','PlaceHolder'=>__('New Password')));?>									
							</div>
							<div class="form-group">
								<?php echo $this->Form->input(__('New Password Saved'),array('type'=>'submit','name'=>'add','class'=>'btn login-btn'));?>
							</div>
							<span class="forgot-password" style="padding-bottom: 0px;">
								<?php echo $this->Html->link(__('Back To Login Page'),['controller' => 'User', 'action' => 'user']);?>
							</span>
							
						</div>	
						
					<?php $this->Form->end(); ?>
				
			</div>		
</div>