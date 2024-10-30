<?php 
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();
// delete Inbound Brew Options
$ib_options = array("ib_email_settings","ib_smtp_options","ib_form_submit","ib_lead_fields",
"ib_db_version","ib_data_version","ib_settings_version","ib_country_import","ib_state_import","ib_field_import",
"ib_social_share_widget_settings","ib_social_share_widget_options","ib_cta_defaults","ib_sitemap_default_settings","ib_ping_data","ib_default_layout","ib_dynamic_navigation",
"ib_active_modules","ib_redirect_settings","ib_default_lead_view_settings","ib_user_default_lead_view");

foreach($ib_options as $option){
	delete_option($option);
}

// delete Inbound Brew Database Tables
$ib_tables = array("ib_contact_field","ib_countries","ib_ctas","ib_cta_post_linkages","ib_downloads","ib_email_field","ib_email_templates",
"ib_keywords","ib_leads","ib_lead_data","ib_lead_fields","ib_lead_history","ib_post_keyword","ib_redirects","ib_settings",
"ib_social_network_post_records","ib_social_network_post_settings","ib_social_network_post_setting_accounts","ib_social_network_accounts",
"ib_states","ib_ctas","ib_cta_post_linkages","ib_cta_templates","ib_lead_views","ib_emails");

global $wpdb;
$wpdb->query("SET FOREIGN_KEY_CHECKS = 0");
foreach($ib_tables as $table){
	$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}{$table}`");
}
$wpdb->query("SET FOREIGN_KEY_CHECKS = 1;");

// delete custom post items
$ib_post_types = array("ib-call-to-action","ib-contact-form","ib-landing-page");
foreach($ib_post_types as $post_type){
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
            'post_type' => $post_type,
        );
	$posts = get_posts($args);
	if(!empty($posts)){
		foreach($posts as $post){
			wp_delete_post($post->ID);
		}
	}
}