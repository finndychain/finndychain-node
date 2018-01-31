<?php
namespace app\admin\model;
use think\Model;
use think\Session;


class SysConf extends  Model
{
    protected $table = 'cloud_sys_conf';


    /**获取所有系统配置信息
     * @return array|mixed
     */
    public function getSysConf(){
        $sys_conf = Session::get('sys_conf');
        if(empty($sys_conf)){
            $sys_conf_result = $this->select();
            //条件处理。预留
            //print_r($sys_conf_result);
            //系统配置转换成键值对存储
            $sys_conf = array_column($sys_conf_result,'value','name');
            Session::set('sys_conf',$sys_conf);
        }
        return $sys_conf;
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
