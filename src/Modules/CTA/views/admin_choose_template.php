<div id="ib_template_list">
    <div class="ib_text-section"><a href="<?php echo $custom_url; ?>">Choose Template</a></div>
    <div id="ib_cta">
        <table cellpadding="0" cellspacing="0" width="100%" class="ib_data-tables stripe" id="ib_template_list">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($templates as $template):
                    $tid = $template->template_id;
                    $select_url = get_admin_url()."admin.php?page={$post_type}&section=ib_add_cta&cta_type=new_cta&tid={$tid}";
                    ?>
                    <tr id="template_<?php echo $tid; ?>" data-id="<?php echo $tid; ?>">
                        <td>
                            <div class="ib-cta_preview-list" data-url="<?php echo $select_url; ?>"><?php echo stripslashes($template->html); ?></div>
                        </td>
                        <td>
                            <strong class="ib_uppercase"><?php echo $template->name; ?></strong><br>
                            <span class="ib_details">
                                Last Edited: <?php echo $Date->format(BREW_WP_DATE_FORMAT . " " . BREW_WP_TIME_FORMAT, $template->updated_at, true); ?><br>
                                Used: <?php $used = count($template->ctas());
                    echo sprintf("%s time%s", number_format($used), ($used != 1) ? "s" : "");
                    ?>
                            </span>
                        </td>
                        <td>
                            <a class="ib-button ib_select-gray" href="<?php echo $select_url; ?>">select</a>
                        </td>
                    </tr>

<?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $("#ib_template_list").ib_ctaLists({
            list: "choose_template",
            post_type: "<?php echo $post_type; ?>"
        });
    });
</script>