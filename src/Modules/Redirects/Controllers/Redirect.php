<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 3/31/15
 * Time: 3:06 PM
 */

namespace InboundBrew\Modules\Redirects\Controllers;

use InboundBrew\Libraries\FormHelper;
use InboundBrew\Libraries\LayoutHelper;
use InboundBrew\Libraries\PaginatorHelper;
use InboundBrew\Libraries\CsvHelper;
use InboundBrew\Libraries\BreadcrumbHelper;
use InboundBrew\Modules\Core\AppController;
use InboundBrew\Modules\Redirects\Models\Redirect as RedirectModel;

class Redirect extends AppController {
    
    const VIEW_PATH = 'Redirects/views/';

    private $post_type = 'ib-redirects';
	
    public function __construct()
    {
		parent::init();
		// if in admin section.
		if(is_admin()){
			if(@$this->active_modules['redirects'] && isset($_GET['page']) && @$_GET['page'] == "ib-redirects"){
				if(@$_POST['action'] == "ib_export_redirect") add_action('admin_init',array($this,"handleCSVExport"),1);
				// ajax hooks
				add_action( "wp_ajax_ib_edit_redirect", array($this,"editRedirect") );
				add_action( "wp_ajax_ib_delete_redirect", array($this,"deleteRedirect") );
				// add scripts
		        add_action('admin_enqueue_scripts', array($this,'addAdminScripts'));
		        $options = get_option(BREW_REDIRECT_SETTINGS_OPTION);
		        if(@$options['auto_redirect_on_url_change']) add_action('save_post', array($this,'checkForSlugChange'));
		    }		
        }else{
	        if(@$this->active_modules['redirects']) add_action('init', array($this, 'ib_checkRedirects'),1);
        }
    }
	
	/**
	* check if slug has changed.
	* only called if option is selected to create auto redirects.
	* @param int $post_id wordpress post id
	*
	* @author: Rico Celis
	* @access: public
	*
	*/
	public function checkForSlugChange($post_id){
		$nonce = @$_POST['data']['Post']['core_nonce'];
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; // don't do anything on autosave.
		if ( ! wp_verify_nonce( $nonce, "ib-core-data-nonce" ) ) return;
		$new_slug = $_POST['post_name'];
		$old_slug = $_POST['data']['Post']['old_slug'];
		if($new_slug != $old_slug && !empty($old_slug) && $_POST['post_status'] == "publish"){ // we need to create a new redirect.
			// check if redirect already exists.
			$redirect = RedirectModel::where('redirect_from',$old_slug)->where("redirect_to",$post_id)->first();
			if(!@$redirect->redirect_id){
				$r = new RedirectModel;
				$r->redirect_from = $old_slug;
				$r->redirect_to = $post_id;
				$r->redirect_type = get_post_type($post_id);
				$r->status = 301;
				$r->save();
			}
		}
	}

	/*
	* Method checks for redirects in the front end.
	*/
	/**
	*
	* check if the url matches any of the requests
	*
	* @author Rico Celis
	* @access public
	*/
	public function ib_checkRedirects(){
		// load all redirects
		$redirects = RedirectModel::all()->toArray();
		$request = $this->getUrl();
		if(!empty($redirects)){
			foreach($redirects as $redirect){
				$wildcard = $redirect['is_wildcard'];
				$r_from = $redirect['redirect_from'];
				$destination = ($redirect['redirect_type'] =="url")? $redirect['redirect_to']: get_permalink($redirect['redirect_to']);
				$match = false;
				// match redirect to current url
				// check if we should use regex search
				if ($wildcard && strpos($r_from,'*') !== false) {
					// clean pattern
					$pattern = '/' . str_replace(array("/","?"),array("\/","\?"), rtrim( $r_from, '/' ) ) . '/';
					// if replacing values
					if(strpos($r_from,"(.*)") !== false){
						preg_match($pattern,$request,$matches);
						if(!empty($matches)){
							$output = $destination;
							foreach($matches as $index=>$m){
								if(!$index) continue; // first index.
								$output = str_replace('$'.$index,$m,$output);
							}
						}
					}else{
						// Make sure it gets all the proper decoding and rtrim action
						$r_from = str_replace('.*','(.*)',$r_from);
						$pattern = '/^' . str_replace( '/', '\/', rtrim( $r_from, '/' ) ) . '/';
						$output = preg_replace($pattern, $destination, $request);
					}
					if ($output !== $request && !empty($output)) {
						// pattern matched, perform redirect
						$match = true;
						$destination = $output;
					}
				}elseif($request == $r_from){ // normal redirect
					$match = true;
				}
				if($match){ // if match was found?
					if( $redirect['status'] == '301' )
                        header ('HTTP/1.1 301 Moved Permanently');
                  	else
						header ('HTTP/1.1 302 Moved Temporarily');
					header ('Location: ' . $destination);
                    exit();
				}
			}
		}
	}
	
	/**
	*
	* returns current url request without host.
	*
	* @author Rico Celis
	* @access public
	*/
	public function getUrl(){
		return  $str = substr(strtolower( urldecode( $_SERVER['REQUEST_URI'] ) ),1);
	}

	/**
	* Enqueues Admin only JS files
	*
	* @author Rico Celis
	* @access public
	*/
	public function addAdminScripts(){
		
		if (isset($_GET['page']) && $_GET['page'] == 'ib-redirects'){
			wp_enqueue_style('ib-redirects-css', BREW_MODULES_URL . 'Redirects/assets/css/ib-redirects.css', array(), BREW_ASSET_VERSION);
			wp_enqueue_script('ib-redirects-js', BREW_MODULES_URL.'Redirects/assets/js/ib_redirects.jquery.js',array('jquery'), BREW_ASSET_VERSION, true );
            wp_enqueue_script('ib-redirects-shepherd', BREW_MODULES_URL.'Redirects/assets/js/ib-redirects-shepherd.js', array('jquery'),BREW_ASSET_VERSION);
        }
	}

	public function ib_test($text){
		echo "the text you passed was {$text}";
	}

	/**
	* function is called when displaying initial view for controller
	*
	* @author Rico Celis
	* @access public
	*/
	public function redirectsAdmin(){
		if(@$this->active_modules['redirects']){
			$section = "list";
			if(@$_GET['section']) $section = $_GET['section'];
			switch($section){
				case "list":
					$this->redirectsHome();
				break;
				case "import":
					$this->redirectsImport();
				break;
				case "export":
					$this->handleCSVExport();
				break;
				case "edit":
					$this->editRedirect($_GET['redirect_id']);
				break;
			}	
		}else{
			// load view
			$this->Breadcrumb->add("Redirects Management");
			$data['Breadcrumb'] = $this->Breadcrumb;
			$data['Layout'] = new LayoutHelper();
			$data['module_index'] = "redirects";
        	echo $this->load->view(BREW_PLUGIN_VIEWS_PATH."inactive_module",$data);
		}
    }
    
    /**
	* Show list of redirects
	*
	* @author Rico Celis
	* @access private
	*/
    private function redirectsHome(){
	    if(!empty($_POST)){
		    $this->addRedirect();
	    }
	    // helpers		
		$data['Paginator'] = new PaginatorHelper;
		$data['Form'] = new FormHelper;
		// data
		$data['redirects'] = $this->loadRedirects();
		$data['post_type'] = $this->post_type;
		$data['options'] = $this->getRedirectOptions();
		// load view
		$this->Breadcrumb->add("Redirects Management");
		$data['Breadcrumb'] = $this->Breadcrumb;
        echo $this->load->view(self::VIEW_PATH."admin_list",$data);
    }
    
    /**
	* allow users to import redirect list
	*
	* @author Rico Celis
	* @access private
	*/
    private function redirectsImport(){
	    // check if importing
	    if(@$_POST['data']['ib_import_redirects']){
			$imported = $this->handleCsvUpload();
		}
		if(@$imported){
			$this->redirectsHome();
		}else{
			// show import form.
			$this->Breadcrumb->add("Redirects Management",array("admin.php?page={$this->post_type}&section=list"));
			$this->Breadcrumb->add("Import");
			$data['post_type'] = $this->post_type;
			$data['Form'] = new FormHelper;
			$data['Breadcrumb'] = $this->Breadcrumb;
		    echo $this->load->view(self::VIEW_PATH."admin_import",$data);
		}
	}

	/**
	* take uploaded CSV and add redirects to database
	* check post to see what to do with duplicates.
	*
	* @author Rico Celis
	* @access public
	*/
	private function handleCsvUpload(){
		$file = @$_FILES['ib_csv_file'];
		$dupsHandle = $_POST['data']['Redirect']['handle_duplicates'];
		if(!@$file['error']){ // no errors in upload
			if (($handle = fopen($file['tmp_name'], "r")) !== FALSE) {
				$separator = ",";
				$row = 1;
				// check if we need to replace all
				if($dupsHandle == "replace_all") RedirectModel::truncate();
			    while (($data = fgetcsv($handle, 0, $separator)) !== FALSE) {
			        if($row == 1){
						//skip header row
						$row++;
						continue;
					}
					$row ++;
					$status = $data[0];
					$from = $data[1];
					$to = $data[2];
					$wildcard = $data[3];
					if($status == "301" && $from == "url_from" && $to == "url_to" && $wildcard == "0") continue;
					if(!empty($status) && !empty($from) && !empty($to)){
						$blogurl = get_bloginfo('url');
						$from = str_replace($blog_url,"",$from); // remove blog url.
						$from = ltrim($from,"/");
						$redirect = RedirectModel::where('redirect_from','=',$from)->first();
						if($redirect){ // if redirect already exists
							if($dupsHandle == "ignore_duplicates") continue; // ignore duplicates
						}else{
							$redirect = new RedirectModel; // create new.
						}
						// assign values
						$redirect->redirect_from = $from;
						$redirect->redirect_to = $to;
						$redirect->status = $status;
						$redirect->is_wildcard = $wildcard;
						$redirect->save(); // overwrite
					}
				}
			}
			$this->_confirm("CSV uploaded successfully.");
			return true;
		}else{
			$this->_error("No file selected.");
		}
		return false;
	}
	
	/**
	* export a CSV file with all redirects in database.
	*
	* @author Rico Celis
	* @access public
	*/
	public function handleCSVExport(){
		$redirects = RedirectModel::all();
		$Csv = new CsvHelper;
		$Csv->addRow(array('STATUS','REDIRECT_FROM','REDIRECT_TO','WILDCARDS'));
		foreach($redirects as $redirect){
			$to = ($redirect->redirect_type == "url")? $redirect->redirect_to : get_permalink($redirect->redirect_to);
			$Csv->addRow(array(
				$redirect->status,
				$redirect->redirect_from,
				$to,
				$redirect->is_wildcard
			));
		}
		$date = date('dMY');
		echo $Csv->render("ib_redirects_{$date}.csv");
		exit();
	}
	

	/**
	* get WordPress content options for redirect
	*
	* @author Rico Celis
	* @access public
	*/
	function getRedirectOptions(){
		$post_types = get_post_types(array(
			'_builtin' => true,
	        'public' => true
	        ), 'objects');
	    $post_types['ib-landing-page'] = json_decode(json_encode(array(
		    'name' =>'ib-landing-page',
		    'labels' => array(
			    'singular_name' => "Landing Page"
		    )
	    )),FALSE);
		$pageTypes = array(
			"url" => "Custom URL",
			"ib-landing-page" => "Landing Pages");
		$typeOptions = array();
		// loop through post types
		foreach($post_types as $post_type){
			$type = $post_type->name;
			if($type == "ib-redirects") continue; // ignore redirects
			$typeOptions[$type] = $this->getPostTypeOptions($type);
			$pageTypes[$type] = $post_type->labels->singular_name;
		}
		return array(
			'types' => $pageTypes,
			'options' => $typeOptions
		);
	}
	
	/**
	* get WordPress content options for redirect
	*
	* @param string post type name
	* @param array ability to overwrite default arguments
	* @return array list of options for each post type
	* @author Rico Celis
	* @access public
	*/
	public static function getPostTypeOptions($post_type,$args = array()){
		$defaults = array(
	        'posts_per_page'   => -1,
	        'offset'           => 0,
	        'category'         => '',
	        'orderby'          => 'post_title',
	        'order'            => 'DESC',
	        'include'          => '',
	        'exclude'          => '',
	        'meta_key'         => '',
	        'meta_value'       => '',
	        'post_type'        => $post_type,
	        'post_mime_type'   => '',
	        'post_parent'      => '',
	        'post_status'      => 'publish',
	        'suppress_filters' => true,
	        'depth'            => 5
	    );
	    $r = wp_parse_args( $args, $defaults );
		$pages = get_posts( $r );
		$p = array();
		foreach($pages as $page){
			$p[$page->ID] = $page->post_title;
		}
		return $p;
	}
	
	/**
	* user is creating new redirect
	*
	* @return data for new record with id.
	* @author Rico Celis
	* @access public
	*/
	public function addRedirect(){
		$post = $_POST['data']['Redirect'];
		$nonce = $_POST['data']['nonce'];
        if (wp_verify_nonce( $nonce, 'ib-redirect-nonce' ) && current_user_can('delete_posts') && !empty($post)) {
            // check if redirect exists
            foreach($post as $row){
	         	if(!RedirectModel::where('redirect_from','=',$row['redirect_from'])->exists()){
					try {
		                $redirect = new RedirectModel;
						$redirect->status = filter_var($row['status'],FILTER_SANITIZE_NUMBER_INT);
						$redirect->redirect_from = filter_var($row['redirect_from']);
						$r_type = filter_var($row['redirect_type'],FILTER_SANITIZE_STRING);
						$r_to = $row[$r_type.'_options'];
						$redirect->redirect_type = $r_type;
						$redirect->redirect_to =  $r_to;
						$redirect->is_wildcard = (@$row['is_wildcard'])? "1" : "0";
		                $redirect->save();
	
		                $result['id'] = $redirect->redirect_id;
		                $result['redirect_from'] = $redirect->redirect_from;
		                $result['redirect_type'] = $redirect->redirect_type;
						$result['redirect_to'] = $redirect->redirect_to;
						$result['is_wildcard'] = $redirect->is_wildcard;
		            } catch (Exception $e) {
		                $result['message'] = $e->getMessage();
		            }
				}
            }
            // confirm
            $this->_confirm("New redirects added.");
        }else{
	        $this->_confirm("Unable to add redirects. Please try again.",true);
        }
    }
    

	/**
	* update redirect
	* request is made through ajax call using WP action "wp_ajax_ib_edit_redirect"
	*
	* @return array type=1 for success, and message="xx"
	* @author Rico Celis
	* @access public
	*/
	public function editRedirect($redirect_id = null){
		// user saving redirect.
		if(!empty($_POST)){
			$result['success'] = 0;
	        $result['message'] = 'Item could not be updated.Please try again.';
			$post = $_POST['data']['Redirect'];
	        if (wp_verify_nonce( $_POST['nonce'], 'ib-kw-nonce' ) && current_user_can('delete_posts') && !empty($post)) {
				$redirect = RedirectModel::find($post['redirect_id']);
				if(!empty($redirect->redirect_id)){ // valid item
					try{
						$redirect->status = filter_var($post['status'],FILTER_SANITIZE_NUMBER_INT);
						$redirect->redirect_from = filter_var($post['redirect_from']);
						$r_type = filter_var($post['redirect_type'],FILTER_SANITIZE_STRING);
						$r_to = $post[$r_type.'_options'];
						$redirect->redirect_type = $r_type;
						$redirect->redirect_to =  $r_to;
						$redirect->is_wildcard = (@$post['is_wildcard'])? "1" : "0";
		                $redirect->save();
						$result['success'] = 1;
		                $result['message'] = 'Item was successfully updated.';
		                $result['redirect'] = array(
			                "status" => $redirect->status,
		                	"redirect_from" => get_bloginfo( 'url', 'display' ). "/<strong>".$redirect->redirect_from."</strong>",
		                	"redirect_to" => strtoupper($redirect->redirect_type).": " .get_permalink((int)$redirect->redirect_to),
		                	"is_wildcard" => ($redirect->is_wildcard)? "YES": "NO");
						$this->_confirm($result['message']);
					}catch (Exception $e) {
						$result['message'] = $e->getMessage();
			        }
				}
			}
			header('Content-Type: application/json');
	        die(json_encode($result));
		}else{ // edit view
			$redirect = RedirectModel::find($redirect_id);
			if(!empty($redirect->redirect_id)){
				// breadcrumd
				$arr = $redirect->toArray();
				$arr[$arr['redirect_type']."_options"] = $arr['redirect_to'];
				$Form = new FormHelper;
				$Form->data = array(
					'Redirect' => $arr
				);
				$this->Breadcrumb->add("Redirects Management","admin.php?page={$this->post_type}");
				$this->Breadcrumb->add("Edit Redirect");
				$data['Breadcrumb'] = $this->Breadcrumb;
				$data['Form'] = $Form;
				$data['redirect'] = $redirect;
				$data['options'] = $this->getRedirectOptions();
				$data['post_type'] = $this->post_type;
				echo $this->load->view(self::VIEW_PATH."admin_edit",$data);
			}else{
				$this->_error("Invalid Redirect");
			}
		}
	}
	
	/**
	* user wants to delete redirect
	* request is made through ajax call using WP action "wp_ajax_ib_delete_redirect"
	*
	* @return array type = 1 for success and message="xx"
	* @author Rico Celis
	* @access public
	*/
	public function deleteRedirect(){
		$result['type'] = 0;
        $result['message'] = 'Item could not be deleted.';
		$post = $_POST;
        if (wp_verify_nonce( $_POST['nonce'], 'ib-kw-nonce' ) && current_user_can('delete_posts') && !empty($post)) {
            try {
                $redirect = RedirectModel::find($post['redirect_id']);
				if(!empty($redirect->redirect_id)){ // valid item
					$redirect->delete();
					$result['type'] = 1;
	                $result['message'] = 'Item was successfully added';
				}else{
					$result['message'] = 'Record not found';
				}
            } catch (Exception $e) {
                $result['message'] = $e->getMessage();
            }
        }
        header('Content-Type: application/json');
        die(json_encode($result));
	}

	/**
	* load redirects from database
	* paginate based on GET variables in url.
	*
	* @return Eloquent Paginator Instance (will all results for this page)
	* @author Rico Celis
	* @access public
	*/
	function loadRedirects(){
		// load all redirects
		$order = "redirect_id";
		$direction = "ASC";
		$redirects = RedirectModel::getRedirects($order,$direction);
		return $redirects;
	}
}