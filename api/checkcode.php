<?php
/**
 *  checkcode.php
 *
 * @copyright			(C) 2005-2014 LEYUN360 Inc.
 * @license				This is a charge software, licensing terms
 * @lastmodify			2011-1-17
   $验证码生成 LEYUN360 $
 */
defined('IN_MYWEB') or exit('No permission resources.');
//$session_storage = 'session_'.base::load_config('system','session_storage');
//base::load_sys_class($session_storage);
//session_start();
$checkcode = base::load_sys_class('checkcode');
$checkcode->code_len = isset($_GET['code_len']) && $_GET['code_len'] ? intval($_GET['code_len']) : 4;
$checkcode->font_size = isset($_GET['font_size']) && $_GET['font_size'] ? intval($_GET['font_size']) : 14;
$checkcode->width = isset($_GET['width']) && $_GET['width'] ? intval($_GET['width']) : 84;
$checkcode->height = isset($_GET['height']) && $_GET['height'] ? intval($_GET['height']) : 24;
$checkcode->font_color = isset($_GET['font_color']) && $_GET['font_color'] ? trim(urldecode($_GET['font_color'])) : '#000000';
$checkcode->background = isset($_GET['background']) && $_GET['background'] ? trim(urldecode($_GET['background'])) : '#F7F7F7';
$checkcode->charset = isset($_GET['charset']) && $_GET['charset'] ? trim($_GET['charset']) : '196152064981065096874984203547865015647'; //abcdefghkmnprstuvwyzABCDEFGHKLMNPRSTUVWYZ23456789
$checkcode->doimage();

//$_SESSION['code']=$checkcode->get_code();

set_cookie('code', $checkcode->get_code(), 60 * 5);
?>