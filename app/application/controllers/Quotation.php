<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quotation extends MY_Ctrl_crud {
	function __construct() {
		parent::__construct();
		$this->modelName = 'Mdl_quotation';
	}

	public function index($customer_rowid = -1) {
		$this->load->model('Mdl_customer', 'c');
		$_strStartScript = '';
		if ($customer_rowid > 0) {
			$_arr = $this->c->get_by_id($customer_rowid);
			if (is_array($_arr) && isset($_arr['display_name'])) {
				$_strStartScript = "$(function() { _doCreateNew(" . $customer_rowid . ", '" . $_arr['display_name'] . "');});\n";
			}
		}
		$this->add_css(array(
			/*
			'public/css/jquery/ui/1.10.4/dark-hive/jquery-ui.css',
			*/
			'public/css/jquery/ui/1.11.4/cupertino/jquery-ui.min.css',
			//'public/css/jquery/dataTable/TableTools/2.1.5/TableTools_JUI.css',
			/*
			'public/css/jquery/dataTable/1.10.11/jquery.dataTables.min.css',
			'public/css/jquery/dataTable/extensions/buttons-1.1.2/buttons.dataTables.min.css',
			'public/css/jquery/dataTable/1.10.11/dataTables.bootstrap.css',
			'public/css/jquery/dataTable/extensions/buttons-1.1.2/buttons.bootstrap.min.css',
			'public/css/jquery/dataTable/1.10.11/dataTables.foundation.css',
			'public/css/jquery/dataTable/extensions/buttons-1.1.2/buttons.foundation.min.css',
			*/
			'public/css/jquery/dataTable/1.10.11/dataTables.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/buttons-1.1.2/buttons.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/colreorder-1.3.1/colReorder.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/fixedcolumns-3.2.1/fixedColumns.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/fixedheader-3.1.1/fixedHeader.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/responsive-2.0.2/responsive.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/scroller-1.4.1/scroller.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/select-1.1.2/select.jqueryui.min.css',
			'public/css/jquery/ui/timepicker/1.6.1/jquery-ui-timepicker-addon.min.css',
			'public/css/jquery/fileupload/fileupload.css',
			'public/css/order/form.css',
			'public/css/order/_detail_premade.css',
			'public/css/quotation/form.css'
		));
		$this->add_js(array(
			'public/js/jquery/1.11.0/jquery.js',
			'public/js/jquery/ui/1.10.4/jquery-ui.min.js',
			'public/js/jquery/ui/1.10.3/jquery-ui-autocomplete-combobox.js',
			'public/js/jquery/dataTable/1.10.11/jquery.dataTables.min.js',
			/*
			'public/js/jquery/dataTable/1.10.11/dataTables.bootstrap.min.js',
			'public/js/jquery/dataTable/1.10.11/dataTables.foundation.min.js',
			*/
			'public/js/jquery/dataTable/1.10.11/dataTables.jqueryui.min.js',
			/*
			'public/js/jquery/dataTable/TableTools/2.1.5/ZeroClipboard.js',
			'public/js/jquery/dataTable/TableTools/2.2.1/dataTables.tableTools.js',
			*/
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/dataTables.buttons.min.js',
			/*
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.bootstrap.min.js',
			'public/js/jquery/dataTable/extensions/buttons-1.1.2/buttons.foundation.min.js',
			*/
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
			'public/js/jquery/ui/timepicker/1.6.1/jquery-ui-timepicker-addon.min.js',
			'public/js/jquery/ui/timepicker/1.6.1/jquery-ui-sliderAccess.js',
			'public/js/jquery/fileupload/load-image.min.js',
			'public/js/jquery/fileupload/canvas-to-blob.min.js',
			'public/js/jquery/fileupload/jquery.iframe-transport.js',
			'public/js/jquery/fileupload/jquery.fileupload.js',
			'public/js/jquery/fileupload/jquery.fileupload-process.js',
			'public/js/jquery/fileupload/jquery.fileupload-image.js',
			'public/js/jquery/fileupload/jquery.form.js',
			'public/js/_public/_fmg_controller.js'
			, 'public/js/jsGlobal.js'
			, 'public/js/jsUtilities.js'
			, 'public/js/jsGlobalConstants.js'
			, array(<<<SCRPT
		$.fn.dataTable.moment( 'YYYY/MM/DD', moment.locale('en') );
		$.fn.dataTable.moment( 'DD/MM/YYYY', moment.locale('en') );
		$.fn.dataTable.moment( 'DD MM YYYY', moment.locale('en') );

SCRPT
			, 'custom')
		));
		
		$this->load->model('mdl_master_table', 'mt');
		$this->load->model($this->modelName, 'm');

		$this->_selOptions["branches"] = $this->mt->list_joomla_user_branch();
		array_unshift($this->_selOptions["branches"], array('id'=>'', 'title'=>''));
		$this->_selOptions["users"] = $this->mt->list_joomla_users();
		array_unshift($this->_selOptions["users"], array('id'=>'', 'name'=>''));
		$this->_selOptions["payment_route"] = $this->mt->list_where('order_payment_route', 'is_cancel=0 AND is_deposit=1', 'sort_index', 'm_');

		$_strNextQONumber = $this->m->getNextQONumber();
		
		//Get Default auto prepare controls (followed by model)
		$this->_prepareControlsDefault();

		//++ set special attributes		
		$this->_setController("rowid", "", array("type"=>"hdn"));
		$this->_setController("status_rowid", "", array("type"=>"hdn"));
		$this->_setController("customer_rowid", "ลูกค้า", array(
			"type"=>"aac"
			, "url" => "./customer/json_search_acc"
			, "min_length" => 3
			, "sel_val" => "rowid"
			, "sel_text" => "display_name_company"
			, "disp_text" => "customer"
		));
		$this->_setController("branch_jug_id", "สาขา", array("type"=>"sel", "sel_options"=>$this->_selOptions["branches"], "sel_val"=>"id", "sel_text"=>"title"));
		$this->_setController("sale_rowid", "พนักงาน", array("type"=>"hdn"));
		$this->_setController("is_disp_notice", "แสดงข้อความหมายเหตุ", array("type"=>"chk"));		
		$this->_setController("start_date", "วันที่สร้าง", array("type"=>"dpk"));
		$this->_setController("day_limit", "กำหนดยื่นราคา", array("type"=>"sel", "sel_val"=>"rowid", "sel_text"=>"name"
			, "sel_options"=>array(
				array("rowid"=>"2", "name"=>"2 วัน")
				,array("rowid"=>"7", "name"=>"7 วัน")
				,array("rowid"=>"15", "name"=>"15 วัน")
				,array("rowid"=>"30", "name"=>"30 วัน")
				,array("rowid"=>"45", "name"=>"45 วัน")
				,array("rowid"=>"60", "name"=>"60 วัน")
			)
		));
		$this->_setController("percent_discount", "ส่วนลด ( % )", array("class"=>"input-double"));
		$this->_setController("payment_condition_rowid", "เงื่อนไขการชำระเงิน", array("type"=>"sel", "sel_val"=>"rowid", "sel_text"=>"name"
			, "sel_options"=>array(
				array("rowid"=>"1", "name"=>"เงินสด")
				,array("rowid"=>"3", "name"=>"ส่งเสริมการขาย")
				,array("rowid"=>"2", "name"=>"CREDIT")
			)
			, "on_changed"=>"_fnc_onChangePaymentCondition.apply(this, arguments);"
		));
		$this->_setController("is_vat", "VAT", array("type"=>"sel", "sel_val"=>"rowid", "sel_text"=>"name"
			, "sel_options"=>array(
				array("rowid"=>"0", "name"=>"ไม่มี VAT")
				,array("rowid"=>"1", "name"=>"แยก VAT(นอก)")
				,array("rowid"=>"2", "name"=>"รวม VAT(ใน)")
			)
		));
		$this->_setController("days_credit", "จำนวนวันเครดิต", array("class"=>"input-integer"));
		//$this->_setController("deposit_percent", "", array("type"=>"hdn", "class"=>"input-double"));
		//$this->_setController("deposit_amount", "", array("type"=>"hdn", "class"=>"input-double"));
		$this->_setController("remark", "บันทึกเพิ่มเติม", array("type"=>"txa"));

		$this->_setController("qo_number", "เลขที่"
			, array("type"=>"txt", "class"=>"set-disabled data-constant", "value"=>$_strNextQONumber)
			, array("selectable"=>TRUE,"default"=>TRUE,"order"=>0)
		);
		$this->_setController("revision", "Rev.", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>1));
		$this->_setController("disp_start_date", "วันที่", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>2));
		$this->_setController("disp_end_date", "วันที่สิ้นสุด", array(), array("selectable"=>TRUE,"default"=>FALSE,"class"=>"center","order"=>3));
		$this->_setController("branch", "สาขา", array(), array("selectable"=>TRUE,"default"=>FALSE,"class"=>"center","order"=>4));
		$this->_setController("customer", "ลูกค้า", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>5));
		$this->_setController("grand_total", "ราคารวม", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"default_number","order"=>6));
		$this->_setController("approved_payment", "ยอดชำระยืนยัน", array("type"=>"hdn"), array("selectable"=>TRUE,"default"=>FALSE,"class"=>"default_number","order"=>7));
		$this->_setController("apprv_pending", "ยอดชำระรอตรวจสอบ", array("type"=>"hdn"), array("selectable"=>TRUE,"default"=>FALSE,"class"=>"default_number","order"=>8));
		$this->_setController("deposit_amount", "ยอดมัดจำที่ต้องชำระ", array(), array("selectable"=>TRUE,"default"=>FALSE,"class"=>"default_number","order"=>9));
		$this->_setController("promotion", "โปรโมชั่น", array(), array("selectable"=>TRUE,"default"=>FALSE,"order"=>10));
		$this->_setController("disp_is_vat", "VAT", array(), array("selectable"=>TRUE,"default"=>FALSE,"class"=>"center","order"=>14));
		$this->_setController("create_user", "สร้างโดย", array(), array("selectable"=>TRUE,"default"=>FALSE,"class"=>"center","order"=>15));
		//$this->_setController("disp_status", "สถานะ", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>16));
		//-- set special attribute

		/*++ dummy field, use it value to show span on panel (just add to array keep value) */
		$this->_setController("sum_net", "", array());
		$this->_setController("sum_discount", "", array());
		$this->_setController("sum_vat", "", array());
		$this->_setController("sum_amount", "", array());
		$this->_setController("status_rowid", "", array("type"=>"hdn"));
		$this->_setController("revision", "", array("type"=>"hdn"));
		$this->_setController("arr_payment_log", "", array("type"=>"hdn"));
		$this->_setController("arr_deposit_log", "", array("type"=>"hdn"));
		$this->_setController("arr_avail_status", "", array());
		$this->_setController("arr_avail_action", "", array());
		$this->_setController("user_acr_action", "", array());
		$this->_setController("is_deposit_ready", "", array());
		$this->_setController("deposit_payment", "", array("type"=>"hdn"));
		$this->_setController("disp_left_amount", "", array());
		//$this->_setController("link_delivery_rowid", "", array());
		$this->_setController("disp_status", "", array());
		$this->_setController("arr_details", "", array());
		/*-- dummy field, use it value to show span on panel (just add to array keep value) */

		// ++ remove from edit controllers queue
		unset($this->_arrDataViewFields['deposit_amount']['form_edit']);
		unset($this->_arrDataViewFields['deposit_percent']['form_edit']);
		unset($this->_arrDataViewFields['status_remark']['form_edit']);
		// -- remove from edit controllers queue

		//$_custom_columns = '{"sTitle":"ใบงาน", "sClass":"center","mData":function() { return \'<img class="tblButton" command="pdf" src="./public/images/pdf_icon_40.png" title="Export to PDF" />\';}, "bSortable": false}';
		$_custom_columns = array(
			array(
				"column" => <<<CCLMS
{ "sTitle":"ชำระแล้ว (%)","width":"100","sClass":"right","mData":'rowid', "mRender": function(data,type,full) { return fnc__DDT_Row_RenderPercentPayment(data, type, full); }, "bSortable": true }
CCLMS
				, "order" => 12
			)
			, array(
				"column" => <<<CCLMS
{ "sTitle":"สถานะ","width":"100","sClass":"center","mData":'rowid',"mRender":function(data,type,full) { return fnc__DDT_Row_RenderStatus(data, type, full); }, "bSortable": true }
CCLMS
				, "order" => 16
			)
			, array(
				"column" => <<<CCLMS
{ "sTitle":"หมายเหตุ","width":"100","sClass":"center","mData":'rowid',"mRender":function(data,type,full) { return fnc__DDT_Row_RenderStatusRemark(data, type, full); }, "bSortable": true }
CCLMS
				, "order" => 17
			)
			, array(
				"column" => <<<CCLMS
{ "sTitle":"ร่างใบงาน","width":"180","sClass":"center","mData":'rowid',"mRender":function(data,type,full) { return fnc__DDT_Row_RenderDraftDetailOrder(data, type, full); }, "bSortable": false }
CCLMS
				, "order" => 18
			)
			, array(
				"column" => <<<CCLMS
{ "sTitle":"แก้ไขสถานะ","width":"180","sClass":"center","mData":'rowid',"mRender":function(data,type,full) { return fnc__DDT_Row_RenderAvailStatus(data, type, full); }, "bSortable": false }
CCLMS
				, "order" => 19
			)
			, array(
				"column" => <<<CCLMS
{ "sTitle":"#","width":"180","sClass":"center","mData":'rowid',"mRender":function(data,type,full) { return fnc__DDT_Row_RenderNotify(data, type, full); }, "bSortable": false }
CCLMS
				, "order" => 20
			)
			, array(
				"column" => <<<CCLMS
{ "sTitle":"จัดการข้อมูล","width":"120","sClass":"center","mData":'rowid',"mRender":function(data,type,full) { return fnc__DDT_Row_RenderAvailAction(data, type, full); }, "bSortable": false }
CCLMS
				, "order" => 21
			)
			, array(
				"column" => '{"sTitle":"เอกสาร", "sClass":"center","mData":function() { return \'<img class="tblButton" command="pdf" src="./public/images/pdf_icon_40.png" title="Export to PDF" />\';}, "bSortable": false}'
				, "order" => 22
			)
		);
		$pass['left_panel'] = $this->add_view('_public/_search_panel', $this->_arrSearchParams(), TRUE);

		$this->load->helper('order_detail_helper');
		$template = array(
			'index' => 0,
			'list_viewable' => FALSE,
			'list_insertable' => $this->_blnCheckRight('insert', 'quotation'),
			'list_editable' => FALSE,
			'list_deleteable' => FALSE,
			'dataview_fields' => $this->_arrDataViewFields,
			'custom_columns' => $_custom_columns
			//, 'jqDataTable' => '1.10.11'
		);		
		$template['edit_template'] = $this->_getEditTemplate();
		$pass['work_panel'] = $this->add_view('_public/_list', $template, TRUE);
		$pass['title'] = "ใบเสนอราคา";
		
		$this->add_js('public/js/quotation/form.js');
		$this->add_js('public/js/quotation/detail.js');
		//$this->add_js('public/js/quotation/payment.js');
		
		if ($_strStartScript != '') $this->add_js($_strStartScript, 'custom');
		
		$qo_status = $this->mt->list_where('quotation_status', 'is_cancel=0', NULL, 'm_');
		$this->add_js("var _ARR_QO_STATUS = " . json_encode($qo_status) . ";", 'custom');

		$this->_DISABLE_ON_LOAD_SEARCH = True;
		$this->add_view_with_script_header('_public/_template_main', $pass);
	}

	function _getEditTemplate() {
		$this->_arrControlLayout = array();
		$this->_arrControlLayout = array_merge($this->_arrControlLayout, 
			array(
				array('qo_number', 'customer_rowid', 'branch_jug_id')
				, array('start_date', 'day_limit', 'is_disp_notice')
				, array('payment_condition_rowid', 'days_credit', 'return <div class="table-title div-sum-title">ราคารวมสุทธิ</div><div class="div-sum-value"><span id="spn-sum_amount" class="user-input input-double input-format-number spn-sum-value"></span>บาท</div>')
				, array('promotion', 'percent_discount', 'return <div class="table-title div-sum-title">ส่วนลด</div><div class="div-sum-value"><span id="spn-sum_discount" class="user-input input-double input-format-number spn-sum-value"></span>บาท</div>')
				, array('', 'is_vat', 'return <div class="table-title div-sum-title">VAT</div><div class="div-sum-value"><span id="spn-sum_vat" class="user-input input-double input-format-number spn-sum-value"></span>บาท</div>')
				, array('', '', 'return <div class="table-title div-sum-title">ยอดรวมทั้งสิ้น</div><div class="div-sum-value"><span id="spn-grand_total" class="user-input input-double input-format-number spn-sum-value"></span>บาท</div>')
				, array('', 'return <div class="table-title frm-edit-row-title">เงินมัดจำ</div><div class="table-value frm-edit-row-value"><input type="text" id="txt-deposit_percent" class="user-input input-double input-format-number" style="width:6em;" data="deposit_percent" placeholder="เปอร์เซ็นต์"> % ( <input type="text" id="txt-deposit_amount" class="user-input input-double input-format-number" style="width:12em;" data="deposit_amount" placeholder="ยอดเงิน"> บาท )</div>', 'return <div class="table-title div-sum-title" from_qs="40"><button id="btnDepositPaymentDialog" class="cls-button">รายการชำระเงิน</button> ชำระแล้ว</div><div class="div-sum-value" from_qs="40"><span id="spn-deposit_payment" class="user-input input-double input-format-number spn-sum-value"></span>บาท</div>')
				, array('', '', 'return <div class="table-title div-sum-title" from_qs="40">คงเหลือ</div><div class="div-sum-value" from_qs="40"><span id="spn-disp_left_amount" class="user-input input-double input-format-number spn-sum-value" from_qs="110" to_qs="120"></span>บาท</div>')
				, array('return <span id="spn-status_remark" style="color:red;font-weight:600;" class="user-input data-container data-constant" data="status_remark"></span>')
				, array('remark')
				, array('arr_payment_log')
			)
		);
		//++ details panel form parts
		$this->_selOptions["list_title"] = $this->mt->list_all('quotation_detail_title', 'sort_index');
		array_unshift($this->_selOptions["list_title"], array('rowid'=>'', 'name'=>' '));

		$this->load->helper('crud_controller_helper');
		$subListControls = hlpr_prepareControlsDefault('Mdl_quotation_detail', $this->_selOptions);
		hlpr_setController($subListControls, "quotation_rowid", "", array("type"=>"hdn", "value"=>"-1"));
		hlpr_setController($subListControls, "title", "หัวข้อ", NULL, array("selectable"=>TRUE,"order"=>0));
		hlpr_setController($subListControls, "description", "คำอธิบาย", NULL);
		hlpr_setController($subListControls, "qty", "จำนวน/รายการ", NULL, array("selectable"=>TRUE,"class"=>"default_int","order"=>2));
		hlpr_setController($subListControls, "price", "ราคาต่อหน่วย", NULL, array("selectable"=>TRUE,"class"=>"default_number","order"=>3));
		hlpr_setController($subListControls, "amount", "ราคารวม", NULL, array("selectable"=>TRUE,"class"=>"default_number","order"=>4));		
		hlpr_setController($subListControls, "abbr_description", "คำอธิบาย", array(), array("selectable"=>TRUE,"order"=>1));

		$_main = $this->add_view(
			'_public/_form', 
			array(
				'index' => 0
				, 'crud_controller' => 'quotation'
				, 'controls' => $this->_arrGetEditControls()
				, 'layout' => $this->_arrControlLayout
				//, 'sublist' => 
			), TRUE);

		$_listSupplier = $this->mt->list_where("order_supplier", "is_cancel < 1", "sort_index", "m_");
		$_detail = $this->add_view(
			'_public/_sublist'
			, array(
				'index' => 1
				, 'master_cols'=>'rowid'
				, 'map_cols'=>'quotation_rowid'
				, 'list_insertable' => $this->_blnCheckRight('edit', 'quotation')
				, 'list_editable' => $this->_blnCheckRight('edit', 'quotation')
				, 'list_deleteable' => $this->_blnCheckRight('edit', 'quotation')
				, 'dataview_fields' => $subListControls
				, 'edit_template' => $this->load->view('quotation/detail', array(
					'index' => 1
					, 'crud_controller' => 'quotation_detail'
					, 'listDetailTitle' => $this->_selOptions["list_title"]
					, 'listSupplier' => $_listSupplier
					, 'polo_panel' => hlpr_get_OrderPolo_ViewParams()
					, 'tshirt_panel' => hlpr_get_OrderTshirt_ViewParams()
					, 'other_panel' => hlpr_get_OrderOther_ViewParams()
					, 'premade_polo_panel' => hlpr_get_OrderPremadePolo_ViewParams()
					, 'premade_tshirt_panel' => hlpr_get_OrderPremadeTshirt_ViewParams()
					, 'premade_cap_panel' => hlpr_get_OrderPremadeCap_ViewParams()
					, 'premade_jacket_panel' => hlpr_get_OrderPremadeJacket_ViewParams()
					, 'premade_other_panel' => hlpr_get_OrderPremadeOther_ViewParams()
					/*, 'cap_panel' => hlpr_get_OrderCap_ViewParams()
					, 'jacket_panel' => hlpr_get_OrderJacket_ViewParams()*/
					
				), TRUE)
			), TRUE);

		$_depPaymentDlg = $this->add_view(
			'_public/_list_payment_dialog'
			, array(
				'index' => 3
				, 'access'=> array(
					"deposit"=>array("editable"=>($this->_blnCheckRight('edit', 'quotation')), "approveable" => ($this->_blnCheckRight('stt>acs', 'quotation')))
					,"payment"=>array("editable"=>($this->_blnCheckRight('edit', 'quotation')), "approveable" => ($this->_blnCheckRight('stt>acs', 'quotation')))
				)
			), TRUE);

		return <<<TMP
<div id="tabs" class="cls-tab-container">
	<ul><li><a href="#divMain">หน้าหลัก</a></li><li><a href="#divDetail">รายละเอียด</a></li></ul>
	<div id="divMain">
		{$_main}
	</div>
	<div id="divDetail">
		{$_detail}
	</div>
	{$_depPaymentDlg}
</div>
<div id="div_status_remark">
	<span class="cls-label" style="font-weight:bold;"></span>
	<!-- 
	<select id="sel-status_remark" style="width:45%;vertical-align:top;" class="user-input">
		<option>&nbsp;</option>
		<option value="ไม่ทราบเหตุผลลูกค้า">ไม่ทราบเหตุผลลูกค้า</option>
		<option value="ไม่เข้าเงื่อนไขการเงิน (ของลูกค้า)">ไม่เข้าเงื่อนไขการเงิน (ของลูกค้า)</option>
		<option value="สินค้าไม่เพียงพอ">สินค้าไม่เพียงพอ</option>
		<option value="สินค้าไม่ตรงกับความต้องการ">สินค้าไม่ตรงกับความต้องการ</option>
		<option value="ขั้นต่ำในการผลิตน้อยไป">ขั้นต่ำในการผลิตน้อยไป</option>
		<option value="ติดต่อสาขาอื่นแล้ว">ติดต่อสาขาอื่นแล้ว</option>
		<option value="ติดต่อเจ้าอื่นแล้ว">ติดต่อเจ้าอื่นแล้ว</option>
		<option value="ราคาไม่ผ่าน">ราคาไม่ผ่าน</option>
		<option value="ระยะเวลาในการผลิตสั้นไป">ระยะเวลาในการผลิตสั้นไป</option>
		<option value="สินค้าและเทคนิคไม่รับทำ">สินค้าและเทคนิคไม่รับทำ</option>
	</select>
	-->
	<textarea id="txa-status_remark" style="width:96%;" class="user-input" rows="3" placeholder="สาเหตุ หรือ ข้อมูลเพิ่มเติม"></textarea>
</div>
TMP;
	}

	function commit() {
		$_blnSuccess = FALSE;
		$_strError = '';
		$_strMessage = '';
		$json_input_data = json_decode(trim(file_get_contents('php://input')), true); //get json
		$_arr = (isset($json_input_data))?$json_input_data:$this->input->post(); //or post data submit
		if (isset($_arr) && ($_arr != FALSE)) {
			$this->load->model($this->modelName, 'm');
			
			if (empty($_arr['sale_rowid'])) $_arr['sale_rowid'] = $this->session->userdata('user_id');
			if (array_key_exists('start_date', $_arr) && (! empty($_arr['start_date']))) {
				$_datValue = $this->m->_datFromPost($_arr['start_date']);
				if ($_datValue instanceof DateTime) $_arr['start_date'] = $_datValue;
			}
			//$this->db->trans_begin();
			
			$_aff_rows = $this->m->commit($_arr);
			$_strError = $this->m->error_message;
			$_rowid = 0;
			if (array_key_exists('rowid', $_arr) && (trim($_arr['rowid']) > '0')) {
				$_rowid = $_arr['rowid'];
			} else {
				$_rowid = $this->m->last_insert_id;
				// ++ update revision to 1, start trigger job for revision runnig by editting
				if ($_strError == '') {
					$this->db->reset_query();

					$this->db->set('revision', 1);
					$this->db->set('update_by', (int) $this->session->userdata('user_id'));
					$this->db->set('update_date', 'now()', FALSE);
					$this->db->where('rowid', $_rowid);
					$this->db->update('pm_t_quotation');
					$_strError .= $this->db->error()['message'];
				}
			}
			if ($_rowid <= 0) $_strError .= 'Invalid rowid';
/*			if ($_strError == '') {
//var_dump($_arr['details']);exit;
				if (array_key_exists('details', $_arr) && (is_array($_arr['details'])) && (count($_arr['details']) > 0)) {
					//$this->db->delete('pm_t_quotation_detail', array('quotation_rowid'=>$_rowid));
					$_dataSet = array();
					foreach ($_arr['details'] as $_row) {
						array_push($_dataSet, array(
							"quotation_rowid"=>$_rowid
							,"title"=>$_row['title']
							,"description"=>$_row['description']
							,"qty"=>(float)$_row['qty']
							,"price"=>(float)$_row['price']
							,"create_by"=>(int) $this->session->userdata('user_id')
						));
					}
					//$this->load->model('Mdl_quotation_detail', 'md');
					$this->db->insert_batch('pm_t_quotation_detail', $_dataSet);
					$_strError .= $this->db->error()['message'];
				}
			}
*/
//echo $this->db->last_query();exit;
			/*
			if (($this->db->trans_status() === FALSE) || ($_strError != "")) {
				$_strError .= "::DB Transaction rollback";
				$this->db->trans_rollback();
			}
			*/
			if ($_strError == "") {
				$_blnSuccess = TRUE;
				$_strMessage = $_aff_rows;
				//$this->db->trans_complete();	
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

	function _arrSearchParams() {
		$_arrCompanySearch = $this->c->list_select_company();
		if (is_array($_arrCompanySearch)) array_unshift($_arrCompanySearch, array('rowid'=>'', 'company'=>''));
		$_to = new DateTime();
		$_frm = date_sub(new DateTime(), new DateInterval('P15D'));
		return array(
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
						"type" => "sel"
						, "label" => "บริษัท"
						, "name" => "company"
						, "sel_options" => $_arrCompanySearch
						, "sel_val" => "company"
						, "sel_text" => "company"
						, "on_changed" => <<<OCH
function(str, event, ui) {
	var _str = str || '';
	_str = _str.toString().trim();
	if (_str != '') {
		$('#tblSearchPanel #aac-customer_rowid').autocomplete('search', _str);
	} /*else {
		$('#tblSearchPanel #aac-customer_rowid').val('');
	}*/
}
OCH
					),
					array(
						"type" => "txt",
						"label" => $this->_getDisplayLabel('qo_number'),
						"name" => "qo_number"
					),
					array(
						"type" => "dpk"
						,"label" => "จากวันที่"
						,"name" => "date_from"
						,"value" => $_frm->format('d/m/Y')
					),
					array(
						"type" => "dpk"
						,"label" => "ถึงวันที่"
						,"name" => "date_to"
						//,"value" => $_to->format('d/m/Y')
					),
					array(
						"type" => "sel",
						"label" => 'สร้างโดย',
						"name" => "create_user_id",
						"sel_options" => $this->_selOptions["users"],
						"sel_val" => "id",
						"sel_text" => "name"
					),
					array(
						"type" => "chk",
						"label" => "แสดงเฉพาะ active",
						"name" => "is_active_status",
						"value" => TRUE
					),
					array(
						"type" => "chk",
						"label" => "แสดงเฉพาะ ยังไม่ยืนยันยอดชำระ",
						"name" => "not_approve_payment",
						"value" => FALSE
					),
					array(
						"type" => "info",
						"value" => "&nbsp;"
					)
				),
				'search_onload' => TRUE
		);		
	}
	
	function get_pdf($quotation_rowid) {
		$this->load->model($this->modelName, 'm');
		$pass['data'] = $this->m->get_detail_report($quotation_rowid);

		for ($i=0; $i < count($pass['data']['details']); $i++) { 
			if( strpos($pass['data']['details'][$i]['title'],' (') > 0 && strpos($pass['data']['details'][$i]['title'],'เสื้อยืด') > 0){

			$pass['data']['details'][$i]['title'] = substr($pass['data']['details'][$i]['title'], 0, strpos($pass['data']['details'][$i]['title'],'('));
			}
		}
		if ($pass['data'] == FALSE) {
			echo "Error get report data: " . $this->m->error_message;
			return;
		} else {
			$_status_rowid = (isset($pass['data']['status_rowid'])) ? $pass['data']['status_rowid'] : 10;
			$html = '';
			mb_internal_encoding("UTF-8");
			$this->load->helper('exp_pdf_helper');
			
			$now = new DateTime();
			$strNow = $now->format('YmdHis');
			$file_name = 'quotation_' . $strNow . '.pdf';
			$this->load->library('mpdf8');
			$pass['title'] = 'ใบเสนอราคา';
	
			$html = $this->load->view('quotation/pdf/quotation', $pass, TRUE);

			if(!isset($pass['data']['is_vat']) || ($pass['data']['is_vat'] == 0 )){
				$temp_name = 'quotation-no-logo';
			}else{
				$temp_name = 'quotation';
			}

			if ($_status_rowid >= 40) {
				$this->mpdf8->exportMPDF_Template($html, $temp_name, $file_name);
			} else {
				$this->mpdf8->exportMPDF_Template_withWaterMark($html, $temp_name, $file_name);
			}
		}
	}

	function json_get_qonumber() {
		$_blnSuccess = FALSE;
		$_strError = '';
		$_strResult = '';
		$_arr = $this->__getAjaxPostParams();
		if (is_array($_arr)) {
			if (array_key_exists('start_date', $_arr) && (strlen($_arr['start_date']) == 10)) {
				$this->load->model($this->modelName, 'm');
				$_date = $this->m->_datFromPost($_arr['start_date']);
				$_str_date = '';
				if ($_date instanceof DateTime) $_str_date = $_date->format('Y/m/d');
				$_strResult = $this->m->getNextQONumber($_str_date);
				$_strError = $this->m->error_message;
				if ($_strError == '') {
					$_blnSuccess = TRUE;
				}

			}
		}
		$json = json_encode(
			array(
				'success' => $_blnSuccess,
				'error' => $_strError,
				'qo_number' => $_strResult
			)
		);
		header('content-type: application/json; charset=utf-8');
		echo isset($_GET['callback'])? "{" . $_GET['callback']. "}(".$json.")":$json;
	}
	
	function change_status_by_id() {
		$blnSuccess = FALSE;
		$strError = '';
		$this->load->model($this->modelName, 'm');
		$json_input_data = json_decode(trim(file_get_contents('php://input')), true); //get json
		$_arrData = (isset($json_input_data))?$json_input_data:$this->input->post(); //or post data submit
		if (isset($_arrData) && ($_arrData != FALSE)) {
			if (! isset($_arrData['rowid'])) $strError .= '"rowid" not found,';
			if (! isset($_arrData['status_rowid'])) $strError .= '"status_rowid" not found,';
			$_remark = FALSE;
			if (isset($_arrData['status_remark']) && (!(empty($_arrData['status_remark'])))) $_remark = $_arrData['status_remark'];
			if ($strError == '') {
				$this->m->change_status_by_id($_arrData['rowid'], $_arrData['status_rowid'], $_remark);
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

	function change_status_by_text() {
		$blnSuccess = FALSE;
		$strError = 'Unknown Error';
		$this->load->model($this->modelName, 'm');
		$json_input_data = json_decode(trim(file_get_contents('php://input')), true); //get json
		$_arrData = (isset($json_input_data))?$json_input_data:$this->input->post(); //or post data submit
		if (isset($_arrData) && ($_arrData != FALSE)) {
			$arrResult = $this->m->change_status_by_code($_arrData);
			$strError = $this->m->error_message;
		} else {
			$strError = 'Invalid parameters passed ( None )';
		}
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
	
	function insert_delivery_order() {
		$blnSuccess = FALSE;
		$strError = 'Unknown Error';
		$this->load->model($this->modelName, 'm');
		$json_input_data = json_decode(trim(file_get_contents('php://input')), true); //get json
		$_arrData = (isset($json_input_data))?$json_input_data:$this->input->post(); //or post data submit
		if (isset($_arrData) && ($_arrData != FALSE)) {
			$arrResult = $this->m->insert_delivery_order($_arrData);
			$strError = $this->m->error_message;
		} else {
			$strError = 'Invalid parameters passed ( None )';
		}
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
}