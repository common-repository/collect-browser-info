<?php
/*
Plugin Name: Collect Browser Info
Description: It provides a shortcode to collect the visitor browser info
Author: Jose Mortellaro
Author URI: https://josemortellaro.com
Domain Path: /languages/
Text Domain: browser-info
Version: 0.0.3
*/
/*  This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

//Definitions
define( 'EOS_CBI_PLUGIN_DIR',untrailingslashit( dirname( __FILE__ ) ) );

add_shortcode( 'collect_browser_info',function(){
  //Add a shortcode to output the browser information
  $is_ssl = is_ssl();
  $output = '<section style="padding:20px">';
  $output .= '<div id="browser-info"></div>';
  $output .= '<div style="margin-top:32px">';
  if( $is_ssl ){
    $output .= '<input type="submit" id="cbi-copy-to-clipboard" class="button" value="'.esc_attr__( 'Copy to clipboard','browser-info' ).'" />';
    $output .= '<p id="cbi-clipboard-msg"></p>';
  }
  $output .= '</div>';
  $output .= '</section>';
  $output .= '<script>';
  $output .= 'function eos_cbi_update_browser_info(){';
  $output .= 'var browser_info = document.getElementById("browser-info"),info = "",w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0),h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);';
  $output .= 'info +="<p><b>'.esc_html__( 'User Agent:','browser-info' ).'</b> " + navigator.userAgent + "</p>";';
  $output .= 'info +="<p><b>'.esc_html__( 'App name:','browser-info' ).'</b> " + navigator.appName + "</p>";';
  $output .= 'info +="<p><b>'.esc_html__( 'Platform:','browser-info' ).'</b> " + navigator.platform + "</p>";';
  $output .= 'info +="<p><b>'.esc_html__( 'Cookies enabled:','browser-info' ).'</b> " + navigator.cookieEnabled + "</p>";';
  $output .= 'info +="<p><b>'.esc_html__( 'Vendor:','browser-info' ).'</b> " + navigator.vendor + "</p>";';
  $output .= 'info +="<p><b>'.esc_html__( 'Screen available width:','browser-info' ).'</b> " + window.screen.availWidth + "</p>";';
  $output .= 'info +="<p><b>'.esc_html__( 'Screen available height:','browser-info' ).'</b> " + window.screen.availHeight + "</p>";';
  $output .= 'info +="<p><b>'.esc_html__( 'Screen width:','browser-info' ).'</b> " + window.screen.width + "</p>";';
  $output .= 'info +="<p><b>'.esc_html__( 'Screen height:','browser-info' ).'</b> " + window.screen.height + "</p>";';
  $output .= 'info +="<p><b>'.esc_html__( 'Viewport width:','browser-info' ).'</b> " + w + "</p>";';
  $output .= 'info +="<p><b>'.esc_html__( 'Viewport height:','browser-info' ).'</b> " + h + "</p>";';
  $output .= 'browser_info.innerHTML = info;';
  $output .= '}';
  $output .= 'function eos_cbi_copy_to_clipboard(text){';
  $output .= 'var clip_msg = document.getElementById("cbi-clipboard-msg");';
  $output .= 'clip_msg.innerHTML = "";';
  $output .= 'navigator.clipboard.writeText(text).then(function() {';
  $output .= 'clip_msg.innerHTML = "'.esc_js( esc_attr__( 'Copying to clipboard was successful!','browser-info' ) ).'";';
  $output .= '},function(err){';
  $output .= 'clip_msg.innerHTML = "'.esc_js( esc_attr__( 'Not possible to copy to the clipboard:','browser-info' ) ).' " + err;';
  $output .= '});';
  $output .= '}';
  $output .= 'eos_cbi_update_browser_info();';
  if( $is_ssl ){
    $output .= 'window.addEventListener("resize",eos_cbi_update_browser_info);';
    $output .= 'document.getElementById("cbi-copy-to-clipboard").addEventListener("click",function(){';
    $output .= 'eos_cbi_copy_to_clipboard(document.getElementById("browser-info").innerText)';
    $output .= '});';
  }
  $output .= '</script>';
  return $output;
} );

//It loads plugin translation files
function eos_cbi_plugin_textdomain(){
	load_plugin_textdomain( 'browser-info', false,EOS_CBI_PLUGIN_DIR . '/languages/' );
}
add_action( 'init', 'eos_cbi_plugin_textdomain' );

//Filter function to read plugin translation files
function eos_cbi_load_translation_file( $mofile, $domain ) {
	if ( 'browser-info' === $domain ) {
		$loc = function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		$mofile = EOS_CBI_PLUGIN_DIR.'/languages/browser-info-' . $loc . '.mo';
	}
	return $mofile;
}
add_filter( 'load_textdomain_mofile', 'eos_cbi_load_translation_file',99,2 ); //loads plugin translation files
