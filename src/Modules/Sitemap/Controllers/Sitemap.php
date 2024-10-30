<?php
/**
* Created by sean.carrico.
* User: sean
* Date: 3/25/15
* Time: 12:45 PM
*/

namespace InboundBrew\Modules\Sitemap\Controllers;

use InboundBrew\Modules\Core\AppController;
use InboundBrew\Modules\Core\Service\Ping\PingFactory;
use InboundBrew\Modules\Sitemap\Libraries\SitemapBuilder;
use InboundBrew\Modules\Sitemap\Libraries\SitemapData;
use InboundBrew\Libraries\BreadcrumbHelper;

/**
 * Class Sitemap
 * @package InboundBrew\Modules\Sitemap\Controllers
 */
class Sitemap extends AppController{

    /**
     *
     */
    const VIEW_PATH = 'Sitemap/views/';

    /**
     *
     */
    public function __construct()
    {
        parent::init();
        if (is_admin()) {
            // ajax action
            if(@$this->active_modules['sitemap']){
	            if ($this->siteMapExists()) $this->renameOldSiteMap();
	            // check if slug changed.
	            add_action('save_post', array($this,'checkForSlugChange'));
	            add_action('admin_post_call_ib_sitemap_ping', array($this,'manualPingCall'));
			}
        } else {
            // set the query vars and redirect to virtual sitemap
            if(@$this->active_modules['sitemap']){
            	add_filter('query_vars', array($this, 'registerQueryVars'), 1, 1);
				add_filter('template_redirect', array($this, 'siteMapRedirect'), 1, 0);
				add_action('transition_post_status', array($this, 'callStatusChangePing'), 10, 3);
			}
        }
    }

    /**
     *
     */
    public function loadAdminPage()
    {
        $obj = new SitemapData();
        $obj->loadData();
        $data = $obj->data;
		$this->Breadcrumb->add("Sitemap Settings");
		$data['Breadcrumb'] = $this->Breadcrumb;
        echo $this->load->view(self::VIEW_PATH.'sitemap-admin',$data);
    }

    /**
     * @param $vars
     * @return array
     */
    public function registerQueryVars($vars) {
        $vars[] = 'xml_sitemap';
        return $vars;
    }

    /**
     * @return bool
     */
    public function siteMapExists() {
        $path = ABSPATH;
        return (file_exists($path . "sitemap.xml") || file_exists($path . "sitemap.xml.gz"));
    }

    /**
     * @param $new_status
     * @param $old_status
     * @param $post
     */
    public function callStatusChangePing( $new_status, $old_status, $post ) {
        if (($old_status == 'publish'  &&  $new_status != $old_status) || ($new_status == 'publish' && $old_status != $new_status)) {
            $array1 = get_object_vars($this->options->standard);
            $array2 = get_object_vars($this->options->custom_post_types);
            $array = array_merge($array1,$array2);
            if (!in_array($post->post_type,array_keys($array))) {
                return;
            }
            $message = '';
            $dataMap = new SitemapData();
            $result['date'] = time();
            $sitemap_url = get_site_url().'/sitemap.xml'; // TODO: make path options depending on the page type
            foreach($this->options->service as $service=>$type) {
                $result['start'] = microtime(true);
                $result['service'] = $service;
                $result['success'] = false;
                try {
                    $ping = PingFactory::build($service);
                    $ping->setUrl($sitemap_url);
                    $res = $ping->sendPing();
                    $message .= $res."<br />";
                    $result['success'] = true;
                } catch (\Exception $e) {
                    $message .= $e."<br />";
                    $this->_error($e,true);
                }
                $result['end'] = microtime(true);
                $dataMap->setPingData($result);
            }
            $dataMap->savePingResults();
            $this->_info($message,true);

        }
    }

    /**
     * Called by add_action admin_post
     * Allow for a manual call to the ping services without having to wait for post status change
     * 
     * @param boolean $redirect wether to redirect at the end of the method or not.
     * @param boolean $from_post wether the method was called from a post action.
     */
    public function manualPingCall()
    {
	    $from_post = ($_POST['action'] == "call_ib_sitemap_ping")? true : false;
	    if($from_post){
	        if (!isset( $_POST['_wpnonce']) || !wp_verify_nonce( $_POST['_wpnonce'], 'ib-send-manual-ping')) {
	            $this->_error("You do not have the permission to perform this request",true);
	            header('Location: ' . $_POST['_wp_http_referer']);
	        }
	    }
        $message = '';
        $dataMap = new SitemapData();
        $result['date'] = time();
        $options = $dataMap::getSitemapSettings();
        $sitemap_url = get_site_url().'/sitemap.xml'; // TODO: make path options depending on the page type
        if(@$options->service){
	     	foreach($options->service as $service=>$type) {
	            $result['start'] = microtime(true);
	            $result['service'] = $service;
	            $result['success'] = false;
	            try {
	                $ping = PingFactory::build($service);
	                $ping->setUrl($sitemap_url);
	                $res = $ping->sendPing();
	                $message .= $res."<br />";
	                $result['success'] = true;
	            } catch (\Exception $e) {
	                $message .= $e."<br />";
	                $this->_error($e,true);
	            }
	            $result['end'] = microtime(true);
	            $dataMap->setPingData($result);
	        }
	        $dataMap->savePingResults();
	        if($from_post){
	        	$this->_info($message,true);
				header('Location: ' . $_POST['_wp_http_referer']);
			}   
        }
    }

    /**
     * Renames old sitemap files in the blog directory
     * @return bool
     */
    public function renameOldSiteMap() {
        $path = ABSPATH;
        $res = true;
        if(file_exists($f = $path . "sitemap.xml")) {
            if (!rename($f, $path . "sitemap.backup.xml")) {
                $res = false;
            }
        }
        if(file_exists($f = $path . "sitemap.xml.gz")) {
            if(!rename($f, $path . "sitemap.backup.xml.gz")) {
                $res = false;
            }
        }
        return $res;
    }

    /**
     * Handles the plugin output on template redirection if the xml_sitemap query var is present.
     */
    public function siteMapRedirect()
    {
        /** @var $wp_query WP_Query */
        global $wp_query;
        if (!empty($wp_query->query_vars["xml_sitemap"])) {
            $wp_query->is_404 = false;
            $wp_query->is_feed = true;
            $builder = new SitemapBuilder(new SitemapData());
            header('Content-Type: text/xml; charset=utf-8');
            die($builder->buildXml());

        }
    }
    
    /**
	* check if slug has changed. Ping search engines.
	* @param int $post_id wordpress post id
	*
	* @author: Rico Celis
	* @access: public
	*
	*/
	public function checkForSlugChange($post_id){
		$nonce = @$_POST['data']['Post']['core_nonce'];
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; // don't do anything on autosave.
		if ( ! wp_verify_nonce( $nonce, "ib-core-data-nonce" ) ) return;
		$new_slug = $_POST['post_name'];
		$old_slug = $_POST['data']['Post']['old_slug'];
		$old_status = $_POST['data']['Post']['old_status'];
		// if slug changed or status changed.
		if($new_slug != $old_slug || $_POST['post_status'] != $old_status){
			// we need to ping search engines to notify of url change.
			$this->manualPingCall(false,false);
		}
	}
    
}