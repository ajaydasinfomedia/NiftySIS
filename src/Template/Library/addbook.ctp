<script>
$(document).ready(function(){
	$('body').on('click', '#racksave', function() {

		var rack_loc = $('#racklocation').val();
		
		
                   $.ajax({
                       type: 'POST',
                      url: '<?php echo $this->Url->build(["controller" => "Library","action" => "addrack"]);?>',
                     data : {racktype:rack_loc},
                     success: function (data)
                        {       
							if(data != 'false')
							{
							  $('#tab2').append('<tr class="del-'+data+'"><td class="text-center">'+rack_loc+'</td><td class="text-center"><a href="#" class="del" id="'+data+'" class="btn btn-success"><button class="btn btn-danger btn-xs">X</button></a></td><tr>');
							  $('#rack_title_id').append('<option value='+data+'>'+rack_loc+'</option>');
							  $('#racklocation').val("");
							}
							$('#racklocation').val("");
						},
                    error: function(e) {
                 alert("An error occurred: " + e.responseText);
                    console.log(e);
                }

       });

	});





	$('body').on('click', '#booksave', function() {
		var book_category = $('#categorytype').val();

		
                   $.ajax({
                       type: 'POST',
                      url: '<?php echo $this->Url->build(["controller" => "Library","action" => "adddata"]);?>',
                     data : {booktype:book_category},
                     success: function (data)
                        {
                        	if(data != 'false')
							{
								$('#tab1').append('<tr class="del-'+data+'"><td class="text-center">'+book_category+'</td><td class="text-center"><a href="#" class="del" id="'+data+'" class="btn btn-success"><button class="btn btn-danger btn-xs">X</button></a></td><tr>');
								$('#bc_title_id').append('<option value='+data+'>'+book_category+'</option>');
								$('#categorytype').val("");
							}
							$('#categorytype').val("");
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
				swal("Deleted!", "Your record has been deleted.", "success");

				$.ajax({
				type:'POST',
				url:'<?php echo $this->Url->build(['controller'=>'Library','action'=>'deletebc']);?>',
				data:{bcid:id},
				success:function(data){
					$("#bc_title_id option[value="+id+"]").remove();
					$('body .del-'+id).fadeOut(300);
				}
				}) ;
			}
			else {	 
				swal("Cancelled", "Not removed!", "error"); 
			}
		});	
	});
	
	$("body").on('click','.del1',function()
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
					url:'<?php echo $this->Url->build(['controller'=>'Library','action'=>'deletebc']);?>',
					data:{bcid:id},
					success:function(data){
						$("#rack_title_id option[value="+id+"]").remove();
						$('body .del-'+id).fadeOut(300);
					}
				});
			}
			else {	 
				swal("Cancelled", "Not removed!", "error"); 
			}
		});		
	});
});	

</script>



<!--Book Information Model-->
<div class="modal fade " id="myModalbook" role="dialog">
    <div class="modal-dialog modal-md"  >

      <div class="modal-content">
        <div class="modal-header" >
         <a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
        <div id="result1"></div>
        <h4 align="center"><b><u> <?php echo __('Category'); ?> </u></b></h4>

        </div>
        <div class="modal-body" >


<table class="table" id="tab1" align="center" style="width:40em">
		<tr>
                    <th class="text-center"><?php echo __('Category'); ?></th>
                    <th class="text-center"> <?php echo __('Action'); ?></th>
                </tR>
<?php
	foreach($category_data as $book_cate){
?>
                <tr class="del-<?php echo $book_cate['category_id'];?>">
                		<td class="text-center"><?php echo $book_cate['category_type']; ?></td>
                		<td class="text-center">
                        <a href="#" class="del" id="<?php echo $book_cate['category_id']; ?>" class="btn btn-success">
                           <button class="btn btn-danger btn-xs"><?php echo __('X');?></button>
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
               		 <?php echo __('Category Name');?><span class="require-field">*</span></label>
             </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
			<?php echo $this->Form->input('',array('class'=>'form-control validate[required,maxSize[50]]','id'=>'categorytype'));?>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12">
                <a href="#" id="booksave" class="btn btn-success"><?php echo __('Add Category'); ?> </a>
            </div>
            </div>
	</center>
		
	  </div>

        </div>
      </div>

    </div>


    <!-- End Book Category Model -->




    <!-- Rack Information Model -->
    <div class="modal fade " id="myModalrack" role="dialog">
    <div class="modal-dialog modal-md">

      <div class="modal-content">
        <div class="modal-header" >
          <a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
        <h4 align="center"><b><u><?php echo __('Rack Location'); ?></u></b></h4>

        </div>
        <div class="modal-body" >


<table class="table" id="tab2" align="center" style="width:40em">
		<tr>
                    <th class="text-center"><?php echo __('Rack Location');?></th>
                    <th class="text-center"><?php echo __('Action'); ?></th>
                </tR>

                <?php
	foreach($rack_data as $rack_info){
?>
                <tr class="del-<?php echo $rack_info['category_id'];?>">
                		<td class="text-center"><?php echo $rack_info['category_type']; ?></td>
                		<td class="text-center">
                        <a href="#" class="del1" id="<?php echo $rack_info['category_id']; ?>" class="btn btn-success">
                           <button class="btn btn-danger btn-xs"><?php echo __('X'); ?></button>
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
               		 <?php echo __('Rack Location Name');?><span class="require-field">*</span></label>
             </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
			<?php echo $this->Form->input('',array('class'=>'form-control validate[required]','id'=>'racklocation'));?>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12">
                <a href="#" id="racksave" class="btn btn-success"><?php echo __('Add Rack Location'); ?> </a>
            </div>
            </div>
	</center>
		
	  </div>

        </div>
      </div>

    </div>


    <!--End Rack Informatoin-->

<div class="row schooltitle">
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					  <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Member List'),array('controller'=>'Library','action' => 'memberlist'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>

					   <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Book List'),array('controller'=>'Library','action' => 'booklist'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>


					     <li class="active">

					<?php  
					if(isset($edit))	
						echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Edit Book'),array('controller'=>'Library','action' => 'addbook',$this->Setting->my_simple_crypt($update_book_row['id'],'e')),array('escape' => false));
					else
						echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Book'),array('controller'=>'Library','action' => 'addbook'),array('escape' => false));
					?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>

					     <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Issue List'),array('controller'=>'Library','action' => 'issuelist'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>

					     <li class="">

					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Issue Book'),array('controller'=>'Library','action' => 'issuebook'),array('escape' => false));
						?>

						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->

					  </li>
				</ul>
</div>
<?php
if(isset($update_book_row['id'])){
    $book_no     = $update_book_row['ISBN'];
    $book_name   = $update_book_row['book_name']; 
    $author_name = $update_book_row['author_name'];
    $price       = $update_book_row['price'];
    $qty         = $update_book_row['quantity'];
    $description = $update_book_row['description'];
    
}else{
    $book_no     = '';
    $book_name   = '';
    $author_name = '';
    $price       = '';
    $qty         = '';
    $description = '';
}
?>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addbook']]);?>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('ISBN '));?> <span class="require-field">*</span></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'ISBN','class'=>'form-control validate[required,maxSize[50]]','value'=>$book_no,'PlaceHolder'=> __('Enter ISBN Number ')));?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12"></div>
				</div>

				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Book Category '));?> <span class="require-field">*</span></div>

							<div class="col-md-8 col-sm-8 col-xs-12">
							<select class="form-control validate[required,maxSize[50]]" id="bc_title_id" name="cat_id">
                           
							<option value=""> <?php echo __('---Select Category---'); ?> </option>
							       <?php
											foreach($category_data as $book_c){
										?>
							        <option value="<?php echo $book_c['category_id'];?>" <?php if(isset($update_book_row['id'])){
                                           if($update_book_row['cat_id'] == $book_c['category_id']){
                                               echo 'selected="selected" ';
                                           }else{
                                               echo '';
                                           }
                                            
                                        } 
                                        ?> ><?php echo $book_c['category_type'];?></option>
                                                        <?php
                                                        }
                                                         ?>                 
							</select>
						 </div>

						 <div class="col-md-2 col-sm-2 col-xs-12">

						 		<button type="button" name="" data-toggle="modal" data-target="#myModalbook" class="btn btn-success"><?php echo __('Add Or Remove'); ?> </button>

						 </div>
				</div>

				
					
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Book Name '));?> <span class="require-field">*</span></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'book_name','value'=>$book_name,'type'=>'text','class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=> __('Enter Book Name ')));?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12"></div>
				</div>


				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Author Name '));?> <span class="require-field">*</span></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'author_name','type'=>'text','class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=> __('Enter Author Name '),'value'=>$author_name));?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12"></div>
				</div>

				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Rack Location '));?> <span class="require-field">*</span></div>

							<div class="col-md-8 col-sm-8 col-xs-12">
							<select class="form-control validate[required,maxSize[50]]" id="rack_title_id" name="rack_location">
							<option value=""><?php echo __('---Select Rack Location---'); ?> </option>
							       
							        <?php
											foreach($rack_data as $rack_cate){
										?>

							        <option value="<?php echo $rack_cate['category_id'];?>" <?php 
                                            
                                            if(isset($update_book_row['id'])){
                                                    if($update_book_row['rack_location'] == $rack_cate['category_id']){
                                               echo 'selected="selected" ';
                                           }else{
                                               echo '';
                                           }
                                            
                                        } 
                                            
                                            ?> ><?php echo $rack_cate['category_type'];?></option>
                                                        <?php
                                                        }
                                                         ?>      

							</select>
						 </div>

						 <div class="col-md-2 col-sm-2 col-xs-12">
						 		<button type="button" name="" data-toggle="modal" data-target="#myModalrack" class="btn btn-success"><?php echo __('Add Or Remove'); ?> </button>

						 </div>
				</div>

				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__(' Price '));?> <span class="require-field">*</span></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'price','type'=>'text','value'=>$price,'class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[8]]','PlaceHolder'=> __('Enter Price ')));?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-12"></div>
				</div>


				<div class="form-group number quantity">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__(' Quantity '));?> <span class="require-field">*</span></div>
							<div class="col-md-2 col-sm-2 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'quantity','min'=>'1','value'=>$qty,'type'=>'number','class'=>'form-control validate[required,maxSize[5]]'));?>
							</div>
				
				</div>


				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__(' Description '));?></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'description','type'=>'textarea','class'=>'form-control validate[maxSize[500]]','PlaceHolder'=> __('Enter Description '),'value'=>$description));?>
							</div>
						
				</div>



			
			
				<div class="form-group" style="margin-top:0px">
							<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
							<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
							<?php echo $this->Form->input(__('Add Book'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
							</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>			

