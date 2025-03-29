<?php
define('IN_DAILI', true);
class daili {
	private $db, $err_code;

	//构造函数 $aid 检查代理权限
	public function __construct($aid = 0) {
		$this -> db = base :: load_model('user_model');
		$this -> check_daili($aid);
	}

	// 登录权限判断
	public function check_daili($aid) {
		if (ROUTE_C != 'login') {
			if (!$this -> get_userid() || !$this -> get_username()) { // 未登录
				header('Location: ' . DAILI_PATH . '&c=login'); //跳转到登录界面
				exit;
			} else {
				//检查登录情况
				$apassword = get_cookie('apassword');
				$user = $this -> db -> get_one(array('uid' => intval($this -> get_userid())));
				if ($apassword != md5($user['uid'] . $user['password'])) {
					$this -> log_out();
					showmessage('验证错误！');
				}
				if ($aid) { // 需要检查权限
					$this -> check_aid($aid);
				}
			}
		}
	}

	// 登录权限判断
	public function check_aid($aid) {
		$_aid = $this -> get_userinfo('aid');
		if ($_aid > $aid) {
			showmessage('代理权限不足！');
		}
	}

	// 检查是否属于旗下代理账户 并返回上级操作代理
	//$data 传入用户数组或UID
	public function check_agent($data) {
		if (!$data) return false;
		if (!is_array($data)) $data = $this -> db -> get_one(array('uid' => $data));
		$uid = intval($this -> get_userid());
		$agent = $this -> db -> get_one(array('uid' => $uid));
		if ($data['agent'] != $uid) {//如果不是直接代理关系
			if ($agent['aid'] == 1) {//如果操作人是一级代理
				$agent_new = $this -> db -> get_one("aid > 1 AND uid = '$data[agent]' AND agent = '$uid'");
				if ($agent_new['aid'] == 3) {//上级为二级代理(阅) 则返回一级代理
					return $agent;
				} else {
					return $agent_new;
				}
			}
			return false;
		} else {
			return $agent;
		}
	}

	// 登陆
	public function login($username, $password) {
		$data = $this -> db -> get_one("username = '$username' AND aid > 0");
		if ($data) {
			$password = md5(md5($password) . $data['encrypt']);
			if ($password != $data['password']) {
				$this -> err_code = 2;
				return false;
			} elseif ($data['lock'] == 1) {//如果被锁定禁止登录
				$this -> err_code = 3;
				return false;
			} elseif ($password == $data['password']) {
				$this -> db -> update(array('loginip' => get_onlineip(), 'logintime' => SYS_TIME), array('uid' => $data['uid']));
				$apassword = md5($data['uid'] . $data['password']);
				set_cookie(array('aid', 'aname', 'apassword'), array($data['uid'], $username, $apassword), 604800);
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
		set_cookie('apassword', '');
		set_cookie('aname', '');
		set_cookie('aid', '');
	}

	// 获取当前用户UID
	public function get_userid() {
		return get_cookie('aid');
	}

	// 获取当前用户名
	public function get_username() {
		return get_cookie('aname');
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
		$data = $this -> db -> get_one(array('username' => $this -> get_username(), 'uid' => $this -> get_userid()));
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
		$uiddata = $this -> db -> get_one(array('uid' => $uid));
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
		$uiddata = $this -> db -> get_one(array('uid' => $uid));
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

	public function go_order_count($where) {// 统计语句
		$db = base :: load_model('order_model');
		$count = $db -> query("SELECT COUNT(*) AS num, SUM(money) AS money, SUM(CASE WHEN account <> money THEN money END) AS moneys, SUM(CASE WHEN account > 0 THEN (account - money) ELSE account END) AS account, SUM(CASE WHEN account > money THEN money END) AS take FROM #@__order WHERE $where ORDER BY id DESC", true);
		return $count;
	}

	public function gamelist() {// 查询游戏
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
			'3' => '账户被禁',
			);
		return $msg[$this -> err_code];
	}

	/**
	 * 加载代理模板
	 *
	 * @param string $file 文件名
	 * @param string $m 模型名
	 */
	public static function daili_tpl($file, $m = '') {
		$m = empty($m) ? ROUTE_M : $m;
		if (empty($m)) return false;
		return FILE_PATH . 'modules' . DIRECTORY_SEPARATOR . $m . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $file . '.tpl.php';
	}
}
?>