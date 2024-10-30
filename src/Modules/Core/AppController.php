<?php 
namespace InboundBrew\Modules\Core;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use InboundBrew\Libraries\Load;
use InboundBrew\Libraries\BreadcrumbHelper;

class AppController{
	var $transient_name = "ib_admin_notice";
	var $transient_expiration = 60; // transient will expire in 60 seconds.
	var $allowedPostTypes = array("page","post","ib-landing-page");
	var $ib_dashboard_post_type = "inboundbrew";
	var $active_modules;
	private $partials_path;
    protected $load;

	public function init(){
		$this->active_modules = get_option(BREW_ACTIVE_MODULES_OPTION);
		if (!defined('DOING_AJAX')) { // ajax call
			add_action('admin_notices',array($this,"checkForAdminNotice"),1);
		}
        $this->load = Load::getInstance();
		$this->Breadcrumb = new BreadcrumbHelper;
		if(@$_GET['page'] != $this->ib_dashboard_post_type)
		$this->Breadcrumb->add("<span class=\"fa fa-line-chart\"></span> Dashboard","admin.php?page=".$this->ib_dashboard_post_type);
		//add_action('init',array($this,'checkForAdminNotice'),1);
		if(is_admin()){
	        add_action('admin_enqueue_scripts', array($this,'addGlobalAdminScripts'));
		}
    }
    
    /**
	* load global admin scripts
	*
	* @author Rico Celis
	* @access public
	*/
    public function addGlobalAdminScripts(){
		// load scripts
		wp_enqueue_script('ib_navigation-jquery-js', BREW_MODULES_URL.'Core/assets/js/ib_navigation.jquery.js',array('jquery'), BREW_ASSET_VERSION, true );
		wp_enqueue_script('jquery-inline-education-js', BREW_MODULES_URL.'Core/assets/js/jquery.inline_education.js',array('jquery'), BREW_ASSET_VERSION, true );
		wp_enqueue_style('ib-roboto-css',"https://fonts.googleapis.com/css?family=Roboto:400,300,500,700", array(), BREW_ASSET_VERSION);
		// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
         wp_localize_script( 
             'ib_navigation-jquery-js', 'ibNavAjax',
             array( 
                 'ajaxurl' => admin_url( 'admin-ajax.php' ),
                 'ibNavNonce' => wp_create_nonce( 'ib-nav-nonce' ),
            )
        );
        wp_localize_script( 
             'jquery-inline-education-js', 'ibInlineEducationAjax',
             array( 
                 'ajaxurl' => admin_url( 'admin-ajax.php' ),
                 'blog_url' => BREW_PLUGIN_BLOG_URL,
            )
        );
    }

	/**
	* check if any notifications need to be displayed
	*
	* @author Rico Celis
	* @access public
	*/
	public function checkForAdminNotice(){
		$notice = get_transient($this->transient_name);
		if(!empty($notice)){
			$this->displayNotice($notice);
		}
	}
	
	/**
	* display a success notification.
	* @param $text string for admin notice
	* @param $use_transient bool to override ajax only inclusion
	* @author Rico Celis
	* @access public
	*/
	public function _confirm($text,$use_transient = false){
		$this->handle(array(
			'class' => "updated",
			'text' => $text
		),$use_transient);
	}
	
	/**
	* display a error notification next time the page redirects.
	* @param $text string for admin notice
	* @param $use_transient bool to override ajax only inclusion
	* @param $icon fa icon to use in string
	* @author Rico Celis
	* @access public
	*/
	public function _error($text,$use_transient = false,$icon = null){
		$this->handle(array(
			'class' => "error",
			'text' => $text,
			'icon' => $icon
		),$use_transient);
	}

    /**
     * display a info notification next time the page redirects.
     * @param $text string for admin notice
     * @param $use_transient bool to override ajax only inclusion
     * @param $icon fa icon to use in string
     * @author Rico Celis
     * @access public
     */
    public function _info($text,$use_transient = false,$icon = null){
        $this->handle(array(
            'class' => "notice-info",
            'text' => $text,
            'icon' => $icon
        ),$use_transient);
    }
	
	/**
	* handle when notification should be displayed
	* if AJAX call wait for next time page redirects.
	* @param $notice array
	*		 class = 'div class'
	*		 text = 'message to display'
	* @param $use_transient bool override ajax only inclusion
	* @author Rico Celis
	* @access public
	*/
	private function handle($notice,$use_transient = false){
		if ((defined('DOING_AJAX') && DOING_AJAX) || $use_transient) { // ajax call
			set_transient( $this->transient_name, $notice, $this->transient_expiration );
		}else{
			$this->displayNotice($notice);
		}
	}
	
	/**
	* a notification has been found and needs to be displayed.
	* @param $notice array
	*		class = 'div class'
	*		text = 'message to display'
	*
	* @author Rico Celis
	* @access public
	*/
	public function displayNotice($notice){
		$icon = (@$notice['icon'])? "<span class=\"fa fa-{$notice['icon']}\"></span> ":  "";
		echo "<div class=\"{$notice['class']} notice is-dismissible\" id=\"message\">
		<p><strong>{$icon} {$notice['text']}</strong></p>
		<button class=\"notice-dismiss\" type=\"button\"><span class=\"screen-reader-text\">Dismiss this notice.</span></button></div>";
		// delete it so it will only display once
		delete_transient($this->transient_name);
	}
	
	/**
	* load redirect view 
	* @param string $url url to go to.
	* @author Rico Celis
	* @access public
	*/
	public function jsRedirect($url){
		echo $this->load->view(BREW_PLUGIN_VIEWS_PATH.'js_redirect',array(
			'redirect_url' => $url
		),"blank");
	}
	
	/**
     * @return array
     */
    public function getFonts()
    {
        $fonts = array(
                "inherit"         => "Inherit from Theme",
                "Arial"           => "Arial / Helvetica",
                "Comic Sans MS"   => "Comic Sans",
                "Courier New"     => "Courier New",
                "Georgia"         => "Georgia",
                "Impact"          => "Impact",
                '"Tahoma"'        => "Tahoma",
                "Times New Roman" => "Times New Roman",
                "Trebuchet MS"    => "Trebuchet",
                "Verdana"         => "Verdana",
        );
        return $fonts;
    }
}
