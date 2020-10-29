<?php
class Mdl_order_detail_tshirt extends MY_Model {
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'pm_t_order_detail_tshirt';

		$this->_FIELDS = array(
			'order_rowid' => NULL
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
/*	
	function search($arrObj = array()) {	
		$_sql = <<<EOT
SELECT sp.*, IFNULL(f.name, '--') AS fabric, IFNULL(cp.name, '--') AS clasper_ptrn, IFNULL(mc.name, '--') AS m_clasper_type, IFNULL(fc.name, '--') AS f_clasper_type 
FROM $this->_TABLE_NAME sp 
	LEFT OUTER JOIN pm_m_fabric f ON sp.fabric_rowid = f. rowid 
	LEFT OUTER JOIN pm_m_clasper_ptrn cp ON sp.clasper_ptrn_rowid = cp.rowid 
	LEFT OUTER JOIN pm_m_clasper_type mc ON sp.m_clasper_type_rowid = mc.rowid 
	LEFT OUTER JOIN pm_m_clasper_type fc ON sp.f_clasper_type_rowid = fc.rowid 
WHERE 1 
EOT;
		if (array_key_exists('code', $arrObj)) if (trim($arrObj['code']) != '') $_sql .= "AND sp.code LIKE '%" . trim($arrObj['code']) . "%' ";
		if (array_key_exists('fabric_rowid', $arrObj)) if (trim($arrObj['fabric_rowid']) != '') $_sql .= "AND sp.fabric_rowid = " . trim($arrObj['fabric_rowid']) . " ";
		return $this->arr_execute($_sql);
	}
*/
}