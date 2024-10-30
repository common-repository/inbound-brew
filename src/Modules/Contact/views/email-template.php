<script type="text/javascript">
    <?php
    $arr = isset($form_fields)? json_decode($form_fields):array();?>
    var field_array = <?php echo !empty($arr)?$form_fields:'undefined'; ?>;
</script>

<div id="ib_mail">
    <div class="ib-row">
        <div class="ib-column ib-column-6">
            <form action="/wp-admin/admin-post.php" method="post" id="email_template_form">
                <div class="ib-row" style="margin:1em; border:2px solid #0083CA;">
                    <div class=" ib-th">
                        <b>Email Title</b>
                    </div>
                    <div class="ib-td">
                        <input class="ib-input-full" value="<?php echo isset($mail->email_title)?$mail->email_title:'';?>" type="text" name="email_title" size="30" placeholder="Email Tile" id="email_title" spellcheck="true" autocomplete="off" required>
                    </div>

                    <div class="ib-th">
                        <b>Email Content</b>
                    </div>
                    <div class="ib-td">
                        <?php
                        if (empty($mail->email_value)) {
                            $content = "<p>Dear {{name}},</p>
                                        <p>[ content of the email this may include some contact form data.
                                        While you are able to add media or any other content that is supported by tinymce editor,
                                        we suggest you stick to just text. *short codes will not work here ]</p>
                                        <p>[thank you part]</p>
                                        <p><a href='{{download_link}}' title='Click to Download'>link</a> for download</p>";
                        } else {
                            $content = $mail->email_value;
                        }
                        $args = array(
                            'wpautop'          => false,
                            'textarea_rows'    => 15,
                            'textarea_name'    => 'email_value',
                            'drag_drop_upload' => true
                        );
                        wp_editor($content, 'email_value', $args);
                        ?>
                    </div>

                    <div class="ib-th">
                        <b>Email Subject</b>
                    </div>
                    <div class="ib-td">
                        <textarea class="ib-cf-textarea" name="email_subject" id="email_subject" placeholder="a short description of your email" required><?php echo isset($mail->email_subject)?$mail->email_subject:''; ?></textarea>
                    </div>
                    <div class="ib-th">
	                    <b>Send Email To:</b>
	                </div>
	                <div class="ib-td" style="padding-bottom:0px;">
		               <span class="ib-notes"><span class="red">*</span> You can enter multiple emails. Separate each using commas. <strong>{{email}}</strong> is used to represent the lead's email.</span>
		            </div>
		            <div class="ib-td">
			            <label class="ib-label">To:</label>
			            <?php $send_to = (!empty($mail->send_to))? $mail->send_to : "{{email}},"; ?>
			            <textarea name="send_to" style="width:99%" id="send_to" required><?php echo $send_to; ?></textarea>
			        </div>
			        <div class="ib-td">
			            <label class="ib-label">CC:</label>
			            <textarea name="send_cc" style="width:99%"><?php echo @$mail->send_cc; ?></textarea>
			        </div>
			        <div class="ib-td">
			            <label class="ib-label">BCC:</label>
			            <textarea name="send_bcc" style="width:99%"><?php echo @$mail->send_bcc; ?></textarea>
			        </div>
                </div>
                <div style="margin:1em;">
                    <?php wp_nonce_field('save_email_template'); ?>
                    <input id="email_id" type="hidden" name="email_id" value="<?php echo isset($mail->email_id)?$mail->email_id:'';?>" />
                    <input type="hidden" name="action" value="save_email_template" />
                    <input id="save-email-template-button" type="submit" class="ib-button" value="SAVE" />
                    <input class="ib-button cancel" type="button" value="DELETE" onclick="window.location='admin-post.php?action=delete_email_template&email_id=<?php echo $mail->email_id; ?>'; return false;">
                </div>
            </form>
        </div>
        <div class="ib-column ib-column-3">
            <div class="ib-row" style="margin:1em; border:2px solid #0083CA;">
                <div class="ib-th">
                    <b>Used Form Fields</b>
                </div>
                <div data-role="email-template-holder" style="margin: 6px 0 0;padding: 0 12px 12px;">
                    <div id="token-error" class="error" style="display:none;"></div>
                    <?php foreach($tokens as $token): ?>
                    <div data-token="<?php echo $token->field_token; ?>" id="<?php echo $token->field_id; ?>" class="ib-pill-small <?php echo(is_array(@$field_array) && in_array($token->field_id,@$field_array))?'ib-used':'';?> "><?php echo $token->field_name; ?></div>
                    <?php endforeach; ?>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="ib-row" style="margin:1em; border:2px solid #0083CA;">
                <div class="ib-th">
                    <b>Contact Forms attached to this Email</b>
                </div>
                <?php if(count(@$forms)): $i=0;?>
                    <?php foreach($forms as $form): $i++;?>
                    <div class="ib-td<?php echo($i % 2)?' grey':'';?>" id="post_<?php echo $form->post->ID; ?>"><a target="_blank" href="/wp-admin/post.php?post=<?php echo $form->post->ID; ?>&action=edit"><?php echo $form->post->post_title; ?></a></div>
                    <?php endforeach; ?>
                <?php else: ?>
                <div class="ib-td"><b>There are currently no Contact Forms associated with this email</b></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$("#email_template_form").validate({
			rules:{
				email_title:"required",
				LeadEmail:{
					required:true,
					email:true
				},
			submitHandler:function(form){
				form.submit();
	    	}}
		});
	});
</script>