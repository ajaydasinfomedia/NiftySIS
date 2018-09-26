<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use Cake\I18n\Time;


class NoticeController extends AppController{
	
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Et');
		$this->loadComponent('Flash');
		$this->stud_date = $this->Setting->getfieldname('date_format');
	}

	public function view($id = null)
	{
		$this->autoRender=false;
		
		if($this->request->is('ajax'))
		{
			$get_id=$_POST['id'];
			
			$notice_table_register=TableRegistry::get('smgt_notice');
			$get_field=$notice_table_register->get($get_id);									
			?>
			<script>
			$(document).ready(function() {
				var table = $('.viewdetails').removeAttr('width').DataTable( {
					"columns": [
						{ "width": "20%" },
						null
					  ],
					"order": [[ 0, "desc" ]],
					"paging":   false,
					"ordering": false,
					"searching": false,
					"info":     false
				} );
			});
			</script>
			
			<style>
			.modal .modal-content .modal-body{
				padding-top: 0px !important;
			}
			table.dataTable{
				border-collapse: collapse;
				margin-top: 0px !important;
			}
			
			table.dataTable.no-footer,
			table.dataTable thead th
			{
				border-bottom: medium none;
			}
			</style>			
			<table class="table table-striped viewdetails" cellspacing="0" width="100%">
				<thead></thead>	
				<tfoot></tfoot>	
				<tbody>
					<tr>
						<td><?php echo __('Notice Title:'); ?></td>
						<td><?php echo $get_field['notice_title']; ?></td>
					</tr>						
					<tr>
						<td><?php echo __('Notice Comment:'); ?> </td>
						<td><?php echo strlen(($get_field['notice_comment']) > 50)?substr($get_field['notice_comment'],0,50)."...":$get_field['notice_comment'];?></td>
					</tr>								
					<tr>
						<td><?php echo __('Notice For:'); ?></td>
						<td><?php echo $get_field['notice_for']; ?></td>
					</tr>				
					<tr>
						<td><?php echo __('Notice Start Date:'); ?> </td>
						<td><?php echo date($this->stud_date,strtotime($get_field['notice_start_date'])); ?></td>
					</tr>				
					<tr>
						<td><?php echo __('Notice End Date:'); ?> </td>
						<td><?php echo date($this->stud_date,strtotime($get_field['notice_end_date'])); ?></td>
					</tr>
				</tbody>
			</table>						
		<?php
		}
	}

	public function addnotice()
	{
 		$table_class_register=TableRegistry::get('classmgt');
		$get_class=$table_class_register->find();
		$this->set('class_name',$get_class);
		
		/* $table_section_register=TableRegistry::get('class_section');
		$get_section=$table_section_register->find();
		$this->set('section_name',$get_section);*/
		
		$notice_table_register=TableRegistry::get('smgt_notice');
		$class_section_id = 0;
		$emial_to = "";
		
		if($this->request->is('post'))
		{
			$school_name = $this->Setting->getfieldname('school_name');
			$school_email = $this->Setting->getfieldname('email');
			
			$data=$this->request->data;
			
			$role = $data['notice_for'];
			
			if(isset($_REQUEST['which_class']))
					 $class_id = $_REQUEST['which_class'];
				 
			if(isset($_REQUEST['section']))
					 $class_section_id = $_REQUEST['section'];	 
			
			if($role == 'all')
			{
				$userdata = $this->Setting->smgt_get_all_user_notice();
		
				if(!empty($userdata))
				{
					$smgt_sms_service_enable=isset($_REQUEST['sendsms'])?$_REQUEST['sendsms']:0;
				
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
										
							$message_content = $data['message'];
							
							$emial_to = $this->Setting->get_user_email_id($user_id);
							$sys_name = $school_name;
							$sys_email = $school_email;
							$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
							/* @mail($emial_to,_("Notice Reminder Alert!"),$message_content,$headers); */
							
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
							
							$notice_title = $data["notice_title"];
							$notice_comment = $data["notice_comment"];
							$start_date = $data["notice_start_date"];
							$end_date = $data["notice_end_date"];
								
							$sys_email=$this->Setting->getfieldname('email'); 
							$school_name = $this->Setting->getfieldname('school_name');

							$mailtem = TableRegistry::get('smgt_emailtemplate');
							$format =$mailtem->find()->where(["find_by"=>"Notice"])->hydrate(false)->toArray();
							
							$str=$format[0]['template'];
							$subject=$format[0]['subject'];
							
							$msgarray = explode(" ",$str);
							$subarray = explode(" ",$subject);
							
							$email_id=$email;

							$msgarray['{{notice_title}}']=$notice_title;
							$msgarray['{{notice_start_date}}']=$start_date;
							$msgarray['{{notice_end_date}}']=$end_date;
							$msgarray['{{notice_for}}']=$data['notice_for'];
							$msgarray['{{notice_comment}}']=$notice_comment;
							$msgarray['{{school_name}}']=$school_name;
							
							$subarray['{{notice_title}}']=$notice_title;
							$msgarray['{{notice_start_date}}']=$start_date;
							$msgarray['{{notice_end_date}}']=$end_date;
							$subarray['{{notice_for}}']=$data['notice_for'];
							$subarray['{{notice_comment}}']=$notice_comment;
							$subarray['{{school_name}}']=$school_name;

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
								
							/* $email_from = "Niftyschool@school.com"; // Who the email is from  
							$email_subject = "Niftyschool: Event Notification"; // The Subject of the email  
							$email_message = "Sir / Madam / Dear Student,<br>";
							$email_message .= "<p>We are going to organize event <strong>{$notice_title}</strong> in our school.</p>";
							$email_message .= "<p>Here information about event.</p>";
							$email_message .= "<p><strong>Start Date :</strong>{$start_date}.</p>";
							$email_message .= "<p><strong>End Date :</strong>{$end_date}.</p>";
							$email_message .= "<p><strong>Comment :</strong>{$notice_comment}.</p><br>";
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
					
							$ok = @mail($email_to, $email_subject, $email_message, $headers); */
						}
					}
									
				}
			}
			else if($role == 'parent' || $role == 'student' || $role == 'teacher' || $role == 'supportstaff' )
			{
				
				$userdata=$this->Setting->smgt_get_user_notice($role,$_REQUEST['which_class'],$class_section_id);
				
				if(!empty($userdata))
				{
					$smgt_sms_service_enable=isset($_REQUEST['sendsms'])?$_REQUEST['sendsms']:0;
			
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
						
							$message_content = $data['message'];
							
							$emial_to = $this->Setting->get_user_email_id($user_id);
							$sys_name = 'NiftySchool';
							$sys_email = 'nifty@school.com';
							$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
						
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
				}
				$mail_service_enable=isset($_REQUEST['sendmail'])?$_REQUEST['sendmail']:0;
				if($mail_service_enable == 1)
				{
					$user_ids=$this->Setting->smgt_get_event_mailer($role,$_REQUEST['which_class'],$class_section_id);
				
					if(!empty($user_ids))
					{
						foreach($user_ids as $user)
						{
							$email = $this->Setting->get_user_email_id($user);
							
							$notice_title = $data["notice_title"];
							$notice_comment = $data["notice_comment"];
							$start_date = $data["notice_start_date"];
							$end_date = $data["notice_end_date"];
								
							/* $email_from = "Niftyschool@school.com"; // Who the email is from  
							$email_subject = "Niftyschool: Event Notification"; // The Subject of the email  
							$email_message = "Sir / Madam / Dear Student,<br>";
							$email_message .= "<p>We are going to organize event <strong>{$notice_title}</strong> in our school.</p>";
							$email_message .= "<p>Here information about event.</p>";
							$email_message .= "<p><strong>Start Date :</strong>{$start_date}.</p>";
							$email_message .= "<p><strong>End Date :</strong>{$end_date}.</p>";
							$email_message .= "<p><strong>Comment :</strong>{$notice_comment}.</p><br>";
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

							$ok = @mail($email_to, $email_subject, $email_message, $headers); */
														
							$sys_email=$this->Setting->getfieldname('email'); 
							$school_name = $this->Setting->getfieldname('school_name');

							$mailtem = TableRegistry::get('smgt_emailtemplate');
							$format =$mailtem->find()->where(["find_by"=>"Notice"])->hydrate(false)->toArray();
							
							$str=$format[0]['template'];
							$subject=$format[0]['subject'];
							
							$msgarray = explode(" ",$str);
							$subarray = explode(" ",$subject);
							
							$email_id=$email;

							$msgarray['{{notice_title}}']=$notice_title;
							$msgarray['{{notice_start_date}}']=$start_date;
							$msgarray['{{notice_end_date}}']=$end_date;
							$msgarray['{{notice_for}}']=$data['notice_for'];
							$msgarray['{{notice_comment}}']=$notice_comment;
							$msgarray['{{school_name}}']=$school_name;
							
							$subarray['{{notice_title}}']=$notice_title;
							$msgarray['{{notice_start_date}}']=$start_date;
							$msgarray['{{notice_end_date}}']=$end_date;
							$subarray['{{notice_for}}']=$data['notice_for'];
							$subarray['{{notice_comment}}']=$notice_comment;
							$subarray['{{school_name}}']=$school_name;

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
			}
			
			$notice_table_entity = $notice_table_register->newEntity();
			
			$data_ar = array();
			
			$notice_title = '';
			$notice_start_date = '';
			$notice_comment = '';
			
			$notice_title = $data['notice_title'];
			$notice_start_date = $data['notice_start_date'];
			$notice_end_date = $data['notice_end_date'];
			$notice_comment = $data['notice_comment'];
			
			$data_ar['notice_title'] = $data['notice_title'];
			$data_ar['notice_comment'] = $data['notice_comment'];
			$data_ar['notice_start_date'] = date("Y-m-d", strtotime($data['notice_start_date']));;
			$data_ar['notice_end_date'] = date("Y-m-d", strtotime($data['notice_end_date']));;
			$data_ar['notice_for'] = $role;
			$data_ar['which_class'] = $class_id;
			$data_ar['section'] = $class_section_id;
			
			$data_add = $notice_table_register->patchEntity($notice_table_entity,$data_ar);

			if($notice_table_register->save($data_add))
			{
				$this->Flash->success(__('Notice added Successfully', null), 
									'default', 
									 array('class' => 'success'));

							
				if($role == 'student')
				{
					$data = $this->Setting->get_users_data_rollwise('student');
					foreach($data as $data1)
					{

						$name=$data1['first_name']." ".$data1['last_name'];
						$emailrol=$data1['email'];
						$notice_title=$notice_title;
						$start_date=$notice_start_date;
						$end_date=$notice_end_date;
						
						$sys_email=$this->Setting->getfieldname('email'); 
						$school_name = $this->Setting->getfieldname('school_name');

						$mailtem = TableRegistry::get('smgt_emailtemplate');
						$format =$mailtem->find()->where(["find_by"=>"Notice"])->hydrate(false)->toArray();
						
						$str=$format[0]['template'];
						$subject=$format[0]['subject'];
						
						$msgarray = explode(" ",$str);
						$subarray = explode(" ",$subject);
						
						$email_id=$emailrol;

						$msgarray['{{notice_title}}']=$notice_title;
						$msgarray['{{notice_start_date}}']=$start_date;
						$msgarray['{{notice_end_date}}']=$end_date;
						$msgarray['{{notice_for}}']=$data_ar['notice_for'];
						$msgarray['{{notice_comment}}']=$notice_comment;
						$msgarray['{{school_name}}']=$school_name;
						
						$subarray['{{notice_title}}']=$notice_title;
						$msgarray['{{notice_start_date}}']=$start_date;
						$msgarray['{{notice_end_date}}']=$end_date;
						$subarray['{{notice_for}}']=$data_ar['notice_for'];
						$subarray['{{notice_comment}}']=$notice_comment;
						$subarray['{{school_name}}']=$school_name;
						
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
				elseif($role == 'teacher')
				{
					$data = $this->Setting->get_users_data_rollwise('teacher');
					foreach($data as $data1)
					{

						$name=$data1['first_name']." ".$data1['last_name'];
						$emailrol=$data1['email'];
						$notice_title=$notice_title;
						$start_date=$notice_start_date;
						$end_date=$notice_end_date;
						
						$sys_email=$this->Setting->getfieldname('email'); 
						$school_name = $this->Setting->getfieldname('school_name');

						$mailtem = TableRegistry::get('smgt_emailtemplate');
						$format =$mailtem->find()->where(["find_by"=>"Notice"])->hydrate(false)->toArray();
						
						$str=$format[0]['template'];
						$subject=$format[0]['subject'];
						
						$msgarray = explode(" ",$str);
						$subarray = explode(" ",$subject);
						
						$email_id=$emailrol;

						$msgarray['{{notice_title}}']=$notice_title;
						$msgarray['{{notice_start_date}}']=$start_date;
						$msgarray['{{notice_end_date}}']=$end_date;
						$msgarray['{{notice_for}}']=$data_ar['notice_for'];
						$msgarray['{{notice_comment}}']=$notice_comment;
						$msgarray['{{school_name}}']=$school_name;
						
						$subarray['{{notice_title}}']=$notice_title;
						$msgarray['{{notice_start_date}}']=$start_date;
						$msgarray['{{notice_end_date}}']=$end_date;
						$subarray['{{notice_for}}']=$data_ar['notice_for'];
						$subarray['{{notice_comment}}']=$notice_comment;
						$subarray['{{school_name}}']=$school_name;

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
				elseif($role == 'parent')
				{
					$data = $this->Setting->get_users_data_rollwise('parent');
					foreach($data as $data1)
					{

						$name=$data1['first_name']." ".$data1['last_name'];
						$emailrol=$data1['email'];
						$notice_title=$notice_title;
						$start_date=$notice_start_date;
						$end_date=$notice_end_date;
						
						$sys_email=$this->Setting->getfieldname('email'); 
						$school_name = $this->Setting->getfieldname('school_name');

						$mailtem = TableRegistry::get('smgt_emailtemplate');
						$format =$mailtem->find()->where(["find_by"=>"Notice"])->hydrate(false)->toArray();
						
						$str=$format[0]['template'];
						$subject=$format[0]['subject'];
						
						$msgarray = explode(" ",$str);
						$subarray = explode(" ",$subject);
						
						$email_id=$emailrol;

						$msgarray['{{notice_title}}']=$notice_title;
						$msgarray['{{notice_start_date}}']=$start_date;
						$msgarray['{{notice_end_date}}']=$end_date;
						$msgarray['{{notice_for}}']=$data_ar['notice_for'];
						$msgarray['{{notice_comment}}']=$notice_comment;
						$msgarray['{{school_name}}']=$school_name;
						
						$subarray['{{notice_title}}']=$notice_title;
						$msgarray['{{notice_start_date}}']=$start_date;
						$msgarray['{{notice_end_date}}']=$end_date;
						$subarray['{{notice_for}}']=$data_ar['notice_for'];
						$subarray['{{notice_comment}}']=$notice_comment;
						$subarray['{{school_name}}']=$school_name;

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
				else
				{
					if($role == 'supportstaff')
					{
						$data = $this->Setting->get_users_data_rollwise('supportstaff');
						foreach($data as $data1)
						{

							$name=$data1['first_name']." ".$data1['last_name'];
							$emailrol=$data1['email'];
							$notice_title=$notice_title;
							$start_date=$notice_start_date;
							$end_date=$notice_end_date;
							
							$sys_email=$this->Setting->getfieldname('email'); 
							$school_name = $this->Setting->getfieldname('school_name');

							$mailtem = TableRegistry::get('smgt_emailtemplate');
							$format =$mailtem->find()->where(["find_by"=>"Notice"])->hydrate(false)->toArray();
							
							$str=$format[0]['template'];
							$subject=$format[0]['subject'];
							
							$msgarray = explode(" ",$str);
							$subarray = explode(" ",$subject);
							
							$email_id=$emailrol;

							$msgarray['{{notice_title}}']=$notice_title;
							$msgarray['{{notice_start_date}}']=$start_date;
							$msgarray['{{notice_end_date}}']=$end_date;
							$msgarray['{{notice_for}}']=$data_ar['notice_for'];
							$msgarray['{{notice_comment}}']=$notice_comment;
							$msgarray['{{school_name}}']=$school_name;
							
							$subarray['{{notice_title}}']=$notice_title;
							$msgarray['{{notice_start_date}}']=$start_date;
							$msgarray['{{notice_end_date}}']=$end_date;
							$subarray['{{notice_for}}']=$data_ar['notice_for'];
							$subarray['{{notice_comment}}']=$notice_comment;
							$subarray['{{school_name}}']=$school_name;

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
			return $this->redirect(['controller'=>'Notice','action'=>'noticelist']);
		}
	}
	
	public function noticemultidelete() 
		{
			$this->autoRender = false;
			$id=json_decode($_REQUEST[n_id]);
			foreach($id as $recordid)
				{
						$class = TableRegistry::get('smgt_notice');
						
						$item =$class->get($recordid);

						if($class->delete($item))
						{
							
						}	
				}
		}
	
	public function delete($id){

			$notice_table_register=TableRegistry::get('smgt_notice');

			$this->request->is(['post','delete']);

			$items=$notice_table_register->get($id);
			if($notice_table_register->delete($items))
			{
				$this->Flash->success(__('Notice Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			}
			return $this->redirect(['action'=>'noticelist']);
	}

	public function noticelist(){

		$notice_table_register=TableRegistry::get('smgt_notice');
		$class_table_register=TableRegistry::get('classmgt');

		$fetch_data=$notice_table_register->find();
		$class_data=$class_table_register->find();
		$this->set('rows',$fetch_data);
		$this->set('class_row',$class_data);
	}


	public function updatenotice($id)
	{
		if($id)
		{
			$id = $this->Setting->my_simple_crypt($id,'d');	
			
			$table_class_register=TableRegistry::get('classmgt');
			$get_class=$table_class_register->find();
			$this->set('class_name',$get_class);
			
			$table_section_register=TableRegistry::get('class_section');
			$get_section=$table_section_register->find();
			$this->set('section_name',$get_section);

			$notice_table_register=TableRegistry::get('smgt_notice');
			$exists = $notice_table_register->exists(['notice_id' => $id]);
			
			if($exists)
			{
				$get_items=$notice_table_register->get($id);

				if($this->request->is('post')){
					
					$data=$this->request->data;
					
					$role = $data['notice_for'];
					
					if(isset($_REQUEST['which_class']))
							 $class_id = $_REQUEST['which_class'];
						 
					if(isset($_REQUEST['section']))
							 $class_section_id = $_REQUEST['section'];
							 
					if($role == 'all')
					{
						$userdata = $this->Setting->smgt_get_all_user_notice();
				
						if(!empty($userdata))
						{
							$mail_service_enable=isset($_REQUEST['sendmail'])?$_REQUEST['sendmail']:0;
							if($mail_service_enable == 1)
							{
								foreach($userdata as $user)
								{
									$email = $this->Setting->get_user_email_id($user);
									if($email != "")
									{
										$notice_title = $data["notice_title"];
										$notice_comment = $data["notice_comment"];
										$start_date = $data["notice_start_date"];
										$end_date = $data["notice_end_date"];						
										
										$email_from = "Niftyschool@school.com"; // Who the email is from  
										$email_subject = "Niftyschool: Event Notification"; // The Subject of the email  
										$email_message = "Sir / Madam / Dear Student,<br>";
										$email_message .= "<p>We are going to organize event <strong>{$notice_title}</strong> in our school.</p>";
										$email_message .= "<p>Here information about event.</p>";
										$email_message .= "<p><strong>Start Date :</strong>{$start_date}.</p>";
										$email_message .= "<p><strong>End Date :</strong>{$end_date}.</p>";
										$email_message .= "<p><strong>Comment :</strong>{$notice_comment}.</p><br>";
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
					}
					else if($role == 'parent' || $role == 'student' || $role == 'teacher' || $role == 'supportstaff' )
					{
						$mail_service_enable=isset($_REQUEST['sendmail'])?$_REQUEST['sendmail']:0;
						if($mail_service_enable == 1)
						{						
							$user_ids=$this->Setting->smgt_get_event_mailer($role,$_REQUEST['which_class'],$class_section_id);
							
							if(!empty($user_ids))
							{
								foreach($user_ids as $user)
								{
									$email = $this->Setting->get_user_email_id($user);
									if($email != "")
									{
										$notice_title = $data["notice_title"];
										$notice_comment = $data["notice_comment"];
										$start_date = $data["notice_start_date"];
										$end_date = $data["notice_end_date"];														
										
										$email_from = "Niftyschool@school.com"; // Who the email is from  
										$email_subject = "Niftyschool: Event Notification"; // The Subject of the email  
										$email_message = "Sir / Madam / Dear Student,<br>";
										$email_message .= "<p>We are going to organize event <strong>{$notice_title}</strong> in our school.</p>";
										$email_message .= "<p>Here information about event.</p>";
										$email_message .= "<p><strong>Start Date :</strong>{$start_date}.</p>";
										$email_message .= "<p><strong>End Date :</strong>{$end_date}.</p>";
										$email_message .= "<p><strong>Comment :</strong>{$notice_comment}.</p><br>";
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
					}
					
					$data = $this->request->data;
					$data['notice_start_date'] = date('Y-m-d', strtotime($this->request->data('notice_start_date')));
					$data['notice_end_date'] = date('Y-m-d', strtotime($this->request->data('notice_end_date')));
					$update_data=$notice_table_register->patchEntity($get_items,$data);

					if($notice_table_register->save($update_data))
					{
						$this->Flash->success(__('Notice Updated Successfully', null), 
									'default', 
									 array('class' => 'alert alert-success'));
									 
						return $this->redirect(['controller'=>'Notice','action'=>'noticelist']);
					}	
					else
					{
						echo 'something wrong in update page';
					}
				}
				$this->set('row',$get_items);
			}
			else
				return $this->redirect(['controller'=>'Notice','action'=>'noticelist']);
		}
		else
			return $this->redirect(['controller'=>'Notice','action'=>'noticelist']);
	}

}


?>