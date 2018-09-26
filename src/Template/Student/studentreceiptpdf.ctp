<?php 
		use Cake\Routing\Router;
		
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
		
		$school_name = $this->Setting->getfieldname('school_name');
		$school_address = $this->Setting->getfieldname('school_address');
		$stud_date = $this->Setting->getfieldname('date_format');
		$school_icon=$this->Setting->getfieldname('school_icon');
		
		$school_logo = $this->Setting->getfieldname('school_logo');
		
		$attach_logo = WWW_ROOT ."img/".$school_logo;
		$ext = pathinfo($attach_logo, PATHINFO_EXTENSION);				
		$logo = $this->Setting->base64_encode_image ($attach_logo,$ext);
		
		$user_img = $this->Setting->get_user_image($user_id);
		$attach_user_img = WWW_ROOT ."img/".$user_img;
		$user_ext = pathinfo($attach_user_img, PATHINFO_EXTENSION);				
		$user_profile = $this->Setting->base64_encode_image ($attach_user_img,$user_ext);
		
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
		strong{
			color: #4E5E6A;
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
			margin-top: 15px;
			border: 1px solid #97C4E7;
		}
		.studINOF th
		{
			padding: 6px 14px 6px 14px;
			font-size:14px;
			background-color: #337ab7;
			color: #FFFFFF;	
			
		}
		.borderright{
			border-right: 1px solid #337ab7;
		}
		.studINOF td{
			padding: 6px 14px 6px 14px;
			font-size:14px;	
			border-bottom: 1px solid #97C4E7;
			color: #444444;
		}
		.candidate{
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
		.candidate span
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
				<span>Examination Hall Ticket</span>
			</div>
		
			<hr color="#FFFFFF">
						
			<table border=0 width=100% class="studINOF">
				<thead>
				</thead>
				<tbody>
					<tr>					
						<td rowspan=4 style="text-align:center;border-right: 1px solid #97C4E7;">
							<img src="'.$user_profile.'" height="100" width="100"/>	
						</td>
						<td colspan=2>
							<strong>Student Name : </strong>'.$this->Setting->get_user_full_name($user_id).'
						</td>
					</tr>
					<tr>
						<td align="left" style="border-right: 1px solid #97C4E7;">
							<strong>Student ID : </strong>'.$this->Setting->get_studentID($user_id).'
						</td>
						<td align="left">	
							<strong>Roll No : </strong>'.$this->Setting->get_user_roll_no($user_id).'
						</td>
					</tr>
					<tr>
						<td align="left" style="border-right: 1px solid #97C4E7;">
							<strong>Class Name : </strong>'.$this->Setting->get_class_id($this->Setting->get_class_list_user_id($user_id)).'
						</td>
						<td align="left">
							<strong>Section Name : </strong>'.$this->Setting->section_name($this->Setting->get_user_section_id($user_id)).'
						</td>
					</tr>
					<tr>
						<td align="left" style="border-right: 1px solid #97C4E7;">
							<strong>Start Date : </strong>'.date($stud_date,strtotime($this->Setting->get_exam_data($exam_id,'exam_date'))).'					
						</td>
						<td align="left">
							<strong>End Date : </strong>'.date($stud_date,strtotime($this->Setting->get_exam_data($exam_id,'exam_end_date'))).'
						</td>
					</tr>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
			<hr color="#FFFFFF">
			<table border=0 width=100% class="studINOF">
				<thead>
				</thead>
				<tbody>
					<tr>					
						<td>
							<strong>Examination Centre : </strong>
							'.$this->Setting->get_hall_data($hall_id,'hall_name').
							", ".$school_name.'
						</td>
					</tr>
					<tr>
						<td>
							<strong>Examination Centre Address : </strong>'.$school_address.'
						</td>
					</tr>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
			</div>');
			
			if(!empty($exam_time_table_data))
			{
				$mpdf->WriteHTML('
				<div style="float:left; width: 100%;padding-top: 20px;">
				<table border=0 width=100% class="studINOF">
					<thead>
						<tr>
							<th colspan=5 style="background: #FFFFFF;color: #000000;">
								Time Table For Exam Hall
							</th>
						</tr>
						<tr>
							<th>Subject Code</th>
							<th>Subject</th>
							<th>Exam Date</th>
							<th>Exam Time</th>
							<th>Examiner Sign.</th>
						</tr>
					</thead>
					<tbody>');
					
					foreach($exam_time_table_data as $time_table)
					{
						$mpdf->WriteHTML('
						<tr>
							<td align=center>'.$this->Setting->get_subject_data($time_table['subject_id'],'sub_code').'</td>
							<td align=center>'.$this->Setting->get_subject_data($time_table['subject_id'],'sub_name').'</td>
							<td align=center>'.date($stud_date,strtotime($time_table['exam_date'])).'</td>
							<td align=center>'.substr_replace($time_table['start_time'], ' ', -3, -2)." To ".substr_replace($time_table['end_time'], ' ', -3, -2).'</td>
							<td align=center></td>
						</tr>');
					}
					$mpdf->WriteHTML('
					</tbody>
					<tfoot>
					</tfoot>
					</table>
				</div>');		
			}
			$mpdf->WriteHTML('
			<div class="candidate">
				<hr color="#97C4E7">
				<span>Student Signature</span>
			</div>
			<div class="signature">
				<hr color="#97C4E7">
				<span>Authorized Signature</span>
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