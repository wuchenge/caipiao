<?php   
 header("Access-Control-Allow-Origin:*"); 
	header('Access-Control-Allow-Headers:x-requested-with,content-type'); 
	$rws_post = file_get_contents("php://input");
    file_put_contents("log.txt", $rws_post, FILE_APPEND);
    $mypost = json_decode($rws_post, 1);
	
	//{"amount":"5000","channelType":"125","extend":"extend1","merchant":"SH8001","orderId":"test20200225","sign":"f5f1a77b0767c3c3bf9144618dffc46764920afa","status":1,"subject":"test","tranNo":"YDZF202002252307340920681"}
	if($mypost){
		$orderuid = $mypost['orderId'];
		$status   = $mypost['status'];
		$price    = round($mypost['amount']/100,2);
	}else{
		exit("fail0");
	}
   // $orderuid = $_REQUEST["orderNo"];
   if($status=="1"){
	  
     //校验key成功，是自己人。执行自己的业务逻辑：加余额，订单付款成功，装备购买成功等等。
	$db = array(
	    'dsn' => 'mysqli:host=localhost;dbname=zgcp_shop_key',
	    'host' => 'localhost',
	    'port' => '3306',
	    'dbname' => 'zgcp_shop_key',
	    'username' => 'zgcp_shop_key',
	    'password' => 'mzTwynCBtbhGtAzx124zdgw24t@%',
	    'charset' => 'utf8',
	);
	
	$link = mysqli_connect($db['host'], $db['username'], $db['password']) or die( 'Could not connect: '  .  mysqli_error ($link));
	$addtime = time();
	mysqli_select_db($link, $db['dbname']) or die ( 'Can\'t use foo : '  .  mysqli_error ($link));
	mysqli_set_charset($link, $db['charset']);
	$result  = mysqli_query($link, "select * from bc_pay where payid='{$orderuid}'");
	$row = mysqli_fetch_array($result);
	if($row["state"]=="0"){
		mysqli_query($link , "update bc_pay set state='1',comment='在线支付' where  payid='".$orderuid."'"); 
		mysqli_query($link , "update bc_user set money=money+{$price} where  uid={$row['uid']}");	        
		echo "success";
	}else{
		echo "fail";
	}
   }else{
   	echo "fail";
   }

