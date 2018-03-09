<?php
namespace app\admin\model;
use think\Model;


class FinndyData extends  Model
{

    protected $name = 'finndy_data';
    //删除数据
    public function cleardata($robotid,$type)
    {


        $onedayago = time() - 24 * 3600;
        if ($type == 99999) {//删除全部数据
            $res = $this->where(array('robotid' => $robotid))->delete();
            if ($res) {
                return $msg = 'succ';
            } else {
                return $msg = 'fail';
            }
        } else {//删除最近一天的数据
            $map['robotid'] = $robotid;
            $map['create_time'] = ['>', $onedayago];
            $res = $this->where($map)->delete();

            if ($res) {
                return $msg = 'succ';
            } else {
                return $msg = 'fail';
            }
        }
    }

    /**写入本地数据库
     * @param $data
     * @return mixed
     */
    public function insertdata($data){
        return $this->insert($data);
    }

}
