;(function($) {
	var ajax = $.ajax;
	$.ajax = function(s) {
		var old = s.error;
		var errHeader = s.errorHeader || "Error-Json";
		s.error = function(xhr, status, err) {
			try {
				var errMsg = window["eval"]("(" + xhr.getResponseHeader(errHeader) + ")");
				if(typeof(errMsg.id)=="string"){
				   location.href = "/";
				}
			}
			catch (ex) {    
				var errMsg = window["eval"]("(" + "{id:'00000000-0000-0000-0000-000000000000',msg:'啊哦，好像出了点儿问题。',script:''}" + ")");
			}
		}
		ajax(s);
	}
})(jQuery);