<?php

/**
 * Created by sean.carrico.
 * User: sean
 * Date: 8/4/15
 * Time: 11:33 AM
 */

namespace InboundBrew\Modules\Leads\Controllers;

use InboundBrew\Modules\Core\AppController;
use InboundBrew\Modules\Core\Models\FormField;
use InboundBrew\Modules\Core\Models\Lead as LeadModel;
use InboundBrew\Modules\Core\Models\Country;
use InboundBrew\Modules\Core\Models\LeadData;
use InboundBrew\Modules\Core\Models\LeadHistory;
use InboundBrew\Modules\Core\Models\State;
use InboundBrew\Modules\Leads\Models\LeadView;
use InboundBrew\Modules\Settings\Models\SettingsModel;
// email
use InboundBrew\Modules\Contact\Models\Email as EmailModel;
use InboundBrew\Modules\Contact\Models\EmailTemplate;

// libraries
use Valitron\Validator;
use InboundBrew\Libraries\FormHelper;
use InboundBrew\Libraries\DateHelper;
use InboundBrew\Libraries\LayoutHelper;
use InboundBrew\Libraries\FontAwesomeHelper;
use InboundBrew\Libraries\LeadDataMap;

class Lead extends AppController {

    /**
     *
     */
    const VIEW_PATH = 'Leads/views/';

    /**
     * @var string
     */
    private $post_type = 'ib-leads-admin';

    /**
     *
     */
    public function __construct() {
        parent::init();
        $this->init();
        $this->partials_path = BREW_MODULES_PATH . "Leads/views/partials/";
    }

    /**
     *
     */
    public function init() {
        // basic post handler
        add_action('admin_post_export_ib_leads', array($this, 'exportLeads'));
        add_action('admin_post_import_ib_leads', array($this, 'importLeads'));
        // ajax hook
        /* 		add_action('wp_ajax_delete_ib_lead', array($this,'deleteLead'));
          add_action('wp_ajax_update_ib_lead', array($this,'updateLead'));
          add_action('wp_ajax_search_ib_leads', array($this,'searchLead'));
          add_action('wp_ajax_set_ib_lead_leave', array($this,'searchLead'));
          add_action('wp_ajax_update_lead_score', array($this,'updateLeadScore'));
          add_action('wp_ajax_update_lead_type', array($this,'updateLeadType')); */

        add_action('wp_ajax_ib_update_lead_field', array($this, 'updateLeadField'));
        add_action('wp_ajax_ib_verify_lead_email', array($this, 'verifyLeadEmail'));
        add_action('wp_ajax_ib_lead_activity', array($this, 'saveLeadActivity'));
        add_action('wp_ajax_ib_lead-filters', array($this, 'getLeadTableFilteredView'));
        add_action('wp_ajax_ib_new_lead_view', array($this, 'createNewLeadView'));
        add_action('wp_ajax_ib_update_lead_view', array($this, 'updateLeadView'));
        add_action('wp_ajax_ib_delete_lead_view', array($this, 'deleteLeadView'));
        add_action('wp_ajax_ib_lead_recent_history', array($this, 'leadRecentHistory'));
        add_action('wp_ajax_ib_archive_lead', array($this, 'archiveLead'));
        add_action('wp_ajax_ib_send_lead_email', array($this, 'sendLeadEmail'));
        add_action('wp_ajax_ib_restore_lead', array($this, 'restoreLead'));

//        add_action('admin_post_add_ib_lead_history', array($this,'addLeadHistory'));
        // admin post
        add_action('admin_post_ib_add_lead', array($this, 'saveNewLead'));
        // add scripts
        add_action('admin_enqueue_scripts', array($this, 'addAdminScripts'));


        if (@$_GET['action'] == "ib_preview_lead_email")
            add_action('admin_init', array($this, "previewHistoryEmail"), 1);
    }

    /**
     *
     */
    public function addAdminScripts() {
        if (isset($_GET['page']) && @$_GET['page'] == $this->post_type) {
            wp_enqueue_script('ib-country-dropdown', BREW_MODULES_URL . 'Core/assets/js/ib-country-dropdown.js', array('jquery'), BREW_ASSET_VERSION, true);
            //wp_enqueue_script('ib-lead-admin', BREW_MODULES_URL.'Leads/assets/js/ib-lead-admin.jquery.js', array('jquery'), BREW_ASSET_VERSION, true );
            wp_enqueue_script('ib-lead-admin', BREW_MODULES_URL . 'Leads/assets/js/ib_lead_editor.jquery.js', array('jquery'), BREW_ASSET_VERSION, true);
            wp_enqueue_script('ib-lead-list', BREW_MODULES_URL . 'Leads/assets/js/ib_leads-list.jquery.js', array('jquery'), rand(), true);
            wp_enqueue_script('ib-star-rating', BREW_MODULES_URL . 'Core/assets/js/ib-stars.jquery.js', array('jquery'), BREW_ASSET_VERSION);
            wp_enqueue_style('ib-lead-management', BREW_MODULES_URL . 'Leads/assets/css/ib-leads.css', array(), BREW_ASSET_VERSION);
            wp_localize_script(
                    'ib-country-dropdown-js', 'ibLocals', array(
                'ibCountry' => json_encode(Country::orderBy('country_name')->get()),
                'ibState' => State::all(),
                    )
            );

            wp_localize_script(
                    'ib-lead-list', 'ibLeadAjax', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'ibLeadNonce' => wp_create_nonce('ib-lead-nonce')
                    )
            );

            if (@$_GET['section'] == "ib_add_lead" || @$_GET['section'] == "ib_edit_lead") {
                wp_enqueue_script('ib-masked-input', BREW_MODULES_URL . 'Core/assets/js/jquery.maskedinput.min.js', array('jquery'), BREW_ASSET_VERSION);
            }

            /* Shepherd Load Decisions */

            //No section - must be the main leads page
            if (!isset($_GET['section'])) {
                wp_enqueue_script('ib-leads-main-shepherd', BREW_MODULES_URL . 'Leads/assets/js/ib-leads-main-shepherd.js', array('jquery'), BREW_ASSET_VERSION);
            }
            // Add Leads Page
            else if ($_GET['section'] == 'ib_add_lead') {
                wp_enqueue_script('ib-leads-add-shepherd', BREW_MODULES_URL . 'Leads/assets/js/ib-leads-add-shepherd.js', array('jquery'), BREW_ASSET_VERSION);
            }
        }
    }

    /**
     *
     */
    public function loadAdmin() {
        $data['post_type'] = $this->post_type;
        switch (@$_GET['section']) {
            case 'manage':
                #breadcrumbs;
                $this->Breadcrumb->add("Lead Import/Export");
                $data['Breadcrumb'] = $this->Breadcrumb;
                echo $this->load->view(self::VIEW_PATH . 'manage', $data);
                break;
            case 'settings':
                #breadcrumbs;
                $this->Breadcrumb->add("Lead Settings");
                $data['Breadcrumb'] = $this->Breadcrumb;
                $data['forms'] = FormField::where('field_custom', 1)->get();
                // load view
                echo $this->load->view(self::VIEW_PATH . 'settings', $data);
                break;
            case 'lead':
                $this->viewLead($_GET['id']);
                break;
            case 'note':
                $data = $this->editLead($_GET['id']);
                //print_debug($data,true);
                $this->Breadcrumb->add('Lead Management', "admin.php?page={$this->post_type}");
                $this->Breadcrumb->add("{$data['lead']->lead_first_name} {$data['lead']->lead_last_name}", "admin.php?page={$this->post_type}&section=lead&id={$data['lead']->lead_id}");
                $this->Breadcrumb->add("Add Note");
                $data['Breadcrumb'] = $this->Breadcrumb;
                echo $this->load->view(self::VIEW_PATH . 'note', $data);
                break;
            case 'share':
                $data = $this->editLead($_GET['id']);
                //print_debug($data,true);
                $this->Breadcrumb->add('Lead Management', "admin.php?page={$this->post_type}");
                $this->Breadcrumb->add("{$data['lead']->lead_first_name} {$data['lead']->lead_last_name}", "admin.php?page={$this->post_type}&section=lead&id={$data['lead']->lead_id}");
                $this->Breadcrumb->add("Share Item");
                $data['Breadcrumb'] = $this->Breadcrumb;
                echo $this->load->view(self::VIEW_PATH . 'share', $data);
                break;
            case "ib_add_lead":
                $this->addLead();
                break;
            case "ib_edit_lead":
                $this->editLeadData($_GET['lid']);
                break;
            default:
                $this->adminList();
                break;
        }
    }

    /**
     * Load list of leads
     *
     * @author Rico Celis
     * @access Private
     */
    private function adminList() {
        $this->Breadcrumb->add("Lead Management");
        $data['Breadcrumb'] = $this->Breadcrumb;
        //print_debug($data['leads'],true);
        $data['post_type'] = $this->post_type;
        $data['field_tokens'] = $this->getFieldTokens();
        $data['Date'] = new DateHelper;
        $data['Form'] = new FormHelper;
        $data['Layout'] = new LayoutHelper;
        $data['users'] = $this->usersList();
        $data['partials_path'] = $this->partials_path;
        $user = wp_get_current_user();
        $data['views'] = LeadView::getUserViews($user->ID);
        $lead_filters = $data['filters'] = $this->getLeadFilterFields();
        $data['active_view'] = (@$_COOKIE['inboundbrew_active_lead_view']) ? $_COOKIE['inboundbrew_active_lead_view'] : "all";
        $filters = $data['views'][$data['active_view']]['view_filters'];
        $data['leads'] = $this->getFilteredLeadResults($filters, $lead_filters);
        $data['Layout'] = new LayoutHelper;
        // emails and templates
        $data['emails'] = EmailModel::emails_list();
        $data['templates'] = EmailTemplate::templates_list();
        echo $this->load->view(self::VIEW_PATH . 'admin_list', $data);
    }

    private function viewLead($lead_id) {
        // load lead
        $lData = LeadModel::leadFormData($lead_id);
        if ($lData) {
            if ($lData['Lead']['deleted_at'] != null)
                $this->_error("This lead is currently archived.", false, "archive");
            $dob = date("F d, Y", strtotime($lData['Lead']['lead_dob']));
            $lData['Lead']['lead_dob'] = $dob;
            $data['lead_history'] = LeadHistory::where('lead_id', $lead_id)->orderBy("created_at", "desc")->get();
            $this->Breadcrumb->add('Lead Management', "admin.php?page={$this->post_type}");
            $this->Breadcrumb->add("View Lead: " . $lData['Lead']['lead_first_name'] . " " . $lData['Lead']['lead_last_name']);
            // helpers
            $data['Date'] = new DateHelper;
            $Form = new FormHelper;
            $Form->data = $lData;
            $Form->hiddenFields = true;
            $data['Form'] = $Form;
            $data['Breadcrumb'] = $this->Breadcrumb;
            $data['form_action'] = "ib_edit_lead";
            $data['lead_id'] = $lead_id;
            $data['post_type'] = $this->post_type;
            // additional variables
            $data = $this->addFormData($data, array('field_tokens' => true));
            // emails and templates
            $data['emails'] = EmailModel::emails_list();
            $data['templates'] = EmailTemplate::templates_list();

            // load view
            echo $this->load->view(self::VIEW_PATH . 'lead_editor', $data);
        } else { // invalid
            $this->_error("Invalid Lead", true);
            $this->jsRedirect("admin.php?page=" . $this->post_type);
        }
    }

    /** load create new lead form
     *
     * @author Rico Celis
     * @access public
     */
    private function addLead() {
        // breadcrumb
        $this->Breadcrumb->add("Lead Management", "admin.php?page=" . $this->post_type);
        $this->Breadcrumb->add("Add New Lead");
        $data['Breadcrumb'] = $this->Breadcrumb;
        $data['Form'] = new FormHelper;
        $data['form_action'] = "ib_add_lead";
        $data['post_type'] = $this->post_type;
        $data = $this->addFormData($data);

        // load view
        echo $this->load->view(self::VIEW_PATH . 'lead_editor', $data);
    }

    /** verify unique email
     *
     * @author Rico Celis
     * @access public
     */
    public function verifyLeadEmail() {
        $result = array(
            'success' => true,
        );
        $nonce = @$_POST['nonce'];
        if ($nonce && wp_verify_nonce($nonce, 'ib-lead-nonce') && current_user_can("edit_posts")) {
            if (LeadModel::withTrashed()->where("lead_email", $_POST['email'])->exists()) { // another lead has email
                $result = array(
                    'success' => false,
                    'message' => sprintf("%s is associated with another lead. Please choose a different email.", $_POST['email'])
                );
            }
        }
        die(json_encode($result));
    }

    /* add new lead activity
     *
     * @author Rico Celis
     * @access public
     */

    public function saveLeadActivity() {
        $result = array(
            'success' => false,
            'message' => "Unable to save field. Please try again."
        );
        $post = $_POST['data']['LeadActivity'];
        $lead = LeadModel::withTrashed()->find($post['lead_id']);
        $nonce = @$_POST['nonce'];
        if ($nonce && wp_verify_nonce($nonce, 'ib-lead-nonce') && current_user_can("edit_posts") && @$lead->lead_id) {
            $response_message = "Field saved.";
            if ($post['history_id']) {
                $mode = "edit";
                $history = LeadHistory::find($post['history_id']);
            } else {
                $mode = "add";
                $history = new LeadHistory;
            }
            switch ($post['activity_type']) {
                case "phone":
                    $history->history_event = $history_event = "Phone Call Record Added.";
                    $history->history_type = BREW_LEAD_HISTORY_TYPE_PHONE_CALL;
                    $icon = "fa-phone-square";
                    $response_message = "Phone Call saved for {$lead->lead_first_name}.";
                    break;
                case "comment":
                    $history->history_event = $history_event = "Comment Added.";
                    $history->history_type = BREW_LEAD_HISTORY_TYPE_NOTE;
                    $icon = "fa-comment";
                    $response_message = "Comment saved for for {$lead->lead_first_name}.";
                    break;
            }
            $comment = str_replace("\n", "<br>", $post['comment']);
            $comment .= ' <a href="" class="ib_edit_history">[edit]</a>';
            $current_user = wp_get_current_user();
            $history->history_note = $post['comment'];
            $history->lead_id = $post['lead_id'];
            $history->wp_user_id = $current_user->ID;
            $history->save();
            $Date = new DateHelper;
            // return
            $result = array(
                'success' => true,
                'history' => array(
                    'mode' => $mode,
                    'history_id' => $history->history_id,
                    'stamp' => date("YmdHis"),
                    'date' => $Date->format(BREW_WP_DATE_FORMAT . " " . BREW_WP_TIME_FORMAT, $history->created_at, true),
                    'icon' => $icon,
                    'history_event' => $history_event,
                    'history_note' => $comment,
                    'user' => $current_user->first_name . " " . $current_user->last_name
                ),
                'message' => $response_message);
        }
        die(json_encode($result, JSON_UNESCAPED_SLASHES));
    }

    /* update lead field through ajax
     *
     * @author Rico Celis
     * @access public
     */

    public function updateLeadField() {
        $result = array(
            'success' => false,
            'message' => "Unable to save field. Please try again."
        );
        $nonce = @$_POST['nonce'];
        if ($nonce && wp_verify_nonce($nonce, 'ib-lead-nonce') && current_user_can("edit_posts")) {
            $post = $_POST['data'];
            $lead_id = $post['lead_id'];
            $lead = LeadModel::withTrashed()->find($lead_id);
            $old_camp = $lead->campaign_id;
            if (@$lead->lead_id) {
                $icon = "fa-pencil";
                $Date = new DateHelper;
                $field_tokens = $this->getFieldTokens();
                $current_user = wp_get_current_user();
                // lead fields
                if (!empty($post['Lead'])) {
                    foreach ($post['Lead'] as $field => $value) {
                        if ($field == "nonus_state" || $field == "us_state")
                            $field = "lead_state";
                        // verify email before saving.

                        if ($field == "lead_email") {
                            $value = strtolower($value);
                            if (LeadModel::withTrashed()->where("lead_email", $value)->where("lead_id", "!=", $lead->lead_id)->exists()) { // another lead has email
                                die(json_encode(array(
                                    'success' => false,
                                    'message' => sprintf("%s is associated with another lead. Please choose a different email.", $value),
                                    'email' => $lead->lead_email,
                                )));
                            }
                        }
                        if ($field == 'lead_dob') {
                            $value = date("Y-m-d", strtotime($value));
                        }
                        $lead->$field = $value;
                        $lead->save();
                        $history = new LeadHistory;
                        $history->history_type = BREW_LEAD_HISTORY_TYPE_UPDATED;
                        $history->history_note = "";

                        if (empty($value))
                            $value = "empty";
                        switch ($field) {
                            case "assigned_to":
                                $history->history_type = BREW_LEAD_HISTORY_ASSIGNED;
                                $history->history_event = 'Lead assigned to {{user:' . $value . '}}.';
                                $user = get_userdata($value);
                                $history_event = "Lead assigned to {$user->first_name} {$user->last_name}";
                                $icon = "fa-arrow-right";
                                break;
                            case "lead_picture":
                                $history->history_type = BREW_LEAD_HISTORY_TYPE_PICTURE;
                                $history->history_event = $history_event = "Lead picture updated.";
                                $icon = "fa-picture-o";
                                break;
                            case "type_id":
                                $types = array("", "Prospect", "Lead", "Customer", "Affiliate");
                                $history->history_event = $history_event = sprintf("Lead Type changed to %s.", $types[$value]);
                                break;
                            case "country_id":
                                $countries = Country::countryList("country_name");
                                if ($value != "empty")
                                    $value = $countries[$value];
                                $history->history_event = $history_event = sprintf("Country changed to %s.", $value);
                                break;
                            case "lead_dob":
                                $history->history_event = $history_event = sprintf("Birthdate changed to %s.", date("F d, Y", strtotime($value)));
                                break;
                            case "lead_opt_in":
                                if ($value == "null")
                                    $history->history_event = $history_event = "Opt-in changed to Opted-Out";
                                else
                                    $history->history_event = $history_event = "Opt-in changed to Opted-In";
                                break;
                            default:
                                $history->history_event = sprintf("{{%s}} changed to %s.", $field, $value);
                                $history_event = str_replace('{{' . $field . '}}', $field_tokens[$field], $history->history_event);
                                break;
                        }
                        $history->wp_user_id = $current_user->ID;
                        $history->lead_id = $lead_id;
                        $history->save();
                    }
                }
                // custom fields
                if (!empty($post['LeadData'])) {
                    LeadData::saveLeadData($lead_id, $post['LeadData'], array("add_history" => true));
                    foreach ($post['LeadData'] as $term => $value) {
                        if (is_array($value))
                            $value = implode(", ", $value);
                        $history_event = $field_tokens[$term] . " changed to " . $value;
                    }
                    $history_date = $Date->format(BREW_WP_DATE_FORMAT . " " . BREW_WP_TIME_FORMAT, current_time('mysql', 0), true);
                }
                $result = array(
                    'success' => true,
                    'history' => array(
                        'stamp' => date("YmdHis"),
                        'date' => !empty($history->created_at) ? $Date->format(BREW_WP_DATE_FORMAT . " " . BREW_WP_TIME_FORMAT, $history->created_at, true) : $history_date,
                        'icon' => $icon,
                        'history_event' => $history_event,
                        'mode' => "add",
                        'user' => $current_user->first_name . " " . $current_user->last_name
                    ),
                    'message' => "Field saved.");
            }
        }
        die(json_encode($result));
    }

    /* save new lead through post
     *
     * @author Rico Celis
     * @access public
     */

    public function saveNewLead() {
        $nonce = @$_POST['_wpnonce'];
        if ($nonce && wp_verify_nonce($nonce, 'ib_save_lead_nonce') && current_user_can("edit_posts")) {
            $post = $_POST['data'];
            $leadData = $post['Lead'];
            $lead = new LeadModel;
            $state = ($leadData['country_id'] == 228) ? $leadData['us_state'] : $leadData['non_state'];
            $lead->lead_state = $state;
            // birthdate
            if (!empty($leadData['lead_dob'])) {
                $Date = new DateHelper;
                $leadData['lead_dob'] = $Date->format("Y-m-d", $leadData['lead_dob']);
            }
            unset($leadData['us_state']);
            unset($leadData['non_state']);
            foreach ($leadData as $index => $value) {
                //special fields handling
                switch ($index) {
                    case "lead_email":
                        $value = strtolower($value);
                        break;
                    case "campaign_id":
                        if ($value == "") {
                            $value = null;
                        }
                        break;
                }
                $lead->$index = $value;
            }
            $lead->save();
            $lead_id = $lead->lead_id;
            // save custom fields.
            LeadData::saveLeadData($lead_id, $post['LeadData']);
            // add history
            $current_user = wp_get_current_user();
            $h = new LeadHistory();
            $h->history_event = "Lead Created";
            $h->history_type = BREW_LEAD_HISTORY_TYPE_CREATED;
            $h->history_note = "Lead Created by " . $current_user->ID . ":" . $current_user->display_name;
            $h->wp_user_id = $current_user->ID;
            $h->lead_id = $lead_id;
            $h->save();

            // redirect
            $this->_confirm("Lead Created", true);
            header("Location:admin.php?page={$this->post_type}&section=lead&id=" . $lead_id);
        }
    }

    /** add additional data needed for add/edit form
     *
     * @param array $data object with pre-existing data for view
     * @return array $data array with additional fields
     * @author Rico Celis
     * @access public
     */
    private function addFormData($data, $options = array()) {
        $_defaults = array(
            'field_tokens' => false
        );
        $options = array_merge($_defaults, $options);
        $data['custom_fields'] = FormField::where('field_custom', 1)->get()->toArray();
        if ($options['field_tokens']) {
            $data['field_tokens'] = $this->getFieldTokens($data['custom_fields']);
        }
        $data['countries'] = Country::countryList("country_name", "Choose One");
        $data['states'] = State::stateList("state_abbr", "Choose One");
        $data['users'] = $this->usersList();
        return $data;
    }

    private function usersList() {
        // wp users
        $wp_users = get_users();
        $users = array();
        foreach ($wp_users as $user) {
            $users[$user->ID] = $user->data->display_name;
        }
        return $users;
    }

    /** get filter view for leads table
     *
     * @author Rico Celis
     * @access public
     */
    public function getLeadTableFilteredView() {
        $nonce = @$_POST['nonce'];
//        echo "<pre>";
//        print_r($_POST);
//        exit;
        if ($nonce && wp_verify_nonce($nonce, 'ib-lead-nonce')) {
            $data['leads'] = $this->getFilteredLeadResults(@$_POST['data']['LeadAppliedFilter']);
            $data['post_type'] = $this->post_type;
            $data['field_tokens'] = $this->getFieldTokens();
            $data['Date'] = new DateHelper;
            $data['Form'] = new FormHelper;
            $data['Layout'] = new LayoutHelper;
            $data['users'] = $this->usersList();
            $data['partials_path'] = $this->partials_path;
            $user = wp_get_current_user();
            $data['views'] = LeadView::getUserViews($user->ID);
            $data['filters'] = $this->getLeadFilterFields();
            echo $this->load->view(self::VIEW_PATH . 'ajax_lead_table_view', $data);
            exit();
        }
    }

    /* get all leads
     *
     * author: Sean Carico
     * access:private
     */

    private function loadAllLeads() {
        $leads = LeadModel::whereNull('deleted_at')->get();
        return $leads;
    }

    /* get filtered results
     *
     * @param array $filters array of static and custom filters
     * @return Lavarel Object of results
     * @author: Rico Celis
     * @access: private
     */

    private function getFilteredLeadResults($filters, $field_tokens = null) {
        if (empty($filters))
            return $this->loadAllLeads();
        //print_debug($filters);
        // custom fields first
        $LeadData = new LeadData;
        if (!$field_tokens)
            $field_tokens = $this->getLeadFilterFields();
        if (!empty($filters['custom'])) {
            foreach ($filters['custom'] as $token => $values) {
                if (!empty($values['start']) || !empty($values['start'])) { // date
                    // start range
                    if (!empty($values['start'])) {
                        $LeadData->where(function($query) {
                            $query->where("data_term", $token)->where("data_value", ">=", $values['start']);
                        });
                    }
                    // end range
                    if (!empty($values['end'])) {
                        $LeadData->where(function($query) {
                            $query->where("data_term", $token)->where("data_value", "<=", $values['end']);
                        });
                    }
                } else { // static value
                    $token_settings = $field_tokens['custom_fields'][$token];
                    switch ($token_settings['type']) {
                        case "text":
                        case "textarea":
                        case "email":
                            foreach ($values as $index => $term) {
                                if ($index == "not_set") {
                                    $Lead->where($token, "");
                                } else {
                                    $Lead->where($token, "like", "%" . $term . "%");
                                }
                            }
                            break;
                        default:
                            if (@$values['not_set']) {
                                $values[] = "";
                                unset($values['not_set']);
                            }
                            $LeadData->where(function($query) {
                                $query->where("data_term", $token)
                                        ->whereIn("data_value", $values);
                            });
                            break;
                    }
                }
            }
            // get the lead ids for this term
            $leadDataResults = $leadData->get();
            $lead_ids = array();
            if (count($leadDataResults)) {
                foreach ($leadDataResults as $result) {
                    $lead_ids[] = $result->lead_id;
                }
            }
        }
        // static fields
        switch (@$filters['static']['archived_leads']) {
            case "archived":
                $Lead = LeadModel::onlyTrashed()->orderBy("lead_first_name");
                break;
            case "all":
                $Lead = LeadModel::withTrashed()->orderBy("lead_first_name");
                break;
            default: // active leads
                $Lead = LeadModel::orderBy("lead_first_name");
                break;
        }
        if (@$lead_ids)
            $Lead->whereIn("lead_id", $lead_ids);
        $Date = new DateHelper;
        if (!empty($filters['static'])) {
            foreach ($filters['static'] as $token => $values) {
                if ($token == "archived_leads")
                    continue;
                if (@$values['start'] || $values['end']) { // date
                    // start range
                    if (!empty($values['start'])) {
                        $start = $Date->format("Y-m-d H:i:s", $values['start'] . " 00:00:00", true);
                        $Lead->where($token, ">=", $start);
                    }
                    // end range
                    if (!empty($values['end'])) {
                        $end = $Date->format("Y-m-d H:i:s", $values['end'] . " 23:59:59", true);
                        $Lead->where($token, "<=", $end);
                    }
                } else { // static value
                    $token_settings = $field_tokens['static_fields'][$token];
                    switch ($token_settings['type']) {
                        case "text":
                        case "textarea":
                        case "email":
                            foreach ($values as $index => $term) {
                                if ($index == "not_set") {
                                    $Lead->where($token, "");
                                } else {
                                    $Lead->where($token, "like", "%" . $term . "%");
                                }
                            }
                            break;

                        default:
                            switch ($token) {
                                case "type_id":
                                case "assigned_to":
                                case "country_id":
                                    if (@$values['not_set']) {
                                        $values[] = "0";
                                        unset($values['not_set']);
                                    }
                                    break;
                            }
                            $Lead->whereIn($token, $values);
                            break;
                    }
                }
            }
        }
        //print_debug($Lead->toSql());
        $leads = $Lead->get();
        //print_debug($leads->toArray());
        return $leads;
    }

    /* get all fields tokens for static and custom fields
     *
     * @param array $custom_fields if loaded
     * @return array index array with token and name of fields
     * @author Rico Celis
     * @access private
     */

    private function getFieldTokens($custom_fields = null) {
        if (!$custom_fields)
            $custom_fields = FormField::where('field_custom', 1)->get()->toArray();
        $field_tokens = array(
            'lead_id' => "ID",
            'type_id' => "Lead Type",
            'lead_email' => "Email Address",
            'lead_email2' => "Email Address 2",
            'lead_ip' => "IP Address",
            'lead_first_name' => "First Name",
            'lead_last_name' => "Last Name",
            'lead_address' => "Address",
            'lead_address2' => "Address 2",
            'lead_city' => "City",
            'lead_state' => "State/Province",
            'country_id' => "Country",
            'lead_postal' => "Postal",
            'lead_phone' => "Phone",
            'lead_phone2' => "Phone2",
            'lead_dob' => "Birthdate",
            'lead_social_facebook' => "Social Facebook",
            'lead_social_twitter' => "Social Twitter",
            'lead_social_linkedin' => "Social LinkedIn",
            'lead_social_other' => "Social Other",
            'lead_opt_in'	=>	'Opt-In',
            'created_at' => "Created",
            'updated_at' => "Last Activity",
            'assigned_to' => "Assigned To"
        );
        if (@$custom_fields) {
            foreach ($custom_fields as $field) {
                $field_tokens[$field['field_token']] = $field['field_name'];
            }
        }
        return $field_tokens;
    }

    /* get all lead fields (static and custom) to filter on
     *
     * @access private
     * @author Rico Celis
     */

    private function getLeadFilterFields($lead_id = null) {
        if ($lead_id) {
            $lead = LeadModel::withTrashed()->find($lead_id);
            $leadData = $lead->leadData()->get()->toArray();
        }
        $lead_types = array(
            1 => "Prospect",
            2 => "Lead",
            3 => "Customer",
            4 => "Affiliate");
        $countries = Country::countryList("country_name");
        $users = $this->usersList();
        $static_fields = array(
            'lead_id' => array(
                'value' => (@$lead_id) ? $lead_id : "",
                'type' => "int",
                'label' => "ID",
                'no_filter' => true),
            'lead_first_name' => array(
                'type' => "text",
                'label' => "First Name",
                'no_filter' => true,
                'value' => (@$lead->lead_first_name) ? $lead->lead_first_name : ""),
            'lead_last_name' => array(
                'type' => "text",
                'label' => "Last Name",
                'no_filter' => true,
                'value' => (@$lead->lead_last_name) ? $lead->lead_last_name : ""),
            'type_id' => array(
                'type' => "select",
                'label' => "Lead Type",
                'options' => $lead_types,
                'value' => (@$lead->type_id) ? $lead_types[$lead->type_id] : ""),
            'lead_email' => array(
                'type' => "email",
                'label' => "Email Address",
                'value' => (@$lead->lead_email) ? $lead->lead_email : ""),
            'lead_email2' => array(
                'type' => "email",
                'label' => "Email Address 2",
                'value' => (@$lead->lead_email2) ? $lead->lead_email2 : ""),
            'lead_ip' => array(
                'type' => "text",
                'label' => "IP Address",
                'value' => (@$lead->lead_ip) ? $lead->lead_ip : ""),
            'lead_city' => array(
                'type' => "text",
                'label' => "City",
                'value' => (@$lead->lead_city) ? $lead->lead_city : ""),
            'lead_state' => array(
                'type' => "text",
                'label' => "State/Province",
                'value' => (@$lead->lead_state) ? $lead->lead_state : ""),
            'country_id' => array(
                'type' => "select",
                'label' => "Country",
                'options' => $countries,
                'value' => (@$lead->country_id) ? $countries[$lead->country_id] : ""),
            'lead_postal' => array(
                'type' => "text",
                'label' => "Postal",
                'value' => (@$lead->lead_postal) ? $lead->lead_postal : ""),
            'lead_phone' => array(
                'type' => "text",
                'label' => "Phone",
                'value' => (@$lead->lead_phone) ? $lead->lead_phone : ""),
            'lead_phone2' => array(
                'type' => "text",
                'label' => "Phone2",
                'value' => (@$lead->lead_phone2) ? $lead->lead_phone2 : ""),
            'lead_dob' => array(
                'type' => "date",
                'label' => "Birthdate",
                'value' => (@$lead->lead_dob) ? $lead->lead_dob : ""),
            'lead_social_facebook' => array(
                'type' => "text",
                'label' => "Social Facebook",
                'value' => (@$lead->lead_social_facebook) ? $lead->lead_social_facebook : ""),
            'lead_social_twitter' => array(
                'type' => "text",
                'label' => "Social Twitter",
                'value' => (@$lead->lead_social_twitter) ? $lead->lead_social_twitter : ""),
            'lead_social_linkedin' => array(
                'type' => "text",
                'label' => "Social LinkedIn",
                'value' => (@$lead->lead_social_linkedin) ? $lead->lead_social_linkedin : ""),
            'created_at' => array(
                'type' => "date",
                'label' => "Created",
                'value' => (@$lead->created_at) ? $lead->created_at : ""),
            'updated_at' => array(
                'type' => "date",
                'label' => "Last Activity",
                'value' => (@$lead->updated_at) ? $lead->updated_at : ""),
            'assigned_to' => array(
                'type' => "select",
                'label' => "Assigned To",
                'options' => $users,
                'value' => (@$lead->assigned_to) ? $users[$lead->assigned_to] : ""),
            'opt_in' => array(
                'type' => "checkbox",
                'label' => "Opt In",
                'value' => (@$lead->opt_in) ? $lead->opt_in : "")
        );
        $custom = FormField::where('field_custom', 1)->get()->toArray();
        $custom_fields = array();
        if (count($custom)) {
            foreach ($custom as $field) {
                $custom_fields[$field['field_token']] = array(
                    'type' => $field['field_type'],
                    'label' => $field['field_name']
                );
                if (!empty($field['field_value'])) {
                    $values = explode("\n", stripslashes($field['field_value']));
                    $options = array();
                    foreach ($values as $value):
                        $value = rtrim(str_replace("\r", "", $value));
                        $options[$value] = $value;
                    endforeach;
                    $custom_fields[$field['field_token']]['options'] = $options;
                }
                // check value
                if (@$leadData) {
                    foreach ($leadData as $data) {
                        if ($data['data_term'] == $field['field_token']) {
                            $custom_fields[$field['field_token']]['value'] = $data['data_value'];
                            break;
                        }
                    }
                }
            }
        }
        return array(
            'static_fields' => $static_fields,
            'custom_fields' => $custom_fields
        );
    }

    public function addLeadHistory() {
        if (!isset($_POST['add_ib_lead_note_nonce']) || !wp_verify_nonce($_POST['add_ib_lead_note_nonce'], 'add_ib_lead_note')) {
            $this->_error("Sorry, your nonce did not verify", true);
            header('Location: ' . $_POST['_wp_http_referer']);
        }
        $rules = array(
            'required' => array(
                'lead_id',
                'history_note',
                'history_type'
            ),
            'integer' => array(
                'lead_id',
                'history_type'
            )
        );
        $v = new Validator($_POST);
        $v->rules($rules);
        if ($v->validate()) {
            $h = new LeadHistory();
            $h->lead_id = $_POST['lead_id'];
            $h->history_note = $_POST['history_note'];
            $h->history_type = $_POST['history_type'];
            switch ($_POST['history_type']) {
                case BREW_LEAD_HISTORY_TYPE_CREATED:
                    $event = 'Lead Created';
                    break;
                case BREW_LEAD_HISTORY_TYPE_FORM_SUBMISSION:
                    $event = 'Form Submission';
                    break;
                case BREW_LEAD_HISTORY_TYPE_NOTE:
                    $event = 'Note';
                    break;
                case BREW_LEAD_HISTORY_TYPE_SHARED:
                    $event = 'Shared media';
                    break;
                case BREW_LEAD_HISTORY_TYPE_CONTENT_DOWNLOADED:
                    $event = 'Downloaded Content';
                    break;
                default:
                    $event = 'Note';
                    break;
            }
            $h->history_event = $event;
            $h->save();
            $this->_confirm("$event, was successfully", true);
            header('Location: admin.php?page=ib-leads-admin&section=lead&id=' . $_POST['lead_id']);
        } else {
            $this->_error(print_r($v->errors()), true);
            header('Location: ' . $_POST['_wp_http_referer']);
        }
    }

    public function createNewLeadView() {
        $response = array(
            'success' => false
        );
        $nonce = $_POST['nonce'];
        if ($nonce && wp_verify_nonce($nonce, 'ib-lead-nonce')) {
            $leadView = new LeadView;
            $user = wp_get_current_user();
            $leadView->wp_user_id = $user->ID;
            $leadView->view_name = $_POST['view_name'];
            $leadView->view_access = 'private';
            $leadView->display_order = $_POST['display_order'];
            $leadView->view_filters = serialize($_POST['filters']);
            $leadView->view_columns = serialize($_POST['columns']);
            $leadView->save();
            $response = array(
                'success' => true,
                'view' => array(
                    'lead_view_id' => $leadView->lead_view_id,
                    'view_name' => $leadView->view_name,
                    'view_columns' => $_POST['columns'],
                    'view_filters' => $_POST['filters']
            ));
        }
        die(json_encode($response));
    }

    public function updateLeadView() {
        $response = array(
            'success' => false,
            'title' => "Error!",
            'message' => "Unable to update view. Please try again."
        );
        $nonce = $_POST['nonce'];
        if ($nonce && wp_verify_nonce($nonce, 'ib-lead-nonce')) {
            $user = wp_get_current_user();
            $leadView = LeadView::find($_POST['lead_view_id']);
            if (@$leadView->lead_view_id && @$leadView->wp_user_id == $user - ID) {
                $leadView->view_name = $_POST['view_name'];
                $leadView->view_filters = serialize($_POST['filters']);
                $leadView->view_columns = serialize($_POST['columns']);
                $leadView->view_columns_order = serialize($_POST['order']);
                $leadView->view_columns_width = serialize($_POST['widths']);
                $leadView->save();
                $response = array(
                    'success' => true,
                    'title' => "Success!",
                    'message' => "View updated.");
            }
        }
        die(json_encode($response));
    }

    public function deleteLeadView() {
        $response = array(
            'success' => false,
            'message' => "Unable to delete view. Please try again."
        );
        $nonce = $_POST['nonce'];
        if ($nonce && wp_verify_nonce($nonce, 'ib-lead-nonce')) {
            $user = wp_get_current_user();
            $leadView = LeadView::find($_POST['lead_view_id']);
            if (@$leadView->lead_view_id && @$leadView->wp_user_id == $user->ID) {
                $leadView->delete();
                $response = array(
                    'success' => true,
                    'message' => "View deleted.");
            }
        }
        die(json_encode($response));
    }

    private function recordVisit() {

    }

    /**
     * generate a CSV of all(soft deleted included) leads in the Lead model
     */
    public function exportLeads() {
        // output headers so that the file is downloaded rather than displayed
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=ib_leads.csv');

        // create a file pointer connected to the output stream
        $handle = fopen('php://output', 'w');

        // output the column headings
        $headers = array('ID', 'Email', 'IP Address', 'Name', 'Address1', 'Address2', 'City', 'State', 'Country', 'Postal Code', 'Phone', 'Date of Birth', 'Score', 'Type', 'Created', 'Last Updated', 'Deleted');
        fputcsv($handle, $headers);

        // fetch the data
        $leads = LeadModel::withTrashed()->get();
        // loop over the rows, outputting them
        foreach ($leads as $value) {
            $data = array();
            $data[] = $value->lead_id;
            $data[] = $value->lead_email;
            $data[] = $value->lead_ip;
            $data[] = $value->lead_first_name;
            $data[] = $value->lead_last_name;
            $data[] = $value->lead_address;
            $data[] = $value->lead_address2;
            $data[] = $value->lead_city;
            $data[] = $value->lead_state;
            $data[] = $value->country->country_name;
            $data[] = $value->lead_postal;
            $data[] = $value->lead_phone;
            $data[] = $value->lead_dob;
            $data[] = $value->lead_score;
            $data[] = $value->type_id;
            $data[] = $value->created_at;
            $data[] = $value->updated_at;
            $data[] = $value->deleted_at;
            fputcsv($handle, $data);
            unset($data);
        }

        fclose($handle);
    }

    /**
     * digest a CSV file into the ib_keywords table
     * ony requirements are the Keyword header term
     * Soft deletes all previous keywords if delete existing is selected.
     */
    public function importLeads() {
        $error = array();
        if (!check_admin_referer('ib_lead_batch_upload') || !current_user_can('edit_posts')) {
            $error[] = "You are not authorized to batch upload";
        }
        $allowed = array("text/plain", "text/csv");
        if (!in_array($_FILES['csv_file']['type'], $allowed)) {
            $error[] = "incorrect file type. Please upload csv.";
        }
        if ($_FILES['csv_file']['size'] < 1) {
            $error[] = "File is empty.";
        }
        if (!empty($error)) {
            $errors = "<br />" . implode("<br />", $error);
            $this->_error("the following errors were found:" . $errors, true);
            header('Location: ' . $_POST['_wp_http_referer']);
        } else {
            $file = $_FILES['csv_file']['tmp_name'];
            $overwrite = true;
            if ($_POST['lead_duplicates'] == 'ignore')
                $overwrite = false;
            if (($handle = fopen($file, "r")) !== FALSE) {
                $i = 0;
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($i == 0) {
                        if (false === ($email = array_search('Email', $data))) {
                            $error[] = "Missing email header";
                        }
                        $ip = array_search('IP Address', $data);
                        $first_name = array_search('First Name', $data);
                        $last_name = array_search('Last Name', $data);
                        $address1 = array_search('Address1', $data);
                        $address2 = array_search('Address2', $data);
                        $email = array_search('Email', $data);
                        $city = array_search('City', $data);
                        $state = array_search('State', $data);
                        $country = array_search('Country', $data);
                        $postal = array_search('Postal Code', $data);
                        $phone = array_search('Phone', $data);
                        $dob = array_search('Date of Birth', $data);
                    }
                    if ($i > 0) {
                        if (isset($email) && is_int($email)) {
                            // ignore example row
                            if ($email == "email@example.com" &&
                                    $ip == "127.0.0.1" &&
                                    $name == "John Doe" &&
                                    $address1 == "123 Street" &&
                                    $address2 == "#4" &&
                                    $city == "New York" &&
                                    $state == "NY" &&
                                    $country == "United States of America" &&
                                    $postal == "10022" &&
                                    $phone == "(212) 555-5555" &&
                                    $dob == "2015-01-01") {
                                $i ++;
                                continue;
                            }
                            // new lead.
                            $lead = array();
                            $lead['lead_email'] = ($data[$email] != false && is_string($data[$email])) ? $data[$email] : '';
                            if (isset($ip) && is_int($ip))
                                $lead['lead_ip'] = isset($data[$ip]) ? $data[$ip] : '';
                            if (isset($first_name) && is_int($first_name))
                                $lead['lead_last_name'] = isset($data[$first_name]) ? $data[$first_name] : '';
                            if (isset($last_name) && is_int($last_name))
                                $lead['lead_last_name'] = isset($data[$last_name]) ? $data[$last_name] : '';
                            if (isset($address1) && is_int($address1))
                                $lead['lead_address'] = isset($data[$address1]) ? $data[$address1] : '';
                            if (isset($address2) && is_int($address2))
                                $lead['lead_address2'] = isset($data[$address2]) ? $data[$address2] : '';
                            if (isset($city) && is_int($city))
                                $lead['lead_city'] = isset($data[$city]) ? $data[$city] : '';
                            if (isset($state) && is_int($state))
                                $lead['lead_state'] = isset($data[$state]) ? $data[$state] : '';
                            if (isset($country) && is_int($country))
                                $lead['country_id'] = (isset($data[$country]) && $cnt = Country::name($data[$country])->first()) ? $cnt->country_id : '';
                            if (isset($postal) && is_int($postal))
                                $lead['lead_postal'] = isset($data[$postal]) ? $data[$postal] : '';
                            if (isset($phone) && is_int($phone))
                                $lead['lead_phone'] = isset($data[$phone]) ? $data[$phone] : '';
                            if (isset($dob) && is_int($dob))
                                $lead['lead_dob'] = isset($data[$dob]) ? $data[$dob] : '';
                            $this->saveLead($lead, $overwrite);
                        } else {
                            $error[] = "Missing email address on line $i";
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
                $this->_confirm("Leads successfully uploaded", true);
            }
            header('Location: admin.php?page=ib-leads-admin');
        }
    }

    /* importing leads */

    private function saveLead($data, $overwrite = true) {
        if (!$overwrite && $lead = LeadModel::email($data['lead_email'])->first()) {
            return;
        } else {
            $lead = LeadModel::firstOrNew(array('lead_email' => $data['lead_email']));
            foreach ($data as $key => $value) {
                if (!empty($value)) {
                    $lead->$key = $value;
                }
            }
            $lead->save();
        }
    }

    /* load 10 latest history for a lead
     *
     * @author Rico Celis
     * @access Public
     */

    public function leadRecentHistory() {
        $nonce = @$_POST['nonce'];
        if ($nonce && wp_verify_nonce($nonce, 'ib-lead-nonce')) {
            $lead_id = $_POST['lead_id'];
            $data['post_type'] = $this->post_type;
            $data['lead_history'] = LeadHistory::where('lead_id', $lead_id)->orderBy("created_at", "desc")->take(10)->get();
            // helpers
            $data['Date'] = new DateHelper;
            echo $this->load->view(self::VIEW_PATH . 'ajax_recent_history', $data);
            exit();
        }
    }

    /* delete lead through ajax
     *
     * @author Rico Celis
     * @access Public
     */

    public function archiveLead() {
        $nonce = @$_POST['nonce'];
        if ($nonce && wp_verify_nonce($nonce, 'ib-lead-nonce')) {
            $lead_id = $_POST['lead_id'];
            $lead = LeadModel::find($lead_id);
            if (@$lead->lead_id) {
                $response = array('success' => true);
                // add history
                $user = wp_get_current_user();
                $history = new LeadHistory;
                $history->wp_user_id = $user->ID;
                $history->history_type = BREW_LEAD_HISTORY_TYPE_DELETED;
                $history->history_event = "Lead Archived";
                $history->history_note = "{{user}} Archived this lead.";
                $history->lead_id = $lead->lead_id;
                $history->save();
                $lead->delete();
            } else {
                $response = array(
                    'success' => false,
                    'Unable to archive lead. Please try again.'
                );
            }
        }
        die(json_encode($response));
    }

    public function sendLeadEmail() {
        add_action('phpmailer_init', array('InboundBrew\Modules\Contact\Controllers\Email', 'phpMailerInit'));
        $nonce = @$_POST['_wpnonce'];
        if ($nonce && wp_verify_nonce($nonce, 'ib_send_lead_email_form')) {
            $post = $_POST['data']['LeadEmail'];
            $lead_id = $post['lead_id'];
            $tokens = $this->getLeadFilterFields($lead_id);
            $tokens = array_merge($tokens['static_fields'], $tokens['custom_fields']);
            switch ($post['email_type']) {
                case "email":
                    $email = EmailModel::find($post['email_id']);
                    if (@$email->email_id) {
                        $body = $email->email_value;
                        $template_id = $email->email_template_id;
                        $subject = $email->email_subject;
                        $send_to = (empty($email->send_to)) ? $tokens['lead_email']['value'] : str_replace("{{email}}", $tokens['lead_email']['value'], $email->send_to);
                        $send_cc = $email->send_cc;
                        $send_bcc = $email->send_bcc;
                        $template = EmailTemplate::find($email->email_template_id);
                    }
                    break;
                case "custom":
                    $template_id = $post['email_template_id'];
                    $subject = $post['email_subject'];
                    $body = $post['message_body'];
                    $template = EmailTemplate::find($template_id);
                    $send_to = (empty($template->send_to)) ? $tokens['lead_email']['value'] : str_replace("{{email}}", $tokens['lead_email']['value'], $template->send_to);
                    $send_cc = $template->send_cc;
                    $send_bcc = $template->send_bcc;
                    break;
            }
            // replace tokens
            $body = $this->replaceStringLeadTokens($body, $tokens);
            $subject = $this->replaceStringLeadTokens($subject, $tokens);

            // send to
            $to = (empty($email->send_to)) ? $postData['email'] : str_replace("{{email}}", $postData['email'], $email->send_to);
            $sendToArray = explode(",", $send_to);
            $to = array();
            foreach ($sendToArray as $email_address) {
                $email_address = trim(rtrim($email_address));
                if (!empty($email_address))
                    $to[] = $email_address;
            }
            $settings = new SettingsModel;
            $data = array(
                'template_data' => unserialize($template->settings),
                'FontAwesome' => new FontAwesomeHelper,
                'settings' => $settings->loadSettings()
            );

            // send settings
            $send_setings = json_decode(get_option('ib_smtp_options'));
            $headers = array();
            if (@$send_setings->mail_content_type == 'html') {
                $wrap = $this->load->view("Contact/views/email/template", $data, "blank");
                $message = str_ireplace('{{template_content}}', $body, $wrap);
                //we have to do this one more time to grab any tokens in the template (like unsubscribe)
                $message = $this->replaceStringLeadTokens($message, $tokens);
                $headers = array('Content-Type: text/html; charset=UTF-8');
            } else {
                $message = $body;
            }
            // check bc adn bcc
            if (!empty($send_cc))
                $headers[] = "Cc:" . $send_cc;
            if (!empty($end_cc))
                $headers[] = "Bcc:" . $send_bcc;
            $success = wp_mail($to, stripslashes($subject), $message, $headers);
            if ($success) {
                $Date = new DateHelper();
                $user = wp_get_current_user();
                $history = new LeadHistory;
                $history->wp_user_id = $user->ID;
                $history->history_type = BREW_LEAD_HISTORY_TYPE_EMAIL;
                $history->history_event = "Email Sent";
                $history->history_note = $message;
                $history->lead_id = $lead_id;
                $history->save();
                $url = "admin.php?page={$this->post_type}&action=ib_preview_lead_email&history_id={$history->history_id}";
                $note = "To view sent email <a href=\"{$url}\" class=\"ib_preview_sent_email\">click here</a>.";
                $result = array(
                    'success' => true,
                    'history' => array(
                        'mode' => "add",
                        'history_id' => $history->history_id,
                        'stamp' => date("YmdHis"),
                        'date' => $Date->format(BREW_WP_DATE_FORMAT . " " . BREW_WP_TIME_FORMAT, $history->created_at, true),
                        'icon' => 'fa-envelope',
                        'history_event' => $history->history_event,
                        'history_note' => $note,
                        'user' => $user->first_name . " " . $user->last_name
                    ),
                    'title' => "Email Sent.",
                    'message' => "Email was sent to " . $tokens['lead_first_name']['value'] . " " . $tokens['lead_last_name']['value'] . "."
                );
            } else {
                $msg = 'Unable to send email. Please try again.';
                if ($_SESSION['wp_mail_error']){
                    $msg = $_SESSION['wp_mail_error'];
                    unset($_SESSION['wp_mail_error']);
                }

                $result = array(
                    'success' => false,
                    'message' => $msg
                );
            }
            die(json_encode($result));
        }
    }

    private function replaceStringLeadTokens($string, $tokens) {
        $pattern = "/\{\{(.*?)\}\}/";
        preg_match_all($pattern, $string, $matches);
        if (@$matches[1]) {
            foreach ($matches[1] as $token) {
                if ($token == "unsubscribe") {
                    //special logic for unsubscribe links
                    //if we are here, it means we are sending one-off emails. Unsubscribe will be added to batch emails only for now
                    $replace_with = "";
                } else {
                    $db_field = LeadDataMap::mapTokenToDatabaseField($token);
                    $values = @$tokens[$db_field];
                    $replace_with = "";
                    if ($values)
                        $replace_with = $values['value'];
                }
                $string = str_replace("{{" . $token . "}}", $replace_with, $string);
            }
        }
        return $string;
    }

    public function previewHistoryEmail() {
        $history_id = $_GET['history_id'];
        $history = LeadHistory::find($history_id);
        if (@$history->history_id && @$history->history_type == BREW_LEAD_HISTORY_TYPE_EMAIL) {
            echo stripslashes($history->history_note);
            exit();
        }
    }

    public function restoreLead() {
        $result = array(
            'success' => false,
            'message' => "Unable to restore lead. Please try again."
        );
        $lead_id = @$_POST['lead_id'];
        $lead = LeadModel::withTrashed()->find($lead_id);
        $nonce = @$_POST['nonce'];
        if ($nonce && wp_verify_nonce($nonce, 'ib-lead-nonce') && current_user_can("edit_posts") && @$lead->lead_id) {
            $history_event = "Lead Restored by user.";
            $lead->restore();
            $user = wp_get_current_user();
            $history = new LeadHistory;
            $history->wp_user_id = $user->ID;
            $history->history_type = BREW_LEAD_HISTORY_TYPE_RESTORED;
            $history->history_event = $history_event;
            $history->lead_id = $lead->lead_id;
            $history->save();
            $Date = new DateHelper();
            $result = array(
                'success' => true,
                'message' => "Lead Restored.",
                'history' => array(
                    'mode' => "add",
                    'history_id' => $history->history_id,
                    'stamp' => date("YmdHis"),
                    'date' => $Date->format(BREW_WP_DATE_FORMAT . " " . BREW_WP_TIME_FORMAT, $history->created_at, true),
                    'icon' => "fa-undo",
                    'history_event' => $history_event,
                    'history_note' => "",
                    'user' => $user->first_name . " " . $user->last_name
                )
            );
        }
        die(json_encode($result, JSON_UNESCAPED_SLASHES));
    }

}
