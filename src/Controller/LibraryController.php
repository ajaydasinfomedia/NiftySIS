<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class LibraryController extends AppController
{


  public function addbook($id = null)
  {

    $category_table=TableRegistry::get('smgt_categories');
    $get_all_data_category=$category_table->find()->where(['category_title'=>'bctype']);
    $this->set('category_data',$get_all_data_category);

    $get_all_data_rack=$category_table->find()->where(['category_title'=>'racklocation']);
    $this->set('rack_data',$get_all_data_rack);

    $book_table_register=TableRegistry::get('smgt_library_book');


    if(isset($id))
	{
		$this->set('edit',true);
		$id = $this->Setting->my_simple_crypt($id,'d');	
		$exists = $book_table_register->exists(['id' => $id]);
		
		if($exists)
		{
			$get_record_from_libbook=$book_table_register->get($id);
			$this->set('update_book_row',$get_record_from_libbook);

			if($this->request->is('post'))
			{
				$update=$book_table_register->patchEntity($get_record_from_libbook,$this->request->data);

				if($book_table_register->save($update))
				{
					$this->Flash->success(__('Book Updated Successfully', null), 
										'default', 
										array('class' => 'alert alert-success'));

					return $this->redirect(['action'=>'booklist']);
				}else{
					echo 'Some Error in Update Data';
				}
			}
		}
		else
			return $this->redirect(['action'=>'booklist']);		
    }

	$user_session_id = $this->request->session()->read('user_id');

	$book_table_entity=$book_table_register->newEntity();
 
    if($this->request->is('post'))
	{
		$data=$this->request->data;

		$data['added_date']=date('Y-m-d');
		$data['added_by']=$user_session_id;

		$insert_data=$book_table_register->patchEntity($book_table_entity,$data);

		if($book_table_register->save($insert_data))
		{
			//return $this->redirect(['action'=>'addbook']);
			$this->Flash->success(__('Book added Successfully ', null), 
									'default', 
									 array('class' => 'success'));     
		}
		return $this->redirect(['action'=>'booklist']);
    }
  }
  public function booklist(){

     $book_table_register=TableRegistry::get('smgt_library_book');
     $category_table=TableRegistry::get('smgt_categories');
     $book_list=$book_table_register->find();
     $get_all_data_rack=$category_table->find()->where(['category_title'=>'racklocation']);
     $this->set('rack_data',$get_all_data_rack);
     $this->set('book_info',$book_list);
  }


  public function bookmultidelete() 
		{
			$this->autoRender = false;
			$id=json_decode($_REQUEST[b_id]);
			foreach($id as $recordid)
				{
						$class = TableRegistry::get('smgt_library_book');
						
						$item =$class->get($recordid);

						if($class->delete($item))
						{
							
						}
						
				}
		}
  
  
 public function deletebook($id){

        $book_table_register=TableRegistry::get('smgt_library_book');
        $this->request->is(['post','delete']);
        $item = $book_table_register->get($id);
        if($book_table_register->delete($item)){
          $this->Flash->success(__('Book Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));	
          return $this->redirect(['action'=>'booklist']);


        }


    }

	public function issuebook($id=null)
	{

		$class_table_register=TableRegistry::get('classmgt');
		$get_class=$class_table_register->find();
		$this->set('class_info',$get_class);


		$category_table=TableRegistry::get('smgt_categories');
		$get_all_data_category=$category_table->find()->where(['category_title'=>'period']);
		$this->set('category_data',$get_all_data_category);

		$get_all_data_category=$category_table->find()->where(['category_title'=>'bctype']);
		$this->set('category_data_bc',$get_all_data_category);

		$issue_table_register=TableRegistry::get('smgt_library_book_issue');

		if(isset($id))
		{
			$this->set('edit',true);
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $issue_table_register->exists(['id' => $id]);
			
			if($exists)
			{
				$get_update_book_issue=$issue_table_register->get($id);
				$this->set('update_book_issue',$get_update_book_issue);
				
				$get_pert_class=$class_table_register->get($get_update_book_issue['class_id']);
				$this->set('get_class_student',$get_pert_class);

				$get_all_student_name=TableRegistry::get('smgt_users');
				$get_name=$get_all_student_name->find()->where(['classname'=>$get_pert_class['class_id']]);
				$this->set('get_stud_name',$get_name);

				$book_table_regis=TableRegistry::get('smgt_library_book');
				$get_book_per=$book_table_regis->find()->where(['cat_id'=>$get_update_book_issue['cat_id']]);
				$this->set('get_book_per',$get_book_per);

				if($this->request->is('post'))
				{
					$data=$this->request->data;

					$h = $this->request->data('book_id');

					$ans=array();
					foreach ($h as $key) {
					$ans[]=$key;
					}

					$data['book_id']=implode(',',$ans);
					$data['issue_date']=date("Y-m-d", strtotime($this->request->data('issue_date')));
					$data['end_date']=date("Y-m-d", strtotime($this->request->data('end_date')));

					$update=$issue_table_register->patchEntity($get_update_book_issue,$data);

					if($issue_table_register->save($update))
					{
						$this->Flash->success(__('Issue Updated Successfully', null), 
										'default', 
										 array('class' => 'alert alert-success'));
						return $this->redirect(['action'=>'issuelist']);
					 }else{
						   echo 'Some Error in Update Data';
					 }
				}
			}
			else
				return $this->redirect(['action'=>'issuelist']);		
		}

		$user_session_id = $this->request->session()->read('user_id');

		if($this->request->is('post'))
		{
			$h = $this->request->data('book_id');
			$ans=array();
            foreach ($h as $key) 
			{
               $issue_table_entity=$issue_table_register->newEntity();

				$data=$this->request->data;
				$data['book_id']=$key;
				$data['issue_by']=$user_session_id;
				$data['status']='Issue';
				$data['issue_date']=date("Y-m-d", strtotime($this->request->data('issue_date')));
				$data['end_date']=date("Y-m-d", strtotime($this->request->data('end_date')));
				$insert_data=$issue_table_register->patchEntity($issue_table_entity,$data);
				$flag=0;
					
				if($issue_table_register->save($insert_data))
				{
					$flag=1;
				}
			}
			if($flag == 1)
			{					
				$this->Flash->success(__('Issue added Successfully', null), 
									'default', 
									 array('class' => 'success'));
                  
			}
			return $this->redirect(['action'=>'issuelist']);
		}
	}



  public function issuelist(){

    $lib_issue_table_register=TableRegistry::get('smgt_library_book_issue');
    $get_all_data_lib=$lib_issue_table_register->find();
    $user_table_register=TableRegistry::get('smgt_users');
    $get_all_data_user=$user_table_register->find();

      $this->set('lib_info',$get_all_data_lib);
      $this->set('user_info',$get_all_data_user);

      $lib_book_register=TableRegistry::get('smgt_library_book');

      $get_all_book=$lib_book_register->find();

      $this->set('book_info',$get_all_book);

      $period_register= TableRegistry::get('smgt_categories');
      $get_cate=$period_register->find()->where(['category_title'=>'period']);


        $this->set('fetch_category',$get_cate);

  }

  public function issuemultidelete() 
		{
			$this->autoRender = false;
			$id=json_decode($_REQUEST[i_id]);
			foreach($id as $recordid)
				{
						$class = TableRegistry::get('smgt_library_book_issue');
						
						$item =$class->get($recordid);

						if($class->delete($item))
						{
							
						}
						
				}
		}
  
  
  public function deleteissuebook($id)
    {
          $lib_issue_table_register=TableRegistry::get('smgt_library_book_issue');

          $this->request->is(['post','delete']);
          $item = $lib_issue_table_register->get($id);
          if($lib_issue_table_register->delete($item))
		  {
            $this->Flash->success(__('Issue Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));	
            return $this->redirect(['action'=>'issuelist']);


          }

    }



    	public function ShowStudent($id = null){

    		$user_table_register=TableRegistry::get('smgt_users');


    		$this->autoRender=false;
    		if($this->request->is('ajax')){

    			$get_id=$_POST['id'];

    				$Get_All_Data=$user_table_register->find()->where(['classsection'=>$get_id]);
    			?>

    			<select class="form-control validate[required]" name="student_id" id="classid">
    				<option value=""><?php echo __('---Select Student---'); ?></option>
    			<?php
    				foreach ($Get_All_Data as $d)
    				{
    					?>
    				<option value="<?php echo $d['user_id']; ?>"><?php echo $d['first_name']; ?></option>
    				<?php
    				}
    				?>
                            </select>
    			<?php

    		}

    	}

    public function deletebc($bcid = null){
       $this->autoRender = false;
                       if($this->request->is('ajax')){
                           $bctypeid=$_POST['bcid'];

                           $cat = TableRegistry::get('smgt_categories');
                           $itemsbc=$cat->get($bctypeid);
                           if($cat->delete($itemsbc))
						   {

                           }
                       }
    }


    public function deleteperiod($bcid = null){
       $this->autoRender = false;
                       if($this->request->is('ajax')){
                           $periodtypeid=$_POST['periodid'];

                           $cat = TableRegistry::get('smgt_categories');
                           $itemsperiod=$cat->get($periodtypeid);
                           if($cat->delete($itemsperiod))
						   {

                           }
                       }
    }

public function addperiod()
{
	$this->autoRender = false;
   
	if($this->request->is('ajax'))
	{
		$period = $_POST['periodtype'];
		if($period != '')
		{
			$cat = TableRegistry::get('smgt_categories');

			$a = $cat->newEntity();
			$a['category_title']='period';
			$a['category_type']=$period;
			
			if($cat->save($a))
				$i=$a['category_id'];

			echo $i;
		}
		else
			echo "false";
	}
}

public function addrack($id=null){
  $this->autoRender = false;
    if($this->request->is('ajax'))
	{
		if(!empty($_POST['racktype']))
		{
                    $rack = $_POST['racktype'];

                   $cat = TableRegistry::get('smgt_categories');

                   $a = $cat->newEntity();
                  $a['category_title']='racklocation';

                  $a['category_type']=$rack;
                       if($cat->save($a))
                           {
                           $i=$a['category_id'];

                         }
                  echo $i;
		}
		else
			echo "false";

}

}

public function adddata($id = null) {
            $this->autoRender = false;
              if($this->request->is('ajax'))
			  {
				  if(!empty($_POST['booktype']))
				  {
                              $cls = $_POST['booktype'];

                             $cat = TableRegistry::get('smgt_categories');

                             $a = $cat->newEntity();
                            $a['category_title']='bctype';

                            $a['category_type']=$cls;
    			                       if($cat->save($a))
    			                           {
                                     $i=$a['category_id'];

                                   }
                            echo $i;
				  }
				  else
					  echo "false";
            }
       }



   public function getbookname($id=null){
              if($this->request->is('ajax')){
                $cate_id=$_POST['bc_id'];
                $library_table_register=TableRegistry::get('smgt_library_book');

                $get_all_libdata=$library_table_register->find()->where(['cat_id'=>$cate_id]);

                $this->set('lib_info',$get_all_libdata);
   }
   }


     public function memberlist(){

       $register_library=TableRegistry::get('smgt_library_book_issue');
       $register_users=TableRegistry::get('smgt_users');
       $register_class=TableRegistry::get('classmgt');

       $query=$register_library->find();
       $query->select()->distinct(['student_id']);
       $this->set('query',$query);

      $get_class=$register_class->find();
      $this->set('get_class',$get_class);
      $get_student=$register_users->find()->where(['role'=>'student']);
      $this->set('get_student',$get_student);


     }



     public function studentviewbook(){

       if($this->request->is('ajax')){

         $get_student_id=$_POST['student_id'];

         $student_name_register=TableRegistry::get('smgt_users');
         $stud_name=$student_name_register->get($get_student_id);
         $this->set('name',$stud_name['first_name'].' '.$stud_name['last_name']);


          $this->set('id',$get_student_id);

          $lib_issue_student=TableRegistry::get('smgt_library_book_issue');

          $get_par_stud=$lib_issue_student->find()->where(['student_id'=>$get_student_id]);

          $this->set('get_book',$get_par_stud);

          $cat_table=TableRegistry::get('smgt_categories');

          $cat=$cat_table->find()->where(['category_title'=>'period']);

          $this->set('cat_info',$cat);

          $book_info_register=TableRegistry::get('smgt_library_book');
          $get_book_info=$book_info_register->find();
          $this->set('gb_info',$get_book_info);
       }

     }

     public function returnbook(){



       if($this->request->is('ajax')){

         $stud_id=$_POST['student_id'];
         $this->set('id',$stud_id);
          $book[]=$this->request->data(['book']);


         $user_table_register=TableRegistry::get('smgt_users');

         $get_student_name=$user_table_register->find()->where(['user_id'=>$stud_id]);

         $name='';
          foreach ($get_student_name as $value) {
            $name=$value['first_name'].' '.$value['last_name'];
          }
          $this->set('student_name',$name);

         $book_issue_register=TableRegistry::get('smgt_library_book_issue');

         $getparticular_student=$book_issue_register->find();
         $getparticular_student->select()->where(['student_id'=>$stud_id,'status'=>'Issue']);

         $this->set('get_book_wise',$getparticular_student);

         $book_register=TableRegistry::get('smgt_library_book');
         $get_all_bo=$book_register->find();
         $this->set('get_book_in',$get_all_bo);

       }else{
        if($this->request->is('post')){

              $check=$this->request->data('book_return');
              $fine=$this->request->data('fine');
              $returndate=$this->request->data('returndate');
				// var_dump($returndate);die;
              $num=array();
              foreach ($fine as $key) {
                  if($key == ''){

                  }else{
                    $num[]=$key;
                  }

              }

             $countfine=count($num);
             
             $co=0;
             $demo=array();
              foreach($check as $ch_id){
                $demo[$ch_id]=$num[$co];
                $co++;
              }

            $book_register_issue=TableRegistry::get("smgt_library_book_issue");
              foreach ($demo as $key => $value) {
                  
                          $query=$book_register_issue->query();
                             $update=$query->update()
                             ->set(['fine' => $value,'actual_return_date' => date('Y-m-d'),'status' => 'Submitted'])
                             ->where(['id'=>$key])
                             ->execute();

                             $flag=0;
                             if($query){
                                $flag=1;
                             }

              }
              if($flag == 1){
                $this->redirect(['action'=>'memberlist']);
              }
        }
       }
}
}
?>
