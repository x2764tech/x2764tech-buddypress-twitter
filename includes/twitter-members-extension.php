<?php


add_filter( 'bp_before_member_header_meta', 'x2764tech_bp_twitter_member_details');

/**
 *  Display the twitter widget using our xprofile data and return it in the members header
 */
function x2764tech_bp_twitter_member_details() {
    global $x2764tech_bp_twitter;
    $twitter_handle = xprofile_get_field_data($x2764tech_bp_twitter->get_member_label()) ; //fetch the location field for the displayed user
    if ( $twitter_handle != "" ) { // check to see the twitter field has data

        echo $x2764tech_bp_twitter->follow_markup($twitter_handle);
	}
}