<?php

if (!defined("WP_UNINSTALL_PLUGIN")
{
	wp_die();
}

$pages = get_posts(array("post_type" => "post", "numberposts" => -1, "category" => "Test"));

foreach($pages as $page)
{
	wp_delete_post($page->ID,false);
}

?>