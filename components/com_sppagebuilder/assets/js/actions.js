jQuery(function(e){window.onbeforeunload=function(e){if(void 0!==window.warningAtReload&&1==window.warningAtReload){var t="Do you want to lose unsaved data?";return(e=e||window.event)&&(e.returnValue=t),t}return null},e(document).on("click","#btn-save-page",function(t){t.preventDefault();var n=e(this),i=e.parseJSON(e("#jform_sptext").val());console.log(e("#sp-page-builder").data("pageid")),i.filter(function(e){return e.columns.filter(function(e){return e.addons.filter(function(e){return"sp_row"===e.type||"inner_row"===e.type?e.columns.filter(function(e){return e.addons.filter(function(e){return null!=typeof e.htmlContent&&delete e.htmlContent,null!=typeof e.assets&&delete e.assets,e})}):(null!=typeof e.htmlContent&&delete e.htmlContent,null!=typeof e.assets&&delete e.assets,e)})})}),e("#jform_sptext").val(JSON.stringify(i));var a=e("#adminForm"),o=e("#sp-page-builder").data("pageid");e.ajax({type:"POST",url:pagebuilder_base+"index.php?option=com_sppagebuilder&task=page.apply&pageId="+o,data:a.serialize(),beforeSend:function(){n.find(".fa-save").removeClass("fa-save").addClass("fa-spinner fa-spin")},success:function(i){try{var a=e.parseJSON(i);n.find(".fa").removeClass("fa-spinner fa-spin").addClass("fa-save"),0===e(".sp-pagebuilder-notifications").length&&e('<div class="sp-pagebuilder-notifications"></div>').appendTo("body");var o="success";if(!a.status)o="error";if(a.title&&e("#jform_title").val(a.title),a.id&&e("#jform_id").val(a.id),e('<div class="notify-'+o+'">'+a.message+"</div>").css({opacity:0,"margin-top":-15,"margin-bottom":0}).animate({opacity:1,"margin-top":0,"margin-bottom":15},200).prependTo(".sp-pagebuilder-notifications"),void 0!==window.warningAtReload&&1==window.warningAtReload&&(window.warningAtReload=!1),e(".sp-pagebuilder-notifications").find(">div").each(function(){var t=e(this);setTimeout(function(){t.animate({opacity:0,"margin-top":-15,"margin-bottom":0},200,function(){t.remove()})},3e3)}),!a.status)return;window.history.replaceState("","",a.redirect),a.preview_url&&0===e("#btn-page-preview").length&&e("#btn-page-options").parent().before('<div class="sp-pagebuilder-btn-group"><a id="btn-page-preview" target="_blank" href="'+a.preview_url+'" class="sp-pagebuilder-btn sp-pagebuilder-btn-primary"><i class="fa fa-eye"></i> Preview</a></div>'),"btn-save-new"==t.target.id&&(window.location.href="index.php?option=com_sppagebuilder&view=page&layout=edit"),"btn-save-close"==t.target.id&&(window.location.href="index.php?option=com_sppagebuilder&view=pages")}catch(e){window.location.href="index.php?option=com_sppagebuilder&view=pages"}}})})});