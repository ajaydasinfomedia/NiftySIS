<div class="modal-header">
	<a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
	<h4 class="modal-title"><?php echo $header;?></h4>
</div>
<form name="medicinecat_form" action="editcriteria" method="post" class="form-horizontal">
<div class="modal-body clearfix">

	<div class="controls">
		<div class="form-row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<input type="text" value="<?php echo $retrieved_data->section_name;?>" class="form-control" name="section_name" style="color:#000;">
				<input type="hidden" value="<?php echo $retrieved_data->class_section_id;?>" name="class_section_id">
			</div>
		</div>		               
	</div>

</div>

<div class="modal-footer">                
	<button data-dismiss="modal" class="btn btn-danger" type="button"> <?php echo __('Close'); ?> </button>
	<button class="btn btn-success" type="submit"> <?php echo __('Submit'); ?> </button>
</div>
</form>