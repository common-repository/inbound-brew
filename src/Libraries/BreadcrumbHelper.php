<?php 
	namespace InboundBrew\Libraries;
	class BreadcrumbHelper {
	var $divider = "&nbsp;&nbsp;<span class=\"fa fa-chevron-right\"></span>&nbsp;&nbsp;";
	var $path_array;
	var $isMobile = false;
	
	# constructor function
	function __construct()
	{
		$this->path_array = array();
	}
	
	# add new item to path
	# $text = text to use in link
	# $url = link url
	function add($text = null, $url = null)
	{
		# if user is passing an array instead of text
		if(is_array($text))
		{
			# loop through array elements
			foreach($text as $breadcrumb)
			{
				# if element is array
				# first element is a text and second is link
				if(is_array($breadcrumb))
				{
					# first index is text second index is link
					$d = array(
						'text' => @$breadcrumb[0],
						'url' => @$breadcrumb[1]);
				}else{
					$d = array('text' => $breadcrumb);
				}
				$this->path_array[] = $d;
			}
		}else{
			# add item to path array
			$d = array();
			$d['text'] = $text;
			if($url) $d['url'] = $url;
			$this->path_array[] = $d;
		}
	}
	
	# print breadcrumb path
	# ---------------------
	function printPath()
	{
		#intial
		$path_string = "<div class='ib_breadcrumbs'>";
		$totalItems = count($this->path_array);
		if($totalItems > 1){
			for($i = 0; $i < $totalItems; $i++)
			{
				$values = $this->path_array[$i];
				# if linkable item
				if(!empty($values['url'])){
					# add link to path string
					$path_string .= "<a href=\"{$values['url']}\">{$values['text']}</a>";
				}else{
					# add only display text
					$path_string .= $values['text'];
				}
				# add divider only if item is not last
				if($i != $totalItems - 1) $path_string .= "<span>{$this->divider}</span>";
			}
		}else{ // 1 item
			$path_string .= "<h2>".$this->path_array[0]['text']."</h2>";
		}
		# return breadcrumb path html
		return $path_string . "</div>";
	}
}?>