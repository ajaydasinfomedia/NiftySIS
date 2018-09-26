<?php
namespace App\Controller;
 
use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\Network\Session\DatabaseSession;
use Cake\View\Helper\FlashHelper;
use Cake\Auth\DefaultPasswordHasher;

class UserController extends AppController
{
	public function initialize()
   {
       parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Flash');
		$this->stud_date = $this->Setting->getfieldname('date_format');
		
   }
	public function user()
	{
		$session = $this->request->session();
		$user_id = $session->read('user_id');
		
		if($user_id)
		{
			$role = $this->Setting->get_user_role($user_id);
			
			if($role == 'admin')
			{
				return $this->redirect(['controller' => 'Templet','action'=>'templet']);
			}
			elseif($role == 'student')
			{
				
				return $this->redirect(['controller' => 'Templet','action'=>'studentdash']);
			}
			elseif($role == 'teacher')
			{
				return $this->redirect(['controller' => 'Templet','action'=>'teacherdash']);
			}
			elseif($role == 'parent')
			{
				return $this->redirect(['controller' => 'Templet','action'=>'parentdash']);
			}
			elseif($role == 'supportstaff')
			{
				return $this->redirect(['controller' => 'Templet','action'=>'supportstaffdash']);
			}
		}
		if($this->request->is("post") )
		{
			if(isset($this->request->data["add"]))
			{			
				$c1=$this->request->data();
			
				$pass = $c1['password'];

				$t=$this->Setting->login($c1['username'],$pass);
				
				$session->write('user',$c1['username']);
				$session->write('user_id',$t[1]);

				if(!empty($session->read('user_id')))
				{
					$session->write('image',$t[2]);
				}	
				

				if($t[0] == '')
				{	
					$this->Flash->error(__("Invalid Username And Password"),[
						'params' => [
							'class' => 'alert alert-error'
						]
					]);
					
				}
				elseif($t[0] == 'admin')
				{
					return $this->redirect(['controller' => 'Templet','action'=>'templet']);
				}
				elseif($t[0] == 'student')
				{
					return $this->redirect(['controller' => 'Templet','action'=>'studentdash']);
				}
				elseif($t[0] == 'teacher')
				{
					return $this->redirect(['controller' => 'Templet','action'=>'teacherdash']);
				}
				elseif($t[0] == 'parent')
				{
					return $this->redirect(['controller' => 'Templet','action'=>'parentdash']);
				}
				elseif($t[0] == 'supportstaff')
				{
					return $this->redirect(['controller' => 'Templet','action'=>'supportstaffdash']);
				}
			}		
		}
		
	}
	public function forgetpassword()
	{
		$hasher = new DefaultPasswordHasher();
		
		$smgt_users = TableRegistry::get('smgt_users');
		
		if($this->request->is("post"))
		{
			$pass = '';
			
			$c1=$this->request->data();
			
			$username = $c1['username'];		
			$phone = $c1['phone'];		
			$password = $c1['password'];			
			
			$get_all_data_from_user = $smgt_users->find()
									->where(['email'=>$username,'phone'=>$phone])
									->hydrate(false)->toArray();
			
			$school_name = $this->Setting->getfieldname('school_name');
			$school_email = $this->Setting->getfieldname('email');
				
			if(!empty($get_all_data_from_user))
			{		
				$get_user_id = $get_all_data_from_user[0]['user_id'];
				$get_user_data = $smgt_users->get($get_user_id);
			
				$pass = $hasher->hash($password);
				
				$get_user_data['password'] = $pass;
				
				if($smgt_users->save($get_user_data))
				{	
					$message_content = "Your New Password is ".$password;
					$emial_to = $username;
					$sys_name = $school_name;
					$sys_email = $school_email;
					$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
					@mail($emial_to,_("New Password!"),$message_content,$headers);
								
					$this->Flash->success(__('New Password Set Successfully.', null), 
						'default', array('class' => 'alert alert-success'));					
				}
				return $this->redirect(['controller'=>'User','action'=>'user']);
			}
			else
			{
				$this->Flash->error(__("Unsuccessful ! Wrong Email and Phone Number"),[
						'params' => [
							'class' => 'alert alert-error'
						]
					]);
				
				return $this->redirect(['controller'=>'User','action'=>'forgetpassword']);
				
			}			
		}
	}
	public function logout()
	{
		$this->Setting->logout();
		return $this->redirect(['controller' => 'User','action'=>'user']);
	}
	public function profileupload()
	{
		$this->autoRender=false;
		
		$stud_date = $this->Setting->getfieldname('date_format');
		$header = '';
		$get_data = array();
		
		$id=$this->request->session()->read('user_id');
		
		if($id)
		{
			$users = TableRegistry::get('Smgt_users');
			$item = $users->get($id);
			
			$img2=$this->request->data();
			$xyz1=$this->Setting->getimage($img2['image']);
			$img2['image']=$xyz1;
			
			$ptitem = $users->patchEntity($item,$img2);
				
			if($users->save($ptitem))
			{
				// $this->Setting->logout();
				return $this->redirect(['controller' => 'Comman','action'=>'account']);
			}
			
		}

	}
	
	public function userinfo($id = null)
	{
		$this->autoRender=false;
		
		if($this->request->is('ajax'))
		{
			$get_id=$_POST['id'];
			
			$user_table_register=TableRegistry::get('smgt_users');
			$get_field=$user_table_register->get($get_id);
			
			$gender = ($get_field['gender'] == 'female')?'Female':'Male';
			$stud_date = $this->Setting->getfieldname('date_format');
			
			$user = $this->request->session()->read('user_id');
			$role = $this->Setting->get_user_role($user);
			?>
			<div class="col-md-6 col-sm-6 col-xs-12">
			<h4><i class="fa fa-image"></i><?php echo "&nbsp;".__('Profile Image');?></h4>
				<span style="<?php if($get_field['role'] == 'student'){ echo 'height: 265px;';}else{echo 'height: 215px;';}?>width: 100%;float: left;">
					<img src="<?php echo $this->request->webroot."img/".$get_field['image'];?>" style="max-width: 100%;max-height: 100%;">
				</span>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
			<h4><i class="fa fa-user"></i><?php echo "&nbsp;".__('Personal Info');?></h4>
			<table class="table table-striped" cellspacing="0" width="100%" style="border:1px solid #dddddd;border-top: medium none;border-collapse: separate;">
				<thead></thead>	
				<tfoot></tfoot>	
				<tbody>
					<tr>
						<td><?php echo __('Name '); ?></td>
						<td><?php echo $this->Setting->get_user_id($get_field['user_id']); ?></td>
					</tr>
					<?php 
					if($get_field['role'] == 'student')
					{
					?>	
					<tr>
						<td><?php echo __('Roll Number '); ?> </td>
						<td><?php echo $get_field['roll_no']; ?></td>
					</tr>
			  <?php }
					?>	
					<tr>
						<td><?php echo __('Date Of Birth '); ?></td>
						<td><?php echo date($stud_date,strtotime($get_field['date_of_birth'])); ?></td>
					</tr>				
					<tr>
						<td><?php echo __('Gender '); ?> </td>
						<td><?php echo $gender; ?></td>
					</tr>
					<?php 
					if($get_field['role'] == 'student')
					{
					?>
					<tr>
						<td><?php echo __('Class '); ?> </td>
						<td><?php echo $this->Setting->get_class_id($this->Setting->get_user_class($get_field['user_id'])); ?></td>
					</tr>
			  <?php }
					elseif($get_field['role'] == 'teacher')
					{
					?>
					<tr>
						<td><?php echo __('Class (Subject Related)'); ?> </td>
						<td><?php 
						echo $this->Setting->get_teacher_class_list($get_field['user_id']);?></td>
					</tr>		
			   <?php }
					elseif($get_field['role'] == 'parent')
					{
				?>
					<tr>
						<td><?php echo __('Child '); ?> </td>
						<td><?php 
						echo $this->Setting->get_parent_child_list($get_field['user_id']);?></td>
					</tr>
					<?php }
					elseif($get_field['role'] == 'supportstaff')
					{
				?>
					<tr>
						<td><?php echo __('Working Time '); ?> </td>
						<td><?php 
						echo $get_field['working_hour'];?></td>
					</tr>
					<?php }
					?>
				</tbody>
			</table>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
			<h4><i class="fa fa-map-marker"></i><?php echo "&nbsp;".__('Contact Info');?></h4>
			<table class="table table-striped" cellspacing="0" width="100%" style="border:1px solid #dddddd;border-top: medium none;border-collapse: separate;">
				<thead></thead>	
				<tfoot></tfoot>	
				<tbody>						
					<tr>
						<td><?php echo __('Mobile Number '); ?> </td>
						<td><?php echo $get_field['mobile_no']; ?></td>
					</tr>								
					<tr>
					<?php 
					if($role == 'admin' || $role == 'parent' || $role == 'supportstaff'){
					?>
						<td><?php echo __('Phone Number '); ?></td>
						<td><?php echo $get_field['phone']; ?></td>
					<?php } ?>
					</tr>	
					<tr>
						<td><?php echo __('Address '); ?></td>
						<td><?php 
							if(!empty($get_field['address'])){echo $get_field['address']."</br>";}
							if(!empty($get_field['city'])){echo $get_field['city']."</br>";}
							if(!empty($get_field['state'])){echo $get_field['state']."</br>";}
							if(!empty($get_field['zip_code'])){echo $get_field['zip_code'];}
						?></td>
					</tr>
				</tbody>
			</table>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
			<h4><i class="fa fa-key"></i><?php echo "&nbsp;".__('Authorize Info');?></h4>
			<table class="table table-striped" cellspacing="0" width="100%" style="border:1px solid #dddddd;border-top: medium none;border-collapse: separate;">
				<thead></thead>	
				<tfoot></tfoot>	
				<tbody>	
					<?php 
					if($get_field['role'] == 'student')
					{
					?>
					<tr>
						<td><?php echo __('Student ID  '); ?> </td>
						<td><?php echo $this->Setting->get_studentID($get_field['user_id']); ?></td>
					</tr>
			  <?php } ?>	
					<tr>
						<td><?php echo __('Email   '); ?> </td>
						<td><?php echo $get_field['email']; ?></td>
					</tr>								
					<tr>
						<td><?php echo __('Username '); ?></td>
						<td><?php echo $get_field['username']; ?></td>
					</tr>	
				</tbody>
			</table>
			</div>
		<?php
		}
	}
	
	public function showdata($id = null)
	{
		$class2=TableRegistry::get('class_section');
		$query2=$class2->find();

		$class3=TableRegistry::get('smgt_subject');
		
		$teacher_msg_all_stud = $this->Setting->getfieldname('teacher_msg_all_stud');
		
		$user_session_id=$this->request->session()->read('user_id');
		$role=$this->Setting->get_user_role($user_session_id);		
		
		$this->autoRender=false;
		
		if($this->request->is('ajax'))
		{			
			$get_id=$_POST['id'];
			
			$get_cls=$class2->get($get_id);
			
			$get_class=$get_cls['class_section_id'];
			
			if($role == 'teacher' && $teacher_msg_all_stud == 'no')
				$get_data=$class3->find()->where(['section'=>$get_class,'teacher_id'=>$user_session_id]);
			else
				$get_data=$class3->find()->where(['section'=>$get_class]);
			
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
				var table = $('#viewnotice').removeAttr('width').DataTable( {
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
			<table id="viewnotice" class="table table-striped" cellspacing="0" width="100%">
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
}

?>