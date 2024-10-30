<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 7/14/15
 * Time: 12:55 PM
 */

namespace InboundBrew\Modules\SEO\Controllers;

use InboundBrew\Modules\Core\AppController;
use InboundBrew\Modules\Settings\Models\SettingsModel;
// helpers
use InboundBrew\Libraries\FormHelper;


/**
 * Class Keyword
 * @package InboundBrew\Modules\SEO\Controllers
 */
class Meta extends AppController{

    /**
     *
     */
    const VIEW_PATH = 'SEO/views/';
	private $about_this_page_meta_key = "_inboundbrew_about_this_page";
    /**
     *
     */
    public function __construct()
    {
        parent::init();
        $this->init();
    }

    /**
     * adds all the WP actions and filters
     */
    public function init()
    {
        if (is_admin()) {
	        // load scripts
			add_action('admin_enqueue_scripts', array($this,'addAdminScripts'));
			// add side meta box
			add_action('add_meta_boxes', array($this, 'addMetaBox'));
			add_action('save_post', array($this,'saveAboutThisPageMetaboxData'));
        }else{ // front end
	        // hook into wp_head.
	        add_action('wp_head', array($this,'addMetaTags'));
	        
        }
    }

    /**
     * Enqueues Admin only JS files
     */
    public function addAdminScripts()
    {
	    if(!in_array(get_post_type(),$this->allowedPostTypes)) return;
		wp_enqueue_script('ib-metabox-meta', BREW_MODULES_URL.'SEO/assets/js/metabox-meta.jquery.js', array('jquery'), BREW_ASSET_VERSION, true );
		wp_enqueue_style('ib-meta-css', BREW_MODULES_URL.'SEO/assets/css/meta.css', array(), BREW_ASSET_VERSION);
    }
    
    /**
	* add custom meta tags
	*
	* @author Rico Celis
	* @access public
	*/
    public function addMetaTags(){
	    if(!in_array(get_post_type(),$this->allowedPostTypes)) return;
	    $post_id = get_the_ID();
		// meta data
		$post_data = get_post_meta($post_id,$this->about_this_page_meta_key);
	    $description = @$post_data[0]['ib_meta_description'];
	    $image = @$post_data[0]['ib_meta_image'];
	    $page_title = get_the_title( $post_id );
	    $url = get_permalink($post_id);
	    $site = get_bloginfo("name");
	    // no description
/*	    if(empty($description)){
		    $description = get_bloginfo('description'); // use blog description.
	    }*/
	    // get settings.
	    $Setting = new SettingsModel;
	    $settings = $Setting->loadSettings();
	    $twitter_name = $settings->social_name_twitter;
	    // twitter
	     echo "
		 <!-- Twitter Card data -->
		<meta name=\"twitter:card\" content=\"summary\">
		<meta name=\"twitter:site\" content=\"@{$twitter_name}\">
		<meta name=\"twitter:title\" content=\"".addslashes($page_title)."\">";
		if($description){
			echo "<meta name=\"twitter:description\" content=\"".addslashes($description)."\">";
		}
		if($image){
			echo "<meta name=\"twitter:image\" content=\"{$image}\">";
		}
		echo "<!-- Open Graph data -->
		<meta property=\"og:title\" content=\"".addslashes($page_title)."\" />
		<meta property=\"og:type\" content=\"article\" />
		<meta property=\"og:url\" content=\"{$url}\" />";
		if($image){
			echo "<meta property=\"og:image\" content=\"{$image}\" />";
		}
		if($description){
			echo "<meta property=\"og:description\" content=\"".addslashes($description)."\" />";
		}
		echo "<meta property=\"og:site_name\" content=\"".addslashes($site)."\" />
		<!-- Schema.org markup for Google+ --> 
		<meta itemprop=\"name\" content=\"".addslashes($page_title)."\">";
		if($description){
			echo "<meta itemprop=\"description\" content=\"".addslashes($description)."\">";
		}
		if($image){
			echo "<meta itemprop=\"image\" content=\"{$image}\">";    
		}
    }
    
    /**
     * add metabox to add About This Page information
     */
     /**
     * Adds side meta box to selected post types
     * @param $postType post type for page
     */
    public function addMetaBox($postType)
    {
	    if(!in_array($postType,$this->allowedPostTypes)) return;
	    $obj = get_post_type_object($postType);
        // add metabox
        $title = 'About This '. $obj->labels->singular_name;
        add_meta_box(
            'ib_about_this_page',
            $title,
            array($this,'setAboutThisPageMetaBox'),
            $postType,
            'side',
            'high'
        );
    }
     
	/**
     * create metabox to edit About this Page data (Meta data).
     */
    public function setAboutThisPageMetaBox(){
	    $data['post'] = "";
	    $data['post_type_object'] = get_post_type_object(get_post_type());
	    $Form = new FormHelper;
	    $post_data = get_post_meta(get_the_ID(),$this->about_this_page_meta_key);
	    if(empty($post_data)){
		    /*$post_data = array(
			    'ib_meta_description' => get_bloginfo('description')
		    );*/
	    }else{
		    $post_data = $post_data[0];
	    }
	    $Form->data = array('Meta' => $post_data);
	    $data['Form'] = $Form;
	    echo $this->load->view(self::VIEW_PATH . 'metabox_about-this-page', $data,"blank");
    }
    
    /**
     * save data in metabox.
     */
    public function saveAboutThisPageMetaboxData($post_id){
	    if ( ! isset( $_POST['inboundbrew_meta_box_nonce'] ) ) return; // Check if our nonce is set.
		if ( ! wp_verify_nonce( $_POST['inboundbrew_meta_box_nonce'], 'ib_save_about_this_page_data' ) ) return; // Verify that the nonce is valid.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; // don't do anything on autosave.
		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && (in_array($_POST['post_type'],$this->allowedPostTypes))) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) return;
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) return;
		}
		/* OK, it's safe for us to save the data now. */
		$my_data = @$_POST['data']['Meta'];
		if(!$my_data) return;
		update_post_meta( $post_id, $this->about_this_page_meta_key, $my_data );
	}
}