<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 7/27/15
 * Time: 8:32 PM
 */

namespace InboundBrew\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Comment extends Eloquent
{
    protected $primaryKey = 'comment_ID';
    /**
     * Post relation for a comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function post()
    {
        return $this->hasOne('InboundBrew\Modules\Core\Models\Post');
    }
}