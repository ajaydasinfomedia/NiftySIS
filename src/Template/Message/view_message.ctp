<script>

$( document ).ready(function(){
 $("body").on("click", ".del", function(){
   

		var id = $(this).attr('id');
	
		$.ajax({
       type: 'POST',
       url: '<?php echo $this->Url->build([
"controller" => "Message",
"action" => "deleteReply"]);?>',
       data : {department:id},
	   success: function (data)
	   {
	$('body .del-'+id).hide();
	alert(data);
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
<div class="row">
	<div class="mailbox-content">
 	<div class="message-header">
		<h3><span><?php echo __('Subject')?> :</span><?php echo $message->subject;?></h3>
		<p class="message-date"><?php echo $message->date;?></p>
	</div>
	<div class="message-sender">    
	<p><?php echo __('From:'); ?> <?php echo $sender_name; ?><span> &lt<?php echo $sender_email;?> <?php echo __('&gt'); ?> </span>
	<br> <?php echo __(' To:'); ?> <?php if(isset($receiver_name)){echo $receiver_name;}?>
	</p>
	</div>
    <div class="message-content">
	<p><?php echo $message_content;?></p>
	<div class="message-options pull-right">
	<?php echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-trash fa-lg')).__('Delete'),array('action' => 'msgdeleted', $message['message_id']),array('class'=>'btn btn-danger','escape' => false),array('confirm' => __('Are you sure you wish to delete this Record?')));
    	?>
   </div>
   </div>
   	
	 
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
<?php ?>