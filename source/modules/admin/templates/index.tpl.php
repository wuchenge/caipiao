<?php defined('IN_ADMIN') or exit('No permission resources.');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=7" />
	<title>管理后台</title>
	<link rel="Shortcut Icon" href="favicon.ico" />
	<link href="<?php echo CSS_PATH?>global.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo CSS_PATH?>frame.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?php echo JS_PATH?>jquery-1.8.1.min.js"></script>
	<script type="text/javascript" src="<?php echo JS_PATH?>admin_frame.js"></script>
</head>
<body scroll="no">
<div id="loading"><div class="msg"><p class="attention">加载中...</p></div></div>
<div class="header">
	<a class="logo" href="<?php echo ADMIN_PATH?>">管理平台</a>
	<div class="cut_line">
		<div>
			<h3>Hi, <a href="<?php echo ADMIN_PATH?>&c=userset" title="用户信息" target="right_iframe"><?php echo $username ?><em>[资料设置]</em></a></h3>
		</div>
		<div class="fun">
			<a href="<?php echo WEB_PATH?>" target="_blank" class="homeLink df" title="网站首页">网站首页</a>
			<a href="<?php echo ADMIN_PATH?>&c=login&a=logout" class="logout df" title="退出">退出</a>
		</div>
	</div>
</div>
<div id="content" class="content-on">
	<div class="col-left left_menu">
		<ul>
			<li class="menuli on fb blue" onclick="menu_show(this);"><a hidefocus="true" style="outline:none" href="<?php echo ADMIN_PATH?>"><i class="icon-1"></i>首页</a></li>
			<div class="line-x"></div>
			<li class="menuli title" onclick="">综合管理</li>
			<?php if($this -> super){?>
			<div class="line-x"></div>
			<li class="menuli" onclick="menu_show(this);"><a hidefocus="true" style="outline:none" href="<?php echo ADMIN_PATH?>&c=settings" target="right_iframe"><i class="icon-6"></i>通用设置</a></li>
			<div class="line-x"></div>
			<li class="menuli" onclick="menu_show(this);"><a hidefocus="true" style="outline:none" href="<?php echo ADMIN_PATH?>&c=administrator" target="right_iframe"><i class="icon-5"></i>管理员管理</a></li>
			<?php }?>
			<div class="line-x"></div>
			<li class="menuli" onclick="menu_show(this);"><a hidefocus="true" style="outline:none" href="<?php echo ADMIN_PATH?>&c=user" target="right_iframe"><i class="icon-3"></i>账户管理</a></li>
			<div class="line-x"></div>
			<li class="menuli title" onclick="">游戏管理</li>
			<?php if($this -> super){?>
			<div class="line-x"></div>
			<li class="menuli" onclick="menu_show(this);"><a hidefocus="true" style="outline:none" href="<?php echo ADMIN_PATH?>&c=game" target="right_iframe"><i class="icon-4"></i>游戏管理</a></li>
			<li class="menuli" onclick="menu_show(this);"><a hidefocus="true" style="outline:none" href="<?php echo ADMIN_PATH?>&c=game&a=peilv" target="right_iframe"><i class="icon-4"></i>游戏赔率</a></li>
			<?php }?>
			<div class="line-x"></div>
			<li class="menuli" onclick="menu_show(this);"><a hidefocus="true" style="outline:none" href="<?php echo ADMIN_PATH?>&c=order" target="right_iframe"><i class="icon-2"></i>注单管理</a></li>
			<div class="line-x"></div>
			<?php if($this -> super){?>
			<li class="menuli" onclick="menu_show(this);"><a hidefocus="true" style="outline:none" href="<?php echo ADMIN_PATH?>&c=account" target="right_iframe"><i class="icon-2"></i>流水管理</a></li>
			<div class="line-x"></div>
			<?php }?>
			<li class="menuli" onclick="menu_show(this);"><a hidefocus="true" style="outline:none" href="<?php echo ADMIN_PATH?>&c=pay" target="right_iframe"><i class="icon-2"></i>充值管理</a></li>
			<div class="line-x"></div>
			<li class="menuli" onclick="menu_show(this);"><a hidefocus="true" style="outline:none" href="<?php echo ADMIN_PATH?>&c=cash" target="right_iframe"><i class="icon-2"></i>提现管理</a></li>
			<li class="menuli" onclick="menu_show(this);"><a hidefocus="true" style="outline:none" href="<?php echo ADMIN_PATH?>&c=account&a=day" target="right_iframe"><i class="icon-2"></i>盈亏统计</a></li>
			<li class="menuli" onclick="menu_show(this);"><a hidefocus="true" style="outline:none" href="<?php echo ADMIN_PATH?>&c=account&a=user" target="right_iframe"><i class="icon-2"></i>盈亏报表</a></li>
		</ul>
	</div>
	<div class="col-auto">
		<div class="content">
			<iframe name="right_iframe" id="right_iframe" src="<?php echo ADMIN_PATH?>&c=index&a=right" frameborder="false" scrolling="auto" style="overflow-x:hidden;border:none;" width="100%" height="auto" allowtransparency="true" onload="showloading()"></iframe>
		</div>
		<div id="pay_tps" class="w_tps">
			<a hidefocus="true" style="outline:none" href="<?php echo ADMIN_PATH?>&c=pay" target="right_iframe">您有新的充值订单需要处理！</a>
			<i>x</i>
		</div>
		<div id="cash_tps" class="w_tps">
			<a hidefocus="true" style="outline:none" href="<?php echo ADMIN_PATH?>&c=cash" target="right_iframe">您有新的提现申请需要处理！</a>
			<i>x</i>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		var payid = [], cashid = [];
		var ajax_getprompt = function(t){
			$.ajax({
				url: '<?php echo ADMIN_PATH?>&c=index&a=ajax_getprompt',
				data: {},
				type: 'GET',
				dataType: 'json',
				success: function(e) {
					var pay_ids = e.pay_ids;
					var cash_ids = e.cash_ids;
					if(pay_ids.length > 0){
						playSound();
						payid = pay_ids;
						$('#pay_tps a').text('您有'+pay_ids.length+'条充值申请需要处理！');
						$('#pay_tps').show();
					}else if(cash_ids.length > 0){
						playSound();
						cashid = cash_ids;
						$('#cash_tps a').text('您有'+cash_ids.length+'条提现申请需要处理！');
						$('#cash_tps').show();
						$('#pay_tps').hide();
					}else{
						$('#pay_tps').hide();
						$('#cash_tps').hide();
						playSound(true);
					}
					// console.log(e);
				},
				error: function() {
					console.log('ERR');
				}
			});
		}
		
		setInterval(function(){
			var stamp = Date.parse(new Date());//当前时间戳
			/*ajax_getpay(stamp);
			ajax_getcash(stamp);*/
			ajax_getprompt(stamp);

		},3000);//10秒刷新一次
		$('.col-auto .w_tps i').click(function() {
			$(this).parent().hide();
			playSound(true);
		});
	});
</script>
</body>
</html>