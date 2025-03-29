<?php
defined('IN_MYWEB') or exit('No permission resources.');
base :: load_sys_class('model', '', 0);
class opentime_model extends model {
	public $table_name;
	public function __construct() {
		$this -> db_config = base :: load_config('database');
		$this -> db_setting = 'c_db';
		$this -> table_name = 'c_opentime';
		parent :: __construct();
	}
}

?>