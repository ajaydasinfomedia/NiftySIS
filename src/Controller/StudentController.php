<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Mailer\Email;

class StudentController extends AppController
{
	
	public function initialize()
    {
       parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Flash');	
		$this->loadComponent('Et');
    }
   
	public function addstudent()
	{
		$this->set('Student','Student');
		
		$class = TableRegistry::get('Classmgt');
		
		$query=$class->find();
		$this->set('it',$query);
		
		$country=$this->Setting->getfieldname('country');
		
		$country_code=$this->Setting->get_country_code($country);
		
		$this->set('country_code',$country_code);

		if($this->request->is('post'))
		{
			$class1 = TableRegistry::get('smgt_users'); 
			
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
				$c1['created_date']=date("Y-m-d");
				$c1['date_of_birth']=date("Y-m-d", strtotime($c1['date_of_birth']));
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
				
				
				$a = $class1->patchEntity($a,$c1);

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

							$sys_name = $school_name;
							$sys_email = $sys_email;
							$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
							@mail($to,_($submsg),$message,$headers);
						}
					}					 
				}
			}
			return $this->redirect(['action'=>'studentlist']);
		}	
	}
	
	public function multidelete() 
	{
		$this->autoRender = false;
		$id=json_decode($_REQUEST[i_id]);
		foreach($id as $recordid)
		{
			$class = TableRegistry::get('Smgt_users');			
			$item =$class->get($recordid);
			if($class->delete($item))
			{			
			}				
		}
	}
	
	public function view2($id = null) 
	{	
		$this->autoRender = false;
		
		if($this->request->is('ajax'))
		{
			$cls = $_POST['id'];
			
			$post = TableRegistry::get('class_section');
			$data = $post->find()->where(["class_id"=>$cls,'is_deactive'=>0])->hydrate(false)->toArray();
			if(!empty($data))
			{
			?>
				<option value=""> <?php echo __('Select Section'); ?> </option>
				<?php
				foreach($data as $option)
				{
					echo "<option value='{$option['class_section_id']}'>{$option['section_name']}</option>";
				}
				die;
			}	
			else
			{
				?>
				<option value=""> <?php echo __('Select Section'); ?> </option>
				<?php
				die;
			}
		}
	}
	
	public function registration()
	{
		$this->set('Student','Student');
		
		$class = TableRegistry::get('Classmgt');
		
		$query=$class->find();
		$this->set('it',$query);
		
		$country=$this->Setting->getfieldname('country');
		
		$country_code=$this->Setting->get_country_code($country);
		$this->set('country_code',$country_code);
		
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
				$a = $class1->newEntity();
				
				$c1=$this->request->data;			
				
				$hasher = new DefaultPasswordHasher();
				$pass = $hasher->hash($c1['password']);
				$c1['password']=$pass;
				
				$c1['working_hour']=null;
				$c1['position']=null;
				$c1['submitted_document']=null;
				$c1['child']=null;
				$c1['relation']=null;
				$c1['role']='student';
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
				$c1['status']='Not Approved';
				
				$a=$class1->patchEntity($a,$c1);				
				if($class1->save($a))
				{
					$this->Flash->success(__('Registration Successful', null), 
							'default', 
							 array('class' => 'success'));
				}
			}			
			return $this->redirect(['controller' => 'User','action'=>'user']);
		}		
	}
	public function studentlist()
	{
		$this->set('Student','Student');
		
		$class_table_register=TableRegistry::get('classmgt');
		$get_all_class_record=$class_table_register->find("list",["keyField"=>"class_id","valueField"=>"class_name"])->hydrate(false)->toArray();
		$this->set('class_data',$get_all_class_record);
		
		$class = TableRegistry::get('Smgt_users');
		$query = $class->find()->where(['role'=>'student','is_deactive'=>'0']);
		
		foreach($query as $data)
		{
			$class_id=$data['classname'];
			$class_section_id=$data['classsection'];
		
			$class_name[]=$this->Setting->get_class_id($class_id);
			$class_section[]=$this->Setting->get_section_name($class_section_id);
		}
		$this->set('class_name',@$class_name);
		$this->set('class_section',@$class_section);
		
		if(isset($_POST['filter_class']))
		{
			$data=$this->request->data();
			
			if(isset($data['select_stud']))
			{			
				$cls_id=$data['select_stud'];
				$this->set('cls_id',$cls_id);
				
				$query=$class->find()->where(['classname'=>$cls_id,'role'=>'student','is_deactive'=>'0']);
			}
			if(isset($data['section']) && !empty(($data['section'])))
			{
				$cls_id=$data['select_stud'];
				$section_id=$data['section'];
				$this->set('cls_id',$cls_id);
				$this->set('sec_id',$section_id);
				
				if(isset($data['select_stud']) && !empty($data['select_stud']))
					$query=$class->find()->where(['classname'=>$cls_id,'classsection'=>$section_id,'role'=>'student','is_deactive'=>'0']);
				else
					$query=$class->find()->where(['classsection'=>$section_id,'role'=>'student','is_deactive'=>'0']);
			}
		}		
		$this->set('it',$query);
	}
	public function delete($id)
	{
		$class = TableRegistry::get('Smgt_users');
		$this->request->is(['post','delete']);
		
		$item =$class->get($id);
		$item->is_deactive = 1;
		if($class->save($item))
		{
			$this->Flash->success(__('Student Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'studentlist']);
	}
	public function approve($id)
	{
		$studentID = $this->Setting->generate_studentID();
		
		$loginlink = $_SERVER['SERVER_NAME'].$this->request->base;
		$fees_amount = '';
		
		$class = TableRegistry::get('Smgt_users');
		$item =$class->get($id);
		
		$item->status = 'Approved';
		$item->studentID = $studentID['studentID'];
		$item->studentID_prefix = $studentID['studentID_prefix'];
		
		if($class->save($item))
		{
			$this->Flash->success(__('Student Approved Successfully', null), 
                            'default', 
                             array('class' => 'success'));
					
			$sys_email=$this->Setting->getfieldname('email'); 
			$school_name = $this->Setting->getfieldname('school_name');
			$get_user_class=$this->Setting->get_class_id($item->classname);
			$feesclass=TableRegistry::get('smgt_fees');	
			$fees =$feesclass->find()->where(['class_id'=>$item->classname])->hydrate(false)->toArray();
			if(!empty($fees))
				$fees_amount=$fees[0]['fees_amount'];

			$mailtem = TableRegistry::get('smgt_emailtemplate');
			$format =$mailtem->find()->where(["find_by"=>"Student_Approved"])->hydrate(false)->toArray();
			
			$str=$format[0]['template'];
			$subject=$format[0]['subject'];
			
			$msgarray = explode(" ",$str);
			$subarray = explode(" ",$subject);

			$email_id=$item->email;

			$msgarray['{{student_name}}']=$item->first_name ." ". $item->last_name;
			$msgarray['{{school_name}}']=$school_name;
			$msgarray['{{class_name}}']=$get_user_class;
			$msgarray['{{roll_number}}']=$item->roll_no;
			$msgarray['{{fee_amount}}']=$fees_amount;
			$msgarray['{{login_link}}']=$loginlink;
			
			$subarray['{{student_name}}']=$item->first_name ." ". $item->last_name;
			$subarray['{{school_name}}']=$school_name;
			$subarray['{{class_name}}']=$get_user_class;
			$subarray['{{roll_number}}']=$item->roll_no;
			$subarray['{{fee_amount}}']=$fees_amount;
			$subarray['{{login_link}}']=$loginlink;

			$datamsg = str_replace(array_keys($msgarray),array_values($msgarray),$str);
			$submsg = str_replace(array_keys($subarray),array_values($subarray),$subject);

			if($email_id != '')
			{
				$email = new Email('default');
				$to = $email_id;
				$message = $datamsg;	
			
				$sys_name = $school_name;
				$sys_email = $sys_email;
				$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
				@mail($to,_($submsg),$message,$headers);
			}
		}
		return $this->redirect(['action'=>'studentlist']);
	}
	public function updatestudent($id)
	{
		$this->set('Student','Student');
		
		if($id)
		{
			$id = $this->Setting->my_simple_crypt($id,'d');
			
			$class_data = TableRegistry::get('Classmgt');		
			$cls = $class_data->find("list",["keyField"=>"class_id","valueField"=>"class_name"]);		
			$this->set('cls',$cls);
			
			$class = TableRegistry::get('Smgt_users');
			$exists = $class->exists(['user_id' => $id]);
			if($exists)
			{
				$item = $class->get($id);
				$stud_class = $item->classname;
				
				$section_data = TableRegistry::get('class_section');
				$sect = $section_data->find("list",["keyField"=>"class_section_id","valueField"=>"section_name"])->where(['class_id'=>$stud_class]);
				$this->set('sect',$sect);
						
				$country=$this->Setting->getfieldname('country');
			
				$country_code=$this->Setting->get_country_code($country);
				$this->set('country_code',$country_code);
			
				if($this->request->is(['post','put']))
				{			
					$img2=$this->request->data();

					if($img2['password']!='')
					{
						$hasher = new DefaultPasswordHasher();
						$pass = $hasher->hash($img2['password']);
						$img2['password']=$pass;
					}
					else
						$img2['password']=$item->password;
					
					if($img2['status'] != '')
						$img2['status']='Approved';
					else
						$img2['status']='Not Approved';
					
					$xyz1=$this->Setting->getimage($img2['image']);
			
					$old_value = $this->request->data('image2');
					$img2['image']=$old_value;
					
					if($xyz1!='')
						$img2['image']=$xyz1;
					
					$img2['date_of_birth']=date("Y-m-d", strtotime($img2['date_of_birth']));
					
					if($item->roll_no == NULL)
					{
						$sys_email=$this->Setting->getfieldname('email'); 
						$school_name = $this->Setting->getfieldname('school_name');
					
						$email = new Email('default');
						$to = $item->email;	
						$submsg = "Registration Confirm";	
						$message = "Your Registration Confirm."."\n"."\n".
									"Student ID : ".$this->Setting->get_studentID($id)."\n" .
									"Class : ".$this->Setting->get_class_id($img2['classname'])."\n" .
									"Section : ".$this->Setting->get_section_name($img2['classsection'])."\n" .
									"Roll No. : ".$img2['roll_no'].
									"\n"."\n".
									"Regards From ".$school_name;
											
						$sys_name = $school_name;
						$sys_email = $sys_email;
						$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
						@mail($to,_($submsg),$message,$headers);
					}		
					
					$item = $class->patchEntity($item,$img2);
				
					if($class->save($item))
					{											
						$this->Flash->success(__('Student Updated Successfully', null), 
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
	
	public function exportcsv()
	{
		$class = TableRegistry::get('Classmgt');
		$user_tbl = TableRegistry::get('smgt_users');
		
		$query=$class->find();
		$this->set('it',$query);
		
		if(isset($_REQUEST['exportstudentin_csv']))
		{
			$class_id = $this->request->data('class_name');
			if($_REQUEST['class_name'] != "all")
			{
				$student_list = $user_tbl->find()->where(['classname' =>$class_id,'role'=>'student']);
			}
			else
			{
				$student_list = $user_tbl->find()->where(['role'=>'student']);
			}

			$rows=array();
			$rows[]=array("No","Username","Email","Roll No","Class Name","First Name","Middle Name","Last Name","Gender","Birth Date","Address","City Name","State Name","Zip Code","Mobile Number","Alternate Mobile Numbe","Phone Number");
			$i = 1;
		
			foreach($student_list as $retrive_date)
			{
				$row = array();
				$row[] = $i; 
				$row[] = $retrive_date['username']; 
				$row[] = $retrive_date['email']; 
				$row[] = $retrive_date['roll_no']; 
				$row[] = $this->Setting->get_class_id($retrive_date['classname']); 
				$row[] = $retrive_date['first_name']; 
				$row[] = $retrive_date['middle_name']; 
				$row[] = $retrive_date['last_name']; 
				$row[] = $retrive_date['gender']; 
				$row[] = $retrive_date['date_of_birth']->format('Y-m-d'); 
				$row[] = $retrive_date['address']; 
				$row[] = $retrive_date['city']; 
				$row[] = $retrive_date['state']; 
				$row[] = $retrive_date['zip_code']; 
				$row[] = $retrive_date['mobile_no']; 
				$row[] = $retrive_date['alternate_mobile_no']; 
				$row[] = $retrive_date['phone']; 
				
				$i++;
				$rows[] = $row;
			}

			$filename = "Export Student.csv";
			$this->Setting->export_to_csv($filename,$rows);
		}
	}
	
	public function uploadcsv()
	{
		$class = TableRegistry::get('Classmgt');
		$user_tbl = TableRegistry::get('smgt_users');
		
		$query=$class->find();
		$this->set('it',$query);
		
		if(isset($_REQUEST['upload_csv_file']))
		{
			if(isset($_FILES['csv_file']))
			{	
				$errors= array();
				$file_name = $_FILES['csv_file']['name'];
				$file_size =$_FILES['csv_file']['size'];
				$file_tmp =$_FILES['csv_file']['tmp_name'];
				$file_type=$_FILES['csv_file']['type'];
		
				$value = explode(".", $_FILES['csv_file']['name']);
			
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
						$password = $csv['password'];
						$class=$_POST['class_name'];
						
						if($password == "") // if user not exist and password is empty but the column is set, it will be generated
							$password =123;
						
						$problematic_row = false;
						
						$user=$this->Setting->check_user($username,$email);
						
						if(!isset($user))
						{
							$c1=$this->request->data;
							
							$a=$user_tbl->newEntity();
							
							$c1['classname']=$class;
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
							$c1['status']=null;
							$c1['classsection']=$cls;
							
							
							
							$a=$user_tbl->patchEntity($a,$c1);
								
							if($user_tbl->save($a))
							{
								$i=1;								
							}
				
						}
						else
						{
							$id = $user_tbl->get($user);
							
							$c1=$this->request->data;
							
							$c1['classname']=$_POST['class_name'];
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
							$c1['email']=$email;
							$c1['username']=$username;
							$c1['password']=$password;
							$c1['image']='finel-logo6.jpg';
							$c1['working_hour']=null;
							$c1['position']=null;
							$c1['submitted_document']=null;
							$c1['relation']=null;
							$c1['role']='student';
							$c1['status']=null;
							$c1['classsection']=$_POST['classsection'];										
							
							$a=$user_tbl->patchEntity($id,$c1);
								
							if($user_tbl->save($a))
							{
								$i=2;									
							}				
						}				
					}
					if($i==1)
					{
						$this->Flash->success(__('CSV Record added Successfully', null), 
										   'default', 
											array('class' => 'success'));
					}
					if($i==2)
					{
						$this->Flash->success(__('CSV Record Updated Successfully', null), 
											   'default', 
												array('class' => 'success'));											
					}
				}
			}
		}
	}
	public function result($id = null)
	{
		$this->autoRender=false;
		
		if($this->request->is('ajax'))
		{
			$id=$_POST['id'];
		
			$class = TableRegistry::get('Smgt_users');
			$u_id=$class->get($id);
			
			$user_id=$u_id['user_id'];

			$name=$u_id['first_name']." ".$u_id['last_name'];
			
			$all_exam=$this->Setting->exam();
			
			$get_current_user_id=$this->request->session()->read('user_id');
			
			$class_id = $u_id['classname'];
			
			$tbl_sub_count=$this->Setting->get_subject_count($class_id);
			
			$tbl_subject=$this->Setting->get_subject($class_id);
			
			$total_subject=$tbl_sub_count;
			
			?>	

			<div class="panel panel-white">
			
				<div class="panel-heading">
					<h4 class="panel-title"><?php echo $name;?></h4>
				</div>
			
			<?php 
				
				if(!empty($all_exam))
				{
					
				?>
					<div class="clearfix"></div>
  					<div id="accordion" class="panel-group" aria-multiselectable="true" role="tablist">
						
					<?php 
						
						$i=0;
						foreach ($all_exam as $exam)
						{
							$exam_id =$exam['exam_id'];
							$total_mark = $this->Setting->get_exam_data($exam_id,'total_mark');
						?>
						
						<div class="panel panel-default">
							<div id="heading_<?php echo $i;?>" class="panel-heading" role="tab">
							<h4 class="panel-title"> <a class="collapsed" aria-controls="collapse_<?php echo $i;?>" aria-expanded="false" href="#collapse_<?php echo $i;?>" data-parent="#accordion" data-toggle="collapse">
								<?php echo __('Exam Result'); ?>
								: <?php echo $exam['exam_name']; ?> </a> </h4>
								
							</div>
						
						<div id="collapse_<?php echo $i;?>" class="panel-collapse collapse" aria-labelledby="heading_<?php echo $i;?>" role="tabpanel" aria-expanded="true" style="height: 0px;">
								<div class="clearfix"></div>
								<?php if(isset($get_current_user_id)) {?>
									<div class="print-button pull-right" style="padding-right: 15px;padding-top: 20px;"> 
					
									<a href="studentresultpdf/<?php echo $this->Setting->my_simple_crypt($user_id,'e');?>/<?php echo $this->Setting->my_simple_crypt($exam_id,'e');?>" target="_blank" class="btn btn-info"><?php echo __('PDF'); ?></a> 
									<a href="studentresultprint/<?php echo $this->Setting->my_simple_crypt($user_id,'e');?>/<?php echo $this->Setting->my_simple_crypt($exam_id,'e');?>" target="_blank" class="btn btn-info"><?php echo __('Print'); ?></a> 
									
									</div>
									
									<?php }else 
										{
											?>
									<div class="print-button pull-right" style="padding-right: 15px;padding-top: 20px;"> <a href="?dashboard=user&page=student&print=pdf&student=<?php echo $user_id;?>&exam_id=<?php echo $exam_id;?>" target="_blank" class="btn btn-info"><?php echo __('PDF'); ?></a> <a href="?dashboard=user&page=student&print=print&student=<?php echo $user_id;?>&exam_id=<?php echo $exam_id;?>" target="_blank" class="btn btn-info"><?php echo __('Print'); ?></a> </div>
									<?php 
											
										}
											
											?>
								<div class="clearfix"></div>
								<div class="panel-body view_result">
									<div class="table-responsive">
										<table class="table table-bordered" style="text-align: center;">
										  <tr>
											<th style="text-align: center;"><?php echo __('SI.No.')?></th>
											<th style="text-align: center;"><?php echo __('Subject')?></th>
											<th style="text-align: center;"><?php echo __('Obtain Mark (Out of '.$total_mark.')')?></th>
											<th style="text-align: center;"><?php echo __('Total Mark')?>
											<?php				  
											if($total_mark == 100)
											{
											?>
											<th style="text-align: center;"><?php echo __('Grade')?></th>
											<th style="text-align: center;"><?php echo __('Grade Comment')?></th>
											<?php
											}
											?>
										  </tr>
										<?php
											$no = 1;
											$total=0;
											$grade_point = 0;
											$cnt_total_mark = 0;
											foreach($tbl_subject as $sub)
											{
												
												$mark=$this->Setting->get_mark($exam_id,$class_id,$sub['subid'],$user_id);
												
												$grade_id=$this->Setting->grade_mark($mark);
												
												$get_grade=$this->Setting->grade_name($grade_id);
												$get_grade_marks_comment=$this->Setting->grade_comment($grade_id);
												$get_grade_point=$this->Setting->grade_point($grade_id);
											
											?>
											<tr>
												<td><?php echo $no;?></td>
												<td><?php echo $sub['sub_name'];?></td>
												<td><?php echo $mark;?> </td>
												<td><?php echo $total_mark;?> </td>
												<?php				  
												if($total_mark == 100)
												{
												?>
												<td><?php echo $get_grade;?></td>
												<td><?php echo $get_grade_marks_comment;?></td>
												<?php
												}
												?>
											</tr>
											<?php
							$total +=  $mark;
							$grade_point += $get_grade_point;
							$cnt_total_mark = $cnt_total_mark+$total_mark;
							$no++;
							}
							
							?>
							<tr>
								<td colspan="2" align=center class="totalmarks bordertop"><b><?php echo __('Total Marks');?></b></td>				
								<td colspan="1" align=center class="bordertop"><?php echo $total;?></td>
								<td colspan="1" align=center class="bordertop"><?php echo $cnt_total_mark;?></td>
							</tr>
							<tr>
							<?php
							$percentage = 0;
							if($total > 0)
								$percentage = round($total*100/$cnt_total_mark);
							?>
							<td colspan="2" align=center class="totalmarks bordertop"><b><?php echo __('Percentage');?></b></td>				
							<td colspan="2" align=center class="bordertop"><?php echo $percentage.'%';?></td>
							</tr>
							</table>
						  </div>
						  <?php
						  
						  if($total_mark == 100)
						  {
						  ?>
						  <p class="result_point">
							<?php echo __("GPA( Grade Point Average )");
							if($grade_point > 0)
							{
								$GPA=$grade_point/$total_subject;							
								echo " => ".round($GPA, 2);
							}
							else{echo " => 0";}
							?>
						  </p>
						  <hr />
						  <?php
						  }
						  ?>
						</div>
					  </div>
					  </div>
				<?php
				$i++;
			}
			?>
			
			</div>
		</div>
		
		<?php
		}
		else 
		{
			 echo __('No Result Found');
		}
	}
	}
	public function studentresultpdf($student_id = null,$exam_id)
	{
		$student_id = $this->Setting->my_simple_crypt($student_id,'d');
		$exam_id = $this->Setting->my_simple_crypt($exam_id,'d');
		
		$class = TableRegistry::get('Smgt_users');
		$exam_class = TableRegistry::get('Smgt_exam');
		
		$exists = $class->exists(['user_id' => $student_id]);	
		$exists1 = $exam_class->exists(['exam_id' => $exam_id]);	
		if($exists && $exists1)
		{				
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
			
			$mname=$u_id['middle_name'];
			$this->set('mname',$mname);
			
			$lname=$u_id['last_name'];
			$this->set('lname',$lname);
			
			$name=$u_id['first_name']." ".$u_id['last_name'];
			
			$profile=$u_id['image'];
			
			if($profile == ''){
				$profile = 'finel-logo6.jpg';
			}
			else
			{
				$ext = pathinfo($profile, PATHINFO_EXTENSION);
				if($ext == 'png')
					$profile = 'finel-logo6.jpg';
				else
					$profile=$u_id['image'];
			}
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
		}
		else
			return $this->redirect(['action'=>'studentlist']);
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
		
		$exists = $class->exists(['user_id' => $student_id]);	
		$exists1 = $exam_class->exists(['exam_id' => $exam_id]);	
		if($exists && $exists1)
		{
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
			
			$mname=$u_id['middle_name'];
			$this->set('mname',$mname);
			
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
					<td colspan="3" align=center><?php echo $fname." ".$mname." ".$lname;?></td>											
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
		else
			return $this->redirect(['action'=>'studentlist']);
	}
	public function studentparent($id = null)
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
			
			$parent_list=$class1->find()->where(['child_id'=>$user_id]);
			
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
						<div id="example_wrapper" class="dataTables_wrapper">
						<table class="table table-bordered table-striped" cellspacing="0" width="100%">
							<thead>
							<tr>
								<th><?php echo __('Photo')?></th>
								<th><?php echo __('Name')?></th>
								<th><?php echo __('Relation')?></th>
							</tr>
							</thead>
							<tfoot>
							</tfoot>
							<tbody>
							<?php
							if(!$parent_list->isEmpty())
							{
								
								foreach($parent_list as $list_part)
								{
									$pare_id=$list_part['child_parent_id'];
									
									$name=$this->Setting->get_user_id($pare_id);
									$photo=$this->Setting->get_user_image($pare_id);
									$relation=$this->Setting->get_user_relation($pare_id);

								?>
								<tr>
									<td><image src="../img/<?php echo $photo; ?>" alt="none" height='50px' width='50px' class='profileimg'/></td>
									<td><?php echo $name;?></td>
									<td><?php echo $relation;?></td>			
								</tr>
								
							<?php }
							}else
							{
							echo "<b><p style='color:red;'>Parent Not Available</p></b>";}
							?>
							</tbody>
						</table>
						</div>
					</div>
				</div>	
			</div>		
		<?php
		}
	}
	public function studentattendance($id)
	{	
		$get_current_user_id = $this->request->session()->read('user_id');			
		$role=$this->Setting->get_user_role($get_current_user_id);
		
		if($id)
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
					
					$attendance = $this->Setting->smgt_view_student_attendance($start_date,$end_date,$user_id);
					
					if(!empty($attendance))
					{
						$this->set('attendance',$attendance);
					}

					/* $curremt_date =$start_date;
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
						$this->set('attendance_status',$attendance_status); */
				}
			}
			else
			{
				if($role == 'admin')
					return $this->redirect(['action'=>'studentlist']);
				else
					return $this->redirect(['controller'=>'Comman','action'=>'studentlist']);
			}
		}
		else
		{
			if($role == 'admin')
				return $this->redirect(['action'=>'studentlist']);
			else
				return $this->redirect(['controller'=>'Comman','action'=>'studentlist']);
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
	public function studentsubjectattendance($id)
    {		
		if(isset($id))
		{
			$class3=TableRegistry::get('smgt_subject');
			
			$id = $this->Setting->my_simple_crypt($id,'d');
			$this->set('id',$id);
			
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
					
					if(!empty($attendance))
					{
						$this->set('attendance',$attendance);
					}
					
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
			{
				if($role == 'admin')
					return $this->redirect(['action'=>'studentlist']);
				else
					return $this->redirect(['controller'=>'Comman','action'=>'studentlist']);
			}
		}
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
}
?>