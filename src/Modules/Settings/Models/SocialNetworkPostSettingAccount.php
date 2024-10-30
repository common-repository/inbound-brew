<?php
/**
 * Created by Rico Celis.
 * Date: 10/13/15
 * Time: 02:39 PM
 */

namespace InboundBrew\Modules\Settings\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
// libraries
use InboundBrew\Libraries\DateHelper;

class SocialNetworkPostSettingAccount extends Eloquent {

//    protected $softDelete = true;
    protected $table = 'ib_social_network_post_setting_accounts';
    protected $primaryKey = 'id';
    
    /**
	* load list from database
	*
	* @param string $post_setting_id post setting id
	* @param array $options list of possible options
	* @author Rico Celis
	* @access public
	*/
	public function getList($post_setting_id,$options = array()){
		$_defaults = array(
			'as_array' => false, // return results as array instead of Eloquent models
			'ids_only' => false // return only an array of ids
		);
		$options = array_merge($_defaults,$options);
		$results = self::where("posting_setting_id",$post_setting_id)->get();
		if($options['as_array'] || $options['ids_only']){
			$results = $results->toArray(); // want PHP array back?
			if(empty($results)) return $results;
			if($options['ids_only']){
				$ids = array();
				foreach($results as $item){
					$ids[] = $item['network_account_id'];
				}
				return $ids;
			}
		}
		return $results;
	}
}?>