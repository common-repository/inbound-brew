<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 10/21/15
 * Time: 1:59 PM
 */

namespace InboundBrew\Modules\Contact\Controllers;

use InboundBrew\Modules\Contact\Models\Email as EmailModel;
use InboundBrew\Modules\Contact\Models\Post;
use InboundBrew\Modules\Core\AppController;
use InboundBrew\Modules\Core\Models\FormField;
use InboundBrew\Modules\Core\Models\PostMeta;
use InboundBrew\Modules\Settings\Models\SettingsModel;
// helpers
use Valitron\Validator;

/**
 * Class Email
 * @package InboundBrew\Modules\Contact\Controllers
 */
class Email extends AppController{

    /**
     *
     */
    const VIEW_PATH = 'Contact/views/';
    var $post_type = "ib-email-admin";

    /**
     *
     */
    function __construct()
    {
        parent::init();
        $this->init();
        $this->partials_path = BREW_MODULES_PATH . "Contact/views/partials/";
    }

    /**
     *
     */
    public function init()
    {
        add_action('admin_menu', array($this, 'registerMenu'));
        add_action('admin_enqueue_scripts', array($this,'addAdminScripts'));
        add_action('phpmailer_init',array($this,'phpMailerInit'));
        add_action('admin_head', array($this,'addEmailTemplateButtons'));
        add_action('admin_post_save_email_settings', array($this,'saveEmailSettings'));
        add_action('admin_post_send_test_email', array($this,"sendTestEmail"));
        add_action('admin_post_save_email_template', array($this,'saveEmailTemplate'));
        add_action('admin_post_delete_email_template', array($this,'deleteTemplate'));
        add_action('admin_post_save_send_settings', array($this,'saveSendSettings'));
        add_action('wp_ajax_attach_template_token', array($this,'addTemplateFields'));
        add_action('wp_ajax_detach_template_token', array($this,'removeTemplateFields'));
        add_action('wp_ajax_set_download_link_bool', array($this,'updateTemplateDownload'));
        add_action('wp_ajax_ib_get_email_template_preview', array($this,'getEmailTemplatePreview'));
    }

    /**
     * Calls the add_submenu_page hook with callback that takes user to the admin page
     */
    public function registerMenu()
    {
        add_submenu_page(
            'inboundbrew',
            'Inbound Brew: Email',
            'Email',
            'manage_options',
            'ib-email-admin',
            array($this,'loadAdmin')
        );
    }

    /**
     *
     */
    public function addAdminScripts()
    {
        if (isset($_GET['page']) && @$_GET['page'] == 'ib-email-admin') {
            wp_enqueue_media();
            wp_enqueue_script('tinymce');
            wp_enqueue_style('ib-cta-css', BREW_MODULES_URL.'CTA/assets/css/cta.css', array(), BREW_ASSET_VERSION);
            wp_enqueue_style('colorpicker-css', BREW_MODULES_URL.'Core/assets/third-party/colorpicker/css/colpick.css', array(), BREW_ASSET_VERSION);
            wp_enqueue_script('colorpicker-js', BREW_MODULES_URL.'Core/assets/third-party/colorpicker/js/colpick.js', array(), BREW_ASSET_VERSION);
            wp_enqueue_script('ib-tabs-js', BREW_MODULES_URL.'Core/assets/js/ib-tabs.jquery.js', array(),BREW_ASSET_VERSION);
            wp_enqueue_script('ib-cta-js', BREW_MODULES_URL.'CTA/assets/js/cta.js',array('colorpicker-js'),BREW_ASSET_VERSION);
            wp_enqueue_script('ib-cta-tabs-js', BREW_MODULES_URL.'CTA/assets/js/ib_cta_tabs.jquery.js', array(), BREW_ASSET_VERSION);
            wp_enqueue_script('ib-email', BREW_MODULES_URL.'Contact/assets/js/ib-email.jquery.js', array(), BREW_ASSET_VERSION);
            wp_enqueue_script('ib-email-tokens', BREW_MODULES_URL.'Contact/assets/js/email-tokens.jquery.js', array(), BREW_ASSET_VERSION);
            $FormField = new FormField;
            wp_localize_script(
                'ib-email-tokens', 'ibEmailAjax',
                array('Codes'=>FormField::all()->toArray())
            );

        }
    }

    function addEmailTemplateButtons() {
        // check user permissions
        if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
            return;
        }
        add_filter("mce_external_plugins", array($this,'addTinyMceButtonPlugin'));
        add_filter('mce_buttons_3', array($this, 'registerEmailMceButtons'));

    }

    function addTinyMceButtonPlugin($plugin_array) {
        $plugin_array['ib_email_token_buttons'] = BREW_MODULES_URL.'Contact/assets/js/email-tokens.tinymce.js';
        return $plugin_array;
    }

    function registerEmailMceButtons($buttons) {
        array_push($buttons, "ib_email_cf_token_button");
        array_push($buttons, "ib_download_link_token_button");
        return $buttons;
    }
    
    public function getEmailTemplatePreview(){
	    $options = json_decode(get_option('ib_email_settings'));
        $data['default'] = $options;
        $result['template'] = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>'), array('', ''), $this->load->view(self::VIEW_PATH . 'email/general',$data,"blank")));
        header('Content-Type: application/json');
        die(json_encode($result));
    }

    /**
     *
     */
    public function loadAdmin()
    {
	   	$section = @$_GET['section'];
	   	if(!$section) $section = "emails";
	   	switch($section){
			case "emails":
				$this->emailsList();
			break;
	   	}
    }
    
    /*
	* show available emails
	*
	@ author Rico Celis
	@ access private
	*/
    private function emailsList(){
	    // check wizzard status
		if(isset($_GET['wizzard'])){
			$path = self::VIEW_PATH."email_wizzard_instructions";
			echo $this->load->view($path,array(),"blank");
		}
        #breadcrumbs;
        $this->Breadcrumb->add("Emails");
        $data['Breadcrumb'] = $this->Breadcrumb;
        $data['templates'] = EmailModel::all();
        $data['post_type'] = $this->post_type;
        $data['partials_path'] = $this->partials_path;
        // load view
        echo $this->load->view(self::VIEW_PATH . 'admin_emails',$data);
        break;
    }

    public function template($id = false)
    {
        $title = 'New Template';
        if ($id) {
            $title = 'Edit Email';
            $template = EmailModel::find($id);
            if (is_object($template->fields)) {
                foreach ($template->fields as $field) {
                    $data['field_array'][] = $field->field_id;
                }
            }
            $arr = array();
            $forms = PostMeta::where('meta_key', '=', 'email_template')->where('meta_value', '=', $template->email_id)->get();
            foreach ($forms as $key=>$form) {
                if ($form->post->post_status != 'publish') {
                    unset($forms[$key]);
                } else {
                    $post = Post::find($form->post->ID);
                    foreach ($post->fields as $field) {
                        $arr[$post->post_title][] = $field->field_token;
                    }
                }

            }

            $data['form_fields'] = json_encode($arr);
            $data['forms'] = $forms;
            $data['mail'] = $template;
        } else {
            if (!$template = EmailModel::where('email_title','')->first()) {
                $template = new EmailModel();
                $template->email_title = '';
                $template->email_value = '';
                $template->save();
            }
            $data['mail'] = $template;
        }
        $data['tokens'] = FormField::all();
        $this->Breadcrumb->add('Emails',"admin.php?page=ib-email-admin&section=templates");
        $this->Breadcrumb->add($title);
        $data['Breadcrumb'] = $this->Breadcrumb;
        // load view
        echo $this->load->view(self::VIEW_PATH . 'email-template',$data);
    }

    public function saveEmailSettings()
    {
        $option = json_decode(get_option('ib_email_settings'));
        foreach($_POST as $key=>$value) {
            $option->$key = $value;
        }
        if(!isset($_POST['no_logo_container'])) $option->no_logo_container = "0";
        if(!isset($_POST['no_social_container'])) $option->no_social_container = "0";
        update_option('ib_email_settings',json_encode($option));
        $this->_confirm("Changes have been successfully saved",true);
        // save wizzard step
		$Settings = new SettingsModel;
		$Settings->wizzardStepCompleted("emails");
        // redirect
        header('Location: ' . $_POST['_wp_http_referer']);
    }

    public function saveEmailTemplate()
    {
        if (!isset( $_POST['_wpnonce']) || !wp_verify_nonce( $_POST['_wpnonce'], 'save_email_template')) {
            $this->_error("Changes could not be saved",true);
            header('Location: ' . $_POST['_wp_http_referer']);
        }
        $v = new Validator($_POST);
        $v->rule('required', array('email_id', 'email_title', 'email_value', 'email_subject'));
        $v->rule('numeric', array('email_id'));
        if ($v->validate()) {
            $mail = EmailModel::find($_POST['email_id']);
            $mail->email_title = $_POST['email_title'];
            $mail->email_value = stripslashes($_POST['email_value']);
            $mail->email_subject = stripslashes($_POST['email_subject']);
            $mail->send_to = stripcslashes($_POST['send_to']);
            $mail->send_cc = stripcslashes($_POST['send_cc']);
            $mail->send_bcc = stripcslashes($_POST['send_bcc']);
            $mail->save();
            // save wizzard step
			$Settings = new SettingsModel;
			$Settings->wizzardStepCompleted("emails");
            // save email template
            $this->_confirm("Your template has been saved", true);
            header('Location: admin.php?page=ib-email-admin&section=templates');
        } else {
            http_response_code(400);
            $this->_error("Changes could not be saved. Please ensure that the email has a title, body content, and subject",true);
            header('Location: ' . $_POST['_wp_http_referer']);
        }
    }

    public function deleteTemplate()
    {

        $template = EmailModel::find($_GET['email_id']);
        $template->delete();
        $this->_confirm("Your template has been deleted",true);
        header('Location: admin.php?page=ib-email-admin&section=templates');
    }

    /**
     * attaches keywords to posts/pages via the ib_post_keywords pivot table
     */
    public function addTemplateFields() {
        $v = new Validator($_POST);
        $v->rule('required', array('email_id', 'field_id'));
        $v->rule('numeric', array('email_id','field_id'));
        if ($v->validate()) {
            try {
                $form = FormField::find($_POST['field_id']);
                $template = EmailModel::find($_POST['email_id']);
                $template->fields()->attach($form->field_id);
                $result['message'] = 'success';
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

    public function removeTemplateFields() {
        $v = new Validator($_POST);
        $v->rule('required', array('email_id', 'field_id'));
        $v->rule('numeric', array('email_id','field_id'));
        if ($v->validate()) {
            try {
                $template = EmailModel::find($_POST['email_id']);
                $template->fields()->detach($_POST['field_id']);
                $result['message'] = 'success';
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

    public function updateTemplateDownload()
    {
        $v = new Validator($_POST);
        $v->rule('required', array('email_id', 'link_value'));
        $v->rule('numeric', array('email_id','link_value'));
        if ($v->validate()) {
            try {
                $template = EmailModel::find($_POST['email_id']);
                $template->email_download_link = $_POST['link_value'];
                $template->save();
                $result['message'] = 'success';
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

    public function saveSendSettings()
    {
        if (!isset( $_POST['_wpnonce']) || !wp_verify_nonce( $_POST['_wpnonce'], 'ib_email_settings') || !is_admin()) {
            $this->_error("You do not have the required permissions to make these changes",true);
            header('Location: ' . $_POST['_wp_http_referer']);
        }

        $option = json_decode(get_option('ib_smtp_options'));
        foreach($_POST as $key=>$value) {
            $option->$key = $value;
        }
        update_option('ib_smtp_options',json_encode($option));
        $this->_confirm("Changes have been successfully saved",true);
        // save wizzard step
		$Settings = new SettingsModel;
		$Settings->wizzardStepCompleted("emails");
        header('Location: ' . $_POST['_wp_http_referer']);

    }

    public function sendTestEmail()
    {
        if (!isset( $_POST['_wpnonce']) || !wp_verify_nonce( $_POST['_wpnonce'], 'ib_test_email')) {
            $this->_error("You do not have required permissions",true);
            header('Location: ' . $_POST['_wp_http_referer']);
        }
		$headers = array();
        $options = json_decode(get_option('ib_smtp_options'));
        $data['default'] = json_decode(get_option('ib_email_settings'));
        if (@$options->mail_content_type == 'html') {
            $wrap = $this->load->view(self::VIEW_PATH . 'email/general', $data,"blank");
            $message = str_ireplace('{{template_content}}', 'This is a test message from IB', $wrap);
            $message = str_ireplace('{{email_title}}', 'Test from IB SMTP Module', $message);
            $headers = array('Content-Type: text/html; charset=UTF-8');
        } else {
            $message = 'This is a test message from IB';
        }

        $to = $_POST['to'];
        $subject = 'Test from IB SMTP Module';
        wp_mail($to, $subject, $message, $headers);

        $this->_confirm("Your test email has been sent to $to",true);
        header('Location: ' . $_POST['_wp_http_referer']);
    }

    public function phpMailerInit($phpmailer)
    {
        // Check that mailer is not blank, and if mailer=smtp, host is not blank
        if ( !$options = json_decode(get_option('ib_smtp_options'))) {
            return;
        }

        // Set the mailer type as per config above, this overrides the already called isMail method
        $phpmailer->Mailer = $options->mailer;

        if (isset($options->mail_from_name)) {
            $phpmailer->FromName = $options->mail_from_name;
        }

        if (isset($options->mail_from)) $phpmailer->From = $options->mail_from;

        // Set the Sender (return-path) if required
        if (@$options->mail_set_return_path) $phpmailer->Sender = $phpmailer->From;

        // Set the SMTPSecure value, if set to none, leave this blank
        $phpmailer->SMTPSecure = ($options->smtp_ssl == 'none')?'':$options->smtp_ssl;

        // If we're sending via SMTP, set the host
        if ($options->mailer == "smtp") {

            // Set the SMTPSecure value, if set to none, leave this blank
            $phpmailer->SMTPSecure = ($options->smtp_ssl == 'none')?'':$options->smtp_ssl;

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
}