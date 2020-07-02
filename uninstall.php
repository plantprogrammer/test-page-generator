<?php

namespace test_page_generator;

define("SETTING_NAME", "test_post_generator_num_pages");
define("CAT_NAME", "Test Post");

if (!defined("WP_UNINSTALL_PLUGIN"))
{
	wp_die();
}

$cat_ID = get_cat_ID(CAT_NAME);
$posts = get_posts(array("post_type" => "post", "numberposts" => -1, "category" => array($cat_ID)));
	
foreach($posts as $post)
{
	wp_delete_post($post->ID,false);
}

wp_delete_category(CAT_NAME);
delete_option(SETTING_NAME);
