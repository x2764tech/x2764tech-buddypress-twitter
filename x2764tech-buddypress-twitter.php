<?php
/**
 * Plugin Name: x2764tech BuddyPress Twitter
 * Plugin URI: http://buddypress.org/community/groups/buddypress-twitter
 * Description: Let your members and groups show their Twitter Follow Button on their profile page and group page. Using the twitter's @username widget, the plugin fetches your members and/or groups username and displays their folow button in the member's/group's header.
 *
 * If your BuddyPress community is active on Twitter, this plugin is a great tool for boosting communication both on and off your website.
 * Version: 1.3
 * Author: x2764tech
 * Author URI: https://github.com/x2764tech
 * License: GPL2
 **/

if(!function_exists('x2764tech_bp_twitter_init')) {

    /**
     * Class x2764tech_BuddyPress_Twitter
     * @property bool   members
     * @property bool   groups
     * @property bool   groups_placement
     * @property bool   count
     * @property bool   button_size
     * @property string member_label
     * @property string group_label
     */
    class x2764tech_BuddyPress_Twitter
    {
        const CurrentVersion = '1.3';
        public $MEMBER_MAP = array(
        'members' => self::OPTION_MEMBERS,
        'groups'  => self::OPTION_GROUPS,
        'groups_placement' => self::OPTION_GROUPS_PLACEMENT,
        'count' => self::OPTION_COUNT,
        'button_size' => self::OPTION_BUTTON_SIZE,
        'member_label' => self::OPTION_MEMBER_LABEL,
        'group_label' => self::OPTION_GROUP_LABEL
        );
        const OPTION_MEMBERS = 'x2764tech-bp-twitter_members';
        const OPTION_GROUPS = 'x2764tech-bp-twitter_groups';
        const OPTION_GROUPS_PLACEMENT = 'x2764tech-bp-twitter_groups-placement';
        const OPTION_COUNT = 'x2764tech-bp-twitter_count';
        const OPTION_BUTTON_SIZE = 'x2764tech_bp_twitter_button_size';
        const OPTION_MEMBER_LABEL = 'x2764tech_bp_twitter_member_label';
        const OPTION_GROUP_LABEL = 'x2764tech_bp_twitter_group_label';


        public function get_members() { return $this->get_option('members'); }
        public function set_members($value) { $this->set_option('members',  $value); }
        public function get_groups() { return $this->get_option('groups'); }

        /** @param bool $value */
        public function set_groups($value) { $this->set_option('groups',  $value); }
        /** return int|void 0 (or false) - Before the group description, else 1 */
        public function get_groups_placement() { return $this->get_option('groups_placement'); }
        /** @param int $value 0 (or false) - Before the group description, else 1 */
        public function set_groups_placement($value) { $this->set_option('groups_placement',  $value); }

        public function get_count() { return $this->get_option('count'); }
        public function set_count($value) { $this->set_option('count',  $value); }

        /**
         * @return int|void truthy if you should use large button
         */
        public function get_button_size() { return $this->get_option('button_size'); }
        public function set_button_size($value) { $this->set_option('button_size',  $value); }
        public function get_member_label() { return $this->get_option('member_label'); }
        public function set_member_label($value) { $this->set_option('member_label',  $value); }
        public function get_group_label() { return $this->get_option('group_label'); }
        public function set_group_label($value) { $this->set_option('group_label',  $value); }

        /**
         * @var x2764tech_BuddyPress_Twitter
         */
        protected static $instance = null;

        protected function __construct()
        {
            //Thou shalt not construct that which is not constructable!
        }

        /**
         * @param $name
         * @param $value
         */
        private function set_option($name, $value)
        {
            if (array_key_exists($name, $this->MEMBER_MAP)) {
                $option = $this->MEMBER_MAP[$name];
                update_option($option, $value, true);
            }
        }

        /**
         * @param $name
         * @return mixed|void
         */
        public function get_option($name)
        {
            if (array_key_exists($name, $this->MEMBER_MAP)) {
                $option = $this->MEMBER_MAP[$name];
                return get_option($option);
            }
        }

        protected function __clone()
        {
            //Me not like clones! Me smash clones!
        }

        /**
         * @return x2764tech_BuddyPress_Twitter the one and only!
         */
        public static function getInstance()
        {
            if (!isset(static::$instance)) {
                static::$instance = new static;
            }
            return static::$instance;
        }

        public function __get($name)
        {
            if(property_exists($this, $name)) {
                return $this->$name;
            }
            return $this->get_option($name);
        }

        public function __set($name, $value) {
            if(property_exists($this, $name)) {
                return $this->$name;
            }
            $this->set_option($name, $value);
        }

        public function follow_markup($handle) {
            if(empty($handle)) return '';
            $handle = esc_html($handle);
            $count_attribute = $this->get_count() ? 'true' : 'false';
            $size_attribute = $this->get_button_size() ? 'data-size="large"' : '';
            return <<<HTML
            <a href="https://twitter.com/{$handle}" class="twitter-follow-button" data-show-count="{$count_attribute}" {$size_attribute} >Follow @{$handle}</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
HTML;

        }
    }

    global $x2764tech_bp_twitter;
    $x2764tech_bp_twitter = x2764tech_BuddyPress_Twitter::getInstance();

    function x2764tech_bp_twitter_init()
    {
        global $x2764tech_bp_twitter;

        require(dirname(__FILE__) . '/admin.php');

        if ($x2764tech_bp_twitter->get_members()) {
            require(dirname(__FILE__) . '/includes/twitter-members-extension.php');
        }

        if ($x2764tech_bp_twitter->get_groups()) {
            require(dirname(__FILE__) . '/includes/twitter-groups-extension.php');
        }


        function x2764tech_bp_twitter_enqueue_styles()
        {
            wp_enqueue_style('x2764tech_bp_twitter', plugin_dir_url(__FILE__) . 'style.css');
        }

        add_action('init', 'x2764tech_bp_twitter_enqueue_styles');
    }

    add_action('bp_include', 'x2764tech_bp_twitter_init');


    function x2764tech_bp_twitter_activate_version_1_3()
    {
        $version = get_option('x2764tech_bp_twitter_version', '0');
        if(version_compare($version, '1.3.1', '>=')) {
            return;
        }
        deactivate_plugins('buddypress-twitter/buddypress-twitter.php');
        global $x2764tech_bp_twitter;
        if ($old_members = get_option('twittercj-members')) {
            $x2764tech_bp_twitter->set_members(true);
        }

        if ($old_groups = get_option('twittercj-groups')) {
            $x2764tech_bp_twitter->set_groups(true);
        }

        if($old_groups_placement = get_option('twittercj-groups-placement')) {
            $x2764tech_bp_twitter->set_groups_placement(true);
        }

        if($old_count = get_option('twittercj-count')) {
            $x2764tech_bp_twitter->set_count(true);
        }


        if($old_button_size = get_option('twittercj-button-size')) {
            $x2764tech_bp_twitter->set_button_size(true);
        }

        if($old_member_label = get_option('twittercj_member_label')) {
            $x2764tech_bp_twitter->set_member_label($old_member_label);
        }

        if($old_group_label = get_option('twittercj_group_label')) {
            $x2764tech_bp_twitter->set_group_label($old_group_label);
        }

        update_option('x2764tech_bp_twitter_version', '1.3');
    }

    register_activation_hook(__FILE__, 'x2764tech_bp_twitter_activate_version_1_3');
}