<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 7/27/15
 * Time: 8:34 PM
 */

namespace InboundBrew\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class PostMeta extends Eloquent
{
    protected $table = 'postmeta';
    protected $primaryKey = 'meta_id';
    protected $fillable = array('meta_key', 'meta_value');

    public function post()
    {
        return $this->belongsTo('InboundBrew\Modules\Core\Models\Post','post_id','ID');
    }
}