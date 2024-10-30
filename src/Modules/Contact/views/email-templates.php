<div id="ib_mail">
    <div class="ib-tabs ib-row" id="ib-tabs">
        <div class="tab-wrapper noselect">
            <a href="admin.php?page=ib-email-admin&section=templates" class="ib-tab-link selected">Emails</a>
            <a href="admin.php?page=ib-email-admin&section=header" class="ib-tab-link">Header</a>
            <a href="admin.php?page=ib-email-admin&section=footer" class="ib-tab-link">Footer</a>
            <a href="admin.php?page=ib-email-admin&section=content" class="ib-tab-link">Content</a>
            <a href="admin.php?page=ib-email-admin&section=social" class="ib-tab-link">Social</a>
            <a href="admin.php?page=ib-email-admin&section=advanced" class="ib-tab-link">Advanced</a>
        </div>
        <div class="tabs">
            <!-- Manage Redirect Tabs -->
            <div id="ib_mail_templates">
                <div class="fr">
                    <a href="admin.php?page=ib-email-admin&section=template" class="ib-button">Create New</a>
                </div>
                <div class="clear"></div>
                <?php //echo $templates->render();?>
                <table cellpadding="0" cellspacing="0" width="100%" class="ib_data-tables stripe">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th class="ib-main">Email Title</th>
                            <th>Email Description</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody id="ib_email_list">
                    <?php $i = 0; foreach($templates as $template):
                        $class = "";
                        $edit_url = "admin.php?page=ib-email-admin&section=template&email_id={$template->email_id}";
                        $trash_url = "admin-post.php?action=delete_email_template&email_id={$template->email_id}";
                        if ($i++ % 2 == 0) $class="grey"; ?>
                        <tr class="<?php echo $class; ?>" id="ib_email-row-<?php echo $template->email_id; ?>">
                            <td id="email_id_<?php echo $template->email_id; ?>"><?php echo $template->email_id; ?></td>
                            <td id="email_title_<?php echo $template->email_id; ?>" class="ib-main"><a href="<?php echo $edit_url; ?>"><?php echo $template->email_title ?></a></td>
                            <td id="email_subject_<?php echo $template->email_id; ?>"><?php echo $template->email_subject ?></td>
                            <td>
                                <a href="<?php echo $trash_url; ?>" class="ib-link delete">[delete]</a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <section>
        <div class="ib-row">
            <div class="fl ib-column-6 ib-td">
                <!--                <span style="height:1em;width:1em;background-color:mediumspringgreen;display:inline-block;"></span> Prospect&nbsp;-->
                <!--                <span style="height:1em;width:1em;background-color:deepskyblue;display:inline-block;"></span> Lead&nbsp;-->
                <!--                <span style="height:1em;width:1em;background-color:yellow;display:inline-block;"></span> Customer-->
            </div>
        </div>
    </section>
    <script>
        jQuery(document).ready(function($) {
	        // data tables
	        $('.ib_data-tables').DataTable({
		        "order": [[ 1, "asc" ]],
	            "columnDefs": [
	            	{ "orderable": false, "targets": [0,3] }
	            ],
                "pageLength": 25
            	});
            // delete confirm
            $(".ib-link.delete").each(function(){
                $(this).click(function(e){
                    e.preventDefault();
                    var $me = $(this);
                    $.confirm({
                        title: 'Delete Template',
                        content: "Are you sure you want to delete this template?",
                        confirm: function(){
                            window.location.href = $me.attr('href');
                        },
                        cancel:function(){
                            _lockClick = false;
                        },
                        confirmButton: 'DELETE',
                        cancelButton: 'CANCEL',
                    });
                });
            });
        });
    </script>
</div>