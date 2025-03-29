<?php
defined('IN_MYWEB') or exit('No permission resources.');
base :: load_app_class('admin', 'admin', 0);

class settings extends admin {
	private $db;

	public function __construct() {
		parent :: __construct(1);
		$this -> db = base :: load_model('settings_model');
	}

	public function init() {
		if (isset($_POST['dosubmit'])) {
			$setting_arr = $_POST['setting'];
			$zjarr = array();
			foreach ($setting_arr['zjarr'] as $key => $val) {
				foreach ($val as $ke => $va) {
					if(!empty($va)){
						$zjarr[$ke][$key] = $va;
					}
					
				}
			}
			$setting_arr['zjarr'] = urlencode((serialize($zjarr)));

			/*$gift = array();
			foreach ($setting_arr['gift'] as $key => $val) {
				foreach ($val as $ke => $va) {
					if(!empty($va)){
						$gift[$ke][$key] = $va;
					}
					
				}
			}*/
			$setting_arr['gift'] = urlencode((serialize($setting_arr['gift'])));

			if ($_FILES['wxfile']['size'] || $_FILES['alifile']['size']|| $_FILES['lunfantu1']['size']|| $_FILES['lunfantu5']['size']|| $_FILES['lunfantu2']['size']|| $_FILES['lunfantu3']['size']|| $_FILES['lunfantu4']['size']) {//如果选择了上传图片
				$up = base::load_sys_class('upimg');
				$up->datedir = false;//不要添加日期目录
				$up->dir = 'ewm';
				$up->thumb = 0;
				if ($_FILES['wxfile']['size']) {//微信
					$up->filename = 'wxfile';
					$wxreturn = $up->up();
					if ($wxreturn['state'] == 'success') {
						@unlink('./uppic/ewm/'.$config['wxewm']);//删除原来的图像
						$setting_arr['wxewm'] = $wxreturn['info'];
					}
				}
				if ($_FILES['alifile']['size']) {//数字货币
					$up->filename = 'alifile';
					$alireturn = $up->up();
					if ($alireturn['state'] == 'success') {
						@unlink('./uppic/ewm/'.$config['aliewm']);//删除原来的图像
						$setting_arr['aliewm'] = $alireturn['info'];
					}
				}
				if ($_FILES['lunfantu1']['size']) {//轮番图1
					$up->filename = 'lunfantu1';
					$alireturn1 = $up->up();
					if ($alireturn1['state'] == 'success') {
						@unlink('./uppic/ewm/'.$config['lunfantu1']);//删除原来的图像
						$setting_arr['lunfantu1'] = $alireturn1['info'];
					}
				}
				if ($_FILES['lunfantu2']['size']) {//轮番图2
					$up->filename = 'lunfantu2';
					$alireturn2 = $up->up();
					if ($alireturn2['state'] == 'success') {
						@unlink('./uppic/ewm/'.$config['lunfantu2']);//删除原来的图像
						$setting_arr['lunfantu2'] = $alireturn2['info'];
					}
				}
				if ($_FILES['lunfantu3']['size']) {//轮番图3
					$up->filename = 'lunfantu3';
					$alireturn3 = $up->up();
					if ($alireturn3['state'] == 'success') {
						@unlink('./uppic/ewm/'.$config['lunfantu3']);//删除原来的图像
						$setting_arr['lunfantu3'] = $alireturn3['info'];
					}
				}
				if ($_FILES['lunfantu4']['size']) {//轮番图4
					$up->filename = 'lunfantu4';
					$alireturn4 = $up->up();
					if ($alireturn4['state'] == 'success') {
						@unlink('./uppic/ewm/'.$config['lunfantu4']);//删除原来的图像
						$setting_arr['lunfantu4'] = $alireturn4['info'];
					}
				}
				if ($_FILES['lunfantu5']['size']) {//轮番图5
					$up->filename = 'lunfantu5';
					$alireturn5 = $up->up();
					if ($alireturn5['state'] == 'success') {
						@unlink('./uppic/ewm/'.$config['lunfantu5']);//删除原来的图像
						$setting_arr['lunfantu5'] = $alireturn5['info'];
					}
				}
			}
			/*$setting_arr['wxewm'] = empty($setting_arr['wxewm'])?$config['wxewm']:$setting_arr['wxewm'];
			$setting_arr['aliewm'] = empty($setting_arr['aliewm'])?$config['aliewm']:$setting_arr['aliewm'];*/

			if(empty($setting_arr['wxewm'])) $setting_arr['wxewm'] = $setting_arr['wxfile_old'];
			if(empty($setting_arr['aliewm'])) $setting_arr['aliewm'] = $setting_arr['alifile_old'];
			//轮番图
			if(empty($setting_arr['lunfantu1'])) $setting_arr['lunfantu1'] = $setting_arr['lunfantu1_old'];
			if(empty($setting_arr['lunfantu2'])) $setting_arr['lunfantu2'] = $setting_arr['lunfantu2_old'];
			if(empty($setting_arr['lunfantu3'])) $setting_arr['lunfantu3'] = $setting_arr['lunfantu3_old'];
			if(empty($setting_arr['lunfantu4'])) $setting_arr['lunfantu4'] = $setting_arr['lunfantu4_old'];
			if(empty($setting_arr['lunfantu5'])) $setting_arr['lunfantu5'] = $setting_arr['lunfantu5_old'];
			/*$setting_arr['wxewm'] = '0807_1565161441_3012.png';
			$setting_arr['aliewm'] = '0807_1565161441_4606.png';*/
			// var_dump($setting_arr);exit;
			foreach($setting_arr as $k => $v) {
				$setting[$k] = safe_replace(trim($v));
				$this -> db -> insert(array('name' => $k , 'data' => safe_replace(trim($v))), 1, 1); //更新数据
			}
			// 写入本地文件
			$iscache = base :: load_config('system', 'iscache'); //是否开启设置缓存
			if ($iscache) write_config($setting, 'setting.php');
			showmessage('更新成功！', HTTP_REFERER);
		}
		$settingarr = $this -> get_settings(); //读取系统设置
		foreach($settingarr as $k => $v) {
			$$k = $v;
		}

		$gift = unserialize(urldecode($gift));

		include $this -> admin_tpl('settings');
	}
}

?>