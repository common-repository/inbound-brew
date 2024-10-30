<?php
/**
 * Created by Rico Celis.
 * Date: 10/12/15
 * Time: 10:36 AM
 */

namespace InboundBrew\Modules\Settings\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\Paginator;
// libraries
use InboundBrew\Libraries\DateHelper;
use InboundBrew\Libraries\FacebookHelper;
use InboundBrew\Libraries\TwitterHelper;
use InboundBrew\Libraries\LinkedInHelper;
// classes
use InboundBrew\Modules\Settings\Models\SocialNetworkPostSettingAccount;
use InboundBrew\Modules\Settings\Models\SocialNetworkAccount;
use InboundBrew\Modules\Settings\Models\SocialNetworkPostRecord;

class SocialNetworkPostSetting extends Eloquent {

    protected $softDelete = true;
    protected $table = 'ib_social_network_post_settings';
    protected $primaryKey = 'post_setting_id';
    protected $dates = array('deleted_at');
    protected $posted_status = "posted";
    protected $not_posted_status  = "not";
    protected $post_pending_status  = "pending";
    protected $error_posted_status  = "error";
    private $ib_social_about_custom_key = "_inboundbrew_social_about_custom_";
    private $about_this_page_meta_key = "_inboundbrew_about_this_page";
    
    /**
	* load post from database
	* paginate based on GET variables in url.
	*
	* @param string $network what social network to use
	* @param array $options  list of possible options
	* @return Eloquent Paginator Instance (will all results for this page)
	* @author Rico Celis
	* @access public
	*/
	public function getList($network,$options = array()){
		$_defaults = array(
			'wp_post_id' => 0, // WordPress Post ID (if linked to post)
			'as_array' => false, // return results as array instead of Eloquent models
			'dates_as_array' => true, // convert dates to human arrays,
			'add_accounts' => false // add Social Network Settings Accounts
		);
		$options = array_merge($_defaults,$options);
		$match = array("social_network"=>$network,"wp_post_id" => $options['wp_post_id']);
		
		$results = self::where("social_network",$network)->where("wp_post_id",$options['wp_post_id'])->orderBy("post_setting_id","ASC")->get();
		if($options['as_array'] || $options['add_accounts']){ // want PHP array back?
			$arr = $results->toArray();
			if($options['add_accounts']){
				$SocialNetworkPostSettingAccount = new SocialNetworkPostSettingAccount;
				foreach($arr as $index=>$values){
					$arr[$index]['accounts'] = $SocialNetworkPostSettingAccount->getList($values['post_setting_id'],array('ids_only'=>true));
				}
			}
			return  $arr;
		}else{
			return $results;
		}
	}
	
	/**
	* update posting settings
	* if id is passed it will update
	* if id and is_deleted is passed it will delete setting.
	*
	* @param string $network what social network to use
	* @param array $settings settings sent to $_POST
	* @param array $options optional to update settings.
	* @return boolean true when done.
	* @author Rico Celis
	* @access public
	*/
	public function updateSettings($network,$settings,$options = array()){
		$_defaults = array(
			'wp_post_id' => 0
		);
		$options = array_merge($_defaults,$options);
		if(empty($settings)) return;
		$DateHelper = new DateHelper;
		$SocialNetworkPostSettingAccount = new SocialNetworkPostSettingAccount;
		foreach($settings as $setting){
			$sid = $setting['post_setting_id'];
			if($sid) $postSetting = self::find($sid);
			if(!$options['wp_post_id']){ // not linked to post
				// manage possible existing records
				if($sid){
					// want to delete setting
					if($setting['is_deleted']){
						$postSetting->delete();
						continue;
					}
				}else{
					if($setting['is_deleted']) continue; // new deleted skip
					$postSetting = new SocialNetworkPostSetting;
					if($options['wp_post_id']) $postSetting->wp_post_id = $options['wp_post_id'];
				}	
			}else{ // linked to post
				if($sid){
					if($postSetting->posting_status == $this->posted_status) continue; // if setting was already posted skip.
				}else{
					if($setting['is_deleted']) continue; // new deleted skip
					$postSetting = new SocialNetworkPostSetting;
				}
				$postSetting->wp_post_id = $options['wp_post_id'];
			}
			// time
			$time = "00:00";
			if($setting['when_to_post'] == "on")
				$time = $setting['when_to_post_time'];
			
			// if option is date convert
			if($setting['when_to_post'] == "on" && $setting['when_to_post_on_option'] == "date"){
				$setting['when_to_post_on_option_value'] = $DateHelper->date_humanToMysql($setting['when_to_post_on_option_date']);
 			}
 			// save post settings (if linked to post)
			$setting['when_to_post_time'] = $time;
			if($options['wp_post_id']){
				// save posting status
				$postSetting->posting_status = $this->not_posted_status;
				$postSetting->post_at = $this->postAt($setting);
			}
			$postSetting->social_network = $network;
			$postSetting->when_to_post = $setting['when_to_post'];
			$postSetting->when_to_post_on_option = ($setting['when_to_post'] == "on")? $setting['when_to_post_on_option'] : "";
			$postSetting->when_to_post_on_option_value = ($setting['when_to_post'] == "on" && ($setting['when_to_post_on_option'] == "days" || $setting['when_to_post_on_option'] == "date"))? $setting['when_to_post_on_option_value'] : "" ;
			$postSetting->when_to_post_time = $time;
			$postSetting->save();
			// update accounts
			$setting_id = $postSetting->post_setting_id;
			$SocialNetworkPostSettingAccount::where('posting_setting_id',$setting_id)->delete();
			foreach($setting['accounts'] as $account_id){
				$settingAccount = new SocialNetworkPostSettingAccount;
				$settingAccount->posting_setting_id = $setting_id;
				$settingAccount->network_account_id = $account_id;
				$settingAccount->social_network = $network;
				$settingAccount->save();
			}			
		}
		return true;
	}
	
	/**
	* using post data, determine when to post to social network
	*
	* @param array $settings settings sent to $_POST
	* @return timestamp.
	* @author Rico Celis
	* @access public
	*/
	private function postAt($setting){
		$post_at = "0000-00-00 00:00:00"; // now
		$format = "Y-m-d H:i";
		switch($setting['when_to_post']){
			case  "now":
				return $post_at;
			break;
			case "on":
				// post on a certain day
				$value = $setting['when_to_post_on_option_value'];
				$post_time = $setting['when_to_post_time'];
				$now = strtotime(date("Y-m-d") . " " . $post_time);
				switch($setting['when_to_post_on_option']){
					// number of days after.
					case "date":
						$stamp = $value . " " . $post_time; // return this if for date
					break;
					case "days":
						$time = strtotime("+{$value} day {$post_time}",$now);
						$stamp = date($format,$time);
					break;
					default: // next [week day]
						$string_format = "{$setting['when_to_post_on_option']} {$post_time}";
						$time = strtotime($string_format,$now);
						$stamp = date($format,$time);
					break;
				}
				$DateHelper = new DateHelper;
				return $DateHelper->toGMT($format,$stamp); // convert from local time to GMT
			break;
		}		
	}
	
	/**
	* user wants to replace current default settings for network
	*
	* @param string $network what social network to use
	* @param array $settings settings sent to $_POST
	* @return boolean true when done.
	* @author Rico Celis
	* @access public
	*/
	public function overwriteDefaults($network,$settings){
		// delete previous settings
		self::where('social_network',$network)->where('wp_post_id',0)->delete();
		// loop through settings
		$new = array();
		foreach($settings as $index=>$setting){
			// setting with actual dates cannot be default.
			if($setting['when_to_post_on_option'] == "date") continue;
			// remove post id
			$setting['post_setting_id'] = "";
			$new[] = $setting;
		}
		return $this->updateSettings($network,$new);
	}
	
	/**
	* get a list of postings that must need to be done.
	* @param string $network what social network to query on
	* @param boolean $post_now true if we're looking for things that need to be posted right after publish
	* @return Eloquent results object
	* @author Rico Celis
	* @access public
	*/
    public function needToPost($network,$post_now = false){
		$now = date("Y-m-d H:i:s");
		$when_to_post = ($post_now)? "now":"on"; 
		$results = self::where("posting_status",$this->not_posted_status)->where("social_network",$network)->where("when_to_post",$when_to_post)->where("post_at","<=",$now)->get();
		return $results;
    }
    
    /**
	* loop through postings and send them to the network
	* @param Eloquent results Object $postings all SocialNetworkPostSetting that need to be posted to social networks
	* @return boolean true when done.
	* @author Rico Celis
	* @access public
	*/
    public function postToNetwork($postings){
	    if(!$postings->count()) return ;
	    
	    $FacebookHelper = new FacebookHelper;
	    $TwitterHelper = new TwitterHelper;
	    $LinkedInHelper = new LinkedInHelper;
		$PostSettingAccount = new SocialNetworkPostSettingAccount;
		$SocialNetworkAccount = new SocialNetworkAccount;
	    
		$statues = array("publish","private");
		
	    //save them all as pending so they don't get picked up by another process, like when FB scrapes.
	    foreach($postings as $postSetting){
	    	$status = get_post_status($postSetting->wp_post_id);
	    	if(!in_array($status, $statues)) continue;
	    	$postSetting->posting_status = $this->post_pending_status;
	    	$postSetting->save();
	    }
	    foreach($postings as $postSetting){
	    	$status = get_post_status($postSetting->wp_post_id);
		    if(!in_array($status, $statues)) continue;
		    $network = $postSetting->social_network;
		    $post_data = get_post_meta($postSetting->wp_post_id, $this->ib_social_about_custom_key . $network);
		    $post_data = @$post_data[0];
		    $post_data['url'] = get_permalink($postSetting->wp_post_id);
		    // if using About This Page information
		    if($post_data['use'] == "about_this_page"){
			    $about = get_post_meta($postSetting->wp_post_id, $this->about_this_page_meta_key);
			    $about = @$about[0];
			    $post_data['about_image'] = $about['ib_meta_image'];
			    $post_data['about_description'] = $about['ib_meta_description'];
		    }
		    #test (overwrite image if local host)
		    $site = get_site_url();
		    if($site == "http://localhost") $post_data['about_image'] = "http://inboundbrew.com/wp-content/uploads/2015/08/elixir.jpg";
		    $a_ids = $PostSettingAccount->getList($postSetting->post_setting_id,array('as_array'=>true,'ids_only'=>true));
		    if(empty($a_ids)) continue;
		    $accounts = $SocialNetworkAccount->whereIn('account_id',$a_ids)->get()->toArray();
		    switch($network){
			    case "facebook":
			    	$postings = $FacebookHelper->post(
				    	$accounts,
				    	$post_data
			    	);
			    break;
			    case "twitter":
			    	$postings = $TwitterHelper->post(
				    	$accounts,
				    	$post_data
			    	);
			    break;
			    case "linked_in":
			    	$postings = $LinkedInHelper->post(
				    	$accounts,
				    	$post_data
			    	);
			    break;
		    }
		    $error = false;
		    // save a record of posts ids.
	    	foreach($postings as $record){
		    	if(!empty($record['error'])) $error = true;
		    	$postRecord = new SocialNetworkPostRecord;
		    	$postRecord->social_network = $network;
		    	$postRecord->post_setting_id = $postSetting->post_setting_id;
		    	$postRecord->social_network_account_id = $record['account_id'];
		    	$postRecord->post_id = $record['post_id'];
		    	$postRecord->error_message = $record['error'];
		    	if(@$record['post_meta1']) $postRecord->post_meta1 = $record['post_meta1'];
		    	$postRecord->save(); // save the record.
	    	}
	    	// update post setting record to show activity.
	    	$postSetting->posting_status = ($error)? $this->error_posted_status:$this->posted_status;
	    	$postSetting->posted_title = $post_data['about_title'];
	    	$postSetting->posted_image = $post_data['about_image'];
	    	$postSetting->posted_description = $post_data['about_description'];
	    	$postSetting->posted_url = $post_data['url'];
	    	$postSetting->save();
	    }
    }
    
    /**
	* return list of items that were posted recently
	* @param int $limit number of results to get.
	* @param array $options any additional options needed for the query
	* @return Eloquent results Object (or array, based on $options) of all SocialNetworkPostSetting
	*
	* @author Rico Celis
	* @access public
	*/
    public function postedRecently($limit = 10,$options = array()){
	    $_defaults = array(
		    'as_array' => false, // return array instead of eloquent objects
		    'link_post' => false, // return WP post data.
	    );
	    $options = array_merge($_defaults,$options);
		$results = self::whereIn("posting_status",array("error","posted"))->orderBy("updated_at","DESC")->take($limit)->get();
		if($options['as_array']){
			$results = $results->toArray();
			if(count($results)){
				foreach($results as $index=>$setting){
					$wp_id = $setting['wp_post_id'];
					$results[$index]['post_title'] = get_post_field("post_title",$wp_id); 
				}
			}
		}
		return $results;
	}
	
	 /**
	* return list of the next items that will be posting.
	* @param int $limit number of results to get.
	* @param array $options any additional options needed for the query
	* @return Eloquent results Object (or array, based on $options) of all SocialNetworkPostSetting
	*
	* @author Rico Celis
	* @access public
	*/
    public function postingSoon($limit = 10,$options = array()){
	    $_defaults = array(
		    'as_array' => false, // return array instead of eloquent objects
		    'link_post' => false, // return WP post data.
	    );
	    $options = array_merge($_defaults,$options);
		$results = self::where("posting_status","not")->orderBy("post_at","ASC")->take($limit)->get();
		if($options['as_array']){
			$results = $results->toArray();
			if(count($results)){
				foreach($results as $index=>$setting){
					$wp_id = $setting['wp_post_id'];
					$results[$index]['post_title'] = get_post_field("post_title",$wp_id); 
				}
			}
		}
		return $results;
	}
	
	/**
	* get a history of postings
	* @param string $network optional parameter for query
	* @param string $order field to sort by
	* @param string $direction direction in which the sorting should be done "ASC" or "DESC"
	* @param string $wp_page value for the current admin page
	* @return Eloquent Paginator Instance (will all results for this page)
	* @author Rico Celis
	* @access public
	*/
	public function history($network=null,$order,$direction,$appends = array()){
		$query = self::whereIn("posting_status",array('posted','error'));
		if($network) $query->where("social_network",$network);
		$results = $query->orderBy($order,$direction)->get();
		return $results;
	}
 }?>