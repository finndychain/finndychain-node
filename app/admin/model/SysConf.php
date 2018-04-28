<?php
namespace app\admin\model;
use think\Model;
use think\Cache;

class SysConf extends  Model
{

    protected $name = 'sys_conf';

    // 关闭自动写入update_time字段
    protected $updateTime = false;  //兼容mysql5.7

    /**获取所有系统配置信息
     * @return array|mixed
     */
    public function getSysConf(){

        $sys_conf = Cache::get('sys_conf');

        if(empty($sys_conf)){
            $sys_conf = $this->column('value','name');

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

        //每次更新完重新设置缓存
        $this->delSysConfCache('sys_conf');
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

