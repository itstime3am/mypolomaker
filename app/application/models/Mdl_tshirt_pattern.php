<?php
class Mdl_tshirt_pattern extends MY_Model {
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'pm_t_tshirt_pattern';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			'code' => ''
			,'standard_pattern_rowid' => NULL
			//,'standard_pattern_detail' => NULL
			,'fabric_rowid' => NULL
			,'neck_type_rowid'=>NULL
			,'neck_type_detail' => NULL
			,'main_color_rowid' => NULL //new
			,'line_color_rowid' => NULL //new
			,'sub_color1_rowid' => NULL //new
			,'sub_color2_rowid' => NULL //new
			,'sub_color3_rowid' => NULL //new
			,'color_detail' => NULL //new
			,'m_collar_type_rowid' => NULL //new
			,'f_collar_type_rowid' => NULL //new
			,'collar_detail' => NULL
			,'m_sleeves_type_rowid' => NULL //new
			,'f_sleeves_type_rowid' => NULL //new
			,'sleeves_detail' => NULL 
			,'flap_type_rowid' => NULL
			,'flap_type_detail' => NULL
			,'m_flap_side' => NULL
			,'f_flap_side' => NULL
			,'flap_side_ptrn_rowid' => NULL
			,'flap_side_ptrn_detail' => NULL
			,'m_pocket' => NULL
			,'f_pocket' => NULL
			,'pocket_type_rowid' => NULL
			,'pocket_type_detail' => NULL
			,'pen_pattern_rowid' => NULL //new
			,'pen_detail' => NULL //new
			,'m_pen' => NULL //new
			,'f_pen' => NULL //new
			,'is_pen_pos_left' => NULL //new
			,'is_pen_pos_right' => NULL //new
			,'remark1' => NULL
			,'remark2' => NULL
		);
	}
	function search($arrObj = array()) {
		
		$_sql = <<<EOT
SELECT sp.*, COALESCE(f.name, '--') AS fabric, COALESCE(s.name, '--') AS standard_pattern 
FROM v_raw_pattern_tshirt sp 
	LEFT OUTER JOIN pm_m_fabric f ON sp.fabric_rowid = f.rowid 
	LEFT OUTER JOIN pm_m_standard_pattern s ON sp.standard_pattern_rowid = s.rowid 
WHERE True 
EOT;
		if (array_key_exists('code', $arrObj)) if (trim($arrObj['code']) != '') $_sql .= "AND sp.code LIKE '%" . trim($arrObj['code']) . "%' ";
		if (array_key_exists('fabric_rowid', $arrObj)) if (trim($arrObj['fabric_rowid']) != '') $_sql .= "AND sp.fabric_rowid = " . trim($arrObj['fabric_rowid']) . " ";
		return $this->arr_execute($_sql);
	}
}