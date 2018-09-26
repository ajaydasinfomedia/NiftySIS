<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Utility\Xml;
use Cake\View\Helper\FlashHelper;
use Cake\Datasource\ConnectionManager;
use Cake\Network\Http\Client;

class EtComponent extends Component
{


	public function get_name($user_id){
 $user = TableRegistry::get('smgt_users');
 $count_data=$user->find()->where(array('user_id'=>$user_id))->count();
 
 if($count_data > 0){
 $data = $user->get($user_id);
 $name = $data['first_name'].' '.$data['last_name'];
 return $name;
 }else{
 return '--';
 }	
 }


 public function get_username($user_id){
 $user = TableRegistry::get('smgt_users');
 $count_data=$user->find()->where(array('user_id'=>$user_id))->count();
 
 if($count_data > 0){
 $data = $user->get($user_id);
 $username = $data['username'];
 return $username;
 }else{
 return '--';
 } }
 public function get_email($user_id=0){if($user_id){ $table_users=TableRegistry::get('smgt_users'); $data_users=$table_users->get($user_id); $users_email=$data_users['email']; return $users_email;} }
 
 public function get_school_name(){ $table_school=TableRegistry::get('smgt_setting'); $data_school=$table_school->get(1); $school_name=$data_school['field_value']; return $school_name; }
 
 public function get_username1($username)    {				$class=TableRegistry::get('smgt_users');			$query = $class->find('all',array('conditions' => array('username'=>$username)));				foreach ($query as $id3) 		{			$username=$id3['username'];		}					return $username;			}
 public function get_password($password)    {				$class=TableRegistry::get('smgt_users');			$query1 = $class->find('all',array('conditions' => array('password'=>$password)));				foreach ($query1 as $id1) 		{			$password=$id1['password'];		}					return $password;			}		public function get_role($role)    {				$class=TableRegistry::get('smgt_users');			$query = $class->find('all',array('conditions' => array('role'=>$role)));				foreach ($query as $id4) 		{			$role=$id4['role'];		}					return $role;			}				public function get_role_teacher($classid)    {								$class=TableRegistry::get('smgt_users');				$query = $class->find('all',array('conditions' => array('role'=>'teacher','classname'=>$classid)))->hydrate(false)->toArray();									return $query;			}				public function get_emailall($field=""){ $get_emailall=TableRegistry::get('smgt_users'); $userall=$get_emailall->find('all')->hydrate(false)->toArray();$data = array(); foreach($userall as $user_data)	{				$data[] = $user_data[$field];			}return $data; }	
 public function fees_pay_id($fees_pay_id)    {				$class=TableRegistry::get('smgt_fees_payment');				$query = $class->find('all',array('conditions' => array('fees_pay_id'=>$fees_pay_id)))->hydrate(false)->toArray();				foreach ($query as $fees_payment) 		{			return $fees_payment['fees_pay_id'];		}			}		
 }






