<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 7/14/15
 * Time: 12:55 PM
 */

namespace InboundBrew\Modules\SEO\Controllers;

use InboundBrew\Modules\Core\AppController;
use InboundBrew\Modules\SEO\Models\Keyword as KeywordModel;
use InboundBrew\Modules\SEO\Models\Post as PostModel;
// helpers
use Valitron\Validator;

/**
 * Class Keyword
 * @package InboundBrew\Modules\Keywords\Controllers
 */
class Keyword extends AppController{

    /**
     *
     */
    const VIEW_PATH = 'SEO/views/';

    /**
     * @var array
     * @access private
     */
    private $data = array();

    /**
     * Constructor, calls the parent AppController initializes the class
     * @access public
     */
    public function __construct()
    {
        parent::init();
        $this->register();
    }

    /**
     * adds all the WP actions and filters
     * @access private
     */
    private function register()
    {
        if (is_admin()) {
            // ajax hook
            add_action('wp_ajax_remove_ib_keywords', array($this, 'deleteKeyword'));
            add_action('wp_ajax_add_ib_keywords', array($this, 'addKeyword'));
            add_action('wp_ajax_edit_ib_keywords', array($this, 'updateKeyword'));
            add_action('wp_ajax_keyword_auto_complete', array($this, 'autoComplete'));
            add_action('wp_ajax_add_post_keyword', array($this, 'addPostKeyword'));
            add_action('wp_ajax_add_keyword_with_post', array($this,'addKeywordPost'));
            add_action('wp_ajax_remove_post_keyword', array($this, 'removePostKeyword'));

            // basic post handler
            add_action('admin_post_ib_kw_csv', array($this, 'uploadKeywords'));
            add_action('admin_post_export_ib_keywords', array($this,'exportKeywords'));
            // add side meta box
            add_action('add_meta_boxes', array($this, 'addMetaBox'));
            // add scripts
            add_action('admin_enqueue_scripts', array($this, 'addAdminScripts'));
            // save post
            add_action('save_post', array($this,'saveKeywordMetaBoxData'));
        }
    }

    /**
     * Enqueues Admin only JS files
     */
    public function addAdminScripts()
    {
        
        if (isset($_GET['page']) && $_GET['page'] == 'keyword-admin'){
            wp_enqueue_script('ib-keyword-shepherd', BREW_MODULES_URL.'SEO/assets/js/ib-keywords-shepherd.js', array('jquery'),BREW_ASSET_VERSION);
        }

        wp_enqueue_style('ib-keywords-css', BREW_MODULES_URL . 'SEO/assets/css/meta.css', array(), BREW_ASSET_VERSION);
        wp_enqueue_script('ib-keyword-seo', BREW_MODULES_URL.'SEO/assets/js/keyword-seo.js',array(), BREW_ASSET_VERSION);
        wp_enqueue_script('ib-kw-handle', BREW_MODULES_URL.'SEO/assets/js/keyword-ajax.js',array(), BREW_ASSET_VERSION);
        // in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
        wp_localize_script(
            'ib-kw-handle', 'ibKwAjax',
            array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'ibKwNonce' => wp_create_nonce( 'ib-kw-nonce' )
            )
        );

    }

    /**
     * Inspects the section $_GET string if set in order to load the correct view
     */
    public function loadAdmin()
    {
        switch (@$_GET['section']) {
            case 'keyword_manage':
                $this->Breadcrumb->add("Import");
                $data['Breadcrumb'] = $this->Breadcrumb;
                echo $this->load->view(self::VIEW_PATH . 'keyword_manage',$data);
                break;
            case 'keyword_list':
                $keywords = $this->loadKeywords();
                $data['terms'] = $keywords;
                #breadcrumbs;
                $this->Breadcrumb->add("Keyword Management");
                $data['Breadcrumb'] = $this->Breadcrumb;
                // load view
                echo $this->load->view(self::VIEW_PATH . 'keyword_list',$data);
                break;
            default:
                $keywords = $this->loadKeywords();
                $data['terms'] = $keywords;
                #breadcrumbs;
                $this->Breadcrumb->add("Keyword Management");
                $data['Breadcrumb'] = $this->Breadcrumb;
                // load view
                echo $this->load->view(self::VIEW_PATH . 'keyword_list',$data);
                break;

        }
    }

    /**
     * Returns keywords from the getList method ordered by column and direction ASC/DESC
     * @return mixed
     */
    private function loadKeywords()
    {
        // load all keywords
        $keywords = KeywordModel::all();
        return $keywords;
    }

    /**
     * Adds side meta box to selected post types
     * @param $postType
     */
    public function addMetaBox($postType)
    {

        if (in_array($postType,array('post','page','ib-landing-page'))) {
            $this->setPageData();
            add_meta_box(
                'ib_post_keywords',
                'Keywords',
                array($this, 'setKeywordMeta'),
                $postType,
                'side',
                'high'
            );
            add_meta_box(
                'ib_on_page_seo',
                'On Page SEO',
                array($this, 'setPageSeoMeta'),
                $postType,
                'side',
                'high'
            );
        }
    }

    /**
     * @access public
     *
     */
    public function setPageData()
    {
        global $post;
        $words = PostModel::find($post->ID)->keywords;
        $k = 0;
        $u = array();
        $this->data['seo_url'] = false;
        $this->data['seo_h_one'] = false;
        $this->data['seo_alt_tag'] = true;
        $this->data['seo_title'] = false;
        $this->data['seo_title_tag'] = true;
        foreach ($words as $word) {
            $k++;
            $pattern = '/\b' . $word->keyword_value . '\b/i';
            if (preg_match($pattern, $post->post_content)) {
                $u[$word->keyword_id] = 1;
                $word->used = 1;
            }
            if (preg_match($pattern, $post->post_title)) {
                $u[$word->keyword_id] = 1;
                $this->data['seo_title'] = true;
            }
            $url_pattern = '/' . str_ireplace(' ', '-', $word->keyword_value) . '/i';
            if (preg_match($url_pattern, $post->post_name)) {
                $this->data['seo_url'] = true;
            }

        }

        $this->data['kw_count'] = $k;
        $this->data['kw_used'] = count($u);
        $this->data['kw_percent'] = 0;
        if ($k > 0) {
            $this->data['kw_percent'] = round($this->data['kw_used'] / $k * 100);
        }
        if (preg_match('/<h1>.*?<\/h1>/',$post->post_content)) {
            $this->data['seo_h_one'] = true;
        }
        if (preg_match('/(<img(?!.*?alt=([\'"]).*?\2)[^>]*?)(\/?>)/',$post->post_content)) {
            $this->data['seo_alt_tag'] = false;
        }
        if (preg_match('/(<a(?!.*?title=([\'"]).*?\2)[^>]*?)(\/?>)/',$post->post_content)) {
            $this->data['seo_title_tag'] = false;
        }
        $this->data['words'] = $words;
    }

	/**
	* Save keyword data from metabox
	*
	* @author Rico Celis
	* @access public
	*/
	public function saveKeywordMetaBoxData($post_id = false){
		global $post;

        if (!$post_id || !$post || !is_object($post) || !isset($post->post_type)){ return; }
        if ($post->post_type == "ib-landing-page" && $post->skipMetaSave){
            //otherwise this is called twice
            unset($post->skipMetaSave);
            return;
        }

        $keywords = @$_POST['Keyword'];
		if(!empty($keywords)){
			foreach($keywords as $value){
				$keyword_id = $value['keyword_id'];
				$label = $value['keyword'];
				$is_deleted = $value['is_deleted'];
				if(!$keyword_id && $is_deleted) continue; // skip added in tool and deleted
				if(!$keyword_id){ // new keyword
					// check that doesn't exist
					$keyword = KeywordModel::where("keyword_value",$label)->get();
					if(@$keyword->keyword_id){
						$keyword_id = $keyword->keyword_id;
					}else{
						$keyword = new KeywordModel;
						$keyword->keyword_value = $label;
						$keyword->save();
						$keyword_id = $keyword->keyword_id;
					}
					$this->addPostKeyword($post_id,$keyword_id);
				}else{ // existing keyword
					if($is_deleted){
						$this->removePostKeyword($post_id,$keyword_id);
					}else{
						$this->addPostKeyword($post_id,$keyword_id);
					}
				}
			}
		}
	}

    /**
    * attaches keywords to posts/pages via the ib_post_keywords pivot table
    *
    * @param int $post_id id for WP post
    * @param int $keyword_id id for Keyword Model record
    * @return boolean true when linked
    *
    * @author Rico Celis
    * @access public 
     
    */
    public function addPostKeyword($post_id,$keyword_id) {
        $v = new Validator(array(
	        'post_id' => $post_id,
	        'keyword_id' => $keyword_id
        ));
        $v->rule('required', array('post_id', 'keyword_id'));
        $v->rule('numeric', array('post_id', 'keyword_id'));
        $result = array();
        if ($v->validate()) {
            try {
                $post = PostModel::find($post_id);
                $post->keywords()->attach($keyword_id);
            } catch (\Exception $e) {
                $result['message'] = $e->getMessage();
            }
        } else {
            $result['message'] = $v->errors();
        }
        return $result;
    }

    /**
    * removes keywords from posts/pages via the ib_post_keywords pivot table
    *
    * @param int $post_id id for WP post
    * @param int $keyword_id id for Keyword Model record
    * @return boolean true when linked
    *
    * @author Rico Celis
    * @access public 
     
    */
    public function removePostKeyword($post_id,$keyword_id) {
        $v = new Validator(array(
	        'post_id' => $post_id,
	        'keyword_id' => $keyword_id
        ));
        $v->rule('required', array('post_id', 'keyword_id'));
        $v->rule('numeric', array('post_id', 'keyword_id'));
        $result = array();
        if ($v->validate()) {
            try {
                $post = PostModel::find($post_id);
                $post->keywords()->detach($keyword_id);
            } catch (\Exception $e) {
                $result['message'] = $e->getMessage();
            }
        } else {
            $result['message'] = $v->errors();
        }
        return $result;
    }

    /**
     * setKeywordMeta
     * get Existing Keywords as well as keywords that are already attached to a post in order to display keyword options
     */
    public function setKeywordMeta()
    {
        echo $this->load->view(self::VIEW_PATH . 'partials/post-keywords', $this->data,"blank");
    }

    /**
     *
     */
    public function setPageSeoMeta()
    {
        echo $this->load->view(self::VIEW_PATH . 'partials/on-page-seo', $this->data,"blank");
    }

    /**
     * Adds a keyword to the ib_keywords table
     */
    public function addKeyword()
    {
        header('Content-Type: application/json');
        $nonce = $_POST['nonce'];
        $result = array();
        if (wp_verify_nonce( $nonce, 'ib-kw-nonce' ) && current_user_can('delete_posts')) {
            $v = new Validator($_POST);
            $v->rule('required', array('keyword'));
            if ($v->validate()) {
                try {
                    $term = filter_var($_POST['keyword'],FILTER_SANITIZE_STRING);
                    $keyword = $this->saveKeyword($term);
                    $result['id'] = $keyword->keyword_id;
                    $result['keyword'] = $keyword->keyword_value;
                    $this->_confirm("Keyword \"$term\" added successfully");
                    die(json_encode($result));
                } catch (Exception $e) {
                    http_response_code(400);
                    $result['error'] = $e->getMessage();
                    $this->_error($e->getMessage());
                    die(json_encode($result));
                }
            } else {
                http_response_code(400);
                $result['error'] = $v->errors();
                $this->_error($v->errors());
                die(json_encode($result));
            }
        }
        http_response_code(401);
        $result['error'] = "You are not authorized";
        $this->_error("You are not authorized");
        die(json_encode($result));
    }

    /**
     * Adds a keyword to the ib_keywords table
     */
    public function addKeywordPost()
    {
        header('Content-Type: application/json');
        $nonce = $_POST['nonce'];
        $result = array();
        if (wp_verify_nonce( $nonce, 'ib-kw-nonce' ) && current_user_can('delete_posts')) {
            $v = new Validator($_POST);
            $v->rule('required', array('post_id', 'keyword'));
            $v->rule('numeric', array('post_id'));
            if ($v->validate()) {
                try {
                    $term = filter_var($_POST['keyword'],FILTER_SANITIZE_STRING);
                    $keyword = $this->saveKeyword($term);
                    $post = PostModel::find($_POST['post_id']);
                    $post->keywords()->attach($keyword->keyword_id);
                    //set results
                    $result['id'] = $keyword->keyword_id;
                    $result['keyword'] = $keyword->keyword_value;
                    die(json_encode($result));
                } catch (Exception $e) {
                    http_response_code(400);
                    $result['error'] = $e->getMessage();
                    $this->_error($e->getMessage());
                    die(json_encode($result));
                }
            } else {
                http_response_code(400);
                $result['error'] = $v->errors();
                $this->_error($v->errors());
                die(json_encode($result));
            }
        }
        http_response_code(401);
        $result['error'] = "You are not authorized";
        $this->_error("You are not authorized");
        die(json_encode($result));
    }

    /**
     * update the text value of the keyword
     */
    public function updateKeyword()
    {
        header('Content-Type: application/json');
        $nonce = $_POST['nonce'];
        $result = array();
        if (wp_verify_nonce( $nonce, 'ib-kw-nonce' ) && current_user_can('delete_posts')) {
            $v = new Validator($_POST);
            $v->rule('required', array('keyword','id'));
            $v->rule('integer', 'id');
            if ($v->validate()) {
                try {
                    $term = KeywordModel::find($_POST['id']);
                    $term->keyword_value = $_POST['keyword'];
                    $term->save();
                    $result['keyword'] = $term->keyword_value;
                    $this->_confirm($result['keyword'] ." successfully updated");
                    die(json_encode($result));
                } catch (Exception $e) {
                    http_response_code(400);
                    $result['error'] = $e->getMessage();
                    $this->_error($e->getMessage());
                    die(json_encode($result));
                }
            } else {
                http_response_code(400);
                $result['error'] = $v->errors();
                $this->_error($v->errors());
                die(json_encode($result));
            }
        }
        http_response_code(401);
        $result['error'] = "You are not authorized";
        $this->_error("You are not authorized");
        die(json_encode($result));
    }

    /**
     * returns the label (keyword_value) and value (keyword_id) from like query of keyword model
     */
    public function autoComplete() {
        header('Content-Type: application/json');
        //$exclude = array();
        $id = $_POST['post'];
        $term = $_POST['term'];
        $result = KeywordModel::like('keyword_value', $term)->get();
        $used = PostModel::find($id)->keywords;
        /*foreach ($used as $arg) {
            $exclude[] = $arg->keyword_id;
        }*/
        $i = 0;
        $arr = array();
        foreach ($result as $value) {
            $arr[$i]['label'] = $value->keyword_value;
            $arr[$i]['value'] = $value->keyword_id;
            $i++;
        }
        die(json_encode($arr));
    }

    /**
     * Soft deletes keyword
     */
    public function deleteKeyword()
    {
        $nonce = $_POST['nonce'];
        if (wp_verify_nonce( $nonce, 'ib-kw-nonce' ) && current_user_can('edit_posts')) {
            $v = new Validator($_POST);
            $v->rule('required', 'id');
            $v->rule('integer', 'id');
            if ($v->validate()) {
                try {
                    $record = KeywordModel::find($_POST['id']);
                    $record->delete();
                    $result['success'] = 'Keyword "'.$record->keyword_value.'" Deleted Successfully';
                    $this->_confirm($result['success']);
                    die(json_encode($result));
                } catch (Exception $e) {
                    http_response_code(400);
                    $result['error'] = $e->getMessage();
                    $this->_error($e->getMessage());
                    die(json_encode($result));
                }
            } else {
                http_response_code(400);
                $result['error'] = $v->errors();
                $this->_error($v->errors());
                die(json_encode($result));
            }
        }
        http_response_code(401);
        $result['error'] = "You are not authorized";
        $this->_error($result['error']);
        die(json_encode($result));;
    }

    /**
     * digest a CSV file into the ib_keywords table
     * ony requirements are the Keyword header term
     * Soft deletes all previous keywords if delete existing is selected.
     */
    public function uploadKeywords()
    {
        $error = array();
        if (!check_admin_referer( 'ib_keyword_batch_upload' ) || !current_user_can('edit_posts')) {
            $error[] = "You are not authorized to batch upload";
        }
		$allowed = array("text/plain","text/csv");
        if (!in_array($_FILES['csv_file']['type'],$allowed)) {
            $error[] = "incorrect file type. Please upload csv.";
        }
        if ($_FILES['csv_file']['size'] < 1) {
            $error[] = "File is empty.";
        }
        if (!empty($error)) {
            $errors = "<br />".implode("<br />", $error);
            $this->_error("the following errors were found:". $errors,true);
            header('Location: ' . $_POST['_wp_http_referer']);
        } else {
            $file = $_FILES['csv_file']['tmp_name'];
            if (($handle = fopen($file, "r")) !== FALSE) {
                $i = 0;
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($i == 0) {
                        if (false === $kw = array_search('Keyword', $data)) {
                            $error[] = "Missing keyword header";
                        } else {
                            if (@$_POST['replace_all']) {
                                $record = KeywordModel::where('deleted_at', '=', NULL);
                                $record->delete();
                            }
                        }
                    }
                    if (isset($kw) && is_int($kw)) {
	                    $keyword = $data[$kw];
                        if ($keyword != false && is_string($keyword) && $keyword != "Sample Keyword" && $i > 0) {
                            $this->saveKeyword($data[$kw]);
                        }
                    }
                    $i++;
                }
                fclose($handle);
            } else {
                $error[] = 'Could not read provided file';
            }

            if (!empty($error)) {
                $this->_error("the following errors were found: <br />" . implode('<br />', $error), true);
            } else {
                $this->_confirm("Keywords successfully uploaded",true);
            }
            header('Location: ' . $_POST['_wp_http_referer']);
        }
    }

    /**
     * generate a CSV of all(soft deleted included) keywords in the keyword model
     */
    public function exportKeywords()
    {
        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=ib_keywords.csv');

        // create a file pointer connected to the output stream
        $handle = fopen('php://output', 'w');

        // output the column headings
        $headers = array('ID', 'Keyword', 'Competitive Score','SERP Standing', 'Overall','Created','Last Modified','Deleted');
        fputcsv($handle, $headers);

        // fetch the data
        $keywords = KeywordModel::withTrashed()->get();
        // loop over the rows, outputting them
        foreach($keywords as $value) {
            $data = array();
            $data[] = $value->keyword_id;
            $data[] = $value->keyword_value;
            $data[] = $value->keyword_score;
            $data[] = $value->keyword_serp;
            $data[] = $value->keyword_rank;
            $data[] = $value->created_at;
            $data[] = $value->updated_at;
            $data[] = $value->deleted_at;
            fputcsv($handle, $data);
            unset($data);
        }

        fclose($handle);
    }

    /**
     * do nothing if keyword exists
     * un-delete keyword if it was soft deleted
     * add keyword if it does not exist.
     * @param $term
     * @return \Illuminate\Database\Eloquent\Model|KeywordModel|
     */
    private function saveKeyword($term)
    {
        if ($keyword = KeywordModel::where('keyword_value',$term)->first()) {
            return $keyword;
        } else if ($keyword = KeywordModel::onlyTrashed()->where('keyword_value',$term)->first()) {
            $keyword->restore();
            return $keyword;
        } else {
            $keyword = new KeywordModel();
            $keyword->keyword_value = $term;
            $keyword->save();
            return $keyword;
        }
    }
}