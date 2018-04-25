<?php
namespace app\home\model;
use think\Model;
use app\home\model\Cate;
class Article  extends Model
{
    //获取文章列表
    public function getArticleList(){
         $res=$this->paginate(5);
         $res = collection($res)->toArray();
         return $res;
    }
    //更新指定字段(更新文章查看次数)
    public function setField($where,$field)
    {
        $this->where($where)->setInc($field);

    }
    public function getAllArticles($cateId){
        $cate=new Cate();
        $allCateID=$cate->getchilrenid($cateId);
        $artRes=db('article')->where("cateid IN($allCateID)")->order('id desc')->paginate(2);

        return $artRes;
    }

    public function getHotRes($cateId){
        $cate=new Category();
        $allCateID=$cate->getchilrenid($cateId);
        $artRes=db('article')->where("cateid IN($allCateID)")->order('click desc')->limit(5)->select();
        return $artRes;
    }

    public function getSerHot(){
       $artRes=db('article')->order('click desc')->limit(5)->select();
        return $artRes; 
    }

    public function getSiteHot(){
        $siteHotArt=$this->field('id,title,thumb')->order('click desc')->limit(5)->select();
        return $siteHotArt;
    }

    //获取最新文章
    public function getNewArticle(){
        $newArtiecleRes=db('article')->field('a.id,a.title,a.summary,a.thumb,a.click,a.create_time,a.keywords,c.name')->alias('a')->join('bk_cate c','a.cateid=c.id','left')->order('a.id desc')->limit(10)->select();
        return $newArtiecleRes;
    }

    public function getRecArt(){
        $recArt=$this->where('rec','=',1)->field('id,title,thumb')->order('id desc')->limit(4)->select();
        return $recArt;
    }
}
