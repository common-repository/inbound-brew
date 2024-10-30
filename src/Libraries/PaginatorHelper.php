<?php
/**
* Class is use to help create elements for pagination.
*/
namespace InboundBrew\Libraries;

class PaginatorHelper {
	var $order_var = "order"; // name of variable in url to determine order
	var $dir_var = "direction"; // name of variable in url to determine direction
	var $wp_page_var = "page";  // name of variable in url to determine current WP admin page in url
	var $useAjax = false; // should paginate using ajax
	var $sortingClass = "ib-sorting-header";
    public function __construct()
    {
	
    }

	public function useAjax($options = array()){
		$jsSelector = $options['selector'];
		$wpAction = $options['wp_action'];
		echo '<script>
			$(document).ready(function(){
				$("#'.$jsSelector.' a.'.$this->sortingClass.'").each(function(){
					var $link = $(this);
					$link.click(function(e){
						e.preventDefault();
						var url = $(this).attr("href")+"&action='.$wpAction.'";
						$.get(url,function(response){
							$("#'.$jsSelector.'").html(response);
						});
					});
				});
			});
		</script>';
	}
	
	/**
	* create table headers for sorting using Eloquent Paginator object
	* @param object $paginator Eloquent/Paginator Object
	* @param string $headers array of values to create headers.
	* @param string $wp_page value for the current admin page
	* @return string HTML string containing TH objects with html links to sort data.
	* @author Rico Celis
	* @access public
	*/
	public function sortingHeaders($paginator,$headers){
		$str = "";
		foreach($headers as $header){
			// create TH element and any attributes passed
			$str.= "<th";
			if(@$header['attributes']){
				foreach($header['attributes'] as $att => $val){
					$str.= " {$att}=\"{$val}\"";
				}
			}
			// get url
			$cVars = $_GET;
			$vars = array();
			$dir = "ASC";
			foreach($cVars as $index=>$value){
				if($index == $this->order_var){ // if order field
					if($header['field'] == $value){ // if currently sorting on this field
						$dir = ($cVars[$this->dir_var] == "ASC")? "DESC":"ASC"; // toggle direction
						$linkClass = "sort-".$dir;
					}
				}
			}
			$cVars[$this->order_var] = $header['field'];
			$cVars[$this->dir_var] = $dir;
			$arr = explode("?",$_SERVER['REQUEST_URI']);
			$url = "".$arr[0];
			$prefix = "?";
			foreach($cVars as $index=>$value){
				$url.="{$prefix}{$index}={$value}";
				if($prefix == "?") $prefix = "&";
			}
			$link = "<a href=\"{$url}\" class=\"{$linkClass} {$this->sortingClass}\">{$header['display']}</a>";
			$str.=">{$link}</th>";
		}
		return $str;
	}
}