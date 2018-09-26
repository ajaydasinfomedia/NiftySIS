<?php
use Cake\Routing\Router;

$title = isset($row['title'])?$row['title']:'';
$class = isset($row['class_id'])?$row['class_id']:'';
$sec_id = isset($row['section_id'])?$row['section_id']:'';
$s_id = isset($row['subject_id'])?$row['subject_id']:'';
$content = isset($row['content'])?$row['content']:'';
$submission_date = isset($row['submission_date'])?$row['submission_date']:'';
$user_id=$this->request->session()->read('user_id');
$get_role=$this->Setting->get_user_role($user_id);
?>
<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Homework List'),['controller' => 'Comman', 'action' => 'homeworklist'],['escape' => false]);?>
		</li>
		<li class="active">		
			<?php  
			if(isset($edit))
				echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-upload fa-lg')) . __('Upload Homework'),['controller' => 'Comman','action' => 'studaddsubmission',$this->Setting->my_simple_crypt($row['homework_id'],'e')],['escape' => false]);
				
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
				<?php echo $this->Form->input('title',array('name'=>'title','class'=>'form-control validate[required]','PlaceHolder'=> __('Enter title'),'value'=>$title,'disabled'));?>
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Subject '));?><span style="color:red;"><?php echo " *"; ?></span>
			</div>
			<div class="col-md-10 col-sm-10 col-xs-12">
				<div class="result">
					<select class="form-control validate[required]" name="sub_id" disabled>
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
				<?php echo $this->Form->input('',array('disabled','name'=>'homework_content','value'=>$content,'type'=>'textarea','class'=>'form-control validate[required]','PlaceHolder'=> __('Enter Content ')));?>	
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2 col-sm-2 col-xs-12 label_float">
				<?php echo $this->Form->label(__('Submission Date '));?><span style="color:red;"><?php echo " *"; ?></span>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12">
				<?php 
				if(isset($edit))
					$submission_date = date("Y-m-d", strtotime($submission_date));
				else
					$submission_date = date('Y-m-d');
				
				echo $this->Form->input('',array('disabled','id'=>'','value'=>$submission_date,'name'=>'submission_date','class'=>'form-control validate[required]','PlaceHolder'=> __('Submission Date ')));?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2 col-sm-2 col-xs-12 label_float">
				<?php echo $this->Form->label(__('Syllabus'));?>
			</div>
			<div class="col-md-10 col-sm-10 col-xs-12">
				<?php 
				if($row['syllabus'] == NULL || $row['syllabus'] == '')
				{
					echo '';
				}else{
				$file = WWW_ROOT.'syllabus'.'/'.$row['syllabus'];
				$file1 =$this->request->webroot.'syllabus/'.$row['syllabus'];
				echo "&nbsp";
				echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-download fa-lg')) . __('Download Syllabus'),['action' => 'readfile',$row['syllabus']],['escape' => false,'class'=>'btn btn-default']);
				
				?>
				<a href="<?php echo $file1;?>" target="_blank" class="btn btn-default"><i class="fa fa-eye"></i><?php echo " ".__('View Syllabus'); ?></a> 						
				<?php
				}
				?>
			</div>
		</div>
		<?php
		if(isset($status) && $status == 0)
		{
			?>
			<div class="form-group">
				<div class="col-md-2 col-sm-2 col-xs-12 label_float">
					<?php echo $this->Form->label(__('Upload Homework '));?><span style="color:red;"><?php echo " *"; ?></span>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12">
					<?php echo $this->Form->input('',array('type'=>'file','name'=>'submission','class'=>' validate[required]'));?>
				</div>
			</div>
			<?php
		}
		else
		{
		?>
		<div class="col-md-offset-2 col-sm-offset-2 col-md-8 col-sm-8 col-xs-12" style="padding:0px;">
			<label class="col-sm-6 control-label" style="color: #008000;">HOMEWORK SUBMITTED !</label>
		</div>
		<?php
		}
		?>
		<div class="col-md-offset-2 col-sm-offset-2 col-md-8 col-sm-8 col-xs-12" style="padding:0px;">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<?php 
				if(isset($status) && $status == 0)
				{
					echo $this->Form->input(__('Save Homework'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));
				}	
				?>
			</div>
		</div>
	</div>
</div>