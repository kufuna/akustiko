var escapeSelector=$.ui.escapeSelector=(function(){var selectorEscape=/([!"#$%&'()*+,./:;<=>?@[\]^`{|}~])/g;return function(selector){return selector.replace(selectorEscape,"\\$1")}})();
/*!
 * jQuery UI Labels 1.12.1
 * http://jqueryui.com
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 */
var labels=$.fn.labels=function(){var ancestor,selector,id,labels,ancestors;if(this[0].labels&&this[0].labels.length){return this.pushStack(this[0].labels)}
labels=this.eq(0).parents("label");id=this.attr("id");if(id){ancestor=this.eq(0).parents().last();ancestors=ancestor.add(ancestor.length?ancestor.siblings():this.siblings());selector="label[for='"+$.ui.escapeSelector(id)+"']";labels=labels.add(ancestors.find(selector).addBack(selector))}
return this.pushStack(labels)}