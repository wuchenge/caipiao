<?php
defined('IN_MYWEB') or exit('No permission resources.');
base :: load_app_class('daili', 'daili', 0);
base :: load_sys_class('form', '', 0);
class login extends daili {
	public function init() {
		include $this -> daili_tpl('login');
	}

	public function logind() {
		$username = isset($_POST['username']) && trim($_POST['username']) ? safe_replace(trim($_POST['username'])) : showmessage('用户名不能为空！', HTTP_REFERER);
		$password = isset($_POST['password']) && trim($_POST['password']) ? trim($_POST['password']) : showmessage('密码不能为空！', HTTP_REFERER);
		$code = isset($_POST['code']) && trim($_POST['code']) ? trim($_POST['code']) : showmessage('请输入验证码！', HTTP_REFERER);
		if (get_cookie('code') != strtolower($code)) {
			//showmessage('验证码错误或已过期', HTTP_REFERER);
		}
		if ($this -> login($username, $password)) {
			set_cookie('code'); //清空验证码COOKIE
			showmessage('登录成功!', 'c=index');
		} else {
			showmessage($this -> get_err(), 'c=login');
		}
	}

	public function logout() {
		$this -> log_out();
		showmessage('退出成功！', 'c=login');
	}
}
?>