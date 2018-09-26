<script type="text/javascript"	src="http://192.168.1.31/ashvin/wordpress/school-management/wp-content/plugins/school-management/assets/accordian/jquery-1.10.2.js"></script>
<script type="text/javascript"	src="http://192.168.1.31/ashvin/wordpress/school-management/wp-content/plugins/school-management/assets/js/fullcalendar.min.js"></script>

<script>
	
	 $(document).ready(function() {
	
		 $('#calendar').fullCalendar({
			 header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},
			editable: false,
			eventLimit: true, // allow "more" link when too many events
			events: []		});

		 
	});

</script>
			<div class="right_side">
					<div class="row">
						<div class="responsivesort col-lg-3 col-md-3 col-sm-6 col-xs-12">
							<div class="panel info-box panel-white">
								<div class="panel-body student">
									<div class="info-box-stats">
										<p class="counter"> <?php echo __('0'); ?> </p>
										<span class="info-box-title"> <?php echo __('Student'); ?> </span>
									</div>
								</div>
							</div>
						</div>
						<div class="responsivesort col-lg-3 col-md-3 col-sm-6 col-xs-12">
							<div class="panel info-box panel-white">
								<div class="panel-body teacher">
									<div class="info-box-stats">
										<p class="counter"> <?php echo __('0'); ?> </p>
										<span class="info-box-title"> <?php echo __('Teacher'); ?> </span>
									</div>
								</div>
							</div>
						</div>
						<div class="responsivesort col-lg-3 col-md-3 col-sm-6 col-xs-12">
							<div class="panel info-box panel-white">
								<div class="panel-body parent">
									<div class="info-box-stats">
										<p class="counter"> <?php echo __('0'); ?> </p>
										<span class="info-box-title"> <?php echo __('Parent'); ?> </span>
									</div>
								</div>
							</div>
						</div>
						<div class="responsivesort col-lg-3 col-md-3 col-sm-6 col-xs-12">
							<div class="panel info-box panel-white">
								<div class="panel-body attendence">
									<div class="info-box-stats">
										<p class="counter"> <?php echo __('0'); ?> </p>
										<span class="info-box-title"> <?php echo __("Today's Attendance"); ?> </span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row responsivesort">
						<div class="col-md-8 col-sm-8 col-xs-12">
							<div class="panel panel-white">
							<div class="panel-body">
							<div id="calendar" class="fc fc-ltr fc-unthemed"></div>
							</div></div></div>
						<div class="col-md-4">
							<div class="panel panel-white">
								<div class="panel-heading">
									<h3 class="panel-title"> <?php echo __('Notice Board'); ?> </h3>
								</div>
								<div class="panel-body">
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="panel panel-white">
								<div class="panel-heading">
									<h3 class="panel-title"> <?php echo __('Holiday List'); ?> </h3>
								</div>
								<div class="panel-body">
								</div>
							</div>
						</div>	
		</div>	