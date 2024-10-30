<?php
$now = date("Y-m-d H:i:s");
$column_var = ($settings->wizzard_hide) ? 6 : 4;

?>
<?php
/* --inbound-brew-free-start-- */
?>
<?php
include_once 'dashboard.php';
?>
<?php
/* --inbound-brew-free-end-- */
?>
<div>

    <div class="clear"></div>
    <div class="dashboard-widgets-wrap">
        <div class="metabox-holder">
            <!-- post container 1 -->
            <div class="ib-column ib-column-<?php echo $column_var; ?>"  id="social-network-tokens">
                <!-- social network tokens -->
                <div id="" class="ib-widget">
                    <h3 class="handle">Social Network Tokens</h3>
                    <div class="ib-inside">
                        <table cellpadding="0" cellspacing="0" class="ib_data-tables" width="100%">
                            <tbody>
                                <!-- facebook -->
                                <tr class="alt0">
                                    <td class="text-left">
                                        <div class="ib-list-network-name">
                                            <img src="<?php echo BREW_PLUGIN_IMAGES_URL; ?>/social/logo_facebook.png" width="16px" align="left">Facebook</div>
                                        <?php if ($settings->social_connected_facebook): ?>
                                            <span class="ib-connection-status connected">Connected</span><br>
                                            <span class="ib-notes"><?php echo $settings->social_name_facebook; ?></span>
                                        <?php else: ?>
                                            <span class="ib-connection-status disconnected">Not Connected</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($settings->social_connected_facebook):
                                            $posting_settings = "admin.php?page=ib-admin-settings&section=ib_social_post_settings&network=facebook";
                                            $diff = strtotime($settings->social_connected_facebook) - strtotime($now);
                                            $days = floor($diff / 86400);
                                            echo sprintf("expires in %s days", $days);
                                            ?>
                                            <br><a href="<?php echo $facebook_login_url; ?>">[revalidate]</a>
                                            <a href="<?php echo $posting_settings; ?>">[posting settings]</a>
                                        <?php else: ?>
                                            <a href="<?php echo $facebook_login_url; ?>">[connect]
                                            <?php endif; ?>
                                    </td>
                                </tr>
                                <!-- Twitter -->
                                <tr class="">
                                    <td class="text-left">
                                        <div class="ib-list-network-name">
                                            <img src="<?php echo BREW_PLUGIN_IMAGES_URL; ?>/social/logo_twitter.png" width="16px" align="left">Twitter</div>
                                        <?php if ($settings->social_connected_twitter): ?>
                                            <span class="ib-connection-status connected">Connected</span><br>
                                            <span class="ib-notes">@<?php echo $settings->social_name_twitter; ?></span>
                                        <?php else: ?>
                                            <span class="ib-connection-status disconnected">Not Connected</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($settings->social_connected_twitter == "0000-00-00 00:00:00"):
                                            echo "Never Expires<br><a href=\"{$twitter_login_url}\">[revalidate]</a>";
                                        elseif ($settings->social_connected_twitter):
                                            $diff = strtotime($settings->social_connected_twitter) - strtotime($now);
                                            $days = floor($diff / 86400);
                                            echo sprintf("expires in %s days", $days);
                                            echo "<br><a href=\"{$twitter_login_url}\">[revalidate]</a>";
                                        endif;
                                        if ($settings->social_connected_twitter):
                                            $posting_settings = "admin.php?page=ib-admin-settings&section=ib_social_post_settings&network=twitter";
                                            ?>
                                            <a href="<?php echo $posting_settings; ?>">[posting settings]</a>
                                        <?php else: ?>
                                            <a href="<?php echo $twitter_login_url; ?>">[connect]
                                            <?php endif; ?>
                                    </td>
                                </tr>
                                <!-- LinkedIn -->
                                <tr class="alt0">
                                    <td class="text-left">
                                        <div class="ib-list-network-name"><img src="<?php echo BREW_PLUGIN_IMAGES_URL; ?>/social/logo_linked_in.png" width="16px" align="left">LinkedIn</div>
                                        <?php if ($settings->social_connected_linked_in): ?>
                                            <span class="ib-connection-status connected">Connected</span><br>
                                            <span class="ib-notes"><?php echo $settings->social_name_linked_in; ?></span>
                                        <?php else: ?>
                                            <span class="ib-connection-status disconnected">Not Connected</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($settings->social_connected_linked_in == "0000-00-00 00:00:00"):
                                            echo "Never Expires<br><a href=\"{$linked_in_login_url}\">[revalidate]</a>";
                                        elseif ($settings->social_connected_linked_in):
                                            $diff = strtotime($settings->social_connected_linked_in) - strtotime($now);
                                            $days = floor($diff / 86400);
                                            echo sprintf("expires in %s days", $days);
                                            echo "<br><a href=\"{$linked_in_login_url}\">[revalidate]</a>";
                                        endif;
                                        if ($settings->social_connected_linked_in):
                                            $posting_settings = "admin.php?page=ib-admin-settings&section=ib_social_post_settings&network=linked_in";
                                            ?>
                                            <a href="<?php echo $posting_settings; ?>">[posting settings]</a>
                                        <?php else: ?>
                                            <a href="<?php echo $linked_in_login_url; ?>">[connect]
                                            <?php endif; ?>
                                    </td>

                                </tr>

                                <?php
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!--END social netowork tokens -->
            </div>
            <!-- post container 2 -->
            <div class="ib-column ib-column-<?php echo $column_var; ?>" id="social-network-activity">
                <!-- social network activity -->
                <div class="ib-widget">
                    <h3 class="handle">Social Network Posting History</h3>
                    <div class="ib-inside ib-margin-top">
                        <div class="ib-tabs" id="social_history_tabs">
                            <div class="tab-wrapper">
                                <a class="ib-tab-link" href="#social_history_posted">Posted Recently</a>
                                <a class="ib-tab-link" href="#social_history_soon">Upcoming</a>
                            </div>
                            <div class="tabs">
                                <!-- recently posted -->
                                <div id="social_history_posted">
                                    <?php if (count($social_posted_recently)): ?>
                                        <table class="ib_data-tables" width="100%" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <?php
                                                $i = 0;
                                                foreach ($social_posted_recently as $history):
                                                    $class = "";
                                                    $details_url = "admin.php?page=ib-admin-settings&section=ib_social_posting_records&pid=" . $history['post_setting_id'];
                                                    if ($i++ % 2 == 0)
                                                        $class = "alt0";
                                                    ?>
                                                    <tr class="<?php echo $class; ?>">
                                                        <td width="20"><img src="<?php echo BREW_PLUGIN_IMAGES_URL; ?>/social/logo_<?php echo $history['social_network']; ?>.png" width="16px" align="left"></td>
                                                        <th class="main"><span class='ib-date'><?php echo $Date->format(BREW_WP_DATE_FORMAT . " " . BREW_WP_TIME_FORMAT, $history['updated_at'], true); ?></span><br>
                                                            <a href="<?php echo $details_url; ?>"><?php echo $history['post_title']; ?></a></th>
                                                        <td class="ib-posting-status-<?php echo $history['posting_status']; ?>"><?php echo ucfirst($history['posting_status']); ?></td>
                                                        <td>
                                                            <a href="<?php echo $details_url; ?>">[details]</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <h3>No history posts.</h3>
                                    <?php endif; ?>
                                </div>
                                <!-- soon to be posted -->
                                <div id="social_history_soon">
                                    <?php if (count($social_posting_soon)): ?>
                                        <table class="ib_data-tables" width="100%" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <?php
                                                $i = 0;
                                                foreach ($social_posting_soon as $history):
                                                    $class = "";
                                                    $details_url = "admin.php?page=ib-admin-settings&section=ib_social_posting_records&pid=" . $history['post_setting_id'];
                                                    if ($i++ % 2 == 0)
                                                        $class = "alt0";
                                                    ?>
                                                    <tr class="<?php echo $class; ?>">
                                                        <td width="20"><img src="<?php echo BREW_PLUGIN_IMAGES_URL; ?>/social/logo_<?php echo $history['social_network']; ?>.png" width="16px" align="left"></td>
                                                        <th class="main"><a href="<?php echo $details_url; ?>"><?php echo $history['post_title']; ?></a></th>
                                                        <td><span class='ib-date'><?php echo $Date->format(BREW_WP_DATE_FORMAT . " " . BREW_WP_TIME_FORMAT, $history['post_at'], true); ?></span></td>
                                                        <td>
                                                            <a href="<?php echo $details_url; ?>">[details]</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <h3>No upcoming posts.</h3>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--END social network activity-->
            </div>
            <!-- post container 3 -->
            <?php //if (!$settings->wizzard_hide):                               ?>
<!--                <div class="ib-column ib-column-<?php // echo $column_var;                                                                                                                                                                                                                                                                                                                                                                                                                                                          ?>" id="wizzard-container">
                     get started wizzard
                    <div class="ib-widget">
                        <h3 class="handle"><img src="<?php //echo BREW_PLUGIN_IMAGES_URL;                                                                                                                                                                                                                                                                                                                                                                                                                                                                 ?>icons/icon_wizzard.png" width="24px" align="absmiddle"> Get Started With InboundBrew</h3>
                        <div class="ib-inside">
                            <div class="ib-widget-instructions">To take full advantage of these plugins setup the following items.
                                <br><strong><span class='red'>*</span> We recommend you follow this order.</strong></div>
                            <table class="ib_data-tables" width="100%" cellspacing="0" cellpadding="0">
                                <tbody>
            <?php
//                                    $i = 0;
//                                    $all_clear = true;
//                                    foreach ($wizzard_steps as $step => $values):
//                                        $class = "";
//                                        if ($i++ % 2 == 0)
//                                            $class = "alt0";
//                                        $step = "wizzard_{$step}";
//                                        if ($settings->$step):
//                                            $icon = "icons/icon_wizzard-checked.png";
//                                        else:
//                                            $all_clear = false;
//                                            $icon = "icons/icon_wizzard-unchecked.png";
//                                        endif;
            ?>
                                        <tr class="<?php //echo $class;                                                                                                                                                                                                                                                                                                                                                                                                                                                          ?>">
                                            <td class="ib-wizzard-step-list-name text-left">
                                                <img src="<?php // echo BREW_PLUGIN_IMAGES_URL . $icon;                                                                                                                                                                                                                                                                                                                                                                                                                                                    ?>" width="24px" align="absmiddle">
            <?php //echo $values['title'];                               ?>
                                            </td>
                                            <td><a href="<?php //echo $values['url'];                                                                                                                                                                                                                                                                                                                                                                                                                                                       ?>">[manage]</a></td>
                                        </tr>
            <?php //endforeach;        ?>
            <?php
            //if ($all_clear):
            //  $class = ($i++ % 2 == 0) ? "alt0" : "";
            ?>
                                        <tr class="<?php //echo $class;                                                                                                                                                                                                                                                                                                                                                                                                                                                          ?>">
                                            <td class="ib-wizzard-step-list-name text-left">
                                                Check your SEO status on your pages.
                                            </td>
                                            <td><a href="edit.php?post_type=page">[pages]</a></td>
                                        </tr>
            <?php //$class = ($i++ % 2 == 0) ? "alt0" : "";                               ?>
                                        <tr class="<?php echo $class; ?>">
                                            <td class="ib-wizzard-step-list-name text-left">
                                                Don't show this anymore.
                                            </td>
                                            <td><a href="edit.php?post_type=page" class="red" id="ib_remove-wizzard">[hide]</a></td>
                                        </tr>
            <?php // endif;                               ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    END get started wizzard
                </div>-->
            <?php //endif;                               ?>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $("#social_history_tabs").ib_tabs();
            $("#ib-blog-link").click(function () {
                window.open("<?php echo BREW_BLOG_URL; ?>");
            });
            $("#ib-plugin-blog-link").click(function () {
                window.open("<?php echo BREW_PLUGIN_BLOG_URL; ?>");
            });
        });
    </script>
</div>