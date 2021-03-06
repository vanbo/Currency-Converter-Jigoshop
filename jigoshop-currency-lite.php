<?php
/*
   Plugin Name: Multi Currency Lite For Jigoshop
   Plugin URI: https://github.com/steveclarkcouk/Currency-Converter-Jigoshop
   Description: Add multi currencies to your Jigoshop Store
   Version: 1.1
   Author: Steve Clark
   Author URI: http://www.the-escape.co.uk
 */

include 'constants.php';
include 'classes/jigoshop-currency-class.php';
include 'classes/jigoshop-currency-converter.php';

// --- Admin Builder
$jmc_admin = new Jigoshop_Mutli_Currency_Admin();
$jmc_admin->setData('prefix', $prefix);
$jmc_admin->setData('countries', $countries);

// --  Make Sure A Cache Value Is Set:
if(get_option($jmc_admin->data['prefix'] . 'cache_period') == '') {
	update_option($jmc_admin->data['prefix'] . 'cache_period', 6400);
	}

// --- Front End
$currency_converter = new currency_converter();
$currency_converter->data = $jmc_admin->data;

// -- Manual Call For Currency Convert
function jigoshop_currency_product_conversion()
{
	global $currency_converter;
	echo $currency_converter->add_conversion_prices();
}

// -- Manual Call For Currency Convert
function jigoshop_currency_cart_conversion()
{
	global $currency_converter;
	echo $currency_converter->add_cart_conversion_prices();
}

// -- Check The Cache And Rebuild From Yahoo If Necessary
add_action('wp_head', array($currency_converter, 'check_caches'));

// -- [ Action to add currency converted values to product page ]
if(get_option($prefix . 'add_to_single_summary')) {
	add_action('jigoshop_template_single_summary', 'jigoshop_currency_product_conversion');
	}

// -- [ Action to add currency converted to cart, Gulp! ]
if(get_option($prefix . 'add_to_cart_auto')) {
	add_action('after_checkout_form',  'jigoshop_currency_cart_conversion');
	}





?>