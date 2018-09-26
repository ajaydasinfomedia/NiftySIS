<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\I18n\I18n;
use Cake\I18n\Time;
use Cake\I18n\Date;
use Cake\I18n\FrozenDate;
use Cake\Database\Type;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
		
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
		$this->loadComponent('Setting');		
			
		if(file_exists(TMP.'installed.txt') && $this->request->controller != "Installer")
		{
			$sysLang = $this->Setting->getfieldname("system_lang");
			$this->set('sysLang',$sysLang);
			
			$logo=$this->Setting->getfieldname('school_logo');
			$this->set('logo',$logo);
					
			$school_name=$this->Setting->getfieldname('school_name');
			$this->set('school_name',$school_name);
			
			$school_icon=$this->Setting->getfieldname('school_icon');
			$this->set('school_icon',$school_icon);
			
			$school_profile=$this->Setting->getfieldname('school_profile');
			$this->set('school_profile',$school_profile);
			
			$this->Setting->send_mail_absent_student();
			$this->Setting->send_mail_student_result_declare();
			$this->Setting->send_mail_student_return_book();
			
			$lang_rtl=$this->Setting->getfieldname('lang_rtl');
			
			$session = $this->request->session();
			
			$session->write('lang_rtl',$lang_rtl);
			$user = $session->read('user_id');
			
			if(empty($user) && $this->request->action != 'registration' 
							&& $this->request->action != 'viewsectionlist'
							&& $this->request->action != 'editterm'
							&& $this->request->action != 'viewstandardlist'
							&& $this->request->action != 'viewparentschoollist'
							&& $this->request->action != 'viewmediumlist'
							&& $this->request->action != 'viewqualificationlist'
							&& $this->request->action != 'viewoccupationlist'
							&& $this->request->action != 'paymentviewpdf'
							&& $this->request->action != 'studentreceiptpdf'
							&& $this->request->action != 'addnewsection')
			{
				if($this->name != 'User')
					return $this->redirect(['controller' => 'User','action'=>'user']);
			}
			/* var_dump($this->name);die; */
			
			$role = $this->Setting->get_user_role($user);
			// var_dump($user);
			// var_dump($role);die;
			/*
			if($this->name != 'Templet' && $this->name != 'Changepassword')
			{
				if(!empty($user) && $role == 'student' || $role == 'teacher' || $role == 'parent' || $role == 'supportstaff')
				{	
					if($this->name == 'Comman')
					{
						$access = $this->Setting->comman_user_action_access($this->request->action,$role);
										
						if(!$access)
						{
							if($this->name != 'User')
								return $this->redirect(['controller' => 'User','action'=>'user']);
						}
						
					}
					else
					{
						$access = $this->Setting->check_user_controller_access($this->name,$role);
										
						if(!$access)
						{
							if($this->name != 'User')
								return $this->redirect(['controller' => 'User','action'=>'user']);
						}
						
					}
				}
			}
			*/
		}
    }
	
    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
		if(file_exists(TMP.'installed.txt') && $this->request->controller != "Installer")
		{
			$session = $this->request->session();
			$user = $session->read('user_id');
			
			if(empty($user) && $this->request->action != 'registration' 
							&& $this->request->action != 'viewsectionlist'
							&& $this->request->action != 'editterm'
							&& $this->request->action != 'viewstandardlist'
							&& $this->request->action != 'viewparentschoollist'
							&& $this->request->action != 'viewmediumlist'
							&& $this->request->action != 'viewqualificationlist'
							&& $this->request->action != 'viewoccupationlist'
							&& $this->request->action != 'paymentviewpdf'
							&& $this->request->action != 'studentreceiptpdf'
							&& $this->request->action != 'addnewsection')
			{
				if($this->name != 'User')
					return $this->redirect(['controller' => 'User','action'=>'user']);
			}
		}
		
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }

    }
	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);

		if(file_exists(TMP.'installed.txt') && $this->request->controller != "Installer")
		{
			
			$session = $this->request->session();
			$user = $session->read('user_id');
			
			 
			if(empty($user) && $this->request->action != 'registration' 
							&& $this->request->action != 'viewsectionlist'
							&& $this->request->action != 'editterm'
							&& $this->request->action != 'viewstandardlist'
							&& $this->request->action != 'viewparentschoollist'
							&& $this->request->action != 'viewmediumlist'
							&& $this->request->action != 'viewqualificationlist'
							&& $this->request->action != 'viewoccupationlist'
							&& $this->request->action != 'paymentviewpdf'
							&& $this->request->action != 'studentreceiptpdf'
							&& $this->request->action != 'addnewsection')
			{
				if($this->name != 'User')
					return $this->redirect(['controller' => 'User','action'=>'user']);
			}
			
			$this->loadComponent("Setting");
			@$lang = $this->Setting->getfieldname("system_lang");
			@$dateformat = $this->Setting->getfieldname("date_format");
			// date_default_timezone_set('UTC'); // Potential for mistakes
			
			if (empty($lang)) {
				return $lang = "en_US";
			}
			// var_dump($lang);
			I18n::locale($lang);
			
			$dateformatlocale = "yyyy-MM-dd";
			
			if($lang == 'en' ||
			   $lang == 'zh_CH' || 
			   $lang == 'cs' || 
			   $lang == 'fr' || 
			   $lang == 'de' || 
			   $lang == 'el' || 
			   $lang == 'it' || 
			   $lang == 'ja' || 
			   $lang == 'pl' || 
			   $lang == 'pt_BR' || 
			   $lang == 'pt_PT' || 
			   $lang == 'ru' || 
			   $lang == 'es' || 
			   $lang == 'th' || 
			   $lang == 'tr' 
			   )
			{
				
				$dateformatlocale = 'yyyy-MM-dd';
			}
			elseif($lang == 'ar' || $lang == 'fa')
			{
				$dateformatlocale = 'yyyy-MM-dd';
			}
			// Time::$defaultLocale = $lang;
			Time::setToStringFormat($dateformatlocale);
			Date::setToStringFormat($dateformatlocale);
			FrozenDate::setToStringFormat($dateformatlocale);
			Type::build('date')
			->useImmutable()->useLocaleParser()
			->setLocaleFormat($dateformatlocale);
			Type::build('datetime')
			->useImmutable()->useLocaleParser()
			->setLocaleFormat($dateformatlocale);

			// ini_set('intl.default_locale', "$lang");
			//setlocale ("$lang.utf8");
		}

	} 
}
