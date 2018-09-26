<script>
	$(document).ready(function(){
		$('body').on('click', '#save', function() {
			 var str = $('#category_name').val();
			 $.ajax({
				type: 'POST',
				url: '<?php echo $this->Url->build(["controller" => "Hostel","action" => "adddata"]);?>',
				data : {category_name:str},
				success: function (data)
				{
					
					if(data != 'false')
					{
						$('#tab1').append('<tr class="del-'+data+'"><td class="text-center">'+str+'</td><td class="text-center"><a href="#" class="del" id="'+data+'" class="btn btn-success"><button class="btn btn-danger btn-xs">X</button></a></td><tr>');
						$('#room_category').append('<option value='+data+'>'+str+'</option>');
					}
					$('#category_name').val("");
				},
				error: function(e) {
				alert("An error occurred: " + e.responseText);
				console.log(e);
				}	
			});
		});
		$("body").on('click','.del',function(){
			
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
					   url:'<?php echo $this->Url->build(['controller'=>'Hostel','action'=>'category_delete']);?>',
					   data:{category_id:id},
					   success:function(data){
							$('body .del-'+id).fadeOut(300);
							$('option.'+id).hide();
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
<?php
$next_id = 'RM'.sprintf("%04d",$next_id);

$room_unique_id = isset($row['room_unique_id'])?$row['room_unique_id']:$next_id;
$hostel_id = isset($row['hostel_id'])?$row['hostel_id']:'';
$room_category = isset($row['room_category'])?$row['room_category']:'';
$hostel_desc = isset($row['hostel_desc'])?$row['hostel_desc']:'';
$beds_capacity = isset($row['beds_capacity'])?$row['beds_capacity']:'';
$room_desc = isset($row['room_desc'])?$row['room_desc']:'';
?>
<div class="row">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Hostel List'),array('controller'=>'Comman','action' => 'hostellist'),array('escape' => false));?>
		</li>
		<li class="">
			<?php  
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Hostel'),array('controller'=>'Comman','action' => 'addhostel'),array('escape' => false));
			?>
		</li>
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Room List'),array('controller'=>'Comman','action' => 'roomlist'),array('escape' => false));?>
		</li>
		<li class="active">
			<?php  
			if(isset($edit))
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Edit Room'),array('controller'=>'Comman','action' => 'addroom',$row['room_id']),array('escape' => false));
			else
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Room'),array('controller'=>'Comman','action' => 'addroom'),array('escape' => false));
			?>
		</li>
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Beds List'),array('controller'=>'Comman','action' => 'bedslist'),array('escape' => false));?>
		</li>
		<li class="">
			<?php  
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Beds'),array('controller'=>'Comman','action' => 'addbeds'),array('escape' => false));
			?>
		</li>
	</ul>
</div>

<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header" >
				<a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
				<div id="result1"></div>
				<h4 align="center"><b><u> <?php echo __('Hostel Room Category'); ?> </u></b></h4>
			</div>
			<div class="modal-body" >
				<table class="table" id="tab1" align="center">
					<tr>
						<td class="text-center"><?php echo __('Category Name');?></td>
						<td class="text-center"><?php echo __('Action');?></td>
					</tr>
					<?php
					foreach($category_data as $fetch)
					{
					?>
					<tr class="del-<?php echo $fetch['hostel_room_category_id'];?>">
						<td class="text-center"><?php echo $fetch['category_name']; ?></td>
						<td class="text-center">
							<a href="#" class="del" id="<?php echo $fetch['hostel_room_category_id']; ?>" class="btn btn-success">
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
					<div class="col-md-3 col-sm-6 col-xs-12">
						<label class="col-sm-12 control-label validate[required]" for="birth_date" id="post_name" value="catagory">
						<?php echo __('Room Category');?> <span class="require-field">*</span></label>
					</div>
					<div class="col-md-5 col-sm-5 col-xs-12">
						<?php echo $this->Form->input('',array('class'=>'form-control validate[required,maxSize[50]]','id'=>'category_name'));?>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12">
						<button type="submit" id="save" class="btn btn-success"> <?php echo __('Save Category'); ?> </button>
					</div>
					<div class="col-md-1 col-sm-1 col-xs-12">
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
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addhostel']]);?>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Room Unique ID '));?><span class="require-field">*</span></div>
					<div class="col-md-8 col-sm-8 col-xs-12">
						<?php echo $this->Form->input('',array('readonly','name'=>'room_unique_id','value'=>$room_unique_id,'class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=> __('Enter Room Unique ID ')));?>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Hostel '));?><span class="require-field">*</span></div>
					<div class="col-md-8 col-sm-8 col-xs-12 export_model">
						<?php 
							echo @$this->form->select("hostel_id",$cls,["default"=>$hostel_id,"name"=>"hostel_id","empty"=>__("Select Hostel"),"class"=>"form-control validate[required,maxSize[50]]"]);
						?>				
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Room Category '));?><span class="require-field">*</span></div>
					<div class="col-md-8 col-sm-8 col-xs-12 export_model">
						<select class="form-control validate[required,maxSize[50]]" id="room_category" name="room_category">
							<option value=""> <?php echo __('Select Category Name'); ?> </option>
							<?php
							foreach($category_data as $fetch_data)
							{
								$selected = ($fetch_data['hostel_room_category_id'] == $room_category)?'selected':'';
							?>
							<option value="<?php echo $fetch_data['hostel_room_category_id'];?>" class="<?php echo $fetch_data['hostel_room_category_id'];?>" <?php echo $selected;?>>
							<?php echo $fetch_data['category_name'];?></option>
							<?php
							}
							?>
						</select>
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12 label_float">
						<button type="button" name="" data-toggle="modal" data-target="#myModal" class="btn btn-success"> <?php echo __('Add or Remove'); ?> </button>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Bed capacity '));?><span class="require-field">*</span></div>
					<div class="col-md-8 col-sm-8 col-xs-12">
						<?php echo $this->Form->input('',array('name'=>'beds_capacity','value'=>$beds_capacity,'class'=>'form-control validate[required,maxSize[3]]','PlaceHolder'=> __('Enter Bed capacity ')));?>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Description '));?><span class="require-field">*</span></div>
					<div class="col-md-8 col-sm-8 col-xs-12 export_model">
						<?php echo $this->Form->input('',array('name'=>'room_desc','value'=>$room_desc,'type'=>'textarea','class'=>'form-control validate[required,maxSize[500]]','PlaceHolder'=>'Enter Description '));?>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
					<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
					<?php 
					if(isset($edit))
						echo $this->Form->input(__('Edit Room'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));
					else
						echo $this->Form->input(__('Add Room'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));
					?>
					</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>		