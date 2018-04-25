<?php
namespace app\admin\model;
use think\Model;


class AuthGroup extends  Model
{

    protected $name = 'auth_group';

    // 关闭自动写入update_time字段
    protected $updateTime = false;

    /** 查询制定字段的值
     * @param array $where
     * @param $field
     * @return array|false|\PDOStatement|string|Model
     */
    public function getRuleValue(array $where, $field)
    {
        return $this->where($where)->field($field)->find();
    }

    /**插入数据
     * @param array $data
     * @return int|string
     */
    public function saveData(array $data)
    {
        return $this->insert($data);
    }

    public function updateData(array $where ,array $data)
    {
        return  $this->where($where)->update($data);
    }


}
