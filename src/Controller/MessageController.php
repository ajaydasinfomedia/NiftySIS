<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\Routing\Router;
use Cake\I18n\Time;
use Services_Twilio;
use Cake\Mailer\Email;
class MessageController extends AppController
{
	public function initialize()
   {
       parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Flash');
		$this->loadComponent('Et');
		$this->loadModel('Post');
		
		require_once(ROOT . DS .'vendor' . DS  . 'twilio' . DS . 'Services' . DS . 'Twilio.php');
   }
	public function message()
	{
		$class = TableRegistry::get('Classmgt');
		$query=$class->find();
		$this->set('class',$query);
	}
	public function compose()
	{
		$cls=TableRegistry::get('smgt_message_sent');
		$cls1=TableRegistry::get('smgt_message_reciver');
		
		$current_date=Time::now();
		$this->set('current_date',$current_date);
		
		$class = TableRegistry::get('Classmgt');
		$query=$class->find();
		$this->set('class',$query);
		
		$class1 = TableRegistry::get('smgt_users');
		$query1=$class1->find()->where(['role'=>'student']);
		$this->set('s_user',$query1);
		
		$query2=$class1->find()->where(['role'=>'teacher']);
		$this->set('t_user',$query2);
		
		$query3=$class1->find()->where(['role'=>'parent']);
		$this->set('p_user',$query3);
		
		$query4=$class1->find()->where(['role'=>'supportstaff']);
		$this->set('ss_user',$query4);
		
		$nm=$this->Setting->getfieldname('clicktell');
		$nm1=$this->Setting->getfieldname('twillo');
		
		$f = 0;
		
		if($this->request->is('post'))
		{
			$data=$this->request->data;

			if(isset($_POST['save_message']))
			{
				$class_id = 0;
				$class_section_id = 0;
				$message_body="";
				$sender_id=$this->request->session()->read('user_id');
				$created_date = $current_date;
				$subject = $data['subject'];
				$message_body = $data['message_body'];
				$tablename="smgt_message";
				$emial_to = "";
				
				$role=$data['message_for'];
				$class_id=$data['class_id'];
				
				if(isset($_REQUEST['class_id']))
					 $class_id = $_REQUEST['class_id'];
				if(isset($_REQUEST['section']))
					 $class_section_id = $_REQUEST['section'];
				
				if($role == 'all' || $role == 'parent' || $role == 'student' || $role == 'teacher' || $role == 'supportstaff' )
				{
					if($role == 'all')
					{
						$user_data = array();
						$result=$class1->find()->hydrate(false)->toArray();
						foreach($result as $retrive_data)
						{
							$user_data[]=$retrive_data['user_id'];
						}	
						$userdata=$user_data;
					}
					else{
						$userdata=$this->Setting->smgt_get_event_mailer($role,$_REQUEST['class_id'],$class_section_id);		
					}
					/* debug($userdata);die; */
					if(!empty($userdata))
					{
						$smgt_sms_service_enable=isset($_REQUEST['smgt_sms_service_enable'])?$_REQUEST['smgt_sms_service_enable']:0;
	
						if($smgt_sms_service_enable == 1)
						{	
							$mail_id = array();
							$i = 0;
						
							foreach($userdata as $user)
							{
								
								if($role == 'parent' && $class_id != 'all')
									$mail_id[]=$user;
								else 
									$mail_id[]=$user;
									
								$i++;
								
							}
							foreach($mail_id as $user_id)
							{
								$country = $this->Setting->getfieldname('country');
								$country_code = $this->Setting->get_country_code($country);
								
								$reciever_number = "+".$country_code."".$this->Setting->get_user_mobile_no($user_id);
				
								$message_content = $data['sms_template'];

								$current_sms_service=$this->Setting->getfieldname('select_serveice');
								
								if($current_sms_service == 'clicktell')
								{
									$to = $reciever_number;
					
									$message = str_replace(" ","%20",$message_content);
									
									$nm=$this->Setting->getfieldname($current_sms_service);	
									$service_data_decode=json_decode($nm,true);
									
									$username=$service_data_decode['username'];
									$password=$service_data_decode['password'];
									$api=$service_data_decode['api_key'];
									$sendsms_id=$service_data_decode['sender_id'];
									
									$baseurl ="http://api.clickatell.com";
															
									$url = "$baseurl/http/sendmsg?user=$username&password=$password&api_id=$api&to=$to&text=$message";
												
									$ret = file($url);
							
								}
								if($current_sms_service == 'twillo')
								{
									
									$receiver = $reciever_number; //Receiver Number
									$message = str_replace(" ","%20",$message_content);
										
									$nm1=$this->Setting->getfieldname($current_sms_service);
									$service_data_decode=json_decode($nm1,true);
									
									$account_sid = $service_data_decode['account_sid']; //Twilio SID
									$auth_token = $service_data_decode['auth_token']; // Twilio token
									$from_number = $service_data_decode['from_number'];//My number											
													
									$client = new Services_Twilio($account_sid, $auth_token);
				 				
									$message_sent = $client->account->messages->sendMessage(
				 						$from_number, // From a valid Twilio number
				 						$receiver, // Text this number
				 						$message
									);
			 			 								
								}
																						
							}
						}
						
						$mail_service_enable=isset($_REQUEST['sendmail'])?$_REQUEST['sendmail']:0;
						if($mail_service_enable == 1)
						{
							foreach($userdata as $user)
							{
								$email = $this->Setting->get_user_email_id($user);
								if($email != "")
								{
									
									$message_subject = $data["subject"];
									$message_content = $data["message_body"];
									$sender = $this->Setting->get_user_id($sender_id);
									
									
									
									$email_from = "Niftyschool@school.com"; // Who the email is from  
									$email_subject = "Message Reminder Alert!"; // The Subject of the email  
									$email_message = "Sir / Madam / Dear Student,<br>";
									$email_message .= "<p><strong>{$sender}</strong> Message to you.</p>";
									$email_message .= "<p><strong>Message Subject :</strong>{$message_subject}.</p>";
									$email_message .= "<p><strong>Message Content :</strong>{$message_content}.</p>";
									$email_message .= "<p>Thank You.</p>";
									
			
			
									$email_to = $email; // Who the email is to  
									$headers = "From: ".$email_from;  
			 
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
									$email_message .= "--{$mime_boundary}\n" .  
													  "Content-Transfer-Encoding: base64\n\n" .   
													  "--{$mime_boundary}--\n";  

									$ok = @mail($email_to, $email_subject, $email_message, $headers);
								}
							}
						}
					}
					
					$a=$cls->newEntity();
													
					$data['sender_id']=$sender_id;
					$data['message_for']=$role;
					$data['class_id']=$class_id;
					$data['date']=$current_date;
					$data['deleted']=0;
						
							
					$a=$cls->patchEntity($a,$data);
							
					if($cls->save($a))
					{
						$msgid=$a['message_id'];
										
						foreach($userdata as $receive_data)
						{
							
							$a1=$cls1->newEntity();
							
							$data['message_id']=$msgid;
							$data['reciver_id']=$receive_data;
							$data['sent_id']=$sender_id;
							$data['date']=$current_date;
							$data['status']=0;
								
									
							$a1=$cls1->patchEntity($a1,$data);
									
							if($cls1->save($a1))
							{
								$f = 1;	

								$name = $this->Setting->get_user_id($receive_data);	
								$emailrol = $this->Setting->get_user_email_id($receive_data);	

								$sys_email=$this->Setting->getfieldname('email'); 
								$school_name = $this->Setting->getfieldname('school_name');

								$subject="";
								$mailtem = TableRegistry::get('smgt_emailtemplate');
								$format =$mailtem->find()->where(["find_by"=>"Message Received"])->hydrate(false)->toArray();
								
								$str=$format[0]['template'];
								$subject=$format[0]['subject'];
								
								$msgarray = explode(" ",$str);
								$subarray = explode(" ",$subject);
								
								$email_id=$emailrol;
								
								$sender_email=$this->Setting->get_user_email_id($sender_id);
					
								$msgarray['{{from_mail}}']=$sender_email;
								$msgarray['{{school_name}}']=$school_name;
								$msgarray['{{receiver_name}}']=$name;
								$msgarray['{{message_content}}']=$message_body;
								
								$subarray['{{from_mail}}']=$sender_email;
								$subarray['{{school_name}}']=$school_name;
								$subarray['{{receiver_name}}']=$name;
								$subarray['{{message_content}}']=$message_body;
								
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
				else
				{
					$role=$data['message_for'];
					
					$smgt_sms_service_enable=isset($_REQUEST['smgt_sms_service_enable'])?$_REQUEST['smgt_sms_service_enable']:0;

					if($smgt_sms_service_enable == 1)
					{	
						$country = $this->Setting->getfieldname('country');
						$country_code = $this->Setting->get_country_code($country);
									
						$reciever_number = "+".$country_code."".$this->Setting->get_user_mobile_no($role);
				
						$message_content = $data['sms_template'];
	
						$current_sms_service=$this->Setting->getfieldname('select_serveice');
								
						if($current_sms_service == 'clicktell')
						{	
							$to = $reciever_number;
							$message = str_replace(" ","%20",$message_content);
																	
							$nm=$this->Setting->getfieldname($current_sms_service);						
							$service_data_decode=json_decode($nm,true);
									
							$username=$service_data_decode['username'];
							$password=$service_data_decode['password'];
							$api=$service_data_decode['api_key'];
							$sendsms_id=$service_data_decode['sender_id'];
									
							$baseurl ="http://api.clickatell.com";
															
							$url = "$baseurl/http/sendmsg?user=$username&password=$password&api_id=$api&to=$to&text=$message";
												
							$ret = file($url);
		
						}
						if($current_sms_service == 'twillo')
						{
									
							$receiver = $reciever_number; //Receiver Number
							$message = str_replace(" ","%20",$message_content);
																	
							$nm1=$this->Setting->getfieldname($current_sms_service);
							$service_data_decode=json_decode($nm1,true);
									
							$account_sid = $service_data_decode['account_sid']; //Twilio SID
							$auth_token = $service_data_decode['auth_token']; // Twilio token
							$from_number = $service_data_decode['from_number'];//My number											
													
							$client = new Services_Twilio($account_sid, $auth_token);
							
							$message_sent = $client->account->messages->sendMessage(
								$from_number, // From a valid Twilio number
								$receiver, // Text this number
								$message
							);
			 			 								
						}
					}
					
					$mail_service_enable=isset($_REQUEST['sendmail'])?$_REQUEST['sendmail']:0;
					if($mail_service_enable == 1)
					{
						
							$email = $this->Setting->get_user_email_id($role);
					
							if($email != "")
							{
								
								$message_subject = $data["subject"];
								$message_content = $data["message_body"];
								$sender = $this->Setting->get_user_id($sender_id);
								
								
								
								$email_from = "Niftyschool@school.com"; // Who the email is from  
								$email_subject = "Message Reminder Alert!"; // The Subject of the email  
								$email_message = "Sir / Madam / Dear Student,<br>";
								$email_message .= "<p><strong>{$sender}</strong> Message to you.</p>";
								$email_message .= "<p><strong>Message Subject :</strong>{$message_subject}.</p>";
								$email_message .= "<p><strong>Message Content :</strong>{$message_content}.</p>";
								$email_message .= "<p>Thank You.</p>";
								
		
		
								$email_to = $email; // Who the email is to  
								$headers = "From: ".$email_from;  
		 
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
								$email_message .= "--{$mime_boundary}\n" .  
												  "Content-Transfer-Encoding: base64\n\n" .   
												  "--{$mime_boundary}--\n";  

						 
								$ok = @mail($email_to, $email_subject, $email_message, $headers);
							}
						
					}
					
					
					$a=$cls->newEntity();
					
					$get_message_des="";
					
					$data['sender_id']=$sender_id;
					$data['message_for']=$role;
					$data['class_id']="user";
					$data['date']=$current_date;
					$data['deleted']=0;
	
					$a=$cls->patchEntity($a,$data);
							
					if($cls->save($a))
					{
						$msgid=$a['message_id'];			
					
						$a1=$cls1->newEntity();
							
						$data['message_id']=$msgid;
						$data['reciver_id']=$role;
						$data['sent_id']=$sender_id;
						$data['date']=$current_date;
						$data['status']=0;
																
						$a1=$cls1->patchEntity($a1,$data);
									
						if($cls1->save($a1))
						{
							$f = 1;			
						}
					}
				}
				if($f == 1)
				{
					$this->Flash->success(__('Message Sent Successfully', null), 
                            'default', 
                             array('class' => 'success'));
				   				 
					$stud_email=$this->Setting->get_user_email_id($role);
					$data=$this->Setting->get_user_id($role);
					$sys_email=$this->Setting->getfieldname('email'); 
					$school_name = $this->Setting->getfieldname('school_name');

					$subject="";
					$mailtem = TableRegistry::get('smgt_emailtemplate');
					$format =$mailtem->find()->where(["find_by"=>"Message Received"])->hydrate(false)->toArray();
					$str=$format[0]['template'];
					$subject=$format[0]['subject'];
					
					$msgarray = explode(" ",$str);
					$subarray = explode(" ",$subject);
					
					$email_id=$stud_email;
					$sender_email=$this->Setting->get_user_email_id($sender_id);
					
					$msgarray['{{from_mail}}']=$sender_email;
					$msgarray['{{school_name}}']=$school_name;
					$msgarray['{{receiver_name}}']=$data;
					$msgarray['{{message_content}}']=$message_body;
					
					$subarray['{{from_mail}}']=$sender_email;
					$subarray['{{school_name}}']=$school_name;
					$subarray['{{receiver_name}}']=$data;
					$subarray['{{message_content}}']=$message_body;
					
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
	public function inbox()
	{			
		$user_id=$this->request->session()->read('user_id');
		$cls1=TableRegistry::get('smgt_message_reciver');
		
		$inboxdata = "";
		
		$sentdeleted=TableRegistry::get('smgt_message_sent');
		$deletemsgid=$sentdeleted->find()->where(['deleted'=>1,'sender_id'=>$user_id]);
		foreach($deletemsgid as $deletedata)
		{   
			$deleteid[]=$deletedata['message_id'];  
		}

		$cls2=$cls1->find()->where(['reciver_id'=>$user_id,'status'=>0])->count();
		$this->set('p',$cls2);
		
		$cls_cout=TableRegistry::get('smgt_message_replies');
		
		$cls_msg_cout=$cls_cout->find()->where(['receiver_id'=>$user_id]);
		
		$c=0;
		$count_replay=array();
		$old_msg=0;
		$t=0;
		foreach($cls_msg_cout as $msg_data)
		{
			$k=0;
			if($t != $msg_data['message_id'])
			{
						
				$rmsgid[]=$msg_data['message_id'];
				$rep=$cls_cout->find()->where(['message_id'=>$msg_data['message_id']]);
		
				foreach($rep as $repdata)
				{
					$k=$k+1;
				}
				$countrep[]=array('mid'=>$msg_data['message_id'],'count'=>$k);
			}			
			$t=$msg_data['message_id'];			
		}
		
		$cls_msg_cout=$cls_cout->find()->where(['sender_id'=>$user_id]);
		
		$c=0;
		$count_replay=array();
		$old_msg=0;
		$t=0;
		foreach($cls_msg_cout as $msg_data)
		{
			$k=0;
			if($t != $msg_data['message_id'])
			{				
				$sender[]=$msg_data['message_id'];
				$rep=$cls_cout->find()->where(['message_id'=>$msg_data['message_id']]);
			
				foreach($rep as $repdata)
				{
					$k=$k+1;
				}
				$countrep[]=array('mid'=>$msg_data['message_id'],'count'=>$k);
			}			
			$t=$msg_data['message_id'];			
		}
		
		$current_date=Time::now();
		$this->set('current_date',$current_date);
		
		$this->set('c',$c);
		
		$class=TableRegistry::get('smgt_message_reciver');
		
		$inboxdata=array();
		$messageid=array();
		
		if(isset($rmsgid))
		{
			foreach($rmsgid as $rid)
			{
				$temp=0;
				if(isset($deleteid))
				{
					foreach($deleteid as $sentdata)
					{								
						if( $sentdata == $rid)
						{						
							$temp=2;
							break;
						}		
					}			
					if($temp == 0)
					{
						$queryreply = $class->find()->where(['message_id' => $rid]);
						$temp=0;
						$countreply=0;
						foreach($queryreply as $rdata)
						{
							if($temp == 0)
							{
								$messageid[]=$rdata['message_id'];
								$inboxdata[]=array(
									'user_name'=>$this->Setting->get_user_id($rdata['sent_id']),					
									'msg_sub'=>$this->Setting->get_message_sub($rdata['message_id']),
									'msg_des'=>$this->Setting->get_message_des($rdata['message_id']),
									'date'=>$rdata['date'],
									'id'=>$rdata['message_id'],
									);
								$temp=1;
							}	
						}
					}
				}
				else
				{
					$queryreply = $class->find()->where(['message_id' => $rid]);
					$temp=0;
					$countreply=0;
					foreach($queryreply as $rdata)
					{						
						$messageid[]=$rdata['message_id'];
						$inboxdata[]=array(
						'user_name'=>$this->Setting->get_user_id($rdata['sent_id']),					
						'msg_sub'=>$this->Setting->get_message_sub($rdata['message_id']),
						'msg_des'=>$this->Setting->get_message_des($rdata['message_id']),
						'date'=>$rdata['date'],
						'id'=>$rdata['message_id'],
							);
					$temp=1;	 
					}					
				}
			}
		}
		if(isset($countrep))
		{
			$this->set('reply_count',$countrep);
		}
		
		$query = $class->find()
				->where(['reciver_id' => $user_id])
				->order(['date' => 'DESC']);
				
		$inboxdatashow = array();
		foreach($query as $usr_nm)
		{
			$inboxdatashow[]=array(
			'user_name'=>$this->Setting->get_user_id($usr_nm['sent_id']),
			'msg_sub'=>$this->Setting->get_message_sub($usr_nm['message_id']),
			'msg_des'=>$this->Setting->get_message_des($usr_nm['message_id']),
			'date'=>$usr_nm['date'],
			'id'=>$usr_nm['message_id'],
			);
		}	
		if(!empty($inboxdatashow))
			$this->set('inboxdata',$inboxdatashow);
	}	
	
	public function sentbox()
	{
		$user_id=$this->request->session()->read('user_id');
		$cls1=TableRegistry::get('smgt_message_reciver');
		
		$cls2=$cls1->find()->where(['reciver_id'=>$user_id,'status'=>0])->count();
				
		$cls_cout=TableRegistry::get('smgt_message_replies');
		$cls_msg_cout=$cls_cout->find()->where(['receiver_id'=>$user_id]);
		
		$c=0;
		$count_replay=array();
		$old_msg=0;
		$t=0;
		foreach($cls_msg_cout as $msg_data)
		{
			$k=0;
			if($t != $msg_data['message_id'])
			{				
				$rmsgid[]=$msg_data['message_id'];
				$rep=$cls_cout->find()->where(['message_id'=>$msg_data['message_id']]);
		
				foreach($rep as $repdata)
				{
					$k=$k+1;
				}
				$countrep[]=array('mid'=>$msg_data['message_id'],'count'=>$k);
			}			
			$t=$msg_data['message_id'];		
		}
		$cls_msg_cout=$cls_cout->find()->where(['sender_id'=>$user_id]);
		
		$c=0;
		$count_replay=array();
		$old_msg=0;
		$t=0;
		foreach($cls_msg_cout as $msg_data)
		{
			$k=0;
			if($t != $msg_data['message_id'])
			{						
				$sender[]=$msg_data['message_id'];
				$rep=$cls_cout->find()->where(['message_id'=>$msg_data['message_id']]);
		
				foreach($rep as $repdata)
				{
					$k=$k+1;
				}
				$countrep[]=array('mid'=>$msg_data['message_id'],'count'=>$k);
			}			
			$t=$msg_data['message_id'];			
		}			
	
		$current_date=Time::now();
		$this->set('current_date',$current_date);
		
		$this->set('p',$cls2);
		$this->set('c',$c);
		
		$class=TableRegistry::get('smgt_message_reciver');
		
		if(isset($countrep)){
		$this->set('reply_count',$countrep);}
		
		$class=TableRegistry::get('smgt_message_sent');
		$query = $class->find()
				->where(['sender_id' => $user_id ,'deleted'=>0])
				->order(['date' => 'DESC']);
				

		foreach($query as $usr_nm)
		{
			if($usr_nm['message_for'] == 'student'|| $usr_nm['message_for'] == 'teacher'|| $usr_nm['message_for'] == 'parent'|| $usr_nm['message_for'] == 'supportstaff')
			{
				$username=$usr_nm['message_for'];	
				
			}
			else
			{
				$username=$this->Setting->get_user_id($usr_nm['message_for']);
			}
						
			$inboxdata[]=array('user_name'=>$username,
				'msg_sub'=>$this->Setting->get_message_sub($usr_nm['message_id']),
				'msg_des'=>$this->Setting->get_message_des($usr_nm['message_id']),
				'date'=>$usr_nm['date'],
				'id'=>$usr_nm['message_id'],
			);
			
		}
		if(isset($inboxdata))
			$this->set('inboxdata',$inboxdata);
	}
	
	public function viewMessage($id)
	{
		$user=TableRegistry::get('hmgt_user');
		$cls=TableRegistry::get('smgt_message_sent');
		$cls_rply=TableRegistry::get('smgt_message_replies');
		
		$current_date=Time::now();
		$this->set('current_date',$current_date);
		
		$message = $cls->get($id);
				
		$current_user_id=$this->request->session()->read('user_id');
		
		$sender_name=$this->Setting->get_user_id($current_user_id);
		$useremail=$user->get($current_user_id);
		$sender_email=$useremail['email'];
		
		$this->set('message',$message);
		$this->set('sender_name',$sender_name);
		$this->set('sender_email',$sender_email);
		$this->set('receiver_name',$message['message_for']);
		$this->set('receiver_nm',$message['message_for']);
		$this->set('message_content',$message['message_body']);
		$this->set('current_user_id',$current_user_id);
		
	}
	public function viewInboxMessage($id)
    {	
		$current_user_id=$this->request->session()->read('user_id');
		if($id)
		{
			$id = $this->Setting->my_simple_crypt($id,'d');	
			
			$cls=TableRegistry::get('smgt_message_reciver');
			$exists = $cls->exists(['message_id' => $id]);
			
			if($exists)
			{
				$this->set('edit',true);
				$user=TableRegistry::get('smgt_users');
				
				$userdata=$user->find();		
				$this->set('userdata',$userdata);
				
				$update=$cls->find()->where(['message_id'=>$id,'reciver_id'=>$current_user_id]);
				
				foreach($update as $updatedata)
				{
					$getupdateid=$updatedata['smgt_reciver_id'];

					$x=$cls->get($getupdateid);
					
					$x['status']=1;
				
					if($cls->save($x))
					{
					
					}
				}
				
				$cls_rply=TableRegistry::get('smgt_message_replies');
				
				$current_date=Time::now();
				$this->set('current_date',$current_date);
					
				$msg=$cls->find()->where(['message_id'=>$id,'reciver_id'=>$current_user_id]);
				if($msg->isEmpty())
				{
					$msg=$cls->find()->where(['message_id'=>$id,'sent_id'=>$current_user_id]);
				
				}
				
				$aj = 0;
				foreach($msg as $data)
				{
							
					$sender_name=$this->Setting->get_user_id($data['sent_id']);
					$sender_email=$this->Setting->get_user_email_id($data['sent_id']);
				
					$receiver_name = $this->Setting->get_user_id($data['reciver_id']);
					$receiver_email=$this->Setting->get_user_email_id($data['reciver_id']);
					
					$sender_nm=$data['sent_id'];
					
					$tbl_receiver_id=$data['smgt_reciver_id'];

					$aj++;
				}

				if($aj > 1)
				{
					$receiver_name = "Group";
					$receiver_email = "Group";
				}
							
				$message_sub=$this->Setting->get_message_sub($id);
				$message_content=$this->Setting->get_message_des($id);
				
				$this->set('id',$id);
				$this->set('sender_name',$sender_name);
				$this->set('sender_email',$sender_email);
				$this->set('receiver_name',$receiver_name);
				$this->set('receiver_email',$receiver_email);
				$this->set('message_sub',$message_sub);
				$this->set('message_content',$message_content);
				$this->set('current_user_id',$current_user_id);
				
				$msg_rply=$this->Setting->smgt_get_all_replies($id);
				
				$this->set('msg_rply',$msg_rply);
						
				if(isset($_POST['replay_message']))
				{
					if($this->request->is('post'))
					{
						$data=$this->request->data;
						
						$a1=$cls_rply->newEntity();
						
						$data['smgt_reciver_id']=$tbl_receiver_id;
						$data['message_id']=$id;
						$data['sender_id']=$current_user_id;
						$data['receiver_id']=$sender_nm;
						$data['message_comment']=$_POST['replay_message_body'];;
						$data['created_date']=$current_date;
						$data['status']=0;
															
						$a1=$cls_rply->patchEntity($a1,$data);
										
						if($cls_rply->save($a1))
						{
							$this->Flash->success(__("Message Reply Successfully"));
							return $this->redirect(['action'=>'viewInboxMessage',$id]);
											
						}
					}
				}
			}
		}
    }
	
	public function delete($id)
	{
		$deleteid=TableRegistry::get('smgt_message_sent');
		$deletedata=$deleteid->get($id);
		$current_user_id=$this->request->session()->read('user_id');
		
		if($deletedata['sender_id'] != $current_user_id)
		{
			$cls=TableRegistry::get('smgt_message_reciver');
			$deletedrecdata=$cls->find()->where(['message_id'=>$id,'reciver_id'=>$current_user_id]);
			
			foreach($deletedrecdata as $message)
			{
					$id=$message['smgt_reciver_id'];
			}
		
			$message = $cls->get($id);
				
			if($cls->delete($message))
				
			{
				$this->Flash->success(__('Message Deleted Successfully', null), 
								'default', 
								 array('class' => 'success'));				
			}			
			return $this->redirect(['action'=>'inbox']);
		}
		else
		{			
			$current_user_id=$this->request->session()->read('user_id');
			
			$cls=TableRegistry::get('smgt_message_sent');
			
			$query=$cls->get($id);
			
			$query['deleted']=1;
			
			if($cls->save($query))
			{
									
			}
			return $this->redirect(['action'=>'inbox']);
		}
	}
	
	public function deleteReply($id = null)
	{
	   if($this->request->is('ajax'))
	   {
			$cls = $_POST['department'];
			
			$clss=TableRegistry::get('smgt_message_replies');
			$message = $clss->get($cls);
			if($clss->delete($message))
			{
				echo __("Reply Deleted Successfully");
			}
		
			die();
		}
	}
	public function msgdeleted($id)
	{
		$current_user_id=$this->request->session()->read('user_id');
		
		$cls=TableRegistry::get('smgt_message_sent');
		
		$query=$cls->get($id);
		
		$query['deleted']=1;
		
		if($cls->save($query))
		{
								
		}
		return $this->redirect(['action'=>'sentbox']);
	}	
}

?>