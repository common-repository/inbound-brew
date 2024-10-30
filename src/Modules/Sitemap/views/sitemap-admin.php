<div id="ib_sitemap">
    <div class="ib-row">
        <div class="ib-column ib-column-4">
            <form method="post" action="<?php echo admin_url("admin-post.php"); ?>">
            <!-- Basic Options -->
            <div class="ib-row ib-admin-box">
                <div class="ib_row ib-th">
                    Update notification
                </div>
                <div class="ib-td">
                    <ul>
                        <li>
                            <input id="service[google]" name="service[google]" <?php echo isset($options->service->google)?'checked':''; ?> type="checkbox">
                            <label for="service[google]">Notify Google about updates of your Blog</label><br />
                        </li>
                        <li>
                            <input id="service[bing]" name="service[bing]" <?php echo isset($options->service->bing)?'checked':''; ?> type="checkbox">
                            <label for="service[bing]">Notify Bing (formerly MSN Live Search) about updates of your Blog</label><br>
                        </li>
                    </ul>
                </div>
            </div>
        <!-- Includes -->
            <div class="ib-row ib-admin-box">
                <div class="ib_row ib-th">
                    WordPress standard content
                </div>
                <div class="ib-td">
                    <ul>
                        <li>
                            <input id="standard[home]" name="standard[home]" <?php echo isset($options->standard->home)?'checked':''; ?> type="checkbox">
                            <label for="standard[home]">Include homepage</label>
                        </li>
                        <li>
                            <input id="standard[posts]" name="standard[post]" <?php echo isset($options->standard->post)?'checked':''; ?> type="checkbox">
                            <label for="standard[posts]">Include posts</label>
                        </li>
                        <li>
                            <input id="standard[pages]" name="standard[page]" <?php echo isset($options->standard->page)?'checked':''; ?> type="checkbox">
                            <label for="standard[pages]">Include static pages</label>
                        </li>
                        <li>
                            <input id="standard[categories]" name="standard[categories]" <?php echo isset($options->standard->categories)?'checked':''; ?> type="checkbox">
                            <label for="standard[categories]">Include categories</label>
                        </li>
                        <li>
                            <input id="standard[archives]" name="standard[archives]" <?php echo isset($options->standard->archives)?'checked':''; ?> type="checkbox">
                            <label for="standard[archives]">Include archives</label>
                        </li>
                        <li>
                            <input id="standard[author]" name="standard[author]" <?php echo isset($options->standard->author)?'checked':''; ?> type="checkbox">
                            <label for="standard[author]">Include author pages</label>
                        </li>
                        <li>
                            <input id="standard[tags]" name="standard[tags]" <?php echo isset($options->standard->tags)?'checked':''; ?> type="checkbox">
                            <label for="standard[tags]">Include tag pages</label>
                        </li>
                    </ul>
                </div>
            </div>

            <?php if (isset($taxonomies) && !empty($taxonomies)): ?>
            <div class="ib-row ib-admin-box">
                <div class="ib_row ib-th">
                    Custom taxonomies
                </div>
                <div class="ib-td">
                    <ul>
                        <?php foreach ($taxonomies as $key=>$value): ?>
                            <li>
                                <input id="custom_taxonomy[<?php echo $key; ?>]" name="custom_taxonomy[<?php echo $key; ?>]" <?php echo isset($options->custom_taxonomy->$key)?'checked':''; ?> type="checkbox">
                                <label for="custom_taxonomy[<?php echo $key; ?>]">Include taxonomy pages for <?php echo $value->label; ?></label>
                            </li>
                        <?php endforeach; ?>

                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <?php if(isset($post_types) && !empty($post_types)): ?>
            <div class="ib-row ib-admin-box">
                <div class="ib_row ib-th">
                    Custom post types
                </div>
                <div class="ib-td">
                    <ul>
                        <?php foreach ($post_types as $key=>$value): ?>
                        <li>
                            <input id="custom_post_types[<?php echo $key; ?>]" name="custom_post_types[<?php echo $key; ?>]" <?php echo isset($options->custom_post_types->$key)?'checked':''; ?> type="checkbox">
                            <label for="custom_post_types[<?php echo $key; ?>]">Include custom post type <?php echo $value->label; ?></label>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <div class="ib-row">
                <input type="hidden" name="action" value="save_ib_sitemap_updates" />
                <?php wp_nonce_field('ib-save-sitemap-settings','ib-save-sitemap-settings-nonce'); ?>
                <input class="ib-button" value="Update options" type="submit">
            </div>
            </form>
        </div>
        <div class="ib-column ib-column-8">
            <div class="ib-row ib-admin-box">
                <div class="ib-th">
                    Results of last ping
                </div>
                <div class="ib-column ib-column-8 ib-td">
                    <ul>
                        <li>
                            <b>URL:</b> to your sitemap index file is:
                            <a href="/sitemap.xml" target="_blank">sitemap.xml</a>.
                        </li>
                    <?php if(isset($ping) && !empty($ping)): ?>
                        <li><b>Date:</b> <?php echo date('Y-m-d H:i:s',$ping[0]->date); ?></li>
                        <?php foreach ($ping as $key=>$value):
                            if ($value->success): ?>
                                <li><b><?php echo ucwords($value->service);?>:</b> successfully notified about changes. Ping duration of <?php echo round($value->duration,3);?> seconds</li>
                            <?php else: ?>
                                <li><?php echo ucwords($value->service);?>:</b> notification attempt unsuccessful. Ping duration of <?php echo round($value->duration,3);?> seconds</li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>Your Sitemap has yet to be pinged.</li>
                    <?php endif; ?>
                    </ul>
                </div>
                <div class="ib-column ib-column-4 ib-td">
                    <div class="fr">
                        <form action="<?php echo admin_url("admin-post.php"); ?>" method="post">
                            <input type="hidden" name="action" value="call_ib_sitemap_ping">
                            <?php wp_nonce_field('ib-send-manual-ping'); ?>
                            <input class="ib-button" type="submit" value="Ping Now">
                        </form>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>

</div>
