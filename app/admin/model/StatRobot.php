<?php
namespace app\admin\model;
use think\Model;
use think\Session;

class StatRobot extends  Model
{

    protected $name = 'stat_robot';

    /**插入数据源统计表
     * @param array $data
     * @return int|string|true
     */
    public function insertStatRobot(array $data){
        $data['create_time'] = time();
        $where = array('uid'=>$data['uid'],'dateline'=>$data['dateline']);
        $res = $this->where($where)->find();
        if(empty($res)){//如果没数据 新增
            $data['count'] = 1;
            return $this->insert($data);
        }else{//如果存在 更新count 自增
           $where = array('uid'=>$data['uid'],'dateline'=>$data['dateline']);
           return  $this->where($where)->setInc('count');
        }
    }

    /** 获取数据源数量
     * @param array $data
     * @return array
     */
    public function getRobotStat(array $data){
        $robotstatistics = array();
        $uid = $data['uid'];
        $today = $data['today'];
        $thisweek = $data['thisweek'];
        $thismonth = $data['thismonth'];
        if(Session::get('usertype') == '超级管理员'){
            $robotstatistics['all'] = $this->sum('count');
            $robotstatistics['today'] = $this->where('dateline','=',$today)->sum('count');
        }else{
            $robotstatistics['all'] = $this->where('uid',$uid)->sum('count');
            $robotstatistics['today'] = $this->where('uid',$uid)->where('dateline','=',$today)->sum('count');
            $robotstatistics['thisweek'] = $this->where('uid',$uid)->where('dateline','>=',$thisweek)->sum('count');
            $robotstatistics['thismonth'] = $this->where('uid',$uid)->where('dateline','>=',$thismonth)->sum('count');
        }
        return $robotstatistics;

    }
}

