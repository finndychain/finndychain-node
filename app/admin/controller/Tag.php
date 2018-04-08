<?php
namespace app\admin\controller;
use app\admin\model\Tag as TagModel;

class Tag extends Base
{
    protected function _initialize()
    {
        parent::_initialize();
        $this->modelTag = new TagModel();
    }

    public function Index()
    {
        $title = '标签列表';
        $tagRes = $this->modelTag->select();
        //dump($tagRes);die;
        $this->assign('tagRes' ,$tagRes);
        $this->assign('title' ,$title);
        return $this->fetch('index');
    }

    public function add()
    {
        if(request()->isPost()) {
            $data = input();

            if(empty(trim($data['name'])) ){
                $this->error('标签名称不能为空');
            }
            //判断规则是否已存在
            if($this->modelTag->getTagInfo(array('name' => $data['name']) )){
                $this->error('该标签已存在,请重新输入');
            }
            $data['create_time'] = time();
            $res = $this->modelTag->insert($data);
            if(!$res){
                $this->error('操作失败,稍后再试!');
            }
            $this->success('新增标签成功!','index');
        }
        $title= '添加标签';
        $this->assign('title' ,$title);
        return $this->fetch();
    }

    public function edit()
    {
        if(request()->isPost()) {
            $data = input();
            if(empty(trim($data['name'])) ){
                $this->error('标签名称不能为空');
            }
            if($this->modelTag->getTagInfo(array('name' => $data['name']) )){
                $this->error('该标签已存在,请重新输入');
            }
            $id = $data['id'];
            unset($data['id']);
            $where = array('id'=>$id);
            $res = $this->modelTag->updateData($where,$data);
            if(!$res){
                $this->error('数据没有变化,更新失败!');
            }
            $this->success('更新成功!','index');
        }
        $tagId = intval(input('param.id'));
        if(empty($tagId)){
            $this->error('参数有误');
        }
        $title = '编辑标签';
        $tags = $this->modelTag->find($tagId);
        $this->assign('title' ,$title);
        $this->assign('tags' ,$tags);
        return $this->fetch();
    }

    public function del()
    {
        $tagId = intval(input('param.id'));
        if(empty($tagId)){
            $this->error('参数有误');
        }
        $res = TagModel::destroy($tagId);
        if(!$res){
            $this->error('删除失败！');
        }
        $this->success('删除成功！',url('index'));
    }


}
