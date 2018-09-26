<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Controller\Component;
use Cake\Datasource\ConnectionManager;
use Cake\Network\Http\Client;

class ReportController extends AppController{


	public static $load_table_exam;
	public static $load_table_class;
	public static $load_table_subject;
	public static $load_table_user;
	public static $load_table_payment;
	public $getdata_exam;
	public $getdata_payment;
	public $getdata_class;
	public $getdata_subject;
	public $getdata_user;


	public function load_table(){
	ReportController::$load_table_exam=TableRegistry::get('smgt_exam');
	ReportController::$load_table_class=TableRegistry::get('classmgt');
	ReportController::$load_table_subject=TableRegistry::get('smgt_subject');
	ReportController::$load_table_payment=TableRegistry::get('smgt_fees_payment');

	}

	public function failed(){

		$conn=ConnectionManager::get('default');
		$this->load_table();
		$this->getdata_exam=ReportController::$load_table_exam->find();
		$this->set('exam_data',$this->getdata_exam);

		$this->getdata_class=ReportController::$load_table_class->find();
		$this->set('class_data',$this->getdata_class);

		$this->getdata_subject=ReportController::$load_table_subject->find();
		$this->set('subject_data',$this->getdata_subject);
		
		$section = TableRegistry::get('class_section');			
		$section_id = $section->find();			
		$this->set('section_id',$section_id);
	
			if(isset($_REQUEST['view_chart'])) {
				$exam_name=$this->request->data('exam_name');
				
			$exam_id = $_REQUEST['exam_name'];
			$class_id = $_REQUEST['class_name'];
				
			$class_name=$this->request->data('class_name');
			$rs=$conn->execute("SELECT * , count( student_id ) as count
			FROM smgt_marks as m, smgt_users as u
			WHERE m.marks < 35
			AND m.exam_id = '$exam_name'
			AND m.Class_id = '$class_name'
			AND m.student_id = u.user_id
			GROUP BY m.subject_id");
			$this->set('report_fail',$rs);
			
			$section_id=$this->request->data['section'];
			$this->set('sec_id',$section_id);
			
			$this->set('exam_id',$exam_id);
			$this->set('class_id',$class_id);
}
			

	}
	public function attendance()
	{

		$this->load_table();
		$conn=ConnectionManager::get('default');
		
		/*  $class = TableRegistry::get('classmgt');			
		$class_id = $class->find();			
		$this->set('class_id',$class_id); */
			
		$this->getdata_class=ReportController::$load_table_class->find();
		$this->set('class_data',$this->getdata_class);
		
		if(isset($_REQUEST['view_chart']))
		{
			$start_date=$this->request->data('start_date');
			$end_date=$this->request->data('end_date');
			$clas_id=$this->request->data('class_name');

			$this->getdata_class=ReportController::$load_table_class->find();
			$this->set('class_data',$this->getdata_class);
			
			$rs=$conn->execute("SELECT at.class_id, 
			SUM(case when at.status ='Present' then 1 else 0 end) as Present, 
			SUM(case when at.status ='Absent' then 1 else 0 end) as Absent 
			from smgt_attendence as at
			where at.attendence_date BETWEEN '$start_date' AND '$end_date' 
			AND at.class_id = '$clas_id' 
			AND at.role_name = 'student' GROUP BY at.class_id");

			$this->set('report_attendence',$rs);	
			$this->set('class_id',$clas_id);
		}
	}
	public function teacher()
	{
		$conn=ConnectionManager::get('default');	
		ReportController::$load_table_user=TableRegistry::get('smgt_users');
		
		$this->getdata_user=ReportController::$load_table_user->find();
		$rs=$conn->execute("SELECT sb.sub_name,sb.subid,sb.teacher_id FROM smgt_subject as sb 
		INNER JOIN smgt_users as u on u.user_id = sb.teacher_id");
		
		$rs1=$conn->execute("SELECT count(mark.student_id) as count1,sb.teacher_id FROM smgt_subject as sb 
		INNER JOIN smgt_marks as mark on sb.subid = mark.subject_id
		INNER JOIN smgt_users as u on u.user_id = sb.teacher_id 
		WHERE mark.marks >= 40 
		group by sb.teacher_id");
		
		$rs2=$conn->execute("SELECT count(mark.student_id) as count2,sb.teacher_id FROM smgt_subject as sb 
		INNER JOIN smgt_marks as mark on sb.subid=mark.subject_id
		INNER JOIN smgt_users as u on u.user_id = sb.teacher_id 
		WHERE mark.marks < 40 
		group by mark.subject_id");

		// $this->set('report_teacher',$rs);
		$this->set('report_teacher',$rs1);
		$this->set('report_teacher2',$rs2);
		$this->set('user_data',$this->getdata_user);
	}

	public function feepayment(){

		$this->load_table();
		$conn=ConnectionManager::get('default');
		
		$this->getdata_class=ReportController::$load_table_class->find();
		$this->set('class_data',$this->getdata_class);

		$this->getdata_payment=ReportController::$load_table_payment->find();
		$this->set('payment_data',$this->getdata_payment);
		
		
		if(isset($_REQUEST['view_chart'])){

				$catetable_register=TableRegistry::get('smgt_categories');
				$get_all_data_cat=$catetable_register->find();
					$this->set('get_all_data_cat',$get_all_data_cat);
					
					 $user_table_register=TableRegistry::get('smgt_users');
					 $get_all_user=$user_table_register->find();
						$this->set('get_all_user',$get_all_user);
			
		
			$get_class_id=$this->request->data('class_id');
			$get_fees_id=$this->request->data('fees_id');
			$get_payment_status=$this->request->data('payment_status');
			$start_year=$this->request->data('start_year');
			$end_year=$this->request->data('end_year');

				
				

$rs=$conn->execute("SELECT * from smgt_fees_payment WHERE class_id='$get_class_id' and fees_id='$get_fees_id' and payment_status='$get_payment_status' and  start_year>='$start_year' and end_year<='$end_year' ");
$this->set('fees_data',$rs);



		}

		


	}
	public function result()
	{
		$conn=ConnectionManager::get('default');
		$this->load_table();
		$this->getdata_exam=ReportController::$load_table_exam->find();
		$this->set('exam_data',$this->getdata_exam);

		$this->getdata_class=ReportController::$load_table_class->find();
		$this->set('class_data',$this->getdata_class);
		
		if(isset($_REQUEST['view_chart'])) 
		{		

			$subject_tbl = TableRegistry::get('smgt_subject');
			
			$exam_id = $_REQUEST['exam_name'];
			$class_id = $_REQUEST['class_name'];
			
			$conn = ConnectionManager::get('default');
			
			if(isset($_REQUEST['section']) && $_REQUEST['section'] != "")
			{
				
				$sub_id = $_REQUEST['section'];
				
				$subject_list = $subject_tbl->find()
								->where(['class_id'=>$class_id,'section'=>$sub_id])
								->hydrate(false)->toArray();
		
				$sql = 'select * from class_section as cls_sec 
				left join smgt_users as user on cls_sec.class_id = user.classname 
				where cls_sec.class_section_id = '.$sub_id.'  
				AND cls_sec.is_deactive = 0 ';
				
				$student = $conn->execute($sql)->fetchAll('assoc');
				$this->set('sub_id',$sub_id);
			}
			else
			{ 
				$subject_list = $subject_tbl->find()
								->where(['class_id'=>$class_id])
								->hydrate(false)->toArray();
								
				$sql = 'select * from smgt_users where classname ='.$class_id;
				
				$student = $conn->execute($sql)->fetchAll('assoc');
				
			} 
			
			$this->set('exam_id',$exam_id);
			$this->set('class_id',$class_id);
			$this->set('subject_list',$subject_list);
			$this->set('student',$student);
		}
	}
}

?>
