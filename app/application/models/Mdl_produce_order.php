<?php

class Mdl_produce_order extends MY_Model {
	function __construct()
	{
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'v_';
		$this->_AUTO_FIELDS = array('rowid' => '');
		$this->_FIELDS = array(
			'job_number' => '',
			'ref_number' => '',
			'customer_rowid' => '',
			'order_date' => '',
			'due_date' => '',
			'deliver_date' => '',
			'is_vat' => '',
			'has_sample' => '',
			'polo_pattern_rowid' => '',
			'size_category' => '',
			'file_image1' => '',
			'file_image2' => '',
			'remark1' => '',
			'remark2' => '',
			'create_by' => '',
			'create_date' => '',
			'update_by' => '',
			'update_date' => ''
			,'is_tax_inv_req' => ''
			,'file_image3' => NULL
			,'file_image4' => NULL
			,'file_image5' => NULL
			,'file_image6' => NULL
			,'file_image7' => NULL
			,'file_image8' => NULL
			,'file_image9' => NULL
			,'ps_rowid' => NULL
			,'status_remark' => NULL
		);
	}
	function search($arrObj = array()) {
		$_sql = <<<EOT
SELECT t.rowid, t.job_number, t.ref_number, t.customer_rowid, v.customer, t.order_date, t.deliver_date, t.due_date
,TO_CHAR(t.order_date, 'DD/MM/YYYY') AS disp_order_date, TO_CHAR(t.due_date, 'DD/MM/YYYY') AS disp_due_date
,TO_CHAR(t.deliver_date, 'DD/MM/YYYY') AS disp_deliver_date, t.is_vat, t.has_sample, t.polo_pattern_rowid, t.size_category
,t.file_image1, t.file_image2, ps.code AS ps_code, ps.name AS process_status 
,t.remark1, t.remark2, d.neck_type_rowid, d.neck_type_detail, d.base_pattern_rowid, d.base_pattern_detail
,d.standard_pattern_rowid, d.standard_pattern_detail, d.fabric_rowid, d.color, d.color_add1, d.color_add2, d.m_clasper_type_rowid
,d.f_clasper_type_rowid, d.clasper_ptrn_rowid, d.clasper_detail, d.collar_type_rowid, d.collar_detail, d.m_sleeves_type_rowid
,d.f_sleeves_type_rowid, d.sleeves_detail, d.flap_type_rowid, d.flap_type_detail, d.m_flap_side, d.f_flap_side, d.flap_side_ptrn_rowid
,d.flap_side_ptrn_detail, d.m_pocket, d.f_pocket, d.pocket_type_rowid, d.pocket_type_detail, d.m_pen, d.f_pen, d.pen_pattern_rowid
,d.pen_position_rowid, d.pen_detail, d.remark1 AS detail_remark1, d.remark2 AS detail_remark2
,fnc__dispVATType(t.is_vat) AS disp_vat_type, COALESCE(t.is_tax_inv_req, 0) AS is_tax_inv_req
, v.total_price_sum, v.total_deposit_payment, v.arr_deposit_log, v.total_payment, v.arr_payment_log
, v.total_price_sum - v.total_deposit_payment - v.total_payment AS total_left_amount
, fnc_order_avai_status(t.ps_rowid) AS avail_process_status
FROM pm_t_order_polo t
	INNER JOIN v_order_report_status v ON v.type_id = 1 AND v.order_rowid = t.rowid 
	INNER JOIN pm_t_order_detail_polo d ON t.rowid = d.order_rowid 
	LEFT OUTER JOIN m_process_status ps ON t.ps_rowid = ps.rowid
WHERE COALESCE(t.is_cancel, 0) < 1 

EOT;

		if (isset($arrObj['is_active_status']) && ($arrObj['is_active_status'])) {
			$_sql .= "\nAND (COALESCE(t.ps_rowid, 1) >= 10 AND (t.ps_rowid != 60))\n";
			unset($arrObj['is_active_status']);
		}
		$_sql .= $this->_getSearchConditionSQL($arrObj, 
			array(
				'job_number' => array("type"=>"txt"),
				'date_from' => array('type'=>'dat', 'dbcol'=>'t.order_date', 'operand'=>'>='),
				'date_to' => array('type'=>'dat', 'dbcol'=>'t.order_date', 'operand'=>'<=')
			)
		);
		$_sql .= $this->_getCheckAccessRight("t.create_by", "order");
		$_sql .= ' LIMIT 3000';

		return $this->arr_execute($_sql);
	}

	function list_size_quan() {
		$_sql = <<<EOT
SELECT s.rowid, c.rowid AS cat_rowid, c.name AS category, sc.rowid as sub_rowid, sc.name AS sub_category, s.size_text, s.size_chest 
, CASE WHEN s.is_use > 0 THEN 0 ELSE 1 END AS is_expired
FROM pm_m_order_size s
	INNER JOIN pm_m_order_size_cat c ON s.main_cat_rowid = c.rowid 
	INNER JOIN pm_m_order_size_sub_cat sc ON s.sub_cat_rowid = sc.rowid 
WHERE s.is_polo = 1 
-- AND s.is_use = 1 
ORDER BY s.main_cat_rowid, s.sub_cat_rowid, s.size_chest
EOT;
//ORDER BY CASE WHEN c.rowid = 8 THEN 0 ELSE c.rowid END, sc.rowid DESC, s.rowid

		$_arr = $this->arr_execute($_sql);
		$_arrData = array();
		$_arrIndex = array("cat" => array(), "sub" => array());
		if ($_arr !== FALSE) {
			foreach ($_arr as $_row) {
				if (! array_key_exists($_row['cat_rowid'], $_arrIndex["cat"])) {
					$_arrIndex["cat"][$_row['cat_rowid']] = $_row['category'];
				}
				if (! array_key_exists($_row['sub_rowid'], $_arrIndex["sub"])) {
					$_arrIndex["sub"][$_row['sub_rowid']] = $_row['sub_category'];
				}
				if (! array_key_exists($_row['cat_rowid'], $_arrData)) {
					$_arrData[$_row['cat_rowid']] = array();
				}
				if (! array_key_exists($_row['sub_rowid'], $_arrData[$_row['cat_rowid']])) {
					$_arrData[$_row['cat_rowid']][$_row['sub_rowid']] = array();
				}
				$_arrData[$_row['cat_rowid']][$_row['sub_rowid']][$_row['size_text']] = array('rowid'=>$_row['rowid'], 'chest' => $_row['size_chest'], 'is_expired' => $_row['is_expired']);
			}
		}
		return array("appendix" => $_arrIndex, "data" => $_arrData);
	}
	
	function list_job_number() {
		$_sql = <<<EOT
SELECT t.rowid, t.job_number 
FROM {$this->_TABLE_NAME} t 
WHERE True

EOT;
		$_sql .= $this->_getCheckAccessRight("t.create_by", "order");
		$_sql .= " ORDER BY t.rowid, t.job_number ";
		return $this->arr_execute($_sql);	
	}
	
    function get_detail_by_id($RowID) {
		$_sql = <<<EOT
SELECT t.* 
FROM v_order_polo t 
WHERE t.rowid = ?
EOT;
		$_sql .= $this->_getCheckAccessRight("t.create_by", "order");
		$_arr = $this->arr_execute($_sql, array($RowID));
		if (is_array($_arr) && count($_arr) > 0) {
			return $_arr[0];
		} else {
			return FALSE;
		}
	}

	function delete($RowID) {
		$_rowid = $this->db->escape((int) $RowID);
		if ($_rowid > 0) {
			while ($this->error_message == '') {
				$this->db->delete('pm_t_order_detail_polo', array('order_rowid'=>$_rowid));
				if ($this->db->error()['message'] !== "") {
					$this->error_message = $this->db->error()['message'];
					break;
				}
				$this->db->delete('pm_t_order_size_polo', array('order_rowid'=>$_rowid));
				if ($this->db->error()['message'] !== "") {
					$this->error_message = $this->db->error()['message'];
					break;
				}
				$this->db->delete('pm_t_order_size_custom_polo', array('order_rowid'=>$_rowid));
				if ($this->db->error()['message'] !== "") {
					$this->error_message = $this->db->error()['message'];
					break;
				}
				$this->db->delete('pm_t_order_price_polo', array('order_rowid'=>$_rowid));
				if ($this->db->error()['message'] !== "") {
					$this->error_message = $this->db->error()['message'];
					break;
				}
				$this->db->delete('pm_t_order_screen_polo', array('order_rowid'=>$_rowid));
				if ($this->db->error()['message'] !== "") {
					$this->error_message = $this->db->error()['message'];
					break;
				}
				$this->db->delete('t_order_add_option', array('order_type_id'=>1, 'order_rowid'=>$_rowid));
				if ($this->db->error()['message'] !== "") {
					$this->error_message = $this->db->error()['message'];
					break;
				}
				return parent::delete($RowID);
				break;
			}
		} else {
			$this->error_message = 'ERROR:: Invalid parameter "order_rowid" ( ' . $RowID . ' )';
		}
	}

    function get_detail_report($RowID) {
		$_sql = <<<EOT
SELECT m.job_number, m.ref_number, m.customer_name, m.option_promotion AS promotion, m.company, m.contact_no
, m.has_sample, m.is_tax_inv_req, m.address, m.customer_mobile, m.customer_tel, m.tax_id, m.tax_branch, m.disp_vat_type
, m.disp_order_date AS order_date, m.disp_due_date AS due_date, m.disp_deliver_date AS deliver_date
, COALESCE(mp.code, CASE WHEN COALESCE(m.polo_pattern_rowid, -1) = -1 THEN 'รูปแบบมาตรฐาน' ELSE '' END) AS pattern_name
, COALESCE(f.name, '') AS fabric_name, COALESCE(n.name, '') AS neck_type_name, m.neck_type_detail
, COALESCE(nh.name, '') AS neck_hem_name, m.neck_hem_detail, COALESCE(mbs.name, '') AS m_base_shape, COALESCE(fbs.name, '') AS f_base_shape
, COALESCE(mcol.name, ''::text) AS main_color, COALESCE(lcol.name, ''::text) AS line_color, COALESCE(s1col.name, ''::text) AS sub_color1
, COALESCE(s2col.name, ''::text) AS sub_color2, COALESCE(s3col.name, ''::text) AS sub_color3
, m.option_hem_rowid, COALESCE(hcol.name, ''::text) AS option_hem_color, m.color_detail
, COALESCE(cl.name, '') AS collar_name, m.collar_detail, m.collar_detail2
, COALESCE(ctm.name, '') AS m_clasper_name, COALESCE(ctf.name, '') AS f_clasper_name, COALESCE(cp.name, '') AS clasper_pattern_name
, m.clasper_detail, m.clasper_detail2
, COALESCE(msl.name, '') AS m_sleeves_name, COALESCE(fsl.name, '') AS f_sleeves_name, m.sleeves_detail
, COALESCE(ft.name, '') AS flap_type_name, m.flap_type_detail, m.option_is_mfl, m.option_is_ffl, m.option_male_fix_length, m.option_female_fix_length
, COALESCE(fsp.name, '') AS flap_side_ptrn_name, m.flap_side_ptrn_detail, m.m_flap_side, m.f_flap_side
, COALESCE(pt.name, '') AS pocket_type_name, m.pocket_type_detail, m.m_pocket, m.f_pocket
, COALESCE(pp.name, '') AS pen_pattern_name, m.pen_detail, m.m_pen, m.f_pen, m.is_pen_pos_left, m.is_pen_pos_right
, m.option_is_no_neck_tag, m.option_is_customer_size_tag, m.option_is_no_plmk_size_tag
, m.option_is_no_back_clasper, m.option_is_pakaging_tpb, m.option_is_no_packaging_sep_tpb
, m.detail_remark1, m.detail_remark2
, m.polo_pattern_rowid, COALESCE(m.size_category, -1) AS size_category
, m.file_image1, m.file_image2, m.file_image3, m.file_image4, m.file_image5, m.file_image6, m.file_image7, m.file_image8
, m.file_image9, m.remark1, m.remark2
, m.neck_type_rowid, m.base_pattern_rowid, m.base_pattern_detail, m.standard_pattern_rowid, m.standard_pattern_detail
, m.fabric_rowid, m.m_clasper_type_rowid, m.f_clasper_type_rowid, m.clasper_ptrn_rowid, m.collar_type_rowid
, m.m_sleeves_type_rowid, m.f_sleeves_type_rowid, m.flap_type_rowid, m.flap_side_ptrn_rowid
, m.pocket_type_rowid, m.pen_pattern_rowid, m.pen_position_rowid, m.create_by
, COALESCE(bp.name, '') AS base_pattern_name, COALESCE(sp.name, '') AS standard_pattern_name
FROM v_order_polo m
	LEFT OUTER JOIN pm_t_polo_pattern mp ON mp.rowid = m.polo_pattern_rowid
	LEFT OUTER JOIN pm_m_standard_pattern sp ON m.standard_pattern_rowid = sp.rowid
	LEFT OUTER JOIN pm_m_base_pattern bp ON m.base_pattern_rowid = bp.rowid
	LEFT OUTER JOIN pm_m_fabric f ON m.fabric_rowid = f.rowid
	LEFT OUTER JOIN pm_m_collar_type cl ON m.collar_type_rowid = cl.rowid
	LEFT OUTER JOIN pm_m_neck_type n ON m.neck_type_rowid = n.rowid
	LEFT OUTER JOIN pm_m_flap_type ft ON m.flap_type_rowid = ft.rowid
	LEFT OUTER JOIN pm_m_flap_side_ptrn fsp ON m.flap_side_ptrn_rowid = fsp.rowid
	LEFT OUTER JOIN pm_m_pocket_type pt ON m.pocket_type_rowid = pt.rowid
	LEFT OUTER JOIN pm_m_pen_pattern pp ON m.pen_pattern_rowid = pp.rowid
	LEFT OUTER JOIN pm_m_pen_position ppn ON m.pen_position_rowid = ppn.rowid
	LEFT OUTER JOIN m_neck_hem nh ON nh.rowid = m.neck_hem_rowid
	LEFT OUTER JOIN m_base_shape mbs ON m.m_shape_rowid = mbs.rowid
	LEFT OUTER JOIN m_base_shape fbs ON m.f_shape_rowid = fbs.rowid
	LEFT OUTER JOIN m_color mcol ON mcol.rowid = m.main_color_rowid
	LEFT OUTER JOIN m_color lcol ON lcol.rowid = m.line_color_rowid
	LEFT OUTER JOIN m_color s1col ON s1col.rowid = m.sub_color1_rowid
	LEFT OUTER JOIN m_color s2col ON s2col.rowid = m.sub_color2_rowid
	LEFT OUTER JOIN m_color s3col ON s3col.rowid = m.sub_color3_rowid
	LEFT OUTER JOIN m_color hcol ON hcol.rowid = m.option_hem_color_rowid
	LEFT OUTER JOIN pm_m_clasper_type ctm ON m.m_clasper_type_rowid = ctm.rowid
	LEFT OUTER JOIN pm_m_clasper_type ctf ON m.f_clasper_type_rowid = ctf.rowid
	LEFT OUTER JOIN pm_m_clasper_ptrn cp ON m.clasper_ptrn_rowid = cp.rowid
	LEFT OUTER JOIN pm_m_sleeves_type msl ON m.m_sleeves_type_rowid = msl.rowid
	LEFT OUTER JOIN pm_m_sleeves_type fsl ON m.f_sleeves_type_rowid = fsl.rowid
WHERE m.rowid = ?
EOT;
		$_sql .= $this->_getCheckAccessRight("m.create_by", "order");
		$_arr = $this->arr_execute($_sql, array($RowID));
		$_item = array();
		if (is_array($_arr) && count($_arr) > 0) {
			$_item = $_arr[0];
			$_item['user_name'] = '';
			$_user = JFactory::getUser($_item['create_by']);
			if ($_user) $_item['user_name'] = $_user->username;
		}
		
		$_whileChecker = TRUE;
		while ($_whileChecker) {
			$_size_cat = trim(array_key_exists('size_category', $_item) ? $_item['size_category'] : '');
			if ($_size_cat == '') $_size_cat = -1;
			$_sql = <<<EOT
SELECT s.size_text, s.size_chest, COALESCE(t.qty, 0) AS qty, TO_CHAR(COALESCE(price, 0), '9G999G990D00') AS price
, CASE sc.rowid WHEN 1 THEN 'c' WHEN 2 THEN 'f' WHEN 3 THEN 'm' WHEN 4 THEN 'c' ELSE '-' END AS type 
FROM pm_m_order_size s
	INNER JOIN pm_m_order_size_cat c ON s.main_cat_rowid = c.rowid 
	INNER JOIN pm_m_order_size_sub_cat sc ON s.sub_cat_rowid = sc.rowid 
	LEFT OUTER JOIN (SELECT * FROM pm_t_order_size_polo WHERE order_rowid = $RowID) AS t ON t.order_size_rowid = s.rowid 
WHERE s.is_polo = 1 
AND (s.is_use = 1 OR COALESCE(t.qty, 0) > 0)
AND c.rowid = $_size_cat 
ORDER BY c.rowid, sc.rowid, s.size_chest
EOT;
			$_arr1 = $this->arr_execute($_sql);
			if ($this->error_message != '') break;
			
			$_arrSizeQuan = array();
			if (is_array($_arr1)) {
				foreach ($_arr1 as $_row) {
					array_push($_arrSizeQuan, array(
						'type' => $_row['type'],
						'size' => $_row['size_text'],
						'chest' => $_row['size_chest'],
						'qty' => $_row['qty'],
						'price' => $_row['price']
					));
				}
			}
				
			$_sql = <<<EOT
SELECT size_text, size_chest, qty
, CASE sub_cat_rowid WHEN 1 THEN 'c' WHEN 2 THEN 'f' WHEN 3 THEN 'm' WHEN 4 THEN 'c' ELSE '-' END AS type
, TO_CHAR(COALESCE(price, 0), '9G999G990D00') AS price 
FROM pm_t_order_size_custom_polo 
WHERE order_rowid = $RowID 
AND main_cat_rowid = $_size_cat 
ORDER BY sub_cat_rowid, size_chest
EOT;
			$_arr2 = $this->arr_execute($_sql);
			if ($this->error_message !== '') break;
			if (is_array($_arr2)) {
				foreach ($_arr2 as $_row) {
					array_push($_arrSizeQuan, array(
						'type' => $_row['type'],
						'size' => $_row['size_text'],
						'chest' => $_row['size_chest'],
						'qty' => $_row['qty'],
						'price' => $_row['price']
					));
				}
			}
			$_item['size_quan'] = $_arrSizeQuan;

			$_sql = <<<OTP
SELECT t.detail AS detail, TO_CHAR(COALESCE(t.price, 0), '9G999G990D00') AS price 
FROM pm_t_order_price_polo t 
WHERE t.order_rowid = $RowID
ORDER BY t.seq
OTP;
			$_arr3 = $this->arr_execute($_sql);
			if ($this->error_message !== '') break;
			if (is_array($_arr3)) {
				$_item['others_price'] = $_arr3;
			}

			$_sql = <<<SCRN
SELECT t.order_screen_rowid, COALESCE(m.name, '') AS order_screen, t.position, t.detail, t.size, t.job_hist
, TO_CHAR(COALESCE(t.price, 0), '9G999G990D00') AS price 
FROM pm_t_order_screen_polo t LEFT OUTER JOIN pm_m_order_screen m ON t.order_screen_rowid = m.rowid 
WHERE t.order_rowid = $RowID 
ORDER BY t.seq, t.order_screen_rowid, t.position 
SCRN;
			$_arr4 = $this->arr_execute($_sql);
			if ($this->error_message !== '') break;
			if ($_arr4 == FALSE) $_arr4 = array();
			$_item['screen'] = $_arr4;
			
			$_whileChecker = FALSE;
		}

		if (($this->error_message == '') && (count($_item) > 0)) {
			return $_item;
		} else {
			return FALSE;
		}
	}

	function change_status_by_id($rowid, $ps_rowid, $status_remark = FALSE) {
		$_rowid = $this->db->escape((int) $rowid);
		$_ps_rowid = $this->db->escape((int) $ps_rowid);
		
		if ($status_remark) $this->db->set('status_remark', $status_remark);
		$this->db->set('ps_rowid', $_ps_rowid);
		$this->db->set('update_by', $this->db->escape((int)$this->session->userdata('user_id')));
		$this->db->where('rowid', $_rowid);
		$this->db->update($this->_TABLE_NAME);
		
		$this->error_message = $this->db->error()['message'];
		return true;
	}

}