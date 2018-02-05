<?php
namespace app\admin\Controller;
use think\Controller;
use think\Session;
use app\admin\model\SysConf;

class Base extends  Controller
{
    public function _initialize(){

        //验证安装文件
        if (!is_file(ROOT_PATH . 'data/install.lock') || !is_file(APP_PATH . 'database.php')) {
            $this->redirect('install/index/index');
        }

        if(!Session::get('uid') || !Session::get('userinfo') || !Session::get('username')){
            $this->error('您尚未登录系统',url('login/dologin'));
        }

        if(empty($this->getSysConfValue('app_key'))&&$this->request->controller() != 'Sysconf'){
            $this->error('未填写授权信息','Sysconf/SysSet');
        }


    }

    /**获取所有系统配置信息
     * @return array|mixed
     */
    public function getSysConf(){
       $modelSysConf = new SysConf();
       $sys_conf = $modelSysConf->getSysConf();
       return $sys_conf;
    }

    /**获取单个系统配置信息
     * @param string $key
     * @return bool
     */
    public function getSysConfValue($key='app_key'){
        if(empty($key))return false;
        $sys_conf = $this->getSysConf();
        if(!isset($sys_conf[$key]))return false;
        return $sys_conf[$key];
    }


    protected function setPageSeo($title=''){
        $pageSeo['title']=$title;
        $pageSeo['keywords']=$title;
        $pageSeo['desc']=$title;
        $this->assign($pageSeo);
    }



}
