<div class="row">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Student'),['controller' => 'Student', 'action' => 'addstudent'],['escape' => false]);?>
		</li>
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Student List'),['controller' => 'Student', 'action' => 'studentlist'],['escape' => false]);?>
		</li>
		<li class="active">		
			<?php echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('Upload Student CSV'),['controller' => 'Student', 'action' => 'uploadcsv'],['escape' => false]);?>
		</li>

		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Export Student'),['controller' => 'Student', 'action' => 'exportcsv'],['escape' => false]);?>
		</li>

	</ul>	
</div>
<div class="row">			
		<div class="panel-body">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'uploadcsv']]);?>
				<div class="form-group">
						<div class="col-md-10 col-sm-10 col-xs-12">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label('Select Class : ');?><span class="require-field">*</span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<select class="form-control validate[required]" name="class_name">
								<option value=""><?php echo __('Select Class'); ?></option>
									<?php foreach($it as $it2):?><option value="<?php echo $it2['class_id'];?>"><?php echo $it2['class_name'];?></option> <?php endforeach;?>
								</select>
							</div>
						</div>
				</div>
				<div class="form-group">
						<div class="col-md-10 col-sm-10 col-xs-12">
							<label class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo __('Select CSV File:'); ?> <span class="require-field">*</span></label>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<input class="" type="file" name="csv_file" value="Select CSV File">
							</div>
						</div>
				</div>
				<div class="form-group">
					
						<div class="col-md-10 col-sm-10 col-xs-12">
							<label class="col-md-2 col-sm-2 col-xs-12"></label>
							<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
							<?php echo $this->Form->input(__('Upload CSV File'),array('type'=>'submit','name'=>'upload_csv_file','class'=>'btn btn-success'));?>
								<!---<button class="btn btn-success" type="submit" name="upload_csv_file"> <?php echo __('Upload CSV File');?> </button>--->
							</div>
						</div>
				</div>
			</form>
        
		</div>
</div>			