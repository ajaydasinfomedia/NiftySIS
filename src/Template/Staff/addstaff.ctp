<script>
jQuery(document).ready(function() {
	$('#date_of_birth12').datepicker
	({				
		maxDate: new Date(),
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',	
	});		
} );
</script>
<div class="row">
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Support Staff List'),['controller' => 'Staff', 'action' => 'stafflist'],['escape' => false]);?>
		</li>
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add New Support Staff'),['controller' => 'Staff', 'action' => 'addstaff'],['escape' => false]);?>
		</li>
		
	</ul>	
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('formID',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addstaff']]);?>
				<fieldset>
					<legend><?php echo __('Personal Info'); ?></legend>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter First Name '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('first_name',array('name'=>'first_name','class'=>'form-control validate[required,custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=> __('Enter First Name ')));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter Middle Name '));?></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('middle_name',array('name'=>'middle_name','class'=>'form-control validate[custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=> __('Enter Middle Name ')));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter Last Name '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('last_name',array('name'=>'last_name','class'=>'form-control validate[required,custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=> __('Enter Last Name ')));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Date Of Birth '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12 datepickericon">
								<?php echo $this->Form->input('',array('id'=>'date_of_birth12','name'=>'date_of_birth','class'=>'form-control validate[required]','PlaceHolder'=> __('Enter Birth Date ')));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Gender '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">		
								<label class="radio-inline label_float radio">
									<?php 
										$options = array('male' => __('Male'), 'female' => __('Female'));
										$attributes2 = array('legend' => false,'value' => 'male');
										echo $this->Form->radio('gender',$options,$attributes2);
									?>
								</label>								
							</div>
				</div>
				
				<legend><?php echo __('Address'); ?></legend>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter Address '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('address',array('name'=>'address','class'=>'form-control validate[required,maxSize[150]]','PlaceHolder'=> __('Enter Valid Address ')));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter State '));?></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('state', array('name'=>'state','class'=>'form-control'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter City '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('city', array('name'=>'city','class'=>'form-control validate[required,maxSize[50]]'));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter Pin Code '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('zip_code',array('name'=>'zip_code','class'=>'form-control validate[required,maxSize[10]]','PlaceHolder'=> __('Enter Pin Code ')));?>
							</div>
				</div>
				<legend><?php echo __('Contact'); ?></legend>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Mobile Number '));?></div>
							<div class="col-md-1 col-sm-1 col-xs-12">
								<?php echo $this->Form->input('mobile_code',array('readonly','class'=>'form-control','name'=>'mobile_code','value'=>'+'.$country_code));?>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<?php echo $this->Form->input('mobile_no',array('name'=>'mobile_no','class'=>'form-control validate[custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=> __('Enter Mobile Number ')));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Alternate Mobile Number '));?></div>
							<div class="col-md-1 col-sm-1 col-xs-12">
								<?php echo $this->Form->input('alternate_mobile_code',array('readonly','class'=>'form-control','name'=>'alternate_mobile_code','value'=>'+'.$country_code));?>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<?php echo $this->Form->input('alternate_mobile_no',array('name'=>'alternate_mobile_no','class'=>'form-control validate[custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=> __('Enter Alternate Mobile Number')));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Phone Number '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'phone','class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=> __('Enter Phone Number ')));?>
							</div>
				</div>
				<legend><?php echo __('Authorize Info'); ?></legend>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Email '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('id'=>'email','name'=>'email','class'=>'form-control validate[required,custom[email],maxSize[50]]','PlaceHolder'=> __('Enter Valid Email ')));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Username '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('username',array('name'=>'username','class'=>'form-control validate[required,custom[onlyLetterNumber],maxSize[50]]','PlaceHolder'=> __('Enter Valid Username ')));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Password '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('password',array('type'=>'password','name'=>'password','class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=> __('Enter Password ')));?>
							</div>
				</div>
				<legend><?php echo __('Other Info'); ?></legend>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Working Hour '));?></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('working_hour', array('name'=>'working_hour','class'=>'form-control','options' => array('Full Time'=> __('Full Time '),'Part Time'=> __('Part Time ')),'empty' => __('(Select Job Time)')));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter Position '));?></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('position',array('name'=>'position','class'=>'form-control','PlaceHolder'=> __('Enter Position ')));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Image '));?></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('class'=>'file','id'=>'file-0a','type'=>'file','name'=>'image','accept'=>'.jpg, .jpeg'));?>
							</div>
				</div>
				<div class="form-group">
							
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input(__('Add Support Staff'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
							</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>			