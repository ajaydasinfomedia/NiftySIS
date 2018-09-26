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
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-pencil-square-o fa-lg')) . __('Edit Route'),['controller' => 'Classroute', 'action' => 'updateteacherroute',$this->Setting->my_simple_crypt($it['route_id'],'e')],['escape' => false]);?>
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-calendar fa-lg')) . __('Teacher Time Table'),['controller' => 'Classroute', 'action' => 'teacherroutelist'],['escape' => false]);?>
		</li>
	</ul>
</div>

<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->create('$it',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>				

				<div class="form-group">
							<label class="col-md-2 col-sm-2 col-xs-12"> <?php echo __('Select Teacher :'); ?> </label>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php 
									echo @$this->form->select("teacher_id",$usr,["default"=>$it['teacher_id'],"empty"=>__("Select Teacher"),"class"=>"form-control validate[required,maxSize[50]]"]);
								?>
							</div>
				</div>
				<div class="form-group">
							<label class="col-md-2 col-sm-2 col-xs-12"> <?php echo __('Select Class :'); ?> </label>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php 
									echo @$this->form->select("class_id",$cls,["default"=>$it['class_id'],"empty"=>__("Select Class"),"class"=>"form-control validate[required,maxSize[50]]","id"=>"addroute"]);
								?>
							</div>
				</div>
				
				<div class="form-group">
						<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label(__('Select Section : '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php 
									echo @$this->form->select("section",$sect,["default"=>$it['section'],"empty"=>__("Select Section"),"class"=>"form-control validate[required,maxSize[50]] ajaxdata","name"=>"section"]);
								?>
							
							
								<!---<select class="form-control validate[required] ajaxdata" name="section" id="dep">
									<option value="<?php echo $it['section']; ?>"><?php echo $it['section']; ?></option>
								</select>--->
						</div>
				</div>
				
				<div class="form-group">
							<label class="col-md-2 col-sm-2 col-xs-12"> <?php echo __('Select Subject :'); ?> </label>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php 
									echo @$this->form->select("subject_id",$sub,["default"=>$it['subject_id'],"empty"=>__("Select Subject"),"class"=>"form-control validate[required,maxSize[50]]"]);
								?>
							</div>
				</div>

				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label(__('Select Day : '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
							<?php $a=$it['weekday'];?>
								<?php echo $this->Form->input('', array('name'=>'weekday','class'=>'form-control validate[required,maxSize[10]]','options' => array('1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursday','5'=>'Friday','6'=>'Saturday','7'=>'Sunday'),'default'=>$a));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label(__('Start Time : '));?></div>
							<div class="col-md-2 col-sm-2 col-xs-12">
							<?php $a=$it['start_time'];							
							$a1=explode(':',$a);
							?>
								<?php echo $this->Form->input('', array('id'=>'start_hour','name'=>'start_hour','class'=>'form-control validate[required]','options' => array('01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12'),'default'=>$a1[0]));?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<?php echo $this->Form->input('', array('id'=>'start_min','name'=>'start_min','class'=>'form-control validate[required]','options' => array('00'=>'00','01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56','57'=>'57','58'=>'58','59'=>'59'),'default'=>$a1[1]));?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<?php echo $this->Form->input('', array('id'=>'start_ampm','name'=>'start_ampm','class'=>'form-control validate[required]','options' => array('am'=>'am','pm'=>'pm'),'default'=>$a1[2]));?>
							</div>
							
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label(__('End Time : '));?></div>
							<div class="col-md-2 col-sm-2 col-xs-12">
							<?php $a=$it['end_time'];							
							$a1=explode(':',$a);
							?>
								<?php echo $this->Form->input('', array('id'=>'end_hour','name'=>'end_hour','class'=>'form-control validate[required]','options' => array('01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12'),'default'=>$a1[0]));?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<?php echo $this->Form->input('', array('id'=>'end_min','name'=>'end_min','class'=>'form-control validate[required]','options' => array('00'=>'00','01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15','16'=>'16','17'=>'17','18'=>'18','19'=>'19','20'=>'20','21'=>'21','22'=>'22','23'=>'23','24'=>'24','25'=>'25','26'=>'26','27'=>'27','28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56','57'=>'57','58'=>'58','59'=>'59'),'default'=>$a1[1]));?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<?php echo $this->Form->input('', array('id'=>'end_ampm','name'=>'end_ampm','class'=>'form-control validate[required]','options' => array('am'=>'am','pm'=>'pm'),'default'=>$a1[2]));?>
							</div>
				</div>
				<div class="form-group">
							<label class="col-md-2 col-sm-2 col-xs-12"></label>
							<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input(__('Save Route'),array('type'=>'submit','name'=>'add','id'=>'update_teacher_class_route','class'=>'btn btn-success'));?>
							</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>
<script>
$(document).ready(function(){
	$( "#update_teacher_class_route" ).on("click",function(e) {
		
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
