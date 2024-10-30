<script type="text/javascript">
    (function($){
        $(document).ready( function () {
            $('.ib_data-tables').DataTable({
	            "order": [[ 0, "asc" ]],
                "columnDefs": [
                    { "orderable": false, "targets": [2] }
                ],
                "pageLength": 25
            });
        } );
    }(jQuery));
</script>

<div class="ib_list-buttons"><button class="ib-button" data-role="add-keyword"><span class="fa fa-plus"></span> New Keyword</button></div>

<div id="ib_keywords">
    <div class="ib-tabs" id="ib-tabs">
        <div class="tab-wrapper noselect">
            <a href="admin.php?page=keyword-admin&section=keyword_list" class="ib-tab-link selected">Keywords</a>
            <a href="admin.php?page=keyword-admin&section=keyword_manage" class="ib-tab-link">Import/Export</a></div>
        <div class="tabs">
            <!-- Manage Redirect Tabs -->
            <div id="ib_keywords">
                
                <table cellpadding="0" cellspacing="0" width="100%" class="ib_data-tables stripe">
                    <thead>
                        <tr>
                            <!-- <th>ID</th> -->
                            <th class="ib-main">Keyword</th>
                            <th>Pages Using Keyword</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody id="ib_cta_list">
                    <?php $i = 0; foreach($terms as $term):
                        $class = "";
                        if ($i++ % 2 == 0) $class="grey"; ?>
                        <tr class="<?php echo $class; ?>" id="ib_keyword-row-<?php echo $term->keyword_id; ?>">
                            <!-- <td id="keyword_id_<?php echo $term->keyword_id; ?>"><?php echo $term->keyword_id; ?></td> -->
                            <td class="ib-main" id="keyword_value_<?php echo $term->keyword_id; ?>"><a href="<?php echo $term->keyword_id; ?>" data-role="edit-keyword"><?php echo $term->keyword_value; ?></a></td>
                            <td><?php echo number_format($term->posts()->count()); ?></td>
                            <td>
                                <a href="<?php echo $term->keyword_id; ?>" data-role="delete-keyword" class="ib_icon-link delete fa fa-trash ib_delete-form"></a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div data-role="keyword-add-dialog" title="Add New Keyword">
        <div class="ib-row ib-td">
            <div>Keyword:</div>
            <div data-role="new-keyword" contenteditable="true" class="ib-content-editable"></div>
        </div>
        <div class="ib-row ib-td">
            <div class="fr"><button class="ib-button" data-action="keyword-add">Add</button></div>
            <div class="clear"></div>
        </div>
    </div>

    <div data-role="keyword-edit-dialog" title="Edit Keyword">
        <div class="ib-row ib-td">
            <div>Keyword:</div>
            <div data-role="editable-keyword" contenteditable="true" class="ib-content-editable"></div>
            <div data-role="editable-keyword-id" class="ib-hidden"></div>
        </div>
        <div class="ib-row ib-td">
            <div class="fr"><button class="ib-button" data-action="keyword-save-edit">Save</button></div>
            <div class="clear"></div>
        </div>
    </div>
</div>