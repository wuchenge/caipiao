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

	public function haoma() {
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$gameid = intval($_GET['gameid']);
		$list = $this -> db2 -> listinfo(array('gameid' => $gameid), 'sendtime DESC', $page, 15);
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