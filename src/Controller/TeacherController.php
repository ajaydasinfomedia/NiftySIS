<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Mailer\Email;
class TeacherController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Flash');
		$this->loadComponent('Et');
	}
	public function addteacher()
	{
		$this->set('Teacher','Teacher');
		
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
				$password=$c1['password'];
				$pass = $hasher->hash($c1['password']);
				$c1['password']=$pass;
				
				$c1['classname']=implode(',',$this->request->data('classname'));
		
				$c1['roll_no']=null;
				$c1['child']=null;
				$c1['relation']=null;
				$c1['role']='teacher';
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

				$docu=$this->Setting->getdoc($c1['docume']);

				if($docu=='')
				{
					$c1['docume']="profile.jpg";
				}
				else
				{
					$c1['docume']=$this->request->data('docume');
					$c1['docume']=$c1['docume']['name'];
				}
				
				$c1['submitted_document']=$this->request->data(['submitted_document']);
				if(!empty($c1['submitted_document']))
					$c1['submitted_document']=implode(',',$c1['submitted_document']);
				
				$a=$class1->patchEntity($a,$c1);
	
				if($class1->save($a))
				{
					$username=$a['username'];							
					$role=$a['role'];
					$this->Flash->success(__('Teacher Registered Successfully', null), 
							'default', 
							 array('class' => 'success'));
				
				
					$sys_email=$this->Setting->getfieldname('email'); 
					$school_name = $this->Setting->getfieldname('school_name');
					$get_username=$this->Et->get_username1($username);
					$get_password=$this->Et->get_password($password);
					$get_role=$this->Et->get_role($role);

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
				}
			}
			return $this->redirect(['action'=>'teacherlist']);
		}
	}
	
	public function teachermultidelete() 
	{
		$this->autoRender = false;
		$id=json_decode($_REQUEST[t_id]);
		foreach($id as $recordid)
			{
					$class = TableRegistry::get('Smgt_users');
					
					$item =$class->get($recordid);

					if($class->delete($item))
					{
						
					}
					
			}
	}

	
	public function teacherlist()
	{
		$subname = array();
		$classnamels = array();
		$this->set('Teacher','Teacher');
		
		$class = TableRegistry::get('Smgt_users');
		
		$query=$class->find()->where(['role'=>'teacher'])->hydrate(false);
		
		foreach($query as $getid)
		{
			$subjectname=$this->Setting->get_teacher_subject($getid['user_id']);
			$classname=$this->Setting->get_class_id($getid['classname']);
	
			$subname[]=$subjectname;
			$classnamels[]=$classname;
		}
		if(!empty($subname))
			$this->set('subname',$subname);
		if(!empty($classnamels))
		$this->set('classname',$classnamels);
		
		
		$this->set('it',$query);
		
		
	}
	public function delete($id)
	{
		$class = TableRegistry::get('Smgt_users');
		
		$this->request->is(['post','delete']);
		
		$item = $class->get($id);
		if($class->delete($item))
		{
			$this->Flash->success(__('Teacher Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'teacherlist']);
	}
	public function updateteacher($id)
	{
		$this->set('Teacher','Teacher');
		if($id)
		{
			$id = $this->Setting->my_simple_crypt($id,'d');
			
			$class_data = TableRegistry::get('Classmgt');		
			$cls = $class_data->find();	
			$this->set('cls',$cls);

			$class1 = TableRegistry::get('Smgt_users');
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
					
					if($img2['password']!='')
					{
						$hasher = new DefaultPasswordHasher();
						$pass = $hasher->hash($img2['password']);
						$img2['password']=$pass;
					}
					else
						$img2['password']=$item->password;
					
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
						$this->Flash->success(__('Teacher Updated Successfully', null), 
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
	public function teacherattendance($id)
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
					
					$attendance=$this->Setting->smgt_view_teacher_attendance($start_date,$end_date,$user_id);
					
					if(!empty($attendance))
					{
						$this->set('attendance',$attendance);
					}
					
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
			{
				if($role == 'admin')
					return $this->redirect(['action'=>'teacherlist']);
				else
					return $this->redirect(['controller'=>'Comman','action'=>'teacherlist']);
			}
		}
		else
		{
			if($role == 'admin')
				return $this->redirect(['action'=>'teacherlist']);
			else
				return $this->redirect(['controller'=>'Comman','action'=>'teacherlist']);
		}
	}
}

?>