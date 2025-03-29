<?php
//service.html
defined('IN_MYWEB') or exit('No permission resources.');
header('Content-Type: application/json; charset=utf-8');
$lotteryname = safe_replace(trim($_REQUEST['lotteryname']));
$gameid = intval($_REQUEST['gameid']);
if (empty($lotteryname)) {
	$re['state'] = 0;
	$re['msg'] = 'ERR-'.SYS_TIME;
	echo json_encode($re);
	exit;
}
// print_r('<pre>');

$account = intval($_REQUEST['account']);
$db = base :: load_model('haoma_model');
$db2 = base :: load_model('order_model');
function get_settings($filed = '') {
	$setdb = base :: load_model('settings_model');
	if ($filed) {
		$settingdata = $setdb -> get_one(array('name' => $filed));
		return $settingdata['data'];
	} else {
		$settingdata = $setdb -> select();
		foreach($settingdata as $k => $v) {
			$settingarr[$v['name']] = $v['data'];
		}
		return $settingarr;
	}
}

$cfgarr = array(
    'jslhc' => array(
		'name' => '急速六合彩',
		'starttime' => '',//开奖时间 提前20分源码分享站ymfxz.com
		'endtime' => '',//关奖时间 推迟20分
		'time' => 20,//结算刷新时间
		'maxtime' => 120,//最大更新时间
		'id' => array(53),//入库字段ID，入库多个ID数组
		'template' => array('lhc'),//操作模板
		'url' => '',//采集URL
	),
	'xylhc' => array(
		'name' => '幸运六合彩',
		'starttime' => '',//开奖时间 提前20分
		'endtime' => '',//关奖时间 推迟20分
		'time' => 20,//结算刷新时间
		'maxtime' => 300,//最大更新时间
		'id' => array(54),//入库字段ID，入库多个ID数组
		'template' => array('lhc'),//操作模板
		'url' => '',//采集URL
	),
	'xglhc' => array(
		'name' => '香港六合彩',
		'starttime' => '',//开奖时间 提前20分
		'endtime' => '',//关奖时间 推迟20分
		'time' => 20,//结算刷新时间
		'maxtime' => 172,000,//最大更新时间172,800
		'id' => array(55),//入库字段ID，入库多个ID数组
		'template' => array('lhc'),//操作模板
		'url' => '',//采集URL
	),
	'jskl8' => array(
		'name' => '急速快乐8',
		'starttime' => '',//开奖时间 提前20分
		'endtime' => '',//关奖时间 推迟20分
		'time' => 20,//结算刷新时间
		'maxtime' => 90,//最大更新时间
		'id' => array(52),//入库字段ID，入库多个ID数组
		'template' => array('pk10'),//操作模板
		'url' => '',//采集URL
	),
	'twkl10f' => array(
		'name' => '台湾快乐10分',
		'starttime' => '',//开奖时间 提前20分
		'endtime' => '',//关奖时间 推迟20分
		'time' => 20,//结算刷新时间
		'maxtime' => 90,//最大更新时间
		'id' => array(45),//入库字段ID，入库多个ID数组
		'template' => array('kl10f'),//操作模板
		'url' => '',//采集URL
	),
		'xjpkl10f' => array(
		'name' => '新加坡快乐10分',
		'starttime' => '',//开奖时间 提前20分
		'endtime' => '',//关奖时间 推迟20分
		'time' => 20,//结算刷新时间
		'maxtime' => 180,//最大更新时间
		'id' => array(50),//入库字段ID，入库多个ID数组
		'template' => array('kl10f'),//操作模板
		'url' => '',//采集URL
	),
		'xykl10f' => array(
		'name' => '幸运快乐10分',
		'starttime' => '',//开奖时间 提前20分
		'endtime' => '',//关奖时间 推迟20分
		'time' => 20,//结算刷新时间
		'maxtime' => 300,//最大更新时间
		'id' => array(51),//入库字段ID，入库多个ID数组
		'template' => array('kl10f'),//操作模板
		'url' => '',//采集URL
	),
	'xgk3' => array(
		'name' => '台湾快3',
		'starttime' => '',//开奖时间 提前20分
		'endtime' => '',//关奖时间 推迟20分
		'time' => 20,//结算刷新时间
		'maxtime' => 90,//最大更新时间
		'id' => array(44),//入库字段ID，入库多个ID数组
		'template' => array('k3k3'),//操作模板
		'url' => '',//采集URL
	),
	'xjpk3' => array(
		'name' => '新加坡快3',
		'starttime' => '',//开奖时间 提前20分
		'endtime' => '',//关奖时间 推迟20分
		'time' => 20,//结算刷新时间
		'maxtime' => 180,//最大更新时间
		'id' => array(48),//入库字段ID，入库多个ID数组
		'template' => array('k3k3'),//操作模板
		'url' => '',//采集URL
	),
	'xyk3' => array(
		'name' => '幸运快3',
		'starttime' => '',//开奖时间 提前20分
		'endtime' => '',//关奖时间 推迟20分
		'time' => 20,//结算刷新时间
		'maxtime' => 300,//最大更新时间
		'id' => array(49),//入库字段ID，入库多个ID数组
		'template' => array('k3k3'),//操作模板
		'url' => '',//采集URL
	),
	'xg11x5' => array(
		'name' => '台湾11X5',
		'starttime' => '',//开奖时间 提前20分
		'endtime' => '',//关奖时间 推迟20分
		'time' => 20,//结算刷新时间
		'maxtime' => 90,//最大更新时间
		'id' => array(43),//入库字段ID，入库多个ID数组
		'template' => array('cqssc'),//操作模板
		'url' => '',//采集URL
	),
	'xjp11x5' => array(
		'name' => '新加坡11X5',
		'starttime' => '',//开奖时间 提前20分
		'endtime' => '',//关奖时间 推迟20分
		'time' => 20,//结算刷新时间
		'maxtime' => 180,//最大更新时间
		'id' => array(46),//入库字段ID，入库多个ID数组
		'template' => array('cqssc'),//操作模板
		'url' => '',//采集URL
	),
	'xy11x5' => array(
		'name' => '幸运11X5',
		'starttime' => '',//开奖时间 提前20分
		'endtime' => '',//关奖时间 推迟20分
		'time' => 20,//结算刷新时间
		'maxtime' => 300,//最大更新时间
		'id' => array(47),//入库字段ID，入库多个ID数组
		'template' => array('cqssc'),//操作模板
		'url' => '',//采集URL
	),
	'cqssc' => array(
		'name' => '重庆时时彩',
		'starttime' => '',//开奖时间 提前20分
		'endtime' => '',//关奖时间 推迟20分
		'time' => 30,//结算刷新时间
		'maxtime' => 1200,//最大更新时间
		'id' => array(32),//入库字段ID，入库多个ID数组
		'template' => array('fantan', 'cqssc', 'liubo'),//操作模板
		'url' => 'http://api.api68.com/CQShiCai/getBaseCQShiCai.do?issue=&lotCode=10060',//采集URL
	),
	'gdkl' => array(
		'name' => '广东快乐10分',
		'starttime' => '',//开奖时间 提前20分
		'endtime' => '',//关奖时间 推迟20分
		'time' => 40,//结算刷新时间
		'maxtime' => 1200,//最大更新时间
		'id' => array(),//入库字段ID，入库多个ID数组
		'template' => array('fantan'),//操作模板
		'url' => 'https://api.api68.com/klsf/getLotteryInfo.do?lotCode=10005',//采集URL
	),
	'xync' => array(//幸运农场 重庆快乐十分，10分钟一期,时间为全天
		'name' => '幸运农场',
		'starttime' => '',//开奖时间
		'endtime' => '',//关奖时间
		'time' => 50,//结算刷新时间
		'maxtime' => 600,//最大更新时间
		'id' => array(),//入库字段ID，入库多个ID数组
		'template' => array('fantan'),//操作模板
		'url' => 'https://api.api68.com/klsf/getLotteryInfo.do?lotCode=10009',//采集URL

	),
	'pcdd' => array(//PC蛋蛋 幸运28 北京快乐8，每天从早上9:05至23:55每
		'name' => 'PC蛋蛋',
// 		'starttime' => '08:45:00',//开奖时间 提前20分
// 		'endtime' => '23:59:59',//关奖时间 推迟20分
        'starttime' => '',//开奖时间 提前20分
		'endtime' => '',//关奖时间 推迟20分
		'time' => 35,//结算刷新时间
		'maxtime' => 300,//最大更新时间
		'id' => array(27),//入库字段ID，入库多个ID数组
		'template' => array('pc28'),//操作模板
		'url' => 'http://api.api68.com/LuckTwenty/getPcLucky28.do?issue=',//采集URL
	),
	'jnd28' => array(//加拿大28，每三分半钟开一期，每天维护时间为: 晚上20:00点到21:30点，周一可能会有延迟
		'name' => '加拿大28',
// 		'starttime' => '20:40:00',//开奖时间
// 		'endtime' => '20:20:00',//关奖时间
        'starttime' => '',//开奖时间
		'endtime' => '',//关奖时间
		'time' => 25,//结算刷新时间
		'maxtime' => 210,//最大更新时间
		'id' => array(28),//入库字段ID，入库多个ID数组
		'template' => array('pc28'),//操作模板
		'url' => 'https://liyingyl.com/?m=api&c=trend&a=getMoreLottery&lottery_type=3',//采集URL
	),
	'pk10' => array(
		'name' => '北京赛车',
		'starttime' => '',//开奖时间
		'endtime' => '',//关奖时间
		'time' => 45,//结算刷新时间
		'maxtime' => 1200,//最大更新时间
		'id' => array(39),//入库字段ID，入库多个ID数组
		'template' => array('pk10', 'pkpj'),//操作模板
		'url' => 'http://api.api68.com/pks/getLotteryPksInfo.do?lotCode=10001',//采集URL
	),
	
		'twpk10' => array(
		'name' => '台湾PK10',
		'starttime' => '',//开奖时间
		'endtime' => '',//关奖时间
		'time' => 45,//结算刷新时间
		'maxtime' => 1200,//最大更新时间
		'id' => array(40),//入库字段ID，入库多个ID数组
		'template' => array('pk10', 'pkpj'),//操作模板
		'url' => '',//采集URL
	),
	
		'xjppk10' => array(
		'name' => '新加坡PK10',
		'starttime' => '',//开奖时间
		'endtime' => '',//关奖时间
		'time' => 45,//结算刷新时间
		'maxtime' => 180,//最大更新时间
		'id' => array(41),//入库字段ID，入库多个ID数组
		'template' => array('pk10', 'pkpj'),//操作模板
		'url' => '',//采集URL
	),
	
		'xypk10' => array(
		'name' => '幸运PK10',
		'starttime' => '',//开奖时间
		'endtime' => '',//关奖时间
		'time' => 45,//结算刷新时间
		'maxtime' => 1200,//最大更新时间
		'id' => array(42),//入库字段ID，入库多个ID数组
		'template' => array('pk10', 'pkpj'),//操作模板
		'url' => '',//采集URL
	),
		'xyft' => array(
		'name' => '幸运飞艇',
		'starttime' => '',//开奖时间
		'endtime' => '',//关奖时间
		'time' => 300,//结算刷新时间
		'maxtime' => 90,//最大更新时间
		'id' => array(38),//入库字段ID，入库多个ID数组
		'template' => array(pk10),//操作模板
		// 'url' => 'https://www-pc520.com/?m=api&c=trend&a=getMoreLottery&lottery_type=4',
		'url' => 'http://api.api68.com/pks/getLotteryPksInfo.do?issue=&lotCode=10057',//采集URL
	),
/* 			'jscp' => array(
		'name' => '极速时时彩',
		'starttime' => '',//开奖时间
		'endtime' => '',//关奖时间
		'time' => 5,//结算刷新时间
		'maxtime' => 90,//最大更新时间
		'id' => array(35),//入库字段ID，入库多个ID数组
		'template' => array(cqssc),//操作模板
		'url' => 'http://api.api68.com/CQShiCai/getBaseCQShiCai.do?lotCode=10036',//采集URL https://leyinga.com/?m=api&c=trend&a=getMoreLottery&lottery_type=1
	), */
			'xjssc' => array(
		'name' => '新疆时时彩',
// 		'starttime' => '10:00:00',//开奖时间
// 		'endtime' => '02:20:00',//关奖时间
		'starttime' => '',//开奖时间
		'endtime' => '',//关奖时间
		'time' => 45,//结算刷新时间
		'maxtime' => 1200,//最大更新时间
		'id' => array(33),//入库字段ID，入库多个ID数组
		'template' => array(cqssc),//操作模板
		'url' => 'http://api.api68.com/CQShiCai/getBaseCQShiCai.do?lotCode=10004',//采集URL
	),
			'tjssc' => array(
		'name' => '天津时时彩',
		'starttime' => '',//开奖时间
		'endtime' => '',//关奖时间
		'time' => 45,//结算刷新时间
		'maxtime' => 1200,//最大更新时间
		'id' => array(34),//入库字段ID，入库多个ID数组
		'template' => array(cqssc),//操作模板
		'url' => 'http://api.api68.com/CQShiCai/getBaseCQShiCai.do?lotCode=10003',//采集URL
	),
		'sjsc' => array(
		'name' => '极速赛车',
		'starttime' => '',//开奖时间
		'endtime' => '',//关奖时间
		'time' => 45,//结算刷新时间
		'maxtime' => 90,//最大更新时间
		'id' => array(23),//入库字段ID，入库多个ID数组
		'template' => array(pk10),//操作模板
		'url' => 'http://api.api68.com/pks/getLotteryPksInfo.do?issue=&lotCode=10037',//采集URL
	),
	
	'teqdd' => array(//土耳其蛋蛋（台湾28） 自开奖 每天从早上00：00至23:59：59每2分钟 30秒一期
		'name' => '土耳其蛋蛋',
		'starttime' => '00:0:01',//开奖时间 提前20分
		'endtime' => '23:59:59',//关奖时间 推迟20分
		'time' => 20,//结算刷新时间
		'maxtime' => 60,//最大更新时间
		'id' => array(29),//入库字段ID，入库多个ID数组
		'template' => array('pc28'),//操作模板
		'url' => 'https://leyinga.com/?m=api&c=trend&a=getMoreLottery&lottery_type=1',//采集URL
	),
	
		'twdd' => array(//台湾幸运28 自开奖 每天从早上00：00至23:59：59每 180秒一期
		'name' => '台湾蛋蛋',
		'starttime' => '00:0:01',//开奖时间 提前20分
		'endtime' => '23:59:59',//关奖时间 推迟20分
		'time' => 35,//结算刷新时间
		'maxtime' => 180,//最大更新时间
		'id' => array(30),//入库字段ID，入库多个ID数组
		'template' => array('pc28'),//操作模板
		'url' => 'https://leyinga.com/?m=api&c=trend&a=getMoreLottery&lottery_type=1',//采集URL
	),
	
		'xjpdd' => array(//新加坡蛋蛋 自开奖 每天从早上00：00至23:59：59每2分钟 30秒一期
		'name' => '新加坡28',
// 		'starttime' => '00:0:03',//开奖时间 提前20分
// 		'endtime' => '23:59:59',//关奖时间 推迟20分
		'starttime' => '',//开奖时间 提前20分
		'endtime' => '',//关奖时间 推迟20分
		'time' => 35,//结算刷新时间
		'maxtime' => 300,//最大更新时间
		'id' => array(31),//入库字段ID，入库多个ID数组
		'template' => array('pc28'),//操作模板
		'url' => 'https://leyinga.com/?m=api&c=trend&a=getMoreLottery&lottery_type=1',//采集URL
	),
	
	'jsssc' => array(//自开奖 每天从早上00：00至23:59：59每1分钟 30秒一期
		'name' => '台湾时时彩',
		'starttime' => '00:0:03',//开奖时间 提前20分
		'endtime' => '23:59:59',//关奖时间 推迟20分
		'time' => 35,//结算刷新时间
		'maxtime' => 90,//最大更新时间
		'id' => array(35),//入库字段ID，入库多个ID数组
		'template' => array('cqssc'),//操作模板
		'url' => 'https://leyinga.com/?m=api&c=trend&a=getMoreLottery&lottery_type=1',//采集URL
	),
	
		'xjpssc' => array(//新加坡时时自开奖 每天从早上00：00至23:59：59每1分钟 30秒一期
		'name' => '新加坡时时彩',
		'starttime' => '00:0:03',//开奖时间 提前20分
		'endtime' => '23:59:59',//关奖时间 推迟20分
		'time' => 35,//结算刷新时间
		'maxtime' => 180,//最大更新时间
		'id' => array(36),//入库字段ID，入库多个ID数组
		'template' => array('cqssc'),//操作模板
		'url' => 'https://leyinga.com/?m=api&c=trend&a=getMoreLottery&lottery_type=1',//采集URL
	),
	
		'xyssc' => array(//幸运时时彩自开奖 每天从早上00：00至23:59：59每1分钟 30秒一期
		'name' => '幸运时时彩',
		'starttime' => '00:0:03',//开奖时间 提前20分
		'endtime' => '23:59:59',//关奖时间 推迟20分
		'time' => 35,//结算刷新时间
		'maxtime' => 300,//最大更新时间
		'id' => array(37),//入库字段ID，入库多个ID数组
		'template' => array('cqssc'),//操作模板
		'url' => 'https://leyinga.com/?m=api&c=trend&a=getMoreLottery&lottery_type=1',//采集URL
	),
/* 	'3fpc' => array(//土耳其蛋蛋 自开奖 每天从早上00：00至23:59：59每1分钟 30秒一期
		'name' => '3分pc',
		'starttime' => '00:0:03',//开奖时间 提前20分
		'endtime' => '23:59:59',//关奖时间 推迟20分
		'time' => 15,//结算刷新时间
		'maxtime' => 60,//最大更新时间
		'id' => array(25),//入库字段ID，入库多个ID数组
		'template' => array('pc28'),//操作模板
		'url' => 'https://leyinga.com/?m=api&c=trend&a=getMoreLottery&lottery_type=1',//采集URL
	),
	'5fpc' => array(//土耳其蛋蛋 自开奖 每天从早上00：00至23:59：59每1分钟 30秒一期
		'name' => '5分pc',
		'starttime' => '00:0:03',//开奖时间 提前20分
		'endtime' => '23:59:59',//关奖时间 推迟20分
		'time' => 15,//结算刷新时间
		'maxtime' => 300,//最大更新时间
		'id' => array(26),//入库字段ID，入库多个ID数组
		'template' => array('pc28'),//操作模板
		'url' => 'https://leyinga.com/?m=api&c=trend&a=getMoreLottery&lottery_type=1',//采集URL
	), */
);
$pj_re = array();



echo get_function($gameid, $lotteryname);

function get_function($gameid, $game) {
	global $cfgarr, $account;
	if ($account) {
		foreach ($cfgarr[$game]['template'] as $k => $template) {
			$id = $cfgarr[$game]['id'][$k];
			$return = account($id, $game, $template);
		}
		return $return;
	} else {
		if($game=='teqdd' ||  $game=='xjpdd' ||$game=='twdd' ||$game=='jsssc' || $game=='xjpssc' || $game=='xyssc' || $game=='pk10' || $game=='twpk10'|| $game=='xjppk10'|| $game=='xypk10' ||$game=='xg11x5'||$game=='xgk3'||$game=='twkl10f'||$game=='xjpkl10f'||$game=='xykl10f'||$game=='xjpk3'||$game=='xyk3'||$game=='xjp11x5'||$game=='xy11x5'||$game=='jskl8'||$game=='jslhc'||$game=='xylhc'){ //|| $game=='jscp'
// 			echo $game;exit;
			return gohaoma_sys($gameid, $game);
		}
		else
		return gohaoma_url($gameid, $game);
	}
}

//游戏对应方法

//游戏结算
function account($gameid, $game, $template) {
	global $db, $db2, $cfgarr;
	$time = SYS_TIME;
	$re['name'] = $cfgarr[$game]['name'];
	$re['time'] = $cfgarr[$game]['time'];//处理注单的间隔时间

//	$starttime = $cfgarr[$game]['starttime'];
//	$endtime = $cfgarr[$game]['endtime'];
//	if ($endtime && $starttime) {
//		$endtime_tmp = strtotime(date("Y-m-d $endtime"));
//		$starttime_tmp = strtotime(date("Y-m-d $starttime"));
//		if ($starttime_tmp > $endtime_tmp) {//隔天
//			$state = $time < $starttime_tmp && $time > $endtime_tmp ? true : false;
//		} else {//当天
//			$state = $time < $starttime_tmp || $time > $endtime_tmp ? true : false;
//		}
//		if ($state) {
//			return $starttime_tmp - $time;
////			$re['state'] = 1;
////			$re['msg'] = '关奖时间，'.$starttime.'自动开启';
////			$re['time'] = $starttime_tmp - $time;
////			return json_encode($re);
//		}
//	}

	$db3 = base :: load_model('user_model');
	$db4 = base :: load_model('account_model');
    if($gameid==13 || $gameid==14){
	    $haomadb = $db -> get_one("gameid = '$gameid' AND haoma != '' AND sendtime  <= $time AND account = 0");
	}else{
		 $haomadb = $db -> get_one("gameid = '$gameid' AND haoma != ''  AND account = 0");
	}
	if ($haomadb) {//存在
		$qishu = $haomadb['qishu'];
		$haoma = $haomadb['haoma'];
		$haomaid = $haomadb['id'];
		$orderlist = $db2 -> select("gameid = '$gameid' AND qishu = '$qishu' AND account = 0 AND tui = 0", '*', 100, 'id ASC');
		$account = 1;
		if ($orderlist) {//存在记录
			$account = 0;
			if (count($orderlist) < 100) {//一次处理100单，如果没有查询到100单，则表示已经处理完成
				$account = 1;
			}
			$haomaarr = explode(',', $haoma);
			$account_fun = 'account_'.$template;
			$wanfa_account_fun = 'wanfa_account_'.$template;
			$pro = $account_fun($gameid, $haomaarr);
			//处理查询到的注单

			foreach($orderlist as $order) {
				$return = $wanfa_account_fun($order, $pro['zhi'], $pro['bharr'], $haomaarr, $gameid);
				$fact = false;
				if (strpos($return, '@') !== false) {//输
					$multiple = -1;
					if ($order['ban'] == 1) {
						$wfarr = explode('@', $return);//拆分投注玩法
						$multiple = $wfarr[1];
						$update['wanfa'] = $return;
					}
				} elseif (strpos($return, '#') !== false) {//按照指定金额处理
					$arr = explode('#', $return);
					$multiple = $arr[1];
					$fact = true;
				} else {//返回赔率
					$multiple = $return;
				}
//				print_r($multiple);
				if ($multiple >= 0) {//赢 和 退 大于1为退回倍数
					//查询用户当前的金额
					$oldmoney = $db3 -> get_one(array('uid' => $order['uid']));//用户金额
					$money = $fact ? $multiple : ($multiple ? round(bcmul($order['money'], $multiple, 2), 2) : $order['money']);
					$accountdb = array(
						'uid' => $order['uid'],
						'money' => $money,
						'countmoney' => $oldmoney['money'] + $money,
						'type' => 3,
						'addtime' => $time,
						'comment' => '注单结算，单号：'.$order['orderid'].' 盈利发放'
					);
					$db4 -> insert($accountdb);//记录流水
					$db3 -> update(array('money' => '+='.$money), array('uid' => $order['uid']));//用户金额更新
				} else {//输了
					$money = 0 - $order['money'];
				}
				$update['account'] = $money;
				$update['endtime'] = $time;
				$update['ban'] = 0;
				$db2 -> update($update, array('id' => $order['id']));//更新注单
			}
		}
		if ($account) {//将开奖数据标记为完成
			$db -> update(array('account' => 1), array('id' => $haomaid));//更新
			$re['state'] = 1;
			$re['msg'] = $qishu.'期，注单已处理完成';
			return json_encode($re);
		} else {
			$re['state'] = 1;
			$re['msg'] = $qishu.'期，注单正在处理...';
			return json_encode($re);
		}
 	} else {
		$re['state'] = 1;
		$re['msg'] = '当前暂无注单处理';
		return json_encode($re);
	}
}

function account_fantan($gameid, $haomaarr) {
	//取得开奖号码
	if ($gameid == 1 || $gameid == 14) {
		$he = $haomaarr[3]*10 +  $haomaarr[4];
	} else if ($gameid == 4 || $gameid == 5 || $gameid == 13) {
		$he = $haomaarr[1]*10 +  $haomaarr[2];
	} else if ($gameid == 2 || $gameid == 3) {
		$he = $haomaarr[6]*10 +  $haomaarr[7];
	} else{
		$he = 0;
		foreach($haomaarr as $k => $h) {
			$he = $he + $h;
		}
	}
	$return['zhi'] = $he % 4 == 0 ? 4 : $he % 4;
	$return['bharr'] = array(
		'1L2' => '1念2',
		'2L3' => '2念3',
		'3L4' => '3念4',
		'4L1' => '4念1',
		'1L4' => '1念4',
		'2L1' => '2念1',
		'3L2' => '3念2',
		'4L3' => '4念3',
		'1L3' => '1念3',
		'2L4' => '2念4',
		'3L1' => '3念1',
		'4L2' => '4念2',
		'12J' => '1-2角',
		'23J' => '2-3角',
		'34J' => '3-4角',
		'41J' => '4-1角',
		'Z1' => '正1',
		'Z2' => '正2',
		'Z3' => '正3',
		'Z4' => '正4',
		'F1' => '番1',
		'F2' => '番2',
		'F3' => '番3',
		'F4' => '番4',
		'D' => '单',
		'S' => '双'
	);
	return $return;
}

function wanfa_account_fantan($order, $zhi, $bharr) {
	$wfarr = explode('@', $order['wanfa']);//拆分投注玩法
	$numberkey = array_keys($bharr, $wfarr[0]);//返回投注号码的编号数组
	$number = $numberkey[0];//返回投注号码的编号
	$nian = $zhi == 1 ? '1念2' : ($zhi == 2 ? '2念3' : ($zhi == 3 ? '3念4' : '4念1'));
	switch ($number){
		case '1L2':
			$return = $zhi == 1 ? $wfarr[1] : ($zhi == 2 ? 0 : $nian.'@'.$wfarr[1]);
			break;
		case '2L3':
			$return = $zhi == 2 ? $wfarr[1] : ($zhi == 3 ? 0 : $nian.'@'.$wfarr[1]);
			break;
		case '3L4':
			$return = $zhi == 3 ? $wfarr[1] : ($zhi == 4 ? 0 : $nian.'@'.$wfarr[1]);
			break;
		case '4L1':
			$return = $zhi == 4 ? $wfarr[1] : ($zhi == 1 ? 0 : $nian.'@'.$wfarr[1]);
			break;
		case '1L4':
			$return = $zhi == 1 ? $wfarr[1] : ($zhi == 4 ? 0 : $nian.'@'.$wfarr[1]);
			break;
		case '2L1':
			$return = $zhi == 2 ? $wfarr[1] : ($zhi == 1 ? 0 : $nian.'@'.$wfarr[1]);
			break;
		case '3L2':
			$return = $zhi == 3 ? $wfarr[1] : ($zhi == 2 ? 0 : $nian.'@'.$wfarr[1]);
			break;
		case '4L3':
			$return = $zhi == 4 ? $wfarr[1] : ($zhi == 3 ? 0 : $nian.'@'.$wfarr[1]);
			break;
		case '1L3':
			$return = $zhi == 1 ? $wfarr[1] : ($zhi == 3 ? 0 : $nian.'@'.$wfarr[1]);
			break;
		case '2L4':
			$return = $zhi == 2 ? $wfarr[1] : ($zhi == 4 ? 0 : $nian.'@'.$wfarr[1]);
			break;
		case '3L1':
			$return = $zhi == 3 ? $wfarr[1] : ($zhi == 1 ? 0 : $nian.'@'.$wfarr[1]);
			break;
		case '4L2':
			$return = $zhi == 4 ? $wfarr[1] : ($zhi == 2 ? 0 : $nian.'@'.$wfarr[1]);
			break;
		case '12J':
			$return = $zhi == 1 || $zhi == 2 ? $wfarr[1] : '3-4角@'.$wfarr[1];
			break;
		case '23J':
			$return = $zhi == 2 || $zhi == 3 ? $wfarr[1] : '4-1角@'.$wfarr[1];
			break;
		case '34J':
			$return = $zhi == 3 || $zhi == 4 ? $wfarr[1] : '1-2角@'.$wfarr[1];
			break;
		case '41J':
			$return = $zhi == 4 || $zhi == 1 ? $wfarr[1] : '2-3角@'.$wfarr[1];
			break;
		case 'Z1':
			$return = $zhi == 1 ? $wfarr[1] : ($zhi == 2 || $zhi == 4 ? 0 : 'Z'.$zhi.'@'.$wfarr[1]);
			break;
		case 'Z2':
			$return = $zhi == 2 ? $wfarr[1] : ($zhi == 1 || $zhi == 3 ? 0 : 'Z'.$zhi.'@'.$wfarr[1]);
			break;
		case 'Z3':
			$return = $zhi == 3 ? $wfarr[1] : ($zhi == 2 || $zhi == 4 ? 0 : 'Z'.$zhi.'@'.$wfarr[1]);
			break;
		case 'Z4':
			$return = $zhi == 4 ? $wfarr[1] : ($zhi == 1 || $zhi == 3 ? 0 : 'Z'.$zhi.'@'.$wfarr[1]);
			break;
		case 'F1':
			$return = $zhi == 1 ? $wfarr[1] : 'F'.$zhi.'@'.$wfarr[1];
			break;
		case 'F2':
			$return = $zhi == 2 ? $wfarr[1] : 'F'.$zhi.'@'.$wfarr[1];
			break;
		case 'F3':
			$return = $zhi == 3 ? $wfarr[1] : 'F'.$zhi.'@'.$wfarr[1];
			break;
		case 'F4':
			$return = $zhi == 4 ? $wfarr[1] : 'F'.$zhi.'@'.$wfarr[1];
			break;
		case 'D':
			$return = $zhi % 2 != 0 ? $wfarr[1] : '双@'.$wfarr[1];
			break;
		case 'S':
			$return = $zhi % 2 == 0 ? $wfarr[1] : '单@'.$wfarr[1];
			break;
		default:
	}
	return $return;
}

function account_pc28($gameid, $haomaarr) {
	//取得开奖号码
	$zhi = 0;
	foreach($haomaarr as $k => $h) {
		$zhi = $zhi + $h;
	}
	$return['zhi'] = $zhi;
	$return['bharr'] = array(
		'DA' => '大',
		'X' => '小',
		'D' => '单',
		'S' => '双',
		'DD' => '大单',
		'DS' => '大双',
		'XD' => '小单',
		'XS' => '小双',
		'JD' => '极大',
		'JX' => '极小',
		'BZ' => '豹子',
		'HB' => '红波',
		'LVB' => '绿波',
		'LB' => '蓝波'
	);
	return $return;
}

function wanfa_account_pc28($order, $zhi, $bharr, $haomaarr, $gameid) {
	global $db2;
	$wfarr = explode('@', $order['wanfa']);//拆分投注玩法
	$numberkey = array_keys($bharr, $wfarr[0]);//返回投注号码的编号数组
	$number = $numberkey ? $numberkey[0] : 'NUM';//返回投注号码的编号
	$settings = get_settings();
	if ($settings['is_1314'] == 1 && ($zhi == 13 || $zhi == 14)) {
		//13.14时，大小单双为活动赔率
		//统计所有有效注单的总投注额
		$count = $db2 -> query("SELECT SUM(money) AS money FROM #@__order WHERE tui = 0 AND uid = '$order[uid]' AND gameid = '$order[gameid]' AND qishu = '$order[qishu]' ORDER BY id DESC", true);
		$money_count = round($count['money'], 2);
		$new_wfarr = $money_count < 1001 ? 1.8 : ($money_count < 10001 ? 1.6 : 1);
		if ($gameid == 27 || $gameid == 31|| $gameid == 29|| $gameid == 30) {
			//PC蛋蛋1倍退本
			$new2_wfarr = 1;
		} else {
			//加拿大28全输
			$new2_wfarr = -1;
		}
	} else {
		$new_wfarr = $wfarr[1];
		$new2_wfarr = $wfarr[1];
	}
	if($settings['bz_ts'] == 1 && ($haomaarr[0] == $haomaarr[1] && $haomaarr[0] == $haomaarr[2])){
		$return = -1;
	}else{
		switch ($number){
			case 'DA':
				$return = $zhi > 13 ? $new_wfarr : '小@'.$wfarr[1];
				break;
			case 'X':
				$return = $zhi < 14 ? $new_wfarr : '大@'.$wfarr[1];
				break;
			case 'D':
				$return = $zhi % 2 != 0 ? $new_wfarr : '双@'.$wfarr[1];
				break;
			case 'S':
				$return = $zhi % 2 == 0 ? $new_wfarr : '单@'.$wfarr[1];
				break;
			case 'DD':
				$return = $zhi > 13 && $zhi % 2 != 0 ? $new2_wfarr : -1;
				break;
			case 'DS':
				$return = $zhi > 13 && $zhi % 2 == 0 ? $new2_wfarr : -1;
				break;
			case 'XD':
				$return = $zhi < 14 && $zhi % 2 != 0 ? $new2_wfarr : -1;
				break;
			case 'XS':
				$return = $zhi < 14 && $zhi % 2 == 0 ? $new2_wfarr : -1;
				break;
			case 'JD':
				$return = $zhi > 21 ? $wfarr[1] : -1;
				break;
			case 'JX':
				$return = $zhi < 6 ? $wfarr[1] : -1;
				break;
			case 'BZ':
				$settings = get_settings();
				if($settings['bz_ts'] == 1){
					$return = $haomaarr[0] == $haomaarr[1] && $haomaarr[0] == $haomaarr[2] ? -1 : -1; //$wfarr[1]
				}else{
					$return = $haomaarr[0] == $haomaarr[1] && $haomaarr[0] == $haomaarr[2] ? $wfarr[1] : -1; //
				}
				
				break;
			case 'HB':
				$return = in_array($zhi, array(3,6,9,12,15,18,21,24)) ? $wfarr[1] : -1;
				break;
			case 'LVB':
				$return = in_array($zhi, array(1,4,7,10,16,19,22,25)) ? $wfarr[1] : -1;
				break;
			case 'LB':
				$return = in_array($zhi, array(2,5,8,11,17,20,23,26)) ? $wfarr[1] : -1;
				break;
			default:
				//特码数字
				$return = $zhi == $wfarr[0] ? $wfarr[1] : -1;
				break;
		}
	}
	
	return $return;
}
//快3k3
function account_k3k3($gameid, $haomaarr) {
	//取得开奖号码
	$zhi = 0;
	foreach($haomaarr as $k => $h) {
		$zhi = $zhi + $h;
	}
	$return['zhi'] = $zhi;
	$return['bharr'] = array(
		'DA' => '大',
		'X' => '小',
		'D' => '单',
		'S' => '双',
		'DD' => '大单',
		'DS' => '大双',
		'XD' => '小单',
		'XS' => '小双',
		'JD' => '极大',
		'JX' => '极小',
		'BZ' => '豹子',
		'HB' => '红波',
		'LVB' => '绿波',
		'LB' => '蓝波'
	);
	return $return;
}

function wanfa_account_k3k3($order, $zhi, $bharr, $haomaarr, $gameid) {
	global $db2;
	$wfarr = explode('@', $order['wanfa']);//拆分投注玩法
	$numberkey = array_keys($bharr, $wfarr[0]);//返回投注号码的编号数组
	$number = $numberkey ? $numberkey[0] : 'NUM';//返回投注号码的编号
	$settings = get_settings();
	/* if ($settings['is_1314'] == 1 && ($zhi == 13 || $zhi == 14)) {   //pc28  1314玩法，不需要时候注释
		//13.14时，大小单双为活动赔率
		//统计所有有效注单的总投注额
		$count = $db2 -> query("SELECT SUM(money) AS money FROM #@__order WHERE tui = 0 AND uid = '$order[uid]' AND gameid = '$order[gameid]' AND qishu = '$order[qishu]' ORDER BY id DESC", true);
		$money_count = round($count['money'], 2);
		$new_wfarr = $money_count < 1001 ? 1.8 : ($money_count < 10001 ? 1.6 : 1);
		if ($gameid == 9) {
			//PC蛋蛋1倍退本
			$new2_wfarr = 1;
		} else {
			//加拿大28全输
			$new2_wfarr = -1;
		}
	} else {
		$new_wfarr = $wfarr[1];
		$new2_wfarr = $wfarr[1];
	} */
	$new_wfarr = $wfarr[1];
	$new2_wfarr = $wfarr[1];
	if($settings['bz_ts'] == 1 && ($haomaarr[0] == $haomaarr[1] && $haomaarr[0] == $haomaarr[2])){
		$return = -1;
	}else{
		switch ($number){
			case 'DA':
				$return = $zhi > 11 ? $new_wfarr : '小@'.$wfarr[1];
				break;
			case 'X':
				$return = $zhi < 10 ? $new_wfarr : '大@'.$wfarr[1];
				break;
			case 'D':
				$return = $zhi % 2 != 0 ? $new_wfarr : '双@'.$wfarr[1];
				break;
			case 'S':
				$return = $zhi % 2 == 0 ? $new_wfarr : '单@'.$wfarr[1];
				break;
			case 'DD':
				$return = $zhi > 10 && $zhi % 2 != 0 ? $new2_wfarr : -1;
				break;
			case 'DS':
				$return = $zhi > 10 && $zhi % 2 == 0 ? $new2_wfarr : -1;
				break;
			case 'XD':
				$return = $zhi < 11 && $zhi % 2 != 0 ? $new2_wfarr : -1;
				break;
			case 'XS':
				$return = $zhi < 11 && $zhi % 2 == 0 ? $new2_wfarr : -1;
				break;
/* 			case 'JD':
				$return = $zhi > 21 ? $wfarr[1] : -1;
				break;
			case 'JX':
				$return = $zhi < 6 ? $wfarr[1] : -1;
				break; */
			case 'BZ':
				$settings = get_settings();
				if($settings['bz_ts'] == 1){
					$return = $haomaarr[0] == $haomaarr[1] && $haomaarr[0] == $haomaarr[2] ? -1 : -1; //$wfarr[1]
				}else{
					$return = $haomaarr[0] == $haomaarr[1] && $haomaarr[0] == $haomaarr[2] ? $wfarr[1] : -1; //
				}
				
				break;
			case 'HB':
				$return = in_array($zhi, array(3,6,9,12,15,18,21,24)) ? $wfarr[1] : -1;
				break;
			case 'LVB':
				$return = in_array($zhi, array(1,4,7,10,16,19,22,25)) ? $wfarr[1] : -1;
				break;
			case 'LB':
				$return = in_array($zhi, array(2,5,8,11,17,20,23,26)) ? $wfarr[1] : -1;
				break;
			default:
				//特码数字
				$return = $zhi == $wfarr[0] ? $wfarr[1] : -1;
				break;
		}
	}
	
	return $return;
}

function account_pk10($gameid, $haomaarr) {
	//取得开奖号码属性
	$zhi = array();
	foreach($haomaarr as $k => $h) {
		if ($k >= 5) break;
		$zhi[$k] = $haomaarr[9-$k] < $haomaarr[$k] ? '龙' : '虎';
	}
	$return['zhi'] = $zhi;
	$return['bharr'] = array(0 => '冠军', 1 => '亚军', 2 => '第三名', 3 => '第四名', 4 => '第五名', 5 => '第六名', 6 => '第七名', 7 => '第八名', 8 => '第九名', 9 => '第十名', 10 => '冠亚');
	return $return;
}

function wanfa_account_pk10($order, $zhi, $bharr, $haomaarr, $gameid) {
	$wfarr = explode('@', $order['wanfa']);//拆分投注玩法
	$numberkey = array_keys($bharr, $wfarr[2]);//返回投注号码的编号数组
	$number = $numberkey[0];//返回投注号码的编号
	if ($number == 10) {//冠亚军和
		$gyh = $haomaarr[0] + $haomaarr[1];
		switch ($wfarr[0]){
			case '冠亚大':
				$return = $gyh > 11 ? $wfarr[1] : -1;
				break;
			case '冠亚小':
				$return = $gyh < 12 ? $wfarr[1] : -1;
				break;
			case '冠亚单':
				$return = $gyh % 2 != 0 ? $wfarr[1] : -1;
				break;
			case '冠亚双':
				$return = $gyh % 2 == 0 ? $wfarr[1] : -1;
				break;
			default:
				//数字
				$return = $gyh == $wfarr[0] ? $wfarr[1] : -1;
		}
	} else {//单号1~10
		$hm = $haomaarr[$number];//取得对应编号的开奖号码
		switch ($wfarr[0]){
			case '大':
				$return = $hm > 5 ? $wfarr[1] : '小@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '小':
				$return = $hm < 6 ? $wfarr[1] : '大@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '单':
				$return = $hm % 2 != 0 ? $wfarr[1] : '双@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '双':
				$return = $hm % 2 == 0 ? $wfarr[1] : '单@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '龙':
				$return = $zhi[$number] == '龙' ? $wfarr[1] : '虎@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '虎':
				$return = $zhi[$number] == '虎' ? $wfarr[1] : '龙@'.$wfarr[1].'@'.$wfarr[2];
				break;
			default:
				//数字
				$return = $hm == $wfarr[0] ? $wfarr[1] : $hm.'@'.$wfarr[1].'@'.$wfarr[2];
		}
	}
	return $return;
}

function account_kl10f($gameid, $haomaarr) {
	//取得开奖号码属性
	$zhi = array();
	foreach($haomaarr as $k => $h) {
		if ($k >= 5) break;
		$zhi[$k] = $haomaarr[9-$k] < $haomaarr[$k] ? '龙' : '虎';
	}
	$return['zhi'] = $zhi;
	$return['bharr'] = array(0 => '冠军', 1 => '亚军', 2 => '第三名', 3 => '第四名', 4 => '第五名', 5 => '第六名', 6 => '第七名', 7 => '第八名', 8 => '第九名', 9 => '第十名', 10 => '冠亚');
	return $return;
}

function wanfa_account_kl10f($order, $zhi, $bharr, $haomaarr, $gameid) {
	$wfarr = explode('@', $order['wanfa']);//拆分投注玩法
	$numberkey = array_keys($bharr, $wfarr[2]);//返回投注号码的编号数组
	$number = $numberkey[0];//返回投注号码的编号
	if ($number == 10) {//冠亚军和
		$gyh = $haomaarr[0] + $haomaarr[1];
		switch ($wfarr[0]){
			case '冠亚大':
				$return = $gyh > 11 ? $wfarr[1] : -1;
				break;
			case '冠亚小':
				$return = $gyh < 12 ? $wfarr[1] : -1;
				break;
			case '冠亚单':
				$return = $gyh % 2 != 0 ? $wfarr[1] : -1;
				break;
			case '冠亚双':
				$return = $gyh % 2 == 0 ? $wfarr[1] : -1;
				break;
			default:
				//数字
				$return = $gyh == $wfarr[0] ? $wfarr[1] : -1;
		}
	} else {//单号1~10
		$hm = $haomaarr[$number];//取得对应编号的开奖号码
		switch ($wfarr[0]){
			case '大':
				$return = $hm > 10 ? $wfarr[1] : '小@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '小':
				$return = $hm < 11 ? $wfarr[1] : '大@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '单':
				$return = $hm % 2 != 0 ? $wfarr[1] : '双@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '双':
				$return = $hm % 2 == 0 ? $wfarr[1] : '单@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '龙':
				$return = $zhi[$number] == '龙' ? $wfarr[1] : '虎@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '虎':
				$return = $zhi[$number] == '虎' ? $wfarr[1] : '龙@'.$wfarr[1].'@'.$wfarr[2];
				break;
			default:
				//数字
				$return = $hm == $wfarr[0] ? $wfarr[1] : $hm.'@'.$wfarr[1].'@'.$wfarr[2];
		}
	}
	return $return;
}


function account_pkpj($gameid, $haomaarr) {
	$return['zhi'] = 0;
	$return['bharr'] = array(1 => '1门', 2 => '2门', 3 => '3门', 4 => '4门', 5 => '5门');
	return $return;
}

function pkpj_goval($order, $haomaarr) {
	global $db2;
	$count_1 = $db2 -> query("SELECT SUM(money) AS money FROM #@__order WHERE tui = 0 AND gameid = '$order[gameid]' AND qishu = '$order[qishu]' AND wanfa like '%1%' ORDER BY id DESC", true);
	$money[1] = intval($count_1['money']);
	$count_2 = $db2 -> query("SELECT SUM(money) AS money FROM #@__order WHERE tui = 0 AND gameid = '$order[gameid]' AND qishu = '$order[qishu]' AND wanfa like '%2%' ORDER BY id DESC", true);
	$money[2] = intval($count_2['money']);
	$count_3 = $db2 -> query("SELECT SUM(money) AS money FROM #@__order WHERE tui = 0 AND gameid = '$order[gameid]' AND qishu = '$order[qishu]' AND wanfa like '%3%' ORDER BY id DESC", true);
	$money[3] = intval($count_3['money']);
	$count_4 = $db2 -> query("SELECT SUM(money) AS money FROM #@__order WHERE tui = 0 AND gameid = '$order[gameid]' AND qishu = '$order[qishu]' AND wanfa like '%4%' ORDER BY id DESC", true);
	$money[4] = intval($count_4['money']);
	$count_5 = $db2 -> query("SELECT SUM(money) AS money FROM #@__order WHERE tui = 0 AND gameid = '$order[gameid]' AND qishu = '$order[qishu]' AND wanfa like '%5%' ORDER BY id DESC", true);
	$money[5] = intval($count_5['money']);

	//取得开奖号码属性
	$px = array();
	$men = array('[91][73][64]', '[101][92][83][74][65]', '[102][93][84][75]', '[103][94][85][76][21]', '[104][95][86][31]', '[105][96][87][41][32]', '[106][97][51][42]', '[107][98][61][52][43]', '[108][71][62][53]', '[109][81][72][63][54]', '[82]');
	$i = 1;
	foreach($haomaarr as $k => $h) {
		if ($k % 2 == 0) {
			$arr = array($haomaarr[$k], $haomaarr[$k+1]);
			rsort($arr);
			foreach($men as $n => $v) {
				if (strpos($v,'['.$arr[0].$arr[1].']') !==false) {
					$px[$i] = $n.'.'.(strlen($arr[0].$arr[1]) == 2 ? '0'.$arr[0].$arr[1] : $arr[0].$arr[1]);
					break;
				}
			}
			$i++;
		}
	}
	arsort($px);
	$i = 1;
	foreach($px as $k => $v) {
		$new_px[$i] = $k;
		$i++;
	}
//	$return['px'] = $new_px;
	$m[1] = $money[$new_px[1]];
	$m[2] = $money[$new_px[2]];
	$m[3] = $money[$new_px[3]];
	$m[4] = $money[$new_px[4]];
	$m[5] = $money[$new_px[5]];
//	$m[1] = 900;
//	$m[2] = 100;
//	$m[3] = 100;
//	$m[4] = 800;
//	$m[5] = 100;
	$num = array_count_values($m);
	if ($num[0] >= 4) {//无效
		$jye = 0;
	} elseif ($m[2]+$m[3]+$m[4]+$m[5] <= $m[1]) {//通吃
		$jye = $m[2]+$m[3]+$m[4]+$m[5];
	} elseif ($m[3]+$m[4]+$m[5] <= $m[1]) {//1吃345
		$cha = $m[1] - ($m[3]+$m[4]+$m[5]);
		if ($cha > 0) {//不够吃
			$jye = $m[1];
		} else {
			$jye = $m[3]+$m[4]+$m[5];
		}
	} elseif ($m[4]+$m[5] <= $m[1]) {//1吃45
		$cha = $m[1] - ($m[4]+$m[5]);
		$jye = $m[1];
		if ($cha > 0) {//不够吃
			$yu3 = ($m[3]+$m[4]+$m[5]) - $m[1];
		} else {
			$yu3 = $m[3];
		}
		if ($yu3 < $m[2]) {//2吃3
			$jye = $jye + $yu3;
		} else {
			$jye = $jye + $m[2];
		}
	} elseif ($m[5] <= $m[1]) {
		$cha = $m[1] - $m[5];
		$jye = $m[1];
		if ($cha > 0) {//不够吃
			$yu4 = ($m[4]+$m[5]) - $m[1];
		} else {
			$yu4 = $m[4];
		}
		if ($yu4 < $m[2]) {//4不够2吃，2继续吃3
			$jye = $jye + $yu4;
			$yu2 = $m[2] - $yu4;
			//4不够2吃，2继续吃3
			if ($yu2 <= $m[3]) {//3够吃
				$jye = $jye + $yu2;
			} else {//3不够吃
				$jye = $jye + $m[3];
			}
		} else {//4够2吃
			$jye = $jye + $m[2];
			$yu4 = $yu4 - $m[2];
			if ($yu4 <= $m[3]) {//4够3吃
				$jye = $jye + $yu4;
			} else {
				$jye = $jye + $m[3];
			}
		}
	} elseif ($m[5] > $m[1]) {
		$yu5 = $m[5] - $m[1];
		$jye = $m[1];
		if ($yu5 > 0) {//5赔完1后还有多，2继续吃
			if ($yu5 >= $m[2]) {//5够赔2
				$yu5 = $yu5 - $m[2];
				$jye = $jye + $m[2];
				if ($yu5 > 0) {//5赔完2后还有多，3继续吃
					if ($yu5 >= $m[3]) {//5够赔3
						$yu5 = $yu5 - $m[3];
						$jye = $jye + $m[3];
						if ($yu5 > 0) {//5赔完3后还有多，4继续吃
							if ($yu5 >= $m[4]) {//5够赔4
								$jye = $jye + $m[4];
							} else {//5不够赔4
								$jye = $jye + $yu5;
							}
						}
					} else {//5不够赔3
						$jye = $jye + $yu5;
						//开始4赔3
						if ($m[4] >= $m[3]) {//4够赔3
							$jye = $jye + $m[3];
						} else {//4不够赔3
							$jye = $jye + $m[4];
						}
					}
				} else {//5赔完2后没有多了，这里5已经为0了，//开始4赔3
					if ($m[4] >= $m[3]) {//4够赔3
						$jye = $jye + $m[3];
					} else {//4不够赔3
						$jye = $jye + $m[4];
					}
				}
			} else {//5不够赔2
				$jye = $jye + $yu5;
				$yu2 = $m[2] - $yu5;
				//开始4赔2
				if ($m[4] >= $yu2) {//4够赔2
					$jye = $jye + $yu2;
					$yu4 = $m[4] - $yu2;
					if ($yu4 > 0) {//4赔完2后还有多，3继续吃
						//开始4赔3
						if ($yu4 >= $m[3]) {//4够赔3
							$jye = $jye + $m[3];
						} else {//4不够赔3
							$jye = $jye + $yu4;
						}
					}
				} else {//4不够赔2
					$jye = $jye + $m[4];
					$yu2 = $yu2 - $m[4];
					//开始3赔2
					if ($m[3] >= $yu2) {//3够赔2
						$jye = $jye + $yu2;
					} else {//3不够赔2
						$jye = $jye + $m[3];
					}
				}
			}
		} else {//5赔完1后没有多了，这里5已经为0了，开始4赔2
			if ($m[4] >= $m[2]) {//4够赔2
				$yu4 = $m[4] - $m[2];
				$jye = $jye + $m[2];
				if ($yu4 > 0) {//4赔完2后还有多，3继续吃
					if ($yu4 >= $m[3]) {//4够赔3
						$jye = $jye + $m[3];
					} else {//4不够赔3
						$jye = $jye + $yu4;
					}
				}
			} else {//4不够赔时
				$yu2 = $m[2] - $m[4];
				$jye = $jye + $m[4];
				//开始3赔2
				if ($m[3] >= $yu2) {//3够赔2
					$jye = $jye + $yu2;
				} else {//3不够赔2
					$jye = $jye + $m[3];
				}
			}
		}
	}
	if ($jye) {
//		$return['jye'] = $jye;
//		$return['m'] = $m;
		$tc = ceil($jye * 0.03);
		if ($m[5]) {
			$m[5] = $m[5] - $tc;
			$t = 5;
		} elseif ($m[4])  {
			$m[4] = $m[4] - $tc;
			$t = 4;
		} elseif ($m[3])  {
			$m[3] = $m[3] - $tc;
			$t = 3;
		} elseif ($m[2])  {
			$m[2] = $m[2] - $tc;
			$t = 2;
		}
		$jye_tmp = $jye - $tc;
		if ($jye_tmp <= $m[1]) {//够赔1
			$he = $m[1];
			$js[5] = !$m[5] || $he >= $m[5] ? $m[5] : $he;
			$js[4] = !$m[4] || $he - $m[5] >= $m[4] ? $m[4] : $he - $m[5];
			$js[3] = !$m[3] || $he - $m[5] - $m[4] >= $m[3] ? $m[3] : $he - $m[5] - $m[4];
			$js[2] = !$m[2] || $he - $m[5] - $m[4] - $m[3] >= $m[2] ? $m[2] : $he - $m[5] - $m[4] - $m[3];
			$js[1] = $js[2] + $js[3] + $js[4] + $js[5];
			$js[0] = 1;
		} elseif ($jye_tmp <= $m[1]+$m[2]) {//够赔12
			$he = $m[1] + $m[2];
			$js[5] = !$m[5] || $he >= $m[5] ? $m[5] : $he;
			$js[4] = !$m[4] || $he - $m[5] >= $m[4] ? $m[4] : $he - $m[5];
			$js[3] = !$m[3] || $he - $m[5] - $m[4] >= $m[3] ? $m[3] : $he - $m[5] - $m[4];
			$js[2] = !$m[2] ? $m[2] : $js[3] + $js[4] + $js[5] - $m[1];
			$js[1] = $m[1];
			$js[0] = 2;
		} elseif ($jye_tmp <= $m[1]+$m[2]+$m[3]) {//够赔123
			$he = $m[1] + $m[2] + $m[3];
			$js[5] = !$m[5] || $he >= $m[5] ? $m[5] : $he;
			$js[4] = !$m[4] || $he - $m[5] >= $m[4] ? $m[4] : $he - $m[5];
			$js[3] = !$m[3] ? $m[3] : $js[4] + $js[5] - $m[1] - $m[2];
			$js[2] = $m[2];
			$js[1] = $m[1];
			$js[0] = 3;
		} elseif ($jye_tmp <= $m[1]+$m[2]+$m[3]+$m[4]) {//够赔1234
			$he = $m[1] + $m[2] + $m[3] + $m[4];
			$js[5] = !$m[5] || $he >= $m[5] ? $m[5] : $he;
			$js[4] = !$m[4] ? $m[4] : $js[5] - $m[1] - $m[2] - $m[3];
			$js[3] = $m[3];
			$js[2] = $m[2];
			$js[1] = $m[1];
			$js[0] = 4;
		}
		$js[$t] = $js[$t] + $tc;
		$m[$t] = $m[$t] + $tc;
		$js[5] = $m[5] ? ($m[5] - $js[5]) / $m[5] : 0;
		$js[4] = $m[4] ? ($js[0] < 4 ? $m[4] - $js[4] : $js[4] + $m[4]) / $m[4] : 0;
		$js[3] = $m[3] ? ($js[0] < 3 ? $m[3] - $js[3] : $js[3] + $m[3]) / $m[3] : 0;
		$js[2] = $m[2] ? ($js[0] < 2 ? $m[2] - $js[2] : $js[2] + $m[2]) / $m[2] : 0;
		$js[1] = $m[1] ? ($js[1] + $m[1]) / $m[1] : 0;
	} else {
		$js[5] = -1;
		$js[4] = -1;
		$js[3] = -1;
		$js[2] = -1;
		$js[1] = -1;
	}
	$new_js = array();
	foreach($new_px as $k => $v) {
		$new_js[$v] = $js[$k];
	}
	$return['js'] = $new_js;
	$return['qishu'] = $order['qishu'];
//	print_r($return);
	return $return;
}

function wanfa_account_pkpj($order, $zhi, $bharr, $haomaarr, $gameid) {
	global $db2, $pj_re;
	$wfarr = explode('@', $order['wanfa']);//拆分投注玩法
	$numberkey = array_keys($bharr, $wfarr[0]);//返回投注号码的编号数组
	$number = $numberkey[0];//返回投注号码的编号
	if ($pj_re['qishu'] != $order['qishu']) {
		$pj_re = pkpj_goval($order, $haomaarr);
	}
	return $pj_re['js'][$number] > 0 ? $number.'#'.round($pj_re['js'][$number] * $order['money'], 2) : ($pj_re['js'][$number] == -1 ? 0 : -1);
}

function account_cqssc($gameid, $haomaarr) {
	//取得开奖号码属性
	$he = 0;
	$zhi = array();
	foreach($haomaarr as $k => $h) {
		$he = $he + $h;
	}
	$zhi['he'] = $he;
	$zhi['qs'] = cq_ssc_goval(array($haomaarr[0],$haomaarr[1],$haomaarr[2]));
	$zhi['zs'] = cq_ssc_goval(array($haomaarr[1],$haomaarr[2],$haomaarr[3]));
	$zhi['hs'] = cq_ssc_goval(array($haomaarr[2],$haomaarr[3],$haomaarr[4]));
	$return['zhi'] = $zhi;
	$return['bharr'] = array(0 => '第一球', 1 => '第二球', 2 => '第三球', 3 => '第四球', 4 => '第五球', 5 => '总和', 6 => '前三', 7 => '中三', 8 => '后三');
	return $return;
}

function cq_ssc_goval($strarr) {
	sort($strarr); //排序
	//计算豹子、对子
	$re[0] = $strarr[2] - $strarr[1] == 0 ? 1 : 0;
	$re[0] = $strarr[1] - $strarr[0] == 0 ? ++$re[0] : $re[0];
	//计算顺子、半顺、杂六
	$re[1] = $strarr[2] - $strarr[1] == 1 || $strarr[2] - $strarr[1] == 8 ? 1 : 0;
	$re[1] = $strarr[1] - $strarr[0] == 1 ? ++$re[1] : $re[1];
	if ($re[0] == 1) {
		$val = '对子';
	} elseif ($re[0] == 2) {
		$val = '豹子';
	} elseif ($re[1] == 1) {
		$val = '半顺';
	} elseif ($re[1] == 2) {
		$val = '顺子';
	} else {
		$val = '杂六';
	}
	return $val;
}

function wanfa_account_cqssc($order, $zhi, $bharr, $haomaarr, $gameid) {
	$wfarr = explode('@', $order['wanfa']);//拆分投注玩法
	$numberkey = array_keys($bharr, $wfarr[2]);//返回投注号码的编号数组
	$number = $numberkey[0];//返回投注号码的编号
	if ($number == 5) {//总和
		$lhh = $haomaarr[0] > $haomaarr[4] ? '龙' : ($haomaarr[0] == $haomaarr[4] ? '和' : '虎');
		switch ($wfarr[0]){
			case '总和大':
				$return = $zhi['he'] > 22 ? $wfarr[1] : '总和小@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '总和小':
				$return = $zhi['he'] < 23 ? $wfarr[1] : '总和大@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '总和单':
				$return = $zhi['he'] % 2 != 0 ? $wfarr[1] : '总和双@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '总和双':
				$return = $zhi['he'] % 2 == 0 ? $wfarr[1] : '总和单@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '龙':
				$return = $lhh == '龙' ? $wfarr[1] : $lhh.'@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '虎':
				$return = $lhh == '虎' ? $wfarr[1] : $lhh.'@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '和':
				$return = $lhh == '和' ? $wfarr[1] : $lhh.'@'.$wfarr[1].'@'.$wfarr[2];
				break;
			default:
		}
	} elseif ($number == 6 || $number == 7 || $number == 8) {//前三 中三 后三
		$arr = array(6 => 'qs', 7 => 'zs', 8 => 'hs');
		$key = $arr[$number];
		switch ($wfarr[0]){
			case '豹子':
				$return = $zhi[$key] == '豹子' ? $wfarr[1] : -1;
				break;
			case '顺子':
				$return = $zhi[$key] == '顺子' ? $wfarr[1] : -1;
				break;
			case '对子':
				$return = $zhi[$key] == '对子' ? $wfarr[1] : -1;
				break;
			case '半顺':
				$return = $zhi[$key] == '半顺' ? $wfarr[1] : -1;
				break;
			case '杂六':
				$return = $zhi[$key] == '杂六' ? $wfarr[1] : -1;
				break;
			default:
		}
	} else {//五球
		$hm = $haomaarr[$number];//取得对应编号的开奖号码
		switch ($wfarr[0]){
			case '大':
				$return = $hm > 4 ? $wfarr[1] : '小@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '小':
				$return = $hm < 5 ? $wfarr[1] : '大@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '单':
				$return = $hm % 2 != 0 ? $wfarr[1] : '双@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '双':
				$return = $hm % 2 == 0 ? $wfarr[1] : '单@'.$wfarr[1].'@'.$wfarr[2];
				break;
			default:
				//数字
				$return = $hm == $wfarr[0] ? $wfarr[1] : $hm.'@'.$wfarr[1].'@'.$wfarr[2];
		}
	}
	return $return;
}

function account_liubo($gameid, $haomaarr) {
	//取得开奖号码
	$zhi = intval($haomaarr[0].$haomaarr[1]);
	if ($zhi == 0 || $zhi == 50 ) {
		$zhi = intval($haomaarr[2].$haomaarr[3]);
	}
	if ($zhi > 50) {
		$zhi = $zhi - 50;
	}
	$zhi = trim($zhi);
	$zhiarr['zhi'] = $zhi;
	$zhiarr['he'] = $zhi < 10 ? $zhi : ($zhi[0]+$zhi[1]);
	$zhiarr['w'] = $zhi < 10 ? $zhi : $zhi[1];
	$zhiarr['t'] = $zhi < 10 ? 0 : $zhi[0];
	$return['zhi'] = $zhiarr;
	$return['bharr'] = array(
		'DA' => '大',
		'X' => '小',
		'D' => '单',
		'S' => '双',
		'DD' => '大单',
		'DS' => '大双',
		'XD' => '小单',
		'XS' => '小双',
		'JQ' => '家禽',
		'YS' => '野兽',
		'HSDA' => '合数大',
		'HSX' => '合数小',
		'HSD' => '合数单',
		'HSS' => '合数双',
		'HB' => '红波',
		'LVB' => '绿波',
		'LB' => '蓝波',
		'HDA' => '红大',
		'HX' => '红小',
		'HD' => '红单',
		'HS' => '红双',
		'LVDA' => '绿大',
		'LVX' => '绿小',
		'LVD' => '绿单',
		'LVS' => '绿双',
		'LDA' => '蓝大',
		'LX' => '蓝小',
		'LD' => '蓝单',
		'LS' => '蓝双',
		'T0' => '0头',
		'T1' => '1头',
		'T2' => '2头',
		'T3' => '3头',
		'T4' => '4头',
		'W0' => '0尾',
		'W1' => '1尾',
		'W2' => '2尾',
		'W3' => '3尾',
		'W4' => '4尾',
		'W5' => '5尾',
		'W6' => '6尾',
		'W7' => '7尾',
		'W8' => '8尾',
		'W9' => '9尾',
		'NIU' => '牛',
		'JI' => '鸡',
		'YANG' => '羊',
		'ZHU' => '猪',
		'GOU' => '狗',
		'MA' => '马',
		'SHE' => '蛇',
		'SHU' => '鼠',
		'HU' => '虎',
		'TU' => '兔',
		'LONG' => '龙',
		'HOU' => '猴'
	);
	return $return;
}

function wanfa_account_liubo($order, $zhiarr, $bharr, $haomaarr, $gameid) {
	global $db2;
	$wfarr = explode('@', $order['wanfa']);//拆分投注玩法
	$numberkey = array_keys($bharr, $wfarr[0]);//返回投注号码的编号数组
	$number = $numberkey ? $numberkey[0] : 'NUM';//返回投注号码的编号
	$zhi = $zhiarr['zhi'];
	$he = $zhiarr['he'];
	$t = $zhiarr['t'];
	$w = $zhiarr['w'];
	$he_type = array('DA', 'X', 'D', 'S', 'DD', 'DS', 'XD', 'XS', 'HSDA', 'HSX', 'HSD', 'HSS', 'JQ', 'YS');
	if ($zhi == 49 && in_array($number, $he_type)) {
		$return = 0;
	} else {
		switch ($number){
			case 'DA':
				$return = $zhi > 24 ? $wfarr[1] : '小@'.$wfarr[1];
				break;
			case 'X':
				$return = $zhi < 25 ? $wfarr[1] : '大@'.$wfarr[1];
				break;
			case 'D':
				$return = $zhi % 2 != 0 ? $wfarr[1] : '双@'.$wfarr[1];
				break;
			case 'S':
				$return = $zhi % 2 == 0 ? $wfarr[1] : '单@'.$wfarr[1];
				break;
			case 'DD':
				$return = $zhi > 24 && $zhi % 2 != 0 ? $wfarr[1] : -1;
				break;
			case 'DS':
				$return = $zhi > 24 && $zhi % 2 == 0 ? $wfarr[1] : -1;
				break;
			case 'XD':
				$return = $zhi < 25 && $zhi % 2 != 0 ? $wfarr[1] : -1;
				break;
			case 'XS':
				$return = $zhi < 25 && $zhi % 2 == 0 ? $wfarr[1] : -1;
				break;
			case 'JQ':
				$return = in_array($zhi, array(11,23,35,47,3,15,27,39,5,17,29,41,1,13,25,37,49,2,14,26,38,6,18,30,42)) ? $wfarr[1] : -1;
				break;
			case 'YS':
				$return = in_array($zhi, array(7,19,31,43,12,24,36,48,10,22,34,46,9,21,33,45,8,20,32,44,4,16,28,40)) ? $wfarr[1] : -1;
				break;
			case 'HSDA':
				$return = $he > 6 ? $wfarr[1] : '合数小@'.$wfarr[1];
				break;
			case 'HSX':
				$return = $he < 7 ? $wfarr[1] : '合数大@'.$wfarr[1];
				break;
			case 'HSD':
				$return = $he % 2 != 0 ? $wfarr[1] : '合数双@'.$wfarr[1];
				break;
			case 'HSS':
				$return = $he % 2 == 0 ? $wfarr[1] : '合数单@'.$wfarr[1];
				break;
			case 'HB':
				$return = in_array($zhi, array(1,2,7,8,12,13,18,19,23,24,29,30,34,35,40,45,46)) ? $wfarr[1] : -1;
				break;
			case 'LVB':
				$return = in_array($zhi, array(5,6,11,16,17,21,22,27,28,32,33,38,39,43,44,49)) ? $wfarr[1] : -1;
				break;
			case 'LB':
				$return = in_array($zhi, array(3,4,9,10,14,15,20,25,26,31,36,37,41,42,47,48)) ? $wfarr[1] : -1;
				break;
			case 'HDA':
				$return = in_array($zhi, array(29,30,34,35,40,45,46)) ? $wfarr[1] : -1;
				break;
			case 'HX':
				$return = in_array($zhi, array(1,2,7,8,12,13,18,19,23,24)) ? $wfarr[1] : -1;
				break;
			case 'HD':
				$return = in_array($zhi, array(1,7,13,19,23,29,35,45)) ? $wfarr[1] : -1;
				break;
			case 'HS':
				$return = in_array($zhi, array(2,8,12,18,24,30,34,40,46)) ? $wfarr[1] : -1;
				break;
			case 'LVDA':
				$return = in_array($zhi, array(27,28,32,33,38,39,43,44,49)) ? $wfarr[1] : -1;
				break;
			case 'LVX':
				$return = in_array($zhi, array(5,6,11,16,17,21,22)) ? $wfarr[1] : -1;
				break;
			case 'LVD':
				$return = in_array($zhi, array(5,11,17,21,27,33,39,43,49)) ? $wfarr[1] : -1;
				break;
			case 'LVS':
				$return = in_array($zhi, array(6,16,22,28,32,38,44)) ? $wfarr[1] : -1;
				break;
			case 'LDA':
				$return = in_array($zhi, array(25,26,31,36,37,41,42,47,48)) ? $wfarr[1] : -1;
				break;
			case 'LX':
				$return = in_array($zhi, array(3,4,9,10,14,15,20)) ? $wfarr[1] : -1;
				break;
			case 'LD':
				$return = in_array($zhi, array(3,9,15,25,31,37,41,47)) ? $wfarr[1] : -1;
				break;
			case 'LS':
				$return = in_array($zhi, array(4,10,14,20,26,36,42,48)) ? $wfarr[1] : -1;
				break;
			case 'T0':
				$return = $t == 0 ? $wfarr[1] : $t.'头@'.$wfarr[1];
				break;
			case 'T1':
				$return = $t == 1 ? $wfarr[1] : $t.'头@'.$wfarr[1];
				break;
			case 'T2':
				$return = $t == 2 ? $wfarr[1] : $t.'头@'.$wfarr[1];
				break;
			case 'T3':
				$return = $t == 3 ? $wfarr[1] : $t.'头@'.$wfarr[1];
				break;
			case 'T4':
				$return = $t == 4 ? $wfarr[1] : $t.'头@'.$wfarr[1];
				break;
			case 'W0':
				$return = $w == 0 ? $wfarr[1] : $w.'尾@'.$wfarr[1];
				break;
			case 'W1':
				$return = $w == 1 ? $wfarr[1] : $w.'尾@'.$wfarr[1];
				break;
			case 'W2':
				$return = $w == 2 ? $wfarr[1] : $w.'尾@'.$wfarr[1];
				break;
			case 'W3':
				$return = $w == 3 ? $wfarr[1] : $w.'尾@'.$wfarr[1];
				break;
			case 'W4':
				$return = $w == 4 ? $wfarr[1] : $w.'尾@'.$wfarr[1];
				break;
			case 'W5':
				$return = $w == 5 ? $wfarr[1] : $w.'尾@'.$wfarr[1];
				break;
			case 'W6':
				$return = $w == 6 ? $wfarr[1] : $w.'尾@'.$wfarr[1];
				break;
			case 'W7':
				$return = $w == 7 ? $wfarr[1] : $w.'尾@'.$wfarr[1];
				break;
			case 'W8':
				$return = $w == 8 ? $wfarr[1] : $w.'尾@'.$wfarr[1];
				break;
			case 'W9':
				$return = $w == 9 ? $wfarr[1] : $w.'尾@'.$wfarr[1];
				break;
			case 'NIU'://牛
				$return = in_array($zhi, array(12,24,36,48)) ? $wfarr[1] : -1;
				break;
			case 'JI'://鸡
				$return = in_array($zhi, array(4,16,28,40)) ? $wfarr[1] : -1;
				break;
			case 'YANG'://羊
				$return = in_array($zhi, array(6,18,30,42)) ? $wfarr[1] : -1;
				break;
			case 'ZHU'://猪
				$return = in_array($zhi, array(2,14,26,38)) ? $wfarr[1] : -1;
				break;
			case 'GOU'://狗
				$return = in_array($zhi, array(3,15,27,39)) ? $wfarr[1] : -1;
				break;
			case 'MA'://马
				$return = in_array($zhi, array(7,19,31,43)) ? $wfarr[1] : -1;
				break;
			case 'SHE'://蛇
				$return = in_array($zhi, array(8,20,32,44)) ? $wfarr[1] : -1;
				break;
			case 'SHU'://鼠
				$return = in_array($zhi, array(1,13,25,37,49)) ? $wfarr[1] : -1;
				break;
			case 'HU'://虎
				$return = in_array($zhi, array(11,23,35,47)) ? $wfarr[1] : -1;
				break;
			case 'TU'://兔
				$return = in_array($zhi, array(10,22,34,46)) ? $wfarr[1] : -1;
				break;
			case 'LONG'://龙
				$return = in_array($zhi, array(9,21,33,45)) ? $wfarr[1] : -1;
				break;
			case 'HOU'://猴
				$return = in_array($zhi, array(5,17,29,41)) ? $wfarr[1] : -1;
				break;
			default:
				//特码数字
				$return = $zhi == $wfarr[0] ? $wfarr[1] : -1;
				break;
		}
	}
	return $return;
}
function account_lhc($gameid, $haomaarr) {
	//取得开奖号码属性
	$zhi = array();
	foreach($haomaarr as $k => $h) {
		if ($k >= 5) break;
		$zhi[$k] = $haomaarr[9-$k] < $haomaarr[$k] ? '龙' : '虎';
	}
	$return['zhi'] = $zhi;
	$return['bharr'] = array(0 => '第一球', 1 => '第二球', 2 => '第三球', 3 => '第四球', 4 => '第五球', 5 => '第六球', 6 => '单特码', 7 => '第八名', 8 => '第九名', 9 => '第十名', 10 => '特肖');
	return $return;
}

function wanfa_account_lhc($order, $zhi, $bharr, $haomaarr, $gameid) {
	$wfarr = explode('@', $order['wanfa']);//拆分投注玩法
	$numberkey = array_keys($bharr, $wfarr[2]);//返回投注号码的编号数组
	$number = $numberkey[0];//返回投注号码的编号
	if ($number == 10) {
		$gyh = $haomaarr[6];
		switch ($wfarr[0]){
			case '牛'://牛
				$return = ($gyh == 12 ||$gyh == 24||$gyh == 36||$gyh == 48 ) ? $wfarr[1] : -1;
				break;
			case '鸡'://鸡
				$return = ($gyh == 4 ||$gyh == 16||$gyh == 28||$gyh == 40 ) ? $wfarr[1] : -1;
				break;
			case '羊'://羊
				$return = ($gyh == 6 ||$gyh == 18||$gyh == 30||$gyh == 42 ) ? $wfarr[1] : -1;
				break;
			case '猪'://猪
				$return = ($gyh == 2 ||$gyh == 14||$gyh == 26||$gyh == 38 ) ? $wfarr[1] : -1;
				break;
			case '狗'://狗
				$return = ($gyh == 3 ||$gyh == 15||$gyh == 27||$gyh == 39 ) ? $wfarr[1] : -1;
				break;
			case '马'://马
				$return = ($gyh == 7 ||$gyh == 19||$gyh == 31||$gyh == 43 ) ? $wfarr[1] : -1;
				break;
			case '蛇'://蛇
				$return = ($gyh == 8 ||$gyh == 20||$gyh == 32||$gyh == 44 ) ? $wfarr[1] : -1;
				break;
			case '鼠'://鼠
				$return = ($gyh == 1 ||$gyh == 13||$gyh == 25||$gyh == 37||$gyh == 48 ) ? $wfarr[1] : -1;
				break;
			case '虎'://虎
				$return = ($gyh == 11 ||$gyh == 23||$gyh == 35||$gyh == 47 ) ? $wfarr[1] : -1;
				break;
			case '兔'://兔
				$return = ($gyh == 10 ||$gyh == 22||$gyh == 34||$gyh == 46 ) ? $wfarr[1] : -1;
				break;
			case '龙'://龙
				$return = ($gyh == 9 ||$gyh == 21||$gyh == 33||$gyh == 45 ) ? $wfarr[1] : -1;
				break;
			case '猴'://猴
				$return = ($gyh == 5 ||$gyh == 17||$gyh == 29||$gyh == 41 ) ? $wfarr[1] : -1;
				break;
			default:
				//数字
				$return = $gyh == $wfarr[0] ? $wfarr[1] : -1;
		}
	} else {//单号1~10
		$hm = $haomaarr[$number];//取得对应编号的开奖号码
		switch ($wfarr[0]){
			case '大':
				$return = $hm > 25 ? $wfarr[1] : '小@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '小':
				$return = $hm < 24 ? $wfarr[1] : '大@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '单':
				$return = $hm % 2 != 0 ? $wfarr[1] : '双@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '双':
				$return = $hm % 2 == 0 ? $wfarr[1] : '单@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '龙':
				$return = $zhi[$number] == '龙' ? $wfarr[1] : '虎@'.$wfarr[1].'@'.$wfarr[2];
				break;
			case '虎':
				$return = $zhi[$number] == '虎' ? $wfarr[1] : '龙@'.$wfarr[1].'@'.$wfarr[2];
				break;
			default:
				//数字
				$return = $hm == $wfarr[0] ? $wfarr[1] : $hm.'@'.$wfarr[1].'@'.$wfarr[2];
		}
	}
	return $return;
}
//===================================================================================

//返回采集号码
function gohaoma_url($gameid, $game) {
	global $cfgarr;
	$time = SYS_TIME;
	$re['name'] = $cfgarr[$game]['name'];
	$maxtime = $cfgarr[$game]['maxtime'];
	$url = $cfgarr[$game]['url'];
	/*
	$starttime = $cfgarr[$game]['starttime'];
	$endtime = $cfgarr[$game]['endtime'];
	if ($endtime && $starttime) {
		$endtime_tmp = strtotime(date("Y-m-d $endtime"));
		$starttime_tmp = strtotime(date("Y-m-d $starttime"));
		if ($starttime_tmp > $endtime_tmp) {//隔天
			$state = $time < $starttime_tmp && $time > $endtime_tmp ? true : false;
		} else {//当天
			$state = $time < $starttime_tmp || $time > $endtime_tmp ? true : false;
		}
		if ($state) {
			$re['state'] = 1;
			$re['msg'] = '关奖时间，'.$starttime.'自动开启';
			$re['last'] = '--';
			$re['code'] = '--';
			$re['time'] = $starttime_tmp - $time;
			return json_encode($re);
		}
	}*/

	$header = array(
		'Expect: ',//头部送出 Expect: 可高速度
		'Content-Type: application/json; charset=utf-8',//定义文档返回的类型
		'Content-Length: 0'
	);
	$xml_result = $result = fileget_content($url, '', '', $url, $header, 10);
	$json = json_decode($result);
	// var_dump($url);exit;
	if(strpos($url,'api68.com') !== false){
		if ($json->errorCode != 0 || !isset($json->errorCode)) {
			$re['state'] = 1;
			$re['msg'] = '数据获取异常，5秒后重试';
			$re['last'] = '--';
			$re['code'] = '--';
			$re['time'] = 5;
			return json_encode($re);
		}
		$haomaarr = explode(',', trim($json->result->data->preDrawCode));
		$haoma = '';
		foreach($haomaarr as $v) {
			$haoma .= intval($v).',';
		}
		$haoma = rtrim($haoma, ',');
		$idarr = $cfgarr[$game]['id'];
		$content['current']['qishu'] = htmlspecialchars(addslashes(trim($json->result->data->preDrawIssue)));//当前期号
		$content['current']['sendtime'] = $time;//当前期开奖的时间
		$content['current']['haoma'] = $haoma;//开奖号码
		$content['next']['qishu'] = htmlspecialchars(addslashes(trim($json->result->data->drawIssue)));//下期期数
		$content['next']['sendtime'] = strtotime($json->result->data->drawTime);//下期开奖时间戳
		$awardTime = trim($json->result->data->drawTime);//下期开奖时间
		$awardTimeInterval = intval(strtotime($awardTime) - $time);//下期开奖时间距离当前的时间的时间戳
		$awardtime = $awardTimeInterval < 0 ? 0 : $awardTimeInterval;
	} elseif(strpos($url,'getMoreLottery') !== false) {
		if ($json->status != 0 || !isset($json->status)) {
			$re['state'] = 1;
			$re['msg'] = '数据获取异常，5秒后重试';
			$re['last'] = '--';
			$re['code'] = '--';
			$re['time'] = 5;
			return json_encode($re);
		}
		if ($json->data->totalPage == 0) {
			$re['state'] = 1;
			$re['msg'] = '当前无今日数据';
			$re['last'] = '--';
			$re['code'] = '--';
			$re['time'] = $maxtime;
			return json_encode($re);
		}
		$haomaarr = explode(',', trim($json->data->list[0]->lottery_numbers));
		$haoma = '';
		foreach($haomaarr as $v) {
			$haoma .= intval($v).',';
		}
		$haoma = rtrim($haoma, ',');
		$idarr = $cfgarr[$game]['id'];
		$content['current']['qishu'] = htmlspecialchars(addslashes(trim($json->data->list[0]->lottery_id)));//当前期号
		$content['current']['sendtime'] = $time;//当前期开奖的时间
		$open_time = date('Y-m-d H:i:s', trim($json->data->list[0]->lottery_date));
		switch ($game){
			case 'pcdd':
				//PC蛋蛋
				$content['current']['haoma'] = intval($haomaarr[0]).','.intval($haomaarr[1]).','.intval($haomaarr[2]);//开奖号码
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$timearr = explode(' ', $open_time);
				if ($timearr[1] == '23:55:00') {//返回的是最后一期
					$next_sendtime = strtotime($timearr[0].'09:05:00') + 86400;//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 300;//当前返回时间加5分钟
				}
				break;
			case 'pk10':
				$content['current']['haoma'] = $haoma;//开奖号码
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$timearr = explode(' ', $open_time);
				if ($timearr[1] == '04:50:00') {//返回的是最后一期
					$next_sendtime = strtotime($timearr[0].'09:30:00') + 86400;//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 1200;//当前返回时间加20分钟
				}
				break;
				
			case 'twpk10':
				$content['current']['haoma'] = $haoma;//开奖号码
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$timearr = explode(' ', $open_time);
				if ($timearr[1] == '04:50:00') {//返回的是最后一期
					$next_sendtime = strtotime($timearr[0].'09:30:00') + 86400;//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 90;//当前返回时间加1.5分钟
				}
				break;
			case 'twkl10f':
				$content['current']['haoma'] = $haoma;//开奖号码
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$timearr = explode(' ', $open_time);
				if ($timearr[1] == '04:50:00') {//返回的是最后一期
					$next_sendtime = strtotime($timearr[0].'09:30:00') + 86400;//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 90;//当前返回时间加1.5分钟
				}
				break;
			case 'xjpkl10f':
				//新加坡快乐10分kl10f
				$content['current']['haoma'] = $haoma;//开奖号码
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$timearr = explode(' ', $open_time);
				if ($timearr[1] == '04:50:00') {//返回的是最后一期
					$next_sendtime = strtotime($timearr[0].'09:30:00') + 86400;//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 180;//当前返回时间加1.5分钟
				}
				break;
			case 'xykl10f':
				//幸运快乐10分kl10f
				$content['current']['haoma'] = $haoma;//开奖号码
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$timearr = explode(' ', $open_time);
				// if ($timearr[1] == '04:50:00') {//返回的是最后一期
				// 	$next_sendtime = strtotime($timearr[0].'09:30:00') + 86400;//第二天第一期的时间
				// } else {
					$next_sendtime = strtotime($open_time) + 300;//当前返回时间加1.5分钟
				// }
				break;
			case 'jskl8':
				//极速快乐8
				$content['current']['haoma'] = $haoma;//开奖号码
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$timearr = explode(' ', $open_time);
				if ($timearr[1] == '04:50:00') {//返回的是最后一期
					$next_sendtime = strtotime($timearr[0].'09:30:00') + 86400;//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 90;//当前返回时间加1.5分钟
				}
				break;
			case 'xjppk10':
				$content['current']['haoma'] = $haoma;//开奖号码
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$timearr = explode(' ', $open_time);
				if ($timearr[1] == '04:50:00') {//返回的是最后一期
					$next_sendtime = strtotime($timearr[0].'09:30:00') + 86400;//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 180;//当前返回时间加3分钟
				}
				break;
				
			case 'xypk10': 
				$content['current']['haoma'] = $haoma;//开奖号码
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$timearr = explode(' ', $open_time);
				if ($timearr[1] == '04:50:00') {//返回的是最后一期
					$next_sendtime = strtotime($timearr[0].'09:30:00') + 86400;//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 300;//当前返回时间加5分钟
				}
				break;
				
				case 'xyft':
				 
				$content['current']['haoma'] = $haoma;//开奖号码
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$timearr = explode(' ', $open_time);
				if ($timearr[1] == '23:50:00') {//返回的是最后一期
					$next_sendtime = strtotime($timearr[0].'13:05:00') + 86400;//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 300;//当前返回时间加20分钟
				}
				break;
			case 'jnd28':
				//加拿大28
				$content['current']['haoma'] = intval($haomaarr[0]).','.intval($haomaarr[1]).','.intval($haomaarr[2]);//开奖号码
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$next_sendtime = strtotime($open_time) + 210;
				break;
			case 'cqssc':
				$content['current']['haoma'] = $haoma;//开奖号码
				$issue = substr($content['current']['qishu'], -3);
				if ($issue == '120') {//返回的是当天最后一期
					$next_qishu = date('Ymd', strtotime(substr($content['current']['qishu'], 0, 8)) + 86400).'001';//第二天第一期的时间
				} else {
					$next_qishu = $content['current']['qishu'] + 1;//下期期数
				}
				$timearr = explode(' ', $open_time);//2019-02-19 03:10:00
				$h = intval(substr($timearr[1], 0, 2));//03:10:00
				if ($h == 3) {//返回的是最后一期
					$next_sendtime = strtotime(date('Y-m-d 07:30:00', $time));//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 600;//当前返回时间加20分钟
				}
				break;
		}
		$content['next']['qishu'] = htmlspecialchars(addslashes($next_qishu));//下期期数
		$content['next']['sendtime'] = $next_sendtime;//下期开奖时间戳
		$awardTime = date('Y-m-d H:i:s', $next_sendtime);//下期开奖时间
		$awardTimeInterval = intval($next_sendtime - $time);//下期开奖时间距离当前的时间的时间戳
		$awardtime = $awardTimeInterval < 0 ? 0 : $awardTimeInterval;
	} elseif(strpos($url,'roomDataList') !== false) {
		if ($json->status != 0 || !isset($json->status)) {//1202 token无效
			$re['state'] = 1;
			$re['msg'] = '数据获取异常，5秒后重试';
			$re['last'] = '--';
			$re['code'] = '--';
			$re['time'] = 5;
			return json_encode($re);
		}
		$haomaarr = empty($json->list[0]->spare_1) ? explode(',', trim($json->list[0]->open_result)) : explode('+', trim($json->list[0]->spare_1));
		$haoma = '';
		foreach($haomaarr as $v) {
			$haoma .= intval($v).',';
		}
		$haoma = rtrim($haoma, ',');
		$idarr = $cfgarr[$game]['id'];
		$content['current']['qishu'] = htmlspecialchars(addslashes(trim($json->list[0]->issue)));//当前期号
		$content['current']['sendtime'] = $time;//当前期开奖的时间
		$content['current']['haoma'] = $haoma;//开奖号码
		switch ($game){
			case 'pcdd':
				//PC蛋蛋
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$open_time = date('Y-', $time).trim($json->list[0]->open_time);
				$timearr = explode(' ', $open_time);
				if ($timearr[1] == '23:55:00') {//返回的是最后一期
					$next_sendtime = strtotime($timearr[0].'09:05:00') + 86400;//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 300;//当前返回时间加5分钟
				}
				break;
			case 'pk10':
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$open_time = trim($json->list[0]->open_time);
				$timearr = explode(' ', $open_time);
				if ($timearr[1] == '23:57:00') {//返回的是最后一期
					$next_sendtime = strtotime($timearr[0].'09:07:00') + 86400;//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 1200;//当前返回时间加20分钟
				}
				break;
				
			case 'twpk10':
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$open_time = trim($json->list[0]->open_time);
				$timearr = explode(' ', $open_time);
				if ($timearr[1] == '23:57:00') {//返回的是最后一期
					$next_sendtime = strtotime($timearr[0].'09:07:00') + 86400;//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 90;//当前返回时间加1.5分钟
				}
				break;
			case 'twkl10f':
				
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$open_time = trim($json->list[0]->open_time);
				$timearr = explode(' ', $open_time);
				if ($timearr[1] == '23:57:00') {//返回的是最后一期
					$next_sendtime = strtotime($timearr[0].'09:07:00') + 86400;//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 90;//当前返回时间加1.5分钟
				}
				break;
				case 'xjpkl10f':
				//新加坡快乐10分
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$open_time = trim($json->list[0]->open_time);
				$timearr = explode(' ', $open_time);
				if ($timearr[1] == '23:57:00') {//返回的是最后一期
					$next_sendtime = strtotime($timearr[0].'09:07:00') + 86400;//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 180;//当前返回时间加3分钟
				}
				break;
				case 'xykl10f':
				    
				//幸运快乐10分
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$open_time = trim($json->list[0]->open_time);
				$timearr = explode(' ', $open_time);
				if ($timearr[1] == '23:57:00') {//返回的是最后一期
					$next_sendtime = strtotime($timearr[0].'09:07:00') + 86400;//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 300;//当前返回时间加5分钟
				}
				

				break;
				case 'jskl8':
				//极速快乐8
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$open_time = trim($json->list[0]->open_time);
				$timearr = explode(' ', $open_time);
				if ($timearr[1] == '23:57:00') {//返回的是最后一期
					$next_sendtime = strtotime($timearr[0].'09:07:00') + 86400;//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 90;//当前返回时间加1.5分钟
				}
				break;
			case 'xjppk10':
				
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$open_time = trim($json->list[0]->open_time);
				$timearr = explode(' ', $open_time);
				if ($timearr[1] == '23:57:00') {//返回的是最后一期
					$next_sendtime = strtotime($timearr[0].'09:07:00') + 86400;//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 180;//当前返回时间加3分钟
				}
				break;
			case 'xypk10':
				
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$open_time = trim($json->list[0]->open_time);
				$timearr = explode(' ', $open_time);
				if ($timearr[1] == '23:57:00') {//返回的是最后一期
					$next_sendtime = strtotime($timearr[0].'09:07:00') + 86400;//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 300;//当前返回时间加5分钟
				}
				break;
				
			case 'xyft':
				$content['current']['haoma'] = $haoma;//开奖号码
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$timearr = explode(' ', $open_time);
				if ($timearr[1] == '23:50:00') {//返回的是最后一期
					$next_sendtime = strtotime($timearr[0].'13:05:00') + 86400;//第二天第一期的时间
				} else {
					$next_sendtime = strtotime($open_time) + 300;//当前返回时间加20分钟
				}
				break;
				break;
			case 'jnd28':
				//加拿大28
				$next_qishu = $content['current']['qishu'] + 1;//下期期数
				$open_time = date('Y-', $time).trim($json->list[0]->open_time);
				$next_sendtime = strtotime($open_time) + 210;
				break;
			case 'cqssc':
				$issue = substr($content['current']['qishu'], -3);
				if ($issue == '120') {//返回的是当天最后一期
					$next_qishu = date('Ymd', strtotime(substr($content['current']['qishu'], 0, 8)) + 86400).'001';//第二天第一期的时间
				} else {
					$next_qishu = $content['current']['qishu'] + 1;//下期期数
				}
				$open_time = trim($json->list[0]->open_time);
				$timearr = explode(' ', $open_time);
				$h = intval(substr($timearr[1], 0, 2));//22-02
				if ($h == 2) {//返回的是最后一期
					$next_sendtime = strtotime(date('Y-m-d 10:10:00', $time));//第二天第一期的时间
				} elseif ($h >= 22 || $h <= 2) {
					$next_sendtime = strtotime($open_time) + 300;//当前返回时间加5分钟
				} else {
					$next_sendtime = strtotime($open_time) + 600;//当前返回时间加10分钟
				}
				break;
		}
		$content['next']['qishu'] = htmlspecialchars(addslashes($next_qishu));//下期期数
		$content['next']['sendtime'] = $next_sendtime;//下期开奖时间戳
		$awardTime = date('Y-m-d H:i:s', $next_sendtime);//下期开奖时间
		$awardTimeInterval = intval($next_sendtime - $time);//下期开奖时间距离当前的时间的时间戳
		$awardtime = $awardTimeInterval < 0 ? 0 : $awardTimeInterval;
	} elseif(strpos($url,'apk10.com') !== false) {
		if (empty($json->time) || !isset($json->time)) {
			$re['state'] = 1;
			$re['msg'] = '数据获取异常，5秒后重试';
			$re['last'] = '--';
			$re['code'] = '--';
			$re['time'] = 5;
			return json_encode($re);
		}
		$haomaarr = explode(',', trim($json->current->awardNumbers));
		$haoma = '';
		foreach($haomaarr as $v) {
			$haoma .= intval($v).',';
		}
		$haoma = rtrim($haoma, ',');
		$idarr = $cfgarr[$game]['id'];
		$content['current']['qishu'] = htmlspecialchars(addslashes(trim($json->current->periodNumber)));
		$content['current']['sendtime'] = $time;
		$content['current']['haoma'] = $haoma;
		$content['next']['qishu'] = htmlspecialchars(addslashes(trim($json->next->periodNumber)));
		$content['next']['sendtime'] = strtotime($json->next->awardTime);
		$awardTime = trim($json->next->awardTime);
		$awardTimeInterval = intval($json->next->awardTimeInterval);
		$awardtime = $awardTimeInterval < 0 ? 0 : $awardTimeInterval;
		$awardtime = $game == 'cqssc' ? $awardtime : $awardtime / 1000;//除重庆时时彩，其他需要除以1000
	}else if(strpos($url,'api.eiini.cn') !== false){
		$xml_result =simplexml_load_string($xml_result);
		$xml_result= json_encode($xml_result);
		$result=json_decode($xml_result,true);
		$json = $result['row']['@attributes'];
		// var_dump($json);exit;
		if (!$json) {//1202 token无效
			$re['state'] = 1;
			$re['msg'] = '数据获取异常，5秒后重试';
			$re['last'] = '--';
			$re['code'] = '--';
			$re['time'] = 5;
			return json_encode($re);
		}
		$haoma = $json['opencode'];

		$idarr = $cfgarr[$game]['id'];
		$content['current']['qishu'] = htmlspecialchars(addslashes(trim($json['expect'])));//当前期号
		$content['current']['sendtime'] = $time;//当前期开奖的时间
		$content['current']['haoma'] = $haoma;//开奖号码
		switch ($game){
			case 'jnd28':
				//加拿大28
				$next_qishu = $json['expect'] + 1;//下期期数
				$open_time = $json['opentime'];
				$next_sendtime = strtotime($open_time) + 180;
				break;
		}
		$content['next']['qishu'] = htmlspecialchars(addslashes($next_qishu));//下期期数
		$content['next']['sendtime'] = $next_sendtime;//下期开奖时间戳
		$awardTime = date('Y-m-d H:i:s', $next_sendtime);//下期开奖时间
		$awardTimeInterval = intval($next_sendtime - $time);//下期开奖时间距离当前的时间的时间戳
		$awardtime = $awardTimeInterval < 0 ? 0 : $awardTimeInterval;
	}

	foreach ($idarr as $id) {
		$content['current']['gameid'] = $id;
		$content['next']['gameid'] = $id;
		sendhaoma($content);
	}
	if ($awardtime == 0) {
		$re['state'] = 1;
		$re['msg'] = '下一期正在开奖';
		$re['last'] = $content['current']['qishu'];
		$re['code'] = $content['current']['haoma'];
		$re['time'] = 5;
		return json_encode($re);
	} else {
		$re['state'] = 1;
		$re['msg'] = '下期于'.$awardTime.'开奖';
		$re['last'] = $content['current']['qishu'];
		$re['code'] = $content['current']['haoma'];
		$re['time'] = $awardtime > $maxtime ? $maxtime : $awardtime;
		return json_encode($re);
	}
}
//返回自开奖号码
function gohaoma_sys($gameid, $game) {
    
	global  $db,$cfgarr;
	$time = SYS_TIME;
	$re['name'] = $cfgarr[$game]['name'];
	$maxtime = $cfgarr[$game]['maxtime'];
	
	 switch ($game){
		case 'xgk3':   //香港快3k3
		//期数获取
		$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
		$curtime = time()- $beginToday;
		$curqishu =  intval($curtime / 90) ;
		$nexqishu =  $curqishu+1 ;
		$curqishutime  = $curqishu* 90;
		$nexqishutime =  $curqishutime + 90;
		$fixno = '198579'; //定义一个期数
		$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
		$lastno = ($daynum-1)*576 + $fixno;

		//开奖号码
		$qishu = $lastno+$curqishu;
		$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
		if ($haomadb) {//存在
		   $opennum = $haomadb['yukaihaoma'];
		}
		else{
			$opennum = KillBigLosses(13,$game,$qishu);
			// $opennum = gameLottery($game);
		}
		break;
		case 'xjpk3':   //新加坡快3k3
		//期数获取
		$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
		$curtime = time()- $beginToday;
		$curqishu =  intval($curtime / 180) ;
		$nexqishu =  $curqishu+1 ;
		$curqishutime  = $curqishu* 180;
		$nexqishutime =  $curqishutime + 180;
		$fixno = '198579'; //定义一个期数
		$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
		$lastno = ($daynum-1)*576 + $fixno;

		//开奖号码
		$qishu = $lastno+$curqishu;
		$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
		if ($haomadb) {//存在
		   $opennum = $haomadb['yukaihaoma'];
		}
		else{
			$opennum = KillBigLosses(13,$game,$qishu);
			// $opennum = gameLottery($game);
		}
		break;
        case 'xyk3':   //幸运快3k3
		//期数获取
		$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
		$curtime = time()- $beginToday;
		$curqishu =  intval($curtime / 300) ;
		$nexqishu =  $curqishu+1 ;
		$curqishutime  = $curqishu* 300;
		$nexqishutime =  $curqishutime + 300;
		$fixno = '198579'; //定义一个期数
		$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
		$lastno = ($daynum-1)*576 + $fixno;

		//开奖号码
		$qishu = $lastno+$curqishu;
		$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
		if ($haomadb) {//存在
		   $opennum = $haomadb['yukaihaoma'];
		}
		else{
			$opennum = KillBigLosses(13,$game,$qishu);
			// $opennum = gameLottery($game);
		}
		break;
		case 'teqdd':   //土耳其蛋蛋29
		
		//期数获取
		$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
		$curtime = time()- $beginToday;
		$curqishu =  intval($curtime / 60) ;
		$nexqishu =  $curqishu+1 ;
		$curqishutime  = $curqishu* 60;
		$nexqishutime =  $curqishutime + 60;
		
// 		$fixno = '198579'; //定义一个期数
// 		$daynum = floor( ($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
// 		$lastno = ($daynum-1)*577 + $fixno;
// 		//开奖号码
// 		$qishu = $lastno+$curqishu;
		
		
		$curqishu = 0;
		$time = $curqishutime+$beginToday;
		$fixno = '1175186'; //定义一个期数
		$daynum = floor( ($time-strtotime('2023-02-09'." 00:00:00"))/60);
		$lastno = $daynum + $fixno;
// 		//开奖号码
		$qishu = $lastno+$curqishu;
        
		
		$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
		if ($haomadb) {//存在
		   $opennum = $haomadb['yukaihaoma'];
		}
		else{
			$opennum = KillBigLosses(13,$game,$qishu);
			// $opennum = gameLottery($game);
		}
		break;
		case 'xjpdd':   //新加坡蛋蛋
		//期数获取
		$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
		$curtime = time()- $beginToday;
		$curqishu =  intval($curtime / 300) ;
		$nexqishu =  $curqishu+1 ;
		$curqishutime  = $curqishu* 300;
		$nexqishutime =  $curqishutime + 300;
		$fixno = '198579'; //定义一个期数
		$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
		$lastno = ($daynum-1)*576 + $fixno;

		//开奖号码
		$qishu = $lastno+$curqishu;
		$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
		if ($haomadb) {//存在
		   $opennum = $haomadb['yukaihaoma'];
		}
		else{
			$opennum = KillBigLosses(13,$game,$qishu);
			// $opennum = gameLottery($game);
		}
		break;
		
		case 'twdd':   //台湾蛋蛋
		//期数获取
		$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
		$curtime = time()- $beginToday;
		$curqishu =  intval($curtime / 180) ;
		$nexqishu =  $curqishu+1 ;
		$curqishutime  = $curqishu* 180;
		$nexqishutime =  $curqishutime + 180;
		$fixno = '198579'; //定义一个期数
		$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
		$lastno = ($daynum-1)*576 + $fixno;

		//开奖号码
		$qishu = $lastno+$curqishu;
		$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
		if ($haomadb) {//存在
		   $opennum = $haomadb['yukaihaoma'];
		}
		else{
			$opennum = KillBigLosses(13,$game,$qishu);
			// $opennum = gameLottery($game);
		}
		break;
		
		
		case '3fpc':
		//期数获取
		$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
		$curtime = time()- $beginToday;
		$curqishu =  intval($curtime / 180) ;
		$nexqishu =  $curqishu+1 ;
		$curqishutime  = $curqishu* 180;
		$nexqishutime =  $curqishutime + 180;
		$fixno = '190579'; //定义一个期数
		$daynum = floor(($time-strtotime('2019-08-18'." 00:00:00"))/3600/24);
		$lastno = ($daynum-1)*576 + $fixno;

		//开奖号码
		$qishu = $lastno+$curqishu;
		$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
		if ($haomadb) {//存在
		   $opennum = $haomadb['yukaihaoma'];
		}
		else{
			$opennum = KillBigLosses(25,$game,$qishu);
			// $opennum = gameLottery($game);
		}
		break;
		case '5fpc':
		//期数获取
		$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
		$curtime = time()- $beginToday;
		$curqishu =  intval($curtime / 300) ;
		$nexqishu =  $curqishu+1 ;
		$curqishutime  = $curqishu* 300;
		$nexqishutime =  $curqishutime + 300;
		$fixno = '190579'; //定义一个期数
		$daynum = floor(($time-strtotime('2019-08-18'." 00:00:00"))/3600/24);
		$lastno = ($daynum-1)*576 + $fixno;

		//开奖号码
		$qishu = $lastno+$curqishu;
		$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
		if ($haomadb) {//存在
		   $opennum = $haomadb['yukaihaoma'];
		}
		else{
			$opennum = KillBigLosses(26,$game,$qishu);
			// $opennum = gameLottery($game);
		}
		break;
		case 'jsssc':  //极速时时彩
		//期数获取
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
		$qishu = $lastno+$curqishu;
		// var_dump($qishu);exit;
		$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
		// var_dump($haomadb);exit;
		if ($haomadb) {//存在
		   $opennum = $haomadb['yukaihaoma'];
		}
		else{
			$opennum = KillBigLosses(20,$game,$qishu);
			// $opennum = gameLottery($game);
		}
		break;
		
		case 'jslhc':  //极速六合彩
		//期数获取
		$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
		$curtime = time()- $beginToday;
		$curqishu =  intval($curtime / 120) ;
		$nexqishu =  $curqishu+1 ;
		$curqishutime  = $curqishu* 120;
		$nexqishutime =  $curqishutime + 120;
		$fixno = '50000'; //定义一个期数
		$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
		$lastno = ($daynum-1)*960 + $fixno;

		//开奖号码
		$qishu = $lastno+$curqishu;
		// var_dump($qishu);exit;
		$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
		// var_dump($haomadb);exit;
		if ($haomadb) {//存在
		   $opennum = $haomadb['yukaihaoma'];
		}
		else{
			$opennum = KillBigLosses(20,$game,$qishu);
			// $opennum = gameLottery($game);
		}
		break;
		
		case 'xylhc':  //极速六合彩
		//期数获取
		$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
		$curtime = time()- $beginToday;
		$curqishu =  intval($curtime / 300) ;
		$nexqishu =  $curqishu+1 ;
		$curqishutime  = $curqishu* 300;
		$nexqishutime =  $curqishutime + 300;
		$fixno = '50000'; //定义一个期数
		$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
		$lastno = ($daynum-1)*960 + $fixno;

		//开奖号码
		$qishu = $lastno+$curqishu;
		// var_dump($qishu);exit;
		$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
		// var_dump($haomadb);exit;
		if ($haomadb) {//存在
		   $opennum = $haomadb['yukaihaoma'];
		}
		else{
			$opennum = KillBigLosses(20,$game,$qishu);
			// $opennum = gameLottery($game);
		}
		break;
		
		case 'xg11x5':  //香港11X5
		//期数获取
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
		$qishu = $lastno+$curqishu;
		// var_dump($qishu);exit;
		$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
		// var_dump($haomadb);exit;
		if ($haomadb) {//存在
		   $opennum = $haomadb['yukaihaoma'];
		}
		else{
			$opennum = KillBigLosses(20,$game,$qishu);
			// $opennum = gameLottery($game);
		}
		break;
		case 'xjp11x5':  //新加坡11X5
		//期数获取
		$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
		$curtime = time()- $beginToday;
		$curqishu =  intval($curtime / 180) ;
		$nexqishu =  $curqishu+1 ;
		$curqishutime  = $curqishu* 180;
		$nexqishutime =  $curqishutime + 180;
		$fixno = '238579'; //定义一个期数
		$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
		$lastno = ($daynum-1)*960 + $fixno;

		//开奖号码
		$qishu = $lastno+$curqishu;
		// var_dump($qishu);exit;
		$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
		// var_dump($haomadb);exit;
		if ($haomadb) {//存在
		   $opennum = $haomadb['yukaihaoma'];
		}
		else{
			$opennum = KillBigLosses(20,$game,$qishu);
			// $opennum = gameLottery($game);
		}
		break;
		case 'xy11x5':  //幸运11X5
		//期数获取
		$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
		$curtime = time()- $beginToday;
		$curqishu =  intval($curtime / 300) ;
		$nexqishu =  $curqishu+1 ;
		$curqishutime  = $curqishu* 300;
		$nexqishutime =  $curqishutime + 300;
		$fixno = '238579'; //定义一个期数
		$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
		$lastno = ($daynum-1)*960 + $fixno;

		//开奖号码
		$qishu = $lastno+$curqishu;
		// var_dump($qishu);exit;
		$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
		// var_dump($haomadb);exit;
		if ($haomadb) {//存在
		   $opennum = $haomadb['yukaihaoma'];
		}
		else{
			$opennum = KillBigLosses(20,$game,$qishu);
			// $opennum = gameLottery($game);
		}
		break;
		
		case 'xjpssc':  
		//期数获取
		$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
		$curtime = time()- $beginToday;
		$curqishu =  intval($curtime / 180) ;
		$nexqishu =  $curqishu+1 ;
		$curqishutime  = $curqishu* 180;
		$nexqishutime =  $curqishutime + 180;
		$fixno = '238579'; //定义一个期数
		$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
		$lastno = ($daynum-1)*960 + $fixno;

		//开奖号码
		$qishu = $lastno+$curqishu;
		// var_dump($qishu);exit;
		$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
		// var_dump($haomadb);exit;
		if ($haomadb) {//存在
		   $opennum = $haomadb['yukaihaoma'];
		}
		else{
			$opennum = KillBigLosses(20,$game,$qishu);
			// $opennum = gameLottery($game);
		}
		break;
		
		case 'xyssc': 
		//期数获取
		$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
		$curtime = time()- $beginToday;
		$curqishu =  intval($curtime / 300) ;
		$nexqishu =  $curqishu+1 ;
		$curqishutime  = $curqishu* 300;
		$nexqishutime =  $curqishutime + 300;
		$fixno = '238579'; //定义一个期数
		$daynum = floor(($time-strtotime('2018-06-20'." 00:00:00"))/3600/24);
		$lastno = ($daynum-1)*960 + $fixno;

		//开奖号码
		$qishu = $lastno+$curqishu;
		// var_dump($qishu);exit;
		$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
		// var_dump($haomadb);exit;
		if ($haomadb) {//存在
		   $opennum = $haomadb['yukaihaoma'];
		}
		else{
			$opennum = KillBigLosses(20,$game,$qishu);
			// $opennum = gameLottery($game);
		}
		break;
		case 'pk10': 
			//期数获取
			$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$curtime = time()- $beginToday;
			$curqishu =  intval($curtime / 1200) ;
			$nexqishu =  $curqishu+1 ;
			$curqishutime  = $curqishu* 1200;
			$nexqishutime =  $curqishutime + 1200;
			$fixno = '744176'; //定义一个期数
			$daynum = floor(($time-strtotime('2020-01-20'." 09:32:34"))/3600/24);
			$lastno = ($daynum-1)*44 + $fixno;

			//开奖号码
			$qishu = $lastno+$curqishu;
			// var_dump($qishu);exit;
			$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
			// var_dump($haomadb);exit;
			if ($haomadb) {//存在
			   $opennum = $haomadb['yukaihaoma'];
			}
			else{
				$array   = array( "1", "2", "3", "4", "5","6","7","8", "9","10");
				shuffle($array);
				$opennum = "";
				for($i=0; $i<10; $i++){

				   $opennum .= $array[$i];
				   if($i!=9)
					   $opennum .= ",";
				}
				rtrim($opennum, ',');
			}
		break;
		case 'twpk10': 
			//期数获取
			$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$curtime = time()- $beginToday;
			$curqishu =  intval($curtime / 90) ;
			$nexqishu =  $curqishu+1 ;
			$curqishutime  = $curqishu* 90;
			$nexqishutime =  $curqishutime + 90;
			$fixno = '739424'; //定义一个期数
			$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
			$lastno = ($daynum-1)*43 + $fixno;

			//开奖号码
			$qishu = $lastno+$curqishu;
			// var_dump($qishu);exit;
			$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
			// var_dump($haomadb);exit;
			if ($haomadb) {//存在
			   $opennum = $haomadb['yukaihaoma'];
			}
			else{
				$array   = array( "1", "2", "3", "4", "5","6","7","8", "9","10");
				shuffle($array);
				$opennum = "";
				for($i=0; $i<10; $i++){

				   $opennum .= $array[$i];
				   if($i!=9)
					   $opennum .= ",";
				}
				rtrim($opennum, ',');
			}
		break;
		case 'twkl10f': 
			//期数获取-台湾快乐10分kl10f
			$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$curtime = time()- $beginToday;
			$curqishu =  intval($curtime / 90) ;
			$nexqishu =  $curqishu+1 ;
			$curqishutime  = $curqishu* 90;
			$nexqishutime =  $curqishutime + 90;
			$fixno = '739424'; //定义一个期数
			$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
			$lastno = ($daynum-1)*43 + $fixno;

			//开奖号码
			$qishu = $lastno+$curqishu;
			// var_dump($qishu);exit;
			$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
			// var_dump($haomadb);exit;
			if ($haomadb) {//存在
			   $opennum = $haomadb['yukaihaoma'];
			}
			else{
				$array   = array( "1", "2", "3", "4", "5","6","7","8", "9","10","11", "12", "13", "14", "15","16","17","18", "19","20");
				shuffle($array);
				$opennum = "";
				for($i=0; $i<8; $i++){

				   $opennum .= $array[$i];
				   if($i!=7)
					   $opennum .= ",";
				}
				rtrim($opennum, ',');
			}
		break;
		case 'xjpkl10f': 
			//期数获取-新加坡快乐10分kl10f
			$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$curtime = time()- $beginToday;
			$curqishu =  intval($curtime / 180) ;
			$nexqishu =  $curqishu+1 ;
			$curqishutime  = $curqishu* 180;
			$nexqishutime =  $curqishutime + 180;
			$fixno = '739424'; //定义一个期数
			$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
			$lastno = ($daynum-1)*43 + $fixno;

			//开奖号码
			$qishu = $lastno+$curqishu;
			// var_dump($qishu);exit;
			$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
			// var_dump($haomadb);exit;
			if ($haomadb) {//存在
			   $opennum = $haomadb['yukaihaoma'];
			}
			else{
				$array   = array( "1", "2", "3", "4", "5","6","7","8", "9","10","11", "12", "13", "14", "15","16","17","18", "19","20");
				shuffle($array);
				$opennum = "";
				for($i=0; $i<8; $i++){

				   $opennum .= $array[$i];
				   if($i!=7)
					   $opennum .= ",";
				}
				rtrim($opennum, ',');
			}
		break;
		case 'xykl10f': 
			//期数获取-幸运快乐10分kl10f
			$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$curtime = time()- $beginToday;
			$curqishu =  intval($curtime / 300) ;
			$nexqishu =  $curqishu+1 ;
			$curqishutime  = $curqishu* 300;
			$nexqishutime =  $curqishutime + 300;
			$fixno = '739424'; //定义一个期数
			$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
			$lastno = ($daynum-1)*43 + $fixno;

			//开奖号码
			$qishu = $lastno+$curqishu;
			//var_dump($qishu);exit;
			$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
			// var_dump($haomadb);exit;
			if ($haomadb) {//存在
			   $opennum = $haomadb['yukaihaoma'];
			}
			else{
				$array   = array( "1", "2", "3", "4", "5","6","7","8", "9","10","11", "12", "13", "14", "15","16","17","18", "19","20");
				shuffle($array);
				$opennum = "";
				for($i=0; $i<8; $i++){

				   $opennum .= $array[$i];
				   if($i!=7)
					   $opennum .= ",";
				}
				rtrim($opennum, ',');
			}
		break;
		case 'xjppk10': 
			//期数获取
			$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$curtime = time()- $beginToday;
			$curqishu =  intval($curtime / 180) ;
			$nexqishu =  $curqishu+1 ;
			$curqishutime  = $curqishu* 180;
			$nexqishutime =  $curqishutime + 180;
			$fixno = '739424'; //定义一个期数
			$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
			$lastno = ($daynum-1)*43 + $fixno;

			//开奖号码
			$qishu = $lastno+$curqishu;
			// var_dump($qishu);exit;
			$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
			// var_dump($haomadb);exit;
			if ($haomadb) {//存在
			   $opennum = $haomadb['yukaihaoma'];
			}
			else{
				$array   = array( "1", "2", "3", "4", "5","6","7","8", "9","10");
				shuffle($array);
				$opennum = "";
				for($i=0; $i<10; $i++){

				   $opennum .= $array[$i];
				   if($i!=9)
					   $opennum .= ",";
				}
				rtrim($opennum, ',');
			}
		break;
		case 'jskl8': 
			//期数获取   极速快乐8
			$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$curtime = time()- $beginToday;
			$curqishu =  intval($curtime / 90) ;
			$nexqishu =  $curqishu+1 ;
			$curqishutime  = $curqishu* 90;
			$nexqishutime =  $curqishutime + 90;
			$fixno = '739424'; //定义一个期数
			$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
			$lastno = ($daynum-1)*43 + $fixno;

			//开奖号码
			$qishu = $lastno+$curqishu;
			// var_dump($qishu);exit;
			$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
			// var_dump($haomadb);exit;
			if ($haomadb) {//存在
			   $opennum = $haomadb['yukaihaoma'];
			}
			else{
				$array   = array( "1", "2", "3", "4", "5","6","7","8", "9","10");
				shuffle($array);
				$opennum = "";
				for($i=0; $i<8; $i++){

				   $opennum .= $array[$i];
				   if($i!=7)
					   $opennum .= ",";
				}
				rtrim($opennum, ',');
			}
		break;
		case 'xypk10': 
			//期数获取
			$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
			$curtime = time()- $beginToday;
			$curqishu =  intval($curtime / 300) ;
			$nexqishu =  $curqishu+1 ;
			$curqishutime  = $curqishu* 300;
			$nexqishutime =  $curqishutime + 300;
			$fixno = '739424'; //定义一个期数
			$daynum = floor(($time-strtotime('2019-09-27'." 00:00:00"))/3600/24);
			$lastno = ($daynum-1)*43 + $fixno;

			//开奖号码
			$qishu = $lastno+$curqishu;
			// var_dump($qishu);exit;
			$haomadb = $db -> get_one("qishu = '$qishu' AND yukaihaoma != ''");
			// var_dump($haomadb);exit;
			if ($haomadb) {//存在
			   $opennum = $haomadb['yukaihaoma'];
			}
			else{
				$array   = array( "1", "2", "3", "4", "5","6","7","8", "9","10");
				shuffle($array);
				$opennum = "";
				for($i=0; $i<10; $i++){

				   $opennum .= $array[$i];
				   if($i!=9)
					   $opennum .= ",";
				}
				rtrim($opennum, ',');
			}
		break;
	}

	$idarr = $cfgarr[$game]['id'];
	$content['current']['qishu']    = $lastno+$curqishu;
	$content['current']['sendtime'] = $curqishutime+$beginToday;
	$content['current']['haoma']    = $opennum;
	$content['current']['is_lottery']    = 1;
	$content['next']['qishu']       = $lastno+$curqishu +1;
	$content['next']['sendtime']    = $nexqishutime+$beginToday;
	//当前期剩余时间
	$awardtime = $nexqishutime - $curtime;

    

	foreach ($idarr as $id) {
			$content['current']['gameid'] = $id;
			$content['next']['gameid'] = $id;
			sendhaoma($content);
		}
		if ($awardtime == 0) {
			$re['state'] = 1;
			$re['msg'] = '下一期正在开奖';
			$re['last'] = $content['current']['qishu'];
			$re['code'] = $content['current']['haoma'];
			$re['time'] = 5;
			return json_encode($re);
		} else {
			$re['state'] = 1;
			$re['msg'] = '下期于'.$awardTime.'开奖';
			$re['last'] = $content['current']['qishu'];
			$re['code'] = $content['current']['haoma'];
			$re['time'] = $awardtime > $maxtime ? $maxtime : $awardtime;
			return json_encode($re);
		}
}

//开奖号码公用入库
function sendhaoma($content) {
	global $db;
	$gameid = $content['current']['gameid'];
	$current_qishu = $content['current']['qishu'];
	$next_qishu = $content['next']['qishu'];
	$current = $db -> get_one(array('gameid' => $gameid, 'qishu' => $current_qishu));
	if ($current) {//存在当期记录
		if ($current['haoma'] == '') {//没有更新号码
			$db -> update($content['current'], array('gameid' => $gameid, 'qishu' => $current_qishu));//更新
		}
	} else {//当期记录没有
		$db -> insert($content['current']);
	}
	//写入下一期
	if (!$next_qishu || $next_qishu - $current_qishu > 1) {//无期数 或 跨期的时候不要写入下一期
		return false;
	}
	$next = $db -> get_one(array('gameid' => $gameid, 'qishu' => $next_qishu));
	if (!$next) {//没有下期
		$db -> insert($content['next']);//写入下一期记录
	}
}

//杀大赔小
function KillBigLosses($gameid,$game,$qishu){
	global $cfgarr, $account;
	$order = base :: load_model('order_model') -> get_one("qishu = '{$qishu}' AND gameid = {$gameid}","*","money DESC");
	if($order){
		$opennum = gameLottery($game);
		$haomaarr = explode(',', $opennum);
		$template = $cfgarr[$game]['template'];
		if(is_array($template)) $template = $template[0];
		$account_fun = 'account_'.$template;
		$wanfa_account_fun = 'wanfa_account_'.$template;
		$pro = $account_fun($gameid, $haomaarr);
		$return = $wanfa_account_fun($order, $pro['zhi'], $pro['bharr'], $haomaarr, $gameid);
		$fact = false;
		if (strpos($return, '@') !== false) {//输
			$multiple = -1;
			if ($order['ban'] == 1) {
				$wfarr = explode('@', $return);//拆分投注玩法
				$multiple = $wfarr[1];
				$update['wanfa'] = $return;
			}
		} elseif (strpos($return, '#') !== false) {//按照指定金额处理
			$arr = explode('#', $return);
			$multiple = $arr[1];
			$fact = true;
		} else {//返回赔率
			$multiple = $return;
		}
		if ($multiple >= 0) {
			return KillBigLosses($gameid,$game,$qishu);
		}else{
			return $opennum;
		}
	}else{
		return gameLottery($game);
	}
}

function gameLottery($game){
	switch ($game){
		case 'xgk3':   //香港快3k3台湾k3
			$array   = array( "1", "2", "3", "4", "5","6","1", "2", "3", "4", "5","6","1", "2", "3", "4", "5","6",);
			shuffle($array);
			$opennum = "";
			for($i=0; $i<3; $i++){

			   $opennum .= $array[$i];
			   if($i!=2)
				   $opennum .= ",";
			}
			rtrim($opennum, ',');
		break;
		case 'xjpk3':   //新加坡快3k3
			$array   = array( "1", "2", "3", "4", "5","6","1", "2", "3", "4", "5","6","1", "2", "3", "4", "5","6",);
			shuffle($array);
			$opennum = "";
			for($i=0; $i<3; $i++){

			   $opennum .= $array[$i];
			   if($i!=2)
				   $opennum .= ",";
			}
			rtrim($opennum, ',');
		break;
		case 'xyk3':   //幸运快3k3
			$array   = array( "1", "2", "3", "4", "5","6","1", "2", "3", "4", "5","6","1", "2", "3", "4", "5","6",);
			shuffle($array);
			$opennum = "";
			for($i=0; $i<3; $i++){

			   $opennum .= $array[$i];
			   if($i!=2)
				   $opennum .= ",";
			}
			rtrim($opennum, ',');
		break;
		case 'teqdd':   //土耳其蛋蛋
			$array   = array( "0","1", "2", "3", "4", "5","6","7","8", "9");
			shuffle($array);
			$opennum = "";
			for($i=0; $i<3; $i++){

			   $opennum .= $array[$i];
			   if($i!=2)
				   $opennum .= ",";
			}
			rtrim($opennum, ',');
		break;
		
			case 'xjpdd':   //新加坡蛋蛋
			$array   = array( "0","1", "2", "3", "4", "5","6","7","8", "9");
			shuffle($array);
			$opennum = "";
			for($i=0; $i<3; $i++){

			   $opennum .= $array[$i];
			   if($i!=2)
				   $opennum .= ",";
			}
			rtrim($opennum, ',');
		break;
		
		case 'twdd':   //新加坡蛋蛋
			$array   = array( "0","1", "2", "3", "4", "5","6","7","8", "9");
			shuffle($array);
			$opennum = "";
			for($i=0; $i<3; $i++){

			   $opennum .= $array[$i];
			   if($i!=2)
				   $opennum .= ",";
			}
			rtrim($opennum, ',');
		break;
		
		
		case '3fpc':
			$array   = array( "0","1", "2", "3", "4", "5","6","7","8", "9");
			shuffle($array);
			$opennum = "";
			for($i=0; $i<3; $i++){

			   $opennum .= $array[$i];
			   if($i!=2)
				   $opennum .= ",";
			}
			rtrim($opennum, ',');
		break;
		case '5fpc':
			$array   = array( "0","1", "2", "3", "4", "5","6","7","8", "9");
			shuffle($array);
			$opennum = "";
			for($i=0; $i<3; $i++){

			   $opennum .= $array[$i];
			   if($i!=2)
				   $opennum .= ",";
			}
			rtrim($opennum, ',');
		break;
		case 'jsssc':  //极速时时彩
			$array   = array( "0","1", "2", "3", "4", "5","6","7","8", "9");
			shuffle($array);
			$opennum = "";
			for($i=0; $i<5; $i++){

			   $opennum .= $array[$i];
			   if($i!=4)
				   $opennum .= ",";
			}
			rtrim($opennum, ',');
		break;
		
		case 'jslhc':  //极速六合彩
			$array   = array( "1", "2", "3", "4", "5","6","7","8", "9","10","11", "12", "13", "14", "15","16","17","18", "19","20","21", "22", "23", "24", "25","26","27","28", "29","30","31", "32", "33", "34", "35","36","37","38", "39","40","41", "42", "43", "44", "45","46","47","48", "49");
			shuffle($array);
			$opennum = "";
			for($i=0; $i<7; $i++){

			   $opennum .= $array[$i];
			   if($i!=6)
				   $opennum .= ",";
			}
			rtrim($opennum, ',');
		break;
		
		case 'xylhc':  //幸运六合彩
			$array   = array( "1", "2", "3", "4", "5","6","7","8", "9","10","11", "12", "13", "14", "15","16","17","18", "19","20","21", "22", "23", "24", "25","26","27","28", "29","30","31", "32", "33", "34", "35","36","37","38", "39","40","41", "42", "43", "44", "45","46","47","48", "49");
			shuffle($array);
			$opennum = "";
			for($i=0; $i<7; $i++){

			   $opennum .= $array[$i];
			   if($i!=6)
				   $opennum .= ",";
			}
			rtrim($opennum, ',');
		break;
		
	    case 'xg11x5':  //香港11X5
			$array   = array( "10","1", "2", "3", "4", "5","6","7","8", "9", "11");
			shuffle($array);
			$opennum = "";
			for($i=0; $i<5; $i++){

			   $opennum .= $array[$i];
			   if($i!=4)
				   $opennum .= ",";
			}
			rtrim($opennum, ',');
		break;
		case 'xjp11x5':  //新加坡11X5
			$array   = array( "10","1", "2", "3", "4", "5","6","7","8", "9", "11");
			shuffle($array);
			$opennum = "";
			for($i=0; $i<5; $i++){

			   $opennum .= $array[$i];
			   if($i!=4)
				   $opennum .= ",";
			}
			rtrim($opennum, ',');
		break;
		case 'xy11x5':  //幸运11X5
			$array   = array( "10","1", "2", "3", "4", "5","6","7","8", "9", "11");
			shuffle($array);
			$opennum = "";
			for($i=0; $i<5; $i++){

			   $opennum .= $array[$i];
			   if($i!=4)
				   $opennum .= ",";
			}
			rtrim($opennum, ',');
		break;
		
		case 'xjpssc':  //极速时时彩
			$array   = array( "0","1", "2", "3", "4", "5","6","7","8", "9");
			shuffle($array);
			$opennum = "";
			for($i=0; $i<5; $i++){

			   $opennum .= $array[$i];
			   if($i!=4)
				   $opennum .= ",";
			}
			rtrim($opennum, ',');
		break;
		
		case 'xyssc':  //极速时时彩
			$array   = array( "0","1", "2", "3", "4", "5","6","7","8", "9");
			shuffle($array);
			$opennum = "";
			for($i=0; $i<5; $i++){

			   $opennum .= $array[$i];
			   if($i!=4)
				   $opennum .= ",";
			}
			rtrim($opennum, ',');
		break;
	}
	return $opennum;
}
?>