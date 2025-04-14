<?php
defined('IN_MYWEB') or exit('No permission resources.');
base :: load_sys_class('model', '', 0);
class account_model extends model {
	public function __construct() {
		$this -> db_config = base :: load_config('database');
		$this -> db_setting = 'default';
		$this -> table_name = 'account';
		parent :: __construct();
	}


	public function getTotalBet($date, $uid)
	{
		$start 		= strtotime($date . " 00:00:00");
		$end 		= strtotime($date . " 23:59:59");

		$sql 		= "
			SELECT `uid`, sum(`money`) as total_bet 
			FROM `bc_account` 
			WHERE `type` = 2 AND `addtime` >= '{$start}' AND `addtime` <= '{$end}' AND `uid` = {$uid}
			";
		$account_db = base::load_model('account_model', true);
		$account_db->query($sql);
		$data = $account_db->fetch_array()[0];

		return abs($data['total_bet']);
	}

	public function getExtra($date, $uid)
	{
		$sql 		= "
			SELECT sum(`money`) as total_bet 
			FROM `bc_extra` 
			WHERE `date_time` = '{$date}' AND `uid` = {$uid}
			";
		$account_db = base::load_model('extra_model', true);
		$account_db->query($sql);
		$data = $account_db->fetch_array()[0];

		return $data['total_bet'] ? $data['total_bet'] : 0;
	}
}

?>