 
<div class="row schooltitle">			
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					  <li>
					  
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . 'Access Rights',array('controller'=>'Teacherrights','action' => 'accessteacher'),array('escape' => false));
						?>
						
						<!--	 <i class="fa fa-align-justify"></i>Add Student</a> -->
						  
					  </li>
					  <li class="active">
					<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . 'Teacher Access Rights',array('controller'=>'Teacherrights','action' => 'teacheraccessright'),array('escape' => false));
						?>
					  </li>
				
				</ul>
</div>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<?php echo $this->Form->Create('form1',['id'=>'formID','class'=>'form_horizontal ','method'=>'post','enctype'=>'multipart/form-data'],['url'=>['controller'=>'Teacherrights','action'=>'accessteacher']]);?>
<div class="panel-body">
				<form name="student_form" action="" method="post" class="form-horizontal" id="access_right_form">
					<div class="row">
						<div class="col-md-2 col-sm-2 col-xs-12"><?php echo __('View Subjects'); ?></div>
						<div class="col-md-4 col-sm-6 col-xs-12">
							<?php $chksub = isset($teacher_access[0]['chksub'])?$teacher_access[0]['chksub']:'';?>
							<td align="left" valign="middle">
								<input name="chksub" type="radio" id="signi" value="all_sub" <?php echo ($chksub == 'all_sub') ?  "checked" : "" ;  ?>/> <?php echo __('All Subjects'); ?><br>
								<input name="chksub" type="radio" id="signi" value="own_cls_sub" <?php echo ($chksub == 'own_cls_sub') ?  "checked" : "" ;  ?>/> <?php echo __('Only Own Class Subjects'); ?><br>
								<input name="chksub" type="radio" id="signi" value="own_sub" <?php echo ($chksub == 'own_sub') ?  "checked" : "" ;  ?>/> <?php echo __('Only Own Subjects');?>
							</td>
						</div>
					</div><br><span style="float:left;width:100%;border-bottom:1px solid hsl(0, 0%, 87%);"></span><br>
					<div class="row">
						<div class="col-md-2 col-sm-2 col-xs-12"><?php echo __('View Students'); ?></div>
						<div class="col-md-4 col-sm-6 col-xs-12">
							<?php $chkstud = isset($teacher_access[0]['chkstud'])?$teacher_access[0]['chkstud']:'';?>
							<td align="left" valign="middle">
								<input name="chkstud" type="radio" id="signi" value="all_stud" <?php echo ($chkstud== 'all_stud') ?  "checked" : "" ;  ?>/> <?php echo __('All Students'); ?><br>
								<input name="chkstud" type="radio" id="signi" value="own_cls_stud" <?php echo ($chkstud== 'own_cls_stud') ?  "checked" : "" ;  ?>/> <?php echo __('Only Own Class Students'); ?>
							</td>
						</div>
					</div><br><span style="float:left;width:100%;border-bottom:1px solid hsl(0, 0%, 87%);"></span><br>
					<div class="row">
						<div class="col-md-2 col-sm-2 col-xs-12"><?php echo __('Students Attendance'); ?></div>
						<div class="col-md-4 col-sm-6 col-xs-12">
							<?php $chkatted = isset($teacher_access[0]['chkatted'])?$teacher_access[0]['chkatted']:'';?>
							<td align="left" valign="middle">
								<input name="chkatted" type="radio" id="signi" value="all_stud_attend" <?php echo ($chkatted== 'all_stud_attend') ?  "checked" : "" ;  ?>/> <?php echo __('All Students Attendance'); ?><br>
								<input name="chkatted" type="radio" id="signi" value="own_sub_cls_attend" <?php echo ($chkatted== 'own_sub_cls_attend') ?  "checked" : "" ;  ?>/> <?php echo __('Only Own Class Students Attendance'); ?>
							</td>
						</div>
					</div><br><span style="float:left;width:100%;border-bottom:1px solid hsl(0, 0%, 87%);"></span><br>
					<div class="col-sm-8 row_bottom">
						<?php echo $this->Form->input(__('Save Rights'),array('type'=>'submit','name'=>'add','class'=>'btn btn-success'));?>
					</div>
		
				</form>
				</div>
			<?php $this->Form->end(); ?>
		</div>
	</div>
</div>
