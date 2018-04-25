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
namespace app\home\controller;

use think\Db;

class Index extends Base
{
    public function index()
    {

        return $this->fetch('/index');
    }

    public function upload(){
        $imgUrl = '';
        if($this->request->isPost()){
            //$file = $this->request->file('uploadImg');
            $file = $_FILES['uploadImg'];
            if (empty($file)) {
                $this->error('请选择上传文件');
            }
            $rs = oss_upload_file('50/'.$file['name'],$file['tmp_name']);
            $imgUrl = oss_display_image($rs['savePath'],1);
        }
        $this->assign('img_url',$imgUrl);
        return $this->fetch("/upload");
    }

    protected function remove(){
        $obj = '50/image-6.jpg';
        $a= oss_remove_file($obj);
        var_dump($a);
    }



}
