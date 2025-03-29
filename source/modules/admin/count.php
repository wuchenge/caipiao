<?php
defined('IN_MYWEB') or exit('No permission resources.');
base :: load_app_class('admin', 'admin', 0);
class user extends admin {
	private $db, $lock, $daili;

	public function __construct() {
		parent :: __construct();
		$this -> db = base :: load_model('user_model');
		$this -> db2 = base :: load_model('order_model');
		$this -> lock = array(0 => '否', 1 => '<span style="color: #F00;">是</span>');
		$this -> daili = array(0 => '否', 1 => '<span style="color: #F00;">一级</span>', 2 => '<span style="color: #F00;">二级</span>', 3 => '<span style="color: #F00;">二级(阅)</span>');
	}

	public function init() {
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$infos = $this -> db -> listinfo('', 'uid DESC', $page, 15);
		$pages = $this -> db -> pages;
		base :: load_sys_class('format', '', 0);
		base :: load_sys_class('form');
		include $this -> admin_tpl('user_list');
	}

	public function search() { // 搜索
		$where = "";
		if (is_array($_GET['search'])) extract($_GET['search']);
		$search_uid = intval($uid);
		$search_username = safe_replace($username);
		$search_agent = intval($agent);
		$search_aid = intval($aid);
		$search_start_time = $start_time;
		$search_end_time = $end_time;
		if ($search_uid) $where .= $where ? " AND uid = '$search_uid'" : "uid = '$search_uid'";
		if ($search_username) $where .= $where ? " AND (username like '%$search_username%' OR nickname like '%$search_username%')" : "(username like '%$search_username%' OR nickname like '%$search_username%')";
		if ($search_agent) $where .= $where ? " AND agent = '$search_agent'" : "agent = '$search_agent'";
		if ($search_aid) {
			$search_aid_db = $search_aid;
			if ($search_aid_db == 4) $search_aid_db = 0;
			$where .= $where ? " AND aid = '$search_aid_db'" : "aid = '$search_aid_db'";
		}
		if ($search_start_time) {
			$time_start = strtotime($search_start_time);
			$where .= $where ? " AND regtime >= '$time_start'" : "regtime >= '$time_start'";
		}
		if ($search_end_time) {
			$time_end = strtotime($search_end_time);
			$where .= $where ? " AND regtime <= '$time_end'" : "regtime <= '$time_end'";
		}
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$infos = $this -> db -> listinfo($where, 'regtime DESC', $page, 20);
		$pages = $this -> db -> pages;
		base :: load_sys_class('format', '', 0);
		base :: load_sys_class('form');
		include $this -> admin_tpl('user_list');
	}

	public function add() {
		if (isset($_POST['dosubmit'])) {
			$username = isset($_POST['username']) && trim($_POST['username']) ? safe_replace(trim($_POST['username'])) : showmessage('请输入用户名！', HTTP_REFERER);
			$pwd = trim($_POST['password']);
			if (empty($pwd)) $pwd = '123456';
			if (strlen($pwd) > 20 || strlen($pwd) < 6) {
				showmessage('密码限制为6-20个字符！', HTTP_REFERER);
			}
			$aid = intval($_POST['aid']);
			$agent = intval($_POST['agent']);
			if (($aid == 2 || $aid == 3) && $agent < 1) {
				showmessage('为二级代理时，上级代理人UID必填！', HTTP_REFERER);
			}
			if ($aid != 1) {
				$insert['agent'] = $agent;
			}
			if ($aid == 0 && $agent > 0) {//添加普通账户并且填写了上级代理
				$agent_db = $this -> db -> get_one(array('uid' => $agent));
				if ($agent_db['aid'] == 3) {//检查上级为二级代理(阅)
					$insert['agents'] = $agent_db['agent'];
				}
			}
			list($password, $encrypt) = creat_password($pwd);
			$insert['username'] = $username;
			$insert['password'] = $password;
			$insert['encrypt'] = $encrypt;
			$insert['aid'] = $aid;
			$insert['lock'] = intval($_POST['lock']);
			$insert['regtime'] = SYS_TIME;
			$insert['is_robot'] = $_POST['is_robot'];
			if ($this -> db -> insert($insert)) {
				showmessage('添加成功！', 'c=user&a=init');
			} else {
				showmessage('操作失败！', HTTP_REFERER);
			}
		}
		include $this -> admin_tpl('user_add');
	}

	public function edit() {
		$uid = isset($_GET['uid']) && $_GET['uid'] ? intval($_GET['uid']) : showmessage('参数错误！', HTTP_REFERER);
		$data = $this -> db -> get_one(array('uid' => $uid));
		if ($data) {
			if (isset($_POST['dosubmit'])) {
				$pwd = trim($_POST['password']);
				if ($pwd) {
					if (strlen($pwd) > 20 || strlen($pwd) < 6) {
						showmessage('密码限制为6-20个字符！', HTTP_REFERER);
					}
					list($password, $encrypt) = creat_password($pwd);
					$update['password'] = $password;
					$update['encrypt'] = $encrypt;
				}
				$aid = intval($_POST['aid']);
				$agent = intval($_POST['agent']);
				if (($aid == 2 || $aid == 3) && $agent < 1) {
					showmessage('为二级代理时，上级代理人UID必填！', HTTP_REFERER);
				}
				if ($aid != 1) {
					$update['agent'] = $agent;
				} else {
					$update['agent'] = 0;
				}
				if ($aid == 0 && $agent > 0) {//添加普通账户并且填写了上级代理
					$agent_db = $this -> db -> get_one(array('uid' => $agent));
					if ($agent_db['aid'] == 3) {//检查上级为二级代理(阅)
						$update['agents'] = $agent_db['agent'];
					}
				}
				$update['aid'] = $aid;
				$update['lock'] = intval($_POST['lock']);
				$update['nickname'] = safe_replace(trim($_POST['nickname']));
				$update['email'] = safe_replace(trim($_POST['email']));
				$update['qq'] = safe_replace(trim($_POST['qq']));
				$update['mobile'] = safe_replace(trim($_POST['mobile']));
				$update['name'] = safe_replace(trim($_POST['name']));
				$update['bank'] = safe_replace(trim($_POST['bank']));
				$update['card'] = safe_replace(trim($_POST['card']));
				$update['weixin'] = safe_replace(trim($_POST['weixin']));
				$update['alipay'] = safe_replace(trim($_POST['alipay']));
				$update['send_money'] = safe_replace(trim($_POST['send_money']));
				$update['is_robot'] = safe_replace(trim($_POST['is_robot']));
				if ($this -> db -> update($update, array('uid' => $uid))) {
					showmessage('修改成功！', 'c=user&a=init');
				} else {
					showmessage('修改失败！', HTTP_REFERER);
				}
			}
			include $this -> admin_tpl('user_edit');
		} else {
			showmessage('未找到对应数据！', HTTP_REFERER);
		}
	}

	public function info() {
		$uid = intval($_GET['uid']);
		if (!$uid) {
			showmessage('参数错误！', HTTP_REFERER);
		}
		base :: load_sys_class('format', '', 0);
		base :: load_sys_class('form', '', 0);
		$data = $this -> db -> get_one(array('uid' => $uid));
		if ($data) {
			$settingarr = $this -> get_settings(); //读取系统设置
			$gameid = intval($_GET['gameid']);
			$where_gameid = "";
			if ($gameid) {
				$where_gameid = " AND gameid = '$gameid'";
			}
			if (isset($_GET['dosubmit']) && (trim($_GET['start_time']) || trim($_GET['end_time']))) {
				$start_time = trim($_GET['start_time']);
				$end_time = trim($_GET['end_time']);
				$custom_where = "tui = 0$where_gameid";
				if ($start_time) {
					$time_start = strtotime($start_time);
					$custom_where .= " AND addtime >= '$time_start'";
				}
				if ($end_time) {
					$time_end = strtotime($end_time);
					$custom_where .= " AND addtime <= '$time_end'";
				}
				$where = "$custom_where AND uid = '$uid'";
				//自定义时间统计
				$custom_count = $this -> go_order_count($where);
				if ($data['aid']) {//如果是代理 统计该代理旗下用户数据
					$daili_user_count = $this -> db -> count("agent = '$uid' OR agents = '$uid'");
					$daili_order_count = $this -> db2 -> count("agent = '$uid' OR agents = '$uid'");
					$where = "$custom_where AND (agent = '$uid' OR agents = '$uid')";
					$daili_custom_count = $this -> go_order_count($where);
				}
				$custom = true;
			} else {
				$where = " AND uid = '$uid'$where_gameid";
				//今日统计
				$starttime = strtotime(date('Y-m-d'));//今日0点
				$today_where = "tui = 0 AND addtime >= '$starttime'$where";
				$today_count = $this -> go_order_count($today_where);
				//昨日统计
				$starttime = strtotime(date('Y-m-d')) - 86400;//昨日0点
				$endtime = strtotime(date('Y-m-d'));//今日0点
				$yesterday_where = "tui = 0 AND addtime >= '$starttime' AND addtime < '$endtime'$where";
				$yesterday_count = $this -> go_order_count($yesterday_where);
				//本周统计
				//$starttime = mktime(0, 0 , 0, date('m'), date('d')-date('w')+1, date('Y'));//本周开始时间
				$starttime = strtotime(date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600)));//本周开始时间
				$tswk_where = "tui = 0 AND addtime >= '$starttime'$where";
				$tswk_count = $this -> go_order_count($tswk_where);
				//本月统计
				$starttime = mktime(0, 0 , 0, date('m'), 1, date('Y'));//本月开始时间
				$thismonth_where = "tui = 0 AND addtime >= '$starttime'$where";
				$thismonth_count = $this -> go_order_count($thismonth_where);
				//上月统计
				$starttime = mktime(0, 0 , 0, date('m')-1, 1, date('Y'));//上月开始时间
				$endtime = mktime(23,59,59,date('m') ,0,date('Y'));//上月结束时间
				$lastmonth_where = "tui = 0 AND addtime >= '$starttime' AND addtime < '$endtime'$where";
				$lastmonth_count = $this -> go_order_count($lastmonth_where);
				//本季度统计
				$season = ceil((date('n'))/3);//当月是第几季度
				$starttime = mktime(0, 0, 0, $season*3-3+1, 1, date('Y'));//本季度开始时间
				$quarter_where = "tui = 0 AND addtime >= '$starttime'$where";
				$quarter_count = $this -> go_order_count($quarter_where);
				if ($data['aid']) {//如果是代理 统计该代理旗下用户数据
					$daili_user_count = $this -> db -> count("agent = '$uid' OR agents = '$uid'");
					$daili_order_count = $this -> db2 -> count("agent = '$uid' OR agents = '$uid'");
					$where = " AND (agent = '$uid' OR agents = '$uid')$where_gameid";
					//今日统计
					$starttime = strtotime(date('Y-m-d'));//今日0点
					$daili_today_where = "tui = 0 AND addtime >= '$starttime'$where";
					$daili_today_count = $this -> go_order_count($daili_today_where);
					//昨日统计
					$starttime = strtotime(date('Y-m-d')) - 86400;//昨日0点
					$endtime = strtotime(date('Y-m-d'));//今日0点
					$daili_yesterday_where = "tui = 0 AND addtime >= '$starttime' AND addtime < '$endtime'$where";
					$daili_yesterday_count = $this -> go_order_count($daili_yesterday_where);
					//本周统计
					//$starttime = mktime(0, 0 , 0, date('m'), date('d')-date('w')+1, date('Y'));//本周开始时间
					$starttime = strtotime(date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600)));//本周开始时间
					$daili_tswk_where = "tui = 0 AND addtime >= '$starttime'$where";
					$daili_tswk_count = $this -> go_order_count($daili_tswk_where);
					//本月统计
					$starttime = mktime(0, 0 , 0, date('m'), 1, date('Y'));//本月开始时间
					$daili_thismonth_where = "tui = 0 AND addtime >= '$starttime'$where";
					$daili_thismonth_count = $this -> go_order_count($daili_thismonth_where);
					//上月统计
					$starttime = mktime(0, 0 , 0, date('m')-1, 1, date('Y'));//上月开始时间
					$endtime = mktime(23,59,59,date('m') ,0,date('Y'));//上月结束时间
					$daili_lastmonth_where = "tui = 0 AND addtime >= '$starttime' AND addtime < '$endtime'$where";
					$daili_lastmonth_count = $this -> go_order_count($daili_lastmonth_where);
					//本季度统计
					$season = ceil((date('n'))/3);//当月是第几季度
					$starttime = mktime(0, 0, 0, $season*3-3+1, 1, date('Y'));//本季度开始时间
					$daili_quarter_where = "tui = 0 AND addtime >= '$starttime'$where";
					$daili_quarter_count = $this -> go_order_count($daili_quarter_where);
				}
			}
			//查询游戏列表
			$gamearr = $this -> gamelist();
			include $this -> admin_tpl('user_info');
		} else {
			showmessage('未找到对应数据！', HTTP_REFERER);
		}
	}

	public function del() {
		$uid = intval($_GET['uid']);
		if (!$uid) {
			echo json_encode(array('run' => 'no', 'msg' => '参数错误！'));
			exit();
		}
		$r = $this -> db -> get_one(array('uid' => $uid));
		if ($r) {
			if ($this -> db -> delete(array('uid' => $uid))) {
				@unlink('./uppic/user/'.$r['pic']);//删除用户图像
				$db2 = base :: load_model('order_model');//注单
				$db3 = base :: load_model('pay_model');//充值
				$db4 = base :: load_model('cash_model');//提现
				$db5 = base :: load_model('account_model');//流水
				$db2 -> delete(array('uid' => $uid));
				$db3 -> delete(array('uid' => $uid));
				$db4 -> delete(array('uid' => $uid));
				$db5 -> delete(array('uid' => $uid));
				echo json_encode(array('run' => 'yes', 'msg' => '删除成功！', 'id' => 'list_' . $uid));
				exit();
			} else {
				echo json_encode(array('run' => 'no', 'msg' => '删除失败！'));
				exit();
			}
		} else {
			echo json_encode(array('run' => 'no', 'msg' => '未找到对应数据！'));
			exit();
		}
	}

	public function ajax_username() {// 检查用户名是否可用
		$username = isset($_POST['param']) && trim($_POST['param']) ? safe_replace(trim($_POST['param'])) : '';
		$oldusername = isset($_POST['oldusername']) && trim($_POST['oldusername']) ? safe_replace(trim($_POST['oldusername'])) : '';
		if (!$username || ($oldusername != $username && $this -> db -> get_one(array('username' => $username)))) {
			$msg['info'] = '该用户名已被注册！';
			$msg['status'] = 'n';
		} else {
			$msg['info'] = '用户名可用！';
			$msg['status'] = 'y';
		}
		echo json_encode($msg);
	}
}
