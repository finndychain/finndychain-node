<?php
namespace app\admin\controller;
use app\admin\model\Article as ArticleModel;
use app\admin\model\Category as CategoryModel;

class Article extends Base
{
    protected function _initialize()
    {
        parent::_initialize();
        $this->modelArticle = new ArticleModel();

    }

    public function Index()
    {
        $title = '权限设置';

        $articleres = $this->modelArticle->getArticleList();
        $this->assign('articleres' ,$articleres);
        $this->assign('title' ,$title);
        return $this->fetch('index');
    }

    public function add()
    {
        if(request()->isPost()) {
            $data = input();
            $validate = $this->validate($data, 'Article.add');
            if (true !== $validate) {
                $this->error($validate);
            }
            if(empty(intval($data['cateid']))){
                $this->error('请选择分类');
            }
            $data['uid'] = $this->uid;
            $data['create_time'] = time();
            if( $this->modelArticle ->addArticle($data)){
                $this->success('添加文章成功',url('index'));
            }else{
                $this->error('添加文章失败！');
            }

        }
        $categoryModel = new CategoryModel();
        $categoryRes = $categoryModel->getList('sort');
        $title= '添加文章';
        $this->assign('categoryRes' ,$categoryRes);
        $this->assign('title' ,$title);
        return $this->fetch();

    }

    public function edit()
    {
        if(request()->isPost()) {
            $data = input();

            $validate = $this->validate($data, 'Article.edit');
            if (true !== $validate) {
                // 验证失败 输出错误信息
                $this->error($validate);
            }

            $res = $this->modelArticle->editArticle($data);
            if(!$res){
                $this->error('数据没有变化,更新失败!');
            }
            $this->success('更新成功!','index');

        }

        $articleId = intval(input('param.id'));
        if(empty($articleId)){
            $this->error('参数有误');
        }
        $categoryModel = new CategoryModel();
        $categoryRes = $categoryModel->getList('sort');
        $articles = $this->modelArticle->find($articleId);
        $title= '编辑文章';
        $this->assign('categoryRes' ,$categoryRes);
        $this->assign('articles' ,$articles);
        $this->assign('title' ,$title);
        return $this->fetch();

    }
    //排序
    public function sort()
    {
        $data = input('post.');
        $res = $this->modelArticle->orderData($data);
        if(!$res){
            $this->error('数据没有变化,排序失败！');
        }
        $this->success('更新成功');
    }


    public function del()
    {
        $articleId = intval(input('param.id'));
        if(empty($articleId)){
            $this->error('参数有误');
        }
        $res = $this->modelArticle->delArticle($articleId);
        if(!$res){
            $this->error('删除权限失败！');
        }
        $this->success('删除权限成功！',url('index'));



    }


}
