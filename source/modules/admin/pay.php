<?php
defined('IN_MYWEB') or exit('No permission resources.');
base::load_app_class('admin','admin',0);
class pay extends admin{

	private $db, $db2, $db3, $state;

	public function __construct() {
		parent::__construct();
		$this->db = base::load_model('pay_model');
		$this->db2 = base::load_model('user_model');
		$this->db3 = base::load_model('account_model');
		$this->state = array(
			0 => '<span style="color: #FFA700;">等待支付</span>',
			1 => '<span style="color: #00B520;">在线充值</span>',
			2 => '<span style="color: #0070FF;">人工充值</span>',
			3 => '<span style="color: #0070FF;">代理充值</span>',
			4 => '<span style="color: #F00;">充值失败</span>'
		);
	}

	public function init() {
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$user_db = base :: load_model('user_model');
		$settingarr = $this -> get_settings(); //读取系统设置
		$blacklist = explode(",",$settingarr['blacklist']);
		$blacklist = "'".implode("','", $blacklist)."'";
		$user_db = $user_db->select("loginip IN({$blacklist})",'uid');
		foreach($user_db as $k => $v) {
			$uids[] = $v['uid'];
		}
		$uids = empty($uids)?"":implode(',', $uids);
		$term = empty($uids)?"":"uid NOT IN({$uids})";
		$list = $this->db->listinfo($term, 'id DESC', $page, 15);
		$pages = $this->db->pages;
		base::load_sys_class('format', '', 0);
		include $this->admin_tpl('pay_list');
	}

	public function search() {
 		$where = '';
		if(is_array($_GET['search'])) extract($_GET['search']);
		$search_payid = safe_replace($payid);
		$search_uid = intval($uid);
		$search_agent = intval($agent);
		$search_agents = intval($agents);
		$search_state = intval($state);
		$stateoption[$search_state] = 'selected="selected"';
		if($search_payid) $where .= $where ? " AND payid like '%$search_payid%'" : "payid like '%$search_payid%'";
		if($search_uid) $where .= $where ?  " AND uid='$search_uid'" : "uid='$search_uid'";
		if($search_agent) $where .= $where ?  " AND agent='$search_agent'" : "agent='$search_agent'";
		if($search_agents) $where .= $where ?  " AND agents='$search_agents'" : "agents='$search_agents'";
		if($search_state){
			if($search_state == 5) $search_state = 0;
			$where .= $where ?  " AND state='$search_state'" : "state='$search_state'";
		}
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $this->db->listinfo($where, 'id DESC', $page, 15);
 		$pages = $this->db->pages;
		base::load_sys_class('format', '', 0);
 		include $this->admin_tpl('pay_list');
	}

	public function add() {
		if (isset($_POST['dosubmit'])) {
			$uid = isset($_POST['uid']) && intval($_POST['uid']) ? intval($_POST['uid']) : showmessage('请填写UID！', HTTP_REFERER);
			$money = isset($_POST['money']) && trim($_POST['money']) ? trim($_POST['money']) : showmessage('请填写金额！', HTTP_REFERER);
			$comment = isset($_POST['comment']) && trim($_POST['comment']) ? safe_replace(trim($_POST['comment'])) : '';
			$user = $this->db2->get_one(array('uid' => $uid));
			if (!$user) {
				showmessage('未找到对应UID数据！', HTTP_REFERER);
			} else {
				$money = round($money, 2);//金额
				if ($this->db2->update(array('money' => '+='.$money), array('uid' => $uid))) {//充值
					$payid = date('YmdHis',SYS_TIME).random(6, '1234567890');//日期加随机订单号
					$agents = 0;
					if ($user['agent']) {//如果存在上级代理
						$agent = $this->db2->get_one(array('uid' => $user['agent']));
						if ($agent['aid'] == 3) {//上级为二级代理(阅)
							$agents = $agent['agent'];
						}
					}
					if($_POST['type'] == 1){
						$this->db->insert(array('uid'=>$uid, 'agent'=>$user['agent'], 'agents'=>$agents, 'payid'=>$payid, 'money'=>$money, 'state'=>2, 'comment'=>$comment, 'addtime'=>SYS_TIME, 'paytime'=>SYS_TIME));
						//流水记录
						$countmoney = $user['money'] + $money;
						$this->db3->insert(array('uid'=>$uid, 'money'=>$money, 'countmoney'=>$countmoney, 'type'=>5, 'addtime'=>SYS_TIME, 'comment'=>'管理后台人工充值'));
					}else if($_POST['type'] == 2){
						$this->db->insert(array('uid'=>$uid, 'agent'=>$user['agent'], 'agents'=>$agents, 'payid'=>$payid, 'money'=>$money, 'state'=>2, 'comment'=>$comment, 'addtime'=>SYS_TIME, 'paytime'=>SYS_TIME));
						//流水记录
						$countmoney = $user['money'] + $money;
						$this->db3->insert(array('uid'=>$uid, 'money'=>$money, 'countmoney'=>$countmoney, 'type'=>0, 'addtime'=>SYS_TIME, 'comment'=>'管理后台人工充值'));
						$this->db2->update(array('aims_dama' => '+='.$money*$this -> setting['dama_mult']), array('uid' => $uid));
					}
					
					showmessage('充值成功！', 'c=pay&a=init');
				} else {
					showmessage('充值失败！', HTTP_REFERER);
				}
			}
		}
		$uid = isset($_GET['uid']) && intval($_GET['uid']) ? intval($_GET['uid']) : '';
		include $this->admin_tpl('pay_add');
	}

	public function addto() {
		$id = intval($_GET['id']);
		if (!$id) {
			echo json_encode(array('run' => 'no', 'msg' => '参数错误！'));
			exit();
		}
		$r = $this -> db -> get_one(array('id' => $id, 'state' => 0));
		if ($r) {
		    $settingarr = $this -> get_settings();
		    $dict = [
		        $settingarr['hbname1']=>$settingarr['hblv1'],
		        $settingarr['hbname2']=>$settingarr['hblv2'],
		        $settingarr['hbname3']=>$settingarr['hblv3'],
		        $settingarr['hbname4']=>$settingarr['hblv4'],
		        $settingarr['hbname5']=>$settingarr['hblv5'],
		    ];
		    
			$uid = $r['uid'];
			$money = $r['money']*$dict[$r['type2']];
			$user = $this->db2->get_one(array('uid' => $uid));
			if (!$user) {
				echo json_encode(array('run' => 'no', 'msg' => '未找到对应UID数据！'));
				exit();
			} else {
				$settingarr = $this -> get_settings();
				$gift = unserialize(urldecode($settingarr['gift']));
				
				//首冲送金额
				$first = $this->db3->select("uid={$uid} AND type=0","id");
				$first_money = 0;
				if(count($first) == 1){
					if(!empty($gift['firstRechargeMoney5']) && $money >= $gift['firstRechargeMoney5']){
						$first_money = $gift['firstRechargeMoneyGive5'];
					}else if(!empty($gift['firstRechargeMoney4']) && $money >= $gift['firstRechargeMoney4']){
						$first_money = $gift['firstRechargeMoneyGive4'];
					}else if(!empty($gift['firstRechargeMoney3']) && $money >= $gift['firstRechargeMoney3']){
						$first_money = $gift['firstRechargeMoneyGive3'];
					}else if(!empty($gift['firstRechargeMoney2']) && $money >= $gift['firstRechargeMoney2']){
						$first_money = $gift['firstRechargeMoneyGive2'];
					}else if(!empty($gift['firstRechargeMoney']) && $money >= $gift['firstRechargeMoney']){
						$first_money = $gift['firstRechargeMoneyGive'];
					}
					// var_dump($first_money);exit;
				}
				//每日首冲送金额
				$day_time = strtotime(date("Y-m-d 00:00:00"));
				$day_first = $this->db3->select("uid={$uid} AND type=0 AND addtime > $day_time","id");
				$day_first_money = 0;
				if(count($day_first) == 1){
					if(!empty($gift['dayFirstRechargeMoneyGive5']) && $money >= $gift['dayFirstRechargeMoney5']){
						$day_first_money = $gift['dayFirstRechargeMoneyGive5'];
					}else if(!empty($gift['dayFirstRechargeMoneyGive4']) && $money >= $gift['dayFirstRechargeMoney4']){
						$day_first_money = $gift['dayFirstRechargeMoneyGive4'];
					}else if(!empty($gift['dayFirstRechargeMoneyGive3']) && $money >= $gift['dayFirstRechargeMoney3']){
						$day_first_money = $gift['dayFirstRechargeMoneyGive3'];
					}else if(!empty($gift['dayFirstRechargeMoneyGive2']) && $money >= $gift['dayFirstRechargeMoney2']){
						$day_first_money = $gift['dayFirstRechargeMoneyGive2'];
					}else if(!empty($gift['dayFirstRechargeMoneyGive']) && $money >= $gift['dayFirstRechargeMoney']){
						$day_first_money = $gift['dayFirstRechargeMoneyGive'];
					}
					// var_dump($day_first_money);exit;
				}
				//每次充值送金额
				$boutfirst_money = 0;
				if(!empty($gift['rechargeMoneyGive5']) && $money >= $gift['rechargeMoney5']){
					$boutfirst_money = $gift['rechargeMoneyGive5'];
				}else if(!empty($gift['rechargeMoneyGive4']) && $money >= $gift['rechargeMoney4']){
					$boutfirst_money = $gift['rechargeMoneyGive4'];
				}else if(!empty($gift['rechargeMoneyGive3']) && $money >= $gift['rechargeMoney3']){
					$boutfirst_money = $gift['rechargeMoneyGive3'];
				}else if(!empty($gift['rechargeMoneyGive2']) && $money >= $gift['rechargeMoney2']){
					$boutfirst_money = $gift['rechargeMoneyGive2'];
				}else if(!empty($gift['rechargeMoneyGive']) && $money >= $gift['rechargeMoney']){
					$boutfirst_money = $gift['rechargeMoneyGive'];
				}
				// var_dump($boutfirst_money);exit;
				// var_dump($money+$first_money+$day_first_money+$boutfirst_money);exit;
				$add_money = $money+$first_money+$day_first_money+$boutfirst_money;
				if ($this->db2->update(array('money' => '+='.$add_money), array('uid' => $uid))) {//充值
					$this->db2->update(array('aims_dama' => '+='.$money*$settingarr['dama_mult']), array('uid' => $uid));

					//更新订单
					$this->db->update(array('state' => 1, 'paytime' => SYS_TIME), array('id' => $id, 'uid' => $uid));
					//流水记录
					$countmoney = $user['money'] + $money+$first_money+$day_first_money+$boutfirst_money;
					$this->db3->insert(array('uid'=>$uid, 'money'=>$money, 'countmoney'=>$countmoney, 'type'=>0, 'addtime'=>SYS_TIME, 'comment'=>'等待支付转管理后台人工到帐'));

					if($first_money > 0){
						$this->db3->insert(array('uid'=>$uid, 'money'=>$first_money, 'countmoney'=>$countmoney, 'type'=>5, 'addtime'=>SYS_TIME, 'comment'=>'首冲送金额'));
					}

					if($day_first_money > 0){
						$this->db3->insert(array('uid'=>$uid, 'money'=>$day_first_money, 'countmoney'=>$countmoney, 'type'=>5, 'addtime'=>SYS_TIME, 'comment'=>'首冲送金额'));
					}

					if($boutfirst_money > 0){
						$this->db3->insert(array('uid'=>$uid, 'money'=>$boutfirst_money, 'countmoney'=>$countmoney, 'type'=>5, 'addtime'=>SYS_TIME, 'comment'=>'每次充值送金额'));
					}
					


					echo json_encode(array('run' => 'yes','msg' => '充值成功！','id' => array(
						array('id' => 'state_'.$id,'htm' => '<span style="color: #00B520;">在线充值</span>'),
						array('id' => 'paytime_'.$id,'htm' => '1秒前'),
						array('id' => 'addto_'.$id,'htm' => '')
					)));
					exit();
				} else {
					echo json_encode(array('run' => 'no', 'msg' => '充值失败！'));
					exit();
				}
			}
		} else {
			echo json_encode(array('run' => 'no', 'msg' => '未找到对应数据！'));
			exit();
		}
	}

	public function delto() {
		$id = intval($_GET['id']);
		if (!$id) {
			echo json_encode(array('run' => 'no', 'msg' => '参数错误！'));
			exit();
		}
		$r = $this -> db -> get_one(array('id' => $id, 'state' => 0));
		if ($r) {
			if ($this->db->update(array('state' => 4), array('id' => $id))) {//更新订单
				echo json_encode(array('run' => 'yes','msg' => '操作成功！','id' => array(
					array('id' => 'state_'.$id,'htm' => '<span style="color: #F00;">充值失败</span>'),
					array('id' => 'addto_'.$id,'htm' => '')
				)));
			} else {
				echo json_encode(array('run' => 'no', 'msg' => '操作失败！'));
				exit();
			}
		} else {
			echo json_encode(array('run' => 'no', 'msg' => '未找到对应数据！'));
			exit();
		}
	}

	public function del() {
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

	public function counts() {
		base::load_sys_class('form', '', 0);
		$settingarr = $this -> get_settings(); //读取系统设置
		$pay_count = $this -> db -> count();

		if (isset($_GET['dosubmit']) && (trim($_GET['start_time']) || trim($_GET['end_time']) || trim($_GET['uid']) || trim($_GET['state']))) {
			$uid = intval($_GET['uid']);
			$where = "";
			if ($uid) {
				$where .= " AND uid = '$uid'";
			}
			$agent = intval($_GET['agent']);
			$where = "";
			if ($agent) {
				$where .= " AND agent = '$agent'";
			}
			$agents = intval($_GET['agents']);
			$where = "";
			if ($agents) {
				$where .= " AND agents = '$agents'";
			}
			$state = intval($_GET['state']);
			if ($state != 0) {
				if ($state == -1) {
					$where .= " AND state = 0";
				} else {
					$where .= " AND state = '$state'";
				}
			}
			$start_time = trim($_GET['start_time']);
			$end_time = trim($_GET['end_time']);
			if ($start_time) {
				$time_start = strtotime($start_time);
				$where .= " AND addtime >= '$time_start'";
			}
			if ($end_time) {
				$time_end = strtotime($end_time);
				$where .= " AND addtime <= '$time_end'";
			}
			$where = "id > 0$where";
			//自定义时间统计
			$custom_count = $this -> go_pay_count($where);
			$custom = true;
		} else {
			//今日统计
			$starttime = strtotime(date('Y-m-d'));//今日0点
			$today_where = "addtime >= '$starttime'";
			$today_count = $this -> go_pay_count($today_where);
			//昨日统计
			$starttime = strtotime(date('Y-m-d')) - 86400;//昨日0点
			$endtime = strtotime(date('Y-m-d'));//今日0点
			$yesterday_where = "addtime >= '$starttime' AND addtime < '$endtime'";
			$yesterday_count = $this -> go_pay_count($yesterday_where);
			//本周统计
			//$starttime = mktime(0, 0 , 0, date('m'), date('d')-date('w')+1, date('Y'));//本周开始时间
			$starttime = strtotime(date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600)));//本周开始时间
			$tswk_where = "addtime >= '$starttime'";
			$tswk_count = $this -> go_pay_count($tswk_where);
			//本月统计
			$starttime = mktime(0, 0 , 0, date('m'), 1, date('Y'));//本月开始时间
			$thismonth_where = "addtime >= '$starttime'";
			$thismonth_count = $this -> go_pay_count($thismonth_where);
			//上月统计
			$starttime = mktime(0, 0 , 0, date('m')-1, 1, date('Y'));//上月开始时间
			$endtime = mktime(23,59,59,date('m') ,0,date('Y'));//上月结束时间
			$lastmonth_where = "addtime >= '$starttime' AND addtime < '$endtime'";
			$lastmonth_count = $this -> go_pay_count($lastmonth_where);
			//本季度统计
			$season = ceil((date('n'))/3);//当月是第几季度
			$starttime = mktime(0, 0, 0, $season*3-3+1, 1, date('Y'));//本季度开始时间
			$quarter_where = "addtime >= '$starttime'";
			$quarter_count = $this -> go_pay_count($quarter_where);
		}

		$statearr = array(
			-1 => '等待支付',
			1 => '在线充值',
			2 => '人工充值',
			3 => '代理充值',
			4 => '充值失败'
		);
		include $this -> admin_tpl('pay_count');
	}

	public function ajax_uid() {// 检查用户名是否存在
		$uid = isset($_POST['param']) && trim($_POST['param']) ? safe_replace(trim($_POST['param'])) : '';
		$user = $this -> db2 -> get_one(array('uid' => $uid));
		if ($user) {
			if ($user['agent']) {
				$tps = ' 【注意，这是一个其他代理（UID：'.$user['agent'].'）的旗下账户！】';
			}
			$msg['info'] = '查询到对应UID账户名：'.$user['username'].$tps;
			$msg['status'] = 'y';
		} else {
			$msg['info'] = '未找到对应UID数据！';
			$msg['status'] = 'n';
		}
		echo json_encode($msg);
	}

}