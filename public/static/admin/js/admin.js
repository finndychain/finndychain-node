//推送任务关联源js
function cronaddupdatefield(inputid, cronid) { 
	var fieldstr = trim(document.getElementById(inputid).value);//trim
	
	var x = new Ajax('XML', 'ajaxwaitid'); 
	x.get(siteUrl + '/batch.common.php?action=cronaddupdatefield&cronid=' + cronid + '&fieldstr=' + fieldstr, function(s) {
		if (s == 'RET_INVALID') {
			Msg('输入的字段或别名无效');
		} else if(s == 'RET_NOLOGIN') {
			Msg('权限不足');
		} else if(s == 'RET_NEEDINIT') {
			Msg('请先添加关联源进行初始化');
		} else {
			var newfield = '<button type="button" id="'+fieldstr+'" value="'+fieldstr+'" onclick="crondeletefield(this,\''+fieldstr+'\',\''+cronid+'\')"> '+s+' <i class="ace-icon fa fa-trash-o bigger-110 orange"></i> </button>';
			document.getElementById('fieldtext').innerHTML += newfield;
		}
	});
	document.getElementById(inputid).value = '';
}

function crondeletefield(thebtn, fieldstr, cronid) {
	if(confirm('确定删除?')) {
		var x = new Ajax('XML', 'ajaxwaitid');
		x.get(siteUrl + '/batch.common.php?action=crondeletefield&cronid=' + cronid + '&fieldstr=' + fieldstr, function(s) {
			if (s == 'RET_OK') {
				thebtn.parentNode.removeChild(thebtn);
			} else if(s == 'RET_FAIL') {
				Msg('删除失败');
			} else if(s == 'RET_INVALID') {
				Msg('输入的字段或别名无效');
			} else if(s == 'RET_NOLOGIN') {
				Msg('权限不足');
			} else {
				Msg('删除异常');
			}
		});

	}
}

// 检测数据源入口链接是否重复
function hostsimilaritycheck(urls){
	$.post(siteUrl + '/batch.common.php?action=hostsimilaritycheck',{"urls":urls},function(s) {
		if (s != 'RET_OK') {
			Msg('「'+s+'」已经存在，请重新选择网址采集！');
		}
	});
}

//公开显示联系方式
function contactShow(id,value) {
		var x = new Ajax('XML', 'ajaxwaitid');
		x.get(siteUrl + '/batch.common.php?action=contactshow&type=' + id + '&value=' + value, function(s) {
			if (s == 'RET_OK') {
				document.getElementById(id).value = 0
			}else {
				Msg('网络异常，稍后再试！');
			}
		});
}
//推送任务：关联源js
function cronaddrobot(inputid, cronid) { 
	var robotid = trim(document.getElementById(inputid).value);//trim
	
	var x = new Ajax('XML', 'ajaxwaitid'); 
	x.get(siteUrl + '/batch.common.php?action=cronaddrobot&cronid=' + cronid + '&rid=' + robotid, function(s) {
		if (s == '') {
			Msg('输入的源无效');
		} else if(s == 'RET_NOLOGIN') {
			Msg('权限不足');
		} else if(s == 'RET_REPEAT') {
			Msg('重复添加或已绑定其它任务');
		} else if(s == 'RET_INIT') {
			//后续再完善
			//newrobot = '<input type="button" name="robotidbtn[]" id="rid'+robotid+'" value="'+s+'" onclick="crondeleterobot(this,'+robotid+','+cronid+')"> ';
			//document.getElementById('robottext').innerHTML += newrobot;
			////首次添加关联源时，同步初始化关联字段
			//newfield = '<input type="button" name="fieldidbtn[]" id="extfield1" value="extfield1->tagname1" onclick="crondeletefield(this,'+robotid+','+cronid+')"> ';
			//document.getElementById('fieldtext').innerHTML += newfield;

		} else {
			var newrobot = '<button type="button" class="btn btn-white btn-success" id="rid'+robotid+'" onclick="javascript:crondeleterobot(this,\''+robotid+'\',\''+cronid+'\');">'+s+' <i class="ace-icon fa fa-trash-o bigger-110 orange"></i> </button> ';
			document.getElementById('robottext').innerHTML += newrobot;
		}
	});
	document.getElementById(inputid).value = '';
}

function crondeleterobot(thebtn, robotid, cronid) {
	if(confirm('确定删除？')) { 
		var x = new Ajax('XML', 'ajaxwaitid'); 
		x.get(siteUrl + '/batch.common.php?action=crondeleterobot&cronid=' + cronid + '&rid=' + robotid, function(s) {
			if (s == 'RET_OK') {
				thebtn.parentNode.removeChild(thebtn);
			} else {
				Msg('删除失败');
			}
		});
		
	}
}

//专题页：源关联专题js
function addrobot(inputid, htmlid) { 
	var robotid = trim(document.getElementById(inputid).value);//trim
	
	var x = new Ajax('XML', 'ajaxwaitid'); 
	x.get(siteUrl + '/batch.common.php?action=getrobotname&rid=' + robotid, function(s) {
		if (s == '') {
			Msg('输入的源无效');
		} else {
			var newrobot = '<button type="button" class="btn btn-white btn-success" id="rid'+robotid+'" onclick="javascript:deleterobot(this);" >'+s+' <i class="ace-icon fa fa-trash-o bigger-110 orange"></i> </button> <input type="hidden" name="robotid[]" id="postrid'+robotid+'" value="'+robotid+'">';
			document.getElementById(htmlid).innerHTML += newrobot;
		}
	});
	document.getElementById(inputid).value = '';
}

function deleterobot(thebtn) {
	if(confirm('确定删除？')) { 
		var node = document.getElementById('post'+thebtn.id);
		node.parentNode.removeChild(node);
		thebtn.parentNode.removeChild(thebtn);
	}
}
 
//TAG页
function addtag(inputid, htmlid) {
	var tagtext = document.getElementById(htmlid).innerHTML;
	var tag = document.getElementById(inputid).value;
	
	if(checktag(tag)) {
		newtag = '<input type="button" name="tagnamebtn[]" value="'+tag+'" onclick="deletetag(this)"><input type="hidden" name="tagname[]" id="tagnameid'+tag+'" value="'+tag+'">';
		document.getElementById(htmlid).innerHTML += newtag;
	}
	document.getElementById(inputid).value = '';
}

function addtagname(tagname, htmlid) {
	var tagtext = document.getElementById(htmlid).innerHTML;
	var tag = tagname;
	if(checktag(tag)) {
		newtag = '<input type="button" name="tagnamebtn[]" value="'+tag+'" onclick="deletetag(this)"><input type="hidden" name="tagname[]" id="tagnameid'+tag+'" value="'+tag+'">';
		document.getElementById(htmlid).innerHTML += newtag;
	}
}


function deletetag(thebtn) {
	if(confirm('确定删除TAG: '+thebtn.value+'?')) {
		document.getElementById('tagnameid'+thebtn.value).disabled = true;
		thebtn.disabled = true;
		thebtn.style.display = "none";
	}
}

function checktag(Sting) {

	if(Sting.length < 2 || Sting.length > 10) {
		alert('TAG长度不符合要求');
		return false;
	}
	//常用的符号
	var compStr = "§№☆★○●◎◇◆□■△▲※→←↑↓〓＃＆＠＼︿＿￣〖〗【】（）〔〕｛｝．’‘”“》《〉〈〕〔‘’“”々～‖∶”’‘｜¨ˇˉ·－…！？：；，、。  ~!@#$%^&*()+={}|[]\\:;\"'<,>?/";
    var length2 = Sting.length;
	
	for (var iIndex=0;iIndex<length2;iIndex++) {
		var temp1 = compStr.indexOf(Sting.charAt(iIndex));
		if(temp1>=0){
			alert('TAG名中包含非法字符');
			return false;
		}
	}
	return true;
}

function ShowHide(id) {
	if ( itm = document.getElementById(id) ) {
		if (itm.style.display == 'none'){
			itm.style.display = '';
		} else {
			itm.style.display = 'none';
		}
	}
}

function changevalue(id, value) {
	var obj = document.getElementById(id);
	obj.value = value;
}

function validate(theform) {
	//检测是否同意条款
	if(document.getElementById('accede').checked) {

	} else {
		Msg("您必须同意服务条款后才能提交");
		return false;
	}

    var btn = document.getElementById("thevaluesubmit");
    //if(btn) btn.disabled = true; //暂关闭，避免创建失败后返回时无法保存，发需求时单独处理

    //标题
    var subject = document.getElementById("subject");
    if(subject) {
        if (subject.value.length < 2 || subject.value.length > 80) {

            subject.focus();
            Msg("标题长度不符合要求(2~80)");
            if(btn) btn.disabled = false;
            return false;
        }
    }

    //文件后缀名
    var fileext = document.getElementById("fileext");
    if(fileext) {
        if (fileext.value.length < 1 || fileext.value.length > 15) {

            fileext.focus();
            Msg("文件后缀名长度不符合要求");
            if(btn) btn.disabled = false;
            return false;
        }
    }

    //名称
    var name = document.getElementById("name");
    if(name) {
        if (name.value.length < 10 || name.value.length >50) {

            name.focus();
            Msg("数据源名称字数10个字以上并且50个字以下");
            if(btn) btn.disabled = false;
            return false;
        }
    }

	//预估数据量
	var estimatedsourcedatanum = document.getElementById("estimatedsourcedatanum");
	if(estimatedsourcedatanum) {
		if (estimatedsourcedatanum.value.length < 1 || estimatedsourcedatanum.value.length > 50) {
			estimatedsourcedatanum.focus();
			Msg("预估数据量不能为空");
			if(btn) btn.disabled = false;
			return false;
		}
	}

    //机器人描述
    var name = document.getElementById("description");
    if(name) {
        if (name.value.length < 3 || name.value.length > 65530) {

            name.focus();
            Msg("源描述长度不符合要求");
            if(btn) btn.disabled = false;
            return false;
        }
    }


    //数据分类
    var catid = document.getElementById("import");
    if(catid) {
        if (catid.value<1) {

            catid.focus();
            Msg("请选择一个数据分类");
            if(btn) btn.disabled = false;
            return false;
        }
    }


    //源网站
    var name = document.getElementById("sourcesitename");
    if(name) {
        if (name.value.length < 1 || name.value.length > 250) {

            name.focus();
            Msg("来源网站不能为空");
            if(btn) btn.disabled = false;
            return false;
        }
    }

    //源网址
    //var name = document.getElementById("sourcesiteurl");
    //if(name) {
    //    if (name.value.length < 1 || name.value.length > 250) {
    //        Msg("来源网址不符合要求");
    //        name.focus();
    //        if(btn) btn.disabled = false;
    //        return false;
    //    }
    //}

    //组名称
    var groupname = document.getElementById("groupname");
    if(groupname) {
        if (groupname.value.length < 1 || groupname.value.length > 50) {

            groupname.focus();
            Msg("组名称长度不符合要求");
            if(btn) btn.disabled = false;
            return false;
        }
    }

    //用户名称
    var username = document.getElementById("username");
    if(username) {
        if (username.value.length < 1 || username.value.length > 30) {

            username.focus();
            Msg("用户名称长度不符合要求");
            if(btn) btn.disabled = false;
            return false;
        }
    }

    //类型名称
    var typename = document.getElementById("typename");
    if(typename) {
        if (typename.value.length < 1 || typename.value.length > 30) {

            typename.focus();
            Msg("类型名称长度不符合要求");
            if(btn) btn.disabled = false;
            return false;
        }
    }

    //类型名称
    var field = document.getElementById("field");
    if(field) {
        if (field.value.length < 1 || field.value.length > 30) {

            field.focus();
            Msg("字段名称长度不符合要求");
            if(btn) btn.disabled = false;
            return false;
        }
    }

    //空间名称
    var spacename = document.getElementById("spacename");
    if(spacename) {
        if (spacename.value.length < 1 || spacename.value.length > 30) {

            spacename.focus();
            Msg("空间名称长度不符合要求");
            if(btn) btn.disabled = false;
            return false;
        }
    }

    //空间名称
    var importtext = document.getElementById("importtext");
    if(importtext) {
        if (importtext.value.length < 1 || importtext.value.length > 30) {

            importtext.focus();
            Msg("导入文本长度不符合要求");
            if(btn) btn.disabled = false;
            return false;
        }
    }

    //页面自定义
    if(typeof window.thevalidate == 'function') {
        if(thevalidate(theform)) {
            return true;
        } else {
            if(btn) btn.disabled = false;
            return false;
        }
    }

    return true;
}

function validatereq(theform) {
    var btn = document.getElementById("thevaluesubmit");
    //if(btn) btn.disabled = true; //已使用jq loading方式代替

    //需求名称
    var name = document.getElementById("reqname");
    if(name) {
        if (name.value.length < 5 || name.value.length > 50) {
            Msg("需求名称长度不符合要求");
            name.focus();
            if(btn) btn.disabled = false;
            return false;
        }
    }


    //需求描述
    var name = document.getElementById("reqdescription");
    if(name) {
        if (name.value.length < 5 || name.value.length > 65530) {

            name.focus();
            Msg("需求描述长度不符合要求5个字符以上");
            if(btn) btn.disabled = false;
            return false;
        }
    }


    //需求qq
    var reqqq = document.getElementById("qq");
    if(reqqq) {
        if (reqqq.value.length < 5 || reqqq.value.length > 11) {

            reqqq.focus();
            Msg("请输入有效QQ，便于需求沟通");
            if(btn) btn.disabled = false;
            return false;
        }
    }

    //需求赏金
    var name = document.getElementById("sourceprepayment");
    if(name) {
        if(parseInt(document.getElementById("membercredit").value) < parseInt(name.value)){

            name.focus();
            Msg("您的账户余额不足，请先充值");
            if(btn) btn.disabled = false;
            return false;
        }
    }

    //追加需求赏金
    var addname = document.getElementById("addmoney");
    if(addname) {
        if(parseInt(document.getElementById("membercredit").value) < parseInt(addname.value)){

            addname.focus();
            Msg("您的账户余额不足，请先充值");
            if(btn) btn.disabled = false;
            return false;
        }
    }


    return true;
}

function listsubmitconfirm(theform) {
	theform.listsubmit.disabled = true;
	if(confirm("确定要执行本操作吗？")) {
		theform.listsubmit.disabled = false;
		return true;
	} else {
		theform.listsubmit.disabled = false;
		return false;
	}
}

function getid(id) {
	return document.getElementById(id);
}

function uploadFile(mode) {
	var theform = document.getElementById("theform");
	var msg = document.getElementById("divshowuploadmsg");
	var msgok = document.getElementById("divshowuploadmsgok");
	var oldAction = theform.action;
	var oldonSubmit = theform.onSubmit;
	msgok.style.display = 'none';
	msg.style.display = '';
	msg.innerHTML = '<i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i> 正在上传中，请稍等...';
	theform.action = siteUrl + "/batch.upload.php?mode=" + mode;
	theform.onSubmit = "";
	theform.target = "phpframe";
	theform.submit();
	theform.action = oldAction;
	theform.onSubmit = oldonSubmit;
	theform.target = "";
	if(mode ==2) {
		delpic();
	}
	return false;
}

function attacheditsubmit(aid) {
	var theform = document.getElementById("theform");
	var oldAction = theform.action;
	var oldonSubmit = theform.onSubmit;
	theform.action = siteUrl + "/batch.upload.php?editaid=" + aid;
	theform.onSubmit = "";
	theform.target = "phpframe";
	theform.submit();
	theform.action = oldAction;
	theform.onSubmit = oldonSubmit;
	theform.target = "";
	return false;
}

/**
 * showmenu/hidemenu 会用hideshowtags取代
 */
function showmenu(id) {
	var thismenu=document.getElementById(id);
	thismenu.style.display="block";
}
function hidemenu(id) {
	var thismenu=document.getElementById(id);
	thismenu.style.display="none";
}
/**
 * 隐藏并显示上传DIV中的指定标签
 * @param string id: 待显示的标签ID
 */
function hideshowtags(upid,id) {
	//获取上传DIV对象
	var uploadobj = getbyid(upid).getElementsByTagName('div');
	for(var i=0; i< uploadobj.length; i++) {
		if(uploadobj[i].id.indexOf('upload') != -1) {
			uploadobj[i].style.display = "none";
		}
	}
	var showtagobj = getbyid(id);
	showtagobj.style.display = "block";
	
	var tags = getbyid(upid).getElementsByTagName('div')[0].getElementsByTagName('a');
	for(var i=0; i< tags.length; i++) {
		tags[i].className = "";
	}
	if (getbyid(id+'tab')) { getbyid(id+'tab').className = "current"; }
}
/***************批量上传调用开始***********************/
/**
 * 插入一个预览图片
 * @param object obj: 上传文本框对象
 */
function insertimg(obj) {
	var childnum = getbyid("batchdisplay").getElementsByTagName("input");	//获取上传控制个数
	var upallowmax = getbyid("uploadallowmax").value;
	//判断是否超过限制
	if(upallowmax < childnum.length ) {
		alert("一次允许最多上传"+ upallowmax +"个");
		return false;
	}
	//获取最高ID
	var id =getmaxid();
	//添加默认的DIV
	var pichtml = '';
	var src = obj.value;
	
	//判断文件类型
	var patn = /\.jpg$|\.jpeg$|\.gif$|\.png$|\.bmp$/i;
	var filetype = 'file';
	if(patn.test(src)){
		filetype = 'image';
		
	}
	
	var temp_title = src.substr(src.lastIndexOf('\\')+1);
	if(src.lastIndexOf('.') != -1){
		temp_title = temp_title.substr(0,(src.substr(src.lastIndexOf('\\')+1)).lastIndexOf('.'));
	}
	pichtml += '<div class="picspace" id="pic_space_' + id + '" onmouseover="mouseoverpic(' + id + ', 0)" onmouseout="mouseoutpic(' + id + ', 0)">';
	pichtml += '<a href="javascript:;" onclick="delpic('+id+');" style="float: right; margin: 10px 5px 0 0;">删除</a>';
	if (navigator.userAgent.toLowerCase().indexOf('ie') < 0 || filetype != 'image') {
		pichtml += '<img src="admin/images/upload_file.gif" alt="upload file" class="nopreview" align="absmiddle" />';
	} else {
		pichtml += '<img src="' + src + '" id="pic_item_id_' + id + '" align="absmiddle" />';
	}
	pichtml += '<span class="pictext" id="pic_text_' + id + '">';
	pichtml += '<label id="label_pic_' + id + '" title="'+getStrbylen(temp_title,40)+'"><span id="pic_' + id + '">'+getStrbylen(temp_title,16)+'</span><input  id="pic_input_' + id + '" type="text" name="picname[]" value="'+temp_title+'" style="display: none;" /></label>';
	pichtml += '</span><div style="display:none;"><input type="text" name="title[]" value="'+ temp_title +'" id="pic_title_' + id + '" /><input type="text" name="thumb[]" value="'+ (getbyid('uploadthumb2') == null?"":getbyid('uploadthumb2').value) +'"/></div>';
	pichtml += '</div>';

	// 把图片框加到pic_space_main中去
	var picmain = getbyid("batchpreview");
	picmain.innerHTML = picmain.innerHTML + pichtml;
	obj.style.display = 'none';	//隐藏当前的上传对象
	var newid = id+1;	//获取下一个最大的上传ID并创建新的上传控件
	addupload(newid);	//创建一个新的上传控制
}
/**
 * 添加一个新的上传对象控件
 * @param int newid: 新的上传控件ID后缀
 */
function addupload(newid) {
	//两种生成方式，解决浏览器之间的兼容性问题
	try{
		//IE模式下的创建方式,解决常规setAttribute设置属性带来的一些未知的错误
		var uploadHTML = document.createElement("<input type=\"file\" id=\"batch_" + newid + "\" name=\"batchfile[]\" onchange=\"insertimg(this)\" class=\"fileinput\">");
		getbyid("batchdisplay").appendChild(uploadHTML);
	}catch(e) {
		//非IE模式则须要用下列的常规setAttribute设置属性，否则生成的结果不能正常初始化
		var uploadobj = document.createElement("input");
		uploadobj.setAttribute("name", "batchfile[]");
		uploadobj.setAttribute("onchange", "insertimg(this)");
		uploadobj.setAttribute("type", "file");
		uploadobj.setAttribute("id", "batch_" + newid);
		uploadobj.setAttribute("class", "fileinput");
		getbyid("batchdisplay").appendChild(uploadobj);
	}
}
/**
 * 得到页面中可用的最大ID号
 * 写这个函数主要是因为不能通过图片的个数来计算可用的最大ID号.
 * 图片是可以取消的,如果取消,则通过图片个数算出的最大ID号会与已有图片的ID号重叠.
 */
function getmaxid() {
	var items = getbyid("batchdisplay").getElementsByTagName("input");
	var count = items.length;
	var id = 0;
	for (var i=0; i<count; i++) {
		if(items[i].id.substr(0, 6) == "batch_") {
			tmp_id = new Number(items[i].id.substr(6));
			if(id < tmp_id) {
				id = tmp_id;
			}
		}
	}
	if(id == 0) {
		return 1;
	}
	id = new Number(id);
	return id;
}
//下面两个js函数 从spacenews.htm中移来
function textCounter(obj, showid, maxlimit) {
	var len = strLen(obj.value);
	var showobj = $(showid);
	if(len < maxlimit) {
		if(showobj != null){
			showobj.innerHTML = maxlimit - len;
		}
	} else {
		obj.value = getStrbylen(obj.value, maxlimit);
		if(showobj != null){
			showobj.innerHTML = "0";
		}
	}
}
function strLen(str) {
	var charset = is_ie ? document.charset : document.characterSet;
	var len = 0;
	for(var i = 0; i < str.length; i++) {
		len += str.charCodeAt(i) < 0 || str.charCodeAt(i) > 255 ? (charset.toLowerCase() == "utf-8" ? 3 : 2) : 1;
	}
	return len;
}
/**
 * 截取指定字符串长度
 * @param string str: 要截取的字符串
 * @param int len: 要截取的长度
 * @return 
 */
function getStrbylen(str, len) {
	var num = 0;
	var strlen = 0;
	var newstr = "";
	var laststrlen = 1;
	var obj_value_arr = str.split("");
	for(var i = 0; i < obj_value_arr.length; i ++) {
		if(i < len && num + byteLength(obj_value_arr[i]) <= len) {
			num += byteLength(obj_value_arr[i]);
			strlen = i + 1;
		}
	}
	if(str.length > strlen) {
		if(byteLength(str.charAt(strlen-1)) == 1){
			laststrlen = 2;
		}
		newstr = str.substr(0, strlen-laststrlen) + '…';
	} else {
		newstr = str;
	}
	return newstr;
}
/**
 * 判断中英问混排时候的长度
 * @param string sStr: 混排的字符串
 */
function byteLength (sStr) {
	aMatch = sStr.match(/[^\x00-\x80]/g);
	return (sStr.length + (! aMatch ? 0 : aMatch.length));
}
/**
 * 鼠标移到预览图事件
 * @param int id: 操作对象ID反缀
 * @param int optype: 操作类型
 */
function mouseoverpic(id,optype) {
	if(optype == 1) {
		var delpicbutton = getbyid("del_pic_button" + id);
		if(delpicbutton.style.display != "inline") {
			delpicbutton.style.display="inline";
		}
	} else if(optype == 0) {
		getbyid("pic_" + id).style.display = "none";
		getbyid("pic_input_" + id).style.display = "inline";
	}
}
/**
 * 鼠标移出预览图事件
 * @param int id: 操作对象ID反缀
 * @param int optype: 操作类型
 */
function mouseoutpic(id, optype) {
	if(optype == 1) {
		var delpicbutton = getbyid("del_pic_button" + id);
		delpicbutton.style.display="none";
	} else if(optype == 0) {
		var picobj = getbyid("pic_" + id);
		var inputobj = getbyid("pic_input_" + id);
		var labelobj = getbyid("label_pic_" + id);
		picobj.style.display = "inline";
		inputobj.style.display = "none";
		//判断是否为空，如果为空则取默认的文件名称
		var re = /\s/ig;
   		var result = inputobj.value.replace(re, "");
		if(result == "") {
			inputobj.value = getbyid("pic_title_" + id).value;
		}
		picobj.innerText = inputobj.value;
		labelobj.title = inputobj.value;
	}
}
/**
 * 删除全部预览图或其中的某一张
 * @param int pid: 要删除的预览图ID后缀，如没有填写则删除全部的预览图
 */
function delpic(pid) {
	//判断是否有传删除的图片ID，如果没有则删除全部的图片
	if(typeof pid != "undefined") {
		getbyid("batchpreview").removeChild(getbyid("pic_space_"+pid));
		//删除相对应的上传控件
		getbyid("batchdisplay").removeChild(getbyid("batch_" + pid));
	} else {
		//获取全部图片对象
		var previewobj = getbyid("batchpreview");
		var allobj = previewobj.getElementsByTagName("div");
		for(var i = allobj.length - 1; 0<=i; i--) {
			if(allobj[i].id.indexOf("pic_space_") != -1) {
				previewobj.removeChild(allobj[i]);
			}
		}
		var bdisplay = getbyid("batchdisplay");
		var allupobj = bdisplay.getElementsByTagName("input");
		for(var i = allupobj.length - 1; 0<=i; i--) {
			bdisplay.removeChild(allupobj[i]);
		}
		addupload(1);
	}
	return false;
}
/***************批量上传调用结束***********************/
//显示工具条
function hidetoolbar() {
	window.parent.document.getElementById("toolbarframe").style.display="none";
}
function hidetoolbarOpera() {
	if (navigator.userAgent.toLowerCase().indexOf('opera') > -1) {
		window.parent.document.getElementById("toolbarframe").style.display="none";
	}
}

function showtoolbar() {
	document.getElementById("toolbarframe").style.display = "block";
}

//切换频道
function channelNav(Obj, channel) {
	var channelTabs = document.getElementById('topmenu').getElementsByTagName('a');
	for (i=0; i<channelTabs.length; i++) {
		channelTabs[i].className = '';
	}
	Obj.className = 'current';
	Obj.blur();
	
	var sideDoc = window.parent.leftframe.document;
	var sideChannels = sideDoc.getElementsByTagName('div')
	for (i=0; i<sideChannels.length; i++) {
		sideChannels[i].style.display = '';
	}
	var sideChannelLinks = sideDoc.getElementsByTagName('a')
	for (i=0; i<sideChannelLinks.length; i++) {
		sideChannelLinks[i].className = '';
	}
	var targetChannels = channel.split(',');
	sideDoc.getElementById(targetChannels[0]).getElementsByTagName('a')[0].className = 'current';
	for (i=0; i<targetChannels.length; i++) {
		sideDoc.getElementById(targetChannels[i]).style.display = 'block';
	}
}


//树形菜单
function treeView() {
	var list = document.getElementsByTagName('div');
	for ( i=0; i<list.length; i++ ) {
		list[i].getElementsByTagName('h3')[0].onclick = function() {
			if (this.parentNode.getElementsByTagName('ul')[0].style.display == '') {
				this.parentNode.getElementsByTagName('ul')[0].style.display = 'none';
			} else {
				this.parentNode.getElementsByTagName('ul')[0].style.display = '';
			}
		}
	}

	var list = document.getElementsByTagName('span');
	for ( i=0; i<list.length; i++ ) {
		list[i].getElementsByTagName('h4')[0].onclick = function() {
			if (this.parentNode.getElementsByTagName('ul')[0].style.display == '') {
				this.parentNode.getElementsByTagName('ul')[0].style.display = 'none';
			} else {
				this.parentNode.getElementsByTagName('ul')[0].style.display = '';
			}
		}
	}
	
	var linkitem = document.getElementsByTagName('a');
	for ( j=0; j<linkitem.length; j++ ) {
		linkitem[j].onclick = function() {
			for ( k=0; k<linkitem.length; k++ ) {
				linkitem[k].className = '';
			}
			this.className = 'current';
			this.blur();
		}
	}
}

//侧栏开关
function sideSwitch() {
	var mainFrame = window.parent.document.getElementById('mainframeset');
	var switcher = document.getElementById('sideswitch');
	if (mainFrame.cols == '0,*') {
		mainFrame.cols = '200,*';
		switcher.innerHTML = '关闭侧栏';
		switcher.className = 'opened';
	} else {
		mainFrame.cols = '0,*';
		switcher.innerHTML = '打开侧栏';
		switcher.className = 'closed';
	}
}

function robotReferUrl(mode) {
	var theform = document.getElementById("theform");
	var msg = document.getElementById("divshowrobotmsg");
	var msgok = document.getElementById("divshowrobotmsgok");
	var oldAction = theform.action;
	var oldonSubmit = theform.onSubmit;
	msgok.style.display = 'none';
	msg.style.display = '';
	msg.innerHTML = "读取中 ...";
	theform.action = siteUrl + "/batch.common.php?action=" + mode;
	theform.onSubmit = "";
	theform.target = "phpframe";
	theform.submit();
	theform.action = oldAction;
	theform.onSubmit = oldonSubmit;
	theform.target = "";

	return false;
}

function cpmenus(id) {

	for(i=0; i<7; i++) {
		if(document.getElementById('menus_'+i) != null) { 
			document.getElementById('menus_'+i).style.display = (i==id) ? '': 'none';
		}
	}
	
	idshow = 99;
	if (id == -1) { 
		document.getElementById('menus_'+idshow).style.display = '';
	} else {
		document.getElementById('menus_'+idshow).style.display = 'none';
	}
	
	for(i=-1; i<7; i++) {
		if(document.getElementById('li_'+i) != null) {
			document.getElementById('li_'+i).className = (document.getElementById('li_'+i) != null && i != id) ? '' : 'active';
		}
	}

}