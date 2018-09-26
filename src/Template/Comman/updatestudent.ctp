<div class="row">		
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li>		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Student List'),['controller' => 'Comman', 'action' => 'studentlist'],['escape' => false]);?>
		</li>
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-pencil-square-o fa-lg')) .__('Edit Student'),['controller' => 'Comman', 'action' => 'updatestudent',$this->Setting->my_simple_crypt($it['user_id'],'e')],['escape' => false]);?>
		</li>
	</ul>	
</div>
<?php
$user = $this->request->session()->read('user_id');
$role = $this->Setting->get_user_role($user);
?>
<div class="row">			
		<div class="panel-body">
			<?php 
			$stud_date = $this->Setting->getfieldname('date_format');
			echo $this->Form->Create('$it',['id'=>'formID','class'=>'form_horizontal formsize','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'']]);?>
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Class '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								
									<?php 
									
										foreach($cls as $it2):
										{
											$a[$it2['class_id']]=$it2['class_name'];
										}
										endforeach;
									
									?>

								<?php echo $this->Form->select('',array('options' =>$a),array('disabled','name'=>'classname','value' =>$it['classname'],'class'=>'form-control validate[maxSize[50]]'));?>

							</div>
				</div>
				
				
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter Roll Number '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('readonly','value'=>$it['roll_no'],'name'=>'roll_no','class'=>'form-control validate[required,maxSize[20]]'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter First Name '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['first_name'],'name'=>'first_name','class'=>'form-control validate[required,custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=>'Enter First Name'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter Middle Name '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['middle_name'],'name'=>'middle_name','class'=>'form-control validate[custom[onlyLetterSp],maxSize[50]]','PlaceHolder'=>'Enter Middle Name'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter Last Name '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['last_name'],'name'=>'last_name','class'=>'form-control validate[required,maxSize[50]]','PlaceHolder'=>'Enter Last Name'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Gender '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
		
								<label class="radio-inline label_float radio">
									<?php 
									$gender=!empty($it['gender'])?$it['gender']:'male';
										$options = array('male' => __('Male'), 'female' => __('Female'));
										echo $this->Form->radio('gender',$options,array('default'=>$gender));
									?>
								</label>
								
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Date Of Birth '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12 datepickericon">
								<?php echo $this->Form->input('',array('value'=>date($stud_date, strtotime($it['date_of_birth'])),'id'=>'date_of_birth','name'=>'date_of_birth','class'=>'form-control validate[required]','PlaceHolder'=>'Enter Birth Date'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter Address '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->textarea('',array('value'=>$it['address'],'name'=>'address','class'=>'form-control validate[required,maxSize[150]]','PlaceHolder'=>'Enter Valid Address','rows' => '3', 'cols' => '5'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select State '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('', array('value'=>$it['state'],'name'=>'state','class'=>'form-control'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select City '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('', array('value'=>$it['city'],'name'=>'city','class'=>'form-control validate[required,maxSize[50]]'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enter Pin Code '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['zip_code'],'name'=>'zip_code','class'=>'form-control validate[required,maxSize[10]]','PlaceHolder'=>'Enter Pin Code'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Mobile Number '));?></div>
							<div class="col-md-1 col-sm-1 col-xs-12">
								<?php echo $this->Form->input('',array('readonly','class'=>'form-control','name'=>'mobile_code','value'=>'+91'));?>
							</div>
							<div class="col-sm-7">
								<?php echo $this->Form->input('',array('value'=>$it['mobile_no'],'name'=>'mobile_no','class'=>'form-control validate[custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=>'Enter Mobile Number'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Alternate Mobile Number '));?></div>
							<div class="col-md-1 col-sm-1 col-xs-12">
								<?php echo $this->Form->input('',array('readonly','class'=>'form-control','name'=>'alternate_mobile_code','value'=>'+91'));?>
							</div>
							<div class="col-sm-7">
								<?php echo $this->Form->input('',array('value'=>$it['alternate_mobile_no'],'name'=>'alternate_mobile_no','class'=>'form-control validate[custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=>'Enter Alternate Mobile Number'));?>
							</div>
				</div>
				<?php 
				if(!$role == 'student' || !$role == 'teacher'){
				?>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Phone Number '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['phone'],'name'=>'phone','class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[15]]','PlaceHolder'=>'Enter Phone Number'));?>
							</div>
				</div>
				<?php } ?>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Valid Email '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['email'],'name'=>'email','class'=>'form-control validate[required,custom[email],maxSize[50]]','PlaceHolder'=>'Enter Valid Email'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Valid Username '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input('',array('readonly','value'=>$it['username'],'name'=>'username','class'=>'form-control validate[maxSize[50]]'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Image '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">
							
						<?php	
							if ($it['image']!='') 
							{
								echo $this->Html->image($it['image'],array('id'=>'oldimg','height'=>'100px','width'=>'100px'));
							} 
							else 
							{
								echo "Not Image";
							}
						?>
								<?php echo $this->Form->input('',array('type'=>'hidden','value'=>$it['image'],'name'=>'image2'));?>
								<?php echo $this->Form->input('',array('id'=>'newimg','class'=>'file','type'=>'file','name'=>'image','accept'=>'.jpg, .jpeg'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Approved '));?></div>
							<div class="col-md-10 col-sm-10 col-xs-12">	
								<?php	
						
									if($it['status']=='Approved')
									{
										echo $this->Form->select('',['Approved'=>''],['name'=>'status','multiple' => 'checkbox','default'=>'Approved']);
									}
									else
									{
										echo $this->Form->select('',['Approved'=>''],['name'=>'status','multiple' => 'checkbox']);
									}
								
								?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12"></div>
							<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
								<?php echo $this->Form->input(__('Save Student'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success','id'=>'MyName'));?>
							</div>
				</div>

			<?php $this->Form->end(); ?>
		</div>
</div>			
