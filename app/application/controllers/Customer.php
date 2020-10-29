<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends MY_Ctrl_crud {
	function __construct() {
		parent::__construct();
		$this->modelName = 'Mdl_customer';
	}

	public function index($customer_rowid = -1) {
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
			'public/js/jsUtilities.js',
			'public/js/jsGlobalConstants.js'
		));
		$this->load->model('mdl_master_table', 'mt');
		$_arrProvince = $this->mt->list_all_province();
		array_unshift($_arrProvince, array('rowid'=>'', 'name_th'=>''));
		/* ++ issues 20170805 #2 change customer search criteria */
		//$_arrJoomlaUsers = $this->mt->list_joomla_users();
		//array_unshift($_arrJoomlaUsers, array('id'=>'', 'name'=>''));
		$_arrJoomlaBranches = $this->mt->list_joomla_user_branch();
		array_unshift($_arrJoomlaBranches, array('id'=>'', 'title'=>''));
		/* -- issues 20170805 #2 change customer search criteria */

		//Get Default auto prepare controls (followed by model)
		$this->_prepareControlsDefault();
		//++ set special attributes
		$this->_setController("display_name", "ชื่อลูกค้า", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>0));
		$this->_setController("company", "ชื่อกิจการ", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>1));
		$this->_setController("position", "ตำแหน่ง", array());
		$this->_setController("mobile", "มือถือ", array(), array("selectable"=>TRUE,"default"=>FALSE,"order"=>5));
		$this->_setController("tel", "โทรศัพท์", array(), array("selectable"=>TRUE,"default"=>FALSE,"order"=>6));
		$this->_setController("fax", "โทรสาร", array());
		$this->_setController("email", "E-mail", array(), array("selectable"=>TRUE,"default"=>TRUE,"class"=>"center","order"=>7));
		//$this->_setController("is_new_customer", "ลูกค้าใหม่", array("type"=>"hidden"));
		$this->_setController("tax_id", "เลขที่ผู้เสียภาษี", array());
		$this->_setController("tax_branch", "สาขา", array());
		$this->_setController("remark", "บันทึกเพิ่มเติม", array("type"=>"txa"));

		//$this->_setController("disp_is_new_customer", "ลูกค้าใหม่", array(), array("selectable"=>TRUE,"default"=>FALSE,"class"=>"center","order"=>8));
		//$this->_setController("last_job_number", "ใบงานล่าสุด", array(), array("selectable"=>TRUE,"default"=>FALSE,"class"=>"center","order"=>9));
		$this->_setController("disp_last_order_date", "วันที่สั่งล่าสุด", array(), array("selectable"=>TRUE,"default"=>FALSE,"class"=>"center","order"=>10));
		//Details Address to another table
		$this->_setController('address', "ที่อยู่", array("name"=>"address", "type"=>"txa"), array("selectable"=>TRUE,"default"=>FALSE,"order"=>2));
		$this->_setController('province_rowid', "จังหวัด", array("name"=>"province_rowid", "type"=>'sel', 'sel_options'=>$_arrProvince, 'sel_val'=>'rowid', 'sel_text'=>'name_th'));
		$this->_setController('postal_code', "รหัสไปรษณีย์", array("name"=>"postal_code", "type"=>'txt', "maxlangth"=>"5"), array("selectable"=>TRUE,"default"=>FALSE,"class"=>"center","order"=>4));
		//Details Address to another table

		$this->_setController("province", "จังหวัด", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>3));
		$this->_setController("create_user", "สร้างโดย", array(), array("selectable"=>TRUE,"default"=>FALSE,"order"=>11));
		$this->_setController("disp_branch", "สาขา", array(), array("selectable"=>TRUE,"default"=>FALSE,"class"=>"center","order"=>12));
		//$this->_setController("update_user", "แก้ไขโดย", array(), array("selectable"=>TRUE,"default"=>FALSE,"order"=>12));
		//-- set special attributes

		$this->_arrControlLayout = array();
		if ($this->_blnCheckRight('insert', 'order')) {
			$this->_arrControlLayout = array(
				" " => array(
					'return <span class="group-title" style="text-align:right;margin-top:10px;">สั่งสินค้า</span>'
					, 'return <img class="link-button" command="link" target="self" href="order_polo/index/{rowid}/{display_name}" src="./public/images/polo-32.png" title="สั่งตัดเสื้อโปโล" />'
					, 'return <img class="link-button" command="link" target="self" href="order_tshirt/index/{rowid}/{display_name}" src="./public/images/tshirt-32.png" title="สั่งตัดเสื้อยืด" />'
					, 'return <img class="link-button" command="link" target="self" href="order_premade_polo/index/{rowid}/{display_name}" src="./public/images/polo-p-32.png" title="สั่งตัดสำเร็จรูป เสื้อโปโล" />'
					, 'return <img class="link-button" command="link" target="self" href="order_premade_tshirt/index/{rowid}/{display_name}" src="./public/images/tshirt-p-32.png" title="สั่งตัดสำเร็จรูป เสื้อยืด" />'
					, 'return <span class="group-title" style="text-align:right;margin-top:10px;">อื่นๆ</span>'
					, 'return <img class="link-button" command="link" target="self" href="delivery/index/{rowid}" src="./public/images/package-32.png" title="ออกใบนำส่งสินค้า" />'
					, 'return <img class="link-button" command="link" target="self" href="quotation/index/{rowid}" src="./public/images/quotation-32.png" title="ออกใบเสนอราคา" />'
					, ''
				)
			);
		}
		$this->_arrControlLayout = array_merge($this->_arrControlLayout, 
			array(
				array('display_name', '')
				, array('position', '')
				, array('company', '')
				, array('address', '')
				, array('province_rowid', 'postal_code', '')
				, array('mobile', 'tel', '')
				, array('email', 'fax', '')
				, array('remark', '', '')
				, 'ข้อมูลภาษี' => array('tax_id', 'tax_branch', '')
			)
		);

		$pass['left_panel'] = $this->add_view(
			'_public/_search_panel', 
			array(
				'controls' => array(
					array(
						"type" => "txt",
						"label" => $this->_getDisplayLabel('display_name'),
						"name" => "display_name"
					),
					array(
						"type" => "txt",
						"label" => $this->_getDisplayLabel('company'),
						"name" => "company"
					)/*,
					array(
						"type" => "chk"
						,"label" => $this->_getDisplayLabel('disp_is_new_customer')
						,"name" => "new_customer"
						,"value" => TRUE
					)*/,
					array(
						"type" => "sel"
						,"label" => 'จังหวัด'
						,"name" => "province_rowid"
						,"sel_options" => $_arrProvince
						,"sel_val" => "rowid"
						,"sel_text" => "name_th"
						,"value" => 10 // set filter province as กรุงเทพฯ
					),
					array(
						"type" => "txt",
						"label" => 'เบอร์ติดต่อ',
						"name" => "contact_no"
					),
					array(
						"type" => "txt",
						"label" => $this->_getDisplayLabel('email'),
						"name" => "email"
					),
					/* ++ issues 20170805 #2 change customer search criteria
					array(
						"type" => "sel",
						"label" => 'สร้างโดย',
						"name" => "create_user_id",
						"sel_options" => $_arrJoomlaUsers,
						"sel_val" => "id",
						"sel_text" => "name"
					)
					*/
					array(
						"type" => "sel",
						"label" => 'สาขาที่สร้าง',
						"name" => "create_branch_id",
						"sel_options" => $_arrJoomlaBranches,
						"sel_val" => "id",
						"sel_text" => "title"
					)
					/* -- issues 20170805 #2 change customer search criteria */
				)
				,'search_onload' => TRUE
			), TRUE
		);

		$subListControls = array(
			'order_type_id' => array('form_edit' => array())
			,'order_rowid' => array('form_edit' => array())
			,'customer_rowid' => array('form_edit' => array())
			,'category' => array('list_item' => array('label'=>'ประเภท', 'selectable'=>TRUE, 'default'=>TRUE,'width'=>'60px', 'class'=>'center'))
			,'type' => array('list_item' => array('label'=>'ชนิด', 'selectable'=>TRUE, 'default'=>TRUE, 'width'=>'60px', 'class'=>'center'))
			,'job_number' => array('list_item' => array('label'=>'เลขที่ใบงาน', 'selectable'=>TRUE, 'default'=>TRUE, 'width'=>'90px', 'class'=>'center'))
			,'order_date' => array('list_item' => array('label'=>'วันที่สั่ง', 'selectable'=>TRUE, 'default'=>TRUE, 'width'=>'80px', 'class'=>'center'))
			,'deliver_date' => array('list_item' => array('label'=>'วันส่งงาน', 'selectable'=>TRUE, 'default'=>TRUE, 'width'=>'80px', 'class'=>'center'))
			,'customer' => array('list_item' => array('label'=>'ลูกค้า', 'selectable'=>TRUE, 'default'=>TRUE))
			,'company' => array('list_item' => array('label'=>'ชื่อกิจการ', 'selectable'=>TRUE, 'default'=>TRUE))
			,'sum_qty' => array('list_item' => array('label'=>'จำนวน', 'selectable'=>TRUE, 'default'=>TRUE, 'class'=>'default_number'))
			,'total_price_sum' => array('list_item' => array('label'=>'ราคารวม', 'selectable'=>TRUE, 'default'=>TRUE, 'class'=>'default_number'))
			//,'order_status' => array('list_item' => array('label'=>'สถานะ', 'selectable'=>TRUE, 'default'=>TRUE, 'class'=>'center'))
			,'payment_status' => array('list_item' => array('label'=>'สถานะ', 'selectable'=>TRUE, 'default'=>TRUE, 'width'=>'90px', 'class'=>'center'))
		);

		$template = array(
			'index' => 0,
			'list_viewable' => TRUE,
			'list_editable' => TRUE,
			'list_deleteable' => TRUE
		);
		$template['dataview_fields'] = $this->_arrDataViewFields;
		if ($this->_blnCheckRight('insert', 'order')) {
			$template['custom_columns'] = <<<CCLMS
{"sTitle":"สั่งสินค้า", "sClass":"center","mData":"client_temp_id","sWidth":"110px","bSortable":false,"mRender":function(data,type,full) { return '<img class="tblButton" command="link" target="self" src="./public/images/polo-32.png" title="สั่งตัดเสื้อโปโล" href="order_polo/index/' + full.rowid + '" /><img class="tblButton" command="link" target="self" src="./public/images/tshirt-32.png" title="สั่งตัดเสื้อยืด"  href="order_tshirt/index/' + full.rowid + '/' + full.display_name + '" /><img class="tblButton" command="link" target="self" src="./public/images/polo-p-32.png" title="สั่งตัดสำเร็จรูปเสื้อโปโล"  href="order_premade_polo/index/' + full.rowid + '/' + full.display_name + '" /><img class="tblButton" command="link" target="self" src="./public/images/tshirt-p-32.png" title="สั่งตัดสำเร็จรูปเสื้อยืด"   href="order_premade_tshirt/index/' + full.rowid + '/' + full.display_name + '" />';}}
, {"sTitle":"อื่นๆ", "sClass":"center","mData":"client_temp_id","sWidth":"60px","bSortable":false,"mRender":function(data,type,full) { return '<img class="tblButton" command="link" target="self" src="./public/images/package-32.png" title="ออกใบนำส่งสินค้า" href="delivery/index/' + full.rowid + '" /><img class="tblButton" command="link" target="self" src="./public/images/quotation-32.png" title="ออกใบเสนอราคา" href="quotation/index/' + full.rowid + '" />';}}
CCLMS;
		}

		$_sub_custom_columns = <<<SCC
{"sTitle":"เรียกดู", "sClass":"center","mData":"client_temp_id","mRender":function(data,type,full) { return '<img class="tblButton" command="fnc_cmdJobListRow" params="[\'view\', ' + full.order_type_id + ', ' + full.order_rowid + ', \'' + full.job_number + '\', ' + full.customer_rowid + ']" src="./public/images/b_view.png" title="เรียกดู" />'; }, "bSortable": false},
{"sTitle":"แก้ไข", "sClass":"center","mData":"client_temp_id","mRender":function(data,type,full) { return '<img class="tblButton" command="fnc_cmdJobListRow" params="[\'edit\', ' + full.order_type_id + ', ' + full.order_rowid + ', \'' + full.job_number + '\', ' + full.customer_rowid + ']" src="./public/images/b_edit.png" title="แก้ไข" />'; }, "bSortable": false},
{"sTitle":"คัดลอก", "sClass":"center","mData":"client_temp_id","mRender":function(data,type,full) { return '<img class="tblButton" command="fnc_cmdJobListRow" params="[\'clone\', ' + full.order_type_id + ', ' + full.order_rowid + ', \'' + full.job_number + '\', ' + full.customer_rowid + ']" src="./public/images/copy.png" title="คัดลอก" />'; }, "bSortable": false}
SCC;
		$template['edit_template'] = $this->load->view(
			'_public/_form', 
			array(
				'index' => 0,
				'crud_controller' => 'customer',
				'controls' => $this->_arrGetEditControls(),
				'layout' => $this->_arrControlLayout,
				'sublist' => $this->add_view('_public/_sublist', array(
					'index' => 1,
					'master_cols'=>'rowid',
					'map_cols'=>'customer_rowid',
					'dataview_fields' => $subListControls,
					'custom_columns' => $_sub_custom_columns,
					'list_viewable' => FALSE,
					'list_editable' => FALSE,
					'list_deleteable' => FALSE
				), TRUE)
			), 
			TRUE);
		$pass['work_panel'] = $this->add_view('_public/_list', $template, TRUE);

		$this->add_js('public/js/customer/sub_job_list_override.js');//add later than _list because it override dialog initial
		
		$pass['title'] = "ลูกค้า";
		$this->add_view_with_script_header('_public/_template_main', $pass);
	}

	function json_sub_job_list_search() {
		$_blnSuccess = FALSE;
		$_strError = '';
		$arrResult = array();
		$_arr = $this->__getAjaxPostParams();
		if ($_arr !== FALSE) {
			if (array_key_exists('customer_rowid', $_arr)) {
				$this->load->model($this->modelName, 'm');
				$arrResult = $this->m->list_customer_job($_arr['customer_rowid']);
				$strError = $this->m->error_message;
				if ($strError == '') {
					$blnSuccess = TRUE;
					if (!is_array($arrResult)) {
						$arrResult = array();
					}
				}

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
	
	function commit() {
		$_blnSuccess = FALSE;
		$_strError = '';
		$_strMessage = '';
		$json_input_data = json_decode(trim(file_get_contents('php://input')), true); //get json
		$_arr = (isset($json_input_data))?$json_input_data:$this->input->post(); //or post data submit
		if (isset($_arr) && ($_arr != FALSE)) {
			$this->load->model($this->modelName, 'm');
			$this->db->trans_begin();
			
			$_aff_rows = $this->m->commit($_arr);
			$_strError = $this->m->error_message;
			while ($_strError == '') {
				$_rowid = 0;
				if (array_key_exists('rowid', $_arr) && (trim($_arr['rowid']) > '0')) {
					$_rowid = $_arr['rowid'];
				} else {
					$_rowid = $this->m->last_insert_id;
				}
				if ($_rowid <= 0) {
					$_strError = 'Invalid rowid';
					break;
				}
				$this->db->delete('pm_t_customer_address', array('customer_rowid'=>$_rowid));
				$this->load->model('Mdl_customer_address', 'md');
				foreach ($_arr as $key=>$value) {
					if (array_key_exists($key, $this->md->_FIELDS)) {
						$this->md->_FIELDS[$key] = $value;
					}
				}
				$this->md->_FIELDS['customer_rowid'] = $_rowid;
				$this->md->_FIELDS['is_default'] = $_rowid;
				$this->md->insert();
				if ($this->md->error_message !== "") {
					$_strError = $this->md->error_message;
					break;
				}
				break;
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
	
	function json_search_acc() {
		//$this->_serviceCheckRight('view');
		$blnSuccess = FALSE;
		$strError = 'Unknown Error';
		$this->load->model($this->modelName, 'm');
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
}