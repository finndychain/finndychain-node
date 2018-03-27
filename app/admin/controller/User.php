<?php
namespace app\admin\controller;
use think\Session;

use app\admin\model\Users as modelUsers;
use app\admin\model\AuthGroupAccess;


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

        //获取用户所属组的名称
        foreach($userlist as $k=>$v){
            $groupnamearr = $this->modelUsers->getGroupInfo($v['uid'] , 'title');

            $groupnamestr= implode('、',$groupnamearr );
          
            $v[groupname] = $groupnamestr;
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
            $data['password'] = passwordencrypt($data['password']);
            $user_info = $this->modelUsers->getUserinfo(array('username'=>$data['username']));
            if($user_info){
                $this->error('用户名已存在!');
            }
            //自增id
            $insert_id = $this->modelUsers->insertUserinfo($data);
            if(!$insert_id){
                $this->error('操作失败,稍后再试!');
            }
//            //插入到auth_group_access 表中
            if (!empty($data['group_ids'])) {
                foreach ($data['group_ids'] as $k => $v) {
                    $group=array(
                        'uid'=>$insert_id,
                        'group_id'=>$v,
                        'create_time'=> time(),
                    );
                    $authgroupaccess = new AuthGroupAccess();
                    $authgroupaccess->insert($group);
                }
            }
            $this->success('添加成功','user/index');
        }
        //获取用户组
        $authgroup = new \app\admin\model\AuthGroup();
        $authgroupres = $authgroup->select();
        $this->assign('authgroupres',$authgroupres);
        return $this->fetch();
    }
    //修改用户信息
    public function edit()
    {
        if(request()->isPost()) {
            $data = input();
            // dump($data);die;
            $uid = $data['uid'];
            $groupidarr = $data['group_ids'];
            if(empty($groupidarr)){
                $this->error('请选择用户组');
            }
            unset($data['uid']);
            unset($data['group_ids']);
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


            $inser_res = $this->modelUsers->setUserValues(array('uid'=>$uid),$data);
            if($inser_res === ''){
                $this->error('操作失败,稍后再试!');
            }

            //同时 更新 group_access 表 先删除后添加
            $authgroupaccess = new AuthGroupAccess();
            $res = $authgroupaccess->where(array('uid'=>$uid))->delete();
            if (!empty($groupidarr)) {
                foreach ($groupidarr as $k => $v) {
                    $group=array(
                        'uid'=>$uid,
                        'group_id'=>$v,
                        'create_time'=>time(),
                    );
                    $authgroupaccess = new AuthGroupAccess();
                    $authgroupaccess->insert($group);
                }
            }
            $this->success('修改成功','user/index');
        }

        $uid = intval(input('param.uid'));
        if(empty($uid)){
            $this->error('参数有误!');
        }
        //获取用户组
        $authgroup = new \app\admin\model\AuthGroup();
        $authgroupres = $authgroup->select();


        //获取该用户信息
        $userinfo = $this->modelUsers->getUserinfo(array('uid' => $uid));
        //获取用户所属组的id
        $groupIdArr = $this->modelUsers->getGroupInfo($uid );

        $userinfo[group_id] = $groupIdArr;
        $this->assign('userinfo',$userinfo);
        $this->assign('authgroupres',$authgroupres);
        return $this->fetch();
    }
    //删除用户
    public function del()
    {
        $data = input();
        $uid = intval($data['uid']);
        if(empty($uid)){
            $this->error('参数错误!');
        }
        if($uid == 1){
            $this->error('超级管理员不能删除!');
        }
        $res = $this->modelUsers->delUser(array('uid'=>$uid));
        if(!$res){
            $this->error('操作失败,稍后再试!');
        }
        $this->success('删除成功','user/index');

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




}
