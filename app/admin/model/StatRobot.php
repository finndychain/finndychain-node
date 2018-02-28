<?php
namespace app\admin\model;
use think\Model;

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
    public function getRobotStat(array $data ,$key=''){
        $robotstatistics = array();
        $today = $data['today'];
        $thisweek = $data['thisweek'];
        $thismonth = $data['thismonth'];
        if(empty($key)){//管理员
            $robotstatistics['all'] = $this->sum('count');
            $robotstatistics['today'] = $this->where('dateline',$today)->sum('count');
        }else{//非管理员 按照指定uid查询
            $robotstatistics['all'] = $this->where('uid',$key)->sum('count');
            $robotstatistics['today'] = $this->where('uid',$key)->where('dateline',$today)->value('count');
            $robotstatistics['thisweek'] = $this->where('uid',$key)->where('dateline','>=',$thisweek)->sum('count');
            $robotstatistics['thismonth'] = $this->where('uid',$key)->where('dateline','>=',$thismonth)->sum('count');
        }
        return $robotstatistics;
    }

    /**按照日期查询每天新增的数据数量
     * @param $data 日期
     * @return array
     */
    public function getStatByDate($data,$key=''){

        if(empty($key)){
            foreach($data as $v){
                $data = $this->where('dateline',$v)->sum('count') ;
                empty($data) ? $data = 0 : $data;
                $everydaystat[] = $data;
            }
        }else{
            foreach($data as $v){
                $data = $this->where('uid',$key)->where('dateline',$v)->value('count') ;
                empty($data) ? $data = 0 : $data;
                $everydaystat[] = $data;
            }

        }
        return $everydaystat;
    }
}

