<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 9/4/15
 * Time: 2:40 PM
 */

namespace InboundBrew\Modules\Sitemap\Libraries;

use WP_Query;

class SitemapBuilder {

    const VIEW_PATH = 'Sitemap/views/';

    private $sitemap_data;

    private $xml = '';

    private $result = array();

    function __construct(SitemapData $data)
    {
        $data->loadData();
        $this->sitemap_data = $data;
    }

    public function buildXml()
    {
        $this->xml .= '<?xml version="1.0" encoding="UTF-8"?>';
        $this->xml .= '<?xml-stylesheet type="text/xsl" href="' . BREW_MODULES_URL . self::VIEW_PATH . 'sitemap.xsl"?>';
        $this->xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $args = array(
            'sort_order' => 'ASC',
            'sort_column' => 'post_title',
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
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages_array = get_pages($args);
        // iterate through all pages to remove ones that are not usable
        foreach ($pages_array as $key => $value) {
            if ($value->post_status == 'private' || $value->post_status == 'trash') {
                unset($pages_array[$key]);
            }
        }

        $args = array(
            'category' => '',
            'category_name' => '',
            'orderby' => 'date',
            'order' => 'DESC',
            'include' => '',
            'exclude' => '',
            'meta_key' => '',
            'meta_value' => '',
            'post_type' => 'post',
            'post_mime_type' => '',
            'post_parent' => '',
            'author' => '',
            'post_status' => 'publish',
            'suppress_filters' => true,
            'numberposts' => -1
        );
        $posts_array = get_posts($args);
        // iterate through all pages to remove ones that are not usable
        foreach ($posts_array as $key => $value) {
            if ($value->post_status == 'private' || $value->post_status == 'trash') {
                unset($posts_array[$key]);
            }
        }

        foreach ($this->sitemap_data->options as $option_type => $value) {
            if ($option_type == 'custom_taxonomy') {
                foreach ($this->sitemap_data->options->custom_taxonomy as $taxonomy=>$page) {
                    $query = new WP_Query(array('post_type' => 'post','tax_query' => array('taxonomy' => $taxonomy),'post_status' => array('publish')));
                    $this->result = array_merge($query->posts, $this->result);
                }
            }

            if ($option_type == 'custom_post_types') {

                foreach ($this->sitemap_data->options->custom_post_types as $post_type=>$page) {
                    $query = new WP_Query(array('post_type' => $post_type, 'post_status' => array('publish')));
                    $this->result = array_merge($query->posts, $this->result);
                }
            }
        }


        $result = array_merge($pages_array,$posts_array,$this->result);

        foreach ($result as $post) {
            $this->xml .= '<url><loc>'.get_permalink($post->ID).'</loc><lastmod>'.date('Y-m-d', strtotime($post->post_modified)).'</lastmod></url>';
        }
        $this->xml .= '</urlset>';
        return $this->xml;
    }

}