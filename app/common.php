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
    $url = 'http://www.finndy.test/api/robot/'.$url;
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
    $base = new app\admin\controller\Base();
    $sys_conf = $base->getSysConf();
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
    //dump(api_build_url('api.php',$params));
    $res = api_request('get' ,api_build_url('api.php',$params));
    $catarr = check_api_result($res);
    return $catarr;
}
