<script>
		jQuery(document).ready(function() {
			
			jQuery("#date_of_birth").datepicker({
				numberOfMonths: 1,
				dateFormat: 'yy-mm-dd',  
				maxDate : new Date(),
				onSelect: function() {
				var date = $('#date_of_birth').datepicker('getDate');  
				date.setDate(date.getDate());

				$("#date_of_birth1").datepicker("option","minDate", date);
				}
		   });
		   jQuery("#date_of_birth1").datepicker({     
				numberOfMonths: 1,
				dateFormat: 'yy-mm-dd',  
				maxDate : new Date(),
				onSelect: function() {
				}
			}); 
			
			$('#studentsubjectattendance').DataTable({responsive: true});
			
		});
</script>
<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('View Attendance'),['controller' => 'Comman', 'action' => 'studentattendance',$this->Setting->my_simple_crypt($id,'e')],['escape' => false]);?>
		</li>
		<li class="active">		
			<?php		
			echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('View Subject Wise Attendance'),['controller' => 'Comman', 'action' => 'studentsubjectattendance',$this->Setting->my_simple_crypt($id,'e')],['escape' => false]);?>
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
						echo $this->Form->input('',array('value'=>$s_date,'id'=>'date_of_birth','name'=>'sdate','class'=>'form-control validate[required]'));
						
					}
					else
					{
						echo $this->Form->input('',array('value'=>$current_date,'id'=>'date_of_birth','name'=>'sdate','class'=>'form-control validate[required]'));
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
						echo $this->Form->input('',array('value'=>$e_date,'id'=>'date_of_birth1','name'=>'edate','class'=>'form-control validate[required]'));
						
					}
					else
					{
						echo $this->Form->input('',array('value'=>$current_date,'id'=>'date_of_birth1','name'=>'edate','class'=>'form-control validate[required]'));
					}
					?>
				</div>
			</div>
						
			<div class="col-md-3 col-sm-6 col-xs-12">
						
				<?php echo $this->Form->label('Select Subject');?><span style="color:red;"><?php echo " *"; ?></span>
						
					<div class="result">
						<select class="form-control validate[required,maxSize[50]]" name="sub_id">
						<option value=""><?php echo __('Select Subject'); ?></option>
						<?php foreach($get_data as $id):
						if(isset($s_id))
							$selected = ($s_id == $id['subid'])?"selected":"";
						else
							$selected = "";
						?>
						<option value="<?php echo $id['subid'];?>" <?php echo $selected;?>>
							<?php echo $id['sub_name'];?>
						</option> 
						<?php endforeach;?>		
					</select>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12 button-possition">
				<?php echo $this->Form->label('');?>
				<?php echo $this->Form->input(__('Go'),array('type'=>'submit','name'=>'view_attendance','class'=>'btn btn-info'));?>
			</div>
			
		<?php $this->Form->end(); ?>
		<div class="clearfix"></div>
		<?php
		if(isset($_REQUEST['view_attendance']))
		{?>
			<p style="margin-top:20px;margin-left: 15px;"><b> <?php echo __('Student Name :'); ?> <?php echo $name;?></b> , <b> <?php echo __('Subject Name :'); ?> <?php echo $sub_name;?></b></p>
			<table id="studentsubjectattendance" class="table">
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
			$i=0;
			while($end_date >= $curremt_date)
			{			
				echo '<tr>';
				echo '<td>';
				echo $curremt_date;
				echo '</td>';
				
				echo '<td>';
				echo date("D", strtotime($curremt_date));
				echo '</td>';
				
				if($attendance_status[$i] != '')
				{
					echo '<td>';
					echo $attendance_status[$i];
					echo '</td>';
				}
				else 
				{
				
					echo '<td>';
					echo __('Absent');
					echo '</td>';
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
			}
			echo '</tbody>';
		echo '</table>';
		}	
			?>	
	</div>
</div>