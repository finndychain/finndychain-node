<?php
/*
 +----------------------------------------------------------------------
 | Copyright (c) 2017  All rights reserved.
 +----------------------------------------------------------------------
 | Author: Andy
 +----------------------------------------------------------------------
 | CreateDate:  18/01/15 下午1:42
 +----------------------------------------------------------------------
 +----------------------------------------------------------------------
*/
// 安装配置验证
namespace app\install\validate;

use think\Validate;

class InstallConfig extends Validate
{
    // 验证规则
    protected $rule = [
        //管理员信息验证规则
        'admin_username'       => 'require|length:1,32|regex:^(?!_)(?!\d)(?!.*?_$)[\w]+$',
        'admin_password'       => 'require|length:6,30',
        'admin_repassword'     => 'require|confirm:admin_password',
        'admin_email'          => 'require|email',
        //网站信息验证规则
        'web_site_title'       => 'require',
        'index_url'            => 'require',
        'web_site_description' => 'require',
        'web_site_keyword'     => 'require',
        //数据库验证规则
        'type'                 => 'require',
        'hostname'             => 'require',
        'database'             => 'require',
        'username'             => 'require',
        'password'             => 'require|length:6,30',
        'hostport'             => 'require|number|gt:0',
        'prefix'               => 'require',
    ];

    protected $message = [
        'admin_username.require'   => '请填写用户名',
        'admin_username.length'    => '用户名长度为1-32个字符',
        'admin_username.regex'     => '用户名只可含有数字、字母、下划线且不以下划线开头结尾，不以数字开头！',
        'admin_password.require'   => '请填写密码',
        'admin_password.length'    => '密码长度为6-30位',
        'admin_repassword.require' =>'请填写重复密码',
        'admin_repassword.confirm' =>'两次输入的密码不一致',
        'admin_email.require'      => '请填写邮箱',
        'admin_email.email'        => '邮箱格式不正确',

        'web_site_title.require'   => '请填写完整网站名称',
        'index_url.require'        => '请填写完整网站域名',
        'web_site_description.require'   => '请填写完整网站描述信息',
        'web_site_keyword.require'       => '请填写完整网站关键词',

        'type.require'     => '请填写正确的数据库类型',
        'hostname.require' => '请填写正确的数据库主机',
        'database.require' => '请填写正确的数据库实例',
        'username.require' => '请填写正确的数据库用户',
        'password.require' => '请填写正确的数据库密码',
        'password.length'    => '数据库密码长度为6-30位',
        'hostport.require' => '请填写正确的数据库端口',
        'prefix.require'   => '请填写正确的数据库表前缀',

    ];

    protected $scene=[
        'admin_info' => ['admin_username','admin_password','admin_repassword','admin_email'],
        'web_config' => ['web_site_title','index_url','web_site_description','web_site_keyword'],
        'db_config' => ['type','hostname','database','username','password','hostport','prefix'],
    ];
}