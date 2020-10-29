<?php
class Mdl_standard_pattern extends MY_Model {
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'pm_m_standard_pattern';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			'name' => ''
			, 'is_polo' => 0
			, 'is_tshirt' => 0
			, 'is_jacket' => 0
			, 'is_cap' => 0
			, 'remark' => ''
			, 'create_by' => 0
			, 'create_date' => NULL
			, 'update_by' => 0
			, 'update_date' => NULL
		);
	}
	function search($arrObj = array()) {
		
		$_sql =<<<QUERY

SELECT t.*, CASE WHEN t.is_polo=1 THEN 'Apply' ELSE '' END AS disp_polo, CASE WHEN t.is_tshirt=1 THEN 'Apply' ELSE '' END AS disp_tshirt 
FROM {$this->_TABLE_NAME} t 
WHERE COALESCE(t.is_cancel, 0) < 1

QUERY;
		if (array_key_exists('name', $arrObj)) if (trim($arrObj['name']) != '') $_sql  .= "AND t.name LIKE '%" . trim($arrObj['name']) . "%' ";
		if (array_key_exists('is_polo', $arrObj)) if (trim($arrObj['is_polo']) > '0') $_sql .= "AND COALESCE(t.is_polo, 0) = 1 ";
		if (array_key_exists('is_tshirt', $arrObj)) if (trim($arrObj['is_tshirt']) > '0') $_sql .= "AND COALESCE(t.is_tshirt, 0) = 1 ";
		if (array_key_exists('is_jacket', $arrObj)) if (trim($arrObj['is_jacket']) > '0') $_sql .= "AND COALESCE(is_jacket, 0) = 1 ";
		if (array_key_exists('is_cap', $arrObj)) if (trim($arrObj['is_cap']) > '0') $_sql .= "AND COALESCE(is_cap, 0) = 1 ";
		$_sql .= "\n ORDER BY sort_index ASC";

		return $this->arr_execute($_sql);
	}
	
	
}