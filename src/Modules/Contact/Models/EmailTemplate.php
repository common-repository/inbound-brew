<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 10/28/15
 * Time: 12:12 PM
 */

namespace InboundBrew\Modules\Contact\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class EmailTemplate extends Eloquent{

    protected $table = 'ib_email_templates';
    protected $primaryKey = 'email_template_id';
    protected $fillable = array('name');
    protected $dates = array('deleted_at');
    
    static function defaultTemplateValues(){
	    $default = array(
		    'info' => array(
			   'send_to' => "{{email}},"
			),
		    'top_bar' => array(
			    'container_visible' => "1",
			    'background' => "0083CA",
			    'padding_left' => "0",
			    'padding_right' => "20",
			    'padding_top' => "5",
			    'padding_bottom' => "5"
		    ),
		    'top_bar_social_icons' => array(
			    'container_visible' => "1",
			    'color' => "FFFFFF",
			    'icon_size' => "2",
			    'icon_spacing' => "10",
			    'facebook' => "on",
			    'twitter' => "on",
			    'linked_in' =>"on",
			    'google_plus' => "on"
		    ),
		    'header' => array(
			    'container_visible' => "1",
			    'login_image_align' => "left",
			    'background' => "FFFFFF",
			    'padding_top' => "20",
			    'padding_bottom' => "20",
			    'padding_left' => "20",
			    'padding_right' => "20",
			    'margin_top' => "0",
			    'margin_bottom' => "0"
		    ),
		    'banner_image' => array(
			    'container_visible' => "1",
			    'image' => "",
			    'margin_top' => "0",
			    'margin_bottom' => "0"
		    ),
		    'body' => array(
			    'color' => "474747",
			    'background' => "FFFFFF",
			    'padding_top' => "20",
			    'padding_bottom' => "20",
			    'padding_left' => "20",
			    'padding_right' => "20",
		    ),
		    'footer' => array(
			    'copyright' => "1",
			    'logo_container' => "1",
			    'contact_website' => get_bloginfo('url'),
			    'contact_email' => get_bloginfo('admin_email'),
			    'contact_custom' => get_bloginfo('name'),
			    'logo_margin_left' => "0",
			    'logo_margin_right' => "0",
			    'logo_margin_top' => "0",
			    'logo_margin_bottom' => "0",
			    'padding_top' => "20",
			    'padding_bottom' => "20",
			    'padding_right' => "20",
			    'padding_left' => "20",
			    'background' => "0083CA",
			    'color' => "FFFFFF"
		    ),
		    'footer_social_icons' => array(
			    'container_visible' => "1",
			    'color' => "FFFFFF",
			    'icon_size' => "2",
			    'icon_spacing' => "10",
			    'facebook' => "on",
			    'twitter' => "on",
			    'linked_in' =>"on",
			    'google_plus' => "on"
		    )
	    );
	    return $default;
    }
    
    public function emails(){
		return $this->hasMany('InboundBrew\Modules\Contact\Models\Email',"email_template_id");
	}
	
	/*
	* get list of emails to use in form select
	*/
	static function templates_list(){
		$results = self::orderBy("name")->get();
		$emails = array();
		if(count($results)){
			foreach($results as $email){
				$emails[$email->email_template_id] = $email->name;
			}
		}
		return $emails;
	}
}