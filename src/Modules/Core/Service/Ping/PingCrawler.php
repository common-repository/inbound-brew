<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 9/9/15
 * Time: 10:17 AM
 */

namespace InboundBrew\Modules\Core\Service\Ping;


abstract class PingCrawler {

    private $url;
    protected $location = null;

    public function __construct()
    {
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getLocation()
    {
        return $this->location;
    }

    abstract public function sendPing();
}