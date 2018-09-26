<?php
namespace App\Controller;

use Cake\Controller\Controller;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
use Cake\Controller\Exception\SecurityException;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use Cake\Auth\DefaultPasswordHasher;

class InstallerController extends Controller
{
	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
		if (isset($this->request->params['admin'])) {
            $this->Security->requireSecure();
        }
    }
	
	public function initialize()
    {
		parent::initialize();
		$this->viewBuilder()->layout("school_install");
		$this->loadComponent('Csrf');
		$this->loadComponent('Setting');
        $this->loadComponent('Security',['blackHoleCallback' => 'forceSSL']);
    }
	
	public function forceSSL()
    {
     
    }
	
	public function index()
	{
		if (file_exists(TMP.'installed.txt')) 
		{ 
			return $this->redirect(["controller"=>"users"]);
			die;
		}
		if($this->request->is("post"))
		{	
			$file = ROOT . DS . 'config'. DS . 'app.php';       
			$content = file_get_contents($file);	
			
			$db_host = $this->request->data["db_host"];
			$db_username = $this->request->data["db_username"];
			$db_pass = $this->request->data["db_pass"];
			$db_name = $this->request->data["db_name"];
			
			$con = mysqli_connect($db_host,$db_username,$db_pass,$db_name);		
			if (mysqli_connect_errno())
			{
				echo "Failed to connect to Database : " . mysqli_connect_error();
				die;
			}
		  
			$content = str_replace(["CUST_HOST","CUST_USERNAME","CUST_PW","CUST_DB_NAME"],[$db_host,$db_username,$db_pass,$db_name],$content);
			$status = file_put_contents($file, $content);
			
			$this->gymTableInstall($db_name,$db_username,$db_host,$db_pass);
			$this->insertData($this->request->data);
		}
	}
	
	private function gymTableInstall($db_name,$db_username,$db_host,$db_pass)
    {		
		$this->viewBuilder()->layout("");
		$this->autoRender = false;	
				
		$config = [
					'className' => 'Cake\Database\Connection',
					'driver' => 'Cake\Database\Driver\Mysql',
					'persistent' => false,
					'host' => $db_host,
					'username' => $db_username,
					'password' => $db_pass,
					'database' => $db_name,
					'encoding' => 'utf8',
					'timezone' => 'UTC',
					'flags' => [],
					'cacheMetadata' => true,
					'log' => false,
					'quoteIdentifiers' => false,           
					'url' => env('DATABASE_URL', null)
				];
			
		ConnectionManager::config('install_db', $config);
		$conn = ConnectionManager::get('install_db');				
		
		$sql="CREATE TABLE IF NOT EXISTS `smgt_admission` (
		  `admission_id` int(11) NOT NULL AUTO_INCREMENT,
		  `roll_no` int(11) DEFAULT NULL,
		  `adminssion_roll` varchar(255) DEFAULT NULL,
		  `adminssion_status` int(11) DEFAULT NULL,
		  `admission_no` varchar(50) NOT NULL,
		  `admission_date` date NOT NULL,
		  `first_name` varchar(255) DEFAULT NULL,
		  `middle_name` varchar(255) DEFAULT NULL,
		  `last_name` varchar(255) NOT NULL,
		  `date_of_birth` date DEFAULT NULL,
		  `gender` varchar(255) DEFAULT NULL,
		  `address` varchar(255) DEFAULT NULL,
		  `state` varchar(255) DEFAULT NULL,
		  `city` varchar(255) NOT NULL,
		  `zip_code` varchar(255) DEFAULT NULL,
		  `mobile_no` varchar(255) DEFAULT NULL,
		  `phone` varchar(255) DEFAULT NULL,
		  `email` varchar(255) DEFAULT NULL,
		  `preschoolname` varchar(255) DEFAULT NULL,
		  `role` varchar(255) DEFAULT NULL,
		  `siblingsone` varchar(255) NOT NULL DEFAULT 'NULL',
		  `siblingstwo` varchar(255) NOT NULL DEFAULT 'NULL',
		  `siblith` varchar(255) NOT NULL DEFAULT 'NULL',
		  `fathersalutation` varchar(255) DEFAULT NULL,
		  `mothersalutation` varchar(255) DEFAULT NULL,
		  `fatherfn` varchar(255) DEFAULT NULL,
		  `motherfn` varchar(255) DEFAULT NULL,
		  `fathermn` varchar(255) DEFAULT NULL,
		  `mothermn` varchar(255) DEFAULT NULL,
		  `fatherln` varchar(255) DEFAULT NULL,
		  `motherln` varchar(255) DEFAULT NULL,
		  `fatheremail` varchar(255) DEFAULT NULL,
		  `motheremail` varchar(255) DEFAULT NULL,
		  `fathermob` varchar(255) DEFAULT NULL,
		  `mothermob` varchar(255) DEFAULT NULL,
		  `fatherschool` varchar(255) DEFAULT NULL,
		  `motherschool` varchar(255) DEFAULT NULL,
		  `fathermedium` varchar(255) DEFAULT NULL,
		  `mothermedium` varchar(255) DEFAULT NULL,
		  `fatherhighest` varchar(255) DEFAULT NULL,
		  `motherhighest` varchar(255) DEFAULT NULL,
		  `fatheincome` varchar(255) DEFAULT NULL,
		  `motherincome` varchar(255) DEFAULT NULL,
		  `fatheroccu` varchar(255) DEFAULT NULL,
		  `motheroccu` varchar(255) DEFAULT NULL,
		  `status` varchar(255) DEFAULT NULL,
		  `fatdocume` varchar(255) DEFAULT NULL,
		  `motdocume` varchar(255) DEFAULT NULL,
		  PRIMARY KEY (`admission_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
		
		$stmt = $conn->execute($sql);
		
		$sql="CREATE TABLE IF NOT EXISTS `smgt_add_beds` (
		  `bed_id` int(11) NOT NULL AUTO_INCREMENT,
		  `bed_unique_id` text NOT NULL,
		  `room_unique_id` int(11) NOT NULL,
		  `bed_status` int(11) NOT NULL,
		  `bed_desc` text NOT NULL,
		  `created_date` date NOT NULL,
		  `created_by` bigint(20) NOT NULL,
		  PRIMARY KEY (`bed_id`),
		  KEY `room_unique_id` (`room_unique_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
		
		$stmt = $conn->execute($sql);
		
		$sql="CREATE TABLE IF NOT EXISTS `smgt_assign_bed_new` (
		  `assign_id` int(11) NOT NULL AUTO_INCREMENT,
		  `room_unique_id` int(11) NOT NULL,
		  `bed_unique_id` text NOT NULL,
		  `student_id` int(11) NOT NULL,
		  `assign_date` date NOT NULL,
		  `created_date` date NOT NULL,
		  `created_by` bigint(20) NOT NULL,
		  PRIMARY KEY (`assign_id`),
		  KEY `room_unique_id` (`room_unique_id`),
		  KEY `student_id` (`student_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
		
		$stmt = $conn->execute($sql);
		
		$sql="CREATE TABLE IF NOT EXISTS `smgt_event` (
		  `event_id` int(11) NOT NULL AUTO_INCREMENT,
		  `event_title` varchar(200) NOT NULL,
		  `event_desc` text NOT NULL,
		  `start_date` date NOT NULL,
		  `end_date` date NOT NULL,
		  `event_for` varchar(100) NOT NULL,
		  `created_date` date NOT NULL,
		  `created_by` bigint(20) NOT NULL,
		  PRIMARY KEY (`event_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
		
		$stmt = $conn->execute($sql);
		
		$sql="CREATE TABLE IF NOT EXISTS `smgt_export` (
		  `export_id` int(11) NOT NULL AUTO_INCREMENT,
		  `export_title` varchar(200) NOT NULL,
		  `export_model` text NOT NULL,
		  `student` text NOT NULL,
		  `teacher` text NOT NULL,
		  `parent` text NOT NULL,
		  `staff` text NOT NULL,
		  `import_title` varchar(200) NOT NULL,
		  `import_model` text NOT NULL,
		  `type` varchar(50) NOT NULL,
		  `created_date` date NOT NULL,
		  `created_by` bigint(20) NOT NULL,
		  `modify_date` date NOT NULL,
		  `modify_by` bigint(20) NOT NULL,
		  PRIMARY KEY (`export_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
		
		$stmt = $conn->execute($sql);
		
		$sql="CREATE TABLE IF NOT EXISTS `smgt_hostel` (
		  `hostel_id` int(11) NOT NULL AUTO_INCREMENT,
		  `hostel_name` varchar(50) NOT NULL,
		  `hostel_type` varchar(50) NOT NULL,
		  `hostel_desc` text NOT NULL,
		  `created_by` bigint(20) NOT NULL,
		  `created_date` date NOT NULL,
		  `modify_by` bigint(20) NOT NULL,
		  `modify_date` date NOT NULL,
		  PRIMARY KEY (`hostel_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
		
		$stmt = $conn->execute($sql);
		
		$sql="CREATE TABLE IF NOT EXISTS `smgt_hostel_room` (
		  `room_id` int(11) NOT NULL AUTO_INCREMENT,
		  `room_unique_id` text NOT NULL,
		  `hostel_id` int(11) NOT NULL,
		  `room_status` int(11) NOT NULL,
		  `room_category` int(11) NOT NULL,
		  `beds_capacity` int(11) NOT NULL,
		  `room_desc` text NOT NULL,
		  `created_date` date NOT NULL,
		  `created_by` bigint(20) NOT NULL,
		  `modify_date` date NOT NULL,
		  `modify_by` bigint(20) NOT NULL,
		  PRIMARY KEY (`room_id`),
		  KEY `hostel_id` (`hostel_id`),
		  KEY `room_category` (`room_category`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
		
		$stmt = $conn->execute($sql);
		
		$sql="CREATE TABLE IF NOT EXISTS `smgt_hostel_room_category` (
		  `hostel_room_category_id` int(11) NOT NULL AUTO_INCREMENT,
		  `category_name` varchar(50) NOT NULL,
		  `created_date` date NOT NULL,
		  `created_by` bigint(20) NOT NULL,
		  `modify_date` date NOT NULL,
		  `modify_by` bigint(20) NOT NULL,
		  PRIMARY KEY (`hostel_room_category_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
		
		$stmt = $conn->execute($sql);
		
		$sql="CREATE TABLE IF NOT EXISTS `smgt_news` (
		  `news_id` int(11) NOT NULL AUTO_INCREMENT,
		  `news_title` varchar(500) NOT NULL,
		  `news_desc` text NOT NULL,
		  `news_start_date` date NOT NULL,
		  `news_end_date` date NOT NULL,
		  `news_document` text NOT NULL,
		  `created_date` date NOT NULL,
		  `created_by` bigint(20) NOT NULL,
		  PRIMARY KEY (`news_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
		
		$stmt = $conn->execute($sql);
		
		$sql="CREATE TABLE IF NOT EXISTS `child_tbl` (
		  `child_tbl_id` int(11) NOT NULL AUTO_INCREMENT,
		  `child_parent_id` int(11) NOT NULL,
		  `child_id` int(11) NOT NULL,
		  `created_by` int(11) NOT NULL,
		  `created_date` date NOT NULL,
		  `status` varchar(20) NOT NULL,
		  PRIMARY KEY (`child_tbl_id`),
		  KEY `child_parent_id` (`child_parent_id`),
		  KEY `child_id` (`child_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
		
		$stmt = $conn->execute($sql);
		
		$sql="CREATE TABLE IF NOT EXISTS `classmgt` (
		  `class_id` int(11) NOT NULL AUTO_INCREMENT,
		  `class_name` varchar(100) NOT NULL,
		  `class_num_name` varchar(5) NOT NULL,
		  `class_capacity` tinyint(4) NOT NULL,
		  `creater_id` int(11) NOT NULL,
		  `created_date` date NOT NULL,
		  `modified_date` date NOT NULL,
		  PRIMARY KEY (`class_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
		
		$stmt = $conn->execute($sql);
		
		$sql="CREATE TABLE IF NOT EXISTS `class_section` (
		  `class_section_id` int(11) NOT NULL AUTO_INCREMENT,
		  `class_id` int(11) NOT NULL,
		  `section_name` varchar(100) NOT NULL,
		  `is_deactive` tinyint(4) NOT NULL,
		  `created_date` datetime NOT NULL,
		  `created_by` int(11) NOT NULL,
		  PRIMARY KEY (`class_section_id`),
		  KEY `class_id` (`class_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
			
		$stmt = $conn->execute($sql);
		
		$sql="CREATE TABLE IF NOT EXISTS `smgt_attendence` (
		  `attendence_id` int(50) NOT NULL AUTO_INCREMENT,
		  `user_id` int(50) NOT NULL,
		  `class_id` int(50) NOT NULL,
		  `attend_by` int(11) NOT NULL,
		  `attendence_date` date NOT NULL,
		  `status` varchar(50) NOT NULL,
		  `mail_status` tinyint(4) NOT NULL DEFAULT '0',
		  `role_name` varchar(20) NOT NULL,
		  `comment` text NOT NULL,
		  `section` int(11) DEFAULT NULL,
		  PRIMARY KEY (`attendence_id`),
		  KEY `user_id` (`user_id`),
		  KEY `class_id` (`class_id`),
		  KEY `attend_by` (`attend_by`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
				
		$stmt = $conn->execute($sql);
				
		$sql="CREATE TABLE IF NOT EXISTS `smgt_categories` (
		  `category_id` int(11) NOT NULL AUTO_INCREMENT,
		  `category_type` varchar(50) NOT NULL,
		  `category_title` varchar(50) NOT NULL,
		  PRIMARY KEY (`category_id`) KEY_BLOCK_SIZE=11
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";		
							
		$stmt = $conn->execute($sql);
					
		$sql="CREATE TABLE IF NOT EXISTS `smgt_exam` (
		  `exam_id` int(11) NOT NULL AUTO_INCREMENT,
		  `exam_name` varchar(200) NOT NULL,
		  `class_id` int(11) NOT NULL,
		  `section_id` int(11) NOT NULL,
		  `term_id` int(11) NOT NULL,
		  `pass_mark` tinyint(3) NOT NULL,
		  `total_mark` tinyint(3) NOT NULL,
		  `exam_date` date NOT NULL,
		  `exam_end_date` date NOT NULL,
		  `exam_comment` text NOT NULL,
		  `syllabus` text NOT NULL,
		  `created_date` date DEFAULT NULL,
		  `modified_date` date DEFAULT NULL,
		  `exam_creater_id` int(11) DEFAULT NULL,
		  PRIMARY KEY (`exam_id`),
		  KEY `class_id` (`class_id`),
		  KEY `section_id` (`section_id`),
		  KEY `term_id` (`term_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
		
	 	$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `smgt_exam_hall_receipt` (
		  `receipt_id` int(11) NOT NULL AUTO_INCREMENT,
		  `exam_id` int(11) NOT NULL,
		  `user_id` int(11) NOT NULL,
		  `hall_id` int(11) NOT NULL,
		  `exam_hall_receipt_status` int(11) NOT NULL,
		  `created_date` date NOT NULL,
		  `created_by` int(11) NOT NULL,
		  PRIMARY KEY (`receipt_id`),
		  KEY `exam_id` (`exam_id`),
		  KEY `user_id` (`user_id`),
		  KEY `hall_id` (`hall_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
		
	 	$stmt = $conn->execute($sql);	
		
		$sql="CREATE TABLE IF NOT EXISTS `comman_access` (
		  `comman_access_id` int(11) NOT NULL AUTO_INCREMENT,
		  `comman_action` varchar(100) NOT NULL,
		  `student` int(11) NOT NULL,
		  `teacher` int(11) NOT NULL,
		  `parent` int(11) NOT NULL,
		  `supportstaff` int(11) NOT NULL,
		  PRIMARY KEY (`comman_access_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
		
		$insert ="INSERT INTO `comman_access` (`comman_action`, `student`, `teacher`, `parent`, `supportstaff`) VALUES
				('account', 1, 1, 1, 1),
				('addbeds', 0, 0, 0, 0),
				('addevent', 0, 0, 0, 0),
				('addexam', 0, 1, 0, 0),
				('addexpense', 0, 0, 0, 1),
				('addexport', 0, 1, 0, 1),
				('addhomework', 0, 1, 0, 0),
				('addhostel', 0, 0, 0, 0),
				('addimport', 0, 1, 0, 1),
				('addincome', 0, 0, 0, 1),
				('addmarks', 0, 1, 0, 1),
				('addmultiplemark', 0, 1, 0, 1),
				('addpayment', 0, 0, 0, 1),
				('addroom', 0, 0, 0, 0),
				('addstudent', 0, 0, 0, 1),
				('addsubject', 0, 1, 0, 0),
				('assignroom', 1, 1, 1, 1),
				('bedslist', 1, 1, 1, 1),
				('booklist', 1, 1, 0, 1),
				('childlist', 0, 0, 1, 0),
				('classroutelist', 1, 1, 1, 0),
				('eventlist', 1, 1, 1, 1),
				('examlist', 1, 1, 1, 0),
				('expenselist', 0, 0, 0, 1),
				('expensepdf', 1, 1, 1, 1),
				('exploremark', 0, 1, 0, 1),
				('exportlist', 0, 1, 0, 1),
				('feelist', 1, 0, 1, 1),
				('holidaylist', 1, 1, 1, 1),
				('homeworklist', 1, 1, 1, 0),
				('hostellist', 1, 1, 1, 1),
				('importedit', 0, 1, 0, 1),
				('importlist', 0, 1, 0, 1),
				('incomelist', 0, 0, 0, 1),
				('incomepdf', 1, 1, 1, 1),
				('memberlist', 1, 1, 0, 1),
				('newslist', 1, 1, 1, 1),
				('noticelist', 1, 1, 1, 1),
				('parentlist', 1, 0, 1, 0),
				('paymentlist', 1, 0, 1, 1),
				('paymentpdf', 1, 1, 1, 1),
				('readfile', 1, 1, 1, 1),
				('roomlist', 1, 1, 1, 1),
				('studaddsubmission', 1, 0, 0, 0),
				('studentattendance', 0, 0, 1, 0),
				('studentlist', 1, 1, 1, 1),
				('studentresultpdf', 1, 1, 1, 1),
				('studentsubjectattendance', 0, 0, 1, 0),
				('subjectlist', 1, 1, 0, 0),
				('teacherlist', 1, 1, 1, 1),
				('teacherroutelist', 0, 1, 0, 0),
				('transportlist', 1, 1, 1, 1),
				('updateexam', 0, 1, 0, 0),
				('updateparent', 0, 0, 1, 0),
				('updatestudent', 1, 0, 0, 1),
				('updatesubject', 0, 1, 0, 0),
				('updateteacher', 0, 1, 0, 0),
				('viewdataexpense', 0, 0, 0, 0),
				('viewdataincome', 0, 0, 0, 0),
				('viewdatapayment', 0, 0, 0, 0),
				('viewsubmission', 0, 1, 0, 0),
				('addpersonal', 1, 1, 1, 1),
				('changepassword', 1, 1, 1, 1),
				('studentresultprint', 1, 1, 1, 1),
				('delete', 0, 1, 0, 1),
				('examdelete', 0, 1, 0, 0),
				('parentchild', 1, 0, 1, 0),
				('paymentdelete', 0, 0, 0, 1),
				('deleteincome', 0, 0, 0, 1),
				('deleteexpense', 0, 0, 0, 1),
				('updatestaff', 0, 1, 0, 1),
				('stafflist', 0, 1, 0, 1),
				('studentexamlist', 1, 1, 1, 0),
				('studentreceipt', 1, 1, 1, 0),
				('studentreceiptpdf', 1, 1, 1, 0),
				('examtimetable', 0, 1, 0, 0),
				('studentexamhall', 0, 1, 0, 0),
				('examhallreceipt', 0, 1, 0, 0),
				('viewexamtimetable', 1, 1, 0, 0)";
		
		$stmt = $conn->execute($sql);		
		$stmt = $conn->execute($insert);
		
		$sql="CREATE TABLE IF NOT EXISTS `tblteachermenu` (
		  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
		  `menu_name` varchar(30) NOT NULL,
		  `controller_name` varchar(30) NOT NULL,
		  `action_name` varchar(30) NOT NULL,
		  `icon` varchar(30) NOT NULL,
		  `teacher_approve` int(3) NOT NULL,
		  `student_approve` int(11) NOT NULL,
		  `staff_approve` int(11) NOT NULL,
		  `parent_approve` int(11) NOT NULL,
		  PRIMARY KEY (`menu_id`) KEY_BLOCK_SIZE=11
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
	
		$path = $this->request->base;		
		$insert ="INSERT INTO `tblteachermenu` (`menu_name`, `controller_name`, `action_name`, `icon`, `teacher_approve`, `student_approve`, `staff_approve`, `parent_approve`) VALUES
			('Teacher', 'comman', 'teacherlist', 'teacher.png', 1, 1, 1, 1),
			('Student', 'comman', 'studentlist', 'student-icon.png', 1, 1, 1, 1),
			('Parent', 'comman', 'parentlist', 'parents.png', 0, 1, 0, 1),
			('Support Staff', 'comman', 'stafflist', 'staff.png', 1, 0, 1, 0),
			('Child', 'comman', 'childlist', 'student-icon.png', 0, 0, 0, 1),
			('Subject', 'comman', 'subjectlist', 'subject.png', 1, 1, 0, 0),
			('Class Routine', 'comman', 'classroutelist', 'class-route.png', 1, 1, 0, 1),
			('Attendance', 'attendance', 'attendance', 'attandance.png', 1, 0, 0, 0),
			('Exam', 'comman', 'examlist', 'exam.png', 1, 1, 0, 1),			
			('Manage Marks', 'comman', 'addmarks', 'mark-manage.png', 1, 0, 1, 0),
			('Message', 'message', 'inbox', 'message.png', 1, 1, 1, 1),
			('Notice Board', 'comman', 'noticelist', 'notice.png', 1, 1, 1, 1),
			('News', 'comman', 'newslist', 'news.png', 1, 1, 1, 1),
			('Event', 'comman', 'eventlist', 'event.png', 1, 1, 1, 1),			
			('Holiday', 'comman', 'holidaylist', 'holiday.png', 1, 1, 1, 1),
			('Homework', 'comman', 'homeworklist', 'homework.png', 1, 1, 0, 1),
			('Hostel', 'comman', 'hostellist', 'hostel.png', 1, 1, 1, 1),
			('Exam Hall', 'comman', 'examhallreceipt', 'hall.png', 1, 0, 0, 0),
			('Transport', 'comman', 'transportlist', 'transport.png', 1, 1, 1, 1),
			('Feepayment', 'comman', 'feelist', 'fee.png', 0, 1, 1, 1),
			('Payment', 'comman', 'paymentlist', 'payment.png', 0, 1, 1, 1),
			('Library', 'comman', 'memberlist', 'library.png', 1, 1, 1, 0),
			('Import/Export', 'comman', 'exportlist', 'import-export.png', 1, 0, 1, 0),
			('Report', 'report', 'failed', 'report.png', 1, 0, 1, 0),
			('Account', 'comman', 'account', 'account.png', 1, 1, 1, 1)";
							
		$stmt = $conn->execute($sql);		
		$stmt = $conn->execute($insert);	
			
		$sql="CREATE TABLE IF NOT EXISTS `smgt_fees` (
		  `fees_id` int(11) NOT NULL AUTO_INCREMENT,
		  `fees_title_id` int(11) NOT NULL,
		  `class_id` int(11) NOT NULL,
		  `fees_amount` float NOT NULL,
		  `description` text NOT NULL,
		  `created_date` datetime NOT NULL,
		  `created_by` int(11) NOT NULL,
		  `section` varchar(255) DEFAULT NULL,
		  PRIMARY KEY (`fees_id`),
		  KEY `fees_title_id` (`fees_title_id`),
		  KEY `class_id` (`class_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
				
		$stmt = $conn->execute($sql);
		
		$sql="CREATE TABLE IF NOT EXISTS `smgt_fees_payment` (
		  `fees_pay_id` int(11) NOT NULL AUTO_INCREMENT,
		  `class_id` int(11) NOT NULL,
		  `student_id` int(11) NOT NULL,
		  `fees_id` int(11) NOT NULL,
		  `total_amount` int(11) NOT NULL,
		  `fees_paid_amount` int(11) DEFAULT NULL,
		  `payment_status` tinyint(4) DEFAULT NULL,
		  `description` text NOT NULL,
		  `start_year` varchar(20) NOT NULL,
		  `end_year` varchar(20) NOT NULL,
		  `paid_by_date` date DEFAULT NULL,
		  `created_date` datetime NOT NULL,
		  `created_by` bigint(20) NOT NULL,
		  `section` varchar(255) DEFAULT NULL,
		  PRIMARY KEY (`fees_pay_id`),
		  KEY `class_id` (`class_id`),
		  KEY `student_id` (`student_id`),
		  KEY `fees_id` (`fees_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
		
		$stmt = $conn->execute($sql);
				
		$sql="CREATE TABLE IF NOT EXISTS `smgt_fee_payment_history` (
		  `payment_history_id` bigint(20) NOT NULL AUTO_INCREMENT,
		  `fees_pay_id` int(11) NOT NULL,
		  `amount` int(11) NOT NULL,
		  `payment_method` varchar(50) NOT NULL,
		  `paid_by_date` date NOT NULL,
		  `created_by` bigint(20) NOT NULL,
		  `trasaction_id` varchar(50) NOT NULL,
		  PRIMARY KEY (`payment_history_id`),
		  KEY `fees_pay_id` (`fees_pay_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);
				
		$sql="CREATE TABLE IF NOT EXISTS `smgt_grade` (
		  `grade_id` int(11) NOT NULL AUTO_INCREMENT,
		  `grade_name` varchar(20) NOT NULL,
		  `grade_point` float NOT NULL,
		  `mark_from` tinyint(3) NOT NULL,
		  `mark_upto` tinyint(3) NOT NULL,
		  `grade_comment` text NOT NULL,
		  `created_date` datetime NOT NULL,
		  `creater_id` int(11) DEFAULT NULL,
		  PRIMARY KEY (`grade_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
		
		$stmt = $conn->execute($sql);
				
		$sql="CREATE TABLE IF NOT EXISTS `smgt_hall` (
		  `hall_id` int(11) NOT NULL AUTO_INCREMENT,
		  `hall_name` varchar(200) NOT NULL,
		  `number_of_hall` bigint(20) NOT NULL,
		  `hall_capacity` bigint(20) NOT NULL,
		  `description` text NOT NULL,
		  `date` datetime NOT NULL,
		  PRIMARY KEY (`hall_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
		
		$stmt = $conn->execute($sql);
				
		$sql="CREATE TABLE IF NOT EXISTS `smgt_holiday` (
		  `holiday_id` int(11) NOT NULL AUTO_INCREMENT,
		  `holiday_title` varchar(200) NOT NULL,
		  `description` text NOT NULL,
		  `date` date NOT NULL,
		  `end_date` date NOT NULL,
		  `created_by` int(11) NOT NULL,
		  PRIMARY KEY (`holiday_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
				
		$stmt = $conn->execute($sql);		
				
		$sql="CREATE TABLE IF NOT EXISTS `smgt_income_expense` (
		  `income_id` int(11) NOT NULL AUTO_INCREMENT,
		  `invoice_type` varchar(50) NOT NULL,
		  `class_id` int(11) DEFAULT NULL,
		  `supplier_name` varchar(100) NOT NULL,
		  `entry` text NOT NULL,
		  `payment_status` varchar(50) NOT NULL,
		  `create_by` int(11) NOT NULL,
		  `income_create_date` date NOT NULL,
		  `section` int(11) DEFAULT NULL,
		  PRIMARY KEY (`income_id`),
		  KEY `class_id` (`class_id`),
		  KEY `section` (`section`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
		
		$stmt = $conn->execute($sql);
				
		$sql="CREATE TABLE IF NOT EXISTS `smgt_library_book` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `ISBN` varchar(50) NOT NULL,
		  `book_name` varchar(200) NOT NULL,
		  `author_name` varchar(100) NOT NULL,
		  `cat_id` int(11) NOT NULL,
		  `rack_location` int(11) NOT NULL,
		  `price` varchar(10) NOT NULL,
		  `quantity` int(11) NOT NULL,
		  `description` text NOT NULL,
		  `added_by` int(11) NOT NULL,
		  `added_date` varchar(20) NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `cat_id` (`cat_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
		
		$stmt = $conn->execute($sql);
						
		$sql="CREATE TABLE IF NOT EXISTS `smgt_library_book_issue` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `class_id` int(11) NOT NULL,
		  `student_id` int(11) NOT NULL,
		  `cat_id` int(11) NOT NULL,
		  `book_id` int(50) NOT NULL,
		  `issue_date` date NOT NULL,
		  `end_date` date NOT NULL,
		  `actual_return_date` varchar(20) DEFAULT NULL,
		  `period` int(11) NOT NULL,
		  `fine` varchar(20) DEFAULT NULL,
		  `status` varchar(50) NOT NULL,
		  `issue_by` int(11) NOT NULL,
		  `section` int(11) DEFAULT NULL,
		  PRIMARY KEY (`id`),
		  KEY `class_id` (`class_id`,`student_id`,`cat_id`,`book_id`,`section`),
		  KEY `student_id` (`student_id`,`cat_id`,`book_id`,`section`),
		  KEY `book_id` (`book_id`),
		  KEY `section` (`section`),
		  KEY `cat_id` (`cat_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);
		
		$sql="CREATE TABLE IF NOT EXISTS `smgt_marks` (
		  `mark_id` bigint(20) NOT NULL AUTO_INCREMENT,
		  `exam_id` int(11) NOT NULL,
		  `class_id` int(11) NOT NULL,
		  `subject_id` int(11) NOT NULL,
		  `marks` int(11) NOT NULL,
		  `attendance` tinyint(4) NOT NULL,
		  `grade_id` int(11) NOT NULL,
		  `student_id` int(11) NOT NULL,
		  `marks_comment` text NOT NULL,
		  `created_date` datetime NOT NULL,
		  `modified_date` datetime NOT NULL,
		  `created_by` int(11) NOT NULL,
		  `section` int(11) DEFAULT NULL,
		  PRIMARY KEY (`mark_id`),
		  KEY `exam_id` (`exam_id`),
		  KEY `class_id` (`class_id`),
		  KEY `subject_id` (`subject_id`),
		  KEY `grade_id` (`grade_id`),
		  KEY `student_id` (`student_id`),
		  KEY `section` (`section`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";			
				
		$stmt = $conn->execute($sql);	
				
		$sql="CREATE TABLE IF NOT EXISTS `smgt_message_reciver` (
		  `smgt_reciver_id` int(11) NOT NULL AUTO_INCREMENT,
		  `message_id` int(11) NOT NULL,
		  `reciver_id` int(11) NOT NULL,
		  `sent_id` int(11) NOT NULL,
		  `status` int(11) NOT NULL,
		  `date` datetime NOT NULL,
		  PRIMARY KEY (`smgt_reciver_id`),
		  KEY `message_id` (`message_id`),
		  KEY `reciver_id` (`reciver_id`),
		  KEY `sent_id` (`sent_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";

		$stmt = $conn->execute($sql);				
				
		$sql="CREATE TABLE IF NOT EXISTS `smgt_message_replies` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `smgt_reciver_id` int(11) NOT NULL,
		  `message_id` int(11) NOT NULL,
		  `sender_id` int(11) NOT NULL,
		  `receiver_id` int(11) NOT NULL,
		  `message_comment` text NOT NULL,
		  `created_date` datetime NOT NULL,
		  `status` int(11) NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `smgt_reciver_id` (`smgt_reciver_id`),
		  KEY `message_id` (`message_id`),
		  KEY `sender_id` (`sender_id`),
		  KEY `receiver_id` (`receiver_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
		
		$stmt = $conn->execute($sql);		
				
		$sql="CREATE TABLE IF NOT EXISTS `smgt_message_sent` (
		  `message_id` int(11) NOT NULL AUTO_INCREMENT,
		  `sender_id` int(11) NOT NULL,
		  `message_for` varchar(20) NOT NULL,
		  `class_id` varchar(10) NOT NULL,
		  `date` datetime NOT NULL,
		  `subject` varchar(150) NOT NULL,
		  `message_body` text NOT NULL,
		  `deleted` int(11) NOT NULL,
		  `section` int(11) DEFAULT NULL,
		  PRIMARY KEY (`message_id`),
		  KEY `sender_id` (`sender_id`),
		  KEY `section` (`section`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);		
				
		$sql="CREATE TABLE IF NOT EXISTS `smgt_notice` (
		  `notice_id` int(11) NOT NULL AUTO_INCREMENT,
		  `notice_title` varchar(100) NOT NULL,
		  `notice_comment` text NOT NULL,
		  `notice_start_date` date NOT NULL,
		  `notice_end_date` date NOT NULL,
		  `notice_for` varchar(15) NOT NULL,
		  `which_class` varchar(15) NOT NULL,
		  `section` int(11) DEFAULT NULL,
		  PRIMARY KEY (`notice_id`) KEY_BLOCK_SIZE=11,
		  KEY `section` (`section`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";

		$stmt = $conn->execute($sql);	
				
		$sql="CREATE TABLE IF NOT EXISTS `smgt_payment` (
		  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) NOT NULL,
		  `class_id` int(11) NOT NULL,
		  `c_name` varchar(30) NOT NULL,
		  `payment_title` varchar(100) NOT NULL,
		  `description` text NOT NULL,
		  `amount` int(11) NOT NULL,
		  `payment_status` varchar(10) NOT NULL,
		  `date` datetime DEFAULT CURRENT_TIMESTAMP,
		  `payment_reciever_id` int(11) NOT NULL,
		  `section` int(11) DEFAULT NULL,
		  PRIMARY KEY (`payment_id`),
		  KEY `user_id` (`user_id`),
		  KEY `class_id` (`class_id`),
		  KEY `payment_reciever_id` (`payment_reciever_id`),
		  KEY `section` (`section`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);			
				
		$sql="CREATE TABLE IF NOT EXISTS `smgt_setting` (
		  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
		  `field_name` text NOT NULL,
		  `field_value` text NOT NULL,
		  PRIMARY KEY (`setting_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";

		$stmt = $conn->execute($sql);						
				
		$sql="CREATE TABLE IF NOT EXISTS `smgt_subject` (
		  `subid` int(11) NOT NULL AUTO_INCREMENT,
		  `sub_name` varchar(255) NOT NULL,
		  `teacher_id` int(11) NOT NULL,
		  `class_id` int(11) NOT NULL,
		  `author_name` varchar(255) NOT NULL,
		  `edition` varchar(255) NOT NULL,
		  `syllabus` varchar(255) DEFAULT NULL,
		  `section` int(11) NOT NULL,
		  `sub_code` varchar(50) NOT NULL,
		  PRIMARY KEY (`subid`),
		  KEY `teacher_id` (`teacher_id`),
		  KEY `class_id` (`class_id`),
		  KEY `section` (`section`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);								
				
		$sql="CREATE TABLE IF NOT EXISTS `smgt_sub_attendance` (
		  `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) NOT NULL,
		  `class_id` int(11) NOT NULL,
		  `sub_id` int(11) NOT NULL,
		  `attend_by` int(11) NOT NULL,
		  `attendance_date` date NOT NULL,
		  `status` varchar(50) NOT NULL,
		  `role_name` varchar(50) NOT NULL,
		  `comment` text NOT NULL,
		  `section` int(11) DEFAULT NULL,
		  PRIMARY KEY (`attendance_id`),
		  KEY `user_id` (`user_id`),
		  KEY `class_id` (`class_id`),
		  KEY `sub_id` (`sub_id`),
		  KEY `section` (`section`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);	
						
		$sql="CREATE TABLE IF NOT EXISTS `smgt_time_table` (
		  `route_id` int(11) NOT NULL AUTO_INCREMENT,
		  `subject_id` int(11) NOT NULL,
		  `teacher_id` int(11) NOT NULL,
		  `class_id` int(11) NOT NULL,
		  `start_time` varchar(10) NOT NULL,
		  `end_time` varchar(10) NOT NULL,
		  `weekday` varchar(20) NOT NULL,
		  `section` int(11) NOT NULL,
		  PRIMARY KEY (`route_id`),
		  KEY `subject_id` (`subject_id`),
		  KEY `teacher_id` (`teacher_id`),
		  KEY `class_id` (`class_id`),
		  KEY `section` (`section`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";

		$stmt = $conn->execute($sql);		
				
		$sql="CREATE TABLE IF NOT EXISTS `smgt_transport` (
		  `transport_id` int(11) NOT NULL AUTO_INCREMENT,
		  `route_name` varchar(30) NOT NULL,
		  `vehicle_identifier` int(10) NOT NULL,
		  `vehicle_registration_number` varchar(30) NOT NULL,
		  `driver_name` varchar(100) NOT NULL,
		  `driver_phone_number` varchar(15) NOT NULL,
		  `driver_address` text NOT NULL,
		  `image` varchar(100) NOT NULL,
		  `description` text NOT NULL,
		  `route_fare` int(6) NOT NULL,
		  PRIMARY KEY (`transport_id`) KEY_BLOCK_SIZE=11
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
		
		$stmt = $conn->execute($sql);	

		$sql="CREATE TABLE IF NOT EXISTS `smgt_homework` (
		  `homework_id` int(11) NOT NULL AUTO_INCREMENT,
		  `title` varchar(100) NOT NULL,
		  `class_id` int(11) NOT NULL,
		  `section_id` int(11) NOT NULL,
		  `subject_id` int(11) NOT NULL,
		  `content` text NOT NULL,
		  `syllabus` text NOT NULL,
		  `created_date` date NOT NULL,
		  `submission_date` date NOT NULL,
		  `created_by` bigint(20) NOT NULL,
		  PRIMARY KEY (`homework_id`),
		  KEY `class_id` (`class_id`),
		  KEY `section_id` (`section_id`),
		  KEY `subject_id` (`subject_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
		
		$stmt = $conn->execute($sql);

		$sql="CREATE TABLE IF NOT EXISTS `smgt_student_homework` (
		  `stu_homework_id` int(50) NOT NULL AUTO_INCREMENT,
		  `homework_id` int(50) NOT NULL,
		  `student_id` int(50) NOT NULL,
		  `status` int(10) NOT NULL,
		  `uploaded_date` date NOT NULL,
		  `file` varchar(255) NOT NULL,
		  PRIMARY KEY (`stu_homework_id`),
		  KEY `homework_id` (`homework_id`),
		  KEY `student_id` (`student_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
		
		$stmt = $conn->execute($sql);
				
		$sql="CREATE TABLE IF NOT EXISTS `teacher_access_rights` (
		  `teacheraccess_id` int(11) NOT NULL AUTO_INCREMENT,
		  `chksub` varchar(100) NOT NULL,
		  `chkstud` varchar(100) NOT NULL,
		  `chkatted` varchar(100) NOT NULL,
		  `modify_date` datetime NOT NULL,
		  `modify_by` bigint(20) NOT NULL,
		  PRIMARY KEY (`teacheraccess_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
		
		$curr_date = date("Y-m-d");
		
		$insert = "INSERT INTO `teacher_access_rights` (`chksub`, `chkstud`, `chkatted`, `modify_date`, `modify_by`) VALUES
		('own_sub', 'own_cls_stud', 'all_stud_attend', '{$curr_date}', 1)";
		
		$stmt = $conn->execute($sql);	
		$stmt = $conn->execute($insert);		
				
		$sql="CREATE TABLE IF NOT EXISTS `uploadcsv` (
		  `uploadcsv_id` int(11) NOT NULL AUTO_INCREMENT,
		  `class_name` varchar(20) NOT NULL,
		  `file` text NOT NULL,
		  PRIMARY KEY (`uploadcsv_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1";

		$stmt = $conn->execute($sql);
		
		$sql="CREATE TABLE IF NOT EXISTS `exam_time_table` (
		  `exam_time_table_id` int(11) NOT NULL AUTO_INCREMENT,
		  `exam_id` int(11) NOT NULL,
		  `subject_id` int(11) NOT NULL,
		  `exam_date` date NOT NULL,
		  `start_time` varchar(10) NOT NULL,
		  `end_time` varchar(10) NOT NULL,
		  `created_date` date NOT NULL,
		  `created_by` int(11) NOT NULL,
		  PRIMARY KEY (`exam_time_table_id`),
		  KEY `exam_id` (`exam_id`),
		  KEY `subject_id` (`subject_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";

		$stmt = $conn->execute($sql);
		
		$sql="CREATE TABLE IF NOT EXISTS `tbl_term` (
		  `term_id` int(11) NOT NULL AUTO_INCREMENT,
		  `term_name` varchar(50) NOT NULL,
		  `term_status` int(11) NOT NULL,
		  `created_by` bigint(20) NOT NULL,
		  PRIMARY KEY (`term_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";

		$stmt = $conn->execute($sql);
		
		$sql="CREATE TABLE IF NOT EXISTS `smgt_admission_main` (
		  `adminssion_main_id` int(11) NOT NULL AUTO_INCREMENT,
		  `title` varchar(500) NOT NULL,
		  `meta_key` varchar(200) NOT NULL,
		  `is_active` int(11) NOT NULL,
		  `created_date` date NOT NULL,
		  PRIMARY KEY (`adminssion_main_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";

		$stmt = $conn->execute($sql);
		
		$sql="CREATE TABLE IF NOT EXISTS `smgt_emailtemplate` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `find_by` varchar(100) NOT NULL,
		  `name` varchar(255) NOT NULL,
		  `subject` varchar(255) NOT NULL,
		  `template` longtext NOT NULL,
		  `keywords` longtext NOT NULL,
		  `created_date` datetime NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";
		
		$insert = "
		INSERT INTO `smgt_emailtemplate` (`find_by`, `name`, `subject`, `template`, `keywords`, `created_date`) VALUES
		('registration', 'Registration Email Template', 'Student Registration', 'Hello {{student_name}} ,\r\n\r\nYour registration has been successful with {{school_name}}. You Will be able to access your account after school admin approves it.\r\n\r\nUser Name : {{user_name}} , \r\nClass Name : {{class_name}}, \r\nEmail : {{email}}\r\n\r\n\r\nThank you\r\n{{school_name}}.', 'a:5:{s:16:\"{{student_name}}\";s:59:\"The student full name or login name (whatever is available)\";s:13:\"{{user_name}}\";s:20:\"User name of student\";s:14:\"{{class_name}}\";s:21:\"Class name of student\";s:9:\"{{email}}\";s:16:\"Email of student\";s:15:\"{{school_name}}\";s:12:\" School name\";}', '2018-04-30 12:00:00'),
		('Student_Approved', 'Student Activation Mail Template', 'Student Approved', 'Hello {{student_name}},\r\n\r\n                 Your account with {{school_name}} is approved. You can access student account using your login details. You can login using this link. {{login_link}}. Your other details are given bellow.\r\n\r\nclass name : {{class_name}}\r\nRoll Number : {{roll_number}}\r\nFee Amount : {{fee_amount}} \r\n\r\nThanks ,\r\n{{school_name}}', 'a:6:{s:16:\"{{student_name}}\";s:59:\"The student full name or login name (whatever is available)\";s:15:\"{{school_name}}\";s:11:\"School name\";s:14:\"{{class_name}}\";s:18:\"Student class name\";s:15:\"{{roll_number}}\";s:19:\"Student Roll Number\";s:14:\"{{fee_amount}}\";s:24:\"Student Total Fee Amount\";s:14:\"{{login_link}}\";s:18:\"Student Login link\";}', '2018-04-30 12:00:00'),
		('Add_User', 'Add User', 'Your have been assigned {{role}} of Role Name in {{school_name}}.', 'Dear {{user_name}},\r\n\r\n         You are Added by admin in {{school_name}} system. Your have been assigned role of {{role}} in {{school_name}}.  You can login using this link. {{login_link}}\r\n\r\nUserName : {{username}}\r\nPassword : {{Password}}\r\n\r\nRegards From \r\n{{school_name}}.', 'a:6:{s:13:\"{{user_name}}\";s:21:\"The student full name\";s:15:\"{{school_name}}\";s:12:\" School name\";s:8:\"{{role}}\";s:19:\"Student roll number\";s:14:\"{{login_link}}\";s:18:\"Student login link\";s:12:\"{{username}}\";s:16:\"Student username\";s:12:\"{{password}}\";s:16:\"Student password\";}', '2018-04-30 12:00:00'),
		('Student Assign to Teacher mail template', 'Student Assign to Teacher mail template', 'New Student Assigned', 'Dear {{teacher_name}},\r\n\r\n         New Student {{student_name}} has been assigned to you.\r\n \r\nRegards From \r\n{{school_name}}.', 'a:3:{s:16:\"{{student_name}}\";s:21:\"The student full name\";s:15:\"{{school_name}}\";s:12:\" School name\";s:16:\"{{teacher_name}}\";s:16:\"The Teacher name\";}', '2018-04-30 12:00:00'),
		('Message Received', 'Message Received', 'You have received new message from {{from_mail}} at {{school_name}}', 'Dear {{receiver_name}},\r\n\r\n         You have received new message from {{message_content}}\".\r\n \r\nRegards From \r\n{{school_name}}.', 'a:4:{s:13:\"{{from_mail}}\";s:19:\"Message sender name\";s:15:\"{{school_name}}\";s:11:\"School name\";s:17:\"{{receiver_name}}\";s:20:\"Message Receive Name\";s:19:\"{{message_content}}\";s:15:\"Message Content\";}', '2018-04-30 12:00:00'),
		('Attendance Absent Notification', 'Attendance Absent Notification', 'Your Child {{child_name}} is absent today', 'Your Child {{child_name}} is absent {{attendance_date}}', 'a:2:{s:15:\"{{child_name}} \";s:19:\"Enter name of child\";s:20:\"{{attendance_date}} \";s:15:\"Attendance Date\";}', '2018-04-30 12:00:00'),
		('Student Assigned to Teacher Student mail template', 'Student Assigned to Teacher Student mail template', 'You have been Assigned {{teacher_name}} at {{school_name}}', 'Dear {{student_name}},\r\n\r\n         You are assigned to  {{teacher_name}}. {{teacher_name}} belongs to {{class_name}}.\r\n \r\nRegards From {{school_name}}.', 'a:4:{s:16:\"{{teacher_name}}\";s:18:\"Enter teacher name\";s:15:\"{{school_name}}\";s:17:\"Enter school name\";s:16:\"{{student_name}}\";s:18:\"Enter student name\";s:14:\"{{class_name}}\";s:16:\"Enter Class name\";}', '2018-04-30 12:00:00'),
		('Notice', 'Notice', 'New notice for you', 'New notice for you\n\nNotice Title : {{notice_title}}\n\nNotice Start Date  : {{notice_start_date}}\n\nNotice End Date  : {{notice_end_date}}\n\nNotice For  : {{notice_for}}\n\nNotice Comment :  {{notice_comment}}\n\nRegards From {{school_name}}\n', 'a:5:{s:16:\"{{notice_title}}\";s:18:\"Enter notice title\";s:21:\"{{notice_start_date}}\";s:23:\"Enter notice start date\";s:19:\"{{notice_end_date}}\";s:21:\"Enter notice end date\";s:14:\"{{notice_for}}\";s:26:\"Enter role name for notice\";s:18:\"{{notice_comment}}\";s:20:\"Enter notice comment\";}', '2018-04-30 12:00:00'),
		('Event', 'Event', 'New event for you', 'New event for you\r\n\r\nEvent Title : {{event_title}}\r\n\r\nEvent Start Date  : {{event_start_date}}\r\n\r\nEvent End Date  : {{event_end_date}}\r\n\r\nEvent For  : {{event_for}}\r\n\r\nEvent Comment :  {{event_comment}}\r\n\r\nRegards From {{school_name}}\r\n', 'a:5:{s:15:\"{{event_title}}\";s:17:\"Enter event title\";s:20:\"{{event_start_date}}\";s:22:\"Enter event start date\";s:18:\"{{event_end_date}}\";s:20:\"Enter event end date\";s:13:\"{{event_for}}\";s:25:\"Enter role name for event\";s:17:\"{{event_comment}}\";s:19:\"Enter event comment\";}', '2018-04-30 12:00:00'),
		('Holiday', 'Holiday', 'Holiday Announcement', 'Holiday Announcement\r\n\r\nHoliday Title : {{holiday_title}}\r\n\r\nHoliday Date {{holiday_date}}\r\n\r\nRegards From \r\n{{school_name}}\r\n', 'a:2:{s:17:\"{{holiday_title}}\";s:19:\"Enter holiday title\";s:16:\"{{holiday_date}}\";s:18:\"Enter holiday date\";}', '2018-04-30 12:00:00'),
		('HomeWork Mail Template', 'HomeWork Mail Template', 'New Homework Assigned', 'Dear {{parent_name}} New homework has been assign to you/your child\n\nStudent name : {{student_name}} \nHomework Title : {{title}}\nSubmission Date : {{submission_date}}\n\nThanks.\n {{school_name}}\n', 'a:4:{s:16:\"{{student_name}}\";s:59:\"The student full name or login name (whatever is available)\";s:9:\"{{title}}\";s:22:\"Student homework title\";s:19:\"{{submission_date}}\";s:15:\"Submission Date\";s:15:\"{{school_name}}\";s:11:\"School name\";}', '2018-04-30 12:00:00')";
		
		$stmt = $conn->execute($sql);
		$stmt = $conn->execute($insert);
		
		$sql="CREATE TABLE IF NOT EXISTS `smgt_users` (
		  `user_id` int(11) NOT NULL AUTO_INCREMENT,
		  `classname` int(11) DEFAULT NULL,
		  `roll_no` int(11) DEFAULT NULL,
		  `first_name` varchar(20) NOT NULL,
		  `middle_name` varchar(20) NOT NULL,
		  `last_name` varchar(20) NOT NULL,
		  `gender` varchar(6) NOT NULL,
		  `date_of_birth` date NOT NULL,
		  `address` text NOT NULL,
		  `city` varchar(20) NOT NULL,
		  `state` varchar(20) NOT NULL,
		  `zip_code` int(11) NOT NULL,
		  `mobile_no` varchar(20) NOT NULL,
		  `alternate_mobile_no` varchar(20) DEFAULT NULL,
		  `phone` decimal(10,0) NOT NULL,
		  `email` varchar(50) NOT NULL,
		  `username` varchar(50) NOT NULL,
		  `password` varchar(1000) NOT NULL,
		  `image` text NOT NULL,
		  `working_hour` varchar(20) DEFAULT NULL,
		  `position` varchar(20) DEFAULT NULL,
		  `submitted_document` text,
		  `relation` varchar(20) DEFAULT NULL,
		  `role` varchar(20) NOT NULL,
		  `status` varchar(15) DEFAULT NULL,
		  `classsection` int(11) DEFAULT NULL,
		  `docume` varchar(255) DEFAULT NULL,
		  `created_date` date NOT NULL,
		  `studentID` varchar(50) NOT NULL,
		  `studentID_prefix` varchar(50) NOT NULL,
		  `is_deactive` int(11) NOT NULL,
		  `exam_hall_receipt` int(11) NOT NULL,
		  PRIMARY KEY (`user_id`),
		  UNIQUE KEY `username` (`username`),
		  UNIQUE KEY `email` (`email`),
		  KEY `classname` (`classname`),
		  KEY `classsection` (`classsection`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1";

		$username = $this->request->data["lg_username"];
		$password = $this->request->data["confirm"];

		$hasher = new DefaultPasswordHasher();
		$password = $hasher->hash($password);
		$curr_date = date("Y-m-d");
		
		$insert = "
		INSERT INTO `smgt_users` 
		(`classname`, `roll_no`, `first_name`, `middle_name`, `last_name`, `gender`, `date_of_birth`, `address`, `city`, `state`, `zip_code`, `mobile_no`, `alternate_mobile_no`, `phone`, `email`, `username`, `password`, `image`, `working_hour`, `position`, `submitted_document`, `relation`, `role`, `status`, `classsection`, `docume`,`created_date`,`studentID`,`studentID_prefix`,`is_deactive`,`exam_hall_receipt`) 
		VALUES
		(0, 0, 'admin', 'a', 'admin', 'male', '1995-03-05', 'Gandhinagar', 'gandhinagar', 'gujarat', 382051, '9426900000', '9426900000', '723975808', 'admin@gmail.com', '{$username}', '{$password}', 'profile.jpg', '', '', '', '', 'admin', '', 0, '','{$curr_date}','','',0,0)";
		
		$stmt = $conn->execute($sql);	
		$stmt = $conn->execute($insert);		
		
		/* Relation of Tables */
		$sql = "ALTER TABLE `child_tbl`
			ADD CONSTRAINT `fk_child_id` FOREIGN KEY (`child_id`) REFERENCES `smgt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_child_parent_id` FOREIGN KEY (`child_parent_id`) REFERENCES `smgt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `class_section`
			ADD CONSTRAINT `fk_class_id` FOREIGN KEY (`class_id`) REFERENCES `classmgt` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `exam_time_table`
			ADD CONSTRAINT `fk_subject_id` FOREIGN KEY (`subject_id`) REFERENCES `smgt_subject` (`subid`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_exam_id` FOREIGN KEY (`exam_id`) REFERENCES `smgt_exam` (`exam_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_add_beds`
			ADD CONSTRAINT `fk_room_unique_id` FOREIGN KEY (`room_unique_id`) REFERENCES `smgt_hostel_room` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_assign_bed_new`
			ADD CONSTRAINT `fk_student_id` FOREIGN KEY (`student_id`) REFERENCES `smgt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_roomunique_id` FOREIGN KEY (`room_unique_id`) REFERENCES `smgt_hostel_room` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_attendence`
			ADD CONSTRAINT `fk_attend_user_id` FOREIGN KEY (`user_id`) REFERENCES `smgt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_attend_by` FOREIGN KEY (`attend_by`) REFERENCES `smgt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_exam`
			ADD CONSTRAINT `fk_exam_term_id` FOREIGN KEY (`term_id`) REFERENCES `tbl_term` (`term_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_exam_class_id` FOREIGN KEY (`class_id`) REFERENCES `classmgt` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_exam_section_id` FOREIGN KEY (`section_id`) REFERENCES `class_section` (`class_section_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_exam_hall_receipt`
			ADD CONSTRAINT `fk_examhall_id` FOREIGN KEY (`hall_id`) REFERENCES `smgt_hall` (`hall_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_exam_hall_id` FOREIGN KEY (`exam_id`) REFERENCES `smgt_exam` (`exam_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_exam_hall_user_id` FOREIGN KEY (`user_id`) REFERENCES `smgt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_fees`
			ADD CONSTRAINT `fk_fee_cate_id` FOREIGN KEY (`fees_title_id`) REFERENCES `smgt_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_fee_class_id` FOREIGN KEY (`class_id`) REFERENCES `classmgt` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_fees_payment`
			ADD CONSTRAINT `fk_fees_payment_id` FOREIGN KEY (`class_id`) REFERENCES `classmgt` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_fee_payment_id` FOREIGN KEY (`fees_id`) REFERENCES `smgt_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_fee_payment_student_id` FOREIGN KEY (`student_id`) REFERENCES `smgt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_fee_payment_history`
			ADD CONSTRAINT `fk_fees_pay_id` FOREIGN KEY (`fees_pay_id`) REFERENCES `smgt_fees_payment` (`fees_pay_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_homework`
			ADD CONSTRAINT `fk_homework_sub_id` FOREIGN KEY (`subject_id`) REFERENCES `smgt_subject` (`subid`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_homework_class_id` FOREIGN KEY (`class_id`) REFERENCES `classmgt` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_homework_section_id` FOREIGN KEY (`section_id`) REFERENCES `class_section` (`class_section_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_hostel_room`
			ADD CONSTRAINT `fk_hostel_room_category_id` FOREIGN KEY (`room_category`) REFERENCES `smgt_hostel_room_category` (`hostel_room_category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_room_hostel_id` FOREIGN KEY (`hostel_id`) REFERENCES `smgt_hostel` (`hostel_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_income_expense`
			ADD CONSTRAINT `fk_income_expence_section_id` FOREIGN KEY (`section`) REFERENCES `class_section` (`class_section_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_income_expence_class_id` FOREIGN KEY (`class_id`) REFERENCES `classmgt` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_library_book`
			ADD CONSTRAINT `fk_library_cat_id` FOREIGN KEY (`cat_id`) REFERENCES `smgt_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_library_book_issue`
			ADD CONSTRAINT `fk_issue_book_id` FOREIGN KEY (`book_id`) REFERENCES `smgt_library_book` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_issue_cat_id` FOREIGN KEY (`cat_id`) REFERENCES `smgt_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_issue_class_id` FOREIGN KEY (`class_id`) REFERENCES `classmgt` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_issue_section_id` FOREIGN KEY (`section`) REFERENCES `class_section` (`class_section_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_issue_student_id` FOREIGN KEY (`student_id`) REFERENCES `smgt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_marks`
			ADD CONSTRAINT `fk_marks_class_id` FOREIGN KEY (`class_id`) REFERENCES `classmgt` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_exam_mark_id` FOREIGN KEY (`exam_id`) REFERENCES `smgt_exam` (`exam_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_exam_subject_id` FOREIGN KEY (`subject_id`) REFERENCES `smgt_subject` (`subid`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_marks_section_id` FOREIGN KEY (`section`) REFERENCES `class_section` (`class_section_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_marks_student_id` FOREIGN KEY (`student_id`) REFERENCES `smgt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_message_reciver`
			ADD CONSTRAINT `fk_msg_reciver_id` FOREIGN KEY (`reciver_id`) REFERENCES `smgt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_msg_receiver_id` FOREIGN KEY (`message_id`) REFERENCES `smgt_message_sent` (`message_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_msg_sent_id` FOREIGN KEY (`sent_id`) REFERENCES `smgt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_message_replies`
			ADD CONSTRAINT `fk_msg_replies_id` FOREIGN KEY (`smgt_reciver_id`) REFERENCES `smgt_message_reciver` (`smgt_reciver_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_msg_reply_sent_id` FOREIGN KEY (`message_id`) REFERENCES `smgt_message_sent` (`message_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_msg_sender_id` FOREIGN KEY (`sender_id`) REFERENCES `smgt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_message_sent`
			ADD CONSTRAINT `fk_msg_sent_user_id` FOREIGN KEY (`sender_id`) REFERENCES `smgt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);	
		
		$sql = "ALTER TABLE `smgt_payment`
			ADD CONSTRAINT `fk_payment_section_id` FOREIGN KEY (`section`) REFERENCES `class_section` (`class_section_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_payment_class_id` FOREIGN KEY (`class_id`) REFERENCES `classmgt` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_payment_receiver_id` FOREIGN KEY (`payment_reciever_id`) REFERENCES `smgt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_payment_user_id` FOREIGN KEY (`user_id`) REFERENCES `smgt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_student_homework`
			ADD CONSTRAINT `fk_stud_homework_student_id` FOREIGN KEY (`student_id`) REFERENCES `smgt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_stud_homework_id` FOREIGN KEY (`homework_id`) REFERENCES `smgt_homework` (`homework_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_subject`
			ADD CONSTRAINT `fk_sub_section_id` FOREIGN KEY (`section`) REFERENCES `class_section` (`class_section_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_sub_class_id` FOREIGN KEY (`class_id`) REFERENCES `classmgt` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_teacher_sub_id` FOREIGN KEY (`teacher_id`) REFERENCES `smgt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_sub_attendance`
			ADD CONSTRAINT `fk_sub_attend_section_id` FOREIGN KEY (`section`) REFERENCES `class_section` (`class_section_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_sub_attend_class_id` FOREIGN KEY (`class_id`) REFERENCES `classmgt` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_sub_attend_sub_id` FOREIGN KEY (`sub_id`) REFERENCES `smgt_subject` (`subid`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_sub_attend_user_id` FOREIGN KEY (`user_id`) REFERENCES `smgt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		$sql = "ALTER TABLE `smgt_time_table`
			ADD CONSTRAINT `fk_time_table_section_id` FOREIGN KEY (`section`) REFERENCES `class_section` (`class_section_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_time_table_class_id` FOREIGN KEY (`class_id`) REFERENCES `classmgt` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_time_table_subject_id` FOREIGN KEY (`subject_id`) REFERENCES `smgt_subject` (`subid`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_time_table_teacher_id` FOREIGN KEY (`teacher_id`) REFERENCES `smgt_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql);
		
		/* $sql = "ALTER TABLE `smgt_users`
			ADD CONSTRAINT `fk_classname_user_id` FOREIGN KEY (`classname`) REFERENCES `classmgt` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD CONSTRAINT `fk_classsection_user_id` FOREIGN KEY (`classsection`) REFERENCES `class_section` (`class_section_id`) ON DELETE CASCADE ON UPDATE CASCADE";
		$stmt = $conn->execute($sql); */
		
		
		file_put_contents(TMP.'installed.txt', date('Y-m-d, H:i:s'));	
		
		$this->redirect(["action"=>"success"]);
		
	}
	
	private function insertData($data)
	{
		$this->viewBuilder()->layout("");
		$this->autoRender = false;
		$year = date("Y");
		$conn = ConnectionManager::get('install_db');
		$sql = $insert = "
		INSERT INTO `smgt_setting` 
		(`field_name`, `field_value`) 
		VALUES
			('school_name', '{$data['name']}'), 
			('start_year', '{$year}'), 
			('school_address', 'Address Detail'),
			('office_phone_no', '0000000000'),
			('country', '{$data['country']}'), 
			('email', '{$data['email']}'), 
			('school_logo', 'school-logo.png'), 
			('school_profile', 'school_life.jpg'), 
			('system_lang', '{$data['system_lang']}'), 
			('date_format', '{$data['date_format']}'), 
			('enable_sandbox', 'yes'), 
			('paypal_email', 'Test@gmail.com'), 
			('currency_code', 'BRL'), 
			('admission_code', ''), 
			('parent_msg_stud', 'no'), 
			('stud_msg_other', 'yes'), 
			('teacher_msg_all_stud', 'no'), 
			('select_serveice', 'clicktell'), 
			('twillo', ''), 
			('clicktell', ''), 
			('smgt_currency_code', '{$data['currency_code']}'), 
			('school_name_image', 'school_name.png'), 
			('name_image', 'school_name.png'), 
			('old_icon', 'Niftysol-Logo.80-80.png'), 
			('school_icon', 'Niftysol-Logo.80-80.png'),
			('copyright', 'Copyright © 2018-2019. All rights reserved.'),
			('stud_method', 'Random'),
			('no_of_digit', '4'),
			('stud_id_prefix', ''),
			('reminder_message', 'Hello PARENT_NAME,
      Your child  CHILD_NAME  FEES_TYPE fess due Amount is  DUE_AMOUNT , please complete it.
Thank You.'),
			('system_version' ,1.0)";
		$stmt = $conn->execute($sql);			
		
		//$this->updateSys();
	}
	
	
	public function updateSys()
	{		
		$this->autoRender = false;
		$system_version = "";
		$conn = ConnectionManager::get('install_db');
		$sql = "SELECT * from smgt_setting";
		$settings = $conn->execute($sql)->fetchAll("assoc");
		$system_version = $this->Setting->getfieldname('system_version');
		if(!empty($settings))
		{
			if($system_version != "")
			{
				$version = $system_version;
				switch($version)
				{
					CASE "2": /* If old version is 2*/
					
						/* update queries for version 3 */
						
					break ;
				}
				
			}
			else
			{
				/* 1st Update */							
				$sql = "ALTER TABLE `general_setting` ADD `enable_rtl` INT(11) NULL DEFAULT '0'";
				$conn->execute($sql);
				$sql = "ALTER TABLE `general_setting` CHANGE `enable_rtl` `enable_rtl` INT(11) NULL DEFAULT '0'";
				$conn->execute($sql);
				$sql = "ALTER TABLE `general_setting` ADD `datepicker_lang` TEXT NULL DEFAULT NULL";
				$conn->execute($sql);
				$sql = "ALTER TABLE `general_setting` ADD `system_version` TEXT NULL DEFAULT NULL";
				$conn->execute($sql);
				$sql = "ALTER TABLE `general_setting` ADD `sys_language` VARCHAR(20) NOT NULL DEFAULT 'en'";
				$conn->execute($sql);
				/* $sql = "UPDATE `general_setting` SET system_version = '2'"; */
				$sql = "UPDATE `general_setting` SET system_version = '3'";
				$conn->execute($sql);				
				
				$path = $this->request->base;
				$sql = "INSERT INTO `gym_accessright` (`controller`, `action`, `menu`, `menu_icon`, `menu_title`, `member`, `staff_member`, `accountant`, `page_link`) VALUES ('Reports', '', 'report', 'report.png', 'Report', '0', '1', '1', '".$path."/reports/membership-report')";
				$conn->execute($sql);
				
				$sql = "SHOW COLUMNS FROM `membership` LIKE 'membership_class' ";
				$columns = $conn->execute($sql)->fetch();
				if($columns == false)
				{
					$sql = "ALTER TABLE `membership` ADD `membership_class` varchar(255) NULL";
					$conn->execute($sql);
				}						
			}				
		}		
	}
	
	
	public function success()
	{
		
	}
	
	public function isAuthorized($user)
	{
		return true;
	}
}