<?php
//配置文件
return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------


    // 视图输出字符串内容替换
    'view_replace_str'       => [
        '__ROOT__'=>PUBLIC_PATH,
        '__STATIC__'=> PUBLIC_PATH.'/static',
        '__CSS__'=> PUBLIC_PATH.'/static/css',
        '__JS__'=> PUBLIC_PATH.'/static/js',
        '__IMG__'=> PUBLIC_PATH.'/static/img',
        '__CDN__'=>'',
    ],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => APP_COMMON_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => APP_COMMON_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

];