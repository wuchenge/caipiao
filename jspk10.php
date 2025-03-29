<?php
$api = "https://vip.28a.vip/query?format=json&code=10061&limit=5&token=FCC9DB0889B87BFB";
$data = file_get_contents($api);
if($data){
	$data = json_decode($data,1);
	if($data){
		$lotCode  = 'jndk8';
		$expect   = $data['data'][0]['expect'];
		$opencode = $data['data'][0]['opencode'];
		$opentime = $data['data'][0]['opentime'];
     //	$drawTime = $opentime + 150; 
    //$drawTime = $opentime+3*60;
	   $dqtime = date("Y-m-d H:i:s");
       $catime = strtotime($opentime);
	   $drawTime = date('Y-m-d H:i:s', $catime+3*60);
	   $drawIssue = $expect+1;
		$ret = array("sign"=>true,
		             "message"=>'获取成功',
					 "data"=>array(
					     "title" =>'xyft',
						 "name"  =>'xyft',
						 "expect"=>$expect,
						 "opencode"=>$opencode,
						 "opentime"=>$opentime,
						 "source"=>'08TK',
						 "sourcecode"=>$lotCode,
						 "drawTime"=>$drawTime 
					 ));
		//echo json_encode($ret);
		//echo '{"sign":true,"message":"获取成功","data":[{"title":"xyft","name":"xyft","expect":"'.$expect.'","opencode":"'.$opencode.'","opentime":"'.$opentime.'","source":"开彩采集","sourcecode":""}]}';


		echo '{"errorCode":0,"message":"操作成功","result":{"businessCode":0,"message":"操作成功","data":{"serverTime":"'.$dqtime.'","lotCode":1,"lotName":"加拿大28","iconUrl":"","totalCount":179,"shelves":1,"groupCode":46,"frequency":"","index":100,"preDrawIssue":'.$expect.',"preDrawTime":"'.$opentime.'","preDrawCode":'.$opencode.',"drawTime":"'.$drawTime.'","drawIssue":'.$drawIssue.',"sumNum":10,"sumSingleDouble":-1,"sumBigSmall":-1,"sumMiddle":"","sumLimit":"","drawCount":179,"sdrawCount":"","status":0}}}';
	}
}
?>
