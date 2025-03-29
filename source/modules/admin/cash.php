<?php
defined('IN_MYWEB') or exit('No permission resources.');
base::load_app_class('admin','admin',0);
class cash extends admin{

	private $db, $state;

	public function __construct() {
		parent::__construct();
		$this->db = base::load_model('cash_model');
		$this->state = array(
			0 => '<span style="color: #FFA700;">等待处理</span>',
			1 => '<span style="color: #0070FF;">正在处理</span>',
			2 => '<span style="color: #00B520;">提现成功</span>',
			3 => '<span style="color: #F00;">提现失败</span>'
		);
	}

	public function init() {
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $this->db->listinfo('', 'id DESC', $page, 15);
		$pages = $this->db->pages;
		base::load_sys_class('format', '', 0);
		include $this->admin_tpl('cash_list');
	}

	public function search() {
 		$where = '';
		if(is_array($_GET['search'])) extract($_GET['search']);
		$search_uid = intval($uid);
		$search_state = intval($state);
		$stateoption[$search_state] = 'selected="selected"';
		if($search_uid) $where .= $where ?  " AND uid='$search_uid'" : "uid='$search_uid'";
		if($search_state){
			if($search_state == 4) $search_state = 0;
			$where .= $where ?  " AND state='$search_state'" : "state='$search_state'";
		}
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $this->db->listinfo($where, 'id DESC', $page, 15);
 		$pages = $this->db->pages;
		base::load_sys_class('format', '', 0);
 		include $this->admin_tpl('cash_list');
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
					$db2 = base::load_model('user_model');
					$db3 = base::load_model('account_model');
					$money = $r['money'];
					$uid = $r['uid'];
					$user = $db2 -> get_one(array('uid' => $uid));
					$db2->update(array('money' => '+='.$money), array('uid' => $uid));
					//流水记录
					$countmoney = $user['money'] + $money;
					$db3->insert(array('uid'=>$uid, 'money'=>$money, 'countmoney'=>$countmoney, 'type'=>1, 'addtime'=>SYS_TIME, 'comment'=>'提现失败返还'));
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

	public function del() {
		$id = intval($_GET['id']);
		if (!$id) {
			echo json_encode(array('run' => 'no', 'msg' => '参数错误！'));
			exit();
		}
		$r = $this -> db -> get_one(array('id' => $id));
		if ($r && $r['state'] > 1) {
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

  public function counts() {
    base::load_sys_class('form', '', 0);
    $settingarr = $this -> get_settings(); //读取系统设置
    $cash_count = $this -> db -> count();

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
      $custom_count = $this -> go_cash_count($where);
      $custom = true;
    } else {
      //今日统计
      $starttime = strtotime(date('Y-m-d'));//今日0点
      $today_where = "addtime >= '$starttime'";
      $today_count = $this -> go_cash_count($today_where);
      //昨日统计
      $starttime = strtotime(date('Y-m-d')) - 86400;//昨日0点
      $endtime = strtotime(date('Y-m-d'));//今日0点
      $yesterday_where = "addtime >= '$starttime' AND addtime < '$endtime'";
      $yesterday_count = $this -> go_cash_count($yesterday_where);
      //本周统计
      //$starttime = mktime(0, 0 , 0, date('m'), date('d')-date('w')+1, date('Y'));//本周开始时间
      $starttime = strtotime(date('Y-m-d', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600)));//本周开始时间
      $tswk_where = "addtime >= '$starttime'";
      $tswk_count = $this -> go_cash_count($tswk_where);
      //本月统计
      $starttime = mktime(0, 0 , 0, date('m'), 1, date('Y'));//本月开始时间
      $thismonth_where = "addtime >= '$starttime'";
      $thismonth_count = $this -> go_cash_count($thismonth_where);
      //上月统计
      $starttime = mktime(0, 0 , 0, date('m')-1, 1, date('Y'));//上月开始时间
      $endtime = mktime(23,59,59,date('m') ,0,date('Y'));//上月结束时间
      $lastmonth_where = "addtime >= '$starttime' AND addtime < '$endtime'";
      $lastmonth_count = $this -> go_cash_count($lastmonth_where);
      //本季度统计
      $season = ceil((date('n'))/3);//当月是第几季度
      $starttime = mktime(0, 0, 0, $season*3-3+1, 1, date('Y'));//本季度开始时间
      $quarter_where = "addtime >= '$starttime'";
      $quarter_count = $this -> go_cash_count($quarter_where);
    }

    $statearr = array(
      -1 => '等待处理',
      1 => '正在处理',
      2 => '提现成功',
      3 => '提现失败'
    );
    include $this -> admin_tpl('cash_count');
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