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

class ExportController extends AppController
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
	public function addexport($id=0)
	{
		
		$this->set('Export','Export');
		$get_current_user_id=$this->request->session()->read('user_id');
		
		$class2 = TableRegistry::get('smgt_export'); 
		
		if($id)
		{
			$this->set('edit',true);
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $class2->exists(['export_id' => $id]);
			
			if($exists)
			{
				$item = $class2->get($id);
				$this->set('row',$item);
			}
			else
				return $this->redirect(['action'=>'exportlist']);
		}
			
		if($this->request->is('post'))
		{	
						
			$users = TableRegistry::get('Smgt_users');		
			$student_data = $users->find()->where(['role'=>'student']);
			$student_data_array = $users->find()->where(['role'=>'student'])->hydrate(false)->toArray();
			$teacher_data = $users->find()->where(['role'=>'teacher']);
			$teacher_data_array = $users->find()->where(['role'=>'teacher'])->hydrate(false)->toArray();
			$parent_data = $users->find()->where(['role'=>'parent']);
			$parent_data_array = $users->find()->where(['role'=>'parent'])->hydrate(false)->toArray();
			$staff_data = $users->find()->where(['role'=>'supportstaff']);
			$staff_data_array = $users->find()->where(['role'=>'supportstaff'])->hydrate(false)->toArray();
			
			$studnet_db = array();
			$teacher_db = array();
			$parent_db = array();
			$staff_db = array();
			
			$c1=$this->request->data;
			
			ob_clean();
			ob_start();
			
			error_reporting(E_ALL);
			ini_set('display_errors', TRUE);
			ini_set('display_startup_errors', TRUE);
		
			$objPHPExcel = new PHPExcel();
			
			$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
									 ->setLastModifiedBy("Maarten Balliauw")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");
									 
			for($i=5;$i<=100;$i++)
			{
				$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
			}
			
			$wizard = new PHPExcel_Helper_HTML;
			
			if(isset($c1['export_model']))
			{
				$export_model = $c1['export_model'];
				
				$index = 0;
				$index_i = 1;
				
				foreach($export_model as $export_model_data)
				{
					if($export_model_data == 'student')
					{
						$html1 = "<strong>No.</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("A1", $wizard->toRichTextObject($html1));
						
						$html2 = "<strong>Student Name</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("B1", $wizard->toRichTextObject($html2));
						
						$html3 = "<strong>Class Name</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("C1", $wizard->toRichTextObject($html3));
						
						$html4 = "<strong>Section Name</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("D1", $wizard->toRichTextObject($html4));
						
						$html5 = "<strong>Roll No.</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("E1", $wizard->toRichTextObject($html5));
						
						$html6 = "<strong>Student Email</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("F1", $wizard->toRichTextObject($html6));
						
						$html7 = "<strong>Status</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("G1", $wizard->toRichTextObject($html7));
						
						$html8 = "<strong>Attendence</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("H1", $wizard->toRichTextObject($html8));
						
						$i = 2;
						$idx = 1;
						if(!empty($student_data))
						{
							$studnet_db = json_encode($student_data_array);
							
							foreach($student_data as $record)
							{
								$percent = 0;
								$total = $this->Setting->user_attendance_count($record->user_id);
								$present = $this->Setting->user_present_count($record->user_id);
								$absent = $this->Setting->user_absent_count($record->user_id);
								if($total == 0)
								{
									$percent = '0%';
								}
								else
								{
									$percent = $present*100/$total;
									$percent = round($percent)."%";
								}
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("A{$i}", $wizard->toRichTextObject($idx));
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("B{$i}", $wizard->toRichTextObject($this->Setting->get_user_id($record->user_id)));	
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("C{$i}", $wizard->toRichTextObject($this->Setting->get_class_id($record->classname)));
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("D{$i}", $wizard->toRichTextObject($this->Setting->get_section_name($record->classsection)));		
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("E{$i}", $wizard->toRichTextObject($record->roll_no));

								$objPHPExcel->getActiveSheet()
								->setCellValue("F{$i}", $wizard->toRichTextObject($record->email));
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("G{$i}", $wizard->toRichTextObject($record->status));
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("H{$i}", $wizard->toRichTextObject($percent));
								
								$i++;
								$idx++;
							}	
						}						
						$objPHPExcel->getActiveSheet()->setTitle('Student Record');
						$objPHPExcel->setActiveSheetIndex($index);	
						$index++;
					}
					if($export_model_data == 'teacher')
					{
						$objPHPExcel->createSheet();
						$objPHPExcel->setActiveSheetIndex($index);	
						
						$html1 = "<strong>No.</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("A1", $wizard->toRichTextObject($html1));
						
						$html2 = "<strong>Teacher Name</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("B1", $wizard->toRichTextObject($html2));
						
						$html3 = "<strong>Class Name</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("C1", $wizard->toRichTextObject($html3));
						
						$html4 = "<strong>Subject Name</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("D1", $wizard->toRichTextObject($html4));
						
						$html6 = "<strong>Teacher Email</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("E1", $wizard->toRichTextObject($html6));
						
						$html7 = "<strong>Attendence</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("F1", $wizard->toRichTextObject($html7));
						
						$i = 2;
						$idx = 1;
						if(!empty($teacher_data))
						{
							$teacher_db = json_encode($teacher_data_array);
							
							foreach($teacher_data as $record)
							{
								$percent = 0;
								$total = $this->Setting->user_attendance_count($record->user_id);
								$present = $this->Setting->user_present_count($record->user_id);
								$absent = $this->Setting->user_absent_count($record->user_id);
								if($total == 0)
								{
									$percent = '0%';
								}
								else
								{
									$percent = $present*100/$total;
									$percent = round($percent)."%";
								}
								
								$teacher_sub = array();					
								$teacher_sub[] = $this->Setting->get_teacher_subject($record->user_id);
								$subject_list = implode(',',$teacher_sub);
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("A{$i}", $wizard->toRichTextObject($idx));
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("B{$i}", $wizard->toRichTextObject($this->Setting->get_user_id($record->user_id)));	
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("C{$i}", $wizard->toRichTextObject($this->Setting->get_class_id($record->classname)));
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("D{$i}", $wizard->toRichTextObject($subject_list));		

								$objPHPExcel->getActiveSheet()
								->setCellValue("E{$i}", $wizard->toRichTextObject($record->email));
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("F{$i}", $wizard->toRichTextObject($percent));
								
								$i++;
								$idx++;
							}	
						}		
						
						$objPHPExcel->getActiveSheet()->setTitle('Teacher Record');	
						$index++;	
					}
					if($export_model_data == 'parent')
					{
						
						$objPHPExcel->createSheet();
						$objPHPExcel->setActiveSheetIndex($index);	
						
						$html1 = "<strong>No.</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("A1", $wizard->toRichTextObject($html1));
						
						$html2 = "<strong>Parent Name</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("B1", $wizard->toRichTextObject($html2));
						
						$html3 = "<strong>Child Name</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("C1", $wizard->toRichTextObject($html3));
						
						$html6 = "<strong>Teacher Email</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("D1", $wizard->toRichTextObject($html6));
						
						$i = 2;
						$idx = 1;						
						if(!empty($parent_data))
						{
							$parent_db = json_encode($parent_data_array);
							
							foreach($parent_data as $record)
							{
								$child_nm = array();					
								$childs = array();	
								
								$childs = $this->Setting->get_child_id($record->user_id);
								if(!empty($childs))
								{
									foreach($childs as $child)
									{
										$child_nm[] = $this->Setting->get_user_id($child);
									}
								}
								$child_list = implode(',',$child_nm);
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("A{$i}", $wizard->toRichTextObject($idx));
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("B{$i}", $wizard->toRichTextObject($this->Setting->get_user_id($record->user_id)));	
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("C{$i}", $wizard->toRichTextObject($child_list));

								$objPHPExcel->getActiveSheet()
								->setCellValue("D{$i}", $wizard->toRichTextObject($record->email));
								
								$i++;
								$idx++;
							}	
						}		
						
						$objPHPExcel->getActiveSheet()->setTitle('Parent Record');	
						$index++;	
					}
					if($export_model_data == 'staff')
					{
						
						$objPHPExcel->createSheet();
						$objPHPExcel->setActiveSheetIndex($index);	
						
						$html1 = "<strong>No.</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("A1", $wizard->toRichTextObject($html1));
						
						$html2 = "<strong>SupportStaff Name</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("B1", $wizard->toRichTextObject($html2));
						
						$html3 = "<strong>SupportStaff Email</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("C1", $wizard->toRichTextObject($html3));
						
						$i = 2;
						$idx = 1;
						if(!empty($staff_data))
						{
							$staff_db = json_encode($staff_data_array);
							
							foreach($staff_data as $record)
							{
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("A{$i}", $wizard->toRichTextObject($idx));
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("B{$i}", $wizard->toRichTextObject($this->Setting->get_user_id($record->user_id)));	

								$objPHPExcel->getActiveSheet()
								->setCellValue("C{$i}", $wizard->toRichTextObject($record->email));
								
								$i++;
								$idx++;
							}	
						}		
						
						$objPHPExcel->getActiveSheet()->setTitle('SupportStaff Record');	
						$index++;	
					}
				}
			}						
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="ExportData.xlsx"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
			$objWriter->save('php://output');
			
			
			if($id)
			{
				$db_cl = array();
				
				$db_cl['export_title']=$c1['export_title'];

				if(!empty($c1['export_model']))
					$db_cl['export_model']=implode(',',$c1['export_model']);						
				
				$db_cl['type']='export';
				$db_cl['modify_date']=date("Y-m-d");
				$db_cl['modify_by']=$get_current_user_id;
				
				$item = $class2->get($id);
				$update_data = $class2->patchEntity($item,$db_cl);
				
				if($class2->save($update_data))
				{
				}
			}
			else
			{
				$db_cl = array();
				
				$db_cl['export_title']=$c1['export_title'];

				if(!empty($c1['export_model']))
					$db_cl['export_model']=implode(',',$c1['export_model']);						
				
				$db_cl['student']=$studnet_db;
				$db_cl['teacher']=$teacher_db;
				$db_cl['parent']=$parent_db;
				$db_cl['staff']=$staff_db;
				$db_cl['type']='export';
				$db_cl['created_date']=date("Y-m-d");
				$db_cl['created_by']=$get_current_user_id;
				
				$a=$class2->newEntity();
				$a=$class2->patchEntity($a,$db_cl);
				
				if($class2->save($a))
				{
				}
			}

			die;						
		}
	}
	
	public function excelexport($id=0) 
	{
		$this->set('Export','Export');
		$get_current_user_id=$this->request->session()->read('user_id');			 
				
	}
	
	public function exportmultidelete() 
	{
		$this->autoRender = false;
		$id=json_decode($_REQUEST[e_id]);
		
		$i = 0;
		
		foreach($id as $recordid)
		{
			$class = TableRegistry::get('smgt_export');
			
			$item =$class->get($recordid);

			if($class->delete($item))
			{
				$i = 1;		
			}	
		}
		if($i == 1)
		{
			$this->Flash->success(__('Export Deleted Successfully', null), 
					'default', 
					 array('class' => 'success'));
		}
	}
	
	public function exportlist($id=0)
	{
		$this->set('Export','Export');
		
		$class = TableRegistry::get('smgt_export');
		$query=$class->find()->where(['type'=>'export'])->order(['export_id'=>'DESC']);
		$this->set('it',$query);
		
		if(isset($_REQUEST['name']))
		{		
			$class2 = TableRegistry::get('smgt_export');
			$item = $class2->get($id);
			$export_model = $item->export_model;
			$export_model = explode(',',$export_model);
			
			$users = TableRegistry::get('Smgt_users');		
			$student_data = json_decode($item->student);
			$teacher_data = json_decode($item->teacher);
			$parent_data = $users->find()->where(['role'=>'parent']);
			$staff_data = $users->find()->where(['role'=>'supportstaff']);
		
			$c1=$this->request->data;
			
			ob_clean();
			ob_start();
			
			error_reporting(E_ALL);
			ini_set('display_errors', TRUE);
			ini_set('display_startup_errors', TRUE);
		
			$objPHPExcel = new PHPExcel();
			
			$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
									 ->setLastModifiedBy("Maarten Balliauw")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");
									 
			for($i=5;$i<=100;$i++)
			{
				$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
			}
			
			$wizard = new PHPExcel_Helper_HTML;
			
			if($export_model)
			{

				$index = 0;
				$index_i = 1;
				
				foreach($export_model as $export_model_data)
				{
					if($export_model_data == 'student')
					{
						$html1 = "<strong>No.</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("A1", $wizard->toRichTextObject($html1));
						
						$html2 = "<strong>Student Name</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("B1", $wizard->toRichTextObject($html2));
						
						$html3 = "<strong>Class Name</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("C1", $wizard->toRichTextObject($html3));
						
						$html4 = "<strong>Section Name</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("D1", $wizard->toRichTextObject($html4));
						
						$html5 = "<strong>Roll No.</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("E1", $wizard->toRichTextObject($html5));
						
						$html6 = "<strong>Student Email</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("F1", $wizard->toRichTextObject($html6));
						
						$html7 = "<strong>Status</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("G1", $wizard->toRichTextObject($html7));
						
						$html8 = "<strong>Attendence</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("H1", $wizard->toRichTextObject($html8));
						
						$i = 2;
						$idx = 1;
						if(!empty($student_data))
						{
							foreach($student_data as $record)
							{
								$percent = 0;
								$total = $this->Setting->user_attendance_count($record->user_id);
								$present = $this->Setting->user_present_count($record->user_id);
								$absent = $this->Setting->user_absent_count($record->user_id);
								if($total == 0)
								{
									$percent = '0%';
								}
								else
								{
									$percent = $present*100/$total;
									$percent = round($percent)."%";
								}
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("A{$i}", $wizard->toRichTextObject($idx));
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("B{$i}", $wizard->toRichTextObject($this->Setting->get_user_id($record->user_id)));	
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("C{$i}", $wizard->toRichTextObject($this->Setting->get_class_id($record->classname)));
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("D{$i}", $wizard->toRichTextObject($this->Setting->get_section_name($record->classsection)));		
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("E{$i}", $wizard->toRichTextObject($record->roll_no));

								$objPHPExcel->getActiveSheet()
								->setCellValue("F{$i}", $wizard->toRichTextObject($record->email));
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("G{$i}", $wizard->toRichTextObject($record->status));
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("H{$i}", $wizard->toRichTextObject($percent));
								
								$i++;
								$idx++;
							}	
						}						
						$objPHPExcel->getActiveSheet()->setTitle('Student Record');
						$objPHPExcel->setActiveSheetIndex($index);	
						$index++;
					}
					if($export_model_data == 'teacher')
					{
						$objPHPExcel->createSheet();
						$objPHPExcel->setActiveSheetIndex($index);	
						
						$html1 = "<strong>No.</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("A1", $wizard->toRichTextObject($html1));
						
						$html2 = "<strong>Teacher Name</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("B1", $wizard->toRichTextObject($html2));
						
						$html3 = "<strong>Class Name</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("C1", $wizard->toRichTextObject($html3));
						
						$html4 = "<strong>Subject Name</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("D1", $wizard->toRichTextObject($html4));
						
						$html6 = "<strong>Teacher Email</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("E1", $wizard->toRichTextObject($html6));
						
						$html7 = "<strong>Attendence</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("F1", $wizard->toRichTextObject($html7));
						
						$i = 2;
						$idx = 1;
						if(!empty($teacher_data))
						{
							foreach($teacher_data as $record)
							{
								$percent = 0;
								$total = $this->Setting->user_attendance_count($record->user_id);
								$present = $this->Setting->user_present_count($record->user_id);
								$absent = $this->Setting->user_absent_count($record->user_id);
								if($total == 0)
								{
									$percent = '0%';
								}
								else
								{
									$percent = $present*100/$total;
									$percent = round($percent)."%";
								}
								
								$teacher_sub = array();					
								$teacher_sub[] = $this->Setting->get_teacher_subject($record->user_id);
								$subject_list = implode(',',$teacher_sub);
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("A{$i}", $wizard->toRichTextObject($idx));
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("B{$i}", $wizard->toRichTextObject($this->Setting->get_user_id($record->user_id)));	
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("C{$i}", $wizard->toRichTextObject($this->Setting->get_class_id($record->classname)));
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("D{$i}", $wizard->toRichTextObject($subject_list));		

								$objPHPExcel->getActiveSheet()
								->setCellValue("E{$i}", $wizard->toRichTextObject($record->email));
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("F{$i}", $wizard->toRichTextObject($percent));
								
								$i++;
								$idx++;
							}	
						}		
						
						$objPHPExcel->getActiveSheet()->setTitle('Teacher Record');	
						$index++;	
					}
					if($export_model_data == 'parent')
					{
						
						$objPHPExcel->createSheet();
						$objPHPExcel->setActiveSheetIndex($index);	
						
						$html1 = "<strong>No.</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("A1", $wizard->toRichTextObject($html1));
						
						$html2 = "<strong>Parent Name</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("B1", $wizard->toRichTextObject($html2));
						
						$html3 = "<strong>Child Name</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("C1", $wizard->toRichTextObject($html3));
						
						$html6 = "<strong>Teacher Email</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("D1", $wizard->toRichTextObject($html6));
						
						$i = 2;
						$idx = 1;						
						if(!empty($parent_data))
						{
							foreach($parent_data as $record)
							{
								$childs = array();	
								
								$childs = $this->Setting->get_child_id($record->user_id);
								if(!empty($childs))
								{
									foreach($childs as $child)
									{
										$child_nm[] = $this->Setting->get_user_id($child);
									}
								}
								$child_list = implode(',',$child_nm);
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("A{$i}", $wizard->toRichTextObject($idx));
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("B{$i}", $wizard->toRichTextObject($this->Setting->get_user_id($record->user_id)));	
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("C{$i}", $wizard->toRichTextObject($child_list));

								$objPHPExcel->getActiveSheet()
								->setCellValue("D{$i}", $wizard->toRichTextObject($record->email));
								
								$i++;
								$idx++;
							}	
						}		
						
						$objPHPExcel->getActiveSheet()->setTitle('Parent Record');	
						$index++;	
					}
					if($export_model_data == 'staff')
					{
						
						$objPHPExcel->createSheet();
						$objPHPExcel->setActiveSheetIndex($index);	
						
						$html1 = "<strong>No.</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("A1", $wizard->toRichTextObject($html1));
						
						$html2 = "<strong>SupportStaff Name</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("B1", $wizard->toRichTextObject($html2));
						
						$html3 = "<strong>SupportStaff Email</strong>";
						$objPHPExcel->getActiveSheet()
						->setCellValue("C1", $wizard->toRichTextObject($html3));
						
						$i = 2;
						$idx = 1;
						if(!empty($staff_data))
						{
							foreach($staff_data as $record)
							{
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("A{$i}", $wizard->toRichTextObject($idx));
								
								$objPHPExcel->getActiveSheet()
								->setCellValue("B{$i}", $wizard->toRichTextObject($this->Setting->get_user_id($record->user_id)));	

								$objPHPExcel->getActiveSheet()
								->setCellValue("C{$i}", $wizard->toRichTextObject($record->email));
								
								$i++;
								$idx++;
							}	
						}		
						
						$objPHPExcel->getActiveSheet()->setTitle('SupportStaff Record');	
						$index++;	
					}
				}
			}						
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="ExportData.xlsx"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
			$objWriter->save('php://output');
			die;
		}
	}
	public function delete($id)
	{
		$this->request->is(['post','delete']);
		$class1 = TableRegistry::get('smgt_export');
		$item = $class1->get($id);
		if($class1->delete($item))
		{
			$this->Flash->success(__('Export Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'exportlist']);
	}
	public function importDelete($id)
	{
		$this->request->is(['post','delete']);
		$class1 = TableRegistry::get('smgt_export');
		$item = $class1->get($id);
		if($class1->delete($item))
		{
			$this->Flash->success(__('Import Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'importlist']);
	}
	
	public function addimport()
    {
		$this->set('Export','Export');
		$get_current_user_id=$this->request->session()->read('user_id');
		
		$class2 = TableRegistry::get('smgt_export');
		$user_tbl = TableRegistry::get('smgt_users');
		
		$import_model = array();
		
		$student_chk = 0;
		$teacher_chk = 0;
		$parent_chk = 0;
		$staff_chk = 0;
		
		if($this->request->is('post'))
		{
			if($_FILES['student_csv_file']['name'] != '')
			{	
				$import_model[] = 'student';
				
				$errors= array();
				$file_name = $_FILES['student_csv_file']['name'];
				$file_size =$_FILES['student_csv_file']['size'];
				$file_tmp =$_FILES['student_csv_file']['tmp_name'];
				$file_type=$_FILES['student_csv_file']['type'];
				$value = explode(".", $_FILES['student_csv_file']['name']);
				$file_ext = strtolower(array_pop($value));
				$extensions = array("csv");

				if(in_array($file_ext,$extensions )=== false)
				{
					$errors[]="this file not allowed, please choose a CSV file.";
				}
				if($file_size > 2097152){
					$errors[]='File size limit 2 MB';
				}
				
				if(empty($errors)==true)
				{				
					$hasher = new DefaultPasswordHasher();

					$rows = array_map('str_getcsv', file($file_tmp));		
					
					$header = array_map('strtolower',array_shift($rows));
					
					$csv = array();
					$i=0;
					foreach ($rows as $row) 
					{
						$csv = array_combine($header, $row);
						
						$username = $csv['username'];
						$email = $csv['email'];
						$user_id = 0;
						$password = $hasher->hash($csv['password']);
						$class=1;
						
						if($password == "") // if user not exist and password is empty but the column is set, it will be generated
							$password = $username;
						
						$problematic_row = false;
						
						$user=$this->Setting->check_user($username,$email);
						
						$student_chk = 1;
						
						if(!$user)
						{
							$studentID = $this->Setting->generate_studentID();
							$student_chk = 2;
							
							$c1=array();
							
							$a=$user_tbl->newEntity();
							
							$c1['classname']=null;
							$c1['roll_no']=$csv['roll_no'];
							$c1['first_name']=$csv['first_name'];
							$c1['middle_name']=$csv['middle_name'];
							$c1['last_name']=$csv['last_name'];
							$c1['gender']=$csv['gender'];
							$c1['date_of_birth']=$csv['birth_date'];
							$c1['address']=$csv['address'];
							$c1['city']=$csv['city_name'];
							$c1['state']=$csv['state_name'];
							$c1['zip_code']=$csv['zip_code'];
							$c1['mobile_no']=$csv['mobile_number'];
							$c1['alternet_mobile_no']=$csv['alternet_mobile_number'];
							$c1['phone']=$csv['phone'];
							$c1['email']=$email;
							$c1['username']=$username;
							$c1['password']=$password;
							$c1['image']='finel-logo6.jpg';
							$c1['working_hour']=null;
							$c1['position']=null;
							$c1['submitted_document']=null;
							$c1['relation']=null;
							$c1['role']='student';
							$c1['status']='Not Approved';
							$c1['classsection']=0;														
							$c1['docume']=null;														
							$c1['classsection']=date("Y-m-d");														
							$c1['studentID'] = $studentID['studentID'];
							$c1['studentID_prefix'] = $studentID['studentID_prefix'];
							$c1['is_deactive']=0;
							$c1['exam_hall_receipt']=0;
							
							$a=$user_tbl->patchEntity($a,$c1);
								
							if($user_tbl->save($a))
							{
								$i=1;								
							}
				
						}
					}
				}				
			}
			
			if($_FILES['teacher_csv_file']['name'] != '')
			{	
				$import_model[] = 'teacher';
				
				$errors= array();
				$file_name = $_FILES['teacher_csv_file']['name'];
				$file_size =$_FILES['teacher_csv_file']['size'];
				$file_tmp =$_FILES['teacher_csv_file']['tmp_name'];
				$file_type=$_FILES['teacher_csv_file']['type'];

				$value = explode(".", $_FILES['teacher_csv_file']['name']);
		
				$file_ext = strtolower(array_pop($value));
	
				$extensions = array("csv");
		
				if(in_array($file_ext,$extensions )=== false)
				{
					$errors[]="this file not allowed, please choose a CSV file.";
				}
				if($file_size > 2097152){
					$errors[]='File size limit 2 MB';
				}
				
				if(empty($errors)==true)
				{
					
					$hasher = new DefaultPasswordHasher();

					$rows = array_map('str_getcsv', file($file_tmp));		
					
					$header = array_map('strtolower',array_shift($rows));
					
					$csv = array();
					$i=0;
					foreach ($rows as $row) 
					{
						$csv = array_combine($header, $row);
						
						$username = $csv['username'];
						$email = $csv['email'];
						$user_id = 0;
						$password = $hasher->hash($csv['password']);
						$class=1;
						
						if($password == "") // if user not exist and password is empty but the column is set, it will be generated
							$password =$username;
						
						$problematic_row = false;
						
						$user=$this->Setting->check_user($username,$email);
						
						$teacher_chk = 1;
						
						if(!$user)
						{
							$teacher_chk = 2;
							
							$c1=array();
							
							$a=$user_tbl->newEntity();
							
							$c1['classname']=null;
							$c1['roll_no']=null;
							$c1['first_name']=$csv['first_name'];
							$c1['middle_name']=$csv['middle_name'];
							$c1['last_name']=$csv['last_name'];
							$c1['gender']=$csv['gender'];
							$c1['date_of_birth']=$csv['birth_date'];
							$c1['address']=$csv['address'];
							$c1['city']=$csv['city_name'];
							$c1['state']=$csv['state_name'];
							$c1['zip_code']=$csv['zip_code'];
							$c1['mobile_no']=$csv['mobile_number'];
							$c1['alternet_mobile_no']=$csv['alternet_mobile_number'];
							$c1['phone']=$csv['phone'];
							$c1['email']=$email;
							$c1['username']=$username;
							$c1['password']=$password;
							$c1['image']='finel-logo6.jpg';
							$c1['working_hour']=null;
							$c1['position']=null;
							$c1['submitted_document']=null;
							$c1['relation']=null;
							$c1['role']='teacher';
							$c1['status']=null;
							$c1['classsection']=0;														
							
							$a=$user_tbl->patchEntity($a,$c1);
								
							if($user_tbl->save($a))
							{
								$i=1;								
							}
				
						}
					}
				}				
			}
			
			if($_FILES['parent_csv_file']['name'] != '')
			{	
				$import_model[] = 'parent';
				
				$errors= array();
				$file_name = $_FILES['parent_csv_file']['name'];
				$file_size =$_FILES['parent_csv_file']['size'];
				$file_tmp =$_FILES['parent_csv_file']['tmp_name'];
				$file_type=$_FILES['parent_csv_file']['type'];
	
				$value = explode(".", $_FILES['parent_csv_file']['name']);
			
				$file_ext = strtolower(array_pop($value));
			
				$extensions = array("csv");
			
				if(in_array($file_ext,$extensions )=== false)
				{
					$errors[]="this file not allowed, please choose a CSV file.";
				}
				if($file_size > 2097152){
					$errors[]='File size limit 2 MB';
				}
				
				if(empty($errors)==true)
				{
					
					$hasher = new DefaultPasswordHasher();
	
					$rows = array_map('str_getcsv', file($file_tmp));		
					
					$header = array_map('strtolower',array_shift($rows));
					
					$csv = array();
					$i=0;
					foreach ($rows as $row) 
					{
						$csv = array_combine($header, $row);
						
						$username = $csv['username'];
						$email = $csv['email'];
						$user_id = 0;
						$password = $hasher->hash($csv['password']);
						$class=1;
						
						if($password == "") // if user not exist and password is empty but the column is set, it will be generated
							$password =$username;
						
						$problematic_row = false;
						
						$user=$this->Setting->check_user($username,$email);
						
						$parent_chk = 1;
						
						if(!$user)
						{
							$parent_chk = 2;
							
							$c1=array();
							
							$a=$user_tbl->newEntity();
							
							$c1['classname']=null;
							$c1['roll_no']=null;
							$c1['first_name']=$csv['first_name'];
							$c1['middle_name']=$csv['middle_name'];
							$c1['last_name']=$csv['last_name'];
							$c1['gender']=$csv['gender'];
							$c1['date_of_birth']=$csv['birth_date'];
							$c1['address']=$csv['address'];
							$c1['city']=$csv['city_name'];
							$c1['state']=$csv['state_name'];
							$c1['zip_code']=$csv['zip_code'];
							$c1['mobile_no']=$csv['mobile_number'];
							$c1['alternet_mobile_no']=$csv['alternet_mobile_number'];
							$c1['phone']=$csv['phone'];
							$c1['email']=$email;
							$c1['username']=$username;
							$c1['password']=$password;
							$c1['image']='finel-logo6.jpg';
							$c1['working_hour']=null;
							$c1['position']=null;
							$c1['submitted_document']=null;
							$c1['relation']=null;
							$c1['role']='parent';
							$c1['status']=null;
							$c1['classsection']=0;														
							
							$a=$user_tbl->patchEntity($a,$c1);
								
							if($user_tbl->save($a))
							{
								$i=1;								
							}
				
						}
					}
				}				
			}
			
			if($_FILES['staff_csv_file']['name'] != '')
			{	
				$import_model[] = 'staff';
				
				$errors= array();
				$file_name = $_FILES['staff_csv_file']['name'];
				$file_size =$_FILES['staff_csv_file']['size'];
				$file_tmp =$_FILES['staff_csv_file']['tmp_name'];
				$file_type=$_FILES['staff_csv_file']['type'];

				$value = explode(".", $_FILES['staff_csv_file']['name']);

				$file_ext = strtolower(array_pop($value));
	
				$extensions = array("csv");
		
				if(in_array($file_ext,$extensions )=== false)
				{
					$errors[]="this file not allowed, please choose a CSV file.";
				}
				if($file_size > 2097152){
					$errors[]='File size limit 2 MB';
				}
				
				if(empty($errors)==true)
				{
					
					$hasher = new DefaultPasswordHasher();
	
					$rows = array_map('str_getcsv', file($file_tmp));		
					
					$header = array_map('strtolower',array_shift($rows));
					
					$csv = array();
					$i=0;
					foreach ($rows as $row) 
					{
						$csv = array_combine($header, $row);
						
						$username = $csv['username'];
						$email = $csv['email'];
						$user_id = 0;
						$password = $hasher->hash($csv['password']);
						$class=1;
						
						if($password == "") // if user not exist and password is empty but the column is set, it will be generated
							$password =$username;
						
						$problematic_row = false;
						
						$user=$this->Setting->check_user($username,$email);
						
						$staff_chk = 1;
						
						if(!$user)
						{
							$staff_chk = 2;
							
							$c1=array();
							
							$a=$user_tbl->newEntity();
							
							$c1['classname']=null;
							$c1['roll_no']=null;
							$c1['first_name']=$csv['first_name'];
							$c1['middle_name']=$csv['middle_name'];
							$c1['last_name']=$csv['last_name'];
							$c1['gender']=$csv['gender'];
							$c1['date_of_birth']=$csv['birth_date'];
							$c1['address']=$csv['address'];
							$c1['city']=$csv['city_name'];
							$c1['state']=$csv['state_name'];
							$c1['zip_code']=$csv['zip_code'];
							$c1['mobile_no']=$csv['mobile_number'];
							$c1['alternet_mobile_no']=$csv['alternet_mobile_number'];
							$c1['phone']=$csv['phone'];
							$c1['email']=$email;
							$c1['username']=$username;
							$c1['password']=$password;
							$c1['image']='finel-logo6.jpg';
							$c1['working_hour']=null;
							$c1['position']=null;
							$c1['submitted_document']=null;
							$c1['relation']=null;
							$c1['role']='supportstaff';
							$c1['status']=null;
							$c1['classsection']=0;														
							
							$a=$user_tbl->patchEntity($a,$c1);
								
							if($user_tbl->save($a))
							{
								$i=1;								
							}
				
						}
					}
				}				
			}
			
			$c1=$this->request->data;
				
			$db_cl = array();
			
			$db_cl['import_title']=$c1['import_title'];

			if(!empty($import_model))
				$db_cl['import_model']=implode(',',$import_model);						
			
			$db_cl['type']='import';
			$db_cl['created_date']=date("Y-m-d");
			$db_cl['created_by']=$get_current_user_id;
			
			$a=$class2->newEntity();
			$a=$class2->patchEntity($a,$db_cl);
			
			$sucs = 1;
			
			if($student_chk == 1 && $student_chk != 2)
			{
				$sucs = 2;
				$this->Flash->success(__('Duplicate Student Record So Not Import', null), 
                            'default', 
                             array('class' => 'success'));
			}
			
			if($teacher_chk == 1 && $teacher_chk != 2)
			{
				$sucs = 2;
				$this->Flash->success(__('Duplicate Teacher Record So Not Import', null), 
                            'default', 
                             array('class' => 'success'));
			}
			
			if($parent_chk == 1 && $parent_chk != 2)
			{
				$sucs = 2;
				$this->Flash->success(__('Duplicate Parent Record So Not Import', null), 
                            'default', 
                             array('class' => 'success'));
			}
			
			if($staff_chk == 1 && $staff_chk != 2)
			{
				$sucs = 2;
				$this->Flash->success(__('Duplicate SupportStaff Record So Not Import', null), 
                            'default', 
                             array('class' => 'success'));
			}
				
			if($class2->save($a))
			{
				if($sucs == 1)
				{
					$this->Flash->success(__('Import Successfully', null), 
								'default', 
								 array('class' => 'success'));
				}
			}
			return $this->redirect(['action'=>'importlist']);
		}
    }
	
	public function importlist()
    {
		$this->set('Export','Export');
		
		$class = TableRegistry::get('smgt_export');
		$query=$class->find()->where(['type'=>'import'])->order(['export_id'=>'DESC']);
		$this->set('it',$query);
    }
	
	public function importedit()
	{			
		$term_id = $_REQUEST['class_section_id'];

		$smgt_export = TableRegistry::get('smgt_export');	
		$retrieved_data = $smgt_export->get($term_id);	
		$this->set('model_data',$retrieved_data);	
	}
	public function updateImport()
	{
		if($this->request->is('post'))
		{
			$term_id = $_REQUEST['export_id'];
			
			$smgt_export = TableRegistry::get('smgt_export');		
			$retrieved_data = $smgt_export->get($term_id);
			
			$retrieved_data['import_title'] = $_REQUEST['import_title'];
			
			$smgt_export->save($retrieved_data);
			
			$this->Flash->success(__('Import Updated Successfully', null), 
							'default', 
							 array('class' => 'danger'));
							 
			return $this->redirect(['action'=>'importlist']);
		}
	}
}

?>