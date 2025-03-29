var drag = new Object();
	drag = {
		opts: function(opt){
			var opts = {
				warp: '', //--可选参数，父容器
			}
			return $.extend(opts, opt, true);
		},
		on: function(opt, callback){ //创建
			var _this = this;
			var _opts = _this.opts(opt);
			var oL,oT,oLeft,oTop;
			var ui = {
				warp: document.querySelector('body'),
				main: _opts.warp,
				currentHeight: _opts.warp.offsetHeight,
				maxW: window.screen.width - _opts.warp.offsetWidth,
				maxH: window.screen.height - _opts.warp.offsetHeight
			}
			ui.main.addEventListener('touchstart', function(e) {
				var ev = e || window.event;
				var touch = ev.targetTouches[0];
				oL = touch.clientX - ui.main.offsetLeft;
				oT = touch.clientY - ui.main.offsetTop;
				window.addEventListener("touchmove", preventDefault, { passive: false });
			})
			ui.main.addEventListener('touchmove', function(e) {
				var _self = this;
				var ev = e || window.event;
				var touch = ev.targetTouches[0];
				oLeft = touch.clientX - oL;
				oTop = touch.clientY - oT;
				oLeft < 0 ? oLeft = 0 : oLeft >= ui.maxW ? oLeft = ui.maxW : '';
				oTop < 0 ? oTop = 0 : oTop >= ui.maxH ? oTop = ui.maxH : '';
				ui.main.style.left = oLeft + 'px';
				ui.main.style.top = oTop + 'px';
				typeof callback == "function" ?  callback({el: _self, type: 'touchmove'}) : '';
			})
			ui.main.addEventListener('touchend', function() {
				var _self = this;
				oLeft > 0 && oLeft < ui.maxW / 2 ? oLeft = 0 : oLeft > ui.maxW / 2 && oLeft < ui.maxW ? oLeft = ui.maxW : '';
				ui.main.style.left = oLeft + 'px';
				ui.main.style.transition = 'left .3s';
				var timer = setTimeout(function(){
					ui.main.style.transition = 'auto';
					clearInterval(timer);
				},300);
				ui.main.style.height = ui.currentHeight + "px";
				ui.main.style.top = oTop + 'px';
                window.removeEventListener("touchmove", preventDefault);
				typeof callback == "function" ?  callback({el: _self, type: 'touchend'}) : '';
			})
            function preventDefault(e){
                e.preventDefault();
            }
		}
	}
