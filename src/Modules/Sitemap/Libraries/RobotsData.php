<?php

namespace InboundBrew\Modules\Sitemap\Libraries;

/**
* Class RobotsData
* @package InboundBrew\Modules\Sitemap\Libraries
*/
class RobotsData {
	/* Get robots content
	*
	* @return string Robots content
	* @author Rico Celis
	* @access static
	*/
    public static function getContent(){
	    if ($content = get_option("ib-custom-robot")) {
            return $content;
        } else {
            $site_url = parse_url( site_url() );
            $path = ( !empty( $site_url['path'] ) ) ? $site_url['path'] : '';
            $output = "User-agent: *\n";
            $output .= "Disallow: $path/wp-admin/\n";
            $output .= "Disallow: $path/wp-includes/\n";
            $output .= "Disallow: $path/wp-content/\n";
            $output .= "Sitemap: \n" . site_url() . "/sitemap.xml";
        }
        return $output;
    }
    
    /* save settings.
	*
	* @return boolean true when complete
	* @author Rico Celis
	* @access static
	*/
    public static function saveSettings($data){
	    if (@$data['blog_public']) {
           	update_option('blog_public', 0);
        } else {
            update_option('blog_public', 1);
        }
        update_option("ib-custom-robot", $data['content']);
        return true;
    }
 }