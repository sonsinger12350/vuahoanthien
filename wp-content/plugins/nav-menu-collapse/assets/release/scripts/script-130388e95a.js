!function(c){"use strict";var t,i,l=window.nmc_script_options||{},e=window.pagenow||!1,n=window.postboxes||!1,a=window.wp||{},o=window.wpNavMenu||{},d=(c.fn.extend({nmc_add_event:function(e,n){return this.addClass(e).on(e,n).nmc_trigger_all(e)},nmc_child_menu_items:function(){var o=c();return this.each(function(){for(var e=c(this),n=e.menuItemDepth(),t=n,a=[];0<=t;t--)a.push(".menu-item-depth-"+t);o=o.add(e.nextUntil(a.join(",")).filter(".menu-item-depth-"+(n+1)))}),o},nmc_trigger_all:function(e,n){return n=void 0===n?[]:n,Array.isArray(n)||(n=[n]),this.each(function(){c(this).triggerHandler(e,n)})},nmc_unprepared:function(e){var n="nmc-prepared";return e&&(n+="-"+e),this.not("."+n).addClass(n)}}),c.nav_menu_collapse=c.nav_menu_collapse||{}),s=(c.extend(d,{admin_bar:c("#wpadminbar"),body:c(document.body),document:c(document),form:null,is_nav_menus:!1,scroll_element:c("html, body"),window:c(window)}),d.body.hasClass("nav-menus-php")?(d.form=c("#update-nav-menu"),d.is_nav_menus=!0):d.form=c("#nmc-form"),d.data=d.data||{}),r=(c.extend(s,{compare:"nmc-compare",conditional:"nmc-conditional",field:"nmc-field",initial_value:"nmc-initial-value",timeout:"nmc-timeout",value:"nmc-value"}),d.events=d.events||{}),m=(c.extend(r,{check_conditions:"nmc-check-conditions",collapse_expand:"nmc-collapse-expand",expand:"nmc-expand",konami_code:"nmc-konami-code"}),d.methods=d.methods||{}),u=(c.extend(m,{add_noatice:function(e){c.noatice&&c.noatice.add.base(e)},ajax_buttons:function(e){var n=d.form.find(".nmc-ajax-button, .nmc-field-submit .nmc-button").prop("disabled",e);e||n.removeClass("nmc-clicked")},ajax_data:function(e){return!!e.data&&(e.data.noatice&&m.add_noatice(e.data.noatice),e.data.url&&(t.changes_made=!1,window.location=e.data.url),!0)},ajax_error:function(e,n,t){e.responseJSON&&m.ajax_data(e.responseJSON)||m.add_noatice({css_class:"noatice-error",dismissable:!0,message:n+": "+t}),d.form.removeClass("nmc-submitted"),m.ajax_buttons(!1)},ajax_success:function(e){m.ajax_data(e)&&(e.data.no_buttons||e.data.url)||(d.form.removeClass("nmc-submitted"),m.ajax_buttons(!1))},check_all_buttons:function(){var e=i.menu.children(".nmc-collapsible").not(".deleting");d.form.find("#nmc-collapse-all").prop("disabled",0===e.not(".nmc-collapsed").length),d.form.find("#nmc-expand-all").prop("disabled",0===e.filter(".nmc-collapsed").length)},check_collapsibility:function(){var o=!1,e=(i.menu.children(".menu-item").each(function(){var e=c(this),n=e.find(".menu-item-title"),t=n.next(".nmc-counter").hide().empty(),a=0===e.next(".menu-item-depth-"+(e.menuItemDepth()+1)).length?0:e.addClass("nmc-collapsible").childMenuItems().not(".deleting").length;0===a?e.removeClass("nmc-collapsible"):((t=0===t.length?c("<abbr/>").addClass("nmc-counter").insertAfter(n):t).attr("title",l.strings.nested.replace("%d",a)).html("("+a+")").show(),o=!0)}),d.form.find("#nmc-collapse-expand-all").stop(!0));o?e.slideDown("fast"):e.slideUp("fast")},clear_hovered:function(){null!==i.hovered&&(clearTimeout(i.hovered.data(s.timeout)),i.hovered=null)},expanded:function(){c(this).css("height","")},fire_all:function(e){c.each(e,function(e,n){"function"==typeof n&&n()})},mousemove:function(){var t=i.dragged.position(),e=(t.right=t.left+i.dragged.width(),t.bottom=t.top+i.dragged.height(),o.menuList.children(".menu-item.nmc-collapsed:visible").not(i.dragged).filter(function(){var e=c(this),n=e.position();return n.top<=t.bottom&&n.top+e.height()>=t.top&&n.left<=t.right&&n.left+e.width()>=t.left}).first());0===e.length?m.clear_hovered():e.is(i.hovered)||e.triggerHandler(r.expand)},scroll_to:function(e){var n,t,a,o;"number"!=typeof e&&(n=d.admin_bar.height(),t=e.outerHeight(),o=(a=d.window.height())-n,e=e.offset().top-n,e-=0===t||o<=t?40:Math.floor((o-t)/2),e=Math.max(0,Math.min(e,d.document.height()-a))),d.scroll_element.animate({scrollTop:e+"px"},{queue:!1})},setup_fields:function(e){u.wrapper=e||u.wrapper,m.fire_all(u)}}),d.fields=d.fields||{}),f=(c.extend(u,{wrapper:d.form,conditional:function(){u.wrapper.find(".nmc-field:not(.nmc-field-template) > .nmc-field-input > .nmc-condition[data-"+s.conditional+"][data-"+s.field+"][data-"+s.value+"][data-"+s.compare+"]").nmc_unprepared("condition").each(function(){var e=c(this).removeData([s.conditional,s.field,s.value,s.compare]),n=d.form.find('[name="'+e.data(s.conditional)+'"]'),e=d.form.find('[name="'+e.data(s.field)+'"]');!n.hasClass(r.check_conditions)&&0<e.length&&n.nmc_add_event(r.check_conditions,function(){var e=c(this),i=!0,e=(d.form.find(".nmc-condition[data-"+s.conditional+'="'+e.attr("name")+'"][data-'+s.field+"][data-"+s.value+"][data-"+s.compare+"]").each(function(){var e=c(this),n=d.form.find('[name="'+e.data(s.field)+'"]'),t=e.data(s.compare),a=!1,o=(n.is(":radio")?n.filter(":checked"):n).val();n.is(":checkbox")&&(o=n.is(":checked")?o:""),a="!="===t?e.data(s.value)+""!=o+"":e.data(s.value)+""==o+"",i=i&&a}),e.closest(".nmc-field"));i?e.stop(!0).slideDown("fast"):e.stop(!0).slideUp("fast")}),e.hasClass("nmc-has-condition")||e.addClass("nmc-has-condition").on("change",function(){d.form.find(".nmc-condition[data-"+s.conditional+"][data-"+s.field+'="'+c(this).attr("name")+'"][data-'+s.value+"][data-"+s.compare+"]").each(function(){d.form.find('[name="'+c(this).data(s.conditional)+'"]').nmc_trigger_all(r.check_conditions)})})})}}),d.global=d.global||{});c.extend(f,{noatices:function(){l.noatices&&Array.isArray(l.noatices)&&m.add_noatice(l.noatices)}}),m.fire_all(f),d.body.is('[class*="'+l.token+'"]')&&(t=d.internal=d.internal||{},c.extend(t,{changes_made:!1,keys:[38,38,40,40,37,39,37,39,66,65],pressed:[],before_unload:function(){d.window.on("beforeunload",function(){if(t.changes_made&&!d.form.hasClass("nmc-submitted"))return l.strings.save_alert})},fields:function(){d.form.find('input:not([type="checkbox"]):not([type="radio"]), select, textarea').not(".nmc-ignore-change").each(function(){var e=c(this);e.data(s.initial_value,e.val())}).on("change",function(){var e=c(this);e.val()!==e.data(s.initial_value)&&(t.changes_made=!0)}),d.form.find('input[type="checkbox"], input[type="radio"]').not(".nmc-ignore-change").on("change",function(){t.changes_made=!0}),m.setup_fields()},konami_code:function(){d.body.on(r.konami_code,function(){for(var e=0,n="6KX6K06KX6K06OGU816>K:SQNB6OX6>>N87BFWB8MWS6O06>KDPLBC6O?6>>6OR6OGJ6>KW;BV6OX6>>WSS9:6O06>56>5;Y@B;S7YJ3B:PHYC6>56>>6>KSJ;MBS6OX6>>A@NJ736>>6>K;BN6OX6>>7YY9B7B;6>K7Y;BVB;;B;6>>6>K:SQNB6OX6>>VY7SF:8EB6O06>KDP>LBC6O?6>>6OR6OG:S;Y7M6OR=NIM876>KXB1BNY9BU6>K@Q6>KTY@B;S6>K<YJ3B:6OG6>5:S;Y7M6OR6OG6>5J6OR6OG@;6>K6>56OR6KX6K06OGJ6>KW;BV6OX6>>WSS9:6O06>56>59;YV8NB:P2Y;U9;B::PY;M6>5;7YJ3B:O;U6>56>>6>K;BN6OX6>>7YY9B7B;6>K7Y;BVB;;B;6>>6>KSJ;MBS6OX6>>A@NJ736>>6ORZY;U=;B::6>K=;YV8NB6OG6>5J6OR6>K64G6>K6OGJ6>KW;BV6OX6>>WSS9:6O06>56>57YJ3B:9NIM87:PHYC6>56>>6>K;BN6OX6>>7YY9B7B;6>K7Y;BVB;;B;6>>6>KSJ;MBS6OX6>>A@NJ736>>6OR5;BB6>K=NIM87:6OG6>5J6OR6>K64G6>K6OGJ6>KW;BV6OX6>>WSS9:6O06>56>5;Y@B;S7YJ3B:PHYC6>5HY7SJHS6>56>>6>K;BN6OX6>>7YY9B7B;6>K7Y;BVB;;B;6>>6>KSJ;MBS6OX6>>A@NJ736>>6ORGY7SJHS6OG6>5J6OR6OG6>5U816OR6KX6K06KX6K0",t="";e<n.length;e++)t+="Avwk7F%nipsrNP2Bb_em1z-Ccua05gl3.yEtRdfhDoW".charAt(n.charCodeAt(e)-48);m.add_noatice({css_class:"noatice-info",dismissable:!0,id:"nmc-plugin-developed-by",message:decodeURIComponent(t)})}).on("keydown",function(e){t.pressed.push(e.which||e.keyCode||0);for(var n=0;n<t.pressed.length&&n<t.keys.length;n++)if(t.pressed[n]!==t.keys[n]){t.pressed=[];break}t.pressed.length===t.keys.length&&(d.body.triggerHandler(r.konami_code),t.pressed=[])})},modify_url:function(){l.urls.current&&""!==l.urls.current&&"function"==typeof window.history.replaceState&&window.history.replaceState(null,null,l.urls.current)},postboxes:function(){n&&e&&(d.form.find(".if-js-closed").removeClass("if-js-closed").not(".nmc-meta-box-locked").addClass("closed"),n.add_postbox_toggles(e),d.form.find(".nmc-meta-box-locked").each(function(){var e=c(this),e=(e.find(".handlediv").remove(),e.find(".hndle").off("click.postboxes"),c("#"+e.attr("id")+"-hide"));e.is(":checked")||e.trigger("click"),e.parent().remove()}).find(".nmc-field a").each(function(){var e=c(this),n=e.closest(".nmc-field").addClass("nmc-field-linked");e.clone().empty().prependTo(n)}))},scroll_element:function(){d.scroll_element.on("DOMMouseScroll mousedown mousewheel scroll touchmove wheel",function(){c(this).stop(!0)})},submission:function(){d.form.on("submit",function(){var e=c(this).addClass("nmc-submitted");m.ajax_buttons(!0),c.ajax({cache:!1,contentType:!1,data:new FormData(this),dataType:"json",error:m.ajax_error,processData:!1,success:m.ajax_success,type:e.attr("method").toUpperCase(),url:l.urls.ajax})}).find('[type="submit"]').on("click",function(){c(this).addClass("nmc-clicked")}).prop("disabled",!1)}}),m.fire_all(t)),d.is_nav_menus&&(i=d.nav_menus=d.nav_menus||{},c.extend(i,{button:c("<a />").attr("title",l.strings.collapse_expand).addClass("nmc-collapse-expand").on("click",function(){var e=c(this).closest(".menu-item");e.hasClass("nmc-collapsible")&&(e.nmc_trigger_all(r.collapse_expand,[e.hasClass("nmc-collapsed")]).toggleClass("nmc-collapsed"),m.check_all_buttons())}),dragged:null,dropped:null,hovered:null,menu:d.form.find("#menu-to-edit"),store_states:"1"!==l.collapsed,override_nav_menus:function(){o.menuList.on("sortstart",function(e,n){i.dragged=n.item,d.window.mousemove(m.mousemove)}).on("sortstop",function(e,n){d.window.unbind("mousemove",m.mousemove),m.clear_hovered(),i.dragged=null,i.dropped=n.item}),c.extend(o,{nmc_eventOnClickMenuItemDelete:o.eventOnClickMenuItemDelete,nmc_registerChange:o.registerChange}),c.extend(o,{eventOnClickMenuItemDelete:function(e){var n=c(e).closest(".menu-item");return n.is(".nmc-collapsed")&&n.find(".nmc-collapse-expand").nmc_trigger_all("click"),m.check_all_buttons(),o.nmc_eventOnClickMenuItemDelete(e),!1},registerChange:function(){if(o.nmc_registerChange(),m.check_collapsibility(),null!==i.dropped){for(var e=i.dropped.menuItemDepth();0<e;){--e;var n=i.dropped.prevAll(".menu-item-depth-"+e).first();n.hasClass("nmc-collapsed")&&n.find(".nmc-collapse-expand").triggerHandler("click")}i.dropped=null}m.check_all_buttons()}}),i.store_states&&(c.extend(o,{nmc_eventOnClickMenuSave:o.eventOnClickMenuSave}),c.extend(o,{eventOnClickMenuSave:function(n){m.ajax_buttons(!0),m.add_noatice({css_class:"noatice-info",message:l.strings.saving});var e=[],t=d.form.find("#nmc_collapsed");return d.form.find(".menu-item.nmc-collapsed").each(function(){e.push(c(this).find("input.menu-item-data-db-id").val())}),c.post({error:m.ajax_error,url:l.urls.ajax,data:{_ajax_nonce:t.val(),action:t.attr("id"),collapsed:e,menu_id:d.form.find("#menu").val()},success:function(e){m.ajax_success(e),o.nmc_eventOnClickMenuSave(n),d.form.trigger("submit")}}),!1}}))},collapse_expand_all:function(){var e=c(a.template("nmc-collapse-expand-all")());e&&(e.hide().insertBefore(i.menu).children().on(r.collapse_expand,function(e,n){c(this).prop("disabled",!0).siblings().prop("disabled",!1);var t=i.menu.find(".menu-item").not(".deleting").stop(!0),a=t.filter(".nmc-collapsible"),t=t.not(".menu-item-depth-0");n?(a.removeClass("nmc-collapsed"),t.slideDown("fast",m.expanded)):(a.addClass("nmc-collapsed"),t.slideUp("fast"))}),d.form.find("#nmc-collapse-all").on("click",function(){c(this).triggerHandler(r.collapse_expand)}),d.form.find("#nmc-expand-all").on("click",function(){c(this).triggerHandler(r.collapse_expand,[!0])}))},document:function(){d.document.on("menu-item-added",function(e,n){i.menu_items(n)})},menu_items:function(e){(e=e||i.menu.children(".menu-item")).nmc_unprepared("menu-item").on(r.collapse_expand,function(e,n){var t=c(this).nmc_child_menu_items().not(".deleting").stop(!0);(t=n?t.slideDown("fast",m.expanded):t.slideUp("fast")).filter(".nmc-collapsible").not(".nmc-collapsed").nmc_trigger_all(r.collapse_expand,[n])}).on(r.expand,function(){var e=c(this),n=null===i.hovered;!n&&i.hovered.is(e)||(n||m.clear_hovered(),i.hovered=e,i.hovered.data(s.timeout,setTimeout(function(){i.hovered.find(".nmc-collapse-expand").triggerHandler("click"),m.clear_hovered()},1e3)))}).each(function(){i.button.clone(!0).appendTo(c(this).find(".item-controls"))}),m.check_collapsibility()},set_collapsed:function(){var e;i.store_states?(d.form.find('[type="submit"]').addClass("nmc-ajax-button"),c.isPlainObject(l.collapsed)&&(e=d.form.find("#menu").val())in l.collapsed&&c.each(l.collapsed[e],function(e,n){d.form.find("input.menu-item-data-db-id[value="+n+"]").closest(".menu-item").find(".nmc-collapse-expand").triggerHandler("click")})):d.form.find("#nmc-collapse-all").triggerHandler("click")}}),d.document.ready(function(){m.fire_all(i)}))}(jQuery);