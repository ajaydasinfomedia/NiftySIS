<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$school_name = "";
$school_icon = "";

$school_name = $this->Setting->getfieldname('school_name');
$school_icon = $this->Setting->getfieldname('school_icon');

$cakeDescription = $school_name;
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
	<?php
	if($school_icon == ""){ 
		 echo  $this->Html->meta( 'icon','niftyschool.png'); 
	}
	else{ 
		echo $this->Html->meta('icon','img/'.$school_icon);
	}	
	
	echo $this->Html->css('bootstrap.min');
	
	$session = $this->request->session();
	
	if($session->read("lang_rtl") == "yes")
	{
		echo $this->Html->css('bootstrap-rtl.min');
		echo "<style>
				.page-sidebar.sidebar,.menu-icon,.nav.navbar-nav.navbar-left{
					float:right!important;
				}
				.menu.accordion-menu > li > a{
					text-align:right!important;
				}
				.nav.nav-tabs.panel_tabs{
					padding-left:0px!important;
					padding-right:30px;
					margin:20px 20px 0px 0px!important;
				}
				.nav.navbar-right .dropdown{
					float:left!important;
				}
				div.success,div.error{
					right : 15px;
				}
				
			  </style>";
	}
	?>
		
	<?= $this->Html->css('jquery-ui.css') ?>
	
	<?= $this->Html->script('jquery-2.1.4_min.js') ?>
	<?= $this->Html->script('datatable_min.js', ['defer']) ?>	

	<?php
	if($this->request->action != 'examhallreceipt' && $this->request->action != 'studentexamhall')
	{
		?>
		<style>		
		.sweet-alert,.sweet-overlay{position:fixed;display:none}body.stop-scrolling{height:100%;overflow:visible}.sweet-overlay{background-color:#000;-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=40)";background-color:rgba(0,0,0,.4);left:0;right:0;top:0;bottom:0;z-index:10000}.sweet-alert{background-color:#fff;font-family:'Open Sans','Helvetica Neue',Helvetica,Arial,sans-serif;width:478px;padding:17px;border-radius:2px;text-align:center;left:50%;top:50%;margin-left:-256px;margin-top:-200px;overflow:hidden;z-index:99999}@media all and (max-width:540px){.sweet-alert{width:auto;margin-left:0;margin-right:0;left:15px;right:15px}}.sweet-alert h2{color:#575757;font-size:30px;text-align:center;font-weight:600;text-transform:none;position:relative;margin:25px 0;padding:0;line-height:40px;display:block}.sweet-alert p{color:#797979;font-size:16px;font-weight:300;position:relative;text-align:inherit;float:none;margin:0;padding:0;line-height:normal}.sweet-alert fieldset{border:none;position:relative}.sweet-alert .sa-error-container{background-color:#f1f1f1;margin-left:-17px;margin-right:-17px;overflow:hidden;padding:0 10px;max-height:0;}.sweet-alert .sa-error-container.show{padding:10px 0;max-height:100px;}.sweet-alert .sa-error-container .icon{display:inline-block;width:24px;height:24px;border-radius:50%;background-color:#ea7d7d;color:#fff;line-height:24px;text-align:center;margin-right:3px}.sweet-alert .sa-error-container p{display:inline-block}.sweet-alert .sa-input-error{position:absolute;top:29px;right:26px;width:20px;height:20px;opacity:0;-webkit-transform:scale(.5);transform:scale(.5);-webkit-transform-origin:50% 50%;transform-origin:50% 50%;-webkit-transition:all .1s;transition:all .1s}.sweet-alert .sa-input-error::after,.sweet-alert .sa-input-error::before{content:"";width:20px;height:6px;background-color:#f06e57;border-radius:3px;position:absolute;top:50%;margin-top:-4px;left:50%;margin-left:-9px}.sweet-alert .sa-input-error::before{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}.sweet-alert .sa-input-error::after{-webkit-transform:rotate(45deg);transform:rotate(45deg)}.sweet-alert .sa-input-error.show{opacity:1;-webkit-transform:scale(1);transform:scale(1)}.sweet-alert input{width:100%;box-sizing:border-box;border-radius:3px;border:1px solid #d7d7d7;height:43px;margin-top:10px;margin-bottom:17px;font-size:18px;box-shadow:inset 0 1px 1px rgba(0,0,0,.06);padding:0 12px;display:none;-webkit-transition:all .3s;transition:all .3s}.sweet-alert input:focus{outline:0;box-shadow:0 0 3px #c4e6f5;border:1px solid #b4dbed}.sweet-alert input:focus::-moz-placeholder{transition:opacity .3s 30ms ease;opacity:.5}.sweet-alert input:focus:-ms-input-placeholder{transition:opacity .3s 30ms ease;opacity:.5}.sweet-alert input:focus::-webkit-input-placeholder{transition:opacity .3s 30ms ease;opacity:.5}.sweet-alert input::-moz-placeholder{color:#bdbdbd}.sweet-alert input:-ms-input-placeholder{color:#bdbdbd}.sweet-alert input::-webkit-input-placeholder{color:#bdbdbd}.sweet-alert.show-input input{display:block}.sweet-alert .sa-confirm-button-container{display:inline-block;position:relative}.sweet-alert .la-ball-fall{position:absolute;left:50%;top:50%;margin-left:-27px;margin-top:4px;opacity:0;visibility:hidden}.sweet-alert button{background-color:#8CD4F5;color:#fff;border:none;box-shadow:none;font-size:17px;font-weight:500;-webkit-border-radius:2px;border-radius:2px;padding:10px 32px;margin:26px 5px 0;cursor:pointer}.sweet-alert button:focus{outline:0;box-shadow:0 0 2px rgba(128,179,235,.5),inset 0 0 0 1px rgba(0,0,0,.05)}.sweet-alert button:hover{background-color:#7ecff4}.sweet-alert button:active{background-color:#5dc2f1}.sweet-alert button.cancel{background-color:#C1C1C1}.sweet-alert button.cancel:hover{background-color:#b9b9b9}.sweet-alert button.cancel:active{background-color:#a8a8a8}.sweet-alert button.cancel:focus{box-shadow:rgba(197,205,211,.8) 0 0 2px,rgba(0,0,0,.0470588) 0 0 0 1px inset!important}.sweet-alert button[disabled]{opacity:.6;cursor:default}.sweet-alert button.confirm[disabled]{color:transparent}.sweet-alert button.confirm[disabled]~.la-ball-fall{opacity:1;visibility:visible;transition-delay:0s}.sweet-alert button::-moz-focus-inner{border:0}.sweet-alert[data-has-cancel-button=false] button{box-shadow:none!important}.sweet-alert[data-has-confirm-button=false][data-has-cancel-button=false]{padding-bottom:40px}.sweet-alert .sa-icon{width:80px;height:80px;border:4px solid gray;-webkit-border-radius:40px;border-radius:50%;margin:20px auto;padding:0;position:relative;box-sizing:content-box}.sweet-alert .sa-icon.sa-error{border-color:#F27474}.sweet-alert .sa-icon.sa-error .sa-x-mark{position:relative;display:block}.sweet-alert .sa-icon.sa-error .sa-line{position:absolute;height:5px;width:47px;background-color:#F27474;display:block;top:37px;border-radius:2px}.sweet-alert .sa-icon.sa-error .sa-line.sa-left{-webkit-transform:rotate(45deg);transform:rotate(45deg);left:17px}.sweet-alert .sa-icon.sa-error .sa-line.sa-right{-webkit-transform:rotate(-45deg);transform:rotate(-45deg);right:16px}.sweet-alert .sa-icon.sa-warning{border-color:#F8BB86}.sweet-alert .sa-icon.sa-warning .sa-body{position:absolute;width:5px;height:47px;left:50%;top:10px;-webkit-border-radius:2px;border-radius:2px;margin-left:-2px;background-color:#F8BB86}.sweet-alert .sa-icon.sa-warning .sa-dot{position:absolute;width:7px;height:7px;-webkit-border-radius:50%;border-radius:50%;margin-left:-3px;left:50%;bottom:10px;background-color:#F8BB86}.sweet-alert .sa-icon.sa-info::after,.sweet-alert .sa-icon.sa-info::before{content:"";background-color:#C9DAE1;position:absolute}.sweet-alert .sa-icon.sa-info{border-color:#C9DAE1}.sweet-alert .sa-icon.sa-info::before{width:5px;height:29px;left:50%;bottom:17px;border-radius:2px;margin-left:-2px}.sweet-alert .sa-icon.sa-info::after{width:7px;height:7px;border-radius:50%;margin-left:-3px;top:19px}.sweet-alert .sa-icon.sa-success{border-color:#A5DC86}.sweet-alert .sa-icon.sa-success::after,.sweet-alert .sa-icon.sa-success::before{content:'';position:absolute;width:60px;height:120px;background:#fff}.sweet-alert .sa-icon.sa-success::before{-webkit-border-radius:120px 0 0 120px;border-radius:120px 0 0 120px;top:-7px;left:-33px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-transform-origin:60px 60px;transform-origin:60px 60px}.sweet-alert .sa-icon.sa-success::after{-webkit-border-radius:0 120px 120px 0;border-radius:0 120px 120px 0;top:-11px;left:30px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-transform-origin:0 60px;transform-origin:0 60px}.sweet-alert .sa-icon.sa-success .sa-placeholder{width:80px;height:80px;border:4px solid rgba(165,220,134,.2);-webkit-border-radius:40px;border-radius:50%;box-sizing:content-box;position:absolute;left:-4px;top:-4px;z-index:2}.sweet-alert .sa-icon.sa-success .sa-fix{width:5px;height:90px;background-color:#fff;position:absolute;left:28px;top:8px;z-index:1;-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}.sweet-alert .sa-icon.sa-success .sa-line{height:5px;background-color:#A5DC86;display:block;border-radius:2px;position:absolute;z-index:2}.sweet-alert .sa-icon.sa-success .sa-line.sa-tip{width:25px;left:14px;top:46px;-webkit-transform:rotate(45deg);transform:rotate(45deg)}.sweet-alert .sa-icon.sa-success .sa-line.sa-long{width:47px;right:8px;top:38px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}.sweet-alert .sa-icon.sa-custom{background-size:contain;border-radius:50%;border:none;background-position:center center;background-repeat:no-repeat}@-webkit-keyframes showSweetAlert{0%{transform:scale(.7);-webkit-transform:scale(.7)}45%{transform:scale(1.05);-webkit-transform:scale(1.05)}80%{transform:scale(.95);-webkit-transform:scale(.95)}100%{transform:scale(1);-webkit-transform:scale(1)}}@keyframes showSweetAlert{0%{transform:scale(.7);-webkit-transform:scale(.7)}45%{transform:scale(1.05);-webkit-transform:scale(1.05)}80%{transform:scale(.95);-webkit-transform:scale(.95)}100%{transform:scale(1);-webkit-transform:scale(1)}}@-webkit-keyframes hideSweetAlert{0%{transform:scale(1);-webkit-transform:scale(1)}100%{transform:scale(.5);-webkit-transform:scale(.5)}}@keyframes hideSweetAlert{0%{transform:scale(1);-webkit-transform:scale(1)}100%{transform:scale(.5);-webkit-transform:scale(.5)}}@-webkit-keyframes slideFromTop{0%{top:0}100%{top:50%}}@keyframes slideFromTop{0%{top:0}100%{top:50%}}@-webkit-keyframes slideToTop{0%{top:50%}100%{top:0}}@keyframes slideToTop{0%{top:50%}100%{top:0}}@-webkit-keyframes slideFromBottom{0%{top:70%}100%{top:50%}}@keyframes slideFromBottom{0%{top:70%}100%{top:50%}}@-webkit-keyframes slideToBottom{0%{top:50%}100%{top:70%}}@keyframes slideToBottom{0%{top:50%}100%{top:70%}}.showSweetAlert[data-animation=pop]{-webkit-animation:showSweetAlert .3s;animation:showSweetAlert .3s}.showSweetAlert[data-animation=none]{-webkit-animation:none;animation:none}.showSweetAlert[data-animation=slide-from-top]{-webkit-animation:slideFromTop .3s;animation:slideFromTop .3s}.showSweetAlert[data-animation=slide-from-bottom]{-webkit-animation:slideFromBottom .3s;animation:slideFromBottom .3s}.hideSweetAlert[data-animation=pop]{-webkit-animation:hideSweetAlert .2s;animation:hideSweetAlert .2s}.hideSweetAlert[data-animation=none]{-webkit-animation:none;animation:none}.hideSweetAlert[data-animation=slide-from-top]{-webkit-animation:slideToTop .4s;animation:slideToTop .4s}.hideSweetAlert[data-animation=slide-from-bottom]{-webkit-animation:slideToBottom .3s;animation:slideToBottom .3s}@-webkit-keyframes animateSuccessTip{0%,54%{width:0;left:1px;top:19px}70%{width:50px;left:-8px;top:37px}84%{width:17px;left:21px;top:48px}100%{width:25px;left:14px;top:45px}}@keyframes animateSuccessTip{0%,54%{width:0;left:1px;top:19px}70%{width:50px;left:-8px;top:37px}84%{width:17px;left:21px;top:48px}100%{width:25px;left:14px;top:45px}}@-webkit-keyframes animateSuccessLong{0%,65%{width:0;right:46px;top:54px}84%{width:55px;right:0;top:35px}100%{width:47px;right:8px;top:38px}}@keyframes animateSuccessLong{0%,65%{width:0;right:46px;top:54px}84%{width:55px;right:0;top:35px}100%{width:47px;right:8px;top:38px}}@-webkit-keyframes rotatePlaceholder{0%,5%{transform:rotate(-45deg);-webkit-transform:rotate(-45deg)}100%,12%{transform:rotate(-405deg);-webkit-transform:rotate(-405deg)}}@keyframes rotatePlaceholder{0%,5%{transform:rotate(-45deg);-webkit-transform:rotate(-45deg)}100%,12%{transform:rotate(-405deg);-webkit-transform:rotate(-405deg)}}.animateSuccessTip{-webkit-animation:animateSuccessTip .75s;animation:animateSuccessTip .75s}.animateSuccessLong{-webkit-animation:animateSuccessLong .75s;animation:animateSuccessLong .75s}.sa-icon.sa-success.animate::after{-webkit-animation:rotatePlaceholder 4.25s ease-in;animation:rotatePlaceholder 4.25s ease-in}@-webkit-keyframes animateErrorIcon{0%{transform:rotateX(100deg);-webkit-transform:rotateX(100deg);opacity:0}100%{transform:rotateX(0);-webkit-transform:rotateX(0);opacity:1}}@keyframes animateErrorIcon{0%{transform:rotateX(100deg);-webkit-transform:rotateX(100deg);opacity:0}100%{transform:rotateX(0);-webkit-transform:rotateX(0);opacity:1}}.animateErrorIcon{-webkit-animation:animateErrorIcon .5s;animation:animateErrorIcon .5s}@-webkit-keyframes animateXMark{0%,50%{transform:scale(.4);-webkit-transform:scale(.4);margin-top:26px;opacity:0}80%{transform:scale(1.15);-webkit-transform:scale(1.15);margin-top:-6px}100%{transform:scale(1);-webkit-transform:scale(1);margin-top:0;opacity:1}}@keyframes animateXMark{0%,50%{transform:scale(.4);-webkit-transform:scale(.4);margin-top:26px;opacity:0}80%{transform:scale(1.15);-webkit-transform:scale(1.15);margin-top:-6px}100%{transform:scale(1);-webkit-transform:scale(1);margin-top:0;opacity:1}}.animateXMark{-webkit-animation:animateXMark .5s;animation:animateXMark .5s}@-webkit-keyframes pulseWarning{0%{border-color:#F8D486}100%{border-color:#F8BB86}}@keyframes pulseWarning{0%{border-color:#F8D486}100%{border-color:#F8BB86}}.pulseWarning{-webkit-animation:pulseWarning .75s infinite alternate;animation:pulseWarning .75s infinite alternate}@-webkit-keyframes pulseWarningIns{0%{background-color:#F8D486}100%{background-color:#F8BB86}}@keyframes pulseWarningIns{0%{background-color:#F8D486}100%{background-color:#F8BB86}}.pulseWarningIns{-webkit-animation:pulseWarningIns .75s infinite alternate;animation:pulseWarningIns .75s infinite alternate}@-webkit-keyframes rotate-loading{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}@keyframes rotate-loading{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}.sweet-alert .sa-icon.sa-error .sa-line.sa-left{-ms-transform:rotate(45deg)\9}.sweet-alert .sa-icon.sa-error .sa-line.sa-right{-ms-transform:rotate(-45deg)\9}.sweet-alert .sa-icon.sa-success{border-color:transparent\9}.sweet-alert .sa-icon.sa-success .sa-line.sa-tip{-ms-transform:rotate(45deg)\9}.sweet-alert .sa-icon.sa-success .sa-line.sa-long{-ms-transform:rotate(-45deg)\9}		 
		.la-ball-fall,.la-ball-fall>div{position:relative;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}.la-ball-fall{display:block;font-size:0;color:#fff;width:54px;height:18px}.la-ball-fall.la-dark{color:#333}.la-ball-fall>div{display:inline-block;float:none;background-color:currentColor;border:0 solid currentColor;width:10px;height:10px;margin:4px;border-radius:100%;opacity:0;-webkit-animation:ball-fall 1s ease-in-out infinite;-moz-animation:ball-fall 1s ease-in-out infinite;-o-animation:ball-fall 1s ease-in-out infinite;animation:ball-fall 1s ease-in-out infinite}.la-ball-fall>div:nth-child(1){-webkit-animation-delay:-.2s;-moz-animation-delay:-.2s;-o-animation-delay:-.2s;animation-delay:-.2s}.la-ball-fall>div:nth-child(2){-webkit-animation-delay:-.1s;-moz-animation-delay:-.1s;-o-animation-delay:-.1s;animation-delay:-.1s}.la-ball-fall>div:nth-child(3){-webkit-animation-delay:0s;-moz-animation-delay:0s;-o-animation-delay:0s;animation-delay:0s}.la-ball-fall.la-sm{width:26px;height:8px}.la-ball-fall.la-sm>div{width:4px;height:4px;margin:2px}.la-ball-fall.la-2x{width:108px;height:36px}.la-ball-fall.la-2x>div{width:20px;height:20px;margin:8px}.la-ball-fall.la-3x{width:162px;height:54px}.la-ball-fall.la-3x>div{width:30px;height:30px;margin:12px}@-webkit-keyframes ball-fall{0%{opacity:0;-webkit-transform:translateY(-145%);transform:translateY(-145%)}10%,90%{opacity:.5}20%,80%{opacity:1;-webkit-transform:translateY(0);transform:translateY(0)}100%{opacity:0;-webkit-transform:translateY(145%);transform:translateY(145%)}}@-moz-keyframes ball-fall{0%{opacity:0;-moz-transform:translateY(-145%);transform:translateY(-145%)}10%,90%{opacity:.5}20%,80%{opacity:1;-moz-transform:translateY(0);transform:translateY(0)}100%{opacity:0;-moz-transform:translateY(145%);transform:translateY(145%)}}@-o-keyframes ball-fall{0%{opacity:0;-o-transform:translateY(-145%);transform:translateY(-145%)}10%,90%{opacity:.5}20%,80%{opacity:1;-o-transform:translateY(0);transform:translateY(0)}100%{opacity:0;-o-transform:translateY(145%);transform:translateY(145%)}}@keyframes ball-fall{0%{opacity:0;-webkit-transform:translateY(-145%);-moz-transform:translateY(-145%);-o-transform:translateY(-145%);transform:translateY(-145%)}10%,90%{opacity:.5}20%,80%{opacity:1;-webkit-transform:translateY(0);-moz-transform:translateY(0);-o-transform:translateY(0);transform:translateY(0)}100%{opacity:0;-webkit-transform:translateY(145%);-moz-transform:translateY(145%);-o-transform:translateY(145%);transform:translateY(145%)}}
		</style>
		<script>
		!function(e,t,n){"use strict";!function o(e,t,n){function a(s,l){if(!t[s]){if(!e[s]){var i="function"==typeof require&&require;if(!l&&i)return i(s,!0);if(r)return r(s,!0);var u=new Error("Cannot find module '"+s+"'");throw u.code="MODULE_NOT_FOUND",u}var c=t[s]={exports:{}};e[s][0].call(c.exports,function(t){var n=e[s][1][t];return a(n?n:t)},c,c.exports,o,e,t,n)}return t[s].exports}for(var r="function"==typeof require&&require,s=0;s<n.length;s++)a(n[s]);return a}({1:[function(o){var a,r,s,l,i=function(e){return e&&e.__esModule?e:{"default":e}},u=o("./modules/handle-dom"),c=o("./modules/utils"),d=o("./modules/handle-swal-dom"),f=o("./modules/handle-click"),p=o("./modules/handle-key"),m=i(p),v=o("./modules/default-params"),y=i(v),h=o("./modules/set-params"),g=i(h);s=l=function(){function o(e){var t=s;return t[e]===n?y["default"][e]:t[e]}var s=arguments[0];if(u.addClass(t.body,"stop-scrolling"),d.resetInput(),s===n)return c.logStr("SweetAlert expects at least 1 attribute!"),!1;var i=c.extend({},y["default"]);switch(typeof s){case"string":i.title=s,i.text=arguments[1]||"",i.type=arguments[2]||"";break;case"object":if(s.title===n)return c.logStr('Missing "title" argument!'),!1;i.title=s.title;for(var p in y["default"])i[p]=o(p);i.confirmButtonText=i.showCancelButton?"Confirm":y["default"].confirmButtonText,i.confirmButtonText=o("confirmButtonText"),i.doneFunction=arguments[1]||null;break;default:return c.logStr('Unexpected type of argument! Expected "string" or "object", got '+typeof s),!1}g["default"](i),d.fixVerticalPosition(),d.openModal(arguments[1]);for(var v=d.getModal(),h=v.querySelectorAll("button"),b=["onclick","onmouseover","onmouseout","onmousedown","onmouseup","onfocus"],w=function(e){return f.handleButton(e,i,v)},C=0;C<h.length;C++)for(var S=0;S<b.length;S++){var x=b[S];h[C][x]=w}d.getOverlay().onclick=w,a=e.onkeydown;var k=function(e){return m["default"](e,i,v)};e.onkeydown=k,e.onfocus=function(){setTimeout(function(){r!==n&&(r.focus(),r=n)},0)},l.enableButtons()},s.setDefaults=l.setDefaults=function(e){if(!e)throw new Error("userParams is required");if("object"!=typeof e)throw new Error("userParams has to be a object");c.extend(y["default"],e)},s.close=l.close=function(){var o=d.getModal();u.fadeOut(d.getOverlay(),5),u.fadeOut(o,5),u.removeClass(o,"showSweetAlert"),u.addClass(o,"hideSweetAlert"),u.removeClass(o,"visible");var s=o.querySelector(".sa-icon.sa-success");u.removeClass(s,"animate"),u.removeClass(s.querySelector(".sa-tip"),"animateSuccessTip"),u.removeClass(s.querySelector(".sa-long"),"animateSuccessLong");var l=o.querySelector(".sa-icon.sa-error");u.removeClass(l,"animateErrorIcon"),u.removeClass(l.querySelector(".sa-x-mark"),"animateXMark");var i=o.querySelector(".sa-icon.sa-warning");return u.removeClass(i,"pulseWarning"),u.removeClass(i.querySelector(".sa-body"),"pulseWarningIns"),u.removeClass(i.querySelector(".sa-dot"),"pulseWarningIns"),setTimeout(function(){var e=o.getAttribute("data-custom-class");u.removeClass(o,e)},300),u.removeClass(t.body,"stop-scrolling"),e.onkeydown=a,e.previousActiveElement&&e.previousActiveElement.focus(),r=n,clearTimeout(o.timeout),!0},s.showInputError=l.showInputError=function(e){var t=d.getModal(),n=t.querySelector(".sa-input-error");u.addClass(n,"show");var o=t.querySelector(".sa-error-container");u.addClass(o,"show"),o.querySelector("p").innerHTML=e,setTimeout(function(){s.enableButtons()},1),t.querySelector("input").focus()},s.resetInputError=l.resetInputError=function(e){if(e&&13===e.keyCode)return!1;var t=d.getModal(),n=t.querySelector(".sa-input-error");u.removeClass(n,"show");var o=t.querySelector(".sa-error-container");u.removeClass(o,"show")},s.disableButtons=l.disableButtons=function(){var e=d.getModal(),t=e.querySelector("button.confirm"),n=e.querySelector("button.cancel");t.disabled=!0,n.disabled=!0},s.enableButtons=l.enableButtons=function(){var e=d.getModal(),t=e.querySelector("button.confirm"),n=e.querySelector("button.cancel");t.disabled=!1,n.disabled=!1},"undefined"!=typeof e?e.sweetAlert=e.swal=s:c.logStr("SweetAlert is a frontend module!")},{"./modules/default-params":2,"./modules/handle-click":3,"./modules/handle-dom":4,"./modules/handle-key":5,"./modules/handle-swal-dom":6,"./modules/set-params":8,"./modules/utils":9}],2:[function(e,t,n){Object.defineProperty(n,"__esModule",{value:!0});var o={title:"",text:"",type:null,allowOutsideClick:!1,showConfirmButton:!0,showCancelButton:!1,closeOnConfirm:!0,closeOnCancel:!0,confirmButtonText:"OK",confirmButtonColor:"#8CD4F5",cancelButtonText:"Cancel",imageUrl:null,imageSize:null,timer:null,customClass:"",html:!1,animation:!0,allowEscapeKey:!0,inputType:"text",inputPlaceholder:"",inputValue:"",showLoaderOnConfirm:!1};n["default"]=o,t.exports=n["default"]},{}],3:[function(t,n,o){Object.defineProperty(o,"__esModule",{value:!0});var a=t("./utils"),r=(t("./handle-swal-dom"),t("./handle-dom")),s=function(t,n,o){function s(e){m&&n.confirmButtonColor&&(p.style.backgroundColor=e)}var u,c,d,f=t||e.event,p=f.target||f.srcElement,m=-1!==p.className.indexOf("confirm"),v=-1!==p.className.indexOf("sweet-overlay"),y=r.hasClass(o,"visible"),h=n.doneFunction&&"true"===o.getAttribute("data-has-done-function");switch(m&&n.confirmButtonColor&&(u=n.confirmButtonColor,c=a.colorLuminance(u,-.04),d=a.colorLuminance(u,-.14)),f.type){case"mouseover":s(c);break;case"mouseout":s(u);break;case"mousedown":s(d);break;case"mouseup":s(c);break;case"focus":var g=o.querySelector("button.confirm"),b=o.querySelector("button.cancel");m?b.style.boxShadow="none":g.style.boxShadow="none";break;case"click":var w=o===p,C=r.isDescendant(o,p);if(!w&&!C&&y&&!n.allowOutsideClick)break;m&&h&&y?l(o,n):h&&y||v?i(o,n):r.isDescendant(o,p)&&"BUTTON"===p.tagName&&sweetAlert.close()}},l=function(e,t){var n=!0;r.hasClass(e,"show-input")&&(n=e.querySelector("input").value,n||(n="")),t.doneFunction(n),t.closeOnConfirm&&sweetAlert.close(),t.showLoaderOnConfirm&&sweetAlert.disableButtons()},i=function(e,t){var n=String(t.doneFunction).replace(/\s/g,""),o="function("===n.substring(0,9)&&")"!==n.substring(9,10);o&&t.doneFunction(!1),t.closeOnCancel&&sweetAlert.close()};o["default"]={handleButton:s,handleConfirm:l,handleCancel:i},n.exports=o["default"]},{"./handle-dom":4,"./handle-swal-dom":6,"./utils":9}],4:[function(n,o,a){Object.defineProperty(a,"__esModule",{value:!0});var r=function(e,t){return new RegExp(" "+t+" ").test(" "+e.className+" ")},s=function(e,t){r(e,t)||(e.className+=" "+t)},l=function(e,t){var n=" "+e.className.replace(/[\t\r\n]/g," ")+" ";if(r(e,t)){for(;n.indexOf(" "+t+" ")>=0;)n=n.replace(" "+t+" "," ");e.className=n.replace(/^\s+|\s+$/g,"")}},i=function(e){var n=t.createElement("div");return n.appendChild(t.createTextNode(e)),n.innerHTML},u=function(e){e.style.opacity="",e.style.display="block"},c=function(e){if(e&&!e.length)return u(e);for(var t=0;t<e.length;++t)u(e[t])},d=function(e){e.style.opacity="",e.style.display="none"},f=function(e){if(e&&!e.length)return d(e);for(var t=0;t<e.length;++t)d(e[t])},p=function(e,t){for(var n=t.parentNode;null!==n;){if(n===e)return!0;n=n.parentNode}return!1},m=function(e){e.style.left="-9999px",e.style.display="block";var t,n=e.clientHeight;return t="undefined"!=typeof getComputedStyle?parseInt(getComputedStyle(e).getPropertyValue("padding-top"),10):parseInt(e.currentStyle.padding),e.style.left="",e.style.display="none","-"+parseInt((n+t)/2)+"px"},v=function(e,t){if(+e.style.opacity<1){t=t||16,e.style.opacity=0,e.style.display="block";var n=+new Date,o=function(e){function t(){return e.apply(this,arguments)}return t.toString=function(){return e.toString()},t}(function(){e.style.opacity=+e.style.opacity+(new Date-n)/100,n=+new Date,+e.style.opacity<1&&setTimeout(o,t)});o()}e.style.display="block"},y=function(e,t){t=t||16,e.style.opacity=1;var n=+new Date,o=function(e){function t(){return e.apply(this,arguments)}return t.toString=function(){return e.toString()},t}(function(){e.style.opacity=+e.style.opacity-(new Date-n)/100,n=+new Date,+e.style.opacity>0?setTimeout(o,t):e.style.display="none"});o()},h=function(n){if("function"==typeof MouseEvent){var o=new MouseEvent("click",{view:e,bubbles:!1,cancelable:!0});n.dispatchEvent(o)}else if(t.createEvent){var a=t.createEvent("MouseEvents");a.initEvent("click",!1,!1),n.dispatchEvent(a)}else t.createEventObject?n.fireEvent("onclick"):"function"==typeof n.onclick&&n.onclick()},g=function(t){"function"==typeof t.stopPropagation?(t.stopPropagation(),t.preventDefault()):e.event&&e.event.hasOwnProperty("cancelBubble")&&(e.event.cancelBubble=!0)};a.hasClass=r,a.addClass=s,a.removeClass=l,a.escapeHtml=i,a._show=u,a.show=c,a._hide=d,a.hide=f,a.isDescendant=p,a.getTopMargin=m,a.fadeIn=v,a.fadeOut=y,a.fireClick=h,a.stopEventPropagation=g},{}],5:[function(t,o,a){Object.defineProperty(a,"__esModule",{value:!0});var r=t("./handle-dom"),s=t("./handle-swal-dom"),l=function(t,o,a){var l=t||e.event,i=l.keyCode||l.which,u=a.querySelector("button.confirm"),c=a.querySelector("button.cancel"),d=a.querySelectorAll("button[tabindex]");if(-1!==[9,13,32,27].indexOf(i)){for(var f=l.target||l.srcElement,p=-1,m=0;m<d.length;m++)if(f===d[m]){p=m;break}9===i?(f=-1===p?u:p===d.length-1?d[0]:d[p+1],r.stopEventPropagation(l),f.focus(),o.confirmButtonColor&&s.setFocusStyle(f,o.confirmButtonColor)):13===i?("INPUT"===f.tagName&&(f=u,u.focus()),f=-1===p?u:n):27===i&&o.allowEscapeKey===!0?(f=c,r.fireClick(f,l)):f=n}};a["default"]=l,o.exports=a["default"]},{"./handle-dom":4,"./handle-swal-dom":6}],6:[function(n,o,a){var r=function(e){return e&&e.__esModule?e:{"default":e}};Object.defineProperty(a,"__esModule",{value:!0});var s=n("./utils"),l=n("./handle-dom"),i=n("./default-params"),u=r(i),c=n("./injected-html"),d=r(c),f=".sweet-alert",p=".sweet-overlay",m=function(){var e=t.createElement("div");for(e.innerHTML=d["default"];e.firstChild;)t.body.appendChild(e.firstChild)},v=function(e){function t(){return e.apply(this,arguments)}return t.toString=function(){return e.toString()},t}(function(){var e=t.querySelector(f);return e||(m(),e=v()),e}),y=function(){var e=v();return e?e.querySelector("input"):void 0},h=function(){return t.querySelector(p)},g=function(e,t){var n=s.hexToRgb(t);e.style.boxShadow="0 0 2px rgba("+n+", 0.8), inset 0 0 0 1px rgba(0, 0, 0, 0.05)"},b=function(n){var o=v();l.fadeIn(h(),10),l.show(o),l.addClass(o,"showSweetAlert"),l.removeClass(o,"hideSweetAlert"),e.previousActiveElement=t.activeElement;var a=o.querySelector("button.confirm");a.focus(),setTimeout(function(){l.addClass(o,"visible")},500);var r=o.getAttribute("data-timer");if("null"!==r&&""!==r){var s=n;o.timeout=setTimeout(function(){var e=(s||null)&&"true"===o.getAttribute("data-has-done-function");e?s(null):sweetAlert.close()},r)}},w=function(){var e=v(),t=y();l.removeClass(e,"show-input"),t.value=u["default"].inputValue,t.setAttribute("type",u["default"].inputType),t.setAttribute("placeholder",u["default"].inputPlaceholder),C()},C=function(e){if(e&&13===e.keyCode)return!1;var t=v(),n=t.querySelector(".sa-input-error");l.removeClass(n,"show");var o=t.querySelector(".sa-error-container");l.removeClass(o,"show")},S=function(){var e=v();e.style.marginTop=l.getTopMargin(v())};a.sweetAlertInitialize=m,a.getModal=v,a.getOverlay=h,a.getInput=y,a.setFocusStyle=g,a.openModal=b,a.resetInput=w,a.resetInputError=C,a.fixVerticalPosition=S},{"./default-params":2,"./handle-dom":4,"./injected-html":7,"./utils":9}],7:[function(e,t,n){Object.defineProperty(n,"__esModule",{value:!0});var o='<div class="sweet-overlay" tabIndex="-1"></div><div class="sweet-alert"><div class="sa-icon sa-error">\n      <span class="sa-x-mark">\n        <span class="sa-line sa-left"></span>\n        <span class="sa-line sa-right"></span>\n      </span>\n    </div><div class="sa-icon sa-warning">\n      <span class="sa-body"></span>\n      <span class="sa-dot"></span>\n    </div><div class="sa-icon sa-info"></div><div class="sa-icon sa-success">\n      <span class="sa-line sa-tip"></span>\n      <span class="sa-line sa-long"></span>\n\n      <div class="sa-placeholder"></div>\n      <div class="sa-fix"></div>\n    </div><div class="sa-icon sa-custom"></div><h2>Title</h2>\n    <p>Text</p>\n    <fieldset>\n      <input type="text" tabIndex="3" />\n      <div class="sa-input-error"></div>\n    </fieldset><div class="sa-error-container">\n      <div class="icon">!</div>\n      <p>Not valid!</p>\n    </div><div class="sa-button-container">\n      <button class="cancel" tabIndex="2">Cancel</button>\n      <div class="sa-confirm-button-container">\n        <button class="confirm" tabIndex="1">OK</button><div class="la-ball-fall">\n          <div></div>\n          <div></div>\n          <div></div>\n        </div>\n      </div>\n    </div></div>';n["default"]=o,t.exports=n["default"]},{}],8:[function(e,t,o){Object.defineProperty(o,"__esModule",{value:!0});var a=e("./utils"),r=e("./handle-swal-dom"),s=e("./handle-dom"),l=["error","warning","info","success","input","prompt"],i=function(e){var t=r.getModal(),o=t.querySelector("h2"),i=t.querySelector("p"),u=t.querySelector("button.cancel"),c=t.querySelector("button.confirm");if(o.innerHTML=e.html?e.title:s.escapeHtml(e.title).split("\n").join("<br>"),i.innerHTML=e.html?e.text:s.escapeHtml(e.text||"").split("\n").join("<br>"),e.text&&s.show(i),e.customClass)s.addClass(t,e.customClass),t.setAttribute("data-custom-class",e.customClass);else{var d=t.getAttribute("data-custom-class");s.removeClass(t,d),t.setAttribute("data-custom-class","")}if(s.hide(t.querySelectorAll(".sa-icon")),e.type&&!a.isIE8()){var f=function(){for(var o=!1,a=0;a<l.length;a++)if(e.type===l[a]){o=!0;break}if(!o)return logStr("Unknown alert type: "+e.type),{v:!1};var i=["success","error","warning","info"],u=n;-1!==i.indexOf(e.type)&&(u=t.querySelector(".sa-icon.sa-"+e.type),s.show(u));var c=r.getInput();switch(e.type){case"success":s.addClass(u,"animate"),s.addClass(u.querySelector(".sa-tip"),"animateSuccessTip"),s.addClass(u.querySelector(".sa-long"),"animateSuccessLong");break;case"error":s.addClass(u,"animateErrorIcon"),s.addClass(u.querySelector(".sa-x-mark"),"animateXMark");break;case"warning":s.addClass(u,"pulseWarning"),s.addClass(u.querySelector(".sa-body"),"pulseWarningIns"),s.addClass(u.querySelector(".sa-dot"),"pulseWarningIns");break;case"input":case"prompt":c.setAttribute("type",e.inputType),c.value=e.inputValue,c.setAttribute("placeholder",e.inputPlaceholder),s.addClass(t,"show-input"),setTimeout(function(){c.focus(),c.addEventListener("keyup",swal.resetInputError)},400)}}();if("object"==typeof f)return f.v}if(e.imageUrl){var p=t.querySelector(".sa-icon.sa-custom");p.style.backgroundImage="url("+e.imageUrl+")",s.show(p);var m=80,v=80;if(e.imageSize){var y=e.imageSize.toString().split("x"),h=y[0],g=y[1];h&&g?(m=h,v=g):logStr("Parameter imageSize expects value with format WIDTHxHEIGHT, got "+e.imageSize)}p.setAttribute("style",p.getAttribute("style")+"width:"+m+"px; height:"+v+"px")}t.setAttribute("data-has-cancel-button",e.showCancelButton),e.showCancelButton?u.style.display="inline-block":s.hide(u),t.setAttribute("data-has-confirm-button",e.showConfirmButton),e.showConfirmButton?c.style.display="inline-block":s.hide(c),e.cancelButtonText&&(u.innerHTML=s.escapeHtml(e.cancelButtonText)),e.confirmButtonText&&(c.innerHTML=s.escapeHtml(e.confirmButtonText)),e.confirmButtonColor&&(c.style.backgroundColor=e.confirmButtonColor,c.style.borderLeftColor=e.confirmLoadingButtonColor,c.style.borderRightColor=e.confirmLoadingButtonColor,r.setFocusStyle(c,e.confirmButtonColor)),t.setAttribute("data-allow-outside-click",e.allowOutsideClick);var b=e.doneFunction?!0:!1;t.setAttribute("data-has-done-function",b),e.animation?"string"==typeof e.animation?t.setAttribute("data-animation",e.animation):t.setAttribute("data-animation","pop"):t.setAttribute("data-animation","none"),t.setAttribute("data-timer",e.timer)};o["default"]=i,t.exports=o["default"]},{"./handle-dom":4,"./handle-swal-dom":6,"./utils":9}],9:[function(t,n,o){Object.defineProperty(o,"__esModule",{value:!0});var a=function(e,t){for(var n in t)t.hasOwnProperty(n)&&(e[n]=t[n]);return e},r=function(e){var t=/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(e);return t?parseInt(t[1],16)+", "+parseInt(t[2],16)+", "+parseInt(t[3],16):null},s=function(){return e.attachEvent&&!e.addEventListener},l=function(t){e.console&&e.console.log("SweetAlert: "+t)},i=function(e,t){e=String(e).replace(/[^0-9a-f]/gi,""),e.length<6&&(e=e[0]+e[0]+e[1]+e[1]+e[2]+e[2]),t=t||0;var n,o,a="#";for(o=0;3>o;o++)n=parseInt(e.substr(2*o,2),16),n=Math.round(Math.min(Math.max(0,n+n*t),255)).toString(16),a+=("00"+n).substr(n.length);return a};o.extend=a,o.hexToRgb=r,o.isIE8=s,o.logStr=l,o.colorLuminance=i},{}]},{},[1]),"function"==typeof define&&define.amd?define(function(){return sweetAlert}):"undefined"!=typeof module&&module.exports&&(module.exports=sweetAlert)}(window,document);
		!function(e){"use strict";var t=function(){};t.prototype.init=function(){e("#sa-basic").click(function(){swal("Here's a message!")}),e("#sa-title").click(function(){swal("Here's a message!","Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.")}),e("#sa-success").click(function(){swal("Good job!","Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.","success")}),e("#sa-warning").click(function(){swal({title:"Are you sure?",text:"You will not be able to recover this imaginary file!",type:"warning",showCancelButton:!0,confirmButtonColor:"#DD6B55",confirmButtonText:"Yes, delete it!",closeOnConfirm:!1},function(){swal("Deleted!","Your imaginary file has been deleted.","success")})}),e("#sa-params").click(function(){swal({title:"Are you sure?",text:"You will not be able to recover this imaginary file!",type:"warning",showCancelButton:!0,confirmButtonColor:"#DD6B55",confirmButtonText:"Yes, delete it!",cancelButtonText:"No, cancel plx!",closeOnConfirm:!1,closeOnCancel:!1},function(e){e?swal("Deleted!","Your imaginary file has been deleted.","success"):swal("Cancelled","Your imaginary file is safe :)","error")})}),e("#sa-image").click(function(){swal({title:"Govinda!",text:"Recently joined twitter",imageUrl:"../plugins/images/users/govinda.jpg"})}),e("#sa-close").click(function(){swal({title:"Auto close alert!",text:"I will close in 2 seconds.",timer:2e3,showConfirmButton:!1})})},e.SweetAlert=new t,e.SweetAlert.Constructor=t}(window.jQuery),function(e){"use strict";e.SweetAlert.init()}(window.jQuery),function(e,t,n){"use strict";!function e(t,n,o){function a(s,i){if(!n[s]){if(!t[s]){var l="function"==typeof require&&require;if(!i&&l)return l(s,!0);if(r)return r(s,!0);var u=new Error("Cannot find module '"+s+"'");throw u.code="MODULE_NOT_FOUND",u}var c=n[s]={exports:{}};t[s][0].call(c.exports,function(e){var n=t[s][1][e];return a(n||e)},c,c.exports,e,t,n,o)}return n[s].exports}for(var r="function"==typeof require&&require,s=0;s<o.length;s++)a(o[s]);return a}({1:[function(o,a,r){var s,i,l,u,c=function(e){return e&&e.__esModule?e:{default:e}},d=o("./modules/handle-dom"),f=o("./modules/utils"),p=o("./modules/handle-swal-dom"),m=o("./modules/handle-click"),v=c(o("./modules/handle-key")),y=c(o("./modules/default-params")),g=c(o("./modules/set-params"));(l=u=function(){var o=arguments[0];function a(e){var t=o;return t[e]===n?y.default[e]:t[e]}if(d.addClass(t.body,"stop-scrolling"),p.resetInput(),o===n)return f.logStr("SweetAlert expects at least 1 attribute!"),!1;var r=f.extend({},y.default);switch(typeof o){case"string":r.title=o,r.text=arguments[1]||"",r.type=arguments[2]||"";break;case"object":if(o.title===n)return f.logStr('Missing "title" argument!'),!1;r.title=o.title;for(var l in y.default)r[l]=a(l);r.confirmButtonText=r.showCancelButton?"Confirm":y.default.confirmButtonText,r.confirmButtonText=a("confirmButtonText"),r.doneFunction=arguments[1]||null;break;default:return f.logStr('Unexpected type of argument! Expected "string" or "object", got '+typeof o),!1}g.default(r),p.fixVerticalPosition(),p.openModal(arguments[1]);for(var c=p.getModal(),b=c.querySelectorAll("button"),h=["onclick","onmouseover","onmouseout","onmousedown","onmouseup","onfocus"],w=function(e){return m.handleButton(e,r,c)},C=0;C<b.length;C++)for(var S=0;S<h.length;S++){var x=h[S];b[C][x]=w}p.getOverlay().onclick=w,s=e.onkeydown;e.onkeydown=function(e){return v.default(e,r,c)},e.onfocus=function(){setTimeout(function(){i!==n&&(i.focus(),i=n)},0)},u.enableButtons()}).setDefaults=u.setDefaults=function(e){if(!e)throw new Error("userParams is required");if("object"!=typeof e)throw new Error("userParams has to be a object");f.extend(y.default,e)},l.close=u.close=function(){var o=p.getModal();d.fadeOut(p.getOverlay(),5),d.fadeOut(o,5),d.removeClass(o,"showSweetAlert"),d.addClass(o,"hideSweetAlert"),d.removeClass(o,"visible");var a=o.querySelector(".sa-icon.sa-success");d.removeClass(a,"animate"),d.removeClass(a.querySelector(".sa-tip"),"animateSuccessTip"),d.removeClass(a.querySelector(".sa-long"),"animateSuccessLong");var r=o.querySelector(".sa-icon.sa-error");d.removeClass(r,"animateErrorIcon"),d.removeClass(r.querySelector(".sa-x-mark"),"animateXMark");var l=o.querySelector(".sa-icon.sa-warning");return d.removeClass(l,"pulseWarning"),d.removeClass(l.querySelector(".sa-body"),"pulseWarningIns"),d.removeClass(l.querySelector(".sa-dot"),"pulseWarningIns"),setTimeout(function(){var e=o.getAttribute("data-custom-class");d.removeClass(o,e)},300),d.removeClass(t.body,"stop-scrolling"),e.onkeydown=s,e.previousActiveElement&&e.previousActiveElement.focus(),i=n,clearTimeout(o.timeout),!0},l.showInputError=u.showInputError=function(e){var t=p.getModal(),n=t.querySelector(".sa-input-error");d.addClass(n,"show");var o=t.querySelector(".sa-error-container");d.addClass(o,"show"),o.querySelector("p").innerHTML=e,setTimeout(function(){l.enableButtons()},1),t.querySelector("input").focus()},l.resetInputError=u.resetInputError=function(e){if(e&&13===e.keyCode)return!1;var t=p.getModal(),n=t.querySelector(".sa-input-error");d.removeClass(n,"show");var o=t.querySelector(".sa-error-container");d.removeClass(o,"show")},l.disableButtons=u.disableButtons=function(e){var t=p.getModal(),n=t.querySelector("button.confirm"),o=t.querySelector("button.cancel");n.disabled=!0,o.disabled=!0},l.enableButtons=u.enableButtons=function(e){var t=p.getModal(),n=t.querySelector("button.confirm"),o=t.querySelector("button.cancel");n.disabled=!1,o.disabled=!1},void 0!==e?e.sweetAlert=e.swal=l:f.logStr("SweetAlert is a frontend module!")},{"./modules/default-params":2,"./modules/handle-click":3,"./modules/handle-dom":4,"./modules/handle-key":5,"./modules/handle-swal-dom":6,"./modules/set-params":8,"./modules/utils":9}],2:[function(e,t,n){Object.defineProperty(n,"__esModule",{value:!0});n.default={title:"",text:"",type:null,allowOutsideClick:!1,showConfirmButton:!0,showCancelButton:!1,closeOnConfirm:!0,closeOnCancel:!0,confirmButtonText:"OK",confirmButtonColor:"#8CD4F5",cancelButtonText:"Cancel",imageUrl:null,imageSize:null,timer:null,customClass:"",html:!1,animation:!0,allowEscapeKey:!0,inputType:"text",inputPlaceholder:"",inputValue:"",showLoaderOnConfirm:!1},t.exports=n.default},{}],3:[function(t,n,o){Object.defineProperty(o,"__esModule",{value:!0});var a=t("./utils"),r=(t("./handle-swal-dom"),t("./handle-dom")),s=function(e,t){var n=!0;r.hasClass(e,"show-input")&&((n=e.querySelector("input").value)||(n="")),t.doneFunction(n),t.closeOnConfirm&&sweetAlert.close(),t.showLoaderOnConfirm&&sweetAlert.disableButtons()},i=function(e,t){var n=String(t.doneFunction).replace(/\s/g,"");"function("===n.substring(0,9)&&")"!==n.substring(9,10)&&t.doneFunction(!1),t.closeOnCancel&&sweetAlert.close()};o.default={handleButton:function(t,n,o){var l,u,c,d=t||e.event,f=d.target||d.srcElement,p=-1!==f.className.indexOf("confirm"),m=-1!==f.className.indexOf("sweet-overlay"),v=r.hasClass(o,"visible"),y=n.doneFunction&&"true"===o.getAttribute("data-has-done-function");function g(e){p&&n.confirmButtonColor&&(f.style.backgroundColor=e)}switch(p&&n.confirmButtonColor&&(l=n.confirmButtonColor,u=a.colorLuminance(l,-.04),c=a.colorLuminance(l,-.14)),d.type){case"mouseover":g(u);break;case"mouseout":g(l);break;case"mousedown":g(c);break;case"mouseup":g(u);break;case"focus":var b=o.querySelector("button.confirm"),h=o.querySelector("button.cancel");p?h.style.boxShadow="none":b.style.boxShadow="none";break;case"click":var w=o===f,C=r.isDescendant(o,f);if(!w&&!C&&v&&!n.allowOutsideClick)break;p&&y&&v?s(o,n):y&&v||m?i(o,n):r.isDescendant(o,f)&&"BUTTON"===f.tagName&&sweetAlert.close()}},handleConfirm:s,handleCancel:i},n.exports=o.default},{"./handle-dom":4,"./handle-swal-dom":6,"./utils":9}],4:[function(n,o,a){Object.defineProperty(a,"__esModule",{value:!0});var r=function(e,t){return new RegExp(" "+t+" ").test(" "+e.className+" ")},s=function(e){e.style.opacity="",e.style.display="block"},i=function(e){e.style.opacity="",e.style.display="none"};a.hasClass=r,a.addClass=function(e,t){r(e,t)||(e.className+=" "+t)},a.removeClass=function(e,t){var n=" "+e.className.replace(/[\t\r\n]/g," ")+" ";if(r(e,t)){for(;n.indexOf(" "+t+" ")>=0;)n=n.replace(" "+t+" "," ");e.className=n.replace(/^\s+|\s+$/g,"")}},a.escapeHtml=function(e){var n=t.createElement("div");return n.appendChild(t.createTextNode(e)),n.innerHTML},a._show=s,a.show=function(e){if(e&&!e.length)return s(e);for(var t=0;t<e.length;++t)s(e[t])},a._hide=i,a.hide=function(e){if(e&&!e.length)return i(e);for(var t=0;t<e.length;++t)i(e[t])},a.isDescendant=function(e,t){for(var n=t.parentNode;null!==n;){if(n===e)return!0;n=n.parentNode}return!1},a.getTopMargin=function(e){e.style.left="-9999px",e.style.display="block";var t,n=e.clientHeight;return t="undefined"!=typeof getComputedStyle?parseInt(getComputedStyle(e).getPropertyValue("padding-top"),10):parseInt(e.currentStyle.padding),e.style.left="",e.style.display="none","-"+parseInt((n+t)/2)+"px"},a.fadeIn=function(e,t){if(+e.style.opacity<1){t=t||16,e.style.opacity=0,e.style.display="block";var n=+new Date,o=function(e){function t(){return e.apply(this,arguments)}return t.toString=function(){return e.toString()},t}(function(){e.style.opacity=+e.style.opacity+(new Date-n)/100,n=+new Date,+e.style.opacity<1&&setTimeout(o,t)});o()}e.style.display="block"},a.fadeOut=function(e,t){t=t||16,e.style.opacity=1;var n=+new Date,o=function(e){function t(){return e.apply(this,arguments)}return t.toString=function(){return e.toString()},t}(function(){e.style.opacity=+e.style.opacity-(new Date-n)/100,n=+new Date,+e.style.opacity>0?setTimeout(o,t):e.style.display="none"});o()},a.fireClick=function(n){if("function"==typeof MouseEvent){var o=new MouseEvent("click",{view:e,bubbles:!1,cancelable:!0});n.dispatchEvent(o)}else if(t.createEvent){var a=t.createEvent("MouseEvents");a.initEvent("click",!1,!1),n.dispatchEvent(a)}else t.createEventObject?n.fireEvent("onclick"):"function"==typeof n.onclick&&n.onclick()},a.stopEventPropagation=function(t){"function"==typeof t.stopPropagation?(t.stopPropagation(),t.preventDefault()):e.event&&e.event.hasOwnProperty("cancelBubble")&&(e.event.cancelBubble=!0)}},{}],5:[function(t,o,a){Object.defineProperty(a,"__esModule",{value:!0});var r=t("./handle-dom"),s=t("./handle-swal-dom");a.default=function(t,o,a){var i=t||e.event,l=i.keyCode||i.which,u=a.querySelector("button.confirm"),c=a.querySelector("button.cancel"),d=a.querySelectorAll("button[tabindex]");if(-1!==[9,13,32,27].indexOf(l)){for(var f=i.target||i.srcElement,p=-1,m=0;m<d.length;m++)if(f===d[m]){p=m;break}9===l?(f=-1===p?u:p===d.length-1?d[0]:d[p+1],r.stopEventPropagation(i),f.focus(),o.confirmButtonColor&&s.setFocusStyle(f,o.confirmButtonColor)):13===l?("INPUT"===f.tagName&&(f=u,u.focus()),f=-1===p?u:n):27===l&&!0===o.allowEscapeKey?(f=c,r.fireClick(f,i)):f=n}},o.exports=a.default},{"./handle-dom":4,"./handle-swal-dom":6}],6:[function(n,o,a){var r=function(e){return e&&e.__esModule?e:{default:e}};Object.defineProperty(a,"__esModule",{value:!0});var s=n("./utils"),i=n("./handle-dom"),l=r(n("./default-params")),u=r(n("./injected-html")),c=function(){var e=t.createElement("div");for(e.innerHTML=u.default;e.firstChild;)t.body.appendChild(e.firstChild)},d=function(e){function t(){return e.apply(this,arguments)}return t.toString=function(){return e.toString()},t}(function(){var e=t.querySelector(".sweet-alert");return e||(c(),e=d()),e}),f=function(){var e=d();if(e)return e.querySelector("input")},p=function(){return t.querySelector(".sweet-overlay")},m=function(e){if(e&&13===e.keyCode)return!1;var t=d(),n=t.querySelector(".sa-input-error");i.removeClass(n,"show");var o=t.querySelector(".sa-error-container");i.removeClass(o,"show")};a.sweetAlertInitialize=c,a.getModal=d,a.getOverlay=p,a.getInput=f,a.setFocusStyle=function(e,t){var n=s.hexToRgb(t);e.style.boxShadow="0 0 2px rgba("+n+", 0.8), inset 0 0 0 1px rgba(0, 0, 0, 0.05)"},a.openModal=function(n){var o=d();i.fadeIn(p(),10),i.show(o),i.addClass(o,"showSweetAlert"),i.removeClass(o,"hideSweetAlert"),e.previousActiveElement=t.activeElement,o.querySelector("button.confirm").focus(),setTimeout(function(){i.addClass(o,"visible")},500);var a=o.getAttribute("data-timer");if("null"!==a&&""!==a){var r=n;o.timeout=setTimeout(function(){r&&"true"===o.getAttribute("data-has-done-function")?r(null):sweetAlert.close()},a)}},a.resetInput=function(){var e=d(),t=f();i.removeClass(e,"show-input"),t.value=l.default.inputValue,t.setAttribute("type",l.default.inputType),t.setAttribute("placeholder",l.default.inputPlaceholder),m()},a.resetInputError=m,a.fixVerticalPosition=function(){d().style.marginTop=i.getTopMargin(d())}},{"./default-params":2,"./handle-dom":4,"./injected-html":7,"./utils":9}],7:[function(e,t,n){Object.defineProperty(n,"__esModule",{value:!0});n.default='<div class="sweet-overlay" tabIndex="-1"></div><div class="sweet-alert"><div class="sa-icon sa-error">\n      <span class="sa-x-mark">\n        <span class="sa-line sa-left"></span>\n        <span class="sa-line sa-right"></span>\n      </span>\n    </div><div class="sa-icon sa-warning">\n      <span class="sa-body"></span>\n      <span class="sa-dot"></span>\n    </div><div class="sa-icon sa-info"></div><div class="sa-icon sa-success">\n      <span class="sa-line sa-tip"></span>\n      <span class="sa-line sa-long"></span>\n\n      <div class="sa-placeholder"></div>\n      <div class="sa-fix"></div>\n    </div><div class="sa-icon sa-custom"></div><h2>Title</h2>\n    <p>Text</p>\n    <fieldset>\n      <input type="text" tabIndex="3" />\n      <div class="sa-input-error"></div>\n    </fieldset><div class="sa-error-container">\n      <div class="icon">!</div>\n      <p>Not valid!</p>\n    </div><div class="sa-button-container">\n      <button class="cancel" tabIndex="2">Cancel</button>\n      <div class="sa-confirm-button-container">\n        <button class="confirm" tabIndex="1">OK</button><div class="la-ball-fall">\n          <div></div>\n          <div></div>\n          <div></div>\n        </div>\n      </div>\n    </div></div>',t.exports=n.default},{}],8:[function(e,t,o){Object.defineProperty(o,"__esModule",{value:!0});var a=e("./utils"),r=e("./handle-swal-dom"),s=e("./handle-dom"),i=["error","warning","info","success","input","prompt"];o.default=function(e){var t=r.getModal(),o=t.querySelector("h2"),l=t.querySelector("p"),u=t.querySelector("button.cancel"),c=t.querySelector("button.confirm");if(o.innerHTML=e.html?e.title:s.escapeHtml(e.title).split("\n").join("<br>"),l.innerHTML=e.html?e.text:s.escapeHtml(e.text||"").split("\n").join("<br>"),e.text&&s.show(l),e.customClass)s.addClass(t,e.customClass),t.setAttribute("data-custom-class",e.customClass);else{var d=t.getAttribute("data-custom-class");s.removeClass(t,d),t.setAttribute("data-custom-class","")}if(s.hide(t.querySelectorAll(".sa-icon")),e.type&&!a.isIE8()){var f=function(){for(var o=!1,a=0;a<i.length;a++)if(e.type===i[a]){o=!0;break}if(!o)return logStr("Unknown alert type: "+e.type),{v:!1};var l=n;-1!==["success","error","warning","info"].indexOf(e.type)&&(l=t.querySelector(".sa-icon.sa-"+e.type),s.show(l));var u=r.getInput();switch(e.type){case"success":s.addClass(l,"animate"),s.addClass(l.querySelector(".sa-tip"),"animateSuccessTip"),s.addClass(l.querySelector(".sa-long"),"animateSuccessLong");break;case"error":s.addClass(l,"animateErrorIcon"),s.addClass(l.querySelector(".sa-x-mark"),"animateXMark");break;case"warning":s.addClass(l,"pulseWarning"),s.addClass(l.querySelector(".sa-body"),"pulseWarningIns"),s.addClass(l.querySelector(".sa-dot"),"pulseWarningIns");break;case"input":case"prompt":u.setAttribute("type",e.inputType),u.value=e.inputValue,u.setAttribute("placeholder",e.inputPlaceholder),s.addClass(t,"show-input"),setTimeout(function(){u.focus(),u.addEventListener("keyup",swal.resetInputError)},400)}}();if("object"==typeof f)return f.v}if(e.imageUrl){var p=t.querySelector(".sa-icon.sa-custom");p.style.backgroundImage="url("+e.imageUrl+")",s.show(p);var m=80,v=80;if(e.imageSize){var y=e.imageSize.toString().split("x"),g=y[0],b=y[1];g&&b?(m=g,v=b):logStr("Parameter imageSize expects value with format WIDTHxHEIGHT, got "+e.imageSize)}p.setAttribute("style",p.getAttribute("style")+"width:"+m+"px; height:"+v+"px")}t.setAttribute("data-has-cancel-button",e.showCancelButton),e.showCancelButton?u.style.display="inline-block":s.hide(u),t.setAttribute("data-has-confirm-button",e.showConfirmButton),e.showConfirmButton?c.style.display="inline-block":s.hide(c),e.cancelButtonText&&(u.innerHTML=s.escapeHtml(e.cancelButtonText)),e.confirmButtonText&&(c.innerHTML=s.escapeHtml(e.confirmButtonText)),e.confirmButtonColor&&(c.style.backgroundColor=e.confirmButtonColor,c.style.borderLeftColor=e.confirmLoadingButtonColor,c.style.borderRightColor=e.confirmLoadingButtonColor,r.setFocusStyle(c,e.confirmButtonColor)),t.setAttribute("data-allow-outside-click",e.allowOutsideClick);var h=!!e.doneFunction;t.setAttribute("data-has-done-function",h),e.animation?"string"==typeof e.animation?t.setAttribute("data-animation",e.animation):t.setAttribute("data-animation","pop"):t.setAttribute("data-animation","none"),t.setAttribute("data-timer",e.timer)},t.exports=o.default},{"./handle-dom":4,"./handle-swal-dom":6,"./utils":9}],9:[function(t,n,o){Object.defineProperty(o,"__esModule",{value:!0});o.extend=function(e,t){for(var n in t)t.hasOwnProperty(n)&&(e[n]=t[n]);return e},o.hexToRgb=function(e){var t=/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(e);return t?parseInt(t[1],16)+", "+parseInt(t[2],16)+", "+parseInt(t[3],16):null},o.isIE8=function(){return e.attachEvent&&!e.addEventListener},o.logStr=function(t){e.console&&e.console.log("SweetAlert: "+t)},o.colorLuminance=function(e,t){(e=String(e).replace(/[^0-9a-f]/gi,"")).length<6&&(e=e[0]+e[0]+e[1]+e[1]+e[2]+e[2]),t=t||0;var n,o,a="#";for(o=0;o<3;o++)n=parseInt(e.substr(2*o,2),16),a+=("00"+(n=Math.round(Math.min(Math.max(0,n+n*t),255)).toString(16))).substr(n.length);return a}},{}]},{},[1]),"function"==typeof define&&define.amd?define(function(){return sweetAlert}):"undefined"!=typeof module&&module.exports&&(module.exports=sweetAlert)}(window,document);
		</script>
		<?php
	}
	if($this->request->action == 'registration')
	{
		?>
		<style>
		.page-header-fixed:not(.page-sidebar-fixed):not(.page-horizontal-bar) .page-inner{
			padding-top:0px !important;
		}
		#main-wrapper{
			margin:0px !important;
		}
		</style>
		<?php
	}
	?>
	<style>
	.noactive{
		background: hsl(218, 29%, 24%) none repeat scroll 0 0!important;
		color: #FFFFFF!important;
		border-left: 3px solid #899dc1;
	}
	li.noactive>a:hover{
		border: medium none!important;
	}
	.affix {
		position: fixed;
		top: 0;
		width: 100%;
		background:#FFFFFF;
		z-index: 999999;
		border-radius: 0;
	}
	#loginlist{margin-bottom: 0px;}
	</style>
	
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('webroot/script') ?>
</head>
<body class="page-header-fixed pace-done">
	<main class="page-content content-wrap">
	<?php
	
	use Cake\Controller\Component;
	use Cake\ORM\TableRegistry;

	$name='';
	$controller_name=$this->request->params['controller'];
	$action_name=$this->request->params['action'];
	
	$user_id=$this->request->session()->read('user_id');

	$logo = isset($logo)?$logo:"";
	
	if(!empty($user_id))
	{ 
		$get_role=$this->Setting->get_user_role($user_id);
		$name=$this->Setting->get_user_id($user_id);
	?>
	<div class="navbar" data-spy="" data-offset-top="">
		<div class="navbar-inner">
			<div class="sidebar-pusher">
				<a class="waves-effect waves-button waves-classic push-sidebar" href="javascript:void(0);">
					<i class="fa fa-bars"></i>
				</a>
			</div>  
			<div class="search-button">
				<a class="waves-effect waves-button waves-classic show-search" href="javascript:void(0);"><i class="fa fa-search"></i></a>
			</div>
            <div class="topmenu-outer">
				<div class="top-menu">
					<div class="col-md-8 col-sm-9 col-xs-10">
						<div class="row">
						<ul class="nav navbar-nav navbar-left">
							<li>
								<div class="page-title">
									<h3>										
										<span class="logo">
											<a href="<?php echo $this->request->base;?>" style="float: left;width: 100%;cursor: pointer;">
											<?php	
												echo $this->Html->image($logo, ['style'=>'']);?>
											</a>
										</span>											
										<div class="school_subname">
											<font><?php echo __("$school_name");?> </font>
										</div>
									</h3>
								</div>
							</li>                       
                        </ul>
						</div>
					</div>
                    <ul class="nav navbar-nav navbar-right col-md-4 col-sm-3 col-xs-2">                      
						<li class="dropdown hidden-md hidden-sm hidden-lg">
							<a data-toggle="dropdown" class="dropdown-toggle waves-effect waves-button waves-classic" href="#" style="text-align: center;">
							<span class="user-name1">
								<i class="fa fa-cog" style="font-size: 25px;"></i>
							</span>
							</a>
							<ul role="menu" class="dropdown-menu dropdown-list" style="min-width: 53px;float: right;position: unset;text-align: center;">
								<li role="presentation">
									<?php $url = $this->request->base."/Comman/account";?>
									<a href="<?php echo $url;?>" style="padding: 5px;" data-toggle="tooltip" data-placement="left" title="Profile">
										<i class="fa fa-user" style="font-size: 25px;margin: 0px;"></i>
									</a>
								</li>
								<li role="presentation">
									<?php					
									if($get_role == 'student' || $get_role == 'teacher')
										$url = $this->request->base."/User/user";
									else
										$url = $this->request->base."/Changepassword/changepassword";
									?>
									<a href="<?php echo $url;?>" style="padding: 5px;" data-toggle="tooltip" data-placement="left" title="Change Password">
										<i class="fa fa-key" style="font-size: 25px;margin: 0px;"></i>
									</a>
								</li>
								<li role="presentation">
									<a href="<?php echo $this->request->base;?>/User/logout" style="padding: 5px;" data-toggle="tooltip" data-placement="left" title="Logout">
										<i class="fa fa-sign-out" style="font-size: 25px;margin: 0px;"></i>
									</a>
								</li>
							</ul>
                        </li> 
						
						<li class="dropdown hidden-xs">
							<a data-toggle="dropdown" class="dropdown-toggle waves-effect waves-button waves-classic" href="#">
							<?php
							$user_id=$this->request->session()->read('user_id');
							/* $user=$this->request->session()->read('user'); */
							$user=$this->Setting->get_user_id($user_id);
							/* $image=$this->request->session()->read('image'); */
							$image=$this->Setting->get_user_image($user_id);
											
							?>
							<span class="user-name">
							<?php
							if(!empty($user_id))
							{
								echo $this->Html->image($image, ['class' => 'img-circle avatar','id'=>'profileimg','width'=>'40','height'=>'40']);
							}
							if(!empty($user_id))
							{
								?>
								<span id="username"><?php echo $name;?></span>
					<?php	}
							?><i class="fa fa-angle-down"></i>
							</span>
							</a>
                            <ul role="menu" class="dropdown-menu dropdown-list">
								<li role="presentation">
								<?php  
								if(!empty($user_id))
								{
									echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-user')) . __('Profile'),['controller' => 'comman', 'action' => 'account'],['escape' => false]);
								}
								if($get_role == 'student' || $get_role == 'teacher')
								{ ?>
								<li role="presentation"><?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-key m-r-xs')) . __('Change Password'),['controller' => 'User', 'action' => 'user'],['escape' => false]);?>
								<?php
								}
								else{ ?>
								<li role="presentation"><?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-key m-r-xs')) . __('Change Password'),['controller' => 'Changepassword', 'action' => 'changepassword'],['escape' => false]);?>
								<?php } ?>	
								<li role="presentation"><?php  echo $this->Html->link($this->Html->tag('i', '  ', array('class' => 'fa fa-sign-out m-r-xs')) . __('Log out'),['controller' => 'User', 'action' => 'logout'],['escape' => false]);?>
                            </ul>
                        </li>                       
					</ul><!-- Nav -->
				</div><!-- Top Menu -->
			</div>
		</div>
	</div>
	<?php
	if(isset($get_role))
	{
		if($get_role == 'teacher')
		{
			$getteachermenu=TableRegistry::get('tblteachermenu');
            $get_all_menu_teacher=$getteachermenu->find();
			?>
            <div class="page-sidebar sidebar">
				<div class="slimScrollDiv" style="position: relative; float: left !important;overflow: hidden; width: 100%; height: 100%;">
				<div class="page-sidebar-inner slimscroll" style="overflow: hidden; width: auto; height: 100%;">
                    <ul class="menu accordion-menu">
						<li>
							<span class="menu-icon"><?php echo $this->Html->image('icons/dashboard.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
							<?php echo $this->Html->link(__('Dashboard'),['controller' => 'Templet', 'action' => 'teacherdash'],['class'=>checkul('Templet',$controller_name)]);?></li>
						</li>
						<?php
						foreach($get_all_menu_teacher as $teacher_menu):
							if($teacher_menu['teacher_approve'] == '0')
							{

							}
							else if($teacher_menu['teacher_approve']  == '1')
							{
							?>
							<li>
								<span class="menu-icon"><?php echo $this->Html->image("icons/".$teacher_menu['icon']);?></span>
								<?php echo $this->Html->link(__($teacher_menu['menu_name']),['controller' => $teacher_menu['controller_name'], 'action' =>$teacher_menu['action_name']],['class'=>checkul($teacher_menu['action_name'],$action_name)]);?>
							</li>
							<?php
							}
                        endforeach;
                        ?>
                    </ul>
                </div>
            </div>
			<?php
		}
		if($get_role == 'supportstaff')
		{
			$getteachermenu=TableRegistry::get('tblteachermenu');
            $get_all_menu_teacher=$getteachermenu->find();
		?>
        <div class="page-sidebar sidebar">
			<div class="slimScrollDiv" style="position: relative; float: left !important;overflow: hidden; width: 100%; height: 100%;">
			<div class="page-sidebar-inner slimscroll" style="overflow: hidden; width: auto; height: 100%;">

					 <ul class="menu accordion-menu">

						<li>
							<span class="menu-icon"><?php echo $this->Html->image('icons/dashboard.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
							<?php echo $this->Html->link(__('Dashboard'),['controller' => 'Templet', 'action' => 'supportstaffdash'],['class'=>checkul('Templet',$controller_name)]);?></li>
						</li>

							<?php
							foreach($get_all_menu_teacher as $staff_menu):


								if($staff_menu['staff_approve'] == '0'){

								}else if($staff_menu['staff_approve']  == '1'){

							?>

						<li>
							<span class="menu-icon"><?php echo $this->Html->image("icons/".$staff_menu['icon']);?></span>
                            <?php echo $this->Html->link(__($staff_menu['menu_name']),['controller' => $staff_menu['controller_name'], 'action' =>$staff_menu['action_name']],['class'=>checkul($staff_menu['action_name'],$action_name)]);?>
                        </li>

                        <?php
                    }
                        endforeach;
                        ?>



                    </ul>
                </div>
            </div>
				
                 <?php
                    }
			if($get_role == 'student'){
                    	$getteachermenu=TableRegistry::get('tblteachermenu');
                	$get_all_menu_teacher=$getteachermenu->find();

                        ?>
              <div class="page-sidebar sidebar">
               <div class="slimScrollDiv" style="position: relative; float: left !important;overflow: hidden; width: 100%; height: 100%;">
			  <div class="page-sidebar-inner slimscroll" style="overflow: hidden; width: auto; height: 100%;">

					 <ul class="menu accordion-menu">

						<li>
							<span class="menu-icon"><?php echo $this->Html->image('icons/dashboard.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
							<?php echo $this->Html->link(__('Dashboard'),['controller' => 'Templet', 'action' => 'studentdash'],['class'=>checkul('Templet',$controller_name)]);?></li>
						</li>

							<?php
							foreach($get_all_menu_teacher as $student_menu):


								if($student_menu['student_approve'] == '0'){

								}else if($student_menu['student_approve']  == '1'){

							?>

						<li>
							<span class="menu-icon"><?php echo $this->Html->image("icons/".$student_menu['icon']);?></span>
                            <?php echo $this->Html->link(__($student_menu['menu_name']),['controller' => $student_menu['controller_name'], 'action' =>$student_menu['action_name']],['class'=>checkul($student_menu['action_name'],$action_name)]);?>
                        </li>

                        <?php
                    }
                        endforeach;
                        ?>



                    </ul>
                </div>
            </div>
				
				<?php
                    }
			if($get_role == 'parent'){
                    	$getteachermenu=TableRegistry::get('tblteachermenu');
                	$get_all_menu_teacher=$getteachermenu->find();

                        ?>
                    <div class="page-sidebar sidebar">
               <div class="slimScrollDiv" style="position: relative; float: left !important;overflow: hidden; width: 100%; height: 100%;">
			  <div class="page-sidebar-inner slimscroll" style="overflow: hidden; width: auto; height: 100%;">
					 <ul class="menu accordion-menu">
					
						<li>
							<span class="menu-icon"><?php echo $this->Html->image('icons/dashboard.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
							<?php echo $this->Html->link(__('Dashboard'),['controller' => 'Templet', 'action' => 'parentdash'],['class'=>checkul('Templet',$controller_name)]);?></li>
						</li>

							<?php
							foreach($get_all_menu_teacher as $parent_menu):


								if($parent_menu['parent_approve'] == '0'){

								}else if($parent_menu['parent_approve']  == '1'){

							?>
						
						<li>
							<span class="menu-icon"><?php echo $this->Html->image("icons/".$parent_menu['icon']);?></span>                         
							<?php 
							
									echo $this->Html->link(__($parent_menu['menu_name']),['controller' => $parent_menu['controller_name'], 'action' =>$parent_menu['action_name']],['class'=>checkul($parent_menu['action_name'],$action_name)]);
								
								?></li>
						</li>

                        <?php
                    }
                        endforeach;
                        ?>



                    </ul>
                </div>
            </div>
				
                 <?php
                    }

                }

        ?>

        <?php
            if(isset($get_role)){
                if($get_role == 'admin'){

             ?>


			<div class="page-sidebar sidebar">
				<div class="slimScrollDiv" style="position: relative; float: left !important;overflow: hidden; width: 100%; height: 100%;">
					<div class="page-sidebar-inner slimscroll" style="overflow: hidden; width: auto; height: 100%;">
						<ul class="menu accordion-menu">
							<li>
								<span class="menu-icon"><?php echo $this->Html->image('icons/dashboard.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
								<?php echo $this->Html->link(__('Dashboard'),['controller' => 'Templet', 'action' => 'templet'],['class'=>checkul('Templet',$controller_name)]);?>
							</li>
							
							<li>
								<span class="menu-icon"><?php echo $this->Html->image('icons/admission.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
								<?php echo $this->Html->link(__('Admission'),['controller' => 'Admission', 'action' => 'admissionlist'],['class'=>checkul('Admission',$controller_name)]);?>
							</li>
							
							<li id="member-setting" class="<?php echo checkul("Student",$controller_name)
																	  ." ".checkul("Teacher",$controller_name)
																	  ." ".checkul("Parent",$controller_name)
																	  ." ".checkul("Staff",$controller_name);?>">
								<span class="menu-icon">
									<?php echo $this->Html->image('icons/member.png');?>  
								</span>
								<a style="cursor: pointer;"><?= __('Members')?><i class="fa fa-angle-right pull-right right-arrow"></i></a>
							</li>  
							<div id="member-setting-item" style="display:none;">
								<li>
									<span class="menu-icon"><?php echo $this->Html->image('icons/student-icon.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
									<?php echo $this->Html->link(__('Student'),['controller' => 'Student', 'action' => 'studentlist'],['class'=>checkul('Student',$controller_name)]);?>
								</li>	
								<li>
									<span class="menu-icon"><?php echo $this->Html->image('icons/teacher.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
									<?php echo $this->Html->link(__('Teacher'),['controller' => 'Teacher', 'action' => 'teacherlist'],['class'=>checkul("Teacher",$controller_name)]);?>
								</li>
								<li>
									<span class="menu-icon"><?php echo $this->Html->image('icons/parents.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
									<?php echo $this->Html->link(__('Parent'),['controller' => 'Parent', 'action' => 'parentlist'],['class'=>checkul("Parent",$controller_name)]);?>
								</li>
								<li>
									<span class="menu-icon"><?php echo $this->Html->image('icons/staff.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
									<?php echo $this->Html->link(__('Support Staff'),['controller' => 'Staff', 'action' => 'stafflist'],['class'=>checkul("Staff",$controller_name)]);?>
								</li>
							</div>																
							
							<li>
								<span class="menu-icon"><?php echo $this->Html->image('icons/class.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
								<?php echo $this->Html->link(__('Class'),['controller' => 'Classmgt', 'action' => 'classlist'],['class'=>checkul("Classmgt",$controller_name)]);?>
							</li>
														
							<li>
								<span class="menu-icon"><?php echo $this->Html->image('icons/subject.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
								<?php echo $this->Html->link(__('Subject'),['controller' => 'Subject', 'action' => 'subjectlist'],['class'=>checkul("Subject",$controller_name)]);?>
							</li>
							
							<li>
								<span class="menu-icon"><?php echo $this->Html->image('icons/class-route.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
								<?php echo $this->Html->link(__('Class Routine'),['controller' => 'Classroute', 'action' => 'classroutelist'],['class'=>checkul("Classroute",$controller_name)]);?>
							</li>
							
							<li>
								<span class="menu-icon"><?php echo $this->Html->image('icons/attandance.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
								<?php echo $this->Html->link(__('Attendance'),['controller' => 'Attendance', 'action' => 'attendance'],['class'=>checkul("Attendance",$controller_name)]);?>
							</li>
							
							<li>
								<span class="menu-icon"><?php echo $this->Html->image('icons/exam.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
								<?php echo $this->Html->link(__('Exam'),['controller' => 'Exam', 'action' => 'examlist'],['class'=>checkul("Exam",$controller_name)]);?>
							</li>
							
							<li>
								<span class="menu-icon"><?php echo $this->Html->image('icons/mark-manage.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
								<?php echo $this->Html->link(__('Mark Manage'),['controller' => 'Marks', 'action' => 'addmarks'],['class'=>checkul("Marks",$controller_name)]);?>
							</li>
							
							<li>
								<span class="menu-icon"><?php echo $this->Html->image('icons/grade.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
								<?php echo $this->Html->link(__('Grade'),['controller' => 'Grade', 'action' => 'gradelist'],['class'=>checkul("Grade",$controller_name)]);?>
							</li>
							
							<li>
								<span class="menu-icon"><?php echo $this->Html->image('icons/homework.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
								<?php echo $this->Html->link(__('Homework'),['controller' => 'Homework', 'action' => 'homeworklist'],['class'=>checkul("Homework",$controller_name)]);?>
							</li>
							
							<li id="reminder-setting" class="<?php echo checkul("Message",$controller_name)
																	." ".checkul("Notice",$controller_name)
																	." ".checkul("News",$controller_name)
																	." ".checkul("Event",$controller_name)
																	." ".checkul("Holiday",$controller_name);
																	?>">
								<span class="menu-icon">
									<?php echo $this->Html->image('icons/notice.png');?>  
								</span>
								<a style="cursor: pointer;"><?= __('Reminder')?><i class="fa fa-angle-right pull-right right-arrow"></i></a>
							</li>  
							<div id="reminder-setting-item" style="display:none;">
								<li>
									<span class="menu-icon"><?php echo $this->Html->image('icons/message.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
									<?php echo $this->Html->link(__('Message'),['controller' => 'Message', 'action' => 'inbox'],['class'=>checkul("Message",$controller_name)]);?>
								</li>							
								<li>
									<span class="menu-icon"><?php echo $this->Html->image('icons/notice.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
									<?php echo $this->Html->link(__('Notice'),['controller' => 'Notice', 'action' => 'noticelist'],['class'=>checkul("Notice",$controller_name)]);?>
								</li>	
								<li>
									<span class="menu-icon"><?php echo $this->Html->image('icons/news.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
									<?php echo $this->Html->link(__('News'),['controller' => 'News', 'action' => 'newslist'],['class'=>checkul("News",$controller_name)]);?>
								</li>							
								<li>
									<span class="menu-icon"><?php echo $this->Html->image('icons/event.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
									<?php echo $this->Html->link(__('Event'),['controller' => 'Event', 'action' => 'eventlist'],['class'=>checkul("Event",$controller_name)]);?>
								</li>
								<li>
									<span class="menu-icon"><?php echo $this->Html->image('icons/holiday.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
									<?php echo $this->Html->link(__('Holiday'),['controller' => 'Holiday', 'action' => 'holidaylist'],['class'=>checkul("Holiday",$controller_name)]);?>
								</li>															 
							</div>
							
							<li>
								<span class="menu-icon"><?php echo $this->Html->image('icons/hostel.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
								<?php echo $this->Html->link(__('Hostel'),['controller' => 'Hostel', 'action' => 'hostellist'],['class'=>checkul("Hostel",$controller_name)]);?>
							</li>
							
							<li>
								<span class="menu-icon"><?php echo $this->Html->image('icons/hall.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
								<?php echo $this->Html->link(__('Exam Hall'),['controller' => 'Hall', 'action' => 'halllist'],['class'=>checkul("Hall",$controller_name)]);?>
							</li>
							
							<li>
								<span class="menu-icon"><?php echo $this->Html->image('icons/transport.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
								<?php echo $this->Html->link(__('Transport'),['controller' => 'Transport', 'action' => 'transportlist'],['class'=>checkul("Transport",$controller_name)]);?>
							</li>
							
							<li id="pay-setting" class="<?php echo checkul("Payment",$controller_name)
																   ." ".checkul("Feepayment",$controller_name)
																   ." ".checkul("Library",$controller_name);?>">
								<span class="menu-icon">
									<?php echo $this->Html->image('icons/fee.png');?>  
								</span>
								<a style="cursor: pointer;"><?= __('Pay')?><i class="fa fa-angle-right pull-right right-arrow"></i></a>
							</li>  
							<div id="pay-setting-item" style="display:none;">
								<li>
									<span class="menu-icon"><?php echo $this->Html->image('icons/payment.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
									<?php echo $this->Html->link(__('Payment'),['controller' => 'Payment', 'action' => 'paymentlist'],['class'=>checkul("Payment",$controller_name)]);?>
								</li>
								<li>
									<span class="menu-icon"><?php echo $this->Html->image('icons/fee.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
									<?php echo $this->Html->link(__('Fee Payment'),['controller' => 'Feepayment', 'action' => 'feetypelist'],['class'=>checkul("Feepayment",$controller_name)]);?>
								</li>
								<li>
									<span class="menu-icon"><?php echo $this->Html->image('icons/library.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
									<?php echo $this->Html->link(__('Library'),['controller' => 'Library', 'action' => 'memberlist'],['class'=>checkul("Library",$controller_name)]);?>
								</li>
							</div>																			
							
							<li id="general-setting" class="<?php echo checkul("Comman",$controller_name)
																  ." ".checkul("Export",$controller_name)
																  ." ".checkul("Report",$controller_name)
																  ." ".checkul("Migration",$controller_name)
																  ." ".checkul("Smssetting",$controller_name)
																  ." ".checkul("Emailtemplate",$controller_name)
																  ." ".checkul("Teacherrights",$controller_name)
																  ." ".checkul("Setting",$controller_name);?>">
								<span class="menu-icon">
									<?php echo $this->Html->image('icons/general-setting.png');?>  
								</span>
								<a style="cursor: pointer;"><?= __('General Settings')?><i class="fa fa-angle-right pull-right right-arrow"></i></a>
							</li>  
							<div id="general-setting-item" style="display:none;">
								<li>
									<span class="menu-icon"> <?php echo $this->Html->image('icons/account.png');?>  </span>
									<?php echo $this->Html->link(__('Account'),['controller' => 'Comman', 'action' => 'account'],['class'=>checkul("Comman",$controller_name)]);?>
								</li>
								<li>
									<span class="menu-icon"><?php echo $this->Html->image('icons/import-export.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
									<?php echo $this->Html->link(__('Import/Export'),['controller' => 'Export', 'action' => 'importlist'],['class'=>checkul("Export",$controller_name)]);?>
								</li>
								<li>
									<span class="menu-icon"><?php echo $this->Html->image('icons/report.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
									<?php echo $this->Html->link(__('Report'),['controller' => 'Report', 'action' => 'failed'],['class'=>checkul("Report",$controller_name)]);?>
								</li>									
								<li>
									<span class="menu-icon"><?php echo $this->Html->image('icons/migration.png', ['alt' => 'icon','class' => 'image']);?></span>
									<?php echo $this->Html->link(__('Migration'),['controller' => 'Migration', 'action' => 'migration'],['class'=>checkul("Migration",$controller_name)]);?>
								</li>
								<li>
									<span class="menu-icon"><?php echo $this->Html->image('icons/sms-setting.png', ['alt' => 'CakePHP','class' => 'image']);?></span>
									<?php echo $this->Html->link(__('SMS Setting'),['controller' => 'Smssetting', 'action' => 'smssetting'],['class'=>checkul("Smssetting",$controller_name)]);?>
								</li>								
								<li>
									<span class="menu-icon"> <?php echo $this->Html->image('icons/mail_tempalate.png');?>  </span>
									<?php echo $this->Html->link(__('Mail Template'),['controller' => 'Emailtemplate', 'action' =>'index' ],['class'=>checkul("Emailtemplate",$controller_name)]);?>
								</li>
								<li>
									<span class="menu-icon"> <?php echo $this->Html->image('icons/access-Rights.png');?>  </span>
									<?php echo $this->Html->link(__('Access Rights'),['controller' => 'Teacherrights', 'action' => 'accessteacher'],['class'=>checkul("Teacherrights",$controller_name)]);?>
								</li>
								<li>
									<span class="menu-icon"> <?php echo $this->Html->image('icons/school_setting.png');?>  </span>
									<?php echo $this->Html->link(__('School Settings'),['controller' => 'Setting', 'action' => 'generalsetting'],['class'=>checkul("Setting",$controller_name)]);?>
								</li>								
							</div>	
						</ul>
					</div>

					<?php 
					}
					?>
					<div class="slimScrollBar" style="background: rgb(204, 204, 204) none repeat scroll 0% 0%; width: 7px; position: absolute; top: 0px; opacity: 0.3; display: none; border-radius: 0px; z-index: 99; right: 0px; height: 1088px;">
					</div>
					<div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 0px; background: rgb(51, 51, 51) none repeat scroll 0% 0%; opacity: 0.2; z-index: 90; right: 0px;">
					</div>
				</div><!-- Page Sidebar Inner -->
            </div>
<?php

   }

        }
            ?>
	<!--		<?php

				$class1 = TableRegistry::get('smgt_setting');

				$query1=$class1->find()->where(['field_name'=>'school_name']);

				foreach($query1 as $data1):
				{

				}
				endforeach;



			?> -->
			<?php
			$session = $this->request->session();
			$user = $session->read('user_id');
			$role = $this->Setting->get_user_role($user);		
			?>
			<div class="mas <?php if(!empty($user)){echo 'login-page';}?>">
				<div class="page-inner" style="min-height:1300px!important">				
					<div id="main-wrapper1" <?php if(isset($role)){
					if($role == 'admin' && $this->request->action == 'registration'){?>style="margin-top:15px;"<?php }}?>>
						<?php if($role){?>
								<?= $this->Flash->render() ?>
						<?php }?>
						<?= $this->fetch('content') ?>				
					</div>					
					<?php 
					function checkul($controller_name,$currentcontollr)
					{
						if($controller_name == $currentcontollr)
						{
							return "noactive";
						}
						else
						{
							return "none";
						}					 
					}
					?>
					
				</div>
				<footer id="footerID">
					<div class="col-md-12 col-sm-12 col-xs-12 copyright">
					<?php
					$copyright = $this->Setting->getfieldname('copyright');
					if($copyright){echo $copyright;}
					?>
					</div>
				</footer>
			</div>
		</main>
	</body>
</html>
<script> 
$(document).ready(function(){
	
	var ua = navigator.userAgent.toLowerCase(); 
	if (ua.indexOf('safari') != -1) { 
	  if (ua.indexOf('chrome') > -1) {
		/* alert("1") */ /* Chrome */
	  } else {
		/* Safari */
		$(".page-sidebar").css('width','176px');
		$(".mas.login-page").css('width','87%');
	  }
	}
	
	if ( $( ".mas" ).is( ".login-page" ) ) {
		$( "footer#footerID" ).css("display","block");
	}
	
    $("#general-setting").click(function(){
        $("#general-setting-item").slideToggle("slow");
		    $("i", this).toggleClass("fa-angle-down pull-right down-arrow");
			
		$("#member-setting-item").hide(500);
		$("#reminder-setting-item").hide(500);
		$("#pay-setting-item").hide(500);
    });
	$("#member-setting").click(function(){
        $("#member-setting-item").slideToggle("slow");
		    $("i", this).toggleClass("fa-angle-down pull-right down-arrow");
			
		$("#general-setting-item").hide(500);
		$("#reminder-setting-item").hide(500);
		$("#pay-setting-item").hide(500);
    });
	$("#reminder-setting").click(function(){
        $("#reminder-setting-item").slideToggle("slow");
		    $("i", this).toggleClass("fa-angle-down pull-right down-arrow");
			
		$("#member-setting-item").hide(500);
		$("#general-setting-item").hide(500);
		$("#pay-setting-item").hide(500);
    });
	$("#pay-setting").click(function(){
        $("#pay-setting-item").slideToggle("slow");
		    $("i", this).toggleClass("fa-angle-down pull-right down-arrow");
			
		$("#member-setting-item").hide(500);
		$("#reminder-setting-item").hide(500);
		$("#general-setting-item").hide(500);
    });
	$('body').ajaxStart(function(){
		$('#loadingmessage').css("display", "block");
	});
	$('body').ajaxComplete(function(){
		$('#loadingmessage').css("display", "none");
	});
	$('body').on('click', '.sa-warning', function(){
		
		var url = $(this).attr('url');

		swal({   
			title: "Are You Sure?",
			text: "Are you sure you want to delete this?",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "#297FCA",   
			confirmButtonText: "Yes, delete!",
			cancelButtonText: "No, cancel it!",	
			closeOnConfirm: false,
			closeOnCancel: false
		}, function(isConfirm){
			if (isConfirm)
			{
				swal("Deleted!", "Your record has been deleted.", "success");
				window.location.href = url;		
			}
			else {	 
				swal("Cancelled", "Not removed!", "error"); 
			}
		});
	}); 
});
</script>