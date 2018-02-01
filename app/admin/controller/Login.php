<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use app\admin\model\Users;

class Login extends Controller
{
    //登录
    public function doLogin()
    {
        if(request()->isPost()){
            $postdata = input();
            $password = passwordencrypt($postdata['password']);
            //dump($password);die;
            $username = $postdata['username'];
            if(empty($username) || empty($postdata['password'])){
                $this->error('账号或密码不能为空!');
            }
            $captcha = $postdata['seccode'];
            if(!captcha_check($captcha)){
                $this->error('验证码错误!');
            }

            $password = passwordencrypt($postdata['password']);

            $data['username'] = $username;
            $data['password'] = $password;
            $users = new Users();

            $res = $users->getUserinfo($data);
            if(empty($res)){

                $this->error('账号或密码错误!');
            }
            if($res){
                Session::set('uid',$res['uid']);
                Session::set('username',$res['username']);
                unset($res['password']);
                Session::set('userinfo',$res);
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

    public function register(){
        return $this->fetch('login/register');
    }
}
