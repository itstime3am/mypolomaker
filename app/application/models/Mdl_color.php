<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mdl_color extends MY_Model {
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'm_color';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			'name' => '',
			'polo_cols' => '',
			'tshirt_cols' => '',
			'cap_cols' => '',
			'jacket_cols' => '',
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
		
		$_sql =<<<QUERY
SELECT m.*
, CHAR_LENGTH(m.polo_cols) - CHAR_LENGTH(REPLACE(m.polo_cols, ',', '')) - 1 AS polo_count 
, CHAR_LENGTH(m.tshirt_cols) - CHAR_LENGTH(REPLACE(m.tshirt_cols, ',', '')) - 1 AS tshirt_count 
, CHAR_LENGTH(m.cap_cols) - CHAR_LENGTH(REPLACE(m.cap_cols, ',', '')) - 1 AS cap_count 
, CHAR_LENGTH(m.jacket_cols) - CHAR_LENGTH(REPLACE(m.jacket_cols, ',', '')) - 1 AS jacket_count 
FROM {$this->_TABLE_NAME} m 
WHERE True 

QUERY;
		if (array_key_exists('name', $arrObj)) if (trim($arrObj['name']) != '') $_sql  .= "AND m.name LIKE '%" . trim($arrObj['name']) . "%' ";
		$_sql .= "\n ORDER BY sort_index ASC";

		return $this->arr_execute($_sql);
	}
	
	
}