<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 7/14/15
 * Time: 1:21 PM
 */

namespace InboundBrew\Modules\SEO\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keyword extends Eloquent {

    protected $softDelete = true;
    protected $table = 'ib_keywords';
    protected $primaryKey = 'keyword_id';
    protected $fillable = array('keyword_value', 'keyword_rank');
    protected $dates = array('deleted_at');

    public function posts() {
        return $this->belongsToMany('InboundBrew\Modules\SEO\Models\Post','ib_post_keyword','keyword_id', 'post_id');
    }

    function scopeLike($query, $field, $value){
        return $query->where($field, 'LIKE', "%$value%");
    }
}