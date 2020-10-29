<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_pocket_position extends MY_Model { // ** currently for jacket only
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'pm_m_pocket_position';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			'name' => '',
			'is_jacket' => 0,
			'remark' => '',
			'create_by' => 0,
			'create_date' => NULL,
			'update_by' => 0,
			'update_date' => NULL
		);
	}
	function search($arrObj = array()) {
		$_sql = "SELECT c.*, CASE WHEN is_jacket=1 THEN 'Apply' ELSE '' END AS disp_jacket FROM " . $this->_TABLE_NAME . " c WHERE True ";

		if (isset($arrObj['name']) && (! empty($arrObj['name']))) $_sql .= " AND LOWER(name) LIKE '%" . strtolower(trim($arrObj['name'])) . "%' ";
		if (isset($arrObj['is_jacket']) && (bool($arrObj['is_jacket']))) $_sql .= " AND is_jacket = 1 ";
		return $this->arr_execute($_sql);
	}	
}