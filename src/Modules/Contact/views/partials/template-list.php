<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 10/29/15
 * Time: 2:56 PM
 */
?>
<script type="text/javascript">
    (function( $ ){
        $(document).ready(function() {

            checkTokenUsage();
            $('#form_content').change(function() {
                checkTokenUsage();
            });

            $(".ib-link").on('click', function (e) {
                var id = $(this).data('role');
                $("#template-details-"+id).toggle("slow", function () {
                    // Animation complete.
                });
            });

            function checkTokenUsage() {
                var tokens = new Array();
                $('div#sortable, div.ib-form').each( function() {
                    tokens.push($(this).data('id'));
                });

                $('#ib_cf_email_template div.ib-pill').each(function () {
                    var $el = $(this);
                    var radio_id = $el.closest('div[data-role="template-info"]').attr("id").replace('template-details-','template-');
                    $('#'+radio_id).prop('disabled', false);
                    if (tokens.indexOf($el.data('token')) !== -1) {
                        $el.addClass('ib-used');
                    } else {
                        if ($('#post_type').length && $('#post_type').val() != 'ib-landing-page') {
                            $el.removeClass('ib-used');
                            $('#' + radio_id).prop('disabled', true);
                            $('#' + radio_id).prop('checked', false);
                        }
                    }
                });
                tokens = [];
            }
        });
    })(jQuery);
</script>
<div class="ib-row" data-role="email-template-holder">
    <fieldset>
        <legend class="screen-reader-text">Email Template Options</legend>
    <?php foreach($templates as $template): ?>
        <div>
            <div class="ib-column ib-column-8">
            <input type="radio" name="email_template" value="<?php echo $template->email_id; ?>" id="template-<?php echo $template->email_id; ?>" <?php echo (isset($email_template) && $email_template == $template->email_id)?'checked':''; ?>/>
            <label for="template-<?php echo $template->email_id; ?>"><?php echo $template->email_title; ?></label>
            </div>
            <div class="ib-column ib-column-4">
                <div class="ib-link fr" data-role="<?php echo $template->email_id; ?>">[details]</div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="ib-column ib-column-12" data-role="template-info" style="padding: 1em;border: 2px solid #0083CA;display:none;margin-bottom:1em;" id="template-details-<?php echo $template->email_id; ?>">
            <div class="ib-row">
                <?php echo $template->email_subject; ?>
            </div>
            <div class="ib-row">
            <?php foreach($template->fields as $field): ?>
                <div class="ib-pill" data-token="<?php echo $field->field_token; ?>"><?php echo $field->field_name; ?></div>
            <?php endforeach; ?>
                <div class="clear"></div>
            </div>
            <div style="color:red;">
                <?php if(!$template->email_download_link):
                    echo _e('Template does not have a Download Link');
                endif; ?>
            </div>
            <div class="ib-td">
                <a class="ib-link" title="View Template" href="admin.php?page=ib-email-admin&section=template&email_id=<?php echo $template->email_id; ?>">[view]</a>
            </div>
        </div>
    <?php endforeach; ?>
    </fieldset>
</div>