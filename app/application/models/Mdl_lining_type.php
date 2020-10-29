<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_lining_type extends MY_Model { // ** currently for jacket only
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'pm_m_lining_type';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			'name' => '',
			'remark' => '',
			'create_by' => 0,
			'create_date' => NULL,
			'update_by' => 0,
			'update_date' => NULL
		);
	}

	function search($arrObj = array()) {
		$_sql = "SELECT m.* FROM " . $this->_TABLE_NAME . " m WHERE True ";

		if (isset($arrObj['name']) && (! empty($arrObj['name']))) $_sql .= " AND LOWER(name) LIKE '%" . strtolower(trim($arrObj['name'])) . "%' ";
		return $this->arr_execute($_sql);
	}
}