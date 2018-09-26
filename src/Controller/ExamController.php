<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;
use Cake\Mailer\Email;

class ExamController extends AppController
{
	public function initialize()
	{
	   parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Flash');
	}
	public function addexam()
	{
			$table_class=TableRegistry::get('classmgt');
			$class=$table_class->find();
			$this->set('class_data',$class);
			
			$tbl_term=TableRegistry::get('tbl_term');
			$term_data=$tbl_term->find()->where(['term_status'=>0]);
			$this->set('term_data',$term_data);
			
			$exam_table_register=TableRegistry::get('smgt_exam');
			$exam_table_entity=$exam_table_register->newEntity();

			if($this->request->is('post'))
			{
				$data=$this->request->data;
				
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
				
				$db_cl = array();
				
				$db_cl['exam_name']=$data['exam_name'];																																		
				$db_cl['class_id']=$data['class_name'];																																		
				$db_cl['section_id']=$data['section'];	
				$db_cl['term_id']=$data['term_id'];		
				$db_cl['pass_mark']=$data['pass_mark'];																																																																							
				$db_cl['total_mark']=$data['total_mark'];																																		
				$db_cl['exam_date']=date("Y-m-d", strtotime($data['exam_date']));
				$db_cl['exam_end_date']=date("Y-m-d", strtotime($data['exam_end_date']));
				$db_cl['exam_comment']=$data['exam_comment'];
				$db_cl['syllabus'] = $data['syllabus']['name'];
				$db_cl['created_date']=date("Y-m-d");
				$db_cl['modified_date']=date("Y-m-d");
				
				$course=$exam_table_register->patchEntity($exam_table_entity,$db_cl);
				
				if($exam_table_register->save($course))
				{
					$this->Flash->success(__(' Exam added Successfully', null), 
							'default', 
							 array('class' => 'success'));

				}
				return $this->redirect(['action'=>'examlist']);
			}
	}	
	public function adddata($id = null) 
	{
		$get_current_user_id=$this->request->session()->read('user_id');
		
		$this->autoRender = false;
        if($this->request->is('ajax'))
		{
			if(!empty($_POST['term_name']))
			{
				$cls = $_POST['term_name'];
				
				$cat = TableRegistry::get('tbl_term');
				$a = $cat->newEntity();

				$a['term_name']=$cls;
				$a['created_by']=$get_current_user_id;
				
				if($cat->save($a))
				{
					$i=$a['term_id'];
				}
				echo $i;
			}
			else
				echo "false";
            die();
       }
	}
	public function termDelete($id = null)
	{
		$this->autoRender = false;
		if($this->request->is('ajax'))
		{
			$term_id=$_POST['term_id'];
			$cat = TableRegistry::get('tbl_term');
			$items=$cat->get($term_id);
			$items->term_status = 1;
			if($cat->save($items))
			{
				$this->Flash->success(__('Term Deleted Successfully', null), 
										'default', 
										array('class' => 'success'));	
			}
		}
	}	
	public function exammultidelete() 
	{
		$this->autoRender = false;
		$id=json_decode($_REQUEST[e_id]);
		foreach($id as $recordid)
			{
					$class = TableRegistry::get('smgt_exam');
					
					$item =$class->get($recordid);

					if($class->delete($item))
					{
						
					}
					
			}
	}
	
	
	public function delete($id){

			$class = TableRegistry::get('smgt_exam');
			$this->request->is(['post','delete']);
			$item = $class->get($id);
			if($class->delete($item)){
				
				$this->Flash->success(__('Exam Deleted Successfully', null), 
						'default', 
						 array('class' => 'success'));	
			}
			return $this->redirect(['action'=>'examlist']);
	}

	public function updateexam($id)
	{
		$this->set('edit',true);
		
		$table_class=TableRegistry::get('classmgt');
		$class=$table_class->find();
		$this->set('class_data',$class);
		
		$tbl_term=TableRegistry::get('tbl_term');
		$term_data=$tbl_term->find()->where(['term_status'=>0]);
		$this->set('term_data',$term_data);
			
		if($id)
		{
			$id = $this->Setting->my_simple_crypt($id,'d');	
			
			$Table_Registry=TableRegistry::get('smgt_exam');
			$exists = $Table_Registry->exists(['exam_id' => $id]);
			
			if($exists)
			{
				$Get_Item=$Table_Registry->get($id);

				if($this->request->is('post'))
				{
						$data=$this->request->data;
						
						$db_cl = array();
						
						$img=$this->request->data['syllabus']['name'];
						$img2=$this->request->data('file2');
						
						
						if(!$img)
						{
							$this->request->data['syllabus']['name']=$img2;
							unset($this->request->data['file2']);
							unset($this->request->data['syllabus']);
							$db_cl['syllabus']=$img2;								
						}
						else
						{
							if($this->request->data['syllabus'])
							{
								$img1=$this->request->data['syllabus'];
								$u="syllabus";
								$fp=WWW_ROOT.$u;	

								$imgname=$img1['name'];

								$fpp=$fp.'/'.$imgname;

								if(move_uploaded_file($img1['tmp_name'],$fpp))
								{
							
								}
							}
							
							unset($this->request->data['file2']);
							unset($this->request->data['syllabus']);
							
							$db_cl['syllabus']=$img;		
						}
						
						$db_cl['exam_name']=$data['exam_name'];	
						$db_cl['class_id']=$data['class_name'];																																		
						$db_cl['section_id']=$data['section'];	
						$db_cl['term_id']=$data['term_id'];
						$db_cl['pass_mark']=$data['pass_mark'];																																																																							
						$db_cl['total_mark']=$data['total_mark'];	
						$db_cl['exam_date']=date("Y-m-d", strtotime($data['exam_date']));
						$db_cl['exam_end_date']=date("Y-m-d", strtotime($data['exam_end_date']));
						$db_cl['exam_comment']=$data['exam_comment'];
						if(isset($data['syllabus']['name'])){
						$db_cl['syllabus'] = $data['syllabus']['name'];}
						$db_cl['created_date']=date("Y-m-d");
						$db_cl['modified_date']=date("Y-m-d");
				
						$Get_Item=$Table_Registry->patchEntity($Get_Item,$db_cl);
						if($Table_Registry->save($Get_Item))
						{
							$this->Flash->success(__('Exam Updated Successfully', null), 
								'default', 
								 array('class' => 'alert alert-success'));
								
								return $this->redirect(['controller'=>'Exam','action'=>'examlist']);
						}
						else
						{
							echo 'Some Error in Update Page';
						}
				}
				$this->set('row',$Get_Item);
			}
			else
				return $this->redirect(['controller'=>'Exam','action'=>'examlist']);
		}
		else
			return $this->redirect(['controller'=>'Exam','action'=>'examlist']);
	}

	public function examlist()
	{
		$exam_table_register=TableRegistry::get('smgt_exam');
		$fetch_data=$exam_table_register->find();
		$this->set('row',$fetch_data);
	}
	
	public function examtimetable()
    {
		$this->set('exam_id',false);
		$t = 0;
		
		$user_id=$this->request->session()->read('user_id');
		
		$smgt_exam=TableRegistry::get('smgt_exam');	
		$smgt_subject=TableRegistry::get('smgt_subject');	
		$exam_time_table=TableRegistry::get('exam_time_table');	
		
		$query1=$smgt_exam->find();
		$this->set('exam_data',$query1);
		
		if(isset($_POST['manage_exam']))
		{
			$exam_id=$_POST['exam_id'];
			$this->set('exam_id',$exam_id);	
			
			$query=$smgt_exam->find()->where(['exam_id'=>$exam_id])->hydrate(false)->toArray();
			
			if(!empty($query))
			{
				$exam_name = $query[0]['exam_name'];
				$class_id = $query[0]['class_id'];
				$section_id = $query[0]['section_id'];
				$term_id = $query[0]['term_id'];
				$exam_date = $query[0]['exam_date'];
				$exam_end_date = $query[0]['exam_end_date'];
				
				$this->set('exam_name',$exam_name);
				$this->set('class_id',$class_id);
				$this->set('section_id',$section_id);
				$this->set('term_id',$term_id);
				$this->set('exam_date',$exam_date);
				$this->set('exam_end_date',$exam_end_date);
				
				$subject_query = $smgt_subject->find()->where(['class_id'=>$class_id,'section'=>$section_id])->hydrate(false)->toArray();
				if(!empty($subject_query))
					$this->set('subject_data',$subject_query);	
			}
		}
		
		if(isset($_POST['save_exam_time']))
		{			
			$exam_id=$_POST['exam_id'];
			$class_id=$_POST['class_id'];
			$section_id=$_POST['section_id'];
			
			$subject_query = $smgt_subject->find()->where(['class_id'=>$class_id,'section'=>$section_id])->hydrate(false)->toArray();	
		
			if(!empty($subject_query))
			{
				foreach($subject_query as $subject_data)
				{
					$subject_id = $subject_data['subid'];
					$exam_date = $_POST['date_'.$subject_id];
					$start_hour = $_POST["start_hour_".$subject_id];
					$start_min = $_POST["start_min_".$subject_id];
					$start_ampm = $_POST["start_ampm_".$subject_id];
					$end_hour = $_POST["end_hour_".$subject_id];
					$end_min = $_POST["end_min_".$subject_id];
					$end_ampm = $_POST["end_ampm_".$subject_id];
					
					$st=$start_hour.":".$start_min.":".$start_ampm;
					$ed=$end_hour.":".$end_min.":".$end_ampm;
					
					$exam_data=$this->Setting->check_exam_id($exam_id,$subject_id);
					
					if(!$exam_data)
					{
						$exam_entity=$exam_time_table->newEntity();
						
						$data['exam_id']=$exam_id;
						$data['subject_id']=$subject_id;
						$data['exam_date']=date("Y-m-d", strtotime($exam_date));
						$data['start_time']=$st;
						$data['end_time']=$ed;
						$data['created_date']=date("Y-m-d");
						$data['created_by']=$user_id;						
						
						$exam_patch_entity=$exam_time_table->patchEntity($exam_entity,$data);
						
						if($exam_time_table->save($exam_patch_entity))
						{
							$t=1;
						}
					}
					else
					{
						$id = $exam_time_table->get($exam_data);
						
						$data['exam_id']=$exam_id;
						$data['subject_id']=$subject_id;
						$data['exam_date']=date("Y-m-d", strtotime($exam_date));
						$data['start_time']=$st;
						$data['end_time']=$ed;
						$data['created_date']=date("Y-m-d");
						$data['created_by']=$user_id;						
						
						$exam_patch_entity=$exam_time_table->patchEntity($id,$data);
						
						if($exam_time_table->save($exam_patch_entity))
						{
							$t=2;
						}
					}
				}
			}
		}
		if($t == 1)
		{
			$this->Flash->success(__('Exam Time Table Successful', null), 
							   'default', 
								array('class' => 'success'));
		}
		if($t == 2)
		{
			$this->Flash->success(__('Edit Exam Time Table Successful', null), 
							   'default', 
								array('class' => 'success'));
		}
    }
	
	public function viewexamtimetable($exam_id)
    {
		$t = 0;
		
		$user_id=$this->request->session()->read('user_id');
		
		$smgt_exam=TableRegistry::get('smgt_exam');	
		$smgt_subject=TableRegistry::get('smgt_subject');	
		$exam_time_table=TableRegistry::get('exam_time_table');	
		
		$query1=$smgt_exam->find();
		$this->set('exam_data',$query1);
		
		if($exam_id)
		{
			$exam_id = $this->Setting->my_simple_crypt($exam_id,'d');
			
			$query=$smgt_exam->find()->where(['exam_id'=>$exam_id])->hydrate(false)->toArray();
				
			if(!empty($query))
			{
				$exam_name = $query[0]['exam_name'];
				$class_id = $query[0]['class_id'];
				$section_id = $query[0]['section_id'];
				$term_id = $query[0]['term_id'];
				$exam_date = $query[0]['exam_date'];
				$exam_end_date = $query[0]['exam_end_date'];
				
				$this->set('exam_name',$exam_name);
				$this->set('class_id',$class_id);
				$this->set('section_id',$section_id);
				$this->set('term_id',$term_id);
				$this->set('exam_date',$exam_date);
				$this->set('exam_end_date',$exam_end_date);
				$this->set('exam_id',$exam_id);
				
				$subject_query = $smgt_subject->find()->where(['class_id'=>$class_id,'section'=>$section_id])->hydrate(false)->toArray();
				if(!empty($subject_query))
					$this->set('subject_data',$subject_query);	
			}
		}
    }
	
	public function readfile($readfile = NULL)
	{
		$this->set('file',$readfile);
		
		$file = WWW_ROOT.'syllabus'.DS.$readfile;

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