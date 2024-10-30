<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 9/4/15
 * Time: 1:19 PM
 */

namespace InboundBrew\Modules\Sitemap\Libraries;


/**
 * Class SitemapData
 * @package InboundBrew\Modules\Sitemap\Libraries
 */
class SitemapData {

    /**
     * @var array
     */
    public $data = array();

    /**
     * @param $name
     * @return value of specified key of data array
     */
    public function __get($name)
    {
        return $this->data[$name];
    }


    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
    
	public static function saveSettings($data){
		$sitemap = json_encode($data);
		update_option('ib_sitemap_default_settings', $sitemap);
		return true;
	}

    /**
     *
     */
    public function loadData()
    {
        $this->options = self::getSitemapSettings();
        $this->post_types = self::getCustomPostTypes();
        $this->taxonomies = self::getCustomTaxonomies();
        $this->ping = self::getPingData();
    }

    /**
     * @return array of custom_post_type objects
     */
    public static function getCustomPostTypes()
    {
        $args = array('public' => true,'_builtin' => false);
        $output = 'objects'; // names or objects
        $post_types = get_post_types( $args, $output );
        return $post_types;
    }

    /**
     * @return object
     */
    public static function getSitemapSettings()
    {
        return json_decode(get_option('ib_sitemap_default_settings'));
    }


    /**
     * @return array of taxonomy objects
     */
    public static function getCustomTaxonomies()
    {
        $args = array(
            'public'   => true,
            '_builtin' => false
        );
        $output = 'objects';
        $taxonomies = get_taxonomies( $args, $output );
        return $taxonomies;
    }

    /**
     * @param array $result
     */
    public function setPingData(array $result)
    {
        $result['duration'] = $result['end'] - $result['start'];
        $this->data['result'][] = $result;
    }

    public function savePingResults()
    {
        update_option('ib_ping_data',json_encode($this->data['result']));
    }

    public static function getPingData()
    {
        return json_decode(get_option('ib_ping_data'));
    }
}