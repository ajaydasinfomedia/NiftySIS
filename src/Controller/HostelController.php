<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;
use PHPExcel;
use PHPExcel_Helper_HTML;
use PHPExcel_Writer_Excel2007;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Datasource\ConnectionManager;

class HostelController extends AppController
{
	public function initialize()
	{
        parent::initialize();
	    $this->loadComponent('RequestHandler');
		$this->loadComponent('Setting');
		$this->loadComponent('Flash');
		
		require_once(ROOT . DS .'vendor' . DS  . 'PHPExcel' . DS  . 'PHPExcel.php');
		require_once(ROOT . DS .'vendor' . DS  . 'PHPExcel' . DS  . 'PHPExcel' . DS  . 'Writer' . DS  . 'Excel2007.php');
		
	}
	public function addhostel($id=0)
	{
		
		$this->set('Hostel','Hostel');
		$get_current_user_id=$this->request->session()->read('user_id');
		
		$class2 = TableRegistry::get('smgt_hostel'); 
		
		if($id)
		{
			$this->set('edit',true);
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $class2->exists(['hostel_id' => $id]);
			
			if($exists)
			{
				$item = $class2->get($id);
				$this->set('row',$item);
			}
			else
				return $this->redirect(['action'=>'hostellist']);
		}
			
		if($this->request->is('post'))
		{	
			if($id)
			{
				$c1=$this->request->data;
					
				$db_cl = array();
				
				$db_cl['hostel_name']=$c1['hostel_name'];
				$db_cl['hostel_type']=$c1['hostel_type'];
				$db_cl['hostel_desc']=$c1['hostel_desc'];
				$db_cl['modify_date']=date("Y-m-d");
				$db_cl['modify_by']=$get_current_user_id;
				
				$item = $class2->get($id);
				$a=$class2->patchEntity($item,$db_cl);

				if($class2->save($a))
				{
					$this->Flash->success(__('Hostel Updated Successfully', null), 
								'default', 
								array('class' => 'success'));
				}
				return $this->redirect(['action'=>'hostellist']);
			}
			else
			{
				$c1=$this->request->data;
					
				$db_cl = array();
				
				$db_cl['hostel_name']=$c1['hostel_name'];
				$db_cl['hostel_type']=$c1['hostel_type'];
				$db_cl['hostel_desc']=$c1['hostel_desc'];
				$db_cl['created_date']=date("Y-m-d");
				$db_cl['created_by']=$get_current_user_id;
				
				$a=$class2->newEntity();
				$a=$class2->patchEntity($a,$db_cl);

				if($class2->save($a))
				{
					$this->Flash->success(__('Hostel added Successfully', null), 
								'default', 
								array('class' => 'success'));
				}
				return $this->redirect(['action'=>'hostellist']);
			}
		}
	}
	
	public function hostelmultidelete() 
	{
		$this->autoRender = false;
		$id=json_decode($_REQUEST[e_id]);
		
		$i = 0;
		
		foreach($id as $recordid)
		{
			$class = TableRegistry::get('smgt_hostel');
			
			$item =$class->get($recordid);

			if($class->delete($item))
			{
				$i = 1;
			}		
		}
		if($i == 1)
		{
			$this->Flash->success(__('Hostel Deleted Successfully', null), 
						'default', 
						 array('class' => 'success'));
		}
	}
	
	public function hostellist($id=0)
	{
		$this->set('Hostel','Hostel');
		
		$class = TableRegistry::get('smgt_hostel');
		$query=$class->find()->order(['hostel_id'=>'DESC']);
		$this->set('it',$query);
		
	}
	public function delete($id)
	{
		$this->request->is(['post','delete']);
		$class1 = TableRegistry::get('smgt_hostel');
		$item = $class1->get($id);
		if($class1->delete($item))
		{
			$this->Flash->success(__('Hostel Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'hostellist']);
	}
	
	public function addroom($id=0)
	{
				
		$class2 = TableRegistry::get('smgt_hostel_room'); 
		
		$conn = ConnectionManager::get('default');
		$result = $conn->query("SHOW TABLE STATUS LIKE 'smgt_hostel_room';")->fetchAll('assoc');
		$next = $result[0]['Auto_increment'];
		$this->set('next_id',$next);
		
		$this->set('Hostel','Hostel');
		$get_current_user_id=$this->request->session()->read('user_id');	
		
		$smgt_hostel = TableRegistry::get('smgt_hostel');	
		$cls = $smgt_hostel->find("list",["keyField"=>"hostel_id","valueField"=>"hostel_name"]);	
		$this->set('cls',$cls);
		
		$smgt_hostel_room_category=TableRegistry::get('smgt_hostel_room_category');
		$get_all_data=$smgt_hostel_room_category->find();
		$this->set('category_data',$get_all_data);
						  
		if($id)
		{
			$this->set('edit',true);
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $class2->exists(['room_id' => $id]);
			
			if($exists)
			{
				$item = $class2->get($id);
				$this->set('row',$item);
			}
			else
				return $this->redirect(['action'=>'roomlist']);	
		}
			
		if($this->request->is('post'))
		{	
			if($id)
			{
				$c1=$this->request->data;
					
				$db_cl = array();
				
				$db_cl['room_unique_id']=$c1['room_unique_id'];
				$db_cl['hostel_id']=$c1['hostel_id'];
				$db_cl['room_category']=$c1['room_category'];
				$db_cl['beds_capacity']=$c1['beds_capacity'];
				$db_cl['room_desc']=$c1['room_desc'];
				$db_cl['modify_date']=date("Y-m-d");
				$db_cl['modify_by']=$get_current_user_id;
				
				$item = $class2->get($id);
				$a=$class2->patchEntity($item,$db_cl);

				if($class2->save($a))
				{
					$this->Flash->success(__('Hostel Room Updated Successfully', null), 
								'default', 
								array('class' => 'success'));
				}
				return $this->redirect(['action'=>'roomlist']);
			}
			else
			{
				$c1=$this->request->data;
					
				$db_cl = array();
				
				$db_cl['room_unique_id']=$c1['room_unique_id'];
				$db_cl['hostel_id']=$c1['hostel_id'];
				$db_cl['room_category']=$c1['room_category'];
				$db_cl['beds_capacity']=$c1['beds_capacity'];
				$db_cl['room_desc']=$c1['room_desc'];
				$db_cl['created_date']=date("Y-m-d");
				$db_cl['created_by']=$get_current_user_id;
				
				$a=$class2->newEntity();
				$a=$class2->patchEntity($a,$db_cl);

				if($class2->save($a))
				{
					$this->Flash->success(__('Hostel Room added Successfully', null), 
								'default', 
								array('class' => 'success'));
				}
				return $this->redirect(['action'=>'roomlist']);
			}
		}
	}
	public function roomlist()
    {
		$this->set('Hostel','Hostel');
		
		$class = TableRegistry::get('smgt_hostel_room');
		$query=$class->find()->order(['room_id'=>'DESC']);
		$this->set('it',$query);
    }
	public function adddata($id = null) 
	{
		$get_current_user_id=$this->request->session()->read('user_id');
		
		$this->autoRender = false;
        if($this->request->is('ajax'))
		{
			if(!empty($_POST['category_name']))
			{
				$cls = $_POST['category_name'];
				
				$cat = TableRegistry::get('smgt_hostel_room_category');
				$a = $cat->newEntity();

				$a['category_name']=$cls;
				$a['created_date']=date("Y-m-d");
				$a['created_by']=$get_current_user_id;
				
				if($cat->save($a))
				{
					$i=$a['hostel_room_category_id'];
				}
				echo $i;
			}
			else
				echo "false";
            die();
       }
	}
	public function categoryDelete($id = null)
	{
		$this->autoRender = false;
		if($this->request->is('ajax'))
		{
			$category_id=$_POST['category_id'];
			$cat = TableRegistry::get('smgt_hostel_room_category');
			$items=$cat->get($category_id);
			if($cat->delete($items))
			{
				$this->Flash->success(__('Hostel Room Category Deleted Successfully', null), 
										'default', 
										array('class' => 'success'));	
			}
		}
	}
	public function roomDelete($id)
	{
		$this->request->is(['post','delete']);
		$class1 = TableRegistry::get('smgt_hostel_room');
		$item = $class1->get($id);
		if($class1->delete($item))
		{
			$this->Flash->success(__('Hostel Room Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'roomlist']);
	}
	public function roommultidelete() 
	{
		$this->autoRender = false;
		$id=json_decode($_REQUEST[e_id]);
		
		$i = 0;
		
		foreach($id as $recordid)
		{
			$class = TableRegistry::get('smgt_hostel_room');
			
			$item =$class->get($recordid);

			if($class->delete($item))
			{
				$i = 1;
			}		
		}
		if($i == 1)
		{
			$this->Flash->success(__('Hostel Room Deleted Successfully', null), 
						'default', 
						 array('class' => 'success'));
		}
	}
	
	public function addbeds($id=0)
	{				
		$class2 = TableRegistry::get('smgt_add_beds'); 
		
		$conn = ConnectionManager::get('default');
		$result = $conn->query("SHOW TABLE STATUS LIKE 'smgt_add_beds';")->fetchAll('assoc');
		$next = $result[0]['Auto_increment'];
		$this->set('next_id',$next);
		
		$this->set('Hostel','Hostel');
		$get_current_user_id=$this->request->session()->read('user_id');
		
		$smgt_hostel_room = TableRegistry::get('smgt_hostel_room');	
		$cls = $smgt_hostel_room->find("list",["keyField"=>"room_id","valueField"=>"room_unique_id"]);	
		$this->set('cls',$cls);
		
		if($id)
		{
			$this->set('edit',true);
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $class2->exists(['bed_id' => $id]);
			
			if($exists)
			{
				$item = $class2->get($id);
				$this->set('row',$item);
			}
			else
				return $this->redirect(['action'=>'bedslist']);
		}
			
		if($this->request->is('post'))
		{	
			$beds_data_capacity = 0;
			$room_beds_capacity = 0;
			
			if($id)
			{
				$c1=$this->request->data;
				
				$room_unique_id = $c1['room_unique_id'];
				
				$hostel_room_data = $smgt_hostel_room->find()->where(['room_id'=>$room_unique_id])->hydrate(false)->toArray();
				$bed_data = $class2->find()->where(['room_unique_id'=>$room_unique_id])->hydrate(false)->toArray();
				
				$beds_data_capacity = count($bed_data);
				$room_beds_capacity = $hostel_room_data[0]['beds_capacity'];
				
				if($beds_data_capacity == $room_beds_capacity)
				{
					$this->Flash->success(__('Unsuccessful! Hostel Room Bed No Capacity.', null), 
									'default', 
									array('class' => 'success'));
									
					return $this->redirect(['action'=>'bedslist']);
				}
				else
				{
					$db_cl = array();
					
					$db_cl['bed_unique_id']=$c1['bed_unique_id'];
					$db_cl['room_unique_id']=$c1['room_unique_id'];
					$db_cl['bed_desc']=$c1['bed_desc'];
					$db_cl['modify_date']=date("Y-m-d");
					$db_cl['modify_by']=$get_current_user_id;
					
					$item = $class2->get($id);
					$a=$class2->patchEntity($item,$db_cl);

					if($class2->save($a))
					{
						$this->Flash->success(__('Hostel Room Bed Data Edited Successfully', null), 
									'default', 
									array('class' => 'success'));
					}
					return $this->redirect(['action'=>'bedslist']);
				}
			}
			else
			{
				$c1=$this->request->data;
				
				$room_unique_id = $c1['room_unique_id'];
				
				$hostel_room_data = $smgt_hostel_room->find()->where(['room_id'=>$room_unique_id])->hydrate(false)->toArray();
				$bed_data = $class2->find()->where(['room_unique_id'=>$room_unique_id])->hydrate(false)->toArray();
				
				$beds_data_capacity = count($bed_data);
				$room_beds_capacity = $hostel_room_data[0]['beds_capacity'];
				
				if($beds_data_capacity == $room_beds_capacity)
				{
					$this->Flash->success(__('Unsuccessful! Hostel Room Bed No Capacity.', null), 
									'default', 
									array('class' => 'success'));
									
					return $this->redirect(['action'=>'bedslist']);
				}
				else
				{
					$db_cl = array();
					
					$db_cl['bed_unique_id']=$c1['bed_unique_id'];
					$db_cl['room_unique_id']=$c1['room_unique_id'];
					$db_cl['bed_desc']=$c1['bed_desc'];
					$db_cl['created_date']=date("Y-m-d");
					$db_cl['created_by']=$get_current_user_id;
					
					$a=$class2->newEntity();
					$a=$class2->patchEntity($a,$db_cl);

					if($class2->save($a))
					{
						$this->Flash->success(__('Hostel Room Bed Data Added Successfully', null), 
									'default', 
									array('class' => 'success'));
					}
					return $this->redirect(['action'=>'bedslist']);
				}
			}
		}
	}
	public function bedslist()
    {
		$this->set('Hostel','Hostel');
		
		$class = TableRegistry::get('smgt_add_beds');
		$query=$class->find()->order(['bed_id'=>'DESC']);
		$this->set('it',$query);
    }
	public function bedsDelete($id)
	{
		$this->request->is(['post','delete']);
		$class1 = TableRegistry::get('smgt_add_beds');
		$item = $class1->get($id);
		if($class1->delete($item))
		{
			$this->Flash->success(__('Bed Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'bedslist']);
	}
	public function bedsmultidelete() 
	{
		$this->autoRender = false;
		$id=json_decode($_REQUEST[e_id]);
		
		$i = 0;
		
		foreach($id as $recordid)
		{
			$class = TableRegistry::get('smgt_add_beds');
			
			$item =$class->get($recordid);

			if($class->delete($item))
			{
				$i = 1;
			}		
		}
		if($i == 1)
		{
			$this->Flash->success(__('Bed Deleted Successfully', null), 
						'default', 
						 array('class' => 'success'));
		}
	}
	
	public function assignroom($id=0)
	{
		$id = $this->Setting->my_simple_crypt($id,'d');	
		
		$smgt_assign_bed_new = TableRegistry::get('smgt_assign_bed_new'); 
		$class2 = TableRegistry::get('smgt_add_beds'); 
		$student_tbl = TableRegistry::get('smgt_users'); 
		
		$this->set('Hostel','Hostel');
		$get_current_user_id=$this->request->session()->read('user_id');
		
		$cls = $class2->find()->where(['room_unique_id'=>$id])->hydrate(false)->toArray();	
		if(!empty($cls))
			$this->set('cls',$cls);
		
		$stud = $student_tbl->find()->where(['role'=>'student']);	
		$this->set('stud',$stud);
		
		if($this->request->is('post'))
		{
			$c1=$this->request->data;
					
			$db_cl = array();
			$succss = 0;
			foreach($c1['room_unique_id'] as $key=>$value)
			{
				$bed_unique_id = $c1['bed_unique_id'][$key];
			
				$cls_data = $class2->find()->where(['bed_unique_id'=>$bed_unique_id])->hydrate(false)->toArray();
				$assign_bed_data = $smgt_assign_bed_new->find()->where(['bed_unique_id'=>$bed_unique_id])->hydrate(false)->toArray();	
	
				if(!empty($assign_bed_data))
				{			
					$ds_data = $smgt_assign_bed_new->get($assign_bed_data[0]['assign_id']);
					
					$ds_data2['room_unique_id']=$value;
					$ds_data2['bed_unique_id']=$bed_unique_id;
					$ds_data2['student_id']=$c1['student_id'][$key];
					$ds_data2['assign_date']=date("Y-m-d", strtotime($c1['assign_date'][$key]));
					$ds_data2['created_date']=date("Y-m-d");						
					$ds_data2['created_by']=$get_current_user_id;
					
					$item1 = $smgt_assign_bed_new->patchEntity($ds_data,$ds_data2);
					
					if($smgt_assign_bed_new->save($item1))
						$succss = 1;
					
					$student = $this->Setting->hostel_room_student_bed_unique_id($bed_unique_id);

					if($student['student_id'])
					{
						$bed_data = $class2->get($cls_data[0]['bed_id']);						
						$bed_data->bed_status = 1;								
						$class2->save($bed_data);
					}	
				}
				else
				{
					$db_cl['room_unique_id']=$value;
					$db_cl['bed_unique_id']=$bed_unique_id;
					$db_cl['student_id']=$c1['student_id'][$key];
					$db_cl['assign_date']=date("Y-m-d", strtotime($c1['assign_date'][$key]));
					$db_cl['created_date']=date("Y-m-d");
					$db_cl['created_by']=$get_current_user_id;
				
					$a=$smgt_assign_bed_new->newEntity();
					$a=$smgt_assign_bed_new->patchEntity($a,$db_cl);
					if($smgt_assign_bed_new->save($a))
						$succss = 1;
					
					$student = $this->Setting->hostel_room_student_bed_unique_id($bed_unique_id);
					
					if($student['student_id'])
					{
						$bed_data = $class2->get($cls_data[0]['bed_id']);						
						$bed_data->bed_status = 1;								
						$class2->save($bed_data);
					}
				}
			}

			if($succss == 1)
			{
				$this->Flash->success(__('Hostel Room assign Successfully', null), 
							'default', 
							array('class' => 'success'));
			}
			return $this->redirect(['action'=>'roomlist']);
		}
	}
	
	public function assignRoomDelete($room_unique_id,$bed_unique_id,$student_id)
	{

		$class1 = TableRegistry::get('smgt_assign_bed_new');
		$class2 = TableRegistry::get('smgt_add_beds'); 
		
		$item = $class1->find()->where(['room_unique_id'=>$room_unique_id,
										'bed_unique_id'=>$bed_unique_id,
										'student_id'=>$student_id
										]);
		$indx = 0;
		
		$cls_data = $class2->find()->where(['bed_unique_id'=>$bed_unique_id]);	
		foreach($cls_data as $as_data)
		{
			$student = $this->Setting->hostel_room_student_bed_unique_id($bed_unique_id);
			if($student['student_id'])
			{
				$as_data['bed_status'] = 0;
				$class2->save($as_data);
			}
		}

		foreach($item as $data)
		{
			$ds_data = $class1->get($data['assign_id']);
	
			$ds_data2['student_id'] = 0;
			$ds_data2['assign_date'] = date("Y-m-d");
			
			$item1 = $class1->patchEntity($ds_data,$ds_data2);
			
			if($class1->save($item1))
				$indx = 1;
		}

		if($indx == 1)
		{
			$this->Flash->success(__('Hostel Room assign Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'assignroom',$room_unique_id]);
	}
}

?>