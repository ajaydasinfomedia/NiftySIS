<script>
$(document).ready(function() {
	$('#classlist').DataTable({
		responsive: true,
	});
});
</script>
<div class="msg-title">
<div class="row mailbox-header">
        <div class="col-md-2 col-sm-2 col-xs-12">
            <?php  echo $this->Html->link(__('Compose'),['controller' => 'Message', 'action' => 'compose'],['class'=>'btn btn-success btn-block']);?>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h2>
                <?php
					if(isset($_REQUEST['inbox']))
                        echo __( 'Inbox');
					else if(!isset($_REQUEST['sentbox']))
						echo __( 'Sent Item');
					else if(isset($_REQUEST['compose']))
						echo __( 'Compose');
				?>
								
                                    
            </h2>
        </div>
                               
</div>
</div>
<div class="col-md-2 col-sm-2 col-xs-12 msg-div">
    <ul class="list-unstyled mailbox-nav">
        <li <?php if(isset($_REQUEST['inbox']))
		{?>
			class=""
		<?php }?>>
		
		<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-inbox')) . __('Inbox'),['controller' => 'Message', 'action' => 'inbox'],['escape' => false]);?><span class="badge badge-success pull-right"><?php echo $p;?></span></li>
		        
		<li <?php if(!isset($_REQUEST['sentbox']))
		{?>
		class="active"
		<?php }?>>
		
		<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-sign-out')) . __('Sent'),['controller' => 'Message', 'action' => 'sentbox'],['escape' => false]);?></li>
						
    </ul>
</div>
<div class="col-md-10 col-sm-10 col-xs-12">
<div class="row">
	<div class="mailbox-content" style="overflow-x: scroll;">
		<table class="table table-striped" cellspacing="0" width="100%" id="classlist">
			<thead>
				<tr>
					<th><?php echo __('Message For');?></th>
					<th><?php echo __('Subject');?></th>
					<th><?php echo __('Description');?></th>
					<th><?php echo __('Date');?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			$a=0;
			if(isset($inboxdata)){
			foreach($inboxdata as $msg)
			{
								
				?>
				<tr>
				
				<td>
						
						<?php 
							echo $msg['user_name'];
						?>
					
				</td>
				<td style="position:relative;">
					 <a href="view_inbox_message/<?php echo $this->Setting->my_simple_crypt($msg['id'],'e');?>"><span class="badge badge-success pull-right" style="top:8px;"><?php if(isset($reply_count)){ foreach($reply_count as $countdata){ if($countdata['mid'] == $msg['id']){ echo $countdata['count']; break;}}}?></span>
					 <?php 
					 
					
						echo $msg['msg_sub'];
				
		
					 ?>
						
							</a>
				</td>
				<td><?php echo $msg['msg_des'];?>
				</td>
				<td>
					<?php  
					$stud_date = $this->Setting->getfieldname('date_format');
					echo date($stud_date, strtotime($msg['date']));?>
				</td>
				</tr>
				<?php 
				$a=$a+1;
			}}
			?>
			
			</tbody>
		</table>
	 </div>
</div>
</div>