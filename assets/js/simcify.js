/*!
 * Simcify.js
 * Version 1.0 - built Sat, Oct 6th 2018, 01:12 pm
 * https://simcycreative.com
 * Bai Ming - <hello@simcycreative.com>
 * Private License
 */
/**
 * Import libraries
 */
function _toConsumableArray(e){if(Array.isArray(e)){for(var t=0,i=Array(e.length);t<e.length;t++)i[t]=e[t];return i}return Array.from(e)}("function"==typeof define&&define.amd?define:function(e,t){"undefined"!=typeof module&&module.exports?module.exports=t(require("jquery")):window.toastr=t(window.jQuery)})(["jquery"],function(e){return function(){function t(t,i){return t||(t=s()),(a=e("#"+t.containerId)).length?a:(i&&(n=t,(a=e("<div/>").attr("id",n.containerId).addClass(n.positionClass)).appendTo(e(n.target)),a=a),a);var n}function i(t,i,n){var r=!(!n||!n.force)&&n.force;return!(!t||!r&&0!==e(":focus",t).length||(t[i.hideMethod]({duration:i.hideDuration,easing:i.hideEasing,complete:function(){o(t)}}),0))}function n(e){l&&l(e)}function r(i){function r(e){return null==e&&(e=""),e.replace(/&/g,"&amp;").replace(/"/g,"&quot;").replace(/'/g,"&#39;").replace(/</g,"&lt;").replace(/>/g,"&gt;")}function l(t){var i=t&&!1!==f.closeMethod?f.closeMethod:f.hideMethod,r=t&&!1!==f.closeDuration?f.closeDuration:f.hideDuration,s=t&&!1!==f.closeEasing?f.closeEasing:f.hideEasing;if(!e(":focus",g).length||t)return clearTimeout(x.intervalId),g[i]({duration:r,easing:s,complete:function(){o(g),clearTimeout(v),f.onHidden&&"hidden"!==E.state&&f.onHidden(),E.state="hidden",E.endTime=new Date,n(E)}})}function d(){(f.timeOut>0||f.extendedTimeOut>0)&&(v=setTimeout(l,f.extendedTimeOut),x.maxHideTime=parseFloat(f.extendedTimeOut),x.hideEta=(new Date).getTime()+x.maxHideTime)}function h(){clearTimeout(v),x.hideEta=0,g.stop(!0,!0)[f.showMethod]({duration:f.showDuration,easing:f.showEasing})}function p(){var e=(x.hideEta-(new Date).getTime())/x.maxHideTime*100;b.width(e+"%")}var f=s(),m=i.iconClass||f.iconClass;if(void 0!==i.optionsOverride&&(f=e.extend(f,i.optionsOverride),m=i.optionsOverride.iconClass||m),!function(e,t){if(e.preventDuplicates){if(t.message===u)return!0;u=t.message}return!1}(f,i)){c++,a=t(f,!0);var v=null,g=e("<div/>"),y=e("<div/>"),w=e("<div/>"),b=e("<div/>"),C=e(f.closeHtml),x={intervalId:null,hideEta:null,maxHideTime:null},E={toastId:c,state:"visible",startTime:new Date,options:f,map:i};return i.iconClass&&g.addClass(f.toastClass).addClass(m),function(){if(i.title){var e=i.title;f.escapeHtml&&(e=r(i.title)),y.append(e).addClass(f.titleClass),g.append(y)}}(),function(){if(i.message){var e=i.message;f.escapeHtml&&(e=r(i.message)),w.append(e).addClass(f.messageClass),g.append(w)}}(),f.closeButton&&(C.addClass(f.closeClass).attr("role","button"),g.prepend(C)),f.progressBar&&(b.addClass(f.progressClass),g.prepend(b)),f.rtl&&g.addClass("rtl"),f.newestOnTop?a.prepend(g):a.append(g),function(){var e="";switch(i.iconClass){case"toast-success":case"toast-info":e="polite";break;default:e="assertive"}g.attr("aria-live",e)}(),g.hide(),g[f.showMethod]({duration:f.showDuration,easing:f.showEasing,complete:f.onShown}),f.timeOut>0&&(v=setTimeout(l,f.timeOut),x.maxHideTime=parseFloat(f.timeOut),x.hideEta=(new Date).getTime()+x.maxHideTime,f.progressBar&&(x.intervalId=setInterval(p,10))),f.closeOnHover&&g.hover(h,d),!f.onclick&&f.tapToDismiss&&g.click(l),f.closeButton&&C&&C.click(function(e){e.stopPropagation?e.stopPropagation():void 0!==e.cancelBubble&&!0!==e.cancelBubble&&(e.cancelBubble=!0),f.onCloseClick&&f.onCloseClick(e),l(!0)}),f.onclick&&g.click(function(e){f.onclick(e),l()}),n(E),f.debug&&console&&console.log(E),g}}function s(){return e.extend({},{tapToDismiss:!0,toastClass:"toast",containerId:"toast-container",debug:!1,showMethod:"fadeIn",showDuration:300,showEasing:"swing",onShown:void 0,hideMethod:"fadeOut",hideDuration:1e3,hideEasing:"swing",onHidden:void 0,closeMethod:!1,closeDuration:!1,closeEasing:!1,closeOnHover:!0,extendedTimeOut:1e3,iconClasses:{error:"toast-error",info:"toast-info",success:"toast-success",warning:"toast-warning"},iconClass:"toast-info",positionClass:"toast-top-right",timeOut:5e3,titleClass:"toast-title",messageClass:"toast-message",escapeHtml:!1,target:"body",closeHtml:'<button type="button">&times;</button>',closeClass:"toast-close-button",newestOnTop:!0,preventDuplicates:!1,progressBar:!1,progressClass:"toast-progress",rtl:!1},h.options)}function o(e){a||(a=t()),e.is(":visible")||(e.remove(),e=null,0===a.children().length&&(a.remove(),u=void 0))}var a,l,u,c=0,d={error:"error",info:"info",success:"success",warning:"warning"},h={clear:function(n,r){var o=s();a||t(o),i(n,o,r)||function(t){for(var n=a.children(),r=n.length-1;r>=0;r--)i(e(n[r]),t)}(o)},remove:function(i){var n=s();return a||t(n),i&&0===e(":focus",i).length?void o(i):void(a.children().length&&a.remove())},error:function(e,t,i){return r({type:d.error,iconClass:s().iconClasses.error,message:e,optionsOverride:i,title:t})},getContainer:t,info:function(e,t,i){return r({type:d.info,iconClass:s().iconClasses.info,message:e,optionsOverride:i,title:t})},options:{},subscribe:function(e){l=e},success:function(e,t,i){return r({type:d.success,iconClass:s().iconClasses.success,message:e,optionsOverride:i,title:t})},version:"2.1.3",warning:function(e,t,i){return r({type:d.warning,iconClass:s().iconClasses.warning,message:e,optionsOverride:i,title:t})}};return h}()}),function(){function e(t){var i=e.modules[t];if(!i)throw new Error('failed to require "'+t+'"');return"exports"in i||"function"!=typeof i.definition||(i.client=i.component=!0,i.definition.call(this,i.exports={},i),delete i.definition),i.exports}e.loader="component",e.helper={},e.helper.semVerSort=function(e,t){for(var i=e.version.split("."),n=t.version.split("."),r=0;r<i.length;++r){var s=parseInt(i[r],10),o=parseInt(n[r],10);if(s!==o)return s>o?1:-1;var a=i[r].substr((""+s).length),l=n[r].substr((""+o).length);if(""===a&&""!==l)return 1;if(""!==a&&""===l)return-1;if(""!==a&&""!==l)return a>l?1:-1}return 0},e.latest=function(t,i){function n(e){throw new Error('failed to find latest module of "'+e+'"')}var r=/(.*)~(.*)@v?(\d+\.\d+\.\d+[^\/]*)$/;/(.*)~(.*)/.test(t)||n(t);for(var s=Object.keys(e.modules),o=[],a=[],l=0;l<s.length;l++){var u=s[l];if(new RegExp(t+"@").test(u)){var c=u.substr(t.length+1);null!=r.exec(u)?o.push({version:c,name:u}):a.push({version:c,name:u})}}if(0===o.concat(a).length&&n(t),o.length>0){var d=o.sort(e.helper.semVerSort).pop().name;return!0===i?d:e(d)}d=a.sort(function(e,t){return e.name>t.name})[0].name;return!0===i?d:e(d)},e.modules={},e.register=function(t,i){e.modules[t]={definition:i}},e.define=function(t,i){e.modules[t]={exports:i}},e.register("abpetkov~transitionize@0.0.3",function(e,t){function i(e,t){if(!(this instanceof i))return new i(e,t);this.element=e,this.props=t||{},this.init()}t.exports=i,i.prototype.isSafari=function(){return/Safari/.test(navigator.userAgent)&&/Apple Computer/.test(navigator.vendor)},i.prototype.init=function(){var e=[];for(var t in this.props)e.push(t+" "+this.props[t]);this.element.style.transition=e.join(", "),this.isSafari()&&(this.element.style.webkitTransition=e.join(", "))}}),e.register("ftlabs~fastclick@v0.6.11",function(e,t){function i(e){"use strict";var t,n=this;if(this.trackingClick=!1,this.trackingClickStart=0,this.targetElement=null,this.touchStartX=0,this.touchStartY=0,this.lastTouchIdentifier=0,this.touchBoundary=10,this.layer=e,!e||!e.nodeType)throw new TypeError("Layer must be a document node");this.onClick=function(){return i.prototype.onClick.apply(n,arguments)},this.onMouse=function(){return i.prototype.onMouse.apply(n,arguments)},this.onTouchStart=function(){return i.prototype.onTouchStart.apply(n,arguments)},this.onTouchMove=function(){return i.prototype.onTouchMove.apply(n,arguments)},this.onTouchEnd=function(){return i.prototype.onTouchEnd.apply(n,arguments)},this.onTouchCancel=function(){return i.prototype.onTouchCancel.apply(n,arguments)},i.notNeeded(e)||(this.deviceIsAndroid&&(e.addEventListener("mouseover",this.onMouse,!0),e.addEventListener("mousedown",this.onMouse,!0),e.addEventListener("mouseup",this.onMouse,!0)),e.addEventListener("click",this.onClick,!0),e.addEventListener("touchstart",this.onTouchStart,!1),e.addEventListener("touchmove",this.onTouchMove,!1),e.addEventListener("touchend",this.onTouchEnd,!1),e.addEventListener("touchcancel",this.onTouchCancel,!1),Event.prototype.stopImmediatePropagation||(e.removeEventListener=function(t,i,n){var r=Node.prototype.removeEventListener;"click"===t?r.call(e,t,i.hijacked||i,n):r.call(e,t,i,n)},e.addEventListener=function(t,i,n){var r=Node.prototype.addEventListener;"click"===t?r.call(e,t,i.hijacked||(i.hijacked=function(e){e.propagationStopped||i(e)}),n):r.call(e,t,i,n)}),"function"==typeof e.onclick&&(t=e.onclick,e.addEventListener("click",function(e){t(e)},!1),e.onclick=null))}i.prototype.deviceIsAndroid=navigator.userAgent.indexOf("Android")>0,i.prototype.deviceIsIOS=/iP(ad|hone|od)/.test(navigator.userAgent),i.prototype.deviceIsIOS4=i.prototype.deviceIsIOS&&/OS 4_\d(_\d)?/.test(navigator.userAgent),i.prototype.deviceIsIOSWithBadTarget=i.prototype.deviceIsIOS&&/OS ([6-9]|\d{2})_\d/.test(navigator.userAgent),i.prototype.needsClick=function(e){"use strict";switch(e.nodeName.toLowerCase()){case"button":case"select":case"textarea":if(e.disabled)return!0;break;case"input":if(this.deviceIsIOS&&"file"===e.type||e.disabled)return!0;break;case"label":case"video":return!0}return/\bneedsclick\b/.test(e.className)},i.prototype.needsFocus=function(e){"use strict";switch(e.nodeName.toLowerCase()){case"textarea":return!0;case"select":return!this.deviceIsAndroid;case"input":switch(e.type){case"button":case"checkbox":case"file":case"image":case"radio":case"submit":return!1}return!e.disabled&&!e.readOnly;default:return/\bneedsfocus\b/.test(e.className)}},i.prototype.sendClick=function(e,t){"use strict";var i,n;document.activeElement&&document.activeElement!==e&&document.activeElement.blur(),n=t.changedTouches[0],(i=document.createEvent("MouseEvents")).initMouseEvent(this.determineEventType(e),!0,!0,window,1,n.screenX,n.screenY,n.clientX,n.clientY,!1,!1,!1,!1,0,null),i.forwardedTouchEvent=!0,e.dispatchEvent(i)},i.prototype.determineEventType=function(e){"use strict";return this.deviceIsAndroid&&"select"===e.tagName.toLowerCase()?"mousedown":"click"},i.prototype.focus=function(e){"use strict";var t;this.deviceIsIOS&&e.setSelectionRange&&0!==e.type.indexOf("date")&&"time"!==e.type?(t=e.value.length,e.setSelectionRange(t,t)):e.focus()},i.prototype.updateScrollParent=function(e){"use strict";var t,i;if(!(t=e.fastClickScrollParent)||!t.contains(e)){i=e;do{if(i.scrollHeight>i.offsetHeight){t=i,e.fastClickScrollParent=i;break}i=i.parentElement}while(i)}t&&(t.fastClickLastScrollTop=t.scrollTop)},i.prototype.getTargetElementFromEventTarget=function(e){"use strict";return e.nodeType===Node.TEXT_NODE?e.parentNode:e},i.prototype.onTouchStart=function(e){"use strict";var t,i,n;if(e.targetTouches.length>1)return!0;if(t=this.getTargetElementFromEventTarget(e.target),i=e.targetTouches[0],this.deviceIsIOS){if((n=window.getSelection()).rangeCount&&!n.isCollapsed)return!0;if(!this.deviceIsIOS4){if(i.identifier===this.lastTouchIdentifier)return e.preventDefault(),!1;this.lastTouchIdentifier=i.identifier,this.updateScrollParent(t)}}return this.trackingClick=!0,this.trackingClickStart=e.timeStamp,this.targetElement=t,this.touchStartX=i.pageX,this.touchStartY=i.pageY,e.timeStamp-this.lastClickTime<200&&e.preventDefault(),!0},i.prototype.touchHasMoved=function(e){"use strict";var t=e.changedTouches[0],i=this.touchBoundary;return Math.abs(t.pageX-this.touchStartX)>i||Math.abs(t.pageY-this.touchStartY)>i},i.prototype.onTouchMove=function(e){"use strict";return!this.trackingClick||((this.targetElement!==this.getTargetElementFromEventTarget(e.target)||this.touchHasMoved(e))&&(this.trackingClick=!1,this.targetElement=null),!0)},i.prototype.findControl=function(e){"use strict";return void 0!==e.control?e.control:e.htmlFor?document.getElementById(e.htmlFor):e.querySelector("button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea")},i.prototype.onTouchEnd=function(e){"use strict";var t,i,n,r,s,o=this.targetElement;if(!this.trackingClick)return!0;if(e.timeStamp-this.lastClickTime<200)return this.cancelNextClick=!0,!0;if(this.cancelNextClick=!1,this.lastClickTime=e.timeStamp,i=this.trackingClickStart,this.trackingClick=!1,this.trackingClickStart=0,this.deviceIsIOSWithBadTarget&&(s=e.changedTouches[0],(o=document.elementFromPoint(s.pageX-window.pageXOffset,s.pageY-window.pageYOffset)||o).fastClickScrollParent=this.targetElement.fastClickScrollParent),"label"===(n=o.tagName.toLowerCase())){if(t=this.findControl(o)){if(this.focus(o),this.deviceIsAndroid)return!1;o=t}}else if(this.needsFocus(o))return e.timeStamp-i>100||this.deviceIsIOS&&window.top!==window&&"input"===n?(this.targetElement=null,!1):(this.focus(o),this.deviceIsIOS4&&"select"===n||(this.targetElement=null,e.preventDefault()),!1);return!(!this.deviceIsIOS||this.deviceIsIOS4||!(r=o.fastClickScrollParent)||r.fastClickLastScrollTop===r.scrollTop)||(this.needsClick(o)||(e.preventDefault(),this.sendClick(o,e)),!1)},i.prototype.onTouchCancel=function(){"use strict";this.trackingClick=!1,this.targetElement=null},i.prototype.onMouse=function(e){"use strict";return!this.targetElement||(!!e.forwardedTouchEvent||(!e.cancelable||(!(!this.needsClick(this.targetElement)||this.cancelNextClick)||(e.stopImmediatePropagation?e.stopImmediatePropagation():e.propagationStopped=!0,e.stopPropagation(),e.preventDefault(),!1))))},i.prototype.onClick=function(e){"use strict";var t;return this.trackingClick?(this.targetElement=null,this.trackingClick=!1,!0):"submit"===e.target.type&&0===e.detail||((t=this.onMouse(e))||(this.targetElement=null),t)},i.prototype.destroy=function(){"use strict";var e=this.layer;this.deviceIsAndroid&&(e.removeEventListener("mouseover",this.onMouse,!0),e.removeEventListener("mousedown",this.onMouse,!0),e.removeEventListener("mouseup",this.onMouse,!0)),e.removeEventListener("click",this.onClick,!0),e.removeEventListener("touchstart",this.onTouchStart,!1),e.removeEventListener("touchmove",this.onTouchMove,!1),e.removeEventListener("touchend",this.onTouchEnd,!1),e.removeEventListener("touchcancel",this.onTouchCancel,!1)},i.notNeeded=function(e){"use strict";var t,n;if(void 0===window.ontouchstart)return!0;if(n=+(/Chrome\/([0-9]+)/.exec(navigator.userAgent)||[,0])[1]){if(!i.prototype.deviceIsAndroid)return!0;if(t=document.querySelector("meta[name=viewport]")){if(-1!==t.content.indexOf("user-scalable=no"))return!0;if(n>31&&window.innerWidth<=window.screen.width)return!0}}return"none"===e.style.msTouchAction},i.attach=function(e){"use strict";return new i(e)},"undefined"!=typeof define&&define.amd?define(function(){"use strict";return i}):void 0!==t&&t.exports?(t.exports=i.attach,t.exports.FastClick=i):window.FastClick=i}),e.register("component~indexof@0.0.3",function(e,t){t.exports=function(e,t){if(e.indexOf)return e.indexOf(t);for(var i=0;i<e.length;++i)if(e[i]===t)return i;return-1}}),e.register("component~classes@1.2.1",function(t,i){var n=e("component~indexof@0.0.3"),r=/\s+/,s=Object.prototype.toString;function o(e){if(!e)throw new Error("A DOM element reference is required");this.el=e,this.list=e.classList}i.exports=function(e){return new o(e)},o.prototype.add=function(e){if(this.list)return this.list.add(e),this;var t=this.array();return~n(t,e)||t.push(e),this.el.className=t.join(" "),this},o.prototype.remove=function(e){if("[object RegExp]"==s.call(e))return this.removeMatching(e);if(this.list)return this.list.remove(e),this;var t=this.array(),i=n(t,e);return~i&&t.splice(i,1),this.el.className=t.join(" "),this},o.prototype.removeMatching=function(e){for(var t=this.array(),i=0;i<t.length;i++)e.test(t[i])&&this.remove(t[i]);return this},o.prototype.toggle=function(e,t){return this.list?(void 0!==t?t!==this.list.toggle(e,t)&&this.list.toggle(e):this.list.toggle(e),this):(void 0!==t?t?this.add(e):this.remove(e):this.has(e)?this.remove(e):this.add(e),this)},o.prototype.array=function(){var e=this.el.className.replace(/^\s+|\s+$/g,"").split(r);return""===e[0]&&e.shift(),e},o.prototype.has=o.prototype.contains=function(e){return this.list?this.list.contains(e):!!~n(this.array(),e)}}),e.register("component~event@0.1.4",function(e,t){var i=window.addEventListener?"addEventListener":"attachEvent",n=window.removeEventListener?"removeEventListener":"detachEvent",r="addEventListener"!==i?"on":"";e.bind=function(e,t,n,s){return e[i](r+t,n,s||!1),n},e.unbind=function(e,t,i,s){return e[n](r+t,i,s||!1),i}}),e.register("component~query@0.0.3",function(e,t){function i(e,t){return t.querySelector(e)}(e=t.exports=function(e,t){return i(e,t=t||document)}).all=function(e,t){return(t=t||document).querySelectorAll(e)},e.engine=function(t){if(!t.one)throw new Error(".one callback required");if(!t.all)throw new Error(".all callback required");return i=t.one,e.all=t.all,e}}),e.register("component~matches-selector@0.1.5",function(t,i){var n=e("component~query@0.0.3"),r=Element.prototype,s=r.matches||r.webkitMatchesSelector||r.mozMatchesSelector||r.msMatchesSelector||r.oMatchesSelector;i.exports=function(e,t){if(!e||1!==e.nodeType)return!1;if(s)return s.call(e,t);for(var i=n.all(t,e.parentNode),r=0;r<i.length;++r)if(i[r]==e)return!0;return!1}}),e.register("component~closest@0.1.4",function(t,i){var n=e("component~matches-selector@0.1.5");i.exports=function(e,t,i,r){for(e=i?{parentNode:e}:e,r=r||document;(e=e.parentNode)&&e!==document;){if(n(e,t))return e;if(e===r)return}}}),e.register("component~delegate@0.2.3",function(t,i){var n=e("component~closest@0.1.4"),r=e("component~event@0.1.4");t.bind=function(e,t,i,s,o){return r.bind(e,i,function(i){var r=i.target||i.srcElement;i.delegateTarget=n(r,t,!0,e),i.delegateTarget&&s.call(e,i)},o)},t.unbind=function(e,t,i,n){r.unbind(e,t,i,n)}}),e.register("component~events@1.0.9",function(t,i){var n=e("component~event@0.1.4"),r=e("component~delegate@0.2.3");function s(e,t){if(!(this instanceof s))return new s(e,t);if(!e)throw new Error("element required");if(!t)throw new Error("object required");this.el=e,this.obj=t,this._events={}}i.exports=s,s.prototype.sub=function(e,t,i){this._events[e]=this._events[e]||{},this._events[e][t]=i},s.prototype.bind=function(e,t){var i,s={name:(i=e.split(/ +/)).shift(),selector:i.join(" ")},o=this.el,a=this.obj,l=s.name,u=(t=t||"on"+l,[].slice.call(arguments,2));function c(){var e=[].slice.call(arguments).concat(u);a[t].apply(a,e)}return s.selector?c=r.bind(o,s.selector,l,c):n.bind(o,l,c),this.sub(l,t,c),c},s.prototype.unbind=function(e,t){if(0==arguments.length)return this.unbindAll();if(1==arguments.length)return this.unbindAllOf(e);var i=this._events[e];if(i){var r=i[t];r&&n.unbind(this.el,e,r)}},s.prototype.unbindAll=function(){for(var e in this._events)this.unbindAllOf(e)},s.prototype.unbindAllOf=function(e){var t=this._events[e];if(t)for(var i in t)this.unbind(e,i)}}),e.register("switchery",function(t,i){var n=e("abpetkov~transitionize@0.0.3"),r=e("ftlabs~fastclick@v0.6.11"),s=e("component~classes@1.2.1"),o=e("component~events@1.0.9");i.exports=l;var a={color:"#64bd63",secondaryColor:"#dfdfdf",jackColor:"#fff",jackSecondaryColor:null,className:"switchery",disabled:!1,disabledOpacity:.5,speed:"0.4s",size:"default"};function l(e,t){if(!(this instanceof l))return new l(e,t);this.element=e,this.options=t||{};for(var i in a)null==this.options[i]&&(this.options[i]=a[i]);null!=this.element&&"checkbox"==this.element.type&&this.init(),!0===this.isDisabled()&&this.disable()}l.prototype.hide=function(){this.element.style.display="none"},l.prototype.show=function(){var e=this.create();this.insertAfter(this.element,e)},l.prototype.create=function(){return this.switcher=document.createElement("span"),this.jack=document.createElement("small"),this.switcher.appendChild(this.jack),this.switcher.className=this.options.className,this.events=o(this.switcher,this),this.switcher},l.prototype.insertAfter=function(e,t){e.parentNode.insertBefore(t,e.nextSibling)},l.prototype.setPosition=function(e){var t=this.isChecked(),i=this.switcher,n=this.jack;e&&t?t=!1:e&&!t&&(t=!0),!0===t?(this.element.checked=!0,window.getComputedStyle?n.style.left=parseInt(window.getComputedStyle(i).width)-parseInt(window.getComputedStyle(n).width)+"px":n.style.left=parseInt(i.currentStyle.width)-parseInt(n.currentStyle.width)+"px",this.options.color&&this.colorize(),this.setSpeed()):(n.style.left=0,this.element.checked=!1,this.switcher.style.boxShadow="inset 0 0 0 0 "+this.options.secondaryColor,this.switcher.style.borderColor=this.options.secondaryColor,this.switcher.style.backgroundColor=this.options.secondaryColor!==a.secondaryColor?this.options.secondaryColor:"#fff",this.jack.style.backgroundColor=this.options.jackSecondaryColor!==this.options.jackColor?this.options.jackSecondaryColor:this.options.jackColor,this.setSpeed())},l.prototype.setSpeed=function(){var e={},t={"background-color":this.options.speed,left:this.options.speed.replace(/[a-z]/,"")/2+"s"};e=this.isChecked()?{border:this.options.speed,"box-shadow":this.options.speed,"background-color":3*this.options.speed.replace(/[a-z]/,"")+"s"}:{border:this.options.speed,"box-shadow":this.options.speed},n(this.switcher,e),n(this.jack,t)},l.prototype.setSize=function(){switch(this.options.size){case"small":s(this.switcher).add("switchery-small");break;case"large":s(this.switcher).add("switchery-large");break;default:s(this.switcher).add("switchery-default")}},l.prototype.colorize=function(){var e=this.switcher.offsetHeight/2;this.switcher.style.backgroundColor=this.options.color,this.switcher.style.borderColor=this.options.color,this.switcher.style.boxShadow="inset 0 0 0 "+e+"px "+this.options.color,this.jack.style.backgroundColor=this.options.jackColor},l.prototype.handleOnchange=function(e){if(document.dispatchEvent){var t=document.createEvent("HTMLEvents");t.initEvent("change",!0,!0),this.element.dispatchEvent(t)}else this.element.fireEvent("onchange")},l.prototype.handleChange=function(){var e=this,t=this.element;t.addEventListener?t.addEventListener("change",function(){e.setPosition()}):t.attachEvent("onchange",function(){e.setPosition()})},l.prototype.handleClick=function(){var e=this.switcher;r(e),this.events.bind("click","bindClick")},l.prototype.bindClick=function(){var e="label"!==this.element.parentNode.tagName.toLowerCase();this.setPosition(e),this.handleOnchange(this.element.checked)},l.prototype.markAsSwitched=function(){this.element.setAttribute("data-switchery",!0)},l.prototype.markedAsSwitched=function(){return this.element.getAttribute("data-switchery")},l.prototype.init=function(){this.hide(),this.show(),this.setSize(),this.setPosition(),this.markAsSwitched(),this.handleChange(),this.handleClick()},l.prototype.isChecked=function(){return this.element.checked},l.prototype.isDisabled=function(){return this.options.disabled||this.element.disabled||this.element.readOnly},l.prototype.destroy=function(){this.events.unbind()},l.prototype.enable=function(){this.options.disabled&&(this.options.disabled&&(this.options.disabled=!1),this.element.disabled&&(this.element.disabled=!1),this.element.readOnly&&(this.element.readOnly=!1),this.switcher.style.opacity=1,this.events.bind("click","bindClick"))},l.prototype.disable=function(){this.options.disabled||(this.options.disabled||(this.options.disabled=!0),this.element.disabled||(this.element.disabled=!0),this.element.readOnly||(this.element.readOnly=!0),this.switcher.style.opacity=this.options.disabledOpacity,this.destroy())}}),"object"==typeof exports?module.exports=e("switchery"):"function"==typeof define&&define.amd?define("Switchery",[],function(){return e("switchery")}):(this||window).Switchery=e("switchery")}(),function(e,t,i){"use strict";!function e(t,i,n){function r(o,a){if(!i[o]){if(!t[o]){var l="function"==typeof require&&require;if(!a&&l)return l(o,!0);if(s)return s(o,!0);var u=new Error("Cannot find module '"+o+"'");throw u.code="MODULE_NOT_FOUND",u}var c=i[o]={exports:{}};t[o][0].call(c.exports,function(e){var i=t[o][1][e];return r(i||e)},c,c.exports,e,t,i,n)}return i[o].exports}for(var s="function"==typeof require&&require,o=0;o<n.length;o++)r(n[o]);return r}({1:[function(n,r,s){function o(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(s,"__esModule",{value:!0});var a,l,u,c,d=n("./modules/handle-dom"),h=n("./modules/utils"),p=n("./modules/handle-swal-dom"),f=n("./modules/handle-click"),m=o(n("./modules/handle-key")),v=o(n("./modules/default-params")),g=o(n("./modules/set-params"));s.default=u=c=function(){function n(e){var t=r;return t[e]===i?v.default[e]:t[e]}var r=arguments[0];if((0,d.addClass)(t.body,"stop-scrolling"),(0,p.resetInput)(),r===i)return(0,h.logStr)("SweetAlert expects at least 1 attribute!"),!1;var s=(0,h.extend)({},v.default);switch(typeof r){case"string":s.title=r,s.text=arguments[1]||"",s.type=arguments[2]||"";break;case"object":if(r.title===i)return(0,h.logStr)('Missing "title" argument!'),!1;s.title=r.title;for(var o in v.default)s[o]=n(o);s.confirmButtonText=s.showCancelButton?"Confirm":v.default.confirmButtonText,s.confirmButtonText=n("confirmButtonText"),s.doneFunction=arguments[1]||null;break;default:return(0,h.logStr)('Unexpected type of argument! Expected "string" or "object", got '+typeof r),!1}(0,g.default)(s),(0,p.fixVerticalPosition)(),(0,p.openModal)(arguments[1]);for(var u=(0,p.getModal)(),y=u.querySelectorAll("button"),w=["onclick","onmouseover","onmouseout","onmousedown","onmouseup","onfocus"],b=function(e){return(0,f.handleButton)(e,s,u)},C=0;C<y.length;C++)for(var x=0;x<w.length;x++){var E=w[x];y[C][E]=b}(0,p.getOverlay)().onclick=b,a=e.onkeydown;e.onkeydown=function(e){return(0,m.default)(e,s,u)},e.onfocus=function(){setTimeout(function(){l!==i&&(l.focus(),l=i)},0)},c.enableButtons()},u.setDefaults=c.setDefaults=function(e){if(!e)throw new Error("userParams is required");if("object"!=typeof e)throw new Error("userParams has to be a object");(0,h.extend)(v.default,e)},u.close=c.close=function(){var n=(0,p.getModal)();(0,d.fadeOut)((0,p.getOverlay)(),5),(0,d.fadeOut)(n,5),(0,d.removeClass)(n,"showSweetAlert"),(0,d.addClass)(n,"hideSweetAlert"),(0,d.removeClass)(n,"visible");var r=n.querySelector(".sa-icon.sa-success");(0,d.removeClass)(r,"animate"),(0,d.removeClass)(r.querySelector(".sa-tip"),"animateSuccessTip"),(0,d.removeClass)(r.querySelector(".sa-long"),"animateSuccessLong");var s=n.querySelector(".sa-icon.sa-error");(0,d.removeClass)(s,"animateErrorIcon"),(0,d.removeClass)(s.querySelector(".sa-x-mark"),"animateXMark");var o=n.querySelector(".sa-icon.sa-warning");return(0,d.removeClass)(o,"pulseWarning"),(0,d.removeClass)(o.querySelector(".sa-body"),"pulseWarningIns"),(0,d.removeClass)(o.querySelector(".sa-dot"),"pulseWarningIns"),setTimeout(function(){var e=n.getAttribute("data-custom-class");(0,d.removeClass)(n,e)},300),(0,d.removeClass)(t.body,"stop-scrolling"),e.onkeydown=a,e.previousActiveElement&&e.previousActiveElement.focus(),l=i,clearTimeout(n.timeout),!0},u.showInputError=c.showInputError=function(e){var t=(0,p.getModal)(),i=t.querySelector(".sa-input-error");(0,d.addClass)(i,"show");var n=t.querySelector(".sa-error-container");(0,d.addClass)(n,"show"),n.querySelector("p").innerHTML=e,setTimeout(function(){u.enableButtons()},1),t.querySelector("input").focus()},u.resetInputError=c.resetInputError=function(e){if(e&&13===e.keyCode)return!1;var t=(0,p.getModal)(),i=t.querySelector(".sa-input-error");(0,d.removeClass)(i,"show");var n=t.querySelector(".sa-error-container");(0,d.removeClass)(n,"show")},u.disableButtons=c.disableButtons=function(e){var t=(0,p.getModal)(),i=t.querySelector("button.confirm"),n=t.querySelector("button.cancel");i.disabled=!0,n.disabled=!0},u.enableButtons=c.enableButtons=function(e){var t=(0,p.getModal)(),i=t.querySelector("button.confirm"),n=t.querySelector("button.cancel");i.disabled=!1,n.disabled=!1},void 0!==e?e.sweetAlert=e.swal=u:(0,h.logStr)("SweetAlert is a frontend module!"),r.exports=s.default},{"./modules/default-params":2,"./modules/handle-click":3,"./modules/handle-dom":4,"./modules/handle-key":5,"./modules/handle-swal-dom":6,"./modules/set-params":8,"./modules/utils":9}],2:[function(e,t,i){Object.defineProperty(i,"__esModule",{value:!0});i.default={title:"",text:"",type:null,allowOutsideClick:!1,showConfirmButton:!0,showCancelButton:!1,closeOnConfirm:!0,closeOnCancel:!0,confirmButtonText:"OK",confirmButtonColor:"#8CD4F5",cancelButtonText:"Cancel",imageUrl:null,imageSize:null,timer:null,customClass:"",html:!1,animation:!0,allowEscapeKey:!0,inputType:"text",inputPlaceholder:"",inputValue:"",showLoaderOnConfirm:!1},t.exports=i.default},{}],3:[function(t,i,n){Object.defineProperty(n,"__esModule",{value:!0});var r=t("./utils"),s=(t("./handle-swal-dom"),t("./handle-dom")),o=function(e,t){var i=!0;(0,s.hasClass)(e,"show-input")&&((i=e.querySelector("input").value)||(i="")),t.doneFunction(i),t.closeOnConfirm&&sweetAlert.close(),t.showLoaderOnConfirm&&sweetAlert.disableButtons()},a=function(e,t){var i=String(t.doneFunction).replace(/\s/g,"");"function("===i.substring(0,9)&&")"!==i.substring(9,10)&&t.doneFunction(!1),t.closeOnCancel&&sweetAlert.close()};n.default={handleButton:function(t,i,n){function l(e){f&&i.confirmButtonColor&&(p.style.backgroundColor=e)}var u,c,d,h=t||e.event,p=h.target||h.srcElement,f=-1!==p.className.indexOf("confirm"),m=-1!==p.className.indexOf("sweet-overlay"),v=(0,s.hasClass)(n,"visible"),g=i.doneFunction&&"true"===n.getAttribute("data-has-done-function");switch(f&&i.confirmButtonColor&&(u=i.confirmButtonColor,c=(0,r.colorLuminance)(u,-.04),d=(0,r.colorLuminance)(u,-.14)),h.type){case"mouseover":l(c);break;case"mouseout":l(u);break;case"mousedown":l(d);break;case"mouseup":l(c);break;case"focus":var y=n.querySelector("button.confirm"),w=n.querySelector("button.cancel");f?w.style.boxShadow="none":y.style.boxShadow="none";break;case"click":var b=n===p,C=(0,s.isDescendant)(n,p);if(!b&&!C&&v&&!i.allowOutsideClick)break;f&&g&&v?o(n,i):g&&v||m?a(n,i):(0,s.isDescendant)(n,p)&&"BUTTON"===p.tagName&&sweetAlert.close()}},handleConfirm:o,handleCancel:a},i.exports=n.default},{"./handle-dom":4,"./handle-swal-dom":6,"./utils":9}],4:[function(i,n,r){Object.defineProperty(r,"__esModule",{value:!0});var s=function(e,t){return new RegExp(" "+t+" ").test(" "+e.className+" ")},o=function(e){e.style.opacity="",e.style.display="block"},a=function(e){e.style.opacity="",e.style.display="none"};r.hasClass=s,r.addClass=function(e,t){s(e,t)||(e.className+=" "+t)},r.removeClass=function(e,t){var i=" "+e.className.replace(/[\t\r\n]/g," ")+" ";if(s(e,t)){for(;i.indexOf(" "+t+" ")>=0;)i=i.replace(" "+t+" "," ");e.className=i.replace(/^\s+|\s+$/g,"")}},r.escapeHtml=function(e){var i=t.createElement("div");return i.appendChild(t.createTextNode(e)),i.innerHTML},r._show=o,r.show=function(e){if(e&&!e.length)return o(e);for(var t=0;t<e.length;++t)o(e[t])},r._hide=a,r.hide=function(e){if(e&&!e.length)return a(e);for(var t=0;t<e.length;++t)a(e[t])},r.isDescendant=function(e,t){for(var i=t.parentNode;null!==i;){if(i===e)return!0;i=i.parentNode}return!1},r.getTopMargin=function(e){e.style.left="-9999px",e.style.display="block";var t,i=e.clientHeight;return t="undefined"!=typeof getComputedStyle?parseInt(getComputedStyle(e).getPropertyValue("padding-top"),10):parseInt(e.currentStyle.padding),e.style.left="",e.style.display="none","-"+parseInt((i+t)/2)+"px"},r.fadeIn=function(e,t){if(+e.style.opacity<1){t=t||16,e.style.opacity=0,e.style.display="block";var i=+new Date;!function n(){e.style.opacity=+e.style.opacity+(new Date-i)/100,i=+new Date,+e.style.opacity<1&&setTimeout(n,t)}()}e.style.display="block"},r.fadeOut=function(e,t){t=t||16,e.style.opacity=1;var i=+new Date;!function n(){e.style.opacity=+e.style.opacity-(new Date-i)/100,i=+new Date,+e.style.opacity>0?setTimeout(n,t):e.style.display="none"}()},r.fireClick=function(i){if("function"==typeof MouseEvent){var n=new MouseEvent("click",{view:e,bubbles:!1,cancelable:!0});i.dispatchEvent(n)}else if(t.createEvent){var r=t.createEvent("MouseEvents");r.initEvent("click",!1,!1),i.dispatchEvent(r)}else t.createEventObject?i.fireEvent("onclick"):"function"==typeof i.onclick&&i.onclick()},r.stopEventPropagation=function(t){"function"==typeof t.stopPropagation?(t.stopPropagation(),t.preventDefault()):e.event&&e.event.hasOwnProperty("cancelBubble")&&(e.event.cancelBubble=!0)}},{}],5:[function(t,n,r){Object.defineProperty(r,"__esModule",{value:!0});var s=t("./handle-dom"),o=t("./handle-swal-dom");r.default=function(t,n,r){var a=t||e.event,l=a.keyCode||a.which,u=r.querySelector("button.confirm"),c=r.querySelector("button.cancel"),d=r.querySelectorAll("button[tabindex]");if(-1!==[9,13,32,27].indexOf(l)){for(var h=a.target||a.srcElement,p=-1,f=0;f<d.length;f++)if(h===d[f]){p=f;break}9===l?(h=-1===p?u:p===d.length-1?d[0]:d[p+1],(0,s.stopEventPropagation)(a),h.focus(),n.confirmButtonColor&&(0,o.setFocusStyle)(h,n.confirmButtonColor)):13===l?("INPUT"===h.tagName&&(h=u,u.focus()),h=-1===p?u:i):27===l&&!0===n.allowEscapeKey?(h=c,(0,s.fireClick)(h,a)):h=i}},n.exports=r.default},{"./handle-dom":4,"./handle-swal-dom":6}],6:[function(i,n,r){function s(e){return e&&e.__esModule?e:{default:e}}Object.defineProperty(r,"__esModule",{value:!0});var o=i("./utils"),a=i("./handle-dom"),l=s(i("./default-params")),u=s(i("./injected-html")),c=function(){var e=t.createElement("div");for(e.innerHTML=u.default;e.firstChild;)t.body.appendChild(e.firstChild)},d=function e(){var i=t.querySelector(".sweet-alert");return i||(c(),i=e()),i},h=function(){var e=d();return e?e.querySelector("input"):void 0},p=function(){return t.querySelector(".sweet-overlay")},f=function(e){if(e&&13===e.keyCode)return!1;var t=d(),i=t.querySelector(".sa-input-error");(0,a.removeClass)(i,"show");var n=t.querySelector(".sa-error-container");(0,a.removeClass)(n,"show")};r.sweetAlertInitialize=c,r.getModal=d,r.getOverlay=p,r.getInput=h,r.setFocusStyle=function(e,t){var i=(0,o.hexToRgb)(t);e.style.boxShadow="0 0 2px rgba("+i+", 0.8), inset 0 0 0 1px rgba(0, 0, 0, 0.05)"},r.openModal=function(i){var n=d();(0,a.fadeIn)(p(),10),(0,a.show)(n),(0,a.addClass)(n,"showSweetAlert"),(0,a.removeClass)(n,"hideSweetAlert"),e.previousActiveElement=t.activeElement,n.querySelector("button.confirm").focus(),setTimeout(function(){(0,a.addClass)(n,"visible")},500);var r=n.getAttribute("data-timer");if("null"!==r&&""!==r){var s=i;n.timeout=setTimeout(function(){s&&"true"===n.getAttribute("data-has-done-function")?s(null):sweetAlert.close()},r)}},r.resetInput=function(){var e=d(),t=h();(0,a.removeClass)(e,"show-input"),t.value=l.default.inputValue,t.setAttribute("type",l.default.inputType),t.setAttribute("placeholder",l.default.inputPlaceholder),f()},r.resetInputError=f,r.fixVerticalPosition=function(){d().style.marginTop=(0,a.getTopMargin)(d())}},{"./default-params":2,"./handle-dom":4,"./injected-html":7,"./utils":9}],7:[function(e,t,i){Object.defineProperty(i,"__esModule",{value:!0});i.default='<div class="sweet-overlay" tabIndex="-1"></div><div class="sweet-alert"><div class="sa-icon sa-error">\n      <span class="sa-x-mark">\n        <span class="sa-line sa-left"></span>\n        <span class="sa-line sa-right"></span>\n      </span>\n    </div><div class="sa-icon sa-warning">\n      <span class="sa-body"></span>\n      <span class="sa-dot"></span>\n    </div><div class="sa-icon sa-info"></div><div class="sa-icon sa-success">\n      <span class="sa-line sa-tip"></span>\n      <span class="sa-line sa-long"></span>\n\n      <div class="sa-placeholder"></div>\n      <div class="sa-fix"></div>\n    </div><div class="sa-icon sa-custom"></div><h2>Title</h2>\n    <p>Text</p>\n    <fieldset>\n      <input type="text" tabIndex="3" />\n      <div class="sa-input-error"></div>\n    </fieldset><div class="sa-error-container">\n      <div class="icon">!</div>\n      <p>Not valid!</p>\n    </div><div class="sa-button-container">\n      <button class="cancel" tabIndex="2">Cancel</button>\n      <div class="sa-confirm-button-container">\n        <button class="confirm" tabIndex="1">OK</button><div class="la-ball-fall">\n          <div></div>\n          <div></div>\n          <div></div>\n        </div>\n      </div>\n    </div></div>',t.exports=i.default},{}],8:[function(e,t,n){Object.defineProperty(n,"__esModule",{value:!0});var r=e("./utils"),s=e("./handle-swal-dom"),o=e("./handle-dom"),a=["error","warning","info","success","input","prompt"];n.default=function(e){var t=(0,s.getModal)(),n=t.querySelector("h2"),l=t.querySelector("p"),u=t.querySelector("button.cancel"),c=t.querySelector("button.confirm");if(n.innerHTML=e.html?e.title:(0,o.escapeHtml)(e.title).split("\n").join("<br>"),l.innerHTML=e.html?e.text:(0,o.escapeHtml)(e.text||"").split("\n").join("<br>"),e.text&&(0,o.show)(l),e.customClass)(0,o.addClass)(t,e.customClass),t.setAttribute("data-custom-class",e.customClass);else{var d=t.getAttribute("data-custom-class");(0,o.removeClass)(t,d),t.setAttribute("data-custom-class","")}if((0,o.hide)(t.querySelectorAll(".sa-icon")),e.type&&!(0,r.isIE8)()){var h=function(){for(var n=!1,r=0;r<a.length;r++)if(e.type===a[r]){n=!0;break}if(!n)return logStr("Unknown alert type: "+e.type),{v:!1};var l=i;-1!==["success","error","warning","info"].indexOf(e.type)&&(l=t.querySelector(".sa-icon.sa-"+e.type),(0,o.show)(l));var u=(0,s.getInput)();switch(e.type){case"success":(0,o.addClass)(l,"animate"),(0,o.addClass)(l.querySelector(".sa-tip"),"animateSuccessTip"),(0,o.addClass)(l.querySelector(".sa-long"),"animateSuccessLong");break;case"error":(0,o.addClass)(l,"animateErrorIcon"),(0,o.addClass)(l.querySelector(".sa-x-mark"),"animateXMark");break;case"warning":(0,o.addClass)(l,"pulseWarning"),(0,o.addClass)(l.querySelector(".sa-body"),"pulseWarningIns"),(0,o.addClass)(l.querySelector(".sa-dot"),"pulseWarningIns");break;case"input":case"prompt":u.setAttribute("type",e.inputType),u.value=e.inputValue,u.setAttribute("placeholder",e.inputPlaceholder),(0,o.addClass)(t,"show-input"),setTimeout(function(){u.focus(),u.addEventListener("keyup",swal.resetInputError)},400)}}();if("object"==typeof h)return h.v}if(e.imageUrl){var p=t.querySelector(".sa-icon.sa-custom");p.style.backgroundImage="url("+e.imageUrl+")",(0,o.show)(p);var f=80,m=80;if(e.imageSize){var v=e.imageSize.toString().split("x"),g=v[0],y=v[1];g&&y?(f=g,m=y):logStr("Parameter imageSize expects value with format WIDTHxHEIGHT, got "+e.imageSize)}p.setAttribute("style",p.getAttribute("style")+"width:"+f+"px; height:"+m+"px")}t.setAttribute("data-has-cancel-button",e.showCancelButton),e.showCancelButton?u.style.display="inline-block":(0,o.hide)(u),t.setAttribute("data-has-confirm-button",e.showConfirmButton),e.showConfirmButton?c.style.display="inline-block":(0,o.hide)(c),e.cancelButtonText&&(u.innerHTML=(0,o.escapeHtml)(e.cancelButtonText)),e.confirmButtonText&&(c.innerHTML=(0,o.escapeHtml)(e.confirmButtonText)),e.confirmButtonColor&&(c.style.backgroundColor=e.confirmButtonColor,c.style.borderLeftColor=e.confirmLoadingButtonColor,c.style.borderRightColor=e.confirmLoadingButtonColor,(0,s.setFocusStyle)(c,e.confirmButtonColor)),t.setAttribute("data-allow-outside-click",e.allowOutsideClick);var w=!!e.doneFunction;t.setAttribute("data-has-done-function",w),e.animation?"string"==typeof e.animation?t.setAttribute("data-animation",e.animation):t.setAttribute("data-animation","pop"):t.setAttribute("data-animation","none"),t.setAttribute("data-timer",e.timer)},t.exports=n.default},{"./handle-dom":4,"./handle-swal-dom":6,"./utils":9}],9:[function(t,i,n){Object.defineProperty(n,"__esModule",{value:!0});n.extend=function(e,t){for(var i in t)t.hasOwnProperty(i)&&(e[i]=t[i]);return e},n.hexToRgb=function(e){var t=/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(e);return t?parseInt(t[1],16)+", "+parseInt(t[2],16)+", "+parseInt(t[3],16):null},n.isIE8=function(){return e.attachEvent&&!e.addEventListener},n.logStr=function(t){void 0!==e&&e.console&&e.console.log("SweetAlert: "+t)},n.colorLuminance=function(e,t){(e=String(e).replace(/[^0-9a-f]/gi,"")).length<6&&(e=e[0]+e[0]+e[1]+e[1]+e[2]+e[2]),t=t||0;var i,n,r="#";for(n=0;3>n;n++)i=parseInt(e.substr(2*n,2),16),i=Math.round(Math.min(Math.max(0,i+i*t),255)).toString(16),r+=("00"+i).substr(i.length);return r}},{}]},{},[1]),"function"==typeof define&&define.amd?define(function(){return sweetAlert}):"undefined"!=typeof module&&module.exports&&(module.exports=sweetAlert)}(window,document),function(e){var t;if("function"==typeof define&&define.amd&&(define(e),t=!0),"object"==typeof exports&&(module.exports=e(),t=!0),!t){var i=window.Cookies,n=window.Cookies=e();n.noConflict=function(){return window.Cookies=i,n}}}(function(){function e(){for(var e=0,t={};e<arguments.length;e++){var i=arguments[e];for(var n in i)t[n]=i[n]}return t}function t(e){return e.replace(/(%[0-9A-Z]{2})+/g,decodeURIComponent)}return function i(n){function r(){}function s(t,i,s){if("undefined"!=typeof document){"number"==typeof(s=e({path:"/"},r.defaults,s)).expires&&(s.expires=new Date(1*new Date+864e5*s.expires)),s.expires=s.expires?s.expires.toUTCString():"";try{var o=JSON.stringify(i);/^[\{\[]/.test(o)&&(i=o)}catch(e){}i=n.write?n.write(i,t):encodeURIComponent(String(i)).replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g,decodeURIComponent),t=encodeURIComponent(String(t)).replace(/%(23|24|26|2B|5E|60|7C)/g,decodeURIComponent).replace(/[\(\)]/g,escape);var a="";for(var l in s)s[l]&&(a+="; "+l,!0!==s[l]&&(a+="="+s[l].split(";")[0]));return document.cookie=t+"="+i+a}}function o(e,i){if("undefined"!=typeof document){for(var r={},s=document.cookie?document.cookie.split("; "):[],o=0;o<s.length;o++){var a=s[o].split("="),l=a.slice(1).join("=");i||'"'!==l.charAt(0)||(l=l.slice(1,-1));try{var u=t(a[0]);if(l=(n.read||n)(l,u)||t(l),i)try{l=JSON.parse(l)}catch(e){}if(r[u]=l,e===u)break}catch(e){}}return e?r[e]:r}}return r.set=s,r.get=function(e){return o(e,!1)},r.getJSON=function(e){return o(e,!0)},r.remove=function(t,i){s(t,"",e(i,{expires:-1}))},r.defaults={},r.withConverter=i,r}(function(){})}),function(e,t){"function"==typeof define&&define.amd?define(["jquery"],t):"object"==typeof exports?module.exports=t(require("jquery")):e.Dropify=t(e.jQuery)}(this,function(e){var t="dropify";function i(t,i){if(window.File&&window.FileReader&&window.FileList&&window.Blob){this.element=t,this.input=e(this.element),this.wrapper=null,this.preview=null,this.filenameWrapper=null,this.settings=e.extend(!0,{defaultFile:"",maxFileSize:0,minWidth:0,maxWidth:0,minHeight:0,maxHeight:0,showRemove:!0,showLoader:!0,showErrors:!0,errorsPosition:"overlay",allowedFormats:["portrait","square","landscape"],messages:{default:"Drag and drop a file here or click",replace:"Drag and drop or click to replace",remove:"Remove",error:"Ooops, something wrong appended."},error:{fileSize:"The file size is too big ({{ value }} max).",minWidth:"The image width is too small ({{ value }}}px min).",maxWidth:"The image width is too big ({{ value }}}px max).",minHeight:"The image height is too small ({{ value }}}px min).",maxHeight:"The image height is too big ({{ value }}px max).",imageFormat:"The image format is not allowed ({{ value }} only)."},tpl:{wrap:'<div class="dropify-wrapper"></div>',loader:'<div class="dropify-loader"></div>',message:'<div class="dropify-message"><span class="file-icon" /> <p>{{ default }}</p></div>',preview:'<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-infos-message">{{ replace }}</p></div></div></div>',filename:'<p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p>',clearButton:'<button type="button" class="dropify-clear">{{ remove }}</button>',errorLine:'<p class="dropify-error">{{ error }}</p>',errorsContainer:'<div class="dropify-errors-container"><ul></ul></div>'}},i,this.input.data()),this.imgFileExtensions=["png","jpg","jpeg","gif","bmp"],this.errorsEvent=e.Event("dropify.errors"),this.isDisabled=!1,this.isInit=!1,this.file={object:null,name:null,size:null,width:null,height:null,type:null},Array.isArray(this.settings.allowedFormats)||(this.settings.allowedFormats=this.settings.allowedFormats.split(" ")),this.onChange=this.onChange.bind(this),this.clearElement=this.clearElement.bind(this),this.onFileReady=this.onFileReady.bind(this),this.translateMessages(),this.createElements(),this.setContainerSize(),this.errorsEvent.errors=[],this.input.on("change",this.onChange)}}return i.prototype.onChange=function(){this.resetPreview(),this.readFile(this.element)},i.prototype.createElements=function(){this.isInit=!0,this.input.wrap(e(this.settings.tpl.wrap)),this.wrapper=this.input.parent();var t=e(this.settings.tpl.message).insertBefore(this.input);e(this.settings.tpl.errorLine).appendTo(t),!0===this.isTouchDevice()&&this.wrapper.addClass("touch-fallback"),this.input.attr("disabled")&&(this.isDisabled=!0,this.wrapper.addClass("disabled")),!0===this.settings.showLoader&&(this.loader=e(this.settings.tpl.loader),this.loader.insertBefore(this.input)),this.preview=e(this.settings.tpl.preview),this.preview.insertAfter(this.input),!1===this.isDisabled&&!0===this.settings.showRemove&&(this.clearButton=e(this.settings.tpl.clearButton),this.clearButton.insertAfter(this.input),this.clearButton.on("click",this.clearElement)),this.filenameWrapper=e(this.settings.tpl.filename),this.filenameWrapper.prependTo(this.preview.find(".dropify-infos-inner")),!0===this.settings.showErrors&&(this.errorsContainer=e(this.settings.tpl.errorsContainer),"outside"===this.settings.errorsPosition?this.errorsContainer.insertAfter(this.wrapper):this.errorsContainer.insertBefore(this.input));var i=this.settings.defaultFile||"";""!=i.trim()&&(this.file.name=this.cleanFilename(i),this.setPreview(i))},i.prototype.readFile=function(t){if(t.files&&t.files[0]){var i=new FileReader,n=new Image,r=t.files[0],s=null,o=this,a=e.Event("dropify.fileReady");this.clearErrors(),this.showLoader(),this.setFileInformations(r),i.readAsDataURL(r),this.errorsEvent.errors=[],this.checkFileSize(),i.onload=function(e){s=e.target.result,this.isImage()?(n.src=e.target.result,n.onload=function(){o.setFileDimensions(this.width,this.height),o.validateImage(),o.input.trigger(a,[s])}):this.input.trigger(a,[s])}.bind(this),this.input.on("dropify.fileReady",this.onFileReady)}},i.prototype.onFileReady=function(e,t){if(this.input.off("dropify.fileReady",this.onFileReady),0===this.errorsEvent.errors.length)this.setPreview(t,this.file.name);else{this.input.trigger(this.errorsEvent,[this]);for(var i=this.errorsEvent.errors.length-1;i>=0;i--){var n=this.errorsEvent.errors[i].namespace.split(".").pop();this.showError(n)}if(void 0!==this.errorsContainer){this.errorsContainer.addClass("visible");var r=this.errorsContainer;setTimeout(function(){r.removeClass("visible")},1e3)}this.wrapper.addClass("has-error"),this.resetPreview(),this.clearElement()}},i.prototype.setFileInformations=function(e){this.file.object=e,this.file.name=e.name,this.file.size=e.size,this.file.type=e.type,this.file.width=null,this.file.height=null},i.prototype.setFileDimensions=function(e,t){this.file.width=e,this.file.height=t},i.prototype.setPreview=function(t){this.wrapper.removeClass("has-error").addClass("has-preview"),this.filenameWrapper.children(".dropify-filename-inner").html(this.file.name);var i=this.preview.children(".dropify-render");if(this.hideLoader(),!0===this.isImage()){var n=e("<img />").attr("src",t);this.settings.height&&n.css("max-height",this.settings.height),n.appendTo(i)}else e("<i />").attr("class","dropify-font-file").appendTo(i),e('<span class="dropify-extension" />').html(this.getFileType()).appendTo(i);this.preview.fadeIn()},i.prototype.resetPreview=function(){this.wrapper.removeClass("has-preview");var e=this.preview.children(".dropify-render");e.find(".dropify-extension").remove(),e.find("i").remove(),e.find("img").remove(),this.preview.hide(),this.hideLoader()},i.prototype.cleanFilename=function(e){var t=e.split("\\").pop();return t==e&&(t=e.split("/").pop()),""!=e?t:""},i.prototype.clearElement=function(){if(0===this.errorsEvent.errors.length){var t=e.Event("dropify.beforeClear");this.input.trigger(t,[this]),!1!==t.result&&(this.resetFile(),this.input.val(""),this.resetPreview(),this.input.trigger(e.Event("dropify.afterClear"),[this]))}else this.resetFile(),this.input.val(""),this.resetPreview()},i.prototype.resetFile=function(){this.file.object=null,this.file.name=null,this.file.size=null,this.file.type=null,this.file.width=null,this.file.height=null},i.prototype.setContainerSize=function(){this.settings.height&&this.wrapper.height(this.settings.height)},i.prototype.isTouchDevice=function(){return"ontouchstart"in window||navigator.MaxTouchPoints>0||navigator.msMaxTouchPoints>0},i.prototype.getFileType=function(){return this.file.name.split(".").pop().toLowerCase()},i.prototype.isImage=function(){return"-1"!=this.imgFileExtensions.indexOf(this.getFileType())},i.prototype.translateMessages=function(){for(var e in this.settings.tpl)for(var t in this.settings.messages)this.settings.tpl[e]=this.settings.tpl[e].replace("{{ "+t+" }}",this.settings.messages[t])},i.prototype.checkFileSize=function(){0!==this.maxFileSizeToByte()&&this.file.size>this.maxFileSizeToByte()&&this.pushError("fileSize")},i.prototype.maxFileSizeToByte=function(){var e=0;if(0!==this.settings.maxFileSize){var t=this.settings.maxFileSize.slice(-1).toUpperCase();"K"===t?e=1024*parseFloat(this.settings.maxFileSize):"M"===t?e=1048576*parseFloat(this.settings.maxFileSize):"G"===t&&(e=1073741824*parseFloat(this.settings.maxFileSize))}return e},i.prototype.validateImage=function(){0!==this.settings.minWidth&&this.settings.minWidth>=this.file.width&&this.pushError("minWidth"),0!==this.settings.maxWidth&&this.settings.maxWidth<=this.file.width&&this.pushError("maxWidth"),0!==this.settings.minHeight&&this.settings.minHeight>=this.file.height&&this.pushError("minHeight"),0!==this.settings.maxHeight&&this.settings.maxHeight<=this.file.height&&this.pushError("maxHeight"),"-1"==this.settings.allowedFormats.indexOf(this.getImageFormat())&&this.pushError("imageFormat")},i.prototype.getImageFormat=function(){return this.file.width==this.file.height?"square":this.file.width<this.file.height?"portrait":this.file.width>this.file.height?"landscape":void 0},i.prototype.pushError=function(t){var i=e.Event("dropify.error."+t);this.errorsEvent.errors.push(i),this.input.trigger(i,[this])},i.prototype.clearErrors=function(){void 0!==this.errorsContainer&&this.errorsContainer.children("ul").html("")},i.prototype.showError=function(e){void 0!==this.errorsContainer&&this.errorsContainer.children("ul").append("<li>"+this.getError(e)+"</li>")},i.prototype.getError=function(e){var t=this.settings.error[e],i="";return"fileSize"===e?i=this.settings.maxFileSize:"minWidth"===e?i=this.settings.minWidth:"maxWidth"===e?i=this.settings.maxWidth:"minHeight"===e?i=this.settings.minHeight:"maxHeight"===e?i=this.settings.maxHeight:"imageFormat"===e&&(i=this.settings.allowedFormats.join(" ")),""!==i?t.replace("{{ value }}",i):t},i.prototype.showLoader=function(){void 0!==this.loader&&this.loader.show()},i.prototype.hideLoader=function(){void 0!==this.loader&&this.loader.hide()},i.prototype.destroy=function(){this.input.siblings().remove(),this.input.unwrap(),this.isInit=!1},i.prototype.init=function(){this.createElements()},i.prototype.isDropified=function(){return this.isInit},e.fn[t]=function(n){return this.each(function(){e.data(this,t)||e.data(this,t,new i(this,n))}),this},i}),function(e,t){"function"==typeof define&&define.amd?define(["exports"],t):"object"==typeof exports&&"string"!=typeof exports.nodeName?t(exports):t(e.commonJsStrict={})}(this,function(e){"function"!=typeof Promise&&function(e){function t(e,t){return function(){e.apply(t,arguments)}}function i(e){if("object"!=typeof this)throw new TypeError("Promises must be constructed via new");if("function"!=typeof e)throw new TypeError("not a function");this._state=null,this._value=null,this._deferreds=[],a(e,t(r,this),t(s,this))}function n(e){var t=this;return null===this._state?void this._deferreds.push(e):void u(function(){var i=t._state?e.onFulfilled:e.onRejected;if(null!==i){var n;try{n=i(t._value)}catch(t){return void e.reject(t)}e.resolve(n)}else(t._state?e.resolve:e.reject)(t._value)})}function r(e){try{if(e===this)throw new TypeError("A promise cannot be resolved with itself.");if(e&&("object"==typeof e||"function"==typeof e)){var i=e.then;if("function"==typeof i)return void a(t(i,e),t(r,this),t(s,this))}this._state=!0,this._value=e,o.call(this)}catch(e){s.call(this,e)}}function s(e){this._state=!1,this._value=e,o.call(this)}function o(){for(var e=0,t=this._deferreds.length;t>e;e++)n.call(this,this._deferreds[e]);this._deferreds=null}function a(e,t,i){var n=!1;try{e(function(e){n||(n=!0,t(e))},function(e){n||(n=!0,i(e))})}catch(e){if(n)return;n=!0,i(e)}}var l=setTimeout,u="function"==typeof setImmediate&&setImmediate||function(e){l(e,1)},c=Array.isArray||function(e){return"[object Array]"===Object.prototype.toString.call(e)};i.prototype.catch=function(e){return this.then(null,e)},i.prototype.then=function(e,t){var r=this;return new i(function(i,s){n.call(r,new function(e,t,i,n){this.onFulfilled="function"==typeof e?e:null,this.onRejected="function"==typeof t?t:null,this.resolve=i,this.reject=n}(e,t,i,s))})},i.all=function(){var e=Array.prototype.slice.call(1===arguments.length&&c(arguments[0])?arguments[0]:arguments);return new i(function(t,i){function n(s,o){try{if(o&&("object"==typeof o||"function"==typeof o)){var a=o.then;if("function"==typeof a)return void a.call(o,function(e){n(s,e)},i)}e[s]=o,0==--r&&t(e)}catch(e){i(e)}}if(0===e.length)return t([]);for(var r=e.length,s=0;s<e.length;s++)n(s,e[s])})},i.resolve=function(e){return e&&"object"==typeof e&&e.constructor===i?e:new i(function(t){t(e)})},i.reject=function(e){return new i(function(t,i){i(e)})},i.race=function(e){return new i(function(t,i){for(var n=0,r=e.length;r>n;n++)e[n].then(t,i)})},i._setImmediateFn=function(e){u=e},"undefined"!=typeof module&&module.exports?module.exports=i:e.Promise||(e.Promise=i)}(this),"function"!=typeof window.CustomEvent&&function(){function e(e,t){t=t||{bubbles:!1,cancelable:!1,detail:void 0};var i=document.createEvent("CustomEvent");return i.initCustomEvent(e,t.bubbles,t.cancelable,t.detail),i}e.prototype=window.Event.prototype,window.CustomEvent=e}(),HTMLCanvasElement.prototype.toBlob||Object.defineProperty(HTMLCanvasElement.prototype,"toBlob",{value:function(e,t,i){for(var n=atob(this.toDataURL(t,i).split(",")[1]),r=n.length,s=new Uint8Array(r),o=0;o<r;o++)s[o]=n.charCodeAt(o);e(new Blob([s],{type:t||"image/png"}))}});var t,i,n,r=["Webkit","Moz","ms"],s=document.createElement("div").style,o=[1,8,3,6],a=[2,7,4,5];function l(e){if(e in s)return e;for(var t=e[0].toUpperCase()+e.slice(1),i=r.length;i--;)if((e=r[i]+t)in s)return e}function u(e,t){e=e||{};for(var i in t)t[i]&&t[i].constructor&&t[i].constructor===Object?(e[i]=e[i]||{},u(e[i],t[i])):e[i]=t[i];return e}function c(e){return u({},e)}function d(e){if("createEvent"in document){var t=document.createEvent("HTMLEvents");t.initEvent("change",!1,!0),e.dispatchEvent(t)}else e.fireEvent("onchange")}function h(e,t,i){if("string"==typeof t){var n=t;(t={})[n]=i}for(var r in t)e.style[r]=t[r]}function p(e,t){e.classList?e.classList.add(t):e.className+=" "+t}function f(e,t){for(var i in t)e.setAttribute(i,t[i])}function m(e){return parseInt(e,10)}function v(e,t){var i=e.naturalWidth,n=e.naturalHeight,r=t||b(e);if(r&&r>=5){var s=i;i=n,n=s}return{width:i,height:n}}i=l("transform"),t=l("transformOrigin"),n=l("userSelect");var g={translate3d:{suffix:", 0px"},translate:{suffix:""}},y=function(e,t,i){this.x=parseFloat(e),this.y=parseFloat(t),this.scale=parseFloat(i)};y.parse=function(e){return e.style?y.parse(e.style[i]):e.indexOf("matrix")>-1||e.indexOf("none")>-1?y.fromMatrix(e):y.fromString(e)},y.fromMatrix=function(e){var t=e.substring(7).split(",");return t.length&&"none"!==e||(t=[1,0,0,1,0,0]),new y(m(t[4]),m(t[5]),parseFloat(t[0]))},y.fromString=function(e){var t=e.split(") "),i=t[0].substring(U.globals.translate.length+1).split(","),n=t.length>1?t[1].substring(6):1,r=i.length>1?i[0]:0,s=i.length>1?i[1]:0;return new y(r,s,n)},y.prototype.toString=function(){var e=g[U.globals.translate].suffix||"";return U.globals.translate+"("+this.x+"px, "+this.y+"px"+e+") scale("+this.scale+")"};var w=function(e){if(!e||!e.style[t])return this.x=0,void(this.y=0);var i=e.style[t].split(" ");this.x=parseFloat(i[0]),this.y=parseFloat(i[1])};function b(e){return e.exifdata?e.exifdata.Orientation:1}function C(e,t,i){var n=t.width,r=t.height,s=e.getContext("2d");switch(e.width=t.width,e.height=t.height,s.save(),i){case 2:s.translate(n,0),s.scale(-1,1);break;case 3:s.translate(n,r),s.rotate(180*Math.PI/180);break;case 4:s.translate(0,r),s.scale(1,-1);break;case 5:e.width=r,e.height=n,s.rotate(90*Math.PI/180),s.scale(1,-1);break;case 6:e.width=r,e.height=n,s.rotate(90*Math.PI/180),s.translate(0,-r);break;case 7:e.width=r,e.height=n,s.rotate(-90*Math.PI/180),s.translate(-n,r),s.scale(1,-1);break;case 8:e.width=r,e.height=n,s.translate(0,n),s.rotate(-90*Math.PI/180)}s.drawImage(t,0,0,n,r),s.restore()}function x(){var e,t,r,s,o,a=this,l=a.options.viewport.type?"cr-vp-"+a.options.viewport.type:null;a.options.useCanvas=a.options.enableOrientation||E.call(a),a.data={},a.elements={},e=a.elements.boundary=document.createElement("div"),t=a.elements.viewport=document.createElement("div"),a.elements.img=document.createElement("img"),r=a.elements.overlay=document.createElement("div"),a.options.useCanvas?(a.elements.canvas=document.createElement("canvas"),a.elements.preview=a.elements.canvas):a.elements.preview=a.elements.img,p(e,"cr-boundary"),e.setAttribute("aria-dropeffect","none"),s=a.options.boundary.width,o=a.options.boundary.height,h(e,{width:s+(isNaN(s)?"":"px"),height:o+(isNaN(o)?"":"px")}),p(t,"cr-viewport"),l&&p(t,l),h(t,{width:a.options.viewport.width+"px",height:a.options.viewport.height+"px"}),t.setAttribute("tabindex",0),p(a.elements.preview,"cr-image"),f(a.elements.preview,{alt:"preview","aria-grabbed":"false"}),p(r,"cr-overlay"),a.element.appendChild(e),e.appendChild(a.elements.preview),e.appendChild(t),e.appendChild(r),p(a.element,"croppie-container"),a.options.customClass&&p(a.element,a.options.customClass),function(){var e,t,r,s,o,a=this,l=!1;function u(e,t){var i=a.elements.preview.getBoundingClientRect(),n=o.y+t,r=o.x+e;a.options.enforceBoundary?(s.top>i.top+t&&s.bottom<i.bottom+t&&(o.y=n),s.left>i.left+e&&s.right<i.right+e&&(o.x=r)):(o.y=n,o.x=r)}function c(e){a.elements.preview.setAttribute("aria-grabbed",e),a.elements.boundary.setAttribute("aria-dropeffect",e?"move":"none")}function p(i){if((void 0===i.button||0===i.button)&&(i.preventDefault(),!l)){if(l=!0,e=i.pageX,t=i.pageY,i.touches){var r=i.touches[0];e=r.pageX,t=r.pageY}c(l),o=y.parse(a.elements.preview),window.addEventListener("mousemove",f),window.addEventListener("touchmove",f),window.addEventListener("mouseup",m),window.addEventListener("touchend",m),document.body.style[n]="none",s=a.elements.viewport.getBoundingClientRect()}}function f(n){n.preventDefault();var s=n.pageX,l=n.pageY;if(n.touches){var c=n.touches[0];s=c.pageX,l=c.pageY}var p=s-e,f=l-t,m={};if("touchmove"===n.type&&n.touches.length>1){var v=n.touches[0],g=n.touches[1],y=Math.sqrt((v.pageX-g.pageX)*(v.pageX-g.pageX)+(v.pageY-g.pageY)*(v.pageY-g.pageY));r||(r=y/a._currentZoom);var w=y/r;return _.call(a,w),void d(a.elements.zoomer)}u(p,f),m[i]=o.toString(),h(a.elements.preview,m),S.call(a),t=l,e=s}function m(){c(l=!1),window.removeEventListener("mousemove",f),window.removeEventListener("touchmove",f),window.removeEventListener("mouseup",m),window.removeEventListener("touchend",m),document.body.style[n]="",k.call(a),P.call(a),r=0}a.elements.overlay.addEventListener("mousedown",p),a.elements.viewport.addEventListener("keydown",function(e){var t=37,l=38,c=39,d=40;if(!e.shiftKey||e.keyCode!==l&&e.keyCode!==d){if(a.options.enableKeyMovement&&e.keyCode>=37&&e.keyCode<=40){e.preventDefault();var p=function(e){switch(e){case t:return[1,0];case l:return[0,1];case c:return[-1,0];case d:return[0,-1]}}(e.keyCode);o=y.parse(a.elements.preview),document.body.style[n]="none",s=a.elements.viewport.getBoundingClientRect(),function(e){var t=e[0],s=e[1],l={};u(t,s),l[i]=o.toString(),h(a.elements.preview,l),S.call(a),document.body.style[n]="",k.call(a),P.call(a),r=0}(p)}}else{var f=0;f=e.keyCode===l?parseFloat(a.elements.zoomer.value,10)+parseFloat(a.elements.zoomer.step,10):parseFloat(a.elements.zoomer.value,10)-parseFloat(a.elements.zoomer.step,10),a.setZoom(f)}}),a.elements.overlay.addEventListener("touchstart",p)}.call(this),a.options.enableZoom&&function(){var e=this,t=e.elements.zoomerWrap=document.createElement("div"),i=e.elements.zoomer=document.createElement("input");function n(){F.call(e,{value:parseFloat(i.value),origin:new w(e.elements.preview),viewportRect:e.elements.viewport.getBoundingClientRect(),transform:y.parse(e.elements.preview)})}function r(t){var i,r;if("ctrl"===e.options.mouseWheelZoom&&!0!==t.ctrlKey)return 0;i=t.wheelDelta?t.wheelDelta/1200:t.deltaY?t.deltaY/1060:t.detail?t.detail/-60:0,r=e._currentZoom+i*e._currentZoom,t.preventDefault(),_.call(e,r),n.call(e)}p(t,"cr-slider-wrap"),p(i,"cr-slider"),i.type="range",i.step="0.0001",i.value=1,i.style.display=e.options.showZoomer?"":"none",i.setAttribute("aria-label","zoom"),e.element.appendChild(t),t.appendChild(i),e._currentZoom=1,e.elements.zoomer.addEventListener("input",n),e.elements.zoomer.addEventListener("change",n),e.options.mouseWheelZoom&&(e.elements.boundary.addEventListener("mousewheel",r),e.elements.boundary.addEventListener("DOMMouseScroll",r))}.call(a),a.options.enableResize&&function(){var e,t,i,r,s,o,a,l=this,u=document.createElement("div"),c=!1,d=50;p(u,"cr-resizer"),h(u,{width:this.options.viewport.width+"px",height:this.options.viewport.height+"px"}),this.options.resizeControls.height&&(p(o=document.createElement("div"),"cr-resizer-vertical"),u.appendChild(o));this.options.resizeControls.width&&(p(a=document.createElement("div"),"cr-resizer-horisontal"),u.appendChild(a));function f(o){if((void 0===o.button||0===o.button)&&(o.preventDefault(),!c)){var a=l.elements.overlay.getBoundingClientRect();if(c=!0,t=o.pageX,i=o.pageY,e=-1!==o.currentTarget.className.indexOf("vertical")?"v":"h",r=a.width,s=a.height,o.touches){var u=o.touches[0];t=u.pageX,i=u.pageY}window.addEventListener("mousemove",m),window.addEventListener("touchmove",m),window.addEventListener("mouseup",v),window.addEventListener("touchend",v),document.body.style[n]="none"}}function m(n){var o=n.pageX,a=n.pageY;if(n.preventDefault(),n.touches){var c=n.touches[0];o=c.pageX,a=c.pageY}var p=o-t,f=a-i,m=l.options.viewport.height+f,v=l.options.viewport.width+p;"v"===e&&m>=d&&m<=s?(h(u,{height:m+"px"}),l.options.boundary.height+=f,h(l.elements.boundary,{height:l.options.boundary.height+"px"}),l.options.viewport.height+=f,h(l.elements.viewport,{height:l.options.viewport.height+"px"})):"h"===e&&v>=d&&v<=r&&(h(u,{width:v+"px"}),l.options.boundary.width+=p,h(l.elements.boundary,{width:l.options.boundary.width+"px"}),l.options.viewport.width+=p,h(l.elements.viewport,{width:l.options.viewport.width+"px"})),S.call(l),j.call(l),k.call(l),P.call(l),i=a,t=o}function v(){c=!1,window.removeEventListener("mousemove",m),window.removeEventListener("touchmove",m),window.removeEventListener("mouseup",v),window.removeEventListener("touchend",v),document.body.style[n]=""}o&&(o.addEventListener("mousedown",f),o.addEventListener("touchstart",f));a&&(a.addEventListener("mousedown",f),a.addEventListener("touchstart",f));this.elements.boundary.appendChild(u)}.call(a)}function E(){return this.options.enableExif&&window.EXIF}function _(e){if(this.options.enableZoom){var t=this.elements.zoomer,i=B(e,4);t.value=Math.max(t.min,Math.min(t.max,i))}}function F(e){var n=this,r=e?e.transform:y.parse(n.elements.preview),s=e?e.viewportRect:n.elements.viewport.getBoundingClientRect(),o=e?e.origin:new w(n.elements.preview);function a(){var e={};e[i]=r.toString(),e[t]=o.toString(),h(n.elements.preview,e)}if(n._currentZoom=e?e.value:n._currentZoom,r.scale=n._currentZoom,n.elements.zoomer.setAttribute("aria-valuenow",n._currentZoom),a(),n.options.enforceBoundary){var l=function(e){var t=this._currentZoom,i=e.width,n=e.height,r=this.elements.boundary.clientWidth/2,s=this.elements.boundary.clientHeight/2,o=this.elements.preview.getBoundingClientRect(),a=o.width,l=o.height,u=i/2,c=n/2,d=-1*(u/t-r),h=-1*(c/t-s),p=1/t*u,f=1/t*c;return{translate:{maxX:d,minX:d-(a*(1/t)-i*(1/t)),maxY:h,minY:h-(l*(1/t)-n*(1/t))},origin:{maxX:a*(1/t)-p,minX:p,maxY:l*(1/t)-f,minY:f}}}.call(n,s),u=l.translate,c=l.origin;r.x>=u.maxX&&(o.x=c.minX,r.x=u.maxX),r.x<=u.minX&&(o.x=c.maxX,r.x=u.minX),r.y>=u.maxY&&(o.y=c.minY,r.y=u.maxY),r.y<=u.minY&&(o.y=c.maxY,r.y=u.minY)}a(),M.call(n),P.call(n)}function k(){var e=this,n=e._currentZoom,r=e.elements.preview.getBoundingClientRect(),s=e.elements.viewport.getBoundingClientRect(),o=y.parse(e.elements.preview.style[i]),a=new w(e.elements.preview),l=s.top-r.top+s.height/2,u=s.left-r.left+s.width/2,c={},d={};c.y=l/n,c.x=u/n,d.y=(c.y-a.y)*(1-n),d.x=(c.x-a.x)*(1-n),o.x-=d.x,o.y-=d.y;var p={};p[t]=c.x+"px "+c.y+"px",p[i]=o.toString(),h(e.elements.preview,p)}function S(){if(this.elements){var e=this.elements.boundary.getBoundingClientRect(),t=this.elements.preview.getBoundingClientRect();h(this.elements.overlay,{width:t.width+"px",height:t.height+"px",top:t.top-e.top+"px",left:t.left-e.left+"px"})}}w.prototype.toString=function(){return this.x+"px "+this.y+"px"};var T,A,I,O,M=(T=S,A=500,function(){var e=this,t=arguments,i=I&&!O;clearTimeout(O),O=setTimeout(function(){O=null,I||T.apply(e,t)},A),i&&T.apply(e,t)});function P(){var e,t=this,i=t.get();D.call(t)&&(t.options.update.call(t,i),t.$&&"undefined"==typeof Prototype?t.$(t.element).trigger("update.croppie",i):(window.CustomEvent?e=new CustomEvent("update",{detail:i}):(e=document.createEvent("CustomEvent")).initCustomEvent("update",!0,!0,i),t.element.dispatchEvent(e)))}function D(){return this.elements.preview.offsetHeight>0&&this.elements.preview.offsetWidth>0}function q(){var e,n=this,r={},s=n.elements.preview,o=new y(0,0,1),a=new w;D.call(n)&&!n.data.bound&&(n.data.bound=!0,r[i]=o.toString(),r[t]=a.toString(),r.opacity=1,h(s,r),e=n.elements.preview.getBoundingClientRect(),n._originalImageWidth=e.width,n._originalImageHeight=e.height,n.data.orientation=b(n.elements.img),n.options.enableZoom?j.call(n,!0):n._currentZoom=1,o.scale=n._currentZoom,r[i]=o.toString(),h(s,r),n.data.points.length?function(e){if(4!==e.length)throw"Croppie - Invalid number of points supplied: "+e;var n=this,r=e[2]-e[0],s=n.elements.viewport.getBoundingClientRect(),o=n.elements.boundary.getBoundingClientRect(),a={left:s.left-o.left,top:s.top-o.top},l=s.width/r,u=e[1],c=e[0],d=-1*e[1]+a.top,p=-1*e[0]+a.left,f={};f[t]=c+"px "+u+"px",f[i]=new y(p,d,l).toString(),h(n.elements.preview,f),_.call(n,l),n._currentZoom=l}.call(n,n.data.points):function(){var e=this,t=e.elements.preview.getBoundingClientRect(),n=e.elements.viewport.getBoundingClientRect(),r=e.elements.boundary.getBoundingClientRect(),s=n.left-r.left,o=n.top-r.top,a=s-(t.width-n.width)/2,l=o-(t.height-n.height)/2,u=new y(a,l,e._currentZoom);h(e.elements.preview,i,u.toString())}.call(n),k.call(n),S.call(n))}function j(e){var t,i,n,r,s=this,o=0,a=s.options.maxZoom||1.5,l=s.elements.zoomer,u=parseFloat(l.value),c=s.elements.boundary.getBoundingClientRect(),h=v(s.elements.img,s.data.orientation),p=s.elements.viewport.getBoundingClientRect();s.options.enforceBoundary&&(n=p.width/h.width,r=p.height/h.height,o=Math.max(n,r)),o>=a&&(a=o+1),l.min=B(o,4),l.max=B(a,4),!e&&(u<l.min||u>l.max)?_.call(s,u<l.min?l.min:l.max):e&&(i=Math.max(c.width/h.width,c.height/h.height),t=null!==s.data.boundZoom?s.data.boundZoom:i,_.call(s,t)),d(l)}function L(e){var t=e.points,i=m(t[0]),n=m(t[1]),r=m(t[2])-i,s=m(t[3])-n,o=e.circle,a=document.createElement("canvas"),l=a.getContext("2d"),u=e.outputWidth||r,c=e.outputHeight||s;e.outputWidth&&e.outputHeight;return a.width=u,a.height=c,e.backgroundColor&&(l.fillStyle=e.backgroundColor,l.fillRect(0,0,u,c)),!1!==this.options.enforceBoundary&&(r=Math.min(r,this._originalImageWidth),s=Math.min(s,this._originalImageHeight)),l.drawImage(this.elements.preview,i,n,r,s,0,0,u,c),o&&(l.fillStyle="#fff",l.globalCompositeOperation="destination-in",l.beginPath(),l.arc(a.width/2,a.height/2,a.width/2,0,2*Math.PI,!0),l.closePath(),l.fill()),a}function R(e,t){var i,n,r,s,o=this,a=[],l=null,u=E.call(o);if("string"==typeof e)i=e,e={};else if(Array.isArray(e))a=e.slice();else{if(void 0===e&&o.data.url)return q.call(o),P.call(o),null;i=e.url,a=e.points||[],l=void 0===e.zoom?null:e.zoom}return o.data.bound=!1,o.data.url=i||o.data.url,o.data.boundZoom=l,(n=i,r=u,s=new Image,s.style.opacity=0,new Promise(function(e){function t(){s.style.opacity=1,setTimeout(function(){e(s)},1)}s.removeAttribute("crossOrigin"),n.match(/^https?:\/\/|^\/\//)&&s.setAttribute("crossOrigin","anonymous"),s.onload=function(){r?EXIF.getData(s,function(){t()}):t()},s.src=n})).then(function(i){if(function(e){this.elements.img.parentNode&&(Array.prototype.forEach.call(this.elements.img.classList,function(t){e.classList.add(t)}),this.elements.img.parentNode.replaceChild(e,this.elements.img),this.elements.preview=e),this.elements.img=e}.call(o,i),a.length)o.options.relative&&(a=[a[0]*i.naturalWidth/100,a[1]*i.naturalHeight/100,a[2]*i.naturalWidth/100,a[3]*i.naturalHeight/100]);else{var n,r,s=v(i),l=o.elements.viewport.getBoundingClientRect(),u=l.width/l.height;s.width/s.height>u?n=(r=s.height)*u:(n=s.width,r=s.height/u);var c=(s.width-n)/2,d=(s.height-r)/2,h=c+n,p=d+r;o.data.points=[c,d,h,p]}o.data.points=a.map(function(e){return parseFloat(e)}),o.options.useCanvas&&function(e){var t=this.elements.canvas,i=this.elements.img,n=t.getContext("2d"),r=E.call(this);e=this.options.enableOrientation&&e,n.clearRect(0,0,t.width,t.height),t.width=i.width,t.height=i.height,r&&!e?C(t,i,m(b(i)||0)):e&&C(t,i,e)}.call(o,e.orientation||1),q.call(o),P.call(o),t&&t()}).catch(function(e){console.error("Croppie:"+e)})}function B(e,t){return parseFloat(e).toFixed(t||0)}function z(){var e=this,t=e.elements.preview.getBoundingClientRect(),i=e.elements.viewport.getBoundingClientRect(),n=i.left-t.left,r=i.top-t.top,s=(i.width-e.elements.viewport.offsetWidth)/2,o=(i.height-e.elements.viewport.offsetHeight)/2,a=n+e.elements.viewport.offsetWidth+s,l=r+e.elements.viewport.offsetHeight+o,u=e._currentZoom;(u===1/0||isNaN(u))&&(u=1);var c=e.options.enforceBoundary?0:Number.NEGATIVE_INFINITY;return n=Math.max(c,n/u),r=Math.max(c,r/u),a=Math.max(c,a/u),l=Math.max(c,l/u),{points:[B(n),B(r),B(a),B(l)],zoom:u,orientation:e.data.orientation}}var N={type:"canvas",format:"png",quality:1},H=["jpeg","webp","png"];function $(e){var t=this,i=z.call(t),n=u(c(N),c(e)),r="string"==typeof e?e:n.type||"base64",s=n.size||"viewport",o=n.format,a=n.quality,l=n.backgroundColor,d="boolean"==typeof n.circle?n.circle:"circle"===t.options.viewport.type,f=t.elements.viewport.getBoundingClientRect(),m=f.width/f.height;return"viewport"===s?(i.outputWidth=f.width,i.outputHeight=f.height):"object"==typeof s&&(s.width&&s.height?(i.outputWidth=s.width,i.outputHeight=s.height):s.width?(i.outputWidth=s.width,i.outputHeight=s.width/m):s.height&&(i.outputWidth=s.height*m,i.outputHeight=s.height)),H.indexOf(o)>-1&&(i.format="image/"+o,i.quality=a),i.circle=d,i.url=t.data.url,i.backgroundColor=l,new Promise(function(e,n){switch(r.toLowerCase()){case"rawcanvas":e(L.call(t,i));break;case"canvas":case"base64":e(function(e){return L.call(this,e).toDataURL(e.format,e.quality)}.call(t,i));break;case"blob":(function(e){var t=this;return new Promise(function(i,n){L.call(t,e).toBlob(function(e){i(e)},e.format,e.quality)})}).call(t,i).then(e);break;default:e(function(e){var t=e.points,i=document.createElement("div"),n=document.createElement("img"),r=t[2]-t[0],s=t[3]-t[1];return p(i,"croppie-result"),i.appendChild(n),h(n,{left:-1*t[0]+"px",top:-1*t[1]+"px"}),n.src=e.url,h(i,{width:r+"px",height:s+"px"}),i}.call(t,i))}})}function V(e){if(!this.options.useCanvas||!this.options.enableOrientation)throw"Croppie: Cannot rotate without enableOrientation && EXIF.js included";var t,i,n,r,s,l=this,u=l.elements.canvas;l.data.orientation=(t=l.data.orientation,i=e,n=o.indexOf(t)>-1?o:a,r=n.indexOf(t),s=i/90%n.length,n[(n.length+r+s%n.length)%n.length]),C(u,l.elements.img,l.data.orientation),j.call(l),F.call(l),copy=null}if(window.jQuery){var W=window.jQuery;W.fn.croppie=function(e){if("string"===typeof e){var t=Array.prototype.slice.call(arguments,1),i=W(this).data("croppie");return"get"===e?i.get():"result"===e?i.result.apply(i,t):"bind"===e?i.bind.apply(i,t):this.each(function(){var i=W(this).data("croppie");if(i){var n=i[e];if(!W.isFunction(n))throw"Croppie "+e+" method not found";n.apply(i,t),"destroy"===e&&W(this).removeData("croppie")}})}return this.each(function(){var t=new U(this,e);t.$=W,W(this).data("croppie",t)})}}function U(e,t){if(e.className.indexOf("croppie-container")>-1)throw new Error("Croppie: Can't initialize croppie more than once");if(this.element=e,this.options=u(c(U.defaults),t),"img"===this.element.tagName.toLowerCase()){var i=this.element;p(i,"cr-original-image"),f(i,{"aria-hidden":"true",alt:""});var n=document.createElement("div");this.element.parentNode.appendChild(n),n.appendChild(i),this.element=n,this.options.url=this.options.url||i.src}if(x.call(this),this.options.url){var r={url:this.options.url,points:this.options.points};delete this.options.url,delete this.options.points,R.call(this,r)}}U.defaults={viewport:{width:100,height:100,type:"square"},boundary:{},orientationControls:{enabled:!0,leftClass:"",rightClass:""},resizeControls:{width:!0,height:!0},customClass:"",showZoomer:!0,enableZoom:!0,enableResize:!1,mouseWheelZoom:!0,enableExif:!1,enforceBoundary:!0,enableOrientation:!1,enableKeyMovement:!0,update:function(){}},U.globals={translate:"translate3d"},u(U.prototype,{bind:function(e,t){return R.call(this,e,t)},get:function(){var e=z.call(this),t=e.points;return this.options.relative&&(t[0]/=this.elements.img.naturalWidth/100,t[1]/=this.elements.img.naturalHeight/100,t[2]/=this.elements.img.naturalWidth/100,t[3]/=this.elements.img.naturalHeight/100),e},result:function(e){return $.call(this,e)},refresh:function(){return function(){q.call(this)}.call(this)},setZoom:function(e){_.call(this,e),d(this.elements.zoomer)},rotate:function(e){V.call(this,e)},destroy:function(){return function(){var e,t,i=this;i.element.removeChild(i.elements.boundary),e=i.element,t="croppie-container",e.classList?e.classList.remove(t):e.className=e.className.replace(t,""),i.options.enableZoom&&i.element.removeChild(i.elements.zoomerWrap),delete i.elements}.call(this)}}),e.Croppie=window.Croppie=U});var _slice=Array.prototype.slice,_slicedToArray=function(){return function(e,t){if(Array.isArray(e))return e;if(Symbol.iterator in Object(e))return function(e,t){var i=[],n=!0,r=!1,s=void 0;try{for(var o,a=e[Symbol.iterator]();!(n=(o=a.next()).done)&&(i.push(o.value),!t||i.length!==t);n=!0);}catch(e){r=!0,s=e}finally{try{!n&&a.return&&a.return()}finally{if(r)throw s}}return i}(e,t);throw new TypeError("Invalid attempt to destructure non-iterable instance")}}(),_extends=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var i=arguments[t];for(var n in i)Object.prototype.hasOwnProperty.call(i,n)&&(e[n]=i[n])}return e};!function(e,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t(require("jquery")):"function"==typeof define&&define.amd?define(["jquery"],t):e.parsley=t(e.jQuery)}(this,function(e){"use strict";function t(e,t){return e.parsleyAdaptedCallback||(e.parsleyAdaptedCallback=function(){var i=Array.prototype.slice.call(arguments,0);i.unshift(this),e.apply(t||S,i)}),e.parsleyAdaptedCallback}function i(e){return 0===e.lastIndexOf(A,0)?e.substr(A.length):e}var n,r=1,s={},o={attr:function(e,t,i){var n,r,s,o=new RegExp("^"+t,"i");if(void 0===i)i={};else for(n in i)i.hasOwnProperty(n)&&delete i[n];if(!e)return i;for(n=(s=e.attributes).length;n--;)r=s[n],r&&r.specified&&o.test(r.name)&&(i[this.camelize(r.name.slice(t.length))]=this.deserializeValue(r.value));return i},checkAttr:function(e,t,i){return e.hasAttribute(t+i)},setAttr:function(e,t,i,n){e.setAttribute(this.dasherize(t+i),String(n))},generateID:function(){return""+r++},deserializeValue:function(t){var i;try{return t?"true"==t||"false"!=t&&("null"==t?null:isNaN(i=Number(t))?/^[\[\{]/.test(t)?e.parseJSON(t):t:i):t}catch(e){return t}},camelize:function(e){return e.replace(/-+(.)?/g,function(e,t){return t?t.toUpperCase():""})},dasherize:function(e){return e.replace(/::/g,"/").replace(/([A-Z]+)([A-Z][a-z])/g,"$1_$2").replace(/([a-z\d])([A-Z])/g,"$1_$2").replace(/_/g,"-").toLowerCase()},warn:function(){var e;window.console&&"function"==typeof window.console.warn&&(e=window.console).warn.apply(e,arguments)},warnOnce:function(e){s[e]||(s[e]=!0,this.warn.apply(this,arguments))},_resetWarnings:function(){s={}},trimString:function(e){return e.replace(/^\s+|\s+$/g,"")},parse:{date:function(e){var t=e.match(/^(\d{4,})-(\d\d)-(\d\d)$/);if(!t)return null;var i=t.map(function(e){return parseInt(e,10)}),n=_slicedToArray(i,4),r=(n[0],n[1]),s=n[2],o=n[3],a=new Date(r,s-1,o);return a.getFullYear()!==r||a.getMonth()+1!==s||a.getDate()!==o?null:a},string:function(e){return e},integer:function(e){return isNaN(e)?null:parseInt(e,10)},number:function(e){if(isNaN(e))throw null;return parseFloat(e)},boolean:function(e){return!/^\s*false\s*$/i.test(e)},object:function(e){return o.deserializeValue(e)},regexp:function(e){var t="";return/^\/.*\/(?:[gimy]*)$/.test(e)?(t=e.replace(/.*\/([gimy]*)$/,"$1"),e=e.replace(new RegExp("^/(.*?)/"+t+"$"),"$1")):e="^"+e+"$",new RegExp(e,t)}},parseRequirement:function(e,t){var i=this.parse[e||"string"];if(!i)throw'Unknown requirement specification: "'+e+'"';var n=i(t);if(null===n)throw"Requirement is not a "+e+': "'+t+'"';return n},namespaceEvents:function(t,i){return(t=this.trimString(t||"").split(/\s+/))[0]?e.map(t,function(e){return e+"."+i}).join(" "):""},difference:function(t,i){var n=[];return e.each(t,function(e,t){-1==i.indexOf(t)&&n.push(t)}),n},all:function(t){return e.when.apply(e,_toConsumableArray(t).concat([42,42]))},objectCreate:Object.create||(n=function(){},function(e){if(arguments.length>1)throw Error("Second argument not supported");if("object"!=typeof e)throw TypeError("Argument must be an object");n.prototype=e;var t=new n;return n.prototype=null,t}),_SubmitSelector:'input[type="submit"], button:submit'},a={namespace:"data-parsley-",inputs:"input, textarea, select",excluded:"input[type=button], input[type=submit], input[type=reset], input[type=hidden]",priorityEnabled:!0,multiple:null,group:null,uiEnabled:!0,validationThreshold:3,focus:"first",trigger:!1,triggerAfterFailure:"input",errorClass:"parsley-error",successClass:"parsley-success",classHandler:function(e){},errorsContainer:function(e){},errorsWrapper:'<ul class="parsley-errors-list"></ul>',errorTemplate:"<li></li>"},l=function(){this.__id__=o.generateID()};l.prototype={asyncSupport:!0,_pipeAccordingToValidationResult:function(){var t=this,i=function(){var i=e.Deferred();return!0!==t.validationResult&&i.reject(),i.resolve().promise()};return[i,i]},actualizeOptions:function(){return o.attr(this.element,this.options.namespace,this.domOptions),this.parent&&this.parent.actualizeOptions&&this.parent.actualizeOptions(),this},_resetOptions:function(e){this.domOptions=o.objectCreate(this.parent.options),this.options=o.objectCreate(this.domOptions);for(var t in e)e.hasOwnProperty(t)&&(this.options[t]=e[t]);this.actualizeOptions()},_listeners:null,on:function(e,t){return this._listeners=this._listeners||{},(this._listeners[e]=this._listeners[e]||[]).push(t),this},subscribe:function(t,i){e.listenTo(this,t.toLowerCase(),i)},off:function(e,t){var i=this._listeners&&this._listeners[e];if(i)if(t)for(var n=i.length;n--;)i[n]===t&&i.splice(n,1);else delete this._listeners[e];return this},unsubscribe:function(t,i){e.unsubscribeTo(this,t.toLowerCase())},trigger:function(e,t,i){t=t||this;var n,r=this._listeners&&this._listeners[e];if(r)for(var s=r.length;s--;)if(n=r[s].call(t,t,i),!1===n)return n;return!this.parent||this.parent.trigger(e,t,i)},asyncIsValid:function(e,t){return o.warnOnce("asyncIsValid is deprecated; please use whenValid instead"),this.whenValid({group:e,force:t})},_findRelated:function(){return this.options.multiple?e(this.parent.element.querySelectorAll("["+this.options.namespace+'multiple="'+this.options.multiple+'"]')):this.$element}};var u=function(t){e.extend(!0,this,t)};u.prototype={validate:function(e,t){if(this.fn)return arguments.length>3&&(t=[].slice.call(arguments,1,-1)),this.fn(e,t);if(Array.isArray(e)){if(!this.validateMultiple)throw"Validator `"+this.name+"` does not handle multiple values";return this.validateMultiple.apply(this,arguments)}var i=arguments[arguments.length-1];if(this.validateDate&&i._isDateInput())return arguments[0]=o.parse.date(arguments[0]),null!==arguments[0]&&this.validateDate.apply(this,arguments);if(this.validateNumber)return!isNaN(e)&&(arguments[0]=parseFloat(arguments[0]),this.validateNumber.apply(this,arguments));if(this.validateString)return this.validateString.apply(this,arguments);throw"Validator `"+this.name+"` only handles multiple values"},parseRequirements:function(t,i){if("string"!=typeof t)return Array.isArray(t)?t:[t];var n=this.requirementType;if(Array.isArray(n)){for(var r=function(e,t){var i=e.match(/^\s*\[(.*)\]\s*$/);if(!i)throw'Requirement is not an array: "'+e+'"';var n=i[1].split(",").map(o.trimString);if(n.length!==t)throw"Requirement has "+n.length+" values when "+t+" are needed";return n}(t,n.length),s=0;s<r.length;s++)r[s]=o.parseRequirement(n[s],r[s]);return r}return e.isPlainObject(n)?function(e,t,i){var n=null,r={};for(var s in e)if(s){var a=i(s);"string"==typeof a&&(a=o.parseRequirement(e[s],a)),r[s]=a}else n=o.parseRequirement(e[s],t);return[n,r]}(n,t,i):[o.parseRequirement(n,t)]},requirementType:"string",priority:2};var c=function(e,t){this.__class__="ValidatorRegistry",this.locale="en",this.init(e||{},t||{})},d={email:/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i,number:/^-?(\d*\.)?\d+(e[-+]?\d+)?$/i,integer:/^-?\d+$/,digits:/^\d+$/,alphanum:/^\w+$/i,date:{test:function(e){return null!==o.parse.date(e)}},url:new RegExp("^(?:(?:https?|ftp)://)?(?:\\S+(?::\\S*)?@)?(?:(?:[1-9]\\d?|1\\d\\d|2[01]\\d|22[0-3])(?:\\.(?:1?\\d{1,2}|2[0-4]\\d|25[0-5])){2}(?:\\.(?:[1-9]\\d?|1\\d\\d|2[0-4]\\d|25[0-4]))|(?:(?:[a-z\\u00a1-\\uffff0-9]-*)*[a-z\\u00a1-\\uffff0-9]+)(?:\\.(?:[a-z\\u00a1-\\uffff0-9]-*)*[a-z\\u00a1-\\uffff0-9]+)*(?:\\.(?:[a-z\\u00a1-\\uffff]{2,})))(?::\\d{2,5})?(?:/\\S*)?$","i")};d.range=d.number;var h=function(e){var t=(""+e).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);return t?Math.max(0,(t[1]?t[1].length:0)-(t[2]?+t[2]:0)):0},p=function(e,t){return function(i){for(var n=arguments.length,r=Array(n>1?n-1:0),s=1;s<n;s++)r[s-1]=arguments[s];return r.pop(),t.apply(void 0,[i].concat(_toConsumableArray((a=e,r.map(o.parse[a])))));var a}},f=function(e){return{validateDate:p("date",e),validateNumber:p("number",e),requirementType:e.length<=2?"string":["string","string"],priority:30}};c.prototype={init:function(e,t){this.catalog=t,this.validators=_extends({},this.validators);for(var i in e)this.addValidator(i,e[i].fn,e[i].priority);window.Parsley.trigger("parsley:validator:init")},setLocale:function(e){if(void 0===this.catalog[e])throw new Error(e+" is not available in the catalog");return this.locale=e,this},addCatalog:function(e,t,i){return"object"==typeof t&&(this.catalog[e]=t),!0===i?this.setLocale(e):this},addMessage:function(e,t,i){return void 0===this.catalog[e]&&(this.catalog[e]={}),this.catalog[e][t]=i,this},addMessages:function(e,t){for(var i in t)this.addMessage(e,i,t[i]);return this},addValidator:function(e,t,i){if(this.validators[e])o.warn('Validator "'+e+'" is already defined.');else if(a.hasOwnProperty(e))return void o.warn('"'+e+'" is a restricted keyword and is not a valid validator name.');return this._setValidator.apply(this,arguments)},updateValidator:function(e,t,i){return this.validators[e]?this._setValidator.apply(this,arguments):(o.warn('Validator "'+e+'" is not already defined.'),this.addValidator.apply(this,arguments))},removeValidator:function(e){return this.validators[e]||o.warn('Validator "'+e+'" is not defined.'),delete this.validators[e],this},_setValidator:function(e,t,i){"object"!=typeof t&&(t={fn:t,priority:i}),t.validate||(t=new u(t)),this.validators[e]=t;for(var n in t.messages||{})this.addMessage(n,e,t.messages[n]);return this},getErrorMessage:function(e){var t;"type"===e.name?t=(this.catalog[this.locale][e.name]||{})[e.requirements]:t=this.formatMessage(this.catalog[this.locale][e.name],e.requirements);return t||this.catalog[this.locale].defaultMessage||this.catalog.en.defaultMessage},formatMessage:function(e,t){if("object"==typeof t){for(var i in t)e=this.formatMessage(e,t[i]);return e}return"string"==typeof e?e.replace(/%s/i,t):""},validators:{notblank:{validateString:function(e){return/\S/.test(e)},priority:2},required:{validateMultiple:function(e){return e.length>0},validateString:function(e){return/\S/.test(e)},priority:512},type:{validateString:function(e,t){var i=arguments.length<=2||void 0===arguments[2]?{}:arguments[2],n=i.step,r=void 0===n?"any":n,s=i.base,o=void 0===s?0:s,a=d[t];if(!a)throw new Error("validator type `"+t+"` is not supported");if(!a.test(e))return!1;if("number"===t&&!/^any$/i.test(r||"")){var l=Number(e),u=Math.max(h(r),h(o));if(h(l)>u)return!1;var c=function(e){return Math.round(e*Math.pow(10,u))};if((c(l)-c(o))%c(r)!=0)return!1}return!0},requirementType:{"":"string",step:"string",base:"number"},priority:256},pattern:{validateString:function(e,t){return t.test(e)},requirementType:"regexp",priority:64},minlength:{validateString:function(e,t){return e.length>=t},requirementType:"integer",priority:30},maxlength:{validateString:function(e,t){return e.length<=t},requirementType:"integer",priority:30},length:{validateString:function(e,t,i){return e.length>=t&&e.length<=i},requirementType:["integer","integer"],priority:30},mincheck:{validateMultiple:function(e,t){return e.length>=t},requirementType:"integer",priority:30},maxcheck:{validateMultiple:function(e,t){return e.length<=t},requirementType:"integer",priority:30},check:{validateMultiple:function(e,t,i){return e.length>=t&&e.length<=i},requirementType:["integer","integer"],priority:30},min:f(function(e,t){return e>=t}),max:f(function(e,t){return e<=t}),range:f(function(e,t,i){return e>=t&&e<=i}),equalto:{validateString:function(t,i){var n=e(i);return n.length?t===n.val():t===i},priority:256}}};var m={};m.Form={_actualizeTriggers:function(){var e=this;this.$element.on("submit.Parsley",function(t){e.onSubmitValidate(t)}),this.$element.on("click.Parsley",o._SubmitSelector,function(t){e.onSubmitButton(t)}),!1!==this.options.uiEnabled&&this.element.setAttribute("novalidate","")},focus:function(){if(this._focusedField=null,!0===this.validationResult||"none"===this.options.focus)return null;for(var e=0;e<this.fields.length;e++){var t=this.fields[e];if(!0!==t.validationResult&&t.validationResult.length>0&&void 0===t.options.noFocus&&(this._focusedField=t.$element,"first"===this.options.focus))break}return null===this._focusedField?null:this._focusedField.focus()},_destroyUI:function(){this.$element.off(".Parsley")}},m.Field={_reflowUI:function(){if(this._buildUI(),this._ui){var e=function e(t,i,n){for(var r=[],s=[],o=0;o<t.length;o++){for(var a=!1,l=0;l<i.length;l++)if(t[o].assert.name===i[l].assert.name){a=!0;break}a?s.push(t[o]):r.push(t[o])}return{kept:s,added:r,removed:n?[]:e(i,t,!0).added}}(this.validationResult,this._ui.lastValidationResult);this._ui.lastValidationResult=this.validationResult,this._manageStatusClass(),this._manageErrorsMessages(e),this._actualizeTriggers(),!e.kept.length&&!e.added.length||this._failedOnce||(this._failedOnce=!0,this._actualizeTriggers())}},getErrorsMessages:function(){if(!0===this.validationResult)return[];for(var e=[],t=0;t<this.validationResult.length;t++)e.push(this.validationResult[t].errorMessage||this._getErrorMessage(this.validationResult[t].assert));return e},addError:function(e){var t=arguments.length<=1||void 0===arguments[1]?{}:arguments[1],i=t.message,n=t.assert,r=t.updateClass,s=void 0===r||r;this._buildUI(),this._addError(e,{message:i,assert:n}),s&&this._errorClass()},updateError:function(e){var t=arguments.length<=1||void 0===arguments[1]?{}:arguments[1],i=t.message,n=t.assert,r=t.updateClass,s=void 0===r||r;this._buildUI(),this._updateError(e,{message:i,assert:n}),s&&this._errorClass()},removeError:function(e){var t=(arguments.length<=1||void 0===arguments[1]?{}:arguments[1]).updateClass,i=void 0===t||t;this._buildUI(),this._removeError(e),i&&this._manageStatusClass()},_manageStatusClass:function(){this.hasConstraints()&&this.needsValidation()&&!0===this.validationResult?this._successClass():this.validationResult.length>0?this._errorClass():this._resetClass()},_manageErrorsMessages:function(t){if(void 0===this.options.errorsMessagesDisabled){if(void 0!==this.options.errorMessage)return t.added.length||t.kept.length?(this._insertErrorWrapper(),0===this._ui.$errorsWrapper.find(".parsley-custom-error-message").length&&this._ui.$errorsWrapper.append(e(this.options.errorTemplate).addClass("parsley-custom-error-message")),this._ui.$errorsWrapper.addClass("filled").find(".parsley-custom-error-message").html(this.options.errorMessage)):this._ui.$errorsWrapper.removeClass("filled").find(".parsley-custom-error-message").remove();for(var i=0;i<t.removed.length;i++)this._removeError(t.removed[i].assert.name);for(i=0;i<t.added.length;i++)this._addError(t.added[i].assert.name,{message:t.added[i].errorMessage,assert:t.added[i].assert});for(i=0;i<t.kept.length;i++)this._updateError(t.kept[i].assert.name,{message:t.kept[i].errorMessage,assert:t.kept[i].assert})}},_addError:function(t,i){var n=i.message,r=i.assert;this._insertErrorWrapper(),this._ui.$errorsWrapper.addClass("filled").append(e(this.options.errorTemplate).addClass("parsley-"+t).html(n||this._getErrorMessage(r)))},_updateError:function(e,t){var i=t.message,n=t.assert;this._ui.$errorsWrapper.addClass("filled").find(".parsley-"+e).html(i||this._getErrorMessage(n))},_removeError:function(e){this._ui.$errorsWrapper.removeClass("filled").find(".parsley-"+e).remove()},_getErrorMessage:function(e){var t=e.name+"Message";return void 0!==this.options[t]?window.Parsley.formatMessage(this.options[t],e.requirements):window.Parsley.getErrorMessage(e)},_buildUI:function(){if(!this._ui&&!1!==this.options.uiEnabled){var t={};this.element.setAttribute(this.options.namespace+"id",this.__id__),t.$errorClassHandler=this._manageClassHandler(),t.errorsWrapperId="parsley-id-"+(this.options.multiple?"multiple-"+this.options.multiple:this.__id__),t.$errorsWrapper=e(this.options.errorsWrapper).attr("id",t.errorsWrapperId),t.lastValidationResult=[],t.validationInformationVisible=!1,this._ui=t}},_manageClassHandler:function(){if("string"==typeof this.options.classHandler)return 0===e(this.options.classHandler).length&&ParsleyUtils.warn("No elements found that match the selector `"+this.options.classHandler+"` set in options.classHandler or data-parsley-class-handler"),e(this.options.classHandler);if("function"==typeof this.options.classHandler)var t=this.options.classHandler.call(this,this);return void 0!==t&&t.length?t:this._inputHolder()},_inputHolder:function(){return this.options.multiple&&"SELECT"!==this.element.nodeName?this.$element.parent():this.$element},_insertErrorWrapper:function(){var t;if(0!==this._ui.$errorsWrapper.parent().length)return this._ui.$errorsWrapper.parent();if("string"==typeof this.options.errorsContainer){if(e(this.options.errorsContainer).length)return e(this.options.errorsContainer).append(this._ui.$errorsWrapper);o.warn("The errors container `"+this.options.errorsContainer+"` does not exist in DOM")}else"function"==typeof this.options.errorsContainer&&(t=this.options.errorsContainer.call(this,this));return void 0!==t&&t.length?t.append(this._ui.$errorsWrapper):this._inputHolder().after(this._ui.$errorsWrapper)},_actualizeTriggers:function(){var e,t=this,i=this._findRelated();i.off(".Parsley"),this._failedOnce?i.on(o.namespaceEvents(this.options.triggerAfterFailure,"Parsley"),function(){t._validateIfNeeded()}):(e=o.namespaceEvents(this.options.trigger,"Parsley"))&&i.on(e,function(e){t._validateIfNeeded(e)})},_validateIfNeeded:function(e){var t=this;e&&/key|input/.test(e.type)&&(!this._ui||!this._ui.validationInformationVisible)&&this.getValue().length<=this.options.validationThreshold||(this.options.debounce?(window.clearTimeout(this._debounced),this._debounced=window.setTimeout(function(){return t.validate()},this.options.debounce)):this.validate())},_resetUI:function(){this._failedOnce=!1,this._actualizeTriggers(),void 0!==this._ui&&(this._ui.$errorsWrapper.removeClass("filled").children().remove(),this._resetClass(),this._ui.lastValidationResult=[],this._ui.validationInformationVisible=!1)},_destroyUI:function(){this._resetUI(),void 0!==this._ui&&this._ui.$errorsWrapper.remove(),delete this._ui},_successClass:function(){this._ui.validationInformationVisible=!0,this._ui.$errorClassHandler.removeClass(this.options.errorClass).addClass(this.options.successClass)},_errorClass:function(){this._ui.validationInformationVisible=!0,this._ui.$errorClassHandler.removeClass(this.options.successClass).addClass(this.options.errorClass)},_resetClass:function(){this._ui.$errorClassHandler.removeClass(this.options.successClass).removeClass(this.options.errorClass)}};var v=function(t,i,n){this.__class__="Form",this.element=t,this.$element=e(t),this.domOptions=i,this.options=n,this.parent=window.Parsley,this.fields=[],this.validationResult=null},g={pending:null,resolved:!0,rejected:!1};v.prototype={onSubmitValidate:function(e){var t=this;if(!0!==e.parsley){var i=this._submitSource||this.$element.find(o._SubmitSelector)[0];if(this._submitSource=null,this.$element.find(".parsley-synthetic-submit-button").prop("disabled",!0),!i||null===i.getAttribute("formnovalidate")){window.Parsley._remoteCache={};var n=this.whenValidate({event:e});"resolved"===n.state()&&!1!==this._trigger("submit")||(e.stopImmediatePropagation(),e.preventDefault(),"pending"===n.state()&&n.done(function(){t._submit(i)}))}}},onSubmitButton:function(e){this._submitSource=e.currentTarget},_submit:function(t){if(!1!==this._trigger("submit")){if(t){var i=this.$element.find(".parsley-synthetic-submit-button").prop("disabled",!1);0===i.length&&(i=e('<input class="parsley-synthetic-submit-button" type="hidden">').appendTo(this.$element)),i.attr({name:t.getAttribute("name"),value:t.getAttribute("value")})}this.$element.trigger(_extends(e.Event("submit"),{parsley:!0}))}},validate:function(t){if(arguments.length>=1&&!e.isPlainObject(t)){o.warnOnce("Calling validate on a parsley form without passing arguments as an object is deprecated.");var i=_slice.call(arguments);t={group:i[0],force:i[1],event:i[2]}}return g[this.whenValidate(t).state()]},whenValidate:function(){var t,i=this,n=arguments.length<=0||void 0===arguments[0]?{}:arguments[0],r=n.group,s=n.force,a=n.event;this.submitEvent=a,a&&(this.submitEvent=_extends({},a,{preventDefault:function(){o.warnOnce("Using `this.submitEvent.preventDefault()` is deprecated; instead, call `this.validationResult = false`"),i.validationResult=!1}})),this.validationResult=!0,this._trigger("validate"),this._refreshFields();var l=this._withoutReactualizingFormOptions(function(){return e.map(i.fields,function(e){return e.whenValidate({force:s,group:r})})});return(t=o.all(l).done(function(){i._trigger("success")}).fail(function(){i.validationResult=!1,i.focus(),i._trigger("error")}).always(function(){i._trigger("validated")})).pipe.apply(t,_toConsumableArray(this._pipeAccordingToValidationResult()))},isValid:function(t){if(arguments.length>=1&&!e.isPlainObject(t)){o.warnOnce("Calling isValid on a parsley form without passing arguments as an object is deprecated.");var i=_slice.call(arguments);t={group:i[0],force:i[1]}}return g[this.whenValid(t).state()]},whenValid:function(){var t=this,i=arguments.length<=0||void 0===arguments[0]?{}:arguments[0],n=i.group,r=i.force;this._refreshFields();var s=this._withoutReactualizingFormOptions(function(){return e.map(t.fields,function(e){return e.whenValid({group:n,force:r})})});return o.all(s)},reset:function(){for(var e=0;e<this.fields.length;e++)this.fields[e].reset();this._trigger("reset")},destroy:function(){this._destroyUI();for(var e=0;e<this.fields.length;e++)this.fields[e].destroy();this.$element.removeData("Parsley"),this._trigger("destroy")},_refreshFields:function(){return this.actualizeOptions()._bindFields()},_bindFields:function(){var t=this,i=this.fields;return this.fields=[],this.fieldsMappedById={},this._withoutReactualizingFormOptions(function(){t.$element.find(t.options.inputs).not(t.options.excluded).each(function(e,i){var n=new window.Parsley.Factory(i,{},t);if(("Field"===n.__class__||"FieldMultiple"===n.__class__)&&!0!==n.options.excluded){var r=n.__class__+"-"+n.__id__;void 0===t.fieldsMappedById[r]&&(t.fieldsMappedById[r]=n,t.fields.push(n))}}),e.each(o.difference(i,t.fields),function(e,t){t.reset()})}),this},_withoutReactualizingFormOptions:function(e){var t=this.actualizeOptions;this.actualizeOptions=function(){return this};var i=e();return this.actualizeOptions=t,i},_trigger:function(e){return this.trigger("form:"+e)}};var y=function(e,t,i,n,r){var s=window.Parsley._validatorRegistry.validators[t],o=new u(s);n=n||e.options[t+"Priority"]||o.priority,_extends(this,{validator:o,name:t,requirements:i,priority:n,isDomConstraint:r=!0===r}),this._parseRequirements(e.options)};y.prototype={validate:function(e,t){var i;return(i=this.validator).validate.apply(i,[e].concat(_toConsumableArray(this.requirementList),[t]))},_parseRequirements:function(e){var t=this;this.requirementList=this.validator.parseRequirements(this.requirements,function(i){return e[t.name+(n=i,n[0].toUpperCase()+n.slice(1))];var n})}};var w=function(t,i,n,r){this.__class__="Field",this.element=t,this.$element=e(t),void 0!==r&&(this.parent=r),this.options=n,this.domOptions=i,this.constraints=[],this.constraintsByName={},this.validationResult=!0,this._bindConstraints()},b={pending:null,resolved:!0,rejected:!1};w.prototype={validate:function(t){arguments.length>=1&&!e.isPlainObject(t)&&(o.warnOnce("Calling validate on a parsley field without passing arguments as an object is deprecated."),t={options:t});var i=this.whenValidate(t);if(!i)return!0;switch(i.state()){case"pending":return null;case"resolved":return!0;case"rejected":return this.validationResult}},whenValidate:function(){var e,t=this,i=arguments.length<=0||void 0===arguments[0]?{}:arguments[0],n=i.force,r=i.group;if(this.refreshConstraints(),!r||this._isInGroup(r))return this.value=this.getValue(),this._trigger("validate"),(e=this.whenValid({force:n,value:this.value,_refreshed:!0}).always(function(){t._reflowUI()}).done(function(){t._trigger("success")}).fail(function(){t._trigger("error")}).always(function(){t._trigger("validated")})).pipe.apply(e,_toConsumableArray(this._pipeAccordingToValidationResult()))},hasConstraints:function(){return 0!==this.constraints.length},needsValidation:function(e){return void 0===e&&(e=this.getValue()),!(!e.length&&!this._isRequired()&&void 0===this.options.validateIfEmpty)},_isInGroup:function(t){return Array.isArray(this.options.group)?-1!==e.inArray(t,this.options.group):this.options.group===t},isValid:function(t){if(arguments.length>=1&&!e.isPlainObject(t)){o.warnOnce("Calling isValid on a parsley field without passing arguments as an object is deprecated.");var i=_slice.call(arguments);t={force:i[0],value:i[1]}}var n=this.whenValid(t);return!n||b[n.state()]},whenValid:function(){var t=this,i=arguments.length<=0||void 0===arguments[0]?{}:arguments[0],n=i.force,r=void 0!==n&&n,s=i.value,a=i.group;if(i._refreshed||this.refreshConstraints(),!a||this._isInGroup(a)){if(this.validationResult=!0,!this.hasConstraints())return e.when();if(void 0!==s&&null!==s||(s=this.getValue()),!this.needsValidation(s)&&!0!==r)return e.when();var l=this._getGroupedConstraints(),u=[];return e.each(l,function(i,n){var r=o.all(e.map(n,function(e){return t._validateConstraint(s,e)}));if(u.push(r),"rejected"===r.state())return!1}),o.all(u)}},_validateConstraint:function(t,i){var n=this,r=i.validate(t,this);return!1===r&&(r=e.Deferred().reject()),o.all([r]).fail(function(e){n.validationResult instanceof Array||(n.validationResult=[]),n.validationResult.push({assert:i,errorMessage:"string"==typeof e&&e})})},getValue:function(){var e;return void 0===(e="function"==typeof this.options.value?this.options.value(this):void 0!==this.options.value?this.options.value:this.$element.val())||null===e?"":this._handleWhitespace(e)},reset:function(){return this._resetUI(),this._trigger("reset")},destroy:function(){this._destroyUI(),this.$element.removeData("Parsley"),this.$element.removeData("FieldMultiple"),this._trigger("destroy")},refreshConstraints:function(){return this.actualizeOptions()._bindConstraints()},addConstraint:function(e,t,i,n){if(window.Parsley._validatorRegistry.validators[e]){var r=new y(this,e,t,i,n);"undefined"!==this.constraintsByName[r.name]&&this.removeConstraint(r.name),this.constraints.push(r),this.constraintsByName[r.name]=r}return this},removeConstraint:function(e){for(var t=0;t<this.constraints.length;t++)if(e===this.constraints[t].name){this.constraints.splice(t,1);break}return delete this.constraintsByName[e],this},updateConstraint:function(e,t,i){return this.removeConstraint(e).addConstraint(e,t,i)},_bindConstraints:function(){for(var e=[],t={},i=0;i<this.constraints.length;i++)!1===this.constraints[i].isDomConstraint&&(e.push(this.constraints[i]),t[this.constraints[i].name]=this.constraints[i]);this.constraints=e,this.constraintsByName=t;for(var n in this.options)this.addConstraint(n,this.options[n],void 0,!0);return this._bindHtml5Constraints()},_bindHtml5Constraints:function(){null!==this.element.getAttribute("required")&&this.addConstraint("required",!0,void 0,!0),null!==this.element.getAttribute("pattern")&&this.addConstraint("pattern",this.element.getAttribute("pattern"),void 0,!0);var e=this.element.getAttribute("min"),t=this.element.getAttribute("max");null!==e&&null!==t?this.addConstraint("range",[e,t],void 0,!0):null!==e?this.addConstraint("min",e,void 0,!0):null!==t&&this.addConstraint("max",t,void 0,!0),null!==this.element.getAttribute("minlength")&&null!==this.element.getAttribute("maxlength")?this.addConstraint("length",[this.element.getAttribute("minlength"),this.element.getAttribute("maxlength")],void 0,!0):null!==this.element.getAttribute("minlength")?this.addConstraint("minlength",this.element.getAttribute("minlength"),void 0,!0):null!==this.element.getAttribute("maxlength")&&this.addConstraint("maxlength",this.element.getAttribute("maxlength"),void 0,!0);var i=this.element.type;return"number"===i?this.addConstraint("type",["number",{step:this.element.getAttribute("step")||"1",base:e||this.element.getAttribute("value")}],void 0,!0):/^(email|url|range|date)$/i.test(i)?this.addConstraint("type",i,void 0,!0):this},_isRequired:function(){return void 0!==this.constraintsByName.required&&!1!==this.constraintsByName.required.requirements},_trigger:function(e){return this.trigger("field:"+e)},_handleWhitespace:function(e){return!0===this.options.trimValue&&o.warnOnce('data-parsley-trim-value="true" is deprecated, please use data-parsley-whitespace="trim"'),"squish"===this.options.whitespace&&(e=e.replace(/\s{2,}/g," ")),"trim"!==this.options.whitespace&&"squish"!==this.options.whitespace&&!0!==this.options.trimValue||(e=o.trimString(e)),e},_isDateInput:function(){var e=this.constraintsByName.type;return e&&"date"===e.requirements},_getGroupedConstraints:function(){if(!1===this.options.priorityEnabled)return[this.constraints];for(var e=[],t={},i=0;i<this.constraints.length;i++){var n=this.constraints[i].priority;t[n]||e.push(t[n]=[]),t[n].push(this.constraints[i])}return e.sort(function(e,t){return t[0].priority-e[0].priority}),e}};var C=w,x=function(){this.__class__="FieldMultiple"};x.prototype={addElement:function(e){return this.$elements.push(e),this},refreshConstraints:function(){var t;if(this.constraints=[],"SELECT"===this.element.nodeName)return this.actualizeOptions()._bindConstraints(),this;for(var i=0;i<this.$elements.length;i++)if(e("html").has(this.$elements[i]).length){t=this.$elements[i].data("FieldMultiple").refreshConstraints().constraints;for(var n=0;n<t.length;n++)this.addConstraint(t[n].name,t[n].requirements,t[n].priority,t[n].isDomConstraint)}else this.$elements.splice(i,1);return this},getValue:function(){if("function"==typeof this.options.value)return this.options.value(this);if(void 0!==this.options.value)return this.options.value;if("INPUT"===this.element.nodeName){if("radio"===this.element.type)return this._findRelated().filter(":checked").val()||"";if("checkbox"===this.element.type){var t=[];return this._findRelated().filter(":checked").each(function(){t.push(e(this).val())}),t}}return"SELECT"===this.element.nodeName&&null===this.$element.val()?[]:this.$element.val()},_init:function(){return this.$elements=[this.$element],this}};var E=function(t,i,n){this.element=t,this.$element=e(t);var r=this.$element.data("Parsley");if(r)return void 0!==n&&r.parent===window.Parsley&&(r.parent=n,r._resetOptions(r.options)),"object"==typeof i&&_extends(r.options,i),r;if(!this.$element.length)throw new Error("You must bind Parsley on an existing element.");if(void 0!==n&&"Form"!==n.__class__)throw new Error("Parent instance must be a Form instance");return this.parent=n||window.Parsley,this.init(i)};E.prototype={init:function(e){return this.__class__="Parsley",this.__version__="2.7.2",this.__id__=o.generateID(),this._resetOptions(e),"FORM"===this.element.nodeName||o.checkAttr(this.element,this.options.namespace,"validate")&&!this.$element.is(this.options.inputs)?this.bind("parsleyForm"):this.isMultiple()?this.handleMultiple():this.bind("parsleyField")},isMultiple:function(){return"radio"===this.element.type||"checkbox"===this.element.type||"SELECT"===this.element.nodeName&&null!==this.element.getAttribute("multiple")},handleMultiple:function(){var t,i,n=this;if(this.options.multiple=this.options.multiple||(t=this.element.getAttribute("name"))||this.element.getAttribute("id"),"SELECT"===this.element.nodeName&&null!==this.element.getAttribute("multiple"))return this.options.multiple=this.options.multiple||this.__id__,this.bind("parsleyFieldMultiple");if(!this.options.multiple)return o.warn("To be bound by Parsley, a radio, a checkbox and a multiple select input must have either a name or a multiple option.",this.$element),this;this.options.multiple=this.options.multiple.replace(/(:|\.|\[|\]|\{|\}|\$)/g,""),t&&e('input[name="'+t+'"]').each(function(e,t){"radio"!==t.type&&"checkbox"!==t.type||t.setAttribute(n.options.namespace+"multiple",n.options.multiple)});for(var r=this._findRelated(),s=0;s<r.length;s++)if(i=e(r.get(s)).data("Parsley"),void 0!==i){this.$element.data("FieldMultiple")||i.addElement(this.$element);break}return this.bind("parsleyField",!0),i||this.bind("parsleyFieldMultiple")},bind:function(t,i){var n;switch(t){case"parsleyForm":n=e.extend(new v(this.element,this.domOptions,this.options),new l,window.ParsleyExtend)._bindFields();break;case"parsleyField":n=e.extend(new C(this.element,this.domOptions,this.options,this.parent),new l,window.ParsleyExtend);break;case"parsleyFieldMultiple":n=e.extend(new C(this.element,this.domOptions,this.options,this.parent),new x,new l,window.ParsleyExtend)._init();break;default:throw new Error(t+"is not a supported Parsley type")}return this.options.multiple&&o.setAttr(this.element,this.options.namespace,"multiple",this.options.multiple),void 0!==i?(this.$element.data("FieldMultiple",n),n):(this.$element.data("Parsley",n),n._actualizeTriggers(),n._trigger("init"),n)}};var _=e.fn.jquery.split(".");if(parseInt(_[0])<=1&&parseInt(_[1])<8)throw"The loaded version of jQuery is too old. Please upgrade to 1.8.x or better.";_.forEach||o.warn("Parsley requires ES5 to run properly. Please include https://github.com/es-shims/es5-shim");var F=_extends(new l,{element:document,$element:e(document),actualizeOptions:null,_resetOptions:null,Factory:E,version:"2.7.2"});_extends(C.prototype,m.Field,l.prototype),_extends(v.prototype,m.Form,l.prototype),_extends(E.prototype,l.prototype),e.fn.parsley=e.fn.psly=function(t){if(this.length>1){var i=[];return this.each(function(){i.push(e(this).parsley(t))}),i}return e(this).length?new E(this[0],t):void o.warn("You must bind Parsley on an existing element.")},void 0===window.ParsleyExtend&&(window.ParsleyExtend={}),F.options=_extends(o.objectCreate(a),window.ParsleyConfig),window.ParsleyConfig=F.options,window.Parsley=window.psly=F,F.Utils=o,window.ParsleyUtils={},e.each(o,function(e,t){"function"==typeof t&&(window.ParsleyUtils[e]=function(){return o.warnOnce("Accessing `window.ParsleyUtils` is deprecated. Use `window.Parsley.Utils` instead."),o[e].apply(o,arguments)})});var k=window.Parsley._validatorRegistry=new c(window.ParsleyConfig.validators,window.ParsleyConfig.i18n);window.ParsleyValidator={},e.each("setLocale addCatalog addMessage addMessages getErrorMessage formatMessage addValidator updateValidator removeValidator".split(" "),function(e,t){window.Parsley[t]=function(){return k[t].apply(k,arguments)},window.ParsleyValidator[t]=function(){var e;return o.warnOnce("Accessing the method '"+t+"' through Validator is deprecated. Simply call 'window.Parsley."+t+"(...)'"),(e=window.Parsley)[t].apply(e,arguments)}}),window.Parsley.UI=m,window.ParsleyUI={removeError:function(e,t,i){var n=!0!==i;return o.warnOnce("Accessing UI is deprecated. Call 'removeError' on the instance directly. Please comment in issue 1073 as to your need to call this method."),e.removeError(t,{updateClass:n})},getErrorsMessages:function(e){return o.warnOnce("Accessing UI is deprecated. Call 'getErrorsMessages' on the instance directly."),e.getErrorsMessages()}},e.each("addError updateError".split(" "),function(e,t){window.ParsleyUI[t]=function(e,i,n,r,s){var a=!0!==s;return o.warnOnce("Accessing UI is deprecated. Call '"+t+"' on the instance directly. Please comment in issue 1073 as to your need to call this method."),e[t](i,{message:n,assert:r,updateClass:a})}}),!1!==window.ParsleyConfig.autoBind&&e(function(){e("[data-parsley-validate]").length&&e("[data-parsley-validate]").parsley()});var S=e({}),T=function(){o.warnOnce("Parsley's pubsub module is deprecated; use the 'on' and 'off' methods on parsley instances or window.Parsley")},A="parsley:";return e.listen=function(e,n){var r;if(T(),"object"==typeof arguments[1]&&"function"==typeof arguments[2]&&(r=arguments[1],n=arguments[2]),"function"!=typeof n)throw new Error("Wrong parameters");window.Parsley.on(i(e),t(n,r))},e.listenTo=function(e,n,r){if(T(),!(e instanceof C||e instanceof v))throw new Error("Must give Parsley instance");if("string"!=typeof n||"function"!=typeof r)throw new Error("Wrong parameters");e.on(i(n),t(r))},e.unsubscribe=function(e,t){if(T(),"string"!=typeof e||"function"!=typeof t)throw new Error("Wrong arguments");window.Parsley.off(i(e),t.parsleyAdaptedCallback)},e.unsubscribeTo=function(e,t){if(T(),!(e instanceof C||e instanceof v))throw new Error("Must give Parsley instance");e.off(i(t))},e.unsubscribeAll=function(t){T(),window.Parsley.off(i(t)),e("form,input,textarea,select").each(function(){var n=e(this).data("Parsley");n&&n.off(i(t))})},e.emit=function(e,t){var n;T();var r=t instanceof C||t instanceof v,s=Array.prototype.slice.call(arguments,r?2:1);s.unshift(i(e)),r||(t=window.Parsley),(n=t).trigger.apply(n,_toConsumableArray(s))},e.extend(!0,F,{asyncValidators:{default:{fn:function(e){return e.status>=200&&e.status<300},url:!1},reverse:{fn:function(e){return e.status<200||e.status>=300},url:!1}},addAsyncValidator:function(e,t,i,n){return F.asyncValidators[e]={fn:t,url:i||!1,options:n||{}},this}}),F.addValidator("remote",{requirementType:{"":"string",validator:"string",reverse:"boolean",options:"object"},validateString:function(t,i,n,r){var s,o,a={},l=n.validator||(!0===n.reverse?"reverse":"default");if(void 0===F.asyncValidators[l])throw new Error("Calling an undefined async validator: `"+l+"`");(i=F.asyncValidators[l].url||i).indexOf("{value}")>-1?i=i.replace("{value}",encodeURIComponent(t)):a[r.element.getAttribute("name")||r.element.getAttribute("id")]=t;var u=e.extend(!0,n.options||{},F.asyncValidators[l].options);s=e.extend(!0,{},{url:i,data:a,type:"GET"},u),r.trigger("field:ajaxoptions",r,s),o=e.param(s),void 0===F._remoteCache&&(F._remoteCache={});var c=F._remoteCache[o]=F._remoteCache[o]||e.ajax(s),d=function(){var t=F.asyncValidators[l].fn.call(r,c,i,n);return t||(t=e.Deferred().reject()),e.when(t)};return c.then(d,d)},priority:-1}),F.on("form:submit",function(){F._remoteCache={}}),l.prototype.addAsyncValidator=function(){return o.warnOnce("Accessing the method `addAsyncValidator` through an instance is deprecated. Simply call `Parsley.addAsyncValidator(...)`"),F.addAsyncValidator.apply(F,arguments)},F.addMessages("en",{defaultMessage:"This value seems to be invalid.",type:{email:"This value should be a valid email.",url:"This value should be a valid url.",number:"This value should be a valid number.",integer:"This value should be a valid integer.",digits:"This value should be digits.",alphanum:"This value should be alphanumeric."},notblank:"This value should not be blank.",required:"This value is required.",pattern:"This value seems to be invalid.",min:"This value should be greater than or equal to %s.",max:"This value should be lower than or equal to %s.",range:"This value should be between %s and %s.",minlength:"This value is too short. It should have %s characters or more.",maxlength:"This value is too long. It should have %s characters or fewer.",length:"This value length is invalid. It should be between %s and %s characters long.",mincheck:"You must select at least %s choices.",maxcheck:"You must select %s choices or fewer.",check:"You must select between %s and %s choices.",equalto:"This value should be the same."}),F.setLocale("en"),(new function(){var t=this,i=window||global;_extends(this,{isNativeEvent:function(e){return e.originalEvent&&!1!==e.originalEvent.isTrusted},fakeInputEvent:function(i){t.isNativeEvent(i)&&e(i.target).trigger("input")},misbehaves:function(i){t.isNativeEvent(i)&&(t.behavesOk(i),e(document).on("change.inputevent",i.data.selector,t.fakeInputEvent),t.fakeInputEvent(i))},behavesOk:function(i){t.isNativeEvent(i)&&e(document).off("input.inputevent",i.data.selector,t.behavesOk).off("change.inputevent",i.data.selector,t.misbehaves)},install:function(){if(!i.inputEventPatched){i.inputEventPatched="0.0.3";for(var n=["select",'input[type="checkbox"]','input[type="radio"]','input[type="file"]'],r=0;r<n.length;r++){var s=n[r];e(document).on("input.inputevent",s,{selector:s},t.behavesOk).on("change.inputevent",s,{selector:s},t.misbehaves)}}},uninstall:function(){delete i.inputEventPatched,e(document).off(".inputevent")}})}).install(),F}); 

/**
 * Jquery start
 */
$(document).ready(function () {
	toastr.options = {
		"closeButton": true,
		"newestOnTop": true,
		"progressBar": true,
		"positionClass": "toast-top-right",
		"preventDuplicates": false,
		"onclick": null,
		"showDuration": "300",
		"hideDuration": "1000",
		"timeOut": "5000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "swing",
		"showMethod": "slideDown",
		"hideMethod": "slideUp"
	};


	/*
	 * initialize dropify
	 */
	 if ($(".dropify")) {
	 	$(".dropify").dropify()
	 }


	/*
	 * initialize dropify
	 */
	 if ($(".croppie")) {
	 	croppify();
	 }

	/*
	 * initialize Switchery
	 */
	var elems = Array.prototype.slice.call(document.querySelectorAll('.switch'));
	elems.forEach(function(html) {
		var switchery = new Switchery(html, {
			size: 'small',
			color: '#007bff'
		});
	});
});
/*
 * Simcyfy show loader
 */
function showLoader(options) {
	/**
	 * remove existing loaders
	 */
	hideLoader();
	if (options === undefined) {
		options = {
			color: "#007bff",
			background: "rgba(298, 294, 290, 0.9)"
		};
	} else {
		if (options.background === undefined) {
			options.background = "rgba(298, 294, 290, 0.9)";
		}
		if (options.color === undefined) {
			options.color = "#007bff";
		}
	}
	$("body").append('<div class="loading-overlay" style="background-color:' + options.background + ';"><div class="loader-box"><div class="circle-loader"></div></div></div>');
	$("body").append('<style class="notify-styling">.circle-loader:before { border-top-color: ' + options.color + '; }</style>');
}
/*
 * Simcyfy hide loader
 */
function hideLoader() {
	$(".loading-overlay, notify-styling").remove();
}
/*
 * Simcyfy show card payment form
 *
 * @param {object} with [buttonText] - text of the button, [buttonClass] - class of the button and [callback] - this is a function
 */
function showCardPaymentForm(options) {
	hideCardPaymentForm();
	if (options === undefined) {
		options = {
			buttonText: "Proceed",
			buttonClass: "",
			callback: undefined
		};
	} else {
		if (options.buttonText === undefined) {
			options.buttonText = "Proceed";
		}
		if (options.buttonClass === undefined) {
			options.buttonClass = "";
		}
		if (options.callback === undefined) {
			options.callback = false;
		}
	}
	$("body").addClass("fit-screen");
	$("body").append('<div class="payment-modal"><div class="cancel-payment">&times;</div><div id="card-success" class="hidden"> <i class="fa fa-check"></i><p>Payment Successful!</p></div><div id="form-errors" class="hidden"> <i class="fa fa-exclamation-triangle"></i><p id="card-error">Card error</p></div><div id="form-container"><div id="card-front"><div id="shadow"></div><div id="image-container"> <span id="amount">This is a <strong>Secure</strong> checkout.</span> </span> <span id="card-image"> </span></div><form class="card-payment-form"> <label for="card-number"> Card Number </label> <input type="text" id="card-number" data-stripe="number" placeholder="1234 5678 9101 1112" length="16"><div id="cardholder-container"> <label for="card-holder">Card Holder </label> <input type="text" id="card-holder" data-stripe="holder" placeholder="e.g. John Doe" /></div><div id="exp-container"> <label for="card-exp"> Expiration </label> <input id="card-month" data-stripe="exp_month" type="text" placeholder="MM" length="2"> <input id="card-year" data-stripe="exp_year" type="text" placeholder="YY" length="2"></div><div id="cvc-container"> <label for="card-cvc"> CVC/CVV</label> <input id="card-cvc" data-stripe="cvc" placeholder="XXX-X" type="text" min-length="3" max-length="4"><p>Last 3 or 4 digits</p></div></form></div><div id="card-back"><div id="card-stripe"></div></div> <button type="button" id="card-btn" class="btn btn-primary card-button ' + options.buttonClass + '">' + options.buttonText + '</button></div></div>');
	$(".cancel-payment").click(function () {
		hideCardPaymentForm();
	});
	if (options.callback) {
		$(".card-button").click(function () {
			eval(options.callback);
		});
	}
}
/*
 * hide card payment form
 */
function hideCardPaymentForm() {
	$(".payment-modal").slideUp("slow", function () {
		$(".payment-modal").remove();
		$("body").removeClass("fit-screen");
	});
}
/*
 * fancyboxjs
 */
 $("body").on("click", ".click-preview", function(){
 	var imagePath = $(this).attr("src");
 	log("here");
 	$("body").append('<div class="click-preview-overlay"><div class="click-preview-close"><div></div></div><div class="click-preview-box"><div class="click-preview-image-holder"><img src="'+imagePath+'"></div></div></div>');

 });
 $("body").on("click", ".click-preview-overlay", function(){
 	$(".click-preview-overlay").remove();
 });
/*
 * reload page
 */
function reload() {
	window.location.reload();
}
/*
 * redirect
 *
 *@param {string} url to redirect
 */
function redirect(url, loader) {
	if (loader === undefined) {
		loader = false
	}
	if (loader) {
		showLoader();
	}
	window.location.href = url;
}
/*
 * open url on a new tap
 *
 *@param {string} url to open
 */
function openUrl(url) {
	window.open(url);
}
/*
 * print on console
 *
 *@param {any data type}
 */
function log(data) {
	console.log(data);
}
/*
 * print error on console
 *
 *@param {any data type}
 */
function error(data) {
	console.error(data);
}
/*
 * send data to server side
 *
 *@param {string}
 *@param {object}: {string}[url], the url where to send data, {object}[data], data to send to server, {boolean} loader, whether to show loading or not.
 */
function server(options) {
	var sendToServer = true;
	if (options === undefined) {
		sendToServer = false;
	} else {
		if (options.url === undefined) {
			console.error("URL is missing!");
			sendToServer = false;
		}
		if (options.data === undefined) {
			console.error("Data is missing!");
			sendToServer = false;
		}
		if (options.loader === undefined) {
			options.loader = true;
		}
		if (options.deletemodal === undefined) {
			options.deletemodal = true;
		}
	}
	if (sendToServer) {
		if (options.loader) {
			showLoader();
		}
		var posting = $.post(options.url, options.data);
		posting.done(function (response) {
			if (options.loader) {
				hideLoader()
			}
			serverResponse(response, options.deletemodal);
		});
	}
}
/*
 * create random string
 *
 *@param {object}: {string}[length], the length of the random string, {string}[type], number or alphabet characters, {string}[case] lower|upper.
 */
function random(options) {
	var response = "";
	if (options === undefined) {
		options = {
			length: 16,
			type: "",
			case :""
		};
	} else {
		if (options.length === undefined) {
			options.length = 16;
		}
	}
	if (options.type === "alphabet") {
		var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	} else if (options.type === "number") {
		var possible = "1234567890";
	} else {
		var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	}
	options.length = parseInt(options.length);
	for (var i = 0; i < options.length; i++) {
		response += possible.charAt(Math.floor(Math.random() * possible.length));
	}
	if (options.case === "upper") {
		response = response.toUpperCase();
	} else if (options.case === "lower") {
		response = response.toLowerCase();
	}
	return response;
}
/*
 * sweet alert
 *
 *@param {string} title
 *@param {string} message
 *@param {string} success|info|error|warning
 *@param {string} text of the button
 *@param {object} [callback] [callback][string] function, [showCancelButton][boolen], [closeOnConfirm][boolean]
 */
function notify(title, message, type, button, advanced) {
	if (type === undefined) {
		type = "info";
	}
	if (button === undefined) {
		button = "Okay!";
	}
	if (advanced === undefined) {
		swal({
			title: title,
			text: message,
			type: type,
			showCancelButton: false,
			confirmButtonColor: "#007bff",
			confirmButtonText: button,
			closeOnConfirm: true
		});
	} else {
		if (advanced.color === undefined) {
			advanced.color = "#007bff";
		}
		if (advanced.showCancelButton === undefined) {
			advanced.showCancelButton = false;
		}
		if (advanced.closeOnConfirm === undefined) {
			advanced.closeOnConfirm = true;
		}
		if (advanced.callback === undefined) {
			swal({
				title: title,
				text: message,
				type: type,
				showCancelButton: advanced.showCancelButton,
				confirmButtonColor: advanced.color,
				confirmButtonText: button,
				closeOnConfirm: advanced.closeOnConfirm
			});
		} else {
			swal({
				title: title,
				text: message,
				type: type,
				showCancelButton: advanced.showCancelButton,
				confirmButtonColor: advanced.color,
				confirmButtonText: button,
				closeOnConfirm: advanced.closeOnConfirm
			}, function () {
				eval(advanced.callback);
			});
		}
	}
}
/*
 * send to server on click
 *
 *@param {any data type}
 */
$("body").on("click", ".send-to-server-click", function (event) {
	event.preventDefault();
	var holder = $(this);
	var data = holder.attr("data"),
		url = holder.attr("url"),
		loader = true;
	// format data 
	var dataArray = data.split("|");
	var data = {};
	dataArray.forEach(function (item) {
		var singleItem = item.split(":");
		data[singleItem[0]] = singleItem[1];
	});
	if (holder.attr("loader") === "true") {
		loader = true;
	} else if (holder.attr("loader") === "false") {
		loader = false;
	}
	if (holder.attr("warning-title") === undefined) {
		server({
			url: url,
			data: data,
			loader: loader
		});
	} else {
		var title = holder.attr("warning-title"),
			button = "Continue";
		if (holder.attr("warning-button") === undefined) {
			button = "Continue";
		} else {
			button = holder.attr("warning-button");
		}
		if (holder.attr("warning-message") === undefined) {
			message = "";
		} else {
			message = holder.attr("warning-message");
		}
		swal({
			title: title,
			text: message,
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#007bff",
			confirmButtonText: button,
			closeOnConfirm: false
		}, function () {
			if (holder.attr("type") === "form") {
				event.stopPropagation();
				let inputs = '';
				dataArray.forEach(function (item) {
					var singleItem = item.split(":");
					inputs += '<input type="hidden" name="' + singleItem[0] + '" value="' + singleItem[1] + '" />';
				});
				$("body").append('<form action="'+url+'" method="post" id="a_poster">'+inputs+'</form>');
				$("#a_poster").submit();
			}
			else{
				server({
					url: url,
					data: data,
					loader: loader
				});
			}
		});
	}
})
/*
 * send to server on change
 *
 */
$("body").on("change", ".send-to-server-change", function (event) {
	event.preventDefault();
	var holder = $(this);
	var extradata = holder.attr("extradata"),
		  url = holder.attr("url"),
		  fieldName = holder.attr("name"),
		  fieldValue = holder.val(),
		  url = holder.attr("url"),
		  loader = true;

	var data = {};
	data[fieldName] = fieldValue;

	if (holder.attr("extradata") !== undefined) {
		// format data 
		var dataArray = extradata.split("|");
		dataArray.forEach(function (item) {
			var singleItem = item.split(":");
			data[singleItem[0]] = singleItem[1];
		});
	}
	if (holder.attr("loader") === "true") {
		loader = true;
	} else if (holder.attr("loader") === "false") {
		loader = false;
	}

	server({
		url: url,
		data: data,
		loader: loader
	});
})
/*
 * fetch from server and display
 *
 *@param {any data type}
 */
$("body").on("click", ".fetch-display-click", function (event) {
	event.preventDefault();
	var holder = $(this);
	var data = holder.attr("data"),
		url = holder.attr("url"),
		dataHolder = holder.attr("holder"),
		loader = true;
	if (data !== undefined) {
		// format data 
		var dataArray = data.split("|");
		var data = {};
		dataArray.forEach(function (item) {
			var singleItem = item.split(":");
			data[singleItem[0]] = singleItem[1];
		});
	}
	if (holder.attr("loader") === "true") {
		loader = true;
	} else if (holder.attr("loader") === "false") {
		loader = false;
	}
	if (loader) {
		showLoader();
		$(dataHolder).html('<div class="loader-box"><div class="circle-loader"></div></div>');
	}
	var posting = $.post(url, data);
	posting.done(function (response) {
		if (loader) {
			hideLoader()
		}
		if (holder.attr("modal") !== undefined) {
			$(holder.attr("modal")).modal({show: true, backdrop: 'static', keyboard: false});
		}
		$(dataHolder).html(response);
	});
});

$("body").on("click", ".a-form-post", function (event) {
	event.stopPropagation();
	event.preventDefault();
	var holder = $(this);
	var data = holder.attr("data"),
		url = holder.attr("url");
	if (data !== undefined) {
		// format data
		var dataArray = data.split("|");
		let inputs = '';
		dataArray.forEach(function (item) {
			var singleItem = item.split(":");
			inputs += '<input type="hidden" name="' + singleItem[0] + '" value="' + singleItem[1] + '" />';
		});
		$("body").append('<form action="'+url+'" method="post" id="a_poster">'+inputs+'</form>');
		$("#a_poster").submit();
	}
});
/*
 * handle response from server
 *
 *@param {json}
 */
function serverResponse(response, modal) {
	if (response.notify === undefined) {
		response.notify = true;
	}
	if (response.notifyType === undefined) {
		response.notifyType = "swal";
	}
	if (response.status === undefined) {
		response.status = "info";
	}
	if (response.title === undefined) {
		response.title = "Hello!";
	}
	if (response.buttonText === undefined) {
		response.buttonText = "Okay";
	}
	if (response.callback !== undefined && response.callbackTime === undefined) {
		if (!response.notify || response.notifyType === "toastr") {
			response.callbackTime = "instant";
		} else if (response.notify && response.notifyType === "swal") {
			response.callbackTime = "onconfirm";
		}
	}
	if (response.showCancelButton === undefined) {
		response.showCancelButton = false;
	}
	if (response.message === undefined) {
		response.message = "";
	}
	if (response.callbackTime === "instant" && response.callback !== undefined) {
		eval(response.callback);
	}
	if (response.notify) {
		if (response.notifyType === "swal") {
			if (response.callbackTime === "onconfirm" && response.callback !== undefined) {
				swal({
					title: response.title,
					text: response.message,
					type: response.status,
					showCancelButton: response.showCancelButton,
					confirmButtonColor: "#007bff",
					confirmButtonText: response.buttonText,
					closeOnConfirm: true
				}, function () {
					swal.close();
					eval(response.callback);
				});
			} else {
				swal({
					title: response.title,
					text: response.message,
					type: response.status,
					showCancelButton: response.showCancelButton,
					confirmButtonColor: "#007bff",
					confirmButtonText: response.buttonText,
					closeOnConfirm: true
				});
			}
		} else if (response.notifyType === "toastr") {
			swal.close();
			if (response.status === "success") {
				toastr.success(response.message, response.title);
			} else if (response.status === "error") {
				toastr.error(response.message, response.title);
			} else if (response.status === "info") {
				toastr.info(response.message, response.title);
			} else if (response.status === "warning") {
				toastr.warning(response.message, response.title);
			}
		}
	} else {
		if(modal==true){
			swal.close();
		}
	}
}
/*
 * submit form
 */
$("body").on("submit", ".simcy-form", function (event) {
	event.preventDefault();
	var loader = false;
	if ($(this).attr("loader") === "true") {
		loader = true;
	}
	$(this).parsley().validate();
	if (($(this).parsley().isValid())) {
		if (loader) {
			showLoader();
		}
		$.ajax({
			url: $(this).attr("action"),
			type: $(this).attr("method"),
			data: new FormData(this),
			contentType: false,
			processData: false,
			success: function (response) {
				if (loader) {
					hideLoader();
				}
				serverResponse(response, false);
			},
			error: function (xhr, status, error) {
				console.log(error);
				if (loader) {
					hideLoader();
				}
				toastr.error(error, "Oops!");
			}
		});
	}
});
/*
 * form with js callback
 */
$(".simcy-front-form").submit(function(event){
  event.preventDefault();
  $(this).parsley().validate();
  if (($(this).parsley().isValid())) {
    eval($(this).attr("callback"));
  }
});
/*
 * render cropie js DOM
 */
function croppify() {
	$('.croppie').each(function() {
		var input = $(this),
		      inputName = input.attr("name"),
		      defaultImage = "data:image/png;base64,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SLryuH7vvfea7777znzwwQempKTEHD9+fBsHFAwmDf105swZbs+beEFgOo8f7Fk0K69FXoeARmcE4IdRa2trswD/888/VoMGGoZ7APnVV1+1NnLqjASdg6BREEwKCMt9vF7RloGZYW+Q/3i1cj/fM7uQKnwHSf/77z9LFl7j4XpnKoM0PLQPPfSQoT3BlBh1BI8rV65YDMAkG5KpPPLGLMLGf/HFF83rr79uFQMPFIqANxSaHfs8/KbLRp32ysOLQRyN4HX39ddfm08//dS2CdsNzUHn//777+bff/+19lzQYDqYETik//nnn63t99prr9mvAR1Nim3MAARzIdBmpGtqarLTUrxyn376afPEE09Y7cODApH4OyydnZ2WxO+++659PZN/WLAv33rrLXsJs4WOD9eb13a6MtD8DCJpM3mWl5dvZ4tN/OOPP5p33nnHmjXbXxzgw27lMSjG7AInfoJBMYTGHn/77bftQ4nJ1i1mVr7EO8cmaAkkPNLlGq/C8KsxABDSobkCggbX9/rNwIo0gelAPmhiiJ9J+D7VZHn//fdtZzPYSq1jar0zlUH9gzdDatnpyky9527/Ti2PMng4X375Zds+ZlzOy2zNm2++aWd7yJ+3IXjRxnyKNxo4ACVM3N2uBd9FtRWDqaJwPruRl/tSyRuk5fd+6k1d05WRibzku1uZfB9FUsvjweJhwzxiDr5XxhCYQeE3TfjtEKXMqGm808BRG3pY6bC90Uzhzj6suhykXMYd2P8QmdkZBnH51rbp6q8EToeKXvMGAS9mIbxBUyuadwSUwHmHXAvMJgJK4GyiqXnlHQElcN4h1wKziYASOJtoal55R0AJnHfItcBsIqAEziaamlfeEVAC5x1yLTCbCCiBs4mm5pV3BJTAeYdcC8wmAkrgbKKpeeUdASVw3iHXArOJgBI4m2hqXnlHQAmcd8i1wGwioATOJpqaV94R+D+2nGx4HuI9swAAAABJRU5ErkJggg==",
		      inputId = random();
		if (input.attr("default") !== undefined) {
			defaultImage = input.attr("default");
		}
		input.removeAttr("name");
		input.attr("croppie-id",".croppie-"+inputId);
		input.after('<label class="croppie-cabinet croppie-'+inputId+'"><div class="croppie-figure"><img src="'+defaultImage+'" class="croppie-output" /><div class="croppie-cabinet-overlay"><div class="croppie-upload-icon"></div></div></div><input type="hidden" name="'+inputName+'"></label>');
		var inputHidden = $(".croppie-"+inputId).find("input");
		input.insertAfter(inputHidden);
	})
}

/*
 * process cropie js image
 */
var $uploadCrop, tempFilename, rawImg, imageId, croppieId;
// var cropWidth = 
function readFile(input) {
    // showLoader();
    if (input.files && input.files[0]) {
        document.getElementsByTagName("input")[0].removeAttribute("croppie-status");
        var reader = new FileReader(),
            cropWidth = 300,
            cropHeight = 300,
            croppieId = input.getAttribute("croppie-id");
        reader.onload = function(e) {
            // $('body').find(".croppie-box").remove();
            $("body").append('<div class="croppie-overlay"><div class="croppie-overlay-close"><div></div></div><div class="croppie-overlay-save"><div></div></div><div class="croppie-box ready"></div></div>');
            hideLoader();
            input.setAttribute("croppie-status", "active");
            rawImg = e.target.result;
            if (input.getAttribute("crop-width")) {
                cropWidth = parseInt(input.getAttribute("crop-width"));
            }
            if (input.getAttribute("crop-height")) {
                cropHeight = parseInt(input.getAttribute("crop-height"));
            }
			var ua = window.navigator.userAgent;
			var msie = ua.indexOf("MSIE ");
			var enforceBoundary = true;
			if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))  // If Internet Explorer, return version number
			{
				enforceBoundary = true;
			}
			else // If another browser, return 0
			{
				enforceBoundary = false;
			}

			$uploadCrop = $(".croppie-box").croppie({
                viewport: {
                    width: cropWidth,
                    height: cropHeight,
                },
                boundary: {
                    width: parseInt(cropWidth + 100),
                    height: parseInt(cropHeight + 100)
                },
                enforceBoundary: enforceBoundary,
                enableExif: true,
                showZoomer: false
            });


            $uploadCrop.croppie('bind', {
                url: rawImg
            }).then(function() {});
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        // notify("Oops!", "you're browser doesn't support the FileReader API", "error");
    }
}

$('body').on('click', '.croppie-overlay-close', function() {
    $(".croppie-overlay").remove();
});

$('body').on('change', '.croppie', function(ev) {
    imageId = $(this).data('id');
    tempFilename = $(this).val();
    $('.croppie-overlay-close').data('id', imageId);
    readFile(this);
});

$('body').on('click', '.croppie-overlay-save', function(ev) {
    var croppieInput = $("body").find("input[croppie-status=active]");
    $uploadCrop.croppie('result', {
        type: 'base64',
        format: 'png',
        size: {
            width: parseInt(croppieInput.attr("crop-width")),
            height: parseInt(croppieInput.attr("crop-height"))
        }
    }).then(function(resp) {
        croppieInput.closest(".croppie-cabinet").find('img').attr('src', resp);
        croppieInput.siblings("input[type=hidden]").val(resp);
        $("input").removeAttr("croppie-status");
        $(".croppie-overlay").remove();
    });
});
