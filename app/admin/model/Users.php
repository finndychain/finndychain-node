<?php
namespace app\admin\model;
use think\Model;


class Users extends  Model
{
    public function login($data){
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
}
