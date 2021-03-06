/*  
 * JCE Editor                 2.2.6
 * @package                 JCE
 * @url                     http://www.joomlacontenteditor.net
 * @copyright               Copyright (C) 2006 - 2012 Ryan Demmer. All rights reserved
 * @license                 GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
 * @date                    19 August 2012
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * NOTE : Javascript files have been compressed for speed and can be uncompressed using http://jsbeautifier.org/
 */
(function(){var each=tinymce.each,JSON=tinymce.util.JSON,Node=tinymce.html.Node,Entities=tinymce.html.Entities;tinymce.create('tinymce.plugins.CodePlugin',{init:function(ed,url){var self=this;this.editor=ed;this.url=url;ed.onPreInit.add(function(){if(ed.getParam('code_style')){ed.schema.addValidElements('style[scoped|*]');ed.schema.addValidChildren('+body[style]');}
ed.parser.addNodeFilter('script,style',function(nodes){for(var i=0,len=nodes.length;i<len;i++){self._serializeSpan(nodes[i]);}});ed.parser.addNodeFilter('noscript',function(nodes){for(var i=0,len=nodes.length;i<len;i++){self._serializeNoScript(nodes[i]);}});ed.serializer.addNodeFilter('span,script,div',function(nodes,name,args){for(var i=0,len=nodes.length;i<len;i++){var node=nodes[i];if(node.name=='span'&&/mceItemScript/.test(node.attr('class'))){self._buildScript(node);}
if(node.name=='span'&&/mceItemStyle/.test(node.attr('class'))){self._buildStyle(node);}
if(node.name=='div'&&node.attr('data-mce-type')=='noscript'){self._buildNoScript(node);}}});});ed.onInit.add(function(){if(ed.theme&&ed.theme.onResolveName){ed.theme.onResolveName.add(function(theme,o){if(o.name==='span'&&/mceItemScript/.test(o.node.className)){o.name='script';}
if(o.name==='span'&&/mceItemStyle/.test(o.node.className)){o.name='style';}
if(o.name==='span'&&/mcePhp/.test(o.node.className)){o.name='php';}});}
if(ed.settings.content_css!==false)
ed.dom.loadCSS(url+"/css/content.css");});ed.onBeforeSetContent.add(function(ed,o){if(/<(\?|script|style)/.test(o.content)){if(!ed.getParam('code_script')){o.content=o.content.replace(/<script[^>]*>([\s\S]*?)<\/script>/gi,'');}
if(!ed.getParam('code_style')){o.content=o.content.replace(/<style[^>]*>([\s\S]*?)<\/style>/gi,'');}
if(!ed.getParam('code_php')){o.content=o.content.replace(/<\?(php)?([\s\S]*?)\?>/gi,'');}
o.content=o.content.replace(/\="([^"]+?)"/g,function(a,b){b=b.replace(/<\?(php)?(.+?)\?>/gi,function(x,y,z){return'{php:start}'+ed.dom.encode(z)+'{php:end}';});return'="'+b+'"';});if(/<textarea/.test(o.content)){o.content=o.content.replace(/<textarea([^>]*)>([\s\S]*?)<\/textarea>/gi,function(a,b,c){c=c.replace(/<\?(php)?(.+?)\?>/gi,function(x,y,z){return'{php:start}'+ed.dom.encode(z)+'{php:end}';});return'<textarea'+b+'>'+c+'</textarea>';});}
o.content=o.content.replace(/<([^>]+)<\?(php)?(.+?)\?>([^>]*?)>/gi,function(a,b,c,d,e){if(b.charAt(b.length)!==' '){b+=' ';}
return'<'+b+'data-mce-php="'+d+'" '+e+'>';});o.content=o.content.replace(/<\?(php)?([\s\S]+?)\?>/gi,'<span class="mcePhp" data-mce-type="php"><!--$2-->\u00a0</span>');o.content=o.content.replace(/<script([^>]+)><\/script>/gi,'<script$1>\u00a0</script>');o.content=o.content.replace(/<script([^>]*)>/gi,function(a,b){var re=/\stype="([^"]+)"/;if(re.test(b)){b=b.replace(re,' data-mce-type="$1"');}else{b+=' data-mce-type="text/javascript"';}
return'<script'+b+'>';});}});ed.onPostProcess.add(function(ed,o){if(o.get){if(/(mcePhp|data-mce-php|\{php:start\})/.test(o.content)){o.content=o.content.replace(/\{php:start\}([^\{]+)\{php:end\}/g,function(a,b){return'<?php'+ed.dom.decode(b)+'?>';});o.content=o.content.replace(/<textarea([^>]*)>([\s\S]*?)<\/textarea>/gi,function(a,b,c){if(/&lt;\?php/.test(c)){c=ed.dom.decode(c);}
return'<textarea'+b+'>'+c+'</textarea>';});o.content=o.content.replace(/data-mce-php="([^"]+?)"/g,function(a,b){return'<?php'+ed.dom.decode(b)+'?>';});o.content=o.content.replace(/<span([^>]+)class="mcePhp"([^>]+)><!--([\s\S]*?)-->(&nbsp;|\u00a0)<\/span>/g,function(a,b,c,d){return'<?php'+ed.dom.decode(d)+'?>';});}}});},_convertCurlyCode:function(content){content=content.replace(/\{([^\}]+)\}([\s\S]+?)\{\/\1\}/,'<span class="mceItemCurlyCode" data-mce-type="code-item">{$1}$2{/$1}</span>');content=content.replace(/\{([^\}]+)\}/,'<span class="mceItemCurlyCode" data-mce-type="code-item">{$1}</span>');},_buildScript:function(n){var self=this,ed=this.editor,v,node,text;if(!n.parent)
return;if(n.firstChild){v=n.firstChild.value;}
p=JSON.parse(n.attr('data-mce-json'))||{};p.type=n.attr('data-mce-type')||p.type||'text/javascript';node=new Node('script',1);if(v){v=tinymce.trim(v);if(v){text=new Node('#text',3);text.raw=true;if(ed.getParam('code_cdata',true)){v='// <![CDATA[\n'+self._clean(tinymce.trim(v))+'\n// ]]>';}
text.value=v;node.append(text);}}
each(p,function(v,k){node.attr(k,v);});n.replace(node);return true;},_buildStyle:function(n){var self=this,ed=this.editor,v,node,text;if(!n.parent)
return;if(n.firstChild){v=n.firstChild.value;}
p=JSON.parse(n.attr('data-mce-json'))||{};p.type='text/css';node=new Node('style',1);if(v){v=tinymce.trim(v);if(v){text=new Node('#text',3);text.raw=true;if(ed.getParam('code_cdata',true)){v='<!--\n'+self._clean(tinymce.trim(v))+'\n-->';}
text.value=v;node.append(text);}}
if(n.parent.name=='head'){p.scoped=null;}else{p.scoped="scoped";}
each(p,function(v,k){node.attr(k,v);});n.replace(node);return true;},_buildNoScript:function(n){var self=this,ed=this.editor,p,node;if(!n.parent)
return;p=JSON.parse(n.attr('data-mce-json'))||{};node=new Node('noscript',1);each(p,function(v,k){node.attr(k,v);});n.wrap(node);n.unwrap();return true;},_serializeSpan:function(n){var self=this,ed=this.editor,dom=ed.dom,v,k,p={};if(!n.parent)
return;each(n.attributes,function(at){if(at.name.indexOf('data-mce-')!==-1||at.name=='type')
return;p[at.name]=at.value;});var span=new Node('span',1);span.attr('class','mceItem'+this._ucfirst(n.name));span.attr('data-mce-json',JSON.serialize(p));span.attr('data-mce-type',n.attr('data-mce-type')||p.type);v=n.firstChild?n.firstChild.value:'';if(v.length){var text=new Node('#comment',8);text.value=this._clean(v);span.append(text);}
span.append(new tinymce.html.Node('#text',3)).value='\u00a0';n.replace(span);},_serializeNoScript:function(n){var self=this,ed=this.editor,dom=ed.dom,v,k,p={};if(!n.parent)
return;each(n.attributes,function(at){if(at.name=='type')
return;p[at.name]=at.value;});var div=new Node('div',1);div.attr('data-mce-json',JSON.serialize(p));div.attr('data-mce-type',n.name);n.wrap(div);n.unwrap();},_ucfirst:function(s){return s.charAt(0).toUpperCase()+s.substring(1);},_clean:function(s){s=s.replace(/(\/\/\s+<!\[CDATA\[)/gi,'\n');s=s.replace(/(<!--\[CDATA\[|\]\]-->)/gi,'\n');s=s.replace(/^[\r\n]*|[\r\n]*$/g,'');s=s.replace(/^\s*(\/\/\s*<!--|\/\/\s*<!\[CDATA\[|<!--|<!\[CDATA\[)[\r\n]*/gi,'');s=s.replace(/\s*(\/\/\s*\]\]>|\/\/\s*-->|\]\]>|-->|\]\]-->)\s*$/g,'');return s;},getInfo:function(){return{longname:'Code',author:'Ryan Demmer',authorurl:'http://www.joomlacontenteditor.net',infourl:'http://www.joomlacontenteditor.net',version:'2.2.6'};}});tinymce.PluginManager.add('code',tinymce.plugins.CodePlugin);})();