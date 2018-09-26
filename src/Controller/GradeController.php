<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class GradeController extends AppController{


	public function addgrade(){

		$grade_table_register=TableRegistry::get('smgt_grade');
		$grade_table_entity=$grade_table_register->newEntity();

		if($this->request->is('post')){

			$data=$this->request->data;
			$data['created_date']=Time::now();
			$store_data=$grade_table_register->patchEntity($grade_table_entity,$data);

			if($grade_table_register->save($store_data)){
				
				 $this->Flash->success(__('Grade added Successfully', null), 
									'default', 
									 array('class' => 'success'));
			}
			
			return $this->redirect(['controller'=>'Grade','action'=>'gradelist']);

		}


	}

	public function grademultidelete() 
		{
			$this->autoRender = false;
			$id=json_decode($_REQUEST[g_id]);
			foreach($id as $recordid)
				{
						$class = TableRegistry::get('smgt_grade');
						
						$item =$class->get($recordid);

						if($class->delete($item))
						{
							
						}
						
				}
		}
	
	  public function delete($id){

	  	$grade_table_register=TableRegistry::get('smgt_grade');
	  	$this->request->is(['post','delete']);

	  		$get_gradeId=$grade_table_register->get($id);
	  		if($grade_table_register->delete($get_gradeId))
			{
	  			
	  			$this->Flash->success(__('Grade Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));	
			}
			return $this->redirect(['controller'=>'Grade','action'=>'gradelist']);
    }

	   public function gradelist(){
	   	$grade_table_register=TableRegistry::get('smgt_grade');
	   	$result_set=$grade_table_register->find();

	   	$this->set('row',$result_set);

    }

    public function updategrade($id)
	{
		if($id)
		{
			$id = $this->Setting->my_simple_crypt($id,'d');	
			
			$grade_table_register=TableRegistry::get('smgt_grade');
			$exists = $grade_table_register->exists(['grade_id' => $id]);
			
			if($exists)
			{
				$_result_set=$grade_table_register->get($id);

				if($this->request->is('post'))
				{	
					$Save_Data=$grade_table_register->patchEntity($_result_set,$this->request->data);
					if($grade_table_register->save($Save_Data))
					{
						$this->Flash->success(__('Grade Updated Successfully', null), 
							'default', 
							 array('class' => 'alert alert-success'));
							 
						return $this->redirect(['controller'=>'Grade','action'=>'gradelist']);
					}
					else
					{
						echo 'Some Error in Update Page';
					}
				}
				$this->set('row',$_result_set);
			}
			else
				return $this->redirect(['controller'=>'Grade','action'=>'gradelist']);
		}
		else
			return $this->redirect(['controller'=>'Grade','action'=>'gradelist']);
    }

}

?>