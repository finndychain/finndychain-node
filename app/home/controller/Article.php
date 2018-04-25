<?php
namespace app\home\controller;
use think\Request;
use app\home\model\Article as ArticleModel;
class Article extends Base
{
    protected function _initialize()
    {
        parent::_initialize();
        $this->modelArticle = new ArticleModel();

    }

    public function index()
    {

        $cateId = intval(input('param.cateid'));

        //$articleList= $this->modelArticle->where(array('cateid'=>$cateId))->paginate(5);

        $listCount = $this->modelArticle->where(array('cateid'=>$cateId))->count();
        $page = empty(intval(input('get.page')))? 1 : intval(input('get.page'));
        $page = ($page < 1) ?  1 : $page;
        //$perpage = config('pagesize');
        $perpage = 5;
        $start = ($page - 1) * $perpage;
        $limit = $start.','.$perpage;

        $articleList= $this->modelArticle->where(array('cateid'=>$cateId))->limit($limit)->select();
        $theurl = url('article/index',array('cateid'=>$cateId));
        $multipage = multi($listCount, $perpage, $page, $theurl); //分页处理
        //dump($multipage);die;

        $hotRes=$this->modelArticle->getHotRes($cateId);
        $this->assign(array(
            'articleList'=>$articleList,
            'hotRes'=>$hotRes,
            'multipage'=>$multipage
        ));
        return $this->fetch('index');
    }


    public function detail()
    {
        $artId= intval(input('artid'));
        if(empty($artId)){
            $this->error('参数有误');
        }
        $articles =  $this->modelArticle->where(array('id'=>$artId))->find();
        $this->modelArticle->setField(array('id'=>$artId) , 'click');
    	$hotRes=$this->modelArticle->getHotRes($articles['cateid']);
    	$this->assign(array(
    		'articles'=>$articles,
    		'hotRes'=>$hotRes,
            'artid'=>$artId,
    		));
        return $this->fetch('article/detail');
    }

}
