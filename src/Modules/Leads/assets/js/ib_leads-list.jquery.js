!function($){$.widget("ui.dialog",$.extend({},$.ui.dialog.prototype,{_title:function(title){this.options.title?title.html(this.options.title):title.html("&#160;")}})),$.fn.ib_leadsList=function(method){var _jconfirm,_totalFilters,_totalColumns,_viewButtons,_columnsFilters,_ibThis,_dataTable,_theList,_filterScreens,_appliedFilters,_filtersForm,_tableView,_columnsForm,defaults={},methods={init:function(options){_ibThis.find(".settings-buttons .ib_tipsy").tipsy({gravity:"s"}),_totalFilters=$("#total-filters"),_totalColumns=$("#total-columns"),_viewButtons=$("#view_action_buttons"),_tableView=_ibThis.find("#leads-table-view"),_theList=_ibThis.find("#filter-fields-list"),_filters=_ibThis.find("#lead_filters"),_appliedFilters=_ibThis.find("#lead-applied-filters"),_columnsFilters=_ibThis.find("#lead_columns"),_filterScreens=_filters.find("#filter-screens"),_ibThis.find("#update_view_button").click(function(e){e.preventDefault(),methods.updateCurrentViewConfirm()}),methods.setupDataTables(),_ibThis.find("#filters-button").on("click",function(e){e.stopPropagation(),methods.showFilters()}),_ibThis.find("#columns-button").on("click",function(e){e.stopPropagation(),methods.showColumnsFilter()}),$("#lead-table-search").on("keyup",function(){methods.searchTable($(this).val())}),$(document).mouseup(function(e){var $datePicker=$("#ui-datepicker-div");_filters.is(e.target)||0!==_filters.has(e.target).length||$datePicker.is(e.target)||0!==$datePicker.has(e.target).length||!_filters.is(":visible")||(_filters.hide(),_filtersChanged&&methods.filterTableView(!0)),_columnsFilters.is(e.target)||0!==_columnsFilters.has(e.target).length||$datePicker.is(e.target)||0!==$datePicker.has(e.target).length||_columnsFilters.hide()}),_filtersForm=_ibThis.find("#lead_filters_form"),_filtersForm.find("select,input").on("change",function(){methods.handleFiltersChanged($(this))}),_filters.on("click","li",function(e){e.stopPropagation(),methods.showFilterFieldSettings($(this))}),_filterScreens.find(".ib-date").datepicker({dateFormat:"yy-mm-dd"}),_filterScreens.on("click",".fa-times",function(){methods.removeAppliedFilter($(this).parent().data("identifier"))}),_columnsForm=_ibThis.find("#lead_columns_form"),_columnsForm.on("click","input",function(){methods.checkVisibleColumns()}),$("#create_new_view_btn").click(function(e){e.preventDefault();var $form=$(this).parent();$form.hasClass("open")?methods.verifyNewViewForm($form):methods.showNewViewForm($form)}),$("#cancel_create_new_view_btn").click(function(e){e.preventDefault();var view=settings.views[settings.active_view];if("all"==view.lead_view_id){$(".ib_new_view-form");methods.clearNewViewSettings()}methods.hideNewViewForm($(this).parent())}),_leadViews=$("#ib_lead_views"),_leadViews.on("click","li",function(){methods.loadView($(this))}),_leadViews.on("click",".fa-trash",function(){methods.deleteLeadViewConfirm($(this).parent())}),methods.setSelectedFilters(settings.views[settings.active_view]),_tableView.on("click",".details-control",function(){methods.showRecentActivity($(this).closest("tr"))}),_tableView.on("click",".ib_archive-lead",function(e){e.preventDefault(),methods.archiveLeadConfirm($(this).closest("tr"))}),_tableView.on("click",".ib_restore-lead",function(e){e.preventDefault(),methods.restoreLeadConfirm($(this).closest("tr"))}),_activityDialog=$("#ib_activity_dialog"),_activityDialog.dialog({width:600,closeText:"Close",autoOpen:!1,dialogClass:"ib_ui-dialog",buttons:{Cancel:function(){$(this).dialog("close")},"Save Activity":function(){methods.handleSaveActivity()}},modal:!0,create:function(){$(this).closest(".ui-dialog").find(".ui-dialog-buttonpane .ui-button:first").addClass("ib_cancel")}}),_emailDialog=$("#ib_email_dialog"),_emailDialog.dialog({width:600,closeText:"Close",autoOpen:!1,dialogClass:"ib_ui-dialog",buttons:{Cancel:function(){var ed=tinymce.get("LeadEmailEmailBody");ed.setContent(""),$(this).dialog("close")},"Send Email":function(){methods.verifySendEmail()}},modal:!0,create:function(){$(this).closest(".ui-dialog").find(".ui-dialog-buttonpane .ui-button:first").addClass("ib_cancel")}});var $body=$("#ib_email_dialog_body");$body.find("#LeadEmailEmailType0,#LeadEmailEmailType1").on("click",function(){"email"==$(this).val()?($body.find("#ib_choose_email").show(),$body.find("#ib_choose_template").hide()):($body.find("#ib_choose_email").hide(),$body.find("#ib_choose_template").show())})},deleteLeadViewConfirm:function($view){var view_id=$view.data("view"),view_name=settings.views[view_id].view_name;$.confirm({title:"Delete Lead View?",content:"Are you sure you want to delete <strong>"+view_name+"</strong> view from your views?",confirm:function(){methods.deleteLeadView($view,view_id)},confirmButton:"Delete View",cancelButton:"Cancel",confirmButtonClass:"ib_delete",cancelButtonClass:"ib_cancel"})},deleteLeadView:function($view,view_id){$.ajax({type:"POST",dataType:"json",url:ibLeadAjax.ajaxurl,data:{action:"ib_delete_lead_view",lead_view_id:view_id,nonce:ibLeadAjax.ibLeadNonce},success:function(response){response.success?($prev=$view.prev(),$prev.trigger("click"),$view.remove(),delete settings.views[view_id],methods.clearNewViewSettings()):$.confirm({title:"Error!",content:response.message,confirmButton:"OK",confirmButtonClass:"ib_save"})}})},clearNewViewSettings:function(){$("#new_view_view_name").val(""),_viewButtons.hide()},archiveLeadConfirm:function($row){$.confirm({title:"Archive Lead?",content:"Are you sure you want to archive this lead?",confirm:function(){methods.archiveLead($row)},cancel:function(){_lockClick=!1},confirmButton:"Archive Lead",cancelButton:"Cancel",confirmButtonClass:"ib_delete",cancelButtonClass:"ib_cancel"})},archiveLead:function($row){var lead_id=$row.attr("lead-id");$.ajax({type:"POST",dataType:"json",url:ibLeadAjax.ajaxurl,data:{action:"ib_archive_lead",lead_id:lead_id,nonce:ibLeadAjax.ibLeadNonce},success:function(response){if(response.success){var arch=$("#ib_archived_leads_status").val();"only_active"==arch?_dataTable.row($row).remove().draw():$row.addClass("ib_lead-archived")}else $.confirm({title:"Error!",content:response.message,confirmButton:"OK",confirmButtonClass:"ib_save"})}})},restoreLeadConfirm:function($row){$.confirm({title:"Restore Lead?",content:"Are you sure you want to restore this lead?",confirm:function(){methods.restoreLead($row)},confirmButton:"Restore Lead",cancelButton:"Cancel",confirmButtonClass:"ib_delete",cancelButtonClass:"ib_cancel"})},restoreLead:function($row){var lead_id=$row.attr("lead-id");$.ajax({type:"POST",dataType:"json",url:ibLeadAjax.ajaxurl,data:{action:"ib_restore_lead",lead_id:lead_id,nonce:ibLeadAjax.ibLeadNonce},success:function(response){if(response.success){var arch=$("#ib_archived_leads_status").val();"archived"==arch?_dataTable.row($row).remove().draw():$row.removeClass("ib_lead-archived")}else $.confirm({title:"Error!",content:response.message,confirmButton:"OK",confirmButtonClass:"ib_save"})}})},searchTable:function($term){_dataTable.search($term).draw()},showNewViewForm:function($form){$form.addClass("open"),$("#update_view_button").hide(),$("#create_new_view_btn").html('<span class="fa fa-check"></span> Save View'),$form.find("input").css({width:0}).focus().show().animate({width:150},1e3),$form.find(".ib_cancel").css({width:0}).show().animate({width:76},1e3)},hideNewViewForm:function($form){$form.removeClass("open"),$("#create_new_view_btn").html('<span class="fa fa-plus"></span> New View'),$("#update_view_button").show(),$form.find("input").animate({width:0},1e3,function(){$(this).hide()}),$form.find(".ib_cancel").animate({width:0},1e3,function(){$(this).hide()})},updateCurrentViewConfirm:function(){var currentView=settings.views[settings.active_view];$.confirm({title:"Update "+currentView.view_name+" Settings?",content:"By clicking <strong>Update Settings</strong> you will overwrite the current filters and columns.",confirm:function(){methods.updateCurrentView()},confirmButton:"Update Settings",cancelButton:"Cancel",confirmButtonClass:"ib_save",cancelButtonClass:"ib_cancel"})},updateCurrentView:function(){var view=settings.views[settings.active_view];console.log(view);var data={action:"ib_update_lead_view",lead_view_id:view.lead_view_id,view_name:view.view_name,nonce:ibLeadAjax.ibLeadNonce,columns:view.view_columns,filters:view.view_filters,widths:view.view_columns_width,order:view.view_columns_order};$.ajax({url:ibLeadAjax.ajaxurl,dataType:"json",type:"post",data:data,success:function(response,status){response.success&&_viewButtons.hide(),$.alert({title:response.title,content:response.message,confirmButtonClass:"ib_save"})},error:function(jqXhr,textStatus,errorThrown){console.log(errorThrown),_lockClick=!1}})},verifyNewViewForm:function($form){if(!_lockClick){var name=$form.find("input").val().trim();if(name.length<1)$.alert({title:"Error!",content:"Please provide a name for the view.",confirmButtonClass:"ib_save"});else{_lockClick=!0;var view=settings.views[settings.active_view],data={action:"ib_new_lead_view",view_name:$("#new_view_view_name").val(),nonce:ibLeadAjax.ibLeadNonce,columns:view.view_columns,filters:view.view_filters,widths:view.hasOwnProperty("view_columns_width")?view.view_columns_width:[],order:view.hasOwnProperty("view_columns_order")?view.view_columns_order:[],display_order:_leadViews.children().length};$.ajax({url:ibLeadAjax.ajaxurl,dataType:"json",type:"post",data:data,success:function(response,status){response.success&&methods.displayNewView(response.view),_lockClick=!1},error:function(jqXhr,textStatus,errorThrown){console.log(errorThrown),_lockClick=!1}})}}},showRecentActivity:function($tr){var lead_id=$tr.attr("lead-id"),$row=_dataTable.row($tr);return $row.child.isShown()?($row.child.hide(),$tr.removeClass("lead-details-shown"),void $tr.find("td.details-control span").attr("class","fa fa-chevron-right")):void $.ajax({type:"POST",dataType:"html",url:ibLeadAjax.ajaxurl,data:{action:"ib_lead_recent_history",lead_id:lead_id,nonce:ibLeadAjax.ibLeadNonce},success:function(response){$tr.find("td.details-control span").attr("class","fa fa-chevron-down"),$tr.addClass("lead-details-shown");var parentClass=$tr.hasClass("odd")?"odd":"even";$row.child(response,"ib_lead-recent-history "+parentClass).show()}})},displayNewView:function(view){_viewButtons.hide(),$("#cancel_create_new_view_btn").trigger("click"),settings.views[view.lead_view_id]=view,settings.active_view=view.lead_view_id,_leadViews.find("li.active").removeClass("active"),_leadViews.append('<li class="active" data-view="'+view.lead_view_id+'">'+view.view_name+'<span class="fa fa-trash"></span></li>'),methods.setCookie("inboundbrew_active_lead_view",view.lead_view_id,30)},checkColumnOrder:function(){var columns=[];if(_tableView.find("thead th").each(function(){var $me=$(this),token=$me.data("field");void 0!=token&&columns.push(token)}),_initialTableSetup=!1,_viewButtons.show(),"all"==settings.views[settings.active_view].lead_view_id){var $form=$(".ib_new_view-form");$form.hasClass("open")||methods.showNewViewForm($form)}methods.setViewColumns(columns),methods.setViewColumnsOrder(),methods.initializeColumnResize()},setViewColumnsOrder:function(){settings.views[settings.active_view].view_columns_order=_dataTable.colReorder.order()},checkVisibleColumns:function(){var columns=[];_columnsForm.find('input[type="checkbox"]').each(function(){var $input=$(this);if($input.is(":checked")){var token=$input.data("token");columns.push(token)}});var view=settings.views[settings.active_view];if(_viewButtons.show(),"all"==view.lead_view_id){var $form=$(".ib_new_view-form");$form.hasClass("open")||methods.showNewViewForm($form)}methods.handleVisibleColumns(columns),methods.checkColumnOrder()},setupDataTables:function(){var $table=_ibThis.find(".ib_data-tables");$.fn.dataTable.isDataTable("#"+$table.attr("id"))&&_dataTable.destroy(),_initialTableSetup=!0,_dataTable=$table.DataTable({order:[[0,"desc"]],columnDefs:[{orderable:!1,targets:[-2,-1]}],pageLength:25,asStripeClasses:["alt0","alt1"],sDom:"Rt<lp>",colReorder:{reorderCallback:function(){methods.checkColumnOrder()}}});var view=settings.views[settings.active_view],columns=view.view_columns;methods.handleVisibleColumns(columns),view.hasOwnProperty("view_columns_order")&&view.view_columns_order.length>0&&_dataTable.colReorder.order(view.view_columns_order),$table.show(),$table.find(".ib_tipsy").tipsy({gravity:"s"}),_tableView=$table,_tableView.on("click",".ib_action-menu li",function(e){e.stopPropagation();var $me=$(this),$row=$me.closest("tr");methods.handleNewActivity($me,$row)}),_tableView.on("click",".fa-plus",function(e){e.preventDefault()}),_tableView.on("click",".ib_preview_sent_email",function(e){e.preventDefault(),methods.previewLeadEmail($(this))})},initializeColumnResize:function(){_ibThis.find(".JCLRgrips").remove(),setTimeout(function(){_tableView.colResizable({liveDrag:!1,draggingClass:"dragging",resizeMode:"flex",onResize:function(){methods.setColumnsWidth()}})},500)},applyColumnWidths:function(){var widths=settings.views[settings.active_view].view_columns_width;for(var token in widths){var w=widths[token];_tableView.find('th[data-field="'+token+'"]').width(w)}},setColumnsWidth:function(){var widths={};_tableView.find("th").each(function(){var $me=$(this),token=$me.attr("data-field");void 0!=token&&(widths[token]=$me.width())}),methods.setViewWidths(widths),_viewButtons.show()},handleVisibleColumns:function(columns){var count=columns.length;count>0?_totalColumns.html(count).show():_totalColumns.hide();var columnIndex=0;for(var field_token in settings.filters.static_fields){var column=(settings.filters[field_token],_dataTable.column(columnIndex)),visible=!0;$.inArray(field_token,columns)==-1&&(visible=!1),column.visible(visible),columnIndex++}for(var field_token in settings.filters.custom_fields){var column=(settings.filters[field_token],_dataTable.column(columnIndex)),visible=!0;$.inArray(field_token,columns)==-1&&(visible=!1),column.visible(visible),columnIndex++}},removeAppliedFilter:function(identifier){_filterScreens.find('.selected-filter[data-identifier="'+identifier+'"]').each(function(){var $me=$(this),linked=$me.attr("linked-checkbox");void 0!=linked&&$("#"+linked).prop("checked",!1),$me.remove()}),_filtersChanged=!0,_viewButtons.show()},handleFiltersChanged:function($input){var node=$input[0].nodeName.toLowerCase(),token=$input.data("token"),field_type=$input.data("field"),fields=settings.filters[field_type+"_fields"],label="archived_leads"==token?"Archived Leads":fields[token].label,replaceFilters=!1,appliedElement="",selectedElement="";switch(node){case"select":if("archived_leads"==token){var value=$input.val(),identifier=field_type+token;switch(value){case"only_active":var text="Active Leads Only";break;case"archived":var text="Archived Leads Only";break;case"all":var text="Archived and Active Leads"}selectedElement='<div class="selected-filter" data-identifier="'+identifier+"\">\t\t\t\t\t\t\t\t\t<input type=\"hidden\" id='ib_archived_leads_status' name='data[LeadAppliedFilter]["+field_type+"]["+token+"]' value=\""+value+'">'+text+"</div>",text=label+": "+text,appliedElement='<div class="selected-filter" data-identifier="'+identifier+'">'+text+"</div>",replaceFilters=!0}else{var value=$input.val();if(value.length<1)return;var text=$input.find('option[value="'+value+'"]').text(),identifier=field_type+token+value;selectedElement='<div class="selected-filter" data-identifier="'+identifier+'">\t\t\t\t\t\t\t\t\t<input type="hidden" name=\'data[LeadAppliedFilter]['+field_type+"]["+token+"][]' value=\""+value+'">'+text+' <span class="fa fa-times"> </span>\t\t\t\t\t\t\t\t\t</div>',text=label+": "+text,appliedElement='<div class="selected-filter" data-identifier="'+identifier+'">'+text+' <span class="fa fa-times"> </span></div>'}break;case"input":if($input.hasClass("hasDatepicker")){var $parent=$input.closest(".filter-screen"),start=$parent.find('input[data-range="range_start"]').val(),end=$parent.find('input[data-range="range_end"]').val(),identifier=field_type+token,text=start+" - "+end;selectedElement='<div class="selected-filter" data-identifier="'+identifier+'">\t\t\t\t\t\t\t\t<input type="hidden" name=\'data[LeadAppliedFilter]['+field_type+"]["+token+"][start]'  value=\""+start+'">\t\t\t\t\t\t\t\t<input type="hidden" name=\'data[LeadAppliedFilter]['+field_type+"]["+token+"][end]'  value=\""+end+'">\t\t\t\t\t\t\t\t'+text+' <span class="fa fa-times"> </span>\t\t\t\t\t\t\t\t</div>',text=label+": "+text,appliedElement='<div class="selected-filter" data-identifier="'+identifier+'">'+text+"</div>",replaceFilters=!0}else{var type=$input.data("type");if("checkbox"==type){var identifier=field_type+token+"notset";if($input.is(":checked")){var text="Not Set";selectedElement='<div class="selected-filter" data-identifier="'+identifier+'" linked-checkbox="'+$input.attr("id")+'">\t\t\t\t\t\t\t\t\t\t<input type="hidden" name=\'data[LeadAppliedFilter]['+field_type+"]["+token+'][not_set]\' value="1">\t\t\t\t\t\t\t\t\t\t'+text+' <span class="fa fa-times"> </span>\t\t\t\t\t\t\t\t\t\t</div>';var text=label+": "+text;appliedElement='<div class="selected-filter" data-identifier="'+identifier+'">'+text+"</div>"}else methods.removeAppliedFilter(identifier)}else{var value=$input.val();if(value.length<1)return;var text=value;selectedElement='<div class="selected-filter" data-identifier="'+identifier+'">\t\t\t\t\t\t\t\t\t<input type="hidden" name=\'data[LeadAppliedFilter]['+field_type+"]["+token+"][]' value=\""+value+'">'+text+' <span class="fa fa-times"> </span>\t\t\t\t\t\t\t\t\t</div>',text=label+": "+text,appliedElement='<div class="selected-filter" data-identifier="'+identifier+'">'+text+"</div>",replaceFilters=!0}}}var selectedFilters=$input.closest(".filter-screen").find(".selected-filters");replaceFilters&&($prev=selectedFilters.find('.selected-filter[data-identifier="'+identifier+'"]'),$prev.length&&($prev.remove(),_appliedFilters.find('.selected-filter[data-identifier="'+identifier+'"]').remove())),selectedElement.length>0&&selectedFilters.append(selectedElement),appliedElement.length>0&&_appliedFilters.append(appliedElement),_filtersChanged=!0,_viewButtons.show();var view=settings.views[settings.active_view];if("all"==view.lead_view_id){var $form=$(".ib_new_view-form");$form.hasClass("open")||methods.showNewViewForm($form)}methods.calculateSelectedFields()},calculateSelectedFields:function(){_filterScreens.find(".filter-screen").each(function(){var $screen=$(this),token=$screen.attr("id"),count=$screen.find(".selected-filter").length,$bubble=_theList.find('li[data-token="'+token+'"] .bubble');$bubble.html(count),count>0?("applied-filters"==token&&_totalFilters.html(count).show(),$bubble.show()):("applied-filters"==token&&_totalFilters.hide(),$bubble.hide())})},showFilters:function(){_filters.show(),_columnsFilters.hide()},showColumnsFilter:function(){_filters.hide(),_columnsFilters.show()},showFilterFieldSettings:function($field){var $active=_filters.find("li.active"),token=$active.data("token");_filterScreens.find("#"+token).hide(),$active.removeClass("active"),$field.addClass("active"),token=$field.data("token"),_filterScreens.find("#"+token).show()},viewColumnResized:function(column){},setViewFilters:function(){var data=_filtersForm.serializeObject(),filters=data.data.LeadAppliedFilter;settings.views[settings.active_view].view_filters=filters},setViewColumns:function(columns){settings.views[settings.active_view].view_columns=columns},setViewWidths:function(widths){settings.views[settings.active_view].view_columns_width=widths},filterTableView:function(set_view_filters){set_view_filters&&methods.setViewFilters(),$.ajax({url:ibLeadAjax.ajaxurl,dataType:"html",type:"post",data:_filtersForm.serialize()+"&nonce="+ibLeadAjax.ibLeadNonce,success:function(response,status){_filtersChanged=!1,_tableView.html(response),methods.setupDataTables()},error:function(jqXhr,textStatus,errorThrown){console.log(errorThrown),_lockClick=!1}})},loadView:function($viewtab){if(!$viewtab.hasClass("active")){var view_id=$viewtab.data("view");settings.active_view=view_id;var view=settings.views[view_id];_leadViews.find("li.active").removeClass("active"),$viewtab.addClass("active"),methods.setSelectedColumns(view),methods.setSelectedFilters(view),methods.filterTableView(!1),methods.setCookie("inboundbrew_active_lead_view",view_id,30)}},setSelectedColumns:function(view){_columnsFilters.find('input[type="checkbox"]').each(function(){var checked=!1,$me=$(this),token=$me.attr("data-token");$.inArray(token,view.view_columns)!=-1&&(checked=!0),$me.prop("checked",checked)}),methods.handleVisibleColumns(view.view_columns)},setSelectedFilters:function(view){var appliedElements="";_filterScreens.find(".filter-screen").each(function(){var $screen=$(this),token=$screen.attr("id");if("applied-filters"!=token){var field_type=$screen.data("field-type"),label=$screen.data("label"),type=$screen.attr("data-type"),$selected=$screen.find(".selected-filters"),selectedStr="",$notSetCheckbox=$screen.find('input[type="checkbox"]');if($notSetCheckbox.prop("checked",!1),view.view_filters.hasOwnProperty(field_type)){var type_filters=view.view_filters[field_type];if(type_filters.hasOwnProperty(token)){var token_filters=type_filters[token];switch(type){case"select":if("archived_leads"==token){var value=token_filters,identifier=field_type+token;switch(value){case"only_active":var text="Active Leads Only";break;case"archived":var text="Archived Leads Only";break;case"all":var text="Archived and Active Leads"}selectedStr+='<div class="selected-filter" data-identifier="'+identifier+"\">\t\t\t\t\t\t\t\t\t\t\t\t<input type=\"hidden\" id='ib_archived_leads_status' name='data[LeadAppliedFilter]["+field_type+"]["+token+"]' value=\""+value+'">'+text+"</div>";var text="Archived Leads: "+text;appliedElements+='<div class="selected-filter" data-identifier="'+identifier+'">'+text+"</div>"}else for(var f in token_filters)if("not_set"==f){$notSetCheckbox.prop("checked",!0);var text="Not Set",identifier=field_type+token+"notset";selectedStr+='<div class="selected-filter" data-identifier="'+identifier+'" linked-checkbox="'+$notSetCheckbox.attr("id")+'">\t\t\t\t\t\t\t\t\t\t\t\t\t\t<input type="hidden" name=\'data[LeadAppliedFilter]['+field_type+"]["+token+'][not_set]\' value="1">\t\t\t\t\t\t\t\t\t\t\t\t\t\t'+text+' <span class="fa fa-times"> </span>\t\t\t\t\t\t\t\t\t\t\t\t\t\t</div>';var text=label+": "+text;appliedElements+='<div class="selected-filter" data-identifier="'+identifier+'">'+text+"</div>"}else{var value=token_filters[f],identifier=field_type+token+value,text=$screen.find('select option[value="'+value+'"]').text();selectedStr+='<div class="selected-filter" data-identifier="'+identifier+'">\t\t\t\t\t\t\t\t\t\t\t\t\t<input type="hidden" name=\'data[LeadAppliedFilter]['+field_type+"]["+token+"][]' value=\""+value+'">'+text+' <span class="fa fa-times"> </span>\t\t\t\t\t\t\t\t\t\t\t\t\t</div>',text=label+": "+text,appliedElements+='<div class="selected-filter" data-identifier="'+identifier+'">'+text+' <span class="fa fa-times"> </span></div>'}break;case"date":var start=token_filters.start,end=token_filters.end,identifier=field_type+token,text=start+" - "+end;selectedStr+='<div class="selected-filter" data-identifier="'+identifier+'">\t\t\t\t\t\t\t\t\t\t<input type="hidden" name=\'data[LeadAppliedFilter]['+field_type+"]["+token+"][start]'  value=\""+start+'">\t\t\t\t\t\t\t\t\t\t<input type="hidden" name=\'data[LeadAppliedFilter]['+field_type+"]["+token+"][end]'  value=\""+end+'">\t\t\t\t\t\t\t\t\t\t'+text+' <span class="fa fa-times"> </span>\t\t\t\t\t\t\t\t\t\t</div>',text=label+": "+text,appliedElements+='<div class="selected-filter" data-identifier="'+identifier+'">'+text+' <span class="fa fa-times"> </span></div>';break;case"default":var value=$input.val();if(value.length<1)return;var text=value;selectedStr+='<div class="selected-filter" data-identifier="'+identifier+'">\t\t\t\t\t\t\t\t\t\t\t<input type="hidden" name=\'data[LeadAppliedFilter]['+field_type+"]["+token+"][]' value=\""+value+'">'+text+' <span class="fa fa-times"> </span>\t\t\t\t\t\t\t\t\t\t\t</div>',text=label+": "+text,appliedElements+='<div class="selected-filter" data-identifier="'+identifier+'">'+text+"</div>"}}}$selected.html(selectedStr)}}),_appliedFilters.html(appliedElements),methods.calculateSelectedFields()},handleSaveActivity:function(){var text=$("#LeadActivityComment").val();text.trim().length>0&&(_activityDialog.dialog("close"),methods.saveActivity())},saveActivity:function(){$.ajax({url:ibLeadAjax.ajaxurl,dataType:"json",type:"post",data:$("#ib_lead_activity").serialize()+"&nonce="+ibLeadAjax.ibLeadNonce,success:function(response,status){response.success?($.alert({title:"Activity Added.",content:response.message,confirmButtonClass:"ib_save"}),$("#LeadActivityComment").val(""),$("#lead_activity_type").val(""),$("#LeadActivityHistoryId").val("")):$.alert({title:"Error!",content:response.message,confirmButtonClass:"ib_save"})},error:function(jqXhr,textStatus,errorThrown){console.log(errorThrown),_lockClick=!1}})},handleNewActivity:function($li,$row){var action=$li.data("action");$("#LeadActivityComment").val(""),$("#LeadActivityHistoryId").val("");var lead_id=$row.attr("lead-id");switch(action){case"comment":_activityDialog.find("#LeadActivityLeadId").val(lead_id),_activityDialog.dialog("option","title",'<span class="fa fa-comment"> </span> New Comment For '+$row.attr("lead-name")+":"),_activityDialog.dialog("open"),$("#lead_activity_type").val("comment");break;case"phone":_activityDialog.find("#LeadActivityLeadId").val(lead_id),_activityDialog.dialog("option","title",'<span class="fa fa-phone-square"> </span> New Phone Call For '+$row.attr("lead-name")+":"),_activityDialog.dialog("open"),$("#lead_activity_type").val("phone");break;case"email":_emailDialog.find("#LeadEmailLeadId").val(lead_id),_emailDialog.dialog("option","title",'<span class="fa fa-envelope"> </span> Send Email To '+$row.attr("lead-name")+":"),_emailDialog.dialog("open")}},verifySendEmail:function(){var type=$("#LeadEmailEmailType0").is(":checked")?"email":"template",errors=[];switch(type){case"email":var email_id=$("#LeadEmailEmailId").val();0==email_id.length&&errors.push("Please choose an email to use.");break;case"template":var template_id=$("#LeadEmailEmailTemplateId").val();0==template_id.length&&errors.push("Please choose an email template to use.");var email_subject=$("#LeadEmailEmailSubject").val().trim();0==email_subject.length&&errors.push("Please provide an email subject.")}if(errors.length>0){var content="Please correct the following errors before sending the email:<ul>";for(var e in errors)content+="<li>"+errors[e]+"</li>";content+="</ul>",$.alert({title:"Error!",content:content,confirmButton:"Ok",confirmButtonClass:"ib_save"})}else{_emailDialog.dialog("close"),$("#LeadEmailMessageBody").val(tinymce.get("LeadEmailEmailBody").getContent());var ed=tinymce.get("LeadEmailEmailBody");ed.setContent(""),$.ajax({url:ibLeadAjax.ajaxurl,dataType:"json",type:"post",data:$("#ib_send_lead_email").serialize(),success:function(response,status){$.alert({title:response.title,content:response.message,confirmButton:"Ok",confirmButtonClass:"ib_save"})},error:function(jqXhr,textStatus,errorThrown){console.log(errorThrown),_lockClick=!1}})}},previewLeadEmail:function($previewButton){var href=$previewButton.attr("href");href+="&nonce="+ibLeadAjax.ibLeadNonce;var content='<iframe width="100%" src="'+href+'" id="email-quick-preview"></iframe>';_jconfirm=$.alert({title:"Email Preview",content:content,confirmButton:"CLOSE",confirmButtonClass:"ib_save",columnClass:"ib-template-preview-float",icon:"fa fa-eye",onOpen:function(){$("#email-quick-preview").on("load",function(){var $me=$(this),$body=$me.contents().find("body");if($body.length){var height=parseFloat($body.outerHeight())+20;$me.attr("height",height+"px")}methods.recenterModal()})}})},recenterModal:function(){_jconfirm.setDialogCenter()},setCookie:function(c_name,value,expiredays){var exdate=new Date;exdate.setDate(exdate.getDate()+expiredays),document.cookie=c_name+"="+escape(value)+(null==expiredays?"":";expires="+exdate.toGMTString())},getCookie:function(c_name){return document.cookie.length>0&&(c_start=document.cookie.indexOf(c_name+"="),c_start!=-1)?(c_start=c_start+c_name.length+1,c_end=document.cookie.indexOf(";",c_start),c_end==-1&&(c_end=document.cookie.length),unescape(document.cookie.substring(c_start,c_end))):""}},_filtersChanged=!1,_lockClick=!1,_initialTableSetup=!0,settings=$.extend({},defaults,method);return methods[method]?(_ibThis=$(this),methods[method].apply(this,Array.prototype.slice.call(arguments,1))):"object"!=typeof method&&method?void $.error("Method "+method+" does not exist on jQuery.ib_leadsList"):this.each(function(){_ibThis=$(this),methods.init()})}}(jQuery);