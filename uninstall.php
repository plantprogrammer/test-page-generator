<?php

namespace test_page_generator;

define("TEST_POST_SETTING_NAME", "test_post_generator_num_pages");
define("TEST_POST_CAT_NAME", "Test Post");

if (!defined("WP_UNINSTALL_PLUGIN"))
{
	wp_die();
}

$cat_ID = get_cat_ID(TEST_POST_CAT_NAME);
$posts = get_posts(array("post_status" => "any, trash","post_type" => "post", "numberposts" => -1, "category" => array($cat_ID)));

foreach($posts as $post)
{
	wp_delete_post($post->ID,true);
}

wp_delete_category($cat_ID);
delete_option(TEST_POST_SETTING_NAME);


