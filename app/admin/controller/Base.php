<?php
namespace app\admin\Controller;

use think\Session;
use app\common\controller\Bbase;
use app\admin\model\SysConf as modelSysConf;

class Base extends  Bbase
{
    protected function _initialize()
    {
        parent::_initialize();

        if(!Session::get('uid') || !Session::get('userinfo') || !Session::get('username')){
            $this->error('您尚未登录系统',url('login/dologin'));
        }

        if(empty($this->getSysConfValue('app_key'))||empty($this->getSysConfValue('app_secret'))){
           if(in_array($this->request->controller(),array('Robot'))){
               $this->error('请先配置云账号信息',url('Sysconf/SysSet'));
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
        $seoInfo['title']=$this->getSysConfValue('web_site_title');
        $seoInfo['kw']=$this->getSysConfValue('web_site_keyword');
        $seoInfo['desc']=$this->getSysConfValue('web_site_description');
        $this->assign('seoInfo',$seoInfo);
    }

    protected function checkUserType($userTypeNew=''){
        $userInfo = Session::get('userinfo');
        $userType = $userInfo['user_type'];
        if(empty($userTypeNew)||($userType<2)||($userTypeNew>$userType)){
            $this->error('没有权限操作！');
        }
    }

    protected function getUserInfo($key=''){
        $userInfo = Session::get('userinfo');
        if(!empty($key)){
            return $userInfo[$key];
        }
        return $userInfo;
    }



}
