<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\Model\Smgt_subject;
use Cake\View\Helper\FlashHelper;
use Cake\ORM\TableRegistry; 

class ClassrouteController extends AppController
{
	public function initialize()
	{
       	parent::initialize();
		$this->loadComponent('Setting');
	}
	public function addclassroute()
	{
		$this->set('Classroute','Classroute');
		
		$class1 = TableRegistry::get('Classmgt');
		
		$query1=$class1->find();
		$this->set('it',$query1);
		
		$class2 = TableRegistry::get('smgt_subject');
		
		$query2=$class2->find();
		$this->set('sub',$query2);
		
		$class3 = TableRegistry::get('smgt_users');
		
		$query3=$class3->find()->where(['role'=>'teacher']);
		$this->set('usr',$query3);
		
		$class4=TableRegistry::get('smgt_time_table');
		
		$check=$class4->find();
		
		$a=$class4->newEntity();
		
		if($this->request->is('post'))
		{
			$isaccept = '';
			
			$c1=$this->request->data;
			
			$s1=$this->request->data('start_hour');
			$s2=$this->request->data('start_min');
			$s3=$this->request->data('start_ampm');
			
			$e1=$this->request->data('end_hour');
			$e2=$this->request->data('end_min');
			$e3=$this->request->data('end_ampm');
		
			$st=$s1.":".$s2.":".$s3;
			$c1['start_time']=$st;
			
			$et=$e1.":".$e2.":".$e3;
			$c1['end_time']=$et;
						
			$isaccept = $this->Setting->is_route_exist($c1);
			
			if($isaccept == 'duplicate')
			{
				$this->Flash->success(__('Duplicate Class Route', null), 
							'default', 
							 array('class' => 'success'));
							 
				return $this->redirect(['action'=>'addclassroute']);
			}
			if($isaccept == 'teacher_duplicate')
			{
				$this->Flash->success(__('Duplicate Teacher Class Route', null), 
							'default', 
							 array('class' => 'success'));
							 
				return $this->redirect(['action'=>'addclassroute']);
			}
			if($isaccept == 'success')
			{
				$a=$class4->patchEntity($a,$c1);
				if($class4->save($a))
				{
						$this->Flash->success(__('Class route added Successfully', null), 
							'default', 
							 array('class' => 'success'));	
				}
				return $this->redirect(['action'=>'classroutelist']);
			}				
		}	
	}
				
	public function classroutesection($sid = null) {
	$this->autoRender = false;
	   if($this->request->is('ajax')){
	$cls = $_POST['sid'];

	$post = TableRegistry::get('class_section');
	$data = $post->find()->where(["class_id"=>$cls])->hydrate(false)->toArray();
	if(!empty($data))
	{
		
			?>
			<option value="">Select Section</option>
			<?php
			foreach($data as $option)
			{
				echo "<option value='{$option['class_section_id']}'>{$option['section_name']}</option>";
			}


	}
	   }
	}	
	
				
	public function classroutelist()
	{
		$this->set('Classroute','Classroute');
		
		$class=TableRegistry::get('smgt_time_table');
		$aa=$class->find();
		
		
		$class_route = array();
		foreach($aa as $class_id)
		{	
			$classname=$this->Setting->get_class_id($class_id['class_id']);
			$classsection=$this->Setting->get_section_name($class_id['class_id']);
			
			$c_id=$class_id['class_id'];
		
			$xyz=$this->Setting->sgmt_day_list($class_id['weekday']);
			
			
			foreach($xyz as $key => $value)
			{
			
				$period = $this->Setting->get_period($c_id,$key);	
				
				foreach($period as $data)
				{

					$subjectname=$this->Setting->get_subject_id($data['subject_id']);
					$teachername=$this->Setting->get_user_id($data['teacher_id']);
					
					$class_route[$c_id]['classname']=$classname;
					$class_route[$c_id]['classsection']=$classsection;
				
					$class_route[$c_id][$key][$data['route_id']] = array('class'=>$c_id,'day'=>$value,'subject'=>$subjectname,'teacher'=>$teachername,'stime'=>$data['start_time'],'etime'=>$data['end_time'],'route_id'=>$data['route_id']);

				}
			}
		}
		$this->set('class_route',$class_route);
		
		$xyz=$this->Setting->sgmt_day_list();
		$class_list=$this->Setting->get_class_list();
		$teachername=$this->Setting->get_user_id(@$data['teacher_id']);
		
		$this->set('daywk',$xyz);
		if(!empty($class_list))
			$this->set('class_list',$class_list);
		if(!empty($teachername))
			$this->set('teacher_name',$teachername);	
	}
	public function teacherroutelist()
    {
		$this->set('Classroute','Classroute');
		
		$class=TableRegistry::get('smgt_time_table');
		$aa=$class->find();
		
		
		$class_route = array();
		foreach($aa as $class_id)
		{	
			$classname=$this->Setting->get_class_id($class_id['class_id']);
			
			$c_id=$class_id['class_id'];
		
			$xyz=$this->Setting->sgmt_day_list($class_id['weekday']);
			
			
			foreach($xyz as $key => $value)
			{
			
				$period = $this->Setting->get_period($c_id,$key);	
				
				foreach($period as $data)
				{
					$subjectname=$this->Setting->get_subject_id($data['subject_id']);

					$class_route[$c_id]['classname']=$classname;
				
					$class_route[$c_id][$key][$data['route_id']] = array('class'=>$c_id,'class_name'=>$classname,'day'=>$value,'subject'=>$subjectname,'teacher'=>$data['teacher_id'],'stime'=>$data['start_time'],'etime'=>$data['end_time'],'route_id'=>$data['route_id']);

				}
			}
		}
		$this->set('class_route',$class_route);
		
		$xyz=$this->Setting->sgmt_day_list();
		$class_list=$this->Setting->get_class_list();
		$teachername=$this->Setting->get_user_list();
	
		$this->set('daywk',$xyz);
		if(!empty($class_list))
			$this->set('class_list',$class_list);
		if(!empty($teachername))
			$this->set('teacher_name',$teachername);	
    }
	public function delete($id)
	{
		$class=TableRegistry::get('smgt_time_table');
		
		$this->request->is(['post','delete']);
		
		$item = $class->get($id);
		if($class->delete($item))
		{
			return $this->redirect(['action'=>'classroutelist']);
		}
	}
	public function updateclassroute($id)
	{
		$this->set('Classroute','Classroute');
		if($id)
		{	
			$id = $this->Setting->my_simple_crypt($id,'d');	
			
			$class=TableRegistry::get('smgt_time_table');
			$exists = $class->exists(['route_id' => $id]);
			
			if($exists)
			{
				$item = $class->get($id);
				
				if($this->request->is(['post','put']))
				{
					$c1=$this->request->data;
						
					$s1=$this->request->data('start_hour');
					$s2=$this->request->data('start_min');
					$s3=$this->request->data('start_ampm');
						
					$e1=$this->request->data('end_hour');
					$e2=$this->request->data('end_min');
					$e3=$this->request->data('end_ampm');
					
					$st=$s1.":".$s2.":".$s3;
					$c1['start_time']=$st;
						
					$et=$e1.":".$e2.":".$e3;
					$c1['end_time']=$et;
					
					$start_time = $item->start_time;
					$end_time = $item->end_time;
					
					if($c1['start_time'] == $start_time && $c1['end_time'] == $end_time)
					{
						$item = $class->patchEntity($item,$c1);
						if($class->save($item))
						{
								$this->Flash->success(__('Class Route Updated Successfully', null), 
									'default', 
									 array('class' => 'success'));	
						}
						return $this->redirect(['action'=>'classroutelist']);
					}
					else
					{
						$isaccept = $this->Setting->is_route_exist($c1);
						
						if($isaccept == 'duplicate')
						{
							$this->Flash->success(__('Duplicate Class Route', null), 
										'default', 
										 array('class' => 'success'));
										 
							return $this->redirect(['action'=>'updateclassroute',$id]);
						}
						if($isaccept == 'teacher_duplicate')
						{
							$this->Flash->success(__('Duplicate Teacher Class Route', null), 
										'default', 
										 array('class' => 'success'));
										 
							return $this->redirect(['action'=>'updateclassroute',$id]);
						}
						if($isaccept == 'success')
						{
							$item = $class->patchEntity($item,$c1);
							if($class->save($item))
							{
									$this->Flash->success(__('Class Route Updated Successfully', null), 
										'default', 
										 array('class' => 'success'));	
							}
							return $this->redirect(['action'=>'classroutelist']);
						}
					}
				}
				$this->set('it',$item);
				
				$class1 = TableRegistry::get('smgt_users');		
				$query1=$class1->find()->where(['role'=>'teacher']);
				foreach($query1 as $it5)
				{
					$name=$it5['first_name']." ".$it5["last_name"];
					$b[$it5['user_id']]=$name;
				} 
				$this->set('usr',$b);
				
				$class_data = TableRegistry::get('Classmgt');			
				$cls = $class_data->find("list",["keyField"=>"class_id","valueField"=>"class_name"]);			
				$this->set('cls',$cls);
				
				$class3 = TableRegistry::get('smgt_subject');		
				$sub = $class3->find("list",["keyField"=>"subid","valueField"=>"sub_name"]);			
				$this->set('sub',$sub);
				
				$section_data = TableRegistry::get('class_section');
				$sect = $section_data->find("list",["keyField"=>"class_section_id","valueField"=>"section_name"]);
				$this->set('sect',$sect);
			}
			else
				return $this->redirect(['action'=>'classroutelist']);
		}
		else
			return $this->redirect(['action'=>'classroutelist']);
	}
	public function updateteacherroute($id)
	{
		$this->set('Classroute','Classroute');
		
		if($id)
		{
			$id = $this->Setting->my_simple_crypt($id,'d');	
			
			$class=TableRegistry::get('smgt_time_table');
			$exists = $class->exists(['route_id' => $id]);
			
			if($exists)
			{
				$item = $class->get($id);
				
				if($this->request->is(['post','put']))
				{
					$c1=$this->request->data;
						
					$s1=$this->request->data('start_hour');
					$s2=$this->request->data('start_min');
					$s3=$this->request->data('start_ampm');
						
					$e1=$this->request->data('end_hour');
					$e2=$this->request->data('end_min');
					$e3=$this->request->data('end_ampm');
					
					$st=$s1.":".$s2.":".$s3;
					$c1['start_time']=$st;
						
					$et=$e1.":".$e2.":".$e3;
					$c1['end_time']=$et;
					
					$start_time = $item->start_time;
					$end_time = $item->end_time;
					
					if($c1['start_time'] == $start_time && $c1['end_time'] == $end_time)
					{
						$item = $class->patchEntity($item,$c1);
						if($class->save($item))
						{
								$this->Flash->success(__('Class Route Updated Successfully', null), 
									'default', 
									 array('class' => 'success'));	
						}
						return $this->redirect(['action'=>'teacherroutelist']);
					}
					else
					{
						$isaccept = $this->Setting->is_route_exist($c1);
						
						if($isaccept == 'duplicate')
						{
							$this->Flash->success(__('Duplicate Class Route', null), 
										'default', 
										 array('class' => 'success'));
										 
							return $this->redirect(['action'=>'updateteacherroute',$id]);
						}
						if($isaccept == 'teacher_duplicate')
						{
							$this->Flash->success(__('Duplicate Teacher Class Route', null), 
										'default', 
										 array('class' => 'success'));
										 
							return $this->redirect(['action'=>'updateteacherroute',$id]);
						}
						if($isaccept == 'success')
						{
							$item = $class->patchEntity($item,$c1);
							if($class->save($item))
							{
									$this->Flash->success(__('Class Route Updated Successfully', null), 
										'default', 
										 array('class' => 'success'));	
							}
							return $this->redirect(['action'=>'teacherroutelist']);
						}
					}
				}
				$this->set('it',$item);
				
				$class1 = TableRegistry::get('smgt_users');		
				$query1=$class1->find()->where(['role'=>'teacher']);
				foreach($query1 as $it5)
				{
					$name=$it5['first_name']." ".$it5["last_name"];
					$b[$it5['user_id']]=$name;
				} 
				$this->set('usr',$b);
				
				$class_data = TableRegistry::get('Classmgt');			
				$cls = $class_data->find("list",["keyField"=>"class_id","valueField"=>"class_name"]);			
				$this->set('cls',$cls);
				
				$class3 = TableRegistry::get('smgt_subject');		
				$sub = $class3->find("list",["keyField"=>"subid","valueField"=>"sub_name"]);			
				$this->set('sub',$sub);
				
				$section_data = TableRegistry::get('class_section');
				$sect = $section_data->find("list",["keyField"=>"class_section_id","valueField"=>"section_name"]);
				$this->set('sect',$sect);
			}
			else
				return $this->redirect(['action'=>'teacherroutelist']);
		}
		else
			return $this->redirect(['action'=>'teacherroutelist']);
	}	
}

?>