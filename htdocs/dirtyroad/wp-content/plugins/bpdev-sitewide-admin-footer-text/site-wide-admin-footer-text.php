<?php
/*
Plugin Name:Bpdev Sitewide Admin Footer Text
Description:Change the contents of wordpress mu backend(admin) footer sitewide
Version: 1.1
Requires at least: wpmu 2.8
Tested up to:wpmu 2.8.6
License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html
Author: Brajesh Singh
Author URI: http://buddydev.com
Plugin URI:http://buddydev.com/category/plugins/
Sitewide Only:true
Last Modified: December 10, 2009
*/
/***
    Copyright (C) 2009 Brajesh Singh(buddydev.com)

    This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or  any later version.

    This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses>.

    */
//provide a text box for siteadmin to edit
add_action('wpmu_options', 'bpdev_show_sitewide_admin_footer_content_box');
//for updating the footer conytent to database
add_action('update_wpmu_options', 'bpdev_update_sitewide_admin_footer_content');
//filter the footer text
add_filter('admin_footer_text', 'bpdev_sitewide_admin_footer_content');
/**
*@desc it adds a box on the options page to allow Site Admin for updating the footer content
*/
function bpdev_show_sitewide_admin_footer_content_box()
{
//if there is already the sitewide footer content
//show it in the form 
$site_wide_admin_footer_content=get_site_option("sitewide_admin_footer_content");

?>
<table class="form-table">
	<tr valign="top">
				<th scope="row"><?php _e("Sitewide Admin Footer Content","bpdev");?></th> 
					<td>
						<textarea rows="5" cols="40" id="bpdev_sitewide_admin_footer_content" name="bpdev_sitewide_admin_footer_content"><?php echo stripslashes( $site_wide_admin_footer_content ); ?></textarea>
						
					<br/>
					<?php _e("If you want you can use XHTML markup here.","bpdev");?>				</td> 
			</tr>
	</table>
<?php

}

/**
*@desc This method updates the sitewide footer content to database
*/
function bpdev_update_sitewide_admin_footer_content()
{
//get the content of sidteside footer content
   $sitewide_admin_footer_content = $_POST['bpdev_sitewide_admin_footer_content'];
	if(empty($sitewide_admin_footer_content))
	 $sitewide_admin_footer_content='';

	//let us store it in site meta table

	update_site_option( 'sitewide_admin_footer_content' , $sitewide_admin_footer_content );


}


//show the changed sitewide footer text depending on admin prefs
function bpdev_sitewide_admin_footer_content($footer_content)
{
//get the sitewide footer content
  $site_wide_admin_footer_content=get_site_option("sitewide_admin_footer_content");

	if(empty($site_wide_admin_footer_content))
		return $footer_content;//do not modify
	else
		return stripslashes($site_wide_admin_footer_content);//let us return the modified content
}
?>