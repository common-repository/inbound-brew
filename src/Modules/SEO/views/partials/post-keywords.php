<?php
/**
 * Created by sean.carrico.
 * User: sean
 * Date: 7/17/15
 * Time: 2:06 PM
 */
?>
<div id="ib-keyword-holder" class="ib-row" style="min-height: 25px;">
	<div class="ib-row">
    	<div>Add Keywords to Post:</div>
    	<div id="autocomplete" contenteditable="true" class="ib-content-editable"></div>
    	<?php $total_keywords = isset($words)? count($words) : 0; ?>
    	<div class="ib-keyword-list" id="ib_keyword_list" data-counter="<?php echo $total_keywords; ?>">
	    	<?php if (isset($words) && !empty($words)):
		    	$counter = 0;
				foreach($words as $word):
					$used_class = (isset($word->used) && $word->used !== false)? 'ib-used':'';
					echo "<input type=\"hidden\" name=\"Keyword[{$counter}][keyword_id]\" value=\"{$word->keyword_id}\" id=\"ib_keyword_hidden_{$word->keyword_id}\" \>
					<input type=\"hidden\" name=\"Keyword[{$counter}][is_deleted]\" value=\"0\" id=\"ib_keyword_deleted_{$word->keyword_id}\" \>
					<input type=\"hidden\" name=\"Keyword[{$counter}][keyword]\" value=\"{$word->keyword_value}\" id=\"ib_keyword_deleted_{$word->keyword_id}\" \>
					<div class=\"ib-pill-small {$used_class}\" data-id=\"{$word->keyword_id}\">{$word->keyword_value}</div>";
					$counter ++;
				endforeach;
			endif; ?>
			<div class="clear" id="ib_keyword_list_clear"></div>
		</div>
    </div>
</div>