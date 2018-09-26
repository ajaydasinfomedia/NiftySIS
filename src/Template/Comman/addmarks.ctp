<script>
		jQuery(document).ready(function() {
		
		jQuery("#dep").change(function(){
		
			var get_class_id=$(this).val();

				if($(this).val() == ''){
					
				}else{

			$.ajax({

				type:'POST',
				url:'<?php echo $this->Url->build(["controller"=>"User","action"=>"showdata"])?>',
				data:{id:get_class_id},


				success:function(getdata){

					$(".result").html(getdata);

				},
/*
				beforeSend:function(){
					$(".result").html("<center><h5>Loading...</h5></center>");
				},
*/
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

<div class="row">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-pencil fa-lg')) . __('Manage Marks'),['controller' => 'Comman', 'action' => 'addmarks'],['escape' => false]);?>
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-upload fa-lg')) . __('Export Marks'),['controller' => 'Comman', 'action' => 'exploremark'],['escape' => false]);?>
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-align-left fa-lg')) . __('Add Multiple Subject Marks'),['controller' => 'Comman', 'action' => 'addmultiplemark'],['escape' => false]);?>
		</li>
	</ul>	
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addmarks']]);?>
			
				<div class="form-group">
					<div class="col-md-3 col-sm-6 col-xs-12">
						
						<?php echo $this->Form->label('Select Exam');?><span style="color:red;"><?php echo " *"; ?></span>					
						<?php 
																				
							if(isset($_POST['manage_mark']))
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
			
						if(isset($_POST['attendance']))
						{
							echo @$this->form->select("class_id",$class_id,["default"=>$c_id,"empty"=>__("Select Class"),"class"=>"form-control validate[required,maxSize[50]]",'id'=>'class_id']);
						}
						else
						{
							echo @$this->form->select("class_id",$class_id,["default"=>"","empty"=>__("Select Class"),"class"=>"form-control validate[required,maxSize[50]]",'id'=>'class_id']);
						}

						?>

				<!--		
						<select class="form-control validate[required]" id="class_id" name="class_id">
									<option>Select Class Name</option>
									<?php foreach($class_id as $id):?><option value="<?php echo $id['class_id'];?>"><?php echo $id['class_name'];?></option> <?php endforeach;?>
						</select> -->
						
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
					
					<div class="col-md-3 col-sm-6 col-xs-12">
						
						<?php echo $this->Form->label(_('Select Subject'));?><span style="color:red;"><?php echo " *"; ?></span>
						
						<div class="result">
							<select class="form-control validate[required,maxSize[50]]" name="sub_id">
						<?php if(isset($s_id))
						{ ?>
							<option value="<?php echo $s_id; ?>"><?php echo $sub_nm;?></option>
				<?php	}else{ ?>
							<option> <?php echo __('Select Subject'); ?> </option>
							<?php foreach($get_data as $id):?><option value="<?php echo $id['subid'];?>"><?php echo $id['sub_name'];?></option> <?php endforeach;?>
				<?php	}?>

							
					</select>

						</div>
						
					</div>	
				
					<div class="col-md-3 col-sm-6 col-xs-12 button-possition">

						<?php echo $this->Form->input(__('Manage Marks'),array('type'=>'submit','name'=>'manage_mark','class'=>'btn btn-info'));?>

					</div>
					
				</div>
			
			<?php $this->Form->end(); ?>
			</div>

		
	
		<div class="mark_tbl"> 
     
			<?php 
			if(isset($_POST['manage_mark']))
			{	
				$total_mark = 0;			
				$total_mark = $this->Setting->get_exam_data($e_id,'total_mark');
			?>
			
			<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
			<div class="table-responsive">
				<div id="example_wrapper" class="dataTables_wrapper">
					<table id="classlist" class="table" cellspacing="0" width="100%">
						
					<thead>
						<tr>
							<th><?php echo __('Student ID');?></th>
							<th><?php echo __('Roll No.');?></th>
							<th><?php echo __('Name');?></th>
							<th><?php echo __('Mark Obtained (Out of '.$total_mark.')');?></th>
							<th><?php echo __('Comment');?></th>
							<th><?php echo __('Action');?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php echo __('Student ID');?></th>
							<th><?php echo __('Roll No.');?></th>
							<th><?php echo __('Name');?></th>
							<th><?php echo __('Mark Obtained (Out of '.$total_mark.')');?></th>
							<th><?php echo __('Comment');?></th>
							<th><?php echo __('Action');?></th>
						</tr>
					</tfoot>
					<tbody>						
						<?php 
						foreach($user as $user_data)
						{
							echo "<tr>";
							
							$x=0;
							$name=$user_data['first_name']." ".$user_data['last_name'];
							
							echo "<td>" . $this->setting->get_studentID($user_data['user_id']) . "</td>";
							echo "<td>" . $user_data['roll_no'] . "</td>";
							echo "<td>" . $name . "</td>";
							
							foreach($marktabel as $marktabeldata)	
							{
								if($marktabeldata['student_id'] == $user_data['user_id'] && $marktabeldata['exam_id'] == $e_id && $marktabeldata['class_id'] == $c_id && $marktabeldata['subject_id'] == $s_id)
								{
									
								?>	
									<td><input type="number" name="marks_<?php echo $user_data['user_id'];?>" value="<?php echo $marktabeldata['marks'];?>" class="form-control validate[required,custom[onlyNumberSp]] text-input" min="0" max="<?php echo $total_mark;?>"></td>
									<td><input type="text" name="marks_comment_<?php echo $user_data['user_id'];?>" value="<?php echo $marktabeldata['marks_comment'];?>" class="form-control validate[required,custom[onlyNumberSp]] text-input"></td>
									
									<?php
										$x=1;	
										break;
							
								}
							}
							
							if($x==0){
							
								echo "<td>" ."<input type='number' name='marks_".$user_data['user_id']."' class='form-control validate[required,custom[onlyNumberSp]] text-input' min='0' max='".$total_mark."'>". "</td>";
								echo "<td>" ."<input type='text' name='marks_comment_".$user_data['user_id']."' class='form-control validate[required,custom[onlyNumberSp]] text-input'>" . "</td>";
							}
							
							echo "<td>" ."<button type='submit' name='add_mark' value='".$user_data['user_id']."' class='btn btn-success'>Update</button>"."</td>";
																
							echo "</tr>";
						 }
						 ?>												
					</tbody>
					</table>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="pull-right">
							<?php echo $this->Form->input('Update All Marks',array('type'=>'submit','name'=>'save_all_marks','class'=>'btn btn-success'));?>
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