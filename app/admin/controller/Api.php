<?php
namespace app\admin\controller;
use think\Controller;

class Api extends Controller
{
    public function Index()
    {
        $result = array(
            "error_code"=> 0,
            "reason"=> 'ok',
            "result"=> '',
        );
        echo json_encode($result);exit();
    }
}
