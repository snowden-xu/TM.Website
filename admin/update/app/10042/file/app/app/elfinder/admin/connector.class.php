<?php

defined('IN_MET') or exit('No permission');
error_reporting(0); // Set E_ALL for debuging

load::sys_class('admin');
//必要的
load::own_class('admin/elfile/elFinderConnector');
load::own_class('admin/elfile/elFinder');
load::own_class('admin/elfile/elFinderVolumeDriver');
load::own_class('admin/elfile/elFinderVolumeLocalFileSystem');

//连接器
class connector extends admin {

    private $opts = array();      //全局配置参数

    public function __construct() {
        global $_M;
        parent::__construct();
    }

    //连接器配置
    public function doelfinder() {
        global $_M;
        
        function access($attr, $path, $data, $volume) {
            return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
                ? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
                : null;                                    // else elFinder decide it itself
        }

        //安全mimetype
        $mimetype = $this->uploadmimetype();
        
        //调试
//        $this->opts['debug'] = true;
        //配置参数
        $this->opts['roots'] = array(
            array(
                'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
                'path'          => '../upload/',                // path to files (REQUIRED)
                'URL'           => $_M[url][site].'upload/',                // URL to files (REQUIRED)
                'uploadDeny'    => array('all'),                // All Mimetypes not allowed to upload
                'uploadAllow'   => $mimetype,                   // Mimetype `image` and `text/plain` allowed to upload
                'uploadOrder'   => array('deny', 'allow'),      // allowed Mimetype `image` and `text/plain` only
                'uploadMaxSize' => $_M['config']['met_file_maxsize'].'M',   //上传文件大小限制
                'archiveMimes'  => $mimetype,                    //创建文件类型
                'accessControl' => 'access',                     // disable and hide dot starting files (OPTIONAL)
                'alias'         => 'upload',
                'encoding'      => $this->encoding()
            )
        );

        //插件
        $this->plugin();

        // run elFinder
        $connector = new elFinderConnector(new elFinder($this->opts));
        $connector->run();
        
    }

    //mimetype
    private function uploadmimetype() {
        global $_M;
        $type = stringto_array($_M['config']['met_file_format'] . '|', '|');
        $mime = elFinderVolumeDriver::getMimeTable();
        foreach ($type as $val) {
			if($val == 'php' || $val == 'asp' || $val == 'aspx' || $val == 'jsp' || $val == 'asa' || $val == 'js')continue;
            $v = $mime[$val];
            if ($v)
                $mimetype .= $v . '|';
        }
        return stringto_array($mimetype . '|', '|');
    }

    //插件
    private function plugin() {
        global $_M;

        //插件开关
        $this->opts['bind'] = array(
            'upload.presave' => array(
//                    'Plugin.AutoResize.onUpLoadPreSave',  //自动调整
//                    'Plugin.Watermark.onUpLoadPreSave',   //水印插件
//                    'Plugin.Normalizer.onUpLoadPreSave',  //文件名称和文件路径
                    'Plugin.Sanitizer.onUpLoadPreSave',
            ),
            'upload.pre mkdir.pre mkfile.pre rename.pre archive.pre' => array(
//                    'Plugin.Normalizer.cmdPreprocess',       //文件名称和文件路径
                    'Plugin.Sanitizer.cmdPreprocess',
            ),
//            'upload.presave' => array()
            
        );

        //插件全局参数
        $this->opts['plugin'] = array(
                //自动调整
//            'AutoResize' => array(
//                'enable'         => true,       // For control by volume driver
//                'maxWidth'       => 6024,       // Path to Water mark image
//                'maxHeight'      => 6024,       // Margin right pixel
//                'quality'        => 95,         // JPEG image save quality
//                'preserveExif'   => false,      // Preserve EXIF data (Imagick only)
//                'targetType'     => IMG_GIF|IMG_JPG|IMG_PNG|IMG_WBMP // Target image formats ( bit-field )
//            ),
                //水印
//            'Watermark' => array(
//                'enable'         => true,       // For control by volume driver
//                'source'         => 'logo.png', // Path to Water mark image
//                'marginRight'    => 5,          // Margin right pixel
//                'marginBottom'   => 5,          // Margin bottom pixel
//                'quality'        => 95,         // JPEG image save quality
//                'transparency'   => 70,         // Water mark image transparency ( other than PNG )
//                'targetType'     => IMG_GIF|IMG_JPG|IMG_PNG|IMG_WBMP, // Target image formats ( bit-field )
//                'targetMinPixel' => 200         // Target image minimum pixel size
//            ),
//                文件名称和文件路径
//            'Normalizer' => array(
//                'enable'    => true,  // For control by volume driver 音量驱动控制
//                'nfc'       => true,  // Canonical Decomposition followed by Canonical Composition
//                'nfkc'      => true,  // Compatibility Decomposition followed by Canonical
//                'lowercase' => false, // Make chars lowercase
//                'convmap'   => array()// Convert map ('FROM' => 'TO') array
//            ),
                
            'Sanitizer' => array(
                'enable' => true,
                'targets'  => array('\\','/',':','*','?','"','<','>','|'), // target chars
                'replace'  => '_'    // replace to this
            )
        );
    }
    
    ///WINDOWS系统下 文件名转码，解决中文名UTF-8编码问题
    private function encoding() {
        global $_M;
        return is_strinclude(php_uname('s'), 'win')?'GBK':'UTF-8';
    }
    
    

}
