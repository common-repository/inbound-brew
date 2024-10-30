<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 9/2/15
 * Time: 3:54 PM
 */

namespace InboundBrew\Modules\Sitemap\Controllers;


use InboundBrew\Modules\Core\AppController;
use InboundBrew\Libraries\BreadcrumbHelper;
use InboundBrew\Modules\Sitemap\Libraries\RobotsData;

/**
 * Class Robot
 * @package InboundBrew\Modules\Sitemap\Controllers
 */
class Robot extends AppController {

    /**
     *
     */
    const VIEW_PATH = 'Sitemap/views/';

    /**
     * @var string
     */
    private $option = 'ib-custom-robot';

    /**
     * Initialize the AppController
     * if admin set up ajax and register the menu
     * override the default robots.txt values
     */
    function __construct()
    {
        parent::init();
        if(@$this->active_modules['robots']) add_filter('robots_txt', array($this,'addCustomContent'), 10, 2);

    }

    /**
     * Load the robots admin view
     * Pass in data array to handle display and content
     */
    public function loadAdminPage()
    {
        $data = array();
        $data['content'] = RobotsData::getContent();
        $data['public'] = get_option('blog_public');
		$this->Breadcrumb->add("Manage Your Robots.txt");
		$data['Breadcrumb'] = $this->Breadcrumb;
        echo $this->load->view(self::VIEW_PATH.'robot-admin',$data);
    }

    /**
     * check if the blog is public
     * if so output either saved custom robots content or default content
     * else disallow all pages
     * @return string
     */
    public function addCustomContent()
    {
        $public = get_option( 'blog_public' );
        if ( '0' == $public ) {
            $output = "User-agent: *\n";
            $output .= "Disallow: /\n";
        } else {
            if ($output = get_option($this->option)) {
                $output = esc_attr(strip_tags($output));
            } else {
                $output = RobotsData::getContent();
            }
        }

        return $output;
    }
}