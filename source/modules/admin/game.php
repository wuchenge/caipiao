<?php
defined('IN_MYWEB') or exit('No permission resources.');
base :: load_app_class('admin', 'admin', 0);
class game extends admin {

	private $db, $db2;

	public function __construct() {
		parent :: __construct(1);
		$this -> db = base :: load_model('game_model');
		$this -> db2 = base :: load_model('haoma_model');
	}

	public function init() {
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $this -> db -> listinfo('', 'id ASC', $page, 15);
		$pages = $this -> db -> pages;
		$state = array(0 => '<span class="redled"></span>', 1 => '<span class="greenled"></span>');
		include $this -> admin_tpl('game_list');
	}
	
		public function peilv() {
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $this -> db -> listinfo('', 'id ASC', $page, 15);
		$pages = $this -> db -> pages;
		$state = array(0 => '<span class="redled"></span>', 1 => '<span class="greenled"></span>');
		include $this -> admin_tpl('game_peilv');
	}

	public function room() {
		$gameid = isset($_GET['gameid']) && intval($_GET['gameid']) ? intval($_GET['gameid']) : showmessage('参数错误！', HTTP_REFERER);
		$game = $this->db->get_one(array('id' => $gameid));
		$game['data'] = unserialize($game['data']);
		$game['data'] = $game['data'][0];
		$room = base::load_config('room/room_'.$gameid);
		if(isset($_POST['dosubmit'])){
			$data = $_POST;
			unset($data['dosubmit']);
			$roomConf = array();
			foreach ($data as $key => $val) {
				foreach ($val as $ke => $va) {
					$roomConf[$ke][$key] = $va;
				}
			}
			if(!empty($_FILES)){
				$up = base::load_sys_class('upimg');
				$up->datedir = false;//不要添加日期目录
				$up->dir = 'room';
				$up->thumb = 0;
				foreach ($roomConf as $key => &$value) {
					// $value['data'] = new_addslashes(serialize(array($value['data'])));
					$imgKey = $key+1;
					if(!empty($_FILES['new_img_'.$imgKey]['name'])){
						$up->filename = 'new_img_'.$imgKey;
						$ret = $up->up();
						if($ret['state'] == 'success'){
							$value['img'] = $ret['info'];
						}else{
							showmessage($ret['info'], HTTP_REFERER);
						}
					}else{
						$value['img'] = $value['old_img'];
					}
				}
				// 写入本地文件
				write_config($roomConf, 'room/room_'.$gameid.'.php');
				showmessage('修改成功！', HTTP_REFERER);
			}
		}else{
			include $this -> admin_tpl('game_room');
		}
	}

	public function haoma() {
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$gameid = intval($_GET['gameid']);
		// $list = $this -> db2 -> listinfo(array('gameid' => $gameid), 'sendtime DESC', $page, 15);
		$list = $this -> db2 -> listinfo(array('gameid' => $gameid), 'qishu DESC', $page, 15);
		$pages = $this -> db2 -> pages;
		$account = array(0 => '--', 1 => '<span style="color: #005AFF">是</span>');
		base::load_sys_class('format', '', 0);
		include $this -> admin_tpl('game_haoma_list');
	}

	public function haoma_add() {
		$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : showmessage('参数错误！', HTTP_REFERER);
		$data = $this -> db2 -> get_one(array('id' => $id));
		if ($data && $data['haoma'] == '') {
			if (isset($_POST['dosubmit'])) {
				$haoma = isset($_POST['haoma']) && trim($_POST['haoma']) ? safe_replace(trim($_POST['haoma'])) : showmessage('请输入开奖号码！', HTTP_REFERER);
				if ($this -> db2 -> update(array('haoma' => $haoma), array('id' => $id))) {
					showmessage('补号成功！', 'c=game&a=init');
				} else {
					showmessage('补号失败！', HTTP_REFERER);
				}
			}
			include $this -> admin_tpl('game_haoma_add');
		} else {
			showmessage('未找到对应数据！', HTTP_REFERER);
		}
	}
	public function haoma_inset() {
		$gameid = isset($_GET['gameid']) && intval($_GET['gameid']) ? intval($_GET['gameid']) : showmessage('参数错误！', HTTP_REFERER);		
		if (isset($_POST['dosubmit'])) {
				$haoma = isset($_POST['haoma']) && trim($_POST['haoma']) ? safe_replace(trim($_POST['haoma'])) : showmessage('请输入开奖号码！', HTTP_REFERER);
				$qishu = isset($_POST['qishu']) && trim($_POST['qishu']) ? safe_replace(trim($_POST['qishu'])) : showmessage('请输入期数！', HTTP_REFERER);
				
				if($gameid ==13){ //土耳其(番)					
					$current = $this -> db2 -> get_one(array('gameid' => $gameid, 'qishu' => $qishu));
					if ($current) {//存在当期记录,更新号码						     
						    $content['current']['gameid'] = $gameid;//游戏ID
							$content['current']['qishu']  = $qishu;//插入期号
						//	$content['current']['sendtime'] = intval($qishu - $openqishu) * 150 + $beginToday;//插入期开奖的时间
							$content['current']['haoma'] = $haoma;//插入开奖号码
							if ($this -> db2 -> update($content['current'], array('gameid' => $gameid, 'qishu' => $qishu))) {//更新
							showmessage('更新号码成功！', 'c=game&a=init');
							} else {
								showmessage('更新号码失败！', HTTP_REFERER);
						}
						
					} else {//当期记录没有
					 
					    //期数获取
						$time = SYS_TIME;
						$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
						$curtime = time()- $beginToday;
						$curqishu =  intval($curtime / 150) ;
						$nexqishu =  $curqishu+1 ;
						$curqishutime  = $curqishu* 150;
						$nexqishutime =  $curqishutime + 150;
						$fixno = '188579'; //定义一个期数
						$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
						$lastno = ($daynum-1)*576 + $fixno;
						
						//开奖号码
						$openqishu = $lastno+$curqishu;
					    if($qishu<$openqishu){
							showmessage('期数小于当前期数', HTTP_REFERER);
						}						 
						$content['current']['gameid'] = $gameid;//游戏ID
					    $content['current']['qishu']  = $qishu;//插入期号
						$content['current']['sendtime'] = ($qishu - $openqishu) *150 + $beginToday + $curqishutime;//插入期开奖的时间
						$content['current']['haoma'] = $haoma;//插入开奖号码
					
						if ($this -> db2 -> insert($content['current'])) {
						showmessage('插入号码成功！', 'c=game&a=init');
						} else {
							showmessage('插入号码失败！', HTTP_REFERER);
						}
					}
					
					
				}
		else if($gameid ==14){ //极速时时彩(番)					
					$current = $this -> db2 -> get_one(array('gameid' => $gameid, 'qishu' => $qishu));
					if ($current) {//存在当期记录,更新号码						     
						    $content['current']['gameid'] = $gameid;//游戏ID
							$content['current']['qishu']  = $qishu;//插入期号
						//	$content['current']['sendtime'] = intval($qishu - $openqishu) * 150 + $beginToday;//插入期开奖的时间
							$content['current']['haoma'] = $haoma;//插入开奖号码
							if ($this -> db2 -> update($content['current'], array('gameid' => $gameid, 'qishu' => $qishu))) {//更新
							showmessage('更新号码成功！', 'c=game&a=init');
							} else {
								showmessage('更新号码失败！', HTTP_REFERER);
						}
						
					} else {//当期记录没有
					
					    //期数获取
						$time = SYS_TIME;
						$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
						$curtime = time()- $beginToday;
						$curqishu =  intval($curtime / 90) ;
						$nexqishu =  $curqishu+1 ;
						$curqishutime  = $curqishu* 90;
						$nexqishutime =  $curqishutime + 90;
						$fixno = '238579'; //定义一个期数
						$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
						$lastno = ($daynum-1)*960 + $fixno;
						
						//开奖号码
						$openqishu = $lastno+$curqishu;
					    if($qishu<$openqishu){
							showmessage('期数小于当前期数', HTTP_REFERER);
						}						 
						$content['current']['gameid'] = $gameid;//游戏ID
					    $content['current']['qishu']  = $qishu;//插入期号
						$content['current']['sendtime'] = ($qishu - $openqishu) *150 + $beginToday + $curqishutime;//插入期开奖的时间
						$content['current']['haoma'] = $haoma;//插入开奖号码
					
						if ($this -> db2 -> insert($content['current'])) {
						showmessage('插入号码成功！', 'c=game&a=init');
						} else {
							showmessage('插入号码失败！', HTTP_REFERER);
						}
					}
		}
	}else
		include $this -> admin_tpl('game_haoma_inset');
	}


	public function haoma_inset_ajax() {
		$gameid = isset($_GET['gameid']) && intval($_GET['gameid']) ? intval($_GET['gameid']) : exit(json_encode(array('info' => '参数错误！','status' => 0)));
		if (!empty($_POST)) {
			$haoma = isset($_POST['haoma']) && trim($_POST['haoma']) ? safe_replace(trim($_POST['haoma'])) : exit(json_encode(array('info' => '请输入开奖号码！','status' => 0)));
			$qishu = isset($_POST['qishu']) && trim($_POST['qishu']) ? safe_replace(trim($_POST['qishu'])) : exit(json_encode(array('info' => '请输入期数！','status' => 0)));

			$time = SYS_TIME;
			$second = 150;
			switch ($gameid){
				case '6': //pcdd (f)
					$second = 1200;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '739424'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*43 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '13': //pcdd (f)
					$second = 150;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '188579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '14': //jsssc f
					$second = 90;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '238579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '25': //3fpc
					$second = 180;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '190579'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-08-18'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '26':
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '190579'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-08-18'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					break;
					case '27': //北京28
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '995870'; //定义一个期数
					$daynum = floor(($time-strtotime('2020-01-21'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*288 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '28': //加拿大28
					$second = 210;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '2532528'; //定义一个期数
					$daynum = floor(($time-strtotime('2020-02-10'." 19:21:54"))/3600/24);
					$lastno = ($daynum-1)*394 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '29': //新西兰28
					$second = 150;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '198579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '30': 
					$second = 180;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '198579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '31': //新加坡28
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '198579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '32':
					$second = 1200;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '20200121059'; //定义一个期数
					$daynum = floor(($time-strtotime('2020-01-21'."23:50:47"))/3600/24);
					$lastno = ($daynum-1)*59 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '33': 
					$second = 1200;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '200120001'; //定义一个期数
					$daynum = floor(($time-strtotime('2020-01-20'." 10:20:40"))/3600/24);
					$lastno = ($daynum-1)*49 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
					case '34': 
					$second = 1200;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '200120001'; //定义一个期数
					$daynum = floor(($time-strtotime('2020-01-20'." 09:20:30"))/3600/24);
					$lastno = ($daynum-1)*42 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
					case '35': 
					$second = 90;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '238579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*960 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
					case '36': 
					$second = 180;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '238579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*960 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
					case '37':
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '238579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*960 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '42': 
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '739424'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*43 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '41': 
					$second = 180;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '739424'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*43 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '40': 
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '739424'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*43 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '39': 
					$second = 1200;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '744176'; //定义一个期数
					$daynum = floor(($time-strtotime('2020-01-20'." 09:32:34"))/3600/24);
					$lastno = ($daynum-1)*44 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '38': 
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '20200209001'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-09-27'." 13:09:53"))/3600/24);
					$lastno = ($daynum-1)*180 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
					
					case '43': 
					$second = 90;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '238579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*960 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '46': //新加坡11x5
					$second = 180;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '238579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*960 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '47': //幸运11X5
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '238579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*960 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '44': //台湾k3
					$second = 90;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '198579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '48': //新加坡k3
					$second = 180;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '198579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '49': //幸运k3
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '198579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '45': //快乐十分
					$second = 90;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '739424'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*43 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '50': //新加坡快乐十分
					$second = 180;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '739424'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*43 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '51': //幸运快乐十分
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '739424'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*43 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '52': //快乐8
					$second = 90;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '739424'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*43 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '53': //极速六合彩
					$second = 120;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '50000'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*960 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '54': //幸运六合彩
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '50000'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*960 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '55': //极速六合彩
					$second = 120;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '2020009'; //定义一个期数
					$daynum = floor(($time-strtotime('2020-02-04'." 21:30:00"))/3600/24);
					$lastno = ($daynum-1)*0.5 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
			}
			$current = $this -> db2 -> get_one(array('gameid' => $gameid, 'qishu' => $qishu));
			if($current){
				if ($this -> db2 -> update($content['current'], array('gameid' => $gameid, 'qishu' => $qishu))) {//更新
					exit(json_encode(array(
						'info' 	 => '更新号码成功！',
						'status' => 1
					)));
				} else {
					exit(json_encode(array(
						'info' 	 => '更新号码失败！',
						'status' => 0
					)));
				}
			}else{
				$content['current']['is_lottery'] = 0;
				if ($this -> db2 -> insert($content['current'])) {
					exit(json_encode(array(
							'info' 	 => '添加号码成功！',
							'status' => 1
						)));
				} else {
					exit(json_encode(array(
							'info' 	 => '添加号码失败！',
							'status' => 0
						)));
				}
			}
		}
	}

	public function haoma_insets_ajax() {
		$gameid = isset($_GET['gameid']) && intval($_GET['gameid']) ? intval($_GET['gameid']) : exit(json_encode(array('info' => '参数错误！','status' => 0)));
		if (!empty($_POST)) {
			$num = isset($_POST['num']) && trim($_POST['num']) ? safe_replace(trim($_POST['num'])) : exit(json_encode(array('info' => '请输入批量个数！','status' => 0)));
			$qishu = isset($_POST['qishu']) && trim($_POST['qishu']) ? safe_replace(trim($_POST['qishu'])) : exit(json_encode(array('info' => '请输入期数！','status' => 0)));
			$time = SYS_TIME;
			$second = 150;
			for ($i=0; $i <= $num; $i++) {
			$haoma = $this->random_haoma($gameid);
			if(!$haoma) exit(json_encode(array('info' => '参数错误！','status' => 0)));
			switch ($gameid){
				case '6': //pk10
					$second = 1200;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '739424'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*43 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '13': //pcdd (f)
					$second = 150;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '188579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '14': //jsssc f
					$second = 90;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '238579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '25': //3fpc
					$second = 180;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '190579'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-08-18'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '26':
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '190579'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-08-18'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					break;
				case '27': //北京28
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '995870'; //定义一个期数
					$daynum = floor(($time-strtotime('2020-01-21'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*288 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '28': //加拿大28
					$second = 210;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '2532528'; //定义一个期数
					$daynum = floor(($time-strtotime('2020-02-10'." 19:21:54"))/3600/24);
					$lastno = ($daynum-1)*394 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '29': //新西兰28
					$second = 60;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '198579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '30': //28
					$second = 180;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '198579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '31': //新加坡28
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '198579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '32': 
					$second = 1200;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '20200121059'; //定义一个期数
					$daynum = floor(($time-strtotime('2020-01-21'."23:50:47"))/3600/24);
					$lastno = ($daynum-1)*59 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '33': 
					$second = 1200;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '200120001'; //定义一个期数
					$daynum = floor(($time-strtotime('2020-01-20'." 10:20:40"))/3600/24);
					$lastno = ($daynum-1)*49 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
					case '34': 
					$second = 1200;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '200120001'; //定义一个期数
					$daynum = floor(($time-strtotime('2020-01-20'." 09:20:30"))/3600/24);
					$lastno = ($daynum-1)*42 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
					case '35': //时时彩
					$second = 90;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '238579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*960 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
					case '36': 
					$second = 180;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '238579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*960 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
					case '37': 
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '238579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*960 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '42': 
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '739424'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*43 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '41': 
					$second = 180;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '739424'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*43 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '40': //台湾PK10
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '739424'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*43 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '39': 
					$second = 1200;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '744176'; //定义一个期数
					$daynum = floor(($time-strtotime('2020-01-20'." 09:32:34"))/3600/24);
					$lastno = ($daynum-1)*44 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '38':
				    $second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '20200209001'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-09-27'." 13:09:53"))/3600/24);
					$lastno = ($daynum-1)*180 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
					
					case '43': //台湾11x5
					$second = 90;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '238579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*960 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '46': //新加坡11x5
					$second = 180;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '238579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*960 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '47': //幸运11X5
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '238579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*960 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '44': //台湾k3
					$second = 90;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '198579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '48': //新加坡k3
					$second = 180;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '198579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '49': //幸运k3
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '198579'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*576 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '45': //台湾快乐十分
					$second = 90;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '739424'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*43 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '50': //新加坡快乐十分
					$second = 180;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '739424'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*43 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '51': //幸运快乐十分
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '739424'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*43 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '52': //快乐8
					$second = 90;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '739424'; //定义一个期数
					$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*43 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '53': //极速六合彩
					$second = 120;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '50000'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*960 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '54': //幸运六合彩
					$second = 300;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '50000'; //定义一个期数
					$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
					$lastno = ($daynum-1)*960 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
				case '55': //极速六合彩
					$second = 120;
					$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
					$curtime = time()- $beginToday;
					$curqishu =  intval($curtime / $second) ;
					$nexqishu =  $curqishu+1 ;
					$curqishutime  = $curqishu* $second;
					$nexqishutime =  $curqishutime + $second;
					$fixno = '2020009'; //定义一个期数
					$daynum = floor(($time-strtotime('2020-02-04'." 21:30:00"))/3600/24);
					$lastno = ($daynum-1)*0.5 + $fixno;
					
					//开奖号码
					$openqishu = $lastno+$curqishu;
				    if($qishu<$openqishu){
						showmessage('期数小于当前期数', HTTP_REFERER);
					}						 
					$content['current']['gameid'] = $gameid;//游戏ID
				    $content['current']['qishu']  = $qishu;//插入期号
					$content['current']['sendtime'] = ($qishu - $openqishu) *$second + $beginToday + $curqishutime;//插入期开奖的时间
					$content['current']['yukaihaoma'] = $haoma;//插入开奖号码
					
					break;
			}
			$current = $this -> db2 -> get_one(array('gameid' => $gameid, 'qishu' => $qishu));
			if($current){
				if (!$this -> db2 -> update($content['current'], array('gameid' => $gameid, 'qishu' => $qishu))) {//更新
					exit(json_encode(array(
						'info' 	 => '批量添加号码失败！',
						'status' => 1
					)));
				}
			}else{
				$content['current']['is_lottery'] = 0;
				if (!$this -> db2 -> insert($content['current'])) {
					exit(json_encode(array(
						'info' 	 => '批量添加号码失败！',
						'status' => 1
					)));
				}
			}
			$qishu++;
			}
			exit(json_encode(array(
				'info' 	 => '批量添加号码成功！',
				'status' => 1
			)));
		}
	}

	public function haoma_update_ajax(){
		$gameid = isset($_GET['gameid']) && intval($_GET['gameid']) ? intval($_GET['gameid']) : exit(json_encode(array('info' => '参数错误！','status' => 0)));
		if (!empty($_POST)) {
			$id = isset($_POST['id']) && trim($_POST['id']) ? safe_replace(trim($_POST['id'])) : exit(json_encode(array('info' => '选择要修改的预开奖！','status' => 0)));
			$haoma = isset($_POST['haoma']) && trim($_POST['haoma']) ? safe_replace(trim($_POST['haoma'])) : exit(json_encode(array('info' => '请输入期数！','status' => 0)));

			$current = $this -> db2 -> get_one(array('gameid' => $gameid, 'id' => $id));
			if ($current) {//存在当期记录,更新号码
				    $content['current']['gameid'] = $gameid;//游戏ID
					//$content['current']['sendtime'] = intval($qishu - $openqishu) * 150 + $beginToday;//插入期开奖的时间
					$content['current']['haoma'] = $haoma;//插入开奖号码
					if ($this -> db2 -> update($content['current'], array('gameid' => $gameid, 'id' => $id))) {//更新
						exit(json_encode(array(
							'info' 	 => '更新号码成功！',
							'status' => 1
						)));
					// showmessage('更新号码成功！', 'c=game&a=init');
					} else {
						exit(json_encode(array(
							'info' 	 => '更新号码失败！',
							'status' => 0
						)));
						// showmessage('更新号码失败！', HTTP_REFERER);
					}
			}
		}
	}

	public function random_haoma_ajax() {
		$gameid = $_POST['gameid'];
		exit(json_encode($this->random_haoma($gameid)));
	}

	public function random_haoma($gameid) {
		$gameConf = array(
			27 => array(
				"num" => array("0","1", "2", "3", "4", "5","6","7","8", "9","0","1", "2", "3", "4", "5","6","7","8", "9"),
				"sum" => 3
			),
			28 => array(
				"num" => array("0","1", "2", "3", "4", "5","6","7","8", "9","0","1", "2", "3", "4", "5","6","7","8", "9"),
				"sum" => 3
			),
			29 => array(
				"num" => array("0","1", "2", "3", "4", "5","6","7","8", "9","0","1", "2", "3", "4", "5","6","7","8", "9"),
				"sum" => 3
			),
			30 => array(
				"num" => array("0","1", "2", "3", "4", "5","6","7","8", "9","0","1", "2", "3", "4", "5","6","7","8", "9"),
				"sum" => 3
			),
			31 => array(
				"num" => array("0","1", "2", "3", "4", "5","6","7","8", "9","0","1", "2", "3", "4", "5","6","7","8", "9"),
				"sum" => 3
			),
			32 => array(
				"num" => array("0","1", "2", "3", "4", "5","6","7","8", "9","0","1", "2", "3", "4", "5","6","7","8", "9"),
				"sum" => 5
			),
			33 => array(
				"num" => array("0","1", "2", "3", "4", "5","6","7","8", "9","0","1", "2", "3", "4", "5","6","7","8", "9"),
				"sum" => 5
			),
			34 => array(
				"num" => array("0","1", "2", "3", "4", "5","6","7","8", "9","0","1", "2", "3", "4", "5","6","7","8", "9"),
				"sum" => 5
			),
			35 => array(
				"num" => array("0","1", "2", "3", "4", "5","6","7","8", "9","0","1", "2", "3", "4", "5","6","7","8", "9"),
				"sum" => 5
			),
			36 => array(
				"num" => array( "0","1", "2", "3", "4", "5","6","7","8", "9","0","1", "2", "3", "4", "5","6","7","8", "9"),
				"sum" => 5
			),
			37 => array(
				"num" => array( "0","1", "2", "3", "4", "5","6","7","8", "9","0","1", "2", "3", "4", "5","6","7","8", "9"),
				"sum" => 5
			),
			14 => array(
				"num" => array( "0","1", "2", "3", "4", "5","6","7","8", "9"),
				"sum" => 5
			),
			25 => array(
				"num" => array( "0","1", "2", "3", "4", "5","6","7","8", "9"),
				"sum" => 3
			),
			26 => array(
				"num" => array( "0","1", "2", "3", "4", "5","6","7","8", "9"),
				"sum" => 3
			),
			42 => array(
				"num" => array( "10","1", "2", "3", "4", "5","6","7","8", "9"),
				"sum" => 10
			),
			41 => array(
				"num" => array( "10","1", "2", "3", "4", "5","6","7","8", "9"),
				"sum" => 10
			),
			40 => array(
				"num" => array( "10","1", "2", "3", "4", "5","6","7","8", "9"),
				"sum" => 10
			),
			39 => array(
				"num" => array( "10","1", "2", "3", "4", "5","6","7","8", "9"),
				"sum" => 10
			),
			38 => array(
				"num" => array( "10","1", "2", "3", "4", "5","6","7","8", "9"),
				"sum" => 10
			),
			43 => array(
				"num" => array("10","1", "2", "3", "4", "5","6","7","8", "9", "11"),
				"sum" => 5
			),
			46 => array(
				"num" => array( "10","1", "2", "3", "4", "5","6","7","8", "9", "11"),
				"sum" => 5
			),
			47 => array(
				"num" => array( "10","1", "2", "3", "4", "5","6","7","8", "9", "11"),
				"sum" => 5
			),
			44 => array(
				"num" => array("1", "2", "3", "4", "5","6","1", "2", "3", "4", "5","6","1", "2", "3", "4", "5","6"),
				"sum" => 3
			),
			48 => array(
				"num" => array( "1", "2", "3", "4", "5","6","1", "2", "3", "4", "5","6","1", "2", "3", "4", "5","6"),
				"sum" => 3
			),
			49 => array(
				"num" => array( "1", "2", "3", "4", "5","6","1", "2", "3", "4", "5","6","1", "2", "3", "4", "5","6"),
				"sum" => 3
			),
			45 => array(
				"num" => array("1", "2", "3", "4", "5","6","7","8", "9","10","11", "12", "13", "14", "15","16","17","18", "19","20"),
				"sum" => 8
			),
			50 => array(
				"num" => array( "1", "2", "3", "4", "5","6","7","8", "9","10","11", "12", "13", "14", "15","16","17","18", "19","20"),
				"sum" => 8
			),
			51 => array(
				"num" => array("1", "2", "3", "4", "5","6","7","8", "9","10","11", "12", "13", "14", "15","16","17","18", "19","20"),
				"sum" => 8
			),
			52 => array(
				"num" => array( "1", "2", "3", "4", "5","6","7","8", "9","10"),
				"sum" => 8
			),
		    53 => array(
				"num" => array( "1", "2", "3", "4", "5","6","7","8", "9","10","11", "12", "13", "14", "15","16","17","18", "19","20","21", "22", "23", "24", "25","26","27","28", "29","30","31", "32", "33", "34", "35","36","37","38", "39","40","41", "42", "43", "44", "45","46","47","48", "49"),
				"sum" => 7
			),
			54 => array(
				"num" => array("1", "2", "3", "4", "5","6","7","8", "9","10","11", "12", "13", "14", "15","16","17","18", "19","20","21", "22", "23", "24", "25","26","27","28", "29","30","31", "32", "33", "34", "35","36","37","38", "39","40","41", "42", "43", "44", "45","46","47","48", "49"),
				"sum" => 7
			),
			55 => array(
				"num" => array("1", "2", "3", "4", "5","6","7","8", "9","10","11", "12", "13", "14", "15","16","17","18", "19","20","21", "22", "23", "24", "25","26","27","28", "29","30","31", "32", "33", "34", "35","36","37","38", "39","40","41", "42", "43", "44", "45","46","47","48", "49"),
				"sum" => 7
			),
			
		);
		if(empty($gameConf[$gameid])) return false;

		$array   = $gameConf[$gameid]["num"];
		shuffle($array);
		$opennum = "";
		for($i=0; $i<$gameConf[$gameid]["sum"]; $i++){

		   $opennum .= $array[$i];
		   if($i!=$gameConf[$gameid]["sum"]-1)
			   $opennum .= ",";
		}
		rtrim($opennum, ',');

		return $opennum;
	}

	public function haoma_del_ajax() {
		$gameid = isset($_GET['gameid']) && intval($_GET['gameid']) ? intval($_GET['gameid']) : exit(json_encode(array('info' => '参数错误！','status' => 0)));
		if (!empty($_POST)) {
			$id = isset($_POST['id']) && trim($_POST['id']) ? safe_replace(trim($_POST['id'])) : exit(json_encode(array('info' => '选择要删除的预开奖！','status' => 0)));
			$this -> db2 -> delete(array('id' => $id));
			exit(json_encode(array(
				'info' 	 => '删除成功！',
				'status' => 1
			)));
		}
	}


	public function add() {
		if (isset($_POST['dosubmit'])) {
			$name = isset($_POST['name']) && trim($_POST['name']) ? safe_replace(trim($_POST['name'])) : showmessage('请输入游戏名称！', HTTP_REFERER);
			$template = isset($_POST['template']) && trim($_POST['template']) ? safe_replace(trim($_POST['template'])) : showmessage('请输入游戏模板名称！', HTTP_REFERER);
			$data = new_addslashes(serialize($_POST['data']));
			$insert = array(
				'name' => $name,
				'sort' => intval($_POST['sort']),
				'fptime' => intval($_POST['fptime']),
				'data' => $data,
				'template' => $template,
				'state' => intval($_POST['state'])
			);
			if ($this -> db -> insert($insert)) {
				showmessage('添加成功！', 'c=game&a=init');
			} else {
				showmessage('操作失败！', HTTP_REFERER);
			}
		}
		include $this -> admin_tpl('game_add');
	}

	public function del() {
		$id = intval($_GET['id']);
		if (!$id) {
			echo json_encode(array('run' => 'no', 'msg' => '参数错误！'));
			exit();
		}
		$r = $this -> db -> get_one(array('id' => $id));
		if ($r) {
			$num = $this -> db -> count();
			if ($num <= 1) {
				echo json_encode(array('run' => 'no', 'msg' => '至少需要保留一个游戏'));
				exit();
			}
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

	public function edit() {
		$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : showmessage('参数错误！', HTTP_REFERER);
		$data = $this -> db -> get_one(array('id' => $id));
		if ($data) {
			if (isset($_POST['dosubmit'])) {
				$name = isset($_POST['name']) && trim($_POST['name']) ? safe_replace(trim($_POST['name'])) : showmessage('请输入游戏名称', HTTP_REFERER);
				$template = isset($_POST['template']) && trim($_POST['template']) ? safe_replace(trim($_POST['template'])) : showmessage('请输入游戏模板名称', HTTP_REFERER);
				$data = new_addslashes(serialize($_POST['data']));
				$update = array(
					'name' => $name,
					'sort' => intval($_POST['sort']),
					'fptime' => intval($_POST['fptime']),
					'data' => $data,
					'template' => $template,
					'state' => intval($_POST['state'])
				);
				if ($this -> db -> update($update, array('id' => $id))) {
					showmessage('修改成功！', 'c=game&a=init');
				} else {
					showmessage('修改失败！', HTTP_REFERER);
				}
			}
			$dataarr = unserialize($data['data']);
			include $this -> admin_tpl('game_edit');
		} else {
			showmessage('未找到对应数据！', HTTP_REFERER);
		}
	}
	
	public function peilvxg() {
		$id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : showmessage('参数错误！', HTTP_REFERER);
		$data = $this -> db -> get_one(array('id' => $id));
		if ($data) {
			if (isset($_POST['dosubmit'])) {
				$name = isset($_POST['name']) && trim($_POST['name']) ? safe_replace(trim($_POST['name'])) : showmessage('请输入游戏名称', HTTP_REFERER);
				$template = isset($_POST['template']) && trim($_POST['template']) ? safe_replace(trim($_POST['template'])) : showmessage('请输入游戏模板名称', HTTP_REFERER);
				$data = new_addslashes(serialize($_POST['data']));
				$update = array(
					'name' => $name,
					'sort' => intval($_POST['sort']),
					'fptime' => intval($_POST['fptime']),
					'data' => $data,
					'template' => $template,
					'state' => intval($_POST['state'])
				);
				if ($this -> db -> update($update, array('id' => $id))) {
					showmessage('修改成功！', 'c=game&a=init');
				} else {
					showmessage('修改失败！', HTTP_REFERER);
				}
			}
			$dataarr = unserialize($data['data']);
			include $this -> admin_tpl('game_peilvxg');
		} else {
			showmessage('未找到对应数据！', HTTP_REFERER);
		}
	}

	public function delall() {
		$time = SYS_TIME - (86400 * 30 * 3);
		$where = "sendtime <= '$time'";
		if ($this -> db2 -> delete($where)) {
			echo json_encode(array('run' => 'yes', 'msg' => '清理完成！'));
			exit();
		} else {
			echo json_encode(array('run' => 'no', 'msg' => '清理失败！'));
			exit();
		}
	}
}
?>