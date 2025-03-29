<?php
defined('IN_MYWEB') or exit('No permission resources.');
base :: load_app_class('daili', 'daili', 0);
class pay extends daili{

	private $db, $db2, $db3, $state, $uid, $username;

	public function __construct() {
		parent::__construct(2);
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
		$this -> uid = intval($this -> get_userid());
		$this -> username = trim($this -> get_username());
	}

	public function init() {
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $this->db->listinfo("agent = '$this->uid' OR agents = '$this->uid'", 'id DESC', $page, 15);
		$pages = $this->db->pages;
		base::load_sys_class('format', '', 0);
		include $this->daili_tpl('pay_list');
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
		$search_payid = safe_replace($payid);
		$search_state = intval($state);
		$stateoption[$search_state] = 'selected="selected"';
		if($search_payid) $where .= " AND payid like '%$search_payid%'";
		if($search_state){
			if($search_state == 5) $search_state = 0;
			$where .= " AND state='$search_state'";
		}
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $this->db->listinfo($where, 'id DESC', $page, 15);
 		$pages = $this->db->pages;
		base::load_sys_class('format', '', 0);
 		include $this->daili_tpl('pay_list');
	}

	public function add() {
		return false;
		if (isset($_POST['dosubmit'])) {
			$uid = isset($_POST['uid']) && intval($_POST['uid']) ? intval($_POST['uid']) : showmessage('请填写UID！', HTTP_REFERER);
			$money = isset($_POST['money']) && trim($_POST['money']) ? trim($_POST['money']) : showmessage('请填写金额！', HTTP_REFERER);
			$comment = isset($_POST['comment']) && trim($_POST['comment']) ? safe_replace(trim($_POST['comment'])) : '';
			$user = $this->db2->get_one(array('uid'=>$uid));
			$agent = $this -> check_agent($user);
			if (!$agent) {
				showmessage('无权操作该账户！');
			}
			if ($user) {
				$money = round($money, 2);//金额
				if ($money > 0) {
					$agent_money = -1 * $money;//转为负数
					if ($money > $agent['money']) showmessage('代理账户余额不足！', HTTP_REFERER);
				} elseif ($money < 0) {
					$agent_money = abs($money);//转为正数
					if ($agent_money > $user['money']) showmessage('操作账户余额不足！', HTTP_REFERER);
				} else {
					showmessage('请填写金额！', HTTP_REFERER);
				}
				if ($this->db2->update(array('money' => '+='.$money), array('uid' => $uid)) && $this->db2->update(array('money' => '+='.$agent_money), array('uid' => $agent['uid']))) {//充值
					$payid = date('YmdHis',SYS_TIME).random(6, '1234567890');//日期加随机订单号
					//处理旗下账户
					$agents = $this -> uid == $agent['uid'] ? $agent['uid'] : 0;
					$this->db->insert(array('uid'=>$uid, 'agent'=>$user['agent'], 'agents'=>$agents, 'payid'=>$payid, 'money'=>$money, 'state'=>3, 'comment'=>$comment, 'addtime'=>SYS_TIME, 'paytime'=>SYS_TIME));
					//流水记录
					$countmoney = $user['money'] + $money;
					$this->db3->insert(array('uid'=>$uid, 'money'=>$money, 'countmoney'=>$countmoney, 'type'=>0, 'addtime'=>SYS_TIME, 'comment'=>'代理充值'));
					//流水记录
					$agent_countmoney = $agent['money'] + $agent_money;
					$this->db3->insert(array('uid'=>$agent['uid'], 'money'=>$agent_money, 'countmoney'=>$agent_countmoney, 'type'=>0, 'addtime'=>SYS_TIME, 'comment'=>'代理充值-划账'));
					showmessage('充值成功！', 'c=pay&a=init');
				} else {
					showmessage('充值失败！', HTTP_REFERER);
				}
			} else {
				showmessage('未找到对应UID数据！', HTTP_REFERER);
			}
		}
		$uid = isset($_GET['uid']) && intval($_GET['uid']) ? intval($_GET['uid']) : '';
		include $this->daili_tpl('pay_add');
	}

	public function addto() {
		$id = intval($_GET['id']);
		if (!$id) {
			echo json_encode(array('run' => 'no', 'msg' => '参数错误！'));
			exit();
		}
		$r = $this -> db -> get_one(array('id' => $id, 'state' => 0));
		if ($r) {
			$uid = $r['uid'];
			$money = $r['money'];
			$user = $this->db2->get_one(array('uid' => $uid));
			$agent = $this -> check_agent($user);
			if (!$agent) {
				echo json_encode(array('run' => 'no', 'msg' => '无权操作该账户！'));
				exit();
			}
			if (!$user) {
				echo json_encode(array('run' => 'no', 'msg' => '未找到对应UID数据！'));
				exit();
			} else {
				$agent_money = -1 * $money;//转为负数
				if ($money > $agent['money']) {
					echo json_encode(array('run' => 'no', 'msg' => '代理账户余额不足！'));
					exit();
				}
				if ($this->db2->update(array('money' => '+='.$money), array('uid' => $uid)) && $this->db2->update(array('money' => '+='.$agent_money), array('uid' => $agent['uid']))) {//充值
					//更新订单
					$this->db->update(array('state' => 1, 'paytime' => SYS_TIME), array('id' => $id, 'uid' => $uid));
					//流水记录
					$countmoney = $user['money'] + $money;
					$this->db3->insert(array('uid'=>$uid, 'money'=>$money, 'countmoney'=>$countmoney, 'type'=>0, 'addtime'=>SYS_TIME, 'comment'=>'等待支付转代理人工到帐'));
					//流水记录
					$agent_countmoney = $agent['money'] + $agent_money;
					$this->db3->insert(array('uid'=>$agent['uid'], 'money'=>$agent_money, 'countmoney'=>$agent_countmoney, 'type'=>0, 'addtime'=>SYS_TIME, 'comment'=>'代理充值-划账'));
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
			$agent = $this -> check_agent($r['uid']);
			if (!$agent) {
				echo json_encode(array('run' => 'no', 'msg' => '无权操作该账户！'));
				exit();
			}
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

	public function ajax_uid() {// 检查用户名是否存在
		$_aid = $this -> get_userinfo('aid');
		$uid = isset($_POST['param']) && trim($_POST['param']) ? safe_replace(trim($_POST['param'])) : '';
		$user = $this -> db2 -> get_one(array('uid' => $uid));
		$agent = $this -> check_agent($user);
		if (!$agent) {
			$msg['info'] = '未找到代理下对应UID数据！';
			$msg['status'] = 'n';
		} else {
			$msg['status'] = 'y';
			if ($agent['uid'] != $user['agent']) {
				$msg['info'] = '查询到子代理下对应UID账户名：'.$user['username'];
			} else {
				$msg['info'] = '查询到代理下对应UID账户名：'.$user['username'];
			}
		}
		echo json_encode($msg);
	}

}