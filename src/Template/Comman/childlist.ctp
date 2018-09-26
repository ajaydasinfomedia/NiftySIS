<script>
		$(document).ready(function() {
			$('#childlist').DataTable({responsive: true});
		});
</script>

<script>

$( document ).ready(function(){
    $('body').on('click', '.save', function() {
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
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Child List'),['controller' => 'Comman', 'action' => 'childlist'],['escape' => false]);?>
		</li>		
	</ul>	
</div>
<?php
if($role == 'parent' && isset($child_id))
{
?>	
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="childlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th><?php echo __('Photo');?></th>
						<th><?php echo __('Child Name');?></th>
						<th><?php echo __('Student ID');?></th>
						<th><?php echo __('Class');?></th>
						<th><?php echo __('Roll No.');?></th>
						<th><?php echo __('Child Email');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><?php echo __('Photo');?></th>
						<th><?php echo __('Child Name');?></th>
						<th><?php echo __('Student ID');?></th>
						<th><?php echo __('Class');?></th>
						<th><?php echo __('Roll No.');?></th>
						<th><?php echo __('Child Email');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</tfoot>
				<tbody>
					<tr>
					<?php	
						if(isset($child_id))	
						{	
							$i=0;
							foreach($child_id as $it2)
							{
							
								?>
								<td><?php echo $this->Html->image($photo[$i],array('height'=>'50px','width'=>'50px','class'=>'profileimg'));?></td>							
								<td><?php echo $name[$i]; ?></td>
								<td><?php echo $this->Setting->get_studentID($it2); ?></td>
								<td><?php echo $clsname[$i]; ?></td>
								<td><?php echo $roll_no[$i];?></td>
								<td><?php echo $email[$i];?></td>
								<?php
								$get_user_exam_hall_receipt = $this->Setting->get_user_exam_hall_receipt($it2);
								echo "<td>".$this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-bar-chart')). __('View Result'),['action'=>'result','data-toggle'=>'modal','data-target'=>'#myModal','print'=>$it2,'class'=>'btn btn-default save'],['escape' => false])." ".
								$this->Form->button($this->Html->tag('i',"&nbsp;", array('class' => 'fa fa-user')). __('View Parent'),['action'=>'studentparent','data-toggle'=>'modal','data-target'=>'#myModal1','print'=>$it2,'class'=>'btn btn-default save'],['escape' => false])." ".
								$this->Html->link($this->Html->tag('i'," ", array('class' => 'fa fa-eye')).__(' View Attendance'),array('controller'=>'Comman','action' => 'studentattendance', $this->Setting->my_simple_crypt($it2,'e')),array('class'=>'btn btnview btn-default','escape' => false));
								if($get_user_exam_hall_receipt == 1)
								{
									echo $this->Html->link(__('Exam Receipt'),array('action' => 'studentexamlist', $this->Setting->my_simple_crypt($it2,'e')),array('class'=>' btn btnview btn-success'));
								}
								echo "</td>";
								?>
							</tr>
							<?php
							$i++;
							}
						}
						else
						{
							$i=0;							
							if(isset($it))	
							{
								foreach($it as $it2)
								{
								
									$name=$it2['first_name']." ".$it2['last_name'];
									echo "<td>" .$this->Html->image($it2['image'],array('height'=>'50px','width'=>'50px','class'=>'profileimg')) . "</td>";
									echo "<td>" .__($name). "</td>";
									echo "<td>" .__($class_name[$i]) . "</td>";
									echo "<td>" .__($it2['roll_no']) . "</td>";
									echo "<td>" .__($it2['email']) . "</td>";
									echo "<td>"." "."</td>";
									echo "</tr>";
								$i++;
								}
							}
						}
					?>					
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php
}
else
{
?>
	<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No availability of Child Data');?></h4></div>
<?php
}
?>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      
	  <div class="modal-header"> 
			<a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
			<h4 id="myLargeModalLabel" class="modal-title">
			<?php 
			$school_name = $this->Setting->getfieldname('school_name');
			echo $school_name; 
			?>
			</h4>
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

