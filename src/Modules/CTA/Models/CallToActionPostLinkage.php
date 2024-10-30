<?php
/**
 * Created by PhpStorm.
 * User: rico
 * Date: 7/28/15
 * Time: 4:45 PM
 */

namespace InboundBrew\Modules\CTA\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;

class CallToActionPostLinkage extends Eloquent {

    protected $table = 'ib_cta_post_linkages';
    protected $primaryKey = 'linkage_id';
}