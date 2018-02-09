<?php
//配置文件
return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------
    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'view_suffix'  => 'html',
    ],

    'view_replace_str'  =>  [
        '__PUBLIC__'=>'/public/',
        '__ROOT__' => '/',
        '__ADMIN__' => '/static/admin',
    ],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => APP_COMMON_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => APP_COMMON_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'robot_debug_url'    => 'http://www.finndy.com/finndyrobottest.php?op=debug',
    'api_url'    => 'http://www.finndy.test/api/robot/',

    //最大分页数
    'maxpages'    => 500,
    'pagesize'    => 20,
    'api_url'    => 'http://www.finndy.test/api/robot/',

];