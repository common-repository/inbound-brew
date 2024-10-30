<?php
if (!empty($Form->data['CallToAction']['normal']['text']['description'])) {
    $Form->data['CallToAction']['normal']['text']['description'] = $Form->data['CallToAction']['html_preview'];
}
$fonts = $this->fonts;
$allow_template = true;
$allow_cta = true;
$allow_image = false;
switch ($cta_type):
    case "custom":
        $tabWidth = "24.8%";
        break;
    case "edit_cta":
    case "clone_cta":
    case "new_cta":
        $tabWidth = "49.5%";
        $allow_template = false;
        break;
    default: // template
        $tabWidth = "33%";
        $allow_cta = false;
        break;
    case "image":
    case "edit_image":
        $allow_template = false;
        $allow_cta = false;
        $allow_image = true;
        $tabWidth = "49.5%";
        break;
endswitch;
?>
<!---->
    <div id="cta_editor">
        <?php
        echo $Form->create("CallToAction");
        wp_nonce_field('ib_cta_nonce');
        echo $Form->hidden("CallToAction.cta_template_id");
        ?>
        <div class="ib_cta-settings">
            <div class="ib-cta-tabs" id="cta_settings">
                <?php if ($allow_image): ?>
                    <div tab-id="cta-image" class="ib-cta-tab-link active" style="width:<?php echo $tabWidth; ?>">Image</div>
                    <?php
                endif;
                if (!$allow_image):
                    ?>
                    <div tab-id="cta-font" class="ib-cta-tab-link active" style="width:<?php echo $tabWidth; ?>">Font/Text</div>
                    <?php
                endif;
                if ($allow_template):
                    ?>
                    <div tab-id="cta-background" class="ib-cta-tab-link" style="width:<?php echo $tabWidth; ?>">Background</div>
                    <div tab-id="cta-border" class="ib-cta-tab-link" style="width:<?php echo $tabWidth; ?>">Border</div>
                    <?php
                endif;
                if ($allow_cta || $allow_image):
                    ?>
                    <div tab-id="cta-actions" class="ib-cta-tab-link" style="width:<?php echo $tabWidth; ?>">Action</div>
                <?php endif; ?>
                <!--    <a href="#cta-points" class="points"></a>-->
                <div class="clear"></div>
                <div class="tabs">
                    <?php if ($allow_image): ?>
                        <div id="cta-image">
                            <?php
                            echo $Form->hidden("CallToAction.normal.upload_image_id", "", array(
                                'data-state' => "normal"));
                            ?>
                            <?php
                            echo $Form->hidden("CallToAction.normal.cta_image_url", "", array(
                                'data-state' => "normal"));
                            ?>
                            <?php
                            echo $Form->hidden("CallToAction.normal.cta_thumbnail", "", array(
                                'data-state' => "normal"));
                            ?>
                            <div class='ib_editor-fields'>
                                <!-- button text -->
                                <div class="ib_choose-image">
                                    <div class="cta-thumbnail" id="normal_cta_thumbnail"></div>
                                    <div class="ib_cta-choose-image-button">
                                        <a href="" class="ib-button ib_select-gray" id="select_normal_image_button" data-state="normal">choose image</a>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <!-- cta image -->
                        <?php
                    endif;
                    if (!$allow_image):
                        ?>
                        <div id="cta-font">
                            <div class='ib_editor-fields'>
                                <!-- button text -->
                                <?php
                                    ?>
                                    <div class="ib_label">Text:</div>
                                    <?php
                                ?>
                                <div class="ib_fields">
                                    <?php
                                        ?>
                                        <?php
                                        echo $Form->text("CallToAction.normal.text.button_text", array(
                                            'data-group' => "text",
                                            'data-state' => "normal",
                                            'data-property' => "text",
                                            'div' => false));
                                        ?>
                                        <?php
                                    ?>
                                    <?php if ($allow_template): ?>
                                        <!-- uppercase -->
                                        <?php $uppercase = @$Form->data['CallToAction']['normal']['text']['text_transform']; ?>
                                        <a id="" class="ib_cta-transform uppercase <?php if ($uppercase) echo 'ib-active'; ?>" data-property="text-transform" data-value="uppercase" data-state="normal">Aa</a>
                                        <?php
                                        echo @$Form->hidden("CallToAction.normal.text.text_transform", "", array(
                                            'data-group' => "text",
                                            'data-state' => "normal",
                                            'data-property' => "text-transform"));
                                        ?>
                                        <!-- bold -->
                                        <?php $bold = @$Form->data['CallToAction']['normal']['text']['font_weight']; ?>
                                        <a id="" class="ib_cta-transform bold <?php if ($bold) echo 'ib-active'; ?>" data-property="font-weight" data-value="bold" data-state="normal">B</a>
                                        <?php
                                        echo $Form->hidden("CallToAction.normal.text.font_weight", "", array(
                                            'data-group' => "text",
                                            'data-state' => "normal",
                                            'data-property' => "font-weight"));
                                        ?>
                                        <!-- italic -->
                                        <?php $italic = @$Form->data['CallToAction']['normal']['text']['font_style']; ?>
                                        <a id="" class="ib_cta-transform italic <?php if ($italic) echo 'ib-active'; ?>" data-property="font-style" data-value="italic" data-state="normal">I</a>
                                        <?php
                                        echo $Form->hidden("CallToAction.normal.text.font_style", "", array(
                                            'data-group' => "text",
                                            'data-state' => "normal",
                                            'data-property' => "font-style"));
                                        ?>
                                        <?php $underline = @$Form->data['CallToAction']['normal']['text']['text_decoration']; ?>
                                        <!-- underline -->
                                        <a id="" class="ib_cta-transform underline <?php if ($underline) echo 'ib-active'; ?>" data-property="text-decoration" data-value="underline" data-state="normal">U</a>
                                        <?php
                                        echo $Form->hidden("CallToAction.normal.text.text_decoration", "", array(
                                            'data-group' => "text",
                                            'data-state' => "normal",
                                            'data-property' => "text-decoration"));
                                        ?>
                                    <?php endif; ?>
                                </div>
                                <div class="clear"></div>
                                <?php if ($allow_template): ?>
                                    <!-- font size -->
                                    <div class="ib_label">Font Size:</div>
                                    <div class="ib_fields with-slider">
                                        <?php
                                        echo $Form->text("CallToAction.normal.text.font_size", array(
                                            'data-property' => "font-size",
                                            'div' => 'ib_slider-value',
                                            //'readonly' => "readonly",
                                            'data-state' => "normal",
                                            'data-group' => "text"));
                                        ?>
                                        <div data-min="0" data-max="50" data-value="<?php echo $Form->data['CallToAction']['normal']['text']['font_size']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="clear"></div>
                                    <!-- font family -->
                                    <div class="ib_label">Font Family:</div>
                                    <div class="ib_fields">
                                        <?php
                                        echo $Form->select("CallToAction.normal.text.font_family", $fonts, array(
                                            'data-group' => "text",
                                            'data-state' => "normal",
                                            'data-property' => "font-family"))
                                        ?>
                                    </div>
                                    <div class="clear"></div>
                                    <!-- font color -->
                                    <div class="ib_label">Font Color:</div>
                                    <div class="ib_fields">
                                        <?php
                                        echo $Form->text("CallToAction.normal.text.color", array(
                                            'data-group' => "text",
                                            'data-state' => "normal",
                                            'data-property' => "color",
                                            'class' => "ib_color-picker",
                                            'div' => false));
                                        ?>
                                    </div>
                                    <div class="clear"></div>
                                    <!-- text shadow -->
                                    <div class="ib_label">Text Shadow:</div>
                                    <div class="ib_fields">
                                        <?php
                                        echo $Form->text("CallToAction.normal.text.text_shadow.color", array(
                                            'data-group' => "text",
                                            'data-state' => "normal",
                                            'data-property' => "text-shadow",
                                            'data-subproperty' => "color",
                                            'class' => "ib_color-picker",
                                            'div' => false));
                                        ?>
                                    </div>
                                    <div class="clear"></div>
                                    <!-- text shadow properties -->
                                    <div class="ib_label">&nbsp;</div>
                                    <div class="ib_fields">
                                        <!-- text shadow x -->
                                        <div class="ib-column ib-column-4 ib_slider-4">
                                            <div class="label">x:</div>
                                            <?php
                                            echo $Form->text("CallToAction.normal.text.text_shadow.x", array(
                                                'data-property' => "text-shadow",
                                                'data-subproperty' => "x",
                                                'data-state' => "normal",
                                                //'readonly' => "readonly",
                                                'data-group' => "text",
                                                'div' => 'ib_slider-value',
                                                'class' => 'ib-small-scrub-input'));
                                            ?>
                                            <div data-min="0" data-max="10" data-value="<?php echo @$Form->data['CallToAction']['normal']['text']['text_shadow']['x']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                        </div>
                                        <!-- text shadow y -->
                                        <div class="ib-column ib-column-4 ib_slider-4">
                                            <div class="label">y:</div>
                                            <?php
                                            echo $Form->text("CallToAction.normal.text.text_shadow.y", array(
                                                'data-property' => "text-shadow",
                                                'data-subproperty' => "y",
                                                'data-state' => "normal",
                                                //'readonly' => "readonly",
                                                'data-group' => "text",
                                                'div' => 'ib_slider-value',
                                                'class' => 'ib-small-scrub-input'));
                                            ?>
                                            <div data-min="0" data-max="10" data-value="<?php echo @$Form->data['CallToAction']['normal']['text']['text_shadow']['y']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                        </div>
                                        <div class="ib-column ib-column-4 ib_slider-4">
                                            <!-- text shadow blur -->
                                            <div class="label">blur:</div>
                                            <?php
                                            echo $Form->text("CallToAction.normal.text.text_shadow.blur", array(
                                                'data-property' => "text-shadow",
                                                'data-subproperty' => "blur",
                                                'data-state' => "normal",
                                                //'readonly' => "readonly",
                                                'data-group' => "text",
                                                'div' => 'ib_slider-value',
                                                'class' => 'ib-small-scrub-input'));
                                            ?>
                                            <div data-min="0" data-max="10" data-value="<?php echo @$Form->data['CallToAction']['normal']['text']['text_shadow']['blur']; ?>" class="ib-slider ui-slider" aria-disabled="false" style="margin-left:62px;"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="clear"></div>
                                    <!-- font awesome icon -->
                                <?php endif; ?>
                                <div class="ib_label">Icon:</div>
                                <div class="ib_fields">
                                    <!-- icon -->
                                    <div class="ib-column ib-column-4">
                                        <div class="ib_font-awesome-picker noselect" data-pickerid="fa" data-iconsets='{"fa":"Pick FontAwesome"}' data-icons="fa" id="IconPickerNormal">
                                            <?php $fa_icon = @$Form->data['CallToAction']['normal']['icon']['icon']; ?>
                                            <div class="icon"><span class="<?php echo $fa_icon; ?>"></span></div>
                                            <?php
                                            echo $Form->hidden("CallToAction.normal.icon.icon", "", array(
                                                'data-group' => "icon",
                                                'data-state' => "normal",
                                                'data-property' => "icon"));
                                            ?>
                                            <div class="ib_icon_name"><?php echo ($fa_icon) ? $fa_icon : "no icon"; ?></div>
                                            <div class="fa fa-caret-down fa-1x"></div>
                                        </div>
                                    </div>
                                    <?php if ($allow_template): ?>
                                        <!-- icon color -->
                                        <div class="ib-column ib-column-4">
                                            <?php
                                            echo $Form->text("CallToAction.normal.icon.color", array(
                                                'data-group' => "icon",
                                                'data-property' => "color",
                                                'data-state' => "normal",
                                                'class' => "ib_color-picker",
                                                'label' => "color",
                                                'div' => false));
                                            ?>
                                        </div>
                                        <!-- icon position -->
                                        <div class="ib-column ib-column-4">
                                            <div for="CallToActionTextIconPosition" class="div_label">position:</div>
                                            <div class="input select" style="width:50%;">
                                                <?php
                                                echo $Form->select("CallToAction.normal.icon.position", array(
                                                    'left' => "Left",
                                                    'right' => "Right"), array(
                                                    'data-group' => "icon",
                                                    'data-property' => "position",
                                                    'data-state' => "normal",
                                                    'div' => false));
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                    else:
                                        echo $Form->hidden("CallToAction.normal.icon.color");
                                        echo $Form->hidden("CallToAction.normal.icon.position");
                                    endif;
                                    ?>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <?php if ($allow_template): ?>
                                <!-- text hover state -->
                                <div class="hover-state">
                                    <div class="hover-options">
                                        Create Hover State? &nbsp;
                                        <?php
                                        echo $Form->checkbox("CallToAction.text_has_hover_state", 1, array(
                                            'data-group' => "text",
                                            'div' => false));
                                        ?>
                                    </div>
                                    <div class="ib_hover-state-options">
                                        <!-- hover state options -->
                                        <div class='ib_editor-fields'>
                                            <!-- button text -->
                                            <?php
                                                ?>
                                                <div class="ib_label">Text:</div>
                                                <?php
                                            ?>
                                            <div class="ib_fields">
                                                <?php if ($allow_template): ?>
                                                    <!-- uppercase -->
                                                    <?php $uppercase = @$Form->data['CallToAction']['hover']['text']['text_transform']; ?>
                                                    <a id="" class="ib_cta-transform uppercase <?php if ($uppercase) echo 'ib-active'; ?>" data-property="text-transform" data-value="uppercase" data-state="hover">Aa</a>
                                                    <?php
                                                    echo @$Form->hidden("CallToAction.hover.text.text_transform", "", array(
                                                        'data-group' => "text",
                                                        'data-state' => "hover",
                                                        'data-property' => "text-transform"));
                                                    ?>
                                                    <!-- bold -->
                                                    <?php $bold = @$Form->data['CallToAction']['hover']['text']['font_weight']; ?>
                                                    <a id="" class="ib_cta-transform bold <?php if ($bold) echo 'ib-active'; ?>" data-property="font-weight" data-value="bold" data-state="hover">B</a>
                                                    <?php
                                                    echo $Form->hidden("CallToAction.hover.text.font_weight", "", array(
                                                        'data-group' => "text",
                                                        'data-state' => "hover",
                                                        'data-property' => "font-weight"));
                                                    ?>
                                                    <!-- italic -->
                                                    <?php $italic = @$Form->data['CallToAction']['hover']['text']['font_style']; ?>
                                                    <a id="" class="ib_cta-transform italic <?php if ($italic) echo 'ib-active'; ?>" data-property="font-style" data-value="italic" data-state="hover">I</a>
                                                    <?php
                                                    echo $Form->hidden("CallToAction.hover.text.font_style", "", array(
                                                        'data-group' => "text",
                                                        'data-state' => "hover",
                                                        'data-property' => "font-style"));
                                                    ?>
                                                    <?php $underline = @$Form->data['CallToAction']['hover']['text']['text_decoration']; ?>
                                                    <!-- underline -->
                                                    <a id="" class="ib_cta-transform underline <?php if ($underline) echo 'ib-active'; ?>" data-property="text-decoration" data-value="underline" data-state="hover">U</a>
                                                    <?php
                                                    echo $Form->hidden("CallToAction.hover.text.text_decoration", "", array(
                                                        'data-group' => "text",
                                                        'data-state' => "hover",
                                                        'data-property' => "text-decoration"));
                                                    ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="clear"></div>
                                            <?php if ($allow_template): ?>
                                                <!-- font size -->
                                                <div class="ib_label">Font Size:</div>
                                                <div class="ib_fields with-slider">
                                                    <?php
                                                    echo $Form->text("CallToAction.hover.text.font_size", array(
                                                        'data-property' => "font-size",
                                                        'div' => 'ib_slider-value',
                                                        //'readonly' => "readonly",
                                                        'data-state' => "hover",
                                                        'data-group' => "text"));
                                                    ?>
                                                    <div data-min="0" data-max="50" data-value="<?php echo $Form->data['CallToAction']['hover']['text']['font_size']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                                    <div class="clear"></div>
                                                </div>
                                                <div class="clear"></div>
                                                <!-- font family -->
                                                <div class="ib_label">Font Family:</div>
                                                <div class="ib_fields">
                                                    <?php
                                                    echo $Form->select("CallToAction.hover.text.font_family", $fonts, array(
                                                        'data-group' => "text",
                                                        'data-state' => "hover",
                                                        'data-property' => "font-family"))
                                                    ?>
                                                </div>
                                                <div class="clear"></div>
                                                <!-- font color -->
                                                <div class="ib_label">Font Color:</div>
                                                <div class="ib_fields">
                                                    <?php
                                                    echo $Form->text("CallToAction.hover.text.color", array(
                                                        'data-group' => "text",
                                                        'data-state' => "hover",
                                                        'data-property' => "color",
                                                        'class' => "ib_color-picker",
                                                        'div' => false));
                                                    ?>
                                                </div>
                                                <div class="clear"></div>
                                                <!-- text shadow -->
                                                <div class="ib_label">Text Shadow:</div>
                                                <div class="ib_fields">
                                                    <?php
                                                    echo $Form->text("CallToAction.hover.text.text_shadow.color", array(
                                                        'data-group' => "text",
                                                        'data-state' => "hover",
                                                        'data-property' => "text-shadow",
                                                        'data-subproperty' => "color",
                                                        'class' => "ib_color-picker",
                                                        'div' => false));
                                                    ?>
                                                </div>
                                                <div class="clear"></div>
                                                <!-- text shadow properties -->
                                                <div class="ib_label">&nbsp;</div>
                                                <div class="ib_fields">
                                                    <!-- text shadow x -->
                                                    <div class="ib-column ib-column-4 ib_slider-4">
                                                        <div class="label">x:</div>
                                                        <?php
                                                        echo $Form->text("CallToAction.hover.text.text_shadow.x", array(
                                                            'data-property' => "text-shadow",
                                                            'data-subproperty' => "x",
                                                            'data-state' => "hover",
                                                            //'readonly' => "readonly",
                                                            'data-group' => "text",
                                                            'div' => 'ib_slider-value',
                                                            'class' => 'ib-small-scrub-input'));
                                                        ?>
                                                        <div data-min="0" data-max="10" data-value="<?php echo @$Form->data['CallToAction']['hover']['text']['text_shadow']['x']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                                    </div>
                                                    <!-- text shadow y -->
                                                    <div class="ib-column ib-column-4 ib_slider-4">
                                                        <div class="label">y:</div>
                                                        <?php
                                                        echo $Form->text("CallToAction.hover.text.text_shadow.y", array(
                                                            'data-property' => "text-shadow",
                                                            'data-subproperty' => "y",
                                                            'data-state' => "hover",
                                                            //'readonly' => "readonly",
                                                            'data-group' => "text",
                                                            'div' => 'ib_slider-value',
                                                            'class' => 'ib-small-scrub-input'));
                                                        ?>
                                                        <div data-min="0" data-max="10" data-value="<?php echo @$Form->data['CallToAction']['hover']['text']['text_shadow']['y']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                                    </div>
                                                    <div class="ib-column ib-column-4 ib_slider-4">
                                                        <!-- text shadow blur -->
                                                        <div class="label">blur:</div>
                                                        <?php
                                                        echo $Form->text("CallToAction.hover.text.text_shadow.blur", array(
                                                            'data-property' => "text-shadow",
                                                            'data-subproperty' => "blur",
                                                            'data-state' => "hover",
                                                            //'readonly' => "readonly",
                                                            'data-group' => "text",
                                                            'div' => 'ib_slider-value',
                                                            'class' => 'ib-small-scrub-input'));
                                                        ?>
                                                        <div data-min="0" data-max="10" data-value="<?php echo @$Form->data['CallToAction']['hover']['text']['text_shadow']['blur']; ?>" class="ib-slider ui-slider" aria-disabled="false" style="margin-left:62px;"></div>
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                                                <div class="clear"></div>
                                                <!-- font awesome icon -->
                                            <?php endif; ?>
                                            <div class="ib_label">Icon:</div>
                                            <div class="ib-column ib-column-4">
                                                <div class="ib_font-awesome-picker noselect" data-pickerid="fa" data-iconsets='{"fa":"Pick FontAwesome"}' data-icons="fa" id="IconPickerNormal">
                                                    <?php $fa_icon = @$Form->data['CallToAction']['normal']['icon']['icon']; ?>
                                                    <div class="icon"><span class="<?php echo $fa_icon; ?>"></span></div>
                                                    <?php
                                                    echo $Form->hidden("CallToAction.normal.icon.icon", "", array(
                                                        'data-group' => "icon",
                                                        'data-state' => "normal",
                                                        'data-property' => "icon"));
                                                    ?>
                                                    <div class="ib_icon_name"><?php echo ($fa_icon) ? $fa_icon : "no icon"; ?></div>
                                                    <div class="fa fa-caret-down fa-1x"></div>
                                                </div>
                                            </div>
                                            <div class="ib_fields">
                                                <?php if ($allow_template): ?>
                                                    <!-- icon color -->
                                                    <div class="ib-column ib-column-4">
                                                        <?php
                                                        echo $Form->text("CallToAction.hover.icon.color", array(
                                                            'data-group' => "icon",
                                                            'data-property' => "color",
                                                            'data-state' => "hover",
                                                            'class' => "ib_color-picker",
                                                            'label' => "color",
                                                            'div' => false));
                                                        ?>
                                                    </div>
                                                    <?php
                                                else:
                                                    echo $Form->hidden("CallToAction.hover.icon.color");
                                                    echo $Form->hidden("CallToAction.hover.icon.position");
                                                endif;
                                                ?>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        <!-- end hover state options -->
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php
                    endif;
                    if ($allow_template):
                        ?>
                        <!-- background -->
                        <div id="cta-background">
                            <div class='ib_editor-fields'>
                                <!-- background color -->
                                <div class="ib_label">Background Color:</div>
                                <div class="ib_fields">
                                    <?php
                                    echo $Form->radio("CallToAction.normal.background.type", array(
                                        'solid' => "Solid Color",
                                        'gradient' => "Gradient"), array(
                                        'data-state' => "hover"
                                    ));
                                    ?>
                                    <div class="clear"></div>
                                </div>
                                <div class="clear"></div>
                                <!-- background color solid -->
                                <div id="normal_background_solid">
                                    <div class="ib_label">&nbsp;</div>
                                    <div class="ib_fields">
                                        <?php
                                        echo $Form->text("CallToAction.normal.background.background", array(
                                            'data-group' => "background",
                                            'data-state' => "normal",
                                            'data-property' => "background",
                                            'class' => "ib_color-picker",
                                            'div' => false));
                                        ?>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <!-- background color gradient -->
                                <div id="normal_background_gradient">
                                    <div class="ib_label">&nbsp;</div>
                                    <div class="ib_fields">
                                        <div class="fl ib-margin-right">
                                            <?php
                                            echo $Form->text("CallToAction.normal.background.background_top", array(
                                                'data-group' => "background",
                                                'data-property' => "background-top",
                                                'data-state' => "normal",
                                                'class' => "ib_color-picker",
                                                'label' => "top",
                                                'div' => false));
                                            ?>
                                        </div>
                                        <div class="fl">
                                            <?php
                                            echo $Form->text("CallToAction.normal.background.background_bottom", array(
                                                'data-group' => "background",
                                                'data-property' => "background-bottom",
                                                'data-state' => "normal",
                                                'class' => "ib_color-picker",
                                                'label' => "bottom",
                                                'div' => false));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <?php if ($cta_type != "top_bar"): ?>
                                    <!-- horizontal padding -->
                                    <div class="ib_label">Horizontal Padding:</div>
                                    <div class="ib_fields with-slider">
                                        <?php
                                        echo $Form->text("CallToAction.normal.background.h_padding", array(
                                            'data-property' => "h-padding",
                                            'data-state' => "normal",
                                            'div' => 'ib_slider-value',
                                            //'readonly' => "readonly",
                                            'data-group' => "background"));
                                        ?>
                                        <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['CallToAction']['normal']['background']['h_padding']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="clear"></div>
                                <?php endif; ?>
                                <!-- vertical padding -->
                                <div class="ib_label">Vertical Padding:</div>
                                <div class="ib_fields with-slider">
                                    <?php
                                    echo $Form->text("CallToAction.normal.background.v_padding", array(
                                        'div' => 'ib_slider-value',
                                        'data-property' => "v-padding",
                                        //'readonly' => "readonly",
                                        'data-state' => "normal",
                                        'data-group' => "background"));
                                    ?>
                                    <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['CallToAction']['normal']['background']['v_padding']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <!-- background hover state -->
                            <div class="hover-state">
                                <div class="hover-options">
                                    Create Hover State? &nbsp;
                                    <?php
                                    echo $Form->checkbox("CallToAction.background_has_hover_state", 1, array(
                                        'data-group' => "background",
                                        'div' => false));
                                    ?>
                                </div>
                                <div class="ib_hover-state-options">
                                    <!-- hover state options -->
                                    <div class='ib_editor-fields'>
                                        <!-- background color -->
                                        <div class="ib_label">Background Color:</div>
                                        <div class="ib_fields">
                                            <?php
                                            echo $Form->radio("CallToAction.hover.background.type", array(
                                                'solid' => "Solid Color",
                                                'gradient' => "Gradient"), array(
                                                'data-state' => "hover"
                                            ));
                                            ?>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                        <!-- background color solid -->
                                        <div id="hover_background_solid">
                                            <div class="ib_label">&nbsp;</div>
                                            <div class="ib_fields">
                                                <?php
                                                echo $Form->text("CallToAction.hover.background.background", array(
                                                    'data-group' => "background",
                                                    'data-state' => "hover",
                                                    'data-property' => "background",
                                                    'class' => "ib_color-picker",
                                                    'div' => false));
                                                ?>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                        <!-- background color gradient -->
                                        <div id="hover_background_gradient">
                                            <div class="ib_label">&nbsp;</div>
                                            <div class="ib_fields">
                                                <div class="fl ib-margin-right">
                                                    <?php
                                                    echo $Form->text("CallToAction.hover.background.background_top", array(
                                                        'data-group' => "background",
                                                        'data-property' => "background-top",
                                                        'data-state' => "hover",
                                                        'class' => "ib_color-picker",
                                                        'label' => "top",
                                                        'div' => false));
                                                    ?>
                                                </div>
                                                <div class="fl">
                                                    <?php
                                                    echo $Form->text("CallToAction.hover.background.background_bottom", array(
                                                        'data-group' => "background",
                                                        'data-property' => "background-bottom",
                                                        'data-state' => "hover",
                                                        'class' => "ib_color-picker",
                                                        'label' => "bottom",
                                                        'div' => false));
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                        <?php if ($cta_type != "top_bar"): ?>
                                            <!-- horizontal padding -->
                                            <div class="ib_label">Horizontal Padding:</div>
                                            <div class="ib_fields with-slider">
                                                <?php
                                                echo $Form->text("CallToAction.hover.background.h_padding", array(
                                                    'data-property' => "h-padding",
                                                    'data-state' => "hover",
                                                    'div' => 'ib_slider-value',
                                                    //'readonly' => "readonly",
                                                    'data-group' => "background"));
                                                ?>
                                                <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['CallToAction']['hover']['background']['h_padding']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="clear"></div>
                                        <?php endif; ?>
                                        <!-- vertical padding -->
                                        <div class="ib_label">Vertical Padding:</div>
                                        <div class="ib_fields with-slider">
                                            <?php
                                            echo $Form->text("CallToAction.hover.background.v_padding", array(
                                                'div' => 'ib_slider-value',
                                                'data-property' => "v-padding",
                                                //'readonly' => "readonly",
                                                'data-state' => "hover",
                                                'data-group' => "background"));
                                            ?>
                                            <div data-min="0" data-max="50" data-value="<?php echo @$Form->data['CallToAction']['hover']['background']['v_padding']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div> <!-- end hover state options. -->
                            </div>
                        </div>
                        <!-- border -->
                        <div id="cta-border">
                            <div class='ib_editor-fields'>
                                <!-- border color -->
                                <div class="ib_label">Color:</div>
                                <div class="ib_fields">
                                    <?php
                                    echo $Form->text("CallToAction.normal.border.border_color", array(
                                        'data-group' => "border",
                                        'data-property' => "border-color",
                                        'class' => "ib_color-picker",
                                        'data-state' => "normal",
                                        'div' => false));
                                    ?>
                                </div>
                                <div class="clear"></div>
                                <!-- border thickness -->
                                <div class="ib_label">Thickness:</div>
                                <div class="ib_fields with-slider">
                                    <?php
                                    echo $Form->text("CallToAction.normal.border.border_width", array(
                                        'data-property' => "border-width",
                                        'data-state' => "normal",
                                        'div' => 'ib_slider-value',
                                        //'readonly' => "readonly",
                                        'data-group' => "border"));
                                    ?>
                                    <div data-property="h-padding" data-group="border" data-min="0" data-max="10" data-value="<?php echo @$Form->data['CallToAction']['normal']['border']['border_width']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="clear"></div>
                                <!-- border Type -->
                                <div class="ib_label">Style:</div>
                                <div class="ib_fields">
                                    <?php
                                    echo $Form->select("CallToAction.normal.border.border_style", array(
                                        'solid' => "Solid",
                                        'dotted' => "Dotted",
                                        'dashed' => "Dashed"), array(
                                        'data-group' => "border",
                                        'data-state' => "normal",
                                        'data-property' => "border-style"));
                                    ?>
                                </div>
                                <div class="clear"></div>
                                <!-- border radius -->
                                <?php
                                    ?>
                                    <div class="ib_label">Radius:</div>
                                    <div class="ib_fields">
                                        <div class='ib_radius-editor' id="normal_border_radius_editor">
                                            <span class="fa fa-2x fa-link"></span>
                                            <div class="top-left">
                                                <?php
                                                echo $Form->text("CallToAction.normal.border.border_top_left_radius", array(
                                                    'data-property' => "border-top-left-radius",
                                                    'div' => false,
                                                    'data-state' => "normal",
                                                    'data-group' => "border"));
                                                ?>
                                                <span>px</span>
                                            </div>
                                            <div class="top-right">
                                                <?php
                                                echo $Form->text("CallToAction.normal.border.border_top_right_radius", array(
                                                    'data-property' => "border-top-right-radius",
                                                    'data-state' => "normal",
                                                    'div' => false,
                                                    'data-group' => "border"));
                                                ?>
                                                <span>px</span>
                                            </div>
                                            <div class="bottom-left">
                                                <?php
                                                echo $Form->text("CallToAction.normal.border.border_bottom_left_radius", array(
                                                    'data-property' => "border-bottom-left-radius",
                                                    'data-state' => "normal",
                                                    'div' => false,
                                                    'data-group' => "border"));
                                                ?>
                                                <span>px</span>
                                            </div>
                                            <div class="bottom-right">
                                                <?php
                                                echo $Form->text("CallToAction.normal.border.border_bottom_right_radius", array(
                                                    'data-property' => "border-bottom-right-radius",
                                                    'data-state' => "normal",
                                                    'div' => false,
                                                    'data-group' => "border"));
                                                ?>
                                                <span>px</span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                ?>
                            </div>
                            <!-- border hover state -->
                            <div class="hover-state">
                                <div class="hover-options">
                                    Create Hover State? &nbsp;
                                    <?php
                                    echo $Form->checkbox("CallToAction.border_has_hover_state", 1, array(
                                        'data-group' => "text",
                                        'div' => false));
                                    ?>
                                </div>
                                <div class="ib_hover-state-options">
                                    <!-- hover state options -->
                                    <div class='ib_editor-fields'>
                                        <!-- border color -->
                                        <div class="ib_label">Color:</div>
                                        <div class="ib_fields">
                                            <?php
                                            echo $Form->text("CallToAction.hover.border.border_color", array(
                                                'data-group' => "border",
                                                'data-property' => "border-color",
                                                'class' => "ib_color-picker",
                                                'data-state' => "hover",
                                                'div' => false));
                                            ?>
                                        </div>
                                        <div class="clear"></div>
                                        <!-- border thickness -->
                                        <div class="ib_label">Thickness:</div>
                                        <div class="ib_fields with-slider">
                                            <?php
                                            echo $Form->text("CallToAction.hover.border.border_width", array(
                                                'data-property' => "border-width",
                                                'data-state' => "hover",
                                                'div' => 'ib_slider-value',
                                                //'readonly' => "readonly",
                                                'data-group' => "hover"));
                                            ?>
                                            <div data-property="h-padding" data-group="border" data-min="0" data-max="10" data-value="<?php echo @$Form->data['CallToAction']['normal']['border']['border_width']; ?>" class="ib-slider ui-slider" aria-disabled="false"></div>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                        <!-- border Type -->
                                        <div class="ib_label">Style:</div>
                                        <div class="ib_fields">
                                            <?php
                                            echo $Form->select("CallToAction.hover.border.border_style", array(
                                                'solid' => "Solid",
                                                'dotted' => "Dotted",
                                                'dashed' => "Dashed"), array(
                                                'data-group' => "border",
                                                'data-state' => "hover",
                                                'data-property' => "border-style"));
                                            ?>
                                        </div>
                                        <div class="clear"></div>
                                        <!-- border radius -->
                                        <?php
                                            ?>
                                            <div class="ib_label">Radius:</div>
                                            <div class="ib_fields">
                                                <div class='ib_radius-editor' id="hover_border_radius_editor">
                                                    <span class="fa fa-2x fa-link"></span>
                                                    <div class="top-left">
                                                        <?php
                                                        echo $Form->text("CallToAction.hover.border.border_top_left_radius", array(
                                                            'data-property' => "border-top-left-radius",
                                                            'div' => false,
                                                            'data-state' => "hover",
                                                            'data-group' => "border"));
                                                        ?>
                                                        <span>px</span>
                                                    </div>
                                                    <div class="top-right">
                                                        <?php
                                                        echo $Form->text("CallToAction.hover.border.border_top_right_radius", array(
                                                            'data-property' => "border-top-right-radius",
                                                            'data-state' => "hover",
                                                            'div' => false,
                                                            'data-group' => "border"));
                                                        ?>
                                                        <span>px</span>
                                                    </div>
                                                    <div class="bottom-left">
                                                        <?php
                                                        echo $Form->text("CallToAction.hover.border.border_bottom_left_radius", array(
                                                            'data-property' => "border-bottom-left-radius",
                                                            'data-state' => "hover",
                                                            'div' => false,
                                                            'data-group' => "border"));
                                                        ?>
                                                        <span>px</span>
                                                    </div>
                                                    <div class="bottom-right">
                                                        <?php
                                                        echo $Form->text("CallToAction.hover.border.border_bottom_right_radius", array(
                                                            'data-property' => "border-bottom-right-radius",
                                                            'data-state' => "hover",
                                                            'div' => false,
                                                            'data-group' => "border"));
                                                        ?>
                                                        <span>px</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        ?>

                                    </div>
                                </div> <!-- end hover state options. -->
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($allow_cta || $allow_image): ?>
                        <!-- actions -->
                        <div id="cta-actions">
                            <div class='ib_editor-fields'>
                                <!-- alt text -->
                                <div class="ib_label">Alt Text:</div>
                                <div class='ib_fields'>
                                    <?php
                                    echo $Form->text("CallToAction.actions.alt_text", array(
                                        'data-group' => "actions",
                                        'data-property' => "alt",
                                        'data-state' => "normal",
                                        'div' => false));
                                    ?>
                                </div>
                                <!-- title text -->
                                <div class="ib_label">Title Text:</div>
                                <div class='ib_fields'>
                                    <?php
                                    echo $Form->text("CallToAction.actions.title_text", array(
                                        'data-group' => "actions",
                                        'data-property' => "title",
                                        'data-state' => "normal",
                                        'div' => false));
                                    ?>
                                </div>
                                <div class="clear"></div>
                                <!-- links to -->
                                <div class="ib_label">Links To:</div>
                                <div class="ib_fields">
                                    <?php
                                    echo $Form->radio("CallToAction.actions.cta_link", array(
                                        'internal' => "Select a Post/Page",
                                        'external' => "Custom URL"));
                                    ?>
                                    <div class="clear"></div>
                                </div>
                                <!-- select post or page -->
                                <div id="select_post">
                                    <div class="ib_label">&nbsp;</div>
                                    <div class="ib_fields">
                                        <?php
                                        echo $Form->select("CallToAction.actions.internal_link", $pages, array(
                                            'data-property' => "href",
                                            'data-state' => "normal",
                                            'data-group' => "actions"));
                                        ?>
                                    </div>
                                </div>
                                <!-- custom url -->
                                <div id="choose_custom_url">
                                    <div class="ib_label">&nbsp;</div>
                                    <div class="ib_fields">
                                        <?php
                                        echo $Form->text("CallToAction.actions.external_link", array(
                                            'data-group' => "actions",
                                            'data-property' => "href",
                                            'data-state' => "normal",
                                            'div' => false));
                                        ?>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <!-- target -->
                                <div class="ib_label">Target:</div>
                                <div class="ib_fields">
                                    <?php
                                    echo $Form->select("CallToAction.actions.target", array(
                                        '_self' => "Current Window (_self)",
                                        '_blank' => "New Window (_blank)"), array(
                                        'data-property' => "target",
                                        'data-state' => "normal",
                                        'data-group' => "actions"));
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            ?>
            <div class="ib_save_settings">
                <button class="ib_save" id="saveButton">Save Settings</button>
                <button class="ib_cancel" id="cancelButton">Cancel</button>
            </div>
        </div>
        <div class="ib_cta-preview">
            <?php
                ?>
                <div class="ib_title">Button Preview</div>
                <?php
            ?>
            <div class="ib_preview" id="cta_preview">
                <?php
                $cta_html = @$Form->data['CallToAction']['html_preview'];
                $b_text = ($cta_type == "image") ? "&nbsp;" : "Button Text";
                if (!@$cta_html)
                    $cta_html = '<a class="cta-btn" data-role="cta-button">' . $b_text . '</a>';
                echo stripslashes($cta_html);
                ?>
            </div>
            <?php
            echo $Form->textarea("CallToAction.html_preview", array(
                'id' => "html_preview",
                'style' => "display:none"));
            ?>
            <div id="hover_state_styles">
                <style><?php echo @$Form->data['CallToAction']['hover_styles']; ?></style>
                <?php
                echo $Form->textarea("CallToAction.hover_styles", array(
                    'id' => "hover_styles",
                    'style' => "display:none"));
                ?>
            </div>
        </div>
        <div class="clear"></div>
        <?php echo $Form->end(); ?>
    </div>
    <?php
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $("#cta_editor").ib_ctaEditor({
            cancel_url: "admin.php?page=<?php echo $post_type; ?>",
            no_image_thumbnail: "<?php echo BREW_PLUGIN_IMAGES_URL; ?>no_image.jpg"
        });
    });
</script>

