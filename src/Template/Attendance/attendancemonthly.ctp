<link href="<?php echo $this->request->base;?>/css/bootstrap-datepicker.css" rel="stylesheet">
<script src="<?php echo $this->request->base;?>/js/bootstrap-datepicker.js"></script>
<script>
$( document ).ready(function(){
	
    $("#atten").change(function(){
		
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
				console.log(data);			  				
		   },
			error: function(e) {
				   alert("An error occurred: " + e.responseText);
				   console.log(e.responseText);	
			}
		});
    });
	
	$("#dep").change(function(){
		
		var sec_id = $(this).val();
     
		$('.userdata').html();
		
		$.ajax({
			
		   type: 'POST',
		   url: '<?php echo $this->Url->build(["controller" => "Attendance","action" => "viewstud"]);?>',		
		   data : {id : sec_id},
			 
		   success: function (data)
		   {            
				
				$('.userdata').html(data);
				console.log(data);
				console.log(data);			  				
		   },
			error: function(e) {
				   alert("An error occurred: " + e.responseText);
				   console.log(e.responseText);	
			}
		});
    });	
	
	   var today = new Date();
	   $('#date_of_attendance').datepicker
		({
			changeMonth: true,
			changeYear: true,
			autoclose:true,
            endDate: "today",
            maxDate: today,
			yearRange:'-75:+10',
			dateFormat: 'yy-mm-dd',	
		});

});

</script>

<?php

		use Cake\ORM\TableRegistry;

		$user_id=$this->request->session()->read('user_id');

			$class_user = TableRegistry::get('smgt_users');
			$query=$class_user->find()->where(['user_id'=>$user_id]);

			$get_role='';

			foreach($query as $role){
					$get_role=$role['role'];
			}

	?>
	
<div class="row">
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-users fa-lg')) . __('Attendance'),['controller' => 'Attendance', 'action' => 'attendance'],['escape' => false]);?>
		</li>
		<li style="display:<?php if($get_role == 'teacher'){echo 'none';}else{echo 'block';}?>">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-user fa-lg')) . __('Teacher Attendance'),['controller' => 'Attendance', 'action' => 'teacherattendance'],['escape' => false]);?>
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-users fa-lg')) . __('Subject Wise Attendance'),['controller' => 'Attendance', 'action' => 'subjectattendance'],['escape' => false]);?>
		</li>
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-bar-chart fa-lg')) . __('Monthly Attendance Report'),['controller' => 'Attendance', 'action' => 'attendancemonthly'],['escape' => false]);?>
		</li>
	</ul>	
</div>
<div class="row">			
	<div class="panel-body">
		<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'attendance']]);?>
		<div class="form-group">
			<div class="col-md-2 col-sm-3 col-xs-12">
						
				<?php echo $this->Form->label(__('Select Class'));?><span style="color:red;"><?php echo " *"; ?></span>	
				<?php 
													
					if(isset($_POST['attendance']))
					{
						echo @$this->form->select("class_id",$class_id,["default"=>$cls_id,"empty"=>__("Select Class"),"class"=>"form-control validate[required,maxSize[50]]","id"=>"atten"]);					
					}
					else
					{
						echo @$this->form->select("class_id",$class_id,["default"=>"","empty"=>__("Select Class"),"class"=>"form-control validate[required,maxSize[50]]","id"=>"atten"]);
					}
							
					?>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-12">
					
				<?php echo $this->Form->label(__('Select Section'));?><span style="color:red;"><?php echo " *"; ?></span>	
				
				
				<select class="form-control validate[required,maxSize[50]] ajaxdata" name="section" id="dep">

				<?php if(isset($sec_id)){?>

					<option value="<?php echo $sec_id; ?>"><?php echo $this->Setting->get_class_section($sec_id); ?></option>

						<?php } 
					else
						echo "<option value=''>"?> <?php echo __('Select Section'); ?> <?php "</option>";
					?>

				</select>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-12">
					
				<?php echo $this->Form->label(__('Select Student'));?><span style="color:red;"><?php echo " *"; ?></span>	
				
				
				<select class="form-control validate[required,maxSize[50]] userdata" name="student" id="">

				<?php if(isset($stud_id)){?>

					<option value="<?php echo $stud_id; ?>"><?php echo $this->Setting->get_user_id($stud_id); ?></option>

						<?php } 
					else
						echo "<option value=''>"?> <?php echo __('Select Student'); ?> <?php "</option>";
					?>

				</select>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-12">
				<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
					<?php echo $this->Form->label(__('Month'));?>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">	
					<?php 
					$month = isset($month)?$month:'';
					echo $this->Form->input('',array('value'=>$month,'id'=>'month_of_attendance','name'=>'attendence_month','class'=>'form-control validate[required] date-one'));?>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-12">
				<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
					<?php echo $this->Form->label(__('Year'));?>
				</div>
				<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">	
					<?php 
					$year = isset($year)?$year:'';
					echo $this->Form->input('',array('value'=>$year,'id'=>'year_of_attendance','name'=>'attendence_year','class'=>'form-control validate[required] date-two'));?>
				</div>
			</div>
			<div class="col-md-2 col-sm-3 col-xs-12 button-list-possition">
				<?php echo $this->Form->label('');?>
				<?php echo $this->Form->input(__('View Attendance'),array('type'=>'submit','name'=>'attendance','class'=>'btn btn-success'));?>
			</div>
		</div>
		</form>
		<div class="col-md-12 col-sm-12 col-xs-12">
		<?php
		$stud_date = $this->Setting->getfieldname('date_format');
		
		if(isset($_REQUEST['attendance']))
		{
			if(isset($check_attendance))
			{
				?>
				<p style="margin-top:20px;margin-left: 15px;"><b><?php echo __('Student Name :'); ?> <?php echo $name;?></b></p>
				
					<table width=100% border=1 class="cnttable" style="margin-bottom: 30px;">
						<thead>
							<tr>
								<th><?php echo __('Present Days');?></th>
								<th><?php echo __('Absent Days');?></th>
								<th><?php echo __('Late Days');?></th>
								<th><?php echo __('Total Days');?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $cntpresent;?></td>
								<td><?php echo $cntabsent;?></td>
								<td><?php echo $cntlate;?></td>
								<td><?php echo $cnttotal;?></td>
							</tr>
						</tbody>
					</table>
					<table id="studentattendance" class="table col-md-12" style="margin-bottom: 0px;">
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
					foreach($check_attendance as $atted)
					{
						echo "<tr>
							<td>".date($stud_date, strtotime($atted['attendence_date']))."</td>
							<td>".date("D", strtotime($atted['attendence_date']))."</td>
							<td>".$atted['status']."</td>
							<td>".$atted['comment']."</td>
							</tr>";				
					}
					echo '</tbody>';
				echo '</table>';
				?>
				<form name="attend_report" method="post" class="form_horizontal" style="float:left;width:100%;">
					<?php
					$check_attendance1 = serialize($check_attendance);
					?>
					<input type="hidden" value="<?php echo $cntpresent;?>" name="cntpresent">
					<input type="hidden" value="<?php echo $cntabsent;?>" name="cntabsent">
					<input type="hidden" value="<?php echo $cntlate;?>" name="cntlate">
					<input type="hidden" value="<?php echo $cnttotal;?>" name="cnttotal">
					<input type="hidden" value='<?php echo $check_attendance1;?>' name="check_attendance">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<?php echo $this->Form->label('Attendance Report Send to Parent');?>
						<?php echo $this->Form->input(__('Click!'),array('type'=>'submit','name'=>'attendance_report','class'=>'btn btn-success'));?>
					</div>
				</form>
			<?php
			}
			else
			{
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
<script type="text/javascript">
	$('.date-one').datepicker({
		startView: "months", 
		minViewMode: "months",
		format: 'mm'
	});

	$('.date-two').datepicker({
		minViewMode: 2,
		format: 'yyyy'
	});
</script>
<style>
.formsize {
    width: 100%;
    margin: 0px auto;
    padding-top: 20px;
    float: left;
}
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