<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 9/22/15
 * Time: 11:18 AM
 */
switch ($context) {
    case 'text':
    case 'date':
    case 'textarea':
    ?>
        <form id="custom-field-add">
            <div class="ib-row ib-tr">
                <div class="ib-column ib-column-3 ib-td">
                    <label for="name" class="ib-required">Label</label><br />
                </div>
                <div class="ib-column ib-column-9 ib-td">
                    <input type="text" name="name" class="ib-required" data-type="text" style="width:99%"/>
                </div>
            </div>
            <input type="hidden" name="action" value="save_custom_lead_field" />
            <input type="hidden" name="context" value="<?php echo $context; ?>" />
            <input type="button" class="ib-button" value="Save">
        </form>
    <?php
        break;
    case 'radio':
    case 'checkbox':
    case 'select':
        ?>
        <form id="custom-field-add">
            <div class="ib-row ib-tr">
                <div class="ib-column ib-column-3 ib-td">
                    <label for="name" class="ib-required">Label</label><br />
                </div>
                <div class="ib-column ib-column-9 ib-td">
                    <input type="text" name="name" class="ib-required" data-type="text" style="width:99%"/>
                </div>
            </div>
            <div class="ib-row ib-tr">
                <div class="ib-column ib-column-3 ib-td">
                    <label for="options" class="ib-required">Options:</label>
                </div>
                <div class="ib-column ib-column-9 ib-td">
                    <textarea name="options" class="ib-required" style="width:99%;"></textarea>
                    <div class="ib-notes">Enter each option in a new line</div>
                </div>
            </div>
            <input type="hidden" name="action" value="save_custom_lead_field" />
            <input type="hidden" name="context" value="<?php echo $context; ?>" />
            <input type="button" class="ib-button" value="Save">
        </form>
    <?php
        break;
    default:
        break;
}