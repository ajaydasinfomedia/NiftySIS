<script>
$(document).ready(function() {
	$('#calendar').fullCalendar({		 
		header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
		},
		events: <?php echo $data;?>,
		eventClick:  function(event, jsEvent, view) {
				var color = event.color;
        		$(".ui-widget-content").css("box-shadow","inset 0px 3px 0px 0px #"+color);						
        		$("#eventnm").html(event.title);			
        		$("#eventdesc").html(event.desc);
        		$("#eventstart").html(event.startd);
        		$("#eventend").html(event.endd);
        		$("#eventContent").dialog({ 
					modal: true, 
					title: event.type+" Details",
				});
   		 	},
								
			eventRender: function(event, element) { 				
	          element.find('.fc-event-title').append( event.type ); 
	        }, 
			timeFormat: 'h(:mm)t'
	});	 
	
	jQuery('body').on('click', '.viewdetail', function() {
		
		var get_id=jQuery(this).attr('data-id');
		var get_type=jQuery(this).attr('data-type');

		$.ajax({
				type:'POST',
				url:'<?php echo $this->Url->build(["controller"=>"Templet","action"=>"view"]); ?>',
				data:{id:get_id,type:get_type},
				success:function(getdata){
					$(".modal-content").html(getdata);
				},
				beforeSend:function(){
					$(".modal-content").html("<center><h2 class=text-muted><b>Loading...</b></h2></center>");
				},
				error:function(e){						
					console.log(e);
				},
		});
	});
	$("#myModal1").draggable({
		handle: ".modal-header"
	});
});
</script>
<style>
.ui-dialog .ui-dialog-titlebar-close{
	position: absolute;
    width: 0px;
    margin: 0px;
    padding: 0px;
    height: 0px;
    border: medium none;
	border-radius: unset;
}
.ui-widget-content{
    background: #FFFFFF;
    border: 3px solid #1B1B1B;
    color: #FFFFFF;
}
.modal .modal-content.dashboard_model .modal-body{
	overflow: auto;
}
.ui-widget-content .ui-dialog-titlebar {
    background-color: #FFFFFF;
	color: #4E5E6A!important;
}
#eventContent{
    float: none;
    width: auto;
	display: none;
	padding: 0px 1em;
}
.ui-draggable .ui-dialog-titlebar{
	border: 0px;
	padding: 15px;
}
.ui-widget-content.ui-draggable{
	min-width:60%!important;
	border: none;
	border-radius: unset;
	padding: 0px;
}
.ui-dialog .ui-dialog-title{
	font-weight: 600;
}
.ui-dialog-titlebar-close{
	outline: 0;
}
.ui-dialog-titlebar-close:after
{
	content: '\f00d';
    font-family: FontAwesome;
    font-size: 14px;
    top: -10px;
    position: absolute;
    right: 15px;
    outline: 0;
    color: #000;
	font-weight: normal;
}
</style>
<div id="eventContent" title="Event Details">
	<style>
	table.dataTable{
		margin-top: 0px !important;
	}
	.table > tbody > tr{
		border-top: 1px solid #dddddd;
	}
	.table > tbody > tr > td{
		border: medium none;
	}
	table.dataTable.no-footer,
	table.dataTable thead th
	{
		border-bottom: medium none;
	}
	</style>
	<table id="examlist" class="table table-striped calendarlist" cellspacing="0" width="100%" style="width: 100%;float:left;color: #4E5E6A;margin-bottom: 0px;">
		<thead></thead>
		<tfoot></tfoot>	
		<tbody>	
			<tr>
				<td style="max-width:120px;min-width:120px;float:left;"><?php echo __('Title '); ?> </td>
				<td style="float: left;"><span id="eventnm"></span></td>
			</tr>
			<tr>
				<td style="max-width:120px;min-width:120px;float:left;"><?php echo __('Description '); ?> </td>
				<td style="float: left;"><div id="eventdesc"></div></td>
			</tr>
			<tr>
				<td style="max-width:120px;min-width:120px;float:left;"><?php echo __('Start Date '); ?> </td>
				<td style="float: left;"><div id="eventstart"></div></td>
			</tr>
			<tr>
				<td style="max-width:120px;min-width:120px;float:left;"><?php echo __('End Date '); ?> </td>
				<td style="float: left;"><div id="eventend"></div></td>
			</tr>
		</tbody>
	</table>
</div>
<div class="modal fade " id="myModal1" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content dashboard_model">
				
        </div>
    </div>  
</div>
<div class="default_main">
	<div class="row">
	<?php
	$stud_date = $this->Setting->getfieldname('date_format');
	echo $this->Html->link('',['controller' => 'Setting', 'action' => 'generalsetting']);
	?>
			<div class="responsivesort col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="panel info-box panel-white">
					<div class="panel-body student">
						<span class="info-box-icon bg-aqua">
							<i class="ion ion-ios-gear-outline"><?php echo $this->Html->image('student.png');?></i>
						</span>
						<div class="info-box-stats">	
							<?php  echo $this->Html->link(__('Student'),['controller' => 'Student', 'action' => 'studentlist'],['class' =>'studdash']);?>							
							<p class="counter"><?php echo $stud_count;?></p>
						</div>
					</div>
				</div>
			</div>
			<div class="responsivesort col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="panel info-box panel-white">
					<div class="panel-body teacher">
						<span class="info-box-icon bg-aqua">
							<i class="ion ion-ios-gear-outline"><?php echo $this->Html->image('teacher.png');?></i>
						</span>
						<div class="info-box-stats">			
							<?php  echo $this->Html->link(__('Teacher'),['controller' => 'Teacher', 'action' => 'teacherlist'],['class' =>'techdash']);?>
							<p class="counter"><?php echo $teacher_count;?></p>
						</div>
					</div>
				</div>
			</div>
			<div class="responsivesort col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="panel info-box panel-white">
					<div class="panel-body parent">
						<span class="info-box-icon bg-aqua">
							<i class="ion ion-ios-gear-outline"><?php echo $this->Html->image('parents.png');?></i>
						</span>
						<div class="info-box-stats">					
							<?php  echo $this->Html->link(__('Parent'),['controller' => 'Parent', 'action' => 'parentlist'],['class' =>'parentdash']);?>
							<p class="counter"><?php echo $parent_count;?></p>
						</div>
					</div>
				</div>
			</div>
			<div class="responsivesort col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="panel info-box panel-white">
					<div class="panel-body attendence">
						<span class="info-box-icon bg-aqua">
							<i class="ion ion-ios-gear-outline"><?php echo $this->Html->image('attendance.png');?></i>
						</span>
						<div class="info-box-stats">				
							<?php  echo $this->Html->link(__('Today Attendance'),['controller' => 'Attendance', 'action' => 'attendance'],['class' =>'attenddash']);?>
							<p class="counter"><?php echo $attend_count;?></p>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
			<!--<div class="col-md-4 cal-div">
				<div class="panel panel-white">	
					<div class="panel-body">
						<div id="calendar">
							
						</div>
					</div>
				</div>
			</div>-->
			<div class="responsivesortdata col-md-6 col-sm-6 col-xs-12 cal-div">
				<?php
				if(isset($inboxdata)):
				?>
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="row">
						<div class="panel panel-white message">	
							<div class="panel-heading">
								<?php echo $this->Html->image('icons/new_notice.png');?>  
								<h3 class="panel-title"><?php echo __('Inbox Messages'); ?></h3>
								<p class="view_all">
									<?php echo $this->Html->link(__('View All'),['controller' => 'Message', 'action' => 'inbox']);?>
								</p>
							</div>
							
							<div class="panel-body" style="height:400px;overflow-y:scroll;">
								<?php
								$a=0;
								$user_id = $this->request->session()->read('user_id');							
								
								foreach($inboxdata as $msg):
									$status = $this->Setting->get_msg_status($msg['id'],$user_id);
								?>
							   <div class="calendar-event">
									<span class="user_profile">
										<?php echo $this->Html->image($msg['image'],array('height'=>'36px','width'=>'36px','class'=>'profileimg'));?>
									</span>
									<div class="message-event">						
										<p class="user_name"><?php echo $msg['user_name'];?></p>
										<p class="message_sub"><?php echo $msg['msg_sub'];?></p>
										<p class="message_desc">
											<?php echo (strlen($msg['msg_des']) > 150)?substr($msg['msg_des'],0,150)."...":$msg['msg_des'];?>
										</p>
									</div>
								</div>
								<?php						
								 endforeach;
								?>
							</div>						
						</div>
					</div>
				</div>
				<?php endif; ?>	
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="row">
						<div class="panel panel-white">
							<div class="panel-heading" style="margin-bottom: 15px;">
								<?php echo $this->Html->image('icons/new_calender.png');?>  
								<h3 class="panel-title"><?php echo __('Calendar'); ?></h3>		
							</div>
							<div class="panel-body">
								<div id="calendar"></div>
							</div>
						</div>
					</div>
				</div>						
			</div>
			<div class="responsivesortdata col-md-6 col-sm-6 col-xs-12 cal-div-top">
				<?php
				if(isset($notice_data)){
				?>
				<div class="panel panel-white notice">
					<div class="panel-heading">
						<?php echo $this->Html->image('icons/new_notice.png');?>  
						<h3 class="panel-title"><?php echo __('Notice List'); ?></h3>
						<p class="view_all">
							<?php echo $this->Html->link(__('View All'),['controller' => 'Notice', 'action' => 'noticelist']);?>
						</p>
					</div>
					<div class="panel-body">
						 <?php
                           foreach($notice_data as $notice_info):
                        ?>
                       <div class="events">
							<div class="calendar-event">						
								<p class="remainder_date"><?php echo date($stud_date,strtotime($notice_info['notice_start_date']))
								.str_repeat('&nbsp;', 6).
								date($stud_date,strtotime($notice_info['notice_end_date']));?></p>
								<p class="remainder_title"><a href="#" class="viewdetail" data-id="<?php echo $notice_info['notice_id'];?>" data-type="notice" data-toggle="modal" data-target="#myModal1" ><?php echo $notice_info['notice_title'];?></a></p>
								<p class="remainder_desc"><?php echo (strlen($notice_info['notice_comment']) > 50)?substr($notice_info['notice_comment'],0,50)."...":$notice_info['notice_comment'];?></p>
							</div>
						</div>
						<?php
						
                         endforeach;
                        ?>
					</div>
				</div>
				<?php
					}
					else
						echo "<div class='panel panel-white notice'><div class='panel-heading'><h4 class='text-danger'>No Notice Data Available</h4></div></div>";
				
				if(isset($news_list)){
				?>
				<div class="panel panel-white news">
					<div class="panel-heading">
						<?php echo $this->Html->image('icons/new_news.png');?>  
						<h3 class="panel-title"><?php echo __('News List'); ?></h3>	
						<p class="view_all">
							<?php echo $this->Html->link(__('View All'),['controller' => 'News', 'action' => 'newslist']);?>
						</p>	
					</div>
					<div class="panel-body">
					<?php
                    foreach($news_list as $news_info):
                    ?>
					<div class="events">
						<div class="calendar-event">
							<p class="remainder_date"><?php echo date($stud_date,strtotime($news_info['news_start_date']))
									.str_repeat('&nbsp;', 6).
									date($stud_date,strtotime($news_info['news_end_date']));?></p>
							<p class="remainder_title"><a href="#" class="viewdetail" data-id="<?php echo $news_info['news_id'];?>" data-type="news" data-toggle="modal" data-target="#myModal1" ><?php echo $news_info['news_title'];?></a></p>	
							<p class="remainder_desc"><?php echo (strlen($news_info['news_desc']) > 50)?substr($news_info['news_desc'],0,50)."...":$news_info['news_desc'];?></p>
						</div>
					</div>
                    <?php 
                    endforeach;
					?>           
										
					</div>
				</div>
				<?php
					}
					else
						echo "<div class='panel panel-white news'><div class='panel-heading'><h4 class='text-danger'>No News Data available</h4></div></div>";
				
				if(isset($holiday_list)){
				?>
				<div class="panel panel-white holiday">
					<div class="panel-heading">
						<?php echo $this->Html->image('icons/new_holiday.png');?>  
						<h3 class="panel-title"><?php echo __('Holiday List'); ?></h3>
						<p class="view_all">
							<?php echo $this->Html->link(__('View All'),['controller' => 'Holiday', 'action' => 'holidaylist']);?>
						</p>
					</div>
					<div class="panel-body">
						<?php
                     foreach($holiday_list as $holiday_info):
                     ?>
					<div class="events">
						<div class="calendar-event">
							<p class="remainder_date"><?php echo date($stud_date,strtotime($holiday_info['date']))
									.str_repeat('&nbsp;', 6).
									date($stud_date,strtotime($holiday_info['end_date']));?></p>
							<p class="remainder_title"><a href="#" class="viewdetail" data-id="<?php echo $holiday_info['holiday_id'];?>" data-type="holiday" data-toggle="modal" data-target="#myModal1" ><?php echo $holiday_info['holiday_title'];?></a></p>			
							<p class="remainder_desc"><?php echo (strlen($holiday_info['description']) > 50)?substr($holiday_info['description'],0,50)."...":$holiday_info['description'];?></p>
						</div>
					</div>
                    <?php 
                    endforeach;
					?>           
										
					</div>
				</div>
				<?php
					}
					else
						echo "<div class='panel panel-white holiday'><div class='panel-heading'><h4 class='text-danger'>No Holiday Data Available</h4></div></div>";
				
				if(isset($event_list)){
				?>
				<div class="panel panel-white event">
					<div class="panel-heading">
						<?php echo $this->Html->image('icons/new_event.png');?>  
						<h3 class="panel-title"><?php echo __('Event List'); ?></h3>
						<p class="view_all">
							<?php echo $this->Html->link(__('View All'),['controller' => 'Event', 'action' => 'eventlist']);?>
						</p>	
					</div>
					<div class="panel-body">
					<?php
                    foreach($event_list as $event_info):
                    ?>
					<div class="events">
						<div class="calendar-event">
							<p class="remainder_date"><?php echo date($stud_date,strtotime($event_info['start_date']))
									.str_repeat('&nbsp;', 6).
									date($stud_date,strtotime($event_info['end_date']));?></p>
							<p class="remainder_title"><a href="#" class="viewdetail" data-id="<?php echo $event_info['event_id'];?>" data-type="event" data-toggle="modal" data-target="#myModal1" ><?php echo $event_info['event_title'];?></a></p>	
							<p class="remainder_desc"><?php echo (strlen($event_info['event_desc']) > 50)?substr($event_info['event_desc'],0,50)."...":$event_info['event_desc'];?></p>
						</div>
					</div>
                    <?php 
                    endforeach;
					?>           
										
					</div>
				</div>
				<?php
					}
					else
						echo "<div class='panel panel-white event'><div class='panel-heading'><h4 class='text-danger'>No Event Data Available</h4></div></div>";
				?>
			</div>
		</div>
		</div>
</div>