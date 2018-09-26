<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;

class SmssettingController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Flash');
	}
	public function smssetting()
	{
		$class = TableRegistry::get('smgt_setting');
		
		$nm=$this->Setting->getfieldname('clicktell');
		$nm1=$this->Setting->getfieldname('twillo');
		
		$service=$this->Setting->getfieldname('select_serveice');
		$this->set('service',$service);
		
		$service_data_decode=json_decode($nm,true);
		$service_data_decode1=json_decode($nm1,true);
			
		$this->set('service_data_decode',$service_data_decode);
		$this->set('service_data_decode1',$service_data_decode1);
		
		if($this->request->is('post'))
		{
			$data=$this->request->data;
			
			$service=$data['select_serveice'];

			if($data['select_serveice']=='clicktell')
			{
		
				$username=$data['username'];
				$password=$data['password'];
				$api=$data['api_key'];
				$sender_id=$data['sender_id'];
				
				$entry_data=array();
				
				$entry_data=array('username'=>$username,'password'=>$password,'api_key'=>$api,'sender_id'=>$sender_id);
				
				$custom_field=json_encode($entry_data);
			
				$xyz=$this->Setting->setting('select_serveice',$service);
				$xyz1=$this->Setting->setting('clicktell',$custom_field);
			}
			if($data['select_serveice']=='twillo')
			{
		
				$account=$data['account_sid'];
				$token=$data['auth_token'];
				$number=$data['from_number'];
				
				$entry_data1=array();
				
				$entry_data1=array('account_sid'=>$account,'auth_token'=>$token,'from_number'=>$number);
				
				$custom_field1=json_encode($entry_data1);

				$xyz=$this->Setting->setting('select_serveice',$service);
				$xyz1=$this->Setting->setting('twillo',$custom_field1);
				
			}
			return $this->redirect(['action'=>'smssetting']);
		}
	}
}

?>

