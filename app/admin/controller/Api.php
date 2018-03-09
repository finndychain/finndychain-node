<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\FinndyData;
use app\admin\model\SysConf as modelSysConf;

class Api extends Controller
{
    public function Index()
    {
        $result = array(
            "error_code"=> 0,
            "reason"=> 'ok',
            "result"=> '',
        );
        return json($result);
    }

    public function finndydataapi(){
        if(request()->isPost()){
            $postdata = input();
            $modelSysConf = new modelSysConf();
            $sys_conf = $modelSysConf->getSysConf();
            $token = md5($sys_conf['app_key'].'finndy');
            if($postdata['cloud_post_token'] != $token){
                return false;
            }

            $data['robotid'] = $postdata['robotid'];
            $data['subject'] = $postdata['subject'];
            $data['message'] = $postdata['message'];
            for($i=1;$i<49;$i++){
                $extfield_num = 'extfield'.$i;
                $data[$extfield_num] = $postdata[$extfield_num];
            }

            if(empty($data)){
                $data['create_time'] = time();
                $finndydata = new FinndyData();
                return $finndydata->insertdata($data);
            }
            return false;
        }else{
            return false;
        }
    }
}
