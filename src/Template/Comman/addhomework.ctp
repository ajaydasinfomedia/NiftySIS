<?php
use Cake\Routing\Router;
?>
<script>
$(document).ready(function() {
$('#submission_date').datepicker
		({				
			minDate: new Date(),
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'yy-mm-dd',	
		});
		});
</script>
<script>
jQuery(document).ready(function() {
	
	jQuery("#dep").change(function(){
	
		var get_class_id=$(this).val();

			if($(this).val() == ''){
				
			}
			else
			{
				$.ajax({

					type:'POST',
					url:'<?php echo $this->Url->build(["controller"=>"User","action"=>"showdata"])?>',
					data:{id:get_class_id},
					
					success:function(getdata){
						$(".result").html(getdata);
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
<script>
$( document ).ready(function(){
	
    $("#class_id").change(function(){
	
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
<?php
$title = isset($row['title'])?$row['title']:'';
$class = isset($row['class_id'])?$row['class_id']:'';
$sec_id = isset($row['section_id'])?$row['section_id']:'';
$s_id = isset($row['subject_id'])?$row['subject_id']:'';
$content = isset($row['content'])?$row['content']:'';
$submission_date = isset($row['submission_date'])?$row['submission_date']:'';
?>
<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Homework List'),['controller' => 'Comman', 'action' => 'homeworklist'],['escape' => false]);?>
		</li>
		<li class="active">		
			<?php  
			if(isset($edit))
				echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('Edit Homework'),['controller' => 'Comman', 'action' => 'addhomework',$this->Setting->my_simple_crypt($row['homework_id'],'e')],['escape' => false]);
			else
				echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Homework'),['controller' => 'Comman', 'action' => 'addhomework'],['escape' => false]);
			?>
		</li>
	</ul>	
</div>
<div class="row">			
	<div class="panel-body">
		<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addhomework']]);?>
				
		<div class="form-group">
			<div class="col-md-2 col-sm-2 col-xs-12 label_float">
				<?php echo $this->Form->label(__('Title'));?><span style="color:red;"><?php echo " *"; ?></span>
			</div>
			<div class="col-md-10 col-sm-10 col-xs-12">
				<?php echo $this->Form->input('title',array('name'=>'title','class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=> __('Enter title'),'value'=>$title));?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Class '));?><span style="color:red;"><?php echo " *"; ?></span>
			</div>
			<div class="col-md-10 col-sm-10 col-xs-12">
				<?php 
				echo @$this->form->select("class_id",$class_id,["default"=>$class,"empty"=>__("Select Class"),"class"=>"form-control validate[required,maxSize[50]]",'id'=>'class_id']);
				?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2 col-sm-2 col-xs-12 label_float">
				<?php echo $this->Form->label(__('Select Section '));?><span style="color:red;"><?php echo " *"; ?></span>
			</div>
			<div class="col-md-10 col-sm-10 col-xs-12">
				<select class="form-control validate[required,maxSize[50]] ajaxdata" name="section" id="dep">
					<?php if(isset($sec_id)){?>
						<option value="<?php echo $sec_id; ?>"><?php echo $this->Setting->get_class_section($sec_id); ?></option>
					<?php } 
					else
						echo "<option value=''>"?> <?php echo __('Select Section'); ?> <?php "</option>";
					?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Subject '));?><span style="color:red;"><?php echo " *"; ?></span>
			</div>
			<div class="col-md-10 col-sm-10 col-xs-12">
				<div class="result">
					<select class="form-control validate[required,maxSize[50]]" name="sub_id">
						<?php if(isset($s_id))
						{ ?>
							<option value="<?php echo $s_id; ?>"><?php echo $this->Setting->get_subject_name($s_id);?></option>
						<?php	}else{ ?>
							<option value=""><?php echo __('Select Subject'); ?></option>
							<?php foreach($get_data as $id):?><option value="<?php echo $id['subid'];?>"><?php echo $id['sub_name'];?></option> <?php endforeach;?>
						<?php	}?>		
					</select>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2 col-sm-2 col-xs-12 label_float">
				<?php echo $this->Form->label(__('Content'));?><span style="color:red;"><?php echo " *"; ?></span>
			</div>
			<div class="col-md-10 col-sm-10 col-xs-12">
				<?php echo $this->Form->input('',array('name'=>'homework_content','value'=>$content,'type'=>'textarea','class'=>'form-control validate[required,maxSize[500]]','PlaceHolder'=> __('Enter Content ')));?>	
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2 col-sm-2 col-xs-12 label_float">
				<?php echo $this->Form->label(__('Submission Date '));?><span style="color:red;"><?php echo " *"; ?></span>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12 datepickericon">
				<?php 
				if(isset($edit))
					$submission_date = date("Y-m-d", strtotime($submission_date));
				else
					$submission_date = date('Y-m-d');
				
				echo $this->Form->input('',array('id'=>'submission_date','value'=>$submission_date,'name'=>'submission_date','class'=>'form-control validate[required]','PlaceHolder'=> __('Submission Date ')));?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Import Syllabus '));?></div>
			<div class="col-md-10 col-sm-10 col-xs-12">
				<?php 
				if(isset($edit))
				{
					echo $this->Form->input('',array('type'=>'hidden','value'=>$row['syllabus'],'name'=>'file2'));
					echo $this->Form->input('',array('type'=>'file','name'=>'syllabus'));
				}
				else
					echo $this->Form->input('',array('type'=>'file','name'=>'syllabus'));
				?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2 col-sm-2 col-xs-12 label_float">
				<?php echo $this->Form->label(__('Enable Send Mail To Parents'));?>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12">	
				<input type="checkbox" name="parent_mail" value="1">
			</div>
		</div>
		<div class="col-md-offset-2 col-sm-offset-2 col-md-8 col-sm-8 col-xs-12">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<?php echo $this->Form->input(__('Save Homework'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
			</div>
		</div>
	</div>
</div>