<?php 
use Cake\Routing\Router;
?>
<script>
$(document).ready(function(){
	$('body').on('change', '#exam_id', function() {
		
		var val = $(this).val();	
		var curr_data = {exam_id : val};
		
		if(val == null || val == ''){
			return false;
		}
		else
		{
			jQuery('#loadingmessage').show();
			
			jQuery.ajax({
				type:"POST",
				url:"<?php echo Router::url(array('controller'=>'Hall','action'=>'studentexamhall'));?>",
				data:curr_data,
				async:false,
				success: function(response){    			               
					jQuery('.studentexamhall').html(response);
					jQuery('#loadingmessage').hide();	
				},
				error: function(e) {
					console.log(e);
				}
			});			
		}
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
		<li class="active">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Exam Hall Receipt'),array('controller'=>'Hall','action' => 'examhallreceipt'),array('escape' => false));?>
		</li>
	</ul>
</div>
<div id='loadingmessage' style='display:none'>
  <center><img src='../img/loading.gif' width='120px'></center>
</div>
<div class="exam panel-body">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<form id="formID2" name="f1" class="form_horizontal" method="post" enctype="multipart/form-data">
		
		<div class="form-group">
			<div class="col-md-3 col-sm-6 col-xs-12">
				<select class="form-control validate[required]" id="exam_id" name="exam_id">
					<option value=""> <?php echo __('Select Exam '); ?> </option>
					<?php
					foreach($exam_data as $fetch_data)
					{
						$class_id = $this->Setting->get_exam_data($fetch_data['exam_id'],'class_id');
						$section_id = $this->Setting->get_exam_data($fetch_data['exam_id'],'section_id');
						$selected = ($fetch_data['exam_id'] == $exam_id)?'selected':'';
					?>
						<option value="<?php echo $fetch_data['exam_id'];?>" <?php echo $selected;?>><?php echo $fetch_data['exam_name']." (".$this->Setting->get_class_id($class_id).") (".$this->Setting->section_name($section_id).")";?></option>
					<?php
					}
					?>
				</select>
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