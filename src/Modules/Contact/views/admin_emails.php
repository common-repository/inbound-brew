<div id="ib_emails">
    <?php echo $Layout->element($partials_path . "list_actions", array('post_type' => $post_type)); ?>
    <div class="ib-tabs" id="ib-tabs">
        <?php
        echo $Layout->element($partials_path . "list_tabs", array(
            'post_type' => $post_type,
            'active' => "emails"));
        ?>
        <div class="tabs">
            <div>
                <table cellpadding="0" cellspacing="0" width="100%" class="ib_data-tables stripe">
                    <thead>
                        <tr>
                            <!-- <th>ID</th> -->
                            <th>Email Name</th>
                            <th>Subject Line</th>
                            <th>Contact Forms</th>
                            <?php 
?>
                            <th>Last Updated</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody id="ib_cta_list">
                        <?php
                        $i = 0;
                        $contact_forms = array();
                        foreach ($emails as $email):
                            $emailId = $email->email_id;
                            $edit_url = "admin.php?page={$post_type}&section=edit_email&email_id={$emailId}";
                            $trash_url = wp_nonce_url("admin.php?page={$post_type}&section=delete_email&email_id={$email->email_id}", "ib_delete_email_nonce");
                            $clone_url = "admin.php?page={$post_type}&section=clone_email&email_id={$emailId}";
                            $preview_url = "admin.php?page={$post_type}&section=emails_list&action=ib_preview_email&email_id={$emailId}";
                            if ($i++ % 2 == 0)
                                $class = "alt0";
                            ?>
                            <tr id="ib_email-row-<?php echo $emailId; ?>">
                                <!-- <td><?php echo $emailId; ?></td> -->
                                <td><?php echo $email->email_title; ?></td>
                                <td><?php echo $email->email_subject; ?></td>
                                <td><?php
                                    $linkedForms = $email->contactForms();
                                    if ($count = count($linkedForms)):
                                        $contact_forms["email_" . $emailId] = $linkedForms;
                                        ?>
                                        <a href="" data-email="<?php echo $emailId; ?>" class="ib_linked-forms"><?php echo $count; ?></a>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <?php 
?>
                                <td><?php echo $Date->format(BREW_WP_DATE_FORMAT, $email->updated_at, true); ?>
                                <td class="ib_tools">
                                    <a href="<?php echo $clone_url; ?>" class="ib_icon-link fa fa-clone" title='Clone'></a>
                                    <a href="<?php echo $edit_url; ?>" class="ib_icon-link fa fa-pencil" title='Edit'></a>
                                    <a href="<?php echo $trash_url; ?>" class="ib_icon-link delete fa fa-trash ib_delete-email" title='Delete'></a>
                                    <a href="<?php echo $preview_url; ?>"  data-title="<?php echo str_replace('"', "&quot;", $email->email_subject); ?>"  class="ib_icon-link delete fa fa-eye ib_preview"  title='Preview'></a>
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
            $("#ib_emails").ib_emailsLists({
                list: "emails",
                nonce: "<?php echo wp_create_nonce("ib-email-preview-nonce"); ?>",
                contact_forms: <?php echo json_encode($contact_forms, JSON_NUMERIC_CHECK) ?>
            });

            
        });



        /* --inbound-brew-free-start-- */ 
        var dtConfig = {
                            "columnDefs": [
                                { "orderable": false, "targets": [0, 1, 3] }
                            ],
                            "order": [[3, "desc"]],
                            "pageLength": 25,
                            asStripeClasses : []
                        };
        /* --inbound-brew-free-end-- */ 

    </script>
</div>