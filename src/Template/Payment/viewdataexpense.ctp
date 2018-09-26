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
	width: 130px;
	float: left;
	text-align: center;
}
.billtocontent{
	float: left;
    width: 70%;
	padding-left: 20px;
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
$admin_user=$admin_info['first_name'].' '.$admin_info['last_name'];
$currency = $this->Setting->getfieldname('currency_code');
$currency_symbol = $this->Setting->get_currency_symbole($currency);
$stud_date = $this->Setting->getfieldname('date_format');
$heading = $this->Setting->getfieldname('school_name');

$school_logo = $this->Setting->getfieldname('school_logo');	
$attach_logo = WWW_ROOT ."img/".$school_logo;
$ext = pathinfo($attach_logo, PATHINFO_EXTENSION);				
$logo = $this->Setting->base64_encode_image ($attach_logo,$ext);
		
?>

<div class="print_show">
	<page size="A4">
				
	<img class="invoiceIMG" style="vertical-align:top;margin-top:-60px;background-repeat:no-repeat;" src="<?php echo $this->request->base.'/webroot/img/new_1000_1.jpg';?>" width="100%">
	<?php echo '
	<div class="mainarea">
		<div class="movetop">
			<div class="mainlogo">
				<img src="'.$logo.'"/>
			</div>
			<div class="schoolname">	
				<div class="textcr"><b>'.$heading.'</b></div>
				<div class="textcr">'.$address.'</div>
				<div class="textcr">Phone No. '.$phone.'</div>
			</div>
		</div>	
		
		<div class="lastdiv">
			<div class="textcr first">Invoice <br><b style="text-align:right;">#'.$expense_data['income_id'].'</b></div>
			<div class="textcr">Date : <b>'.date($stud_date,strtotime($expense_data['income_create_date'])).'</b></div>
			<div class="textcr">Status : <b>'.$expense_data['payment_status'].'</b></div>
		</div>
		<div class="rightdiv">
			<div class="billtoimg">
				<h4>| Bill To.</h4>
			</div>
			<div class="billtocontent">
				<div class="textcr"><b>'.$expense_data['supplier_name'].'</b></div>
				<div class="textcr">Gandhinagar</div>
			</div>
		</div>	
		
		<div class="markstable">
		<table width=100% style="margin-top:40px;">
			<thead>
			<tr>
				<th>#</th>
				<th>Expense Date</th>
				<th>Expense Name</th>
				<th>('.$currency_symbol.') Price</th>
			</tr>
			<thead>
			<tbody>';
			
			$num=1;
			$total_amount=0;
			$entry=$expense_data['entry'];
			$am=json_decode($entry);
			$amount=array();
			
			foreach($am as $total)
			{
				$total_amount=$total_amount + $total->amount;
				
				echo '
				<tr>
					<td align=center>'.(string)$num.'</td>
					<td align=center>'.date($stud_date,strtotime($expense_data['income_create_date'])).'</td>
					<td align=center>'.$total->entry.'</td>
					<td align=center>'.$currency_symbol." ".$total->amount.'</td>
				</tr>';
				$num++;
			}
			echo '
			</tbody>
		</table>
		</div>
		<table align=right width=175px style="margin-top: 20px;">
			<tr>
				<td colspan="3" align=right class="totalmarks bordertop" style="padding: 5px 0px;"><b>Subtotal ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
				<td colspan="1" align=left class="bordertop" style="padding: 5px 0px;"><b>'.$currency_symbol." ".(string)$total_amount.'</b></td>
			</tr>
			<tr>
				<td colspan="3" align=right class="totalmarks bordertop" style="padding: 5px 0px;"><b>Discount ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
				<td colspan="1" align=left class="bordertop" style="padding: 5px 0px;"><b>'.$currency_symbol." ".'0.00'.'</b></td>
			</tr>
			<tr>
				<td colspan="3" align=right class="totalmarks bordertop" style="padding: 5px 0px;"><b>Total Paid ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
				<td colspan="1" align=left class="bordertop" style="padding: 5px 0px;"><b>'.$currency_symbol." ".(string)$total_amount.'</b></td>
			</tr>
			<tr>
				<td colspan="3" align=right class="totalmarks bordertop" style="padding: 5px 0px;"><b>Amount Due ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
				<td colspan="1" align=left class="bordertop" style="padding: 5px 0px;"><b>'.$currency_symbol." ".'0.00'.'</b></td>
			</tr>
			<tr style="background-color: #03A9F4;color: #FFFFFF;">
				<td colspan="3" align=right class="totalmarks bordertop" style="padding: 5px 0px;"><b>Grand Total ('.$currency_symbol.') : &nbsp;&nbsp;  </b></td>				
				<td colspan="1" align=left class="bordertop" style="padding: 5px 0px;"><b>'.$currency_symbol." ".(string)$total_amount.'</b></td>
			</tr>
		</table>
		<div class="signature">
			<hr color="#97C4E7">
			<span>Signature</span>
		</div>
	</div>
	</page>';
	?>
</div>

<center>
<?php 
echo $this->Html->link (__('Print'),array('action' => 'expenseprint',$this->Setting->my_simple_crypt($expense_data['income_id'],'e')),array('class'=>'btn btnview btn-info printbtn','target'=>'top'))."&nbsp;";
echo $this->Html->link (__('PDF'),array('action' => 'expensepdf',$this->Setting->my_simple_crypt($expense_data['income_id'],'e')),array('class'=>'btn btnview btn-info','target'=>'top')); ?>
</center>