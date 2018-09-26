<script>

$(document).ready(function() {
	$('#message_form').validationEngine();


	$('#sms_sent').click(function(){
	
	
		if($('#sms_sent').is(':checked')){
			$('#message_sent').fadeIn(10);
		}else{
			$('#message_sent').slideUp(0);
		}
	
	
	});
});
</script>

<!--- section ajax ----->
<script>

$( document ).ready(function(){
	
	$("#smgt_select_class").hide();
	$("#smgt_select_section").hide();
	
	$("#to").change(function(){
		var msg_to = $(this).val();
		if(msg_to == 'student' || msg_to == 'parent')
		{
			$("#smgt_select_class").show();	
		}else{
			$("#smgt_select_class").hide();
			$("#smgt_select_section").hide();
		}
	});
	
    $("#class_list").change(function(){
		var class_id = $(this).val();
		 
		if(class_id == 'All')
		{
			$("#smgt_select_section").hide();
		}
		else{
			$("#smgt_select_section").show();
		}
	 
		$('.ajaxdata').html();
			$.ajax({
			type: 'POST',
			url: '<?php echo $this->Url->build(["controller" => "Student","action" => "view2"]);?>',
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
});
</script>
<!--- end section ajax ----->


<div class="msg-title">
<div class="row mailbox-header">
        <div class="col-md-2 col-sm-2 col-xs-12">
            <?php  echo $this->Html->link(__('Compose'),['controller' => 'Message', 'action' => 'compose'],['class'=>'btn btn-success btn-block']);?>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h2>
                <?php
					if(isset($_REQUEST['inbox']))
                        echo __( 'Inbox');
					else if(isset($_REQUEST['sentbox']))
						echo __( 'Sent Item');
					else if(!isset($_REQUEST['compose']))
						echo __( 'Compose');
				?>
								
                                    
            </h2>
        </div>
                               
</div>
</div>
<div class="col-md-2 col-sm-2 col-xs-12 msg-div">
    <ul class="list-unstyled mailbox-nav">
        <li <?php if(!isset($_REQUEST['inbox']))
		{ ?>
			class=""
		<?php }?>>
		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-inbox')) . __('Inbox'),['controller' => 'Message', 'action' => 'inbox'],['escape' => false]);?><span class="badge badge-success pull-right"><?php //echo $p;?></span></li>
        
		<li <?php if(isset($_REQUEST['sentbox']))
		{ ?>class=""
		<?php } ?>>
		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-sign-out')) . __('Sent'),['controller' => 'Message', 'action' => 'sentbox'],['escape' => false]);?></li>
			
    </ul>
</div>
<div class="col-md-10 col-sm-10 col-xs-12">
<div class="row">
	<div class="mailbox-content">
		<h2>
			<?php  $edit=0;
				if(isset($_REQUEST['edit']))
				{
					echo __( 'Edit Message');
					$edit=1;
				
				}
			?>
		</h2>
		<?php
		$user = $this->request->session()->read('user_id');
		$role = $this->Setting->get_user_role($user);
		$parent_msg_stud = $this->Setting->getfieldname('parent_msg_stud');
		$stud_msg_other = $this->Setting->getfieldname('stud_msg_other');
		?>
		<form name="class_form" action="" method="post" class="form-horizontal" id="message_form">
	<!--        <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?> 
			<input type="hidden" name="action" value="<?php echo $action;?>"> -->
			<div class="form-group">
				<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Message To'));?><span class="require-field">*</span></div>
				<div class="col-md-8 col-sm-8 col-xs-12">
					<select name="message_for" class="form-control validate[required,maxSize[50]] text-input" id="to">
						<option value="all"><?php echo __('All');?></option>
						<?php						
						if($role == 'parent' && $parent_msg_stud == 'no' ||
							$role == 'student' && $stud_msg_other == 'no'
						)
						{}else{?>
						<option value="student"><?php echo __('Student');?></option>	
						<?php } ?>
						<option value="teacher"><?php echo __('Teachers');?></option>	
						<option value="parent"><?php echo __('Parents');?></option>	
						<option value="supportstaff"><?php echo __('Support Staff');?></option>
						
						<?php
						if($role == 'parent' && $parent_msg_stud == 'no' ||
							$role == 'student' && $stud_msg_other == 'no'
						)
						{}else{
						echo "<optgroup label='Student'>";
							foreach($s_user as $user_data)
							{ ?>
								<option value="<?php echo $user_data['user_id'];?>"><?php echo $user_data['first_name']." ".$user_data['last_name'];?></option>
					<?php   } 
						echo "</optgroup>";	
						}
						echo "<optgroup label='Teacher'>";
							foreach($t_user as $user_data)
							{ ?>
								<option value="<?php echo $user_data['user_id'];?>"><?php echo $user_data['first_name']." ".$user_data['last_name'];?></option>
					<?php   } 									
							echo "</optgroup>";			
							echo "<optgroup label='Parent'>";
							foreach($p_user as $user_data)
							{ ?>
								<option value="<?php echo $user_data['user_id'];?>"><?php echo $user_data['first_name']." ".$user_data['last_name'];?></option>
					<?php   } 
							echo "</optgroup>";			
							echo "<optgroup label='Support Staff'>";
							foreach($ss_user as $user_data)
							{ ?>
								<option value="<?php echo $user_data['user_id'];?>"><?php echo $user_data['first_name']." ".$user_data['last_name'];?></option>
					<?php   } 
							echo "</optgroup>";	
							?>
					</select>
				</div>	
			</div>
			<div id="smgt_select_class">
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Class'));?><span class="require-field">*</span></div>
					<div class="col-md-8 col-sm-8 col-xs-12">
						<select name="class_id"  id="class_list" class="form-control validate[required,maxSize[50]]">
							<option value="All"><?php echo __('All');?></option>
							<?php
								
								foreach($class as $class_data)
								{ ?>
									<option value="<?php echo $class_data['class_id'];?>"><?php echo $class_data['class_name'];?></option>
						<?php   } 
								
								
							?>
						</select>
					</div>
				</div>
			</div>
			<div id="smgt_select_section">
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Section '));?><span class="require-field">*</span></div>
					<div class="col-md-8 col-sm-8 col-xs-12">
						<select class="form-control  validate[required,maxSize[50]] ajaxdata" name="section" id="dep">
							<option value=""><?php echo __('Select Section');?></option>
						</select>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Subject'));?><span class="require-field">*</span></div>
				<div class="col-md-8 col-sm-8 col-xs-12">
					<?php echo $this->Form->input('',array('name'=>'subject','class'=>'form-control validate[required,maxSize[150]]','PlaceHolder'=> __('Enter Subject ')));?>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Message Comment'));?><span class="require-field">*</span></div>
				<div class="col-md-8 col-sm-8 col-xs-12">                
					<?php echo $this->Form->textarea('',array('name'=>'message_body','class'=>'form-control validate[required,maxSize[500]]','PlaceHolder'=> __('Enter Message Comment '),'rows' => '2', 'cols' => '5'));?>
				</div>
			</div>
			
			<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Send Mail '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php
									echo $this->Form->checkbox('',array('hiddenField'=>'false','name'=>'sendmail','id'=>'checkmail'));
								?>
							</div>
			</div>
			
			<div class="form-group">
				<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Send SMS'));?></div>
				<div class="col-md-8 col-sm-8 col-xs-12">
					<?php
					echo $this->Form->checkbox('',array('hiddenField'=>'false','name'=>'smgt_sms_service_enable','id'=>'sms_sent','class'=>''));
					?>
				</div>
			</div>
			
			<div id="message_sent" style="display:none;">
			<div class="form-group">
				<div class="col-md-2 col-sm-2 col-xs-12 label_float" for="sms_template"><?php echo $this->Form->label(__('SMS Text'));?><span class="require-field">*</span></div>
				<div class="col-md-8 col-sm-8 col-xs-12">
					<?php echo $this->Form->textarea('',array('name'=>'sms_template','class'=>'form-control validate[required,maxSize[150]] xms_txt','PlaceHolder'=> __('Enter SMS Text '),'maxlength'=>'160','rows' => '2', 'cols' => '5'));?>
					<?php echo $this->Form->label(__('Max. 160 Character'));?>
				</div>
			</div>
			</div>			
			<div class="form-group">
				<div class="col-md-10 col-sm-10 col-xs-12">
					<div class="pull-right">
						<?php echo $this->Form->input(__('Send Message'),array('type'=>'submit','name'=>'save_message','class'=>'btn btn-success'));?>		   
					</div>
				</div>
			</div>
				
			
		</form>
	</div>        
</div>
</div>