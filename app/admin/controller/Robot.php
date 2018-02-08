<?php
namespace app\admin\controller;
use app\admin\model\FinndyData;


class Robot extends Base
{
    public function add()
    {
        if(request()->isPost()){
            $postdata = input();

            $validate = $this->validate($postdata,'Robot.add');//使用validate验证
            if(true !== $validate){
                // 验证失败 输出错误信息
                $this->error($validate);
            }
            $params = $postdata;
            $params['op'] = 'valuesubmit';
            $res = api_request('POST' ,'api.php', api_build_params($params));

            if($res['error_code'] === 0){
                $this->success('创建数据源成功','robot/robotlist');
            }else{
                $this->error('创建失败');
            }
        }
        //不同saas版本权限的前端最大最小显示值处理


        $theuser = $this->getuserrobotver();

        $catarr = getcategory();


        $title = '创建数据源';
        $tabbox  = 'style="display:none;"';
        $op = 'add';
        $thevalue = array();
        $this->assign([
            'title' => $title,
            'tabbox' => $tabbox,
            'catarr' => $catarr,
            'op' => $op,
            'theuser' => $theuser,
            'thevalue' =>$thevalue,
        ]);
        return $this->fetch('add');
    }

    //编辑源
    public function edit($robotid)
    {
        if(request()->isPost()){
            $postdata = input();
            $validate = $this->validate($postdata,'Robot.edit');//使用validate验证
            if(true !== $validate){
                // 验证失败 输出错误信息
                $this->error($validate);
            }
            $params = $postdata;
            $params['op'] = 'valuesubmit';
            $a = api_build_params($params);
            $res = api_request('POST' ,'api.php', api_build_params($params));
            if($res['error_code'] === 0){
                $this->success('编辑数据源成功','robot/robotlist');
            }else{
                $this->error($res['reason']);
            }
        }

        $thevalue = '';
        $op = 'edit';
        //不同saas版本权限的前端最大最小显示值处理
        $theuser = $this->getuserrobotver();

        $robotid = intval(input('robotid'));
        $catarr = getcategory();
        $params['op'] = 'getrobotrule';
        $params['robotid'] = $robotid;
        $res = api_request('get' ,api_build_url('api.php',$params));

        if($res['error_code'] != 0){
            $this->error('参数有误');
        }else{
            $thevalue= check_api_result($res);
            $title = '编辑源';
            $tabbox  = 'style="display:none;"';
            $this->assign([
                'title' => $title,
                'tabbox' => $tabbox,
                'catarr' => $catarr,
                'op' => $op,
                'theuser' => $theuser,
                'thevalue' => $thevalue,
            ]);
            return $this->fetch('add');
        }

    }
    //复制源
    public function copy()
    {
        if(request()->isPost()){
            $postdata = input();
            $validate = $this->validate($postdata,'Robot.copy');//使用validate验证
            if(true !== $validate){
                // 验证失败 输出错误信息
                $this->error($validate);
            }
            $copyrobotid = $postdata['robotid'];
            $params = $postdata;
            unset($params['robotid']);
            $params['op']='valuesubmit';
            $res = api_request('POST' ,'api.php', api_build_params($params));
            if($res['error_code'] === 0){
                $this->success('创建数据源成功','robot/robotlist');
            }else{
                $url = url('robot/copy' , array('robotid'=>$copyrobotid));
                $this->error($res['reason'],$url);
            }

            return;
        }
        //不同saas版本权限的前端最大最小显示值处理
        $theuser = $this->getuserrobotver();
        $op = 'copy';
        $robotid = intval(input('robotid'));
        $catarr = getcategory();
        $params['robotid'] = $robotid;
        $params['op']='copyrobotrule';//给robotid赋值0 name加copy
        $list = api_request('get' , api_build_url('api.php',$params));

        if($list['error_code'] != 0){
            $this->error('参数有误');
        }else{
            $thevalue=$list['result'];
            $thevalue['robotid'] = $robotid;//通过隐藏域提交后 如果创建失败返回带上该id
            $title = '复制源';
            $tabbox  = 'style="display:none;"';
            $this->assign([
                'title' => $title,
                'tabbox' => $tabbox,
                'catarr' => $catarr,
                'op' => $op,
                'thevalue' => $thevalue,
                'theuser' => $theuser,
            ]);
            return $this->fetch('add');
        }

    }

    //开始采集
    public function stoprun()
    {
        $robotid = input('robotid');
        $params['robotid'] = $robotid;
        $params['op'] = 'stoprun';
        $res = api_request('get', api_build_url('api.php', $params));
        if ($res['error_code'] != 0) {
            echo $res['reason'];

        }else{
            //TODOLIST
            echo $res;
        }
    }
    //停止采集
    public function startrun()
    {
        $robotid = input('robotid');
        $params['robotid'] = $robotid;
        $params['op'] = 'startrun';
        $res = api_request('get', api_build_url('api.php', $params));
        if ($res['error_code'] != 0) {
            echo $res['reason'];

        }else{
            //TODOLIST
            echo $res;
        }
    }
    //运行测试
    public function debugrobot(){
        $robotid = 264564;
        $params['robotid'] = $robotid;
        $params['op'] = 'debugrobot';

        $res = api_request_html('get', api_build_url('api.php', $params));

        $this->assign(
            'res',$res
        );
       return  $this->fetch('debug');

    }
    //采集进度
    //运行测试
    public function progress(){
        $robotid = $_POST['robotid'];//input('robotid');
        $params['robotid'] = $robotid;
        $params['op'] = 'progress';
        $res = api_request('POST','api.php', api_build_params( $params));
        if ($res['error_code'] != 0) {
            echo $res['reason'];
        }else{
            //TODOLIST
            return json($res['result']);
        }
    }

    //导出规则
    public function export()
    {
        $robotid = intval(input('robotid'));
        if(empty($robotid)){
            return false;
        }
        $name = input('robotname');
        $params['robotid'] = $robotid;
        $params['op']='exportrule';
        $res = api_request('get' , api_build_url('api.php',$params));
        $exporttext = check_api_result($res);
        $filename = $name;
        ob_clean();
        header('Content-Encoding: none');
        header('Content-Type: '.(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream'));
        header('Content-Disposition: attachment; filename="'.$filename.'.txt"');
        header('Content-Length: '.strlen($exporttext));
        header('Pragma: no-cache');
        header('Expires: 0');

        echo $exporttext;

    }


    //导入规则
    public function import()
    {
        $title = '导入规则';
        return view('import',['title'=>$title] );
    }

    //导入规则
    public function importcopy()
    {
        if(request()->isPost()){
            if(input('post.importtext')){
                if(empty(trim(input('post.importtext')))){
                    $this->error('请输入正确的数据源规则');
                }
                //不同saas版本权限的前端最大最小显示值处理
                $theuser = $this->getuserrobotver();

                $op = 'importcopy';
                $params['importtext']=input('post.importtext');
                $params['op']='importcopyrobotrule';
                $res = api_request('POST' ,'api.php', api_build_params($params));
                $thevalue = check_api_result($res);
                $catarr = getcategory();
                $title = '导入规则';
                $this->assign([
                    'title' => $title,
                    'catarr' => $catarr,
                    'op' => $op,
                    'thevalue' => $thevalue,
                    'theuser'=> $theuser,
                ]);
                return $this->fetch('add');
            }else{

                $postdata = input();
                $params = $postdata;
                $params['op']='valuesubmit';
                $res = api_request('POST' ,'api.php', api_build_params($params));

                if($res['error_code'] === 0){
                    $this->success('创建数据源成功','robot/robotlist');
                }else{
                    $this->error('创建失败,请重新编辑');
                }
                return;
            }
            return;
        }

    }

    //数据源列表
    public function robotlist()
    {
        $title = '数据源列表';
        $tabbox  = 'style="display:none;"';
        $params['op'] = 'getrobotslist';

        $res = api_request('get' ,api_build_url('api.php',$params));
        $arr = check_api_result($res);

        $listarr = $arr['data']?$arr['data']:array();
        foreach($listarr as &$item){
            $item['status_desc']=lang('cp_source_available_font_'.$item['status']);
        }
        $this->assign([
            'title' => $title,
            'listarr'=>$listarr,
            'tabbox'=>$tabbox,
        ]);
        return $this->fetch('robotlist');
    }
    //数据源详情
    public function detail()
    {
        $title = '源详情';
        $tabbox  = 'style="display:none;"';
        $robotid = intval(input('robotid'));
        $params['op'] = 'getrobotextfield';
        $params['robotid'] = $robotid;
        $res = api_request('get' ,api_build_url('api.php',$params));
        if($res['error_code'] != 0){
            $this->error('参数有误');
        }
        $thevaluearr = $res['result'];
        $thevaluearr['status_desc'] = lang('cp_source_available_font_'.$thevaluearr['status']);


        $save_method = $this->getSysConfValue('save_method');
        if(empty($save_method)){
            $datacount =$thevaluearr['datacount'];
            $params['op'] = 'getrobotdata';
            $params['robotid'] = $robotid;
            $res = api_request('get' ,api_build_url('api.php',$params));
            if($res['error_code'] == 0){
                $jsonpurl = $res['result'];
            }else{
                $this->error('参数有误');
            }
            //获取导出csv和jsonurl
            $params['op'] = 'getrobotdataurl';
            $res = api_request('get' ,api_build_url('api.php',$params));
            if($res['error_code'] == 0){
                $publuc_exporturl_arr = $res['result'];
            }else{
                $this->error('参数有误');
            }
            //数据导出url
            $publuc_exportjson_url = $publuc_exporturl_arr['apiurl_json'];
            $publuc_exportcsv_url = $publuc_exporturl_arr['export_csv_url'];
            //清除数据url
            $clear_data_all = $publuc_exporturl_arr['danger_all'];
            $clear_data_one = $publuc_exporturl_arr['danger_one'];
            $jsonpurl = $jsonpurl;
        }else{
            //本地
            $data = new FinndyData();
            $datacount = $data->where('robotid',$robotid)->count();
            $datacount      = intval($datacount);

            $jsonpurl = url('robot/getjsonp' , array('robotid'=>$robotid));
            $publuc_exportcsv_url = url('robot/export_csv' , array('robotid'=>$robotid));
            $publuc_exportjson_url =url('robot/export_json' , array('robotid'=>$robotid,'pagesize'=>'20','pageindex'=>'0','sortby'=>'desc')) ;
            $clear_data_all = url('robot/cleardata' , array('robotid'=>$robotid,'type'=>9999));
            $clear_data_one = url('robot/cleardata' , array('robotid'=>$robotid,'type'=>1));
        }
        $this->assign([
            'publuc_exportjson_url' => $publuc_exportjson_url,
            'publuc_exportcsv_url' => $publuc_exportcsv_url,
            'jsonpurl' => $jsonpurl,
            'clear_data_all' => $clear_data_all,
            'clear_data_one' => $clear_data_one,
        ]);
        $this->assign([
            'title' => $title,
            'save_type' => $save_type,
            'tabbox' => $tabbox,
            'thevaluearr' => $thevaluearr,
            'datacount' => $datacount,
            'jsonpurl' => $jsonpurl,
        ]);
        return $this->fetch('detail');
    }

    //jqgrid 获取数据
    public function getjsonp(){
        //整理参数
        $errorcode = 0;
        $robotid = intval(input('robotid'));

        $pageindex	= empty($_GET['page'])? 1 :abs(intval($_GET['page']));	    //当前查询页码
        $pagesize	= empty($_GET['rows'])? 0 :abs(intval($_GET['rows']));	    //每页数量
        $callback   = isset($_GET['callback']) ? trim($_GET['callback']) : '';  //jsonp回调参数
        $retarray   = array();
        $start		= ($pageindex-1) * $pagesize;

        $params['op'] = 'getrobotextfield';
        $params['robotid'] = $robotid;
        $res = api_request('get' ,api_build_url('api.php',$params));

        $resarr = check_api_result($res);
        $used_extfield_arr = $resarr['used_extfield_arr'];

        //采集数据量
        $data = new FinndyData();
        $listcount = $data->where('robotid',$robotid)->count();
        $thevalue = $data->where('robotid',$robotid)->order('itemid')->limit($start,$pagesize)->select();


        if($listcount){
            foreach($thevalue as $retarr){

                $tmparr = array();
                $tmparr["itemid"] = ''.$retarr['itemid'];
                $tmparr["subject"] = ''.stripslashes($retarr['subject']);
                $tmparr["message"] = ''.stripslashes($retarr['message']);
                foreach($used_extfield_arr as $v){
                    $tmparr["extfield".$v] = ''.stripslashes($retarr['extfield'.$v]);
                }
                $tmparr["dateline"] = ''.date('Y-m-d H:i:s',$retarr['create_time'] );
                //组合数据二维数组
                $retarray["rows"][] = $tmparr;
            }

            $retarray['errorcode']  = $errorcode;
            $retarray["records"]    = $listcount ;                         //返回总记录数
            $retarray["page"]       = $pageindex;                                                               //当前页面数
            $retarray["total"]      = ($retarray["records"]%$pagesize) ? intval($retarray["records"]/$pagesize)+1 : intval($retarray["records"]/$pagesize);
        }else{
            $errorcode = 10001;
        }
        $tmp= json_encode($retarray);
        echo $callback . '(' . $tmp .')';
    }

    //获取用户采集工具版本信息
    public function getuserrobotver(){
        $params['op'] = 'getuserinfo';
        $res = api_request('get' , api_build_url('api.php',$params));
        $theuser = check_api_result($res);
        return $theuser;

    }
    //导出数据csv
    public function export_csv(){
        ob_clean();
        define('SEPERATOR',',');  //分隔符 ',',';','空格 ','制表符'
        $robotid = intval(input('robotid'));
        $params['op'] = 'getrobotextfield';
        $params['robotid'] = $robotid;
        $res = api_request('get' ,api_build_url('api.php',$params));
        $resarr = check_api_result($res);
        $used_extfield_arr = $resarr['used_extfield_arr'];
        $arrcount = count($used_extfield_arr);

        for($i=-3; $i<$arrcount+1; $i++) {
            $fid = $used_extfield_arr[$i];
            if ($i == -3) {
                $titleid = 'itemid';
                $titlestr = 'ID';
            } elseif ($i == -2) {
                $titleid = 'subject';
                $titlestr = '标题';
            } elseif ($i == -1) {
                $titleid = 'message';
                $titlestr = '内容';
            } elseif ($i == $arrcount) {
                $titleid = 'dateline';
                $titlestr = '挖掘时间';
            } else {
                $titleid = "extfield" . $fid;
                $tmpvarstr = 'extfieldreg' . $fid;
                if ($theexportvalue[$tmpvarstr] == "") {
                    $elablestr = '扩展字段' . $fid;
                } else {
                    $theexportvalue[$tmpvarstr] = unserialize($theexportvalue[$tmpvarstr]);
                    if ($theexportvalue[$tmpvarstr]['extfieldalias']) {
                        $elablestr = sstripslashes(base64_decode($theexportvalue[$tmpvarstr]['extfieldalias']));
                    } else {
                        $elablestr = '扩展字段' . $fid;
                    }
                }
                $titlestr = $elablestr;
            }
            $dataarray['exportColumns'][$i+3] = array(
                "id"            => $titleid,
                "title"         => $titlestr,
            );
        }

        $header = array();
        foreach($dataarray['exportColumns'] as $title){
            $header[] = iconv('UTF-8', 'GBK', $title['title']);
        }
        //初始化数据
        $exportFileName = $resarr['name'].'-' . mt_rand() . '.csv';
        header("Content-Type: application/CSV");
        header("Content-Disposition:attachment;filename=" . $exportFileName);
        header("Pragma: no-cache");
        header("Expires: 0");
        //表头
        $data[] = $header;
        $finndydata = new FinndyData();
        $thevalue = $finndydata->where('robotid', $robotid)->order('itemid')->select();
        $listcount = count($thevalue);
        if($listcount){
            foreach($thevalue as $retarr){
                $tmparr = array();
                $tmparr["itemid"] =  iconv('UTF-8', 'GBK', ''.$retarr['itemid']);
                $tmparr["subject"] = iconv('UTF-8', 'GBK', ''.stripslashes($retarr['subject']));
                $tmparr["message"] = iconv('UTF-8', 'GBK', ''.stripslashes($retarr['message']));
                foreach($used_extfield_arr as $v){
                    $tmparr["extfield".$v] =iconv('UTF-8', 'GBK', ''.stripslashes($retarr['extfield'.$v]));
                }
                $tmparr["dateline"] = iconv('UTF-8', 'GBK', ''.date('Y-m-d H:i:s'));
                $data[] = $tmparr;
            }
            //输出CSV
            out_put_csv($data);
        }

    }
    //导出json
    public function export_json(){
        $data = input('param.');

        $errorcode = 0;
        $robotid = intval($data['robotid']);
        $pageindex = empty($data['pageindex'])? 0 :intval($data['pageindex']);	    //查询页码
        $pagesize	= empty($data['pagesize'])? 20 :intval($data['pagesize']);	//每页数量固定20最大30
        $sortby	= ($data['sortby'] == 'desc')? 'DESC' : 'ASC'; //排序
        $datatype = 'json';
        if($pagesize>30){
            $pagesize	= 30;
        }
        //参数处理
        $pageindex	= ($pageindex < 0) ? 0 : $pageindex;
        $start		= $pageindex * $pagesize;
        $listcount  = 0;
        $dataarray  = array();

        //查询源机器人使用的扩展字段
        $params['op'] = 'getrobotextfield';
        $params['robotid'] = $robotid;
        $res = api_request('get' ,api_build_url('api.php',$params));
        $resarr = check_api_result($res);
        $used_extfield_arr = $resarr['used_extfield_arr'];

        //采集数据量
        $data = new FinndyData();
        $listcount = $data->where('robotid',$robotid)->count();
        $thevalue = $data->where('robotid',$robotid)->order('itemid')->limit($start,$pagesize)->select();
        if($listcount){
            foreach($thevalue as $retarr){
                $tmparr = array();
                $tmparr["itemid"] = ''.$retarr['itemid'];
                $tmparr["subject"] = ''.stripslashes($retarr['subject']);
                $tmparr["message"] = ''.stripslashes($retarr['message']);
                foreach($used_extfield_arr as $v){
                    $tmparr["extfield".$v] = ''.stripslashes($retarr['extfield'.$v]);
                }
                //$tmparr["dateline"] = ''.date($retarr['dateline'], 'Y-m-d H:i:s');
                //组合数据二维数组
                $dataarray[]  = $tmparr;
            }

        }
        //计算本次请求返回的数据条数
        $retcount   = getretcount($listcount, $pageindex, $pagesize);
        $retarray   = array(
            "errorcode" => $errorcode,
            "allcount"  => intval($listcount),
            "retcount"  => $retcount,
            "pageindex" => $pageindex,
            "pagesize"  => $pagesize,
            "datalist"  => $dataarray
        );
        //生成响应API
        $response = new \org\Response();
        $response->gen($retarray,$datatype);

    }

    //删除数据
    public function  cleardata(){
        $type = intval(input('type'));
        if($type != 1 && $type != 9999){
            $this->error('参数有误');
        }
        $robotid = intval(input('robotid'));
        if(empty($robotid)){
            $this->error('参数有误');
        }
        $data = new FinndyData();
        $listcount = $data->where('robotid',$robotid)->count();
        if(empty($listcount)){
            $this->error('暂无数据');
        }else{
            $res = $data->cleardata($robotid , $type);
            if($res == 'succ'){
                $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }
        }

    }

    public function policy(){
        return $this->fetch('policy');
    }



}
