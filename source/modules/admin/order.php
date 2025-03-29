<?php
defined('IN_MYWEB') or exit('No permission resources.');
base::load_app_class('admin','admin',0);
class order extends admin{

	private $db, $tuiarr;

	public function __construct() {
		parent::__construct();
		$this->db = base::load_model('order_model');
		$this->tuiarr = array(1 => '<span style="color: #0000ff;">已退单</span>', 2 => '<span style="color: #ff6600;">无效单</span>', 3 => '<span style="color: #F00;">违规单</span>');
	}

	public function init() {
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $this->db->listinfo('', 'id DESC', $page, 15);
		$pages = $this->db->pages;
		base::load_sys_class('format', '', 0);
		base::load_sys_class('form', '', 0);
		//查询游戏列表
		$gamearr = $this -> gamelist();
		include $this->admin_tpl('order_list');
	}

	public function search() {
 		$where = '';
		if(is_array($_GET['search'])) extract($_GET['search']);
		$search_orderid = safe_replace($orderid);
		$search_gameid = intval($gameid);
		$search_qishu = safe_replace($qishu);
		$search_uid = intval($uid);
		$search_agent = intval($agent);
		$search_state = intval($state);
		$search_start_time = $start_time;
		$search_end_time = $end_time;
		$stateoption[$search_state] = 'selected="selected"';
		if($search_orderid) $where .= $where ? " AND orderid like '%$search_orderid%'" : "orderid like '%$search_orderid%'";
		if($search_gameid) $where .= $where ?	" AND gameid='$search_gameid'" : "gameid='$search_gameid'";
		if($search_qishu) $where .= $where ?	" AND qishu='$search_qishu'" : "qishu='$search_qishu'";
		if($search_uid) $where .= $where ?	" AND uid='$search_uid'" : "uid='$search_uid'";
		if ($search_agent) $where .= $where ? " AND agent = '$search_agent'" : "agent = '$search_agent'";
		if($search_state){
			if($search_state == 6) {
				$where .= $where ?	" AND account=0 AND tui=0" : "account=0 AND tui=0";
			} elseif($search_state == 1) {
				$where .= $where ?	" AND account>0" : "account>0";
			} elseif($search_state == 2) {
				$where .= $where ?	" AND account<0" : "account<0";
			} elseif($search_state == 3) {
				$where .= $where ?	" AND tui=1" : "tui=1";
			} elseif($search_state == 4) {
				$where .= $where ?	" AND tui=2" : "tui=2";
			} elseif($search_state == 5) {
				$where .= $where ?	" AND tui=3" : "tui=3";
			}
		}
		if ($search_start_time) {
			$time_start = strtotime($search_start_time);
			$where .= $where ? " AND addtime >= '$time_start'" : "addtime >= '$time_start'";
		}
		if ($search_end_time) {
			$time_end = strtotime($search_end_time);
			$where .= $where ? " AND addtime <= '$time_end'" : "addtime <= '$time_end'";
		}
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $this->db->listinfo($where, 'id DESC', $page, 15);
		$pages = $this->db->pages;
		base::load_sys_class('format', '', 0);
		base::load_sys_class('form', '', 0);
		//查询游戏列表
		$gamearr = $this -> gamelist();
		include $this->admin_tpl('order_list');
	}

	public function del() {
		$id = intval($_GET['id']);
		if (!$id) {
			echo json_encode(array('run' => 'no', 'msg' => '参数错误！'));
			exit();
		}
		$r = $this -> db -> get_one(array('id' => $id));
		if ($r) {
			if ($this -> db -> delete(array('id' => $id))) {
				echo json_encode(array('run' => 'yes', 'msg' => '删除成功！', 'id' => 'list_' . $id));
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

	public function invalid() {
		if ($_POST['type']) { // 批量操作
			if (!is_array($_POST['id'])) { // 不是数组列
				showmessage('请先选择再执行操作', HTTP_REFERER);
			}
			$idarr = $_POST['id'];
		} else {
			if (!$_GET['id']) {
				echo json_encode(array('run' => 'no', 'msg' => '参数错误！'));
				exit();
			}
			$idarr = array($_GET['id']);
		}
		$return = false;
		foreach($idarr as $id) {
			$id = intval($id);
			$r = $this -> db -> get_one(array('id' => $id));
			if ($r) {
				if ($r['account'] == 0 && $r['tui'] == 0) {//注单未结算
					if ($this->db->update(array('tui' => 1), array('id' => $id))) {
						$db2 = base::load_model('user_model');
						$db3 = base::load_model('account_model');
						$user = $db2 -> get_one(array('uid' => $r['uid']));
						$db2->update(array('money' => '+='.$r['money']), array('uid' => $r['uid']));//本金退还
						//流水记录
						$comment = '未结算注单退单，单号：'.$r['orderid'];
						$countmoney = $user['money'] + $r['money'];
						$db3->insert(array('uid'=>$r['uid'], 'money'=>$r['money'], 'countmoney'=>$countmoney, 'type'=>4, 'addtime'=>SYS_TIME, 'comment'=>$comment));
						$return = true;
					}
				} else {//注单已结算
					if ($this->db->update(array('tui' => 2), array('id' => $id))) {
						$db2 = base::load_model('user_model');
						$db3 = base::load_model('account_model');
						$user = $db2 -> get_one(array('uid' => $r['uid']));
						$db2->update(array('money' => '+='.$r['money']), array('uid' => $r['uid']));//本金退还
						//流水记录
						$comment = '无效注单退单，单号：'.$r['orderid'];
						$countmoney = $user['money'] + $r['money'];
						$db3->insert(array('uid'=>$r['uid'], 'money'=>$r['money'], 'countmoney'=>$countmoney, 'type'=>4, 'addtime'=>SYS_TIME, 'comment'=>$comment));
						if ($r['account'] > 0) {//赢了退回
							$account = '-='.$r['account'];
							$countmoney = $countmoney - $r['account'];
							$db2->update(array('money' => $account), array('uid' => $r['uid']));//盈利资金退还
							//流水记录
							$comment = '无效注单盈利返还，单号：'.$r['orderid'];
							$db3->insert(array('uid'=>$r['uid'], 'money'=>$r['account'], 'countmoney'=>$countmoney, 'type'=>4, 'addtime'=>SYS_TIME, 'comment'=>$comment));
						}
						$return = true;
					}
				}
			}
		}
		if ($_POST['type']) { // 批量操作
			showmessage('处理完成！', 'c=order&a=init');
		} else {
			if ($return) {
				echo json_encode(array('run' => 'yes', 'msg' => '处理成功！'));
			} else {
				echo json_encode(array('run' => 'no', 'msg' => '处理失败！'));
			}
			exit();
		}
	}

	public function against() {
		if ($_POST['type']) { // 批量操作
			if (!is_array($_POST['id'])) { // 不是数组列
				showmessage('请先选择再执行操作', HTTP_REFERER);
			}
			$idarr = $_POST['id'];
		} else {
			if (!$_GET['id']) {
				echo json_encode(array('run' => 'no', 'msg' => '参数错误！'));
				exit();
			}
			$idarr = array($_GET['id']);
		}
		$return = false;
		foreach($idarr as $id) {
			$id = intval($id);
			$r = $this -> db -> get_one(array('id' => $id));
			if ($r) {
				if ($this->db->update(array('tui' => 3), array('id' => $id))) {
					$db2 = base::load_model('user_model');
					$db3 = base::load_model('account_model');
					$user = $db2 -> get_one(array('uid' => $r['uid']));
					if ($r['account'] > 0) {//赢了退回
						$account = '-='.$r['account'];
						$countmoney = $user['money'] - $r['account'];
						$db2->update(array('money' => $account), array('uid' => $r['uid']));//盈利资金退还
						//流水记录
						$comment = '违规注单盈利返还，单号：'.$r['orderid'];
						$db3->insert(array('uid'=>$r['uid'], 'money'=>$r['account'], 'countmoney'=>$countmoney, 'type'=>4, 'addtime'=>SYS_TIME, 'comment'=>$comment));
					}
					$return = true;
				}
			}
		}
		if ($_POST['type']) { // 批量操作
			showmessage('处理完成！', 'c=order&a=init');
		} else {
			if ($return) {
				echo json_encode(array('run' => 'yes', 'msg' => '处理成功！'));
			} else {
				echo json_encode(array('run' => 'no', 'msg' => '处理失败！'));
			}
			exit();
		}
	}

	public function go_gamename($gameid) {// 返回游戏名称
		$db = base::load_model('game_model');
		$game = $db -> get_one(array('id' => $gameid));
		echo $game['name'];
	}

	public function delall() {
		$time = SYS_TIME - (86400 * 30 * 3);
		$where = "addtime <= '$time' AND (account <> 0 || tui > 0)";
		if ($this -> db -> delete($where)) {
			echo json_encode(array('run' => 'yes', 'msg' => '清理完成！'));
			exit();
		} else {
			echo json_encode(array('run' => 'no', 'msg' => '清理失败！'));
			exit();
		}
	}
}