<?php
namespace app\admin\model;
use think\Model;

class UserRobot extends  Model
{

    protected $name = 'user_robot';

    // 关闭自动写入update_time字段
    protected $updateTime = false;

    /** 添加用户数据源关联
     * @param array $data
     * @return int|string
     */
    public function insertUserRobot(array $data){
        return $this->insert($data);
    }

    /**获取用户所发布的数据源id
     * @param array $where
     */
    public function getRobotid(array $where){
        return  $this->where($where)->column('robotid');
    }








}

