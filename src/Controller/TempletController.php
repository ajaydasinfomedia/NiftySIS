<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;
use Cake\Network\Http\Client;

class TempletController extends AppController
{
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
	
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Flash');
	}
	
	public function load_table()
	{
		ReportController::$load_table_exam=TableRegistry::get('smgt_exam');
		ReportController::$load_table_class=TableRegistry::get('classmgt');
		ReportController::$load_table_subject=TableRegistry::get('smgt_subject');
		ReportController::$load_table_payment=TableRegistry::get('smgt_fees_payment');
	}
	
	public function templet()
	{
		$stud_date = $this->Setting->getfieldname('date_format');
		
		$class1 = TableRegistry::get('Smgt_users');
		$class2 = TableRegistry::get('smgt_attendence');
		
		$query1=$class1->find()->where(['role'=>'student']);
		$query2=$class1->find()->where(['role'=>'teacher']);
		$query3=$class1->find()->where(['role'=>'parent']);
		
		
		$stud_count=0;
		foreach($query1 as $data)
		{
			$stud_count=$stud_count+1;
		}
		$this->set('stud_count',$stud_count);
		
		$teacher_count=0;
		foreach($query2 as $data)
		{
			$teacher_count=$teacher_count+1;
		}
		$this->set('teacher_count',$teacher_count);
		
		$currt_dt=Time::now();
		$current_date=date("Y-m-d", strtotime($currt_dt));
		
		$parent_count=0;
		foreach($query3 as $data)
		{
			$parent_count=$parent_count+1;
		}
		$this->set('parent_count',$parent_count);
		
		$query4=$class2->find()->where(['attendence_date'=>$current_date,'role_name'=>'student']);
		
		$attend_count=0;
		foreach($query4 as $data)
		{
			$attend_count=$attend_count+1;
		}
		$this->set('attend_count',$attend_count);
		
		$holiday_table_register=TableRegistry::get('smgt_holiday');
		$select_holiday=$holiday_table_register->find()->order(['holiday_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();
		if(!empty($select_holiday))
			$this->set('holiday_list',$select_holiday);

		$notice_table_register=TableRegistry::get('smgt_notice');
		$select_data_wise_teacher=$notice_table_register->find()->where(['OR' =>[[
												'notice_for' => 'admin'],
												['notice_for' => 'student'],
												['notice_for' => 'teacher'],
												['notice_for' => 'supportstaff'],
												['notice_for' => 'parent'],
												['notice_for' => 'all']]])->order(['notice_start_date' => 'DESC'])->limit(5)->hydrate(false)->toArray();	
		if(!empty($select_data_wise_teacher))
			$this->set('notice_data',$select_data_wise_teacher);
		
		$news_table_register=TableRegistry::get('smgt_news');
		$select_news = $news_table_register->find()->order(['news_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();
		if(!empty($select_news))
			$this->set('news_list',$select_news);
		
		$event_table_register=TableRegistry::get('smgt_event');
		$select_event = $event_table_register->find()->order(['event_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();
		if(!empty($select_event))
			$this->set('event_list',$select_event);

		$user_id=$this->request->session()->read('user_id');
		$role=$this->Setting->get_user_role($user_id);
		$data=$this->Setting->smgt_notice_show_calender($role);
		$this->set('data',$data);
		
		/* Message Code */
		$class=TableRegistry::get('smgt_message_reciver');
		$query = $class->find()
				->where(['reciver_id' => $user_id])
				->order(['date' => 'DESC']);
				
		$inboxdatashow = array();
		foreach($query as $usr_nm)
		{
			$inboxdatashow[]=array(
			'user_name'=>$this->Setting->get_user_id($usr_nm['sent_id']),
			'image'=>$this->Setting->get_user_image($usr_nm['sent_id']),
			'msg_sub'=>$this->Setting->get_message_sub($usr_nm['message_id']),
			'msg_des'=>$this->Setting->get_message_des($usr_nm['message_id']),
			'date'=>$usr_nm['date'],
			'id'=>$usr_nm['message_id'],
			);
		}	
		if(!empty($inboxdatashow))
			$this->set('inboxdata',$inboxdatashow);
		
		/* Homework Code */
		$class = TableRegistry::get('smgt_homework');
		$query=$class->find()->order(['homework_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();
		
		if(!empty($query))
			$this->set('it',$query);
		
		/* Fail Report */
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
	
		if(isset($_REQUEST['view_chart'])) 
		{
			$exam_name=$this->request->data('exam_name');
				
			$exam_id = $_REQUEST['exam_name'];
			$class_id = $_REQUEST['class_name'];
				
			$class_name=$this->request->data('class_name');
			$rs=$conn->execute("SELECT * , count( student_id ) as count
			FROM smgt_marks as m, smgt_users as u
			WHERE m.marks <40
			AND m.exam_id = '$exam_name'
			AND m.Class_id = '$class_name'
			AND m.student_id = u.user_id
			GROUP BY m.subject_id")->fetchAll('assoc');
			
			if(!empty($rs))
				$this->set('report_fail',$rs);
			
			$section_id=$this->request->data['section'];
			$this->set('sec_id',$section_id);
			
			$this->set('exam_id',$exam_id);
			$this->set('class_id',$class_id);
		}
	}
	public function teacherdash()
	{
		$class1 = TableRegistry::get('Smgt_users');
		$class2 = TableRegistry::get('smgt_attendence');
		
		$query1=$class1->find()->where(['role'=>'student']);
		$query2=$class1->find()->where(['role'=>'teacher']);
		$query3=$class1->find()->where(['role'=>'parent']);
		
		$currt_dt=Time::now();
		$current_date=date("Y-m-d", strtotime($currt_dt));
		
		$stud_count=0;
		foreach($query1 as $data)
		{
			$stud_count=$stud_count+1;
		}
		$this->set('stud_count',$stud_count);
		
		$teacher_count=0;
		foreach($query2 as $data)
		{
			$teacher_count=$teacher_count+1;
		}
		$this->set('teacher_count',$teacher_count);
		
		$parent_count=0;
		foreach($query3 as $data)
		{
			$parent_count=$parent_count+1;
		}
		$this->set('parent_count',$parent_count);

		$class=TableRegistry::get('smgt_users');

		$user_session_id=$this->request->session()->read('user_id');

		$query=$class->find()->where(['user_id'=>$user_session_id]);
		
		$user_list=array();
		foreach ($query as $id) 
		{
			$teacher_name=$id['first_name']." ".$id['last_name'];
			
			$user_list['teacher_name'] = $teacher_name;
			$user_list['user_id'] = $id['user_id'];
			
		}
		// var_dump($user_list['teacher_name']);die;
		$class=TableRegistry::get('smgt_time_table');
		$aa=$class->find();
		
		
		$class_route = array();
		foreach($aa as $class_id)
		{	
			$classname=$this->Setting->get_class_id($class_id['class_id']);
			
			$c_id=$class_id['class_id'];
		
			$xyz=$this->Setting->sgmt_day_list($class_id['weekday']);
			
			
			foreach($xyz as $key => $value)
			{
			
				$period = $this->Setting->get_period($c_id,$key);	
				
				foreach($period as $data)
				{
					$subjectname=$this->Setting->get_subject_id($data['subject_id']);
					$class_route[$c_id]['classname']=$classname;
				
					$class_route[$c_id][$key][$data['route_id']] = array('class'=>$c_id,'class_name'=>$classname,'day'=>$value,'subject'=>$subjectname,'teacher'=>$data['teacher_id'],'stime'=>$data['start_time'],'etime'=>$data['end_time'],'route_id'=>$data['route_id']);
				
				}
			}
		}
		if(!empty($class_route))
			$this->set('class_route',$class_route);
		
		
		$xyz=$this->Setting->sgmt_day_list();
		$class_list=$this->Setting->get_user_class_list($user_session_id);
		/* $teachername=$user_list; */
		// var_dump($class_list);die;
		
		$this->set('daywk',$xyz);
		$this->set('class_list',$class_list);
		$this->set('teacher_array',$user_list);	
		
		$query4=$class2->find()->where(['attendence_date'=>$current_date,'role_name'=>'student']);
		
		$attend_count=0;
		foreach($query4 as $data)
		{
			$attend_count=$attend_count+1;
		}
		$this->set('attend_count',$attend_count);
		
		$holiday_table_register=TableRegistry::get('smgt_holiday');
		$select_holiday=$holiday_table_register->find()->order(['holiday_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();
		if(!empty($select_holiday))
			$this->set('holiday_list',$select_holiday);

		$notice_table_register=TableRegistry::get('smgt_notice');
		$select_data_wise_teacher=$notice_table_register->find()->where(['OR' =>[[
												'notice_for' => 'teacher'],
												['notice_for' => 'all']]])->order(['notice_start_date' => 'DESC'])->limit(5)->hydrate(false)->toArray();	
		if(!empty($select_data_wise_teacher))
			$this->set('notice_data',$select_data_wise_teacher);
		
		$news_table_register=TableRegistry::get('smgt_news');
		$select_news = $news_table_register->find()->order(['news_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();
		if(!empty($select_news))
			$this->set('news_list',$select_news);
		
		$event_table_register=TableRegistry::get('smgt_event');
		$select_event = $event_table_register->find()->where(['OR' =>[[
												'event_for' => 'teacher'],
												['event_for' => 'all']]])->order(['event_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();
		if(!empty($select_event))
			$this->set('event_list',$select_event);
		
		$user_session_id=$this->request->session()->read('user_id');
		$class_id = $this->Setting->get_class_list_teacher_id($user_session_id);
		if(!empty($class_id))
		{
			$exam_table_register=TableRegistry::get('smgt_exam');
			$select_exam=$exam_table_register->find()->where(['exam_date >='=>$current_date,'class_id IN'=>$class_id])->order(['exam_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();
			if(!empty($select_exam))
				$this->set('exam_list',$select_exam);
		}
		
		/* Homework Code */
		$get_current_user_id = $this->request->session()->read('user_id');
		
		$class = TableRegistry::get('smgt_homework');

		$class_list = array();	
		$class_list = $this->Setting->get_user_class_list($get_current_user_id);
		$smgt_student_homework = TableRegistry::get('smgt_student_homework');
		
		if(!empty($class_list))
			$homework_query=$class->find()->where(['class_id IN'=>$class_list])->order(['homework_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();

		if(!empty($homework_query))
			$this->set('it',$homework_query);
		
		/* calendar notice_data */	
		$user_id=$this->request->session()->read('user_id');
		$role=$this->Setting->get_user_role($user_id);
		$data=$this->Setting->smgt_notice_show_calender($role);
		$this->set('data',$data);
		
		/* Message Code */
		$class=TableRegistry::get('smgt_message_reciver');
		$query = $class->find()
				->where(['reciver_id' => $user_id])
				->order(['date' => 'DESC'])->limit(5);
				
		$inboxdatashow = array();
		foreach($query as $usr_nm)
		{
			$inboxdatashow[]=array(
			'user_name'=>$this->Setting->get_user_id($usr_nm['sent_id']),
			'image'=>$this->Setting->get_user_image($usr_nm['sent_id']),
			'msg_sub'=>$this->Setting->get_message_sub($usr_nm['message_id']),
			'msg_des'=>$this->Setting->get_message_des($usr_nm['message_id']),
			'date'=>$usr_nm['date'],
			'id'=>$usr_nm['message_id'],
			);
		}	
		if(!empty($inboxdatashow))
			$this->set('inboxdata',$inboxdatashow);
		
		/* Teacher Performance */
		$conn=ConnectionManager::get('default');	
		$rs1=$conn->execute("SELECT count(mark.student_id) as count1,sb.teacher_id FROM smgt_subject as sb 
		INNER JOIN smgt_marks as mark on sb.subid=mark.subject_id
		INNER JOIN smgt_users as u on u.user_id = sb.teacher_id 
		WHERE mark.marks >= 40
		AND sb.teacher_id = ".$user_id."
		group by sb.teacher_id")->fetchAll('assoc');
		
		if(!empty($rs1))
			$this->set('report_teacher',$rs1);
	}
	
	public function studentdash()
	{
		$class1 = TableRegistry::get('Smgt_users');
		$class2 = TableRegistry::get('smgt_attendence');
		
		$query1=$class1->find()->where(['role'=>'student']);
		$query2=$class1->find()->where(['role'=>'teacher']);
		$query3=$class1->find()->where(['role'=>'parent']);
		
		$stud_count=0;
		foreach($query1 as $data)
		{
			$stud_count=$stud_count+1;
		}
		$this->set('stud_count',$stud_count);
		
		$teacher_count=0;
		foreach($query2 as $data)
		{
			$teacher_count=$teacher_count+1;
		}
		$this->set('teacher_count',$teacher_count);
		
		$parent_count=0;
		foreach($query3 as $data)
		{
			$parent_count=$parent_count+1;
		}
		$this->set('parent_count',$parent_count);
		
		$currt_dt=Time::now();
		$current_date=date("Y-m-d", strtotime($currt_dt));
		
		$query4=$class2->find()->where(['attendence_date'=>$current_date,'role_name'=>'student']);
		
		$attend_count=0;
		foreach($query4 as $data)
		{
			$attend_count=$attend_count+1;
		}
		$this->set('attend_count',$attend_count);
		
		$holiday_table_register=TableRegistry::get('smgt_holiday');
		$select_holiday=$holiday_table_register->find()->order(['holiday_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();
		if(!empty($select_holiday))
			$this->set('holiday_list',$select_holiday);

		$notice_table_register=TableRegistry::get('smgt_notice');
		$d1=$notice_table_register->find()->where(['OR' =>[[
												'notice_for' => 'student'],
												['notice_for' => 'all']]])->order(['notice_start_date' => 'DESC'])->limit(5)->hydrate(false)->toArray();	

		if(!empty($d1))
			$this->set('notice_data',$d1);
		
		$news_table_register=TableRegistry::get('smgt_news');
		$select_news = $news_table_register->find()->order(['news_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();
		if(!empty($select_news))
			$this->set('news_list',$select_news);
		
		$event_table_register=TableRegistry::get('smgt_event');
		$select_event = $event_table_register->find()->where(['OR' =>[[
												'event_for' => 'student'],
												['event_for' => 'all']]])->order(['event_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();
		if(!empty($select_event))
			$this->set('event_list',$select_event);
		
		$user_session_id=$this->request->session()->read('user_id');		
		$class_id = $this->Setting->get_user_class($user_session_id);
	
		$exam_table_register=TableRegistry::get('smgt_exam');
		$select_exam=$exam_table_register->find()->where(['exam_date >='=>$current_date,'class_id'=>$class_id])->order(['exam_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();
		if(!empty($select_exam))
			$this->set('exam_list',$select_exam);
		/* Homework Code */
		
		$class_id = 0;
		$get_current_user_id = $this->request->session()->read('user_id');
		$class_id=$this->Setting->get_user_class($get_current_user_id);
		/*
		$class = TableRegistry::get('smgt_homework');
		
		$class_list = array();	
		$class_list = $this->Setting->get_user_class_list($get_current_user_id);
		$smgt_student_homework = TableRegistry::get('smgt_student_homework');
		
		if($class_id)
			$query=$class->find()->where(['class_id'=>$class_id])->order(['homework_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();
		*/
		
		$conn=ConnectionManager::get('default');
		$query = $conn->execute("SELECT * FROM smgt_student_homework as stud_mark 
		LEFT JOIN smgt_homework as mark on stud_mark.homework_id = mark.homework_id
		WHERE stud_mark.student_id =".$get_current_user_id)->fetchAll('assoc');
		
		if(!empty($query))
			$this->set('it',$query);
		
		/* calendar notice_data */	
		$user_id=$this->request->session()->read('user_id');
		$role=$this->Setting->get_user_role($user_id);
		$data=$this->Setting->smgt_notice_show_calender($role);
		$this->set('data',$data);
		
		/* Message Code */
		$class=TableRegistry::get('smgt_message_reciver');
		$query = $class->find()
				->where(['reciver_id' => $user_id])
				->order(['date' => 'DESC'])->limit(5);
				
		$inboxdatashow = array();
		foreach($query as $usr_nm)
		{
			$inboxdatashow[]=array(
			'user_name'=>$this->Setting->get_user_id($usr_nm['sent_id']),
			'image'=>$this->Setting->get_user_image($usr_nm['sent_id']),
			'msg_sub'=>$this->Setting->get_message_sub($usr_nm['message_id']),
			'msg_des'=>$this->Setting->get_message_des($usr_nm['message_id']),
			'date'=>$usr_nm['date'],
			'id'=>$usr_nm['message_id'],
			);
		}	
		if(!empty($inboxdatashow))
			$this->set('inboxdata',$inboxdatashow);
		
		/* Class Routing Code*/
		$class=TableRegistry::get('smgt_time_table');
		$aa=$class->find();

		$user_session_id=$this->request->session()->read('user_id');

		$class_route = array();

		foreach($aa as $class_id)
		{
			$classname=$this->Setting->get_class_id($class_id['class_id']);

			$c_id=$class_id['class_id'];

			$xyz=$this->Setting->sgmt_day_list($class_id['weekday']);
			
			foreach($xyz as $key => $value)
			{

				$period = $this->Setting->get_period($c_id,$key);

				foreach($period as $data)
				{

					$subjectname=$this->Setting->get_subject_id($data['subject_id']);
					$teachername=$this->Setting->get_user_id($data['teacher_id']);

					$class_route[$c_id]['classname']=$classname;

					$class_route[$c_id][$key][$data['route_id']] = array('class'=>$c_id,'day'=>$value,'subject'=>$subjectname,'teacher'=>$teachername,'stime'=>$data['start_time'],'etime'=>$data['end_time'],'route_id'=>$data['route_id']);
				}
			}
		}
		$this->set('class_route',$class_route);

		$xyz=$this->Setting->sgmt_day_list();
		$class_list=$this->Setting->get_user_class_list($user_session_id);
		
		$this->set('daywk',$xyz);
		$this->set('class_list',$class_list);
		
		$class1 = TableRegistry::get('Smgt_users');
		$user_session_id=$this->request->session()->read('user_id');
		
		$class_id=$this->Setting->get_user_class($user_session_id);
		
		$query1=$class1->find()->where(['classname'=>$class_id,'role'=>'student']);
		
		$class_list_id=$this->Setting->get_class_list_by_id($class_id);
		
		$this->set('user_session_id',$user_session_id);
		$this->set('class_list_id',$class_list_id);
		$this->set('it1',$query1);
		
		/* Student Marks Report */
		
		$load_table_exam=TableRegistry::get('smgt_exam');
		$this->getdata_exam=$load_table_exam->find()->hydrate(false)->toArray();
		if(!empty($this->getdata_exam))
			$this->set('exam_data',$this->getdata_exam);
		
		$load_table_subject=TableRegistry::get('smgt_subject');
		$this->getdata_subject=$load_table_subject->find();
		$this->set('subject_data',$this->getdata_subject);
	
		$conn=ConnectionManager::get('default');	
		$rs1=$conn->execute("SELECT *
			FROM smgt_marks as m
			WHERE m.class_id = '$class_id'
			AND m.student_id = '$user_session_id'
			group by m.subject_id")->fetchAll('assoc');
		/* debug($rs1);die; */
		if(!empty($rs1))
			$this->set('report_fail',$rs1);
	}
	
	public function parentdash()
    {
		$class1 = TableRegistry::get('Smgt_users');
		$class2 = TableRegistry::get('smgt_attendence');
		
		$query1=$class1->find()->where(['role'=>'student']);
		$query2=$class1->find()->where(['role'=>'teacher']);
		$query3=$class1->find()->where(['role'=>'parent']);
		
		$stud_count=0;
		foreach($query1 as $data)
		{
			$stud_count=$stud_count+1;
		}
		$this->set('stud_count',$stud_count);
		
		$teacher_count=0;
		foreach($query2 as $data)
		{
			$teacher_count=$teacher_count+1;
		}
		$this->set('teacher_count',$teacher_count);
		
		$parent_count=0;
		foreach($query3 as $data)
		{
			$parent_count=$parent_count+1;
		}
		$this->set('parent_count',$parent_count);
		
		$currt_dt=Time::now();
		$current_date=date("Y-m-d", strtotime($currt_dt));
		
		$query4=$class2->find()->where(['attendence_date'=>$current_date,'role_name'=>'student']);
		
		$attend_count=0;
		foreach($query4 as $data)
		{
			$attend_count=$attend_count+1;
		}
		$this->set('attend_count',$attend_count);
		
		$holiday_table_register=TableRegistry::get('smgt_holiday');
		$select_holiday=$holiday_table_register->find()->order(['holiday_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();	
		if(!empty($select_holiday))
			$this->set('holiday_list',$select_holiday);

		$notice_table_register=TableRegistry::get('smgt_notice');
		$d1=$notice_table_register->find()->where(['OR' =>[[
												'notice_for' => 'parent'],
												['notice_for' => 'all']]])->order(['notice_start_date' => 'DESC'])->limit(5)->hydrate(false)->toArray();	
		if(!empty($d1))
			$this->set('notice_data',$d1);
		
		$news_table_register=TableRegistry::get('smgt_news');
		$select_news = $news_table_register->find()->order(['news_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();	
		if(!empty($select_news))
			$this->set('news_list',$select_news);
		
		$event_table_register=TableRegistry::get('smgt_event');
		$select_event = $event_table_register->find()->where(['OR' =>[[
												'event_for' => 'parent'],
												['event_for' => 'all']]])->order(['event_id'=>'DESC'])->limit(5)->limit(5)->hydrate(false)->toArray();
		if(!empty($select_event))
			$this->set('event_list',$select_event);
		
		$user_session_id=$this->request->session()->read('user_id');
		$class_id = $this->Setting->get_parents_student_id($user_session_id);
		
		if(!empty($class_id))
		{
			$exam_table_register=TableRegistry::get('smgt_exam');
			$select_exam=$exam_table_register->find()->where(['exam_date >='=>$current_date,'class_id IN'=>$class_id])->order(['exam_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();
			if(!empty($select_exam))
				$this->set('exam_list',$select_exam);
		}
		
		/* Homework Code */
		$get_current_user_id = $this->request->session()->read('user_id');
		
		$class = TableRegistry::get('smgt_homework');

		$class_list = array();	
		$class_list = $this->Setting->get_user_class_list($get_current_user_id);
		$smgt_student_homework = TableRegistry::get('smgt_student_homework');
		
		if(!empty($class_list))
			$query=$class->find()->where(['class_id IN'=>$class_list])->order(['homework_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();

		if(!empty($query))
			$this->set('it',$query);
		
		/* calendar notice_data */	
		$user_id=$this->request->session()->read('user_id');
		$role=$this->Setting->get_user_role($user_id);
		$data=$this->Setting->smgt_notice_show_calender($role);
		$this->set('data',$data);
		
		/* Message Code */
		$class=TableRegistry::get('smgt_message_reciver');
		$query = $class->find()
				->where(['reciver_id' => $user_id])
				->order(['date' => 'DESC'])->limit(5);
				
		$inboxdatashow = array();
		foreach($query as $usr_nm)
		{
			$inboxdatashow[]=array(
			'user_name'=>$this->Setting->get_user_id($usr_nm['sent_id']),
			'image'=>$this->Setting->get_user_image($usr_nm['sent_id']),
			'msg_sub'=>$this->Setting->get_message_sub($usr_nm['message_id']),
			'msg_des'=>$this->Setting->get_message_des($usr_nm['message_id']),
			'date'=>$usr_nm['date'],
			'id'=>$usr_nm['message_id'],
			);
		}	
		if(!empty($inboxdatashow))
			$this->set('inboxdata',$inboxdatashow);
		
		/* Class Routing Code*/
		$class=TableRegistry::get('smgt_time_table');
		$aa=$class->find();

		$user_session_id=$this->request->session()->read('user_id');

		$class_route = array();

		foreach($aa as $class_id)
		{
			$classname=$this->Setting->get_class_id($class_id['class_id']);

			$c_id=$class_id['class_id'];

			$xyz=$this->Setting->sgmt_day_list($class_id['weekday']);
			
			foreach($xyz as $key => $value)
			{

				$period = $this->Setting->get_period($c_id,$key);

				foreach($period as $data)
				{

					$subjectname=$this->Setting->get_subject_id($data['subject_id']);
					$teachername=$this->Setting->get_user_id($data['teacher_id']);

					$class_route[$c_id]['classname']=$classname;

					$class_route[$c_id][$key][$data['route_id']] = array('class'=>$c_id,'day'=>$value,'subject'=>$subjectname,'teacher'=>$teachername,'stime'=>$data['start_time'],'etime'=>$data['end_time'],'route_id'=>$data['route_id']);
				}
			}
		}
		// var_dump($class_route);die;
		$this->set('class_route',$class_route);

		$xyz=$this->Setting->sgmt_day_list();
		$class_list=$this->Setting->get_user_class_list($user_session_id);
		$class_list_parent_child=$this->Setting->get_parents_student_id($user_session_id);
		$this->set('daywk',$xyz);
		$this->set('class_list',$class_list);
		$this->set('class_list_parent_child',$class_list_parent_child);
		
		$class1 = TableRegistry::get('Smgt_users');
		$user_session_id=$this->request->session()->read('user_id');
		
		$class_id=$this->Setting->get_user_class($user_session_id);
		
		$query1=$class1->find()->where(['classname'=>$class_id,'role'=>'student']);
		
		$class_list_id=$this->Setting->get_class_list_by_id($class_id);
		
		$this->set('user_session_id',$user_session_id);
		$this->set('class_list_id',$class_list_id);
		$this->set('it1',$query1);
		
		/* Student Fail Report */
				
		$load_table_exam=TableRegistry::get('smgt_exam');
		$this->getdata_exam=$load_table_exam->find()->hydrate(false)->toArray();
		if(!empty($this->getdata_exam))
			$this->set('exam_data',$this->getdata_exam);
		
		$load_table_subject=TableRegistry::get('smgt_subject');
		$this->getdata_subject=$load_table_subject->find();
		$this->set('subject_data',$this->getdata_subject);
		
		$childs = array();
		$childs = $this->Setting->get_child_id($user_session_id);
		
	}
	
	public function supportstaffdash()
    {
		$class1 = TableRegistry::get('Smgt_users');
		$class2 = TableRegistry::get('smgt_attendence');
		
		$query1=$class1->find()->where(['role'=>'student']);
		$query2=$class1->find()->where(['role'=>'teacher']);
		$query3=$class1->find()->where(['role'=>'parent']);
		
		$stud_count=0;
		foreach($query1 as $data)
		{
			$stud_count=$stud_count+1;
		}
		$this->set('stud_count',$stud_count);
		
		$teacher_count=0;
		foreach($query2 as $data)
		{
			$teacher_count=$teacher_count+1;
		}
		$this->set('teacher_count',$teacher_count);
		
		$parent_count=0;
		foreach($query3 as $data)
		{
			$parent_count=$parent_count+1;
		}
		$this->set('parent_count',$parent_count);
		
		$currt_dt=Time::now();
		$current_date=date("Y-m-d", strtotime($currt_dt));
		
		$query4=$class2->find()->where(['attendence_date'=>$current_date,'role_name'=>'student']);
		
		$attend_count=0;
		foreach($query4 as $data)
		{
			$attend_count=$attend_count+1;
		}
		$this->set('attend_count',$attend_count);
		
		$holiday_table_register=TableRegistry::get('smgt_holiday');
		$select_holiday=$holiday_table_register->find()->order(['holiday_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();
		if(!empty($select_holiday))
			$this->set('holiday_list',$select_holiday);

		$notice_table_register=TableRegistry::get('smgt_notice');
		$d1=$notice_table_register->find()->where(['OR' =>[[
												'notice_for' => 'supportstaff'],
												['notice_for' => 'all']]])->order(['notice_start_date' => 'DESC'])->limit(5)->hydrate(false)->toArray();		
		
		if(!empty($d1))
			$this->set('notice_data',$d1);
		
		$news_table_register=TableRegistry::get('smgt_news');
		$select_news = $news_table_register->find()->order(['news_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();
		if(!empty($select_news))
			$this->set('news_list',$select_news);
		
		$event_table_register=TableRegistry::get('smgt_event');
		$select_event = $event_table_register->find()->where(['OR' =>[[
												'event_for' => 'supportstaff'],
												['event_for' => 'all']]])->order(['event_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();
		if(!empty($select_event))
			$this->set('event_list',$select_event);
		
		$exam_table_register=TableRegistry::get('smgt_exam');
		$select_exam=$exam_table_register->find()->where(['exam_date >='=>$current_date])->order(['exam_id'=>'DESC'])->limit(5)->hydrate(false)->toArray();
		if(!empty($select_exam))
			$this->set('exam_list',$select_exam);
		
		/* calendar notice_data */	
		$user_id=$this->request->session()->read('user_id');
		$role=$this->Setting->get_user_role($user_id);
		$data=$this->Setting->smgt_notice_show_calender($role);
		$this->set('data',$data);
		
		/* Message Code */
		$class=TableRegistry::get('smgt_message_reciver');
		$query = $class->find()
				->where(['reciver_id' => $user_id])
				->order(['date' => 'DESC'])->limit(5);
				
		$inboxdatashow = array();
		foreach($query as $usr_nm)
		{
			$inboxdatashow[]=array(
			'user_name'=>$this->Setting->get_user_id($usr_nm['sent_id']),
			'image'=>$this->Setting->get_user_image($usr_nm['sent_id']),
			'msg_sub'=>$this->Setting->get_message_sub($usr_nm['message_id']),
			'msg_des'=>$this->Setting->get_message_des($usr_nm['message_id']),
			'date'=>$usr_nm['date'],
			'id'=>$usr_nm['message_id'],
			);
		}	
		if(!empty($inboxdatashow))
			$this->set('inboxdata',$inboxdatashow);
    }
	
	public function view()
	{
		$this->autoRender=false;
		
		if($this->request->is('ajax'))
		{
			$stud_date = $this->Setting->getfieldname('date_format');
			$header = '';
			$get_data = array();
			
			$id=$_POST['id'];
			$type=$_POST['type'];
			
			if($type == 'notice')
			{
				$header = __('Notice Details');
				$notice_table_register = TableRegistry::get('smgt_notice');		
				$get_data = $notice_table_register->find()->where(['notice_id'=>$id])->hydrate(false)->toArray();
			}
			elseif($type == 'news')
			{
				$header = __('News Details');
				$notice_table_register = TableRegistry::get('smgt_news');		
				$get_data = $notice_table_register->find()->where(['news_id'=>$id])->hydrate(false)->toArray();
			}
			elseif($type == 'holiday')
			{
				$header = __('Holiday Details');
				$notice_table_register = TableRegistry::get('smgt_holiday');		
				$get_data = $notice_table_register->find()->where(['holiday_id'=>$id])->hydrate(false)->toArray();
			}
			elseif($type == 'event')
			{
				$header = __('Event Details');
				$notice_table_register = TableRegistry::get('smgt_event');		
				$get_data = $notice_table_register->find()->where(['event_id'=>$id])->hydrate(false)->toArray();
			}
			elseif($type == 'exam')
			{
				$header = __('Exam Details');
				$exam_table_register = TableRegistry::get('smgt_exam');		
				$get_data = $exam_table_register->find()->where(['exam_id'=>$id])->hydrate(false)->toArray();
			}
			elseif($type == 'homework')
			{
				$header = __('Homework Details');
				$homework_table_register = TableRegistry::get('smgt_homework');		
				$get_data = $homework_table_register->find()->where(['homework_id'=>$id])->hydrate(false)->toArray();
			}
			?>
			
			<script>
			$(document).ready(function() {
				var table = $('.viewdetails').removeAttr('width').DataTable( {
					"columns": [
						{ "width": "15%" },
						null
					  ],
					"order": [[ 0, "desc" ]],
					"paging":   false,
					"ordering": false,
					"searching": false,
					"info":     false
				} );
			});
			</script>
			
			<style>
			.modal-content.dashboard_model .badge.badge-success,
			.modal-content.dashboard_model a.badge:focus, 
			.modal-content.dashboard_model a.badge:hover, 
			.modal-content.dashboard_model a.label:focus
			{
				background-color: #FFFFFF;
				color: #000000;
			}
			.modal-content.dashboard_model .badge {
				font-weight: 600;
				font-size: 20px!important;
				line-height: 10px!important;
				height: 17px;
				padding: 4px;
			}
			table.dataTable{
				border-collapse: collapse;
				margin-top: 0px !important;
			}
			
			table.dataTable.no-footer,
			table.dataTable thead th
			{
				border-bottom: medium none;
			}
			</style>
			<?php
			if($type == 'notice')
				$color = "box-shadow: inset 0px 4px 0px 0px #00a65a;";
			elseif($type == 'news')
				$color = "box-shadow: inset 0px 4px 0px 0px #3c8dbc;";
			elseif($type == 'holiday')
				$color = "box-shadow: inset 0px 4px 0px 0px #dd4b39;";
			elseif($type == 'event')
				$color = "box-shadow: inset 0px 4px 0px 0px #f39c12;";
			elseif($type == 'exam')
				$color = "box-shadow: inset 0px 4px 0px 0px #cb48bb;";
			elseif($type == 'homework')
				$color = "box-shadow: inset 0px 4px 0px 0px #39cccc;";
			else
				$color = "";
			?>
			<div class="modal-header" style="border-bottom:medium none;height: 35px;padding:18px 15px 0px;cursor: move; <?php echo $color;?>">
				<span type="button" class="" data-dismiss="modal"><?php echo $header;?></span>
				<a href="#" class="close-btn-cat badge badge-success pull-right" data-dismiss="modal" style="top:18px;">&times;</a>
			</div>
			<div class="modal-body" style="padding:0px 15px;">
				<?php 
				if(!empty($get_data))
				{
				?>
				
					<table id="examlist" class="table table-striped viewdetails" cellspacing="0" width="100%">
					<thead></thead>
					<tfoot></tfoot>	
					<tbody>					
					<?php 
					if($type == 'notice')
					{
					?>
						<tr>
							<td><?php echo __('Title '); ?> </td>
							<td><?php echo $get_data[0]['notice_title']; ?></td>
						</tr>
						<tr>
							<td><?php echo __('Description '); ?> </td>
							<td><?php echo strlen(($get_data[0]['notice_comment']) > 50)?substr($get_data[0]['notice_comment'],0,50)."...":$get_data[0]['notice_comment'];?></td>
						</tr>
						<tr>
							<td><?php echo __('Start Date '); ?> </td>
							<td><?php echo date($stud_date,strtotime($get_data[0]['notice_start_date'])); ?></td>
						</tr>
						<tr>
							<td><?php echo __('End Date '); ?> </td>
							<td><?php echo date($stud_date,strtotime($get_data[0]['notice_end_date'])); ?></td>
						</tr>
					<?php
					}
					elseif($type == 'news')
					{
					?>
						<tr>
							<td><?php echo __('Title '); ?> </td>
							<td><?php echo $get_data[0]['news_title']; ?></td>
						</tr>
						<tr>
							<td><?php echo __('Description '); ?> </td>
							<td><?php echo strlen(($get_data[0]['news_desc']) > 50)?substr($get_data[0]['news_desc'],0,50)."...":$get_data[0]['news_desc'];?></td>
						</tr>
						<tr>
							<td><?php echo __('Start Date '); ?> </td>
							<td><?php echo date($stud_date,strtotime($get_data[0]['news_start_date'])); ?></td>
						</tr>
						<tr>
							<td><?php echo __('End Date '); ?> </td>
							<td><?php echo date($stud_date,strtotime($get_data[0]['news_end_date'])); ?></td>
						</tr>
					<?php
					}
					elseif($type == 'holiday')
					{
					?>
						<tr>
							<td><?php echo __('Title '); ?> </td>
							<td><?php echo $get_data[0]['holiday_title']; ?></td>
						</tr>
						<tr>
							<td><?php echo __('Description '); ?> </td>
							<td><?php echo strlen(($get_data[0]['description']) > 50)?substr($get_data[0]['description'],0,50)."...":$get_data[0]['description'];?></td>
						</tr>
						<tr>
							<td><?php echo __('Start Date '); ?> </td>
							<td><?php echo date($stud_date,strtotime($get_data[0]['date'])); ?></td>
						</tr>
						<tr>
							<td><?php echo __('End Date '); ?> </td>
							<td><?php echo date($stud_date,strtotime($get_data[0]['end_date'])); ?></td>
						</tr>
					<?php
					}
					elseif($type == 'event')
					{
					?>
						<tr>
							<td><?php echo __('Title '); ?> </td>
							<td><?php echo $get_data[0]['event_title']; ?></td>
						</tr>
						<tr>
							<td><?php echo __('Description '); ?> </td>
							<td><?php echo strlen(($get_data[0]['event_desc']) > 50)?substr($get_data[0]['event_desc'],0,50)."...":$get_data[0]['event_desc'];?></td>
						</tr>
						<tr>
							<td><?php echo __('Start Date '); ?> </td>
							<td><?php echo date($stud_date,strtotime($get_data[0]['start_date'])); ?></td>
						</tr>
						<tr>
							<td><?php echo __('End Date '); ?> </td>
							<td><?php echo date($stud_date,strtotime($get_data[0]['end_date'])); ?></td>
						</tr>
					<?php
					}
					elseif($type == 'exam')
					{
					?>
						<tr>
							<td><?php echo __('Title '); ?> </td>
							<td><?php echo $get_data[0]['exam_name']; ?></td>
						</tr>
						<tr>
							<td><?php echo __('Description '); ?> </td>
							<td><?php echo strlen(($get_data[0]['exam_comment']) > 50)?substr($get_data[0]['exam_comment'],0,50)."...":$get_data[0]['exam_comment'];?></td>
						</tr>
						<tr>
							<td><?php echo __('Start Date '); ?> </td>
							<td><?php echo date($stud_date,strtotime($get_data[0]['exam_date'])); ?></td>
						</tr>
						<tr>
							<td><?php echo __('End Date '); ?> </td>
							<td><?php echo date($stud_date,strtotime($get_data[0]['exam_end_date'])); ?></td>
						</tr>
					<?php
					}
					elseif($type == 'homework')
					{
					?>
						<tr>
							<td><?php echo __('Title '); ?> </td>
							<td><?php echo $get_data[0]['title']; ?></td>
						</tr>
						<tr>
							<td><?php echo __('Description '); ?> </td>
							<td><?php echo strlen(($get_data[0]['content']) > 50)?substr($get_data[0]['content'],0,50)."...":$get_data[0]['content'];?></td>
						</tr>
						<tr>
							<td><?php echo __('Created Date '); ?> </td>
							<td><?php echo date($stud_date,strtotime($get_data[0]['created_date'])); ?></td>
						</tr>
						<tr>
							<td><?php echo __('Submission Date '); ?> </td>
							<td><?php echo date($stud_date,strtotime($get_data[0]['submission_date'])); ?></td>
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
			
		<?php
		}
	}
}
?>