<?php

// Add the form

add_filter('groups_custom_group_fields_editable', 'x2764tech_bp_twitter_group_fields');
add_action('groups_group_details_edited', 'x2764tech_bp_twitter_group_header_save');
add_action('groups_created_group', 'x2764tech_bp_twitter_group_header_save');
add_filter('bp_get_group_description', 'x2764tech_bp_twitter_show_field_in_header');
add_filter('bp_before_group_header_meta', 'x2764tech_bp_twitter_show_field_before_header');

function x2764tech_bp_twitter_group_fields()
{
    global $x2764tech_bp_twitter;

    ?>
    <label for="group-field-x2764tech-bp-twitter"><?php echo $x2764tech_bp_twitter->get_group_label(); ?></label>
    <input type="text" name="group-field-x2764tech-bp-twitter" id="group-field-x2764tech-bp-twitter" value="<?php echo x2764tech_bp_twitter_group_handle(); ?>"/>
    <?php

}

// show the group score in group header
function x2764tech_bp_twitter_show_field_in_header($plus_field_meta)
{
    global $x2764tech_bp_twitter;
    if ($x2764tech_bp_twitter->get_groups_placement()) {
        $plus_field_meta .= $x2764tech_bp_twitter->follow_markup(x2764tech_bp_twitter_group_handle());
    }

    return $plus_field_meta;
}



// show the group twittercj button in group header - before the description
function x2764tech_bp_twitter_show_field_before_header()
{
    global $x2764tech_bp_twitter;
    if (x2764tech_bp_twitter_group_handle() != '' && !$x2764tech_bp_twitter->get_groups_placement()) { // check to see the twittercj field has data

        echo x2764tech_bp_twitter_group_handle();
        echo $x2764tech_bp_twitter->follow_markup(x2764tech_bp_twitter_group_handle());
    }
}

function x2764tech_bp_twitter_group_handle() {
    $new_value = groups_get_groupmeta( bp_get_group_id(), 'x2764tech-bp-twitter');
    if(!empty($new_value)) {
        return $new_value;
    }
    return groups_get_groupmeta( bp_get_group_id(), 'group_plus_header_field-onecj');
}


function x2764tech_bp_twitter_group_header_save($group_id)
{
    if (isset($_POST['group-field-x2764tech-bp-twitter'])) {
        $value = $_POST['group-field-x2764tech-bp-twitter'];
        groups_update_groupmeta($group_id, 'x2764tech-bp-twitter', $value);
    }
}


?>