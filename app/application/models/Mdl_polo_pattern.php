<?php
class Mdl_polo_pattern extends MY_Model {
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
	$this->_TABLE_NAME = 'pm_t_polo_pattern';
		$this->_AUTO_FIELDS = array(
			'rowid' => ''
		);
		$this->_FIELDS = array(
			'code' => '',
			'neck_type_rowid' => '',
			//'neck_type_detail' => '',
			//'base_pattern_rowid' => '',
			//'base_pattern_detail' => '',
			'standard_pattern_rowid' => '',
			//'standard_pattern_detail' => '',
			'fabric_rowid' => '',
			//'color' => '',
			//'color_add1' => '',
			//'color_add2' => '',
			'm_clasper_type_rowid' => '',
			'f_clasper_type_rowid' => '',
			'clasper_ptrn_rowid' => '',
			'clasper_detail' => '',
			'collar_type_rowid' => '',
			'collar_detail' => '',
			'm_sleeves_type_rowid' => '',
			'f_sleeves_type_rowid' => '',
			'sleeves_detail' => '',
			'flap_type_rowid' => '',
			'flap_type_detail' => '',
			'm_flap_side' => '',
			'f_flap_side' => '',
			'flap_side_ptrn_rowid' => '',
			'flap_side_ptrn_detail' => '',
			'm_pocket' => '',
			'f_pocket' => '',
			'pocket_type_rowid' => '',
			'pocket_type_detail' => '',
			'm_pen' => '',
			'f_pen' => '',
			'pen_pattern_rowid' => '',
			//'pen_type_rowid' => '',
			//'pen_position_rowid' => '',
			'pen_detail' => '',
			'remark1' => '',
			'remark2' => '',
			//'remark3' => '',
			/*'create_by' => '',
			'create_date' => '',
			'update_by' => '',
			'update_date' => '',*/
			"neck_hem_rowid" => 0,
			"neck_hem_detail" => '',
			"m_shape_rowid" => 0,
			"f_shape_rowid" => 0,
			"main_color_rowid" => 0,
			"line_color_rowid" => 0,
			"sub_color1_rowid" => 0,
			"sub_color2_rowid" => 0,
			"sub_color3_rowid" => 0,
			"color_detail" => '',
			"collar_detail2" => '',
			"clasper_detail2" => '',
			"is_pen_pos_left" => 0,
			"is_pen_pos_right" => 0
		);
		/*
								,
			"option_hem_rowid" => 0,
			"option_hem_color_rowid" => 0,
			"option_is_no_neck_tag" => 0,
			"option_is_customer_size_tag" => 0,
			"option_is_no_plmk_size_tag" => 0,
			"option_is_no_back_clasper" => 0,
			"option_pakaging_type_rowid" => 0,
			"option_pakaging_method_rowid" => 0
		*/
	}
	function search($arrObj = array()) {	
		$_sql = <<<EOT
SELECT sp.*, COALESCE(f.name, '--') AS fabric, COALESCE(cp.name, '--') AS clasper_ptrn, COALESCE(mc.name, '--') AS m_clasper_type, COALESCE(fc.name, '--') AS f_clasper_type 
FROM v_raw_pattern_polo sp 
	LEFT OUTER JOIN pm_m_fabric f ON sp.fabric_rowid = f. rowid 
	LEFT OUTER JOIN pm_m_clasper_ptrn cp ON sp.clasper_ptrn_rowid = cp.rowid 
	LEFT OUTER JOIN pm_m_clasper_type mc ON sp.m_clasper_type_rowid = mc.rowid 
	LEFT OUTER JOIN pm_m_clasper_type fc ON sp.f_clasper_type_rowid = fc.rowid 
WHERE True 
EOT;
		if (array_key_exists('code', $arrObj)) if (trim($arrObj['code']) != '') $_sql .= "AND sp.code LIKE '%" . trim($arrObj['code']) . "%' ";
		if (array_key_exists('fabric_rowid', $arrObj)) if (trim($arrObj['fabric_rowid']) != '') $_sql .= "AND sp.fabric_rowid = " . trim($arrObj['fabric_rowid']) . " ";
		$_sql .= "\n ORDER BY rowid ASC";
//echo $_sql;exit;
		return $this->arr_execute($_sql);
	}
}