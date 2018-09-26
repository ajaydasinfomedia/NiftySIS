<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Mailer\Email;

class HomeworkController extends AppController
{
	public function initialize()
    {
       parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Flash');	
		$this->loadComponent('Et');
    }
	
	public function addhomework($id=0)
    {
		$stud_date = $this->Setting->getfieldname('date_format');
		
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
					$homework_title = $homework_patchEntity['title'];
					$homework_submition_date = $homework_patchEntity['submission_date'];
					
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
										$parent_name = $this->Setting->get_user_id($parent_id);
										$student_name = $this->Setting->get_user_id($user_id);
										
										$sys_email=$this->Setting->getfieldname('email'); 
										$school_name = $this->Setting->getfieldname('school_name');
										
										$mailtem = TableRegistry::get('smgt_emailtemplate');
										$format =$mailtem->find()->where(["find_by"=>"HomeWork Mail Template"])->hydrate(false)->toArray();
										
										$str=$format[0]['template'];
										$subject=$format[0]['subject'];
										
										$msgarray = explode(" ",$str);
										$subarray = explode(" ",$subject);
										
										$email_id=$emial_to;

										$msgarray['{{parent_name}}']=$parent_name;
										$msgarray['{{student_name}}']=$student_name;
										$msgarray['{{title}}']=$homework_title;	
										$msgarray['{{submition_date}}']=date($stud_date,strtotime($homework_submition_date));
										$msgarray['{{school_name}}']=$school_name;	

										$subarray['{{parent_name}}']=$parent_name;
										$subarray['{{student_name}}']=$student_name;
										$subarray['{{title}}']=$homework_title;	
										$subarray['{{submition_date}}']=date($stud_date,strtotime($homework_submition_date));
										$subarray['{{school_name}}']=$school_name;			

										$datamsg = str_replace(array_keys($msgarray),array_values($msgarray),$str);
										$submsg = str_replace(array_keys($subarray),array_values($subarray),$subject);
																			
										if($email_id != '')
										{							
											$email = new Email('default');
											$to = $emial_to;																		
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
				
				$db_cl = array();
				
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
						$homework_title = $homework_patchEntity['title'];
						$homework_submition_date = $homework_patchEntity['submission_date'];
					
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
										$parent_name = $this->Setting->get_user_id($parent_id);
										$student_name = $this->Setting->get_user_id($user_id);
										
										$sys_email=$this->Setting->getfieldname('email'); 
										$school_name = $this->Setting->getfieldname('school_name');
										
										$mailtem = TableRegistry::get('smgt_emailtemplate');
										$format =$mailtem->find()->where(["find_by"=>"HomeWork Mail Template"])->hydrate(false)->toArray();
										
										$str=$format[0]['template'];
										$subject=$format[0]['subject'];
										
										$msgarray = explode(" ",$str);
										$subarray = explode(" ",$subject);
										
										$email_id=$emial_to;

										$msgarray['{{parent_name}}']=$parent_name;
										$msgarray['{{student_name}}']=$student_name;
										$msgarray['{{title}}']=$homework_title;	
										$msgarray['{{submission_date}}']=date($stud_date,strtotime($homework_submition_date));
										$msgarray['{{school_name}}']=$school_name;	

										$subarray['{{parent_name}}']=$parent_name;
										$subarray['{{student_name}}']=$student_name;
										$subarray['{{title}}']=$homework_title;	
										$subarray['{{submission_date}}']=date($stud_date,strtotime($homework_submition_date));
										$subarray['{{school_name}}']=$school_name;			

										$datamsg = str_replace(array_keys($msgarray),array_values($msgarray),$str);
										$submsg = str_replace(array_keys($subarray),array_values($subarray),$subject);

										if($email_id != '')
										{										
											$email = new Email('default');
											$to = $emial_to;									
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
							$i = 1;
						}
					}					
				}
			}
			if($i == 1)
			{
				$this->Flash->success(__('Homework added Successfully', null), 
							'default', 
							array('class' => 'success'));
			}
			if($i == 2)
			{
				$this->Flash->success(__('Homework Updated Successfully', null), 
							'default', 
							array('class' => 'success'));
			}
			return $this->redirect(['action'=>'homeworklist']);
		}
    }
	
	public function homeworklist()
    {
		$this->set('Homework','Homework');
		
		$class = TableRegistry::get('smgt_homework');
		$query=$class->find()->order(['homework_id'=>'DESC']);
		$this->set('it',$query);
    }
	
	public function delete($id=0)
	{
		$smgt_homework = TableRegistry::get('smgt_homework');
		$smgt_student_homework = TableRegistry::get('smgt_student_homework'); 
		
		$this->request->is(['post','delete']);
		
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
	
	public function multidelete() 
	{
		$this->autoRender = false;
		
		$id = json_decode($_REQUEST['i_id']);
		
		$smgt_homework = TableRegistry::get('smgt_homework');
		$smgt_student_homework = TableRegistry::get('smgt_student_homework');
		
		foreach($id as $recordid)
		{
			$item =$smgt_homework->get($recordid);
			
			if($smgt_homework->delete($item))
			{
				$exitdata = array();
				$exitdata = $smgt_student_homework->find()->where(['homework_id'=>$recordid]);
				
				if(!empty($exitdata))
				{
					foreach($exitdata as $exit_data)
					{
						$stu_homework_id = $exit_data['stu_homework_id'];
						$del_stu_homework_id = $smgt_student_homework->get($stu_homework_id);
						$smgt_student_homework->delete($del_stu_homework_id);
					}
				}			
			}				
		}
	}
	
	public function viewsubmission($id=0)
    {
		$this->set('Homework','Homework');
		
		$smgt_student_homework = TableRegistry::get('smgt_student_homework');
		
		if($id)
		{
			$this->set('id',$id);
			
			$query = $smgt_student_homework->find()->where(['homework_id'=>$id]);
		
			if(!empty($query))
				$this->set('it',$query);
		}
	}
	
	public function submissionfile($readfile = NULL)
	{
		$this->set('file',$readfile);
		
		$file = WWW_ROOT.'submission'.DS.$readfile;

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
			$this->redirect(['action'=>'submissionfile']);	
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
}
?>