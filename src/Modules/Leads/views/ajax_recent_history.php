<?php if(empty($lead_history)): ?>
	<h3>No Lead History</h3>
<?php else: ?>
	<?php foreach($lead_history as $item):
		$match = true;
		$add_edit = false;
		switch($item->history_type):
	        case BREW_LEAD_HISTORY_TYPE_CREATED:
	            $icon = 'fa-user';
	            break;
	        case BREW_LEAD_HISTORY_TYPE_UPDATED:
	        	$icon = 'fa-pencil';
	        break;	
	        case BREW_LEAD_HISTORY_TYPE_FORM_SUBMISSION:
	            $icon = 'fa-check';
	            break;
	        case BREW_LEAD_HISTORY_TYPE_NOTE:
	            $icon = 'fa-comment';
	            $add_edit = true;
	            break;
	        case BREW_LEAD_HISTORY_TYPE_PHONE_CALL:
	            $icon = 'fa-phone-square';
	            $add_edit = true;
	            break;
	        case BREW_LEAD_HISTORY_TYPE_EMAIL:
	            $icon = 'fa-envelope';
	            $url = "admin.php?page={$post_type}&action=ib_preview_lead_email&history_id={$item->history_id}";
	            $item->history_note = "To view sent email <a href=\"{$url}\" class=\"ib_preview_sent_email\">click here</a>.";
	            break;
	        case BREW_LEAD_HISTORY_TYPE_SHARED:
	            $icon = 'fa-share-square-o';
	            break;
	        case BREW_LEAD_HISTORY_TYPE_CONTENT_DOWNLOADED:
	            $icon = 'fa-download';
	            break;
	        case BREW_LEAD_HISTORY_TYPE_DELETED:
	        	$icon = 'fa-trash';
	        	$user_data = get_userdata($item->wp_user_id);
	        	$item->history_note = str_replace("{{user}}", "<span class=\"ib_wp_user\">" . $user_data->first_name . " " . $user_data->last_name  . "</span>", $item->history_note);
	        	break;
			case BREW_LEAD_HISTORY_TYPE_RESTORED:
				$icon = 'fa-undo';
				break;
			case BREW_LEAD_HISTORY_TYPE_PICTURE:
				$icon = 'fa-picture-o';
			break;
			case BREW_LEAD_HISTORY_ASSIGNED:
				$icon = 'fa-arrow-right';
				$match = false;
	            preg_match("/\{\{user:(.*)\}\}/",$item->history_event,$matches);
	            if(!empty($matches)):
	                $user = $matches[1];
	                $token = '{{user:'.$user."}}";
	                $user_data = get_userdata($user);
					$item->history_event = str_replace($token, $user_data->first_name." ".$user_data->last_name, $item->history_event);
	            endif;
			break;
	        default:
	            $icon = 'fa-file-text-o';
	        break;
	    endswitch; 
	?>
		<div class="lead_history" id="lead_history_<?php echo $item->history_id; ?>" data-id="<?php echo $item->history_id; ?>">
			<div class="history-date"><?php echo $Date->format(BREW_WP_DATE_FORMAT." ".BREW_WP_TIME_FORMAT,$item->created_at,true); ?></div>
			<span class="history-icon"><span class="fa <?php echo $icon; ?>"> </span></span>
			<div class="history-event">
				<span class="history-user">
					<?php if($item->wp_user_id):
		               $user_info = get_userdata($item->wp_user_id);
		               $first_name = $user_info->first_name;
					   $last_name = $user_info->last_name;
		               echo $first_name . " " . $last_name;
					else:
						echo "System";
		        	endif; ?>:
		        </span>
		        <?php if($match):
	                    preg_match("/\{\{(.*)\}\}/", $item->history_event,$matches);
	                    if(!empty($matches)):
	                        $token = $matches[1];
	                        $item->history_event = str_replace("{{".$token."}}", $field_tokens[$token], $item->history_event);
	                    endif;
	                endif;
	                echo $item->history_event;
	                if(!empty($item->history_note)):
	                	echo "<br><span class='history-note'>". stripslashes($item->history_note) . "</span>";
	                endif; ?>
	        </div>
	        <div class="clear"></div>
		</div>
	<?php endforeach;
endif; ?>