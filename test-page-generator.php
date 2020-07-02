<?php
/*
* Plugin Name: Test Post Generator
* Plugin URI: https://iansackofwits.com
* Description: This plug-in will automatically generate posts for you, reducing the time to manually create multiple posts to test whatever you need.
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
	wp_die();
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

function test_page_enqueue()
{
	wp_enqueue_style("test_post_style", plugin_dir_url(__FILE__) . "style.css");
}

add_action("admin_init","page_number_setting");

function page_number_setting()
{
	$settings_group = "test-post-generator";
	$setting_name = "num_pages";
	register_setting($settings_group, $setting_name);
	
	$page = $settings_group;
	$section_title = "Generate Test Posts";
	$section_callback = "render_settings_field";
	add_settings_section($setting_name, $section_title, null, $page);
	
	$field_title = "Number of Posts";
	add_settings_field($setting_name, $field_title, "render_settings_field", $page, $setting_name);
	function render_settings_field()
	{
		echo "<input type='number' id='num_pages' name='num_pages'>";	
	}
}

function test_posts_num_add($new_value, $old_value) 
{
	$new_value = intval($old_value) + intval($new_value);
	return $new_value;
}

function deal_with_settings() 
{
	add_filter("pre_update_option_num_pages", "test_posts_num_add", 10, 2 );
	add_action("update_option_num_pages","create_test_posts", 10, 3);
}

add_action("init", "deal_with_settings");

add_action("admin_menu", "add_settings_page");

function pluginPage()
{
	?>
	<div class="wrap">
		<h1 id="heading"><?php echo esc_html(get_admin_page_title())?></h1>
		<form action="options.php" method="POST">
			<?php 
			$page = "test-post-generator";
			settings_fields($page);
			do_settings_sections($page);
			submit_button("Generate");?>
		</form>
		<form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
			<input type="hidden" name="action" value="delete_test_posts">
			<?php submit_button("Delete");
			wp_nonce_field("delete_test_posts","test_field_nonce");?>
		</form>
	</div>
	<?php 
}

function delete_test_posts() 
{
	if (check_admin_referer("delete_test_posts","test_field_nonce"))
	    {
		$catID = get_cat_ID("Test");
		$posts = get_posts(array("post_type" => "post", "numberposts" => -1, "category" => array($catID)));

		foreach($posts as $post)
		{
			wp_trash_post($post->ID,false);
		} 
		wp_redirect(admin_url('admin.php?page=test-post-generator'));
		die();
	    }
}
add_action("admin_post_delete_test_posts", "delete_test_posts");

function create_test_posts($old_value,$value,$option)
{
	//implement some random capability to insert random text to the post
	
    $text = "Test";
    $headingNum = 1;
    $textComplete = "<h" . $headingNum . ">" . $text . "</h" . $headingNum . ">";

	$curPageNum = intval($old_value);
	
	$newPageTotal = intval($value);
	
	$category_arr = array(
	    "cat_name" => "Test Post");
	
	$cat_id = wp_insert_category($category_arr);
	
	while ($curPageNum < $newPageTotal)
	{
		$title = "Test Page" . " " . $curPageNum;

		$post_data = array(
		"post_title" => $title,
		"post_type" => "post",
		"post_content" => $textComplete,
		"post_status" => "publish",
		"post_category" => array($cat_id)	
		
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
