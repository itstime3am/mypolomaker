<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_cap extends MY_Ctrl_crud {
	function __construct() {
		parent::__construct();
		$this->modelName = 'Mdl_order_cap';
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
		$this->load->model('Mdl_cap_pattern', 'p');
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
			'public/js/jsUtilities.js',
			'public/js/jsGlobalConstants.js'
		));
				
		$this->_selOptions = $this->_prepareSelectOptions(array(
			'standard_pattern'=>array('where'=>array('is_cancel'=>0,'is_cap'=>1),'order_by'=>'sort_index')
			//,'cap_type'=>array('where'=>array('is_cancel'=>0),'order_by'=>'sort_index')
			,'fabric'=>array('table_name'=>'pm_m_cap_fabric_type','where'=>array('is_cancel'=>0),'order_by'=>'sort_index')
			,'front_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND cap_cols LIKE '%,front,%'",'order_by'=>'sort_index')
			,'back_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND cap_cols LIKE '%,back,%'",'order_by'=>'sort_index')
			,'brim_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND cap_cols LIKE '%,brim,%'",'order_by'=>'sort_index')
			,'button_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND cap_cols LIKE '%,button,%'",'order_by'=>'sort_index')
			,'swr_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND cap_cols LIKE '%,swr,%'",'order_by'=>'sort_index')
			,'afh_color'=>array('table_name'=>'m_color','where'=>"is_cancel = 0 AND cap_cols LIKE '%,afh,%'",'order_by'=>'sort_index')
			,'cap_belt_type'=>array('where'=>array('is_cancel'=>0),'order_by'=>'sort_index')
			,'order_screen' => array('where' => array('is_cancel'=>0,'is_cap'=>1),'order_by'=>'sort_index')
			,'supplier'=>array('table_name'=>'m_order_supplier','where'=>array('is_cancel'=>0),'no_feed_row'=>TRUE,'order_by'=>'sort_index')
		));

		$_temp = $this->p->search();
		$this->_selOptions['cap_pattern'] = array(array('rowid'=>'-1', 'code'=>'- กำหนดเอง -'));
		foreach ($_temp as $_row) {
			$_each = array();
			foreach ($_row as $_key => $_value) {
				if (strpos($_key, 'remark') === 0) {
					$_each['detail_' . $_key] = $_value;				
				} else {
					$_each[$_key] = $_value;
				}
			}
			array_push($this->_selOptions['cap_pattern'], $_each);
		}
		
		$_editFormParams['details_panel'] = $this->__getDetailsPanel();
		$_editFormParams['title'] = 'สั่งตัด : หมวก';
		//-- details panel form parts

		//++ others_price panel form parts
		$_editFormParams['others_price_panel'] = $this->add_view('order/_others_price', array(), TRUE);
		//-- size_quan panel form parts

		//++ screen panel form parts
		$_editFormParams['screen_panel'] = $this->add_view('order/_screen', array(
				'order_screen' => $this->_selOptions['order_screen']
				,'arr_position_list' => array('','ด้านหน้าหมวก','ด้านซ้ายหมวก','ด้านขวาหมวก','ด้านหลังหมวก')
			), TRUE);
		//-- screen panel form parts
		
		$_mdl = $this->modelName;
		$this->load->model($_mdl);
		$_arrJobNumber = $this->$_mdl->list_job_number();
		if (is_array($_arrJobNumber)) {
			array_unshift($_arrJobNumber, array('rowid'=>'', 'job_number'=>''));
		} else {
			$_arrJobNumber = array();
		}
		$_editFormParams['job_number_list'] = $_arrJobNumber;
		$_editFormParams['supplier_list'] = $this->_selOptions['supplier'];
		$_editFormParams['pattern_list'] = $this->_selOptions['cap_pattern'];
		
		$this->_setController("rowid", "", array('type'=>'hdn'));
		//-- this one useualy been set in 	_prepareControlsDefault but here we remove those function to save unnecessary procedure
		$this->_setController("job_number", "เลขที่งาน", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>0));
		$this->_setController("customer", "ลูกค้า", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>1));
		$this->_setController("disp_order_date", "วันที่สั่งงาน", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>2));
		$this->_setController("disp_due_date", "กำหนดส่ง", array(), array("selectable"=>TRUE,"class"=>"center","order"=>3));
		$this->_setController("disp_deliver_date", "วันที่ส่งลูกค้า", array(), array("selectable"=>TRUE,"class"=>"center","default"=>TRUE,"order"=>4));
		$this->_setController("disp_vat_type", "VAT", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>5));
		$this->_setController("total_price_sum", "ยอดรวม(บาท)", NULL, array("selectable"=>TRUE,"default"=>TRUE,"class"=>"default_number","order"=>6));
		$this->_setController("deposit_payment", "", NULL);
		$this->_setController("arr_deposit_log", "", NULL);
		$this->_setController("close_payment", "", NULL);
		$this->_setController("arr_payment_log", "", NULL);
		$this->_setController("avail_process_status", "", NULL);
		//-- set special attributes	
		
		$pass['left_panel'] = $this->__getLeftPanel();
		$_editFormParams['index'] = 2;
		$_editFormParams['crud_controller'] = 'order_cap';
		
		$_custom_columns = array();
		$_custom_columns[] = array(
				"column" => '{"sTitle":"ขำระมัดจำ", "sClass":"cls-payment-dlg right","sWidth":"80px","mData":"rowid","mRender": function(data,type,full) { return \'<span class="cls-spn-payment">\' + formatNumber(full.deposit_payment) + \'</span>\';}, "bSortable": true}'
				, "order" => 7
			);
			//<img class="tblButton" command="cmd_open_deposit_dialog" src="public/images/b_view.png" title="รายการชำระเงินมัดจำ" />
		$this->_setController("left_amount", "คงเหลือ(บาท)", NULL, array("selectable"=>TRUE,"default"=>TRUE,"class"=>"default_number","order"=>8));
		/*
		$_custom_columns[] = array(
				"column" => '{"sTitle":"รายการชำระ", "sClass":"cls-payment-dlg right","sWidth":"80px","mData":"rowid","mRender": function(data,type,full) { return \'<span class="cls-spn-payment">\' + formatNumber(full.close_payment) + \'</span><img class="tblButton" command="cmd_open_payment_dialog" src="public/images/forms.png" title="รายการชำระเงิน" />\';}, "bSortable": true}'
				, "order" => 9
			);
		*/
		$this->_setController("process_status", "สถานะ", NULL, array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>12));
		
		if ($this->_blnCheckRight('edit')) $_custom_columns[] = array(
				"column" => '{"sTitle":"แก้ไขสถานะ", "sClass": "center","mData":"rowid","mRender": function(data,type,full) { return fnc__DDT_Row_RenderOP(data, type, full); } ,"bSortable": false}' 
				, "order" => 14
			);
		if ($this->_blnCheckRight('export_pdf')) $_custom_columns[] = array(
				"column" => '{"sTitle":"เอกสาร", "sClass": "center","mData": function() { return \'<img class="tblButton" command="pdf_1" src="./public/images/pdf_icon_40.png" title="ใบสั่งซื้อหมวกสั่งตัด" /><img class="tblButton" command="pdf_6" src="./public/images/pdf_icon_40.png" title="ใบงานข้อมูลหมวกสั่งตัด" />\';}, "bSortable": false}'
				, "order" => 15
			);
		
		$pass['work_panel'] = $this->add_view('_public/_list', array(
				'custom_columns' => $_custom_columns,
				'dataview_fields' => $this->_arrDataViewFields,
				'list_editable' => FALSE,
				'list_deleteable' => FALSE,
				'edit_template' => $this->load->view('order/form_cap', $_editFormParams, TRUE)
			), TRUE);

		$this->add_css('public/css/order/form.css');
		$this->add_js('public/js/order/_base_order.js');
		$this->add_js('public/js/order/form.js');
		$this->add_js('public/js/order/_cap.js');

		$pass['title'] = "สั่งตัดหมวก";
		
		if ($_strStartScript != '') $this->add_js($_strStartScript, 'custom');

		$this->add_view_with_script_header('_public/_template_main', $pass);
	}
	
	function __getDetailsPanel() {
		//++ details panel form parts
		$this->load->helper('crud_controller_helper');
		$_temp = hlpr_prepareControlsDefault('Mdl_order_detail_cap', $this->_selOptions);
		$_details = array();
		foreach ($_temp as $_key => $_obj) {
			if (strpos($_key, 'remark') === 0) {
				$_obj['form_edit']['name'] = 'detail_' . $_obj['form_edit']['name'];
				$_details['detail_' . $_key] = $_obj;				
			} else {
				$_details[$_key] = $_obj;
			}
		}
		hlpr_setController($_details, 'order_rowid', '', array('type'=>'hdn'));
		//hlpr_setController($_details, 'cap_type_rowid', '');
		//hlpr_setController($_details, 'cap_type_detail', '', array('type'=>'txa', 'rows'=>1, 'maxlength'=>60));
		hlpr_setController($_details, 'standard_pattern_rowid', '');
		hlpr_setController($_details, 'standard_pattern_detail', '', array('type'=>'txa', 'rows'=>1, 'maxlength'=>60));
		hlpr_setController($_details, 'fabric_rowid', '');
		hlpr_setController($_details, 'front_color_rowid', 'สีหน้าหมวก');
		hlpr_setController($_details, 'back_color_rowid', 'สีหลังหมวก');
		hlpr_setController($_details, 'brim_color_rowid', 'สีปีกหมวก');
		hlpr_setController($_details, 'button_color_rowid', 'สีกระดุมหมวก');
		hlpr_setController($_details, 'is_sandwich_rim', 'มีกุ๊นขอบแซนวิช', array('type'=>'chk'));
		hlpr_setController($_details, 'swr_color_rowid', 'สีกุ๊นขอบแซนวิช');
		hlpr_setController($_details, 'is_air_flow', 'มีเจาะรูตาไก่', array('type'=>'chk'));
		hlpr_setController($_details, 'air_flow_holes_number', 'จำนวนรู', array('add_class'=>'input-integer'));
		hlpr_setController($_details, 'afh_color_rowid', 'สีรูตาไก่');
		hlpr_setController($_details, 'cap_belt_type_rowid', '');
		hlpr_setController($_details, 'cap_belt_detail', '', array('type'=>'txa', 'rows'=>1, 'maxlength'=>60));
		hlpr_setController($_details, 'detail_remark1', '', array('type'=>'txa', 'rows'=>2, 'maxlength'=>140));
		hlpr_setController($_details, 'detail_remark2', '', array('type'=>'txa', 'rows'=>2, 'maxlength'=>140));
		
		$_arrDetailsLayout =  array(
			//"แบบแพทเทิร์น" => array('cap_type_rowid', 'cap_type_detail')
			'แบบแพทเทิร์น' => array('standard_pattern_rowid', 'standard_pattern_detail')
			,'ชนิดผ้าหมวก' => array('fabric_rowid', '')
			,'สีหมวก' => array(
				array('front_color_rowid', '') 
				,array('back_color_rowid', '')
				,array('brim_color_rowid', '')
				,array('button_color_rowid', '')
			),
			'กุ๊นขอบแซนวิช' => array(
				array('is_sandwich_rim', 'swr_color_rowid', '')
			)
			,'รูตาไก่หมวก' => array(
				array('is_air_flow', 'air_flow_holes_number', 'afh_color_rowid')
			)
			,'อะไหล่หมวกด้านหลัง' => array(
				array('cap_belt_type_rowid', 'cap_belt_detail')
			)
			,'รายละเอียดเพิ่มเติม' => array(
				array('detail_remark1')
				,array('detail_remark2')
			)
		);
		
		return $this->add_view('order/_detail', 
			array(
				'controls' => hlpr_arrGetEditControls($_details)
				,'layout' => $_arrDetailsLayout
			), TRUE
		);
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
					"name" => "date_from",
					"value" => $_frm->format('d/m/Y')
				),
				array(
					"type" => "dpk",
					"label" => "ถึงวันที่",
					"name" => "date_to",
					"value" => $_to->format('d/m/Y')
				),
				array(
					"type" => "info",
					"value" => "&nbsp;"
				),
				array(
					"type" => "info",
					"value" => "* จำกัดจำนวนแสดงผลไว้ที่ 3,000 เพื่อประสิทธิภาพในการทำงานของโปรแกรม"
				)
			)
			,'layout' => array() //to prevent fall through passed parameters
			,'search_onload' => (($this->_CUSTOMER_ROWID <= 0) && ($this->_START_SCRIPT == ''))
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
				
				// ++ others price
				$_sql = <<<OTP
SELECT t.detail, TO_CHAR(COALESCE(t.price, 0), '9G999G990D00') AS price 
FROM t_order_price_cap t 
WHERE t.order_rowid = $_rowid 
ORDER BY t.price 
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
				$_sql =<<<SCRN
SELECT t.order_screen_rowid, COALESCE(m.name, '') AS order_screen, t.position, t.detail, t.size, t.job_hist
, TO_CHAR(COALESCE(t.price, 0), '9G999G990D00') AS price 
FROM t_order_screen_cap t 
LEFT OUTER JOIN pm_m_order_screen m ON t.order_screen_rowid = m.rowid 
WHERE t.order_rowid = $_rowid 
ORDER BY t.order_screen_rowid, t.position 

SCRN;
				$_arr4 = $this->m->arr_execute($_sql);
				$_strError = $this->m->error_message;
				if ($_strError != '') {
					$_whileChecker = FALSE;
					continue;
				}
				if ($_arr4 == FALSE) $_arr4 = array();
				$_arrReturn['screen'] = $_arr4;
				$_whileChecker = FALSE;
				// ++ screen	
			}
		}
		if ($_arrReturn !== FALSE) $_blnSuccess = TRUE;
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
	
	function _get_detail_report($rowid) {
		$_arrReturn = array();
		if (($rowid != FALSE) && $rowid > '0') {
			$_whileChecker = TRUE;
			while ($_whileChecker) {
				$this->load->model($this->modelName, 'm');
				$_arrReturn = $this->m->get_detail_report($rowid);				
				$_strError = $this->m->error_message;
				if ($_strError != '') {
					$_whileChecker = FALSE;
					continue;
				}

				$_sql = <<<OTP
SELECT STRING_AGG(t.detail, ',') AS others, COALESCE(SUM(t.price), 0) AS others_price 
FROM t_order_price_cap t 
WHERE t.order_rowid = $rowid

OTP;
				$_arr3 = $this->m->arr_execute($_sql);
				$_strError = $this->m->error_message;
				if ($_strError != '') {
					$_whileChecker = FALSE;
					continue;
				}
				if (is_array($_arr3)) {
					$_arrReturn['others'] = $_arr3[0]['others'];
					$_arrReturn['others_price'] = $_arr3[0]['others_price'];
				}

				$_sql = <<<SCRN
SELECT t.order_screen_rowid, COALESCE(m.name, '') AS order_screen, t.position, t.detail, t.size, t.job_hist
, TO_CHAR(COALESCE(t.price, 0), '9G999G990D00') AS price 
FROM t_order_screen_cap t 
	LEFT OUTER JOIN pm_m_order_screen m ON t.order_screen_rowid = m.rowid 
WHERE t.order_rowid = $rowid 
ORDER BY t.seq, t.order_screen_rowid, t.position 
SCRN;
				$_arr4 = $this->m->arr_execute($_sql);
				$_strError = $this->m->error_message;
				if ($_strError != '') {
					$_whileChecker = FALSE;
					continue;
				}
				if ($_arr4 == FALSE) $_arr4 = array();
				$_arrReturn['screen'] = $_arr4;
				$_whileChecker = FALSE;
			}
		}
		if (count($_arrReturn) > 0) {
			return $_arrReturn;
		} else {
			return FALSE;
		}
	}
	
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
					$_exists = $this->_mas->int_exists_job_number($_arr['job_number'], 5, (isset($_arr['rowid']) ? $_arr['rowid'] : -1));
					if ($_exists > 0) {
						throw new Exception('Duplicate "job_number" ( ' . $_arr['job_number'] . ' ) violation.'); 
					}
				}
				if (array_key_exists('order_date', $_arr)) $_arr['order_date'] = $this->m->_strConvertDisplayDateFormat($this->m->_datFromPost($_arr['order_date']));
				if (array_key_exists('due_date', $_arr)) $_arr['due_date'] = $this->m->_strConvertDisplayDateFormat($this->m->_datFromPost($_arr['due_date']));
				if (array_key_exists('deliver_date', $_arr)) $_arr['deliver_date'] = $this->m->_strConvertDisplayDateFormat($this->m->_datFromPost($_arr['deliver_date']));

				//++ Manage upload files
				$this->load->helper('upload_helper');
				$_tmpPath = _file_temp_upload_path();
				$_upPath = _file_upload_path();
				for ($_i=1;$_i<10;$_i++) {
					$_key = 'file_image' . $_i;
					$_oldKey = 'old_file_image' . $_i;
					if (array_key_exists($_key, $_arr) && ($_arr[$_key] != '')) {
						$_oldFile = $_tmpPath . $_arr[$_key];
						//$_now = new DateTime();
						$_ext = pathinfo($_oldFile, PATHINFO_EXTENSION);
						$_newFileName = gmdate('YmdHis') . '-' . $_i . '-pl.' . $_ext; // $_now->format('YmdHis') . '-1-pl.' . $_ext;
						$_newFile = $_upPath . $_newFileName;
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
						$_to_delete = $_upPath . trim($_arr[$_oldKey]);
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
					$_rslt = $this->db->query('SELECT rowid FROM t_order_add_option WHERE order_type_id = 5 AND order_rowid = ?', array($_rowid));
					if ($_rslt->num_rows > 0) {
						$_aopRowid = $_rslt->first_row()->rowid;
					}
					$this->load->model('Mdl_order_add_option', 'aopt');
					// ++ order detail
					$this->load->model('Mdl_order_detail_cap', 'md');
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
					
					$this->aopt->_FIELDS['order_type_id'] = 5; //cap type
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
					
					// ++ Others price
					$this->db->delete('t_order_price_cap', array('order_rowid'=>$_rowid));
					$_bulk = array();
					if (array_key_exists('others_price', $_arr)) {
						if (is_array($_arr['others_price'])) {
							foreach ($_arr['others_price'] as $_obj) {
								array_push($_bulk, array(
									'order_rowid' => $_rowid
									, 'detail' => $_obj['detail']
									, 'price' => $_obj['price']
									, "seq" => ((isset($_obj['seq']) && (intval($_obj['seq']) > 0)) ? intval($_obj['seq']) : NULL)
								));
							}
							if (count($_bulk) > 0) {
								$this->db->insert_batch('t_order_price_cap', $_bulk);
							}
						}
					}
					if ($this->db->_error_message() !== "") {
						$_strError = $this->db->_error_message();
						break;
					}
					// -- Others price

					// ++ Screen
					$this->db->delete('t_order_screen_cap', array('order_rowid'=>$_rowid));
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
								$this->db->insert_batch('t_order_screen_cap', $_bulk);
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

	function get_pdf($pdf_index, $rowid) {
		$pass['data'] = $this->_get_detail_report($rowid);
		if ($pass['data'] == FALSE) {
			echo "Error get report data: " . $this->m->error_message;
			return;
		} else {
			mb_internal_encoding("UTF-8");
			$this->load->helper('exp_pdf_helper');
			$this->load->helper('upload_helper');

			$file_name = '';
			$html = '';
			$_rev_no = (isset($pass['data']['quotation_revision'])) ? (int)$pass['data']['quotation_revision'] : 0;
			$now = new DateTime();
			$strNow = $now->format('YmdHis');
			switch ($pdf_index) {
				case "1":
					$file_name = 'FM-SA-05-001_' . $strNow . '.pdf';
					$pass['title'] = 'สั่งซื้อ หมวกสั่งตัด';
					$pass['code'] = sprintf('FM-SA-05-001 REV.%02d', $_rev_no);
					$pass['is_show_price'] = TRUE;
					$pass['head_section'] = $this->load->view('order/pdf/section/_pdf_order_detail_cap', $pass, TRUE);
					$pass['screen_section'] = $this->load->view('order/pdf/section/_pdf_screen', $pass, TRUE);
					$html = $this->load->view('order/pdf/pdf_cap', $pass, TRUE);
					break;
				case "6":
					$file_name = 'FM-SA-05-002_' . $strNow . '.pdf';
					$pass['title'] = 'ใบงานข้อมูล หมวกสั่งตัด';
					$pass['code'] = sprintf('FM-SA-05-002 REV.%02d', $_rev_no);
					$pass['head_section'] = $this->load->view('order/pdf/section/_pdf_order_detail_cap', $pass, TRUE);
					$pass['screen_section'] = $this->load->view('order/pdf/section/_pdf_screen', $pass, TRUE);
					$html = $this->load->view('order/pdf/pdf_cap', $pass, TRUE);
					break;
			}
//echo $html;exit;
			$this->load->library('pdf');
			$this->pdf->exportMPDF($html, $file_name);
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
