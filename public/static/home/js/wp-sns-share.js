/*
 Plugin Name: wp-sns-share
 Version: 2.5
 */

function WPSNS_getElementsByClassName(tagName, className){
	var list = [], allList = document.getElementsByTagName(tagName);
	for(var i = 0;i < allList.length;i++){
		if(allList[i].className == className){
			list[list.length] = allList[i];
		}
	}
	return list;
}

function WPSNS_init(){
	var WPSNS_block = WPSNS_getElementsByClassName("ul", "WPSNS_ul");
	for(var num = 0;num < WPSNS_block.length;num++){
		var WPSNS_Li_list = WPSNS_block[num].childNodes;
		for(var i = 0;i < WPSNS_Li_list.length;i++){
			var item = WPSNS_Li_list[i];
			if(item.className == "WPSNS_item"){
				var item_a = item.firstChild;
				//not element node || it's element node but not A node
				while(item_a.nodeType != 1 || item_a.tagName.toUpperCase() != "A"){
					item_a = item_a.nextSibling;
				}
				if(item_a.tagName.toUpperCase() == "A"){
					item_a.onmouseover = function(e){
						var evt = window.event ? window.event.srcElement : e.target;
						evt.nextSibling.style.display = '';
//						if(document.all){
//							window.event.srcElement.nextSibling.style.display = '';
//						}
//						else{
//							e.target.nextSibling.style.display = '';
//						}
					};
					item_a.onmouseout = function(e){
						var evt = window.event ? window.event.srcElement : e.target;
						evt.nextSibling.style.display = 'none';
//						if(document.all){
//							window.event.srcElement.nextSibling.style.display = 'none';
//						}
//						else{
//							e.target.nextSibling.style.display = 'none';
//						}
					};
				}
			}
		}
	}
}

function shareToSNS(sns, tiny) {
	var url = encodeURIComponent(location.href);
	var title = encodeURIComponent(document.title);
	if(tiny == 0 || !useTinyURL(sns)){
		share(url, title, sns);
	}
	else if(tiny == 1){
		var tiny = document.getElementById("wp-sns-share-tiny").value;
		if(tiny != "")
			share(tiny, title, sns);
		else
			share(url, title, sns);
	}
}

function share(url, title, sns){
	var shareURL = "";
	var width = 626;
	var height = 436;
	var desc = document.getElementById("wp-sns-share-desc").value;
	var blog = document.getElementById("wp-sns-share-blog").value;
	var weibo_content = ' 来自：' + blog + ' 《' + title + '》 ' + desc;
	
	if (sns == "renren") {
		shareURL = '/share.xiaonei.com/share/buttonshare.do?link='+ url + '&title='+ title;
	} else if (sns == "douban") {
		shareURL = '/www.douban.com/recommend/?url='+ url + '&title='+ title;
	} else if (sns == "kaixin") {
		width = 1050;
		height = 600;
		shareURL = '/www.kaixin001.com/~repaste/repaste.php?&rurl='+ url + '&rtitle='+ title;
	} else if (sns == "sina") {
		shareURL = '/v.t.sina.com.cn/share/share.php?title='+ weibo_content + '&url='+ url;
	} else if (sns == "t163") {
		var source = '网易微博';
		shareURL = '/t.163.com/article/user/checkLogin.do?source='+ source 
					+'&info='+ weibo_content + ' ' + url + '&' + new Date().getTime();
	} else if (sns == "tsohu") {
		var width = 700;
		shareURL = '/t.sohu.com/third/post.jsp?content=utf-8&title='+ weibo_content + '&url='+ url;
	} else if (sns == "fanfou") {
		var d = encodeURIComponent(window.getSelection ? window.getSelection().toString()
					: document.getSelection ? document.getSelection()
											: document.selection.createRange().text);
		shareURL = '/fanfou.com/sharer?t='+ title + '&u='+ url + '&d=' + d;
	} else if (sns == "qqzone") {
		width = 1050;
		height = 600;
		shareURL = '/sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='
				+ url + '&title='+ title;
	} else if (sns == "tqq") {
		shareURL = '/v.t.qq.com/share/share.php?title='+ weibo_content + '&url='+ url;
	} else if (sns == "baidu") {
		width = 1050;
		height = 600;
		shareURL = '/apps.hi.baidu.com/share/?url='+ url + '&title='+ title;
	} else if (sns == "gmark") {
		width = 800;
		height = 700;
		shareURL = '/www.google.com/bookmarks/mark?op=add&bkmk='+ url + '&title='+ title;
	} else if (sns == "gbuzz") {
		width = 800;
		shareURL = '/www.google.com/buzz/post?url='+ url + '&title='+ title;
	} else if (sns == "delicious") {
		width = 1050;
		height = 835;
		shareURL = '/del.icio.us/post?url='+ url + '&title='+ title;
	} else if (sns == "twitter") {
		width = 800;
		height = 515;
		shareURL = '/twitter.com/home?status='+ weibo_content + ' ' + url;
	} else if (sns == "facebook") {
		shareURL = '/www.facebook.com/sharer.php?u='+url+'&t='+title;
	} else if (sns == "linkedin") {
		shareURL = '/www.linkedin.com/shareArticle?url='+url+'&title='+title;
	}
	
	if(!document.all)
		window.open(shareURL, title, 'toolbar=0,resizable=1,scrollbars=yes,status=1,width=' 
				+ width + ',height=' + height);
	else
		window.open(shareURL);
}

function useTinyURL(sns){
	var list = new Array("twitter");
	for (i in list){
		if(list[i] == sns)
			return true;
	}
	return false;
}

if(window.addEventListener){
	window.addEventListener("load", WPSNS_init, false);
}
else if(window.attachEvent){
	window.attachEvent("onload", WPSNS_init, false);
}
