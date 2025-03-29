<?php
defined('IN_MYWEB') or exit('No permission resources.');
base :: load_app_class('admin', 'admin', 0);
class userset extends admin {
	private $db, $a_arr;
	public function __construct() {
		parent :: __construct();
		$this -> db = base :: load_model('admin_model');
		$this -> a_arr[ROUTE_A] = 'class="on"';
	}

	public function init() {
		$userinfo = $this -> get_userinfo(); //获取用户信息
		$issuperarr = array(0 => '信息管理员', 1 => '超级管理员');
		include $this -> admin_tpl('userset');
	}

	public function pwset() {
		if (isset($_POST['dosubmit'])) {
			$password = isset($_POST['password']) && trim($_POST['password']) ? safe_replace(trim($_POST['password'])) : showmessage('请输入原密码！', HTTP_REFERER);
			$newpassword = isset($_POST['newpassword']) && trim($_POST['newpassword']) ? safe_replace(trim($_POST['newpassword'])) : showmessage('请输入新密码！', HTTP_REFERER);
			$newpassword2 = isset($_POST['newpassword2']) && trim($_POST['newpassword2']) ? safe_replace(trim($_POST['newpassword2'])) : '';
			if (strlen($newpassword) > 20 || strlen($newpassword) < 6) {
				showmessage('密码长度为6-20字符！', HTTP_REFERER);
			} elseif ($newpassword != $newpassword2) {
				showmessage('两次输入的新密码不一致！', HTTP_REFERER);
			}
			$info = $this -> get_userinfo();
			if (md5(md5($password) . $info['encrypt']) != $info['password']) {
				showmessage('旧密码验证错误！', HTTP_REFERER);
			}
			list($password, $encrypt) = creat_password($newpassword);
			if ($this -> db -> update(array('password' => $password, 'encrypt' => $encrypt), array('id' => $info['id']))) {
				$this -> log_out(); //退出当前登录
				showmessage('修改成功，请重新登录！', HTTP_REFERER);
			} else {
				showmessage('修改失败！', HTTP_REFERER);
			}
		}
		include $this -> admin_tpl('userset');
	}
}
?>