<div>
		<div class="ib-tabs" id="ib-tabs">
	    <div class="tab-wrapper noselect">
	        <a href="admin.php?page=ib-leads-admin&section=list" class="ib-tab-link">Leads</a>
	        <a href="admin.php?page=ib-leads-admin&section=manage" class="ib-tab-link">Import/Export</a>
	        <a href="admin.php?page=ib-leads-admin&section=settings" class="ib-tab-link selected">Settings</a>
	    </div>
	    <div class="tabs">
	        <div class="ib-row" style="padding: 2em 2em 2em .5em;">
	            <div class="ib-column ib-column-8">
	                <div class="ib-row">
	                    <div class="ib-th ib-column ib-column-12"><b>Custom Fields</b></div>
	                </div>
	                <div data-role="custom-field-wrapper" id="custom-field-wrapper">
	                    <div class="ib-row" data-role="field-holder">
	                        <div class="ib-column ib-column-6 ib-td text-left"><b>Name</b></div>
	                        <div class="ib-column ib-column-3 ib-td text-center"><b>Type</b></div>
	                        <div class="ib-column ib-column-3 ib-td text-center"><b>Options</b></div>
	                    </div>
	                <?php $i=1; foreach($forms as $form): ?>
	                    <div class="ib-row <?php echo($i % 2)?' ib-white':'';?>" data-role="field-holder" id="ib-customfield-row-<?php echo $form->field_id; ?>">
	                        <div class="ib-column ib-column-6 ib-td text-left ib-list-item">
		                        <span id="ib-field-name"><?php echo $form->field_name; ?></span><br>
		                        <a href="" class="ib-link ib-edit-link" data-id="<?php echo $form->field_id;?>">[edit]</a>
		                        <a href="" class="ib-link ib-delete-link red" data-id="<?php echo $form->field_id;?>">[delete]</a>
		                    </div>
	                        <div class="ib-column ib-column-3 ib-td text-center" id="ib-field-type"><?php echo strtoupper($form->field_type); ?></div>
	                        <div class="ib-column ib-column-3 ib-td text-center" id="ib-field-options"><?php echo nl2br(stripslashes($form->field_value)); ?></div>
	                    </div>
	                <?php $i++; endforeach; ?>
	                </div>
	                <div class="ib-row ib-margin-top ib-white" style="padding: 2em;">
	                    <div class="ib-column-5 fl">
	                        Each lead has set field options. Create custom fields if
	                        you need additional information gathered in your
	                        contact forms.
	                    </div>
	                    <div class="fr">
	                        <button id="add-lead-field-button" type="button" class="ib-td ib-button" style="background-color:#0083CA;" data-role="add-lead-field">Add New</button>
	                    </div>
	                    <div class="clear"></div>
	                </div>
	            </div>
	
	            <div class="ib-column-4 ib-column" style="background-color: white;">
	                <div class="ib-row">
	                    <div class="ib-th ib-column ib-column-12"><b>Static Fields</b></div>
	                </div>
	                <div class="ib-row">
	                    <div class="ib-column ib-column-6 ib-td"><b>Name</b></div>
	                    <div class="ib-column ib-column-6 ib-td"><b>Type</b></div>
	                </div>
	                <div class="ib-row ib-white">
	                    <div class="ib-column ib-column-6 ib-td">Name</div>
	                    <div class="ib-column ib-column-6 ib-td">TEXT</div>
	                </div>
	                <div class="ib-row">
	                    <div class="ib-column ib-column-6 ib-td">Email</div>
	                    <div class="ib-column ib-column-6 ib-td">EMAIL</div>
	                </div>
	                <div class="ib-row ib-white">
	                    <div class="ib-column ib-column-6 ib-td">Address1</div>
	                    <div class="ib-column ib-column-6 ib-td">TEXT</div>
	                </div>
	                <div class="ib-row">
	                    <div class="ib-column ib-column-6 ib-td">Address2</div>
	                    <div class="ib-column ib-column-6 ib-td">TEXT</div>
	                </div>
	                <div class="ib-row ib-white">
	                    <div class="ib-column ib-column-6 ib-td">City</div>
	                    <div class="ib-column ib-column-6 ib-td">TEXT</div>
	                </div>
	                <div class="ib-row">
	                    <div class="ib-column ib-column-6 ib-td">State</div>
	                    <div class="ib-column ib-column-6 ib-td">SELECT</div>
	                </div>
                    <div class="ib-row ib-white">
                        <div class="ib-column ib-column-6 ib-td">Country</div>
                        <div class="ib-column ib-column-6 ib-td">SELECT</div>
                    </div>
	                <div class="ib-row">
	                    <div class="ib-column ib-column-6 ib-td">Zip/Postal Code</div>
	                    <div class="ib-column ib-column-6 ib-td">TEXT</div>
	                </div>
	                <div class="ib-row ib-white">
	                    <div class="ib-column ib-column-6 ib-td">Phone</div>
	                    <div class="ib-column ib-column-6 ib-td">TEXT</div>
	                </div>
	                <div class="ib-row">
	                    <div class="ib-column ib-column-6 ib-td">Date of Birth</div>
	                    <div class="ib-column ib-column-6 ib-td">DATE</div>
	                </div>
	            </div>
	        </div>
	    </div>
	    <div id="lead-settings-dialog" title="Add Custom LeadField">
	        <div class="ib-row" style="border-bottom:1px dashed #B7B7B7"></div>
	        <div class="ib-row">
	            <div class="ib-column ib-column-3 ib-td">
	                <label for="field_type">Type: </label>
	            </div>
	            <div class="ib-column ib-column-9 ib-td">
	                <select data-role="form-type" name="context">
	                    <option value="">Select Type</option>
	                    <option value="text">Text</option>
	                    <option value="date">Date</option>
	                    <option value="textarea">Textarea</option>
	                    <option value="radio">Radio</option>
	                    <option value="checkbox">Checkbox</option>
	                    <option value="select">Select</option>
	                </select>
	            </div>
	        </div>
	        <div data-role="field-options-holder">
	        </div>
	    </div>
	    <!-- edit custom lead field dialog -->
	    <div id="edit-lead-settings-dialog" title="Edit Lead Field">
	        <div style="border-bottom:1px dashed #B7B7B7" class="ib-row"></div>
	        <div class="ib-row">
	            <div class="ib-column ib-column-3 ib-td">
	                <label for="field_type">Type: </label>
	            </div>
	            <div class="ib-column ib-column-9 ib-td">
	                <strong id="ib-edit-field-type">&nbsp;</strong>
	            </div>
	        </div>
	        <div data-role="field-options-holder">
		        <form id="custom-field-edit" action="">
			        <input type="hidden" value="edit_custom_lead_field" name="action">
			        <input type="hidden" name="lead_field_id" value="" id="ib-edit-field-id"/>
					<div class="ib-row ib-tr">
						<div class="ib-column ib-column-3 ib-td">
	                    	<label class="ib-required" for="name">Label</label><br>
	                	</div>
						<div class="ib-column ib-column-9 ib-td">
	                    	<input type="text" style="width:99%" data-type="text" class="ib-required" name="name" id="ib-edit-field-name">
	                	</div>
					</div>
		            <div class="ib-row ib-tr"  id="ib-edit-field-options-div">
		                <div class="ib-column ib-column-3 ib-td">
		                    <label class="ib-required" for="options">Options:</label>
		                </div>
		                <div class="ib-column ib-column-9 ib-td">
		                    <textarea style="width:99%;" class="ib-required" name="options" id="ib-edit-field-options"></textarea>
		                    <div class="ib-notes">Enter each option in a new line</div>
		                </div>
		            </div>
		            <div class="clear"></div>
		            <a href="" class="ib-button" id="submit-edit-button">Update</a>
		        </form>
    		</div>
	    </div>
	</div>
</div>