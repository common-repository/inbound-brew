<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 11/6/15
 * Time: 4:45 PM
 */
?>
<script>
    (function($) {
        $(document).ready(function () {
            var file_frame;
            $('#upload_download_content').click(function(e){
                e.preventDefault();
                if(file_frame) file_frame.open();
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Select Downloadable Content',
                    button: {
                        text: 'Add'
                    },
                    multiple: false
                });
                file_frame.on( 'select', function() {
                    attachment = file_frame.state().get('selection').first().toJSON();
                    var imgurl = attachment.url;
                    $("#download_content").val(imgurl);
                });
                file_frame.open();
            });
        });
    })(jQuery);
</script>
<div class="ib-row" data-role="email-download-holder">
    <h3>Select Content:</h3>
    <div class="ib-td">
        <input class="ib-input-full" id="download_content" name="download_content" data-name="download-content" type="text" value="<?php echo isset($download_content)?$download_content:'';?>" />
        <input id="upload_download_content" class="ib-button" type="button" value="Attach">
    </div>
    <h3>Download Title:</h3>
    <div class="ib-td">
        <input class="ib-input-full" id="download_title" name="download_title" data-name="download-title" type="text" value="<?php echo isset($download_title)?$download_title:'';?>" />
    </div>
    <h3>Set Rules:</h3>
    <div class="ib-td">
        <label for="expires_after"><b>Set Expiration from Send Date</b></label><br />
        <select class="ib-input-full" name="expires_after">
            <option value="">Never Expires</option>
            <option value="1" <?php echo (isset($expires_after) && $expires_after == 1)?'selected':''; ?>>1 Day</option>
            <option value="2" <?php echo (isset($expires_after) && $expires_after == 2)?'selected':''; ?>>2 Days</option>
            <option value="3" <?php echo (isset($expires_after) && $expires_after == 3)?'selected':''; ?>>3 Days</option>
            <option value="4" <?php echo (isset($expires_after) && $expires_after == 4)?'selected':''; ?>>4 Days</option>
            <option value="5" <?php echo (isset($expires_after) && $expires_after == 5)?'selected':''; ?>>5 Days</option>
        </select>
    </div>
    <div class="ib-td">
        <lable for="download_limit"><b>Total number of Downloads</b></lable>
        <select class="ib-input-full" name="download_limit">
            <option value="">Unlimited Downloads</option>
            <option value="1" <?php echo (isset($download_limit) && $download_limit == 1)?'selected':''; ?>>1 Download</option>
            <option value="2" <?php echo (isset($download_limit) && $download_limit == 2)?'selected':''; ?>>2 Downloads</option>
            <option value="3" <?php echo (isset($download_limit) && $download_limit == 3)?'selected':''; ?>>3 Downloads</option>
            <option value="4" <?php echo (isset($download_limit) && $download_limit == 4)?'selected':''; ?>>4 Downloads</option>
            <option value="5" <?php echo (isset($download_limit) && $download_limit == 5)?'selected':''; ?>>5 Downloads</option>
        </select>
    </div>
</div>