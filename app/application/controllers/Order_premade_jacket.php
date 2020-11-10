<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_premade_jacket extends MY_Ctrl_crud {
	function __construct() {
		parent::__construct();
		$this->modelName = 'Mdl_order_premade_jacket';
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
		} else if ($customer_rowid > 0) {
			$_arr = $this->c->get_by_id($customer_rowid);
			if (is_array($_arr) && isset($_arr['display_name'])) {
				$_strStartScript = "$(function() {_doOrderNew(" . $customer_rowid . ", '" . $_arr['display_name'] . "');});\n";
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
		
		$this->_selOptions = $this->_prepareSelectOptions(
			array(
				'order_screen'=>array('where'=>array('is_jacket'=>1))
				,'supplier'=>array('table_name'=>'m_order_supplier','where'=>array('is_cancel'=>0),'no_feed_row'=>TRUE,'order_by'=>'sort_index')
			)
		);
/* ++ Use aac to reduce load 
		$this->load->model('Mdl_customer', 'c');
		$this->_selOptions['customer'] = $this->c->list_select();
		if (is_array($this->_selOptions['customer'])) {
			array_unshift($this->_selOptions['customer'], array('rowid'=>'', 'company'=>'', 'display_name'=>''));
		}
*/		
		$_editFormParams['type_premade_order'] = TRUE;
		$this->load->model($this->modelName, 'm_model');
		$this->load->model('Mdl_jacket_pattern', 'p');
		$_temp = $this->p->search();
		$this->_selOptions['jacket_pattern'] = array(array('rowid'=>'', 'code'=>'', 'color'=>''));
		foreach ($_temp as $_row) {
			$_each = array();
			foreach ($_row as $_key => $_value) {
				if (strpos($_key, 'remark') === 0) {
					$_each['detail_' . $_key] = $_value;				
				} else {
					$_each[$_key] = $_value;
				}
			}
			array_push($this->_selOptions['jacket_pattern'], $_each);
		}
		$_editFormParams['details_panel'] = $this->add_view('order/_detail_premade', array(
			'size_quan_matrix' => $this->m_model->list_size_quan(),
			'pattern_list' => $this->_selOptions['jacket_pattern']
		), TRUE);
		//-- details panel

		//++ others_price panel form parts
		$_editFormParams['others_price_panel'] = $this->add_view('order/_others_price', array(), TRUE);
		//-- others_price panel form parts

		//++ screen panel form parts
		$_editFormParams['screen_panel'] = $this->add_view('order/_screen', array(
			'order_screen' => $this->_selOptions['order_screen'],
			'order_column' => array(
				"สถานะ", "จัดการสถานะ", "วันที่ Approve", "ช่างตีบล็อค", "รูปภาพ"
			)
		), TRUE);
		//-- screen panel form parts

		$_arrJobNumber = $this->m_model->list_job_number();
		if (is_array($_arrJobNumber)) {
			array_unshift($_arrJobNumber, array('rowid'=>'', 'job_number'=>''));
		} else {
			$_arrJobNumber = array();
		}
		$_editFormParams['job_number_list'] = $_arrJobNumber;
		$_editFormParams['supplier_list'] = $this->_selOptions['supplier'];
		
		//++ set special attributes
		//this one usualy been set in _prepareControlsDefault but here we remove those function to save unnecessary procedure
		$this->_setController("rowid", "", array('type'=>'hdn'));
		$this->_setController("job_number", "เลขที่งาน", array(), array("selectable"=>TRUE,"default"=>TRUE,"order"=>0));
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
		$this->_setController("order_detail_rowid", "", NULL);
		$this->_setController("prod_screen_count", "", NULL);
		$this->_setController("prod_weave_count", "", NULL);
		//-- set special attributes	

		$_screen_status = $this->mt->list_where('manu_screen_status', 'is_cancel=0', NULL, 'm_');
		$this->add_js("var _ARR_SCREEN_STATUS = " . json_encode($_screen_status) . ";", 'custom');
		$_weave_status = $this->mt->list_where('manu_weave_status', 'is_cancel=0', NULL, 'm_');
		$this->add_js("var _ARR_WEAVE_STATUS = " . json_encode($_weave_status) . ";", 'custom');
		
		$pass['left_panel'] = $this->__getLeftPanel();

		$_editFormParams['index'] = 2;
		$_editFormParams['crud_controller'] = 'order_premade_jacket';
		
		$_custom_columns = array();
		//"column" => '{"sTitle":"ขำระมัดจำ", "sClass":"cls-payment-dlg right","sWidth":"80px","mData":"rowid","mRender": function(data,type,full) { return \'<span class="cls-spn-payment">\' + formatNumber(full.total_deposit_payment) + \'</span><img class="tblButton" command="cmd_open_deposit_dialog" src="public/images/b_view.png" title="รายการชำระเงินมัดจำ" />\';}, "bSortable": true}'
		$_custom_columns[] = array(
				"column" => '{"sTitle":"ขำระมัดจำ", "sClass":"cls-payment-dlg right","sWidth":"80px","mData":"rowid","mRender": function(data,type,full) { return \'<span class="cls-spn-payment">\' + formatNumber(full.deposit_payment) + \'</span>\';}, "bSortable": true}'
				, "order" => 7
			);
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
				"column" => '{"sTitle":"ใบงาน", "sClass": "center","mData": function() { return \'<img class="tblButton" command="pdf_1" src="./public/images/pdf_icon_40.png" title="สั่งซื้อแจ็คเก็ตสำเร็จรูป" /><img class="tblButton" command="pdf_6" src="./public/images/pdf_icon_40.png" title="ใบงานข้อมูล แจ็คเก็ตสำเร็จรูป" />\';}, "bSortable": false}'
				, "order" => 15
			);

		$_custom_columns[] = array(
				"column" => '{"sTitle":"#", "sClass": "center","mData":"rowid","mRender": function(data,type,full) { return fnc__DDT_Row_RenderNotification(data, type, full); } ,"bSortable": false}'
				, "order" => 16
		);
		
		$pass['work_panel'] = $this->add_view('_public/_list', 
			array(
				'custom_columns' => $_custom_columns,
				'dataview_fields' => $this->_arrDataViewFields,
				'list_editable' => FALSE,
				'list_deleteable' => FALSE,
				'edit_template' => $this->load->view('order/form_jacket', $_editFormParams, TRUE)
			), TRUE
		);
		$this->add_css('public/css/order/form.css');
		$this->add_js('public/js/order/_base_order.js');
		$this->add_js('public/js/order/form_premade.js');
		$pass['title'] = "สั่งสำเร็จรูปเสื้อแจ็คเก็ต";
		
		if ($_strStartScript != '') $this->add_js($_strStartScript, 'custom');

		$this->add_view_with_script_header('_public/_template_main', $pass);
	}
	
	function __getLeftPanel() {
		//$_arrCompanySearch = $this->c->list_select_company();
		//if (is_array($_arrCompanySearch)) array_unshift($_arrCompanySearch, array('rowid'=>'', 'company'=>''));
		$_to = new DateTime();
		$_frm = date_sub(new DateTime(), new DateInterval('P3D'));
		return $this->add_view('_public/_search_panel', 
			array(
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
			), 
			TRUE
		);
	}

	function get_order_by_id() {
		$_blnSuccess = FALSE;
		$_strError = '';
		$_rowid = $this->input->post('rowid');
		$_arrReturn = $this->_get_detail_by_rowid($_rowid);
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
					$_exists = $this->_mas->int_exists_job_number($_arr['job_number'], 8, (isset($_arr['rowid']) ? $_arr['rowid'] : -1));
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
					
					$this->db->save_queries = false;
					if ($_rowid <= 0) {
						$_strError = 'Invalid RowID';
						break;
					}

					//++ details size
					$this->db->query('delete from t_order_premade_size_jacket where order_detail_rowid in (select rowid from t_order_premade_detail_jacket where order_rowid = ' . $_rowid . ')');
					$this->db->delete('t_order_premade_detail_jacket', array('order_rowid'=>$_rowid));
					$_bulkSize = array();
					if (array_key_exists('detail', $_arr)) {
						if (is_array($_arr['detail'])) {
							foreach ($_arr['detail'] as $_obj) {
								if (array_key_exists('pattern_rowid', $_obj) && array_key_exists('color', $_obj)) {
									if (intval($_obj['pattern_rowid']) > 0) {
										$this->db->set('order_rowid', $_rowid);
										$this->db->set('pattern_rowid', intval($_obj['pattern_rowid']));
										$this->db->set('color', $_obj['color']);
										$this->db->insert('t_order_premade_detail_jacket');
										$_strError = $this->db->_error_message();
									
										$_detail_rowid = 0;
										if ($_strError == '') {
											$_detail_rowid = $this->db->insert_id();

											if (array_key_exists('order_size', $_obj)) {
												if (is_array($_obj['order_size'])) {
													foreach ($_obj['order_size'] as $_size) {
														if (array_key_exists('order_size_rowid', $_size) && array_key_exists('qty', $_size)) {
															if ((intval($_size['qty']) > 0)) { //(intval($_size['order_size_rowid']) > 0) && 
																array_push($_bulkSize, 
																	array('order_detail_rowid'=>$_detail_rowid, 'order_size_rowid'=>intval($_size['order_size_rowid']), 'qty'=>intval($_size['qty']))
																);
															}
														}
													}
												}
											}
										}
									}
								}
							}
							if (count($_bulkSize) > 0) {
								$this->db->insert_batch('t_order_premade_size_jacket', $_bulkSize);
							}
						}
					}
					if ($this->db->_error_message() !== "") {
						$_strError = $this->db->_error_message();
						break;
					}
					//-- details size
					
					// ++ Others price
					$this->db->delete('t_order_premade_price_jacket', array('order_rowid'=>$_rowid));
					$_bulk = array();
					if (array_key_exists('others_price', $_arr)) {
						if (is_array($_arr['others_price'])) {
							foreach ($_arr['others_price'] as $_obj) {
								array_push($_bulk, 
									array(
										'order_rowid'=>$_rowid, 
										'detail'=>$_obj['detail'],
										'price'=>$_obj['price']
									)
								);
							}
							if (count($_bulk) > 0) {
								$this->db->insert_batch('t_order_premade_price_jacket', $_bulk);
							}
						}
					}
					if ($this->db->_error_message() !== "") {
						$_strError = $this->db->_error_message();
						break;
					}
					// -- Others price

					//++ screen
					$this->db->delete('t_order_premade_screen_jacket', array('order_rowid'=>$_rowid));
					$_bulk = array();
					if (array_key_exists('screen', $_arr)) {
						if (is_array($_arr['screen'])) {
							foreach ($_arr['screen'] as $_obj) {
								if (array_key_exists('order_screen_rowid', $_obj)) {
									array_push($_bulk, 
										array(
											'order_rowid'=>$_rowid, 
											'order_screen_rowid'=>$_obj['order_screen_rowid'], 
											'position'=>$_obj['position'],
											'detail'=>$_obj['detail'],
											'size'=>$_obj['size'],
											'job_hist'=>$_obj['job_hist'],
											'price'=>$_obj['price']
										)
									);
								}
							}
							if (count($_bulk) > 0) {
								$this->db->insert_batch('t_order_premade_screen_jacket', $_bulk);
							}
						}
					}
					if ($this->db->_error_message() !== "") {
						$_strError = $this->db->_error_message();
					}
					break;
					//-- screen
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
	function _get_detail_by_rowid($rowid) {
		$_arrReturn = FALSE;
		$_strError = "";
		if (($rowid != FALSE) && $rowid > '0') {
			$this->load->model($this->modelName, 'm');
			$_arrReturn = $this->m->get_detail_by_id($rowid);				
			$_strError = $this->m->error_message;
		}
		if ($_strError != "") {
			return FALSE;
		} else {
			return $_arrReturn;
		}
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
			$_rev_no = (isset($pass['data']['quotation_revision'])) ? (int)$pass['data']['quotation_revision'] : 0;
			$now = new DateTime();
			$strNow = $now->format('YmdHis');
			switch ($pdf_index) {
				case "1":
					$file_name = 'FM-SA-08-001_' . $strNow . '.pdf';
					$pass['title'] = 'สั่งซื้อ แจ็คเก็ตสำเร็จรูป';
					$pass['code'] = sprintf('FM-SA-08-001 REV.%02d', $_rev_no);
					$pass['is_display_price'] = TRUE;
					$pass['others_price_panel'] = $this->load->view('order/pdf/section/_pdf_others_price', $pass, TRUE);
					$pass['detail_section'] = $this->load->view('order/pdf/section/_pdf_premade_order_detail', $pass, TRUE);
					$pass['screen_section'] = $this->load->view('order/pdf/section/_pdf_screen', $pass, TRUE);
					$pass['images_section'] = $this->load->view('order/pdf/section/_pdf_sample_images', $pass, TRUE);
					$html = $this->load->view('order/pdf/premade_pdf', $pass, TRUE);
					break;
				case "6":
					$file_name = 'FM-SA-08-002_' . $strNow . '.pdf';
					$pass['title'] = 'ใบงานข้อมูล แจ็คเก็ตสำเร็จรูป';
					$pass['code'] = sprintf('FM-SA-08-002 REV.%02d', $_rev_no);
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