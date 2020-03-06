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

register_activation_hook(__FILE__, "setup");

register_deactivation_hook(__FILE__, "trash_test_pages");

function addPage()
{
	$page_title = "Test Page Generator Settings";
	$menu_title = "Test Page";
	$capability = "manage_options";
	$menu_slug = "test-page-generator";
	$pluginFunction = "pluginPage";
	add_menu_page($page_title,$menu_title,$capability,$menu_slug,$pluginFunction);

}

function testPageEnqueue()
{
	wp_enqueue_script("testPageAjax", plugin_dir_url(__FILE__) . "settings.js");
	wp_enqueue_style("testPageStyle", plugin_dir_url(__FILE__) . "style.css");
}

$numPages = 0;

function ajax_work()
{
	$numPages = (int)$_POST["pages"];
	create_test_pages($numPages);
	wp_die();
}

add_action("admin_menu", "addPage");
add_action("admin_enqueue_scripts", "testPageEnqueue");
add_action("wp_ajax_test_page","ajax_work");

function setup()
{
	$cookieName = "curPageNum";
	$cookieValue = 1;
	setcookie($cookieName, $cookieValue, time() + (10 * 365 * 24 * 60 * 60));
}

function pluginPage()
{
	?>
	<div class="wrap">
		<h2 id="heading"><?php echo esc_html(get_admin_page_title())?></h2>
		<form id="numPages" action="" method="POST">
			<label for="numPages">Number of Pages to Generate</label>
			<input type="text" name="numPages">
			<?php submit_button();?>
		</form>
	</div>
	<?php 
}

function create_test_pages($numPages)
{
	$cookieName = "curPageNum";
	$curPageNum = $_COOKIE[$cookieName];
	
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

	$contentArr = ["<p>Hi</p>","<h1>Hi</h1>","<h2>Hi</h2>","<h3>Hi</h3>","<h4>Hi</h4>"
	,"<b>Hi</b>","<i>Hi</i>","<p>Hello</p>","<h5>Hi</h5>","<h3>Hello</h3>"];

	$newPageTotal = $numPages + $curPageNum;
	
	while ($curPageNum < $newPageTotal)
	{
		$title = "Test Page" . " " . $curPageNum;

		$post_data = array(
		"post_title" => $title,
		"post_type" => "post",
		"post_content" => $contentArr[$curPageNum%10],
		"post_status" => "publish",
		"post_category" => array($catID)	
		
	);
	$curPageNum++;	
	wp_insert_post($post_data);
		
	}
	
	setcookie($cookieName, $curPageNum, time() + (10 * 365 * 24 * 60 * 60));

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
