<?php
namespace app\admin\controller;
use app\admin\model\Category as CategoryModel;

class Category extends Base
{
    protected function _initialize()
    {
        parent::_initialize();
        $this->modelCategory = new CategoryModel();
    }

    public function Index()
    {
        $title = '分类列表';
        $categoryRes = $this->modelCategory->getList('sort');
        $this->assign('categoryRes' ,$categoryRes);
        $this->assign('title' ,$title);
        return $this->fetch('index');
    }

    public function add()
    {
        if(request()->isPost()) {
            $data = input();
            if(empty(trim($data['name']))){
                $this->error('分类名称不能为空');
            }
            //判断规则是否已存在
            if($this->modelCategory->getCategoryInfo(array('name' => $data['name']) )){
                $this->error('该分类已存在,请重新输入');
            }
            $pid = $data['pid'];
            $where = array('id'=>$pid);
            $level = $this->modelCategory->getCategoryValue($where , 'level');
            if($level){
                $data['level'] = $level[0]['level'] + 1;
            }else{//顶级分类
                $data['level'] = 0;
            }
            $data['create_time'] = time();
            $res = $this->modelCategory->insert($data);
            if(!$res){
                $this->error('操作失败,稍后再试!');
            }
            $this->success('新增分类成功!','index');
        }
        $categoryRes = $this->modelCategory->getList('sort');
        $title= '添加分类';
        $this->assign('categoryRes' ,$categoryRes);
        $this->assign('title' ,$title);
        return $this->fetch();
    }

    public function edit()
    {
        if(request()->isPost()) {
            $data = input();
            if(empty(trim($data['name']))){
                $this->error('分类名称不能为空');
            }
            $id = $data['id'];
            unset($data['id']);
            $pid = $data['pid'];
            $where = array('id'=>$pid);
            $level = $this->modelCategory->getCategoryValue($where , 'level');
            if($level){
                $data['level'] = $level[0]['level']+1;
            }else{//顶级分类
                $data['level'] = 0;
            }
            $where = array('id'=>$id);
            $res = $this->modelCategory->updateData($where,$data);
            if(!$res){
                $this->error('数据没有变化,更新失败!');
            }
            $this->success('更新成功!','index');
        }
        $categoryId = intval(input('param.id'));
        if(empty($categoryId)){
            $this->error('参数有误');
        }
        $title = '编辑分类';
        $categoryRes = $this->modelCategory->getList('sort');
        $categorys = $this->modelCategory->find($categoryId);
        $this->assign('title' ,$title);
        $this->assign('categoryRes' ,$categoryRes);
        $this->assign('categorys' ,$categorys);
        return $this->fetch();
    }
    //排序
    public function sort()
    {
        $data = input('post.');
        $res = $this->modelCategory->orderData($data);
        if(!$res){
            $this->error('数据没有变化,排序失败！');
        }
        $this->success('更新成功');
    }


    public function del()
    {
        $categoryId = intval(input('param.id'));
        if(empty($categoryId)){
            $this->error('参数有误');
        }
        $categoryArr = array();
        $categoryArr = $this->modelCategory->getChilrenId($categoryId);
        $categoryArr[] = $categoryId;
        if(count($categoryArr) < 1){
            $this->error('参数有误');
        }
        $res = CategoryModel::destroy($categoryArr);
        if(!$res){
            $this->error('删除分类失败！');
        }
        $this->success('删除分类成功！',url('index'));
    }


}
