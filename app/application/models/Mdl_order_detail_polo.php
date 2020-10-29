<?php
class Mdl_order_detail_polo extends MY_Model {
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'pm_t_order_detail_polo';
		$this->_FIELDS = array(
			'order_rowid' => NULL
			,'standard_pattern_rowid' => NULL
			,'fabric_rowid' => NULL
			,'neck_type_rowid' => NULL
			,'neck_type_detail' => NULL
			,'neck_hem_rowid' => NULL //new
			,'neck_hem_detail' => NULL //new
			,'m_shape_rowid' => NULL //new
			,'f_shape_rowid' => NULL //new
			,'main_color_rowid' => NULL //new
			,'line_color_rowid' => NULL //new
			,'sub_color1_rowid' => NULL //new
			,'sub_color2_rowid' => NULL //new
			,'sub_color3_rowid' => NULL //new
			,'color_detail' => NULL //new
			,'collar_type_rowid' => NULL
			,'collar_detail' => NULL
			,'collar_detail2' => NULL //new
			,'m_clasper_type_rowid' => NULL
			,'f_clasper_type_rowid' => NULL
			,'clasper_ptrn_rowid' => NULL
			,'clasper_detail' => NULL
			,'clasper_detail2' => NULL //new
			,'m_sleeves_type_rowid' => NULL
			,'f_sleeves_type_rowid' => NULL
			,'sleeves_detail' => NULL
			,'flap_type_rowid' => NULL
			,'flap_type_detail' => NULL
			,'flap_side_ptrn_rowid' => NULL
			,'flap_side_ptrn_detail' => NULL
			,'m_flap_side' => NULL
			,'f_flap_side' => NULL
			,'pocket_type_rowid' => NULL
			,'pocket_type_detail' => NULL
			,'m_pocket' => NULL
			,'f_pocket' => NULL
			,'pen_pattern_rowid' => NULL
			,'pen_detail' => NULL
			,'m_pen' => NULL
			,'f_pen' => NULL
			,'is_pen_pos_left' => NULL //new
			,'is_pen_pos_right' => NULL //new
			,'remark1' => NULL
			,'remark2' => NULL
		);
/* 
		// ++ Obsoleted ++
			,'standard_pattern_detail' => NULL
			,'color' => NULL
			,'color_add1' => NULL
			,'color_add2' => NULL
			,'pen_position_rowid' => NULL
		// -- Obsoleted --
*/
	}
}