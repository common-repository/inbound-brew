var Validator=function(form){this.form=jQuery(form),this.validForm=!0;var Elements={text:{selector:jQuery(':input[data-type="text"].ib-required'),reg:/^[a-z0-9 ]+$/i},city:{selector:jQuery(':input[data-type="city"].ib-required'),reg:/^[a-z0-9 ]+$/i},wildcard:{selector:jQuery(':input[data-type="wildcard"].ib-required'),reg:/^[a-z0-9 =_\-\*\/?.\(\)&]+$/i},email:{selector:jQuery(':input[data-type="email"].ib-required'),reg:/^[a-z-0-9_+.-]+\@([a-z0-9-]+\.)+[a-z0-9]{2,7}$/i},email2:{selector:jQuery(':input[data-type="email"].ib-required'),reg:/^[a-z-0-9_+.-]+\@([a-z0-9-]+\.)+[a-z0-9]{2,7}$/i},url:{selector:jQuery(':input[data-type="url"].ib-required'),reg:/^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i},urlpath:{selector:jQuery(':input[data-type="urlpath"].ib-required'),reg:/^\/[\/\.a-zA-Z0-9\-]+$/i},message:{selector:jQuery(':input[data-type="textarea"].ib-required'),reg:/^\s+$/},captcha:{selector:jQuery(':input[data-type="captcha"].ib-required'),reg:/^\s+$/},date:{selector:jQuery(':input[data-type="date"].ib-required'),reg:/^\d{2}([.\/-])\d{2}\1\d{4}$/},dob:{selector:jQuery(':input[data-type="birth_date"].ib-required'),reg:/^\d{2}([.\/-])\d{2}\1\d{4}$/},name:{selector:jQuery(':input[data-type="name"].ib-required'),reg:/^[a-zA-Z ]{2,30}$/},select:{selector:jQuery(':input[data-type="select"].ib-required'),reg:/^\s+$/},country:{selector:jQuery(':input[data-type="country"].ib-required'),reg:/^\s+$/},state:{selector:jQuery(':input[data-type="state"].ib-required'),reg:/^\s+$/},radio:{selector:jQuery(':input[data-type="radio"].ib-required')},checkbox:{selector:jQuery(':input[data-type="checkbox"].ib-required')},acceptance:{selector:jQuery(':input[data-type="acceptance"].ib-required')},address:{selector:jQuery(':input[data-type="address"].ib-required'),reg:/[,#-\/\s\!\@\$.....]/},address2:{selector:jQuery(':input[data-type="address2"].ib-required'),reg:/[,#-\/\s\!\@\$.....]/},postal:{selector:jQuery(':input[data-type="postal"].ib-required'),reg:/^\s+$/}},handleError=function(element){var eClass="ib-highlight-red";element.addClass(eClass);var parent_label=jQuery("label[for='"+element.attr("name")+"']");parent_label.addClass(eClass),element.on("keyup change",function(){jQuery(this).removeClass(eClass).delay(500),jQuery(parent_label).removeClass(eClass).delay(500)})};this.validate=function(){for(var i in Elements){var type=i,validation=Elements[i];if(validation.selector.closest(this.form).length>0)switch(type){case"message":case"select":case"captcha":case"state":case"country":case"postal":validation.selector.length&&(validation.reg.test(validation.selector.val())||""==validation.selector.val())&&validation.selector.is(":visible")&&(handleError(validation.selector),this.validForm=!1);break;case"city":case"date":case"dob":case"name":case"email":case"email2":case"text":case"address":case"address2":case"url":case"wildcard":!validation.selector.length||validation.reg.test(validation.selector.val())&&""!=validation.selector.val()||!validation.selector.is(":visible")||(handleError(validation.selector),this.validForm=!1);break;case"radio":case"checkbox":case"acceptance":if(validation.selector.length){var isChecked=!1;jQuery(validation.selector).each(function(){jQuery(this).is(":checked")&&(isChecked=!0)}),isChecked||(handleError(validation.selector),this.validForm=!1)}}}}};