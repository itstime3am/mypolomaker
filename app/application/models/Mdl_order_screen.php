<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_order_screen extends MY_Model {
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'pm_m_order_screen';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			'name' => ''
			, 'screen_type' => 0
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
SELECT t.*, CASE WHEN t.screen_type=1 THEN 'งานปัก'  WHEN t.screen_type=2 THEN 'งานสกรีน' END AS disp_screen_type
, CASE WHEN t.is_polo=1 THEN 'Apply' ELSE '' END AS disp_polo, CASE WHEN t.is_tshirt=1 THEN 'Apply' ELSE '' END AS disp_tshirt
, CASE WHEN t.is_jacket=1 THEN 'Apply' ELSE '' END AS disp_jacket, CASE WHEN t.is_cap=1 THEN 'Apply' ELSE '' END AS disp_cap
FROM {$this->_TABLE_NAME} t 
WHERE TRUE

QUERY;
		if (array_key_exists('name', $arrObj)) if (trim($arrObj['name']) != '') $_sql  .= "AND name LIKE '%" . trim($arrObj['name']) . "%' ";

		if (array_key_exists('screen_type', $arrObj)) if (trim($arrObj['screen_type']) > 0) $_sql .= "AND COALESCE(screen_type, 0) = " . $arrObj['screen_type'] . " ";
		if (array_key_exists('is_polo', $arrObj)) if (trim($arrObj['is_polo']) > '0') $_sql .= "AND COALESCE(is_polo, 0) = 1 ";
		if (array_key_exists('is_tshirt', $arrObj)) if (trim($arrObj['is_tshirt']) > '0') $_sql .= "AND COALESCE(is_tshirt, 0) = 1 ";
		if (array_key_exists('is_jacket', $arrObj)) if (trim($arrObj['is_jacket']) > '0') $_sql .= "AND COALESCE(is_jacket, 0) = 1 ";
		if (array_key_exists('is_cap', $arrObj)) if (trim($arrObj['is_cap']) > '0') $_sql .= "AND COALESCE(is_cap, 0) = 1 ";
		
		return $this->arr_execute($_sql);
	}
}