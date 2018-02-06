<?php
namespace app\admin\model;
use think\Model;
use think\Cache;

class SysConf extends  Model
{
    protected $table = 'cloud_sys_conf';


    /**获取所有系统配置信息
     * @return array|mixed
     */
    public function getSysConf(){
       // $sys_conf = Cache::get('sys_conf');

        if(empty($sys_conf)){
            $sys_conf = $this->column('value','name');

            //条件处理。
            //系统配置转换成键值对存储
            //$sys_conf = array_column($sys_conf_result,'value','name');
            Cache::set('sys_conf',$sys_conf);
        }
        return $sys_conf;
    }


    /**删除系统配置缓存
     * @return array|mixed
     */
    public function delSysConfCache($key = 'sys_conf'){
        return Cache::rm($key);
    }

    /**更新系统设置
     * @param $data
     */
    public function updateSysConf(array $data){
        foreach($data as $k=>$v){
           $this->where('name',$k)->setField('value',$v);
        }
    }


    /**获取单个系统配置信息
     * @return array|mixed
     */
    public function getSysConfValue($key='app_key'){
        if(empty($key))return false;
        $sys_conf = $this->getSysConf();
        if(!isset($sys_conf[$key]))return false;
        return $sys_conf[$key];
    }
}

