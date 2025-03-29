function windowW(){//框架调整
	if($(window).width()<1440){
		$('.header').css('width',1440+'px');
		$('#content').css('width',1440+'px');
		$('body').attr('scroll','');
		$('body').css('overflow','');
	}
}
$(window).resize(function(){
	if($(window).width()<1440){
		windowW();
	}else{
		$('.header').css('width','auto');
		$('#content').css('width','auto');
		$('body').attr('scroll','no');
		$('body').css('overflow','hidden');
	}
});

window.onresize = function(){
	var heights = $(window).height()-84;
	$("#right_iframe").height(heights+39);
	$("#content").height(heights+39);
}

$(document).ready(function () {
	windowW();
	window.onresize();
});

function menu_show(obj) {//菜单选中
	$('.menuli').removeClass('on fb blue');
	$(obj).addClass('on fb blue');
}

function showloading(type){//loading
	if(type) {
		$('#loading').show();
	} else {
		$('#loading').fadeOut("slow");
	}
}

//播放提示音乐
function playSound(stop) {
	var a = $('#SOUND');
	if (a.length == 0) {
		a = $('<audio id="SOUND" loop="loop"><source src="statics/images/task.mp3" type="audio/mpeg"/></audio>').appendTo($('body'))[0];
		if (a.load) {
			a.load()
		}
	} else {
		a = a[0]
	}
	if (stop) {
		a.pause()
	} else if (a.play) {
		a.play()
	}
}