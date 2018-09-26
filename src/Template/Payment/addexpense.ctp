<script>
jQuery(document).ready(function() {
	$("#date").datepicker({
		minDate: new Date(),
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd', 
   });
});
</script>

				<?php

					if(isset($row['income_id'])){
						$name=$row['supplier_name'];
						$status=$row['payment_status'];
						$date= date("Y-m-d",strtotime($row['income_create_date']));
						$link_name= __('Edit Expense');
							}else{
						$name='';
						$status='';
						$date='';
						$link_name= __('Add Expense');
					}

				?>



<div class="row">			
				<ul role="tablist" class="nav nav-tabs panel_tabs">
<li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Payment List'),array('controller'=>'Payment','action' => 'paymentlist'),array('escape' => false));?></li>

					  <li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Payment'),array('controller'=>'Payment','action' => 'addpayment'),array('escape' => false));?></li>
					  
					   <li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Income List'),array('controller'=>'Payment','action' => 'incomelist'),array('escape' => false));?></li>
					   
					   <li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Income'),array('controller'=>'Payment','action' => 'addincome'),array('escape' => false));?></li>
					  
					  <li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Expense List'),array('controller'=>'Payment','action' => 'expenselist'),array('escape' => false));?></li>
	
					   <li class="active"><?php
						if(isset($edit))
							echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __("$link_name"),array('controller'=>'Payment','action' => 'addexpense',$this->Setting->my_simple_crypt($row['income_id'],'e')),array('escape' => false));
						else
							echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __("$link_name"),array('controller'=>'Payment','action' => 'addexpense'),array('escape' => false));
						?></li>
				</ul>
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addexpense']]);?>
			

				<div class="form-group">
				<?php echo $this->Form->input('',array('name'=>'invoice_type','value'=>'expense','id'=>'','type'=>'hidden','class'=>'form-control','PlaceHolder'=>''));?>
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Supplier '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'supplier_name','value'=>$name,'id'=>'','type'=>'text','class'=>'form-control','PlaceHolder'=>'Enter Supplier Name'));?>
							</div>
				</div>
				
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Status '));?><span style="color:red;"><?php echo " *"; ?></div>
							
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
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Date '));?><span style="color:red;"><?php echo " *"; ?></div>
							<div class="col-md-10 col-sm-10 col-xs-12 datepickericon">
								<?php echo $this->Form->input('',array('name'=>'income_create_date','id'=>'date','value'=>$date,'type'=>'text','class'=>'form-control validate[required]','PlaceHolder'=> __('Enter Date ')));?>
							</div>
				</div>
				
				
				<hr style="float:left;width:100%">
				
				<?php 

					if(isset($row['income_id'])){

						$entry=$row['entry'];

							$am=json_decode($entry);

							$amount=array();
							foreach($am as $total){
				
					?>

					<div id="custom_label">
						<div class="form-group">
						<label class="col-md-2 col-sm-2 col-xs-12 control-label" for="income_entry"><?php echo __('Expense Entry');?><span style="color:red;"><?php echo " *"; ?></span></label>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<input id="income_amount" class="form-control validate[required,custom[onlyNumberSp],maxSize[8]] text-input" type="text" value="<?php echo $total->amount; ?>" name="custom_label[]" placeholder="Expense Amount">
						</div>
						<div class="col-md-5 col-sm-5 col-xs-12">
							<input id="income_entry" class="form-control validate[required,maxSize[50]] text-input" type="text" value="<?php echo $total->entry ; ?>" name="custom_value[]" placeholder="Expense Entry Label">
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
						<label class="col-md-2 col-sm-2 col-xs-12 control-label" for="income_entry"><?php echo __('Expense Entry');?><span style="color:red;"><?php echo " *"; ?></span></label>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<input id="income_amount" class="form-control validate[required,custom[onlyNumberSp],maxSize[8]] text-input" type="text" value="" name="custom_label[]" placeholder="Expense Amount">
						</div>
						<div class="col-md-5 col-sm-5 col-xs-12">
							<input id="income_entry" class="form-control validate[required,maxSize[50]] text-input" type="text" value="" name="custom_value[]" placeholder="Expense Entry Label">
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
			<label class="col-md-2 col-sm-2 col-xs-12 control-label" for="income_entry"></label>
			<div class="col-md-3 col-sm-6 col-xs-12">
				<button id="add_new_entry" class="btn btn-primary btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_custom_label()"><?php echo __('Add More Field'); ?>
				</button>
			</div>
			
		</div>
			

				<hr style="float:left;width:100%">
			
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
							<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">

							<?php echo $this->Form->input(__('Create Expense Entry'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
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

