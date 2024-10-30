<!-- settings -->
<?php
switch (@$editing):
    case "email":
        $tabWidth = "49.5%";
        $templateDisplay = "style='display:none;'";
        break;
    case "custom":
        $tabWidth = "19.7%";
        $templateDisplay = "";
        break;
    default:
        $editing = "template";
        $tabWidth = "24.5%";
        $templateDisplay = "";
        break;
endswitch;
?>
<div id="ib_email_editor">
    <?php
    echo $Form->create($save_action, array('url' => admin_url('admin-post.php')));
    wp_nonce_field('ib_save_email_nonce');
    if (@$email_template_id)
        echo $Form->hidden("Template.info.email_template_id", $email_template_id);
    if (@$email_id)
        echo $Form->hidden("Email.email_id", $email_id);
    ?>
    <div class="ib-column ib-column-6" style='min-width: 315px;'>
        <div class="ib-cta-tabs" id="editor_tabs">
            <?php if ($editing == "email" || $editing == "custom"): ?>
                <div tab-id="email-info" class="ib-cta-tab-link active" style="width:<?php echo $tabWidth; ?>">Info</div>
                <?php
            endif;
            if ($editing == "template" || $editing == "custom"):
                if ($editing == "template"):
                    ?>
                    <div tab-id="template-info" class="ib-cta-tab-link active" style="width:<?php echo $tabWidth; ?>">Info</div>
                <?php endif; ?>
                <div tab-id="template-header" class="ib-cta-tab-link" style="width:<?php echo $tabWidth; ?>">Header</div>
                <div tab-id="template-content" class="ib-cta-tab-link" style="width:<?php echo $tabWidth; ?>">Content</div>
            <?php endif; ?>
            <?php if ($editing == "email" || $editing == "custom"): ?>
                <div tab-id="email-body" class="ib-cta-tab-link" style="width:<?php echo $tabWidth; ?>">Body</div>
                <?php
            endif;
            if ($editing == "template" || $editing == "custom"):
                ?>
                <div tab-id="template-footer" class="ib-cta-tab-link" style="width:<?php echo $tabWidth; ?>">Footer</div>
            <?php endif; ?>
            <div class="clear"></div>
            <div class="tabs">
                <?php if ($editing == "email" || $editing == "custom"): ?>
                    <!-- template info -->
                    <div id="email-info" class="email-info-wrap">
                        <div class="ib_editor-fields">
                            <!-- name -->
                            <div class="ib_label">* Name:</div>
                            <div class="ib_fields">
                                <?php echo $Form->text("Email.email_title", array('div' => false, 'required' => true)); ?>
                                <label id="EmailTitle-error" class="error" for="EmailEmailTitle" style="display:none"></label>
                            </div>
                            <div class="clear"></div>
                            <?php if ($editing == "custom"): ?>
                                <!-- description -->
                                <div class="ib_label">*  Description:</div>
                                <div class="ib_fields">
                                    <?php echo $Form->textarea("Template.info.description", array('div' => false, 'required' => true)); ?>
                                </div>
                            <?php endif; ?>
                            <div class="clear"></div>
                            <!-- subject -->
                            <div class="ib_label">*  Subject:</div>
                            <div class="ib_fields">
                                <?php echo $Form->text("Email.email_subject", array('div' => false, 'required' => true)); ?>
                                <label id="EmailSubject-error" class="error" for="EmailEmailSubject" style="display:none"></label>
                            </div>
                            <div class="clear"></div>
                            <!-- send to -->
                            <div class="ib_label">*  To:</div>
                            <div class="ib_fields">
                                <?php echo $Form->text("Email.send_to", array('div' => false, 'class' => "text-left", 'required' => true)); ?>
                            </div>
                            <div class="clear"></div>
                            <!-- send cc -->
                            <div class="ib_label">CC:</div>
                            <div class="ib_fields">
                                <?php echo $Form->text("Email.send_cc", array('div' => false, 'class' => "text-left")); ?>
                            </div>
                            <div class="clear"></div>
                            <!-- send cc -->
                            <div class="ib_label">BCC:</div>
                            <div class="ib_fields">
                                <?php echo $Form->text("Email.send_bcc", array('div' => false, 'class' => "text-left")); ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <!-- email body -->
                    <div id="email-body">
                        <?php
                        $Form->wpEditor("Email.email_value", array(), array(
                            'div' => false));
                        ?>
                    </div>
                <?php endif; ?>
                <!-- template info -->
                <?php if ($editing == "template"): ?>
                    <div id="template-info">
                        <div class="ib_editor-fields">
                            <!-- name -->
                            <div class="ib_label">* Name:</div>
                            <div class="ib_fields">
                                <?php echo $Form->text("Template.info.name", array('div' => false, 'required' => true)); ?>
                            </div>
                            <div class="clear"></div>
                            <!-- description -->
                            <?php if ($editing != "custom"): ?>
                                <div class="ib_label">* Description:</div>
                                <div class="ib_fields">
                                    <?php echo $Form->textarea("Template.info.description", array('div' => false, 'required' => true)); ?>
                                </div>
                            <?php endif; ?>
                            <div class="clear"></div>
                            <!-- send to -->
                            <div class="ib_label">* To:</div>
                            <div class="ib_fields">
                                <?php echo $Form->text("Template.info.send_to", array('div' => false, 'class' => "text-left", 'required' => true)); ?>
                            </div>
                            <div class="clear"></div>
                            <!-- send cc -->
                            <div class="ib_label">CC:</div>
                            <div class="ib_fields">
                                <?php echo $Form->text("Template.info.send_cc", array('div' => false, 'class' => "text-left")); ?>
                            </div>
                            <div class="clear"></div>
                            <!-- send cc -->
                            <div class="ib_label">BCC:</div>
                            <div class="ib_fields">
                                <?php echo $Form->text("Template.info.send_bcc", array('div' => false, 'class' => "text-left")); ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- header -->
                <div id="template-header" <?php echo $templateDisplay; ?>>
                    <!-- logo section -->
                    <div class="ib_field-section">
                        <!-- logo -->
                        <div class="ib_editor-fields">
                            <div class="ib_label">Logo: <?php
                                echo $Form->checkbox("Template.header.container_visible", "1", array(
                                    'label' => false,
                                    'div' => false,
                                    'data-changes' => "container",
                                    'data-group' => ".ib_header_container",
                                    'data-selector' => "#header_container"));
                                ?></div>
                            <div class="ib_fields ib_header_container">
                                <?php
                                echo $Form->wpUpload("Template.header.logo_image", array(
                                    'id' => "headerLogoSelect",
                                    'class' => "ib_media-select fl",
                                    'label' => "Select",
                                    'field_attributes' => array(
                                        'div' => false,
                                        'data-selector' => "#logo-image",
                                        'data-changes' => "attribute",
                                        'data-attribute' => "src",
                                )));
                                ?>

                                
                            </div>
                            <div class="clear"></div>
                            <div class="ib_label">Alignment:</div>
                                <?php
                                echo $Form->select("Template.header.logo_image_align", array(
                                    'left' => "Left",
                                    'center' => "Center",
                                    'right' => "Right"), array(
                                    'div' => "input select fl",
                                    'label' => false,
                                    'data-selector' => "#header_container",
                                    'data-changes' => "css",
                                    'data-property' => "text-align",
                                ));
                                ?>
                            <div class="clear"></div>
                        </div>
                        <!-- logo spacing -->
                        <div class="ib_editor-fields ib_header_container">
                            <div class="ib_label">Logo Padding:</div>
                            <div class="ib_fields">
                                <div class="ib_small-slider">
                                    <div class="label">Left:</div>
                                    <?php
                                    echo $Form->text("Template.header.padding_left", array(
                                        'data-selector' => "#header_container",
                                        'data-changes' => "css",
                                        'data-property' => "padding-left",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['header']['padding_left']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class="ib_small-slider">
                                    <div class="label">Right:</div>
                                    <?php
                                    echo $Form->text("Template.header.padding_right", array(
                                        'data-selector' => "#header_container",
                                        'data-changes' => "css",
                                        'data-property' => "padding-right",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['header']['padding_right']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class='clear'></div>
                                <div class="ib_small-slider">
                                    <div class="label">Top:</div>
                                    <?php
                                    echo $Form->text("Template.header.padding_top", array(
                                        'data-selector' => "#header_container",
                                        'data-changes' => "css",
                                        'data-property' => "padding-top",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['header']['padding_top']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class="ib_small-slider">
                                    <div class="label">Bottom:</div>
                                    <?php
                                    echo $Form->text("Template.header.padding_bottom", array(
                                        'data-selector' => "#header_container",
                                        'data-changes' => "css",
                                        'data-property' => "padding-bottom",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['header']['padding_bottom']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class='clear'></div>
                            </div>
                        </div>
                    </div>
                    <!-- header section -->
                    <div class="ib_field-section ib_header_container">
                        <!-- header padding -->
                        <div class="ib_editor-fields">
                            <div class="ib_label">Header Margin:</div>
                            <div class="ib_fields">
                                <div class="ib_small-slider">
                                    <div class="label">Top:</div>
                                    <?php
                                    echo $Form->text("Template.header.margin_top", array(
                                        'data-selector' => "#header_container",
                                        'data-changes' => "css",
                                        'data-property' => "margin-top",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['header']['margin_top']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class="ib_small-slider">
                                    <div class="label">Bottom:</div>
                                    <?php
                                    echo $Form->text("Template.header.margin_bottom", array(
                                        'data-selector' => "#header_container",
                                        'data-changes' => "css",
                                        'data-property' => "margin-bottom",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['header']['margin_bottom']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <!-- header background -->
                        <div class="ib_editor-fields">
                            <div class="ib_label">Background Color:</div>
                            <div class="ib_fields">
                                <?php
                                echo $Form->text("Template.header.background", array(
                                    'class' => "ib_color-picker",
                                    'data-selector' => "#header_container",
                                    'data-changes' => "css",
                                    'data-property' => "background-color",
                                    'div' => false));
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- top bar -->
                    <div class="ib_field-section">
                        <!-- top bar color -->
                        <div class="ib_editor-fields">
                            <div class="ib_label">Top Bar: <?php
                                echo $Form->checkbox("Template.top_bar.container_visible", "1", array(
                                    'label' => false,
                                    'data-selector' => "#top_bar",
                                    'data-group' => ".ib_topbar_container",
                                    'data-changes' => "container",
                                    'div' => false));
                                ?></div>
                            <div class="clear"></div>
                            
                        
                        </div>
                        <div class="ib_editor-fields">
                            <div class="ib_fields" style="margin-left:0px">
                                <div class="ib_label">Top Bar Color:</div>
                                <div class='fl'>
                                    <?php
                                    echo $Form->text("Template.top_bar.background", array(
                                        'data-selector' => "#top_bar",
                                        'data-changes' => "css",
                                        'data-property' => "background-color",
                                        'class' => "ib_color-picker",
                                        'div' => false));
                                    ?>
                                </div>
                            
                            </div>    
                        </div>
                        <div class="clear"></div>
                        <!-- top bar padding -->
                        <div class="ib_editor-fields ib_topbar_container">

                            <div class="ib_label">Top Bar Padding:</div>
                            <div class="ib_fields">
                                <div class="ib_small-slider">
                                    <div class="label">Left:</div>
                                    <?php
                                    echo $Form->text("Template.top_bar.padding_left", array(
                                        'data-selector' => "#top_bar",
                                        'data-changes' => "css",
                                        'data-property' => "padding-left",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['top_bar']['padding_left']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class="ib_small-slider">
                                    <div class="label"">Right:</div>
                                    <?php
                                    echo $Form->text("Template.top_bar.padding_right", array(
                                        'data-selector' => "#top_bar",
                                        'data-changes' => "css",
                                        'data-property' => "padding-right",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['top_bar']['padding_right']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class='clear'></div>
                                <div class="ib_small-slider">
                                    <div class="label">Top:</div>
                                    <?php
                                    echo $Form->text("Template.top_bar.padding_top", array(
                                        'data-selector' => "#top_bar",
                                        'data-changes' => "css",
                                        'data-property' => "padding-top",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="0" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class="ib_small-slider">
                                    <div class="label">Bottom:</div>
                                    <?php
                                    echo $Form->text("Template.top_bar.padding_bottom", array(
                                        'data-selector' => "#top_bar",
                                        'data-changes' => "css",
                                        'data-property' => "padding-bottom",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['top_bar']['padding_bottom']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class='clear'></div>
                            </div>
                        </div>
                    </div>
                    <!-- social icons -->
                    <div class="ib_editor-fields ib_header_container">
                        <!-- icon color -->
                        <div class="ib_editor-fields">
                            <div class="ib_label">Social Icons?: <?php
                                echo $Form->checkbox("Template.top_bar_social_icons.container_visible", "1", array(
                                    'data-selector' => "#top_bar_social_icons",
                                    'data-changes' => "container",
                                    'data-group' => ".ib_topbar_social_icons",
                                    'label' => false,
                                    'div' => false));
                                ?></div>
                        </div>
                        <div class="clear"></div>
                        <div class="ib_editor-fields">
                            <div class="ib_fields ib_topbar_social_icons">
                                <div class="ib_label ib_inline">Color:</div>
                                <div class="ib_fields fl">
                                    <?php
                                    echo $Form->text("Template.top_bar_social_icons.color", array(
                                        'data-selector' => "#top_bar_social_icons a",
                                        'data-changes' => "css",
                                        'data-property' => "color",
                                        'class' => "ib_color-picker",
                                        'div' => false));
                                    ?>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <!-- icon size and spacing -->
                        <div class="ib_editor-fields ib_topbar_social_icons">
                            <div class="ib_fields">
                                <div class="ib_small-slider">
                                <div class="ib_label">Icon Size:</div>
                                    <?php
                                    echo $Form->text("Template.top_bar_social_icons.icon_size", array(
                                        'data-selector' => "#top_bar_social_icons",
                                        'data-changes' => "custom",
                                        'data-property' => "icon_size",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="1" data-max="5" data-value="<?php echo @$Form->data['Template']['top_bar_social_icons']['icon_size']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class="ib_small-slider">
                                    <div class="ib_label">Spacing:</div>
                                    <?php
                                    echo $Form->text("Template.top_bar_social_icons.icon_spacing", array(
                                        'data-selector' => "#top_bar_social_icons",
                                        'data-changes' => "custom",
                                        'data-property' => "icon_spacing",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['top_bar_social_icons']['icon_spacing']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <!-- networks -->
                        <div class="ib_editor-fields ib_topbar_social_icons">
                            <div class="ib_label">Networks:</div>
                            <div class="ib_fields checkboxes">
                                <?php
                                echo $Form->checkbox("Template.top_bar_social_icons.facebook", "on", array(
                                    'data-selector' => "#top_bar_social_icons",
                                    'data-changes' => "custom",
                                    'data-property' => "icon_facebook",
                                    'div' => false,
                                    'label' => "Facebook &nbsp;&nbsp;&nbsp;"));
                                ?>
                                <?php
                                echo $Form->checkbox("Template.top_bar_social_icons.twitter", "on", array(
                                    'data-selector' => "#top_bar_social_icons",
                                    'data-changes' => "custom",
                                    'data-property' => "icon_twitter",
                                    'div' => false,
                                    'label' => "Twitter &nbsp;&nbsp;&nbsp;"));
                                ?>
                                <?php
                                echo $Form->checkbox("Template.top_bar_social_icons.linked_in", "on", array(
                                    'data-selector' => "#top_bar_social_icons",
                                    'data-changes' => "custom",
                                    'data-property' => "icon_linkedin",
                                    'div' => false,
                                    'label' => "LinkedIn &nbsp;&nbsp;&nbsp;"));
                                ?>
                                <?php
                                echo $Form->checkbox("Template.top_bar_social_icons.google_plus", "on", array(
                                    'data-selector' => "#top_bar_social_icons",
                                    'data-changes' => "custom",
                                    'data-property' => "icon_google-plus",
                                    'div' => false,
                                    'label' => "Google Plus"));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content -->
                <div id="template-content" <?php echo $templateDisplay; ?>>
                    <!-- banner section section -->
                    <div class="ib_field-section">
                        <!-- banner image -->
                        <div class="ib_editor-fields">
                            <div class="ib_label">Banner Image: <?php
                                echo $Form->checkbox("Template.banner_image.container_visible", "1", array(
                                    'data-selector' => "#banner_image",
                                    'data-changes' => "container",
                                    'data-group' => ".ib_banner_image",
                                    'label' => false,
                                    'div' => false));
                                ?></div>
                            <div class="ib_fields ib_banner_image">
                                <?php
                                echo $Form->wpUpload("Template.banner_image.image", array(
                                    'id' => "headerLogoSelect",
                                    'class' => "ib_media-select fl",
                                    'label' => "Select",
                                    'field_attributes' => array(
                                        'div' => false,
                                        'data-selector' => "#banner-image",
                                        'data-changes' => "attribute",
                                        'data-attribute' => "src",
                                )));
                                ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <!-- top bar padding -->
                        <div class="ib_editor-fields ib_banner_image">
                            <div class="ib_label">Banner Padding:</div>
                            <div class="ib_fields">
                                <div class="ib_small-slider">
                                    <div class="label" style="width:25px;">Top:</div>
                                    <?php
                                    echo $Form->text("Template.banner_image.margin_top", array(
                                        'data-selector' => "#banner_image",
                                        'data-changes' => "css",
                                        'data-property' => "margin-top",
                                        //'readonly' => "readonly"
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="<?php echo @$Form->data['Template']['banner_image']['margin_top']; ?>" data-max="50" data-value="0" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class="ib_small-slider">
                                    <div class="label" style="width:45px;">Bottom:</div>
                                    <?php
                                    echo $Form->text("Template.banner_image.margin_bottom", array(
                                        'data-selector' => "#banner_image",
                                        'data-changes' => "css",
                                        'data-property' => "padding-bottom",
                                        //'readonly' => "readonly"
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['banner_image']['margin_bottom']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <!-- email body -->
                    <div class="ib_field-section">
                        <!-- email body spacing -->
                        <div class="ib_editor-fields">
                            <div class="ib_label">Body Spacing:</div>
                            <div class="ib_fields">
                                <div class="ib_small-slider">
                                    <div class="label" style="width:25px;">Left:</div>
                                    <?php
                                    echo $Form->text("Template.body.padding_left", array(
                                        'data-selector' => "#body",
                                        'data-changes' => "css",
                                        'data-property' => "padding-left",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['body']['padding_left']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class="ib_small-slider">
                                    <div class="label" style="width:45px;">Right:</div>
                                    <?php
                                    echo $Form->text("Template.body.padding_right", array(
                                        'data-selector' => "#body",
                                        'data-changes' => "css",
                                        'data-property' => "padding-right",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['body']['padding_right']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class='clear'></div>
                                <div class="ib_small-slider">
                                    <div class="label" style="width:25px;">Top:</div>
                                    <?php
                                    echo $Form->text("Template.body.padding_top", array(
                                        'data-selector' => "#body",
                                        'data-changes' => "css",
                                        'data-property' => "padding-top",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['body']['padding_top']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class="ib_small-slider">
                                    <div class="label" style="width:45px;">Bottom:</div>
                                    <?php
                                    echo $Form->text("Template.body.padding_bottom", array(
                                        'data-selector' => "#body",
                                        'data-changes' => "css",
                                        'data-property' => "padding-bottom",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['body']['padding_bottom']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class='clear'></div>
                            </div>
                        </div>
                        <!-- email background color -->
                        <div class="ib_editor-fields">
                            <div class="ib_label">Background Color:</div>
                            <div class="ib_fields">
                                <?php
                                echo $Form->text("Template.body.background", array(
                                    'data-selector' => "#body",
                                    'data-changes' => "css",
                                    'data-property' => "background-color",
                                    'class' => "ib_color-picker",
                                    'div' => false));
                                ?>
                            </div>
                        </div>
                        <!-- email font color -->
                        <div class="ib_editor-fields">
                            <div class="ib_label">Font Color:</div>
                            <div class="ib_fields">
                                <?php
                                echo $Form->text("Template.body.color", array(
                                    'data-selector' => "#body",
                                    'data-changes' => "css",
                                    'data-property' => "color",
                                    'class' => "ib_color-picker",
                                    'div' => false));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- footer -->
                <div id="template-footer" <?php echo $templateDisplay; ?>>
                    <!-- copyright contact section -->
                    <div class="ib_field-section">
                        <!-- Copyright-->
                        <div class="ib_editor-fields">
                            <div class="ib_label">Copyright?: <?php
                                echo $Form->checkbox("Template.footer.copyright", "1", array(
                                    'data-changes' => "container",
                                    'data-selector' => "#copyright_info",
                                    'data-group' => ".ib_copyright_info",
                                    'label' => false,
                                    'div' => false));
                                ?></div>
                            <div class="ib_fields ib_copyright_info">
                                <?php
                                echo $Form->text("Template.footer.copyright_info", array(
                                    'data-selector' => "#copyright",
                                    'data-changes' => "html",
                                    'div' => false));
                                ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="ib_editor-fields">
                            <div class="ib_label">Contact Info?: <?php
                                echo $Form->checkbox("Template.footer.contact_info", "1", array(
                                    'data-changes' => "container",
                                    'data-selector' => "#contact_info,#contact_custom",
                                    'data-group' => ".ib_contact_info",
                                    'label' => false,
                                    'div' => false));
                                ?></div>
                            <div class="clear"></div>    
                        </div>

                        <!-- footer contact info -->
                        <div class="ib_editor-fields ib_contact_info">
                            <div class="clear"></div>
                            <div class="ib_label">Address:</div>
                            <div class="ib_fields fl">
                                <?php
                                echo $Form->text("Template.footer.contact_address", array(
                                    'div' => false,
                                    'data-selector' => "#contact_address",
                                    'data-after' => "<br>",
                                    'data-changes' => "html"));
                                ?>
                            </div>
                            <div class="ib_label ib_inline">City:</div>
                            <div class="ib_fields fl">
                                <?php
                                echo $Form->text("Template.footer.contact_city", array(
                                    'div' => false,
                                    'data-selector' => "#contact_city",
                                    'data-after' => ",",
                                    'data-changes' => "html"));
                                ?>
                            </div>
                            <div class="clear"></div>
                            <div class="ib_label">State:</div>
                            <div class="ib_fields fl">
                                <?php
                                echo $Form->text("Template.footer.contact_state", array(
                                    'div' => false,
                                    'data-selector' => "#contact_state",
                                    'data-after' => "",
                                    'data-changes' => "html"));
                                ?>
                            </div>
                            <div class="ib_label ib_inline">Zip:</div>
                            <div class="ib_fields fl">
                                <?php
                                echo $Form->text("Template.footer.contact_zip", array(
                                    'div' => false,
                                    'data-selector' => "#contact_zip",
                                    'data-after' => "<br>",
                                    'data-changes' => "html"));
                                ?>
                            </div>
                            <div class="clear"></div>
                            <div class="ib_label">Phone:</div>
                            <div class="ib_fields fl">
                                <?php
                                echo $Form->text("Template.footer.contact_phone", array(
                                    'div' => false,
                                    'data-selector' => "#contact_phone",
                                    'data-after' => "<br>",
                                    'data-changes' => "html"));
                                ?>
                            </div>
                            <div class="ib_label ib_inline">Web:</div>
                            <div class="ib_fields fl">
                                <?php
                                echo $Form->text("Template.footer.contact_website", array(
                                    'div' => false,
                                    'data-selector' => "#contact_website",
                                    'data-after' => "<br>",
                                    'data-changes' => "html"
                                ));
                                ?>
                            </div>
                            <div class="clear"></div>
                            <div class="ib_label">Email:</div>
                            <div class="ib_fields fl">
                                <?php
                                echo $Form->text("Template.footer.contact_email", array(
                                    'div' => false,
                                    'data-selector' => "#contact_email",
                                    'data-after' => "<br>",
                                    'data-changes' => "html"
                                ));
                                ?>
                            </div>
                            <div class="ib_label ib_inline">Custom:</div>
                            <div class="ib_fields fl">
                                <?php
                                echo $Form->text("Template.footer.contact_custom", array(
                                    'div' => false,
                                    'data-selector' => "#contact_custom",
                                    'data-changes' => "html"
                                ));
                                ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="ib_label">Unsubscribe?: <?php
                                echo $Form->checkbox("Template.footer.unsubscribe", "1", array(
                                    'data-changes' => "container",
                                    'data-selector' => "#unsubscribe",
                                    'label' => false,
                                    'div' => false));
                                ?></div>
                        <div class="clear"></div>
                    </div>
                    <!-- logo section -->
                    <div class="ib_field-section">
                        <!-- logo -->
                        <div class="ib_editor-fields">
                            <div class="ib_label">Logo: <?php
                                echo $Form->checkbox("Template.footer.logo_container", "1", array(
                                    'data-changes' => "container",
                                    'data-selector' => "#footer_logo",
                                    'data-group' => ".ib_footer_logo",
                                    'label' => false,
                                    'div' => false));
                                ?></div>
                            <div class="ib_fields ib_footer_logo">
                                <?php
                                echo $Form->wpUpload("Template.footer.logo_image", array(
                                    'id' => "headerLogoSelect",
                                    'class' => "ib_media-select fl",
                                    'label' => "Select",
                                    'field_attributes' => array(
                                        'div' => false,
                                        'data-selector' => "#footer-logo",
                                        'data-changes' => "attribute",
                                        'data-attribute' => "src",
                                )));
                                ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <!-- logo spacing -->
                        <div class="ib_editor-fields ib_footer_logo">
                            <div class="ib_label">Logo Padding:</div>
                            <div class="ib_fields">
                                <div class="ib_small-slider">
                                    <div class="label">Left:</div>
                                    <?php
                                    echo $Form->text("Template.footer.logo_margin_left", array(
                                        'data-selector' => "#footer_logo",
                                        'data-changes' => "css",
                                        'data-property' => "margin-left",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['footer']['logo_margin_left']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class="ib_small-slider">
                                    <div class="label">Right:</div>
                                    <?php
                                    echo $Form->text("Template.footer.logo_margin_right", array(
                                        'data-selector' => "#footer_logo",
                                        'data-changes' => "css",
                                        'data-property' => "margin-right",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['footer']['logo_margin_right']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class='clear'></div>
                                <div class="ib_small-slider">
                                    <div class="label">Top:</div>
                                    <?php
                                    echo $Form->text("Template.footer.logo_margin_top", array(
                                        'data-selector' => "#footer_logo",
                                        'data-changes' => "css",
                                        'data-property' => "margin-top",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['footer']['logo_margin_top']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class="ib_small-slider">
                                    <div class="label">Bottom:</div>
                                    <?php
                                    echo $Form->text("Template.footer.logo_margin_bottom", array(
                                        'data-selector' => "#footer_logo",
                                        'data-changes' => "css",
                                        'data-property' => "margin-bottom",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['footer']['logo_margin_bottom']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class='clear'></div>
                            </div>
                        </div>
                    </div>
                    <!-- footer section -->
                    <div class="ib_field-section">
                        <!-- footer margin -->
                        <div class="ib_editor-fields ib_footer_logo">
                            <div class="ib_label">Footer Padding:</div>
                            <div class="ib_fields">
                                <div class="ib_small-slider">
                                    <div class="label">Left:</div>
                                    <?php
                                    echo $Form->text("Template.footer.padding_left", array(
                                        'data-selector' => "#footer",
                                        'data-changes' => "css",
                                        'data-property' => "padding-left",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['footer']['padding_left']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class="ib_small-slider">
                                    <div class="label">Right:</div>
                                    <?php
                                    echo $Form->text("Template.footer.padding_right", array(
                                        'data-selector' => "#footer",
                                        'data-changes' => "css",
                                        'data-property' => "padding-right",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['footer']['padding_right']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class='clear'></div>
                                <div class="ib_small-slider">
                                    <div class="label">Top:</div>
                                    <?php
                                    echo $Form->text("Template.footer.padding_top", array(
                                        'data-selector' => "#footer",
                                        'data-changes' => "css",
                                        'data-property' => "padding-top",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['footer']['padding_top']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class="ib_small-slider">
                                    <div class="label">Bottom:</div>
                                    <?php
                                    echo $Form->text("Template.footer.padding_bottom", array(
                                        'data-selector' => "#footer",
                                        'data-changes' => "css",
                                        'data-property' => "padding-bottom",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['footer']['padding_bottom']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class='clear'></div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <!-- header background -->
                        <div class="ib_editor-fields">
                            <div class="ib_label">Background Color:</div>
                            <div class="ib_fields fl">
                                <?php
                                echo $Form->text("Template.footer.background", array(
                                    'data-selector' => "#footer",
                                    'data-changes' => "css",
                                    'data-property' => "background-color",
                                    'class' => "ib_color-picker",
                                    'div' => false
                                ));
                                ?>
                            </div>
                            <div class="clear"></div>
                            <div class="ib_label">Font Color:</div>
                            <div class="ib_fields fl" style="width:30%">
                                <?php
                                echo $Form->text("Template.footer.color", array(
                                    'data-selector' => "#footer,#contact_info,#footer-custom",
                                    'data-changes' => "css",
                                    'data-property' => "color",
                                    'class' => "ib_color-picker",
                                    'div' => false));
                                ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <!-- social icons -->
                    <div class="ib_editor-fields">
                        <!-- icon color -->
                        <div class="ib_editor-fields">
                            <div class="ib_label">Social Icons?: <?php
                                echo $Form->checkbox("Template.footer_social_icons.container_visible", "1", array(
                                    'data-changes' => "container",
                                    'data-selector' => "#footer_social_icons",
                                    'data-group' => ".footer_social_icons",
                                    'label' => false,
                                    'div' => false));
                                ?></div>
                            
                        </div>
                        <!-- icon size and spacing -->
                        <div class="ib_editor-fields footer_social_icons">
                            
                            <div class="ib_fields">
                                
                                <div class="ib_small-slider">
                                    <div class="ib_label" style="width:65px;">Color:</div>
                                    <?php
                                    echo $Form->text("Template.footer_social_icons.color", array(
                                        'data-selector' => "#footer_social_icons a",
                                        'data-changes' => "css",
                                        'data-property' => "color",
                                        'class' => "ib_color-picker",
                                        'div' => false));
                                    ?>
                                </div>
                                <div class="ib_small-slider">
                                    <div class="ib_label">Icon Size:</div>
                                    <?php
                                    echo $Form->text("Template.footer_social_icons.icon_size", array(
                                        'data-selector' => "#footer_social_icons",
                                        'data-changes' => "custom",
                                        'data-property' => "icon_size",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="1" data-max="5" data-value="<?php echo @$Form->data['Template']['footer_social_icons']['icon_size']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                                <div class="ib_small-slider">
                                    <div class="ib_label">Spacing:</div>
                                    <?php
                                    echo $Form->text("Template.footer_social_icons.icon_spacing", array(
                                        'data-selector' => "#footer_social_icons",
                                        'data-changes' => "custom",
                                        'data-property' => "icon_spacing",
                                        //'readonly' => "readonly",
                                        'div' => 'ib_slider-value',
                                        'class' => 'ib-small-scrub-input'
                                        ));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['Template']['footer_social_icons']['icon_spacing']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <!-- networks -->
                        <div class="ib_editor-fields footer_social_icons">
                            <div class="ib_label">Networks:</div>
                            <div class="ib_fields checkboxes">
                                <?php
                                echo $Form->checkbox("Template.footer_social_icons.facebook", "on", array(
                                    'data-selector' => "#footer_social_icons",
                                    'data-changes' => "custom",
                                    'data-property' => "icon_facebook",
                                    'div' => false,
                                    'label' => "Facebook &nbsp;&nbsp;&nbsp;"));
                                ?>
                                <?php
                                echo $Form->checkbox("Template.footer_social_icons.twitter", "on", array(
                                    'data-selector' => "#footer_social_icons",
                                    'data-changes' => "custom",
                                    'data-property' => "icon_twitter",
                                    'div' => false,
                                    'label' => "Twitter &nbsp;&nbsp;&nbsp;"));
                                ?>
                                <?php
                                echo $Form->checkbox("Template.footer_social_icons.linked_in", "on", array(
                                    'data-selector' => "#footer_social_icons",
                                    'data-changes' => "custom",
                                    'data-property' => "icon_linkedin",
                                    'div' => false,
                                    'label' => "LinkedIn &nbsp;&nbsp;&nbsp;"));
                                ?>
                                <?php
                                echo $Form->checkbox("Template.footer_social_icons.google_plus", "on", array(
                                    'data-selector' => "#footer_social_icons",
                                    'data-changes' => "custom",
                                    'data-property' => "icon_google-plus",
                                    'div' => false,
                                    'label' => "Google Plus"));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        ?>
        <div class="ib_save_settings">
            <button class="ib_save save_btn" id="saveButton">Save Settings</button>
            <button class="ib_cancel" id="cancelButton">Cancel</button>
        </div>
    </div>
    <!-- preview -->
    <div class="ib-column ib-column-6" id="sticky-anchor">
        <div id="sticky">
            <?php echo $Layout->element($partials_path . "template_preview", array("Form" => $Form)); ?>
        </div>
    </div>
    <div class="clear"></div>
    <?php echo $Form->end(); ?>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($) {
    $("#ib_email_editor").ib_emailEditor({
    preview_frame:"#ib-email-template-iframe",
            cancel_url : "admin.php?page=<?php echo $post_type; ?>&section=templates_list",
            form_selector:"#<?php echo $save_action; ?>",
<?php if (!empty($contact_forms)): ?>
        contact_forms: <?php echo json_encode($contact_forms, JSON_NUMERIC_CHECK); ?>
<?php endif; ?>
    });
    });
</script>