<?php
namespace app\admin\model;
use think\Model;
use think\db;


class Article extends  Model
{
    protected static function init()
    {
        //新增前 处理缩略图  如果上传失败 则不会继续执行insert
        //只有在模型实例 使用save 方法才生效  $article 为该模型的实例
//        Article::event('before_insert' , function($article){
//            if($_FILES['thumb']['tmp_name']){
//                $file = request()->file('thumb');
//                $image = \think\Image::open($file);
//
//                //$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
//                $info = $file->validate(['size'=>config('uploadmaxsize'),'ext'=>'jpg,png,jpeg'])->move(UPLOAD_PATH);
//               // $info = $file->move(UPLOAD_PATH);
//                if($info){
//                     //$thumb=ROOT_PATH . 'public' . DS . 'uploads'.'/'.$info->getExtension();
//                    // /cloud/public\uploads/20180404\33d3730c68dbb31151d4e3fed2719949.gif
//
//                    //原图 20180404\33d3730c68dbb31151d4e3fed2719949.gif
//                    $imgName = $info->getSaveName();
//                    $i = strpos($imgName,'\\');
//                    $start = substr($imgName,0,$i+1);
//                    $end = substr($imgName,$i+1);
//                    //缩略图 20180417\thumb_33d3730c68dbb31151d4e3fed2719949.gif
//                    $thumbName = $start.'thumb_'.$end;
//
//
//                    //生成缩略图
//                    $image->thumb(240, 120)->save(UPLOAD_PATH.'/'.$thumbName);
//                    $thumb = '/uploads/'.$info->getSaveName();
//                    $thumb_sm = '/uploads/'.$thumbName;
//
//
//                    //$image->thumb(150, 150)->save('./1.png');
//                    $article['thumb']=$thumb;
//                    $article['thumb_sm']=$thumb_sm;
//                   // Log::record('upload');
//                }else{
//                    $article['error'] = $file->getError();
//                    return false;
//                }
//            }
//        });
        //编辑之前 替换之前的缩略图
//        Article::event('before_update',function($article){
//
//
//            if($_FILES['thumb']['tmp_name']){
//                $arts=Article::find($article->id);
//                $thumb=$_SERVER['DOCUMENT_ROOT'].$arts['thumb'];
//                $thumb_sm=$_SERVER['DOCUMENT_ROOT'].$arts['thumb_sm'];
//                if(file_exists($thumb)){
//                    @unlink($thumb);
//                }
//                if(file_exists($thumb_sm)){
//                    @unlink($thumb_sm);
//                }
//                $file = request()->file('thumb');
//                $image = \think\Image::open($file);
//                $info = $file->validate(['size'=>config('uploadmaxsize'),'ext'=>'jpg,png'])->move(UPLOAD_PATH);
//
//
//                if($info){
//
//                    $imgName = $info->getSaveName();
//                    $i = strpos($imgName,'\\');
//                    $start = substr($imgName,0,$i+1);
//                    $end = substr($imgName,$i+1);
//                    //20180417\thumb_33d3730c68dbb31151d4e3fed2719949.gif
//                    $thumbName = $start.'thumb_'.$end;
//
//
//                    //生成缩略图
//                    $image->thumb(240, 120)->save(UPLOAD_PATH.'/'.$thumbName);
//                    $thumb = '/uploads/'.$info->getSaveName();
//                    $thumb_sm = '/uploads/'.$thumbName;
//
//                    $article['thumb']=$thumb;
//                    $article['thumb_sm']=$thumb_sm;
//                }else{
//                    $article['error'] = $file->getError();
//                    return false;
//                }
//            }
//        });

        //删除文章之前删除缩略图 使用 模型事件 before_delete 只可以在调用模型的方法才能生效，使用查询构造器通过Db类操作是无效的
        Article::event('before_delete',function($article){
            $arts=Article::find($article->id);
            $thumb=$_SERVER['DOCUMENT_ROOT'].$arts['thumb'];
            $thumb_sm=$_SERVER['DOCUMENT_ROOT'].$arts['thumb_sm'];
            if(file_exists($thumb)){
                @unlink($thumb);
            }
            if(file_exists($thumb_sm)){
                @unlink($thumb_sm);
            }
        });
    }

    //获取文章列表
    public function getArticleList($where ,$limit)
    {
        $res = Db::name('article')
            ->alias('a')
            ->join('article_category c','a.cateid = c.id','left')
            ->join('users u' , 'a.uid = u.uid','left')->field('a.id,a.title,a.poststatus,a.is_top,a.recommended,a.create_time,a.update_time,c.name,u.username,a.keywords,a.click')
            ->limit($limit)->where($where)->select();
        return $res;

    }

    //新增文章
    public function addArticle($data)
    {
        $res = $this->allowField(true)->data($data, true)->isUpdate(false)->save();
       // dump($res);

        $data['keywords'] = str_replace('，', ',', $data['keywords']);
        $keywords = explode(',', $data['keywords']);
        if($res > 0 ){
            $this->addTags($this->id,$keywords);
        }
       return $res;

    }
    //编辑文章
    public function editArticle($data)
    {
        $res = $this->allowField(true)->isUpdate(true)->data($data, true)->save();
        $data['keywords'] = str_replace('，', ',', $data['keywords']);
        $keywords = explode(',', $data['keywords']);
        $this->addTags($this->id,$keywords);
        return $res;
    }
    //删除文章
    public function delArticle($artId)
    {
        $res = self::destroy($artId);

        return $res;
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
            $oldTagIds = Db::name('article_tag_access')->where('articleid', $articleId)->column('tagid');
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
                    array_push($tagIds, $tagId);
                }
            }
            if (empty($tagIds) && !empty($oldTagIds)) {
                Db::name('article_tag_access')->where('articleid', $articleId)->delete();
            }
            $sameTagIds = array_intersect($oldTagIds, $tagIds);
            $shouldDeleteTagIds = array_diff($oldTagIds, $sameTagIds);
            if (!empty($shouldDeleteTagIds)) {
                Db::name('article_tag_access')->where(['articleid' => $articleId, 'tagid' => ['in', $shouldDeleteTagIds]])->delete();
            }
            if (!empty($data)) {
                Db::name('article_tag_access')->insertAll($data);
            }
        } else {//删除标签
            Db::name('article_tag_access')->where('articleid', $articleId)->delete();
        }
    }

    /**更新指定字段
     * @param array $where
     * @param $key
     * @param $value
     * @return int
     */
    public function setAritcleValue(array $where,$key,$value){
        return  $this->where($where)->setField($key,$value);
    }


}
