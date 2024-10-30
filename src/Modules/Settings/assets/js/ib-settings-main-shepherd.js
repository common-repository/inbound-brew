jQuery(document).ready(function($){var tour_name="settings",tour=newTour();if(tour.addStep("Module Welcome",{title:"Settings Module: Overview",text:$.fn.ib_inlineEducation("getText","settings")+'<br/><br/><i>If you want to see this message again, click <i class= "fa fa-info-circle fa-1x"></i> at any time. You can also <a href="https://inboundbrew.com/download-our-plugin-user-guide/" target="_blank">download our user guide</a> to learn more about the plugin.</i>.',classes:"settings-main-info-shepherd shepherd-theme-arrows ",attachTo:{element:"#settings",on:"right"},advanceOn:".docs-link click",showCancelLink:!0,tetherOptions:{constraints:null,offset:"-35px -6px 0px 0px"},buttons:[{text:"Exit",classes:"shepherd-button-secondary",action:tour.cancel},{text:"Next Step",action:tour.next}]}),tour.addStep("General Settings Tab",{title:"Settings Module: General Settings Tab",text:"Set all your general plugin preferences ranging from whether you want your main menu on the top or side to which modules you want activated.",classes:"settings-general-shepherd shepherd-theme-arrows ",attachTo:{element:"#general-settings-tab ",on:"right"},advanceOn:".docs-link click",showCancelLink:!0,tetherOptions:{constraints:null,offset:"35px -5px 0px 0px"},buttons:[{text:"Back",classes:"shepherd-button-secondary",action:tour.back},{text:"Next Step",action:tour.next}]}),tour.addStep("Getting Started Page",{title:"Settings Module: Getting Started Page",text:'Remember if you want to get back to the "Getting Started Page," this link is ready and waiting for you :)',classes:"settings-general-shepherd shepherd-theme-arrows ",attachTo:{element:"#getting-started-link",on:"top"},advanceOn:".docs-link click",showCancelLink:!0,tetherOptions:{constraints:null,offset:"35px -5px 0px 0px"},buttons:[{text:"Back",classes:"shepherd-button-secondary",action:tour.back},{text:"Next: Social Share Settings",action:function(){goToUrl(ibConstants.adminUrl+"page=ib-admin-settings&section=ib_social_settings&ignoreWelcomeTourStep=1")}}]}),ibConstants.getVars.gstour||showTour(tour_name,tour),ibConstants.getVars.gstour){var gstour=newTour();gstour.addStep("Getting Started",{title:"Getting Started: Settings Overview",text:"The Settings Module is where you configure the plugin. Here you setup your email settings, navigation, social media connections, and more. Before you really dive into the plugin, make sure to go through this section and get setup.",classes:"settings-main-info-shepherd shepherd-theme-arrows ",attachTo:{element:"#settings",on:"right"},advanceOn:".docs-link click",showCancelLink:!0,tetherOptions:{constraints:null,offset:"-35px -6px 0px 0px"},buttons:[{text:"Exit",classes:"shepherd-button-secondary",action:gstour.cancel},{text:"Jump Into Settings Now",classes:"shepherd-button-tertiary",action:function(){gstour.hide(),showTour(tour_name,tour)}},{text:"Next Step: Keywords",action:function(){goToUrl(ibConstants.adminUrl+"page=keyword-admin&gstour=1")}}]}),gstour.start()}$(".ib-module-info-icon").on("click",function(){showTourAgain(tour_name,tour)})});