<script type="text/javascript">
    (function($){
        $(document).ready( function () {
            $('.ib_data-tables').DataTable({
	            "order": [[ 3, "asc" ]],
                "columnDefs": [
                    { "orderable": false, "targets": [0,8] }
                ],
                "pageLength": 25
            });
            $(".ib-lead-rating").ib_stars({
                edit_text : "<?php echo _('edit'); ?>",
                save_text : "<?php echo _("save"); ?>"
            });
        });
    }(jQuery));
</script>
<div id="ib_leads">
    <div class="ib-row">
	    <div class="fr"><a class="ib-button" href="admin.php?page=<?php echo $post_type; ?>&section=ib_add_lead">Add New Lead</a></div>
    </div>
    <?php //echo $leads->render();?>
    <div class="ib-tabs ib-row" id="ib-tabs">
        <div class="tab-wrapper noselect">
            <a href="admin.php?page=ib-leads-admin&section=list" class="ib-tab-link selected">Leads</a>
            <a href="admin.php?page=ib-leads-admin&section=manage" class="ib-tab-link">Import/Export</a>
        </div>
        <div class="tabs">
            <!-- Manage Redirect Tabs -->
            <div id="ib_leads">
                <div class="fl ib-column-6 ib-td">
                    <span style="height:1em;width:1em;background-color:mediumspringgreen;display:inline-block;"></span> Prospect&nbsp;
                    <span style="height:1em;width:1em;background-color:deepskyblue;display:inline-block;"></span> Lead&nbsp;
                    <span style="height:1em;width:1em;background-color:yellow;display:inline-block;"></span> Customer
                </div>
                <table cellpadding="0" cellspacing="0" width="100%" class="ib_data-tables stripe">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Rating</th>
                            <th class="ib-main">Name</th>
                            <th>Location</th>
                            <th>IP Address</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody id="ib_lead_list">
                    <?php foreach($leads as $lead):
	                    $edit_url = "admin.php?page=ib-leads-admin&section=lead&id=". $lead->lead_id;
                    ?>
                        <tr id="ib_lead-row-<?php echo $lead->lead_id; ?>">
                            <td><?php echo $lead->lead_id; ?></td>
                            <td data-order="<?php echo $lead->type_id;?>" data-search="<?php echo $lead->type_search; ?>"><span class="lead-type <?php echo $lead->type_style; ?>"></span></td>
                            <td data-order="<?php echo $lead->lead_score; ?>" data-search="<?php echo $lead->score_search; ?>">
                                <span class="ib-lead-rating" data-rating="<?php echo $lead->lead_score/20;?>" data-id="<?php echo $lead->lead_id; ?>"></span>
                            </td>
                            <td class="ib-main">
                                <?php echo isset($lead->lead_name)? "<a href=\"{$edit_url}\">".$lead->lead_name."</a>":'NA';?>
                                <br />
                                <a href="admin.php?page=ib-leads-admin&section=lead&id=<?php echo $lead->lead_id; ?>" class="ib-link"><?php echo$lead->lead_email;?></a>
                            </td>
                            <td><?php
                                echo isset($lead->lead_city)?$lead->lead_city.", ":'';
                                echo isset($lead->lead_state)?$lead->lead_state.'<br />':'';
                                echo isset($lead->country->country_name)?$lead->country->country_name:'';
                                ?>
                            </td>
                            <td><?php echo isset($lead->lead_ip)?$lead->lead_ip:'NA'; ?></td>
                            <td><?php echo $lead->created_at; ?></td>
                            <td><?php echo $lead->updated_at; ?></td>
                            <td>
	                            <?php $trash_url = wp_nonce_url("admin.php?page=ib-leads-admin&section=ib_delete_lead&lid={$lead->lead_id}","ib-delete-lead");?>
	                            <a href="<?php echo $edit_url; ?>" class="ib_icon-link fa fa-pencil"></a>
								<a href="<?php echo $trash_url; ?>" class="ib_icon-link delete fa fa-trash ib-confirm" data-confirm="Are you sure you want to delete this lead?"></a>	                            
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".ib-confirm").click(function(e){
			e.preventDefault();
			var $me = $(this);
			$.confirm({
		        title: "Confirm?",
		        content: $me.attr('data-confirm'),
		        confirm: function(){
			    	window.location.href = $me.attr('href');
			    },
			    confirmButtonClass: 'ib_save',
				cancelButtonClass: 'ib_cancel'
			});
		});
	});
</script>