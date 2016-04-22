<?php


add_action('admin_menu', 'x2764tech_bp_twitter_plugin_menu');

add_action('network_admin_menu', 'x2764tech_bp_twitter_plugin_menu');


function x2764tech_bp_twitter_plugin_menu()
{

    add_submenu_page('bp-general-settings', 'Bp Twitter', 'BuddyPress Twitter', 'manage_options', 'x2764tech-bp-twitter', 'x2764tech_bp_twitter_plugin_options');


    //call register settings function

    add_action('admin_init', 'x2764tech_bp_twitter_register_settings');

}

function x2764tech_bp_twitter_register_settings()
{
    global $x2764tech_bp_twitter;
    foreach($x2764tech_bp_twitter->MEMBER_MAP as $_ => $option_name) {
        register_setting('x2764tech_bp_twitter_plugin_options', $option_name);
    }
}


function x2764tech_bp_twitter_plugin_options()
{
    global $x2764tech_bp_twitter;
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    ?>


    <?php if (!empty($_GET['settings-updated'])) : ?>
    <div id="message" class="updated">
        <p><strong><?php _e('Buddypress Twitter Settings have been saved.'); ?></strong></p>
    </div>
    <?php endif; ?>


    <div class="wrap">

        <h2>
            <?php _e('BuddyPress Twitter Settings', 'x2764tech_bp_twitter') ?>
        </h2>


        <h3><?php _e('Member and Group Components.', 'x2764tech_bp_twitter') ?></h3>


        <p><?php _e('The plugin uses Buddypress XProfile Fields and requires you to name the "Mirror Profile Field Title" below the same as your custom Profile Field Title - Please read the <a href="http://wordpress.org/extend/plugins/buddypress-twitter/installation/" target="_blank" title="Opens in a new tab">plugin installation instructions</a> if you are not sure what to do.', 'x2764tech_bp_twitter') ?></p>

        <form method="post" action="<?php echo admin_url('options.php'); ?>">

            <?php wp_nonce_field('update-options'); ?>


            <table class="form-table">


                <hr />


                <?php // members admin options ?>


                <table class="form-table">


                    <tr valign="top">

                        <th scope="row"><b>Members</b></th>

                        <td>
                            <input type="checkbox" name="<?php echo x2764tech_BuddyPress_Twitter::OPTION_MEMBERS ?>"
                                   value="1" <?php if ($x2764tech_bp_twitter->get_members()) echo 'checked="checked"'; ?>/>
                            Let your members display their twitter follow button on their profile page.
                        </td>

                    </tr>

                    <tr valign="top">
                        <th scope="row">
                            <span style="color: red;">Mirror</span>
                            Profile Field Title
                        </th>
                        <td>
                            <input <?php if (!$x2764tech_bp_twitter->get_members()) { ?>disabled="disabled"<?php } ?>
                                   name="<?php echo x2764tech_BuddyPress_Twitter::OPTION_MEMBER_LABEL ?>"
                                   value="<?php echo esc_attr($x2764tech_bp_twitter->get_member_label()) ?>" />
                                <?php if (!$x2764tech_bp_twitter->get_members()) { ?>
                                <br/>
                                    <i>
                                        <span style="color: orange;">Disabled</span>
                                        - Tick the check-box above and save to enable this feature
                                    </i>
                                <?php } ?>
                            <p>
                                <span style="color: green;">Quick links:</span>
                                Visit <a
                                    href="<?php echo home_url('/wp-admin/admin.php?page=bp-profile-setup&group_id=1&mode=add_field') ?>"
                                    target="_blank" title="opens in a new tab">Add Field</a> to set up a new XProfile
                                field or <a href="<?php echo home_url(); ?>/wp-admin/admin.php?page=bp-profile-setup"
                                            target="_blank" title="opens in a new tab">Extended Profile Fields</a> to
                                edit a existing field
                            </p>
                        </td>
                    </tr>
                </table>

                <?php // groups admin options ?>

                <table class="form-table">


                    <tr valign="top">

                        <th scope="row"><b>Groups</b></th>

                        <td>
                            <input type="checkbox" name="<?php echo x2764tech_BuddyPress_Twitter::OPTION_GROUPS ?>"
                                   value="true" <?php if ($x2764tech_bp_twitter->get_groups()) echo 'checked="checked"'; ?>/>
                            Let your groups display their Twitter follow button on the group's home page.
                        </td>

                    </tr>


                    <tr valign="top">
                        <th scope="row">Group Field Title</th>
                        <td>
                            <input <?php if (!$x2764tech_bp_twitter->get_groups()) { ?>disabled="disabled"<?php } ?>
                                   name="x2764tech_bp_twittergroup_label"
                                   value="<?php echo esc_attr($x2764tech_bp_twitter->get_group_label()) ?>"/>
                            <?php if (!$x2764tech_bp_twitter->get_groups()) { ?>
                                <br/>
                                <i>
                                    <span style="color: orange;">Disabled</span>
                                    - Tick the check-box above and save to enable this feature
                                </i>
                            <?php } ?>
                        </td>
                    </tr>
                </table>


                <input type="hidden" name="action" value="update"/>
                <input type="hidden" name="page_options"
                       value="<?php
                       echo implode(',', array( x2764tech_BuddyPress_Twitter::OPTION_MEMBERS,
                           x2764tech_BuddyPress_Twitter::OPTION_GROUPS,
                           x2764tech_BuddyPress_Twitter::OPTION_GROUP_LABEL,
                           x2764tech_BuddyPress_Twitter::OPTION_MEMBER_LABEL));
                       ?>"/>


                <p class="submit">

                    <input type="submit" class="button-primary" value="<?php _e('Save Component Settings', 'x2764tech_bp_twitter') ?>"/>

                </p>


        </form>


        <h3><?php _e('Display Settings.', 'x2764tech_bp_twitter') ?></h3>


        <p><?php _e('Alter the appearance of the twitter button - note that the appearance will be the same for members and for groups. Click the save button to preview the changes.', 'x2764tech_bp_twitter') ?></p>

        <form method="post" action="<?php echo admin_url('options.php'); ?>">

            <?php wp_nonce_field('update-options'); ?>


            <table class="form-table">
                <hr/>


                <tr valign="top">

                    <th scope="row"><b>Groups Button Position</b></th>

                    <td>
                        <label>
                            <input <?php if (!$x2764tech_bp_twitter->get_groups()) { ?>disabled="disabled"<?php } ?>
                                   type="radio" name="<?php echo x2764tech_BuddyPress_Twitter::OPTION_GROUPS_PLACEMENT ?>"
                                   value="" <?php if (!$x2764tech_bp_twitter->get_groups_placement()) echo 'checked="checked"'; ?> />
                            Before the group description</label>
                        <br/>
                        <label>
                            <input <?php if (!$x2764tech_bp_twitter->get_groups()) { ?>disabled="disabled"<?php } ?>
                                   type="radio" name="<?php echo x2764tech_BuddyPress_Twitter::OPTION_GROUPS_PLACEMENT ?>"
                                   value="1" <?php if ($x2764tech_bp_twitter->get_groups_placement()) echo 'checked="checked"'; ?> />
                            After the group description</label>
                        <?php if (!$x2764tech_bp_twitter->get_groups()) { ?>
                            <br/>
                            <i>
                            <span style="color: orange;">Disabled</span>
                            - This feature requires the groups component to be enable above.</i>
                        <?php } ?>
                    </td>

                </tr>

                <tr valign="top">

                    <th scope="row"><b>Follower Button Size</b></th>

                    <td>
                        <label>
                            <input type="radio" name="<?php echo x2764tech_BuddyPress_Twitter::OPTION_BUTTON_SIZE ?>"
                                   value="" <?php if (!$x2764tech_bp_twitter->get_button_size()) echo 'checked="checked"'; ?> />
                            Normal</label>
                        <br/>
                        <label>
                            <input type="radio" name="<?php echo x2764tech_BuddyPress_Twitter::OPTION_BUTTON_SIZE ?>"
                                   value="1" <?php if ($x2764tech_bp_twitter->get_button_size()) echo 'checked="checked"'; ?> />
                            Large</label>
                    </td>

                </tr>

                <tr valign="top">

                    <th scope="row"><b>Follower Count</b></th>

                    <td>
                        <input type="checkbox" name="<? echo x2764tech_BuddyPress_Twitter::OPTION_COUNT ?>"
                               value="1" <?php if ($x2764tech_bp_twitter->get_count()) echo 'checked="checked"'; ?>/>
                        Shows the user's/group's follower count next to their follow button.
                    </td>

                </tr>


            </table>

            <div id="bp-twitter-button-preview" style="padding:0 10px 10px;margin-top:20px;border: 1px solid #CCC;">

                <p>
                    <span style="color: green;">Button Preview</span>
                </p>

                <a href="https://twitter.com/itsCharlKruger" class="twitter-follow-button"
                   data-show-count="<?php if ($x2764tech_bp_twitter->get_count()) {
                       echo 'true';
                   } else {
                       echo 'false';
                   } ?>" <?php if ($x2764tech_bp_twitter->get_button_size()) echo 'data-size="large""'; ?> >Follow
                    @itsCharlKruger</a>
                <script>!function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (!d.getElementById(id)) {
                            js = d.createElement(s);
                            js.id = id;
                            js.src = "//platform.twitter.com/widgets.js";
                            fjs.parentNode.insertBefore(js, fjs);
                        }
                    }(document, "script", "twitter-wjs");</script>

            </div>

            <input type="hidden" name="action" value="update"/>
            <input type="hidden" name="page_options"
                   value="<?php
                    echo implode(',', array(
                        x2764tech_BuddyPress_Twitter::OPTION_GROUPS_PLACEMENT,
                        x2764tech_BuddyPress_Twitter::OPTION_COUNT,
                        x2764tech_BuddyPress_Twitter::OPTION_BUTTON_SIZE
                        ) );
                   ?>"/>


            <p class="submit">

                <input type="submit" class="button-primary" value="<?php _e('Save Display Settings') ?>"/>

            </p>


        </form>


        <p>If you enjoy the plugin and would like to keep up to speed on future features and updates, <b>follow me on
                twitter</b> or check out my blog - <a href="http://charlkruger.com" target="_blank">CharlKruger.com</a>
        </p>
        <p>Feel free to retweet the plugin to let your followers know</p>
        <a href="https://twitter.com/share" class="twitter-share-button"
           data-url="http://buddypress.org/community/groups/buddypress-twitter/home/"
           data-text="Let your #Buddypress members and groups add their twitter follow button to their profiles"
           data-via="itsCharlKruger">Tweet</a>
        <script>!function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (!d.getElementById(id)) {
                    js = d.createElement(s);
                    js.id = id;
                    js.src = "//platform.twitter.com/widgets.js";
                    fjs.parentNode.insertBefore(js, fjs);
                }
            }(document, "script", "twitter-wjs");</script>

    </div>

    <?php

}


?>