<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 7/27/15
 * Time: 9:30 PM
 */

namespace InboundBrew\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserMeta extends Eloquent
{
    protected $primaryKey = 'meta_id';
    public function getTable()
    {
        return $this->getConnection()->db->prefix. 'usermeta';
    }
}