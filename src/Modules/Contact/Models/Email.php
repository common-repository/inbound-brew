<?php

/**
 * Created by PhpStorm.
 * User: sean
 * Date: 10/28/15
 * Time: 12:12 PM
 */

namespace InboundBrew\Modules\Contact\Models;

use InboundBrew\Modules\Core\Models\PostMeta;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Email extends Eloquent {

    protected $table = 'ib_emails';
    protected $primaryKey = 'email_id';
    protected $fillable = array('email_title', 'email_value');
    protected $dates = array('deleted_at');

    public function scopeOfName($query, $name) {
        return $query->whereName($name);
    }

    public function fields() {
        return $this->belongsToMany('InboundBrew\Modules\Core\Models\FormField', 'ib_email_field', 'email_id', 'field_id');
    }

    static function defaultEmailCopy() {
        return "<p>Dear {{first_name}},</p>
        <p>[ content of the email this may include some contact form data.
        While you are able to add media or any other content that is supported by tinymce editor,
        we suggest you stick to just text. *short codes will not work here ]</p>
        <p>[thank you part]</p>
        <p><a href='{{download_link}}' title='Click to Download'>link</a> for download</p>";
    }

    /*
     * get all contact forms linked to this email.
     *
     * @param $email_id email record id
     *
     * @author Rico Celis
     * @access Public
     */

    public function contactForms($email_id = null) {
        if (!$email_id)
            $email_id = $this->email_id;
        $forms = PostMeta::where('meta_key', '=', 'email_template')->where('meta_value', '=', $email_id)->get();
        $arr = array();
        foreach ($forms as $key => $form) {
            if ($form->post->post_status == 'publish' && $form->post->post_type == "ib-contact-form") {
                $post = Post::find($form->post->ID);
                $values = array(
                    'post_id' => $post->ID,
                    'post_title' => $post->post_title,
                    'fields' => array()
                );
                foreach ($post->fields as $field) {
                    $values['fields'][] = $field->field_token;
                }
                $arr[$post->ID] = $values;
            }
        }
        return $arr;
    }

    /*
     * delete linkage to contact forms and landing pages.
     *
     * @author Rico Celis
     * @access Public
     */

    public function deleteLinkedData() {
        // delete linkages to contact forms
        $forms = PostMeta::where('meta_key', '=', 'email_template')->where('meta_value', '=', $this->email_id)->get();
        foreach ($forms as $form) {
            $form->delete();
        }
    }

    /*
     * get list of emails to use in form select
     */

    static function emails_list() {
        $results = self::orderBy("email_title")->get();
        $emails = array();
        if (count($results)) {
            foreach ($results as $email) {
                $emails[$email->email_id] = $email->email_title;
            }
        }
        return $emails;
    }

}
