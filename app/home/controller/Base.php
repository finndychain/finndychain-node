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

use app\common\controller\Bbase;
use app\home\model\Category;
use think\Cache;
class Base extends  Bbase
{

    protected function _initialize()
    {
        parent::_initialize();

        $categoryRes = Cache::get('categoryRes');
        if($categoryRes){
            $this->assign('categoryRes',$categoryRes);
        }else{
            $this->getNavCates();
        }

        if(intval(input('cateid'))){
            $this->getCrumbCates(intval(input('cateid')));
        }
        //文章详情页 获取分类id 获取面包屑分类
        if(intval(input('artid'))){
            $artId =intval(input('artid'));
            $article = new \app\home\model\Article();
            $cateId = $article->field('cateid')->find($artId);

            $this->getCrumbCates($cateId['cateid']);
        }

    }

    //获取导航 分类信息
    public function getNavCates()
    {
        $category = new Category();
        $categoryRes = $category->where(array('pid'=>0))->select();

        foreach($categoryRes as $k => $v){
            $children=$category->where(array('pid'=>$v['id']))->select();

            if($children){
                $categoryRes[$k]['children']=$children;
            }else{
                $categoryRes[$k]['children']=0;
            }
        }
        Cache::set('categoryRes' ,$categoryRes ,3600 );

        $this->assign('categoryRes',$categoryRes);
    }

    //获取面包屑 分类信息
    public function getCrumbCates($cateId){
        $category = new Category();
        $crumbCates=$category->getparents($cateId);
       // dump($crumbCates);die;

        $this->assign('crumbCates',$crumbCates);
    }



}
