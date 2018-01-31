<?php
namespace app\admin\controller;
use think\Db;


class Sysconf extends Base
{
    public function Index()
    {
        $title = '权限设置';
        return view('index',['title'=>$title]);
    }

    //增加角色
    public function AddRole()
    {

        $title = '新增角色';
        return view('addrole', ['title' => $title]);
    }
    //修改角色
    public function EditRole($rid)
    {
        dump($rid);
        $title = '编辑角色';
        $list = array(
            'description' => '角色描述22222',
            'rolename' => '角色名称1111',
        );
        return view('addrole', ['title' => $title,'list'=>$list]);
    }

    //禁用角色
    public function BanRole()
    {
        dump(input());
    }


    //系统设置
    public function SysSet()
    {
        if(request()->isPost()) {
            $data = input();
            foreach($data as $k=>$v){
                Db::name('sys_conf')->where('name',$k)->setField('value',$v);
            }

            $this->success('修改成功','sysconf/sysset');
        }

        $title = '系统设置';

        $systeminfo = $this->getSysConf();

        $this->assign('systeminfo',$systeminfo);
        $this->assign([
            'title' => $title,
        ]);

        return $this->fetch('sysset');
    }



    //后台菜单设置
    public function MenuSet()
    {
        $title = '后台菜单';
        return view('menuset', ['title' => $title]);
    }
    //增加后台菜单
    public function AddMenu()
    {
        $title = '增加后台菜单';
        return view('addmenu', ['title' => $title]);
    }
    //编辑后台菜单
    public function EditMenu()
    {
        $title = '编辑后台菜单';
        return view('addmenu', ['title' => $title]);
    }

}
