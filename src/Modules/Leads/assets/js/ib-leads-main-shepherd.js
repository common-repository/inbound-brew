jQuery(document).ready(function($){var tour_name="leads",tour=newTour();if(tour.addStep("Module Welcome",{title:"Leads: Overview",text:$.fn.ib_inlineEducation("getText","leads")+'<br/><br/><i>If you want to see this message again, click <i class= "fa fa-info-circle fa-1x"></i> at any time</i>.',classes:"leads-info-shepherd shepherd-theme-arrows ",attachTo:{element:"#leads",on:"right"},advanceOn:".docs-link click",showCancelLink:!0,tetherOptions:{constraints:null,offset:"-70px -10px 0px 0px"},buttons:[{text:"Exit",classes:"shepherd-button-secondary",action:tour.cancel},{text:"Next Step",action:tour.next}]}),tour.addStep("New Lead",{title:"Leads: Add New Lead Manually",text:"If you need to manually enter a lead, click this button.",classes:"settings-general-smaller-shepherd shepherd-theme-arrows ",attachTo:{element:".ib-button",on:"right"},advanceOn:".docs-link click",showCancelLink:!0,tetherOptions:{constraints:null,offset:"0px 0px 0px 0px"},buttons:[{text:"Back",classes:"shepherd-button-secondary",action:tour.back},{text:"Next Step",action:tour.next}]}),tour.addStep("Lead Col and Filtering Views",{title:"Leads: Views and Filtering",text:'Adjust what you see by adding or removing fields, adding filters, and reording or resizing columns.<br/><br/>When you make a change, you\'ll be prompted to save a new view. New views shows up as tabs that you can easily pull up later on.<br/><br/>Try creating a new view now, or click "Next" to continue.',classes:"settings-general-shepherd shepherd-theme-arrows ",attachTo:{element:"#columns-button",on:"right"},advanceOn:".docs-link click",showCancelLink:!0,tetherOptions:{constraints:null,offset:"-0px 0px 0px 0px"},buttons:[{text:"Back",classes:"shepherd-button-secondary",action:tour.back},{text:"Next Step: Dashboard",action:function(){goToUrl(ibConstants.adminUrl+"page=inboundbrew&gstour=1")}}]}),ibConstants.getVars.gstour||showTour(tour_name,tour),ibConstants.getVars.gstour){var gstour=newTour();gstour.addStep("Getting Started",{title:"Getting Started: Leads",text:"This is where captured Leads show up. Here you can send them emails, prioritize for following up, or see what pages they've visited.",classes:"settings-main-info-shepherd shepherd-theme-arrows ",attachTo:{element:"#leads",on:"right"},advanceOn:".docs-link click",showCancelLink:!0,tetherOptions:{constraints:null,offset:"-27px -10px 0px 0px"},buttons:[{text:"Back to CTAs",classes:"shepherd-button-secondary",action:function(){goToUrl(ibConstants.adminUrl+"page=ib-call-to-action&gstour=1")}},{text:"Jump Into Leads Now",classes:"shepherd-button-tertiary",action:function(){gstour.hide(),showTour(tour_name,tour)}},{text:"Next Step: Dashboard",action:function(){goToUrl(ibConstants.adminUrl+"page=inboundbrew&gstour=1")}}]}),gstour.start()}$(".ib-module-info-icon").on("click",function(){showTourAgain(tour_name,tour)})});