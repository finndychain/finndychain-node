<?php
namespace app\admin\validate;
use think\Validate;
class Article extends Validate
{
    public function __construct(array $rules = [], array $message = [], array $field = [])
    {
        parent::__construct($rules, $message, $field);
        mb_internal_encoding('utf-8');
    }

    protected $rule = [
        'title' => 'require',
        'keywords' => 'require',
        'content' => 'require',
        'cateid' => 'require',
        'thumb' => 'image|fileExt:jpg,gif,png|fileSize:2048',
    ];
    protected $message =[
        'title.require'=>'文章标题不能为空',
        'keywords.require'=>'关键词不能为空',
        'content.require'=>'文章内容不能为空',
        //'thumb.image'=>'请上传图片',
        'thumb.fileExt'=>'上传的图片为jpg,gif,png',
        'thumb.fileSize'=>'上传的图片大小超过2M',
    ];
    protected $scene = [
        'add' => ['title','keywords','content'],
        'edit' => ['title','keywords','content'],
    ];
}

