<?php
use Cake\Routing\Router;
$user = $this->request->session()->read('user_id');
$role = $this->Setting->get_user_role($user);
$stud_date = $this->Setting->getfieldname('date_format');
?>	
<div class="row">
	<ul role="tablist" class="nav nav-tabs panel_tabs">	
		<li class="">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-list-ul fa-lg')) . __('Admission List'),['controller' => 'Admission', 'action' => 'admissionlist'],['escape' => false]);?>
		</li>
		<li class="active">		
			<?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-plus fa-lg')) . __('Admission Form'),['controller' => 'Admission', 'action' => 'registration'],['escape' => false]);?>
		</li>
	</ul>	
</div>
<style>
.table > tbody > tr > th{
	background-color: #999999;
	border: 1px solid #000000;
	color: #FFFFFF;
}
.table > tbody > tr > td{
	background-color: #e5e5e5;
	border: 1px solid #000000;
	color: #000000;
}
</style>
<div class="panel-body">	
	<div class="table-responsive" style="padding-top: 0px;">
		<div id="example_wrapper" class="dataTables_wrapper">
			<h4><i class="fa fa-user"></i><?php echo "&nbsp;".__('Personal Info');?></h4>
			<table class="table table-bordered" cellspacing="0" width="100%" style="border:1px solid #000000;">
				<thead></thead>	
				<tfoot></tfoot>	
				<tbody>
					<tr>
						<th colspan="2"><?php echo __('Admission No. '); ?></th>
						<td colspan="2"><?php echo $data->admission_no; ?></td>
						<th colspan="2"><?php echo __('Admission Date '); ?> </th>
						<td colspan="2"><?php echo date($stud_date,strtotime($data->admission_date)); ?></td>
					</tr>
					<tr>
						<th><?php echo __('Name '); ?> </th>
						<td><?php echo $data->first_name." ".$data->middle_name." ".$data->last_name; ?></td>
						<th><?php echo __('Date of Birth '); ?> </th>
						<td><?php echo date($stud_date,strtotime($data->date_of_birth)); ?></td>
						<th><?php echo __('Gender '); ?></th>
						<td><?php echo $data->gender; ?></td>
						<th><?php echo __('Address '); ?></th>
						<td><?php 
						if(!empty($data->state))
							$state = $data->state.", </br>";
						else
							$state = '';
						echo $data->address." , </br>".$data->city." , </br>".$state.$data->zip_code; ?></td>
					</tr>
					<tr>
						<th><?php echo __('Mobile No. '); ?></th>
						<td><?php echo $data->mobile_no; ?></td>
						<th><?php echo __('Phone No. '); ?></th>
						<td><?php echo $data->phone; ?></td>
						<th><?php echo __('Email '); ?> </th>
						<td><?php echo $data->email; ?></td>
						<th><?php echo __('Previous School '); ?></th>
						<td><?php echo $this->Setting->get_admission_value($data->preschoolname); ?></td>	
					</tr>
				</tbody>
			</table>
			<?php
			$aj = 0;
			if($data->siblingsone != 'NULL' || $data->siblingstwo != 'NULL' || $data->siblith != 'NULL')
				echo $aj = 1;
			
			if($aj == 1)
			{
			?>
			<h4><i class="fa fa-user"></i><?php echo "&nbsp;".__('Siblings Info');?></h4>
			<table class="table table-bordered" cellspacing="0" width="100%" style="border:1px solid #000000;">
				<thead></thead>	
				<tfoot></tfoot>	
				<tbody>
					<?php
					if($data->siblingsone != 'NULL')
					{
						$siblingsone = array();
						$siblingsone = explode(',',$data->siblingsone);
					?>
					<tr>
						<th colspan="10"><?php echo __('Siblings 1'); ?></th>					
					</tr>
					<tr>
						<th><?php echo __('Relation '); ?></th>
						<td><?php echo $siblingsone[0]; ?></td>
						<th><?php echo __('Name '); ?></th>
						<td><?php echo $siblingsone[1]; ?></td>
						<th><?php echo __('Age '); ?> </th>
						<td><?php echo $siblingsone[2]; ?></td>
						<th><?php echo __('Standard '); ?> </th>
						<td><?php echo $siblingsone[3]; ?></td>
						<th><?php echo __('SID '); ?> </th>
						<td><?php echo $siblingsone[4]; ?></td>
					</tr>
					<?php
					}
					if($data->siblingstwo != 'NULL')
					{
						$siblingstwo = array();
						$siblingstwo = explode(',',$data->siblingstwo);
					?>
					<tr>
						<th colspan="10"><?php echo __('Siblings 3'); ?></th>					
					</tr>
					<tr>
						<th><?php echo __('Relation '); ?></th>
						<td><?php echo $siblingstwo[0]; ?></td>
						<th><?php echo __('Name '); ?></th>
						<td><?php echo $siblingstwo[1]; ?></td>
						<th><?php echo __('Age '); ?> </th>
						<td><?php echo $siblingstwo[2]; ?></td>
						<th><?php echo __('Standard '); ?> </th>
						<td><?php echo $siblingstwo[3]; ?></td>
						<th><?php echo __('SID '); ?> </th>
						<td><?php echo $siblingstwo[4]; ?></td>
					</tr>
					<?php
					}
					if($data->siblith != 'NULL')
					{
						$siblith = array();
						$siblith = explode(',',$data->siblith);
					?>
					<tr>
						<th colspan="10"><?php echo __('Siblings 3'); ?></th>					
					</tr>
					<tr>
						<th><?php echo __('Relation '); ?></th>
						<td><?php echo $siblith[0]; ?></td>
						<th><?php echo __('Name '); ?></th>
						<td><?php echo $siblith[1]; ?></td>
						<th><?php echo __('Age '); ?> </th>
						<td><?php echo $siblith[2]; ?></td>
						<th><?php echo __('Standard '); ?> </th>
						<td><?php echo $siblith[3]; ?></td>
						<th><?php echo __('SID '); ?> </th>
						<td><?php echo $siblith[4]; ?></td>
					</tr>
					<?php
					}
					?>
				</tbody>
			</table>
			<?php
			}
			?>
		</div>
	</div>
</div>