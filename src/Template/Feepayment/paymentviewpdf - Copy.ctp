<?php
		error_reporting(0);

		ob_clean();
		
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="deliverynote"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');     
		
		require_once(ROOT . DS .'vendor'. DS . 'mpdf' . DS . 'mpdf.php');
		$mpdf	=	new mPDF('c','A4-L');	
		
		/* $mpdf->showImageErrors = true; */
		
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
		
		$logo = isset($logo)?$logo:'school-logo.jpg';
		$attach_logo = WWW_ROOT ."img/".$logo;
		$ext = pathinfo($attach_logo, PATHINFO_EXTENSION);		
		$logo = $this->Setting->base64_encode_image ($attach_logo,$ext);	

		$mpdf->WriteHTML('<html>');
		$mpdf->WriteHTML('<head>');

		$mpdf->WriteHTML('</head>');
		$mpdf->WriteHTML('<body>');

		$mpdf->SetTitle('Payment Invoice');
		
		$mpdf->WriteHTML('<h4><b>'.$heading.'</b></h4>');
		$mpdf->WriteHTML('<hr>');
		$mpdf->WriteHTML('<table class=table width=100% style=border:1px solid #ddd>');
		$mpdf->WriteHTML('<tr>');
		$mpdf->WriteHTML('<td>');
		
		if($ext == 'jpg' || $ext == 'jpeg')
			$mpdf->WriteHTML('<img src='.$logo.'></td>');
		
		$mpdf->WriteHTML('<td align=right><b>Issue Date:</b>'.date($stud_date,strtotime($payment_info['created_date'])));
		$mpdf->WriteHTML('<br>');
		$mpdf->WriteHTML('<b>Status:</b>'.$sta);	
		$mpdf->WriteHTML('</tr>');
		$mpdf->WriteHTML('</table>');
		$mpdf->WriteHTML('<hr>');
		
		$mpdf->WriteHTML('<table class=table width=100% style=border:1px solid #ddd>');
		$mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td>');
						$mpdf->WriteHTML('<h4><b>Payment To:</b></h4>');
						$mpdf->WriteHTML('<div>'.$heading.'</div>');
						$mpdf->WriteHTML('<div>');
						$mpdf->WriteHTML($address.'<br>');
						$mpdf->WriteHTML($phone);		
						$mpdf->WriteHTML('</div>');
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td align=right><b><h4>Bill To</h4></b>');
						$mpdf->WriteHTML('<div>');
						$mpdf->WriteHTML("Student ID : ".$this->setting->get_studentID($user_info['user_id']).'<br>');
						$mpdf->WriteHTML($user_info['first_name'].'  '.$user_info['last_name'].'<br>');
						$mpdf->WriteHTML($user_info['address'].'<br>');
						$mpdf->WriteHTML($user_info['state'].'  '.$user_info['city'].'<br>');				
						$mpdf->WriteHTML('</div>');						
			  $mpdf->WriteHTML('</td>');
	    $mpdf->WriteHTML('</tr>');
        $mpdf->WriteHTML('</table>');
        $mpdf->WriteHTML('<hr>');
		
		$mpdf->WriteHTML('<caption align=left><h4><b>Invoice Entries<b></h4></caption>');

        $mpdf->WriteHTML('<table width=100% align=center border=1 cellpadding=6px>');
			$mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td align=center>');
					$mpdf->WriteHTML('<b>#</b>');
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td align=center>');
					$mpdf->WriteHTML('<b>Fee Type</b>');
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td align=center>');
					$mpdf->WriteHTML('<b>Total</b>');
				$mpdf->WriteHTML('</td>');		   
			$mpdf->WriteHTML('</tr>');
			
			$mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td align=center>');
					$mpdf->WriteHTML('1');
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td align=center>');
					$mpdf->WriteHTML($cate_info['category_type']);
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td align=center>');
					$mpdf->WriteHTML($payment_info['total_amount']." ");
				$mpdf->WriteHTML('</td>');
			$mpdf->WriteHTML('</tr>');
		$mpdf->WriteHTML('</table>');
      
		$mpdf->WriteHTML('<br>');
		
		$mpdf->WriteHTML('<table width=100% align=center border=1 cellpadding=6px>');
			$mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td colspan=2 align=right>');
					$mpdf->WriteHTML('<b>Sub Total: </b>');
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td colspan=2 align=center>');
					$mpdf->WriteHTML($total_amount." ");
				$mpdf->WriteHTML('</td>');
			$mpdf->WriteHTML('</tr>');	
			$mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td colspan=2 align=right>');
					$mpdf->WriteHTML('<b>Payment Made: </b>');
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td colspan=2 align=center>');
					$mpdf->WriteHTML($fees_paid_amount." ");
				$mpdf->WriteHTML('</td>');
			$mpdf->WriteHTML('</tr>');	
			$mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td colspan=2 align=right>');
					$mpdf->WriteHTML('<b>Due Amount: </b>');
				$mpdf->WriteHTML('</td>');	
				$mpdf->WriteHTML('<td colspan=2 align=center>');
					$mpdf->WriteHTML($due_amount." ");
				$mpdf->WriteHTML('</td>');	
			$mpdf->WriteHTML('</tr>');
		$mpdf->WriteHTML('</table>');
       
		if(isset($history))
		{
			$mpdf->WriteHTML('<caption align=left><h4><b>Payment History<b></h4></caption>');
			
			$mpdf->WriteHTML('<table width=100% align=center border=1 cellpadding=6px>');
			$mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td align=center>');
					$mpdf->WriteHTML('<b>Date</b>');
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td align=center>');
					$mpdf->WriteHTML('<b>Amount</b>');
				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td align=center>');
					$mpdf->WriteHTML('<b>Method</b>');
				$mpdf->WriteHTML('</td>');		   
			$mpdf->WriteHTML('</tr>');
				
			foreach($history as $history_info)
			{
				$mpdf->WriteHTML('<tr>');
					$mpdf->WriteHTML('<td align=center>');
						$mpdf->WriteHTML(date($stud_date,strtotime($history_info['paid_by_date'])));
					$mpdf->WriteHTML('</td>');
					$mpdf->WriteHTML('<td align=center>');
						$mpdf->WriteHTML($history_info['amount']." ");
					$mpdf->WriteHTML('</td>');
					$mpdf->WriteHTML('<td align=center>');
						$mpdf->WriteHTML($history_info['payment_method']);
					$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('</tr>');
			}
			$mpdf->WriteHTML("</table>");	
		}
						
	$mpdf->WriteHTML('</body>');
	$mpdf->WriteHTML('</html>');
	
	$stylesheet = file_get_contents(ROOT . DS .'webroot'. DS . 'css' . DS . 'pdf.css'); // external css
	$mpdf->WriteHTML($stylesheet,1);
	$mpdf->WriteHTML($html,2);
			
	$mpdf->Output();
		
	ob_end_flush();
		
	unset($mpdf);
	die;
?>