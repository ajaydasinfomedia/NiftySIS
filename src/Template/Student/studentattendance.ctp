<script>
$(document).ready(function() {
	
	$('#studentattendance').DataTable({responsive: true});
	
	$("#date_of_birth20").datepicker({
		numberOfMonths: 1,
		dateFormat: 'yy-mm-dd',  
		maxDate : new Date(),
		onSelect: function() {
		var date = $('#date_of_birth20').datepicker('getDate');  
		date.setDate(date.getDate());

		$("#date_of_birth21").datepicker("option","minDate", date);
		}
   });
   $("#date_of_birth21").datepicker({     
		numberOfMonths: 1,
		dateFormat: 'yy-mm-dd', 
		maxDate : new Date(),	
		onSelect: function() {
		}
	}); 
}); 
</script>
<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li class="active">		
			<?php  
			if(isset($id))
			{
				echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('View Attendance'),['controller' => 'Student', 'action' => 'studentattendance',$this->Setting->my_simple_crypt($id,'e')],['escape' => false]);
			}
			else
			{
				echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('View Attendance'),['controller' => 'Student', 'action' => 'studentattendance'],['escape' => false]);
			} ?>
		</li>
		<li>
		<?php
		if(isset($id))
		{		
			echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('View Subject Wise Attendance'),['controller' => 'Student', 'action' => 'studentsubjectattendance',$this->Setting->my_simple_crypt($id,'e')],['escape' => false]);
		}
		else
		{
			echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('View Subject Wise Attendance'),['controller' => 'Student', 'action' => 'studentsubjectattendance'],['escape' => false]);
	
		}	?>	
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-bar-chart fa-lg')) . __('Monthly Attendance Report'),['controller' => 'Attendance', 'action' => 'attendancemonthly'],['escape' => false]);?>
		</li>
	</ul>	
</div>

<div class="row">			
	<div class="panel-body">
		<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'studentattendance']]);?>
		<div class="form-group">
			<div class="col-md-3 col-sm-6 col-xs-12 attenddatepickericon">
				<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
					<?php echo $this->Form->label(__('Start Date'));?>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
					<?php
					if(isset($_REQUEST['sdate']))
					{
						$s_date=$_REQUEST['sdate'];
						echo $this->Form->input('',array('value'=>$s_date,'id'=>'date_of_birth20','name'=>'sdate','class'=>'form-control validate[required]'));
						
					}
					else
					{
						echo $this->Form->input('',array('value'=>$current_date,'id'=>'date_of_birth20','name'=>'sdate','class'=>'form-control validate[required]'));
					}
					?>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12 attenddatepickericon">
				<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
					<?php echo $this->Form->label(__('End Date'));?>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
					<?php
					if(isset($_REQUEST['edate']))
					{
						$e_date=$_REQUEST['edate'];
						echo $this->Form->input('',array('value'=>$e_date,'id'=>'date_of_birth21','name'=>'edate','class'=>'form-control validate[required]'));
						
					}
					else
					{
						echo $this->Form->input('',array('value'=>$current_date,'id'=>'date_of_birth21','name'=>'edate','class'=>'form-control validate[required]'));
					}
					?>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12 button-possition">
				<?php echo $this->Form->label('');?>
				<?php echo $this->Form->input(__('Go'),array('type'=>'submit','name'=>'view_attendance','class'=>'btn btn-info'));?>
			</div>
			
		<?php $this->Form->end(); ?>
		<div class="clearfix"></div>
		<?php
		$stud_date = $this->Setting->getfieldname('date_format');
		if(isset($_REQUEST['view_attendance']))
		{
			if(isset($attendance))
			{
				$a=0;
				$p=0;
				$t=0;
				$l=0;
				?>
				<p style="margin-top:20px;margin-left: 15px;"><b><?php echo __('Student Name :'); ?> <?php echo $name;?></b></p>
				
				<table id="studentattendance" class="table col-md-12">
				<thead>
					<tr>
						<th><?php echo __('Date');?></th>
						<th><?php echo __('Day');?></th>
						<th><?php echo __('Attendance');?></th>
						<th><?php echo __('Comment');?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><?php echo __('Date');?></th>
						<th><?php echo __('Day');?></th>
						<th><?php echo __('Attendance');?></th>
						<th><?php echo __('Comment');?></th>
					</tr>
				</tfoot>
				<tbody>
				<?php
				foreach($attendance as $atted)
				{
					echo "<tr>
						<td>".date($stud_date, strtotime($atted['attendence_date']))."</td>
						<td>".date("D", strtotime($atted['attendence_date']))."</td>
						<td>";
						if($atted['status'] == 'Present')
						{
							$p = $p+1;
						}
						elseif($atted['status'] == 'Absent')
						{
							$a = $a+1;
						}
						else{
							$l = $l+1;
						}
						echo $atted['status']."</td>
						<td>".$atted['comment']."</td>
						</tr>";
					$t = $t+1;
				}
				/*
				$i=0;
				
				while($end_date >= $curremt_date)
				{			
					echo '<tr>';
					echo '<td>';
					echo date($stud_date, strtotime($curremt_date));
					echo '</td>';
					
					echo '<td>';
					echo date("D", strtotime($curremt_date));
					echo '</td>';
					
					if($attendance_status[$i] != '')
					{
						echo '<td>';
						echo $attendance_status[$i];
						echo '</td>';
						$p = $p+1;					
					}
					else 
					{
					
						echo '<td>';
						echo __('Absent');
						echo '</td>';
						$a = $a+1;
					}
					
					if($attendance_comment[$i] != '')
					{
						echo '<td>';
						echo $attendance_comment[$i];
						echo '</td>';
					}
					else 
					{
						echo '<td>';
						echo __('-');
						echo '</td>';
					}				
			echo '</tr>';
			$curremt_date = strtotime("+1 day", strtotime($curremt_date));
			$curremt_date = date("Y-m-d", $curremt_date);
			$i=$i+1;
			$t = $t+1;
				}*/
				echo '</tbody>';
			echo '</table>';
		
			?>
			<table width=100% border=1 class="cnttable" style="margin-top: 30px;">
				<thead>
					<tr>
						<th><?php echo __('Present Days');?></th>
						<th><?php echo __('Absent Days');?></th>
						<th><?php echo __('Late Days');?></th>
						<th><?php echo __('Total Days');?></th>
						<?php /*<th><?php echo __('Total Present on Percentage (%)');?></th>*/?>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $p;?></td>
						<td><?php echo $a;?></td>
						<td><?php echo $l;?></td>
						<td>
							<?php
							echo $t;
							/*
							$per=0;
							
							if($a > 0)
								$per = $a*100/$t;
							
							echo round($per);
							*/
							?>
						</td>
					</tr>
				</tbody>
			</table>		
			<?php
			}
			else{
				?>
				<div class="panel-body">
					<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Attendance Available');?></h4>
					</div>
				</div>
			<?php
			}
		}		
		?>
	</div>
</div>
</div>
<style>
.cnttable th
{
	background-color: #f5f5f5;
}
.cnttable th,
.cnttable td{
	text-align: center;
	padding: 5px 0px;
}
</style>