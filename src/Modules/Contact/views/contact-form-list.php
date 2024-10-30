<?php $src = BREW_LANDING_PAGE_PATH . 'views/templates/'; ?>
<script type="text/javascript">
    
    jQuery(document).ready(function ($) {
                $("#ib_cf").ib_formLists();
    });
</script>
<div id="ib_contact_forms">
    <div class="ib_list-buttons"><a class="ib-button" href="admin.php?page=ib-contact-forms&section=add"><span class="fa fa-plus"></span> New Form</a></div>

    <div class="ib-tabs" id="ib-tabs">
        <div class="tab-wrapper noselect">
            <a href="admin.php?page=ib-contact-forms&section=list" id="contact-forms-tab" class="ib-tab-link selected">Contact Forms</a>
            <a href="admin.php?page=ib-contact-forms&section=settings" class="ib-tab-link">Settings</a>
        </div>
        <div class="tabs">
            <!-- Manage Redirect Tabs -->
            <div id="ib_cf">
                <table cellpadding="0" cellspacing="0" width="100%" class="ib_data-tables stripe">
                    <thead>
                        <tr>
                            <!-- <th>ID</th> -->
                            <th class="ib-main">Title</th>
                            <?php 
?>
                            <th>Created</th>
                            <th>Short Code</th>
                            <th>Form Fields</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody id="ib_cf_list">
                        <?php
                        $i = 0;
                        foreach ($cfs as $cf):
//                            print_debug($cf, true);
                            $class = "";
                            $edit_url = "admin.php?page=ib-contact-forms&section=edit&cf_id={$cf->ID}";
                            $trash_url = get_delete_post_link($cf->ID);
                            $clone_url = "admin.php?page=ib-contact-forms&section=clone&cf_id={$cf->ID}";
                            if ($i++ % 2 == 0)
                                $class = "alt0";
                            ?>
                            <tr class="<?php echo $class; ?>" id="ib_cf-row-<?php echo $cf->ID; ?>">
                                <!-- <td><?php echo $cf->ID; ?></td> -->
                                <td class="ib-main"><a href="<?php echo $edit_url; ?>"><?php echo $cf->post_title; ?></a></td>
                                <?php 
?>
                                <td><?php 
                                            echo $Date->format(BREW_WP_DATE_FORMAT, $cf->post_date_gmt, true); ?></td>
                                <td>
                                    <span class="ib_short-code" data-shortcode="[brew_cf id=<?php echo $cf->ID; ?>]" original-title="">[brew_cf id=<?php echo $cf->ID; ?>]</span>
                                </td>
                                <td>
                                    <?php
                                    echo '| ';
                                    foreach ($cf->fields as $field):
                                        echo $field->field_name . ' | ';
                                    endforeach;
                                    ?>
                                </td>
                                <td class="ib_tools">
                                        <!--a href="<?php echo $clone_url; ?>" class="ib_icon-link fa fa-clone"></a-->
                                    <a href="<?php echo $edit_url; ?>" class="ib_icon-link fa fa-pencil" title='Edit'></a>
                                    <a href="<?php echo $trash_url; ?>" class="ib_icon-link delete fa fa-trash ib_delete-form" title='Delete'></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $(".ib_delete-form").on("click", function (e) {
            var $me = $(this);
            e.preventDefault();
            $.confirm({
                title: 'Delete Contact Form?',
                content: "Are you sure you want to delete this Contact Form?",
                confirm: function () {
                    window.location.href = $me.attr("href");
                },
                cancel: function () {
                    _lockClick = false;
                },
                confirmButton: 'DELETE',
                cancelButton: 'CANCEL',
                confirmButtonClass: 'ib_save',
                cancelButtonClass: 'ib_cancel'
            });
        })
    });

    /* --inbound-brew-free-start-- */ 
    var dtConfig = {
                        "order": [[1, "desc"]],
                        "columnDefs": [
                            {"orderable": false, "targets": [2, 4]}
                        ],
                        "pageLength": 25
                    };
    /* --inbound-brew-free-end-- */ 
</script>
