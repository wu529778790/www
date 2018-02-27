//斗图终结者
//表情搜索功能核心模块
//本搜索功能的图片全部来自搜狗表情搜索（http://pic.sogou.com/pic/emo/）
//2016-12-11

var setting = {};
setting.info = {
    offset: 0,  //偏移量
    key: '表情',    //搜索词
    wd: '',     //不包含“表情”关键词的搜索词
    canload: false, //是否可继续加载
};

//搜索被提交
$("#search").submit(function(e){
    setting.info.key = $("#query").val() + " 表情";
    setting.info.wd = $("#query").val();
    setting.info.offset = 0;
    $("#mainList").html('');
    $("#loadMore").html("疯狂加载中");
    $("#loadMore").show();
    loadPic();
    return false;
});

//热搜词被点击
function search(obj){
    $("#query").val($(obj).html());
	$("#search").submit();
}

//ajax加载返回图像
function loadPic()
{
    setting.info.canload = false;   //还没加载出来，不许触发
    $("#loadMore").html("疯狂加载中……");
    $.ajax({                                //调用jquery的ajax方法
        type: "POST",                       //设置ajax方法提交数据的形式   
        url: "//pic.sogou.com/pics/json.jsp",                      //把数据提交
        data: "query=" + setting.info.key + "&st=5&start=" + setting.info.offset + "&xml_len=60&reqFrom=wap_result&",
        dataType : "jsonp",
        jsonp: "callback",//回调参数名
        //jsonpCallback:"mkPlayerCallBack",//函数名
        success: function(jsonData){             //提交成功后的回调，msg变量是ok.php输出的内容。   
            var html;
            for(i=0; i<eval(jsonData.items).length; i++)
            {
                //console.log(unescape(jsonData.items[i].title.replace(/\\u/g, '%u')));//unescape(jsonData.items[i].title.replace(/\\u/g, '%u'))
                html  = '<div class="col-xs-6 col-sm-4 col-md-4 col-lg-2 single">';
                html += '<img src="' + mkSiteInfo.siteUrl + '/images/loading.gif" data-original="'+jsonData.items[i].locImageLink+'" class="img-thumbnail lazyload load' + setting.info.offset + '" title="'+safeStr(unescape(jsonData.items[i].title.replace(/\\u/g, '%u')))+'">';
                html += '</div>';
                $("#mainList").append(html);
            }
            
            $('.load' + setting.info.offset).lazyload({
                effect: "show",     // effect(特效),值有show(直接显示),fadeIn(淡入),slideDown(下拉)等,常用fadeIn
                failurelimit: 5,    //加载N张可加区域外的图片
                //load:$(this).css('height',$(this).css('width')),     //加载完的回调函数
                threshold : 180 //距离屏幕180px即开始加载
                //placeholder : "img/grey.gif", //用图片提前占位
            });
            
            $('.load' + setting.info.offset).css('height',$('.load' + setting.info.offset).css('width'));   //修正高度
            
            setting.info.offset += eval(jsonData.items).length; //更新偏移量
            
            if(jsonData.totalNum > 0)
            {
                $("#loadMore").html("点击加载更多");
                setting.info.canload = true;    //又可以加载了
                $(".hottag").hide();    //隐藏热门标签
            }
            else
            {
                $("#loadMore").html("没找到相关图片，请改变关键词");
                $(".hottag").show();    //提供热门标签
            }
        }
    });//ajax
    
    
    history.pushState({}, '表情搜索', 'search.php?wd='+setting.info.wd); //浏览器地址改变
}

window.onresize = function() {
    $(".lazyload").css('height',$(".lazyload").css('width'));   //修正高度
};

$(function(){
    history.pushState({}, '表情搜索', 'search.php'); //浏览器地址改变
    
    if($("#query").val() !== '') //有传递的参数
    {
        $("#search").submit();
    }
    else
    {
        $("#query").focus();
    }
    
    $(window).scroll(function() { 
        if(setting.info.canload === false) return true;  //还没搜索过，不允许加载
        
        //当内容滚动到底部时加载新的内容  
        if ($(this).scrollTop() + $(window).height() + 20 >= $(document).height() && $(this).scrollTop() > 20) {  
            loadPic();  
        }  
    });  
}); 

function GetQueryString(name)   //获取get参数
{
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if(r !== null) 
        return decodeURI(r[2]); 
    return null;
}

//针对浏览器前进、后退的处理
window.addEventListener("popstate", function() {
    var currentState = history.state;
    
    var getWd = safeStr(GetQueryString("wd")); //获取参数
    
    if(getWd == setting.info.wd) return true;   //是一样的，别管他！
    
    if(getWd !== '' && getWd !== null)
    {
        $("#query").val(getWd);
        $("#search").submit();
    }
    else
    {
        $("#mainList").html('');    //清空原有的值
        $("#loadMore").hide();
        $(".hottag").show();    //显示热门标签
        $("#query").val('');
        $("#query").focus();
    }
});

function safeStr(str){
    return str.replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}