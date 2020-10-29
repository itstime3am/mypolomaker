<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report_account_all extends MY_Ctrl_crud {
	function __construct() {
		parent::__construct();
		$this->modelName = 'Mdl_order_status';
	}
	
	public function index($product_type_id = 0) {
		$this->add_css(array(
			'public/css/jquery/ui/1.10.4/cupertino/jquery-ui.min.css',
			'public/css/jquery/dataTable/1.9.4/dataTables_jui.css',
			'public/css/jquery/dataTable/TableTools/2.1.5/TableTools_JUI.css',
			'public/css/jquery/dataTable/FixedColumns/3.0.2/dataTables.fixedColumns.min.css',
			'public/css/report/report_global.css',
			array('a.DTTT_button_commit_page span { background: url(public/images/ok-grey.png) no-repeat bottom right;display: inline-block;height: 24px;line-height: 24px;padding-right: 30px; }', 'custom'),
			array('a.DTTT_button_commit_page:hover span { background: url(public/images/ok-green.png) no-repeat center right; }', 'custom')
		));
		$_allowEdit = "true";
		if (! $this->_blnCheckRight('edit')) {
			$this->add_css('td.edit {border:0px solid transparent;}', 'custom');
			$_allowEdit = "false";
		}
		$this->add_js(array(
			array("var _ALLOW_EDIT = " . $_allowEdit . ";", 'custom_init'),
			'public/js/jquery/1.11.0/jquery.js',
			'public/js/jquery/ui/1.10.4/jquery-ui.min.js',
			'public/js/jquery/ui/1.10.3/jquery-ui-autocomplete-combobox.js',
			'public/js/jquery/dataTable/1.9.4/jquery.dataTables.min.js',
			'public/js/jquery/dataTable/TableTools/2.1.5/ZeroClipboard.js',
			'public/js/jquery/dataTable/TableTools/2.2.1/dataTables.tableTools.min.js',
			'public/js/jquery/editable/1.7.1/jquery.editable.min.js',				
			'public/js/jquery/dataTable/FixedColumns/3.0.2/dataTables.fixedColumns.min.js',
			'public/js/report/report_global.js',
			'public/js/report/report_account_all.js',
			'public/js/jsUtilities.js',
			'public/js/jsGlobalConstants.js'
		));
		
		$this->load->helper('report_controller_helper');

		//Get Default auto prepare controls (followed by model)
		$this->_prepareControlsDefault();
		//++ set special attributes
		$this->_setController("order_status_rowid", "", array("type"=>"hdn"));
		$this->_setController("order_status_id", "", NULL);
		$this->_setController("order_status", "สถานะ", NULL, array("selectable"=>FALSE,"class"=>"center edit order_status_id","width"=>'80px',"order"=>0));
		$this->_setController("job_number", "เลขที่ใบสั่งตัด", NULL, array("selectable"=>TRUE,"width"=>80,"class"=>"center","order"=>1));
		$this->_setController("order_date", "วันที่", NULL, array("selectable"=>TRUE,"default"=>FALSE,"width"=>60,"class"=>"center","order"=>2));
		$this->_setController("sales_name", "เซลส์", NULL, array("selectable"=>TRUE,"width"=>100,"order"=>3));
		$this->_setController("customer", "ชื่อลูกค้า", NULL, array("selectable"=>TRUE,"width"=>120,"order"=>4));
		$this->_setController("company", "บริษัท", NULL, array("selectable"=>TRUE,"width"=>120,"class"=>"center","order"=>5));
		$this->_setController("category", "กลุ่มสินค้า", NULL, array("selectable"=>TRUE,"class"=>"center","width"=>60,"order"=>6));
		$this->_setController("type", "ประเภท", NULL, array("selectable"=>TRUE,"class"=>"center","width"=>60,"order"=>7));
		$this->_setController("fabric", "ชนิดผ้า", NULL, array("selectable"=>TRUE,"width"=>80,"order"=>8));
		$this->_setController("sum_qty", "จำนวนตัว", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"center default_int","order"=>9));
			
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
		$this->_setController("option_promotion", "PROMOTION", NULL, array("selectable"=>TRUE,"width"=>"100","class"=>"center","order"=>40));

		$this->_setController("deposit_payment", "จำนวนเงินมัดจำ", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"default_number edit deposit_payment","order"=>41));
		$this->_setController("deposit_route", "ช่องทางการชำระมัดจำ", NULL, array("selectable"=>FALSE,"width"=>"90","class"=>"center edit deposit_route_id","order"=>42));
		$this->_setController("deposit_date", "วันที่ชำระมัดจำ", NULL, array("selectable"=>FALSE,"width"=>"80","class"=>"center edit deposit_date","order"=>43));

		$this->_setController("close_payment_amount", "จำนวนเงินงวดสุดท้าย", NULL, array("selectable"=>FALSE,"width"=>"80","class"=>"default_number edit close_payment_amount","order"=>44));
		$this->_setController("close_payment_route", "ช่องทางการชำระงวดสุดท้าย", NULL, array("selectable"=>FALSE,"width"=>"90","class"=>"center edit close_payment_route_id","order"=>45));
		$this->_setController("close_payment_date", "วันที่ชำระงวดสุดท้าย", NULL, array("selectable"=>FALSE,"width"=>"80","class"=>"center edit close_payment_date","order"=>46));
		$this->_setController("close_payment_wht", "WHT จำนวน", NULL, array("selectable"=>FALSE,"width"=>"80","class"=>"center edit close_payment_wht","order"=>47));

		$this->_setController("account_remark", "ตรวจสอบการเงิน", NULL, array("selectable"=>FALSE,"width"=>"120","class"=>"center edit account_remark","order"=>48));
		$this->_setController("deliver_remark", "หมายเหตุ", NULL, array("selectable"=>FALSE,"width"=>"120","class"=>"center edit deliver_remark","order"=>49));
		$this->_setController("status_deliver_date", "วันที่ส่งจริง", NULL, array("selectable"=>FALSE,"width"=>"80","class"=>"center edit status_deliver_date","order"=>50));
		//-- set special attributes
		
		$pass['left_panel'] = $this->add_view('_public/_search_panel', hlpr_arrSearchControlsParams(), TRUE);
		$pass['work_panel'] = $this->add_view(
			'_public/_list', 
			array(
				'index'=>0,
				'list_editable'=>FALSE,
				'list_viewable'=>FALSE,
				'list_deleteable'=>FALSE,
				'list_insertable'=>FALSE,
				'dataview_fields'=>$this->_arrDataViewFields
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
		$pass['title'] = "รายงานบัญชี";

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
			<th rowspan="2">จำนวนตัว</th>
			<th colspan="4" class="ui-state-default">ปักแยกตามร้าน</th>
			<th colspan="5" class="ui-state-default">สกรีนแยกตามร้าน</th>
			<th colspan="5" class="ui-state-default">ราคาเฉลี่ยต่อตัว</th>
			<th rowspan="2">ราคารวม</th>
			<th rowspan="2">VAT</th>
			<th rowspan="2">รวมทั้งสิ้น</th>
			<th rowspan="2">PROMOTION</th>
			<th colspan="3" class="ui-state-default">มัดจำ</th>
			<th colspan="4" class="ui-state-default">งวดสุดท้าย</th>
			<th rowspan="2">ตรวจสอบการเงิน</th>
			<th rowspan="2">หมายเหตุ</th>
			<th rowspan="2">วันที่ส่งจริง</th>
		</tr>
		<tr>
			<th>ปักโรงงาน</th><th>ปักพี่แดง</th><th>ปักร็อค</th><th>ปักร้านหมวก</th>
			<th>สกรีนโรงงาน</th><th>สกรีน DTG</th><th>สกรีนปุ้ย</th><th>สกรีนพี่ณี</th><th>สกรีนร้านหมวก</th>
			<th>เสื้อรวม</th><th>สกรีนรวม</th><th>ปักรวม</th><th>อื่นๆรวม</th><th>ราคารวมต่อตัว</th>
			<th>จำนวนเงิน</th><th>ช่องทางการชำระ</th><th>วันที่ชำระ</th>
			<th>จำนวนเงิน</th><th>ช่องทางการชำระ</th><th>วันที่ชำระ</th><th>WHT จำนวน</th>
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
			if ($this->mcommit->commit($_arrData, 3)) {
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