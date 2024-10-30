<?php 
// name space
namespace InboundBrew\Libraries;
// load classess
use Abraham\TwitterOAuth\TwitterOAuth;
use InboundBrew\Modules\Settings\Models\SocialNetworkToken;

class TwitterHelper{
	private $host = "https://api.inboundbrew.com/passthrough/twitter_api.php?action=";
	private $twitter;
	
	// initialize facebook helper
	public function __construct(){
		
	}
	
	/**
	* post content to a Twitter account.
	*
	* @param array $accounts account/tokens that need to be posted to.
	* @param array $post_data data for the post.
	* @return array of post ids
	* 		success => true,false
	*		post_id => int facebook post id,
	*		error => error message from twitter
	* @author Rico Celis
	* @access public
	*/
	/**
	* post content to a LinkedIn account.
	*
	* @param array $accounts account/tokens that need to be posted to.
	* @param array $post_data data for the post.
	* @return array of post ids
	* 		success => true,false
	*		post_id => int Linked In post id,
	*		error => error message from facebook
	* @author Rico Celis
	* @access public
	*/
	public function post($accounts,$post_data){
		$status = $post_data['about_description'] . " " . $post_data['url'];
		$post_ids = array();
		foreach($accounts as $account){
			$data = array(
				'access_token' => $account['token'],
				'access_token_secret' => $account['meta1'],
				'status' => $post_data['about_description'],
				'url' => $post_data['url'],
			);
			// image image.
			if($post_data['about_image']) $data['image'] = $post_data['about_image'];
			// send
			$context = $this->request("POST","post",$data);
			$error = "";
			$post_id = 0; // not send
			if(@$context->error){
				$error = $context->error;
			}else{
				$post_id = $context->id_str;
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
	* @param int $tweet_id id of Twitter post
	* @param string $access_token access token for account post belongs to.
	* @return indexed array of stats.
	* @author Rico Celis
	* @access public
	*/
	public function getPostInformation($tweet_id,$account){
		$data = array(
			'access_token' => $account['token'],
			'access_token_secret' => $account['meta1'],
			'tweet_id' => $tweet_id
		);
		// Post Information
		$tweet = $this->request("POST","tweet",$data);
		$error = false;
		if(!$tweet->error){
			$data = array(
				'favorited' => $tweet->favorite_count,
				'retweeted' => $tweet->retweet_count
			);
		}else{
			$error = $tweet->error;
		}
		return array(
			'error' => $error,
			'data' => @$data);
	}
	
	/**
	* send a request to LinkedIn AP
	*
	* @param string $method GET or POST
	* @param string $endpoint API endpoint
	* @param array $data data to send to API
	* @param array $params additional parameter for request.
	*/
	function request($method, $endpoint,$data, $params = array()) {
		// default params
		$_params = array('token_url' => get_bloginfo('url')."/".BREW_SOCIAL_API_VERIFY_TOKEN_SLUG);
	    // merge params
		$params = array_merge($_params,$params);
        // encode data
		$data_string = "";
		if($data){
			//url-ify the data for the POST
			foreach($data as $key=>$value) { $data_string .= $key.'='.$value.'&'; }
			rtrim($data_string, '&');
		}
		// intialize url
		$url = $this->host.$endpoint . '&' . http_build_query($params);
		// curl request
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
}