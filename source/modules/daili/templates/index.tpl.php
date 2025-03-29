<?php defined('IN_DAILI') or exit('No permission resources.');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=7" />
	<title>代理后台</title>
	<link rel="Shortcut Icon" href="favicon.ico" />
	<link href="<?php echo CSS_PATH?>global.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo CSS_PATH?>frame.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?php echo JS_PATH?>jquery-1.8.1.min.js"></script>
	<script type="text/javascript" src="<?php echo JS_PATH?>admin_frame.js"></script>
</head>
<body scroll="no">
<div id="loading"><div class="msg"><p class="attention">加载中...</p></div></div>
<div class="header">
	<a class="logo" href="<?php echo DAILI_PATH?>">代理平台</a>
	<div class="cut_line">
		<div>
			<h3>Hi, <a href="<?php echo DAILI_PATH?>&c=userset" title="用户信息" target="right_iframe"><?php echo $this -> username ?><em>[资料设置]</em></a></h3>
		</div>
		<div class="fun">
			<a href="<?php echo WEB_PATH?>" target="_blank" class="homeLink df" title="网站首页">网站首页</a>
			<a href="<?php echo DAILI_PATH?>&c=login&a=logout" class="logout df" title="退出">退出</a>
		</div>
	</div>
</div>
<div id="content" class="content-on">
	<div class="col-left left_menu">
		<ul>
			<li class="menuli on fb blue" onclick="menu_show(this);"><a hidefocus="true" style="outline:none" href="<?php echo DAILI_PATH?>"><i class="icon-1"></i>首页</a></li>
			<div class="line-x"></div>
			<li class="menuli" onclick="menu_show(this);"><a hidefocus="true" style="outline:none" href="<?php echo DAILI_PATH?>&c=user" target="right_iframe"><i class="icon-3"></i>账户管理</a></li>
			<div class="line-x"></div>
			<li class="menuli" onclick="menu_show(this);"><a hidefocus="true" style="outline:none" href="<?php echo DAILI_PATH?>&c=order" target="right_iframe"><i class="icon-2"></i>注单管理</a></li>
			<?php if($this -> aid < 3){ ?>
			<div class="line-x"></div>
			<li class="menuli" onclick="menu_show(this);"><a hidefocus="true" style="outline:none" href="<?php echo DAILI_PATH?>&c=pay" target="right_iframe"><i class="icon-2"></i>充值管理</a></li>
			<div class="line-x"></div>
			<li class="menuli" onclick="menu_show(this);"><a hidefocus="true" style="outline:none" href="<?php echo DAILI_PATH?>&c=cash" target="right_iframe"><i class="icon-2"></i>提现管理</a></li>
			<div class="line-x"></div>
			<li class="menuli" onclick="menu_show(this);"><a hidefocus="true" style="outline:none" href="<?php echo DAILI_PATH?>&c=user&a=account" target="right_iframe"><i class="icon-2"></i>盈亏报表</a></li>
			<?php } ?>
		</ul>
	</div>
	<div class="col-auto">
		<div class="content">
			<iframe name="right_iframe" id="right_iframe" src="<?php echo DAILI_PATH?>&c=index&a=right" frameborder="false" scrolling="auto" style="overflow-x:hidden;border:none;" width="100%" height="auto" allowtransparency="true" onload="showloading()"></iframe>
		</div>
		<div id="pay_tps" class="w_tps">
			<a hidefocus="true" style="outline:none" href="<?php echo DAILI_PATH?>&c=pay" target="right_iframe">您有新的充值订单需要处理！</a>
			<i>x</i>
		</div>
		<div id="cash_tps" class="w_tps">
			<a hidefocus="true" style="outline:none" href="<?php echo DAILI_PATH?>&c=cash" target="right_iframe">您有新的提现申请需要处理！</a>
			<i>x</i>
		</div>
	</div>
</div>
<?php if($this -> aid < 3){ ?>
<script type="text/javascript">
	$(function(){
		var payid = [], cashid = [];
		var ajax_getpay = function(t) {// 取得最新充值订单
			$.ajax({
				url: '<?php echo DAILI_PATH?>&c=index&a=ajax_getpay',
				data: {},
				type: 'GET',
				dataType: 'json',
				success: function(e) {
					var ids = e.ids;
					if (ids) {
						var strs = new Array();
						strs = ids.split(',');
						var len = strs.length;
						var num = 0;
						for (i = 0; i < len; i++) {
							if (payid.indexOf(strs[i]) == -1) {
								num++;
							}
						}
						if (num > 0) {
							playSound();
							payid = strs;
							$('#pay_tps a').text('您有'+num+'条充值申请需要处理！');
							$('#pay_tps').show();
						}
					}
				},
				error: function() {
					console.log('ERR');
				}
			});
		},ajax_getcash = function(t) {// 取得最新提现申请
			$.ajax({
				url: '<?php echo DAILI_PATH?>&c=index&a=ajax_getcash',
				data: {},
				type: 'GET',
				dataType: 'json',
				success: function(e) {
					var ids = e.ids;
					if (ids) {
						var strs = new Array();
						strs = ids.split(',');
						var len = strs.length;
						var num = 0;
						for (i = 0; i < len; i++) {
							if (cashid.indexOf(strs[i]) == -1) {
								num++;
							}
						}
						if (num > 0) {
							playSound();
							cashid = strs;
							$('#cash_tps a').text('您有'+num+'条提现申请需要处理！');
							$('#cash_tps').show();
						}
					}
				},
				error: function() {
					console.log('ERR');
				}
			});
		};
		setInterval(function(){
			var stamp = Date.parse(new Date());//当前时间戳
			ajax_getpay(stamp);
			ajax_getcash(stamp);
		},10000);//10秒刷新一次
		$('.col-auto .w_tps i').click(function() {
			$(this).parent().hide();
			playSound(true);
		});
	});
</script>
<?php } ?>
</body>
</html>