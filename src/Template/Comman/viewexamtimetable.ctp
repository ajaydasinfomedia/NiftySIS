<?php
use Cake\Routing\Router;

$user_id=$this->request->session()->read('user_id');
$get_role=$this->Setting->get_user_role($user_id);			
?>
<script>
$(document).ready(function(){		
	$("#formID2").validationEngine();
	$('#examtableinfo').DataTable({
		responsive: true,
		bPaginate: false,
		bFilter: false, 
		bInfo: false,
	});
	$('#examtimetable').DataTable({
		responsive: true,
		bPaginate: false,
		bFilter: false, 
		bInfo: false,
	});
});
</script>
<div class="row">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">		
		<li class="">			
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Exam List'),['controller'=>'Comman','action' => 'examlist'],['escape' => false]);?>
		</li>
		<?php
		if($get_role == 'teacher'){
		?>
		<li class="">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg'))  . __(' Add Exam'),['controller'=>'Comman','action' => 'addexam'],['escape' => false]);?>  
		</li>
		<?php
		}
		?>
		<li class="active">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg'))  . __(' Exam Time Table'),['controller'=>'Comman','action' => 'viewexamtimetable', $this->Setting->my_simple_crypt($exam_id,'e')],['escape' => false]);?>  
		</li>  	
	</ul>
</div>

<?php 
	$stud_date = $this->Setting->getfieldname('date_format');
?>
<input type="hidden" id="start" value="<?php echo date($stud_date,strtotime($exam_date));?>">
<input type="hidden" id="end" value="<?php echo date($stud_date,strtotime($exam_end_date));?>">
<div class="panel-body">
	<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
	<div class="col-md-12 col-sm-12 col-xs-12">				
		<div class="form-group">
			<div class="col-md-12">
				<div class="row">
					<table id="examtableinfo" class="table info" cellspacing="0" width="100%" style="border: 1px solid #000000;text-align: center;margin-bottom: 0px;">
						<thead>
							<tr>
								<th style="border-top: medium none;border-right: 1px solid #000000;"><?php echo __('Exam');?></th>
								<th style="border-right: 1px solid #000000;"><?php echo __('Class');?></th>							
								<th style="border-right: 1px solid #000000;"><?php echo __('Section');?></th>							
								<th style="border-right: 1px solid #000000;"><?php echo __('Term');?></th>							
								<th style="border-right: 1px solid #000000;"><?php echo __('Start Date');?></th>							
								<th style=""><?php echo __('End Date');?></th>							
							</tr>
						</thead>
						<tfoot></tfoot>
						<tbody>							
							<tr>
								<td style="border-right: 1px solid #000000;"><?php echo $exam_name;?></td>							
								<td style="border-right: 1px solid #000000;"><?php echo $this->Setting->get_class_id($class_id);?></td>
								<td style="border-right: 1px solid #000000;"><?php echo $this->Setting->get_class_section($section_id);?></td>
								<td style="border-right: 1px solid #000000;"><?php echo $this->Setting->term_name($term_id);?></td>
								<td style="border-right: 1px solid #000000;"><?php echo date($stud_date,strtotime($exam_date));?></td>
								<td style=""><?php echo date($stud_date,strtotime($exam_end_date));?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>			
		</div>
		<div class="form-group">
		<?php
		if(isset($subject_data))
		{
		?>
		<input type="hidden" name="exam_id" value="<?php echo $exam_id;?>">
		<input type="hidden" name="class_id" value="<?php echo $class_id;?>">
		<input type="hidden" name="section_id" value="<?php echo $section_id;?>">
		<div class="table-responsive">
			<div id="example_wrapper" class="dataTables_wrapper">
				<table id="examtimetable" class="table" cellspacing="0" width="100%">	
				<thead>
					<tr>
						<th><?php echo __('Subject Code');?></th>
						<th><?php echo __('Subject');?></th>
						<th><?php echo __('Exam Date');?></th>
						<th><?php echo __('Exam Start Time');?></th>
						<th><?php echo __('Exam End Time');?></th>
					</tr>
				</thead>
				<tfoot></tfoot>
				<tbody>					
					<?php 
					$i = 1;
					foreach($subject_data as $subject_data)
					{
						?>
						<script>
						$(document).ready(function(){
							var start = $( "#start" ).val();
							var end = $( "#end" ).val();
							$("#exam_date_<?php echo $i;?>").datepicker({ 
								minDate: start,
								maxDate: end,
								dateFormat: 'yy-mm-dd', 
							});
						});
						</script>
						<?php
						$exam_data=$this->Setting->check_exam_id($exam_id,$subject_data['subid']);
						if($exam_data)
						{ 
							$single_exam_data=$this->Setting->get_exam_time_table_data($exam_data);
						?>
							<tr>
								<td><?php echo $subject_data['sub_code'];?></td>
								<td><?php echo $subject_data['sub_name'];?></td>
								<td><?php echo date($stud_date,strtotime($single_exam_data[0]['exam_date']));?></td>
								<td><?php echo substr_replace($single_exam_data[0]['start_time'], ' ', -3, -2);?></td>
								<td><?php echo substr_replace($single_exam_data[0]['end_time'], ' ', -3, -2);?></td>
							</tr>							
						<?php
						}
						$i++;
					}
					?>
				</tbody>
			</table>
			</div>
		</div>	
  <?php } 
		else{
		?>
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Subject Data Available');?></h4></div>
<?php		
	}
  ?>
		</div>
	</div>
<?php $this->Form->end(); ?>
</div>
<style>
.exam.panel-body{margin-top: 20px;}
.submit{
	margin-top:0px;
}
.datepickericon{position: relative;}
.datepickericon:before{
	left: 8px;
}
.input.select select{
	padding: 2px!important;
}
.table.info td, 
.table.info>tbody>tr>td,
.table.info>thead>tr>th,
.table.info > tfoot > tr > th{
	padding: 8px;
}
.table td, 
.table>tbody>tr>td,
.table>thead>tr>th,
.table > tfoot > tr > th{
	padding: 12px;
}
.table.info>thead>tr>th{text-align: center;}
.table>thead>tr>th{	
	background-color: #e5e5e5;
	border-bottom: 1px solid #000000;
}
</style>