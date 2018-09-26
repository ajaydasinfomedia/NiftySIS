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

<div class="row">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-pencil fa-lg')) . __('Manage Marks'),['controller' => 'Comman', 'action' => 'addmarks'],['escape' => false]);?>
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-upload fa-lg')) . __('Export Marks'),['controller' => 'Comman', 'action' => 'exploremark'],['escape' => false]);?>
		</li>
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-align-left fa-lg')) . __('Add Multiple Subject Marks'),['controller' => 'Comman', 'action' => 'addmultiplemark'],['escape' => false]);?>
		</li>
	</ul>	
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addmultiplemark']]);?>
			
				<div class="form-group">
					<div class="col-md-3 col-sm-6 col-xs-12">
						
						<?php echo $this->Form->label('Select Exam');?><span style="color:red;"><?php echo " *"; ?></span>
						
						<?php 
																			
							if(isset($_POST['add_multiple_subject_marks']))
							{
								echo @$this->form->select("exam_id",$exam_id,["default"=>$e_id,"empty"=>__("Select Exam"),"class"=>"form-control validate[required,maxSize[50]]",'id'=>'exam_id']);	
							}
							else{
								echo @$this->form->select("exam_id",$exam_id,["default"=>"","empty"=>__("Select Exam"),"class"=>"form-control validate[required,maxSize[50]]",'id'=>'exam_id']);	
							}
							
						?>
						
					</div>
					
					
					<div class="col-md-3 col-sm-6 col-xs-12">
						
						<?php echo $this->Form->label('Select Class');?><span style="color:red;"><?php echo " *"; ?></span>
						
						<?php 
							
						if(isset($_POST['add_multiple_subject_marks']))
						{
							echo @$this->form->select("class_id",$class_id,["default"=>$c_id,"empty"=>__("Select Class"),"class"=>"form-control validate[required,maxSize[50]]",'id'=>'class_id']);
						}
						else{
							echo @$this->form->select("class_id",$class_id,["default"=>"","empty"=>__("Select Class"),"class"=>"form-control validate[required,maxSize[50]]",'id'=>'class_id']);
						}
							
						?>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
						
						<?php echo $this->Form->label('Select Section');?><span style="color:red;"><?php echo " *"; ?></span>	
						<select class="form-control validate[required,maxSize[50]] ajaxdata" name="section" id="dep">
						<?php if(isset($sec_id)){?>
							<option value="<?php echo $sec_id; ?>"><?php echo $this->Setting->get_class_section($sec_id); ?></option>
								<?php } 
							else
								echo "<option value=''>" ?> <?php echo __('Select Section'); ?> <?php "</option>";
						?>
						</select>
					</div>
					
					<div class="col-md-3 col-sm-6 col-xs-12 button-possition">
						<?php echo $this->Form->label(__(''));?>
						<?php echo $this->Form->input(__('Go'),array('type'=>'submit','name'=>'add_multiple_subject_marks','class'=>'btn btn-info'));?>

					</div>
					
				</div>
			
			<?php $this->Form->end(); ?>
			</div>
			
			<div class="mark_tbl"> 
     
			<?php 
			if(isset($_POST['add_multiple_subject_marks']))
			{	
				$total_mark = 0;			
				$total_mark = $this->Setting->get_exam_data($e_id,'total_mark');
			?>
			<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addmultiplemark']]);?>
			<div class="table-responsive">
				<div id="example_wrapper" class="dataTables_wrapper">
					<table id="classlist" class="table" cellspacing="0" width="100%">
		
					<thead>
						<tr>
							<th><?php echo __('Student ID');?></th>
							<th><?php echo __('Roll No.');?></th>
							<th><?php echo __('Name');?></th>
							<?php 
							
								if(!empty($sub_m_data))
								{			
									foreach($sub_m_data as $sub_id)
									{
										
										echo "<th> ".$sub_id['sub_name']." </th>";
									}
								}
							?>
							<th>&nbsp;</th>
							
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php echo __('Student ID');?></th>
							<th><?php echo __('Roll No.');?></th>
							<th><?php echo __('Name');?></th>
							<?php 
							
								if(!empty($sub_m_data))
								{			
									foreach($sub_m_data as $sub_id)
									{
										
										echo "<th> ".$sub_id['sub_name']." </th>";
									}
								}
							?>
							<th>&nbsp;</th>
							
						</tr>
					<tfoot>
					<tbody>
						<?php 
						foreach($user as $user_data)
						{
							echo "<tr>";			
							$name=$user_data['first_name']." ".$user_data['last_name'];
							$user_m_id=$user_data['user_id'];
											
							echo "<td>" . $this->setting->get_studentID($user_data['user_id']) . "</td>";
							echo "<td>" . $user_data['roll_no'] . "</td>";
							echo "<td>" . $name . "</td>";
											
							foreach($sub_m_data as $sub_id)
							{
								$x=0;
								foreach($marktabel as $marktabeldata)	
								{
									if($marktabeldata['student_id'] == $user_data['user_id'] && $marktabeldata['exam_id'] == $e_id && $marktabeldata['class_id'] == $c_id && $marktabeldata['subject_id'] == $sub_id['subid'])
									{
									?>
										<td>
											<label> <?php echo __('Mark (Out of '.$total_mark.')'); ?> </label><BR>
											<input type="number" name="marks_<?php echo $user_data['user_id'];?>_<?php echo $sub_id['subid'];?>_mark" value="<?php echo $marktabeldata['marks'];?>" class="form-control validate[required,custom[onlyNumberSp]] text-input" min="0" max="<?php echo $total_mark;?>">
											<BR>															
											<label> <?php echo __('Comment'); ?> </label><BR>
											<input type="text" name="marks_<?php echo $user_data['user_id'];?>_<?php echo $sub_id['subid'];?>_comment" value="<?php echo $marktabeldata['marks_comment'];?>" class="form-control validate[required,custom[onlyNumberSp]] text-input">
										</td>
										<?php	
										$x=1;	
										break;
									}
								}
								if($x==0)
								{
									echo '<td>
									<label>Mark (Out of '.$total_mark.')</label><BR>
									<input type="number" name="marks_'.$user_data['user_id'].'_'.$sub_id['subid'].'_mark" value="0" class="form-control validate[required,custom[onlyNumberSp]] text-input" min="0" max="'.$total_mark.'"><BR>
									<label>Comment</label><BR>
									<input type="text" name="marks_'.$user_data['user_id'].'_'.$sub_id['subid'].'_comment" value="" class="form-control text-input">																					
									</td>'; 
								}
							}
							echo "<td>" ."<button type='submit' name='add_single_student_mark' value='".$user_data['user_id']."' class='btn btn-success'>Add/Save Mark</button>"."</td>";
							echo "<tr>";
						 } 
						 ?>		
					</tbody>
					</table>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="pull-right">
							<?php echo $this->Form->input('Update All Marks',array('type'=>'submit','name'=>'save_all_multiple_subject_marks','class'=>'btn btn-success'));?>
						</div>
					</div>
					</div>
					</div>
					<?php $this->Form->end(); ?>
					</div>
	<?php	}
			?>					
		</div>
</div>