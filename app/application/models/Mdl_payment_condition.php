<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_payment_condition extends MY_Model {
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'm_payment_condition';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			'code' => '',
			'name' => '',
			'description' => '',
			'remark' => '',
			'create_by' => 0,
			'create_date' => NULL,
			'update_by' => 0,
			'update_date' => NULL,
			'is_cancel' => 0,
			'sort_index' => 0,
		);
	}
	function search($arrObj = array()) {
		$_sql = "SELECT m.* FROM " . $this->_TABLE_NAME . " m WHERE True ";

		if (isset($arrObj['code']) && (! empty($arrObj['code']))) $_sql .= " AND LOWER(code) LIKE '%" . strtolower(trim($arrObj['code'])) . "%' ";
		if (isset($arrObj['name']) && (! empty($arrObj['name']))) $_sql .= " AND LOWER(name) LIKE '%" . strtolower(trim($arrObj['name'])) . "%' ";
		
		return $this->arr_execute($_sql);
	}

}