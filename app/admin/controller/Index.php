<?php
namespace app\admin\controller;
use app\admin\model\StatRobot;
use app\admin\model\Users;
use think\Session;

class Index extends Base
{
    public function Index()
    {

        $robotstatistics = array();
        $userscounts = array();
        //通过api获取数据源数
//        $params['op'] = 'getrobotstatistics';
//        $res = api_request('get' ,api_build_url('api.php',$params));
//
//        if($res['error_code'] != 0){
//            $this->error('参数有误');
//        }else{
//            $robotstatistics = $res[result];
//        }


        //本地获取数据
        $uid = Session::get('uid');
        $statrobot = new StatRobot();
        $today = date('Y-m-d' ,time());
        $todaytime=strtotime('today');
        //本周起始日期
        $thisweek = date('Y-m-d', strtotime("this week Monday", time()));
        //本月起始日期
        $thismonth = date('Y-m-d' , mktime(0, 0, 0, date('m'), 1, date('Y')));
        $wherearr = array(
            'uid'=>$uid,
            'today'=>$today,
            'thisweek'=>$thisweek,
            'thismonth'=>$thismonth,
        );
        $robotstatistics = $statrobot->getRobotStat($wherearr);

        //用户数统计
        $users = new Users();
        $userscounts = $users->getUsersCounts($todaytime);

        $title = '云采集';
        $this->assign([
            'title' => $title,
            'robotstatistics' => $robotstatistics,
            'userscounts' => $userscounts,
        ]);
        return $this->fetch();
    }
}
