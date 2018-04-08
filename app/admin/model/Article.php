<?php
namespace app\admin\model;
use think\Model;
use think\db;


class Article extends  Model
{
    protected static function init()
    {
        Article::event('before_insert' , function($article){
            if($_FILES['thumb']['tmp_name']){
                $file = request()->file('thumb');

                //$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                $info = $file->validate(['size'=>2048000,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info){
                     //$thumb=ROOT_PATH . 'public' . DS . 'uploads'.'/'.$info->getExtension();
                    // /cloud/public\uploads/20180404\33d3730c68dbb31151d4e3fed2719949.gif
                    $thumb='/uploads'.'/'.$info->getSaveName();
                    $article['thumb']=$thumb;
                }else{
                    return false;
                }
            }
        });
        //编辑之前 替换之前的缩略图
        Article::event('before_update',function($article){
//           dump($_FILES['thumb']['tmp_name']);die;
            if($_FILES['thumb']['tmp_name']){
                $arts=Article::find($article->id);
                $thumbpath=$_SERVER['DOCUMENT_ROOT'].$arts['thumb'];

                if(file_exists($thumbpath)){
                    @unlink($thumbpath);
                }
                $file = request()->file('thumb');
                $info = $file->validate(['size'=>2048000,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info){
                    $thumb= '/uploads'.'/'.$info->getSaveName();
                    $article['thumb']=$thumb;
                }

            }
        });

        Article::event('before_delete',function($article){
            $arts=Article::find($article->id);
            $thumbpath=$_SERVER['DOCUMENT_ROOT'].$arts['thumb'];
            if(file_exists($thumbpath)){
                @unlink($thumbpath);
            }
        });
    }

    //获取文章列表
    public function getArticleList()
    {
        $res = Db::name('article')
            ->alias('a')
            ->join('category c','a.cateid = c.id','left')
            ->join('users u' , 'a.uid = u.uid','left')->field('a.id,a.title,a.poststatus,a.create_time,a.update_time,c.name,u.username,a.keywords')
            ->select();
        return $res;

    }

    //新增文章
    public function addArticle($data)
    {

        $this->allowField(true)->data($data, true)->isUpdate(false)->save();

        $data['keywords'] = str_replace('，', ',', $data['keywords']);
        $keywords = explode(',', $data['keywords']);
        $this->addTags($this->id,$keywords);
        return $this;
    }
    //编辑文章
    public function editArticle($data)
    {
        $this->allowField(true)->isUpdate(true)->data($data, true)->save();
        $data['keywords'] = str_replace('，', ',', $data['keywords']);
        $keywords = explode(',', $data['keywords']);
        $this->addTags($this->id,$keywords);
        return $this;
    }
    //删除文章
    public function delArticle($data)
    {
        $where = array('id'=>$data);
        $this->where($where)->delete();
        $this->addTags($data );
        return $this;

    }

    /**文章处理 关联标签处理
     * @param $articleId 文章id
     * @param string $keywords 标签
     */
    public function addTags($articleId ,$keywords='' )
    {
        $tagModel = new Tag();

        $tagIds = [];

        $data = [];

        if (!empty($keywords)) {

            $oldTagIds = Db::name('article_tag')->where('articleid', $articleId)->column('tagid');

            foreach ($keywords as $keyword) {
                $keyword = trim($keyword);
                if (!empty($keyword)) {
                    $findTag = $tagModel->where('name', $keyword)->find();

                    if (empty($findTag)) {
                        $tagId = $tagModel->insertGetId([
                            'name' => $keyword,
                            'create_time'=>time()
                        ]);
                    } else {
                        $tagId = $findTag['id'];
                    }

                    if (!in_array($tagId, $oldTagIds)) {
                        array_push($data, ['tagid' => $tagId, 'articleid' => $articleId,'create_time'=>time()]);
                    }
                    //dump($data);die;
                    array_push($tagIds, $tagId);

                }
            }
            if (empty($tagIds) && !empty($oldTagIds)) {
                Db::name('article_tag')->where('articleid', $articleId)->delete();
            }

            $sameTagIds = array_intersect($oldTagIds, $tagIds);

            $shouldDeleteTagIds = array_diff($oldTagIds, $sameTagIds);

            if (!empty($shouldDeleteTagIds)) {
                Db::name('article_tag')->where(['articleid' => $articleId, 'tagid' => ['in', $shouldDeleteTagIds]])->delete();
            }

            if (!empty($data)) {
                Db::name('article_tag')->insertAll($data);
            }
        } else {//删除标签
            Db::name('article_tag')->where('articleid', $articleId)->delete();
        }
    }



}
