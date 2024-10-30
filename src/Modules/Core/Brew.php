<?php

/**
 * Created by sean.carrico.
 * User: sean
 * Date: 3/25/15
 * Time: 11:49 AM
 */
//namespace InboundBrew\Modules\Core;
use InboundBrew\Modules\CTA\Models\CallToAction;
use InboundBrew\Modules\CTA\Models\CTATemplate;
use InboundBrew\Modules\CTA\Models\CallToActionPostLinkage;
use InboundBrew\Modules\Settings\Models\SettingsModel;
use InboundBrew\Modules\Contact\Models\EmailTemplate;
use InboundBrew\Modules\Contact\Models\Email as EmailModel;

/**
 * Class Brew
 * @package InboundBrew\Modules\Core
 */
class Brew {

    /**
     * Stores the path to data files used for import methods
     */
    const DATA_PATH = 'assets/data/';

    /**
     * Calls the init of AppController
     *
     * @author Sean Carrico
     * @access public
     */
    public function __construct() {

    }

    public function constraintExists($name){
        global $wpdb;

        return $wpdb->get_results("SELECT * FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS 
                                WHERE CONSTRAINT_NAME ='$name'");
    }

    /**
     * wrapper for the create/update statements and data imports
     *
     * @author Sean Carrico
     * @access public
     */
    public function Init() {
        $db_version = get_option('ib_db_version');
        $data_version = get_option('ib_data_version');
        $settings_version = get_option('ib_settings_version');
        if (empty($settings_version))
            $settings_version = 0;
        // check
        if (empty($db_version)) {
            $this->executeTableStatements();
            update_option('ib_db_version', BREW_DB_VERSION);
            $db_version = BREW_DB_VERSION;
        }
        if ($db_version != BREW_DB_VERSION) {
            $this->runDBUpdates($db_version);
            update_option('ib_db_version', BREW_DB_VERSION);
        }
        if ($data_version != BREW_DATA_VERSION || empty($data_version)) {
            $this->executeDataImports($data_version);
            update_option('ib_data_version', BREW_DATA_VERSION);
        }
        if (empty($settings_version))
            $settings_version = 0.1;
        if ($settings_version != BREW_SETTINGS_VERSION || empty($settings_version)) {
            $this->checkSettingsStatus($settings_version);
            update_option('ib_settings_version', BREW_SETTINGS_VERSION);
        }
    }

    /**
     * Calls the methods that create tables
     *
     * @author Sean Carrico
     * @access public
     */
    public function executeTableStatements() {
        $this->createKeywordTable();
        $this->createPostKeywordTable();
        $this->createCountryTable();
        $this->createStateTable();
        $this->createCTAsTable();
        $this->createRedirectsTable();
        $this->createLeadFieldTable();
        $this->createLeadTable();
        $this->createLeadHistoryTable();
        $this->createLeadDataTable();
        $this->createSettingsTable();
        $this->createEmailsTable();
        $this->createEmailTemplatesTable();
        $this->createEmailFieldTable();
        $this->createContactFormFieldTable();
        $this->createDownloadTable();
        $this->createReportsTables();
        $this->createCampaignTables();
        $this->createTrackingEventsTable();
        $this->createCampaignEventsLogTable();
        $this->createEmailTrackTable();
    }

    /**
     * Calls the methods that import data
     *
     * @author Sean Carrico
     * @access public
     */
    public function executeDataImports($data_version) {
        if ($data_version == '' || $data_version < 1.0) {
            $country_import = get_option('ib_country_import');
            if (!$country_import) {
                if ($this->importCountryData())
                    update_option('ib_country_import', 1);
            }

            $state_import = get_option('ib_state_import');
            if (!$state_import) {
                if ($this->importStateData())
                    update_option('ib_state_import', 1);
            }

            $field_import = get_option('ib_field_import');
            if (!$field_import) {
                $this->importLeadFieldData();
                update_option('ib_field_import', 1);
            }
        }
        if ($data_version < 1.1) {
            $this->importLeadFieldDataSet3();
        }
        if ($data_version < 1.2) {
            $this->importLeadFieldDataSet4();
        }
        if ($data_version < 1.3) {
          update_option('ib_show_getting_started_menu', 1);
        }

        
    }

    /**
     * method creates table to track redirects
     *
     * @author Rico Celis
     * @access private
     */
    private function createRedirectsTable() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'ib_redirects';

        $sql = "CREATE TABLE $table_name (
          redirect_id int(11) unsigned NOT NULL AUTO_INCREMENT,
          redirect_from varchar(255) NOT NULL DEFAULT '',
          redirect_to varchar(255) NOT NULL DEFAULT '',
          redirect_type varchar(12) NOT NULL DEFAULT 'url',
          status varchar(3) NOT NULL DEFAULT '',
          is_wildcard tinyint(1) NOT NULL DEFAULT '0',
          redirect_uses int(11) NOT NULL DEFAULT '0',
          updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          PRIMARY KEY  (redirect_id)
        ) ENGINE=InnoDB;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $wpdb->query($sql);
    }

    /**
     * checks if user has setup default email settings
     *
     * @author Rico Celis
     * @access public
     */
    public function checkSettingsStatus($settings_version) {
        global $ib_dynamic_navigation;
        global $wpdb;
        switch ($settings_version) {
            case ((double) $settings_version < 1.0):
// email settings
                $current_user = wp_get_current_user();
                $name = $current_user->user_firstname . " " . $current_user->user_lastname;
                $smtp = (Object) json_decode(get_option('ib_smtp_options'));
                if (empty($smtp->mail_from))
                    $smtp->mail_from = get_bloginfo("admin_email");
                if (empty($smtp->mail_from_name))
                    $smtp->mail_from_name = $name;
                if (empty($smtp->mailer))
                    $smtp->mailer = "mail";
                if (empty($smtp->mail_content_type))
                    $smtp->mail_content_type = "html";
                if (empty($smtp->smtp_auth))
                    $smtp->smtp_auth = "0";
                if (empty($smtp->smtp_ssl))
                    $smtp->smtp_ssl = "none";
                update_option('ib_smtp_options', json_encode($smtp));
            case ((double) $settings_version < 1.1):
// set default widget settings
                $widget_settings = array(
                    'facebook_share' => 1,
                    'twitter_share' => 1,
                    'linked_in_share' => 1,
                    'google_plus_share' => 1,
                    'background' => array(
                        'background_color' => "F2F2F2",
                        'border_color' => "999999",
                        'border_radius' => "0",
                        'border_width' => "1",
                        'h_padding' => "4",
                        'v_padding' => "4",
                        'padding_right' => "4",
                        'padding_left' => "4",
                    ),
                    'icons' => array(
                        'type' => "square",
                        'size' => 3,
                        'color' => "0083CA",
                        'margin_bottom' => 20,
                        'facebook' => "3B99FC",
                        'linked_in' => "1A85BC",
                        'twitter' => "1A85BC",
                        'google_plus' => "FCA604"
                    ),
                    'title' => array(
                        'text' => "share",
                        'color' => "444444",
                        'margin_bottom' => 5,
                        'font_size' => 12
                    ),
                    'position' => array(
                        'location' => "right",
                        'top' => 25,
                    )
                );
                update_option(BREW_SOCIAL_WIDGET_SETTINGS, $widget_settings);
            case ((double) $settings_version < 1.2):
                $nav = array(
                    'order' => array(
                        'dashboard',
                        'leads',
                        'email',
                        'forms',
                        'landing_page',
                        'cta',
                        'keywords',
                        'redirects',
                        'settings'));
                update_option(IB_TOP_NAV_VALUES, $nav);
                update_option(BREW_DEFAULT_LAYOUT_OPTION, "top_nav");
            case ((double) $settings_version < 1.3):
                $post_types = get_post_types(array(
                    '_builtin' => true,
                    'public' => true
                        ), 'objects');
                $post_types['ib-landing-page'] = json_decode(json_encode(array(
                    'name' => 'ib-landing-page',
                    'labels' => array(
                        'name' => "Landing Pages",
                        'singular_name' => "Landing Page"
                    )
                        )), FALSE);
                $widget_options = array();
                foreach ($post_types as $post_type) {
                    if ($post_type->name == "attachment")
                        continue;
                    $widget_options[$post_type->name] = "on";
                }
                $widget_options['show_on_mobile'] = "";
                update_option(BREW_SOCIAL_WIDGET_OPTIONS, $widget_options);
            case ((double) $settings_version < 1.4):
                //active modules
                $modules = array();
                foreach ($ib_dynamic_navigation['navigation'] as $name => $values) {
                    if (!@$values['is_module'])
                        continue;
                    $modules[$name] = "on";
                }
                update_option(BREW_ACTIVE_MODULES_OPTION, $modules);
                // default auto redirect
                $rsettings = array(
                    'auto_redirect_on_url_change' => "on"
                );
                update_option(BREW_REDIRECT_SETTINGS_OPTION, $rsettings);

                //turn off auto-collapse by default
                update_option("ib_auto_collapse_wp", "false");
                update_option("ib_auto_collapse_ib", "false");
            case ((double) $settings_version < 1.5):
                $options_nav = get_option(IB_TOP_NAV_VALUES);
                if (!in_array("getting_started", $options_nav['order'])) {
                    $options_nav['order'][] = 'getting_started';
                    update_option(IB_TOP_NAV_VALUES, $options_nav);
                }
                
        }
    }

    /**
     * create tables associated with settings
     *
     * @author Rico Celis
     * @access private
     */
    private function createSettingsTable() {
        global $wpdb;

        $table_name = $wpdb->prefix . "ib_settings";

        $query1 = "CREATE TABLE {$table_name} (
            settings_id int(11)  unsigned NOT NULL AUTO_INCREMENT,
            social_connected_facebook timestamp NULL DEFAULT NULL,
            social_name_facebook VARCHAR(100) NULL DEFAULT NULL,
            social_connected_linked_in timestamp NULL DEFAULT NULL,
            social_name_linked_in VARCHAR(100) NULL DEFAULT NULL,
            social_connected_twitter timestamp NULL DEFAULT NULL,
            social_name_twitter VARCHAR(100) NULL DEFAULT NULL,
            social_connected_google TIMESTAMP NULL DEFAULT NULL,
            social_name_google VARCHAR(100) NULL DEFAULT NULL,
            social_url_facebook text NULL DEFAULT NULL ,
            social_url_linkedin text NULL DEFAULT NULL,
            social_url_twitter text NULL DEFAULT NULL,
            social_url_google_plus text NULL DEFAULT NULL,
            created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
            updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
            wizzard_emails  tinyint(1) NOT NULL DEFAULT '0',
            wizzard_contact_forms  tinyint(1) NOT NULL DEFAULT '0',
            wizzard_social_settings  tinyint(1) NOT NULL DEFAULT '0',
            wizzard_landing_pages  tinyint(1) NOT NULL DEFAULT '0',
            wizzard_ctas  tinyint(1) NOT NULL DEFAULT '0',
            wizzard_hide  tinyint(1) NOT NULL DEFAULT '0',
            PRIMARY KEY  (settings_id)
        ) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $wpdb->query($query1);

// add table for tracking social networks tokens
        $table_name = $wpdb->prefix . "ib_social_network_accounts";
        $query2 = "CREATE TABLE {$table_name} (
          account_id int(11) NOT NULL AUTO_INCREMENT,
          social_network varchar(20) NOT NULL DEFAULT '',
          account_type varchar(20) NOT NULL DEFAULT '',
          account_type_id varchar(255) NOT NULL DEFAULT '',
          token text NULL DEFAULT NULL,
          meta1 text NULL DEFAULT NULL,
          meta2 text NULL DEFAULT NULL,
          display_name varchar(255) NOT NULL DEFAULT '',
          is_active TINYINT(1) NOT NULL DEFAULT '1',
          created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          PRIMARY KEY  (account_id),
          KEY IDX_social_network (social_network,account_type,account_type_id),
          INDEX IDX_activeAccounts (social_network, is_active),
          INDEX IDX_activeAccountsType (social_network,account_type, is_active)
        ) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $wpdb->query($query2);

// add table to manage post settings
        $table_name = $wpdb->prefix . "ib_social_network_post_settings";
        $query3 = "CREATE TABLE {$table_name} (
          post_setting_id bigint(20) NOT NULL AUTO_INCREMENT,
          social_network varchar(20) NOT NULL DEFAULT '',
          wp_post_id bigint(20) NOT NULL DEFAULT '0',
          when_to_post enum('now','on') NOT NULL DEFAULT 'now',
          when_to_post_on_option varchar(20) NOT NULL DEFAULT '',
          when_to_post_on_option_value varchar(20) NOT NULL DEFAULT '',
          when_to_post_time time NULL,
          posting_status ENUM('','not','posted','error','pending') NOT NULL DEFAULT 'not',
          post_at DATETIME  NULL DEFAULT NULL,
          posted_title text NULL DEFAULT NULL,
          posted_image text NULL DEFAULT NULL,
          posted_description text NULL DEFAULT NULL,
          posted_url text NULL DEFAULT NULL,
          created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          deleted_at timestamp NULL DEFAULT NULL,
          PRIMARY KEY  (post_setting_id),
          KEY IDX_socialNetwork (social_network,wp_post_id,posting_status),
          KEY IDX_status (posting_status,social_network,post_at)
        ) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $wpdb->query($query3);

// add table to keep linkage between post settings and accounts
        $table_name = $wpdb->prefix . "ib_social_network_post_setting_accounts";
        $query4 = "CREATE TABLE {$table_name} (
          posting_account_id bigint(20) NOT NULL AUTO_INCREMENT,
          posting_setting_id bigint(20) NOT NULL DEFAULT '0',
          network_account_id bigint(20) NOT NULL DEFAULT '0',
          social_network varchar(20) NOT NULL DEFAULT '',
          created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          PRIMARY KEY  (posting_account_id),
          KEY IDX_postSetting (posting_account_id),
          KEY IDX_socialNetwork (social_network)
        ) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB;";
        $wpdb->query($query4);

// add table to track social network posting records
        $table_name = $wpdb->prefix . "ib_social_network_post_records";
        $query5 = "CREATE TABLE {$table_name} (
          record_id bigint(20) NOT NULL AUTO_INCREMENT,
          social_network varchar(20) NOT NULL DEFAULT '',
          post_setting_id bigint(20) NOT NULL DEFAULT '0',
          social_network_account_id bigint(20) NOT NULL DEFAULT '0',
          post_id varchar(255) NOT NULL DEFAULT '',
          post_meta1 text NULL DEFAULT NULL,
          error_message text NULL DEFAULT NULL,
          created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          PRIMARY KEY  (record_id),
          KEY IDX_postSetting (post_setting_id)
        ) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB";
        $wpdb->query($query5);
    }

    /**
     * method creates table to save CTA's templates
     *
     * @author Rico Celis
     * @access private
     */
    private function createCTAsTable() {
        global $wpdb;
        // create cta table
        $table_name = $wpdb->prefix . "ib_ctas";
        $sql = "CREATE TABLE {$table_name} (
			  `cta_id` bigint(20) NOT NULL AUTO_INCREMENT,
			  `cta_template_id` bigint(20) NOT NULL DEFAULT '0',
			  `cta_type` ENUM('button','image','before_leave_cta','top_bar') NOT NULL DEFAULT 'button',
			  `name` varchar(255) NOT NULL DEFAULT '',
			  `html` longtext NULL DEFAULT NULL,
			  `links_to` enum('internal','external') NOT NULL DEFAULT 'internal',
			  `links_to_value` longtext NULL DEFAULT NULL,
        `cta_level` INT DEFAULT 1,
        `cta_points` INT DEFAULT '0',
        `campaign_id` INT NULL,
			  `cta_settings` longtext NULL DEFAULT NULL,
			  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `deleted_at` timestamp NULL DEFAULT NULL,
			  PRIMARY KEY  (cta_id),
			  KEY IDX_template (cta_template_id)
			) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $wpdb->query($sql);
        // create cta template name

        $table_name = $wpdb->prefix . 'ib_cta_templates';
        $sql = "CREATE TABLE {$table_name} (
          template_id int(11) unsigned NOT NULL AUTO_INCREMENT,
          name varchar(255) NOT NULL DEFAULT '',
          html text NULL DEFAULT NULL,
          settings text NULL DEFAULT NULL,
          hover_styles text NULL DEFAULT NULL,
          created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          deleted_at timestamp NULL DEFAULT NULL,
          PRIMARY KEY  (template_id),
          KEY IDX_name (name)
        ) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB;";
        $wpdb->query($sql);
// create table for post linkages
        $table_name = $wpdb->prefix . 'ib_cta_post_linkages';
        $sql = "CREATE TABLE {$table_name} (
			`linkage_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`cta_id` bigint(20) NOT NULL DEFAULT '0',
			`wp_post_id` bigint(20) NOT NULL DEFAULT '0',
            `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
            `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY  (linkage_id),
			KEY IDX_cta (cta_id),
			KEY IDX_wpPost (wp_post_id)
		) CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate} ENGINE=InnoDB";
        $wpdb->query($sql);
    }

    /**
     *
     */
    private function createCrawlTable() {
        global $wpdb;

        $table_name = $wpdb->prefix . "ib_crawl";
        $sql = "CREATE TABLE $table_name (
            crawl_id INT(11) unsigned NOT NULL AUTO_INCREMENT,
            crawl_url varchar(255) NOT NULL DEFAULT '',
            crawl_code MEDIUMINT(3) NOT NULL DEFAULT '0',
            crawl_type ENUM('a','img') NOT NULL DEFAULT '0',
            crawl_text varchar(255) NOT NULL DEFAULT '',
            crawl_alt varchar(255) NOT NULL DEFAULT '',
            crawl_url_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (crawl_id),
            UNIQUE KEY ib_crawl_url (crawl_url)
          ) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $wpdb->query($sql);
    }

    /**
     * Creates the table to hold Keywords
     *
     * @author Sean Carrico
     * @access private
     */
    private function createKeywordTable() {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $table_name = $wpdb->prefix . "ib_keywords";
        $sql = "CREATE TABLE $table_name (
          keyword_id int(11) unsigned NOT NULL AUTO_INCREMENT,
          keyword_value varchar(75) NOT NULL DEFAULT '',
          keyword_score smallint(6),
          keyword_serp tinyint(2),
          keyword_rank tinyint(2),
          updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          deleted_at timestamp NULL DEFAULT NULL,
          PRIMARY KEY (keyword_id),
          KEY ib_keyword_value (keyword_value),
          KEY ib_keyword_score (keyword_score),
          KEY ib_keyword_serp (keyword_serp),
          KEY ib_keyword_rank (keyword_rank)
        ) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB;";

        $wpdb->query($sql);
    }

    /**
     * Creates pivot table to associate keywords to posts
     *
     * @author Sean Carrico
     * @access private
     */
    private function createPostKeywordTable() {
        global $wpdb;

        $table_name = $wpdb->prefix . "ib_post_keyword";
        $sql = "CREATE TABLE $table_name (
                  keyword_id INT(11) UNSIGNED NOT NULL DEFAULT '0',
                  post_id BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
                  UNIQUE KEY ib_post_keyword_comound (post_id,keyword_id)
                ) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $wpdb->query($sql);

        $key = "ALTER TABLE $table_name
                    ADD CONSTRAINT " . $table_name . "_post_id FOREIGN KEY (post_id)
                    REFERENCES " . $wpdb->prefix . "posts (ID)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE,
                    ADD CONSTRAINT " . $table_name . "_keyword_id FOREIGN KEY (keyword_id)
                    REFERENCES " . $wpdb->prefix . "ib_keywords (keyword_id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE;";
        $wpdb->query($key);
    }

    /**
     * Creates the table to hold customer
     *
     * @author Sean Carrico
     * @access private
     */
    private function createLeadTable() {
        global $wpdb;
        $table_name = $wpdb->prefix . "ib_leads";
        $sql = "CREATE TABLE $table_name (
                  `lead_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `assigned_to` bigint(20) NOT NULL DEFAULT '0',
                  `lead_email` varchar(75) NOT NULL DEFAULT '',
                  `lead_email2` varchar(75) NOT NULL DEFAULT '',
                  `lead_ip` varchar(39) NOT NULL DEFAULT '',
                  `lead_name` varchar(75) DEFAULT NULL,
                  `lead_first_name` varchar(75) DEFAULT NULL,
                  `lead_last_name` varchar(75) DEFAULT NULL,
                  `lead_address` varchar(255) DEFAULT NULL,
                  `lead_address2` varchar(255) DEFAULT NULL,
                  `lead_city` varchar(75) DEFAULT NULL,
                  `lead_state` varchar(50) DEFAULT NULL,
                  `country_id` smallint(3) DEFAULT NULL,
                  `lead_postal` varchar(16) DEFAULT NULL,
                  `lead_phone` varchar(20) DEFAULT NULL,
                  `lead_phone2` varchar(20) NOT NULL DEFAULT '',
                  `lead_dob` date DEFAULT NULL,
                  `lead_score` tinyint(3) unsigned DEFAULT '0',
                  `campaign_id` int(11) DEFAULT NULL DEFAULT '0',
                  `lead_level` int(11) DEFAULT '1',
                  `lead_social_facebook` varchar(255) NOT NULL DEFAULT '',
                  `lead_social_twitter` varchar(255) NOT NULL DEFAULT '',
                  `lead_social_linkedin` varchar(255) NOT NULL DEFAULT '',
                  `lead_picture` text NULL DEFAULT NULL,
                  `type_id` tinyint(2) unsigned DEFAULT '1',
                  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                  `deleted_at` timestamp NULL DEFAULT NULL,
                  `lead_opt_in` int(1) DEFAULT NULL,
                  PRIMARY KEY (`lead_id`),
                  UNIQUE KEY `ib_lead_email` (`lead_email`),
                  KEY `ib_lead_ip` (`lead_ip`),
                  KEY `ib_leads_campaign_id` (`campaign_id`)
              ) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB;";


        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $wpdb->query($sql);
        // lead views
        $this->createLeadViewsTable();
    }

    private function createLeadViewsTable() {
        global $wpdb;
        $table_name = $wpdb->prefix . "ib_lead_views";
        $sql = "CREATE TABLE {$table_name} (
		  `lead_view_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		  `wp_user_id` bigint(20) NOT NULL DEFAULT '0',
		  `view_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
		  `view_access` enum('public','private') COLLATE utf8_bin NOT NULL DEFAULT 'private',
		  `display_order` FLOAT NULL,
		  `view_filters` blob NULL,
		  `view_columns` blob NULL,
		  `view_columns_order` blob NULL,
		  `view_columns_width` blob NULL,
		  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `deleted_at` timestamp NULL DEFAULT NULL,
		  PRIMARY KEY  (lead_view_id),
		  KEY `IDX_user` (`wp_user_id`,`view_access`)
		) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $wpdb->query($sql);
// default settings
        $settings = array(
            'lead_view_id' => "all",
            'view_name' => "All Leads",
            'view_filters' => array(
                'static' => array(
                    'archived_leads' => "only_active"
                )
            ),
            'view_columns' => array('type_id', 'lead_first_name', 'lead_last_name', 'lead_email', 'lead_phone', 'created_at', 'updated_at', 'assigned_to'),
            'view_columns_width' => array(),
            'view_columns_order' => array(),
            'public' => "",
        );

        update_option(BREW_DEFAULT_LEAD_VIEW_SETTINGS_OPTION, $settings);
    }

    private function createLeadHistoryTable() {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $table_name = $wpdb->prefix . "ib_lead_history";

        $sql = "CREATE TABLE $table_name (
          history_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
          wp_user_id BIGINT(20) NOT NULL DEFAULT '0',
          history_type tinyint(3) unsigned NOT NULL DEFAULT '0',
          history_event text NULL DEFAULT NULL,
          history_note blob,
          lead_id int(11) unsigned NOT NULL DEFAULT '0',
          created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          deleted_at timestamp NULL DEFAULT NULL,
          PRIMARY KEY (history_id)
        ) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB;";
        $wpdb->query($sql);

        $key = "ALTER TABLE $table_name
                ADD CONSTRAINT " . $table_name . "_lead_id FOREIGN KEY (lead_id)
                REFERENCES " . $wpdb->prefix . "ib_leads (lead_id)
                ON DELETE CASCADE
                ON UPDATE CASCADE;";
        $wpdb->query($key);
    }

    private function createLeadDataTable() {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $table_name = $wpdb->prefix . "ib_lead_data";

        $sql = "CREATE TABLE $table_name (
          data_id int(11) unsigned NOT NULL AUTO_INCREMENT,
          lead_id int(11) unsigned DEFAULT NULL,
          data_term varchar(45) DEFAULT NULL,
          data_value blob,
          created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          deleted_at timestamp NULL DEFAULT NULL,
          PRIMARY KEY (data_id),
          KEY ib_lead_data_lead_id (lead_id)
        ) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB;";
        $wpdb->query($sql);

        $key = "ALTER TABLE $table_name
                ADD CONSTRAINT " . $table_name . "_lead_id FOREIGN KEY (lead_id)
                REFERENCES " . $wpdb->prefix . "ib_leads (lead_id)
                ON DELETE CASCADE
                ON UPDATE CASCADE;";
        $wpdb->query($key);
    }

    /**
     * Creates the table to hold Country List
     *
     * @author Sean Carrico
     * @access private
     */
    private function createCountryTable() {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $table_name = $wpdb->prefix . "ib_countries";
        $sql = "CREATE TABLE $table_name (
            country_id smallint(3) unsigned NOT NULL AUTO_INCREMENT,
            country_iso char(2) NOT NULL DEFAULT '',
            country_name varchar(80) NOT NULL DEFAULT '',
            created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
            updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
            deleted_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY  (country_id),
            UNIQUE KEY ib_country_iso (country_iso),
            UNIQUE KEY ib_country_name (country_name)
        ) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB;";
        $wpdb->query($sql);
    }

    /**
     * Creates the table to hold States/Provinces with key relating to Country
     *
     * @author Sean Carrico
     * @access private
     */
    private function createStateTable() {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $table_name = $wpdb->prefix . "ib_states";
        $sql = "CREATE TABLE $table_name (
            state_id int(11) unsigned NOT NULL AUTO_INCREMENT,
            state_name varchar(100) NOT NULL DEFAULT '',
            state_abbr char(2) NOT NULL DEFAULT '',
            country_id smallint(5) unsigned NOT NULL DEFAULT '0',
            created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
            updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
            deleted_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY  (state_id)
        ) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB;";

        $wpdb->query($sql);

        $key = "ALTER TABLE $table_name
            ADD CONSTRAINT " . $table_name . "_country_id FOREIGN KEY (country_id)
            REFERENCES " . $wpdb->prefix . "ib_countries (country_id)
            ON DELETE CASCADE
            ON UPDATE CASCADE;";

        $wpdb->query($key);
    }

    private function createLeadFieldTable() {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $table_name = $wpdb->prefix . "ib_lead_fields";
        $sql = "CREATE TABLE $table_name (
            field_id tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
            field_name varchar(100) NOT NULL DEFAULT '',
            field_type char(25) NOT NULL DEFAULT '',
            field_token varchar(100) NOT NULL DEFAULT '',
            field_value blob NULL,
            field_custom tinyint (1) DEFAULT '0',
            created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
            updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
            deleted_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY  (field_id),
            UNIQUE KEY ib_field_name (field_name)
        ) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB;";
        $wpdb->query($sql);
    }

    private function createEmailsTable() {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $table_name = $wpdb->prefix . "ib_emails";
        $sql = "CREATE TABLE $table_name (
            email_id tinyint(11) unsigned NOT NULL AUTO_INCREMENT,
            email_template_id INT(11) UNSIGNED NOT NULL DEFAULT '0',
            email_title varchar(100) NOT NULL DEFAULT '',
            email_subject varchar(255),
            email_value blob,
            email_download_link tinyint(1) unsigned NOT NULL DEFAULT '1',
            send_to text NULL DEFAULT NULL,
            send_cc text NULL DEFAULT NULL,
            send_bcc text NULL DEFAULT NULL,
            email_level INT DEFAULT 1,
            open_points INT NULL,
            click_points INT NULL,
            campaign_id INT NULL,
            created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
            updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
            deleted_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY  (email_id),
            UNIQUE KEY ib_email_title (email_title)
        ) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB;";

        $wpdb->query($sql);
    }

    /* Create email templates table
     *  and create first email template (either from defaults or previous settings)
     *
     * @param object $old_settings previously selected email template settings
     * @return int id of new template
     * @author Rico Celis
     * @access private
     */

    private function createEmailTemplatesTable($old_settings = null) {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $table_name = $wpdb->prefix . "ib_email_templates";
        $sql = "CREATE TABLE {$table_name} (
		  `email_template_id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
		  `description` text COLLATE utf8_bin NULL,
		  `send_to` text COLLATE utf8_bin NULL,
		  `send_cc` text COLLATE utf8_bin NULL,
		  `send_bcc` text COLLATE utf8_bin NULL,
		  `settings` BLOB NULL,
		  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `deleted_at` timestamp NULL DEFAULT NULL,
		  PRIMARY KEY  (email_template_id)
		) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB;";
        $wpdb->query($sql);
// create first template based on old settings.
        return $this->createDefaultEmailTemplate($old_settings);
    }

    private function createEmailFieldTable() {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $table_name = $wpdb->prefix . "ib_email_field";
        $sql = "CREATE TABLE $table_name (
                  email_id TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
                  field_id TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
                  UNIQUE KEY ib_email_field_comound (email_id,field_id)
                ) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB;";
        $wpdb->query($sql);

        $key = "ALTER TABLE $table_name
                    ADD CONSTRAINT " . $table_name . "_field_id FOREIGN KEY (field_id)
                    REFERENCES " . $wpdb->prefix . "ib_lead_fields (field_id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE;";
        $wpdb->query($key);
    }

    private function createContactFormFieldTable() {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $table_name = $wpdb->prefix . "ib_contact_field";
        $sql = "CREATE TABLE $table_name (
                  post_id BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
                  field_id TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
                  UNIQUE KEY ib_email_field_comound (post_id,field_id)
                ) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB;";
        $wpdb->query($sql);

        $key = "ALTER TABLE $table_name
                    ADD CONSTRAINT " . $table_name . "_post_id FOREIGN KEY (post_id)
                    REFERENCES " . $wpdb->prefix . "posts (ID)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE,
                    ADD CONSTRAINT " . $table_name . "_field_id FOREIGN KEY (field_id)
                    REFERENCES " . $wpdb->prefix . "ib_lead_fields (field_id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE;";
        $wpdb->query($key);
    }

    private function createDownloadTable() {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $table_name = $wpdb->prefix . "ib_downloads";
        $sql = "CREATE TABLE $table_name (
                  download_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                  download_url varchar(255) NOT NULL DEFAULT '',
                  download_expire DATETIME,
                  download_limit TINYINT(2) UNSIGNED,
                  download_alias VARCHAR(16) NOT NULL DEFAULT '',
                  download_title VARCHAR(45) NOT NULL DEFAULT '',
                  download_refer varchar(255) NOT NULL DEFAULT '',
                  lead_id INT(11) unsigned NOT NULL DEFAULT '0',
                  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                  deleted_at timestamp NULL DEFAULT NULL,
                  PRIMARY KEY  (download_id),
                  UNIQUE KEY ib_download_alias (download_alias)
                ) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB;";
        $wpdb->query($sql);
        $key = "ALTER TABLE $table_name
                    ADD CONSTRAINT " . $table_name . "_lead_id FOREIGN KEY (lead_id)
                    REFERENCES " . $wpdb->prefix . "ib_leads (lead_id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE;";
        $wpdb->query($key);
    }

    public function createReportsTables() {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $query = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "ib_analytic_reports (
                  `id` bigint(20) NOT NULL AUTO_INCREMENT,
                  `top_exit_pages` LONGTEXT NULL,
                  `top_landing_pages` LONGTEXT NULL,
                  `total_sessions` INT NULL,
                  `total_session_duration` FLOAT NULL,
                  `total_bounce_rate` FLOAT NULL,
                  `total_page_view_per_session` FLOAT NULL,
                  `total_unique_page_views` INT NULL,
                  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
                   `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
              PRIMARY KEY (id)
              ) CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate} ENGINE=InnoDB";
        $wpdb->query($query);
        $query1 = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "ib_twitter_reports (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `total_twt_retweets` INT NULL,
                `total_twt_mentions` INT NULL,
                `total_twt_followers` INT NULL,
                `total_twt_likes` INT NULL,
                `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
                `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
              PRIMARY KEY (id)
              ) CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate} ENGINE=InnoDB";
        $wpdb->query($query1);
        $query2 = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "ib_linkedin_reports (
                  `id` bigint(20) NOT NULL AUTO_INCREMENT,
                  `company_id` VARCHAR(30) NULL,
                  `total_organic_followers` INT NULL,
                  `total_shares` INT NULL,
                  `total_likes` INT NULL,
                  `total_comments` INT NULL,
                  `total_impressions` INT NULL,
                  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
                  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
                  PRIMARY KEY (id)
                ) CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate} ENGINE=InnoDB";
        $wpdb->query($query2);
        $query3 = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "ib_facebook_reports (
                  `id` bigint(20) NOT NULL AUTO_INCREMENT,
                  `page_id` VARCHAR(100) NULL,
                  `total_page_likes` INT NULL,
                  `total_page_impressions` INT NULL,
                  `total_page_interactions` INT NULL,
                  `total_post_shares` bigint(20) NULL,
                  `total_post_comments` bigint(20) NULL,
                  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
                  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
                PRIMARY KEY (id)
                ) CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate} ENGINE=InnoDB";
        $wpdb->query($query3);
        $ib_report = "CREATE TABLE " . $wpdb->prefix . "ib_reports (
                      `id` INT AUTO_INCREMENT,
                      `facebook_post_count` INT DEFAULT '0',
                      `twitter_post_count` INT DEFAULT '0',
                      `google_post_count` INT DEFAULT '0',
                      `linkedin_post_count` INT DEFAULT '0',
                      `new_leads_captured` INT DEFAULT '0',
                      `total_downloads` INT DEFAULT '0',
                      `email_sent_count` INT DEFAULT '0',
                      `post_published` INT DEFAULT '0',
                      `page_published` INT DEFAULT '0',
                      `updated_at` TIMESTAMP NULL DEFAULT '0000-00-00 00:00:00',
                      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                      `deleted_at` timestamp NULL DEFAULT NULL,
                      PRIMARY KEY (id)
                    ) CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate} ENGINE=InnoDB";
        $wpdb->query($ib_report);
    }

    public function createCampaignTables() {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $master_campaign = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "ib_campaign_master (
                        `cm_id` INT NOT NULL AUTO_INCREMENT,
                                                      `name` varchar(100) NOT NULL DEFAULT '',
                        `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
                        `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
                        `deleted_at` TIMESTAMP NULL DEFAULT NULL,
                        PRIMARY KEY (cm_id)
                        ) CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate} ENGINE=InnoDB";
        $wpdb->query($master_campaign);

        $insert_master = "INSERT INTO " . $wpdb->prefix . "ib_campaign_master VALUES(1,'InboundBrew Master',NOW(),NOW(), NULL)";
        $wpdb->query($insert_master);

        $campaign = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "ib_campaign (
                     `id` INT AUTO_INCREMENT,
                    `name` varchar(100) NOT NULL DEFAULT '',
                    `campaign_level` INT NOT NULL DEFAULT '0',
                    `created_by` INT NULL,
                    `cm_id` INT NOT NULL DEFAULT 1,
                    `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
                    `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
                    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
                    PRIMARY KEY (id),
                    CONSTRAINT ib_campaign_master_id FOREIGN KEY (`cm_id`)
                    REFERENCES " . $wpdb->prefix . "ib_campaign_master (`cm_id`)
                     ) CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate} ENGINE=InnoDB";
        $wpdb->query($campaign);

        $campaign_step = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "ib_campaign_step (
                           `id` INT AUTO_INCREMENT,
                          `name` varchar(100) NOT NULL DEFAULT '',
                          `campaign_id` INT NULL,
                          `email_template_id` TINYINT(3) unsigned NULL,
                          `event_type` VARCHAR(100) DEFAULT 'immediate',
                          `scheduler_value` VARCHAR(100) DEFAULT 1,
                          `scheduler_type` VARCHAR(100) DEFAULT 'days',
                          `sort_order` INT(11) NOT NULL DEFAULT '0',
                          `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
                          `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
                          `deleted_at` TIMESTAMP NULL DEFAULT NULL,
                          PRIMARY KEY (id),
                          CONSTRAINT ib_campaign_step_id FOREIGN KEY (`campaign_id`)
                          REFERENCES " . $wpdb->prefix . "ib_campaign (`id`)
                          ON DELETE SET NULL
                          ON UPDATE CASCADE,
                          CONSTRAINT ib_email_tempalte_id FOREIGN KEY (`email_template_id`)
                          REFERENCES " . $wpdb->prefix . "ib_emails (`email_id`)
                          ON DELETE SET NULL
                          ON UPDATE CASCADE
                         ) CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate} ENGINE=InnoDB";
        $wpdb->query($campaign_step);

        //add leads foreign key
        $add_leads_foreign_key = "ALTER TABLE " . $wpdb->prefix . "ib_leads ADD CONSTRAINT ib_leads_campaign_id FOREIGN KEY (`campaign_id`)
                    REFERENCES " . $wpdb->prefix . "ib_campaign (`id`)
                    ON DELETE SET NULL
                    ON UPDATE CASCADE;";
        $wpdb->query($add_leads_foreign_key);
    }

    public function createTrackingEventsTable() {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $tracking = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "ib_tracking_events (
                   `id` INT AUTO_INCREMENT,
                    `visitor_id` varchar(100) NOT NULL DEFAULT '',
                    `lead_id` INT NULL,
                    `event_type` varchar(100) NOT NULL DEFAULT '',
                    `reference_id` INT NOT NULL DEFAULT '0',
                    `page_id` INT NOT NULL DEFAULT '0',
                    `points` INT DEFAULT '0',
                    `campaign_id` INT NULL,
                    `level` INT DEFAULT 1,
                  `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
                  `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
                  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
                  PRIMARY KEY (id)
                  ) CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate} ENGINE=InnoDB";

        $wpdb->query($tracking);
    }

    public function createCampaignEventsLogTable() {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $campaign_event = "CREATE TABLE " . $wpdb->prefix . "ib_lead_campaign_events_log (
                        `id` INT AUTO_INCREMENT,
                        `campaign_id` int(11) NOT NULL DEFAULT '0',
                        `lead_id` int(11) NOT NULL DEFAULT '0',
                        `current_step_id` int(11) NOT NULL DEFAULT '0',
                        `prev_step_id` int(11) NOT NULL DEFAULT '0',
                        `next_step_id` int(11) NOT NULL DEFAULT '0',
                        `step_schedule_time` TIMESTAMP NULL DEFAULT NULL,
                        `event_execution_time` TIMESTAMP NULL DEFAULT NULL,
                        `email_token` VARCHAR(255) DEFAULT NULL,
                        `parent_id` int(11) NOT NULL DEFAULT '0',
                        `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
                        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        `deleted_at` timestamp NULL DEFAULT NULL,
                        `status` tinyint(1) NOT NULL DEFAULT '0',
                        `event_type` varchar(254) DEFAULT NULL,
                        PRIMARY KEY (id)
                      ) CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate} ENGINE=InnoDB";
        $wpdb->query($campaign_event);
    }

    public function createEmailTrackTable() {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $ib_email_track = "CREATE TABLE " . $wpdb->prefix . "ib_email_track (
                        `id` INT AUTO_INCREMENT,
                        `event_log_id` INT NOT NULL DEFAULT '0',
                        `event_type` varchar(100) NOT NULL DEFAULT '',
                        `message_id` VARCHAR(255) NULL,
                        `event_execution_date`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        `updated_at` TIMESTAMP NULL DEFAULT '0000-00-00 00:00:00',
                        `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        `deleted_at` TIMESTAMP NULL DEFAULT NULL,
                        PRIMARY KEY (id)
                      ) CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate} ENGINE=InnoDB";
        $wpdb->query($ib_email_track);
    }

    /**
     * Imports Country CSV into Country table
     *
     * @author Sean Carrico
     * @access private
     * @return bool
     */
    private function importCountryData() {
        global $wpdb;

        $table_name = $wpdb->prefix . "ib_countries";
        $error = '';
        $file = @plugin_dir_path(__FILE__) . self::DATA_PATH . 'countries.csv';
        if (($handle = fopen($file, "r")) !== FALSE) {
            $separator = ",";
            $row = 1;
            while (($data = fgetcsv($handle, 0, $separator)) !== FALSE) {
                if ($row == 1) {
//skip header row
                    $row++;
                    continue;
                }
                try {
                    $wpdb->insert($table_name, array('country_id' => $data[0], 'country_iso' => $data[1], 'country_name' => $data[2], 'created_at' => current_time('mysql')));
                } catch (Exception $e) {
                    $error .= $e->getMessage() . "<br />";
                }

                $row++;
            }
            if (empty($error)) {
                set_transient('ib_country_import', "Country CSV uploaded successfully.");
                return true;
            } else {
                set_transient('ib_country_import', "Countries imported but with the following errors: <br/>" . $error);
                return false;
            }
        }
        set_transient('ib_country_import', "Countries file could not be opened.");
        return false;
    }

    /**
     * Imports States CSV into State table
     *
     * @author Sean Carrico
     * @access private
     * @return bool
     */
    private function importStateData() {
        global $wpdb;

        $table_name = $wpdb->prefix . "ib_states";
        $error = '';
        $file = @plugin_dir_path(__FILE__) . self::DATA_PATH . 'states.csv';
        if (($handle = fopen($file, "r")) !== FALSE) {
            $separator = ",";
            $row = 1;
            while (($data = fgetcsv($handle, 0, $separator)) !== FALSE) {
                if ($row == 1) {
//skip header row
                    $row++;
                    continue;
                }
                try {
                    $wpdb->insert($table_name, array('state_id' => $data[0], 'state_name' => $data[1], 'state_abbr' => $data[2], 'country_id' => $data[3], 'created_at' => current_time('mysql')));
                } catch (Exception $e) {
                    $error .= $e->getMessage() . "<br />";
                }
                $row++;
            }
            if (empty($error)) {
                set_transient('ib_state_import', "State CSV uploaded successfully.");
                return true;
            } else {
                set_transient('ib_state_import', "States imported but with the following errors: <br/>" . $error);
                return true;
            }
        }
        set_transient('ib_state_import', "State file could not be opened.");
        return false;
    }

    private function importLeadFieldData() {
        global $wpdb;

        $table_name = $wpdb->prefix . "ib_lead_fields";
        $wpdb->insert($table_name, array('field_name' => 'First Name', 'field_type' => 'name', 'field_token' => 'first_name', 'created_at' => current_time('mysql')));
        $wpdb->insert($table_name, array('field_name' => 'Last Name', 'field_type' => 'name', 'field_token' => 'last_name', 'created_at' => current_time('mysql')));
        $wpdb->insert($table_name, array('field_name' => 'Email', 'field_type' => 'email', 'field_token' => 'email', 'created_at' => current_time('mysql')));
        $wpdb->insert($table_name, array('field_name' => 'Address', 'field_type' => 'address', 'field_token' => 'address', 'created_at' => current_time('mysql')));
        $wpdb->insert($table_name, array('field_name' => 'Adddress2', 'field_type' => 'address2', 'field_token' => 'address2', 'created_at' => current_time('mysql')));
        $wpdb->insert($table_name, array('field_name' => 'Zip/Postal Code', 'field_type' => 'postal', 'field_token' => 'postal', 'created_at' => current_time('mysql')));
        $wpdb->insert($table_name, array('field_name' => 'City', 'field_type' => 'city', 'field_token' => 'city', 'created_at' => current_time('mysql')));
        $wpdb->insert($table_name, array('field_name' => 'State', 'field_type' => 'state', 'field_token' => 'state', 'created_at' => current_time('mysql')));
        $wpdb->insert($table_name, array('field_name' => 'Country', 'field_type' => 'country', 'field_token' => 'country', 'created_at' => current_time('mysql')));
        $wpdb->insert($table_name, array('field_name' => 'Date of Birth', 'field_type' => 'birth_date', 'field_token' => 'birth_date', 'created_at' => current_time('mysql')));
        // new fields
        $this->importLeadFieldDataSet2();
        return true;
    }

    private function importLeadFieldDataSet2() {
        global $wpdb;
        $table_name = $wpdb->prefix . "ib_lead_fields";
        $wpdb->insert($table_name, array('field_name' => 'Email2', 'field_type' => 'email', 'field_token' => 'email2', 'created_at' => current_time('mysql')));
        $wpdb->insert($table_name, array('field_name' => 'Phone', 'field_type' => 'phone', 'field_token' => 'phone', 'created_at' => current_time('mysql')));
        $wpdb->insert($table_name, array('field_name' => 'Phone2', 'field_type' => 'phone', 'field_token' => 'phone2', 'created_at' => current_time('mysql')));
        $wpdb->insert($table_name, array('field_name' => 'Social Facebook', 'field_type' => 'url', 'field_token' => 'social_facebook', 'created_at' => current_time('mysql')));
        $wpdb->insert($table_name, array('field_name' => 'Social Twitter', 'field_type' => 'url', 'field_token' => 'social_twitter', 'created_at' => current_time('mysql')));
        $wpdb->insert($table_name, array('field_name' => 'Social Linked In', 'field_type' => 'url', 'field_token' => 'social_linkedin', 'created_at' => current_time('mysql')));
// new fields
        $this->importLeadFieldDataSet3();
        return true;
    }

    private function importLeadFieldDataSet3() {
        global $wpdb;
        $table_name = $wpdb->prefix . "ib_lead_fields";
        $wpdb->update($table_name, array('field_name' => 'Address2'), array('field_token' => 'address2'));
        $wpdb->update($table_name, array('field_name' => 'Social LinkedIn'), array('field_token' => 'social_linkedin'));
//this->importLeadFieldDataSet4();
        return true;
    }

    private function importLeadFieldDataSet4() {
        global $wpdb;
        $table_name = $wpdb->prefix . "ib_lead_fields";
        
        $res = $wpdb->get_results("SELECT * FROM ".$table_name." WHERE `field_name`='Opt-in'");
        if (!$res){
          $wpdb->insert($table_name, array('field_name' => 'Opt-in', 'field_type' => 'checkbox', 'field_token' => 'opt_In', 'created_at' => current_time('mysql')));
        }
        

        $table_name = $wpdb->prefix . "ib_leads";
        $res = $wpdb->get_results("SELECT * 
                                    FROM information_schema.COLUMNS 
                                    WHERE 
                                        TABLE_SCHEMA = '".DB_NAME."' 
                                    AND TABLE_NAME = '".$table_name."' 
                                    AND COLUMN_NAME = 'lead_opt_in'");
        if (!$res){
          $wpdb->query("ALTER TABLE " . $table_name . " ADD lead_opt_in INT(1)");
        }
        return true;
    }

    /**
     * method runs hand-made alter statements for DB upgrades/migrations
     *
     * @author Chris Fontes
     * @arguments $toVersion = the DB version number we are incrementing to
     * @access private
     */
    public function runDBUpdates($current_db_version) {
        global $wpdb;

        $changes = array();

//Performed in a switch so the db version
        switch (true) {
            case ((double) $current_db_version < 1.1): //code here for changes in db version 1.1
                $query = "ALTER TABLE " . $wpdb->prefix . "ib_social_network_post_settings
                          CHANGE COLUMN post_settings_id
                          post_setting_id BIGINT(20) NOT NULL AUTO_INCREMENT FIRST";
                $wpdb->query($query);
                $changes[] = $query;
            case ((double) $current_db_version < 1.2): //code here for changes in db version 1.2
                $query = "SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND TABLE_NAME LIKE '%_ib_%'";
                $resutls = $wpdb->get_results($query);
                foreach ($resutls as $row) {
                    $query = "ALTER TABLE " . $row->TABLE_SCHEMA . "." . $row->TABLE_NAME . " CONVERT TO CHARACTER SET " . $wpdb->charset . " COLLATE " . $wpdb->collate;
                    $wpdb->query($query);
                }
            case ((double) $current_db_version < 1.3): //code here for changes in db version 1.3
                //code here for changes in db version 1.3
                $query = "ALTER TABLE " . $wpdb->prefix . "ib_social_network_accounts ADD is_active TINYINT(1) NOT NULL DEFAULT '1' AFTER display_name";
                $wpdb->query($query);
                $changes[] = $query;
                $query = "ALTER TABLE " . $wpdb->prefix . "ib_social_network_accounts ADD INDEX IDX_activeAccounts (social_network, is_active);";
                $wpdb->query($query);
                $changes[] = $query;
                $query = "ALTER TABLE " . $wpdb->prefix . "ib_social_network_accounts ADD INDEX IDX_activeAccountsType (social_network,account_type, is_active);";
                $wpdb->query($query);
                $changes[] = $query;
            case ((double) $current_db_version < 1.4): //code here for changes in db version 1.4
                $query = "ALTER TABLE " . $wpdb->prefix . "ib_settings CHANGE social_network_widget_twitter_google social_network_widget_google_plus_share TINYINT(1) NOT NULL";
                $query = "ALTER TABLE " . $wpdb->prefix . "ib_settings CHANGE social_network_widget_twitter_pinterest social_network_widget_pinterest_share TINYINT(1) NOT NULL";
                $wpdb->query($query);
                $changes[] = $query;
            case ((double) $current_db_version < 1.5): //code here for changes in db version 1.5
                $query = "ALTER TABLE " . $wpdb->prefix . "ib_email_templates ADD send_to text NULL DEFAULT NULL AFTER email_download_link, ADD send_cc text NULL DEFAULT NULL AFTER send_to, ADD send_bcc text NULL DEFAULT NULL AFTER send_cc";
                $wpdb->query($query);
                $changes[] = $query;
            case ((double) $current_db_version < 1.6): //code here for changes in db version 1.6
                $query = "ALTER TABLE " . $wpdb->prefix . "ib_lead_history ADD wp_user_id BIGINT(20) NOT NULL AFTER history_id";
                $wpdb->query($query);
                $changes[] = $query;
            case ((double) $current_db_version < 1.7):
// create new cta table.
                $query = "CREATE TABLE " . $wpdb->prefix . "ib_ctas (
				  `cta_id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `cta_template_id` bigint(20) NOT NULL DEFAULT '0',
				  `cta_type` ENUM('button','image') NOT NULL DEFAULT 'button',
				  `name` varchar(255) NOT NULL DEFAULT '',
				  `html` longtext NULL DEFAULT NULL,
				  `links_to` enum('internal','external') NOT NULL DEFAULT 'internal',
				  `links_to_value` longtext NULL DEFAULT NULL,
				  `cta_settings` longtext NULL DEFAULT NULL,
				  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				  `deleted_at` timestamp NULL DEFAULT NULL,
				  PRIMARY KEY  (cta_id),
				  KEY IDX_template (cta_template_id)
				) CHARACTER SET $wpdb->charset COLLATE $wpdb->collate ENGINE=InnoDB";
                $wpdb->query($query);
                $changes[] = $query;
// create linkages table
                $query = "CREATE TABLE " . $wpdb->prefix . "ib_cta_post_linkages (
					`linkage_id` bigint(20) NOT NULL AUTO_INCREMENT,
					`cta_id` bigint(20) NOT NULL DEFAULT '0',
					`wp_post_id` bigint(20) NOT NULL DEFAULT '0',
					`created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
					`updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
					PRIMARY KEY  (linkage_id),
					KEY IDX_cta (cta_id),
					KEY IDX_wpPost (wp_post_id)
					) CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate} ENGINE=InnoDB";
                $wpdb->query($query);
                $changes[] = $query;
// add hover state styles to CTA templates table
                $query = "ALTER TABLE " . $wpdb->prefix . "ib_cta_templates ADD hover_styles text NULL DEFAULT NULL AFTER settings";
                $wpdb->query($query);
                $changes[] = $query;
// get all CTA, create template and link to table.
                $args = array(
                    'hierarchical' => 1,
                    'exclude' => '',
                    'include' => '',
                    'meta_key' => '',
                    'meta_value' => '',
                    'authors' => '',
                    'child_of' => 0,
                    'parent' => -1,
                    'exclude_tree' => '',
                    'number' => '',
                    'offset' => 0,
                    'post_type' => "ib-call-to-action",
                );
                $ctas = get_posts($args);
                if (!empty($ctas)) {
                    foreach ($ctas as $wp_cta) {
// take each CTA and create a template from it.
                        $post_custom = get_post_custom($wp_cta->ID);
                        $cta_type = $post_custom['cta_type'][0];
                        $settings = CallToAction::updateCtaSettings($post_custom, $cta_type);
                        if ($cta_type == "button") {
                            $t_settings = $settings;
                            $t_settings['normal']['text']['button_text'] = $wp_cta->post_title;
                            unset($t_settings['actions']);
// create template only if CTA is a button
                            $template = new CTATemplate;
                            $template->name = $wp_cta->post_title;
                            $template->settings = serialize($t_settings);
                            $template->html = stripslashes($wp_cta->post_content);
                            $template->save();
                            $template_id = $template->template_id;
                        } else {
                            $template_id = 0;
                        }
// create new CTA object
                        $cta_settings = array(
                            'actions' => $settings['actions'],
                            'normal' => array(
                                'text' => array(
                                    'button_text' => $settings['normal']['text']['button_text']
                        )));
                        $links_to = $settings['actions']['cta_link'];
                        $links_to_value = $settings['actions'][$links_to . "_link"];
                        if ($links_to == "internal")
                            $links_to_value = url_to_postid($links_to_value); // convert permalink into id.
                        $cta = new CallToAction;
                        $cta->name = $settings['normal']['text']['button_text'];
                        $cta->cta_type = $cta_type;
                        $cta->cta_template_id = $template_id;
                        $cta->html = stripslashes($wp_cta->post_content);
                        $cta->links_to = $links_to;
                        $cta->links_to_value = $links_to_value;
                        $cta->cta_settings = serialize($cta_settings);
                        $cta->save();
                        $cta_id = $cta->cta_id;
// overwrite id to use wp post id
                        $sql = "UPDATE " . $wpdb->prefix . "ib_ctas SET cta_id={$wp_cta->ID} WHERE cta_id={$cta_id}";
                        $wpdb->query($sql);
                    }
// find posts where short codes is found.
                    $query = "SELECT * FROM " . $wpdb->prefix . "posts WHERE post_content LIKE '%[brew_cta id=%'";
                    $pages = $wpdb->get_results($query);
                    if (!empty($pages)) {
                        $ptn = "/\[brew_cta id=\"([^\]]*)\"\]/";
                        foreach ($pages as $page) {
                            preg_match_all($ptn, $page->post_content, $matches);
                            if (!empty($matches[1])) {
                                foreach ($matches[1] as $cta_id) {
                                    $linkage = new CallToActionPostLinkage;
                                    $linkage->cta_id = $cta_id;
                                    $linkage->wp_post_id = $page->ID;
                                    $linkage->save();
                                }
                            }
                        }
                    }
                }
            case ((double) $current_db_version < 1.8):
// rename database table
                $old = $wpdb->prefix . "ib_email_templates";
                $new = $wpdb->prefix . "ib_emails";
                $wpdb->query("RENAME TABLE {$old} TO {$new};");
// add template id linkage
                $sql = "ALTER TABLE " . $wpdb->prefix . "ib_emails ADD `email_template_id` INT(11) NOT NULL AFTER `email_id`";
                $wpdb->query($sql);
// create email template table
                $oldSettings = json_decode(get_option('ib_email_settings'));
                $template_id = $this->createEmailTemplatesTable($oldSettings);
// make current settings a template
// delete unwanted settings fields
                $sql = "ALTER TABLE `" . $wpdb->prefix . "ib_settings` DROP `social_network_widget_facebook_share`, DROP `social_network_widget_facebook_like`, DROP `social_network_widget_twitter_share`, DROP `social_network_widget_pinterest_share`, DROP `social_network_widget_google_plus_share`";
                $wpdb->query($sql);
                $sql = "ALTER TABLE `" . $wpdb->prefix . "ib_settings` ADD `social_url_facebook` text NULL DEFAULT NULL AFTER `social_name_twitter`, ADD `social_url_linkedin` text NULL DEFAULT NULL AFTER `social_url_facebook`, ADD `social_url_twitter` text NULL DEFAULT NULL AFTER `social_url_linkedin`, ADD `social_url_google_plus` text NULL DEFAULT NULL AFTER `social_url_twitter`";
                $wpdb->query($sql);
// use settings links to update table
                $sm = new SettingsModel;
                $settings = $sm->loadSettings();
                if (@$oldSettings->facebook_link)
                    $settings->social_url_facebook = $oldSettings->facebook_link;
                if (@$oldSettings->twitter_link)
                    $settings->social_url_twitter = $oldSettings->twitter_link;
                if (@$oldSettings->linkedin_link)
                    $settings->social_url_linkedin = $oldSettings->linkedin_link;
                if (@$oldSettings->google_link)
                    $settings->social_url_google_plus = $oldSettings->google_link;
                $settings->save();
// update all emails to link to this template
                $sql = "UPDATE `" . $wpdb->prefix . "ib_emails` SET email_template_id='{$template_id}' WHERE 1";
                $wpdb->query($sql);
            case ((double) $current_db_version < 1.9):
                $table = $wpdb->prefix . "ib_lead_history";
                $results = $wpdb->get_results("SHOW COLUMNS FROM `{$table}` LIKE 'wp_user_id'");
                if (empty($results)) {
                    $query = "ALTER TABLE " . $table . " ADD wp_user_id BIGINT(20) NOT NULL AFTER history_id";
                    $wpdb->query($query);
                    $changes[] = $query;
                }
            case ((double) $current_db_version < 2.0):
// add extra lead fields
                $query = "ALTER TABLE `" . $wpdb->prefix . "ib_leads`
                					ADD `lead_email2` VARCHAR(75) NOT NULL AFTER `lead_email`,
                					ADD `lead_phone2` VARCHAR(20) NOT NULL AFTER `lead_phone`,
                					ADD `lead_social_facebook` VARCHAR(255) NOT NULL AFTER `lead_score`,
                					ADD `assigned_to` BIGINT(20) NOT NULL AFTER `lead_id`,
                					ADD `lead_social_twitter` VARCHAR(255) NOT NULL AFTER `lead_social_facebook`,
                					ADD `lead_social_linkedin` VARCHAR(255) NOT NULL AFTER `lead_social_twitter`,
                					ADD `lead_picture` text NULL DEFAULT NULL AFTER `lead_social_linkedin`";
                $wpdb->query($query);
                $changes[] = $query;
                $query = "ALTER TABLE `" . $wpdb->prefix . "ib_lead_history` CHANGE `history_event` `history_event` text NULL DEFAULT NULL";
                $wpdb->query($query);
                $changes[] = $query;
                $this->importLeadFieldDataSet2();
                $table = $wpdb->prefix . "ib_lead_history";
                $results = $wpdb->get_results("SELECT * FROM {$table} WHERE history_type IN(" . BREW_LEAD_HISTORY_TYPE_CREATED . "," . BREW_LEAD_HISTORY_TYPE_UPDATED . ")");
                if (!empty($results)) {
                    foreach ($results as $history) {
                        switch ($history->history_type) {
                            case BREW_LEAD_HISTORY_TYPE_CREATED:
                                $pattern = "/Lead Created by (.*):/";
                                preg_match($pattern, $history->history_note, $matches);
                                $query = "UPDATE {$table} SET history_event='Lead Created'";
                                if (!empty($matches)) {
                                    $user_id = $matches[1];
                                    $query .= ",wp_user_id='" . intval($user_id) . "',history_note='Lead created by a user.'"; // also update history note
                                }
                                $query .= " WHERE history_id='" . $history->history_id . "'";
                                $wpdb->query($query);
                                $changes[] = $query;
                                break;
                            case BREW_LEAD_HISTORY_TYPE_UPDATED:
                                $pattern = "/Lead Updated by (.*):/";
                                preg_match($pattern, $history->history_note, $matches);

                                if (!empty($matches)) {
                                    $user_id = $matches[1];
                                    $query = "UPDATE {$table} SET wp_user_id='" . intval($user_id) . "', history_note='Lead fields where updated.' WHERE history_id='" . $history->history_id . "'"; // also update history note
                                    $wpdb->query($query);
                                    $changes[] = $query;
                                }

                                break;
                        }
                    }
                }
            case ((double) $current_db_version < 2.1):
                $this->createLeadViewsTable();
            case ((double) $current_db_version < 2.2):
                $emails = EmailModel::where("email_value", "LIKE", "%{{download_link}}%")->get();
                if (count($emails)) {
                    foreach ($emails as $email) {
                        $email->email_download_link = 1;
                        $email->save();
                    }
                }
            case ((double) $current_db_version < 2.25):
                $query = "ALTER TABLE `" . $wpdb->prefix . "ib_emails` ADD `send_to` text NULL DEFAULT NULL AFTER `email_download_link`,
                					ADD `send_cc` text NULL DEFAULT NULL AFTER `send_to`,
                					ADD `send_bcc` text NULL DEFAULT NULL AFTER `send_cc`";
                $wpdb->query($query);
            case ((double) $current_db_version < 2.26):
                $query = "ALTER TABLE `" . $wpdb->prefix . "ib_settings` ADD `social_connected_google` TIMESTAMP NULL AFTER `social_name_twitter`,
					               ADD `social_name_google` VARCHAR(100) NOT NULL AFTER `social_connected_google`";
                $wpdb->query($query);

            case ((double) $current_db_version < 2.27):
                $this->createReportsTables();
            case ((double) $current_db_version < 2.28):
                $query = "ALTER TABLE " . $wpdb->prefix . "ib_ctas CHANGE `cta_type` `cta_type` ENUM('button','image','before_leave_cta','top_bar') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
                $wpdb->query($query);
            case ((double) $current_db_version < 2.29):
                $this->createCampaignTables();
                $lead = "ALTER TABLE " . $wpdb->prefix . "ib_leads ADD `campaign_id` INT NULL AFTER lead_score";
                $wpdb->query($lead);
                $key = "ALTER TABLE " . $wpdb->prefix . "ib_leads ADD CONSTRAINT ib_leads_campaign_id FOREIGN KEY (`campaign_id`)
                    REFERENCES " . $wpdb->prefix . "ib_campaign (`id`)
                    ON DELETE SET NULL
                    ON UPDATE CASCADE;";
                $wpdb->query($key);
            case ((double) $current_db_version < 2.30):
                $query = "ALTER TABLE `" . $wpdb->prefix . "ib_social_network_post_settings` CHANGE COLUMN `posting_status` `posting_status` ENUM('','not','posted','error', 'pending') NOT NULL DEFAULT '' AFTER `when_to_post_time`";
                $wpdb->query($query);
            case ((double) $current_db_version < 2.31):
                $query = "ALTER TABLE `" . $wpdb->prefix . "ib_social_network_post_settings`
                          CHANGE COLUMN `posting_status` `posting_status` ENUM('','not','posted','error','pending') NOT NULL DEFAULT 'not' AFTER `when_to_post_time`;";
                $wpdb->query($query);
                $campaign_step = "ALTER TABLE `" . $wpdb->prefix . "ib_campaign_step` ADD `sort_order` INT(11) NOT NULL DEFAULT '0' AFTER `scheduler_type`";
                $wpdb->query($campaign_step);
                $cta = "ALTER TABLE " . $wpdb->prefix . "ib_ctas ADD `cta_level` INT DEFAULT 1 AFTER links_to_value, ADD `cta_points` INT DEFAULT '0' AFTER `cta_level`";
                $wpdb->query($cta);

            case ((double) $current_db_version < 2.32) :
                $emails = "ALTER TABLE " . $wpdb->prefix . "ib_emails ADD `email_level` INT DEFAULT 1 AFTER send_bcc, ADD `email_points` INT DEFAULT '0' AFTER `email_level`";
                $wpdb->query($emails);
                $lead = "ALTER TABLE " . $wpdb->prefix . "ib_leads ADD `lead_level` INT DEFAULT 1 AFTER `campaign_id`";
                $wpdb->query($lead);
            case ((double) $current_db_version < 2.33) :
                $this->createTrackingEventsTable();
            case ((double) $current_db_version < 2.34) :
                $lead_sql = "ALTER TABLE " . $wpdb->prefix . "ib_leads CHANGE `lead_score` `lead_score` TINYINT(3) UNSIGNED NULL DEFAULT '0'";
                $wpdb->query($lead_sql);
                $lead_score = "Update " . $wpdb->prefix . "ib_leads set lead_score = 0 WHERE lead_score IS NULL";
                $wpdb->query($lead_score);
                $this->createCampaignEventsLogTable();
            case ((double) $current_db_version < 2.35) :
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_leads CHANGE `lead_score` `lead_score` TINYINT(3) UNSIGNED NULL DEFAULT '0'");
                $wpdb->query("UPDATE " . $wpdb->prefix . "ib_leads SET lead_score = 0 WHERE lead_score IS NULL");
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_lead_campaign_events_log CHANGE `event_execution_time` `event_execution_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP");
            case ((double) $current_db_version < 2.36) :
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_lead_campaign_events_log ADD `email_token` VARCHAR(255) DEFAULT NULL AFTER `event_execution_time`");
            case ((double) $current_db_version < 2.37) :
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_ctas ADD `campaign_id` INT NULL AFTER `cta_points`");
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_ctas ADD CONSTRAINT ib_cta_campaign_id FOREIGN KEY (`campaign_id`)
                    REFERENCES " . $wpdb->prefix . "ib_campaign (`id`)
                    ON DELETE SET NULL
                    ON UPDATE CASCADE;");
            case ((double) $current_db_version < 2.38) :
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_emails CHANGE `email_points` `open_points` INT NULL AFTER `email_level`");
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_emails Add `click_points` INT NULL AFTER `open_points`");
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_emails ADD `campaign_id` INT NULL AFTER `open_points`");
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_emails ADD CONSTRAINT ib_email_campaign_id FOREIGN KEY (`campaign_id`)
                    REFERENCES " . $wpdb->prefix . "ib_campaign (`id`)
                    ON DELETE SET NULL
                    ON UPDATE CASCADE;");
            case ((double) $current_db_version < 2.39) :
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_tracking_events Add `campaign_id` INT NULL AFTER `points`");
            case ((double) $current_db_version < 2.40) :
                $this->createEmailTrackTable();
            /*case ((double) $current_db_version < 2.41) :
                //fixing bad foreign key constraints
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_emails DROP FOREIGN KEY ib_email_campaign_id");
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_ctas DROP FOREIGN KEY ib_cta_campaign_id");
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_leads DROP FOREIGN KEY ib_leads_campaign_id");

            case ((double) $current_db_version < 2.42) :
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_lead_campaign_events_log CHANGE `event_execution_time` `event_execution_time` TIMESTAMP NULL DEFAULT NULL");
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_lead_campaign_events_log CHANGE `step_schedule_time` `step_schedule_time` TIMESTAMP NULL DEFAULT NULL");
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_lead_campaign_events_log CHANGE `updated_at` `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'");
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_reports CHANGE `updated_at` `updated_at` TIMESTAMP NULL DEFAULT '0000-00-00 00:00:00'");
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_email_track CHANGE `event_execution_date` `event_execution_date` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'");
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_email_track CHANGE `updated_at` `updated_at` TIMESTAMP NULL DEFAULT '0000-00-00 00:00:00'");
            */
            case ((double) $current_db_version < 2.43) :
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_leads ADD COLUMN `lead_first_name` VARCHAR(75) NULL AFTER `lead_name`");
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_leads ADD COLUMN `lead_last_name` VARCHAR(75) NULL AFTER `lead_first_name`");
                $wpdb->query("UPDATE " . $wpdb->prefix . "ib_leads
                            SET `lead_first_name`=SUBSTRING_INDEX(`lead_name`, ' ', 1),
                            `lead_last_name`=REPLACE(`lead_name`, CONCAT(SUBSTRING_INDEX(`lead_name`, ' ', 1), ' '), '')");

                $settings = array(
                    'lead_view_id' => "all",
                    'view_name' => "All Leads",
                    'view_filters' => array(
                        'static' => array(
                            'archived_leads' => "only_active"
                        )
                    ),
                    'view_columns' => array('type_id', 'lead_first_name', 'lead_last_name', 'lead_email', 'lead_phone', 'created_at', 'updated_at', 'assigned_to'),
                    'view_columns_width' => array(),
                    'view_columns_order' => array(),
                    'public' => "",
                );
                update_option(BREW_DEFAULT_LEAD_VIEW_SETTINGS_OPTION, $settings);

                $wpdb->query("UPDATE " . $wpdb->prefix . "ib_lead_fields SET `field_name` = 'First Name', `field_token`='first_name' WHERE `field_token`='name'");
                $wpdb->query("INSERT INTO " . $wpdb->prefix . "ib_lead_fields
                              SET `field_name` = 'Last Name',
                              `field_token`='last_name',
                              `field_type`='name',
                              `field_value`='',
                              `field_custom`=0,
                              `created_at`=NOW(),
                              `updated_at`=NOW()
                              ");
                $wpdb->query("UPDATE " . $wpdb->prefix . "ib_lead_fields SET `field_type` = 'email2' WHERE `field_token`='email2'");
            case ((double) $current_db_version < 2.44) :

            case ((double) $current_db_version < 2.45) :
                /*$wpdb->query("ALTER TABLE " . $wpdb->prefix . "ib_leads CHANGE `lead_name` VARCHAR(75) NULL DEFAULT NULL");*/
            case ((double) $current_db_version < 2.5) :
                /*we need to make sure that the DB schema is accurate - a bug was introduced that made 
                it possible to have two different version of the schema, depending on if it was a fresh 
                install or if it was upgraded. */



                $charset_collate = $wpdb->get_charset_collate();
                $schema = "

CREATE TABLE `" . $wpdb->prefix . "ib_campaign_master` (
  `cm_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cm_id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_campaign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `campaign_level` int(11) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL DEFAULT '0',
  `cm_id` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ib_campaign_master_id` (`cm_id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_analytic_reports` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `top_exit_pages` longtext,
  `top_landing_pages` longtext,
  `total_sessions` int(11) DEFAULT NULL DEFAULT '0',
  `total_session_duration` float DEFAULT NULL,
  `total_bounce_rate` float DEFAULT NULL,
  `total_page_view_per_session` float DEFAULT NULL,
  `total_unique_page_views` int(11) DEFAULT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ".$charset_collate.";


CREATE TABLE `" . $wpdb->prefix . "ib_campaign_step` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `campaign_id` int(11) DEFAULT NULL DEFAULT '0',
  `email_template_id` tinyint(3) unsigned DEFAULT NULL,
  `event_type` varchar(100) DEFAULT 'immediate',
  `scheduler_value` varchar(100) DEFAULT '1',
  `scheduler_type` varchar(100) DEFAULT 'days',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ib_campaign_step_id` (`campaign_id`),
  KEY `ib_email_tempalte_id` (`email_template_id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_contact_field` (
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `field_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `ib_email_field_comound` (`post_id`,`field_id`),
  KEY `" . $wpdb->prefix . "ib_contact_field_field_id` (`field_id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_countries` (
  `country_id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `country_iso` char(2) NOT NULL DEFAULT '',
  `country_name` varchar(80) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`country_id`),
  UNIQUE KEY `ib_country_iso` (`country_iso`),
  UNIQUE KEY `ib_country_name` (`country_name`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_ctas` (
  `cta_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cta_template_id` bigint(20) NOT NULL DEFAULT '0',
  `cta_type` enum('button','image','before_leave_cta','top_bar') NOT NULL DEFAULT 'button',
  `name` varchar(255) NOT NULL DEFAULT '',
  `html` longtext NULL DEFAULT NULL,
  `links_to` enum('internal','external') NOT NULL DEFAULT 'internal',
  `links_to_value` longtext NULL DEFAULT NULL,
  `cta_level` int(11) DEFAULT '1',
  `cta_points` int(11) DEFAULT '0',
  `campaign_id` int(11) DEFAULT NULL DEFAULT '0',
  `cta_settings` longtext NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cta_id`),
  KEY `IDX_template` (`cta_template_id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_cta_post_linkages` (
  `linkage_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cta_id` bigint(20) NOT NULL DEFAULT '0',
  `" . $wpdb->prefix . "post_id` bigint(20) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`linkage_id`),
  KEY `IDX_cta` (`cta_id`),
  KEY `IDX_wpPost` (`" . $wpdb->prefix . "post_id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_cta_templates` (
  `template_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `html` text NULL DEFAULT NULL,
  `settings` text NULL DEFAULT NULL,
  `hover_styles` text NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`template_id`),
  KEY `IDX_name` (`name`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_downloads` (
  `download_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `download_url` varchar(255) NOT NULL DEFAULT '',
  `download_expire` datetime DEFAULT NULL,
  `download_limit` tinyint(2) unsigned DEFAULT NULL,
  `download_alias` varchar(16) NOT NULL DEFAULT '',
  `download_title` varchar(45) NOT NULL DEFAULT '',
  `download_refer` varchar(255) NOT NULL DEFAULT '',
  `lead_id` int(11) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`download_id`),
  UNIQUE KEY `ib_download_alias` (`download_alias`),
  KEY `" . $wpdb->prefix . "ib_downloads_lead_id` (`lead_id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_emails` (
  `email_id` tinyint(11) unsigned NOT NULL AUTO_INCREMENT,
  `email_template_id` int(11) unsigned NOT NULL DEFAULT '0',
  `email_title` varchar(100) NOT NULL DEFAULT '',
  `email_subject` varchar(255) DEFAULT NULL,
  `email_value` blob,
  `email_download_link` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `send_to` text NULL DEFAULT NULL,
  `send_cc` text NULL DEFAULT NULL,
  `send_bcc` text NULL DEFAULT NULL,
  `email_level` int(11) DEFAULT '1',
  `open_points` int(11) DEFAULT NULL DEFAULT '0',
  `click_points` int(11) DEFAULT NULL DEFAULT '0',
  `campaign_id` int(11) DEFAULT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email_id`),
  UNIQUE KEY `ib_email_title` (`email_title`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_email_field` (
  `email_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `field_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `ib_email_field_comound` (`email_id`,`field_id`),
  KEY `" . $wpdb->prefix . "ib_email_field_field_id` (`field_id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_email_templates` (
  `email_template_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `description` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  `send_to` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  `send_cc` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  `send_bcc` text CHARACTER SET utf8 COLLATE utf8_bin NULL,
  `settings` blob NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email_template_id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_email_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_log_id` int(11) NOT NULL DEFAULT '0',
  `event_type` varchar(100) NOT NULL DEFAULT '',
  `message_id` varchar(255) DEFAULT NULL,
  `event_execution_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_facebook_reports` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `page_id` varchar(100) DEFAULT NULL,
  `total_page_likes` int(11) DEFAULT NULL DEFAULT '0',
  `total_page_impressions` int(11) DEFAULT NULL DEFAULT '0',
  `total_page_interactions` int(11) DEFAULT NULL DEFAULT '0',
  `total_post_shares` bigint(20) DEFAULT NULL,
  `total_post_comments` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_keywords` (
  `keyword_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `keyword_value` varchar(75) NOT NULL DEFAULT '',
  `keyword_score` smallint(6) DEFAULT NULL,
  `keyword_serp` tinyint(2) DEFAULT NULL,
  `keyword_rank` tinyint(2) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`keyword_id`),
  KEY `ib_keyword_value` (`keyword_value`),
  KEY `ib_keyword_score` (`keyword_score`),
  KEY `ib_keyword_serp` (`keyword_serp`),
  KEY `ib_keyword_rank` (`keyword_rank`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_leads` (
  `lead_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `assigned_to` bigint(20) NOT NULL DEFAULT '0',
  `lead_email` varchar(75) NOT NULL DEFAULT '',
  `lead_email2` varchar(75) NOT NULL DEFAULT '',
  `lead_ip` varchar(39) NOT NULL DEFAULT '',
  `lead_name` varchar(75) DEFAULT NULL,
  `lead_first_name` varchar(75) DEFAULT NULL,
  `lead_last_name` varchar(75) DEFAULT NULL,
  `lead_address` varchar(255) DEFAULT NULL,
  `lead_address2` varchar(255) DEFAULT NULL,
  `lead_city` varchar(75) DEFAULT NULL,
  `lead_state` varchar(50) DEFAULT NULL,
  `country_id` smallint(3) DEFAULT NULL,
  `lead_postal` varchar(16) DEFAULT NULL,
  `lead_phone` varchar(20) DEFAULT NULL,
  `lead_phone2` varchar(20) NOT NULL DEFAULT '',
  `lead_dob` date DEFAULT NULL,
  `lead_score` tinyint(3) unsigned DEFAULT '0',
  `campaign_id` int(11) DEFAULT NULL DEFAULT NULL,
  `lead_level` int(11) DEFAULT '1',
  `lead_social_facebook` varchar(255) NOT NULL DEFAULT '',
  `lead_social_twitter` varchar(255) NOT NULL DEFAULT '',
  `lead_social_linkedin` varchar(255) NOT NULL DEFAULT '',
  `lead_picture` text NULL DEFAULT NULL,
  `type_id` tinyint(2) unsigned DEFAULT '1',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `lead_opt_in` int(1) DEFAULT NULL,
  PRIMARY KEY (`lead_id`),
  UNIQUE KEY `ib_lead_email` (`lead_email`),
  KEY `ib_lead_ip` (`lead_ip`),
  KEY `ib_leads_campaign_id` (`campaign_id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_lead_campaign_events_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL DEFAULT '0',
  `lead_id` int(11) NOT NULL DEFAULT '0',
  `current_step_id` int(11) NOT NULL DEFAULT '0',
  `prev_step_id` int(11) NOT NULL DEFAULT '0',
  `next_step_id` int(11) NOT NULL DEFAULT '0',
  `step_schedule_time` timestamp NULL DEFAULT NULL,
  `event_execution_time` timestamp NULL DEFAULT NULL,
  `email_token` varchar(255) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `event_type` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_lead_data` (
  `data_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` int(11) unsigned DEFAULT NULL,
  `data_term` varchar(45) DEFAULT NULL,
  `data_value` blob,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`data_id`),
  KEY `ib_lead_data_lead_id` (`lead_id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_lead_fields` (
  `field_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `field_name` varchar(100) NOT NULL DEFAULT '',
  `field_type` char(25) NOT NULL DEFAULT '',
  `field_token` varchar(100) NOT NULL DEFAULT '',
  `field_value` blob NULL,
  `field_custom` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`field_id`),
  UNIQUE KEY `ib_field_name` (`field_name`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_lead_history` (
  `history_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `" . $wpdb->prefix . "user_id` bigint(20) NOT NULL DEFAULT '0',
  `history_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `history_event` text NULL DEFAULT NULL,
  `history_note` blob,
  `lead_id` int(11) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`history_id`),
  KEY `" . $wpdb->prefix . "ib_lead_history_lead_id` (`lead_id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_lead_views` (
  `lead_view_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `" . $wpdb->prefix . "user_id` bigint(20) NOT NULL DEFAULT '0',
  `view_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `view_access` enum('public','private') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'private',
  `display_order` float NULL,
  `view_filters` blob NULL,
  `view_columns` blob NULL,
  `view_columns_order` blob NULL,
  `view_columns_width` blob NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`lead_view_id`),
  KEY `IDX_user` (`" . $wpdb->prefix . "user_id`,`view_access`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_linkedin_reports` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `company_id` varchar(30) DEFAULT NULL,
  `total_organic_followers` int(11) DEFAULT NULL DEFAULT '0',
  `total_shares` int(11) DEFAULT NULL DEFAULT '0',
  `total_likes` int(11) DEFAULT NULL DEFAULT '0',
  `total_comments` int(11) DEFAULT NULL DEFAULT '0',
  `total_impressions` int(11) DEFAULT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_post_keyword` (
  `keyword_id` int(11) unsigned NOT NULL DEFAULT '0',
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `ib_post_keyword_comound` (`post_id`,`keyword_id`),
  KEY `" . $wpdb->prefix . "ib_post_keyword_keyword_id` (`keyword_id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_redirects` (
  `redirect_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `redirect_from` varchar(255) NOT NULL DEFAULT '',
  `redirect_to` varchar(255) NOT NULL DEFAULT '',
  `redirect_type` varchar(12) NOT NULL DEFAULT 'url',
  `status` varchar(3) NOT NULL DEFAULT '',
  `is_wildcard` tinyint(1) NOT NULL DEFAULT '0',
  `redirect_uses` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`redirect_id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facebook_post_count` int(11) DEFAULT '0',
  `twitter_post_count` int(11) DEFAULT '0',
  `google_post_count` int(11) DEFAULT '0',
  `linkedin_post_count` int(11) DEFAULT '0',
  `new_leads_captured` int(11) DEFAULT '0',
  `total_downloads` int(11) DEFAULT '0',
  `email_sent_count` int(11) DEFAULT '0',
  `post_published` int(11) DEFAULT '0',
  `page_published` int(11) DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_settings` (
  `settings_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `social_connected_facebook` timestamp NULL DEFAULT NULL,
  `social_name_facebook` varchar(100) NULL DEFAULT NULL,
  `social_connected_linked_in` timestamp NULL DEFAULT NULL,
  `social_name_linked_in` varchar(100) NULL DEFAULT NULL,
  `social_connected_twitter` timestamp NULL DEFAULT NULL,
  `social_name_twitter` varchar(100) NULL DEFAULT NULL,
  `social_connected_google` timestamp NULL DEFAULT NULL,
  `social_name_google` varchar(100) NULL DEFAULT NULL,
  `social_url_facebook` text NULL DEFAULT NULL,
  `social_url_linkedin` text NULL DEFAULT NULL,
  `social_url_twitter` text NULL DEFAULT NULL,
  `social_url_google_plus` text NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `wizzard_emails` tinyint(1) NOT NULL DEFAULT '0',
  `wizzard_contact_forms` tinyint(1) NOT NULL DEFAULT '0',
  `wizzard_social_settings` tinyint(1) NOT NULL DEFAULT '0',
  `wizzard_landing_pages` tinyint(1) NOT NULL DEFAULT '0',
  `wizzard_ctas` tinyint(1) NOT NULL DEFAULT '0',
  `wizzard_hide` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`settings_id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_social_network_accounts` (
  `account_id` int(11) NOT NULL AUTO_INCREMENT,
  `social_network` varchar(20) NOT NULL DEFAULT '',
  `account_type` varchar(20) NOT NULL DEFAULT '',
  `account_type_id` varchar(255) NOT NULL DEFAULT '',
  `token` text NULL DEFAULT NULL,
  `meta1` text NULL DEFAULT NULL,
  `meta2` text NULL DEFAULT NULL,
  `display_name` varchar(255) NOT NULL DEFAULT '',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`account_id`),
  KEY `IDX_social_network` (`social_network`,`account_type`,`account_type_id`),
  KEY `IDX_activeAccounts` (`social_network`,`is_active`),
  KEY `IDX_activeAccountsType` (`social_network`,`account_type`,`is_active`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_social_network_post_records` (
  `record_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `social_network` varchar(20) NOT NULL DEFAULT '',
  `post_setting_id` bigint(20) NOT NULL DEFAULT '0',
  `social_network_account_id` bigint(20) NOT NULL DEFAULT '0',
  `post_id` varchar(255) NOT NULL DEFAULT '',
  `post_meta1` text NULL DEFAULT NULL,
  `error_message` text NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`record_id`),
  KEY `IDX_postSetting` (`post_setting_id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_social_network_post_settings` (
  `post_setting_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `social_network` varchar(20) NOT NULL DEFAULT '',
  `" . $wpdb->prefix . "post_id` bigint(20) NOT NULL DEFAULT '0',
  `when_to_post` enum('now','on') NOT NULL DEFAULT 'now',
  `when_to_post_on_option` varchar(20) NOT NULL DEFAULT '',
  `when_to_post_on_option_value` varchar(20) NOT NULL DEFAULT '',
  `when_to_post_time` time NULL,
  `posting_status` enum('','not','posted','error','pending') NOT NULL DEFAULT 'not',
  `post_at` datetime DEFAULT NULL,
  `posted_title` text NULL DEFAULT NULL,
  `posted_image` text NULL DEFAULT NULL,
  `posted_description` text NULL DEFAULT NULL,
  `posted_url` text NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_setting_id`),
  KEY `IDX_socialNetwork` (`social_network`,`" . $wpdb->prefix . "post_id`,`posting_status`),
  KEY `IDX_status` (`posting_status`,`social_network`,`post_at`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_social_network_post_setting_accounts` (
  `posting_account_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `posting_setting_id` bigint(20) NOT NULL DEFAULT '0',
  `network_account_id` bigint(20) NOT NULL DEFAULT '0',
  `social_network` varchar(20) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`posting_account_id`),
  KEY `IDX_postSetting` (`posting_account_id`),
  KEY `IDX_socialNetwork` (`social_network`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_states` (
  `state_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `state_name` varchar(100) NOT NULL DEFAULT '',
  `state_abbr` char(2) NOT NULL DEFAULT '',
  `country_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`state_id`),
  KEY `" . $wpdb->prefix . "ib_states_country_id` (`country_id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_tracking_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitor_id` varchar(100) NOT NULL DEFAULT '',
  `lead_id` int(11) DEFAULT NULL DEFAULT '0',
  `event_type` varchar(100) NOT NULL DEFAULT '',
  `reference_id` int(11) NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `points` int(11) DEFAULT '0',
  `campaign_id` int(11) DEFAULT NULL DEFAULT '0',
  `level` int(11) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ".$charset_collate.";

CREATE TABLE `" . $wpdb->prefix . "ib_twitter_reports` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `total_twt_retweets` int(11) DEFAULT NULL DEFAULT '0',
  `total_twt_mentions` int(11) DEFAULT NULL DEFAULT '0',
  `total_twt_followers` int(11) DEFAULT NULL DEFAULT '0',
  `total_twt_likes` int(11) DEFAULT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ".$charset_collate.";

";

          require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
          dbDelta( $schema );



          $name = $wpdb->prefix."ib_contact_field_post_id";
          if (!$this->constraintExists($name)){
            $wpdb->query("ALTER TABLE ".$wpdb->prefix."ib_contact_field ADD CONSTRAINT `$name` FOREIGN KEY (`post_id`) REFERENCES `" . $wpdb->prefix . "posts` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE");
          }

          $name = $wpdb->prefix."ib_downloads_lead_id";
          if (!$this->constraintExists($name)){
            $wpdb->query("ALTER TABLE ".$wpdb->prefix."ib_downloads ADD CONSTRAINT `".$name."` FOREIGN KEY (`lead_id`) REFERENCES `".$wpdb->prefix."ib_leads` (`lead_id`) ON DELETE CASCADE ON UPDATE CASCADE");
          }

          $name = $wpdb->prefix."ib_email_field_field_id";
          if (!$this->constraintExists($name)){
            $wpdb->query("ALTER TABLE ".$wpdb->prefix."ib_email_field ADD CONSTRAINT `".$name."` FOREIGN KEY (`field_id`) REFERENCES `".$wpdb->prefix."ib_lead_fields` (`field_id`) ON DELETE CASCADE ON UPDATE CASCADE");
          }

          $name = "ib_leads_campaign_id";
          if (!$this->constraintExists($name)){
            $wpdb->query("ALTER TABLE ".$wpdb->prefix."ib_leads ADD CONSTRAINT `".$name."` FOREIGN KEY (`campaign_id`) REFERENCES `".$wpdb->prefix."ib_campaign` (`id`) ON DELETE SET NULL ON UPDATE CASCADE");
          }

          $name = $wpdb->prefix."ib_lead_data_lead_id";
          if (!$this->constraintExists($name)){
            $wpdb->query("ALTER TABLE ".$wpdb->prefix."ib_lead_data ADD CONSTRAINT `".$name."` FOREIGN KEY (`lead_id`) REFERENCES `".$wpdb->prefix."ib_leads` (`lead_id`) ON DELETE CASCADE ON UPDATE CASCADE");
          }

          $name = $wpdb->prefix."ib_lead_history_lead_id";
          if (!$this->constraintExists($name)){
            $wpdb->query("ALTER TABLE ".$wpdb->prefix."ib_lead_history ADD CONSTRAINT `".$name."` FOREIGN KEY (`lead_id`) REFERENCES `".$wpdb->prefix."ib_leads` (`lead_id`) ON DELETE CASCADE ON UPDATE CASCADE");
          }

          $name = $wpdb->prefix."ib_post_keyword_post_id";
          if (!$this->constraintExists($name)){
            $wpdb->query("ALTER TABLE ".$wpdb->prefix."ib_post_keyword ADD CONSTRAINT `".$name."` FOREIGN KEY (`post_id`) REFERENCES `".$wpdb->prefix."posts` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE");
          }

          $name = $wpdb->prefix."ib_states_country_id";
          if (!$this->constraintExists($name)){
            $wpdb->query("ALTER TABLE ".$wpdb->prefix."ib_states ADD CONSTRAINT `".$name."` FOREIGN KEY (`country_id`) REFERENCES `".$wpdb->prefix."ib_countries` (`country_id`) ON DELETE CASCADE ON UPDATE CASCADE");
          }

          $name = "ib_campaign_master_id";
          if (!$this->constraintExists($name)){
            $wpdb->query("ALTER TABLE ".$wpdb->prefix."ib_campaign ADD CONSTRAINT `".$name."` FOREIGN KEY (`cm_id`) REFERENCES `".$wpdb->prefix."ib_campaign_master` (`cm_id`)");
          }

          $name = "ib_email_tempalte_id";
          if (!$this->constraintExists($name)){
            $wpdb->query("ALTER TABLE ".$wpdb->prefix."ib_campaign_step ADD CONSTRAINT `".$name."` FOREIGN KEY (`email_template_id`) REFERENCES `".$wpdb->prefix."ib_emails` (`email_id`) ON DELETE SET NULL ON UPDATE CASCADE");
          }

          $name = $wpdb->prefix."ib_contact_field_field_id";
          if (!$this->constraintExists($name)){
            $wpdb->query("ALTER TABLE ".$wpdb->prefix."ib_contact_field ADD CONSTRAINT `".$name."` FOREIGN KEY (`field_id`) REFERENCES `".$wpdb->prefix."ib_lead_fields` (`field_id`) ON DELETE CASCADE ON UPDATE CASCADE");
          }

          $name = $wpdb->prefix."ib_post_keyword_keyword_id";
          if (!$this->constraintExists($name)){
            $wpdb->query("ALTER TABLE ".$wpdb->prefix."ib_post_keyword ADD CONSTRAINT `".$name."` FOREIGN KEY (`keyword_id`) REFERENCES `".$wpdb->prefix."ib_keywords` (`keyword_id`) ON DELETE CASCADE ON UPDATE CASCADE");
          }

          $name = "ib_campaign_step_id";
          if (!$this->constraintExists($name)){
            $wpdb->query("CONSTRAINT `".$name."` FOREIGN KEY (`campaign_id`) REFERENCES `" . $wpdb->prefix . "ib_campaign` (`id`) ON DELETE SET NULL ON UPDATE CASCADE");
          }


        case ((double) $current_db_version < 2.6) :
          $wpdb->query("ALTER TABLE `" . $wpdb->prefix . "ib_leads` CHANGE COLUMN `campaign_id` `campaign_id` INT(11) NULL DEFAULT NULL AFTER `lead_score`;");

        }

        return $changes;
    }

    private function defaultTemplateValues() {
        $default = array(
            'info' => array(
                'send_to' => "{{email}}, "
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
                'linked_in' => "on",
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
                'linked_in' => "on",
                'google_plus' => "on"
            )
        );
        return $default;
    }

    public function createDefaultEmailTemplate($old_settings = null) {
        global $wpdb;
        $default = $this->defaultTemplateValues();
        unset($default['info']);
        // if old settings
        if ($old_settings) {
            // top bar
            $default['top_bar'] = array(
                'container_visible' => (@$old_settings->no_social_container) ? "" : "on",
                'background' => $old_settings->share_background,
                'padding_left' => $old_settings->share_padding_left,
                'padding_right' => $old_settings->share_padding_right,
                'padding_top' => $old_settings->share_padding_top,
                'padding_bottom' => $old_settings->share_padding_bottom);
            // top social icons
            $default['top_bar_social_icons']['color'] = $old_settings->share_color;
            $default['top_bar_social_icons']['facebook'] = (@$old_settings->facebook_link) ? "on" : "";
            $default['top_bar_social_icons']['twitter'] = (@$old_settings->twitter_link) ? "on" : "";
            $default['top_bar_social_icons']['linked_in'] = (@$old_settings->linkedin_link) ? "on" : "";
            $default['top_bar_social_icons']['google_plus'] = (@$old_settings->google_link) ? "on" : "";
            // header
            $default['header'] = array(
                'container_visible' => (@$old_settings->no_logo_container) ? "" : "on",
                'login_image_align' => $old_settings->logo_image_align,
                'background' => $old_settings->logo_background,
                'padding_top' => $old_settings->logo_padding_top,
                'padding_bottom' => $old_settings->logo_padding_bottom,
                'padding_left' => $old_settings->logo_padding_left,
                'padding_right' => $old_settings->logo_padding_right
            );
            //logo image
            if (@$old_settings->logo_image)
                $default['header']['logo_image'] = $old_settings->logo_image;
            //banner image
            if (@$old_settings->banner_image)
                $default['banner_image']['image'] = $old_settings->banner_image;
            if (@$old_settings->banner_padding_top)
                $default['banner_image']['banner_image']['margin_top'] = $old_settings->banner_padding_top;
            if (@$old_settings->banner_padding_bottom)
                $default['banner_image']['banner_image']['margin_bottom'] = $old_settings->banner_padding_bottom;
            $default['body'] = array(
                'color' => $old_settings->body_color,
                'background' => $old_settings->body_background,
                'padding_top' => $old_settings->body_padding_top,
                'padding_bottom' => $old_settings->body_padding_bottom,
                'padding_left' => $old_settings->body_padding_left,
                'padding_right' => $old_settings->body_padding_right,
            );
            // footer
            $default['footer']['contact_email'] = (@$old_settings->contact_email) ? $old_settings->contact_email : get_bloginfo('admin_email');
            $default['footer']['contact_custom'] = get_bloginfo('name');
            $default['footer']['contact_phone'] = (@$old_settings->contact_phone) ? $old_settings->contact_phone : "";
            $default['footer']['background'] = $old_settings->footer_background;
            $default['footer']['color'] = $old_settings->footer_color;
            // social icons
            $default['footer_social_icons']['container_visible'] = (@$old_settings->no_social_footer) ? "" : "1";
            $default['footer_social_icons']['color'] = $old_settings->footer_color;
            $default['footer_social_icons']['facebook'] = (@$old_settings->facebook_link) ? "on" : "";
            $default['footer_social_icons']['twitter'] = (@$old_settings->twitter_link) ? "on" : "";
            $default['footer_social_icons']['linked_in'] = (@$old_settings->linkedin_link) ? "on" : "";
            $default['footer_social_icons']['google_plus'] = (@$old_settings->google_link) ? "on" : "";
        }
        // save template
        $table = $wpdb->prefix . "ib_email_templates";
        $query = "INSERT INTO `{$table}` (`name`, `description`, `send_to`, `settings`, `created_at`) VALUES('Default Template', 'Default Template created by InboundBrew.', '{{email}},', '" . serialize($default) . "', NOW())";
        $wpdb->query($query);
        return $wpdb->insert_id;
    }

}
