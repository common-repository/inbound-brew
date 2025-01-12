<?php 
	namespace InboundBrew\Libraries;
	class FontAwesomeHelper {
		
		var $font = "fontawesome/fontawesome-webfont.ttf";
		var $scssFile = "fontawesome/_variables.scss";
		var $outputSizes = array('16','32','48','48','64','80');
		var $icons = array(
			"glass" => array('code'=>'&#xf000;'),
			"music" => array('code'=>'&#xf001;'),
			"search" => array('code'=>'&#xf002;'),
			"envelope-o" => array('code'=>'&#xf003;'),
			"heart" => array('code'=>'&#xf004;'),
			"star" => array('code'=>'&#xf005;'),
			"star-o" => array('code'=>'&#xf006;'),
			"user" => array('code'=>'&#xf007;'),
			"film" => array('code'=>'&#xf008;'),
			"th-large" => array('code'=>'&#xf009;'),
			"th" => array('code'=>'&#xf00a;'),
			"th-list" => array('code'=>'&#xf00b;'),
			"check" => array('code'=>'&#xf00c;'),
			"times" => array('code'=>'&#xf00d;'),
			"search-plus" => array('code'=>'&#xf00e;'),
			"search-minus" => array('code'=>'&#xf010;'),
			"power-off" => array('code'=>'&#xf011;'),
			"signal" => array('code'=>'&#xf012;'),
			"cog" => array('code'=>'&#xf013;'),
			"trash-o" => array('code'=>'&#xf014;'),
			"home" => array('code'=>'&#xf015;'),
			"file-o" => array('code'=>'&#xf016;'),
			"clock-o" => array('code'=>'&#xf017;'),
			"road" => array('code'=>'&#xf018;'),
			"download" => array('code'=>'&#xf019;'),
			"arrow-circle-o-down" => array('code'=>'&#xf01a;'),
			"arrow-circle-o-up" => array('code'=>'&#xf01b;'),
			"inbox" => array('code'=>'&#xf01c;'),
			"play-circle-o" => array('code'=>'&#xf01d;'),
			"repeat" => array('code'=>'&#xf01e;'),
			"refresh" => array('code'=>'&#xf021;'),
			"list-alt" => array('code'=>'&#xf022;'),
			"lock" => array('code'=>'&#xf023;'),
			"flag" => array('code'=>'&#xf024;'),
			"headphones" => array('code'=>'&#xf025;'),
			"volume-off" => array('code'=>'&#xf026;'),
			"volume-down" => array('code'=>'&#xf027;'),
			"volume-up" => array('code'=>'&#xf028;'),
			"qrcode" => array('code'=>'&#xf029;'),
			"barcode" => array('code'=>'&#xf02a;'),
			"tag" => array('code'=>'&#xf02b;'),
			"tags" => array('code'=>'&#xf02c;'),
			"book" => array('code'=>'&#xf02d;'),
			"bookmark" => array('code'=>'&#xf02e;'),
			"print" => array('code'=>'&#xf02f;'),
			"camera" => array('code'=>'&#xf030;'),
			"font" => array('code'=>'&#xf031;'),
			"bold" => array('code'=>'&#xf032;'),
			"italic" => array('code'=>'&#xf033;'),
			"text-height" => array('code'=>'&#xf034;'),
			"text-width" => array('code'=>'&#xf035;'),
			"align-left" => array('code'=>'&#xf036;'),
			"align-center" => array('code'=>'&#xf037;'),
			"align-right" => array('code'=>'&#xf038;'),
			"align-justify" => array('code'=>'&#xf039;'),
			"list" => array('code'=>'&#xf03a;'),
			"outdent" => array('code'=>'&#xf03b;'),
			"indent" => array('code'=>'&#xf03c;'),
			"video-camera" => array('code'=>'&#xf03d;'),
			"picture-o" => array('code'=>'&#xf03e;'),
			"pencil" => array('code'=>'&#xf040;'),
			"map-marker" => array('code'=>'&#xf041;'),
			"adjust" => array('code'=>'&#xf042;'),
			"tint" => array('code'=>'&#xf043;'),
			"pencil-square-o" => array('code'=>'&#xf044;'),
			"share-square-o" => array('code'=>'&#xf045;'),
			"check-square-o" => array('code'=>'&#xf046;'),
			"arrows" => array('code'=>'&#xf047;'),
			"step-backward" => array('code'=>'&#xf048;'),
			"fast-backward" => array('code'=>'&#xf049;'),
			"backward" => array('code'=>'&#xf04a;'),
			"play" => array('code'=>'&#xf04b;'),
			"pause" => array('code'=>'&#xf04c;'),
			"stop" => array('code'=>'&#xf04d;'),
			"forward" => array('code'=>'&#xf04e;'),
			"fast-forward" => array('code'=>'&#xf050;'),
			"step-forward" => array('code'=>'&#xf051;'),
			"eject" => array('code'=>'&#xf052;'),
			"chevron-left" => array('code'=>'&#xf053;'),
			"chevron-right" => array('code'=>'&#xf054;'),
			"plus-circle" => array('code'=>'&#xf055;'),
			"minus-circle" => array('code'=>'&#xf056;'),
			"times-circle" => array('code'=>'&#xf057;'),
			"check-circle" => array('code'=>'&#xf058;'),
			"question-circle" => array('code'=>'&#xf059;'),
			"info-circle" => array('code'=>'&#xf05a;'),
			"crosshairs" => array('code'=>'&#xf05b;'),
			"times-circle-o" => array('code'=>'&#xf05c;'),
			"check-circle-o" => array('code'=>'&#xf05d;'),
			"ban" => array('code'=>'&#xf05e;'),
			"arrow-left" => array('code'=>'&#xf060;'),
			"arrow-right" => array('code'=>'&#xf061;'),
			"arrow-up" => array('code'=>'&#xf062;'),
			"arrow-down" => array('code'=>'&#xf063;'),
			"share" => array('code'=>'&#xf064;'),
			"expand" => array('code'=>'&#xf065;'),
			"compress" => array('code'=>'&#xf066;'),
			"plus" => array('code'=>'&#xf067;'),
			"minus" => array('code'=>'&#xf068;'),
			"asterisk" => array('code'=>'&#xf069;'),
			"exclamation-circle" => array('code'=>'&#xf06a;'),
			"gift" => array('code'=>'&#xf06b;'),
			"leaf" => array('code'=>'&#xf06c;'),
			"fire" => array('code'=>'&#xf06d;'),
			"eye" => array('code'=>'&#xf06e;'),
			"eye-slash" => array('code'=>'&#xf070;'),
			"exclamation-triangle" => array('code'=>'&#xf071;'),
			"plane" => array('code'=>'&#xf072;'),
			"calendar" => array('code'=>'&#xf073;'),
			"random" => array('code'=>'&#xf074;'),
			"comment" => array('code'=>'&#xf075;'),
			"magnet" => array('code'=>'&#xf076;'),
			"chevron-up" => array('code'=>'&#xf077;'),
			"chevron-down" => array('code'=>'&#xf078;'),
			"retweet" => array('code'=>'&#xf079;'),
			"shopping-cart" => array('code'=>'&#xf07a;'),
			"folder" => array('code'=>'&#xf07b;'),
			"folder-open" => array('code'=>'&#xf07c;'),
			"arrows-v" => array('code'=>'&#xf07d;'),
			"arrows-h" => array('code'=>'&#xf07e;'),
			"bar-chart-o" => array('code'=>'&#xf080;'),
			"twitter-square" => array('code'=>'&#xf081;'),
			"facebook-square" => array('code'=>'&#xf082;'),
			"camera-retro" => array('code'=>'&#xf083;'),
			"key" => array('code'=>'&#xf084;'),
			"cogs" => array('code'=>'&#xf085;'),
			"comments" => array('code'=>'&#xf086;'),
			"thumbs-o-up" => array('code'=>'&#xf087;'),
			"thumbs-o-down" => array('code'=>'&#xf088;'),
			"star-half" => array('code'=>'&#xf089;'),
			"heart-o" => array('code'=>'&#xf08a;'),
			"sign-out" => array('code'=>'&#xf08b;'),
			"linkedin-square" => array('code'=>'&#xf08c;'),
			"thumb-tack" => array('code'=>'&#xf08d;'),
			"external-link" => array('code'=>'&#xf08e;'),
			"sign-in" => array('code'=>'&#xf090;'),
			"trophy" => array('code'=>'&#xf091;'),
			"github-square" => array('code'=>'&#xf092;'),
			"upload" => array('code'=>'&#xf093;'),
			"lemon-o" => array('code'=>'&#xf094;'),
			"phone" => array('code'=>'&#xf095;'),
			"square-o" => array('code'=>'&#xf096;'),
			"bookmark-o" => array('code'=>'&#xf097;'),
			"phone-square" => array('code'=>'&#xf098;'),
			"twitter" => array('code'=>'&#xf099;'),
			"facebook" => array('code'=>'&#xf09a;'),
			"github" => array('code'=>'&#xf09b;'),
			"unlock" => array('code'=>'&#xf09c;'),
			"credit-card" => array('code'=>'&#xf09d;'),
			"rss" => array('code'=>'&#xf09e;'),
			"hdd-o" => array('code'=>'&#xf0a0;'),
			"bullhorn" => array('code'=>'&#xf0a1;'),
			"bell" => array('code'=>'&#xf0f3;'),
			"certificate" => array('code'=>'&#xf0a3;'),
			"hand-o-right" => array('code'=>'&#xf0a4;'),
			"hand-o-left" => array('code'=>'&#xf0a5;'),
			"hand-o-up" => array('code'=>'&#xf0a6;'),
			"hand-o-down" => array('code'=>'&#xf0a7;'),
			"arrow-circle-left" => array('code'=>'&#xf0a8;'),
			"arrow-circle-right" => array('code'=>'&#xf0a9;'),
			"arrow-circle-up" => array('code'=>'&#xf0aa;'),
			"arrow-circle-down" => array('code'=>'&#xf0ab;'),
			"globe" => array('code'=>'&#xf0ac;'),
			"wrench" => array('code'=>'&#xf0ad;'),
			"tasks" => array('code'=>'&#xf0ae;'),
			"filter" => array('code'=>'&#xf0b0;'),
			"briefcase" => array('code'=>'&#xf0b1;'),
			"arrows-alt" => array('code'=>'&#xf0b2;'),
			"users" => array('code'=>'&#xf0c0;'),
			"link" => array('code'=>'&#xf0c1;'),
			"cloud" => array('code'=>'&#xf0c2;'),
			"flask" => array('code'=>'&#xf0c3;'),
			"scissors" => array('code'=>'&#xf0c4;'),
			"files-o" => array('code'=>'&#xf0c5;'),
			"paperclip" => array('code'=>'&#xf0c6;'),
			"floppy-o" => array('code'=>'&#xf0c7;'),
			"square" => array('code'=>'&#xf0c8;'),
			"bars" => array('code'=>'&#xf0c9;'),
			"list-ul" => array('code'=>'&#xf0ca;'),
			"list-ol" => array('code'=>'&#xf0cb;'),
			"strikethrough" => array('code'=>'&#xf0cc;'),
			"underline" => array('code'=>'&#xf0cd;'),
			"table" => array('code'=>'&#xf0ce;'),
			"magic" => array('code'=>'&#xf0d0;'),
			"truck" => array('code'=>'&#xf0d1;'),
			"pinterest" => array('code'=>'&#xf0d2;'),
			"pinterest-square" => array('code'=>'&#xf0d3;'),
			"google-plus-square" => array('code'=>'&#xf0d4;'),
			"google-plus" => array('code'=>'&#xf0d5;'),
			"money" => array('code'=>'&#xf0d6;'),
			"caret-down" => array('code'=>'&#xf0d7;'),
			"caret-up" => array('code'=>'&#xf0d8;'),
			"caret-left" => array('code'=>'&#xf0d9;'),
			"caret-right" => array('code'=>'&#xf0da;'),
			"columns" => array('code'=>'&#xf0db;'),
			"sort" => array('code'=>'&#xf0dc;'),
			"sort-asc" => array('code'=>'&#xf0dd;'),
			"sort-desc" => array('code'=>'&#xf0de;'),
			"envelope" => array('code'=>'&#xf0e0;'),
			"linkedin" => array('code'=>'&#xf0e1;'),
			"undo" => array('code'=>'&#xf0e2;'),
			"gavel" => array('code'=>'&#xf0e3;'),
			"tachometer" => array('code'=>'&#xf0e4;'),
			"line-chart" =>	array('code'=>'&#xf201'),
			"comment-o" => array('code'=>'&#xf0e5;'),
			"comments-o" => array('code'=>'&#xf0e6;'),
			"bolt" => array('code'=>'&#xf0e7;'),
			"sitemap" => array('code'=>'&#xf0e8;'),
			"umbrella" => array('code'=>'&#xf0e9;'),
			"clipboard" => array('code'=>'&#xf0ea;'),
			"lightbulb-o" => array('code'=>'&#xf0eb;'),
			"exchange" => array('code'=>'&#xf0ec;'),
			"cloud-download" => array('code'=>'&#xf0ed;'),
			"cloud-upload" => array('code'=>'&#xf0ee;'),
			"user-md" => array('code'=>'&#xf0f0;'),
			"stethoscope" => array('code'=>'&#xf0f1;'),
			"suitcase" => array('code'=>'&#xf0f2;'),
			"bell-o" => array('code'=>'&#xf0a2;'),
			"coffee" => array('code'=>'&#xf0f4;'),
			"cutlery" => array('code'=>'&#xf0f5;'),
			"file-text-o" => array('code'=>'&#xf0f6;'),
			"building-o" => array('code'=>'&#xf0f7;'),
			"hospital-o" => array('code'=>'&#xf0f8;'),
			"ambulance" => array('code'=>'&#xf0f9;'),
			"medkit" => array('code'=>'&#xf0fa;'),
			"fighter-jet" => array('code'=>'&#xf0fb;'),
			"beer" => array('code'=>'&#xf0fc;'),
			"h-square" => array('code'=>'&#xf0fd;'),
			"plus-square" => array('code'=>'&#xf0fe;'),
			"angle-double-left" => array('code'=>'&#xf100;'),
			"angle-double-right" => array('code'=>'&#xf101;'),
			"angle-double-up" => array('code'=>'&#xf102;'),
			"angle-double-down" => array('code'=>'&#xf103;'),
			"angle-left" => array('code'=>'&#xf104;'),
			"angle-right" => array('code'=>'&#xf105;'),
			"angle-up" => array('code'=>'&#xf106;'),
			"angle-down" => array('code'=>'&#xf107;'),
			"desktop" => array('code'=>'&#xf108;'),
			"laptop" => array('code'=>'&#xf109;'),
			"tablet" => array('code'=>'&#xf10a;'),
			"mobile" => array('code'=>'&#xf10b;'),
			"circle-o" => array('code'=>'&#xf10c;'),
			"quote-left" => array('code'=>'&#xf10d;'),
			"quote-right" => array('code'=>'&#xf10e;'),
			"spinner" => array('code'=>'&#xf110;'),
			"circle" => array('code'=>'&#xf111;'),
			"reply" => array('code'=>'&#xf112;'),
			"github-alt" => array('code'=>'&#xf113;'),
			"folder-o" => array('code'=>'&#xf114;'),
			"folder-open-o" => array('code'=>'&#xf115;'),
			"smile-o" => array('code'=>'&#xf118;'),
			"frown-o" => array('code'=>'&#xf119;'),
			"meh-o" => array('code'=>'&#xf11a;'),
			"gamepad" => array('code'=>'&#xf11b;'),
			"keyboard-o" => array('code'=>'&#xf11c;'),
			"flag-o" => array('code'=>'&#xf11d;'),
			"flag-checkered" => array('code'=>'&#xf11e;'),
			"terminal" => array('code'=>'&#xf120;'),
			"code" => array('code'=>'&#xf121;'),
			"reply-all" => array('code'=>'&#xf122;'),
			"mail-reply-all" => array('code'=>'&#xf122;'),
			"star-half-o" => array('code'=>'&#xf123;'),
			"location-arrow" => array('code'=>'&#xf124;'),
			"crop" => array('code'=>'&#xf125;'),
			"code-fork" => array('code'=>'&#xf126;'),
			"chain-broken" => array('code'=>'&#xf127;'),
			"question" => array('code'=>'&#xf128;'),
			"info" => array('code'=>'&#xf129;'),
			"exclamation" => array('code'=>'&#xf12a;'),
			"superscript" => array('code'=>'&#xf12b;'),
			"subscript" => array('code'=>'&#xf12c;'),
			"eraser" => array('code'=>'&#xf12d;'),
			"puzzle-piece" => array('code'=>'&#xf12e;'),
			"microphone" => array('code'=>'&#xf130;'),
			"microphone-slash" => array('code'=>'&#xf131;'),
			"shield" => array('code'=>'&#xf132;'),
			"calendar-o" => array('code'=>'&#xf133;'),
			"fire-extinguisher" => array('code'=>'&#xf134;'),
			"rocket" => array('code'=>'&#xf135;'),
			"maxcdn" => array('code'=>'&#xf136;'),
			"chevron-circle-left" => array('code'=>'&#xf137;'),
			"chevron-circle-right" => array('code'=>'&#xf138;'),
			"chevron-circle-up" => array('code'=>'&#xf139;'),
			"chevron-circle-down" => array('code'=>'&#xf13a;'),
			"html5" => array('code'=>'&#xf13b;'),
			"css3" => array('code'=>'&#xf13c;'),
			"anchor" => array('code'=>'&#xf13d;'),
			"unlock-alt" => array('code'=>'&#xf13e;'),
			"bullseye" => array('code'=>'&#xf140;'),
			"ellipsis-h" => array('code'=>'&#xf141;'),
			"ellipsis-v" => array('code'=>'&#xf142;'),
			"rss-square" => array('code'=>'&#xf143;'),
			"play-circle" => array('code'=>'&#xf144;'),
			"ticket" => array('code'=>'&#xf145;'),
			"minus-square" => array('code'=>'&#xf146;'),
			"minus-square-o" => array('code'=>'&#xf147;'),
			"level-up" => array('code'=>'&#xf148;'),
			"level-down" => array('code'=>'&#xf149;'),
			"check-square" => array('code'=>'&#xf14a;'),
			"pencil-square" => array('code'=>'&#xf14b;'),
			"external-link-square" => array('code'=>'&#xf14c;'),
			"share-square" => array('code'=>'&#xf14d;'),
			"compass" => array('code'=>'&#xf14e;'),
			"caret-square-o-down" => array('code'=>'&#xf150;'),
			"caret-square-o-up" => array('code'=>'&#xf151;'),
			"caret-square-o-right" => array('code'=>'&#xf152;'),
			"eur" => array('code'=>'&#xf153;'),
			"gbp" => array('code'=>'&#xf154;'),
			"usd" => array('code'=>'&#xf155;'),
			"inr" => array('code'=>'&#xf156;'),
			"jpy" => array('code'=>'&#xf157;'),
			"rub" => array('code'=>'&#xf158;'),
			"krw" => array('code'=>'&#xf159;'),
			"btc" => array('code'=>'&#xf15a;'),
			"file" => array('code'=>'&#xf15b;'),
			"file-text" => array('code'=>'&#xf15c;'),
			"sort-alpha-asc" => array('code'=>'&#xf15d;'),
			"sort-alpha-desc" => array('code'=>'&#xf15e;'),
			"sort-amount-asc" => array('code'=>'&#xf160;'),
			"sort-amount-desc" => array('code'=>'&#xf161;'),
			"sort-numeric-asc" => array('code'=>'&#xf162;'),
			"sort-numeric-desc" => array('code'=>'&#xf163;'),
			"thumbs-up" => array('code'=>'&#xf164;'),
			"thumbs-down" => array('code'=>'&#xf165;'),
			"youtube-square" => array('code'=>'&#xf166;'),
			"youtube" => array('code'=>'&#xf167;'),
			"xing" => array('code'=>'&#xf168;'),
			"xing-square" => array('code'=>'&#xf169;'),
			"youtube-play" => array('code'=>'&#xf16a;'),
			"dropbox" => array('code'=>'&#xf16b;'),
			"stack-overflow" => array('code'=>'&#xf16c;'),
			"instagram" => array('code'=>'&#xf16d;'),
			"flickr" => array('code'=>'&#xf16e;'),
			"adn" => array('code'=>'&#xf170;'),
			"bitbucket" => array('code'=>'&#xf171;'),
			"bitbucket-square" => array('code'=>'&#xf172;'),
			"tumblr" => array('code'=>'&#xf173;'),
			"tumblr-square" => array('code'=>'&#xf174;'),
			"long-arrow-down" => array('code'=>'&#xf175;'),
			"long-arrow-up" => array('code'=>'&#xf176;'),
			"long-arrow-left" => array('code'=>'&#xf177;'),
			"long-arrow-right" => array('code'=>'&#xf178;'),
			"apple" => array('code'=>'&#xf179;'),
			"windows" => array('code'=>'&#xf17a;'),
			"android" => array('code'=>'&#xf17b;'),
			"linux" => array('code'=>'&#xf17c;'),
			"dribbble" => array('code'=>'&#xf17d;'),
			"skype" => array('code'=>'&#xf17e;'),
			"foursquare" => array('code'=>'&#xf180;'),
			"trello" => array('code'=>'&#xf181;'),
			"female" => array('code'=>'&#xf182;'),
			"male" => array('code'=>'&#xf183;'),
			"gittip" => array('code'=>'&#xf184;'),
			"sun-o" => array('code'=>'&#xf185;'),
			"moon-o" => array('code'=>'&#xf186;'),
			"archive" => array('code'=>'&#xf187;'),
			"bug" => array('code'=>'&#xf188;'),
			"vk" => array('code'=>'&#xf189;'),
			"weibo" => array('code'=>'&#xf18a;'),
			"renren" => array('code'=>'&#xf18b;'),
			"pagelines" => array('code'=>'&#xf18c;'),
			"stack-exchange" => array('code'=>'&#xf18d;'),
			"arrow-circle-o-right" => array('code'=>'&#xf18e;'),
			"arrow-circle-o-left" => array('code'=>'&#xf190;'),
			"caret-square-o-left" => array('code'=>'&#xf191;'),
			"dot-circle-o" => array('code'=>'&#xf192;'),
			"wheelchair" => array('code'=>'&#xf193;'),
			"vimeo-square" => array('code'=>'&#xf194;'),
			"try" => array('code'=>'&#xf195;'),
			"plus-square-o" => array('code'=>'&#xf196;'),
		
		);
		
		/* return image through php headers.
		*
		@param string $icon icon name
		@param int $size number between 1-5
		@param string $color hex color value.
		@return resouce Image Resource
		@author Rico Celis
		@access Public
		*/
		public function returnIcon($icon,$size,$color){
			// get image data
			$im = $this->generateImageData($icon,$size,$color,array('return_resource' =>true));
			return $im;
		}
		
		/* export base64 image from FontAwesome Icon
		*
		@param string $icon icon name
		@param int $size number between 1-5
		@param string $color hex color value.
		@param array $attributes array of additional attributes
		@return string img tag with base64 url
		@author Rico Celis
		@access Public
		*/
		public function generateImageTag($icon,$size,$color,$attributes = array()){
			$imgData = $this->generateImageData($icon,$size,$color);
			// base64 data
			$base64Img = base64_encode($imgData);
			// creat image tag
			$html = sprintf('<img src="data:image/png;base64,%s" alt="%s"',$base64Img,$icon);
			if(!empty($attributes)){
				foreach($attributes as $attribute=>$value){
					$html .= sprintf('%s="%s" ',$attribute,$value);
				}
			}
			// close image tag
			$html .="/>";
			return $html;
		}
		
		/* generate image data using settings
		*
		@param string $icon icon name
		@param int $size number between 1-5
		@param string $color hex color value.
		@return string new image data.
		@author Rico Celis
		@access Public
		*/
		private function generateImageData($icon,$size,$color,$options = array()){
			// convert to rgb
			$rgb = $this->hex2rgb($color);
			$this->font = realpath(dirname(__FILE__)). "/fontawesome/fontawesome-webfont.ttf";
			// get code for icon
			$iconData = $this->icons[$icon];
			$text = $iconData['code'];
			
			

			// calculate size and spacing
			$outputSize = $this->outputSizes[$size - 1];
			$size = $width = $height = $outputSize*3;
			$fontSize = $outputSize;
			$padding = (int)ceil(($outputSize/25));
			
			// create the image
			$im = imagecreatetruecolor($width, $height);
			imagealphablending($im, false);
			
			// Create some colors
			$fontC = imagecolorallocate($im, $rgb['r'], $rgb['g'], $rgb['b']);
			$bgc = imagecolorallocatealpha($im, 255, 0, 255, 127);
			imagefilledrectangle($im, 0, 0, $width,$height, $bgc);
			imagealphablending($im, true);
			
			// Add the text
			list($fontX, $fontY) = $this->ImageTTFCenter($im, $text, $this->font, $fontSize);
			imagettftext($im, $fontSize, 0, $fontX, $fontY, $fontC, $this->font, $text);
			
			// Using imagepng() results in clearer text compared with imagejpeg()
			imagealphablending($im,false);
			imagesavealpha($im,true);			
			
			$this->imagetrim($im, $bgc, $padding);
			$this->imagecanvas($im, $outputSize, $bgc, $padding);
			if(@$options['return_resource']){
				return $im;
			}else{
				ob_start();
				imagepng($im);
				$imgData = ob_get_contents(); // read from buffer
				ob_end_clean(); // delete buffer
				imagedestroy($im);
				return $imageData;	
			}
		}
		
		/* convert hex value to rgb
		*
		@param string $hex hex color
		@return array indexed array with rgb value
		@author Rico Celis
		@access Public
		*/
		private function hex2rgb($hex) {
		   $hex = str_replace("#", "", $hex);
		
		   if(strlen($hex) == 3) {
		      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
		   } else {
		      $r = hexdec(substr($hex,0,2));
		      $g = hexdec(substr($hex,2,2));
		      $b = hexdec(substr($hex,4,2));
		   }
		   $rgb = array(
		   	"r" => $r,
		   	"g" => $g,
		   	"b" => $b);
		   return $rgb; // returns an array with the rgb values
		}
		
		private function ImageTTFCenter($image, $text, $font, $size, $angle = 45) 
		{
		    $xi = imagesx($image);
		    $yi = imagesy($image);
		
		    // First we create our bounding box for the first text
			$box = imagettfbbox($size, $angle, $font, $text);
		
			$xr = abs(max($box[2], $box[4]));
		    $yr = abs(max($box[5], $box[7]));
		
		    // compute centering
		    $x = intval(($xi - $xr) / 2);
		    $y = intval(($yi + $yr) / 2);
		
			//echo $x;echo '|';	echo $y;exit;
		    return array($x, $y);
		}
		
		private function imagetrim(&$im, $bg, $pad=null){

		    // Calculate padding for each side.
		    if (isset($pad)){
		        $pp = explode(' ', $pad);
		        if (isset($pp[3])){
		            $p = array((int) $pp[0], (int) $pp[1], (int) $pp[2], (int) $pp[3]);
		        }else if (isset($pp[2])){
		            $p = array((int) $pp[0], (int) $pp[1], (int) $pp[2], (int) $pp[1]);
		        }else if (isset($pp[1])){
		            $p = array((int) $pp[0], (int) $pp[1], (int) $pp[0], (int) $pp[1]);
		        }else{
		            $p = array_fill(0, 4, (int) $pp[0]);
		        }
		    }else{
		        $p = array_fill(0, 4, 0);
		    }
		
		    // Get the image width and height.
		    $imw = imagesx($im);
		    $imh = imagesy($im);
		
		    // Set the X variables.
		    $xmin = $imw;
		    $xmax = 0;
		
		    // Start scanning for the edges.
		    for ($iy=0; $iy<$imh; $iy++){
		        $first = true;
		        for ($ix=0; $ix<$imw; $ix++){
		            $ndx = imagecolorat($im, $ix, $iy);
		            if ($ndx != $bg){
		                if ($xmin > $ix){ $xmin = $ix; }
		                if ($xmax < $ix){ $xmax = $ix; }
		                if (!isset($ymin)){ $ymin = $iy; }
		                $ymax = $iy;
		                if ($first){ $ix = $xmax; $first = false; }
		            }
		        }
		    }
		
		    // The new width and height of the image. (not including padding)
		    $imw = 1+$xmax-$xmin; // Image width in pixels
		    $imh = 1+$ymax-$ymin; // Image height in pixels
		
		    // Make another image to place the trimmed version in.
		    $im2 = imagecreatetruecolor($imw+$p[1]+$p[3], $imh+$p[0]+$p[2]);
		
		    // Make the background of the new image the same as the background of the old one.
		    $bg2 = imagecolorallocatealpha($im2, ($bg >> 16) & 0xFF, ($bg >> 8) & 0xFF, $bg & 0xFF, 127);
		    imagefill($im2, 0, 0, $bg2);
			imagealphablending($im2, true);
		
		    // Copy it over to the new image.
		    imagecopy($im2, $im, $p[3], $p[0], $xmin, $ymin, $imw, $imh);
		
		    // To finish up, we replace the old image which is referenced.
			imagealphablending($im2,false);
			imagesavealpha($im2,true);
		    $im = $im2;
			//imagedestroy($im2);
		}
		
		function imagecanvas(&$im, $size, $bg, $padding)
		{
			$srcW = imagesx($im);
		    $srcH = imagesy($im);
			
			$srcRatio = $srcW/$srcH;
			
			$im2 = imagecreatetruecolor($size, $size);
			$bg2 = imagecolorallocatealpha($im2, ($bg >> 16) & 0xFF, ($bg >> 8) & 0xFF, $bg & 0xFF, 127);
			//imagefilledrectangle($im2, 0, 0, $size,$size, $bg2);
			imagefill($im2, 0, 0, $bg2);
			imagealphablending($im2, true);
			
			// init
			$dstX = $dstY = $srcX = $srcY = 0;
			$dstW = $dstH = $size;
		
			// if source size is smaller than output size
			if($srcW < $size && $srcH < $size)
			{
				$dstW = $srcW; $dstH = $srcH;
			}
			// if source is bigger than output
			else
			{
				// use padding
				// if horizontal long
				if($srcW > $srcH)
				{
					$dstW = $size - $padding;
					$dstH = (int)(($dstW/$srcW)*$srcH);
				}
				// if vertically long or equal(square)
				else
				{
					$dstH = $size - $padding;
					$dstW = (int)(($dstH/$srcH)*$srcW);
				}	
			}
			
			$dstX = (int)(($size - $dstW)/2);
			$dstY = (int)(($size - $dstH)/2);
			
			// imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
			imagecopyresampled($im2, $im, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH);
			
			imagealphablending($im2,false);
			imagesavealpha($im2,true);
			$im = $im2;
			//imagedestroy($im2);
		}
	}
?>