<?php
namespace app\common\controller;
use think\Controller;
use think\Url;
Class Bbase extends Controller
{
    protected function _initialize()
    {
        parent::_initialize();

        $var_pathinfo_on = config("var_pathinfo_on");

        if(true === $var_pathinfo_on) {
            $var_pathinfo = config("var_pathinfo");
            Url::root("/index.php?{$var_pathinfo}=");
        }

        //验证安装文件
        if (!is_file(ROOT_PATH . 'data/install.lock') || !is_file(APP_PATH . 'database.php')) {
            $this->redirect('install/index/index');
        }

    }



}