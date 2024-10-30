<?php
/**
 * Created by PhpStorm.
 * User: rico
 * Date: 7/28/15
 * Time: 4:45 PM
 */

namespace InboundBrew\Modules\CTA\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use InboundBrew\Modules\CTA\Models\CallToActionPostLinkage;

class CallToAction extends Eloquent {

    protected $softDelete = true;
    protected $table = 'ib_ctas';
    protected $primaryKey = 'cta_id';
    protected $fillable = array('title');
	protected $dates = array('deleted_at');
	/**
	* load redirects from database
	* paginate based on GET variables in url.
	*
	* @param string $order field to sort by
	* @param string $direction direction in which the sorting should be done "ASC" or "DESC"
	* @param string $wp_page value for the current admin page
	* @return Eloquent Paginator Instance (will all results for this page)
	* @author Rico Celis
	* @access public
	*/
	public function getList($order,$direction){
		$results = self::orderBy($order,$direction)->get();
//		$arr = explode("?",$_SERVER['REQUEST_URI']);
//		$uri = $arr[0];
//		$results->setPath(urldecode($uri));
//		$results->setPageName(BREW_ELOQUENT_PAGE_NAME); // which variable name will determine the current page.
//		$results->appends(['page'=>$wp_page,'order'=>$order,'direction'=>$direction]);
		return $results;
	}
	
	/**
	* user has modified template
	* so all cta's linked to it needs the html updated.
	*
	* @param Eloquent Object $template CTA Template object
	* @return boolean true when completed.
	* @author Rico Celis
	* @access public
	*/
	static public function templateModified($template){
		// get all cta's linked to template
		$results = self::where("cta_template_id",$template->template_id)->get();
		$source = stripslashes($template->html);
		foreach($results as $cta){
			// replace template name with cta name
			$html = str_replace($template->name, $cta->name, $source);
			$cta->html = $html;
			$cta->save();
		}
		return true;
	}
	
	/**
	* when a CTA template is deleted
	* delete all CTA's
	* delete reference to posts
	* load posts CTA is in and delete short code.
	*
	* @param Eloquent Object $template CTA Template object
	* @return boolean true when completed.
	* @author Rico Celis
	* @access public
	*/
	static public function templateDeleted($template_id){
		$ctas = self::where("cta_template_id",$template_id)->get();
		foreach($ctas as $cta){
			$this->deleteCTALinkages($cta->cta_id);
			$cta->delete();
		}
	}
	
	/**
	* upgrade cta settings
	* convert old cta settings into new requirements
	*
	* @param object $old_cta_settings
	* @param string $cta_type (button,image)
	* @return array new requirements
	* @author Rico Celis
	* @access public
	*/
	static public function updateCtaSettings($old_cta_settings,$cta_type){
		$radius = ($old_cta_settings['border_radius'][0])? $old_cta_settings['border_radius'][0] : "0";
		$actions = array(
			'alt_text' => $old_cta_settings['alt'][0],
			'title_text' => $old_cta_settings['title'][0],
			'cta_link' => $old_cta_settings['cta_link'][0],
			'internal_link' => $old_cta_settings['internal_link'][0],
			'external_link' => $old_cta_settings['external_link'][0]
		);
		if($cta_type == "button"){
			$new_settings = array(
				'normal' => array(
					'text' => array(
						'button_text' => $old_cta_settings['button_text'][0],
						'text_transform' => ($old_cta_settings['text_transform'][0])? "uppercase":"",
						'font_weight' => ($old_cta_settings['font_weight'][0])? "bold":"",
						'font_style' => ($old_cta_settings['font_style'][0])? "italic":"",
						'font_size' => $old_cta_settings['font_size'][0],
						'font_family' => $old_cta_settings['font'][0],
						'color' => $old_cta_settings['color'][0],
					),
					'background' => array(
						'type' => $old_cta_settings['cta_bgr_type'][0],
						'background_color' => $old_cta_settings['background_color'][0],
						'background_top' => $old_cta_settings['background_top'][0],
						'background_bottom' => $old_cta_settings['background_bottom'][0],
						'h_padding' => $old_cta_settings['h_padding'][0],
						'v_padding' => $old_cta_settings['v_padding'][0],
					),
					'border' => array(
						'border_color' => $old_cta_settings['border_color'][0],
						'border_width' => $old_cta_settings['border'][0],
						'border_style' => "solid",
						'border_top_left_radius' => $radius,
						'border_top_right_radius' => $radius,
						'border_bottom_right_radius' => $radius,
						'border_bottom_left_radius' => $radius,
					)
				),
				'actions' => $actions
			);
		}else{
			$new_settings = array(
				'normal' => array(
					'upload_image_id' => $old_cta_settings['upload_image_id'][0],
					'cta_image_url' => $old_cta_settings['cta_image_url'][0],
					'cta_thumbnail' => $old_cta_settings['cta_thumbnail'][0],
				),
				'actions' => $actions
			);
		}
		return $new_settings;
	}
	
	/**
	* when a CTA is deleted
	* delete reference to posts
	* load posts CTA is in and delete short code.
	*
	* @param Eloquent Object $template CTA Template object
	* @return boolean true when completed.
	* @author Rico Celis
	* @access public
	*/
	static public function deleteCTALinkages($cta_id){
		global $wpdb;
		// delete linkages
		$linkages = CallToActionPostLinkage::where('cta_id',$cta_id)->delete();
		// remove all shortcodes from the page
		$shortCode = sanitize_text_field('[brew_cta id="'.$cta_id.'"]');
		$query = "UPDATE ".$wpdb->prefix."posts
			SET post_content = REPLACE (post_content, '{$shortCode}', '')
			WHERE post_content LIKE '%{$shortCode}%'";
		$wpdb->query($query);
	}
	
	/**
	* delete CTA reference from Post/Page
	*
	* @param string $cta_id id of CTA row
	* @param string $wp_post_id wordpress post id
	* @return boolean true when completed.
	* @author Rico Celis
	* @access public
	*/
	static public function deleteCTAPostReference($cta_id,$wp_post_id){
		global $wpdb;
		// delete linkage from table
		$linkages = CallToActionPostLinkage::where('cta_id',$cta_id)->where('wp_post_id',$wp_post_id)->delete();
		// remove short code from the page
		$shortCode = '[brew_cta id="'.$cta_id.'"]';
		$query = sprintf("UPDATE ".$wpdb->prefix."posts
			SET post_content = REPLACE (post_content, '%s', '')
			WHERE ID='%s'",
			sanitize_text_field($shortCode),
			sanitize_text_field($cta_id));
		$wpdb->query($query);
	}
	
	/**
	* check for existing CTA's in string
	* delete existing linkages for post
	* if any found add them to the list.
	*
	* @param string $content wp post content
	* @param $post_id $post_id wp post id
	* @return boolean true when completed.
	* @author Rico Celis
	* @access public
	*/
	static public function checkForCTAsInContent($content,$post_id){
		// delete current linkages
		CallToActionPostLinkage::where('wp_post_id',$post_id)->delete();
		// check status
		$status = get_post_status($post_id);
		$allowed  = array('publish','pending','draft','future','private');
		if(!in_array($status, $allowed)) return;
		$ptn = "/\[brew_cta id=\"([^\]]*)\"\]/";
		preg_match_all($ptn, stripslashes($content), $matches);
		if(!empty($matches[1])){
			foreach($matches[1] as $cta_id){
				$linkage = new CallToActionPostLinkage;
				$linkage->cta_id = $cta_id;
				$linkage->wp_post_id = $post_id;
				$linkage->save();
			}
		}
		return true;
	}
}