<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 7/17/15
 * Time: 2:06 PM
 */
?>
<div data-role="keyword-holder" class="ib-row" style="min-height: 25px;">
<?php
if (isset($words) && !empty($words)):
    foreach($words as $word):?>
        <div class="ib-pill <?php echo (isset($word->used) && $word->used !== false)?'ib-used':''; ?>" id="<?php echo $word->keyword_id; ?>"><?php echo $word->keyword_value; ?></div>
<?php endforeach; ?>
<?php endif; ?>
</div>
<div class="clear"></div>
<div class="ib-row" style="margin-top:2em;">
    <div>Add Keywords to Post:</div>
    <div id="autocomplete" contenteditable="true" class="ib-content-editable"></div>
    <div>
        <span class="ib-link" data-role="add-post-keyword">[Create New]</span>
    </div>
</div>

<div data-role="keyword-post-add-box" title="Add New Keyword" style="display: none;">
    <div class="ib-row">
        <div>New Keyword:</div>
        <div id="ib-new-keyword" contenteditable="true"  class="ib-content-editable"></div>
    </div>
    <div class="ib-row">
        <div class="fl">
            <button id="ib-add-new-keyword" class="ib-button">Add</button>
        </div>
        <div class="clear"></div>
    </div>
</div>