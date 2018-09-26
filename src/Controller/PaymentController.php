<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class PaymentController extends AppController
{


    public function deleteincome($id){

        $income_table_register=TableRegistry::get('smgt_income_expense');
			$this->request->is(['post','delete']);
			$item=$income_table_register->get($id);
			if($income_table_register->delete($item))
			{
				$this->Flash->success(__('Income Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));	
							 
				return $this->redirect(['action'=>'incomelist']);
			}

    }
	
	public function paymentmultidelete() 
		{
			$this->autoRender = false;
			$id=json_decode($_REQUEST[p_id]);
			foreach($id as $recordid)
				{
						$class = TableRegistry::get('smgt_payment');
						
						$item =$class->get($recordid);

						if($class->delete($item))
						{
							
						}
						
				}
		}
	

	public function incomepdf($id)
	{
		$id = $this->Setting->my_simple_crypt($id,'d');	
		$income_id=$id;

		$income_table_register=TableRegistry::get('smgt_income_expense');		
		$exists = $income_table_register->exists(['income_id' => $income_id]);
		
		if($exists)
		{
			$income_all_record=$income_table_register->get($income_id);

			$this->set('income_data',$income_all_record);

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

			$get_created_id=$income_all_record['create_by'];
			$get_bill_to=$income_all_record['supplier_name'];

			$user_table_register=TableRegistry::get('smgt_users');
			$get_user_info=$user_table_register->get($get_created_id);
			$Bill_To_id=$user_table_register->get($get_bill_to);
			
			$currency = $this->Setting->getfieldname('currency_code');
			$currency_symbol = $this->Setting->get_currency_symbole($currency);
			$this->set('currency_symbol',$currency_symbol);
			
			$this->set('usertobill',$Bill_To_id);
			$this->set('User_info',$get_user_info);
			$this->set('address',$address);
			$this->set('phone',$phone);
		}
		else
			return $this->redirect(['action'=>'incomelist']);
	}
	
	public function incomeprint($id)
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
		
		$id = $this->Setting->my_simple_crypt($id,'d');	
		$income_id=$id;

		$income_table_register=TableRegistry::get('smgt_income_expense');		
		$exists = $income_table_register->exists(['income_id' => $income_id]);
		
		if($exists)
		{
			$income_all_record=$income_table_register->get($income_id);

			$this->set('income_data',$income_all_record);

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

			$get_created_id=$income_all_record['create_by'];
			$get_bill_to=$income_all_record['supplier_name'];

			$user_table_register=TableRegistry::get('smgt_users');
			$get_user_info=$user_table_register->get($get_created_id);
			$Bill_To_id=$user_table_register->get($get_bill_to);
			
			$full_info=$get_user_info['first_name'].' '.$get_user_info['last_name'];
			$address=$get_user_info['address'];
			$billToname=$Bill_To_id['first_name'].' '.$Bill_To_id['last_name'];
			$Bill_To_Address=$Bill_To_id['address'];	

			$currency = $this->Setting->getfieldname('currency_code');
			$currency_symbol = $this->Setting->get_currency_symbole($currency);
			$stud_date = $this->Setting->getfieldname('date_format');
			
			$heading = $this->Setting->getfieldname('school_name');
			
			$school_logo = $this->Setting->getfieldname('school_logo');

			$attach_logo = WWW_ROOT ."img/".$school_logo;
			$ext = pathinfo($attach_logo, PATHINFO_EXTENSION);				
			$logo = $this->Setting->base64_encode_image ($attach_logo,$ext);
			
			$this->set('currency_symbol',$currency_symbol);
			$this->set('usertobill',$Bill_To_id);
			$this->set('User_info',$get_user_info);
			$this->set('address',$address);
			$this->set('phone',$phone);
			
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
					<div class="textcr first">Invoice <br><b style="text-align:right;">#'.$income_all_record['income_id'].'</b></div>
					<div class="textcr">Date : <b>'.date($stud_date,strtotime($income_all_record['income_create_date'])).'</b></div>
					<div class="textcr">Status : <b>'.$income_all_record['payment_status'].'</b></div>
				</div>
				<div class="rightdiv">
					<div class="billtoimg">
						<h4>| Bill To.</h4>
					</div>
					<div class="billtocontent">
						<div class="textcr"><b>'.$billToname.'</b></div>
						<div class="textcr">Student ID <b>'.$this->Setting->get_studentID($Bill_To_id['user_id']).'</b></div>
						<div class="textcr">'.$Bill_To_Address.'</div>
					</div>
				</div>
				
				<div class="markstable">
				<table width=99% style="margin-top:40px;">
					<thead>
					<tr>
						<th>#</th>
						<th>Income Date</th>
						<th>Income Name</th>
						<th>('.$currency_symbol.') Price</th>
					</tr>
					<thead>
					<tbody>';
					
					$num=1;
					$total_amount=0;
					$entry=$income_all_record['entry'];
					$am=json_decode($entry);
					$amount=array();
					
					foreach($am as $total)
					{
						$total_amount=$total_amount + $total->amount;
						echo '
						<tr>
							<td align=center>'.(string)$num.'</td>
							<td align=center>'.date($stud_date,strtotime($income_all_record['income_create_date'])).'</td>
							<td align=center>'.$total->entry.'</td>
							<td align=center>'.$currency_symbol." ".$total->amount.'</td>
						</tr>';
						$num++;
					}
					echo '						
					</tbody>
				</table>
				</div>
				<table align=right width=280px style="margin-top: 20px;">
					<tr>
						<td colspan="3" align=right class=" " style="padding: 5px 0px;"><b>Subtotal ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
						<td colspan="1" align=left class="" style="padding: 5px 0px;"><b>'.$currency_symbol." ".(string)$total_amount.'</b></td>
					</tr>
					<tr>
						<td colspan="3" align=right class=" " style="padding: 5px 0px;"><b>Discount ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
						<td colspan="1" align=left class="" style="padding: 5px 0px;"><b>'.$currency_symbol." ".'0.00'.'</b></td>
					</tr>
					<tr>
						<td colspan="3" align=right class=" " style="padding: 5px 0px;"><b>Total Paid ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
						<td colspan="1" align=left class="" style="padding: 5px 0px;"><b>'.$currency_symbol." ".(string)$total_amount.'</b></td>
					</tr>
					<tr>
						<td colspan="3" align=right class=" " style="padding: 5px 0px;"><b>Amount Due ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
						<td colspan="1" align=left class="" style="padding: 5px 0px;"><b>'.$currency_symbol." ".'0.00'.'</b></td>
					</tr>
					<tr style="background-color: #03A9F4;color: #4E5E6A;">
						<td colspan="3" align=right class=" " style="padding: 5px 0px;"><b>Grand Total ('.$currency_symbol.') : &nbsp;&nbsp;  </b></td>				
						<td colspan="1" align=left class="" style="padding: 5px 0px;"><b>'.$currency_symbol." ".(string)$total_amount.'</b></td>
					</tr>
				</table>
				<div class="signature">
					<hr color="#97C4E7">
					<span>Signature</span>
				</div>
			</div>
		</page>';
		}
		else
			return $this->redirect(['action'=>'incomelist']);
	}
	
	public function incomemultidelete() 
		{
			$this->autoRender = false;
			$id=json_decode($_REQUEST[i_id]);
			foreach($id as $recordid)
				{
						$class = TableRegistry::get('smgt_income_expense');
						
						$item =$class->get($recordid);

						if($class->delete($item))
						{
							
						}
						
				}
		}
	
    public function paymentprint($id)
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
			width: auto;
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
		
		$this->set('id',$id);

		$payment_table_register=TableRegistry::get('smgt_payment');
		$id = $this->Setting->my_simple_crypt($id,'d');	
		$exists = $payment_table_register->exists(['payment_id' => $id]);
		
		if($exists)
		{
			$get_all_record=$payment_table_register->get($id);
			$this->set('payment_data',$get_all_record);
		   
			$user_table_register=TableRegistry::get('smgt_users');
			$user_id=$get_all_record['payment_reciever_id'];
			$record_from_user=$user_table_register->get($user_id);
			$this->set('user',$record_from_user);

			$billto_name=$user_table_register->get($get_all_record['user_id']);

			$this->set('btn',$billto_name);

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
			
			$stud_name=$billto_name['first_name'].' '.$billto_name['last_name'];
			$stud_address=$billto_name['address'];
			$username=$record_from_user['first_name'].''.$record_from_user['last_name'];
			$currency = $this->Setting->getfieldname('currency_code');
			$currency_symbol = $this->Setting->get_currency_symbole($currency);
			$stud_date = $this->Setting->getfieldname('date_format');
			$heading = $this->Setting->getfieldname('school_name');

			$school_logo = $this->Setting->getfieldname('school_logo');

			$attach_logo = WWW_ROOT ."img/".$school_logo;
			$ext = pathinfo($attach_logo, PATHINFO_EXTENSION);				
			$logo = $this->Setting->base64_encode_image ($attach_logo,$ext);
			
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
						<div class="textcr first">Invoice <br><b style="text-align:right;">#'.$get_all_record['payment_id'].'</b></div>
						<div class="textcr">Date : <b>'.date($stud_date,strtotime($get_all_record['date'])).'</b></div>
						<div class="textcr">Status : <b>'.$get_all_record['payment_status'].'</b></div>
					</div>
					<div class="rightdiv">
						<div class="billtoimg">
							<h4>| Bill To.</h4>
						</div>
						<div class="billtocontent">
							<div class="textcr"><b>'.$stud_name.'</b></div>
							<div class="textcr">Student ID <b>'.$this->Setting->get_studentID($billto_name['user_id']).'</b></div>
							<div class="textcr">'.$stud_address.'</div>
						</div>
					</div>
										
					<div class="markstable">
					<table width=99% align=left style="margin-top:40px;">
						<thead>
						<tr>
							<th>#</th>
							<th>Payment Date</th>
							<th>Payment Name</th>
							<th>Price</th>
						</tr>
						<thead>
						<tbody>
							<tr>
								<td align=center>1</td>
								<td align=center>'.date($stud_date,strtotime($get_all_record['date'])).'</td>
								<td align=center>'.$get_all_record['payment_title'].'</td>
								<td align=center>'.$currency_symbol." ".(string)$get_all_record['amount'].'</td>
							</tr>
						</tbody>
					</table>
					</div>
					<table align=right width=280px style="margin-top: 20px;">
						<tr>
							<td colspan="3" align=right class=" " style="padding: 5px 0px;"><b>Subtotal ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
							<td colspan="1" align=left class="" style="padding: 5px 0px;"><b>'.$currency_symbol." ".(string)$get_all_record['amount'].'</b></td>
						</tr>
						<tr>
							<td colspan="3" align=right class=" " style="padding: 5px 0px;"><b>Discount ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
							<td colspan="1" align=left class="" style="padding: 5px 0px;"><b>'.$currency_symbol." ".'0.00'.'</b></td>
						</tr>
						<tr>
							<td colspan="3" align=right class=" " style="padding: 5px 0px;"><b>Total Paid ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
							<td colspan="1" align=left class="" style="padding: 5px 0px;"><b>'.$currency_symbol." ".(string)$get_all_record['amount'].'</b></td>
						</tr>
						<tr>
							<td colspan="3" align=right class=" " style="padding: 5px 0px;"><b>Amount Due ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
							<td colspan="1" align=left class="" style="padding: 5px 0px;"><b>'.$currency_symbol." ".'0.00'.'</b></td>
						</tr>
						<tr style="background-color: #03A9F4;color: #4E5E6A;">
							<td colspan="3" align=right class=" " style="padding: 5px 0px;"><b>Grand Total ('.$currency_symbol.') : &nbsp;&nbsp;  </b></td>				
							<td colspan="1" align=left class="" style="padding: 5px 0px;"><b>'.$currency_symbol." ".(string)$get_all_record['amount'].'</b></td>
						</tr>
					</table>
					<div class="signature">
						<hr color="#97C4E7">
						<span>Signature</span>
					</div>
				</div>
			</page>';
		}
		else
			return $this->redirect(['action'=>'paymentlist']);
    }
	public function paymentpdf($id)
	{

		$this->set('id',$id);

		$payment_table_register=TableRegistry::get('smgt_payment');
		$id = $this->Setting->my_simple_crypt($id,'d');	
		$exists = $payment_table_register->exists(['payment_id' => $id]);
		
		if($exists)
		{
			$get_all_record=$payment_table_register->get($id);
			$this->set('payment_data',$get_all_record);
		   
			$user_table_register=TableRegistry::get('smgt_users');
			$user_id=$get_all_record['payment_reciever_id'];
			$record_from_user=$user_table_register->get($user_id);
			$this->set('user',$record_from_user);

			$billto_name=$user_table_register->get($get_all_record['user_id']);

			$this->set('btn',$billto_name);

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
			
			$currency = $this->Setting->getfieldname('currency_code');
			$currency_symbol = $this->Setting->get_currency_symbole($currency);
			
			$this->set('currency_symbol',$currency_symbol);
			
			$this->set('address',$address);
			$this->set('phone',$phone);
		}
		else
			return $this->redirect(['action'=>'paymentlist']);
    }
    public function viewdatapayment($id=null){
        //$this->autoRender=false;

        if($this->request->is('ajax')){
            $payment_id=$_POST['id'];

            $this->set('setid',$payment_id);

            $payment_table_register=TableRegistry::get('smgt_payment');
            $get_all_record=$payment_table_register->get($payment_id);
            $this->set('payment_data',$get_all_record);
            
            $user_id=$get_all_record['payment_reciever_id'];
			
			$user_table_register=TableRegistry::get('smgt_users');
            $record_from_user=$user_table_register->get($user_id);
            $this->set('user',$record_from_user);
			 
			
            $billto_name=$user_table_register->get($get_all_record['user_id']);
	
            $this->set('btn',$billto_name);

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

            $this->set('id',$payment_id);

        }
    }

	
	public function expmultidelete() 
		{
			$this->autoRender = false;
			$id=json_decode($_REQUEST[e_id]);
			foreach($id as $recordid)
				{
						$class = TableRegistry::get('smgt_income_expense');
						
						$item =$class->get($recordid);

						if($class->delete($item))
						{
							
						}
						
				}
		}
	
    public function expensepdf($id)
	{
		$id = $this->Setting->my_simple_crypt($id,'d');	
		$get_id=$id;
		$expense_table_register=TableRegistry::get('smgt_income_expense');
		
		$exists = $expense_table_register->exists(['income_id' => $id]);
		
		if($exists)
		{
			$get_field=$expense_table_register->get($get_id);
			$this->set('expense_data',$get_field);

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

			$create_id=$get_field['create_by'];

			$get_admin=TableRegistry::get('smgt_users');
			$get_admin_data=$get_admin->get($create_id);
			$this->set('admin_info',$get_admin_data);
			
			$currency = $this->Setting->getfieldname('currency_code');
			$currency_symbol = $this->Setting->get_currency_symbole($currency);
			$this->set('currency_symbol',$currency_symbol);
			
		}
		else
			return $this->redirect(['action'=>'expenselist']);
    }
	
	public function expenseprint($id)
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
		
		$id = $this->Setting->my_simple_crypt($id,'d');	
		$get_id=$id;
		$expense_table_register=TableRegistry::get('smgt_income_expense');
		
		$exists = $expense_table_register->exists(['income_id' => $id]);
		
		if($exists)
		{
			$get_field=$expense_table_register->get($get_id);
			$this->set('expense_data',$get_field);

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

			$create_id=$get_field['create_by'];

			$get_admin=TableRegistry::get('smgt_users');
			$get_admin_data=$get_admin->get($create_id);
			$this->set('admin_info',$get_admin_data);
			
			$heading = $this->Setting->getfieldname('school_name');
			$stud_date = $this->Setting->getfieldname('date_format');
			
			$currency = $this->Setting->getfieldname('currency_code');
			$currency_symbol = $this->Setting->get_currency_symbole($currency);
			$this->set('currency_symbol',$currency_symbol);
			
			$school_logo = $this->Setting->getfieldname('school_logo');	
			$attach_logo = WWW_ROOT ."img/".$school_logo;
			$ext = pathinfo($attach_logo, PATHINFO_EXTENSION);				
			$logo = $this->Setting->base64_encode_image ($attach_logo,$ext);
			
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
					<div class="textcr first">Invoice <br><b style="text-align:right;">#'.$get_field['income_id'].'</b></div>
					<div class="textcr">Date : <b>'.date($stud_date,strtotime($get_field['income_create_date'])).'</b></div>
					<div class="textcr">Status : <b>'.$get_field['payment_status'].'</b></div>
				</div>
				<div class="rightdiv">
					<div class="billtoimg">
						<h4>| Bill To.</h4>
					</div>
					<div class="billtocontent">
						<div class="textcr"><b>'.$get_field['supplier_name'].'</b></div>
						<div class="textcr"></div>
					</div>
				</div>
				
				<div class="markstable">
				<table width=99% style="margin-top:40px;">
					<thead>
					<tr>
						<th>#</th>
						<th>Expense Date</th>
						<th>Expense Name</th>
						<th>('.$currency_symbol.') Price</th>
					</tr>
					<thead>
					<tbody>';
					
					$num=1;
					$total_amount=0;
					$entry=$get_field['entry'];
					$am=json_decode($entry);
					$amount=array();
					
					foreach($am as $total)
					{
						$total_amount=$total_amount + $total->amount;
						
						echo '
						<tr>
							<td align=center>'.(string)$num.'</td>
							<td align=center>'.date($stud_date,strtotime($get_field['income_create_date'])).'</td>
							<td align=center>'.$total->entry.'</td>
							<td align=center>'.$currency_symbol." ".$total->amount.'</td>
						</tr>';
						$num++;
					}
					echo '						
					</tbody>
				</table>
				</div>
				<table align=right width=280px style="margin-top: 20px;">
					<tr>
						<td colspan="3" align=right class=" " style="padding: 5px 0px;"><b>Subtotal ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
						<td colspan="1" align=left class="" style="padding: 5px 0px;"><b>'.$currency_symbol." ".(string)$total_amount.'</b></td>
					</tr>
					<tr>
						<td colspan="3" align=right class=" " style="padding: 5px 0px;"><b>Discount ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
						<td colspan="1" align=left class="" style="padding: 5px 0px;"><b>'.$currency_symbol." ".'0.00'.'</b></td>
					</tr>
					<tr>
						<td colspan="3" align=right class=" " style="padding: 5px 0px;"><b>Total Paid ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
						<td colspan="1" align=left class="" style="padding: 5px 0px;"><b>'.$currency_symbol." ".(string)$total_amount.'</b></td>
					</tr>
					<tr>
						<td colspan="3" align=right class=" " style="padding: 5px 0px;"><b>Amount Due ('.$currency_symbol.') : &nbsp;&nbsp; </b></td>				
						<td colspan="1" align=left class="" style="padding: 5px 0px;"><b>'.$currency_symbol." ".'0.00'.'</b></td>
					</tr>
					<tr style="background-color: #03A9F4;color: #4E5E6A;">
						<td colspan="3" align=right class=" " style="padding: 5px 0px;"><b>Grand Total ('.$currency_symbol.') : &nbsp;&nbsp;  </b></td>				
						<td colspan="1" align=left class="" style="padding: 5px 0px;"><b>'.$currency_symbol." ".(string)$total_amount.'</b></td>
					</tr>
				</table>
				<div class="signature">
					<hr color="#97C4E7">
					<span>Signature</span>
				</div>
			</div>
			</page>';
		}
		else
			return $this->redirect(['action'=>'expenselist']);
    }
	
    public function viewdataexpense($id = null){

		if($this->request->is('ajax')){
                    $get_id=$_POST['id'];

                    $expense_table_register=TableRegistry::get('smgt_income_expense');

			$get_field=$expense_table_register->get($get_id);
                        $this->set('expense_data',$get_field);

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


				$create_id=$get_field['create_by'];

                                $get_admin=TableRegistry::get('smgt_users');

				$get_admin_data=$get_admin->get($create_id);

				$this->set('admin_info',$get_admin_data);
   
                }

    }

	

	public function deleteexpense($id){

		$expense_table_register=TableRegistry::get('smgt_income_expense');
			$this->request->is(['post','delete']);

			$item=$expense_table_register->get($id);
			if($expense_table_register->delete($item))
			{
				$this->Flash->success(__('Expense Deleted Successfully', null), 
                            'default', 
                             array('class' => 'success'));	
				
				return $this->redirect(['action'=>'expenselist']);
			}

	}



        public function viewdataincome($id=null){

           if($this->request->is('ajax')){
            $income_id=$_POST['id'];
                    $this->set('get_id',$income_id);
                    $income_table_register=TableRegistry::get('smgt_income_expense');
                    $income_all_record=$income_table_register->get($income_id);

                    $this->set('income_data',$income_all_record);

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

                        $get_created_id=$income_all_record['create_by'];
                        $get_bill_to=$income_all_record['supplier_name'];



                        $user_table_register=TableRegistry::get('smgt_users');
                        $get_user_info=$user_table_register->get($get_created_id);

                        $Bill_To_id=$user_table_register->get($get_bill_to);

                        $this->set('usertobill',$Bill_To_id);

                        $this->set('User_info',$get_user_info);



                        $this->set('address',$address);
                        $this->set('phone',$phone);


             }
        }

	public function addincome($id=null)
	{
						
		$class_table_register=TableRegistry::get('classmgt');
		$get_class=$class_table_register->find();
		$this->set('class_info',$get_class);

		$income_table_register=TableRegistry::get('smgt_income_expense');
		$income_table_entity=$income_table_register->newEntity();

		$student_info=TableRegistry::get('smgt_users');

		if(isset($id))
		{
			$this->set('edit',true);
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $income_table_register->exists(['income_id' => $id]);
			
			if($exists)
			{
				$get_income_record=$income_table_register->get($id);

				$getclass_name=$class_table_register->find('all',['conditions'=>['class_id'=>$get_income_record['class_id']]]);

				$classn='';
				$classid='';

				foreach($getclass_name as $c)
				{
					$classn=$c['class_name'];
					$classid=$c['class_id'];
				}

				$this->set('class_name',$classn);
				$this->set('classid',$classid);

				$getall_from_user=$get_income_record['class_id'];

				$allstudent_from_class=$student_info->find('all',['conditions'=>['classname'=>$getall_from_user,'role'=>'student']]);
				$this->set('allstudent_from_class',$allstudent_from_class);

				if($this->request->is('post'))
				{
					$data1=$this->request->data;
					
					$all_value_entry1=$data1['custom_value'];
					$all_label1=$data1['custom_label'];

					$entry_data1=array();
					$i1=0;

					foreach ($all_value_entry1 as $one_entry1) 
					{
						$entry_data1[]=array('amount'=>$all_label1[$i1],'entry'=>$one_entry1);
						$i1++;
					}
					
					$custom_field1=json_encode($entry_data1);
					$data1['entry']=$custom_field1;

					$update_income=$income_table_register->patchEntity($get_income_record,$data1);
					if($income_table_register->save($update_income))
					{
						$this->Flash->success(__('Income Updated Successfully', null), 
						'default', 
						 array('class' => 'alert alert-success'));
						 
						return $this->redirect(['action'=>'incomelist']);
					}
					else
					{
						echo 'Some Error in Update Data';
					}
				}
				
				$this->set('incomerecord',$get_income_record);
			}
			else
				return $this->redirect(['action'=>'incomelist']);
		}
		
		if($this->request->is('post'))
		{
			$data=$this->request->data;

			$all_value_entry=$data['custom_value'];
			$all_label=$data['custom_label'];

			$entry_data=array();
			$i=0;

			foreach ($all_value_entry as $one_entry) 
			{
				$entry_data[]=array('amount'=>$all_label[$i],'entry'=>$one_entry);
				$i++;
			}

			$custom_field=json_encode($entry_data);

			$data['entry']=$custom_field;
			$data['supplier_name']=$this->request->data('user_id');
			$data['income_create_date']=date('Y-m-d',strtotime($this->request->data('income_create_date')));
			$data['create_by']=$this->request->session()->read('user_id');

			$Add_Data=$income_table_register->patchEntity($income_table_entity,$data);

			if($income_table_register->save($Add_Data))
			{
				 $this->Flash->success(__(' Income added Successfully', null), 
									'default', 
									 array('class' => 'success'));
			}
			return $this->redirect(['action'=>'incomelist']);
		}
	}

	public function addpayment($id=null)
	{
		$class_table_register=TableRegistry::get('classmgt');
		$get_class=$class_table_register->find();
		$this->set('class_info',$get_class);

		$payment_table_register=TableRegistry::get('smgt_payment');
		$payment_table_entity=$payment_table_register->newEntity();

        $student_info=TableRegistry::get('smgt_users');

        if(isset($id))
		{
			$this->set('edit',true);
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $payment_table_register->exists(['payment_id' => $id]);
			
			if($exists)
			{
				$get_all_payment=$payment_table_register->get($id);

				$getall_from_user=$get_all_payment['class_id'];

				$allstudent_from_class=$student_info->find('all',['conditions'=>['classname'=>$getall_from_user,'role'=>'student']]);
				$this->set('allstudent_from_class',$allstudent_from_class);

				if($this->request->is('post'))
				{
				   $data1=$this->request->data;
				   $data1['payment_reciever_id']=$this->request->session()->read('user_id');
				   $data1['date']=date("Y-m-d");

					$update_payment=$payment_table_register->patchEntity($get_all_payment,$data1);
					if($payment_table_register->save($update_payment))
					{
						$this->Flash->success(__('Payment Updated Successfully', null), 
							'default', 
								array('class' => 'alert alert-success'));
					 
						return $this->redirect(['action'=>'paymentlist']);
					}
					else
					{
						echo 'Some Error in Update Data';
					}

			   }
			   $this->set('row',$get_all_payment);
			}
			else
				return $this->redirect(['action'=>'paymentlist']);
        }
		else{}
		
		if($this->request->is('post'))
		{
			$data=$this->request->data;
			
			$data['payment_reciever_id']=$this->request->session()->read('user_id');
			$data['date']=date("Y-m-d");
			
			$payment_add=$payment_table_register->patchEntity($payment_table_entity,$data);

			if($payment_table_register->save($payment_add))
			{
				 $this->Flash->success(__('Payment added Successfully', null), 
									'default', 
									 array('class' => 'success'));
									 
				return $this->redirect(['action'=>'paymentlist']);
			}
			else
			{
				echo 'Some Error in Insert Data';
			}
		}
	}

	public function addexpense($id = null)
	{
		$income_table_register=TableRegistry::get('smgt_income_expense');

		if(isset($id))
		{
			$this->set('edit',true);
			$id = $this->Setting->my_simple_crypt($id,'d');	
			$exists = $income_table_register->exists(['income_id' => $id]);
			
			if($exists)
			{
				$fetch=$income_table_register->get($id);

				if($this->request->is('post'))
				{
					$data1=$this->request->data;

					$all_value_entry1=$data1['custom_value'];
					$all_label1=$data1['custom_label'];

					$entry_data1=array();
					$i1=0;

					foreach ($all_value_entry1 as $one_entry1) 
					{
						$entry_data1[]=array('amount'=>$all_label1[$i1],'entry'=>$one_entry1);
						$i1++;
					}

					$custom_field1=json_encode($entry_data1);

					$data1['entry']=$custom_field1;
		
					$fetch=$income_table_register->patchEntity($fetch,$data1);

					if($income_table_register->save($fetch))
					{
						$this->Flash->success(__('Payment Updated Successfully', null), 
								'default', 
								 array('class' => 'success'));
								 
						return $this->redirect(['controller'=>'Payment','action'=>'expenselist']);
					}
					else
					{
						echo 'Some Error in Update Page';
					}
				}
				$this->set('row',$fetch);
			}
			else
				return $this->redirect(['action'=>'expenselist']);
		}
		
		$income_table_entity=$income_table_register->newEntity();
		
		if($this->request->is('post'))
		{
			$data=$this->request->data;
			$all_value_entry=$data['custom_value'];
			$all_label=$data['custom_label'];

			$entry_data=array();
			$i=0;

			foreach ($all_value_entry as $one_entry) 
			{
				$entry_data[]=array('amount'=>$all_label[$i],'entry'=>$one_entry);
				$i++;
			}

			$custom_field=json_encode($entry_data);

			$data['entry']=$custom_field;
			$data['create_by']=$this->request->session()->read('user_id');;

			$Add_Data=$income_table_register->patchEntity($income_table_entity,$data);
			if($income_table_register->save($Add_Data))
			{
				 $this->Flash->success(__('Expense added Successfully', null), 
									'default', 
									 array('class' => 'success'));

				return $this->redirect(['action'=>'expenselist']);
			}
		}
	}

	public function expenselist(){
		$expense_table_register=TableRegistry::get('smgt_income_expense');
		$fetch_data_from_expense=$expense_table_register->find()->where(['invoice_type' =>'expense']);
		$this->set('rows',$fetch_data_from_expense);
	}


	public function paymentlist(){
		$payment_table_register=TableRegistry::get('smgt_payment');
		$users_table_register=TableRegistry::get('smgt_users');
		$fetch_data_from_payment=$payment_table_register->find();
		$fetch_data_user=$users_table_register->find();
		$this->set('payment',$fetch_data_from_payment);
		$this->set('user',$fetch_data_user);
		}

	public function incomelist(){

		$income_table_register=TableRegistry::get('smgt_income_expense');
                $users_table_register=TableRegistry::get('smgt_users');

		$fetch_data_from_income=$income_table_register->find()->where(['invoice_type' =>'income']);
		$fetch_data_users=$users_table_register->find();

                $this->set('income_data',$fetch_data_from_income);
                $this->set('user_data',$fetch_data_users);



	}


	public function delete($id){

			$payment_table_register=TableRegistry::get('smgt_payment');
			$this->request->is(['post','delete']);

			$item=$payment_table_register->get($id);
			if($payment_table_register->delete($item))
			{
				 $this->Flash->success(__('Payment Deleted Successfully', null), 
									'default', 
									 array('class' => 'success'));
									 
				return $this->redirect(['action'=>'paymentlist']);
			}

	}


	public function ShowStudent($id = null){

		$user_table_register=TableRegistry::get('smgt_users');


		$this->autoRender=false;
		if($this->request->is('ajax')){

			$get_id=$_POST['id'];

				$Get_All_Data=$user_table_register->find()->where(['classsection'=>$get_id]);
			?>

			<select class="form-control validate[required]" name="user_id" id="classid">
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



}

?>
