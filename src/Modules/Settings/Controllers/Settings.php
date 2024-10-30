<?php

/**
 * Created by rico.celis
 * User: Rico
 * Date: 11/06/15
 * Time: 5:06 PM
 */

namespace InboundBrew\Modules\Settings\Controllers;

// libraries
use InboundBrew\Libraries\FormHelper;
use InboundBrew\Libraries\DateHelper;
use InboundBrew\Libraries\PaginatorHelper;
use InboundBrew\Libraries\LayoutHelper;
use InboundBrew\Libraries\FacebookHelper;
use InboundBrew\Libraries\TwitterHelper;
use InboundBrew\Libraries\LinkedInHelper;
// models
use InboundBrew\Modules\Core\AppController;
use InboundBrew\Modules\Settings\Models\SettingsModel;
use InboundBrew\Modules\Settings\Models\SocialNetworkAccount;
use InboundBrew\Modules\Settings\Models\SocialNetworkPostSetting;
use InboundBrew\Modules\Settings\Models\SocialNetworkPostRecord;
use InboundBrew\Modules\Redirects\Models\Redirect;
use InboundBrew\Modules\Redirects\Controllers\Redirect as RedirectController;
use InboundBrew\Modules\Sitemap\Libraries\SitemapData;
use InboundBrew\Modules\Sitemap\Libraries\RobotsData;
use InboundBrew\Modules\Core\Models\FormField;

class Settings extends AppController {

    const VIEW_PATH = 'Settings/views/';

    private $ib_social_about_custom_key = "_inboundbrew_social_about_custom_";
    private $post_type = 'ib-admin-settings';
    private $social_networks = array('facebook', 'twitter', 'linked_in', 'google');
    private $widget_networks = array(
        "facebook" => array(
            'name' => "Facebook",
            'class' => "facebook-square",
            'url' => "https://www.facebook.com/sharer/sharer.php?u=%s"),
        "twitter" => array(
            'name' => "Twitter",
            'class' => "twitter-square",
            'url' => "https://twitter.com/home?status=%s"
        ),
        "linked_in" => array(
            'name' => "Linked In",
            'class' => "linkedin-square",
            'url' => "https://www.linkedin.com/shareArticle?mini=true&url=%s&title=%s"),
        "google_plus" => array(
            'name' => "Google +",
            'class' => "google-plus-square",
            'url' => "https://plus.google.com/share?url=%s")
            /* "pinterest"=> "" */            ); // networks available for social share widget
    private $partials_path; // where partial elements are located

    public function __construct() {
        parent::init();
// if user is admin
        if (is_admin()) {
// enqueue scripts
            add_action('admin_enqueue_scripts', array($this, 'addAdminScripts'));
// add social meta box
            add_action('add_meta_boxes', array($this, 'addMetaBoxes'));
            add_action('save_post', array($this, 'saveSocialPostSettingsMetaboxData'), 1000);
// add ajax hooks
            add_action('wp_ajax_ib_load_posting_setting_defaults', array($this, 'loadPostSettingsDefaults'));
            add_action('wp_ajax_ib_save_posting_setting_defaults', array($this, 'savePostSettingsDefaults'));
            add_action('wp_ajax_ib_manage_social_share_widget', array($this, 'manageSocialShareWidget'));
            add_action('wp_ajax_ib_save_navigation_order', array($this, "saveNavOrder"));
// post hook
            add_action("admin_post_ib_save_social_urls", array($this, "saveSocialUrls"));


// leads
            add_action("wp_ajax_ib_add_custom_lead_field", array($this, "saveLeadField"));
            add_action("wp_ajax_ib_edit_custom_lead_field", array($this, "editLeadField"));
            add_action("wp_ajax_ib_delete_custom_lead_field", array($this, "deleteLeadField"));


            /* TODO: send test email from email settings
              if (@$_GET['page'] == 'ib-email-settings') {
              wp_enqueue_script('ib-email-tokens', BREW_MODULES_URL.'Contact/assets/js/email-tokens.jquery.js', array(), BREW_ASSET_VERSION);
              $FormField = new FormField;
              wp_localize_script('ib-email-tokens', 'ibEmailAjax',
              array(
              'ajaxurl' => admin_url( 'admin-ajax.php' ),
              'ibEmailNonce' => wp_create_nonce( 'ib-email-nonce' ),
              'Codes'=>FormField::all()->toArray()
              )
              );
              } */
        } else { // frontend
            add_action('wp_enqueue_scripts', array($this, 'addFrontEndScripts'));
            add_action('wp_footer', array($this, 'socialShareWidget'));
            add_action('wp_head', array($this, 'addScripts'));
        }
        add_action('init', array($this, 'checkForSocialPostings'), 1);
        $this->partials_path = BREW_MODULES_PATH . "Settings/views/partials/";
    }

    /**
     * Add meta boxes for social sharing
     *
     * @author: Rico Celis
     * @access: public
     *
     */
    function addMetaBoxes() {
        $postType = get_post_type();
// check post type
        if (!in_array($postType, $this->allowedPostTypes))
            return;
// check if connected to networks
        $Setting = new SettingsModel;
        $settings = $Setting->loadSettings();
// if connected to facebook
        if ($settings->social_connected_facebook) {
// add metabox
            $logo_source = BREW_PLUGIN_IMAGES_URL . "social/logo_facebook-white.png";
            add_meta_box(
                    'ib_social_facebook', "<img src='{$logo_source}' width='24' style='vertical-align: middle' /> Facebook Settings:", array($this, 'setMetaboxSettingsFacebook'), $postType, 'normal', 'default'
            );
        }
// if connected to twitter?
        if ($settings->social_connected_twitter) {
// add metabox
            $logo_source = BREW_PLUGIN_IMAGES_URL . "social/logo_twitter-white.png";
            add_meta_box(
                    'ib_social_twitter', "<img src='{$logo_source}' width='24' style='vertical-align: middle' /> Twitter Settings:", array($this, 'setMetaboxSettingsTwitter'), $postType, 'normal', 'default'
            );
        }
// if connected to twitter?
        if ($settings->social_connected_linked_in) {
// add metabox
            $logo_source = BREW_PLUGIN_IMAGES_URL . "social/logo_linked_in-white.png";
            add_meta_box(
                    'ib_social_linked_in', "<img src='{$logo_source}' width='24' style='vertical-align: middle' /> LinkedIn Settings:", array($this, 'setMetaboxSettingsLinkedIn'), $postType, 'normal', 'default'
            );
        }
    }

    /**
     * Add meta boxes for social sharing in Facebook
     *
     * @author: Rico Celis
     * @access: public
     *
     */
    public function setMetaboxSettingsFacebook() {
// post type object
        $data['post_type_object'] = get_post_type_object(get_post_type());
        $post_id = get_the_ID();
        $SocialNetworkPostSetting = new SocialNetworkPostSetting;
        $network = "facebook";
        $settings = $SocialNetworkPostSetting->getList($network, array(
            'as_array' => true,
            'add_accounts' => true,
            'wp_post_id' => $post_id));
        $data['post_settings'] = $settings;
// acounts
        $SocialNetworkAccount = new SocialNetworkAccount;
        $data['accounts'] = $SocialNetworkAccount->getAccountList($network, array('display_data' => true));
        $data['network'] = $network;
        $data['network_name'] = "Facebook";
        $Form = new FormHelper;
        $post_data = get_post_meta(get_the_ID(), $this->ib_social_about_custom_key . $network);
        if ($post_data)
            $Form->data = array(
                'SocialNetworkPostSetting' => array(
                    'about_page' => array(
                        "{$network}" => $post_data[0]
            )));
        $data['Form'] = $Form;
        echo $this->load->view(self::VIEW_PATH . 'metabox_social_settings', $data, "blank");
    }

    /**
     * Add meta boxes for social sharing in Facebook
     *
     * @author: Rico Celis
     * @access: public
     *
     */
    public function setMetaboxSettingsLinkedIn() {
// post type object
        $data['post_type_object'] = get_post_type_object(get_post_type());
        $post_id = get_the_ID();
        $SocialNetworkPostSetting = new SocialNetworkPostSetting;
        $network = "linked_in";
        $settings = $SocialNetworkPostSetting->getList($network, array(
            'as_array' => true,
            'add_accounts' => true,
            'wp_post_id' => $post_id));
        $data['post_settings'] = $settings;
// acounts
        $SocialNetworkAccount = new SocialNetworkAccount;
        $data['accounts'] = $SocialNetworkAccount->getAccountList($network, array('display_data' => true));
        $data['network'] = $network;
        $data['network_name'] = "LinkedIn";
        $data['limit_description'] = 256; // description limit.
        $Form = new FormHelper;
        $post_data = get_post_meta(get_the_ID(), $this->ib_social_about_custom_key . $network);
        if ($post_data)
            $Form->data = array(
                'SocialNetworkPostSetting' => array(
                    'about_page' => array(
                        "{$network}" => $post_data[0]
            )));
        $data['Form'] = $Form;
        echo $this->load->view(self::VIEW_PATH . 'metabox_social_settings', $data, "blank");
    }

    /**
     * Add meta boxes for social sharing in Twitter
     *
     * @author: Rico Celis
     * @access: public
     *
     */
    public function setMetaboxSettingsTwitter() {
// post type object
        $data['post_type_object'] = get_post_type_object(get_post_type());
        $post_id = get_the_ID();
        $SocialNetworkPostSetting = new SocialNetworkPostSetting;
        $network = "twitter";
        $settings = $SocialNetworkPostSetting->getList($network, array(
            'as_array' => true,
            'add_accounts' => true,
            'wp_post_id' => $post_id));
        $data['post_settings'] = $settings;
// acounts
        $SocialNetworkAccount = new SocialNetworkAccount;
        $data['accounts'] = $SocialNetworkAccount->getAccountList($network, array('display_data' => true));
        $data['network'] = $network;
        $data['network_name'] = "Twitter";
        $data['limit_description'] = 140; // twitter description limit.
        $Form = new FormHelper;
        $post_data = get_post_meta(get_the_ID(), $this->ib_social_about_custom_key . $network);
        if ($post_data)
            $Form->data = array(
                'SocialNetworkPostSetting' => array(
                    'about_page' => array(
                        "{$network}" => $post_data[0]
            )));
        $data['Form'] = $Form;
        echo $this->load->view(self::VIEW_PATH . 'metabox_social_settings', $data, "blank");
    }

    /**
     * save data from social posting settings metabox
     *
     * @author: Rico Celis
     * @access: public
     *
     */
    public function saveSocialPostSettingsMetaboxData($post_id) {
// check active networks
        $active_networks = array();
        $SettingsModel = new SettingsModel();
        $settings = $SettingsModel->loadSettings();
        if ($settings->social_connected_facebook)
            $active_networks[] = "facebook";
        if ($settings->social_connected_linked_in)
            $active_networks[] = "linked_in";
        if ($settings->social_connected_twitter)
            $active_networks[] = "twitter";
// loop through active networks
        foreach ($active_networks as $network) {
            if (!isset($_POST["inboundbrew_post_settings_{$network}_nonce"]))
                return; // Check if our nonce is set.
            if (!wp_verify_nonce($_POST["inboundbrew_post_settings_{$network}_nonce"], "ib_save_post_settings_{$network}"))
                return; // Verify that the nonce is valid.
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return; // don't do anything on autosave.
            if (wp_is_post_revision($post_id) !== false)
                return; //don't save this stuff on revisions

            if (isset($_POST['post_type']) && (in_array($_POST['post_type'], $this->allowedPostTypes))) { // Check the user's permissions.
                if (!current_user_can('edit_page', $post_id))
                    return;
            } else {
                if (!current_user_can('edit_post', $post_id))
                    return;
            }
            /* OK, it's safe for us to save the data now. */
            $post_settings = @$_POST['data']['SocialNetworkPostSetting'][$network];
// custom data
            $custom = $_POST['data']['SocialNetworkPostSetting']['about_page'][$network];
            $custom['about_title'] = $_POST['post_title'];
            if ($custom['use'] == "about_this_page") { // reset if using meta widget
                $custom['about_image'] = "";
                $custom['about_thumbnail'] = "";
                $custom['about_description'] = "";
            }
// save custom post data.
            update_post_meta($post_id, $this->ib_social_about_custom_key . $network, $custom);
//if(!$post_settings) continue;
// save post_settings
            $SocialNetworkPostSetting = new SocialNetworkPostSetting;
            $SocialNetworkPostSetting->updateSettings($network, $post_settings, array("wp_post_id" => $post_id));
        }
    }

    /** Enqueues Front End scripts & styles * */
    public function addScripts() {
        wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), BREW_ASSET_VERSION);
    }

    /**
     * Enqueues Admin only JS files
     *
     * @author Rico Celis
     * @access public
     */
    public function addAdminScripts() {
        wp_enqueue_script('ib-post-settings-jquery-js', BREW_MODULES_URL . 'Settings/assets/js/ib-post-settings.jquery.js', array('jquery'), BREW_ASSET_VERSION, true);

        wp_enqueue_script('ib-social-widget-admin-js', BREW_MODULES_URL . 'Settings/assets/js/ib_social-widget.jquery.js', array('jquery'), BREW_ASSET_VERSION, true);
        wp_enqueue_script('ib-lead-settings-js', BREW_MODULES_URL . 'Settings/assets/js/ib_lead_settings.jquery.js', array('jquery'), BREW_ASSET_VERSION, true);
        /* TODO: send test email from email settings
          wp_enqueue_script('ib-email-settings-js', BREW_MODULES_URL.'Settings/assets/js/ib_email_settings.jquery.js',array('jquery'), BREW_ASSET_VERSION, true );
         */

        if (isset($_GET['page']) && @$_GET['page'] == "ib-admin-settings") {
            // load css
            wp_enqueue_style('ib-settings-css', BREW_MODULES_URL . 'Settings/assets/css/ib-settings.css', array(), BREW_ASSET_VERSION);
            wp_enqueue_style('ib-social-share-widget-css', BREW_MODULES_URL . 'Settings/assets/css/ib-social-share-widget.css', array(), BREW_ASSET_VERSION);
            wp_enqueue_script('ib-social-widget-admin-js', BREW_MODULES_URL . 'Settings/assets/js/ib_social-widget.jquery.js', array('jquery'), BREW_ASSET_VERSION, true);
            wp_enqueue_script('ib-lead-settings-js', BREW_MODULES_URL . 'Settings/assets/js/ib_lead_settings.jquery.js', array('jquery'), BREW_ASSET_VERSION, true);
            /* TODO: send test email from email settings
              wp_enqueue_script('ib-email-settings-js', BREW_MODULES_URL.'Settings/assets/js/ib_email_settings.jquery.js',array('jquery'), BREW_ASSET_VERSION, true );
             */
            wp_localize_script('ib-lead-settings-js', 'ibSettingsAjax', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'ibSettingsNonce' => wp_create_nonce('ib-settings-nonce'),
            ));
            switch (@$_GET['section']) {
                case "oauth_callback":
                    wp_enqueue_script('ib-social-accounts-jquery-js', BREW_MODULES_URL . 'Settings/assets/js/ib_social-accounts.jquery.js', array('jquery'), BREW_ASSET_VERSION, true);
            }


            wp_enqueue_script('ib-tabs-jquery-js', BREW_MODULES_URL . 'Core/assets/js/ib-tabs.jquery.js', array('jquery'), BREW_ASSET_VERSION, true);
            wp_enqueue_style('colorpicker-css', BREW_MODULES_URL . 'Core/assets/third-party/colorpicker/css/colpick.css', array(), BREW_ASSET_VERSION);
            wp_enqueue_script('colorpicker-js', BREW_MODULES_URL . 'Core/assets/third-party/colorpicker/js/colpick.js', array(), BREW_ASSET_VERSION);

            /* Shepherd Load Decisions */

            //No section - must be the main settings page
            if (!isset($_GET['section'])) {
                wp_enqueue_script('ib-settings-main-shepherd', BREW_MODULES_URL . 'Settings/assets/js/ib-settings-main-shepherd.js', array('jquery'), BREW_ASSET_VERSION);
            }
            // Social Settings Page
            else if ($_GET['section'] == 'ib_social_settings') {
                wp_enqueue_script('ib-social-settings-add-shepherd', BREW_MODULES_URL . 'Settings/assets/js/ib-social-settings-add-shepherd.js', array('jquery'), BREW_ASSET_VERSION);
            }

            // Social Share Widget
            else if ($_GET['section'] == 'ib_social_share_widget') {
                wp_enqueue_script('ib-social-share-settings-add-shepherd', BREW_MODULES_URL . 'Settings/assets/js/ib-social-share-settings-add-shepherd.js', array('jquery'), BREW_ASSET_VERSION);
            }

            // Leads Settings
            else if ($_GET['section'] == 'ib_leads_settings') {
                wp_enqueue_script('ib-settings-add-shepherd', BREW_MODULES_URL . 'Settings/assets/js/ib-leads-settings-add-shepherd.js', array('jquery'), BREW_ASSET_VERSION);
            }

            // Email Settings
            else if ($_GET['section'] == 'ib_email_settings') {
                wp_enqueue_script('ib-email-settings-add-shepherd', BREW_MODULES_URL . 'Settings/assets/js/ib-email-settings-add-shepherd.js', array('jquery'), BREW_ASSET_VERSION);
            }

            // Advanced Settings
            else if ($_GET['section'] == 'ib_advance_settings') {
                wp_enqueue_script('ib-advance-settings-add-shepherd', BREW_MODULES_URL . 'Settings/assets/js/ib-advance-settings-add-shepherd.js', array('jquery'), BREW_ASSET_VERSION);
            }

        }
    }

    public function addFrontEndScripts() {
        wp_enqueue_style('ib-social-share-widget-css', BREW_MODULES_URL . 'Settings/assets/css/ib-social-share-widget.css', array(), BREW_ASSET_VERSION);
        wp_enqueue_script('ib-social-widget-admin-js', BREW_MODULES_URL . 'Settings/assets/js/ib_social-widget.jquery.js', array('jquery'), BREW_ASSET_VERSION, true);
    }

    /**
     * function is called when displaying initial view for controller
     *
     * @author Rico Celis
     * @access public
     */
    public function settingsAdmin() {
// helpers
        $section = (@$_GET['section']) ? $_GET['section'] : "ib_settings";
        switch ($section) {
            case "ib_settings":
                $this->settingsHome();
                break;
            case "ib_social_settings":
                $this->socialSettings();
                break;
            case "ib_social_share_widget":
                $this->shareWidgetSettings();
                break;
            case "oauth_callback":
                $this->oauthCallback();
                break;
            case "ib_social_post_settings":
                $this->socialPostSettings();
                break;
            case "ib_disconnect_network":
                $this->disconnectSocialNetwork();
                break;
            case "ib_social_posting_records":
                if (@$_GET['pid'])
                    $this->socialPostingRecordDetails($_GET['pid']);
                else
                    $this->socialPostingRecords($_GET['network']);
                break;
            case "ib_save_social_accounts":
                $this->saveAccountsAfterOAuth();
                break;
            case "ib_advance_settings":
                $this->advancedSettings();
                break;
            case "ib_email_settings":
                $this->emailSettings();
                break;
            case "ib_leads_settings":
                $this->leadSettings();
                break;
            case "ib_license_key":
                $this->licenseKeySettings();
                break;
            
        }
    }

    /**
     * display lead settings
     * standard fields and custom fields.
     *
     * @author Rico Celis
     * @access private
     */
    private function leadSettings() {
// load custom fields
        $data['custom_fields'] = FormField::where('field_custom', 1)->orderBy("field_name")->get();
        $data['static_fields'] = FormField::where('field_custom', 0)->orderBy("field_id")->get();
        $data['Layout'] = new LayoutHelper;
        $this->Breadcrumb->add("Lead Settings");
        $data['Breadcrumb'] = $this->Breadcrumb;
        $data['partials_path'] = $this->partials_path;
        $data['post_type'] = $this->post_type;
        $data['Form'] = new FormHelper;
        echo $this->load->view(self::VIEW_PATH . "admin_lead_settings", $data);
    }

    /**
     * save new lead field
     *
     * @author Rico Celis
     * @access private
     */
    public function saveLeadField() {
        $result = array(
            'message' => "Unable to save field. Please try again.",
            'success' => false
        );
        if (isset($_POST['nonce']) && wp_verify_nonce(@$_POST['nonce'], 'ib-settings-nonce')) {
            $post = $_POST['data']['LeadField'];
            $field = new FormField;
            $field->field_name = $post['field_name'];
            $field->field_type = $post['field_type'];
            $field->field_token = strtolower(preg_replace("/[^a-zA-Z0-9]/", "_", $post['field_name']));
            $field->field_custom = 1;
            if ($post['field_type'] == "singlecheckbox") {
                $field->field_value = (!empty($post['field_name'])) ? $post['field_name'] : "";
            } else {
                $field->field_value = (!empty($post['field_value'])) ? $post['field_value'] : "";
            }
            if ($field->save()) {

                $field->save();
                $post['field_id'] = $field->field_id;
                $post['field_value'] = stripcslashes(str_replace("\n ", ",", $field->field_value));
                $this->mapFormLeadData();
                $result = array(
                    'field' => $post,
                    'success' => true
                );
            }
        }
        die(json_encode($result));
    }

    /**
     * edit lead field
     *
     * @author Sean
     * @access private
     */
    public function editLeadField() {
        $result = array(
            'message' => "Unable to save field. Please try again.",
            'success' => false
        );
        if (isset($_POST['nonce']) && wp_verify_nonce(@$_POST['nonce'], 'ib-settings-nonce')) {
            $post = $_POST['data']['LeadField'];
            $field = FormField::find($post['lead_field_id']);
            if (@$field->field_id) {
                $field->field_name = $post['field_name'];
                $field->field_type = $post['field_type'];

                if ($post['field_type'] == "singlecheckbox") {
                    $field->field_value = (!empty($post['field_name'])) ? $post['field_name'] : "";
                } else {
                    $field->field_value = (!empty($post['field_value'])) ? $post['field_value'] : "";
                }
                $field->save();
                $post['field_id'] = $field->field_id;
                $post['field_value'] = stripslashes(str_replace("\n ", ",", $field->field_value));
                $result = array(
                    'field' => $post,
                    'success' => true
                );
            }
        }
        die(json_encode($result));
    }

    /**
     * edit lead field
     *
     * @author Sean
     * @access private
     */
    public function deleteLeadField() {
        $result = array(
            'message' => "Unable to delete field. Please try again.",
            'success' => false
        );
        if (isset($_POST['nonce']) && wp_verify_nonce(@$_POST['nonce'], 'ib-settings-nonce')) {
            $field_id = $_POST['lead_field_id'];
            $field = FormField::find($field_id);
            if (@$field->field_id && @$field->field_custom) { // exists and is custom
// delete lead data
                if ($field->deleteCustomField()) {
                    $result = array(
                        'message' => "Custom field and all related data has been deleted.",
                        'success' => true
                    );
                }
            }
        }
        die(json_encode($result));
    }

    /**
     * save lead fields in wp options ??
     *
     * @author Sean
     * @access private
     */
    public static function mapFormLeadData() {
        $forms = FormField::where('field_custom', 1)->orderBy('field_id')->get();
        $i = 1;
        foreach ($forms as $form) {
            $arr["data_" . $i] = $form->field_token;
            $i++;
        }
        update_option('ib_lead_fields', json_encode($arr));
    }

    /**
     * display settings' home page
     *
     * @author Rico Celis
     * @access private
     */
    private function settingsHome() {
        global $ib_dynamic_navigation;
        $post = @$_POST;
        $nonce = @$post['ib_settings_nonce'];
        if (isset($nonce) && wp_verify_nonce($nonce, 'ib_save_settings')) {
            if ($this->saveSettings("general", $post['data']['Settings'])) {
                $this->_confirm("Settings updated.");
//redirect in case menu nav options were changed - this way, they take place immediately.
                $this->jsRedirect(get_admin_url() . "admin.php?page=ib-admin-settings");
                exit;
            } else {
                $this->_error("Unable to update settings. Please try again.");
            }
        }
        $data['post_type'] = $this->post_type;
        $data['partials_path'] = $this->partials_path;
        $data['Layout'] = new LayoutHelper;
        $this->Breadcrumb->add("Inbound Brew Settings");
        $data['Breadcrumb'] = $this->Breadcrumb;
        $Form = new FormHelper();
        $settings = array(
//'usage_data' => get_option(BREW_SEND_USAGE_OPTION),
            'usage_data' => (BREW_FS_TRACKING ? "send" : "dont_send"),
            'auto_collapse_wp' => get_option(BREW_AUTO_COLLAPSE_WP_SIDEBAR),
            'auto_collapse_ib' => get_option(BREW_AUTO_COLLAPSE_IB_SIDEBAR),
            'modules' => get_option(BREW_ACTIVE_MODULES_OPTION),
            'ib_show_getting_started_menu' => get_option('ib_show_getting_started_menu'));
        $Form->data = array(
            'Settings' => $settings
        );
        $data['Form'] = $Form;
        $data['nav_values'] = $ib_dynamic_navigation;
// show view
        $path = self::VIEW_PATH . "admin_settings";
        echo $this->load->view($path, $data);
    }

    /**
     * display/edit advance settings' home page
     *
     * @author Rico Celis
     * @access private
     */
    private function advancedSettings() {
        $post = @$_POST;
        $nonce = @$post['ib_settings_nonce'];
        if (isset($nonce) && wp_verify_nonce($nonce, 'ib_save_settings')) {
// save stuff
            if ($this->saveSettings("advanced", $post['data'])) {
                $this->_confirm("Settings updated.");
            } else {
                $this->_error("Unable to update settings. Please try again.");
            }
        }
        $data['post_type'] = $this->post_type;
        $data['partials_path'] = $this->partials_path;
        $data['Layout'] = new LayoutHelper;
        $this->Breadcrumb->add("Sitemap");
        $data['Breadcrumb'] = $this->Breadcrumb;
        $Form = new FormHelper();
// Sitemap Data
        $obj = new SitemapData();
        $obj->loadData();
        $sitemap = array();
        foreach ($obj->data['options'] as $index => $option) {
            $sitemap[$index] = (array) $option;
        }
        $robots = RobotsData::getContent();
        $Form->data = array(
            'Sitemap' => $sitemap,
            'Robots' => array(
                'content' => $robots,
                'blog_public' => (get_option('blog_public')) ? "" : "on"),
            'Redirects' => get_option(BREW_REDIRECT_SETTINGS_OPTION));
// Robots Data
        $data['Form'] = $Form;
        $data['Layout'] = new LayoutHelper();
        $data = array_merge($data, $obj->data);
// show view
        $path = self::VIEW_PATH . "admin_advanced_settings";
        echo $this->load->view($path, $data);
    }

    /**
     * display/edit email settings
     *
     * @author Rico Celis
     * @access private
     */
    private function emailSettings() {
        $post = @$_POST;
        $nonce = @$post['ib_settings_nonce'];
        if (isset($nonce) && wp_verify_nonce($nonce, 'ib_save_settings')) {
// save stuff
            $settings = $post['data']['SendSettings'];
            if ($this->saveSettings("email", $settings)) {
                $this->_confirm("Settings updated.");
            } else {
                $this->_error("Unable to update settings. Please try again.");
            }
        }
        $data['post_type'] = $this->post_type;
        $data['partials_path'] = $this->partials_path;
        $data['Layout'] = new LayoutHelper;
        $this->Breadcrumb->add("Email Settings");
        $data['Breadcrumb'] = $this->Breadcrumb;
        $Form = new FormHelper();
// email settings
        $options = json_decode(get_option('ib_smtp_options'));
        $Form->data = array(
            'SendSettings' => (array) $options
        );
        $data['Form'] = $Form;
// show view


        $path = self::VIEW_PATH . "admin_email_settings";
        echo $this->load->view($path, $data);
    }

    /**
     * save settings
     * @param string $section which section we are saving settings for
     * @param array $data settings data
     *
     * @author Rico Celis
     * @access Private
     */
    private function saveSettings($section, $data) {
        global $ib_dynamic_navigation, $fs;
        $status = false;
        switch ($section) {
            case "general":
                // default layout
                update_option(BREW_DEFAULT_LAYOUT_OPTION, $data['default_layout']);

                update_option(BREW_SEND_USAGE_OPTION, $data['usage_data']);
                update_option('ib_show_getting_started_menu', $data['ib_show_getting_started_menu']);
                

                update_option(BREW_AUTO_COLLAPSE_WP_SIDEBAR, $data['auto_collapse_wp']);
                update_option(BREW_AUTO_COLLAPSE_IB_SIDEBAR, $data['auto_collapse_ib']);

                $post = (@$data['modules']) ? $data['modules'] : array();
                foreach ($ib_dynamic_navigation['navigation'] as $name => $values) {
                    if (@!$values['is_module'])
                        continue;
                    $post[$name] = (!@$values['can_turn_off']) ? "on" : (@$post[$name]) ? "on" : "";
                }
                update_option(BREW_ACTIVE_MODULES_OPTION, $post);

                //freemius "hooks"
                if ($data['usage_data'] != (BREW_FS_TRACKING ? "send" : "dont_send")) {
                    if ($data['usage_data'] == "dont_send") {
                        $fs->stop_tracking();
                    } else {
                        $noRedirect = $fs->allow_tracking();
                        if (!$noRedirect) {
                            update_option(BREW_SEND_USAGE_OPTION, "dont_send");
                            $this->jsRedirect(get_admin_url() . "admin.php?page=inbound-brew");
                        }
                    }
                }

                $status = true;
                break;
            case "advanced":
                // sitemap
                SitemapData::saveSettings($data['Sitemap']);
                // robots
                RobotsData::saveSettings($data['Robots']);
                // case
                Redirect::saveSettings($data['Redirects']);
                $status = true;
                break;
            case "email":
                $option = json_decode(get_option('ib_smtp_options'));
                foreach ($data as $key => $value) {
                    $option->$key = $value;
                }
                update_option('ib_smtp_options', json_encode($option));
                $status = true;
                break;
        }
        return $status;
    }

    /**
     * social settings
     *
     * @author Rico Celis
     * @access private
     */
    private function socialSettings() {
        $Settings = new SettingsModel;
        $settings = $Settings->loadSettings();
        $data['post_type'] = $this->post_type;
        $data['partials_path'] = $this->partials_path;
        $data['Layout'] = new LayoutHelper;
        $data['settings'] = $settings;
        $data['widget_networks'] = $this->widget_networks;
        // facebook
        $data['facebook_login_url'] = $Settings->getSocialNetworkLoginUrl("facebook");
        // twitter
        $data['twitter_login_url'] = $Settings->getSocialNetworkLoginUrl("twitter");
        // linkedin
        $data['linked_in_login_url'] = $Settings->getSocialNetworkLoginUrl("linked_in");
        // breadcrumbs
        $this->Breadcrumb->add("Social Network Settings");
        $data['Breadcrumb'] = $this->Breadcrumb;
        // form
        $Form = new FormHelper;
        $Form->data = array(
            'Setting' => $settings->toArray());
        $data['Form'] = $Form;
        // check wizzard status
        if (isset($_GET['wizzard'])) {
            $path = self::VIEW_PATH . "settings_wizzard_instructions";
            echo $this->load->view($path, array(
                'settings' => $settings
                    ), "blank");
        }


        // show view
        $path = self::VIEW_PATH . "admin_social_settings";
        echo $this->load->view($path, $data);
    }

    /**
     * social share widget settings
     *
     * @author Rico Celis
     * @access private
     */
    private function shareWidgetSettings() {
        $Settings = new SettingsModel;
        $settings = $Settings->loadSettings();
        $data['post_type'] = $this->post_type;
        $data['partials_path'] = $this->partials_path;
        $data['Layout'] = new LayoutHelper;
        $data['settings'] = $settings;
        $data['widget_networks'] = $this->widget_networks;
// breadcrumbs
        $this->Breadcrumb->add("Social Share Widget");
        $data['Breadcrumb'] = $this->Breadcrumb;
        $widget_settings = get_option(BREW_SOCIAL_WIDGET_SETTINGS);
        $widget_settings['options'] = (array) get_option(BREW_SOCIAL_WIDGET_OPTIONS);
// get post types.
        $data['post_types'] = $this->getAllPostTypes();
// form
        $Form = new FormHelper;
        $Form->data = array(
            'Setting' => $settings->toArray(),
            'ShareWidget' => (array) $widget_settings);
        $data['Form'] = $Form;
        $data['fonts'] = $this->getFonts();
// show view
        $path = self::VIEW_PATH . "admin_social_share_widget";
        echo $this->load->view($path, $data);
    }

    /**
     * user is connecting to a social network
     * need to handle callback based on network
     *
     * @author Rico Celis
     * @access private
     */
    private function oauthCallback() {
        $token = @$_GET['token'];
        $network = @$_GET['network'];
        $account_id = @$_GET['account_type_id'];
        $screen_name = @$_GET['screen_name'];
// check if found token
        if ($token) {
            switch ($network) {
                case "facebook":
                    $Facebook = new FacebookHelper($account_id);
                    $accounts = $Facebook->getUserAccounts($token, true);
                    break;
                case "twitter":
// api server handled it.
                    break;
                case "linked_in":
                    $LinkedIn = new LinkedInHelper;
                    $expiration = $_GET['expiration'];
                    $accounts = $LinkedIn->getUserAccounts($token);
                    $profile = $LinkedIn->getUserProfile($token);
                    if ($profile) {
                        $screen_name = $profile->firstName . " " . $profile->lastName;
                        $account_id = $profile->id;
                    }
                    break;
                case "google":
                    $expiration = $_GET['expiration'];
                    break;
            }

// new acounts
            $new_accounts = array(
                array(
                    'social_network' => $network,
                    'account_type' => "me",
                    'name' => $screen_name,
                    'id' => $account_id,
                    'access_token' => $token,
                    'meta1' => @$_GET['meta1'],
                    'meta2' => @$_GET['meta1']
                )
            );
            if (!empty($accounts))
                $new_accounts = array_merge($new_accounts, $accounts);
            $data['new_accounts'] = $new_accounts;
            $data['network'] = $network;
            $data['screen_name'] = $screen_name;
            $data['expiration'] = @$expiration;
// old accounts
            $SocialNetworkAccount = new SocialNetworkAccount;
            $data['old_accounts'] = $SocialNetworkAccount->getAccountList($network, array('as_array' => true));
            $data['network_name'] = $this->getNetworkDisplayName($network);
            $data['post_type'] = $this->post_type;
// breadcrumbs
            $this->Breadcrumb->add("Social Network Settings", "admin.php?page={$this->post_type}&section=ib_social_settings");
            $this->Breadcrumb->add(sprintf("Select %s Accounts", $data['network_name']));
// helpers
            $data['Breadcrumb'] = $this->Breadcrumb;
            $data['Form'] = new FormHelper;
            $data['Layout'] = new LayoutHelper;
// show view
            $path = self::VIEW_PATH . "admin_select_social_accounts";
            echo $this->load->view($path, $data);
        }else {
            $this->_error(sprintf("Error: %s", $_POST['error']));
            $this->socialSettings();
        }
    }

    /**
     * explicit control for converting network token to network display name
     * needed to handle LinkedIn appropriately
     *
     * @author Chris Fontes
     * @access private
     */
    function getNetworkDisplayName($network) {
        switch (strtolower($network)) {
            case "linked_in":
                return "LinkedIn";
            default:
                return ucwords(str_replace("_", "", $network));
        }
    }

    /**
     * save accounts user wants to allow access to afer validation.
     *
     * @author Rico Celis
     * @access private
     */
    function saveAccountsAfterOAuth() {
        if (isset($_POST['ib_save_social_accounts_nonce']) &&
                wp_verify_nonce($_POST['ib_save_social_accounts_nonce'], 'ib_save_social_accounts') &&
                current_user_can('manage_options')) {
// verified
            $data = $_POST['data'];
            $network = $data['SocialSetting']['network'];
            $SocialNetworkAccount = new SocialNetworkAccount;
            $accounts = $data['SocialNetworkAccount'];
            $SocialNetworkAccount->saveAccountTokens($network, $accounts);
// update settings to show is connected and expiration for facebook token
            $Settings = new SettingsModel;
            $Settings->socialNetworkConnected($network, @$data['SocialSetting']['expiration'], @$data['SocialSetting']['screen_name']);
            $network_name = $this->getNetworkDisplayName($network);

// mark wizzard step as completed.
            $Settings->wizzardStepCompleted("social_settings");
            $this->_confirm(sprintf("Connected To %s.", $network_name));
        } else {
            $this->_error("You do not have enough access.");
        }
        $this->socialSettings();
    }

    /**
     * user wants to remove connection to a social network
     *
     * @author Rico Celis
     * @access private
     */
    public function disconnectSocialNetwork() {
        $network = $_GET['network'];
        $nonce = $_GET['_wpnonce'];
        if (wp_verify_nonce($nonce, 'ib-disconnect-network') && current_user_can('manage_options') && in_array($network, $this->social_networks)) {
            $Settings = new SettingsModel;
            $Settings->disconnectSocialNetwork($network);
            $this->_confirm(sprintf("Disconnected from %s.", ucwords(str_replace("_", " ", $network))));
        } else {
            $this->_error(sprintf("Unable to disconnected from %s. Please try again.", ucwords(str_replace("_", " ", $network))));
        }
        $this->socialSettings();
    }

    /**
     * allow users to manage post settings for social network
     * param $_GET['network'] what network is being used.
     *
     * @author Rico Celis
     * @access private
     */
    public function socialPostSettings() {
        $network = $_GET['network'];

        $Settings = new SettingsModel;
        $SocialNetworkPostSetting = new SocialNetworkPostSetting;
        if (current_user_can('delete_posts') && !empty($_POST['data'])) {
            $post = $_POST['data'];
// save post settings
            $SocialNetworkPostSetting->updateSettings($network, $post['SocialNetworkPostSetting'][$network]);
            $this->_confirm("Settings Updated.");
        }
// settings
        $settings = $Settings->loadSettings();
// post settings
        $postSettings = $SocialNetworkPostSetting->getList($network, array('as_array' => true, 'add_accounts' => true));
        $SocialNetworkAccount = new SocialNetworkAccount;
        $socialAccounts = $SocialNetworkAccount->getAccountList($network, array('display_data' => true));
        $Form = new FormHelper;
        $Form->data = array('Setting' => $settings->toArray());
        $data['post_type'] = $this->post_type;
        $data['network'] = $network;
        $data['network_name'] = $this->getNetworkDisplayName($network);
        $data['post_settings'] = $postSettings;
        $data['accounts'] = $socialAccounts;
// breadcrumbs
        $this->Breadcrumb->add("Social Network Settings", "admin.php?page=" . $this->post_type . "&section=ib_social_settings");
        $this->Breadcrumb->add(sprintf("%s Post Settings", $data['network_name']));
        $data['Breadcrumb'] = $this->Breadcrumb;
        $data['Form'] = $Form;

// load view
        echo $this->load->view(self::VIEW_PATH . "admin_social_post_settings", $data);
    }

    /**
     * user wants to load all default post settings for a network
     *
     * @author Rico Celis
     * @access private
     */
    public function loadPostSettingsDefaults() {
        $result['type'] = 0;
        $result['message'] = "Unable to load defaults. Please try again.";
        $post = $_POST;
        if (wp_verify_nonce($_POST['nonce'], 'ib-kw-nonce') && current_user_can('delete_posts') && !empty($post)) {
            $network = $_POST['network'];
            $SocialNetworkPostSetting = new SocialNetworkPostSetting;
            $postSettings = $SocialNetworkPostSetting->getList($network, array('as_array' => true, 'add_accounts' => true));
            $result['type'] = 1;
            $result['post_settings'] = $postSettings;
        }
        header('Content-Type: application/json');
        die(json_encode($result));
    }

    /**
     * save settings within page/post as defaults.
     *
     * @author Rico Celis
     * @access private
     */
    public function savePostSettingsDefaults() {
        $result['type'] = 0;
        $result['message'] = "Unable save as defaults. Please try again.";
        $post = $_POST;
        parse_str($post['settings'], $post_settings);
        if (wp_verify_nonce($_POST['nonce'], 'ib-kw-nonce') && current_user_can('delete_posts') && !empty($post)) {
            $network = $_POST['network'];
            $SocialNetworkPostSetting = new SocialNetworkPostSetting;
            $postSettings = $post_settings['data']['SocialNetworkPostSetting'][$network];
            $SocialNetworkPostSetting->overwriteDefaults($network, $postSettings);
            $result['type'] = 1;
            $result['message'] = "Default settings updated";
        }
        header('Content-Type: application/json');
        die(json_encode($result));
    }

    /**
     * checks database for any items that need to be posted to social networks
     * check that user is connected.
     * check only networks the user is connected to.
     * @param boolean $post_now true if we're looking for things that need to be posted right after publish
     *
     * @author Rico Celis
     * @access private
     */
    public function checkForSocialPostings($post_now = true) {
        $Settings = new SettingsModel;
        $settings = $Settings->loadSettings();
        $PostSetting = new SocialNetworkPostSetting();
        // loop through networks
        foreach ($this->social_networks as $network) {
            $connected = "social_connected_{$network}";

            if ($settings->$connected != null) { // connected to network
                // get all items that need to be posted
                $postings = $PostSetting->needToPost($network, true);
                if ($postings->count()) { // if any results.
                    $PostSetting->postToNetwork($postings); // post to the network
                }
            }
        }
    }

    /**
     * show a list of posting records associated with a SocialPostSetting
     *
     * @param int $post_setting_id id for SocialNetworkPostSetting record
     * @author Rico Celis
     * @access private
     */
    public function socialPostingRecordDetails($post_setting_id) {
        $PostSetting = new SocialNetworkPostSetting();
        $post_setting = $PostSetting->find($_GET['pid']);
        if ($post_setting->post_setting_id) { // valid record
            $network = $post_setting['social_network'];
// breadcrumb
            $this->Breadcrumb->add("Social Network Settings", "admin.php?page=" . $this->post_type . "&section=ib_social_settings");
            $this->Breadcrumb->add("Social Posting History", "admin.php?page=" . $this->post_type . "&section=ib_social_posting_records");
            $this->Breadcrumb->add("Post Details");
            $data['Breadcrumb'] = $this->Breadcrumb;
            $data['Layout'] = new LayoutHelper;
            $data['Date'] = new DateHelper;
            $data['post_setting'] = $post_setting;
            $SocialNetworkAccount = new SocialNetworkAccount;
            $data['accounts'] = $SocialNetworkAccount->getAccountList($network, array('as_array' => true));
            $SocialNetworkPostRecord = new SocialNetworkPostRecord;
            $data['records'] = $SocialNetworkPostRecord->getPostSettingRecords($post_setting->post_setting_id, array(
                'get_social_stats' => true,
                'accounts' => $data['accounts']
            ));
            $data['network_image'] = BREW_PLUGIN_IMAGES_URL . "/social/logo_{$network}.png";
            $data['network_name'] = $this->getNetworkDisplayName($network);
// load view
            echo $this->load->view(self::VIEW_PATH . "admin_social_posting_record_details", $data);
        } else {
            $this->_error("Invalid Record");
            $this->socialPostingRecords();
        }
    }

    /**
     * show a history of all posting to a social network.
     *
     * @param string $network network to query. if not passed it will query all networks.
     * @author Rico Celis
     * @access private
     */
    public function socialPostingRecords($network = null) {
        $order = "updated_at";
        $direction = "ASC";
        if (isset($_GET['order']))
            $order = $_GET['order'];
        if (isset($_GET['direction']))
            $direction = $_GET['direction'];
        $wp_page = $_GET['page'];
        $SocialNetworkPostSetting = new SocialNetworkPostSetting;
        $appends = array(
            'page' => $_GET['page'],
            'section' => $_GET['section']
        );
        if ($network)
            $appends['network'] = $network;
        $data['history'] = $SocialNetworkPostSetting->history($network, $order, $direction, $appends);
// helpers
        $data['Breadcrumb'] = $this->Breadcrumb;
        $data['Layout'] = new LayoutHelper;
        $data['Date'] = new DateHelper;
        $data['Paginator'] = new PaginatorHelper;
// breadcrumb
        $this->Breadcrumb->add("Social Network Settings", "admin.php?page=" . $this->post_type . "&section=ib_social_settings");
        $title = "Social Posting History";
        if ($network) {
            $data['network_name'] = $this->getNetworkDisplayName($network);
            $title .= ": {$data['network_name']}";
        }
        $this->Breadcrumb->add($title);
        $data['network'] = $network;
// load view
        if (!count($data['history']))
            $this->_error("No history found.");
        echo $this->load->view(self::VIEW_PATH . "admin_social_posting_records", $data);
    }

    /** user can turn on and off networks to add to social share widget.
     * through AJAX
     *
     * @return json object with type = 0|1 and a message.
     * @author Rico Celis
     * @access private
     */
    public function manageSocialShareWidget() {
        $result['type'] = 0;
        $result['message'] = 'Unable to save settings. Please try again.';
        $post = $_POST;
        if (wp_verify_nonce($_POST['nonce'], 'ib-kw-nonce') && current_user_can('delete_posts') && !empty($post)) {
            unset($post['nonce']);
            unset($post['action']);
            $post = $post['data']['ShareWidget'];
            $result['type'] = 1;
            $result['message'] = "Social share widget settings updated.";
            $options = (@$post['options']) ? $post['options'] : array();
            update_option(BREW_SOCIAL_WIDGET_OPTIONS, $options);
            if (@$post['options'])
                unset($post['options']);
            update_option(BREW_SOCIAL_WIDGET_SETTINGS, $post);
        }
        header('Content-Type: application/json');
        die(json_encode($result));
    }

    /** user is saving the order the top nav should be.
     * through AJAX
     *
     * @return json object with type = 0|1 and a message.
     * @author Rico Celis
     * @access private
     */
    public function saveNavOrder() {
        $result['type'] = 0;
        $result['message'] = 'Unable to save settings. Please try again.';
        $post = $_POST;
        if (wp_verify_nonce($_POST['nonce'], 'ib-nav-nonce') && current_user_can('delete_posts') && !empty($post)) {
            $options = get_option(IB_TOP_NAV_VALUES);
            $options['order'] = $post['order'];
            $result['type'] = 1;
            $result['message'] = "Settings Updated.";
            update_option(IB_TOP_NAV_VALUES, $options);
        }
        header('Content-Type: application/json');
        die(json_encode($result));
    }

    /** Save social urls through admin_post hook
     *
     * @return json object with type = 0|1 and a message.
     * @author Rico Celis
     * @access private
     */
    public function saveSocialUrls() {
        if (@isset($_POST['_wpnonce']) && wp_verify_nonce(@$_POST['_wpnonce'], 'ib_save_social_urls_nonce')) {
            $post = $_POST['data']['Setting'];
            $Setting = new SettingsModel;
            $settings = $Setting->loadSettings();
            foreach ($post as $index => $value) {
                $settings->$index = $value;
            }
            $settings->save();
// save email template
            $this->_confirm("Your social urls have been saved.", true);
            header("Location: admin.php?page=" . $this->post_type . "&section=ib_social_settings");
        }
    }

    /** add social share widget to footer in all pages.
     *
     * @return json object with type = 0|1 and a message.
     * @author Rico Celis
     * @access private
     */
    public function socialShareWidget() {
        $settings = get_option(BREW_SOCIAL_WIDGET_SETTINGS);
        if (@$settings['turn_off_widget'])
            return;

        $has_networks = false;
// check if anynetworks are connected
        $post_id = get_the_ID();
        $permalink = urlencode(get_permalink($post_id));
        $title = urlencode(get_the_title($post_id));
// check options
        $post_type = get_post_type($post_id);
        $options = get_option(BREW_SOCIAL_WIDGET_OPTIONS);
        if (!@$options[$post_type])
            return;
        if (wp_is_mobile() && !@$options["show_on_mobile"])
            return;
// check post type
        if (!$options[$post_type])
            return;

        foreach ($this->widget_networks as $network => $values) {
            $field = "{$network}_share";
            if (@$settings[$field]) {
                $settings[$field] = sprintf($values['url'], $permalink, $title);
                $has_networks = true;
            }
        }
        if ($has_networks) {


            $widget_style = "";
// background settings.
            foreach ($settings['background'] as $property => $value) {
                $property = str_replace("_", "-", $property);
                switch ($property) {
                    case "v-padding":
                        $widget_style .= "padding-top:{$value}px;padding-bottom:{$value}px;";
                        break;
                    case "h-padding":
                        $widget_style .= "padding-left:{$value}px;padding-right:{$value}px;";
                        break;
                    default:
                        if (strpos($property, "color")) {
                            $widget_style .= "{$property}:#{$value};";
                        } else {
                            $widget_style .= "{$property}:{$value}px;";
                        }
                        break;
                }
            }
            if ($settings['background']['h_padding'] > 0) {
                $new = 50 + ($settings['background']['h_padding'] * 2) + ($settings['background']['border_width'] * 2);
                $widget_style .= "width:{$new}px;";
            }
// position
            $location = $settings['position']['location'];
            $margin = "-{$settings['background']['border_width']}px";
            $widget_style .= "margin-{$location}:-{$settings['background']['border_width']}px;{$location}:0px;top:{$settings['position']['top']}%;";
            $data['widget_style'] = $widget_style;
            $data['widget_networks'] = $this->widget_networks;
            $data['settings'] = $settings;
// title style
            $title_styles = "";
            foreach ($settings['title'] as $property => $value) {
                $property = str_replace("_", "-", $property);
                switch ($property) {
                    case "text":
                        continue;
                        break;
                    case "text-transform":
                    case "font-weight":
                    case "font-style":
                    case "text-decoration":
                    case "font-family":
                        $value = $value;
                        break;
                    default:
                        if (strpos($property, "color") !== false)
                            $value = "#" . $value;
                        else
                            $value = $value . "px";
                        break;
                }
                if ($value)
                    $title_styles .= "{$property}:{$value};";
            }
            $data['title_styles'] = $title_styles;
            echo $this->load->view(self::VIEW_PATH . "social_share_widget", $data, "blank");
        }
        return;
    }

    private function getAllPostTypes() {
        $post_types = get_post_types(array(
            '_builtin' => true,
            'public' => true
                ), 'objects');
        $post_types['ib-landing-page'] = json_decode(json_encode(array(
            'name' => 'ib-landing-page',
            'labels' => array(
                'name' => "Landing Pages",
                'singular_name' => "Landing Page"
            )
                )), FALSE);
        return $post_types;
    }

    

    /**
     * display License Key settings
     * standard fields and custom fields.
     *
     * @access private
     */
    private function licenseKeySettings() {
        $data['Layout'] = new LayoutHelper;
        $this->Breadcrumb->add("License Key Settings");
        $data['Breadcrumb'] = $this->Breadcrumb;
        $data['partials_path'] = $this->partials_path;
        $data['post_type'] = $this->post_type;
        $data['Form'] = new FormHelper;
        
        echo $this->load->view(self::VIEW_PATH . "admin_license_key_settings", $data);
    }

}
