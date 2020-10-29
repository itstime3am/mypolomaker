<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_order_detail_jacket extends MY_Model {
	function __construct() {
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 't_order_detail_jacket';

		$this->_FIELDS = array(
			'order_rowid' => NULL
			//,'jacket_pattern_type_rowid' => NULL
			,'standard_pattern_rowid' => NULL
			,'fabric_rowid' => NULL
			,'neck_type_rowid' => NULL
			,'neck_type_detail' => NULL
			,'lining_type_rowid' => NULL
			,'lining_type_detail' => NULL
			,'m_shape_rowid' => NULL
			,'f_shape_rowid' => NULL
			,'main_color_rowid' => NULL
			,'line_color_rowid' => NULL
			,'sub_color1_rowid' => NULL
			,'sub_color2_rowid' => NULL
			,'sub_color3_rowid' => NULL
			,'color_detail' => NULL
			,'collar_type_rowid' => NULL
			,'collar_detail' => NULL
			,'collar_detail2' => NULL
			,'m_clasper_type_rowid' => NULL
			,'f_clasper_type_rowid' => NULL
			,'clasper_ptrn_rowid' => NULL
			,'clasper_detail' => NULL
			,'clasper_detail2' => NULL
			,'m_sleeves_type_rowid' => NULL
			,'f_sleeves_type_rowid' => NULL
			,'sleeves_detail' => NULL
			,'flap_type_rowid' => NULL
			,'flap_type_detail' => NULL
			,'flap_side_ptrn_rowid' => NULL
			,'flap_side_ptrn_detail' => NULL
			,'m_flap_side' => NULL
			,'f_flap_side' => NULL
			,'pocket_position_rowid' => NULL
			,'pocket_position_detail' => NULL
			,'pocket_type_rowid' => NULL
			,'pocket_type_detail' => NULL
			,'m_pocket' => NULL
			,'f_pocket' => NULL
			,'pen_pattern_rowid' => NULL
			,'pen_detail' => NULL
			,'m_pen' => NULL
			,'f_pen' => NULL
			,'is_pen_pos_left' => NULL
			,'is_pen_pos_right' => NULL
			,'remark1' => NULL
			,'remark2' => NULL
		);
	}
}