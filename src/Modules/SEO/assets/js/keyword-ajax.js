!function($){$(document).ready(function(){function processPostAddRequest(){$.ajax({data:{action:"add_keyword_with_post",keyword:$("#ib-new-keyword").text(),post_id:$("#post_ID").val(),nonce:ibKwAjax.ibKwNonce},dataType:"json",type:"post",url:ibKwAjax.ajaxurl,success:function(data){var word='<div id="'+data.id+'" class="ib-pill">'+data.keyword+"</div>";$('[data-role="keyword-post-add-box"]').toggle(600),$("#ib-new-keyword").text(""),$('[data-role="keyword-holder"]').append(word)},error:function(data){}})}function processAddRequest(){$.ajax({data:{action:"add_ib_keywords",keyword:$('div[data-role="new-keyword"]').text(),nonce:ibKwAjax.ibKwNonce},dataType:"json",type:"post",url:ibKwAjax.ajaxurl,success:function(data){window.location.reload(!0)},error:function(data){window.location.reload(!0)}})}function processEditRequest(){$.ajax({data:{action:"edit_ib_keywords",keyword:$('div[data-role="editable-keyword"]').text(),id:$('div[data-role="editable-keyword-id"]').text(),nonce:ibKwAjax.ibKwNonce},dataType:"json",type:"post",url:ibKwAjax.ajaxurl,success:function(data){window.location.reload(!0)},error:function(data){window.location.reload(!0)}})}function processDeleteRequest(e){$.ajax({type:"POST",dataType:"json",url:ibKwAjax.ajaxurl,data:{action:"remove_ib_keywords",id:$(e.target).attr("href"),nonce:ibKwAjax.ibKwNonce},success:function(data){window.location.reload(!0)},error:function(data){window.location.reload(!0)}}),e.preventDefault()}$('[data-role="keyword-add-dialog"]').dialog({autoOpen:!1}),$('[data-role="keyword-edit-dialog"]').dialog({autoOpen:!1}),$('[data-role="add-keyword"]').on("click",function(e){e.preventDefault(),$('[data-role="keyword-add-dialog"]').dialog("open")}),$('[data-role="edit-keyword"]').on("click",function(e){e.preventDefault();var id=$(e.target).attr("href"),term=$("td#keyword_value_"+id).text();$('[data-role="editable-keyword-id"]').text(id),$('[data-role="editable-keyword"]').text(term),$('[data-role="keyword-edit-dialog"]').dialog("open")}),$("#ib_keywords").on("click",'[data-role="delete-keyword"]',processDeleteRequest),$('[data-role="keyword-edit-dialog"]').on("click",'[data-action="keyword-save-edit"]',processEditRequest),$('[data-role="keyword-add-dialog"]').on("click",'[data-action="keyword-add"]',processAddRequest),$('[data-role="add-post-keyword"]').on("click",function(e){e.preventDefault(),$('[data-role="keyword-post-add-box"]').toggle("600")}),$("#ib-add-new-keyword").on("click",function(e){e.preventDefault(),processPostAddRequest()})})}(jQuery);