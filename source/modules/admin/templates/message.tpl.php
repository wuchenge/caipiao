<?php defined('IN_ADMIN') or exit('No permission resources.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=7" />
	<title>提示信息</title>
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<style type="text/css">
	<!--
	*{padding:0;margin:0;font-size:12px;}
	a:link,a:visited{text-decoration:none;color:#0068a6;}
	a:hover,a:active{color:#ff6600;text-decoration:underline;}
	.showMsg{border:1px solid #1e64c8;zoom:1;width:450px;height:172px;position:absolute;top:44%;left:50%;margin:-87px 0 0 -225px;}
	.showMsg h5{background-image:url(<?php echo IMG_PATH?>msg_img/msg.png);background-repeat:no-repeat;color:#fff;padding-left:35px;height:25px;line-height:26px;*line-height:28px;overflow:hidden;font-size:14px;text-align:left;}
	.showMsg .content{padding:10px 12px 10px 45px;font-size:14px;height:100px;line-height:96px;display:inline-block;display:-moz-inline-stack;zoom:1;*display:inline;}
	.showMsg .bottom{background:#e4ecf7;margin:0 1px 1px 1px;line-height:26px;*line-height:30px;height:26px;text-align:center;}
	.showMsg .ok,.showMsg .guery{background:url(<?php echo IMG_PATH?>msg_img/msg_bg.png) no-repeat 0px -560px;}
	.showMsg .guery{background-position:left -460px;}
	-->
	</style>
</head>
<body>
<div class="showMsg" style="text-align:center">
	<h5>提示信息</h5>
  <div class="content guery">
  	<?php echo $msg?>
  </div>
	<div class="bottom">
  	<?php
		$bottom = '';
		if($url_forward=='goback' || $url_forward=='') {
			$bottom = '<a href="javascript:history.back();" >[点这里返回上一页]</a>';
		} elseif($url_forward=="close") {
			$bottom = '<a href="javascript:;" onclick="window.close();">[关闭]</a>';
		} elseif($url_forward=="blank") {

		} elseif($url_forward) {
			$url_forward = ADMIN_PATH.'&'.$url_forward;
			$bottom = '<a href="'.url($url_forward, 1).'">如果您的浏览器没有自动跳转，请点击这里。</a>';
			$bottom .= '<script type="text/javascript">setTimeout("location.href = \"'.url($url_forward, 1).'\"",'.$ms.');</script >';
		}
		echo $bottom;
		?>
	</div>
</div>
</body>
</html>