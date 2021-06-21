<?php

	/*  BPS filters */


add_filter ('bps_current_page', 'pp_mm_current_page', 999 );
function pp_mm_current_page ($current) {

	foreach (bps_directories() as $dir) {

		if ($current == $dir->path. 'membersmap/')  {

			$current = $dir->path . 'membersmap/';

			return $current;

		}

	}

	return $current;
}


add_filter ('bps_add_directory', 'pp_mm_add_my_directories');		// add the [membersmap] shortcode pages as directories
function pp_mm_add_my_directories ($dirs) {

	$pages = get_pages ();
	foreach ($pages as $page)  if ( has_shortcode ($page->post_content, 'membersmap') ) {
		$dir = new stdClass;
		$dir->id = $page->ID;
		$dir->title = $page->post_title;
		$dir->path = parse_url (get_page_link ($page->ID), PHP_URL_PATH);
		$dirs[$page->ID] = $dir;
	}

	return $dirs;
}


add_action ('bps_before_search_form', 'pp_mm_bps_form_action');
function pp_mm_bps_form_action ($form) {

	$requested_url = bp_get_requested_url();

	if( ( strpos( $requested_url, BP_MEMBERS_SLUG ) !== false ) && ( strpos( $requested_url, 'membersmap' ) !== false ) ) {
		$form->action = $requested_url;
	}

}

	/* end BPS filters */

