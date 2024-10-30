<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 7/30/15
 * Time: 3:15 PM
 */

namespace InboundBrew\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Option extends Eloquent{

    protected $primaryKey = 'option_id';
    protected $fillable = array('option_name', 'option_value');

    public $timestamps = false;

}