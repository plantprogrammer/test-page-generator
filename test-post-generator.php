<?php
/*
* Plugin Name: Test Post Generator
* Description: This plug-in will automatically generate posts for you, reducing the time to manually create multiple posts to test whatever you need.
* Version: 1.0
* Author: plantprogrammer
* Text Domain: test-page-generator
* Author URI: https://iansackofwits.com
* License: GPLv2 or later
* License URI: https://www.gnu.org/licenses/gpl-3.0.en.html 
* Tested up to: 5.4
* Version: 1.0
* Requires at least: 5.0
* Requires PHP: 5.6
*/

namespace test_post_generator;

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

define("TEST_POST_CAT_NAME", "Test Post");
define("TEST_POST_SETTING_NAME", "test_post_generator_num_pages");
define("TEST_POST_PLUGIN_NAMESPACE", "test_post_generator\\");

register_deactivation_hook(__FILE__, TEST_POST_PLUGIN_NAMESPACE . "trash_test_posts");
register_activation_hook(__FILE__, TEST_POST_PLUGIN_NAMESPACE . "add_test_post_category");

function add_test_post_category()
{
    $category_arr = array(
    	    "cat_name" => TEST_POST_CAT_NAME);
    	
    $cat_id = wp_insert_category($category_arr);
    add_option(TEST_POST_SETTING_NAME,"1","","no");
}

function add_test_post_settings_page()
{
	$page_title = "Test Post Generator Settings";
	$menu_title = "Test Post";
	$capability = "manage_options";
	$menu_slug = "test-post-generator";
	$plugin_function = TEST_POST_PLUGIN_NAMESPACE . "test_post_settings_page";
	add_menu_page($page_title, $menu_title, $capability, $menu_slug, $plugin_function);
}

add_action("admin_init", TEST_POST_PLUGIN_NAMESPACE. "test_post_page_number_setting");

/*Sets up the settings option responsible for keeping track of the current page number*/
function test_post_page_number_setting()
{
	$settings_group = "test-post-generator";
	$setting_name = TEST_POST_SETTING_NAME;
	$setting_callback = "check_num_pages";
	register_setting($settings_group, $setting_name, $setting_callback);
	function setting_callback($input)
	{
	    $validated_num = absint($input);
	    
	    //arbitrarily chose 1000 as the post limit. should be limit as WordPress hangs if it creates many posts.
	    if ($validated_num > 1000)
	    {
	        $validated_num = 1000;       
	    }
	    return $validated_num;
	}
	
	$page = $settings_group;
	$section_title = "Generate Test Posts";
	add_settings_section($setting_name, $section_title, null, $page);
	
	$field_title = "Number of Posts";
	$settings_field_callback = TEST_POST_PLUGIN_NAMESPACE . "render_settings_field";
	add_settings_field($setting_name, $field_title, $settings_field_callback, $page, $setting_name);
	function render_settings_field()
	{
		echo "<input type='number' id='" . TEST_POST_SETTING_NAME . "' name='" . TEST_POST_SETTING_NAME . "' max='1000' min='0'>";	
	}
}

/*Increases the current page number settings option to reflect the recently added pages*/
function test_post_num_add($new_value, $old_value) 
{
	$new_value = intval($old_value) + intval($new_value);
	return $new_value;
}

function test_post_deal_with_settings() 
{
	add_filter("pre_update_option_" . TEST_POST_SETTING_NAME, TEST_POST_PLUGIN_NAMESPACE . "test_post_num_add", 10, 2 );
	add_action("update_option_" . TEST_POST_SETTING_NAME, TEST_POST_PLUGIN_NAMESPACE . "create_test_posts", 10, 3);
}

add_action("init", TEST_POST_PLUGIN_NAMESPACE . "test_post_deal_with_settings");
add_action("admin_menu", TEST_POST_PLUGIN_NAMESPACE . "add_test_post_settings_page");

function test_post_settings_page()
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
		    <h2>Trash All Generated Test Posts</h2>
			<input type="hidden" name="action" value="delete_test_posts">
			<?php submit_button("Trash All");
			wp_nonce_field("delete_test_posts","test_field_nonce");?>
		</form>
	</div>
	<?php 
}

/*Will be called when the user clicks on the button to delete the posts within the settings page*/
function delete_test_posts() 
{
	if (check_admin_referer("delete_test_posts", "test_field_nonce"))
	    {
    	    trash_test_posts();
    	    wp_redirect(admin_url('admin.php?page=test-post-generator'));
	        die();
	    }
	wp_die();
}
add_action("admin_post_delete_test_posts", TEST_POST_PLUGIN_NAMESPACE . "delete_test_posts");

function create_test_posts($old_value, $value, $option)
{
	$post_text = "<p>Lorem ipsum dolor sit amet, ad tota quaerendum per, duo debitis volumus at, ad regione voluptua quo. 
	Mel eripuit erroribus in, eum no dicunt signiferumque. Ut integre incorrupte cum, sed at harum oratio laboramus. 
	Nam et liber volutpat. Eum sententiae reprimique theophrastus et, tollit mucius accumsan ei cum. 
	Mel integre accusam epicuri te, eum eu meis dictas abhorreant. Mei ea nulla scripta expetendis, ad nec omnes tincidunt.</p>";

	$curPageNum = intval($old_value);
	
	$newPageTotal = intval($value);
	
	$cat_id = get_cat_ID(TEST_POST_CAT_NAME);
	
	while ($curPageNum < $newPageTotal)
	{
		$title = "Test Post" . " " . $curPageNum;

		$post_data = array(
		"post_title" => $title,
		"post_type" => "post",
		"post_content" => $post_text,
		"post_status" => "private",
		"post_category" => array($cat_id)	
	);
	$curPageNum++;	
	wp_insert_post($post_data);
	}
}

/*Trashes all the test posts when the plugin is deactivated*/
function trash_test_posts()
{
	$cat_id = get_cat_ID(TEST_POST_CAT_NAME);
	$posts = get_posts(array("post_status" => "private", "post_type" => "post", "numberposts" => -1, "category" => array($cat_id)));

	foreach($posts as $post)
	{
		wp_trash_post($post->ID);
	}
}

