/*!
 * 麦亚信君康项目,
 *  author: wu529778790
 */
webpackJsonp([4],{342:function(e,n){function r(e){for(var n,r=[],a=0,i=0,l="";null!=(n=u.exec(e));){var p=n[0],o=n[1],s=n.index;if(l+=e.slice(i,s),i=s+p.length,o)l+=o[1];else{var f=e[i],c=n[2],g=n[3],h=n[4],v=n[5],x=n[6],$=n[7];l&&(r.push(l),l="");var m=null!=c&&null!=f&&f!==c,w="+"===x||"*"===x,d="?"===x||"*"===x,k=n[2]||"/",E=h||v;r.push({name:g||a++,prefix:c||"",delimiter:k,optional:d,repeat:w,partial:m,asterisk:!!$,pattern:E?E.replace(/([=!:$\/()])/g,"\\$1"):$?".*":"[^"+t(k)+"]+?"})}}return i<e.length&&(l+=e.substr(i)),l&&r.push(l),r}function t(e){return e.replace(/([.+*?=^!:${}()[\]|\/\\])/g,"\\$1")}function a(e,n){var r=e.source.match(/\((?!\?)/g);if(r)for(var t=0;t<r.length;t++)n.push({name:t,prefix:null,delimiter:null,optional:!1,repeat:!1,partial:!1,asterisk:!1,pattern:null});return e}function i(e,n){return l(r(e),n)}function l(e,n){for(var r="",a=0;a<e.length;a++){var i=e[a];if("string"==typeof i)r+=t(i);else{var l=t(i.prefix),p="(?:"+i.pattern+")";n.push(i),i.repeat&&(p+="(?:"+l+p+")*"),p=i.optional?i.partial?l+"("+p+")?":"(?:"+l+"("+p+"))?":l+"("+p+")",r+=p}}var u=t("/");return r=(r.slice(-u.length)===u?r.slice(0,-u.length):r)+"(?:"+u+"(?=$))?$",new RegExp("^"+r,"i")}function p(e,n){return e instanceof RegExp?a(e,n):i(e,n)}e.exports=p;var u=new RegExp(["(\\\\.)","([\\/.])?(?:(?:\\:(\\w+)(?:\\(((?:\\\\.|[^\\\\()])+)\\))?|\\(((?:\\\\.|[^\\\\()])+)\\))([+*?])?|(\\*))"].join("|"),"g")}});
//# sourceMappingURL=4.7b697185a7780d1974f4.js.map