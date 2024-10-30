<div class="ib-tabs" id="ib-tabs">
	<div class="tab-wrapper noselect">
		<a href="admin.php?page=<?php echo $post_type; ?>&section=list" class="ib-tab-link">Redirects</a>
		<a href="admin.php?page=<?php echo $post_type; ?>&section=import" class="ib-tab-link selected">Import/Export</a>
	</div>
	<div class="tabs">
		<div class="tab ib-padding">
			<div class="ib-header">Import Redirects:</div>
			<div class="ib-td">
                To import your redirects please <a href="<?php echo BREW_PLUGIN_ASSETS_URL; ?>files/redirects_template.csv">download this template</a>.<br><br>
				STATUS: 301 or 302
				WILDCARD: 0=NO 1=YES
            </div>
            <div class="ib-td">
	            <?php echo $Form->create("ib_import_redirects",array('type'=>"file")); 
					echo $Form->hidden("ib_import_redirects","1"); ?>
					<?php echo $Form->radio('Redirect.handle_duplicates',array(
						'ignore_duplicates' => "<b>Ignore Duplicates</b>",
						'replace_duplicates' => "<b>Replace Duplicates</b>",
						'replace_all' => "<b>Replace All Redirects</b>"),array('div'=>false));?>
				<div class="ib-margin-top">
					<?php echo $Form->file('ib_csv_file',array(
							'required' => true,
							'accept' => "csv",
							'div' => false
						));?>
					<button id="ibRedirectImport" class="ib-button">Submit CSV</button>
				</div>
				<?php echo $Form->end();?>
			</div>
			<div class="ib-header">Export Redirects:</div>
            <div class="ib-td">
                Click the button below to export a CSV file of all the redirects you have created.
                <?php echo $Form->create("ib_export_redirect",array('type'=>"file")); 
						echo $Form->hidden("ib_export_redirects","1"); ?>
				<button id="ibRedirectExport" class="ib-button">Export Redirects</button>
				<?php echo $Form->end();?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$("#RedirectHandleDuplicates2").click(function(){
			$.alert({
				title:"Replace All Redirects",
				content:"You are about to delete all your redirects and replace them with the uploaded file.",
				confirmButtonClass: 'ib_save'
			});
		});
	});
</script>