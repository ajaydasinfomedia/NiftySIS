<style>
hr{
	border-color: #97C4E7;
}
.mainarea{
	float: left;
	width: 100%;	
}
.movetop{
	float: left;
	width: 95%;
	top: 25px;
	margin-left: 0px;
	position: absolute;
	min-height: 90px;
}
.mainlogo,
.schoolname
{
	float: left;
	width: 130px;
	text-align: left;
	padding-bottom: 10px;			
}
.schoolname{
	width: 50%;
	padding-left: 20px;
}
.schoolname h4{margin-top: 0px;}
.mainlogo img
{
	float: none;
	margin: 0px auto;
}
.schoolname span{
	font-size: 22px;
}
.leftdiv{
	float: left;
	width: 280px;
	margin-top: 25px;
}
.rightdiv{
	float: left;
	width: 70%;
	padding-left: 15px;
	margin-top: 10px;
}
.lastdiv{
	float: right;
	width: 150px;
	margin-top: 25px;
}
.leftdiv h4,
.rightdiv h4,
.lastdiv h4
{
	float: left;
	width: 100%;
	font-size:16px;
	margin-bottom: 8px;
}
.leftdiv b,
.rightdiv b,
.lastdiv b
{
	color: #4E5E6A;
}
.leftdiv .textcr,
.rightdiv .textcr,
.lastdiv .textcr
{
	float: left;
	width: 100%;
    color: #65696d;
	text-align: left;
}
.rightdiv .textcr{
	width: 100%;
}
.lastdiv .textcr b
{
	text-align: right;
	font-size: 11px;
}
.lastdiv .textcr.first,
.lastdiv .textcr.first b
{
	text-align: center;
	background-color: #03A9F4;
	color: #FFFFFF;
	text-transform: uppercase;
    font-size: 15px;
    padding: 0px;
	text-align: center;
}
.lastdiv .textcr
{
	font-size: 12px;
	padding: 5px 0px 0px;
	text-align: center;
}
.billtoimg{
	width: 26%;
    float: left;
}
.billtocontent{
	float: left;
    width: 70%;
}
.markstable{
	float: left;
	width: 100%;		
}
.markstable table tr:nth-child(even) {
	background: #d1e9ff;
}
.markstable table tr:nth-child(odd) {
	background: #a7d1f7;
}
.markstable table{
	border-collapse:collapse;
}
.markstable th
{
	padding: 6px 14px 6px 14px;
	font-size:14px;
	border-top: 1px solid #97C4E7;
	border-bottom: 1px solid #97C4E7;
	background-color: #337ab7;
	color: #FFFFFF;	
	text-align: center;
	text-transform: uppercase;
}
.markstable td{
	padding: 6px 14px 6px 14px;
	font-size:14px;
	border-bottom: 1px solid #97C4E7;
	border-right: 1px solid #FFFFFF;
}
.markstable td.space{
	border: medium none;
	border-left: medium none;
	border-right: medium none;
	background-color: #FFFFFF;
}
.resultdate{
	float: left;
	width: 100%;
	padding-top: 100px;
	text-align: left;
	padding-bottom:20px;
}
.signature{
	float: left;
	width: 200px;
	padding-top: 50px;
	text-align: center;
	margin-right: 60px;
	clear: both;
}
.signature span,
.resultdate span
{
	font-size: 16px;
	color: #4E5E6A;
	font-style: italic;
	padding-bottom:20px;
}
</style>
<?php
$currency = $this->Setting->getfieldname('currency_code');
$currency_symbol = $this->Setting->get_currency_symbole($currency);
$stud_date = $this->Setting->getfieldname('date_format');
$total_amount = !empty($payment_info['total_amount'])?$payment_info['total_amount']:0;
$fees_paid_amount = !empty($payment_info['fees_paid_amount'])?$payment_info['fees_paid_amount']:0;
$due_amount = 0;
$due_amount = $payment_info['total_amount'] - $payment_info['fees_paid_amount'];
$heading = $this->Setting->getfieldname('school_name');
?>
<div class="print_show">

<table class="table" width="100%" style="border:1px solid #ddd">
		<tr>
		<?php $logo = isset($logo)?$logo:'school-logo.jpg';?>
				<td><img src="<?php echo $this->request->base;?>/img/<?php echo $logo;?>" ></td>
				<td align="right"><b> <?php echo __('Issue Date :'); ?> </b><?php echo date($stud_date,strtotime($payment_info['created_date']));; ?>
					<br>
					<?php
						if($payment_info['payment_status'] == 0){
							$sta = __('Not Paid');
						}else if($payment_info['payment_status'] == 1){
							$sta = __('Partially Paid');
						}else if($payment_info['payment_status'] == 2){
							$sta = __('Fully Paid');
						}
					?>

					<b> <?php echo __('Status :'); ?> </b><label class="btn btn-success btn-xs"><?php echo $sta;?></label>
				</td>
		</tr>

		<tr>
			<td><b><h4><?php echo __('Payment To');?></h4></b>
				<div><?php echo $heading;?> <br>
				<?php echo $address; ?><br>
				<?php echo $phone; ?>
			</div>
			</td>

			<td align="right"><b><h4><?php echo __('Bill to');?></h4></b>
				<div>
				<?php echo "Student ID : ".$this->setting->get_studentID($user_info['user_id']); ?><br>
				<?php echo $user_info['first_name'].'  '.$user_info['last_name']; ?><br>
				<?php echo $user_info['address'];?><br>
				<?php echo $user_info['state'].','.$user_info['city']; ?>
				</div>
			</td>
		</tr>

	</table>

		<table class="table table-bordered" style="border:0.2px solid #ddd" width="100%" >

			<tr>
				<th class="text-center" align="center" style="border:1px solid #ddd"><?php echo '#';?></th>
				<th class="text-center" align="center" style="border:1px solid #ddd"><?php echo __('Fee Type')?></th>
				<th class="text-center" align="center" style="border:1px solid #ddd"><?php echo __('Total');?></th>
			</tr>

			<tr>
					<td class="text-center" align="center" style="border:1px solid #ddd"><?php echo '1';?></td>
					<td class="text-center" align="center" style="border:1px solid #ddd"><?php echo $cate_info['category_type'];?></td>
					<td class="text-center" align="center" style="border:1px solid #ddd"><?php echo $payment_info['total_amount']." ".$currency_symbol; ?></td>
			</tr>

		</table>

		<table align="right" width="100%">
			<tr>
				<td colspan="2" class="text-right" align="right"> <?php echo __('Sub Total:'); ?> </td>
				<td colspan="2" align="center"><?php echo $total_amount." ".$currency_symbol; ?></td>
			</tr>

			<tr>
				<td colspan="2" align="right" class="text-right"> <?php echo __('Payment Made:'); ?> </td>
				<td colspan="2" align="center"><?php echo $fees_paid_amount." ".$currency_symbol;?></td>
			</tr>

			<tr>
				<td colspan="2" class="text-right" align="right"> <?php echo __('Due Amount:'); ?> </td>
				<td colspan="2" align="center"><?php echo $due_amount." ".$currency_symbol; ?></td>
			</tr>

		</table>

<?php
if(isset($history))
{
?>

	<hr style="float:left;width:100%";/>

	<caption><h4><?php echo __('Payment History');?></h4></caption>

	<table class="table table-bordered" style="border:0.2px solid #ddd" width="100%" >

		<tr>
			<th class="text-center" align="center" style="border:1px solid #ddd"><?php echo __('Date');?></th>
			<th class="text-center" align="center" style="border:1px solid #ddd"><?php echo __('Amount')?></th>
			<th class="text-center" align="center" style="border:1px solid #ddd"><?php echo __('Method');?></th>
		</tr>
		<?php
		foreach($history as $history_info){
		?>
		<tr>
			<td class="text-center" align="center" style="border:1px solid #ddd"><?php echo date($stud_date,strtotime($history_info['paid_by_date']));?></td>
			<td class="text-center" align="center" style="border:1px solid #ddd"><?php echo $history_info['amount']." ".$currency_symbol;?></td>
			<td class="text-center" align="center" style="border:1px solid #ddd"><?php echo $history_info['payment_method'];?></td>
		</tr>
		<?php
		}
		?>
	</table>
<?php
}
?>
</div>
<center style="margin-bottom:20px;">
<button type="button" class="btn btnview btn-info printbtn" onclick="PrintElem('.print_show')" ><?php echo __('Print'); ?> </button> 
<?php echo $this->Html->link(__('PDF'),array('action' => 'paymentviewpdf',$this->Setting->my_simple_crypt($payment_info['fees_pay_id'],'e')),array('class'=>'btn btnview btn-info','target'=>'top')); ?>
</center>