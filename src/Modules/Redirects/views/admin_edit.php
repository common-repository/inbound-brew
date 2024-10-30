<div class="wrap" id="ib_redirects">
	<?php echo $Breadcrumb->printPath(); ?>
	<div class="ib-column ib-column-12 ib-admin-box">
		<?php echo $Form->create("ib_edit_redirect");
		echo $Form->hidden("Redirect.redirect_id",$redirect['Redirect']['redirect_id']) ?>
        	<div class="ib-th"><b>Add New Redirect:</b></div>
			<div class="ib-td"><strong>301:</strong> Permanent Redirect&nbsp;&nbsp;&nbsp;<strong>302:</strong> Temporary Redirect)</div>
			<div>
						<?php echo $Form->select('Redirect.status',array(
							301 => '301',
							302 => '302'
						) ,array(
							'label' => "Status",
							'required' => true,
							'div' => "input select fl"
						)); ?>
					</div>
					<div class="input text">
						<label for="RedirectRedirectFromUrl"><span class="required">*</span>Redirect From <?php bloginfo('url'); ?>/</label>
						<?php echo $Form->text('Redirect.redirect_from',array(
							'label' => false,
							'required' => true,
							'div' => false,
							'size' => 40
						)); ?>
					</div>
					<div class="input select">
						<div class="fl">
							<?php echo $Form->select('Redirect.redirect_type',$options['types'],array(
								'label' => "Redirect To",
								'required' => true,
								'div' => false
							)); ?>
						</div>
						<div id="ib_post-type-options" class="fl">
							<?php echo $Form->text('Redirect.url_options',array(
								'label' => false,
								'required' => true,
								'id' => "ib_url_options",
								'div' => false,
								'style' => "display:none;",
								'size' => 40
							)); 
							// create post type options dropdowns
							foreach($options['options'] as $postType => $pOptions):
								$fieldName = "Redirect." . $postType . "_options";
								echo $Form->select($fieldName,$pOptions,array(
									'empty' => "Choose One",
									'id' => "ib_".$postType."_options",
									'label' => false,
									'required' => true,
									'style' => "display:none;",
									'data-type' => "post-type-options",
									'div' =>false
								));
							endforeach;
						?>	
						</div>
						<div class="clear"></div>
					</div>
			<?php 
				$wlink = "<a href=\"\" id=\"ib-help\" data-type=\"wildcard\">What are wildcards?</a>";
				echo $Form->checkbox("Redirect.is_wildcard","1",array(
				'label' => "Uses Wildcard? {$wlink}",
			)); ?>
			<div class="fr ib-td"><a href="" id='ibEditCancel' class="ib-button cancel">Cancel</a></div>
			<div class='ib-td'><button id="ibRedirectSubmit" class="ib-button">Submit Changes</button></div>
	<?php echo $Form->end(); ?>
	</div>
</div>
<script>
	jQuery(document).ready(function($) {
		$("#ib_redirects").ib_redirects({
			redirect_submit: $("#ibRedirectSubmit"),
			post_type:"<?php echo $post_type; ?>"
		});
	});
</script>