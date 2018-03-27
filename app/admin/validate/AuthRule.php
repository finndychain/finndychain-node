<?php
namespace app\admin\validate;
use think\Validate;
class AuthRule extends Validate
{
    public function __construct(array $rules = [], array $message = [], array $field = [])
    {
        parent::__construct($rules, $message, $field);
        mb_internal_encoding('utf-8');
    }

    protected $rule = [
        'title' => 'require',
        'name'=> 'require|unique:auth_rule',
    ];
    protected $message =[
        'title.require'=>'权限名称不能为空',
        'name.require'=>'权限内容不能为空',
        'name.unique'=>'该权限规则已存在,请重新输入',
    ];
    protected $scene = [
        'add' => ['title','name'],
        'edit' => ['title','name'],
    ];
}

