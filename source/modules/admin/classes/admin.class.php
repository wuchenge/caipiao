<?php
define('IN_ADMIN', true);
class admin {
	private $db, $err_code;

	// 构造函数 $issuper 检查权限
	public function __construct($issuper = 0) {
		$this -> db = base :: load_model('admin_model');
		$this -> check_admin($issuper);
		$this -> setting = $this -> get_settings(); //读取系统设置
	}

	// 权限判断
	public function check_admin($issuper) {
		if (ROUTE_C != 'login') {
			if (!$this -> get_userid() || !$this -> get_username()) { // 未登录
				header('Location: ' . ADMIN_PATH . '&c=login'); //跳转到登录界面
				exit;
			} else {
				//检查登录情况
				$userpassword = get_cookie('userpassword');
				$admin = $this -> db -> get_one(array('id' => intval($this -> get_userid())));
				if ($userpassword != md5($admin['id'] . $admin['password'])) {
					$this -> log_out();
					showmessage('验证错误！');
				}
				if ($issuper) { // 需要检查权限
					$super = $this -> get_userinfo('issuper');
					if ($super < $issuper) { // 普通管理员
						showmessage('权限不足！');
					}
				}
			}
		}
	}
	// 登陆
	public function login($username, $password, $google_secret="", $ga="") {
		$data = $this -> db -> get_one(array('username' => $username));
		
		if ($data) {
			if($data['google_secret']){
				if(!$google_secret){
					$this -> err_code = 3;
			        return false;
				}else{
					$checkResult=$ga->verifyCode($data['google_secret'], $google_secret,2);
					if(!$checkResult){
					    $this -> err_code = 4;
			            return false;
					}
				}
			}
			$password = md5(md5($password) . $data['encrypt']);
			// $password = $data['password'];
			if ($password != $data['password']) {
				$this -> err_code = 2;
				return false;
			} elseif ($password == $data['password']) {
				$this -> db -> update(array('ip' => get_onlineip(), 'lastlogin' => SYS_TIME), array('id' => $data['id']));
				$userpassword = md5($data['id'] . $data['password']);
				set_cookie(array('userid', 'username', 'userpassword'), array($data['id'], $username, $userpassword), 604800);
				return true;
			}
			$this -> err_code = 0;
			return false;
		} else {
			$this -> err_code = 1;
			return false;
		}
	}

	// 退出登陆
	public function log_out() {
		set_cookie('userpassword', '');
		set_cookie('username', '');
		set_cookie('userid', '');
	}

	// 获取当前用户UID
	public function get_userid() {
		return get_cookie('userid');
	}

	// 获取当前用户名
	public function get_username() {
		return get_cookie('username');
	}

	/**
	 * 获取当前用户信息
	 *
	 * @param string $filed 获取指定字段
	 * @param string $enforce 强制更新
	 */
	public function get_userinfo($filed = '', $enforce = 0) {
		static $data;
		if ($data && !$enforce) {
			if ($filed && isset($data[$filed])) {
				return $data[$filed];
			} elseif ($filed && !isset($data[$filed])) {
				return false;
			} else {
				return $data;
			}
		}
		$data = $this -> db -> get_one(array('username' => $this -> get_username(), 'id' => $this -> get_userid()));
		if ($filed && isset($data[$filed])) {
			return $data[$filed];
		} elseif ($filed && !isset($data[$filed])) {
			return false;
		} else {
			return $data;
		}
	}

	/**
	 * 获取指定用户信息
	 *
	 * @param string $filed 获取指定字段
	 * @param string $uid 查询的用户ID
	 */
	public function get_user($filed = '', $uid) {
		$uiddata = $this -> db -> get_one(array('id' => $uid));
		if ($filed && isset($uiddata[$filed])) {
			return $uiddata[$filed];
		} elseif ($filed && !isset($uiddata[$filed])) {
			return false;
		} else {
			return $uiddata;
		}
	}

	/**
	 * 获取指定会员信息
	 *
	 * @param string $filed 获取指定字段
	 * @param string $uid 查询的用户ID
	 */
	public function go_user($uid, $filed = 'username') {
		if (!$uid) return '--';
		$udb = base :: load_model('user_model');
		$uiddata = $udb -> get_one(array('uid' => $uid));
		if ($filed && isset($uiddata[$filed])) {
			return $uiddata[$filed].($filed == 'username' ? '('.$uid.')' : '');
		} elseif ($filed && !isset($uiddata[$filed])) {
			return false;
		} else {
			return $uiddata;
		}
	}

	// 获取系统设置信息
	public function get_settings($filed = '') {
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

	// 统计语句
	public function go_order_count($where) {
		$db = base :: load_model('order_model');
		$count = $db -> query("SELECT COUNT(*) AS num, SUM(money) AS money, SUM(CASE WHEN account <> money THEN money END) AS moneys, SUM(CASE WHEN account > 0 THEN (account - money) ELSE account END) AS account, SUM(CASE WHEN account > money THEN money END) AS take FROM #@__order WHERE $where ORDER BY id DESC", true);
		return $count;
	}

	public function go_profit_count($where) {
		$db = base :: load_model('pay_model');
		$count['pay'] = $db -> query("SELECT COUNT(*) AS num, SUM(p.`money`) AS money FROM bc_user as u left join bc_account as p on u.uid=p.uid WHERE $where AND u.is_robot = 0 AND p.`type`=0 ORDER BY id DESC", true);
		$db = base :: load_model('cash_model');
    	$count['cash'] = $db -> query("SELECT COUNT(*) AS num, SUM(p.`money`) AS money FROM bc_user as u left join bc_account as p on u.uid=p.uid WHERE $where AND u.is_robot = 0 AND p.`type`=1 ORDER BY id DESC", true);
    	// var_dump($count);exit;
    	// echo "SELECT COUNT(*) AS num, SUM(p.`money`) AS money FROM bc_user as u left join bc_account as p on u.uid=p.uid WHERE $where AND u.is_robot = 0 AND p.`type`=1 ORDER BY id DESC";exit;
		return $count;
	}
	public function go_pay_count($where) {
		$db = base :: load_model('pay_model');
		$count = $db -> query("SELECT COUNT(*) AS num, SUM(money) AS money FROM #@__pay WHERE $where ORDER BY id DESC", true);
		return $count;
	}
  public function go_cash_count($where) {
    $db = base :: load_model('cash_model');
    $count = $db -> query("SELECT COUNT(*) AS num, SUM(money) AS money FROM #@__cash WHERE $where ORDER BY id DESC", true);
    return $count;
  }
	// 查询游戏
	public function gamelist() {
		$db = base::load_model('game_model');
		$gamedb = $db -> select('', 'id,name,state', '', 'sort ASC, id DESC');//查询出所有游戏
		foreach ($gamedb as $value) {
			$gamearr[$value['id']] = $value['name'].($value['state'] != 1 ? '[停]' : '');
		}
		return $gamearr;
	}

	// 获取登录错误原因
	public function get_err() {
		$msg = array('-1' => '数据库错误',
			'0' => '未知错误，请重试',
			'1' => '用户名或密码错误',
			'2' => '用户名或密码错误',
			'3' => '请输入Google验证码',
			'4' => 'Google验证码不正确'
			);
		return $msg[$this -> err_code];
	}

	/**
	 * 加载后台模板
	 *
	 * @param string $file 文件名
	 * @param string $m 模型名
	 */
	public static function admin_tpl($file, $m = '') {
		$m = empty($m) ? ROUTE_M : $m;
		if (empty($m)) return false;
		return FILE_PATH . 'modules' . DIRECTORY_SEPARATOR . $m . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $file . '.tpl.php';
	}
}



?>