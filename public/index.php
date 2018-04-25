<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
//header("Content-type: text/html; charset=utf-8");
if (version_compare(PHP_VERSION, '5.5', '<')) {
    die('PHP版本过低，最少需要PHP5.5，请升级PHP版本！');
}

// 定义应用目录
define('APP_PATH', __DIR__ . '/../app/');

// 定义应用目录
define('APP_COMMON_PATH', APP_PATH . 'common/');

// 定义应用目录
define('PUBLIC_PATH', '');
define('UPLOAD_PATH', __DIR__.'/uploads');

// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';

