<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;

class MigrationController extends AppController
{
	public function initialize()
	{
       parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Flash');
		
	}
	public function migration()
	{
		$class1=TableRegistry::get('smgt_exam');
		
		$query1=$class1->find();
		$this->set('exam_id',$query1);
		
		$class2=TableRegistry::get('classmgt');
		
		$query2=$class2->find();
		$this->set('class_id',$query2);
		
		if($this->request->is('post'))
		{
			$data=$this->request->data();
			
			$current_class = $data['current_class'];
			$next_class = $data['next_class'];
			$exam_id = $data['exam_id'];
			$passing_marks = $data['passing_marks'];			
			
			$class4=TableRegistry::get('smgt_users');
			$query4=$class4->find()->where(['classname'=>$current_class,'role'=>'student']);
			
			$class_mark=TableRegistry::get('smgt_marks');
			
			$student_fail = $this->Setting->fail_student_list($current_class,$exam_id,$passing_marks);
			
			$update = $this->Setting->smgt_migration($current_class,$next_class,$exam_id,$student_fail);
			
			$this->Flash->success(__('Migration Completed Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			return $this->redirect(['action'=>'migration']);
		}
	}
}

?>
