<div class="ib-row">
    <div class="ib-column ib-column-6">
        <?php echo $form_options; ?>
    </div>
    <div class="ib-column ib-column-6">
        <?php $args = array(
                    'textarea_rows' => 15,
                    'textarea_name' => 'ib_lp_column_1',
                    'wpautop' => false
                    );
        wp_editor( $ib_lp_column_1, 'ib_lp_column_1', $args );?>
    </div>
    <div>
        <input type="hidden" name="template_id" value="<?php echo $template_id; ?>" />
    </div>
</div>