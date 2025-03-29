<?php
defined('IN_MYWEB') or exit('No permission resources.');
base :: load_app_class('admin', 'admin', 0);

class index extends admin {
	private $db, $db2, $super;

	public function __construct() {
		parent :: __construct();
		$this -> db = base :: load_model('user_model');
		$this -> db2 = base :: load_model('order_model');
		$this -> super = $this -> get_userinfo('issuper');
	}

	public function init() {
		$username = trim($this -> get_username());
		include $this -> admin_tpl('index');
	}

	public function right() {
		base::load_sys_class('form', '', 0);
		$settingarr = $this -> get_settings(); //读取系统设置
		$user_count = $this -> db -> count();
		$order_count = $this -> db2 -> count();
		$gameid = intval($_GET['gameid']);
		$where_gameid = "";
		if ($gameid) {
			$where_gameid = " AND gameid = '$gameid'";
		}
		if ($this -> super) {
			if (isset($_GET['dosubmit']) && (trim($_GET['start_time']) || trim($_GET['end_time']))) {
				$start_time = trim($_GET['start_time']);
				$end_time = trim($_GET['end_time']);
				$where = "tui = 0$where_gameid";
				$profit_where = "";
				if ($start_time) {
					$time_start = strtotime($start_time);
					$where .= " AND addtime >= '$time_start'";
					$profit_where = empty($profit_where)?"addtime >= '$time_start'":$profit_where." AND addtime >= '$time_start'";
				}
				if ($end_time) {
					$time_end = strtotime($end_time);
					$where .= " AND addtime <= '$time_end'";
					$profit_where = empty($profit_where)?"addtime <= '$time_end'":$profit_where." AND addtime <= '$time_end'";
				}
				//自定义时间统计
				$custom_count = $this -> go_order_count($where);
				$custom_num_count = intval($custom_count['num']);
				$custom_money_count = round($custom_count['money'], 2);
	      		$custom_moneys_count = round($custom_count['moneys'], 2);
				$custom_account_count = round($custom_count['account'], 2);
				$custom_take_count = round($custom_count['take'], 2);
				$custom_profit_count = $this -> go_profit_count($profit_where);
				$custom = true;
			} else {
				//今日统计
				$starttime = strtotime(date('Y-m-d'));//今日0点
				$today_where = "tui = 0 AND addtime >= '$starttime'$where_gameid";
				$today_count = $this -> go_order_count($today_where);
				$today_profit_count = $this -> go_profit_count("p.addtime >= '$starttime'$where_gameid");
				//昨日统计
				$starttime = strtotime(date('Y-m-d 00:00:00')) - 86400;//昨日0点
				$endtime = strtotime(date('Y-m-d 00:00:00'));//今日0点
				$yesterday_where = "tui = 0 AND addtime >= '$starttime' AND addtime < '$endtime'$where_gameid";
				$yesterday_count = $this -> go_order_count($yesterday_where);
				$yesterday_profit_count = $this -> go_profit_count("p.addtime >= '$starttime' AND p.addtime < '$endtime'");
				// var_dump($yesterday_profit_count);exit;
				//本周统计
				//$starttime = mktime(0, 0 , 0, date('m'), date('d')-date('w')+1, date('Y'));//本周开始时间
				$starttime = strtotime(date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600)));//本周开始时间
				$tswk_where = "tui = 0 AND addtime >= '$starttime'$where_gameid";
				$tswk_count = $this -> go_order_count($tswk_where);
				$tswk_profit_count = $this -> go_profit_count("p.addtime >= '$starttime'");
				//本月统计
				$starttime = mktime(0, 0 , 0, date('m'), 1, date('Y'));//本月开始时间
				$thismonth_where = "tui = 0 AND addtime >= '$starttime'$where_gameid";
				$thismonth_count = $this -> go_order_count($thismonth_where);
				$thismonth_profit_count = $this -> go_profit_count("p.addtime >= '$starttime'");
				// var_dump($thismonth_profit_count);exit;
				//上月统计
				$starttime = mktime(0, 0 , 0, date('m')-1, 1, date('Y'));//上月开始时间
				$endtime = mktime(23,59,59,date('m') ,0,date('Y'));//上月结束时间
				$lastmonth_where = "tui = 0 AND addtime >= '$starttime' AND addtime < '$endtime'$where_gameid";
				$lastmonth_count = $this -> go_order_count($lastmonth_where);
				$lastmonth_profit_count = $this -> go_profit_count("p.addtime >= '$starttime' and p.addtime <= '$endtime'");
				//本季度统计
				$season = ceil((date('n'))/3);//当月是第几季度
				$starttime = mktime(0, 0, 0, $season*3-3+1, 1, date('Y'));//本季度开始时间
				$quarter_where = "tui = 0 AND addtime >= '$starttime'$where_gameid";
				$quarter_count = $this -> go_order_count($quarter_where);
				$quarter_profit_count = $this -> go_profit_count("p.addtime >= '$starttime'");
			}
		}
		//查询游戏列表
		$gamearr = $this -> gamelist();
		include $this -> admin_tpl('right');
	}

	public function ajax_getprompt() {
		$msg = array(
			'pay_ids' => array(),
			'cash_ids' => array(),
		);
		$user_db = base :: load_model('user_model');
		$settingarr = $this -> get_settings(); //读取系统设置
		$blacklist = explode(",",$settingarr['blacklist']);
		$blacklist = "'".implode("','", $blacklist)."'";
		$user_db = $user_db->select("loginip IN({$blacklist})",'uid');
		foreach($user_db as $k => $v) {
			$uids[] = $v['uid'];
		}
		$uids = empty($uids)?"":implode(',', $uids);
		$term = empty($uids)?"":"and uid NOT IN({$uids})";

		$db = base :: load_model('pay_model');
		$paydb = $db -> select("agent=0 and state=0 ".$term, 'id', '', 'id ASC');
		foreach($paydb as $k => $v) {
			$payarr[] = $v['id'];
		}
		$msg['pay_ids'] = empty($payarr)?array():$payarr;

		$db = base :: load_model('cash_model');
		$cashdb = $db -> select(array('agent' => 0, 'state' => 0), 'id', '', 'id ASC');
		foreach($cashdb as $k => $v) {
			$casharr[] = $v['id'];
		}
		$msg['cash_ids'] = empty($casharr)?array():$casharr;
		echo json_encode($msg);
	}

	public function ajax_getpay() {// 检查充值订单
		$user_db = base :: load_model('user_model');
		$settingarr = $this -> get_settings(); //读取系统设置
		$blacklist = explode(",",$settingarr['blacklist']);
		$blacklist = "'".implode("','", $blacklist)."'";
		$user_db = $user_db->select("loginip IN({$blacklist})",'uid');
		foreach($user_db as $k => $v) {
			$uids[] = $v['uid'];
		}
		$uids = implode(',', $uids);

		$db = base :: load_model('pay_model');
		$paydb = $db -> select("agent=0 and state=0 and uid NOT IN({$uids})", 'id', '', 'id ASC');
		foreach($paydb as $k => $v) {
			$payarr[] = $v['id'];
		}
		$msg['ids'] = $payarr ? implode(',', $payarr) : '';
		echo json_encode($msg);
	}

	public function ajax_getcash() {// 检查提现申请
		$db = base :: load_model('cash_model');
		$cashdb = $db -> select(array('agent' => 0, 'state' => 0), 'id', '', 'id ASC');
		foreach($cashdb as $k => $v) {
			$casharr[] = $v['id'];
		}
		$msg['ids'] = $casharr ? implode(',', $casharr) : '';
		echo json_encode($msg);
	}

}

?>