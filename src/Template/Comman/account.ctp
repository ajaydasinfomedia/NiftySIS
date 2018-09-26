<script>
	$(function(){
		$('body').on('click', '#personal_info', function() {

				var address=$('#address').val();
				var city=$('#city').val();
				var state=$('#state').val();
				var phone=$('#phone').val();
				var email=$('#email').val();

				 if(address == '' || city == '' || phone == '' || email == ''){
				$('#result').html('<center><div class="alert alert-info">Info! Fill Textbox Required !</div></center>');
					}else{	


				$.ajax({

						type:'POST',
						url:'<?php echo $this->Url->build(["controller"=>"comman","action"=>"addpersonal"]); ?>',
						data:{
							address:address,
							city:city,
							state:state,
							phone:phone,
							email:email
						},

						success:function(getdata){
							$("#result").html(getdata);
						},

						error:function(e){
							console.log(e);
						},
						beforeSend:function(){
						$('#personal_info').attr('value','Loading...');
					},
					complete:function(){
						$('#personal_info').attr('value','Save');
					}
				});
			}
		});
		$('body').on('click', '#password_info', function() {
			var oldpass=$('#old_password').val();
			var newpass=$('#new_password').val();
			var conform=$('#con_password').val();

			if(oldpass == ''){
 				$('#changeresult').html('<center><div class="alert alert-info">Info! Please Enter Old Password</div></center>');
			}else if(newpass == ''){
				 $('#changeresult').html('<center><div class="alert alert-info">Info! Please Enter New Password</div></center>');
			}else if(conform == ''){
				$('#changeresult').html('<center><div class="alert alert-info">Info! Please Fill Confirm Password !</div></center>');
			}else if(newpass != conform){
				$('#changeresult').html('<center><div class="alert alert-info">Info! New Password And Confirm Passwod is not Match</div></center>');
			}else{
			$.ajax({

					type:'POST',
					url:'<?php echo $this->Url->build(["controller"=>"comman","action"=>"changepassword"]); ?>',
					data:{
						old_password:oldpass,
						new_password:newpass,
						conform_password:conform
					},

					success:function(getdata){
						$("#changeresult").html(getdata);
					},

					error:function(e){
						console.log(e);
					},

					beforeSend:function(){
						$('#password_info').attr('value','Loading...');
					},
					complete:function(){
						$('#password_info').attr('value','Save');
					}

			});

		}
		});
	});
</script>

<div class="modal fade " id="myModal1" role="dialog">
	<form id='formID' method="post" enctype="multipart/form-data" action="<?php echo $this->request->base."/User/profileupload";?>">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header" >
				<a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
				<h4 class="modal-title"> <?php echo __('Profile Image Change');?></h4>
			</div>
			<div class="modal-body" >
				<div class="row">			
					<div class="form-group">
						<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label(__('Profile Image '));?><span style="color:red;"><?php echo " *"; ?></span></div>
						<div class="col-md-10 col-sm-10 col-xs-12">							
							<input id="file-0a"  type="file" name="image" class="file validate[required]" accept=".png, .jpg, .jpeg, .gif">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer" style="text-align: left;">
				<button type="submit" class="btn btn-success" id="profilechange"> <?php echo __('Done'); ?> </button>
			</div>	
        </div>
    </div>
	</form>
</div>
<html>
	<head>
		<style type="text/css">
		.profile-cover{
			background-image: url("<?php echo $this->request->webroot; ?>/img/<?php echo $school_profile; ?>");
			background-size: cover;
			background-repeat: no-repeat;	
		}
		</style>
	</head>
	<body>
	<div class="main_wrapper account_main">
		<div class="profile-cover">
			<div class="row">
				<div class="col-md-3 profile-image">
					<div class="profile-image-container" style="text-align: center;">
						<?php echo $this->Html->image($comman_info['image'],array('height'=>'150px','width'=>'150px','class'=>'img-circle'));?>
						<?php
						$user_session_id=$this->request->session()->read('user_id');
						$role=$this->Setting->get_user_role($user_session_id);
						if($role == 'admin'){
						?>
						<button type="button" class="viewdetail btn-success" data-id="<?php echo $user_session_id;?>" data-toggle="modal" data-target="#myModal1" style="margin-top: 8px;border-radius: 4px;"><?php echo __('Change Profile Image');?></button>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div id="main-wrapper" class="account_main">
			<div class="row">
				<div class="col-md-3 user-profile">
					<h3 class="text-center"><?php echo $comman_info['first_name'] .' '.$comman_info['last_name'];?></h3>
					<p class="text-center">
						<b></b>
						<?php
						if($role == 'student') { ?>
							<b> <?php echo __('Class :'); ?> </b><?php echo $class;?>
						<?php }
						if($role == 'teacher') { ?>
							<?php echo $subject;?> <?php echo __('Teacher'); ?>
						<?php }
						?>
					</p>
					<hr>
					<ul class="list-unstyled text-center">
						<li>
							<p>
								<i class="fa fa-map-marker m-r-xs"></i>
								<a href="#"><?php echo $comman_info['address'].','.$comman_info['city'];?></a>
							</p>
						</li>
						<li>
							<i class="fa fa-envelope m-r-xs"></i>
							<a href="#"><?php echo $comman_info['email'];?></a><p></p>
							<p></p>
						</li>
					</ul>
				</div>
				<div class="col-md-6 m-t-lg">
					<div id="changeresult"></div>
					<div class="panel panel-white">
						<div class="panel-heading">
							<div class="panel-title"><?php echo __('Account Settings');?> </div>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<label class="label_float clo-md-2 col-sm-2 col-xs-12"></label>
								<div class="clo-md-8 col-sm-8 col-xs-12">
									<p></p>
										<h4 class="bg-danger"></h4>
									<p></p>
								</div>
							</div>
							<div class="form-group">
								<div class="label_float clo-md-3 col-sm-3 col-xs-12"><?php echo $this->Form->label(__('Name'));?></div>
								<div class="clo-md-9 col-sm-9 col-xs-12">
									<?php echo $this->Form->input('full_name',array('name'=>'full_name','value'=>$comman_info['first_name'].' '.$comman_info['last_name'],'class'=>'form-control validate[required]','PlaceHolder'=>'Enter full Name','readonly'));?>
								</div>
							</div>
							<div class="form-group">
								<div class="label_float clo-md-3 col-sm-3 col-xs-12"><?php echo $this->Form->label(__('Username'));?></div>
								<div class="clo-md-9 col-sm-9 col-xs-12">
									<?php echo $this->Form->input('firstname',array('name'=>'firstname','value'=>$comman_info['first_name'],'class'=>'form-control validate[required]','PlaceHolder'=>'Enter Name','readonly'));?>
								</div>
							</div>
							<div class="form-group">
								<div class="label_float clo-md-3 col-sm-3 col-xs-12"><?php echo $this->Form->label(__('Current Password'));?></div>
								<div class="clo-md-9 col-sm-9 col-xs-12">
									<?php echo $this->Form->password('',array('name'=>'current_password','id'=>'old_password','value'=>'','class'=>'form-control validation[required]','PlaceHolder'=>'Current password'));?>
								</div>
							</div>
							<div class="form-group">
								<div class="label_float clo-md-3 col-sm-3 col-xs-12"><?php echo $this->Form->label(__('New Password'));?></div>
								<div class="clo-md-9 col-sm-9 col-xs-12">
									<?php echo $this->Form->password('',array('name'=>'new_password','id'=>'new_password','value'=>'','class'=>'form-control','PlaceHolder'=>'New password'));?>
								</div>
							</div>
							<div class="form-group">
								<div class="label_float clo-md-3 col-sm-3 col-xs-12"><?php echo $this->Form->label(__('Confirm Password'));?></div>
								<div class="clo-md-9 col-sm-9 col-xs-12">
									<?php echo $this->Form->password('',array('name'=>'conform_password','value'=>'','id'=>'con_password','class'=>'form-control','PlaceHolder'=>'Confirm password'));?>
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-offset-3 clo-md-9 col-sm-9 col-xs-12">
									<?php 
									
										echo $this->Form->input(__('Save'),array('type'=>'submit','id'=>'password_info','name'=>'add','class'=>'btn btn-success'));
									
									?>
								</div>
							</div>
						</div>
					</div>	
					
					<div id="result"></div>
					
					<div class="panel panel-white">
						<div class="panel-heading">
							<div class="panel-title"><?php echo __('Account Settings');?></div>
						</div>
						<div class="panel-body">
							<div class="form-group">
								<div class="label_float clo-md-3 col-sm-3 col-xs-12">
									
										<?php echo $this->Form->label(__('Address'));?><?php echo " *"; ?></span>
									
								</div>
								<div class="clo-md-9 col-sm-9 col-xs-12">
									<?php echo $this->Form->input('',array('name'=>'address','id'=>'address','value'=>$comman_info['address'],'class'=>'form-control validate[required]','PlaceHolder'=>'Address'));?>
								</div>
							</div>
							<div class="form-group">
								<div class="label_float clo-md-3 col-sm-3 col-xs-12">
									
										<?php echo $this->Form->label(__('City'));?><?php echo " *"; ?></span>
									
								</div>
								<div class="clo-md-9 col-sm-9 col-xs-12">
									<?php echo $this->Form->input('',array('name'=>'city','id'=>'city','value'=>$comman_info['city'],'class'=>'form-control validate[required]','PlaceHolder'=>'City'));?>
								</div>
							</div>
							<div class="form-group">
								<div class="label_float clo-md-3 col-sm-3 col-xs-12">
									
										<?php echo $this->Form->label(__('State'));?>
									
								</div>
								<div class="clo-md-9 col-sm-9 col-xs-12">
									<?php echo $this->Form->input('',array('name'=>'state','id'=>'state','value'=>$comman_info['state'],'class'=>'form-control validate[required]','PlaceHolder'=>'State'));?>
								</div>
							</div>
							<div class="form-group">
								<div class="label_float clo-md-3 col-sm-3 col-xs-12">
									
										<?php echo $this->Form->label(__('Phone'));?><?php echo " *"; ?></span>
									
								</div>
								<div class="clo-md-9 col-sm-9 col-xs-12">
									<?php echo $this->Form->input('',array('name'=>'phone','id'=>'phone','value'=>$comman_info['phone'],'class'=>'form-control validate[required]','PlaceHolder'=>'Phone'));?>
								</div>
							</div>
							<div class="form-group">
								<div class="label_float clo-md-3 col-sm-3 col-xs-12">
									
										<?php echo $this->Form->label(__('Email'));?><?php echo " *"; ?></span>								
									
								</div>
								<div class="clo-md-9 col-sm-9 col-xs-12">
									<?php echo $this->Form->input('',array('name'=>'email','id'=>'email','value'=>$comman_info['email'],'class'=>'form-control validate[required,custom[email]]','PlaceHolder'=>'Email'));?>
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-offset-3 clo-md-9 col-sm-9 col-xs-12">
									<?php echo $this->Form->input(__('Save'),array('type'=>'submit','id'=>'personal_info','name'=>'add','class'=>'btn btn-success'));?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
			if($role=='student')
			{ ?>
			<div class="col-md-3 m-t-lg">
				<div class="panel panel-white">
					<div class="panel-heading">
						<div class="panel-title"><?php echo __('Parent');?> </div>
					</div>
					<div class="panel-body">
						<div class="team">
							<?php
							if(isset($data))
							{
							
								foreach($data as $user_parent)
								{ 	
								
								?>
									<div class="team-member">
										<image src="../img/<?php echo $user_parent['image']; ?>" alt="none" height='40px' width='50px' class='profileimg'/>
										<span><?php echo $user_parent['name']; ?></span>
										<br>
										<small><?php echo $user_parent['relation']; ?></small>
									</div>
						<?php	
								}	
							}
							else
								echo "No Any Child";
						?>	
					</div>
				</div>
			</div>
			</div>
			<?php }
			else if($role == 'parent')
			{
			?>

			<div class="col-md-3 m-t-lg">
				<div class="panel panel-white">
					<div class="panel-heading">
						<div class="panel-title"><?php echo __('Child');?> </div>
					</div>
					<div class="panel-body">
						<div class="team">
							
							<?php
							
								foreach($child_identify as $child_info)
								{ 	
									
																					
												?>
									<div class="team-member" style="width:100%">
										<image src="../images/<?php echo $this->Setting->get_user_image($child_info);?>" alt="none" height='40px' width='50px' class='profileimg'/>
										<span><?php echo $this->Setting->get_user_id($child_info); ?></span>
										<br>
										<small>
											<?php
											$class_id = $this->Setting->get_user_class($child_info);
											
											echo __('Class : '.$this->Setting->get_class_id($class_id));
											?>

										</small>
									</div>
						<?php	
							}
									
						?>
					
					</div>
				</div>
			</div>
			</div>
			<?php
			}
			?>
			</div>
			</div>
			</div>
	</body>
</html>