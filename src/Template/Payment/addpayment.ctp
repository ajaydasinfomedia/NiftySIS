<script>
		jQuery(document).ready(function() {
		
		jQuery('.cc').change(function(){
			
			var select_class=$('.cc option:selected').text();
			$('.cname').val(select_class);
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

<?php
    if(isset($row['payment_id'])){
        $title_name=$row['payment_title'];
        $cid=$row['class_id'];
       	$class_name=$row['c_name'];
        $amount=$row['amount'];
        $link_name= __('Edit Payment');
        $ps=$row['payment_status'];
        $desc=$row['description'];
        $btnn= __('Edit Payment');
}else{
        $title_name='';
        $class_name='';
        $amount='';
        $link_name= __('Add Payment');
        $ps='';
        $desc='';
        $btnn= __('Add Payment');
}
?>

<div class="row">			
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					
					 <li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Payment List'),array('controller'=>'Payment','action' => 'paymentlist'),array('escape' => false));?></li>

					  <li class="active"><?php  
					  
					  if(isset($edit))
						echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Edit Payment'),array('controller'=>'Payment','action' => 'addpayment',$this->Setting->my_simple_crypt($row['payment_id'],'e')),array('escape' => false));
					  else
						 echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Payment'),array('controller'=>'Payment','action' => 'addpayment'),array('escape' => false));
					 
					  ?></li>
					  
					   <li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) .  __('Income List'),array('controller'=>'Payment','action' => 'incomelist'),array('escape' => false));?></li>
					   
					   <li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Income'),array('controller'=>'Payment','action' => 'addincome'),array('escape' => false));?></li>
					  
					  <li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Expense List'),array('controller'=>'Payment','action' => 'expenselist'),array('escape' => false));?></li>
	
					   <li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Expense'),array('controller'=>'Payment','action' => 'addexpense'),array('escape' => false));?></li>
				</ul>
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addpayment']]);?>
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Title '));?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'payment_title','value'=>$title_name,'id'=>'payment_title','class'=>'form-control validate[required,maxSize[50]] payment_title','PlaceHolder'=> __('')));?>
							</div>
				</div>
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Class '));?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								
								<?php
								$class_arr=array();
								
								$options_class['']= __('Select Class');
								foreach($class_info as $class_data):
									$options_class[$class_data['class_id']]=$class_data['class_name'];
								endforeach;
								
                                if(isset($row['payment_id']))
								{
									echo $this->Form->select('',$options_class,['default'=>$cid,'class'=>'form-control select validate[required,maxSize[50]] cc','name'=>'class_id','id'=>'option_class']);
									// $options_class[$cid]=$class_name;
								}
								else
								{
									echo $this->Form->select('',$options_class,['class'=>'form-control select validate[required,maxSize[50]] cc','name'=>'class_id','id'=>'option_class']);
								}
															
						 ?>
								
							</div>
				</div>
				
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Section '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<select class="form-control validate[required,maxSize[50]] ajaxdata" name="section" id="dep">
							<?php
								if(isset($row['payment_id']))
								{
									?>
										<option value="<?php echo $row['section'] ;?>"><?php echo $this->Setting->section_name($row['section']);?></option>
									<?php
								}
								else{
							?>
								<option value=""><?php echo __('Select Section'); ?></option>
								<?php
								}
								?>
							</select>
							</div>
				</div>
				
				<div class="form-group">

					

							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Student '));?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<div class="result">
								<?php   

								if(isset($row['payment_id']))
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
								
								echo $this->Form->select('',$options,['id'=>'classid','class'=>'form-control select validate[required,maxSize[50]]','name'=>'user_id']);
								?>				  
						
								</div>

								


								<?php echo $this->Form->input('',array('name'=>'c_name','class'=>'cname','type'=>'hidden','value'=>$class_name));?>
							</div>
				</div>
			
			
									
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Amount '));?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'amount','value'=>$amount,'class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[8]]','PlaceHolder'=> __('Enter Amount ')));?>
							</div>
				</div>
				
	
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Status '));?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								
								<?php
							$options=[
                                    $ps=>$ps,
									''=> __('Select Status'),
									'Paid'=> __('Paid'),
									'Part Paid'=> __('Part Paid'),
									'Unpaid'=> __('Unpaid')
									];
							echo $this->Form->select('',$options,['class'=>'form-control select validate[required,maxSize[50]]','name'=>'payment_status']);
						 ?>
								
							</div>
				</div>
			
			
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Description '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'description','value'=>$desc,'id'=>'message','type'=>'textarea','class'=>'form-control validate[maxSize[500]]','PlaceHolder'=> __('Enter Description ')));?>
							</div>
				</div>
			
				
			
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
							<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">

							<?php echo $this->Form->input(__("$btnn"),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
							</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>