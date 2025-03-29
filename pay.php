<?php
  $tjurl = 'https://www.weiyi66pay.com/Pay_Order_create.html';
  $Md5key = 'nkq2q46ngfzoyjn211j9745wkfbq68yk';
  $data = [
    'MemberId' => 6027,
	'OrderId'  => time().rand(1000, 9999),
	'Date'     => date("Y-m-d H:i:s"),
	'PayType'  => 35,
	'NotifyUrl'   => "/notifyurl.php",
	'CallbackUrl' => "/callback.php",
	'Amount'   => 100
  ];  
  $data['Sign'] = getSign($data);
  $params = "";
  
	foreach ($data as $key => $val) {
		$params .= '<input type="hidden" name="' . $key . '" value="' . $val . '">';
	}
	exit('<!DOCTYPE html>
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
				<div class="row" style="margin:15px;0;">
					<div class="col-md-12">
						<form id="form1" class="form-inline" method="post" action="'.$tjurl.'">'
							.$params.'
						</form>
					</div>
				</div>
			</div>
			<script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
			<script>
			   $("#form1").submit();
			</script>
			</body>
			</html>');
  
  function getSign($data){
	 global $Md5key;
     ksort($data);
	 $md5str = "";
	 foreach ($data as $key => $val) {
		$md5str = $md5str . $key . "=" . $val . "&";
	 }
	 $sign = strtoupper(md5($md5str."key=" . $Md5key));
	 return $sign;
  }
?>