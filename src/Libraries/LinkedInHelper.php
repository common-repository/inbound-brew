<?php 
namespace InboundBrew\Libraries;
class LinkedInHelper{
	private $connection;
	private $_redirect_uri;
	private $_oauthToken;
	private $api_version = "v1";
	// initialize facebook helper
	public function __construct(){
		$this->_redirect_uri = get_admin_url()."admin.php?page=ib-admin-settings&section=oauth_callback&network=linked_in";
	}
	
	/**
		* load accounts user has access to post to.
		*
		* @param string $api_token linkedin token.
		* @return array list of accounts user manages (companies pages)
		* @author Rico Celis
		* @access public
		*/
	public function getUserAccounts($api_token){
		$this->_oauthToken = $api_token;
		$resource = 'companies';
		$params = array(
			'is-company-admin' => "true"
		);
		$response = $this->fetch('GET',$resource,$params);
		if(!isset($response->values)) return null;
		$accounts = array();
		foreach($response->values as $company){
			$accounts[] = array(
				'social_network' => "linked_in",
				'name' => $company->name,
				'account_type' => "company",
				'id' => $company->id,
				'access_token' => $api_token);
		}
		return $accounts;
	}
	
	/**
	* load accounts user has access to post to.
	*
	* @param string $api_token facebook token.
	* @return array facebook profile information.
	* @author Rico Celis
	* @access public
	*/
	public function getUserProfile($api_token){
		$this->_oauthToken = $api_token;
		$resource = 'people/~';
		$response = $this->fetch('GET',$resource);
		if(!isset($response->firstName)) return null;
		return  $response;
	}
	
	/**
	* get post information using post id
	*
	* @param array $post_id id of LinkedIn post
	* @param array $account SocialNetworkAccount record data.
	* @return indexed array of stats.
	* @author Rico Celis
	* @access public
	*/
	public function getPostInformation($post_id,$account){
		$this->_oauthToken = $account['token'];
		$error = "";
		switch($account['account_type']){
			case "me":
			 	$resource = "people/~/network/updates/key=".$post_id . "/update-comments";
			break;
			case "company":
				$resource = "companies/{$company_id}/updates/key=".$post_id;
			break;
		}
		$params = array(
			'scope' => "self"
		);
		$response = $this->fetch("GET", $resource,$params);
		if(!$response->message){
			$r = $response->getDecodedBody();
			$data = array(
				'likes' => $response['likes'],
				'comments' => $response['comments'],
				'shares' => $r['shares']['shares']);
		}else{
			$error = "Unable to retrieve post details.";
		}
		return array(
			'error' => $error,
			'data' => @$data);
	}
	
	/**
	* post content to a LinkedIn account.
	*
	* @param array $accounts account/tokens that need to be posted to.
	* @param array $post_data data for the post.
	* @return array of post ids
	* 		success => true,false
	*		post_id => int LinkedIn post id,
	*		error => error message from facebook
	* @author Rico Celis
	* @access public
	*/
	public function post($accounts,$post_data){
		$data = array(
			'content' => array(
				'title' => $post_data['about_title'],
				'description' => $post_data['about_description'],
				'submitted-url' => $post_data['url'],
				'submitted-image-url' => $post_data['about_image']),
			'visibility' => array(
				'code' => "anyone")
		);
		$post_ids = array();
		foreach($accounts as $account){
			$error = "";
			$this->_oauthToken = $account['token'];
			switch($account['account_type']){
				case "me":
					$resource = "people/~/shares";
				break;
				case "company":
					$company_id = $account['account_type_id'];
					$resource = "companies/{$company_id}/shares";
				break;
			}
			$response = $this->send('POST',$resource,$data);
			if(@$response->updateKey){ // if success
				$post_id = $response->updateKey;
				$update_url = $response->updateUrl;
			}else{
				$error = $response->message;
			}
			$post_ids[] = array(
					'account_id' => $account['account_id'],
					'post_id' => $post_id,
					'post_meta1' => $update_url,
					'error' => $error,
					'posted' => date("Y-m-d H:i:s")
				);
		}
		return $post_ids;
	}
	
	/**
	* fetch at request to LinkedIn API
	*
	* @param string $method GET or POST
	* @param string $resource API request url
	* @param array $params additional parameter for request.
	*/
	function fetch($method, $resource, $params = array()) {
	    $_params = array('oauth2_access_token' => $this->_oauthToken,
	                    'format' => 'json',
	              );
		$params = array_merge($_params,$params);
	    // Need to use HTTPS
	    $url = 'https://api.linkedin.com/'.$this->api_version.'/' . $resource . '?' . http_build_query($params);
	    // Tell streams to make a (GET, POST, PUT, or DELETE) request
	    $context = stream_context_create(
	                    array('http' =>
	                        array('method' => $method,
	                        )
	                    )
	                );
	    // Hocus Pocus
	    $response = @file_get_contents($url, false, $context);
		// unable to get
		if($response === FALSE) {
			return (object) array('message' => 'Error');
		}
	    // Native PHP object, please
	    return json_decode($response);
	}
	
	/**
	* send a request to LinkedIn AP
	*
	* @param string $method GET or POST
	* @param string $resource API request url
	* @param array $data data to send to API
	* @param array $params additional parameter for request.
	*/
	function send($method, $resource,$data, $params = array()) {
		// default params
		$_params = array('oauth2_access_token' => $this->_oauthToken,
	                    'format' => 'json',
	              );
	    // merge params
		$params = array_merge($_params,$params);
        // encode data
		$data_string = json_encode($data);
	    $url = 'https://api.linkedin.com/'.$this->api_version.'/' . $resource . '?' . http_build_query($params);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',                                                                                
		    'Content-Length: ' . strlen($data_string))                                                                       
		);                                                                                                                   
		$result = curl_exec($ch);
		return json_decode($result);
	}
	
} ?>