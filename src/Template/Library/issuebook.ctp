<script>
$(function(){

	$('#dep').change(function(){

		var get_class_id= $(this).val();
		$.ajax({
				type:'POST',
				url:'<?php echo $this->Url->build(["controller"=>"Library","action"=>"ShowStudent"])?>',
				data:{id:get_class_id},


				success:function(getdata){
					$(".result").html(getdata);
				},

				error:function(){
					alert('An Error Occured:'+e.responseText);
					console.log();
				}

			});

	});


	$('body').on('click', '#periodsave', function() {
		var period_category = $('#periodtype').val();
                   $.ajax({
                       type: 'POST',
                      url: '<?php echo $this->Url->build(["controller" => "Library","action" => "addperiod"]);?>',
                     data : {periodtype:period_category},
                     success: function (data)
                        {	
							if(data != 'false')
							{
								$('#tab1').append('<tr class="del-'+data+'"><td class="text-center">'+period_category+' Days</td><td class="text-center"><a href="#" class="del" id="'+data+'" class="btn btn-success"><button class="btn btn-danger btn-xs">X</button></a></td><tr>');
								$('#period_title_id').append('<option value='+data+'>'+period_category+' Days</option>');
								$('#periodtype').val("");
							}
							$('#periodtype').val("");
						},
                    error: function(e) {
                 alert("An error occurred: " + e.responseText);
                    console.log(e);
                }

       });

	});  
    
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
					swal("Deleted!", "Your records has been deleted.", "success");		

				   $.ajax({
				   type:'POST',
				   url:'<?php echo $this->Url->build(['controller'=>'Library','action'=>'deleteperiod']);?>',
				   data:{periodid:id},
				   success:function(data){
						$("#period_title_id option[value="+id+"]").remove();
						$('body .del-'+id).fadeOut(300);

					}
					}) ;
				}
				else {	 
					swal("Cancelled", "Not removed!", "error"); 
				}
			});
	});
    
    
    $('#issue_date').change(function(){
        var date=$(this).val();
        $('#is_d').attr('value',date);
          
    });
    
    $('#period_title_id').change(function(){
       
    var sel_text= parseInt($("#period_title_id option:selected").text());
    var due_date=$('#is_d').val();
    
        var date=new Date(due_date);
        var newdate=new Date(date);
        newdate.setDate(newdate.getDate()+sel_text);
        var dd=newdate.getDate();
        var mm=newdate.getMonth()+1;
        var y=newdate.getFullYear();
        
            fo='';
            if(mm == 11 || mm == 12){
                fo='';
            }else{
                fo='0';
            }
        
        var someformat= fo +mm+ '/' + dd + '/' + y;
            $('#end_date').val(someformat);
       
    });
    
    
    $('#bc_id').change(function(){
   
        var bcate_id=$(this).val();
       
        if(bcate_id == ''){
            alert('This is not Currect Option');
        }else{
          
          $.ajax({
                type:'POST',
                url:'<?php echo $this->Url->build(['controller'=>'Library','action'=>'getbookname']);?>',
                data:{bc_id:bcate_id},
                success:function(data){
                   $('#getbook').html(data);
                }
   }) ;

        }                 
    });   
});
</script>


<!-- section ajax ---->
<script>

$( document ).ready(function(){
	
    $("#option_class").change(function(){
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



<!--Period Information Model-->
<div class="modal fade " id="myModalperiod" role="dialog">
    <div class="modal-dialog modal-md"  >

      <div class="modal-content">
        <div class="modal-header" >
        <a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
        <div id="result1"></div>
        <h4 align="center"><b><u><?php echo __('Issue Period');?></u></b></h4>

        </div>
        <div class="modal-body" >


<table class="table" id="tab1" align="center" style="width:40em">
		<tr>
                    <td class="text-center"><?php echo __('Period Time');?></td>
                    <td class="text-center"><?php echo __('Action');?></td>
                </tR>

               
                <?php
	foreach($category_data as $period_info){
?>
                <tr class="del-<?php echo $period_info['category_id'];?>">
                		<td class="text-center"><?php echo $period_info['category_type']; ?><?php echo __(' Days');?></td>
                		<td class="text-center">
                        <a href="#" class="del" id="<?php echo $period_info['category_id']; ?>" class="btn btn-success">
                           <button class="btn btn-danger btn-xs"><?php echo __('X'); ?> </button>
                        </a>
                        </td>
                </tr>
                <?php
            }
                ?>
	</table>

        </div>
           <div class="modal-footer">
           <center>
           <div class="row">
           	 <div class="col-md-2 col-sm-2 col-xs-12"></div>
              <div class="col-md-3 col-sm-6 col-xs-12">
                    <label class="col-sm-12 control-label" for="birth_date" id="post_name" value="catagory">
               		 <?php echo __('Period Time:');?>
					 <span class="require-field">*</span></label>
             </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
			<?php echo $this->Form->input('',array('class'=>'form-control validate[required]','id'=>'periodtype'));?>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12">
                <a href="#" id="periodsave" class="btn btn-success"><?php echo __('Add Period');?> </a>
            </div>
            </div>
	</center>
		
	  </div>

        </div>
      </div>

    </div>


<!--End Period-->



<div class="row schooltitle">
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					  <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Member List'),array('controller'=>'Library','action' => 'memberlist'),array('escape' => false));
						?>

					  </li>

					   <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Book List'),array('controller'=>'Library','action' => 'booklist'),array('escape' => false));
						?>

					  </li>


					<li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Book'),array('controller'=>'Library','action' => 'addbook'),array('escape' => false));
						?>

					  </li>

					  <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Issue List'),array('controller'=>'Library','action' => 'issuelist'),array('escape' => false));
						?>

					  </li>

					     <li class="active">

						<?php  
						if(isset($edit))
							echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Issue Book'),array('controller'=>'Library','action' => 'issuebook',$this->Setting->my_simple_crypt($update_book_issue['id'],'e')),array('escape' => false));
						else
							echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Issue Book'),array('controller'=>'Library','action' => 'issuebook'),array('escape' => false));
						?>

					  </li>
				</ul>
</div>

<?php
$stud_date = $this->Setting->getfieldname('date_format');
?>

<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addbook']]);?>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Class '));?><span class="require-field">*</span></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
					
                                <select class="form-control validate[required,maxSize[50]]" name="class_id" id="option_class">
                                    <option value=""> <?php echo __('---Select Class---'); ?> </option>
                                    <?php 
                                    	foreach($class_info as $class_data):
                                    ?>
            <option value="<?php echo $class_data['class_id']; ?>" <?php
                                   if(isset($update_book_issue['id'])){
                                        if($update_book_issue['class_id'] ==  $class_data['class_id']){
                                            echo 'selected="selected"';
                                        }else{
                                            echo '';
                                        }
                                    }
                    ?> ><?php echo $class_data['class_name']; ?></option>
                                    <?php
                                        endforeach;
                                    ?>
                                </select>
								
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12"></div>
				</div>
				
				<div class="form-group">
						<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Section'));?> <span class="require-field">*</span></div>
						<div class="col-md-8 col-sm-8 col-xs-12">
							<select class="form-control validate[required,maxSize[50]] ajaxdata" name="section" id="dep">
							<?php
								if(isset($update_book_issue['id'])){
							?>
								<option value="<?php echo $update_book_issue['section'] ;?>"><?php echo $this->Setting->section_name($update_book_issue['section']);?></option>
							<?php
								}
							?>
								<option value=""> <?php echo __('---Select Section---'); ?> </option>
								
							</select>
						</div>
				</div>

				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__(' Student '));?><span class="require-field">*</span></div>

							<div class="col-md-8 col-sm-8 col-xs-12">
								<div class="result">
							<select class="form-control validate[required,maxSize[50]]" id="bc_title_id" name="student_id">
		
                                <option value=""><?php echo __('---Select Student---'); ?> </option>
                                
                                
                                <?php 
                                foreach($get_stud_name as $stud_in):
                                ?>
                                 <option value="<?php echo $stud_in['user_id'];?>" <?php
                                          if(isset($update_book_issue['id'])){
                                        if($update_book_issue['student_id'] ==  $stud_in['user_id']){
                                            echo 'selected="selected"';
                                        }else{
                                            echo '';
                                        }
                                    }
       
                                         ?> ><?php echo $stud_in['first_name'];?></option>
                               	<?php
                                endforeach;
                                ?>				                  
							</select>
						</div>
						 </div>

						 <div class="col-md-2 col-sm-2 col-xs-12">

						 </div>
				</div>

				
				<input type="hidden" value="" id="is_d">	
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Issue Date '));?><span class="require-field">*</span></div>
							<div class="col-md-8 col-sm-8 col-xs-12 datepickericon">
                                <?php
                                    if(isset($update_book_issue['id'])){
                                        $issue_date= date($stud_date,strtotime($update_book_issue['issue_date']));
                                    }else{
                                        $issue_date='';
                                    }
                                
                                ?>
                                
								<?php echo $this->Form->input('',array('name'=>'issue_date','id'=>'date_of_birth20','type'=>'text','class'=>'form-control validate[required]','value'=>$issue_date,'PlaceHolder'=> __('Enter Issue Date ')));?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12"></div>
				</div>


				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__(' Period '));?><span class="require-field">*</span></div>

							<div class="col-md-2 col-sm-2 col-xs-12">
							<select class="form-control validate[required,maxSize[50]]" id="period_title_id" name="period">
							<option value=""><?php echo __('Select Period'); ?> </option>
                                
                                       <?php
	                                           foreach($category_data as $period_info_dropdown){
                                        ?>
                                    
                                            <option value="<?php echo $period_info_dropdown['category_id']; ?>"<?php
                                                    
                                                    
                                                      if(isset($update_book_issue['id'])){
                                        if($update_book_issue['period'] ==  $period_info_dropdown['category_id']){
                                            echo 'selected="selected"';
                                        }else{
                                            echo '';
                                        }
                                    }
 
                                                    ?> ><?php echo $period_info_dropdown['category_type'];?><?php echo __(' Days')?></option> 
                                        <?php
                                            }
                                        ?>
							       
							       
							</select>
						 </div>
						
						 <div class="col-md-2 col-sm-2 col-xs-12">
						 		<button type="button" id="period_add" name="" data-toggle="modal" data-target="#myModalperiod" class="btn btn-success"><?php echo __('Add Or Remove'); ?> </button>
						 </div>
				</div>


				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Return Date '));?><span class="require-field">*</span></div>
							<div class="col-md-8 col-sm-8 col-xs-12 datepickericon">
                                 <?php
                                    if(isset($update_book_issue['id'])){
                                        $end_date = date($stud_date,strtotime($update_book_issue['end_date']));
                                    }else{
                                        $end_date='';
                                    }
                                
                                ?>
								<?php echo $this->Form->input('',array('readonly','name'=>'end_date','id'=>'date_of_birth21','type'=>'text','value'=>$end_date,'class'=>'form-control validate[required]','style'=>'pointer-events: none;','PlaceHolder'=>__('Enter Return date')));?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12"></div>
				</div>

				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Book Category '));?><span class="require-field">*</span></div>

							<div class="col-md-8 col-sm-8 col-xs-12">
							<select class="form-control validate[required,maxSize[50]]" id="bc_id" name="cat_id">
							<option value=""><?php echo __('---Select Category---');?></option>
							       
                                <?php
                                    foreach($category_data_bc as $book_info){
                                ?>
                                <option value="<?php echo $book_info['category_id']; ?>" <?php
                                          if(isset($update_book_issue['id'])){
                                        if($update_book_issue['cat_id'] ==  $book_info['category_id']){
                                            echo 'selected="selected"';
                                        }else{
                                            echo '';
                                        }
                                    }
                                        
                                        
                                        ?> ><?php echo $book_info['category_type']; ?></option>
                                <?php
                                }
                                ?>
							       
							</select>
						 </div>

						 <div class="col-md-2 col-sm-2 col-xs-12">
						 	
						 </div>
				</div>
				


				<div class="form-group" style="">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"style="padding-top:0px"><?php echo $this->Form->label(__(' Book Name '));?><span class="require-field">*</span></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
                                <div id="getbook">
								
                                    <select name="book_id[]" id="example-dropRight" multiple class="validate[required,maxSize[50]]" >
                                       
                                        <?php
                                            foreach($get_book_per as $gb){
                                        ?>
                                        <option value="<?php echo $gb['id'] ?>" <?php
                                                    if(isset($update_book_issue['id'])){
                                                        $arr_tra=explode(",",$update_book_issue['book_id']);
                                                        for($j=0;$j<count($arr_tra);$j++){
                                                        if($arr_tra[$j] ==  $gb['id']){
                                                        echo 'selected="selected"';
                                                        }else{
                                                        echo '';
                                                        }
                                                }
                                    }

                                    ?> ><?php echo $gb['book_name'];?></option>
                                        <?php
                                        }
                                        ?>
                                        
  	      
                                </select>
                                 
                                    
                                </div>
                               
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12"></div>
				</div>



			
			
				<div class="form-group" style="margin-top:-20px">
							<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
							<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
							<?php echo $this->Form->input(__('Issue Book'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
							</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>			
<script>
$(function(){    
	$('#example-dropRight').multiselect({
		buttonWidth: '180px',
		dropRight: true
	});
	$('body').on('click', '#issue-book', function() {
		$select_val = $("#example-dropRight").val();
		
		if($select_val == null){
			alert('Select Any Book');
			return false;
		}
		else
			return true;	
	}); 
	
	$("#date_of_birth20").datepicker({
		numberOfMonths: 1,
		dateFormat: 'yy-mm-dd',  

		onSelect: function() {
		var date = $('#date_of_birth20').datepicker('getDate');  
		date.setDate(date.getDate() + 1);

		$("#date_of_birth21").datepicker("option","minDate", date);
		}
   });
   $("#date_of_birth21").datepicker({     
		numberOfMonths: 1,
		dateFormat: 'yy-mm-dd',  
		onSelect: function() {
		}
	}); 

	$('#period_title_id').change(function() {
		
		var date1 = $('#date_of_birth20').datepicker('getDate'); 
		var period = jQuery('#period_title_id').find('option:selected').text();
		date1.setDate(date1.getDate()+parseInt(period));
		$( "#date_of_birth21" ).datepicker("setDate", date1);
		
	}); 
});
</script>