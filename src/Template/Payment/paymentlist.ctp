<?php
use Cake\Routing\Router;
?>
<!--- start checkbox js ---->
<script>
	$(function(){

	/* $('.ch_pend').click(function() { */
	$('#pay').click(function() {
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
					data={p_id:get_id	};	


					jQuery.ajax({
					type:"POST",
					url:"<?php echo Router::url(array('controller'=>'Payment','action'=>'paymentmultidelete'));?>",
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
<!--- end checkbox js ---->



<script>
		$(document).ready(function() {
		$('#examlist').DataTable({
				responsive: true,
				"order":[[0,"desc"]],
			});

		jQuery('body').on('click', '.viewpayment', function() {	
			var pay_id=$(this).attr('id');
		
		
				$.ajax({

					type:'POST',
					url:'<?php echo $this->Url->build(["controller"=>"Payment","action"=>"viewdatapayment"]); ?>',
					data:{id:pay_id},

					success:function(getdata){
						$(".modal-body").html(getdata);
					},

					beforeSend:function(){
						$(".modal-body").html("<center><h2 class=text-muted><b>Loading...</b></h2></center>");
					},

					error:function(e){
						alert("An error ocurred:"+e.responseText);
						console.log(e);
					},

			});
		
		
		});
		
		} );
		
		
	</script>
	
	
	
	<script>
	
		  function PrintElem(elem)
			{
					Popup($(elem).html());
			}

    function Popup(data) 
    {
        var mywindow = window.open('', 'Print Expense Invoice', 'height=600,width=800');
        mywindow.document.write('<html><head><title>my div</title>');
        /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10

        mywindow.print();
        mywindow.close();

        return true;
    }


	</script>


	<?php $heading = $this->Setting->getfieldname('school_name');?>
	<div class="modal fade " id="myModal1" role="dialog">
    <div class="modal-dialog modal-lg"  >

      <div class="modal-content">
        <div class="modal-header" >
          <a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
			<h4><?php echo $heading; ?></h4>
        </div>
        <div class="modal-body" >
		
        </div>
		
        <div class="modal-footer">
		 
          
		
		</div>
			 
        </div>
      </div>
      
    </div>




				<!-- -->





<div class="row schooltitle">			
				<ul role="tablist" class="nav nav-tabs panel_tabs">
					  <li class="active"><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Payment List'),array('controller'=>'Payment','action' => 'paymentlist'),array('escape' => false));?></li>

					  <li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Payment'),array('controller'=>'Payment','action' => 'addpayment'),array('escape' => false));?></li>
					  
					   <li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) .  __('Income List'),array('controller'=>'Payment','action' => 'incomelist'),array('escape' => false));?></li>
					   
					   <li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Income'),array('controller'=>'Payment','action' => 'addincome'),array('escape' => false));?></li>
					  
					  <li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Expense List'),array('controller'=>'Payment','action' => 'expenselist'),array('escape' => false));?></li>
	
					   <li class=""><?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-plus-circle fa-lg')) . __('Add Expense'),array('controller'=>'Payment','action' => 'addexpense'),array('escape' => false));?></li>
				</ul>
</div>
<?php
if(isset($payment))
{
	if(!empty($payment->toArray()))
	{
	?>
<div class="panel-body">	
	<div class="table-responsive">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr><th><input type="checkbox" id="select_all" name="select_all" /></th>
						<th><?php echo __('Student Name'); ?></th>
						<th><?php echo __('Student ID');?></th>
						<th><?php echo __('Class'); ?></th>
						<th><?php echo __('Title'); ?></th>
						<th><?php echo __('Amount'); ?></th>
						<th><?php echo __('Status'); ?></th>
						<th><?php echo __('Date'); ?></th>
						<th><?php echo __('Action'); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr><th></th>
						<th><?php echo __('Student Name'); ?></th>
						<th><?php echo __('Student ID');?></th>
						<th><?php echo __('Class'); ?></th>
						<th><?php echo __('Title'); ?></th>
						<th><?php echo __('Amount'); ?></th>
						<th><?php echo __('Status'); ?></th>
						<th><?php echo __('Date'); ?></th>
						<th><?php echo __('Action'); ?></th>
					</tr>
				</tfoot>
				<tbody>

			<?php
			$currency = $this->Setting->getfieldname('currency_code');
			$currency_symbol = $this->Setting->get_currency_symbole($currency);
		
			foreach($payment as $payment_data):
					{
						foreach($user as $userdata):
						{
							if($userdata['user_id'] == $payment_data['user_id'])
							{
								$stud_date = $this->Setting->getfieldname('date_format');
			?>
				
					<tr>
					
					<td>
					<p style='display:none;'><?php echo $payment_data['payment_id'];?></p>
					<input type="checkbox" class="checkbox ch_pend" name="id[]" dataid="<?php echo $payment_data['payment_id'];  ?>"> </td>
					<td><?php echo $userdata['first_name'].'  '.$userdata['last_name'];?></td>
					<Td><?php echo $this->Setting->get_studentID($userdata['user_id']); ?></td>
					<td><?php echo $payment_data['c_name']; ?></td>
					<Td><?php echo $payment_data['payment_title'];?></td>
					<td><?php echo $currency_symbol." ".$payment_data['amount']; ?></td>
					<td><?php echo $payment_data['payment_status']; ?></td>
					<td><?php echo date($stud_date,strtotime($payment_data['date'])); ?></td>
					<td>
						<button type="button" id="<?php echo $payment_data['payment_id']; ?>" data-toggle="modal" data-target="#myModal1" class="btn btn-default viewpayment"><span class="fa fa-eye fa-lg"></span> <?php echo __(' View Payment '); ?></button>

							<?php 
							echo $this->Html->link (__('Edit'),array('controller'=>'Payment','action' => 'addpayment',$this->Setting->my_simple_crypt($payment_data['payment_id'],'e')),array('class'=>'btn btnview btn-info'))
							."&nbsp;".
							$this->Form->button( __('Delete'),['action'=>'#','url'=>$this->request->base.'/'.$this->name.'/delete/'.$payment_data['payment_id'],'class'=>'btn btn-danger sa-warning']);
							?>
					</td>
				</tr>
				<?php
						}
						
						}endforeach;
					
					}endforeach;
					?>
				
				</tbody>
				</table>
				<tr>
					<td><button type="button" class="btn btn-danger" id="pay"><?php echo __('Delete Selected'); ?> </button></td>
				</tr>
		</div>
	</div>
</div>
<?php
	}
	else{
		?>
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Payment Data Available');?></h4></div>
<?php		
	}
}
?>