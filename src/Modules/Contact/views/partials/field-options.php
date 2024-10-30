<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 9/23/15
 * Time: 1:37 PM
 */

switch ($context) {
    case 'radio':
    case 'select':
    case 'checkbox':
    ?>
        <div data-role="<?php echo $token; ?>" data-name="form-field-builder">
            <div class="ib-row" style="margin-top: 1em;">
                <div>
                    <label for="label">Label</label><br />
                    <input type="text" name="label" class="ib-required" data-type="text" value="<?php echo $name; ?>"/>
                </div>
                <div>
                    <input type="checkbox" name="label_aside" class="ib-form-checkbox" />
                    <label for="label_aside">Label next to input</label>
                </div>
                <div>
                    <input type="checkbox" name="required" checked class="ib-form-checkbox"/>
                    <label for="required">Required field?</label>
                </div>
                <?php if (in_array($context, array('radio','checkbox'))): ?>
                <div>
                    <input type="checkbox" name="horizontal_options" class="ib-form-checkbox" />
                    <label for="horizontal_options">Display <?php echo $context; ?> options horizontally</label>
                </div>
                <?php endif; ?>
                <div style="display:none;">
                    <textarea name="options"><?php echo $options; ?></textarea>
                </div>
                <input type="hidden" name="context" value="<?php echo $context; ?>" />
                <input type="hidden" name="token" value="<?php echo $token; ?>" />
                <?php if (isset($field_id)): ?>
                    <input type="hidden" name="field_id" value="<?php echo $field_id; ?>" />
                <?php endif; ?>
            </div>
        </div>
    <?php
        break;
    case 'captcha': ?>
        <div data-role="<?php echo $token; ?>" data-name="form-field-builder">
            <div class="ib-row" style="margin-top: 1em;">
                <div>
                    Captcha phrase/question will be generated at the time form is displayed</div>
                </div>
                <div>
                    <label for="values">Default value (optional)</label><br>
                    <input type="text" name="values"/>
                </div>
                <input type="hidden" name="context" value="<?php echo $context; ?>" />
                <input type="hidden" name="token" value="<?php echo $token; ?>" />
            </div>
        </div>
    <?php
        break;
    case 'acceptance': ?>
        <div data-role="<?php echo $token; ?>" data-name="form-field-builder">
            <div class="ib-row" style="margin-top: 1em;">
                <div>
                    <input type="checkbox" name="label_aside" class="ib-form-checkbox" />
                    <label for="label_aside">Label next to input</label>
                </div>
                <div>
                    <label for="label">Label</label><br />
                    <input type="text" name="label" class="ib-required" data-type="text" value="<?php echo $name; ?>"/>
                </div>
                <div>
                    <label for="statement" style="font-size: smaller"> Add your acceptance statement.</label>
                    <textarea name="statement" class="ib-cf-textarea"></textarea><br>
                </div>
                <input type="hidden" name="context" value="<?php echo $context; ?>" />
                <input type="hidden" name="token" value="<?php echo $token; ?>" />
            </div>
    </div>
<?php break;
    case 'text':
    case 'email':
    case 'name':
    case 'address':
    case 'address2':
    case 'phone':
    case 'phone2':
    case 'postal':
    case 'city':
    case 'state':
    case 'country':
    case 'birth_date':
    case 'date':
    case 'textarea':
    default: ?>
        <div data-role="<?php echo $token; ?>" data-name="form-field-builder">
            <div class="ib-row" style="margin-top: 1em;">
                <div>
                    <label for="label">Label</label><br />
                    <input type="text" name="label" class="ib-required" data-type="text" value="<?php echo $name; ?>"/>
                </div>
                <div>
                    <input type="checkbox" name="label_aside" class="ib-form-checkbox" />
                    <label for="label_aside">Label next to input</label>
                </div>
                <div>
                    <input type="checkbox" name="required" checked class="ib-form-checkbox" />
                    <label for="required">Required field?</label>
                </div>
                <input type="hidden" name="context" value="<?php echo $context; ?>" />
                <input type="hidden" name="token" value="<?php echo $token; ?>" />
                <?php if (isset($field_id)): ?>
                <input type="hidden" name="field_id" value="<?php echo $field_id; ?>" />
                <?php endif; ?>
            </div>
        </div>
    <?php
        break;
}