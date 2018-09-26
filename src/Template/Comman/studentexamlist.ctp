<?php
use Cake\Routing\Router;
?>
<script>
$(document).ready(function() {
	$('#studentexamlist').DataTable({
		responsive: true,
	});
});
</script>
<div class="row schooltitle">			
	<ul role="tablist" class="nav nav-tabs panel_tabs">
		<li class="active">
			<?php  echo $this->Html->link($this->Html->tag('i', '', array('class' => 'fa fa-list fa-lg')) . __('Exam Receipt List'),['controller'=>'Comman','action' => 'studentexamlist'],['escape' => false]);?>
		</li>
	</ul>
</div>
<?php
if(isset($exam_data))
{
?>
<div class="panel-body">	
	<div class="table-responsive" style="padding-top: 0px;">
		<div id="example_wrapper" class="dataTables_wrapper">
			<table id="studentexamlist" class="table table-striped" cellspacing="0" width="100%">
				<thead>
					<tr>				
						<th><?php echo __('Exam Name');?></th>
						<th><?php echo __('Action');?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>					
						<th><?php echo __('Exam Name');?></th>
						<th><?php echo __('Action');?></th>
					</tr>															
				</tfoot>
				<tbody>
					<?php
					foreach($exam_data as $exam_data)
					{
						$receipt_id = $this->Setting->student_exam_receipt($user_id, $exam_data['exam_id']);
						
						echo "<tr>";
						echo "<td>".$this->Setting->get_exam_data($exam_data['exam_id'],'exam_name')."</td>";
						echo "<td>";
							if($receipt_id)
							{
								echo $this->Html->link(__('Print'),array('action' => 'studentreceipt', $this->Setting->my_simple_crypt($receipt_id,'e')),array('target'=>'_blank','class'=>' btn btnview btn-success'))." ";
								echo $this->Html->link(__('PDF'),array('action' => 'studentreceiptpdf', $this->Setting->my_simple_crypt($receipt_id,'e')),array('target'=>'_blank','id'=>'cmd','class'=>' btn btnview btn-success'))." ";
							}
						echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>					
			</table>
		</div>
	</div>
</div>
<?php
}
else{
		?>
		<div id='main-wrapper'><h4 class='text-danger'><?php echo __('No Exam Data Available');?></h4></div>
<?php		
	}
?>

<script>
$('#cmd').click(function() {
  var options = {
  };
  var pdf = new jsPDF('p', 'pt', 'a4');
  pdf.addHTML($("#content"), 15, 15, options, function() {
    pdf.save('pageContent.pdf');
  });
});
</script>