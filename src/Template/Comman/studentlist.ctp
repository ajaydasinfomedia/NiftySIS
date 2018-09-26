<script>
$(document).ready(function() {
	$('#studentlist').DataTable({responsive: true});
});
</script>

<script>

$( document ).ready(function(){
    $('body').on('click', '.save', function() {
	
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


    $('body').on('click', '.save', function() {
	
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
	     
		jQuery('body').on('click', '.viewdetail', function() {	

		var get_id=jQuery(this).attr('viewID');

		$.ajax({
			type:'POST',
			url:'<?php echo $this->Url->build(["controller"=>"User","action"=>"userinfo"]); ?>',
			data:{id:get_id},

			success:function(getdata){
				$(".modal-body").html(getdata);
			},
			beforeSend:function(){
				$(".modal-body").html("<center><h2 class=text-muted><b>Loading...</b></h2></center>");
			},
			error:function(e){
				
				console.log(e);
			},
		});
	});
	
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
<div class="panel-body" style="padding-top: 30px;">	
	<form method="post">
		<div class="col-md-3 col-sm-6 col-xs-12">		
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
		<div class="col-md-3 col-sm-6 col-xs-12">	
			<?php echo $this->Form->label(__('Select Section'));?>	
			<select class="form-control ajaxdata" name="section" id="dep">
			<?php if(isset($sec_id)){?>
				<option value="<?php echo $sec_id; ?>"><?php echo $this->Setting->get_class_section($sec_id); ?></option>
					<?php } 
				else
					echo "<option value=''>"?> <?php echo __('Select Section'); ?> <?php "</option>";
			?>
			</select>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 button-list-possition">
			<?php echo $this->Form->label('');?>
			<div class="submit">
				<input class="btn btn-info" type="submit" name="filter_class" value="Go">
			</div>
		</div>
	</form>
</div>


<?php } 

if(isset($parent_stud))
{
?>
<div class="panel-body">	
	<div class="table-responsive" style="padding-top: 0px;">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="studentlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
				<?php if($role == 'parent')
				{ 
					if(isset($parent_stud))
					{
					?>
						<tr>
							<th><?php echo __('Photo');?></th>
							<th><?php echo __('Student Name');?></th>
							<th><?php echo __('Student ID');?></th>
							<th><?php echo __('Class');?></th>
							<th><?php echo __('Section');?></th>
						</tr>
						<?php		
					}
				}
				elseif($role == 'student' || $role == 'supportstaff' || $role == 'teacher')
				{ 
					if(isset($parent_stud))
					{
				?>
						<tr>
							<th><?php echo __('Photo');?></th>
							<th><?php echo __('Student Name');?></th>
							<th><?php echo __('Student ID');?></th>
							<th><?php echo __('Class');?></th>
							<th><?php echo __('Section');?></th>
							<th><?php echo __('Action');?></th>
						</tr>
						<?php
					}
				}
				?>
				</thead>
				<tfoot>
				<?php if($role == 'parent')
				{ 
					if(isset($parent_stud))
					{
					?>
						<tr>
							<th><?php echo __('Photo');?></th>
							<th><?php echo __('Student Name');?></th>
							<th><?php echo __('Student ID');?></th>
							<th><?php echo __('Class');?></th>
							<th><?php echo __('Section');?></th>
						</tr>
						<?php		
					}
				}
				elseif($role == 'student' || $role == 'supportstaff' || $role == 'teacher')
				{ 
					if(isset($parent_stud))
					{
				?>
						<tr>
						<th><?php echo __('Photo');?></th>
						<th><?php echo __('Student Name');?></th>
						<th><?php echo __('Student ID');?></th>
						<th><?php echo __('Class');?></th>
						<th><?php echo __('Section');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
					<?php
					}
				}
				?>
				</tfoot>
				<tbody>					
							<?php
								if($role=='student')
								{
								if(isset($parent_stud))
								{				
								foreach($parent_stud as $it2)
								{
									$result_cnt = $this->Setting->user_mark_count($it2['user_id'],$it2['classname']);
									$parent_cnt = $this->Setting->user_parent_count($it2['user_id']);
									
									$name=$it2['first_name']." ".$it2['last_name'];
									echo "<tr>";
									echo "<td>" .$this->Html->image($it2['image'],array('height'=>'50px','width'=>'50px','class'=>'profileimg')) . "</td>";
									echo "<td>" .__($name). "</td>";
									echo "<td>" .__($it2['studentID_prefix'].$it2['studentID']). "</td>";
									echo "<td>" .__($this->Setting->get_class_id($it2['classname'])) . "</td>";
									echo "<td>" .__($this->Setting->get_class_section($it2['classsection'])) . "</td>";
									
									if($it2['user_id']==$user_session_id)
									{
									echo "<td>".
									$this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-eye')). __('View Details'),['action'=>'#','data-toggle'=>'modal','data-target'=>'#myModalview','viewID'=>$it2['user_id'],'class'=>'btn btn-primary viewdetail'],['escape' => false]).
									"&nbsp;";
									if($result_cnt >= 1){ echo $this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-bar-chart')). __('View Result'),['action'=>'result','data-toggle'=>'modal','data-target'=>'#myModal','print'=>$it2['user_id'],'class'=>'btn btn-default save'],['escape' => false])." ";}
									if($parent_cnt >= 1){ echo $this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-user')). __('View Parent'),['action'=>'studentparent','data-toggle'=>'modal','data-target'=>'#myModal1','print'=>$it2['user_id'],'class'=>'btn btn-default save'],['escape' => false])." ";}
									if($it2['exam_hall_receipt'] == 1)
									{
										echo $this->Html->link(__('Exam Receipt'),array('action' => 'studentexamlist', $this->Setting->my_simple_crypt($it2['user_id'],'e')),array('class'=>' btn btnview btn-success'))." ";
									}
									echo $this->Html->link($this->Html->tag('i'," ", array('class' => 'fa fa-eye')).__(' View Attendance'),array('controller'=>'Student','action' => 'studentattendance', $this->Setting->my_simple_crypt($it2['user_id'],'e')),array('class'=>'btn btnview btn-default','escape' => false))." ".
									$this->Html->link(__('Edit'),array('action' => 'updatestudent', $this->Setting->my_simple_crypt($it2['user_id'],'e')),array('class'=>' btn btnview btn-info')).
									"</td>";
									}
									else
									{
									echo "<td>"." "."</td>";
									}	
									echo "</tr>";
								?>
							<?php }
								}
							}
							if($role=='supportstaff')
							{	
								$i=0;
								if(isset($parent_stud))
								{
									foreach($parent_stud as $it2)
									{
										$result_cnt = $this->Setting->user_mark_count($it2['user_id'],$it2['classname']);
										$parent_cnt = $this->Setting->user_parent_count($it2['user_id']);
									
										$name=$it2['first_name']." ".$it2['last_name'];
										echo "<tr>";
										echo "<td>" .$this->Html->image($it2['image'],array('height'=>'50px','width'=>'50px','class'=>'profileimg')) . "</td>";
										echo "<td>" .__($name). "</td>";
										echo "<td>" .__($it2['studentID']). "</td>";
										echo "<td>" .__($this->Setting->get_class_id($it2['classname'])) . "</td>";
										echo "<td>" .__($this->Setting->get_class_section($it2['classsection'])) . "</td>";						
										echo "<td>";
										if($result_cnt >= 1){ echo $this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-bar-chart')). __('View Result'),['action'=>'result','data-toggle'=>'modal','data-target'=>'#myModal','print'=>$it2['user_id'],'class'=>'btn btn-default save'],['escape' => false])." ";}
										if($parent_cnt >= 1){ echo $this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-user')). __('View Parent'),['action'=>'studentparent','data-toggle'=>'modal','data-target'=>'#myModal1','print'=>$it2['user_id'],'class'=>'btn btn-default save'],['escape' => false])." ";}
										echo $this->Html->link($this->Html->tag('i'," ", array('class' => 'fa fa-eye')).__(' View Attendance'),array('controller'=>'Student','action' => 'studentattendance', $this->Setting->my_simple_crypt($it2['user_id'],'e')),array('class'=>'btn btnview btn-default','escape' => false)).
										"&nbsp;".$this->Html->link(__('Edit'),array('action' => 'updatestudent', $this->Setting->my_simple_crypt($it2['user_id'],'e')),array('class'=>' btn btnview btn-info'))
										."</td>";
									$i++;
										echo "</tr>";
									?>
								
								<?php
									}
								}
								else
								{									
								?>
									<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Student Data Available');?></h4></div>
								<?php	
								}
							}
							if($role=='teacher')
							{	
								$i=0;
								if(isset($parent_stud))
								{
									foreach($parent_stud as $it2)
									{
										$result_cnt = $this->Setting->user_mark_count($it2['user_id'],$it2['classname']);
										$parent_cnt = $this->Setting->user_parent_count($it2['user_id']);
										
										$name=$it2['first_name']." ".$it2['last_name'];
										echo "<tr>";
										echo "<td>" .$this->Html->image($it2['image'],array('height'=>'50px','width'=>'50px','class'=>'profileimg')) . "</td>";
										echo "<td>" .__($name). "</td>";
										echo "<td>" .__($it2['studentID']). "</td>";
										echo "<td>" .__($this->Setting->get_class_id($it2['classname'])) . "</td>";
										echo "<td>" .__($this->Setting->get_class_section($it2['classsection'])) . "</td>";
										echo "<td>";
										if($result_cnt >= 1){ echo $this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-bar-chart')). __('View Result'),['action'=>'result','data-toggle'=>'modal','data-target'=>'#myModal','print'=>$it2['user_id'],'class'=>'btn btn-default save'],['escape' => false])." ";}
										if($parent_cnt >= 1){ echo $this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-user')). __('View Parent'),['action'=>'studentparent','data-toggle'=>'modal','data-target'=>'#myModal1','print'=>$it2['user_id'],'class'=>'btn btn-default save'],['escape' => false])." ";}
										if($it2['exam_hall_receipt'] == 1)
										{
											echo $this->Html->link(__('Exam Receipt'),array('action' => 'studentexamlist', $this->Setting->my_simple_crypt($it2['user_id'],'e')),array('class'=>' btn btnview btn-success'))." ";
										}
										echo $this->Html->link($this->Html->tag('i'," ", array('class' => 'fa fa-eye')).__(' View Attendance'),array('controller'=>'Student','action' => 'studentattendance', $this->Setting->my_simple_crypt($it2['user_id'],'e')),array('class'=>'btn btnview btn-default','escape' => false)).
										"</td>";
									
									$i++;
										echo "</tr>";
									?>
						<?php }
								}
								else{									
								?>
									<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Student Data Available');?></h4></div>
								<?php	
								}
							}
							if($role == 'parent')
							{	
								if(isset($parent_stud))	
								{
								foreach($parent_stud as $it2)
								{
									$name=$it2['first_name']." ".$it2['last_name'];
									$result_cnt = $this->Setting->user_mark_count($it2['user_id'],$it2['classname']);
									$parent_cnt = $this->Setting->user_parent_count($it2['user_id']);
								?>
								<tr>
									<td><?php echo $this->Html->image($it2['image'],array('height'=>'50px','width'=>'50px','class'=>'profileimg'));?></td>
									<td><?php echo $name; ?></td>
									<td><?php echo $it2['studentID']; ?></td>
									<td><?php echo $this->Setting->get_class_id($it2['classname']); ?></td>
									<td><?php echo $this->Setting->get_class_section($it2['classsection']); ?></td>									
								</tr>
							
							<?php

								}
						}
						else
						{									
						?>
							<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Student Data Available');?></h4></div>
						<?php	
						}
					?>
					
					<?php }
						?>
					
				</tbody>
				</table>
		</div>
	</div>
</div>
<?php
}
?>
<?php $heading = $this->Setting->getfieldname('school_name');?>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">

    <!-- Modal content-->
    <div class="modal-content">
      
	  <div class="modal-header"> 
			<a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
			<h4 id="myLargeModalLabel" class="modal-title"> <?php echo $heading; ?> </h4>
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
			<a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
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
<div class="modal fade " id="myModalview" role="dialog">
	<div class="modal-dialog modal-md"  >
		<div class="modal-content">
			<div class="modal-header" >
				<span type="button" class="" data-dismiss="modal"><?php echo __("Student Details");?></span>
				<a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
			</div>
			<div class="modal-body" style="float: left;width: 100%;background-color: #FFFFFF;"></div>
		</div>
	</div>
</div>