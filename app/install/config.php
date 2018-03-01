<?php
return [
    //产品配置
    'product_name'   => '蓝蜘蛛开源云采集工具', //产品名称
    'company_website_domain' => 'http://www.finndy.com', //官方网址
    'website_domain' => 'http://cloud.finndy.com', //产品网址
    'company_name'   => 'XXX有限公司', //公司名称
    'original_table_prefix'  => 'cloud_', //默认表前缀

    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => APP_COMMON_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => APP_COMMON_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
];