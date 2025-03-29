<?php
defined('IN_MYWEB') or exit('No permission resources.');
base :: load_app_class('daili', 'daili', 0);
class account extends daili{

	private $db, $type, $uid, $username;

	public function __construct() {
		parent::__construct();
		$this->db = base::load_model('account_model');
		$this->type = array(
			0 => '<span style="color: #FFA700;">充值</span>',
			1 => '<span style="color: #0070FF;">提现</span>',
			2 => '<span style="color: #00B520;">投注</span>',
			3 => '<span style="color: #FF0000;">盈利</span>',
			4 => '<span style="color: #FF00DE;">退单</span>',
			5 => '<span style="color: #F60;">红包</span>'
		);
		$this -> uid = intval($this -> get_userid());
		$this -> username = trim($this -> get_username());
	}

	public function init() {
		$list = array();
		$pages = '<div class="tps">请从《账户管理》点击操作或输入账户UID查询操作</div>';
		include $this->daili_tpl('account_list');
	}

	public function search() {
 		$where = "";
		if(is_array($_GET['search'])) extract($_GET['search']);
		$search_uid = intval($uid);
		if (!$search_uid) {
			showmessage('请输入账户UID！', HTTP_REFERER);
		}
		$agent = $this -> check_agent($search_uid);
		if (!$agent) {
			showmessage('无权操作该账户！');
		}
		$search_type = intval($type);
		$typeoption[$search_type] = 'selected="selected"';
		if($search_uid) $where .= $where ?  " AND uid='$search_uid'" : "uid='$search_uid'";
		if($search_type){
			if($search_type == 5) $search_type = 0;
			$where .= $where ?  " AND type='$search_type'" : "type='$search_type'";
		}
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$list = $this->db->listinfo($where, 'id DESC', $page, 15);
 		$pages = $this->db->pages;
		base::load_sys_class('format', '', 0);
 		include $this->daili_tpl('account_list');
	}

}