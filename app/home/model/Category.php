<?php
namespace app\home\model;
use think\Model;
class Category  extends Model
{
    protected $name = 'article_category';
    public function getchilrenid($cateId){
        $cateRes=$this->select();
        $arr=$this->_getchilrenid($cateRes,$cateId);
        $arr[]=$cateId;
        $strId=implode(',', $arr);
        return $strId;
    }

    public function _getchilrenid($cateRes,$cateId){
        static $arr=array();
        foreach ($cateRes as $k => $v) {
            if($v['pid'] == $cateId){
                $arr[]=$v['id'];
                $this->_getchilrenid($cateRes,$v['id']);
            }
        }

        return $arr;
    }

    public function getparents($cateId){
        $cateRes=$this->field('id,pid,level,name')->select();
        $cates=$this->field('id,pid,level,name')->find($cateId);
        $pid=$cates['pid'];
        if($pid){
            $arr=$this->_getparentsid($cateRes,$pid);
        }
        $arr[]=$cates;
        return $arr;
    }

    public function _getparentsid($cateRes,$pid){
        static $arr=array();
        foreach ($cateRes as $k => $v) {
            if($v['id'] == $pid){
                $arr[]=$v;
                $this->_getparentsid($cateRes,$v['pid']);
            }
        }

        return $arr;
    }




}
