<?php
namespace App\View\Helper;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper;
use Cake\Datasource\ConnectionManager;
use Cake\Routing\Router;
use Cake\Controller\Component;
use Cake\Utility\Xml;
use Cake\Network\Http\Client;
use Cake\I18n\Time;

class SettingHelper extends Helper
{
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
	
	public function user_mark_count($user_id=0,$class_id=0)
	{	
		$query = '';
		
		$class=TableRegistry::get('smgt_marks');		
		$query = $class->find('all',array('conditions' => array('student_id' => $user_id,'class_id' => $class_id)))->count();		
		return $query;
	}
	public function user_parent_count($user_id)
	{
		$parent_list = '';
		
		$class1 = TableRegistry::get('child_tbl');		
		$parent_list=$class1->find()->where(['child_id'=>$user_id])->count();		
		return $parent_list;
	}
	public function get_msg_status($msg_id,$user_id)
	{
		$status = 0;
		
		$class1 = TableRegistry::get('smgt_message_reciver');		
		$parent_list=$class1->find()->where(['message_id'=>$msg_id,'reciver_id'=>$user_id]);
		
		foreach($parent_list as $data)
		{
			$status = $data['status'];
		}
		return $status;
	}
	
	function fetchlogo()
	{
		$nm = '';
		
		$nm=$this->getfieldname('school_icon');
		return $nm;
	}
	
	public function get_class_id($id=0)
    {		
		$class=TableRegistry::get('classmgt');
		if($id)
		{
			$query = $class->find()->where(['class_id'=>$id])->hydrate(false)->toArray();
			if(!empty($query))
			{
				foreach ($query as $id1) 
				{
					return $id1['class_name'];
				}
			}
		}
	}
	public function get_teacher_classes($id)
    {
		$class_all = array();
		$class_nm = "";
		
		$users=TableRegistry::get('smgt_users');		
		$query = $users->find('all',array('conditions' => array('user_id'=>$id,'role'=>'teacher')));
		
		foreach ($query as $id) 
		{
			$class = explode(',',$id['classname']);
			foreach($class as $cls)
				$class_all[] = $this->get_class_id($cls);
		}
		$class_nm = implode(',',$class_all);
		return $class_nm;
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
	public function selected($val1,$val2)
	{
		if($val1 == $val2)
			return ' selected = "selected" ';
		else
			return '';
	}
	public function multiselected($value,$options)
	{
		if(in_array($value,$options))
			return 'selected';
		else
			return '';
	}
	public function checked($val1,$val2)
	{
		if($val1 == $val2)
			return ' checked = "checked" ';
		else
			return '';
	}
	public function get_user_id($id=0)
    {		
		$class=TableRegistry::get('smgt_users');
		if($id)
		{
			$query = $class->find('all',array('conditions' => array('user_id'=>$id)));
			
			foreach ($query as $id) 
			{
				$id1=$id['first_name']." ".$id['last_name'];
				return $id1;
			}
		}
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
	public function get_user_role($id=0)
    {		
		$class=TableRegistry::get('smgt_users');
		if($id)
		{
			$query = $class->find('all',array('conditions' => array('user_id'=>$id)));
			
			foreach ($query as $id) 
			{
				$id1=$id['role'];
				return $id1;
			}
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
	public function get_class_section($id)
    {
		$id1 = '';
		
		$class=TableRegistry::get('class_section');
		
		$query = $class->find('all',array('conditions' => array('class_section_id'=>$id)));
		
		foreach ($query as $id) 
		{
			$id1=$id['section_name'];
			return $id1;
		}
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
	
	public function hostel_name($id)
	{	
		$query = '';
		$class=TableRegistry::get('smgt_hostel');
		$query = $class->find('all',array('conditions' => array('hostel_id' => $id)))->select("hostel_name")->hydrate(false)->toArray();		
		if(!empty($query))
			return $query[0]['hostel_name'];
	}
	public function hostel_room_category_name($id)
	{	
		$query = '';
		$class=TableRegistry::get('smgt_hostel_room_category');		
		$query = $class->find('all',array('conditions' => array('hostel_room_category_id' => $id)))->select("category_name")->hydrate(false)->toArray();		
		if(!empty($query))
			return $query[0]['category_name'];
	}
	public function hostel_room_bed_unique_id($id)
	{	
		$query = '';
		$class=TableRegistry::get('smgt_hostel_room');		
		$query = $class->find('all',array('conditions' => array('room_id' => $id)))->select("room_unique_id")->hydrate(false)->toArray();		
		if(!empty($query))
			return $query[0]['room_unique_id'];
	}
	public function hostel_room_student_bed_unique_id($id)
	{		
		$data = array();
		
		$class=TableRegistry::get('smgt_assign_bed_new');		
		$query = $class->find('all',array('conditions' => array('bed_unique_id' => $id)))->hydrate(false)->toArray();		
		if(!empty($query))
		{
			$data['student_id'] = $query[0]['student_id'];
			$data['assign_date'] = $query[0]['assign_date'];
			$data['room_unique_id'] = $query[0]['room_unique_id'];
			$data['bed_unique_id'] = $query[0]['bed_unique_id'];
			
			return $data;
		}
	}
	public function hostel_room_student_room_unique_id($id)
	{		
		$data = array();
		
		$class=TableRegistry::get('smgt_assign_bed_new');		
		$query = $class->find('all',array('conditions' => array('student_id' => $id)))->hydrate(false)->toArray();		
		if(!empty($query))
		{
			$data['student_id'] = $query[0]['student_id'];
			$data['assign_date'] = $query[0]['assign_date'];
			$data['room_unique_id'] = $this->hostel_room_bed_unique_id($query[0]['room_unique_id']);
			$data['bed_unique_id'] = $query[0]['bed_unique_id'];
			
			return $data;
		}
	}
	public function hostel_room_count_bed_unique_id($id)
	{
		$final_cnt = 0;
		$smgt_hostel_room=TableRegistry::get('smgt_hostel_room');	
		$smgt_add_beds=TableRegistry::get('smgt_add_beds');
		
		$smgt_hostel_room1 = $smgt_hostel_room->find()->where(['room_unique_id'=>$id]);
		foreach($smgt_hostel_room1 as $hostel_data)
		{
			$beds_capacity = $hostel_data['beds_capacity'];
			
			$room_id = $hostel_data['room_id'];
			$smgt_assign_bed_new1 = $smgt_add_beds->find()->where(['room_unique_id'=>$room_id,'bed_status'=>1])->hydrate(false)->toArray();
			
		}
		$final_cnt = count($smgt_assign_bed_new1);
		return $final_cnt;
	}
	
	public function paas_teacher_performance($id)
	{
		$pass_cnt = 0;
		
		$conn=ConnectionManager::get('default');	
		
		$rs=$conn->execute("SELECT count(mark.student_id) as count1 FROM smgt_subject as sb 
		INNER JOIN smgt_marks as mark on sb.subid=mark.subject_id
		WHERE mark.marks >= 40
		AND sb.teacher_id = '".$id."'
		group by mark.subject_id");
		
		foreach($rs as $rs1)
		{
			$pass_cnt = $rs1['count1'];
		}
		return $pass_cnt;
	}
	public function fail_teacher_performance($id)
	{
		$fail_cnt = 0;
		
		$conn=ConnectionManager::get('default');	
		
		$rs=$conn->execute("SELECT count(mark.student_id) as count1 FROM smgt_subject as sb 
		INNER JOIN smgt_marks as mark on sb.subid=mark.subject_id
		WHERE mark.marks < 40
		AND sb.teacher_id = '".$id."'
		group by mark.subject_id");
		
		foreach($rs as $rs1)
		{
			$fail_cnt = $rs1['count1'];
		}
		return $fail_cnt;
	}
	public function get_stude_marks_report($stud_id,$exam_id,$sub_id)
    {
		$class=TableRegistry::get('smgt_marks');		
		$query = $class->find()->where(['student_id'=>$stud_id,'exam_id'=>$exam_id,'subject_id'=>$sub_id])->hydrate(false)->toArray();
		
		if(!empty($query))
		{
			foreach ($query as $id) 
			{
				return $id['marks'];			
			}
		}
		else
			return 0;
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
	public function get_user_class($id)
    {	
		$id1 = '';
		
		$class=TableRegistry::get('smgt_users');		
		$query = $class->find('all',array('conditions' => array('user_id'=>$id)));
		
		foreach ($query as $id) 
		{
			$id1=$id['classname'];
			return $id1;
		}
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
	public function get_child_id($user_id)
	{
		$parent_id = array();
		
		$class2 = TableRegistry::get('child_tbl');
		$userdt=$class2->find()->where(['child_parent_id'=>$user_id]);
		foreach($userdt as $data)
		{
			$parent_id[]=$data['child_id'];
		}
		if(isset($parent_id))
			return $parent_id;
	}
	public function get_class_list_user_id($id)
	{		
		$class_list = '';
		
		$class=TableRegistry::get('smgt_users');		
		$query = $class->find('all')->where(['user_id'=>$id]);
	
		foreach ($query as $id) 
		{
			$class_list = $id['classname'];
		}
		return $class_list;
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
	public function get_admission_main($type="")
    {
		$smgt_admission_main = TableRegistry::get('smgt_admission_main');
		$smgt_admission_main_find = $smgt_admission_main->find()->where(['meta_key'=>$type,'is_active'=>0])->hydrate(false)->toArray();
		if(!empty($smgt_admission_main_find))
			return $smgt_admission_main_find;
	}
	public function get_adminID()
    {
		$user_id = 0;
		$smgt_users = TableRegistry::get('smgt_admission_main');
		$smgt_users_find = $smgt_users->find()->where(['role'=>'admin'])->hydrate(false)->toArray();
		foreach ($smgt_users_find as $id) 
		{
			$user_id = $id['user_id'];
			return $user_id;
		}	
	}
	public function get_admission_value($id=0)
    {
		$title = '';
		if($id)
		{
			$smgt_users = TableRegistry::get('smgt_admission_main');
			$smgt_users_find = $smgt_users->find()->where(['adminssion_main_id'=>$id])->hydrate(false)->toArray();
			foreach ($smgt_users_find as $id) 
			{
				$title = $id['title'];
				return $title;
			}
		}
	}
	public function base64_encode_image ($filename=string,$filetype=string) 
	{
		if ($filename) {
			$imgbinary = fread(fopen($filename, "r"), filesize($filename));
			return 'data:image/' . $filetype . ';base64,' . base64_encode($imgbinary);
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
	public function get_student_homework_data($id=0,$user_id=0,$field_name='')
    {
		$id1 = '';
		
		$class = TableRegistry::get('smgt_student_homework');
		
		$query = $class->find()->where(['homework_id'=>$id,'student_id'=>$user_id]);
		
		foreach ($query as $id) 
		{
			$id1 = $id[$field_name];
		}
		return $id1;
	}
	public function check_attendence($class_id,$date)
	{
		$result = '';
		
		$class=TableRegistry::get('smgt_attendence');
		$result = $class->find('all',array('conditions' => array('class_id'=>$class_id,'attendence_date' => $date)))->hydrate(false)->toArray();
		if(!empty($result))
			return $result;
	}
	public function check_user_access_module($name='',$role='')
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
	
	public function viewheader_pdf()
	{
		return "<img src='{$this->request->base}/webroot/img/school-logo.png' style='margin-top:-20px;width:100%;height:55%;padding-right:28px;'>";
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
	
	public function term_name($id)
	{
		$query = '';
		
		$class=TableRegistry::get('tbl_term');		
		$query = $class->find('all',array('conditions' => array('term_id' => $id)))->select("term_name")->hydrate(false)->toArray();		
		if(!empty($query))
			return $query[0]['term_name'];
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
	
	public function get_exam_time_table_data($exam_id)
	{	
		$d = '';
		
		$class=TableRegistry::get('exam_time_table');		
		$query = $class->find('all',array('conditions' => array('exam_time_table_id' => $exam_id)))->hydrate(false)->toArray();
		if(!empty($query))
			return $query;
	}
	
	public function student_exam_receipt($user_id,$exam_id)
	{	
		$d = '';
		
		$class=TableRegistry::get('smgt_exam_hall_receipt');		
		$query = $class->find('all',array('conditions' => array('user_id' => $user_id, 'exam_id' => $exam_id)))->hydrate(false)->toArray();
		if(!empty($query))
			return $query[0]['receipt_id'];
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
	public function get_user_exam_hall_receipt($id=0)
    {		
		$class=TableRegistry::get('smgt_users');
		if($id)
		{
			$query = $class->find('all',array('conditions' => array('user_id'=>$id)));			
			foreach ($query as $id) 
			{
				$id1=$id['exam_hall_receipt'];				
			}
			return $id1;
		}
	}
	
	public function dateformat_PHP_to_jQueryUI($php_format)
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
	
	public function Exam_Dates()
	{
		$dates = array();
		$class=TableRegistry::get('smgt_exam');	
		$query = $class->find()->hydrate(false)->toArray();

		foreach ($query as $id) 
		{
			$date_from = strtotime($id['exam_date']);
			$date_to = strtotime($id['exam_end_date']);
						
			for($i=$date_from; $i<=$date_to; $i+=86400) 
			{
				$dates[] = date("Y-m-d", $i);
			}
		}
		if(!empty($dates))
			return $dates;
		
	}
	public function sub_stud_check_attendence($class_id,$date,$user_id)
	{
		$result = '';
		
		$class=TableRegistry::get('smgt_attendence');
		$result = $class->find('all',array('conditions' => array('class_id'=>$class_id,'attendence_date' => $date,'user_id' => $user_id)))->hydrate(false)->toArray();
		if(!empty($result))
			return $result;
	}
}