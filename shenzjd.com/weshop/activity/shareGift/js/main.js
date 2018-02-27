var _basePath = '/meyacom-app/',
	dynamicLoading = {
		css: function(path) {
			if(!path || path.length === 0) {
				throw new Error('argument "path" is required !');
			}
			document.write('<link rel="stylesheet" type="text/css" href="' + _basePath + path + '"/>');
		},
		js: function(path, async) {
			if(!path || path.length === 0) {
				throw new Error('argument "path" is required !');
			}
			document.write('<script src="' + _basePath + path + '"' + (async ? ' async defer' : '') + '></script>');
		},
		eachReverse: function(ary, func) {
			if(ary) {
				var i;
				for(i = ary.length - 1; i > -1; i -= 1) {
					if(ary[i] && func(ary[i], i, ary)) {
						break;
					}
				}
			}
		}
	};
//构建初始变量
var script = document.currentScript || dynamicLoading.eachReverse(document.getElementsByTagName('script'), function(_script) {
	var _src = _script.getAttribute('src');
	if(_src && (_src.lastIndexOf('main.js') != -1)) {
		script = _script;
		script._src = _src;
		return false;
	}
}) || {};
script._css = script.getAttribute('data-css');
script._js = script.getAttribute('data-js');
script._src = script._src || script.getAttribute('src');
script._menu = script.getAttribute('data-menu') || 'true';
script._faskclick = script.getAttribute('data-faskclick') || 'true';
_basePath = (script._src && script._src.substring(0, script._src.indexOf('js'))) || '';

//动态加载 CSS 文件
//dynamicLoading.css("plugs/mui/css/mui.min.css");
//dynamicLoading.css("css/common/main.css");
if(script._css) {
	var array = script._css.split(',');
	for(var i = 0; i < array.length; i++) {
		var _cs = array[i];
		dynamicLoading.css(_cs.lastIndexOf('.css') != -1 ? _cs : (_cs + '.css'));
	}
}

//动态加载 JS 文件
dynamicLoading.js("js/jquery/jquery-2.1.4.min.js");
dynamicLoading.js("js/common/base_compose.js");
dynamicLoading.js("js/common/base_template.js");
dynamicLoading.js("plugs/mui/js/mui.min.js");
dynamicLoading.js("js/common/merge.js");
dynamicLoading.js("js/common/url.js");

if(script._js) {
	var array = script._js.split(',');
	for(var i = 0; i < array.length; i++) {
		var _j = array[i];
		dynamicLoading.js(_j.lastIndexOf('.js') != -1 ? _j : (_j + '.js'));
	}
}