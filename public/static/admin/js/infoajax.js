var num_item = 0;
var bool_ext = true;
function setitem(){
	objudiv = document.getElementById('udiv');
	adddiv = document.getElementById('url');
	var checkid = document.getElementById('robotid').value;
    var arr = new Array();
    arr=adddiv.value.split("\n");

    for(var i=0;i<arr.length;i++){
        if(arr[i] != ''){

			if(checkid == ''|| !checkid || checkid == 0 || checkid == '0'){
				//添加入口源链接重复检测
				hostsimilaritycheck(arr[i]);
			}

			urln = document.createElement('div');
            urln.id = 'url_' + num_item;
            urln.style.cssText = 'padding:5px;border:1px solid #ccc;margin-bottom:5px;word-break: break-all;';

            urln.appendChild(document.createTextNode(arr[i]));
            urln.appendChild(document.createTextNode(' '));

            oBody = document.createElement('a');
            oBody.href = 'javascript:;';
            oBody.title = '';
            if(window.document.all != null) // IE
                oBody.attachEvent('onclick', new Function('delitem('+num_item+');'));
            else // Firefox
                oBody.addEventListener('click', new Function('delitem('+num_item+');'), false);
            oBody.appendChild(document.createTextNode('删除'));
            urln.appendChild(oBody);

            oBody = document.createElement("input");
            oBody.id = 'listurl_manual[]';
            oBody.name = 'listurl_manual[]';
            oBody.type = 'text';
            oBody.value = arr[i];
            oBody.size = '5';
            oBody.style.display = 'none';
            urln.appendChild(oBody);

            objudiv.appendChild(urln);
            adddiv.value = '';
            num_item++
        }
    }
}
function delitem(n){
	objudiv = document.getElementById('udiv');
	objudiv.removeChild(document.getElementById('url_'+n));

}
function insertitem(){
	setitem();
}
function choosemode(n){
	objmode = document.getElementsByName('mode');
	if(n == 1){
			objmode[0].checked = true;
			modedisabled('m2', true);
			modedisabled('m1', false);
	} else {
			objmode[1].checked = true;
			modedisabled('m1', true);
			modedisabled('m2', false);
	}
}
function modedisabled(n, b){
	objmid = document.getElementById(n);
	midinput = objmid.getElementsByTagName('input');
	for(i = 0; i < midinput.length; i++){
		if(midinput[i].id != 'mode')
			midinput[i].disabled = b;
	}
	if(b == true)
		objmid.style.color = '#aaa';
	else
		objmid.style.color = '';
}
function wildcard(n) {
	obj_wildcard = document.getElementById('wildcard');
	obj_listpagestart = document.getElementById('listpagestart');
	obj_listpageend = document.getElementById('listpageend');
	obj_listpagestart.value = obj_listpageend.value = '';
	document.getElementById('str_wc').style.display = 'none';
	if(n == 0){
		obj_wildcard.style.display = '';
		obj_listpagestart.maxLength = obj_listpageend.maxLength = 10;
	} else {
		obj_wildcard.style.display = 'none';
		obj_listpagestart.maxLength = obj_listpageend.maxLength = 1;
	}
}
function extractmessages() {
	if(bool_ext) {
		robotid = document.getElementById('robotid').value;
		getdata('item[]', siteUrl + '/midycp.php?action=extractmessages', robotid);
	} else {
		return false;
	}
}
function extractpause(obj) {
	bool_ext = !bool_ext;
	if(bool_ext) extractmessages();
	obj.value = bool_ext ? '暂停采集' : '继续采集';
}

function str_pad_left(input, pad_length){
	var pad_length = parseInt(pad_length);
	
	if(pad_length > input.length){
			input = (Math.pow(10, pad_length) + parseInt(input)) + '';
			input = input.substring(1, input);
	}
	return input;
}

function inputcheck(obj){
	var obj_listurl_auto = document.getElementById('listurl_auto');
	var obj_listpagestart = document.getElementById('listpagestart');
	var obj_listpageend = document.getElementById('listpageend');
	var obj_str_wc = document.getElementById('str_wc');
	var obj_len = document.getElementById('wildcardlen');
	var str_listpagestart = obj_listpagestart.value;
	var str_listpageend = obj_listpageend.value;
	var str_wc = '';
	var checkid = document.getElementById('robotid').value;
	if(checkid == ''|| !checkid || checkid == 0 || checkid == '0'){
		//添加入口源链接重复检测
		hostsimilaritycheck(obj_listurl_auto.value);
	}


	if(obj.id != 'listurl_auto'){
		str_replace = /\D+/g; 
		obj.value = obj.value.replace(str_replace, '');
	}
	if(obj_listurl_auto.value.search(/\[page\]/g) >= 0 && obj_listpagestart.value != '' && obj_listpageend.value != ''){
		str_listpagestart = str_pad_left(obj_listpagestart.value, obj_len.value);
		str_listpageend = str_pad_left(obj_listpageend.value, obj_len.value);
		str_wc = obj_listurl_auto.value.replace(/\[page\]/, str_listpagestart) + '<br />......<br />' + obj_listurl_auto.value.replace(/\[page\]/, str_listpageend);
		obj_str_wc.innerHTML = str_wc;
		obj_str_wc.style.display = '';
	} else {
		obj_str_wc.style.display = 'none';
	}

}

function initxmlhttp() {
	var xmlhttp;
	try {
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp=false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		try {
			xmlhttp = new XMLHttpRequest();
		} catch (e) {
			xmlhttp=false;
		}
	}
	if (!xmlhttp && window.createRequest) {
		try {
			xmlhttp = window.createRequest();
		} catch (e) {
			xmlhttp=false;
		}
	}
	return xmlhttp;
}

function getdata(formbody, posturl, robotid) {
	var xmlhttp = initxmlhttp();
	
	robotid = robotid == null ? 0 : parseInt(robotid);
	var poststr = '';
	var bool_up = 0;
	var obj_input = document.getElementsByName(formbody);
	var obj_ext_id = obj_ext_tr = obj_ext_href = obj_ext_return = obj_ext_a = '';
	var obj_extps = document.getElementById('extps');
	var obj_message = document.getElementById('message');
	
	for(i = 0; i < obj_input.length && i < 5; i++) {
		obj_ext_return = document.getElementById('ext_return_' + obj_input[i].value);
		obj_ext_tr = document.getElementById('ext_tr_' + obj_input[i].value);		
		
		if(obj_input[i].disabled == false) {
			if(obj_input[i].checked == true) {
				if(bool_up == 0) {
					obj_ext_href = document.getElementById('ext_href_' + obj_input[i].value);
					obj_ext_id = obj_input[i].value;
					obj_ext_a = document.getElementById('ext_a_' + obj_input[i].value);

					obj_ext_return.innerHTML = '采集进行中...';
					obj_extps.style.display = '';
					obj_message.innerHTML = '正在采集：' + obj_ext_href.value + '...';
					poststr += '&' + formbody + '=' + obj_ext_href.value;
					
					obj_ext_tr.removeChild(obj_input[i]);
					i--;
					bool_up++;
				} else {
					obj_ext_return.innerHTML = '<font style="color: #777">等待中...</font>';
				}
			} else {
				obj_ext_return.innerHTML = '<font style="color: #ccc">不采集</font>';
				obj_ext_tr.removeChild(obj_input[i]);
				i--;
			}
		}
	}

	if(poststr == '') {
		obj_extps.style.display = 'none';
		if(robotid != 0) {
			obj_message.innerHTML = '<a href="' + siteUrl + '/midycp.php?action=robotmessages&robotid='+robotid+'">采集完毕，点击查看采集结果</a>';
		} else {
			obj_message.innerHTML = '采集器参数错误，无法查看采集结果。';
		}
		return false;
	}
	
	poststr += '&robotid=' + robotid;
	xmlhttp.open('POST', posturl, true);
	xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	xmlhttp.send(poststr);
	
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState==4) {
			obj_ext_return = document.getElementById('ext_return_' + obj_ext_id);
			
			if(document.getElementById('follow').checked == true) {
				window.location.href = '#' + obj_ext_a.name;
			}
			if(xmlhttp.status==200){
				var re = xmlhttp.responseText;
				re = re.split('!|!');
				obj_ext_return.innerHTML = '<font style="color: #0F0">采集成功</font> <a href="' + siteUrl + '/midycp.php?action=robotmessages&op=viewmessage&itemid='+re['1']+'" target="_blank">查看</a>';
			} else {
				obj_ext_return.innerHTML = '<font style="color: #F00">采集失败</font>';
			}
			extractmessages();
		}
	}
}