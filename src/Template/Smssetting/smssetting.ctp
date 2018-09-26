<script>

$(document).ready(function() {
<?php	

				  if(isset($service)){
						if($service == 'clicktell'){?>
		$('.clickatell').show();
			$('.twillo').hide();
			<?php } else{?> $('.clickatell').hide();
			$('.twillo').show();<?php } }?>
	$('#checkboxclickatell').click(function(){
		if($('#checkboxclickatell').is(':checked')){
			$('.clickatell').show();
			$('.twillo').hide();
		}
		
	
	});
	
	$('#checkboxtwillo').click(function(){
		if($('#checkboxtwillo').is(':checked')){
			$('.twillo').show();
			$('.clickatell').hide();
		}
		
	
	});

});
</script>

<div class="row">			
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					  <li class="active">
						  <?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-awards fa-lg')) . __('SMS Setting'),['controller' => 'Smssetting', 'action' => 'smssetting'],['escape' => false]);?>
					  </li>
				</ul>
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'smssetting']]);?>
			<div class="form-group">
				<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Message Service '));?></div>
					
				<div class="col-md-8 col-sm-8 col-xs-12">
					<div class="radio label_float">
				  <?php	

				  if(isset($service)){
						if($service == 'clicktell')
					{?>
								<input id="checkboxclickatell" type="radio"  name="select_serveice" value="clicktell" checked> <?php echo __('Clickatell');?> 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input id="checkboxtwillo" type="radio" name="select_serveice" value="twillo">  <?php echo __('Twilio');?>
						
				<?php	
				} 
				else
				{ ?>
								<input id="checkboxclickatell" type="radio"  name="select_serveice" value="clicktell" > <?php echo __('Clickatell');?> 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input id="checkboxtwillo" type="radio" name="select_serveice" value="twillo" checked>  <?php echo __('Twilio');?>
			<?php	} }
				else
				{ ?>
								<input id="checkboxclickatell" type="radio"  name="select_serveice" value="clicktell" > <?php echo __('Clickatell');?> 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input id="checkboxtwillo" type="radio" name="select_serveice" value="twillo">  <?php echo __('Twilio');?>
		<?php	} ?>
			
						
					</div>
								
				</div>
				
			</div>
			<div class="clickatell">
			
					<div class="form-group">
								<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Username '));?><span style="color:red;"><?php echo " *"; ?></span></div>
								<div class="col-md-8 col-sm-8 col-xs-12">
									<?php echo $this->Form->input('',array('value'=>$service_data_decode['username'],'name'=>'username','class'=>'form-control validate[required,maxSize[50]]'));?>
								</div>
					</div>
					<div class="form-group">
								<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Password '));?><span style="color:red;"><?php echo " *"; ?></span></div>
								<div class="col-md-8 col-sm-8 col-xs-12">
									<?php echo $this->Form->input('',array('value'=>$service_data_decode['password'],'name'=>'password','class'=>'form-control validate[required,maxSize[50]]'));?>
								</div>
					</div>
					<div class="form-group">
								<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('API Key '));?><span style="color:red;"><?php echo " *"; ?></span></div>
								<div class="col-md-8 col-sm-8 col-xs-12">
									<?php echo $this->Form->input('',array('value'=>$service_data_decode['api_key'],'name'=>'api_key','class'=>'form-control validate[required,maxSize[50]]'));?>
								</div>
					</div>
					<div class="form-group">
								<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Sender ID '));?><span style="color:red;"><?php echo " *"; ?></span></div>
								<div class="col-md-8 col-sm-8 col-xs-12">
									<?php echo $this->Form->input('',array('value'=>$service_data_decode['sender_id'],'name'=>'sender_id','class'=>'form-control validate[required,maxSize[50]]'));?>
								</div>
					</div>
			</div>
				
			<div class="twillo">		
						<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Account SID '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$service_data_decode1['account_sid'],'name'=>'account_sid','class'=>'form-control validate[required,maxSize[50]]'));?>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Auth Token '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$service_data_decode1['auth_token'],'name'=>'auth_token','class'=>'form-control validate[required,maxSize[50]]'));?>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('From Number '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$service_data_decode1['from_number'],'name'=>'from_number','class'=>'form-control validate[required,maxSize[50]]'));?>
							</div>
						</div>
						
			</div> 
			<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"></div>
					<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
						<?php echo $this->Form->input(__('Save'),array('type'=>'submit','name'=>'save_sms_setting','class'=>'btn btn-success'));?>
					</div>
			</div>
			<?php $this->Form->end(); ?>
		</div>
</div>