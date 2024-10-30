<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 10/8/15
 * Time: 12:52 PM
 */

namespace InboundBrew\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadHistory extends Eloquent{

    protected $softDelete = true;
    protected $table = 'ib_lead_history';
    protected $primaryKey = 'history_id';
    protected $fillable = array('lead_id','history_type','history_note','history_event');
    protected $dates = array('deleted_at');

    public function lead()
    {
        return $this->belongsTo('InboundBrew\Modules\Core\Models\Lead','lead_id','lead_id');
    }
}
