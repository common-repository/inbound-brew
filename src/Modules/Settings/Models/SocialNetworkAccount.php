<?php

/**
 * Created by Rico Celis.
 * Date: 10/06/15
 * Time: 10:11 PM
 */

namespace InboundBrew\Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class SocialNetworkAccount extends Eloquent {

    //protected $softDelete = true;
    protected $table = 'ib_social_network_accounts';
    protected $primaryKey = 'account_id';

    /**
     * add new account
     * use network,account_type,account_type_id to check if exists
     *
     * @param array data field values
     * @param wether account is active or not.
     * @return Eloquent object
     * @author Rico Celis
     * @access public
     */
    public function addAccount($data, $is_active = 1) {
        $_defaults = array('display_name' => "");
        $data = array_merge($_defaults, $data);
        $account = self::where('social_network', $data['social_network'])->where('account_type', $data['account_type'])->where('account_type_id', $data['account_type_id'])->first();
        if (@$account->account_id) { //exists?
            // update name
            $account->display_name = stripslashes(stripslashes($data['display_name']));
            // update token
            $account->token = $data['token'];
            if (@$data['meta1'])
                $account->meta1 = $data['meta1'];
            if (@$data['meta2'])
                $account->meta2 = $data['meta2'];
            $account->is_active = $is_active;
            $account->save();
        }else {
            $account = new SocialNetworkAccount;
            $account->social_network = $data['social_network'];
            $account->account_type = $data['account_type'];
            $account->account_type_id = $data['account_type_id'];
            $account->token = $data['token'];
            //gross strip slashes becuase, for some reason, we are getting triple slashes back from FB 
            $account->display_name = stripslashes(stripslashes($data['display_name']));
            if (@$data['meta1'])
                $account->meta1 = $data['meta1'];
            if (@$data['meta2'])
                $account->meta2 = $data['meta2'];
            $account->is_active = $is_active;
            $account->save();
        }
        return $account;
    }

    /**
     * save tokens for users facebook accounts
     *
     * @param string $network what network we are working with.
     * @param array $accounts list of users accounts (response from Social Network API)
     * @return boolean true when done, false if token was not valid
     * @author Rico Celis
     * @access public
     */
    public function saveAccountTokens($network, $accounts) {
        foreach ($accounts as $account) {
            $this->addAccount(array(
                'social_network' => $network,
                'account_type' => $account['account_type'],
                'account_type_id' => $account['id'],
                'token' => $account['access_token'],
                'meta1' => @$account['meta1'],
                'meta2' => @$account['meta2'],
                'display_name' => stripslashes(stripslashes($account['name'])),
                    ), $account['is_active']);
        }
        return false; // token was invalid should probably re-validate.
    }

    /**
     * get list of account for a network
     *
     * @param string $network what network we are working with.
     * @param array $options list of options
     * @return array list of Eloquent models or array (depending option provided)
     * @author Rico Celis
     * @access public
     */
    public function getAccountList($network, $options = array()) {
        $_defaults = array(
            'as_array' => false, // return results as array instead of Eloquent models
            'display_data' => false, // only return data necesary for displaying list
            'account_type' => false, // filter by token type
        );
        $options = array_merge($_defaults, $options);
        $query = self::where('social_network', $network);
        if ($options['account_type'])
            $query->where('account_type', $options['account_type']);
        $query->where('is_active', 1);
        $accounts = $query->orderBy('account_type', "DESC")->orderBy('display_name', "ASC")->get();
        if ($options['as_array'] || $options['display_data']) {
            $tokens = $accounts->toArray();
            if ($options['display_data']) {
                $response = array();
                foreach ($tokens as $index => $value) {
                    $response[] = array(
                        'account_id' => $value['account_id'],
                        'social_network' => $value['social_network'],
                        'account_type' => $value['account_type'],
                        'display_name' => $value['display_name']
                    );
                }
                return $response;
            }
        }
        return $tokens;
    }

}
