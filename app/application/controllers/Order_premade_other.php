<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_premade_other extends MY_Ctrl_crud {
	function __construct() {
		parent::__construct();
		$this->modelName = 'Mdl_order_premade_other';
		$this->_CUSTOMER_ROWID = -1;
		$this->_START_SCRIPT = '';
	}
	
	public function pass_command($action = 0, $rowid = 0, $job_number = '', $customer_rowid = 0) {
		if (is_numeric($customer_rowid) && ($customer_rowid > 0)) $this->_CUSTOMER_ROWID = $customer_rowid;
		if (is_numeric($rowid) && ($rowid > 0)) {
			switch ($action) {
				case 1:
				case '1': //view
					$this->_START_SCRIPT .= "$(function() {_doExtCmdView(" . $rowid . ", '" . $job_number . "', " . $this->_CUSTOMER_ROWID . ");});\n";
					break;
				case 2:
				case '2': //edit
					$this->_START_SCRIPT .= "$(function() {_doExtCmdEdit(" . $rowid . ", '" . $job_number . "', " . $this->_CUSTOMER_ROWID . ");});\n";
					break;
				case 3:
				case '3': //clone
					$this->_START_SCRIPT .= "$(function() {_doExtCmdClone(" . $rowid . ", '" . $job_number . "', " . $this->_CUSTOMER_ROWID . ");});\n";
					break;
			}
		}
		$this->index();
	}
	
	public function index($customer_rowid = -1) {
		if (is_numeric($customer_rowid) && ($customer_rowid > 0)) $this->_CUSTOMER_ROWID = $customer_rowid;

		$this->load->model('Mdl_customer', 'c');
		$_strStartScript = '';
		if ($this->_START_SCRIPT != '') {
			$_strStartScript = $this->_START_SCRIPT;
		} else if ($this->_CUSTOMER_ROWID > 0) {
			$_arr = $this->c->get_by_id($this->_CUSTOMER_ROWID);
			if (is_array($_arr) && isset($_arr['display_name'])) {
				$_strStartScript = "$(function() {_doOrderNew(" . $this->_CUSTOMER_ROWID . ", '" . $_arr['display_name'] . "');});\n";
			}
		}
		$this->add_css(array(
			'public/css/jquery/ui/1.11.4/cupertino/jquery-ui.min.css',
			'public/css/jquery/dataTable/1.10.11/dataTables.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/buttons-1.1.2/buttons.jqueryui.min.css'
			, 'public/css/quotation/form.css'
		));
		$this->add_js(array(
			'public/js/jquery/1.11.0/jquery.js',
			'public/js/jquery/ui/1.10.4/jquery-ui.min.js',
			'public/js/jquery/ui/1.10.3/jquery-ui-autocomplete-combobox.js',
			'public/js/jquery/dataTable/1.10.11/jquery.dataTables.min.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/dataTables.buttons.min.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.jqueryui.min.js',
			'public/js/jquery/dataTable/extensions/jszip-2.5.0/jszip.min.js',
			'public/js/jquery/dataTable/extensions/pdfmake-0.1.18/pdfmake.min.js',
			'public/js/jquery/dataTable/extensions/pdfmake-0.1.18/vfs_fonts.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.html5.min.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.print.min.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.colVis.min.js',
			'public/js/jquery/fileupload/load-image.min.js',
			'public/js/jquery/fileupload/canvas-to-blob.min.js',
			'public/js/jquery/fileupload/jquery.iframe-transport.js',
			'public/js/jquery/fileupload/jquery.fileupload.js',
			'public/js/jquery/fileupload/jquery.fileupload-process.js',
			'public/js/jquery/fileupload/jquery.fileupload-image.js',
			'public/js/jquery/fileupload/jquery.form.js',
			'public/js/jsGlobal.js',
			'public/js/jsUtilities.js',
			'public/js/jsGlobalConstants.js'
		));
		$this->_selOptions = $this->_prepareSelectOptions(array(
			'supplier'=>array('table_name'=>'m_order_supplier','where'=>array('is_cancel'=>0),'no_feed_row'=>TRUE,'order_by'=>'sort_index')
		));
		
		$this->_setController("rowid", "", array('type'=>'hdn'));	
		//-- this one useualy been set in 	_prepareControlsDefault but here we remove those function to save unnecessary procedure
		$this->_setController("job_number", "เลขที่งาน", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>0));
		$this->_setController("product_type", "ประเภท", NULL, array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>1));
		$this->_setController("customer", "ลูกค้า", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>2));
		$this->_setController("disp_order_date", "วันที่สั่งงาน", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>3));
		$this->_setController("disp_due_date", "กำหนดส่ง", array(), array("selectable"=>TRUE,"class"=>"center","order"=>4));
		$this->_setController("disp_deliver_date", "วันที่ส่งลูกค้า", array(), array("selectable"=>TRUE,"class"=>"center","default"=>TRUE,"order"=>5));
		$this->_setController("disp_vat_type", "VAT", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>6));
		$this->_setController("total_price_sum", "ยอดรวม(บาท)", NULL, array("selectable"=>TRUE,"default"=>TRUE,"class"=>"default_number","order"=>7));
		$this->_setController("deposit_payment", "", NULL);
		$this->_setController("arr_deposit_log", "", NULL);
		$this->_setController("close_payment", "", NULL);
		$this->_setController("arr_payment_log", "", NULL);
		$this->_setController("avail_process_status", "", NULL);
		$this->_setController("product_type_rowid", "", NULL);
		$this->_setController("prod_screen_count", "", NULL);
		$this->_setController("prod_weave_count", "", NULL);
		//-- set special attributes	

		$_screen_status = $this->mt->list_where('manu_screen_status', 'is_cancel=0', NULL, 'm_');
		$this->add_js("var _ARR_SCREEN_STATUS = " . json_encode($_screen_status) . ";", 'custom');
		$_weave_status = $this->mt->list_where('manu_weave_status', 'is_cancel=0', NULL, 'm_');
		$this->add_js("var _ARR_WEAVE_STATUS = " . json_encode($_weave_status) . ";", 'custom');
		
		$pass['left_panel'] = $this->__getLeftPanel();
	
		$_custom_columns = array();
		//"column" => '{"sTitle":"ขำระมัดจำ", "sClass":"cls-payment-dlg right","sWidth":"80px","mData":"rowid","mRender": function(data,type,full) { return \'<span class="cls-spn-payment">\' + formatNumber(full.total_deposit_payment) + \'</span><img class="tblButton" command="cmd_open_deposit_dialog" src="public/images/b_view.png" title="รายการชำระเงินมัดจำ" />\';}, "bSortable": true}'
		$_custom_columns[] = array(
				"column" => '{"sTitle":"ขำระมัดจำ", "sClass":"cls-payment-dlg right","sWidth":"80px","mData":"rowid","mRender": function(data,type,full) { return \'<span class="cls-spn-payment">\' + formatNumber(full.deposit_payment) + \'</span>\';}, "bSortable": true}'
				, "order" => 8
			);
		$this->_setController("left_amount", "คงเหลือ(บาท)", NULL, array("selectable"=>TRUE,"default"=>TRUE,"class"=>"default_number","order"=>9));
		$this->_setController("process_status", "สถานะ", NULL, array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>12));
		
		if ($this->_blnCheckRight('edit')) $_custom_columns[] = array(
				"column" => '{"sTitle":"แก้ไขสถานะ", "sClass": "center","mData":"rowid","mRender": function(data,type,full) { return fnc__DDT_Row_RenderOP(data, type, full); } ,"bSortable": false}' 
				, "order" => 14
			);
			if ($this->_blnCheckRight('export_pdf')) $_custom_columns[] = array(
				"column" => '{"sTitle":"ใบงาน", "sClass": "center", "mData": "rowid","mRender": function(data,type,full) { return \'<img class="tblButton" command="pdf_1" src="./public/images/pdf_icon_40.png" title="ใบสั่งซื้อ \' + full[\'product_type\'] + \'สำเร็จรูป" /><img class="tblButton" command="pdf_6" src="./public/images/pdf_icon_40.png" title="ใบงานข้อมูล \' + full[\'product_type\'] + \'สำเร็จรูป" />\';}, "bSortable": false}'
				, "order" => 15
			);

		$_custom_columns[] = array(
			"column" => '{"sTitle":"#", "sClass": "center","mData":"rowid","mRender": function(data,type,full) { return fnc__DDT_Row_RenderNotification(data, type, full); } ,"bSortable": false}'
				, "order" => 16
		);
		
		$this->load->model($this->modelName, 'm_model');
		$this->load->helper('order_detail_helper');
		$_arrDtlTabs = hlpr_get_OrderOther_ViewParams();
		
		$_arrJobNumber = $this->m_model->list_job_number();
		if (is_array($_arrJobNumber)) {
			array_unshift($_arrJobNumber, array('rowid'=>'', 'job_number'=>''));
		} else {
			$_arrJobNumber = array();
		}
		$pass['work_panel'] = $this->add_view('_public/_list', array(
				'custom_columns' => $_custom_columns
				, 'dataview_fields' => $this->_arrDataViewFields
				, 'list_editable' => FALSE
				, 'list_deleteable' => FALSE
				, 'edit_template' => $this->load->view('order/form_other', array(
					"index" => 2
					, "crud_controller" => "Order_premade_other"
					, "job_number_list" => $_arrJobNumber
					, "supplier_list" => $this->_selOptions['supplier']
					, "detail_panel" => $_arrDtlTabs["detail_panel"]
					, "others_panel" => $_arrDtlTabs["others_panel"]
				), TRUE)
			), TRUE);

		$this->add_css('public/css/order/form.css');
		$this->add_js('public/js/order/_base_order.js');
		$this->add_js('public/js/order/form.js');
		$pass['title'] = "สั่งตัดสำเร็จรูป:: อื่นๆ";
		
		if ($_strStartScript != '') $this->add_js($_strStartScript, 'custom');

		$this->add_view_with_script_header('_public/_template_main', $pass);
	}

	function __getLeftPanel() {
		//$_arrCompanySearch = $this->c->list_select_company();
		//if (is_array($_arrCompanySearch)) array_unshift($_arrCompanySearch, array('rowid'=>'', 'company'=>''));
		$_to = new DateTime();
		$_frm = date_sub(new DateTime(), new DateInterval('P3D'));
		
		return $this->add_view('_public/_search_panel', array(
			'controls' => array(
				array(
					"type" => "txt",
					"label" => "เลขที่ใบงาน",
					"name" => "job_number"
				),
				array(
					"type" => "aac"
					, "label" => "ลูกค้า"
					, "name" => "customer_rowid"
					, "url" => "./customer/json_search_acc"
					, "min_length" => 3
					, "sel_val" => "rowid"
					, "sel_text" => "display_name_company"
					, "on_select" => <<<OSL
			var _aac_text = '';
			if (ui.item) {
				_aac_text = ui.item.value || '';
				_aac_text = _aac_text.toString().trim();
			}
			if (_aac_text != '') {
				var _match = /\[(.+)\]/.exec(_aac_text);
				if ((_match) && (_match.length > 1)) {
					setValue($('#txt-company', $(this).parents('form').get(0)), _match[1]);
					ui.item.value = _aac_text.substring(0, (_aac_text.length - _match[1].length - 3));
				}
			}
OSL
				),
				array(
					"type" => "txt"
					, "label" => "บริษัท"
					, "name" => "company"
				),
				array(
					"type" => "dpk",
					"label" => "จากวันที่",
					"name" => "date_from"
					//,"value" => $_frm->format('d/m/Y')
				),
				array(
					"type" => "dpk",
					"label" => "ถึงวันที่",
					"name" => "date_to",
					"value" => $_to->format('d/m/Y')
				),
				array(
					"type" => "chk",
					"label" => "แสดงเฉพาะ active",
					"name" => "is_active_status",
					"value" => TRUE
				),
				array(
					"type" => "info",
					"value" => "&nbsp;"
				),
				array(
					"type" => "info",
					"value" => "* จำกัดจำนวนแสดงผลไว้ที่ 3,000 เพื่อประสิทธิภาพในการทำงานของโปรแกรม"
				)
			),
			'layout' => array(),
			'search_onload' => (($this->_CUSTOMER_ROWID <= 0) && ($this->_START_SCRIPT == ''))
		), TRUE);
	}
	
	function get_order_by_id() {
		$_blnSuccess = FALSE;
		$_strError = '';
		$_arrReturn = array();
		$_rowid = $this->input->post('rowid');
		if (($_rowid != FALSE) && $_rowid > '0') {
			$_whileChecker = TRUE;
			while ($_whileChecker) {
				$this->load->model($this->modelName, 'm');
				$_arrReturn = $this->m->get_detail_by_id($_rowid);				
				$_strError = $this->m->error_message;
				if ($_strError != '') {
					$_whileChecker = FALSE;
					continue;
				}
				// ++ custom size quantity
				$_sql = <<<SQL
SELECT COALESCE(main_cat_rowid, 0) AS main_cat_rowid, COALESCE(sub_cat_rowid, 0) AS sub_cat_rowid
, size_text, size_chest, qty, TO_CHAR(COALESCE(price, 0), '9G999G990D00') AS price 
FROM t_order_size_other 
WHERE order_rowid = $_rowid 
ORDER BY seq, sub_cat_rowid

SQL;
				$_arr2 = $this->m->arr_execute($_sql);
				$_strError = $this->m->error_message;
				if ($_strError != '') {
					$_whileChecker = FALSE;
					continue;
				}
				if (is_array($_arr2)) {
					$_inx = 0;
					$_int_curr = 0;
					foreach ($_arr2 as $_row) {
						if ($_int_curr != $_row['sub_cat_rowid']) {
							$_int_curr = $_row['sub_cat_rowid'];
							$_inx = 1;
						} else {
							$_inx += 1;
						}
						$_ref = $_row['main_cat_rowid'] . '_' . $_row['sub_cat_rowid'];
						$_arrReturn['sq_' . $_ref . '_text' . $_inx] = $_row['size_text'];
						$_arrReturn['sq_' . $_ref . '_chest' . $_inx] = $_row['size_chest'];
						$_arrReturn['sq_' . $_ref . '_qty' . $_inx] = $_row['qty'];
						$_arrReturn['sp_' . $_ref . '_price' . $_inx] = $_row['price'];
					}
				}
				// -- custom size quantity
				// -- size quantity

				// ++ others price
				$_sql = <<<OTP
SELECT t.detail, TO_CHAR(COALESCE(t.price, 0), '9G999G990D00') AS price 
FROM t_order_price_other t 
WHERE t.order_rowid = $_rowid 
ORDER BY t.seq, t.price 
OTP;
				$_arr3 = $this->m->arr_execute($_sql);
				$_strError = $this->m->error_message;
				if ($_strError != '') {
					$_whileChecker = FALSE;
					continue;
				}
				if ($_arr3 == FALSE) $_arr3 = array();
				$_arrReturn['others_price'] = $_arr3;
				$_blnSuccess = TRUE;
				$_whileChecker = FALSE;
				// -- others price
				// ++ screen
				$_userid = $this->session->userdata('user_id');
				$_sql =<<<QUERY
				SELECT  d.position, d.detail, d.job_hist, CONCAT('กว้าง ' ,tmp.width, ' | ', 'สูง ' ,tmp.height ) as size, s.name AS disp_type, ss.name as disp_status, s.screen_type, tmp.img, tmp.rowid as prod_rowid,
				tmp.fabric_date , tmp.block_date, tmp.block_emp, tmp.approve_date, tmp.prod_status, tmp.status_remark
					, ARRAY_TO_JSON(ARRAY(
				SELECT UNNEST(fnc_manu_screen_avai_status(tmp.prod_status))
				INTERSECT
				SELECT UNNEST(uac.arr_avail_status)
				)) AS arr_avail_status
				FROM v_order_report vo
				INNER JOIN fnc_listmanuscreen_accright_byuser(984) uac ON True
				INNER JOIN (
					SELECT o.product_type_rowid AS type_id, s.order_rowid, s.order_screen_rowid, s.position, s.detail, s.size, s.job_hist, s.price, s.seq
					FROM t_order_premade_other o
					INNER JOIN t_order_premade_screen_other s ON s.order_rowid = o.rowid
				) d
				ON d.type_id = vo.type_id
				AND d.order_rowid = vo.order_rowid
				INNER JOIN pm_m_order_screen s on s.rowid = d.order_screen_rowid
				LEFT JOIN pm_t_manu_screen_production tmp on tmp.order_screen_rowid = d.order_screen_rowid and tmp.order_rowid = d.order_rowid and tmp.seq = d.seq
				LEFT JOIN m_manu_screen_status ss ON ss.rowid = tmp.prod_status
				LEFT join m_manu_screen_type mst on mst.rowid = tmp.screen_type
				--WHERE o.ps_rowid = 10
				WHERE COALESCE(vo.is_cancel, 0) < 1
				AND s.screen_type  = 2
				AND d.order_rowid = $_rowid
QUERY;
				$_arr4 = $this->m->arr_execute($_sql);
				$_strError = $this->m->error_message;
				if ($_strError != '') {
					$_whileChecker = FALSE;
					continue;
				}
				if ($_arr4 == FALSE) $_arr4 = array();
				$_arrReturn['screen_order'] = $_arr4;
				

				// ++ weave
				$_sql =<<<QUERY
				SELECT  d.position, d.detail, d.job_hist, CONCAT('กว้าง ' ,tmp.width, ' | ', 'สูง ' ,tmp.height ) as size, s.name AS disp_type, ss.name as disp_status, s.screen_type, tmp.img, tmp.rowid as prod_rowid,
				tmp.fabric_date , tmp.block_date, tmp.block_emp, tmp.approve_date, tmp.prod_status, tmp.status_remark
					, ARRAY_TO_JSON(ARRAY(
				SELECT UNNEST(fnc_manu_screen_avai_status(tmp.prod_status))
				INTERSECT
				SELECT UNNEST(uac.arr_avail_status)
				)) AS arr_avail_status
				FROM v_order_report vo
				INNER JOIN fnc_listmanuscreen_accright_byuser(984) uac ON True
				INNER JOIN (
					SELECT o.product_type_rowid AS type_id, s.order_rowid, s.order_screen_rowid, s.position, s.detail, s.size, s.job_hist, s.price, s.seq
					FROM t_order_premade_other o
					INNER JOIN t_order_premade_screen_other s ON s.order_rowid = o.rowid
				) d
				ON d.type_id = vo.type_id
				AND d.order_rowid = vo.order_rowid
				INNER JOIN pm_m_order_screen s on s.rowid = d.order_screen_rowid
				LEFT JOIN pm_t_manu_screen_production tmp on tmp.order_screen_rowid = d.order_screen_rowid and tmp.order_rowid = d.order_rowid and tmp.seq = d.seq
				LEFT JOIN m_manu_screen_status ss ON ss.rowid = tmp.prod_status
				LEFT join m_manu_screen_type mst on mst.rowid = tmp.screen_type
				--WHERE o.ps_rowid = 10
				WHERE COALESCE(vo.is_cancel, 0) < 1
				AND s.screen_type  = 1
				AND d.order_rowid = $_rowid
QUERY;
				$_arr5 = $this->m->arr_execute($_sql);
				$_strError = $this->m->error_message;
				if ($_strError != '') {
					$_whileChecker = FALSE;
					continue;
				}
				if ($_arr5 == FALSE) $_arr5 = array();
				$_arrReturn['weave_order'] = $_arr5;


				$_whileChecker = FALSE;
				// ++ screen	
			}
		}
		if ($_arrReturn !== FALSE) $_blnSuccess = TRUE;
		// print_r($_arrReturn);exit;
		$json = json_encode(
			array(
				'success' => $_blnSuccess,
				'error' => $_strError,
				'data' => $_arrReturn
			)
		);
		header('content-type: application/json; charset=utf-8');
		echo isset($_GET['callback'])? "{" . $_GET['callback']. "}(".$json.")": $json;
	}
	
	function get_pdf($pdf_index, $rowid) {
		$this->load->model($this->modelName, 'm');
		$pass['data'] = $this->m->get_detail_report($rowid);
		if ($pass['data'] == FALSE) {
			echo "Error get report data: " . $this->m->error_message;
			return;
		} else {
			mb_internal_encoding("UTF-8");
			$this->load->helper('exp_pdf_helper');
			$this->load->helper('upload_helper');
			$this->load->library('mpdf8');
			
			$file_name = '';
			$html = '';
			$_prodType = $pass['data']['type'] . $pass['data']['category'];
			$_rev_no = (isset($pass['data']['quotation_revision'])) ? (int)$pass['data']['quotation_revision'] : 0;
			$now = new DateTime();
			$strNow = $now->format('YmdHis');
			switch ($pdf_index) {
				case "1":
					$file_name = 'FM-SA-03-001_' . $strNow . '.pdf';
					$pass['title'] = 'ใบสั่งซื้อ ' . $_prodType;
					$pass['code'] = sprintf('FM-SA-03-001 REV.%02d', $_rev_no);
					$pass['is_display_price'] = TRUE;
					$pass['others_price_panel'] = $this->load->view('order/pdf/section/_pdf_others_price', $pass, TRUE);
					$pass['detail_section'] = $this->load->view('order/pdf/section/_pdf_premade_order_detail', $pass, TRUE);
					$pass['screen_section'] = $this->load->view('order/pdf/section/_pdf_screen', $pass, TRUE);
					$pass['images_section'] = $this->load->view('order/pdf/section/_pdf_sample_images', $pass, TRUE);
					$html = $this->load->view('order/pdf/premade_pdf', $pass, TRUE);
					break;
				case "6":
					$file_name = 'FM-SA-03-002_' . $strNow . '.pdf';
					$pass['title'] = 'ใบงานข้อมูล ' . $_prodType;
					$pass['code'] = sprintf('FM-SA-03-002 REV.%02d', $_rev_no);
					$pass['is_display_price'] = FALSE;
					$pass['others_price_panel'] = $this->load->view('order/pdf/section/_pdf_others_price', $pass, TRUE);
					$pass['detail_section'] = $this->load->view('order/pdf/section/_pdf_premade_order_detail', $pass, TRUE);
					$pass['screen_section'] = $this->load->view('order/pdf/section/_pdf_screen', $pass, TRUE);
					$pass['images_section'] = $this->load->view('order/pdf/section/_pdf_sample_images', $pass, TRUE);
					$html = $this->load->view('order/pdf/premade_pdf', $pass, TRUE);
					break;
			}
//echo $html;exit;
			$temp_name = 'quotation-no-logo';
			$this->mpdf8->exportMPDF_Template($html, $temp_name, $file_name);
		}
	}

	function change_status_by_id() {
		$blnSuccess = FALSE;
		$strError = '';
		$this->load->model($this->modelName, 'm');
		$json_input_data = json_decode(trim(file_get_contents('php://input')), true); //get json
		$_arrData = (isset($json_input_data))?$json_input_data:$this->input->post(); //or post data submit
		if (isset($_arrData) && ($_arrData != FALSE)) {
			if (! isset($_arrData['rowid'])) $strError .= '"rowid" not found,';
			if (! isset($_arrData['ps_rowid'])) $strError .= '"ps_rowid" not found,';
			$_remark = FALSE;
			if (isset($_arrData['status_remark']) && (!(empty($_arrData['status_remark'])))) $_remark = $_arrData['status_remark'];
			if ($strError == '') {
				$this->m->change_status_by_id($_arrData['rowid'], $_arrData['ps_rowid'], $_remark);
				$strError = $this->m->error_message;
			}
		} else {
			$strError = 'Invalid parameters passed ( None )';
		}
		if ($strError == '') {
			$blnSuccess = TRUE;
		}
		$json = json_encode(
			array(
				'success' => $blnSuccess,
				'error' => $strError
			)
		);
		header('content-type: application/json; charset=utf-8');
		echo isset($_GET['callback'])? "{" . $_GET['callback']. "}(".$json.")":$json;
	}

}

/*
	function commit() {
		$_blnSuccess = FALSE;
		$_strError = '';
		$_strMessage = '';
		$json_input_data = json_decode(trim(file_get_contents('php://input')), true); //get json
		$_arrData = (isset($json_input_data))?$json_input_data:$this->input->post(); //or post data submit
		if (isset($_arrData) && ($_arrData != FALSE)) {
			try {
				$this->load->model($this->modelName, 'm');
				$this->db->trans_begin();

				$_arr = $_arrData;
				if (array_key_exists('job_number', $_arr)) {
					$this->load->model('Mdl_master_table', '_mas');
					$_exists = $this->_mas->int_exists_job_number($_arr['job_number'], 6, (isset($_arr['rowid']) ? $_arr['rowid'] : -1));
					if ($_exists > 0) {
						throw new Exception('Duplicate "job_number" ( ' . $_arr['job_number'] . ' ) violation.'); 
					}
				}
				if (array_key_exists('order_date', $_arr)) $_arr['order_date'] = $this->m->_strConvertDisplayDateFormat($this->m->_datFromPost($_arr['order_date']));
				if (array_key_exists('due_date', $_arr)) $_arr['due_date'] = $this->m->_strConvertDisplayDateFormat($this->m->_datFromPost($_arr['due_date']));
				if (array_key_exists('deliver_date', $_arr)) $_arr['deliver_date'] = $this->m->_strConvertDisplayDateFormat($this->m->_datFromPost($_arr['deliver_date']));

				//++ Manage upload files
				for ($_i=1;$_i<10;$_i++) {
					$_key = 'file_image' . $_i;
					$_oldKey = 'old_file_image' . $_i;
					if (array_key_exists($_key, $_arr) && ($_arr[$_key] != '')) {
						$_oldFile = FCPATH . 'uploads/temp/' . $_arr[$_key];
						//$_now = new DateTime();
						$_ext = pathinfo($_oldFile, PATHINFO_EXTENSION);
						$_newFileName = gmdate('YmdHis') . '-' . $_i . '-pl.' . $_ext; // $_now->format('YmdHis') . '-1-pl.' . $_ext;
						$_newFile = FCPATH . 'uploads/' . $_newFileName;
						if (file_exists($_oldFile)) {
							if (rename($_oldFile, $_newFile)) {
								$_arr[$_key] = $_newFileName;
							} else {
								$_arr[$_key] = '';
							}
						}
					}
					//++ Manage old image files
					if (array_key_exists($_oldKey, $_arr) && ($_arr[$_oldKey] != '') && ($_arr[$_oldKey] != $_arr[$_key])) {
						$_to_delete = FCPATH . 'uploads/' . trim($_arr[$_oldKey]);
						if (file_exists($_to_delete)) {
							unlink($_to_delete);
						}
					}
				}
				//-- Manage upload files
				
				$_aff_rows = $this->m->commit($_arr);
				$_strError = $this->m->error_message;
				$_is_insert = FALSE;
				while ($_strError == '') {
					$_rowid = 0;
					if (array_key_exists('rowid', $_arr) && (trim($_arr['rowid']) > '0')) {
						$_rowid = $_arr['rowid'];
					} else {
						$_is_insert = TRUE;
						$_rowid = $this->m->last_insert_id;
					}
					if ($_rowid <= 0) {
						$_strError = 'Invalid rowid';
						break;
					}
					
					// ++ order add option :: new requirements 201708
					$_aopRowid = -1;
					$_rslt = $this->db->query('SELECT rowid FROM t_order_add_option WHERE order_type_id = 6 AND order_rowid = ?', array($_rowid));
					if ($_rslt->num_rows > 0) {
						$_aopRowid = $_rslt->first_row()->rowid;
					}
					$this->load->model('Mdl_order_add_option', 'aopt');
					// ++ order detail
					$this->load->model('Mdl_order_detail_jacket', 'md');
					foreach ($_arr as $key=>$value) {
						if (strpos($key, 'option_') === 0) {
							$_key = substr($key, 7);
							if (array_key_exists($_key, $this->aopt->_FIELDS)) {
								$this->aopt->_FIELDS[$_key] = $value;
							} else {
								if (($_key == 'is_pakaging_tpb') && ($value > 0)) {
									$this->aopt->_FIELDS['pakaging_type_rowid'] = 1;
									$this->aopt->_FIELDS['pakaging_method_rowid'] = 1;
								} else if (($_key == 'is_no_packaging_sep_tpb') && ($value > 0)) {
									$this->aopt->_FIELDS['pakaging_type_rowid'] = 1;
									$this->aopt->_FIELDS['pakaging_method_rowid'] = 2;
								}
							}
						} else {
							if (strpos($key, 'remark') === 0) continue;
							if (strpos($key, 'detail_remark') === 0) $key = substr($key, -7);
							if (array_key_exists($key, $this->md->_FIELDS)) {
								$this->md->_FIELDS[$key] = $value;
							}
						}
					}
					$this->md->_FIELDS['order_rowid'] = $_rowid;
					if ($_is_insert) {
						$this->md->insert();
					} else {
						$this->md->update(array('order_rowid'=>$_rowid));
					}
					if ($this->md->error_message !== "") {
						$_strError = $this->md->error_message;
						break;
					}
					// -- order detail
					
					$this->aopt->_FIELDS['order_type_id'] = 6; //jacket type
					$this->aopt->_FIELDS['order_rowid'] = $_rowid;
					if ($_aopRowid > 0) {
						$this->aopt->update(array('rowid'=>$_aopRowid));
					} else {
						$this->aopt->insert();
					}
					if ($this->aopt->error_message !== "") {
						$_strError = $this->aopt->error_message;
						break;
					}
					// -- order add option :: new requirements 201708

					// ++ Size quantity
					$this->db->delete('t_order_size_jacket', array('order_rowid'=>$_rowid));
					$_bulk = array();
					if (array_key_exists('size', $_arr)) {
						if (is_array($_arr['size'])) {
							foreach ($_arr['size'] as $_order_size_rowid=>$_obj) {
								if (array_key_exists('qty', $_obj) && array_key_exists('price', $_obj)) {
									if ((intval($_order_size_rowid) > 0) && (intval($_obj['qty']) > 0) && (floatval($_obj['price']) > 0)) {
										array_push($_bulk, array(
											'order_rowid' => $_rowid
											, 'order_size_rowid' => intval($_order_size_rowid)
											, 'qty' => intval($_obj['qty'])
											, 'price' => floatval($_obj['price'])
											, "seq" => ((isset($_obj['seq']) && (intval($_obj['seq']) > 0)) ? intval($_obj['seq']) : NULL)
										));
									}
								}
							}
							if (count($_bulk) > 0) $this->db->insert_batch('t_order_size_jacket', $_bulk);
						}
					}
					if ($this->db->_error_message() !== "") {
						$_strError .= ", " . $this->db->_error_message();
						break;
					}

					// ++ Custom size quantity
					$this->db->delete('t_order_size_custom_jacket', array('order_rowid'=>$_rowid));
					$_bulk = array();
					if (array_key_exists('size_custom', $_arr)) {
						if (is_array($_arr['size_custom'])) {
							foreach ($_arr['size_custom'] as $_cat_rowid => $_cat_arr) {
								if (! is_array($_cat_arr)) continue;
								foreach ($_cat_arr as $_sub_rowid => $_sub_arr) {
									if (! is_array($_sub_arr)) continue;
									foreach ($_sub_arr as $_cus_index => $_cus_obj) {
										$_cus_text = '';
										$_cus_chest = 0;
										$_cus_qty = 0;
										$_cus_price = 0;
										if (array_key_exists('qty', $_cus_obj) && (intval($_cus_obj['qty']))) {
											$_cus_qty = intval($_cus_obj['qty']);
											if (array_key_exists('text', $_cus_obj)) $_cus_text = $_cus_obj['text'];
											if (array_key_exists('chest', $_cus_obj)) $_cus_chest = intval($_cus_obj['chest']);
											if (array_key_exists('price', $_cus_obj)) $_cus_price = floatval($_cus_obj['price']);
											array_push($_bulk, array(
												'order_rowid' => $_rowid
												, 'main_cat_rowid' => $_cat_rowid
												, 'sub_cat_rowid' => $_sub_rowid
												, 'size_text' => $_cus_text
												, 'size_chest' => $_cus_chest
												, 'qty' => $_cus_qty
												, 'price' => $_cus_price
												, "seq" => ($_cus_index || NULL)
											));
										}
									}
								}
							}
							if (count($_bulk) > 0) {
								$this->db->insert_batch('t_order_size_custom_jacket', $_bulk);
							}
						}
					}
					if ($this->db->_error_message() !== "") {
						$_strError = $this->db->_error_message();
						break;
					}
					// -- Custom size quantity
					// -- Size quantity

					// ++ Others price
					$this->db->delete('t_order_price_jacket', array('order_rowid'=>$_rowid));
					$_bulk = array();
					if (array_key_exists('others_price', $_arr)) {
						if (is_array($_arr['others_price'])) {
							foreach ($_arr['others_price'] as $_obj) {
								array_push($_bulk, array(
										'order_rowid'=>$_rowid
										, 'detail'=>$_obj['detail']
										, 'price'=>$_obj['price']
										, "seq" => ((isset($_obj['seq']) && (intval($_obj['seq']) > 0)) ? intval($_obj['seq']) : NULL)
									));
							}
							if (count($_bulk) > 0) {
								$this->db->insert_batch('t_order_price_jacket', $_bulk);
							}
						}
					}
					if ($this->db->_error_message() !== "") {
						$_strError = $this->db->_error_message();
						break;
					}
					// -- Others price

					// ++ Screen
					$this->db->delete('t_order_screen_jacket', array('order_rowid'=>$_rowid));
					$_bulk = array();
					if (array_key_exists('screen', $_arr)) {
						if (is_array($_arr['screen'])) {
							foreach ($_arr['screen'] as $_obj) {
								if (array_key_exists('order_screen_rowid', $_obj)) {
									array_push($_bulk, array(
										'order_rowid'=>$_rowid
										, 'order_screen_rowid'=>$_obj['order_screen_rowid']
										, 'position'=>$_obj['position']
										, 'detail'=>$_obj['detail']
										, 'size'=>$_obj['size']
										, 'job_hist'=>$_obj['job_hist']
										, 'price'=>$_obj['price']
										, "seq" => ((isset($_obj['seq']) && (intval($_obj['seq']) > 0)) ? intval($_obj['seq']) : NULL)
									));
								}
							}
							if (count($_bulk) > 0) {
								$this->db->insert_batch('t_order_screen_jacket', $_bulk);
							}
						}
					}
					if ($this->db->_error_message() !== "") $_strError = $this->db->_error_message();

					break;
					// -- Screen
				}
			} catch (Exception $e) {
				$_blnSuccess = FALSE;
				$_strError = $e->getMessage();
			}

			if (($this->db->trans_status() === FALSE) || ($_strError != "")) {
				$_strError .= "::DB Transaction rollback";
				$this->db->trans_rollback();
			} else {
				$_blnSuccess = TRUE;
				$_strMessage = $_aff_rows;
				$this->db->trans_complete();			
			}
		}
		$json = json_encode(
			array(
				'success' => $_blnSuccess,
				'error' => $_strError,
				'message' => $_strMessage
			)
		);
		header('content-type: application/json; charset=utf-8');
		echo isset($_GET['callback'])? "{" . $_GET['callback']. "}(".$json.")": $json;
	}
*/
