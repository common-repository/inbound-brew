<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 8/7/15
 * Time: 8:34 AM
 */

namespace InboundBrew\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Eloquent{

    protected $softDelete = true;
    protected $table = 'ib_countries';
    protected $primaryKey = 'country_id';
    protected $fillable = array('country_name', 'country_iso');
    protected $dates = array('deleted_at');

    public function states()
    {
        return $this->hasMany('InboundBrew\Modules\Core\Models\State','country_id','country_id');
    }

    public function leads()
    {
        return $this->hasMany('InboundBrew\Modules\Core\Models\lead','country_id','country_id');
    }

    public function scopeId($query,$id)
    {
        return $query->whereId('country_id',$id);
    }

    public function scopeName($query,$name)
    {
        return $query->where('country_name',$name);
    }
    
    static function countryList($display = "country_name",$empty = null){
	    $results = self::orderBy($display,"ASC")->get()->toArray();
	    $list = array();
	    if($empty) $list[0] = $empty;
	    foreach($results as $country){
		    $list[$country['country_id']] = $country[$display];
	    }
	    return $list;
    }

}