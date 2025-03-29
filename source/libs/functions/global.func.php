<?php
/**
 * 返回经addslashes处理过的字符串或数组
 *
 * @param  $string 需要处理的字符串或数组
 * @param  $string 所有参数在通过路由时已进行new_addslashes过滤
 * @return mixed
 */
function new_addslashes($string) {
	if (!is_array($string)) return addslashes($string);
	foreach($string as $key => $val) $string[$key] = new_addslashes($val);
	return $string;
}

/**
 * 返回经stripslashes处理过的字符串或数组
 *
 * @param  $string 需要处理的字符串或数组
 * @return mixed
 */
function new_stripslashes($string) {
	if (!is_array($string)) return stripslashes($string);
	foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
	return $string;
}

/**
 * 返回经htmlspecialchars处理过的字符串或数组
 *
 * @param  $obj 需要处理的字符串或数组
 * @return mixed
 */
function new_htmlspecialchars($string) {
	if (!is_array($string)) return htmlspecialchars($string);
	foreach($string as $key => $val) $string[$key] = new_htmlspecialchars($val);
	return $string;
}

/**
 * 安全过滤函数
 *
 * @param  $string
 * @return string
 */
function safe_replace($string) {
	$string = str_replace('%20', '', $string);
	$string = str_replace('%27', '', $string);
	$string = str_replace('%2527', '', $string);
	$string = str_replace('"', '&quot;', $string);
	// $string = str_replace("'", "", $string);
	$string = str_replace('<', '&lt;', $string);
	$string = str_replace('>', '&gt;', $string);
	return $string;
	// return new_htmlspecialchars($string);
}

/**
 * 获取当前页面完整URL地址
 */
function get_url() {
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? safe_replace($_SERVER['PHP_SELF']) : safe_replace($_SERVER['SCRIPT_NAME']);
	$path_info = isset($_SERVER['PATH_INFO']) ? safe_replace($_SERVER['PATH_INFO']) : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . safe_replace($_SERVER['QUERY_STRING']) : $path_info);
	return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
}

/**
 * 获取请求ip
 *
 * @return ip 地址
 */
function get_onlineip() {
	$onlineip = '';
	if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	return preg_match ('/[\d\.]{7,15}/', $onlineip, $matches) ? $matches[0] : '';
	// return $onlineip;
}

function get_client_ip() {
	$ip = $_SERVER['REMOTE_ADDR'];
	if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
		foreach ($matches[0] AS $xip) {
			if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
				$ip = $xip;
				break;
			}
		}
	}
	return $ip;
}

/**
 * 产生随机字符串
 *
 * @param int $length 输出长度
 * @param string $chars 可选的
 * @return string 字符串
 */
function random($length, $chars = 'abcdefghijklmnopqrstuvwxyz0123456789') {
	$hash = '';
	$max = strlen($chars) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}

/**
 * 字符串加密、解密函数
 *
 * @param string $txt 字符串
 * @param string $operation ENCODE为加密，DECODE为解密，可选参数，默认为ENCODE，
 * @param string $key 密钥：数字、字母、下划线
 * @return string
 */
function sys_auth($txt, $operation = 'ENCODE', $key = '') {
	$key = $key ? $key : base :: load_config('system', 'auth_key');
	$txt = $operation == 'ENCODE' ? (string)$txt : base64_decode($txt);
	$len = strlen($key);
	$code = '';
	for($i = 0; $i < strlen($txt); $i++) {
		$k = $i % $len;
		$code .= $txt[$i] ^ $key[$k];
	}
	$code = $operation == 'DECODE' ? $code : base64_encode($code);
	return $code;
}

/**
 * 语言文件处理
 *
 * @param string $language 标示符
 * @param array $pars 转义的数组,二维数组 ,'key1'=>'value1','key2'=>'value2',
 * @param string $modules 多个模块之间用半角逗号隔开，如：member,guestbook
 * @return string 语言字符
 */
function L($language = 'no_language', $pars = array(), $modules = '') {
	static $LANG = array();
	if (!$LANG) {
		require_once FILE_PATH . 'languages' . DIRECTORY_SEPARATOR . ROUTE_LANG . DIRECTORY_SEPARATOR . 'system.lang.php';
		if (file_exists(FILE_PATH . 'languages' . DIRECTORY_SEPARATOR . ROUTE_LANG . DIRECTORY_SEPARATOR . ROUTE_M . '.lang.php')) require_once FILE_PATH . 'languages' . DIRECTORY_SEPARATOR . ROUTE_LANG . DIRECTORY_SEPARATOR . ROUTE_M . '.lang.php';
		if (!empty($modules)) {
			$modules = explode(',', $modules);
			foreach($modules AS $m) {
				require_once FILE_PATH . 'languages' . DIRECTORY_SEPARATOR . ROUTE_LANG . DIRECTORY_SEPARATOR . $m . '.lang.php';
			}
		}
	}
	if (!array_key_exists($language, $LANG)) {
		return $LANG['no_language'] . '[' . $language . ']';
	} else {
		$language = $LANG[$language];
		if ($pars) {
			foreach($pars AS $_k => $_v) {
				$language = str_replace('{' . $_k . '}', $_v, $language);
			}
		}
		return $language;
	}
}

/**
 * 模板调用
 *
 * @param  $template
 * @param  $dir
 * @param  $style
 * @return unknown_type
 */
function template($template, $dir = '', $style = '') {
	if (!$style) $style = base :: load_config('system', 'tpl_name'); //设置的模板方案目录
	$root = base :: load_config('system', 'tpl_root'); //标准模板物理目录
	$dirfile = MYFILE_PATH . $dir . DIRECTORY_SEPARATOR . $template . '.html';
	$stylefile = MYFILE_PATH . $root . DIRECTORY_SEPARATOR . $style . DIRECTORY_SEPARATOR . $template . '.html';
	$defaultfile = MYFILE_PATH . $root . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . $template . '.html';
	if ($dir && file_exists($dirfile)) {//存在指定目录
		return $dirfile;
	} elseif ($style && file_exists($stylefile)) { // 先找指定样式的模板
		return $stylefile;
	} elseif (file_exists($defaultfile)) {
		return $defaultfile;
	} else {
		return '';
	}
}

/**
 * 提示信息页面跳转，跳转地址如果传入数组，页面会提示多个地址供用户选择，默认跳转地址为数组的第一个值，时间为5秒。
 *
 * @param string $msg 提示信息
 * @param mixed $ (string/array) $url_forward 跳转地址
 * @param int $ms 跳转等待时间
 */
function showmessage($msg, $url_forward = 'goback', $ms = 1250, $dialog = '') {
	if (defined('IN_ADMIN')) {
		include(admin :: admin_tpl('message', 'admin'));
	} else if (defined('IN_DAILI')) {
		include(daili :: daili_tpl('message', 'daili'));
	} else {
		include(template('message'));
	}
	exit;
}

/**
 * 输出自定义错误
 *
 * @param  $errno 错误号
 * @param  $errstr 错误描述
 * @param  $errfile 报错文件地址
 * @param  $errline 错误行号
 * @return string 错误提示
 */

function my_error_handler($errno, $errstr, $errfile, $errline) {
	if ($errno == 8) return '';
	$errfile = str_replace(MYFILE_PATH, '', $errfile);
	if (base :: load_config('system', 'errorlog')) {
		error_log(date('m-d H:i:s', SYS_TIME) . ' | ' . $errno . ' | ' . str_pad($errstr, 30) . ' | ' . $errfile . ' | ' . $errline . "\r\n", 3, CACHE_PATH . 'error_log.php');
	} else {
		$str = '<div style="font-size:12px;text-align:left; border-bottom:1px solid #9cc9e0; border-right:1px solid #9cc9e0;padding:1px 4px;color:#000000;font-family:Arial, Helvetica,sans-serif;"><span>errorno:' . $errno . ',str:' . $errstr . ',file:<font color="blue">' . $errfile . '</font>,line' . $errline . '</div>';
		echo $str;
	}
}

/**
 * 取得文件扩展
 *
 * @param  $filename 文件名
 * @return 扩展名
 */
function fileext($filename) {
	return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}

function url($url, $isabs = 0) {
	if (strpos($url, '://') !== false || strpos($url, '?') !== false) return $url;
	$siteurl = get_url();
	if ($isabs || defined('SHOWJS')) {
		$url = strpos($url, WEB_PATH) === 0 ? $siteurl . substr($url, strlen(WEB_PATH)) : $siteurl . $url;
	} else {
		$url = strpos($url, WEB_PATH) === 0 ? $url : WEB_PATH . $url;
	}
	return $url;
}

/**
 * 分页函数
 *
 * @param  $num 信息总数
 * @param  $curr_page 当前分页
 * @param  $perpage 每页显示数
 * @param  $urlrule URL规则
 * @param  $array 需要传递的数组，用于增加额外的方法
 * @return 分页
 */
function pages($num, $curr_page, $perpage = 20, $urlrule = '', $array = array(), $setpages = 10, $arrpage = 1) {
	if ($urlrule == '') $urlrule = url_par('page={$page}');
	$multipage = '';
	if ($num > $perpage) {
		$page = $setpages + 1;
		$offset = ceil($page / 2 - 1);
		$pages = ceil($num / $perpage);
		$from = $curr_page - $offset;
		$to = $curr_page + $offset;
		$more = 0;
		if ($page >= $pages) {
			$from = 2;
			$to = $pages-1;
		} else {
			if ($from <= 1) {
				$to = $page-1;
				$from = 2;
			} elseif ($to >= $pages) {
				$from = $pages - ($page-2);
				$to = $pages-1;
			}
			$more = 1;
		}
		if ($arrpage > 0) $multipage .= '总数：<b>' . $num . '</b>&nbsp;&nbsp;';
		if ($curr_page > 0) {
			$multipage .= ' <a href="' . pageurl($urlrule, $curr_page-1, $array) . '" class="a1">上一页</a>';
			if ($curr_page == 1) {
				$multipage .= ' <span>1</span>';
			} elseif ($curr_page > ($setpages - $offset) && $more) {
				$multipage .= ' <a href="' . pageurl($urlrule, 1, $array) . '">1</a>..';
			} else {
				$multipage .= ' <a href="' . pageurl($urlrule, 1, $array) . '">1</a>';
			}
		}
		for($i = $from; $i <= $to; $i++) {
			if ($i != $curr_page) {
				$multipage .= ' <a href="' . pageurl($urlrule, $i, $array) . '">' . $i . '</a>';
			} else {
				$multipage .= ' <span>' . $i . '</span>';
			}
		}
		if ($curr_page < $pages) {
			if ($curr_page < $pages - ($setpages - $offset-1) && $more) {
				$multipage .= ' ..<a href="' . pageurl($urlrule, $pages, $array) . '">' . $pages . '</a> <a href="' . pageurl($urlrule, $curr_page + 1, $array) . '" class="a1">下一页</a>';
			} else {
				$multipage .= ' <a href="' . pageurl($urlrule, $pages, $array) . '">' . $pages . '</a> <a href="' . pageurl($urlrule, $curr_page + 1, $array) . '" class="a1">下一页</a>';
			}
		} elseif ($curr_page == $pages) {
			$multipage .= ' <span>' . $pages . '</span> <a href="' . pageurl($urlrule, $curr_page, $array) . '" class="a1">下一页</a>';
		} else {
			$multipage .= ' <a href="' . pageurl($urlrule, $pages, $array) . '">' . $pages . '</a> <a href="' . pageurl($urlrule, $curr_page + 1, $array) . '" class="a1">下一页</a>';
		}
	}
	return $multipage;
}

/**
 * 返回分页路径
 *
 * @param  $urlrule 分页规则
 * @param  $page 当前页
 * @param  $array 需要传递的数组，用于增加额外的方法
 * @return 完整的URL路径
 */
function pageurl($urlrule, $page, $array = array()) {
	if (strpos($urlrule, '#')) {
		$urlrules = explode('#', $urlrule);
		$urlrule = $page < 2 ? $urlrules[0] : $urlrules[1];
	}
	$findme = array('{$page}');
	$replaceme = array($page);
	if (is_array($array)) foreach ($array as $k => $v) {
		$findme[] = '{$' . $k . '}';
		$replaceme[] = $v;
	}
	$url = str_replace($findme, $replaceme, $urlrule);
	return $url;
}

/**
 * URL路径解析，pages 函数的辅助函数
 *
 * @param  $par 传入需要解析的变量 默认为，page={$page}
 * @param  $url URL地址
 * @return URL
 */
function url_par($par, $url = '') {
	if ($url == '') $url = get_url();
	$pos = strpos($url, '?');
	if ($pos === false) {
		$url .= '?' . $par;
	} else {
		$querystring = substr(strstr($url, '?'), 1);
		parse_str($querystring, $pars);
		$query_array = array();
		foreach($pars as $k => $v) {
			if ($k == 'page') continue;
			$query_array[$k] = $v;
		}
		$querystring = http_build_query($query_array) . '&' . $par;
		$url = substr($url, 0, $pos) . '?' . $querystring;
	}
	return $url;
}

/**
 * 查询单个字符是否存在于某字符串
 *
 * @param  $haystack 字符串
 * @param  $needle 要查找的字符
 * @return bool
 */
function str_exists($haystack, $needle) {
	return !(strpos($haystack, $needle) === false);
}

/**
 * 替换关键字为*号
 *
 * @param  $content 字符串
 * @param  $filter 要查找的字符 半角逗号分隔
 * @return content 返回处理过的内容
 */
function str_filter($content, $filter) {
	if (empty($filter)) return $content;
	$filter = '/' . str_replace(',', '|', preg_quote(trim($filter), '/')) . '/';
	return preg_replace($filter, '**', $content);
	// $filter = explode(',', $filter);//拆分关键字
	// return strtr($content, array_combine($filter,array_fill(0,count($filter),'*')));//替换
}

/**
 * 查询多个字符是否存在于某字符串
 *
 * @param  $haystack 字符串
 * @param  $needle 要查找的字符 半角逗号分隔
 * @return bool
 */
function str_allexists($content, $filter) {
	$filter = '/' . str_replace(',', '|', preg_quote(trim($filter), '/')) . '/';
	return preg_match($filter, $content);
}

/**
 * 检查IP
 *
 * @param  $ip 要检查的IP
 * @param  $accesslist IP列表 换行分隔
 * @return bool
 */
function ipaccess($ip, $accesslist) {
	if (empty($ip) || empty($accesslist)) return 0;
	return preg_match("/^(" . rtrim(str_replace(array("\r\n", ' '), array('|', ''), preg_quote(trim($accesslist), '/')), '|') . ")/", trim($ip));
}

/**
 * 判断email格式是否正确
 *
 * @param  $email
 */
function is_email($email) {
	return strlen($email) > 6 && preg_match('/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/', $email);
}

/**
 * 判断手机格式是否正确
 *
 * @param  $mobile 移动：134、135、136、137、138、139、150、151、152、157、158、159、182、183、184、187、188、178(4G)、147(上网卡)；
联通：130、131、132、155、156、185、186、176(4G)、145(上网卡)；
电信：133、153、180、181、189 、177(4G)；
卫星通信：1349
 * 虚拟运营商：170
 */
function is_mobile($mobile) {
	if (!is_numeric($mobile)) {
		return false;
	}
	return preg_match('/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/', $mobile) ? true : false;
}

function checkmobile() {
	// if (get_cookie('mobile_type') && get_cookie('mobile_type') != 'unknown') {
	// return true;
	// }
	$mobile = array();
	static $touchbrowser_list = array('iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini',
		'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung',
		'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser',
		'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource',
		'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone',
		'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop',
		'benq', 'haier', '^lct', '320x320', '240x320', '176x220', 'windows phone');
	static $wmlbrowser_list = array('cect', 'compal', 'ctl', 'lg', 'nec', 'tcl', 'alcatel', 'ericsson', 'bird', 'daxian', 'dbtel', 'eastcom',
		'pantech', 'dopod', 'philips', 'haier', 'konka', 'kejian', 'lenovo', 'benq', 'mot', 'soutec', 'nokia', 'sagem', 'sgh',
		'sed', 'capitel', 'panasonic', 'sonyericsson', 'sharp', 'amoi', 'panda', 'zte');

	static $pad_list = array('ipad');

	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);

	if (dstrpos($useragent, $pad_list)) { // ipad 使用电脑版
		return false;
	}
	if (($v = dstrpos($useragent, $touchbrowser_list, true))) { // 触屏版
		// set_cookie('mobile_type', $v);
		return '2';
	}
	if (($v = dstrpos($useragent, $wmlbrowser_list))) { // wml版
		// set_cookie('mobile_type', $v);
		return '3';
	}
	$brower = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop');
	if (dstrpos($useragent, $brower)) { // 使用电脑版
		return false;
	}
	// set_cookie('mobile_type', 'unknown');//未知设备
	return false;
}

function dstrpos($string, $arr, $returnvalue = false) {
	if (empty($string)) return false;
	foreach((array)$arr as $v) {
		if (strpos($string, $v) !== false) {
			$return = $returnvalue ? $v : true;
			return $return;
		}
	}
	return false;
}

/**
 * iconv 编辑转换
 */
if (!function_exists('iconv')) {
	function iconv($in_charset, $out_charset, $str) {
		$in_charset = strtoupper($in_charset);
		$out_charset = strtoupper($out_charset);
		if (function_exists('mb_convert_encoding')) {
			return mb_convert_encoding($str, $out_charset, $in_charset);
		} else {
			base :: load_sys_func('iconv');
			$in_charset = strtoupper($in_charset);
			$out_charset = strtoupper($out_charset);
			if ($in_charset == 'UTF-8' && ($out_charset == 'GBK' || $out_charset == 'GB2312')) {
				return utf8_to_gbk($str);
			}
			if (($in_charset == 'GBK' || $in_charset == 'GB2312') && $out_charset == 'UTF-8') {
				return gbk_to_utf8($str);
			}
			return $str;
		}
	}
}

/**
 * 生成适用于IN ()查询语句
 *
 * @param  $array 数组
 */
function dimplode($array) {
	if (!empty($array)) {
		$array = array_map('addslashes', $array);
		return "'" . implode("','", is_array($array) ? $array : array($array)) . "'";
	} else {
		return 0;
	}
}

/**
 * CURL
CURLOPT_SSL_VERIFYHOST的值
设为0表示不检查证书
设为1表示检查证书中是否有CN(common name)字段
设为2表示在1的基础上校验当前的域名是否与CN匹配
而libcurl早期版本中这个变量是boolean值，为true时作用同目前设置为2，后来出于调试需求，增加了仅校验是否有CN字段的选项，因此两个值true/false就不够用了，升级为0/1/2三个值。
再后来(libcurl_7.28.1之后的版本)，这个调试选项由于经常被开发者用错，被去掉了，因此目前也不支持1了，只有0/2两种取值。
 */
function fileget_content($url, $post = '', $cookie = '', $referer = '', $headers = array(), $timeout = 10) { // CURL
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	//针对HTTPS的解决方法
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
	//速度优化
	//curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0); //强制协议为1.0
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); //强制使用IPV4协议解析域名
	curl_setopt($ch, CURLOPT_HTTPHEADER , ($headers ? $headers : array('Expect: ')));//头部送出 Expect: 可减少询问过程
	//模拟用户使用的浏览器
	curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; 4399Box.560; .NET4.0C; .NET4.0E)");// 模拟用户使用的浏览器
	curl_setopt($ch, CURLOPT_REFERER, ($referer ? $referer : ''));//构造来路
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //使用自动跳转
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//获取的信息以文件流的形式返回
	if ($post) {
		curl_setopt($ch, CURLOPT_POST, 1);//发送一个常规的Post请求
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);//Post提交的数据包
	}
	if($cookie) {
		curl_setopt($ch, CURLOPT_COOKIE, $cookie);//发送Cookie
	}
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);//链接服务器超时的时间
	$data = curl_exec($ch);
	$status = curl_getinfo($ch);
	$errno = curl_errno($ch);
	curl_close($ch);
	if ($errno || $status['http_code'] != 200) {
		return false;
	} else {
		return $data;
	}
}

/**
 * 生成加密后的密码
 *
 * @param string $password 密码
 * @return array 加密后的密码
 */
function creat_password($password) {
	$encrypt = substr(md5(rand()), 0, 6);
	return array(md5(md5($password) . $encrypt), $encrypt);
}

/**
 * 字符串加星号
 */

function mobile_rep($mobile) {
	return substr_replace($mobile, '****', 3, 4);
}

function PassStart($str, $start, $end = 0, $dot = "*", $charset = "UTF-8") {
	$len = mb_strlen($str, $charset);
	if ($start == 0 || $start > $len) {
		$start = 1;
	}
	if ($end != 0 && $end > $len) {
		$end = $len - 2;
	}
	$endStart = $len - $end;
	$top = mb_substr($str, 0, $start, $charset);
	$bottom = "";
	if ($endStart > 0) {
		$bottom = mb_substr($str, $endStart, $end, $charset);
	}
	$len = $len - mb_strlen($top, $charset);
	$len = $len - mb_strlen($bottom, $charset);
	$newStr = $top;
	for ($i = 0; $i < $len; $i++) {
		$newStr .= $dot;
	}
	$newStr .= $bottom;
	return $newStr;
}

/**
 * 写入本地文件
 *
 * @arr 数组
 */
function write_config($arr, $path) {
	// 写入本地文件
	$html = "<?php\n\n";
	$html .= "return ";
	$html .= var_export($arr, true) . ';';
	$html .= "\n\n?>";
	// var_dump(CONFIG_PATH . $path);exit;
	/*if (!is_writable(CONFIG_PATH . $path)){
		
		showmessage('没有写入权限！', HTTP_REFERER);
	}*/
	file_put_contents(CONFIG_PATH . $path, $html);
	return true;
}

/**
 * 设置 cookie
 *
 * @param string $var 变量名
 * @param string $value 变量值
 * @param int $time 有效时间时间 秒
 */
function set_cookie($var, $value = '', $time = 0) {
	$time = $time > 0 ? (SYS_TIME + $time) : ($value == '' ? (SYS_TIME - 3600) : 0);
	$s = $_SERVER['SERVER_PORT'] == '443' ? 1 : 0;
	if (is_array($value)) {
		foreach($value as $k => $v) {
			$n = base :: load_config('system', 'cookie_pre') . $var[$k];
			$_COOKIE[$n] = '' . $v . '';
			setcookie($n, sys_auth($v, 'ENCODE'), $time, base :: load_config('system', 'cookie_path'), base :: load_config('system', 'cookie_domain'), $s);
		}
	} else {
		$var = base :: load_config('system', 'cookie_pre') . $var;
		$_COOKIE[$var] = $value;
		setcookie($var, sys_auth($value, 'ENCODE'), $time, base :: load_config('system', 'cookie_path'), base :: load_config('system', 'cookie_domain'), $s);
	}
}

/**
 * 获取通过 set_cookie 设置的 cookie 变量
 *
 * @param string $var 变量名
 * @return mixed 成功则返回cookie 值，否则返回 false
 */
function get_cookie($var) {
	$var = base :: load_config('system', 'cookie_pre') . $var;
	return isset($_COOKIE[$var]) ? sys_auth($_COOKIE[$var], 'DECODE') : false;
}

/**
 * 生成二维码
 *
 * @param string $content 变量名
 * @return mixed 成功则返回cookie 值，否则返回 false
 */
function QR_code($content) {
	include '/api/phpqrcode/phpqrcode.php';
	
}


?>