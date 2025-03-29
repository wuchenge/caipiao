<?php
/*
header("Content-type:text/html;charset=utf-8");
     $signType = "MD5";
    $orderNo = $_REQUEST["payid"];
	$type="legal";
	$orderAmount = $_REQUEST["money"];
    $amount = 0;   
    $orderCurrency = $_REQUEST["type"];
	$coin="USDT";
	$customerId="MN1576478969485";
	$key123="7c97fd40-a38a-4748-aae5-66b21cf940eb";
    $crmNo = "MN1576478969485";  

    $pickupUrl = "http://".$_SERVER['HTTP_HOST'].'/';
    $receiveUrl = "http://".$_SERVER['HTTP_HOST'] . '/ddpay/notify.php';
    
    $sign = md5($pickupUrl. $receiveUrl . $signType . $orderNo . $orderAmount . $orderCurrency . $customerId . $key123);
*/

   $orderNo = $_REQUEST["payid"];
   $orderAmount = $_REQUEST["money"];
   $type = $_REQUEST["type"];
   if($type == 'zfbsm'){
   	  $type = 'zfbh5';
   }
   pay($orderNo, $orderAmount, $type);
   
   function pay($orderNo, $money, $type){
		$key = '473E871EDF87414C899158D34D2229DE';
		$mch = "SH8079";
		$time = time();
		$notifyUrl = "http://".$_SERVER['SERVER_NAME']."/ddpay/notify.php";
		$callbackUrl = "http://".$_SERVER['SERVER_NAME']."/";
		$order_no = $orderNo;
		$amount = $money*100;
		$pay_type = $type;
		$subject = "会员充值";
		$extend = 1234;
		$source= "amount=".$amount."&callback_url=".$callbackUrl."&extend=".$extend."&merchant=".$mch."&notify_url=".$notifyUrl."&order_no=".$order_no."&order_time=".$time."&pay_type=".$pay_type."&subject=".$subject."&key=".$key;
		$sign = sha1($source);
		$return_array = [
			"sign" => $sign,
			"order_no" => $order_no,
			"notify_url" => $notifyUrl,
			"callback_url" => $callbackUrl,
			"merchant" => $mch,
			"amount" => $amount,
			"order_time" => $time,
			"pay_type" => $pay_type,
			"subject" => $subject,
			"extend" => $extend,
		];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, "http://api.youdaopay.net/api/addorder");

		$paramstr = "";
		foreach ($return_array as $key => $val) {
			$paramstr = $paramstr . $key . "=" . $val . "&";
		}
		$paramstr = rtrim($paramstr, '&');
		//echo $paramstr;echo '<hr>';	// 打印返回结果[debug]
		curl_setopt($ch, CURLOPT_POSTFIELDS, $paramstr);
		$contents = curl_exec($ch);
		////echo $contents;echo '<hr>';	// 打印返回结果[debug]
		$parseJson = json_decode($contents);
		
		if($parseJson){
			if ($parseJson->success == true) {
				$result = $parseJson->result;
				$qrCode = $result->qrCode;
				header("Location:" . $qrCode);	// 这里立即自动跳转到H5付款链接
			} else {
				echo "接口失败，请联系客服充值!";
			}
		}else{
			echo '<!DOCTYPE html>
					<html lang="zh-CN">
					<head>
						<meta charset="utf-8">
						<meta http-equiv="X-UA-Compatible" content="IE=edge">
						<meta name="viewport" content="width=device-width, initial-scale=1">
						<title>支付</title>
						<!-- Bootstrap -->
						<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css"
							  integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
						<!--[if lt IE 9]>
						<script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
						<script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
						<![endif]-->
					</head>
					<body>
					<div class="container">
						金额有误，请根据金额重新选择通道:
						<p>银联：100-10000</p>
						<p>支付宝：500-20000</p>
					</div>
					<script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
					</body>
					</html>';
		}
	}

?>

<!--
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>支付</title>
</head>
<body onLoad="document.submitpay.submit();">
        <form type="hidden" name="submitpay"  action="https://otc.globalokpaytech.com/paygateway/toCashierIndex" method="get">
        <input type="hidden" name='signType' id='signType' type='text' value='<?php echo $signType;?>' />
        <input type="hidden" name='orderNo' id='orderNo' type='text' value='<?php echo $orderNo;?>' />
        <input type="hidden" name='type' id='type' type='text' value='<?php echo $type;?>'/>
        <input type="hidden" name='orderAmount' id='orderAmount' type='text' value='<?php echo $orderAmount;?>'/>
        <input type="hidden" name='amount' id='amount' type='text' value='<?php echo $amount;?>'/>
        <input type="hidden" name='orderCurrency' id='orderCurrency' type='text' value='<?php echo $orderCurrency;?>'/>
        <input type="hidden" name='coin' id='coin' type='text' value='<?php echo $coin;?>'/>
        <input type="hidden" name='customerId' id='customerId' type='text' value='<?php echo $customerId;?>'/>
        <input type="hidden" name='crmNo' id='crmNo' type='text' value='<?php echo $crmNo;?>'/>
		<input type="hidden" name='pickupUrl' id='pickupUrl' type='text' value='<?php echo $pickupUrl;?>'/>
        <input type="hidden" name='receiveUrl' id='receiveUrl' type='text' value='<?php echo $receiveUrl;?>'/>
        <input type="hidden" name='sign' id='sign' type='text' value='<?php echo $sign;?>'/>
        </form>   
   </body>
</html>-->

		