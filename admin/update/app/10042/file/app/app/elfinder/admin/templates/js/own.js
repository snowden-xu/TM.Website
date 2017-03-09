define(function (require, exports, module) {
    
    //siteurl全局根目录
    //getQueryString('n')应用目录名
    var common = require('common'); //加载公共函数文件（语言文字获取等）
    var langtxt = common.langtxt(); //获取语言文字
    
    //公用的
    var gcss    = 'own/admin/templates/css/';
    var gjs     = 'own/admin/templates/js/';
    
    //私有的
    var fcss    = 'own/admin/elfile/css/';
    var fjs     = 'own/admin/elfile/js/';
    
    //jQuery and jQuery UI (REQUIRED)
    require.async(fcss + 'jquery-ui.min.css');
    require.async(fjs + 'jquery-ui.min');
    
    //elfinder CSS (REQUIRED)
    require.async(fcss + 'elfinder.min.css');
    //默认样式
    require.async(fcss + 'theme.css');
    
    //elfinder JS (REQUIRED)
    require.async(fjs + 'elfinder.min',function() {
        //lang语言
        require.async(gjs + 'lang',function() {
            //进行加固语言
            if(typeof(langarr) == 'undefined'){
                var yunlang = phplang;
            }else{
                var yunlang = langarr[lang]?langarr[lang]:phplang;
            }

            //elfinder translation (OPTIONAL)
            require.async(fjs + 'i18n/elfinder.' + yunlang);

            //elfinder initialization (REQUIRED)
            $(document).ready(function() {
                $('#elfinder').elfinder({
                    ui:['toolbar', 'places', 'tree', 'path', 'stat'],
                    height : '710',
                    lang: yunlang,             // language (OPTIONAL)
                    url : own_name + 'c=connector&a=doelfinder',  // connector URL (REQUIRED)
                });
            });
        })
    });
    
    //elfinder css modify
    require.async(gcss + 'yunel.css');
    
});
