<?php
namespace app\admin\validate;
use think\Validate;
class User extends Validate
{
    protected $rule = [
        'username' => 'require|max:20',
        'password'=> 'require|max:16'
    ];
    protected $message =[
        'username.require'=>'用户名不能为空',
        'username.max'=>'用户名不能超过20位',
        'password.require'=>'密码不能为空',
        'password.max'=>'密码长度不能查过16',
    ];
    protected $scene = [
        'AddUser' => ['username','password']
    ];
}

