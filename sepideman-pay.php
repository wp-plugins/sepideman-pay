<?php
/**
 * Plugin Name: پرداخت سپیدمان
 * Plugin URI: http://plugins.sepideman.com/sepideman-pay
 * Description: پرداخت سپیدمان، با کاربری آسان برای پارس پال
 * Version: 2.0.0
 * Author: زرتشت سپیدمان
 * Author URI: http://www.ZartoshtSepideman.com
 * License: GPLv2
 * Tags: ParsPal, Sepideman, Bank, Payment
 */
if ( ! defined( 'ABSPATH' ) ) {die();}

add_action( 'admin_menu', 'sepideman_menu' );
function sepideman_menu(){
	add_menu_page( 'پرداخت سپیدمان', 'پرداخت سپیدمان', 'manage_options', 'sepideman-pay', 'sepideman_pay', plugins_url( 'sepideman-pay/images/sepideman_pay.png' ), 6 ); 
}

add_action( 'admin_init', 'register_sepideman_pay_settings' );
function register_sepideman_pay_settings(){
	register_setting( 'sp-settings', 'merchent_id' );
	register_setting( 'sp-settings', 'password' );
}

function sepideman_pay() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap"><h2>تنظیمات پرداخت سپیدمان</h2>';
	echo '<form method="post" action="options.php">';
	settings_fields( 'sp-settings' );
	do_settings_sections( 'sp-settings' );
	echo '<br><br><table><tr>';
	echo '<td style="width: 110px">شناسه درگاه : </td><td><input dir="ltr" type="text" name="merchent_id" value="' . esc_attr( get_option('merchent_id') ) . '"></td></tr>';
	echo '<tr><td>رمز عبور : </td><td><input dir="ltr" type="text" name="password" value="' . esc_attr( get_option('password') ) . '"></td></tr>';
	echo '<tr><td colspan="2">';
	echo submit_button();
	echo '</td></tr>';
	echo '</table></form></div>';
	echo '<hr>';
}

add_shortcode( 'sp', 'sepideman_pay_form' );
function sepideman_pay_form($atts){
	$atts = shortcode_atts( array(
		'price' => '',
		'des' => '',
	), $atts, 'sp' );
	
	$MerchentID = esc_attr( get_option('merchent_id') );
	$Password = esc_attr( get_option('password') );
	$PageTitle = the_title();
	$ShowOrderNumberField = false;
	$ReturnPath = get_permalink();
	
	
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
                                <td colspan="3">جهت ادامه عملیات اطلاعات زیر را تکمیل کنید.</td></tr>';
						if ($atts['price'] != '' && is_numeric($atts['price'])) {
								$result .= '<tr><td colspan="2">بها : ' . $atts['price'] . ' تومان<input type="hidden" name="Price" value="' . $atts['price'] . '"/></td><td id="alertPrice"></td></tr>';
						}
						else {
								$result .= '<tr><td style="width: 140px">بها : </td><td><input type="text" name="Price" dir="ltr" id="txtPrice" size="6"/> تومان</td></tr>';
						}
						$result .= '<tr><td style="width: 140px">نام و نام خانوادگی : </td><td><input type="text" name="Paymenter"  id="txtPaymenter" />&nbsp;&nbsp;<i>*</i></td></tr>
										<tr><td>ایمیل : </td><td><input type="text" dir="ltr" name="Email"  id="txtEmail" class="enput"/>&nbsp;&nbsp;<i>*</i></td></tr>
										<tr><td>تلفن : </td><td><input type="text" dir="ltr" name="Mobile" id="txtMobile" class="enput"  maxlength="12"/>&nbsp;&nbsp;<i>*</i>
										<tr><td>توضیحات خرید : </td><td>';
						if ($atts['des'] != '')
								$result .= str_replace($match, $replace, $atts['des']) . '<input type="hidden" name="Description" value="' . str_replace($match, $replace, $atts['des']) . '"/></td></tr>';
						else {
								$result .= '<input type="text" name="Description" value="خرید "/></td></tr>';
						}
						if ($ShowOrderNumberField)
								$result .= '<tr><td>شماره سفارش : </td><td><input type="text" name="ResNumber"/></td></tr>';
						else
								$result .= '<tr><td colspan="2"><input type="hidden" name="ResNumber" value="-"/></td></tr>';
						$result .= '<tr><td colspan="2"><p id="alert"></p><input type="submit" name="submit" value="ادامه عمليات خريد" class="sbtn" onclick="return Validate()"/></td></tr></table></form>';
}

                $result .= '
                   <div  style="margin-top:12px;">
						پرداخت توسط درگاه بانک 
                        <img src="' . plugins_url( 'sepideman-pay/images/mellat.png' ) . '" id="img" />
						 و کلیه کارت‌های عضو شتاب.
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