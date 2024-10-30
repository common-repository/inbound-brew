<?php

/**
 * Created by sean.carrico.
 * User: sean
 * Date: 3/31/15
 * Time: 3:06 PM
 */

namespace InboundBrew\Modules\Contact\Controllers;

use InboundBrew\Modules\Contact\Models\Post;
use InboundBrew\Modules\Core\AppController;
use InboundBrew\Modules\Contact\Models\Email;
use InboundBrew\Modules\Settings\Models\SettingsModel;
use InboundBrew\Libraries\BreadcrumbHelper;
use InboundBrew\Libraries\DateHelper;
// helpers
use InboundBrew\Modules\Core\Models\PostMeta;
use Valitron\Validator;
use WP_Query;

/**
 * Class LandingPage
 * @package InboundBrew\Modules\Contact
 */
class LandingPage Extends AppController {

    /**
     *
     */
    const VIEW_PATH = 'Contact/views/';

    /**
     * @var string
     */
    private $post_type = 'ib-landing-page';
    private $data = array();

    /**
     *
     */
    public function __construct() {
        parent::init();

        add_action('init', array($this, 'registerPostType'));

        add_filter('single_template', array($this, 'loadTemplate'));
        add_filter('post_type_link', array($this, 'removePostTypeSlug'), 10, 2);

        add_action('pre_get_posts', array($this, 'customPostParseRequest'));
        add_action('add_meta_boxes_' . $this->post_type, array($this, 'addMetaBox'), 0);
        add_action('save_post_' . $this->post_type, array($this, 'saveLandingPage'));
        add_action("wp_ajax_get_contact_form_data", array($this, "getFormData"));

        add_action('admin_enqueue_scripts', array($this, 'loadAdminScripts'));
    }

    public function loadAdminScripts() {
        global $post;

        if (@$post->post_type == $this->post_type) {
            //get rid of screen options here
            add_filter( 'screen_options_show_screen', '__return_false' );

            wp_enqueue_media();
            // register required js
            wp_enqueue_script('ib-lp-cf', BREW_MODULES_URL . 'Contact/assets/js/ib-lp-cf.jquery.js', array('jquery'), BREW_ASSET_VERSION);

            $activeModule = array(
                                'title' => "Landing Pages",
                                'class' => "file-text-o",
                                'page' => "landing-page-admin",
                                'is_module' => true,
                                'module_name' => "Landing Pages",
                            );


            

            add_action('admin_menu', array($this, "disableAddNew"));
        }

        if (isset($_GET['page']) && $_GET['page'] == 'landing-page-admin'){
            wp_enqueue_script('ib-landing-page-shehperd-js', BREW_MODULES_URL . 'Contact/assets/js/ib-landing-page-shepherd.js', array(), BREW_ASSET_VERSION);
        }
    }

    public function disableAddNew(){
        echo '<style type="text/css">
                a.page-title-action { display:none !important; }
              </style>';
    }

    /**
     * @param $post_link
     * @param $post
     * @return mixed
     */
    function removePostTypeSlug($post_link, $post) {

        if ($this->post_type != $post->post_type) { // || 'publish' != $post->post_status ) {
            return $post_link;
        }

        $post_link = str_replace('/' . $post->post_type . '/', '/', $post_link);

        return $post_link;
    }

    /**
     * @param $query
     */
    function customPostParseRequest($query) {
        global $wpdb;

        if (!$query->is_main_query()) {
            return;
        }

        $post_name = (!empty($query->query_vars['pagename']) ? $query->query_vars['pagename'] : (!empty($query->query_vars['name']) ? $query->query_vars['name'] : $query->query_vars['category_name'] ));

        $post_type = $wpdb->get_var(
                $wpdb->prepare(
                        'SELECT post_type FROM ' . $wpdb->posts . ' WHERE post_name = %s LIMIT 1', $post_name
                )
        );

        switch ($post_type) {
            case $this->post_type:
                $query->set('name', $post_name);
                $query->set('pagename', $post_name);
                $query->set('post_type', $post_type);
                unset($query->query['category_name']);
                unset($query->query_vars['category_name']);
                $query->query['pagename'] = $post_name;
                $query->query['page'] = '';
                $query->is_page = TRUE;
                $query->is_singular = TRUE;
                $query->is_home = FALSE;
                $query->is_archive = FALSE;
                $query->is_category = FALSE;
                break;
        }
        //print_debug($query, true);

    }

    /**
     * Register the Landing Page Post Type
     *
     */
    public function registerPostType() {
        $this->labels = array(
            'name' => 'Landing Pages',
            'singular_name' => 'Landing Page',
            'add_new' => 'Add New',
            'add_new_item' => "Add New Landing Page",
            'edit_item' => 'Edit Landing Page',
            'new_item' => 'New Landing Page',
            'view_item' => 'View Landing Page',
            'search_items' => 'Search Landing Pages',
            'not_found' => 'Nothing found',
            'not_found_in_trash' => 'Nothing found in Trash',
            'parent_item_colon' => '',
        );

        $args = array(
            'labels' => $this->labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'query_var' => true,
            'menu_icon' => '',
            'rewrite' => array('slug' => $this->post_type, 'with_front' => FALSE),
            'capability_type' => 'page',
            'hierarchical' => true,
            'menu_position' => null,
            'show_in_nav_menus' => false,
            'show_in_menu' => false,
            'supports' => array('title', 'page-attributes'),

        );

        register_post_type($this->post_type, $args);
    }

    /**
     *
     */
    public function addMetaBox() {
        $this->setData();
        // remove the default
        remove_meta_box(
                'pageparentdiv', $this->post_type, 'side'
        );
        add_meta_box(
                'ib_landing_page_meta_box', //id
                'Landing Page Options', //title
                array($this, 'setMetaBoxContent'), // callback
                $this->post_type, // post type
                'normal', // context placement
                'high' // where in the context
                // $callback_args
        );
        add_meta_box(
                'ib_landing_page_template_meta', 'Page Attributes', array($this, 'setThemeTemplateOptionsMeta'), $this->post_type, 'side', 'low'
        );
    }

    /**
     *
     */
    public function setMetaBoxContent() {
        // set default template
        $template_id = 1;
        // look for the template ID in the postmeta
        if (isset($this->data['template_id']))
            $template_id = $this->data['template_id'];
        // layout string will override the postmeta (gives the ability to change existing layout)
        if (isset($_GET['layout']) && is_numeric($_GET['layout'])) {
            $template_id = $_GET['layout'];
            $this->data['template_id'] = $template_id;
        }

        // We'll use this nonce field later on when saving.
        wp_nonce_field('ib_lp_meta_box_nonce', 'lp_meta_box_nonce');
        $this->data['forms'] = Post::type('ib-contact-form')->status()->get();
        $this->data['published_pages'] = $this->getPublishedPages();
        $this->data['form_options'] = $this->load->view(self::VIEW_PATH . 'partials/landing-page-contact-form', $this->data, "blank");
        echo $this->load->view(self::VIEW_PATH . 'templates/' . $template_id . '/file', $this->data, "blank");
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

    /**
     *
     */
    public function setThemeTemplateOptionsMeta() {
        global $post;
        $data = array();
        if ($meta = get_post_custom(@$post->ID)) {
            foreach ($meta as $key => $value) {
                if ($key == 'ib_lp_page_template')
                    $data['selected'] = $value[0];
            }
        }
        $screen = get_current_screen();
        $action = $screen->action;
        //$Breadcrumb = new BreadcrumbHelper();
        $this->Breadcrumb->add($this->labels['name'], get_admin_url() . "admin.php?page=landing-page-admin");
        $this->Breadcrumb->add(($action == "add") ? $this->labels['add_new_item'] : $this->labels['edit_item']);
        $data['Breadcrumb'] = $this->Breadcrumb;
        $data['templates'] = $this->getThemeTemplates();
        
        echo $this->load->view(self::VIEW_PATH . 'partials/page-attributes', $data, "blank");
    }

    /**
     *
     */
    public function setData() {
        global $post;
        if ($meta = get_post_custom(@$post->ID)) {
            foreach ($meta as $key => $value) {
                $this->data[$key] = $value[0];
            }
        }
        $this->data['id'] = $post->ID;
        $this->data['emails'] = Email::where('email_value', '!=', '')->get();
        if (@$this->data['email_template']) {
            $this->data['email_template'] = explode("|", $this->data['email_template']);
        }
    }

    /**
     *
     */
    public function saveLandingPage() {
        global $post;

        $is_autosave = wp_is_post_autosave(@$post->ID);
        $is_revision = wp_is_post_revision(@$post->ID);
        $is_valid_nonce = ( isset($_POST['ib_lp_meta_box_nonce']) && wp_verify_nonce($_POST['ib_lp_meta_box_nonce'], basename(__FILE__)) ) ? 'true' : 'false';

        // Exits script depending on save status
        if ($is_autosave || $is_revision || !$is_valid_nonce) {
            return;
        }
        
        $template_id = '1';
        if (isset($_POST['template_id'])) {
            $template_id = $_POST['template_id'];
        }
        $templates = $this->load->config(self::VIEW_PATH . 'templates/config');
        $content = $templates->$template_id->content;
        for ($i = 1; $i <= 5; $i++) {
            $content = preg_replace('/{{ib_lp_column_' . $i . '}}/', @$_POST['ib_lp_column_' . $i], $content);
        }
        $cf = @$_POST['ib_contact_form'];
        if (!$cf)
            $cf = "";
        $content = preg_replace('/{{contact_form}}/', $cf, $content);
        $content .= '<div id="ib_lp_id" data-value="' . @$post->ID . '"></div>';
        // reset values if user doesn't want a download.

        if(!@$_POST['allow_download']){
		    $_POST['allow_download'] = "0";
		    $_POST['download_content']  = "";
		    $_POST['expires_after'] = "";
		    $_POST['download_limit'] = "";
		}
		if(!isset($_POST['dont_send_email_template'])) $_POST['dont_send_email_template'] = "0";
		// email template
		if(@$_POST['dont_send_email_template']) $_POST['email_template'] = array(); //reset email template selection.
		$_POST['email_template'] = @implode("|", @$_POST['email_template']);
		
		// Checks for input and sanitizes/saves if needed
        foreach($_POST as $key=>$value) {
            if (is_string($value) && strlen($value) && is_object($post) && isset($post->ID)) {
	            //print_debug($key.":".$value);
                if (preg_match('/\ib_lp_column/', $key)) {
                    update_post_meta($post->ID, $key, stripslashes($value));
                } else {
                    update_post_meta($post->ID, $key, sanitize_text_field($value));
                }
            }
        }

        remove_action('save_post_' . $this->post_type, array($this, 'saveLandingPage'));

        $my_post = array(
            'ID' => @$post->ID,
            'post_content' => $content,
        );

        // save wizzard step
        $Settings = new SettingsModel;
        $Settings->wizzardStepCompleted("landing_pages");



        // Update the post into the database
        wp_update_post($my_post);

        @$post->skipMetaSave = true;

        add_action('save_post_' . $this->post_type, array($this, 'saveLandingPage'));
    }

    /**
     * Adds the ability to have the Landing Page be associated with existing Theme Templates
     * @return array
     */
    public function getThemeTemplates() {
        $template_files = array();
        $template_files['default'] = 'Default Template';
        $templates = get_page_templates();
        foreach ($templates as $key => $value) {
            $template_files[$value] = $key;
        }
        return $template_files;
    }

    /**
     * @return mixed
     */
    public function loadTemplate() {
        // get the queried object which contains the information we need to
        // access our post meta data
        $query_object = get_queried_object();
        $page_template = get_post_meta($query_object->ID, 'ib_lp_page_template', true);

        // create an array of default templates
        $default_templates = array();
        $default_templates[] = 'single.php';

        // only apply our template to our CPT pages.
        if ($query_object->post_type == $this->post_type) {
            // if the page_template isn't empty, set it as the default_template
            if (!empty($page_template)) {
                $default_templates = $page_template;
            }
        }

        // locate the template and return it
        $new_template = locate_template($default_templates, false);
        return $new_template;
    }

    /**
     *
     */
    public function loadAdminPage() {
        $lps = $this->loadLandingPages();
        $data['post_type'] = $this->post_type;
        $data['lps'] = $lps;
        $data['templates'] = $this->load->config(self::VIEW_PATH . 'templates/config');

        #breadcrumbs;
        $this->Breadcrumb->add("Landing Page Management");
        $data['Breadcrumb'] = $this->Breadcrumb;
        $data['Date'] = new DateHelper;
        // check wizzard status
        if (isset($_GET['wizzard'])) {
            $path = self::VIEW_PATH . "landing-page_wizzard_instructions";
            echo $this->load->view($path, array(), "blank");
        }
        echo $this->load->view(self::VIEW_PATH . 'landing-page-list', $data);
    }

    /**
     * @return mixed
     */
    private function loadLandingPages() {
        $landing_pages = Post::getLandingPages($this->post_type);
        foreach ($landing_pages as $page) {
            $page->template = PostMeta::where('post_id', $page->ID)->where('meta_key', 'template_id')->first();
        }
        return $landing_pages;
    }

    public function getFormData() {
        $v = new Validator($_POST);
        $v->rule('required', array('form_id'));
        $v->rule('numeric', array('form_id'));
        if ($v->validate()) {
            $meta = PostMeta::where('post_id', $_POST['form_id'])->get();
            $data = array();
            foreach ($meta as $m) {
                if (in_array($m->meta_key, array('email_template', 'download_limit', 'expires_after', 'download_content', 'thank_you_message', 'thank_you_option', 'thank_you_redirect', 'dont_send_email_template'))) {
                    $data[$m->meta_key] = stripslashes($m->meta_value);
                }
            }
            if (@$data['email_template']) {
                $data['email_template'] = explode("|", $data['email_template']);
            }
            die(json_encode($data));
        }
    }

}
