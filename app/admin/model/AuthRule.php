<?php
namespace app\admin\model;
use think\Model;
use think\cache;



class AuthRule extends  Model
{

    protected $name = 'auth_rule';

    // 关闭自动写入update_time字段
    protected $updateTime = false;

    /**获取权限规则
     * @param array $where 条件
     * @return array|bool|false|\PDOStatement|string|Model
     */
    public function getRuleInfo(array $where){
        if(!is_array($where)) return false;
        return  $this->where($where)->find();
    }


    /** 查询制定字段的值
     * @param array $where
     * @param $field
     * @return array|false|\PDOStatement|string|Model
     */
    public function getRuleValue(array $where, $field)
    {
       $data = $this->where($where)->field($field)->select();

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


    /**
     * 数据排序
     * @param  array $data   数据源
     * @param  string $id    主键
     * @param  string $order 排序字段
     * @return boolean       操作是否成功
     */
    public function updateOrderData($data,$id='id',$order='sort'){

        foreach ($data as $key => $val) {
            if($this->where(array($id=>$key))->setField('sort',$val)){
                $result = true;
            }
        }
        return $result;
    }


    /**根据 权限id 来获取该条数据下面子级的id
     * @param $authruleid
     * @return array id 数组
     */
    public function getChilrenId($authruleid)
    {
        $authruleres = $this->select();
        return $this->_getChilrenId($authruleres,$authruleid);
    }

    public function _getChilrenId($authruleres,$authruleid)
    {
        static $arr=array();
        foreach ($authruleres as $k => $v) {
            if($v['pid'] == $authruleid){
                $arr[]=$v['id'];
                $this->_getChilrenId($authruleres,$v['id']);
            }
        }
        //asort($arr);
        return $arr;
    }


    /**获取权限规则列表 树状结构(父子关系)
     * @param $where array
     * @param $order array
     * @return array
     */
    public function getRuleFormatList($where=array(),$order=array('sort'=>'ASC')){
        $data = $this->getRuleList($where,$order);
        return self::ruleSortFormat($data);
    }


    /**根据pid 递归查询 权限(父子关系格式化)
     * @param $authRes
     * @param int $pid
     * @return array
     */
    static public function ruleSortFormat($authRes , $pid=0)
    {
        static $arr = array();
        foreach($authRes as $k => $v){
            if($v['pid'] == $pid ){
                $arr[] = $v;
                self::ruleSortFormat($authRes , $v['id']);
            }
        }
        return $arr;
    }

    /**
     * 获取权限规则列表 层级结构
     * @param   array $order 排序方式
     * @param  array $where  查询条件
     * @return array         结构数据
     */
    public function getTreeData($where=array(),$order=array('sort'=>'ASC') ){
        // 判断是否需要排
        $data = $this->getRuleList($where,$order);
        $data= self::channelLevel($data,0);
        $data = collection($data)->toArray();

        if(session('uid') != 1){
            foreach ($data as $k => $v) {

                if (authCheck($v['name'],session('uid'))) {

                    if(is_array($v['_data'])){
                        foreach ($v['_data'] as $m => $n) {
                            if(!authCheck($n['name'],session('uid'))){
                                // 删除无权限的规则
                                unset($data[$k]['_data'][$m]);
                            }
                        }
                    }
                }else{
                    // 删除无权限的规则
                    unset($data[$k]);
                }
            }
        }
        return $data;
    }

    /**获取权限规则列表
     * @param $where array
     * @param $order array
     * @return array
     */
    public function getRuleList($where=array(),$order=array('sort'=>'ASC'))
    {
        if(empty($where)){
            $authRes = $this->order($order)->select();
        }else{
            $authRes = $this->where($where)->order($order)->select();
        }
        $authRes = collection($authRes)->toArray();
        return $authRes;
    }

    /**
     * 返回多层栏目
     * @param $data 操作的数组
     * @param int $pid 一级PID的值
     * @param string $fieldPri 唯一键名，如果是表则是表的主键
     * @param string $fieldPid 父ID键名
     * @return array
     */
    static public function channelLevel($data, $pid = 0, $fieldPri = 'id', $fieldPid = 'pid')
    {
        if (empty($data)) {
            return array();
        }
        $arr = array();
        foreach ($data as $v) {
            if ($v[$fieldPid] == $pid) {
                $arr[$v[$fieldPri]] = $v;

                $arr[$v[$fieldPri]]["_data"] = self::channelLevel($data, $v[$fieldPri],  $fieldPri, $fieldPid);
            }
        }
        return $arr;
    }

}
