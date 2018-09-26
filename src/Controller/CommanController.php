<?php

namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Controller\Component;
use Cake\I18n\Time;
use PHPExcel;
use PHPExcel_Helper_HTML;
use PHPExcel_Writer_Excel2007;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Datasource\ConnectionManager;
use Gmgt_paypal_class;
use Cake\Mailer\Email;

class CommanController extends AppController
{
	public $comman_arr=array();

	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Et');
		$this->loadComponent('Flash');
		
		require_once(ROOT . DS .'vendor' . DS  . 'PHPExcel' . DS  . 'PHPExcel.php');
		require_once(ROOT . DS .'vendor' . DS  . 'PHPExcel' . DS  . 'PHPExcel' . DS  . 'Writer' . DS  . 'Excel2007.php');
		require_once(ROOT . DS .'vendor' . DS  . 'paypal' . DS . 'paypal_class.php');
		
		$this->stud_date = $this->Setting->getfieldname('date_format');
	}

	public function examlist()
	{
		$user_session_id=$this->request->session()->read('user_id');		
		$role=$this->Setting->get_user_role($user_session_id);
		$fetch_data = '';		
		$exam_table_register=TableRegistry::get('smgt_exam');
		
		if($role == 'student')
		{
			$class_id = $this->Setting->get_user_class($user_session_id);
			$fetch_data=$exam_table_register->find()->where(['class_id'=>$class_id])->hydrate(false)->toArray();
		}
		elseif($role == 'teacher')
		{
			$class_id = $this->Setting->get_class_list_teacher_id($user_session_id);
			if(!empty($class_id))
				$fetch_data=$exam_table_register->find()->where(['class_id IN'=>$class_id])->hydrate(false)->toArray();
		}
		elseif($role == 'parent')
		{
			$class_id = $this->Setting->get_parents_student_id($user_session_id);
			if(!empty($class_id))
				$fetch_data=$exam_table_register->find()->where(['class_id IN'=>$class_id])->hydrate(false)->toArray();
		}
		else
		{
			$fetch_data=$exam_table_register->find()->hydrate(false)->toArray();
		}
		if(!empty($fetch_data))
			$this->set('row',$fetch_data);
	}


    public function teacherlist()
	{
		
		$class = TableRegistry::get('Smgt_users');
		$query=$class->find()->where(['role'=>'teacher']);
		$this->set('it',$query);
		
		/* Student Module Start*/
		$user_session_id=$this->request->session()->read('user_id');
		
		$role=$this->Setting->get_user_role($user_session_id);
		
		$class_id=$this->Setting->get_user_class($user_session_id);
		
		$query1=$class->find()->where(['classname'=>$class_id,'role'=>'teacher']);
		
		$this->set('role',$role);
		$this->set('it1',$query1);
		/* End Student Module */
	}

    public function holidaylist()
	{	
		$fetch_data = array();	
		$holiday_table_register=TableRegistry::get('smgt_holiday');
		$fetch_data=$holiday_table_register->find()->hydrate(false)->toArray();
		if(!empty($fetch_data))
			$this->set('row',$fetch_data);
	}
	
	public function addstudent()
	{
		$login = $_SERVER['HTTP_HOST'].$this->request->base;
		
		$class = TableRegistry::get('Classmgt');
		
		$query=$class->find();
		$this->set('it',$query);
		
		
		if($this->request->is('post'))
		{
			$class1 = TableRegistry::get('Smgt_users'); 
			
			$email=$this->request->data['email'];
			$username=$this->request->data['username'];	
				
			$check_email = $class1->find()->where(['email'=>$email]);					
			$check_user = $class1->find()->where(['username'=>$username]);	
				
			if(!$check_email->isEmpty())
			{
				$this->Flash->success(__('Duplicate Email id'));
				$this->set('post_data',$this->request->data);
				
			}	
			if(!$check_user->isEmpty())
			{
				$this->Flash->success(__('Duplicate Username'));
				$this->set('post_data',$this->request->data);
					
			}			
			if (!$check_email->isEmpty() || !$check_user->isEmpty() ) 
			{
				$this->set('post_data',$this->request->data);
					
			}
			else
			{
				$studentID = $this->Setting->generate_studentID();
				
				$a = $class1->newEntity();
				
				$c1=$this->request->data;
				
				$hasher = new DefaultPasswordHasher();
				$password=$c1['password'];
				$pass = $hasher->hash($c1['password']);
				$c1['password']=$pass;
				
				$c1['working_hour']=null;
				$c1['position']=null;
				$c1['submitted_document']=null;
				$c1['child']=null;
				$c1['relation']=null;
				$c1['role']='student';
				$c1['studentID'] = $studentID['studentID'];
				$c1['studentID_prefix'] = $studentID['studentID_prefix'];
				
				$image=$this->Setting->getimage($c1['image']);
				
				if($image=='')
				{
					$c1['image']="profile.jpg";
				}
				else
				{
					$c1['image']=$this->request->data('image');
					$c1['image']=$c1['image']['name'];
				}												
				
				if($c1['status'] != '')
				{
					$c1['status']='Approved';
				}
				else{$c1['status']='Not Approved';}
				
				
				$a=$class1->patchEntity($a,$c1);
				
				if($class1->save($a))
				{
					$cid=$a['classname'];
					$username=$a['username'];							
					$role=$a['role'];
							
					$this->Flash->success(__('Student Registered Successfully', null), 
							'default', 
							 array('class' => 'success'));
							 
					$sys_email=$this->Setting->getfieldname('email'); 
					$school_name = $this->Setting->getfieldname('school_name');
					$get_user_class=$this->Setting->get_class_id($cid);
					$get_username=$this->Et->get_username1($username);
										
					$subject="";
					$mailtem = TableRegistry::get('smgt_emailtemplate');
					$format =$mailtem->find()->where(["find_by"=>"registration"])->hydrate(false)->toArray();
					
					$str=$format[0]['template'];
					$subject=$format[0]['subject'];
					
					$msgarray = explode(" ",$str);
					$subarray = explode(" ",$subject);
					
					$email_id=$c1['email'];

					$msgarray['{{student_name}}']=$c1['first_name']." ".$c1['last_name'];
					$msgarray['{{school_name}}']=$school_name;
					$msgarray['{{user_name}}']=$get_username;
					$msgarray['{{class_name}}']=$get_user_class;
					$msgarray['{{email}}']=$c1['email'];
										
					$subarray['{{student_name}}']=$c1['first_name']." ".$c1['last_name'];
					$subarray['{{school_name}}']=$school_name;
					$subarray['{{user_name}}']=$get_username;
					$subarray['{{class_name}}']=$get_user_class;
					$subarray['{{email}}']=$c1['email'];

					$datamsg = str_replace(array_keys($msgarray),array_values($msgarray),$str);
					$submsg = str_replace(array_keys($subarray),array_values($subarray),$subject);
					
					if($email_id != '')
					{
						$email = new Email('default');
						$to = $email_id;									
						$message = $datamsg;
						
						/* $email->from([$sys_email => $school_name])
						 ->to($to)
						->subject( _($submsg))
						->send($message); */
						
						$sys_name = $school_name;
						$sys_email = $sys_email;
						$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
						@mail($to,_($submsg),$message,$headers);

					}

					$sys_email=$this->Setting->getfieldname('email'); 
					$school_name = $this->Setting->getfieldname('school_name');
					$get_username=$this->Et->get_username1($username);
					$get_password=$this->Et->get_password($password);
					$get_role=$this->Et->get_role($role);
					$subject="";
					$mailtem = TableRegistry::get('smgt_emailtemplate');
					$format =$mailtem->find()->where(["find_by"=>"Add_User"])->hydrate(false)->toArray();
					
					$str=$format[0]['template'];
					$subject=$format[0]['subject'];
					
					$msgarray = explode(" ",$str);
					$subarray = explode(" ",$subject);
					
					$loginlink = $_SERVER['SERVER_NAME'].$this->request->base;
					$email_id=$c1['email'];

					$msgarray['{{user_name}}']=$get_username;
					$msgarray['{{username}}']=$get_username;
					$msgarray['{{school_name}}']=$school_name;
					$msgarray['{{role}}']=$get_role;
					$msgarray['{{login_link}}']=$loginlink;
					$msgarray['{{Password}}']=$password;

					$subarray['{{user_name}}']=$get_username;
					$subarray['{{username}}']=$get_username;
					$subarray['{{school_name}}']=$school_name;
					$subarray['{{role}}']=$get_role;
					$subarray['{{login_link}}']=$loginlink;
					$subarray['{{Password}}']=$password;

					$datamsg = str_replace(array_keys($msgarray),array_values($msgarray),$str);
					$submsg = str_replace(array_keys($subarray),array_values($subarray),$subject);

					if($email_id != '')
					{
						$email = new Email('default');
						$to = $email_id;
						$message = $datamsg;
						
						/* $email->from([$sys_email => $school_name])
						 ->to($to)
						->subject( _($submsg))
						->send($message); */

						$sys_name = $school_name;
						$sys_email = $sys_email;
						$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
						@mail($to,_($submsg),$message,$headers);

					}

					$techer="";
					$teacher_email="";
					$sys_email=$this->Setting->getfieldname('email'); 
					$school_name = $this->Setting->getfieldname('school_name');
					$get_role_teacher=$this->Et->get_role_teacher($cid);


					foreach($get_role_teacher as $get_role_teacher1)
					{
						$techer=$get_role_teacher1['first_name']." ".$get_role_teacher1['last_name'];
						$teacher_email=$get_role_teacher1['email'];
						
						
						$subject="";
						$mailtem = TableRegistry::get('smgt_emailtemplate');
						$format =$mailtem->find()->where(["find_by"=>"Student Assign to Teacher mail template"])->hydrate(false)->toArray();
						$str=$format[0]['template'];
						$subject=$format[0]['subject'];
						
						$msgarray = explode(" ",$str);
						$subarray = explode(" ",$subject);
												
						$email_id=$teacher_email;

						$msgarray['{{teacher_name}}']=$techer;
						$msgarray['{{school_name}}']=$school_name;
						$msgarray['{{student_name}}']=$c1['first_name']." ".$c1['last_name'];
						
						$subarray['{{teacher_name}}']=$techer;
						$subarray['{{school_name}}']=$school_name;
						$subarray['{{student_name}}']=$c1['first_name']." ".$c1['last_name'];
						
						$datamsg = str_replace(array_keys($msgarray),array_values($msgarray),$str);
						$submsg = str_replace(array_keys($subarray),array_values($subarray),$subject);

						if($email_id != '')
						{
							$email = new Email('default');
							$to = $email_id;
							$message = $datamsg;
							
							/* $email->from([$sys_email => $school_name])
							 ->to($to)
							->subject( _($submsg))
							->send($message); */

							$sys_name = $school_name;
							$sys_email = $sys_email;
							$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
							@mail($to,_($submsg),$message,$headers);

						}					
					}

					$techer="";
					$teacher_email="";
					$sys_email=$this->Setting->getfieldname('email'); 
					$school_name = $this->Setting->getfieldname('school_name');
					$get_role_teacher=$this->Et->get_role_teacher($cid);
					$get_user_class=$this->Setting->get_class_id($cid);

					foreach($get_role_teacher as $get_role_teacher1)
					{
						$techer=$get_role_teacher1['first_name']." ".$get_role_teacher1['last_name'];
						$teacher_email=$get_role_teacher1['email'];

						$mailtem = TableRegistry::get('smgt_emailtemplate');
						$format =$mailtem->find()->where(["find_by"=>"Student Assigned to Teacher Student mail template"])->hydrate(false)->toArray();
						$str=$format[0]['template'];
						$subject=$format[0]['subject'];
						
						$msgarray = explode(" ",$str);
						$subarray = explode(" ",$subject);
						
						$email_id=$c1['email'];

						$msgarray['{{teacher_name}}']=$techer;
						$msgarray['{{school_name}}']=$school_name;
						$msgarray['{{student_name}}']=$c1['first_name']." ".$c1['last_name'];
						$msgarray['{{class_name}}']=$get_user_class;

						$subarray['{{teacher_name}}']=$techer;
						$subarray['{{school_name}}']=$school_name;
						$subarray['{{student_name}}']=$c1['first_name']." ".$c1['last_name'];
						$subarray['{{class_name}}']=$get_user_class;

						$datamsg = str_replace(array_keys($msgarray),array_values($msgarray),$str);
						$submsg = str_replace(array_keys($subarray),array_values($subarray),$subject);

						if($email_id != '')
						{
							$email = new Email('default');
							$to = $email_id;
							$message = $datamsg;
							
							/* $email->from([$sys_email => $school_name])
							 ->to($to)
							->subject( _($submsg))
							->send($message); */

							$sys_name = $school_name;
							$sys_email = $sys_email;
							$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
							@mail($to,_($submsg),$message,$headers);

						}
					}
				}
			}
		}	
	}
	public function updatestudent($id)
	{
		if($id)
		{
			$class = TableRegistry::get('Classmgt');
		
			$query=$class->find();
			$this->set('cls',$query);
		
			$class = TableRegistry::get('Smgt_users');
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $class->exists(['user_id' => $id]);
			
			if($exists)
			{
				$item = $class->get($id);
				
				if($this->request->is(['post','put']))
				{			
						$img2=$this->request->data();

						if($img2['status'] != '')
						{
							$img2['status']='Approved';
						}
						else{$img2['status']='Not Approved';}
						
						$xyz1=$this->Setting->getimage($img2['image']);
				
						$old_value = $this->request->data('image2');
						$img2['image']=$old_value;
						
						if($xyz1!='')
						{
							$img2['image']=$xyz1;
						}
					
						$item = $class->patchEntity($item,$img2);
					
						if($class->save($item))
						{
						
							$this->Flash->success(__('Student Record Updated Successfully', null), 
								'default', 
								 array('class' => 'alert alert-success'));
							
						}
						return $this->redirect(['action'=>'studentlist']);
				}
				$this->set('it',$item);
			}
			else
				return $this->redirect(['action'=>'studentlist']);
		}
		else
			return $this->redirect(['action'=>'studentlist']);
	}
	
    public function studentlist()
	{
		$user_session_id=$this->request->session()->read('user_id');
		$role=$this->Setting->get_user_role($user_session_id);
		
		$class = TableRegistry::get('Smgt_users');
				
		if(isset($_POST['filter_class']))
		{
			$data=$this->request->data();
			
			if(isset($data['select_stud']))
			{
				$cls_id=$data['select_stud'];
				$this->set('cls_id',$cls_id);
			}
			if(isset($data['section']))
			{
				$section_id=$data['section'];
				$this->set('sec_id',$section_id);
			}
			
			$clsname = '';
			
			if(isset($data['select_stud']) && !empty($data['select_stud']))
			{
				if($role == 'teacher')
				{
					$class_id = $this->Setting->get_user_class_list($user_session_id);
					$access = $this->Setting->get_teacher_access();
					$classid = $this->Setting->get_class_list_teacher_id($user_session_id);
					
					if($access['chkstud'] == 'own_cls_stud')
					{
						if(isset($data['section']) && !empty($data['section']))
						{
							if(!empty($classid))
							{
								$parent_stud = $class->find()->where(['classname IN'=>$classid,'classsection'=>$section_id,'role'=>'student','is_deactive'=>'0'])->hydrate(false)->toArray();
							}
						}
						else
						{
							if(isset($data['select_stud']) && !empty($data['select_stud']))
							{
								$parent_stud = $class->find()->where(['classname'=>$cls_id,'role'=>'student','is_deactive'=>'0'])->hydrate(false)->toArray();
							}
							else
							{	
								if(!empty($classid))
								{
									$parent_stud = $class->find()->where(['classname IN'=>$classid,'role'=>'student','is_deactive'=>'0'])->hydrate(false)->toArray();
								}
							}
						}
					}
					else
					{
						if(isset($data['section']) && !empty($data['section']))
							$parent_stud = $class->find()->where(['classname'=>$cls_id,'classsection'=>$section_id,'role'=>'student','is_deactive'=>'0'])->hydrate(false)->toArray();
						else
						{
							if(isset($data['select_stud']) && !empty($data['select_stud']))
								$parent_stud = $class->find()->where(['classname'=>$cls_id,'role'=>'student','is_deactive'=>'0'])->hydrate(false)->toArray();
							else	
								$parent_stud = $class->find()->where(['role'=>'student','is_deactive'=>'0'])->hydrate(false)->toArray();
						}
					}
				}
				elseif($role == 'student')
				{
					$class_id=$this->Setting->get_user_class($user_session_id);
					if(isset($data['section']) && !empty($data['section']))
						$parent_stud = $class->find()->where(['classname'=>$class_id,'classsection'=>$section_id,'role'=>'student','is_deactive'=>'0'])->hydrate(false)->toArray();
					else	
						$parent_stud = $class->find()->where(['classname'=>$class_id,'role'=>'student','is_deactive'=>'0'])->hydrate(false)->toArray();
				}
				elseif($role == 'parent')
				{
					$classid = $this->Setting->get_parents_student_id($user_session_id);
					if(isset($data['section']) && !empty($data['section']))
					{
						if(!empty($classid))
						{
							$parent_stud = $class->find()->where(['classname IN'=>$classid,'classsection'=>$section_id,'role'=>'student','is_deactive'=>'0'])->hydrate(false)->toArray();
						}
					}
					else
					{
						if(isset($data['select_stud']) && !empty($data['select_stud']))
						{
							$parent_stud = $class->find()->where(['classname'=>$cls_id,'role'=>'student','is_deactive'=>'0'])->hydrate(false)->toArray();
						}
						else
						{
							if(!empty($classid))
							{
								$parent_stud = $class->find()->where(['classname IN'=>$classid,'role'=>'student','is_deactive'=>'0'])->hydrate(false)->toArray();
							}
						}
					}
				}
				else
				{
					$parent_stud = $class->find()->where(['role'=>'student','is_deactive'=>'0'])->hydrate(false)->toArray();
				}
			}
			else{
				
				$clsname=$this->Setting->get_class_id($cls_id);
				if(isset($data['section']) && !empty($data['section']))
					$parent_stud=$class->find()->where(['classname'=>$cls_id,'classsection'=>$section_id,'role'=>'student','is_deactive'=>'0'])->hydrate(false)->toArray();
				else
					$parent_stud=$class->find()->where(['classname'=>$cls_id,'role'=>'student','is_deactive'=>'0'])->hydrate(false)->toArray();
			}
			
			$this->set('clsname',$clsname);
			if(!empty($parent_stud))
				$this->set('parent_stud',$parent_stud);
		}
		else
		{
			if($role == 'student')
			{
				$class_id=$this->Setting->get_user_class($user_session_id);
				$query = $class->find()->where(['classname'=>$class_id,'role'=>'student','is_deactive'=>'0'])->hydrate(false)->toArray();			
				$classname=$this->Setting->get_class_id($class_id);						
				$this->set('classname',$classname);
				$this->set('class_id',$class_id);
			}
			elseif($role == 'teacher')
			{
				$access = $this->Setting->get_teacher_access();
				$classid = $this->Setting->get_class_list_teacher_id($user_session_id);
				$class_id = $this->Setting->get_user_class_list($user_session_id);

				if($access['chkstud'] == 'own_cls_stud')
				{
					if(!empty($classid))
					{
						$query = $class->find()->where(['classname IN'=>$classid,'role'=>'student','is_deactive'=>'0'])->hydrate(false)->toArray();
					}
				}
				else
					$query = $class->find()->where(['role'=>'student','is_deactive'=>'0'])->hydrate(false)->toArray();
			}
			elseif($role == 'parent')
			{
					$class_ids = $this->Setting->get_parents_student_id($user_session_id);	
					if(!empty($class_ids))
					{
						$query = $class->find()->where(['classname IN'=>$class_ids,'role'=>'student','is_deactive'=>'0'])->hydrate(false)->toArray();
					}
			}
			else
			{	
				$query = $class->find()->where(['role'=>'student','is_deactive'=>'0'])->hydrate(false)->toArray();
			}
			
			if(!empty($query))
				$this->set('parent_stud',$query);
		}
		
		$class_table_register=TableRegistry::get('classmgt');
		
		if($role == 'teacher')
		{
			$access = $this->Setting->get_teacher_access();
			$classid = $this->Setting->get_class_list_teacher_id($user_session_id);
			
			if($access['chkstud'] == 'own_cls_stud')
			{
				if(!empty($classid))
				{
					$get_all_class_record=$class_table_register->find("list",["keyField"=>"class_id","valueField"=>"class_name"])->where(['class_id IN'=>$classid])->hydrate(false)->toArray();
				}
			}
			else
				$get_all_class_record=$class_table_register->find("list",["keyField"=>"class_id","valueField"=>"class_name"])->hydrate(false)->toArray();
		}
		if($role == 'parent')
		{
			$classid = $this->Setting->get_parents_student_id($user_session_id);
			if(!empty($classid))
			{
				$get_all_class_record=$class_table_register->find("list",["keyField"=>"class_id","valueField"=>"class_name"])->where(['class_id IN'=>$classid])->hydrate(false)->toArray();
			}	
		}	
		else
		{
			$get_all_class_record=$class_table_register->find("list",["keyField"=>"class_id","valueField"=>"class_name"])->hydrate(false)->toArray();
		}
		if(!empty($get_all_class_record))
			$this->set('class_data',$get_all_class_record);
		
		$this->set('user_session_id',$user_session_id);
		$this->set('role',$role);
	}
	
	public function childlist()
    {
		$user_session_id=$this->request->session()->read('user_id');
		
		$child_id=$this->Setting->get_child_id($user_session_id);
		
		$role=$this->Setting->get_user_role($user_session_id);
		
		if($child_id)
		{
			foreach($child_id as $c)
			{
				$cls_id=$this->Setting->get_user_class($c);
				$clsname[]=$this->Setting->get_class_id($cls_id);
				$name[]=$this->Setting->get_user_id($c);
				$photo[]=$this->Setting->get_user_image($c);
				$roll_no[]=$this->Setting->get_roll_no($c);
				$email[]=$this->Setting->get_user_email_id($c);
			}
			
			$this->set('photo',$photo);
			$this->set('name',$name);
			$this->set('clsname',$clsname);
			$this->set('roll_no',$roll_no);
			$this->set('email',$email);
			$this->set('child_id',$child_id);
		}
		
		$this->set('role',$role); 
	}
	
	public function studentattendance($id)
	{		
		if(isset($id))
		{
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$this->set('id',$id);
			
			$class = TableRegistry::get('smgt_users');
			
			$exists = $class->exists(['user_id' => $id]);
			
			if($exists)
			{
				$u_id=$class->get($id);
				
				$user_id=$u_id['user_id'];

				$name=$u_id['first_name']." ".$u_id['last_name'];
				$this->set('name',$name);
				
				$currt_dt=Time::now();
				$current_date=date("Y-m-d", strtotime($currt_dt));

				$this->set('current_date',$current_date);

				if(isset($_POST['view_attendance']))
				{			
					$start_date = $_REQUEST['sdate'];
					$end_date = $_REQUEST['edate'];
					
					$attendance=$this->Setting->smgt_view_student_attendance($start_date,$end_date,$user_id);
					
					$curremt_date =$start_date;
					$this->set('curremt_date',$curremt_date);
					$this->set('end_date',$end_date);
					
					while($end_date >= $curremt_date)
					{
						$attendance_status[] =$this->Setting->smgt_get_attendence($user_id,$curremt_date);			
						
						$attendance_comment[] =$this->Setting->smgt_get_attendence_comment($user_id,$curremt_date);
						$curremt_date = strtotime("+1 day", strtotime($curremt_date));
						$curremt_date = date("Y-m-d", $curremt_date);							
					}
					$this->set('attendance_comment',$attendance_comment);
						$this->set('attendance_status',$attendance_status);			
				}
			}
			else
				return $this->redirect(['action'=>'childlist']);
		}
		else
			return $this->redirect(['action'=>'childlist']);
	}
	
	public function studentsubjectattendance($id)
    {
		if(isset($id))
		{
			$class3=TableRegistry::get('smgt_subject');
			
			$id = $this->Setting->my_simple_crypt($id,'d');	
			
			$class = TableRegistry::get('smgt_users');
			$exists = $class->exists(['user_id' => $id]);
			
			if($exists)
			{
				$u_id=$class->get($id);
				
				$user_id=$u_id['user_id'];
				
				$class_id=$this->Setting->get_class_list_user_id($user_id);
				$this->set('class_id',$class_id);			
				
				$get_data=$class3->find()->where(['class_id'=>$class_id]);
				$this->set('get_data',$get_data);
				
				$name=$u_id['first_name']." ".$u_id['last_name'];
				$this->set('name',$name);
				$this->set('id',$id);
				
				$currt_dt=Time::now();
				$current_date=date("Y-m-d", strtotime($currt_dt));

				$this->set('current_date',$current_date);
				
				if(isset($_POST['view_attendance']))
				{
				
					$start_date = $_REQUEST['sdate'];
					$end_date = $_REQUEST['edate'];
					$subject_id=$_REQUEST['sub_id'];
					$this->set('s_id',$subject_id);
					
					$sub_name=$this->Setting->get_subject_id($subject_id);
					
					$attendance=$this->Setting->smgt_view_subject_attendance($start_date,$end_date,$user_id,$subject_id);
					
					$curremt_date =$start_date;
					$this->set('curremt_date',$curremt_date);
					$this->set('end_date',$end_date);
					
					$this->set('sub_name',$sub_name);
					
					while($end_date >= $curremt_date)
					{
						$attendance_status[] =$this->Setting->smgt_get_subject_attendence($user_id,$subject_id,$curremt_date);			
						
						$attendance_comment[] =$this->Setting->smgt_get_subject_attendence_comment($user_id,$subject_id,$curremt_date);
						$curremt_date = strtotime("+1 day", strtotime($curremt_date));
						$curremt_date = date("Y-m-d", $curremt_date);
						
					}
					$this->set('attendance_comment',$attendance_comment);
					$this->set('attendance_status',$attendance_status);
				}
			}
			else
				return $this->redirect(['action'=>'childlist']);
		}
		else
			return $this->redirect(['action'=>'childlist']);
    }
	
	public function addmarks()
	{
		
		$class1=TableRegistry::get('smgt_exam');	
		$query1=$class1->find("list",["keyField"=>"exam_id","valueField"=>"exam_name"]);
		$this->set('exam_id',$query1);
		
		$class2 = TableRegistry::get('Classmgt');			
		$query2 = $class2->find("list",["keyField"=>"class_id","valueField"=>"class_name"]);			
		$this->set('class_id',$query2);
		
		$class3=TableRegistry::get('smgt_subject');
		$query3=$class3->find();
		
		$section = TableRegistry::get('class_section');			
		$section_id = $section->find();			
		$this->set('section_id',$section_id);
			
		if($this->request->is('post'))
		{
			$class_mark = TableRegistry::get('smgt_marks'); 
						
			$data=$this->request->data();
			
			$exam_id=$data['exam_id'];
			$class_id=$data['class_id'];
			$subject_id=$data['sub_id'];
			
			$section_id=$data['section'];
			$this->set('sec_id',$section_id);
						
			$class4=TableRegistry::get('smgt_users');
			$query4=$class4->find()->where(['classname'=>$class_id,'role'=>'student']);
			
			$this->set('user',$query4);
			$this->set('e_id',$exam_id);
			$this->set('c_id',$class_id);
			
			$sub_set_name=$class3->find()->where(['subid'=>$subject_id]);
						
			foreach($sub_set_name as $name)
			{
				$sub_nm=$name['sub_name'];
			}
			$this->set('s_id',$subject_id);
			$this->set('sub_nm',$sub_nm);
			
			$user_id=$this->request->session()->read('user_id');
			
			$tbl_mark_value=$class_mark->find();
					
			$this->set('marktabel',$tbl_mark_value);
			
			if(isset($_POST['add_mark']))
			{
				
				$value=$_POST['add_mark'];
				
				$mark_data=$this->Setting->check_mark_id($exam_id,$class_id,$subject_id,$value);
				
				$this->set('mark_data',$mark_data);
				
				$mark=$data['marks_'.$value];
				$comment=$data['marks_comment_'.$value];				
								
				if(!$mark_data)
				{
					
					$a=$class_mark->newEntity();
					
					$data['exam_id']=$exam_id;
					$data['class_id']=$class_id;
					$data['subject_id']=$subject_id;
					$data['marks']=$mark;
					$data['attendance']=0;
					$data['grade_id']=$this->Setting->grade_mark($mark);
					$data['student_id']=$value;
					$data['marks_comment']=$comment;
					$data['created_date']=Time::now();
					$data['modified_date']=Time::now();
					$data['created_by']=$user_id;
										
					$a=$class_mark->patchEntity($a,$data);
						
					if($class_mark->save($a))
					{
						$this->Flash->success(__('Mark added Successfully', null), 
								   'default', 
									array('class' => 'success'));
									
					}
		
				}
				else
				{
								
					$id = $class_mark->get($mark_data);
					
					$user_id=$this->request->session()->read('user_id');
										
					$data['exam_id']=$exam_id;
					$data['class_id']=$class_id;
					$data['subject_id']=$subject_id;
					$data['marks']=$mark;
					$data['attendance']=0;
					$data['grade_id']=$this->Setting->grade_mark($mark);
					$data['student_id']=$value;
					$data['marks_comment']=$comment;
					$data['modified_date']=Time::now();
					$data['created_by']=$user_id;
										
					$a=$class_mark->patchEntity($id,$data);
						
					if($class_mark->save($a))
					{
						$this->Flash->success(__('Mark Updated Successfully', null), 
								   'default', 
									array('class' => 'success'));
					}
				}
			}
			
			if(isset($_POST['save_all_marks']))
			{
				$t=0;
				$temp=0;
				
				foreach($query4	as $class_data)
				{
					$u_id=$class_data['user_id'];
					
					$mark_data=$this->Setting->check_mark_id($exam_id,$class_id,$subject_id,$u_id);
				
					$this->set('mark_data',$mark_data);
				
					$mark=$data['marks_'.$u_id];
					$comment=$data['marks_comment_'.$u_id];
					
					if(!$mark_data)
					{
						
						$a=$class_mark->newEntity();
						
						$data['exam_id']=$exam_id;
						$data['class_id']=$class_id;
						$data['subject_id']=$subject_id;
						$data['marks']=$mark;
						$data['attendance']=0;
						$data['grade_id']=$this->Setting->grade_mark($mark);
						$data['student_id']=$u_id;
						$data['marks_comment']=$comment;
						$data['created_date']=Time::now();
						$data['modified_date']=Time::now();
						$data['created_by']=$user_id;
						
						
						$a=$class_mark->patchEntity($a,$data);
						
						if($class_mark->save($a))
						{
							$t=1;
						}
			
					}
					else{
						
						
						$id = $class_mark->get($mark_data);
						
						$user_id=$this->request->session()->read('user_id');
						
						
						$data['exam_id']=$exam_id;
						$data['class_id']=$class_id;
						$data['subject_id']=$subject_id;
						$data['marks']=$mark;
						$data['attendance']=0;
						$data['grade_id']=$this->Setting->grade_mark($mark);
						$data['student_id']=$u_id;
						$data['marks_comment']=$comment;
						$data['modified_date']=Time::now();
						$data['created_by']=$user_id;
						
						
						$a=$class_mark->patchEntity($id,$data);
							
						if($class_mark->save($a))
						{
							$temp=1;
						}
					
					}
				}
				if($t == 1)
				{
					$this->Flash->success(__('Mark added Successfully', null), 
									   'default', 
										array('class' => 'success'));
				}
				if($temp == 1)
				{
					$this->Flash->success(__('Mark Updated Successfully', null), 
									   'default', 
										array('class' => 'success'));
				}
			}
		}
		
		
	}
	public function showdata($id = null)
	{
			$class2=TableRegistry::get('classmgt');
		
			$query2=$class2->find();
			
			$class3=TableRegistry::get('smgt_subject');
		
			$this->autoRender=false;
			
			if($this->request->is('ajax'))
			{
				$get_id=$_POST['id'];
				
				$get_cls=$class2->get($get_id);
				
				$get_class=$get_cls['class_id'];
				
				$get_data=$class3->find()->where(['class_id'=>$get_class]);
				$this->set('get_data',$get_data);
				
				?>
				
				<select class="form-control validate[required]" name="sub_id">
					<option value=""><?php echo __('Select Subject');?></option>
				<?php
					foreach ($get_data as $d) 
					{
						?>
					
							<option value="<?php echo $d['subid']; ?>"><?php echo $d['sub_name']; ?></option>
					<?php
					$id=$d['subid'];
					$name=$d['sub_name'];
					}
					?>
					</select>
					
					<?php
			
					
				
			}
	}
	
	public function exploremark()
    {
		$class1=TableRegistry::get('smgt_exam');	
		$query1=$class1->find("list",["keyField"=>"exam_id","valueField"=>"exam_name"]);
		$this->set('exam_id',$query1);
		
		$class2 = TableRegistry::get('Classmgt');			
		$query2 = $class2->find("list",["keyField"=>"class_id","valueField"=>"class_name"]);			
		$this->set('class_id',$query2);
		
		$class3=TableRegistry::get('smgt_subject');
		$query3=$class3->find();
		
		
		if(isset($_POST['export_marks']))
		{

			$data=$this->request->data();
			
			$exam_id=$data['exam_id'];
			$class_id=$data['class_id'];
			$subject_list = $this->Setting->get_subject($class_id);
		
			$class4=TableRegistry::get('smgt_users');
			$student=$class4->find()->where(['classname'=>$class_id,'role'=>'student']);
			
			$this->set('user',$student);
			$this->set('e_id',$exam_id);
			$this->set('c_id',$class_id);
			
			$header = array();
			$marks = array();
			$header[] = 'Roll No';
			$header[] = 'Student Name';
			
			$subject_array = array();
			if(!empty($subject_list))
			{
				foreach($subject_list as $result)
				{
					$header[]=$result->sub_name;
					$subject_array[] = $result->subid;
				}
			}
			
			$header[]= 'Total';
			
			$filename = WWW_ROOT.'Reports\export_marks.csv';
			$file_chk = file_exists ( $filename );
			
			if($file_chk)
			{
				$file_path = $filename;
				$fh =fopen($file_path, 'w');
				
				$class_header[] = 'Class';
				$class_header[] = $this->Setting->get_class_id($class_id);
				
				fputcsv($fh, $class_header);
				fputcsv($fh, $header);
				
				foreach($student as $user)
				{
					$row = array();
					$row[] =  $this->Setting->get_roll_no($user['user_id']);
					$row[] = $this->Setting->get_user_id($user['user_id']);
					
					$total = 0;
					if(!empty($subject_array))
					{
						$total = 0;
						foreach($subject_array as $sub_id)
						{	

							$marks = $this->Setting->get_mark($exam_id,$class_id,$sub_id,$user['user_id']);
			
							if($marks)
							{
								$row[] =  $marks;
								$total += $marks;
							}
							else	
								$row[] = 0;
						}
						$row[] = $total ;
					}
					fputcsv($fh, $row);
				}
				fclose($fh);
				
				$filename = WWW_ROOT.'Reports\export_marks.csv';
				$file_path=$filename;
				
				$mime = 'text/plain';
				header('Content-Type:application/force-download');
				header('Pragma: public');       // required
				header('Expires: 0');           // no cache
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file_path)).' GMT');
				header('Cache-Control: private',false);
				header('Content-Type: '.$mime);
				header('Content-Disposition: attachment; filename="'.basename($file_path).'"');
				header('Content-Transfer-Encoding: binary');
				//header('Content-Length: '.filesize($file_name));      // provide file size
				header('Connection: close');
				readfile($file_path);		
				exit;	
			}
		}	
    }
	
	public function addmultiplemark()
	{
		$class1=TableRegistry::get('smgt_exam');	
		$query1=$class1->find("list",["keyField"=>"exam_id","valueField"=>"exam_name"]);
		$this->set('exam_id',$query1);
		
		$class2 = TableRegistry::get('Classmgt');			
		$query2 = $class2->find("list",["keyField"=>"class_id","valueField"=>"class_name"]);			
		$this->set('class_id',$query2);
		
		$section = TableRegistry::get('class_section');			
		$section_id = $section->find();			
		$this->set('section_id',$section_id);
		
		$teacher_msg_all_stud = $this->Setting->getfieldname('teacher_msg_all_stud');
		
		$user_session_id=$this->request->session()->read('user_id');
		$role=$this->Setting->get_user_role($user_session_id);
		
		if($this->request->is('post'))
		{
			$class_mark = TableRegistry::get('smgt_marks'); 
			
			$data=$this->request->data();
			
			$exam_id=$data['exam_id'];
			$class_id=$data['class_id'];
			
			$section_id=$data['section'];
			$this->set('sec_id',$section_id);
			
			$class4=TableRegistry::get('smgt_users');
			$query4=$class4->find()->where(['classname'=>$class_id,'role'=>'student']);
			
			$this->set('user',$query4);
			$this->set('e_id',$exam_id);
			$this->set('c_id',$class_id);
			
			$class3=TableRegistry::get('smgt_subject');
			
			if($role == 'teacher' && $teacher_msg_all_stud == 'no')
				$query3=$class3->find()->where(['class_id'=>$class_id,'teacher_id'=>$user_session_id]);
			else	
				$query3=$class3->find()->where(['class_id'=>$class_id]);
			
			$this->set('sub_m_data',$query3);
			
			$user_id=$this->request->session()->read('user_id');
			
			$tbl_mark_value=$class_mark->find();
					
			$this->set('marktabel',$tbl_mark_value);
			
			if(isset($_POST['add_single_student_mark']))
			{
				$value=$_POST['add_single_student_mark'];
				$t=0;
				$temp=0;
				foreach($query3 as $data_subject)
				{
					
					$mark = $_REQUEST['marks_'.$value.'_'.$data_subject['subid'].'_mark'];
					$comment = $_REQUEST['marks_'.$value.'_'.$data_subject['subid'].'_comment'];
					
					$mark_data=$this->Setting->check_mark_id($exam_id,$class_id,$data_subject['subid'],$value);
					
					if(!isset($mark_data))
					{
						$a=$class_mark->newEntity();
						
						$data['exam_id']=$exam_id;
						$data['class_id']=$class_id;
						$data['subject_id']=$data_subject['subid'];
						$data['marks']=$mark;
						$data['attendance']=0;
						$data['grade_id']=$this->Setting->grade_mark($mark);
						$data['student_id']=$value;
						$data['marks_comment']=$comment;
						$data['created_date']=Time::now();
						$data['modified_date']=Time::now();
						$data['created_by']=$user_id;
						
						
						$a=$class_mark->patchEntity($a,$data);
							
						if($class_mark->save($a))
						{
							$temp=1;
						}
						
					}
					else
					{
						
						$id = $class_mark->get($mark_data);
						
						$user_id=$this->request->session()->read('user_id');
						
						$data['exam_id']=$exam_id;
						$data['class_id']=$class_id;
						$data['subject_id']=$data_subject['subid'];
						$data['marks']=$mark;
						$data['attendance']=0;
						$data['grade_id']=$this->Setting->grade_mark($mark);
						$data['student_id']=$value;
						$data['marks_comment']=$comment;
						$data['modified_date']=Time::now();
						$data['created_by']=$user_id;
						
						
						$a=$class_mark->patchEntity($id,$data);
							
						if($class_mark->save($a))
						{
							$t=2;
						}
				
					}
				
				}
				if($temp == 1)
				{
					$this->Flash->success(__('Subject Mark Added Successfully', null), 
									   'default', 
										array('class' => 'success'));
				}
				if($t == 2)
				{
					$this->Flash->success(__('Subject Mark Updated Successfully', null), 
									   'default', 
										array('class' => 'success'));
				}
			}
			if(isset($_POST['save_all_multiple_subject_marks']))
			{
				$t=0;
				$temp=0;
				
				foreach($query4 as $user_data)
				{
					foreach($query3 as $sub_data)
					{
						$mark = $_REQUEST['marks_'.$user_data['user_id'].'_'.$sub_data['subid'].'_mark'];
						$comment = $_REQUEST['marks_'.$user_data['user_id'].'_'.$sub_data['subid'].'_comment'];
					
						$mark_data=$this->Setting->check_mark_id($exam_id,$class_id,$sub_data['subid'],$user_data['user_id']);
							
						if(!isset($mark_data))
						{
						
							$a=$class_mark->newEntity();
							
							$data['exam_id']=$exam_id;
							$data['class_id']=$class_id;
							$data['subject_id']=$sub_data['subid'];
							$data['marks']=$mark;
							$data['attendance']=0;
							$data['grade_id']=$this->Setting->grade_mark($mark);
							$data['student_id']=$user_data['user_id'];
							$data['marks_comment']=$comment;
							$data['created_date']=Time::now();
							$data['modified_date']=Time::now();
							$data['created_by']=$user_id;
							
							
							$a=$class_mark->patchEntity($a,$data);
							
							if($class_mark->save($a))
							{
								$t=1;
							}
				
						}
						else
						{
												
							$id = $class_mark->get($mark_data);
							
							$user_id=$this->request->session()->read('user_id');
							
							
							$data['exam_id']=$exam_id;
							$data['class_id']=$class_id;
							$data['subject_id']=$sub_data['subid'];
							$data['marks']=$mark;
							$data['attendance']=0;
							$data['grade_id']=$this->Setting->grade_mark($mark);
							$data['student_id']=$user_data['user_id'];
							$data['marks_comment']=$comment;
							$data['modified_date']=Time::now();
							$data['created_by']=$user_id;
							
							
							$a=$class_mark->patchEntity($id,$data);
								
							if($class_mark->save($a))
							{
								$temp=1;
							}
						}
					}
				}
				if($t == 1)
				{
					$this->Flash->success(__('All Subject Mark added Successfully', null), 
									   'default', 
										array('class' => 'success'));
				}
				if($temp == 1)
				{
					$this->Flash->success(__('All Subject Marks updated Successfully', null), 
									   'default', 
										array('class' => 'success'));
				}
			}
		}
	}
	
	public function noticelist()
	{
		
		$notice_table_register=TableRegistry::get('smgt_notice');
		$teacher_data=$notice_table_register->find()->where(['notice_for'=>'all'])->orwhere(['notice_for'=>'teacher']);
		$this->set('rows',$teacher_data);     
		
		/* Student Module Start*/
		$user_session_id=$this->request->session()->read('user_id');
		$role=$this->Setting->get_user_role($user_session_id);
		
		$d1=$notice_table_register->find()->where(['OR' =>[[
												'notice_for' => 'student'],
												['notice_for' => 'all']]]);		
		
		$d2=$notice_table_register->find()->where(['OR' =>[[
												'notice_for' => 'support Staff'],
												['notice_for' => 'all']]]);
        
        
      $notice_for_parent=$notice_table_register->find()->where(['OR' =>[[
												'notice_for' => 'parent'],
												['notice_for' => 'all']]]);

      	$this->set('notice_parent',$notice_for_parent);
        
		$this->set('notice_data',$d1);
		$this->set('notice_data_staff',$d2);
		$this->set('role',$role);
		/* End Student Module */
	}

	public function subjectlist()
	{
		$class = TableRegistry::get('smgt_subject');

		$user_id = $this->request->session()->read('user_id');
		$role = $this->Setting->get_user_role($user_id);

		if($role == 'teacher')
		{
			$access = $this->Setting->get_teacher_access();
			$classid = $this->Setting->get_class_list_user_id($user_id);

			if($access['chksub'] == 'own_cls_sub')
				$query=$class->find()->where(['class_id IN'=>explode(',',$classid)]);
			elseif($access['chksub'] == 'own_sub')
				$query=$class->find()->where(['teacher_id'=>$user_id]);
			else
				$query=$class->find();	
		}
		else
		{
			$query=$class->find();
		}

		foreach ($query as $id)
		{
			$xyz=$this->Setting->get_user_id($id['teacher_id']);
			$xyz1=$this->Setting->get_class_id($id['class_id']);
			$xyz2=$this->Setting->get_section_name($id['section']);

			$abc[]=$xyz;
			$abc1[]=$xyz1;
			$abc2[]=$xyz2;
		}
		if(!empty($abc))
			$this->set('id',$abc);
		if(!empty($abc1))
			$this->set('id1',$abc1);
		if(!empty($abc2))
			$this->set('id2',$abc2);

		$this->set('it',$query);
		
		$class1 = TableRegistry::get('smgt_users');
		$class2 = TableRegistry::get('classmgt');


		$query1=$class1->find();
		$this->set('it1',$query1);

		$query2=$class2->find();
		$this->set('it2',$query2);
		
		/* Student Module Start*/
		$user_session_id=$this->request->session()->read('user_id');
		$class_id=$this->Setting->get_user_class($user_session_id);
		$classname=$this->Setting->get_class_id($class_id);
		
		$stud_sub=$class->find()->where(['class_id'=>$class_id])->hydrate(false)->toArray();
		$role=$this->Setting->get_user_role($user_session_id);
		
		$name = array();
		
		if(!empty($stud_sub))
		{
			foreach($stud_sub as $data)
			{
				$xyz1=$this->Setting->get_teacher_by_cls_sub($data['sub_name']);
				
				$xyz2=$this->Setting->get_user_id($xyz1);
				$name[]=$xyz2;
			}
		}
		if(!empty($name))
			$this->set('name',$name);
		
		$this->set('stud_sub',$stud_sub);
		$this->set('classname',$classname);
		$this->set('role',$role);
		/* End Student Module */
	}

	public function studentresultpdf($student_id = null,$exam_id)
	{
		// $this->autoRender = false;
		$student_id = $this->Setting->my_simple_crypt($student_id,'d');
		$exam_id = $this->Setting->my_simple_crypt($exam_id,'d');
		
		$class = TableRegistry::get('Smgt_users');
		$exam_class = TableRegistry::get('Smgt_exam');
						
		$u_id=$class->get($student_id);
		$e_id=$exam_class->get($exam_id);
		
		$user_id=$u_id['user_id'];
		$exam_id=$e_id['exam_id'];
		
		$this->set('exam_id',$exam_id);
		
		$studentID = $this->Setting->get_studentID($u_id['user_id']);
		$this->set('studentID',$studentID);
		
		$roll_no=$u_id['roll_no'];
		$this->set('roll_no',$roll_no);
		
		$gender=$u_id['gender'];
		$this->set('gender',$gender);
		
		$date_of_birth=$u_id['date_of_birth'];
		$this->set('date_of_birth',$date_of_birth);
		
		$phone=$u_id['phone'];
		$this->set('phone',$phone);
			
		$fname=$u_id['first_name'];
		$this->set('fname',$fname);
		
		$lname=$u_id['last_name'];
		$this->set('lname',$lname);
		
		$name=$u_id['first_name']." ".$u_id['last_name'];
		$profile=$u_id['image'];
		$this->set('profile',$profile);
		
		$name=$u_id['first_name']." ".$u_id['last_name'];
			
		$all_exam=$this->Setting->exam();
			
		$get_current_user_id=$this->request->session()->read('user_id');
			
		$class_id = $u_id['classname'];
		
		$section_id = $u_id['classsection'];
		$sectionname=$this->Setting->get_section_name($section_id);
		$this->set('sectionname',$sectionname);
			
		$tbl_sub_count=$this->Setting->get_subject_count($class_id);
			
		$tbl_subject=$this->Setting->get_subject($class_id);
		$this->set('tbl_subject',$tbl_subject);
		
		$total_subject=$tbl_sub_count;
		
		$class1 = TableRegistry::get('Smgt_setting');
		$query1=$class1->find();
		
		$school_logo=$this->Setting->getfieldname('school_logo');
		
		$this->set('school_logo',$school_logo);
		
		$baselink = $_SERVER['SERVER_NAME'].$this->request->webroot."webroot/";
		$this->set('school1_logo',$baselink);
					
		$school_logo=$this->Setting->getfieldname('school_logo');		
		$this->set('school_logo',$school_logo);
	
		$school_name=$this->Setting->getfieldname('school_name');
		$this->set('school_name',$school_name);

		$classname=$this->Setting->get_class_id($class_id);
		$this->set('classname',$classname);

		$examname=$this->Setting->get_exam_name($exam_id);
		$this->set('examname',$examname);
		
		$exammonthyear=$this->Setting->get_exam_month_year($exam_id);
		$this->set('exammonthyear',$exammonthyear);
			
		$i=0;
		$total=0;
		$grade_point = 0;
		
		foreach($tbl_subject as $sub)
		{
			$b=$this->Setting->get_mark($exam_id,$class_id,$sub['subid'],$user_id);
				$grade_id=$this->Setting->grade_mark($b);
			$data[]=array(
		
			'mark'=>$this->Setting->get_mark($exam_id,$class_id,$sub['subid'],$user_id),
			'get_grade'=>$this->Setting->grade_name($grade_id),
			'subject_name'=>$sub['sub_name']);
			
			
			//$get_grade_marks_comment[]=$this->Setting->grade_comment($grade_id);

		
		$total += $b;
		$grade_point += $this->Setting->grade_point($grade_id);
		
		}
		$this->set('data',$data);		
					
		$this->set('total',$total);
		
		$GPA=$grade_point/$total_subject;
		
		$this->set('GPA',round($GPA, 2));			
	}
	
	public function studentresultprint($student_id = null,$exam_id)
	{
		?>
		<style>
		.invoiceIMG{
			margin-top: 0px;
			padding-top: 0px;
		}
		.movetop{
			float: left;
			width: 100%;
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
			border: 1px solid #97C4E7;
			margin-bottom: 15px;
			border-bottom:1px solid #97C4E7;	
		}
		.studINOF th:last-child{
			border-right: medium none;
		}
		.studINOF th
		{
			padding: 6px 14px 6px 14px;
			font-size:14px;
			border-top: 1px solid #97C4E7;
			border-right: 1px solid #97C4E7;
			border-bottom: 1px solid #97C4E7;
			background-color: #337ab7;
			color: #000000;	
		}
		.borderright{
			border-right: 1px solid #97C4E7;
		}
		.studINOF td{
			padding: 6px 14px 6px 14px;
			font-size:14px;
		}
		.markstable{
			float: left;
			width: 100%;
			/* border: 1px solid #97C4E7; */
			border-top: medium none;
			border-bottom: medium none;
			border-right: medium none;
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
			color: #000000;						
		}
		.markstable td:first-child,
		.markstable th:first-child
		{
			border-left: 1px solid #97C4E7;	
		}
		.markstable td:last-child,
		.markstable th:last-child
		{
			border-right: 1px solid #97C4E7;
		}
		.markstable td{
			padding: 6px 14px 6px 14px;
			font-size:14px;
			border-bottom: 1px solid #97C4E7;				
		}
		.totalmarks{
			background-color: #337ab7;
			color: #000000;	
			text-align: center;
		}
		.markstable td.space{
			border: medium none;
		}
		.bordertop{
			border-top: 1px solid #97C4E7;
		}
		.resultdate{
			float: left;
			width: 200px;
			padding-top: 80px;
			text-align: center;
		}
		.signature{
			float: right;
			width: 200px;
			padding-top: 80px;
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
		@media print {
		  body, page[size="A4"] {
			font-family: 'Open Sans',sans-serif;
		  }
		}
		</style>
		<script>window.onload = function(){ 				
			window.print(); 		
		};</script>
<?php
		$this->autoRender = false;
		
	/* 	ob_start(); */
		
		$student_id = $this->Setting->my_simple_crypt($student_id,'d');
		$exam_id = $this->Setting->my_simple_crypt($exam_id,'d');
		
		$class = TableRegistry::get('Smgt_users');
		$exam_class = TableRegistry::get('Smgt_exam');
		
		$u_id=$class->get($student_id);
		$e_id=$exam_class->get($exam_id);
		
		$user_id=$u_id['user_id'];
		$exam_id=$e_id['exam_id'];
		
		$studentID = $this->Setting->get_studentID($u_id['user_id']);
		$this->set('studentID',$studentID);
		
		$roll_no=$u_id['roll_no'];
		$this->set('roll_no',$roll_no);
		
		$gender=$u_id['gender'];
		$this->set('gender',$gender);
		
		$date_of_birth=$u_id['date_of_birth'];
		$this->set('date_of_birth',$date_of_birth);
		
		$phone=$u_id['phone'];
		$this->set('phone',$phone);
		
		$fname=$u_id['first_name'];
		$lname=$u_id['last_name'];
		$name=$u_id['first_name']." ".$u_id['last_name'];
		$profile=$u_id['image'];
		
		$name=$u_id['first_name']." ".$u_id['last_name'];
			
		$all_exam=$this->Setting->exam();
			
		$get_current_user_id=$this->request->session()->read('user_id');
			
		$class_id = $u_id['classname'];
		
		$section_id = $u_id['classsection'];
		$sectionname=$this->Setting->get_section_name($section_id);
		$this->set('sectionname',$sectionname);
		
		$tbl_sub_count=$this->Setting->get_subject_count($class_id);
			
		$tbl_subject=$this->Setting->get_subject($class_id);
			
		$total_subject=$tbl_sub_count;
		
		$class1 = TableRegistry::get('Smgt_setting');
		$query1=$class1->find();
		
		$baselink = $_SERVER['SERVER_NAME'].$this->request->webroot."webroot/";
		$this->set('school1_logo',$baselink);	
		
		$school_logo=$this->Setting->getfieldname('school_logo');

		$attach_logo = WWW_ROOT ."img/".$school_logo;
		$ext = pathinfo($attach_logo, PATHINFO_EXTENSION);				
		$logo = $this->Setting->base64_encode_image ($attach_logo,$ext);			
		$this->set('school_logo',$school_logo);
	
		$school_name=$this->Setting->getfieldname('school_name');
		$this->set('school_name',$school_name);
		
		$classname=$this->Setting->get_class_id($class_id);
		$this->set('classname',$classname);

		$examname=$this->Setting->get_exam_name($exam_id);
		$this->set('examname',$examname);
		
		$exammonthyear=$this->Setting->get_exam_month_year($exam_id);
		$this->set('exammonthyear',$exammonthyear);
		
		foreach($query1 as $qy)
		{
			$nm=$this->Setting->getfieldname($qy['field_name']);
			$vl[$qy['field_name']]=$nm;
		}
		
		$total_mark = $this->Setting->get_exam_data($exam_id,'total_mark');
		
		$i=0;
		$total=0;
		$grade_point = 0;
		$data = array();
		
		foreach($tbl_subject as $sub)
		{
			$b=$this->Setting->get_mark($exam_id,$class_id,$sub['subid'],$user_id);
				$grade_id=$this->Setting->grade_mark($b);
			$data[]=array(
		
			'mark'=>$this->Setting->get_mark($exam_id,$class_id,$sub['subid'],$user_id),
			'get_grade'=>$this->Setting->grade_name($grade_id),
			'subject_name'=>$sub['sub_name']);

			$total += $b;
			$grade_point += $this->Setting->grade_point($grade_id);
		
		}
		$this->set('data',$data);							
		$this->set('total',$total);

		if($grade_point > 0)			
			$GPA=$grade_point/$total_subject;
		else
			$GPA=0;
		
		$this->set('GPA',round($GPA, 2));
		?>
		<page size="A4">
		<div class="movetop">
			<div class="mainlogo">
				<img src="<?php echo $logo;?>"/>
			</div>
			<div class="schoolname">	
				<span><?php echo $school_name;?></span>
			</div>
		</div>	
		
		<div class="pagetitle">	
			<span><?php echo $examname.' Exam';?></span>
		</div>
	
		<hr color="#FFFFFF">
					
		<table width=100% class="studINOF">			
			<tr>
				<th colspan="1"><?php echo __('Month & Year of Exam');?></th>						
				<th colspan="3"><?php echo __('Name of Student');?></th>												
			</tr>			
			<tr>
				<td colspan="1" class="borderright" align=center><?php echo $exammonthyear;?></td>
				<td colspan="3" align=center><?php echo $fname." ".$lname;?></td>											
			</tr>		
			<tr>
				<th><?php echo __('Student ID');?></th>
				<th><?php echo __('Class Name');?></th>						
				<th><?php echo __('Section Name');?></th>						
				<th><?php echo __('Date of Birth');?></th>
			</tr>		
			<tr>
				<td class="borderright" align=center><?php echo $studentID;?></td>
				<td class="borderright" align=center><?php echo $classname;?></td>
				<td class="borderright" align=center><?php echo $sectionname;?></td>
				<td align=center><?php echo $date_of_birth;?></td>
			</tr>				
		</table>
		
		<div class="markstable">
			<table width=100%>
				<thead>
				<tr>
					<th><?php echo __('SI.No.');?></th>
					<th><?php echo __('Name of Subject');?></th>
					<th><?php echo __('Obtain Mark');?></th>
					<th><?php echo __('Out of Mark');?></th>
					<?php 
					if($total_mark == 100)
					{
						echo '<th>Grade</th>';
					}
					?>				
				</tr>
				<thead>
				<tbody>
				
				<?php 
				
				$i=1;
				$cnt_total_mark = 0;
				foreach($data as $pdfdata)
				{
				?>
				<tr>
					<td align=center><?php echo $i;?></td>
					<td align=center><?php echo $pdfdata['subject_name'];?></td>
					<td align=center><?php echo $pdfdata['mark'];?></td>
					<td align=center><?php echo $total_mark;?></td>
					<?php 
					if($total_mark == 100)
					{
						echo '<td align=center>'.$pdfdata['get_grade'].'</td>';
					}
					echo '</tr>';
					
					$i=$i+1;
					$cnt_total_mark = $cnt_total_mark+$total_mark;
				}
				echo '
				<tr border=0 class="space"><td border=0 colspan="4" class="space"><br></td></tr>
				<tr>
					<td colspan="2" align=center class="totalmarks bordertop"><b>'. __('Total Marks').'</b></td>				
					<td colspan="1" align=center class="bordertop">'.$total.'</td>
					<td colspan="1" align=center class="bordertop">'.$cnt_total_mark.'</td>
				</tr>
				<tr>';
				$percentage = 0;
				$percentage = round($total*100/$cnt_total_mark);
				echo '
					<td colspan="2" align=center class="totalmarks bordertop"><b>'.__('Percentage').'</b></td>				
					<td colspan="2" align=center class="bordertop">'.$percentage.'%'.'</td>
				</tr>';
				if($total_mark == 100)
				{
				echo '	
				<tr>
					<td colspan="3"><b>'.__('GPA(grade point average) :').'</b></td>
					<td>'.$GPA.'</td>
				</tr>';
				}
				echo '
				</tbody>
			</table>
		</div>
		<div class="resultdate">
			<hr color="#97C4E7">
			<span>'.__('Date of Publication of Result').'</span>
		</div>
		<div class="signature">
			<hr color="#97C4E7">
			<span>'.__('Controller of Examination').'</span>
		</div>
	</page>';
	}
	
	public function addsubject()
	{
		$class = TableRegistry::get('Classmgt');

		$query=$class->find();
		$this->set('it',$query);

		$class1 = TableRegistry::get('Smgt_users');

		$query1=$class1->find()->where(['role'=>'teacher']);
		$this->set('it1',$query1);

		$class2 = TableRegistry::get('smgt_subject');

		if($this->request->is('post'))
		{
			$c1=$this->request->data;
			
			$sub_code=$this->request->data['sub_code'];
			
			$chk_code = $this->Setting->check_subject_data($sub_code);
			
			if(!$chk_code)
			{
				if($this->request->data['syllabus'])
				{
					$img=$this->request->data['syllabus'];
					$u="syllabus";
					$fp=WWW_ROOT.$u;

					$imgname=$img['name'];

					$fpp=$fp.'/'.$imgname;

					if(move_uploaded_file($img['tmp_name'],$fpp))
					{
						// echo "success";
					}
				}			
			
				$a=$class2->newEntity();
				
				$c1['syllabus']=$this->request->data('file');
				$c1['syllabus']=$c1['syllabus']['name'];

				$a=$class2->patchEntity($a,$c1);

				if($class2->save($a))
				{
					$this->Flash->success(__('New Subject added Successfully', null),
							'default',
						   array('class' => 'success'));					
				}
			}
			else
			{
				$this->Flash->error(__('Subject code already exists'),[ 
							'params' => [
								'class' => 'alert alert-error'
						]]);
			}
			return $this->redirect(['action'=>'subjectlist']);
		}
	}


	public function transportlist()
	{
		$transport_table_register=TableRegistry::get('smgt_transport');
		$Get_all_data=$transport_table_register->find();
		$this->set('rows',$Get_all_data);
	}

	public function classroutelist()
	{

		$class=TableRegistry::get('smgt_time_table');
		$aa=$class->find();

		$user_session_id=$this->request->session()->read('user_id');
		
		$child_id=$this->Setting->get_child_id($user_session_id);
		
		$role=$this->Setting->get_user_role($user_session_id);
		if($role == 'parent')
		{
			$class_list_parent_child=$this->Setting->get_parents_student_id($user_session_id);
			/* foreach($child_id as $c)
			{
				$cls_id=$this->Setting->get_user_class($c);				
				$class_list_parent_child[]=$this->Setting->get_class_list_by_id($cls_id);
			} */
			$this->set('class_list_parent_child',$class_list_parent_child);
		}
		
		$class_route = array();
		$teachername = '';
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
		$class_list=$this->Setting->get_class_list();
		
		$this->set('daywk',$xyz);
		$this->set('class_list',$class_list);
		$this->set('teacher_name',$teachername);
		
		/* Student Module Start*/
		$class1 = TableRegistry::get('Smgt_users');
		$user_session_id=$this->request->session()->read('user_id');
		
		$class_id=$this->Setting->get_user_class($user_session_id);
		
		$query1=$class1->find()->where(['classname'=>$class_id,'role'=>'student']);
		
		$class_list_id=$this->Setting->get_class_list_by_id($class_id);
		
		$this->set('user_session_id',$user_session_id);
		$this->set('class_list_id',$class_list_id);
		$this->set('it1',$query1);
		
		/* End Student Module */
	}
	public function deleteincome($id){

        $income_table_register=TableRegistry::get('smgt_income_expense');
			$this->request->is(['post','delete']);
			$item=$income_table_register->get($id);
			if($income_table_register->delete($item))
			{
				$this->Flash->success(__('Income Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));	
							 
				return $this->redirect(['action'=>'incomelist']);
			}

    }

      public function incomepdf($id)
	  {

                   $income_id=$id;

                    $income_table_register=TableRegistry::get('smgt_income_expense');
                    $income_all_record=$income_table_register->get($income_id);

                    $this->set('income_data',$income_all_record);

                     $setting=TableRegistry::get('smgt_setting');

			$query_address=$setting->find('all',['conditions'=>['field_name'=>'school_address']]);
			$query_phone=$setting->find('all',['conditions'=>['field_name' =>'office_phone_no']]);
			$address='';
			$phone='';
			foreach($query_address as $q)
			{
				$address=$q['field_value'];
			}
			foreach ($query_phone as $value) {
				$phone=$value['field_value'];
			}

                        $get_created_id=$income_all_record['create_by'];
                        $get_bill_to=$income_all_record['supplier_name'];



                        $user_table_register=TableRegistry::get('smgt_users');
                        $get_user_info=$user_table_register->get($get_created_id);

                        $Bill_To_id=$user_table_register->get($get_bill_to);

                        $this->set('usertobill',$Bill_To_id);

                        $this->set('User_info',$get_user_info);

                        $this->set('address',$address);
                        $this->set('phone',$phone);


        }

    public function paymentpdf($id){

       $this->set('id',$id);

        $payment_table_register=TableRegistry::get('smgt_payment');
            $get_all_record=$payment_table_register->get($id);

            $this->set('payment_data',$get_all_record);
            $user_table_register=TableRegistry::get('smgt_users');
            $user_id=$get_all_record['payment_reciever_id'];
            $record_from_user=$user_table_register->get($user_id);
             $this->set('user',$record_from_user);

             $billto_name=$user_table_register->get($get_all_record['user_id']);

             $this->set('btn',$billto_name);

            $setting=TableRegistry::get('smgt_setting');

			$query_address=$setting->find('all',['conditions'=>['field_name'=>'school_address']]);
			$query_phone=$setting->find('all',['conditions'=>['field_name' =>'office_phone_no']]);
			$address='';
			$phone='';
			foreach($query_address as $q)
			{
				$address=$q['field_value'];
			}
			foreach ($query_phone as $value) {
				$phone=$value['field_value'];
			}

                        $this->set('address',$address);
                        $this->set('phone',$phone);


    }

    public function viewdatapayment($id=null){
        //$this->autoRender=false;

        if($this->request->is('ajax')){
            $payment_id=$_POST['id'];

            $this->set('setid',$payment_id);

            $payment_table_register=TableRegistry::get('smgt_payment');
            $get_all_record=$payment_table_register->get($payment_id);


            $this->set('payment_data',$get_all_record);
            $user_table_register=TableRegistry::get('smgt_users');
            $user_id=$get_all_record['payment_reciever_id'];
            $record_from_user=$user_table_register->get($user_id);
             $this->set('user',$record_from_user);

             $billto_name=$user_table_register->get($get_all_record['user_id']);

             $this->set('btn',$billto_name);

            $setting=TableRegistry::get('smgt_setting');

			$query_address=$setting->find('all',['conditions'=>['field_name'=>'school_address']]);
			$query_phone=$setting->find('all',['conditions'=>['field_name' =>'office_phone_no']]);
			$address='';
			$phone='';
			foreach($query_address as $q)
			{
				$address=$q['field_value'];
			}
			foreach ($query_phone as $value) {
				$phone=$value['field_value'];
			}

                        $this->set('address',$address);
                        $this->set('phone',$phone);

            $this->set('id',$payment_id);

        }
    }

    public function expensepdf($id){

                        $get_id=$id;
                     $expense_table_register=TableRegistry::get('smgt_income_expense');

			$get_field=$expense_table_register->get($get_id);
                        $this->set('expense_data',$get_field);

			$setting=TableRegistry::get('smgt_setting');

			$query_address=$setting->find('all',['conditions'=>['field_name'=>'school_address']]);
			$query_phone=$setting->find('all',['conditions'=>['field_name' =>'office_phone_no']]);
			$address='';
			$phone='';
			foreach($query_address as $q)
			{
				$address=$q['field_value'];
			}
			foreach ($query_phone as $value) {
				$phone=$value['field_value'];
			}

                        $this->set('address',$address);
                        $this->set('phone',$phone);


				$create_id=$get_field['create_by'];

                                $get_admin=TableRegistry::get('smgt_users');
				$get_admin_data=$get_admin->get($create_id);

				$this->set('admin_info',$get_admin_data);


    }

    public function viewdataexpense($id = null){

		//$this->autoRender=false;

		if($this->request->is('ajax')){
                    $get_id=$_POST['id'];

                    $expense_table_register=TableRegistry::get('smgt_income_expense');

			$get_field=$expense_table_register->get($get_id);
                        $this->set('expense_data',$get_field);

			$setting=TableRegistry::get('smgt_setting');

			$query_address=$setting->find('all',['conditions'=>['field_name'=>'school_address']]);
			$query_phone=$setting->find('all',['conditions'=>['field_name' =>'office_phone_no']]);
			$address='';
			$phone='';
			foreach($query_address as $q)
			{
				$address=$q['field_value'];
			}
			foreach ($query_phone as $value) {
				$phone=$value['field_value'];
			}

                        $this->set('address',$address);
                        $this->set('phone',$phone);


				$create_id=$get_field['create_by'];

                                $get_admin=TableRegistry::get('smgt_users');

				$get_admin_data=$get_admin->get($create_id);

				$this->set('admin_info',$get_admin_data);
                                //var_dump($get_admin_data);
                }

    }

	

	public function deleteexpense($id){

		$expense_table_register=TableRegistry::get('smgt_income_expense');
			$this->request->is(['post','delete']);

			$item=$expense_table_register->get($id);
			if($expense_table_register->delete($item))
			{
				$this->Flash->success(__('Expense Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));	
							 
				return $this->redirect(['action'=>'expenselist']);
			}

	}



        public function viewdataincome($id=null){

           if($this->request->is('ajax')){
            $income_id=$_POST['id'];
                    $this->set('get_id',$income_id);
                    $income_table_register=TableRegistry::get('smgt_income_expense');
                    $income_all_record=$income_table_register->get($income_id);

                    $this->set('income_data',$income_all_record);

                     $setting=TableRegistry::get('smgt_setting');

			$query_address=$setting->find('all',['conditions'=>['field_name'=>'school_address']]);
			$query_phone=$setting->find('all',['conditions'=>['field_name' =>'office_phone_no']]);
			$address='';
			$phone='';
			foreach($query_address as $q)
			{
				$address=$q['field_value'];
			}
			foreach ($query_phone as $value) {
				$phone=$value['field_value'];
			}

                        $get_created_id=$income_all_record['create_by'];
                        $get_bill_to=$income_all_record['supplier_name'];



                        $user_table_register=TableRegistry::get('smgt_users');
                        $get_user_info=$user_table_register->get($get_created_id);

                        $Bill_To_id=$user_table_register->get($get_bill_to);

                        $this->set('usertobill',$Bill_To_id);

                        $this->set('User_info',$get_user_info);



                        $this->set('address',$address);
                        $this->set('phone',$phone);


             }
        }

	public function addincome($id=null)
	{

		$user_session_id=$this->request->session()->read('user_id');
	
		$role=$this->Setting->get_user_role($user_session_id);
		
		$this->set('role',$role);
		
		$class_table_register=TableRegistry::get('classmgt');
		$get_class=$class_table_register->find();
		$this->set('class_info',$get_class);

		$income_table_register=TableRegistry::get('smgt_income_expense');
		$income_table_entity=$income_table_register->newEntity();
		
		$student_info=TableRegistry::get('smgt_users');

		if(isset($id))
		{
			$this->set('edit',true);
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $income_table_register->exists(['income_id' => $id]);
			
			if($exists)
			{
				$get_income_record=$income_table_register->get($id);

				$getclass_name=$class_table_register->find('all',['conditions'=>['class_id'=>$get_income_record['class_id']]]);

				$classn='';
				$classid='';

				foreach($getclass_name as $c)
				{
					$classn=$c['class_name'];
					$classid=$c['class_id'];
				}

				$this->set('class_name',$classn);
				$this->set('classid',$classid);

				$getall_from_user=$get_income_record['class_id'];

				$allstudent_from_class=$student_info->find('all',['conditions'=>['classname'=>$getall_from_user,'role'=>'student']]);

				$this->set('allstudent_from_class',$allstudent_from_class);

				if($this->request->is('post'))
				{
					$data1=$this->request->data;

					$all_value_entry1=$data1['custom_value'];
					$all_label1=$data1['custom_label'];

					$entry_data1=array();
						$i1=0;

					foreach ($all_value_entry1 as $one_entry1) 
					{
						$entry_data1[]=array('amount'=>$all_label1[$i1],'entry'=>$one_entry1);
						$i++;
					}

					$custom_field1=json_encode($entry_data1);

					$data1['entry']=$custom_field1;

					$update_income=$income_table_register->patchEntity($get_income_record,$data1);

					if($income_table_register->save($update_income))
					{
						$this->Flash->success(__('Income Updated Successfully', null), 
									'default', 
										array('class' => 'success'));	
							
						return $this->redirect(['action'=>'incomelist']);
					}else{
							echo 'Some Error in Update Data';
					}
				}
				$this->set('incomerecord',$get_income_record);
			}
			else
				return $this->redirect(['action'=>'incomelist']);	
		}
		
		if($this->request->is('post'))
		{
			$data=$this->request->data;

			$all_value_entry=$data['custom_value'];
			$all_label=$data['custom_label'];

			$entry_data=array();
			$i=0;

			foreach ($all_value_entry as $one_entry) 
			{
				$entry_data[]=array('amount'=>$all_label[$i],'entry'=>$one_entry);
				$i++;
			}
			
			$custom_field=json_encode($entry_data);

			$data['entry']=$custom_field;
			$data['supplier_name']=$this->request->data('user_id');
			$data['income_create_date']=date('Y-m-d',strtotime($this->request->data('income_create_date')));
			$data['create_by']=$this->request->session()->read('user_id');

			$Add_Data=$income_table_register->patchEntity($income_table_entity,$data);

			if($income_table_register->save($Add_Data))
			{
				$this->Flash->success(__('Income added Successfully', null), 
						'default', 
						 array('class' => 'success'));	
						 
				return $this->redirect(['action'=>'addincome']);
			}
		}
	}

	public function addpayment($id=null)
	{

		$class_table_register=TableRegistry::get('classmgt');
		$get_class=$class_table_register->find();
		$this->set('class_info',$get_class);
		
		$user_session_id=$this->request->session()->read('user_id');
		
		$role=$this->Setting->get_user_role($user_session_id);
				
		$this->set('role',$role);
		
		$payment_table_register=TableRegistry::get('smgt_payment');
		$payment_table_entity=$payment_table_register->newEntity();

		$student_info=TableRegistry::get('smgt_users');

		if(isset($id))
		{
			$this->set('edit',true);
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $payment_table_register->exists(['payment_id' => $id]);
			
			if($exists)
			{
				$get_all_payment=$payment_table_register->get($id);
				
				$getall_from_user=$get_all_payment['class_id'];
				$allstudent_from_class=$student_info->find('all',['conditions'=>['classname'=>$getall_from_user,'role'=>'student']]);
				$this->set('allstudent_from_class',$allstudent_from_class);

				if($this->request->is('post'))
				{
					$data1=$this->request->data;
					$data1['payment_reciever_id']=$data1['payment_reciever_id'];

					$update_payment=$payment_table_register->patchEntity($get_all_payment,$data1);
				
					if($payment_table_register->save($update_payment))
					{
						$this->Flash->success(__('Payment Updated Successfully', null), 
											'default', 
											array('class' => 'success'));	

						return $this->redirect(['action'=>'paymentlist']);
					}
					else
					{
						echo 'Some Error in Update Data';
					}
				}
				$this->set('row',$get_all_payment);
			}	
			else
				return $this->redirect(['action'=>'paymentlist']);
		}
		
		if($this->request->is('post')){

			$data=$this->request->data;
			
			$data['payment_reciever_id']="1";

			$payment_add=$payment_table_register->patchEntity($payment_table_entity,$data);

			if($payment_table_register->save($payment_add))
			{
				$this->Flash->success(__('Payment added Successfully', null), 
                            'default', 
                             array('class' => 'success'));	
							 
				return $this->redirect(['action'=>'addpayment']);
			}
			else
			{
				echo 'Some Error in Insert Data';
			}

		}

	}

	public function addexpense($id = null)
	{
		
		$user_session_id=$this->request->session()->read('user_id');
		
		$role=$this->Setting->get_user_role($user_session_id);
		
		$this->set('role',$role);
		
		$income_table_register=TableRegistry::get('smgt_income_expense');

		if(isset($id))
		{	
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $income_table_register->exists(['income_id' => $id]);
			
			if($exists)
			{
				$fetch=$income_table_register->get($id);
				$this->set('edit',true);

				if($this->request->is('post'))
				{					
					$data1=$this->request->data;

					$all_value_entry1=$data1['custom_value'];
					$all_label1=$data1['custom_label'];

					$entry_data1=array();
					$i1=0;

					foreach ($all_value_entry1 as $one_entry1) 
					{
						$entry_data1[]=array('amount'=>$all_label1[$i1],'entry'=>$one_entry1);
						$i1++;
					}
						
					$custom_field1=json_encode($entry_data1);

					$data1['entry']=$custom_field1;
						
					$fetch=$income_table_register->patchEntity($fetch,$data1);

					if($income_table_register->save($fetch))
					{
						$this->Flash->success(__('Expense Updated Successfully', null), 
								'default', 
								 array('class' => 'success'));	
								 
						return $this->redirect(['controller'=>'comman','action'=>'expenselist']);
					}
					else
					{
						echo 'Some Error in Update Page';
					}
				}
				$this->set('row',$fetch);
			}
			else
				return $this->redirect(['action'=>'expenselist']);	
		}

		$income_table_entity=$income_table_register->newEntity();

		if($this->request->is('post')){

			$data=$this->request->data;
			$all_value_entry=$data['custom_value'];
			$all_label=$data['custom_label'];

			$entry_data=array();
			$i=0;

			foreach ($all_value_entry as $one_entry) {

				$entry_data[]=array('amount'=>$all_label[$i],'entry'=>$one_entry);

				$i++;
			}

			$custom_field=json_encode($entry_data);

			$data['entry']=$custom_field;
			$data['create_by']='1';

			$Add_Data=$income_table_register->patchEntity($income_table_entity,$data);

			if($income_table_register->save($Add_Data))
			{
				$this->Flash->success(__('Expense added Successfully', null), 
                            'default', 
                             array('class' => 'success'));	
							 
				return $this->redirect(['action'=>'addexpense']);
			}
		}
	}

	public function expenselist()
	{
		$user_session_id=$this->request->session()->read('user_id');
		
		$role=$this->Setting->get_user_role($user_session_id);
		
		$this->set('role',$role);
		
		$expense_table_register=TableRegistry::get('smgt_income_expense');
		$fetch_data_from_expense=$expense_table_register->find()->where(['invoice_type' =>'expense']);
		$this->set('rows',$fetch_data_from_expense);
	}

	public function incomelist()
	{
		$user_session_id=$this->request->session()->read('user_id');
		
		$role=$this->Setting->get_user_role($user_session_id);
		
		$this->set('role',$role);
		
		$income_table_register=TableRegistry::get('smgt_income_expense');
                $users_table_register=TableRegistry::get('smgt_users');

		$fetch_data_from_income=$income_table_register->find()->where(['invoice_type' =>'income']);
		$fetch_data_users=$users_table_register->find();

                $this->set('income_data',$fetch_data_from_income);
                $this->set('user_data',$fetch_data_users);



	}


	public function delete($id){

			$payment_table_register=TableRegistry::get('smgt_payment');
			$this->request->is(['post','delete']);

			$item=$payment_table_register->get($id);
			if($payment_table_register->delete($item))
			{
				$this->Flash->success(__('Payment Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));	
							 
				return $this->redirect(['action'=>'paymentlist']);
			}

	}


	public function ShowStudent($id = null){

		$class_table_register=TableRegistry::get('classmgt');
		$user_table_register=TableRegistry::get('smgt_users');


		$this->autoRender=false;
		if($this->request->is('ajax')){

			$get_id=$_POST['id'];

			$get_class=$class_table_register->get($get_id);

				$Get_All_Data=$user_table_register->find()->where(['classname'=>$get_id]);
			?>

			<select class="form-control validate[required]" name="user_id" id="classid">
				<option value=""><?php echo __('---Select Student---'); ?></option>
			<?php
				foreach ($Get_All_Data as $d)
				{
					?>
				<option value="<?php echo $d['user_id']; ?>"><?php echo $d['first_name']; ?></option>
				<?php
				}
				?>
                        </select>
			<?php

		}

	}
	 public function paymentlist()
	{
		$payment_table_register=TableRegistry::get('smgt_payment');
		$users_table_register=TableRegistry::get('smgt_users');
		$fetch_data_from_payment=$payment_table_register->find();
		$fetch_data_user=$users_table_register->find();
		$this->set('payment',$fetch_data_from_payment);
		$this->set('user',$fetch_data_user);
		
		/* Student Module Start*/
		$user_session_id=$this->request->session()->read('user_id');
		
		$role=$this->Setting->get_user_role($user_session_id);
		
		$this->set('stud_id',$user_session_id);
		$this->set('role',$role);
		/* End Student Module */
		$child_id=$this->Setting->get_child_id($user_session_id);
		
		$this->set('child_id',$child_id);
	}
	
	 public function feelist()
    {
		 $fees_payment=TableRegistry::get('smgt_fees_payment');
         $get_all_data=$fees_payment->find();

         $this->set('fees_data',$get_all_data);

          $catetable_register=TableRegistry::get('smgt_categories');
          $get_all_data_cat=$catetable_register->find();

         $this->set('get_all_data_cat',$get_all_data_cat);

         $user_table_register=TableRegistry::get('smgt_users');
         $get_all_user=$user_table_register->find();

         $this->set('get_all_user',$get_all_user);

         $class_table_register=TableRegistry::get('classmgt');
         $get_all_class=$class_table_register->find();

         $this->set('get_all_class',$get_all_class);
		 
		 /* Student Module Start*/
		$user_session_id=$this->request->session()->read('user_id');
		
		$role=$this->Setting->get_user_role($user_session_id);
		
		$this->set('stud_id',$user_session_id);
		$this->set('role',$role);
		/* End Student Module */
		
		$child_id=$this->Setting->get_child_id($user_session_id);
		
		$this->set('child_id',$child_id);
		
		if($this->request->is("post"))
		{
			$user_id=$this->request->session()->read('user_id');
			$user_info=$user_table_register->get($user_id);
			$new_member=1;
			
			$mp_id = $this->request->data["payid"];
			$paymentamount = $this->request->data["paymentamount"];
			
			$this->request->session()->write("Payment.mp_id",$mp_id);
			$this->request->session()->write("Payment.amount",$paymentamount);
			
			$custom_var = $mp_id;		
			// var_dump($_REQUEST);die;
			require_once(ROOT . DS .'vendor' . DS  . 'paypal' . DS . 'paypal_process.php');
		}
		
    }
	
     public function memberlist(){

       $register_library=TableRegistry::get('smgt_library_book_issue');
       $register_users=TableRegistry::get('smgt_users');
       $register_class=TableRegistry::get('classmgt');

       $query=$register_library->find();
	   $query->select()->distinct(['student_id']);
       $this->set('query',$query);

      $get_class=$register_class->find();
      $this->set('get_class',$get_class);
      $get_student=$register_users->find()->where(['role'=>'student']);
      $this->set('get_student',$get_student);
	
		/* Student Module Start*/
		$user_session_id=$this->request->session()->read('user_id');
		
		$class_id=$this->Setting->get_user_class($user_session_id);
		
		$role=$this->Setting->get_user_role($user_session_id);
		if($role == 'student')
		{
			$query1=$register_users->find()->where(['classname'=>$class_id,'role'=>'student']);	
			$classname=$this->Setting->get_class_id($class_id);			
			$this->set('classname',$classname);			
			$this->set('it1',$query1);
		}
		/* End Student Module */
		
		$this->set('role',$role);
		$this->set('user_session_id',$user_session_id);
     }

	 public function booklist()
	 {
		$book_table_register=TableRegistry::get('smgt_library_book');
		$category_table=TableRegistry::get('smgt_categories');
		$book_list=$book_table_register->find();
		$get_all_data_rack=$category_table->find()->where(['category_title'=>'racklocation']);
		$this->set('rack_data',$get_all_data_rack);
		$this->set('book_info',$book_list);
	}

		public function account()
		{

			$user_id=$this->request->session()->read('user_id');
			$role=$this->Setting->get_user_role($user_id);
			
			if($role == 'student')
			{
				$class_id=$this->Setting->get_user_class($user_id);
				$class=$this->Setting->get_class_id($class_id);			
				$this->set('class',$class);
			}
			if($role == 'teacher')
			{
				$subject=$this->Setting->get_user_subject($user_id);
				$this->set('subject',$subject);
			}
			
			/* Student Module Start*/
			$user_parent = TableRegistry::get('child_tbl');
			$parent=$user_parent->find()->where(['child_id'=>$user_id]);
		
			$data = array();
			foreach($parent as $user_parent)
			{ 	
				$data[]=array('image'=>$this->Setting->get_user_image($user_parent['child_parent_id']),
					'name'=>$this->Setting->get_user_id($user_parent['child_parent_id']),
					'relation'=>$this->Setting->get_user_relation($user_parent['child_parent_id']));
			}

			$this->set('role',$role);
		
			if(($role=='student') && (sizeof($data) >= 1))
				$this->set('data',$data);
		
			/* End Student Module */
		
			$class_user = TableRegistry::get('smgt_users');
			$school_setting_table_register=TableRegistry::get('smgt_setting');
			$school_image_info=$school_setting_table_register->find()->select(['field_value'])->where(['field_name'=>'school_profile']);

			$query=$class_user->find()->where(['user_id'=>$user_id]);
			$get_role='';

			foreach($query as $role)
			{
				$get_role=$role['role'];
				$this->comman_arr['first_name']=$role->first_name;
				$this->comman_arr['last_name']=$role->last_name;
				$this->comman_arr['image']=$role->image;
				$this->comman_arr['password']=$role->password;
				$this->comman_arr['address']=$role->address;
				$this->comman_arr['city']=$role->city;
				$this->comman_arr['state']=$role->state;
				$this->comman_arr['email']=$role->email;
				$this->comman_arr['phone']=$role->phone;
				$this->comman_arr['role']=$role->role;
			}
		
			foreach($school_image_info as $get_image)
			{
				$this->comman_arr['cover_image']=$get_image['field_value'];
			}
		
			$this->set('get_role',$get_role);
		
			if($get_role == 'teacher')
			{
				$this->teacheraccount($this->comman_arr);
			}
			else if($get_role == 'parent')
			{
				$this->parentaccount($this->comman_arr);
				$user_child = TableRegistry::get('child_tbl');
				$user_table=TableRegistry::get('smgt_users');
				$class_table=TableRegistry::get('classmgt');

				$get_all_data_from_user=$user_table->find();
				$get_all_data_from_class=$class_table->find();

				$child_list=$user_child->find()->where(['child_parent_id'=>$user_id]);

				$arr=array();

				foreach ($child_list as $value) {		
						$arr[]=$value['child_id'];
				}
				// var_dump($arr);die;
				$this->set('child_identify',$arr);
				$this->set('class_identify',$get_all_data_from_class);
				$this->set('user_identify',$get_all_data_from_user);


			}
			else if($get_role == 'student'){
				$this->studentaccount($this->comman_arr);
			}
			else if($get_role == 'supportstaff'){
				$this->staffaccount($this->comman_arr);
			}
			else if($get_role == 'admin'){
				$this->adminaccount($this->comman_arr);
			}
		}

		public function teacheraccount($query)
		{
				$this->set('comman_info',$query);
		}
		
		public function staffaccount($query)
		{
				$this->set('comman_info',$query);
		}

		public function parentaccount($query)
		{
				$this->set('comman_info',$query);
		}

		public function studentaccount($query)
		{
				$this->set('comman_info',$query);
		}
		
		public function adminaccount($query)
		{
				$this->set('comman_info',$query);
		}

 		public function changepassword()
		{
			$user_id=$this->request->session()->read('user_id');
			$class_user = TableRegistry::get('smgt_users');
			$query=$class_user->find()->where(['user_id'=>$user_id]);
			$get_pass='';
			
			$hasher = new DefaultPasswordHasher();
			
			foreach($query as $role)
			{
				$get_pass=$role['password'];
			}
			
			$old_password=$_POST['old_password'];
			$new_password=$_POST['new_password'];
			$conform_password=$_POST['conform_password'];
			$this->autoRender=false;
			
			if($this->request->is('ajax'))
			{
				$match = $this->Setting->changepassword();
				$chk_pass = $hasher->check($old_password,$match);
				
				if($chk_pass)
				{
					if($new_password != '' || $conform_password != '')
					{
						$newpass= $hasher->hash($new_password);
						
						$query=$class_user->query();
						$query->update()
							->set(['password'=>$newpass])
							->where(['user_id'=>$user_id])
							->execute();

						if($query)
						{
							$school_email = $this->Setting->getfieldname('email');
							$school_name = $this->Setting->getfieldname('school_name');
											
							$student_name = $this->Setting->get_user_id($user_id);
							$to = $this->Setting->get_user_email_id($user_id);
							
							$message_content = "Dear $student_name \n\n Your new password : $new_password\n\n Thank You\n $school_name";
									
							$emial_to = $to;
							$sys_name = $school_name;
							$sys_email = $school_email;
							
							$headers = "MIME-Version: 1.0" . "\r\n";
							$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
							$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";

							@mail($emial_to,_("Change Password Reminder!"),$message_content,$headers);			
					
							echo '<center><div class="alert alert-success">Your Password Information Is Updated.</div></center>';
						}
						else
						{
							echo '';
						}

					}
					else
					{
						echo '<center><div class="alert alert-info">Information ! Please Fill Newpassword !</div></center>';
					}
				}
				else
				{
					echo '<center><div class="alert alert-danger">Sorry ! Old Password is Not Match !</div></center>';
				}
			}
 		}

		public function addpersonal()
		{
			$this->autoRender=false;
			$user_id=$this->request->session()->read('user_id');
			$user_table_register=TableRegistry::get('smgt_users');
			
			if($this->request->is('ajax'))
			{
				$address=$_POST['address'];
				$city=$_POST['city'];
				$state=!empty($_POST['state'])?$_POST['state']:'';
				$phone=$_POST['phone'];
				$email=$_POST['email'];
				
				if (filter_var($email, FILTER_VALIDATE_EMAIL)) 
				{
				
					$query=$user_table_register->query();

					$query->update()
					->set(['address'=>$address,'city'=>$city,'state'=>$state,'phone'=>$phone,'email'=>$email])
					->where(['user_id'=>$user_id])
					->execute();

					if($query)
					{
						echo '<center><div class="alert alert-success">Your Personal Information Is Updated.</div></center>';
					}
					else
					{
						echo '<center><div class="alert alert-danger">Your Personal Information Is Updated.</div></center>';
					}
				}
				else
				{
					echo '<center><div class="alert alert-danger">Not a valid email address.</div></center>';
				}
			}
		}

		public function readfile($readfile = NULL)
		{
			$this->set('file',$readfile);

			$file = WWW_ROOT.'syllabus'.DS.$readfile;

			if (file_exists($file)) 
			{
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.$file.'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file));
				readfile($file);
				exit;
			}
			else
			{
				$this->redirect(['action'=>'readfile']);	
			}
		}
 
		public function addnews($id=0)
		{
			$this->set('Comman','News');
			
			$get_current_user_id=$this->request->session()->read('user_id');
			
			if($id)
			{
				$this->set('edit',true);
				$class = TableRegistry::get('smgt_news');
				$item = $class->get($id);
				$this->set('row',$item);
			}
		
			if($this->request->is('post'))
			{
				if($id)
				{
					$class = TableRegistry::get('smgt_news');
					
					$img=$_FILES['news_document']['name'];
					$img2=$this->request->data('file2');
				
					$c1=$this->request->data;
					
					$db_cl = array();
					
					if(!$img)
					{					
						$_FILES['news_document']['name']=$img2;
						unset($this->request->data['file2']);
						unset($_FILES['news_document']);
						$db_cl['news_document']=$img2;								
					}
					else
					{
						$news_document_img = $_FILES['news_document'];
						$u="document";
						$fp=WWW_ROOT.$u;	

						$imgname=$news_document_img['name'];

						$fpp=$fp.'/'.$imgname;
						
						if(move_uploaded_file($news_document_img['tmp_name'],$fpp))
						{
							echo "success";
						}
						
						unset($this->request->data['file2']);
						unset($_FILES['news_document']);
						$db_cl['news_document']=$img;		
					}			
					
					$db_cl['news_title']=$c1['news_title'];
					$db_cl['news_desc']=$c1['news_desc'];
					$db_cl['news_start_date'] = date("Y-m-d", strtotime($c1['news_start_date']));;
					$db_cl['news_end_date'] = date("Y-m-d", strtotime($c1['news_end_date']));;
					$db_cl['created_date']=date("Y-m-d");
					$db_cl['created_by']=$get_current_user_id;
					
					$item = $class->patchEntity($item,$db_cl);
				
					if($class->save($item))
					{
						$this->Flash->success(__('News Updated Successfully', null), 
							'default', 
							 array('class' => 'success'));
						
					}
					return $this->redirect(['action'=>'newslist']);						
				}
				else
				{
					if($_FILES['news_document'])
					{
						$img=$_FILES['news_document'];
						$u="document";
						$fp=WWW_ROOT.$u;	

						$imgname=$img['name'];

						$fpp=$fp.'/'.$imgname;

						if(move_uploaded_file($img['tmp_name'],$fpp))
						{
							echo "success";
						}
					}		
					
					$class2 = TableRegistry::get('smgt_news'); 			
					$a=$class2->newEntity();
				
					$c1=$this->request->data;
					
					$db_cl = array();
					
					$db_cl['news_title']=$c1['news_title'];
					$db_cl['news_desc']=$c1['news_desc'];
					$db_cl['news_document']=$_FILES['news_document'];
					$db_cl['news_document']=$db_cl['news_document']['name'];
					$db_cl['news_start_date'] = date("Y-m-d", strtotime($c1['news_start_date']));;
					$db_cl['news_end_date'] = date("Y-m-d", strtotime($c1['news_end_date']));;
					$db_cl['created_date']=date("Y-m-d");
					$db_cl['created_by']=$get_current_user_id;
					
					$a=$class2->patchEntity($a,$db_cl);
					
					if($class2->save($a))
					{
						$this->Flash->success(__('News added Successfully', null), 
								'default', 
								 array('class' => 'success'));
					}
					return $this->redirect(['action'=>'newslist']);
				}
			}
		}
	
		public function newsmultidelete() 
		{
			$this->autoRender = false;
			$id=json_decode($_REQUEST[n_id]);
			foreach($id as $recordid)
			{
				$class = TableRegistry::get('smgt_news');
				
				$item =$class->get($recordid);

				if($class->delete($item))
				{
					
				}	
			}
		}
		
		public function newslist()
		{
			$this->set('Comman','News');
			
			$class = TableRegistry::get('smgt_news');
			$query=$class->find()->order(['news_id'=>'DESC']);;
			$this->set('it',$query);			
		}
		
		public function addexport($id=0)
		{		
			$this->set('Comman','Export');
			$get_current_user_id=$this->request->session()->read('user_id');
			
			$class2 = TableRegistry::get('smgt_export'); 
			
			if($id)
			{
				$this->set('edit',true);
				$id = $this->Setting->my_simple_crypt($id,'d');	
				$exists = $class2->exists(['export_id' => $id]);
				
				if($exists)
				{
					$item = $class2->get($id);
					$this->set('row',$item);
				}
				else
					return $this->redirect(['action'=>'exportlist']);
			}
				
			if($this->request->is('post'))
			{	
							
				$users = TableRegistry::get('Smgt_users');		
				$student_data = $users->find()->where(['role'=>'student']);
				$student_data_array = $users->find()->where(['role'=>'student'])->hydrate(false)->toArray();
				$teacher_data = $users->find()->where(['role'=>'teacher']);
				$teacher_data_array = $users->find()->where(['role'=>'teacher'])->hydrate(false)->toArray();
				$parent_data = $users->find()->where(['role'=>'parent']);
				$parent_data_array = $users->find()->where(['role'=>'parent'])->hydrate(false)->toArray();
				$staff_data = $users->find()->where(['role'=>'supportstaff']);
				$staff_data_array = $users->find()->where(['role'=>'supportstaff'])->hydrate(false)->toArray();
				
				$studnet_db = array();
				$teacher_db = array();
				$parent_db = array();
				$staff_db = array();
				
				$c1=$this->request->data;
				
				ob_clean();
				ob_start();
				
				error_reporting(E_ALL);
				ini_set('display_errors', TRUE);
				ini_set('display_startup_errors', TRUE);
			
				$objPHPExcel = new PHPExcel();
				
				$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
										 ->setLastModifiedBy("Maarten Balliauw")
										 ->setTitle("Office 2007 XLSX Test Document")
										 ->setSubject("Office 2007 XLSX Test Document")
										 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
										 ->setKeywords("office 2007 openxml php")
										 ->setCategory("Test result file");
										 
				for($i=5;$i<=100;$i++)
				{
					$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
				}
				
				$wizard = new PHPExcel_Helper_HTML;
				
				if(isset($c1['export_model']))
				{
					$export_model = $c1['export_model'];
					
					$index = 0;
					$index_i = 1;
					
					foreach($export_model as $export_model_data)
					{
						if($export_model_data == 'student')
						{
							$html1 = "<strong>No.</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("A1", $wizard->toRichTextObject($html1));
							
							$html2 = "<strong>Student Name</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("B1", $wizard->toRichTextObject($html2));
							
							$html3 = "<strong>Class Name</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("C1", $wizard->toRichTextObject($html3));
							
							$html4 = "<strong>Section Name</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("D1", $wizard->toRichTextObject($html4));
							
							$html5 = "<strong>Roll No.</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("E1", $wizard->toRichTextObject($html5));
							
							$html6 = "<strong>Student Email</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("F1", $wizard->toRichTextObject($html6));
							
							$html7 = "<strong>Status</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("G1", $wizard->toRichTextObject($html7));
							
							$i = 2;
							$idx = 1;
							if(!empty($student_data))
							{
								$studnet_db = json_encode($student_data_array);
								
								foreach($student_data as $record)
								{
									$objPHPExcel->getActiveSheet()
									->setCellValue("A{$i}", $wizard->toRichTextObject($idx));
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("B{$i}", $wizard->toRichTextObject($this->Setting->get_user_id($record->user_id)));	
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("C{$i}", $wizard->toRichTextObject($this->Setting->get_class_id($record->classname)));
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("D{$i}", $wizard->toRichTextObject($this->Setting->get_section_name($record->classsection)));		
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("E{$i}", $wizard->toRichTextObject($record->roll_no));

									$objPHPExcel->getActiveSheet()
									->setCellValue("F{$i}", $wizard->toRichTextObject($record->email));
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("G{$i}", $wizard->toRichTextObject($record->status));
									
									$i++;
									$idx++;
								}	
							}						
							$objPHPExcel->getActiveSheet()->setTitle('Student Record');
							$objPHPExcel->setActiveSheetIndex($index);	
							$index++;
						}
						if($export_model_data == 'teacher')
						{
							$objPHPExcel->createSheet();
							$objPHPExcel->setActiveSheetIndex($index);	
							
							$html1 = "<strong>No.</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("A1", $wizard->toRichTextObject($html1));
							
							$html2 = "<strong>Teacher Name</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("B1", $wizard->toRichTextObject($html2));
							
							$html3 = "<strong>Class Name</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("C1", $wizard->toRichTextObject($html3));
							
							$html4 = "<strong>Subject Name</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("D1", $wizard->toRichTextObject($html4));
							
							$html6 = "<strong>Teacher Email</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("E1", $wizard->toRichTextObject($html6));
							
							$i = 2;
							$idx = 1;
							if(!empty($teacher_data))
							{
								$teacher_db = json_encode($teacher_data_array);
								
								foreach($teacher_data as $record)
								{
									$teacher_sub = array();					
									$teacher_sub[] = $this->Setting->get_teacher_subject($record->user_id);
									$subject_list = implode(',',$teacher_sub);
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("A{$i}", $wizard->toRichTextObject($idx));
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("B{$i}", $wizard->toRichTextObject($this->Setting->get_user_id($record->user_id)));	
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("C{$i}", $wizard->toRichTextObject($this->Setting->get_class_id($record->classname)));
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("D{$i}", $wizard->toRichTextObject($subject_list));		

									$objPHPExcel->getActiveSheet()
									->setCellValue("E{$i}", $wizard->toRichTextObject($record->email));
									
									$i++;
									$idx++;
								}	
							}		
							
							$objPHPExcel->getActiveSheet()->setTitle('Teacher Record');	
							$index++;	
						}
						if($export_model_data == 'parent')
						{
							
							$objPHPExcel->createSheet();
							$objPHPExcel->setActiveSheetIndex($index);	
							
							$html1 = "<strong>No.</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("A1", $wizard->toRichTextObject($html1));
							
							$html2 = "<strong>Parent Name</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("B1", $wizard->toRichTextObject($html2));
							
							$html3 = "<strong>Child Name</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("C1", $wizard->toRichTextObject($html3));
							
							$html6 = "<strong>Teacher Email</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("D1", $wizard->toRichTextObject($html6));
							
							$i = 2;
							$idx = 1;						
							if(!empty($parent_data))
							{
								$parent_db = json_encode($parent_data_array);
								
								foreach($parent_data as $record)
								{
									$child_nm = array();					
									$childs = array();	
									
									$childs = $this->Setting->get_child_id($record->user_id);
									if(!empty($childs))
									{
										foreach($childs as $child)
										{
											$child_nm[] = $this->Setting->get_user_id($child);
										}
									}
									$child_list = implode(',',$child_nm);
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("A{$i}", $wizard->toRichTextObject($idx));
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("B{$i}", $wizard->toRichTextObject($this->Setting->get_user_id($record->user_id)));	
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("C{$i}", $wizard->toRichTextObject($child_list));

									$objPHPExcel->getActiveSheet()
									->setCellValue("D{$i}", $wizard->toRichTextObject($record->email));
									
									$i++;
									$idx++;
								}	
							}		
							
							$objPHPExcel->getActiveSheet()->setTitle('Parent Record');	
							$index++;	
						}
						if($export_model_data == 'staff')
						{
							
							$objPHPExcel->createSheet();
							$objPHPExcel->setActiveSheetIndex($index);	
							
							$html1 = "<strong>No.</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("A1", $wizard->toRichTextObject($html1));
							
							$html2 = "<strong>SupportStaff Name</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("B1", $wizard->toRichTextObject($html2));
							
							$html3 = "<strong>SupportStaff Email</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("C1", $wizard->toRichTextObject($html3));
							
							$i = 2;
							$idx = 1;
							if(!empty($staff_data))
							{
								$staff_db = json_encode($staff_data_array);
								
								foreach($staff_data as $record)
								{
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("A{$i}", $wizard->toRichTextObject($idx));
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("B{$i}", $wizard->toRichTextObject($this->Setting->get_user_id($record->user_id)));	

									$objPHPExcel->getActiveSheet()
									->setCellValue("C{$i}", $wizard->toRichTextObject($record->email));
									
									$i++;
									$idx++;
								}	
							}		
							
							$objPHPExcel->getActiveSheet()->setTitle('SupportStaff Record');	
							$index++;	
						}
					}
				}						
				
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="ExportData.xlsx"');
				header('Cache-Control: max-age=0');
				// If you're serving to IE 9, then the following may be needed
				header('Cache-Control: max-age=1');

				// If you're serving to IE over SSL, then the following may be needed
				header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
				header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
				header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
				header ('Pragma: public'); // HTTP/1.0

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
				$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
				$objWriter->save('php://output');
				
				
				if($id)
				{
					$db_cl = array();
					
					$db_cl['export_title']=$c1['export_title'];

					if(!empty($c1['export_model']))
						$db_cl['export_model']=implode(',',$c1['export_model']);						
					
					$db_cl['type']='export';
					$db_cl['modify_date']=date("Y-m-d");
					$db_cl['modify_by']=$get_current_user_id;
					
					$item = $class2->get($id);
					$update_data = $class2->patchEntity($item,$db_cl);
					
					if($class2->save($update_data))
					{
					}
				}
				else
				{
					$db_cl = array();
					
					$db_cl['export_title']=$c1['export_title'];

					if(!empty($c1['export_model']))
						$db_cl['export_model']=implode(',',$c1['export_model']);						
					
					$db_cl['student']=$studnet_db;
					$db_cl['teacher']=$teacher_db;
					$db_cl['parent']=$parent_db;
					$db_cl['staff']=$staff_db;
					$db_cl['type']='export';
					$db_cl['created_date']=date("Y-m-d");
					$db_cl['created_by']=$get_current_user_id;
					
					$a=$class2->newEntity();
					$a=$class2->patchEntity($a,$db_cl);
					
					if($class2->save($a))
					{
					}
				}
				die;						
			}
		}
	
		public function excelexport($id=0) 
		{
			$this->set('Comman','Export');
			$get_current_user_id=$this->request->session()->read('user_id');			 					
		}
	
		public function exportmultidelete() 
		{
			$this->autoRender = false;
			$id=json_decode($_REQUEST[e_id]);
			
			$i = 0;
			
			foreach($id as $recordid)
			{
				$class = TableRegistry::get('smgt_export');
				
				$item =$class->get($recordid);

				if($class->delete($item))
				{
					$i = 1;		
				}	
			}
			if($i == 1)
			{
				$this->Flash->success(__('Export Deleted Successfully', null), 
						'default', 
						 array('class' => 'success'));
			}
		}
	
		public function exportlist($id=0)
		{
			$this->set('Comman','Export');
			
			$class = TableRegistry::get('smgt_export');
			$query=$class->find()->where(['type'=>'export'])->order(['export_id'=>'DESC']);
			$this->set('it',$query);
			
			if(isset($_REQUEST['name']))
			{		
				$class2 = TableRegistry::get('smgt_export');
				$item = $class2->get($id);
				$export_model = $item->export_model;
				$export_model = explode(',',$export_model);
				
				$users = TableRegistry::get('Smgt_users');		
				$student_data = json_decode($item->student);
				$teacher_data = json_decode($item->teacher);
				$parent_data = $users->find()->where(['role'=>'parent']);
				$staff_data = $users->find()->where(['role'=>'supportstaff']);
			
				$c1=$this->request->data;
				
				ob_clean();
				ob_start();
				
				error_reporting(E_ALL);
				ini_set('display_errors', TRUE);
				ini_set('display_startup_errors', TRUE);
			
				$objPHPExcel = new PHPExcel();
				
				$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
										 ->setLastModifiedBy("Maarten Balliauw")
										 ->setTitle("Office 2007 XLSX Test Document")
										 ->setSubject("Office 2007 XLSX Test Document")
										 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
										 ->setKeywords("office 2007 openxml php")
										 ->setCategory("Test result file");
										 
				for($i=5;$i<=100;$i++)
				{
					$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
				}
				
				$wizard = new PHPExcel_Helper_HTML;
				
				if($export_model)
				{

					$index = 0;
					$index_i = 1;
					
					foreach($export_model as $export_model_data)
					{
						if($export_model_data == 'student')
						{
							$html1 = "<strong>No.</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("A1", $wizard->toRichTextObject($html1));
							
							$html2 = "<strong>Student Name</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("B1", $wizard->toRichTextObject($html2));
							
							$html3 = "<strong>Class Name</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("C1", $wizard->toRichTextObject($html3));
							
							$html4 = "<strong>Section Name</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("D1", $wizard->toRichTextObject($html4));
							
							$html5 = "<strong>Roll No.</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("E1", $wizard->toRichTextObject($html5));
							
							$html6 = "<strong>Student Email</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("F1", $wizard->toRichTextObject($html6));
							
							$html7 = "<strong>Status</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("G1", $wizard->toRichTextObject($html7));
							
							$i = 2;
							$idx = 1;
							if(!empty($student_data))
							{
								foreach($student_data as $record)
								{
									$objPHPExcel->getActiveSheet()
									->setCellValue("A{$i}", $wizard->toRichTextObject($idx));
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("B{$i}", $wizard->toRichTextObject($this->Setting->get_user_id($record->user_id)));	
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("C{$i}", $wizard->toRichTextObject($this->Setting->get_class_id($record->classname)));
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("D{$i}", $wizard->toRichTextObject($this->Setting->get_section_name($record->classsection)));		
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("E{$i}", $wizard->toRichTextObject($record->roll_no));

									$objPHPExcel->getActiveSheet()
									->setCellValue("F{$i}", $wizard->toRichTextObject($record->email));
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("G{$i}", $wizard->toRichTextObject($record->status));
									
									$i++;
									$idx++;
								}	
							}						
							$objPHPExcel->getActiveSheet()->setTitle('Student Record');
							$objPHPExcel->setActiveSheetIndex($index);	
							$index++;
						}
						if($export_model_data == 'teacher')
						{
							$objPHPExcel->createSheet();
							$objPHPExcel->setActiveSheetIndex($index);	
							
							$html1 = "<strong>No.</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("A1", $wizard->toRichTextObject($html1));
							
							$html2 = "<strong>Teacher Name</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("B1", $wizard->toRichTextObject($html2));
							
							$html3 = "<strong>Class Name</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("C1", $wizard->toRichTextObject($html3));
							
							$html4 = "<strong>Subject Name</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("D1", $wizard->toRichTextObject($html4));
							
							$html6 = "<strong>Teacher Email</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("E1", $wizard->toRichTextObject($html6));
							
							$i = 2;
							$idx = 1;
							if(!empty($teacher_data))
							{
								foreach($teacher_data as $record)
								{
									$teacher_sub = array();					
									$teacher_sub[] = $this->Setting->get_teacher_subject($record->user_id);
									$subject_list = implode(',',$teacher_sub);
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("A{$i}", $wizard->toRichTextObject($idx));
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("B{$i}", $wizard->toRichTextObject($this->Setting->get_user_id($record->user_id)));	
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("C{$i}", $wizard->toRichTextObject($this->Setting->get_class_id($record->classname)));
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("D{$i}", $wizard->toRichTextObject($subject_list));		

									$objPHPExcel->getActiveSheet()
									->setCellValue("E{$i}", $wizard->toRichTextObject($record->email));
									
									$i++;
									$idx++;
								}	
							}		
							
							$objPHPExcel->getActiveSheet()->setTitle('Teacher Record');	
							$index++;	
						}
						if($export_model_data == 'parent')
						{
							
							$objPHPExcel->createSheet();
							$objPHPExcel->setActiveSheetIndex($index);	
							
							$html1 = "<strong>No.</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("A1", $wizard->toRichTextObject($html1));
							
							$html2 = "<strong>Parent Name</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("B1", $wizard->toRichTextObject($html2));
							
							$html3 = "<strong>Child Name</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("C1", $wizard->toRichTextObject($html3));
							
							$html6 = "<strong>Teacher Email</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("D1", $wizard->toRichTextObject($html6));
							
							$i = 2;
							$idx = 1;						
							if(!empty($parent_data))
							{
								foreach($parent_data as $record)
								{
									$childs = array();	
									
									$childs = $this->Setting->get_child_id($record->user_id);
									if(!empty($childs))
									{
										foreach($childs as $child)
										{
											$child_nm[] = $this->Setting->get_user_id($child);
										}
									}
									$child_list = implode(',',$child_nm);
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("A{$i}", $wizard->toRichTextObject($idx));
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("B{$i}", $wizard->toRichTextObject($this->Setting->get_user_id($record->user_id)));	
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("C{$i}", $wizard->toRichTextObject($child_list));

									$objPHPExcel->getActiveSheet()
									->setCellValue("D{$i}", $wizard->toRichTextObject($record->email));
									
									$i++;
									$idx++;
								}	
							}		
							
							$objPHPExcel->getActiveSheet()->setTitle('Parent Record');	
							$index++;	
						}
						if($export_model_data == 'staff')
						{
							
							$objPHPExcel->createSheet();
							$objPHPExcel->setActiveSheetIndex($index);	
							
							$html1 = "<strong>No.</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("A1", $wizard->toRichTextObject($html1));
							
							$html2 = "<strong>SupportStaff Name</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("B1", $wizard->toRichTextObject($html2));
							
							$html3 = "<strong>SupportStaff Email</strong>";
							$objPHPExcel->getActiveSheet()
							->setCellValue("C1", $wizard->toRichTextObject($html3));
							
							$i = 2;
							$idx = 1;
							if(!empty($staff_data))
							{
								foreach($staff_data as $record)
								{
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("A{$i}", $wizard->toRichTextObject($idx));
									
									$objPHPExcel->getActiveSheet()
									->setCellValue("B{$i}", $wizard->toRichTextObject($this->Setting->get_user_id($record->user_id)));	

									$objPHPExcel->getActiveSheet()
									->setCellValue("C{$i}", $wizard->toRichTextObject($record->email));
									
									$i++;
									$idx++;
								}	
							}		
							
							$objPHPExcel->getActiveSheet()->setTitle('SupportStaff Record');	
							$index++;	
						}
					}
				}						
				
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="ExportData.xlsx"');
				header('Cache-Control: max-age=0');
				// If you're serving to IE 9, then the following may be needed
				header('Cache-Control: max-age=1');

				// If you're serving to IE over SSL, then the following may be needed
				header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
				header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
				header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
				header ('Pragma: public'); // HTTP/1.0

				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
				$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
				$objWriter->save('php://output');
				die;
			}
		}
		
		public function importDelete($id)
		{
			$this->request->is(['post','delete']);
			$class1 = TableRegistry::get('smgt_export');
			$item = $class1->get($id);
			if($class1->delete($item))
			{
				$this->Flash->success(__('Import Deleted Successfully', null), 
								'default', 
								 array('class' => 'success'));
				
			}
			return $this->redirect(['action'=>'importlist']);
		}
	
		public function addimport()
		{
			$this->set('Comman','Export');
			$get_current_user_id=$this->request->session()->read('user_id');
			
			$class2 = TableRegistry::get('smgt_export');
			$user_tbl = TableRegistry::get('smgt_users');
			
			$import_model = array();
			
			$student_chk = 0;
			$teacher_chk = 0;
			$parent_chk = 0;
			$staff_chk = 0;
			
			if($this->request->is('post'))
			{
				if($_FILES['student_csv_file']['name'] != '')
				{	
					$import_model[] = 'student';
					
					$errors= array();
					$file_name = $_FILES['student_csv_file']['name'];
					$file_size =$_FILES['student_csv_file']['size'];
					$file_tmp =$_FILES['student_csv_file']['tmp_name'];
					$file_type=$_FILES['student_csv_file']['type'];
					$value = explode(".", $_FILES['student_csv_file']['name']);

					$file_ext = strtolower(array_pop($value));

					$extensions = array("csv");

					if(in_array($file_ext,$extensions )=== false)
					{
						$errors[]="this file not allowed, please choose a CSV file.";
					}
					if($file_size > 2097152){
						$errors[]='File size limit 2 MB';
					}
					
					if(empty($errors)==true)
					{
						
						$hasher = new DefaultPasswordHasher();
						
						$rows = array_map('str_getcsv', file($file_tmp));		
						
						$header = array_map('strtolower',array_shift($rows));
						
						$csv = array();
						$i=0;
						foreach ($rows as $row) 
						{
							$csv = array_combine($header, $row);
							
							$username = $csv['username'];
							$email = $csv['email'];
							$user_id = 0;
							$password = $hasher->hash($csv['password']);
							$class=1;
							
							if($password == "") // if user not exist and password is empty but the column is set, it will be generated
								$password =123;
							
							$problematic_row = false;
							
							$user=$this->Setting->check_user($username,$email);
							
							$student_chk = 1;
							
							if(!$user)
							{
								$studentID = $this->Setting->generate_studentID();
								$student_chk = 2;
								
								$c1=$this->request->data;
								
								$a=$user_tbl->newEntity();
								
								$c1['classname']=null;
								$c1['roll_no']=$csv['roll_no'];
								$c1['first_name']=$csv['first_name'];
								$c1['middle_name']=$csv['middle_name'];
								$c1['last_name']=$csv['last_name'];
								$c1['gender']=$csv['gender'];
								$c1['date_of_birth']=$csv['birth_date'];
								$c1['address']=$csv['address'];
								$c1['city']=$csv['city_name'];
								$c1['state']=$csv['state_name'];
								$c1['zip_code']=$csv['zip_code'];
								$c1['mobile_no']=$csv['mobile_number'];
								$c1['alternet_mobile_no']=$csv['alternet_mobile_number'];
								$c1['phone']=$csv['phone'];
								$c1['email']=$email;
								$c1['username']=$username;
								$c1['password']=$password;
								$c1['image']='finel-logo6.jpg';
								$c1['working_hour']=null;
								$c1['position']=null;
								$c1['submitted_document']=null;
								$c1['relation']=null;
								$c1['role']='student';
								$c1['status']='Not Approved';
								$c1['classsection']=0;														
								$c1['studentID'] = $studentID['studentID'];
								$c1['studentID_prefix'] = $studentID['studentID_prefix'];
								$c1['is_deactive']=0;
								$c1['exam_hall_receipt']=0;
							
								$a=$user_tbl->patchEntity($a,$c1);
									
								if($user_tbl->save($a))
								{
									$i=1;								
								}
					
							}
						}
					}				
				}
				
				if($_FILES['teacher_csv_file']['name'] != '')
				{	
					$import_model[] = 'teacher';
					
					$errors= array();
					$file_name = $_FILES['teacher_csv_file']['name'];
					$file_size =$_FILES['teacher_csv_file']['size'];
					$file_tmp =$_FILES['teacher_csv_file']['tmp_name'];
					$file_type=$_FILES['teacher_csv_file']['type'];

					$value = explode(".", $_FILES['teacher_csv_file']['name']);
		
					$file_ext = strtolower(array_pop($value));
		
					$extensions = array("csv");
				
					if(in_array($file_ext,$extensions )=== false)
					{
						$errors[]="this file not allowed, please choose a CSV file.";
					}
					if($file_size > 2097152){
						$errors[]='File size limit 2 MB';
					}
					
					if(empty($errors)==true)
					{
						
						$hasher = new DefaultPasswordHasher();
							
						$rows = array_map('str_getcsv', file($file_tmp));		
						
						$header = array_map('strtolower',array_shift($rows));
						
						$csv = array();
						$i=0;
						foreach ($rows as $row) 
						{
							$csv = array_combine($header, $row);
							
							$username = $csv['username'];
							$email = $csv['email'];
							$user_id = 0;
							$password = $hasher->hash($csv['password']);
							$class=1;
							
							if($password == "") // if user not exist and password is empty but the column is set, it will be generated
								$password =123;
							
							$problematic_row = false;
							
							$user=$this->Setting->check_user($username,$email);
							
							$teacher_chk = 1;
							
							if(!$user)
							{
								$teacher_chk = 2;
								
								$c1=$this->request->data;
								
								$a=$user_tbl->newEntity();
								
								$c1['classname']=null;
								$c1['roll_no']=null;
								$c1['first_name']=$csv['first_name'];
								$c1['middle_name']=$csv['middle_name'];
								$c1['last_name']=$csv['last_name'];
								$c1['gender']=$csv['gender'];
								$c1['date_of_birth']=$csv['birth_date'];
								$c1['address']=$csv['address'];
								$c1['city']=$csv['city_name'];
								$c1['state']=$csv['state_name'];
								$c1['zip_code']=$csv['zip_code'];
								$c1['mobile_no']=$csv['mobile_number'];
								$c1['alternet_mobile_no']=$csv['alternet_mobile_number'];
								$c1['phone']=$csv['phone'];
								$c1['email']=$email;
								$c1['username']=$username;
								$c1['password']=$password;
								$c1['image']='finel-logo6.jpg';
								$c1['working_hour']=null;
								$c1['position']=null;
								$c1['submitted_document']=null;
								$c1['relation']=null;
								$c1['role']='teacher';
								$c1['status']=null;
								$c1['classsection']=0;														
								
								$a=$user_tbl->patchEntity($a,$c1);
									
								if($user_tbl->save($a))
								{
									$i=1;								
								}
					
							}
						}
					}				
				}
				
				if($_FILES['parent_csv_file']['name'] != '')
				{	
					$import_model[] = 'parent';
					
					$errors= array();
					$file_name = $_FILES['parent_csv_file']['name'];
					$file_size =$_FILES['parent_csv_file']['size'];
					$file_tmp =$_FILES['parent_csv_file']['tmp_name'];
					$file_type=$_FILES['parent_csv_file']['type'];

					$value = explode(".", $_FILES['parent_csv_file']['name']);

					$file_ext = strtolower(array_pop($value));

					$extensions = array("csv");
		
					if(in_array($file_ext,$extensions )=== false)
					{
						$errors[]="this file not allowed, please choose a CSV file.";
					}
					if($file_size > 2097152){
						$errors[]='File size limit 2 MB';
					}
					
					if(empty($errors)==true)
					{
						
						$hasher = new DefaultPasswordHasher();
		
						$rows = array_map('str_getcsv', file($file_tmp));		
						
						$header = array_map('strtolower',array_shift($rows));
						
						$csv = array();
						$i=0;
						foreach ($rows as $row) 
						{
							$csv = array_combine($header, $row);
							
							$username = $csv['username'];
							$email = $csv['email'];
							$user_id = 0;
							$password = $hasher->hash($csv['password']);
							$class=1;
							
							if($password == "") // if user not exist and password is empty but the column is set, it will be generated
								$password =123;
							
							$problematic_row = false;
							
							$user=$this->Setting->check_user($username,$email);
							
							$parent_chk = 1;
							
							if(!$user)
							{
								$parent_chk = 2;
								
								$c1=$this->request->data;
								
								$a=$user_tbl->newEntity();
								
								$c1['classname']=null;
								$c1['roll_no']=null;
								$c1['first_name']=$csv['first_name'];
								$c1['middle_name']=$csv['middle_name'];
								$c1['last_name']=$csv['last_name'];
								$c1['gender']=$csv['gender'];
								$c1['date_of_birth']=$csv['birth_date'];
								$c1['address']=$csv['address'];
								$c1['city']=$csv['city_name'];
								$c1['state']=$csv['state_name'];
								$c1['zip_code']=$csv['zip_code'];
								$c1['mobile_no']=$csv['mobile_number'];
								$c1['alternet_mobile_no']=$csv['alternet_mobile_number'];
								$c1['phone']=$csv['phone'];
								$c1['email']=$email;
								$c1['username']=$username;
								$c1['password']=$password;
								$c1['image']='finel-logo6.jpg';
								$c1['working_hour']=null;
								$c1['position']=null;
								$c1['submitted_document']=null;
								$c1['relation']=null;
								$c1['role']='parent';
								$c1['status']=null;
								$c1['classsection']=0;														
								
								$a=$user_tbl->patchEntity($a,$c1);
									
								if($user_tbl->save($a))
								{
									$i=1;								
								}
					
							}
						}
					}				
				}
				
				if($_FILES['staff_csv_file']['name'] != '')
				{	
					$import_model[] = 'staff';
					
					$errors= array();
					$file_name = $_FILES['staff_csv_file']['name'];
					$file_size =$_FILES['staff_csv_file']['size'];
					$file_tmp =$_FILES['staff_csv_file']['tmp_name'];
					$file_type=$_FILES['staff_csv_file']['type'];
					//$file_ext=strtolower(end(explode('.',$_FILES['csv_file']['name'])));
					$value = explode(".", $_FILES['staff_csv_file']['name']);
					// var_dump($value)."<br>";
					$file_ext = strtolower(array_pop($value));
					// var_dump($file_ext);
					$extensions = array("csv");
					// $upload_dir = wp_upload_dir();die;
					if(in_array($file_ext,$extensions )=== false)
					{
						$errors[]="this file not allowed, please choose a CSV file.";
					}
					if($file_size > 2097152){
						$errors[]='File size limit 2 MB';
					}
					
					if(empty($errors)==true)
					{
						
						$hasher = new DefaultPasswordHasher();

						$rows = array_map('str_getcsv', file($file_tmp));		
						
						$header = array_map('strtolower',array_shift($rows));
						
						$csv = array();
						$i=0;
						foreach ($rows as $row) 
						{
							$csv = array_combine($header, $row);
							
							$username = $csv['username'];
							$email = $csv['email'];
							$user_id = 0;
							$password = $hasher->hash($csv['password']);
							$class=1;
							
							if($password == "") // if user not exist and password is empty but the column is set, it will be generated
								$password =123;
							
							$problematic_row = false;
							
							$user=$this->Setting->check_user($username,$email);
							
							$staff_chk = 1;
							
							if(!$user)
							{
								$staff_chk = 2;
								
								$c1=$this->request->data;
								
								$a=$user_tbl->newEntity();
								
								$c1['classname']=null;
								$c1['roll_no']=null;
								$c1['first_name']=$csv['first_name'];
								$c1['middle_name']=$csv['middle_name'];
								$c1['last_name']=$csv['last_name'];
								$c1['gender']=$csv['gender'];
								$c1['date_of_birth']=$csv['birth_date'];
								$c1['address']=$csv['address'];
								$c1['city']=$csv['city_name'];
								$c1['state']=$csv['state_name'];
								$c1['zip_code']=$csv['zip_code'];
								$c1['mobile_no']=$csv['mobile_number'];
								$c1['alternet_mobile_no']=$csv['alternet_mobile_number'];
								$c1['phone']=$csv['phone'];
								$c1['email']=$email;
								$c1['username']=$username;
								$c1['password']=$password;
								$c1['image']='finel-logo6.jpg';
								$c1['working_hour']=null;
								$c1['position']=null;
								$c1['submitted_document']=null;
								$c1['relation']=null;
								$c1['role']='supportstaff';
								$c1['status']=null;
								$c1['classsection']=0;														
								
								$a=$user_tbl->patchEntity($a,$c1);
									
								if($user_tbl->save($a))
								{
									$i=1;								
								}
					
							}
						}
					}				
				}
				
				$c1=$this->request->data;
					
				$db_cl = array();
				
				$db_cl['import_title']=$c1['import_title'];

				if(!empty($import_model))
					$db_cl['import_model']=implode(',',$import_model);						
				
				$db_cl['type']='import';
				$db_cl['created_date']=date("Y-m-d");
				$db_cl['created_by']=$get_current_user_id;
				
				$a=$class2->newEntity();
				$a=$class2->patchEntity($a,$db_cl);
				
				$sucs = 1;
				
				if($student_chk == 1 && $student_chk != 2)
				{
					$sucs = 2;
					$this->Flash->success(__('Duplicate Student Record', null), 
								'default', 
								 array('class' => 'success'));
				}
				
				if($teacher_chk == 1 && $teacher_chk != 2)
				{
					$sucs = 2;
					$this->Flash->success(__('Duplicate Teacher Record', null), 
								'default', 
								 array('class' => 'success'));
				}
				
				if($parent_chk == 1 && $parent_chk != 2)
				{
					$sucs = 2;
					$this->Flash->success(__('Duplicate Parent Record', null), 
								'default', 
								 array('class' => 'success'));
				}
				
				if($staff_chk == 1 && $staff_chk != 2)
				{
					$sucs = 2;
					$this->Flash->success(__('Duplicate Support Staff Record', null), 
								'default', 
								 array('class' => 'success'));
				}
					
				if($class2->save($a))
				{
					if($sucs == 1)
					{
						$this->Flash->success(__('Data Imported Successfully', null), 
									'default', 
									 array('class' => 'success'));
					}
				}
				return $this->redirect(['action'=>'importlist']);
			}
		}
	
		public function importlist()
		{
			$this->set('Comman','Export');
			
			$class = TableRegistry::get('smgt_export');
			$query=$class->find()->where(['type'=>'import'])->order(['export_id'=>'DESC']);
			$this->set('it',$query);
		}
	
		public function importedit()
		{			
			$term_id = $_REQUEST['class_section_id'];
			
			$smgt_export = TableRegistry::get('smgt_export');	
			$retrieved_data = $smgt_export->get($term_id);	
			$this->set('model_data',$retrieved_data);		
		}
		
		public function updateImport()
		{
			if($this->request->is('post'))
			{
				$term_id = $_REQUEST['export_id'];
				
				$smgt_export = TableRegistry::get('smgt_export');		
				$retrieved_data = $smgt_export->get($term_id);
				
				$retrieved_data['import_title'] = $_REQUEST['import_title'];
				
				$smgt_export->save($retrieved_data);
				
				$this->Flash->success(__('Import Updated Successfully', null), 
								'default', 
								 array('class' => 'danger'));
								 
				return $this->redirect(['action'=>'importlist']);
			}
		}
		
		public function addhostel($id=0)
		{
			$this->set('Comman','Hostel');
			$get_current_user_id=$this->request->session()->read('user_id');
			
			$class2 = TableRegistry::get('smgt_hostel'); 
			
			if($id)
			{
				$this->set('edit',true);
				$item = $class2->get($id);
				$this->set('row',$item);
			}
				
			if($this->request->is('post'))
			{	
				if($id)
				{
					$c1=$this->request->data;
						
					$db_cl = array();
					
					$db_cl['hostel_name']=$c1['hostel_name'];
					$db_cl['hostel_type']=$c1['hostel_type'];
					$db_cl['hostel_desc']=$c1['hostel_desc'];
					$db_cl['modify_date']=date("Y-m-d");
					$db_cl['modify_by']=$get_current_user_id;
					
					$item = $class2->get($id);
					$a=$class2->patchEntity($item,$db_cl);

					if($class2->save($a))
					{
						$this->Flash->success(__('Hostel Edited Successfully', null), 
									'default', 
									array('class' => 'success'));
					}
					return $this->redirect(['action'=>'hostellist']);
				}
				else
				{
					$c1=$this->request->data;
						
					$db_cl = array();
					
					$db_cl['hostel_name']=$c1['hostel_name'];
					$db_cl['hostel_type']=$c1['hostel_type'];
					$db_cl['hostel_desc']=$c1['hostel_desc'];
					$db_cl['created_date']=date("Y-m-d");
					$db_cl['created_by']=$get_current_user_id;
					
					$a=$class2->newEntity();
					$a=$class2->patchEntity($a,$db_cl);

					if($class2->save($a))
					{
						$this->Flash->success(__('Hostel added Successfully', null), 
									'default', 
									array('class' => 'success'));
					}
					return $this->redirect(['action'=>'hostellist']);
				}
			}
		}
	
		public function hostelmultidelete() 
		{
			$this->autoRender = false;
			$id=json_decode($_REQUEST[e_id]);
			
			$i = 0;
			
			foreach($id as $recordid)
			{
				$class = TableRegistry::get('smgt_hostel');
				
				$item =$class->get($recordid);

				if($class->delete($item))
				{
					$i = 1;
				}		
			}
			if($i == 1)
			{
				$this->Flash->success(__('Hostel Deleted Successfully', null), 
							'default', 
							 array('class' => 'success'));
			}
		}
	
		public function hostellist($id=0)
		{
			$this->set('Comman','Hostel');
			
			$class = TableRegistry::get('smgt_hostel');
			$query=$class->find()->order(['hostel_id'=>'DESC']);
			$this->set('it',$query);
			
		}
		
		public function addroom($id=0)
		{
			$class2 = TableRegistry::get('smgt_hostel_room'); 
			
			$conn = ConnectionManager::get('default');
			$result = $conn->query("SHOW TABLE STATUS LIKE 'smgt_hostel_room';")->fetchAll('assoc');
			$next = $result[0]['Auto_increment'];
			$this->set('next_id',$next);
			
			$this->set('Comman','Hostel');
			$get_current_user_id=$this->request->session()->read('user_id');	
			
			$smgt_hostel = TableRegistry::get('smgt_hostel');	
			$cls = $smgt_hostel->find("list",["keyField"=>"hostel_id","valueField"=>"hostel_name"]);	
			$this->set('cls',$cls);
			
			$smgt_hostel_room_category=TableRegistry::get('smgt_hostel_room_category');
			$get_all_data=$smgt_hostel_room_category->find();
			$this->set('category_data',$get_all_data);
							  
			if($id)
			{
				$this->set('edit',true);
				$item = $class2->get($id);
				$this->set('row',$item);
			}
				
			if($this->request->is('post'))
			{	
				if($id)
				{
					$c1=$this->request->data;
						
					$db_cl = array();
					
					$db_cl['room_unique_id']=$c1['room_unique_id'];
					$db_cl['hostel_id']=$c1['hostel_id'];
					$db_cl['room_category']=$c1['room_category'];
					$db_cl['beds_capacity']=$c1['beds_capacity'];
					$db_cl['room_desc']=$c1['room_desc'];
					$db_cl['modify_date']=date("Y-m-d");
					$db_cl['modify_by']=$get_current_user_id;
					
					$item = $class2->get($id);
					$a=$class2->patchEntity($item,$db_cl);

					if($class2->save($a))
					{
						$this->Flash->success(__('Edit Hostel Room Data Successfully', null), 
									'default', 
									array('class' => 'success'));
					}
					return $this->redirect(['action'=>'roomlist']);
				}
				else
				{
					$c1=$this->request->data;
						
					$db_cl = array();
					
					$db_cl['room_unique_id']=$c1['room_unique_id'];
					$db_cl['hostel_id']=$c1['hostel_id'];
					$db_cl['room_category']=$c1['room_category'];
					$db_cl['beds_capacity']=$c1['beds_capacity'];
					$db_cl['room_desc']=$c1['room_desc'];
					$db_cl['created_date']=date("Y-m-d");
					$db_cl['created_by']=$get_current_user_id;
					
					$a=$class2->newEntity();
					$a=$class2->patchEntity($a,$db_cl);

					if($class2->save($a))
					{
						$this->Flash->success(__('Add Hostel Room Data Successfully', null), 
									'default', 
									array('class' => 'success'));
					}
					return $this->redirect(['action'=>'roomlist']);
				}
			}
		}
		
		public function roomlist()
		{
			$this->set('Comman','Hostel');
			
			$class = TableRegistry::get('smgt_hostel_room');
			$query=$class->find()->order(['room_id'=>'DESC']);
			$this->set('it',$query);
		}
		
		public function adddata($id = null) 
		{
			$get_current_user_id=$this->request->session()->read('user_id');
			
			$this->autoRender = false;
			if($this->request->is('ajax'))
			{
				if(!empty($_POST['category_name']))
				{
					$cls = $_POST['category_name'];
					
					$cat = TableRegistry::get('smgt_hostel_room_category');
					$a = $cat->newEntity();

					$a['category_name']=$cls;
					$a['created_date']=date("Y-m-d");
					$a['created_by']=$get_current_user_id;
					
					if($cat->save($a))
					{
						$i=$a['hostel_room_category_id'];
					}
					echo $i;
				}
				else
					echo "false";
				die();
		   }
		}
		
		public function categoryDelete($id = null)
		{
			$this->autoRender = false;
			if($this->request->is('ajax'))
			{
				$category_id=$_POST['category_id'];
				$cat = TableRegistry::get('smgt_hostel_room_category');
				$items=$cat->get($category_id);
				if($cat->delete($items))
				{
					$this->Flash->success(__('Hostel Room Category Deleted Successfully', null), 
											'default', 
											array('class' => 'success'));	
				}
			}
		}
		
		public function roomDelete($id)
		{
			$this->request->is(['post','delete']);
			$class1 = TableRegistry::get('smgt_hostel_room');
			$item = $class1->get($id);
			if($class1->delete($item))
			{
				$this->Flash->success(__('Hostel Room Deleted Successfully', null), 
								'default', 
								 array('class' => 'success'));
				
			}
			return $this->redirect(['action'=>'roomlist']);
		}
		
		public function roommultidelete() 
		{
			$this->autoRender = false;
			$id=json_decode($_REQUEST[e_id]);
			
			$i = 0;
			
			foreach($id as $recordid)
			{
				$class = TableRegistry::get('smgt_hostel_room');
				
				$item =$class->get($recordid);

				if($class->delete($item))
				{
					$i = 1;
				}		
			}
			if($i == 1)
			{
				$this->Flash->success(__('Hostel Room Deleted Successfully', null), 
							'default', 
							 array('class' => 'success'));
			}
		}
	
		public function addbeds($id=0)
		{
			$class2 = TableRegistry::get('smgt_add_beds'); 
			
			$conn = ConnectionManager::get('default');
			$result = $conn->query("SHOW TABLE STATUS LIKE 'smgt_add_beds';")->fetchAll('assoc');
			$next = $result[0]['Auto_increment'];
			$this->set('next_id',$next);
			
			$this->set('Comman','Hostel');
			$get_current_user_id=$this->request->session()->read('user_id');
			
			$smgt_hostel_room = TableRegistry::get('smgt_hostel_room');	
			$cls = $smgt_hostel_room->find("list",["keyField"=>"room_id","valueField"=>"room_unique_id"]);	
			$this->set('cls',$cls);
			
			if($id)
			{
				$this->set('edit',true);
				$item = $class2->get($id);
				$this->set('row',$item);
			}
				
			if($this->request->is('post'))
			{	
				$beds_data_capacity = 0;
				$room_beds_capacity = 0;
				
				if($id)
				{
					$c1=$this->request->data;
					
					$room_unique_id = $c1['room_unique_id'];
					
					$hostel_room_data = $smgt_hostel_room->find()->where(['room_id'=>$room_unique_id])->hydrate(false)->toArray();
					$bed_data = $class2->find()->where(['room_unique_id'=>$room_unique_id])->hydrate(false)->toArray();
					
					$beds_data_capacity = count($bed_data);
					$room_beds_capacity = $hostel_room_data[0]['beds_capacity'];
					
					if($beds_data_capacity == $room_beds_capacity)
					{
						$this->Flash->success(__('Unsuccessful! Hostel Room Bed No Capacity.', null), 
										'default', 
										array('class' => 'success'));
										
						return $this->redirect(['action'=>'bedslist']);
					}
					else
					{
						$db_cl = array();
						
						$db_cl['bed_unique_id']=$c1['bed_unique_id'];
						$db_cl['room_unique_id']=$c1['room_unique_id'];
						$db_cl['bed_desc']=$c1['bed_desc'];
						$db_cl['modify_date']=date("Y-m-d");
						$db_cl['modify_by']=$get_current_user_id;
						
						$item = $class2->get($id);
						$a=$class2->patchEntity($item,$db_cl);

						if($class2->save($a))
						{
							$this->Flash->success(__('Hostel Room Bed Data Edited Successfully', null), 
										'default', 
										array('class' => 'success'));
						}
						return $this->redirect(['action'=>'bedslist']);
					}
				}
				else
				{
					$c1=$this->request->data;
					
					$room_unique_id = $c1['room_unique_id'];
					
					$hostel_room_data = $smgt_hostel_room->find()->where(['room_id'=>$room_unique_id])->hydrate(false)->toArray();
					$bed_data = $class2->find()->where(['room_unique_id'=>$room_unique_id])->hydrate(false)->toArray();
					
					$beds_data_capacity = count($bed_data);
					$room_beds_capacity = $hostel_room_data[0]['beds_capacity'];
					
					if($beds_data_capacity == $room_beds_capacity)
					{
						$this->Flash->success(__('Unsuccessful! Hostel Room Bed has No Extra Capacity.', null), 
										'default', 
										array('class' => 'success'));
										
						return $this->redirect(['action'=>'bedslist']);
					}
					else
					{
						$db_cl = array();
						
						$db_cl['bed_unique_id']=$c1['bed_unique_id'];
						$db_cl['room_unique_id']=$c1['room_unique_id'];
						$db_cl['bed_desc']=$c1['bed_desc'];
						$db_cl['created_date']=date("Y-m-d");
						$db_cl['created_by']=$get_current_user_id;
						
						$a=$class2->newEntity();
						$a=$class2->patchEntity($a,$db_cl);

						if($class2->save($a))
						{
							$this->Flash->success(__('Hostel Room Bed Data Added Successfully', null), 
										'default', 
										array('class' => 'success'));
						}
						return $this->redirect(['action'=>'bedslist']);
					}
				}
			}
		}
		
		public function bedslist()
		{
			$this->set('Comman','Hostel');
			
			$class = TableRegistry::get('smgt_add_beds');
			$query=$class->find()->order(['bed_id'=>'DESC']);
			$this->set('it',$query);
		}
		
		public function bedsDelete($id)
		{
			$this->request->is(['post','delete']);
			$class1 = TableRegistry::get('smgt_add_beds');
			$item = $class1->get($id);
			if($class1->delete($item))
			{
				$this->Flash->success(__('Hostel Bed Deleted Successfully', null), 
								'default', 
								 array('class' => 'success'));
				
			}
			return $this->redirect(['action'=>'bedslist']);
		}
		
		public function bedsmultidelete() 
		{
			$this->autoRender = false;
			$id=json_decode($_REQUEST[e_id]);
			
			$i = 0;
			
			foreach($id as $recordid)
			{
				$class = TableRegistry::get('smgt_add_beds');
				
				$item =$class->get($recordid);

				if($class->delete($item))
				{
					$i = 1;
				}		
			}
			if($i == 1)
			{
				$this->Flash->success(__('Hostel Bed Deleted Successfully', null), 
							'default', 
							 array('class' => 'success'));
			}
		}
	
		public function assignroom($id=0)
		{
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$this->set('roomid',$id);
			
			$smgt_assign_bed_new = TableRegistry::get('smgt_assign_bed_new'); 
			$class2 = TableRegistry::get('smgt_add_beds'); 
			$student_tbl = TableRegistry::get('smgt_users'); 
			
			$this->set('Comman','Hostel');
			$get_current_user_id=$this->request->session()->read('user_id');
			
			$cls = $class2->find()->where(['room_unique_id'=>$id])->hydrate(false)->toArray();	
			if(!empty($cls))
				$this->set('cls',$cls);
			
			$stud = $student_tbl->find()->where(['role'=>'student']);	
			$this->set('stud',$stud);
			
			if($this->request->is('post'))
			{
				$c1=$this->request->data;
						
				$db_cl = array();
				$succss = 0;
				foreach($c1['room_unique_id'] as $key=>$value)
				{
					$bed_unique_id = $c1['bed_unique_id'][$key];
					
					$cls_data = $class2->find()->where(['bed_unique_id'=>$bed_unique_id])->hydrate(false)->toArray();
					$assign_bed_data = $smgt_assign_bed_new->find()->where(['bed_unique_id'=>$bed_unique_id])->hydrate(false)->toArray();	
							
					if(!empty($assign_bed_data))
					{
						
						$ds_data = $smgt_assign_bed_new->get($assign_bed_data[0]['assign_id']);
						
						$ds_data2['room_unique_id']=$value;
						$ds_data2['bed_unique_id']=$bed_unique_id;
						$ds_data2['student_id']=$c1['student_id'][$key];
						$ds_data2['assign_date']=date("Y-m-d", strtotime($c1['assign_date'][$key]));
						$ds_data2['created_date']=date("Y-m-d");						
						$ds_data2['created_by']=$get_current_user_id;
						
						$item1 = $smgt_assign_bed_new->patchEntity($ds_data,$ds_data2);
						
						if($smgt_assign_bed_new->save($item1))
							$succss = 1;
						
						$student = $this->Setting->hostel_room_student_bed_unique_id($bed_unique_id);
						if($student['student_id'])
						{
							$bed_data = $class2->get($cls_data[0]['bed_id']);						
							$bed_data->bed_status = 1;								
							$class2->save($bed_data);
						}
					}
					else
					{
						$db_cl['room_unique_id']=$value;
						$db_cl['bed_unique_id']=$bed_unique_id;
						$db_cl['student_id']=$c1['student_id'][$key];
						$db_cl['assign_date']=date("Y-m-d", strtotime($c1['assign_date'][$key]));
						$db_cl['created_date']=date("Y-m-d");
						$db_cl['created_by']=$get_current_user_id;
					
						$a=$smgt_assign_bed_new->newEntity();
						$a=$smgt_assign_bed_new->patchEntity($a,$db_cl);
						if($smgt_assign_bed_new->save($a))
							$succss = 1;
					}
				}

				if($succss == 1)
				{
					$this->Flash->success(__('Hostel Room assign Successfully', null), 
								'default', 
								array('class' => 'success'));
				}
				return $this->redirect(['action'=>'roomlist']);
			}
		}
	
		public function assignRoomDelete($room_unique_id,$bed_unique_id,$student_id)
		{

			$class1 = TableRegistry::get('smgt_assign_bed_new');
			$class2 = TableRegistry::get('smgt_add_beds'); 
			
			$item = $class1->find()->where(['room_unique_id'=>$room_unique_id,
											'bed_unique_id'=>$bed_unique_id,
											'student_id'=>$student_id
											]);
			$indx = 0;
			
			$cls_data = $class2->find()->where(['bed_unique_id'=>$bed_unique_id]);	
			foreach($cls_data as $as_data)
			{
				$student = $this->Setting->hostel_room_student_bed_unique_id($bed_unique_id);
				if($student['student_id'])
				{
					$as_data['bed_status'] = 0;
					$class2->save($as_data);
				}
			}

			foreach($item as $data)
			{
				$ds_data = $class1->get($data['assign_id']);
		
				$ds_data2['student_id'] = 0;
				$ds_data2['assign_date'] = date("Y-m-d");
				
				$item1 = $class1->patchEntity($ds_data,$ds_data2);
				
				if($class1->save($item1))
					$indx = 1;
			}

			if($indx == 1)
			{
				$this->Flash->success(__('Hostel Room assign Deleted Successfully', null), 
								'default', 
								 array('class' => 'success'));
				
			}
			return $this->redirect(['action'=>'assignroom',$room_unique_id]);
		}
		
		public function addevent($id=0)
		{		
			$this->set('Comman','Event');
			
			$get_current_user_id=$this->request->session()->read('user_id');
			$sys_name = $this->Setting->getfieldname('school_name');
			$sys_email = $this->Setting->getfieldname('email');
			$current_sms_service=$this->Setting->getfieldname('select_serveice');
			$country = $this->Setting->getfieldname('country');
			$country_code = $this->Setting->get_country_code($country);	
			
			if($id)
			{
				$this->set('edit',true);
				$class = TableRegistry::get('smgt_event');
				$item = $class->get($id);
				$this->set('row',$item);
			}
			
			
			if($this->request->is('post'))
			{

				if($id)
				{
					$class = TableRegistry::get('smgt_event');
				
					$c1=$this->request->data;
					
					$db_cl = array();
									
					$db_cl['event_title']=$c1['event_title'];
					$db_cl['event_desc']=$c1['event_desc'];
					$db_cl['event_for']=$c1['event_for'];
					$db_cl['start_date'] = date("Y-m-d", strtotime($c1['start_date']));;
					$db_cl['end_date'] = date("Y-m-d", strtotime($c1['end_date']));;
					$db_cl['created_date']=date("Y-m-d");
					$db_cl['created_by']=$get_current_user_id;
					
					$event_title = $c1['event_title'];
					$event_comment = $c1['event_desc'];
					$event_start_date = date($this->stud_date, strtotime($c1['start_date']));
					$event_end_date = date($this->stud_date, strtotime($c1['end_date']));
					$event_for = $c1['event_for'];
					
					$item = $class->patchEntity($item,$db_cl);
					
					if($class->save($item))
					{
						$user_emails = $this->Setting->get_users_email_mno($c1['event_for']);
						
						foreach($user_emails as $to_email)
						{
							
							$sys_email=$this->Setting->getfieldname('email'); 
							$school_name = $this->Setting->getfieldname('school_name');

							$mailtem = TableRegistry::get('smgt_emailtemplate');
							$format =$mailtem->find()->where(["find_by"=>"Event"])->hydrate(false)->toArray();
							
							$str=$format[0]['template'];
							$subject=$format[0]['subject'];
							
							$msgarray = explode(" ",$str);
							$subarray = explode(" ",$subject);
							
							$email_id=$to_email['email'];

							$msgarray['{{event_title}}']=$event_title;
							$msgarray['{{event_start_date}}']=$event_start_date;
							$msgarray['{{event_end_date}}']=$event_end_date;
							$msgarray['{{event_for}}']=$event_for;
							$msgarray['{{event_comment}}']=$event_comment;
							$msgarray['{{school_name}}']=$school_name;
							
							$subarray['{{event_title}}']=$event_title;
							$subarray['{{event_start_date}}']=$event_start_date;
							$subarray['{{event_end_date}}']=$event_end_date;
							$subarray['{{event_for}}']=$event_for;
							$subarray['{{event_comment}}']=$event_comment;
							$subarray['{{school_name}}']=$school_name;

							$datamsg = str_replace(array_keys($msgarray),array_values($msgarray),$str);
							$submsg = str_replace(array_keys($subarray),array_values($subarray),$subject);
							
							if($email_id)
							{
								$email = new Email('default');
								$to = $email_id;									
								$message = $datamsg;

								$sys_name = $school_name;
								$sys_email = $sys_email;
								$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
								@mail($to,_($submsg),$message,$headers);

							}
							
							$reciever_number = "+".$country_code."".$to_email['mobile'];
			
							if($current_sms_service == 'clicktell')
							{	
								$to = $reciever_number;
								$message = str_replace(" ","%20",$message);
								
								$nm=$this->Setting->getfieldname($current_sms_service);						
								$service_data_decode=json_decode($nm,true);
			
								$username=$service_data_decode['username'];
								$password=$service_data_decode['password'];
								$api=$service_data_decode['api_key'];
								$sender_id=$service_data_decode['sender_id'];
									
								$baseurl ="http://api.clickatell.com";
										
								$url = "$baseurl/http/sendmsg?user=$username&password=$password&api_id=$api&to=$to&text=$message";
					
								$ret = file($url);

							}
							if($current_sms_service == 'twillo')
							{
										
								$receiver = $reciever_number;
								$message = str_replace(" ","%20",$message);
								
								$nm1=$this->Setting->getfieldname($current_sms_service);													
								$service_data_decode=json_decode($nm1,true);
										
								$account_sid = $service_data_decode['account_sid'];
								$auth_token = $service_data_decode['auth_token'];
								$from_number = $service_data_decode['from_number'];							
								
								$client = new Services_Twilio($account_sid, $auth_token);
								
								$message_sent = $client->account->messages->sendMessage(
									$from_number,
									$receiver,
									$message
								);
							
							}
						}
						
						$this->Flash->success(__('Event Updated Successfully', null), 
							'default', 
							 array('class' => 'success'));
						
					}
					return $this->redirect(['action'=>'eventlist']);						
				}
				else
				{
					
					$class2 = TableRegistry::get('smgt_event'); 			
					$a=$class2->newEntity();
				
					$c1=$this->request->data;
					
					$db_cl = array();
					
					$db_cl['event_title']=$c1['event_title'];
					$db_cl['event_desc']=$c1['event_desc'];
					$db_cl['event_for']=$c1['event_for'];
					$db_cl['start_date'] = date("Y-m-d", strtotime($c1['start_date']));;
					$db_cl['end_date'] = date("Y-m-d", strtotime($c1['end_date']));;
					$db_cl['created_date']=date("Y-m-d");
					$db_cl['created_by']=$get_current_user_id;
					
					$event_title = $c1['event_title'];
					$event_comment = $c1['event_desc'];
					$event_start_date = date($this->stud_date, strtotime($c1['start_date']));
					$event_end_date = date($this->stud_date, strtotime($c1['end_date']));
					$event_for = $c1['event_for'];
					
					$a=$class2->patchEntity($a,$db_cl);
					
					if($class2->save($a))
					{
						$user_emails = $this->Setting->get_users_email_mno($c1['event_for']);
						
						foreach($user_emails as $to_email)
						{
							$sys_email=$this->Setting->getfieldname('email'); 
							$school_name = $this->Setting->getfieldname('school_name');

							$mailtem = TableRegistry::get('smgt_emailtemplate');
							$format =$mailtem->find()->where(["find_by"=>"Event"])->hydrate(false)->toArray();
							
							$str=$format[0]['template'];
							$subject=$format[0]['subject'];
							
							$msgarray = explode(" ",$str);
							$subarray = explode(" ",$subject);
							
							$email_id=$to_email['email'];

							$msgarray['{{event_title}}']=$event_title;
							$msgarray['{{event_start_date}}']=$event_start_date;
							$msgarray['{{event_end_date}}']=$event_end_date;
							$msgarray['{{event_for}}']=$event_for;
							$msgarray['{{event_comment}}']=$event_comment;
							$msgarray['{{school_name}}']=$school_name;
							
							$subarray['{{event_title}}']=$event_title;
							$subarray['{{event_start_date}}']=$event_start_date;
							$subarray['{{event_end_date}}']=$event_end_date;
							$subarray['{{event_for}}']=$event_for;
							$subarray['{{event_comment}}']=$event_comment;
							$subarray['{{school_name}}']=$school_name;

							$datamsg = str_replace(array_keys($msgarray),array_values($msgarray),$str);
							$submsg = str_replace(array_keys($subarray),array_values($subarray),$subject);
							
							if($email_id)
							{
								$email = new Email('default');
								$to = $email_id;									
								$message = $datamsg;

								$sys_name = $school_name;
								$sys_email = $sys_email;
								$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
								@mail($to,_($submsg),$message,$headers);

							}
							
							$reciever_number = "+".$country_code."".$to_email['mobile'];
			
							if($current_sms_service == 'clicktell')
							{	
								$to = $reciever_number;
								$message = str_replace(" ","%20",$message);
								
								$nm=$this->Setting->getfieldname($current_sms_service);						
								$service_data_decode=json_decode($nm,true);
			
								$username=$service_data_decode['username'];
								$password=$service_data_decode['password'];
								$api=$service_data_decode['api_key'];
								$sender_id=$service_data_decode['sender_id'];
									
								$baseurl ="http://api.clickatell.com";
										
								$url = "$baseurl/http/sendmsg?user=$username&password=$password&api_id=$api&to=$to&text=$message";

								$ret = file($url);

							}
							if($current_sms_service == 'twillo')
							{
										
								$receiver = $reciever_number;
								$message = str_replace(" ","%20",$message);
								
								$nm1=$this->Setting->getfieldname($current_sms_service);													
								$service_data_decode=json_decode($nm1,true);
										
								$account_sid = $service_data_decode['account_sid'];
								$auth_token = $service_data_decode['auth_token'];
								$from_number = $service_data_decode['from_number'];
								
								$client = new Services_Twilio($account_sid, $auth_token);
								
								$message_sent = $client->account->messages->sendMessage(
									$from_number,
									$receiver,
									$message
								);
							
							}
						}
						
						$this->Flash->success(__('Event added Successfully', null), 
								'default', 
								 array('class' => 'success'));
					}
					return $this->redirect(['action'=>'eventlist']);
				}
			}
		}
	
		public function eventmultidelete() 
		{
			$this->autoRender = false;
			$id=json_decode($_REQUEST[n_id]);
			
			$i = 0;
			
			foreach($id as $recordid)
			{
				$class = TableRegistry::get('smgt_event');			
				$item =$class->get($recordid);
				if($class->delete($item))
				{
					$i = 1;
				}		
			}
			if($i == 1)
			{
				$this->Flash->success(__('Event Deleted Successfully', null), 
							'default', 
							 array('class' => 'success'));
			}
		}
	
		public function eventlist()
		{
			$this->set('Comman','Event');
			
			$class = TableRegistry::get('smgt_event');
			$query=$class->find()->order(['event_id'=>'DESC']);
			$this->set('it',$query);			
		}
		
	public function updatesubject($id)
	{
		$this->set('Subject','Subject');
		
		$class1 = TableRegistry::get('smgt_users');	
		$query1=$class1->find()->where(['role'=>'teacher']);			
		
		foreach($query1 as $it5)
		{
			$name=$it5['first_name']." ".$it5["last_name"];
			$b[$it5['user_id']]=$name;
		} 
		$this->set('it1',$b);
		
		$class_data = TableRegistry::get('Classmgt');			
		$cls = $class_data->find("list",["keyField"=>"class_id","valueField"=>"class_name"]);			
		$this->set('it2',$cls);
		
		$section_data = TableRegistry::get('class_section');
		$sect = $section_data->find("list",["keyField"=>"class_section_id","valueField"=>"section_name"]);
		$this->set('sect',$sect);			
	
		$class = TableRegistry::get('smgt_subject');
		$item = $class->get($id);
		
		$old_sub_code = $item->sub_code;
		
		if($this->request->is(['post','put']))
		{
			$sub_code=$this->request->data['sub_code'];
					
			if($old_sub_code == $sub_code)
			{
				$img=$this->request->data['syllabus']['name'];
				$img2=$this->request->data('file2');
				
				
				if(!$img)
				{
					$this->request->data['syllabus']['name']=$img2;
					unset($this->request->data['file2']);
					unset($this->request->data['syllabus']);
					$this->request->data['syllabus']=$img2;								
				}
				else
				{
					unset($this->request->data['file2']);
					unset($this->request->data['syllabus']);
					$this->request->data['syllabus']=$img;		
				}
		
				$item = $class->patchEntity($item,$this->request->data);
				if($class->save($item))
				{
					$this->Flash->success(__('Subject Updated Successfully', null), 
						'default', 
						 array('class' => 'success'));
					
				}
			}
			else
			{	
				$chk_code = $this->Setting->check_subject_data($sub_code);
				
				if(!$chk_code)
				{
					$img=$this->request->data['syllabus']['name'];
					$img2=$this->request->data('file2');
												
					if(!$img)
					{
						$this->request->data['syllabus']['name']=$img2;
						unset($this->request->data['file2']);
						unset($this->request->data['syllabus']);
						$this->request->data['syllabus']=$img2;								
					}
					else
					{
						unset($this->request->data['file2']);
						unset($this->request->data['syllabus']);
						$this->request->data['syllabus']=$img;		
					}					
					$item = $class->patchEntity($item,$this->request->data);
					
					if($class->save($item))
					{
						$this->Flash->success(__('Subject Updated Successfully', null), 
							'default', 
							 array('class' => 'success'));
						
					}
				}
				else
				{
					$this->Flash->error(__('Subject code already exists'),[ 
								'params' => [
									'class' => 'alert alert-error'
							]]);
				}
			}			
			return $this->redirect(['action'=>'subjectlist']);
		}
		$this->set('it',$item);
	}
	
	public function homeworklist()
    {
		$this->set('Homework','Homework');
		
		$get_current_user_id = $this->request->session()->read('user_id');
		
		$conn=ConnectionManager::get('default');	
		
		$role=$this->Setting->get_user_role($get_current_user_id);
		
		$class = TableRegistry::get('smgt_homework');
		
		if($role == 'teacher')
		{
			$class_list = array();
			
			$class_list = $this->Setting->get_user_class_list($get_current_user_id);
			// var_dump($class_list);die;
			$smgt_student_homework = TableRegistry::get('smgt_student_homework');
			
			if(!empty($class_list))
			{
				$smgt_student_homework_records = $class->find("list",["keyField"=>"homework_id","valueField"=>"homework_id"])->where(['class_id IN'=>$class_list])->hydrate(false)->toArray();
				$this->set('homework_data',$smgt_student_homework_records);
			}
			
			if(isset($_POST['filter_class']))
			{
				$data = $this->request->data();
				$homework_id = $data['homework'];
				
				if($homework_id != 'Select Homework')
					$this->set('homework_id',$homework_id);
				
				$query = $class->find()->where(['homework_id'=>$homework_id]);
				
				if(!empty($query))
					$this->set('homework_filter',$query);

			}
			
			if(!empty($class_list))
				$query=$class->find()->where(['class_id IN'=>$class_list])->order(['homework_id'=>'DESC'])->hydrate(false)->toArray();
			// $query=$class->find()->order(['homework_id'=>'DESC'])->hydrate(false)->toArray();
		}
		elseif($role == 'parent')
		{
			$query = array();
			$childs = array();
			$childs = $this->Setting->get_child_id($get_current_user_id);
			// debug($childs);die;
			if(!empty($childs)):
				foreach($childs as $user):
					$query[] = $this->Setting->get_user_homework($user);					
				endforeach;
			endif;
			
			/* $class_list = $this->Setting->get_parents_student_id($get_current_user_id);
			
			if(!empty($class_list))
				$query=$class->find()->where(['class_id IN'=>$class_list])->order(['homework_id'=>'DESC'])->hydrate(false)->toArray(); */
		}
		else
		{
			$query = $conn->execute("SELECT * FROM smgt_student_homework as stud_mark 
			LEFT JOIN smgt_homework as mark on stud_mark.homework_id = mark.homework_id
			WHERE stud_mark.student_id =".$get_current_user_id)->fetchAll('assoc');
		}
		
		
		if(!empty($query))
			$this->set('it',$query);
    }	
	
	public function studaddsubmission($id=0)
    {
		$class2 = TableRegistry::get('Classmgt');			
		$query2 = $class2->find("list",["keyField"=>"class_id","valueField"=>"class_name"]);			
		$this->set('class_id',$query2);
		
		$section = TableRegistry::get('class_section');			
		$section_id = $section->find();			
		$this->set('section_id',$section_id);
		
		$class3=TableRegistry::get('smgt_subject');
		$query3=$class3->find();
		
		$get_current_user_id = $this->request->session()->read('user_id');
		
		$smgt_homework = TableRegistry::get('smgt_homework'); 
		$smgt_student_homework = TableRegistry::get('smgt_student_homework');
				
		
		if($id)
		{
			$this->set('edit',true);
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $smgt_homework->exists(['homework_id' => $id]);
			
			if($exists)
			{
				$item = $smgt_homework->get($id);
				$this->set('row',$item);	
				
				$student_homework_data = $smgt_student_homework->find()->where(['homework_id'=>$id,'student_id'=>$get_current_user_id])->hydrate(false)->toArray();
				if(!empty($student_homework_data))
				{
					$status = $student_homework_data[0]['status'];	
					$this->set('status',$status);
				}
				
				if($this->request->is('post'))
				{
					if($this->request->data['submission'])
					{
						$img = $this->request->data['submission'];
						$u = "submission";
						$fp = WWW_ROOT.$u;

						$imgname = $img['name'];
						$fpp = $fp.'/'.$imgname;

						if(move_uploaded_file($img['tmp_name'],$fpp))
						{
						}
					}	
					
					$c1=$this->request->data;
					
					if(!empty($student_homework_data))
					{
						foreach($student_homework_data as $stud_data)
						{
							$stud_homework_id = $stud_data['stu_homework_id'];
							$stud_data = $smgt_student_homework->get($stud_homework_id);

							$c1['uploaded_date'] = date("Y-m-d");
							$c1['file'] = $this->request->data['submission']['name'];
							
							$current_date = date("Y-m-d");
							$smgt_homeworkitem = $smgt_homework->get($id);
							$submissiondate = date('Y-m-d',strtotime($smgt_homeworkitem->submission_date));
							
							if($submissiondate < $current_date)
								$c1['status'] = 2;
							else
								$c1['status'] = 1;
							
							$student_homework_save = $smgt_student_homework->patchEntity($stud_data,$c1);
							
							if($smgt_student_homework->save($student_homework_save))
							{
								$this->Flash->success(__('Submission Successfully Uploaded', null),
										'default',
									   array('class' => 'success'));
									   
								return $this->redirect(['action'=>'homeworklist']);
							}
						}				
					}
				}
			}
			else
				return $this->redirect(['action'=>'homeworklist']);
		}
	}
	
	public function addhomework($id=0)
    {	
		$class2 = TableRegistry::get('Classmgt');			
		$query2 = $class2->find("list",["keyField"=>"class_id","valueField"=>"class_name"]);			
		$this->set('class_id',$query2);
		
		$section = TableRegistry::get('class_section');			
		$section_id = $section->find();			
		$this->set('section_id',$section_id);
		
		$class3=TableRegistry::get('smgt_subject');
		$query3=$class3->find();
		
		$this->set('Homework','Homework');
		$get_current_user_id=$this->request->session()->read('user_id');
		
		$smgt_homework = TableRegistry::get('smgt_homework'); 
		$smgt_student_homework = TableRegistry::get('smgt_student_homework'); 
		
		if($id)
		{
			$this->set('edit',true);
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $smgt_homework->exists(['homework_id' => $id]);
			
			if($exists)
			{
				$item = $smgt_homework->get($id);
				$this->set('row',$item);
			}
			else
				return $this->redirect(['action'=>'homeworklist']);
		}
			
		if($this->request->is('post'))
		{	
			if($id)
			{
				$i = 0;
				$mail = 0;
				
				$c1 = $this->request->data;
				
				$item = $smgt_homework->get($id);
				
				$db_cl = array();
				
				$img=$this->request->data['syllabus']['name'];
				$img2=$this->request->data('file2');
				
				
				if(!$img)
				{
					$this->request->data['syllabus']['name']=$img2;
					unset($this->request->data['file2']);
					unset($this->request->data['syllabus']);
					$db_cl['syllabus']=$img2;								
				}
				else
				{
					if($this->request->data['syllabus'])
					{
						$img1=$this->request->data['syllabus'];
						$u="syllabus";
						$fp=WWW_ROOT.$u;	

						$imgname=$img1['name'];

						$fpp=$fp.'/'.$imgname;

						if(move_uploaded_file($img1['tmp_name'],$fpp))
						{
					
						}
					}
					
					unset($this->request->data['file2']);
					unset($this->request->data['syllabus']);
					
					$db_cl['syllabus']=$img;		
				}
				
				$db_cl['title'] = $c1['title'];
				$db_cl['class_id'] = $c1['class_id'];
				$db_cl['section_id'] = $c1['section'];
				$db_cl['subject_id'] = $c1['sub_id'];
				$db_cl['content'] = $c1['homework_content'];
				$db_cl['submission_date'] = date("Y-m-d", strtotime($c1['submission_date']));
				$db_cl['created_date'] = date("Y-m-d");
				$db_cl['created_by'] = $get_current_user_id;
				
				$homework_patchEntity = $smgt_homework->patchEntity($item,$db_cl);

				if($smgt_homework->save($homework_patchEntity))
				{
					$homework_id = $homework_patchEntity['homework_id'];
					
					$exitdata = array();
					$exitdata = $smgt_student_homework->find()->where(['homework_id'=>$homework_id]);
					
					if(!empty($exitdata))
					{
						foreach($exitdata as $exit_data)
						{
							$stu_homework_id = $exit_data['stu_homework_id'];
							$del_stu_homework_id = $smgt_student_homework->get($stu_homework_id);
							$smgt_student_homework->delete($del_stu_homework_id);
						}
					}
					
					$userids = array();
					$userids = $this->Setting->get_user_class_section('student',$homework_patchEntity['class_id'],$homework_patchEntity['section_id']);
					
					foreach($userids as $user_id)
					{
						$db_cl2 = array();
						
						$db_cl2['homework_id'] = $homework_id;
						$db_cl2['student_id'] = $user_id;
						$db_cl2['status'] = 0;
						
						$stud_homework_newEntity = $smgt_student_homework->newEntity();
						$stud_homework_patchEntity = $smgt_student_homework->patchEntity($stud_homework_newEntity,$db_cl2);
						
						if($smgt_student_homework->save($stud_homework_patchEntity))
						{
							if($mail == 1)
							{
								$parents_id = $this->Setting->get_student_parents_id($user_id);
								if(!empty($parents_id))
								{
									foreach($parents_id as $parent_id)
									{
										$current_date = date('Y-m-d');
										$subject = 'Assign Homework';
										$message_content = "Assign Homework to your child ".$this->Setting->get_user_id($user_id)." on ".$current_date.".";
										
										$emial_to = $this->Setting->get_user_email_id($parent_id);
										
										$sys_email=$this->Setting->getfieldname('email'); 
										$school_name = $this->Setting->getfieldname('school_name');
										
										$email = new Email('default');
										$to = $emial_to;									
										$message = $message_content;
										
										/* $email->from([$sys_email => $school_name])
										 ->to($to)
										->subject( _($subject))
										->send($message); */
										
										$sys_name = $school_name;
										$sys_email = $sys_email;
										$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
										@mail($to,_($subject),$message,$headers);										
									}
								}
							}
							$i = 2;
						}
					}					
				}
			}
			else
			{
				$i = 0;
				$mail = 0;
				
				$c1 = $this->request->data;
				
				$db_cl = array();
				
				if($this->request->data['syllabus'])
				{
					$img=$this->request->data['syllabus'];
					$u="syllabus";
					$fp=WWW_ROOT.$u;	

					$imgname=$img['name'];

					$fpp=$fp.'/'.$imgname;

					if(move_uploaded_file($img['tmp_name'],$fpp))
					{
				
					}
				}
				
				$db_cl['title'] = $c1['title'];
				$db_cl['class_id'] = $c1['class_id'];
				$db_cl['section_id'] = $c1['section'];
				$db_cl['subject_id'] = $c1['sub_id'];
				$db_cl['content'] = $c1['homework_content'];
				$db_cl['syllabus'] = $c1['syllabus']['name'];
				$db_cl['submission_date'] = date("Y-m-d", strtotime($c1['submission_date']));
				$db_cl['created_date'] = date("Y-m-d");
				$db_cl['created_by'] = $get_current_user_id;
				
				if(isset($c1['parent_mail']))
					$mail = 1;
				
				$homework_newEntity = $smgt_homework->newEntity();
				$homework_patchEntity = $smgt_homework->patchEntity($homework_newEntity,$db_cl);

				if($smgt_homework->save($homework_patchEntity))
				{
					$userids = array();
					$userids = $this->Setting->get_user_class_section('student',$homework_patchEntity['class_id'],$homework_patchEntity['section_id']);
					
					foreach($userids as $user_id)
					{
						$db_cl2 = array();
						
						$homework_id = $homework_patchEntity['homework_id'];
						
						$db_cl2['homework_id'] = $homework_id;
						$db_cl2['student_id'] = $user_id;
						$db_cl2['status'] = 0;
						
						$stud_homework_newEntity = $smgt_student_homework->newEntity();
						$stud_homework_patchEntity = $smgt_student_homework->patchEntity($stud_homework_newEntity,$db_cl2);
						
						if($smgt_student_homework->save($stud_homework_patchEntity))
						{
							if($mail == 1)
							{
								$parents_id = $this->Setting->get_student_parents_id($user_id);
								if(!empty($parents_id))
								{
									foreach($parents_id as $parent_id)
									{
										$current_date = date('Y-m-d');
										$subject = 'Assign Homework';
										$message_content = "Assign Homework to your child ".$this->Setting->get_user_id($user_id)." on ".$current_date.".";
										
										$emial_to = $this->Setting->get_user_email_id($parent_id);
										
										$sys_email=$this->Setting->getfieldname('email'); 
										$school_name = $this->Setting->getfieldname('school_name');
										
										$email = new Email('default');
										$to = $emial_to;									
										$message = $message_content;
										
										/* $email->from([$sys_email => $school_name])
										 ->to($to)
										->subject( _($subject))
										->send($message); */
										
										$sys_name = $school_name;
										$sys_email = $sys_email;
										$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
										@mail($to,_($subject),$message,$headers);										
									}
								}
							}						
							$i = 1;
						}
					}					
				}
			}
			if($i == 1)
			{
				$this->Flash->success(__('Homework Added Successfully', null), 
							'default', 
							array('class' => 'success'));
			}
			if($i == 2)
			{
				$this->Flash->success(__('Homework Edited Successfully', null), 
							'default', 
							array('class' => 'success'));
			}
			return $this->redirect(['action'=>'homeworklist']);
		}
    }
	
	public function viewsubmission($id=0)
    {
		$this->set('Homework','Homework');
		
		$smgt_student_homework = TableRegistry::get('smgt_student_homework');
		
		if($id)
		{
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$this->set('id',$id);
			
			$query = $smgt_student_homework->find()->where(['homework_id'=>$id]);
		
			if(!empty($query))
				$this->set('it',$query);
		}
	}
	
	public function homeworkdelete($id=0)
	{
		$smgt_homework = TableRegistry::get('smgt_homework');
		$smgt_student_homework = TableRegistry::get('smgt_student_homework'); 

		if($id)
		{
			$item =$smgt_homework->get($id);

			if($smgt_homework->delete($item))
			{			
				$exitdata = array();
				$exitdata = $smgt_student_homework->find()->where(['homework_id'=>$id]);
				
				if(!empty($exitdata))
				{
					foreach($exitdata as $exit_data)
					{
						$stu_homework_id = $exit_data['stu_homework_id'];
						$del_stu_homework_id = $smgt_student_homework->get($stu_homework_id);
						$smgt_student_homework->delete($del_stu_homework_id);
					}
				}
				
				$this->Flash->success(__('Homework Deleted Successfully', null), 
								'default', 
								 array('class' => 'success'));
				
			}
			return $this->redirect(['action'=>'homeworklist']);
		}
	}
	public function teacherroutelist()
    {
		$this->set('Classroute','Classroute');
		
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
		$this->set('class_route',$class_route);
		
		$xyz=$this->Setting->sgmt_day_list();
		$class_list=$this->Setting->get_class_list();
		$teachername=$this->Setting->get_user_list();
		
		$this->set('daywk',$xyz);
		$this->set('class_list',$class_list);
		$this->set('teacher_name',$teachername);	
    }
	public function addexam()
	{
		$table_class=TableRegistry::get('classmgt');
		$class=$table_class->find();
		$this->set('class_data',$class);
		
		$tbl_term=TableRegistry::get('tbl_term');
		$term_data=$tbl_term->find()->where(['term_status'=>0]);
		$this->set('term_data',$term_data);
		
		$exam_table_register=TableRegistry::get('smgt_exam');
		$exam_table_entity=$exam_table_register->newEntity();

		if($this->request->is('post'))
		{
			$data=$this->request->data;
			
			$db_cl = array();
			
			if($this->request->data['syllabus'])
			{
				$img=$this->request->data['syllabus'];
				$u="syllabus";
				$fp=WWW_ROOT.$u;	

				$imgname=$img['name'];

				$fpp=$fp.'/'.$imgname;

				if(move_uploaded_file($img['tmp_name'],$fpp))
				{
			
				}
			}
			
			$db_cl['exam_name']=$data['exam_name'];	
			$db_cl['class_id']=$data['class_name'];																																		
			$db_cl['section_id']=$data['section'];	
			$db_cl['term_id']=$data['term_id'];		
			$db_cl['pass_mark']=$data['pass_mark'];																																																																							
			$db_cl['total_mark']=$data['total_mark'];	
			$db_cl['exam_date']=date("Y-m-d", strtotime($data['exam_date']));
			$db_cl['exam_end_date']=date("Y-m-d", strtotime($data['exam_end_date']));
			$db_cl['exam_comment']=$data['exam_comment'];
			$db_cl['syllabus'] = $data['syllabus']['name'];
			$db_cl['created_date']=date("Y-m-d");
			$db_cl['modified_date']=date("Y-m-d");
			
			$course=$exam_table_register->patchEntity($exam_table_entity,$db_cl);
			
			if($exam_table_register->save($course))
			{
				$this->Flash->success(__(' Exam added Successfully', null), 
						'default', 
						 array('class' => 'success'));

			}
			return $this->redirect(['action'=>'examlist']);
		}
	}
	public function updateexam($id)	
	{
		$this->set('edit',true);
		
		$table_class=TableRegistry::get('classmgt');
		$class=$table_class->find();
		$this->set('class_data',$class);
		
		$tbl_term=TableRegistry::get('tbl_term');
		$term_data=$tbl_term->find()->where(['term_status'=>0]);
		$this->set('term_data',$term_data);
		
		if($id)
		{
			
			$Table_Registry=TableRegistry::get('smgt_exam');
			
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $Table_Registry->exists(['exam_id' => $id]);
			
			if($exists)
			{
				$Get_Item=$Table_Registry->get($id);
				
				$this->set('edit',true);	
				
				if($this->request->is('post'))
				{
					$data=$this->request->data;
					
					$db_cl = array();
					
					$img=$this->request->data['syllabus']['name'];
					$img2=$this->request->data('file2');
					
					
					if(!$img)
					{
						$this->request->data['syllabus']['name']=$img2;
						unset($this->request->data['file2']);
						unset($this->request->data['syllabus']);
						$db_cl['syllabus']=$img2;								
					}
					else
					{
						if($this->request->data['syllabus'])
						{
							$img1=$this->request->data['syllabus'];
							$u="syllabus";
							$fp=WWW_ROOT.$u;	

							$imgname=$img1['name'];

							$fpp=$fp.'/'.$imgname;

							if(move_uploaded_file($img1['tmp_name'],$fpp))
							{
						
							}
						}
						
						unset($this->request->data['file2']);
						unset($this->request->data['syllabus']);
						
						$db_cl['syllabus']=$img;		
					}
					
					$db_cl['exam_name']=$data['exam_name'];
					$db_cl['class_id']=$data['class_name'];																																		
					$db_cl['section_id']=$data['section'];	
					$db_cl['term_id']=$data['term_id'];
					$db_cl['pass_mark']=$data['pass_mark'];																																																																							
					$db_cl['total_mark']=$data['total_mark'];	
					$db_cl['exam_date']=date("Y-m-d", strtotime($data['exam_date']));
					$db_cl['exam_end_date']=date("Y-m-d", strtotime($data['exam_end_date']));
					$db_cl['exam_comment']=$data['exam_comment'];
					if(isset($data['syllabus']['name'])){
					$db_cl['syllabus'] = $data['syllabus']['name'];}
					$db_cl['created_date']=date("Y-m-d");
					$db_cl['modified_date']=date("Y-m-d");
			
					$Get_Item=$Table_Registry->patchEntity($Get_Item,$db_cl);
					if($Table_Registry->save($Get_Item))
					{
						$this->Flash->success(__('Exam Updated Successfully', null), 
							'default', 
							 array('class' => 'alert alert-success'));
							
							return $this->redirect(['controller'=>'Comman','action'=>'examlist']);
					}
					else
					{
						echo 'Some Error in Update Page';
					}
				}
				$this->set('row',$Get_Item);
			}
			else
				return $this->redirect(['controller'=>'Comman','action'=>'examlist']);
		}
	}
	public function termDelete($id = null)
	{
		$this->autoRender = false;
		if($this->request->is('ajax'))
		{
			$term_id=$_POST['term_id'];
			$cat = TableRegistry::get('tbl_term');
			$items=$cat->get($term_id);
			$items->term_status = 1;
			if($cat->save($items))
			{
				$this->Flash->success(__('Term Deleted Successfully', null), 
										'default', 
										array('class' => 'success'));	
			}
		}
	}	
	public function exammultidelete() 
	{
		$this->autoRender = false;
		$id=json_decode($_REQUEST[e_id]);
		foreach($id as $recordid)
			{
					$class = TableRegistry::get('smgt_exam');
					
					$item =$class->get($recordid);

					if($class->delete($item))
					{
						
					}
					
			}
	}
	public function examadddata($id = null) 
	{
		$get_current_user_id=$this->request->session()->read('user_id');
		
		$this->autoRender = false;
        if($this->request->is('ajax'))
		{
			if(!empty($_POST['term_name']))
			{
				$cls = $_POST['term_name'];
				
				$cat = TableRegistry::get('tbl_term');
				$a = $cat->newEntity();

				$a['term_name']=$cls;
				$a['created_by']=$get_current_user_id;
				
				if($cat->save($a))
				{
					$i=$a['term_id'];
				}
				echo $i;
			}
			else
				echo "false";
            die();
       }
	}
	public function examdelete($id)
	{
		$class = TableRegistry::get('smgt_exam');
		$this->request->is(['post','delete']);
		$item = $class->get($id);
		
		if($class->delete($item))
		{			
			$this->Flash->success(__('Exam Deleted Successfully', null), 
					'default', 
					 array('class' => 'success'));	
		}
		return $this->redirect(['action'=>'examlist']);
	}
	public function addparent()
	{
		$this->set('Parent','Parent');
			
		$class = TableRegistry::get('Classmgt');
		
		$query=$class->find();
		$this->set('it',$query);
		
		$class2 = TableRegistry::get('Smgt_users');
		
		$query2=$class2->find()->where(['role'=>'student']);
		$this->set('child',$query2);
		
		$country=$this->Setting->getfieldname('country');
		
		$country_code=$this->Setting->get_country_code($country);
		$this->set('country_code',$country_code);
		
		if($this->request->is('post'))
		{		
			
				$i = 0;
				$email=$this->request->data['email'];
				$username=$this->request->data['username'];	
					
				$check_email = $class2->find()->where(['email'=>$email]);					
				$check_user = $class2->find()->where(['username'=>$username]);	
				
					if(!$check_email->isEmpty())
					{
						$this->Flash->success(__('Duplicate Email id'));
						$this->set('post_data',$this->request->data);
						
					}	
					if(!$check_user->isEmpty())
					{
						$this->Flash->success(__('Duplicate Username'));
						$this->set('post_data',$this->request->data);
							
					}			
					if (!$check_email->isEmpty() || !$check_user->isEmpty() ) 
					{
						$this->set('post_data',$this->request->data);
							
					}
					else
					{
						$a = $class2->newEntity();

						$c1=$this->request->data;
						
						$hasher = new DefaultPasswordHasher();
						$password=$c1['password'];
						$pass = $hasher->hash($c1['password']);
						$c1['password']=$pass;
						
						$c1['classname']=null;
						$c1['roll_no']=null;
						$c1['alternate_mobile_no']=null;
						$c1['working_hour']=null;
						$c1['position']=null;
						$c1['submitted_document']=null;
						$c1['role']='parent';
						$c1['status']=null;
						$c1['date_of_birth']=date("Y-m-d", strtotime($c1['date_of_birth']));
						
						$image=$this->Setting->getimage($c1['image']);
						
						if($image=='')
						{
							$c1['image']="profile.jpg";
						}
						else
						{
							$c1['image']=$this->request->data('image');
							$c1['image']=$c1['image']['name'];
						}	
								
						$a=$class2->patchEntity($a,$c1);
					
						if($class2->save($a))
						{
							$username=$a['username'];							
							$role=$a['role'];
							$this->Flash->success(__('Parent Registered Successfully', null), 
									'default', 
									 array('class' => 'success'));
									 
									 
							$sys_email=$this->Setting->getfieldname('email'); 
							$school_name = $this->Setting->getfieldname('school_name');
							$get_username=$this->Et->get_username1($username);
							$get_password=$this->Et->get_password($password);
							$get_role=$this->Et->get_role($role);
							
							$subject="";
							$mailtem = TableRegistry::get('smgt_emailtemplate');
							$format =$mailtem->find()->where(["find_by"=>"Add_User"])->hydrate(false)->toArray();
							$str=$format[0]['template'];
							$subject=$format[0]['subject'];
							
							$msgarray = explode(" ",$str);
							$subarray = explode(" ",$subject);
							
							$loginlink= "http://" . $_SERVER['SERVER_NAME'].$this->request->base;
							$email_id=$c1['email'];

							$msgarray['{{user_name}}']=$get_username;
							$msgarray['{{username}}']=$get_username;
							$msgarray['{{school_name}}']=$school_name;
							$msgarray['{{role}}']=$get_role;
							$msgarray['{{login_link}}']=$loginlink;
							$msgarray['{{Password}}']=$password;

							$subarray['{{user_name}}']=$get_username;
							$subarray['{{username}}']=$get_username;
							$subarray['{{school_name}}']=$school_name;
							$subarray['{{role}}']=$get_role;
							$subarray['{{login_link}}']=$loginlink;
							$subarray['{{Password}}']=$password;

							$datamsg = str_replace(array_keys($msgarray),array_values($msgarray),$str);
							$submsg = str_replace(array_keys($subarray),array_values($subarray),$subject);


							if($email_id != '')
							{
								$email = new Email('default');
								$to = $email_id;
								$message = $datamsg;
								
								/* $email->from([$sys_email => $school_name])
								 ->to($to)
								->subject( _($submsg))
								->send($message); */
								
								$sys_name = $school_name;
								$sys_email = $sys_email;
								$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
								@mail($to,_($submsg),$message,$headers);

							}
						}
				
						$query3=$class2->find()->where(['role'=>'parent']);
						foreach($query3 as $p_id)
						{
							$parent_id=$p_id['user_id'];
							
						}
						
						$ch=array();
						$ch=$c1['child_id'];
						
						foreach($ch as $chd)
						{
							$user_id=$this->request->session()->read('user_id');
							
							$class3 = TableRegistry::get('child_tbl');
						
							$a3 = $class3->newEntity();
							
							$c1=$this->request->data();
							
							$c1['child_parent_id']=$parent_id;
							$c1['created_by']=$user_id;
							$c1['child_id']=$chd;
							$c1['created_date']=Time::now();;
							$c1['status']=null;
							
							
							$a3=$class3->patchEntity($a3,$c1);
							
							if($class3->save($a3))
							{
								$i=1;
							}
							
						}
						if($i == 1)
						{
							$this->Flash->success(__('Child Registered Successfully', null), 
									'default', 
									 array('class' => 'success'));
						

								
					}
					}
					
					return $this->redirect(['action'=>'parentlist']);
		}
	}
	public function parentlist()
	{
		$this->set('Parent','Parent');
		$childs = array();
		
		$get_current_user_id = $this->request->session()->read('user_id');			
		$role=$this->Setting->get_user_role($get_current_user_id);
		
		$class = TableRegistry::get('Smgt_users');
		
		if($role == 'student')
		{
			$childs = $this->Setting->get_student_parents_id($get_current_user_id);
			if(!empty($childs))
			{
				$query=$class->find()->where(['user_id IN'=>$childs])->hydrate(false)->toArray();
			
				if(!empty($query))
					$this->set('it',$query);
			}
		}
		else
		{
			$query=$class->find()->where(['role'=>'parent'])->hydrate(false)->toArray();
			if(!empty($query))
				$this->set('it',$query);
		}		
	}
	public function updateparent($id=0)
	{
		if($id)
		{
			$this->set('Parent','Parent');
			
			$class_data = TableRegistry::get('Classmgt');
			
			$cls = $class_data->find("list",["keyField"=>"class_id","valueField"=>"class_name"]);
			
			$this->set('cls',$cls);
		
			$country=$this->Setting->getfieldname('country');
		
			$country_code=$this->Setting->get_country_code($country);
			$this->set('country_code',$country_code);
			
			$class1 = TableRegistry::get('Smgt_users');
			$class3 = TableRegistry::get('child_tbl');
			
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $class1->exists(['user_id' => $id]);
			
			if($exists)
			{
				$item = $class1->get($id);
				
				$id=$item['user_id'];

				$query3=$class3->find()->where(['child_parent_id'=>$id]);

				$a = array();
				
				foreach($query3 as $tbl_ch)
				{
						$a[]=$tbl_ch['child_id'];
				}
				$this->set('parent_child_id',$a);			
				
				if($this->request->is(['post','put']))
				{		
					$query3=$class3->find()->where(['child_parent_id'=>$id]);
					
					foreach($query3 as $tbl_ch)
					{
						$a=$tbl_ch['child_tbl_id'];
						$entity = $class3->get($a); 
						$result = $class3->delete($entity);
					}			
					
					$img2=$this->request->data();
					
					$xyz1=$this->Setting->getimage($img2['image']);
			
					$old_value = $this->request->data('image2');
					$img2['image']=$old_value;
					
					if($xyz1!='')
					{
						$img2['image']=$xyz1;
					}
						
					$ch=array();
					$ch=$img2['child_id'];

					foreach($ch as $chd)
					{
						$user_id=$this->request->session()->read('user_id');
						
						$class3 = TableRegistry::get('child_tbl');
					
						$a3 = $class3->newEntity();
						
						$c1=$this->request->data();
						
						$c1['child_parent_id']=$id;
						$c1['created_by']=$user_id;
						$c1['child_id']=$chd;
						$c1['created_date']=Time::now();;
						$c1['status']=null;
						
						$a3=$class3->patchEntity($a3,$c1);
						
						if($class3->save($a3))
						{
							
						}				
					}
					
					$img2['date_of_birth']=date("Y-m-d", strtotime($img2['date_of_birth']));	
						
					$item = $class1->patchEntity($item,$img2);
					
					if($class1->save($item))
					{
						$this->Flash->success(__('Parent Record Updated Successfully', null), 
							'default', 
							 array('class' => 'success'));
						
					}
					return $this->redirect(['action'=>'parentlist']);
				}
				$this->set('it',$item);
				
				$query1 = $class1->find()->where(['role'=>'student']);
				$this->set('ch',$query1);
			}
			else
				return $this->redirect(['action'=>'parentlist']);
		}
		else
			return $this->redirect(['action'=>'parentlist']);
	}
	public function parentchild($id = null)
	{
		$this->autoRender=false;
		if($this->request->is('ajax'))
		{
			$id=$_POST['id'];
		
			$class = TableRegistry::get('Smgt_users');
			$class1 = TableRegistry::get('child_tbl');
			
			$u_id=$class->get($id);
			
			$user_id=$u_id['user_id'];
			
			$name=$u_id['first_name']." ".$u_id['last_name'];
			
			$child_list=$class1->find()->where(['child_parent_id'=>$user_id]);
			
			?>
			<div class="panel panel-white">
				<div class="panel-heading">
					<h4 class="panel-title"><?php echo $name;?></h4>
				</div>
			</div>		
			<div class="clearfix"></div>
			<div class="main-tog">
				<div class="panel-body view_result">
					<div class="table-responsive">
						<table class="table table-bordered">
							<thead>	
								<tr>
									<th><?php echo __('Photo')?></th>
									<th><?php echo __('Student ID')?></th>
									<th><?php echo __('Name')?></th>
									<th><?php echo __('Class')?></th>
								</tr>
							</thead>
							<tfoot>
							</tfoot>
							<tbody>
							<?php
							if(!$child_list->isEmpty())
							{
								
								foreach($child_list as $list_ch)
								{
									$ch_id=$list_ch['child_id'];
									
									$name=$this->Setting->get_user_id($ch_id);
									$photo=$this->Setting->get_user_image($ch_id);
									$class_id=$this->Setting->get_user_class($ch_id);
									$class=$this->Setting->get_class_id($class_id);
									$studentID = $this->Setting->get_studentID($ch_id);
								?>
								<tr>
									<td><image src="../img/<?php echo $photo; ?>" alt="none" height='50px' width='50px' class='profileimg'/></td>
									<td><?php echo $studentID;?></td>
									<td><?php echo $name;?></td>
									<td><?php echo $class;?></td>			
								</tr>
								
							<?php }
							}else
							{
							echo "<b><p style='color:red;'>child Not Available</p></b>";}
							?>
							</tbody>
						</table>
					</div>
				</div>	
			</div>		
		<?php
		}
	}
	public function updateteacher($id)
	{		
		if($id)
		{
			$this->set('Teacher','Teacher');
			
			$class_data = TableRegistry::get('Classmgt');		
			$cls = $class_data->find();		
			$this->set('cls',$cls);

			$class1 = TableRegistry::get('Smgt_users');
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $class1->exists(['user_id' => $id]);
			
			if($exists)
			{
				$item = $class1->get($id);
				
				$country=$this->Setting->getfieldname('country');
			
				$country_code=$this->Setting->get_country_code($country);
				$this->set('country_code',$country_code);

				if($this->request->is(['post','put']))
				{
					$img2=$this->request->data();
														
					$xyz1=$this->Setting->getimage($img2['image']);
					$img2['classname']=implode(',',$this->request->data('classname'));
					
					$old_value = $this->request->data('image2');
					$img2['image']=$old_value;
					
					$old_value1 = $this->request->data('docume');
					$img2['docume']=$old_value1;
					
					$img2['submitted_document']=$this->request->data(['submitted_document']);
					if(!empty($img2['submitted_document']))
						$img2['submitted_document']=implode(',',$img2['submitted_document']);
					
					if($xyz1!='')
					{
						$img2['image']=$xyz1;
					}
					$img2['date_of_birth']=date("Y-m-d", strtotime($img2['date_of_birth']));
					
					$item = $class1->patchEntity($item,$img2);
				
					if($class1->save($item))
					{
						$this->Flash->success(__('Teacher Record Updated Successfully', null), 
							'default', 
							 array('class' => 'success'));
					}
					return $this->redirect(['action'=>'teacherlist']);
				}
				$this->set('it',$item);
			}
			else
				return $this->redirect(['action'=>'teacherlist']);
		}
		else
			return $this->redirect(['action'=>'teacherlist']);
	}
	public function paymentdelete($id){

			$payment_table_register=TableRegistry::get('smgt_payment');
			$this->request->is(['post','delete']);

			$item=$payment_table_register->get($id);
			if($payment_table_register->delete($item))
			{
				 $this->Flash->success(__('Payment Deleted Successfully', null), 
									'default', 
									 array('class' => 'success'));
									 
				return $this->redirect(['action'=>'paymentlist']);
			}

	}
	public function updatestaff($id)
	{
		if($id)
		{
			$this->set('Staff','Staff');
			
			$class = TableRegistry::get('Smgt_users');
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $class->exists(['user_id' => $id]);
			
			if($exists)
			{
				$item = $class->get($id);
				
				$country=$this->Setting->getfieldname('country');
			
				$country_code=$this->Setting->get_country_code($country);
				$this->set('country_code',$country_code);

				if($this->request->is(['post','put']))
				{
						$img2=$this->request->data();
						
						$hasher = new DefaultPasswordHasher();
						$pass = $hasher->hash($img2['password']);
						$img2['password']=$pass;
						
						$xyz1=$this->Setting->getimage($img2['image']);
				
						$old_value = $this->request->data('image2');
						$img2['image']=$old_value;
						
						if($xyz1!='')
						{
							$img2['image']=$xyz1;
						}
						$img2['date_of_birth']=date("Y-m-d", strtotime($img2['date_of_birth']));
						
						$item = $class->patchEntity($item,$img2);
					
						if($class->save($item))
						{
							$this->Flash->success(__('Staff Record Updated Successfully', null), 
								'default', 
								 array('class' => 'success'));
							
						}
						return $this->redirect(['action'=>'stafflist']);
				}
				$this->set('it',$item);
			}
			else
				return $this->redirect(['action'=>'stafflist']);
		}
		else
			return $this->redirect(['action'=>'stafflist']);
	}
	public function stafflist()
	{
		$this->set('Staff','Staff');
		
		$class = TableRegistry::get('Smgt_users');
		
		$query=$class->find()->where(['role'=>'supportstaff']);
		$this->set('it',$query);
	}
	public function studentexamlist($id)
	{
		$this->set('Student','Student');
		
		if($id)
		{
			$id = $this->Setting->my_simple_crypt($id,'d');
			
			$smgt_users = TableRegistry::get('smgt_users');
			$class = TableRegistry::get('smgt_exam');
			
			$exists = $smgt_users->exists(['user_id' => $id]);
			
			if($exists)
			{
				$this->set('user_id',$id);
				
				$item = $smgt_users->get($id);
				
				$class_id = $item->classname;
				$section_id = $item->classsection;
				
				$exam_data = $class->find()->where(['class_id' => $class_id, 'section_id' => $section_id])->hydrate(false)->toArray();
				if(!empty($exam_data))
					$this->set('exam_data',$exam_data);
			}	
		}
	}
	public function studentreceipt($id)
	{
		$this->set('Student','Student');
		
		if($id)
		{
			$id = $this->Setting->my_simple_crypt($id,'d');
			
			$class = TableRegistry::get('smgt_exam_hall_receipt');
			$exists = $class->exists(['receipt_id' => $id]);
			
			if($exists)
			{
				$item = $class->get($id);
				
				$exam_id = $item->exam_id;
				$user_id = $item->user_id;
				$hall_id = $item->hall_id;
				
				$exam_time_table = TableRegistry::get('exam_time_table');
				$exam_time_table_data = $exam_time_table->find()->where(['exam_id' => $exam_id])->hydrate(false)->toArray();
				if(!empty($exam_time_table_data))
					$this->set('exam_time_table_data',$exam_time_table_data);
				?>
				<script>window.onload = function(){ 
					window.print(); 				
				};</script>
				<?php
				$school_name = $this->Setting->getfieldname('school_name');
				$school_address = $this->Setting->getfieldname('school_address');
				$stud_date = $this->Setting->getfieldname('date_format');
				$logo=$this->Setting->getfieldname('school_logo');
				?>
				<page size="A4">
				<div class="header_logo" style="float:left; width: 100%;text-align:center;">
					<div class="header_logo" style="float:left; width: 100%;text-align:center;"><img src="<?php echo $this->request->base.'/webroot/img/'.$logo;?>"></div>
					<h4 style="font-size:22px;float:left; width: 100%;text-align:center;"><strong style="color:#204759;"><?php echo $school_name;?></strong></h4>
				</div>	
				<div class="header" style="float:left; width: 100%;font-size:18px;text-align:center;padding-bottom: 20px;">
					<span><strong style="color:#970606;"><?php echo __('Examination Hall Ticket');?></strong></span>
				</div>
				
				<div style="float:left; width: 100%;">
				<table width=100% class="count borderpx" style="">
					<thead>
					</thead>
					<tbody>
						<tr>					
							<td rowspan=4 style="text-align:center;border-right : 1px solid #97C4E7;">
								<img src="<?php echo $this->request->base.'/webroot/img/'.$this->Setting->get_user_image($user_id);?>" width="100px" height="100px"/>	
							</td>
							<td colspan=2 style="border-bottom : 1px solid #97C4E7;">
								<strong><?php echo __('Student Name : ');?></strong><?php echo $this->Setting->get_user_full_name($user_id);?>
							</td>
						</tr>
						<tr>
							<td style="border-bottom : 1px solid #97C4E7;border-right : 1px solid #97C4E7;" align="left">
								<strong><?php echo __('Student ID : ');?></strong><?php echo $this->Setting->get_studentID($user_id);?>												
							</td>
							<td style="border-bottom : 1px solid #97C4E7;" align="left">	
								<strong><?php echo __('Roll No : ');?></strong><?php echo $this->Setting->get_user_roll_no($user_id);?>
							</td>
						</tr>
						<tr>
							<td style="border-bottom : 1px solid #97C4E7;border-right : 1px solid #97C4E7;" align="left">
								<strong><?php echo __('Class Name : ');?></strong><?php echo $this->Setting->get_class_id($this->Setting->get_class_list_user_id($user_id));?>						
							</td>
							<td style="border-bottom : 1px solid #97C4E7;" align="left">
								<strong><?php echo __('Section Name : ');?></strong><?php echo $this->Setting->section_name($this->Setting->get_user_section_id($user_id));?>
							</td>
						</tr>
						<tr>
							<td style="border-right : 1px solid #97C4E7;" align="left">
								<strong><?php echo __('Start Date : ');?></strong><?php echo date($stud_date,strtotime($this->Setting->get_exam_data($exam_id,'exam_date')));?> 						
							</td>
							<td style="border-bottom:0" align="left">
								<strong><?php echo __('End Date : ');?></strong><?php echo date($stud_date,strtotime($this->Setting->get_exam_data($exam_id,'exam_end_date')));?>
							</td>
						</tr>
					</tbody>
					<tfoot>
					</tfoot>
				</table>
				</div>
				<div style="float:left; width: 100%;padding-top: 20px;">
				<table width=100% class="count borderpx" style="">
					<thead>
					</thead>
					<tbody>
						<tr>					
							<td style="border-bottom : 1px solid #97C4E7;">
								<strong><?php echo __('Examination Centre : ');?></strong>
								<?php echo $this->Setting->get_hall_data($hall_id,'hall_name').
								", ".$school_name;?>
							</td>
						</tr>
						<tr>
							<td style="border-bottom:0">
								<strong><?php echo __('Examination Centre Address : ');?></strong><?php echo $school_address;?>
							</td>
						</tr>
					</tbody>
					<tfoot>
					</tfoot>
				</table>
				</div>
				<?php
				if(!empty($exam_time_table_data))
				{
				?>
					<div style="float:left; width: 100%;padding-top: 20px;">
					<table width=100% class="count borderpx" style="">
						<thead>
							<tr>
								<th colspan=5 style="border-bottom : 1px solid #97C4E7;">
									<?php echo __('Time Table For Exam Hall');?>
								</th>
							</tr>
							<tr>
								<th style="text-align:center;border-bottom : 1px solid #97C4E7;"><?php echo __('Subject Code');?></th>
								<th style="text-align:center;border-bottom : 1px solid #97C4E7;"><?php echo __('Subject');?></th>
								<th style="text-align:center;border-bottom : 1px solid #97C4E7;"><?php echo __('Exam Date');?></th>
								<th style="text-align:center;border-bottom : 1px solid #97C4E7;"><?php echo __('Exam Time');?></th>
								<th style="text-align:center;border-bottom : 1px solid #97C4E7;"><?php echo __('Examiner Sign.');?></th>
							</tr>
						</thead>
						<tbody>
						<?php
						foreach($exam_time_table_data as $time_table)
						{
						?>
							<tr>
								<td style="text-align:center;border-bottom : 1px solid #97C4E7;"><?php echo $this->Setting->get_subject_data($time_table['subject_id'],'sub_code');?></td>
								<td style="text-align:center;border-bottom : 1px solid #97C4E7;"><?php echo $this->Setting->get_subject_data($time_table['subject_id'],'sub_name');?></td>
								<td style="text-align:center;border-bottom : 1px solid #97C4E7;"><?php echo date($stud_date,strtotime($time_table['exam_date']));?></td>
								<td style="text-align:center;border-bottom : 1px solid #97C4E7;"><?php echo substr_replace($time_table['start_time'], ' ', -3, -2)." To ".substr_replace($time_table['end_time'], ' ', -3, -2);?></td>
								<td style="text-align:center;border-bottom : 1px solid #97C4E7;"></td>
							</tr>
						<?php
						}
						?>
						</tbody>
						<tfoot>
						</tfoot>
						</table>
					</div>	
				<?php
				}
				?>
				<div class="resultdate">
					<hr color="#97C4E7">
					<span>Student Signature</span>
				</div>
				<div class="signature">
					<hr color="#97C4E7">
					<span>Authorized Signature</span>
				</div>
				</page>
				<style>
				@media print {
				  body, page[size="A4"] {
					font-family: 'Open Sans',sans-serif;
				  }
				}
				table, .header,span.sign{
					font-family: sans-serif;
					font-size : 12px;	
					color : #444;		
				}
				.borderpx
				{
					border : 1px solid #97C4E7;
				}
				.count td, .count th{
					padding-left: 10px;
					/* border-bottom : 1px solid #97C4E7; */					 
					height:40px;
				}
				
				#t1{					
					border :0;
					border-color :#97C4E7;
					border-collapse:collapse;
				}
				#t1 td{
					padding : 6px;
				}
				strong{
					color :#4E5E6A;
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
				
				</style>
				
				<?php				
				$this->set('exam_id',$exam_id);
				$this->set('user_id',$user_id);
				$this->set('hall_id',$hall_id);
			}
			else
				return $this->redirect(['action'=>'studentlist']);
		}
		exit;
	}
	public function studentreceiptpdf($id)
	{

		$this->set('Student','Student');
		
		if($id)
		{
			$id = $this->Setting->my_simple_crypt($id,'d');
			
			$class = TableRegistry::get('smgt_exam_hall_receipt');
			$exists = $class->exists(['receipt_id' => $id]);
			
			if($exists)
			{
				$item = $class->get($id);
				
				$exam_id = $item->exam_id;
				$user_id = $item->user_id;
				$hall_id = $item->hall_id;
				
				$exam_time_table = TableRegistry::get('exam_time_table');
				$exam_time_table_data = $exam_time_table->find()->where(['exam_id' => $exam_id])->hydrate(false)->toArray();
				if(!empty($exam_time_table_data))
					$this->set('exam_time_table_data',$exam_time_table_data);
				?>	
				
				<?php				
				$this->set('exam_id',$exam_id);
				$this->set('user_id',$user_id);
				$this->set('hall_id',$hall_id);
			}
			else
				return $this->redirect(['action'=>'studentlist']);
		}
		
	}
	public function examtimetable()
    {
		$this->set('exam_id',false);
		$t = 0;
		
		$user_id=$this->request->session()->read('user_id');
		
		$smgt_exam=TableRegistry::get('smgt_exam');	
		$smgt_subject=TableRegistry::get('smgt_subject');	
		$exam_time_table=TableRegistry::get('exam_time_table');	
		
		$query1=$smgt_exam->find();
		$this->set('exam_data',$query1);
		
		if(isset($_POST['manage_exam']))
		{
			$exam_id=$_POST['exam_id'];
			$this->set('exam_id',$exam_id);	
			
			$query=$smgt_exam->find()->where(['exam_id'=>$exam_id])->hydrate(false)->toArray();
						
			$exam_name = $query[0]['exam_name'];
			$class_id = $query[0]['class_id'];
			$section_id = $query[0]['section_id'];
			$term_id = $query[0]['term_id'];
			$exam_date = $query[0]['exam_date'];
			$exam_end_date = $query[0]['exam_end_date'];
			
			$this->set('exam_name',$exam_name);
			$this->set('class_id',$class_id);
			$this->set('section_id',$section_id);
			$this->set('term_id',$term_id);
			$this->set('exam_date',$exam_date);
			$this->set('exam_end_date',$exam_end_date);
			
			$subject_query = $smgt_subject->find()->where(['class_id'=>$class_id,'section'=>$section_id])->hydrate(false)->toArray();
			if(!empty($subject_query))
				$this->set('subject_data',$subject_query);	
			
		}
		
		if(isset($_POST['save_exam_time']))
		{			
			$exam_id=$_POST['exam_id'];
			$class_id=$_POST['class_id'];
			$section_id=$_POST['section_id'];
			
			$subject_query = $smgt_subject->find()->where(['class_id'=>$class_id,'section'=>$section_id])->hydrate(false)->toArray();	
		
			if(!empty($subject_query))
			{
				foreach($subject_query as $subject_data)
				{
					$subject_id = $subject_data['subid'];
					$exam_date = $_POST['date_'.$subject_id];
					$start_hour = $_POST["start_hour_".$subject_id];
					$start_min = $_POST["start_min_".$subject_id];
					$start_ampm = $_POST["start_ampm_".$subject_id];
					$end_hour = $_POST["end_hour_".$subject_id];
					$end_min = $_POST["end_min_".$subject_id];
					$end_ampm = $_POST["end_ampm_".$subject_id];
					
					$st=$start_hour.":".$start_min.":".$start_ampm;
					$ed=$end_hour.":".$end_min.":".$end_ampm;
					
					$exam_data=$this->Setting->check_exam_id($exam_id,$subject_id);
					
					if(!$exam_data)
					{
						$exam_entity=$exam_time_table->newEntity();
						
						$data['exam_id']=$exam_id;
						$data['subject_id']=$subject_id;
						$data['exam_date']=date("Y-m-d", strtotime($exam_date));
						$data['start_time']=$st;
						$data['end_time']=$ed;
						$data['created_date']=date("Y-m-d");
						$data['created_by']=$user_id;						
						
						$exam_patch_entity=$exam_time_table->patchEntity($exam_entity,$data);
						
						if($exam_time_table->save($exam_patch_entity))
						{
							$t=1;
						}
					}
					else
					{
						$id = $exam_time_table->get($exam_data);
						
						$data['exam_id']=$exam_id;
						$data['subject_id']=$subject_id;
						$data['exam_date']=date("Y-m-d", strtotime($exam_date));
						$data['start_time']=$st;
						$data['end_time']=$ed;
						$data['created_date']=date("Y-m-d");
						$data['created_by']=$user_id;						
						
						$exam_patch_entity=$exam_time_table->patchEntity($id,$data);
						
						if($exam_time_table->save($exam_patch_entity))
						{
							$t=2;
						}
					}
				}
			}
		}
		if($t == 1)
		{
			$this->Flash->success(__('Exam Time Table Successful', null), 
							   'default', 
								array('class' => 'success'));
		}
		if($t == 2)
		{
			$this->Flash->success(__('Edit Exam Time Table Successful', null), 
							   'default', 
								array('class' => 'success'));
		}
    }
	public function examhallreceipt()
    {
		$this->set('exam_id',false);
		
		$t = 0;
		
		$user_id=$this->request->session()->read('user_id');
		
		$smgt_exam=TableRegistry::get('smgt_exam');		
		$exam_time_table=TableRegistry::get('exam_time_table');	
		
		$query1=$smgt_exam->find();		
		$this->set('exam_data',$query1);	
    }
	public function studentexamhall()
	{
		$this->set('hall_id',false);
		
		$exam_id = $_REQUEST['exam_id'];
		$this->set('exam_id',$exam_id);
		
		$smgt_exam=TableRegistry::get('smgt_exam');
		$smgt_user=TableRegistry::get('smgt_users');
		$smgt_hall=TableRegistry::get('smgt_hall');	
		
		$query2=$smgt_hall->find();
		$this->set('hall_data',$query2);
		
		$exists = $smgt_exam->exists(['exam_id' => $exam_id]);
		
		if($exists)
		{
			$Get_Item = $smgt_exam->find()->where(['exam_id'=>$exam_id])->hydrate(false)->toArray();
			if(!empty($Get_Item))
			{
				$class_id = $Get_Item[0]['class_id'];
				$section_id = $Get_Item[0]['section_id'];
				
				$this->set('class_id',$class_id);
				$this->set('section_id',$section_id);
				
				$conn=ConnectionManager::get('default');
				$Student_Item=$conn->execute("select * from smgt_users 
				where role = 'student' 
				and classname = ".$class_id."
				and classsection = ".$section_id."
				and user_id 
				not in 
				( SELECT u.user_id FROM `smgt_users` as u, 
				smgt_exam_hall_receipt as e where	
				e.exam_id=".$exam_id." 
				and e.user_id=u.user_id
				)")->fetchAll('assoc');
				
				$Student_Item1=$conn->execute("
				SELECT u.* FROM `smgt_users` as u, 
				smgt_exam_hall_receipt as e where
				u.role = 'student' 
				and u.classname = ".$class_id."
				and u.classsection = ".$section_id."	
				and e.exam_id=".$exam_id." 
				and e.user_id=u.user_id")->fetchAll('assoc');
				
				if(!empty($Student_Item))
					$this->set('student_data',$Student_Item);
				if(!empty($Student_Item1))
					$this->set('student_data1',$Student_Item1);
			}
		}
	}
	
	public function assgnexamhall() 
	{
		$this->autoRender = false;
		$i=0;
		$user_id=$this->request->session()->read('user_id');
		
		$id=json_decode($_REQUEST['h_id']);
		$exam_id = $_REQUEST['exam_id'];
		$hall_id = $_REQUEST['hall_id'];
		
		$data_return = "";
		
		if($id)
		{
			foreach($id as $recordid)
			{
				$class = TableRegistry::get('smgt_users');		
				$item =$class->get($recordid);
				$item->exam_hall_receipt = 1;
				
				if($class->save($item))
				{
					$data = array();
					
					$smgt_exam_hall_receipt = TableRegistry::get('smgt_exam_hall_receipt');
					$hall_receipt_entity=$smgt_exam_hall_receipt->newEntity();
					
					$data['exam_id']=$exam_id;
					$data['user_id']=$recordid;
					$data['hall_id']=$hall_id;
					$data['created_date']=date('Y-m-d');
					$data['created_by']=$user_id;
					
					$email_id = $this->Setting->get_user_email_id($recordid);
					
					$insert_data=$smgt_exam_hall_receipt->patchEntity($hall_receipt_entity,$data);
					if($smgt_exam_hall_receipt->save($insert_data))
					{
						$receipt_id = $insert_data['receipt_id'];
						$data = $smgt_exam_hall_receipt->get($receipt_id);
						$data->exam_hall_receipt_status = 1;
						$smgt_exam_hall_receipt->save($data);
						
						$pid = $this->Setting->my_simple_crypt($receipt_id,'e');
						
						$data_return .= "<tr id='".$recordid."'>
							<td> 
									<button type='button' class='btn btn-danger btn-xs btn_del' dataid='".$recordid."'>X</button>
								  </td>
							<td>".$item->first_name." ".$item->last_name."</td>
							<td>".$item->studentID_prefix.$item->studentID."</td>
							</tr>";
						
						$server = $_SERVER['SERVER_NAME'];
						if($server != '192.168.1.22')
							$this->Setting->mail_examhall_pdf($email_id,$pid);
							
						$i=1;				
					}		
				}		
			}
			echo $data_return;
		}
		else
			echo "false";
		die();
	}
	
	public function removeexamhall() 
	{
		$this->autoRender = false;
		$i=0;
		
		$user_id=$this->request->session()->read('user_id');
		
		$userid = $_REQUEST['userid'];
		$exam_id = $_REQUEST['exam_id'];
		
		$class = TableRegistry::get('smgt_users');		
		$item =$class->get($userid);
		$item->exam_hall_receipt = 0;
		
		if($class->save($item))
		{
			$smgt_exam_hall_receipt = TableRegistry::get('smgt_exam_hall_receipt');
			$exam_hall_data = $smgt_exam_hall_receipt->find()->where(['exam_id'=>$exam_id,'user_id'=>$userid])->hydrate(false)->toArray();
			
			if(!empty($exam_hall_data))
			{
				$receipt_id = $exam_hall_data[0]['receipt_id'];
				$item1 =$smgt_exam_hall_receipt->get($receipt_id);
				if($smgt_exam_hall_receipt->delete($item1))
				{
					echo '<tr id="'.$userid.'">';
					echo "<td> 
						<p style='display:none;'>".$userid."</p>
						<input type='checkbox' class='checkbox ch_pend' name='id[]' dataid='".$userid."'> 
					</td>";
					echo '<td>'.$item->first_name." ".$item->last_name.'</td>';
					echo '<td>'.$item->studentID_prefix.$item->studentID.'</td>';
					echo '</tr>';
					
					$i=1;					
				}
			}
		}
		else
			echo "false";
		die();
	}
	public function viewexamtimetable($exam_id)
    {
		$t = 0;
		
		$user_id=$this->request->session()->read('user_id');
		
		$smgt_exam=TableRegistry::get('smgt_exam');	
		$smgt_subject=TableRegistry::get('smgt_subject');	
		$exam_time_table=TableRegistry::get('exam_time_table');	
		
		$query1=$smgt_exam->find();
		$this->set('exam_data',$query1);
		
		if($exam_id)
		{
			$exam_id = $this->Setting->my_simple_crypt($exam_id,'d');
			
			$query=$smgt_exam->find()->where(['exam_id'=>$exam_id])->hydrate(false)->toArray();
				
			if(!empty($query))
			{
				$exam_name = $query[0]['exam_name'];
				$class_id = $query[0]['class_id'];
				$section_id = $query[0]['section_id'];
				$term_id = $query[0]['term_id'];
				$exam_date = $query[0]['exam_date'];
				$exam_end_date = $query[0]['exam_end_date'];
				
				$this->set('exam_name',$exam_name);
				$this->set('class_id',$class_id);
				$this->set('section_id',$section_id);
				$this->set('term_id',$term_id);
				$this->set('exam_date',$exam_date);
				$this->set('exam_end_date',$exam_end_date);
				$this->set('exam_id',$exam_id);
				
				$subject_query = $smgt_subject->find()->where(['class_id'=>$class_id,'section'=>$section_id])->hydrate(false)->toArray();
				if(!empty($subject_query))
					$this->set('subject_data',$subject_query);	
			}
		}
    }
}
?>
