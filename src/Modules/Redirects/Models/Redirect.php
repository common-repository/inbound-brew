<?php
/**
 * Created by PhpStorm.
 * User: rico
 * Date: 7/28/15
 * Time: 4:45 PM
 */

namespace InboundBrew\Modules\Redirects\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Redirect extends Eloquent {

    //protected $softDelete = true;
    protected $table = 'ib_redirects';
    protected $primaryKey = 'redirect_id';
    protected $fillable = array('redirect_from', 'redirect_to');
	/**
	* load redirects from database
	* paginate based on GET variables in url.
	*
	* @param string $order field to sort by
	* @param string $direction direction in which the sorting should be done "ASC" or "DESC"
	* @param string $wp_page value for the current admin page
	* @return Eloquent Paginator Instance (will all results for this page)
	* @author Rico Celis
	* @access public
	*/
	static function getRedirects($order,$direction){
		$redirects = self::orderBy($order,$direction)->get();
		return $redirects;
	}
	
	static function saveSettings($settings){
		update_option(BREW_REDIRECT_SETTINGS_OPTION,$settings);
	}
}