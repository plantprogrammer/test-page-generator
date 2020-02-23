<?php

if (!defined("WP_UNINSTALL_PLUGIN"))
{
	wp_die();
}

$catID = get_cat_ID("Test");
$pages = get_posts(array("post_type" => "post", "numberposts" => -1, "category" => array($catID)));
	
foreach($pages as $page)
{
	wp_delete_post($page->ID,false);

}

wp_delete_category($catID);

?>