<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;

class TeacherrightsController extends AppController
{
		public function accessteacher(){
            
            $get_teacher_menu=TableRegistry::get('tblteachermenu');
            $get_menu=$get_teacher_menu->find();
            $this->set('teacher_menu',$get_menu);
            $get_id=array();
            foreach ($get_menu as $id_array) {
                $get_id[]=$id_array['menu_id'];
            }
            
            if($this->request->is('post')){
              $teachers_rig=$this->request->data('teacher_approve');
              $student_rig=$this->request->data('student_approve');
              $staff_rig=$this->request->data('staff_approve');
              $parent_rig=$this->request->data('parent_approve');
            

              $rights=array();
                for($i=0;$i<count($get_id);$i++){
                        $rights[$get_id[$i]]=array(
                                    'teacher_approve'=>$teachers_rig[$i],
                                    'student_approve'=>$student_rig[$i],
                                    'staff_approve'=>$staff_rig[$i],
                                    'parent_approve'=>$parent_rig[$i]
                            );
                }

              $flag=0;                
               foreach ($rights as $menuid => $inner_access) {
                  foreach ($inner_access as $key => $value) {
                      $query =  $get_teacher_menu->query();
                      $query->update()
                       ->set([$key => $value])
                       ->where(['menu_id' => $menuid])
                       ->execute();
                       $flag=1;
                  }
               }

               if($flag == 1){
                return $this->redirect(['action'=>'accessteacher']);
               }
              
            }
		}

    public function teacheraccessright()
    {
		$teacher_access_rights = TableRegistry::get('teacher_access_rights');
		$teacher_access = $teacher_access_rights->find()->hydrate(false)->toArray();
		$cnt_school = $teacher_access_rights->find()->count();
		
		if(!empty($teacher_access))
			$this->set('teacher_access',$teacher_access);

		$i = 0;
		if($this->request->is('post'))
		{
			$request_data = $this->request->data();
			
			$data_ar = array();
			
			$data_ar['chksub'] = $request_data['chksub'];
			$data_ar['chkstud'] = $request_data['chkstud'];
			$data_ar['chkatted'] = $request_data['chkatted'];
			$data_ar['modify_date'] = Time::now();
			$data_ar['modify_by'] = $this->request->session()->read('user_id');
			
			if($cnt_school == 0)
			{
				$i = 1;
				$teacher_access_rights_entity = $teacher_access_rights->newEntity();
				$data_add = $teacher_access_rights->patchEntity($teacher_access_rights_entity,$data_ar);
			}
			else
			{
				$teacher_access_rights_entity = $teacher_access_rights->get($teacher_access[0]['teacheraccess_id']);
				$data_add = $teacher_access_rights->patchEntity($teacher_access_rights_entity,$data_ar);
			}
			
			if($teacher_access_rights->save($data_add))
			{
				if($i == 0)
				{
					$this->Flash->success(__('Teacher Access Updated Successfully', null), 
							'default', 
								array('class' => 'success'));
				}		
				else
				{
					$this->Flash->success(__('Teacher Access added Successfully', null), 
							'default', 
								array('class' => 'success'));
				}
			}
					
		}
    }

}