<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Controller\Component;
use Cake\I18n\Time;
use PHPExcel;
use PHPExcel_Helper_HTML;
use PHPExcel_Writer_Excel2007;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Datasource\ConnectionManager;
use Gmgt_paypal_class;
use Cake\Mailer\Email;

class HallController extends AppController
{
	public function addhall()
	{

		$hall_table_register=TableRegistry::get('smgt_hall');
		$hall_table_entity=$hall_table_register->newEntity();

		if($this->request->is('post')){

			$data=$this->request->data;

			$data['date']=Time::now();

			$insert_data=$hall_table_register->patchEntity($hall_table_entity,$data);

			if($hall_table_register->save($insert_data))
			{
				$this->Flash->success(__('Exam Hall added Successfully', null), 
									'default', 
									 array('class' => 'success'));
			}
			return $this->redirect(['action'=>'halllist']);
		}

	}

	public function halllist()
    {
    	$hall_table_register=TableRegistry::get('smgt_hall');
    	$fetch_data=$hall_table_register->find();
    	$this->set('row',$fetch_data);
    }

    public function updatehall($id)
	{
		if($id)
		{
			$id = $this->Setting->my_simple_crypt($id,'d');	
			
			$hall_table_register=TableRegistry::get('smgt_hall');
			$exists = $hall_table_register->exists(['hall_id' => $id]);
			
			if($exists)
			{
				$Get_Item=$hall_table_register->get($id);

				if($this->request->is('post'))
				{
					$Get_Item=$hall_table_register->patchEntity($Get_Item,$this->request->data);

					if($hall_table_register->save($Get_Item))
					{
						$this->Flash->success(__('Exam Hall Updated Successfully', null), 
								'default', 
								 array('class' => 'alert alert-success'));
						
						return $this->redirect(['controller'=>'Hall','action'=>'halllist']);			
					}
					else
					{
						echo 'Some Error in Update Part';
					}
				}
				$this->set('row',$Get_Item);
			}
			else
				return $this->redirect(['action'=>'halllist']);
		}
		else
			return $this->redirect(['action'=>'halllist']);
    }
	
	public function hallmultidelete() 
	{
		$this->autoRender = false;
		$id=json_decode($_REQUEST[h_id]);
		foreach($id as $recordid)
		{
			$class = TableRegistry::get('smgt_hall');			
			$item =$class->get($recordid);
			if($class->delete($item))
			{				
			}		
		}
	}
	
    public function delete($id)
	{
    	$hall_table_register=TableRegistry::get('smgt_hall');
    	$this->request->is(['post','delete']);
    	$item=$hall_table_register->get($id);
    	
		if($hall_table_register->delete($item))
		{
			$this->Flash->success(__('Exam Hall Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));						 
    	}
		return $this->redirect(['controller'=>'Hall','action'=>'halllist']);
    }
	
	public function examhallreceipt()
    {
		$this->set('exam_id',false);
		
		$t = 0;
		
		$user_id=$this->request->session()->read('user_id');
		
		$smgt_exam=TableRegistry::get('smgt_exam');			
		$query=$smgt_exam->find();		
		$this->set('exam_data',$query);

		$exam_time_table=TableRegistry::get('exam_time_table');	
		$query1=$exam_time_table->find();		
		$this->set('subject_data',$query1);
		
		$smgt_hall=TableRegistry::get('smgt_hall');			
		$query2=$smgt_hall->find();
		$this->set('hall_data',$query2);
    }
	public function studentexamhall()
	{
		$this->set('hall_id',false);
		
		$exam_id = $_REQUEST['exam_id'];
		$this->set('exam_id',$exam_id);
		
		$smgt_exam=TableRegistry::get('smgt_exam');
		$smgt_user=TableRegistry::get('smgt_users');
		$smgt_hall=TableRegistry::get('smgt_hall');	
		
		$query2=$smgt_hall->find();
		$this->set('hall_data',$query2);
		
		$exists = $smgt_exam->exists(['exam_id' => $exam_id]);
		
		if($exists)
		{
			$Get_Item = $smgt_exam->find()->where(['exam_id'=>$exam_id])->hydrate(false)->toArray();
			if(!empty($Get_Item))
			{
				$class_id = $Get_Item[0]['class_id'];
				$section_id = $Get_Item[0]['section_id'];
				
				$this->set('class_id',$class_id);
				$this->set('section_id',$section_id);
				
				$conn=ConnectionManager::get('default');
				$Student_Item=$conn->execute("select * from smgt_users 
				where role = 'student' 
				and classname = ".$class_id."
				and classsection = ".$section_id."
				and user_id 
				not in 
				( SELECT u.user_id FROM `smgt_users` as u, 
				smgt_exam_hall_receipt as e where	
				e.exam_id=".$exam_id." 
				and e.user_id=u.user_id
				)")->fetchAll('assoc');
				
				$Student_Item1=$conn->execute("
				SELECT u.* FROM `smgt_users` as u, 
				smgt_exam_hall_receipt as e where
				u.role = 'student' 
				and u.classname = ".$class_id."
				and u.classsection = ".$section_id."	
				and e.exam_id=".$exam_id." 
				and e.user_id=u.user_id")->fetchAll('assoc');
				
				if(!empty($Student_Item))
					$this->set('student_data',$Student_Item);
				if(!empty($Student_Item1))
					$this->set('student_data1',$Student_Item1);
			}
		}
	}
	
	public function assgnexamhall() 
	{
		$this->autoRender = false;
		$i=0;
		$user_id=$this->request->session()->read('user_id');
		
		$id=json_decode($_REQUEST['h_id']);
		$exam_id = $_REQUEST['exam_id'];
		$hall_id = $_REQUEST['hall_id'];
		
		$data_return = "";
		
		if($id)
		{
			foreach($id as $recordid)
			{
				$class = TableRegistry::get('smgt_users');		
				$item =$class->get($recordid);
				$item->exam_hall_receipt = 1;
				
				if($class->save($item))
				{
					$data = array();
					
					$smgt_exam_hall_receipt = TableRegistry::get('smgt_exam_hall_receipt');
					$hall_receipt_entity=$smgt_exam_hall_receipt->newEntity();
					
					$data['exam_id']=$exam_id;
					$data['user_id']=$recordid;
					$data['hall_id']=$hall_id;
					$data['created_date']=date('Y-m-d');
					$data['created_by']=$user_id;
					
					$email_id = $this->Setting->get_user_email_id($recordid);

					$insert_data=$smgt_exam_hall_receipt->patchEntity($hall_receipt_entity,$data);
					if($smgt_exam_hall_receipt->save($insert_data))
					{
						$receipt_id = $insert_data['receipt_id'];
						$data = $smgt_exam_hall_receipt->get($receipt_id);
						$data->exam_hall_receipt_status = 1;
						$smgt_exam_hall_receipt->save($data);
						
						$pid = $this->Setting->my_simple_crypt($receipt_id,'e');														
						
						$data_return .= "<tr id='".$recordid."'>
						<td> 
								<button type='button' class='btn btn-danger btn-xs btn_del' dataid='".$recordid."'>X</button>
							  </td>
						<td>".$item->first_name." ".$item->last_name."</td>
						<td>".$item->studentID_prefix.$item->studentID."</td>
						</tr>";
						
						$server = $_SERVER['SERVER_NAME'];
						if($server != '192.168.1.22')
							$this->Setting->mail_examhall_pdf($email_id,$pid);
						
						$i=1;				
					}		
				}		
			}
			echo $data_return;
		}
		else
			echo "false";
		die();
	}
	
	public function removeexamhall() 
	{
		$this->autoRender = false;
		$i=0;
		
		$user_id=$this->request->session()->read('user_id');
		
		$userid = $_REQUEST['userid'];
		$exam_id = $_REQUEST['exam_id'];
		
		$class = TableRegistry::get('smgt_users');		
		$item =$class->get($userid);
		$item->exam_hall_receipt = 0;
		
		if($class->save($item))
		{
			$smgt_exam_hall_receipt = TableRegistry::get('smgt_exam_hall_receipt');
			$exam_hall_data = $smgt_exam_hall_receipt->find()->where(['exam_id'=>$exam_id,'user_id'=>$userid])->hydrate(false)->toArray();
			
			if(!empty($exam_hall_data))
			{
				$receipt_id = $exam_hall_data[0]['receipt_id'];
				$item1 =$smgt_exam_hall_receipt->get($receipt_id);
				if($smgt_exam_hall_receipt->delete($item1))
				{
					$i=1;
					
					echo '<tr id="'.$userid.'">';
					echo "<td> 
						<p style='display:none;'>".$userid."</p>
						<input type='checkbox' class='checkbox ch_pend' name='id[]' dataid='".$userid."'> 
					</td>";
					echo '<td>'.$item->first_name." ".$item->last_name.'</td>';
					echo '<td>'.$item->studentID_prefix.$item->studentID.'</td>';
					echo '</tr>';						
				}
			}
		}
		else
			echo "false";
		die();
	}
	
	public function view2($id = null) 
	{	
		$this->autoRender = false;
		
		if($this->request->is('ajax'))
		{
			$cls = $_POST['id'];
			
			$post = TableRegistry::get('exam_time_table');
			$data = $post->find()->where(["exam_id"=>$cls])->hydrate(false)->toArray();
			if(!empty($data))
			{
			?>
				<option value=""> <?php echo __('Select Subject'); ?> </option>
				<?php
				foreach($data as $option)
				{
					echo "<option value='".$option['subject_id']."'>".$this->Setting->get_subject_id($option['subject_id'])."</option>";
				}
				die;
			}	
		}
	}
}

?>
