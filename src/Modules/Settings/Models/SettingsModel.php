<?php

/**
 * Created by Rico Celis.
 * Date: 11/06/15
 * Time: 10:00 AM
 */

namespace InboundBrew\Modules\Settings\Models;

use InboundBrew\Modules\Settings\Models\SocialNetworkAccount;
use InboundBrew\Modules\Settings\Models\SocialNetworkPostSettingAccount;
use Illuminate\Database\Eloquent\Model as Eloquent;
// libraries
use InboundBrew\Libraries\FacebookHelper;
use InboundBrew\Libraries\TwitterHelper;
use InboundBrew\Libraries\LinkedInHelper;

class SettingsModel extends Eloquent {

    //protected $softDelete = true;
    protected $table = 'ib_settings';
    protected $primaryKey = 'settings_id';
    protected $token_expiration_facebook = 60; // number of days a token for facebook is valid
    protected $token_expiration_linkedin = 60; // number of days a token for linkedIn is valid
    protected $token_expiration_twitter = 0; // number of days a token for facebook is valid
    public static $wizzard_steps = array(
        'emails' => array(
            'title' => "Email Templates",
            'url' => "admin.php?page=ib-email-admin&wizzard"),
        'contact_forms' => array(
            'title' => "Contact Forms",
            'url' => "admin.php?page=ib-contact-forms&wizzard"),
        'social_settings' => array(
            'title' => "Social Push Settings",
            'url' => "admin.php?page=ib-admin-settings&section=ib_social_settings&wizzard"),
        'landing_pages' => array(
            'title' => "Landing Pages",
            'url' => "admin.php?page=landing-page-admin&wizzard"),
        'ctas' => array(
            'title' => "Call To Actions",
            'url' => "admin.php?page=ib-call-to-action&wizzard")
    );

    /**
     * load settings using the default id
     * if row is not found create default settings.
     *
     * @return Eloquent Model settings information
     * @author Rico Celis
     * @access public
     */
    public function loadSettings() {
        // see if settings row exists
        $settings = self::orderBy('created_at', 'desc')->first();
        if (empty($settings->settings_id)) { // create default settings
            $settings = new SettingsModel;
            $settings->save();
            return self::find($settings->settings_id);
        } else {
            return $settings;
        }
    }

    /**
     * update settings
     * @param array $new_settings (indexed array where index is field name)
     * @return boolean true when done.
     *
     * @author Rico Celis
     * @access public
     */
    public function updateSettings($new_settings) {
        $settings = $this->loadSettings();
        foreach ($new_settings as $field => $value) {
            $settings->$field = $value;
        }
        $settings->save();
        return true;
    }

    /**
     * user has connected to a social network and we need to track when the tokens will expire
     *
     * @param string $network what network we are working with.
     * @param string $expires time (in seconds) when token expires.
     * @param string $screen_name screen name for social account.
     * @return boolean true when done.
     * @author Rico Celis
     * @access public
     */
    public function socialNetworkConnected($network, $expires = null, $screen_name = "") {
        $settings = $this->loadSettings();
        $connected = "social_connected_{$network}";
        $name = "social_name_{$network}";
        if ($expires) {
            $settings->$connected = date('Y-m-d H:i:s', $expires);
        } else {
            $expiration = "token_expiration_{$network}";
            if (!$this->$expiration)
                $settings->$connected = "0000-00-00 00:00:00";
            else
                $settings->$connected = date('Y-m-d H:i:s', strtotime("+" . $this->$expiration . " days"));
        }
        if ($screen_name)
            $settings->$name = $screen_name;
        $settings->save();
        return true;
    }

    /**
     * user wants to remove their settings from InboundBrew
     * delete all tokens associated with network
     * delete all accounts linked to post settings.
     * keep post settings just in case user wants to re-connect.
     *
     * @param string $network what network we are working with.
     * @return boolean true when done.
     * @author Rico Celis
     * @access public
     */
    public function disconnectSocialNetwork($network) {
        // disconnect
        $settings = $this->loadSettings();
        $field = "social_connected_{$network}";
        $settings->$field = null;
        $settings->save();
        // delete accounts
        /* $SocialNetworkAccount = new SocialNetworkAccount;
          $SocialNetworkAccount->where('social_network',$network)->delete();
          // delete post setting account linkage
          $SocialNetworkPostSettingAccount = new SocialNetworkPostSettingAccount;
          $SocialNetworkPostSettingAccount->where('social_network',$network)->delete(); */
        return true;
    }

    /**
     * get login url for social network
     *
     * @param string $network what network we are working with.
     * @return string login url.
     * @author Rico Celis
     * @access public
     */
    public function getSocialNetworkLoginUrl($network) {
        $token_url = get_bloginfo('url') . "/" . BREW_SOCIAL_API_VERIFY_TOKEN_SLUG;
        $redirect_uri = get_admin_url() . "admin.php?page=ib-admin-settings&section=oauth_callback";
        if ($network == 'google') {
        } else {
            return BREW_SOCIAL_API_REQUEST_URL . "?action=connect_network&network={$network}&token_url=" . urlencode($token_url) . "&redirect_uri=" . urlencode($redirect_uri);
        }
        /* switch($network){
          case "facebook":
          $Facebook = new FacebookHelper;
          return $Facebook->getLoginUrl();
          break;
          case "twitter":
          $Twitter = new TwitterHelper;
          return $Twitter->getLoginUrl();
          break;
          case "linked_in":
          $LinkedIn = new LinkedInHelper;
          return $LinkedIn->getLoginUrl();
          break;
          } */
    }

    /**
     * user has completed a wizzard step
     *
     * @param string $step referer to SettingsModel::$wizzard_steps for list.
     * @return boolean when done.
     * @author Rico Celis
     * @access public
     */
    public function wizzardStepCompleted($step) {
        $settings = $this->loadSettings();
        $step = "wizzard_{$step}";
        if ($settings->$step)
            return true; // already updated.
        $settings->$step = 1;
        $settings->save();
        return true;
    }

}
