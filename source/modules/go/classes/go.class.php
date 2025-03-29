<?php
class go {

	//private $db;

	public function __construct() {

	}

	// 检查USER登录情况
	//$username 用户名
	//$password 用户密码，提供此参数进行登录
	public function check_user($username = '', $password = '') {
		$db = base :: load_model('user_model');
		if ($username && $password) {//使用 用户名 + 密码验证 登录
			$udb = $db -> get_one(array('username' => safe_replace($username)));
			if ($udb) {//存在这个号码
				$password = md5(md5($password) . $udb['encrypt']);//处理比对前的密码
				if ($password == $udb['password']) {
					if ($udb['lock'] == 1) {//如果被锁定禁止登录
						return false;
					}
					$db -> update(array('loginip' => get_onlineip(), 'logintime' => SYS_TIME), array('uid' => $udb['uid']));
					$pwd = md5($udb['uid'] . $udb['password']);
					set_cookie(array('uid', 'password'), array($udb['uid'], $pwd), 7 * 86400);
					if($udb['freezing'] == 1){
						if($udb['freezing_money'] <= 0){
								$udb['money'] = 0;
						}else{
								$udb['money'] -= $udb['freezing_money'];
						}
					}
					// var_dump($udb);exit;
					return $udb;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {//检查COOKIE登录状态
			$uid = get_cookie('uid');
			$pwd = get_cookie('password');
			if (!$uid || !$pwd) return false;
			$udb = $db -> get_one(array('uid' => intval($uid)));
			if($udb['freezing'] == 1){
					if($udb['freezing_money'] <= 0){
							$udb['money'] = 0;
					}else{
							$udb['money'] -= $udb['freezing_money'];
					}
				}
			if ($udb && $pwd == md5($udb['uid'] . $udb['password'])) {
				if ($udb['lock'] == 1) {//如果被锁定禁止登录
					return false;
				}
				return $udb;
			} else {
				return false;
			}
		}
	}

	// 检查验证码
	public function check_code($code) {
		if (!$code) return false;
		if (get_cookie('code') == strtolower($code)) {
			return true;
		} else {
			return false;
		}
	}

	// 存入登录COOKIE
	public function set_pwcode($username, $pwcode) {
		set_cookie('pwcode', md5($username . $pwcode));
	}

	// 获取系统设置信息
	public function get_settings($filed = '') {
		$iscache = base :: load_config('system', 'iscache'); //是否开启设置缓存
		if ($iscache) {
			$settingdata = base :: load_config('setting');
			if ($filed) {
				return $settingdata[$filed];
			} else {
				return $settingdata;
			}
		} else {
			$setdb = base :: load_model('settings_model');
			if ($filed) {
				$settingdata = $setdb -> get_one(array('name' => $filed));
				return $settingdata['data'];
			} else {
				$settingdata = $setdb -> select();
				foreach($settingdata as $k => $v) {
					$settingarr[$v['name']] = $v['data'];
				}
				return $settingarr;
			}
		}
	}
}
