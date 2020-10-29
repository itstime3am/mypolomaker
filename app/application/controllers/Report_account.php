<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report_account extends MY_Ctrl_crud {
	function __construct() 
	{
		parent::__construct();
		$this->modelName = 'Mdl_report_update';
	}
	
	public function index($product_type_id = 0) {
		$this->_PRODUCT_TYPE_ID = is_numeric($product_type_id)?(int)$product_type_id:0;
		$this->add_css(
			array(
				'public/css/jquery/ui/1.10.4/cupertino/jquery-ui.min.css',
				'public/css/jquery/dataTable/1.9.4/dataTables_jui.css',
				'public/css/jquery/dataTable/TableTools/2.1.5/TableTools_JUI.css',
				'public/css/jquery/dataTable/FixedColumns/3.0.2/dataTables.fixedColumns.min.css',
				'public/css/report/report_global.css',
				array('a.DTTT_button_commit_page span { background: url(public/images/ok-grey.png) no-repeat bottom right;display: inline-block;height: 24px;line-height: 24px;padding-right: 30px; }', 'custom'),
				array('a.DTTT_button_commit_page:hover span { background: url(public/images/ok-green.png) no-repeat center right; }', 'custom'),
			)
		);
		$_allowEdit = "true";
		if (! $this->_blnCheckRight('edit')) {
			$this->add_css('td.edit {border:0px solid transparent;}', 'custom');
			$_allowEdit = "false";
		}
		$this->add_js(
			array(
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
				'public/js/report/report_account.js',
				'public/js/jsUtilities.js',
				'public/js/jsGlobalConstants.js'
			)
		);
		
		$this->load->helper('report_controller_helper');

		//Get Default auto prepare controls (followed by model)
		$this->_prepareControlsDefault();
		//++ set special attributes
		$this->_setController("order_status_rowid", "", array());
		$this->_setController("order_status", "สถานะ", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","width"=>'80px',"order"=>0));
		$this->_setController("job_number", "เลขที่ใบสั่งตัด", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>1));
		$this->_setController("sales_name", "เซลส์", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>3));
		$this->_setController("customer", "ชื่อลูกค้า", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>4));
		$this->_setController("company", "บริษัท", array(), array("selectable"=>TRUE,"order"=>5));
		$this->_setController("category", "กลุ่มสินค้า", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>6));
		$this->_setController("type", "ประเภทสินค้า", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>7));
		if ($this->_PRODUCT_TYPE_ID == 5) {
			$this->_setController("detail1", "", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>8));

			$this->_setController("ปักโรงงาน", "ปักโรงงาน", array(), array("selectable"=>TRUE,"width"=>"80","default"=>TRUE,"class"=>"right default_number","order"=>11));
			$this->_setController("ปักร้านหมวก", "ปักร้านหมวก", array(), array("selectable"=>TRUE,"width"=>"80","default"=>TRUE,"class"=>"right default_number","order"=>12));
			$this->_setController("สกรีนโรงงาน", "สกรีนโรงงาน", array(), array("selectable"=>TRUE,"width"=>"80","default"=>TRUE,"class"=>"right default_number","order"=>14));
			$this->_setController("สกรีนร้านหมวก", "สกรีนร้านหมวก", array(), array("selectable"=>TRUE,"width"=>"80","default"=>TRUE,"class"=>"right default_number","order"=>16));
		} else if ($this->_PRODUCT_TYPE_ID == 6) {
			$this->_setController("detail1", "", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>8));

			$this->_setController("ปักโรงงาน", "ปักโรงงาน", array(), array("selectable"=>TRUE,"width"=>"80","default"=>TRUE,"class"=>"right default_number","order"=>11));
			$this->_setController("ปักร้านแจ็คเก็ต", "ปักร้านแจ็คเก็ต", array(), array("selectable"=>TRUE,"width"=>"80","default"=>TRUE,"class"=>"right default_number","order"=>12));
			$this->_setController("สกรีนโรงงาน", "สกรีนโรงงาน", array(), array("selectable"=>TRUE,"width"=>"80","default"=>TRUE,"class"=>"right default_number","order"=>13));
			$this->_setController("สกรีนร้านแจ็คเก็ต", "สกรีนร้านแจ็คเก็ต", array(), array("selectable"=>TRUE,"width"=>"80","default"=>TRUE,"class"=>"right default_number","order"=>14));
			$this->_setController("สกรีนปุ้ย", "สกรีนปุ้ย", array(), array("selectable"=>TRUE,"width"=>"80","default"=>TRUE,"class"=>"right default_number","order"=>15));
			$this->_setController("สกรีนพี่นี", "สกรีนพี่นี", array(), array("selectable"=>TRUE,"width"=>"80","default"=>TRUE,"class"=>"right default_number","order"=>16));
		} else {
			$this->_setController("fabric", "ชนิดผ้า", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>8));
			
			$this->_setController("ปักโรงงาน", "ปักโรงงาน", array(), array("selectable"=>TRUE,"width"=>"80","default"=>TRUE,"class"=>"right default_number","order"=>11));
			$this->_setController("ปักพี่แดง", "ปักพี่แดง", array(), array("selectable"=>TRUE,"width"=>"80","default"=>TRUE,"class"=>"right default_number","order"=>12));
			$this->_setController("ปักร็อค", "ปักร็อค", array(), array("selectable"=>TRUE,"width"=>"80","default"=>TRUE,"class"=>"right default_number","order"=>13));
			$this->_setController("สกรีนโรงงาน", "สกรีนโรงงาน", array(), array("selectable"=>TRUE,"width"=>"80","default"=>TRUE,"class"=>"right default_number","order"=>14));
			$this->_setController("สกรีน DTG", "สกรีน DTG", array(), array("selectable"=>TRUE,"width"=>"80","default"=>TRUE,"class"=>"right default_number","order"=>15));
			$this->_setController("สกรีนปุ้ย", "สกรีนปุ้ย", array(), array("selectable"=>TRUE,"width"=>"80","default"=>TRUE,"class"=>"right default_number","order"=>16));
			$this->_setController("สกรีนพี่นี", "สกรีนพี่นี", array(), array("selectable"=>TRUE,"width"=>"80","default"=>TRUE,"class"=>"right default_number","order"=>17));
		}
		$this->_setController("status_deliver_date", "วันที่ส่งจริง", array(), array("selectable"=>TRUE,"default"=>TRUE,"width"=>"80","order"=>9));
		$this->_setController("sum_qty", "จำนวนตัว", array(), array("selectable"=>TRUE,"width"=>"80","default"=>TRUE,"class"=>"right default_int","order"=>10));
/*		
		$this->_setController("neck_type", "แบบคอ", array(), array("selectable"=>TRUE,"order"=>9));
		$this->_setController("base_pattern", "แบบทรง", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>10));
		$this->_setController("standard_pattern", "แบบเสื้อ", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>11));
		$this->_setController("color", "สีหลัก", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>12));
		$this->_setController("color_add1", "สีตัดต่อ", array(), array("selectable"=>TRUE,"order"=>13));
		$this->_setController("is_screen", "สกรีน", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>14));
		$this->_setController("screen_status", "สถานะสกรีน", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center edit screen_status_id","order"=>15));
		$this->_setController("is_weave", "ปัก", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>16));
		$this->_setController("weave_status", "สถานะปัก", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center edit weave_status_id","order"=>17));
		$this->_setController("due_date", "กำหนดส่ง", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>18));
		$this->_setController("deliver_date", "วันที่ส่งลูกค้า", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>19));
*/
		$this->_setController("ea_item_price", "ราคาเสื้อเฉลี่ย", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"default_number","order"=>21));
		$this->_setController("ea_screen_price", "ราคาสกรีนเฉลี่ย", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"default_number","order"=>22));
		$this->_setController("ea_weave_price", "ราคาปักเฉลี่ย", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"default_number","order"=>23));
		$this->_setController("ea_other_price", "ราคาอื่นๆเฉลี่ย", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"default_number","order"=>24));
		$this->_setController("total_price_each", "ราคารวมเฉลี่ย", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"default_number","order"=>25));
		$this->_setController("total_price_sum_net", "ราคารวม", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"default_number","order"=>26));
		$this->_setController("total_price_sum_vat", "VAT", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"default_number","order"=>27));
		$this->_setController("total_price_sum", "รวมทั้งสิ้น", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"default_number","order"=>28));
		$this->_setController("deposit_payment", "จำนวนเงินมัดจำ", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"default_number","order"=>29));
		$this->_setController("deposit_route", "ช่องทางการชำระมัดจำ", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>30));
		$this->_setController("deposit_date", "วันที่ชำระมัดจำ", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>31));
		$this->_setController("close_payment_amount", "จำนวนเงินงวดสุดท้าย", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"default_number","order"=>32));
		$this->_setController("close_payment_route", "ช่องทางการชำระงวดสุดท้าย", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>33));
		$this->_setController("close_payment_date", "วันที่ชำระงวดสุดท้าย", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>34));
		$this->_setController("close_payment_wht", "WHT จำนวน", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>35));
		$this->_setController("payment_status", "ตรวจสอบการชำระเงิน", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center edit payment_status_id","order"=>36));
		$this->_setController("account_remark", "หมายเหตุ", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center edit account_remark","order"=>37));
		//-- set special attributes
		
		$pass['left_panel'] = $this->add_view('_public/_search_panel', hlpr_arrSearchControlsParams(-1, $this->_PRODUCT_TYPE_ID), TRUE);
		$pass['work_panel'] = $this->add_view(
			'_public/_list', 
			array(
				'index'=>0,
				'list_editable'=>FALSE,
				'list_viewable'=>FALSE,
				'list_deleteable'=>FALSE,
				'list_insertable'=>FALSE,
				'dataview_fields'=>$this->_arrDataViewFields
			), 
			TRUE
		);
		$pass['work_panel'] .= hlpr_htmlSummaryPanel($this->_arrDataViewFields, array(
			array('sum_qty', '', 'total_price_sum')
			, 'รวมราคาปักแยกตามร้าน' => array(array('ปักโรงงาน', 'ปักพี่แดง', 'ปักร็อค'))
			, 'รวมราคาสกรีนแยกตามร้าน' => array(array('สกรีน DTG', 'สกรีนปุ้ย', 'สกรีนพี่นี'))
			, 'รวมราคาเฉลี่ยต่อตัว' => array(
				array('ea_item_price', 'ea_weave_price', 'ea_screen_price')
				, array('ea_other_price', '', 'total_price_each')
			)
			, array('total_price_sum_net', 'total_price_sum_vat', 'total_price_sum')
			, array('deposit_payment', '', 'close_payment_amount')
		));
		
		$pass['title'] = "รายงานบัญชี";
		
		$this->add_js("var _TMPL_TBL_SEARCH = '" . $this->strGetTableTemplate() . "';", "custom_init");

		$this->add_view_with_script_header('_public/_template_main', $pass);
	}
	
	function strGetTableTemplate() {
		$_str1 = '';
		$_arrDW = array();
		$_arrDS = array();
		if ($this->_PRODUCT_TYPE_ID == 5) { //หมวก
			$_str1 = "ชนิดหมวก";
			$_arrDW = array('ปักโรงงาน', 'ปักร้านหมวก');
			$_arrDS = array('สกรีนโรงงาน', 'สกรีนร้านหมวก');
		} else if ($this->_PRODUCT_TYPE_ID == 6) {  //jacket
			$_str1 = "แบบผ้า";
			$_arrDW = array('ปักโรงงาน', 'ปักร้านแจ็คเก็ต');
			$_arrDS = array('สกรีนโรงงาน', 'สกรีนร้านแจ็คเก็ต', 'สกรีนปุ้ย', 'สกรีนพี่นี');
		} else { // others
			$_str1 = "ชนิดผ้า";
			$_arrDW = array('ปักโรงงาน', 'ปักพี่แดง', 'ปักร็อค');
			$_arrDS = array('สกรีนโรงงาน', 'สกรีน DTG', 'สกรีนปุ้ย', 'สกรีนพี่นี');
		}
		$_strCSW = count($_arrDW);
		$_strCSS = count($_arrDS);
		$_tmpl = <<<TBL
<table id="tblSearchResult" class="cls-tbl-list">
	<thead>
		<tr>
			<th rowspan="2">สถานะ</th>
			<th rowspan="2">เลขที่ใบสั่งตัด</th>
			<th rowspan="2">เซลส์</th>
			<th rowspan="2">ชื่อลูกค้า</th>
			<th rowspan="2">บริษัท</th>
			<th rowspan="2">กลุ่มสินค้า</th>
			<th rowspan="2">ประเภทสินค้า</th>
			<th rowspan="2">$_str1</th>
			<th rowspan="2">วันที่ส่งจริง</th>
			<th rowspan="2">จำนวนตัว</th>
			<th colspan="$_strCSW" class="ui-state-default">ปักแยกตามร้าน</th>
			<th colspan="$_strCSS" class="ui-state-default">สกรีนแยกตามร้าน</th>
			<th colspan="5" class="ui-state-default">ราคาเฉลี่ยต่อตัว</th>
			<th rowspan="2">ราคารวม</th>
			<th rowspan="2">VAT</th>
			<th rowspan="2">รวมทั้งสิ้น</th>
			<th colspan="3" class="ui-state-default">มัดจำ</th>
			<th colspan="4" class="ui-state-default">งวดสุดท้าย</th>
			<th rowspan="2">ตรวจสอบการชำระเงิน</th>
			<th rowspan="2">หมายเหตุ</th>
		</tr>
		<tr>
TBL;
			$_tmpl .= '<th>' . join('</th><th>', $_arrDW) . '</th>';
			$_tmpl .= '<th>' . join('</th><th>', $_arrDS) . '</th>';
			$_tmpl .= <<<TBL

			<th>เสื้อรวม</th><th>สกรีนรวม</th><th>ปักรวม</th><th>อื่นๆรวม</th><th>ราคารวม</th>
			<th>จำนวนเงิน</th><th>ช่องทางการชำระเงิน</th><th>วันที่ชำระเงิน</th>
			<th>จำนวนเงิน</th><th>ช่องทางการชำระเงิน</th><th>วันที่ชำระเงิน</th><th>WHT จำนวน</th>
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
/*
	function _arrGetSearchControls() {
		$this->load->model('Mdl_customer', 'c');
		$_arrCustomer = $this->c->list_select();
		if (is_array($_arrCustomer)) {
			array_unshift($_arrCustomer, array('rowid'=>'', 'company'=>'', 'display_name'=>''));
		}
		$this->load->model('mdl_master_table', 'mt');
		$_arrJoomlaUsers = $this->mt->list_joomla_users();
		array_unshift($_arrJoomlaUsers, array('id'=>'', 'name'=>''));

		$_to = new DateTime();
		$_frm = date_sub(new DateTime(), new DateInterval('P3D'));
		return array(
				'controls' => array(
					array(
						"type" => "sel",
						"label" => "สถานะ",
						"name" => "order_status",
						"sel_options" => array(
							array('id'=>'', 'name'=>''),
							array('id'=>0, 'name'=>'None'),
							array('id'=>1, 'name'=>'WIP'),
							array('id'=>2, 'name'=>'รอส่ง'),
							array('id'=>3, 'name'=>'เครดิต'),
							array('id'=>4, 'name'=>'CLOSED')
						),
						"sel_val" => "id",
						"sel_text" => "name"
					),
					array(
						"type" => "sel",
						"label" => "กลุ่มสินค้า",
						"name" => "category_id",
						"sel_options" => array(
							array('id'=>'', 'name'=>''),
							array('id'=>1, 'name'=>'สั่งตัด'),
							array('id'=>2, 'name'=>'สำเร็จรูป')
						),
						"sel_val" => "id",
						"sel_text" => "name"
					),
					array(
						"type" => "sel",
						"label" => "ประเภท",
						"name" => "product_type_id",
						"sel_options" => array(
							array('id'=>'', 'name'=>''),
							array('id'=>1, 'name'=>'เสื้อโปโล'),
							array('id'=>2, 'name'=>'เสื้อยืด')
						),
						"sel_val" => "id",
						"sel_text" => "name"
					),
					array(
						"type" => "txt",
						"label" => "เลขที่ใบงาน",
						"name" => "job_number"
					),
					array(
						"type" => "sel",
						"label" => "ลูกค้า",
						"name" => "customer_rowid",
						"sel_options" => $_arrCustomer,
						"sel_val" => "rowid",
						"sel_text" => "display_name"
					),
					array(
						"type" => "sel",
						"label" => "เซลส์",
						"name" => "create_user_id",
						"sel_options" => $_arrJoomlaUsers,
						"sel_val" => "id",
						"sel_text" => "name"
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
						"value" => "* จำกัดจำนวนแสดงผลไว้ที่ 100 เพื่อประสิทธิภาพในการทำงานของโปรแกรม"
					)
				)
			);
	}
*/
}