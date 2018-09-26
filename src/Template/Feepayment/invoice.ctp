<link href="<?php echo $this->request->base;?>/css/bootstrap-datepicker.css" rel="stylesheet">
<script src="<?php echo $this->request->base;?>/js/bootstrap-datepicker.js"></script>
<script>
$(function(){
	
jQuery("#formID").validationEngine();

jQuery('body').on( 'change', '#ftypeid', function () {

	ftypeid=$('#ftypeid').val();
	cl_id=$('#class_id').val();


   	$.ajax({

                type:'POST',
				url:'<?php echo $this->Url->build(["controller"=>"Feepayment","action"=>"getamount"])?>',
				data:{fid:ftypeid,
					  cid:cl_id},

				success:function(getamt){

					$('#famt').attr('value',getamt);
				},

				error:function(){
					alert('An Error Occured:'+e.responseText);
					console.log();
				},

					beforeSend:function(){
								$('#famt').attr('value','Loading...');

					}



			});

});


 $('#dep').change(function(){

   var cl_id=$(this).val();

   if(cl_id == ''){
   alert('This is not currect Selection');
   return false;
   }else{

   


			$.ajax({

				 type:'POST',
				url:'<?php echo $this->Url->build(["controller"=>"Feepayment","action"=>"ShowStudent"])?>',
				data:{id:cl_id},
				success:function(getdatastd){
				$("#resultstd").html(getdatastd);

				},

				error:function(){
					alert('An Error Occured:'+e.responseText);
					console.log();
				}
			});

   }
});


 $('#class_id').change(function(){

   var cl_id=$(this).val();

   if(cl_id == ''){
   alert('This is not currect Selection');
   return false;
   }else{

   	$.ajax({

        type:'POST',
				url:'<?php echo $this->Url->build(["controller"=>"Feepayment","action"=>"showfeetype"])?>',
				data:{id:cl_id},

				success:function(getdata){

					$("#resultclass").html(getdata);

				},

				error:function(){
					alert('An Error Occured:'+e.responseText);
					console.log();
				}
			});

   }
});

});
</script>

<!-- section ajax ---->
<script>

$( document ).ready(function(){
	
    $("#class_id").change(function(){
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






<div class="row schooltitle">
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					  <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Fee Type List'),array('controller'=>'Feepayment','action' => 'feetypelist'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>

					   <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Fee Type'),array('controller'=>'Feepayment','action' => 'addfeetype'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>


					     <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Fees List'),array('controller'=>'Feepayment','action' => 'feelist'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>

					     <li class="active">

					<?php  
					if(isset($edit))
					{
						echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Edit Invoice'),array('controller'=>'Feepayment','action' => 'invoice',$this->Setting->my_simple_crypt($pay_info['fees_pay_id'],'e')),array('escape' => false));
					}
					else
					{
						echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Generate Invoice'),array('controller'=>'Feepayment','action' => 'invoice'),array('escape' => false));
					}
					
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>
				</ul>
</div>


<div class="row">
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'invoice']]);?>



				<div class="form-group">
						<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Class '));?> <span class="require-field">*</span></div>

							<div class="col-md-10 col-sm-10 col-xs-12">
							<select class="form-control validate[required,maxSize[50]]" id="class_id" name="class_id">
								
								<option value=""> <?php echo __('Select Class'); ?> </option>
								
								<?php foreach($class_array as $cl_info){
										
										if(isset($pay_info['fees_pay_id']) && $cla_id == $cl_info['class_id'])
										 {
											 $selected = "selected";
										 }
										 else
										 {
											 $selected = "";
										 }
								?>
								
								<option value="<?php echo $cl_info['class_id'];?>"<?php echo $selected;?>><?php echo $cl_info['class_name']; ?></option>
								
								<?php
								};
								?>
                            <select>

							</div>
				</div>
				
				<div class="form-group">
						<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Section '));?> <span class="require-field">*</span></div>
						<div class="col-md-10 col-sm-10 col-xs-12">
							<select class="form-control validate[required,maxSize[50]] ajaxdata" name="section" id="dep">
							<?php
								if(isset($pay_info['fees_pay_id'])){
							?>
								<option value="<?php echo $pay_info['section'] ;?>"><?php echo $this->Setting->section_name($pay_info['section']);?></option>
							<?php
								}
								else
								{
							?>
								<option value=""> <?php echo __('Select Section'); ?> </option>
								<?php
								}
								?>
							</select>
						</div>
				</div>	

				

				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Student '));?> <span class="require-field">*</span></div>

							<div class="col-md-10 col-sm-10 col-xs-12">
								<div id="resultstd">
									<select class="form-control validate[required,maxSize[50]]" id="student_id" name="student_id">

									<?php
										if(isset($pay_info['fees_pay_id'])){
									?>
									
										<option value="<?php echo $std_info['user_id']?>"><?php echo $std_info['first_name'];?></option>
									<?php
									}
									else
									{
									?>
										<option value=""> <?php echo __('Select Student'); ?> </option>
									<?php
									}
									?>		
									<select>
								</div>
							</div>
				</div>

				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Fee Type '));?> <span class="require-field">*</span></div>

							<div class="col-md-10 col-sm-10 col-xs-12">

							<div id="resultclass">
							<select class="form-control validate[required,maxSize[50]]" id="ftypeid" name="fees_id">
								<?php if(isset($pay_info['fees_pay_id'])){ ?>
										<option value="<?php echo $c_info['category_id'];?>"><?php echo $c_info['category_type']; ?></option>
									<?php
								}?>
							<option value=""> <?php echo __('Select Fee Type'); ?> </option>

							</select>
							</div>

						 </div>


				</div>


                <div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Amount '));?> <span class="require-field">*</span></div>
							<div class="resultamount"></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php
										if(isset($pay_info['fees_pay_id'])){
											?>
													<?php echo $this->Form->input('',array('name'=>'total_amount','class'=>'form-control validate[required,maxSize[8]]','value'=>$pay_info['total_amount'],'id'=>'famt','readonly'));?>
											<?php
										}else{
								 ?>
								<?php echo $this->Form->input('',array('name'=>'total_amount','class'=>'form-control validate[required,maxSize[8]]','value'=>'','id'=>'famt','readonly'));?>
								<?php
							}
							?>
							</div>

				</div>

<?php
	if(isset($pay_info['fees_pay_id'])){
		$desc=$pay_info['description'];
		$s_year=$pay_info['start_year'];
		$e_year=$pay_info['end_year'];
	}else{
		$desc='';
		$s_year='';
		$e_year='';

	}

 ?>

				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Description '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'description','value'=>$desc,'type'=>'textarea','class'=>'form-control validate[maxSize[500]]','PlaceHolder'=> __('Enter Description ')));?>
							</div>

				</div>


				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Year '));?> <span class="require-field">*</span></div>
							<?php /*<div class="col-md-2 col-sm-2 col-xs-12">
								<select class="form-control validate[required,maxSize[4]]" name="start_year" id="start_year">

									<?php if(isset($pay_info['fees_pay_id'])){

									?>
									<option value="<?php echo $pay_info['start_year'];?>"><?php echo $pay_info['start_year']; ?></option>
									<?php }
									?>

									<option value=""><?php echo __('Starting Year'); ?> </option>
									<?php
									for($i=2000;$i<=2029;$i++){
										?>
									<option value="<?php echo $i;?>"><?php echo $i;?></option>
							<?php	}?>

								</select>
							</div> */ ?>
			
							<div class="col-md-5 col-sm-5 col-xs-12">
							<?php if(isset($pay_info['fees_pay_id']))
							{
							?>
								<input type="text" name="start_year" value="<?php echo $pay_info['start_year'];?>" class="form-control validate[required] start_year date-one" id="start_year1" placeholder="Starting year">	
							<?php
							}
							else
							{
							?>
								<input type="text" name="start_year" class="form-control validate[required] start_year date-one" id="start_year1" placeholder="Starting year">
							<?php
							}
							?>	
							</div>
							<div class="col-md-5 col-sm-5 col-xs-12">
							<?php if(isset($pay_info['fees_pay_id']))
							{
							?>
								<input type="text" name="end_year"  value="<?php echo $pay_info['end_year'];?>" class="form-control validate[required] end_year date-two" id="end_year1" placeholder="Ending year">
							<?php
							}
							else
							{
							?>
								<input type="text" name="end_year" class="form-control validate[required] end_year date-two" id="end_year1" placeholder="Ending year">
							<?php
							}
							?>									
							</div>
							<?php /*<div class="col-md-5 col-sm-5 col-xs-12">
								<select class="form-control validate[required,maxSize[4]] end_year" name="end_year">
									<?php if(isset($pay_info['fees_pay_id'])){

									?>
									<option value="<?php echo $pay_info['end_year'];?>"><?php echo $pay_info['end_year']; ?></option>
									<?php }
									?>
									<option value=""> <?php echo __('Ending Year'); ?> </option>
									<?php
									for($j=2000;$j<=2029;$j++){
									?>
									<option value="<?php echo $j;?>"><?php echo $j;?></option>
									<?php
								}
									?>
								</select>
							</div> */ ?>

				</div>



				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
							<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
							<?php echo $this->Form->input(__('Create Invoice'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
							</div>
				</div>
			<?php $this->Form->end(); ?>

		</div>
</div>
<script type="text/javascript">
      $('.date-one').datepicker({
         minViewMode: 2,
         format: 'yyyy'
	   });
	   
	   $('.date-two').datepicker({
         minViewMode: 2,
         format: 'yyyy'
       });
</script>
<script>

$(".end_year").change(function(){
	
	var end_year =  $(this).val();

	var start_year = document.getElementById("start_year1").value;
	if (parseInt(end_year) >= parseInt(start_year))
	{
		
	} else {
		alert("Select Greater than Start Year");
		$(this).val("");
		return false;
	}
});
</script>