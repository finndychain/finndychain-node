<?php
namespace app\admin\validate;
use think\Validate;
class Robot extends Validate
{
    protected $rule = [
        'name' => 'require|min:10|max:50',
        'description' => 'require|min:3',
        'sourcesitename' => 'require',
        'import' => 'require',
        'importtext' => 'require',


    ];
    protected $message =[
        'name.require'=>'数据源名称字数10个字以上并且50个字以下',
        'name.min'=>'数据源名称字数10个字以上并且50个字以下',
        'name.max'=>'数据源名称字数10个字以上并且50个字以下',
        'description.require'=>'源描述长度不符合要求',
        'description.min'=>'源描述长度不符合要求',
        'sourcesitename.require'=>'来源网站不能为空',
        'import.require'=>'请选择一个数据分类',
        'importtext.require'=>'请输入正确的数据源规则',
    ];
    protected $scene = [
        'add' => ['name','description','sourcesitename','import'],
        'edit' => ['name','description','sourcesitename','import'],
        'copy' => ['name','description','sourcesitename','import'],
        'importcopy' => ['importtext'],
    ];
}

