<script>
	$(document).ready(function(){
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
<?php
$news_title = isset($row['news_title'])?$row['news_title']:'';
$news_desc = isset($row['news_desc'])?$row['news_desc']:'';
$news_start_date = isset($row['news_start_date'])?$row['news_start_date']:'';
$news_end_date = isset($row['news_end_date'])?$row['news_end_date']:'';
$news_document = isset($row['news_document'])?$row['news_document']:'';
?>
<div class="row">			
				<ul role="tablist" class="nav nav-tabs panel_tabs">
				
					  
					    <li class="">
							
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('News List'),array('controller'=>'News','action' => 'newslist'),array('escape' => false));
						?>
						
						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->
						  
					  </li>
					  <li class="active">
							
					<?php  
					if(isset($edit))
						echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Edit News'),array('controller'=>'News','action' => 'addnews',$this->Setting->my_simple_crypt($row['news_id'],'e')),array('escape' => false));
					else
						echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add News'),array('controller'=>'News','action' => 'addnews'),array('escape' => false));
					
					?>
						
						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->
						  
					  </li>
				</ul>
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addnews']]);?>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('News Title '));?><span class="require-field">*</span></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<?php echo $this->Form->input('',array('name'=>'news_title','value'=>$news_title,'class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=> __('Enter News Title ')));?>
					</div>
				</div>
				
				
									
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Description '));?></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<?php echo $this->Form->input('',array('name'=>'news_desc','value'=>$news_desc,'type'=>'textarea','class'=>'form-control validate[maxSize[500]]','PlaceHolder'=> __('Enter Description ')));?>
					</div>
				</div>
			
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Start Date '));?><span class="require-field">*</span></div>
					<div class="col-md-10 col-sm-10 col-xs-12 datepickericon">
						<?php 
						if(isset($edit))
							$start_date = date("Y-m-d", strtotime($news_start_date));
						else
							$start_date = '';
						echo $this->Form->input('',array('name'=>'news_start_date','value'=>$start_date,'id'=>'date_of_birth20','class'=>'form-control validate[required]','PlaceHolder'=> __('Enter News date ')));?>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('End Date '));?><span class="require-field">*</span></div>
					<div class="col-md-10 col-sm-10 col-xs-12 datepickericon">
						<?php 
						if(isset($edit))
							$end_date = date("Y-m-d", strtotime($news_end_date));
						else
							$end_date = '';
						echo $this->Form->input('',array('name'=>'news_end_date','value'=>$end_date,'id'=>'date_of_birth21','class'=>'form-control validate[required]','PlaceHolder'=> __('Enter News date ')));?>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Upload Document '));?></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<?php echo $this->Form->input('',array('type'=>'file','name'=>'news_document'));?>
						<?php echo $this->Form->input('',array('type'=>'hidden','value'=>$news_document,'name'=>'file2'));?>
					</div>
				</div>
			
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
					<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
					<?php 
					if(isset($edit))
						echo $this->Form->input(__('Edit News'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));
					else
						echo $this->Form->input(__('Add News'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));
					?>
					</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>			