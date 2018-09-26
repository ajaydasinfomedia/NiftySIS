<form name="update_import" method="post" enctype="multipart/form-data" action="<?php echo $this->request->base;?>/Export/updateImport">
<div class="modal-header">
    <a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
    <h4 class="modal-title"><?php echo __('Update Import');?></h4>
</div>
<div class="modal-body clearfix npr section-model">		
	<div class="scroll" style="height: 200px;overflow-y:scroll;">
		<?php 
		if(isset($model_data))
		{
			?>
			<input type="hidden" name="export_id" value="<?php echo $model_data->export_id;?>">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12" style="line-height:34px;">
						<?php echo __('Import Title');?>
					</div>
					<div class="col-md-8 col-sm-8 col-xs-12">
						<input type="text" name="import_title" class="form-control" value="<?php echo $model_data->import_title;?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo __(''); ?></label>
					<div class="col-md-2 col-sm-2 col-xs-12">
						<?php echo $this->Form->input(__('Update Import'),array('type'=>'submit','name'=>'update_import','class'=>'btn btn-success'));?>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>			
</div>
</form>