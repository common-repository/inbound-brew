!function($){$.widget("ui.dialog",$.extend({},$.ui.dialog.prototype,{_title:function(title){this.options.title?title.html(this.options.title):title.html("&#160;")}})),$.fn.ib_leadEditor=function(method){var _ibThis,_form,_leadName,_mediaWindow,_previewImage,_activityLog,_dataTable,_hiddenForm,_activityDialog,_emailDialog,_jconfirm,_lockClick,defaults={data:"",empty_name:"Lead Name",hidden_form_selector:"#ib_update_lead_field",mode:"add"},methods={init:function(options){if(_form=_ibThis.find("#lead_editor_form"),$.fn.datepicker&&_ibThis.find(".ib-date").datepicker(),_ibThis.find("#LeadCountryId").change(function(){var selected=$(this).val();console.log("selected",selected),228==selected||0==selected?(_ibThis.find("#us_states").show(),_ibThis.find("#nonus_states").hide()):(_ibThis.find("#us_states").hide(),_ibThis.find("#nonus_states").show())}).trigger("change"),_ibThis.on("keypress",":input:not(textarea)",function(event){13==event.keyCode&&event.preventDefault()}),_leadName=_ibThis.find("#lead-name"),_ibThis.find("#LeadLeadFirstName").on("keyup",function(){methods.copyLeadName()}),_ibThis.find("#LeadLeadLastName").on("keyup",function(){methods.copyLeadName()}),_ibThis.find("#LeadLeadEmail,#LeadLeadPhone").on("keyup",function(){methods.copyLeadDescription()}),_previewImage=$("#lead_preview_image"),_previewImage.click(function(){methods.chooseImage()}),"edit"==settings.mode){if(_form.validate({submitHandler:function(form){form.submit()},invalidHandler:function(){$.alert({title:"Form Error!",content:"There's a problem with your form submission. Please verify and try it again.",confirmButtonClass:"ib_save"})}}),_ibThis.find("#cancel_form").click(function(e){e.preventDefault(),$.confirm({title:"Cancel?",content:"Are you sure you want to leave this page?",confirm:function(){window.href.location="admin.php?page="+settings.post_type},confirmButton:"Leave This Page",cancelButton:"Stay",confirmButtonClass:"ib_save",cancelButtonClass:"ib_cancel"})}),_activityLog=$("#lead_activity_log"),_activityLog.length>0){var $table=_activityLog.find(".ib_data-tables");_dataTable=$table.dataTable({bFilter:!1,order:[[0,"desc"]],columnDefs:[{orderable:!1,targets:[0,1,2,3]}],pageLength:25,asStripeClasses:["alt0","alt1"],sDom:"t<lp>",aoColumns:[null,{sClass:"ib_activity-icon"},null,{sClass:"history-user"}]})}_ibThis.find(".hidden-text").on("click",function(event){event.stopPropagation(),methods.handleHiddenInputField($(this))}),$(document).on("click",function(event){var nodeName=event.target.nodeName.toLowerCase(),exempt=["textarea","input","select"];$.inArray(nodeName,exempt)==-1&&methods.checkForVisibleFields()}),_hiddenForm=_ibThis.find(settings.hidden_form_selector),_form.find('input[type="email"],input[type="text"],textarea,select').on("change",function(){methods.fieldChanged($(this),"text")}),_form.find('input[type="radio"]').on("click",function(){methods.fieldChanged($(this),"radio")}),_form.find('input[type="checkbox"]').on("click",function(){methods.fieldChanged($(this),"checkbox")});var $activityButton=$("#new_activity");$activityButton.click(function(e){e.preventDefault()}),$activityButton.find("li").on("click",function(){methods.handleNewActivity($(this))}),_activityDialog=$("#ib_activity_dialog"),_activityDialog.dialog({width:600,closeText:"Close",autoOpen:!1,dialogClass:"ib_ui-dialog",buttons:{Cancel:function(){$(this).dialog("close")},"Save Activity":function(){methods.handleSaveActivity()}},modal:!0,create:function(){$(this).closest(".ui-dialog").find(".ui-dialog-buttonpane .ui-button:first").addClass("ib_cancel")}}),_emailDialog=$("#ib_email_dialog"),_emailDialog.dialog({width:600,closeText:"Close",autoOpen:!1,dialogClass:"ib_ui-dialog",buttons:{Cancel:function(){$(this).dialog("close")},"Send Email":function(){methods.verifySendEmail()}},modal:!0,create:function(){$(this).closest(".ui-dialog").find(".ui-dialog-buttonpane .ui-button:first").addClass("ib_cancel")}}),_activityLog.on("click",".ib_edit_history",function(e){e.preventDefault(),methods.editHistory($(this).closest("tr"))}),_activityLog.on("click",".ib_preview_sent_email",function(e){e.preventDefault(),methods.previewLeadEmail($(this))})}else $("#LeadLeadEmail").on("blur",function(){methods.verifyEmail($(this).val())});var $body=$("#ib_email_dialog_body");$body.find("#LeadEmailEmailType0,#LeadEmailEmailType1").on("click",function(){"email"==$(this).val()?($body.find("#ib_choose_email").show(),$body.find("#ib_choose_template").hide()):($body.find("#ib_choose_email").hide(),$body.find("#ib_choose_template").show())}),_ibThis.find("#ib_restore_lead_button").on("click",function(e){e.preventDefault(),methods.restoreLeadVerify()})},restoreLeadVerify:function(){_lockClick||(_lockClick=!0,$.confirm({title:"Cancel?",content:"Are you sure you want to restore this lead?",confirm:function(){methods.restoreLead()},confirmButton:"Restore",cancelButton:"Cancel",confirmButtonClass:"ib_save",cancelButtonClass:"ib_cancel"}))},restoreLead:function(){$.ajax({url:ibLeadAjax.ajaxurl,dataType:"json",type:"post",data:{nonce:ibLeadAjax.ibLeadNonce,action:"ib_restore_lead",lead_id:settings.lead_id},success:function(response,status){_lockClick=!1,response.success?methods.restoreLeadSuccess(response):$.alert({title:"Error!",content:response.message,confirmButtonClass:"ib_save"})},error:function(jqXhr,textStatus,errorThrown){console.log(errorThrown),_lockClick=!1}})},restoreLeadSuccess:function(response){$("#message").find(".notice-dismiss").trigger("click"),_ibThis.find("#ib_restore_lead_button").fadeOut("fast",function(){$(this).remove()}),$.alert({title:"Success!",content:response.message,confirmButtonClass:"ib_save"}),methods.newActivityLogRow(response.history)},previewLeadEmail:function($previewButton){var href=$previewButton.attr("href");href+="&nonce="+ibLeadAjax.ajaxurl;var content='<iframe width="100%" src="'+href+'" id="email-quick-preview"></iframe>';_jconfirm=$.alert({title:"Email Preview",content:content,confirmButton:"CLOSE",confirmButtonClass:"ib_save",columnClass:"ib-template-preview-float",icon:"fa fa-eye",onOpen:function(){$("#email-quick-preview").on("load",function(){var $me=$(this),$body=$me.contents().find("body");if($body.length){var height=parseFloat($body.outerHeight())+20;$me.attr("height",height+"px")}methods.recenterModal()})}})},recenterModal:function(){_jconfirm.setDialogCenter()},editHistory:function($row){var $icon=$row.find(".ib_activity-icon span");if($icon.hasClass("fa-phone-square")){var activityType="phone";_activityDialog.dialog("option","title",'<span class="fa fa-phone-square"> </span> Edit Phone Call:')}else{_activityDialog.dialog("option","title",'<span class="fa fa-comment"> </span> Edit Comment:')}$("#LeadActivityComment").val($row.find(".history-note").text().replace(" [edit]","")),$("#lead_activity_type").val(activityType),$("#LeadActivityHistoryId").val($row.data("id")),_activityDialog.dialog("open")},handleSaveActivity:function(){var text=$("#LeadActivityComment").val();text.trim().length>0&&(_activityDialog.dialog("close"),methods.saveActivity())},saveActivity:function(){$.ajax({url:ibLeadAjax.ajaxurl,dataType:"json",type:"post",data:$("#ib_lead_activity").serialize()+"&nonce="+ibLeadAjax.ibLeadNonce,success:function(response,status){response.success?("add"==response.history.mode?methods.newActivityLogRow(response.history):methods.updateActivityLogRow(response.history),$("#LeadActivityComment").val(""),$("#lead_activity_type").val(""),$("#LeadActivityHistoryId").val("")):$.alert({title:"Error!",content:response.message,confirmButtonClass:"ib_save"})},error:function(jqXhr,textStatus,errorThrown){console.log(errorThrown),_lockClick=!1}})},handleNewActivity:function($row){var action=$row.data("action");switch($("#LeadActivityComment").val(""),$("#LeadActivityHistoryId").val(""),action){case"comment":_activityDialog.dialog("option","title",'<span class="fa fa-comment"> </span> New Comment:'),_activityDialog.dialog("open"),$("#lead_activity_type").val("comment");break;case"phone":_activityDialog.dialog("option","title",'<span class="fa fa-phone-square"> </span> New Phone Call:'),_activityDialog.dialog("open"),$("#lead_activity_type").val("phone");break;case"email":_emailDialog.dialog("open")}},verifySendEmail:function(){var type=$('input[name="data[LeadEmail][email_type]"]:checked').val(),errors=[];switch(type){case"email":var email_id=$("#LeadEmailEmailId").val();0==email_id.length&&errors.push("Please choose an email to use.");break;case"custom":var template_id=$("#LeadEmailEmailTemplateId").val();0==template_id.length&&errors.push("Please choose an email template to use.");var email_subject=$("#LeadEmailEmailSubject").val().trim();0==email_subject.length&&errors.push("Please provide an email subject.")}if(errors.length>0){var content="Please correct the following errors before sending the email:<ul>";for(var e in errors)content+="<li>"+errors[e]+"</li>";content+="</ul>",$.alert({title:"Error!",content:content,confirmButton:"Ok",confirmButtonClass:"ib_save"})}else _emailDialog.dialog("close"),"custom"==type&&$("#LeadEmailMessageBody").val(tinymce.get("LeadEmailEmailBody").getContent()),$.ajax({url:ibLeadAjax.ajaxurl,dataType:"json",type:"post",data:$("#ib_send_lead_email").serialize(),success:function(response,status){response.success?(methods.newActivityLogRow(response.history),$.alert({title:response.title,content:response.message,confirmButton:"Close",confirmButtonClass:"ib_save"})):$.alert({title:"Error!",content:response.message,confirmButton:"Ok",confirmButtonClass:"ib_save"})},error:function(jqXhr,textStatus,errorThrown){console.log(errorThrown),_lockClick=!1}})},verifyEmail:function(email){data={action:"ib_verify_lead_email",nonce:ibLeadAjax.ibLeadNonce,email:email},$.ajax({url:ibLeadAjax.ajaxurl,dataType:"json",type:"post",data:data,success:function(response,status){response.success?$("#LeadLeadEmail").removeClass("error"):($.alert({title:"Error!",content:response.message,confirmButtonClass:"ib_save"}),$("#LeadLeadEmail").addClass("error"))},error:function(jqXhr,textStatus,errorThrown){console.log(errorThrown),_lockClick=!1}})},fieldChanged:function($input,type){switch(type){case"text":var value=$input.val(),name=$input.attr("name");break;case"radio":var value=$input.attr("value"),name=$input.attr("name");break;case"checkbox":var values=[],$parent=$input.parent();$parent.find("input").each(function(){var $checkbox=$(this);$checkbox.is(":checked")&&values.push($checkbox.attr("value"))});var name=$input.attr("name"),value=values.join("\n")}0==value.length&&(value="null"),methods.saveLeadField(name,value)},checkForVisibleFields:function(){if($inputs=_form.find("input:visible,select:visible,textarea:visible"),$inputs.length>0){var saved=[];$inputs.each(function(){var $input=$(this),inputType=$input[0].nodeName.toLowerCase(),$toHide=$input,newText="N/A";switch(inputType){case"select":var $toHide=$input.parent();$toHide.next();newText=$input.find('option[value="'+$input.val()+'"]').text();break;case"textarea":$input.next();newText=$input.val();break;case"input":switch($input.attr("type")){case"email":case"text":$input.next();newText=$input.val();break;case"radio":var $toHide=$input.parent();$toHide.next();$checked=$toHide.find("input:checked"),$checked.length&&(newText=$checked.attr("value"));break;case"checkbox":var name=$input.attr("name");if($.inArray(name,saved)==-1){saved.push(name);var $toHide=$input.parent().parent(),checked=($toHide.next(),[]),$inputs=$toHide.find("input");$inputs.each(function(){var $me=$(this);$me.is(":checked")&&checked.push($me.attr("value"))}),checked.length>0&&(newText=checked.join(", ")),newText=checked.join("\n")}}}})}},saveLeadField:function(name,value){var $field=_hiddenForm.find("#lead_field_value");$field.attr("name",name).val(value),$.ajax({url:ibLeadAjax.ajaxurl,dataType:"json",type:"post",data:_hiddenForm.serialize()+"&nonce="+ibLeadAjax.ibLeadNonce,success:function(response,status){if(response.success)methods.newActivityLogRow(response.history);else if($.alert({title:"Error!",content:response.message,confirmButtonClass:"ib_save"}),"undefined"!=typeof response.email){var $email=_ibThis.find("#LeadLeadEmail");$email.val(response.email),$email.next().text(response.email),methods.copyLeadDescription()}},error:function(jqXhr,textStatus,errorThrown){console.log(errorThrown),_lockClick=!1}})},copyLeadName:function($name_field){var name=$("#LeadLeadFirstName").val().trim()+" "+$("#LeadLeadLastName").val().trim();name.length>0?_leadName.html(name).removeClass("empty"):_leadName.html(settings.empty_name).addClass("empty")},copyLeadDescription:function(){var email=$("#LeadLeadEmail").val().trim(),phone=$("#LeadLeadPhone").val().trim(),desc="";email.length>0&&(desc+="<div class='lead-email'><a href='mailto:"+email+"'>"+email+"</a></div>"),phone.length>0&&(desc+="<div class='lead-phone'>"+phone+"</div>"),$("#lead-description").html(desc)},chooseImage:function(){_mediaWindow?_mediaWindow.open():(_mediaWindow=wp.media.frames._mediaWindow=wp.media({title:"Choose Image",button:{text:"Choose Selected"},multiple:!1}),_mediaWindow.on("select",function(){attachment=_mediaWindow.state().get("selection").first().toJSON();var imgurl=attachment.url,$input=$("#LeadLeadPicture");$input.val(imgurl),$("#lead_preview_image").css("background-image","url("+imgurl+")"),"edit"==settings.mode&&methods.saveLeadField($input.attr("name"),imgurl)}),_mediaWindow.open())},handleHiddenInputField:function($text){var type=$text.data("type");switch(type){default:var $show=$text.prev();$text.hide()}$show.show();var nodeName=$show[0].nodeName.toLowerCase();"div"==nodeName?$show.children().first().focus():$show.focus(),$show.on("blur",function(event){$(this).hide(),$(this).next().text($(this).val()),$(this).next().show()})},updateActivityLogRow:function(data){var event='<div class="history-event">'+data.history_event+"</div>";"undefined"!=typeof data.history_note&&(event+='<div class="history-note">'+data.history_note+"</div>");var $row=_activityLog.find("#lead_history_"+data.history_id);_dataTable.fnUpdate(event,$row[0],2,!1)},newActivityLogRow:function(data){var event='<div class="history-event">'+data.history_event+"</div>";"undefined"!=typeof data.history_note&&(event+='<div class="history-note">'+data.history_note+"</div>");var newRow=_dataTable.fnAddData(['<span class="ib-sortable-date">'+data.stamp+"</span>"+data.date,'<span class="fa '+data.icon+'"> </span>',event,data.user]);if("undefined"!=typeof data.history_id){var theNode=_dataTable.fnSettings().aoData[newRow[0]].nTr;theNode.setAttribute("id","lead_history_"+data.history_id),theNode.setAttribute("data-id",data.history_id)}}},settings=$.extend({},defaults,method);return methods[method]?(_ibThis=$(this),methods[method].apply(this,Array.prototype.slice.call(arguments,1))):"object"!=typeof method&&method?void $.error("Method "+method+" does not exist on jQuery.ib_leadEditor"):this.each(function(){_ibThis=$(this),methods.init()})}}(jQuery);