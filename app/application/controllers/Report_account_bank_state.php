<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report_account_bank_state extends MY_Ctrl_crud {
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
		// $this->_setController("order_status", "สถานะ", NULL, array("selectable"=>FALSE,"class"=>"center edit order_status_id","width"=>'80px',"order"=>0));
		$this->_setController("job_number", "เลขที่ใบสั่งตัด", NULL, array("selectable"=>TRUE,"width"=>80,"class"=>"center","order"=>1));
		$this->_setController("qo_number", "เลขที่ใบนำส่ง", NULL, array("selectable"=>TRUE,"width"=>80,"class"=>"center","order"=>2));
		$this->_setController("order_date", "x", NULL, array("selectable"=>TRUE,"default"=>FALSE,"width"=>60,"class"=>"center","order"=>3));
		$this->_setController("customer", "ชื่อลูกค้า", NULL, array("selectable"=>TRUE,"width"=>100,"order"=>4));
		$this->_setController("company", "ชื่อบริษัท", NULL, array("selectable"=>TRUE,"width"=>120,"order"=>5));
		// $this->_setController("category", "กลุ่มสินค้า", NULL, array("selectable"=>TRUE,"class"=>"center","width"=>60,"order"=>7));
		// $this->_setController("type", "ประเภท", NULL, array("selectable"=>TRUE,"class"=>"center","width"=>60,"order"=>8));
		// $this->_setController("fabric", "ชนิดผ้า", NULL, array("selectable"=>TRUE,"width"=>80,"order"=>9));
		// $this->_setController("sum_qty", "จำนวนตัว", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"center default_int","order"=>10));
			
		// $this->_setController("deposit_payment", "จำนวนเงินมัดจำ", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"default_number","order"=>41));
		$this->_setController("deposit_date", "วันที่ชำระมัดจำ", NULL, array("selectable"=>TRUE,"width"=>"80","class"=>"default_number","order"=>41));
		$this->_setController("deposit_payment_route", "ช่องทางการชำระมัดจำ", NULL, array("selectable"=>FALSE,"width"=>"120","class"=>"center","order"=>42));
		$this->_setController("disp_deposit_payment", "จำนวนเงินมัดจำ", NULL, array("selectable"=>FALSE,"width"=>"80","class"=>"center","order"=>43));

		// $this->_setController("close_payment_amount", "จำนวนเงินงวดสุดท้าย", NULL, array("selectable"=>FALSE,"width"=>"80","class"=>"default_number","order"=>44));
		$this->_setController("close_date", "วันที่ชำระงวดสุดท้าย", NULL, array("selectable"=>FALSE,"width"=>"80","class"=>"default_number","order"=>44));
		$this->_setController("close_payment_route", "ช่องทางการชำระงวดสุดท้าย", NULL, array("selectable"=>FALSE,"width"=>"120","class"=>"center","order"=>45));
		$this->_setController("disp_close_payment", "จำนวนเงินงวดสุดท้าย", NULL, array("selectable"=>FALSE,"width"=>"80","class"=>"center","order"=>46));
		
	//-- set special attributes
		
		$pass['left_panel'] = $this->add_view('_public/_search_panel', hlpr_arrSearchControlsParams_bank_state(), TRUE);
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
		// $pass['work_panel'] .= hlpr_htmlSummaryPanel($this->_arrDataViewFields, array(
		// 	array('sum_qty', '', 'total_price_sum')
		// 	, 'รวมราคาปักแยกตามร้าน' => array(array('ปักโรงงาน', 'ปักพี่แดง', 'ปักร็อค', 'ปักร้านหมวก'))
		// 	, 'รวมราคาสกรีนแยกตามร้าน' => array(array('สกรีนโรงงาน', 'สกรีน DTG', 'สกรีนปุ้ย', 'สกรีนพี่ณี', 'สกรีนร้านหมวก'))
		// 	, 'รวมราคาเฉลี่ยต่อตัว' => array(
		// 		array('ea_item_price', 'ea_weave_price', 'ea_screen_price')
		// 		, array('ea_other_price', '', 'total_price_each')
		// 	)
		// 	, array('total_price_sum_net', 'total_price_sum_vat', 'total_price_sum')
		// 	, array('deposit_payment', '', '')
		// ));
		$pass['title'] = "รายงาน Bank State";

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
			<th rowspan="2" style="padding:0px;">เลขที่ใบนำส่ง</th>
			<th rowspan="2">เลขที่ใบนำส่ง</th>
			<th rowspan="2">x</th>
			<th rowspan="2">ชื่อลูกค้า</th>
			<th rowspan="2">บริษัท</th>

			<th colspan="3" class="ui-state-default">มัดจำ</th>
			<th colspan="3" class="ui-state-default">งวดสุดท้าย</th>
		</tr>
		<tr>
			<th>วันที่ชำระ</th><th>ช่องทางการชำระ</th><th>จำนวนเงิน</th>
			<th>วันที่ชำระ</th><th>ช่องทางการชำระ</th><th>จำนวนเงิน</th>ป
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