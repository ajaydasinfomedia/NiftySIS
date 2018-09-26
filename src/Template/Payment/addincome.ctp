<script>
		jQuery(document).ready(function() {
			
			$("#date").datepicker({
				minDate: new Date(),
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true,
				dateFormat: 'yy-mm-dd', 
		   });
	
		jQuery("#dep").change(function(){

			var get_class_id=$(this).val();

				if($(this).val() == ''){
					
				}else{

			$.ajax({

				type:'POST',
				url:'<?php echo $this->Url->build(["controller"=>"Payment","action"=>"ShowStudent"])?>',
				data:{id:get_class_id},


				success:function(getdata){

					$(".result").html(getdata);

				},

				beforeSend:function(){
					$(".result").html("<center><h5>Loading...</h5></center>");
				},

				error:function(){
					alert('An Error Occured:'+e.responseText);
					console.log();
				}



			});
		}

		});
		
		});
	</script>



<!--- section ajax ----->
<script>

$( document ).ready(function(){
	
    $("#option_class").change(function(){
	var class_id = $(this).val();
     
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
		
	

<?php if(isset($incomerecord['income_id'])){

			$status=$incomerecord['payment_status'];
			$date=date("Y-m-d",strtotime($incomerecord['income_create_date']));
			
			$link_name= __('Edit Income');
			

		}else
		{
			$status='';
			$date='';
		
			$link_name= __('Add Income');
		}

?>


<div class="row">			
				<ul role="tablist" class="nav nav-tabs panel_tabs">
<li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Payment List'),array('controller'=>'Payment','action' => 'paymentlist'),array('escape' => false));?></li>

					  <li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Payment'),array('controller'=>'Payment','action' => 'addpayment'),array('escape' => false));?></li>
					  
					   <li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Income List'),array('controller'=>'Payment','action' => 'incomelist'),array('escape' => false));?></li>
					   
					   <li class="active"><?php 
						if(isset($edit))
							echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Edit Income'),array('controller'=>'Payment','action' => 'addincome',$this->Setting->my_simple_crypt($incomerecord['income_id'],'e')),array('escape' => false));
						else
							echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Income'),array('controller'=>'Payment','action' => 'addincome'),array('escape' => false));
					   ?></li>
					  
					  <li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Expense List'),array('controller'=>'Payment','action' => 'expenselist'),array('escape' => false));?></li>
	
					   <li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Expense'),array('controller'=>'Payment','action' => 'addexpense'),array('escape' => false));?></li>
				</ul>
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addpayment']]);?>
				
				
				<?php echo $this->Form->input('',array('name'=>'invoice_type','value'=>'income','id'=>'','type'=>'hidden','class'=>'form-control')); ?>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Class '));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						
						<?php

						$class_arr=array();
						
						$options_class['']= __('Select Class');
						foreach($class_info as $class_data):
							$options_class[$class_data['class_id']]=$class_data['class_name'];
						endforeach;
						
						if(isset($incomerecord['income_id']))
						{
							echo $this->Form->select('',$options_class,['default'=>$classid,'class'=>'form-control select validate[required,maxSize[50]]','name'=>'class_id','id'=>'option_class']);
						}
						else
						{
							echo $this->Form->select('',$options_class,['class'=>'form-control select validate[required,maxSize[50]]','name'=>'class_id','id'=>'option_class']);
						}
														
						?>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Section '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<select class="form-control validate[required,maxSize[50]] ajaxdata" name="section" id="dep">
							<?php
								if(isset($incomerecord['income_id']))
								{
							?>
								<option value="<?php echo $incomerecord['section'] ;?>"><?php echo $this->Setting->section_name($incomerecord['section']);?></option>
							<?php
								}
								else
								{
							?>
								<option value=""><?php echo __('Select Section'); ?></option>
								<?php
								}
								?>
							</select>
							</div>
				</div>



				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Student '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<div class="result">
								<?php
								if(isset($incomerecord['income_id']))
								{
									$options=array();
									
									 foreach ($allstudent_from_class as $val)
									 {
										$options[$val['user_id']]=$val['first_name'];
									 }
								}
								else
								{
									$options=[''=> __('Select Student'),];
								}
								
								echo $this->Form->select('',$options,['id'=>'classid','class'=>'form-control select validate[required,maxSize[50]]','name'=>'supplier_name']);
								?>
							</div>
						</div>
				</div>
			
				
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Status '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								
								<?php
								$options=[
									''=> __('Select Status'),
									'Paid'=> __('Paid'),
									'Part Paid'=> __('Part Paid'),
									'Unpaid'=> __('Unpaid')
								];
								if($status == ''){
									echo $this->Form->select('',$options,['class'=>'form-control select validate[required,maxSize[50]]','name'=>'payment_status']);
								}
								else{
									echo $this->Form->select('',$options,['default'=>$status,'class'=>'form-control select validate[required,maxSize[50]]','name'=>'payment_status']);
								}		
						 ?>
								
							</div>
				</div>
				
			
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Date '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12 datepickericon">
								<?php echo $this->Form->input('',array('name'=>'income_create_date','value'=>$date,'id'=>'date','type'=>'text','class'=>'form-control validate[required]','PlaceHolder'=>'Enter Date'));?>
							</div>
				</div>
				
				<hr style="float:left;width:100%">



				<?php if(isset($incomerecord['income_id'])){

						$entry=$incomerecord['entry'];

							$am=json_decode($entry);

							$amount=array();

							foreach($am as $total){

					

				?>



				<div id="custom_label">
						<div class="form-group">
						<label class="col-md-2 col-sm-2 col-xs-12 control-label label_float" for="income_entry"><?php echo __('Income Entry');?><span style="color:red;"><?php echo " *"; ?></span></label>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<input id="income_amount" class="form-control text-input validate[required,custom[onlyNumberSp],maxSize[8]] " type="text" value="<?php echo $total->amount;?>" name="custom_label[]" placeholder= "Income Amount">
						</div>
						<div class="col-md-5 col-sm-5 col-xs-12">
							<input id="income_entry" class="form-control text-input validate[required,maxSize[50]]" type="text" value="<?php echo $total->entry ; ?>" name="custom_value[]" placeholder="Income Entry Label">
						</div>						
						<div class="col-md-2 col-sm-2 col-xs-12">
						<button type="button" class="btn btn-primary" onclick="deleteParentElement(this)" style="margin-top:0;">
						<i class="entypo-trash"><?php echo __('Delete');?></i>
						</button>
						</div>
						</div>	
					</div>
					


				<?php
			}

			}else{

				 ?>

				
				<div id="custom_label">
						<div class="form-group">
						<label class="col-md-2 col-sm-2 col-xs-12 control-label label_float" for="income_entry"><?php echo __('Income Entry');?><span style="color:red;"><?php echo " *"; ?></span></label>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<input id="income_amount" class="form-control text-input validate[required,custom[onlyNumberSp],maxSize[8]]" type="text" value="" name="custom_label[]" placeholder="Income Amount">
						</div>
						<div class="col-md-5 col-sm-5 col-xs-12">
							<input id="income_entry" class="form-control text-input validate[required,maxSize[50]]" type="text" value="" name="custom_value[]" placeholder="Income Entry Label">
						</div>						
						<div class="col-md-2 col-sm-2 col-xs-12">
						<button type="button" class="btn btn-primary" onclick="deleteParentElement(this)" style="margin-top:0;">
						<i class="entypo-trash"><?php echo __('Delete');?></i>
						</button>
						</div>
						</div>	
					</div>
					
					<?php
}
					?>
					
					<div class="form-group">
			<label class="col-md-2 col-sm-2 col-xs-12 control-label label_float" for="income_entry"></label>
			<div class="col-md-3 col-sm-6 col-xs-12">
				
				<button id="add_new_entry" class="btn btn-primary btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_custom_label()"><?php echo __('Add More Field'); ?>
				</button>
			</div>
			
		</div>
			

				<hr style="float:left;width:100%">
			
				
			
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label('');?></div>
							<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">

							<?php echo $this->Form->input(__('Create Income Entry'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
							</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>			


<script>

   	// CREATING BLANK INVOICE ENTRY
   	var blank_income_entry ='';
   	$(document).ready(function() { 
   		blank_income_entry = $('#invoice_entry').html();
   
   	}); 

	var blank_custom_label ='';
   	$(document).ready(function() { 
   		blank_custom_label = $('#custom_label').html();
   	
   	}); 

   	function add_entry()
   	{
   		$("#invoice_entry").append(blank_income_entry);

   	}

	function add_custom_label()
   	{
   		$("#custom_label").append(blank_custom_label);

   	}
   	
   	// REMOVING INVOICE ENTRY
   	function deleteParentElement(n){
   		n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
   	}
</script>