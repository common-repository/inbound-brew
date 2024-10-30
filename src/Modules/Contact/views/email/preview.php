<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{email_title}}</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <link rel="stylesheet" href="<?php echo BREW_MODULES_URL ?>Core/assets/css/tipsy.css">
    <script src="<?php echo BREW_MODULES_URL;?>Core/assets/js/jquery.tipsy.js"></script>
    <style type="text/css">
        body,
        .email-body{
            margin:0px;
            padding:0px;
            /*background-color:#FFFFFF;*/
            color:#777777;
            font-family:Arial, Helvetica, sans-serif;
            -webkit-text-size-adjust:100%;
            -ms-text-size-adjust:100%;
            width:100% !important;
        }
        #footer a:hover,#footer a:active,
        #footer a:link, #footer a:visited{
            text-decoration:none;
            color:inherit;
        }
        span.ib_token-error{
	        font-weight:bold;
	        color:#CC0000;
	        cursor:pointer;
        }
        h2{
            padding:0px 0px 10px 0px;
            margin:0px 0px 10px 0px;
        }
        h2.name{
            padding:0px 0px 7px 0px;
            margin:0px 0px 7px 0px;
        }
        h3{
            padding:0px 0px 5px 0px;
            margin:0px 0px 5px 0px;
        }
        p{
            margin:0 0 14px 0;
            padding:0;
        }
        img{
            border:0;
            -ms-interpolation-mode:bicubic;
            max-width:100%;
        }
        a img{
            border:none;
        }
        table td{
            border-collapse:collapse;
        }
        td.quote{
            font-family:Georgia, 'Times New Roman', Times, serif;
            font-size:18px;
            line-height:20pt;
            color:#2e8b57;
        }
        span.phone a,span.noLink a{
            color:#2e8b57;
            text-decoration:none;
        }
        .ReadMsgBody{
            width:100%;
        }
        .ExternalClass{
            width:100%;
        }
        .tipsy{
	        font-size:15px;
	        font-weight:300;
	        line-height:25px;
        }
        .tipsy strong{
	        font-style:italic;
        }
    </style>
    <script type="text/javascript">
	    jQuery(document).ready(function($) {
			$(document).on("mouseenter", ".ib_tipsy", function(e) {
			    if ( !$(this).data("tipsy") ) {
			        e.preventDefault();
			        $(this).tipsy({
			            fade: true,
			            html:true,
			            title:function(){
				            var $me = $(this);
				            var conflicts = $me.data("conflicts").split("|");
				            var str;
				            if(conflicts.length > 1){
					            str = "This email template is linked to <strong>" + conflicts.join(", ")+" contact forms.</strong> They it is not capturing this form field.";
				            }else{
					            str = "This email template is linked to <strong>" + conflicts[0]+" contact form.</strong> and it is not capturing this form field.";
				            }
				            return str;
			            }
			        }).trigger("mouseenter");
			        return false;
			    }
			});
		}); 
	</script>
</head>
<body>
	<div class="email-body">
		<div id="top_bar" style="text-align:right;">
			<div id="top_bar_social_icons">
				<a href="" data-network="facebook"><span class="fa fa-facebook-square fa-2x"></span></a>
				<a href="" data-network="twitter"><span class="fa fa-twitter-square fa-2x"></span></a>
				<a href="" data-network="linkedin"><span class="fa fa-linkedin-square fa-2x"></span></a>
				<a href="" data-network="google-plus"><span class="fa fa-google-plus-square fa-2x"></span></a>
			</div>
		</div>
		<div id="header_container">
			<img src="" id="logo-image" style="display:none;"/>
		</div>
		<div id="banner_image">
			<img src="" id="banner-image" style="width:100% !important; height:auto !important;" style="display:none;"/>
		</div>
		<div id="body">{{template_content}}</div>
		<div id="footer">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="50%">
						<div id="footer_logo">
							<img src="" id="footer-logo" align="bottom" />
						</div>
					</td>
					<td width="50%" id="footer_social_icons" style="text-align:right;">
						<a href="" data-network="facebook"><span class="fa fa-facebook-square fa-2x"></span></a>
						<a href="" data-network="twitter"><span class="fa fa-twitter-square fa-2x"></span></a>
						<a href="" data-network="linkedin"><span class="fa fa-linkedin-square fa-2x"></span></a>
						<a href="" data-network="google-plus"><span class="fa fa-google-plus-square fa-2x"></span></a>
					</td>
				</tr>
				<tr>
					<td width="50%">
						<div id="contact_info" style="font-size:12px;font-family:Arial;">
							<span id="contact_address">PO Box 535<br></span>
							<span id="contact_city">Heber City<br></span>
							<span id="contact_state">UT<br></span>
							<span id="contact_zip">84032<br></span>
							<span id="contact_phone">801.921.0201<br></span>
							<span id="contact_email">info@ricocelis.com<br></span>
							<span id="contact_website">www.inboundbrew.com<br></span>
						</div>
						<div id="copyright_info" style="font-size:12px;font-family:Arial;">
							<span id="copyright">&copy; Inbound Brew, 2016. All rights reserved.</span>
						</div>
					</td>
					<td width="50%" style="vertical-align:bottom;text-align:right;font-size:12px;font-family:Arial;line-height:20px;" id="contact_custom">
						<span id="footer_custom">For All Your Marketing Needs</span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div style="vertical-align:bottom;text-align:center;font-size:10px;font-family:Arial;line-height:20px;" id="unsubscribe">{{unsubscribe}}</div>
					</td>
				</tr>
			</table>
		<div>		
	</div>
</body>
</html>