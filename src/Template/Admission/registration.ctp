<?php
use Cake\Routing\Router;
$user = $this->request->session()->read('user_id');
$role = $this->Setting->get_user_role($user);
// var_dump($role);die;
?>	
<!---Previous School Attended  script---->
<script>
    function yesnoCheck(that) {
        if (that.value == "other") {
         
            document.getElementById("ifYes").style.display = "block";
        } else {
            document.getElementById("ifYes").style.display = "none";
        }
    }
</script>
<!--- end ---->

<!---father School  script---->
<script>
    function schoolCheck(that) {
        if (that.value == "other") {
        
            document.getElementById("fatherschoolother").style.display = "block";
        } else {
            document.getElementById("fatherschoolother").style.display = "none";
        }
    }
</script>
<!--- end ---->

<!---mother School  script---->
<script>
    function motschoolCheck(that) {
        if (that.value == "other") {
        
            document.getElementById("motherschoolother").style.display = "block";
        } else {
            document.getElementById("motherschoolother").style.display = "none";
        }
    }
</script>
<!--- end ---->

<!---father medium School  script---->
<script>
    function famediumCheck(that) {
        if (that.value == "other") {
      
            document.getElementById("fathermediumother").style.display = "block";
        } else {
            document.getElementById("fathermediumother").style.display = "none";
        }
    }
</script>
<!--- end ---->

<!---mother medium School  script---->
<script>
    function momediumCheck(that) {
        if (that.value == "other") {
        
            document.getElementById("mothermediumother").style.display = "block";
        } else {
            document.getElementById("mothermediumother").style.display = "none";
        }
    }
</script>
<!--- end ---->

<!---father Occupation School  script---->
<script>
    function fatheroccuCheck(that) {
        if (that.value == "other") {
         
            document.getElementById("fatheroccuother").style.display = "block";
        } else {
            document.getElementById("fatheroccuother").style.display = "none";
        }
    }
</script>
<!--- end ---->

<!---mother Occupation School  script---->
<script>
    function motheroccuCheck(that) {
        if (that.value == "other") {
     
            document.getElementById("motheroccuother").style.display = "block";
        } else {
            document.getElementById("motheroccuother").style.display = "none";
        }
    }
</script>
<!--- end ---->


<!---Family Information script----->
<script>
$(document).ready(function(){
    $("#sinfather").click(function(){
        $("#motid,#motid1,#motid2,#motid3,#motid4,#motid5,#motid6,#motid7,#motid8,#motid9,#motid10,#motid11,#motid12").hide();
    });
	$("#sinfather").click(function(){
        $("#fatid,#fatid1,#fatid2,#fatid3,#fatid4,#fatid5,#fatid6,#fatid7,#fatid8,#fatid9,#fatid10,#fatid11,#fatid12").show();
    });
});
</script>
<script>
$(document).ready(function(){
    $("#sinmother").click(function(){
        $("#motid,#motid1,#motid2,#motid3,#motid4,#motid5,#motid6,#motid7,#motid8,#motid9,#motid10,#motid11,#motid12").show();
    });
	$("#sinmother").click(function(){
        $("#fatid,#fatid1,#fatid2,#fatid3,#fatid4,#fatid5,#fatid6,#fatid7,#fatid8,#fatid9,#fatid10,#fatid11,#fatid12").hide();
    });
});
</script>

<script>
$(document).ready(function(){
    $("#boths").click(function(){
        $("#motid,#motid1,#motid2,#motid3,#motid4,#motid5,#motid6,#motid7,#motid8,#motid9,#motid10,#motid11,#motid12").show();
    });
	$("#boths").click(function(){
        $("#fatid,#fatid1,#fatid2,#fatid3,#fatid4,#fatid5,#fatid6,#fatid7,#fatid8,#fatid9,#fatid10,#fatid11,#fatid12").show();
    });
});
</script>


<!-----end------>


<script>
$(document).ready(function(){
	$('#chkIsTeamLead').change(function(){

    if ($('#chkIsTeamLead').is(':checked') == true){
      $('#txtNumHours,#txtNumHours1,#txtNumHours2,#txtNumHours3,#txtNumHours4').prop('disabled', true);
      console.log('checked');
	} else {
     $('#txtNumHours,#txtNumHours1,#txtNumHours2,#txtNumHours3,#txtNumHours4').prop('disabled', false);
     console.log('unchecked');
	}

	});
	$('#date_of_birth1').datepicker
	({				
		minDate: new Date(),
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd',	
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
<style>
.top-menu .page-title,.page-title h3{
	padding: 0px;
	width: 100%;
	float: left;
	text-align: center;
}
</style>
<?php
$school_name = "";
$school_name = $this->Setting->getfieldname('school_name');
if(!$user)
{
?>
<div class="navbar">
	<div class="navbar-inner">
		<div class="topmenu-outer">
			<div class="top-menu" style="display: block;">
				<div class="col-md-8 col-sm-8 col-xs-12">
				<ul class="nav navbar-nav navbar-left">
					<li>
						<div class="page-title">
							<h3>
							<span class="logo register_page">
							<a href="<?php echo $this->request->base;?>" style="float: left;width: 100%;cursor: pointer;">
							
								<?php 
								if(isset($logo))
								{
									echo $this->Html->image($logo, ['style'=>'']);
								}
								?>	                                
							
							</a>	
							</span>
							<?php 
							if(!empty($school_name))
							{
							?>
							<div class="school_subname">
								<font><?php echo __("$school_name");?> </font>
							</div>
						<?php } ?>
							</h3>
						</div>
					</li>
				</ul>
				</div>
				<ul class="nav navbar-nav navbar-right col-md-4 col-sm-4 col-xs-6">                      
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle waves-effect waves-button waves-classic" href="#">
						<?php
						$user_id=$this->request->session()->read('user_id');
						$user=$this->request->session()->read('user');
						$image=$this->request->session()->read('image');
						?>
						<span class="user-name">
						<?php
						if(!empty($user_id))
						{
							echo $this->Html->image($image, ['class' => 'img-circle avatar','id'=>'profileimg','width'=>'40','height'=>'40']);
						}
						if(!empty($user_id))
						{
							?>
							<span id="username"><?php echo $this->Setting->get_user_id($user_id);?></span>
							<i class="fa fa-angle-down"></i>
						<?php	}
						?>
						</span>
						</a>
						<ul role="menu" class="dropdown-menu dropdown-list">
							<li role="presentation">
							<?php  
							if(!empty($user_id))
							{
								echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-user')) . __('Profile'),['controller' => 'comman', 'action' => 'account'],['escape' => false]);
							}
							?>
							<li role="presentation"><?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-key m-r-xs')) . __('Change Password'),['controller' => 'Changepassword', 'action' => 'changepassword'],['escape' => false]);?>
							<li role="presentation"><?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-sign-out m-r-xs')) . __('Log out'),['controller' => 'User', 'action' => 'logout'],['escape' => false]);?>
						</ul>
					</li>                       
				</ul><!-- Nav -->
			</div><!-- Top Menu -->
		</div>
	</div>
</div>
<?= $this->Flash->render() ?>
<?php } ?>
<div class="row">
	<ul role="tablist" class="nav nav-tabs panel_tabs">	 
		<?php
		if($role == 'admin')
		{
		?>
		<li class="">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Admission List'),['controller' => 'Admission', 'action' => 'admissionlist'],['escape' => false]);?>
		</li>
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus fa-lg')) . __('Admission Form'),['controller' => 'Admission', 'action' => 'registration'],['escape' => false]);?>
		</li>
		<?php
		}
		else{
		?>
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('New Registration'),['controller' => 'Admission', 'action' => 'registration'],['escape' => false]);?>
		</li>
		<?php } ?>
	</ul>	
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'registration']]);?>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Admission Number '));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<?php 					
						$admission_code = $this->Setting->getfieldname('admission_code');
						$next_id = $admission_code.sprintf("%d",$next_id);
						
						echo $this->Form->input('admission_no',array('readonly','value'=>$next_id,'name'=>'admission_no','class'=>'form-control validate[optional,maxSize[20]]','PlaceHolder'=>''));?>												
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Admission Date '));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div class="col-md-4 col-sm-4 col-xs-12 datepickericon">
						<?php echo $this->Form->input('admission_date',array('id'=>'date_of_birth1','name'=>'admission_date','class'=>'form-control validate[required]'));?>
					</div>
				</div>
				
				<legend style="font-weight:900;border-bottom:1px solid #e5e5e5;"><?php echo __('Student Info'); ?></legend>
				
				<div class="form-group">
					<input type="hidden" name="roll_no" value="">
					<input type="hidden" name="adminssion_roll" value="Admission">
					<input type="hidden" name="adminssion_status" value="0">
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('First Name '));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<?php echo $this->Form->input('first_name',array('name'=>'first_name','class'=>'form-control validate[required,custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=>__('Enter First Name')));?>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Middle Name '));?></div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<?php echo $this->Form->input('middle_name',array('name'=>'middle_name','class'=>'form-control validate[custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=>__('Enter Middle Name')));?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Last Name '));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<?php echo $this->Form->input('last_name',array('name'=>'last_name','class'=>'form-control validate[required,custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=>__('Enter Last Name')));?>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Date Of Birth '));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div class="col-md-4 col-sm-4 col-xs-12 datepickericon">
						<?php echo $this->Form->input('',array('id'=>'date_of_birth12','name'=>'date_of_birth','class'=>'form-control validate[required]','PlaceHolder'=>__('Enter Birth Date')));?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Gender '));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<label class="radio-inline label_float radio">
							<?php 
								$options = array('male' => __('Male'), 'female' => __('Female'));
								echo $this->Form->radio('gender',$options);
							?>
						</label>	
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Address '));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<?php echo $this->Form->input('address',array('name'=>'address','class'=>'form-control validate[required,maxSize[150]]','PlaceHolder'=>__('Enter Address ')));?>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('State '));?></div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<?php echo $this->Form->input('', array('name'=>'state','class'=>'form-control','PlaceHolder'=>__('Enter State ')));?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('City '));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<?php echo $this->Form->input('city', array('name'=>'city','class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=>__('Enter City ')));?>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('PinCode '));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<?php echo $this->Form->input('zip_code',array('name'=>'zip_code','class'=>'form-control validate[required,maxSize[10]]','PlaceHolder'=>__('Enter Pin Code')));?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Mobile Number '));?></div>
					<div class="col-md-1 col-sm-1 col-xs-12">
						<?php echo $this->Form->input('mobile_code',array('readonly','class'=>'form-control','name'=>'mobile_code','value'=>'+'.$country_code));?>
					</div>
					<div class="col-md-3 col-sm-6 col-xs-12">
						<?php echo $this->Form->input('mobile_no',array('name'=>'mobile_no','class'=>'form-control','PlaceHolder'=>__('Enter Mobile Number')));?>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Phone Number '));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<?php echo $this->Form->input('',array('name'=>'phone','class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=>__('Enter Phone Number ')));?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Email '));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<?php echo $this->Form->input('',array('name'=>'email','class'=>'form-control validate[required,custom[email],maxSize[50]]','PlaceHolder'=>__('Enter Valid Email ')));?>
					</div>						
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Previous School'));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div class="col-md-3 col-sm-6 col-xs-12">
						<select class="form-control validate[required,maxSize[100]]" name="preschoolname" id="preschoolname" onchange="yesnoCheck(this);">
						<option value=""><?php echo __('-- None --'); ?></option>
						<?php
						$admission_previous_school = $this->Setting->get_admission_main('previous_school');
						foreach($admission_previous_school as $previous_school)
						{
						?>
							<option value="<?php echo $previous_school['adminssion_main_id']; ?>">
								<?php echo $previous_school['title']; ?>
							</option>
						<?php
						}
						?>
						</select>
						<div id="ifYes" style="display: none;">
							<input type="text" id="preschoolother" name="preschoolother" class="form-control validate[required]" />
						</div>
					</div>
					<div class="col-md-1 col-sm-1 col-xs-12">
						<?php
						if($role == 'admin'){
						?>
						<button type="button" class="btn btnview btn-primary viewmodaldata" id='view_section' data-toggle="modal" data-target="#load_modal" data-id="previous_school"><i class="fa fa-edit"></i> <?php echo __('Edit');?></button>							
						<?php } ?>
					</div>
					<input type="hidden" name="role" value="student">
				</div>
				<legend style="font-weight:900;border-bottom:1px solid #e5e5e5;"><?php echo __('Siblings Information'); ?> </legend>						
				<div class="form-group">
					<div class="col-md-6 col-sm-6 col-xs-12" style="display: inline-flex;" id="relationid">		
						<input type="checkbox" id="chkIsTeamLead" />
						&nbsp;&nbsp;&nbsp; <?php echo __('In case of no sibling click here'); ?> </span>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12 relat">	
					<div class="col-md-2 col-sm-3 col-xs-12"><?php echo $this->Form->label(__('Relation Type '));?></div>
					<div class="col-md-2 col-sm-3 col-xs-12"><?php echo $this->Form->label(__('Full Name '));?></div>
					<div class="col-md-2 col-sm-3 col-xs-12"><?php echo $this->Form->label(__('Age '));?></div>
					<div class="col-md-3 col-sm-6 col-xs-12"><?php echo $this->Form->label(__('Standard '));?></div>
					<div class="col-md-2 col-sm-3 col-xs-12"><?php echo $this->Form->label(__('SID '));?></div>
				</div>	
				<br>
				<br>
				<div>
					<div class="col-md-12 col-sm-12 col-xs-12" id="relafirst" style="float:left;padding-bottom: 15px;">
						<div class="col-md-2 col-sm-2 col-xs-12">	
							<label class="radio-inline">
								<input type="radio" name="siblingsone[]" value="Brother" id="txtNumHours2"><?php echo __('Brother'); ?>
							</label>
							<label class="radio-inline">
							  <input type="radio" name="siblingsone[]" value="Sister" id="txtNumHours2"><?php echo __('Sister'); ?>
							</label>						
						</div>
						<div class="col-md-2 col-sm-3 col-xs-12">
							<?php echo $this->Form->input('',array('name'=>'siblingsone[]','class'=>'form-control','id'=>'txtNumHours','PlaceHolder'=> __('Enter Full Name')));?>
						</div>
						<div class="col-md-2 col-sm-3 col-xs-12">
							<?php echo $this->Form->input('',array('name'=>'siblingsone[]','class'=>'form-control','id'=>'txtNumHours1','PlaceHolder'=> __('Enter Age')));?>
						</div>
						<div class="col-md-2 col-sm-3 col-xs-12">
							<select class="form-control standard" name="siblingsone[]" id="txtNumHours3">
								<option value=""><?php echo __('-- Select Standard --'); ?></option>
								<?php
								$admission_previous_school = $this->Setting->get_admission_main('standard');
								foreach($admission_previous_school as $previous_school)
								{
								?>
									<option value="<?php echo $previous_school['adminssion_main_id']; ?>">
										<?php echo $previous_school['title']; ?>
									</option>
								<?php
								}
								?>
							</select>	
						</div>
						<div class="col-md-1 col-sm-1 col-xs-12">
							<?php
							if($role == 'admin'){
							?>
							<button type="button" class="btn btnview btn-primary viewmodaldata" id='view_section1' data-toggle="modal" data-target="#load_modal" data-id="standard"><i class="fa fa-edit"></i> <?php echo __('Edit');?></button>							
							<?php } ?>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<?php echo $this->Form->input('',array('name'=>'siblingsone[]','class'=>'form-control','id'=>'txtNumHours4'));?>
						</div>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12" style="float:left;padding-bottom: 15px;">
						<div class="col-md-2 col-sm-2 col-xs-12">
							<label class="radio-inline">
								<input type="radio" name="siblingstwo[]" value="Brother" id="txtNumHours2"><?php echo __('Brother'); ?>
							</label>
							<label class="radio-inline">
							  <input type="radio" name="siblingstwo[]" value="Sister" id="txtNumHours2"><?php echo __('Sister'); ?>
							</label>	
						</div>
						<div class="col-md-2 col-sm-3 col-xs-12">
							<?php echo $this->Form->input('',array('name'=>'siblingstwo[]','class'=>'form-control','id'=>'txtNumHours','PlaceHolder'=> __('Enter Full Name')));?>
						</div>
						<div class="col-md-2 col-sm-3 col-xs-12">
							<?php echo $this->Form->input('',array('name'=>'siblingstwo[]','class'=>'form-control','id'=>'txtNumHours1','PlaceHolder'=> __('Enter Age')));?>
						</div>
						<div class="col-md-2 col-sm-3 col-xs-12">
							<select class="form-control standard" name="siblingstwo[]" id="txtNumHours3">
								<option value=""><?php echo __('-- Select Standard --'); ?></option>
								<?php
								$admission_previous_school = $this->Setting->get_admission_main('standard');
								foreach($admission_previous_school as $previous_school)
								{
								?>
									<option value="<?php echo $previous_school['adminssion_main_id']; ?>">
										<?php echo $previous_school['title']; ?>
									</option>
								<?php
								}
								?>
							</select>	
						</div>
						<div class="col-md-1 col-sm-1 col-xs-12">
							
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<?php echo $this->Form->input('',array('name'=>'siblingstwo[]','class'=>'form-control','id'=>'txtNumHours4'));?>
						</div>
					</div>										
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="col-md-2 col-sm-2 col-xs-12">
							<label class="radio-inline">
								<input type="radio" name="siblingsthree[]" value="Brother" id="txtNumHours2"><?php echo __('Brother'); ?>
							</label>
							<label class="radio-inline">
							  <input type="radio" name="siblingsthree[]" value="Sister" id="txtNumHours2"><?php echo __('Sister'); ?>
							</label>
						</div>
						<div class="col-md-2 col-sm-3 col-xs-12">
							<?php echo $this->Form->input('',array('name'=>'siblingsthree[]','class'=>'form-control','id'=>'txtNumHours','PlaceHolder'=> __('Enter Full Name')));?>
						</div>
						<div class="col-md-2 col-sm-3 col-xs-12">
							<?php echo $this->Form->input('',array('name'=>'siblingsthree[]','class'=>'form-control','id'=>'txtNumHours1','PlaceHolder'=> __('Enter Age')));?>
						</div>
						<div class="col-md-2 col-sm-3 col-xs-12">
							<select class="form-control standard" name="siblingsthree[]" id="txtNumHours3">
								<option value=""><?php echo __('-- Select Standard --'); ?></option>
								<?php
								$admission_previous_school = $this->Setting->get_admission_main('standard');
								foreach($admission_previous_school as $previous_school)
								{
								?>
									<option value="<?php echo $previous_school['adminssion_main_id']; ?>">
										<?php echo $previous_school['title']; ?>
									</option>
								<?php
								}
								?>
							</select>	
						</div>
						<div class="col-md-1 col-sm-1 col-xs-12">
							
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<?php echo $this->Form->input('',array('name'=>'siblingsthree[]','class'=>'form-control','id'=>'txtNumHours4'));?>
						</div>
					</div>
				</div>	
					<br>
					<br>
					<br>		
				<legend style="font-weight:900;border-bottom:1px solid #e5e5e5;"><?php echo __('Family Information'); ?> </legend>		
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Parental Status '));?></div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<label class="radio-inline" id="sinfather">
						  <input type="radio" name="pstatus" ><?php echo __('Single Father'); ?> 
						</label>
						<label class="radio-inline" id="sinmother">
						  <input type="radio" name="pstatus"><?php echo __('Single Mother'); ?> 
						</label>
						<label class="radio-inline" id="boths">
						  <input type="radio" name="pstatus"><?php echo __('Both'); ?> 
						</label>
					</div>
				</div>	
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Relationship'));?></div>
					<div id="fatid">		
						<div class="col-md-4 col-sm-4 col-xs-12 labfat"><?php echo $this->Form->label(__('FATHER'));?></div>
						<div class="col-md-2 col-sm-2 col-xs-12"></div>
					</div>						
					<div id="motid">									
						<div class="col-md-4 col-sm-4 col-xs-12 labfat"><?php echo $this->Form->label(__('MOTHER'));?></div>								
					</div>
				</div>			
				<div class="form-group">			
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Salutation'));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div id="fatid1">	
						<div class="col-md-4 col-sm-4 col-xs-12">
							<select class="form-control validate[required]" name="fathersalutation" id="fathersalutation">
							<option value="Mr"><?php echo __('Mr'); ?></option>
							</select>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12"></div>
					</div>					
					<div id="motid1">				
						<div class="col-md-4 col-sm-4 col-xs-12" >
							<select class="form-control validate[required]" name="mothersalutation" id="mothersalutation">
							<option value="Ms"><?php echo __('Ms'); ?></option>
							<option value="Mrs"><?php echo __('Mrs'); ?></option>
							<option value="Miss"><?php echo __('Miss'); ?></option>
							</select>
						</div>
					</div>															
				</div>	
				<div class="form-group">					
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('First Name '));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div id="fatid2">	
						<div class="col-md-4 col-sm-4 col-xs-12">
							<?php echo $this->Form->input('fatherfn',array('name'=>'fatherfn','class'=>'form-control validate[required,custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=> __('Enter First Name')));?>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12"></div>
					</div>	
					<div id="motid2">								
						<div class="col-md-4 col-sm-4 col-xs-12">
							<?php echo $this->Form->input('motherfn',array('name'=>'motherfn','class'=>'form-control validate[required,custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=> __('Enter First Name')));?>
						</div>
					</div>
				</div>
				<div class="form-group">				
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Middle Name '));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div id="fatid3">	
						<div class="col-md-4 col-sm-4 col-xs-12">
							<?php echo $this->Form->input('fathermn',array('name'=>'fathermn','class'=>'form-control validate[required,custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=> __('Enter Middle Name')));?>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12"></div>
					</div>
					<div id="motid3">			
						<div class="col-md-4 col-sm-4 col-xs-12">
							<?php echo $this->Form->input('mothermn',array('name'=>'mothermn','class'=>'form-control validate[required,custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=> __('Enter Middle Name')));?>
						</div>
					</div>	
				</div>		
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Last Name '));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div id="fatid4">	
						<div class="col-md-4 col-sm-4 col-xs-12">
							<?php echo $this->Form->input('fatherln',array('name'=>'fatherln','class'=>'form-control validate[required,custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=> __('Enter Last Name')));?>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12"></div>
					</div>
					<div id="motid4">							
						<div class="col-md-4 col-sm-4 col-xs-12">
							<?php echo $this->Form->input('motherln',array('name'=>'motherln','class'=>'form-control validate[required,custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=> __('Enter Last Name')));?>
						</div>
					</div>	
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Email '));?></div>
					<div id="fatid5">	
						<div class="col-md-4 col-sm-4 col-xs-12">
							<?php echo $this->Form->input('fatheremail',array('name'=>'fatheremail','class'=>'form-control','PlaceHolder'=> __('Enter Email')));?>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12"></div>
					</div>
					<div id="motid5">							
						<div class="col-md-4 col-sm-4 col-xs-12">
							<?php echo $this->Form->input('motheremail',array('name'=>'motheremail','class'=>'form-control','PlaceHolder'=> __('Enter Email')));?>
						</div>
					</div>	
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Mobile No '));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div id="fatid6">	
						<div class="col-md-4 col-sm-4 col-xs-12">
							<?php echo $this->Form->input('fathermob',array('name'=>'fathermob','class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=>__('Enter Mobile Number')));?>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12"></div>
					</div>
					<div id="motid6">							
						<div class="col-md-4 col-sm-4 col-xs-12">
							<?php echo $this->Form->input('mothermob',array('name'=>'mothermob','class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=>__('Enter Mobile Number')));?>
						</div>
					</div>	
				</div>
				<div class="form-group">	
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('School Name '));?></div>
					<div id="fatid7">	
						<div class="col-md-4 col-sm-4 col-xs-12">
							<?php /*
							<select class="form-control parent_school" name="fatherschool" id="fatherschool" onchange="schoolCheck(this);">
							<option value=""><?php echo __('-- Select School --'); ?></option>
							<?php
							$admission_previous_school = $this->Setting->get_admission_main('parent_school');
							foreach($admission_previous_school as $previous_school)
							{
							?>
								<option value="<?php echo $previous_school['adminssion_main_id']; ?>">
									<?php echo $previous_school['title']; ?>
								</option>
							<?php
							}
							?>
							</select>
							<div class="col-md-1 col-sm-1 col-xs-12">
								<?php
								if($role == 'admin'){
								?>
								<button type="button" class="btn btnview btn-primary viewmodaldata" id='view_section2' data-toggle="modal" data-target="#load_modal" data-id="parent_school"><i class="fa fa-edit"></i> <?php echo __('Edit');?></button>							
								<?php } ?>
							</div>
							*/ ?>
							<div id="fatherschoolother">
								<?php echo $this->Form->input('',array('name'=>'fatherschoolother','class'=>'form-control','value'=>'','PlaceHolder'=> __('Enter School Name')));?>
							</div>
						</div>
						
					</div>	
					<div class="col-md-2 col-sm-2 col-xs-12 label_float" id="fatid7"></div>
					<div id="motid7">			
						<div class="col-md-4 col-sm-4 col-xs-12">
							<?php /*
							<select class="form-control parent_school" name="motherschool" id="motherschool" onchange="motschoolCheck(this);">
							<option value=""><?php echo __('-- Select School --'); ?></option>
							<?php
							$admission_previous_school = $this->Setting->get_admission_main('parent_school');
							foreach($admission_previous_school as $previous_school)
							{
							?>
								<option value="<?php echo $previous_school['adminssion_main_id']; ?>">
									<?php echo $previous_school['title']; ?>
								</option>
							<?php
							}
							?>
							</select>
							<div class="col-md-1 col-sm-1 col-xs-12">
								<?php
								if($role == 'admin'){
								?>
								<button type="button" class="btn btnview btn-primary viewmodaldata" id='view_section2' data-toggle="modal" data-target="#load_modal" data-id="parent_school"><i class="fa fa-edit"></i> <?php echo __('Edit');?></button>							
								<?php } ?>
							</div>
							*/ ?>
							<div id="motherschoolother">
								<?php echo $this->Form->input('',array('name'=>'motherschoolother','class'=>'form-control','id'=>'motherschoolother','PlaceHolder'=> __('Enter School Name')));?>
							</div>
						</div>
						
					</div>		
				</div>
				
				<div class="form-group">		
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Medium of Instruction '));?></div>
					<div id="fatid8">	
						<div class="col-md-3 col-sm-6 col-xs-12">
							<select class="form-control school_medium" name="fathermedium" id="fathermedium" onchange="famediumCheck(this);">
							<option value=""><?php echo __('-- Select Medium --'); ?></option>
							<?php
							$admission_previous_school = $this->Setting->get_admission_main('school_medium');
							foreach($admission_previous_school as $previous_school)
							{
							?>
								<option value="<?php echo $previous_school['adminssion_main_id']; ?>">
									<?php echo $previous_school['title']; ?>
								</option>
							<?php
							}
							?>
							</select>
							<div id="fathermediumother" style="display: none;">
								<?php echo $this->Form->input('',array('name'=>'fathermediumother','class'=>'form-control','id'=>'fathermediumother','PlaceHolder'=> __('Enter Medium of School')));?>
							</div>
						</div>
						<div class="col-md-1 col-sm-1 col-xs-12">
							<?php
							if($role == 'admin'){
							?>
							<button type="button" class="btn btnview btn-primary viewmodaldata" id='view_section3' data-toggle="modal" data-target="#load_modal" data-id="school_medium"><i class="fa fa-edit"></i> <?php echo __('Edit');?></button>							
							<?php } ?>
						</div>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12 label_float" id="fatid8"></div>
					<div id="motid8">								
						<div class="col-md-3 col-sm-6 col-xs-12">
							<select class="form-control school_medium" name="mothermedium" id="mothermedium" onchange="momediumCheck(this);">
							<option value=""><?php echo __('-- Select Medium --'); ?></option>
							<?php
							$admission_previous_school = $this->Setting->get_admission_main('school_medium');
							foreach($admission_previous_school as $previous_school)
							{
							?>
								<option value="<?php echo $previous_school['adminssion_main_id']; ?>">
									<?php echo $previous_school['title']; ?>
								</option>
							<?php
							}
							?>
							</select>
							<div id="mothermediumother" style="display: none;">
								<?php echo $this->Form->input('',array('name'=>'abc','class'=>'form-control','id'=>'mothermediumother','PlaceHolder'=> __('Enter Medium of School')));?>
							</div>
						</div>
						<div class="col-md-1 col-sm-1 col-xs-12">
							<?php
							if($role == 'admin'){
							?>
							<button type="button" class="btn btnview btn-primary viewmodaldata" id='view_section3' data-toggle="modal" data-target="#load_modal" data-id="school_medium"><i class="fa fa-edit"></i> <?php echo __('Edit');?></button>							
							<?php } ?>
						</div>
					</div>		
				</div>
				
				<div class="form-group">	
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Educational Qualification '));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div id="fatid9">	
						<div class="col-md-3 col-sm-6 col-xs-12">
							<select class="form-control validate[required,maxSize[50]] qualification" name="fatherhighest" id="fatherhighest">
							<option value=""><?php echo __('-- Select Qualification --'); ?></option>
							<?php
							$admission_previous_school = $this->Setting->get_admission_main('qualification');
							foreach($admission_previous_school as $previous_school)
							{
							?>
								<option value="<?php echo $previous_school['adminssion_main_id']; ?>">
									<?php echo $previous_school['title']; ?>
								</option>
							<?php
							}
							?>
							</select>
						</div>
						<div class="col-md-1 col-sm-1 col-xs-12">
							<?php
							if($role == 'admin'){
							?>
							<button type="button" class="btn btnview btn-primary viewmodaldata" id='view_section3' data-toggle="modal" data-target="#load_modal" data-id="qualification"><i class="fa fa-edit"></i> <?php echo __('Edit');?></button>							
							<?php } ?>
						</div>
					</div>	
					<div class="col-md-2 col-sm-2 col-xs-12 label_float" id="fatid9"></div>					
					<div id="motid9">					
						<div class="col-md-3 col-sm-6 col-xs-12">
							<select class="form-control validate[required,maxSize[50]] qualification" name="motherhighest" id="motherhighest">
							<option value=""><?php echo __('-- Select Qualification --'); ?></option>
							<?php
							$admission_previous_school = $this->Setting->get_admission_main('qualification');
							foreach($admission_previous_school as $previous_school)
							{
							?>
								<option value="<?php echo $previous_school['adminssion_main_id']; ?>">
									<?php echo $previous_school['title']; ?>
								</option>
							<?php
							}
							?>
							</select>
						</div>
						<div class="col-md-1 col-sm-1 col-xs-12">
							<?php
							if($role == 'admin'){
							?>
							<button type="button" class="btn btnview btn-primary viewmodaldata" id='view_section3' data-toggle="modal" data-target="#load_modal" data-id="qualification"><i class="fa fa-edit"></i> <?php echo __('Edit');?></button>							
							<?php } ?>
						</div>
					</div>		
				</div>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Annual Income'));?></div>
					<div id="fatid10">	
						<div class="col-md-4 col-sm-4 col-xs-12">
							<?php echo $this->Form->input('',array('name'=>'fatheincome','class'=>'form-control validate[custom[onlyNumberSp]]','PlaceHolder'=>__('Enter Annual Income')));?>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12"></div>
					</div>
					<div id="motid10">							
						<div class="col-md-4 col-sm-4 col-xs-12">
							<?php echo $this->Form->input('',array('name'=>'motherincome','class'=>'form-control validate[custom[onlyNumberSp]]','PlaceHolder'=>__('Enter Annual Income')));?>
						</div>
					</div>	
				</div>
				<div class="form-group">			
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Occupation '));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div id="fatid11">	
						<div class="col-md-3 col-sm-6 col-xs-12">
							<select class="form-control validate[required,maxSize[50]] occupation" name="fatheroccu" id="fatheroccu" onchange="fatheroccuCheck(this);">
							<option value=""><?php echo __('-- Select Occupation --'); ?></option>
							<?php
							$admission_previous_school = $this->Setting->get_admission_main('occupation');
							foreach($admission_previous_school as $previous_school)
							{
							?>
								<option value="<?php echo $previous_school['adminssion_main_id']; ?>">
									<?php echo $previous_school['title']; ?>
								</option>
							<?php
							}
							?>
							</select>
							<div id="fatheroccuother" style="display: none;">
								<?php echo $this->Form->input('',array('name'=>'myoccupa','class'=>'form-control validate[required]','id'=>'fatheroccuother','PlaceHolder'=> __('Enter Occupation')));?>
							</div>
						</div>
						<div class="col-md-1 col-sm-1 col-xs-12">
							<?php
							if($role == 'admin'){
							?>
							<button type="button" class="btn btnview btn-primary viewmodaldata" id='view_section4' data-toggle="modal" data-target="#load_modal" data-id="occupation"><i class="fa fa-edit"></i> <?php echo __('Edit');?></button>							
							<?php } ?>
						</div>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12 label_float" id="fatid11"></div>	
					<div id="motid11">							
						<div class="col-md-3 col-sm-6 col-xs-12">
							<select class="form-control validate[required,maxSize[50]] occupation" name="motheroccu" id="motheroccu" onchange="motheroccuCheck(this);">
							<option value=""><?php echo __('-- Select Occupation --'); ?></option>
							<?php
							$admission_previous_school = $this->Setting->get_admission_main('occupation');
							foreach($admission_previous_school as $previous_school)
							{
							?>
								<option value="<?php echo $previous_school['adminssion_main_id']; ?>">
									<?php echo $previous_school['title']; ?>
								</option>
							<?php
							}
							?>
							</select>
							<div id="motheroccuother" style="display: none;">
								<?php echo $this->Form->input('',array('name'=>'occupationmother','class'=>'form-control validate[required]','id'=>'motheroccuother','PlaceHolder'=> __('Enter Occupation')));?>
							</div>					
						</div>
						<div class="col-md-1 col-sm-1 col-xs-12">
							<?php
							if($role == 'admin'){
							?>
							<button type="button" class="btn btnview btn-primary viewmodaldata" id='view_section4' data-toggle="modal" data-target="#load_modal" data-id="occupation"><i class="fa fa-edit"></i> <?php echo __('Edit');?></button>							
						<?php } ?>
						</div>
					</div>									
				</div>			
				<div class="form-group">					
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Proof of Qualification '));?></div>
					<div id="fatid12">	
						<div class="col-md-4 col-sm-4 col-xs-12">
							<?php echo $this->Form->input('',array('class'=>'file','id'=>'file-0a','type'=>'file','name'=>'fatdocume','PlaceHolder'=> __('Upload Document')));?>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12"></div>
					</div>
					<div id="motid12">	
						<div class="col-md-4 col-sm-4 col-xs-12">
							<?php echo $this->Form->input('',array('class'=>'file','id'=>'file-0a','type'=>'file','name'=>'motdocume','PlaceHolder'=> __('Upload Document')));?>
						</div>
					</div>								
					<input type="hidden" name="status" value="Not Approved">
				</div>					
				<div class="form-group">
					<div class="col-md-3 col-sm-6 col-xs-12"></div>
					<div class="col-md-4 col-sm-4 col-xs-12 regi">
						<?php echo $this->Form->input(__('New Admission'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
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
<script>
jQuery('body').on('click', '.viewmodaldata', function() {
	var model  = jQuery(this).attr('data-id') ;
	var curr_data = {class_id : model};	 
	if(model == 'previous_school')
	{
		jQuery.ajax({
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Admission','action'=>'viewsectionlist'));?>",
				data:curr_data,
				async:false,
				success: function(response){
					jQuery('#previous_school').html(response);							
				},
				error: function(e) {
						console.log(e);
				}
		});			
	}
	else if(model == 'standard')
	{		
		jQuery.ajax({
	        type:"POST",
	        url:"<?php echo Router::url(array('controller'=>'Admission','action'=>'viewstandardlist'));?>",
	        data:curr_data,
	        async:false,
	        success: function(response){
				jQuery('#previous_school').html(response);							
	        },
	        error: function(e) {
	                console.log(e);
	        }
		});
	}
	else if(model == 'parent_school')
	{
		jQuery.ajax({
	        type:"POST",
	        url:"<?php echo Router::url(array('controller'=>'Admission','action'=>'viewparentschoollist'));?>",
	        data:curr_data,
	        async:false,
	        success: function(response){
				jQuery('#previous_school').html(response);							
	        },
	        error: function(e) {
	                console.log(e);
	        }
		});
	}
	else if(model == 'school_medium')
	{
		jQuery.ajax({
	        type:"POST",
	        url:"<?php echo Router::url(array('controller'=>'Admission','action'=>'viewmediumlist'));?>",
	        data:curr_data,
	        async:false,
	        success: function(response){
				jQuery('#previous_school').html(response);							
	        },
	        error: function(e) {
	                console.log(e);
	        }
		});
	}
	else if(model == 'qualification')
	{
		jQuery.ajax({
	        type:"POST",
	        url:"<?php echo Router::url(array('controller'=>'Admission','action'=>'viewqualificationlist'));?>",
	        data:curr_data,
	        async:false,
	        success: function(response){
				jQuery('#previous_school').html(response);							
	        },
	        error: function(e) {
	                console.log(e);
	        }
		});
	}
	else if(model == 'occupation')
	{
		jQuery.ajax({
	        type:"POST",
	        url:"<?php echo Router::url(array('controller'=>'Admission','action'=>'viewoccupationlist'));?>",
	        data:curr_data,
	        async:false,
	        success: function(response){
				jQuery('#previous_school').html(response);							
	        },
	        error: function(e) {
	                console.log(e);
	        }
		});
	}
}); 
jQuery("body").on("click", ".btn-add-cat", function(){
		
	var class_id  = jQuery('#class_id').val() ;
	var adminssion_main_id  = jQuery('#adminssion_main_id').val() ;
	var term_name  = jQuery("#txtfee_type").val();	

	var curr_data = {					
			class_id:class_id,			
			term_name:term_name,			
			dataType: 'json'
			};
			
			jQuery.ajax({
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Admission','action'=>'addnewsection'));?>",
				data:curr_data,
				async:false,
				success: function(response){ 	
					
					if(response != 'false')
					{		
						var json_obj = jQuery.parseJSON(response);	
						jQuery('#class_section').append(json_obj['html']);	
						if(class_id == 'previous_school')
							jQuery('#preschoolname').append(json_obj['select']);
						else if(class_id == 'standard')
							jQuery('.standard').append(json_obj['select']);
						else if(class_id == 'parent_school')
							jQuery('.parent_school').append(json_obj['select']);
						else if(class_id == 'school_medium')
							jQuery('.school_medium').append(json_obj['select']);
						else if(class_id == 'qualification')
							jQuery('.qualification').append(json_obj['select']);
						else if(class_id == 'occupation')
							jQuery('.occupation').append(json_obj['select']);
					}
					else
						alert("Enter Value");
					
					jQuery("#txtfee_type").val("");
				},
				
				error: function(e) {
						console.log(e.responseText);
						 }
		});			
});	
jQuery("body").on("click", ".edit-term", function(){
	
	var term_id  = jQuery(this).attr('data-id') ;	
	var model  = jQuery(this).attr('data-type') ;	
	 	
	var curr_data = {					
			model : model,
			class_section_id:term_id,			
			dataType: 'json'
			};
			
			jQuery.ajax({
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Admission','action'=>'editterm'));?>",
				data:curr_data,
				async:false,
				success: function(response){ 								   
					jQuery('#term-'+term_id).html(response);
					
				},
				beforeSend:function(){
							jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
						},
				error: function(e) {
						console.log(e);
						 }
		});				
});
jQuery("body").on("click", ".btn-cat-update-cancel", function(){
	
	var term_id  = jQuery(this).attr('data-id') ;	
	var model  = jQuery(this).attr('data-type') ;	
		
	var curr_data = {					
			model : model,
			class_section_id:term_id,			
			dataType: 'json'
			};
			
			jQuery.ajax({
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Admission','action'=>'cancelterm'));?>",
				data:curr_data,
				async:false,
				success: function(response){ 								   
					jQuery('#term-'+term_id).html(response);
					
				},
				error: function(e) {
						console.log(e);
						 }
		});				
});	
jQuery("body").on("click", ".btn-cat-update", function(){
	
	var term_id  = jQuery(this).attr('id') ;	
	var model  = jQuery(this).attr('data-type') ;
	
	var term_name  = jQuery("#section_name").val();	
	
	var curr_data = {					
			model : model,
			class_section_id:term_id,			
			term_name:term_name,			
			dataType: 'json'
			};
			
			jQuery.ajax({
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Admission','action'=>'saveterm'));?>",
				data:curr_data,
				async:false,
				success: function(response){ 								   
					var json_obj = jQuery.parseJSON(response);
					jQuery('#term-'+term_id).html(json_obj['html']);	
					if(model == 'previous_school')
						jQuery("#preschoolname option[value="+term_id+"]").text(json_obj['select']);
					else if(model == 'standard')
						jQuery(".standard option[value="+term_id+"]").text(json_obj['select']);
					else if(model == 'parent_school')
						jQuery(".parent_school option[value="+term_id+"]").text(json_obj['select']);
					else if(model == 'school_medium')
						jQuery(".school_medium option[value="+term_id+"]").text(json_obj['select']);
					else if(model == 'qualification')
						jQuery(".qualification option[value="+term_id+"]").text(json_obj['select']);
					else if(model == 'occupation')
						jQuery(".occupation option[value="+term_id+"]").text(json_obj['select']);
				},

				error: function(e) {
						console.log(e);
						 }
		});				
});	
jQuery("body").on("click", ".remove-term", function(){
		
	var term_id  = jQuery(this).attr('data-id') ;	
	var model  = jQuery(this).attr('data-type') ;	
	
	if(confirm("Are you sure want to delete this?"))
	{
		
		var curr_data = {					
				model : model,
				class_section_id:term_id,			
				dataType: 'json'
				};
				
				jQuery.ajax({
					type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Admission','action'=>'deleteterm'));?>",
					data:curr_data,
					async:false,
					success: function(response){ 								   
						jQuery('#term-'+term_id).hide();
						if(model == 'previous_school')
							jQuery("#preschoolname option[value="+term_id+"]").remove();
						else if(model == 'standard')
							jQuery(".standard option[value="+term_id+"]").remove();
						else if(model == 'parent_school')
							jQuery(".parent_school option[value="+term_id+"]").remove();
						else if(model == 'school_medium')
							jQuery(".school_medium option[value="+term_id+"]").remove();
						else if(model == 'qualification')
							jQuery(".qualification option[value="+term_id+"]").remove();
						else if(model == 'occupation')
							jQuery(".occupation option[value="+term_id+"]").remove();
					},
					
					error: function(e) {
							console.log(e);
							 }
			});			
	}
	
});	
</script>
<div class="modal fade modal-white in custom-model" id="load_modal" tabindex="-1" role="dialog" aria-labelledby="load_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content" id="previous_school">
	  
	  </div>
    </div>
</div>