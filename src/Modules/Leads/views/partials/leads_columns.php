<?php
$form_data = array(
    'LeadColumn' => array());
foreach ($active_columns as $token):
    $form_data['LeadColumn'][$token] = "1";
endforeach;
$Form->data = $form_data;
?>
<div class="ib_leads-columns triangle-isosceles" id="lead_columns" style="display:none;">
    <p class='ib-notes'>Choose which columns should display in your view:</p>
    <?php echo $Form->create("ib_lead-columns", array('url' => admin_url('admin-post.php'), 'id' => "lead_columns_form")); ?>
    <ul class="ib_lead-columns-list">
        <?php foreach ($filters['static_fields'] as $token => $values): ?>
            <li data-field="<?php echo $token; ?>">
                <?php echo $Form->checkbox("LeadColumn.{$token}", "1", array("label" => $values['label'], 'data-token' => $token, 'data-field' => "static")); ?>
            </li>
        <?php endforeach; ?>
        <?php
        if (!empty($filters['custom_fields'])):
            foreach ($filters['custom_fields'] as $token => $values):
                ?>
                <li data-field="<?php echo $token; ?>">
                    <?php echo $Form->checkbox("LeadColumn.{$token}", "1", array("label" => $values['label'], 'data-token' => $token, 'data-field' => "custom")); ?>
                </li>
                <?php
            endforeach;
        endif;
        ?>
        <div class="clear"></div>
    </ul>
    <?php echo $Form->end(); ?>
</div>