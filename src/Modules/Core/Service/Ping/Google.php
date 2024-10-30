<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 9/9/15
 * Time: 10:29 AM
 */

namespace InboundBrew\Modules\Core\Service\Ping;


class Google extends PingCrawler{

    protected $location = 'http://www.google.com/webmasters/sitemaps/ping?sitemap=';

    public function __construct()
    {
    }

    public function sendPing()
    {
        $response = wp_remote_get($this->location.$this->getUrl());
        if(is_wp_error($response)) {
            $errs = $response->get_error_messages();
            $errs = htmlspecialchars(implode('; ', $errs));
            throw new \Exception('WP HTTP API Web Request failed: ' . $errs);
        }

        return $response['body'];
    }
}