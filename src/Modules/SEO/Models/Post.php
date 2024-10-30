<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 7/20/15
 * Time: 3:56 PM
 */

namespace InboundBrew\Modules\SEO\Models;


class Post extends \InboundBrew\Modules\Core\Models\Post {

    public function keywords() {
        return $this->belongsToMany('InboundBrew\Modules\SEO\Models\Keyword', 'ib_post_keyword', 'post_id', 'keyword_id');
    }
}