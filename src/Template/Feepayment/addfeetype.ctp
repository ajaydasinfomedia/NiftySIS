<script>
	$(document).ready(function(){			
		  $('body').on('click', '#save', function() {
			 var str = $('#feetype').val();
			 $.ajax({
				type: 'POST',
				url: '<?php echo $this->Url->build(["controller" => "Feepayment","action" => "adddata"]);?>',
				data : {feetype:str},
				success: function (data)
				{			
					if(data != 'false')
					{
						$('#tab1').append('<tr class="del-'+data+'"><td class="text-center">'+str+'</td><td class="text-center"><a href="#" class="del" id="'+data+'" class="btn btn-success"><button class="btn btn-danger btn-xs">X</button></a></td><tr>');
						$('#fees_title_id').append('<option value='+data+'>'+str+'</option>');
						$('#feetype').val("");
					}
					$('#feetype').val("");
				},
				error: function(e) {
				alert("An error occurred: " + e.responseText);
				console.log(e);
				}	
			});
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
	   jQuery("#add_fee_type").validationEngine();
   });

</script>
<!-- end section ajax ---->



<script>
$("body").on('click','.del',function()
{
	var id=$(this).attr('id');
	swal({   
		title: "Are You Sure?",
		text: "Are you sure you want to delete this?",   
		type: "warning",   
		showCancelButton: true,   
		confirmButtonColor: "#297FCA",   
		confirmButtonText: "Yes, delete!",
		cancelButtonText: "No, cancel it!",	
		closeOnConfirm: false,
		closeOnCancel: false
	}, function(isConfirm){
		if (isConfirm)
		{
			swal("Deleted!", "Your record has been deleted.", "success");
			
			$.ajax({
			type:'POST',
			url:'<?php echo $this->Url->build(['controller'=>'Feepayment','action'=>'delete']);?>',
			data:{feetypeid:id},
			success:function(data){
				$("#fees_title_id option[value="+id+"]").remove();
				$('body .del-'+id).fadeOut(300);

			}
			}) ;
		}
		else {	 
			swal("Cancelled", "Not removed!", "error"); 
		}
	});	
});
</script>

<?php

     if(isset($get_rec['fees_id'])){
     $get_rec['fees_id'];

     $amount=$get_rec['fees_amount'];
     $desc= $get_rec['description'];
     $linkname= __('Edit List');
     }else{

     $amount='';
     $desc='';
     $linkname= __('Add Fee Type');
     }


?>

<div class="row schooltitle">
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					  <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Fee Type List'),array('controller'=>'Feepayment','action' => 'feetypelist'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>

					   <li class="active">

					<?php 
					if(isset($edit))
					{
						echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) .  "$linkname",array('controller'=>'Feepayment','action' => 'addfeetype',$this->Setting->my_simple_crypt($get_rec['fees_id'],'e')),array('escape' => false));
					}
					else{
						echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) .  "$linkname",array('controller'=>'Feepayment','action' => 'addfeetype'),array('escape' => false));
					}
					
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>


					     <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Fees List'),array('controller'=>'Feepayment','action' => 'feelist'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>

					     <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Generate Invoice'),array('controller'=>'Feepayment','action' => 'invoice'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>
				</ul>
</div>



<div class="modal fade " id="myModal" role="dialog">
    <div class="modal-dialog modal-md"  >

      <div class="modal-content">
        <div class="modal-header" >
          <a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
        <div id="result1"></div>
        <h4 align="center"><b><u> <?php echo __('Fee Type'); ?> </u></b></h4>

        </div>
        <div class="modal-body" >


<table class="table" id="tab1" align="center" style="width:40em">
		<tr>
                    <td class="text-center"><?php echo __('Type');?></td>
                    <td class="text-center"> <?php echo __('Action'); ?> </td>
                </tR>
<?php
                foreach($category_data as $fetch){
     ?>
                   <tr class="del-<?php echo $fetch['category_id'];?>">
                        <td class="text-center"><?php echo $fetch['category_type']; ?></td>
			<td class="text-center">
                        <a href="#" class="del" id="<?php echo $fetch['category_id']; ?>" class="btn btn-success">
                           <button class="btn btn-danger btn-xs"> <?php echo __('X'); ?> </button>
                        </a>
                        </td>
			<input type="hidden" value="" name="delname" class="delid">
                    </tr>
<?php
}
?>
	</table>

        </div>
           <div class="modal-footer">
		   
           <center>
           <div class="row">
              <div class="col-md-2 col-sm-2 col-xs-12">
                <label class="col-sm-12 control-label validate[required]" for="birth_date" id="post_name" value="catagory">
                <?php echo __('Fee type');?> <span class="require-field">*</span></label>
             </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
				<?php echo $this->Form->input('',array('class'=>'form-control validate[required,maxSize[50]]','id'=>'feetype'));?>
            </div>

            <div class="col-md-2 col-sm-2 col-xs-12">
                <button type="submit" id="save" class="btn btn-success"> <?php echo __('Save Fee Type'); ?> </button>
            </div>

 <div class="col-md-2 col-sm-2 col-xs-12">
  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close');?></button>
            </div>


            </div>
	</center>

			 	<div id="message_board"></div>
				
	  </div>

        </div>
      </div>

    </div>


<div class="row">
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addfeetype']]);?>




				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Fee Type '));?> <span class="require-field">*</span></div>

							<div class="col-md-8 col-sm-8 col-xs-12">
							<select class="form-control validate[required,maxSize[50]]" id="fees_title_id" name="fees_title_id">
								<option value=""> <?php echo __('Select Fee Type'); ?></option>
								<?php
								 foreach($category_data as $fetch_data)
								 {
									 if(isset($get_rec['fees_id']) && $get_rec['fees_title_id'] == $fetch_data['category_id'])
									 {
										 $selected = "selected";
									 }
									 else
									 {
										 $selected = "";
									 }
								?>
									<option value="<?php echo $fetch_data['category_id'];?>" <?php echo $selected;?>>
									<?php echo $fetch_data['category_type'];?></option>
                                 <?php
                                  }
                                  ?>
							</select>

						 </div>

						 <div class="col-md-2 col-sm-2 col-xs-12 label_float">

						 		<button type="button" name="" data-toggle="modal" data-target="#myModal" class="btn btn-success"> <?php echo __('Add Or Remove'); ?> </button>

						 </div>
				</div>



				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Class '));?> <span class="require-field">*</span></div>

							<div class="col-md-8 col-sm-8 col-xs-12">
							<select class="form-control validate[required,maxSize[50]]" id="class_id" name="class_id">
						
                                <option value=""> <?php echo __('Select Class'); ?> </option>

                                <?php
                                foreach($std_class as $cl)
								{								
									if(isset($get_rec['fees_id']) && $get_rec['class_id'] == $cl['class_id'])
									 {
										 $selected = "selected";
									 }
									 else
									 {
										 $selected = "";
									 }
                                ?>
									<option value="<?php echo $cl['class_id'];?>"  <?php echo $selected;?>><?php echo $cl['class_name'];?></option>
                                <?php
                                }
                                ?>
                            <select>

						 </div>
						 	<div class="col-md-2 col-sm-2 col-xs-12"></div>

				</div>
				
				<div class="form-group">
						<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Section '));?> <span class="require-field">*</span></div>
						<div class="col-md-8 col-sm-8 col-xs-12">
							<select class="form-control validate[required,maxSize[50]] ajaxdata" name="section" id="dep">
															
								<?php
									if(isset($get_rec['fees_id'])){
								?>
									<option value="<?php echo $get_rec['section'] ;?>"><?php echo $this->Setting->section_name($get_rec['section']);?></option>
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
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Amount '));?> <span class="require-field">*</span></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'fees_amount','class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[8]]','PlaceHolder'=> __('Enter Amount '),'value'=>$amount));?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12"></div>
				</div>


				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Description '));?></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'description','type'=>'textarea','class'=>'form-control validate[maxSize[500]]','PlaceHolder'=> __('Enter Description '),'value'=>$desc));?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12"></div>
				</div>


				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
							<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
							<?php echo $this->Form->input(__('Create Fee Type'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
							</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>	
