<?php

/**
 * Created by sean.carrico.
 * User: sean
 * Date: 3/25/15
 * Time: 12:45 PM
 */

namespace InboundBrew\Modules\CTA\Controllers;

use WP_Query;
use InboundBrew\Modules\Core\AppController;
// Models
use InboundBrew\Modules\Core\Models\Option;
use InboundBrew\Modules\Core\Models\Post;
use InboundBrew\Modules\CTA\Models\CTATemplate;
use InboundBrew\Modules\CTA\Models\CallToAction as CallToActionModel;
use InboundBrew\Modules\CTA\Models\CallToActionPostLinkage;
use InboundBrew\Modules\Settings\Models\SettingsModel;

// helpers
use Valitron\Validator;
use InboundBrew\Libraries\FormHelper;
use InboundBrew\Libraries\DateHelper;
use InboundBrew\Libraries\LayoutHelper;

/**
 * Class CallToAction
 * @package InboundBrew\Modules\CTA
 */
class CallToAction extends AppController {

    /**
     *
     */
    const VIEW_PATH = 'CTA/views/';

    /**
     * @var string
     */
    private $post_type = 'ib-call-to-action';

    /**
     *
     */
    public function __construct() {
        parent::init();
        add_action('init', array($this, 'registerPostType'));

        add_shortcode('brew_cta', array($this, 'registerShortCode'));
// hook to save post
        add_action('save_post', array($this, 'checkforCTAsInPostContent'));

// register the ajax endpoint
        add_action('wp_ajax_ib_load_template_ctas', array($this, 'loadTemplateCtas'));
        add_action('wp_ajax_ib_load_cta_links', array($this, 'loadCtaLinks'));
        add_action('wp_ajax_ib_delete_cta_from_post', array($this, 'deleteCtaFromPost'));
        add_action('wp_ajax_ib_delete_template_cta', array($this, 'deleteCtaFromTemplate'));
// add the required JS and CSS for the CTA pages
        add_action('admin_enqueue_scripts', array($this, 'addAdminScripts'));
        add_action('wp_enqueue_scripts', array($this, 'addStyles'));
        $this->partials_path = BREW_MODULES_PATH . "CTA/views/partials/";



        add_action('shutdown', function() {
            $final = '';
            $levels = ob_get_level();
            for ($i = 0; $i < $levels; $i++){
                $final .= ob_get_clean();
            }
            echo apply_filters('final_output', $final);
        }, 0);

        add_filter('final_output', function($output) {          
            $after_body = apply_filters('after_body','');
            $output = preg_replace("/(\<body.*\>)/", "$1".$after_body, $output);
            return $output;
        });

        


    }

    public function addStyles() {
        wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), BREW_ASSET_VERSION);
    }

    /**
     *
     */
    public function addAdminScripts() {
//wp_enqueue_script('colorpicker-js', BREW_PLUGIN_ASSETS_URL.'third-party/tinyColorPicker/jqColorPicker.min.js',array('jquery','ib-jquery-ui-js'));
        $postType = get_post_type();
        if (@$_GET['section'] == "ib_add_cta" ||
                @$_GET['section'] == "ib_edit_cta" ||
                @$_GET['section'] == "ib_edit_template" ||
                @$_GET['section'] == "ib_clone_cta")
            $postType = $this->post_type;
        switch ($postType) {
            case $this->post_type:
                wp_enqueue_style('ib-cta-css', BREW_MODULES_URL . 'CTA/assets/css/cta.css', array(), BREW_ASSET_VERSION);
                wp_enqueue_style('colorpicker-css', BREW_MODULES_URL . 'Core/assets/third-party/colorpicker/css/colpick.css', array(), BREW_ASSET_VERSION);
                wp_enqueue_script('colorpicker-js', BREW_MODULES_URL . 'Core/assets/third-party/colorpicker/js/colpick.js', array(), BREW_ASSET_VERSION);
                wp_enqueue_script('ib-radius-editor-js', BREW_MODULES_URL . 'CTA/assets/js/ib_radius_editor.jquery.js', array('jquery'), BREW_ASSET_VERSION);
                wp_enqueue_script('ib-cta-js', BREW_MODULES_URL . 'CTA/assets/js/ib_cta_editor.jquery.js', array('colorpicker-js', 'jquery', 'ib-radius-editor-js'), BREW_ASSET_VERSION);
                wp_enqueue_script('ib-cta-tabs-js', BREW_MODULES_URL . 'CTA/assets/js/ib_cta_tabs.jquery.js', array(), BREW_ASSET_VERSION);

                // font picker
                wp_enqueue_script('ib-icon-picker-js', BREW_MODULES_URL . 'Core/assets/js/icon_picker/icon-picker.js', array('jquery'), BREW_ASSET_VERSION);
                wp_enqueue_style('ib-icon-picker-css', BREW_MODULES_URL . 'Core/assets/js/icon_picker/icon-picker.css', array(), BREW_ASSET_VERSION);




                break;
            case "page":
            case "post":
            case "ib-landing-page":
                wp_enqueue_script('ib-cta-metabox-js', BREW_MODULES_URL . 'CTA/assets/js/ib_cta_metabox.jquery.js', array(), BREW_ASSET_VERSION);
                /* --inbound-brew-free-start-- */
                $codes = $this->loadCTAs()->toArray();
                wp_localize_script(
                        'ib-cta-metabox-js', 'ibCtaShortCode', array('Codes' => $codes)
                );
                /* --inbound-brew-free-end-- */
                break;
            default:
                wp_enqueue_style('ib-cta-css', BREW_MODULES_URL . 'CTA/assets/css/cta.css', array(), BREW_ASSET_VERSION);
                break;
        }
        if (isset($_GET['page']) && $_GET['page'] == 'ib-call-to-action') {
            wp_enqueue_script('ib-cta-lists', BREW_MODULES_URL . 'CTA/assets/js/ib_cta_lists.jquery.js', array('jquery'), BREW_ASSET_VERSION);
            wp_enqueue_style('ib-cta-css', BREW_MODULES_URL . 'CTA/assets/css/cta.css', array(), BREW_ASSET_VERSION);
            wp_enqueue_style('colorpicker-css', BREW_MODULES_URL . 'Core/assets/third-party/colorpicker/css/colpick.css', array(), BREW_ASSET_VERSION);
            wp_enqueue_script('colorpicker-js', BREW_MODULES_URL . 'Core/assets/third-party/colorpicker/js/colpick.js', array(), BREW_ASSET_VERSION);
            wp_enqueue_script('ib-cta-tabs-js', BREW_MODULES_URL . 'CTA/assets/js/ib_cta_tabs.jquery.js', array(), BREW_ASSET_VERSION);
            wp_enqueue_script('ib-cta-main-shepherd', BREW_MODULES_URL . 'CTA/assets/js/ib-cta-main-shepherd.js', array(), BREW_ASSET_VERSION);
        }
// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
        wp_localize_script(
                'ib-cta-lists', 'ibCtaAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'ibCtaNonce' => wp_create_nonce('ib-cta-nonce')
                )
        );
    }

    public function loadAdminPage() {
        $data = array();
        $data['fonts'] = $this->getFonts();
        if ($defaults = get_option('ib_cta_defaults')) {
            $data['default'] = json_decode($defaults);
        }

        $ctas = Post::type('ib-call-to-action')->status()->get();
        $data['ctas'] = $ctas;
        echo $this->load->view(self::VIEW_PATH . 'admin', $data);
    }

    /**
     * handle views for CTA
     * use GET variable "section" to determine what to do.
     *
     * @author Rico Celis
     * @access public
     */
    public function handleCTASections() {
        $section = (@$_GET['section']) ? $_GET['section'] : "ib_cta_list";
        switch ($section) {
            case "ib_template_list":
                $this->templateList();
                break;
            case "ib_cta_list":
                $this->ctaList();
                break;
            case "ib_edit_template":
                $this->editTemplate(@$_GET['tid']);
                break;
            case "ib_delete_template":
                $this->deleteTemplate(@$_GET['tid']);
                $this->templateList();
                break;
            case "ib_choose_cta_type":
                $this->chooseCTAType();
                break;
            case "ib_add_cta":
                $this->addCallToAction($_GET['cta_type']);
                break;
            case "ib_edit_cta":
                $this->editCallToAction($_GET['cta_id']);
                break;
            case "ib_clone_cta":
                $this->addCallToAction("clone_cta", $_GET['cta_id']);
                break;
            case "ib_delete_cta":
                $this->deleteCallToAction($_GET['cta_id']);
                break;
            case "ib_choose_template":
                $this->chooseNewCtaTemplate();
                break;
        }
        if (@$_GET['view'] != "ib_templates") { // cta list
        } else { // template list
            $view = "admin_template_list";
            $data['templates'] = $this->loadCTATemplates();
        }
    }

    /**
     * user is creating a cta but needs to choose a template
     *
     * @author Rico Celis
     * @access public
     */
    public function chooseNewCtaTemplate() {
// helpers
        $data['templates'] = $this->loadCTATemplates();
        $data['post_type'] = $this->post_type;
#breadcrumbs;
        $this->Breadcrumb->add("CTA Template Management", "admin.php?page={$this->post_type}");
        $this->Breadcrumb->add("Choose CTA Type", "admin.php?page={$this->post_type}&section=ib_choose_cta_type");
        $this->Breadcrumb->add("Choose Template");
        $data['Breadcrumb'] = $this->Breadcrumb;
        $data['Date'] = new DateHelper;
// load view
        if (defined('DOING_AJAX') && DOING_AJAX) {
//echo $this->load->view(self::VIEW_PATH."admin_choose_template",$data);
//exit();
        } else {
            echo $this->load->view(self::VIEW_PATH . "admin_choose_template", $data);
        }
    }

    /**
     * Allow user to choose the Type of CTA they will be creating
     *
     * @author Rico Celis
     * @access public
     */
    public function chooseCTAType() {
// load view
        $this->Breadcrumb->add("CTA Management", "admin.php?page=" . $this->post_type);
        $this->Breadcrumb->add("Choose CTA Type");
        $data['post_type'] = $this->post_type;
        $data['Breadcrumb'] = $this->Breadcrumb;
        echo $this->load->view(self::VIEW_PATH . "admin_choose_type", $data);
    }

    /**
     * Allow user to create new cta.
     *
     * @param string $cta_type helps view decide what to load.
     * @param int $reference_id template id or cta id (for cloning)
     * @author Rico Celis
     * @access public
     */
    public function addCallToAction($cta_type = "custom", $reference_id = null) {
        $nonce = @$_POST['_wpnonce'];

        if (!empty($_POST) && $nonce && wp_verify_nonce($nonce, 'ib_cta_nonce') && current_user_can("edit_posts")) {
            $confirmMessage = "Saved Successfully";
            $redirect = "admin.php?page={$this->post_type}";
            switch ($cta_type) {
                case "custom":
                case "new_template":
                    $settings = $_POST['data']['CallToAction'];
                    $html_preview = trim(stripslashes($settings['html_preview']));
                    $hover_styles = trim(stripslashes($settings['hover_styles']));
                    unset($settings['html_preview']);
                    unset($settings['hover_styles']);
// save template
                    $template = new CTATemplate;
                    $template->name = $settings['normal']['text']['button_text'];
                    $template->html = $html_preview;
                    $template->settings = serialize($settings);
                    $template->hover_styles = $hover_styles;
                    $template->save();
                    $template_id = $template->template_id;
                    $confirmMessage = "Template Created Successfully";

// create cta
                    if ($cta_type == "custom") {
                        $ctaSettings = array(
                            'actions' => $settings['actions'],
                            'normal' => array(
                                'icon' => $settings['normal']['icon'],
                                'text' => array(
                                    'button_text' => $settings['normal']['text']['button_text'])
                            )
                        );
                        $cta = new CallToActionModel;
                        $cta->cta_template_id = $template_id;
                        $cta->name = $settings['normal']['text']['button_text'];
                        $cta->html = $html_preview;
                        $cta->cta_type = "button";
                        $cta->links_to = $settings['actions']['cta_link'];
                        $cta->links_to_value = ($settings['actions']['cta_link'] == "internal") ? $settings['actions']['internal_link'] : $settings['actions']['external_link'];
                        $cta->cta_settings = serialize($ctaSettings);
                        $cta->save();
                        $confirmMessage = "CTA and Template Created Successfully";
// confirm
                    } else {
                        $redirect = "admin.php?page={$this->post_type}&section=ib_template_list";
                    }
// redirect to template list.

                    break;
                case "image":
                    $settings = $_POST['data']['CallToAction'];
                    $html_preview = trim(stripslashes($settings['html_preview']));
                    unset($settings['html_preview']);
// create cta
                    $cta = new CallToActionModel;
                    $cta->cta_template_id = 0;
                    $cta->cta_type = "image";
                    $cta->name = "image cta";
                    $cta->html = $html_preview;
                    $cta->links_to_value = ($settings['actions']['cta_link'] == "internal") ? $settings['actions']['internal_link'] : $settings['actions']['external_link'];
                    $cta->cta_settings = serialize($settings);
                    $cta->save();
                    // confirm
                    $confirmMessage = "Image CTA Created Successfully";

                    break;
                case "clone_cta":
                    $confirmMessage = "CTA Cloned Successfully";
                case "new_cta":
                    $settings = $_POST['data']['CallToAction'];
                    $html_preview = trim(stripslashes($settings['html_preview']));
                    $template_id = $settings['cta_template_id'];
                    unset($settings['html_preview']);
                    unset($settings['cta_template_id']);
                    $cta = new CallToActionModel;
                    $cta->cta_type = "button";
                    $cta->cta_template_id = $template_id;
                    $cta->name = $settings['normal']['text']['button_text'];
                    $cta->html = $html_preview;
                    $cta->links_to = $settings['actions']['cta_link'];
                    $cta->links_to_value = ($settings['actions']['cta_link'] == "internal") ? $settings['actions']['internal_link'] : $settings['actions']['external_link'];
                    $cta->cta_settings = serialize($settings);
                    $cta->save();
// redirect to template list.

                    if ($cta_type != "clone_cta") {
                        $confirmMessage = "CTA Saved Successfully";
                    }
                    break;
            }

            $this->_confirm($confirmMessage, true);
            $this->jsRedirect($redirect);
        } else {
// breadcrumb
            $this->Breadcrumb->add("CTA Management", "admin.php?page=" . $this->post_type);
            $Form = new FormHelper;
            $view = "cta_custom";
// handle type
            switch ($cta_type) {
                case "custom":
                    $data = $this->setCtaData();
                    $this->Breadcrumb->add("New Custom CTA");
                    $Form->data = array('CallToAction' => $data['CallToAction']);
                    break;
                case "new_template":
                    $data = $this->setCtaData(null, null, array('is_template' => true));
                    $this->Breadcrumb->add("New CTA Template");
                    $Form->data = array('CallToAction' => $data['CallToAction']);
                    break;
                case "clone_cta":
                    $data = $this->setCtaData();
                    $cta = CallToActionModel::find($reference_id);
                    if (@$cta->cta_id) {
                        $template = CTATemplate::find($cta->cta_template_id);
                        $templateSettings = unserialize($template->settings);
                        $ctaSettings = unserialize($cta->cta_settings);
                        $settings = array_merge($templateSettings, $ctaSettings);
                        $this->Breadcrumb->add("Clone CTA");
                        $settings['cta_template_id'] = $template->template_id;
                        $Form->data = array('CallToAction' => $settings);
                    }
                    break;
                case "new_cta":
                    $template_id = $_GET['tid'];
                    $data = $this->setCtaData(null, $_GET['tid']);
                    $data['CallToAction']['cta_template_id'] = $template_id;
                    $Form->data = array('CallToAction' => $data['CallToAction']);
                    $this->Breadcrumb->add("New CTA from Template");
                    break;
                case "image":
                    $data = $this->setCtaData();
                    $Form->data = array('CallToAction' => $data['CallToAction']);
                    $this->Breadcrumb->add("New Image CTA");
                    break;
            }
// load view
            $data['cta_type'] = $cta_type;
            $data['Form'] = $Form;
            $data['Breadcrumb'] = $this->Breadcrumb;
            $data['fonts'] = $this->getFonts();
            echo $this->load->view(self::VIEW_PATH . $view, $data);
        }
    }

    /**

     */
    private function deleteCallToAction($cta_id) {
        $nonce = @$_GET['_wpnonce'];
        if ($nonce && wp_verify_nonce($nonce, 'ib_delete_cta_nonce') && current_user_can("edit_posts")) {
// delete cta linkages.
            CallToActionModel::deleteCTALinkages($cta_id);
// delete cta
            CallToActionModel::find($cta_id)->delete();
            $this->_confirm("CTA deleted.", true);
            $this->jsRedirect("admin.php?page={$this->post_type}");
        }
    }

    /**
     * Allow user to edit a cta.
     *
     * @param $cta_id record id.
     * @author Rico Celis
     * @access public
     */
    private function editCallToAction($cta_id) {
        $cta = CallToActionModel::find($cta_id);
        $nonce = @$_POST['_wpnonce'];
        if (!empty($_POST) && $nonce && wp_verify_nonce($nonce, 'ib_cta_nonce') && current_user_can("edit_posts")) {
            $settings = $_POST['data']['CallToAction'];
            $html_preview = trim(stripslashes($settings['html_preview']));
            unset($settings['CallToAction']['html_preview']);
            $cta->name = ($cta->cta_type == "button") ? $settings['normal']['text']['button_text'] : "Image CTA";
            $cta->html = $html_preview;
            $cta->links_to_value = ($settings['actions']['cta_link'] == "internal") ? $settings['actions']['internal_link'] : $settings['actions']['external_link'];
            $cta->cta_settings = serialize($settings);
            $cta->save();
// confirm
            $this->_confirm("CTA Updated", true);
// redirect
            $this->jsRedirect("admin.php?page={$this->post_type}");
        } else {
// content data
            $data = $this->setCtaData($cta_id);
// breadcrumb
            $this->Breadcrumb->add("CTA Management", "admin.php?page=" . $this->post_type);
            $this->Breadcrumb->add("Edit Call To Action");
            $data['Breadcrumb'] = $this->Breadcrumb;
            $Form = new FormHelper;
            $Form->data = array(
                'CallToAction' => $data['CallToAction']
            );
            $data['Form'] = $Form;
            $data['cta_type'] = ($cta->cta_type == "button") ? "edit_cta" : "edit_image";
            $data['post_type'] = $this->post_type;
            // load view
            echo $this->load->view(self::VIEW_PATH . "cta_custom", $data);
        }
    }

    /**
     * use data from $_POST and post id to save post meta and update post content.
     *
     * @params array $data cta post data
     * @params int $post_id wp post id.
     * @return boolean true when done.
     * @access Public
     * @author Rico Celis
     */
    private function saveCTAPostData($data, $post_id) {
// set some checkbox defaults
        $bold = 0;
        $italic = 0;
        $ucase = 0;
// Checks for input and sanitizes/saves if needed
        foreach ($data as $key => $value) {
            if ($key == 'font_weight') {
                unset($data[$key]);
                if ($value)
                    $bold = 1;
            }

            if ($key == 'text_transform') {
                unset($data[$key]);
                if ($value)
                    $ucase = 1;
            }

            if ($key == 'font_style') {
                unset($data[$key]);
                if ($value)
                    $italic = 1;
            }

            if ($key != 'post_content') {
                update_post_meta($post_id, $key, sanitize_text_field($value));
            } else {
                update_post_meta($post_id, $key, stripslashes($value));
            }
        }
        /* manually update the checkbox inputs later. If they are not checked they will not be sent
          This makes it impossible to turn them off via the form post.
          So we updated them regardless of presence with the default value of zero */
        update_post_meta($post_id, 'font_weight', $bold);
        update_post_meta($post_id, 'text_transform', $ucase);
        update_post_meta($post_id, 'font_style', $italic);

        $my_post = array(
            'ID' => $post_id,
            'post_content' => @$_POST['post_content'],
        );

// save wizzard step
        $Settings = new SettingsModel;
        $Settings->wizzardStepCompleted("ctas");

// Update the post into the database
        wp_update_post($my_post);
        return true;
    }

    /**
     * get CTA data ready for view
     * get fonts
     * get available pages.
     *
     * @param int $reference_id id of cta or template record
     * @param array $options options
     * @author Rico Celis
     * @access public
     */
    public function setCtaData($cta_id = null, $template_id = null, $options = array()) {
        $_default = array(
            'load_pages' => true,
            'is_template' => false
        );
        $options = array_merge($_default, $options);
        $data = array();
        $data['post_type'] = $this->post_type;
        if ($options['load_pages']) {
            $pages = $this->getPages();
            $data['pages'] = array();
            if (@$pages) {
                foreach ($pages as $page) {
                    $link = $page->ID;
                    $data['pages'][$link] = $page->post_title;
                }
            }
        }
        $data['fonts'] = $this->getFonts();


        //brand new custom CTA
        if (!$cta_id && !$template_id) {
            $callToAction = array(
                'normal' => array(
                    'text' => array(
                        'button_text' => 'Button Text',
                        'font_family' => 'inherit',
                        'text_shadow' => array(
                            'x' => "0",
                            'y' => "0",
                            'blur' => "0"
                        ),
                        'font_size' => 12,
                    ),
                    'border' => array(
                        'border_top_left_radius' => "0",
                        'border_top_right_radius' => "0",
                        'border_bottom_left_radius' => "0",
                        'border_bottom_right_radius' => "0",
                        'border_width' => 0,
                    ),
                    'background' => array(
                        'v_padding' => "0",
                        'h_padding' => "0",
                        'type' => "solid"
                    ),
                    'actions' => array(
                        'cta_link' => "internal"
                    )
                ),
                'hover' => array(
                    'text' => array(
                        'button_text' => 'Button Text',
                        'font_family' => 'inherit',
                        'text_shadow' => array(
                            'x' => "0",
                            'y' => "0",
                            'blur' => "0"
                        ),
                        'font_size' => 12,
                    ),
                    'border' => array(
                        'border_top_left_radius' => "0",
                        'border_top_right_radius' => "0",
                        'border_bottom_left_radius' => "0",
                        'border_bottom_right_radius' => "0",
                        'border_width' => 0,
                    ),
                    'background' => array(
                        'v_padding' => "0",
                        'h_padding' => "0",
                        'type' => "solid"
                    ),
                    'actions' => array(
                        'cta_link' => "internal"
                    )
                )
            );
        } 
        //loading from template
        else if (!$cta_id && $template_id) {
            $template = CTATemplate::find($template_id);
            $callToAction = unserialize($template->settings);
            $callToAction['html_preview'] = stripcslashes($template->html);
            $callToAction['hover_styles'] = stripcslashes($template->hover_styles);
        }
        else if ($cta_id && !$template_id) {
            $cta = CallToActionModel::find($cta_id);
            $callToAction = unserialize($cta->cta_settings);
            $callToAction['html_preview'] = stripcslashes($cta->html);
            if ($cta->cta_template_id) { // load template to get icon settings
                $template = CTATemplate::find($cta->cta_template_id);
                $tsettings = unserialize($template->settings);
                if (empty($callToAction['normal']['icon'])) {
                    $callToAction['normal']['icon'] = $tsettings['normal']['icon'];
                }
                $callToAction['hover_styles'] = stripcslashes($template->hover_styles);
            }
        }
        
        $data['CallToAction'] = $callToAction;
        return $data;
    }

    /**
     * display list of CTA's
     *
     * @author Rico Celis
     * @access public
     */
    private function ctaList($return_ctas = false) {
// helpers
        $ctas = $this->loadCTAs();
        $data['post_type'] = $this->post_type;
        $data['ctas'] = $ctas;
#breadcrumbs;
        $this->Breadcrumb->add("CTA Management");
        $data['Breadcrumb'] = $this->Breadcrumb;
        $data['Date'] = new DateHelper;
        $data['Layout'] = new LayoutHelper;
// check wizzard status
        if (isset($_GET['wizzard'])) {
            $path = self::VIEW_PATH . "cta_wizzard_instructions";
            echo $this->load->view($path, array(), "blank");
        }
        $data['partials_path'] = $this->partials_path;
// load view
        echo $this->load->view(self::VIEW_PATH . "admin_list", $data);
    }

    /**
     * display list of CTA templates
     *
     * @author Rico Celis
     * @access public
     */
    public function templateList() {
// helpers
        $data['templates'] = $this->loadCTATemplates();
        $data['post_type'] = $this->post_type;
#breadcrumbs;
        $this->Breadcrumb->add("CTA Template Management");
        $data['Breadcrumb'] = $this->Breadcrumb;
        $data['Date'] = new DateHelper;
        $data['Layout'] = new LayoutHelper;
        $data['partials_path'] = $this->partials_path;
// load view
        if (defined('DOING_AJAX') && DOING_AJAX) {
            echo $this->load->view(self::VIEW_PATH . "admin_choose_template", $data);
            exit();
        } else {
            echo $this->load->view(self::VIEW_PATH . "admin_template_list", $data);
        }
    }

    /**
     * edit template
     *
     * @param int $template_id Template id
     * @author Rico Celis
     * @access public
     */
    private function editTemplate($template_id) {
        $template = CTATemplate::find($template_id);
        if (empty($template->template_id)) { // valid item
            $this->_confirm("Invalid CTA template.", true);
            $this->jsRedirect($_SERVER['HTTP_REFERER']);
        } else {
            $nonce = @$_POST['_wpnonce'];
            if (!empty($_POST) && $nonce && wp_verify_nonce($nonce, 'ib_cta_nonce') && current_user_can("edit_posts")) {
// save template
                $settings = $_POST['data']['CallToAction'];
                $html_preview = trim(stripslashes($settings['html_preview']));
                $hover_styles = trim(stripslashes($settings['hover_styles']));
                unset($settings['html_preview']);
                unset($settings['hover_styles']);
                $template->name = $settings['normal']['text']['button_text'];
                $template->html = $html_preview;
                $template->hover_styles = $hover_styles;
                $template->settings = serialize($settings);
                $template->save();
                $this->jsRedirect("admin.php?page={$this->post_type}&section=ib_template_list");
                CallToActionModel::templateModified($template);
                return;
            }
            $Form = new FormHelper;
            $Form->data = array(
                'CallToAction' => unserialize($template->settings)
            );
            $Form->data['CallToAction']['html_preview'] = $template->html;
            $Form->data['CallToAction']['hover_styles'] = $template->hover_styles;
            $pages = $this->getPages();
            $data['pages'] = array();
            if (@$pages) {
                foreach ($pages as $page) {
                    $link = get_permalink($page->ID);
                    $data['pages'][$link] = $page->post_title;
                }
            }
// load fonts
            $data['fonts'] = $this->getFonts();
            $this->Breadcrumb->add("CTA Template Management", "admin.php?page={$this->post_type}&section=ib_template_list");
            $this->Breadcrumb->add("Edit Template");
            $data['Breadcrumb'] = $this->Breadcrumb;
            $data['Date'] = new DateHelper;
            $data['cta_type'] = "edit_template";
            $data['post_type'] = $this->post_type;
            $data['Form'] = $Form;
            echo $this->load->view(self::VIEW_PATH . "cta_custom", $data);
        }
    }

    /**
     * load ctas from database
     * paginate based on GET variables in url.
     *
     * @return Eloquent Paginator Instance (will all results for this page)
     * @author Rico Celis
     * @access public
     */
    private function loadCTAs() {
        $results = CallToActionModel::orderBy("name", "ASC")->get();
        return $results;
    }

    /**
     * load cta templates from database
     * paginate based on GET variables in url.
     *
     * @return Eloquent Paginator Instance (will all results for this page)
     * @author Rico Celis
     * @access public
     */
    private function loadCTATemplates() {
// load all redirects
        $order = "name";
        $direction = "ASC";
//		if($_GET['order']) $order = $_GET['order'];
//		if($_GET['direction']) $direction = $_GET['direction'];
//		$wp_page = $_GET['page'];
        $templates = CTATemplate::getList($order, $direction);
        return $templates;
    }

    /**
     *
     */
    public function registerPostType() {
        $this->labels = array(
            'name' => 'Call to Action',
            'singular_name' => 'Call to Action',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Call to Action',
            'edit_item' => 'Edit Call to Action',
            'new_item' => 'New Call to Action',
            'view_item' => 'View Call to Action',
            'search_items' => 'Search Call to Action',
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
            'rewrite' => array("slug" => "inboundbrew-cta"),
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => null,
            'show_in_nav_menus' => false,
            'show_in_menu' => false,
            'supports' => array('title', 'thumbnail')
        );

        register_post_type($this->post_type, $args);
    }

    /**
     * @param $atts
     * @return string
     */
    public function registerShortCode($atts) {
        $cta_style = "";
        extract(shortcode_atts(array(
            'id' => '0',
            'is_preview' => false,
                        ), $atts));
        $cta = CallToActionModel::find($id);
        if (@$cta->cta_id) {
            $html = $cta->html;
            $cta_id = "inboundbrew_cta_{$cta->cta_id}";
            $html = str_replace("<a ", "<a id=\"{$cta_id}\" ", $html);
            if ($cta->links_to == "internal") {
                $ptn = '/^<a.*?href=(["\'])(.*?)\1.*$/';
                preg_match_all($ptn, $html, $matches);
                $match = @$matches[2][0];
                if (!empty($match)) {
                    if (!is_numeric($match)) {
                        // is url
                        $wp_id = url_to_postid($match);
                    } else {
                        $wp_id = $match;
                    }
                    if (@$wp_id) {
                        $pt = get_post_type($wp_id);
                        if (in_array($pt, array("post", "page"))):
                            $link_url = get_permalink($wp_id);
                        else:
                            $link_url = get_post_permalink($wp_id);
                        endif;
                        if ($is_preview) {
                            $link_url = "javascript:void(0);";
                        }
                        $html = str_replace('href="' . $match . '"', 'href="' . $link_url . '"', $html);
                    }
                }
            }
            $template = CTATemplate::find($cta->cta_template_id);
            $hover_styles = str_replace("#cta_preview .cta-btn", "#" . $cta_id, @$template->hover_styles);
            return "<style>{$hover_styles}{$cta_style}</style>{$html}";
        } else {
            return ''; // not a valid cta
        }
    }

    /**
     * @return array
     */
    public function getPages() {
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

    /* delete CTA template */

    private function deleteTemplate($template_id) {
        $nonce = $_GET['_wpnonce'];
        if (wp_verify_nonce($nonce, 'ib-delete-template') && current_user_can('delete_posts')) {
            $template = CTATemplate::find($template_id);
            if (!empty($template->template_id)) { // valid item
                $template->delete();
                CallToActionModel::templateDeleted($template_id);
                $this->_confirm("CTA template deleted.", true);
            }
        } else {
            $this->_error("Invalid CTA template. Please try again", true);
        }
// redirect to template list.
        $this->jsRedirect("admin.php?page={$this->post_type}&section=ib_template_list");
    }

    /* use needs template cta's */

    public function loadTemplateCtas() {
// load ctas
        $result = CallToActionModel::where("cta_template_id", $_POST['template_id'])->orderBy("name", "asc")->get()->toArray();
        $ctas = array();
        if (!empty($result)) {
            $Date = new DateHelper;
            foreach ($result as $cta) {
                $cta['pages_on'] = CallToActionPostLinkage::where("cta_id", $cta['cta_id'])->count();
                $cta['updated_at'] = $Date->format("m/d/y H:i", $cta['updated_at'], true);
                if ($cta['links_to'] == "internal") {
                    $wp_id = $cta['links_to_value'];
                    $pt = get_post_type($wp_id);
                    if (in_array($pt, array("post", "page"))) {
                        $link_url = get_permalink($wp_id);
                    } else {
                        $link_url = get_post_permalink($wp_id);
                    }
                    $link_title = get_the_title($wp_id);
                } else {
                    $link_url = $link_title = $cta['links_to_value'];
                }
                $cta['link_url'] = $link_url;
                $cta['link_title'] = $link_title;
                $ctas[] = $cta;
            }
        }
        header('Content-Type: application/json');
        die(json_encode($ctas));
    }

    /* get list of posts/pages where the cta exists */

    public function loadCtaLinks() {
        $cta_id = $_POST['cta_id'];
        $linkages = CallToActionPostLinkage::where("cta_id", $cta_id)->get()->toArray();
        $links = array();
        if (!empty($linkages)) {
            $post_ids = array();
            foreach ($linkages as $linkage) {
                $post_ids[] = $linkage['wp_post_id'];
            }
            $result = Post::whereIn("ID", $post_ids)->orderBy("post_title", "ASC")->get()->toArray();
            if (!empty($result)) {
                foreach ($result as $link) {
                    $links[] = array(
                        'post_id' => $link['ID'],
                        'url' => get_permalink($link['ID']),
                        'title' => $link['post_title'],
                        'post_type' => $link['post_type'],
                        'cta_id' => $cta_id
                    );
                }
            }
        }
        header('Content-Type: application/json');
        die(json_encode($links));
    }

    /**
     * hook on save_post and check if a cta is in the page
     *
     * @param $post_id word press post id
     * @author Rico Celis
     * @access public
     */
    public function checkforCTAsInPostContent($post_id) {
        if (!empty($_POST) && @$_POST['content']) {
            $content = $_POST['content'];
            CallToActionModel::checkForCTAsInContent($content, $post_id);
        }
    }

    /**
     * user wants to delete a cta reference in a post
     * called through ajax (ib_delete_cta_from_post)
     *
     * @return json response
     * @author Rico Celis
     * @access public
     */
    public function deleteCtaFromPost() {
        $nonce = $_POST['nonce'];
        $response = array(
            'type' => 0,
            'message' => "Unable to delete reference.Please try again."
        );
        if (wp_verify_nonce($nonce, 'ib-cta-nonce') && current_user_can('delete_posts')) {
            CallToActionModel::deleteCTAPostReference($_POST['cta_id'], $_POST['post_id']);
            $response['type'] = 1;
        }
        header('Content-Type: application/json');
        die(json_encode($response));
    }

    /**
     * user wants to delete a cta linked to a template
     * called through ajax (ib_delete_template_cta)
     *
     * @return json response
     * @author Rico Celis
     * @access public
     */
    public function deleteCtaFromTemplate() {
        $nonce = $_POST['nonce'];
        $response = array(
            'type' => 0,
            'message' => "Unable to delete CTA.Please try again."
        );
        if (wp_verify_nonce($nonce, 'ib-cta-nonce') && current_user_can('delete_posts')) {
            CallToActionModel::deleteCTALinkages($_POST['cta_id']);
            CallToActionModel::find($_POST['cta_id'])->delete();
            $response['type'] = 1;
        }
        header('Content-Type: application/json');
        die(json_encode($response));
    }

}
