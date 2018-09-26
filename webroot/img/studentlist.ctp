<script>
		$(document).ready(function() {
				$('#studentlist').DataTable({responsive: true});
		});
</script>

<script>

$( document ).ready(function(){
    $(".save").click(function(){ 
	
	$('.modal-body').html("");	
	
      var str = $(this).attr("print");
	 
       $.ajax({
       type: 'POST',
       url: '<?php echo $this->Url->build([
"controller" => "Student",
"action" => "result"]);?>',
	
       data : {id:str},
       success: function (data)
       {            
			  $('.modal-body').html(data);		
   },
error: function(e) {
       alert("An error occurred: " + e.responseText);
       console.log(e);	
}

       });

       });
	      

   });

</script>
<script>

$( document ).ready(function(){

	$('#select_stud').change(function(){

		$('#stud_list').remove();

	});


    $(".save").click(function(){ 
	
	$('.modal-body1').html("");
	
      var str = $(this).attr("print");
	 
       $.ajax({
       type: 'POST',
       url: '<?php echo $this->Url->build([
"controller" => "Student",
"action" => "studentparent"]);?>',
	
       data : {id:str},
       success: function (data)
       {            
 
			  $('.modal-body1').html(data);
				
   },
error: function(e) {
       alert("An error occurred: " + e.responseText);
       console.log(e);	
}

       });

       });
	      

   });

</script>

<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<?php if($role=='student' || $role == 'parent' || $role=='teacher')
		{ ?>
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Student List'),['controller' => 'Comman', 'action' => 'studentlist'],['escape' => false]);?>
		</li>
		<?php }
		if($role=='supportstaff')
		{ ?>
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Student List'),['controller' => 'Comman', 'action' => 'studentlist'],['escape' => false]);?>
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add New Student'),['controller' => 'Comman', 'action' => 'addstudent'],['escape' => false]);?>
		</li>
		<?php } ?>
	</ul>	
</div>
<?php if($role == 'parent' || $role == 'teacher' || $role == 'supportstaff'){?>
<div class="panel-body">	
	<form method="post">
		<div class="col-sm-3">		
				<?php echo $this->Form->label('Select Class');?>

					<?php
					
						if(isset($_POST['filter_class']))
						{
							echo @$this->form->select("select_stud",$class_data,["default"=>$cls_id,"empty"=>__("Select Class"),"class"=>"form-control validate[required]","id"=>"class_id"]);
						
						}
						else{
							echo @$this->form->select("select_stud",$class_data,["default"=>"","empty"=>__("Select Class"),"class"=>"form-control validate[required]","id"=>"class_id"]);
						}
			?>
						
				
		</div>
		<div class="col-md-3 button-list-possition">
			<?php echo $this->Form->label('');?>
			<input class="btn btn-info" type="submit" name="filter_class" value="Go">
		</div>
	</form>
</div>


<?php } ?>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="studentlist" class="table table-striped" cellspacing="0" width="100%">
				<?php if($role == 'parent')
				{ ?>
					<thead>
					<tr>
						<th><?php echo __('Photo');?></th>
						<th><?php echo __('Student Name');?></th>
						<th><?php echo __('Class');?></th>
						<th><?php echo __('Roll No.');?></th>
						<th><?php echo __('Student Email');?></th>
					</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php echo __('Photo');?></th>
							<th><?php echo __('Student Name');?></th>
							<th><?php echo __('Class Name');?></th>
							<th><?php echo __('Roll Number');?></th>
							<th><?php echo __('Student Email');?></th>
						</tr>
					</tfoot>
		<?php	}
				elseif($role == 'student' || $role == 'supportstaff' || $role == 'teacher')
				{ ?>
				<thead>
					<tr>
						<th><?php echo __('Photo');?></th>
						<th><?php echo __('Student Name');?></th>
						<th><?php echo __('Class');?></th>
						<th><?php echo __('Roll No.');?></th>
						<th><?php echo __('Student Email');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><?php echo __('Photo');?></th>
						<th><?php echo __('Student Name');?></th>
						<th><?php echo __('Class Name');?></th>
						<th><?php echo __('Roll Number');?></th>
						<th><?php echo __('Student Email');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</tfoot>
			<?php } ?>
				<tbody>
					<tr>
						
							<?php
								if($role=='student')
								{							
								foreach($it1 as $it2)
								{
								
									$name=$it2['first_name']." ".$it2['last_name'];
									echo "<td>" .$this->Html->image($it2['image'],array('height'=>'50px','width'=>'50px','class'=>'profileimg')) . "</td>";
									echo "<td>" .__($name). "</td>";
									echo "<td>" .__($classname) . "</td>";
									echo "<td>" .__($it2['roll_no']) . "</td>";
									echo "<td>" .__($it2['email']) . "</td>";
									
									if($it2['user_id']==$user_session_id)
									{
									echo "<td>".$this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-bar-chart')). __('View Result'),['action'=>'result','data-toggle'=>'modal','data-target'=>'#myModal','print'=>$it2['user_id'],'class'=>'btn btn-default save'],['escape' => false])." ".
									$this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-user')). __('View Parent'),['action'=>'studentparent','data-toggle'=>'modal','data-target'=>'#myModal1','print'=>$it2['user_id'],'class'=>'btn btn-default save'],['escape' => false])." ".
									$this->Html->link($this->Html->tag('i'," ", array('class' => 'fa fa-eye')).__(' View Attendance'),array('controller'=>'Student','action' => 'studentattendance', $it2['user_id']),array('class'=>'btn btnview btn-default','escape' => false)).
									"</td>";
									}
									else
									{
									echo "<td>"." "."</td>";
									}	
					
								?>
							</tr>
							<?php }
							}
							if($role=='supportstaff')
							{	
								$i=0;
								if(isset($parent_stud))
								{
									foreach($parent_stud as $it2)
									{
										$name=$it2['first_name']." ".$it2['last_name'];
										echo "<td>" .$this->Html->image($it2['image'],array('height'=>'50px','width'=>'50px','class'=>'profileimg')) . "</td>";
										echo "<td>" .__($name). "</td>";
										echo "<td>" .__($clsname) . "</td>";
										echo "<td>" .__($it2['roll_no']) . "</td>";
										echo "<td>" .__($it2['email']) . "</td>";
										
										echo "<td>".$this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-bar-chart')). __('View Result'),['action'=>'result','data-toggle'=>'modal','data-target'=>'#myModal','print'=>$it2['user_id'],'class'=>'btn btn-default save'],['escape' => false])." ".
										$this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-user')). __('View Parent'),['action'=>'studentparent','data-toggle'=>'modal','data-target'=>'#myModal1','print'=>$it2['user_id'],'class'=>'btn btn-default save'],['escape' => false]).
										"&nbsp;".$this->Html->link($this->Html->tag('i'," ", array('class' => 'fa fa-eye')).__(' View Attendance'),array('controller'=>'Student','action' => 'studentattendance', $it2['user_id']),array('class'=>'btn btnview btn-default','escape' => false)).
										"&nbsp;".$this->Html->link(__('Edit'),array('action' => 'updatestudent', $it2['user_id']),array('class'=>' btn btnview btn-info'))
										."</td>";
									$i++;
									?>
								
								</tr>
								
								<?php
									}
								}
								else
								{
																						
								foreach($it as $it2)
								{
								
									$name=$it2['first_name']." ".$it2['last_name'];
									echo "<td>" .$this->Html->image($it2['image'],array('height'=>'50px','width'=>'50px','class'=>'profileimg')) . "</td>";
									echo "<td>" .__($name). "</td>";
									echo "<td>" .__($class_name[$i]) . "</td>";
									echo "<td>" .__($it2['roll_no']) . "</td>";
									echo "<td>" .__($it2['email']) . "</td>";
									
									echo "<td>".$this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-bar-chart')). __('View Result'),['action'=>'result','data-toggle'=>'modal','data-target'=>'#myModal','print'=>$it2['user_id'],'class'=>'btn btn-default save'],['escape' => false])." ".
									$this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-user')). __('View Parent'),['action'=>'studentparent','data-toggle'=>'modal','data-target'=>'#myModal1','print'=>$it2['user_id'],'class'=>'btn btn-default save'],['escape' => false]).
									"&nbsp;".$this->Html->link($this->Html->tag('i'," ", array('class' => 'fa fa-eye')).__(' View Attendance'),array('controller'=>'Student','action' => 'studentattendance', $it2['user_id']),array('class'=>'btn btnview btn-default','escape' => false)).
									"&nbsp;".$this->Html->link(__('Edit'),array('action' => 'updatestudent', $it2['user_id']),array('class'=>' btn btnview btn-info'))
									."</td>";
								$i++;
					
								?>
							</tr>
							<?php }
							}
							}
							if($role=='teacher')
							{	
								$i=0;
								if(isset($parent_stud))
								{
									foreach($parent_stud as $it2)
									{
								
									$name=$it2['first_name']." ".$it2['last_name'];
									echo "<td>" .$this->Html->image($it2['image'],array('height'=>'50px','width'=>'50px','class'=>'profileimg')) . "</td>";
									echo "<td>" .__($name). "</td>";
									echo "<td>" .__($clsname) . "</td>";
									echo "<td>" .__($it2['roll_no']) . "</td>";
									echo "<td>" .__($it2['email']) . "</td>";
									
									echo "<td>".$this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-bar-chart')). __('View Result'),['action'=>'result','data-toggle'=>'modal','data-target'=>'#myModal','print'=>$it2['user_id'],'class'=>'btn btn-default save'],['escape' => false])." ".
									$this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-user')). __('View Parent'),['action'=>'studentparent','data-toggle'=>'modal','data-target'=>'#myModal1','print'=>$it2['user_id'],'class'=>'btn btn-default save'],['escape' => false])." ".
									$this->Html->link($this->Html->tag('i'," ", array('class' => 'fa fa-eye')).__(' View Attendance'),array('controller'=>'Student','action' => 'studentattendance', $it2['user_id']),array('class'=>'btn btnview btn-default','escape' => false)).
									"</td>";
									
									$i++;
									
									?>
								</tr>
						<?php }
								}
								else{
									
															
								foreach($it as $it2)
								{
								
									$name=$it2['first_name']." ".$it2['last_name'];
									echo "<td>" .$this->Html->image($it2['image'],array('height'=>'50px','width'=>'50px','class'=>'profileimg')) . "</td>";
									echo "<td>" .__($name). "</td>";
									echo "<td>" .__($class_name[$i]) . "</td>";
									echo "<td>" .__($it2['roll_no']) . "</td>";
									echo "<td>" .__($it2['email']) . "</td>";
									
									echo "<td>".$this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-bar-chart')). __('View Result'),['action'=>'result','data-toggle'=>'modal','data-target'=>'#myModal','print'=>$it2['user_id'],'class'=>'btn btn-default save'],['escape' => false])." ".
									$this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-user')). __('View Parent'),['action'=>'studentparent','data-toggle'=>'modal','data-target'=>'#myModal1','print'=>$it2['user_id'],'class'=>'btn btn-default save'],['escape' => false])." ".
									$this->Html->link($this->Html->tag('i'," ", array('class' => 'fa fa-eye')).__(' View Attendance'),array('controller'=>'Student','action' => 'studentattendance', $it2['user_id']),array('class'=>'btn btnview btn-default','escape' => false)).
									"</td>";
									
								$i++;
								
							?>
					</tr>
					
					<?php 
					}
					}
							}if($role == 'parent'){
								
								if(isset($parent_stud))	
								{
								foreach($parent_stud as $it2)
								{
									$name=$it2['first_name']." ".$it2['last_name'];
							?>
								<td><?php echo $this->Html->image($it2['image'],array('height'=>'50px','width'=>'50px','class'=>'profileimg'));?></td>
								<td><?php echo $name; ?></td>
								<td><?php echo $clsname; ?></td>
								<td><?php echo $it2['roll_no'];?></td>
								<td><?php echo $it2['email'];?></td>

							</tr>
							
							<?php

						}
						}
						else
						{
							$i=0;							
								foreach($it as $it2)
								{
								
									$name=$it2['first_name']." ".$it2['last_name'];
									echo "<td>" .$this->Html->image($it2['image'],array('height'=>'50px','width'=>'50px','class'=>'profileimg')) . "</td>";
									echo "<td>" .__($name). "</td>";
									echo "<td>" .__($class_name[$i]) . "</td>";
									echo "<td>" .__($it2['roll_no']) . "</td>";
									echo "<td>" .__($it2['email']) . "</td>";
									echo "</tr>";
								$i++;
							}
						}
					?>
					
					<?php }
						?>
					
				</tbody>
				</table>
		</div>
	</div>
</div>


<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      
	  <div class="modal-header"> 
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 id="myLargeModalLabel" class="modal-title"> <?php echo __('School Management System'); ?> </h4>
		</div>
	  
      <div class="modal-body">
		
      </div>
      
	  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"> <?php echo __('Close'); ?> </button>
      </div>
    </div>

  </div>
</div>

<div id="myModal1" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      
	  <div class="modal-header"> 
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 id="myLargeModalLabel" class="modal-title"> <?php echo __('Parent of Student'); ?> </h4>
		</div>
	  
      <div class="modal-body1">
		
      </div>
      
	  <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"> <?php echo __('Close'); ?> </button>
      </div>
    </div>

  </div>
</div>