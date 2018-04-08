<?php
namespace app\admin\model;
use think\Model;



class Tag extends  Model
{

    /**获取权限规则
     * @param array $where 条件
     * @return array|bool|false|\PDOStatement|string|Model
     */
    public function getTagInfo(array $where){
        if(!is_array($where)) return false;
        return  $this->where($where)->find();
    }


    /** 查询制定字段的值
     * @param array $where
     * @param $field
     * @return array|false|\PDOStatement|string|Model
     */
    public function getTagValue(array $where, $field)
    {
       $data = $this->where($where)->field($field)->select();
       if($data){
           $data = collection($data)->toArray();
       }
       return $data;
    }

    /**更新数据
     * @param array $where
     * @param $field
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function updateData(array $where ,array $data)
    {
        return  $this->where($where)->update($data);
    }



}
