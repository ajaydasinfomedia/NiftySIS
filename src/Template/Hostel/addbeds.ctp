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
		   $.ajax({
			   type:'POST',
			   url:'<?php echo $this->Url->build(['controller'=>'Hostel','action'=>'category_delete']);?>',
			   data:{category_id:id},
			   success:function(data){
					$('body .del-'+id).fadeOut(300);
					$('option.'+id).hide();
				}
		   }) ;
		});
	});
</script>
<?php
$next_id = 'BD'.sprintf("%04d",$next_id);

$bed_unique_id = isset($row['bed_unique_id'])?$row['bed_unique_id']:$next_id;
$room_unique_id = isset($row['room_unique_id'])?$row['room_unique_id']:'';
$bed_desc = isset($row['bed_desc'])?$row['bed_desc']:'';
?>
<div class="row">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Hostel List'),array('controller'=>'Hostel','action' => 'hostellist'),array('escape' => false));?>
		</li>
		<li class="">
			<?php  
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Hostel'),array('controller'=>'Hostel','action' => 'addhostel'),array('escape' => false));
			?>
		</li>
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Room List'),array('controller'=>'Hostel','action' => 'roomlist'),array('escape' => false));?>
		</li>
		<li class="">
			<?php  
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Room'),array('controller'=>'Hostel','action' => 'addroom'),array('escape' => false));
			?>
		</li>
		<li class="">
			<?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Beds List'),array('controller'=>'Hostel','action' => 'bedslist'),array('escape' => false));?>
		</li>
		<li class="active">
			<?php  
			if(isset($edit))
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Edit Beds'),array('controller'=>'Hostel','action' => 'addbeds',$row['bed_id']),array('escape' => false));
			else
				echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Beds'),array('controller'=>'Hostel','action' => 'addbeds'),array('escape' => false));
			?>
		</li>
	</ul>
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addhostel']]);?>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Bed Unique ID '));?><span class="require-field">*</span></div>
					<div class="col-md-8 col-sm-8 col-xs-12">
						<?php echo $this->Form->input('',array('readonly','name'=>'bed_unique_id','value'=>$bed_unique_id,'class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=> __('Enter Bed Unique ID ')));?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Room Unique ID '));?><span class="require-field">*</span></div>
					<div class="col-md-8 col-sm-8 col-xs-12">
						<?php echo @$this->form->select("room_unique_id",$cls,["default"=>$room_unique_id,"name"=>"room_unique_id","empty"=>__("Select Room Unique ID "),"class"=>"form-control validate[required,maxSize[50]]"]);?>		
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Description '));?><span class="require-field">*</span></div>
					<div class="col-md-8 col-sm-8 col-xs-12 export_model">
						<?php echo $this->Form->input('',array('name'=>'bed_desc','value'=>$bed_desc,'type'=>'textarea','class'=>'form-control validate[required,maxSize[500]]','PlaceHolder'=>__('Enter Description ')));?>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
					<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
					<?php 
					if(isset($edit))
						echo $this->Form->input(__('Edit Bed'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));
					else
						echo $this->Form->input(__('Add Bed'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));
					?>
					</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>		