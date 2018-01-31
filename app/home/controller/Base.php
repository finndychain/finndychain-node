<?php
namespace app\home\controller;
use think\Controller;

class Base extends  Controller
{
    public function _initialize(){

        //验证安装文件
        if (!is_file(ROOT_PATH . 'data/install.lock') || !is_file(APP_PATH . 'database.php')) {
            $this->redirect('install/index/index');
        }

    }




}
