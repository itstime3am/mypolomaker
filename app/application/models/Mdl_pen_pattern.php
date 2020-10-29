<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_pen_pattern extends MY_Model {
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'pm_m_pen_pattern';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			'name' => ''
			, 'is_polo' => 0
			, 'is_tshirt' => 0
			, 'remark' => ''
			, 'create_by' => 0
			, 'create_date' => NULL
			, 'update_by' => 0
			, 'update_date' => NULL
		);
	}
	function search($arrObj = array()) {
		$_sql =<<<QUERY
SELECT t.*
, CASE WHEN t.is_polo=1 THEN 'Apply' ELSE '' END AS disp_polo, CASE WHEN t.is_tshirt=1 THEN 'Apply' ELSE '' END AS disp_tshirt 
FROM {$this->_TABLE_NAME} t 
WHERE TRUE

QUERY;
		if (array_key_exists('name', $arrObj)) if (trim($arrObj['name']) != '') $_sql  .= "AND name LIKE '%" . trim($arrObj['name']) . "%' ";
		if (array_key_exists('is_polo', $arrObj)) if (trim($arrObj['is_polo']) != '') $_sql .= "AND COALESCE(is_polo, 0) = 1 ";
		if (array_key_exists('is_tshirt', $arrObj)) if (trim($arrObj['is_tshirt']) != '') $_sql .= "AND COALESCE(is_tshirt, 0) = 1 ";
		return $this->arr_execute($_sql);
	}
}