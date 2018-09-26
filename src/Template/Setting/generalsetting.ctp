<!--  <?php
if(isset($hh))
{
var_dump($hh);

} 
?> -->
<?php 
$laung = $this->Setting->getfieldname('system_lang');
$studentID = $this->Setting->generate_studentID();
/* var_dump($studentID); */
?>
<div class="panel-body">
	<h2> <?php echo __('General Setting'); ?> </h2>
		<div class="panel-body">	
			<?php echo $this->Form->Create('$it',['id'=>'formID','class'=>'form_horizontal','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['action'=>'addclassroute']]);?>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('School Name'));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php echo $this->Form->input('School Name',array('value'=>$it['school_name'],'name'=>'school_name','class'=>'form-control validate[required,maxSize[100]] text-input','PlaceHolder'=> __('Enter School Name')));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Favicon Icon'));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php	
									if (isset($it['school_icon'])) 
									{
										echo $this->Html->image($it['school_icon'],array('height'=>'35px','width'=>'35px'));
									} 
									else 
									{
										echo "Not Image";
									}
								?>
								<?php if(isset($it['school_icon'])) echo $this->Form->input('',array('type'=>'hidden','value'=>$it['school_icon'],'name'=>'old_icon'));?>
								<?php echo $this->Form->input('',array('class'=>'file','type'=>'file','name'=>'school_icon','accept'=>'.png, .jpg, .jpeg, .gif'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Starting Year'));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-8 col-sm-8 col-xs-12">			
								<?php echo $this->Form->input('',array('value'=>$it['start_year'],'name'=>'start_year','class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[4]] text-input','PlaceHolder'=> __('Enter Start Time')));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('School Address '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['school_address'],'name'=>'school_address','class'=>'form-control validate[required,maxSize[150]] text-input','PlaceHolder'=> __('Enter School Address')));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Office Phone Number '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['office_phone_no'],'name'=>'office_phone_no','class'=>'form-control validate[required,custom[onlyNumberSp],maxSize[15]] text-input','PlaceHolder'=> __('Enter Office Phone Number')));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Country '));?></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
							
							<select id="country" class="form-control" name="country">
							<?php 
							foreach($xml as $country)
							{
								?><option value="<?php echo $country->name;?>" <?php if($it['country'] ==  $country->name ) { echo "selected"; }?>><?php echo $country->name;?></option><?php
							}
							?>	
							</select>
							
								
							</div>
				</div>
				
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('System Language '));?></div>
							
					<div class="col-md-8 col-sm-8 col-xs-12">
							<?php echo "<input type='hidden' id='s_lang' value='{$laung}'>";?>
						<select id="system_lang" class="form-control" name="system_lang">
						
							<option value="en" <?php echo ($laung == 'en')?"selected":"";?>><?php echo __('English/en');?></option>
							<option value="ar" <?php ($laung == 'ar')?"selected":"";?>><?php echo __('Arabic/ar');?></option>
							<option value="zh_CH" <?php ($laung == 'zh_CH')?"selected":"";?>><?php echo __('Chinese/zh-CH');?></option>
							<option value="cs" <?php ($laung == 'cs')?"selected":"";?>><?php echo __('Czech/cs');?></option>
							<option value="fr" <?php ($laung == 'fr')?"selected":"";?>><?php echo __('French/fr');?></option>
							<option value="de" <?php ($laung == 'de')?"selected":"";?>><?php echo __('German/de');?></option>
							<option value="el" <?php ($laung == 'el')?"selected":"";?>><?php echo __('Greek/el');?></option>					
							<option value="it" <?php ($laung == 'it')?"selected":"";?>><?php echo __('Italian/it');?></option>	
							<option value="ja" <?php ($laung == 'ja')?"selected":"";?>><?php echo __('Japan/ja');?></option>
							<option value="pl" <?php ($laung == 'pl')?"selected":"";?>><?php echo __('Polish/pl');?></option>
							<option value="pt_BR" <?php ($laung == 'pt_BR')?"selected":"";?>><?php echo __('Portuguese-BR/pt-BR');?></option>
							<option value="pt_PT" <?php ($laung == 'pt_PT')?"selected":"";?>><?php echo __('Portuguese-PT/pt-PT');?></option>						
							<option value="fa" <?php ($laung == 'fa')?"selected":"";?>><?php echo __('Persian');?></option>
							<option value="ru" <?php ($laung == 'ru')?"selected":"";?>><?php echo __('Russian/ru');?></option>
							<option value="es" <?php ($laung == 'es')?"selected":"";?>><?php echo __('Spanish/es');?></option>											
							<option value="th" <?php ($laung == 'th')?"selected":"";?>><?php echo __('Thai/th');?></option>
							<option value="tr" <?php ($laung == 'tr')?"selected":"";?>><?php echo __('Turkish/tr');?></option>
						</select>
						<script>
							var sys_lang = $("#s_lang").val();
							$("#system_lang option[value="+sys_lang+"]").prop("selected",true);
						</script>									
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Set Language to RTL '));?></div>
					<div class="col-md-8 col-sm-8 col-xs-12">
					<?php 
						$lang_rtl = isset($it['lang_rtl'])?$it['lang_rtl']:"";
						if($lang_rtl=='yes')
						{
							$a='yes';
							echo $this->Form->input('', array('value'=>$lang_rtl,'class'=>'','name'=>'lang_rtl','type' => 'select','multiple' => 'checkbox','options' => array('yes' => __('Enable')),'default'=>$a)); 
						}
						else
						{
							echo $this->Form->input('', array('value'=>$lang_rtl,'class'=>'','name'=>'lang_rtl','type' => 'select','multiple' => 'checkbox','options' => array('yes' => __('Enable')))); 
						}
					?>
					</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Email'));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['email'],'name'=>'email','class'=>'form-control validate[required,custom[email],maxSize[50]] text-input','PlaceHolder'=> __('Enter Email')));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('School Logo '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php	
									if ($it['school_logo']!='') 
									{
										echo $this->Html->image($it['school_logo'],array('height'=>'','width'=>''));
									} 
									else 
									{
										echo "Not Image";
									}
								?>
								<?php echo $this->Form->input('',array('type'=>'hidden','value'=>$it['school_logo'],'name'=>'image2'));?>
								<?php echo $this->Form->input('',array('class'=>'file','type'=>'file','name'=>'school_logo','accept'=>'.png, .jpg, .jpeg, .gif'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Profile Cover Image '));?></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php	
									if ($it['school_profile']!='') 
									{					
										echo $this->Html->image($it['school_profile'],array('height'=>'200px','width'=>'600px','style'=>'max-width: 100%;'));
									} 
									else 
									{
										echo "Not Image";
									}
								?>
								<?php echo $this->Form->input('',array('type'=>'hidden','value'=>$it['school_profile'],'name'=>'image3'));?> 
								<?php echo $this->Form->input('',array('class'=>'file','type'=>'file','name'=>'school_profile','accept'=>'.png, .jpg, .jpeg, .gif'));?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Enable Sandbox '));?></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php 
								if($it['enable_sandbox']=='yes')
								{
									$a='yes';
									echo $this->Form->input('', array('value'=>$it['enable_sandbox'],'class'=>'','name'=>'enable_sandbox','type' => 'select','multiple' => 'checkbox','options' => array('yes' => __('Enable')),'default'=>$a)); 
								}
								else
								{
									echo $this->Form->input('', array('value'=>$it['enable_sandbox'],'class'=>'','name'=>'enable_sandbox','type' => 'select','multiple' => 'checkbox','options' => array('yes' => __('Enable')))); 
								}
								?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Paypal Email Id '));?></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['paypal_email'],'name'=>'paypal_email','class'=>'form-control validate[custom[email]]','PlaceHolder'=>'Enter Paypal Email'));?>
							</div>
				</div>
				<div class="form-group">
						<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Currency'));?></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
							<?php 
							$currency_symbols = $this->Setting->currency_list();
							$currency = isset($it['currency_code'])?$it['currency_code']:'';
							?>
								<select class="form-control text-input" name="currency_code">
									  <option value=""><?php echo __('Select Currency');?></option>
										<?php 
										foreach($currency_symbols as $key => $value)
										{
											echo "<option value='".$key."' ".$this->setting->selected($key,$currency).">".$value['symbol']." ".$value['name']."</option>";
										}
										?>
		 
								</select>
							</div>
				
				</div>
				<div class="form-group">
						<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Select Date Format'));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
							<?php 
							$date_format = isset($it['date_format'])?$it['date_format']:'';
							?>
								<select name="date_format" class="form-control plan_list required">
									<option value="Y-m-d" <?php echo $this->Setting->selected("Y-m-d",$date_format);?>><?php echo date("Y-m-d");?></option>
									<option value="m-d-Y" <?php echo $this->Setting->selected("m-d-Y",$date_format);?>><?php echo date("m-d-Y");?></option>
									<option value="d-m-Y" <?php echo $this->Setting->selected("d-m-Y",$date_format);?>><?php echo date("d-m-Y");?></option>
								</select>
							</div>
				
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Admission Form Code '));?><span style="color:red;"><?php echo " *"; ?></span></div>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<?php echo $this->Form->input('',array('value'=>$it['admission_code'],'name'=>'admission_code','class'=>'form-control validate[required,maxSize[20]]','PlaceHolder'=>'Enter Admission Code'));?>
							</div>
				</div>
				<div class="form-group">
					<div class="head">
						<hr>
						<h4 class="section"><?php echo __('Student ID Setting'); ?></h4>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Student id Format'));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div class="col-md-10 col-sm-10 col-xs-12">
						<?php $stud_method = $this->Setting->getfieldname('stud_method');?>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<input type="radio" name="stud_method" value="Random" class="validate[required]" <?php echo $this->Setting->checked($stud_method,'Random');?>><?php echo __('Random');?>
						</div>
						<div class="col-md-2 col-sm-2 col-xs-12">
							<div class="row">
								<input type="radio" name="stud_method" value="Sequential" class="validate[required]" <?php echo $this->Setting->checked($stud_method,'Sequential');?>><?php echo __('Sequential');?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group no_of_digit">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Number Of Digits'));?><span style="color:red;"><?php echo " *"; ?></span></div>
					<div class="col-md-1 col-sm-1 col-xs-12">
						<?php echo $this->Form->input('',array('type'=>'number','min'=>'2','default'=>'4','name'=>'no_of_digit','class'=>'form-control validate[required,maxSize[20]]'));?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Prefix '));?></div>
					<div class="col-md-3 col-sm-6 col-xs-12">
						<?php echo $this->Form->input('stud_id_prefix',array('value'=>$it['stud_id_prefix'],'type'=>'text','name'=>'stud_id_prefix','class'=>'form-control validate[maxSize[50]]'));?>
					</div>
				</div>
				<div class="form-group">
					<div class="head">
					<hr>
						<h4 class="section"><?php echo __('Message Settings'); ?></h4>
					</div>
				</div>
				<div class="form-group">
							<div class="col-md-4 col-sm-4 col-xs-12 label_float"><?php echo $this->Form->label(__('Parent can send message to students'));?></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
							<?php 
							if($it['parent_msg_stud']=='yes')
								{
									$a='yes';
								
								}
								echo $this->Form->input('', array('value'=>$it['parent_msg_stud'],'class'=>'','name'=>'parent_msg_stud','type' => 'select','multiple' => 'checkbox','options' => array('yes' => __('Enable')),'default'=>$a)); ?>
							</div>
				</div>
				<div class="form-group">
							<div class="col-sm-4 label_float"><?php echo $this->Form->label(__('Student can send message to other students'));?></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
							<?php 
							if($it['stud_msg_other']=='yes')
								{
									$a='yes';
								
								}
								echo $this->Form->input('', array('value'=>$it['stud_msg_other'],'class'=>'','name'=>'stud_msg_other','type' => 'select','multiple' => 'checkbox','options' => array('yes' => __('Enable')),'default'=>$a)); ?>
							</div>
				</div>
				
				<?php /*
				<div class="form-group">
					<div class="head">
					<hr>
						<h4 class="section"><?php echo __('Fees Alert Message Settings'); ?></h4>
					</div>
				</div>
				
				<div class='form-group'>
				<div class='control-label col-md-2'><?php echo $this->Form->label(__('Enable Alert Mail')); ?></div>
				<div class="col-md-4 col-sm-4 col-xs-12">
				<?php					
				if($it['fees_alert']=='yes')
				{
					$a='yes';
				
				}
				echo $this->Form->input('', array('value'=>$it['fees_alert'],'class'=>'checkbox','name'=>'fees_alert','type' => 'select','multiple' => 'checkbox','options' => array('yes' => __('Enable')),'default'=>$a)); 
				?>
				</div>
				</div>
				
				<div class='form-group'>					
				<div class='control-label col-md-2'><?php echo $this->Form->label(__('Reminder Message')); ?></div>
				<div class='col-md-8'>
				<?php 
				echo $this->Form->textarea("",["name"=>"reminder_message","class"=>"form-control","value"=>$it['reminder_message']]);
				?>
				</div>			
				</div>
				
				<div class='form-group'>
				<div class='control-label col-md-2'><?php echo $this->Form->label(__('ShortCodes For Notification Mail Message'));?></div>
				<div class='col-md-8 checkbox'>
				<p><?php echo __('Parent Name -> PARENT_NAME'); ?><p>
				<p><?php echo __('Child Name -> CHILD_NAME'); ?><p>
				<p><?php echo __('Fees Type -> FEES_TYPE'); ?><p>
				<p><?php echo __('Due Amount -> DUE_AMOUNT'); ?><p>
				</div>
				</div>
				*/ ?>
				
				<div class="form-group">
				<div class="head">
					<hr>
					<h4 class="section"><?php echo __('Teacher Access Settings'); ?></h4>
				</div>
				</div>
				<div class="form-group">
							<div class="col-sm-4 label_float"><?php echo $this->Form->label(__('Teacher can manage all subject marks '));?></div>
							<div class="col-md-4 col-sm-4 col-xs-12">
							<?php
							if($it['teacher_msg_all_stud']=='yes')
								{
									$a='yes';
								
								}
								 echo $this->Form->input('', array('value'=>$it['teacher_msg_all_stud'],'class'=>'general_check','name'=>'teacher_msg_all_stud','type' => 'select','multiple' => 'checkbox','options' => array('yes' => __('Enable')),'default'=>$a)); ?>
							</div>
				</div> 
				<div class="form-group">
				<div class="head">
					<hr>
					<h4 class="section"><?php echo __('Copyright'); ?></h4>
				</div>
				</div>
				<div class="form-group">
							<div class="col-md-2 col-sm-2 col-xs-12 label_float"><?php echo $this->Form->label(__('Copyright'));?></div>
							<div class="col-md-8 col-sm-8 col-xs-12">			
								<?php echo $this->Form->input('',array('value'=>$it['copyright'],'name'=>'copyright','class'=>'form-control validate[maxSize[150]] text-input','PlaceHolder'=> __('Enter Copyright')));?>
							</div>
				</div>
				<div class="col-md-offset-2 col-sm-offset-2 col-md-8 col-sm-8 col-xs-12">
				<?php echo $this->Form->input(__('Save'),array('type'=>'submit','name'=>'save','class'=>'btn btn-success'));?>
					<!--<input type="submit" class="btn btn-success" name="save" value="Save">-->
				</div>
		<?php $this->Form->end(); ?>
	</div>
</div>
<style>
.no_of_digit .number label {
    display: none;
}
.radio input[type=radio]{
	margin-left: -15px;
}
</style>
