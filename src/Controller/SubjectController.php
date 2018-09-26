<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;

class SubjectController extends AppController
{
	public function initialize()
    {
        parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Flash');
    }
	public function addsubject()
	{
		$this->set('Subject','Subject');
		
		$class = TableRegistry::get('Classmgt');
		$query=$class->find();
		$this->set('it',$query);
		
		$class1 = TableRegistry::get('Smgt_users');
		
		$query1=$class1->find()->where(['role'=>'teacher']);
		$this->set('it1',$query1);
		
		
		if($this->request->is('post'))
		{
						
			$class2 = TableRegistry::get('smgt_subject'); 				
			
			$c1=$this->request->data;
			
			$sub_code=$this->request->data['sub_code'];
			
			$chk_code = $this->Setting->check_subject_data($sub_code);
			
			if(!$chk_code)
			{
				if($this->request->data['syllabus'])
				{
					$img=$this->request->data['syllabus'];
					$u="syllabus";
					$fp=WWW_ROOT.$u;	

					$imgname=$img['name'];

					$fpp=$fp.'/'.$imgname;

					if(move_uploaded_file($img['tmp_name'],$fpp))
					{
				
					}
				}
			
				$c1['syllabus']=$this->request->data['syllabus'];
				$c1['syllabus']=$c1['syllabus']['name'];
				
				$a=$class2->newEntity();
				$a=$class2->patchEntity($a,$c1);
				
				if($class2->save($a))
				{
					$this->Flash->success(__('New Subject added Successfully', null), 
							'default', 
							 array('class' => 'success'));
				}
			}
			else
			{
				$this->Flash->error(__('Subject code already exists'),[ 
							'params' => [
								'class' => 'alert alert-error'
						]]);
			}
			return $this->redirect(['action'=>'subjectlist']);
		}
	}
	
	public function subjectmultidelete() 
	{
		$this->autoRender = false;
		$id=json_decode($_REQUEST[s_id]);
		foreach($id as $recordid)
		{
			$class = TableRegistry::get('smgt_subject');			
			$item =$class->get($recordid);

			if($class->delete($item))
			{
				
			}	
		}
	}
	
	public function subjectlist()
	{
		$this->set('Subject','Subject');
		
		$class = TableRegistry::get('smgt_subject');
		$query=$class->find();
		$abc=array();
		$abc1=array();
		$abc2=array();
		foreach ($query as $id) 
		{
			$xyz=$this->Setting->get_user_id($id['teacher_id']);
			$xyz1=$this->Setting->get_class_id($id['class_id']);
			$xyz2=$this->Setting->get_section_name($id['section']);
			
			$abc[]=$xyz;
			$abc1[]=$xyz1;
			$abc2[]=$xyz2;
		}
	
		$this->set('id',$abc);
		$this->set('id1',$abc1);
		$this->set('id2',$abc2);
		
		$this->set('it',$query);
			
		$class1 = TableRegistry::get('smgt_users');
		$class2 = TableRegistry::get('classmgt');
			
		$query1=$class1->find();
		$this->set('it1',$query1);
		
		$query2=$class2->find();
		$this->set('it2',$query2);
		
	}
	public function delete($id)
	{
		$this->request->is(['post','delete']);
		$class1 = TableRegistry::get('smgt_subject');
		$item = $class1->get($id);
		if($class1->delete($item))
		{
			$this->Flash->success(__('Subject Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'subjectlist']);
	}
	public function updatesubject($id)
	{
		$this->set('Subject','Subject');
		
		if($id)
		{
			$id = $this->Setting->my_simple_crypt($id,'d');	
			
			$class1 = TableRegistry::get('smgt_users');	
			$query1=$class1->find()->where(['role'=>'teacher']);			
			
			foreach($query1 as $it5)
			{
				$name=$it5['first_name']." ".$it5["last_name"];
				$b[$it5['user_id']]=$name;
			} 
			$this->set('it1',$b);
			
			$class_data = TableRegistry::get('Classmgt');			
			$cls = $class_data->find("list",["keyField"=>"class_id","valueField"=>"class_name"]);			
			$this->set('it2',$cls);
			
			$section_data = TableRegistry::get('class_section');
			$sect = $section_data->find("list",["keyField"=>"class_section_id","valueField"=>"section_name"]);
			$this->set('sect',$sect);			
		
			$class = TableRegistry::get('smgt_subject');
			$exists = $class->exists(['subid' => $id]);
			
			if($exists)
			{			
				$item = $class->get($id);
				
				$old_sub_code = $item->sub_code;
				
				if($this->request->is(['post','put']))
				{
					
					$sub_code=$this->request->data['sub_code'];
					
					if($old_sub_code == $sub_code)
					{
						$img=$this->request->data['syllabus']['name'];
						$img2=$this->request->data('file2');
						
						
						if(!$img)
						{
							$this->request->data['syllabus']['name']=$img2;
							unset($this->request->data['file2']);
							unset($this->request->data['syllabus']);
							$this->request->data['syllabus']=$img2;								
						}
						else
						{
							unset($this->request->data['file2']);
							unset($this->request->data['syllabus']);
							$this->request->data['syllabus']=$img;		
						}
				
						$item = $class->patchEntity($item,$this->request->data);
						
						if($class->save($item))
						{
							$this->Flash->success(__('Subject Updated Successfully', null), 
								'default', 
								 array('class' => 'success'));
							
						}
					}
					else
					{	
						$chk_code = $this->Setting->check_subject_data($sub_code);
						
						if(!$chk_code)
						{					
							$img=$this->request->data['syllabus']['name'];
							$img2=$this->request->data('file2');
														
							if(!$img)
							{
								$this->request->data['syllabus']['name']=$img2;
								unset($this->request->data['file2']);
								unset($this->request->data['syllabus']);
								$this->request->data['syllabus']=$img2;								
							}
							else
							{
								unset($this->request->data['file2']);
								unset($this->request->data['syllabus']);
								$this->request->data['syllabus']=$img;		
							}					
							$item = $class->patchEntity($item,$this->request->data);
							
							if($class->save($item))
							{
								$this->Flash->success(__('Subject Updated Successfully', null), 
									'default', 
									 array('class' => 'success'));
								
							}
						}
						else
						{
							$this->Flash->error(__('Subject code already exists'),[ 
										'params' => [
											'class' => 'alert alert-error'
									]]);
						}
					}					
					return $this->redirect(['action'=>'subjectlist']);
				}
				$this->set('it',$item);
			}
			else
				return $this->redirect(['action'=>'subjectlist']);
		}
		else
			return $this->redirect(['action'=>'subjectlist']);
	}
	
	public function readfile($readfile = NULL)
	{
		$this->set('file',$readfile);
		
		$file = WWW_ROOT.'syllabus'.DS.$readfile;
		// var_dump($file);die;
		if (file_exists($file)) 
		{
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.$file.'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			readfile($file);
			exit;
		}
		else
		{
			$this->redirect(['action'=>'readfile']);	
		}
	}
}

?>