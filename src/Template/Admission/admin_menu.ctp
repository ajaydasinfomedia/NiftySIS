<?php
use Cake\ORM\TableRegistry;
$accessright=TableRegistry::get('cmgt_accessright');
$sidebar=$accessright->find()->where(['admin'=>1])->order(['id'=>'ASC'])->toArray();
?>
<script> 
$(document).ready(function(){
    $("#setting").click(function(){
        $("#setting-item").slideToggle("slow");
		    $("i", this).toggleClass("fa-angle-down pull-right down-arrow");
    });
});
</script>
<ul class="menu accordion-menu">
    <?php
        $current_controller=$this->request->params['controller'];
            function check_link($current_controller,$chk_str){
                if($current_controller == $chk_str){
                    return 'menu_active';
                }else{
                    return '';
                }
            }
foreach($sidebar as $s){ ?>
    <li class="<?php echo check_link($current_controller,$s['controller']);?>">
        <span class="menu-icon">
			<?php echo $this->Html->image('icon/'.$s['menu_icon'], ['alt' => '']);?>
		</span>
        <?php echo $this->Html->link(__($s['show_name']),['controller' => $s['controller'], 'action' => $s['action']]);?>
    </li>
<?php } ?>
    <li id="setting" class="<?php echo check_link($current_controller,$s['controller']);?>">
        <span class="menu-icon">
		    <?php echo $this->Html->image('icon/general-setting.png');?>  
		</span>
		<a><?= __('General Settings')?>&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right pull-right right-arrow"></i></a>
	</li>  
    <div id="setting-item" style="display:none;">
		<li class="<?php echo check_link($current_controller,'');?>" >
		<span class="menu-icon"> <?php echo $this->Html->image('icon/account.png');?>  </span>
			<?php echo $this->Html->link(__('Account'),['controller' => 'account', 'action' => 'account']);?>
		</li>
		<li  class="<?php echo check_link($current_controller,'');?>" >
		<span class="menu-icon"> <?php echo $this->Html->image('icon/newsletter.png');?>  </span>
			<?php echo $this->Html->link(__('Newsletter'),['controller' => 'newsletter', 'action' => 'index']);?>
		</li>
		<li class="<?php echo check_link($current_controller,'');?>" >
		<span class="menu-icon"> <?php echo $this->Html->image('icon/mail_template.png');?>  </span>
			<?php echo $this->Html->link(__('Mail Template'),['controller' => 'emailtemplate', 'action' =>'index' ]);?>
		</li>
		<li  class="<?php echo check_link($current_controller,'');?>" >
		<span class="menu-icon"> <?php echo $this->Html->image('icon/church_settiing.png');?>  </span>
			<?php echo $this->Html->link(__('Church Settings'),['controller' => 'settings', 'action' => 'church']);?>
		</li>
		<li  class="<?php echo check_link($current_controller,'');?>" >
		<span class="menu-icon"> <?php echo $this->Html->image('icon/access_rights.png');?>  </span>
			<?php echo $this->Html->link(__('Access Rights'),['controller' => 'Accessright', 'action' => 'accessRight']);?>
		</li>
		</div>	
</ul>