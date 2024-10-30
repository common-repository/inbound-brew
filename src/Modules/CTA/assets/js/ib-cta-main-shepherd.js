jQuery(document).ready(function($){var tour_name="ctas",tour=newTour();if(tour.addStep("Module Welcome",{title:"CTAs: Overview",text:$.fn.ib_inlineEducation("getText","cta")+'<br/><br/><i>If you want to see this message again, click <i class= "fa fa-info-circle fa-1x"></i> at any time. You can also <a href="https://inboundbrew.com/download-our-plugin-user-guide/" target="_blank">download our user guide</a> to learn more about the plugin.</i>',classes:"settings-main-info-shepherd shepherd-theme-arrows ",attachTo:{element:"#cta-management",on:"right"},advanceOn:".docs-link click",showCancelLink:!0,tetherOptions:{constraints:null,offset:"-78px -8px 0px 0px"},buttons:[{text:"Exit",classes:"shepherd-button-secondary",action:tour.cancel},{text:"Next Step",action:tour.next}]}),tour.addStep("New CTA",{title:"CTAs: Add New CTA",text:"From here you can create a <b>custom button</b>, <b>upload your own image</b>, create a CTA from a <b>saved template</b>, create a <b>top bar CTA</b> and also a <b>'before you leave'</b> CTA.<br/><br/>The call-to-action feature allows you to create custom CTA buttons that you can use anywhere on your WordPress site using a simple shortcode. When using the CTA tool, you can create unlimited customized buttons. Your options include a variety of different fonts, sizes, colors, borders, and button padding options.<br/><br/>To learn more about how to create effective CTAs, <a href=\"https://inboundbrew.com/inboundmarketingblog/8-tips-for-creating-a-cta-that-beckons/\" target='_blank'>read this blog post</a>.",classes:"settings-general-smaller-shepherd shepherd-theme-arrows ",attachTo:{element:"#new-cta-button",on:"right"},advanceOn:".docs-link click",showCancelLink:!0,tetherOptions:{constraints:null,offset:"-0px -100px 0px 0px"},buttons:[{text:"Back",classes:"shepherd-button-secondary",action:tour.back},{text:"Next Step",action:tour.next}]}),tour.addStep("New Templates",{title:"CTAs: Add New Template",text:"Here you can create a general design for a custom template to reuse as many times as you see fit for future custom CTAs. ",classes:"settings-general-smaller-shepherd shepherd-theme-arrows ",attachTo:{element:"#new-template-button",on:"right"},advanceOn:".docs-link click",showCancelLink:!0,tetherOptions:{constraints:null,offset:"-0px -5px 0px 0px"},buttons:[{text:"Back",classes:"shepherd-button-secondary",action:tour.back},{text:"Next Step: Leads",action:function(){goToUrl(ibConstants.adminUrl+"page=ib-leads-admin&gstour=1")}}]}),ibConstants.getVars.gstour||showTour(tour_name,tour),ibConstants.getVars.gstour){var gstour=newTour();gstour.addStep("Getting Started",{title:"Getting Started: CTAs",text:'Call To Actions, or CTAs, are meant to prompt the visitor into taking action. Buttons that say "Download Our eBook Now," or enticing images that promise a free Webinar are calls to action that get them to click deeper into the marketing funnel.',classes:"settings-main-info-shepherd shepherd-theme-arrows ",attachTo:{element:"#cta-management",on:"right"},advanceOn:".docs-link click",showCancelLink:!0,tetherOptions:{constraints:null,offset:"-35px -6px 0px 0px"},buttons:[{text:"Back to Landing Pages",classes:"shepherd-button-secondary",action:function(){goToUrl(ibConstants.adminUrl+"page=landing-page-admin&gstour=1")}},{text:"Jump Into CTAs Now",classes:"shepherd-button-tertiary",action:function(){gstour.hide(),showTour(tour_name,tour)}},{text:"Next Step: Leads",action:function(){goToUrl(ibConstants.adminUrl+"page=ib-leads-admin&gstour=1")}}]}),gstour.start()}$(".ib-module-info-icon").on("click",function(){showTourAgain(tour_name,tour)})});