<?php

/**
 * Created by sean.carrico.
 * User: sean
 * Date: 3/25/15
 * Time: 11:18 AM
 */

namespace InboundBrew\Modules\Core\Controllers;

use InboundBrew\Modules\Contact\Controllers\Email;
use InboundBrew\Modules\Core\AppController;
use InboundBrew\Modules\CTA\Controllers\CallToAction;
use InboundBrew\Modules\SEO\Controllers\Keyword;
use InboundBrew\Modules\SEO\Controllers\Meta;
use InboundBrew\Modules\Contact\Controllers\LandingPage;
use InboundBrew\Modules\Contact\Controllers\Form;
use InboundBrew\Modules\Core\Models\FormField;
use InboundBrew\Modules\Leads\Controllers\Lead;
use InboundBrew\Modules\Redirects\Controllers\Redirect;
use InboundBrew\Modules\Sitemap\Controllers\Robot;
use InboundBrew\Modules\Sitemap\Controllers\Sitemap;
use InboundBrew\Modules\Settings\Controllers\Settings;

// models
use InboundBrew\Modules\Settings\Models\SettingsModel;
use InboundBrew\Modules\Settings\Models\SocialNetworkPostSetting;
// libraries
use InboundBrew\Libraries\DateHelper;
use InboundBrew\Libraries\FormHelper;
use Carbon\Carbon;

class Admin extends AppController {

    const VIEW_PATH = 'Core/views/';

    public function __construct() {
        parent::init();
        add_filter('admin_head', array($this, 'addAdminStyles'));
        add_action('admin_head', array($this, 'addShortCodeButtons'));
        add_action('admin_enqueue_scripts', array($this, 'addAdminScripts'));
        add_action('admin_menu', array($this, 'registerMenu'));
        add_action('wp_ajax_ib_hide-wizzard', array($this, 'hideWizzard'));

        /* --inbound-brew-free-start-- */
        add_filter('plugin_action_links_inbound-brew/inboundbrew.php', array($this, 'addActionLinks'));
        add_action('wp_ajax_ib_reports_filter', array($this, 'filterDashboardReports'));
        /* --inbound-brew-free-end-- */


        // add filter to modify collaped navigation
        $ib_slugs = array("inboundbrew", "ib-email-admin", "landing-page-admin", "ib-contact-forms", "ib-call-to-action", "ib-leads-admin", "ib-drip-campaign", "ib-redirects", "keyword-admin", "ib-admin-settings", "ib-robots-txt-admin", "sitemap-xml-admin");

        if (get_option('ib_show_getting_started_menu')){
            $ib_slugs[] = "ib-getting-started";
        }

        if (in_array(@$_GET['page'], $ib_slugs)) {
            add_filter('admin_body_class', array($this, 'handleBodyClasses'));
            add_filter('admin_footer_text', array($this, 'changeFooter'));
        }
        if (is_admin()) {
            // add metabox to save old post
            add_action('add_meta_boxes', array($this, 'addMetaBoxes'));
        }
        $this->initializeModules();

        if (!defined('WP_CRON_LOCK_TIMEOUT')){
            define('WP_CRON_LOCK_TIMEOUT', 0);
        }
    }

    /* --inbound-brew-free-start-- */

    public function addActionLinks($links) {
        if (isset($links['opt-in-or-opt-out inbound-brew']) && stripos($links['opt-in-or-opt-out inbound-brew'], "Opt Out") !== false) {
            //remove Freemius Opt-out - they can opt out from our settings page
            unset($links['opt-in-or-opt-out inbound-brew']);
        }
        $iblinks = array("<a href='" . get_admin_url() . "admin.php?page=ib-admin-settings'>Settings</a>");
        $date_installed = FormField::where('field_name', 'Email')->first()->created_at;
        if ($date_installed->diffInDays(Carbon::now()) <= 7) {
            $iblinks[] = "<a href='" . get_admin_url() . "admin.php?page=ib-admin-getting-started'>Getting Started</a>";
        }
        $iblinks[] = "<a href='mailto:support@inboundbrew.com?subject=Plugin%20Screen%20Support'>Support</a>";
        $iblinks[] = "<a href='https://inboundbrew.com/plugin/'>Get Pro</a>";

        return array_merge($links, $iblinks);
    }

    /* --inbound-brew-free-end-- */


    /**
     * Modify the footer text inside of the WordPress admin area.
     *
     * @since 1.6.2
     *
     * @param string $text  The default footer text.
     * @return string $text Amended footer text.
     */
    public function changeFooter($text) {
        return "<i class='fa fa-ambulance' aria-hidden='true'></i>&nbsp;&nbsp;&nbsp;&nbsp;<a href='" . get_admin_url() . "admin.php?page=ib-admin-getting-started'>Getting Started</a> | <a href='https://inboundbrew.com/pluginresources/' target='_blank'>Resource Center</a> | <a href='https://inboundbrew.com/inboundmarketingblog/free-download-the-inbound-brew-user-guide/' target='_blank'>User Guide</a> | <a href='" . get_admin_url() . "admin.php?page=ib-admin-settings'>Settings</a> | <a href='mailto:support@inboundbrew.com?subject=Plugin%20Footer%20Support%20Request'>support@inboundbrew.com</a> | <span class='ib_footer_version'>Inbound Brew Version " . BREW_PLUGIN_VERSION . "</span>";
    }

    public function addAdminStyles() {
        wp_enqueue_style('ib-icons', BREW_MODULES_URL . 'Core/assets/css/icomoon.css', array(), BREW_ASSET_VERSION);
        wp_enqueue_style('ib-tipsy', BREW_MODULES_URL . 'Core/assets/css/tipsy.css', array(), BREW_ASSET_VERSION);
        wp_enqueue_style('ib-core', BREW_MODULES_URL . 'Core/assets/css/core.css', array(), BREW_ASSET_VERSION);
        wp_enqueue_style('ib-admin', BREW_MODULES_URL . 'Core/assets/css/admin.css', array(), BREW_ASSET_VERSION);
        wp_enqueue_style('ib-jquery-ui', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css', array(), BREW_ASSET_VERSION);
        wp_enqueue_style('data-tables', BREW_MODULES_URL . 'Core/assets/third-party/DataTables-1.10.12/media/css/jquery.dataTables.min.css', array(), BREW_ASSET_VERSION);
        wp_enqueue_style('data-tables-row', BREW_MODULES_URL . 'Core/assets/third-party/DataTables-1.10.12/media/css/rowReorder.dataTables.min.css', array(), BREW_ASSET_VERSION);
        wp_enqueue_style('ib-col-resize', BREW_MODULES_URL . 'Core/assets/third-party/jquery-colresize/css/main.css', array(), BREW_ASSET_VERSION);
        wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), BREW_ASSET_VERSION);
        wp_enqueue_style('ib-jquery-confirm-css', BREW_MODULES_URL . 'Core/assets/css/jquery.confirm.css', array(), BREW_ASSET_VERSION);
        wp_enqueue_style('ib-shepherd-arrows-css', BREW_MODULES_URL . 'Core/assets/css/shepherd-theme-arrows.css', array(), BREW_ASSET_VERSION);
        //wp_enqueue_style('ib-bootstrap-css', "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css", array(), BREW_ASSET_VERSION);
    }

    public function addAdminScripts() {
        // jquery ui
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_script('jquery-ui-autocomplete', array('jquery'));
        wp_enqueue_script('jquery-ui-accordion', array('jquery'));
        wp_enqueue_script('jquery-ui-tabs', array('jquery'));
        wp_enqueue_script('jquery-ui-datepicker', array('jquery'));
        wp_enqueue_script('jquery-ui-droppable', array('jquery'));
        wp_enqueue_script('jquery-ui-progressbar', array('jquery'));
        wp_enqueue_script('jquery-validate-js', BREW_MODULES_URL . 'Core/assets/js/jquery.validate.min.js', array('jquery'), BREW_ASSET_VERSION);
        wp_enqueue_script('jquery-cookie-js', BREW_MODULES_URL . 'Core/assets/third-party/jquery-cookie/jquery.cookie.js', array('jquery'), BREW_ASSET_VERSION);
        wp_enqueue_script('jquery-validate-additional-methods-js', BREW_MODULES_URL . 'Core/assets/js/additional-methods.min.js', array('jquery', 'jquery-validate-js'), BREW_ASSET_VERSION);
        wp_enqueue_script('ib-jquery-serialize-object', BREW_MODULES_URL . 'Core/assets/third-party/jquery-serialize-object/jquery.serialize-object.js', array('jquery'), BREW_ASSET_VERSION);
        wp_enqueue_script('ib-core-js', BREW_MODULES_URL . 'Core/assets/js/core.js', array('jquery'), BREW_ASSET_VERSION);
        wp_enqueue_script('ib-tabs-jquery-js', BREW_MODULES_URL . 'Core/assets/js/ib-tabs.jquery.js', array('jquery'), BREW_ASSET_VERSION);
        wp_enqueue_script('ib-form-validator', BREW_MODULES_URL . 'Core/assets/js/ib-form-validation.js', array('jquery'), BREW_ASSET_VERSION, true);
        wp_enqueue_script('ib-ajax-handler-js', BREW_MODULES_URL . 'Core/assets/js/ib-ajax-handler.js', array('jquery', 'ib-form-validator'), BREW_ASSET_VERSION, true);
        wp_enqueue_script('ib-confirm-js', BREW_MODULES_URL . 'Core/assets/js/jquery.confirm.js', array('jquery'), BREW_ASSET_VERSION);
        wp_enqueue_script('clipboard-min-js', BREW_MODULES_URL . 'Core/assets/js/clipboard.min.js', array('jquery'), BREW_ASSET_VERSION);
        wp_enqueue_script('jquery-tipsy-js', BREW_MODULES_URL . 'Core/assets/js/jquery.tipsy.js', array('jquery'), BREW_ASSET_VERSION);
        // data tables
        wp_enqueue_script('data-tables-js', BREW_MODULES_URL . 'Core/assets/third-party/DataTables-1.10.12/jquery.dataTables.js', array('jquery'), BREW_ASSET_VERSION);
        wp_enqueue_script('ib-datatables-col-reorder', BREW_MODULES_URL . 'Core/assets/third-party/DataTables-1.10.12/dataTables.colReorder.js', array('data-tables-js'), BREW_ASSET_VERSION);
        wp_enqueue_script('ib-datatables-row-reorder', BREW_MODULES_URL . 'Core/assets/third-party/DataTables-1.10.12/dataTables.rowReorder.js', array('data-tables-js'), BREW_ASSET_VERSION);
        // resize
        wp_enqueue_script('ib-col-resize', BREW_MODULES_URL . 'Core/assets/third-party/jquery-colresize/colResizable-1.6.js', array('jquery'), BREW_ASSET_VERSION);
        wp_localize_script(
                'ib-ajax-handler-js', 'ibAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
                )
        );

        wp_localize_script('ib-core-js', 'ibConstants', array('adminUrl' => get_admin_url() . "admin.php?", 'getVars' => $_GET));

        /* Shepherd Common */
        wp_enqueue_script('hubspot-tether', BREW_MODULES_URL . 'Core/assets/third-party/hubspot/tether.js', array('jquery'), BREW_ASSET_VERSION);
        wp_enqueue_script('hubspot-shepherd', BREW_MODULES_URL . 'Core/assets/third-party/hubspot/shepherd.min.js', array('hubspot-tether'), BREW_ASSET_VERSION);
        wp_enqueue_script('ib-shepherd-common', BREW_MODULES_URL . 'Core/assets/js/ib-shepherd-common.js', array('jquery', 'jquery-cookie-js', 'hubspot-tether', 'hubspot-shepherd'), BREW_ASSET_VERSION);

        if (@$_GET['page'] == "inboundbrew") {
            wp_enqueue_script('ib-wizzard-js', BREW_MODULES_URL . 'Core/assets/js/ib-wizzard.js', array('jquery'), BREW_ASSET_VERSION, true);
            wp_enqueue_script('ib-dashboard-shepherd', BREW_MODULES_URL . 'Core/assets/js/ib-dashboard-shepherd.js', array('jquery'), BREW_ASSET_VERSION);
            wp_enqueue_script('chart-js', BREW_MODULES_URL . 'Core/assets/third-party/chartjs/Chart.min.js', array('jquery'), BREW_ASSET_VERSION);
            wp_enqueue_script('chart-util-js', BREW_MODULES_URL . 'AnalyticsReports/assets/js/utils.js', array('jquery'), BREW_ASSET_VERSION);
        }
    }

    /**
     *
     */
    public function registerMenu() {
        /* Main Menu Item */
        add_menu_page(
                'Inbound Brew', //page title
                'Inbound Brew', //menu title
                'manage_options', // capabilities
                'inboundbrew', //menu slug
                array($this, 'loadAdminPage'), //function
                "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDE5LjIuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCAxNTMuNiAxNTMuNiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMTUzLjYgMTUzLjY7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4KCS5zdDB7ZmlsbDojNTg1OTVCO30KPC9zdHlsZT4KPGc+Cgk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMzAuNSw0Mi40Yy0xLjEtMC4xLTIuMi0wLjItMy4zLTAuMmMtMTQuNywwLjMtMjYuMiwxNi0yNS44LDM1YzAuNCwxOS4xLDEyLjIsMzMuOCwyNi45LDMzLjgKCQljMC4yLDAsMC4zLDAsMC41LDBjMC42LDAsMS4xLTAuMSwxLjctMC4xdjMxLjdoMzEuOVYyOC42SDMwLjVWNDIuNHogTTI4LjYsOTkuMWMtMC4xLDAtMC4xLDAtMC4yLDBjLTcuOSwwLTE0LjgtMTAuMS0xNS0yMi4yCgkJQzEzLjEsNjQuNywxOS42LDU0LjIsMjcuNiw1NGMwLjEsMCwwLjEsMCwwLjIsMGMwLjksMCwxLjksMC4yLDIuOCwwLjR2NDQuNEMyOS45LDk4LjksMjkuMyw5OSwyOC42LDk5LjF6Ii8+Cgk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMTQ4LjEsOTMuN2MtMy44LTQuNy05LjEtOC4xLTE1LjktMTAuMmM2LjEtMi4yLDEwLjctNS42LDE0LTEwLjJjMy4yLTQuNiw0LjgtMTAsNC43LTE2LjQKCQljMC00LjUtMC45LTguNi0yLjYtMTIuNGMtMS44LTMuOC00LjMtNy03LjctOS42Yy0xLjctMS4zLTMuNS0yLjMtNS4zLTMuMmMtMS45LTAuOC0zLjktMS41LTYuMi0yYy0yLjMtMC41LTQuOS0wLjgtNy45LTEKCQljLTMtMC4yLTYuNC0wLjItMTAuNC0wLjJsLTQwLjcsMC4ydjExMy44bDM3LjMtMC4zYzQuNSwwLDguMy0wLjEsMTEuNS0wLjNjMy4yLTAuMiw2LTAuNiw4LjUtMS4xYzIuNS0wLjUsNC44LTEuMiw2LjgtMi4yCgkJYzItMC45LDQuMS0yLDYuMi0zLjRjNC4xLTIuOCw3LjQtNi4zLDkuNy0xMC41YzIuMy00LjIsMy41LTguOSwzLjUtMTMuOUMxNTMuNywxMDQuMSwxNTEuOSw5OC40LDE0OC4xLDkzLjd6IE0xMDIsNDQuMWgyLjQKCQljNS4zLDAsOSwxLjIsMTEuMiwzLjVjMi4xLDIuMywzLjIsNi41LDMuMiwxMi40YzAsNi4xLTEuMSwxMC4zLTMuNCwxMi44Yy0yLjMsMi40LTYuMiwzLjctMTEuOSwzLjdIMTAyVjQ0LjF6IE0xMTYuNCwxMjIuNQoJCWMtMi4zLDIuNi02LjMsMy45LTEyLDMuOUgxMDJWOTIuM2gyLjdjNS4zLDAsOS4yLDEuMywxMS42LDMuOWMyLjQsMi42LDMuNyw2LjgsMy43LDEyLjdDMTE5LjksMTE1LjMsMTE4LjcsMTE5LjksMTE2LjQsMTIyLjV6Ii8+CjwvZz4KPC9zdmc+Cg==" // icon
                // not using positiion
        );
        add_submenu_page('inboundbrew', '', 'Dashboard', 'manage_options', 'inboundbrew', ''); //hack - get rid of duplicate menu item
        // register submenus menu
        $this->registerLeadsMenu();
        $this->registerEmailMenu();
        $this->registerFormMenu();
        $this->registerLandingPagesMenu();
        $this->registerCtaMenu();
        $this->registerKeywordsMenu();
        $this->registerSettingsMenu();
        $this->registerRedirectsMenu();
        $this->registerGettingStartedPage();
    }

    // add submenu for Contact Forms
    private function registerFormMenu() {
        add_submenu_page(
                'inboundbrew', 'Inbound Brew: Contact Forms', 'Contact Forms', 'manage_options', 'ib-contact-forms', array($this->Form, 'loadAdminPage')
        );
    }

    // add submenu for Email Templates Menu
    public function registerEmailMenu() {
        add_submenu_page(
                'inboundbrew', 'Inbound Brew: Email', 'Email', 'manage_options', 'ib-email-admin', array($this->Email, 'loadAdmin')
        );
    }

    // add submenu for Landing Pages Menu
    public function registerLandingPagesMenu() {
        add_submenu_page(
                'inboundbrew', 'Inbound Brew: Landing Pages', 'Landing Pages', 'manage_options', 'landing-page-admin', array($this->LandingPage, 'loadAdminPage')
        );
    }

    // add submenu for CTA Menu
    public function registerCtaMenu() {
        /* Content */
        add_submenu_page(
                'inboundbrew', 'Inbound Brew: CTAs', 'CTAs', 'manage_options', 'ib-call-to-action', array($this->CallToAction, 'handleCTASections')
        );
    }

    // add submenu for Leads Menu
    public function registerLeadsMenu() {
        add_submenu_page(
                'inboundbrew', 'Inbound Brew: Leads', 'Lead Management', 'manage_options', 'ib-leads-admin', array($this->Lead, 'loadAdmin')
        );
    }

    // add submenu for Redirects Menu
    public function registerRedirectsMenu() {
        add_submenu_page(
                'inboundbrew', 'Inbound Brew: Redirects', 'Redirects', 'manage_options', 'ib-redirects', array($this->Redirect, 'redirectsAdmin')
        );
    }

    // add submenu for Keywords Menu
    public function registerKeywordsMenu() {
        add_submenu_page(
                'inboundbrew', 'Inbound Brew: Keywords', 'Keywords', 'manage_options', 'keyword-admin', array($this->Keyword, 'loadAdmin')
        );
    }

    // add submenu for Settings Menu
    public function registerSettingsMenu() {
        add_submenu_page(
                'inboundbrew', 'Inbound Brew: Settings', 'Settings', 'manage_options', 'ib-admin-settings', array($this->Settings, 'settingsAdmin')
        );
    }

    // register getting started page
    public function registerGettingStartedPage() {
        $gettingStartedTitle = '';
        if (get_option('ib_show_getting_started_menu')){
            $gettingStartedTitle = 'Getting Started';
        }

        add_submenu_page(
                'inboundbrew', 'Inbound Brew: Getting Started', $gettingStartedTitle, 'manage_options', 'ib-admin-getting-started', array($this, 'loadGettingStartedPage')
        );
        
    }


    private function initializeModules() {
        $this->CallToAction = new CallToAction();
        $this->LandingPage = new LandingPage();
        $this->Form = new Form();
        $this->Keyword = new Keyword();
        $this->Meta = new Meta();
        $this->Lead = new Lead();
        $this->Email = new Email();
        $this->Redirect = new Redirect();
        $this->Robot = new Robot();
        $this->Sitemap = new Sitemap();
        $this->Settings = new Settings();
    }

    /**
     *
     */
    public function loadAdminPage() {
        // load settings.
        $Settings = new SettingsModel();
        $data['settings'] = $Settings->loadSettings();
        $data['Date'] = new DateHelper;
        // login urls for social networks
        $data['facebook_login_url'] = $Settings->getSocialNetworkLoginUrl("facebook");
        $data['twitter_login_url'] = $Settings->getSocialNetworkLoginUrl("twitter");
        $data['linked_in_login_url'] = $Settings->getSocialNetworkLoginUrl("linked_in");
        $data['google_in_login_url'] = $Settings->getSocialNetworkLoginUrl("google");
        // social network history
        $SocialNetworkPostSetting = new SocialNetworkPostSetting();
        $data['social_posted_recently'] = $SocialNetworkPostSetting->postedRecently(10, array('as_array' => true, 'link_to_post' => true));
        $data['social_posting_soon'] = $SocialNetworkPostSetting->postingSoon(10, array('as_array' => true, 'link_to_post' => true));
        // wizzard
        $data['wizzard_steps'] = SettingsModel::$wizzard_steps;
        // breadcrumbs
        $this->Breadcrumb->add("Inbound Brew");
        $data['Breadcrumb'] = $this->Breadcrumb;
        /* --inbound-brew-free-start-- */
        $data['start_date'] = date('Y-m-d', strtotime('-6 days'));
        $data['end_date'] = date('Y-m-d', strtotime('1 days'));
        $data['dataPoints'] = $this->GetDashboardDataPoints($data['start_date'], $data['end_date']);
        /* --inbound-brew-free-end-- */
        $data['Form'] = new FormHelper();
        echo $this->load->view(self::VIEW_PATH . 'admin', $data);
    }

    public function loadGettingStartedPage() {
        // breadcrumbs
        $this->Breadcrumb->add("Inbound Brew");
        $data['Breadcrumb'] = $this->Breadcrumb;
        echo $this->load->view(self::VIEW_PATH . 'getting_started', $data, "blank");
    }

    public function addShortCodeButtons() {
        // check user permissions
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }

        add_filter("mce_external_plugins", array($this, 'addTinyMceButtonPlugin'));
        add_filter('tiny_mce_before_init', array($this, 'formatTinyMCE'));
        add_filter('mce_buttons_3', array($this, 'registerTinyMceButtons'));
    }

    /**
     * User is done with dashboard wizzard and wants to remove it
     *
     * @author Rico Celis
     * @access private
     */
    public function hideWizzard() {
        $post = $_POST;
        $result = array(
            'type' => 0,
            'message' => "Unable to save setting. Please try again.",
        );
        if (wp_verify_nonce($_POST['nonce'], 'ib-kw-nonce') && current_user_can('delete_posts') && !empty($post)) {
            $result['type'] = 1;
            $result['message'] = "success";
            $Settings = new SettingsModel;
            $settings = $Settings->loadSettings();
            $settings->wizzard_hide = 1;
            $settings->save();
        }
        header('Content-Type: application/json');
        die(json_encode($result));
    }

    public function addTinyMceButtonPlugin($plugin_array) {
        if (@$_GET['page'] != "ib-email-admin") {
            $plugin_array['ib_cta_sc_button'] = BREW_MODULES_URL . 'CTA/assets/tinymce/cta-short-code.js?v=' . BREW_ASSET_VERSION;
            $post_type = get_post_type(get_the_ID());
            if ($post_type != "ib-landing-page" && @$_GET['page'] != "ib-leads-admin") {
                $plugin_array['ib_cf_sc_button'] = BREW_MODULES_URL . 'Contact/assets/js/cf-short-code.js?v=' . BREW_ASSET_VERSION;
            }
        }
        return $plugin_array;
    }

    public function registerTinyMceButtons($buttons) {
        if (@$_GET['page'] != "ib-email-admin") {
            array_push($buttons, "ib_cta_sc_button");
            $post_type = get_post_type(get_the_ID());
            if ($post_type != "ib-landing-page") {
                array_push($buttons, "ib_cf_sc_button");
            }
        }
        return $buttons;
    }

    //include the css file to style the graphic that replaces the shortcode
    function formatTinyMCE($in) {
        $in['content_css'] .= "," . BREW_MODULES_URL . 'CTA/assets/tinymce/cta-short-code.css?v=' . BREW_ASSET_VERSION;
        return $in;
    }

    public function handleBodyClasses($classes) {

        $addClasses = array();
        if (get_option(BREW_DEFAULT_LAYOUT_OPTION) == "side_nav") {

            $addClasses[] = "sticky-menu";

            if (isset($_COOKIE['inboundbrew_side_nav_state']) && $_COOKIE['inboundbrew_side_nav_state']) {
                $addClasses[] = "ib_collapsed";
            } else {
                if (get_option(BREW_AUTO_COLLAPSE_IB_SIDEBAR) == "true") {
                    $addClasses[] = "ib_collapsed";
                }
            }
        }

        if (get_option(BREW_AUTO_COLLAPSE_WP_SIDEBAR) == "true") {
            $addClasses[] = "folded";
        }
        return !empty($addClasses) ? $classes . " " . implode(" ", $addClasses) : $classes;
    }

    /**
     * Add meta boxes for Redirect Data.
     *
     * @author: Rico Celis
     * @access: public
     *
     */
    public function addMetaBoxes() {
        add_meta_box(
                'ib_core_post_data', "Post Data", array($this, 'setupMetabox')
        );
    }

    /**
     * Setup metabox data.
     *
     * @author: Rico Celis
     * @access: public
     *
     */
    public function setupMetabox() {
        $post = get_post();
        $data['post'] = $post;
        $data['Form'] = new FormHelper();
        echo $this->load->view(self::VIEW_PATH . 'metabox_core_post_data', $data, "blank");
    }

    /* --inbound-brew-free-start-- */

    /**
     * Get dashboard data points
     * @param string $start_date
     * @param string $end_date
     */
    public function GetDashboardDataPoints($start_date, $end_date) {
        global $wpdb;
        $graph_data = array();
        $begin = new \DateTime($start_date);
        $end = new \DateTime($end_date);
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);
        $sql = "SELECT COUNT(*) as value, 'posts_count' as label, post_date as created_at"
                . " FROM " . $wpdb->prefix . "posts"
                . " WHERE post_date BETWEEN '" . $start_date . "' AND '" . $end_date . "' AND post_status = 'publish' AND post_type IN ('post', 'ib-landing-page') GROUP BY CAST(created_at AS DATE)"
                . " UNION"
                . " SELECT COUNT(*) as value, 'leads_count' as label, created_at FROM " . $wpdb->prefix . "ib_leads WHERE created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "' GROUP BY CAST(created_at AS DATE)"
                . " UNION"
                . " SELECT COUNT(*) as value, 'social_network' as label, created_at  FROM " . $wpdb->prefix . "ib_social_network_post_records WHERE created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "' GROUP BY CAST(created_at AS DATE)";
        $ib_reports = $wpdb->get_results($sql);
        foreach ($ib_reports as $data) {
            //logic for the data
            $graph_data['graph_data'][$data->label][date("Y-m-d", strtotime($data->created_at))] = $data->value;
        }
        foreach ($period as $dt) {
            if (!isset($graph_data['graph_data']['posts_count'][$dt->format("Y-m-d")])) {
                $graph_data['graph_data']['posts_count'][$dt->format("Y-m-d")] = 0;
            }
            if (!isset($graph_data['graph_data']['leads_count'][$dt->format("Y-m-d")])) {
                $graph_data['graph_data']['leads_count'][$dt->format("Y-m-d")] = 0;
            }
            if (!isset($graph_data['graph_data']['social_network'][$dt->format("Y-m-d")])) {
                $graph_data['graph_data']['social_network'][$dt->format("Y-m-d")] = 0;
            }
        }
        return $graph_data;
    }

    /**
     * Filter dashboard chart
     */
    public function filterDashboardReports() {
        $start_date = date("Y-m-d", strtotime($_POST['start_date']));
        if (isset($_POST['end_date'])) {
            $end_date = date("Y-m-d", strtotime($_POST['end_date'] . ' +1 day'));
        } else {
            $end_date = date("Y-m-d", strtotime('+1 day'));
        }
        $data['dataPoints'] = $this->GetDashboardDataPoints($start_date, $end_date);
        $dataPoints = $data['dataPoints'];
        $path = self::VIEW_PATH . "ajax_dashboard";
        $graph_label = array();
        if (isset($dataPoints['graph_data'])) {
            $posts_count = $leads_count = $social_network = array();
            if (isset($dataPoints['graph_data']['posts_count'])) {
                $posts_count = array_keys($dataPoints['graph_data']['posts_count']);
            }
            if (isset($dataPoints['graph_data']['leads_count'])) {
                $leads_count = array_keys($dataPoints['graph_data']['leads_count']);
            }
            if (isset($dataPoints['graph_data']['social_network'])) {
                $social_network = array_keys($dataPoints['graph_data']['social_network']);
            }
        }
        $graph_label = array_unique(array_merge($posts_count, $leads_count, $social_network));
        sort($graph_label);

        ksort($dataPoints['graph_data']['posts_count']);
        ksort($dataPoints['graph_data']['leads_count']);
        ksort($dataPoints['graph_data']['social_network']);
        echo json_encode(array(
            "data" => $this->load->view($path, $data),
            "graph_label" => $graph_label,
            "posts_value" => array_values($dataPoints['graph_data']['posts_count']),
            "leads_value" => array_values($dataPoints['graph_data']['leads_count']),
            "social_value" => array_values($dataPoints['graph_data']['social_network'])
        ));
        exit;
    }

    /* --inbound-brew-free-end-- */
}
