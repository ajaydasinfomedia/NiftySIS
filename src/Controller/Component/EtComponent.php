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
 }
 
 
 public function get_school_name(){
 
 
 public function get_password($password)
 public function fees_pay_id($fees_pay_id)
 }





