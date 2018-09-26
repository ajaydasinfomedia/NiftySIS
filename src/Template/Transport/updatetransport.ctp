<div class="row">			
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					  <li class="active">
							
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-pencil-square-o fa-lg')) . __('Edit Transport'),array('controller'=>'Transport','action' => 'updatetransport',$this->Setting->my_simple_crypt($row['transport_id'],'e')),array('escape' => false));
						?>
						
						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->
						  
					  </li>
					  <li>
							
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Transport'),array('controller'=>'Transport','action' => 'addtransport'),array('escape' => false));
						?>
						
						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->
						  
					  </li>
				</ul>
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'updatetransport']]);?>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Route Name '));?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'route_name','value'=>$row['route_name'],'class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=>'Enter Route Name'));?>
							</div>
				</div>
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Vehicle identifier '));?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'vehicle_identifier','value'=>$row['vehicle_identifier'],'class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=>'Enter Vehicle Identifier'));?>
							</div>
				</div>
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Vehicle Registration Number '));?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'vehicle_registration_number','value'=>$row['vehicle_registration_number'],'class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=>'Enter Vehicle Registration Number'));?>
							</div>
				</div>
				
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Driver Name '));?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'driver_name','value'=>$row['driver_name'],'class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=>'Enter Driver Name'));?>
							</div>
				</div>
				
					<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Driver Phone Number '));?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'driver_phone_number','value'=>$row['driver_phone_number'],'class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=>'Enter Driver Phone Number'));?>
							</div>
				</div>
					
					
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Driver Address '));?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'driver_address','value'=>$row['driver_address'],'type'=>'textarea','class'=>'form-control validate[required,maxSize[150]]','PlaceHolder'=>'Enter Driver Address'));?>
							</div>
				</div>
				
<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Image '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
							
						<?php	
							if ($row['image']!='') 
							{
								echo $this->Html->image($row['image'],array('id'=>'oldimg','height'=>'100px','width'=>'100px'));
							} 
							else 
							{
								echo "Not Image";
							}
						?>
								<?php echo $this->Form->input('',array('type'=>'hidden','value'=>$row['image'],'name'=>'image2'));?>
								<?php echo $this->Form->input('',array('id'=>'newimg','class'=>'file','type'=>'file','name'=>'image','accept'=>'.png, .jpg, .jpeg, .gif'));?>
							</div>
				</div>
				
						
				
								<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Description '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'description','value'=>$row['description'],'type'=>'textarea','class'=>'form-control validate[maxSize[500]]','PlaceHolder'=>'Enter Description '));?>
							</div>
				</div>
				
			
			<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Route Fare '));?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('name'=>'route_fare','value'=>$row['route_fare'],'class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=>'Enter Route Fare'));?>
							</div>
				</div>
			
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
							<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
							<?php echo $this->Form->input(__('Edit Transport'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
							</div>
				</div>
			<?php $this->Form->end(); ?>
        
		</div>
</div>			