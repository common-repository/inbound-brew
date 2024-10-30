<?php

/**
 * Created by PhpStorm.
 * User: sean
 * Date: 10/21/15
 * Time: 1:59 PM
 */

namespace InboundBrew\Modules\Contact\Controllers;

use InboundBrew\Modules\Core\AppController;
use InboundBrew\Modules\Core\Models\FormField;
use InboundBrew\Modules\Contact\Models\Email as EmailModel;
use InboundBrew\Modules\Contact\Models\EmailTemplate;
use InboundBrew\Modules\Settings\Models\SettingsModel;
// helpers
use Valitron\Validator;
use InboundBrew\Libraries\LayoutHelper;
use InboundBrew\Libraries\FormHelper;
use InboundBrew\Libraries\DateHelper;
use InboundBrew\Libraries\FontAwesomeHelper;

/**
 * Class Email
 * @package InboundBrew\Modules\Contact\Controllers
 */
class Email extends AppController {

    /**
     *
     */
    const VIEW_PATH = 'Contact/views/';

    var $post_type = "ib-email-admin";

    /**
     *
     */
    function __construct() {
        parent::init();
        $this->init();
        $this->partials_path = BREW_MODULES_PATH . "Contact/views/partials/";
    }

    
       

    /**
     *
     */
    public function init() {
        add_action('admin_enqueue_scripts', array($this, 'addAdminScripts'));
        //add_action('phpmailer_init',array($this,'phpMailerInit'));
        //add_action('admin_head', array($this,'tinymce_addEmailsButtons'));
        // ajax hooks
        add_action('wp_ajax_ib_get_email_template_preview', array($this, 'getEmailTemplatePreview'));
        add_action("wp_ajax_ib_send_test_email", array($this, 'sendTestEmail'));
        // post hooks
        add_action('admin_post_ib_save_email_template', array($this, 'saveEmailTemplate'));
        add_action('admin_post_ib_save_email', array($this, 'saveEmail'));
        add_action("admin_post_ib_save_custom_email", array($this, 'saveCustomEmail'));
        add_action("admin_post_ib_send_test_email", array($this, 'sendTestEmail'));
        //
        if (@$_GET['action'] == "ib_preview_email")
            add_action('admin_init', array($this, "previewEmailTemplate"), 1);
        add_filter("mce_external_plugins", array($this, 'tinymce_addButtonPlugin'));
        add_filter('mce_buttons_3', array($this, 'tinymce_registerButtons'));
        add_action('wp_ajax_ib_verify_email_title', array($this, 'verifyEmailTitle'));
        add_action( 'wp_mail_failed', array($this, 'catchMailError'), 10, 1 );
    }

    public function catchMailError( $wp_error ) {
        
        $_SESSION['wp_mail_error'] = $this->wpMailErrorToReadable($wp_error->errors['wp_mail_failed'][0]);
    }


    public function wpMailErrorToReadable($error){
      
      if (stripos($error, 'SMTP connect() failed') !== false){
        return "Failure Connecting to SMTP. Check your SMTP Host and Port Settings <a href='".admin_url('admin.php?page=ib-admin-settings&section=ib_email_settings')."'>here</a>.";
      }
      else if (stripos($error, 'SMTP Error: Could not authenticate.') !== false){
        return "SMTP Authentication Failed. Check your Email and Password Settings <a href='".admin_url('admin.php?page=ib-admin-settings&section=ib_email_settings')."'>here</a>.";
      }
      else if (stripos($error, 'The following From address failed') !== false){
        return "The \"From Address\" was invalid. Check your Settings <a href='".admin_url('admin.php?page=ib-admin-settings&section=ib_email_settings')."'>here</a>.";
      }

      
      

      return "Unkown email send error.";
      
    }


    /**
     *
     */
    public function addAdminScripts() {
        if (@$_GET['page'] == 'ib-email-admin' || @$_GET['page'] == 'ib-leads-admin') {
            wp_enqueue_media();
            wp_enqueue_script('tinymce');
            if (isset($_GET['page']) && $_GET['page'] == 'ib-email-admin') {
                wp_enqueue_style('ib-cta-css', BREW_MODULES_URL . 'CTA/assets/css/cta.css', array(), BREW_ASSET_VERSION);
                wp_enqueue_style('colorpicker-css', BREW_MODULES_URL . 'Core/assets/third-party/colorpicker/css/colpick.css', array(), BREW_ASSET_VERSION);
                wp_enqueue_script('colorpicker-js', BREW_MODULES_URL . 'Core/assets/third-party/colorpicker/js/colpick.js', array(), BREW_ASSET_VERSION);
                wp_enqueue_script('ib-emails-lists', BREW_MODULES_URL . 'Contact/assets/js/ib_email_lists.jquery.js', array('jquery'), BREW_ASSET_VERSION);
                wp_enqueue_script('ib-email-editor', BREW_MODULES_URL . 'Contact/assets/js/ib_email_editor.jquery.js', array('jquery'), BREW_ASSET_VERSION);
                wp_enqueue_script('ib-cta-tabs-js', BREW_MODULES_URL . 'CTA/assets/js/ib_cta_tabs.jquery.js', array(), BREW_ASSET_VERSION);
                wp_enqueue_script('ib-email-shehperd-js', BREW_MODULES_URL . 'Contact/assets/js/ib-email-shepherd.js', array(), BREW_ASSET_VERSION);
            }
            wp_enqueue_script('ib-email-tokens', BREW_MODULES_URL . 'Contact/assets/js/email-tokens.jquery.js', array(), BREW_ASSET_VERSION);
            $FormField = new FormField;
            wp_localize_script(
                    'ib-email-tokens', 'ibEmailAjax', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'ibEmailNonce' => wp_create_nonce('ib-email-nonce'),
                'Codes' => FormField::all()->toArray()
                    )
            );
        }
    }

    /* tinymce plugin */
    /*    function tinymce_addEmailsButtons() {
      // check user permissions
      if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
      return;
      }
      add_filter("mce_external_plugins", array($this,'tinymce_addButtonPlugin'));
      add_filter('mce_buttons_3', array($this, 'tinymce_registerButtons'));

      } */

    function tinymce_addButtonPlugin($plugin_array) {
        $plugin_array['ib_email_token_buttons'] = BREW_MODULES_URL . 'Contact/assets/js/email-tokens.tinymce.js';
        return $plugin_array;
    }

    function tinymce_registerButtons($buttons) {
        array_push($buttons, "ib_email_cf_token_button");
        if (@$_GET['page'] != "ib-leads-admin")
            array_push($buttons, "ib_download_link_token_button");
        return $buttons;
    }

    /**
     * Emails module admin
     *
     * @author Rico Celis
     * @access Public
     */
    public function loadAdmin() {
        $section = @$_GET['section'];
        if (!$section)
            $section = "emails_list";
        switch ($section) {
            // emails
            case "emails_list":
                $this->emailsList();
                break;
            case "add_email":
                $this->addEmail(@$_GET['template_id']);
                break;
            case "edit_email":
                $this->editEmail(@$_GET['email_id']);
                break;
            case "delete_email":
                $this->deleteEmail($_GET['email_id']);
                break;
            case "clone_email":
                $this->cloneEmail($_GET['email_id']);
                break;
            case "choose_email_type":
                $this->chooseEmailType();
                break;
            // templates list
            case "templates_list":
                $this->templatesList();
                break;
            case "add_template":
                $this->addTemplate();
                break;
            case "edit_template":
                $this->editTemplate($_GET['template_id']);
                break;
            case "clone_template":
                $this->cloneTemplate($_GET['template_id']);
                break;
            case "delete_template":
                $this->deleteTemplate($_GET['template_id']);
                break;
            case "choose_template":
                $this->chooseTemplate();
                break;
        }
    }

    /*
     * show available emails
     *
      @ author Rico Celis
      @ access private
     */

    private function emailsList() {
        // check wizzard status
        if (isset($_GET['wizzard'])) {
            $path = self::VIEW_PATH . "email_wizzard_instructions";
            echo $this->load->view($path, array(), "blank");
        }
        #breadcrumbs;
        $this->Breadcrumb->add("Emails");
        $data['Layout'] = new LayoutHelper;
        $data['Date'] = new DateHelper;
        $data['Breadcrumb'] = $this->Breadcrumb;
        $data['emails'] = EmailModel::all();
        $data['post_type'] = $this->post_type;
        $data['partials_path'] = $this->partials_path;
        $data['FontAwesome'] = new FontAwesomeHelper;
        // load view
        echo $this->load->view(self::VIEW_PATH . 'admin_emails', $data);
    }

    /*
     * show available templates
     *
      @ author Rico Celis
      @ access private
     */

    private function templatesList() {
        // check wizzard status
        if (isset($_GET['wizzard'])) {
            $path = self::VIEW_PATH . "email_wizzard_instructions";
            echo $this->load->view($path, array(), "blank");
        }
        #breadcrumbs;
        $this->Breadcrumb->add("Email Templates");
        $data['Layout'] = new LayoutHelper;
        $data['Date'] = new DateHelper;
        $data['Breadcrumb'] = $this->Breadcrumb;
        $data['templates'] = EmailTemplate::all();
        $data['post_type'] = $this->post_type;
        $data['partials_path'] = $this->partials_path;
        // load view
        echo $this->load->view(self::VIEW_PATH . 'admin_templates', $data);
    }

    /*
     * Create new email template
     *
      @author Rico Celis
      @access Public
     */

    public function addTemplate() {
        // load view
        $this->Breadcrumb->add("Emails", "admin.php?page={$this->post_type}");
        $this->Breadcrumb->add("New Template");
        $data['Breadcrumb'] = $this->Breadcrumb;
        $Form = new FormHelper;
        $default = EmailTemplate::defaultTemplateValues();
        $user = (function_exists("wp_get_current_user")) ? wp_get_current_user() : get_currentuserinfo();
        $Form->data = array(
            'send_test_to' => $user->user_email,
            'Template' => $default);
        $data['Form'] = $Form;
        $data['Layout'] = new LayoutHelper();
        $data['partials_path'] = $this->partials_path;
        $data['save_action'] = "ib_save_email_template";
        $data['post_type'] = $this->post_type;
        echo $this->load->view(self::VIEW_PATH . 'email_editor', $data);
    }

    /*
     * Choose email type to create
     *
     * @author Rico Celis
     * @access public
     */

    public function chooseEmailType() {
        $this->Breadcrumb->add("Emails", "admin.php?page={$this->post_type}&section=templates_list");
        $this->Breadcrumb->add("Choose Type");
        $data['post_type'] = $this->post_type;
        $data['Breadcrumb'] = $this->Breadcrumb;
        echo $this->load->view(self::VIEW_PATH . "admin_choose_email_type", $data);
    }

    /*
     * Choose a template to create an email from
     *
      @author Rico Celis
      @access Public
     */

    private function chooseTemplate() {
        #breadcrumbs;
        $this->Breadcrumb->add("Email Templates", "admin.php?page={$this->post_type}");
        $this->Breadcrumb->add("Choose Template");
        $data['Layout'] = new LayoutHelper;
        $data['Date'] = new DateHelper;
        $data['Breadcrumb'] = $this->Breadcrumb;
        $data['templates'] = EmailTemplate::all();
        $data['post_type'] = $this->post_type;
        $data['partials_path'] = $this->partials_path;
        // load view
        echo $this->load->view(self::VIEW_PATH . 'admin_choose_template', $data);
    }

    /*
     * Create new email template
     *
     * @param int $template_id id for template
     *
     * @author Rico Celis
     * @access Public
     */

    public function editTemplate($template_id) {
        // load view
        $this->Breadcrumb->add("Email Templates", "admin.php?page={$this->post_type}");
        $template = EmailTemplate::find($template_id);
        $this->Breadcrumb->add($template->name);
        $data['post_type'] = $this->post_type;
        $data['Breadcrumb'] = $this->Breadcrumb;
        $Form = new FormHelper;
        $settings = unserialize($template->settings);
        //$settings = EmailTemplate::defaultTemplateValues();
        $settings['info'] = array(
            'name' => $template->name,
            'description' => $template->description,
            'send_to' => $template->send_to,
            'send_cc' => $template->send_cc,
            'send_bcc' => $template->send_bcc
        );
        $user = (function_exists("wp_get_current_user")) ? wp_get_current_user() : get_currentuserinfo();
        $Form->data = array(
            'send_test_to' => $user->user_email,
            'Template' => $settings);
        $data['Form'] = $Form;
        $data['Layout'] = new LayoutHelper();
        $data['editor_type'] = "template";
        $data['partials_path'] = $this->partials_path;
        $data['email_template_id'] = $template_id;
        $data['save_action'] = "ib_save_email_template";
        echo $this->load->view(self::VIEW_PATH . 'email_editor', $data);
    }

    /*
     * delete email template
     *
     * @param int $template_id id for template
     *
     * @author Rico Celis
     * @access Public
     */

    private function deleteTemplate($template_id) {
        if (@isset($_GET['_wpnonce']) && wp_verify_nonce(@$_GET['_wpnonce'], 'ib_delete_template_nonce')) {
            $template = EmailTemplate::find($template_id);
            $template->delete();
            $this->_confirm("Your template has been deleted.", true);
            $this->jsRedirect("admin.php?page={$this->post_type}&section=templates_list");
        }
    }

    /*
     * clone an existing template.
     *
     * @param int $template_id id for template
     *
     * @author Rico Celis
     * @access Public
     */

    public function cloneTemplate($template_id) {
        // load view
        $this->Breadcrumb->add("Email Templates", "admin.php?page={$this->post_type}");
        $template = EmailTemplate::find($template_id);
        $this->Breadcrumb->add($template->name);
        $data['post_type'] = $this->post_type;
        $data['Breadcrumb'] = $this->Breadcrumb;
        $Form = new FormHelper;
        $settings = unserialize($template->settings);
        $settings['info'] = array(
            'name' => "Copy of " . $template->name,
            'description' => $template->description,
            'send_to' => $template->send_to,
            'send_cc' => $template->send_cc,
            'send_bcc' => $template->send_bcc
        );
        $user = (function_exists("wp_get_current_user")) ? wp_get_current_user() : get_currentuserinfo();
        $Form->data = array(
            'send_test_to' => $user->user_email,
            'Template' => $settings);
        $data['Form'] = $Form;
        $data['Layout'] = new LayoutHelper();
        $data['post_type'] = $this->post_type;
        $data['partials_path'] = $this->partials_path;
        $data['save_action'] = "ib_save_email_template";
        echo $this->load->view(self::VIEW_PATH . 'email_editor', $data);
    }

    /*
     * create a new email
     *
     * @param int $template_id id for template
     *
     * @author Rico Celis
     * @access Public
     */

    public function addEmail($template_id = null) {
        // load view
        $this->Breadcrumb->add("Emails", "admin.php?page={$this->post_type}");
        $newStr = "New Email";
        $email = array(
            'email_value' => EmailModel::defaultEmailCopy()
        );
        if ($template_id) {
            $newStr = "New Email From Template";
            $template = EmailTemplate::find($template_id);
            $settings = unserialize($template->settings);
            $email['send_to'] = $template->send_to;
            $email['send_cc'] = $template->send_cc;
            $email['send_bcc'] = $template->send_bcc;
            $data['email_template_id'] = $template_id;
        } else {
            $newStr = "New Custom Email";
            $settings = EmailTemplate::defaultTemplateValues();
            $email['send_to'] = "{{email}},";
        }
        $this->Breadcrumb->add($newStr);
        $data['editing'] = (@$_GET['email_type'] == "custom") ? "custom" : "email";
        $data['Breadcrumb'] = $this->Breadcrumb;
        $Form = new FormHelper;
        $user = (function_exists("wp_get_current_user")) ? wp_get_current_user() : get_currentuserinfo();
        $Form->data = array(
            'send_test_to' => $user->user_email,
            'Template' => $settings,
            'Email' => $email);
        $data['Form'] = $Form;
        $data['Layout'] = new LayoutHelper();
        $data['post_type'] = $this->post_type;
        $data['partials_path'] = $this->partials_path;
        $data['save_action'] = (@$_GET['email_type'] == "custom") ? "ib_save_custom_email" : "ib_save_email";
        echo $this->load->view(self::VIEW_PATH . 'email_editor', $data);
    }

    /*
     * edit an email
     *
     * @param int $email_id email record id
     *
     * @author Rico Celis
     * @access Public
     */

    public function editEmail($email_id) {
        // load view
        $email = EmailModel::find($email_id);
        $template_id = $email->email_template_id;
        $template = EmailTemplate::find($template_id);
        $emailData = $email->toArray();
        $settings = unserialize($template->settings);
        # breacrumbs
        $this->Breadcrumb->add("Emails", "admin.php?page={$this->post_type}");
        $this->Breadcrumb->add($email->email_title);
        $data['editing'] = "email";
        $data['Breadcrumb'] = $this->Breadcrumb;
        $Form = new FormHelper;
        $user = (function_exists("wp_get_current_user")) ? wp_get_current_user() : get_currentuserinfo();
        $Form->data = array(
            'send_test_to' => $user->user_email,
            'Template' => $settings,
            'Email' => $emailData);
        // helpers
        $data['Form'] = $Form;
        $data['Layout'] = new LayoutHelper();
        // view variables
        $data['email_id'] = $email->email_id;
        $data['post_type'] = $this->post_type;
        $data['partials_path'] = $this->partials_path;
        $data['save_action'] = "ib_save_email";
        $data['contact_forms'] = $email->contactForms(null, array('add_post_id' => false));
        // load view
        echo $this->load->view(self::VIEW_PATH . 'email_editor', $data);
    }

    /*
     * create a copy of an email
     *
     * @param int $email_id email record id
     *
     * @author Rico Celis
     * @access Public
     */

    public function cloneEmail($email_id) {
        // load view
        $email = EmailModel::find($email_id);
        $template_id = $email->email_template_id;
        $template = EmailTemplate::find($template_id);
        $emailData = $email->toArray();
        $emailData['email_title'] = "Copy of " . $emailData['email_title'];
        $settings = unserialize($template->settings);
        # breacrumbs
        $this->Breadcrumb->add("Emails", "admin.php?page={$this->post_type}");
        $this->Breadcrumb->add($email->email_title);
        $data['editing'] = "email";
        $data['Breadcrumb'] = $this->Breadcrumb;
        $Form = new FormHelper;
        $user = (function_exists("wp_get_current_user")) ? wp_get_current_user() : get_currentuserinfo();
        $Form->data = array(
            'send_test_to' => $user->user_email,
            'Template' => $settings,
            'Email' => $emailData);
        // helpers
        $data['Form'] = $Form;
        $data['Layout'] = new LayoutHelper();
        // view variables
        $data['email_template_id'] = $emailData['email_template_id'];
        $data['post_type'] = $this->post_type;
        $data['partials_path'] = $this->partials_path;
        $data['save_action'] = "ib_save_email";
        // load view
        echo $this->load->view(self::VIEW_PATH . 'email_editor', $data);
    }

    /*
     * delete email
     *
     * @param int $email_id id for email
     *
     * @author Rico Celis
     * @access Public
     */

    private function deleteEmail($email_id) {
        if (@isset($_GET['_wpnonce']) && wp_verify_nonce(@$_GET['_wpnonce'], 'ib_delete_email_nonce')) {
            $email = EmailModel::find($email_id);
            $email->deleteLinkedData();
            $email->delete();
            $this->_confirm("Your email has been deleted.", true);
            $this->jsRedirect("admin.php?page={$this->post_type}&section=emails_list");
        }
    }

    /*
     * Save email template settings.
     *
      @author Rico Celis
      @access Public
     */

    public function saveEmailTemplate() {
        if (@isset($_POST['_wpnonce']) && wp_verify_nonce(@$_POST['_wpnonce'], 'ib_save_email_nonce')) {
            $post = $_POST['data']['Template'];
            $template_id = @$post['info']['email_template_id'];
            if ($template_id) { // update template
                $template = EmailTemplate::find($template_id);
            } else { // new template
                $template = new EmailTemplate;
            }
            foreach ($post['info'] as $index => $value) {
                $template->$index = $value;
            }
            unset($post['info']);
            $template->settings = serialize($post);
            $template->save();
            // save email template
            $this->_confirm("Your template has been saved.", true);
            header("Location: admin.php?page=" . $this->post_type . "&section=templates_list");
        }
    }

    /*
     * Save email template settings.
     *
      @author Rico Celis
      @access Public
     */

    public function saveEmail() {
        if (@isset($_POST['_wpnonce']) && wp_verify_nonce(@$_POST['_wpnonce'], 'ib_save_email_nonce')) {
            $post = $_POST['data'];
            $emailData = $post['Email'];
            $templateData = $_POST['data']['Template'];
            $template_id = @$templateData['info']['email_template_id'];
            $email_id = @$emailData['email_id'];
            // creating custom email
            if (!$template_id && !$email_id) {
                // new template
                $template = new EmailTemplate;
                foreach ($templateData['info'] as $index => $value) {
                    $template->$index = $value;
                }
                unset($post['info']);
                $template->settings = serialize($post);
                $template->save();
                $template_id = $template->email_template_id;
            }
            if ($email_id) {
                $email = EmailModel::find($email_id);
            } else {
                $email = new EmailModel;
            }
            if ($template_id)
                $email->email_template_id = $template_id;
            $email->email_title = $emailData['email_title'];
            $email->email_subject = $emailData['email_subject'];
            $email->email_value = stripslashes($emailData['email_value']);
            $email->email_download_link = (strpos($emailData['email_value'], "{{download_link}}") === false) ? 0 : 1;
            $email->send_to = $emailData['send_to'];
            $email->send_cc = $emailData['send_cc'];
            $email->send_bcc = $emailData['send_bcc'];
            $email->save();
            // save email template
            $this->_confirm("Your template has been saved.", true);
            header("Location: admin.php?page=" . $this->post_type . "&section=emails_list");
        }
    }

    public function saveCustomEmail() {
        if (@isset($_POST['_wpnonce']) && wp_verify_nonce(@$_POST['_wpnonce'], 'ib_save_email_nonce')) {
            $post = $_POST['data'];
            $templateData = $post['Template'];
            $emailData = $post['Email'];
            // save template.
            $template = new EmailTemplate;
            $template->name = $emailData['email_title'] . " Template";
            $template->description = $templateData['info']['description'];
            $template->send_to = $emailData['send_to'];
            $template->send_cc = $emailData['send_cc'];
            $template->send_bcc = $emailData['send_bcc'];
            unset($templateData['info']);
            $template->settings = serialize($templateData);
            $template->save();
            // save email
            $email = new EmailModel;
            $email->email_template_id = $template->email_template_id;
            $email->email_title = $emailData['email_title'];
            $email->email_subject = $emailData['email_subject'];
            $email->email_value = stripslashes($emailData['email_value']);
            $email->email_download_link = (strpos($emailData['email_value'], "{{download_link}}") === false) ? 0 : 1;
            $email->send_to = $emailData['send_to'];
            $email->send_cc = $emailData['send_cc'];
            $email->send_bcc = $emailData['send_bcc'];
            $email->save();
            // save email template
            $this->_confirm("Your email has been saved.", true);
            header("Location: admin.php?page=" . $this->post_type . "&section=emails_list");
        }
    }

    /*
     * get initial email preview
     *
      @author Rico Celis
      @access Public
     */
    /* public function getEmailTemplatePreview(){
      //$options = json_decode(get_option('ib_email_settings'));
      //$data['default'] = $options;
      $data['is_preview'] = true;
      $result['template'] = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>'), array('', ''), $this->load->view(self::VIEW_PATH . 'email/preview',$data,"blank")));
      header('Content-Type: application/json');
      die(json_encode($result));
      } */

    public function getEmailTemplatePreview() {
        echo $this->load->view(self::VIEW_PATH . 'email/preview', array(), "blank");
        exit;
    }

    public static function phpMailerInit($phpmailer) {
        // Check that mailer is not blank, and if mailer=smtp, host is not blank
        if (!$options = json_decode(get_option('ib_smtp_options'))) {
            return;
        }
        

        // Set the mailer type as per config above, this overrides the already called isMail method
        $phpmailer->Mailer = $options->mailer;

        if (isset($options->mail_from_name)) {
            $phpmailer->FromName = $options->mail_from_name;
        }

        if (isset($options->mail_from))
            $phpmailer->From = $options->mail_from;

        // Set the Sender (return-path) if required
        if (@$options->mail_set_return_path)
            $phpmailer->Sender = $phpmailer->From;

        // Set the SMTPSecure value, if set to none, leave this blank
        $phpmailer->SMTPSecure = ($options->smtp_ssl == 'none') ? '' : $options->smtp_ssl;

        // If we're sending via SMTP, set the host
        if ($options->mailer == "smtp") {

            // Set the SMTPSecure value, if set to none, leave this blank
            $phpmailer->SMTPSecure = ($options->smtp_ssl == 'none') ? '' : $options->smtp_ssl;

            // Set the other options
            $phpmailer->Host = $options->smtp_host;
            $phpmailer->Port = $options->smtp_port;

            // If we're using smtp auth, set the username & password
            if ($options->smtp_auth) {
                $phpmailer->SMTPAuth = TRUE;
                $phpmailer->Username = $options->smtp_user;
                $phpmailer->Password = $options->smtp_pass;
            }
        }
        $phpmailer = apply_filters('wp_mail_smtp_custom_options', $phpmailer);
    }

    /*
     * send test email
     *
      @author Rico Celis
      @access Public
     */

    public function sendTestEmail() {
        add_action('phpmailer_init', array($this, 'phpMailerInit'));
        $result = array(
            'success' => false,
            'message' => "Unable to send test email. Check your email settings and try again",
            'title' => "Error!"
        );
        $nonce = @$_POST['nonce'];
        if ($nonce && wp_verify_nonce($nonce, 'ib-email-nonce')) {
            $post = $_POST['data'];
            // template and email data (FROM POST)
            $settings = new SettingsModel;
            $data = array(
                'template_data' => $post['Template'],
                'email_data' => @$post['Email'],
                'FontAwesome' => new FontAwesomeHelper,
                'settings' => $settings->loadSettings()
            );
            // change font awesome settings for data urls
            $message = $this->load->view(self::VIEW_PATH . 'email/template', $data, "blank");
            // send settings
            $send_setings = json_decode(get_option('ib_smtp_options'));
            $headers = array();
            if (@$send_setings->mail_content_type == 'html') {
                $headers = array('Content-Type: text/html; charset=UTF-8');
            }
            // send to email addresses.
            $sendToArray = explode(",", $post['send_test_to']);
            $send_to = array();
            foreach ($sendToArray as $email_address) {
                $email = trim(rtrim($email_address));
                if (!empty($email))
                    $send_to[] = $email;
            }
            $subject = "TEST: " . ((@$post['Email']) ? $post['Email']['email_subject'] : $post['Template']['info']['name']);
            if (wp_mail($send_to, stripslashes($subject), $message, $headers)) {
                $result = array(
                    'success' => true,
                    'message' => "Test email sent successfully. Please check your inbox.",
                    'title' => "Success!",
                    'html' => $message
                );
            }
        }
        die(json_encode($result));
    }

    /*
     * create view to show template.
     *
      @author Rico Celis
      @access Public
     */

    public function previewEmailTemplate() {
//	    $nonce = $_GET['nonce'];
//	   if ($nonce && wp_verify_nonce($nonce, 'ib-email-preview-nonce')) {
        $template_id = @$_GET['template_id'];
        // if loading an email?
        if (!$template_id) {
            $email_id = @$_GET['email_id'];
            $email = EmailModel::find($email_id)->toArray();
            $template_id = $email['email_template_id'];
        }
        // load template
        $template = EmailTemplate::find($template_id);
        $templateData = unserialize($template->settings);
        // template and email data (FROM POST)
        $settings = new SettingsModel;
        $data = array(
            'template_data' => $templateData,
            'email_data' => @$email,
            'FontAwesome' => new FontAwesomeHelper,
            'settings' => $settings->loadSettings(),
            'quick_preview' => true
        );
        echo $this->load->view(self::VIEW_PATH . 'email/template', $data, "blank");
        exit();
//	   }else{
//		   $this->_error['Unable to load preview'];
//	   }
    }

    public function verifyEmailTitle() {
        $result = array(
            'success' => true,
        );
        $nonce = @$_POST['nonce'];
        if (isset($_POST['email_id']) && !empty($_POST['email_id'])) {
            if ($nonce && wp_verify_nonce($nonce, 'ib-settings-nonce') && current_user_can("edit_posts")) {
                if (EmailModel::where("email_title", $_POST['title'])->where("email_id", '!=', $_POST['email_id'])->exists()) { // another lead has email
                    $result = array(
                        'success' => false,
                        'message' => sprintf("%s is associated with another email. Please choose a different title.", $_POST['title'])
                    );
                }
            }
        } else {
            if ($nonce && wp_verify_nonce($nonce, 'ib-settings-nonce') && current_user_can("edit_posts")) {
                if (EmailModel::where("email_title", $_POST['title'])->exists()) { // another lead has email
                    $result = array(
                        'success' => false,
                        'message' => sprintf("%s is associated with another email. Please choose a different title.", $_POST['title'])
                    );
                }
            }
        }
        die(json_encode($result));
    }

}
