<?php
defined('IN_MYWEB') or exit('No permission resources.');
base :: load_app_class('go', 'go', 0);
class index extends go {
	private $db, $db_account, $setting, $page, $mobile, $ismobile, $onlineip, $httphost;

	public function __construct() {
		parent :: __construct();
		$this -> httphost = array('fc3312.com','www.fc3312.com','fc9963.com','www.fc9963.com'); //域名限制
		$this -> setting = $this -> get_settings(); //读取系统设置
		$this -> db = base :: load_model('user_model');
		$this -> db_account = base :: load_model('account_model');
		$this -> ismobile = checkmobile();
		$this -> onlineip = get_onlineip();
		if ($this -> ismobile) {
			$this -> page = 3;
			$this -> mobile = 1;
		} else {
			$this -> page = 8;
			$this -> mobile = 0;
		}
		base :: load_sys_class('format', '', 0);
		base :: load_sys_class('form');
	}

	public function init() {//首页
		/*if (!$this -> ismobile && !get_cookie('app')) {
			set_cookie('app', 1, 7 * 86400);
			header('Location: ?a=app');
			exit;
		}*/
		$headername = $this -> setting['webname'];
		$title = '首页 - ' . $this -> setting['webname'];
		$keywords = $this -> setting['keywords'];
		$description = $this -> setting['description'];
		$user = $this -> check_user(); //检查登录
		// var_dump($user);exit;
		//获取游戏导航菜单
		$gonav = $this -> gonav('index', $user);
		$gameheadhtml = $gonav['gameheadhtml'];
		$agent_ann = isset($gonav['ann']) ? '<em>代理公告：</em>'.$gonav['ann'] : '';
		include template('index');
	}

	public function fantan() {//APP 番摊游戏引导页
		$headername = '番摊专场';
		$title = $headername.' - ' . $this -> setting['webname'];
		$keywords = $this -> setting['keywords'];
		$description = $this -> setting['description'];
		$user = $this -> check_user(); //检查登录
		//获取游戏导航菜单
		$gonav = $this -> gonav('fantan', $user);
		$gameheadhtml = $gonav['gameheadhtml'];
		include template('index_fantan');
	}

	public function game() {//游戏首页
		$headername = '在线下注';
		$title = $headername.' - ' . $this -> setting['webname'];
		$keywords = $this -> setting['keywords'];
		$description = $this -> setting['description'];
		$user = $this -> check_user(); //检查登录
		//获取游戏导航菜单
		$gonav = $this -> gonav('all', $user);
		$gamelist = $gonav['gamelist'];
		$gamedb = $gonav['gamedb'];
		$gameheadhtml = $gonav['gameheadhtml'];
		//优先取参数
		$gameid = intval($_GET['gameid']);
		if (!$gameid || !$gamelist[$gameid]) {//如果没有定义或者没有这个游戏
			//取COOKIE结果
			$gameid = intval(get_cookie('gameid')) ? intval(get_cookie('gameid')) : $gamedb[0]['id'];//将gameid设为查询结果的第一个
		}
			$gamename = $gamelist[$gameid]['name'];
			// var_dump($gamelist[$gameid]['template']);exit;
		//检查房间
		$room = base::load_config('room/room_'.$gameid);
		if(!empty($room)){
			$roomid = intval($_GET['roomid']);
			if(empty($_GET['roomid'])){
				include template('game_room');exit;
			}
			$roomid = $roomid-1;
			$roomConf = $room[$roomid];
			//金额限制
			if($roomConf['minimum'] > 0){
				if($user['money'] < $roomConf['minimum'] || $user['money'] > $roomConf['maximum']){
					echo '<script>alert("金额限制'.$roomConf['minimum'].'元-'.$roomConf['maximum'].'元");window.location.href="/?a=game&gameid='.$gameid.'"</script>';exit;
				}
			}
			
			$gamelist[$gameid]['data'] = serialize(array($roomConf['data']));
			
		}

		
		$gametemplate = $gamelist[$gameid]['template'];
		//存储在COOKIE备用
		set_cookie(array('gameid', 'gamename', 'gametemplate'), array($gameid, $gamename, $gametemplate));
		$fptime = $gamelist[$gameid]['fptime'];//提前封盘时间

		//处理游戏数据
		$miniheadhtml = '';
		// var_dump($gamelist[$gameid]['data']);exit;
		$dataarr = unserialize($gamelist[$gameid]['data']);
		// var_dump($dataarr);exit;
		$datalist = array();
		foreach($dataarr as $k => $data) {
			$datalist[($k+1)] = explode("\n", str_replace(array("\r\n", "\r"), "\n", $data));
		}
		$wanfadata = json_encode($datalist);
		//投注限制
		$send_money = empty($user['send_money']) ? $this -> setting['send_money'] : $user['send_money'];
		$title = $gamename.' - '.$title;
		$template = 'game_'.$gametemplate;
		if (file_exists('templates' . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . $template . '.html')) {
			include template($template);
		} else {
			include template('game_chat');
		}
	}

	public function haoma() {//开奖走势
		$gameid = intval(get_cookie('gameid'));
		$gamename = get_cookie('gamename');
		$gametemplate = get_cookie('gametemplate');
		if (!$gameid || !$gamename || !$gametemplate) {
			header('Location: ?a=init');
			exit;
		}
		$headername = '开奖记录 - '.$gamename;
		$title = $headername.' - ' . $this -> setting['webname'];
		$user = $this -> check_user(); //检查登录
		$win = empty($_GET['win']) ? '' : 'yes';
		$where = "gameid = '$gameid'";
		if (trim($_GET['daytime'])) {
			$daytime = trim($_GET['daytime']);
		} else {
			$daytime = date('Y-m-d', SYS_TIME);
		}
		$sendtime = strtotime($daytime);
		if(date('Y-m-d', SYS_TIME) == $daytime)
		$endtime = SYS_TIME;
			else
		$endtime = $sendtime + 86400;

		$where .= " AND (sendtime >= '$sendtime' AND sendtime < '$endtime') AND `is_lottery` = 1";

		$db = base::load_model('haoma_model');
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $db -> listinfo($where, 'sendtime DESC', $page, 60, 1, $this -> page, 0);
		$pages = $db -> pages;
		$haomadata = json_encode($list);
		$template = 'haoma_'.$gametemplate;
		if (file_exists('templates' . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . $template . '.html')) {
			include template($template);
		} else {
			include template('haoma');
		}
	}

	public function rules() {//游戏规则
		$headername = '游戏规则';
		$title = $headername.' - ' . $this -> setting['webname'];
		$user = $this -> check_user(); //检查登录
		$gametemplate = trim($_GET['gamename']);
		if (!$gametemplate) {
			$gameid = intval(get_cookie('gameid'));
			$gamename = get_cookie('gamename');
			$gametemplate = get_cookie('gametemplate');
			if (!$gameid || !$gamename || !$gametemplate) {
				header('Location: ?a=init');
				exit;
			}
		}
		include template('rules_'.$gametemplate);
	}

	public function order() {//注单
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			header('Location: ?a=login');
			exit;
		}
		$gameid = intval($_GET['gameid']);
		if ($gameid) {
			$db = base :: load_model('game_model');
			$gamedb = $db -> get_one(array('id' => $gameid, 'state' => 1));
			$gamename = $gamedb['name'];
		} else {
			$gameid = intval(get_cookie('gameid'));
			$gamename = get_cookie('gamename');
		}
		if (!$gameid || !$gamename) {
			header('Location: ?a=init');
			exit;
		}
		$headername = '我的注单 - '.$gamename;
		$title = $headername.' - ' . $this -> setting['webname'];
		$search = true;
		$where = "gameid = '$gameid' AND uid = '$user[uid]'";
		if(isset($_GET['state'])){
			$state = intval($_GET['state']);
			if($state == 1) {
				$starttime = strtotime(date('Y-m-d'));//今日0点
				$where .= " AND account <> 0 AND addtime >= '$starttime'";
			} else {
				$where .= " AND account = 0 AND tui = 0";
			}
			$search = false;
		} else {
			$starttime = trim($_GET['starttime']);
			$endtime = trim($_GET['endtime']);
			if (empty($starttime)) {//没有开始日期
				$starttime = date('Y-m-d');
			}
			$start_time = strtotime($starttime);
			$end_time = strtotime($endtime);
			if ($end_time && $end_time > $start_time) {//有选择结束日期
				$end_time = $end_time + 86400;
			} else {//选择当天
				$end_time = $start_time + 86400;
			}
			$where .= " AND (addtime >= '$start_time' AND addtime < '$end_time')";
			$qishu = safe_replace(trim($_GET['qishu']));
			if ($qishu) {//有选择日期
				$where .= " AND qishu = '$qishu'";
			}
			$orderid = safe_replace(trim($_GET['orderid']));
			if ($orderid) {//订单号
				$where .= " AND orderid like '%$orderid%'";
			}
		}
		$db = base::load_model('order_model');
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $db->listinfo($where, 'id DESC', $page, 50, 1, $this -> page, 0);
		$pages = $db->pages;
		$orderdata = json_encode($list);
		$count['ying'] = 0;
		$count['shu'] = 0;
		$count['ruzhang'] = 0;
		if ($search && $list) {//如果是，统计 有查到数据则统计 未结算的不统计
			$ying_where = $where. " AND account <> 0";
			$ying_count = $db -> query("SELECT SUM(money) AS money, SUM(account) AS account, SUM(CASE WHEN account > 0 THEN (account - money) ELSE account END) AS count FROM #@__order WHERE $ying_where ORDER BY id DESC", true);
			$count['count'] = round($ying_count['account'] - $ying_count['money'], 2);//得出的去掉成本的输赢总数
			$count['ruzhang'] = round($ying_count['count'], 2);
			$count['shu'] = round($count['count'] - $count['ruzhang'], 2);
			$count['ying'] = round($count['ruzhang'] - $count['shu'], 2);
		}
		include template('user_order');
	}

	public function account() {// 流水
		$headername = '我的资金流水';
		$title = $headername.' - ' . $this -> setting['webname'];
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			header('Location: ?a=login');
			exit;
		}
		$where = "uid = '$user[uid]'";
		$db = base::load_model('account_model');
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $db->listinfo($where, 'id DESC', $page, 50, 1, $this -> page, 0);
		$pages = $db->pages;
		$accountdata = json_encode($list);
		include template('user_account');
	}

	public function user(){// 会员中心
		$headername = '会员中心';
		$title = $headername.' - ' . $this -> setting['webname'];
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			header('Location: ?a=login');
			exit;
		}
		$start = strtotime(date('Y-m-d 00:00:00'));
		$ret = $this->db_account->querys("SELECT SUM(`money`) as money , `type` FROM `bc_account` where `addtime` >= {$start} AND `type` IN(2,3,4) AND `uid`={$user['uid']} GROUP BY `type`",1);
		$data = array();
		foreach ($ret as $key => $val) {
			$data[$val['type']] = $val['money'];
		}
		// var_dump($data);exit;
		include template('user');
	}

	public function user_edit(){// 修改资料
		$headername = '修改资料';
		$title = $headername.' - ' . $this -> setting['webname'];
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			header('Location: ?a=login');
			exit;
		}
		if (isset($_POST['dosubmit'])) { // 提交
			$nickname = safe_replace(trim($_POST['nickname']));
			$oldnickname = safe_replace(trim($_POST['oldnickname']));
			$err = false;
			if (strlen($nickname) > 30) {
				$err = true;
				$msg['info'] = '昵称长度为30字符内！';
			} elseif ($nickname && str_allexists($nickname, $this->setting['userfilter'])) {
				$err = true;
				$msg['info'] = '昵称包含系统禁用的字符！';
			} elseif ($nickname && $oldnickname != $nickname && $this -> db -> get_one(array('nickname' => $nickname))) {
				$err = true;
				$msg['info'] = '昵称已被使用！';
			}
			if ($err == true) {
				$msg['status'] = 'n';
			} else {
				$update['nickname'] = $nickname;
				if (empty($user['name'])) {
					$update['name'] = safe_replace(trim($_POST['name']));
				}
				if (empty($user['qq'])) {
					$update['qq'] = safe_replace(trim($_POST['qq']));
				}
				if (empty($user['mobile'])) {
					$update['mobile'] = safe_replace(trim($_POST['mobile']));
				}
				if (empty($user['bank'])) {
					$update['bank'] = safe_replace(trim($_POST['bank']));
				}
				if (empty($user['card'])) {
					$update['card'] = safe_replace(trim($_POST['card']));
				}
				if (empty($user['alipay'])) {
					$update['alipay'] = safe_replace(trim($_POST['alipay']));
				}
				if ($this -> db -> update($update, array('uid' => $user['uid']))) {
					$msg['info'] = '保存成功！';
					$msg['status'] = 'y';
				} else {
					$msg['info'] = '保存失败！';
					$msg['status'] = 'n';
				}
			}
			echo json_encode($msg);
			exit;
		}
		include template('user_edit');
	}

	public function user_pic(){// 修改头像
		$headername = '修改头像';
		$title = $headername.' - ' . $this -> setting['webname'];
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			header('Location: ?a=login');
			exit;
		}
		$msg = '{}';
		if (isset($_POST['dosubmit'])) { // 提交
			$err = false;
			//修改头像
			$dpic = intval($_POST['dpic']);
			if ($dpic > 0) {//如果有选择的图像
				$pic = $dpic;
			} elseif ($_FILES['file']['size']){//如果选择了上传图片
				$up = base::load_sys_class('upimg');
				$up->datedir = false;//不要添加日期目录
				$return = $up->up();
				if ($return['state'] == 'success') {
					$pic = $return['info'];
				} else {
					$err = true;
					$info['ico'] = 5;
					$info['info'] = $return['info'];
				}
			} else {
				$err = true;
				$info['ico'] = 3;
				$info['info'] = '请选择一张图片！';
			}
			if (!$err) {
				@unlink('./uppic/user/'.$user['pic']);//删除原来的图像
				if ($this -> db -> update(array('pic' => $pic), array('uid' => $user['uid']))) {
					$info['ico'] = 6;
					$info['info'] = '保存成功！';
				} else {
					$info['ico'] = 5;
					$info['info'] = '保存失败！';
				}
			}
			$msg = json_encode($info);
		}
		include template('user_pic');
	}

	public function user_pwd() {// 修改密码
		$headername = '修改密码';
		$title = $headername.' - ' . $this -> setting['webname'];
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			header('Location: ?a=login');
			exit;
		}
		if (isset($_POST['dosubmit'])) { // 提交
			$oldpassword = safe_replace(trim($_POST['oldpassword']));//旧密码
			$newpassword = safe_replace(trim($_POST['newpassword']));//新密码
			$err = false;
			if (strlen($newpassword) > 20 || strlen($newpassword) < 6) {
				$err = true;
				$msg['info'] = '密码长度为6-20字符！';
			} elseif (md5(md5($oldpassword) . $user['encrypt']) != $user['password']) {
				$err = true;
				$msg['info'] = '旧密码验证错误！';
			}
			if ($err == true) {
				$msg['status'] = 'n';
			} else {
				list($password, $encrypt) = creat_password($newpassword);
				if ($this -> db -> update(array('password' => $password, 'encrypt' => $encrypt), array('uid' => $user['uid']))) {
					$msg['info'] = '修改成功，请重新登录...';
					$msg['status'] = 'y';
				} else {
					$msg['info'] = '修改失败！';
					$msg['status'] = 'n';
				}
			}
			echo json_encode($msg);
			exit;
		}
		include template('user_pwd');
	}

	public function user_money_pwd() {// 修改密码
		$headername = '修改资金密码';
		$title = $headername.' - ' . $this -> setting['webname'];
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			header('Location: ?a=login');
			exit;
		}
		if (isset($_POST['dosubmit'])) { // 提交
			$oldpassword = safe_replace(trim($_POST['oldpassword']));//旧密码
			$newpassword = safe_replace(trim($_POST['newpassword']));//新密码
			$err = false;
			if (strlen($newpassword) > 20 || strlen($newpassword) < 6) {
				$err = true;
				$msg['info'] = '密码长度为6-20字符！';
			} elseif (!empty($user['money_password']) && md5(md5($oldpassword) . $user['money_encrypt']) != $user['money_password']) {
				$err = true;
				$msg['info'] = '旧密码验证错误！';
			}
			if ($err == true) {
				$msg['status'] = 'n';
			} else {
				list($password, $encrypt) = creat_password($newpassword);
				if ($this -> db -> update(array('money_password' => $password, 'money_encrypt' => $encrypt), array('uid' => $user['uid']))) {
					$msg['info'] = '修改成功！';
					$msg['status'] = 'y';
				} else {
					$msg['info'] = '修改失败！';
					$msg['status'] = 'n';
				}
			}
			echo json_encode($msg);
			exit;
		}
		include template('user_money_pwd');
	}

	public function user_nav() {// 注单记录 - 游戏选择
		$headername = '注单记录 - 游戏选择';
		$title = $headername.' - ' . $this -> setting['webname'];
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			header('Location: ?a=login');
			exit;
		}
		$db = base :: load_model('game_model');
		$gamedb = $db -> select(array('state' => 1), '*', '', 'sort ASC, id DESC');//查询出所有已开启的游戏
		include template('user_nav');
	}
	
		public function qqqqqq() {// 注单记录 - 游戏选择
		$headername = '设置';
		$title = $headername.' - ' . $this -> setting['webname'];
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			header('Location: ?a=login');
			exit;
		}
		$db = base :: load_model('game_model');
		$gamedb = $db -> select(array('state' => 1), '*', '', 'sort ASC, id DESC');//查询出所有已开启的游戏
		include template('qqqqqq');
	}

	public function dating() {// 注单记录 - 游戏选择
		$headername = '注单记录 - 游戏选择';
		$title = $headername.' - ' . $this -> setting['webname'];
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			header('Location: ?a=login');
			exit;
		}
		$db = base :: load_model('game_model');
		$gamedb = $db -> select(array('state' => 1), '*', '', 'sort ASC, id DESC');//查询出所有已开启的游戏
		include template('dating');
	}

	public function user1() {// 注单记录 - 游戏选择
		$headername = '注单记录 - 游戏选择';
		$title = $headername.' - ' . $this -> setting['webname'];
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			header('Location: ?a=login');
			exit;
		}
		$db = base :: load_model('game_model');
		$gamedb = $db -> select(array('state' => 1), '*', '', 'sort ASC, id DESC');//查询出所有已开启的游戏
		include template('user1');
	}

	public function pay() {// 充值
		$headername = '账户充值';
		$title = $headername.' - ' . $this -> setting['webname'];
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			header('Location: ?a=login');
			exit;
		}
		//查询上级代理 判断代理属性
		$agents = 0;
		$user['agent'] = 0;
		if ($user['agent']) {//有上级代理
			$agent_db = $this -> db -> get_one("aid > 0 AND uid = '$user[agent]'");
			if ($agent_db['aid'] == 3) {//如果是二级代理(阅) 则写入该代理上级
				//$agents = $user['agents'] ? $user['agents'] : 0;//如果是二级代理(阅) 则写入该代理上级 (不采用是为了避免上级代理升级后数据依然上报)
				$agents = $agent_db['agent'];
				//重新查询上级代理
				$agent_db = $this -> db -> get_one("aid = 1 AND uid = '$agents'");
				//查不到一直向上查
				if(!$agent_db){
					while(true){
					$agent_db = $this -> db -> get_one("aid > 0 AND uid = '$agents'");
					$agents = $agent_db['agent'];
                    if(!$agent_db) break;
			        if ($agent_db['aid'] == 1) {//如果是二级代理(阅) 则写入该代理上级
					//var_dump( $agent_db);
					//	exit;
					break;
				  }
			  }
			}
			}
		}
		if (isset($_POST['dosubmit'])) { // 提交
			$money = round(trim($_POST['money']), 2);//金额
			$comment = safe_replace(trim($_POST['comment']));
			if ($money < $this -> setting['pay']) {
				$msg['info'] = '输入的金额低于最低充值金额！请重新输入。';
				$msg['status'] = 'n';
				echo json_encode($msg);
				exit;
			}
			$uid = $user['uid'];
			$payid = date('YmdHis',SYS_TIME).random(6, '1234567890');//日期加随机订单号
			$db = base::load_model('pay_model');
			$paydb = array(
				'uid' => $uid,
				'agent' => $user['agent'],
				'agents' => $agents,
				'payid' => $payid,
				'money' => $money,
				'state' => 0,
				'addtime' => SYS_TIME,
				'comment' => $comment
			);
			if ($db -> insert($paydb)) {//创建订单
				$msg['payid'] = $payid;
				//$msg['info'] = '订单创建成功！请按照平台提示的方式进行支付。';
				$msg['info'] = '订单创建成功123123！请等待确认。';
				$msg['status'] = 'y';
			} else {
				$msg['info'] = '订单创建失败！';
				$msg['status'] = 'n';
			}
			echo json_encode($msg);
			exit;
		}
		if ($user['agent']) {//有上级代理
			if (!$agent_db) {
				$ewm_tps = '<p>很抱歉，您的上级代理已经失效，请联系客服</p>';
			} else {
				$config = unserialize($agent_db['agentconfig']);
			}
		} else {//没有上级 调用系统设置
			$config['wxewm'] = $this -> setting['wxewm'];
			$config['aliewm'] = $this -> setting['aliewm'];
			$config['card'] = $this -> setting['card'];
		}
		$config['wxewm'] = $this -> setting['wxewm'];
		$config['aliewm'] = $this -> setting['aliewm'];
		$config['card'] = $this -> setting['card'];
		if (empty($config['wxewm']) && empty($config['aliewm']) && empty($config['card'])) {
			$ewm_tps = '<p>很抱歉，您的上级代理尚未上传收款资料</p>';
		} else {
			// if ($config['wxewm']) {
			// 	$ewm = '<li class="a"><img src="uppic/ewm/'.$config['wxewm'].'" alt="wxewm" /></li>';
			// 	$btn = '<a class="a" href="javascript:;">微信</a>';
			// }
			// if ($config['aliewm']) {
			// 	if (empty($config['wxewm'])) $class = ' class="a"';
			// 	$ewm .= '<li'.$class.'><img src="uppic/ewm/'.$config['aliewm'].'" alt="aliewm" /></li>';
			// 	$btn .= '<a'.$class.' href="javascript:;">数字货币</a>';
			// }
			if ($config['card']) {
				if (empty($config['wxewm']) && empty($config['aliewm'])) $class_card = ' class="a"';
				$ewm .= '<li style="display: block;" '.$class_card.'><div>'.nl2br($config['card']).'</div></li>';
				$btn .= '<a'.$class_card.' href="javascript:;">银行卡</a>';
			}
		}
		include template('user_pay');
	}

	public function pay_ewm() {// 充值二维码
		$headername = '充值二维码';
		$title = $headername.' - ' . $this -> setting['webname'];
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			header('Location: ?a=login');
			exit;
		}
		$payid = safe_replace(trim($_GET['payid']));
		$payid = substr($payid, 0, -6).'<em>'.substr($payid, -6).'</em>';
		$user['agent'] = 0;
		if ($user['agent']) {//有上级代理
			$agent_db = $this -> db -> get_one("aid > 0 AND uid = '$user[agent]'");
			if ($agent_db['aid'] == 3) {//如果是二级代理(阅) 则查询该代理上级
				$agent_db = $this -> db -> get_one("aid = 1 AND uid = '$agent_db[agent]'");
			}
			/*if (!$agent_db) {
				$ewm_tps = '<p>很抱歉，您的上级代理已经失效，请联系客服</p>';
			} else {
				$config = unserialize($agent_db['agentconfig']);
			}*/
		} else {//没有上级 调用系统设置
			$config['wxewm'] = $this -> setting['wxewm'];
			$config['aliewm'] = $this -> setting['aliewm'];
			$config['card'] = $this -> setting['card'];
		}
		$config['wxewm'] = $this -> setting['wxewm'];
		$config['aliewm'] = $this -> setting['aliewm'];
		$config['card'] = $this -> setting['card'];
		if (empty($config['wxewm']) && empty($config['aliewm']) && empty($config['card'])) {
			$ewm_tps = '<p>很抱歉，您的上级代理尚未上传收款资料</p>';
		} else {
			// if ($config['wxewm']) {
			// 	$ewm = '<li class="a"><img src="uppic/ewm/'.$config['wxewm'].'" alt="wxewm" /></li>';
			// 	$btn = '<a class="a" href="javascript:;">微信</a>';
			// }
			// if ($config['aliewm']) {
			// 	if (empty($config['wxewm'])) $class = ' class="a"';
			// 	$ewm .= '<li'.$class.'><img src="uppic/ewm/'.$config['aliewm'].'" alt="aliewm" /></li>';
			// 	$btn .= '<a'.$class.' href="javascript:;">数字货币</a>';
			// }
			if ($config['card']) {
				if (empty($config['wxewm']) && empty($config['aliewm'])) $class_card = ' class="a"';
				$ewm .= '<li style="display: block;" '.$class_card.'><div>'.nl2br($config['card']).'</div></li>';
				$btn .= '<a'.$class_card.' href="javascript:;">银行卡</a>';
			}
		}
		include template('user_pay_ewm');
	}

	public function pay_list() {// 充值记录
		$headername = '充值记录';
		$title = $headername.' - ' . $this -> setting['webname'];
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			header('Location: ?a=login');
			exit;
		}
		$where = "uid = '$user[uid]'";
		$db = base::load_model('pay_model');
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $db->listinfo($where, 'id DESC', $page, 15);
		$pages = $db->pages;
		$paydata = json_encode($list);
		include template('user_pay_list');
	}

	public function cash() {// 提现
		$headername = '提款申请';
		$title = $headername.' - ' . $this -> setting['webname'];
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			header('Location: ?a=login');
			exit;
		}
		if(empty($user['money_password'])){
			header('Location: ?a=user_money_pwd');
			exit;
		}
		if (isset($_POST['dosubmit'])) { // 提交
			$money = round(trim($_POST['money']), 2);//金额
			$account = intval($_POST['account']);
			$comment = safe_replace(trim($_POST['comment']));
			if ($account == 1) {//提现到银行卡
				if (empty($user['name']) || empty($user['bank']) || empty($user['card'])) {
					$msg['info'] = '您的姓名或银行卡信息不完整，请先修改资料填写完整！';
					$msg['status'] = 'n';
					echo json_encode($msg);
					exit;
				}
				$from = $user['name'].' '.$user['bank'].'：'.$user['card'];
			} else {//提现数字货币
				if (empty($user['name']) || empty($user['alipay'])) {
					$msg['info'] = '您的姓名或数字货币未填写，请先修改资料填写完整';
					$msg['status'] = 'n';
					echo json_encode($msg);
					exit;
				}
				$from = $user['name'].' 数字货币：'.$user['alipay'];
			}

			$money_password = $_POST['money_password'];
			if (md5(md5($money_password) . $user['money_encrypt']) != $user['money_password']) {
				$msg['info'] = '资金密码错误！';
				$msg['status'] = 'n';
				echo json_encode($msg);
				exit;
			}
			if ($money > $user['money']) {
				$msg['info'] = '提现金额超过您目前的资金，请重新填写！';
				$msg['status'] = 'n';
				echo json_encode($msg);
				exit;
			}
			if ($money > $this -> setting['maxcash']) {
				$msg['info'] = '单笔提现金额超过最大限制，请重新填写！';
				$msg['status'] = 'n';
				echo json_encode($msg);
				exit;
			}
			//jarde
			$start_time = strtotime(date("Y-m-d {$this->setting['cash_limit_start_time']}"));
			$end_time = strtotime(date("Y-m-d {$this->setting['cash_limit_end_time']}"));
			if(time() > $end_time || time() < $start_time){
				$msg['info'] = $this->setting['pay_error_remind'];
				$msg['status'] = 'n';
				echo json_encode($msg);
				exit;
			}
			if($user['dama'] < $user['aims_dama']){
				$msg['info'] = '打码量不足';
				$msg['status'] = 'n';
				echo json_encode($msg);
				exit;
			}
			$uid = $user['uid'];
			if ($this -> db -> update(array('money' => '-='.$money), array('uid' => $uid))) {//用户资金减
				$db = base::load_model('cash_model');
				$db2 = base::load_model('account_model');
				if (str_exists($this -> setting['cash'], '%')) {//百分比收费
					$service = round($money * rtrim($this -> setting['cash'] / 100, '%'), 2);
				} else {//单笔收费
					$service = round($this -> setting['cash'], 2);
				}
				//提现记录
				//查询上级代理 判断代理属性
				$agents = 0;
				if ($user['agent']) {//有上级代理
					//$agents = $user['agents'] ? $user['agents'] : 0;//如果是二级代理(阅) 则写入该代理上级 (不采用是为了避免上级代理升级后数据依然上报)
					$agent_db = $this -> db -> get_one("aid > 0 AND uid = '$user[agent]'");
					if ($agent_db['aid'] == 3) {//如果是二级代理(阅) 则写入该代理上级
						$agents = $agent_db['agent'];
					}
				}
				$db -> insert(array('uid'=>$uid, 'agent' => $user['agent'], 'agents' => $agents, 'money'=>$money, 'service'=>$service, 'from'=>$from, 'state'=>0, 'addtime'=>SYS_TIME, 'comment'=>$comment));
				//流水记录
				$moneydb = 0 - $money;
				$countmoney = $user['money'] - $money;
				$db2 -> insert(array('uid'=>$uid, 'money'=>$moneydb, 'countmoney'=>$countmoney, 'type'=>1, 'addtime'=>SYS_TIME, 'comment'=>'提现申请'));
				$msg['info'] = '提现申请成功！请耐心等待申请处理结果。';
				$msg['status'] = 'y';
			} else {
				$msg['info'] = '提现操作失败！';
				$msg['status'] = 'n';
			}
			echo json_encode($msg);
			exit;
		}
		include template('user_cash');
	}

	public function cash_list() {// 提现记录
		$headername = '提款记录';
		$title = $headername.' - ' . $this -> setting['webname'];
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			header('Location: ?a=login');
			exit;
		}
		$where = "uid = '$user[uid]'";
		$db = base::load_model('cash_model');
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $db->listinfo($where, 'id DESC', $page, 15);
		$pages = $db->pages;
		$cashdata = json_encode($list);
		include template('user_cash_list');
	}

	public function appkeydetection($urlarr) {// 域名检查
		$url = $_SERVER['HTTP_HOST'];
		$authorization = true;
		foreach($urlarr as $v) {
			if (strpos($url,$v) !== false) $authorization = false;
		}
		return $authorization;
	}

	public function login() {// 用户登录
		if ($this -> appkeydetection($this -> httphost)) {//地址数组
			//exit('Unauthorized');
		}
		$headername = '会员登录';
		$title = $headername.' - ' . $this -> setting['webname'];
		$keywords = $this -> setting['keywords'];
		$description = $this -> setting['description'];
		$user = $this -> check_user(); //检查登录
		if ($user) {
			header('Location: '.WEB_PATH);
			exit;
		}
		if (isset($_POST['dosubmit'])) { // 提交
			if (!$this -> check_code($_POST['code']) && false) { // 验证码错误
				$msg['info'] = '验证码错误或已过期！';
				$msg['status'] = 'n';
			} else {
				$username = trim($_POST['username']);
				$password = trim($_POST['password']);
				$user = $this -> check_user($username, $password);
				if ($user) {
					$msg['info'] = '登录成功，正在跳转...';
					$msg['status'] = 'y';
				} else {
					$msg['info'] = '账户或密码错误！';
					$msg['status'] = 'n';
				}
			}
			echo json_encode($msg);
			exit;
		}
		include template('login');
	}

	public function register() {// 注册
		$headername = '注册会员';
		$title = $headername.' - ' . $this -> setting['webname'];
		$keywords = $this -> setting['keywords'];
		$description = $this -> setting['description'];
		$user = $this -> check_user(); //检查登录
		if ($user) {//如果已经登录，跳转
			header('Location: '.WEB_PATH);
			exit;
		}
		if (isset($_POST['dosubmit'])) { // 提交
			if (!$this -> check_code($_POST['code'])) { // 验证码错误
				$msg['info'] = '验证码错误或已过期！';
				$msg['status'] = 'n';
			} else {
				$username = safe_replace(trim($_POST['username']));
				$name = safe_replace(trim($_POST['name']));
				$password = safe_replace(trim($_POST['password']));//密码
				$agent = safe_replace(trim($_POST['agent']));//代理人
				
				$err = false;
				$namelen = @iconv_strlen($name,'UTF-8');//姓名的个数
				if ($namelen > 5 || $namelen < 2) {
					$err = true;
					$msg['info'] = '姓名应该为2-5个字！';
				} elseif (strlen($password) > 20 || strlen($password) < 6) {
					$err = true;
					$msg['info'] = '密码长度为6-20字符！';
				} elseif (str_allexists($username, $this->setting['userfilter'])) {
					$err = true;
					$msg['info'] = '用户名包含系统禁用的字符！';
				} elseif ($this -> db -> get_one(array('username' => $username))) {
					$err = true;
					$msg['info'] = '用户名已被注册！';
				}
				//检查代理正确性
				$agent_uid = 0;
				if(!empty($agent)){
					$agent_db = $this -> db -> get_one("aid > 0 AND uid = '$agent'");
					if (!$agent_db) {
						$err = true;
						$msg['info'] = '请填写正确的邀请码！';
					}
					//查询上级代理 判断代理属性
					if ($agent_db['aid'] == 3) {//如果是二级代理(阅) 则写入该代理上级
						$send['agents'] = $agent_db['agent'];
					}
					$agent_uid = $agent_db['uid'];
				}
				
				if ($err == true) {
					$msg['status'] = 'n';
				} else {
					//新用户注册送金额
					$gift = unserialize(urldecode($this->setting['gift']));
					$register_gift = 0;
					if(!empty($gift['registerMoneyMin']) && !empty($gift['registerMoneyMax'])){
						$register_gift = intval(mt_rand($gift['registerMoneyMin'],$gift['registerMoneyMax']));
					}
					

					list($newpassword, $encrypt) = creat_password($password);
					$money = round($this->setting['money']+$register_gift, 2);
					// var_dump($register_gift);exit;
					$send['money'] = $money;
					$send['username'] = $username;
					$send['name'] = $name;
					$send['password'] = $newpassword;
					$send['encrypt'] = $encrypt;
					$send['agent'] = $agent_uid;
					$send['regtime'] = SYS_TIME;
					$send['free_dama'] = $this->setting['init_dama'];
					$send['dama'] = $this->setting['init_dama'];
					$send['aims_dama'] = $money;
					if ($this -> db -> insert($send)) {
						//新用户注册送金额
						if($register_gift > 0){
							$uid = $this->db->insert_id();
							$this->db_account->insert(array(
								'uid'=>$uid,
								'money'=>$register_gift,
								'countmoney'=>$money,
								'type'=>5,
								'addtime'=>SYS_TIME,
								'comment'=>'新用户注册送金额'
							));
						}


						$msg['info'] = '注册成功，即将跳转至登录页面...';
						$msg['status'] = 'y';
					} else {
						$msg['info'] = '注册失败！';
						$msg['status'] = 'n';
					}
				}
			}
			echo json_encode($msg);
			exit;
		}
		include template('register');
	}

	public function logout() {// 退出登录
		set_cookie('uid');
		set_cookie('password');
		header('Location: ?a=login');
		exit;
	}

	public function kefu() {// 客服
		/*$headername = '在线客服';
		$title = $headername.' - ' . $this -> setting['webname'];
		$keywords = $this -> setting['keywords'];
		$description = $this -> setting['description'];
		include template('kefu');*/
		header('Location: '.$this -> setting['kefulink']);exit;
		//exit;
	}

	public function app() {// APP下载
		header('Location: /');exit;
		$headername = '客户端 APP';
		$title = $headername.' - ' . $this -> setting['webname'];
		$keywords = $this -> setting['keywords'];
		$description = $this -> setting['description'];
		$user = $this -> check_user(); //检查登录
		include template('app');
	}

	public function gonav($type = 'all', $user = array()) {// 输出导航
		$game_open = array();
		//$agent = $user['agents'] ? $user['agents'] : $user['agent'];//如果是二级代理(阅) 则写入该代理上级 (不采用是为了避免上级代理升级后数据依然上报)
		$agent = $user['agent'];
		if ($agent) {//如果拥有上级代理
			$agent_db = $this -> db -> get_one("aid > 0 AND uid = '$agent'", 'aid,agent,agentconfig');//取得代理配置数据
			if ($agent_db['aid'] == 3) {//如果是二级代理(阅) 则写入该代理上级
				//重新查询
				$agent_db = $this -> db -> get_one("aid > 0 AND uid = '$agent_db[agent]'", 'aid,agent,agentconfig');
			}
			if ($agent_db) {//存在这个代理
				$config = unserialize($agent_db['agentconfig']);
				$game_open = $config['gameid'];
				$gonav['ann'] = $config['ann'];
			}
		}
		$db = base :: load_model('game_model');
		$where = "state = 1";
		if ($game_open) {
			$where .= " AND id IN (" . implode(",", $game_open) . ")";
		}
		switch ($type){
			case 'index':
				$where .= " AND template != 'fantan'";
				break;
			case 'fantan':
				$where .= " AND template = 'fantan'";
				break;
			default:
		}
		$gamedb = $db -> select($where, '*', '', 'sort ASC, id DESC');//查询出所有已开启的游戏
		$gameheadhtml = '';
		$gamelist = array();
		foreach($gamedb as $k => $game) {
			$gamelist[$game['id']] = $game;//重写数组
			$gameheadhtml .= '<li><a class="nav_game_'.$game['id'].'" href="?a=game&gameid='.$game['id'].'">'.$game['name'].'<span>'.$game['template'].'</span></a></li>';//游戏导航
		}
		$gonav['gamelist'] = $gamelist;
		$gonav['gamedb'] = $gamedb;
		$gonav['gameheadhtml'] = $gameheadhtml;
		return $gonav;
	}

	public function ajax_code() {// 检查验证码
		header('Content-Type: application/json; charset=utf-8');
		$code = isset($_POST['param']) && trim($_POST['param']) ? trim($_POST['param']) : '';
		if ($this -> check_code($code)) {
			$msg['info'] = '验证码输入正确';
			$msg['status'] = 'y';
		} else {
			$msg['info'] = '验证码错误或已过期！';
			$msg['status'] = 'n';
		}
		echo json_encode($msg);
		exit;
	}

	public function ajax_nickname() {// 检查昵称是否可用
		header('Content-Type: application/json; charset=utf-8');
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			$msg['info'] = '尚未登录！';
			$msg['status'] = 'n';
			echo json_encode($msg);
		}
		$nickname = isset($_POST['param']) && trim($_POST['param']) ? safe_replace(trim($_POST['param'])) : '';
		$oldnickname = isset($_POST['oldnickname']) && trim($_POST['oldnickname']) ? safe_replace(trim($_POST['oldnickname'])) : '';
		if (!$nickname || ($oldnickname != $nickname && $this -> db -> get_one(array('nickname' => $nickname)))) {
			$msg['info'] = '昵称已被使用！';
			$msg['status'] = 'n';
		} else {
			if (str_allexists($nickname, $this->setting['userfilter'])) {
				$msg['info'] = '昵称名包含系统禁用的字符！';
				$msg['status'] = 'n';
			} else {
				$msg['info'] = '昵称可用！';
				$msg['status'] = 'y';
			}
		}
		echo json_encode($msg);
	}

	public function ajax_username() {// 检查用户名是否可用 准许未登录时检查
		header('Content-Type: application/json; charset=utf-8');
		$username = isset($_POST['param']) && trim($_POST['param']) ? safe_replace(trim($_POST['param'])) : '';
		if (!$username || $this -> db -> get_one(array('username' => $username))) {
			$msg['info'] = '用户名已被注册！';
			$msg['status'] = 'n';
		} else {
			if (str_allexists($username, $this->setting['userfilter'])) {
				$msg['info'] = '用户名包含系统禁用的字符！';
				$msg['status'] = 'n';
			} else {
				$msg['info'] = '用户名可用！';
				$msg['status'] = 'y';
			}
		}
		echo json_encode($msg);
	}

	public function ajax_gomoney() {// 刷新金额 今日盈利
		header('Content-Type: application/json; charset=utf-8');
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			$msg['err'] = 'y';
			echo json_encode($msg);
			exit;
		}
		$msg['err'] = 'n';
		//今日输赢统计
		/*
		$db = base::load_model('order_model');
		$starttime = strtotime(date('Y-m-d'));//今日0点
		$ying_where = "uid = '$user[uid]' AND account <> 0 AND addtime >= '$starttime'";
		$ying_count = $db -> query("SELECT SUM(CASE WHEN account > 0 THEN (account - money) ELSE account END) AS count FROM #@__order WHERE $ying_where ORDER BY id DESC", true);
		$ying = round($ying_count['count'], 2);
		$msg['ying'] = $ying;
		*/
		$msg['money'] = $user['money'];
		echo json_encode($msg);
		exit;
	}

	public function ajax_goorder() {// 提取我的注单
		header('Content-Type: application/json; charset=utf-8');
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			$msg['err'] = 'y';
			$msg['msg'] = '尚未登录！';
			echo json_encode($msg);
			exit;
		}
		$gameid = intval($_GET['gameid']);
		$qishu = safe_replace(trim($_POST['qishu']));
		$where = array(
			'uid' => $user['uid'],
			'gameid' => $gameid,
			'qishu' => $qishu
		);
		$db = base :: load_model('order_model');
		$orderlist = $db -> select($where, 'money,wanfa,addtime', 5, 'addtime DESC,id DESC');
		$msg['order'] = $orderlist;
		$msg['err'] = 'n';
		$msg['msg'] = '成功！';
		echo json_encode($msg);
		exit;
	}

	public function ajax_chat_order() {// 提取最新注单
		header('Content-Type: application/json; charset=utf-8');
		$gameid = intval($_GET['gameid']);
		$qishu = safe_replace(trim($_POST['qishu']));
		$id = intval($_POST['id']);
		$all = intval($_POST['all']);
		$db = base :: load_model('order_model');
		if ($id) {
			$where = " AND id > '$id'";
		}
		$orderlist = $db -> select("gameid = '$gameid' AND qishu = '$qishu' AND tui = 0$where", 'id,uid,money,wanfa,addtime', ($all ? '' : 5), 'id DESC');
		if ($orderlist) {
			foreach ($orderlist as $key => $value) {
				$orderlist[$key]['user'] = $this -> get_user($value['uid']);
			}
			$msg['id'] = $orderlist[0]['id'];
		} else {
			$msg['id'] = 0;
		}
		$msg['order'] = $orderlist;
		$msg['err'] = 'n';
		$msg['msg'] = '成功！';
		echo json_encode($msg);
		exit;
	}

	public function get_user($uid) {// 获取用户资料
		$udb = $this -> db -> get_one(array('uid' => $uid));
		return $udb;
	}

	public function ajax_gohaomalist() {// 提取开奖历史
		header('Content-Type: application/json; charset=utf-8');
		$gameid = intval($_GET['gameid']);
		$db = base :: load_model('haoma_model');
		/*if($gameid==13 || $gameid==14){
		  $time =SYS_TIME;
		  $gamelist = $db -> select("gameid = '$gameid' AND haoma != '' AND sendtime <='$time'", 'qishu,haoma', 20, 'sendtime DESC');
		}else{
		  $gamelist = $db -> select("gameid = '$gameid' AND haoma != ''", 'qishu,haoma', 20, 'id DESC');
		}*/
		$gamelist = $db -> select("gameid = '$gameid' AND haoma != '' AND is_lottery = 1", 'qishu,haoma', 20, 'id DESC');

		$msg['order'] = $gamelist;
		$msg['err'] = 'n';
		$msg['msg'] = '成功！';
		echo json_encode($msg);
		exit;
	}

	public function ajax_touzhu() {// 游戏投注
		header('Content-Type: application/json; charset=utf-8');
		$user = $this -> check_user(); //检查登录
		if (!$user) {
			$msg['info'] = '您尚未登录！请登录后再操作。';
			$msg['status'] = 'n';
			$msg['login'] = 'y';
			echo json_encode($msg);
			exit;
		}
		$msg['login'] = 'n';
		if ($this -> setting['stop']) {
			$msg['info'] = '抱歉，系统维护，暂停投注，请关注游戏公告。';
			$msg['status'] = 'n';
			echo json_encode($msg);
			exit;
		}
		if (isset($_POST['dosubmit'])) { // 提交
			$gameid = intval($_POST['gameid']);
			$gamename = safe_replace(trim($_POST['gamename']));
			$qishu = safe_replace(trim($_POST['qishu']));
			$wanfa = safe_replace(trim($_POST['wanfa']));
			$money = round($_POST['money'], 2);
			$sum = safe_replace(trim($_POST['sum']));
			if (!$gameid || !$gamename || !$qishu || !$wanfa) {
				$msg['info'] = '参数异常！';
				$msg['status'] = 'n';
				echo json_encode($msg);
				exit;
			}
			//投注限制
			$send_money = $sum ? $sum : (empty($user['send_money']) ? $this -> setting['send_money'] : $user['send_money']);
			$send_arr = explode('-', $send_money);
			if ($money < $send_arr[0] || $money > $send_arr[1]) {
				$msg['info'] = '请输入合规的金额！';
				$msg['status'] = 'n';
				echo json_encode($msg);
				exit;
			}
			$agents = 0;
			if ($user['agent']) {//有上级代理
				//$agents = $user['agents'] ? $user['agents'] : 0;//如果是二级代理(阅) 则写入该代理上级 (不采用是为了避免上级代理升级后数据依然上报)
				$agent_db = $this -> db -> get_one("aid > 0 AND uid = '$user[agent]'");
				if ($agent_db['aid'] == 3) {//如果是二级代理(阅) 则写入该代理上级
					$agents = $agent_db['agent'];
				}
			}
			$uid = $user['uid'];
			$agent = $user['agent'];
			$db = base :: load_model('game_model');
			$gamedb = $db -> get_one("id = '$gameid' AND state = 1", '*', 'id DESC');
			$db1 = base::load_model('haoma_model');

			if(in_array($gameid,array(13,14,25,26))){
				$second = array(
					13 => 150,
					14 => 90,
					25 => 180,
					26 => 300
				);
				$fixno = array(
					13 => '188579',
					14 => '238579',
					25 => '190579',
					26 => '190579'
				);
				$StartTime = array(
					13 => '2018-06-20',
					14 => '2018-06-20',
					25 => '2019-08-18',
					26 => '2019-08-18'
				);
				//期数获取
				$time = SYS_TIME;
				$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
				$curtime = time()- $beginToday;
				$curqishu =  intval($curtime / $second[$gameid]) ;
				$nexqishu =  $curqishu+1 ;
				$curqishutime  = $curqishu* $second[$gameid];
				$nexqishutime =  $curqishutime + $second[$gameid];
				$fixno = $fixno[$gameid]; //定义一个期数
				$daynum = floor(($time-strtotime($StartTime[$gameid]." 00:00:00"))/3600/24);
				$lastno = ($daynum-1)*576 + $fixno;

				//开奖号码
				$nextqishu = $lastno+$curqishu+1;
				$nextdb = $db1 -> get_one("gameid = '$gameid' AND qishu = '$nextqishu'", 'qishu, sendtime', 'id DESC');

			}else{
			   $nextdb = $db1 -> get_one("gameid = '$gameid' AND haoma = ''", 'qishu, sendtime', 'id DESC');
			}
			if (SYS_TIME > $nextdb['sendtime'] - $gamedb['fptime'] || $nextdb['qishu'] != $qishu) {
				$msg['info'] = '小样，你觉得这样就能下注了？';
				$msg['status'] = 'n';
				echo json_encode($msg);
				exit;
			}
			$db2 = base::load_model('order_model');
			$time = date('YmdHis',SYS_TIME);
			$ban = 0;

			//房间控制赔率
			$room = base::load_config('room/room_'.$gameid);
			if(!empty($room)){
				$roomid = intval($_POST['roomid']);
				if(empty($roomid)){
					$msg['info'] = '清选择房间进行下注';
					$msg['status'] = 'n';
					echo json_encode($msg);
					exit;
				}
				$roomid = $roomid-1;
				$roomConf = $room[$roomid];
				//金额限制
				if($user['money'] < $roomConf['minimum'] || $user['money'] > $roomConf['maximum']){
					$msg['info'] = '金额限制'.$roomConf['minimum'].'元-'.$roomConf['maximum'].'元';
					$msg['status'] = 'n';
					echo json_encode($msg);
					exit;
				}
				if(!empty($roomConf['minBetting']) && $money < $roomConf['minBetting']){
					$msg['info'] = '该房间最低下注'.$roomConf['minBetting'].'元';
					$msg['status'] = 'n';
					echo json_encode($msg);
					exit;
				}
				$gamedb['data'] = serialize(array($roomConf['data']));
				
			}
			$dataarr = unserialize($gamedb['data']);
			if ($gameid == 11) {//牌九
				$topnum = $db2 -> count("gameid = '$gameid' AND qishu = '$qishu' AND tui = 0 AND wanfa = '$wanfa@庄'");
				if ($topnum > 0) {
					$msg['info'] = '当前投注玩法已经有玩家上庄，不能投注';
					$msg['status'] = 'n';
					echo json_encode($msg);
					exit;
				}
				//查询是否有人上庄
				$top_num = $db2 -> count("gameid = '$gameid' AND qishu = '$qishu' AND tui = 0 AND wanfa like '%@庄%'");
				if ($top_num > 0) {
					//查询庄在哪一门
					$men_db = $db2 -> get_one("gameid = '$gameid' AND qishu = '$qishu' AND tui = 0 AND wanfa like '%@庄%'", 'wanfa', 'id ASC');
					$men = intval($men_db['wanfa']);
					$count_1 = $db2 -> query("SELECT SUM(money) AS money FROM #@__order WHERE tui = 0 AND gameid = '$gameid' AND qishu = '$qishu' AND wanfa like '%$men%' ORDER BY id DESC", true);
					$topmoney = intval($count_1['money']);
					$count_2 = $db2 -> query("SELECT SUM(money) AS money FROM #@__order WHERE tui = 0 AND gameid = '$gameid' AND qishu = '$qishu' AND wanfa not like '%$men%' ORDER BY id DESC", true);
					$allmoney = intval($count_2['money']);
					if ($allmoney >= $topmoney) {
						$msg['info'] = '已有玩家上庄，总投注金额已达到限制，不能投注';
						$msg['status'] = 'n';
						echo json_encode($msg);
						exit;
					}
					$symoney = $topmoney - $allmoney;
					if ($money > $symoney) {
						$msg['info'] = '已有玩家上庄，总投注金额不能超限，当前还能投注：'.$symoney;
						$msg['status'] = 'n';
						echo json_encode($msg);
						exit;
					}
				}
				if (intval($_POST['top'])) {//上庄
					if ($top_num > 0) {
						$msg['info'] = '当期已经有玩家上庄，不能继续上庄';
						$msg['status'] = 'n';
						echo json_encode($msg);
						exit;
					}
					$where = "gameid = '$gameid' AND qishu = '$qishu' AND tui = 0 AND wanfa <> '$wanfa'";
					$count = $db2 -> query("SELECT SUM(money) AS money FROM #@__order WHERE $where ORDER BY id DESC", true);
					$new_money = intval($count['money']) + 20000;
					if ($money < $new_money) {
						$msg['info'] = '当前上庄金额至少'.$new_money;
						$msg['status'] = 'n';
						echo json_encode($msg);
						exit;
					}
					$wanfa = $wanfa.'@庄';
				}
				$dbmoney = $money;
				$orderid = $time.random(6, '1234567890');//日期加随机订单号
				$sql = "('$uid', '$agent', '$agents', '$orderid', '$gameid', '$qishu', '$money', '$wanfa', ".SYS_TIME.", '$ban')";
			} else {
				$peilv = explode("\n", str_replace(array("\r\n", "\r"), "\n", $dataarr[0]));
				$sql = '';
				$dbmoney = 0;
				$wanfa_arr = explode('|', $wanfa);
				foreach($wanfa_arr as $wf) {//注单重组
					$arr = explode('@', $wf);
					$wanfa = $arr[1].'@'.$arr[2].(isset($arr[3]) && $arr[3] ? '@'.$arr[3] : '');
					$pl = $sum ? $arr[0].'@'.$arr[2].'@'.$sum : $arr[0].'@'.$arr[2];
					if (in_array($pl, $peilv)) {//检查赔率是否和后台设置一致 赔率正确
						$orderid = $time.random(6, '1234567890');//日期加随机订单号
						$sql .= "('$uid', '$agent', '$agents', '$orderid', '$gameid', '$qishu', '$money', '$wanfa', ".SYS_TIME.", '$ban'),";
						$dbmoney = bcadd($dbmoney, $money, 2);
					}
				}
				$sql = rtrim($sql, ',');
			}
			if ($user['money'] < $dbmoney) {//金额不足
				$msg['info'] = '下注失败！您当前的金额不足！';
				$msg['status'] = 'n';
				echo json_encode($msg);
				exit;
			}
			if ($sql && $db2 -> insert($sql, false, false, '(uid, agent, agents, orderid, gameid, qishu, money, wanfa, addtime, ban)')) {//创建订单
				$db3 = base::load_model('account_model');
				//用户金额减
				$this -> db -> update(array('money' => '-='.$dbmoney), array('uid' => $uid));
				//流水记录
				$moneydb = 0 - $dbmoney;
				$countmoney = $user['money'] - $dbmoney;
				$comment = $gamename.' '.$qishu.'期 注单'.$time.'...投注';
				$db3 -> insert(array('uid'=>$uid, 'money'=>$moneydb, 'countmoney'=>$countmoney, 'type'=>2, 'addtime'=>SYS_TIME, 'comment'=>$comment));
				$this -> db -> update(array('dama' => '+='.$dbmoney), array('uid' => $uid));
				$msg['info'] = '下注成功！祝君中奖！';
				$msg['status'] = 'y';
			} else {
				$msg['info'] = '下注失败！';
				$msg['status'] = 'n';
			}
			echo json_encode($msg);
		}
		exit;
	}

	public function ajax_haoma() {// 最近的一期开奖数据及下一期数据 全局通用
		header('Content-Type: application/json; charset=utf-8');
		$gameid = intval($_GET['gameid']);
		$db = base :: load_model('haoma_model');
		if(in_array($gameid,array(13,14,25,26))){
			$second = array(
				13 => 150,
				14 => 90,
				25 => 180,
				26 => 300
			);
			$fixno = array(
				13 => '188579',
				14 => '238579',
				25 => '190579',
				26 => '190579'
			);
			$StartTime = array(
				13 => '2018-06-20',
				14 => '2018-06-20',
				25 => '2019-08-18',
				26 => '2019-08-18'
			);


			//期数获取
			$time = SYS_TIME;
			$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$curtime = time()- $beginToday;
			$curqishu =  intval($curtime / $second[$gameid]) ;
			$nexqishu =  $curqishu+1 ;
			$curqishutime  = $curqishu* $second[$gameid];
			$nexqishutime =  $curqishutime + $second[$gameid];
			$fixno = $fixno[$gameid]; //定义一个期数
			$daynum = floor(($time-strtotime($StartTime[$gameid]." 00:00:00"))/3600/24);
			$lastno = ($daynum-1)*576 + $fixno;

						//开奖号码
			$openqishu = $lastno+$curqishu;

			$haoma_db = $db -> select(array('gameid' => $gameid,'qishu' => $openqishu), '*', 1, 'id DESC');

			$haomadata['time'] = SYS_TIME;
			$haomadata['id'] = $haoma_db[0]['id'];
			$haomadata['gameid'] = $haoma_db[0]['gameid'];
			$haomadata['qishu'] = $haoma_db[0]['qishu'];
			$haomadata['sendtime'] = $haoma_db[0]['sendtime'];
			$haomadata['haoma'] = $haoma_db[0]['haoma'];
			$haomadata['nextqishu'] = $openqishu+1;
			$haomadata['nextsendtime'] = $beginToday + $nexqishutime;
			$haomadata['awartime'] = $haomadata['nextsendtime'] - $haomadata['time'];
			//$haomadata['awartime'] = 0;
			$haomadata['re_max'] = 30;
			$haomadata['awartime'] = $haomadata['awartime'] < 0 ? 0 : $haomadata['awartime'] * 1000;
			if ($haomadata['awartime'] == 0 && $haomadata['re_max']) {//如果已到开奖时间
				//依据开奖延迟时间合理设置请求频率
				if (intval($_GET['re'])) {//如果不是第一次请求
					$haomadata['re'] = 5 * 1000;//返回下一次请求的时间
				} else {
					$haomadata['re'] = $haomadata['re_max'] * 1000;//返回下一次请求的时间
				}
			}
			echo json_encode($haomadata);
		}else{
			$haoma_db = $db -> select(array('gameid' => $gameid), '*', 2, 'id DESC');
			$haomadata['time'] = SYS_TIME;
			$haomadata['id'] = $haoma_db[1]['id'];
			$haomadata['gameid'] = $haoma_db[1]['gameid'];
			$haomadata['qishu'] = $haoma_db[1]['qishu'];
			$haomadata['sendtime'] = $haoma_db[1]['sendtime'];
			$haomadata['haoma'] = $haoma_db[1]['haoma'];
			$haomadata['nextqishu'] = $haoma_db[0]['qishu'];
			$haomadata['nextsendtime'] = $haoma_db[0]['sendtime'];
			$haomadata['awartime'] = $haomadata['nextsendtime'] - $haomadata['time'];
			//$haomadata['awartime'] = 0;
			$haomadata['re_max'] = 30;
			$haomadata['awartime'] = $haomadata['awartime'] < 0 ? 0 : $haomadata['awartime'] * 1000;
			if ($haomadata['awartime'] == 0 && $haomadata['re_max']) {//如果已到开奖时间
				//依据开奖延迟时间合理设置请求频率
				if (intval($_GET['re'])) {//如果不是第一次请求
					$haomadata['re'] = 5 * 1000;//返回下一次请求的时间
				} else {
					$haomadata['re'] = $haomadata['re_max'] * 1000;//返回下一次请求的时间
				}
			}
			echo json_encode($haomadata);
		}
		exit;
	}

}

?>