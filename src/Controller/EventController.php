<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;
use Cake\Mailer\Email;

class EventController extends AppController
{
	public function initialize()
   {
       parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Et');
		$this->loadComponent('Flash');
		
		$this->stud_date = $this->Setting->getfieldname('date_format');
		
   }
	public function addevent($id=0)
	{		
		$this->set('Event','Event');
		
		$get_current_user_id=$this->request->session()->read('user_id');
		$sys_name = $this->Setting->getfieldname('school_name');
		$sys_email = $this->Setting->getfieldname('email');
		$current_sms_service=$this->Setting->getfieldname('select_serveice');
		$country = $this->Setting->getfieldname('country');
		$country_code = $this->Setting->get_country_code($country);	
		
		if($id)
		{
			$this->set('edit',true);
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$class = TableRegistry::get('smgt_event');
			$exists = $class->exists(['event_id' => $id]);
			
			if($exists)
			{
				$item = $class->get($id);
				$this->set('row',$item);
			}
			else
				return $this->redirect(['action'=>'eventlist']);	
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
				$db_cl['start_date'] = date("Y-m-d", strtotime($c1['start_date']));
				$db_cl['end_date'] = date("Y-m-d", strtotime($c1['end_date']));
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
		$this->set('Event','Event');
		
		$class = TableRegistry::get('smgt_event');
		$query=$class->find()->order(['event_id'=>'DESC']);
		$this->set('it',$query);
		
	}
	public function delete($id)
	{
		$this->request->is(['post','delete']);
		$class1 = TableRegistry::get('smgt_event');
		$item = $class1->get($id);
		if($class1->delete($item))
		{
			$this->Flash->success(__('Event Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'eventlist']);
	}
}

?>