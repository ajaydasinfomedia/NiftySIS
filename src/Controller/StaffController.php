<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\ORM\Table;
use Cake\View\Helper\FlashHelper;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Mailer\Email;

class StaffController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Et');
		$this->loadComponent('Flash');
	}
	public function addstaff()
	{
		$this->set('Staff','Staff');
		
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
						
						$password=$c1['password'];
						
						$hasher = new DefaultPasswordHasher();
						$pass = $hasher->hash($c1['password']);
						$c1['password']=$pass;
						
						$c1['classname']=null;
						$c1['roll_no']=null;
						$c1['submitted_document']=null;
						$c1['child']=null;
						$c1['relation']=null;
						$c1['role']='supportstaff';
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
						
						$a=$class1->patchEntity($a,$c1);
							
						if($class1->save($a))
						{
							$username=$a['username'];							
							$role=$a['role'];
							$this->Flash->success(__('Staff Registered Successfully', null), 
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

								$sys_name = $school_name;
								$sys_email = $sys_email;
								$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
								@mail($to,_($submsg),$message,$headers);

							}		 
						}
					}
					
					return $this->redirect(['action'=>'stafflist']);
		}
	}
	public function stafflist()
	{
		$this->set('Staff','Staff');
		
		$class = TableRegistry::get('Smgt_users');
		
		$query=$class->find()->where(['role'=>'supportstaff']);
		$this->set('it',$query);
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
	
	
	
	public function delete($id)
	{
		$class = TableRegistry::get('Smgt_users');
		$this->request->is(['post','delete']);
		
		$item = $class->get($id);
		if($class->delete($item))
		{
			$this->Flash->success(__('Staff Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'stafflist']);
	}
	public function updatestaff($id)
	{
		$this->set('Staff','Staff');
		if($id)
		{
			$id = $this->Setting->my_simple_crypt($id,'d');	
			
			$class = TableRegistry::get('Smgt_users');			
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
					
					if($img2['password']!='')
					{
						$hasher = new DefaultPasswordHasher();
						$pass = $hasher->hash($img2['password']);
						$img2['password']=$pass;
					}
					else
						$img2['password']=$item->password;
					
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
						$this->Flash->success(__('Staff Updated Successfully', null), 
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
}