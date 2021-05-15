<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_quotation_detail extends MY_Model {
	function __construct() {
		parent::__construct();
		if (! isset($this->db)) {
			$this->db = $this->load->database('default', TRUE);
		}
		$this->_TABLE_NAME = 'pm_t_quotation_detail';
		$this->_AUTO_FIELDS = array('rowid' => '');
		$this->_FIELDS = array(
			'quotation_rowid' => NULL,
			//'revision' => ''
			//'seq'=>'',
			//'is_deleted' => 0
			'title_rowid' => NULL,
			'description' => NULL,
			'qty' => NULL,
			'price' => NULL,
			'amount' => NULL,
			'remark' => NULL,
			'json_details' => NULL,
			'json_others' => NULL,
			'json_images' => NULL,
			'create_by' => NULL,
			'create_date' => NULL,
			'update_by' => NULL,
			'update_date' => NULL
		);
		$this->_JSON_FIELDS = array('json_details', 'json_others', 'json_images');
	}
	
	function search($arrObj = array()) {
		$_sql = <<<QUERY
SELECT t.rowid, t.quotation_rowid, t.title_rowid, t.description, t.json_details, t.json_others, t.json_images
, COALESCE(r.total_qty, t.qty) AS qty, COALESCE(CASE WHEN r.total_qty > 0 THEN r.total_price_sum_net / r.total_qty END, t.price) AS price
, COALESCE(r.total_price_sum_net, t.amount) AS amount, t.remark, t.seq
, CASE WHEN r.dtl_count = 1 and r.total_qty > 0 THEN TO_CHAR(r.total_price_sum / r.total_qty, '9G999G999D00') ELSE 'n/a' END AS disp_price
, CONCAT(SUBSTR(t.description, 1, 50), CASE WHEN LENGTH(t.description) > 50 THEN '...' ELSE '' END) AS abbr_description
, qt.name AS title, qt.order_type_id AS type_id, m.qo_number -- , COALESCE(t.amount, COALESCE(t.qty, 0) * COALESCE(t.price, 0)) AS amount
, r.category AS produce_order_category, r.type AS produce_order_type, r.type_id AS order_type_id, r.order_rowid, r.job_number AS order_job_number
, ROW_NUMBER() OVER(PARTITION BY t.quotation_rowid ORDER BY t.seq ASC) AS seq
FROM pm_t_quotation_detail t 
	INNER JOIN pm_t_quotation m ON t.quotation_rowid = m.rowid 
	LEFT OUTER JOIN pm_m_quotation_detail_title qt ON t.title_rowid = qt.rowid
	LEFT OUTER JOIN v_order r ON r.quotation_detail_rowid = t.rowid AND coalesce(r.is_cancel, 0) < 1 -- AND r.order_type_id = qt.order_type_id
WHERE COALESCE(m.is_deleted, 0) < 1
AND COALESCE(t.is_deleted, 0) < 1

QUERY;
		$_sql .= $this->_getSearchConditionSQL($arrObj);
		$_sql .= $this->_getCheckAccessRight__jug_id("m.create_by", "m.branch_jug_id", "quotation");
		$_sql .= "ORDER BY t.rowid, t.seq ASC";

		return $this->arr_execute($_sql);
	}

	function search_for_produce($arrObj = array()) {
		$_sql = <<<QUERY
SELECT t.*
, CASE WHEN t.qty > 0 THEN TO_CHAR(t.amount / t.qty, '9G999G999D00') ELSE 'n/a' END AS disp_price
, CONCAT(SUBSTR(t.description, 1, 50), CASE WHEN LENGTH(t.description) > 50 THEN '...' ELSE '' END) AS abbr_description
, qt.name AS title, qt.order_type_id AS type_id, m.qo_number -- , COALESCE(t.amount, COALESCE(t.qty, 0) * COALESCE(t.price, 0)) AS amount
, r.category AS produce_order_category, r.type AS produce_order_type, r.type_id AS order_type_id, r.order_rowid, r.job_number AS order_job_number
, ROW_NUMBER() OVER(PARTITION BY t.quotation_rowid ORDER BY t.seq ASC) AS seq
FROM pm_t_quotation_detail t 
	INNER JOIN pm_t_quotation m ON t.quotation_rowid = m.rowid 
	LEFT OUTER JOIN pm_m_quotation_detail_title qt ON t.title_rowid = qt.rowid
	LEFT OUTER JOIN (
		SELECT category, type, type_id, order_rowid, job_number, quotation_detail_rowid
		FROM v_order 
		WHERE is_cancel < 1
		GROUP BY category, type, type_id, order_rowid, job_number, quotation_detail_rowid
	) r ON r.quotation_detail_rowid = t.rowid -- AND r.order_type_id = qt.order_type_id
WHERE COALESCE(m.is_deleted, 0) < 1
AND COALESCE(t.is_deleted, 0) < 1
AND (m.status_rowid BETWEEN 0 AND 200)

QUERY;
	
		$_sql .= $this->_getSearchConditionSQL($arrObj);
		$_sql .= $this->_getCheckAccessRight__jug_id("m.create_by", "m.branch_jug_id", "quotation");
		$_sql .= "ORDER BY t.seq ASC";
		
		return $this->arr_execute($_sql);
	}

	function fncQuotationCreateLinkProduceOrder($arrData) {
		$_qod_rowid = (isset($arrData['quotation_detail_rowid'])) ? $this->db->escape((int) $arrData['quotation_detail_rowid']) : -1;
		$_user_id = $this->db->escape((int)$this->session->userdata('user_id'));
		$_order_date = (isset($arrData['create_order_date'])) ? $this->_datFromPost($arrData['create_order_date']) : false;
		if ($_order_date instanceof DateTime) {
			$_order_date = $_order_date->format('Ymd');
		} else {
			$_order_date = null;
		}
		$_ws_sample_date = (isset($arrData['ws_sample_date'])) ? $this->_datFromPost($arrData['ws_sample_date']) : false;
		if ($_ws_sample_date instanceof DateTime) {
			$_ws_sample_date = $_ws_sample_date->format('Ymd');
		} else {
			$_ws_sample_date = null;
		}
		$_deliver_date = (isset($arrData['create_deliver_date'])) ? $this->_datFromPost($arrData['create_deliver_date']) : false;
		if ($_deliver_date instanceof DateTime) {
			$_deliver_date = $_deliver_date->format('Ymd');
		} else {
			$_deliver_date = null;
		}
		$_supplier_rowid = (isset($arrData['supplier_rowid'])) ? $this->db->escape((int) $arrData['supplier_rowid']) : -1;
		if ($_supplier_rowid < 1) $_supplier_rowid = null;
		$_order_remark = (isset($arrData['order_remark'])) ? $arrData['order_remark'] : '';

		$_arrResult = $this->arr_execute('SELECT fnc__quotationDetailCreateLinkProduceOrder(?, ?, ?, ?, ?, ?, ?) AS _result;', array($_qod_rowid, $_user_id, $_order_date, $_ws_sample_date, $_deliver_date, $_supplier_rowid, $_order_remark));
//echo $this->db->last_query();exit;
		if (is_array($_arrResult) && (count($_arrResult) > 0)) {
			return $_arrResult[0]['_result'];
		} else {
			return FALSE;
		}
	}

	function search_for_deliver($arrObj = array()) {
		$_quotation_rowid = (isset($arrObj['quotation_rowid'])) ? $this->db->escape((int) $arrObj['quotation_rowid']) : -1;
		$_sql = <<<QUERY
WITH _deliver AS (
	SELECT d.rowid AS deliver_rowid, d.deliver_job_number, da.deliver_status_id AS status_rowid
	, COALESCE(ds.name, 'ออกใบนำส่ง') AS deliver_status, v.type_id, v.order_rowid
	, COALESCE(v.order_detail_rowid, -1) AS order_detail_rowid, CONCAT(v.type, v.category) AS disp_type
	, CONCAT_WS(': ', v.job_number, v.pattern) AS disp_label, dd.rowid AS dd_rowid, COALESCE(dd.qty, 0) AS deliver_qty, dd.seq
	FROM pm_t_delivery d 
		INNER JOIN pm_t_delivery_detail dd on dd.delivery_rowid = d.rowid
		INNER JOIN v_order_report v 
			ON v.type_id = dd.order_type_id 
			AND v.order_rowid = dd.order_rowid 
			AND COALESCE(dd.order_detail_rowid, -1) = COALESCE(v.order_detail_rowid, -1)
		INNER JOIN pm_t_quotation_detail qd on qd.rowid = v.quotation_detail_rowid
		LEFT OUTER JOIN pm_t_delivery_approve da ON da.delivery_rowid = d.rowid AND COALESCE(da.is_rejected, -1) < 1
		LEFT OUTER JOIN m_deliver_status ds ON ds.rowid = da.deliver_status_id
	WHERE COALESCE(d.is_deleted, -1) < 1
	AND COALESCE(v.is_cancel, -1) < 1
	AND COALESCE(qd.is_deleted, -1) < 1
	AND qd.quotation_rowid = ?
)
, _gr_by_dlv AS (
	SELECT deliver_rowid, status_rowid, deliver_status, deliver_job_number, COUNT(dd_rowid) AS items
	, SUM(deliver_qty) AS total_deliver_qty
	, JSON_AGG(
		(
			SELECT ROW_TO_JSON(_row.*)
			FROM (
				SELECT ROW_NUMBER() OVER() AS indx, disp_type, disp_label, deliver_qty::TEXT AS disp_deliver_qty
			) _row
		) ORDER BY seq
	) AS arr_details
	FROM _deliver
	GROUP BY deliver_rowid, status_rowid, deliver_status, deliver_job_number
)
, _query AS (
	SELECT 0 AS order_type, d.deliver_rowid AS rowid, d.deliver_rowid
	, CONCAT('ใบนำส่งเลขที่: ', d.deliver_job_number) AS disp_title, CONCAT(d.items, ' item(s)') AS description
	, d.total_deliver_qty AS total_deliver_qty, NULL AS left_qty, NULL AS original_qty
	, d.deliver_status AS disp_status, NULL AS deliverable, NULL AS type_id, NULL AS order_rowid, NULL AS order_detail_rowid
	, NULL AS customer_rowid, NULL AS customer, NULL AS company, NULL AS arr_full_address, NULL AS is_vat, NULL AS is_tax_inv_req
	, NULL AS total_price_each, NULL AS percent_discount, NULL AS raw_discount, NULL AS each_discount
	, NULL AS avg_deposit_payment, NULL AS avg_close_payment, NULL AS disp_deposit_date, NULL AS disp_close_date 
	, NULL AS str_deposit_date, NULL AS str_close_date , NULL AS deposit_route, NULL AS close_route
	--, NULL AS json_details, NULL AS json_images, NULL AS json_others
	FROM _gr_by_dlv d
	UNION ALL
	SELECT 1 AS order_type, v.order_rowid AS rowid, NULL AS deliver_rowid
	, CONCAT('ใบสั่งผลิตเลขที่: ', v.job_number) AS disp_title, CONCAT(v.type, ' ' || v.category, ' ' || v.fabric, ' ( ' || vs.str_size_abbr || ' )') AS description
	, 0 AS total_deliver_qty, v.sum_qty - COALESCE(dj.sum_deliver_qty, 0) AS left_qty, v.sum_qty AS original_qty
	, v.process_status AS disp_status, CASE WHEN v.ps_rowid >= 60 THEN 1 ELSE 0 END AS deliverable
	, v.type_id, v.order_rowid, v.order_detail_rowid, v.customer_rowid, v.customer, v.company, va.arr_full_address
	, v.is_vat, v.is_tax_inv_req, v.total_price_each, v.percent_discount AS percent_discount, v.raw_discount
	, (((COALESCE(v.percent_discount, 0) / 100) * COALESCE(v.total_price_each, 0))) AS each_discount
	, CASE WHEN v.sum_qty > 0 THEN v.deposit_payment / v.sum_qty ELSE v.deposit_payment END AS avg_deposit_payment
	, CASE WHEN v.sum_qty > 0 THEN v.close_payment / v.sum_qty ELSE v.close_payment END AS avg_close_payment
	, v.disp_deposit_date, v.disp_close_date
	, TO_CHAR(v.deposit_date, 'YYYYMMDD') AS str_deposit_date, TO_CHAR(v.close_date, 'YYYYMMDD') AS str_close_date
	, CASE WHEN JSON_ARRAY_LENGTH(v.arr_deposit_log) > 0 THEN (v.arr_deposit_log->0)->>'payment_route' END AS deposit_route
	, CASE WHEN JSON_ARRAY_LENGTH(v.arr_payment_log) > 0 THEN (v.arr_payment_log->0)->>'payment_route' END AS close_route
	-- , t.json_details, t.json_images, t.json_others
	FROM pm_t_quotation_detail t
		INNER JOIN pm_t_quotation m ON t.quotation_rowid = m.rowid
		INNER JOIN v_order_report_status v ON t.rowid = v.quotation_detail_rowid
		LEFT OUTER JOIN v_customer_address va ON va.customer_rowid = v.customer_rowid
		LEFT OUTER JOIN v_order_sizes vs 
			ON vs.type_id = v.type_id 
			AND vs.order_rowid = v.order_rowid 
			AND COALESCE(vs.order_detail_rowid, -1) = COALESCE(v.order_detail_rowid, -1)
		LEFT OUTER JOIN (
			SELECT type_id, order_rowid, order_detail_rowid
			, COALESCE(SUM(deliver_qty), 0) AS sum_deliver_qty
			FROM _deliver
			GROUP BY type_id, order_rowid, order_detail_rowid
		) dj 
			ON dj.type_id = v.type_id 
			AND dj.order_rowid = v.order_rowid 
			AND dj.order_detail_rowid = COALESCE(v.order_detail_rowid, -1)
	WHERE COALESCE(v.is_cancel, -1) < 1
	AND COALESCE(t.is_deleted, -1) < 1
	AND COALESCE(m.is_deleted, -1) < 1
	AND t.quotation_rowid = ?
)
SELECT _query.*, m.*
FROM _query, (SELECT branch_jug_id, create_by FROM pm_t_quotation WHERE rowid = ? and COALESCE(is_deleted, -1) < 1) m
WHERE True

QUERY;
		$_sql .= $this->_getCheckAccessRight__jug_id("create_by", "branch_jug_id", "quotation");
		$_sql .= "ORDER BY order_type, deliverable DESC, deliver_rowid, type_id, order_rowid, order_detail_rowid";
		
		return $this->arr_execute($_sql, array($_quotation_rowid, $_quotation_rowid, $_quotation_rowid));
	}

	function fnc_get_order_detail_from_json($quotation_detail_rowid) {
		$_rowid = $this->db->escape((int) $quotation_detail_rowid);
		$_arrReturn = FALSE;

		try {
			$_arrResult = $this->arr_execute('SELECT * FROM fnc_v_quotation_detail_get_order_detail(?) LIMIT 1;', array($_rowid));
			if (is_array($_arrResult) && (count($_arrResult) > 0)) {
				$_arr = $_arrResult[0];
//var_dump($_arr);exit;
				$_arrReturn = json_decode($_arr['fnc_v_quotation_detail_get_order_detail'], TRUE);
//var_dump($_arrReturn);exit;
				$_type_id = (int) $_arrReturn['type_id'];

				switch ($_type_id) {
					case 1:
					case 2:
					case 5:
					case 6:
					case 9:
					case 10:
					case 11:
					case 15:
						$_size_category = (isset($_arrReturn['size_category']) && is_numeric($_arrReturn['size_category'])) ? (int) $_arrReturn['size_category'] : 0;
						$_arrReturn['size_quan'] = $this->_get_subTableSizeQuan($_type_id, $_rowid, $_size_category);
						break;
					case 3:
					case 4:
					case 7:
					case 8:
					case 12:
					case 14:
						$_arrReturn['detail'] = $this->_get_premadeSubTableDetails($_type_id, $_rowid);
						break;
				}
				$_arrReturn['screen'] = $this->_get_subTableScreenWeave($_type_id, $_rowid);
				$_arrOP = $this->_get_arrOtherPrice($_type_id, $_rowid);
				if (is_array($_arrOP)) {
					$_arrReturn['others_price'] = $_arrOP;
				}
			}
		} catch (Exception $e) {
			$this->error_message = $e->getMessage();
			return FALSE;
		}
		if (($_arrReturn) && (count($_arrReturn) > 0)) {
			return $_arrReturn;
		} else {
			return FALSE;
		}
	}

	function _get_arrOtherPrice($type_id, $quotation_detail_rowid) {
		$_arrReturn = array();
		$_type_id = (is_numeric($type_id)) ? (int) $type_id : -1;
		$_rowid = (is_numeric($quotation_detail_rowid)) ? (int) $quotation_detail_rowid : -1;
		$_sql = <<<OTP
			SELECT ((o._jsOP)->>'detail')::TEXT AS detail
			, TO_CHAR(COALESCE(((o._jsOP)->>'price')::NUMERIC(14, 5), 0), '9G999G999D00') AS price
			FROM (
				SELECT JSON_ARRAY_ELEMENTS((json_others->>'others_price')::JSON)::JSON AS _jsOP FROM pm_t_quotation_detail WHERE rowid = ?
			) o;
OTP;
		$_arr = $this->arr_execute($_sql, array($_rowid));
		$_strError = $this->error_message;
		if ($_strError != '') throw new MyException('Error getting other price data:: ' . $_strError);
		if (is_array($_arr) && (count($_arr) > 0)) $_arrReturn = $_arr;
		
		return $_arrReturn;
	}
	
	function _get_subTableSizeQuan($type_id, $quotation_detail_rowid, $size_category) {
		$_arrReturn = array();
		$_type_id = (is_numeric($type_id)) ? (int) $type_id : -1;
		$_rowid = (is_numeric($quotation_detail_rowid)) ? (int) $quotation_detail_rowid : -1;
		$_size_category = (is_numeric($size_category)) ? (int) $size_category : -1;
		switch ($_type_id) {
			case 1:
			case 2:
			case 5:
			case 6:
			case 9:
			case 10:
			case 11:
			case 15:
				$_arr1 = $this->arr_execute('SELECT * FROM fnc_get_order_sizequan_from_qod(?);', array($quotation_detail_rowid));
				$_strError = $this->error_message;
				if ($_strError != '') throw new MyException('Error getting order size data:: ' . $_strError);
				if (is_array($_arr1)) {
					foreach ($_arr1 as $_row) {
						array_push($_arrReturn, array(
							'type' => $_row['type'],
							'size' => $_row['size_text'],
							'chest' => $_row['size_chest'],
							'qty' => $_row['qty'],
							'price' => $_row['price']
						));
					}
				}
				break;
		}
		
		return $_arrReturn;
	}

	function _get_subTableScreenWeave($type_id, $quotation_detail_rowid) {
		$_arrReturn = array();
		$_type_id = (is_numeric($type_id)) ? (int) $type_id : -1;
		$_rowid = (is_numeric($quotation_detail_rowid)) ? (int) $quotation_detail_rowid : -1;
		$_sql = <<<SCRN
SELECT t.order_screen_rowid, COALESCE(m.name, '') AS order_screen, t.position, t.detail, t.size, t.job_hist
, TO_CHAR(COALESCE(t.price, 0), '9G999G990D00') AS price 
FROM (
	SELECT ((s._jsSC)->>'order_screen_rowid')::INT AS order_screen_rowid, ((s._jsSC)->>'position')::TEXT AS position
	, ((s._jsSC)->>'detail')::TEXT AS detail, ((s._jsSC)->>'size')::TEXT AS size, ((s._jsSC)->>'job_hist')::TEXT AS job_hist
	, ((s._jsSC)->>'price')::NUMERIC(14, 5) AS price, ((s._jsSC)->>'seq')::INT AS seq
	FROM (
		SELECT JSON_ARRAY_ELEMENTS((json_others->>'screen')::JSON)::JSON AS _jsSC FROM pm_t_quotation_detail WHERE rowid = ?
	) s
) t
	LEFT OUTER JOIN pm_m_order_screen m ON t.order_screen_rowid = m.rowid 
ORDER BY t.seq
;
SCRN;
		$_arrReturn = $this->arr_execute($_sql, array($_rowid));
		$_strError = $this->error_message;
		if ($_strError != '') throw new MyException('Error getting screen/weave data:: ' . $_strError);
		if (! is_array($_arrReturn)) $_arrReturn = array();
		
		return $_arrReturn;
	}

	function _get_premadeSubTableDetails($type_id, $quotation_detail_rowid) {
		$_arrReturn = array();
		$_type_id = (is_numeric($type_id)) ? (int) $type_id : -1;
		$_rowid = (is_numeric($quotation_detail_rowid)) ? (int) $quotation_detail_rowid : -1;
		$_sizeCondition = '';
		$_patternTable = '';
		switch ($_type_id) {
			case 3:
				$_patternTable = 'pm_t_polo_pattern';
				$_sizeCondition = ' AND is_polo > 0';
				break;
			case 4:
				$_patternTable = 'pm_t_tshirt_pattern';
				$_sizeCondition = ' AND is_tshirt > 0';
				break;
			case 8:
				$_patternTable = 't_jacket_pattern';
				$_sizeCondition = ' AND is_jacket > 0';
				break;
			case 12:
			case 14:
				$_patternTable = 'm_other_premade_pattern';
				$_sizeCondition = ' ';
				break;
		}
		
		switch ($_type_id) {
			case 3:
			case 4:
			case 8:
			case 12:
			case 14:
				$_sql = <<<TMPL
	SELECT s.sort_index, s.rowid AS order_size_rowid, sc.rowid AS sub_cat_rowid, sc.name AS type, s.size_text AS size, s.size_chest AS chest
	, CASE WHEN s.is_use > 0 THEN 0 ELSE 1 END AS is_expired
	FROM pm_m_premade_order_size s
		INNER JOIN pm_m_order_size_sub_cat sc ON s.sub_cat_rowid = sc.rowid
		INNER JOIN (
			SELECT sz.main_cat_rowid, sz.sub_cat_rowid --, ((d.ea_size)->>'order_size_rowid')::INT4 AS order_size_rowid 
			FROM (
				SELECT dtl.indx, dtl.pattern_rowid, dtl.color, dtl.price
				, JSON_ARRAY_ELEMENTS(dtl.order_size) AS ea_size
				FROM fnc_get_premade_order_detail_from_qod(?) dtl
			) d
				INNER JOIN pm_m_premade_order_size sz ON sz.rowid = ((d.ea_size)->>'order_size_rowid')::INT4
			GROUP BY sz.main_cat_rowid, sz.sub_cat_rowid
		) ft ON ft.sub_cat_rowid = s.sub_cat_rowid
	WHERE TRUE {$_sizeCondition}
	ORDER BY s.main_cat_rowid, s.sub_cat_rowid, s.sort_index
TMPL;
				$_arr = $this->arr_execute($_sql, array($_rowid));

				$_arrIndex = array();
				if ($_arr !== FALSE) {
					foreach ($_arr as $_row) {
						if (! array_key_exists($_row['sub_cat_rowid'], $_arrIndex)) {
							$_arrIndex[$_row['sub_cat_rowid']] = array("sub_category" => $_row["type"], "size" => array());
						}
						if (! array_key_exists($_row['order_size_rowid'], $_arrIndex[$_row['sub_cat_rowid']]["size"])) {
							$_arrIndex[$_row['sub_cat_rowid']]["size"][$_row['order_size_rowid']] = array(
								"rowid" => $_row['order_size_rowid']
								, "text" => $_row["size"]
								, "chest" => $_row["chest"]
								, "is_expired" => $_row["is_expired"]
							);
						}
					}
				}
				$_arrReturn['template'] = $_arrIndex;
				
				$_sql = <<<EDT
SELECT dtl.pattern_rowid, p.code, dtl.color, dtl.price AS price, dtl.size
FROM (
	SELECT _dtl.indx, _dtl.pattern_rowid, _dtl.color, _dtl.price
	, JSON_AGG((
		SELECT ROW_TO_JSON(x) FROM ( 
			SELECT _dtl.order_size_rowid, COALESCE(_dtl.qty, 0) AS qty
		) x
	)) AS size
	FROM (
		SELECT d.indx, d.pattern_rowid, d.color, d.price
		, ((d.ea_size)->>'order_size_rowid')::INT4 AS order_size_rowid 
		, ((d.ea_size)->>'qty')::INT4 AS qty 
		FROM (
			SELECT dtl.indx, dtl.pattern_rowid, dtl.color, dtl.price
			, JSON_ARRAY_ELEMENTS(dtl.order_size) AS ea_size
			FROM fnc_get_premade_order_detail_from_qod(?) dtl
		) d
	) _dtl
	GROUP BY _dtl.indx, _dtl.pattern_rowid, _dtl.color, _dtl.price
) dtl
	INNER JOIN {$_patternTable} p ON dtl.pattern_rowid = p.rowid
EDT;
				$_arrReturn['data'] = $this->arr_execute($_sql, array($_rowid));
				$_strError = $this->error_message;
				if ($_strError != '') throw new MyException('Error getting premade details data:: ' . $_strError);
				if (! is_array($_arrReturn)) $_arrReturn = array();
				break;
			case 7:
				$_sql = <<<EDT
SELECT dtl.pattern_rowid, p.code, dtl.color, dtl.price AS price, dtl.sum_qty
FROM (
	SELECT _dtl.indx, _dtl.pattern_rowid, _dtl.color, _dtl.price
	, SUM(_dtl.qty) as sum_qty
	FROM (
		SELECT d.indx, d.pattern_rowid, d.color, d.price
		, ((d.ea_size)->>'order_size_rowid')::INT4 AS order_size_rowid 
		, ((d.ea_size)->>'qty')::INT4 AS qty 
		FROM (
			SELECT dtl.indx, dtl.pattern_rowid, dtl.color, dtl.price
			, JSON_ARRAY_ELEMENTS(dtl.order_size) AS ea_size
			FROM fnc_get_premade_order_detail_from_qod(?) dtl
		) d
	) _dtl
	GROUP BY _dtl.indx, _dtl.pattern_rowid, _dtl.color, _dtl.price
) dtl
	INNER JOIN t_cap_pattern p ON dtl.pattern_rowid = p.rowid
EDT;
				$_arr1 = $this->arr_execute($_sql, array($_rowid));
				$_strError = $this->error_message;
				if ($_strError != '') throw new MyException('Error getting premade details data:: ' . $_strError);
				$_arrDetails = array();
				if (is_array($_arr1)) {
					foreach ($_arr1 as $_row) {
						$_arrDetails[(string)$_row['pattern_rowid']] = $_row;
					}
				}
				$_arrReturn = $_arrDetails;
				if (! is_array($_arrReturn)) $_arrReturn = array();
				break;
		}

		return $_arrReturn;
	}

	function _get_QT_Detail($job_number) {
		$_sql = "
		select ptqd.json_details from v_order_report vor
		left join pm_t_quotation_detail ptqd on vor.quotation_detail_rowid = ptqd.rowid 
		where vor.job_number = '" .$job_number."'";
		return $this->arr_execute($_sql);
	}
}