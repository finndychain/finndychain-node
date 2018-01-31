<?php
namespace app\common\controller;
use think\Controller;

Class Base extends Controller
{
    public function _initialize(){
        //验证安装文件
        if (!is_file(ROOT_PATH . 'data/install.lock') || !is_file(APP_PATH . 'database.php')) {
            $this->redirect('install/index/index');
        }

    }


    protected function setPageSeo($title=''){
        $pageSeo['title']=$title;
        $pageSeo['keywords']=$title;
        $pageSeo['desc']=$title;
        $this->assign($pageSeo);
    }
}