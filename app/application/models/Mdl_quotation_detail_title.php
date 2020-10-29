<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_quotation_detail_title extends MY_Model {
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'pm_m_quotation_detail_title';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			'name' => ''
			, 'remark' => ''
			, 'order_type_id' => NULL
			, 'sort_index' => NULL
			, 'create_by' => 0
			, 'create_date' => NULL
			, 'update_by' => 0
			, 'update_date' => NULL
		);
	}
	function search($arrObj = array()) {
		$_sql =<<<QUERY
SELECT t.*
FROM {$this->_TABLE_NAME} t 
WHERE True
QUERY;
		if (array_key_exists('name', $arrObj)) if (trim($arrObj['name']) != '') $_sql .= "AND name LIKE '%" . trim($arrObj['name']) . "%' ";

		return $this->arr_execute($_sql);
	}	
}