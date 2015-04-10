<?php
/**
 * Plugin Name: پرداخت سپیدمان
 * Plugin URI: http://plugins.sepideman.com/sepideman-pay
 * Description: افزونه پرداخت سپیدمان برای پارس پال، دارای کد کوتاه برای ایجاد فرم دلخواه پرداخت
 * Version: 2.5.0
 * Author: زرتشت سپیدمان
 * Author URI: http://www.ZartoshtSepideman.com
 * License: GPLv2
 * Tags: Parspal, Sepideman, Bank, Payment, پارس پال, بانک, پرداخت آنلاین, سپیدمان , shortcode, کد کوتاه
 */
if ( ! defined( 'ABSPATH' ) ) {die();}

add_action( 'admin_menu', 'sepideman_menu' );
function sepideman_menu(){
	add_menu_page( 'پرداخت سپیدمان', 'پرداخت سپیدمان', 'manage_options', 'sepideman-pay', 'sepideman_pay', plugins_url( 'sepideman-pay/images/sepideman_pay.png' ), 6 ); 
	add_submenu_page( 'sepideman-pay', 'راهنمای پرداخت سپیدمان', 'راهنما', 'manage_options', 'sepideman-pay-help', 'sepideman_pay_help');
}

function sepideman_pay_help(){
	echo '<div class="wrap"><h2>راهنمای پرداخت سپیدمان</h2><p>کاربر گرامی ، پیرو قوانین جهت استفاده از خدمات می بایست لوگو تاییدیه پرداخت را در صفحه اصلی سایت خود قرار دهید . برای اینکار با مراجعه به پنل کاربری خود در وبسایت پارس پال از منوی خدمات پرداخت، درگاه‌های پرداخت من، با انتخاب یکی از درگاه های پرداخت خود پیامی بر روی صفحه ظاهر می شود که از طریق آن کدی شبیه به زیر دریافت می کنید:</p>

<div dir="ltr"><code>
<!-- Gateway Verify Logo -->
< script language="javascript" type="text/javascript"  src="http://www.parspal.com/xContext/Component/Verify/?UI=XXXXXXXXXXXXXXXX&GID=XXXXX&MID=XXXXXXXXXXXXXXXX&Mode=1" >
</ script>
<noscript><a title="درگاه پرداخت"  href="http://www.parspal.com" >درگاه پرداخت پارس پال</a></noscript>
<!-- Gateway Verify Logo -->
</code>
</div>
<p>
شما باید کد مقابل src را تا قبل از Mode& انتخاب کنید، چیزی شبیه به این:</p>
<div dir="ltr"><code>http://www.parspal.com/xContext/Component/Verify/?UI=XXXXXXXXXXXXXXXX&GID=XXXXX&MID=XXXXXXXXXXXXXXXX</code></div>
<p>این کد را در پنل افزونه در محل مناسب پیست کنید.</p>

<p>برای استفاده به راحتی با فراخوانی کد کوتاه [sp] می‌توانید فرم پرداخت سپیدمان را فراخوانی کنید. این افزونه شامل دو ورودی برای ساخت فرم دلخواه است:</p>
<ul>
<li>price: این ورودی فیلد پرداخت را پر می‌کند.</li>
<li>des: این ورودی وظیفه پر کردن بخش توضیحات را به عهده دارد.</li>
</ul>

<p>برای استفاده می‌تواندی کد کوتاه را به این شکل فراخوانی کنید:</p>
<div dir="ltr"><code>[sp price="250000" des="خرید هاست"]</code></div>

<p>در صورت بروز هرگونه مشکل و انتقاد و پیشنهاد از راه‌های ارتباطی زیر استفاده کنید:</p>
<div style="text-align: center">
<a href="http://www.SecuritExperts.com" target="_blank">
<img src="' . plugins_url( 'sepideman-pay/images/securitexperts-80.png' ) . '" title="وبسایت خبری امنیتی سپیدمان، سکیوریتی اکسپرتز">
</a>
<a href="http://www.Sepideman.com" target="_blank">
<img src="' . plugins_url( 'sepideman-pay/images/sepideman-80.png' ) . '" title="وبسایت رسمی سپیدمان">
</a>
<a href="http://www.ZartoshtSepideman.com" target="_blank">
<img src="' . plugins_url( 'sepideman-pay/images/zartoshtsepideman-80.png' ) . '" title="وبسایت شخصی مدیر عامل سپیدمان">
</a>
<a href="https://www.facebook.com/SecuritExperts" target="_blank">
<img src="' . plugins_url( 'sepideman-pay/images/fb.jpg' ) . '" title="سپیدمان در فیسبوک">
</a>
<a href="https://www.instagram.com/SecuritExperts" target="_blank">
<img src="' . plugins_url( 'sepideman-pay/images/insta.png' ) . '" title="سکیوریتی اکسپرتز در اینستاگرام">
</a>
<a href="https://www.instagram.com/Sepideman" target="_blank">
<img src="' . plugins_url( 'sepideman-pay/images/insta.png' ) . '" title="سپیدمان در اینستاگرام">
</a>
<a href="https://www.twitter.com/SecuritExperts" target="_blank">
<img src="' . plugins_url( 'sepideman-pay/images/twitter.jpg' ) . '" title="سپیدمان در توییتر">
</a>
</div>

</div>';
}

add_action( 'admin_init', 'register_sepideman_pay_settings' );
function register_sepideman_pay_settings(){
	register_setting( 'sp-settings', 'merchent_id' );
	register_setting( 'sp-settings', 'password' );
	register_setting( 'sp-settings', 'verify' );
	register_setting( 'sp-settings', 'model' );
}

function sepideman_pay() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap"><h2>تنظیمات پرداخت سپیدمان</h2>';
	echo '<form method="post" action="options.php">';
	settings_fields( 'sp-settings' );
	do_settings_sections( 'sp-settings' );
	echo '<br><br><table style="width: 100%;"><tr>';
	echo '<td style="width: 10%">شناسه درگاه : </td><td style="width: 90%;"><input dir="ltr" type="text" name="merchent_id" value="' . esc_attr( get_option('merchent_id') ) . '"></td></tr>';
	echo '<tr><td>رمز عبور : </td><td><input dir="ltr" type="text" name="password" value="' . esc_attr( get_option('password') ) . '"></td></tr>';
	echo '<tr><td>کد تاییدیه : </td><td><input style="width: 100%;" dir="ltr" type="text" name="verify" value="' . esc_attr( get_option('verify') ) . '"></td></tr>';
	echo '<tr><td>استایل تاییدیه : </td><td><select name="model">';
	
	echo '<option ';
	if(esc_attr( get_option("model") ) == "1"){ echo "selected='selected' "; }
	echo 'value="1">استایل 1</option>';
	echo '<option ';
	if(esc_attr( get_option("model") ) == "2"){ echo "selected='selected' "; }
	echo 'value="2">استایل 2</option>';
	echo '<option ';
	if(esc_attr( get_option("model") ) == "3"){ echo "selected='selected' "; }
	echo 'value="3">استایل 3</option>';
	echo '<option ';
	if(esc_attr( get_option("model") ) == "4"){ echo "selected='selected' "; }
	echo 'value="4">استایل 4</option>';
	echo '<option ';
	if(esc_attr( get_option("model") ) == "5"){ echo "selected='selected' "; }
	echo 'value="5">استایل 5</option>';
	echo '<option ';
	if(esc_attr( get_option("model") ) == "6"){ echo "selected='selected' "; }
	echo 'value="6">استایل 6</option>';
	echo '<option ';
	if(esc_attr( get_option("model") ) == "7"){ echo "selected='selected' "; }
	echo 'value="7">استایل 7</option>';
	echo '<option ';
	if(esc_attr( get_option("model") ) == "8"){ echo "selected='selected' "; }
	echo 'value="8">استایل 8</option>';
	echo '<option ';
	if(esc_attr( get_option("model") ) == "9"){ echo "selected='selected' "; }
	echo 'value="9">استایل 9</option>';
	echo '<option ';
	if(esc_attr( get_option("model") ) == "10"){ echo "selected='selected' "; }
	echo 'value="10">استایل 10</option>';
	echo '<option ';
	if(esc_attr( get_option("model") ) == "11"){ echo "selected='selected' "; }
	echo 'value="11">استایل 11</option>';
	
	echo '</select></td></tr>';
	echo '<tr><td colspan="2">';
	echo submit_button();
	echo '</td></tr>';
	echo '</table></form></div>';
	echo '<hr>';
	echo 'کاربر گرامی ، پیرو قوانین جهت استفاده از خدمات می بایست لوگو تاییدیه پرداخت را در صفحه اصلی سایت خود قرار دهید . لطفا از بین استایل‌های موجود زیر یکی را انتخاب کنید.';
	echo '<table><tr>';
	echo '<td>' .
	'استایل 1';
	echo '</td><td>' .
	'<img src="https://www.parspal.com/Images/Gateway/Icons/01.png">';
	echo '</td><td>' .
	'استایل 2';
	echo '</td><td>' .
	'<img src="https://www.parspal.com/Images/Gateway/Icons/02.png">';
	echo '</td><td>' .
	'استایل 3';
	echo '</td><td>' .
	'<img src="https://www.parspal.com/Images/Gateway/Icons/03.png">';
	echo '</td>';
	echo '</tr><tr>';
	echo '<td>' .
	'استایل 4';
	echo '</td><td>' .
	'<img src="https://www.parspal.com/Images/Gateway/Icons/04.png">';
	echo '</td><td>' .
	'استایل 5';
	echo '</td><td>' .
	'<img src="https://www.parspal.com/Images/Gateway/Icons/05.png">';
	echo '</td><td>' .
	'استایل 6';
	echo '</td><td>' .
	'<img src="https://www.parspal.com/Images/Gateway/Icons/06.png">';
	echo '</td></tr><tr>';
	echo '<td>' .
	'استایل 7';
	echo '</td><td>' .
	'<img src="https://www.parspal.com/Images/Gateway/Icons/07.png">';
	echo '</td><td>' .
	'استایل 8';
	echo '</td><td>' .
	'<img src="https://www.parspal.com/Images/Gateway/Icons/08.png">';
	echo '</td><td>' .
	'استایل 9';
	echo '</td><td>' .
	'<img src="https://www.parspal.com/Images/Gateway/Icons/09.png">';
	echo '</td></tr><tr>';
	echo '<td>' .
	'استایل 10';
	echo '</td><td>' .
	'<img src="https://www.parspal.com/Images/Gateway/Icons/10.png">';
	echo '</td><td>' .
	'استایل 11';
	echo '</td><td>' .
	'<img src="https://www.parspal.com/Images/Gateway/Icons/11.png">';
	echo '</td><td></td><td></td></tr></table>';
}

add_shortcode( 'sp', 'sepideman_pay_form' );
function sepideman_pay_form($atts){
	$atts = shortcode_atts( array(
		'price' => '',
		'des' => '',
	), $atts, 'sp' );
	
	$MerchentID = esc_attr( get_option('merchent_id') );
	$Password = esc_attr( get_option('password') );
	$ShowOrderNumberField = false;
	$ReturnPath = get_permalink();
	echo esc_attr( get_option('model') );
	
$match = array("<", ">", "'");
$replace = array("&nbsp;", "&nbsp;", "&nbsp;");
if (isset ($_POST['status']) && $_POST['status'] == 100) {
		$result = '<div class="Succs" >پرداخت شما با شماره رسید ' . $_POST['refnumber'] . ' با موفقیت انجام شد. <a href="' . site_url() . '">بازگشت به صفحه نخست</a></div>';
}
else
		if (isset ($_POST['status'])) {
				$result .= '<div class="Error">متاسفانه در حین انجام عملیات خرید مشکلی به‌وجود آمد. در صورت کسر مبلغ از حساب شما، پس از گذشت 72 ساعت به حسابتان بازگشت داده می‌شود. <a href="' . site_url() . '">بازگشت به صفحه نخست </a></div>';
		}
		else if (isset ($_POST['submit'])) {
						$result .= '<div class="FForm_bg"><div class="fform">
                            <form  action="http://merchant.parspal.com/postservice/" method="post" id="TransactionForm" >
                            <table style="text-align: right;">
                            <tr>
                            <td  style="padding-bottom:10px;" colspan="2">
                            اگر اطلاعات زیر مورد تایید شماست روی دکمه اتصال به درگاه بانک کلیک کنید.
                            </td>
                            </tr>
                            <tr><td style="width:150px">بها : </td><td>' . str_replace($match, $replace, $_POST['Price']) . ' تومان</td></tr>
                            <tr><td>نام شما : </td><td>' . str_replace($match, $replace, $_POST['Paymenter']) . '</td></tr>
                            <tr><td>ایمیل : </td><td>' . str_replace($match, $replace, $_POST['Email']) . '</td></tr>
                            <tr><td>تلفن : </td><td>' . str_replace($match, $replace, $_POST['Mobile']) . '</td></tr>
                            <tr><td>توضیحات : </td><td>' . str_replace($match, $replace, $_POST['Description']) . '</td></tr>
                            <tr><td>شماره سفارش : </td><td>' . str_replace($match, $replace, $_POST['ResNumber']) . '</td></tr>
                            <tr><td colspan="2">جهت پرداخت با مشخصات فوق برروی دکمه اتصال به درگاه بانک کلیک نمایید .</td></tr>
                            <tr><td colspan="2"><input type="submit" value="تایید و اتصال به درگاه بانک"  class="sbtn"/></td></tr></table>
                            <div style="display:none">
                            <input type="hidden" id="MerchantID" value="' . $MerchentID . '" name="MerchantID"/>
                        	<input type="hidden" id="Password" value="' . $Password . '" name="Password"/>
                        	<input type="hidden" id="Paymenter" value="' . str_replace($match, $replace, $_POST['Paymenter']) . '" name="Paymenter"/>
                        	<input type="hidden" id="Email" value="' . str_replace($match, $replace, $_POST['Email']) . '" name="Email"/>
                        	<input type="hidden" id="Mobile" value="' . str_replace($match, $replace, $_POST['Mobile']) . '" name="Mobile"/>
                        	<input type="hidden" id="Price" value="' . str_replace($match, $replace, $_POST['Price']) . '" name="Price"/>
                        	<input type="hidden" id="ResNumber" value="' . str_replace($match, $replace, $_POST['ResNumber']) . '" name="ResNumber"/>
                        	<input type="hidden" id="Description" value="توضیحات : ' . str_replace($match, $replace, $_POST['Description']) . '" name="Description"/>
                        	<input type="hidden" id="ReturnPath" value="' . $ReturnPath . '" name="ReturnPath"/>
                            </div>
                        	</form></div></div>';
				}
				else {
						$result .= '<form method="post">
											<table id="first_form">
											<tr>
                                <td colspan="3" style="padding-bottom: 5px;">جهت ادامه عملیات اطلاعات زیر را تکمیل کنید.</td></tr>';
						if ($atts['price'] != '' && is_numeric($atts['price'])) {
								$result .= '<tr><td colspan="2" style="padding-bottom: 5px;">بها : ' . $atts['price'] . ' تومان<input type="hidden" name="Price" value="' . $atts['price'] . '"/></td><td id="alertPrice"></td></tr>';
						}
						else {
								$result .= '<tr><td style="width: 140px; padding-bottom: 5px;">بها : </td><td><input type="text" name="Price" dir="ltr" id="txtPrice" size="6"/> تومان</td></tr>';
						}
						$result .= '<tr><td style="width: 140px; padding-bottom: 5px;">نام و نام خانوادگی : </td><td><input type="text" name="Paymenter"  id="txtPaymenter" />&nbsp;&nbsp;<i>*</i></td></tr>
										<tr><td style="padding-bottom: 5px;">ایمیل : </td><td><input type="text" dir="ltr" name="Email"  id="txtEmail" class="enput"/>&nbsp;&nbsp;<i>*</i></td></tr>
										<tr><td style="padding-bottom: 5px;">تلفن : </td><td><input type="text" dir="ltr" name="Mobile" id="txtMobile" class="enput"  maxlength="12"/>&nbsp;&nbsp;<i>*</i>
										<tr><td style="padding-bottom: 5px;">توضیحات خرید : </td><td>';
						if ($atts['des'] != '')
								$result .= str_replace($match, $replace, $atts['des']) . '<input type="hidden" name="Description" value="' . str_replace($match, $replace, $atts['des']) . '"/></td></tr>';
						else {
								$result .= '<input type="text" name="Description" value="خرید "/></td></tr>';
						}
						if ($ShowOrderNumberField)
								$result .= '<tr><td style="padding-bottom: 5px;">شماره سفارش : </td><td><input type="text" name="ResNumber"/></td></tr>';
						else
								$result .= '<tr><td colspan="2" style="padding-bottom: 5px;"><input type="hidden" name="ResNumber" value="-"/></td></tr>';
						$result .= '<tr><td colspan="2" style="padding-bottom: 5px;"><p id="alert"></p><input type="submit" name="submit" value="ادامه عمليات خريد" class="sbtn" onclick="return Validate()"/></td></tr></table></form>';
}

                $result .= '
                   <div  style="margin-top:12px;text-align: center;"><script language="javascript" type="text/javascript"  src="' .
						esc_attr( get_option('verify') ) . '&Mode=' . esc_attr( get_option('model') ) .
					'" ></script><noscript><a title="درگاه پرداخت"  href="http://www.parspal.com" >درگاه پرداخت پارس پال</a></noscript>
					</div>

   <script type="text/javascript" language="javascript">
        function Validate() {
           var _txtPaymenter = document.getElementById("txtPaymenter");
            var _txtEmail = document.getElementById("txtEmail");
            var _txtMobile = document.getElementById("txtMobile");
            var _txtPrice = document.getElementById("txtPrice");
            var atpos=_txtEmail.value.indexOf("@");
            var dotpos=_txtEmail.value.lastIndexOf(".");
			var pass = 0;

            if(_txtPrice != null && _txtPrice.value == ""){
				jQuery("#alert").html("مبلغی وارد نشده است.");
				jQuery("#txtPrice").css({ "border" : "1px solid red" });
                _txtPrice.focus();
				pass = 0;
            } else if(_txtPrice != null && _txtPrice.value.toString() != parseInt(_txtPrice.value,0).toString()) {
                jQuery("#alert").html("مبلغ وارد شده صحیح نیست.");
				jQuery("#txtPrice").css({ "border" : "1px solid red" });
                _txtPrice.focus();
				pass = 0;
            } else { pass += 2; jQuery("#txtPrice").css({ "border" : "1px solid #1ec888" }); }
            
			if (_txtPaymenter.value == "") {
                jQuery("#alert").html("نام و نام خانوادگی وارد نشده است.");
				jQuery("#txtPaymenter").css({ "border" : "1px solid red" });
                _txtPaymenter.focus();
				pass = 0;
            } else { pass += 1; jQuery("#txtPaymenter").css({ "border" : "1px solid #1ec888" }); }
			
            if (_txtEmail.value == "") {
                jQuery("#alert").html("ایمیل خود را وارد کنید.");
				jQuery("#txtEmail").css({ "border" : "1px solid red" });
                _txtEmail.focus();
				pass = 0;
            } else if (_txtEmail.value != "" && (atpos<1 || dotpos<atpos+2 || dotpos+2 >= _txtEmail.value.length)) {
                jQuery("#alert").html("ایمیل وارد شده صحیح نیست.");
				jQuery("#txtEmail").css({ "border" : "1px solid red" });
                _txtEmail.focus();
                pass = 0;
            } else { pass += 2; jQuery("#txtEmail").css({ "border" : "1px solid #1ec888" }); }
			
            if (_txtMobile.value == "") {
                jQuery("#alert").html("شماره تماس خود را وارد کنید.");
				jQuery("#txtMobile").css({ "border" : "1px solid red" });
                _txtMobile.focus();
                pass = 0;
            } else { pass += 1; jQuery("#txtMobile").css({ "border" : "1px solid #1ec888" }); }
			
			if (pass != 6){ return false; }
        }
    </script>';
	return $result;
}
?>