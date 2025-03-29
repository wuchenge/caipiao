<?php
defined('IN_MYWEB') or exit('No permission resources.');
header('Content-Type: application/json; charset=utf-8');
$type = trim($_REQUEST['type']);
$gameid = intval($_REQUEST['gameid']);
if (empty($type) || !$gameid) {
	$re['state'] = 0;
	$re['msg'] = 'ERR-'.SYS_TIME;
	echo json_encode($re);
	exit;
}
//print_r('<pre>');

$db = base :: load_model('haoma_model');

if ($type == 'haoma') {
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
	$haomadata['re_max'] = 30;
	$haomadata['awartime'] = $haomadata['awartime'] < 0 ? 0 : $haomadata['awartime'] * 1000;
	if ($haomadata['awartime'] == 0 && $haomadata['re_max']) {//如果已到开奖时间
		//依据开奖延迟时间合理设置请求频率
		if (intval($_GET['re'])) {//如果不是第一次请求
			$haomadata['re'] = 5 * 1000;//返回下一次请求的时间
		} else {
			$haomadata['re'] = $haomadata['re_max'] * 1000;//返回下一次请求的时间源码分享站ymfxz.com
		}
	}
} elseif ($type == 'top') {
	$haomadata = $db -> select("gameid = '$gameid' AND haoma <> ''", '*', 10, 'qishu DESC');
} elseif ($type == 'list') {
	$where = "gameid = '$gameid'";
	$daytime = date('Y-m-d', SYS_TIME);
	$sendtime = strtotime($daytime);
	$endtime = $sendtime + 86400;
	$where .= " AND (sendtime >= '$sendtime' AND sendtime < '$endtime')";
	$haomadata = $db -> select($where, '*', '', 'qishu DESC');
}
echo json_encode($haomadata);
exit;

?>