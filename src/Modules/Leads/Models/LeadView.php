<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 9/15/15
 * Time: 10:33 AM
 */

namespace InboundBrew\Modules\Leads\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class LeadView extends Eloquent{
    protected $softDelete = true;
    protected $table = 'ib_lead_views';
    protected $primaryKey = 'lead_view_id';
    protected $fillable = array('view_name');
    protected $dates = array('deleted_at');
    
    static function getUserViews($user_id){
	    // default view
	    $settings = get_option(BREW_USER_DEFAULT_LEAD_VIEW_OPTION."_".$user_id,array());
	    if(!$settings) $settings = get_option(BREW_DEFAULT_LEAD_VIEW_SETTINGS_OPTION);
	    if(empty($settings['view_filters'])){
		    $settings['view_filters'] =  array(
		        'static' => array(
			        'archived_leads' => "only_active"
		        )
	        );
	    }
	    $views = array(
	        "all" => $settings
        );
        // user views
        $userViews = LeadView::where("wp_user_id",$user_id)->orderBy("display_order","ASC")->get()->toArray();
        if(!empty($userViews)){
	        foreach($userViews as $view){
		        $view['view_columns'] = unserialize($view['view_columns']);
		        $view['view_filters'] = unserialize($view['view_filters']);
		        $view['view_columns_order'] = unserialize($view['view_columns_order']);
		        $view['view_columns_width'] = unserialize($view['view_columns_width']);
		        $views[$view['lead_view_id']] = $view;
	        }
        }
        return $views;
    }
}