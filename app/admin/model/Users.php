<?php
namespace app\admin\model;
use think\Model;


class Users extends  Model
{
    public function getLogin($data){
        $res = $this->where($data)->find();
        if($res){
            $result = array(
                'code'=>1,
                'msg' =>'succ',
                'result' =>$res
            );
            return $result;
        }else{
            $result = array(
                'code' => '10001',
                'msg'  => 'fail',
            );
            return $result;
        }
    }

    public function getPwd($data){
        $code = '';
        $map = array(
            'username' => $data['username'],
            'password' => $data['set_oldpass'],
        );
        $userinfo = $this->where($map)->select();
        if(empty($userinfo)){
            return $code = '10001' ;
        }
        $insertarr = array(
            'username' => $data['username'],
            'password' => $data['set_okpass'],
        );
        if($this->save($insertarr)){
             return $code = 0;
        }else{
             return $code = '10002';
        }

    }
}
