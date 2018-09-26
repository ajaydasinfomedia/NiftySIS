<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use Cake\I18n\Time;
use Gmgt_paypal_class;

class FeepaymentController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		require_once(ROOT . DS .'vendor' . DS . 'paypal' . DS . 'paypal_class.php');
		
		$this->loadComponent("Setting");
		$this->loadComponent('Et');
	}

	public function ShowStudent($id = null)
	{

		$user_table_register=TableRegistry::get('smgt_users');


		$this->autoRender=false;
		if($this->request->is('ajax')){

		  $get_id=$_POST['id'];

		  $Get_All_Data=$user_table_register->find()->where(['classsection'=>$get_id,"role"=>"student"]);
		  ?>

		  <select class="form-control validate[required]" name="student_id" id="classid">
			<option value=""><?php echo __('Select Student'); ?> </option>
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

	public function paymentview()
	{
		if($this->request->is('ajax'))
		{
			$fees_pay_id=$_POST['vpaymentid'];
			$this->set('pay_id',$fees_pay_id);

			$fees_pay_register=TableRegistry::get("smgt_fees_payment");
			$select_all=$fees_pay_register->get($fees_pay_id);
			$this->set('payment_info',$select_all);
			
			$setting=TableRegistry::get('smgt_setting');
			$query_address=$setting->find('all',['conditions'=>['field_name'=>'school_address']]);
			$query_phone=$setting->find('all',['conditions'=>['field_name' =>'office_phone_no']]);
			
			$address='';
			$phone='';
			
			foreach($query_address as $q)
			{
			$address=$q['field_value'];
			}
			foreach ($query_phone as $value) {
			$phone=$value['field_value'];
			}

			$this->set('address',$address);
			$this->set('phone',$phone);

			$get_user=TableRegistry::get('smgt_users');
			$get_user_data=$get_user->get($select_all['student_id']);
			$this->set('user_info',$get_user_data);				   
			  
			$cate_register = TableRegistry::get('smgt_categories');
			$fetch_all_cat=$cate_register->get($select_all['fees_id']);
			$this->set('cate_info',$fetch_all_cat);
			
			$history_table_register = TableRegistry::get('smgt_fee_payment_history');
			$get_all_history=$history_table_register->find()->where(['fees_pay_id'=>$select_all['fees_pay_id']])->hydrate(false)->toArray();
			if(!empty($get_all_history))
				$this->set('history',$get_all_history);
		}
	}
	
	public function feepaymentsection() {
	$this->autoRender = false;
	   if($this->request->is('ajax')){
	$cls = $_POST['fid'];

	$post = TableRegistry::get('class_section');
	$data = $post->find()->where(["class_id"=>$cls])->hydrate(false)->toArray();
	if(!empty($data))
	{
			?>
			<option value="">---Select Section---</option>
			<?php
			foreach($data as $option)
			{
				echo "<option value='{$option['class_section_id']}'>{$option['section_name']}</option>";
			}

	}
	   }
	}
 
	public function paymentSuccess()
	{
		$payment_data = $this->request->session()->read("Payment");
		$user_session_id=$this->request->session()->read('user_id');
		
		$history_table_register = TableRegistry::get('smgt_fee_payment_history');
		$fees_pay_register=TableRegistry::get("smgt_fees_payment");

		$pid = $payment_data["mp_id"];
		$row = $history_table_register->newEntity();

		$history_entity['fees_pay_id']=$pid;
		$history_entity['amount']=$payment_data['amount'];
		$history_entity['payment_method']='Paypal';
		$history_entity['paid_by_date']=date("Y-m-d");
		$history_entity['created_by']=$user_session_id;
		
		$row = $history_table_register->patchEntity($row,$history_entity);
		if($history_table_register->save($row))
		{
			$row = $fees_pay_register->get($pid);
			$row->fees_paid_amount = $row->fees_paid_amount + $payment_data['amount'];
			
			
			$status=0;

			if($row->fees_paid_amount == 0)
				$status=0;
			else if($row->fees_paid_amount > 0 && $row->fees_paid_amount > $row->fees_paid_amount && $row->fees_paid_amount != $row->total_amount)
				$status=1;
			else if($row->fees_paid_amount == $row->total_amount || $row->fees_paid_amount > $row->total_amount)
				$status=2;
			  
			$row->payment_status = $status;
			$fees_pay_register->save($row);
		}
		
		$session = $this->request->session();
		$session->delete('Payment');
		
		$this->Flash->success(__("Success! Payment Successfully Completed"));
						
			
		$item = $fees_pay_register->get($pid);
		$student_id = $item->student_id;				

		$classchild = TableRegistry::get('child_tbl');
		$parent_list=$classchild->find('all')->where(['child_id'=>$student_id])->hydrate(false)->toArray();

		foreach($parent_list as $p_data)
		{
			$parent_id = $p_data['child_parent_id'];
			$parent_name=$this->Setting->get_user_id($parent_id);
			$parent_email=$this->Setting->get_user_email_id($parent_id);

			$sys_email=$this->Setting->getfieldname('email'); 
			$school_name = $this->Setting->getfieldname('school_name');
			
			$email_id=$parent_email;
			
			if($email_id != '')
			{				
				$sys_name = $school_name;
				$sys_email = $sys_email;
				$from = "From: {$sys_name} <{$sys_email}>" . "\r\n";
				
				$epid = $this->Setting->my_simple_crypt($pid,'e');
				$this->Setting->mail_invoice_pdf($email_id,$epid);
			}
		}
		return $this->redirect(['controller'=>'Comman',"action"=>"feelist"]);
	}
 
  public function addhistory()
  {
	$this->autoRender=false;

    if($this->request->is('ajax'))
	{
		
		$user_session_id = $this->request->session()->read('user_id');
		$final_amt=0;
		
		$pid= $_POST['paymentid'];
		$pamt=$_POST['paymentamt'];
		$pby=$_POST['paymentby'];
		$netfees=$_POST['nettotal'];
		$totalamt=$_POST['totalamt'];
		
        $history_table_register = TableRegistry::get('smgt_fee_payment_history');

		$history_entity = $history_table_register->newEntity();

		$history_entity['fees_pay_id']=$pid;
		$history_entity['amount']=$pamt;
		$history_entity['payment_method']=$pby;
		$history_entity['paid_by_date']=date("Y-m-d");
		$history_entity['created_by']=$user_session_id;

		$sel=$history_table_register->find()->select(['amount'])->where(['fees_pay_id'=>$pid]);

		$a=0;
			foreach($sel as $s){
				$a=$a+$s['amount'];
		}

		$a=$a+$pamt;

		$status=0;

		if($a == 0){
			$status=0;
		}else if($a > 0 && $a > $netfees && $a != $totalamt){
			$status=1;
		}else if($a == $totalamt || $a > $totalamt){
			$status=2;
		}

		$fees_pay_register=TableRegistry::get("smgt_fees_payment");

		 $query=$fees_pay_register->query();
		 $update=$query->update()
		 ->set(['fees_paid_amount'=>$a,'payment_status'=>$status])
		 ->where(['fees_pay_id'=>$pid])
		 ->execute();
										 

		if($history_table_register->save($history_entity))
		{
			$this->Flash->success(__('History added Successfully', null), 
					'default', 
					 array('class' => 'success')); 
			
			$item = $fees_pay_register->get($pid);
			$student_id = $item->student_id;				

			$classchild = TableRegistry::get('child_tbl');
			$parent_list=$classchild->find('all')->where(['child_id'=>$student_id])->hydrate(false)->toArray();

			foreach($parent_list as $p_data)
			{
				$parent_id = $p_data['child_parent_id'];
				$parent_name=$this->Setting->get_user_id($parent_id);
				$parent_email=$this->Setting->get_user_email_id($parent_id);
		
				$sys_email=$this->Setting->getfieldname('email'); 
				$school_name = $this->Setting->getfieldname('school_name');

				$email_id=$parent_email;
				
				if($email_id != '')
				{				
					$sys_name = $school_name;
					$sys_email = $sys_email;
					$from = "From: {$sys_name} <{$sys_email}>" . "\r\n";
					
					$epid = $this->Setting->my_simple_crypt($pid,'e');
					$ok = $this->Setting->mail_invoice_pdf($email_id,$epid);
				}
			}
			echo "success";
		}
    }
  }

  public function feepaymentmultidelete() 
		{
			$this->autoRender = false;
			$id=json_decode($_REQUEST[f_id]);
			foreach($id as $recordid)
				{
						$class = TableRegistry::get('smgt_fees');
						
						$item =$class->get($recordid);

						if($class->delete($item))
						{
							
						}
						
				}
		}
  
  
  
  public function delete($id = null){
     $this->autoRender = false;
                     if($this->request->is('ajax')){
                         $typeid=$_POST['feetypeid'];
                         $cat = TableRegistry::get('smgt_categories');
                         $items=$cat->get($typeid);
                         if($cat->delete($items))
						 {
							$this->Flash->success(__('Category Deleted Successfully', null), 
								'default', 
									array('class' => 'success'));	
                         }
                     }
  }


	public function adddata($id = null) 
	{
		$this->autoRender = false;
        if($this->request->is('ajax'))
		{
			if(!empty($_POST['feetype']))
			{
				$cls = $_POST['feetype'];
				
				$cat = TableRegistry::get('smgt_categories');
				$a = $cat->newEntity();
				
				$a['category_title']='feetype';
				$a['category_type']=$cls;
				
				if($cat->save($a))
				{
					$i=$a['category_id'];
				}
				echo $i;
			}
			else
				echo "false";
            die();
       }
   }


	public function addfeetype($id=null)
	{

		$category_table=TableRegistry::get('smgt_categories');
		$get_all_data=$category_table->find()->where(['category_title'=>'feetype']);
		$this->set('category_data',$get_all_data);

		$class_table=TableRegistry::get('classmgt');
		$get_class=$class_table->find();
		$this->set('std_class',$get_class);


		$feestable_register=TableRegistry::get('smgt_fees');
		$createEntity=$feestable_register->newEntity();

		if(isset($id))
		{
			$this->set('edit',true);
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $feestable_register->exists(['fees_id' => $id]);
			
			if($exists)
			{
				$get_rec=$feestable_register->get($id);
				$this->set('get_rec',$get_rec);

				$get_class_rec=$class_table->find()->where(['class_id'=>$get_rec['class_id']]);
				$get_cat_rec=$category_table->find()->where(['category_id'=>$get_rec['fees_title_id']]);

				$class_name=array();
				foreach($get_class_rec as $cl)
				{
						$class_name=$cl['class_name'];
				}
				$fees_type_name='';
				foreach($get_cat_rec as $fee)
				{
					$fees_type_name=$fee['category_type'];
				}
				$this->set('cat_type',$fees_type_name);
				$this->set('class_name',$class_name);

				if($this->request->is('post'))
				{
					$update=$feestable_register->patchEntity($get_rec,$this->request->data);

					if($feestable_register->save($update))
					{
						$this->Flash->success(__('Fee Type Updated Successfully', null), 
							'default', 
							 array('class' => 'alert alert-success'));
						return $this->redirect(['action'=>'feetypelist']);
					}
					else
					{
						echo 'Some Error in Update Data';
					}
				}
			}
			else
				return $this->redirect(['action'=>'feetypelist']);
		}
		$user_session_id = $this->request->session()->read('user_id');

		if($this->request->is('post'))
		{
			$data=$this->request->data;

			$data['created_date']=Time::now();
			$data['created_by']=$user_session_id;

			$all_data=$feestable_register->patchEntity($createEntity,$data);
			if($feestable_register->save($all_data))
			{

			  $this->Flash->success(__('Fee Type added Successfully', null), 
				'default', 
				 array('class' => 'success')); 
			}
			return $this->redirect(['action'=>'addfeetype']);
		}
    }
    public function feelist(){

         $fees_payment=TableRegistry::get('smgt_fees_payment');
         $get_all_data=$fees_payment->find();

         $this->set('fees_data',$get_all_data);
		 
		 $section_table_register=TableRegistry::get('class_section');
        $section_record=$section_table_register->find();
        $this->set('section_record',$section_record);

          $catetable_register=TableRegistry::get('smgt_categories');
          $get_all_data_cat=$catetable_register->find();

         $this->set('get_all_data_cat',$get_all_data_cat);

         $user_table_register=TableRegistry::get('smgt_users');
         $get_all_user=$user_table_register->find();

         $this->set('get_all_user',$get_all_user);

         $class_table_register=TableRegistry::get('classmgt');
         $get_all_class=$class_table_register->find();

         $this->set('get_all_class',$get_all_class);
		 
		 
    }


    public function paymentdelete($id){
         $fees_payment=TableRegistry::get('smgt_fees_payment');
            $this->request->is(['post','delete']);

            $item=$fees_payment->get($id);

            if($fees_payment->delete($item))
			{
				$this->Flash->success(__('Fee Type Deleted Successfully', null), 
								'default', 
									array('class' => 'success'));	
				
                 return $this->redirect(['controller'=>'Feepayment','action'=>'feelist']);
            }
    }

	public function feemultidelete() 
		{
			$this->autoRender = false;
			$id=json_decode($_REQUEST[f_id]);
			foreach($id as $recordid)
				{
						$class = TableRegistry::get('smgt_fees_payment');
						
						$item =$class->get($recordid);

						if($class->delete($item))
						{
							
						}
						
				}
		}
	
    public function feedelete($id){

            $feestable_register=TableRegistry::get('smgt_fees');

            $this->request->is(['post','delete']);

            $item=$feestable_register->get($id);

            if($feestable_register->delete($item))
			{
				$this->Flash->success(__('Fee Type Deleted Successfully', null), 
								'default', 
									array('class' => 'success'));	
									
                 return $this->redirect(['controller'=>'Feepayment','action'=>'feetypelist']);
            }
    }
    public function feetypelist(){

        $feestable_register=TableRegistry::get('smgt_fees');
        $getall_record=$feestable_register->find();
        $this->set('feesrecord',$getall_record);

        $feetype_table_register=TableRegistry::get('smgt_categories');
        $class_table_register=TableRegistry::get('classmgt');

        $get_record_for_category=$feetype_table_register->find()->where(['category_title'=>'feetype']);
        $get_record_for_class=$class_table_register->find();

        $this->set('class_record',$get_record_for_class);
        $this->set('category_record',$get_record_for_category);
		
		$section_table_register=TableRegistry::get('class_section');
        $section_record=$section_table_register->find();
        $this->set('section_record',$section_record);


    }

    public function showfeetype($id = null){

                     if($this->request->is('ajax')){
                            $class_id=$_POST['id'];
                       $fees_register=TableRegistry::get('smgt_fees');
                       $cat_register=TableRegistry::get('smgt_categories');

                       $getfeestype_id=$fees_register->find()->where(['class_id'=>$class_id]);
                       $getcategory_id=$cat_register->find();

                       $this->set('getcategory_id',$getcategory_id);
                       $this->set('getfeestype_id',$getfeestype_id);

                     }
               }


    public function getamount($c_id = null,$f_id=null){

       if($this->request->is('ajax')){

           $cid=$_POST['cid'];
          $fid=$_POST['fid'];

          $fees_register=TableRegistry::get('smgt_fees');

          $getamount=$fees_register->find()->where(['class_id'=>$cid,'fees_title_id'=>$fid]);



          $a='';
          foreach($getamount as $aa){

              $a=$aa['fees_amount'];
          }

         $this->set('amt',$a);

       }


    }

    

    public function invoice($id = null)
	{
		
		$class_table_register=TableRegistry::get('classmgt');
		$get_class_record=$class_table_register->find();
		$this->set('class_array',$get_class_record);

		$user_table_register=TableRegistry::get('smgt_users');
		$fees_payment=TableRegistry::get('smgt_fees_payment');

		if(isset($id))
		{
			$this->set('edit',true);
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $fees_payment->exists(['fees_pay_id' => $id]);
			
			if($exists)
			{
				$fee_pay_id=$id;

				$get_payment_info=$fees_payment->get($id);
				$this->set('pay_info',$get_payment_info);

				$get_class_info=$class_table_register->get($get_payment_info['class_id']);

				$this->set('cla_id',$get_class_info['class_id']);
				$this->set('cla_name',$get_class_info['class_name']);


				$stud_id=$get_payment_info['student_id'];

				$getstudent=$user_table_register->get($stud_id);


				$this->set('std_info',$getstudent);

				$category_table_register=TableRegistry::get('smgt_categories');

				$get_cat=$category_table_register->get($get_payment_info['fees_id']);

				$this->set('c_info',$get_cat);

				if($this->request->is('post'))
				{			

					$data_request=$this->request->data;

					$update_record=$fees_payment->patchEntity($get_payment_info,$data_request);

					if($fees_payment->save($update_record))
					{
					  $this->Flash->success(__('Invoice Updated Successfully', null), 
								'default', 
								 array('class' => 'alert alert-success'));
								 
						return $this->redirect(['action'=>'feelist']);
					}
					else
					{
						echo 'Some Error During Update data';
					}
				}
			}
			else
				return $this->redirect(['action'=>'feelist']);
		}

		$user_session_id = $this->request->session()->read('user_id');

		if($this->request->is('post'))
		{

			$i=0;
			$class_id=$this->request->data('class_id');
			$get_all_student_idwise=$user_table_register->find()->where(['role'=>'student', 'classname'=>$class_id]);

			$store_id=array();

			foreach($get_all_student_idwise as $get_id){
			 $store_id[]=$get_id['user_id'];
			}

			foreach($store_id as $user_info){

			 $fees_entity=$fees_payment->newEntity();
			}
			
			$data=$this->request->data;
			$data['student_id']=$data["student_id"];
			$data['payment_status']=0;
			$data['created_date']=Time::now();
			$data['created_by']=$user_session_id;
					  
			$insert=$fees_payment->patchEntity($fees_entity,$data);
			if($fees_payment->save($insert))
			{
			 $fees_pay_id=$insert['fees_pay_id'];
			 $i=1;						
			}
		  
			if($i == 1)
			{
				$this->Flash->success(__('Invoice added Successfully', null), 
									'default', 
									 array('class' => 'success')); 
			}
		}
	}
		 
	public function feepaymentalert() 
	{
		$email = new Email('default');
		/* $check_alert_on = $this->Setting->getfieldname("fees_alert"); */
		$sys_email = $this->Setting->getfieldname("email");
		$sys_name = $this->Setting->getfieldname("school_name");	
		$reminder_message = $this->Setting->getfieldname("reminder_message");
		$search = ["PARENT_NAME","CHILD_NAME","FEES_TYPE","DUE_AMOUNT"];

		$fees_payment=TableRegistry::get('smgt_fees_payment');
		$student_data=$fees_payment->find()->where(["payment_status !="=>2])->hydrate(false)->toArray();

		if($check_alert_on == 'yes')
		{
			foreach($student_data as $retrive_data)
			{
				$parent_id = $this->Setting->smgt_get_student_parent_id($retrive_data['student_id']);
				$email_to = $this->Setting->get_user_email_id($parent_id);
				$parent_name = $this->Setting->get_user_id($parent_id);
				$student_name = $this->Setting->get_user_id($retrive_data['student_id']);
				$fees_type = $this->Setting->get_fees_title($retrive_data['fees_id']);
				$due_amount = $retrive_data['total_amount'] - $retrive_data['fees_paid_amount'];
				
				if($email_to != '')
				{
					$reminder_message = $this->Setting->getfieldname("reminder_message");
					$search = ["PARENT_NAME","CHILD_NAME","FEES_TYPE","DUE_AMOUNT"];
					$replace = [$parent_name,$student_name,$fees_type,$due_amount];
					$reminder_message = str_replace($search,$replace,$reminder_message);
					
					$to = $email_to;
					$headers = "From: {$sys_name} <{$sys_email}>" . "\r\n";
			
					mail($to,_("Fees Payment Alert!"),$reminder_message,$headers);
				}
			}
		}
		$this->Flash->success(__('Alert mail sent successfully', null), 
					'default', 
					 array('class' => 'alert alert-success'));
					 
		return $this->redirect(['action'=>'feelist']);
	}
	
	public function paymentviewpdf($fees_pay_id = 0)
	{
		$fees_pay_register=TableRegistry::get("smgt_fees_payment");
		
		$fees_pay_id = $this->Setting->my_simple_crypt($fees_pay_id,'d');
		
		$exists = $fees_pay_register->exists(['fees_pay_id' => $fees_pay_id]);
		
		if($exists)
		{
			$select_all=$fees_pay_register->get($fees_pay_id);
			$this->set('payment_info',$select_all);
			
			$setting=TableRegistry::get('smgt_setting');
			$query_address=$setting->find('all',['conditions'=>['field_name'=>'school_address']]);
			$query_phone=$setting->find('all',['conditions'=>['field_name' =>'office_phone_no']]);
	
			$address='';
			$phone='';
	  
			foreach($query_address as $q)
				$address=$q['field_value'];

			foreach ($query_phone as $value)
				$phone=$value['field_value'];
			
			$this->set('address',$address);
			$this->set('phone',$phone);

			$get_user=TableRegistry::get('smgt_users');
			$get_user_data=$get_user->get($select_all['student_id']);
			$this->set('user_info',$get_user_data);                      
			  
			$cate_register = TableRegistry::get('smgt_categories');
			$fetch_all_cat=$cate_register->get($select_all['fees_id']);
			$this->set('cate_info',$fetch_all_cat);

			$history_table_register = TableRegistry::get('smgt_fee_payment_history');
			$get_all_history=$history_table_register->find()->where(['fees_pay_id'=>$select_all['fees_pay_id']])->hydrate(false)->toArray();
			if(!empty($get_all_history))
				$this->set('history',$get_all_history);
		}
		else
			return $this->redirect(['action'=>'feelist']);
	}
	
	public function paymentviewprint($fees_pay_id = 0)
	{
		?>
		<style>
		html,
		body{
			font-family: "Open Sans",sans-serif;
			float: left;
			width: 100%;
			margin-top: 0px;
			padding-top: 0px;
			color: #4E5E6A;
			font-size: 13px;
		}
		hr{
			border-color: #97C4E7;
		}
		.movetop{
			float: left;
			width: 100%;
			margin-top: -90px;
			margin-left: 0px;
			position: absolute;
			min-height: 90px;
		}
		.mainlogo,
		.schoolname
		{
			float: left;
			width: 130px;
			text-align: left;
			padding-bottom: 10px;			
		}
		.schoolname{
			width: 50%;
			padding-left: 20px;
		}
		.schoolname h4{margin-top: 0px;}
		.mainlogo img
		{
			float: none;
			margin: 0px auto;
			max-width: 100%;
		}
		.schoolname span{
			font-size: 22px;
		}
		.leftdiv{
			float: left;
			width: 280px;
			margin-top: 25px;
		}
		.rightdiv{
			float: left;
			width: 70%;
			margin-top: 10px;
		}
		.lastdiv{
			float: right;
			width: 150px;
			margin-top: 25px;
		}
		.leftdiv h4,
		.rightdiv h4,
		.lastdiv h4
		{
			float: left;
			width: 100%;
			font-size:16px;
			margin-bottom: 8px;
			margin-top: 0px;
		}
		.leftdiv b,
		.rightdiv b,
		.lastdiv b
		{
			color: #4E5E6A;
		}
		.leftdiv .textcr,
		.rightdiv .textcr,
		.lastdiv .textcr
		{
			float: left;
			width: 100%;
			color: #65696d;
			text-align: left;
		}
		.rightdiv .textcr{
			width: 100%;
		}
		.lastdiv .textcr b
		{
			text-align: right;
			font-size: 11px;
		}
		.lastdiv .textcr.first,
		.lastdiv .textcr.first b
		{
			text-align: center;
			background-color: #03A9F4;
			color: #4E5E6A;
			text-transform: uppercase;
			font-size: 15px;
			padding: 0px;
			text-align: center;
		}
		.lastdiv .textcr
		{
			font-size: 12px;
			padding: 5px 0px 0px;
			text-align: center;
		}
		.billtoimg{
			width: 130px;
			float: left;
			text-align: center;
		}
		.billtocontent{
			float: left;
			width: 70%;
			padding-left: 20px;
		}
		.markstable{
			float: left;
			width: 100%;		
		}
		.markstable table tr:nth-child(even) {
			background: #d1e9ff;
		}
		.markstable table tr:nth-child(odd) {
			background: #a7d1f7;
		}
		.markstable table{
			border-collapse:collapse;
		}
		.markstable th
		{
			padding: 6px 14px 6px 14px;
			font-size:14px;
			border-top: 1px solid #97C4E7;
			border-bottom: 1px solid #97C4E7;
			background-color: #337ab7;
			color: #000000;	
		}
		.markstable td:first-child,
		.markstable th:first-child
		{
			border-left: 1px solid #97C4E7;	
		}
		.markstable td:last-child,
		.markstable th:last-child
		{
			border-right: 1px solid #97C4E7;
		}
		.markstable td{
			padding: 6px 14px 6px 14px;
			font-size:14px;
			border-bottom: 1px solid #97C4E7;				
		}
		.markstable td.space{
			border: medium none;
			border-left: medium none;
			border-right: medium none;
			background-color: #FFFFFF;
		}
		.resultdate{
			float: left;
			width: 100%;
			padding-top: 100px;
			text-align: left;
			padding-bottom:20px;
		}
		.totalmarks{
			border-top: 1px solid #97C4E7;
		}
		.signature{
			float: left;
			width: 200px;
			padding-top: 50px;
			text-align: center;
			margin-right: 60px;
			clear: both;
		}
		.signature span,
		.resultdate span
		{
			font-size: 16px;
			color: #4E5E6A;
			font-style: italic;
			padding-bottom:20px;
		}
		@media print {
		  body, page[size="A4"] {
			font-family: 'Open Sans',sans-serif;
		  }
		}
		</style>
		<script>window.onload = function(){ 				
			window.print(); 		
		};</script>
		<?php
		
		$this->autoRender = false;
		
		$fees_pay_register=TableRegistry::get("smgt_fees_payment");
		
		$fees_pay_id = $this->Setting->my_simple_crypt($fees_pay_id,'d');
		
		$exists = $fees_pay_register->exists(['fees_pay_id' => $fees_pay_id]);
		
		if($exists)
		{
			$select_all=$fees_pay_register->get($fees_pay_id);
			$this->set('payment_info',$select_all);
			
			$setting=TableRegistry::get('smgt_setting');
			$query_address=$setting->find('all',['conditions'=>['field_name'=>'school_address']]);
			$query_phone=$setting->find('all',['conditions'=>['field_name' =>'office_phone_no']]);
	
			$address='';
			$phone='';
	  
			foreach($query_address as $q)
				$address=$q['field_value'];

			foreach ($query_phone as $value)
				$phone=$value['field_value'];
			
			$this->set('address',$address);
			$this->set('phone',$phone);

			$get_user=TableRegistry::get('smgt_users');
			$get_user_data=$get_user->get($select_all['student_id']);
			$this->set('user_info',$get_user_data);                      
			  
			$cate_register = TableRegistry::get('smgt_categories');
			$fetch_all_cat=$cate_register->get($select_all['fees_id']);
			$this->set('cate_info',$fetch_all_cat);

			$history_table_register = TableRegistry::get('smgt_fee_payment_history');
			$get_all_history=$history_table_register->find()->where(['fees_pay_id'=>$select_all['fees_pay_id']])->hydrate(false)->toArray();
			if(!empty($get_all_history))
				$this->set('history',$get_all_history);
			
			$heading = $this->Setting->getfieldname('school_name');
			$stud_date = $this->Setting->getfieldname('date_format');
			
			$currency = $this->Setting->getfieldname('currency_code');
			$currency_symbol = $this->Setting->get_currency_symbole($currency);
			$this->set('currency_symbol',$currency_symbol);
			
			$school_logo = $this->Setting->getfieldname('school_logo');	
			$attach_logo = WWW_ROOT ."img/".$school_logo;
			$ext = pathinfo($attach_logo, PATHINFO_EXTENSION);				
			$logo = $this->Setting->base64_encode_image ($attach_logo,$ext);
			
			$total_amount = !empty($select_all['total_amount'])?$select_all['total_amount']:0;
			$fees_paid_amount = !empty($select_all['fees_paid_amount'])?$select_all['fees_paid_amount']:0;
			$due_amount = 0;
			$due_amount = $select_all['total_amount'] - $select_all['fees_paid_amount'];

			if($select_all['payment_status'] == 0){
				$sta = __('Not Paid');
			}else if($select_all['payment_status'] == 1){
				$sta = __('Partially Paid');
			}else if($select_all['payment_status'] == 2){
				$sta = __('Fully Paid');
			}

			echo '
			<page size="A4">
				
			<img class="invoiceIMG" style="vertical-align:top;margin-top:-60px;background-repeat:no-repeat;" src="'.$this->request->base.'/webroot/img/new_1000_1.jpg" width="100%">
			
			<div class="mainarea">
				<div class="movetop">
					<div class="mainlogo">
						<img src="'.$logo.'"/>
					</div>
					<div class="schoolname">	
						<div class="textcr"><b>'.$heading.'</b></div>
						<div class="textcr">'.$address.'</div>
						<div class="textcr">Phone No. '.$phone.'</div>
					</div>
				</div>	

				<div class="lastdiv">
					<div class="textcr first">Invoice <br><b style="text-align:right;">#'.$select_all['fees_pay_id'].'</b></div>
					<div class="textcr">Date : <b>'.date($stud_date,strtotime($select_all['created_date'])).'</b></div>
					<div class="textcr">Status : <b>'.$sta.'</b></div>
				</div>
				<div class="rightdiv">
					<div class="billtoimg">
						<h4>| Bill To.</h4>
					</div>
					<div class="billtocontent">
						<div class="textcr"><b>'.$get_user_data['first_name'].'  '.$get_user_data['last_name'].'</b></div>
						<div class="textcr">Student ID <b>'.$this->Setting->get_studentID($get_user_data['user_id']).'</b></div>
						<div class="textcr">'.$get_user_data['address'].'</div>
					</div>
				</div>	
				
				<div class="markstable">
				<table width=99% style="margin-top:40px;">
					<thead>
					<tr>
						<th>#</th>
						<th>Fee Type</th>
						<th>('.$currency_symbol.') Price</th>
					</tr>
					<thead>
					<tbody>
						<tr>
							<td align=center>1</td>
							<td align=center>'.$fetch_all_cat['category_type'].'</td>
							<td align=center>'.$currency_symbol." ".$select_all['total_amount'].'</td>
						</tr>
					</tbody>
				</table>
				</div>
				<table align=right width=250px style="margin-top: 20px;">
					<tr>
						<td colspan="3" align=right class="bordertop" style="padding: 5px 0px;"><b>Subtotal ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
						<td colspan="1" align=left class="bordertop" style="padding: 5px 0px;"><b>'.$currency_symbol." ".$total_amount.'</b></td>
					</tr>
					<tr>
						<td colspan="3" align=right class="bordertop" style="padding: 5px 0px;"><b>Discount ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
						<td colspan="1" align=left class="bordertop" style="padding: 5px 0px;"><b>'.$currency_symbol." ".'0.00'.'</b></td>
					</tr>
					<tr>
						<td colspan="3" align=right class="bordertop" style="padding: 5px 0px;"><b>Total Paid ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
						<td colspan="1" align=left class="bordertop" style="padding: 5px 0px;"><b>'.$currency_symbol." ".$fees_paid_amount.'</b></td>
					</tr>
					<tr>
						<td colspan="3" align=right class="bordertop" style="padding: 5px 0px;"><b>Amount Due ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
						<td colspan="1" align=left class="bordertop" style="padding: 5px 0px;"><b>'.$currency_symbol." ".$due_amount.'</b></td>
					</tr>
					<tr style="background-color: #03A9F4;color: #4E5E6A;">
						<td colspan="3" align=right class="bordertop" style="padding: 5px 0px;"><b>Grand Total ('.$currency_symbol.') : &nbsp;&nbsp;  </b></td>				
						<td colspan="1" align=left class="bordertop" style="padding: 5px 0px;"><b>'.$currency_symbol." ".$due_amount.'</b></td>
					</tr>
				</table>';
				if($get_all_history)
				{
					echo '	
					<div class="markstable">
					<h4>Payment History</h4>
					<table width=99% style="margin-top:20px;">
					<thead>
					<tr>
						<th>#</th>
						<th>Date</th>
						<th>('.$currency_symbol.') Price</th>
						<th>Method</th>
					</tr>
					<thead>
					<tbody>';
					$num=1;
					foreach($get_all_history as $history_info)
					{
						echo '
							<tr>
								<td align=center>'.(string)$num.'</td>
								<td align=center>'.date($stud_date,strtotime($history_info['paid_by_date'])).'</td>
								<td align=center>'.$currency_symbol." ".$history_info['amount'].'</td>
								<td align=center>'.$history_info['payment_method'].'</td>
							</tr>';
						$num++;
					}
				echo '
				</tbody>
				</table>
				</div>';
				}
				echo '
				<div class="signature">
					<hr color="#97C4E7">
					<span>Signature</span>
				</div>
			</div>
		</page>';
		}
		else
			return $this->redirect(['action'=>'feelist']);
	}
}

?>
