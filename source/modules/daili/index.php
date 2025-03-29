<?php
defined('IN_MYWEB') or exit('No permission resources.');
base :: load_app_class('daili', 'daili', 0);

class index extends daili {
	private $db, $db2, $aid, $uid, $username;

	public function __construct() {
		parent :: __construct();
		$this -> db = base :: load_model('user_model');
		$this -> db2 = base :: load_model('order_model');
		$this -> aid = $this -> get_userinfo('aid');
		$this -> uid = intval($this -> get_userid());
		$this -> username = trim($this -> get_username());
	}

	public function init() {
		include $this -> daili_tpl('index');
	}

	public function right() {
		base::load_sys_class('form', '', 0);
		$settingarr = $this -> get_settings(); //读取系统设置
		$daili_user_count = $this -> db -> count("agent = '$this->uid' OR agents = '$this->uid'");
		$daili_order_count = $this -> db2 -> count("agent = '$this->uid' OR agents = '$this->uid'");
		$gameid = intval($_GET['gameid']);
		$where_gameid = "";
		if ($gameid) {
			$where_gameid = " AND gameid = '$gameid'";
		}
		if (isset($_GET['dosubmit']) && (trim($_GET['start_time']) || trim($_GET['end_time']))) {
			$start_time = trim($_GET['start_time']);
			$end_time = trim($_GET['end_time']);
			$where = "tui = 0 AND (agent = '$this->uid' OR agents = '$this->uid')$where_gameid";
			if ($start_time) {
				$time_start = strtotime($start_time);
				$where .= " AND addtime >= '$time_start'";
			}
			if ($end_time) {
				$time_end = strtotime($end_time);
				$where .= " AND addtime <= '$time_end'";
			}
			//自定义时间统计
			$custom_count = $this -> go_order_count($where);
			$custom = true;
		} else {
			$where = " AND (agent = '$this->uid' OR agents = '$this->uid')$where_gameid";
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
		//查询游戏列表
		$gamearr = $this -> gamelist();
		include $this -> daili_tpl('right');
	}

	public function ajax_getpay() {// 检查充值订单
		$db = base :: load_model('pay_model');
		$paydb = $db -> select("(agent = '$this->uid' OR agents = '$this->uid') AND state = 0", 'id', '', 'id ASC');
		foreach($paydb as $k => $v) {
			$payarr[] = $v['id'];
		}
		$msg['ids'] = $payarr ? implode(',', $payarr) : '';
		echo json_encode($msg);
	}

	public function ajax_getcash() {// 检查提现申请
		$db = base :: load_model('cash_model');
		$cashdb = $db -> select("(agent = '$this->uid' OR agents = '$this->uid') AND state = 0", 'id', '', 'id ASC');
		foreach($cashdb as $k => $v) {
			$casharr[] = $v['id'];
		}
		$msg['ids'] = $casharr ? implode(',', $casharr) : '';
		echo json_encode($msg);
	}

}

?>