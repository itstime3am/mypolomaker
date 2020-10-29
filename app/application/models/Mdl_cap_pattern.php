<?php
class Mdl_cap_pattern extends MY_Model {
	function __construct() {
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 't_cap_pattern';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			'code' => ''
			//,'cap_type_rowid' => NULL
			//,'cap_type_detail' => NULL
			,'standard_pattern_rowid' => NULL
			,'standard_pattern_detail' => NULL
			,'fabric_rowid' => NULL
			,'front_color_rowid' => NULL
			,'back_color_rowid' => NULL
			,'brim_color_rowid' => NULL
			,'button_color_rowid' => NULL			
			,'is_sandwich_rim' => NULL
			,'swr_color_rowid' => NULL
			,'air_flow_holes_number' => NULL
			,'afh_color_rowid' => NULL
			,'cap_belt_type_rowid' => NULL
			,'cap_belt_detail' => NULL
			,'remark1' => NULL
			,'remark2' => NULL
		);
	}
	function search($arrObj = array()) {	
		$_sql = <<<EOT
SELECT t.*, COALESCE(f.name, '--') AS fabric, COALESCE(fc.name, '--') AS front_color, COALESCE(bc.name, '--') AS back_color
, COALESCE(brc.name, '--') AS brim_color 
FROM v_raw_pattern_cap t
	LEFT OUTER JOIN pm_m_fabric f ON t.fabric_rowid = f.rowid 
	LEFT OUTER JOIN m_color fc ON t.front_color_rowid = fc.rowid 
	LEFT OUTER JOIN m_color bc ON t.back_color_rowid = bc.rowid 
	LEFT OUTER JOIN m_color brc ON t.brim_color_rowid = brc.rowid 
WHERE True 
EOT;
		if (array_key_exists('code', $arrObj)) if (trim($arrObj['code']) != '') $_sql .= "AND t.code LIKE '%" . trim($arrObj['code']) . "%' ";
		if (array_key_exists('fabric_rowid', $arrObj)) if (trim($arrObj['fabric_rowid']) != '') $_sql .= "AND t.fabric_rowid = " . trim($arrObj['fabric_rowid']) . " ";
		$_sql .= "\n ORDER BY rowid ASC";
//echo $_sql;exit;
		return $this->arr_execute($_sql);
	}
}