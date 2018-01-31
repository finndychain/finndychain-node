var sRTE;
var FacePath = siteUrl + "/images/edit/face/";
var docStyle = "";
var oArea;
function word(sNodeBox, sHTML, sMode, sPageBreak){
	var oHead = new Object;
	var oBody = new Object;
	var sPageBreak = sPageBreak == null || sPageBreak <= 0 ? 0 : sPageBreak;
	oArea = document.createElement("textarea");
	var posArr = [
	{w: 3, x: -2},
	{w: 20, x: -5, t: "撤销", func: "Undo", id: "a1"},
	{w: 20, x: -25, t: "重做", func: "Redo", id: "a2"},
	{w: 2, x: 0, id: "a3"},
	{w: 20, x: -45, t: "剪切", func: "Cut", id: "b1"},
	{w: 20, x: -65, t: "复制", func: "Copy", id: "b2"},
	{w: 20, x: -85, t: "粘贴", func: "Paste", id: "b3"},
	{w: 2, x: 0, id: "b4"},
	{w: 20, x: -225, t: "粗体", func: "Bold", id: "c1"},
	{w: 20, x: -245, t: "斜体", func: "Italic", id: "c2"},
	{w: 20, x: -265, t: "下划线", func: "Underline", id: "c3"},
	{w: 2, x: 0, id: "c4"},
	{w: 20, x: -285, t: "左对齐", func: "justifyleft", id: "d1"},
	{w: 20, x: -305, t: "居中", func: "justifycenter", id: "d2"},
	{w: 20, x: -325, t: "右对齐", func: "justifyright", id: "d3"},
	{w: 20, x: -345, t: "两端对齐", func: "justifyfull", id: "d4"},
	{w: 2, x: 0, id: "d5"},
	{w: 20, x: -105, t: "插入链接", func: "link", id: "e2"},
	{w: 20, x: -879, t: "取消链接", func: "unlink", id: "e11"},
	{w: 20, x: -145, t: "添加图片", func: "img", id: "e1"},
	{w: 20, x: -759, t: "插入Flash", func: "flash", id: "e6"},
	{w: 20, x: -779, t: "插入视频", func: "video", id: "e7"},
	{w: 20, x: -819, t: "插入Real媒体", func: "real", id: "e9"},
	{w: 20, x: -125, t: "插入表格", func: "table", id: "e3"},
	{w: 20, x: -465, t: "插入UBB表情", func: "face", id: "e4"},
	{w: 20, x: -485, t: "插入文字框", func: "textarea", id: "e5"},
	{w: 20, x: -799, t: "设置TAG", func: "cclink", id: "e8"},
	{w: 20, x: -859, t: "下载远程图片", func: "downremotefile", id: "e10"},
	
	{w: 2, x: 0, id: "e4"},
	{w: 20, x: -839, t: "插入分页标志", func: "set_pagebreak", id: "f1"},
	{w: 20, x: -165, t: "横线", func: "inserthorizontalrule", id: "f2"},
	{w: 20, x: -185, t: "文本颜色", func: "forecolor", id: "f3"},
	{w: 20, x: -205, t: "背景色", func: "hilitecolor", id: "f4"},
	{w: 2, x: 0, id: "f4"},
	{w: 40, x: -505, t: "段落样式", func: "formatblock", id: "g1"},
	{w: 40, x: -545, t: "字体", func: "fontname", id: "g2"},
	{w: 60, x: -585, t: "字体大小", func: "fontsize", id: "g3"},
	{w: 2, x: 0, id: "g4"},
	{w: 20, x: -365, t: "编号列表", func: "insertorderedlist", id: "h1"},
	{w: 20, x: -385, t: "项目符号列表", func: "insertunorderedlist", id: "h2"},
	{w: 20, x: -405, t: "减少缩进", func: "outdent", id: "h3"},
	{w: 20, x: -425, t: "增加缩进", func: "indent", id: "h4"},
	{w: 2, x: 0, id: "h5"},
	{w: 20, x: -445, t: "功能", func: "editmenu"}
	];
	oHead["table"] = document.createElement("table");
	oHead["table"].cellSpacing = "0px";
	oHead["table"].cellPadding = "0px";
	oHead["table"].border = "0px";
	oHead["table"].id = sNodeBox + "Table";
	oHead["table"].className = "editerToolsBox";
	oHead["tbody"] = document.createElement("tbody");
	oHead["tr"] = document.createElement("tr");
	oHead["td"] = document.createElement("td");
	oHead["td"].className = "editerToolsBG";
	oHead["td"].id = "editerToolsBG";
	for(var i = 0; i < posArr.length; i++) {
		if(sPageBreak == 1 || (sPageBreak == 0 && posArr[i]["id"] != "f1" && posArr[i]["id"] != "e10") || (sPageBreak == 3 && posArr[i]["id"] != "e10") || (sPageBreak == 2 && posArr[i]["id"] != "f1")) {
			oHead["items"] = document.createElement("img");
			posArr[i]["id"] ? oHead["items"].id = posArr[i]["id"] : function(){};
			oHead["items"].src = siteUrl + "/images/edit/blank.gif";
			oHead["items"].style.margin = "1px";
			oHead["items"].className = "editerToolsIMG";
			oHead["items"].style.width = posArr[i]["w"] + "px";
			oHead["items"].style.backgroundPosition = posArr[i]["x"] + "px 0px";
			if(posArr[i]["func"] != null) {
				oHead["items"].title = posArr[i]["t"];
				oHead["items"]["func"] = posArr[i]["func"];
				oHead["items"].onmouseover = function () {
					var bp = this.style.backgroundPosition;
					this.style.backgroundPosition = bp.split(" ")[0] + " -20px";
				}
				oHead["items"].onmouseout = function () {
					var bp = this.style.backgroundPosition;
					this.style.backgroundPosition = bp.split(" ")[0] + " 0px";
				}
				oHead["items"].onclick = function () {
					wordChiCommand(this["func"]);
				}
			}
		}
		oHead["td"].appendChild(oHead["items"]);
	}
	oHead["tr"].appendChild(oHead["td"]);
	oHead["tbody"].appendChild(oHead["tr"]);
	oHead["table"].appendChild(oHead["tbody"]);
	document.getElementById(sNodeBox).appendChild(oHead["table"]);

	oBody["table"] = document.createElement("table");
	oBody["table"].cellSpacing = "0px";
	oBody["table"].cellPadding = "0px";
	oBody["table"].border = "0px";
	oBody["table"].className = "editerTextBox";
	oBody["table"].id = "editerTextBox";
	oBody["tbody"] = document.createElement("tbody");
	oBody["tr"] = document.createElement("tr");
	oBody["td"] = document.createElement("td");
	oBody["td"].className = "editerTextTopBG";
	oBody["td"].id = "editerTextTopBG";
	oBody["tr"].appendChild(oBody["td"]);
	
	oBody["tbody"].appendChild(oBody["tr"]);
	oBody["tr"] = document.createElement("tr");
	oBody["td"] = document.createElement("td");
	oBody["td"].className = "editerTextBG";
	oBody["td"].id = sNodeBox + "Iframes";
	oBody["tr"].appendChild(oBody["td"]);
	oBody["tbody"].appendChild(oBody["tr"]);

	oBody["tr"] = document.createElement("tr");
	oBody["td"] = document.createElement("td");
	oBody["td"].className = "editerTextBottomBG";
	oBody["input"] = document.createElement("input");
	oBody["input"].type = "checkbox";
	oBody["input"].id = "EditerCMD";
	
	function oInputFunc(){
		var oRTE = getFrameNode(sRTE);
		oRTE.focus();
		var sEditBox = document.getElementById(sNodeBox + "Table");
		var sEditMode = sEditBox.style.display == "none" ? true : false;
		if(sEditMode){
			sEditBox.style.display = "";
			docStyle = oRTE.document.getElementsByTagName("style");
			oRTE.document.body.style.fontFamily = 'Arial, Helvetica, sans-serif';
			oRTE.document.body.style.lineHeight = 'normal';
			if(! window.Event){
				var sOutText = escape(oRTE.document.body.innerText);
				sOutText = sOutText.replace("%3CP%3E%0D%0A%3CHR%3E", "%3CHR%3E");
				sOutText = sOutText.replace("%3CHR%3E%0D%0A%3C/P%3E", "%3CHR%3E");
				oRTE.document.body.innerHTML = unescape(sOutText);
			}else{
				var oMozText = oRTE.document.body.ownerDocument.createRange();
				oMozText.selectNodeContents(oRTE.document.body);
				oRTE.document.body.innerHTML = oMozText.toString();
			}
		}else{
			sEditBox.style.display = "none";
			var innerHTML = oRTE.document.body.innerHTML;
			docStyle = oRTE.document.getElementsByTagName("style");
			oRTE.document.body.style.fontFamily = 'Courier New, Courier, monospace';
			oRTE.document.body.style.lineHeight = '1.5em';
			if(!window.Event){
				oRTE.document.body.innerText = innerHTML;
			}else{
				var oMozText = oRTE.document.createTextNode(innerHTML);
				oRTE.document.body.innerHTML = "";
				oRTE.document.body.appendChild(oMozText);
			}
		}
	}
	function oFullFunc() {
		var fEditMode = document.getElementById("fullEditer").checked;
		var dochtml = document.getElementsByTagName('html')[0];
		if(fEditMode) {
			var dHeight = document.documentElement.clientHeight;
			var dwidth = document.documentElement.clientWidth;
			document.getElementById("fulledit").className = "fullmessage";
			document.body.className = "fullbody";
			dochtml.style.overflow='hidden';
			document.getElementById(sNodeBox + "Table").style.width = document.getElementById(sNodeBox + "Iframes").style.width = document.getElementById(sNodeBox + "_area").style.width = dwidth +"px";
			document.getElementById(sNodeBox + "Iframes").style.height = document.getElementById(sNodeBox + "_area").style.height = dHeight-55 +"px";
		} else {
			document.body.style.cssText = "";
			document.getElementById("fulledit").className = "";
			document.body.className = "";
			dochtml.style.overflow='';
			document.getElementById(sNodeBox + "Iframes").style.height = document.getElementById(sNodeBox + "_area").style.height = "";
			document.getElementById(sNodeBox + "Table").style.width = document.getElementById(sNodeBox + "Iframes").style.width = document.getElementById(sNodeBox + "_area").style.width = document.getElementById("editerToolsBG").style.width = "";
		}
	}

	function CleanCode() {
		var oRTE = getFrameNode(sRTE);
		oRTE.focus();
		if (! window.Event){
			var body = oRTE.document.body;
			for (var index = 0; index < body.all.length; index++) {
				tag = body.all[index];
				tag.removeAttribute("className","",0);
				tag.removeAttribute("style","",0);
			}
			var html = oRTE.document.body.innerHTML;
			html = html.replace(/\<p>/gi,"[$p]");
			html = html.replace(/\<\/p>/gi,"[$\/p]");
			html = html.replace(/\<br[^>]*>/gi,"[$br]");
			html = html.replace(/\<[^>]*>/g,"");
			html = html.replace(/\[\$p\]/gi,"<p>");
			html = html.replace(/\[\$\/p\]/gi,"<\/p>");
			html = html.replace(/\[\$br\]/gi,"<br>");
			oRTE.document.body.innerHTML = html;
		}else{
			var oMozText = oRTE.document.body.ownerDocument.createRange();
			oMozText.selectNodeContents(oRTE.document.body);
			oRTE.document.body.innerHTML = oMozText.toString();

		}
	}

	this.renewcontent = function() {
		if(window.confirm('您确定要恢复上次保存?')) {
			try {
				oArea.load('Finndy');
				var oXMLDoc = oArea.XMLDocument;
				oArea.value = oXMLDoc.selectSingleNode("/ROOTSTUB/message").nodeTypedValue;
				writeDesignMode(sRTE, oArea.value, sMode);
				if(getbyid('tagname') != null ) getbyid('tagname').value = oXMLDoc.selectSingleNode("/ROOTSTUB/tagname").nodeTypedValue;
				if(getbyid('subject') != null ) getbyid('subject').value = oXMLDoc.selectSingleNode("/ROOTSTUB/subject").nodeTypedValue;
			} catch (e) {
				if(window.sessionStorage) {
					try {
						writeDesignMode(sRTE, sessionStorage.getItem('message'), sMode);
						if(getbyid('subject') !=null ) getbyid('subject').value = sessionStorage.getItem('subject');
						if(getbyid('tagname') !=null ) getbyid('tagname').value = sessionStorage.getItem('tagname');
					} catch(e) {}
				}
			}
		}
	}
	window.onresize = oFullFunc;
	oBody["input"].onclick = oInputFunc;
	oBody["label"] = document.createElement("label");
	oBody["label"].style.fontSize = '12px';
	oBody["label"].htmlFor = "EditerCMD"
	oBody["label"].innerHTML = "&nbsp;源代码&nbsp;&nbsp;";
	
	oBody["fullEditer"] = document.createElement("input");
	oBody["fullEditer"].type = "checkbox";
	oBody["fullEditer"].id = "fullEditer";
	oBody["fullEditer"].onclick = oFullFunc;

	oBody["fulllabel"] = document.createElement("label");
	oBody["fulllabel"].style.fontSize = '12px';
	oBody["fulllabel"].htmlFor = "fullEditer"
	oBody["fulllabel"].innerHTML = "&nbsp;全屏&nbsp;&nbsp;";

	oBody["cleanCode"] = document.createElement("label");
	oBody["cleanCode"].style.fontSize = '12px';
	oBody["cleanCode"].onclick = CleanCode;
	oBody["cleanCode"].innerHTML = "清除格式&nbsp;|&nbsp;";

	oBody["renew"] = document.createElement("label");
	oBody["renew"].style.fontSize = '12px';
	oBody["renew"].onclick = this.renewcontent;
	oBody["renew"].innerHTML = "恢复内容&nbsp;|&nbsp;";

	oBody["explain"] = document.createElement("label");
	oBody["explain"].style.fontSize = '12px';
	oBody["explain"].innerHTML = "(换行用 Shift+Enter 键)";
	
	oBody["td"].appendChild(oBody["input"]);
	oBody["td"].appendChild(oBody["label"]);
	oBody["td"].appendChild(oBody["fullEditer"]);
	oBody["td"].appendChild(oBody["fulllabel"]);
	oBody["td"].appendChild(oBody["cleanCode"]);
	oBody["td"].appendChild(oBody["renew"]);
	oBody["td"].appendChild(oBody["explain"]);
	
	oBody["tr"].appendChild(oBody["td"]);
	oBody["tbody"].appendChild(oBody["tr"]);

	oBody["table"].appendChild(oBody["tbody"]);
	
	document.getElementById(sNodeBox).appendChild(oBody["table"]);
	sRTE = sNodeBox + "_area";
	document.getElementById(sNodeBox + "Iframes").innerHTML = "<iframe frameborder='0' scrolling='yes' id=" + sRTE + " class='editerTextArea'></iframe>";

	oArea.name = sNodeBox;
	oArea.id = sNodeBox + "_textarea";
	oArea.style.display = "none";
	oArea.value = sHTML;
	oArea.className = 'userData';
	document.getElementById(sNodeBox).appendChild(oArea);
	this.copycontent = function() {

		var sRTEObj = getFrameNode(sRTE).document.body;
		var sHTML = sRTEObj.innerHTML;
		var textareavalue = sHTML;
		if (sHTML.length > 0) {
			try {
				var xmlDoc = oArea.XMLDocument;
				var subNode = xmlDoc.createNode(1, 'subject', '');
				var subValueNode = xmlDoc.createNode(4, "subject", "");
				var msgNode = xmlDoc.createNode(1, 'message', '');
				var msgValueNode = xmlDoc.createNode(4, "message", "");
				var tagNode = xmlDoc.createNode(1, 'tagname', '');
				var tagValueNode = xmlDoc.createNode(4, "tagname", "");
				
				delmsg = xmlDoc.selectNodes("//subject");
   				delmsg.removeAll();
   				delmsg = xmlDoc.selectNodes("//message");
   				delmsg.removeAll();
   				delmsg = xmlDoc.selectNodes("//tagname");
   				delmsg.removeAll();
				
				msgValueNode.nodeValue = sHTML;
				subValueNode.nodeValue = getbyid('subject').value;
				tagValueNode.nodeValue = getbyid('tagname').value;
				
				subNode.appendChild(subValueNode);
				msgNode.appendChild(msgValueNode);
				tagNode.appendChild(tagValueNode);

				root = xmlDoc.documentElement;
				
				root.appendChild(msgNode);
				root.appendChild(subNode);
				root.appendChild(tagNode);
				oArea.save('Finndy');
			} catch (e) {
				if(window.sessionStorage) {
					try {
						sessionStorage.setItem('message', sHTML);
						if(getbyid('subject') !=null ) sessionStorage.setItem('subject', getbyid('subject').value);
						if(getbyid('tagname') !=null ) sessionStorage.setItem('tagname', getbyid('tagname').value);
					} catch(e) {}
				}
				
			}
		}
	}
	this.save = function(){
		this.copycontent;
		if(oBody["input"].checked == true) {
			oInputFunc();
			oBody["input"].checked = false;
		}
		var oRTE = getFrameNode(sRTE);
		//oRTE.focus();
		var sEditBox = document.getElementById(sNodeBox + "Table");
		var sEditMode = sEditBox.style.display == "none" ? true : false;
		if(sEditMode){
			if(!indow.Event){
				oArea.value = oRTE.document.body.innerText;
			}else{
				var oMozText = oRTE.document.body.ownerDocument.createRange();
				oMozText.selectNodeContents(oRTE.document.body);
				oArea.value = oMozText.toString();
			}
		}else{
			oArea.value = oRTE.document.body.innerHTML;
		}
	}
	
	window.onbeforeunload = this.copycontent;
	function writeDesignMode(sNodeBox, sHTML, sMode) {
		enableDesignMode(sNodeBox, "on");
		var sFix = window.Event ? "" : "";
		if(sMode == 1) {
			var frameHTML = sHTML + "\n\
			" + sFix;
		} else {
			var frameHTML = "\
			<html>\n\
			<head>\n\
			<meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE7\" \/>\n\
			<style>\n\
			body {\n\
				background: #ffffff;\n\
				margin:0px;\n\
				padding:5px;\n\
				font-size:12px;\n\
				overflow:auto;\n\
				scrollbar-face-color:#fff;\n\
				scrollbar-highlight-color:#c1c1bb;\n\
				scrollbar-shadow-color:#c1c1bb;\n\
				scrollbar-3dlight-color:#ebebe4;\n\
				scrollbar-arrow-color:#cacab7;\n\
				scrollbar-track-color:#f4f4f0;\n\
				scrollbar-darkshadow-color:#ebebe4;\n\
				word-wrap: break-word;\n\
				font-family: Arial, Helvetica, sans-serif;\n\
			}\n\
			</style>\n\
			</head>\n\
			<body>\n\
			" + sHTML + "\n\
			" + sFix + "\n\
			</body>\n\
			</html>";
		}

		var oRTE = getFrameNode(sNodeBox).document;
		oRTE.open();
		oRTE.write(frameHTML);
		oRTE.close();
		oRTE.body.focus();
	}
	writeDesignMode(sRTE, sHTML, sMode);
	saveWordStatus();
}

function getFrameNode(sNode){
	return document.frames ? document.frames[sNode] : document.getElementById(sNode).contentWindow;
}
	
function enableDesignMode(sNodeBox, sMode){
	document.frames ? document.frames[sNodeBox].document.designMode = sMode : document.getElementById(sNodeBox).contentDocument.designMode = sMode;
}

function wordChiCommand(_sCmd) {
	var oRTE = document.frames ? document.frames[sRTE] : document.getElementById(sRTE).contentWindow;
	function oPenWin(_sTitle, _sWidth, _sHeight, _sUrl, _bDialog, _open){
		xposition=0; yposition=0;
		if ((parseInt(navigator.appVersion) >= 4 )) {
			xposition = (screen.width - _sWidth) / 2;
			yposition = (screen.height - _sHeight) / 2;
		}
		if(_open) {
			window.open(_sUrl,"win","menubar=no,location=no,resizable=no,scrollbars=no,status=no,left="+xposition+",top="+yposition+",width="+_sWidth+",height="+_sHeight);
		} else {
			if(window.Event) {
				window.open(_sUrl,"win","menubar=no,location=no,resizable=no,scrollbars=no,status=no,left="+xposition+",top="+yposition+",innerWidth="+_sWidth+",innerHeight="+_sHeight);
			} else {
				if(_bDialog == true) {
					showModelessDialog(_sUrl, window, "dialogHeight:"+(_sHeight+20)+"px;dialogWidth:"+_sWidth+"px;status:no;help:no;resizable:yes;status:no;tustatus:no;");
				} else {
					showModalDialog(_sUrl, window, "dialogHeight:"+(_sHeight+20)+"px;dialogWidth:"+_sWidth+"px;status:no;help:no;resizable:yes;status:no;tustatus:no;");
				}
			}
		}
	}
	switch(_sCmd){
		
		case "":
			break;
		case "face":
			oPenWin("插入表情图标", 280, 170, siteUrl + "/images/edit/Face.php?siteurl=" + escape(siteUrl));
			break;
		case "link":
			oPenWin("请输入网页地址", 300, 140, siteUrl + "/images/edit/InsertLink.html", true);
			break;
		case "table":
			oPenWin("请选择表格属性", 300, 240, siteUrl + "/images/edit/InsertTable.html");
			break;
		case "img":
			oPenWin("请输入图片地址", 380, 400, siteUrl + "/batch.insertimage.php", 1, 1);
			break;
		case "flash":
			oPenWin("请输入图片地址", 300, 160, siteUrl + "/images/edit/InsertFlash.html");
			break;
		case "video":
			oPenWin("请输入视频地址", 300, 160, siteUrl + "/images/edit/InsertVideo.html");
			break;
		case "real":
			oPenWin("请输入视频地址", 300, 160, siteUrl + "/images/edit/InsertReal.html");
			break;
		case "forecolor":
			oPenWin("请选择文本颜色", 140, 162, siteUrl + "/images/edit/ForeColor.html", true);
			break;
		case "hilitecolor":
			oPenWin("请选择背景颜色", 140, 162, siteUrl + "/images/edit/BackColor.html", true);
			break;
		case "formatblock":
			oPenWin("请选择段落风格", 160, 242, siteUrl + "/images/edit/FormatBlock.html", true);
			break;
		case "fontsize":
			oPenWin("请选择字体大小", 160, 186, siteUrl + "/images/edit/FontSize.html", true);
			break;
		case "fontname":
			oPenWin("请选择字体样式", 160, 280, siteUrl + "/images/edit/FontName.html", true);
			break;
		case "editmenu":
			oPenWin("开启功能列表", 156, 250, siteUrl + "/images/edit/menu.html");
			break;
		case "textarea":
			insetQUER();
			break;
		case "unlink":
			clearLink();
			break;
		case "cclink":
			insetCCLink();
			break;
		case "CleanCode":
			CleanCode();
			break;
		case "downremotefile":
			downremotefile();
			break;
		case "set_pagebreak":
			set_pagebreak();
			break;
		default:
			oRTE.focus();
			oRTE.document.execCommand(_sCmd, false, null);
			oRTE.focus();
			break;
	}
}

function clearLink() {
	var oRTE = getFrameNode(sRTE);
	oRTE.document.execCommand('Unlink', false, true);
}

function insetQUER() {
	var _sVal;
	var rng = {};
	var oRTE = getFrameNode(sRTE);
	if (document.all) {
		var selection = oRTE.document.selection; 
		if (selection != null) {
			rng = selection.createRange();
		}
	} else {
		var selection = oRTE.getSelection();
		
		rng = selection.getRangeAt(selection.rangeCount - 1).cloneRange();
		rng.text = rng.toString();
	}
	_sVal = rng.text == "" ? "请在文本框输入文字" : rng.text;
	var html = "<table style='border:1px solid #999;width:80%;font-size:12px;' align='center'><tr><td>"+ _sVal +"</td></tr></table>";
	
	insertHtml(html);
}
function insetCCLink() {
	var _sVal;
	var rng = {};
	var oRTE = getFrameNode(sRTE);
	if (document.all) {
		var selection = oRTE.document.selection; 
		if (selection != null) {
			rng = selection.createRange();
		}
	} else {
		var selection = oRTE.getSelection();
		
		rng = selection.getRangeAt(selection.rangeCount - 1).cloneRange();
		rng.text = rng.toString();
	}
	if(rng.text == "") {
		alert("请选中要设置为TAG跟踪的文字");
		var html = "";
	} else if(rng.text.length > 10) {
		alert("要设置为TAG跟踪的文字长度不能超过10个字节");
		var html = rng.text;
	} else {
		_sVal = rng.text;
		var html = "<a href=\"" + siteUrl + "/tag.php?k="+ (_sVal)+"\" target='_blank'>"+ _sVal +"</a>";
	}
	insertHtml(html);
}
function getStatus(){
	var sFunc = getCookie("supeSpaceWordFunc") ? getCookie("supeSpaceWordFunc").split("_") : [];
	for(var i = 0; i < sFunc.length; i++){
		hideList(sFunc[i]);
	}
}
function getCookie(name){
	var result = null; 
	var myCookie = document.cookie + ";"; 
	var searchName = name + "="; 
	var startOfCookie = myCookie.indexOf(searchName); 
	var endOfCookie; 
	if (startOfCookie != -1)
	{ 
		startOfCookie += searchName.length; 
		endOfCookie = myCookie.indexOf(";", startOfCookie); 
		result = unescape(myCookie.substring(startOfCookie, endOfCookie)); 
	} 
	return result; 
}
function setCookie(name, value, expires, path, domain, secure){
	var expDays = expires * 24 * 60 * 60 * 1000;
	var expDate = new Date(); 
	expDate.setTime(expDate.getTime() + expDays); 
	var expString = ((expires == null) ? "" : (";expires=" + expDate.toGMTString())) 
	var pathString = ((path == null) ? "" : (";path=" + path)) 
	var domainString = ((domain == null) ? "" : (";domain=" + domain)) 
	var secureString = ((secure == true) ? ";secure" : "" )
	document.cookie = name + "=" + escape(value) + expString + pathString + domainString + secureString;
}
function hide(_sId){
	document.getElementById(_sId).style.display = document.getElementById(_sId).style.display == "none" ? "" : "none" ;
}
function swap(s,a,b,c){
	document.getElementById(s)[a]=document.getElementById(s)[a]==b?c:b;
}
function saveWordStatus(_sFunc){
	var i = 1;
	var sFuncId = [];

	if(!_sFunc){
		_sFunc = getCookie("supeSpaceWordFunc") != null ? getCookie("supeSpaceWordFunc").split("_") : [];
	}

	for(var j = 0;j < _sFunc.length; j++){
		i = 1;
		sFuncId = _sFunc[j].split("@");
		while(document.getElementById(sFuncId[0] + i)){
			document.getElementById(sFuncId[0] + i).style.display = sFuncId[1] == "true" ? "none" : "";
			i++;
		}
		sFuncId = [];
	}

	setCookie("supeSpaceWordFunc", _sFunc.join("_"), 7);
}

function publish_article(){
	et.save();
}
function getEditorContents() {
	if(typeof oArea != "undefined") {
		publish_article();
		return oArea.value;
	} else {
		return '';
	}
}
function set_pagebreak(){
	var html = '<p>###NextPage###</p>';
	insertHtml(html);
}

function downremotefile(){
	publish_article(); 
	return uploadFile(3);
}

function insertHtml(html) {
	var oRTE = getFrameNode(sRTE);
	if(window.Event){
		oRTE.document.execCommand('insertHTML', false, html);
	} else {
		oRTE.focus();
		var oRng = oRTE.document.selection.createRange();
		oRng.pasteHTML(html);
		oRng.collapse(false);
		oRng.select();
	}
}

function ieGetParent() {
  var node = null;
  var oRTE = getFrameNode(sRTE);
  oRTE.focus();
  var range = oRTE.document.selection.createRange();
  node = range.parentElement();
  while (node) {
    if (node.nodeType == 1 && 
        node.nodeName != 'P' && 
        node.nodeName != 'SPAN' && 
        node.nodeName != 'A') {
        return(node);
    }
    if (node.parentNode) {
      node = node.parentNode;
    } else {
      return null;
    }
  }
}

function cleanHTML(s) {
  // 把标记名统一转换为小写
  s = cleanNodes(s);
  // 使一些标记兼容XHTML标准
  s = s.replace(/<(hr|br)>/gi, '<$1 \/>\n');
  // 使图片标记兼容XHTML标准
  s = s.replace(/<(img [^>]+)>/gi, '<$1 \/>');
  return s; 
}

function cleanNodes(s) { 
  
  // 获取标记的开始位置
  var htmlPattern = new RegExp('<[ ]*([\\w]+).*?>', 'gi');
  s = s.replace(htmlPattern, function(ref) {
    
    var cleanStartTag = ""; 
    
    // 分离标记和该标记的属性
    var ref = ref.replace('^<[ ]*', '<'); 
    var ndx = ref.search(/\s/);
    var tagname = ref.substring(0 ,ndx);
    var attributes = ref.substring(ndx, ref.length);
    
    // 把标记名统一转换为小写
    if (ndx == -1) return ref.toLowerCase(); // 简单标记
    cleanStartTag += tagname.toLowerCase();
    
    // 成对分割标记属性    
    var pairs = attributes.match(/[\w]+\s*=\s*("[^"]*"|[^">\s]*)/gi);
    if (pairs) {
      for (var t = 0; t < pairs.length; t++) {
        var pair = pairs[t];
        var ndx = pair.search(/=/);
        
        // 把属性名转换为小写
        var attrname = pair.substring(0, ndx).toLowerCase();
        
        if (attrname.match(/on\w+/i)) {
          continue;
        }
        
        // 为属性值加上双引号
        var attrval = pair.substring(ndx, pair.length);
        var wellFormed = new RegExp('=[ ]*"[^"]*"', "g");
        if (!wellFormed.test(attrval)) {
          var attrvalPattern = new RegExp('=(.*?)', 'g');
          attrval = attrval.replace(attrvalPattern, '=\"$1');
          attrval += '"';
        }
        var attr = attrname + attrval;
        cleanStartTag += " " + attr;
      }
    } 
    cleanStartTag += ">";

    return cleanStartTag;
  });

  // 把结束标记转换为小写
  s = s.replace(/<\/\s*[\w]*\s*>/g, function (ref) {return ref.toLowerCase();});

  return s;
}

function disableNodes(s) {
  // 禁用script标记(包括在属性中设置事件属性)
  var scriptPattern = new RegExp('<script[^>]*>.*<\/script\s*>', 'gi');
  s = s.replace(scriptPattern, '');
  
  //禁用frame(iframe)标记
  var framePattern = new RegExp('<i?frame[^>]*>.*<\/i?frame\s*>', 'gi');
  s = s.replace(framePattern, '');  
  
  var formPattern = new RegExp('<form[^>]*>.*</form\s*>', 'gi');
  s = s.replace(formPattern, ''); 
  
  return s;
}