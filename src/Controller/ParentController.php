<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Mailer\Email;

class ParentController extends AppController
{
	public function initialize()
   {
       parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Flash');
		$this->loadComponent('Et');
   }
	public function addparent()
	{
		$this->set('Parent','Parent');
			
		$class = TableRegistry::get('Classmgt');
		
		$query=$class->find();
		$this->set('it',$query);
		
		$class2 = TableRegistry::get('Smgt_users');
		
		$query2=$class2->find()->where(['role'=>'student']);
		$this->set('child',$query2);
		
		$country=$this->Setting->getfieldname('country');
		
		$country_code=$this->Setting->get_country_code($country);
		$this->set('country_code',$country_code);
		
		if($this->request->is('post'))
		{		
			
				$i = 0;
				$email=$this->request->data['email'];
				$username=$this->request->data['username'];	
					
				$check_email = $class2->find()->where(['email'=>$email]);					
				$check_user = $class2->find()->where(['username'=>$username]);	
				
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
						$a = $class2->newEntity();

						$c1=$this->request->data;
						
						$hasher = new DefaultPasswordHasher();
						$password=$c1['password'];
						$pass = $hasher->hash($c1['password']);
						$c1['password']=$pass;
						
						$c1['classname']=null;
						$c1['roll_no']=null;
						$c1['alternate_mobile_no']=null;
						$c1['working_hour']=null;
						$c1['position']=null;
						$c1['submitted_document']=null;
						$c1['role']='parent';
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
								
						$a=$class2->patchEntity($a,$c1);
					
						if($class2->save($a))
						{
							$username=$a['username'];							
							$role=$a['role'];
							$this->Flash->success(__('Parent Registered Successfully', null), 
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
				
						$query3=$class2->find()->where(['role'=>'parent']);
						foreach($query3 as $p_id)
						{
							$parent_id=$p_id['user_id'];
							
						}

						$ch=$c1['child_id'];
						
						foreach($ch as $chd)
						{
							$user_id=$this->request->session()->read('user_id');
							
							$class3 = TableRegistry::get('child_tbl');
						
							$a3 = $class3->newEntity();
							
							$c1=$this->request->data();
							
							$c1['child_parent_id']=$parent_id;
							$c1['created_by']=$user_id;
							$c1['child_id']=$chd;
							$c1['created_date']=Time::now();;
							$c1['status']=null;
							
							
							$a3=$class3->patchEntity($a3,$c1);
							
							if($class3->save($a3))
							{
								$i=1;
							}
							
						}
						if($i == 1)
						{
							$this->Flash->success(__('Child Registered Successfully', null), 
									'default', 
									 array('class' => 'success'));
						

								
					}
					}
					
					return $this->redirect(['action'=>'parentlist']);
		}
	}
	public function parentlist()
	{
		$this->set('Parent','Parent');
		
		$class = TableRegistry::get('Smgt_users');
		
		$query=$class->find()->where(['role'=>'parent']);
		$abc = array();
		foreach ($query as $id) 
		{
			$xyz=$this->Setting->get_user_id($id['child_id']);
			$abc[]=$xyz;
		}
		
		$this->set('id',$abc);
		
		$this->set('it',$query);
	}
	
	public function parentmultidelete() 
	{
		$this->autoRender = false;
		$id=json_decode($_REQUEST[p_id]);
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
			$this->Flash->success(__('Parent Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'parentlist']);
	}
	public function updateparent($id=0)
	{
		$this->set('Parent','Parent');
		
		$id = $this->Setting->my_simple_crypt($id,'d');
		
		if($id)
		{	
			$class_data = TableRegistry::get('Classmgt');	
			$cls = $class_data->find("list",["keyField"=>"class_id","valueField"=>"class_name"]);		
			$this->set('cls',$cls);
		
			$country=$this->Setting->getfieldname('country');
		
			$country_code=$this->Setting->get_country_code($country);
			$this->set('country_code',$country_code);
			
			$class1 = TableRegistry::get('Smgt_users');
			$class3 = TableRegistry::get('child_tbl');
			
			$exists = $class1->exists(['user_id' => $id]);
			
			if($exists)
			{				
				$item = $class1->get($id);
				
				$id1=$item->user_id;

				$query3=$class3->find()->where(['child_parent_id'=>$id1]);

				$a = array();
				
				foreach($query3 as $tbl_ch)
				{
						$a[]=$tbl_ch['child_id'];
				}
				$this->set('parent_child_id',$a);	

				if($this->request->is(['post','put']))
				{		
					$query3=$class3->find()->where(['child_parent_id'=>$id1]);
					
					foreach($query3 as $tbl_ch)
					{
						$a=$tbl_ch['child_tbl_id'];
						$entity = $class3->get($a); 
						$result = $class3->delete($entity);
					}			
					
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
					
						$ch = $img2['child_id'];
						
						if(!empty($ch))
						{
							foreach($ch as $chd)
							{								
								$user_id=$this->request->session()->read('user_id');
								
								$class3 = TableRegistry::get('child_tbl');
							
								$a3 = $class3->newEntity();
								
								$c1=$this->request->data();
								
								$c1['child_parent_id']=$id1;
								$c1['created_by']=$user_id;
								$c1['child_id']=$chd;
								$c1['created_date']=Time::now();;
								$c1['status']=null;
								
								$a3=$class3->patchEntity($a3,$c1);
								
								if($class3->save($a3))
								{
									
								}							
							}
						}
			
					$img2['date_of_birth']=date("Y-m-d", strtotime($img2['date_of_birth']));	
						
					$item = $class1->patchEntity($item,$img2);
					
					if($class1->save($item))
					{
						$this->Flash->success(__('Parent Updated Successfully', null), 
							'default', 
							 array('class' => 'success'));
						
					}
					return $this->redirect(['action'=>'parentlist']);
				}
				$this->set('it',$item);
				
				$query1 = $class1->find()->where(['role'=>'student']);
				$this->set('ch',$query1);
			}
			else
				return $this->redirect(['action'=>'parentlist']);
		}
		else
			return $this->redirect(['action'=>'parentlist']);
	}
	public function parentchild($id = null)
	{
		$this->autoRender=false;
		if($this->request->is('ajax'))
		{
			$id=$_POST['id'];
		
			$class = TableRegistry::get('Smgt_users');
			$class1 = TableRegistry::get('child_tbl');
			
			$u_id=$class->get($id);
			
			$user_id=$u_id['user_id'];

			$name=$u_id['first_name']." ".$u_id['last_name'];
			
			$child_list=$class1->find()->where(['child_parent_id'=>$user_id]);
			
			?>
			<div class="panel panel-white">
				<div class="panel-heading">
					<h4 class="panel-title"><?php echo $name;?></h4>
				</div>
			</div>		
			<div class="clearfix"></div>
			<div class="main-tog">
				<div class="panel-body view_result">
					<div class="table-responsive">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th><?php echo __('Photo')?></th>
									<th><?php echo __('Student ID')?></th>
									<th><?php echo __('Name')?></th>
									<th><?php echo __('Class')?></th>
								</tr>
							</thead>
							<tfoot>
							</tfoot>
							<tbody>
							<?php
							if(!$child_list->isEmpty())
							{
								
								foreach($child_list as $list_ch)
								{
									$ch_id=$list_ch['child_id'];
									
									$name=$this->Setting->get_user_id($ch_id);
									$photo=$this->Setting->get_user_image($ch_id);
									$class_id=$this->Setting->get_user_class($ch_id);
									$class=$this->Setting->get_class_id($class_id);
									$studentID = $this->Setting->get_studentID($ch_id);
								?>
								<tr>
									<td><image src="../img/<?php echo $photo; ?>" alt="none" height='50px' width='50px' class='profileimg'/></td>
									<td><?php echo $studentID;?></td>
									<td><?php echo $name;?></td>
									<td><?php echo $class;?></td>			
								</tr>
								
							<?php }
							}else
							{
							echo "<b><p style='color:red;'>child Not Available</p></b>";}
							?>
							</tbody>
						</table>
					</div>
				</div>	
			</div>		
		<?php
		}
	}
}
?>