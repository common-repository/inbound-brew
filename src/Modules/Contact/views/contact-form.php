<script type="text/javascript">
    var countries = <?php echo $countries; ?>;
    var states = <?php echo $states; ?>;
</script>
<style>
    input[data-type='submit'] {
        background-color: #0083CA;
        color: white;
        padding: .5em 1em;
        border: none;
    }
    div[data-role='add-field'] {
        cursor: pointer;
    }
    div[data-role='add-field'].disabled{
        cursor: auto !important;
        color: #9a9a9a;
        text-decoration: line-through;
    }
</style>
<div>
    <?php
    echo $Form->create("ib_contact_form", array('url' => $submit_url));
    wp_nonce_field('ib_contact_form');
    ?>
    <div id="post-body-content">
        <div id="titlediv">
            <div id="titlewrap">
                <input type="text" spellcheck="true" id="title" value="<?php if (isset($post_title)) echo $post_title; ?>" size="30" name="post_title" placeholder="Enter Form Name" required>
            </div>
        </div>
    </div>
    <div id="ib-contact-form">
        <!--div class="ib-row ib-td">
            Short Code: <span style="font-size:large;font-weight:600">[brew_cf id=<?php echo $id; ?>]</span><a id="ib-help" data-type="short-code"> what is a shortcode?</a>
        </div-->
        <div class="ib-row" data-role="form-builder">
            <div class="ib-column ib-column-5">
                <div class="tag-generator">
                    <div class="ib-row">
                        <div class="ib-column ib-column-12 ib-cf-wrapper">
                            <div class="ib-row ib-th">
                                <b>Lead Fields:</b>
                            </div>
                            <?php
                            $i = 1;
                            if (isset($lead_fields)):
                                foreach ($lead_fields as $form):
                                    ?>
                                    <div class="ib-row" data-role="field-holder">
                                        <div id="<?php echo$form->field_id; ?>" class="ib-column ib-column-11 ib-td br" data-role="add-field" data-context="<?php echo $form->field_type; ?>" data-value="<?php echo $form->field_token; ?>" data-name="<?php echo $form->field_name; ?>"><?php echo $form->field_name; ?></div>
                                        <div class="ib-column ib-column-1 ib-td">
                                            <i data-role="add-field" data-context="<?php echo $form->field_type; ?>" data-value="<?php echo $form->field_token; ?>" data-name="<?php echo $form->field_name; ?>" class="fa fa-plus ib-orange"></i>
                                            <i data-role="remove-field" data-value="<?php echo $form->field_token; ?>" data-target="<?php echo $form->field_token; ?>" class="fa fa-minus ib-orange" style="display: none;"></i>
                                        </div>
                                    </div>
                                    <?php
                                    $i++;
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </div>

                    <div class="ib-row">
                        <div class="ib-column ib-column-12" style="border:2px solid #0083CA;";>
                            <div class="ib-th"><b>Confirmation Fields:</b></div>
                            <div class="ib-row" data-role="field-holder">
                                <div class="ib-row" data-role="field-holder">
                                    <div class="ib-column ib-column-11 ib-td br <?php
                                    echo($i % 2) ? ' grey' : '';
                                    $i++;
                                    ?>" data-role="add-field" data-context="acceptance" data-value="acceptance">Acceptance</div>
                                    <div class="ib-column ib-column-1 ib-td">
                                        <i data-role="add-field" data-context="acceptance" data-value="acceptance" class="fa fa-plus ib-orange"></i>
                                        <i data-role="remove-field" data-target="acceptance" data-value="acceptance" class="fa fa-minus ib-orange" style="display: none;"></i>
                                    </div>
                                </div>
                                <div class="ib-row" data-role="field-holder">
                                    <div class="ib-column ib-column-11 ib-td br <?php
                                    echo($i % 2) ? ' grey' : '';
                                    $i++;
                                    ?>" data-role="add-field" data-context="captcha" data-value="captcha">CAPTCHA</div>
                                    <div class="ib-column ib-column-1 ib-td">
                                        <i data-role="add-field" data-context="captcha" data-value="captcha" class="fa fa-plus ib-orange"></i>
                                        <i data-role="remove-field" data-target="captcha" data-value="captcha" class="fa fa-minus ib-orange" style="display: none;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ib-row">
                        <div class="ib-column ib-column-12" style="border:2px solid #0083CA;";>
                            <div class="ib-row ib-th"><b>Custom Lead Fields</b></div>
                            <div data-role="custom-field-wrapper">
                                <?php
                                if (isset($custom_fields)):
                                    foreach ($custom_fields as $form):
                                        ?>
                                        <div class="ib-row" data-role="field-holder">
                                            <div id="<?php echo $form->field_id; ?>" class="ib-column ib-column-11 ib-td br form-option <?php echo($i % 2) ? ' grey' : ''; ?>" data-role="add-field" data-context="<?php echo $form->field_type; ?>" data-value="<?php echo strtolower($form->field_token); ?>"><?php echo $form->field_name; ?></div>
                                            <div class="ib-column ib-column-1 ib-td">
                                                <i data-role="add-field" data-context="<?php echo $form->field_type; ?>" data-value="<?php echo strtolower($form->field_token); ?>" data-name="<?php echo addslashes($form->field_name); ?>" class="fa fa-plus ib-orange"></i>
                                                <i data-role="remove-field" data-target="<?php echo strtolower($form->field_token); ?>" data-value="<?php echo strtolower($form->field_token); ?>" class="fa fa-minus ib-orange" style="display: none;"></i>
                                            </div>
                                        </div>
                                        <?php
                                        $i++;
                                    endforeach;
                                endif;
                                ?>
                            </div>
                            <div class="ib-row" style="border-top:2px solid #0083CA;padding:4px;">
                                <button type="button" class="ib-button green" data-role="add-lead-field">+ Create New Custom Lead Field</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ib-column ib-column-7">
                <div class="ib-row">
                    <div class="ib-cf-wrapper">
                        <div class="ib-th"><b>Form Preview:</b></div>
                        <div data-role="form-display-wrapper" class="form-display-wrapper">
                            <?php
                            if (isset($post_content) && !empty($post_content)):
                                echo $post_content;
                            else:
                                ?>
                                <div data-role="input-wrapper" id="ib-sortable" class="editable">
                                    <div class="ib-form" data-id="email" id="cf-1">
                                        <label for="email" class="ib-required">Email</label><br />
                                        <input type="text" data-type="email" name="email" class="ib-required"/>
                                    </div>
                                </div>
                                <div class="ib-form">
                                    <input type="submit" data-type="submit" value="<?php echo isset($button_text) ? $button_text : 'Button Text'; ?>" name="submit"/>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="ib-td ib-divider">
                            <label for="button_text" class="ib-label">Form Button Text:</label>
                            <input name="button_text" data-role="button-text" id="button_text" type="text" value="<?php echo isset($button_text) ? $button_text : 'Button Text'; ?>" required>
                        </div>
                        <div id="input_holder">
                            <?php
                                $ff = InboundBrew\Modules\Core\Models\FormField::where('field_token', '=', 'email')->first();
                                echo "<input type='hidden' id='lf-".$ff->field_id."' name='leadfield[]'' value='".$ff->field_id."'>";

                                if (!empty($form_fields->fields)):
                                    foreach ($form_fields->fields as $cf):
                                        if ($cf->field_id != 1 && $cf->field_id != 2):
                                            ?>
                                            <input type="hidden" id="lf-<?php echo $cf->field_id; ?>" name="leadfield[]" value="<?php echo $cf->field_id; ?>">
                                            <?php
                                        endif;
                                    endforeach;
                                endif;
                            ?>
                            <input type="hidden" data-role="cf-input" id="form_content" name="post_content" value="<?php echo (isset($post_content) && !empty($post_content)) ? htmlspecialchars($post_content) : ''; ?>" />
                        </div>
                    </div>

                    <div id="ib_cf_email_template" class="ib-cf-wrapper">
                        <div class="ib-th"><b>After Submit Options:</b></div>
                        <div data-role="email-template-holder">
                            <div class="ib-th-dotted" id="ib_dont_send_email_header">Email Options:</div>
                            <?php $i = 0; ?>
                            <div class="ib-row ib-td <?php echo($i % 2) ? ' grey' : ''; ?>">
                                <div class="ib-column ib-column-12">
                                    <input type="checkbox" class='email_template_group' name="dont_send_email_template" value="1" id="ib_dont_send_email" <?php if (@$dont_send_email_template) echo "checked" ?> 
                                        />
                                    <label for="dont_send_email_template">Don't Send an Email</label>
                                </div>
                            </div>
                            <?php $i++; ?>
                            <?php if ($templates): ?>
                                <div id="ib_email_templates">
                                    <div class="ib-row ib-td <?php echo($i % 2) ? ' grey' : ''; ?>" style="padding-top:0px;padding-bottom:0px;">
                                        <strong>Choose what emails should be sent when form is submitted:</strong>
                                        <span id="ib_email_options_header"></span>
                                    </div>
                                    <?php
                                    foreach ($templates as $template):
                                        $checked = "";
                                        if (@$email_template):
                                            $checked = (in_array($template->email_id, $email_template)) ? "checked" : "";
                                        endif;
                                        ?>
                                        <div class="ib-row ib-td <?php echo($i % 2) ? ' grey' : ''; ?>">
                                            <div class="ib-column ib-column-8">
                                                <input type="checkbox" class='email_template_group' name="email_template[]" value="<?php echo $template->email_id; ?>" id="template-<?php echo $template->email_id; ?>" <?php echo $checked; ?>/>
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
                                                        if (empty($email_address))
                                                            continue;
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
                                                            if (empty($email_address))
                                                                continue;
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
                                                            if (empty($email_address))
                                                                continue;
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
                                                <a class="ib-link ib_cf_email_preview" title="View Email" href="admin.php?page=ib-email-admin&section=emails_list&action=ib_preview_email&email_id=<?php echo $template->email_id; ?>" data-title="<?php echo str_replace('"', "&quot;", $template->email_subject); ?>">[view]</a>
                                            </div>
                                        </div>
                                        <?php
                                        $i++;
                                    endforeach;
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- HERE -->
                        <div data-role="download-content-holder">
                            <div class="ib-th-dotted"><b>Downloadable Content:</b></div>
                            <div class="ib-td ib-row">
                                <?php $checked = (!empty($download_content) || @$allow_download) ? "checked" : ""; ?>
                                <input type="checkbox" id="allow-download" name="allow_download" value="1" <?php echo $checked; ?>><strong>Provide a downloadable file after form is submitted.</strong>
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
                                        <input id="upload_download_content" class="ib-button green" type="button" value="Attach">
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
                                        <select class="ib-input-full" name="expires_after">
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
                                        <select class="ib-input-full" name="download_limit">
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
                        <!-- HERE -->
                        <div data-role="thank-you-content-holder">
                            <div class="ib-th-dotted"><b>Thank You Message:</b></div>
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
                    </div>
                    <?php
                    ?>
                    <div class="ib-margin-top">
                        <button class="ib-button save" id="submit_form"><?php echo $submit_button_title; ?></button>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <?php echo $Form->end(); ?>
</div>
<!-- HIDDEN ITEMS -->
<div class="ib-hidden" data-role="field-holder">
    <div id="shortcode-help">
        <h2>What is a shortcode?</h2>
        <p>
            A shortcode is a WordPress-specific code that lets you do nifty things with very little effort. Shortcodes can embed files or create objects that would normally require lots of complicated, ugly code in just one line. Shortcode = shortcut.
        </p>
        <p>for more information please see <a href="https://codex.wordpress.org/Shortcode" target="_blank">WordPress Shordcodes</a></p>
    </div>
</div>
<div id="contact-form-dialog" title="Add Custom Lead Field">
    <div class="ib-row" style="border-bottom:1px dashed #B7B7B7"></div>
    <div class="ib-row">
        <div class="ib-column ib-column-3 ib-td">
            <label for="field_type">Type: </label>
        </div>
        <div class="ib-column ib-column-9 ib-td">
            <select data-role="form-type" name="context">
                <option value="">Select Type</option>
                <option value="text">Text</option>
                <option value="date">Date</option>
                <option value="textarea">Textarea</option>
                <option value="radio">Radio</option>
                <option value="checkbox">Checkbox</option>
                <option value="select">Select</option>
            </select>
        </div>
    </div>
    <div data-role="field-options-holder"></div>
    <div id="add-error"></div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
    });
</script>