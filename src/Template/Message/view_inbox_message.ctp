<script>
$(document).ready(function(){
 
	$("body").on("click", ".del", function(){

		var id = $(this).attr('id');
		
		swal({   
			title: "Are You Sure?",
			text: "Are you sure you want to delete this?",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "#297FCA",   
			confirmButtonText: "Yes, delete!",
			cancelButtonText: "No, cancel it!",	
			closeOnConfirm: false,
			closeOnCancel: false
		}, function(isConfirm){
			if (isConfirm)
			{
				swal("Deleted!", "Message has been deleted.", "success");
				
				$.ajax({
					type: 'POST',
					url: '<?php echo $this->Url->build([
									"controller" => "Message",
									"action" => "deleteReply"]);?>',
					data : {department:id},
					success: function (data)
					{
						$('body .del-'+id).hide();
						location.reload();
					}
			   });
			}
			else {	 
				swal("Cancelled", "Not removed!", "error"); 
			}
		});
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
					else if(isset($_REQUEST['sentbox']))
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
        <li <?php if(!isset($_REQUEST['inbox']))
		{ ?>
			class=""
		<?php }?>>
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-inbox')) . __('Inbox'),['controller' => 'Message', 'action' => 'inbox'],['escape' => false]);?><span class="badge badge-success pull-right"></span></li>
        
		<li <?php if(isset($_REQUEST['sentbox']))
		{?>class="active"
		<?php }?>>
		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-sign-out')) . __('Sent'),['controller' => 'Message', 'action' => 'sentbox'],['escape' => false]);?></li>
			
    </ul>
</div>
<div class="col-md-10 col-sm-10 col-xs-12">
<?php
if(isset($edit))
{
?>
<div class="row">
	<div class="mailbox-content">
 	<div class="message-header">
		<h3><span><?php echo __('Subject')?> :</span><?php echo $message_sub;?></h3>
		<p class="message-date"><?php ?></p>
	</div>
	<div class="message-sender">    
	<p><?php echo __('From:'); ?>  <?php echo $sender_name; ?><span> &lt<?php echo $sender_email; ?> <?php echo __('&gt'); ?> </span>
	<br><?php echo __(' To:'); ?> <?php if(isset($receiver_name)){echo $receiver_name;?><span> &lt<?php echo $receiver_email;?><?php echo __('&gt'); ?> </span><?php }elseif(isset($receiver_nm)){echo $receiver_nm;}?>
	</p>
	</div>
    <div class="message-content">
	<p><?php echo $message_content;?></p>
	<div class="message-options pull-right">
	<?php 
	echo 
    $this->Form->button($this->Html->tag('i', '  ', array('class' => 'fa fa-trash fa-lg')).__('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/delete/'.$id,'class'=>'btn btn-danger sa-warning','escape' => false]);
	?>
   </div>
   </div>
   	 <?php
		$stud_date = $this->Setting->getfieldname('date_format');
		foreach($msg_rply as $reply)
		{?>
			<div class="message-content">
			<div class="del-<?php echo $reply['id'];?>">
			<p><?php echo $reply->message_comment;?><br><h5> <?php echo __('Reply By:'); ?> <?php  foreach ($userdata as $username){ if($reply['sender_id']== $username['user_id']){ echo $username['f_name']; } }?>
			<span class="comment-delete">
				<?php if($current_user_id == $reply['sender_id'])
				{?>
				<a class="del" href="#" url="<?php echo $this->request->base.'/'.$this->name.'/delete/'.$id;?>" class="sa-warning" id=<?php echo  $reply['id'];?>><?php echo __('Delete'); ?> </a>
		<!--		<?php echo $this->Html->link(__('Delete'),array('id'=>$reply['id']),array('class'=>'del'),array('confirm' => __('Are you sure you wish to delete this Record?')));
				?> --><?php }?>
				<span><?php echo date($stud_date, strtotime($reply->created_date));?></span>
				
				</h5> 
				</p>
				</div>
			</div>
		<?php }
   ?>
	 
	 <form name="message-replay" method="post" id="message-replay">
   <input type="hidden" name="message_id">
   <input type="hidden" name="user_id">
   <input type="hidden" name="receiver_id">
    <div class="message-content">
     <div class="col-md-8 col-sm-8 col-xs-12">
        <textarea name="replay_message_body" id="replay_message_body" class="form-control text-input"></textarea>
		
	   </div>
	   <div class="message-options pull-right reply-message-btn">
			<button type="submit" name="replay_message" class="btn btn-default"><i class="fa fa-reply m-r-xs"></i><?php echo __('Reply')?></button>
		
	   </div>
    </div>
	</form>
 </div>
 </div>
<?php 
}
	else{
		?>
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Data Available');?></h4></div>
<?php		
	}
?>
</div>