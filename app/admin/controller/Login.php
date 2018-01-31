<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use app\admin\model\Users;

class Login extends Controller
{
    //登录
    public function dologin()
    {
        if(request()->isPost()){
            $postdata = input();
            $username = $postdata['username'];
            if(empty($username) || empty($postdata['password'])){
                $this->error('账号或密码不能为空!');
            }
            $captcha = $postdata['seccode'];
            if(!captcha_check($captcha)){
                $this->error('验证码错误!');
            }
            $password = passwordencrypt($postdata['password']);
            $users = new Users();
            $data['username'] = $username;
            $data['password'] = $password;
            $res = $users->login($data);
            if($res['code'] == '10001'){
                $this->error('账号或密码错误!');
            }
            if($res['code'] == '1'){
                Session::set('uid',$res['result']['uid']);
                Session::set('username',$res['result']['username']);
                unset($res['result']['password']);
                Session::set('userinfo',$res['result']);
                $this->success('登录成功','index/index');
            }
        }
        if(Session::get('uid') && Session::get('userinfo') && Session::get('username')){
            $this->redirect(url('index/index'));
        }
        return $this->fetch('dologin');
    }


    //注册
    public function loginOut()
    {
        Session::delete('uid');
        Session::delete('username');
        Session::delete('userinfo');
        $this->redirect('login/dologin');
    }
}
