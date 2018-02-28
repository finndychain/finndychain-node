<?php
namespace app\admin\model;
use think\Model;

class UserRobot extends  Model
{

    protected $name = 'user_robot';

    /** 添加用户数据源关联
     * @param array $data
     * @return int|string
     */
    public function insertUserRobot(array $data){
        $data['create_time'] = time();
        return $this->insert($data);
    }

    /**获取用户所发布的数据源id
     * @param array $where
     */
    public function getRobotid(array $where){
        return  $this->where($where)->column('robotid');
    }








}

