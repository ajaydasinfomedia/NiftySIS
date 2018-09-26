<!DOCTYPE html>
<?php
use Cake\ORM\TableRegistry;
$table_church_settings=TableRegistry::get('cmgt_church');
$get_setting_data=$table_church_settings->find()->first();
if(isset($get_setting_data)){
	$logo_image=$get_setting_data['church_logo'];
	$church_name=$get_setting_data['church_name'];
	$favicon_icon=$get_setting_data['favicon_icon'];
	$footer=$get_setting_data['footer'];
	$lang=$get_setting_data['sys_language'];
	$currency=$get_setting_data['currency'];
	$cal_lang=$get_setting_data['calendar_lang'];
}
$user_id=$this->request->session()->read('user_id');
$user_image=$this->request->session()->read('user_image');
$user_name=$this->request->session()->read('user_name');
$user_role=$this->request->session()->read('user_role');
if($user_role == 'admin' || $user_role == 'member'){
    $image_path="member/".$user_image;
}else if($user_role == 'accountant'){
	$image_path="accountant/".$user_image;
}
?>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $church_name ?>
        <?= $this->fetch('title') ?>
    </title><?= $this->Html->meta('icon') ?>
   <?= $this->Html->css('jquery-ui.css') ?>
	
	<?= $this->Html->script('jquery-2.1.4_min.js') ?>
	<?= $this->Html->script('datatable_min.js', ['type' => 'text/javascript','defer']) ?>	
	
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('webroot/script') ?>
	<script>
	$(document).ready(function () {
              $("#primaryTextButton,#servicesave,#search,#saveequipment,#add,#primaryTextButton1,#compose,#saverole,#savesong,#save").kendoButton();
    });
	</script>
</head>
<?php
if($user_id!= ''){
?>
<?php $url = $_SERVER['REQUEST_URI'];  
	if(isset($url)){
		$explode = explode("/",$url);
			$c = count($explode)-1;
		}
	if($explode[$c] == 'dashboard'){	
		$class="home-page";
	}
    else { $class = "inner-page"; }
?>
<body class="page-header-fixed pace-done <?php echo $class; ?>">
<div class="se-pre-con"></div>
<main class="page-content content-wrap">
    <div class="navbar" style="height:60px">
        <div class="navbar-inner">
            <div class="sidebar-pusher">
                <a class="waves-effect waves-button waves-classic push-sidebar" >
                    <i class="fa fa-bars"></i>
                </a>
            </div>
		    <div class="search-button">
			    <li class="dropdown">
					<a data-toggle="dropdown" style="text-decoration:none;" class="dropdown-toggle waves-effect waves-button waves-classic" href="#">
						<?php echo $this->Html->image($image_path, ['class' => 'img-circle avatar','id'=>'profileimg','width'=>'40','height'=>'40']); ?>
						<span class="user-name">
							<span id="username" style="font-family:Arial">&nbsp;<?php echo $user_name;?></span>
							<i class="fa fa-angle-down"></i>
						</span>
					</a>
					<ul role="menu" class="dropdown-menu dropdown-list">
						<li role="presentation"><?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-user')) . __('Profile'),['controller' => 'account', 'action' => 'account'],['escape' => false]);?></li>
						<li role="presentation"><?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-sign-out m-r-xs')) . __('Log out'),['controller' => 'User', 'action' => 'logout'],['escape' => false]);?></li>
					</ul>
				</li>
			</div>
			<div class="topmenu-outer">
				<div class="top-menu">
					<ul class="nav navbar-nav navbar-left col-md-8 col-sm-8 col-xs-6">
						<li>
							<?php echo $this->Html->image($logo_image, ['width' => '50','height' => '50','style'=>'border-radius:50%','id'=>'logo-image']);?>
						</li>
						<li>
							<?= $this->Html->link($church_name,['controller'=>'dashboard','action'=>'dashboard'],['style'=>'font-size:18px;'])?>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle waves-effect waves-button waves-classic" href="#">
								<?php echo $this->Html->image($image_path, ['class' => 'img-circle avatar','id'=>'profileimg','width'=>'40','height'=>'40']); ?>
								<span class="user-name">
									<span id="username" style="font-family:Arial">&nbsp;<?php echo $user_name;?></span>
										<i class="fa fa-angle-down"></i>
								</span>
							</a>
							<ul role="menu" class="dropdown-menu dropdown-list">
								<li role="presentation"><?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-user')) . __('Profile'),['controller' => 'account', 'action' => 'account'],['escape' => false]);?></li>
								<li role="presentation"><?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-sign-out m-r-xs')) . __('Log out'),['controller' => 'User', 'action' => 'logout'],['escape' => false]);?></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
        </div>
    </div>
    <div class="page-sidebar sidebar">
        <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 100%;">
            <div class="page-sidebar-inner slimscroll"  style="width: auto; height: 100%;" id="cssmenu">
	    <?php 
		    if($user_role == 'admin'){
				echo $this->element('admin_menu');	
			}else if($user_role == 'member'){
			    echo $this->element('member_menu');	
			}else if($user_role == 'accountant'){
				echo $this->element('accountant_menu');	
		}
		?>
            </div>
			<div class="slimScrollBar" style="background: rgb(204, 204, 204) none repeat scroll 0% 0%; width: 7px; position: absolute; top: 0px; opacity: 0.3; display: none; border-radius: 0px; z-index: 99; right: 0px; height: 1088px;"></div>
			<div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 0px; background: rgb(51, 51, 51) none repeat scroll 0% 0%; opacity: 0.2; z-index: 90; right: 0px;"></div>
		</div>
    </div>
    <div class="page-inner" style="min-height:1250px!important">
    <div class="page-title"></div>
<?php
	}
?>
    <?= $this->Flash->render() ?>
        <div id="main-wrapper">
            <?= $this->fetch('content') ?>
        </div>
    </div>
	<footer id="footer" class="text-center">
		<i class="fa fa-copyright" aria-hidden="true"></i> <?= $footer?>
    </footer>
</main>
<?= $this->Html->script('modern.js');?>
</body>
</html>
