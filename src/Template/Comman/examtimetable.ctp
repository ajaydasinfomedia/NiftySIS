<script>
$(document).ready(function(){		
	$("#formID2").validationEngine();
	$('#examtimetable').DataTable({
		responsive: true,
		bPaginate: false,
		bFilter: false, 
		bInfo: false,
	});
	
	$( "#save_exam_time" ).on("click",function(e) {
		var subject_data = $("#subject_data").val();
		var suj = JSON.parse(subject_data);
		var productIds = [];
		jQuery.each( suj, function( i, val ) {
			
			var exdt = $("#exam_date_"+val.subid).val();
			
			var strh = $("#start_hour_"+val.subid).val();
			var strm = $("#start_min_"+val.subid).val();
			var strap = $("#start_ampm_"+val.subid).val();
			
			var endh = $("#end_hour_"+val.subid).val();
			var endm = $("#end_min_"+val.subid).val();
			var endap = $("#end_ampm_"+val.subid).val();
			
			var exsdtfull = exdt+strh+strm+strap;
			var exedtfull = exdt+endh+endm+endap;

			if ($.inArray(exsdtfull, productIds) == -1) {
				productIds.push(exsdtfull);
			}
			else{
				alert("Fail! More than one subject exam date & time same");
				e.preventDefault(e);
			}
			if ($.inArray(exedtfull, productIds) == -1) {
				productIds.push(exedtfull);
			}
			else{
				alert("Fail! More than one subject exam date & time same");
				e.preventDefault(e);
			}
			
			var strfull = strh+":"+strm+" "+strap;
			var endfull = endh+":"+endm+" "+endap;
			
			function minFromMidnight(tm){
			 var ampm= tm.substr(-2)
			 var clk = tm.substr(0, 5);
			 var m  = parseInt(clk.match(/\d+$/)[0], 10);
			 var h  = parseInt(clk.match(/^\d+/)[0], 10);
			 h += (ampm.match(/pm/i))? 12: 0;
			 return h*60+m;
			}
			st = minFromMidnight(strfull);
			et = minFromMidnight(endfull);
			
			if(st>=et)
			{
				alert("Subject "+val.sub_name+" "+"End time must be greater than start time");
				e.preventDefault(e);
			}
		});
	});
});
</script>
<div class="row">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">		
		<li class="">			
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Exam List'),['controller'=>'Comman','action' => 'examlist'],['escape' => false]);?>
		</li>
		<li class="">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg'))  . __(' Add Exam'),['controller'=>'Comman','action' => 'addexam'],['escape' => false]);?>  
		</li>
		<li class="active">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg'))  . __(' Exam Time Table'),['controller'=>'Comman','action' => 'examtimetable'],['escape' => false]);?>  
		</li> 	
	</ul>
</div>
			
<div class="exam panel-body">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<form id="formID2" name="f1" class="form_horizontal" method="post" enctype="multipart/form-data">
		
		<div class="form-group">
			<div class="col-md-1 col-sm-1 col-xs-12"><div class="row"><?php echo $this->Form->label(__('Select Exam'));?> <span class="require-field">*</span></div></div>
			<div class="col-md-3 col-sm-6 col-xs-12">
				<select class="form-control validate[required]" id="exam_id" name="exam_id">
					<option value=""> <?php echo __('Select Exam '); ?> </option>
					<?php
					foreach($exam_data as $fetch_data)
					{
						$class_id = $this->Setting->get_exam_data($fetch_data['exam_id'],'class_id');
						$section_id = $this->Setting->get_exam_data($fetch_data['exam_id'],'section_id');
						$selected = ($fetch_data['exam_id'] == $exam_id)?'selected':'';
					?>
						<option value="<?php echo $fetch_data['exam_id'];?>" <?php echo $selected;?>><?php echo $fetch_data['exam_name']." (".$this->Setting->get_class_id($class_id).") (".$this->Setting->section_name($section_id).")";?></option>
					<?php
					}
					?>
				</select>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12">
				<div class="row">
					<?php echo $this->Form->input(__('Manage Exam Time'),array('type'=>'submit','name'=>'manage_exam','class'=>'btn btn-info'));?>
				</div>
			</div>
		</div>
		</form>
	</div>
</div>	

<?php 
if(isset($_POST['manage_exam']))
{
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
					<table class="table info" style="border: 1px solid #000000;text-align: center;margin-bottom: 0px;border-collapse: separate;">
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
		<input type='hidden' name='subject_data' id="subject_data" value='<?php echo json_encode($subject_data);?>'>
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
							$("#exam_date_<?php echo $subject_data['subid'];?>").datepicker({ 
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
								<td>
									<div class="datepickericon">
										<input type="text" id="exam_date_<?php echo $subject_data['subid'];?>" value="<?php echo date($stud_date,strtotime($single_exam_data[0]['exam_date']));?>" name="date_<?php echo $subject_data['subid'];?>" class="form-control validate[required]" placeholder="Exam Date">
									</div>
								</td>
								<td>
								<?php $a=$single_exam_data[0]['start_time'];							
								$a1=explode(':',$a);
								?>
									<div class="col-md-4 col-sm-4 col-xs-12"><div class="row">
										<?php echo $this->Form->input('', array('value'=>$a1[0],'id'=>'start_hour_'.$subject_data['subid'],'name'=>'start_hour_'.$subject_data['subid'],'class'=>'form-control validate[required]','options' => array('01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12'),'empty' => __('Select')));?>
									</div>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-12 minute">
										<?php echo $this->Form->input('', array('value'=>$a1[1],'id'=>'start_min_'.$subject_data['subid'],'name'=>'start_min_'.$subject_data['subid'],'class'=>'form-control validate[required]','options' => array('00'=>'00','01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56','57'=>'57','58'=>'58','59'=>'59')));?>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-12"><div class="row">
										<?php echo $this->Form->input('', array('value'=>$a1[2],'id'=>'start_ampm_'.$subject_data['subid'],'name'=>'start_ampm_'.$subject_data['subid'],'class'=>'form-control validate[required]','options' => array('am'=>__('am'),'pm'=>__('pm'))));?>
									</div>
									</div>
								</td>
								<td>
								<?php $b=$single_exam_data[0]['end_time'];							
								$b1=explode(':',$b);
								?>
									<div class="col-md-4 col-sm-4 col-xs-12"><div class="row">
										<?php echo $this->Form->input('', array('value'=>$b1[0],'id'=>'end_hour_'.$subject_data['subid'],'name'=>'end_hour_'.$subject_data['subid'],'class'=>'form-control validate[required]','options' => array('01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12'),'empty' => __('Select')));?>
									</div>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-12 minute">
										<?php echo $this->Form->input('', array('value'=>$b1[1],'id'=>'end_min_'.$subject_data['subid'],'name'=>'end_min_'.$subject_data['subid'],'class'=>'form-control validate[required]','options' => array('00'=>'00','01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56','57'=>'57','58'=>'58','59'=>'59')));?>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-12"><div class="row">
										<?php echo $this->Form->input('', array('value'=>$b1[2],'id'=>'end_ampm_'.$subject_data['subid'],'name'=>'end_ampm_'.$subject_data['subid'],'class'=>'form-control validate[required]','options' => array('am'=>__('am'),'pm'=>__('pm'))));?>
									</div>
									</div>
								</td>
							</tr>							
						<?php
						}
						else
						{
					?>
					<tr>
						<td><?php echo $subject_data['sub_code'];?></td>
						<td><?php echo $subject_data['sub_name'];?></td>
						<td>
							<div class="datepickericon">
								<input type="text" id="exam_date_<?php echo $i;?>" name="date_<?php echo $subject_data['subid'];?>" class="form-control validate[required]" placeholder="Exam Date">
							</div>
						</td>
						<td>
							<div class="col-md-4 col-sm-4 col-xs-12"><div class="row">
								<?php echo $this->Form->input('', array('id'=>'start_hour_'.$subject_data['subid'],'name'=>'start_hour_'.$subject_data['subid'],'class'=>'form-control validate[required]','options' => array('01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12'),'empty' => __('Select')));?>
							</div>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12 minute">
								<?php echo $this->Form->input('', array('id'=>'start_min_'.$subject_data['subid'],'name'=>'start_min_'.$subject_data['subid'],'class'=>'form-control validate[required]','options' => array('00'=>'00','01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56','57'=>'57','58'=>'58','59'=>'59')));?>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12"><div class="row">
								<?php echo $this->Form->input('', array('id'=>'start_ampm_'.$subject_data['subid'],'name'=>'start_ampm_'.$subject_data['subid'],'class'=>'form-control validate[required]','options' => array('am'=>__('am'),'pm'=>__('pm'))));?>
							</div>
							</div>
						</td>
						<td>
							<div class="col-md-4 col-sm-4 col-xs-12"><div class="row">
								<?php echo $this->Form->input('', array('id'=>'end_hour_'.$subject_data['subid'],'name'=>'end_hour_'.$subject_data['subid'],'class'=>'form-control validate[required]','options' => array('01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12'),'empty' => __('Select')));?>
							</div>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12 minute">
								<?php echo $this->Form->input('', array('id'=>'end_min_'.$subject_data['subid'],'name'=>'end_min_'.$subject_data['subid'],'class'=>'form-control validate[required]','options' => array('00'=>'00','01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56','57'=>'57','58'=>'58','59'=>'59')));?>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12"><div class="row">
								<?php echo $this->Form->input('', array('id'=>'end_ampm_'.$subject_data['subid'],'name'=>'end_ampm_'.$subject_data['subid'],'class'=>'form-control validate[required]','options' => array('am'=>__('am'),'pm'=>__('pm'))));?>
							</div>
							</div>
						</td>
					</tr>
					<?php
						}
						$i++;
					}
					?>
				</tbody>
				</table>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<?php echo $this->Form->input(__('Save Time Table'),array('type'=>'submit','name'=>'save_exam_time','id'=>'save_exam_time','class'=>'btn btn-success'));?>
				</div>
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
<?php
}
?>
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