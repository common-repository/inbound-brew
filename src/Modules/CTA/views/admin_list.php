<div id="ib_cta">
    <?php echo $Layout->element($partials_path . "list_actions", array('post_type' => $post_type)); ?>
    <div class="ib-tabs" id="ib-tabs">
        <?php
        echo $Layout->element($partials_path . "list_tabs", array(
            'post_type' => $post_type,
            'active' => "ctas"));
        ?>
        <div class="tabs">
            <!-- Manage Redirect Tabs -->
            <div>
                <table cellpadding="0" cellspacing="0" width="100%" class="ib_data-tables stripe">
                    <thead>
                        <tr>
                            <!-- <th>ID</th> -->
                            <th>Preview</th>
                            <th>Type</th>
                            <th>Links To</th>
                            <th>Short Code</th>
                            <?php 
                            ?>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody id="ib_cta_list">
                        <?php
                        $i = 0;
                        foreach ($ctas as $cta):
                            $edit_url = "admin.php?page={$post_type}&section=ib_edit_cta&cta_id={$cta->cta_id}";
                            $trash_url = wp_nonce_url("admin.php?page={$post_type}&section=ib_delete_cta&cta_id={$cta->cta_id}", "ib_delete_cta_nonce");
                            $clone_url = "admin.php?page={$post_type}&section=ib_clone_cta&cta_id={$cta->cta_id}";
                            if ($i++ % 2 == 0)
                                $class = "alt0";
                            ?>
                            <tr id="ib_cta-row-<?php echo $cta->cta_id; ?>">
                                <!-- <td><?php echo $cta->cta_id; ?></td> -->
                                <td><div class="ib-cta_preview-list" data-url="<?php echo $edit_url; ?>"><?php echo do_shortcode(stripslashes($cta->html)); ?></div></td>
                                <td><?php echo $cta->cta_type; ?></td>
                                <td class="text-left">
                                    <?php
                                    if ($cta->links_to == "internal"):
                                        $wp_id = $cta->links_to_value;
                                        $pt = get_post_type($wp_id);
                                        if (in_array($pt, array("post", "page"))):
                                            $link_url = get_permalink($wp_id);
                                        else:
                                            $link_url = get_post_permalink($wp_id);
                                        endif;
                                        $link_title = get_the_title($wp_id);
                                    else:
                                        $link_url = $link_title = $cta->links_to_value;
                                    endif;
                                    ?>
                                    <a href="<?php echo $link_url; ?>"><?php echo $link_title; ?></a><br>
                                    <?php echo $Date->format(BREW_WP_DATE_FORMAT . " " . BREW_WP_TIME_FORMAT, $cta->updated_at, true); ?>
                                </td>
                                <td><span class="ib_short-code" title="Copy to Clipboard" data-shortcode='[brew_cta id="<?php echo $cta->cta_id; ?>"]'>[brew_cta id="<?php echo $cta->cta_id; ?>"]</span></td>
                                <?php 
                                ?>
                                <td class="ib_tools">
                                    <a href="<?php echo $clone_url; ?>" class="ib_icon-link fa fa-clone" title="Clone"></a>
                                    <a href="<?php echo $edit_url; ?>" class="ib_icon-link fa fa-pencil" title="Edit"></a>
                                    <a href="<?php echo $trash_url; ?>" class="ib_icon-link delete fa fa-trash ib_delete-cta" title="Delete"></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function ($) {
            $("#ib_cta").ib_ctaLists();
        });
    </script>
</div>