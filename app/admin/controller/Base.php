<?php
namespace app\admin\Controller;
use think\Controller;
use think\Session;
use app\admin\model\SysConf as modelSysConf;

class Base extends  Controller
{
    protected function _initialize()
    {

        //验证安装文件
        if (!is_file(ROOT_PATH . 'data/install.lock') || !is_file(APP_PATH . 'database.php')) {
            $this->redirect('install/index/index');
        }

        if(!Session::get('uid') || !Session::get('userinfo') || !Session::get('username')){
            $this->error('您尚未登录系统',url('login/dologin'));
        }

        if(empty($this->getSysConfValue('app_key'))||empty($this->getSysConfValue('app_secret'))){
           if($this->request->controller() != 'Sysconf'){
               $this->error('请先配置云账号信息','Sysconf/SysSet');
           }
        }

        $this->setPageSeo();

        $this->assign('currentAction',strtolower($this->request->controller().'/'.$this->request->action()));

    }

    /**获取所有系统配置信息
     * @return array|mixed
     */
    protected function getSysConf(){
       $modelSysConf = new modelSysConf();
       $sys_conf = $modelSysConf->getSysConf();
       return $sys_conf;
    }

    /**获取单个系统配置信息
     * @param string $key
     * @return bool
     */
    protected function getSysConfValue($key='app_key'){
        if(empty($key))return false;
        $sys_conf = $this->getSysConf();
        if(!isset($sys_conf[$key]))return false;
        return $sys_conf[$key];
    }

    /**系统SEO配置信息*/
    protected function setPageSeo(){
        $seoInfo['title']=$this->getSysConfValue('web_site_title');;
        $seoInfo['kw']=$this->getSysConfValue('web_site_keyword');;
        $seoInfo['desc']=$this->getSysConfValue('web_site_description');
        $this->assign('seoInfo',$seoInfo);
    }



}
