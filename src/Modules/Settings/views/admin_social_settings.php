<div class="ib-tabs" id="ib-tabs">
    <?php
    echo $Layout->element($partials_path . "settings_tabs", array(
        'post_type' => $post_type,
        'active' => "Social Settings"));
    ?>
    <div class="tabs">
        <!-- Manage Redirect Tabs -->
        <div class="tab ib-padding">
            <?php $now = date("Y-m-d H:i:s"); ?>
            <div class="ib-header">
                Social Network URLs:
            </div>
            <p class="ib-instructions" style="margin-bottom:10px;">Please provide URLs to your social network pages. These are used in the Email Template Module.</p>
            <div class="ib-row">
                <?php
                echo $Form->create("ib_save_social_urls", array('url'=>admin_url("admin-post.php")));
                wp_nonce_field('ib_save_social_urls_nonce');
                ?>
                <div class="ib_editor-fields">
                    <!-- facebook -->
                    <div class="ib_label">Facebook:</div>
                    <div class="ib_fields">
                        <?php
                        echo $Form->text("Setting.social_url_facebook", array(
                            'div' => false,
                            'style' => "width:300px"));
                        ?>
                    </div>
                    <div class="clear"></div>
                    <!-- twitter -->
                    <div class="ib_label">Twitter:</div>
                    <div class="ib_fields">
                        <?php
                        echo $Form->text("Setting.social_url_twitter", array(
                            'div' => false,
                            'style' => "width:300px"));
                        ?>
                    </div>
                    <div class="clear"></div>
                    <!-- Linked In -->
                    <div class="ib_label">LinkedIn:</div>
                    <div class="ib_fields">
                        <?php
                        echo $Form->text("Setting.social_url_linkedin", array(
                            'div' => false,
                            'style' => "width:300px"));
                        ?>
                    </div>
                    <div class="clear"></div>
                    <!-- google plus -->
                    <div class="ib_label">Google+:</div>
                    <div class="ib_fields">
                        <?php
                        echo $Form->text("Setting.social_url_google_plus", array(
                            'div' => false,
                            'style' => "width:300px"));
                        ?>
                    </div>
                    <div class="clear"></div>
                    <div class="ib-margin-top"><button id="widget_submit" class="ib-button">Save Urls</button></div>
                </div>
                <?php echo $Form->end(); ?>
            </div>
            <div class="ib_settings-divider"></div>
            <!-- Social Network Connections -->
            <div class="ib-header">Social Network Connections:</div>
            <!-- facebook -->
            <div class="ib-row ib-social-connection">
                <div class="icon"><span class="fa fa-facebook"></span></div>
                <div class="network">Facebook</div>
                <?php
                if ($settings->social_connected_facebook):
                    // calculate expiration
                    $diff = strtotime($settings->social_connected_facebook) - strtotime($now);
                    $days = floor($diff / 86400);
                    //links
                    $disconnect_url = wp_nonce_url("admin.php?page={$post_type}&section=ib_disconnect_network&network=facebook", "ib-disconnect-network");
                    $posting_settings = "admin.php?page={$post_type}&section=ib_social_post_settings&network=facebook";
                    $posting_history = "admin.php?page={$post_type}&section=ib_social_posting_records&network=facebook";
                    $settings_url = "admin.php?page={$post_type}&section=posting_settings&network=facebook";
                    ?>
                    <div class="details">
                        <span class="ib_connection-name">Connected as <?php echo $settings->social_name_facebook; ?></span> <span class="ib-notes">(<?php echo sprintf("Expires in %s days", $days); ?>)</span><br>
                        <a href="<?php echo $facebook_login_url; ?>" class="ib_connect-network" data-value="Facebook">Update Connected Pages</a>&nbsp;&nbsp;&#124;&nbsp;&nbsp;
                        <a href="<?php echo $posting_settings; ?>">Posting Settings</a>&nbsp;&nbsp;&#124;&nbsp;&nbsp;
                        <a href="<?php echo $posting_history; ?>">History</a>
                    </div>
                <?php endif; ?>
                <div class="buttons">
                    <?php if ($settings->social_connected_facebook): ?>
                        <a href="<?php echo $facebook_login_url; ?>" class="ib_connect-network ib-button" data-value="Facebook">Re-validate</a>
                        <a href="<?php echo $disconnect_url; ?>" class="ib-disconnect-network ib-button cancel" data-value="Facebook">Disconnect</a>
                    <?php else: ?>
                        <a href="<?php echo $facebook_login_url; ?>" class="ib_connect-network ib-button">Connect</a>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
            <!-- Twitter -->
            <div class="ib-row ib-social-connection">
                <div class="icon"><span class="fa fa-twitter"></span></div>
                <div class="network">Twitter</div>
                <?php
                if ($settings->social_connected_twitter):
                    // calculate expiration
                    if ($settings->social_connected_twitter == "0000-00-00 00:00:00"):
                        $exp = "Never";
                    else:
                        $diff = strtotime($settings->social_connected_twitter) - strtotime($now);
                        $days = floor($diff / 86400);
                        $exp = sprintf("in %s days", $days);
                    endif;
                    //links
                    $disconnect_url = wp_nonce_url("admin.php?page={$post_type}&section=ib_disconnect_network&network=twitter", "ib-disconnect-network");
                    $posting_settings = "admin.php?page={$post_type}&section=ib_social_post_settings&network=twitter";
                    $posting_history = "admin.php?page={$post_type}&section=ib_social_posting_records&network=twitter";
                    $settings_url = "admin.php?page={$post_type}&section=posting_settings&network=twitter";
                    ?>
                    <div class="details">
                        <span class="ib_connection-name">Connected as <?php echo $settings->social_name_twitter; ?></span> <span class="ib-notes">(<?php echo sprintf("Expires %s", $exp); ?>)</span><br>
                        <a href="<?php echo $facebook_login_url; ?>" class="ib_connect-network" data-value="Facebook">Update Connected Pages</a>&nbsp;&nbsp;&#124;&nbsp;&nbsp;
                        <a href="<?php echo $posting_settings; ?>">Posting Settings</a>&nbsp;&nbsp;&#124;&nbsp;&nbsp;
                        <a href="<?php echo $posting_history; ?>">History</a>
                    </div>
                <?php endif; ?>
                <div class="buttons">
                    <?php if ($settings->social_connected_twitter): ?>
                        <a href="<?php echo $twitter_login_url; ?>" class="ib_connect-network ib-button" data-value="Twitter">Re-validate</a>
                        <a href="<?php echo $disconnect_url; ?>" class="ib-disconnect-network ib-button cancel" data-value="Twitter">Disconnect</a>
                    <?php else: ?>
                        <a href="<?php echo $twitter_login_url; ?>" class="ib_connect-network ib-button">Connect</a>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
            <!-- Linked In -->
            <div class="ib-row ib-social-connection">
                <div class="icon"><span class="fa fa-linkedin"></span></div>
                <div class="network">LinkedIn</div>
                <?php
                if ($settings->social_connected_linked_in):
                    // calculate expiration
                    $diff = strtotime($settings->social_connected_linked_in) - strtotime($now);
                    $days = floor($diff / 86400);
                    //links
                    $disconnect_url = wp_nonce_url("admin.php?page={$post_type}&section=ib_disconnect_network&network=linked_in", "ib-disconnect-network");
                    $posting_settings = "admin.php?page={$post_type}&section=ib_social_post_settings&network=linked_in";
                    $posting_history = "admin.php?page={$post_type}&section=ib_social_posting_records&network=linked_in";
                    $settings_url = "admin.php?page={$post_type}&section=posting_settings&network=linked_in";
                    ?>
                    <div class="details">
                        <span class="ib_connection-name">Connected as <?php echo $settings->social_name_linked_in; ?></span> <span class="ib-notes">(<?php echo sprintf("Expires in %s days", $days); ?>)</span><br>
                        <a href="<?php echo $linked_in_login_url; ?>" class="ib_connect-network" data-value="Facebook">Update Connected Pages</a>&nbsp;&nbsp;&#124;&nbsp;&nbsp;
                        <a href="<?php echo $posting_settings; ?>">Posting Settings</a>&nbsp;&nbsp;&#124;&nbsp;&nbsp;
                        <a href="<?php echo $posting_history; ?>">History</a>
                    </div>
                <?php endif; ?>
                <div class="buttons">
                    <?php if ($settings->social_connected_linked_in): ?>
                        <a href="<?php echo $linked_in_login_url; ?>" class="ib_connect-network ib-button" data-value="Twitter">Re-validate</a>
                        <a href="<?php echo $disconnect_url; ?>" class="ib-disconnect-network ib-button cancel" data-value="Twitter">Disconnect</a>
                    <?php else: ?>
                        <a href="<?php echo $linked_in_login_url; ?>" class="ib_connect-network ib-button">Connect</a>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
            <?php
            ?>
            <div class="ib_settings-divider"></div>

            <?php
            ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $(".ib-disconnect-network").click(function (e) {
            e.preventDefault();
            var $me = $(this);
            var network = $me.attr('data-value');
            var href = $me.attr('href');
            $.confirm({
                title: "Disconnect " + network,
                content: "Are you sure you want to disconnect your " + network + " connection? The system won't be able to post to " + network + ".",
                confirm: function () {
                    window.location.href = href;
                },
                confirmButton: 'Disconnect',
                cancelButton: 'CANCEL',
                confirmButtonClass: 'ib_save',
                cancelButtonClass: 'ib_cancel'
            });
        });
    });
</script>