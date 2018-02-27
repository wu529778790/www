/*!
 * 麦亚信君康项目,
 *  author: wu529778790
 */
webpackJsonp([6],{340:function(module,exports){module.exports=function(){function camelCase(o){var e=o.replace(/\d/gm,"").replace(/[-|_](\w)/g,function(o,e){return e.toUpperCase()}).replace(/[-|_]/gm,"");return o&&""!==o&&0!=o.length?e.substring(0,1).toLowerCase()+e.substring(1):o}function convertObj(o){return delete o.short_name,o}function convertPolicy(obj){obj="string"==typeof obj?eval("("+obj+")"):obj;var policy={};for(var item in obj)"productId"!==item?function(){var o={};obj[item].forEach(function(e){e=convertObj(e);var t=e.subId||camelCase(e.code);if(e.option){var n={};e.option.forEach(function(o){if("_"!==o.code.substring(0,1)){var e=t.split("_")[0],r=o.code.split("_")[0];t=e!==r?t.replace(e,r):t,n[camelCase(o.code)]=convertObj(o)}}),e.option=n}o[t]=e}),policy[item]=o}():policy[item]=obj[item];return policy}return{convertPolicy:convertPolicy}}()}});
//# sourceMappingURL=6.7366a03364e7fd0e9322.js.map