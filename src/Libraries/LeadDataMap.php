<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 8/6/15
 * Time: 11:20 AM
 */

namespace InboundBrew\Libraries;
use InboundBrew\Modules\Core\Models\FormField;

/**
 * Class ContactDataMap
 * @package InboundBrew\Libraries
 */
class LeadDataMap {
    /**
     * @var array
     */
    private static $lead = array(
            'lead_email'   =>'email',
            'lead_id'      =>'lead_id',
            'lead_ip'      =>'ip_address',
            'lead_first_name'    =>'first_name',
            'lead_last_name'    =>'last_name',
            'lead_address' =>'address',
            'lead_address2'=>'address2',
            'lead_city'    =>'city',
            'lead_phone'   =>'phone',
            'lead_postal'  =>'postal',
            'country_id'   =>'country',
            'lead_state'   =>'state',
            'lead_dob'     =>'birth_date',
            'lead_email2'   =>'email2',
            'lead_phone2'   =>'phone2',
            'lead_social_facebook'   =>'social_facebook',
            'lead_social_twitter'   =>'social_twitter',
            'lead_email2'   =>'email2',
    );

    /**
     * @param $post
     * @return \stdClass
     */
    public static function mapData($post)
    {
        foreach ($post as $key=>$value) {
	        if(is_object($value)) continue;
            foreach (self::$lead as $field=>$test) {
                if (empty($value)) continue;
                if (preg_match('/^' . $test . '$/',$key)) {
                    $obj['lead'][$field] = self::prepareData($field,$value);
                }
            }
            foreach(self::leadFields() as $test) {
                if (empty($value)) continue;
                if (preg_match('/^' . $test->field_token . '$/',$key)) {
                    if (is_array($value)) {
                        foreach($value as $item) {
                            $obj['custom'][$item] = $test->field_token;
                        }
                    } else {
                        $obj['custom'][$value] = $test->field_token;
                    }
                }
            }

        }

        return json_decode(json_encode($obj));
    }

    /**
     * @param $field
     * @param $value
     * @return bool|string
     */
    private static function prepareData($field,$value)
    {
        switch($field)
        {
            case 'lead_dob':
            case 'date':
                $value = date('Y-m-d',strtotime($value));
                break;
        }
        return $value;
    }

    private static function leadFields()
    {
        return FormField::where('field_custom',1)->get();
    }
    
    /*
	* for some reason Sean created tokens that do not match database fields
	* we need to match to the token so we can get the data for it.
	* 
	* @param string $email_token token in email template.
	* @return string associated database field. (if not found it will return the same.)
	* @access static
	* @author Rico.
	*/
    static function mapTokenToDatabaseField($email_token){
	    foreach(self::$lead as $db_field => $token){
		    if($email_token == $token){
			    return $db_field;
		    }
	    }
	    return $email_token;
    }

}