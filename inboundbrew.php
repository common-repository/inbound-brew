<?php
/* --inbound-brew-free-start-- */
/**
 * Plugin Name: Inbound Brew
 * Plugin URI: http://www.inboundbrew.com
 * Description: Inbound Brew is a complete inbound marketing, or permission marketing, toolbox. Everything you need to drive traffic and convert visitors without the heavy price tag. Inbound Brew comes with SEO (keywords, sitemeta, robots and keyword management), Lead nurturing, a CRM, and more.
 * Version: 1.9.4
 * Author: Inbound Brew
 * Author URI: http://www.inboundbrew.com
 * License: GPL2
 */
/* --inbound-brew-free-end-- */

defined('ABSPATH') or die('No script kiddies please!');
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/* --inbound-brew-free-start-- */
if (is_dir(dirname(__FILE__) . "/../inbound-brew-pro")) {

    function ib_pro_notice() {
        ?>
        <div class="updated notice">
            <p>We've deactivated the Free Version of Inbound Brew. Please activate your new, shiny Pro Version, then make sure to enter your license key :)</p>
        </div>
        <?php
    }

    add_action('admin_notices', 'ib_pro_notice');
    deactivate_plugins('inbound-brew/inboundbrew.php');
    return;
}
/* --inbound-brew-free-end-- */

//BEGIN FREEMIUS
// Create a helper function for easy SDK access.
function ib_fs() {
    global $ib_fs;

    if (!isset($ib_fs)) {
        //// Include Freemius SDK.
        require_once dirname(__FILE__) . '/src/Libraries/freemius/start.php';

        $ib_fs = fs_dynamic_init(array(
            'id' => '973',
            'slug' => 'inbound-brew',
            'type' => 'plugin',
            'public_key' => 'pk_b1d9ee09c4ca8a7e4320f20ac91b0',
            /* --inbound-brew-free-start-- */
            'is_premium' => false,
            /* --inbound-brew-free-end-- */

            'has_addons' => false,
            'has_paid_plans' => false,
            'menu' => array(
                'first-path' => "admin.php?page=ib-admin-getting-started",
                'account' => false,
                'contact' => false,
                'support' => false,
            ),
        ));
    }

    return $ib_fs;
}

//Init Freemius .
$fs = ib_fs();
define('BREW_FS_TRACKING', $fs->is_tracking_allowed());
//Signal that SDK was initiated .
do_action('ib_fs_loaded');
//END FREEMIUS
// data and database variables
define('BREW_DB_VERSION', 2.6);

define('BREW_DATA_VERSION', 1.3);
define('BREW_SETTINGS_VERSION', 1.5);
define('BREW_PLUGIN_VERSION', '1.9.4');

// config values
define('BREW_ASSET_VERSION', '2.4');
// NAVIGATION
define('IB_TOP_NAV_VALUES', "ib_dynamic_navigation");
define('BREW_DEFAULT_LAYOUT_OPTION', "ib_default_layout");
define('BREW_SOCIAL_WIDGET_SETTINGS', "ib_social_share_widget_settings");
define('BREW_SOCIAL_WIDGET_OPTIONS', "ib_social_share_widget_options");
define('BREW_ACTIVE_MODULES_OPTION', "ib_active_modules");
define('BREW_REDIRECT_SETTINGS_OPTION', "ib_redirect_settings");
define('BREW_DEFAULT_LEAD_VIEW_SETTINGS_OPTION', "ib_default_lead_view_settings");
define('BREW_USER_DEFAULT_LEAD_VIEW_OPTION', "ib_user_default_lead_view");

$host = DB_HOST;
if (!defined('DB_PORT')) {
    $port = '3306';

    if (stripos(DB_HOST, ":") != false) {
        $parts = explode(":", DB_HOST);
        $host = $parts[0];
        $port = $parts[1];
    }
    define('DB_PORT', $port);
}
define('IB_DB_HOST', $host);

function print_debug($data, $exit = false) {
    echo '<pre>';
    print_r($data);
    if ($exit)
        die();
    else
        echo '</pre>';
}

// inbound brew navigation
global $ib_dynamic_navigation;
$ib_dynamic_navigation = array(
    'navigation' => array(
        'dashboard' => array(
            'title' => "Dashboard",
            'class' => "line-chart",
            'page' => "inboundbrew",
        ),
        'cta' => array(
            'title' => "CTA",
            'page_title' => "CTA Management",
            'class' => "bullhorn",
            'page' => "ib-call-to-action",
            'is_module' => true,
            'module_name' => "CTA's",
        ),
        'landing_page' => array(
            'title' => "Landing Pages",
            'class' => "file-text-o",
            'page' => "landing-page-admin",
            'is_module' => true,
            'module_name' => "Landing Pages",
        ),
        'forms' => array(
            'title' => "Forms",
            'class' => "list-alt",
            'page' => "ib-contact-forms",
            'is_module' => true,
            'module_name' => "Contact Forms",
        ),
        'keywords' => array(
            'page_title' => "Keywords Management",
            'title' => "Keywords",
            'class' => "key",
            'page' => "keyword-admin",
            'is_module' => true,
            'module_name' => "Keywords",
        ),
        'leads' => array(
            'title' => "Leads",
            'class' => "users",
            'page' => "ib-leads-admin",
            'is_module' => true,
            'module_name' => "Lead Management",
        ),
        'email' => array(
            'title' => "Email",
            'class' => "envelope-o",
            'page' => "ib-email-admin",
            'is_module' => true,
            'module_name' => "Email Templates",
        ),
        'redirects' => array(
            'title' => "Redirects",
            'class' => "random",
            'page' => "ib-redirects",
            'is_module' => true,
            'module_name' => "301 Redirects",
            'can_turn_off' => true
        ),
        'sitemap' => array(
            'title' => "Sitmap XML",
            'class' => "sitemap",
            'page' => "ib-admin-settings&section=ib_advance_settings",
            'is_module' => true,
            'module_name' => "Sitemap",
            'can_turn_off' => true
        ),
        'robots' => array(
            'title' => "Robots.txt",
            'class' => "file-text-o",
            'page' => "ib-admin-settings&section=ib_advance_settings",
            'is_module' => true,
            'module_name' => "Robots TXT",
            'can_turn_off' => true
        ),
        'dripcampaign' => array(
            'title' => "Drip Campaign",
            'class' => "paper-plane-o",
            'page' => "ib-drip-campaign",
            'is_module' => true,
            'module_name' => "Drip Campaign",
            'can_turn_off' => true
        ),
        'settings' => array(
            'title' => "Settings",
            'class' => "cog",
            'page' => "ib-admin-settings",
        ),
        'getting_started' => array(
            'title' => "Getting Started",
            'class' => "flag-o",
            'page' => "ib-admin-getting-started",
        ),
    )
);

//we need this even if not active
define('BREW_PLUGIN_IMAGES_URL', plugin_dir_url(__FILE__) . "src/Modules/Core/assets/images/");

    /* --inbound-brew-free-start-- */
    if (is_plugin_active('inbound-brew/inboundbrew.php')) {
        /* --inbound-brew-free-end-- */

        function array_to_object(array $array) {
            $json = json_encode($array);
            return $obj = json_decode($json);
        }

        function get_ip() {
            $ipaddress = '';
            if (@$_SERVER['HTTP_CLIENT_IP'])
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if (@$_SERVER['HTTP_X_FORWARDED_FOR'])
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if (@$_SERVER['HTTP_X_FORWARDED'])
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if (@$_SERVER['HTTP_FORWARDED_FOR'])
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if (@$_SERVER['HTTP_FORWARDED'])
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if (@$_SERVER['REMOTE_ADDR'])
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            else
                $ipaddress = 'UNKNOWN';
            return $ipaddress;
        }

        /* set our constants */
        define('BREW_PLUGIN_FILE', __FILE__);
        define('BREW_PLUGIN_IMAGES_DIR', plugin_dir_path(__FILE__) . "src/Modules/Core/assets/images/");
        define('BREW_PLUGIN_ASSETS_URL', plugin_dir_url(__FILE__) . "src/Modules/Core/assets/");
        define('BREW_PLUGIN_VIEWS_PATH', "Core/views/");
        define('BREW_MODULES_PATH', plugin_dir_path(__FILE__) . "src/Modules/");
        define('BREW_MODULES_URL', plugin_dir_url(__FILE__) . "src/Modules/");
        define('BREW_LANDING_PAGE_PATH', plugin_dir_url(__FILE__) . "src/Modules/Contact/");
        define('BREW_DOWNLOAD_SLUG', 'download-content');
        define('BREW_THANK_YOU_SLUG', 'form-submit-thank-you');
        define('BREW_SEND_USAGE_OPTION', "ib_send_usage");
        define('BREW_AUTO_COLLAPSE_WP_SIDEBAR', "ib_auto_collapse_wp");
        define('BREW_AUTO_COLLAPSE_IB_SIDEBAR', "ib_auto_collapse_ib");
        // lead history types

        define('BREW_LEAD_HISTORY_TYPE_CREATED', 1);
        define('BREW_LEAD_HISTORY_TYPE_FORM_SUBMISSION', 2);
        define('BREW_LEAD_HISTORY_TYPE_NOTE', 3);
        define('BREW_LEAD_HISTORY_TYPE_SHARED', 4);
        define('BREW_LEAD_HISTORY_TYPE_CONTENT_DOWNLOADED', 5);
        define('BREW_LEAD_HISTORY_TYPE_UPDATED', 6);
        define('BREW_LEAD_HISTORY_TYPE_DELETED', 7);
        define('BREW_LEAD_HISTORY_TYPE_RESTORED', 8);
        define('BREW_LEAD_HISTORY_TYPE_PICTURE', 10);
        define('BREW_LEAD_HISTORY_ASSIGNED', 11);
        define('BREW_LEAD_HISTORY_TYPE_PHONE_CALL', 12);
        define('BREW_LEAD_HISTORY_TYPE_EMAIL', 13);
        define('BREW_ACTION_AFTER_FORM_SUBMIT', "inbound_brew_after_form_submit");


        $ib_api_url = 'https://api.inboundbrew.com';
        define('BREW_API_DOMAIN', $ib_api_url);
        
        // social api
        define('BREW_SOCIAL_API_REQUEST_URL', BREW_API_DOMAIN . "/passthrough/handle_request.php"); // where to send validation requests.
        define('BREW_SOCIAL_API_VERIFY_TOKEN_SLUG', "verify-api-token"); // slug to use when verifying api token

        define('BREW_CREATE_SOCIAL_ICON_SLUG', 'ib-social-icon');
        define('TEMPLATE_PREVIEW_SLUG', 'ib-email-template-preview');

        // external links
        define('BREW_BLOG_URL', "https://www.inboundbrew.com/blog");
        define('BREW_PLUGIN_BLOG_URL', "https://www.inboundbrew.com/plugin/");

        define('BREW_WP_DATE_FORMAT', get_option('date_format'));
        define('BREW_WP_TIME_FORMAT', get_option('time_format'));



        require __DIR__ . '/vendor/autoload.php';

        new InboundBrew\Modules\Core\Controllers\Admin();
        new InboundBrew\Modules\Core\Controllers\PublicUI();

        $capsule = new Illuminate\Database\Capsule\Manager;



        // get collation and charset
        $schema = $wpdb->get_results("SELECT @@character_set_database AS DEFAULT_CHARACTER_SET_NAME, @@collation_database AS DEFAULT_COLLATION_NAME", "ARRAY_A");
        $charset = $schema[0]['DEFAULT_CHARACTER_SET_NAME'];
        $collate = $schema[0]['DEFAULT_COLLATION_NAME'];

        $capsule->addConnection(array(
            'driver' => 'mysql',
            'host' => IB_DB_HOST,
            'port' => DB_PORT,
            'database' => DB_NAME,
            'username' => DB_USER,
            'password' => DB_PASSWORD,
            'charset' => $charset,
            'collation' => $collate,
            'prefix' => $wpdb->prefix,
            'strict' => false
        ));

        $capsule->bootEloquent();

        function inboundbrew_getCurrentURL() {
            $currentURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
            $currentURL .= $_SERVER["SERVER_NAME"];

            if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443") {
                $currentURL .= ":" . $_SERVER["SERVER_PORT"];
            }

            $currentURL .= $_SERVER["REQUEST_URI"];
            return $currentURL;
        }

        function loadVirtualPage() {
            $full_url = inboundbrew_getCurrentURL();
            $home_url = get_home_url();
            $url = str_replace($home_url, "", $full_url);
            $url = trim(parse_url($url, PHP_URL_PATH), '/');
            $parts = explode('/', $url);
// download content
            if (count($parts) == 2 && @$parts[0] == BREW_DOWNLOAD_SLUG && !preg_match('/[^a-z0-9]/i', $parts[1])) {
                $args = array(
                    'slug' => 'download-content',
                    'title' => 'Download',
                    'alias' => $parts[1]
                );
                $dl = new InboundBrew\Modules\Content\Controllers\Download();
                $dl->loadVirtual($args);
// token for social api is being verified.
            } elseif (@$parts[0] == BREW_SOCIAL_API_VERIFY_TOKEN_SLUG) {
                $Settings = new InboundBrew\Modules\Settings\Models\SettingsModel();
                $settings = $Settings->loadSettings();
                echo $settings->api_token;
                exit();
            } elseif (isset($parts[0]) && $parts[0] == BREW_THANK_YOU_SLUG) {
                $ty = new InboundBrew\Modules\Content\Controllers\ThankYou();
                $config = json_decode(get_option('ib_form_submit'));
                $ty->LoadVirtual($config);
            } elseif (isset($parts[0]) && $parts[0] == BREW_CREATE_SOCIAL_ICON_SLUG) {
                $FontAwesome = new InboundBrew\Libraries\FontAwesomeHelper();
                $img = $FontAwesome->returnIcon($parts[1], $parts[2], $parts[3]);
                ob_start();
                imagepng($img);
                $size = ob_get_length();
                ob_end_clean(); // delete buffer
// headers
                header("Content-Type: image/png");
                header("Content-Length: " . $size);
// dump the picture and stop the script
                imagepng($img);
                exit;
// display template preview
            } elseif (isset($parts[0]) && $parts[0] == TEMPLATE_PREVIEW_SLUG) {
                $EmailController = new InboundBrew\Modules\Contact\Controllers\Email();
                $EmailController->getEmailTemplatePreview();
            }
        }

        add_action('init', 'loadVirtualPage');
        add_action('ib_check_for_social_postings', 'check_for_social_postings');
        add_action('plugins_loaded', 'inboundbrew_update_db_check');
        hookOntoRewriteRules();
    }

// plugin update method
    function inboundbrew_update_db_check() {
        add_action('admin_init', "checkPluginStatus", 1);
    }

    function checkPluginStatus() {
        require_once(dirname(__FILE__) . '/src/Modules/Core/Brew.php');
        $brew = new Brew();
        $brew->init();
    }

// activation hook to look for items that need to be posted.
    register_activation_hook(__FILE__, 'inboundbrew_activation');

    function inboundbrew_check_requirements() {
        global $wpdb;

        $failed_reqs = array();
        if (version_compare(PHP_VERSION, '5.3.0') < 0) {
            $failed_reqs[] = "<b>PHP Version:</b> at least PHP 5.3.0. You run " . PHP_VERSION . ". We highly recommend you upgrade to PHP 7. WordPress recommends this version anyway, and your version will soon be unsupported by WordPress core if it isn't already. For more info, <a href='https://wordpress.org/about/requirements/' target='_blank'>read this.</a>";
        }

        if (!defined('PDO::ATTR_DRIVER_NAME')) {
            $failed_reqs[] = "<b>PDO Extension:</b>Your host doesn't appear to have this enabled <i>(even though it is the \"gold standard\" for database connections for all PHP applications)</i>. For more info, <a href='https://inboundbrew.com/inboundmarketingblog/hosts-cutting-costs-and-how-it-affects-our-plugin/' alt='Inbound Brew and PDO' target='_blank'>read this article we've written about PDO.</a>";
        }

        /*if (!function_exists('mcrypt_encrypt')) {
            $failed_reqs[] = "<b>Mcrypt Extension:</b> Your host doesn't appear to have this enabled <i>(even though it is the most common library for cryptography for all PHP applications)</i>.";
        }*/


        /*
          //what is the minimum version of mysql we need to run? verify and test here on activation
          $mysqli = mysqli_init();
          if (!$mysqli) {
          $failed_reqs[] = "<b>MySQLi Extension:</b> Your host doesn't appear to have this enabled <i>(even though it is the most common library for mysql connections for all PHP applications)</i>.";
          }
          if ($mysqli && !$mysqli->real_connect(IB_DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT)) {
          $failed_reqs[] = "<b>MySQLi Connection Failure:</b> There seems to be some sort of DB connection error. Is the rest of your site working? If so, contact support@inboundbrew.com right away and we'll help you troubleshoot.";
          }
          else if ($mysqli){
          //there is a connection - check the version
          echo $mysqli->server_info;
          } */

        if (count($failed_reqs)) {
            $error_msg = "<div style='text-align:center;'>
                    <img src='" . BREW_PLUGIN_IMAGES_URL . "InboundBrewTitle.png'>
                </div>
                <div>Oops! The Inbound Brew Plugin has a few requirements that we can't seem to find on your server:<br/><ul>";
            foreach ($failed_reqs as $failure) {
                $error_msg .= "<li>" . $failure . "</li>";
            }

            $error_msg .= "</ul>Email your host and ask them about these items. If they don't cooperate, we recommend finding a better host (if you want a fully managed, hands-off, white-glove solution, <a href='mailto:info@inboundbrew.com?subject=Hosting%20Inquiry'>email us</a>)</div><br/><div>For any other plugin-related questions, feel free to <a href='mailto:support@inboundbrew.com'>email us</a>, or visit <a href='https://inboundbrew.com' target='_blank'>https://inboundbrew.com</a>.</div><br/><br/><a href='javascript:history.back()'><- take me back</a>";


            wp_die($error_msg);
        }
    }

// activation method
    function inboundbrew_activation() {
        global $wpdb;

        inboundbrew_check_requirements();


        require __DIR__ . '/vendor/autoload.php';

        $capsule = new Illuminate\Database\Capsule\Manager;

// get collation and charset
//$schema = $wpdb->get_results("SELECT * FROM information_schema.SCHEMATA WHERE schema_name = '".DB_NAME."'","ARRAY_A");
        $schema = $wpdb->get_results("SELECT @@character_set_database AS DEFAULT_CHARACTER_SET_NAME, @@collation_database AS DEFAULT_COLLATION_NAME", "ARRAY_A");
        $collate = $schema[0]['DEFAULT_COLLATION_NAME'];
        $charset = $schema[0]['DEFAULT_CHARACTER_SET_NAME'];
        $wpdb->charset = $charset;
        $wpdb->collate = $collate;
        $capsule->addConnection(array(
            'driver' => 'mysql',
            'host' => IB_DB_HOST,
            'port' => DB_PORT,
            'database' => DB_NAME,
            'username' => DB_USER,
            'password' => DB_PASSWORD,
            'charset' => $charset,
            'collation' => $collate,
            'prefix' => $wpdb->prefix,
        ));

        $capsule->bootEloquent();

        require_once(dirname(__FILE__) . '/src/Modules/Core/Brew.php');
        $brew = new Brew();
        $brew->init();

        if (!wp_next_scheduled('ib_check_for_social_postings')){
            wp_schedule_event(time(), 'hourly', 'ib_check_for_social_postings');
        }
        
        if (!get_option('ib_sitemap_default_settings', false)){
            add_option('ib_sitemap_default_settings', '{"service":{"google":"on","bing":"on"},"standard":{"home":"on","post":"on","page":"on"},"custom_post_types":{"ib-landing-page":"on","page":"on","post":"on"}}');
        }
        if (!get_option('ib_smtp_options', false)){
            $smtp_options = array(
                'mail_from' => '',
                'mail_from_name' => '',
                'mailer' => 'mail',
                'mail_set_return_path' => '',
                'smtp_host' => 'localhost',
                'smtp_port' => '25',
                'smtp_ssl' => 'none',
                'smtp_auth' => false,
                'smtp_user' => '',
                'smtp_pass' => ''
            );    
            add_option("ib_smtp_options", json_encode($smtp_options));
        }
        $ib_first_installed = get_option('ib_first_installed', false);
        if (!$ib_first_installed) {
            add_option("ib_first_installed", date('Y-m-d H:i:s'));
        }

// hook onto rewrite rules changes
        hookOntoRewriteRules();
        flush_rewrite_rules(false);
    }

// check for social postings (every hour)
    function check_for_social_postings() {
        $Settings = new InboundBrew\Modules\Settings\Controllers\Settings;
        $Settings->checkForSocialPostings(); // check for items that have been scheduled to be posted at a specific time.
    }

    /**
     * When re-write rules are changed add InboundBrew sitemap rewrite rules.
     *
     * @author Rico Celis
     * @access public
     */
    function hookOntoRewriteRules() {
        $modules = get_option(BREW_ACTIVE_MODULES_OPTION);
        if (@$modules['sitemap'])
            add_filter('rewrite_rules_array', "addInboundBrewRewriteRules", 1, 1);
    }

    /**
     * When re-write rules are changed add InboundBrew sitemap rewrite rules.
     *
     * @author Rico Celis
     * @access public
     */
    function addInboundBrewRewriteRules($wp_rules) {
        $ib_rules = array(
            'sitemap(-+([a-zA-Z0-9_-]+))?\.xml$' => 'index.php?xml_sitemap=1',
        );
        return array_merge($ib_rules, $wp_rules);
    }

// For 4.3.0 <= PHP <= 5.4.0
    if (!function_exists('http_response_code')) {

        function http_response_code($newcode = NULL) {
            static $code = 200;
            if ($newcode !== NULL) {
                header('X-PHP-Response-Code: ' . $newcode, true, $newcode);
                if (!headers_sent())
                    $code = $newcode;
            }
            return $code;
        }

    }


    /* --inbound-brew-free-start-- */
    //if the pro versiop is here - don't delete anything
    if (!is_dir(dirname(__FILE__) . "/../inbound-brew-pro")) {
        ib_fs()->add_action('after_uninstall', 'ib_fs_uninstall_cleanup');
    }
    /* --inbound-brew-free-end-- */


    function ib_fs_uninstall_cleanup() {
        // delete Inbound Brew Options
        $ib_options = array("ib_email_settings", "ib_smtp_options", "ib_form_submit", "ib_lead_fields",
            "ib_db_version", "ib_data_version", "ib_settings_version", "ib_country_import", "ib_state_import", "ib_field_import",
            "ib_social_share_widget_settings", "ib_social_share_widget_options", "ib_cta_defaults", "ib_sitemap_default_settings",
            "ib_ping_data", "ib_default_layout", "ib_dynamic_navigation", "ib_active_modules", "ib_redirect_settings",
            "ib_default_lead_view_settings", "ib_user_default_lead_view", "ib_first_installed", "_transient_ib_country_import",
            "_transient_ib_state_import", "ib_send_usage", "ib_auto_collapse_ib", "ib_auto_collapse_wp",
            "ib_google_client_view_id", "ib_google_developer_mode", "ib_google_client_secret", "ib_google_client_id", "fs_accounts", "fs_active_plugins", "ib_show_getting_started_menu",
            "BREW_AUTO_COLLAPSE_IB_SIDEBAR"
            );

        foreach ($ib_options as $option) {
            delete_option($option);
        }

        // delete Inbound Brew Database Tables
        $ib_tables = array("ib_contact_field", "ib_countries", "ib_ctas", "ib_cta_post_linkages", "ib_downloads", "ib_email_field",
            "ib_email_templates", "ib_keywords", "ib_leads", "ib_lead_data", "ib_lead_fields", "ib_lead_history", "ib_post_keyword",
            "ib_redirects", "ib_settings", "ib_social_network_post_records", "ib_social_network_post_settings",
            "ib_social_network_post_setting_accounts", "ib_social_network_accounts", "ib_states", "ib_ctas", "ib_cta_post_linkages",
            "ib_cta_templates", "ib_lead_views", "ib_emails", "ib_analytic_reports", "ib_campaign", "ib_campaign_master",
            "ib_campaign_step", "ib_email_track", "ib_facebook_reports", "ib_lead_campaign_events_log", "ib_linkedin_reports",
            "ib_reports", "ib_tracking_events", "ib_twitter_reports");

        global $wpdb;
        $wpdb->query("SET FOREIGN_KEY_CHECKS = 0");
        foreach ($ib_tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}{$table}`");
        }
        $wpdb->query("SET FOREIGN_KEY_CHECKS = 1;");

        // delete custom post items
        $ib_post_types = array("ib-call-to-action", "ib-contact-form", "ib-landing-page");
        foreach ($ib_post_types as $post_type) {
            $args = array(
                'hierarchical' => 1,
                'exclude' => '',
                'include' => '',
                'meta_key' => '',
                'meta_value' => '',
                'authors' => '',
                'child_of' => 0,
                'parent' => -1,
                'exclude_tree' => '',
                'number' => '',
                'offset' => 0,
                'post_type' => $post_type,
            );
            $posts = get_posts($args);
            if (!empty($posts)) {
                foreach ($posts as $post) {
                    wp_delete_post($post->ID);
                }
            }
        }

        //remove crons
        wp_clear_scheduled_hook('ib_check_for_social_postings');
        wp_clear_scheduled_hook('drip_campaign_hook');
        wp_clear_scheduled_hook('license_key_check_hook');
        wp_clear_scheduled_hook('socail_datapoint_hook');
    }

    function inboundbrew_languages() {
        load_plugin_textdomain('inbound-brew', false, basename(dirname(__FILE__)) . '/languages');
    }

    /* load the languages file */
    add_action('plugins_loaded', 'inboundbrew_languages');

    /*  Add action to load IB menu in landing pages.  */
    add_action('admin_footer', 'enableLadingpageClass');

    /**
     * enableLadingpageClass()
     * To fetch Menu in landing page.
     * Author : Chirag.
     * Date : 19-06-2017
     * @global array $ib_dynamic_navigation
     */
    function enableLadingpageClass() {

        $post_id = "";
        $type = "";
        if (@$_GET['post']) {
            $post_id = @$_GET['post'];
        }

        if (!empty($post_id)) {
            $type = get_post_type($post_id);
        }

        $post_type = "";
        if (@$_GET['post_type']) {
            $post_type = @$_GET['post_type'];
        }
        if ($post_type == "ib-landing-page" || $type == "ib-landing-page") {
            $view_layout = "";
            $view_layout = get_option(BREW_DEFAULT_LAYOUT_OPTION); // Get existing view layout

            /*  Get globle navigator menu options */
            global $ib_dynamic_navigation;
            $modules = $ib_dynamic_navigation['navigation'];
            $navigation = get_option(IB_TOP_NAV_VALUES);
            $active_modules = get_option(BREW_ACTIVE_MODULES_OPTION);

            /* Check the menu view type and call menu file according to that. */
            if ($view_layout == "top_nav") {
                /* --inbound-brew-free-start-- */
                require_once ABSPATH . '/wp-content/plugins/inbound-brew/src/Modules/Core/assets/layouts/top_nav.php';
                /* --inbound-brew-free-end-- */
            } else if ($view_layout == "wp_nav") {
                /* --inbound-brew-free-start-- */
                require_once ABSPATH . '/wp-content/plugins/inbound-brew/src/Modules/Core/assets/layouts/wp_nav.php';
                /* --inbound-brew-free-end-- */
            } else {
                /* --inbound-brew-free-start-- */
                require_once ABSPATH . '/wp-content/plugins/inbound-brew/src/Modules/Core/assets/layouts/side_nav.php';
                /* --inbound-brew-free-end-- */
                echo "<script type='text/javascript'>
             jQuery(document).ready(function ($) {
                $('.wrap').addClass('ib_wrap');
             });
         </script>";
            }
            // Add remove classes to make menu active.
            echo "<script type='text/javascript'>
        jQuery(document).ready(function ($) {
            $('#toplevel_page_inboundbrew').addClass('wp-has-current-submenu wp-menu-open opensub').removeClass('wp-not-current-submenu');
            $('#toplevel_page_inboundbrew').children('a').first().addClass('wp-has-current-submenu wp-menu-open').removeClass('wp-not-current-submenu');

         });
     </script>";
        }
    }

