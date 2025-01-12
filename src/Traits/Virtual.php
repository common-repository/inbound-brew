<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 11/17/15
 * Time: 9:29 AM
 */

namespace InboundBrew\Traits;
use InboundBrew\Modules\Core\AppController;

class Virtual extends AppController{

    private $title;
    private $content;

    /**
     * @param $var
     * @param $data
     */
    public function __set($var,$data)
    {
        $this->$var = $data;
    }


    /**
     * @param $var
     * @return array
     */
    public function __get($var)
    {
        return $this->$var;
    }



    public function virtualPage($posts)
    {
        global $wp, $wp_query;
        if (count($posts) == 0) {
            //create a fake post instance
            $p = new \stdClass;
            // fill $p with everything a page in the database would have
            $p->ID = -1;
            $p->post_author = 1;
            $p->post_date = current_time('mysql');
            $p->post_date_gmt = current_time('mysql', $gmt = 1);
            $p->post_content = $this->content;
            $p->post_title = $this->title;
            $p->post_excerpt = '';
            $p->post_status = 'publish';
            $p->ping_status = 'closed';
            $p->post_password = '';
            $p->to_ping = '';
            $p->pinged = '';
            $p->modified = $p->post_date;
            $p->modified_gmt = $p->post_date_gmt;
            $p->post_content_filtered = '';
            $p->post_parent = 0;
            $p->guid = get_home_url('/' . $p->post_name); // use url instead?
            $p->menu_order = 0;
            $p->post_type = 'page';
            $p->post_mime_type = '';
            $p->comment_status = 'closed';
            $p->comment_count = 0;
            $p->filter = 'raw';
            $p->ancestors = array(); // 3.6
            // reset wp_query properties to simulate a found page
            $wp_query->is_page = TRUE;
            $wp_query->is_singular = TRUE;
            $wp_query->is_home = FALSE;
            $wp_query->is_archive = FALSE;
            $wp_query->is_category = FALSE;
            unset($wp_query->query['error']);
            $wp->query = array();
            $wp_query->query_vars['error'] = '';
            $wp_query->is_404 = FALSE;
            $wp_query->current_post = $p->ID;
            $wp_query->found_posts = 1;
            $wp_query->post_count = 1;
            $wp_query->comment_count = 0;
            // -1 for current_comment displays comment if not logged in!
            $wp_query->current_comment = null;
            $wp_query->is_singular = 1;
            $wp_query->post = $p;
            $wp_query->posts = array($p);
            $wp_query->queried_object = $p;
            $wp_query->queried_object_id = $p->ID;
            $wp_query->current_post = $p->ID;
            $wp_query->post_count = 1;
            return array($p);
        }
    }
}