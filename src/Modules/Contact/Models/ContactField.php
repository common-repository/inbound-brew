<?php
namespace InboundBrew\Modules\Contact\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
//use Illuminate\Database\Eloquent\SoftDeletes;

class ContactField extends Eloquent {
    //protected $softDelete = true;
    protected $table = 'ib_contact_field';
}