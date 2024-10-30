<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 7/27/15
 * Time: 7:35 PM
 */

namespace InboundBrew\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Post extends Eloquent{

    protected $post_type = null;
    protected $primaryKey = 'ID';

    const CREATED_AT = 'post_date';
    const UPDATED_AT = 'post_modified';

    /**
     * Filter by post type
     *
     * @param $query
     * @param string $type
     *
     * @return mixed
     */
    public function scopeType($query, $type = 'post')
    {
        return $query->where('post_type', '=', $type);
    }
    /**
     * Filter by post status
     *
     * @param $query
     * @param string $status
     *
     * @return mixed
     */
    public function scopeStatus($query, $status = 'publish')
    {
        return $query->where('post_status', '=', $status);
    }
    /**
     * Filter by post name
     *
     * @param $query
     * @param string $name
     *
     * @return mixed
     */
    public function scopeName($query, $name = null)
    {
        return $query->where('post_name', '=', $name);
    }
    /**
     * Filter by post author
     *
     * @param $query
     * @param null $author
     *
     * @return mixed
     */
    public function scopeAuthor($query, $author = null)
    {
        if ($author) {
            return $query->where('post_author', '=', $author);
        }
    }
    /**
     * Get comments from the post
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('InboundBrew\Modules\Core\Models\Comment', 'comment_post_ID');
    }
    /**
     * Get meta fields from the post
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function meta()
    {
        return $this->hasMany('InboundBrew\Modules\Core\Models\PostMeta', 'post_id', 'ID');
    }

	/**
	* load ctas from database
	* paginate based on GET variables in url.
	*
	* @param string $order field to sort by
	* @param string $direction direction in which the sorting should be done "ASC" or "DESC"
	* @param string $wp_page value for the current admin page
	* @return Eloquent Paginator Instance (will all results for this page)
	* @author Rico Celis
	* @access public
	*/
	public function getCTAs($order,$direction,$wp_page){
		$paginate = self::orderBy($order,$direction);
		$paginate->where('post_type',"=","ib-call-to-action")->where('post_status',"=","publish");
		$results = $paginate->paginate(BREW_PAGINATION_LIMIT);
		$arr = explode("?",$_SERVER['REQUEST_URI']);
		$uri = $arr[0];
		$results->setPath(urldecode($uri));
		$results->setPageName(BREW_ELOQUENT_PAGE_NAME); // which variable name will determine the current page.
		$results->appends(array('page'=>$wp_page,'order'=>$order,'direction'=>$direction));
		return $results;
	}
}