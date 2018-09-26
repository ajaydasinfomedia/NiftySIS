<style>
.panel{
	margin-bottom:10px!important;
}
.panel-body.emailtemplate textarea.k-textbox,
.panel-body.emailtemplate input.k-textbox {
	width: 100%!important;
	float: left;
	background: #fff;
	border-radius: 0;
	border: 1px solid #dce1e4;
	box-shadow: none!important;
	font-size: 13px;
	padding: 6px 10px!important;
	-webkit-transition: all .2s ease-in-out;
	-moz-transition: all .2s ease-in-out;
	-o-transition: all .2s ease-in-out;
	transition: all .2s ease-in-out;
}
.panel-body.emailtemplate .k-header.k-grid-toolbar {
	padding: 20px;
	overflow: hidden;
	border-top-left-radius: 0;
	border-top-right-radius: 0;
	border: 0!important;
	height: 55px;
	font-size: 14px;
	font-weight: 600;
}
.panel-body.emailtemplate a.k-button.k-button-icontext{	
	text-decoration: none;
	color: #000;
	}
</style>
<div class="panel-body emailtemplate">
	<div class="k-grid k-widget k-display-block">
		<div class="k-header k-grid-toolbar">
			<a role="button" class="k-button k-button-icontext" href="<?php echo $this->Url->build(['controller'=>'Emailtemplate','action'=>'index']);?>"><span class="k-icon k-i-email"></span><?= __('Email Templates')?></a>
		</div>
	</div>
    <div class="panel-group" style="padding-top:20px;" id="accordion">
	<?php  foreach($t as $k=>$v){ ?>
    <div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
			  <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $k?>" class="collapsed"><?= __($v['name'])?></a>
		    </h4>
		</div>
        <div id="collapse<?= $k?>" class="panel-collapse collapse">
        <div class="panel-body">
		<?php echo $this->Form->Create('',['name'=>'temp','id'=>'formID'.$k,'class'=>'form-horizontal','method'=>'post']);?>
			<div class="form-group">
			    <div class="col-sm-2 label_right"><?php echo $this->Form->label(__('Email Subject:'));?><span class="require-field">*</span></div>
				<div class="col-md-8 col-sm-8 col-xs-12">
					<?php echo $this->Form->input('',array('required'=>'required','validationMessage'=>__('Enter Email Subject'),'name'=>'subject','value'=>__($v['subject']),'class'=>'k-textbox'));?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 label_right"><?php echo $this->Form->label(__('Email Template:'));?><span class="require-field">*</span></div>
				<div class="col-md-8 col-sm-8 col-xs-12">
				<?php	echo $this->Form->textarea('',array('name'=>'template','rows'=>'12','value'=>__($v['template']),'class'=>'k-textbox'));?>
				<?php	echo $this->Form->input('',array('name'=>'id','value'=>$v['id'],'type'=>'hidden'));?>
			    </div>
			</div>
			<div class="form-group">
    			<div class="col-sm-offset-2 col-md-8">
				<label><?php echo __('You can use following variables in the email template');?></label><br>
				<?php 
				// var_dump($v['keywords']);
				 $keys=unserialize($v['keywords']);
				 foreach($keys as $y=>$z){
				?>
				<label><strong><?= $y?> </strong><?php echo __($z);?></label><br>
				<?php  } ?>
			    </div>
		    </div>
			<div class="form-group">
				<div class="col-md-2 col-sm-2 col-xs-12"><?php echo $this->Form->label('');?></div>
				<div class="col-md-offset-2 col-sm-offset-2 col-xs-offset-0 col-md-10 col-sm-10 col-xs-12">
			    <?php echo $this->Form->input(__('Save'),array('type'=>'submit','name'=>'add','class'=>'btn k-primary','id'=>'primaryTextButton'));?>
				<?php echo $this->Form->end();?>
			    </div>
			</div>
		</div>
      </div>
    </div>

<?php } ?>
	</div>  
</div>