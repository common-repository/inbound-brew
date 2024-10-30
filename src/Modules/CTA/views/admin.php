<h2>CTA Settings</h2>
<div class="ib-row">
    <div class="ib-column ib-column-2" id="cta-settings-container">
        <form method="post" id="ib_cta_defaults" class="ib-ajax-form">
            <input type="hidden" name="action" value="save_ib_cta_defaults">
            <?php wp_nonce_field( 'ib-cta-default', 'ib-cta-default-nonce' ); ?>
            <section id="font-properties">
                <h3>Font Properties</h3>

                <div class="span2">
                    <label for="font_family">Font Family</label><br />
                    <select name="font" id="font_family" data-name="font-family" class="span2">
                        <?php foreach ($fonts as $key=>$value): ?>
                            <option value="<?php echo $key; ?>" <?php echo (isset($default->font) && $default->font == $key)?' selected':'';?> ><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="span2">
                    <div class="attr-label">
                        <label>Font size:</label>
                        <input type="text" name="font_size" class="slider-default" value="<?php echo isset($default->font_size)?$default->font_size:'';?>" size="4" readonly/>
                        <span class="text-info"><span class="text-value"></span>px</span>
                    </div>
                    <div data-name="font_size" data-min="6" data-max="50" data-value="<?php echo isset($default->font_size)?$default->font_size:'';?>" class="ui-slider" aria-disabled="false"></div>
                </div>
            </section>

            <section id="color-properties">
                <h3>Color</h3>
                <div class="span2">
                    <label>Font color</label>
                    <input name="color" type="text" data-name="color" data-type="color-picker" maxlength="6" size="6" value="<?php echo isset($default->color)?$default->color:'';?>" style="<?php echo(isset($default->color))?'background-color:#'.$default->color.';':'';?>" />
                </div>
                <div class="span2">
                    <label>Background color:</label>
                    <input name="background_color" data-type="color-picker" data-name="background-color" type="text" maxlength="6" size="6" id="background-color" value="<?php echo isset($default->background_color)?$default->background_color:'';?>" style="<?php echo(isset($default->background_color))?'background-color:#'.$default->background_color.';':'';?>" />
                </div>
                <div>
                    For background gradients
                </div>
                <div class="span2">
                    <label>Background Top:</label>
                    <input name="background_top" data-type="color-picker" type="text" maxlength="6" size="6" id="background-top" value="<?php echo isset($default->background_top)?$default->background_top:'';?>" style="<?php echo(isset($default->background_top))?'background-color:#'.$default->background_top.';':'';?>" />
                </div>
                <div class="span2">
                    <label>Background Bottom:</label>
                    <input name="background_bottom" data-type="color-picker" type="text" maxlength="6" size="6" id="background-bottom" value="<?php echo isset($default->background_bottom)?$default->background_bottom:'';?>" style="<?php echo(isset($default->background_bottom))?'background-color:#'.$default->background_bottom.';':'';?>" />
                </div>
            </section>

            <section id="border-properties">
                <h3>Size &amp; Border</h3>
                <div class="span2">
                    <div class="attr-label">
                        <label>Horizontal padding:</label>
                        <input name="h_padding" type="text" class="slider-default" value="<?php echo isset($default->h_padding)?$default->h_padding:'';?>" size="4" readonly/><span>px</span>
                    </div>
                    <div data-name="h_padding" data-min="0" data-max="80" data-value="<?php echo isset($default->h_padding)?$default->h_padding:'';?>" class="ui-slider" aria-disabled="false"></div>
                </div>
                <div class="span2">
                    <div class="attr-label">
                        <label>Vertical padding:</label>
                        <input name="v_padding" type="text" class="slider-default" value="<?php echo isset($default->v_padding)?$default->v_padding:'';?>" size="4" readonly/><span>px</span>
                    </div>
                    <div data-name="v_padding" data-min="0" data-max="60" data-value="<?php echo isset($default->v_padding)?$default->v_padding:'';?>" class="ui-slider" aria-disabled="false"></div>
                </div>
                <div class="span2">
                    <div class="attr-label">
                        <label>Border radius:</label>
                        <input name="border_radius" data-name="border-radius" type="text" class="slider-default" value="<?php echo isset($default->border_radius)?$default->border_radius:'';?>" size="4" readonly/>
                        <span class="text-info"><span class="text-value"></span>px</span>
                    </div>
                    <div data-name="border_radius" data-min="0" data-max="50" data-value="<?php echo isset($default->border_radius)?$default->border_radius:'';?>" class="ui-slider" aria-disabled="false"></div>
                </div>
                <div class="span2">
                    <div class="attr-label">
                        <label>Border size:</label>
                        <input name="border" data-name="border" type="text" class="slider-default" value="<?php echo isset($default->border)?$default->border:'';?>" size="4" readonly/>
                        <span class="text-info"><span class="text-value"></span>px</span>
                    </div>
                    <div data-name="border" data-min="0" data-max="10" data-value="<?php echo isset($default->border)?$default->border:'';?>" class="ui-slider" aria-disabled="false"></div>
                    <div class="span2">
                        <label>Border color:</label>
                        <input name="border_color" data-type="color-picker" data-name="border-color" type="text" maxlength="6" size="6" id="border-color" value="<?php echo isset($default->border_color)?$default->border_color:'';?>" style="<?php echo(isset($default->border_color))?'background-color:#'.$default->border_color.';':'';?>" />
                    </div>
                </div>
                <div class="span2">
                    <input type="submit" value="Save Default Settings" />
                </div>
            </section>
        </form>
        <div class="ib-ajax-response"></div>
    </div>
    <div class="ib-column ib-column-10">
        &nbsp;
    </div>
</div>