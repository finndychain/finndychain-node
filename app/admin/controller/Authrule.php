<?php
namespace app\admin\controller;
use app\admin\model\AuthRule as AuthRuleModel;
use \think\cache;

class Authrule extends Base
{
    protected function _initialize()
    {
        parent::_initialize();
        $this->modelAuthRule = new AuthRuleModel();
    }

    public function Index()
    {
        $title = '权限设置';

        $authRuleres = $this->modelAuthRule->getRuleFormatList();
        $this->assign('authruleres' ,$authRuleres);
        $this->assign('title' ,$title);
        return $this->fetch('index');
    }

    public function add()
    {
        if(request()->isPost()) {
            $data = input();
            $validate = $this->validate($data, 'AuthRule.add');
            if (true !== $validate) {
                // 验证失败 输出错误信息
                $this->error($validate);
            }

            //判断规则是否已存在
            if($this->modelAuthRule->getRuleInfo(array('name' => $data['name']) )){
                $this->error('该规则已存在,请重新输入');
            }
            $pid = $data['pid'];
            $where = array('id'=>$pid);
            $level = $this->modelAuthRule->getRuleValue($where , 'level');
            if($level){
                $data['level'] = $level[0]['level'] + 1;
            }else{//顶级权限
                $data['level'] = 0;
            }
            $data['create_time'] = time();
            $res = $this->modelAuthRule->insert($data);
            if(!$res){
                $this->error('操作失败,稍后再试!');
            }
            $this->success('新增权限成功!','index');
        }

        $authruleres = $this->modelAuthRule->getRuleList();
        $title= '添加标签';
        $this->assign('authruleres' ,$authruleres);
        $this->assign('title' ,$title);
        return $this->fetch();

    }

    public function edit()
    {
        if(request()->isPost()) {
            $data = input();
            $validate = $this->validate($data, 'AuthRule.edit');
            if (true !== $validate) {
                // 验证失败 输出错误信息
                $this->error($validate);
            }
            //判断是否显示菜单复选框按钮的值
            $_data = array();
            foreach ($data as $k=>$v){
                $_data[] = $k;
            }
            if(!in_array('is_display' , $_data)){
                $data['is_display'] = 0;
            }

            $id = $data['id'];
            unset($data['id']);
            $pid = $data['pid'];
            $where = array('id'=>$pid);
            $level = $this->modelAuthRule->getRuleValue($where , 'level');
            if($level){
                $data['level'] = $level[0]['level']+1;
            }else{//顶级权限
                $data['level'] = 0;
            }
            $where = array('id'=>$id);
            $res = $this->modelAuthRule->updateData($where,$data);
            if(!$res){
                $this->error('数据没有变化,更新失败!');
            }
            $this->success('更新成功!','index');

        }

        $ruleid = intval(input('param.ruleid'));
        if(empty($ruleid)){
            $this->error('参数有误');
        }
        $title = '编辑权限';
        $authruleres = $this->modelAuthRule->getRuleList();
        $authrules = $this->modelAuthRule->find($ruleid);
        $this->assign('title' ,$title);
        $this->assign('authruleres' ,$authruleres);
        $this->assign('authrules' ,$authrules);

        return $this->fetch();

    }
    //排序
    public function sort()
    {
        $data = input('post.');
        $res = $this->modelAuthRule->updateOrderData($data);
        if(!$res){
            $this->error('数据没有变化,排序失败！');
        }
        $this->success('更新成功');
    }


    public function del()
    {

        $ruleId = intval(input('param.ruleid'));
        $ruleArr = $this->modelAuthRule->getChilrenId($ruleId);
        $ruleArr[] = $ruleId;
        $res = AuthRuleModel::destroy($ruleArr);

        if(!$res){
            $this->error('删除权限失败！');
        }

        //$this->redirect(url('index'));

        $this->success('删除权限成功！',url('index'));

    }


}
