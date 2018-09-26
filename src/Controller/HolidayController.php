<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Mailer\Email;

class HolidayController extends AppController{
	
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Et');
		$this->loadComponent('Flash');
		
		$this->stud_date = $this->Setting->getfieldname('date_format');
	}

	public function addholiday()
	{
		$holiday_table_register=TableRegistry::get('smgt_holiday');
		$holiday_table_entity=$holiday_table_register->newEntity();

		if($this->request->is('post')){
			
			$data=$this->request->data;
			$data['created_by']=$this->request->session()->read('user_id');
			$data['date']=date("Y-m-d", strtotime($this->request->data('date')));
			$data['end_date']=date("Y-m-d", strtotime($this->request->data('end_date')));
			$query=$holiday_table_register->patchEntity($holiday_table_entity,$data);

			if($holiday_table_register->save($query))
			{
				$this->Flash->success(__('Holiday added Successfully', null), 
								'default', 
								 array('class' => 'success'));
								 
			
				$holiday_title=$query['holiday_title'];
				$date=date($this->stud_date, strtotime($query['date']));
				$resiver_email=$this->Et->get_emailall('email');

				foreach($resiver_email as $resiver_email1)
				{
					$sys_email=$this->Setting->getfieldname('email'); 
					$school_name = $this->Setting->getfieldname('school_name');
				
					$mailtem = TableRegistry::get('smgt_emailtemplate');
					$format =$mailtem->find()->where(["find_by"=>"Holiday"])->hydrate(false)->toArray();
					
					$str=$format[0]['template'];
					$subject=$format[0]['subject'];
					
					$msgarray = explode(" ",$str);
					$subarray = explode(" ",$subject);
					
					$email_id=$resiver_email1;

					$msgarray['{{holiday_title}}']=$holiday_title;
					$msgarray['{{holiday_date}}']=$date;
					$msgarray['{{school_name}}']=$school_name;	

					$subarray['{{holiday_title}}']=$holiday_title;
					$subarray['{{holiday_date}}']=$date;
					$subarray['{{school_name}}']=$school_name;		

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
			}
			return $this->redirect(['action'=>'holidaylist']);
		}
	}

	public function holidaylist()
	{
		$holiday_table_register=TableRegistry::get('smgt_holiday');

		$fetch_data=$holiday_table_register->find();

		$this->set('row',$fetch_data);
	}	

	public function holidaymultidelete() 
	{
		$this->autoRender = false;
		$id=json_decode($_REQUEST[h_id]);
		foreach($id as $recordid)
			{
					$class = TableRegistry::get('smgt_holiday');
					
					$item =$class->get($recordid);

					if($class->delete($item))
					{
						
					}
					
			}
	}
		
	public function delete($id)
	{

		$holiday_table_register=TableRegistry::get('smgt_holiday');

		$this->request->is(['post','delete']);
		$item=$holiday_table_register->get($id);
		if($holiday_table_register->delete($item))
		{
			$this->Flash->success(__('Holiday Deleted Successfully', null), 
						'default', 
						 array('class' => 'success'));	
			
			return $this->redirect(['action'=>'holidaylist']);
		}
	}

	public function updateholiday($id)
	{
		if($id)
		{
			$id = $this->Setting->my_simple_crypt($id,'d');	
			
			$holiday_table_register=TableRegistry::get('smgt_holiday');
			$exists = $holiday_table_register->exists(['holiday_id' => $id]);
			
			if($exists)
			{
				$Get_Item=$holiday_table_register->get($id);

				if($this->request->is('post'))
				{			
					$data=$this->request->data;
					$data['date']=date("Y-m-d", strtotime($this->request->data('date')));
					$data['end_date']=date("Y-m-d", strtotime($this->request->data('end_date')));
				
					$Get_Item=$holiday_table_register->patchEntity($Get_Item,$data);

					if($holiday_table_register->save($Get_Item))
					{
						$this->Flash->success(__('Holiday Updated Successfully', null), 
							'default', 
							 array('class' => 'alert alert-success'));
							 
						return $this->redirect(['controller'=>'Holiday','action'=>'holidaylist']);
					}
					else
					{
						echo 'Some Error In update Page';
					}
				}
				$this->set('row',$Get_Item);
			}
			else
				return $this->redirect(['controller'=>'Holiday','action'=>'holidaylist']);
		}
		else
			return $this->redirect(['controller'=>'Holiday','action'=>'holidaylist']);
	}   
}
?>	
