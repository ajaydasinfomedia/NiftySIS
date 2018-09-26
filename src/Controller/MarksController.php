<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;

class MarksController extends AppController
{
	public function initialize()
   {
       parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Flash');
   }
	
	public function addmarks()
	{
		$class1=TableRegistry::get('smgt_exam');	
		$query1=$class1->find("list",["keyField"=>"exam_id","valueField"=>"exam_name"]);
		$this->set('exam_id',$query1);
		
		$class2 = TableRegistry::get('Classmgt');			
		$query2 = $class2->find("list",["keyField"=>"class_id","valueField"=>"class_name"]);			
		$this->set('class_id',$query2);
		
		$section = TableRegistry::get('class_section');			
		$section_id = $section->find();			
		$this->set('section_id',$section_id);
		
		$class3=TableRegistry::get('smgt_subject');
		$query3=$class3->find();
		
		
		if($this->request->is('post'))
		{
			$class_mark = TableRegistry::get('smgt_marks'); 
			
			
			$data=$this->request->data();
			
			$exam_id=$data['exam_id'];
			$class_id=$data['class_id'];
			$subject_id=$data['sub_id'];
			
			$section_id=$data['section'];
			$this->set('sec_id',$section_id);
			
			
			$class4=TableRegistry::get('smgt_users');
			$query4=$class4->find()->where(['classname'=>$class_id,'role'=>'student']);
			
			$this->set('user',$query4);
			$this->set('e_id',$exam_id);
			$this->set('c_id',$class_id);
			
			$sub_set_name=$class3->find()->where(['subid'=>$subject_id]);
			
			
			foreach($sub_set_name as $name)
			{
				$sub_nm=$name['sub_name'];
			}
			$this->set('s_id',$subject_id);
			$this->set('sub_nm',$sub_nm);
			
			$user_id=$this->request->session()->read('user_id');
			
			$tbl_mark_value=$class_mark->find();
					
			$this->set('marktabel',$tbl_mark_value);
			
			if(isset($_POST['add_mark']))
			{
				
				$value=$_POST['add_mark'];
				
				$mark_data=$this->Setting->check_mark_id($exam_id,$class_id,$subject_id,$value);
				
				$this->set('mark_data',$mark_data);
				
				$mark=$data['marks_'.$value];
				$comment=$data['marks_comment_'.$value];				
				
				
				if(!$mark_data)
				{
					
					$a=$class_mark->newEntity();
					
					$data['exam_id']=$exam_id;
					$data['class_id']=$class_id;
					$data['subject_id']=$subject_id;
					$data['marks']=$mark;
					$data['attendance']=0;
					$data['grade_id']=$this->Setting->grade_mark($mark);
					$data['student_id']=$value;
					$data['marks_comment']=$comment;
					$data['created_date']=Time::now();
					$data['modified_date']=Time::now();
					$data['created_by']=$user_id;
					
					
					$a=$class_mark->patchEntity($a,$data);
						
					if($class_mark->save($a))
					{
						$this->Flash->success(__('Mark added Successfully', null), 
								   'default', 
									array('class' => 'success'));
									
					}
		
				}
				else{
					
					
					$id = $class_mark->get($mark_data);
					
					$user_id=$this->request->session()->read('user_id');
					
					
					$data['exam_id']=$exam_id;
					$data['class_id']=$class_id;
					$data['subject_id']=$subject_id;
					$data['marks']=$mark;
					$data['attendance']=0;
					$data['grade_id']=$this->Setting->grade_mark($mark);
					$data['student_id']=$value;
					$data['marks_comment']=$comment;
					$data['modified_date']=Time::now();
					$data['created_by']=$user_id;
					
					
					$a=$class_mark->patchEntity($id,$data);
						
					if($class_mark->save($a))
					{
						$this->Flash->success(__('Mark Updated Successfully', null), 
								   'default', 
									array('class' => 'success'));
					}
				
				}
			}
			
			if(isset($_POST['save_all_marks']))
			{
				$t=0;
				$temp=0;
				
				foreach($query4	as $class_data)
				{
					$u_id=$class_data['user_id'];
					
					$mark_data=$this->Setting->check_mark_id($exam_id,$class_id,$subject_id,$u_id);
				
					$this->set('mark_data',$mark_data);
				
					$mark=$data['marks_'.$u_id];
					$comment=$data['marks_comment_'.$u_id];
					
					if(!$mark_data)
					{
						
						$a=$class_mark->newEntity();
						
						$data['exam_id']=$exam_id;
						$data['class_id']=$class_id;
						$data['subject_id']=$subject_id;
						$data['marks']=$mark;
						$data['attendance']=0;
						$data['grade_id']=$this->Setting->grade_mark($mark);
						$data['student_id']=$u_id;
						$data['marks_comment']=$comment;
						$data['created_date']=Time::now();
						$data['modified_date']=Time::now();
						$data['created_by']=$user_id;
						
						
						$a=$class_mark->patchEntity($a,$data);
						
						if($class_mark->save($a))
						{
							$t=1;
						}
			
					}
					else{
						
						
						$id = $class_mark->get($mark_data);
						
						$user_id=$this->request->session()->read('user_id');
						
						
						$data['exam_id']=$exam_id;
						$data['class_id']=$class_id;
						$data['subject_id']=$subject_id;
						$data['marks']=$mark;
						$data['attendance']=0;
						$data['grade_id']=$this->Setting->grade_mark($mark);
						$data['student_id']=$u_id;
						$data['marks_comment']=$comment;
						$data['modified_date']=Time::now();
						$data['created_by']=$user_id;
						
						
						$a=$class_mark->patchEntity($id,$data);
							
						if($class_mark->save($a))
						{
							$temp=1;
						}
					
					}
				}
				if($t == 1)
				{
					$this->Flash->success(__('Mark added Successfully', null), 
									   'default', 
										array('class' => 'success'));
				}
				if($temp == 1)
				{
					$this->Flash->success(__('Mark Updated Successfully', null), 
									   'default', 
										array('class' => 'success'));
				}
			}
			if(isset($_POST['upload_csv_file']))
			{ 
		
				if(isset($_FILES['csv_file']))
				{
					$errors= array();
					$file_name = $_FILES['csv_file']['name'];
					$file_size =$_FILES['csv_file']['size'];
					$file_tmp =$_FILES['csv_file']['tmp_name'];
					$file_type=$_FILES['csv_file']['type'];   
					
					$value = explode(".", $_FILES['csv_file']['name']);
					$file_ext = strtolower(array_pop($value));
					$extensions = array("csv"); 
		
					if(in_array($file_ext,$extensions )=== false)
					{		
						$errors[]="this file not allowed, please choose a CSV file.";
					}
					if($file_size > 2097152)
					{
						$errors[]='File size limit 2 MB';
					}
					if(empty($errors)==true){

						$rows = array_map('str_getcsv', file($file_tmp));
						
						
						$header = array_map('strtolower',array_shift($rows));
						$csv = array();
						foreach ($rows as $row) {
							$csv[] = array_combine($header, $row);
						}
						foreach($csv as $csvdata)
						{
							$cdata[]=array('roll'=>$csvdata['roll_no'],
							'marks'=>$csvdata['marks'],
							'comment'=>$csvdata['comment']
							);
						}
						$this->set('csvdata',$cdata);
					
					}else{
						foreach($errors as &$error) echo $error;
					}
				}
			}
			
			
			
			function smgt_arraymap($element)
			{
				return $element['roll_no'];
			}
			
			if(!function_exists("array_column"))
			{
				function array_column($array,$column_name)
				{
					return array_map('smgt_arraymap', $array);
				}
			}

			$i=0;
						
			foreach ( $query4 as $user )
			{
				$mark_detail=$this->Setting->check_mark_id($exam_id,$class_id,$subject_id,$user['user_id']);
				$button_text = __('Add Mark');		
			
				if(isset($csv))
				{
					foreach(array_column($csv, 'roll_no') as $data)
					{
						if($user['roll_no']== $data)
						{						
							$key[$user->user_id] = array_search($user->roll_no, array_column($csv, 'roll_no'));
						}
				
					}
				
				}
				if($mark_detail)
				{
					
					$id = $class_mark->get($mark_detail);
					
					$mark_id=$id->mark_id;
					
					$marks=$id->marks;
					
					$marks_comment=$id->marks_comment;
					
					$button_text = __('Update','school-mgt');
					$action = "edit";					
					
				}
				else
				{
					$marks=0;
					$attendance=0;
					$marks_comment="";
					$action = "save";
					$mark_id="0";
					
				}	
			
				if(isset($key))
				$this->set('key',$key);
				if(isset($mark_id))
				$this->set('mark_id',$mark_id);
				if(isset($marks))
				$this->set('marks',$marks);
				if(isset($marks_comment))
				$this->set('marks_comment',$marks_comment);
				if(isset($button_text))
				$this->set('button_text',$button_text);
				if(isset($action))
				$this->set('action',$action);
			}
		} 
	}
	
	public function marksection() {
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
	
	public function exploremark()
    {
		$class1=TableRegistry::get('smgt_exam');
		
		$query1=$class1->find();
		$this->set('exam_id',$query1);
		
		$class2=TableRegistry::get('classmgt');
		
		$query2=$class2->find();
		$this->set('class_id',$query2);
		
		$class3=TableRegistry::get('smgt_subject');
		$query3=$class3->find();
						
		if(isset($_POST['export_marks']))
		{

			$data=$this->request->data();
			
			$exam_id=$data['exam_id'];
			$class_id=$data['class_id'];
			$subject_list = $this->Setting->get_subject($class_id);
		
			$class4=TableRegistry::get('smgt_users');
			$student=$class4->find()->where(['classname'=>$class_id,'role'=>'student']);
			
			$this->set('user',$student);
			$this->set('e_id',$exam_id);
			$this->set('c_id',$class_id);
			
			$header = array();
			$marks = array();
			$header[] = 'Roll No';
			$header[] = 'Student Name';
			
			$subject_array = array();
			if(!empty($subject_list))
			{
				foreach($subject_list as $result)
				{
					$header[]=$result->sub_name;
					$subject_array[] = $result->subid;
				}
			}
			
			$header[]= 'Total';

			$filename = WWW_ROOT.'Reports/export_marks.csv';
			$file_chk = file_exists ( $filename );

			if($file_chk)
			{
				$file_path = $filename;
				$fh =fopen($file_path, 'w');
				
				$class_header[] = 'Class';
				$class_header[] = $this->Setting->get_class_id($class_id);
				
				fputcsv($fh, $class_header);
				fputcsv($fh, $header);
				
				foreach($student as $user)
				{
					$row = array();
					$row[] =  $this->Setting->get_roll_no($user['user_id']);
					$row[] = $this->Setting->get_user_id($user['user_id']);
					
					$total = 0;
					if(!empty($subject_array))
					{
						$total = 0;
						foreach($subject_array as $sub_id)
						{	
							$marks = $this->Setting->get_mark($exam_id,$class_id,$sub_id,$user['user_id']);

							if($marks)
							{
								$row[] =  $marks;
								$total += $marks;
							}
							else	
								$row[] = 0;
						}
						$row[] = $total ;
					}
					fputcsv($fh, $row);
				}
				fclose($fh);
				
				$filename = WWW_ROOT.'Reports/export_marks.csv';
				$file_path=$filename;
				
				$mime = 'text/plain';
				header('Content-Type:application/force-download');
				header('Pragma: public');       // required
				header('Expires: 0');           // no cache
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file_path)).' GMT');
				header('Cache-Control: private',false);
				header('Content-Type: '.$mime);
				header('Content-Disposition: attachment; filename="'.basename($file_path).'"');
				header('Content-Transfer-Encoding: binary');
				//header('Content-Length: '.filesize($file_name));      // provide file size
				header('Connection: close');
				readfile($file_path);		
				exit;	
			}
		}	
			
    }
	
	public function showdata($id = null)
	{
			$class2=TableRegistry::get('class_section');
		
			$query2=$class2->find();
			
			$class3=TableRegistry::get('smgt_subject');
		
			$this->autoRender=false;
			
			if($this->request->is('ajax'))
			{
				$get_id = $_POST['id'];
				
				$get_cls = $class2->get($get_id);
				
				$get_class = $get_cls['class_section_id'];
				
				$get_data = $class3->find()->where(['section'=>$get_class])->hydrate(false)->toArray();
				$this->set('get_data',$get_data);
				if(!empty($get_data))
				{
				?>				
				<select class="form-control validate[required]" name="sub_id">
					<option value=""><?php echo __('Select Subject');?></option>
					<?php
					foreach ($get_data as $d) 
					{
					?>					
						<option value="<?php echo $d['subid']; ?>"><?php echo $d['sub_name']; ?></option>
					<?php
						$id=$d['subid'];
						$name=$d['sub_name'];
					}
					?>
				</select>					
			<?php
				}
				else{
					?>
					<select class="form-control validate[required]" name="sub_id">
						<option value=""><?php echo __('Select Subject');?></option>
					</select>
				<?php
				}
			}
	}
	
	public function addmultiplemark()
	{
		$class1=TableRegistry::get('smgt_exam');	
		$query1=$class1->find("list",["keyField"=>"exam_id","valueField"=>"exam_name"]);
		$this->set('exam_id',$query1);
		
		$class2 = TableRegistry::get('Classmgt');			
		$query2 = $class2->find("list",["keyField"=>"class_id","valueField"=>"class_name"]);			
		$this->set('class_id',$query2);
		
		$section = TableRegistry::get('class_section');			
		$section_id = $section->find();			
		$this->set('section_id',$section_id);
		
		if($this->request->is('post'))
		{
			$class_mark = TableRegistry::get('smgt_marks'); 
			
			$data=$this->request->data();
			
			$exam_id=$data['exam_id'];
			$class_id=$data['class_id'];
			
			$section_id=$data['section'];
			$this->set('sec_id',$section_id);
			
			$class4=TableRegistry::get('smgt_users');
			$query4=$class4->find()->where(['classname'=>$class_id,'role'=>'student']);
			
			$this->set('user',$query4);
			$this->set('e_id',$exam_id);
			$this->set('c_id',$class_id);
			
			$class3=TableRegistry::get('smgt_subject');
			$query3=$class3->find()->where(['class_id'=>$class_id]);
			
			$this->set('sub_m_data',$query3);
			
			$user_id=$this->request->session()->read('user_id');
			
			$tbl_mark_value=$class_mark->find();
					
			$this->set('marktabel',$tbl_mark_value);
			
			if(isset($_POST['add_single_student_mark']))
			{
				$value=$_POST['add_single_student_mark'];
				$t=0;
				$temp=0;
				foreach($query3 as $data_subject)
				{
					
					$mark = $_REQUEST['marks_'.$value.'_'.$data_subject['subid'].'_mark'];
					$comment = $_REQUEST['marks_'.$value.'_'.$data_subject['subid'].'_comment'];
					
					$mark_data=$this->Setting->check_mark_id($exam_id,$class_id,$data_subject['subid'],$value);
					// var_dump($mark_data);die;
					if(!$mark_data)
					{
						$a=$class_mark->newEntity();
						
						$data['exam_id']=$exam_id;
						$data['class_id']=$class_id;
						$data['subject_id']=$data_subject['subid'];
						$data['marks']=$mark;
						$data['attendance']=0;
						$data['grade_id']=$this->Setting->grade_mark($mark);
						$data['student_id']=$value;
						$data['marks_comment']=$comment;
						$data['created_date']=Time::now();
						$data['modified_date']=Time::now();
						$data['created_by']=$user_id;
						
						
						$a=$class_mark->patchEntity($a,$data);
							
						if($class_mark->save($a))
						{
							$temp=1;
						}
						
					}
					else
					{
						$id = $class_mark->get($mark_data);
						
						$user_id=$this->request->session()->read('user_id');
						
						$data['exam_id']=$exam_id;
						$data['class_id']=$class_id;
						$data['subject_id']=$data_subject['subid'];
						$data['marks']=$mark;
						$data['attendance']=0;
						$data['grade_id']=$this->Setting->grade_mark($mark);
						$data['student_id']=$value;
						$data['marks_comment']=$comment;
						$data['modified_date']=Time::now();
						$data['created_by']=$user_id;
						
						
						$a=$class_mark->patchEntity($id,$data);
							
						if($class_mark->save($a))
						{
							$t=2;
						}
				
					}
				
				}
				if($temp == 1)
				{
					$this->Flash->success(__('Subject Mark added Successfully', null), 
									   'default', 
										array('class' => 'success'));
				}
				if($t == 2)
				{
					$this->Flash->success(__('Subject Mark Updated Successfully', null), 
									   'default', 
										array('class' => 'success'));
				}
			}
			if(isset($_POST['save_all_multiple_subject_marks']))
			{
				$t=0;
				$temp=0;
				
				foreach($query4 as $user_data)
				{
					foreach($query3 as $sub_data)
					{
						$mark = $_REQUEST['marks_'.$user_data['user_id'].'_'.$sub_data['subid'].'_mark'];
						$comment = $_REQUEST['marks_'.$user_data['user_id'].'_'.$sub_data['subid'].'_comment'];
					
						$mark_data=$this->Setting->check_mark_id($exam_id,$class_id,$sub_data['subid'],$user_data['user_id']);
							
						if(!$mark_data)
						{
						
							$a=$class_mark->newEntity();
							
							$data['exam_id']=$exam_id;
							$data['class_id']=$class_id;
							$data['subject_id']=$sub_data['subid'];
							$data['marks']=$mark;
							$data['attendance']=0;
							$data['grade_id']=$this->Setting->grade_mark($mark);
							$data['student_id']=$user_data['user_id'];
							$data['marks_comment']=$comment;
							$data['created_date']=Time::now();
							$data['modified_date']=Time::now();
							$data['created_by']=$user_id;
							
							
							$a=$class_mark->patchEntity($a,$data);
							
							if($class_mark->save($a))
							{
								$t=1;
							}
				
						}
						else
						{
												
							$id = $class_mark->get($mark_data);
							
							$user_id=$this->request->session()->read('user_id');
							
							
							$data['exam_id']=$exam_id;
							$data['class_id']=$class_id;
							$data['subject_id']=$sub_data['subid'];
							$data['marks']=$mark;
							$data['attendance']=0;
							$data['grade_id']=$this->Setting->grade_mark($mark);
							$data['student_id']=$user_data['user_id'];
							$data['marks_comment']=$comment;
							$data['modified_date']=Time::now();
							$data['created_by']=$user_id;
							
							
							$a=$class_mark->patchEntity($id,$data);
								
							if($class_mark->save($a))
							{
								$temp=1;
							}
						}
					}
				}
				if($t == 1)
				{
					$this->Flash->success(__('All Subject Mark added Successfully', null), 
									   'default', 
										array('class' => 'success'));
				}
				if($temp == 1)
				{
					$this->Flash->success(__('All Subject Marks updated Successfully', null), 
									   'default', 
										array('class' => 'success'));
				}
			}
		}
	}
}

?>
