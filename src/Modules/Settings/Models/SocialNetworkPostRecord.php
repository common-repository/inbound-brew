<?php
/**
 * Created by Rico Celis.
 * Date: 10/29/15
 * Time: 10:00 PM
 */

namespace InboundBrew\Modules\Settings\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;
use InboundBrew\Libraries\FacebookHelper;
use InboundBrew\Libraries\TwitterHelper;
use InboundBrew\Libraries\LinkedInHelper;
use InboundBrew\Modules\Settings\Models\SocialNetworkAccount;

class SocialNetworkPostRecord extends Eloquent {
	protected $table = 'ib_social_network_post_records';
    protected $primaryKey = 'id';
    
    /**
	* get a list of all post setting records.
	*
	* @param int $post_setting_id SocialNetworkPostSetting record id.
	* @return array list of records for post setting.
	* @author Rico Celis
	* @access public
	*/
    public function getPostSettingRecords($post_setting_id,$options = array()){
	    $_defaults = array(
		    'get_social_stats' => false, // get social stats
		    'accounts' => array()
	    );
	    $options = array_merge($_defaults,$options);
	    $results = self::where("post_setting_id","=",$post_setting_id)->get()->toArray();
	    if($results && $options['get_social_stats']){
		    $SocialNetworkAccount = new SocialNetworkAccount();
		    $network = $results[0]['social_network'];
		    switch($network){
			    case "facebook":
			    	$Helper = new FacebookHelper;
			    break;
			    case "twitter":
			    	$Helper = new TwitterHelper;
			    break;
			    case "linked_in":
			    	$Helper = new LinkedInHelper;
			    break;
		    }
		    foreach($results as $index=>$record){
			    $activeAccount = false;
			    foreach($options['accounts'] as $account){
					if($record['social_network_account_id'] == $account['account_id']){
						$activeAccount = $account;
						break;
					}
				}
				if($activeAccount){
					$stats = $Helper->getPostInformation($record['post_id'],$activeAccount);
					if(!$stats['error']){
						$results[$index]['social_stats'] = $stats['data'];
					}
				}
		    }
	    }
	    return $results;
    }
}?>