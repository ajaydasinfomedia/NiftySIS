<?php
$school_name = $this->Setting->getfieldname('school_name');
$school_address = $this->Setting->getfieldname('school_address');
$stud_date = $this->Setting->getfieldname('date_format');
?>
<div class="panel-body">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="row">
			<div class="Receipt_Header">
				<div class="Receipt_header">
					<h3>										
						<span class="logo">
							<a href="<?php echo $this->request->base;?>" style="float: left;width: auto;cursor: pointer;">
							<?php 
							if($logo != "")
								echo $this->Html->image($logo, ['style'=>'']);?>
							</a>
						</span>											
						<div class="school_subname">
							<font><?php echo __("$school_name");?> </font>
						</div>
					</h3>
					<h2>
						<?php echo __('Exam Hall Ticket');?>
					</h2>
				</div>
			</div>
			<div class="Receipt_Detail">
				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="col-md-4 col-sm-4 col-xs-12">
						<b><?php echo __("Hall Name :");?></b>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12 no_padding border_bottom">
						<?php echo $this->Setting->get_hall_data($hall_id,'hall_name');?>
					</div>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="col-md-4 col-sm-4 col-xs-12">
						<b><?php echo __("Student ID :");?></b>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12 no_padding border_bottom">
						<?php echo $this->Setting->get_studentID($user_id);?>
					</div>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="col-md-4 col-sm-4 col-xs-12">
						<b><?php echo __("Roll No. :");?></b>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12 no_padding border_bottom">
						<?php echo $this->Setting->get_user_roll_no($user_id);?>
					</div>
				</div>
				<div class="col-md-8 col-sm-8 col-xs-12 padding">			
					<div class="col-md-12 col-sm-12 col-xs-12 padding-bottom">
						<div class="row">
							<div class="col-md-3 col-sm-4 col-xs-12">
								<b><?php echo __("Name of the Student :");?></b>
							</div>
							<div class="col-md-9 col-sm-9 col-xs-12 no_padding border_bottom" style="text-transform: uppercase;">
								<?php echo $this->Setting->get_user_full_name($user_id);?>
							</div>
						</div>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12 padding-bottom">
						<div class="row">
							<div class="col-md-3 col-sm-4 col-xs-12">
								<b><?php echo __("Student Class  :");?></b>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12 no_padding border_bottom">
								<?php echo $this->Setting->get_class_id($this->Setting->get_class_list_user_id($user_id));?>
							</div>
							<div class="col-md-3 col-sm-4 col-xs-12">
								<b><?php echo __("Student Section  :");?></b>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12 no_padding border_bottom">
								<?php echo $this->Setting->section_name($this->Setting->get_user_section_id($user_id));?>
							</div>
						</div>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12 padding-bottom">
						<div class="row">
							<div class="col-md-3 col-sm-4 col-xs-12">
								<b><?php echo __("Exam Start Date :");?></b>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12 no_padding border_bottom">
								<?php echo date($stud_date,strtotime($this->Setting->get_exam_data($exam_id,'exam_date')));?>
							</div>
							<div class="col-md-3 col-sm-4 col-xs-12">
								<b><?php echo __("Exam End Date :");?></b>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12 no_padding border_bottom">
								<?php echo date($stud_date,strtotime($this->Setting->get_exam_data($exam_id,'exam_end_date')));?>
							</div>
						</div>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12 padding-bottom">
						<div class="row">
							<div class="col-md-3 col-sm-4 col-xs-12">
								<b><?php echo __("Exam Center :");?></b>
							</div>
							<div class="col-md-9 col-sm-9 col-xs-12 no_padding border_bottom" style="text-transform: uppercase;">
								<?php echo __("$school_name");?>
							</div>
						</div>
					</div>	
					<div class="col-md-12 col-sm-12 col-xs-12 padding-bottom">
						<div class="row">
							<div class="col-md-3 col-sm-4 col-xs-12">
								<b><?php echo __("Exam Address :");?></b>
							</div>
							<div class="col-md-9 col-sm-9 col-xs-12 no_padding border_bottom" style="text-transform: uppercase;">
								<?php echo __("$school_address");?>
							</div>
						</div>
					</div>		
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12 padding-bottom">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12 no_padding user_profile">
							<?php echo $this->Html->image($this->Setting->get_user_image($user_id),array('max-height'=>'100%','max-width'=>'100%','class'=>'profileimg'));?>
						</div>
					</div>
				</div>
			</div>
			<?php
			if(isset($exam_time_table_data))
			{
			?>
			<div class="form-group">
				<div class="table-responsive" style="padding-top: 0px;">
					<div id="example_wrapper" class="dataTables_wrapper">
						<h4><?php echo __('Exam Time Table');?></h4>
						<table id="examtimetable" class="table table-striped table-bordered" cellspacing="0" width="100%">	
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
							foreach($exam_time_table_data as $time_table)
							{
								echo "<tr>";
								echo "<td>". $this->Setting->get_subject_data($time_table['subject_id'],'sub_code')."</td>";
								echo "<td>". $this->Setting->get_subject_data($time_table['subject_id'],'sub_name')."</td>";
								echo "<td>". date($stud_date,strtotime($time_table['exam_date']))."</td>";
								echo "<td>". $time_table['start_time']."</td>";
								echo "<td>". $time_table['end_time']."</td>";
								echo "</tr>";
							}
							?>
						</tbody>
						</table>
					</div>
				</div>
			</div>
			<?php
			}
			?>
		</div>
	</div>
</div>
<style>
.Receipt_Header{
	width:100%;
	float: left;
	border-bottom: 2px solid #970606;
}
.Receipt_header{
	width:60%;
	float: none;
	margin: 0px auto;
}
.Receipt_header h3{
	float: left;
    width: 100%;
    margin: 0px;
}
.Receipt_header h2{
	float: left;
    width: 100%;
    text-align: center;
    font-weight: 600;
	color: #204859;
}
.Receipt_header span.logo{
	float: left;
	width: auto;
}
.Receipt_header .school_subname{
	margin: 0px;
	border: medium none;
}
.Receipt_header .school_subname>font
{
	line-height: 45px;
	padding-left: 6px;
}
.Receipt_Detail
{
	float: left;
    width: 100%;
    padding: 20px 0px;
}
.Receipt_Detail .padding{
	padding: 15px;
}
.Receipt_Detail .no_padding{
	padding: 0px;
}
.Receipt_Detail .no_padding{
	padding: 0px;
}
.Receipt_Detail .padding-bottom{
	padding-bottom: 15px;
}
.Receipt_Detail .border_bottom
{
	border-bottom: 2px solid #204859;
	color: #204859;
    font-weight: 600;
}
.Receipt_Detail b
{
	color: #970606;
}
.dataTables_wrapper h4
{
	color: #204859;
}
.Receipt_Detail .user_profile{
	text-align: center;
    margin-top: 25px;
    border: 2px solid #204859;
    padding: 10px;
    max-width: 125px;
    min-height: 140px;
    float: right;
    margin-right: 30px;
    border-radius: 2px;
}
</style>