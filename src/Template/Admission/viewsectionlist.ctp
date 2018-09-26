<div class="modal-header">
    <a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
    <h4 class="modal-title"><?php echo $header;?></h4>
</div>
<div class="modal-body clearfix npr section-model">
	 		
			<div class="scroll" style="height: 200px;overflow-y:scroll;">
		
						<table class="table table-bordered table-striped table-hover" id="class_section">
	          	 	<thead>
						<tr>		
							<th><?php echo __('School Name');?></th>
							<th><?php echo __('Action');?></th>
						</tr>
					</thead>
					<tbody>
					<?php 
						if(!empty($model_data))
						{
							foreach($model_data as $retrive_data)
							{
								?>
								<tr id="term-<?php echo $retrive_data['adminssion_main_id'];?>">
									<td><?php echo $retrive_data['title'];?></td>
									<td>

									<a class="widget-icon widget-icon-dark edit-term" href="#" 
									data-type="<?php echo $model;?>"
									data-id="<?php echo $retrive_data['adminssion_main_id'];?>">
									
									<span class="icon-pencil"></span>
									</a>

									<a class="widget-icon widget-icon-dark remove-term" href="#"
									data-type="<?php echo $model;?>"
									data-id="<?php echo $retrive_data['adminssion_main_id'];?>">
									<span class="icon-trash"></span>
									</a>
									</td>	
								</tr>
								
								<?php
							}
						}
					?>						
					</tbody>
	      </table>				
       </div>
	  	 	<div class="form-group">
				<label class="col-sm-3 control-label" for="fee_type"> <?php echo __('Previous School'); ?> <span class="require-field" style="line-height: 30px;">*</span></label>
				<div class="col-md-5 col-sm-5 col-xs-12">
					<input id="txtfee_type" class="form-control text-input" type="text" value="" name="txtfee_type" placeholder="">
				</div>
				<input value="<?php echo $model;?>" type="Hidden" id="class_id">
				
				<div class="col-md-3 col-sm-6 col-xs-12">
					<a class="btn btn-default btn-add-cat" href="#" style="float: left;width: auto;border-radius: 0;padding: 5px 10px;box-shadow: none;font-size: 14px;line-height: 18px;margin: 2px 0;"><?php echo __('Add New School'); ?></a>
				</div>
			</div>			
</div>
<div class="modal-footer">                
     <button type="button" class="btn btn-danger" data-dismiss="modal"> <?php echo __('Close'); ?> </button>
</div>
