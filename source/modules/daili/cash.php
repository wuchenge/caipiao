<?php
defined('IN_MYWEB') or exit('No permission resources.');
base::load_app_class('daili','daili',0);
class cash extends daili{

	private $db, $state, $uid, $username;

	public function __construct() {
		parent::__construct(2);
		$this->db = base::load_model('cash_model');
		$this->state = array(
			0 => '<span style="color: #FFA700;">等待处理</span>',
			1 => '<span style="color: #0070FF;">正在处理</span>',
			2 => '<span style="color: #00B520;">提现成功</span>',
			3 => '<span style="color: #F00;">提现失败</span>'
		);
		$this -> uid = intval($this -> get_userid());
		$this -> username = trim($this -> get_username());
	}

	public function init() {
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $this->db->listinfo("agent = '$this->uid' OR agents = '$this->uid'", 'id DESC', $page, 15);
		$pages = $this->db->pages;
		base::load_sys_class('format', '', 0);
		include $this->daili_tpl('cash_list');
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
		$search_state = intval($state);
		$stateoption[$search_state] = 'selected="selected"';
		if($search_state){
			if($search_state == 4) $search_state = 0;
			$where .= " AND state='$search_state'";
		}
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $this->db->listinfo($where, 'id DESC', $page, 15);
 		$pages = $this->db->pages;
		base::load_sys_class('format', '', 0);
 		include $this->daili_tpl('cash_list');
	}

	public function setstate() {
		$state = isset($_GET['state']) ? intval($_GET['state']) : 0;
		$id = intval($_GET['id']);
		if (!$id) {
			echo json_encode(array('run' => 'no', 'msg' => '参数错误！'));
			exit();
		}
		$r = $this -> db -> get_one(array('id' => $id));
		if ($r) {
			$db2 = base::load_model('user_model');
			$db3 = base::load_model('account_model');
			$uid = $r['uid'];
			$money = $r['money'];
			$user = $db2->get_one(array('uid' => $uid));
			$agent = $this -> check_agent($user);
			if (!$agent) {
				echo json_encode(array('run' => 'no', 'msg' => '无权操作该账户！'));
				exit();
			}
			if ($state == 1 && $r['state'] == 0) {//转为处理中
				$state_htm = '<span style="color: #0070FF;">正在处理</span>';
				$endtime_htm = '--';
				$caozuo_htm = '<a href="javascript:;" onclick="showwindow(\''.ADMIN_PATH.'&c=cash&a=setstate&state=2&id='.$id.'\', \'确定转为《提现成功》吗？\', 1);">[成功]</a><a href="javascript:;" onclick="showwindow(\''.ADMIN_PATH.'&c=cash&a=setstate&state=3&id='.$id.'\', \'确定转为《提现失败》吗？<br/>对应申请金额将自动返还给申请账户！\', 1);">[失败]</a>';
				$update['state'] = 1;
				$isupdate = true;
			} elseif ($state == 2 && ($r['state'] == 1 || $r['state'] == 0)) {//转为成功
				$state_htm = '<span style="color: #00B520;">提现成功</span>';
				$endtime_htm = '1秒前';
				$caozuo_htm = '<a href="javascript:;" onclick="showwindow(\''.ADMIN_PATH.'&c=cash&a=del&id='.$id.'\', \'确定删除这条提现记录吗？<br/>注意：只能删除提现失败或者成功的数据！为方便用户查验，建议保留一定时间！\');">[删除]</a>';
				$update['state'] = 2;
				$update['endtime'] = SYS_TIME;
				$isupdate = true;
			} elseif ($state == 3 && ($r['state'] == 1 || $r['state'] == 0)) {//转为失败
				$state_htm = '<span style="color: #F00;">提现失败</span>';
				$endtime_htm = '1秒前';
				$caozuo_htm = '<a href="javascript:;" onclick="showwindow(\''.ADMIN_PATH.'&c=cash&a=del&id='.$id.'\', \'确定删除这条提现记录吗？<br/>注意：只能删除提现失败或者成功的数据！为方便用户查验，建议保留一定时间！\');">[删除]</a>';
				$update['state'] = 3;
				$update['endtime'] = SYS_TIME;
				$isupdate = true;
			} else {
				$isupdate = false;
			}

			if ($isupdate && $this -> db -> update($update, array('id' => $id))) {
				if ($update['state'] == 3) {//如果失败，退回金额
					$db2->update(array('money' => '+='.$money), array('uid' => $uid));
					//流水记录
					$countmoney = $user['money'] + $money;
					$db3->insert(array('uid'=>$uid, 'money'=>$money, 'countmoney'=>$countmoney, 'type'=>1, 'addtime'=>SYS_TIME, 'comment'=>'提现失败返还'));
				} elseif ($update['state'] == 2) {//提现成功，将金额返回给上级代理
					$db2->update(array('money' => '+='.$money), array('uid' => $agent['uid']));
					//流水记录
					$countmoney = $user['money'] + $money;
					$db3->insert(array('uid'=>$agent['uid'], 'money'=>$money, 'countmoney'=>$countmoney, 'type'=>1, 'addtime'=>SYS_TIME, 'comment'=>'会员提现-代理入账'));
				}
				echo json_encode(array('run' => 'yes','msg' => '操作成功！','id' => array(
					array('id' => 'state_'.$id,'htm' => $state_htm),
					array('id' => 'endtime_'.$id,'htm' => $endtime_htm),
					array('id' => 'caozuo_'.$id,'htm' => $caozuo_htm)
				)));
				exit();
			} else {
				echo json_encode(array('run' => 'no', 'msg' => '操作失败！'));
				exit();
			}
		} else {
			echo json_encode(array('run' => 'no', 'msg' => '未找到对应数据！'));
			exit();
		}
	}

	public function ajax_comment() {
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$comment = isset($_POST['param']) && trim($_POST['param']) ? safe_replace(trim($_POST['param'])) : '';
		if ($this -> db -> update(array('comment' => $comment), array('id' => $id))) {
			$msg['info'] = '已更新';
			$msg['status'] = 'y';
			$msgdel['msg'] = '已更新';
			$msgdel['run'] = 'yes';
		} else {
			$msg['info'] = '更新失败';
			$msg['status'] = 'n';
			$msgdel['msg'] = '更新失败';
			$msgdel['run'] = 'no';
		}
		if (trim($_GET['del'])) {
			echo json_encode($msgdel);
		} else {
			echo json_encode($msg);
		}
	}
}