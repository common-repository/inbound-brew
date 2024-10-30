<?php 
namespace InboundBrew\Libraries;
use InboundBrew\Modules\Core\Service\BrewApi;

class FacebookHelper{
	private $fb;
	private $_redirect_uri;
	private $api_version = "v3.1"; // latest version
	private $graph_url = "https://graph.facebook.com/";
	private $_userId; // user's Id
	private $_clientId;
	
	// initialize facebook helper
	public function __construct($user_id = null){
		$this->_userId = $user_id;
		$this->_redirect_uri = get_admin_url()."admin.php?page=ib-admin-settings&section=oauth_callback&network=facebook";
	}
	
	public function setClientId($client_id){
		$this->_clientId = $client_id;
	}
	
	
	/**
	* load accounts user has access to post to.
	*
	* @param string $api_token facebook token.
	* @param array $fields list of fields to request
	* @return array facebook profile information.
	* @author Rico Celis
	* @access public
	*/
	public function getUserProfile($api_token,$fields = array()){
		$_fields = array('id','name');
		$fields = array_merge($_fields,$fields);
		$params = array(
			'access_token' => $api_token,
			'fields' => implode(",",$fields)
		);
		$response = $this->fetch("GET",$this->_userId,$params);
		return $user;
	}
	
	/**
	* load accounts user has access to post to.
	*
	* @param string $api_token facebook token.
	* @param boolean $long_term whether to create a long lived token or not (tokens usually expire within two hours)
	* @return array list of accounts user manages (pages,groups,etc)
	* @author Rico Celis
	* @access public
	*/
	public function getUserAccounts($api_token,$long_term = true){
		// get pages
		$accounts = array();
		$params = array('access_token' => $api_token);
		$pages = $this->fetch("GET","{$this->_userId}/accounts",$params);
		if(!empty($pages->data)){
			foreach($pages->data as $page){
				$data = array(
					'account_type' => "page",
					'name' => $page->name,
					'id' => $page->id,
					'access_token' => $page->access_token
				);
				if($long_term) $data['access_token'] = $this->createLongTermToken($page->access_token);
				$accounts[] = $data;
				
			}
		}
		// get groups
		$groups = $this->fetch("GET","{$this->_userId}/groups",$params);
		if(!empty($groups->data)){
			foreach($groups->data as $group){
				$accounts[] = array(
					'account_type' => "group",
					'name' => $group->name,
					'id' => $group->id,
					'access_token' => $api_token // copy main token
				);
			}
		}
		return $accounts;
	}
	
	/**
	* Create a long term token
	*
	* @param string $short_token short lived token
	* @return string long term token.
	* @author Rico Celis
	* @access private
	*/
	private function createLongTermToken($short_token){
		$api = new BrewApi;
		$token = $api->getSocialNetworkLongTermToken($short_token,"facebook");
		return $token;
	}
	
	/**
	* post content to a Facebook account.
	*
	* @param array $accounts account/tokens that need to be posted to.
	* @param array $post_data data for the post.
	* @return array of post ids
	* 		success => true,false
	*		post_id => int facebook post id,
	*		error => error message from facebook
	* @author Rico Celis
	* @access public
	*/
	public function post($accounts,$post_data){
		// default params
		$message = ($post_data['about_description'])? $post_data['about_description'] : get_bloginfo('description');
		$params = array(
			//'description' => $message,
			'message' => $message."\r\n\r\n".$post_data['url'],
			//'link' => 'https://inboundbrew.com',//,
			//'picture' => $post_data['about_image'],
			//'name' => $post_data['about_title'],
			//'caption' => get_bloginfo('name').": ".$post_data['about_title']
		);
		$post_ids = array();
		foreach($accounts as $account){
			$error = "";
			$post_id = 0;
			$params['access_token'] = $account['token'];
			$endpoint = "{$account['account_type_id']}/feed";
			//error_log($endpoint);
//			error_log(print_r($params, true));
			$response = $this->send("POST",$endpoint,$params);
//			error_log(print_r($response, true));
			if(@$response->error){
				$error = $response->error->message;
			}else{
				$post_id = $response->id;
			}
			$post_ids[] = array(
				'account_id' => $account['account_id'],
				'post_id' => $post_id,
				'error' => $error,
				'posted' => date("Y-m-d H:i:s")
			);
		}
		return $post_ids;
	}
	
	/**
	* get post information using post id
	*
	* @param array $post_id id of Facebook post
	* @param array $account SocialNetworkAccount record data.
	* @return indexed array of stats.
	* @author Rico Celis
	* @access public
	*/
	public function getPostInformation($post_id,$account){
		$params = array(
			'access_token' => $account['token'],
			'fields' => 'likes.summary(true),comments.summary(true),shares.summary(true)',
		);
		// get post info
		$error = false;
		$response = $this->fetch("GET","{$post_id}",$params);
		if(@$response->error){
			$error = $response->error->message;
		}else{
			$data = array(
				'likes' => $response->likes->summary->total_count,
				'comments' => $response->comments->summary->total_count,
				'shares' => @$response->shares->count);
		}
		return array(
			'error' => $error,
			'data' => @$data);
	}
	
	/**
	* send a get request to facebook graph api
	*
	* @param string $method GET or POST request
	* @param string $endpoint endpoint for request
	* @param array $params additional params for request.
	*
	* @return array result from social network
	*/
	private function fetch($method,$endpoint,$params = array()){
	    // Need to use HTTPS
	    $url = $this->graph_url.$this->api_version.'/' . $endpoint;
	    if($params) $url .= '?' . http_build_query($params);
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
			return (object) array('error' => 'Invalid Connection.');
		}
	    // Native PHP object, please
	    return json_decode($response);
	}
	
	/**
	* send a post request to facebook graph api
	*
	* @param string $method GET or POST request
	* @param string $endpoint endpoint for request
	* @param array $params additional params for request.
	* @param array $data data being posted
	*
	* @return array result from social network
	*/
	function send($method,$endpoint,$params = array(),$data = array()){
		$data_string = "";
		if($data){
			//url-ify the data for the POST
			foreach(data as $key=>$value) { $data_string .= $key.'='.$value.'&'; }
			rtrim($data_string, '&');
		}
		// default params
		$_params = array('token_url' => $token_url = get_bloginfo('url')."/".BREW_SOCIAL_API_VERIFY_TOKEN_SLUG);
		// merge params
		$params = array_merge($_params,$params);
	    // create url
	    $url = $this->graph_url.$this->api_version.'/' . $endpoint . '?' . http_build_query($params);
		// start connection
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch,CURLOPT_POST, count($data));                                                              
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                       
		    'Content-Length: ' . strlen($data_string))                                                                       
		);                                                                                                                   
		$result = curl_exec($ch);
		return json_decode($result);
	}
}?>