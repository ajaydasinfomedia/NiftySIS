<script>
$(document).ready(function(){
	$("#add").click(function(){
		$("#child").clone().appendTo(".newchild");
	});
	$("#remove").click(function(){
		var childcnt = $(".newchild #child").length;	
		if(childcnt > 1)
		{
			$("#child:last-child").remove();
		}
	});
	$('#date_of_birth12').datepicker
	({				
		maxDate: new Date(),
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',	
	});	
});
</script>

<div class="row">	
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Parent List'),['controller' => 'Parent', 'action' => 'parentlist'],['escape' => false]);?>
		</li>
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Parent'),['controller' => 'Parent', 'action' => 'addparent'],['escape' => false]);?>
		</li>
	</ul>	
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addparent']]);?>
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
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Date Of Birth '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12 datepickericon">
								<?php echo $this->Form->input('',array('id'=>'date_of_birth12','name'=>'date_of_birth','class'=>'form-control validate[required]','PlaceHolder'=> __('Enter Birth Date ')));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Child '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12 newchild">
								<select name="child_id[]" id="child" class="form-control validate[required,maxSize[50]]">
									<option value=""><?php echo __('Select Child');?></option>
									<?php foreach($child as $it2):
										$name=$it2['first_name']." ".$it2['last_name'];
									?>
									<option value="<?php echo $it2['user_id'];?>"><?php echo $name;?></option> 
									
									<?php endforeach;?>
								</select>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"></div>
							<div class="col-md-4 col-sm-4 col-xs-12"></div>
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"></div>
							<div class="col-md-4 col-sm-4 col-xs-12" style="margin-top: 5px;">
								<div class="col-md-6 col-sm-6 col-xs-12"><?php echo $this->Form->button(__('Add Child'),array('type'=>'button','class'=>'btn','id'=>'add'));?></div>
								<div class="col-md-6 col-sm-6 col-xs-12"><?php echo $this->Form->button(__('Remove Child'),array('id'=>'remove','type'=>'button','class'=>'btn'));?></div>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Relation '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('relation', array('name'=>'relation','class'=>'form-control validate[required,maxSize[20]]','options' => array('Father'=>__('Father '),'Mother'=>__('Mother '),'Other'=>__('Other ')),'empty' => __('(choose one)')));?>
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

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Phone Number '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('phone1',array('name'=>'phone','class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=> __('Enter Phone Number ')));?>
							</div>
				</div>
				<legend><?php echo __('Authorize Info'); ?></legend>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Email '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('email1',array('name'=>'email','class'=>'form-control validate[required,custom[email],maxSize[50]]','PlaceHolder'=> __('Enter Valid Email ')));?>
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
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Image '));?></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('class'=>'file','id'=>'file-0a','type'=>'file','name'=>'image','accept'=>'.jpg, .jpeg'));?>
							</div>
				</div>
				<div class="form-group">
							
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input(__('Add Parent'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
							</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>			