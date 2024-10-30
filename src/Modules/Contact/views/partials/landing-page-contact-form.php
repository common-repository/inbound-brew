<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 11/30/15
 * Time: 2:51 PM
 */
?>
<?php $i = 0; ?>
<div id="ib_cf_options" style="margin-top:1em; border:2px solid #0083CA;">
    <div class="ib-th"><b>Form Options</b></div>
    <div class="ib-row ib-td <?php echo($i % 2) ? ' grey' : ''; ?>">
        <div class="ib-column ib-column-8">
            <input class="ib-form-option" type="radio" name="ib_contact_form" value="0" id="form-0" checked/>
            <label for="form-0">Do not use a Contact Form</label>
        </div>
    </div>
    <?php $i++; ?>
    <?php foreach ($forms as $form): ?>
        <div class="ib-row ib-td <?php echo($i % 2) ? ' grey' : ''; ?>">
            <div class="ib-column ib-column-8">
                <input type="radio" class='ib-form-option' name="ib_contact_form" value="[brew_cf id=<?php echo $form->ID; ?>]" id="form-<?php echo $form->ID; ?>" <?php echo (isset($ib_contact_form) && $ib_contact_form == '[brew_cf id=' . $form->ID . ']') ? 'checked' : ''; ?>/>
                <label for="form-<?php echo $form->ID; ?>"><?php echo $form->post_title; ?></label>
            </div>
            <div class="ib-column ib-column-4">
                <div class="fr">

                    <a class="ib-link" title="View Template" target="_blank" href="admin.php?page=ib-contact-forms&section=edit&cf_id=<?php echo $form->ID; ?>">[view/edit]</a>
                </div>
            </div>
        </div>
        <?php $i++; ?>
    <?php endforeach; ?>
</div>
<div id="ib_cf_email_template" style="margin-top:1em; border:2px solid #0083CA;">
    <div class="ib-th"><b>Email Options</b></div>
    <div data-role="email-template-holder">
        <div class="ib-th-dotted">Email Options:</div>
        <?php $i = 0; ?>
        <div class="ib-row ib-td <?php echo($i % 2) ? ' grey' : ''; ?>">
            <div class="ib-column ib-column-12">
                <input type="checkbox" name="dont_send_email_template" value="1" id="ib_dont_send_email" <?php if (@$dont_send_email_template) echo "checked" ?> />
                <label for="dont_send_email_template">Don't Send an Email</label>
            </div>
        </div>
        <?php $i++; ?>
        <?php if ($emails): ?>
            <div id="ib_email_templates">
                <div class="ib-row ib-td <?php echo($i % 2) ? ' grey' : ''; ?>" style="padding-top:0px;padding-bottom:0px;">
                    <strong>Choose what emails should be sent when form is submitted:</strong>
                </div>
                <?php
                foreach ($emails as $template):
                    $checked = "";
                    if (@$email_template):
                        $checked = (in_array($template->email_id, $email_template)) ? "checked=\"checked\"" : "";
                    endif;
                    ?>
                    <div class="ib-row ib-td <?php echo($i % 2) ? ' grey' : ''; ?>">
                        <div class="ib-column ib-column-8">
                            <input type="checkbox" name="email_template[]" value="<?php echo $template->email_id; ?>" id="template-<?php echo $template->email_id; ?>" <?php echo $checked; ?>/>
                            <label for="template-<?php echo $template->email_id; ?>"><?php echo $template->email_title; ?></label>
                        </div>
                        <div class="ib-column ib-column-4">
                            <div class="ib-link fr" data-role="<?php echo $template->email_id; ?>">[details]</div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="ib-row" data-role="template-info" style="padding: 1em;border-top: 2px solid #0083CA;border-bottom: 2px solid #0083CA;display:none;margin-bottom:1em;" id="template-details-<?php echo $template->email_id; ?>">
                        <div class="ib-row ib-bottom-dashed">
                            <div class="ib-column ib-column-2 ib-td"><b>Email Subject:</b></div>
                            <div class="ib-column ib-column-10 ib-td bl">
                                <?php echo $template->email_subject; ?>
                            </div>
                        </div>
                        <div class="ib-row ib-bottom-dashed">
                            <div class="ib-column ib-column-2 ib-td"><b>Form Fields:</b></div>
                            <div class="ib-column ib-column-10 ib-td bl">
                                <?php foreach ($template->fields as $field): ?>
                                    <div class="ib-pill-small" data-token="<?php echo $field->field_token; ?>"><?php echo $field->field_name; ?></div>
                                <?php endforeach; ?>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="ib-row ib-bottom-dashed">
                            <div class="ib-column ib-column-2 ib-td"><b>Send To:</b></div>
                            <div class="ib-column ib-column-10 ib-td bl">
                                <?php
                                $send_to = ($template->send_to) ? $template->send_to : "{{email}}";
                                $to_arr = explode(",", $send_to);
                                foreach ($to_arr as $email_address):
                                    ?>
                                    <div class="ib-pill-small"><?php echo $email_address; ?></div>
                                <?php endforeach; ?>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <?php if ($template->send_cc): ?>
                            <div class="ib-row ib-bottom-dashed">
                                <div class="ib-column ib-column-2 ib-td"><b>Send CC:</b></div>
                                <div class="ib-column ib-column-10 ib-td bl"><?php
                                    $cc_arr = explode(",", $template->send_cc);
                                    foreach ($cc_arr as $email_address):
                                        ?>
                                        <div class="ib-pill-small"><?php echo $email_address; ?></div>
                                    <?php endforeach; ?>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($template->send_bcc): ?>
                            <div class="ib-row ib-bottom-dashed">
                                <div class="ib-column ib-column-2 ib-td"><b>Send BCC:</b></div>
                                <div class="ib-column ib-column-10 ib-td bl"><?php
                                    $bcc_arr = explode(",", $template->send_bcc);
                                    foreach ($bcc_arr as $email_address):
                                        ?>
                                        <div class="ib-pill-small"><?php echo $email_address; ?></div>
                                    <?php endforeach; ?>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div style="color:red;">
                            <?php
                            if (!$template->email_download_link):
                                echo _('Template does not have a Download Link');
                            endif;
                            ?>
                        </div>
                        <div class="ib-td">
                            <a class="ib-link" title="View Template" href="admin.php?page=ib-email-admin&section=template&email_id=<?php echo $template->email_id; ?>">[view]</a>
                        </div>
                    </div>
                    <?php
                    $i++;
                endforeach;
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div data-role="download-content-holder" style="margin-top:1em; border:2px solid #0083CA;">
    <div class="ib-th"><b>Downloadable Content</b></div>
    <div class="ib-td ib-row">
        <?php $checked = (!empty($download_content) || @$allow_download) ? "checked=\"checked\"" : ""; ?>
        <input type="checkbox" id="ib_allow-download" name="allow_download" value="1" <?php echo $checked; ?>><strong>Provide a downloadable file after form is submitted.</strong>
    </div>
    <div id="ib_download_settings">
        <div class="ib-td ib-row <?php
        echo($i % 2) ? ' grey' : '';
        $i++;
        ?>">
            <div class="ib-column ib-column-4">
                Download Content:
            </div>
            <div class="ib-column ib-column-6">
                <input class="ib-input-full" id="download_content" name="download_content" data-name="download-content" type="text" value="<?php echo isset($download_content) ? $download_content : ''; ?>" />
            </div>
            <div class="ib-column ib-column-2">
                <input id="upload_download_content" class="ib-button" type="button" value="Attach">
            </div>
        </div>
        <div class="ib-td ib-row <?php
        echo($i % 2) ? ' grey' : '';
        $i++;
        ?>">
            <div class="ib-column ib-column-4">
                <label for="expires_after">Set Expiration from Send Date</label>
            </div>
            <div class="ib-column ib-column-6">
                <select class="ib-input-full" name="expires_after" id="expires_after">
                    <option value="">Never Expires</option>
                    <option value="1" <?php echo (isset($expires_after) && $expires_after == 1) ? 'selected' : ''; ?>>1 Day</option>
                    <option value="2" <?php echo (isset($expires_after) && $expires_after == 2) ? 'selected' : ''; ?>>2 Days</option>
                    <option value="3" <?php echo (isset($expires_after) && $expires_after == 3) ? 'selected' : ''; ?>>3 Days</option>
                    <option value="4" <?php echo (isset($expires_after) && $expires_after == 4) ? 'selected' : ''; ?>>4 Days</option>
                    <option value="5" <?php echo (isset($expires_after) && $expires_after == 5) ? 'selected' : ''; ?>>5 Days</option>
                </select>
            </div>
            <div class="ib-column ib-column-2">&nbsp;</div>
        </div>
        <div class="ib-td ib-row <?php
        echo($i % 2) ? ' grey' : '';
        $i++;
        ?>">
            <div class="ib-column ib-column-4">
                <lable for="download_limit">Total number of Downloads</lable>
            </div>
            <div class="ib-column ib-column-6">
                <select class="ib-input-full" name="download_limit" id="download_limit">
                    <option value="">Unlimited Downloads</option>
                    <option value="1" <?php echo (isset($download_limit) && $download_limit == 1) ? 'selected' : ''; ?>>1 Download</option>
                    <option value="2" <?php echo (isset($download_limit) && $download_limit == 2) ? 'selected' : ''; ?>>2 Downloads</option>
                    <option value="3" <?php echo (isset($download_limit) && $download_limit == 3) ? 'selected' : ''; ?>>3 Downloads</option>
                    <option value="4" <?php echo (isset($download_limit) && $download_limit == 4) ? 'selected' : ''; ?>>4 Downloads</option>
                    <option value="5" <?php echo (isset($download_limit) && $download_limit == 5) ? 'selected' : ''; ?>>5 Downloads</option>
                </select>
            </div>
            <div class="ib-column ib-column-2">&nbsp;</div>
        </div>
        <?php
        ?>
    </div>
</div>
<div data-role="thank-you-content-holder" style="margin-top:1em; border:2px solid #0083CA;">
    <div class="ib-th"><b>Thank You Message</b></div>
    <div class="ib-row ib-td <?php echo($i % 2) ? ' grey' : ''; ?>">
        <div class="ib-column ib-column-12 ib-td">
            <?php $thank_you_option = isset($thank_you_option) ? $thank_you_option : "message"; ?>
            <input id="ib_thank_you_option_message" type="radio" name="thank_you_option" value="message" id="template-0" <?php if ($thank_you_option == "message") echo "checked"; ?>/>
            <label for="template-0">Show user this message:</label>
        </div>
        <div class="ib-column ib-column-12">
            <textarea name="thank_you_message" placeholder="Thank You Message" style="width:99%" id="thank_you_message" ><?php echo @$thank_you_message; ?></textarea>
        </div>
        <div class="clear"></div>
        <?php if (!empty($published_pages)): ?>
            <div class="ib-column ib-column-12 ib-td">
                <input id="ib_thank_you_option_redirect" type="radio" name="thank_you_option" value="redirect" id="template-0"  <?php if ($thank_you_option == "redirect") echo "checked"; ?>/>
                <label for="template-0">Redirect user to this page:</label>
            </div>
            <div class="ib-column ib-column-12">
                <select name="thank_you_redirect" id="thank_you_redirect">
                    <option value="">Select a Page</option>
                    <?php
                    foreach ($published_pages as $page):
                        $o_select = ($page->ID == @$thank_you_redirect) ? "selected" : "";
                        echo "<option value=\"{$page->ID}\" {$o_select}>{$page->post_title}</option>";
                    endforeach;
                    ?>
                </select>
            </div>
            <div class="clear"></div>
        <?php endif; ?>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
    });
</script>