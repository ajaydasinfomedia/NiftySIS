<?php
		error_reporting(0);

		ob_clean();
		
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="Payment"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');     
		
		require_once(ROOT . DS .'vendor'. DS . 'mpdf' . DS . 'mpdf.php');
		$mpdf = new mPDF('c','A4', 0, '', 0, 0, 10, 0, 0, 0);					

		if($payment_info['payment_status'] == 0){
			$sta = __('Not Paid');
		}else if($payment_info['payment_status'] == 1){
			$sta = __('Partially Paid');
		}else if($payment_info['payment_status'] == 2){
			$sta = __('Fully Paid');
		}
		
		$heading = $this->Setting->getfieldname('school_name');

		$currency = $this->Setting->getfieldname('currency_code');
		$currency_symbol = $this->Setting->get_currency_symbole($currency);
		$stud_date = $this->Setting->getfieldname('date_format');
		$total_amount = !empty($payment_info['total_amount'])?$payment_info['total_amount']:0;
		$fees_paid_amount = !empty($payment_info['fees_paid_amount'])?$payment_info['fees_paid_amount']:0;
		$due_amount = 0;
		$due_amount = $payment_info['total_amount'] - $payment_info['fees_paid_amount'];
		
		$school_logo = $this->Setting->getfieldname('school_logo');
		
		$attach_logo = WWW_ROOT ."img/".$school_logo;
		$ext = pathinfo($attach_logo, PATHINFO_EXTENSION);				
		$logo = $this->Setting->base64_encode_image ($attach_logo,$ext);
		
		$mpdf->WriteHTML('<html>');
		$mpdf->WriteHTML('<head>
		<style>
		html,
		body{
			font-family: "Open Sans",sans-serif;
			float: left;
			width: 100%;
			margin-top: 0px;
			padding-top: 0px;
			color: #4E5E6A;
			font-size: 13px;
		}
		.mainarea{
			float: left;
			width: 100%;	
		}
		.movetop{
			float: left;
			width: 100%;
			margin-top: -90px;
			margin-left: 60px;
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
			padding-right: 25px;
			margin-left: 60px;
			margin-top: 25px;
		}
		.rightdiv{
			float: left;
			width: 70%;
			padding-right: 25px;
			margin-left: 60px;
		}
		.lastdiv{
			float: right;
			width: 150px;
			margin-right: 60px;
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
			margin-top: 0px;
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
			font-size:13px;
			margin-bottom: 4px;
			color: #697888;
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
		.lastdiv .textcr.first>br,
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
		.lastdiv .textcr b
		{
			text-align: right;
		}
		.markstable{
			float: left;
			width: 100%;		
		}
		.markstable.second{
			margin-left: 60px;		
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
		.markstable td:first-of-type{
			border-left: 1px solid #97C4E7;
			border-right: 1px solid #97C4E7;
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
			margin-left: 60px;
			padding-bottom:20px;
		}
		.signature{
			float: left;
			width: 200px;
			padding-top: 50px;
			text-align: center;
			margin-left: 60px;
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
		');
		$mpdf->WriteHTML('</head>');
		$mpdf->WriteHTML('<body>');
							
		$mpdf->SetTitle('Student Income');
					
		$mpdf->WriteHTML('<center>');
		
		$mpdf->WriteHTML('
			
			<page size="A4">
				
			<img class="invoiceIMG" style="vertical-align:top;margin-top:-60px;background-repeat:no-repeat;" src="'.$this->request->base.'/webroot/img/new_1000_1.jpg" width="100%" >
			
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
					<div class="textcr first" style="margin-top: 15px;padding:0px;background-color: #03A9F4;color:#FFFFFF;font-size:15px;text-transform:uppercase;">Invoice <br><b style="text-align:right;color:#FFFFFF;font-size:15px;padding:0px;">#'.$payment_info['fee_pay_id'].'</b></div>
					<div class="textcr">Date : <b>'.date($stud_date,strtotime($payment_info['created_date'])).'</b></div>
					<div class="textcr">Status : <b>'.$sta.'</b></div>
				</div>
				<div class="rightdiv">
					<div class="billtoimg">
						<h4>| Bill To.</h4>
					</div>
					<div class="billtocontent">
						<div class="textcr"><b>'.$user_info['first_name'].'  '.$user_info['last_name'].'</b></div>
						<div class="textcr">Student ID <b>'.$this->setting->get_studentID($user_info['user_id']).'</b></div>
						<div class="textcr">'.$user_info['address'].'</div>
					</div>
				</div>
				
				<div class="markstable">
				<table width=670px style="margin-left:60px;margin-top:40px;">
					<thead>
					<tr>
						<th>#</th>
						<th>Fee Type</th>
						<th>('.$currency_symbol.') Price</th>
					</tr>
					<thead>
					<tbody>
						<tr>
							<td align=center>1</td>
							<td align=center>'.$cate_info['category_type'].'</td>
							<td align=center>'.$currency_symbol." ".$payment_info['total_amount'].'</td>
						</tr>
					</tbody>
				</table>
				</div>
				<hr color=#FFFFFF>
				<table align=right width=200px style="border-collapse:collapse;margin-top: 20px;margin-right: 60px;">
					<tr>
						<td colspan="3" align=right class="totalmarks bordertop" style="padding: 5px 0px;"><b>Subtotal ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
						<td colspan="1" align=left class="bordertop" style="padding: 5px 0px;"><b>'.$currency_symbol." ".$total_amount.'</b></td>
					</tr>
					<tr>
						<td colspan="3" align=right class="totalmarks bordertop" style="padding: 5px 0px;"><b>Discount ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
						<td colspan="1" align=left class="bordertop" style="padding: 5px 0px;"><b>'.$currency_symbol." ".'0.00'.'</b></td>
					</tr>
					<tr>
						<td colspan="3" align=right class="totalmarks bordertop" style="padding: 5px 0px;"><b>Total Paid ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
						<td colspan="1" align=left class="bordertop" style="padding: 5px 0px;"><b>'.$currency_symbol." ".$fees_paid_amount.'</b></td>
					</tr>
					<tr>
						<td colspan="3" align=right class="totalmarks bordertop" style="padding: 5px 0px;"><b>Amount Due ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
						<td colspan="1" align=left class="bordertop" style="padding: 5px 0px;"><b>'.$currency_symbol." ".$due_amount.'</b></td>
					</tr>
					<tr style="background-color: #03A9F4;color: #FFFFFF;">
						<td colspan="3" align=right class="totalmarks bordertop" style="padding: 5px 0px;color:#FFFFFF;"><b>Grand Total ('.$currency_symbol.') : &nbsp;&nbsp;  </b></td>				
						<td colspan="1" align=left class="bordertop" style="padding: 5px 0px;color:#FFFFFF;"><b>'.$currency_symbol." ".$due_amount.'</b></td>
					</tr>
				</table>');
				if(isset($history))
				{
					$mpdf->WriteHTML('	
					<div class="markstable second" style="margin-left:60px;">
					<h4>Payment History</h4>
					<table width=100% style="margin-top:20px;margin-left:60px;">
					<thead>
					<tr>
						<th>#</th>
						<th>Date</th>
						<th>('.$currency_symbol.') Price</th>
						<th>Method</th>
					</tr>
					<thead>
					<tbody>');
					$num=1;
					foreach($history as $history_info)
					{
						$mpdf->WriteHTML('
							<tr>
								<td align=center>'.(string)$num.'</td>
								<td align=center>'.date($stud_date,strtotime($history_info['paid_by_date'])).'</td>
								<td align=center>'.$currency_symbol." ".$history_info['amount'].'</td>
								<td align=center>'.$history_info['payment_method'].'</td>
							</tr>');
						$num++;
					}
				$mpdf->WriteHTML('
				</tbody>
				</table>
				</div>');
				}
				$mpdf->WriteHTML('
				<div class="signature">
					<hr color="#97C4E7">
					<span>Signature</span>
				</div>
			</div>
		</page>
		</body>
		');

		$mpdf->WriteHTML('</center>');
		$mpdf->WriteHTML('</html>');
			

		$stylesheet = file_get_contents(ROOT . DS .'webroot'. DS . 'css' . DS . 'pdf.css'); // external css
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($html,2);
				
		$mpdf->Output();
			
		ob_end_flush();
			
		unset($mpdf);
		die;
?>
   
