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
        $mpdf = new mPDF('c','A4', 0, '', 15, 15, 10, 0, 0, 0);	
		
		$school_logo = $this->Setting->getfieldname('school_logo');
		
		$attach_logo = WWW_ROOT ."img/".$school_logo;
		$ext = pathinfo($attach_logo, PATHINFO_EXTENSION);				
		$logo = $this->Setting->base64_encode_image ($attach_logo,$ext);

		$total_mark = $this->Setting->get_exam_data($exam_id,'total_mark');
		$school_address = $this->Setting->getfieldname('school_address');
		
		if($gender == 'male')
			$gender = 'Male';
		else
			$gender = 'Female';
		
		/*
		<img class="invoiceIMG" style="vertical-align:top;margin-top:-60px;background-repeat:no-repeat;" src="'.$this->request->base.'/webroot/img/new_1000_1.jpg" width="100%" >
		*/
		
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
		}
		hr{
			
		}
		.invoiceIMG{
			margin-top: 0px;
			padding-top: 0px;
		}
		.movetop{
			float: left;
			width: 100%;
			/* margin-top: -90px; */
		}
		.mainlogo,
		.schoolname
		{
			float: left;
			width: 100%;
			text-align: center;
			padding-bottom: 10px;			
		}
		.mainlogo img
		{
			float: none;
			margin: 0px auto;
		}
		.schoolname span{
			font-size: 22px;
		}
		.pagetitle{
			float: left;
			width: 100%;
			padding-top: 30px;
			text-align: center;
		}
		.pagetitle span{
			font-size: 20px;
			text-transform: uppercase;
			font-weight: bold;
			color: #970606;
		}
		.studINOF
		{
			float: left;
			width: 100%;
			border-collapse:collapse;
			margin-bottom: 15px;
			border-bottom:1px solid #337ab7;
		}
		.studINOF th
		{
			padding: 6px 14px 6px 14px;
			font-size:14px;
			border: 1px solid #FFFFFF;
			border-bottom: 1px solid #97C4E7;
			background-color: #337ab7;
			color: #FFFFFF;	
		}
		.borderright{
			border-right: 1px solid #337ab7;
		}
		.studINOF td{
			padding: 6px 14px 6px 14px;
			font-size:14px;	
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
		}
		.markstable td{
			padding: 6px 14px 6px 14px;
			font-size:14px;
			border-bottom: 1px solid #97C4E7;
		}
		.totalmarks{
			background-color: #337ab7;
			color: #FFFFFF;	
			text-align: center;
		}
		.markstable td.space{
			border: medium none;
			border-left: medium none;
			border-right: medium none;
		}
		.bordertop{
			border-top: 1px solid #97C4E7;
		}
		.resultdate{
			float: left;
			width: 200px;
			padding-top: 100px;
			text-align: center;
		}
		.signature{
			float: right;
			width: 200px;
			padding-top: 100px;
			text-align: center;
		}
		.signature span,
		.resultdate span
		{
			font-size: 16px;
			color: #4E5E6A;
			font-style: italic;
		}
		page[size="A4"] {
		  background: #FFFFFF;
		  width: 21cm;
		  height: 29.7cm;
		  display: block;
		  margin: 0 auto;
		  margin-bottom: 0.5cm; 
		}
		</style>
		');
		$mpdf->WriteHTML('</head>');
		$mpdf->WriteHTML('<body>');
							
		$mpdf->SetTitle('Student Result');
					
		$mpdf->WriteHTML('<center>');
		
		$mpdf->WriteHTML('
			
			<page size="A4">
			<div class="movetop">
				<div class="mainlogo">
					<img src="'.$logo.'"/>
				</div>
				<div class="schoolname">	
					<span>'.$school_name.'</span>
				</div>
			</div>	
			
			<div class="pagetitle">	
				<span>'.$examname.' Exam'.'</span>
			</div>
		
			<hr color="#FFFFFF">
						
			<table border=0 width=100% class="studINOF">			
				<tr>
					<th colspan="1">Month & Year of Exam</th>						
					<th colspan="3">Name of Student </th>												
				</tr>			
				<tr>
					<td colspan="1" class="borderright" align=center>'.$exammonthyear.'</td>
					<td colspan="3" align=center>'.$fname." ".$mname." ".$lname.'</td>											
				</tr>		
				<tr>
					<th>Student ID </th>
					<th>Class Name</th>						
					<th>Section Name</th>						
					<th>Date of Birth</th>
				</tr>		
				<tr>
					<td class="borderright" align=center>'.$studentID.'</td>
					<td class="borderright" align=center>'.$classname.'</td>
					<td class="borderright" align=center>'.$sectionname.'</td>					
					<td align=center>'.$date_of_birth.'</td>
				</tr>				
			</table>
			
			<div class="markstable">
				<table border=0 width=100%>
					<thead>
					<tr>
						<th>SI.No.</th>
						<th>Name of Subject</th>
						<th>Obtain Mark</th>
						<th>Out of Mark</th>');
						if($total_mark == 100)
						{
							$mpdf->WriteHTML('<th>Grade</th>');
						}
						
					$mpdf->WriteHTML('					
					</tr>
					<thead>
					<tbody>');
					
					$i=1;
					$cnt_total_mark = 0;
					foreach($data as $pdfdata)
					{
						$mpdf->WriteHTML('<tr>
											<td align=center>'.$i.'</td>
											<td align=center>'.$pdfdata['subject_name'].'</td>
											<td align=center>'.$pdfdata['mark'].'</td>
											<td align=center>'.$total_mark.'</td>');
											if($total_mark == 100)
											{
												$mpdf->WriteHTML('<td align=center>'.$pdfdata['get_grade'].'</td>');
											}
						$mpdf->WriteHTML('</tr>');
						$i=$i+1;
						$cnt_total_mark = $cnt_total_mark+$total_mark;
					}
	$mpdf->WriteHTML('
					<tr border=0 class="space"><td border=0 colspan="4" class="space"><br></td></tr>
					<tr>
						<td colspan="2" align=center class="totalmarks bordertop"><b>Total Marks  </b></td>				
						<td colspan="1" align=center class="bordertop">'.$total.'</td>
						<td colspan="1" align=center class="bordertop">'.$cnt_total_mark.'</td>
					</tr>
					<tr>');
					$percentage = 0;
					$percentage = round($total*100/$cnt_total_mark);
					$mpdf->WriteHTML('
						<td colspan="2" align=center class="totalmarks bordertop"><b>Percentage</b></td>				
						<td colspan="2" align=center class="bordertop">'.$percentage.'%'.'</td>
					</tr>');
					if($total_mark == 100)
					{
					$mpdf->WriteHTML('	
					<tr>
						<td colspan="3"><b>GPA(grade point average) : </b></td>
						<td>'.$GPA.'</td>
					</tr>');
					}
					$mpdf->WriteHTML('
					</tbody>
				</table>
			</div>
			<div class="resultdate">
				<hr color="#97C4E7">
				<span>Date of Publication of Result</span>
			</div>
			<div class="signature">
				<hr color="#97C4E7">
				<span>Controller of Examination</span>
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

