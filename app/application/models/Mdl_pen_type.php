<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_pen_type extends MY_Model {
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'pm_m_pen_type';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			'name' => ''
			, 'is_shirt' => 0
			, 'is_tshirt' => 0
		);
	}
	function search($arrObj = array()) {
		$_sql =<<<QUERY
SELECT t.*
, CASE WHEN t.is_shirt=1 THEN 'Apply' ELSE '' END AS disp_polo, CASE WHEN t.is_tshirt=1 THEN 'Apply' ELSE '' END AS disp_tshirt 
FROM {$this->_TABLE_NAME} t 
WHERE TRUE

QUERY;
		if (array_key_exists('name', $arrObj)) if (trim($arrObj['name']) != '') $_sql  .= "AND name LIKE '%" . trim($arrObj['name']) . "%' ";
		if (array_key_exists('is_shirt', $arrObj)) if (trim($arrObj['is_shirt']) != '') $_sql .= "AND COALESCE(is_shirt, 0) = 1 ";
		if (array_key_exists('is_tshirt', $arrObj)) if (trim($arrObj['is_tshirt']) != '') $_sql .= "AND COALESCE(is_tshirt, 0) = 1 ";
		return $this->arr_execute($_sql);
	}
}