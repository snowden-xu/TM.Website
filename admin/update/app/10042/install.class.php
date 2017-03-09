<?php
defined('IN_MET') or exit ('No permission');

load::sys_class('admin');
load::sys_class('nav.class.php');
load::sys_func('file');

//手动安装时，或者发给客户自己手动安装时请将标记 ① ② 的注释去掉

class install //extends admin //标记①
{
    private $no = '10042';              //应用NO值
    private $m_name = 'elfinder';       //应用文件
    private $ver = '1.4';               //应用发布版本号
    
    public function __construct() {
        global $_M;
        //parent::__construct(); //标记②
    }
 
    //安装主方法
    public function dosql() {
        global $_M;
        
        $stall = $this->sqlone('applist');
        if(!$stall){
            //新用户安装
            $this->appsql();
            //安装其他
            $this->mydefault();
        }else{
            /*
             * 版本升级
             * $stall['ver']    旧版本号
             */
            switch ($stall['ver']) {
                case "1.2":
                    $this->ver_13();
                    break;
                default:
                    break;
            }
			$this->ver('1.4');
        }
        
       // echo '安装成功';
       // return '安装成功';
    }
    
    /******************************************************************
     * 
     *                  版本升级
     * 
     * ***************************************************************/
    
    //1.2->1.3
    private function ver_13() {
        $this->ver('1.3');
    }
    
    //全局版本号更新
    private function ver($ver) {
        global $_M;
        DB::query("UPDATE {$_M['table']['applist']} SET ver='{$ver}' where no='{$this->no}' AND m_name='{$this->m_name}' ");
    }
    
    /******************************************************************
     * 
     *                  初始代码
     * 
     * ***************************************************************/
    
    //执行APP相关的表数据插入
    private function appsql() {
        global $_M;
        
        //注册应用
        $field  = "no='{$this->no}',ver='{$this->ver}',m_name='{$this->m_name}',m_class='melfinder',m_action='doelfinder',appname='\$_M[''word''][''{$this->m_name}_name'']',info='\$_M[''word''][''{$this->m_name}_info'']',addtime='1454931774',updatetime='1454931774'";
        $this->addsql('applist',$field);
    }

    //全局默认参数
    private function mydefault() {
        global $_M;
        //安装语言包
        $cl = $this->langjson();
        
    }

    //公共查询方法
    private function sqlone($tname,$where = '') {
        global $_M;
        $table  = $_M['table'][$tname];
        if(!$where){
            $where  = "no='{$this->no}'";
        }
        return DB::get_one("select * from {$table} where {$where}");
    }

    //公共查询方法
    private function sqlall($tname,$where = '') {
        global $_M;
        $table  = $_M['table'][$tname];
        if(!$where){
            $where  = "no='{$this->no}'";
        }
        return DB::get_all("select * from {$table} where {$where}");
    }

    //公共写入方法
    private function addsql($tname,$field = '') {
        global $_M;
        $table  = $_M['table'][$tname];
        DB::query("INSERT INTO {$table} SET {$field}");
    }

    //获取多语言参数数组
    private function langjson() {
        global $_M;
        //获取语言文件内容，对JSon字符串 进行处理，最后转换成数组
        $lang = file_get_contents(PATH_ALL_APP.$this->m_name.'/lang/langsql.php');
        $lang = jsondecode(str_replace(array("\r\n", "\r", "\n", " "), "", $lang));
        foreach ($this->langs() as $kl => $vl){
            foreach ($lang[$vl] as $k => $v) {
                $field    = "name='{$k}',value='{$v}',site='1',no_order='0',array='0',app='{$this->no}',lang='{$vl}'";
                $this->addsql('language',$field);
            }
        }
    }
    
    //基础语言数组
    private function langs() {
        $arr    = array();
        $arr['cn']       = 'cn';
        $arr['en']       = 'en';
        $arr['tc']       = 'tc';
        return $arr;
    }

}
?>