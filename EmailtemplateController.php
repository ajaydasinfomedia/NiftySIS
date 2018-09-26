<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;

class EmailtemplateController extends AppController
{
	const TABLE_USER='smgt_users';
	const TABLE_ET='smgt_emailtemplate';
	public $table_group;
	public $get_all_group;
	public $table_user;
	public $get_user_data;
	public $table_emailtemplate;
	public $table_mgmember;
	public $et_entity;
	public $et_patch;
	public $get_all_mg_data;
	
	public function TableUser(){
			$this->table_user=TableRegistry::get(EmailtemplateController::TABLE_USER);	
	}
	public function TableET(){
			$this->table_emailtemplate=TableRegistry::get(EmailtemplateController::TABLE_ET);
	}
	public function inbox(){
    }
	public function index()
	{
		$this->TableET();
		if($this->request->session()->read('user_id') == ""){return $this->redirect(['controller'=>'User','action'=>'user']);}
		$templates=$this->table_emailtemplate->find()->toArray();
		$this->set('t',$templates);
		
		if($this->request->is('post'))
		{
			$data=$this->request->data();
			$update=$this->table_emailtemplate->get($data['id']);
			$email_patch=$this->table_emailtemplate->patchEntity($update,$data);
		 
			if($this->table_emailtemplate->save($email_patch))
			{
				$message=$update['name'].__(' Added Successfully!');
				$this->Flash->success($message);
				return $this->redirect(['action'=>'index']);
			}
		}
	}
}
?>