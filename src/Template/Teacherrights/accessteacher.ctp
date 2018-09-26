<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">
		<li class="active">  
		<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Access Rights'),array('controller'=>'Teacherrights','action' => 'accessteacher'),array('escape' => false));?>
		</li>
		<li>
		<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Teacher Access Rights'),array('controller'=>'Teacherrights','action' => 'teacheraccessright'),array('escape' => false));?>
		</li>
	</ul>
</div>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal ','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['controller'=>'Teacherrights','action'=>'accessteacher']]);?>
			<table id="gradelist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th><?php echo __('Menu');?></th>
                        <th><?php echo __('Teacher');?></th>
                        <th><?php echo __('Student');?></th>
                        <th><?php echo __('Support Staff');?></th>
                        <th><?php echo __('Parent');?></th>						
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><?php echo __('Menu');?></th>
                        <th><?php echo __('Teacher');?></th>
                        <th><?php echo __('Student');?></th>
                        <th><?php echo __('Support Staff');?></th>
                        <th><?php echo __('Parent');?></th>
					</tr>
				</tfoot>
				<tbody>
                <?php
					foreach($teacher_menu as $teach_menu):
                ?>
					<tr>
						<td align=""><?php echo  __($teach_menu['menu_name']);?></td>
						<td>
							<input type="checkbox" value="" id="checkteacher_<?php echo $teach_menu['menu_id']?>" 	class="teacher" name="teacher_app" <?php if ($teach_menu ['teacher_approve'] == '1'){
							echo ' checked="checked" ';
							}else{
								echo '';
							}
							?>>
							<input type="hidden" name="teacher_approve[]" id="txtteacher_<?php echo $teach_menu['menu_id'];?>" value="<?php echo $teach_menu['teacher_approve']; ?>">
                        </td>
                        <td>
							<input type="checkbox" value="" id="checkstudent_<?php echo $teach_menu['menu_id']?>" class="check" name="student_app" <?php if($teach_menu['student_approve'] == '1'){
							echo ' checked="checked" ';
							}else{
								echo '';
							}
							?> >
							<input type="hidden" name="student_approve[]" id="txtstudent_<?php echo $teach_menu['menu_id'];?>" value="<?php echo $teach_menu['student_approve']; ?>"> 
                        </td>
                        <td>
							<input type="checkbox" value="" id="checkstaff_<?php echo $teach_menu['menu_id']?>" class="check" name="staff_app" <?php if($teach_menu['staff_approve'] == '1'){
								echo ' checked="checked" ';
							}else{
								echo '';
							}
                           ?> >
                           <input type="hidden" name="staff_approve[]" id="txtstaff_<?php echo $teach_menu['menu_id'];?>" value="<?php echo $teach_menu['staff_approve']; ?>"> 
                        </td>
                        <td>
							<input type="checkbox" value="" id="checkparent_<?php echo $teach_menu['menu_id']?>" class="check" name="parent_app" <?php if($teach_menu['parent_approve'] == '1'){
								echo ' checked="checked" ';
							}else{
								echo '';
							}
                           ?> >
                           <input type="hidden" name="parent_approve[]" id="txtparent_<?php echo $teach_menu['menu_id'];?>" value="<?php echo $teach_menu['parent_approve']; ?>"> 
                        </td>                        
						<script>
							$(function(){						   
								$("#checkteacher_<?php echo $teach_menu['menu_id']?>").click(function(){
								   if($(this).is(":checked")){
									   $("#txtteacher_<?php echo $teach_menu['menu_id']?>").attr('value','1');  
								   }else{
										$("#txtteacher_<?php echo $teach_menu['menu_id']?>").attr('value','0');
								   }									
								});
								$("#checkstudent_<?php echo $teach_menu['menu_id']?>").click(function(){
								   if($(this).is(":checked")){
									   $("#txtstudent_<?php echo $teach_menu['menu_id']?>").attr('value','1');
									}else{
										$("#txtstudent_<?php echo $teach_menu['menu_id']?>").attr('value','0');
								   }
								});
								$("#checkstaff_<?php echo $teach_menu['menu_id']?>").click(function(){
								   if($(this).is(":checked")){
									   $("#txtstaff_<?php echo $teach_menu['menu_id']?>").attr('value','1');	   
								   }else{
										$("#txtstaff_<?php echo $teach_menu['menu_id']?>").attr('value','0');
								   }
								});
								$("#checkparent_<?php echo $teach_menu['menu_id']?>").click(function(){
								   if($(this).is(":checked")){
									   $("#txtparent_<?php echo $teach_menu['menu_id']?>").attr('value','1');
									}else{
										$("#txtparent_<?php echo $teach_menu['menu_id']?>").attr('value','0');
								   }																	
								});								
							});
						</script>                           
					</tr>               
                    <?php
                    endforeach;
                    ?>       					
				</tbody>
			</table>
		<center>       
			<?php echo $this->Form->input(__('Save Rights'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
        </center>
		<?php $this->Form->end(); ?>       
		</div>
	</div>
</div>
