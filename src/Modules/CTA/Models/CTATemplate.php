<?php
/**
 * Created by PhpStorm.
 * User: rico
 * Date: 7/28/15
 * Time: 4:45 PM
 */

namespace InboundBrew\Modules\CTA\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class CTATemplate extends Eloquent {

    protected $softDelete = true;
    protected $table = 'ib_cta_templates';
    protected $primaryKey = 'template_id';
    protected $fillable = array('name');
	protected $dates = array('deleted_at');
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
	static function getList($order,$direction){
		$results = self::orderBy($order,$direction)->get();
//		$arr = explode("?",$_SERVER['REQUEST_URI']);
//		$uri = $arr[0];
//		$results->setPath(urldecode($uri));
//		$results->setPageName(BREW_ELOQUENT_PAGE_NAME); // which variable name will determine the current page.
//		$results->appends(['page'=>$wp_page,'order'=>$order,'direction'=>$direction]);
		return $results;
	}
	
	public function ctas(){
		return $this->hasMany('InboundBrew\Modules\CTA\Models\CallToAction',"cta_template_id");
	}
}