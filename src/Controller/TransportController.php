<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Mailer\Email;

class TransportController extends AppController{

	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Et');
	}

	public function addtransport()
	{

		if($this->request->is('post')){
			if($this->request->data['image']){
				$img=$this->request->data['image'];
				$img_var="img";
				$root_dir=WWW_ROOT.$img_var;
				$get_imagename=$img['name'];
				$store=$root_dir.'/'.$get_imagename;
				if(move_uploaded_file($img['tmp_name'], $store)){
				
				}else{
			
				}
			}

				$transport_table_register=TableRegistry::get('smgt_transport');
				$transport_table_entity=$transport_table_register->newEntity();
				$data=$this->request->data;
				
				$data['image']=$this->request->data('image');
				$data['image']=$data['image']['name'];
				$data_add=$transport_table_register->patchEntity($transport_table_entity,$data);
				if($transport_table_register->save($data_add))
				{
					 $this->Flash->success(__('Transport added Successfully', null), 
									'default', 
									 array('class' => 'success'));
				}
				return $this->redirect(['controller'=>'transport','action'=>'transportlist']);
		}
	}

	public function transportlist(){

			$transport_table_register=TableRegistry::get('smgt_transport');
			$Get_all_data=$transport_table_register->find();
			$this->set('rows',$Get_all_data);	


	}
	
	public function transportmultidelete() 
		{
			$this->autoRender = false;
			$id=json_decode($_REQUEST[t_id]);
			foreach($id as $recordid)
				{
						$class = TableRegistry::get('smgt_transport');
						
						$item =$class->get($recordid);

						if($class->delete($item))
						{
							
						}
						
				}
		}
	
	 public function delete($id){

	 	$transport_table_register=TableRegistry::get('smgt_transport');
	 	$this->request->is(['post','delete']);
	 	$get_id=$transport_table_register->get($id);

	 	if($transport_table_register->delete($get_id))
		{
			$this->Flash->success(__('Transport Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));	
							 
	 		
	 	}
		return $this->redirect(['controller'=>'transport','action'=>'transportlist']);

    }

	public function updatetransport($id)
	{
		if($id)
		{
			$id = $this->Setting->my_simple_crypt($id,'d');	
			
			$transport_table_register=TableRegistry::get('smgt_transport');
			$exists = $transport_table_register->exists(['transport_id' => $id]);
			
			if($exists)
			{
				$get_items=$transport_table_register->get($id);

				if($this->request->is(['post','put']))
				{
					$data=$this->request->data;

					if($_FILES['image']['name'] != null && !empty($_FILES['image']['name'])){
						move_uploaded_file($_FILES['image']['tmp_name'],WWW_ROOT."img/".$_FILES['image']['name']);
						$photo_name=$_FILES['image']['name'];

					}else{
						$photo_name=$this->request->data('image2');
					}

					$data['image']=$photo_name;

					$update_data=$transport_table_register->patchEntity($get_items,$data);

					if($transport_table_register->save($update_data))
					{
						$this->Flash->success(__('Transport Updated Successfully', null), 
							'default', 
							 array('class' => 'alert alert-success'));
							 
						
					}
					return $this->redirect(['controller'=>'Transport','action'=>'transportlist']);
				}
				$this->set('row',$get_items);
			}
			else
				return $this->redirect(['controller'=>'Transport','action'=>'transportlist']);
		}
		else
			return $this->redirect(['controller'=>'Transport','action'=>'transportlist']);
	}
}


?>