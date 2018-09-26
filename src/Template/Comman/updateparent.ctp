<script>
$(document).ready(function(){
	$("#add").click(function(){
		$("#child").clone().appendTo(".newchild");
		
	});
	$("#remove").click(function(){
		$("#child:last-child").remove();
		
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
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Parent List'),['controller' => 'Comman', 'action' => 'parentlist'],['escape' => false]);?>
		</li>
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-pencil-square-o fa-lg')) . __('Edit Parent'),['controller' => 'Comman', 'action' => 'updateparent',$this->Setting->my_simple_crypt($it['user_id'],'e')],['escape' => false]);?>
		</li>
	</ul>	
</div>
<?php 
$relation = isset($it['relation'])?$it['relation']:array();
$parent_child_id = isset($parent_child_id)?$parent_child_id:array();
// var_dump($parent_child_id);die;  
?>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('$it',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
				<fieldset>
					<legend><?php echo __('Personal Info'); ?></legend>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter First Name '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['first_name'],'name'=>'first_name','class'=>'form-control validate[required,custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=> __('Enter First Name')));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter Middle Name '));?></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['middle_name'],'name'=>'middle_name','class'=>'form-control validate[custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=>__('Enter Middle Name')));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter Last Name '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['last_name'],'name'=>'last_name','class'=>'form-control validate[required,custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=>__('Enter Last Name')));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Gender '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">		
								<label class="radio-inline label_float radio">
									<?php 
										$gender=!empty($it['gender'])?$it['gender']:'male';
										$options = array('male' => __('Male'), 'female' => __('Female'));
										echo $this->Form->radio('gender',$options,array('default'=>$gender));
									?>
								</label>								
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Date Of Birth '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12 datepickericon">
								<?php echo $this->Form->input('',array('value'=>date("Y-m-d", strtotime($it['date_of_birth'])),'id'=>'date_of_birth12','name'=>'date_of_birth','class'=>'form-control validate[required]','PlaceHolder'=>__('Enter Birth Date')));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Child '));?></div>							
							<div class="col-sm-4 newchild">
							<?php
							if(!empty($parent_child_id))
							{
								foreach($parent_child_id as $child)
								{
							?>
								<select name="child_id[]" id="child" class="form-control validate[required,maxSize[50]]">
									<?php foreach($ch as $it2):
										$name=$it2['first_name']." ".$it2['last_name'];
										$selected = ($it2['user_id']==$child)?"selected":'';
									?>
									<option value="<?php echo $it2['user_id'];?>" <?php echo $selected;?>><?php echo $name;?></option> 
									
									<?php endforeach;?>
								</select>
								<?php
								}
							}
							else
							{ ?>
								<select name="child_id[]" id="child" class="form-control validate[required,maxSize[50]]">
									<option value=""><?php echo __('Select Child');?></option>
									<?php foreach($ch as $it2):
										$name=$it2['first_name']." ".$it2['last_name'];
										/* $selected = ($it2['user_id']==$child)?"selected":''; */
									?>
									<option value="<?php echo $it2['user_id'];?>"><?php echo $name;?></option> 										
									<?php endforeach;?>
								</select>
						<?php
							}
							?>
							</div>				
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Relation '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('', array('name'=>'relation','class'=>'form-control validate[required,maxSize[20]]','options' => array('Father'=>__('Father'),'Mother'=>__('Mother'),'Other'=>__('Other ')),'empty' => __('(choose one)'),'default'=>$relation));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12"></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<div class="col-md-4 col-sm-4 col-xs-12"><?php echo $this->Form->button(__('Add Child'),array('type'=>'button','class'=>'btn','id'=>'add'));?></div>
								<div class="col-md-4 col-sm-4 col-xs-12"><?php echo $this->Form->button(__('Remove Child'),array('id'=>'remove','type'=>'button','class'=>'btn'));?></div>
							</div>
				</div>
				
				<legend><?php echo __('Address'); ?></legend>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter Address '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['address'],'name'=>'address','class'=>'form-control validate[required,maxSize[150]]','PlaceHolder'=> __('Enter Valid Address')));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select State '));?></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('', array('value'=>$it['state'],'name'=>'state','class'=>'form-control'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select City '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('', array('value'=>$it['city'],'name'=>'city','class'=>'form-control validate[required,maxSize[50]]'));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter Pin Code '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['zip_code'],'name'=>'zip_code','class'=>'form-control validate[required,maxSize[10]]','PlaceHolder'=>'Enter Pin Code'));?>
							</div>
				</div>
				<legend><?php echo __('Contact'); ?></legend>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Mobile Number '));?></div>
							<div class="col-md-1 col-sm-1 col-xs-12">
								<?php echo $this->Form->input('',array('readonly','class'=>'form-control','name'=>'mobile_code','value'=>'+'.$country_code));?>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['mobile_no'],'name'=>'mobile_no','class'=>'form-control validate[custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=>'Enter Mobile Number'));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Phone Number '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['phone'],'name'=>'phone','class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=>'Enter Phone Number'));?>
							</div>
				</div>
				<legend><?php echo __('Authorize Info'); ?></legend>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Email '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['email'],'name'=>'email','class'=>'form-control validate[required,custom[email],maxSize[50]]','PlaceHolder'=>'Enter Valid Email'));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Username '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('readonly','value'=>$it['username'],'name'=>'username','class'=>'form-control validate[maxSize[50]]'));?>
							</div>
				</div>
				
				<legend><?php echo __('Other Info'); ?></legend>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Image '));?></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
							
						<?php	
							if ($it['image']!='') 
							{
								echo $this->Html->image($it['image'],array('id'=>'oldimg','height'=>'100px','width'=>'100px'));
							} 
							else 
							{
								echo "Not Image";
							}
						?>
								<?php echo $this->Form->input('',array('type'=>'hidden','value'=>$it['image'],'name'=>'image2'));?>
								<?php echo $this->Form->input('',array('id'=>'newimg','class'=>'file','type'=>'file','name'=>'image','accept'=>'.jpg, .jpeg'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input(__('Save Parent'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
							</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>			