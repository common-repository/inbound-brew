jQuery(document).ready(function($){function insertSelectedKeyword(keyword_id,label){var found=!1;if($keyword_list.find(".ib-pill-small").each(function(){var mine=$(this).text();if(mine==label){var tmpId=$(this).attr("data-id");$("#ib_keyword_deleted_"+tmpId).val("0"),$(this).show(),found=!0}}),!found){var word=getKeywordString(keyword_id,label);$(word).insertBefore($keyword_clear)}checkUsage(),$keyword_field.empty()}function getKeywordString(keyword_id,keyword_label){var counter=parseFloat($keyword_list.attr("data-counter"));counter++;var prefix="Keyword["+counter+"]",word='<input type="hidden" name="'+prefix+'[keyword_id]" value="'+keyword_id+'">\t\t\t\t<input type="hidden" name="'+prefix+'[is_deleted]" value="0" id="ib_keyword_deleted_'+keyword_id+'">\t\t\t\t<input type="hidden" name="'+prefix+'[keyword]" value="'+keyword_label+'">\t\t\t\t<div class="ib-pill-small" data-id="'+keyword_id+'">'+keyword_label+"</div>";return $keyword_list.attr("data-counter",counter),word}function checkUsage(){var h_one_reg=new RegExp("<h1>.*?</h1>","i"),img_alt_reg=/(<img(?!.*?alt=([\'"]).*?\2)[^>]*?)(\/?>)/,link_title_reg=/(<a(?!.*?title=([\'"]).*?\2)[^>]*?)(\/?>)/,title_content=$("#title").val(),content="",keywords=[],k=0,u=0;for(content+=title_content,i=0;i<tinymce.editors.length;i++)content+=tinymce.editors[i].getContent();$keyword_list.find("div.ib-pill-small").each(function(){var $el=$(this);if($el.is(":visible")){k++,$el.removeClass("ib-used");var kw_reg=new RegExp("\\b"+$el.text().toLowerCase()+"\\b","i");keywords.indexOf($el.text().toLowerCase())==-1&&kw_reg.test(content)&&(keywords.push($el.text().toLowerCase()),$el.addClass("ib-used"),u++),kw_reg.test(title_content)?($seoTitleCheckbox.addClass("checked"),$seoTitleInput.val("1")):($seoTitleCheckbox.removeClass("checked"),$seoTitleInput.val(""))}}),h_one_reg.test(content)?($seoHOneCheckbox.addClass("checked"),$seoHOneInput.val("1")):($seoHOneCheckbox.removeClass("checked"),$seoHOneInput.val("")),img_alt_reg.test(content)?($seoAltTagCheckbox.addClass("checked"),$seoAltTagInput.val("1")):($seoAltTagCheckbox.removeClass("checked"),$seoAltTagInput.val("")),link_title_reg.test(content)?($seoTitleTagCheckbox.addClass("checked"),$seoTitleTagInput.val("1")):($seoTitleTagCheckbox.removeClass("checked"),$seoTitleTagInput.val(""));var p=0;k>0&&(p=Math.round(u/k*100)),$("#ib_kw_progress_bar").progressbar({value:p}),$("#ib_kw_selected").text(k),$("#ib_kw_used").text(u),$("#ib_kw_percent_score").text(p)}function checkSlug(){var slug_content=$("#new-post-slug").length>0?$("#new-post-slug").val():$("#editable-post-name").text(),found=!1,value="";$seoKeyworkList.find("div.ib-pill-small").each(function(){var $el=$(this),slug_reg=new RegExp($el.text().toLowerCase().replace(" ","-"),"i");slug_reg.test(slug_content)&&(found=!0,value="1")}),found?$slug_checkbox.addClass("checked"):$slug_checkbox.removeClass("checked"),$slug_input.val(value)}var $keyword_holder=$("#ib-keyword-holder");$keyword_holder.length>0&&($("#ib_kw_progress_bar").progressbar({value:"undefined"!=typeof ib_kw_percent?ib_kw_percent:""}),$('[name="post_title"]').on("keyup",function(){checkUsage()}),"undefined"!=typeof tinymce&&tinymce.on("AddEditor",function(e){e.editor.on("keyup change",function(e){checkUsage()})}),$("#post-body,#new-post-slug").on("keyup",function(){checkSlug()}),$keyword_holder.on("click",function(e){if($pill=$(e.target),$pill.hasClass("ib-pill-small")){var kId=$pill.attr("data-id");$("#ib_keyword_deleted_"+kId).val("1"),$pill.hide(),checkUsage()}})),$keyword_list=$("#ib_keyword_list"),$keyword_clear=$("#ib_keyword_list_clear"),$keyword_field=$("#autocomplete"),_noResults=!1,$keyword_field.autocomplete({source:function(request,response){$.ajax({data:{action:"keyword_auto_complete",post:$("#post_ID").val(),term:request.term},dataType:"json",type:"post",url:ibAjax.ajaxurl,success:function(data){data&&data.length>0&&(data=$.grep(data,function(item){var already_picked=!1;return $keyword_list.find(".ib-pill-small").each(function(){var mine=$(this).text();if(mine==item.label){var tmpId=$(this).attr("data-id");"1"!=$("#ib_keyword_deleted_"+tmpId).val()&&(already_picked=!0)}}),!already_picked})),response(data)},error:function(data){}})},autoFocus:!0,minLength:1,select:function(event,ui){insertSelectedKeyword(ui.item.value,ui.item.label),event.preventDefault()},close:function(event,ui){},response:function(event,ui){var len=ui.content.length;_noResults=!len}}).keyup(function(event){if(13==event.which){var keyword=$keyword_field.text().trim();_noResults&&(event.preventDefault(),_noResults&&keyword.length>0&&insertSelectedKeyword(0,keyword)),$keyword_field.empty()}});var $seoTitleCheckbox=$("#seo_title_check"),$seoTitleInput=$("#seo_title_input"),$seoHOneCheckbox=$("#seo_h_one_check"),$seoHOneInput=$("#seo_h_one_input"),$seoAltTagCheckbox=$("#seo_alt_tag_check"),$seoAltTagInput=$("#seo_alt_tag_input"),$seoTitleTagCheckbox=$("#seo_title_tag_check"),$seoTitleTagInput=$("#seo_title_tag_input"),$seoKeyworkList=$("#ib_keyword_list"),$slug_checkbox=$("#seo_url_check"),$slug_input=$("#seo_url_input")});