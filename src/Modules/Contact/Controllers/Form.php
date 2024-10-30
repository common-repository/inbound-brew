<?php

/**
 * Created by sean.carrico.
 * User: sean
 * Date: 3/25/15
 * Time: 12:45 PM
 */

namespace InboundBrew\Modules\Contact\Controllers;

use InboundBrew\Modules\Contact\Models\Email;
use InboundBrew\Modules\Contact\Models\EmailTemplate;
use InboundBrew\Modules\Contact\Models\EmailField;
use InboundBrew\Modules\Contact\Models\ContactField;
use InboundBrew\Modules\Content\Models\Download;
use InboundBrew\Modules\Core\Models\FormField;
use InboundBrew\Modules\Core\AppController;
use InboundBrew\Modules\Core\Models\Country;
use InboundBrew\Modules\Core\Models\Lead;
use InboundBrew\Modules\Core\Models\LeadData;
use InboundBrew\Modules\Core\Models\LeadHistory;
use InboundBrew\Modules\Core\Models\State;
use InboundBrew\Modules\Settings\Models\SettingsModel;
use InboundBrew\Modules\Settings\Controllers\Settings;
use InboundBrew\Modules\Contact\Models\Post as ContactPost;
// libraries
use InboundBrew\Libraries\MathCaptcha;
use InboundBrew\Libraries\LeadDataMap;
use InboundBrew\Libraries\BreadcrumbHelper;
use InboundBrew\Libraries\FormHelper;
use InboundBrew\Libraries\DateHelper;
use InboundBrew\Libraries\FontAwesomeHelper;
use Valitron\Validator;
use WP_Query;

/**
 * Class Form
 * @package InboundBrew\Modules\Contact
 */
class Form extends AppController {

    /**
     *
     */
    const VIEW_PATH = 'Contact/views/';

    /**
     * @var array
     */
    private $data = array();

    /**
     * @var MathCaptcha
     */
    private $cpa;

    /**
     * @var string
     */
    private $post_type = 'ib-contact-form';

    /**
     *
     */
    public function __construct() {
        parent::init();
        // add menu
        add_action('init', array($this, 'registerPostType'));
        add_action('init', array($this, 'register_cf_shortcode'));
        if (is_admin()) {
            add_action('add_meta_boxes_' . $this->post_type, array($this, 'addMetaBox'), 0);
            add_action('save_post_' . $this->post_type, array($this, 'saveMetaBox'));
            add_action('admin_action_clone_form_as_draft', array($this, 'clonePost'));
            add_action('admin_post_ib_contact_form_settings', array($this, 'saveConfigSettings'));

            add_action("wp_ajax_send_ib_contact", array($this, "receiveInput"));
            add_action("wp_ajax_load_custom_lead_field", array($this, "loadFieldTypes"));
            add_action("wp_ajax_save_custom_lead_field", array($this, "saveLeadField"));
            add_action("wp_ajax_load_lead_field_options", array($this, "loadFieldOptions"));
            add_action("wp_ajax_verify_delete_custom_lead_field", array($this, "verifyDeleteLeadField"));
            add_action("wp_ajax_delete_custom_lead_field", array($this, "deleteLeadField"));
        }
        add_filter('the_content', array($this, 'contactFormFilter'));

        // ajax hook
        add_action("wp_ajax_nopriv_send_ib_contact", array($this, "receiveInput"));

        //admin js
        add_action('wp_enqueue_scripts', array($this, 'addPublicScripts'));

        add_action('admin_enqueue_scripts', array($this, 'addAdminScripts'));
        $this->cpa = new MathCaptcha();
    }

    public function register_cf_shortcode() {
        add_shortcode('brew_cf', array($this, 'registerShortCode'));
    }

    /**
     * @param $atts
     * @return string
     */
    public function registerShortCode($atts) {
        $a = shortcode_atts(array(
            'id' => '0',
                ), $atts);
        $post = get_post($a['id']);
        if ($post->post_status == 'publish') {
            if (preg_match('/{operand1}/', $post->post_content)) {
                $this->cpa->resetCaptcha();
                $patterns = array('/{operand1}/', '/{operator}/', '/{operand2}/');
                $replacements = array($this->cpa->operand1, $this->cpa->operator, $this->cpa->operand2);
                $post->post_content = preg_replace($patterns, $replacements, $post->post_content);
            }
            return $post->post_content;
        } else {
            return '';
        }
    }

    public function loadAdminPage() {
        $section = "list";
        if (isset($_GET['section']))
            $section = $_GET['section'];
        switch ($section) {
            case "list":
                $this->loadContactForms();
                break;
            case "settings":
                $this->loadFormSettings();
                break;
            case "add":
                $this->addContactForm();
                break;
            case "edit":
                $this->editContactForm($_GET['cf_id']);
                break;
        }
    }

    /**
     * load contact form data.
     *
     * @param int $cf_id wp post id for contact form.
     * @access Public
     * @author Rico Celis
     */
    public function editContactForm($cf_id) {
        $contact_form = ContactPost::find($cf_id);
        if ($contact_form) {
            $nonce = @$_POST['_wpnonce'];
            if (!empty($_POST) && $nonce && wp_verify_nonce($nonce, 'ib_contact_form') && current_user_can("edit_posts")) {
                $post = $_POST;

                if (!isset($post['allow_download'])) { // reset values if user doesn't want a download.
                    $post['allow_download'] = "0";
                    $post['download_content'] = "";
                    $post['expires_after'] = "";
                    $post['download_limit'] = "";
                }
                if (!isset($post['dont_send_email_template'])) { // reset values if user doesn't want a download.
                    $post['dont_send_email_template'] = "0";
                } else {
                    $post['email_template'] = ""; //reset email template selection.
                }

                if (is_array($post['email_template'])) {
                    $post['email_template'] = implode("|", @$post['email_template']);
                }

                $this->saveFormPostData($post, $cf_id);
                // confirm
                $this->_confirm("Contact Form Updated", true);
                // redirect
                $this->jsRedirect("admin.php?page=ib-contact-forms&section=edit&cf_id={$cf_id}");
            } else {
                // breadcrumb
                $this->Breadcrumb->add($this->labels['name'], "admin.php?page=ib-contact-forms&section=list");
                $this->Breadcrumb->add($this->labels['edit_item']);
                // variables
                $this->setData($cf_id);
                $this->data['submit_url'] = "admin.php?page=ib-contact-forms&section=edit&cf_id={$cf_id}";
                $this->data['submit_button_title'] = "Update Form Settings";
                // helpers
                $this->data['Breadcrumb'] = $this->Breadcrumb;
                $this->data['Form'] = new FormHelper;
                echo $this->load->view(self::VIEW_PATH . 'contact-form', $this->data);
            }
        } else {
            $this->_error("Invalid Contact Form");
            $this->loadContactForms();
        }
    }

    /**
     * Create new contact form
     *
     * @access Public
     * @author Rico Celis
     */
    public function addContactForm() {
        $nonce = @$_POST['_wpnonce'];
        if (!empty($_POST) && $nonce && wp_verify_nonce($nonce, 'ib_contact_form') && current_user_can("edit_posts")) {
            $post = $_POST;
            $my_post = array(
                'post_title' => $_POST['post_title'],
                'post_content' => $_POST['post_content'],
                'post_status' => 'publish',
                'post_type' => $this->post_type,
                'post_author' => get_current_user_id()
            );
            $post_id = wp_insert_post($my_post, true);
            $this->saveFormPostData($_POST, $post_id);
            // confirm
            $this->_confirm("Contact Form Created", true);
            // redirect
            $this->jsRedirect("admin.php?page=ib-contact-forms&section=edit&cf_id={$post_id}");
        } else {
            // breadcrumb
            $this->Breadcrumb->add($this->labels['name'], "admin.php?page=ib-contact-forms&section=list");
            $this->Breadcrumb->add($this->labels['add_new_item']);
            // variables
            $this->setData();
            $this->data['submit_url'] = "admin.php?page=ib-contact-forms&section=add";
            $this->data['submit_button_title'] = "Create Form";
            // helpers
            $this->data['Breadcrumb'] = $this->Breadcrumb;
            $this->data['Form'] = new FormHelper;
            echo $this->load->view(self::VIEW_PATH . 'contact-form', $this->data);
        }
    }

    /**
     * use data from $_POST and post id to save post meta and update post content.
     *
     * @params array $data contact form post data
     * @params int $post_id wp post id.
     * @return boolean true when done.
     * @access Public
     * @author Rico Celis
     */
    private function saveFormPostData($data, $post_id) {
        // Checks for input and sanitizes/saves if needed
        foreach ($data as $key => $value) {
            if (is_string($value) && $key != 'leadfield') {
                switch ($key) {
                    case 'post_content':
                        update_post_meta($post_id, $key, stripslashes($value));
                        break;
                    case 'post_title':
                        $postTitle = $value;
                    default:
                        update_post_meta($post_id, $key, sanitize_text_field($value));
                        break;
                }
            }
        }

        // handle field
        $post = ContactPost::find($post_id);
        $post->fields()->detach();
        if (isset($data['leadfield']))
            $post->fields()->attach($data['leadfield']);
        $postContent = $this->wrapForm($post_id, @$data['post_content']);
        $my_post = array(
            'ID' => $post_id,
            'post_content' => $postContent,
            'post_title' => $postTitle
        );
        // save wizzard step
        $Settings = new SettingsModel;
        $Settings->wizzardStepCompleted("contact_forms");
        // Update the post into the database

        remove_action('save_post_' . $this->post_type, array($this, 'saveMetaBox'));
        wp_update_post($my_post);
        add_action('save_post_' . $this->post_type, array($this, 'saveMetaBox'));
    }

    /**
     * get a list of all published pages a user could redirect to.
     *
     * @return array
     * @author Rico Celis
     * @access private
     */
    private function getPublishedPages() {
        $args = array(
            'sort_order' => 'ASC',
            'sort_column' => 'post_title',
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
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        // iterate through all pages to remove ones that are not usable
        foreach ($pages as $key => $value) {
            if ($value->post_status == 'private' || $value->post_status == 'trash') {
                unset($pages[$key]);
            }
        }

        $query = new WP_Query(array('post_type' => 'ib-landing-page', 'post_status' => array('pending', 'draft', 'future', 'publish')));
        $result = array_merge($query->posts, $pages);
        return $result;
    }

    private function loadContactForms() {
        $data['post_type'] = $this->post_type;
        // check wizzard status
        if (isset($_GET['wizzard'])) {
            $path = self::VIEW_PATH . "contact-form_wizzard_instructions";
            echo $this->load->view($path, array(), "blank");
        }
        $data['cfs'] = ContactPost::type($this->post_type)->status()->get();
        $data['Date'] = new DateHelper;
        #breadcrumbs;
        $this->Breadcrumb->add("Contact Form Management");
        $data['Breadcrumb'] = $this->Breadcrumb;
        echo $this->load->view(self::VIEW_PATH . 'contact-form-list', $data);
    }

    private function loadFormSettings() {
        $data = array();
        $args = array(
            'sort_order' => 'ASC',
            'sort_column' => 'post_title',
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
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        // iterate through all pages to remove ones that are not usable
        foreach ($pages as $key => $value) {
            if ($value->post_status == 'private' || $value->post_status == 'trash') {
                unset($pages[$key]);
            }
        }

        $query = new WP_Query(array('post_type' => 'ib-landing-page', 'post_status' => array('pending', 'draft', 'future', 'publish')));
        $result = array_merge($query->posts, $pages);

        $data['pages'] = $result;
        $this->Breadcrumb->add("Contact Form Options");
        $data['Breadcrumb'] = $this->Breadcrumb;
        $config = @json_decode(get_option('ib_form_submit'));
        if (!$config) {
            $config = new \stdClass();
            $config->submit_type = 'ajax';
            $config->ajax_content = "Thank you";
            update_option('ib_form_submit', $config);
        }
        //print_debug($config,true);
        $data['config'] = $config;
        echo $this->load->view(self::VIEW_PATH . 'contact-form-settings', $data);
    }

    public function saveConfigSettings() {
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'ib_contact_form_settings'))
            die('Busted!');
        $config = json_encode($_POST);
        update_option('ib_form_submit', $config);
        $this->_confirm("Your changes have been saved", true);
        header('Location:' . $_POST['_wp_http_referer']);
    }

    /**
     *
     */
    public function addPublicScripts() {
        wp_enqueue_style('ib-cf-css', BREW_MODULES_URL . 'Contact/assets/css/forms.css', array(), BREW_ASSET_VERSION);
        wp_enqueue_script('ib-cf-validator', BREW_MODULES_URL . 'Core/assets/js/ib-form-validation.js', array('jquery'), BREW_ASSET_VERSION);
        wp_enqueue_script('ib-cf-handle', BREW_MODULES_URL . 'Contact/assets/js/form-ajax.js', array('ib-cf-validator', 'ib-core-js'), BREW_ASSET_VERSION, true);
        // in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
        wp_enqueue_script('ib-country-dropdown-js', BREW_MODULES_URL . 'Core/assets/js/ib-country-dropdown.js', array('jquery'), BREW_ASSET_VERSION, true);
        wp_localize_script(
                'ib-cf-handle', 'ibCfAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'ibCfNonce' => wp_create_nonce('ib-cf-nonce'),
                )
        );
        wp_localize_script(
                'ib-country-dropdown-js', 'ibLocals', array(
            'ibCountry' => json_encode(Country::orderBy('country_name')->get()),
            'ibState' => State::all()->toArray()
                )
        );
    }

    /**
     *
     */
    public function addAdminScripts() {
        global $post;

        if (isset($_GET['page']) && $_GET['page'] == "ib-contact-forms"){ 
            wp_register_script('ib-form-list', BREW_MODULES_URL . 'Contact/assets/js/ib_form_lists.jquery.js', array(), BREW_ASSET_VERSION);
            wp_enqueue_script('ib-form-list');
        }
        wp_enqueue_style('ib-cf-css', BREW_MODULES_URL . 'Contact/assets/css/forms.css', array(), BREW_ASSET_VERSION);
        if (get_post_type() != "ib-landing-page") {
            wp_enqueue_media();
            // register required js
            
            wp_register_script('ib-form-builder-js', BREW_MODULES_URL . 'Contact/assets/js/form-builder.js', array(), BREW_ASSET_VERSION);
            wp_register_script('ib-cf-js', BREW_MODULES_URL . 'Contact/assets/js/forms.js', array('ib-form-builder-js'), BREW_ASSET_VERSION);

            // enqueue the scripts
            wp_enqueue_script('ib-form-builder-js');
            wp_enqueue_script('ib-cf-js');
        }
        wp_localize_script(
                'ib-cf-js', 'ibCfShortCode', array('Codes' => ContactPost::where('post_type', $this->post_type)->status()->get()->toArray())
        );

         if (isset($_GET['page']) && $_GET['page'] == 'ib-contact-forms') {
            wp_enqueue_script('ib-forms-main-shepherd', BREW_MODULES_URL.'Contact/assets/js/ib-forms-main-shepherd.js', array(), BREW_ASSET_VERSION);
        }
    }

    /**
     *
     */
    public function registerPostType() {
        $this->labels = array(
            'name' => 'Contact Forms',
            'singular_name' => 'Contact Form',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Contact Form',
            'edit_item' => 'Edit Contact Form',
            'new_item' => 'New Contact Form',
            'view_item' => 'View Contact Form',
            'search_items' => 'Search Contact Forms',
            'not_found' => 'Nothing found',
            'not_found_in_trash' => 'Nothing found in Trash',
            'parent_item_colon' => ''
        );

        $args = array(
            'labels' => $this->labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'query_var' => true,
            'menu_icon' => '',
            'rewrite' => array("slug" => "inboundbrew-cf"),
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => null,
            'show_in_nav_menus' => false,
            'show_in_menu' => false,
            'supports' => array('title')
        );

        register_post_type($this->post_type, $args);
    }

    /**
     *
     */
    public function receiveInput() {
        // if the submit button is clicked, send the email
        $nonce = $_POST['nonce'];
        header('Content-Type: application/json');
        // check to see if the submitted nonce matches with the
        // generated nonce we created earlier
        if (!wp_verify_nonce($nonce, 'ib-cf-nonce'))
            die('Busted!');

        if (@$_POST['captcha']) {
            if (!$this->cpa->validate($_POST['captcha'])) {
                $result['type'] = "0";
                $result['message'] = 'captcha did not match';
                die(json_encode($result));
            }
        }

        // save lead data
        $obj = LeadDataMap::mapData($_POST);



        $form = get_post($_POST['id']);
        $history_event = "Form submission";
        $history_note = 'Filled out contact form <a href="' . $_SERVER['HTTP_REFERER'] . '" target="_blank">' . $form->post_title . '</a>';
        $history_type = BREW_LEAD_HISTORY_TYPE_FORM_SUBMISSION;
        $lead = Lead::withTrashed()->email($obj->lead->lead_email)->first();
        if (!$lead) {
            $lead = new Lead();
            $lead->type_id = 1;
            $lead->lead_ip = get_ip();
            $history_event = "Lead Created";
            $history_note = 'Lead was created using contact form <a href="' . $_SERVER['HTTP_REFERER'] . '" target="_blank">' . $form->post_title . '</a>';
            $history_type = BREW_LEAD_HISTORY_TYPE_CREATED;
        } else {
            if ($lead->deleted_at) {
                $lead->restore();
                $h = new LeadHistory();
                $h->history_event = "Lead Restored";
                $h->history_type = BREW_LEAD_HISTORY_TYPE_RESTORED;
                $h->history_note = "Lead restored because of new form submission";
                $h->lead_id = $lead->lead_id;
                $h->save();
            }
        }
        foreach ($obj->lead as $key => $value) {
            $lead->$key = $value;
        }
        $lead->save();


        if (isset($obj->custom)) {
            foreach ($obj->custom as $value => $attribute) {
                $lead_data = new LeadData();
                $lead_data->lead_id = $lead->lead_id;
                $lead_data->data_term = $attribute;
                $lead_data->data_value = $value;
                $lead_data->save();
            }
        }
        // add to lead history
        $h = new LeadHistory();
        $h->history_event = $history_event;
        $h->history_type = $history_type;
        $h->history_note = $history_note;
        $h->lead_id = $lead->lead_id;
        $h->save();
        // result object
        $result = array('redirect' => 0, 'download' => "");
        // send the email and download content
        $is_landing_page = false;
        if (isset($_POST['page']) && is_numeric($_POST['page'])) {
            $is_landing_page = true;
            $metaObj = get_post_meta($_POST['page']);
        } else {
            $metaObj = get_post_meta($_POST['id']);
        }
        // check for file download
        if (@$metaObj['download_content'][0]) {
            $download_url = $this->createDownloadLink($metaObj, $lead);
        }
        // loop through all selected templates and send it.
        $emails = $metaObj['email_template'][0];
        $emailIds = explode("|", $emails);
        if (!empty($emailIds) && !@$metaObj['dont_send_email_template'][0]) {
            foreach ($emailIds as $email_id) {
                $email = Email::find($email_id);
                $this->handleContactFormEmailTemplate($email, $_POST, $metaObj, $lead, @$download_url);
            }
        } else {
            // not sending email but has download
            if (@$download_url) {
                $result['download'] = $download_url;
            }
        }
        // default config values
        $config = @json_decode(get_option('ib_form_submit'));
        // check if user needs a message or a redirect
        $option = (@$metaObj['thank_you_option'][0]) ? $metaObj['thank_you_option'][0] : "message";
        switch ($option) {
            case "message":
                if (@$metaObj['thank_you_message'][0]) {
                    $result['message'] = $metaObj['thank_you_message'][0];
                } else {
                    $result['message'] = isset($config->ajax_content) ? $config->ajax_content : 'Thank You';
                }
                break;
            case "redirect":
                $result['redirect'] = 1;
                $result['location'] = get_permalink($metaObj['thank_you_redirect'][0]);
                break;
        }
        do_action(BREW_ACTION_AFTER_FORM_SUBMIT, $_POST);

        die(json_encode($result));
    }

    /**
     * create download link
     *
     * @param array $meta  meta data for landing page
     * @param Eloquent Object $lead lead created/edited on form submit
     * @return string download url for for download
     * @access public
     * @author Rico Celis
     */
    public function createDownloadLink($meta, $lead) {
        $dl = new Download();
        //set the download url
        $dl->download_url = $meta['download_content'][0];
        // set the expiration date
        
        if (isset($meta['expires_after'][0]) && $meta['expires_after'][0] != '') {
            $dl->download_expire = date('Y-m-d H:i:s', strtotime("+{$meta['expires_after'][0]} day"));
        } else {
            $dl->download_expire = null;
        }
        
        // set the download limit
        if (isset($meta['download_limit'][0]) && $meta['download_limit'][0] != '') {
            $dl->download_limit = $meta['download_limit'][0];
        } else {
            $dl->download_limit = null;
        }
        //set the title
        if (isset($meta['download_title'][0])) {
            $dl->download_title = $meta['download_title'][0];
        } else {
            $dl->download_title = 'Download';
        }
        $dl->download_refer = $_SERVER['HTTP_REFERER'];
        //set alias
        $dl->download_alias = $alias = uniqid();
        // link to lead id
        $dl->lead_id = $lead->lead_id;
        $dl->save();
        $blog_url = get_site_url();
        $link = $blog_url . '/download-content/' . $alias;
        return $link;
    }

    /**
     * populate email template and send it
     * using post data and meta data from either landing page or contact form.
     *
     * @param Eloquent Object $email Email object
     * @param array $postData array of form data to replace keys on template content
     * @param array $meta landing page or contact form meta data
     * @param Eloquent Object $lead lead created/edited on form submit
     * @param string $download download code for download link
     * @return boolean true when done.
     *
     * @author Rico Celis
     * @access private
     */
    private function handleContactFormEmailTemplate($email, $postData, $meta, $lead, $download = null) {
        add_action('phpmailer_init', array('InboundBrew\Modules\Contact\Controllers\Email', 'phpMailerInit'));
        $options = json_decode(get_option('ib_email_settings'));
        $postData['default'] = $options;
        //pass the data into the template to replace the tokens
        foreach ($postData as $key => $value) {
            if (is_array($value))
                $value = implode(", ", $value);
            if (is_object($value))
                continue;
            if ($email) {
                $email->email_value = str_ireplace('{{' . $key . '}}', $value, $email->email_value);
                $email->email_subject = str_ireplace('{{' . $key . '}}', $value, $email->email_subject);
            }
        }
        if (!$email) {
            return true;
        }
        // the email template has a download link.
        if ($email->email_download_link && $download) {
            // update the link token with the virtual page and alias
            $email->email_value = str_ireplace('{{download_link}}', $download, $email->email_value);
        }
        // remove any other tokens
        $email->email_value = preg_replace('/\{\{(.*?)\}\}/', "", $email->email_value);
        $email->email_subject = preg_replace('/\{\{(.*?)\}\}/', "", $email->email_subject);
        // send settings
        $send_setings = json_decode(get_option('ib_smtp_options'));
        $headers = array();
        // load data for tempalte
        $template_id = $email->email_template_id;
        $template = EmailTemplate::find($template_id);
        $settings = new SettingsModel;
        $data = array(
            'template_data' => unserialize($template->settings),
            'FontAwesome' => new FontAwesomeHelper,
            'settings' => $settings->loadSettings()
        );
        // if html
        if (@$send_setings->mail_content_type == 'html') {
            $wrap = $this->load->view(self::VIEW_PATH . 'email/template', $data, "blank");
            $message = str_ireplace('{{template_content}}', $email->email_value, $wrap);
            $headers = array('Content-Type: text/html; charset=UTF-8');
        } else {
            $message = $email->email_value;
        }
        // send to email addresses.
        $to = (empty($email->send_to)) ? $postData['email'] : str_replace("{{email}}", $postData['email'], $email->send_to);
        $sendToArray = explode(",", $to);
        $send_to = array();
        foreach ($sendToArray as $email_address) {
            $email_address = trim(rtrim($email_address));
            if (!empty($email_address))
                $send_to[] = $email_address;
        }
        // check bc adn bcc
        if (!empty($email->send_cc))
            $headers[] = "Cc:" . $email->send_cc;
        if (!empty($email->send_cc))
            $headers[] = "Bcc:" . $email->send_bcc;
        return wp_mail($send_to, stripslashes($email->email_subject), $message, $headers);
    }

    /**
     *
     */
    public function addMetaBox() {
        $this->setData();
        add_meta_box(
                'ib_cf_options_box', //id
                'Contact Form Settings', //title
                array($this, 'setFormMetaBoxContent'), // callback
                $this->post_type, //this post type only
                'normal', // context placement
                'high' // where in the context
                // $callback_args
        );
    }

    /**
     *
     */
    public function setFormMetaBoxContent() {
        wp_nonce_field(basename(__FILE__), 'ib_cf_meta_box_nonce');
        echo $this->load->view(self::VIEW_PATH . 'contact-form', $this->data);
    }

    public function setEmailMetaBox() {
        echo $this->load->view(self::VIEW_PATH . 'partials/template-list', $this->data);
    }

    public function setDownloadMetaBox() {
        echo $this->load->view(self::VIEW_PATH . 'partials/download-content', $this->data);
    }

    /**
     * @param $post_id
     */
    public function saveMetaBox($post_id) {

        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = ( isset($_POST['ib_cf_meta_box_nonce']) && wp_verify_nonce($_POST['ib_cf_meta_box_nonce'], basename(__FILE__)) ) ? 'true' : 'false';

        // Exits script depending on save status
        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            die('sorry!');
        }
        if (@$_POST['dont_send_email_template']) {
            $_POST['email_template'] = array(); //reset email template selection.
        }
        if (!isset($_POST['email_template'])) {
            $_POST['email_template'] = array();
        } else if (!is_array($_POST['email_template'])) {
            $_POST['email_template'] = array($_POST['email_template']);
        }

        $_POST['email_template'] = implode("|", @$_POST['email_template']);
        // Checks for input and sanitizes/saves if needed
        foreach ($_POST as $key => $value) {
            if (!empty($value) && $key != 'leadfield') {
                if ($key == 'post_content') {
                    update_post_meta($post_id, $key, stripslashes($value));
                } else {
                    update_post_meta($post_id, $key, sanitize_text_field($value));
                }
            }
        }

        $post = ContactPost::find($post_id);
        $post->fields()->detach();
        if (isset($_POST['leadfield']))
            $post->fields()->attach($_POST['leadfield']);

        remove_action('save_post_' . $this->post_type, array($this, 'saveMetaBox'));

        $postContent = $this->wrapForm($post_id, @$_POST['post_content']);
        $my_post = array(
            'ID' => $post_id,
            'post_content' => $postContent,
        );

        // save wizzard step
        $Settings = new SettingsModel;
        $Settings->wizzardStepCompleted("contact_forms");

        // Update the post into the database
        wp_update_post($my_post);

        add_action('save_post_' . $this->post_type, array($this, 'saveMetaBox'));
    }

    /**
     * Sets all the required data for metaboxes/views
     *
     * @param int $post_id id for WP post
     */
    public function setData($post_id = 0) {
        if ($post_id) {
            $meta = get_post_custom($post_id);
            foreach ($meta as $key => $value) {
                $this->data[$key] = $value[0];
            }
        }
        $this->data['email_template'] = (@$this->data['email_template']) ? explode("|", $this->data['email_template']) : "";
        $this->data['id'] = $post_id;
        $this->data['form_fields'] = ContactPost::find($post_id);
        $this->data['lead_fields'] = FormField::where('field_custom', 0)->get();
        $this->data['custom_fields'] = FormField::where('field_custom', 1)->get();

        //Countries have some funky encoding - need to utf8 them or the json_encode fails
        $countries = Country::orderBy('country_name')->get()->toArray();
        FormHelper::utf8_encode_deep($countries);
        $this->data['countries'] = json_encode($countries);

        $this->data['states'] = json_encode(State::all()->toArray());
        $this->data['templates'] = Email::where('email_value', '!=', '')->get();
        $this->data['published_pages'] = $this->getPublishedPages();
    }

    /**
     *
     */
    public function clonePost() {
        global $wpdb;
        if (!( isset($_GET['post']) || isset($_POST['post']) || ( isset($_REQUEST['action']) && 'clone_form_as_draft' == $_REQUEST['action'] ) )) {
            die('No post to duplicate has been supplied!');
        }

        /*
         * get the original post id
         */
        $post_id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);

        //print_debug($contact_post,true);
        /*
         * and all the original post data then
         */
        $post = get_post($post_id);

        /*
         * if you don't want current user to be the new post author,
         * then change next couple of lines to this: $new_post_author = $post->post_author;
         */
        $current_user = wp_get_current_user();
        $new_post_author = $current_user->ID;

        /*
         * if post data exists, create the post duplicate
         */
        if (isset($post) && $post != null) {

            /*
             * new post data array
             */
            $args = array(
                'comment_status' => $post->comment_status,
                'ping_status' => $post->ping_status,
                'post_author' => $new_post_author,
                'post_content' => $post->post_content,
                'post_excerpt' => $post->post_excerpt,
                'post_name' => $post->post_name,
                'post_parent' => $post->post_parent,
                'post_password' => $post->post_password,
                'post_status' => 'draft',
                'post_title' => 'Clone - ' . $post->post_title,
                'post_type' => $post->post_type,
                'to_ping' => $post->to_ping,
                'menu_order' => $post->menu_order
            );

            /*
             * insert the post
             * would use wp_insert_post() function
             * however it kills html
             */
            $wpdb->insert('wp_posts', $args);
            $new_post_id = $wpdb->insert_id;

            $contact_post = ContactPost::find($post_id);
            foreach ($contact_post->fields as $cp) {
                $field_arr[] = $cp->field_id;
            }

            $new_post = ContactPost::find($new_post_id);
            $new_post->fields()->attach($field_arr);

            /*
             * get all current post terms ad set them to the new post draft
             */
            $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
            foreach ($taxonomies as $taxonomy) {
                $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
                wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
            }

            /*
             * duplicate all post meta
             */
            $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
            if (count($post_meta_infos) != 0) {
                $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
                foreach ($post_meta_infos as $meta_info) {
                    $meta_key = $meta_info->meta_key;

                    $meta_value = addslashes($meta_info->meta_value);
                    $sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
                }
                $sql_query .= implode(" UNION ALL ", $sql_query_sel);
                $wpdb->query($sql_query);
            }


            /*
             * finally, redirect to the edit post screen for the new draft
             */
            wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
            exit;
        } else {
            wp_die('Post creation failed, could not find original post: ' . $post_id);
        }
    }

    private function wrapForm($id, $postContent) {
        $form = '<form method="post" action="" class="ib-contact-form-ajax">';
        $form .= '<input type="hidden" name="action" value="send_ib_contact">';
        $form .= '<input type="hidden" name="id" value="' . $id . '">';
        $form .= $postContent;
        $form .= '</form>';
        $form .= '<div class="ib-ajax-response"></div>';

        return $form;
    }

    public function contactFormFilter($content) {
        if ($GLOBALS['post']->post_type == $this->post_type) {
            if (preg_match('/{operand1}/', $content)) {
                $this->cpa->resetCaptcha();
                $patterns = array('/{operand1}/', '/{operator}/', '/{operand2}/');
                $replacements = array($this->cpa->operand1, $this->cpa->operator, $this->cpa->operand2);
                $content = preg_replace($patterns, $replacements, $content);
            }
        }
        return $content;
    }

    public function loadFieldTypes() {
        $data['context'] = $_POST['context'];
        $result = $this->load->view(self::VIEW_PATH . 'partials/field-types', $data);
        header('Content-Type: text/html');
        die($result);
    }

    public function loadFieldOptions() {
        $data['context'] = $_POST['context'];
        $data['name'] = $_POST['name'];
        $data['token'] = $_POST['token'];
        if ($form = FormField::where('field_token', $_POST['token'])->first()) {
            $data['options'] = $form->field_value;
            $data['field_id'] = $form->field_id;
        }

        $result = $this->load->view(self::VIEW_PATH . 'partials/field-options', $data);
        if ($result == "") {
            //this should never happen anymore
            http_response_code(500);
            exit;
        }
        header('Content-Type: text/html');
        die($result);
    }

    public function attachPostFormField() {
        $v = new Validator($_POST);
        $v->rule('required', array('post_id', 'field_id'));
        $v->rule('numeric', array('post_id', 'field_id'));
        if ($v->validate()) {
            try {
                $post = ContactPost::find($_POST['post_id']);
                $post->fields()->attach($_POST['field_id']);
            } catch (\Exception $e) {
                http_response_code(400);
                $result['message'] = $e->getMessage();
            }
        } else {
            http_response_code(400);
            $result['message'] = $v->errors();
        }
        die(json_encode($result));
    }

    public function detachPostFormField() {
        $v = new Validator($_POST);
        $v->rule('required', array('post_id', 'field_id'));
        $v->rule('numeric', array('post_id', 'field_id'));
        if ($v->validate()) {
            try {
                $post = ContactPost::find($_POST['post_id']);
                $post->fields()->detach($_POST['field_id']);
            } catch (\Exception $e) {
                http_response_code(400);
                $result['message'] = $e->getMessage();
            }
        } else {
            http_response_code(400);
            $result['message'] = $v->errors();
        }
    }

    public function saveLeadField() {
        $v = new Validator($_POST);
        $v->rule('required', array('name', 'context'));
        if ($v->validate()) {
            try {
                $form = new FormField;
                $form->field_name = $result['name'] = $_POST['name'];

                $existingFormField = FormField::ofName($form->field_name)->first();
                if ($existingFormField) {
                    http_response_code(400);
                    $result['message'] = "The Field Name Already Exists";
                    die(json_encode($result));
                }

                $form->field_type = $result['context'] = $_POST['context'];
                $form->field_token = $result['token'] = strtolower(preg_replace("/[^a-zA-Z0-9]/", "_", $_POST['name']));
                $form->field_custom = 1;
                if (isset($_POST['options'])) {
                    $form->field_value = $result['options'] = $_POST['options'];
                } else {
                    $result['options'] = "";
                }
                $result['options'] = nl2br($result['options']);
                $form->save();
                $result['field_id'] = $form->field_id;
                Settings::mapFormLeadData();
            } catch (\Exception $e) {
                http_response_code(400);
                $result['message'] = $e->getMessage();
            }
        } else {
            http_response_code(400);
            $result['message'] = $v->errors();
        }
        die(json_encode($result));
    }

    /**
     * update settings for a custom lead field
     *
     * @return lead field updated data
     * @access public
     * @author Rico Celis
     */
    public function editLeadField() {
        $formField = FormField::find($_POST['lead_field_id']);
        $response = array(
            'success' => false,
            'message' => "Unable to update custom lead field. Please Try again.",
            'field' => array()
        );
        if (@$formField->field_id) {
            $result = array();
            $formField->field_name = $result['field'] = $_POST['name'];
            if (isset($_POST['options'])) {
                $formField->field_value = $result['options'] = $_POST['options'];
            } else {
                $result['options'] = "";
            }
            $response['success'] = true;
            $result['options'] = nl2br($result['options']);
            $result['field_id'] = $formField->field_id;
            $formField->save();
            $response['field'] = $result;
        }
        die(json_encode($response));
    }

    /**
     * check that a lead field can be deleted
     * it can't be linked to an email template or a contact form
     *
     * @return array
      can_delete: true or false
      response: html with list of email templates and/or contact forms
     * @access public
     * @author Rico Celis
     */
    public function verifyDeleteLeadField() {
        $field_id = $_POST['field_id'];
        $field = FormField::find($field_id);
        $response = array(
            'can_delete' => false,
            'response' => "Invalid Field Id. Please try again"
        );
        if (@$field->field_id) {
            // linked to email templates.
            $email_linkage = EmailField::where("field_id", $field_id)->get()->toArray();
            if (!empty($email_linkage)) {
                $email_templates = array();
                foreach ($email_linkage as $linkage) {
                    $template = Email::find($linkage['email_id']);
                    $email_templates[] = $template;
                }
                $data['email_templates'] = $email_templates;
            }
            // linked to contact forms
            $form_linkage = ContactField::where("field_id", $field_id)->get()->toArray();
            if (!empty($form_linkage)) {
                $contact_forms = array();
                foreach ($form_linkage as $linkage) {
                    $form = ContactPost::find($linkage['post_id']);
                    $contact_forms[] = $form;
                }
                $data['contact_forms'] = $contact_forms;
            }
            if (!empty($contact_forms) || !empty($email_linkage)) {
                $response['response'] = $this->load->view(self::VIEW_PATH . 'partials/cant_delete_custom_field', $data);
            } else {
                $response = array('can_delete' => true);
            }
        }
        die(json_encode($response));
    }

    /**
     * delete custom lead field
     */
    public function deleteLeadField() {
        $response = array(
            'success' => false,
            'message' => "Invalid field. Please try again.");
        $field_id = $_POST['field_id'];
        $field = FormField::find($field_id);
        if (@$field->field_id) {
            $field->delete();
            $response['success'] = true;
        }
        die(json_encode($response));
    }

}
