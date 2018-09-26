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
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-bar-chart fa-lg')) . __('Student Failed Report'),array('controller'=>'report','action' => 'failed'),array('escape' => false));?>
		</li>
		<li>
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-bar-chart fa-lg')) . __('Attendance Report'),array('controller'=>'report','action' => 'attendance'),array('escape' => false));?>
		</li>
		<li class="active">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-bar-chart fa-lg')) . __('Teacher Performance Report'),array('controller'=>'report','action' => 'teacher'),array('escape' => false));?>
		</li>
		<li style="display:<?php if($get_role == 'teacher'){echo 'none';}else{echo 'block';}?>">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-bar-chart fa-lg')) . __('Fee Payment Report'),array('controller'=>'report','action' => 'feepayment'),array('escape' => false));?>
		</li>
		<li>
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-bar-chart fa-lg')) . __('Result Report'),array('controller'=>'report','action' => 'result'),array('escape' => false));?>
		</li>
	</ul>
</div>

<div class="row">
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addexam']]);?>
<?php

$chart_array=array();

$chart_array[] = array(__('Teacher'),__('Pass'),__('Fail'));
						
						$result1 = 0;
						$result2 = 0;
						
						foreach($report_teacher as $result):
													
							$teacher_name = $this->Setting->get_user_id($result['teacher_id']);
							$teacher_pass = $this->Setting->paas_teacher_performance($result['teacher_id']);
							$teacher_fail = $this->Setting->fail_teacher_performance($result['teacher_id']);
												
							$teacher_name =$teacher_name;
							$chart_array[] = array("$teacher_name",(int)$teacher_pass,(int)$teacher_fail);
						
						endforeach;
						
						$options = Array(
				'title' => __('Teacher Performance Report'),
				'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
				'legend' =>Array('position' => 'right',
						'textStyle'=> Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans')),

				'hAxis' => Array(
						'title' =>  __('Teacher Name'),
						'titleTextStyle' => Array('color' => '#222','fontSize' => 14,'bold'=>true,'italic'=>false,'fontName' =>'open sans'),
						'textStyle' => Array('color' => '#222','fontSize' => 10),
						'maxAlternation' => 2
				),
				'vAxis' => Array(
						'title' =>  __('No of Student'),
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

			if(isset($report_teacher)){
					$chart = $GoogleCharts->load( 'column' , 'chart_div' )->get( $chart_array , $options );
			}
			
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







			<?php $this->Form->end(); ?>

		</div>
</div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script>
		<?php echo $chart;?>
	</script>
