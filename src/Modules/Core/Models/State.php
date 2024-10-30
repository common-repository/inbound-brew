<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 8/7/15
 * Time: 1:08 PM
 */

namespace InboundBrew\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Eloquent{

    protected $softDelete = true;
    protected $table = 'ib_states';
    protected $primaryKey = 'state_id';
    protected $fillable = array('state_name', 'state_abbr');
    protected $dates = array('deleted_at');

    public function country()
    {
        return $this->belongsTo('InboundBrew\Modules\Core\Models\Country', 'country_id', 'country_id');
    }
    
    static function stateList($display = "state_abbr",$empty = null){
	    $results = self::orderBy($display,"ASC")->get()->toArray();
	    $list = array();
	    if($empty) $list[0] = $empty;
	    foreach($results as $state){
		    $list[$state['state_abbr']] = $state[$display];
	    }
	    return $list;
    }
}