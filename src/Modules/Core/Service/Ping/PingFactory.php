<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 9/9/15
 * Time: 10:03 AM
 */

namespace InboundBrew\Modules\Core\Service\Ping;

use Exception;

class PingFactory {

    public static function build($service_type)
    {
        $service = "InboundBrew\\Modules\\Core\\Service\\Ping\\". ucwords($service_type);
        try {
            return new $service();
        } catch (Exception $e) {
            throw new Exception("Invalid service type given. $service");
        }
    }
}