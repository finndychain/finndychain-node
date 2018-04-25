<?php
namespace app\admin\controller;
use app\admin\model\AuthGroup as AuthGroupModel;



class Authgroup extends Base
{
    protected function _initialize()
    {
        parent::_initialize();
        $this->modelAuthGroup = new AuthGroupModel();
    }
    public function index()
    {
        $authgroupres = $this->modelAuthGroup->order('id')->paginate(20);
        $title = '用户组设置';
        $this->assign('title',$title);
        $this->assign('authgroupres',$authgroupres);
        return $this->fetch();
    }
    //添加用户组
    public function add()
    {
        $authrule=new \app\admin\model\AuthRule();
        if(request()->isPost()) {
            $data = input('post.');
            $authRuleArr = $authrule->getRuleList();
            $title = trim($data['title']);
            if(empty($title)){
                $this->error('请输入用户组名称');
            }

            if(empty($data['rule_ids'])){
                $this->error('还没有权限规则,请先添加');
            }else{

                $rule_ids = rtrim($data['rule_ids'] ,',');

                //根据子级id获取父级id
                $ruleArr = explode(',' , $rule_ids);

                foreach ( $ruleArr as $k=>$v) {
                    $fathernodearr[] = getParentId($authRuleArr , $v, True);
                }
                $fathernodestr=implode(',', $fathernodearr);
                //去除重复的规则id
                $fathernodearr = explode(',',$fathernodestr);
                $rule_ids_arr = array_unique($fathernodearr);
                $rule_ids = implode(',',$rule_ids_arr);

                $data['rules']=$rule_ids;
                unset($data['rule_ids']);
                unset($data['groupid']);
            }

            $res = $this->modelAuthGroup->insert($data);
            if(!$res){
                $this->error('操作失败,稍后再试!');
            }
            $this->success('新增用户组成功!','index');
        }
        $title = '添加用户组';
        $this->assign('title',$title);
        $treeData = $this->treeview();
        $this->assign('treeData',$treeData);
        $title = '添加用户组';
        $this->assign('title',$title);
        return $this->fetch();
    }
    //修改用户组
    public function edit()
    {
        $authrule=new \app\admin\model\AuthRule();
        if(request()->isPost()) {
            $data = input();
            //获取所有规则 用来获取选中的规则的父级规则id
            $authRuleArr = $authrule->getRuleList();
            $title = trim($data['title']);
            if(empty($title)){
                $this->error('请输入用户组名称');
            }
            if(empty($data['rule_ids'])){
                $this->error('还没有权限规则,请先添加');
            }else{
                $rule_ids = rtrim($data['rule_ids'] ,',');
                //根据子级id获取父级id
                $ruleArr = explode(',' , $rule_ids);
                foreach ( $ruleArr as $k=>$v) {
                    $fatherNodeNrr[] = getParentId($authrulearr , $v, True);
                }
                $fatherNodeStr=implode(',', $fatherNodeNrr);
                //去除重复的规则id
                $fatherNodeArr = explode(',',$fatherNodeStr);
                $rule_ids_arr = array_unique($fatherNodeArr);
                $rule_ids = implode(',',$rule_ids_arr);

                $data['rules']=$rule_ids;
                unset($data['rule_ids']);
                unset($data['groupid']);
            }

            $res = $this->modelAuthGroup->update($data);
            if(!$res){
                $this->error('操作失败,稍后再试!');
            }
            $this->success('编辑成功!','index');

        }
        $groupid = intval(input('param.groupid'));

        //获取所有权限规则 树状结构
        /*$rule_data = $authrule->getTreeData();
        $this->assign('rule_data' ,$rule_data);*/

        //获取该用户组信息
        $group_data = $this->modelAuthGroup->find($groupid);
        $group_data['rules']=explode(',', $group_data['rules']);
        $this->assign('group_data' ,$group_data);

        $title = '分配权限';
        $this->assign('title',$title);
        $treeData = $this->treeview($group_data['rules']);
        $this->assign('treeData',$treeData);
        $this->assign('title',$title);

        return $this->fetch();
    }

    //删除用户组
    public function del()
    {
        $groupid = intval(input('param.groupid'));
        $groupid = 11111;
        $res = AuthGroupModel::destroy($groupid);

        if(!$res){
            $this->error('删除失败！');
        }
        $this->success('删除成功！',url('index'));
    }

    /**获取权限规则树状json
     * @param string $group_data 被编辑的用户组信息
     * @return mixed|string|void
     */
    protected function treeview($ruleArr='')
    {
        $authrule=new \app\admin\model\AuthRule();
        $rule_data = $authrule->getTreeData( );

        $data = array();
        foreach($rule_data as $key => $val){
            $valcount = count($val['_data']);
            $is_show = $this->checkIsSelected($val['id'] , $ruleArr);
            $item = array(
                'text' => $val['title'] ,
                'additionalParameters' =>  array('id' => $val['id'],'item-selected'=>$is_show)
            );
            if($valcount > 0){ //有子级
                $item['type'] = 'folder';
                foreach($val['_data']  as $k=> $v){
                    $vcount = count($v['_data']);
                    $is_show = $this->checkIsSelected($v['id'] , $ruleArr);
                    if($vcount >0){
                        $_children = array();
                        foreach($v['_data'] as $m=>$n){
                            $is_show = $this->checkIsSelected($n['id'] , $ruleArr);
                            if($is_show >0){
                                $is_show_stat ++;
                            }
                            $_children[$n[title]]=array(
                                'text' => $n['title'] ,
                                'type' => 'item',
                                'additionalParameters' =>  array('id' => $n['id'],'item-selected'=>$is_show)// item-selected 默认显示
                            );
                        }
                        $children=array(
                            'text' => $v['title'] ,
                            'type' => 'folder',
                            'additionalParameters' =>  array('id' => $v['id'],'children'=>$_children , 'item-selected'=>$is_show)
                        );
                    }else{
                        $children=array(
                            'text' => $v['title'] ,
                            'type' => 'item',
                            'additionalParameters' =>  array('id' => $v['id'],'item-selected'=>$is_show)
                        );
                    }
                    $item['additionalParameters']['children'][$v[title]] = $children;
                }
            }else{//无子级
                $item['type'] = 'item';
            }
            $data[$val['title']] = $item;
        }
        return json_encode($data);
    }



//    static public function buildTreeData($group_data,$data, $pid = 0, $fieldPri = 'id', $fieldPid = 'pid')
//    {
//
//
//
//
//        if (empty($data)) {
//            return array();
//        }
//        $arr = array();
//        foreach ($data as $v) {
//
//            if ($v[$fieldPid] == $pid) {
//
//                if(in_array($v[id] , $group_data)){
//                    $is_show = true;
//                }else{
//                    $is_show = '';
//                }
//                $arr[$v[title]] = array(
//                    'text' => $v['title'] ,
//                    'type' => 'item',
//                    'additionalParameters' =>array('id' => $v['id'],'item-selected'=>$is_show)// item-selected 默认显示
//                );
//                $arr[$v[title]]["additionalParameters"]["children"] = self::buildTreeData($group_data,$data, $v[$fieldPri],  $fieldPri, $fieldPid);
//            }
//        }
//
//        //return json_encode($arr);
//        return $arr;
//    }


    /**判断 叶子节点有值 默认选中
     * @param $id 权限规则id
     * @param $arr 编辑的用户组拥有的权限规则
     * @return bool|string
     */
    protected function checkIsSelected($id , $arr)
    {
        if(empty($id) || empty($arr)){
            return false;
        }
        if(in_array($id , $arr)){
            $is_show = true;
        }else{
            $is_show = '';
        }
        return $is_show;
    }
}
