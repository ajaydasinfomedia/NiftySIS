<?php

$ad=$admin_info['first_name'].' '.$admin_info['last_name'];
$heading = $this->Setting->getfieldname('school_name');
$stud_date = $this->Setting->getfieldname('date_format');

		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="IncomeInvoice"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');
                
                
                require_once 'mpdf/mpdf.php';
                 $mpdf	=	new mPDF('+aCJK','A4');		
		
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
		
		$mpdf->WriteHTML('<td align=right><b>Issue Date:</b>'.date($stud_date,strtotime($expense_data['income_create_date'])));
		$mpdf->WriteHTML('<br>');
		$mpdf->WriteHTML('<b>Status:</b>'.$expense_data['payment_status']);	
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
						$mpdf->WriteHTML($expense_data['supplier_name'].'<br>');
						
						$mpdf->WriteHTML('</div>');
						
			  $mpdf->WriteHTML('</td>');

	    $mpdf->WriteHTML('</tr>');
        $mpdf->WriteHTML('</table>');

        $mpdf->WriteHTML('<hr>');


        //invoice Entry
        
        $mpdf->WriteHTML('<caption align=left><h4><b>Invoice Entries<b></h4></caption>');




        $mpdf->WriteHTML('<table width=100% align=center border=1 cellpadding=6px>');

        	  $mpdf->WriteHTML('<tr>');

        	  		  $mpdf->WriteHTML('<td align=center>');
        	  		    $mpdf->WriteHTML('<b>#</b>');
        	  		  $mpdf->WriteHTML('</td>');

        	  		   $mpdf->WriteHTML('<td align=center>');
        	  		    $mpdf->WriteHTML('<b>Date</b>');
        	  		  $mpdf->WriteHTML('</td>');


        	  		   $mpdf->WriteHTML('<td align=center>');
        	  		    $mpdf->WriteHTML('<b>Entry</b>');
        	  		  $mpdf->WriteHTML('</td>');

        	  		   $mpdf->WriteHTML('<td align=center>');
        	  		    $mpdf->WriteHTML('<b>Price</b>');
        	  		  $mpdf->WriteHTML('</td>');

        	  		   $mpdf->WriteHTML('<td align=center>');
        	  		    $mpdf->WriteHTML('<b>Username</b>');
        	  		  $mpdf->WriteHTML('</td>');

        	  $mpdf->WriteHTML('</tr>');



	
				$num=1;
				$total_amount=0;
				$entry=$expense_data['entry'];
		
				
				$am=json_decode($entry);
				
				$amount=array();
				
				foreach($am as $total){
					
					
					$total_amount=$total_amount + $total->amount;
					
			
		
        	    $mpdf->WriteHTML('<tr>');

        	  		  $mpdf->WriteHTML('<td align=center>');
        	  		    $mpdf->WriteHTML((string)$num);
        	  		  $mpdf->WriteHTML('</td>');

        	  		   $mpdf->WriteHTML('<td align=center>');
        	  		    $mpdf->WriteHTML(date($stud_date,strtotime($expense_data['income_create_date'])));
        	  		  $mpdf->WriteHTML('</td>');


        	  		   $mpdf->WriteHTML('<td align=center>');
        	  		    $mpdf->WriteHTML($total->entry);
        	  		  $mpdf->WriteHTML('</td>');

        	  		   $mpdf->WriteHTML('<td align=center>');
        	  		    $mpdf->WriteHTML($total->amount);
        	  		  $mpdf->WriteHTML('</td>');

        	  		   $mpdf->WriteHTML('<td align=center>');
        	  		    $mpdf->WriteHTML($ad);
        	  		  $mpdf->WriteHTML('</td>');

        	  $mpdf->WriteHTML('</tr>');


        	  
					
					$num++;
					}
			




        $mpdf->WriteHTML('</table>');


 $mpdf->WriteHTML('<br><br><br>');

        $mpdf->WriteHTML('<table width=100% border=1 cellpadding=6px> ');

        	$mpdf->WriteHTML('<tr>');

        		$mpdf->WriteHTML('<td align=right>');
        		$mpdf->WriteHTML('<h5><b>Grant Total : </b></h5>');
        		$mpdf->WriteHTML('</td>');



        		$mpdf->WriteHTML('<td align=center>');
        		$mpdf->WriteHTML('<h5>'.(string)$total_amount.'</h5>');
        		$mpdf->WriteHTML('</td>');

        	$mpdf->WriteHTML('</tr>');

        $mpdf->WriteHTML('</table>');



	$mpdf->WriteHTML('</body>');
		$mpdf->WriteHTML('</html>');
		


					$mpdf->Output();
					unset($mpdf);
					exit();

		?>