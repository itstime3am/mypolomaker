<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report_status_and_deliver extends MY_Ctrl_order {
	function __construct() {
		parent::__construct();
		$this->modelName = 'Mdl_order_status';
	}
	
	public function index($product_type_id = 0) {
		$this->add_css(array(
			'public/css/jquery/ui/1.11.4/cupertino/jquery-ui.min.css',
			'public/css/jquery/dataTable/1.10.11/dataTables.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/buttons-1.1.2/buttons.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/fixedcolumns-3.2.1/fixedColumns.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/colreorder-1.3.1/colReorder.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/fixedheader-3.1.1/fixedHeader.jqueryui.min.css',
			/*'public/css/jquery/dataTable/extensions/responsive-2.0.2/responsive.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/scroller-1.4.1/scroller.jqueryui.min.css',
			'public/css/jquery/dataTable/extensions/select-1.1.2/select.jqueryui.min.css',*/
			'public/css/report/report_global.css',
			'public/css/report/report_status_and_deliver.css',
			'public/css/quotation/_payment.css'/*,
			array('a.DTTT_button_commit_page span { background: url(public/images/ok-grey.png) no-repeat bottom right;display: inline-block;height: 24px;line-height: 24px;padding-right: 30px; }', 'custom'),
			array('a.DTTT_button_commit_page:hover span { background: url(public/images/ok-green.png) no-repeat center right; }', 'custom')*/
		));
		$_allowEdit = "true";
		if (! $this->_blnCheckRight('edit')) {
			$this->add_css('td.edit { border:0px solid transparent; }', 'custom');
			$_allowEdit = "false";
		}
		$this->add_js(array(
			array("var _ALLOW_EDIT = " . $_allowEdit . ";", 'custom_init'),
			'public/js/jquery/1.11.0/jquery.js',
			'public/js/jquery/ui/1.10.4/jquery-ui.min.js',
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
			'public/js/jquery/dataTable/extensions/fixedcolumns-3.2.1/dataTables.fixedColumns.min.js',
			//'public/js/jquery/dataTable/extensions/fixedheader-3.1.1/dataTables.fixedHeader.min.js',
			'public/js/jquery/dataTable/extensions/colreorder-1.3.1/dataTables.colReorder.min.js',
			//'public/js/jquery/dataTable/extensions/responsive-2.0.2/dataTables.responsive.min.js',
			//'public/js/jquery/dataTable/extensions/responsive-2.0.2/responsive.jqueryui.min.js',
			//'public/js/jquery/dataTable/extensions/scroller-1.4.1/dataTables.scroller.min.js',
			//'public/js/jquery/dataTable/extensions/select-1.1.2/dataTables.select.min.js',
			'public/js/jquery/dataTable/extensions/type-detection/moment_2.8.4.min.js',
			'public/js/jquery/dataTable/extensions/type-detection/datetime-moment.js',
			'public/js/jquery/dataTable/extensions/type-detection/numeric-comma.js',
			'public/js/jquery/editable/1.7.1/jquery.editable.min.js',
			'public/js/jquery/ui/1.10.3/jquery-ui-autocomplete-combobox.js',
			'public/js/report/report_global.js',
			'public/js/report/report_status_and_deliver.js',
			'public/js/jsUtilities.js',
			'public/js/jsGlobalConstants.js'
		));
		
		$this->load->helper('report_controller_helper');

		//Get Default auto prepare controls (followed by model)
		$this->_prepareControlsDefault();
		//++ set special attributes
		$this->_setController("order_status_rowid", "", array("type"=>"hdn"));
		$this->_setController("order_status_id", "", NULL);
		$this->_setController("order_first_dtl", "", NULL);
		$this->_setController("order_price_sum_net", "", NULL);
		$this->_setController("order_price_sum_vat", "", NULL);
		$this->_setController("order_price_sum", "", NULL);
		$this->_setController("quotation_status", "สถานะ", NULL, array("selectable"=>FALSE,"class"=>"center order_status_id","width"=>'80px',"order"=>0));
		$this->_setController("job_number", "เลขที่ใบสั่งตัด", NULL, array("selectable"=>TRUE,"width"=>80,"class"=>"center","order"=>1));
		$this->_setController("disp_order_date", "วันที่", NULL, array("selectable"=>TRUE,"width"=>60,"class"=>"center","order"=>2));
		$this->_setController("sales_name", "เซลส์", NULL, array("selectable"=>TRUE,"width"=>100,"order"=>3));
		$this->_setController("customer", "ชื่อลูกค้า", NULL, array("selectable"=>TRUE,"width"=>120,"order"=>4));
		$this->_setController("company", "บริษัท", NULL, array("selectable"=>TRUE,"width"=>120,"class"=>"center","order"=>5));
		$this->_setController("category", "กลุ่มสินค้า", NULL, array("selectable"=>TRUE,"class"=>"center","width"=>60,"order"=>6));
		$this->_setController("type", "ประเภท", NULL, array("selectable"=>TRUE,"class"=>"center","width"=>60,"order"=>7));
		$this->_setController("fabric", "ชนิดผ้า", NULL, array("selectable"=>TRUE,"width"=>80,"order"=>8));
		$this->_setController("standard_pattern", "แพทเทิร์น", NULL, array("selectable"=>TRUE,"width"=>80,"class"=>"center","order"=>9));
		$this->_setController("main_color", "สีหลัก", NULL, array("selectable"=>TRUE,"width"=>80,"class"=>"center","order"=>10));
		$this->_setController("line_color", "สีวิ่งเส้น", NULL, array("selectable"=>TRUE,"width"=>80,"class"=>"center","order"=>11));
		$this->_setController("sub_color1", "สีรอง1", NULL, array("selectable"=>TRUE,"width"=>80,"class"=>"center","order"=>12));
		$this->_setController("sub_color2", "สีรอง2", NULL, array("selectable"=>TRUE,"width"=>80,"class"=>"center","order"=>13));
		$this->_setController("sub_color3", "สีรอง3", NULL, array("selectable"=>TRUE,"width"=>80,"class"=>"center","order"=>14));
		$this->_setController("option_hem_color", "สีกุ๊น", NULL, array("selectable"=>TRUE,"width"=>80,"class"=>"center","order"=>15));
		$this->_setController("is_screen", "สกรีน", NULL, array("selectable"=>TRUE,"class"=>"center","width"=>40,"order"=>16));
		$this->_setController("screen_status", "สถานะสกรีน", NULL, array("selectable"=>FALSE,"width"=>80,"class"=>"center screen_status_id","order"=>17));
		$this->_setController("is_weave", "ปัก", NULL, array("selectable"=>TRUE,"width"=>40,"class"=>"center","order"=>18));
		$this->_setController("weave_status", "สถานะปัก", NULL, array("selectable"=>FALSE,"width"=>80,"class"=>"center weave_status_id","order"=>19));
		$this->_setController("disp_due_date", "กำหนดส่ง", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"center","order"=>20));
		$this->_setController("disp_deliver_date", "วันที่ส่งลูกค้า", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"center","order"=>21));
		$this->_setController("sum_qty", "จำนวนตัว", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"center default_int","order"=>22));
			
		$this->_setController("ปักโรงงาน", "ปักโรงงาน", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"default_number","order"=>23));
		$this->_setController("ปักพี่แดง", "ปักพี่แดง", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"default_number","order"=>24));
		$this->_setController("ปักร็อค", "ปักร็อค", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"default_number","order"=>25));
		$this->_setController("ปักร้านหมวก", "ปักร้านหมวก", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"default_number","order"=>26));

		$this->_setController("สกรีนโรงงาน", "สกรีนโรงงาน", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"default_number","order"=>27));
		$this->_setController("สกรีน DTG", "สกรีน DTG", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"default_number","order"=>28));
		$this->_setController("สกรีนปุ้ย", "สกรีนปุ้ย", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"default_number","order"=>29));
		$this->_setController("สกรีนพี่ณี", "สกรีนพี่ณี", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"default_number","order"=>30));
		$this->_setController("สกรีนร้านหมวก", "สกรีนร้านหมวก", NULL, array("selectable"=>TRUE,"width"=>"85","class"=>"default_number","order"=>31));
		
		$this->_setController("ea_item_price", "เสื้อรวม", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"default_number","order"=>32));
		$this->_setController("ea_screen_price", "สกรีนรวม", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"default_number","order"=>33));
		$this->_setController("ea_weave_price", "ปักรวม", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"default_number","order"=>34));
		$this->_setController("ea_other_price", "อื่นๆรวม", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"default_number","order"=>35));
		$this->_setController("total_price_each", "ราคารวมต่อตัว", NULL, array("selectable"=>TRUE,"width"=>"90","class"=>"default_number","order"=>36));
		$this->_setController("total_price_sum_net", "ราคารวม", NULL, array("selectable"=>TRUE,"width"=>"90","class"=>"default_number","order"=>37));
		$this->_setController("total_price_sum_vat", "VAT", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"default_number","order"=>38));
		$this->_setController("total_price_sum", "รวมทั้งสิ้น", NULL, array("selectable"=>TRUE,"width"=>"100","class"=>"default_number","order"=>39));
		$this->_setController("promotion", "PROMOTION", NULL, array("selectable"=>TRUE,"width"=>"100","class"=>"center","order"=>40));

		$this->_setController("deposit_payment_route", "ช่องทางชำระมัดจำ", NULL, array("selectable"=>FALSE,"width"=>"100","class"=>"center","order"=>42));
		$this->_setController("disp_deposit_date", "วันที่ชำระมัดจำ", NULL, array("selectable"=>FALSE,"width"=>"100","class"=>"center","order"=>43));
		
		$this->_setController("close_payment_route", "ช่องทางชำระงวดสุดท้าย", NULL, array("selectable"=>FALSE,"width"=>"100","class"=>"center","order"=>45));
		$this->_setController("disp_close_date", "วันที่ชำระงวดสุดท้าย", NULL, array("selectable"=>FALSE,"width"=>"100","class"=>"center","order"=>46));
		
		$this->_setController("close_payment_wht", "WHT จำนวน", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"default_numer","order"=>47));

		$this->_setController("deliver_remark", "หมายเหตุ", NULL, array("selectable"=>FALSE,"width"=>"120","class"=>"center deliver_remark","order"=>48));
		$this->_setController("status_deliver_date", "วันที่ส่งจริง", NULL, array("selectable"=>FALSE,"width"=>"80","class"=>"center status_deliver_date","order"=>49));

		//$this->_setController("total_deposit_payment", "", NULL);
		//$this->_setController("total_payment", "", NULL);
		$this->_setController("disp_deposit_payment", "", NULL);
		$this->_setController("disp_close_payment", "", NULL);
		$this->_setController("arr_deposit_log", "", NULL);
		$this->_setController("arr_payment_log", "", NULL);
		//-- set special attributes

		$_custom_columns = array(
			array(
				"column" => '{"sTitle":"มัดจำ", "sClass":"cls-payment-dlg right","sWidth":"120","mData":"rowid","mRender": function(data,type,full) { return \'<span class="cls-spn-payment">\' + full.disp_deposit_payment + \'</span><img class="tblButton" command="cmd_open_deposit_dialog" src="public/images/forms.png" title="รายการชำระเงินมัดจำ" />\';}, "bSortable": true}'
				, "order" => 41
			)
			, array(
				"column" => '{"sTitle":"งวดสุดท้าย", "sClass":"cls-payment-dlg right","sWidth":"120","mData":"rowid","mRender": function(data,type,full) { return \'<span class="cls-spn-payment">\' + full.disp_close_payment + \'</span><img class="tblButton" command="cmd_open_payment_dialog" src="public/images/forms.png" title="รายการชำระเงิน" />\';}, "bSortable": true}'
				, "order" => 44
			)
		);
/*
		$_custom_columns = array(
			array(
				"column" => '{"sTitle":"มัดจำ", "sClass":"cls-payment-dlg right","sWidth":"80px","mData":"rowid","mRender": function(data,type,full) { return \'<span class="cls-spn-payment">\' + formatNumber(full.total_deposit_payment) + \'</span><img class="tblButton" command="cmd_open_deposit_dialog" src="public/images/forms.png" title="รายการชำระเงินมัดจำ" />\';}, "bSortable": true}'
				, "order" => 41
			)
			, array(
				"column" => '{"sTitle":"งวดสุดท้าย", "sClass":"cls-payment-dlg right","sWidth":"80px","mData":"rowid","mRender": function(data,type,full) { return \'<span class="cls-spn-payment">\' + formatNumber(full.total_payment) + \'</span><img class="tblButton" command="cmd_open_payment_dialog" src="public/images/forms.png" title="รายการชำระเงิน" />\';}, "bSortable": true}'
				, "order" => 44
			)
		);
*/		
		$pass['left_panel'] = $this->add_view('_public/_search_panel', hlpr_arrSearchControlsParams(), TRUE);
		$pass['work_panel'] = $this->add_view(
			'_public/_list', 
			array(
				'index'=>0,
				'list_editable'=>FALSE,
				'list_viewable'=>FALSE,
				'list_deleteable'=>FALSE,
				'list_insertable'=>FALSE,
				'dataview_fields'=>$this->_arrDataViewFields,
				'custom_columns' => $_custom_columns
			), TRUE
		);
		$pass['work_panel'] .= hlpr_htmlSummaryPanel($this->_arrDataViewFields, array(
			array('sum_qty', '', 'total_price_sum')
			, 'รวมราคาปักแยกตามร้าน' => array(array('ปักโรงงาน', 'ปักพี่แดง', 'ปักร็อค', 'ปักร้านหมวก'))
			, 'รวมราคาสกรีนแยกตามร้าน' => array(array('สกรีนโรงงาน', 'สกรีน DTG', 'สกรีนปุ้ย', 'สกรีนพี่ณี', 'สกรีนร้านหมวก'))
			, 'รวมราคาเฉลี่ยต่อตัว' => array(
				array('ea_item_price', 'ea_weave_price', 'ea_screen_price')
				, array('ea_other_price', '', 'total_price_each')
			)
			, array('total_price_sum_net', 'total_price_sum_vat', 'total_price_sum')
			, array('deposit_payment', '', '')
		));

		$_strTblAttr = '';
		if ($this->_blnCheckRight('edit', 'order')) $_strTblAttr .= ' payment_edit_url="'.$this->page_name.'/commit_payment_log" payment_delete_url="'.$this->page_name.'/delete_payment_log" ';
		if ($this->_blnCheckRight('edit')) $_strTblAttr .= ' payment_approve_url="'.$this->page_name.'/approve_payment_log" ';
		$pass['work_panel'] .= $this->add_view('_public/_list_payment_dialog', array( "index"=>3, "table_attr"=>$_strTblAttr ), TRUE);

		$this->load->model('mdl_master_table', 'mst');		
		$_arrMaster = $this->_prepareSelectOptions(array(
			'status'=>array('table_name'=>'m_order_status','where'=>"is_cancel = 0", 'order_by'=>'sort_index')
			,'ws_status'=>array('table_name'=>'m_order_ws_status','where'=>"is_cancel = 0", 'order_by'=>'sort_index')
			,'deposit_payment_route'=>array('table_name'=>'m_order_payment_route','where'=>"is_cancel = 0 AND is_deposit = 1", 'order_by'=>'sort_index')
			,'close_payment_route'=>array('table_name'=>'m_order_payment_route','where'=>"is_cancel = 0 AND is_close = 1", 'order_by'=>'sort_index')
			,'payment_status'=>array('table_name'=>'m_order_payment_status','where'=>"is_cancel = 0", 'order_by'=>'sort_index')
		));
		$_toDTEdit = array();
		foreach ($_arrMaster as $_key=>$_arrData) {
			if (is_array($_arrData)) {
				$_toDTEdit[$_key] = array("0"=>"");
				foreach ($_arrData as $_arrRow) {
					if (isset($_arrRow['rowid']) && isset($_arrRow['name']) && ($_arrRow['rowid'] != '') && ($_arrRow['name'] != '')) $_toDTEdit[$_key][$_arrRow['rowid']] = $_arrRow['name'];
				}
			}
		}
		$this->add_js('var _MASTER_DT_EDITABLE = ' . json_encode($_toDTEdit) . ";\n", 'custom_init');
		//$_str_deposit_route_options = json_encode(hlpr_arrListDepositRoute(1));
		//$this->add_js('var _objDepositRouteOptions = ' . $_str_deposit_route_options . ';', 'custom_init');
		$this->add_js("var _TMPL_TBL_SEARCH = '" . $this->strGetTableTemplate() . "';", "custom_init");

		$pass['title'] = "รายงานสถานะ + รอส่ง";
		$pass["autosearch"] = FALSE;
		$this->_DISABLE_ON_LOAD_SEARCH = True;
		$this->add_view_with_script_header('_public/_template_main', $pass);
	}
	function strGetTableTemplate() {
		$_tmpl = <<<TBL
<table id="tblSearchResult" class="cls-tbl-list">
	<thead>
		<tr>
			<th rowspan="2">สถานะ</th>
			<th rowspan="2">เลขที่ใบสั่งตัด</th>
			<th rowspan="2">วันที่</th>
			<th rowspan="2">เซลส์</th>
			<th rowspan="2">ชื่อลูกค้า</th>
			<th rowspan="2">บริษัท</th>
			<th rowspan="2">กลุ่มสินค้า</th>
			<th rowspan="2">ประเภท</th>
			<th rowspan="2">ชนิดผ้า</th>
			<th colspan="7">รายละเอียด</th>
			<th colspan="2">สกรีน</th>
			<th colspan="2">ปัก</th>
			<th rowspan="2">กำหนดส่ง</th>
			<th rowspan="2">วันที่ส่งลูกค้า</th>
			<th rowspan="2">จำนวนตัว</th>
			<th colspan="4">ปักแยกตามร้าน</th>
			<th colspan="5">สกรีนแยกตามร้าน</th>
			<th colspan="5">ราคาเฉลี่ยต่อตัว</th>
			<th rowspan="2">ราคารวม</th>
			<th rowspan="2">VAT</th>
			<th rowspan="2">รวมทั้งสิ้น</th>
			<th rowspan="2">PROMOTION</th>
			<th rowspan="2">มัดจำ</th>
			<th rowspan="2">ช่องทางชำระมัดจำ</th>
			<th rowspan="2">วันที่ชำระมัดจำ</th>
			<th rowspan="2">งวดสุดท้าย</th>
			<th rowspan="2">ช่องทางชำระงวดสุดท้าย</th>
			<th rowspan="2">วันที่ชำระงวดสุดท้าย</th>
			<th rowspan="2">WHT จำนวน</th>
			<th rowspan="2">หมายเหตุ</th>
			<th rowspan="2">วันที่ส่งจริง</th>
		</tr>
		<tr>
			<th>แพทเทิร์น</th><th>สีหลัก</th><th>สีวิ่งเส้น</th><th>สีรอง1</th><th>สีรอง2</th><th>สีรอง3</th><th>สีกุ๊น</th>
			<th>มี</th><th>สถานะ</th>
			<th>มี</th><th>สถานะ</th>
			<th>ปักโรงงาน</th><th>ปักพี่แดง</th><th>ปักร็อค</th><th>ปักร้านหมวก</th>
			<th>สกรีนโรงงาน</th><th>สกรีน DTG</th><th>สกรีนปุ้ย</th><th>สกรีนพี่ณี</th><th>สกรีนร้านหมวก</th>
			<th>เสื้อรวม</th><th>สกรีนรวม</th><th>ปักรวม</th><th>อื่นๆรวม</th><th>ราคารวมต่อตัว</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

TBL;
		return preg_replace('/(\n|\t)+/', '', $_tmpl);
	}

	function commit() {
		$_blnSuccess = FALSE;
		$_strError = '';
		$_strMessage = '';
		$json_input_data = json_decode(trim(file_get_contents('php://input')), true); //get json
		$_arrData = (isset($json_input_data))?$json_input_data:$this->input->post(); //or post data submit
		if (isset($_arrData) && ($_arrData != FALSE)) {
			$this->load->model($this->modelName, 'mcommit');
			if ($this->mcommit->commit($_arrData, 1)) {
				$_strMessage = $this->mcommit->success_rows . ' row(s) committed.';
				$_blnSuccess = TRUE;
			} else {
				$_strError = $this->mcommit->error_message;
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
}