<script>
	$(document).ready(function(){
	$('#message,#lblmsg').hide();	
		
	$('#checksms').click(function(){	
		if($('#checksms').is(':checked')){
			$('#message,#lblmsg').fadeIn(500);
		}else{
			$('#message,#lblmsg').slideUp(100);
		}	
	});	
	$("#date_of_birth20").datepicker({
		numberOfMonths: 1,
		minDate: new Date(),
		dateFormat: 'yy-mm-dd',  

		onSelect: function() {
		var date = $('#date_of_birth20').datepicker('getDate');  
		date.setDate(date.getDate());

		$("#date_of_birth21").datepicker("option","minDate", date);
		}
   });
   $("#date_of_birth21").datepicker({     
		numberOfMonths: 1,
		minDate: new Date(),
		dateFormat: 'yy-mm-dd',  
		onSelect: function() {
		}
	}); 
});
</script>

<script>

$( document ).ready(function(){
	
    $("#savenotice").change(function(){
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

<div class="row">			
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					   <li>
							<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Notice List'),array('controller'=>'Notice','action' => 'noticelist'),array('escape' => false));
						?>
					
						
						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->
						  
					  </li>
					  <li class="active">
<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-pencil-square-o fa-lg')) . __('Edit Notice'),array('controller'=>'Notice','action' => 'updatenotice',$this->Setting->my_simple_crypt($row['notice_id'],'e')),array('escape' => false));
						?>
					

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>
				</ul>
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'updatenotice']]);?>
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Notice Title '));?> <span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'notice_title','value'=>$row['notice_title'],'class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=>'Enter Notice Title'));?>
							</div>
				</div>
				
				
									
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Notice Comment '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'notice_comment','value'=>$row['notice_comment'],'type'=>'textarea','class'=>'form-control validate[maxSize[500]]','PlaceHolder'=>'Enter Notice Comment'));?>
							</div>
				</div>
			
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Notice Start Date '));?> <span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12 datepickericon">
								<?php echo $this->Form->input('',array('name'=>'notice_start_date','value'=>date("Y-m-d", strtotime($row['notice_start_date'])),'id'=>'date_of_birth20','class'=>'form-control validate[required]','PlaceHolder'=> __('Enter Notice Start date ')));?>
							</div>
				</div>
				
					<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Notice End Date '));?> <span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12 datepickericon">
								<?php echo $this->Form->input('',array('name'=>'notice_end_date','value'=>date("Y-m-d", strtotime($row['notice_end_date'])),'id'=>'date_of_birth21','class'=>'form-control validate[required]','PlaceHolder'=> __('Enter Notice End date ')));?>
							</div>
				</div>
				
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Notice For '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								
								<?php
									
									$Get_Val=$row['notice_for'];
									
									
							$options=[$Get_Val=>$Get_Val,
									'All'=> __('All'),
									'teacher'=> __('Teacher'),
									'student'=> __('Student'),
									'parent'=> __('Parent'),
									'supportstaff'=> __('Support Staff')
									];
							echo $this->Form->select('',$options,['class'=>'form-control select validate[required]','name'=>'notice_for']);
						 ?>
								
							</div>
				</div>
			
			
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Class '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								
								<?php
										$Get_Class=$row['which_class'];
										
											$arr=array();
											$arr[$Get_Class]=$Get_Class;
											$arr['All']= __('All');
											
											foreach($class_name as $get_class):
											
												$arr[$get_class['class_id']]=$get_class['class_name'];
											
											endforeach;
													
							echo $this->Form->select('',$arr,['class'=>'form-control select validate[required]','name'=>'which_class','id'=>'savenotice']);
						 ?>
								
							</div>
				</div>
				
				<div class="form-group">
						<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Section '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
							
								<?php 
									 $Get_Section=$row['section'];
										
											$ses=array();
											$ses[$Get_Section]=$Get_Section;
									
											
											foreach($section_name as $get_section):
											
												$ses[$get_section['class_section_id']]=$get_section['section_name'];
											
											endforeach;
											echo $this->Form->select('',$ses,['class'=>'form-control select ajaxdata','name'=>'section']);
									
								?>
						
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
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Send SMS '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php
									
									echo $this->Form->checkbox('',array('hiddenField'=>'false','name'=>'sendsms','id'=>'checksms','class'=>''));
								
								?>
							</div>
				</div>
			
			
			
			
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('SMS Text '),null,['id'=>'lblmsg']);?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'message','id'=>'message','type'=>'textarea','class'=>'form-control validate[maxSize[150]]','PlaceHolder'=> __('Enter Message ')));?>
							</div>
				</div>
			
				
			
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
							<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
							<?php echo $this->Form->input(__('Add Notice'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
							</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>			