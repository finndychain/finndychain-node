<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function api_request($method, $url, $fields=''){
    $url = config('api_url').$url;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    if ($method === 'POST')
    {
        curl_setopt($ch, CURLOPT_POST, true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    }
    $result = curl_exec($ch);
    $result = json_decode($result,true);
    curl_close($ch);
    return $result;
}
function api_request_html($method, $url, $fields=''){
    $url = config('api_url').$url;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    if ($method === 'POST')
    {
        curl_setopt($ch, CURLOPT_POST, true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    }
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/**API请求生成签名
 * @param $params  array  所有请求传递变量和值的二维数组
 * @return $str  string|md5
 */
function api_sign_create($params=array()){
    $str='';
    //验证读取appSecret对应的信息
    $appSecret =api_get_appkey('app_secret');


    ksort($params);
    foreach ($params as $k => $v) {
        $str .= $k . $v ;
    }
    return strtoupper(md5($str . $appSecret));
}


function api_build_url($path='',$params=array()){
    if(empty($path)){
        return false;
    }
    $params = array_filter($params);
    $params['appkey']= api_get_appkey('app_key');
    $params['time']=time();
    $params['sign'] = api_sign_create($params);
    $url = http_build_query($params);
    $url = $path.'?'.$url;

    return $url;
}

function api_build_params($params=array(),$appkey = ''){
    if(empty($appkey)){
        $appkey = api_get_appkey('app_key');
    }
    //dump($params);
    $params = array_filter($params);
    $params['appkey']= $appkey;
    $params['time']=time();
    $params['sign'] = api_sign_create($params);
    return http_build_query($params);

}

/** 从api获取数据 根据code判断状态
 * @param array $params
 * @return bool|mixed
 */
function check_api_result($params=array()){
       if($params['error_code'] === 0){
            return $params['result'];
       }else{
           return false;
       }
}

//验证读取appkey对应的信息
function api_get_appkey($name='app_key'){

    if(empty($name))return false;
    $model = new app\admin\model\SysConf;
    $sys_conf = $model->getSysConf();
    if(!isset($sys_conf[$name]))return false;
    return $sys_conf[$name];
}

/**将规则导出 csv格式
 * @param $array    导出的规则
 * @param $filename 生成csv文件名
 */
function exportfile($array, $filename) {

    $time = date('Y-m-d H:i:s');
    $array['version'] = strip_tags('3.0');
    $exporttext = "# -------------------------------------------------\r\n".
        "# 私有云采集引擎规则\r\n".
        "# Time: $time\r\n".
        "# -------------------------------------------------\r\n\r\n\r\n".
        wordwrap(random(3).base64_encode(serialize($array)), 80, "\r\n", 1);

    ob_clean();
    header('Content-Encoding: none');
    header('Content-Type: '.(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream'));
    header('Content-Disposition: attachment; filename="'.$filename.'.txt"');
    header('Content-Length: '.strlen($exporttext));
    header('Pragma: no-cache');
    header('Expires: 0');

    echo $exporttext;
}
function random($length, $numeric = 0) {
    PHP_VERSION < '4.2.0' ? mt_srand((double)microtime() * 1000000) : mt_srand();
    $seed = base_convert(md5(print_r($_SERVER, 1).microtime()), 16, $numeric ? 10 : 35);
    $seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
    $hash = '';
    $max = strlen($seed) - 1;
    for($i = 0; $i < $length; $i++) {
        $hash .= $seed[mt_rand(0, $max)];
    }

    return $hash;
}




/**将采集数据导出csv格式
 * @param $data
 */
function out_put_csv($data){
    $file_path="php://output";
    $fp = fopen($file_path, 'a');
    foreach($data as $item) {
        //数据内容二维数组
        fputcsv($fp, $item,SEPERATOR);
        ob_flush();
    }
    fclose($fp);
}


/**采集数据以json格式导出 统计导出数据数
 * @param $listcount
 * @param int $pageindex
 * @param int $pagesize
 * @return int
 */
function getretcount($listcount, $pageindex=0, $pagesize=20){
    $retcount= 0;
    $listcount = intval($listcount);
    if ($listcount <= $pagesize){
        $retcount = empty($pageindex) ? $listcount : 0;
    }else{
        if ($listcount >= $pagesize * $pageindex + $pagesize){
            $retcount = $pagesize;
        }else{
            $retcount = ($pagesize * $pageindex <= $listcount) ? $listcount % $pagesize : 0;
        }
    }
    return $retcount;
}

/**加密方法
 * @param $data
 * @return string
 */
function passwordencrypt($data) {
    return md5(md5('finndycloud'.md5($data)));
}


//获得分类
function getcategory(){
    $params['op'] = 'getcategory';
    $res = api_request('get' ,api_build_url('api.php',$params));
    $catarr = check_api_result($res);
    return $catarr;
}

/**
 * [[单汉字和单英文字母均为1长度]]
 * 字符串按个数截取函数（不同于下面的msgarr按字节截取），支持中文和其它编码
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式，默认"utf-8"
 * @param string $suffix 截断显示字符，默认"..."
 * @return string
 * echo msubstr("出门不带书没有安全感，虽然很多时候都没机会翻没有钱包",0,10); //出门不带书没有安全感...
 * echo msubstr("昆仑2副本！坑爹了Powered by Discuz!",0,10);					 //昆仑2副本！坑爹了P...
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
{
//    if(function_exists("mb_substr"))
//        return mb_substr($str, $start, $length, $charset);
//    elseif(function_exists('iconv_substr')) {
//        return iconv_substr($str,$start,$length,$charset);
//    }
    $re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if(count($match[0]) - $start > $length)
        return $suffix ? $slice."..." : $slice;
    return $slice;
}

//生成分页URL地址集合
//phpurl=0可以利用geturl转换（mpurl需为数组）
function multi($num, $perpage, $curpage, $mpurl, $phpurl=1) {

    global $_SHTML, $lang, $_SGLOBAL;
    //echodebuglog($curpage.'--'.$perpage.'--'.$num);
    if(($curpage-1)*$perpage >= $num) //如果只是”>“会出现num和perpage相等时第二页小异常
       // showmsg('start_listcount_error');

    $maxpages = config('maxpages');
    $multipage = $a_name = '';
    if($phpurl) {
        //如果没有其他参数 ?后面直接 接page=
        if(strlen(strchr($mpurl,'?')) == 1){
            $mpurl .= '';
        }else{
            $mpurl .= strpos($mpurl, '?') ? '&' : '?';
        }
    } else {
        $urlarr = $mpurl;
        unset($urlarr['php']);
        unset($urlarr['modified']);
    }
    if($num > $perpage) {
        $page = 6;//UI中总显示页数(原来为10,调6避免UI过长)
        $offset = 2;
        $realpages = @ceil($num / $perpage);
        $pages = $maxpages && $maxpages < $realpages ? $maxpages : $realpages;
        if($page > $pages) {
            $from = 1;
            $to = $pages;
        } else {
            $from = $curpage - $offset;
            $to = $curpage + $page - $offset - 1;
            if($from < 1) {
                $to = $curpage + 1 - $from;
                $from = 1;
                if(($to - $from) < $page && ($to - $from) < $pages) {
                    $to = $page;
                }
            } elseif($to > $pages) {
                $from = $curpage - $pages + $to;
                $to = $pages;
                if(($to - $from) < $page && ($to - $from) < $pages) {
                    $from = $pages - $page + 1;
                }
            }
        }
        if($phpurl) {
            $url = $mpurl.'page=1'.$a_name;
            $url2 = $mpurl.'page='.($curpage - 1).$a_name;
        }
        $multipage = '<div class="pages"><div>'.($curpage - $offset > 1 && $pages > $page ? '<a href="'.$url.'">1...</a>' : '').($curpage > 1 ? '<a class="prev" href="'.$url2.'">'.lang('pre_page').'</a>' : '');
        for($i = $from; $i <= $to; $i++) {
            if($phpurl) {
                $url = $mpurl.'page='.$i.$a_name;
            }
            $multipage .= $i == $curpage ? '<strong>'.$i.'</strong>' : '<a href="'.$url.'">'.$i.'</a>';
        }

        if($phpurl) {
            $url = $mpurl.'page='.($curpage + 1).$a_name;
            $url2 = $mpurl.'page='.$pages.$a_name;
        }

        $multipage .= ($to < $pages && $curpage < $maxpages ? '<a href="'.$url2.'" target="_self">...'.$realpages.'</a>' : '').
            ($curpage < $pages ? '<a class="next" href="'.$url.'">'.lang('next_page').'</a>' : '').
            ($pages > $page ? '' : '');
        $multipage .= '</div></div>';
    }

    return $multipage;
}

/**权限判断
 * @param $name 当前控制器/方法
 * @param $uid 用户id
 * @return bool
 */
function authCheck($name ,$uid){
    if (empty($uid)) {
        return false;
    }
    if ($uid == 1) {
        return true;
    }
    $auth = new  org\Auth();
    return $auth->check($name ,$uid);
}

function getParentId($data , $id , $clear=False){
    static $arr=array();
    if($clear){
        $arr=array();
    }
    foreach ($data as $k => $v) {
        if($v['id'] == $id){
            $arr[]=$v['id'];
            getParentId($data,$v['pid'],False);
        }
    }

    asort($arr);
    $arrStr=implode(',', $arr);
    return $arrStr;
}


/**
 * 云存储上传文件内容
 *
 * @param string $object 上传的文件名称 (支持目录形式，例如：test/img001.jpg)
 * @param string $path   本地文件路径  ($_FILE['name']['tmp_name'])
 * @return array $return 上传结果
 */
function oss_upload_file($object,$path){
    $return =array();
    try{
        //获取配置项，并赋值给对象$config
        $config=config('aliyun_oss');
        //实例化OSS
        $ossClient=new \OSS\OssClient($config['KeyId'],$config['KeySecret'],$config['Endpoint']);
        //uploadFile的上传方法
        $rs = $ossClient->uploadFile($config['Bucket'], $object, $path);
        //print_r($rs);
        $return['md5'] = $rs['content-md5'];
        $return['sizeUpload'] = $rs['size_upload'];
        $return['totalTime'] = $rs['total_time'];
        $saveInfo=explode('aliyuncs.com/',$rs['info']['url']);
        $return['savePath'] = $saveInfo[1]; //aliyuncs.com

    } catch(OssException $e) {
        //如果出错这里返回报错信息
        return $e->getMessage();
    }
    //否则，完成上传操作
    return $return;
}

/**
 * 云存储删除指定的文件内容
 * @param string $object object名称
 * @param array $options
 * @return null
 */
function oss_remove_file($object){
    //获取配置项，并赋值给对象$config
    $config=config('aliyun_oss');
    //实例化OSS
    $ossClient=new \OSS\OssClient($config['KeyId'],$config['KeySecret'],$config['Endpoint']);
    $rs = $ossClient->deleteObject($config['Bucket'],$object);
    return $rs;
}

/**
 * 云存储获取显示的文件URL
 * @param string $path 文件路径
 * @param int $style  显示格式类型（默认为原图）
 * @return string $url
 */
function oss_display_image($path,$style=0){
    $ds = '!';
    //获取配置项，并赋值给对象$config
    $fsDomain=config('aliyun_oss.FileDomain');
    $url =  $fsDomain.'/'.$path;
    switch ($style){
        case 1://竖立
            $url = $url.$ds.'400x300';
            break;
        case 2: //横向
            $url = $url.$ds.'800x600';
            break;
        default://原图

         break;
    }

    return $url;
}