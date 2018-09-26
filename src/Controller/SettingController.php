<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\View\Helper\FlashHelper;
use  Cake\Utility\Xml;

class SettingController extends AppController
{
	public function initialize()
   {
		parent::initialize();
		$this->loadComponent('Setting');
		
   }
   public function generalsetting()
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
		
		$xml = $this->Setting->get_country_list();
		
		$class=TableRegistry::get('smgt_setting');
		
		$query=$class->find();
		
		foreach ($query as $fldnm) 
		{
			$nm=$this->Setting->getfieldname($fldnm['field_name']);
			$vl[$fldnm['field_name']]=$nm;
		}
		
		$this->set('it',$vl);		
		$this->set('xml',$xml);
		
		if($this->request->is('post'))
		{
			$class=TableRegistry::get('smgt_setting');
			
			$a=$class->newEntity();
			
			$img2=$this->request->data();
			
			$xyz11=$this->Setting->getimage($img2['school_icon']);
			
			$img2['school_icon'] = $this->request->data('old_icon');
			
			if($xyz11!='')
			{
				$img2['school_icon']=$xyz11;
			}
			
			$xyz1=$this->Setting->getimage($img2['school_logo']);
			
			$old_value = $this->request->data('image2');
			$img2['school_logo']=$old_value;
			
			if($xyz1!='')
			{
				$img2['school_logo']=$xyz1;
			}
			
			$xyz2=$this->Setting->getimage($img2['school_profile']);
			
			$img2['school_profile'] = $this->request->data('image3');
			
			if($xyz2!='')
			{
				$img2['school_profile']=$xyz2;
			}
			
			$img2['lang_rtl']='no';			
			$img2['enable_sandbox']='no';
			$img2['parent_msg_stud']='no';
			$img2['stud_msg_other']='no';
			$img2['teacher_msg_all_stud']='no';	
			/* $img2['fees_alert']='no';	 */
			
			if($this->request->data['enable_sandbox'] == true)
			{
				$img2['enable_sandbox']='yes';
			}

			if($this->request->data['parent_msg_stud'] == true)
			{
				$img2['parent_msg_stud']='yes';
			}
			
			if($this->request->data['stud_msg_other'] == true)
			{
				$img2['stud_msg_other']='yes';
			}
			
			if($this->request->data['teacher_msg_all_stud'] == true)
			{
				$img2['teacher_msg_all_stud']='yes';
			}
			
			/* if($this->request->data['fees_alert'] == true)
			{
				$img2['fees_alert']='yes';
			} */
			
			if($this->request->data['lang_rtl'] == true)
			{
				$img2['lang_rtl']='yes';
			}			

			foreach ($img2 as $key=>$value) 
			{
				if($key =='save' || $key =='image2' || $key =='image3')
				{

				}
				else
				{
					$xyz=$this->Setting->setting($key,$value);
				}
				
			}
			
			return $this->redirect(['action'=>'generalsetting']);
		   $this->set('hh',$xyz);
		}
   }
}

?>