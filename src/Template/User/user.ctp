<div class="mainlogin">		
			<div class="panel-body main-class">
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
													
							<div class="form-group loginp-username">
								<?php echo $this->Form->input('Email',array('name'=>'username','class'=>'form-control validate[required]','id'=>'username','PlaceHolder'=>__('Email/Username')));?>					
							</div>
							<div class="form-group loginp-password">
								<?php echo $this->Form->input('Password',array('type'=>'password','name'=>'password','class'=>'form-control validate[required]','id'=>'password','PlaceHolder'=>__('Password')));?>									
							</div>
							<div class="form-group">
								<?php echo $this->Form->input(__('Login'),array('type'=>'submit','name'=>'add','class'=>'btn login-btn'));?>
							</div>
							<span class="forgot-password">
								<?php echo $this->Html->link(__('Forget Password'),['controller' => 'User', 'action' => 'forgetpassword']);?>
							</span>
							<span class="admission">
								<?php echo $this->Html->link(__('Student Admission'),['controller' => 'Admission', 'action' => 'registration']);?>
							</span>
							
						</div>	
						
					<?php $this->Form->end(); ?>
				
			</div>	
			
			<div class="panel-body login-demo">	
				<table id="loginlist" class="table table-striped">
					<thead>
						<tr>
							<th><?php echo __("Role");?></th>
							<th><?php echo __("User Name");?></th>
							<th><?php echo __("Password");?></th>
						</tr>
					</thead>
					<tfoot></tfoot>
					<tbody>
						<tr>
							<td><?php echo __("Student");?></td>
							<td><?php echo __("Cambpell");?></td>
							<td><?php echo __("Cambpell");?></td>
						<tr>
						<tr>
							<td><?php echo __("Teacher");?></td>
							<td><?php echo __("Kaitlyn");?></td>
							<td><?php echo __("Kaitlyn");?></td>
						<tr>
					</tbody>
				</table>
			</div>	
</div>
