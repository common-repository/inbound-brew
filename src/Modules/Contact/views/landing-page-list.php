<?php $src = BREW_LANDING_PAGE_PATH . 'views/templates/'; ?>
<script type="text/javascript">

    /* --inbound-brew-free-start-- */ 
    var dtConfig = {
                    "order": [[1, "desc"]],
                    "columnDefs": [
                        {"orderable": false, "targets": [3]}
                    ],
                    "pageLength": 25
                };
    /* --inbound-brew-free-end-- */ 

    (function ($) {
        $(document).ready(function () {
            var winW = $(window).width();
            var dioW = winW - winW * .50;
            //$('#landing-page-new-dialog').dialog({autoOpen: false,minHeight:500,width:dioW,position: { my: "left top", at: "right bottom", of: $('#ib-new-landing-page') }});
            $('#ib-new-landing-page').on('click', function () {
                //$("#landing-page-new-dialog").dialog("open");
                $.alert({
                    title: "Create Landing Page",
                    content: $("#landing-page-new-dialog").html(),
                    confirmButtonClass: 'ib_cancel',
                    confirmButton: 'CANCEL',
                    columnClass: 'ib-choose-landing-page-type',

                });
            });

            $('.ib_data-tables').DataTable(dtConfig);


            // find delete button for templates
            $(".ib_delete-lp").each(function(){
                $(this).click(function(e){
                    e.preventDefault();
                    deleteLandingPageConfirm($(this)); 
                });
            });


            var deleteLandingPageConfirm = function($delete_button){
                // delete confirm
                $.confirm({
                    title: 'Delete Landing Page?',
                    content: "Are you sure you want to delete this landing page? This cannot be undone.",
                    confirm: function(){
                        window.location.href = $delete_button.attr('href');
                    },
                    cancel:function(){
                        _lockClick = false;
                    },
                    confirmButton: 'DELETE',
                    cancelButton: 'CANCEL',
                    confirmButtonClass: 'ib_save',
                    cancelButtonClass: 'ib_cancel'
                });
            }
        });
    })(jQuery);
</script>
<div id="ib_landing_pages">
    <div class="ib_list-buttons">
        <button id="ib-new-landing-page" class="ib-button next-to-extra">
            <span class="fa fa-plus"></span> New Landing Page
        </button>
    </div>
    

    <div class="clear"></div>
    <div class="ib-tabs ib-margin-top" id="ib-tabs">
        <div class="tabs">
            <!-- Manage Redirect Tabs -->
            <div id="ib_lp">
                <table cellpadding="0" cellspacing="0" width="100%" class="ib_data-tables stripe">
                    <thead>
                        <tr>
                            <!-- <th>ID</th> -->
                            <th class="ib-main">Title</th>
                            <?php 
?>
                            <th>Last Updated</th>
                            <th>Layout</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody id="ib_lp_list">
                        <?php
                        $i = 0;
                        foreach ($lps as $lp):
                            //print_debug($lp, true);
                            $status = ($lp->post_status == "future") ? "scheduled" : $lp->post_status;
                            $class = "";
                            $edit_url = "post.php?post={$lp->ID}&action=edit";
                            $trash_url = get_delete_post_link($lp->ID);
                            if ($i++ % 2 == 0)
                                $class = "alt0";
                            ?>
                            <tr class="<?php echo $class; ?>" id="ib_lp-row-<?php echo $lp->ID; ?>">
                                <!-- <td><?php echo $lp->ID; ?></td> -->
                                <td class="ib-main"><a href="<?php echo $edit_url; ?>"><?php echo $lp->post_title; ?></a>
                                    <?php if ($status != "publish") echo " <span class=\"ib-post-status\">&mdash;" . ucfirst($status) . "</span>"; ?></td>
                                <?php 
?>
                                <td><?php
                                    switch ($status):
                                        case "scheduled":
                                            $the_date = $lp->post_date_gmt;
                                            $date_text = "Scheduled";
                                            break;
                                        case "draft":
                                            $the_date = $lp->post_modified_gmt;
                                            $date_text = "Last Modified";
                                            break;
                                        default:
                                            $the_date = $lp->post_date_gmt;
                                            $date_text = "Published";
                                            break;
                                    endswitch;
                                    echo $date_text . "<br>" . $Date->format(BREW_WP_DATE_FORMAT, $the_date, true);
                                    ?></td>
                                <td>
                                    <img src="<?php echo $src . $lp->template->meta_value . '/thumb-' . $lp->template->meta_value . '.png'; ?>" />
                                </td>
                                <td>
                                    <a href="<?php echo $edit_url; ?>" class="ib_icon-link fa fa-pencil"></a>
                                    <a href="<?php echo $trash_url; ?>" class="ib_icon-link delete fa fa-trash ib_delete-lp"></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="landing-page-new-dialog" title="Create Landing Page" style="display:none;">
        <ul class="ib-grid columns-2">
            <?php foreach ($templates as $key => $template): ?>
                <li>
                    <div class="ib-layout-thumbnail"><img src="<?php echo $src . $key . '/' . $template->thumb; ?>"></div>
                    <div class="ib-layout-details">
                        <a href="post-new.php?post_type=ib-landing-page&layout=<?php echo $key; ?>"><?php echo $template->name; ?></a><br>
                        <?php echo $template->description; ?>
                    </div>
                    <div class="clear"></div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>