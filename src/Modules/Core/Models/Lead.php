<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 10/1/15
 * Time: 3:00 PM
 */

namespace InboundBrew\Modules\Core\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Eloquent{

    protected $softDelete = true;
    protected $table = 'ib_leads';
    protected $primaryKey = 'lead_id';
    protected $fillable = array('lead_email','type_id','lead_score','lead_ip','lead_fname','lead_lname','lead_address','lead_address2','lead_city','lead_state','lead_postal','lead_phone','country_id','lead_dob');
    protected $dates = array('deleted_at');

    public function country()
    {
        return $this->belongsTo('InboundBrew\Modules\Core\Models\Country','country_id','country_id');
    }

    public function leadData()
    {
        return $this->hasMany('InboundBrew\Modules\Core\Models\LeadData', 'lead_id', 'lead_id');
    }

    public function leadHistory()
    {
        return $this->hasMany('InboundBrew\Modules\Core\Models\LeadHistory', 'lead_id', 'lead_id');
    }

    public function scopeEmail($query,$email)
    {
        return $query->where('lead_email',$email);
    }

    public function getList($order,$direction,$wp_page){
        $results = self::orderBy($order,$direction)->get(); //->paginate(BREW_PAGINATION_LIMIT);
//        $arr = explode("?",$_SERVER['REQUEST_URI']);
//        $uri = $arr[0];
//        $results->setPath(urldecode($uri));
//        $results->setPageName(BREW_ELOQUENT_PAGE_NAME); // which variable name will determine the current page.
//        $results->appends(['page'=>$wp_page,'order'=>$order,'direction'=>$direction]);
        return $results;
    }
    
    static function leadFormData($lead_id){
	    $lead = self::withTrashed()->find($lead_id)->toArray();
	    if(!$lead) return;
	    $leadData = LeadData::where('lead_id',$lead_id)->get();
	    $data = array(
		    'Lead' => $lead,
		    'LeadData' => array()
	    );	    
	    if(count($leadData)){
		    foreach($leadData as $ldata){
			    $data['LeadData'][$ldata->data_term] = $ldata->data_value;
		    }
	    }
	    return $data;
    }
}