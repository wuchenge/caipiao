<?php
defined('IN_MYWEB') or exit('No permission resources.');
base :: load_app_class('daili', 'daili', 0);
class order extends daili{

	private $db, $uid, $username, $tuiarr;

	public function __construct() {
		parent::__construct();
		$this->db = base::load_model('order_model');
		$this -> uid = intval($this -> get_userid());
		$this -> username = trim($this -> get_username());
		$this->tuiarr = array(1 => '<span style="color: #0000ff;">已退单</span>', 2 => '<span style="color: #ff6600;">无效单</span>', 3 => '<span style="color: #F00;">违规单</span>');
	}

	public function init() {
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $this->db->listinfo("agent = '$this->uid' OR agents = '$this->uid'", 'id DESC', $page, 15);
		$pages = $this->db->pages;
		base::load_sys_class('format', '', 0);
		base::load_sys_class('form', '', 0);
		//查询游戏列表
		$gamearr = $this -> gamelist();
		include $this->daili_tpl('order_list');
	}

	public function search() {
 		$where = "(agent = '$this->uid' OR agents = '$this->uid')";
		if(is_array($_GET['search'])) extract($_GET['search']);
		$search_uid = intval($uid);
		if ($search_uid) {
			$agent = $this -> check_agent($search_uid);
			if (!$agent) {
				showmessage('无权操作该账户！');
			}
			//上级代理属于操作人 重置搜索条件
			$where = "uid='$search_uid'";
		}
		$search_orderid = safe_replace($orderid);
		$search_gameid = intval($gameid);
		$search_qishu = safe_replace($qishu);
		$search_state = intval($state);
		$search_start_time = $start_time;
		$search_end_time = $end_time;
		$stateoption[$search_state] = 'selected="selected"';
		if($search_orderid) $where .= " AND orderid like '%$search_orderid%'";
		if($search_gameid) $where .= " AND gameid='$search_gameid'";
		if($search_qishu) $where .= " AND qishu='$search_qishu'";
		if($search_state){
			if($search_state == 4) {
				$where .= " AND account=0 AND tui=0";
			} elseif($search_state == 1) {
				$where .= " AND account>0";
			} elseif($search_state == 2) {
				$where .= " AND account<0";
			} elseif($search_state == 3) {
				$where .= " AND tui=1";
			}
		}
		if ($search_start_time) {
			$time_start = strtotime($search_start_time);
			$where .= " AND addtime >= '$time_start'";
		}
		if ($search_end_time) {
			$time_end = strtotime($search_end_time);
			$where .= " AND addtime <= '$time_end'";
		}
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $this->db->listinfo($where, 'id DESC', $page, 15);
 		$pages = $this->db->pages;
		base::load_sys_class('format', '', 0);
		base::load_sys_class('form', '', 0);
		//查询游戏列表
		$gamearr = $this -> gamelist();
 		include $this->daili_tpl('order_list');
	}

	public function go_gamename($gameid) {// 返回游戏名称
		$db = base::load_model('game_model');
		$game = $db -> get_one(array('id' => $gameid));
		echo $game['name'];
	}

}