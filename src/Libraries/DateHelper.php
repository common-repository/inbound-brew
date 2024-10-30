<?php
/**
 * User: Rico Celis
 * Date: 10/13/15
 * Time: 11:15 AM
 * Class helps with date and time handling.
 */

namespace InboundBrew\Libraries;

class DateHelper {
	private $_offset; // timezone offset (from wordpress)
	
    public function __construct()
    {
	    $this->_offset = get_option('gmt_offset');
	}
	
	/**
	* need to convert time array to mysql time
	*
	* @param array $time_array (hours,minutes,meridian)
	* @param boolean $use_offset if time needs to be updated based on timezone offset.
	* @return string mysql time.
	* @author Rico Celis
	* @access public
	*/
	public function time_humanArrayToMysql($time_array,$use_offset = false){
		$human_time = $time_array['hours'].":".$time_array['minutes']." ".$time_array['meridian'];
		$mysql_time = date("H:i",strtotime($human_time));
		return $mysql_time;
	}
	
	/**
	* convert mysql time to human array
	*
	* @param string $mysqltime (military format)
	* @param boolean $use_offset if time needs to be updated based on timezone offset.
	* @return array indexed array (hours,minutes,meridian)
	* @author Rico Celis
	* @access public
	*/
	public function time_mysqlToHumanArray($mysql_time,$use_offset = false){
		$arr = explode(":",date("h:i:a",strtotime($mysql_time)));
		return array(
			'hours' => $arr[0],
			'minutes' => $arr[1],
			'meridian' => $arr[2]
		);
	}
	
	/**
	* convert human date to mysql date
	*
	* @param string $human_date (mm/dd/YYYY)
	* @return string mysql date YYYY-mm-dd
	* @author Rico Celis
	* @access public
	*/
	public function date_humanToMysql($human_date){
		return date('Y-m-d',strtotime($human_date));
	}
	
	/**
	* convert  mysql time stamp to human string.
	*
	* @param string $format format to use in php date method.
	* @param string $mysql_stamp YYYY-mm-dd HH:mm:ss
	* @return string mysql date m/d/Y hh:mm AM
	* @author Rico Celis
	* @access public
	*/
	public function date_MysqlStampToHuman($format,$mysql_stamp){
		return date($format,strtotime($mysql_stamp));
	}
	
	/**
	* convert a date using a format
	*
	* @param string $format php date format
	* @param string $date date string to convert.
	* @param boolean $convert_from_gmt return date using gmt offset
	* @retun string formated date.
	*/
	public function format($format,$date,$convert_from_gmt = false){
		// convert date using offset.
		if($convert_from_gmt)return (get_date_from_gmt($date,$format));
		// just format date.
		return date($format,strtotime($date));
	}
	
	/**
	* takes a date string and converts it,based on format to GMT time
	*
	* @param string $format php date format
	* @param string $date date string to convert.
	* @retun string formated date in GMT.
	*/
	public function toGMT($format,$date){
		$gmt_date = date($format,strtotime($date) - (get_option( 'gmt_offset' ) * 3600));
		return $gmt_date;
	}
}