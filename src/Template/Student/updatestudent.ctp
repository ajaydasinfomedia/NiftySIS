<script>

$( document ).ready(function(){
	
    $("#save").change(function(){
	var class_id = $(this).val();
     
	 $('.ajaxdata').html();
       $.ajax({
       type: 'POST',
       url: '<?php echo $this->Url->build([
"controller" => "Student",
"action" => "view2"]);?>',
	
       data : {id : class_id},

	     
       success: function (data)
       {            
			
			$('.ajaxdata').html(data);
			console.log(data);
			  
				
   },
error: function(e) {
       alert("An error occurred: " + e.responseText);
       console.log(e.responseText);	
}

       });

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
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Student List'),['controller' => 'Student', 'action' => 'studentlist'],['escape' => false]);?>
		</li>
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-pencil-square-o fa-lg')) .__('Edit Student'),['controller' => 'Student', 'action' => 'updatestudent',$this->Setting->my_simple_crypt($it['user_id'],'e')],['escape' => false]);?>
		</li>
	</ul>	
</div>
<?php
$user = $this->request->session()->read('user_id');
$role = $this->Setting->get_user_role($user);
$readonly = ($role == 'admin')?'':'readonly';
?>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('$it',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
				<fieldset>
					<legend><?php echo __('Personal Info'); ?></legend>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Class '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php 
									echo @$this->form->select("class_id",$cls,["default"=>$it['classname'],"name"=>"classname","empty"=>__("Select Class"),"class"=>"form-control validate[required,maxSize[50]]","id"=>"save"]);
								?>
							</div>
							
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Section '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php 
									echo @$this->form->select("class_section_id",$sect,["default"=>$it['classsection'],"name"=>"classsection","empty"=>__("Select Section"),"class"=>"form-control validate[required,maxSize[50]] ajaxdata"]);
								?>
							
							</div>

							
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Roll Number '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array($readonly,'value'=>$it['roll_no'],'name'=>'roll_no','class'=>'form-control validate[required,maxSize[20]]'));?>
							</div>
				
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('First Name '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['first_name'],'name'=>'first_name','class'=>'form-control validate[required,custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=> __('Enter First Name ')));?>
							</div>

							
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Middle Name '));?></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['middle_name'],'name'=>'middle_name','class'=>'form-control validate[custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=> __('Enter Middle Name ')));?>
							</div>
				
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Last Name '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['last_name'],'name'=>'last_name','class'=>'form-control validate[required,custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=> __('Enter Last Name ')));?>
							</div>

	
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Date Of Birth '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12 datepickericon">
								<?php echo $this->Form->input('',array('value'=>date("Y-m-d", strtotime($it['date_of_birth'])),'id'=>'date_of_birth12','name'=>'date_of_birth','class'=>'form-control validate[required]','PlaceHolder'=>'Enter Birth Date'));?>
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
				
				<legend><?php echo __('Address'); ?></legend>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Address '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['address'],'name'=>'address','class'=>'form-control validate[required,maxSize[150]]','PlaceHolder'=> __('Enter Valid Address ')));?>
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
								<?php echo $this->Form->input('',array('value'=>$it['zip_code'],'name'=>'zip_code','class'=>'form-control validate[required,maxSize[10]]','PlaceHolder'=> __('Enter Pin Code ')));?>
							</div>
				</div>
				<legend><?php echo __('Contact'); ?></legend>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Mobile Number '));?></div>
							<div class="col-md-1 col-sm-1 col-xs-12">
								<?php echo $this->Form->input('',array('readonly','class'=>'form-control','name'=>'mobile_code','value'=>'+'.$country_code));?>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['mobile_no'],'name'=>'mobile_no','class'=>'form-control validate[custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=> __('Enter Mobile Number ')));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Alternate Mobile Number '));?></div>
							<div class="col-md-1 col-sm-1 col-xs-12">
								<?php echo $this->Form->input('',array('readonly','class'=>'form-control','name'=>'alternate_mobile_code','value'=>'+'.$country_code));?>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['alternate_mobile_no'],'name'=>'alternate_mobile_no','class'=>'form-control validate[custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=> __('Enter Alternate Mobile Number')));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Phone Number '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['phone'],'name'=>'phone','class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=> __('Enter Phone Number ')));?>
							</div>
				</div>
				<legend><?php echo __('Authorize Info'); ?></legend>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Email '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['email'],'name'=>'email','class'=>'form-control validate[required,custom[email],maxSize[50]]','PlaceHolder'=> __('Enter Valid Email ')));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Username '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('readonly','value'=>$it['username'],'name'=>'username','class'=>'form-control'));?>
							</div>
							
				</div>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Password '));?></div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<?php echo $this->Form->input('password',array('type'=>'password','name'=>'password','class'=>'form-control validate[maxSize[50]]','PlaceHolder'=> __('Enter Password ')));?>
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

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Approved '));?></div>
							<div class="col-md-4 col-sm-4 col-xs-12">	
								<?php	
						
									if($it['status']=='Approved')
									{
										echo $this->Form->select('',['Approved'=>''],['name'=>'status','multiple' => 'checkbox','default'=>'Approved']);
									}
									else
									{
										echo $this->Form->select('',['Approved'=>''],['name'=>'status','multiple' => 'checkbox']);
									}
									
								?>
							</div>
				</div>
				<div class="form-group">
							
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input(__('Save Student'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success','id'=>'MyName'));?>
							</div>
				</div>

			<?php $this->Form->end(); ?>
		</div>
</div>			