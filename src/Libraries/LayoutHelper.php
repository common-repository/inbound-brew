<?php
/**
 * User: Rico Celis
 * Date: 10/13/15
 * Time: 11:15 AM
 * Class helps with html elements in views.
 */

namespace InboundBrew\Libraries;

class LayoutHelper {
	var $modules = array(
		'redirects' => "301 Redirects module is turned off. Please go to <a href='admin.php?page=ib-admin-settings'>General Settings</a> to activate it.",
		'sitemap' => "Sitemap module is turned off. Please go to <a href='admin.php?page=ib-admin-settings'>General Settings</a> to activate it.",
		'robots' => "Robots module is turned off. Please go to <a href='admin.php?page=ib-admin-settings'>General Settings</a> to activate it."
	);
    public function __construct(){
	    
	}
	
	/**
	* creates a visual represntation of a date
	*
	* @param string $date mysql_time stamp
	* @param array $options options to set calendar
	* @return string mysql time.
	* @author Rico Celis
	* @access public
	*/
	function calDay($date,$options = array())
	{
		$_defaults = array(
			'cal_color' => null, // change color from color used in CSS
			'size' => "small", // size of calendar
			'add_year' => false, // to display year or not
			'style' => "" // additional styles.
		);
		$options = array_merge($_defaults,$options);
		$style = ($options['cal_color'])? "style='background-color:{$cal_color}'" : '';
		$dayStyle = ($options['cal_color'])? "style='border:1px solid {$cal_color}'" : '';
		$stamp = strtotime($date);
		$month = date('M',$stamp);
		$day = date('d',$stamp);
		$class = ($options['size'])? "ib_cal-day-" . $options['size'] : "ib_cal-day";
		if($options['add_year']){
			$year = "<div class='year'>".date('Y',$stamp)."</div>";
		}else{
			$year = "";
		}
		if($options['style']){
			$s = "style='{$options['style']}'";
		}else{
			$s = "";
		}
		return "<div class='".$class."' ".$s."><div class='month' {$style}>{$month}</div><div class='day' {$dayStyle}>{$day}</div>{$year}</div>";
	}
	
	/**
	* creates an img tag loading an image
	*
	* @param string $icon name of the icon
	* @param array $attributes any attributes that need to be assigned to the image
	* @return string mysql time.
	* @author Rico Celis
	* @access public
	*/
	function icon($icon,$attributes = array()){
		$str = "<img src=\"". BREW_PLUGIN_IMAGES_URL ."/icons/icon_{$icon}.png\" ";
		if($attributes){
			foreach($attributes as $attr=>$value){
				$str.="{$attr}=\"{$value}\"";
			}
		}
		$str.=">";
		return $str;
	}
	
	public function element($element,$data = array()){
		// view
        extract($data);
        ob_start();
        include $element . ".php";
        return ob_get_clean();
	}
	
	public function inactiveModule($module){
		return "<div class=\"ib-inactive-module\"><span class=\"fa fa-ban\"> </span> ". $this->modules[$module]. "</div>";
	}
	
	/**
	* add line breaks to a string (<br> )
	*
	* @author Rico Celis
	* @param string $string string to fix
	* @return string with fix references
	* @access public
	*/
	function addLineBreaks($string)
	{
		return str_replace("\n","<br>",$string);
	}
	
	function utf8ize($d) {
	    if (is_array($d)) {
	        foreach ($d as $k => $v) {
	            $d[$k] = $this->utf8ize($v);
	        }
	    } else if (is_string ($d)) {
	        return utf8_encode($d);
	    }
	    return $d;
	}
}