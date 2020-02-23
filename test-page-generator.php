<?php
/*
* Plugin Name: Test Page Generator
* Plugin URI: https://iansackofwits.com
* Description: This plug-in will create pages that will be useful for testing out the functionality of a plug-in.
* Version: 1.0
* Author: Ian
* Author URI: https://iansackofwits.com
* License: GPLv2 or later
*/

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

register_activation_hook(__FILE__, "create_test_pages");

register_deactivation_hook(__FILE__, "trash_test_pages");

function create_test_pages()
{
	if (!category_exists("Test"))
	{
		$category_data = array(
		"cat_name" => "Test",
		"category_description" => "Used for the Test Page Generator Plugin",
		"category_nicename" => "Test"
		);
		
		wp_insert_category($category_data);
	}
	
	$catID = get_cat_ID("Test");
	
	$numPages = 10;
	$num = 1;	//will be included in the page that will be generated; it's incremented in the function below 
	
	for ($i = $num; $i <= $numPages; $i++)
	{
		$title = "Test" . " " . $i;
		$post_data = array(
		"post_title" => $title,
		"post_type" => "post",
		"post_content" => $title,
		"post_status" => "publish",
		"post_category" => array($catID)
	);
	wp_insert_post($post_data);	
	}
}

function trash_test_pages()
{
	$catID = get_cat_ID("Test");
	$pages = get_posts(array("post_type" => "post", "numberposts" => -1, "category" => array($catID)));
	
	foreach($pages as $page)
	{
		wp_trash_post($page->ID,false);
	}
}

?>