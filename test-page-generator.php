<?php
/**
* Plugin Name: Test Page Generator
* Plugin URI: https://iansackofwits.com
* Description: This plug-in will create pages that will be useful for testing out the 
functionality of a plug-in.
* Version: 1.0
* Author: Ian
* Author URI: https://iansackofwits.com
* License: GPLv2 or later
**/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright (C) 2020 Ian
*/

if (!defined("ABSPATH"))
{
	wp_die;
}

register_activation_hook(__FILE__, );

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
 
function create_test_pages()
{
	$post_data = array (
		$name => "Trial",
		$type => "post",
		$content => "Test Page",
		$status => "publish"
		$category => "Test"
	);
	wp_insert_post($post_data);	

}


?>