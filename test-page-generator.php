<?php
/**
* Plugin Name: Test Page Generator
* Plugin URI: 
* Description: This plug-in will create pages that will be useful for testing out the 
functionality of a plug-in
* Version: 1.0
* Author: Ian
* Author URI: https://iansackofwits.com
**/

/**
 * Summary.
 *
 * Description.
 *
 * @since x.x.x
 *
 * @see Function/method/class relied on
 * @link URL
 * @global type $varname Description.
 * @global type $varname Description.
 *
 * @param type $var Description.
 * @param type $var Optional. Description. Default.
 * @return type Description.
 */
 
if (!function_exists("create_test_posts"))
{
	function create_test_posts()
	{
		$post_data = array (
			$name = "Trial",
			$type = "post",
			$content = "Test",
			$status = "publish"
		);
		wp_insert_post($post_data);
		
	}
