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


        //本地获取数据源数
        $uid = Session::get('uid');
        $statrobot = new StatRobot();
        $today = date('Ymd' ,time());
        $weektime=strtotime('-6 day');

        //最近7天日期
        $thisweek = date('Ymd', strtotime("-6 day"));
        //最近30天起始日期
        $thismonth = date('Ymd' , strtotime("-29 day"));
        $wherearr = array(
            'today'=>$today,
            'thisweek'=>$thisweek,
            'thismonth'=>$thismonth,
        );

        $usertype = $this->getUserInfo('user_type');
        if($usertype == 3){
            $robotstatistics = $statrobot->getRobotStat($wherearr);
        }else{
            $robotstatistics = $statrobot->getRobotStat($wherearr,$uid);
        }

        //用户数统计
        $users = new Users();
        $userscounts = $users->getUsersCounts($weektime);

        //折线图x坐标 日期
        $datearr = array();
        for($i=0;$i<=6;++$i){
            $datearr[] = date('Ymd' ,time()-$i*24*60*60);
        }
        $datearr = array_reverse($datearr);
        $date = json_encode($datearr);

        //每日数据源数量
        if($usertype == 3){
            $res = $statrobot->getStatByDate($datearr);
        }else{
            $res = $statrobot->getStatByDate($datearr ,$uid);
        }
        $data = json_encode($res);
        $title = '云采集';

        $this->assign([
            'title' => $title,
            'robotstatistics' => $robotstatistics,
            'userscounts' => $userscounts,
            'date' => $date,
            'data' => $data,
            'usertype' => $usertype,
        ]);
        return $this->fetch();
    }
}
