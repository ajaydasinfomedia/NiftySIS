<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\Network\Session\DatabaseSession;
use Cake\View\Helper\FlashHelper;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Mailer\Email;

class ChangepasswordController extends AppController
{
	public function initialize()
   {
       parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Flash');
   }
	public function changepassword()
	{
		$session = $this->request->session();
		
		$user_id=$session->read('user_id');
	
		$class=TableRegistry::get('smgt_users');
			
		$query=$class->find('all',array('conditions' => array('user_id' => $user_id)));
		
		$c1=$this->request->data();
		
		$hasher = new DefaultPasswordHasher();
		
		if(!empty($c1['oldpass']))
		{
			
			$oldpass = $c1['oldpass'];
			
			$match = $this->Setting->changepassword();
			$chk_pass = $hasher->check($oldpass,$match);
	
			if($chk_pass)
			{
				$req_pass = $c1['newpass'];
				$newpass= $hasher->hash($c1['newpass']);
				
				$item = $class->get($user_id);
				
				$c1['password']=$newpass;
				
				$item = $class->patchEntity($item,$c1);
					
				if($class->save($item))
				{
					$school_email = $this->Setting->getfieldname('email');
					$school_name = $this->Setting->getfieldname('school_name');
									
					$student_name = $this->Setting->get_user_id($user_id);
					$to = $this->Setting->get_user_email_id($user_id);
					
					$message_content = "Dear $student_name \n\n Your new password : $req_pass\n\n Thank You\n $school_name";
							
					$emial_to = $to;
					$sys_name = $school_name;
					$sys_email = $school_email;
					
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
					$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";

					@mail($emial_to,_("Change Password Reminder!"),$message_content,$headers);
					
					/* $email = new Email('default');
					$email->from(array('jayesh@dasinfomedia.com' => $sys_name))
					->to($emial_to)
					->subject('Change Password Reminder!')
					->send($message_content); */
					
					$this->Flash->success(__('Password Changed Successfully', null), 
                            'default', 
                             array('class' => 'success'));	
				}
			}
			else
			{	
					$this->Flash->success(__('Old Password Is Wrong', null), 
                            'default', 
                             array('class' => 'success'));
			}
		}
	}
}

?>