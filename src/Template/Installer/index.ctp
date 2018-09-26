<div class="pg-header">
	<h4 class="install_title"><span style="height:30px"><img  src="<?php echo $this->request->webroot;?>img/2.png" height="80%" /></span> <?php echo __("NiftySIS - School Information System Installation Wizard");?></h4>
</div>
<div class="step-content">
<!-- <form id="example-form" method="post" class="form-horizontal"> -->
<?php echo $this->Form->create("",["id"=>"install-form","class"=>"form-horizontal"]);?>
    <div>
		<h3><?php echo __("Database Setup");?></h3>
			<section>
				<h4><?php echo __("Database Setup");?></h4>
				<hr/>
				<div class="form-group">
					<label class="control-label col-md-3"><?php echo __("Database Name")?><span class="text-danger"> *</span></label>
					<div class="col-md-5">
					<div class="input text">
					<input type="text" name="db_name" class="form-control required" value="">
					</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?php echo __("Database Username")?><span class="text-danger"> *</span></label>
					<div class="col-md-5">
					<div class="input text">
					<input type="text" name="db_username" class="form-control required" value="">
					</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?php echo __("Database Password")?></label>
					<div class="col-md-5">
					<div class="input text">
					<input type="text" name="db_pass" class="form-control" value="">
					</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?php echo __("Host")?><span class="text-danger"> *</span></label>
					<div class="col-md-5">
					<div class="input text">
					<input type="text" name="db_host" class="form-control required" value="">
					</div>
					</div>
				</div>
				<div class="col-md-offset-3">
					<p> (*) <?php echo __("Fields are required.")?></p>
				</div>
			</section>
        <h3><?php echo __("System Settings")?></h3>
        <section> 
		  <h4><?php echo __("System Settings")?></h4>
		  <hr/>
		  <div class="form-group">
			  <label class="control-label col-md-3"><?php echo __("System Name")?><span class="text-danger"> *</span></label>
			  <div class="col-md-8 col-sm-8 col-xs-12">
			  <div class="input text">
			  <input type="text" name="name" class="form-control required" value="NiftySIS - School Information System">
			  </div>
			  </div>
		  </div>		  		  
		  <div class="form-group">
			  <label class="control-label col-md-3"><?php echo __("Country")?></label>
			  <div class="col-md-8 col-sm-8 col-xs-12">			
			  <select id="country" class="form-control required" name="country">
								<option value="in" selected="">India</option>
								<option value="af">Afghanistan</option>
								<option value="al">Albania</option>
								<option value="dz">Algeria</option>
								<option value="ad">Andorra</option>
								<option value="ao">Angola</option>
								<option value="aq">Antarctica</option>
								<option value="ar">Argentina</option>
								<option value="am">Armenia</option>
								<option value="aw">Aruba</option>
								<option value="au">Australia</option>
								<option value="at">Austria</option>
								<option value="az">Azerbaijan</option>
								<option value="bh">Bahrain</option>
								<option value="bd">Bangladesh</option>
								<option value="by">Belarus</option>
								<option value="be">Belgium</option>
								<option value="bz">Belize</option>
								<option value="bj">Benin</option>
								<option value="bt">Bhutan</option>
								<option value="bo">Bolivia, Plurinational State Of</option>
								<option value="ba">Bosnia And Herzegovina</option>
								<option value="bw">Botswana</option>
								<option value="br">Brazil</option>
								<option value="bn">Brunei Darussalam</option>
								<option value="bg">Bulgaria</option>
								<option value="bf">Burkina Faso</option>
								<option value="mm">Myanmar</option>
								<option value="bi">Burundi</option>
								<option value="cm">Cameroon</option>
								<option value="ca">Canada</option>
								<option value="cv">Cape Verde</option>
								<option value="cf">Central African Republic</option>
								<option value="td">Chad</option>
								<option value="cl">Chile</option>
								<option value="cn">China</option>
								<option value="cx">Christmas Island</option>
								<option value="cc">Cocos (keeling) Islands</option>
								<option value="co">Colombia</option>
								<option value="km">Comoros</option>
								<option value="cg">Congo</option>
								<option value="cd">Congo, The Democratic Republic Of The</option>
								<option value="ck">Cook Islands</option>
								<option value="cr">Costa Rica</option>
								<option value="hr">Croatia</option>
								<option value="cu">Cuba</option>
								<option value="cy">Cyprus</option>
								<option value="cz">Czech Republic</option>
								<option value="dk">Denmark</option>
								<option value="dj">Djibouti</option>
								<option value="tl">Timor-leste</option>
								<option value="ec">Ecuador</option>
								<option value="eg">Egypt</option>
								<option value="sv">El Salvador</option>
								<option value="gq">Equatorial Guinea</option>
								<option value="er">Eritrea</option>
								<option value="ee">Estonia</option>
								<option value="et">Ethiopia</option>
								<option value="fk">Falkland Islands (malvinas)</option>
								<option value="fo">Faroe Islands</option>
								<option value="fj">Fiji</option>
								<option value="fi">Finland</option>
								<option value="fr">France</option>
								<option value="pf">French Polynesia</option>
								<option value="ga">Gabon</option>
								<option value="gm">Gambia</option>
								<option value="ge">Georgia</option>
								<option value="de">Germany</option>
								<option value="gh">Ghana</option>
								<option value="gi">Gibraltar</option>
								<option value="gr">Greece</option>
								<option value="gl">Greenland</option>
								<option value="gt">Guatemala</option>
								<option value="gn">Guinea</option>
								<option value="gw">Guinea-bissau</option>
								<option value="gy">Guyana</option>
								<option value="ht">Haiti</option>
								<option value="hn">Honduras</option>
								<option value="hk">Hong Kong</option>
								<option value="hu">Hungary</option>
								<option value="id">Indonesia</option>
								<option value="ir">Iran, Islamic Republic Of</option>
								<option value="iq">Iraq</option>
								<option value="ie">Ireland</option>
								<option value="im">Isle Of Man</option>
								<option value="il">Israel</option>
								<option value="it">Italy</option>
								<option value="ci">Côte D'ivoire</option>
								<option value="jp">Japan</option>
								<option value="jo">Jordan</option>
								<option value="kz">Kazakhstan</option>
								<option value="ke">Kenya</option>
								<option value="ki">Kiribati</option>
								<option value="kw">Kuwait</option>
								<option value="kg">Kyrgyzstan</option>
								<option value="la">Lao People's Democratic Republic</option>
								<option value="lv">Latvia</option>
								<option value="lb">Lebanon</option>
								<option value="ls">Lesotho</option>
								<option value="lr">Liberia</option>
								<option value="ly">Libya</option>
								<option value="li">Liechtenstein</option>
								<option value="lt">Lithuania</option>
								<option value="lu">Luxembourg</option>
								<option value="mo">Macao</option>
								<option value="mk">Macedonia, The Former Yugoslav Republic Of</option>
								<option value="mg">Madagascar</option>
								<option value="mw">Malawi</option>
								<option value="my">Malaysia</option>
								<option value="mv">Maldives</option>
								<option value="ml">Mali</option>
								<option value="mt">Malta</option>
								<option value="mh">Marshall Islands</option>
								<option value="mr">Mauritania</option>
								<option value="mu">Mauritius</option>
								<option value="yt">Mayotte</option>
								<option value="mx">Mexico</option>
								<option value="fm">Micronesia, Federated States Of</option>
								<option value="md">Moldova, Republic Of</option>
								<option value="mc">Monaco</option>
								<option value="mn">Mongolia</option>
								<option value="me">Montenegro</option>
								<option value="ma">Morocco</option>
								<option value="mz">Mozambique</option>
								<option value="na">Namibia</option>
								<option value="nr">Nauru</option>
								<option value="np">Nepal</option>
								<option value="nl">Netherlands</option>
								<option value="nc">New Caledonia</option>
								<option value="nz">New Zealand</option>
								<option value="ni">Nicaragua</option>
								<option value="ne">Niger</option>
								<option value="ng">Nigeria</option>
								<option value="nu">Niue</option>
								<option value="kp">Korea, Democratic People's Republic Of</option>
								<option value="no">Norway</option>
								<option value="om">Oman</option>
								<option value="pk">Pakistan</option>
								<option value="pw">Palau</option>
								<option value="pa">Panama</option>
								<option value="pg">Papua New Guinea</option>
								<option value="py">Paraguay</option>
								<option value="pe">Peru</option>
								<option value="ph">Philippines</option>
								<option value="pn">Pitcairn</option>
								<option value="pl">Poland</option>
								<option value="pt">Portugal</option>
								<option value="pr">Puerto Rico</option>
								<option value="qa">Qatar</option>
								<option value="ro">Romania</option>
								<option value="ru">Russian Federation</option>
								<option value="rw">Rwanda</option>
								<option value="bl">Saint Barthélemy</option>
								<option value="ws">Samoa</option>
								<option value="sm">San Marino</option>
								<option value="st">Sao Tome And Principe</option>
								<option value="sa">Saudi Arabia</option>
								<option value="sn">Senegal</option>
								<option value="rs">Serbia</option>
								<option value="sc">Seychelles</option>
								<option value="sl">Sierra Leone</option>
								<option value="sg">Singapore</option>
								<option value="sk">Slovakia</option>
								<option value="si">Slovenia</option>
								<option value="sb">Solomon Islands</option>
								<option value="so">Somalia</option>
								<option value="za">South Africa</option>
								<option value="kr">Korea, Republic Of</option>
								<option value="es">Spain</option>
								<option value="lk">Sri Lanka</option>
								<option value="sh">Saint Helena, Ascension And Tristan Da Cunha</option>
								<option value="pm">Saint Pierre And Miquelon</option>
								<option value="sd">Sudan</option>
								<option value="sr">Suriname</option>
								<option value="sz">Swaziland</option>
								<option value="se">Sweden</option>
								<option value="ch">Switzerland</option>
								<option value="sy">Syrian Arab Republic</option>
								<option value="tw">Taiwan, Province Of China</option>
								<option value="tj">Tajikistan</option>
								<option value="tz">Tanzania, United Republic Of</option>
								<option value="th">Thailand</option>
								<option value="tg">Togo</option>
								<option value="tk">Tokelau</option>
								<option value="to">Tonga</option>
								<option value="tn">Tunisia</option>
								<option value="tr">Turkey</option>
								<option value="tm">Turkmenistan</option>
								<option value="tv">Tuvalu</option>
								<option value="ae">United Arab Emirates</option>
								<option value="ug">Uganda</option>
								<option value="gb">United Kingdom</option>
								<option value="uy">Uruguay</option>
								<option value="us">United States</option>
								<option value="uz">Uzbekistan</option>
								<option value="vu">Vanuatu</option>
								<option value="va">Holy See (vatican City State)</option>
								<option value="ve">Venezuela, Bolivarian Republic Of</option>
								<option value="vn">Viet Nam</option>
								<option value="wf">Wallis And Futuna</option>
								<option value="ye">Yemen</option>
								<option value="zm">Zambia</option>
								<option value="zw">Zimbabwe</option>
				
					</select>
				</div>
			</div>
			<div class="form-group">
			<?php
			$currency_symbols = $this->Setting->currency_list();
			?>
				<label class="control-label col-md-3"><?php echo __('Select Currency');?><span class="text-danger"> *</span></label>
				<div class="col-md-8 col-sm-8 col-xs-12">
					<select class="form-control text-input required" name="currency_code">
						  <option value=""><?php echo __('Select Currency');?></option>
							<?php 
							foreach($currency_symbols as $key => $value)
							{
								echo "<option value='".$key."' ".$this->setting->selected($key,'').">".$value['symbol']." ".$value['name']."</option>";
							}
							?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?php echo __("Email")?> <span class="text-danger">*</span></label>
				<div class="col-md-8 col-sm-8 col-xs-12">
				<div class="input text">
				<input type="text" name="email" class="form-control required email" value="">
				</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?php echo __("Date Format")?></label>
				<div class="col-md-8 col-sm-8 col-xs-12">
				<select name="date_format" class="form-control plan_list required">
					<option value="Y-m-d"><?php echo date("Y-m-d");?></option>
					<option value="m-d-Y"><?php echo date("m-d-Y");?></option>
					<option value="d-m-Y"><?php echo date("d-m-Y");?></option>
				</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3"><?php echo __("System Language")?></label>
				<div class="col-md-8 col-sm-8 col-xs-12">
					<select id="lang-selector" name="system_lang" class="form-control">
					<option value="en">English/en</option>
					<option value="ar">Arabic/ar</option>
					<option value="zh_CH">Chinese/zh-CH</option>
					<option value="cs">Czech/cs</option>
					<option value="fr">French/fr</option>
					<option value="de">German/de</option>
					<option value="el">Greek/el</option>					
					<option value="it">Italian/it</option>	
					<option value="ja">Japan/ja</option>
					<option value="pl">Polish/pl</option>
					<option value="pt_BR">Portuguese-BR/pt-BR</option>
					<option value="pt_PT">Portuguese-PT/pt-PT</option>						
					<option value="fa">Persian</option>
					<option value="ru">Russian/ru</option>
					<option value="es">Spanish/es</option>											
					<option value="th">Thai/th</option>
					<option value="tr">Turkish/tr</option>
					
					</select>				
				</div>
			</div> 
			<div class="col-md-offset-3">
					<p> (*) <?php echo __("Fields are required.")?></p>
			</div>
        </section>  
		 <h3><?php echo __("Login Details");?></h3>
		<section>
		<h4><?php echo __("Login Details");?></h4>
				<hr/>
				<div class="form-group">
					<label class="control-label col-md-3"><?php echo __("Username")?><span class="text-danger"> *</span></label>
					<div class="col-md-5">
					<div class="input text">
					<input type="text" name="lg_username" class="form-control required" value="admin">
					</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3"><?php echo __("Password")?><span class="text-danger"> *</span></label>
					<div class="col-md-5">
					<div class="input text">
					<input type="password" id="password" name="password" class="form-control required password" value="admin">
					</div>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label col-md-3"><?php echo __("Confirm Password")?><span class="text-danger"> *</span></label>
					<div class="col-md-5">
					<div class="input text">
					<input type="password" name="confirm" id="confirm" class="form-control required" value="admin">
					</div>
					</div>
				</div> 			
		</section>
        <h3><?php echo __("Finish");?></h3>
		<section id="final">
			<h4><?php echo __("Please Note :");?></h4>
			<hr/>					
			<p>
				<?php echo __("1. It may take couple of minutes to set-up database.");?>
			</p>			
			<p>
				<?php echo __("2. Do not refresh page after clicking on install button.");?>
			</p>
			<p>
				<?php echo __("3. You will receive success message once installation is finished.");?>
			</p>
			<p>
				<?php echo __("4. Click on install to complete the installation.");?>
			</p>
			
			<div id="loader" style="display:none;">
				<p>			
					<hr/>
					<h4><?php echo __("Please Wait System is now installing.");?></h4>
				</p>
				<span>
					<img src="<?php echo $this->request->base;?>/webroot/img/ajax-loader.gif" />
				</span>
			</div>
		</section>
    </div>	
<?php echo $this->Form->end();?>
<!-- </form> -->
</div>

<script>
$(function ()
 {
	 
var form = $("#install-form");
form.validate({
    errorPlacement: function errorPlacement(error, element) { element.before(error); },
    rules: {
        confirm: {
            equalTo: "#password"
        }
    }
});
form.children("div").steps({
	 labels: {
        cancel: "Cancel",
        current: "current step:",
        pagination: "Pagination",
        finish: "Install Now",
        next: "Next Step",
        previous: "Previous Step",
        loading: "Loading ..."
    },	
    headerTag: "h3",
    bodyTag: "section",	
    transitionEffect: "slideLeft",
    onStepChanging: function (event, currentIndex, newIndex)
    {
        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    },
    onFinishing: function (event, currentIndex)
    {
		$("#loader").css("display","block");
        form.validate().settings.ignore = ":disabled";
        return form.valid();
    },
    onFinished: function (event, currentIndex)
    {				
        form.submit();
    }
});
});
</script>