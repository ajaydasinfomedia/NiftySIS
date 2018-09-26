<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use  Cake\Utility\Xml;
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;
use Cake\Network\Http\Client;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Mailer\Email;
use Cake\Datasource\ConnectionManager;

class SettingComponent extends Component
{
	public function getcontroller()
	{
		$controller = '';
		$controller = $this->_registry->getController();
		$demo=$controller->name;
		return $controller; 
	}
	
    public function setting($field_name, $field_value)
    {			
		$class=TableRegistry::get('smgt_setting');		
		$query = $class->find('all',array('conditions' => array('field_name'=>$field_name)))->toArray();		
		
		if (!empty($query))
		{		
			$i = 0;
			foreach ($query as $id) 
			{

				$id['field_value'] = $field_value;
			
				if($class->save($id))
				{
					$i = 1;
				}
			}
			if($i == 1)
				return "Updated Successfully";	
			exit;
		}
		else
		{ 
			$class=TableRegistry::get('smgt_setting');
			$abc=$class->newEntity();
			
			$abc['field_name']=$field_name;
			$abc['field_value']=$field_value;
			
			if($class->save($abc))
			{
				return "Added Successfully";	
			}
		}
    }
	public function getfieldname($field_name)
    {		
		$id = '';
		
		$class=TableRegistry::get('smgt_setting');		
		$query = $class->find('all',array('conditions' => array('field_name'=>$field_name)));
		
		if (!$query->isEmpty())
		{		
			foreach ($query as $id) 
			{
				$id=$id['field_value'];
				return $id;
			}
		}
	}
	public function section_name($id)
	{
		$query = '';
		
		$class=TableRegistry::get('class_section');		
		$query = $class->find('all',array('conditions' => array('class_section_id' => $id)))->select("section_name")->hydrate(false)->toArray();		
		if(!empty($query))
			return $query[0]['section_name'];
	}
	public function get_user_id($id)
    {		
		$id1 = ''; 
		
		$class=TableRegistry::get('smgt_users');	
		$query = $class->find('all',array('conditions' => array('user_id'=>$id)));
		
		foreach ($query as $id) 
		{
			$id1=$id['first_name']." ".$id['last_name'];
			return $id1;
		}
	}
	public function get_user_image($id)
    {	
		$id1 = ''; 
		
		$class=TableRegistry::get('smgt_users');		
		$query = $class->find('all',array('conditions' => array('user_id'=>$id)));
		
		foreach ($query as $id) 
		{
			$id1=$id['image'];
			return $id1;
		}
	}
	public function get_user_relation($id)
    {	
		$id1 = ''; 
		
		$class=TableRegistry::get('smgt_users');		
		$query = $class->find('all',array('conditions' => array('user_id'=>$id)));
		
		foreach ($query as $id) 
		{
			$id1=$id['relation'];
			return $id1;
		}
	}
	public function get_user_class($id)
    {	
		$id1 = ''; 
		
		$class=TableRegistry::get('smgt_users');		
		$query = $class->find('all',array('conditions' => array('user_id'=>$id)));
		
		foreach ($query as $id) 
		{
			$id1=$id['classname'];
		}
		if($id1 != '')
			return $id1;
	}
	public function get_user_section_id($id)
    {	
		$id1 = '';
		
		$class=TableRegistry::get('smgt_users');		
		$query = $class->find('all',array('conditions' => array('user_id'=>$id)));
		
		foreach ($query as $id) 
		{
			$id1=$id['classsection'];
			return $id1;
		}
	}
	public function get_roll_no($id)
    {	
		$result = ''; 
		
		$class=TableRegistry::get('smgt_users');		
		$query = $class->find('all',array('conditions' => array('user_id'=>$id)));
		
		foreach ($query as $id) 
		{
			$result=$id['roll_no'];
			return $result;
		}
	}
	public function get_user_subject($user_id)
    {
		$result = ''; 
		
		$class=TableRegistry::get('smgt_subject');		
		$query = $class->find('all',array('conditions' => array('teacher_id'=>$user_id)));
		
		foreach ($query as $id) 
		{
			$result=$id['sub_name'];
			return $result;
		}
	}
	public function get_user_email_id($id)
    {	
		$id1 = ''; 
		
		$class=TableRegistry::get('smgt_users');		
		$query = $class->find('all',array('conditions' => array('user_id'=>$id)));
		
		foreach ($query as $id) 
		{
			$id1=$id['email'];
			return $id1;
		}
	}
	public function smgt_get_all_replies($id)
	{
		$query = ''; 
		
		$class=TableRegistry::get('smgt_message_replies');		
		$query = $class->find('all',array('conditions' => array('message_id'=>$id)));		
		return $query;
	}
	
	public function get_user_mobile_no($id)
    {	
		$mobile = ''; 
		
		$class=TableRegistry::get('smgt_users');		
		$query = $class->find('all',array('conditions' => array('user_id'=>$id)));
		
		foreach ($query as $id) 
		{
			$mobile=$id['mobile_no'];
		}
		if(isset($mobile))
			return $mobile;
	}
	
	public function get_username_id($username)
    {	
		$user_id = ''; 
		
		$class=TableRegistry::get('smgt_users');	
		$query = $class->find('all',array('conditions' => array('username'=>$username)));
		
		foreach ($query as $id) 
		{
			$user_id=$id['user_id'];
		}
		if(isset($user_id))
			return $user_id;
		else
			?><script>alert('Wrong Username');</script><?php
	}
	
	public function get_email_userid($email)
    {		
		$user_id = ''; 
		
		$class=TableRegistry::get('smgt_users');		
		$query = $class->find('all',array('conditions' => array('email'=>$email)));
		
		foreach ($query as $id) 
		{
			$user_id=$id['user_id'];
		}
		if($user_id != '')
			return $user_id;
		else
			?><script>alert('Wrong Email');</script><?php
	}
	
	public function get_user_password($user_id)
    {	
		$password = ''; 
		
		$class=TableRegistry::get('smgt_users');		
		$query = $class->find('all',array('conditions' => array('user_id'=>$user_id)));
		
		foreach($query as $id) 
		{
			$password=$id['password'];
		}
		if(isset($password))
			return $password;
	}
	public function get_user_roll_no($id=0)
    {
		$class=TableRegistry::get('smgt_users');
		
		if($id)
		{
			$query = $class->find('all',array('conditions' => array('user_id'=>$id)));
			
			foreach ($query as $id) 
			{
				$id1=$id['roll_no'];
				return $id1;
			}
		}
	}
	public function get_user_role($user_id)
    {		
		$role = ''; 
		
		$class=TableRegistry::get('smgt_users');		
		$query = $class->find('all',array('conditions' => array('user_id'=>$user_id)));
		
		foreach ($query as $id) 
		{
			$role=$id['role'];
		}
		if(isset($role))
			return $role;
	}
	
	public function check_user($username,$email)
    {	
		$id = ''; 
		
		$class=TableRegistry::get('smgt_users');		
		$result = $class->find('all',array('conditions' => array('username'=>$username,'email'=>$email)));
		
		foreach ($result as $id) 
		{
			$id=$id['user_id'];
		}
		if(isset($id))
			return $id;
	}
	
	public function get_teacher_subject($id)
    {	
		$id1 = ''; 
		
		$class=TableRegistry::get('smgt_subject');		
		$query = $class->find('all',array('conditions' => array('teacher_id'=>$id)));
		
		foreach ($query as $id) 
		{
			$id1=$id['sub_name'];
			return $id1;
		}
	}
	public function get_class_id($id=0)
    {	
		if($id)
		{	
			$class=TableRegistry::get('classmgt');		
			$query = $class->find('all',array('conditions' => array('class_id'=>$id)));
			
			foreach ($query as $id) 
			{
				return $id['class_name'];
			}
		}
	}
	
	public function get_section_name($id)
    {	
		$class=TableRegistry::get('class_section');		
		$query = $class->find('all',array('conditions' => array('class_section_id'=>$id)));
		
		foreach ($query as $id) 
		{
			return $id['section_name'];
		}
	}
	
	
	public function get_class_list()
	{	
		$class_list = array();
		
		$class=TableRegistry::get('classmgt');		
		$query = $class->find('all');
		
		$x=0;
		foreach ($query as $id) 
		{
			$class_list[$x]['class_name'] = $id['class_name'];
			$class_list[$x]['class_id'] = $id['class_id'];
			$x++;
		}
		if(!empty($class_list))
		{
			return $class_list;
		}
		else
		{
			return $class_list;
		}
	}

	public function get_class_list_by_id($class_id)
	{	
		$class_list = array();
		
		$class=TableRegistry::get('classmgt');		
		$query = $class->find('all')->where(['class_id'=>$class_id]);
		
		$x=0;
		foreach ($query as $id) 
		{
			$class_list[$x]['class_name'] = $id['class_name'];
			$class_list[$x]['class_id'] = $id['class_id'];
			$x++;
		}
		if(isset($class_list))
			return $class_list;
	}
	
	public function get_teach_list($id)
	{		
		$class_list = array();
		
		$class=TableRegistry::get('classmgt');		
		$query = $class->find('all')->where(['user_id'=>$id]);
		
		$x=0;
		foreach ($query as $id) 
		{
			$class_list[$x]['class_name'] = $id['class_name'];
			$class_list[$x]['class_id'] = $id['class_id'];
			$x++;
		}
		return $class_list;
	}

	public function get_class_list_user_id($id)
	{		
		$class_list = array();
		
		$class=TableRegistry::get('smgt_users');		
		$query = $class->find('all')->where(['user_id'=>$id]);
	
		foreach ($query as $id) 
		{
			$class_list = $id['classname'];
		}
		return $class_list;
	}
	public function get_class_list_teacher_id($id)
	{		
		$class_list = array();
		
		$class=TableRegistry::get('smgt_subject');		
		$query = $class->find('all')->where(['teacher_id'=>$id]);
	
		foreach ($query as $id) 
		{
			$class_list[] = $id['class_id'];
		}
		if(!empty($class_list))
		{
			$class_list = array_unique($class_list);
			return $class_list;
		}
	}
	public function get_user_list()
	{		
		$class_list = array();
		
		$class=TableRegistry::get('smgt_users');		
		$query = $class->find('all',array('conditions' => array('role'=>'teacher')));
		
		$x=0;
		foreach ($query as $id) 
		{
			$teacher_name=$id['first_name']." ".$id['last_name'];
			
			$user_list[$x]['teacher_name'] = $teacher_name;
			$user_list[$x]['user_id'] = $id['user_id'];
			$x++;
		}
		
		if(!empty($user_list))
		{
			return $user_list;
		}
	}

	public function get_subject_id($id)
    {
		$class=TableRegistry::get('smgt_subject');		
		$query = $class->find('all',array('conditions' => array('subid'=>$id)));
		
		foreach ($query as $id) 
		{
			return $id['sub_name'];
		}
	}
	
	public function get_teacher_by_cls_sub($sub_name)
    {
		$id = '';
		
		$class=TableRegistry::get('smgt_subject');		
		$query = $class->find('all',array('conditions' => array('sub_name'=>$sub_name)));
		
		foreach ($query as $id) 
		{
			return $id['teacher_id'];
		}
	}
	
	public function getimage($filename)
    {	
		$imgname = '';
		
		$img=$filename;	
		$u="img";
		$fp=WWW_ROOT.$u;	
		$imgname=$img['name'];
		$fpp=$fp.'/'.$imgname;
		
		if(move_uploaded_file($img['tmp_name'],$fpp))
		{					
		}
		return $imgname;		
	}
	
	public function getdoc($filename)
    {	
		$docname = '';
		
		$doc=$filename;	
		$u="doc";
		$fp=WWW_ROOT.$u;
		$docname=$doc['name'];
		$fpp=$fp.'/'.$docname;

		if(move_uploaded_file($doc['tmp_name'],$fpp))
		{					
		}
		return $docname;		
	}
	
	public function sgmt_day_list()
	{
		$day_list = '';
		
		$day_list = array('1' => __('Monday'),
				'2' => __('Tuesday'),
				'3' => __('Wednesday'),
				'4' => __('Thursday'),
				'5' => __('Friday'),
				'6' => __('Saturday'),
				'7' => __('Sunday'));
		return $day_list;	
	}
	
	public function get_period($cls_id,$week)
	{
		$query = '';
		
		$class=TableRegistry::get('smgt_time_table');			
		$query = $class->find('all',array('conditions' => array('class_id' => $cls_id, 'weekday' => $week)))->hydrate(false)->toArray();
		return $query;
	}
	public function login($user=null,$pass=null)
	{
		
		$class=TableRegistry::get('smgt_users');	
		// $query = $class->find('all',array('conditions' => array('username' => $user)));
		$query = $class->find()->where(['OR' =>[[
												'username' => $user],
												['email' => $user]]]);
		
		$db_pass = '';
		$chk_pass = '';
		
		foreach($query as $data)
		{
			$db_pass = $data['password'];
		}
		if($db_pass != '')
			$chk_pass = (new DefaultPasswordHasher)->check($pass,$db_pass);

		$ty=array('','');
		if($chk_pass != '')
		{
			foreach($query as $data)
			{
				$ty[0]=$data['role'];
				$ty[1]=$data['user_id'];
				$ty[2]=$data['image'];
			}
		}
		if(!empty($ty))
			return $ty;
	}
	public function logout()
	{
		$session = $this->request->session();		
		if($session)
			$session->destroy();
	}
	public function changepassword()
	{
		$class=TableRegistry::get('smgt_users');
		
		$pass = "";			
		$session = $this->request->session();		
		$user_id=$session->read('user_id');
		
		$query = $class->find('all',array('conditions' => array('user_id' => $user_id)));
		
		foreach($query as $data)
		{
			$pass=$data['password'];	
		}
		return $pass;		
	}
	
	public function grade_mark($mark)
	{
		$grade = '';
		
		$class=TableRegistry::get('smgt_grade');		
		$query = $class->find();
		
		foreach($query as $mark_nm)
		{
			if($mark >= $mark_nm['mark_from']  && $mark <= $mark_nm['mark_upto'])
			{
				$grade=$mark_nm['grade_id'];
			}
		}
		if(isset($grade))
			return $grade;
	}

	public function check_mark_id($exam_id,$class_id,$sub_id,$user_id)
	{	
		$d = 0;
		
		$class=TableRegistry::get('smgt_marks');		
		$query = $class->find('all',array('conditions' => array('exam_id' => $exam_id, 'class_id' => $class_id, 'subject_id' => $sub_id, 'student_id' => $user_id)));
		
		foreach($query as $data)
		{ 			
			$d=$data['mark_id'];
		}
		if($d){
			return $d;
		}
	}
	
	public function check_mark_detail($exam_id,$class_id,$sub_id,$user_id)
	{	
		$query = '';
		
		$class=TableRegistry::get('smgt_marks');	
		$query = $class->find('all',array('conditions' => array('exam_id' => $exam_id, 'class_id' => $class_id, 'subject_id' => $sub_id, 'student_id' => $user_id)));
		
		if(isset($query)){
			return $query;
		}
	}
	public function check_mark_detail_result_report($exam_id,$class_id,$sub_id,$user_id)
	{
		$query = array();
		$class=TableRegistry::get('smgt_marks');	
		$query = $class->find()
				->where(['exam_id' => $exam_id, 'class_id' => $class_id, 'subject_id' => $sub_id, 'student_id' => $user_id])
				->hydrate(false)->toArray();		
		return $query;	
	}
	public function exam()
	{
		$tbl_exam = '';
		
		$class=TableRegistry::get('smgt_exam');
		$tbl_exam=$class->find();		
		return $tbl_exam;
	}
	public function get_exam_name($exam_id)
	{
		$id = '';
		
		$class=TableRegistry::get('smgt_exam');
		$tbl_exam=$class->find()->where(['exam_id'=>$exam_id]);
		
		foreach ($tbl_exam as $id) 
		{
			return $id['exam_name'];
		}
	}
	public function get_exam_month_year($exam_id)
	{
		$id = '';
		
		$class=TableRegistry::get('smgt_exam');
		$tbl_exam=$class->find()->where(['exam_id'=>$exam_id]);
		
		foreach ($tbl_exam as $id) 
		{
			return date('F - Y', strtotime($id['exam_end_date']));
		}
	}
	public function get_subject($class_id)
	{
		$tbl_sub = '';
		
		$class=TableRegistry::get('smgt_subject');
		$tbl_sub=$class->find('all',array('conditions' => array('class_id' => $class_id)));	
		return $tbl_sub;
	}
	
	public function get_subject_count($class_id)
	{
		$class=TableRegistry::get('smgt_subject');
		$tbl_sub1=$class->find('all',array('conditions' => array('class_id' => $class_id)));
		
		$t=0;
		foreach($tbl_sub1 as $data)
		{
			$t=$t+1;
		}
		return $t;
	}
	public function user_mark_count($user_id=0,$class_id=0)
	{	
		$query = 0;
		
		$class=TableRegistry::get('smgt_marks');		
		$query = $class->find('all',array('conditions' => array('student_id' => $user_id,'class_id' => $class_id)))->count();		
		return $query;
	}
	public function get_mark($exam_id,$class_id,$sub_id,$user_id)
	{	
		$d = 0;
		
		$class=TableRegistry::get('smgt_marks');	
		$query = $class->find('all',array('conditions' => array('exam_id' => $exam_id, 'class_id' => $class_id, 'subject_id' => $sub_id, 'student_id' => $user_id)));
		
		foreach($query as $data)
		{ 			
			$d=$data['marks'];
		}
		if(isset($d))
			return $d;
	}
	
	public function grade_name($grade_id)
	{
		$d = '';
		
		$class=TableRegistry::get('smgt_grade');	
		$query = $class->find('all',array('conditions' => array('grade_id' => $grade_id)));
	
		foreach($query as $data)
		{ 			
			$d=$data['grade_name'];
		}
		if(isset($d))
		{
			return $d;
		}
	}
	
	public function grade_comment($grade_id)
	{
		$d = '';
		
		$class=TableRegistry::get('smgt_grade');
		$query = $class->find('all',array('conditions' => array('grade_id' => $grade_id)));
	
		foreach($query as $data)
		{ 			
			$d=$data['grade_comment'];
		}
		if(isset($d))
		{
			return $d;
		}
	}
	public function grade_point($grade_id)
	{
		$d = 0;
		
		$class=TableRegistry::get('smgt_grade');
		$query = $class->find('all',array('conditions' => array('grade_id' => $grade_id)));
	
		foreach($query as $data)
		{ 			
			$d=$data['grade_point'];
		}
		if(isset($d))
		{
			return $d;
		}
	}
	public function check_attendence($class_id,$date)
	{
		$result = '';
		
		$class=TableRegistry::get('smgt_attendence');
		$result = $class->find('all',array('conditions' => array('class_id'=>$class_id,'attendence_date' => $date)))->hydrate(false)->toArray();
		if(!empty($result))
			return $result;
	}
	public function check_attendence1($user_id,$class_id,$date)
	{
		$result = '';
		
		$class=TableRegistry::get('smgt_attendence');
		$result = $class->find('all',array('conditions' => array('user_id'=>$user_id,'class_id'=>$class_id,'attendence_date' => $date)))->hydrate(false)->toArray();
		if(!empty($result))
			return $result;
	}
	
	public function smgt_get_all_user_notice()
	{
		$user_data = array();
		
		$class1 = TableRegistry::get('smgt_users');		
		$query3 = $class1->find()->where(['role !='=>'admin']);
		
		foreach($query3 as $all_user_data)
		{
			$user_data[]=$all_user_data['user_id'];
		}
		return $user_data;		
	}
	
	public function smgt_get_user_notice($role,$class_id,$class_section_id = 0)
	{
		$class1 = TableRegistry::get('smgt_users');
		$class2 = TableRegistry::get('child_tbl');
		$user_data = array();
		$userdata = '';
		
		if($role == 'parent' )
		{		
			$new =array();
			
			if($class_id == 'all')
			{				
				$query3=$class1->find()->where(['role'=>'parent']);
				
				foreach($query3 as $parent_data)
				{
					$user_data[]=$parent_data['user_id'];
				}
				$userdata=$user_data;				
			}
			else
			{
				$userdata1=$class1->find()->where(['role'=>'student','classname'=>$class_id]);
				
				foreach($userdata1 as $users)
				{
					$child = $users['user_id'];
					
					$userdt=$class2->find()->where(['child_id'=>$child]);
					foreach($userdt as $data)
					{
						$user_data[]=$data['child_parent_id'];
					}
				}			
				$userdata=$user_data;	
			}
		}
		else if($role == "supportstaff")
		{
				$query3=$class1->find()->where(['role'=>'supportstaff']);
				
				foreach($query3 as $staff_data)
				{
					$user_data[]=$staff_data['user_id'];
				}
				$userdata=$user_data;
		}
		else if($role == 'student')
		{
			if($class_id == 'all')
			{
				$query3=$class1->find()->where(['role'=>'student']);
				
				foreach($query3 as $stud_data)
				{
					$user_data[]=$stud_data['user_id'];
				}
				$userdata=$user_data;
			}
			else
			{
				$query3=$class1->find()->where(['role'=>'student','classname'=>$class_id,'classsection'=>$class_section_id]);
				
				foreach($query3 as $stud_data)
				{
					$user_data[]=$stud_data['user_id'];
				}
				$userdata=$user_data;
			}
		}
		else 
		{
			if($class_id == 'all')
			{
				$query3=$class1->find()->where(['role'=>'teacher']);
				
				foreach($query3 as $teach_data)
				{
					$user_data[]=$teach_data['user_id'];
				}
				$userdata=$user_data;
			}
			else
			{
				$query3=$class1->find()->where(['role'=>'teacher','classname'=>$class_id]);
					
				foreach($query3 as $teach_data)
				{
					$user_data[]=$teach_data['user_id'];
				}
				$userdata=$user_data;
			}
		}			
		return $userdata;
	}
	
	public function get_user_class_section($role,$class_id=0,$class_section_id=0)
	{
		$smgt_users = TableRegistry::get('smgt_users');
		$user_data = array();	
		$or = array();
		
		if($role == 'student' || $role == 'teacher')
		{
			$or["role"] = (!empty($role) && $role != "All" )?$role:NULL;
			$or["classname"] = (!empty($class_id) && $class_id != "All")?$class_id:NULL;
			$or["classsection"] = (!empty($class_section_id) && $class_section_id != 0)?$class_section_id:NULL;

			$keys = array_keys($or,"");	
			foreach ($keys as $k)
			{unset($or[$k]);}
			
			$result = $smgt_users->find()->where([$or])->hydrate(false)->toArray();
			
			foreach($result as $retrive_data)
			{
				$user_data[] = $retrive_data['user_id'];
			}
			$userdata = $user_data;			
		}
		return array_unique($userdata);
	}
	
	public function smgt_get_event_mailer($role,$class_id,$class_section_id=0)
	{	
		$smgt_users = TableRegistry::get('smgt_users');
		$child_tbl = TableRegistry::get('child_tbl');
		$user_data = array();
		
		$or = array();
		$userdata = '';
		
		if($role == 'student')
		{
			$or["role"] = (!empty($role) && $role != "All" )?$role:NULL;
			$or["classname"] = (!empty($class_id) && $class_id != "All")?$class_id:NULL;
			$or["classsection"] = (!empty($class_section_id) && $class_section_id != 0)?$class_section_id:NULL;

			$keys = array_keys($or,"");	
			foreach ($keys as $k)
			{unset($or[$k]);}

			$result = $smgt_users->find()->where([$or])->hydrate(false)->toArray();
			foreach($result as $retrive_data)
			{
				$user_data[]=$retrive_data['user_id'];
			}
			$userdata=$user_data;			
		}
		else
		{
			if($role == 'teacher')
			{
				$userdata=$smgt_users->find()->where(['role'=>'teacher'])->hydrate(false)->toArray();

				foreach($userdata as $users)
				{
						$user_data[]=$users['user_id'];
				}				
				$userdata=$user_data;
			}
			elseif($role == 'parent')
			{
				if($class_id == 'All')
					$userdata=$smgt_users->find()->where(['role'=>'student'])->hydrate(false)->toArray();
				else
					$userdata=$smgt_users->find()->where(['role'=>'student','classname'=>$class_id,'classsection'=>$class_section_id])->hydrate(false)->toArray();

				foreach($userdata as $users)
				{
					$child = $users['user_id'];
					
					$userdt=$child_tbl->find()->where(['child_id'=>$child]);
					foreach($userdt as $data)
					{
						$user_data[]=$data['child_parent_id'];
					}
				}				
				$userdata=$user_data;		
			}		
			elseif($role == 'supportstaff')
			{
				$userdata=$smgt_users->find()->where(['role'=>'supportstaff'])->hydrate(false)->toArray();

				foreach($userdata as $users)
				{
						$user_data[]=$users['user_id'];
				}				
				$userdata=$user_data;
			}			
			elseif($class_id == "All")
			{
				$or["role"] = (!empty($role) && $role != "All" )?$role:NULL;
				$or["classname"] = (!empty($class_id) && $class_id != "All")?$class_id:NULL;

				$keys = array_keys($or,"");	
				foreach ($keys as $k)
				{unset($or[$k]);}

				$result = $smgt_users->find()->where([$or])->hydrate(false)->toArray();
				foreach($result as $retrive_data)
				{
					$user_data[]=$retrive_data['user_id'];
				}
				$userdata=$user_data;
			}
			else
			{
				$userdata=$smgt_users->find()->where(['role'=>'student','classname'=>$class_id])->hydrate(false)->toArray();

				foreach($userdata as $users)
				{
					$child = $users['user_id'];
					
					$userdt=$child_tbl->find()->where(['child_id'=>$child]);
					foreach($userdt as $data)
					{
						$user_data[]=$data['child_parent_id'];
					}
				}				
				$userdata=$user_data;				
			}
		}
		return array_unique($userdata);
	}
	
	public function get_inbox_message($user_id)
	{
		$query = '';
		
		$class=TableRegistry::get('smgt_message_reciver');		
		$query = $class->find('all',array('conditions' => array('reciver_id' => $user_id)));	
		return $query;
	}
	
	public function get_message_sub($msg_id)
	{
		$class=TableRegistry::get('smgt_message_sent');		
		$query = $class->find('all',array('conditions' => array('message_id' => $msg_id)));
		$sub_data="";
		foreach($query as $data)
		{
			$sub_data=$data['subject'];
		}		
		return $sub_data;
	}
	
	public function get_message_des($msg_id)
	{
		$class=TableRegistry::get('smgt_message_sent');		
		$query = $class->find('all',array('conditions' => array('message_id' => $msg_id)));
		$sub_data="";
		foreach($query as $data)
		{
			$sub_data=$data['message_body'];
		}		
		return $sub_data;
	}
	
	public function get_send_message($user_id)
	{
		$query = '';
		
		$class=TableRegistry::get('smgt_message_sent');		
		$query = $class->find('all',array('conditions' => array('sender_id' => $user_id,'deleted'=>0)));	
		return $query;
	}
	
	public function smgt_count_reply_item($id)
	{
		$query = '';
		
		$class=TableRegistry::get('smgt_message_replies');		
		$query = $class->find('all',array('conditions' => array('message_id' => $id)));		
		return $query;
	}
	
	function smgt_admininbox_pagination($totalposts,$p,$lpm1,$prev,$next)
	{
		$adjacents = 1;
		$page_order = "";
		$pagination = "";
		$form_id = 1;
		if(isset($_REQUEST['form_id']))
			$form_id=$_REQUEST['form_id'];
		if(isset($_GET['orderby']))
		{
			$page_order='&orderby='.$_GET['orderby'].'&order='.$_GET['order'];
		}
		if($totalposts > 1)
		{
			$pagination .= '<div class="btn-group">';
			
			if ($p > 1)
				$pagination.= "<a href=\"?page=message&tab=inbox&pg=$prev\" class=\"btn btn-default\"><i class=\"fa fa-angle-left\"></i></a> ";
			else
				$pagination.= "<a class=\"btn btn-default disabled\"><i class=\"fa fa-angle-left\"></i></a> ";

			if ($p < $totalposts)
				$pagination.= " <a href=\"?page=message&tab=inbox&pg=$next\" class=\"btn btn-default next-page\"><i class=\"fa fa-angle-right\"></i></a>";
			else
				$pagination.= " <a class=\"btn btn-default disabled\"><i class=\"fa fa-angle-right\"></i></a>";
			$pagination.= "</div>\n";
		}
		return $pagination;
	}
	public function show_attendance($atten_date)
	{
		$result = '';
		
		$class=TableRegistry::get('smgt_attendence');
		$result = $class->find('all',array('conditions' => array('attendence_date'=>$atten_date,'role_name'=>'teacher')));
		return $result;
	}
	public function show_attendance1($atten_date,$usr_id)
	{
		$result = '';
		
		$class=TableRegistry::get('smgt_attendence');		
		$result = $class->find('all',array('conditions' => array('attendence_date'=>$atten_date,'user_id'=>$usr_id,'role_name'=>'teacher')));
		return $result;
	}
	public function smgt_get_student_parent_id($user_id)
	{
		$parent_id = '';
		
		$class2 = TableRegistry::get('child_tbl');
		$userdt=$class2->find()->where(['child_id'=>$user_id]);
		
		foreach($userdt as $data)
		{
			$parent_id = $data['child_parent_id'];		
		}
		if(isset($parent_id))
			return $parent_id;
	}
	
	public function get_child_id($user_id)
	{
		$parent_id = array();
		
		$class2 = TableRegistry::get('child_tbl');
		$userdt=$class2->find()->where(['child_parent_id'=>$user_id]);
		foreach($userdt as $data)
		{
			$parent_id[]=$data['child_id'];
		}
		if(!empty($parent_id))
			return $parent_id;
	}
	
	public function check_subject_attendence($class_id,$date,$subject_id)
	{
		$result = '';
		
		$class=TableRegistry::get('smgt_sub_attendance');		
		$result = $class->find('all',array('conditions' => array('class_id'=>$class_id,'attendance_date' => $date,'sub_id'=>$subject_id)))->hydrate(false)->toArray();
		return $result;
	}
	
	public function check_subject_attendence1($user_id,$class_id,$date,$subject_id)
	{
		$result = '';
		
		$class=TableRegistry::get('smgt_sub_attendance');		
		$result = $class->find('all',array('conditions' => array('user_id'=>$user_id,'class_id'=>$class_id,'attendance_date' => $date,'sub_id'=>$subject_id)));
		return $result;
	}
	
	public function smgt_view_student_attendance($start_date,$end_date,$user_id)
	{
		$result = '';

	/* 	$class=TableRegistry::get('smgt_attendence');		
		$result = $class->find()->where(['user_id'=>$user_id,'role_name'=>'student','attendence_date BETWEEN :start AND :end'])
									->bind(':start', $start_date, 'date')
									->bind(':end',   $end_date, 'date'); */
									
		$conn=ConnectionManager::get('default');
		$result = $conn->execute("select * from smgt_attendence 
		where user_id = $user_id AND attendence_date >= '$start_date' AND attendence_date <= '$end_date'")->fetchAll('assoc');
		
		return $result;
	}
	
	public function smgt_view_subject_attendance($start_date,$end_date,$user_id,$subject_id)
	{
		$result = '';
		
		/* $class=TableRegistry::get('smgt_sub_attendance');		
		$result = $class->find()->where(['user_id'=>$user_id,'role_name'=>'student','attendance_date BETWEEN :start AND :end'])
									->bind(':start', $start_date, 'date')
									->bind(':end',   $end_date, 'date'); */
		
		$conn=ConnectionManager::get('default');
		$result = $conn->execute("select * from smgt_sub_attendance 
		where user_id = $user_id AND sub_id = $subject_id AND attendance_date >= '$start_date' AND attendance_date <= '$end_date'")->fetchAll('assoc');
		return $result;
	}
	
	public function check_stud_monthly_attendence($student,$month,$year)
	{
		$result = '';
		
		$conn=ConnectionManager::get('default');
		$result = $conn->execute("
		select *, 
		(select count(status) from smgt_attendence where status='Present' AND user_id = $student AND MONTH(attendence_date) = '$month' AND YEAR(attendence_date) = '$year') as cntpresent,
		(select count(status) from smgt_attendence where status='Absent' AND user_id = $student AND MONTH(attendence_date) = '$month' AND YEAR(attendence_date) = '$year') as cntabsent,
		(select count(status) from smgt_attendence where status='Late' AND user_id = $student AND MONTH(attendence_date) = '$month' AND YEAR(attendence_date) = '$year') as cntlate
		from smgt_attendence where user_id = $student AND MONTH(attendence_date) = '$month' AND YEAR(attendence_date) = '$year'")->fetchAll('assoc');
		
		return $result;
	}
	
	public function smgt_view_teacher_attendance($start_date,$end_date,$user_id)
	{
		$result = '';
		
		/* $class=TableRegistry::get('smgt_attendence');		
		$result = $class->find()->where(['user_id'=>$user_id,'role_name'=>'teacher','attendence_date BETWEEN :start AND :end'])
									->bind(':start', $start_date, 'date')
									->bind(':end',   $end_date, 'date'); */
		
		$conn=ConnectionManager::get('default');
		$result = $conn->execute("select * from smgt_attendence 
		where user_id = $user_id AND attendence_date >= '$start_date' AND attendence_date <= '$end_date'")->fetchAll('assoc');

		return $result;
	}
	
	function smgt_get_attendence($userid,$curr_date)
	{
		$status = '';
		
		$class=TableRegistry::get('smgt_attendence');		
		$result = $class->find('all',array('conditions' => array('user_id'=>$userid,'attendence_date' => $curr_date)));
		
		foreach($result as $rs)
		{
			$status=$rs['status'];	
		}
		
		if(isset($status))
			return $status;
	}
	
	function smgt_get_subject_attendence($userid,$subject_id,$curr_date)
	{
		$status = '';
		
		$class=TableRegistry::get('smgt_sub_attendance');		
		$result = $class->find('all',array('conditions' => array('user_id'=>$userid,'sub_id'=>$subject_id,'attendance_date' => $curr_date)));
		
		foreach($result as $rs)
		{
			$status=$rs['status'];	
		}
	
		if(isset($status))
			return $status;
	}
	
	function smgt_get_attendence_comment($userid,$curr_date)
	{
		$status = '';
		
		$class=TableRegistry::get('smgt_attendence');		
		$result = $class->find('all',array('conditions' => array('user_id'=>$userid,'attendence_date' => $curr_date)));
		
		foreach($result as $rs)
		{
			$status=$rs['comment'];	
		}

		if(isset($status))
			return $status;
	}
	
	function smgt_get_subject_attendence_comment($userid,$subject_id,$curr_date)
	{
		$status = '';
		
		$class=TableRegistry::get('smgt_sub_attendance');		
		$result = $class->find('all',array('conditions' => array('user_id'=>$userid,'sub_id'=>$subject_id,'attendance_date' => $curr_date)));
		
		foreach($result as $rs)
		{
			$status=$rs['comment'];	
		}

		if(isset($status))
			return $status;
	}
	public function get_country_code($country)
	{			
		$xml = $this->get_country_list();		
		foreach($xml as $name)
		{	
			if($name->name == $country)
			{		
				return $name->phoneCode;
			}
		}
	}
	
	public function fail_student_list($current_class,$exam_id,$passing_marks)
	{
		$student_fail = array();
		
		$class_mark=TableRegistry::get('smgt_marks');
		$query = $class_mark->find('all',array('conditions' => array('class_id' => $current_class,'exam_id' => $exam_id)));
				
		foreach($query as $student_list)
		{
			if($student_list['marks'] < $passing_marks)
			{
				$student_fail[]=$student_list['student_id'];
			}		
		}
		if(isset($student_fail))
			return $student_fail;
	}
	
	public function smgt_migration($current_class,$next_class,$exam_id,$fail_list)
	{
		$student_list=TableRegistry::get('smgt_users');
		$class_student_list=$student_list->find()->where(['classname'=>$current_class,'role'=>'student']);
		
		foreach($class_student_list as $class_student)
		{
			if (!in_array($class_student['user_id'],$fail_list))
			{
				$item = $student_list->get($class_student['user_id']);			
				$class_student['classname']=$next_class;
				$item['classname']=$class_student['classname'];
				
				if($student_list->save($item))
				{
					
				}
			}
		}
	}
	
	public function smgt_notice_show_calender($role='admin')
	{
		$stud_date = $this->getfieldname('date_format');
		$notice_data_array = array();
		
		/*  Notice */
		$tbl_notice = TableRegistry::get('smgt_notice');
		if($role=='admin')
		{
			$tbl_notice_obj=$tbl_notice->find();
		}
		else
		{
			$tbl_notice_obj=$tbl_notice->find()->where(['notice_for'=>$role]);
		}
				
		if(!empty($tbl_notice_obj))
		{
			foreach($tbl_notice_obj as $notice)
			{	
				$i=1;
				$start_date=date('Y-m-d', strtotime($notice['notice_start_date']));
				$n_start_date=date($stud_date, strtotime($notice['notice_start_date']));
				$end_date=date('Y-m-d', strtotime($notice['notice_end_date'].' +'.$i.' days'));
				$n_end_date=date($stud_date, strtotime($notice['notice_end_date'].' +'.$i.' days'));
				
				$notice_data_array[]=array('title'=>$notice['notice_title'],
									'desc'=>strlen(($notice['notice_comment']) > 50)?substr($notice['notice_comment'],0,50)."...":$notice['notice_comment'],
									'type'=>'Notice',
									'start'=>$start_date,
									'startd'=>$n_start_date,
									'end'=>$end_date,
									'endd'=>$n_end_date,
									'color'=>'#00a65a'
									);
			}
		}
		
		/* Holiday */
		
		$tbl_holiday = TableRegistry::get('smgt_holiday');		
		$tbl_holiday_obj=$tbl_holiday->find();
		$holiday_data_array = array();
		
		if(!empty($tbl_holiday_obj))
		{
			foreach($tbl_holiday_obj as $holiday)
			{			
				$i=1;
				$start_date=date('Y-m-d', strtotime($holiday['date']));
				$h_start_date=date($stud_date, strtotime($holiday['date']));
				$end_date=date('Y-m-d', strtotime($holiday['end_date'].' +'.$i.' days'));
				$h_end_date=date($stud_date, strtotime($holiday['end_date'].' +'.$i.' days'));
			
				$holiday_data_array[]=array('title'=>$holiday['holiday_title'],
									'desc'=>strlen(($holiday['description']) > 50)?substr($holiday['description'],0,50)."...":$holiday['description'],
									'type'=>'Holiday',
									'start'=>$start_date,
									'startd'=>$h_start_date,
									'end'=>$end_date,
									'endd'=>$h_end_date,
									'color'=>'#dd4b39'
									);
			}
		}
		
		/* Event */
		
		$tbl_event = TableRegistry::get('smgt_event');	
		if($role=='admin')
		{
			$tbl_event_obj=$tbl_event->find();
		}
		else
		{
			$tbl_event_obj=$tbl_event->find()->where(['event_for'=>$role]);
		}	
		
		$event_data_array = array();
		
		if(!empty($tbl_event_obj))
		{
			foreach($tbl_event_obj as $event)
			{			
				$i=1;
				$start_date=date('Y-m-d', strtotime($event['start_date']));
				$h_start_date=date($stud_date, strtotime($event['start_date']));
				$end_date=date('Y-m-d', strtotime($event['end_date'].' +'.$i.' days'));
				$h_end_date=date($stud_date, strtotime($event['end_date'].' +'.$i.' days'));
			
				$event_data_array[]=array('title'=>$event['event_title'],
									'desc'=>strlen(($event['event_desc']) > 50)?substr($event['event_desc'],0,50)."...":$event['event_desc'],
									'type'=>'Event',
									'start'=>$start_date,
									'startd'=>$h_start_date,
									'end'=>$end_date,
									'endd'=>$h_end_date,
									'color'=>'#f39c12'
									);
			}
		}
		
		/* News */
		
		$tbl_news = TableRegistry::get('smgt_news');	
		$tbl_news_obj=$tbl_news->find();	
		$news_data_array = array();
		
		if(!empty($tbl_news_obj))
		{
			foreach($tbl_news_obj as $event)
			{			
				$i=1;
				$start_date=date('Y-m-d', strtotime($event['news_start_date']));
				$h_start_date=date($stud_date, strtotime($event['news_start_date']));
				$end_date=date('Y-m-d', strtotime($event['news_end_date'].' +'.$i.' days'));
				$h_end_date=date($stud_date, strtotime($event['news_end_date'].' +'.$i.' days'));
			
				$news_data_array[]=array('title'=>$event['news_title'],
									'desc'=>strlen(($event['news_desc']) > 50)?substr($event['news_desc'],0,50)."...":$event['news_desc'],
									'type'=>'News',
									'start'=>$start_date,
									'startd'=>$h_start_date,
									'end'=>$end_date,
									'endd'=>$h_end_date,
									'color'=>'#3c8dbc'
									);
			}
		}
		
		/* Exam */
		
		$tbl_exam = TableRegistry::get('smgt_exam');	
		$tbl_exam_obj=$tbl_exam->find();	
		$exam_data_array = array();
		
		if(!empty($tbl_exam_obj))
		{
			foreach($tbl_exam_obj as $exam)
			{			
				$i=1;
				$start_date=date('Y-m-d', strtotime($exam['exam_date']));
				$h_start_date=date($stud_date, strtotime($exam['exam_date']));
				$end_date=date('Y-m-d', strtotime($exam['exam_date'].' +'.$i.' days'));
				$h_end_date=date($stud_date, strtotime($exam['exam_date'].' +'.$i.' days'));
			
				$exam_data_array[]=array('title'=>$exam['exam_name'],
									'desc'=>strlen(($exam['exam_comment']) > 50)?substr($exam['exam_comment'],0,50)."...":$exam['exam_comment'],
									'type'=>'Exam',
									'start'=>$start_date,
									'startd'=>$h_start_date,
									'end'=>$end_date,
									'endd'=>$h_end_date,
									'color'=>'#82714A'
									);
			}
		}
		
		$all_data_merge=array_merge($notice_data_array,$holiday_data_array,$event_data_array,$news_data_array,$exam_data_array);
		return $all_data=json_encode($all_data_merge);
	}
	
	public function get_country_list()
	{
		$xml_path = WWW_ROOT.'xmlfile/countrylist.xml';		
		$xml = Xml::build($xml_path);
		return $xml;
	}
	public function export_to_csv($filename="export.csv",$rows = array())
	{	
		if(empty($rows))
		{
			return false;
		}
		ob_start();
		$fp = fopen(TMP .$filename, 'w');
		foreach ($rows as $fields) {
			fputcsv($fp, $fields);
		}
		fclose($fp);
		ob_clean();
		$file= TMP .$filename;//file location
		$mime = 'text/plain';
		header('Content-Type:application/force-download');
		header('Pragma: public');       // required
		header('Expires: 0');           // no cache
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file)).' GMT');
		header('Cache-Control: private',false);
		header('Content-Type: '.$mime);
		header('Content-Disposition: attachment; filename="'.basename($file).'"');
		header('Content-Transfer-Encoding: binary');
			//header('Content-Length: '.filesize($file_name));      // provide file size
		header('Connection: close');
		readfile($file);	
		exit;
	}
	public function cleanData( &$str ) {
        $str = preg_replace( "/\t/", "\\t", $str );
        $str = preg_replace("/\r?\n/", "\\n", $str);
    }
	public function export_to_excel($fileName, $headerRow, $data) 
	{
		ini_set('max_execution_time', 1600); //increase max_execution_time to 10 min if data set is very large
		$fileContent = implode("\t ", $headerRow)."\r\n";
		foreach($data as $result) 
		{
			$fileContent .=  implode("\t ", $result)."\r\n";
		}
		header('Content-type: application/ms-excel'); /// you can set csv format
		header('Content-Disposition: attachment; filename='.$fileName);
		echo $fileContent;
		exit;
	}
	public function get_teacher_access()
    {
		$teacher_access_rights = TableRegistry::get('teacher_access_rights');		
		$query = $teacher_access_rights->find();
		$data = array();
		
		foreach ($query as $id) 
		{
			$data['chksub']=$id['chksub'];
			$data['chkstud']=$id['chkstud'];
			$data['chkatted']=$id['chkatted'];
		}
		if(!empty($data))
			return $data;
	}
	public function send_mail_absent_student()
    {
		$date_atted = date('Y-m-d',(strtotime ('-1 day')));	
		$smgt_attendence = TableRegistry::get('smgt_attendence');	
		$query = $smgt_attendence->find()->where(['attendence_date'=>$date_atted,'role_name'=>'student','status'=>'Absent','mail_status'=>0])
		->hydrate(false)->toArray();
		
		if(!empty($query))
		{
			foreach ($query as $user_data) 
			{
				$school_name = $this->getfieldname('school_name');
				$school_email = $this->getfieldname('email');
				
				$parent_list = $this->smgt_get_student_parent_id($user_data['user_id']);
				$message_content = "Your Child ".$this->get_user_id($user_data['user_id'])." is absent on".$date_atted.".";
						
				$emial_to = $this->get_user_email_id($parent_list);
				$sys_name = $school_name;
				$sys_email = $school_email;
				$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";

				@mail($emial_to,_("Attendance Reminder Alert!"),$message_content,$headers);
				
				$att_user = $smgt_attendence->get($user_data['attendence_id']);
				$att_user->mail_status = 1;
				$smgt_attendence->save($att_user);
			}
		}
	}
	public function send_mail_student_return_book()
    {					
		$date_atted = date('Y-m-d',(strtotime ('-2 day')));	
		// var_dump($date_atted);die;
		$smgt_library_book_issue = TableRegistry::get('smgt_library_book_issue');	
		$query = $smgt_library_book_issue->find()->where(['end_date'=>$date_atted,'return_book_email_status'=>0])
		->hydrate(false)->toArray();
		
		if(!empty($query))
		{
			foreach ($query as $user_data) 
			{
				$school_email = $this->getfieldname('email');
				
				$school_name = $this->getfieldname('school_name');
								
				$student_name = $this->get_user_id($user_data['student_id']);
				$to = $this->get_user_email_id($user_data['student_id']);
				
				$message_content = "Dear $student_name \n\n Your book return end date comes, so return it within 2 days otherwise we will take extra charges for a delay.\n\n Thank You\n $school_name";
						
				$emial_to = $to;
				$sys_name = $school_name;
				$sys_email = $school_email;
				$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";

				@mail($emial_to,_("Return Book Reminder!"),$message_content,$headers);
	
				$att_user = $smgt_library_book_issue->get($user_data['id']);
				$att_user->return_book_email_status = 1;
				$smgt_library_book_issue->save($att_user);
			}
		}
	}
	public function send_mail_student_result_declare()
    {					
		$date_atted = date('Y-m-d',(strtotime ('5 day')));	
		// var_dump($date_atted);die;
		$smgt_marks = TableRegistry::get('smgt_marks');	
		$query = $smgt_marks->find()->where(['DATE(created_date)'=>$date_atted,'result_declare_email_status'=>0])
		->hydrate(false)->toArray();
		
		if(!empty($query))
		{
			foreach ($query as $user_data) 
			{
				$sys_email=$this->getfieldname('email'); 
				$school_email = $this->getfieldname('email');
				
				$school_name = $this->getfieldname('school_name');
		
				$mailtem = TableRegistry::get('smgt_emailtemplate');
				$format =$mailtem->find()->where(["find_by"=>"Result Notification Mail Template"])->hydrate(false)->toArray();
				
				$str=$format[0]['template'];
				$subject=$format[0]['subject'];
				
				$msgarray = explode(" ",$str);
				$subarray = explode(" ",$subject);
										
				$email_id=$teacher_email;
				
				$msgarray['{{student_name}}']=$this->get_user_id($user_data['student_id']);
				$msgarray['{{title}}']=$subject;
				$msgarray['{{school_name}}']=$school_name;
				
				$subarray['{{student_name}}']=$this->get_user_id($user_data['student_id']);
				$subarray['{{title}}']=$subject;
				$subarray['{{school_name}}']=$school_name;
				
				$datamsg = str_replace(array_keys($msgarray),array_values($msgarray),$str);
				$submsg = str_replace(array_keys($subarray),array_values($subarray),$subject);

				$message_content = "Your Result declared ";
						
				$to = $this->get_user_email_id($user_data['student_id']);
				$sys_name = $school_name;
				$sys_email = $school_email;
				$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";

				$sys_name = $school_name;
				$sys_email = $sys_email;
				$message = $datamsg;
				
				$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
				@mail($to,_($submsg),$message,$headers);
				
				/* $email = new Email('default');
				$email->from(array('jayesh@dasinfomedia.com' => 'My Site'))
					->to('ajay@dasinfomedia.com')
					->subject('About')
					->send('My message'); */
	
				$att_user = $smgt_marks->get($user_data['mark_id']);
				$att_user->result_declare_email_status = 1;
				$smgt_marks->save($att_user);
			}
		}
	}
	
	public function get_fees_title($fees_id)
    {
		$fees_title = '';
		
		$smgt_categories = TableRegistry::get('smgt_categories');
		$data = $smgt_categories->find()->where(['category_id'=>$fees_id])->hydrate(false)->toArray();
		if(!empty($data))
		{
			$fees_title = $data[0]['category_type'];
		}
		return $fees_title;
	}
	public function hostel_room_student_bed_unique_id($id)
	{		
		$data = array();
		
		$class=TableRegistry::get('smgt_assign_bed_new');		
		$query = $class->find('all',array('conditions' => array('bed_unique_id' => $id)))->hydrate(false)->toArray();		
		$data['student_id'] = $query[0]['student_id'];
		$data['assign_date'] = $query[0]['assign_date'];
		$data['room_unique_id'] = $query[0]['room_unique_id'];
		$data['bed_unique_id'] = $query[0]['bed_unique_id'];
		
		return $data;
	}
	public function get_users_email_mno($type='')
    {						
		$smgt_users=TableRegistry::get('smgt_users');	
		
		if(!empty($type) && $type == 'all')
			$query = $smgt_users->find();
		else if(!empty($type) && $type != 'all')
			$query = $smgt_users->find()->where(['role'=>$type]);
		else
			$query = $smgt_users->find();
		
		$emails = array();
		
		foreach($query as $data)
		{
			$emails[] = array('email'=>$data['email'],'mobile'=>$data['mobile_no']);
		}		
		return $emails;
	}
	
	public function user_attendance_count($id)
	{
		$total_cnt = 0;
		
		$conn=ConnectionManager::get('default');			
		$rs=$conn->execute("SELECT count(user_id) as count FROM smgt_attendence
		WHERE user_id = '".$id."'
		group by user_id");
		
		foreach($rs as $rs1)
		{
			$total_cnt = $rs1['count'];
		}
		return $total_cnt;
	}
	
	public function user_absent_count($id)
	{
		$absent_cnt = 0;
		
		$conn=ConnectionManager::get('default');			
		$rs=$conn->execute("SELECT count(user_id) as count FROM smgt_attendence
		WHERE status = 'Absent'
		AND user_id = '".$id."'
		group by user_id");
		
		foreach($rs as $rs1)
		{
			$absent_cnt = $rs1['count'];
		}
		return $absent_cnt;
	}
	
	public function user_present_count($id)
	{
		$present_cnt = 0;
		
		$conn=ConnectionManager::get('default');			
		$rs=$conn->execute("SELECT count(user_id) as count FROM smgt_attendence
		WHERE status = 'Present'
		AND user_id = '".$id."'
		group by user_id");
		
		foreach($rs as $rs1)
		{
			$present_cnt = $rs1['count'];
		}
		return $present_cnt;
	}
	public function get_users_data_rollwise($type='')
    {
		$query = '';
				
		$smgt_users=TableRegistry::get('smgt_users');	
		$query = $smgt_users->find()->where(['role'=>$type])->hydrate(false)->toArray();
		return $query;
		
	}
	public function get_subject_name($id)
    {
		$id1 = '';
		
		$class=TableRegistry::get('smgt_subject');
		
		$query = $class->find('all',array('conditions' => array('subid'=>$id)));
		
		foreach ($query as $id) 
		{
			$id1=$id['sub_name'];
			return $id1;
		}
	}
	
	public function get_homework_data($id=0,$field_name='')
    {
		$id1 = '';
		
		$class = TableRegistry::get('smgt_homework');
		
		$query = $class->find()->where(['homework_id'=>$id]);
		
		foreach ($query as $id) 
		{
			$id1 = $id[$field_name];
		}
		return $id1;
	}
	
	public function get_student_parents_id($user_id)
	{
		$parent_id = array();
		
		$class2 = TableRegistry::get('child_tbl');
		$userdt=$class2->find()->where(['child_id'=>$user_id]);
		
		foreach($userdt as $data)
		{
			$parent_id[] = $data['child_parent_id'];		
		}
		if(!empty($parent_id))
			return $parent_id;
	}
	public function get_parents_student_id($user_id)
	{
		$student_id = array();
		$class_id = array();
		
		$class2 = TableRegistry::get('child_tbl');
		$userdt=$class2->find()->where(['child_parent_id'=>$user_id]);
		
		foreach($userdt as $data)
		{
			$student_id[] = $data['child_id'];		
		}
		if(!empty($student_id))
		{
			foreach($student_id as $student_data)
			{
				$class_id[] = $this->get_user_class($student_data);		
			}
		}
		foreach ($class_id as $key=>$val) {
			if ($val === null)
			   unset($class_id[$key]);
		}
		if(!empty($class_id))
			return array_unique($class_id);

	}
	public function get_user_class_list($id)
	{		
		$class_list = array();
		
		$class=TableRegistry::get('smgt_subject');		
		$query = $class->find('all')->where(['teacher_id'=>$id]);
	
		foreach ($query as $id) 
		{
			$class_list[] = $id['class_id'];
		}
		return array_unique($class_list);
	}
	public function get_teacher_class_list($id)
	{		
		$class_list = array();
		$teacher_classList = '';
		
		$class=TableRegistry::get('smgt_subject');		
		$query = $class->find('all')->where(['teacher_id'=>$id]);
	
		foreach ($query as $id) 
		{
			$class_list[] = $this->get_class_id($id['class_id']);
		}
		$class_list = array_unique($class_list);
		$teacher_classList = implode(',',$class_list);
		return $teacher_classList;
	}
	public function base64_encode_image ($filename=string,$filetype=string) 
	{
		if ($filename) {
			$imgbinary = file_get_contents($filename);
			return 'data:image/' . $filetype . ';base64,' . base64_encode($imgbinary);
		}
	}
	
	public function mail_invoice_pdf($to,$view_po)
	{
		// $sys_email=$this->get_email(); 

		$sys_email = $this->getfieldname('email');
		$sys_name = $this->getfieldname('school_name');
		$server = $_SERVER['SERVER_NAME'];
		if(isset($_SERVER['HTTPS'])){
			$protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
		}
		else{
			$protocol = 'http';
		}		
		$url = "$protocol://$server/php/niftysis/feepayment/paymentviewpdf/{$view_po}";
		
		$fileatt = "test.pdf"; // Path to the file                  
	
		$fileatt_type = "application/pdf"; // File Type  
		$fileatt_name = "Feepayment_Invoice.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "$sys_name <$sys_email>"; // Who the email is from  
		$email_subject = "Feepayment Invoice Notification"; // The Subject of the email  
		$email_message = "Sir / Madam,<br>";
		$email_message .= "<p> Your have a new invoice.  You can check the invoice attached here.</p>";
		$email_message .= "<p>Thank You.</p>";
		
		
		/* $email_subject = "{$submsg}"; // The Subject of the email  
		$email_message = "{$datamsg}"; */
				
		// $email_to = "ajay@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to  
		/* $headers = "{$from}";  */
		$headers = "From: ".$email_from;  
		$file = fopen($url,'rb');  


		$contents = file_get_contents($url); // read the remote file
		touch('temp.pdf'); // create a local EMPTY copy
		file_put_contents('temp.pdf', $contents);


		$data = fread($file,filesize("temp.pdf"));  
		// $data = fread($file,19189);  
		fclose($file);  
		$semi_rand = md5(time());  
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		$headers .= "\nMIME-Version: 1.0\n" .  
					"Content-Type: multipart/mixed;\n" .  
					" boundary=\"{$mime_boundary}\"";  
		$email_message .= "This is a multi-part message in MIME format.\n\n" .  
						"--{$mime_boundary}\n" .  
						"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
					   "Content-Transfer-Encoding: 7bit\n\n" .  
		$email_message .= "\n\n";  
		// $data = chunk_split(base64_encode($data));   
		$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		$email_message .= "--{$mime_boundary}\n" .  
						  "Content-Type: {$fileatt_type};\n" .  
						  " name=\"{$fileatt_name}\"\n" .  
						  //"Content-Disposition: attachment;\n" .  
						  //" filename=\"{$fileatt_name}\"\n" .  
						  "Content-Transfer-Encoding: base64\n\n" .  
						 $data .= "\n\n" .  
						  "--{$mime_boundary}--\n";  
		$ok = @mail($email_to, $email_subject, $email_message, $headers);
	}
	
	public function mail_examhall_pdf($to,$view_po)
	{ 
		$sys_email = $this->getfieldname('email');
		$sys_name = $this->getfieldname('school_name');
		$server = $_SERVER['SERVER_NAME'];
		if(isset($_SERVER['HTTPS'])){
			$protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
		}
		else{
			$protocol = 'http';
		}		
		$url = "$protocol://$server/php/niftysis/student/studentreceiptpdf/{$view_po}";
		
		$fileatt = "test.pdf"; // Path to the file                  
	
		$fileatt_type = "application/pdf"; // File Type  
		$fileatt_name = "ExamReceipt.pdf"; // Filename that will be used for the file as the attachment  
		$email_from = "$sys_name <$sys_email>"; // Who the email is from  
		$email_subject = "Exam Receipt"; // The Subject of the email  
		$email_message = "His / Her,<br>";
		$email_message .= "<p> Your exam receipt generated.  You can check exam receipt attached here.</p>";
		$email_message .= "<p>Thank You.</p>";
		
		
		/* $email_subject = "{$submsg}"; // The Subject of the email  
		$email_message = "{$datamsg}"; */
				
		// $email_to = "ajay@dasinfomedia.com"; // Who the email is to  
		$email_to = $to; // Who the email is to  
		/* $headers = "{$from}";  */
		$headers = "From: ".$email_from;  
		$file = fopen($url,'rb');  


		$contents = file_get_contents($url); // read the remote file
		touch('temp.pdf'); // create a local EMPTY copy
		file_put_contents('temp.pdf', $contents);


		$data = fread($file,filesize("temp.pdf"));  
		// $data = fread($file,19189);  
		fclose($file);  
		$semi_rand = md5(time());  
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
			
		$headers .= "\nMIME-Version: 1.0\n" .  
					"Content-Type: multipart/mixed;\n" .  
					" boundary=\"{$mime_boundary}\"";  
		$email_message .= "This is a multi-part message in MIME format.\n\n" .  
						"--{$mime_boundary}\n" .  
						"Content-Type:text/html; charset=\"iso-8859-1\"\n" .  
					   "Content-Transfer-Encoding: 7bit\n\n" .  
		$email_message .= "\n\n";  
		// $data = chunk_split(base64_encode($data));   
		$data = chunk_split(base64_encode(file_get_contents('temp.pdf')));
		$email_message .= "--{$mime_boundary}\n" .  
						  "Content-Type: {$fileatt_type};\n" .  
						  " name=\"{$fileatt_name}\"\n" .  
						  //"Content-Disposition: attachment;\n" .  
						  //" filename=\"{$fileatt_name}\"\n" .  
						  "Content-Transfer-Encoding: base64\n\n" .  
						 $data .= "\n\n" .  
						  "--{$mime_boundary}--\n";  
		$ok = @mail($email_to, $email_subject, $email_message, $headers);
		
	}
	
	public function get_user_homework($get_current_user_id=0)
    {	
		$class = TableRegistry::get('smgt_homework');

		$class_list = array();	
		$class_list = $this->get_class_list_user_id($get_current_user_id);
		
		$smgt_student_homework = TableRegistry::get('smgt_student_homework');
		if(!empty($class_list))
			$query=$class->find()->where(['class_id'=>$class_list])->order(['homework_id'=>'DESC'])->hydrate(false)->toArray();

		if(!empty($query))
			return $query;
	}
	
	public function check_user_controller_access($name='',$role='')
	{	
		$get_current_user_id = $this->request->session()->read('user_id');
		$login=$this->get_user_role($get_current_user_id);
		
		$get_all_menu_teacher = array();
		
		$getteachermenu=TableRegistry::get('tblteachermenu');
		if($role == 'student')
			$get_all_menu_teacher=$getteachermenu->find()->
			where(['student_approve' => '1','menu_name' => $name])->hydrate(false)->toArray();
		elseif($role == 'teacher')
			$get_all_menu_teacher=$getteachermenu->find()->
			where(['teacher_approve' => '1','menu_name' => $name])->hydrate(false)->toArray();
		elseif($role == 'parent')
			$get_all_menu_teacher=$getteachermenu->find()->
			where(['parent_approve' => '1','menu_name' => $name])->hydrate(false)->toArray();
		else
			$get_all_menu_teacher=$getteachermenu->find()->
			where(['staff_approve' => '1','menu_name' => $name])->hydrate(false)->toArray();
		
		if(!empty($get_all_menu_teacher) && $login == $role)
			return true;
		else
			return false;
	}
	
	public function comman_user_action_access($name='',$role='')
	{	
		$get_current_user_id = $this->request->session()->read('user_id');
		$login=$this->get_user_role($get_current_user_id);
		
		$get_all_menu = array();
		
		$getteachermenu=TableRegistry::get('comman_access');
		if($role == 'student')
			$get_all_menu=$getteachermenu->find()->
			where(['student' => '1','comman_action' => $name])->hydrate(false)->toArray();
		elseif($role == 'teacher')
			$get_all_menu=$getteachermenu->find()->
			where(['teacher' => '1','comman_action' => $name])->hydrate(false)->toArray();
		elseif($role == 'parent')
			$get_all_menu=$getteachermenu->find()->
			where(['parent' => '1','comman_action' => $name])->hydrate(false)->toArray();
		else
			$get_all_menu=$getteachermenu->find()->
			where(['supportstaff' => '1','comman_action' => $name])->hydrate(false)->toArray();
		
		if(!empty($get_all_menu) && $login == $role)
			return true;
		else
			return false;
	}
	
	public function get_parent_child_list($user_id)
	{
		$parent_id = array();
		
		$class2 = TableRegistry::get('child_tbl');
		$userdt=$class2->find()->where(['child_parent_id'=>$user_id]);
		foreach($userdt as $data)
		{
			$parent_id[]=$this->get_user_id($data['child_id']);
		}
		if(!empty($parent_id))
		{
			$parent_id = implode(',',$parent_id);
			return $parent_id;			
		}
	}
	
	public function is_route_exist($route_data)
	{

		$conn=ConnectionManager::get('default');			
		
		$table_name = 'smgt_time_table';
		
		$route = $conn->execute("SELECT * FROM $table_name WHERE class_id=".$route_data['class_id']." AND section=".$route_data['section']." 
		AND (start_time='".$route_data['start_time']."' OR end_time='".$route_data['end_time']."') AND weekday=".$route_data['weekday'])->fetchAll('assoc');

		$route2 = $conn->execute("SELECT * FROM $table_name WHERE teacher_id=".$route_data['teacher_id']." 
		AND (start_time='".$route_data['start_time']."' OR end_time='".$route_data['end_time']."') AND weekday=".$route_data['weekday'])->fetchAll('assoc');
		
		if(empty($route) && empty($route2))
			return 'success';
		else
		{
			if(count($route) > 0)
				return 'duplicate';
			if(count($route2) > 0)
				return 'teacher_duplicate';
		}

	}
	
	public function generate_studentID()
	{
		$code = array();

		$emp_id_format = $this->getfieldname('stud_method');
		$emp_id_digit = $this->getfieldname('no_of_digit');
		$emp_id_prefix = $this->getfieldname('stud_id_prefix');
		
		$code['studentID_prefix'] = $emp_id_prefix;
		
		$smgt_users=TableRegistry::get('smgt_users');
					
		if($emp_id_format == 'Random')
		{
			$randam = rand(10,100);
			$code['studentID'] = sprintf("%0".$emp_id_digit."d",$randam);					
		}
		else
		{		
			$query = $smgt_users->find();
			$result = $query->select(["studentID" => $query->func()->max('studentID')])
			->where(['role'=>'student'])->hydrate(false)->toArray();
			
			if(!empty($result[0]['studentID']))
				$lastid = $result[0]['studentID']+1;
			else
				$lastid = '1';
			
			$code['studentID'] = sprintf("%0".$emp_id_digit."d",$lastid);			
		}
		$studentID = $code['studentID'];
		$query = $smgt_users->find()->where(['studentID'=>$studentID])->hydrate(false)->toArray();
		if(!empty($query))
		{
			for($i = 1; ; $i ++)
			{   
				$studentID = $studentID+$i;
				$code['studentID'] = $studentID;
				$query1 = $smgt_users->find()->where(['studentID'=>$studentID])->hydrate(false)->toArray();
				if(empty($query1))
				{
					goto end;
				}		  
			}	
			end:
			return $code;
		}
		else
			return $code;	
	}
	
	public function get_studentID($id=0)
	{
		$class=TableRegistry::get('smgt_users');
		if($id)
		{
			$query = $class->find('all',array('conditions' => array('user_id'=>$id)));
			
			foreach ($query as $id) 
			{
				$studentID = $id['studentID_prefix'].$id['studentID'];
				return $studentID;
			}
		}		
	}
	
	public function my_simple_crypt( $string, $action = 'e' )
	{
		$secret_key = 'my_simple_secret_key';
		$secret_iv = 'my_simple_secret_iv';
	 
		$output = false;
		$encrypt_method = "AES-256-CBC";
		$key = hash( 'sha256', $secret_key );
		$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
	 
		if( $action == 'e' ) {
			$output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
		}
		else if( $action == 'd' ){
			$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
		}
	 
		return $output;
	}
	
	public function check_exam_id($exam_id,$subject_id)
	{	
		$d = '';
		
		$class=TableRegistry::get('exam_time_table');		
		$query = $class->find('all',array('conditions' => array('exam_id' => $exam_id, 'subject_id' => $subject_id)));
		
		foreach($query as $data)
		{ 			
			$d=$data['exam_time_table_id'];
		}
		if(isset($d)){
			return $d;
		}
	}
	public function get_exam_data($id,$field='')
    {		
		$class=TableRegistry::get('smgt_exam');
		if($id)
		{
			$query = $class->find()->where(['exam_id'=>$id])->hydrate(false)->toArray();
			if(!empty($query))
			{
				foreach ($query as $id1) 
				{
					return $id1[$field];
				}
			}
		}
	}
	public function get_hall_data($id,$field='')
    {		
		$class=TableRegistry::get('smgt_hall');
		if($id)
		{
			$query = $class->find()->where(['hall_id'=>$id])->hydrate(false)->toArray();
			if(!empty($query))
			{
				foreach ($query as $id1) 
				{
					return $id1[$field];
				}
			}
		}
	}
	public function get_subject_data($id,$field='')
    {		
		$class=TableRegistry::get('smgt_subject');
		if($id)
		{
			$query = $class->find()->where(['subid'=>$id])->hydrate(false)->toArray();
			if(!empty($query))
			{
				foreach ($query as $id1) 
				{
					return $id1[$field];
				}
			}
		}
	}
	public function get_user_full_name($id=0)
    {		
		$class=TableRegistry::get('smgt_users');
		if($id)
		{
			$query = $class->find('all',array('conditions' => array('user_id'=>$id)));
			
			foreach ($query as $id) 
			{
				$id1=$id['first_name']." ".$id['middle_name']." ".$id['last_name'];
				return $id1;
			}
		}
	}
	public function fetch_exam_time_table_data($exam_id,$subject_id)
	{	
		$d = array();
		
		$class=TableRegistry::get('exam_time_table');		
		$query = $class->find('all',array('conditions' => array('exam_id' => $exam_id, 'subject_id' => $subject_id)));
		
		foreach($query as $data)
		{ 			
			$d['exam_time_table_id']=$data['exam_time_table_id'];
			$d['exam_date']=$data['exam_date'];
			$d['start_time']=$data['start_time'];
			$d['end_time']=$data['end_time'];
		}
		if(!empty($d))
			return $d;			
	}
	
	public function check_subject_data($sub_code)
    {		
		$class=TableRegistry::get('smgt_subject');
		if($sub_code)
		{
			$query = $class->find()->where(['sub_code'=>$sub_code])->hydrate(false)->toArray();
			if(!empty($query))
				return true;
			else
				return false;
		}
	}
	
	public function get_subject_code($id)
    {		
		$class=TableRegistry::get('smgt_subject');
		if($id)
		{
			$query = $class->find()->where(['subid'=>$id])->hydrate(false)->toArray();
			if(!empty($query))
			{
				foreach ($query as $id1) 
				{
					return $id1['sub_code'];
				}
			}
		}
	}
	
	function dateformat_PHP_to_jQueryUI($php_format)
	{
		$SYMBOLS_MATCHING = array(
			// Day
			'd' => 'dd',
			'D' => 'D',
			'j' => 'd',
			'l' => 'DD',
			'N' => '',
			'S' => '',
			'w' => '',
			'z' => 'o',
			// Week
			'W' => '',
			// Month
			'F' => 'MM',
			'm' => 'mm',
			'M' => 'M',
			'n' => 'm',
			't' => '',
			// Year
			'L' => '',
			'o' => '',
			'Y' => 'yy',
			'y' => 'y',
			// Time
			'a' => '',
			'A' => '',
			'B' => '',
			'g' => '',
			'G' => '',
			'h' => '',
			'H' => '',
			'i' => '',
			's' => '',
			'u' => ''
		);
		$jqueryui_format = "";
		$escaping = false;
		for($i = 0; $i < strlen($php_format); $i++)
		{
			$char = $php_format[$i];
			if($char === '\\') // PHP date format escaping character
			{
				$i++;
				if($escaping) $jqueryui_format .= $php_format[$i];
				else $jqueryui_format .= '\'' . $php_format[$i];
				$escaping = true;
			}
			else
			{
				if($escaping) { $jqueryui_format .= "'"; $escaping = false; }
				if(isset($SYMBOLS_MATCHING[$char]))
					$jqueryui_format .= $SYMBOLS_MATCHING[$char];
				else
					$jqueryui_format .= $char;
			}
		}
		return $jqueryui_format;
	}
	
	public function currency_list()
	{
		$currency_list = array(
		'AED'=>array('name' => 'United Arab Emirates Dirham', 'symbol'=>'د.إ', 'hex'=>'&#x62f;&#x2e;&#x625;'),
		'ANG'=>array('name' => 'NL Antillian Guilder', 'symbol'=>'ƒ', 'hex'=>'&#x192;'),
		'ARS'=>array('name' => 'Argentine Peso', 'symbol'=>'$', 'hex'=>'&#x24;'),
		'AUD'=>array('name' => 'Australian Dollar', 'symbol'=>'A$', 'hex'=>'&#x41;&#x24;'),
		'BRL'=>array('name' => 'Brazilian Real', 'symbol'=>'R$', 'hex'=>'&#x52;&#x24;'),
		'BSD'=>array('name' => 'Bahamian Dollar', 'symbol'=>'B$', 'hex'=>'&#x42;&#x24;'),
		'CAD'=>array('name' => 'Canadian Dollar', 'symbol'=>'$', 'hex'=>'&#x24;'),
		'CHF'=>array('name' => 'Swiss Franc', 'symbol'=>'CHF', 'hex'=>'&#x43;&#x48;&#x46;'),
		'CLP'=>array('name' => 'Chilean Peso', 'symbol'=>'$', 'hex'=>'&#x24;'),
		'CNY'=>array('name' => 'Chinese Yuan Renminbi', 'symbol'=>'¥', 'hex'=>'&#xa5;'),
		'COP'=>array('name' => 'Colombian Peso', 'symbol'=>'$', 'hex'=>'&#x24;'),
		'CZK'=>array('name' => 'Czech Koruna', 'symbol'=>'Kč', 'hex'=>'&#x4b;&#x10d;'),
		'DKK'=>array('name' => 'Danish Krone', 'symbol'=>'kr', 'hex'=>'&#x6b;&#x72;'),
		'EUR'=>array('name' => 'Euro', 'symbol'=>'€', 'hex'=>'&#x20ac;'),
		'FJD'=>array('name' => 'Fiji Dollar', 'symbol'=>'FJ$', 'hex'=>'&#x46;&#x4a;&#x24;'),
		'GBP'=>array('name' => 'British Pound', 'symbol'=>'£', 'hex'=>'&#xa3;'),
		'GHS'=>array('name' => 'Ghanaian New Cedi', 'symbol'=>'GH₵', 'hex'=>'&#x47;&#x48;&#x20b5;'),
		'GTQ'=>array('name' => 'Guatemalan Quetzal', 'symbol'=>'Q', 'hex'=>'&#x51;'),
		'HKD'=>array('name' => 'Hong Kong Dollar', 'symbol'=>'$', 'hex'=>'&#x24;'),
		'HNL'=>array('name' => 'Honduran Lempira', 'symbol'=>'L', 'hex'=>'&#x4c;'),
		'HRK'=>array('name' => 'Croatian Kuna', 'symbol'=>'kn', 'hex'=>'&#x6b;&#x6e;'),
		'HUF'=>array('name' => 'Hungarian Forint', 'symbol'=>'Ft', 'hex'=>'&#x46;&#x74;'),
		'IDR'=>array('name' => 'Indonesian Rupiah', 'symbol'=>'Rp', 'hex'=>'&#x52;&#x70;'),
		'ILS'=>array('name' => 'Israeli New Shekel', 'symbol'=>'₪', 'hex'=>'&#x20aa;'),
		'INR'=>array('name' => 'Indian Rupee', 'symbol'=>'₹', 'hex'=>'&#x20b9;'),
		'ISK'=>array('name' => 'Iceland Krona', 'symbol'=>'kr', 'hex'=>'&#x6b;&#x72;'),
		'JMD'=>array('name' => 'Jamaican Dollar', 'symbol'=>'J$', 'hex'=>'&#x4a;&#x24;'),
		'JPY'=>array('name' => 'Japanese Yen', 'symbol'=>'¥', 'hex'=>'&#xa5;'),
		'KRW'=>array('name' => 'South-Korean Won', 'symbol'=>'₩', 'hex'=>'&#x20a9;'),
		'LKR'=>array('name' => 'Sri Lanka Rupee', 'symbol'=>'₨', 'hex'=>'&#x20a8;'),
		'MAD'=>array('name' => 'Moroccan Dirham', 'symbol'=>'.د.م', 'hex'=>'&#x2e;&#x62f;&#x2e;&#x645;'),
		'MMK'=>array('name' => 'Myanmar Kyat', 'symbol'=>'K', 'hex'=>'&#x4b;'),
		'MXN'=>array('name' => 'Mexican Peso', 'symbol'=>'$', 'hex'=>'&#x24;'),
		'MYR'=>array('name' => 'Malaysian Ringgit', 'symbol'=>'RM', 'hex'=>'&#x52;&#x4d;'),
		'NOK'=>array('name' => 'Norwegian Kroner', 'symbol'=>'kr', 'hex'=>'&#x6b;&#x72;'),
		'NZD'=>array('name' => 'New Zealand Dollar', 'symbol'=>'$', 'hex'=>'&#x24;'),
		'PAB'=>array('name' => 'Panamanian Balboa', 'symbol'=>'B/.', 'hex'=>'&#x42;&#x2f;&#x2e;'),
		'PEN'=>array('name' => 'Peruvian Nuevo Sol', 'symbol'=>'S/.', 'hex'=>'&#x53;&#x2f;&#x2e;'),
		'PHP'=>array('name' => 'Philippine Peso', 'symbol'=>'₱', 'hex'=>'&#x20b1;'),
		'PKR'=>array('name' => 'Pakistan Rupee', 'symbol'=>'₨', 'hex'=>'&#x20a8;'),
		'PLN'=>array('name' => 'Polish Zloty', 'symbol'=>'zł', 'hex'=>'&#x7a;&#x142;'),
		'RON'=>array('name' => 'Romanian New Lei', 'symbol'=>'lei', 'hex'=>'&#x6c;&#x65;&#x69;'),
		'RSD'=>array('name' => 'Serbian Dinar', 'symbol'=>'RSD', 'hex'=>'&#x52;&#x53;&#x44;'),
		'RUB'=>array('name' => 'Russian Rouble', 'symbol'=>'руб', 'hex'=>'&#x440;&#x443;&#x431;'),
		'SAR'=>array('name' => 'Saudi Riyal', 'symbol'=>'SAR', 'hex'=>'&#x440;&#x443;&#x431;'),
		'SEK'=>array('name' => 'Swedish Krona', 'symbol'=>'kr', 'hex'=>'&#x6b;&#x72;'),
		'SGD'=>array('name' => 'Singapore Dollar', 'symbol'=>'S$', 'hex'=>'&#x53;&#x24;'),
		'THB'=>array('name' => 'Thai Baht', 'symbol'=>'฿', 'hex'=>'&#xe3f;'),
		'TND'=>array('name' => 'Tunisian Dinar', 'symbol'=>'DT', 'hex'=>'&#x44;&#x54;'),
		'TRY'=>array('name' => 'Turkish Lira', 'symbol'=>'TL', 'hex'=>'&#x54;&#x4c;'),
		'TTD'=>array('name' => 'Trinidad/Tobago Dollar', 'symbol'=>'$', 'hex'=>'&#x24;'),
		'TWD'=>array('name' => 'Taiwan Dollar', 'symbol'=>'NT$', 'hex'=>'&#x4e;&#x54;&#x24;'),
		'USD'=>array('name' => 'US Dollar', 'symbol'=>'$', 'hex'=>'&#x24;'),
		'VEF'=>array('name' => 'Venezuelan Bolivar Fuerte', 'symbol'=>'Bs', 'hex'=>'&#x42;&#x73;'),
		'VND'=>array('name' => 'Vietnamese Dong', 'symbol'=>'₫', 'hex'=>'&#x20ab;'),
		'XAF'=>array('name' => 'CFA Franc BEAC', 'symbol'=>'FCFA', 'hex'=>'&#x46;&#x43;&#x46;&#x41;'),
		'XCD'=>array('name' => 'East Caribbean Dollar', 'symbol'=>'$', 'hex'=>'&#x24;'),
		'XPF'=>array('name' => 'CFP Franc', 'symbol'=>'F', 'hex'=>'&#x46;'),
		'ZAR'=>array('name' => 'South African Rand', 'symbol'=>'R', 'hex'=>'&#x52;')
	);
	return $currency_list;
	}
	public function get_currency_symbole($currecy_code)
	{
		$currency_list = $this->currency_list();
		if($currecy_code)
		{
			$cusrrency_symbol = $currency_list[$currecy_code];
			return $cusrrency_symbol['symbol'];
		}
	}
	
}
?>