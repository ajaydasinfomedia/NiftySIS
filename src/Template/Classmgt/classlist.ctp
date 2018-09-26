<?php
use Cake\Routing\Router;
?>
<script>
	$(function(){

	/* $('.ch_pend').click(function() { */
	$('#mul').click(function() {
			/* if($(this).is(":checked")) { */
			if($(".ch_pend").is(":checked")) {	

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
					swal("Deleted!", "Your records has been deleted.", "success");		
		
					var get_id = $('.ch_pend:checked').map(function() {
					return this.attributes.dataid.textContent;
					}).get()
					get_id = JSON.stringify(get_id);
					data={c_id:get_id};	

					jQuery.ajax({
					type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Classmgt','action'=>'classmultidelete'));?>",
					data:data,
					async:false,
					success: function(response){
							location.reload();

					},
					error: function (e) {
					},
					beforeSend:function(){
					$(this).hide();
					},
					complete:function(e){
					console.log(e.responseText);
					
					}
					});	
				}
				else {	 
					swal("Cancelled", "Not removed!", "error"); 
				}
			});
		}
	});
});
</script>

<!--- checkbox js ---->
<script>
$(document).ready(function(){
    $('#select_all').on('click',function(){
        if(this.checked){
            $('.checkbox').each(function(){
                this.checked = true;
            });
        }else{
             $('.checkbox').each(function(){
                this.checked = false;
            });
        }
    });
    
    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length == $('.checkbox').length){
            $('#select_all').prop('checked',true);
        }else{
            $('#select_all').prop('checked',false);
        }
    });
});
</script>


<script>
		$(document).ready(function() {
		$('#classlist').DataTable({
				responsive: true,
				"order":[[0,"desc"]],
			});
		} );
</script>
<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">				  
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Class List'),['controller' => 'Classmgt', 'action' => 'classlist'],['escape' => false]);?>
		</li>
		<li>		
			<?php echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Class'),['controller' => 'Classmgt', 'action' => 'addclass'],['escape' => false]);?>
		</li>
		
	</ul>	
</div>
<?php
if(isset($it))
{
	if(!empty($it->toArray()))
	{
	?>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="classlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr><th><input name="select_all" value="all" id="select_all" type="checkbox" /></th> 
						<th><?php echo __('Class Name');?></th>
						<th><?php echo __('Class Numeric Name');?></th>
						<th><?php echo __('Capacity');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</thead>
				<tfoot>
					<tr><th></th>
						<th><?php echo __('Class Name');?></th>
						<th><?php echo __('Class Numeric Name');?></th>
						<th><?php echo __('Capacity');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</tfoot>
				<tbody>
					<tr>					
						<?php 
							foreach($it as $it2):
							{
								?>
								<td> 
								<p style='display:none;'><?php echo $it2['class_id'];?></p>
								<input type="checkbox" class="checkbox ch_pend" name="id[]" dataid="<?php echo $it2['class_id'];  ?>"> </td>
								<?php
								echo "<td>" . __($it2['class_name']) . "</td>";
								echo "<td>" . __($it2['class_num_name']) . "</td>";
								echo "<td>" . __($it2['class_capacity']) . "</td>";
								echo "<td>".$this->Html->link(__('Edit'),array('action' => 'updateclass', $this->Setting->my_simple_crypt($it2['class_id'],'e')),array('class'=>'btn btnview btn-info'))
								."&nbsp;".
								$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/delete/'.$it2['class_id'],'class'=>'btn btn-danger sa-warning'])
								."&nbsp;".
								$this->Html->link(__('View Or Add Section'),array('action' => '#'),array('class'=>'btn btnview btn-default viewmodaldata','id'=>'view_section','data-toggle'=>'modal','data-target'=>'#load_modal','data-id'=>$it2['class_id']))
								."</td>";
							}								
						?>
					</tr>
					<?php endforeach;
							?>
				</tbody>
				</table>
				<tr>
						<td><button type="button" class="btn btn-danger" id="mul"> <?php echo __('Delete Selected'); ?> </button></td>
					</tr>
		</div>
	</div>
</div>
<?php
	}
	else{
		?>
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Class Data Available');?></h4></div>
<?php		
	}
}
?>
<div class="modal modal-white in custom-model" id="load_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content"></div>
    </div>
</div>
<script>
jQuery('body').on('click', '.viewmodaldata', function() {		
	var model  = jQuery(this).attr('data-id') ;
	
	var curr_data = {class_id : model};	 				
	jQuery.ajax({
	        type:"POST",
	        url:"<?php echo Router::url(array('controller'=>'Classmgt','action'=>'viewsectionlist'));?>",
	        data:curr_data,
	        async:false,
	        success: function(response){
				jQuery('.modal-content').html(response);							
	        },
	        error: function(e) {
	                console.log(e);
	        }
	    });			
	}); 
	jQuery("body").on("click", ".remove-term", function(){
		
		var term_id  = jQuery(this).attr('data-id') ;	
		var model  = jQuery(this).attr('data-type') ;	
		
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
				swal("Deleted!", "Your record has been deleted.", "success");

				var curr_data = {					
					model : model,
					class_section_id:term_id,			
					dataType: 'json'
					};
					
					jQuery.ajax({
						type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Classmgt','action'=>'deleteterm'));?>",
						data:curr_data,
						async:false,
						success: function(response){ 								   
							jQuery('#term-'+term_id).hide();
							
						},
						
						error: function(e) {
								console.log(e);
								 }
					});
			}
			else {	 
				swal("Cancelled", "Not removed!", "error"); 
			}
		});	
	});	
	jQuery("body").on("click", ".edit-term", function(){
		var term_id  = jQuery(this).attr('data-id') ;	
		var model  = jQuery(this).attr('data-type') ;	
		 
		
			var curr_data = {					
					model : model,
					class_section_id:term_id,			
					dataType: 'json'
					};
					
					jQuery.ajax({
						type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Classmgt','action'=>'editterm'));?>",
						data:curr_data,
						async:false,
						success: function(response){ 								   
							jQuery('#term-'+term_id).html(response);
							
						},
						beforeSend:function(){
									jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
								},
						error: function(e) {
								console.log(e);
								 }
					});			
		
		
	});
	jQuery("body").on("click", ".btn-cat-update-cancel", function(){
		var term_id  = jQuery(this).attr('data-id') ;	
		var model  = jQuery(this).attr('data-type') ;	
		 
			
			var curr_data = {					
					model : model,
					class_section_id:term_id,			
					dataType: 'json'
					};
					
					jQuery.ajax({
						type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Classmgt','action'=>'cancelterm'));?>",
						data:curr_data,
						async:false,
						success: function(response){ 								   
							jQuery('#term-'+term_id).html(response);
							
						},
						error: function(e) {
								console.log(e);
								 }
					});			
		
		
	});	
	jQuery("body").on("click", ".btn-cat-update", function(){
		var term_id  = jQuery(this).attr('id') ;	
		var model  = jQuery(this).attr('data-type') ;
		var term_name  = jQuery("#section_name").val();	
		
			var curr_data = {					
					model : model,
					class_section_id:term_id,			
					term_name:term_name,			
					dataType: 'json'
					};
					
					jQuery.ajax({
						type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Classmgt','action'=>'saveterm'));?>",
						data:curr_data,
						async:false,
						success: function(response){ 								   
							jQuery('#term-'+term_id).html(response);
							
						},

						error: function(e) {
								console.log(e);
								 }
					});			
		
		
	});	
	jQuery("body").on("click", ".btn-add-cat", function(){
		
		var class_id  = jQuery('#class_id').val() ;
		var term_name  = jQuery("#txtfee_type").val();	

			var curr_data = {					
					class_id:class_id,			
					term_name:term_name,			
					dataType: 'json'
					};
					
					jQuery.ajax({
						type:"POST",
						url:"<?php echo Router::url(array('controller'=>'Classmgt','action'=>'addnewsection'));?>",
						data:curr_data,
						async:false,
						success: function(response){ 								   
							if(response != 'false')
							{
								jQuery("#class_section").append(response);
							}
							else
								alert("Enter Section Name");
							
							jQuery("#txtfee_type").val("");
						},
						
						error: function(e) {
								console.log(e.responseText);
								 }
					});			
		
		
	});	
	jQuery('body').on('click', '.addcriteriadata', function() {			
 				
	jQuery.ajax({
	        type:"POST",
	        url:"<?php echo Router::url(array('controller'=>'Ajaxfunction','action'=>'masteraddcriteria'));?>",
	      
	        async:false,
	        success: function(response){    
			               
				jQuery('.modal-content').html(response);
				
	        },
	        beforeSend:function(){
						jQuery('#modal-view').html('<center><img src=../images/4.gif width=120px><div><h3>Loading...</h3></div></center>');
					},
	        error: function(e) {
	                console.log(e);
	                 }
	    });			
	}); 
</script>