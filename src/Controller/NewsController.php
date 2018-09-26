<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry; 
use Cake\View\Helper\FlashHelper;
use Cake\I18n\Time;

class NewsController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Setting');
		$this->loadComponent('Flash');
	}
	public function addnews($id=0)
	{
		$this->set('News','News');
		
		$get_current_user_id=$this->request->session()->read('user_id');
		
		if($id)
		{
			$this->set('edit',true);
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$class = TableRegistry::get('smgt_news');
			$exists = $class->exists(['news_id' => $id]);
			
			if($exists)
			{
				$item = $class->get($id);
				$this->set('row',$item);
			}
			else
				return $this->redirect(['action'=>'newslist']);	
		}
		
		if($this->request->is('post'))
		{
			// var_dump($_FILES);
			// var_dump(date("m-d-Y H:i:s"));die;
			if($id)
			{
				$class = TableRegistry::get('smgt_news');
				
				$img=$_FILES['news_document']['name'];
				$img2=$this->request->data('file2');
			
				$c1=$this->request->data;
				
				$db_cl = array();
				
				if(!$img)
				{					
					$_FILES['news_document']['name']=$img2;
					unset($this->request->data['file2']);
					unset($_FILES['news_document']);
					$db_cl['news_document']=$img2;								
				}
				else
				{
					$news_document_img = $_FILES['news_document'];
					$u="document";
					$fp=WWW_ROOT.$u;	

					$imgname=$news_document_img['name'];

					$fpp=$fp.'/'.$imgname;
					
					if(move_uploaded_file($news_document_img['tmp_name'],$fpp))
					{
						echo "success";
					}
					
					unset($this->request->data['file2']);
					unset($_FILES['news_document']);
					$db_cl['news_document']=$img;		
				}			
				
				$db_cl['news_title']=$c1['news_title'];
				$db_cl['news_desc']=$c1['news_desc'];
				$db_cl['news_start_date'] = date("Y-m-d", strtotime($c1['news_start_date']));;
				$db_cl['news_end_date'] = date("Y-m-d", strtotime($c1['news_end_date']));;
				$db_cl['created_date']=date("Y-m-d");
				$db_cl['created_by']=$get_current_user_id;
				
				$item = $class->patchEntity($item,$db_cl);
			
				if($class->save($item))
				{
					$this->Flash->success(__('News Updated Successfully', null), 
						'default', 
						 array('class' => 'success'));
					
				}
				return $this->redirect(['action'=>'newslist']);		
			}
			else
			{
				if($_FILES['news_document'])
				{
					$img=$_FILES['news_document'];
					$u="document";
					$fp=WWW_ROOT.$u;	

					$imgname=$img['name'];

					$fpp=$fp.'/'.$imgname;

					if(move_uploaded_file($img['tmp_name'],$fpp))
					{
						echo "success";
					}
				}		
				
				$class2 = TableRegistry::get('smgt_news'); 			
				$a=$class2->newEntity();
			
				$c1=$this->request->data;
				
				$db_cl = array();
				
				$db_cl['news_title']=$c1['news_title'];
				$db_cl['news_desc']=$c1['news_desc'];
				$db_cl['news_document']=$_FILES['news_document'];
				$db_cl['news_document']=$db_cl['news_document']['name'];
				$db_cl['news_start_date'] = date("Y-m-d", strtotime($c1['news_start_date']));;
				$db_cl['news_end_date'] = date("Y-m-d", strtotime($c1['news_end_date']));;
				$db_cl['created_date']=date("Y-m-d");
				$db_cl['created_by']=$get_current_user_id;
				
				$a=$class2->patchEntity($a,$db_cl);
				
				if($class2->save($a))
				{
					$this->Flash->success(__('News added Successfully', null), 
							'default', 
							 array('class' => 'success'));
				}
				return $this->redirect(['action'=>'newslist']);
			}
		}
	}
	
	public function newsmultidelete() 
	{
		$this->autoRender = false;
		$id=json_decode($_REQUEST[n_id]);
		foreach($id as $recordid)
		{
			$class = TableRegistry::get('smgt_news');
			
			$item =$class->get($recordid);

			if($class->delete($item))
			{
				
			}	
		}
	}
	
	
	public function readfile($readfile = NULL)
	{
		$this->set('file',$readfile);
		
		$file = WWW_ROOT.'document'.DS.$readfile;

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
	
	
	public function newslist()
	{
		$this->set('News','News');
		
		$class = TableRegistry::get('smgt_news');
		$query=$class->find()->order(['news_id'=>'DESC']);;
		$this->set('it',$query);
		
	}
	public function delete($id)
	{
		$this->request->is(['post','delete']);
		$class1 = TableRegistry::get('smgt_news');
		$item = $class1->get($id);
		if($class1->delete($item))
		{
			$this->Flash->success(__('News Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));
			
		}
		return $this->redirect(['action'=>'newslist']);
	}
	
}

?>