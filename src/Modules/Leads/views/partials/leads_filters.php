<div class="ib_leads-filters triangle-isosceles" id="lead_filters" style="display:none;">
    <?php echo $Form->create("ib_lead-filters", array('url' => admin_url('admin-post.php'), 'id' => "lead_filters_form")); ?>
    <ul class="filter-fields" id="filter-fields-list">
        <li data-token="applied-filters" class="active">Applied Filters <span class="bubble" style="display:none;"></span></li>
        <li data-token="archived_leads">Lead Status <span class="bubble" style="display:none;"></span></li>
        <?php
        foreach ($filters['static_fields'] as $token => $values):
            if (@$values['no_filter'])
                continue;
            ?>
            <li data-token="<?php echo $token; ?>"><?php echo $values['label']; ?> <span class="bubble" style="display:none;"></span></li>
            <?php
        endforeach;
        if (!empty($filters['custom_fields'])):
            foreach ($filters['custom_fields'] as $token => $values):
                ?>
                <li data-token="<?php echo $token; ?>"><?php echo $values['label']; ?> <span class="bubble" style="display:none;"></span></li>
                <?php
            endforeach;
        endif;
        ?>
    </ul>
    <div class="filter-screens" id="filter-screens">
        <div id="applied-filters" class="filter-screen">
            <div class="screen-title">Applied Filters:</div>
            <div class="selected-filters" id="lead-applied-filters"></div>
        </div>
        <div id="archived_leads" class="filter-screen" style="display:none;" data-label="Archived Leads" data-type="select"  data-field-type="static">
            <div class="screen-title">Lead Status:</div>
            <?php
            echo $Form->select("LeadFilter.Lead.archived_leads.value", array(
                'only_active' => "Active Leads Only",
                'archived' => "Archived Leads Only",
                'all' => "Archived and Active Leads"), array('data-token' => "archived_leads", 'data-field' => "static"));
            ?>
            <div class="selected-filters"></div>
        </div>
        <?php
        foreach ($filters['static_fields'] as $token => $values):
            if (@$values['no_filter'])
                continue;
            ?>
            <div id="<?php echo $token; ?>" class="filter-screen" style="display:none;" data-label="<?php echo $values['label']; ?>" data-type="<?php echo $values['type']; ?>" data-field-type="static">
                <div class="screen-title"><?php echo $values['label']; ?>:</div>
                <?php
                switch ($values['type']):
                    case "text":
                    case "email":
                    case "textarea":
                        echo $Form->text("LeadFilter.Lead.{$token}.value", array('placeholder' => "keyword", 'data-token' => $token, 'data-field' => "static"));
                        echo $Form->checkbox("LeadFilter.Lead.{$token}.not_set", "1", array('label' => "Not Set", 'data-token' => $token, 'data-field' => "static"));
                        break;
                    case "select":
                        echo $Form->select("LeadFilter.Lead.{$token}.value", $values['options'], array('empty' => "Choose One", 'data-token' => $token, 'data-field' => "static"));
                        echo $Form->checkbox("LeadFilter.Lead.{$token}.not_set", "1", array('label' => "Not Set", 'data-token' => $token, 'data-field' => "static"));
                        break;
                    case "date":
                        echo $Form->text("LeadFilter.Lead.{$token}.start", array("class" => "ib-date", 'placeholder' => "Start Date", 'data-token' => $token, 'data-field' => "static", 'data-range' => "range_start"));
                        echo $Form->text("LeadFilter.Lead.{$token}.end", array("class" => "ib-date", 'placeholder' => "End Date", 'data-token' => $token, 'data-field' => "static", 'data-range' => "range_end"));
                        echo $Form->checkbox("LeadFilter.Lead.{$token}.not_set", "1", array('label' => "Not Set", 'data-token' => $token, 'data-field' => "static"));
                        break;

                endswitch;
                ?>
                <div class="selected-filters"></div>
            </div>
        <?php endforeach; ?>
        <?php
        if (!empty($filters['custom_fields'])):
            foreach ($filters['custom_fields'] as $token => $values):
                ?>
                <div id="<?php echo $token; ?>" class="filter-screen" style="display:none;" data-label="<?php echo $values['label']; ?>" data-type="<?php echo $values['type']; ?>" data-field-type="custom">
                    <div class="screen-title"><?php echo $values['label']; ?>:</div>
                    <?php
                    switch ($values['type']):
                        case "text":
                        case "email":
                        case "textarea":
                            echo $Form->text("LeadFilter.LeadData.{$token}.value", array('placeholder' => "keyword", 'data-token' => $token, 'data-field' => "custom"));
                            echo $Form->checkbox("LeadFilter.LeadData.{$token}.not_set", "1", array('label' => "Not Set", 'data-token' => $token, 'data-field' => "custom"));
                            break;
                        case "select":
                        case "radio":
                        case "checkbox":
                            echo $Form->select("LeadFilter.LeadData.{$token}.select", $values['options'], array('empty' => "Choose One", 'data-token' => $token, 'data-field' => "custom"));
                            echo $Form->checkbox("LeadFilter.LeadData.{$token}.not_set", "1", array('label' => "Not Set", 'data-token' => $token, 'data-field' => "custom"));
                            break;
                        case "date":
                            echo $Form->text("LeadFilter.LeadData.{$token}.start", array("class" => "ib-date", 'placeholder' => "Start Date", 'data-token' => $token, 'data-field' => "custom", 'data-range' => "range_start"));
                            echo $Form->text("LeadFilter.LeadData.{$token}.end", array("class" => "ib-date", 'placeholder' => "End Date", 'data-token' => $token, 'data-field' => "custom", 'data-range' => "range_end"));
                            echo $Form->checkbox("LeadFilter.LeadData.{$token}.not_set", "1", array('label' => "Not Set", 'data-token' => $token, 'data-field' => "custom"));
                            break;
                    endswitch;
                    ?>
                    <div class="selected-filters"></div>
                </div>
                <?php
            endforeach;
        endif;
        ?>
    </div>
    <div class="clear"></div>
    <?php echo $Form->end(); ?>
</div>
<?php
?>