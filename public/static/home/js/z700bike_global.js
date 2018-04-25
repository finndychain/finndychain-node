if (jQuery('#theme-my-login').length) {
	(function ($) {
	    $.fn.fullscreenr = function (options) {
	        if (options.height === undefined) alert('Please supply the background image height, default values will now be used. These may be very inaccurate.');
	        if (options.width === undefined) alert('Please supply the background image width, default values will now be used. These may be very inaccurate.');
	        if (options.bgID === undefined) alert('Please supply the background image ID, default #bgimg will now be used.');
	        var defaults = { width: 1280, height: 1024, bgID: 'bgimg' };
	        var options = $.extend({}, defaults, options);
	        $(document).ready(function () { $(options.bgID).fullscreenrResizer(options); });
	        $(window).bind("resize", function () { $(options.bgID).fullscreenrResizer(options); });
	        return this;
	    };
	    $.fn.fullscreenrResizer = function (options) {
	        // Set bg size
	        var ratio = options.width / options.height;
	        // Get browser window size
	        var browserheight = $(window).height();
	        var browserwidth = browserheight * ratio+2;
	        // Scale the image

	        // Center the image
	        //$(this).css('left', (browserwidth - $(this).width())/2);
	        //$(this).css('top', (browserheight - $(this).height())/2);
	        $(this).css('height', browserheight);
	        $("#page_login_div").css('width', browserwidth + "px");
	        return this;
	    };
	})(jQuery);
jQuery(document).ready(function ($) {   
			var FullscreenrOptions = {  width: 480, height: 663, bgID: '#page_login_img' };
		jQuery.fn.fullscreenr(FullscreenrOptions);
	 /*var $li_first=$('#theme-my-login').find('.tml-action-links li:first a'),
			text=$li_first.text();
		$li_first.text(text+' |');
		if ($('#lostpasswordform').length) {
			$('#theme-my-login').addClass('lostpw');
		}
		if ($('#registerform').length) {
			$('#theme-my-login').addClass('register').find('#registerform').find('p:eq(1)').nextAll('p').not('.submit').addClass('register_p');
			// $('#CAPTCHA').parent().parent().remove();
			// $('#captcha_img').parent().parent().remove();
			$('#reg_passmail').remove();
			$('#CAPTCHA').parent().parent().addClass('captcha').next().addClass('captcha_img');
		}
    */
	});
}

jQuery(document).ready(function ($) {

    //home slider
    if ($('#zSlider').length) {
        var cccccsetTime = null;
        zSlider();
        //$("#select_btn").hide();
        //$('#picshow_right_cover, #picshow_right img').hover(function () {
        //clearTimeout(cccccsetTime);
        var d = $("#picshow_right_cover");
        var dt = $("#picshow_right");
        var urlText = dt.find("a").attr("href");
        d.text(dt.find("img").attr("alt"));
        if (typeof (urlText) == "string" && urlText != "")
            $("#hdUrlFocus").val(urlText);
        // }, function () {
        //cccccsetTime = setTimeout(function () { $("#picshow_right_cover").hide(); }, 500);
        //});
    }
    function zSlider(ID, delay) {
        var ID = ID ? ID : '#zSlider';
        var delay = delay ? delay : 5000;
        var currentEQ = 0, picnum = $('#picshow_img li').size(), autoScrollFUN;
        $('#select_btn li').eq(currentEQ).addClass('current');
        var cu = $('#picshow_img li').eq(currentEQ);
        cu.fadeIn(100);
        $("#focus-tl-s").html(cu.find("img").attr("alt"));
        autoScrollFUN = setTimeout(autoScroll, delay);
        $('#focus-left').click(function () {
            clearTimeout(autoScrollFUN);
            var lis = $('#select_btn li');
            var picEQ = 0;
            $.each(lis, function (i, v) {
                if ($(v).attr("class") == "current") {
                    picEQ = i;
                    return false;
                }
            });
            if (picEQ > 0) {
                currentEQ = picEQ - 1;
            }
            else {
                currentEQ = picnum - 1;
            }
            $('#select_btn li').removeClass('current');
            $('#picshow_img li').hide();
            var cu = $('#picshow_img li').eq(currentEQ);
            $('#select_btn li').eq(currentEQ).addClass('current');
            cu.fadeIn(100);
            $("#focus-tl-s").html(cu.find("img").attr("alt"));
            return false;
        });
        $('#focus-right').click(function () {
            clearTimeout(autoScrollFUN);
            var lis = $('#select_btn li');
            var picEQ = 0;
            $.each(lis, function (i, v) {
                if ($(v).attr("class") == "current") {
                    picEQ = i;
                    return false;
                }
            });
            if (picEQ == picnum - 1) {
                currentEQ = 0;
            }
            else {
                currentEQ = picEQ + 1;
            }
            $('#select_btn li').removeClass('current');
            $('#picshow_img li').hide();
            var cu = $('#picshow_img li').eq(currentEQ);
            $('#select_btn li').eq(currentEQ).addClass('current');
            cu.fadeIn(100);
            $("#focus-tl-s").html(cu.find("img").attr("alt"));
            return false;
        });
        function autoScroll() {
            clearTimeout(autoScrollFUN);
            currentEQ++;
            if (currentEQ > picnum - 1) currentEQ = 0;
            $('#select_btn li').removeClass('current');
            $('#picshow_img li').hide();
            $('#select_btn li').eq(currentEQ).addClass('current');
            var cu = $('#picshow_img li').eq(currentEQ);
            cu.fadeIn(100);

            $("#focus-tl-s").html(cu.find("img").attr("alt"));
            autoScrollFUN = setTimeout(autoScroll, delay);
        }
        $('#picshow').hover(function () {
            clearTimeout(autoScrollFUN);
        }, function () {
            autoScrollFUN = setTimeout(autoScroll, delay);
        });
        $('#select_btn li').hover(function () {
            var picEQ = $('#select_btn li').index($(this));
            if (picEQ == currentEQ) return false;
            currentEQ = picEQ;
            $('#select_btn li').removeClass('current');
            $('#picshow_img li').hide();
            $('#select_btn li').eq(currentEQ).addClass('current');
            var cu = $('#picshow_img li').eq(currentEQ);
            cu.fadeIn(100);
            $("#focus-tl-s").html(cu.find("img").attr("alt"));
            return false;
        });
    };

    //post hover
    if ($('#list_style').length || $('#list_style_2').length) {

        jQuery.fn.postHover = function () {
            $(this).hover(function () {
                $(this).find('img').fadeTo(100, 0.5).parents('.post-thumbnail').nextAll().not('.list_style_2_info,.list_style_2_date,.entry').show();
                var isbigPic = $(this).attr('class').split(' ')[1], b = '30px';
                if (isbigPic == 'bigpic_post') b = '110px';
                $(this).find('.jiatu').animate({ bottom: (b) }, 150);
            }, function () {
                $(this).find('img').fadeTo(200, 1).parents('.post-thumbnail').nextAll().hide();
                $(this).find('.jiatu').animate({ bottom: ('-41px') }, 100);
            });
        };
        jQuery.fn.postHover_newHome = function () {
            $(this).hover(function () {
                $(this).find('img').fadeTo(100, 0.5).parents('.post-thumbnail').nextAll().not('.list_style_2_info,.list_style_2_date').show();
                var isbigPic = $(this).attr('class').split(' ')[1], b = '100px';
                if (isbigPic == 'bigpic_post') b = '110px';
                $(this).find('.jiatu').animate({ bottom: (b) }, 150);
            }, function () {
                $(this).find('img').fadeTo(200, 1).parents('.post-thumbnail').nextAll().not('.title').hide();
                $(this).find('.jiatu').animate({ bottom: ('-41px') }, 100);
            });
        };
        var $posts = $('#content div.post'), $list_style_pagination = $('#list_style_pagination');
        if ($('#newHome').length) {
            $posts.postHover_newHome();
        } else {
            $posts.postHover();
        }

        if ($('#switchstyle').length) {

            //jquery.cookie plugin
            jQuery.cookie = function (name, value, options) {
                if (typeof value != 'undefined') { // name and value given, set cookie
                    options = options || {};
                    if (value === null) {
                        value = '';
                        options.expires = -1;
                    }
                    var expires = '';
                    if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
                        var date;
                        if (typeof options.expires == 'number') {
                            date = new Date();
                            date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
                        } else {
                            date = options.expires;
                        }
                        expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
                    }
                    // CAUTION: Needed to parenthesize options.path and options.domain
                    // in the following expressions, otherwise they evaluate to undefined
                    // in the packed version for some reason...
                    var path = options.path ? '; path=/' : '';
                    var domain = options.domain ? '; domain=' + (options.domain) : '';
                    var secure = options.secure ? '; secure' : '';
                    document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
                } else { // only name given, get cookie
                    var cookieValue = null;
                    if (document.cookie && document.cookie != '') {
                        var cookies = document.cookie.split(';');
                        for (var i = 0; i < cookies.length; i++) {
                            var cookie = jQuery.trim(cookies[i]);
                            // Does this cookie string begin with the name we want?
                            if (cookie.substring(0, name.length + 1) == (name + '=')) {
                                cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                                break;
                            }
                        }
                    }
                    return cookieValue;
                }
            };
            if ($.cookie('z700bikeStyle_120712') == 'style1') {
                $('#style1').hide();
                $('#style2').show();
                $list_style_pagination.show();
                $posts.postHover();
            } else if ($.cookie('z700bikeStyle_120712') == 'style2') {
                $('#style2').hide();
                $('#style1').show();
                $list_style_pagination.hide();
                $posts.unbind('hover').find('img').parents('.post-thumbnail').nextAll().show();
            } else {
                $('#style2').hide();
                $('#style1').show();
                $list_style_pagination.hide();
                $posts.unbind('hover').find('img').parents('.post-thumbnail').nextAll().show();
                $.cookie('z700bikeStyle_120712', null);
                $.cookie('z700bikeStyle_120712', 'style2', { expires: 30, path: '/' });
            }

            // $('#content').show();

            $('#style1,#style2').click(function () {
                if ($(this).attr('id') == 'style2') {
                    $('#style2').hide();
                    $('#style1').show();
                    $list_style_pagination.hide();
                    $('body').removeClass('list_style').removeClass('ls_hidden').addClass('list_style_2');
                    $.cookie('z700bikeStyle_120712', null);
                    $.cookie('z700bikeStyle_120712', 'style2', { expires: 30, path: '/' });
                    $posts.unbind('hover').find('img').parents('.post-thumbnail').nextAll().show();
                } else {
                    $('#style1').hide();
                    $('#style2').show();
                    $list_style_pagination.show();
                    $('body').removeClass('list_style_2').removeClass('ls_hidden').addClass('list_style');
                    $.cookie('z700bikeStyle_120712', null);
                    $.cookie('z700bikeStyle_120712', 'style1', { expires: 30, path: '/' });
                    $posts.find('img').parents('.post-thumbnail').nextAll().hide();
                    $posts.postHover();
                }
                return false;
            });
        }

        //author.php author's avatar hover
        if ($('#a_avatar').length) {
            $('#a_avatar').hover(function () {
                $('#a_city').show();
            }, function () {
                $('#a_city').hide();
            });
        }

    }

    //navi
    $('#navi').children('ul').children('li').each(function () {
        if ($(this).children().is('ul')) $(this).children('a:first').addClass('havechild');
    });
    $('#navi').children('ul').children('li').hover(function () {
        $(this).children('ul').show();
    }, function () {
        $(this).children('ul').hide();
    });
    function SeeTheBottomNav() {

    }
    //scroll
    var $scroll = $('#scroll'),
		$body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
    var $backToTopFun = function () {
        var windowH = $(window).height(),
			st = $(document).scrollTop();
        (st > windowH / 2) ? (
			$scroll.fadeIn(800)
		) : (
			$scroll.fadeOut(300)
		);
        SeeTheBottomNav();
    };
    $(window).bind('scroll', $backToTopFun);
    $scroll.click(function () {
        $body.animate({ scrollTop: $('#header').offset().top }, 1000);
    });
    $scroll.addClass("scroll");
    SeeTheBottomNav();
    $scroll.hover(function () { $(this).addClass("scroll_over"); }, function () { $(this).removeClass("scroll_over"); });
});