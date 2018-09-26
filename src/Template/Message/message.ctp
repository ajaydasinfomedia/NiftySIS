<div class="msg-title">
<div class="row mailbox-header">
        <div class="col-md-2 col-sm-2 col-xs-12">
            <a class="btn btn-success btn-block" href="/ajay/school_management/message/compose"><?php echo __('Compose');?></a>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <h2>
                <?php
					if(!isset($_REQUEST['inbox']))
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
		{?>
			class="active"
		<?php }?>>
		
			<a href="/ajay/school_management/message/inbox"><i class="fa fa-inbox"></i> <?php echo __('Inbox');?><span class="badge badge-success pull-right">0<?php /* echo count(get_inbox_message(get_current_user_id())); */?></span></a></li>
        
		<li <?php if(isset($_REQUEST['sentbox']))
		{?>class="active"
		<?php }?>>
			<a href="sentbox"><i class="fa fa-sign-out"></i><?php echo __('Sent');?></a></li>  
			
    </ul>
</div>
<div class="col-md-10 col-sm-10 col-xs-12">
 <?php  
 	if(isset($_REQUEST['sentbox']))
	?>
	
		<a href="/ajay/school_management/message/sendbox"></a>
<?php
 	if(!isset($_REQUEST['inbox']))
	?>
		<a href="/ajay/school_management/message/inbox"></a>
<?php
 	if(isset($_REQUEST['compose']))
	
	echo Router::url(['controller' => 'Message', 'action' => 'compose']);
	
 	?>	
<?php		
 	if(isset($_REQUEST['view_message']))
	?>
 		<a href="/ajay/school_management/message/view_message"></a>
<?php 	
 	?>
</div>
