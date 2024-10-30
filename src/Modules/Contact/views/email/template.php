<?php $create_icon_url = get_site_url() . "/" . BREW_CREATE_SOCIAL_ICON_SLUG . "/" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{email_title}}</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <style type="text/css">
        body,
        .email-body{
            margin:10px auto;
            padding:0px;
            /*background-color:#FFFFFF;*/
            color:#777777;
            font-family:Arial, Helvetica, sans-serif;
            -webkit-text-size-adjust:100%;
            -ms-text-size-adjust:100%;
            width:98% !important;
        }
        #footer a, #footer a:hover,#footer a:active,
        #footer a:link, #footer a:visited{
            text-decoration:none;
            color: #<?php echo $template_data['footer']['color']; ?> !important;
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
        .emailPreview{
        	display: none; 
        	max-height: 0px; 
        	overflow: hidden;
        }

    </style>
</head>
<body>
	<div class='emailPreview'>
		<?php if(@$email_data['email_value']):
				echo stripslashes($email_data['email_value']);
			else:
				echo "{{template_content}}";
			endif; ?>
			&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
	</div>

	<div class="email-body">
		<?php if($template_data['top_bar']['container_visible']):
			$topBarStyle = sprintf("padding:%spx %spx %spx %spx;background-color:#%s;",$template_data['top_bar']['padding_top'],$template_data['top_bar']['padding_right'],$template_data['top_bar']['padding_bottom'],$template_data['top_bar']['padding_left'],$template_data['top_bar']['background']);  ?>
		<div id="top_bar" style="text-align:right;<?php echo $topBarStyle; ?>">
			<?php if($template_data['top_bar_social_icons']['container_visible']):
				$color = (@$template_data['top_bar_social_icons']['color'])? $template_data['top_bar_social_icons']['color']: "FFFFFF";
				$size = (@$template_data['top_bar_social_icons']['icon_size'])? $template_data['top_bar_social_icons']['icon_size']: 2;
				$margin = (@$template_data['top_bar_social_icons']['icon_spacing'])? $template_data['top_bar_social_icons']['icon_spacing']: 10;
				 ?>
			<div id="top_bar_social_icons">
				<?php if(@$template_data['top_bar_social_icons']['facebook']): ?>
				<a href="<?php echo $settings->social_url_facebook; ?>" data-network="facebook"><span style="margin-left:<?php echo $margin; ?>px;text-decoration: none; color: #<?php echo $color; ?>; font-size: <?php echo $size*15; ?>px">&#xf082;</span></a>
				<?php endif; ?>
				<?php if(@$template_data['top_bar_social_icons']['twitter']): ?>
				<a href="<?php echo $settings->social_url_twitter; ?>" data-network="twitter"><span style="margin-left:<?php echo $margin; ?>px;text-decoration: none; color: #<?php echo $color; ?>; font-size: <?php echo $size*15; ?>px">&#xf081;</span></a>
				<?php endif; ?>
				<?php if(@$template_data['top_bar_social_icons']['linked_in']): ?>
				<a href="<?php echo $settings->social_url_linkedin; ?>" data-network="linkedin"><span style="margin-left:<?php echo $margin; ?>px;text-decoration: none; color: #<?php echo $color; ?>; font-size: <?php echo $size*15; ?>px">&#xf08c;</span></a>
				<?php endif; ?>
				<?php if(@$template_data['top_bar_social_icons']['google_plus']): ?>
				<a href="<?php echo $settings->social_url_google_plus; ?>" data-network="google_plus"><span style="margin-left:<?php echo $margin; ?>px;text-decoration: none; color: #<?php echo $color; ?>; font-size: <?php echo $size*15; ?>px">&#xf0d4;</span></a>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
		<?php endif;
		if($template_data['header']['container_visible']):
		$headerStyle = sprintf("margin-top:%spx;margin-bottom:%spx;padding:%spx %spx %spx %spx;text-align:%s",$template_data['header']['margin_top'],$template_data['header']['margin_bottom'],$template_data['header']['padding_top'],$template_data['header']['padding_right'],$template_data['header']['padding_bottom'],$template_data['header']['padding_left'],(isset($template_data['header']['logo_image_align']) ? $template_data['header']['logo_image_align'] : "")); ?>
		<div id="header_container" style="<?php echo $headerStyle; ?>">
			<?php if(@$template_data['header']['logo_image']): ?>
			<img src="<?php echo $template_data['header']['logo_image']; ?>" id="logo-image"/>
			<?php endif; ?>
		</div>
		<?php endif;
		if($template_data['banner_image']['container_visible']):
		$bannerStyle = sprintf("margin-top:%spx;margin-bottom:%spx;",$template_data['banner_image']['margin_top'],$template_data['banner_image']['margin_bottom']); ?>
		<div id="banner_image" style="<?php echo $bannerStyle; ?>">
			<?php if(@$template_data['banner_image']['image']): ?>
			<img src="<?php echo $template_data['banner_image']['image']; ?>" id="banner-image" style="width:100% !important; height:auto !important;"/>
			<?php endif; ?>
		</div>
		<?php endif;
		$bodyStyle = sprintf("padding:%spx %spx %spx %spx;background-color:#%s;color:#%s",$template_data['body']['padding_top'],$template_data['body']['padding_right'],$template_data['body']['padding_bottom'],$template_data['body']['padding_left'],$template_data['body']['background'],$template_data['body']['color']); ?>
		<div id="body" style="<?php echo $bodyStyle; ?>">
			<?php if(@$email_data['email_value']):
				echo stripslashes($email_data['email_value']);
			else:
				echo "{{template_content}}";
			endif; ?>
		</div>
	<?php 	$footer_style = sprintf("padding:%spx %spx %spx %spx;background-color:#%s;color:#%s",$template_data['footer']['padding_top'],$template_data['footer']['padding_right'],$template_data['footer']['padding_bottom'],$template_data['footer']['padding_left'],$template_data['footer']['background'],$template_data['footer']['color']); ?>
		<div id="footer" style="<?php echo $footer_style; ?>">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="50%">
						<?php if(isset($template_data['footer']['logo_container']) && $template_data['footer']['logo_container']):
							$footerLogoStyle = sprintf("margin:%spx %spx %spx %spx;",$template_data['footer']['logo_margin_top'],$template_data['footer']['logo_margin_right'],$template_data['footer']['logo_margin_bottom'],$template_data['footer']['logo_margin_left']); ?>
						<div id="footer_logo" style="<?php echo $footerLogoStyle; ?>">
							<?php if(!empty($template_data['footer']['logo_image'])): ?>
							<img src="<?php echo $template_data['footer']['logo_image']; ?>" id="footer-logo" align="bottom" />
							<?php endif; ?>
						</div>
						<?php endif;?>
					</td>
					<?php if($template_data['footer_social_icons']['container_visible']):
						$color = (@$template_data['footer_social_icons']['color'])? $template_data['footer_social_icons']['color']: "FFFFFF";
						$size = (@$template_data['footer_social_icons']['icon_size'])? $template_data['footer_social_icons']['icon_size']: 2;
						$margin = (@$template_data['footer_social_icons']['icon_spacing'])? $template_data['footer_social_icons']['icon_spacing']: 10;
					?>
					<td width="50%" id="footer_social_icons" style="text-align:right;">
						<?php if(@$template_data['footer_social_icons']['facebook']): ?>
						<a href="<?php echo $settings->social_url_facebook; ?>" data-network="facebook"><span style="margin-left:<?php echo $margin; ?>px;text-decoration: none; color: #<?php echo $color; ?>; font-size: <?php echo $size*15; ?>px">&#xf082;</span></a>
						<?php endif; ?>
						<?php if(@$template_data['footer_social_icons']['twitter']): ?>
						<a href="<?php echo $settings->social_url_twitter; ?>" data-network="twitter"><span style="margin-left:<?php echo $margin; ?>px;text-decoration: none; color: #<?php echo $color; ?>; font-size: <?php echo $size*15; ?>px">&#xf081;</span></a>
						<?php endif; ?>
						<?php if(@$template_data['footer_social_icons']['linked_in']): ?>
						<a href="<?php echo $settings->social_url_linkedin; ?>" data-network="linkedin"><span style="margin-left:<?php echo $margin; ?>px;text-decoration: none; color: #<?php echo $color; ?>; font-size: <?php echo $size*15; ?>px">&#xf08c;</span></a>
						<?php endif; ?>
						<?php if(@$template_data['footer_social_icons']['google_plus']): ?>
						<a href="<?php echo $settings->social_url_google_plus; ?>" data-network="google_plus"><span style="margin-left:<?php echo $margin; ?>px;text-decoration: none; color: #<?php echo $color; ?>; font-size: <?php echo $size*15; ?>px">&#xf0d4;</span></a>
						<?php endif; ?>
					</td>
					<?php endif; ?>
				</tr>
				<tr>
					<td width="50%">
						<?php if(@$template_data['footer']['contact_phone']): ?>
							<div id="contact_info" style="font-size:12px;font-family:Arial;">
								<span id="contact_address"><?php echo $template_data['footer']['contact_address']; ?></span><br>
								<span id="contact_city"><?php echo $template_data['footer']['contact_city']; ?></span>,
								<span id="contact_state"><?php echo $template_data['footer']['contact_state']; ?></span>
								<span id="contact_zip"><?php echo $template_data['footer']['contact_zip']; ?><br></span>
								<?php if(@$template_data['footer']['contact_phone']): ?>
									<a href="" id="contact_phone"><?php echo $template_data['footer']['contact_phone']; ?><br></a>
								<?php endif;
								if($template_data['footer']['contact_email']): ?>
									<a href="mailto:<?php echo $template_data['footer']['contact_email']; ?>" id="contact_email"><?php echo $template_data['footer']['contact_email']; ?><br></a>
								<?php endif;
								if($template_data['footer']['contact_website']): ?>
									<a href="<?php echo $template_data['footer']['contact_website']; ?>" id="contact_website"><?php echo $template_data['footer']['contact_website']; ?><br></a>
								<?php endif; ?>
							</div>
						<?php endif; 
						if(@$template_data['footer']['copyright']): ?>
							<div id="copyright_info" style="font-size:12px;font-family:Arial;">
								<span id="copyright">&copy; <?php echo $template_data['footer']['copyright_info']; ?></span>
							</div>
						<?php endif; ?>
					</td>
					<td width="50%" style="vertical-align:bottom;text-align:right;font-size:12px;font-family:Arial;line-height:20px;" id="contact_custom">
						<?php if($template_data['footer']['contact_custom']): ?>
						<span id="footer_custom"><?php echo $template_data['footer']['contact_custom']; ?></span>
						<?php endif; ?>						
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<?php if(@$template_data['footer']['unsubscribe']): ?>
						<div style="vertical-align:bottom;text-align:center;font-size:10px;font-family:Arial;line-height:20px;" id="unsubscribe">{{unsubscribe}}</div>
						<?php endif; ?>						
					</td>
				</tr>
			</table>
	</div>
</body>
</html>