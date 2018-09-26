<div class="row">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">
		  <li class="active">
			  <?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-key fa-lg')) . 'Change Password',['controller' => 'Changepassword', 'action' => 'changepassword'],['escape' => false]);?>
		  </li>
	</ul>
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'changepassword']]);?>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('Enter Old Password ');?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('type'=>'password','name'=>'oldpass','class'=>'form-control validate[required]','PlaceHolder'=>'Enter Old Password'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('Enter New Password ');?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('type'=>'password','name'=>'newpass','class'=>'form-control validate[required]','PlaceHolder'=>'Enter New Password'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
							<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('Change Password',array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
							</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>			