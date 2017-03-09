<?php
/*
 * 应用名称：文件管理器
 * 应用文件：elfinder
 * 描述：在线文件管理功能，复制、剪切、粘贴、移动、删除、下载、重命名；管理已上传的图片、视频、文本文件；图片、文本的编辑、上传等。
 * 版本：1.3
 * elfile版本：2.1.7
 * 作者：YunWang [蝴蝶效应QQ：415420792]
 * 作者URL：http://www.yunwang.wang
 */
defined('IN_MET') or exit ('No permission');

load::sys_class('admin');

//配置
class melfinder extends admin {

    public function __construct() {
        global $_M;
        parent::__construct();
    }

    //初始配置
    public function doelfinder() {
        global $_M;
        $lang   = $this->lang();
        require $this->template('own/index');
    }

    //Lnag
    private function lang() {
        global $_M;
        $lang   = array();
        $lang['cn']   = 'zh_CN';    //中文
        $lang['en']   = 'LANG';     //英文
        $lang['tc']   = 'zh_TW';    //中文繁体
//        $lang['sq']   = '';    //阿尔巴尼亚语
        $lang['ar']   = 'ar';    //阿拉伯语
//        $lang['az']   = '';    //阿塞拜疆语
//        $lang['ga']   = '';    //爱尔兰语
//        $lang['et']   = '';    //爱沙尼亚语
//        $lang['be']   = '';    //白俄罗斯语
        $lang['bg']   = 'bg';    //保加利亚语
//        $lang['is']   = '';    //冰岛语
        $lang['pl']   = 'pl';    //波兰语
        $lang['fa']   = 'fa';    //波斯语
//        $lang['af']   = '';    //布尔语(南非荷兰语)
        $lang['da']   = 'da';    //丹麦语
        $lang['de']   = 'de';    //德语
        $lang['ru']   = 'ru';    //俄语
        $lang['fr']   = 'fr';    //法语
//        $lang['tl']   = '';    //菲律宾语
//        $lang['fi']   = '';    //芬兰语
//        $lang['ht']   = '';    //海地克里奥尔语
        $lang['ko']   = 'ko';    //韩语
        $lang['nl']   = 'nl';    //荷兰语
//        $lang['gl']   = '';    //加利西亚语
        $lang['ca']   = 'ca';    //加泰罗尼亚语
        $lang['cs']   = 'cs';    //捷克语
//        $lang['hr']   = '';    //克罗地亚语
//        $lang['la']   = '';    //拉丁语
//        $lang['lv']   = '';    //拉脱维亚语
//        $lang['lt']   = '';    //立陶宛语
        $lang['ro']   = 'ro';    //罗马尼亚语
//        $lang['mt']   = '';    //马耳他语
//        $lang['ms']   = '';    //马来语
//        $lang['mk']   = '';    //马其顿语
        $lang['no']   = 'no';    //挪威语
        $lang['pt']   = 'pt_BR';    //葡萄牙语[巴西]
        $lang['ja']   = 'jp';    //日语
        $lang['sv']   = 'sv';    //瑞典语
        $lang['sr']   = 'sr';    //塞尔维亚语
        $lang['sk']   = 'sk';    //斯洛伐克语
        $lang['sl']   = 'sl';    //斯洛文尼亚语
//        $lang['sw']   = '';    //斯瓦希里语
//        $lang['th']   = '';    //泰语
        $lang['tr']   = 'tr';    //土耳其语
//        $lang['cy']   = '';    //威尔士语
        $lang['uk']   = 'uk';    //乌克兰语
//        $lang['iw']   = '';    //希伯来语
        $lang['el']   = 'el';    //希腊语
//        $lang['eu']   = '';    //西班牙的巴斯克语
        $lang['es']   = 'es';    //西班牙语
        $lang['hu']   = 'hu';    //匈牙利语
        $lang['it']   = 'it';    //意大利语
//        $lang['yi']   = '';    //意第绪语
//        $lang['ur']   = '';    //乌尔都语
        $lang['id']   = 'id';    //印尼语
        $lang['vi']   = 'vi';    //越南语
        
        //返回语言//fo\he 不知道哪国语言
        $phplang  = $lang[$_M['lang']];
        if($phplang){
           return $phplang;
        }else{
            return 'zh_CN';
        }
    }

}
?>