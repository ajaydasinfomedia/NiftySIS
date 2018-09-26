<?php 
	
		error_reporting(0);
		
		//require_once(ROOT . DS .'vendor' . DS . 'mpdf' . DS . 'mpdf.php');
		
		ob_clean();
		
		header('Content-type: application/pdf');
		header("Content-type: image/png");
		header('Content-Disposition: inline; filename="deliverynote"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');
                
        require_once(ROOT . DS .'vendor'. DS . 'mpdf' . DS . 'mpdf.php');
        $mpdf	=	new mPDF('c','A4-L');	
		
		$attach_logo = WWW_ROOT ."img/school-logo.jpg";
		$user_image = WWW_ROOT ."images/".$profile;
		$ext = pathinfo($attach_logo, PATHINFO_EXTENSION);		
		$ext2 = pathinfo($user_image, PATHINFO_EXTENSION);		
		$logo = $this->Setting->base64_encode_image ($attach_logo,$ext);
		$image = $this->Setting->base64_encode_image ($user_image,$ext2);
		
		$total_mark = $this->Setting->get_exam_data($exam_id,'total_mark');
		
		$mpdf->WriteHTML('<html>');
		$mpdf->WriteHTML('<head>');
		$mpdf->WriteHTML('</head>');
		$mpdf->WriteHTML('<body>');
							
		$mpdf->SetTitle('Student Result');
					
		$mpdf->WriteHTML('<center>');
		
		$mpdf->WriteHTML('<p>');
			$mpdf->WriteHTML('<b>Result</b>');
		$mpdf->WriteHTML('</p>');
		
		$mpdf->WriteHTML('<hr color="black">');
		
		$mpdf->WriteHTML('<table>');
			
			$mpdf->WriteHTML('<tr>');
				
				$mpdf->WriteHTML('<td>');
					$mpdf->WriteHTML('<img src="'.$logo.'" align="left" />');
				$mpdf->WriteHTML('</td>');
			
				$mpdf->WriteHTML('<td>');
					$mpdf->WriteHTML($school_name);
				$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('</tr>');
		
		$mpdf->WriteHTML('</table>');
		
		$mpdf->WriteHTML('<hr color="black">');
		
		$mpdf->WriteHTML('<table>');
			
		$mpdf->WriteHTML('<hr color="black">');
		
		$mpdf->WriteHTML('<tr width=100% float=left>');
		
		$mpdf->WriteHTML('<br>');
		$mpdf->WriteHTML('<td width=40% float=left text-align=left;>');
		
			  $mpdf->WriteHTML('<p>');
			  $mpdf->WriteHTML('<b>Name :</b>'." ".$fname." ".$lname);
			  $mpdf->WriteHTML('</p>');
			  
			  $mpdf->WriteHTML('<br>');
			  
			  $mpdf->WriteHTML('<p>');
			  $mpdf->WriteHTML('<b>Student ID :</b>'." ".$studentID);
			  $mpdf->WriteHTML('</p>');
			  
			  $mpdf->WriteHTML('<br>');
			  
			  $mpdf->WriteHTML('<p>');
			  $mpdf->WriteHTML('<b>Class :</b>'." ".$classname);
			  $mpdf->WriteHTML('</p>');
			  
			  $mpdf->WriteHTML('<br>');
			  
			  $mpdf->WriteHTML('<p>');
			  $mpdf->WriteHTML('<b>Exam Name :</b>'." ".$examname);
			  $mpdf->WriteHTML('</p>');
			 
			$mpdf->WriteHTML('</td>');
			
			$mpdf->WriteHTML('<td width=5% float=right text-align=right;>'.
			'<img src='.$this->request->base ."/webroot/img/".$profile.' height="100px" width="105px">'
			.'</td>');

			$mpdf->WriteHTML('<br>');
			
		  
		  $mpdf->WriteHTML('</tr>');
		
		$mpdf->WriteHTML('</table>');
		
			$mpdf->WriteHTML('<hr color="black">');
			
			$mpdf->WriteHTML('<br>');
			
			$mpdf->WriteHTML('<table width=100% border=1>');
			
			$mpdf->WriteHTML('<thead>');			
			   $mpdf->WriteHTML('<tr border-bottom=1px solid #000;>');
				 $mpdf->WriteHTML('<th border-bottom=1px solid #000 text-align=left border-right=1px solid #000;>S/No</th>');
				 $mpdf->WriteHTML('<th border-bottom=1px solid #000 text-align=left border-right=1px solid #000;>Subject</th>');
				 $mpdf->WriteHTML('<th border-bottom=1px solid #000 text-align=left border-right=1px solid #000;>Obtain Mark (Out of '.$total_mark.')</th>');
				if($total_mark == 100)
				{
				 $mpdf->WriteHTML('<th border-bottom=1px solid #000 text-align=left border-right=1px solid #000;>Grade</th>');
				}
			   $mpdf->WriteHTML('</tr>');
			 $mpdf->WriteHTML('</thead>');
			 
			 $mpdf->WriteHTML('<tbody>');
			
			$i=1;
		
			foreach($data as $pdfdata)
			{
				$mpdf->WriteHTML('<tr align=center;>');
			
			
				$mpdf->WriteHTML('<td align=center>'.$i.'</td>');
			
				$mpdf->WriteHTML('<td align=center>'.$pdfdata['subject_name'].'</td>');
	
				$mpdf->WriteHTML('<td align=center>'.$pdfdata['mark'].'</td>');
				if($total_mark == 100)
				{
					$mpdf->WriteHTML('<td align=center>'.$pdfdata['get_grade'].'</td>');
				}
				
			  $mpdf->WriteHTML('</tr>');
		$i=$i+1;
	}
		$mpdf->WriteHTML('</tbody>');
		$mpdf->WriteHTML('</table>');
		
		 $mpdf->WriteHTML('<p>');
		    $mpdf->WriteHTML('<b>Total Marks : </b>'." ".$total);
		$mpdf->WriteHTML('</p>');
		
		if($total_mark == 100)
		{
			$mpdf->WriteHTML('<p>');
				$mpdf->WriteHTML('<b>GPA(grade point average) : </b>'." ".$GPA);
			$mpdf->WriteHTML('</p>');
		}
		
		  $mpdf->WriteHTML('<hr color="black">');
		 $mpdf->WriteHTML('<p>');
		 $mpdf->WriteHTML('</p>');
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

