<?php
defined('IN_MYWEB') or exit('No permission resources.');
base :: load_app_class('daili', 'daili', 0);
class userset extends daili {

	private $db, $a_arr, $aid;

	public function __construct() {
		parent :: __construct();
		$this -> db = base :: load_model('user_model');
		$this -> aid = $this -> get_userinfo('aid');
		$this -> a_arr[ROUTE_A] = 'class="on"';
		$this -> agent = array(1 => '一级代理', 2 => '二级代理', 3 => '二级代理(阅)');
	}

	public function init() {
		$user = $this -> get_userinfo();
		base::load_sys_class('format', '', 0);
		include $this -> daili_tpl('userset');
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
			if ($this -> db -> update(array('password' => $password, 'encrypt' => $encrypt), array('uid' => $info['uid']))) {
				$this -> log_out(); //退出当前登录
				showmessage('修改成功，请重新登录！', HTTP_REFERER);
			} else {
				showmessage('修改失败！', HTTP_REFERER);
			}
		}
		include $this -> daili_tpl('userset');
	}

	public function agent() {
		return false;
		$this -> check_aid(2);
		$user = $this -> get_userinfo();
		$config = unserialize($user['agentconfig']);
		if (isset($_POST['dosubmit'])) {
			if (is_array($_POST['config'])) extract($_POST['config']);
			if ($_FILES['wxfile']['size'] || $_FILES['alifile']['size']) {//如果选择了上传图片
				$up = base::load_sys_class('upimg');
				$up->datedir = false;//不要添加日期目录
				$up->dir = 'ewm';
				$up->thumb = 0;
				if ($_FILES['wxfile']['size']) {//微信
					$up->filename = 'wxfile';
					$wxreturn = $up->up();
					if ($wxreturn['state'] == 'success') {
						@unlink('./uppic/ewm/'.$config['wxewm']);//删除原来的图像
						$config['wxewm'] = $wxreturn['info'];
					}
				}
				if ($_FILES['alifile']['size']) {//数字货币
					$up->filename = 'alifile';
					$alireturn = $up->up();
					if ($alireturn['state'] == 'success') {
						@unlink('./uppic/ewm/'.$config['aliewm']);//删除原来的图像
						$config['aliewm'] = $alireturn['info'];
					}
				}
			}
			$config['remark'] = safe_replace($remark);
			$config['ann'] = safe_replace($ann);
			$config['card'] = safe_replace($card);
			//$config['cash'] = safe_replace($cash);
			$config['gameid'] = $gameid;//implode(',', $gameid);
			$agentconfig = new_addslashes(serialize($config));
			if ($this -> db -> update(array('agentconfig' => $agentconfig), array('uid' => $user['uid']))) {
				showmessage('修改成功！', HTTP_REFERER);
			} else {
				showmessage('修改失败！', HTTP_REFERER);
			}
		}
		base :: load_sys_class('form');
		//查询游戏列表
		$gamearr = $this -> gamelist();
		include $this -> daili_tpl('userset');
	}

}
?>