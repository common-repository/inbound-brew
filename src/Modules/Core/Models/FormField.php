<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 9/15/15
 * Time: 10:33 AM
 */

namespace InboundBrew\Modules\Core\Models;

use InboundBrew\Modules\Core\Models\LeadData;
use InboundBrew\Modules\Contact\Models\ContactField;
use Illuminate\Database\Eloquent\Model as Eloquent;

class FormField extends Eloquent{
	protected $softDelete = true;
    protected $table = 'ib_lead_fields';
    protected $primaryKey = 'field_id';
    protected $fillable = array('field_value', 'field_type', 'field_name','field_token');
    protected $dates = array('deleted_at');

    public function scopeOfName($query, $name)
    {
        return $query->whereFieldName($name);
    }

    public function leadData()
    {
        return $this->hasMany('InboundBrew\Modules\Core\Models\LeadData', 'term', 'field_token');
    }

    public function templates()
    {
        return $this->belongsToMany('InboundBrew\Modules\Core\Models\Emails', 'ib_email_field', 'field_id', 'email_id');
    }
    
    public function deleteCustomField(){
	    global $wpdb;
	    //delete related data
	    LeadData::where("data_term",$this->field_token)->delete();
	    //delete tokens from emails
		$query = 'UPDATE '.$wpdb->prefix.'ib_emails	SET email_value = REPLACE (email_value, "{{'.$this->field_token.'}}", "")
			WHERE email_value LIKE "%{{'.$this->field_token.'}}%"';
		$wpdb->query($query);
		// delete fields from contact forms
		$results = ContactField::where("field_id",$this->field_id);
		$fields = $results->get();
		if(count($fields)){
			foreach($fields as $field){
				// delete field from post
				$post = Post::find($field->post_id);
				if(@$post->ID){
					$pattern = '/<div id="cf-'. $field->field_id . '"(.*)<\/div>/';
					$string = preg_replace($pattern, "", $post->post_content);
					$post->post_content = $string;
					$post->save();
					update_post_meta($post->ID, "post_content", $string);
				}
			}
			$results->delete();
		}
		$this->delete();
		return true;
    }
}