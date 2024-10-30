<?php

/**
 * Created by sean.carrico.
 * User: sean
 * Date: 3/25/15
 * Time: 11:24 AM
 */

namespace InboundBrew\Modules\Core\Controllers;

class PublicUI {

    //constructor for MyPlugin object
    function __construct() {
        add_action('wp_head', array($this, 'addMeta'));
        add_action('wp_enqueue_scripts', array($this, 'addPublicStyles'));
        add_action('wp_enqueue_scripts', array($this, 'addPublicScripts'));

        add_action('wp', array($this, 'landingpage_remove_autop'));
    }

    public function landingpage_remove_autop() {
        global $wp_filter;
        global $wp;
        if (@$wp->query_vars["post_type"] == 'landingpage') {
            remove_filter('the_content', 'ib-landing-page');
        }
    }

    public function addMeta() {
        $options = get_option('ib_sitemeta_options');
        //$ib_site_keywords = get_option('ib_site_keywords');
        echo "<meta name='description' content='" . $options['site_description'] . "'>\n";
        $google_meta = get_option('ib_add_google_content');
        echo "<meta name='google-site-varification' content='" . $google_meta . "'>\n";
        $bing_meta = get_option('ib_add_bing_content');
        echo "<meta name='msvalidate.01' content='" . $bing_meta . "'>\n";

        if (get_post_meta(get_the_ID(), 'ib_my_meta_value_key', true)) {
            $ib_site_keywords = get_post_meta(get_the_ID(), 'ib_my_meta_value_key');
            echo "<meta name='keywords' content='" . $ib_site_keywords[0] . "'>\n";
        }
    }

    public function addPublicScripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_script('jquery-ui-autocomplete', array('jquery'));
        wp_enqueue_script('jquery-ui-accordion', array('jquery'));
        wp_enqueue_script('jquery-ui-tabs', array('jquery'));
        wp_enqueue_script('jquery-ui-datepicker', array('jquery'));
        wp_enqueue_script('jquery-ui-droppable', array('jquery'));
        wp_enqueue_script('jquery-ui-progressbar', array('jquery'));

        /* wp_enqueue_script('jquery','//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js');
          wp_enqueue_script('ib-jquery-ui-js', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js'); */
        wp_enqueue_script('ib-core-js', BREW_MODULES_URL . 'Core/assets/js/core.js', array('jquery'), BREW_ASSET_VERSION);
        wp_enqueue_script('jquery-cookie-js', BREW_MODULES_URL . 'Core/assets/third-party/jquery-cookie/jquery.cookie.js', array('jquery'), BREW_ASSET_VERSION);
        if (!is_admin()) {
            wp_enqueue_script('ib-track-events', BREW_MODULES_URL . 'Core/assets/js/ib-track.js', array('jquery'), BREW_ASSET_VERSION);
        }
        if (is_admin()) {
            wp_deregister_script('ib-track-events');
        }
    }

    public function addPublicStyles() {
        wp_enqueue_style('ib-jquery-ui', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css', array(), BREW_ASSET_VERSION);
        wp_enqueue_style('ib-core', BREW_MODULES_URL . 'Core/assets/css/core.css', array(), BREW_ASSET_VERSION);
    }

}
