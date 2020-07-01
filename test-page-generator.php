<?php
/*
* Plugin Name: Test Post Generator
* Plugin URI: https://iansackofwits.com
* Description: This plug-in will automatically generate posts for you, reducing the time to manually create multiple pages to test whatever you need.
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

register_deactivation_hook(__FILE__, "trash_test_posts");

function add_settings_page()
{
	$page_title = "Test Post Generator Settings";
	$menu_title = "Test Post";
	$capability = "manage_options";
	$menu_slug = "test-post-generator";
	$pluginFunction = "pluginPage";
	add_menu_page($page_title,$menu_title,$capability,$menu_slug,$pluginFunction);
}

function testPageEnqueue()
{
	wp_enqueue_style("testPostStyle", plugin_dir_url(__FILE__) . "style.css");
}

//make WordPress option

$numPages = 0;

add_action("admin_menu", "add_settings_page");

function pluginPage()
{
	?>
	<div class="wrap">
		<h1 id="heading"><?php echo esc_html(get_admin_page_title())?></h2>
		<form id="numPages" action="" method="POST">
			<label for="numPages">Number of Pages to Generate</label>
			<input type="text" name="numPages">
			<?php submit_button("Generate");?>
		</form>
	</div>
	<?php 
}

function create_test_posts($num_posts)
{

    	$text = "Test";
    
    	$textComplete = "<h" . $headingNum . ">" . $text . "</h" . $headingNum . ">";

	$file = fopen(WP_PLUGIN_DIR. "/test-page-generator-master/settings.txt","r");
	$curPageNum = (int)fgets($file);
	
	$newPageTotal = $numPages + $curPageNum;
	
	while ($curPageNum < $newPageTotal)
	{
		$title = "Test Page" . " " . $curPageNum;

		$post_data = array(
		"post_title" => $title,
		"post_type" => "page",
		"post_content" => $textComplete,
		"post_status" => "publish",
		"post_category" => array($catID)	
		
	);
	$curPageNum++;	
	wp_insert_post($post_data);
	}
}

function trash_test_posts()
{
	$catID = get_cat_ID("Test");
	$posts = get_posts(array("post_type" => "post", "numberposts" => -1, "category" => array($catID)));

	foreach($posts as $post)
	{
		wp_trash_post($post->ID,false);
	}
}

?>
