<?php

if (!defined("WP_UNINSTALL_PLUGIN"))
{
	wp_die();
}

$cat_ID = get_cat_ID("Test Post");
$pages = get_posts(array("post_type" => "post", "numberposts" => -1, "category" => array($cat_ID)));
	
foreach($posts as $post)
{
	wp_delete_post($post->ID,false);

}

wp_delete_category($cat_ID);
delete_option("test-post-generator-num-pages");
