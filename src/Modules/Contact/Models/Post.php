<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 7/20/15
 * Time: 3:56 PM
 */

namespace InboundBrew\Modules\Contact\Models;


class Post extends \InboundBrew\Modules\Core\Models\Post {

    public function fields() {
        return $this->belongsToMany('InboundBrew\Modules\Core\Models\FormField', 'ib_contact_field', 'post_id', 'field_id');
    }

    static function getLandingPages($post_type){
        $results = self::where('post_type',$post_type);
        $results->where('post_status','!=','trash');
        $results->where('post_status','!=','auto-draft');
        return $results->get();
    }
}