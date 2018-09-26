<script>

$( document ).ready(function(){
	
    $("#addroute").change(function(){
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
			  
				
   },
error: function(e) {
       alert("An error occurred: " + e.responseText);
       console.log(e.responseText);	
}

       });

       });
   });

</script>

<div class="row">	
	<ul role="tablist" class="nav nav-tabs panel_tabs">	
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Route List'),['controller' => 'Classroute', 'action' => 'classroutelist'],['escape' => false]);?>
		</li>
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Route'),['controller' => 'Classroute', 'action' => 'addclassroute'],['escape' => false]);?>
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-calendar fa-lg')) . __('Teacher Time Table'),['controller' => 'Classroute', 'action' => 'teacherroutelist'],['escape' => false]);?>
		</li>
	</ul>
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addclassroute']]);?>

				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Teacher '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<select class="form-control validate[required,maxSize[50]]" name="teacher_id">
									<?php foreach($usr as $it2):
										$name=$it2['first_name']." ".$it2['last_name'];
									?>
									<option value="<?php echo $it2['user_id'];?>"><?php echo $name;?></option> 
									
									<?php endforeach;?>
								</select>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Class '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<select class="form-control validate[required,maxSize[50]]" name="class_id" id="addroute">
								<option value=""> <?php echo __('Select Class'); ?> </option>
									<?php foreach($it as $it2):?><option value="<?php echo $it2['class_id'];?>"><?php echo $it2['class_name'];?></option> <?php endforeach;?>
								</select>
							</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Section '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<select class="form-control validate[required,maxSize[50]] ajaxdata" name="section" id="dep">
								<option value=""> <?php echo __('Select Section'); ?></option>
								</select>
							</div>
				</div>
				
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Subject '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<select class="form-control validate[required,maxSize[50]]" name="subject_id">
								<option value=""> <?php echo __('Select Subject'); ?> </option>
									<?php foreach($sub as $it2):?><option value="<?php echo $it2['subid'];?>"><?php echo $it2['sub_name'];?></option> <?php endforeach;?>
								</select>
							</div>
				</div>

				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Day '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('', array('name'=>'weekday','class'=>'form-control validate[required,maxSize[10]]','options' => array('1'=> __('Monday'),'2'=> __('Tuesday'),'3'=> __('Wednesday'),'4'=> __('Thursday'),'5'=> __('Friday'),'6'=> __('Saturday'),'7'=> __('Sunday')),'empty' => __('(choose one)')));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Start Time '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<?php echo $this->Form->input('', array('id'=>'start_hour','name'=>'start_hour','class'=>'form-control validate[required]','options' => array('01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12'),'empty' => __('(Select)')));?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<?php echo $this->Form->input('', array('id'=>'start_min','name'=>'start_min','class'=>'form-control validate[required]','options' => array('00'=>'00','01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56','57'=>'57','58'=>'58','59'=>'59')));?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<?php echo $this->Form->input('', array('id'=>'start_ampm','name'=>'start_ampm','class'=>'form-control validate[required]','options' => array('am'=>__('am'),'pm'=>__('pm'))));?>
							</div>
							
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('End Time '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<?php echo $this->Form->input('', array('id'=>'end_hour','name'=>'end_hour','class'=>'form-control validate[required]','options' => array('01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12'),'empty' => __('(Select)')));?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<?php echo $this->Form->input('', array('id'=>'end_min','name'=>'end_min','class'=>'form-control validate[required]','options' => array('00'=>'00','01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56','57'=>'57','58'=>'58','59'=>'59')));?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<?php echo $this->Form->input('', array('id'=>'end_ampm','name'=>'end_ampm','class'=>'form-control validate[required]','options' => array('am'=>__('am'),'pm'=>__('pm'))));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"></div>
							<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input(__('Add Class Routine'),array('type'=>'submit','name'=>'add','id'=>'add_class_route','class'=>'btn btn-success'));?>
							</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>
<script>
$(document).ready(function(){
	$( "#add_class_route" ).on("click",function(e) {
		
		var strh = $("#start_hour").val();
		var strm = $("#start_min").val();
		var strap = $("#start_ampm").val();
		
		var endh = $("#end_hour").val();
		var endm = $("#end_min").val();
		var endap = $("#end_ampm").val();
		
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
			alert("End time must be greater than start time");
			e.preventDefault(e);
		}
	});
});
</script>			