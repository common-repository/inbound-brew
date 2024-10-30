<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 11/15/15
 * Time: 10:40 AM
 */

namespace InboundBrew\Modules\Content\Controllers;

use InboundBrew\Traits\Virtual;

class ThankYou extends Virtual {

    const VIEW_PATH = 'Content/views/';

    private $data = array();

    function __construct()
    {
        parent::init();
    }

    public function LoadVirtual($config)
    {
        $this->title = isset($config->title)?$config->title:'Contact Form Thank You';
        $this->data['title'] = $this->title;
        $this->data['content'] = isset($config->virtual_content)?$config->virtual_content:'Thank You';
        $this->content = $this->load->view(self::VIEW_PATH . 'contact-thank-you',$this->data,"blank");
        add_filter('the_posts', array($this,'virtualPage'));
    }
}