<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;
use Services_Twilio;
use Cake\Mailer\Email;
class AttendanceController extends AppController
{
	public function initialize()
	{
       parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Flash');
		$this->loadComponent('Et');
		
		require_once(ROOT . DS .'vendor' . DS  . 'twilio' . DS . 'Services' . DS . 'Twilio.php');
		
		$this->stud_date = $this->Setting->getfieldname('date_format');
	}
	public function viewstud($id = null) 
	{	
		$this->autoRender = false;
		
		if($this->request->is('ajax'))
		{
			$sec = $_POST['id'];
			
			$post = TableRegistry::get('smgt_users');
			$data = $post->find()->where(["classsection"=>$sec,'is_deactive'=>0])->hydrate(false)->toArray();
			if(!empty($data))
			{
			?>
				<option value=""> <?php echo __('Select Student'); ?> </option>
				<?php
				foreach($data as $option)
				{
					echo "<option value='{$option['user_id']}'>{$option['first_name']}"." "."{$option['last_name']}</option>";
				}
				die;
			}	
			else
			{
				?>
				<option value=""> <?php echo __('Select Student'); ?> </option>
				<?php
				die;
			}
		}
	}
	public function attendancemonthly()
	{
		$this->set('Attendance','Attendance');
		
		$user_id = $this->request->session()->read('user_id');
		$role = $this->Setting->get_user_role($user_id);
		
		$currt_dt=Time::now();
		$current_date=date("Y-m-d", strtotime($currt_dt));
				
		if(isset($_POST['attendence_date']))
		{
			$this->set('current_date',$_POST['attendence_date']);
		}	
		else
		{
			$this->set('current_date',$current_date);
		}

		$class = TableRegistry::get('Classmgt');

		if($role == 'teacher')
		
		{
			$access = $this->Setting->get_teacher_access();
			$classid = $this->Setting->get_class_list_teacher_id($user_id);

			if($access['chkatted'] == 'own_sub_cls_attend')
				$cls = $class->find("list",["keyField"=>"class_id","valueField"=>"class_name"])->where(['class_id IN'=>$classid]);
			else
				$cls = $class->find("list",["keyField"=>"class_id","valueField"=>"class_name"]);
		}
		else
			$cls = $class->find("list",["keyField"=>"class_id","valueField"=>"class_name"]);
		
		$this->set('class_id',$cls);
		
		$section = TableRegistry::get('class_section');			
		$section_id = $section->find();			
		$this->set('section_id',$section_id);
		
		if(isset($_REQUEST['attendance']))
		{
			$data=$this->request->data();
			
			$current_date_formate=date("Y-m-d", strtotime($current_date));
			
			$this->set('current_date_formate',$current_date_formate);
			
			$class_id=$data['class_id'];
			$this->set('cls_id',$class_id);
			
			$section_id = $data['section'];
			$this->set('sec_id',$section_id);
			
			$student = $data['student'];
			$this->set('stud_id',$student);
			
			$month = $data['attendence_month'];
			$this->set('month',$month);
			
			$year = $data['attendence_year'];
			$this->set('year',$year);
			
			$class1=TableRegistry::get('smgt_users');
			$query1=$class1->find()->where(['classname'=>$class_id,'role'=>'student','classsection'=>$section_id])->hydrate(false)->toArray();
			
			if(!empty($query1))
				$this->set('user',$query1);
			
			$check_attendance = $this->Setting->check_stud_monthly_attendence($student,$month,$year);
			// debug($check_attendance);die;
			if(!empty($check_attendance))
			{
				$cntpresent = $check_attendance[0]['cntpresent'];
				$cntabsent = $check_attendance[0]['cntabsent'];
				$cntlate = $check_attendance[0]['cntlate'];
				$cnttotal = count($check_attendance);
				$this->set('cntpresent',$cntpresent);
				$this->set('cntabsent',$cntabsent);
				$this->set('cntlate',$cntlate);
				$this->set('cnttotal',$cnttotal);
				$this->set('check_attendance',$check_attendance);
			}
			$name = $this->Setting->get_user_id($student);
			$this->set('name',$name);
		}
		
		if(isset($_REQUEST['attendance_report']))
		{
			$cntpresent = $_REQUEST['cntpresent'];
			$cntabsent = $_REQUEST['cntabsent'];
			$cntlate = $_REQUEST['cntlate'];
			$cnttotal = $_REQUEST['cnttotal'];
			$check_attendance = unserialize($_REQUEST['check_attendance']);
		
			$message = '<html><body>';
				$message .= '<br/><table rules="all" style="border-color: #666666;margin-bottom: 30px;" border="1" cellpadding="10">
						<thead>
							<tr style="background: #eeeeee;">
								<th style="color: #000000;">Present Days</th>
								<th style="color: #000000;">Absent Days</th>
								<th style="color: #000000;">Late Days</th>
								<th style="color: #000000;">Total Days</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td align=center>'.$cntpresent.'</td>
								<td align=center>'.$cntabsent.'</td>
								<td align=center>'.$cntlate.'</td>
								<td align=center>'.$cnttotal.'</td>
							</tr>
						</tbody>
					</table>
					<table rules="all" style="border-color: #666666;" border="1" cellpadding="10">
					<thead>
						<tr style="background: #eeeeee;">
							<th>Date</th>
							<th>Day</th>
							<th>Attendance</th>
							<th>Comment</th>
						</tr>
					</thead>
					<tfoot>
					</tfoot>
					<tbody>';
					foreach($check_attendance as $atted)
					{
						$message .= "<tr>
							<td align=center>".date($this->stud_date, strtotime($atted['attendence_date']))."</td>
							<td align=center>".date("D", strtotime($atted['attendence_date']))."</td>
							<td align=center>".$atted['status']."</td>
							<td align=center>".$atted['comment']."</td>
							</tr>";				
					}
					$message .= "</tbody></table><br/><br/>";
					$message .= "</body></html>";

					$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					
					$sys_email=$this->Setting->getfieldname('email'); 
					$school_email = $this->Setting->getfieldname('email');				
					$school_name = $this->Setting->getfieldname('school_name');	
					
					$sys_name = $school_name;
					
					$headers .= 'From:'. $sys_email . "\r\n";
					
					$parents_id = array();
					$parents_id = $this->Setting->get_student_parents_id($check_attendance[0]['user_id']);
					
					if(!empty($parents_id))
					{
						foreach($parents_id as $parent_id)
						{
							$to = $this->Setting->get_user_email_id($parent_id);
							
							@mail($to,_('Monthly Attendance Report'),$message,$headers);
						}
					}
		}
	}
	public function attendance()
	{
		$this->set('Attendance','Attendance');
		
		$user_id = $this->request->session()->read('user_id');
		$role = $this->Setting->get_user_role($user_id);
		
		$currt_dt=Time::now();
		$current_date=date("Y-m-d", strtotime($currt_dt));
				
		if(isset($_POST['attendence_date']))
		{
			$this->set('current_date',$_POST['attendence_date']);
		}	
		else
		{
			$this->set('current_date',$current_date);
		}

		$class = TableRegistry::get('Classmgt');

		if($role == 'teacher')
		{
			$access = $this->Setting->get_teacher_access();
			$classid = $this->Setting->get_class_list_teacher_id($user_id);

			if($access['chkatted'] == 'own_sub_cls_attend')
				$cls = $class->find("list",["keyField"=>"class_id","valueField"=>"class_name"])->where(['class_id IN'=>$classid]);
			else
				$cls = $class->find("list",["keyField"=>"class_id","valueField"=>"class_name"]);
		}
		else
			$cls = $class->find("list",["keyField"=>"class_id","valueField"=>"class_name"]);
		
		$this->set('class_id',$cls);
		
		$section = TableRegistry::get('class_section');			
		$section_id = $section->find();			
		$this->set('section_id',$section_id);
		
		$cls=TableRegistry::get('smgt_attendence');
		$qry=$cls->find();
		$this->set('it',$qry);
				
		$attend_by=$this->request->session()->read('user_id');
		
		$t=0;
		
		if($this->request->is('post'))
		{
			$data=$this->request->data();
			
			$current_date_formate=date("Y-m-d", strtotime($current_date));
			
			$this->set('current_date_formate',$current_date_formate);
			
			$class_id=$data['class_id'];
			$this->set('cls_id',$class_id);
			
			$section_id = $data['section'];
			$this->set('sec_id',$section_id);
			
			$emial_to = "";

			$post_date=$_POST['attendence_date'];	
			
			$class1=TableRegistry::get('smgt_users');
			$query1=$class1->find()->where(['classname'=>$class_id,'role'=>'student','classsection'=>$section_id])->hydrate(false)->toArray();
			
			$check_attendance =$this->Setting->check_attendence($class_id,$post_date);

			if(!empty($check_attendance))
			{
				foreach($check_attendance as $chk_atted)
				{
					$chk_atted_status = $chk_atted['status'];
				}
			}
			if(!empty($check_attendance))
				$this->set('chk_atted_status',$chk_atted_status);
			if(!empty($check_attendance))
				$this->set('check_attendance',$check_attendance);
			
			if(!empty($query1))
				$this->set('user',$query1);
			
			$c_id=$this->Setting->get_class_id($class_id);
			$this->set('c_id',$c_id);
			
			$school_name = $this->Setting->getfieldname('school_name');
			$school_email = $this->Setting->getfieldname('email');
				
			if(isset($_POST['save_attendence']))
			{
			
				foreach($query1 as $user_data)
				{
					
					if(isset($_POST['attendence_'.$user_data['user_id']]))
					{
						$smgt_sms_service_enable=$_REQUEST['smgt_sms_service_enable'];
						 							
						if($smgt_sms_service_enable == 1)
						{	
							$current_sms_service=$this->Setting->getfieldname('select_serveice');
							
							if($_POST['attendence_'.$user_data['user_id']] == 'Absent')
							{	
								$parent_list=array();
								
								$parent_list = $this->Setting->smgt_get_student_parent_id($user_data['user_id']);

								$country = $this->Setting->getfieldname('country');
								$country_code = $this->Setting->get_country_code($country);				

								$reciever_number = "+".$country_code."".$this->Setting->get_user_mobile_no($parent_list);
	
								$message_content = "Your Child ".$this->Setting->get_user_id($user_data['user_id'])." is absent on this date : ".$post_date;													

								$emial_to = $this->Setting->get_user_email_id($parent_list);
								$sys_name = $school_name;
								$sys_email = $school_email;
								$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
								/* @mail($emial_to,_("Attendance Reminder Alert!"),$message_content,$headers); */
																			
								if($current_sms_service == 'clicktell')
								{	
									$to = $reciever_number;
									$message = str_replace(" ","%20",$message_content);
									
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
						
						$check_attendance1 =$this->Setting->check_attendence1($user_data['user_id'],$class_id,$post_date);
						
						if(!$check_attendance1->isEmpty())
						{
							foreach($check_attendance1 as $stud_att)
							{
								$id=$stud_att['attendence_id'];
								$item=$cls->get($id);
								
								$data['user_id']=$user_data['user_id'];
								$data['class_id']=$class_id;
								$data['attend_by']=$attend_by;
								$data['attendence_date']=date("Y-m-d", strtotime($post_date));
								$data['status']=$_POST['attendence_'.$user_data['user_id']];
								$data['role_name']='student';
								$data['comment']=$_POST['attendence_comment_'.$user_data['user_id']];
							
								$a=$cls->patchEntity($item,$data);
								
								if($cls->save($a))
								{
									$t=2;	
									if($data['status']=='Absent')
									{
										$user_id=$data['user_id'];

										$classchild = TableRegistry::get('child_tbl');
										$parent_list=$classchild->find('all')->where(['child_id'=>$user_id])->hydrate(false)->toArray();

										$name=$this->Setting->get_user_id($user_id);
										foreach($parent_list as $p_data)
										{
											$parent_id = $p_data['child_parent_id'];
											$parent_email=$this->Setting->get_user_email_id($parent_id);
										
										
											$subject="";
											
											$sys_email=$this->Setting->getfieldname('email'); 
											$school_name = $this->Setting->getfieldname('school_name');
										
											$mailtem = TableRegistry::get('smgt_emailtemplate');
											$format =$mailtem->find()->where(["find_by"=>"Attendance Absent Notification"])->hydrate(false)->toArray();
											
											$str=$format[0]['template'];
											$subject=$format[0]['subject'];
											
											$msgarray = explode(" ",$str);
											$subarray = explode(" ",$subject);
																			
											$email_id=$parent_email;

											$msgarray['{{child_name}}']=$name;
											$msgarray['{{school_name}}']=$school_name;
											$msgarray['{{attendance_date}}']=date($this->stud_date, strtotime($post_date));

											$subarray['{{child_name}}']=$name;
											$subarray['{{school_name}}']=$school_name;
											$subarray['{{attendance_date}}']=date($this->stud_date, strtotime($post_date));
											
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
						}
						else
						{	
							$a=$cls->newEntity();
							
							$data['user_id']=$user_data['user_id'];
							$data['class_id']=$class_id;
							$data['attend_by']=$attend_by;
							$data['attendence_date']=date("Y-m-d", strtotime($post_date));
							$data['status']=$_POST['attendence_'.$user_data['user_id']];
							$data['role_name']='student';
							$data['comment']=$_POST['attendence_comment_'.$user_data['user_id']];
						
							$a=$cls->patchEntity($a,$data);
							$parent_email = '';
							
							if($cls->save($a))
							{
								$t=1;
								
								if($data['status']=='Absent')
								{
									$user_id=$data['user_id'];
									
									$classchild = TableRegistry::get('child_tbl');
									$parent_list=$classchild->find('all')->where(['child_id'=>$user_id])->hydrate(false)->toArray();
									
									
									$name=$this->Setting->get_user_id($user_id);
									foreach($parent_list as $p_data)
									{
										$parent_id = $p_data['child_parent_id'];
										$parent_email=$this->Setting->get_user_email_id($parent_id);
									
										$sys_email=$this->Setting->getfieldname('email'); 
										$school_name = $this->Setting->getfieldname('school_name');
																			 
										$mailtem = TableRegistry::get('smgt_emailtemplate');
										$format =$mailtem->find()->where(["find_by"=>"Attendance Absent Notification"])->hydrate(false)->toArray();
										
										$str=$format[0]['template'];
										$subject=$format[0]['subject'];
										
										$msgarray = explode(" ",$str);
										$subarray = explode(" ",$subject);
																			
										$email_id=$parent_email;

										$msgarray['{{child_name}}']=$name;
										$msgarray['{{school_name}}']=$school_name;
										$msgarray['{{attendance_date}}']=date($this->stud_date, strtotime($post_date));
										
										$subarray['{{child_name}}']=$name;
										$subarray['{{school_name}}']=$school_name;
										$subarray['{{attendance_date}}']=date($this->stud_date, strtotime($post_date));
										
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
				}
				if($t==1)
				{
					$this->Flash->success(__('Attendance Saved Successfully', null), 
									   'default', 
										array('class' => 'success'));
				}
				
				if($t==2)
				{
					$this->Flash->success(__('Attendance Successfully Updated', null), 
									   'default', 
										array('class' => 'success'));
				}
			}
		}	
	}
		
	public function subjectattendance()
	{
		$this->set('Attendance','Attendance');
		$cls=TableRegistry::get('smgt_sub_attendance');

		$currt_dt=Time::now();
		$current_date=date("Y-m-d", strtotime($currt_dt));
				
		if(isset($_POST['attendence_date']))
		{
			$this->set('current_date',$_POST['attendence_date']);
		}	
		else
		{
			$this->set('current_date',$current_date);
		}
		
		$class = TableRegistry::get('Classmgt');			
		$cls1 = $class->find("list",["keyField"=>"class_id","valueField"=>"class_name"]);			
		$this->set('class_id',$cls1);
		
		$section = TableRegistry::get('class_section');			
		$section_id = $section->find();			
		$this->set('section_id',$section_id);
		
		$class1=TableRegistry::get('smgt_subject');
		$query1=$class1->find();
		
		$attend_by=$this->request->session()->read('user_id');
		
		$t=0;
		
		if($this->request->is('post'))
		{
			$data=$this->request->data();
			
			$current_date_formate=date("Y-m-d", strtotime($current_date));
			
			$this->set('current_date_formate',$current_date_formate);
			
			$class_id=$data['class_id'];
			$subject_id=$data['sub_id'];
			
			$this->set('c_id',$class_id);
			
			$section_id=$data['section'];
			$this->set('sec_id',$section_id);
			
			$sub_set_name=$class1->find()->where(['subid'=>$subject_id]);
			
			foreach($sub_set_name as $name)
			{
				$sub_nm=$name['sub_name'];
			}
			$this->set('s_id',$subject_id);
			$this->set('sub_nm',$sub_nm);
			
			
			$post_date=$_POST['attendence_date'];	
			$this->set('p_date',$post_date);
			
			$class2=TableRegistry::get('smgt_users');
			$query2=$class2->find()->where(['classname'=>$class_id,'role'=>'student','classsection'=>$section_id]);
			
			$this->set('user',$query2);
			
			$c_id=$this->Setting->get_class_id($class_id);
			$this->set('cn_id',$c_id);
			
			$chk_attendance =$this->Setting->check_attendence($class_id,$post_date);
			// debug($chk_attendance);die;
			if(!empty($chk_attendance))
			{
				$this->set('chk_attendance',$chk_attendance);
			}			
			
			$check_attendance = $this->Setting->check_subject_attendence($class_id,$post_date,$subject_id);
			// debug($check_attendance);die;
			$this->set('check_attendance',$check_attendance);
						
			// debug($check_attendance);die;
			if(isset($_POST['save_attendence']))
			{
			
				foreach($query2 as $user_data)
				{
					
					if(isset($_POST['attendence_'.$user_data['user_id']]))
					{
						 $smgt_sms_service_enable=$_REQUEST['smgt_sms_service_enable'];
						 
							
							if($smgt_sms_service_enable == 1)
							{	
								$current_sms_service=$this->Setting->getfieldname('select_serveice');
								
								if($_POST['attendence_'.$user_data['user_id']] == 'Absent')
								{	
									$parent_list=array();
									
									$parent_list = $this->Setting->smgt_get_student_parent_id($user_data['user_id']);
									
										$country = $this->Setting->getfieldname('country');
										$country_code = $this->Setting->get_country_code($country);
											
										$reciever_number = "+".$country_code."".$this->Setting->get_user_mobile_no($parent_list);
										$message_content = "Your Child ".$this->Setting->get_user_id($user_data['user_id'])." is absent today.";													
										
												if($current_sms_service == 'clicktell')
												{	
													$to = $reciever_number;
													$message = str_replace(" ","%20",$message_content);
													
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
													$message);
																				
												}
									}
									
							}
						
					$check_attendance1 =$this->Setting->check_subject_attendence1($user_data['user_id'],$class_id,$post_date,$subject_id);
					
					if(!$check_attendance1->isEmpty())
						{
							
							foreach($check_attendance1 as $stud_att)
							{
								$id=$stud_att['attendance_id'];

								$item=$cls->get($id);

								$data['user_id']=$user_data['user_id'];
								$data['class_id']=$class_id;
								$data['sub_id']=$subject_id;
								$data['attend_by']=$attend_by;
								$data['attendance_date']=date("Y-m-d", strtotime($post_date));
								$data['status']=$_POST['attendence_'.$user_data['user_id']];
								$data['role_name']='student';
								$data['comment']=$_POST['attendence_comment_'.$user_data['user_id']];
							
								$a=$cls->patchEntity($item,$data);
								
								if($cls->save($a))
								{
									$t=2;	

									if($data['status']=='Absent')
									{
										$user_id=$data['user_id'];

										$classchild = TableRegistry::get('child_tbl');
										$parent_list=$classchild->find('all')->where(['child_id'=>$user_id])->hydrate(false)->toArray();

										$name=$this->Setting->get_user_id($user_id);
										foreach($parent_list as $p_data)
										{
											$parent_id = $p_data['child_parent_id'];
											$parent_email=$this->Setting->get_user_email_id($parent_id);
										
											$subject="";
											
											$sys_email=$this->Setting->getfieldname('email'); 
											$school_name = $this->Setting->getfieldname('school_name');
										
											$mailtem = TableRegistry::get('smgt_emailtemplate');
											$format =$mailtem->find()->where(["find_by"=>"Attendance Absent Notification"])->hydrate(false)->toArray();
											
											$str=$format[0]['template'];
											$subject=$format[0]['subject'];
											
											$msgarray = explode(" ",$str);
											$subarray = explode(" ",$subject);
																			
											$email_id=$parent_email;

											$msgarray['{{child_name}}']=$name;
											$msgarray['{{school_name}}']=$school_name;
											$msgarray['{{attendance_date}}']=date($this->stud_date, strtotime($post_date));
											
											$subarray['{{child_name}}']=$name;
											$subarray['{{school_name}}']=$school_name;
											$subarray['{{attendance_date}}']=date($this->stud_date, strtotime($post_date));
											
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
						}
						else
						{	
							$a=$cls->newEntity();
							
							$data['user_id']=$user_data['user_id'];
							$data['class_id']=$class_id;
							$data['sub_id']=$subject_id;
							$data['attend_by']=$attend_by;
							$data['attendance_date']=date("Y-m-d", strtotime($post_date));
							$data['status']=$_POST['attendence_'.$user_data['user_id']];
							$data['role_name']='student';
							$data['comment']=$_POST['attendence_comment_'.$user_data['user_id']];
						
							$a=$cls->patchEntity($a,$data);
							
							if($cls->save($a))
							{
								$t=1;	

								if($data['status']=='Absent')
								{
									$user_id=$data['user_id'];
									
									$classchild = TableRegistry::get('child_tbl');
									$parent_list=$classchild->find('all')->where(['child_id'=>$user_id])->hydrate(false)->toArray();
									
									
									$name=$this->Setting->get_user_id($user_id);
									foreach($parent_list as $p_data)
									{
										$parent_id = $p_data['child_parent_id'];
										$parent_email=$this->Setting->get_user_email_id($parent_id);
																
										$sys_email=$this->Setting->getfieldname('email'); 
										$school_name = $this->Setting->getfieldname('school_name');
																			 
										$mailtem = TableRegistry::get('smgt_emailtemplate');
										$format =$mailtem->find()->where(["find_by"=>"Attendance Absent Notification"])->hydrate(false)->toArray();
										
										$str=$format[0]['template'];
										$subject=$format[0]['subject'];
										
										$msgarray = explode(" ",$str);
										$subarray = explode(" ",$subject);
																			
										$email_id=$parent_email;

										$msgarray['{{child_name}}']=$name;
										$msgarray['{{school_name}}']=$school_name;
										$msgarray['{{attendance_date}}']=date($this->stud_date, strtotime($post_date));
										
										$subarray['{{child_name}}']=$name;
										$subarray['{{school_name}}']=$school_name;
										$subarray['{{attendance_date}}']=date($this->stud_date, strtotime($post_date));
										
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
					}
				}
				if($t==1)
				{
					$this->Flash->success(__('Attendance Saved Successfully', null), 
									   'default', 
										array('class' => 'success'));
				}
				if($t==2)
				{
					$this->Flash->success(__('Attendance Successfully Updated', null), 
									   'default', 
										array('class' => 'success'));
				}
			}
		}	
	}
	public function teacherattendance()
    {	
		$this->set('Attendance','Attendance');
		$class_id = 0;
		
		$currt_dt=Time::now();
		$current_date=date("Y-m-d", strtotime($currt_dt));
			
		if(isset($_POST['attendence_date']))
		{
			$this->set('current_date',$_POST['attendence_date']);
		}	
		else
		{
			$this->set('current_date',$current_date);
		}
				
		$class1=TableRegistry::get('smgt_users');
		$query1=$class1->find()->where(['role'=>'teacher']);
		$this->set('teacher',$query1);
		
		$attend_by=$this->request->session()->read('user_id');
				
		$t=0;
		
		if($this->request->is('post'))
		{
			$data=$this->request->data();
			$post_date=$_POST['attendence_date'];					

			$show_data=$this->Setting->show_attendance($post_date);
			$this->set('show_data',$show_data);
			
			if(isset($_POST['save_teach_attendence']))
			{				
				foreach($query1 as $stud)
				{
					if(isset($_POST['attendence_'.$stud['user_id']]))
					{		
						$cls=TableRegistry::get('smgt_attendence');
						
						$show_data1=$this->Setting->show_attendance1($post_date,$stud['user_id']);
						
						if(!$show_data1->isEmpty())
						{							
							foreach($show_data1 as $data_id)
							{
								$id=$data_id['attendence_id'];
								
								$item=$cls->get($id);

									$data['user_id']=$stud['user_id'];
									$data['class_id']=$class_id;
									$data['attend_by']=$attend_by;
									$data['attendence_date']=date("Y-m-d", strtotime($post_date));
									$data['status']=$_POST['attendence_'.$stud['user_id']];
									$data['role_name']='teacher';
									$data['comment']=$_POST['attendence_comment_'.$stud['user_id']];

									$a=$cls->patchEntity($item,$data);

									if($cls->save($a))
									{	
										$t=2;			
									}
							
								
							}
							
							
						}
						else
						{
							$a=$cls->newEntity();
						
							$data['user_id']=$stud['user_id'];
							$data['class_id']=$class_id;
							$data['attend_by']=$attend_by;
							$data['attendence_date']=date("Y-m-d", strtotime($post_date));
							$data['status']=$_POST['attendence_'.$stud['user_id']];
							$data['role_name']='teacher';
							$data['comment']=$_POST['attendence_comment_'.$stud['user_id']];

							$a=$cls->patchEntity($a,$data);
							
							if($cls->save($a))
							{
								
								$t=1;			
							}
						}
						
					
					}
				}
				if($t==1)
				{
					$this->Flash->success(__('Attendance Saved Successfully', null), 
									   'default', 
										array('class' => 'success'));
				}
				if($t==2)
				{
					$this->Flash->success(__('Attendance Successfully Updated', null), 
									   'default', 
										array('class' => 'success'));
				}
			}
			
		}
		
	}
	
}
?>

