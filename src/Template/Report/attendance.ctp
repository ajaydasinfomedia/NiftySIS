<?php
		use Cake\ORM\TableRegistry;

		$user_id=$this->request->session()->read('user_id');

		$class_user = TableRegistry::get('smgt_users');
		$query=$class_user->find()->where(['user_id'=>$user_id]);

		$get_role='';

		foreach($query as $role)
		{
			$get_role=$role['role'];
		}
	?>

<div class="row">

	<ul role="tablist" class="nav nav-tabs panel_tabs">

                       <li>

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-bar-chart fa-lg')) . __('Student Failed Report'),array('controller'=>'report','action' => 'failed'),array('escape' => false));
						?>


					  </li>

					  <li class="active">
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-bar-chart fa-lg')) . __('Attendance Report'),array('controller'=>'report','action' => 'attendance'),array('escape' => false));
						?>
					  </li>

					    <li>
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-bar-chart fa-lg')) . __('Teacher Performance Report'),array('controller'=>'report','action' => 'teacher'),array('escape' => false));
						?>
					  </li>

					    <li style="display:<?php if($get_role == 'teacher'){echo 'none';}else{echo 'block';}?>">
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-bar-chart fa-lg')) . __('Fee Payment Report'),array('controller'=>'report','action' => 'feepayment'),array('escape' => false));
						?>
					  </li>
						
					<li>
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-bar-chart fa-lg')) . __('Result Report'),array('controller'=>'report','action' => 'result'),array('escape' => false));
						?>
					  </li>

				</ul>
</div>
<script>
	$(function(){

		$('#start_date').datepicker({
			 changeMonth: true,
      		 changeYear: true,
      		 dateFormat:"yy-mm-dd",
		});
    		$('#end_date').datepicker({
			 changeMonth: true,
      		 changeYear: true,
      		  dateFormat:"yy-mm-dd",
		});
		
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

<div class="row">
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'attendacne']]);?>


	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="form-group attenddatepickericon">
			<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
				<label for="exam_id"><?php echo __('Start Date'); ?><span class="require-field">*</span></label>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12 input text" style="padding: 0px;">
				<input type="text" class="form-control validate[required]" id="date_of_birth20" name="start_date" placeholder="Enter Start Date" value="<?php if(isset($_REQUEST['view_chart'])){echo $_REQUEST['start_date'];}?>">
			</div>
		</div>
	</div>

	<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="form-group attenddatepickericon">
		<div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0px;">
			<label for="exam_id"><?php echo __('End Date'); ?><span class="require-field">*</span></label>
        </div>
		<div class="col-md-12 col-sm-12 col-xs-12 input text" style="padding: 0px;">            	 
			<input type="text" class="form-control validate[required]" placeholder="Enter End Date" id="date_of_birth21" name="end_date" value="<?php if(isset($_REQUEST['view_chart'])){echo $_REQUEST['end_date'];}?>">
    	</div>
    	</div>

		</div>
		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="form-group">
				<label for="exam_id"><?php echo __('Class Name'); ?></label>
					<select class="form-control" name="class_name" id="class_id">
						<option value=" "><?php echo __('Select Class'); ?></option>
							<?php foreach($class_data as $class_info):
							$selected_vl = ($class_info['class_id'] == $class_id)?"selected":"";
							?>
							<option value="<?php echo $class_info['class_id'];?>" <?php echo $selected_vl;?>><?php echo $class_info['class_name']; ?></option>
							<?php endforeach;?>
					</select>
			</div>

		</div>

		<div class="col-md-3 col-sm-6 col-xs-12">
			  <div class="form-group button-list-possition">
						<?php echo $this->Form->label('');?>
						<?php echo $this->Form->input(__('GO'),array('type'=>'submit','class'=>'btn btn-info','name'=>'view_chart','style'=>'')); ?>
				</div>
		</div>


	<?php echo $this->Form->input('GO',array('type'=>'submit','class'=>'btn btn-info','name'=>'view_chart','style'=>'visibility:hidden')); ?>



    						<?php
    						if(isset($report_attendence)){
    						$chart_array=array();
    						$chart_array[] = array(__('Class'),__('Present'),__('Absent'));

    						foreach ($report_attendence as $result) {

    							foreach ($class_data as $class_info) {
    								if( $result['class_id'] == $class_info['class_id']){
    									$class_name=$class_info['class_name'];
    								}
    							}

    						$class_id =$class_name;
							$chart_array[] = array("$class_id",(int)$result['Present'],(int)$result['Absent']);
    						}



    		$options = Array(
			'title' => __('Attendance Report','school-mgt'),
			'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
			'legend' =>Array('position' => 'right',
					'textStyle'=> Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),

			'hAxis' => Array(
					'title' =>  __('Class','school-mgt'),
					'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
					'textStyle' => Array('color' => '#222','fontSize' => 10),
					'maxAlternation' => 2

			),
			'vAxis' => Array(
					'title' =>  __('No of Student','school-mgt'),
					'minValue' => 0,
					'maxValue' => 5,
					'format' => '#',
					'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
					'textStyle' => Array('color' => '#222','fontSize' => 12)
			),
			'colors' => array('#22BAA0','#f25656')
	);


  include_once WWW_ROOT.'chart'.DS.'GoogleCharts.class.php';
  $GoogleCharts=new GoogleCharts;


?>
<div class="col-md-12 col-sm-12 col-xs-12">
  <?php

    	  $chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );

    	  if(count($chart_array) > 1){
    	  ?>

    	  	 <div id="chart_div" style="width: 100%; height: 500px;"></div>
    	<?php
    }else{
    	?>
    		<div class="alert alert-info"><h2 align="center"><?php echo __('Result Not Found !'); ?> </h2></div>
    	<?php
    }
    ?>


  <!-- Javascript -->
  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
  <script>
			<?php echo $chart;?>
		</script>
</div>



			<?php
}
			$this->Form->end(); ?>

		</div>
</div>