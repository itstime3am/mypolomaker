<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Delivery extends MY_Ctrl_crud {
	function __construct() 
	{
		parent::__construct();
		$this->modelName = 'Mdl_delivery';
		$this->_DETAIL_ROWS_LIMIT = 16; //row limit for display in pdf
		$this->_START_SCRIPT = '';
	}

	public function pass_command($action = 0, $rowid = 0) {
		if (is_numeric($rowid) && ($rowid > 0)) {
			$this->load->model($this->modelName, 'm');
			$_obj = $this->m->get_by_id($rowid);
			switch ($action) {
				case 1:
				case '1': //view
					$this->_START_SCRIPT .= "$(function() { _doExtCmdView(" . $rowid . ", '" . $_obj['code'] . "', " . $_obj['customer_rowid'] . "); });\n";
					break;
				/*
				case 2:
				case '2': //edit
					$this->_START_SCRIPT .= "$(function() {_doExtCmdEdit(" . $rowid . ", '" . $_obj['code'] . "', " . $_obj['customer_rowid'] . ");});\n";
					break;
				case 3:
				case '3': //clone
					$this->_START_SCRIPT .= "$(function() {_doExtCmdClone(" . $rowid . ", '" . $_obj['code'] . "', " . $_obj['customer_rowid'] . ");});\n";
					break;
				*/
			}
		}
		$this->index();
	}

	public function index($customer_rowid = -1) {
		$_strStartScript = '';
		if ($this->_START_SCRIPT != '') {
			$_strStartScript = $this->_START_SCRIPT;
		} elseif ($customer_rowid > 0) {
			$_arr = $this->c->get_by_id($customer_rowid);
			if (is_array($_arr) && isset($_arr['display_name'])) {
				$_strStartScript = "$(function() { _doCreateNew(" . $customer_rowid . ", '" . $_arr['display_name'] . "');});\n";
			}
		}
		$this->add_css(array(
			'public/css/jquery/ui/1.11.4/cupertino/jquery-ui.min.css',
			'public/css/jquery/dataTable/1.10.11/dataTables.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/buttons-1.1.2/buttons.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/colreorder-1.3.1/colReorder.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/fixedcolumns-3.2.1/fixedColumns.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/fixedheader-3.1.1/fixedHeader.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/responsive-2.0.2/responsive.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/scroller-1.4.1/scroller.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/select-1.1.2/select.jqueryui.min.css'
		));
		$this->add_js(array(
			'public/js/jquery/1.11.0/jquery.js',
			'public/js/jquery/ui/1.10.4/jquery-ui.min.js',
			'public/js/jquery/ui/1.10.3/jquery-ui-autocomplete-combobox.js',
			'public/js/jquery/dataTable/1.10.11/jquery.dataTables.min.js',
			'public/js/jquery/dataTable/1.10.11/dataTables.jqueryui.min.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/dataTables.buttons.min.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.jqueryui.min.js',
			'public/js/jquery/dataTable/extensions/jszip-2.5.0/jszip.min.js',
			'public/js/jquery/dataTable/extensions/pdfmake-0.1.18/pdfmake.min.js',
			'public/js/jquery/dataTable/extensions/pdfmake-0.1.18/vfs_fonts.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.html5.min.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.print.min.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.colVis.min.js',
			'public/js/jquery/dataTable/extensions/colreorder-1.3.1/dataTables.colReorder.min.js',
			'public/js/jquery/dataTable/extensions/fixedcolumns-3.2.1/dataTables.fixedColumns.min.js',
			'public/js/jquery/dataTable/extensions/fixedheader-3.1.1/dataTables.fixedHeader.min.js',
			'public/js/jquery/dataTable/extensions/responsive-2.0.2/dataTables.responsive.min.js',
			'public/js/jquery/dataTable/extensions/responsive-2.0.2/responsive.jqueryui.min.js',
			'public/js/jquery/dataTable/extensions/scroller-1.4.1/dataTables.scroller.min.js',
			'public/js/jquery/dataTable/extensions/select-1.1.2/dataTables.select.min.js',
			'public/js/jquery/dataTable/extensions/type-detection/moment_2.8.4.min.js',
			'public/js/jquery/dataTable/extensions/type-detection/datetime-moment.js',
			'public/js/jquery/dataTable/extensions/type-detection/numeric-comma.js',
			'public/js/jsGlobal.js',
			'public/js/jsUtilities.js',
			'public/js/jsGlobalConstants.js',
			array('var _DETAIL_ROWS_LIMIT = ' . $this->_DETAIL_ROWS_LIMIT . ';', 'custom')
		));
		/* ++ mail issue 20160225 ++ */
		/*
		$this->load->model('Mdl_customer', 'c');
		$this->_selOptions['customer'] = array(array('rowid'=>'', 'customer_name'=> '', 'display_name'=>'', 'company'=>''));
		$_temp = $this->c->list_customer_with_address();
		$_tmpCus = array();
		foreach ($_temp as $_row) {
			if ( ! array_key_exists($_row['rowid'], $_tmpCus)) {
				array_push($this->_selOptions['customer'], array(
					'rowid'=>$_row['rowid'], 
					'customer_name'=>$_row['customer_name'], 
					'display_name'=>$_row['display_name'], 
					'company'=>$_row['company']
				));
				$_tmpCus[$_row['rowid']] = array();
			}
			if ((trim($_row['tel']) != '') || (trim($_row['display_address']) != '')) array_push($_tmpCus[$_row['rowid']], array("tel"=>$_row['tel'], "address"=>$_row['display_address']));
		}
		$_strJson = json_encode($_tmpCus);
		if ($_strJson == "") $_strJson = '[]';
		$this->add_js('var _arrCustomerAddress = ' . $_strJson . ';', 'custom');
		*/
		/* -- mail issue 20160225 -- */

		//Get Default auto prepare controls (followed by model)
		$this->_prepareControlsDefault();
		//++ set special attributes
		$this->_setController("code", "เลขที่ใบส่ง", NULL, array("selectable"=>TRUE,"class"=>"center","default"=>TRUE,"order"=>1));
		$this->_setController("disp_report_create_date", "วันที่", NULL, array("selectable"=>TRUE,"class"=>"center","default"=>TRUE,"order"=>2));
		$this->_setController("disp_deliver_date", "ส่งมอบวันที่", NULL, array("selectable"=>TRUE,"default"=>FALSE,"order"=>3));
		$this->_setController("list_job_numbers", "เลขที่งาน", NULL, array("selectable"=>TRUE,"default"=>TRUE,"order"=>4));
		$this->_setController("title", "หัวข้อ", NULL, array("selectable"=>TRUE,"default"=>FALSE,"class"=>"center","order"=>5));
		$this->_setController("customer_name", "ลูกค้า", NULL, array("selectable"=>TRUE,"class"=>"center","default"=>TRUE,"order"=>6));
		$this->_setController("company", "บริษัท", NULL, array("selectable"=>TRUE,"default"=>FALSE,"order"=>7));
		$this->_setController("disp_grand_total", "ยอดรวม", NULL, array("selectable"=>TRUE,"default"=>TRUE,"class"=>"right default_number","order"=>8));
		$this->_setController("disp_remark", "หมายเหตุ", NULL, array("selectable"=>TRUE,"default"=>FALSE,"order"=>9));
		$this->_setController("create_user", "สร้างโดย", NULL, array("selectable"=>TRUE,"default"=>FALSE,"order"=>10));
		$this->_setController("update_user", "แก้ไขโดย", NULL, array("selectable"=>TRUE,"default"=>FALSE,"order"=>11));
		//-- set special attributes
		$this->_setController("is_approved", "");
		$this->_setController("deposit_date", "");
		$this->_setController("payment_date", "");
		$this->_setController("deposit_route", "");
		$this->_setController("payment_route", "");
		$this->_setController("deposit_amount", "");
		$this->_setController("payment_amount", "");
		$this->_setController("customer_address", "");
		$this->_setController("customer_full_addresses", "");

		$this->load->model('Mdl_master_table', 'mt');
		$_arrJoomlaUsers = $this->mt->list_joomla_users();
		array_unshift($_arrJoomlaUsers, array('id'=>'', 'name'=>''));
		$this->_selOptions["deposit_route"] = $this->mt->list_where('order_payment_route', 'is_cancel=0 AND is_deposit=1', 'sort_index', 'm_');
		$this->_selOptions["close_route"] = $this->mt->list_where('order_payment_route', 'is_cancel=0 AND is_close=1', 'sort_index', 'm_');
/*
		$_arrCustomerSearch = $this->c->list_select();
		if (is_array($_arrCustomerSearch)) array_unshift($_arrCustomerSearch, array('rowid'=>'', 'company'=>'', 'display_name'=>''));
*/
		/* ++ mail issue 20160225 ++ */
		/*
		$_arrCompanySearch = $this->c->list_select_company();
		if (is_array($_arrCompanySearch)) array_unshift($_arrCompanySearch, array('rowid'=>'', 'company'=>''));
		*/
		/* -- mail issue 20160225 -- */
		$_to = new DateTime();
		$_frm = date_sub(new DateTime(), new DateInterval('P3D'));
		$pass['left_panel'] = $this->add_view(
			'_public/_search_panel', 
			array(
				'controls' => array(
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
					if ((_match) && (_match.length > 0)) $('#sel-company', $(this).parents('form').get(0)).combobox('setValue', _match[1]);
				}

OSL
					),
					array(
						"type" => "txt",
						"label" => "เลขที่ใบสั่ง / ใบส่ง",
						"name" => "deliver_job_number"
					),
					array(
						"type" => "dpk",
						"label" => "จากวันที่",
						"name" => "date_from"
						,"value" => $_frm->format('d/m/Y')
					),
					array(
						"type" => "dpk",
						"label" => "ถึงวันที่",
						"name" => "date_to",
						"value" => $_to->format('d/m/Y')
					),
					array(
						"type" => "sel",
						"label" => 'สร้างโดย',
						"name" => "create_user_id",
						"sel_options" => $_arrJoomlaUsers,
						"sel_val" => "id",
						"sel_text" => "name"
					),
					array(
						"type" => "sel",
						"label" => 'แก้ไขโดย',
						"name" => "update_user_id",
						"sel_options" => $_arrJoomlaUsers,
						"sel_val" => "id",
						"sel_text" => "name"
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
				, 'search_onload' => ($_strStartScript == '')
			), TRUE
		);

		$_custom_columns = array();
		if ($this->_blnCheckRight('approve', 'delivery')) {
			$_custom_columns[] = array("column" => <<<CCLMS
{"sTitle":"Appr.","sWidth":"60","sClass":"center","mData":"client_temp_id","mRender":function(data,type,full) { if (full.is_approved <= 0) { return '<img class="tblButton" command="approve" src="./public/images/checkbox_no.png" title="Delivery order approvement" />'; } else if (full.is_approved >= 5) { return '<span class="cls-status-wip">WIP.</span>'; } else if (full.is_approved >= 2) { return '<span class="cls-status-rejected">REJ.</span>'; } else { return '<img class="tblButton" command="revoke" src="./public/images/checkbox_yes.png" title="Revoke an approvement" />'; }}, "bSortable": false}

CCLMS
				, "order" => 15
			);
		}
		if ($this->_blnCheckRight('view', 'delivery')) {
			$_custom_columns[] = array("column" => <<<CCLMS
{"sTitle":"เรียกดู", "sWidth":"50","sClass":"center","mData": function() { return '<img class="list-row-button" command="view" src="./public/images/b_view.png" alt="view" title="' + MSG_ICON_TITLE_VIEW + '" />';}, "bSortable": false}

CCLMS
				, "order" => 16
			);
			$_custom_columns[] = array("column" => <<<CCLMS
{"sTitle":"เอกสาร","sWidth":"50","sClass":"center","mData":"client_temp_id","mRender":function(data,type,full) { if (full.is_approved < 1) { return '<img class="tblButton" command="pdf" src="./public/images/doc_table_icon.png" title="เอกสารนำส่ง" />'; } else { return '&nbsp;'; } }, "bSortable":false}

CCLMS
				, "order" => 17
			);
		}
		if ($this->_blnCheckRight('edit', 'delivery')) {
			$_custom_columns[] = array("column" => <<<CCLMS
{"sTitle":"แก้ไข", "sWidth":"50","sClass":"center","mData":"client_temp_id","mRender":function(data,type,full) { if (full.is_approved < 1) { return '<img class="list-row-button" command="edit" src="./public/images/b_edit.png" alt="edit" title="' + MSG_ICON_TITLE_EDIT + '" />'; } else { return '&nbsp;'; } }, "bSortable": false}

CCLMS
				, "order" => 18
			);
		}
		if ($this->_blnCheckRight('delete', 'delivery')) {
			$_custom_columns[] = array("column" => <<<CCLMS
{"sTitle":"ลบ", "sWidth":"50","sClass":"center","mData":"client_temp_id","mRender":function(data,type,full) { if ((full.is_approved < 5)) { return '<img class="list-row-button" command="delete" src="./public/images/b_delete.png" alt="delete" title="' + MSG_ICON_TITLE_DELETE + '" />'; } else { return '&nbsp;'; }}, "bSortable": false}

CCLMS
				, "order" => 19
			);
		}
		//if (strlen($_custom_columns) > 0) $_custom_columns = substr($_custom_columns, 0, -2);

		$pass['work_panel'] = $this->add_view(
			'_public/_list', 
			array(
				'index'=>1,
				'dataview_fields'=>$this->_arrDataViewFields,
				'list_viewable'=>FALSE,
				'list_editable'=>FALSE,
				'list_deleteable'=>FALSE,
				'custom_columns'=>$_custom_columns,
				'edit_template'=>$this->add_view(
					'delivery/form', 
					array(
						'index'=>2
						, 'arrDepositRoute' => $this->_selOptions["deposit_route"]
						, 'arrCloseRoute' => $this->_selOptions["close_route"]
					),
					TRUE
				)
			), 
			TRUE
		);

		$pass['title'] = "จัดการใบนำส่งสินค้า";

		if ($_strStartScript != '') $this->add_js($_strStartScript, 'custom');
		
		$this->add_view_with_script_header('_public/_template_main', $pass);
	}

	function get_detail_by_id() {
		$_blnSuccess = TRUE;
		$_strError = '';
		$_strMessage = '';
		$_arrJN = array();
		$_arrDtls = array();
		$json_input_data = json_decode(trim(file_get_contents('php://input')), TRUE); //get json
		$_arrData = (isset($json_input_data))?$json_input_data:$this->input->post(); //or post data submit
		if (isset($_arrData) && ($_arrData != FALSE)) {
			$_rowid = (array_key_exists('rowid', $_arrData))?$_arrData['rowid']:-1;
			$this->load->model('Mdl_delivery', 'm');

			$_arrJN = $this->m->get_linked_job_number($_rowid);
			$_strError = $this->m->error_message;

			$_arrDtls = $this->m->get_detail_by_id($_rowid);
			$_strError .= $this->m->error_message;
		}
		if ($_arrDtls == FALSE) $_arrDtls = array();
		if ($_strError != '') $_blnSuccess = FALSE;
		$json = json_encode(
			array(
				'success' => $_blnSuccess,
				'error' => $_strError,
				'linked_job_number' => $_arrJN,
				'details' => $_arrDtls
			)
		);
		header('content-type: application/json; charset=utf-8');
		echo isset($_GET['callback'])? "{" . $_GET['callback']. "}(".$json.")": $json;		
	}
	
	function approve($rowid = -1) {
		$blnSuccess = FALSE;
		$strError = 'Unknown Error';
		$this->load->model('mdl_delivery', 'm');
		$result = $this->m->approve($rowid);
		$strError = $this->m->error_message;
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

	function revoke($rowid = -1) {
		$blnSuccess = FALSE;
		$strError = 'Unknown Error';
		$this->load->model('mdl_delivery', 'm');
		$result = $this->m->revoke($rowid);
		$strError = $this->m->error_message;
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

	function get_pdf($rowid = -1) {
		$this->load->model('Mdl_delivery', 'm');
		$_arrData = $this->m->arr_get_report_data($rowid);
		
		mb_internal_encoding("UTF-8");
		//$this->load->library('pdf');
		$this->load->library('mpdf8');
		$this->load->helper('exp_pdf_helper');
		$this->load->helper('upload_helper');
		$html = '';
		if ($_arrData !== FALSE) {
			//make it easy to checkbox
			if (array_key_exists('product_deliver', $_arrData)) $_arrData['product_deliver'] = ','.$_arrData['product_deliver'].',';
			if (array_key_exists('attachment', $_arrData)) $_arrData['attachment'] = ','.$_arrData['attachment'].',';
			$html = $this->load->view(
				'delivery/pdf/pdf', 
				array(
					'data' => $_arrData,
					'DETAIL_ROWS_LIMIT' => $this->_DETAIL_ROWS_LIMIT
				), 
				TRUE
			);
			// echo $html;exit;
			$this->mpdf8->_addPage($html);
			//$this->mpdf8->exportMPDF($html);
		}

		$_listJN = $this->m->get_linked_job_number($rowid);
		if (is_array($_listJN) && (count($_listJN) > 0)) {
			for ($_i=0;$_i<count($_listJN);$_i++) {
				$_ea = $_listJN[$_i];
				$_type_id = (int) $_ea['order_type_id'];
				$_order_rowid = (int) $_ea['order_rowid'];
				$pass = array();
//echo $_type_id . " :-> " . $_order_rowid . "<br>\n";
				switch ($_type_id) {
					case 1:
						$this->load->model('Mdl_order_polo', 'p');
						$pass['data'] = $this->p->get_detail_report($_order_rowid);
						if (is_array($pass['data'])) {
							$pass['title'] = 'ใบสั่งซื้อ เสื้อสั่งตัดโปโล';
							$pass['code'] = 'FM-SA-01-001 REV.00';
							$pass['is_show_price'] = TRUE;
							$pass['detail_section'] = $this->load->view('order/pdf/section/_pdf_order_detail', $pass, TRUE);
							$pass['others_price_panel'] = $this->load->view('order/pdf/section/_pdf_others_price', $pass, TRUE);
							$pass['size_quan_section'] = $this->load->view('order/pdf/section/_pdf_size_quan', $pass, TRUE);
							$pass['screen_section'] = $this->load->view('order/pdf/section/_pdf_screen', $pass, TRUE);
							$pass['images_section'] = $this->load->view('order/pdf/section/_pdf_sample_images', $pass, TRUE);
							//$html = $this->load->view('order/pdf/pdf_1', $pass, TRUE);

							$this->mpdf8->_addPage($this->load->view('order/pdf/pdf_1', $pass, TRUE));
						}
						break;
					case 2:
						if (! isset($this->order_tshirt)) $this->load->model('mdl_order_tshirt', 'order_tshirt');
						$pass['data'] = $this->order_tshirt->get_detail_report($_order_rowid);
						if (is_array($pass['data'])) {
							$pass['title'] = 'ใบงานข้อมูล เสื้อยืด';
							$pass['is_tshirt'] = TRUE;
							$pass['code'] = 'FM-SA-02-001 REV.00';
							$pass['detail_section'] = $this->load->view('order/pdf/section/_pdf_order_detail', $pass, TRUE);
							$pass['others_price_panel'] = $this->load->view('order/pdf/section/_pdf_others_price', $pass, TRUE);
							$pass['size_quan_section'] = $this->load->view('order/pdf/section/_pdf_size_quan', $pass, TRUE);
							$pass['screen_section'] = $this->load->view('order/pdf/section/_pdf_screen', $pass, TRUE);
							$pass['images_section'] = $this->load->view('order/pdf/section/_pdf_sample_images', $pass, TRUE);
					
							$this->mpdf8->_addPage($this->load->view('order/pdf/pdf_1', $pass, TRUE));
						}
						break;
					case 3:
						if (! isset($this->order_premade_polo)) $this->load->model('mdl_order_premade_polo', 'order_premade_polo');
						$pass['data'] = $this->order_premade_polo->get_detail_report($_order_rowid);
						if (is_array($pass['data'])) {
							$pass['title'] = 'ใบงานข้อมูล เสื้อโปโลสำเร็จรูป';
							$pass['code'] = 'FM-SA-03-001 REV.00';
							$pass['detail_section'] = $this->load->view('order/pdf/section/_pdf_premade_order_detail', $pass, TRUE);
							$pass['screen_section'] = $this->load->view('order/pdf/section/_pdf_screen', $pass, TRUE);
							$pass['images_section'] = $this->load->view('order/pdf/section/_pdf_sample_images', $pass, TRUE);

							$this->mpdf8->_addPage($this->load->view('order/pdf/premade_pdf', $pass, TRUE));
						}
						break;
					case 4:
						if (! isset($this->order_premade_tshirt)) $this->load->model('mdl_order_premade_tshirt', 'order_premade_tshirt');
						$pass['data'] = $this->order_premade_tshirt->get_detail_report($_order_rowid);
						if (is_array($pass['data'])) {
							$pass['title'] = 'ใบงานข้อมูล เสื้อยืดสำเร็จรูป';
							$pass['code'] = 'FM-SA-04-001 REV.00';
							$pass['detail_section'] = $this->load->view('order/pdf/section/_pdf_premade_order_detail', $pass, TRUE);
							$pass['screen_section'] = $this->load->view('order/pdf/section/_pdf_screen', $pass, TRUE);
							$pass['images_section'] = $this->load->view('order/pdf/section/_pdf_sample_images', $pass, TRUE);

							$this->mpdf8->_addPage($this->load->view('order/pdf/premade_pdf', $pass, TRUE));
						}
						break;
/*
					case 5:
						if (! isset($_CI->order_cap)) $_CI->load->library('../controllers/order_cap');
						$pass['data'] = $_CI->order_cap->_get_detail_report($_order_rowid);
						if (is_array($pass['data'])) {
							$pass['title'] = 'ใบงานข้อมูล หมวกสั่งตัด';
							$pass['code'] = 'FM-SA-05-001 REV.00';
							$pass['head_section'] = $this->load->view('order/pdf/section/_pdf_order_detail_cap', $pass, TRUE);
							$pass['screen_section'] = $this->load->view('order/pdf/section/_pdf_screen', $pass, TRUE);

							$this->mpdf8->_addPage($this->load->view('order/pdf/pdf_cap', $pass, TRUE));
						}
						break;
					case 6:
						if (! isset($_CI->order_jacket)) $_CI->load->library('../controllers/order_jacket');
						$pass['data'] = $_CI->order_jacket->_get_detail_report($_order_rowid);
						if (is_array($pass['data'])) {
							$pass['title'] = 'ใบงานข้อมูล แจ็คเก็ตสั่งตัด';
							$pass['code'] = 'FM-SA-06-001 REV.00';
							$pass['is_jacket'] = TRUE;
							$pass['detail_section'] = $this->load->view('order/pdf/section/_pdf_order_detail', $pass, TRUE);
							$pass['others_price_panel'] = $this->load->view('order/pdf/section/_pdf_others_price', $pass, TRUE);
							$pass['size_quan_section'] = $this->load->view('order/pdf/section/_pdf_size_quan', $pass, TRUE);
							$pass['screen_section'] = $this->load->view('order/pdf/section/_pdf_screen', $pass, TRUE);
							$pass['images_section'] = $this->load->view('order/pdf/section/_pdf_sample_images', $pass, TRUE);

							$this->mpdf8->_addPage($this->load->view('order/pdf/pdf_1', $pass, TRUE));
						}
						break;
*/
					case 7:
						if (! isset($this->order_premade_cap)) $this->load->model('mdl_order_premade_cap', 'order_premade_cap');
						$pass['data'] = $this->order_premade_cap->get_detail_report($_order_rowid);
						if (is_array($pass['data'])) {
							$pass['title'] = 'ใบงานข้อมูล หมวกสำเร็จรูป';
							$pass['code'] = 'FM-SA-07-001 REV.00';
							$pass['detail_section'] = $this->load->view('order/pdf/section/_pdf_premade_order_detail_cap', $pass, TRUE);
							$pass['screen_section'] = $this->load->view('order/pdf/section/_pdf_screen', $pass, TRUE);
							$pass['images_section'] = $this->load->view('order/pdf/section/_pdf_sample_images', $pass, TRUE);
							
							$this->mpdf8->_addPage($this->load->view('order/pdf/premade_pdf', $pass, TRUE));
						}
						break;
					case 8:
						if (! isset($this->order_premade_jacket)) $this->load->model('mdl_order_premade_jacket', 'order_premade_jacket');
						$pass['data'] = $this->order_premade_jacket->get_detail_report($_order_rowid);
						if (is_array($pass['data'])) {
							$pass['title'] = 'ใบงานข้อมูล แจ็คเก็ตสำเร็จรูป';
							$pass['code'] = 'FM-SA-08-001 REV.00';
							$pass['detail_section'] = $this->load->view('order/pdf/section/_pdf_premade_order_detail', $pass, TRUE);
							$pass['screen_section'] = $this->load->view('order/pdf/section/_pdf_screen', $pass, TRUE);
							$pass['images_section'] = $this->load->view('order/pdf/section/_pdf_sample_images', $pass, TRUE);

							$this->mpdf8->_addPage($this->load->view('order/pdf/premade_pdf', $pass, TRUE));							
						}
						break;
					case 5:
					case 6:
					case 9:
					case 10:
					case 11:
						if ($_type_id == 5) {
							$pass['title'] = 'ใบงานข้อมูล สั่งตัดหมวก';
							$pass['code'] = 'FM-SA-05-001 REV.00';
						} else if ($_type_id == 6) {
							$pass['title'] = 'ใบงานข้อมูล สั่งตัดเสื้อแจ็คเก็ต';
							$pass['code'] = 'FM-SA-06-001 REV.00';
						} else if ($_type_id == 9) {
							$pass['title'] = 'ใบงานข้อมูล สั่งตัดผ้ากันเปื้อน';
							$pass['code'] = 'FM-SA-09-001 REV.00';
						} else if ($_type_id == 10) {
							$pass['title'] = 'ใบงานข้อมูล สั่งตัดกระเป๋าผ้า';
							$pass['code'] = 'FM-SA-10-001 REV.00';
						} else if ($_type_id == 11) {
							$pass['title'] = 'ใบงานข้อมูล สั่งตัดเสื้อคนงาน';
							$pass['code'] = 'FM-SA-11-001 REV.00';
						}
						if (! isset($this->order_other)) $this->load->model('mdl_order_other', 'order_other');
						$pass['data'] = $this->order_other->get_detail_report($_order_rowid);
						if (is_array($pass['data'])) {
							$pass['detail_section'] = $this->load->view('order/pdf/section/_pdf_order_detail_others', $pass, TRUE);
							$pass['others_price_panel'] = $this->load->view('order/pdf/section/_pdf_others_price', $pass, TRUE);
							$pass['size_quan_section'] = $this->load->view('order/pdf/section/_pdf_size_quan', $pass, TRUE);
							$pass['screen_section'] = $this->load->view('order/pdf/section/_pdf_screen', $pass, TRUE);
							$pass['images_section'] = $this->load->view('order/pdf/section/_pdf_sample_images', $pass, TRUE);
						}
						$this->mpdf8->_addPage($this->load->view('order/pdf/pdf_other', $pass, TRUE));
						break;
					case 12:
					case 14:
						if ($_type_id == 12) {
							$pass['title'] = 'ใบงานข้อมูล ผ้ากันเปื้อนสำเร็จรูป';
							$pass['code'] = 'FM-SA-12-001 REV.00';
						} else if ($_type_id == 14) {
							$pass['title'] = 'ใบงานข้อมูล กระเป๋าผ้าสำเร็จรูป';
							$pass['code'] = 'FM-SA-14-001 REV.00';
						}
						if (! isset($this->order_premade_other)) $this->load->model('mdl_order_premade_other', 'order_premade_other');
						$pass['data'] = $this->order_premade_other->get_detail_report($_order_rowid);
						if (is_array($pass['data'])) {
							$pass['detail_section'] = $this->load->view('order/pdf/section/_pdf_premade_order_detail', $pass, TRUE);
							$pass['screen_section'] = $this->load->view('order/pdf/section/_pdf_screen', $pass, TRUE);
							$pass['images_section'] = $this->load->view('order/pdf/section/_pdf_sample_images', $pass, TRUE);
						}
						$this->mpdf8->_addPage($this->load->view('order/pdf/premade_pdf', $pass, TRUE));
						break;
				}
			}
		}

		$now = new DateTime();
		$file_name = 'delivery_' . $rowid . '_' . $now->format('YmdHis') . '.pdf';
		$this->mpdf8->_export($file_name, 'I');
	}

	function json_search_acc_job_number() {
		$blnSuccess = FALSE;
		$strError = 'Unknown Error';
		$this->load->model('mdl_order_status', 'm');
		$json_input_data = json_decode(trim(file_get_contents('php://input')), true); //get json
		$_arrData = (isset($json_input_data))?$json_input_data:$this->input->post(); //or post data submit
		if (isset($_arrData) && ($_arrData != FALSE)) {
			$arrResult = $this->m->search_acc($_arrData);
		} else {
			$arrResult = $this->m->search_acc();
		}
		
		$strError = $this->m->error_message;
		if ($strError == '') {
			$blnSuccess = TRUE;
			if (!is_array($arrResult)) {
				$arrResult = array();
			}
		}
		$json = json_encode(
			array(
				'success' => $blnSuccess,
				'error' => $strError,
				'data' => $arrResult
			)
		);
		header('content-type: application/json; charset=utf-8');
		echo isset($_GET['callback'])? "{" . $_GET['callback']. "}(".$json.")":$json;	
	}

	function list_detail_items($type_id, $order_rowid) {
		$blnSuccess = FALSE;
		$strError = 'Unknown Error';
		$this->load->model('mdl_order_status', 'm');
		$arrResult = $this->m->list_detail_order($type_id, $order_rowid);
		$strError = $this->m->error_message;
		if ($strError == '') {
			$blnSuccess = TRUE;
			if (!is_array($arrResult)) {
				$arrResult = array();
			}
		}
		$json = json_encode(
			array(
				'success' => $blnSuccess,
				'error' => $strError,
				'data' => $arrResult
			)
		);
		header('content-type: application/json; charset=utf-8');
		echo isset($_GET['callback'])? "{" . $_GET['callback']. "}(".$json.")":$json;	
	}

	function unlink_job_number($jobno_rowid, $delivery_rowid) {
		$blnSuccess = FALSE;
		$strError = 'Unknown Error';
		$this->load->model('mdl_delivery', 'm');
		if ($jobno_rowid > 0) {
			$result = $this->m->unlink_job_number($jobno_rowid, $delivery_rowid);
			$strError = $this->m->error_message;
			if ($strError == '') {
				$blnSuccess = TRUE;
			}
		} else {
			$blnSuccess = TRUE;
			$strError = 'Invalid parameter ( linked job_number rowid <= 0 )';
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
