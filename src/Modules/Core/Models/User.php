<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 7/27/15
 * Time: 9:29 PM
 */

namespace InboundBrew\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    protected $primaryKey = 'ID';
    protected $timestamp = false;
    public function meta()
    {
        return $this->hasMany('InboundBrew\Modules\Core\Models\UserMeta', 'user_id');
    }
}