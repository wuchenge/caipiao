  (function() {
    /**
     * 拖拽方法
     * @param id 元素id
     * @param options 可拖拽的位置top、right、bottom、left
     * @constructor
     */
    var Drag = function(id,options) {
      //插入动画过渡
      var style = document.createElement("style");
      style.type = "text/css";
      style.appendChild(document.createTextNode(".transition{transition: all .3s}"));
      document.getElementsByTagName("head")[0].appendChild(style);
 
      var $id = document.getElementById(id),
          x,
          y,
          domH = $id.offsetHeight,
          domW = $id.offsetWidth,
          windowW = window.screen.availWidth ,
          windowH = window.screen.availHeight ,
          _left = 0,
          top = 0,
          right = 0,
          bottom = 0,
          left = 0;
 
      if(options){
        top = options.top?options.top:0;
        right = options.right?options.right:0;
        bottom = options.bottom?options.bottom:0;
        left = options.left?options.left:0;
      }
 
      $id.addEventListener('touchstart',touch,false);
      $id.addEventListener('touchmove',touch,false);
      $id.addEventListener('touchend',touch,false);
 
      function touch(event){
        var event = event || window.event;
        switch(event.type){
          case "touchstart":
            x = parseInt(event.touches[0].clientX);
            y = parseInt(event.touches[0].clientY);
            break;
          case "touchend":
            y =  parseInt(event.changedTouches[0].clientY);
            x = parseInt(event.changedTouches[0].clientX);
 
            f()
 
            //缓慢过渡效果
            $id.setAttribute('class','transition');
            setTimeout(function() {
              $id.setAttribute('class','');
            },300)
 
            //小于一半到左边，大于一半到右边
            if(x >windowW/2){
              _left = windowW - domW + right
            }else {
              _left = 0 + left
            }
 
            $id.style.top = y - (domH/2)+"px";
            $id.style.left = _left +"px";
            break;
          case "touchmove":
            event.preventDefault();
            y =  parseInt(event.touches[0].clientY);
            x = parseInt(event.touches[0].clientX);
 
            f()
 
            $id.style.top = y - (domH/2) +"px";
            $id.style.left = x - (domW/2) +"px";
            break;
        }
      }
 
      //不可移出边界
      function f() {
        if(y <= (domH/2 + top)){
          y = domH/2 + top
        }
        if(y >= windowH - (domH/2 + bottom)){
          y = windowH - (domH/2 + bottom)
        }
        if(x <= (domW/2 + left)){
          x = domW/2 + left
        }
        if(x >= windowW - (domW/2 + right)){
          x = windowW - (domW/2 + right)
        }
      }
    }
    window.Drag = Drag
  })();
 
 
new Drag('indexbtn',{bottom:100})
 
 
 