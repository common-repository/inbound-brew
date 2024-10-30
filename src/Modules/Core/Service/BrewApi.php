<?php


namespace InboundBrew\Modules\Core\Service;

class BrewApi {

    public function __construct() {

    }

    /**
     * Create a long term token
     *
     * @param string $short_token short lived token
     * @param string $network which social network
     * @return string long term token.
     * @author Rico Celis
     * @access private
     */
    public function getSocialNetworkLongTermToken($short_token, $network) {
        $endpoint = "network_long_term_token";
        $params = array(
            'network' => $network,
            'access_token' => $short_token
        );
        $response = $this->fetch($endpoint, $params);
        return $response->access_token;
    }

    /**
     * send a get request to facebook graph api
     *
     * @param string $endpoint endpoint for request
     * @param array $params additional params for request.
     *
     * @return array result from social network
     */
    private function fetch($endpoint, $params = array()) {
        $_params = array(
            'ping_url' => get_bloginfo('url') . "/" . BREW_SOCIAL_API_VERIFY_TOKEN_SLUG,
        );
        if ($params)
            array_merge($_params, $params);
        $url = BREW_SOCIAL_API_REQUEST_URL . "?action={$endpoint}&" . http_build_query($params);
        // Tell streams to make a (GET, POST, PUT, or DELETE) request
        $context = stream_context_create(
                array('http' =>
                    array('method' => "GET",
                    )
                )
        );
        // Hocus Pocus
        $response = @file_get_contents($url, false, $context);
        // unable to get
        if ($response === FALSE) {
            return (object) array('message' => 'Error');
        }
        $obj = json_decode($response);
        // Native PHP object, please
        return $obj;
    }

    /**
     * Custom function to call the api
     * @param string $url
     * @param string $method
     * @param array $request_data
     */
    public function callApi($url, $method, $request_data, $headers = array(), $type = "json") {
        $token = @$headers['token'];
        $domain = @$headers['domain'];

        $url = BREW_API_DOMAIN . '/' . $url;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POSTFIELDS => ($type == "json") ? json_encode($request_data) : http_build_query($request_data),
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => !empty($headers) ? array("Accept: application/json",
                "Authorization: token={$token}, domain={$domain}") : array("Accept: application/json", "Content-Type: application/json"),
        ));
        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result);
    }

}


?>
