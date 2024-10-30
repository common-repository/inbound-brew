jQuery(document).ready(function($){var tour_name="contact_forms",tour=newTour();if(tour.addStep("Module Welcome",{title:"Contact Forms: Overview",text:$.fn.ib_inlineEducation("getText","forms")+'<br/><br/><i>If you want to see this message again, click <i class= "fa fa-info-circle fa-1x"></i> at any time. You can also <a href="https://inboundbrew.com/download-our-plugin-user-guide/" target="_blank">download our user guide</a> to learn more about the plugin.</i>',classes:"settings-main-info-shepherd shepherd-theme-arrows ",attachTo:{element:"#forms",on:"right"},advanceOn:".docs-link click",showCancelLink:!0,tetherOptions:{constraints:null,offset:"-70px -6px 0px 0px"},buttons:[{text:"Exit",classes:"shepherd-button-secondary",action:tour.cancel},{text:"Next Step: Landing Pages",action:function(){goToUrl(ibConstants.adminUrl+"page=landing-page-admin&gstour=1")}}]}),ibConstants.getVars.gstour||showTour(tour_name,tour),ibConstants.getVars.gstour){var gstour=newTour();gstour.addStep("Getting Started",{title:"Getting Started: Contact Forms",text:"Contact Forms can be used in Landing Pages or wherever you feel like putting them. Once one is completed, that info will be stored as a Lead.",classes:"settings-main-info-shepherd shepherd-theme-arrows ",attachTo:{element:"#forms",on:"right"},advanceOn:".docs-link click",showCancelLink:!0,tetherOptions:{constraints:null,offset:"-27px -10px 0px 0px"},buttons:[{text:"Back to Emails",classes:"shepherd-button-secondary",action:function(){goToUrl(ibConstants.adminUrl+"page=ib-email-admin&gstour=1")}},{text:"Jump Into Contact Forms Now",classes:"shepherd-button-tertiary",action:function(){gstour.hide(),showTour(tour_name,tour)}},{text:"Next Step: Landing Pages",action:function(){goToUrl(ibConstants.adminUrl+"page=landing-page-admin&gstour=1")}}]}),gstour.start()}$(".ib-module-info-icon").on("click",function(){showTourAgain(tour_name,tour)})});