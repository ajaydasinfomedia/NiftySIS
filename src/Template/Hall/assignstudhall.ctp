<?php 
use Cake\Routing\Router;
?>
<script>
$(document).ready(function(){
	
	$("#hall_id").multiselect();
	
	$('body').on('click', '#abc', function() {
		
		var hall_id = $('#hall_id').val();
		var val = $("#exam_id").val();	
		var val2 = $("#sub_id").val();	
		
		if(hall_id == null){
			alert("Select Exam Hall");
			return false;
		}
		if(val == ''){
			alert("Select Exam");
			return false;
		}
		if(val2 == ''){
			alert("Select Subject");
			return false;
		}
		
		var curr_data = {exam_id : val,sub_id : val2,hall_id : hall_id};
		
		jQuery.ajax({
	        type:"POST",
	        url:"<?php echo Router::url(array('controller'=>'Hall','action'=>'studentexamhall'));?>",
	        data:curr_data,
	        async:false,
	        success: function(response){    			               
				jQuery('.studentexamhall').html(response);
	        },
	        beforeSend:function(){
				jQuery('.studentexamhall').html('<center><img src=../img/loading.gif width=120px></center>');
			},
	        error: function(e) {
	            console.log(e);
	        }
	    });			
	});
	
	$("#sub_id").change(function(){
		
		var val = $("#exam_id").val();	
		var val2 = $("#sub_id").val();
     
		var curr_data = {exam_id : val,sub_id : val2};
		
		jQuery.ajax({
	        type:"POST",
	        url:"<?php echo Router::url(array('controller'=>'Hall','action'=>'studentexamhall'));?>",
	        data:curr_data,
	        async:false,
	        success: function(response){    			               
				jQuery('.studentexamhall').html(response);
	        },
	        beforeSend:function(){
				jQuery('.studentexamhall').html('<center><img src=../img/loading.gif width=120px></center>');
			},
	        error: function(e) {
	            console.log(e);
	        }
	    });
	});	
	
	$("#exam_id").change(function(){
		
		var exam_id = $(this).val();
     
		$('.ajaxdata').html();
		$.ajax({
			type: 'POST',
			url: '<?php echo $this->Url->build(["controller" => "Hall","action" => "view2"]);?>',
	
			data : {id : exam_id},	     
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
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Exam Hall List'),array('controller'=>'Hall','action' => 'halllist'),array('escape' => false));?>
		</li>
		<li class="">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Exam Hall'),array('controller'=>'Hall','action' => 'addhall'),array('escape' => false));?>
		</li>
		<li class="">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Assign Exam Hall'),array('controller'=>'Hall','action' => 'examhallreceipt'),array('escape' => false));?>
		</li>
		<li class="active">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Assign Student Exam Hall'),array('controller'=>'Hall','action' => 'assignstudhall'),array('escape' => false));?>
		</li>
	</ul>
</div>
<div class="exam panel-body">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<form id="formID2" name="f1" class="form_horizontal" method="post" enctype="multipart/form-data">
		
		<div class="form-group">
			<div class="col-md-1 col-sm-1 col-xs-12" style="text-align: right;"><div class="row"><?php echo $this->Form->label(__('Exam'));?> <span class="require-field">*</span></div></div>
			<div class="col-md-2 col-sm-2 col-xs-12">
				<select class="form-control validate[required]" id="exam_id" name="exam_id">
					<option value=""> <?php echo __('Select Exam '); ?> </option>
					<?php
					foreach($exam_data as $fetch_data)
					{
						$selected = ($fetch_data['exam_id'] == $exam_id)?'selected':'';
					?>
						<option value="<?php echo $fetch_data['exam_id'];?>" <?php echo $selected;?>><?php echo $fetch_data['exam_name'];?></option>
					<?php
					}
					?>
				</select>
			</div>
			<div class="col-md-1 col-sm-1 col-xs-12" style="text-align: right;"><div class="row"><?php echo $this->Form->label(__('Subject'));?> <span class="require-field">*</span></div></div>
			<div class="col-md-2 col-sm-2 col-xs-12">
				<select class="form-control validate[required] ajaxdata" name="subject_id" id="sub_id">
					<?php 
						if(isset($sec_id)){?>
							<option value="<?php echo $sec_id; ?>"><?php echo $this->Setting->get_class_section($sec_id); ?></option>
					<?php } 
						else
							echo "<option value=''>"?> <?php echo __('Select Subject'); ?> <?php "</option>";
					?>
				</select>
			</div>
			<div class="col-md-1 col-sm-1 col-xs-12" style="text-align: right;"><div class="row"><?php echo $this->Form->label(__('Exam Hall'));?> <span class="require-field">*</span></div></div>
			<div class="col-md-2 col-sm-2 col-xs-12">
				<select class="form-control validate[required]" id="hall_id" name="hall_id" multiple="multiple">
					<?php
					foreach($hall_data as $fetch_data)
					{
						$selected = ($fetch_data['hall_id'] == $hall_id)?'selected':'';
					?>
						<option value="<?php echo $fetch_data['hall_id'];?>" <?php echo $selected;?>><?php echo $fetch_data['hall_name'];?></option>
					<?php
					}
					?>
				</select>
			</div>
			<div class="col-md-2 col-sm-2 col-xs-12">
				<button type="button" class="btn btn-success" id="abc"><?php echo __('Assign Exam Hall');?> </button>			
			</div>
			
		</div>
		</form>
	</div>
</div>
<div class="panel-body">
	<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="studentexamhall"></div>
	</div>
	<?php $this->Form->end(); ?>
</div>
<style>
.exam.panel-body{margin-top: 20px;}
.submit{
	margin-top:0px;
}
</style>