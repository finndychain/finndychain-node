
var displayMonth,displayYear,displayValue,timeswitch,tformat,formElement;

/**
 * 此方法实现了:日期时间的选择功能,如果有传返回日期格式编号，
 * 则参照下列给定的格式返回时间格式，可以修改setDay()函数扩展返回日期格式
 * 
 * @param string formElt 填写时间域唯一名称或id值字符串
 * @param Object e 事件对像Event
 * @param string format 返回日期格式（选填）：格式化分3类，可自已扩展。只是要返回YYYY-mm-dd的日期格式，无须填写此参数
 * 										10～20：之间代表只有日期格式的返回字符串例：
 * 														0: 代表返回YYYY-mm-dd
 * 														11：代表返回YYYY年mm月dd日
 * 														12：返回YYYY/mm/dd
 * 										20～30：之间代表只有日期格式的返回字符串例：
 * 														21：YYYY-mm-dd HH:ii:ss
 * 														22：YYYY年mm月dd日 HH时ii分ss秒
 * @return 如果没有传入格式化编号默认返回YYYY-mm-dd的日期格式，否则按format编号返回日期格式
 * @version 1.0
 */
function getDatePicker(formElt,e,format) {
	formElement=getobject(formElt);
	if (formElement.value == "0") formElement.value = ""; //MIDYADD FIX NULL BUG
	tformat=(!format ? 0:format);
	var pElement=document.body;
	var div=document.getElementById("divHide");
  	if(!div) {
  		div = document.createElement("DIV");
  		div.id = "divHide";
  		div.style.cssText = "left:0;top:0;overflow:hidden;position:absolute;background-color:#FFF;visibility:hidden;border:1px solid #FFBE00;padding-bottom:6px;";
		pElement.appendChild(div);
	}
	timeswitch = 20;
	if(tformat < timeswitch) {
		div.onblur=hidePicker;	//含有时间格式时加上鼠标离开层时隐藏事件
	}

	var time,dateValue=formElement.value;
	if(dateValue!='') {
		var re=new RegExp("-","g"); 
		dateValue=dateValue.replace(re,'/');
		time=new Date(dateValue);
	}
	if(!time || time=='NaN')time=new Date();
	displayMonth = time.getMonth();
	displayYear = time.getFullYear();
	displayValue = time;
	
	var style=div.style;
	var width=200;
	
	//计算Div在窗口中的位置
	style.left=(e.pageX?e.pageX:e.x+document.documentElement.scrollLeft)+"px";
	style.top=(e.pageY?e.pageY:e.y+document.documentElement.scrollTop)+"px";
	style.width=width+"px";
		
	showPicker();
	style.visibility="visible";
	div.focus();
	drag(div);
}

function hidePicker() {
	var div=document.getElementById("divHide");
	div.style.visibility = 'hidden';
}

function showPicker() {
	/*********初始化当前时间************/ 
	var date;
	//判断目标域是否有值
	if(typeof displayValue == "object" ) {
		date = displayValue;
	} else {
		date=new Date();
	}
	var isThisYear = (displayYear == date.getFullYear());
	var isThisMonth = (isThisYear && displayMonth == date.getMonth());
	var today=(isThisYear && isThisMonth ? date.getDate():-1); 
	hour = date.getHours();
	second = date.getSeconds();
	minutes = date.getMinutes();
	var months = new Array("一月", "二月", "三月", "四月", "五月", "六月", "七月","八月", "九月", "十月", "十一月", "十二月");

	var html="<table id=tablePicker width=100% cellspacing=0 cellpadding=0 borderColorLight=#FFBE00 borderColorDark=#FFBE00 border=0 bgcolor=#ffffff style='table-layout:fixed'>";
	html+="<tr height=20px borderColor=#FFBE00 bgColor=#FFBE00 align=center><td colSpan=7 style='padding-top:3px;cursor:move'>";
	//display year
	html+="<font style=\"cursor:pointer\" onmousedown=\"incYear(-1)\" color=white><img src=\""+siteUrl+"/admin/images/back.gif\" /></font>&nbsp";
	html+="<font color=" + (isThisYear ? "green":"#ED1C24") + ">" + displayYear + "年</font>";
	html+="&nbsp<font style='cursor:pointer' onmousedown=\"incYear(1)\" color=white><img src=\""+siteUrl+"/admin/images/next.gif\" /></font>&nbsp;&nbsp;&nbsp;";
	//display month
	html+="<font style=\"cursor:pointer\" onmousedown=\"incMonth(-1)\" color=white><img src=\""+siteUrl+"/admin/images/back.gif\" /></font>&nbsp";
	if(displayMonth<10)html+="&nbsp";
	html+="<font color=" + (isThisMonth ? "green":"#ED1C24") + ">" + months[displayMonth] + "</font>";
	if(displayMonth<10)html+="&nbsp";
	html+="&nbsp<font style=\"cursor:pointer\" onmousedown=\"incMonth(1)\" color=white><img src=\""+siteUrl+"/admin/images/next.gif\" /></font>";
	//目标输入框为只读时显示清除按钮
	if(formElement.readOnly) {
		//判断输入框是否为只读，如果是的话，显示清除按钮
		html+="&nbsp;<span onclick='formElement.value=\"\";hidePicker();' style=\"cursor:pointer;\"><img src=\""+siteUrl+"/admin/images/btn_clear.gif\" /></span>";	
	}
	if(tformat > timeswitch) {
		html+="&nbsp;<span onclick='hidePicker();' style=\"cursor:pointer;\"><img src=\""+siteUrl+"/admin/images/btn_close1.gif\" /></span>";	
	}
	html+="</td></tr>";
	//display day
	html+="<tr height=\"20px\" align=\"center\"><td style=\"padding-top:3px;BORDER-LEFT:none;BORDER-RIGHT:none\"><font color=\"#ED1C24\">日</font></td><td class=week>一</td><td class=week>二</td><td class=week>三</td><td class=week>四</td><td class=week>五</td><td class=week><font color=\"green\">六</font></td></tr>";

	var startDay=new Date(displayYear,displayMonth,1).getDay();
	var dayOfMonthOfFirstSunday = (7 - startDay + 1);
	var intDaysInMonth=getDays(displayMonth, displayYear);
	for (var intWeek = 0; intWeek < 6; intWeek++) {
		html+="<tr height=18px align=center>";
		var dayOfMonth;
		var tdnum = 0;
	    for (var intDay = 0; intDay < 7; intDay++) {
	     	dayOfMonth = (intWeek * 7) + intDay + dayOfMonthOfFirstSunday - 7;
		 	if (dayOfMonth <= 0) {
		 			tdnum++;
	          		html+="<td height=18px borderColor=#ffffff>&nbsp</td>";
			} else if(dayOfMonth <= intDaysInMonth) {
				tdnum++;
		   		html+="<td borderColor=#ffffff "+(dayOfMonth==today ? "bgcolor=#ffcc66":"")+" onmousedown=\"setDay(" + dayOfMonth + ")\"";
		   		html+="style=\"cursor:pointer;color:" + (dayOfMonth==today ? "red":"black") + "\">" + dayOfMonth + "</td>";
			}
		}
		
		if(intWeek+1==6 && tformat > timeswitch) {
			if(tdnum != 0 && tdnum != 7) {
				html+="</tr><tr height=18px align=center>";
			}
			html+="<td colspan='7' borderColor=#ffffff><div style='margin:-1.5px;'><input maxlength='2' onmousedown='this.select()' name='hour' id='hour' style='border: 1px solid #CCCCCC; font-size:12px; line-height:20px;height:20px;width:28px;text-align:center;padding:2px;' size='1' value='"+ (hour<10?'0'+hour:hour) +"' type='text'> 时 <input maxlength='2' onmousedown='this.select()' value='"+ (minutes<10?'0'+minutes:minutes) +"' style='border: 1px solid #CCCCCC;font-size:12px; line-height:20px;height:20px;text-align:center;width:28px;padding:2px;' name='minutes' size='1' type='text'> 分 <input maxlength='2' onmousedown='this.select()' name='second' style='border: 1px solid #CCCCCC;font-size:12px;line-height:20px;height:20px;text-align:center;width:28px;padding:2px;' value='"+ (second<10?'0'+second:second) +"' size='1' type='text'> 秒</div></td>";
		}
		html+="</tr>";
		
	}
	html+="</table>";
	document.getElementById("divHide").innerHTML=html;
}

function getDays(month, year) {
	if (1==month)return ((0 == year % 4) && (0 != (year % 100))) || (0 == year % 400) ? 29 : 28;
	var daysInMonth = new Array(31, 28, 31, 30, 31, 30, 31, 31,30, 31, 30, 31);
	return daysInMonth[month];
}

function setDay(day) {
	//整理年月日时分秒
	var y,m,d,h,i,s;
	y = displayYear;
	m = (displayMonth<9 ? "0":"") + (displayMonth+1);
	d = (day<10 ? "0":"") + day;
	if(tformat && tformat > timeswitch) {
		re = /[^\d]/g;            // 创建正则表达式模式。
		hour = document.getElementsByName("hour")[0];
		minutes = document.getElementsByName("minutes")[0];
		second = document.getElementsByName("second")[0];
		if(re.test(hour.value) || Math.abs(parseInt(hour.value))>23){
			alert("时间出错：小时只能是0～23的整数");
			hour.focus();
			hour.select();
			return false;
		}
		if(re.test(minutes.value) || Math.abs(parseInt(minutes.value))>59){
			alert("时间出错：分钟只能是0～59的整数");
			minutes.focus();
			minutes.select();
			return false;
		}
		if(re.test(second.value) || Math.abs(parseInt(second.value))>59){
			alert("时间出错：分钟只能是0～59的整数");
			second.focus();
			second.select();
			return false;
		}
		h = (hour.value.length < 2 ? "0" : "") + hour.value;
		i = (minutes.value.length < 2 ? "0":"") + minutes.value;
		s = (second.value.length < 2 ? "0":"") + second.value;
	}
	var formEV="";

	//根据要返回的数据类型组合数据
	if(tformat == 0) {
		formEV = y + '-' + m + '-' + d;
	} else if(tformat == 11) {
		formEV = y + '年' + m + '月' + d + '日';
	} else if(tformat == 12) {
		formEV = y + '/' + m + '/' + d;
	} else if(tformat == 21) {
		formEV = y + '-' + m + '-' + d + ' ' + h + ':' + i + ':' + s;
	} else if(tformat == 22) {
		formEV = y + '年' + m + '月' + d + '日 ' + h + '时' + i + '分' + s + '秒';
	}
	formElement.value = formEV;
	formElement.focus();
	hidePicker();
}
/**
 * 月份增、减操作
 */
function incMonth(delta) {
	displayMonth+=delta;
	if (displayMonth>=12) {
		displayMonth = 0;
		incYear(1);
	} else if(displayMonth<=-1) {
	     displayMonth = 11;
	     incYear(-1);
	} else {
		showPicker();
	}
}
/**
 * 年份增、减操作
 */
function incYear(delta,eltName) {
	displayYear+=delta;
	showPicker();
}

/**
 * 获取给定ID或name的字符串，获取对象
 * 
 * @param string 对象ID或名称
 * @return mixed null表示没有找到对象，object 表示找到相对应的对象
 */
function getobject(idname) {
	if (document.getElementById) {
		return document.getElementById(idname);
	} else if (document.all) {
		return document.all[idname];
	} else if (document.layers) {
		return document.layers[idname];
	} else {
		return null;
	}
}

/**
 * 实现层的拖曳操作
 */
function drag(o){
    o.onmousedown = function(a){
        var d = document;
        if(!a) a = window.event;
        if(o.setCapture)
            o.setCapture();
        else if(window.captureEvents)
            window.captureEvents(Event.MOUSEMOVE|Event.MOUSEUP);

		var re = new RegExp("px","ig");
		//计算当前鼠标的坐标点离日期采集器层的零点坐标的Ｘ间距
        var x = (a.pageX?a.pageX:a.x) - parseInt(o.style.left.replace(re,""));
        //计算当前鼠标的坐标点离日期采集器层的零点坐标的Y间距
        var y = (a.pageY?a.pageY:a.y) - parseInt(o.style.top.replace(re,""));
        
        d.onmousemove = function(a){
            if(!a)a=window.event;
            if(!a.pageX)a.pageX=a.clientX;
            if(!a.pageY)a.pageY=a.clientY;
            var tx=a.pageX-x,ty=a.pageY-y;
            o.style.left = tx + "px";
            o.style.top = ty + "px";
        };
        
        d.onmouseup=function(){
            if(o.releaseCapture)
                o.releaseCapture();
            else if(window.captureEvents)
                window.captureEvents(Event.MOUSEMOVE|Event.MOUSEUP);
            d.onmousemove=null;
            d.onmouseup=null;
        };
    };
}