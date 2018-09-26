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
use Cake\Core\App;


class AdmissionController extends AppController
{	
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Et');
		$this->loadComponent('Flash');		
	}
   
	public function registration()
	{
		$user_id = $this->request->session()->read('user_id');
		
		$this->set('Student','Student');
		
		$country=$this->Setting->getfieldname('country');		
		$country_code=$this->Setting->get_country_code($country);
		$this->set('country_code',$country_code);
		
		$smgt_admission_main = TableRegistry::get('smgt_admission_main');
		$smgt_admission_main_find = $smgt_admission_main->find()->where(['meta_key'=>'previous_school','is_active'=>0]);
		$this->set('admission_previous_school',$smgt_admission_main_find);
		
		$conn = ConnectionManager::get('default');
		$result = $conn->query("SHOW TABLE STATUS LIKE 'smgt_admission';")->fetchAll('assoc');
		$next = $result[0]['Auto_increment'];
		$this->set('next_id',$next);
		
		if($this->request->is('post'))
		{
			$class1 = TableRegistry::get('smgt_admission'); 			
			$email=$this->request->data['email'];							
			$check_email = $class1->find()->where(['email'=>$email]);									
				
			if (!$check_email->isEmpty()) 
			{
				$this->Flash->success(__('Duplicate Email id'));			
				$this->set('post_data',$this->request->data);
				return $this->redirect(['controller' => 'Admission','action'=>'registration']);	
			}
			else
			{
				$a = $class1->newEntity();
				
				$c1=$this->request->data;
				
				$c1['role']='student';
				$c1['admission_date']=date("Y-m-d", strtotime($c1['admission_date']));
				$c1['date_of_birth']=date("Y-m-d", strtotime($c1['date_of_birth']));
				
				if($c1['preschoolname'] == 'other')
				{
					$c1['preschoolname']= $c1['preschoolother'];
				}
				else
				{
					$c1['preschoolname'];
				}
				
				$c1['siblingsone'] = NULL;
				$c1['siblingstwo'] = NULL;
				$c1['siblith'] = NULL;
				
				if(isset($this->request->data['siblingsone']))
				{
					$c1['siblingsone']=$this->request->data(['siblingsone']);
					$c1['siblingsone']=implode(',',$c1['siblingsone']);
				}
				
				if(isset($this->request->data['siblingstwo']))
				{
					$c1['siblingstwo']=$this->request->data(['siblingstwo']);
					$c1['siblingstwo']=implode(',',$c1['siblingstwo']);
				}
									
				if(isset($this->request->data['siblingsthree']))
				{
					$c1['siblith']=$this->request->data(['siblingsthree']);
					$c1['siblith']=implode(',',$c1['siblith']);
				}
				
				$c1['fathersalutation'];
				$c1['mothersalutation'];
				$c1['fatherfn'];
				$c1['motherfn'];
				$c1['fathermn'];
				$c1['mothermn'];
				$c1['fatherln'];
				$c1['motherln'];
				$c1['fatheremail'];
				$c1['motheremail'];
				$c1['fathermob'];
				$c1['mothermob'];
				
				/* if($c1['fatherschool'] == 'other')
				{
					$c1['fatherschool']= $c1['fatherschoolother'];
				}
				else
				{
					$c1['fatherschool'];
				}				
				
				if($c1['motherschool'] == 'other')
				{
					$c1['motherschool']= $c1['motherschoolother'];
				}
				else
				{
					$c1['motherschool'];
				} */
				
				if($c1['fathermedium'] == 'other')
				{
					$c1['fathermedium']= $c1['fathermediumother'];
				}
				else
				{
					$c1['fathermedium'];
				}
				
				if($c1['mothermedium'] == 'other')
				{
					$c1['mothermedium']= $c1['abc'];
				}
				else
				{
					$c1['mothermedium'];
				}
				
				$c1['fatherhighest'];
				$c1['motherhighest'];
				$c1['fatheincome'];
				$c1['motherincome'];
				
				if($c1['fatheroccu'] == 'other')
				{
					$c1['fatheroccu']= $c1['myoccupa'];
				}
				else
				{
					$c1['fatheroccu'];
				}
				
				if($c1['motheroccu'] == 'other')
				{
					$c1['motheroccu']= $c1['occupationmother'];
				}
				else
				{
					$c1['motheroccu'];
				}
				
				$c1['status']='Not Approved';
				
				$docu=$this->Setting->getdoc($c1['fatdocume']);

				if($docu=='')
				{
					$c1['fatdocume']="profile.jpg";
				}
				else
				{
					$c1['fatdocume']=$this->request->data('fatdocume');
					$c1['fatdocume']=$c1['fatdocume']['name'];					
				}
				
				$doc=$this->Setting->getdoc($c1['motdocume']);
				
				if($doc=='')
				{
					$c1['motdocume']="profile1.png";					
				}
				else
				{
					$c1['motdocume']=$this->request->data('motdocume');
					$c1['motdocume']=$c1['motdocume']['name'];					
				}
				
				$admission_no = $c1['admission_no'];
				$email_id = $c1['email'];
				$name = $c1['first_name']." ".$c1['last_name'];
				$address = $c1['address'].", ".$c1['city'].", ".$c1['zip_code'];
				
				$a=$class1->patchEntity($a,$c1);

				if($class1->save($a))
				{
					
					$sys_email=$this->Setting->getfieldname('email'); 
					$school_name = $this->Setting->getfieldname('school_name');
					
					if($email_id != '')
					{
						$email = new Email('default');
						$to = $email_id;	
						$submsg = "Admission Application Submitted";	
						$message = "Your Application Submitted"."\n"."\n".
									"Admission Number : ".$admission_no."\n" .
									"Name : ".$name."\n" .
									"Email : ".$email_id."\n" .
									"Address : ".$address.
									"\n"."\n".
									"Regards From ".$school_name;
											
						$sys_name = $school_name;
						$sys_email = $sys_email;
						$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
						@mail($to,_($submsg),$message,$headers);

					}
					
					$this->Flash->success(__('Registration Successful', null), 
							'default', 
							 array('class' => 'success'));
				}
				if(!$user_id)
					return $this->redirect(['controller' => 'User','action'=>'user']);
				else
					return $this->redirect(['controller' => 'Admission','action'=>'admissionlist']);
			}			
		}		
	}
	
	public function userdetails($id= null)
	{
		if($id)
		{
			$id = $this->Setting->my_simple_crypt($id,'d');
			
			$class = TableRegistry::get('smgt_admission');
			$exists = $class->exists(['admission_id' => $id]);
			if($exists)
			{
				$item = $class->get($id);
				$this->set('data',$item);
			}
		}
	}
	
	public function admissionmultidelete() 
	{
		$this->autoRender = false;
		if($this->request->is('ajax'))
		{
		$id=json_decode($_REQUEST[a_id]);

		foreach($id as $recordid)
			{
					$admi = TableRegistry::get('smgt_admission');
					
					$item =$admi->get($recordid);
					$item['adminssion_status'] = 1;
					if($admi->save($item))
					{
					
					}	
			}
		}
	}
	
	public function admissionlist()
	{
		
		$admission_table_register=TableRegistry::get('smgt_admission');
		$fetch_data=$admission_table_register->find()->where(['adminssion_status'=>0]);
		$this->set('it',$fetch_data);
	}
	
	public function sendmail()
	{			
		$this->autoRender = false;
		$link = "http://" . $_SERVER['HTTP_HOST'].$this->request->base;
		
		if($this->request->is('post'))
		{
			$admission_table_register=TableRegistry::get('smgt_admission');
			$post = $this->request->data();
			if(isset($post["go"]))
			{
				$data = $this->request->data();
				$a_id = $data['email'];
						
				$users = TableRegistry::get('smgt_users');
					
				$uname=$data['username'];
				$upass=$data['password'];
				 
				$check_username = $users->find()->where(['username'=>$uname]); 	
				$check_email = $users->find()->where(['email'=>$uname]); 	
				
				if (!$check_username->isEmpty() || !$check_email->isEmpty()) 
				{
					$this->Flash->success(__('Duplicate Username/Email'));
					$this->set('post_data',$data);	
							
				}
				else
				{
					if($a_id != '')		
					{
						$email = new Email('default');
						$to = $a_id;

						$sys_email=$this->Setting->getfieldname('email'); 
						$sys_name = $this->Setting->getfieldname('school_name');
						
						$message = "Your Admission is Approved"."\n"."\n".
									"Your Username: ".$data['username']."\n" .
									"Your Password: ".$data['password']."\n" .
									"Login URL: ".$link.
									"\n"."\n".
									"Regards From ".$sys_name;
						
						$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
						
						/* $email->from([$sys_email => $sys_name])
						->to($to)
						->subject( _("Admission Approved"))
						->send("Your Admission is Approved"."\n"."\n".
						"Your Username: ".$data['username']."\n" .
						"Your Password: ".$data['password']."\n" .
						"Login URL: ".$link.
						"\n"."\n".
						"Regards From ".$sys_name); */

						@mail($to,_("Admission Approved"),$message,$headers);
					}
					
					$studentID = $this->Setting->generate_studentID();
					
					$admi_id =$data['admission_id'];
					$fetch_data1=$admission_table_register->find()->where(['admission_id'=>$admi_id])->hydrate(false)->toArray();
					
					$abc = $users->newEntity();
					$abc['first_name'] = $fetch_data1[0]['first_name'];
					$abc['middle_name'] = $fetch_data1[0]['middle_name'];
					$abc['last_name'] = $fetch_data1[0]['last_name'];
					$abc['gender'] = $fetch_data1[0]['gender'];
					$abc['date_of_birth'] = $fetch_data1[0]['date_of_birth'];
					$abc['address'] = $fetch_data1[0]['address'];
					$abc['city'] = $fetch_data1[0]['city'];
					$abc['state'] = $fetch_data1[0]['state'];
					$abc['zip_code'] = $fetch_data1[0]['zip_code'];
					$abc['mobile_no'] = $fetch_data1[0]['mobile_no'];
					$abc['phone'] = $fetch_data1[0]['phone'];
					$abc['email'] = $fetch_data1[0]['email'];
					$abc['username'] = $uname;
					
					$hasher = new DefaultPasswordHasher();
					$pass = $hasher->hash($upass);
					$abc['password']=$pass;

					$abc['role'] = $fetch_data1[0]['role'];
					$abc['status'] = "Approved";
					$abc['docume'] = null;
					$abc['image'] = "profile.jpg";
					$abc['studentID'] = $studentID['studentID'];
					$abc['studentID_prefix'] = $studentID['studentID_prefix'];
							
					$adminss = TableRegistry::get('smgt_admission');
					$admin_id =$data['admission_id'];
					$fetch1=$adminss->get($admin_id);
					$fetch1['adminssion_status'] = 1;
					
					if($adminss->save($fetch1))
					{
						
					}				
					if($users->save($abc))
					{
								$this->Flash->success(__('Admission Approved Successfully', null), 
										'default', 
										 array('class' => 'success'));
					}
				}
			}
			
			return $this->redirect(['controller' => 'Admission','action'=>'admissionlist']);
		}				
	}
		
	public function approve($id= null)
	{	
		$this->autoRender = false;
		if($this->request->is('ajax'))
		{
			$id=$_POST['id'];
			$class = TableRegistry::get('smgt_admission');
			$u_id=$class->get($id);
			$a_id=$u_id['email'];
			$admi_id=$u_id['admission_id'];
		?>
		<br>
		  <br> 
			<div class="col-md-12 col-sm-12 col-xs-12">
			<form class="form-horizontal" method="post" action="<?php echo $this->request->base;?>/Admission/sendmail">
			<input type="hidden" class="form-control" name="admission_id" value="<?php echo $admi_id ?>">
				<div class="form-group">
				  <label class="control-label col-md-3 col-sm-6 col-xs-12" > <?php echo __('Username/Email :'); ?> </label>
				  <div class="col-md-9 col-sm-6 col-xs-12">
					<input type="text" class="form-control" name="username" value="<?php echo $a_id;?>" readonly>
				  </div>
				</div>
				<div class="form-group">
				  <label class="control-label col-md-3 col-sm-6 col-xs-12"> <?php echo __('Password :'); ?><span style="color:red;"><?php echo " *"; ?></span></label>
				  <div class="col-md-9 col-sm-6 col-xs-12">          
					<input type="password" class="form-control validate[required]" name="password" required>
					<input type="hidden" class="form-control" name="email" value="<?php echo $a_id ?>">
				  </div>
				</div>
				
				<div class="form-group">        
				  <div class="col-sm-offset-3 col-md-9 col-sm-6 col-xs-12">
					<button type="submit" name="go" class="btn btn-default"><?php echo __('Send'); ?> </button>
				  </div>
				</div>
				
			</form>
			</div>	
		<?php
		}
	}
	public function viewsectionlist()
	{
		if(!empty($_REQUEST['class_id']))
		{
			$class = $_REQUEST['class_id'];
			$class_data = '';
			$header = __('Previous School');
			$this->set('header',$header);
			
			$class_tbl = TableRegistry::get('smgt_admission_main');
			$class_data = $class_tbl->find()->where(['meta_key'=>$class,'is_active'=>0]);
			$this->set('model_data',$class_data);
			$this->set('model',$class);
		}		
	}
	public function viewstandardlist()
	{
		if(!empty($_REQUEST['class_id']))
		{
			$class = $_REQUEST['class_id'];
			$class_data = '';
			$header = __('Standard List');
			$this->set('header',$header);
			
			$class_tbl = TableRegistry::get('smgt_admission_main');
			$class_data = $class_tbl->find()->where(['meta_key'=>$class,'is_active'=>0]);
			$this->set('model_data',$class_data);
			$this->set('model',$class);
		}		
	}
	public function viewparentschoollist()
	{
		if(!empty($_REQUEST['class_id']))
		{
			$class = $_REQUEST['class_id'];
			$class_data = '';
			$header = __('Parent School List');
			$this->set('header',$header);
			
			$class_tbl = TableRegistry::get('smgt_admission_main');
			$class_data = $class_tbl->find()->where(['meta_key'=>$class,'is_active'=>0]);
			$this->set('model_data',$class_data);
			$this->set('model',$class);
		}		
	}
	public function viewmediumlist()
	{
		if(!empty($_REQUEST['class_id']))
		{
			$class = $_REQUEST['class_id'];
			$class_data = '';
			$header = __('School Medium List');
			$this->set('header',$header);
			
			$class_tbl = TableRegistry::get('smgt_admission_main');
			$class_data = $class_tbl->find()->where(['meta_key'=>$class,'is_active'=>0]);
			$this->set('model_data',$class_data);
			$this->set('model',$class);
		}		
	}
	public function viewqualificationlist()
	{
		if(!empty($_REQUEST['class_id']))
		{
			$class = $_REQUEST['class_id'];
			$class_data = '';
			$header = __('Qualification List');
			$this->set('header',$header);
			
			$class_tbl = TableRegistry::get('smgt_admission_main');
			$class_data = $class_tbl->find()->where(['meta_key'=>$class,'is_active'=>0]);
			$this->set('model_data',$class_data);
			$this->set('model',$class);
		}		
	}
	public function viewoccupationlist()
	{
		if(!empty($_REQUEST['class_id']))
		{
			$class = $_REQUEST['class_id'];
			$class_data = '';
			$header = __('Occupation List');
			$this->set('header',$header);
			
			$class_tbl = TableRegistry::get('smgt_admission_main');
			$class_data = $class_tbl->find()->where(['meta_key'=>$class,'is_active'=>0]);
			$this->set('model_data',$class_data);
			$this->set('model',$class);
		}		
	}
	public function addnewsection()
	{
		$class_section_arry = array();
		$a = array();
		$html = '';
		
		if(!empty($_REQUEST['term_name']))
		{		
			$term_id = $_REQUEST['class_id'];
			$model = $_REQUEST['term_name'];
			
			$smgt_admission_main = TableRegistry::get('smgt_admission_main');
			$class_section_newentity = $smgt_admission_main->newEntity();

		
			$class_section_arry['meta_key'] = $term_id;
			$class_section_arry['title'] = $model;
			$class_section_arry['is_active'] = 0;
			$class_section_arry['created_date'] = Time::now();
			
			$class_section_patchentity = $smgt_admission_main->patchEntity($class_section_newentity,$class_section_arry);
			if($smgt_admission_main->save($class_section_patchentity))
			{
				$class_section_id = $class_section_patchentity->adminssion_main_id;
				$class_section_name = $class_section_patchentity->title;
				
				$html = '<tr id=term-'.$class_section_id.'>';
				$html .= '<td>'.$class_section_name.'</td>';
				$html .= '<td id='.$class_section_id.'>';
				$html .= '<a class="widget-icon widget-icon-dark edit-term" href="#" 
					data-type="'.$term_id.'"
					data-id="'.$class_section_id.'">					
					<span class="icon-pencil"></span>
				</a>';
				$html .= '<a class="widget-icon widget-icon-dark remove-term" href="#"
					data-type="'.$term_id.'"
					data-id="'.$class_section_id.'">
					<span class="icon-trash"></span>
				</a>';
				
				$html .= '</td>';
				$html .= '</tr>';
				
				$a['html'] = $html;		
				$a['select'] = '<option value='.$class_section_id.'>'.$class_section_name.'</option>';
				echo json_encode($a);
			}
		}
		else
			echo "false";
		die();
	}
	public function editterm()
	{	
		$term_id = $_REQUEST['class_section_id'];
		//var_dump($term_id);die;
		$model = $_REQUEST['model'];
		$smgt_admission_main = TableRegistry::get('smgt_admission_main');
		
		$retrieved_data = $smgt_admission_main->get($term_id);
		
		//echo '<td>'.$i.'</td>';
			echo '<td><input type="text" name="section_name" value="'.$retrieved_data->title.'" id="section_name"></td>';
			echo '<td id='.$retrieved_data->adminssion_main_id.'>
			<a class="btn-cat-update-cancel btn btn-danger" data-type='.$model.' href="#" data-id='.$retrieved_data->adminssion_main_id.'>'.__('Cancel','school-mgt').'</a>
			<a class="btn-cat-update btn btn-primary" data-type='.$model.' href="#" id='.$retrieved_data->adminssion_main_id.'>'.__('Save','school-mgt').'</a>
			</td>';
		die();
	}
	public function cancelterm()
	{	
		$term_id = $_REQUEST['class_section_id'];
		$model = $_REQUEST['model'];
		
		$smgt_admission_main = TableRegistry::get('smgt_admission_main');		
		$retrieved_data = $smgt_admission_main->get($term_id);
		
		echo '<td>'.$retrieved_data->title.'</td>';
		echo '<td id='.$retrieved_data->adminssion_main_id.'>';
		?>
		<a class="widget-icon widget-icon-dark edit-term" href="#" 
									data-type="<?php echo $retrieved_data->meta_key;?>"
									data-id="<?php echo $retrieved_data->adminssion_main_id;?>">
									
									<span class="icon-pencil"></span>
									</a>
									<a class="widget-icon widget-icon-dark remove-term" href="#"
									data-type="<?php echo $retrieved_data->meta_key;?>"
									data-id="<?php echo $retrieved_data->adminssion_main_id;?>">
									<span class="icon-trash"></span>
									</a>
		<?php
		echo 	'</td>';
	die();
	}
	public function saveterm()
	{		
		$a = array();
		$html = '';
		
		$term_id = $_REQUEST['class_section_id'];
		$model = $_REQUEST['model'];
		
		$smgt_admission_main = TableRegistry::get('smgt_admission_main');		
		$retrieved_data = $smgt_admission_main->get($term_id);
		//$criteria_terms = TableRegistry::get('criteria_terms');
		//$retrieved_data = $criteria_terms->get($term_id);
	
		$retrieved_data->title = $_REQUEST['term_name'];
		$smgt_admission_main->save($retrieved_data);
	
		$html = '<td>'.$_REQUEST['term_name'].'</td>';
		$html .= '<td id='.$term_id.'>';
		$html .= '<a class="widget-icon widget-icon-dark edit-term" href="#" 
									data-type="'.$model.'"
									data-id="'.$term_id.'">
									
									<span class="icon-pencil"></span>
									</a>
									<a class="widget-icon widget-icon-dark remove-term" href="#"
									data-type="'.$model.'"
									data-id="'.$term_id.'">
									<span class="icon-trash"></span>
									</a>';
		$html .= '</td>';
		$a['html'] = $html;		
		$a['select'] = $_REQUEST['term_name'];
		echo json_encode($a);
	die();
	}
	public function deleteterm()
	{	
		$term_id = $_REQUEST['class_section_id'];
		$smgt_admission_main = TableRegistry::get('smgt_admission_main');		
		$retrieved_data = $smgt_admission_main->get($term_id);
		$retrieved_data->is_active = 1;
		$smgt_admission_main->save($retrieved_data);
		die();
	}
}
?>