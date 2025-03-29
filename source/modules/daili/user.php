<?php
defined('IN_MYWEB') or exit('No permission resources.');
base :: load_app_class('daili', 'daili', 0);
class user extends daili {
	private $db, $db2, $db_account, $aid, $lock, $daili, $uid, $username;

	public function __construct() {
		parent :: __construct();
		$this -> db = base :: load_model('user_model');
		$this -> db2 = base :: load_model('order_model');
		$this -> db_account = base :: load_model('account_model');
		$this -> lock = array(0 => '否', 1 => '<span style="color: #F00;">是</span>');
		$this -> daili = array(0 => '否', 1 => '<span style="color: #F00;">一级</span>', 2 => '<span style="color: #F00;">二级</span>', 3 => '<span style="color: #F00;">二级(阅)</span>');
		$this -> aid = $this -> get_userinfo('aid');
		$this -> uid = intval($this -> get_userid());
		$this -> username = trim($this -> get_username());
	}



	public function init() {
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$infos = $this -> db -> listinfo("agent = '$this->uid' OR agents = '$this->uid'", 'uid DESC', $page, 15);
		$pages = $this -> db -> pages;
		base :: load_sys_class('format', '', 0);
		base :: load_sys_class('form');
		include $this -> daili_tpl('user_list');
	}

	public function search() { // 搜索
		$where = "(agent = '$this->uid' OR agents = '$this->uid')";
		if (is_array($_GET['search'])) extract($_GET['search']);
		$search_agent = intval($agent);
		if ($search_agent) {
			$agent_d = $this -> check_agent($search_agent);
			if (!$agent_d) {
				showmessage('无权操作该账户！');
			} else {
				//上级代理属于操作人 重置搜索条件
				$where = "agent = '$search_agent'";
			}
		}
		$search_uid = intval($uid);
		$search_username = safe_replace($username);
		$search_aid = intval($aid);
		$search_start_time = $start_time;
		$search_end_time = $end_time;
		if ($search_uid) $where .= $where ? " AND uid = '$search_uid'" : "uid = '$search_uid'";
		if ($search_username) $where .= $where ? " AND (username like '%$search_username%' OR nickname like '%$search_username%')" : "(username like '%$search_username%' OR nickname like '%$search_username%')";
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
		include $this -> daili_tpl('user_list');
	}

	public function add() {
		$this -> check_aid(2);
		if (isset($_POST['dosubmit'])) {
			$username = isset($_POST['username']) && trim($_POST['username']) ? safe_replace(trim($_POST['username'])) : showmessage('请输入用户名！', HTTP_REFERER);
			$pwd = trim($_POST['password']);
			if (empty($pwd)) $pwd = '123456';
			if (strlen($pwd) > 20 || strlen($pwd) < 6) {
				showmessage('密码限制为6-20个字符！', HTTP_REFERER);
			}
			$aid = intval($_POST['aid']);
			if (($aid == 2 || $aid == 3) && $this -> aid == 2) {
				$aid = 0;
			}
			$insert['agent'] = $this -> uid;
			list($password, $encrypt) = creat_password($pwd);
			$insert['username'] = $username;
			$insert['password'] = $password;
			$insert['encrypt'] = $encrypt;
			$insert['aid'] = $aid;
			$insert['lock'] = intval($_POST['lock']);
			$insert['regtime'] = SYS_TIME;
			if ($this -> db -> insert($insert)) {
				showmessage('添加成功！', 'c=user&a=init');
			} else {
				showmessage('操作失败！', HTTP_REFERER);
			}
		}
		include $this -> daili_tpl('user_add');
	}

	public function edit() {
		$this -> check_aid(2);
		$uid = isset($_GET['uid']) && $_GET['uid'] ? intval($_GET['uid']) : showmessage('参数错误！', HTTP_REFERER);
		$data = $this -> db -> get_one(array('uid' => $uid));
		$agent = $this -> check_agent($data);
		if (!$agent) {
			showmessage('无权操作该账户！');
		}
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
				$update['lock'] = intval($_POST['lock']);
				if ($this -> db -> update($update, array('uid' => $uid))) {
					showmessage('修改成功！', 'c=user&a=init');
				} else {
					showmessage('修改失败！', HTTP_REFERER);
				}
			}
			include $this -> daili_tpl('user_edit');
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
		$agent = $this -> check_agent($data);
		if (!$agent) {
			showmessage('无权操作该账户！');
		}
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
					$daili_user_count = $this -> db -> count(array('agent' => $data['uid']));
					$daili_order_count = $this -> db2 -> count(array('agent' => $data['uid']));
					$where = "$custom_where AND agent = '$data[uid]'";
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
					$daili_user_count = $this -> db -> count(array('agent' => $data['uid']));
					$daili_order_count = $this -> db2 -> count(array('agent' => $data['uid']));
					$where = " AND agent = '$data[uid]'$where_gameid";
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
			include $this -> daili_tpl('user_info');
		} else {
			showmessage('未找到对应数据！', HTTP_REFERER);
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

	public function account(){
		$search = $_GET['search'];
		$term = "";
		if($search){
			if(!empty($search['uid'])){
				$term .= $term?" AND `uid` = ".$search['uid']:"`uid` = ".$search['uid'];
				$search_uid = $search['uid'];
			}
			if(!empty($search['type'])){
				if($search['type'] == 1){
					$term .= $term?" AND `aid` = 0":"`aid` = 0";
				}
				if($search['type'] == 2){
					$term .= $term?" AND `aid` > 0":"`aid` > 0";
				}
				
				$search_type = $search['type'];
			}
			if(!empty($search['start_time'])){
				$start_time = $search['start_time'];
			}else{
				$start_time = date("Y-m-d",strtotime(date('Y-m-d 00:00:00')."-1 day"));
			}
			if(!empty($search['end_time'])){
				$end_time = $search['end_time'];
			}else{
				$end_time = date("Y-m-d");
			}
		}
		
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$infos = $this->db->listinfo("agent = '$this->uid' OR agents = '$this->uid'".$term, 'uid DESC', $page, 15);
		$pages = $this->db->pages;

		$infos = $this->user_info($infos,$search);
		// echo 11;exit;
		include $this->daili_tpl('account_user');
	}

	public function user_info($infos,$search){
		if(!empty($search['start_time'])){
			$start_time = $search['start_time'];
		}else{
			$start_time = date("Y-m-d",strtotime(date('Y-m-d 00:00:00')."-1 day"));
		}
		if(!empty($search['end_time'])){
			$end_time = $search['end_time'];
		}else{
			$end_time = date("Y-m-d");
		}
		$start = strtotime($start_time);
		$end = strtotime($end_time);


		foreach ($infos as &$val) {
			$data = array();
			if($val['aid'] == 0){

				$ret = $this->db_account->querys("SELECT SUM(`money`) as money , type  FROM bc_account where `addtime` > {$start} AND `addtime` < {$end} AND type IN(0,1) AND `uid`={$val['uid']} GROUP BY type",1);
				
				foreach ($ret as $vale) $data[$vale['type']] = $vale['money'];
				$val['recharge'] = empty($data[0])?'0.00':$data[0];
				$val['withdraw'] = empty($data[1])?'0.00':$data[1];
			}else{
				$child = $this->get_user_children($val['uid']);
				$child = implode(',', $child);
				$ret = $this->db_account->querys("SELECT SUM(`money`) as money , type  FROM bc_account where `addtime` > {$start} AND `addtime` < {$end} AND type IN(0,1) AND `uid` IN({$child}) GROUP BY type",1);
				foreach ($ret as $vale) $data[$vale['type']] = $vale['money'];
				$val['recharge'] = empty($data[0])?'0.00':$data[0];
				$val['withdraw'] = empty($data[1])?'0.00':$data[1];
			}
		}
		
		return $infos;
	}

	public function get_user_children($uid){
		$childs[] = $uid;
		$child = $this->db->select("agent={$uid}",'uid');
		if(!empty($child)){
			foreach ($child as $val) {
				$childs[] = $val['uid'];
				// return $this->get_user_children($val['uid'],$childs);
				$child2 = $this->db2->select("agent={$val['uid']}",'uid');
				if(!empty($child2)){
					foreach ($child2 as $value) {
						$childs[] = $value['uid'];
					}
				}
				
			}
		}
		return $childs;
	}

	public function count_info() {
		$uid = intval($_GET['uid']);
		if (!$uid) {
			showmessage('参数错误！', HTTP_REFERER);
		}
		$data = $this -> db -> get_one(array('uid' => $uid));
		// var_dump($data);exit;
		if ($data) {
			//
			$order_count = $this->db->query("select SUM(`money`) as money,SUM(`account`) as account from `bc_order` where `uid` = {$uid}",true);

			$pay_count = $this->db->query("select SUM(`money`) as money from `bc_pay` where `uid` = {$uid} and `state`=1",true);

			$cash_count = $this->db->query("select SUM(`money`) as money from `bc_cash` where `uid` = {$uid} and `state`=2",true);

			$day_start = strtotime(date("Y-m-d 00:00:00"));
			$day_end = strtotime(date("Y-m-d 23:59:59"));
			$day_order_count = $this->db->query("select SUM(`money`) as money,SUM(`account`) as account from `bc_order` where `uid` = {$uid} and `addtime`>={$day_start} and `addtime`<={$day_end}",true);


			//当前日期 
			$sdefaultDate = date("Y-m-d"); 
			//$first =1 表示每周星期一为开始日期 0表示每周日为开始日期 
			$first=1; 
			//获取当前周的第几天 周日是 0 周一到周六是 1 - 6 
			$w=date('w',strtotime($sdefaultDate)); 
			//获取本周开始日期，如果$w是0，则表示周日，减去 6 天 
			$week_start=date('Y-m-d',strtotime("$sdefaultDate -".($w ? $w - $first : 6).' days')); 
			$beginThisweek = strtotime($week_start);
			// var_dump($beginThisweek);exit;
			$endThisweek=time();  
			$week_order_count = $this->db->query("select SUM(`money`) as money,SUM(`account`) as account from `bc_order` where `uid` = {$uid} and `addtime`>={$beginThisweek} and `addtime`<={$endThisweek}",true);

			//查询游戏列表
			$gamearr = $this -> gamelist();
			include $this -> daili_tpl('user_count');
		}else{
			showmessage('未找到对应数据！', HTTP_REFERER);
		}
	}

	public function count_pay() {
		$uid = intval($_GET['uid']);
		if (!$uid) {
			showmessage('参数错误！', HTTP_REFERER);
		}
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;

		$db = base::load_model('pay_model');
		$list = $db->listinfo('uid='.$uid, 'id DESC', $page, 5);
		$pages = $db->pages;
		base::load_sys_class('format', '', 0);
		include $this->daili_tpl('count_pay_list');
	}
	public function count_cash() {
		$uid = intval($_GET['uid']);
		if (!$uid) {
			showmessage('参数错误！', HTTP_REFERER);
		}
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$db = base::load_model('cash_model');
		$list = $db->listinfo('uid='.$uid, 'id DESC', $page, 5);
		$pages = $db->pages;
		base::load_sys_class('format', '', 0);
		include $this->daili_tpl('count_cash_list');
	}

	public function count_order() {
		$uid = intval($_GET['uid']);
		if (!$uid) {
			showmessage('参数错误！', HTTP_REFERER);
		}
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$db = base::load_model('cash_model');
		$list = $db->listinfo('uid='.$uid, 'id DESC', $page, 5);
		$pages = $db->pages;
		base::load_sys_class('format', '', 0);
		base::load_sys_class('form', '', 0);
		//查询游戏列表
		$gamearr = $this -> gamelist();
		include $this->daili_tpl('count_order_list');
	}

	public function go_gamename($gameid) {// 返回游戏名称
		$db = base::load_model('game_model');
		$game = $db -> get_one(array('id' => $gameid));
		echo $game['name'];
	}


}
