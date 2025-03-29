<?php defined('IN_ADMIN') or exit('No permission resources.');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET?> />
	<meta http-equiv="X-UA-Compatible" content="IE=7" />
	<title>管理后台登录</title>
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<link rel="Shortcut Icon" href="favicon.ico" />
	<link href="<?php echo CSS_PATH?>global.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo CSS_PATH?>login.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="login_box" style='height:380px;'>
	<div class="login">
		<div class="title">管理后台登录</div>
		<div class="login_ibox">
			<form action="<?php echo ADMIN_PATH?>&c=login&a=logind" method="post" id="form">
				<div class="input_box">
					<div class="input_caption">
						<img src="<?php echo IMG_PATH?>user.png" class="input_caption_img">
					</div>
					<div class="input_value">
						<input type="text" id="input-u" name="username" value="" placeholder="请输入帐号" />
					</div>
				</div>
				<div class="input_box">
					<div class="input_caption">
						<img src="<?php echo IMG_PATH?>pwd.png" class="input_caption_img">
					</div>
					<div class="input_value">
						<input type="password" id="input-p" name="password" value="" placeholder="请输入登录密码" />
					</div>
				</div>
				<div class="input_box">
					<div class="input_caption">
						<img src="<?php echo IMG_PATH?>pwd.png" class="input_caption_img">
					</div>
					<div class="input_value">
						<input type="password" id="input-p" name="google_secret" value="" placeholder="请输入Google验证码" />
					</div>
				</div>
				<div class="btn_box">
					<div class="input_box">
						<div class="input_caption">
							<img src="<?php echo IMG_PATH?>yzm.png" class="input_caption_img">
						</div>
						<div class="input_value">
							<input type="text" id="input-c" name="code" value="" placeholder="请输入验证码" />
						</div>
						<div class="input_value">
							<?php echo form::checkcode('code_img', '4', '14', 84, 22, '' , '', '#FFFFFF')?>
						</div>
					</div>
					<input class="login_btn" type="submit" value="登录" />
				</div>
			</form>
		</div>
	</div>
</div>
</body>
</html>