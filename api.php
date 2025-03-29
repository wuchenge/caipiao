<?php
/**
 * api.php API入口
 * 
 * @copyright (C) 2005-2014 LEYUN360 Inc.
 * @license This is a charge software, licensing terms
 * @lastmodify 2010-12-16
 * $Id: api.php 2 2010-12-16 10:59:13Z LEYUN360 $
 */
define('MYFILE_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
include MYFILE_PATH . '/source/base.php';
$param = base :: load_sys_class('param');
$op = isset($_REQUEST['op']) && trim($_REQUEST['op']) ? trim($_REQUEST['op']) : exit('Operation can not be empty');
if (!preg_match('/([^a-z_]+)/i', $op) && file_exists('api' . DIRECTORY_SEPARATOR . $op . '.php')) {
	include 'api' . DIRECTORY_SEPARATOR . $op . '.php';
} else {
	exit('API handler does not exist');
} 

?>