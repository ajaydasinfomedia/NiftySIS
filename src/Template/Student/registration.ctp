<script>

$( document ).ready(function(){
	
    $("#regis").change(function(){
	var class_id = $(this).val();
     
	 $('#sec').html();
       $.ajax({
       type: 'POST',
       url: '<?php echo $this->Url->build([
"controller" => "Student",
"action" => "view2"]);?>',
	
       data : {id : class_id},

	     
       success: function (data)
       {            
			
			$('#sec').html(data);
			console.log(data);
			  
				
   },
error: function(e) {
       alert("An error occurred: " + e.responseText);
       console.log(e.responseText);	
}

       });

       });
   });

</script>

<div class="navbar">
                <div class="navbar-inner">
                    <div class="sidebar-pusher">
                        <a class="waves-effect waves-button waves-classic push-sidebar" href="javascript:void(0);">
                            <i class="fa fa-bars"></i>
                        </a>
                    </div>
                <!--    <div class="logo-box">
                        <a class="logo-text" href="#"><span>School</span></a>
                    </div> Logo Box -->
                    <div class="search-button">
                        <a class="waves-effect waves-button waves-classic show-search" href="javascript:void(0);"><i class="fa fa-search"></i></a>
                    </div>
                    <div class="topmenu-outer">
                        <div class="top-menu">
							<div class="col-md-8 col-sm-8 col-xs-6">
                            <ul class="nav navbar-nav navbar-left">
								<li>
									<div class="page-title">
										<h3>
										<span class="small_logo">
										<?php
										$small_logo = '/';
										?>
										<?php
										
										$nm=$this->Setting->fetchlogo();
										
										echo $this->Html->image($nm, ['width' => '40','height' => '40','style'=>'']);?>	                                 
									    </span>
										<span class="logo">
											<?php echo $this->Html->image($logo, ['width' => '215','height' => '40','style'=>'']);?>	                                
									    </span>
											
											<div class="school_subname">
												<font><?php echo $school_name;?></font>
											</div>
										</h3>
									</div>
								</li>
							</ul>
							</div>
                            <ul class="nav navbar-nav navbar-right col-md-4 col-sm-4 col-xs-6">
							 </ul><!-- Nav -->
                        </div><!-- Top Menu -->
                    </div>
                </div>
            </div>
<div class="row">
	<ul role="tablist" class="nav nav-tabs panel_tabs">	  
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('New Registration'),['controller' => 'Student', 'action' => 'registration'],['escape' => false]);?>
		</li>
	</ul>	
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'registration']]);?>
				<fieldset>
					<legend><?php echo __('Personal Info'); ?></legend>
			
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Class '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<select class="form-control validate[required,maxSize[50]]" name="classname" id="regis">
									<option value=""><?php echo __('Select Class'); ?> </option>	
									<?php foreach($it as $it2):?><option value="<?php echo $it2['class_id'];?>"><?php echo $it2['class_name'];?></option> <?php endforeach;?>
								</select>
							</div>
							
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Section '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<select class="form-control validate[required,maxSize[50]] ajaxdata" name="classsection" id="sec">
								<option value=""><?php echo __('Select Section'); ?></option>
								</select>
							</div>
							
							<!--
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Roll Number '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'roll_no','class'=>'form-control validate[required,maxSize[20]]','PlaceHolder'=>'Enter Roll Number'));?>
							</div>
							-->
							<input type="hidden" name="roll_no" value="">
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('First Name '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'first_name','class'=>'form-control validate[required,custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=>'Enter First Name'));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Middle Name '));?></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'middle_name','class'=>'form-control validate[custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=>'Enter Middle Name'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Last Name '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'last_name','class'=>'form-control validate[required,custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=>'Enter Last Name'));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Date Of Birth '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12 datepickericon">
								<?php echo $this->Form->input('',array('id'=>'date_of_birth','name'=>'date_of_birth','class'=>'form-control validate[required]','PlaceHolder'=>'Enter Birth Date'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Gender '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
		
								<label class="radio-inline label_float radio">
									<?php 
										$options = array('Male' => __('Male'), 'Female' => __('Female'));
										echo $this->Form->radio('gender',$options);
									?>
								</label>
								
							</div>
				</div>
				
				<legend><?php echo __('Address'); ?></legend>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Address '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'address','class'=>'form-control validate[required,maxSize[150]]','PlaceHolder'=>'Enter Address'));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('State '));?></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('', array('name'=>'state','class'=>'form-control'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('City '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('', array('name'=>'city','class'=>'form-control validate[required,maxSize[50]]'));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('PinCode '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'zip_code','class'=>'form-control validate[required,maxSize[10]]','PlaceHolder'=>'Enter Pin Code'));?>
							</div>
				</div>
				<legend><?php echo __('Contact'); ?></legend>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Mobile Number '));?></div>
							<div class="col-md-1 col-sm-1 col-xs-12">
								<?php echo $this->Form->input('',array('readonly','class'=>'form-control','name'=>'mobile_code','value'=>'+'.$country_code));?>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'mobile_no','class'=>'form-control validate[custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=>'Enter Mobile Number'));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Alternate Mobile Number '));?></div>
							<div class="col-md-1 col-sm-1 col-xs-12">
								<?php echo $this->Form->input('',array('readonly','class'=>'form-control','name'=>'alternate_mobile_code','value'=>'+'.$country_code));?>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'alternate_mobile_no','class'=>'form-control validate[custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=>'Enter Alternate Mobile Number'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Phone Number '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'phone','class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=>'Enter Phone Number'));?>
							</div>
				</div>
				<legend><?php echo __('Authorize Info'); ?></legend>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Email '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'email','class'=>'form-control validate[required,custom[email],maxSize[50]]','PlaceHolder'=>'Enter Valid Email'));?>
							</div>

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Username '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'username','class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=>'Enter Valid Username'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Password '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('type'=>'password','name'=>'password','class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=>'Enter Password'));?>
							</div>
				</div>
				<legend><?php echo __('Other Info'); ?></legend>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Image '));?></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<?php echo $this->Form->input('',array('class'=>'file','id'=>'file-0a','type'=>'file','name'=>'image','accept'=>'.png, .jpg, .jpeg, .gif','PlaceHolder'=>'Select Image'));?>
							</div>
							<!--
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Approved '));?></div>
							<div class="col-md-4 col-sm-4 col-xs-12">							
								<?php  echo $this->Form->input('', array('class'=>'checkbox','name'=>'status','type' => 'select','multiple' => 'checkbox','options' => array('Approved' => __('')))); ?>
							</div>
							-->
							<input type="hidden" name="status" value="Not Approved">
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"></div>
							<div class="col-sm-4 regi">
								<?php echo $this->Form->input(__('New Registration'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
							</div>
				</div>

			<?php $this->Form->end(); ?>
		</div>
</div>			
<style>
.regi .submit{
	text-align: left;
}
</style>
