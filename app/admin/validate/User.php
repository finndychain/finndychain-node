<?php
namespace app\admin\validate;
use think\Validate;
class User extends Validate
{
    public function __construct(array $rules = [], array $message = [], array $field = [])
    {
        parent::__construct($rules, $message, $field);
        mb_internal_encoding('utf-8');
    }

    protected $rule = [
        'username' => 'require|max:10',
        'password'=> 'require|min:6|max:16',
        'set_oldpass'=> 'require|max:16|min:6',
        'set_newpass'=> 'require|max:16|min:6',
        'set_okpass'=> 'require|max:16|min:6',

    ];
    protected $message =[
        'username.require'=>'用户名不能为空',
        'username.max'=>'用户名不能超过10位',
        'password.require'=>'密码不能为空',
        'password.max'=>'密码长度不能超过16位',
        'password.min'=>'密码长度不能少于6位',
        'set_oldpass.require'=>'旧密码不能为空',
        'set_oldpass.max'=>'密码长度不能超过16位',
        'set_oldpass.min'=>'密码长度不能少于6位',
        'set_newpass.require'=>'新密码不能为空',
        'set_newpass.max'=>'密码长度不能超过16位',
        'set_newpass.min'=>'密码长度不能少于6位',
        'set_okpass.require'=>'密码不能为空',
        'set_okpass.max'=>'密码长度不能超过16位',
        'set_okpass.min'=>'密码长度不能少于6位',
    ];
    protected $scene = [
        'AddUser' => ['username','password'],
        'Resetpwd' => ['set_oldpass','set_newpass','set_okpass'],
    ];
}

