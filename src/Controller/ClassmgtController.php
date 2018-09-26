<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;

class ClassmgtController extends AppController
{
	public function addclass()
	{
		$this->set('Classmgt','Classmgt');
		
		$user_id=$this->request->session()->read('user_id');
		
		$a=$this->Classmgt->newEntity();
		
		$query = $this->Classmgt->find();
		$inval = 0;
			if($this->request->is('post'))
			{
				$data=$this->request->data;
				
				foreach($query as $quy){
					if($quy['class_name'] == $this->request->data['class_name'])
						$inval = 1;
				}
				if($inval == 0)
				{
					$data['creater_id']=$user_id;
					$data['created_date']=Time::now();
					$data['modified_date']=Time::now();
					
					$a=$this->Classmgt->patchEntity($a,$data);
					if($this->Classmgt->save($a))
					{
						$this->Flash->success(__('New Class added Successfully', null), 
								'default', 
								 array('class' => 'success'));
					}
				}
				else
				{
					$this->Flash->success(__('Duplicate Class Name', null), 
								'default', 
								 array('class' => 'danger'));
				}
				return $this->redirect(['action'=>'classlist']);
			}
	}
	
	public function classmultidelete() 
	{
		$this->autoRender = false;
		$id=json_decode($_REQUEST[c_id]);
		foreach($id as $recordid)
			{
					$class = TableRegistry::get('classmgt');
					
					$item =$class->get($recordid);

					if($class->delete($item))
					{
						
					}
					
			}
	}
	
	
	public function classlist()
	{
		$this->set('Classmgt','Classmgt');
		
		$query = $this->Classmgt->find();
		
		$this->set('it',$query);
	}
	public function delete($id)
	{
		$this->request->is(['post','delete']);
		
		$item = $this->Classmgt->get($id);
		if($this->Classmgt->delete($item))
		{
			$this->Flash->success(__('Class Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'classlist']);
	}
	public function updateclass($id)
	{
		$this->set('Classmgt','Classmgt');
		
		if($id)
		{
			$id = $this->Setting->my_simple_crypt($id,'d');	
			
			$exists = $this->Classmgt->exists(['class_id' => $id]);
			
			if($exists)
			{
				$item = $this->Classmgt->get($id);

				if($this->request->is(['post','put']))
				{
					$data=$this->request->data;
					
					$data['modified_date']=Time::now();
					
					$item = $this->Classmgt->patchEntity($item,$data);
					
					if($this->Classmgt->save($item))
					{
						$this->Flash->success(__('Class Updated Successfully', null), 
									'default', 
									 array('class' => 'success'));
						
					}
					return $this->redirect(['action'=>'classlist']);
				}
				$this->set('it',$item);
			}
			else
				return $this->redirect(['action'=>'classlist']);
		}
		else
			return $this->redirect(['action'=>'classlist']);
	}
	public function viewsectionlist()
	{
		if(!empty($_REQUEST['class_id']))
		{
			$class = $_REQUEST['class_id'];
			$class_data = '';
			$header = __('Class Section');
			$this->set('header',$header);
			
			$class_tbl = TableRegistry::get('class_section');
			$class_data = $class_tbl->find()->where(['class_id'=>$class,'is_deactive'=>0]);
			$this->set('model_data',$class_data);
			$this->set('model',$class);
		}	
	}
	public function editcriteria()
	{
		$class_section_id = $_REQUEST['class_section_id'];
		
		$class_section = TableRegistry::get('class_section');
		
		$retrieved_data = $class_section->get($class_section_id);
		$header = __('Edit Section');
		$this->set('header',$header);
		$this->set('retrieved_data',$retrieved_data);
		
	}
	public function deleteterm()
	{
		
		$term_id = $_REQUEST['class_section_id'];
		$class_section = TableRegistry::get('class_section');
		$category_data =$class_section->get($term_id);
		$category_data->is_deactive = 1;
		$class_section->save($category_data);
		die();
	}
	public function editterm()
	{
				
		$term_id = $_REQUEST['class_section_id'];
		//var_dump($term_id);die;
		$model = $_REQUEST['model'];
		$class_section = TableRegistry::get('class_section');
		
		$retrieved_data = $class_section->get($term_id);
		
		//echo '<td>'.$i.'</td>';
			echo '<td><input type="text" name="section_name" value="'.$retrieved_data->section_name.'" id="section_name" maxlength="20"></td>';
			echo '<td id='.$retrieved_data->class_section_id.'>
			<a class="btn-cat-update-cancel btn btn-danger" data-type='.$model.' href="#" data-id='.$retrieved_data->class_section_id.'>'.__('Cancel','school-mgt').'</a>
			<a class="btn-cat-update btn btn-primary" data-type='.$model.' href="#" id='.$retrieved_data->class_section_id.'>'.__('Save','school-mgt').'</a>
			</td>';
		die();
	}
	public function cancelterm()
	{
		
		
		$term_id = $_REQUEST['class_section_id'];
		$model = $_REQUEST['model'];
		
		$class_section = TableRegistry::get('class_section');
		$retrieved_data = $class_section->get($term_id);
		
		echo '<td>'.$retrieved_data->section_name.'</td>';
		echo '<td id='.$retrieved_data->class_section_id.'>';
		?>
		<a class="widget-icon widget-icon-dark edit-term" href="#" 
									data-type="<?php echo $retrieved_data->class_id;?>"
									data-id="<?php echo $retrieved_data->class_section_id;?>">
									
									<span class="icon-pencil"></span>
									</a>
									<a class="widget-icon widget-icon-dark remove-term" href="#"
									data-type="<?php echo $retrieved_data->class_id;?>"
									data-id="<?php echo $retrieved_data->class_section_id;?>">
									<span class="icon-trash"></span>
									</a>
		<?php
		echo 	'</td>';
	die();
	}
	public function saveterm()
	{
			
		$term_id = $_REQUEST['class_section_id'];
		$model = $_REQUEST['model'];
		$class_section = TableRegistry::get('class_section');
		$retrieved_data = $class_section->get($term_id);
		//$criteria_terms = TableRegistry::get('criteria_terms');
		//$retrieved_data = $criteria_terms->get($term_id);
	
		$retrieved_data->section_name = $_REQUEST['term_name'];
		$class_section->save($retrieved_data);
	
		echo '<td>'.$_REQUEST['term_name'].'</td>';
		echo '<td id='.$term_id.'>';
		?>
		<a class="widget-icon widget-icon-dark edit-term" href="#" 
									data-type="<?php echo $model;?>"
									data-id="<?php echo $term_id;?>">
									
									<span class="icon-pencil"></span>
									</a>
									<a class="widget-icon widget-icon-dark remove-term" href="#"
									data-type="<?php echo $model;?>"
									data-id="<?php echo $term_id;?>">
									<span class="icon-trash"></span>
									</a>
		<?php
		echo 	'</td>';
	die();
	}
	public function addnewsection()
	{
		$class_section_arry = array();
		if(!empty($_REQUEST['term_name']))
		{		
			$term_id = $_REQUEST['class_id'];
			$model = $_REQUEST['term_name'];
			
			$class_section = TableRegistry::get('class_section');
			$class_section_newentity = $class_section->newEntity();

		
			$class_section_arry['class_id'] = $term_id;
			$class_section_arry['section_name'] = $model;
			$class_section_arry['is_deactive'] = 0;
			$class_section_arry['created_date'] = Time::now();
			$class_section_arry['created_by'] = $this->request->session()->read('user_id');
			
			$class_section_patchentity = $class_section->patchEntity($class_section_newentity,$class_section_arry);
			if($class_section->save($class_section_patchentity))
			{
				$class_section_id = $class_section_patchentity->class_section_id;
				$class_section_name = $class_section_patchentity->section_name;
				
				echo '<tr id=term-'.$class_section_id.'>';
				echo '<td>'.$class_section_name.'</td>';
				echo '<td id='.$class_section_id.'>';
				?>
				<a class="widget-icon widget-icon-dark edit-term" href="#" 
					data-type="<?php echo $term_id;?>"
					data-id="<?php echo $class_section_id;?>">					
					<span class="icon-pencil"></span>
				</a>
				<a class="widget-icon widget-icon-dark remove-term" href="#"
					data-type="<?php echo $term_id;?>"
					data-id="<?php echo $class_section_id;?>">
					<span class="icon-trash"></span>
				</a>
				<?php
				echo 	'</td>';
				echo 	'</tr>';
			}
		}
		else
			echo "false";
		die();
	}
}

?>