<?php
defined('IN_MYWEB') or exit('No permission resources.');
base::load_app_class('admin','admin',0);
class account extends admin{

	private $db, $type;

	public function __construct() {
		parent::__construct(1);
		$this->db = base::load_model('account_model');
		$this->db2 = base::load_model('user_model');
		$this->type = array(
			0 => '<span style="color: #FFA700;">充值</span>',
			1 => '<span style="color: #0070FF;">提现</span>',
			2 => '<span style="color: #00B520;">投注</span>',
			3 => '<span style="color: #FF0000;">盈利</span>',
			4 => '<span style="color: #FF00DE;">退单</span>',
			5 => '<span style="color: #F60;">红包</span>',
			6 => '<span style="color: #F60;">返利</span>'
		);
	}

	public function init() {
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $this->db->listinfo('', 'id DESC', $page, 15);
		$pages = $this->db->pages;
		base::load_sys_class('format', '', 0);

		include $this->admin_tpl('account_list');
	}

	public function search() {
 		$where = "";
		if(is_array($_GET['search'])) extract($_GET['search']);
		$search_uid = intval($uid);
		$search_type = intval($type);
		$typeoption[$search_type] = 'selected="selected"';
		if($search_uid) $where .= $where ?  " AND uid='$search_uid'" : "uid='$search_uid'";
		if($search_type){
			if($search_type == 5) $search_type = 0;
			$where .= $where ?  " AND type='$search_type'" : "type='$search_type'";
		}
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $this->db->listinfo($where, 'id DESC', $page, 15);
 		$pages = $this->db->pages;
		base::load_sys_class('format', '', 0);
 		include $this->admin_tpl('account_list');
	}

	public function del() {
		if ($_POST['type']) { // 批量操作
			//showmessage('禁止操作！', HTTP_REFERER);
			if (!is_array($_POST['id'])) { // 不是数组列
				showmessage('请先选择再执行操作！', HTTP_REFERER);
			}
			foreach($_POST['id'] as $v) {
				$idadd[] = intval($v);
			}
			$where = "id IN (" . implode(",", $idadd) . ")";
			$this -> db -> delete($where);
			showmessage('删除成功！', 'c=account&a=init');
		} else { // 单条操作
			//echo json_encode(array('run' => 'no', 'msg' => '禁止操作！'));
			//exit();
			$id = intval($_GET['id']);
			if (!$id) {
				echo json_encode(array('run' => 'no', 'msg' => '参数错误！'));
				exit();
			}
			if ($this -> db -> delete(array('id' => $id))) {
				echo json_encode(array('run' => 'yes', 'msg' => '删除成功！', 'id' => 'list_' . $id));
				exit();
			} else {
				echo json_encode(array('run' => 'no', 'msg' => '删除失败！'));
				exit();
			}
		}
	}

	public function day(){
		$num = date('Ymd') - date('Ym01') + 1;
		$data = $list = array();
		for ($i=1; $i <= $num; $i++) { 
			$list[] = date('Y-m-'.sprintf("%02d", $i));
		}
		$list = array_reverse($list);


		$search = $_GET['search'];
		$term = "";
		if(!empty($search)){
			if(!empty($search['uid'])){
				$term = " AND `uid`=".$search['uid'];
				$search_uid = $search['uid'];
			}
		}

		$ret = $this->db2->select("aid=0",'uid');
		$uids = array();
		foreach ($ret as $val) {
			$uids[] = $val['uid'];
		}
		$uids = implode(',', $uids);
		// var_dump($uids);exit;

		$start = strtotime(date('Y-m-01 H:i:s'));
		$end = time();
		$ret = $this->db->querys("SELECT FROM_UNIXTIME(a.`addtime`,'%Y-%m-%d') as time , SUM(a.`money`) as money , a.`type`  FROM `bc_user` as u LEFT JOIN `bc_account` as a ON a.`uid`=u.`uid` where u.`is_robot`=0 and a.`addtime` >= {$start} AND a.`type` IN(0,1)  {$term} GROUP BY a.`type`,time",1); //AND a.`uid` IN({$uids})
		$count = array(0,0);
		foreach ($ret as $val) {
			$data[$val['time']][$val['type']] = $val['money'];
			$count[$val['type']] += $val['money'];
		}
		include $this->admin_tpl('account_day');
	}

	public function user(){
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
		$infos = $this->db2->listinfo($term, 'uid DESC', $page, 15);
		$pages = $this->db2->pages;
		
		$infos = $this->user_info($infos,$search);

		include $this->admin_tpl('account_user');
	}

	public function addBet() {
		$uid 	= $_GET['uid'];
		$date 	= date('Y-m-d');

		if (isset($_POST['dosubmit'])) {
			$insert = array(
				'uid' 		=> $uid,
				'date_time' => $date,
				'money' 	=> floatval($_POST['extra'])
			);
			if (base::load_model('extra_model') -> insert($insert)) {
				showmessage('操作成功！', 'c=account&a=addBet&uid=' . $uid);
			} else {
				showmessage('操作失败！', HTTP_REFERER);
			}
		}

		// $uid 	= 1418;
		// $date 	= '2025-04-12';

		$db 		= base::load_model('account_model');
		$total_bet 	= $db->getTotalBet($date, $uid);
		$extra 		= $db->getExtra($date, $uid);

		include $this->admin_tpl('account_user_add_bets');
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
				$ret = $this->db->querys("SELECT SUM(`money`) as money , type  FROM bc_account where `addtime` > {$start} AND `addtime` < {$end} AND type IN(0,1) AND `uid`={$val['uid']} GROUP BY type",1);
				
				foreach ($ret as $vale) $data[$vale['type']] = $vale['money'];
				$val['recharge'] = empty($data[0])?'0.00':$data[0];
				$val['withdraw'] = empty($data[1])?'0.00':$data[1];
			}else{
				$child = $this->get_user_children($val['uid']);
				$child = implode(',', $child);
				$ret = $this->db->querys("SELECT SUM(`money`) as money , type  FROM bc_account where `addtime` > {$start} AND `addtime` < {$end} AND type IN(0,1) AND `uid` IN({$child}) GROUP BY type",1);
				foreach ($ret as $vale) $data[$vale['type']] = $vale['money'];
				$val['recharge'] = empty($data[0])?'0.00':$data[0];
				$val['withdraw'] = empty($data[1])?'0.00':$data[1];
			}

		}
		
		return $infos;
	}

	public function get_user_children($uid){
		$childs[] = $uid;
		$child = $this->db2->select("agent={$uid}",'uid');
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


	public function delall() {
		$time = SYS_TIME - (86400 * 30 * 3);
		$where = "addtime <= '$time'";
		if ($this -> db -> delete($where)) {
			echo json_encode(array('run' => 'yes', 'msg' => '清理完成！'));
			exit();
		} else {
			echo json_encode(array('run' => 'no', 'msg' => '清理失败！'));
			exit();
		}
	}
}