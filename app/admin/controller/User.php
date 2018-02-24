<?php
namespace app\admin\controller;
use think\Session;

use app\admin\model\Users as modelUsers;


class User extends Base
{
    public $modelUser = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->modelUsers = new modelUsers();
    }

    public function index()
    {
        $title = '用户列表';
        $perpage    = config('pagesize');
        $page       = empty(input('get.page')) ? 1 : intval(input('get.page'));
        $page       = ($page < 1) ?  1 : $page;
        $start      = ($page - 1) * $perpage;

        $listcount = $this->modelUsers->count();
        $userlist = $this->modelUsers->order('uid')->limit($start,$perpage)->select();
        foreach($userlist as $k=>$v){
            $userlist[$k]['user_type'] = $this->modelUsers->getUserGroup($v['user_type']);
        }
        $theurl = url('user/index');
        $multipage = multi($listcount, $perpage, $page, $theurl); //分页处理

        $this->assign('title',$title);
        $this->assign('userlist',$userlist);
        $this->assign('multipage',$multipage);
        return $this->fetch();

    }

    public function profile()
    {
        $title = '我的面板';
        $params['op'] = 'getuserinfo';
        $params['uid'] = $this->getSysConfValue('app_key');
        $res = api_request('get' ,api_build_url('api.php',$params));
        $finndy_info = check_api_result($res);
        if(!empty($finndy_info['robotversion'])){
            $params['op'] = 'getversiontolv';
            $params['version'] = $finndy_info['robotversion']; //$userinfo['finndy_uid'];
            $res_lv = api_request('get' ,api_build_url('api.php',$params));
            $result_lv = check_api_result($res_lv);
        }
        $this->assign('finndy',$finndy_info);
        $this->assign('lv',$result_lv);
        return $this->fetch('profile',['title'=>$title]);
    }

    public function resetpwd()
    {

        $title = '修改密码';
        if(request()->isPost()){
            $postdata = input();

            $validate = $this->validate($postdata,'User.Resetpwd');//使用validate验证
            if(true !== $validate){
                // 验证失败 输出错误信息
                $this->error($validate);
            }
            if($postdata['set_newpass'] != $postdata['set_okpass']){$this->error('新密码两次输入不一致!');}

            $userinfo =$this->modelUsers->getUserinfo(['username'=>Session::get('username'),'password'=>passwordencrypt($postdata['set_oldpass'])]);
            if(empty($userinfo)){$this->error('原始密码错误!');}

            $updage_res = $this->modelUsers->setUserValue(array('uid'=>$userinfo['uid']),'password',passwordencrypt($postdata['set_okpass']));
            if(!$updage_res){
                $this->error('网络原因稍后再试!');
            }
            $this->success('密码修改成功,请重新登录','login/loginout');
        }
        return $this->fetch('resetpwd',['title'=>$title]);
    }


    //增加用户
    public function add()
    {
        if(request()->isPost()) {
            $data = input();
            $validate = $this->validate($data, 'User.AddUser');
            if (true !== $validate) {
                // 验证失败 输出错误信息
                $this->error($validate);
            }
            $this->checkUserType($data['user_type']);

            $data['password'] = passwordencrypt($data['password']);
            $user_info = $this->modelUsers->getUserinfo(array('username'=>$data['username']));
            if($user_info){
                $this->error('用户名已存在!');
            }

            $inser_res = $this->modelUsers->insertUserinfo($data);
            if(!$inser_res){
                $this->error('操作失败,稍后再试!');
            }
            $this->success('添加成功','user/index');
        }
    }

    //修改用户信息
    public function edit()
    {
        if(request()->isPost()) {
            $data = input();
            $uid = $data['uid'];
            unset($data['uid']);
            if(empty($uid)){$this->error('参数错误!');}

            $password = $data['password'];
            if(empty($password)){$data['password'] = 123456;} //默认填充密码，提交时不修改

            $validate = $this->validate($data, 'User.AddUser');
            if (true !== $validate) {
                // 验证失败 输出错误信息
                $this->error($validate);
            }
            if(empty($password)){
                unset($data['password']); //剔除默认密码，不提交
            }else{
                $data['password'] = passwordencrypt($password);
            }

            $this->checkUserType($data['user_type']);

            $inser_res = $this->modelUsers->setUserValues(array('uid'=>$uid),$data);
            if(!$inser_res){
                $this->error('操作失败,稍后再试!');
            }
            $this->success('修改成功','user/index');
        }
    }

    //禁用用户
    public function ban()
    {
        $data = input();
        $uid = $data['uid'];
        $status = $data['status'];
        if(strlen($status) != 1 || !intval($status)){$this->error('参数错误!');}
        if($status  != 0){
            $status = 0;
        }else{
            $status = 1;
        }
        if(empty($uid)){$this->error('参数错误!');}

        $status_res =$this->modelUsers->setUserValue(array('uid'=>$uid),'status',$status);
        if(!$status_res){
            $this->error('操作失败,稍后再试!');
        }
        $this->success('修改成功','user/index');

    }

    protected function checkUserType($userTypeNew=''){
        $userInfo = Session::get('userinfo');
        $userType = $userInfo['user_type'];
        if(empty($userTypeNew)||($userType<2)||($userTypeNew>$userType)){
            $this->error('没有权限操作！');
        }
    }


}
