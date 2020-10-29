function __doInitialOrderControls() {
	$('.cls-frm-edit #txt-order_date, .cls-frm-edit #txt-due_date, .cls-frm-edit #txt-deliver_date').datepicker({
			showOn: "both",
			buttonImage: "public/images/select_day.png",
			buttonImageOnly: true,
			dateFormat: 'dd/mm/yy'
		});

	$('.cls-frm-edit #sel-is_vat').on('change', function() {
		var _elem = $('#frm_edit #chk-is_tax_inv_req');
		if (getValue($(this), 0) >= 1) {
			setValue(_elem, 1);
		} else {
			clearValue(_elem);			
		}
	});
	/*++ Change to ajax auto complete */
	//$('#frm_edit #sel-customer_rowid').combobox();
	$('.cls-frm-edit #aac-customer').autocomplete({
		delay: 500,
		minLength: 3,
		source: function( request, response ) {
			$.ajax({
				dataType: "json"
				, type: 'POST'
				, data: {"display_name_company": request.term}
				, url: "./customer/json_search_acc"
				, success: function(data) {
					$('#frm_edit #aac-customer').removeClass('ui-autocomplete-loading');
					if ((data.success == true) && (data.data.length > 0)) {
						var data = data.data;
						var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
						var _resp = $.map( data, function(item) {
								_text = item.display_name_company;
								if ( !request.term || matcher.test(_text) ) return {
										label: _text.replace(
											new RegExp(
												"(?![^&;]+;)(?!<[^<>]*)(" +
												$.ui.autocomplete.escapeRegex(request.term) +
												")(?![^<>]*>)(?![^&;]+;)", "gi"
											), "<strong>$1</strong>" )
										, value: _text
										, hdn_value: item.rowid
										, company: item.company
										, address: item.address
										, contact_no: item.contact_no
										, customer_tel: item.tel
										, customer_mobile: item.mobile
										, tax_id: item.tax_id
										, tax_branch: item.tax_branch
									};
								}
							)
						if (_resp.length > 0) _resp.unshift({label:"&nbsp;", value:"", hdn_value:""});
						response(_resp);
					}
				}
				, error: function(data) {
					$('#frm_edit #aac-customer').removeClass('ui-autocomplete-loading');  
				}
			});
		}
		, minLength: 3
		, select: _fnc_onSelectedCustomer
		, change: _fnc_onSelectedCustomer
	});
		/*
		, open: function() {}
		, close: function() {}
		, focus: function(event,ui) {}
		, select: function () {}
		*/
	/*-- Change to ajax auto complete */

	$('#sel-ref_number').combobox({
		delay: 500
		,minLength: 3
		,select: function (ev, obj) {
				if (_blnFetching == false) {
					var _rowid = $(obj.item).attr('rowid') || -1;
					if (_rowid > 0) _doCloneJobNumber(_rowid);
				}
			}
	});

	$('.frm-edit-row-value').on('click', 'input[type="file"]', function (e) {
		e.stopImmediatePropagation();
	});
	$('.frm-edit-row').on('click', 'div.display-upload', function (e) {
		e.preventDefault();
		_find = $(this).find('input[type="file"]');
		if (_find.length > 0) {
			_elem = $(_find.get(0));
			if (_elem.prop('disabled')) return false;
			_elem.trigger('click');
		}
		return false;
	});
	$('.frm-edit-row-value').on('change', 'input[type="file"]', function (e) {
		$("#frm_upload_image #element_id").val($(this).attr('id'));
		_tmp = $(this).clone();
		_tmp.insertBefore($(this));
		$('#frm_upload_image').append($(this)).submit();
		$(this).detach();
	});
	var bar = $('.bar');
	var percent = $('.percent');
	$('#frm_upload_image').ajaxForm({
		beforeSend: function() {
			var percentVal = '0%';
			bar.width(percentVal)
			percent.html(percentVal);
		},
		uploadProgress: function(event, position, total, percentComplete) {
			var percentVal = percentComplete + '%';
			bar.width(percentVal)
			percent.html(percentVal);
		},
		success: function() {
			var percentVal = '100%';
			bar.width(percentVal)
			percent.html(percentVal);
		},
		complete: function(xhr) {
			bar.width('0px');
			percent.html('0%');
			data = $.parseJSON(xhr.responseText);
			if (data) {
				if ('error' in data) {
					var _errMsg = data['error'];
					console.log(_errMsg);
					alert(_errMsg);
				} else if (data.files) {
					if (data.files[0]) {
						_elemId = data.files[0]['id'];
						_div = $('#' + _elemId).parents('div').get(0);
						$(_div).css('background-image', 'url(' + data.files[0].url + ')'); //.attr('src', data.files[0].url); //thumbnailUrl
						_hdn = $(_div).find('input[type="hidden"]').get(0);
						$(_hdn).val(data.files[0].name);
						_blnScChanged = true;
					}
				}
			}
		}
	});

	_evntCheckBeforeChangeTab = function(currTab) {
		return true;/* TEST FAST */
		if ($('#hdn-customer_rowid', currTab).length > 0) doClearVldrErrorElement($('#hdn-customer_rowid', currTab));
		if (! blnValidateContainer(false, currTab, '.user-input')) {
			$('.cls-frm-edit .ui-tabs-panel:visible .input-invalid').each(function() {
				var _elem = $(this);
				if (_elem.length > 0) {
					var _msg = _elem.attr('invalid-msg') || 'Invalid';
					if (_elem.attr('id') == 'hdn-customer_rowid') {
						doClearVldrErrorElement(_elem);
						doSetVldrError($('#aac-customer'), 'customer_rowid', _msg, 'ข้อมูลลูกค้า: ' + _msg);
					}
				}
			});
			return false;
		} else {
			return true;
		}		
	};
	
	var _chldCount = $("#tabs ul").find("a").length;
	var _currIndex = 0;
	// ++ Inferno:: ** prevent error neverending reload loop
	$("#tabs ul").find("a").each(function() {
		var href = $( this ).attr( "href" );
		if ( href.indexOf( "#" ) == 0 ) {
			var newHref = window.location.protocol + '//' + window.location.hostname + window.location.pathname + href;
			$(this).attr("href", newHref);
			
			var _divEa = $(href);
			if (_divEa.length > 0) {
				var _divDialog = $($(this).parents(".cls-div-form-edit-dialog").get(0));
				if (_divDialog.length <= 0) return false;
				
				var _bSet = _divEa.find('[class^="cls-btn-form-"]:not(.hidden)');
				var _buttons = [];
				
				if (_currIndex > 0) {
					if (_bSet.length > 0) {
						$(_bSet[0]).before($('<input type="button" class="btn-prev ui-icon-circle-triangle-w ui-button ui-widget ui-state-default ui-corner-all" value="ก่อนหน้า" tab_index="' + _currIndex + '"/>').on('click', function() {
							if (_evntCheckBeforeChangeTab(this)) {
								var _curr = parseInt($(this).attr('tab_index') || 0);
								$("#tabs").tabs("option", "active", (_curr - 1));
							}
							return false;
						}));
						$(_bSet[0]).before($('<span> << </span>'));
					}
				}
				if (_currIndex < (_chldCount - 1)) {
					if (_bSet.length > 0) {
						$(_bSet[(_bSet.length - 1)]).after($('<input type="button" class="btn-next ui-icon-circle-triangle-e ui-button ui-widget ui-state-default ui-corner-all" value="ถัดไป" tab_index="' + _currIndex + '"/>').on('click', function() {
							if (_evntCheckBeforeChangeTab(this)) {
								var _curr = parseInt($(this).attr('tab_index') || 0);
								$("#tabs").tabs("option", "active", (_curr + 1));
							}
							return false;
						}));
						$(_bSet[(_bSet.length - 1)]).after($('<span> >> </span>'));
					}
					// disabled, display only last tab (from pdf requirements "แก้ไข polo-maker 21-3-61")
					_divEa.find('[class^="cls-btn-form-reset"]:not(.hidden)').addClass('hidden').css('display', 'none');
				}
				if (_buttons.length > 0)  {
					_divDialog.dialog('option', 'buttons', _buttons);
				}
				
				_currIndex += 1;
			}
		}
	});
	// -- Inferno:: ** prevent error neverending reload loop
    $("#tabs").tabs({
			beforeActivate: function( event, ui ) {
				if (! _evntCheckBeforeChangeTab(ui.oldPanel)) {
					event.preventDefault();
					event.stopPropagation();
					return false;
				}
			}
		})
		.show();

	$('#tabs').tabs( "option", "active", 0 );

}

function customCommand(command, aData, tr, divEditDlg) {
	if ((command.length > 4) && (command.substr(0, 4).toLowerCase() == 'pdf_')) {
		var _strnum = command.substr(4);
		if (_isInt(_strnum)) {
			var _int = parseInt(_strnum);
			doGenPDF(aData, divEditDlg, _int);
		}
	} else if (command == 'cmd_open_deposit_dialog') {
		var _lst_log = aData['arr_deposit_log'];
		if ((typeof _lst_log == 'string') && (_lst_log.trim() != '')) _lst_log = JSON.parse(_lst_log);
		
		_openPaymentListDialog(null, _lst_log, false, false);
	} else if (command == 'cmd_open_payment_dialog') {
		var _tbl = $('table#tbl_payment', _DIV_PAYMENT_DLG);
		var _edit_url = _tbl.attr('payment_edit_url') || '';
		var _delete_url = _tbl.attr('payment_delete_url') || '';
		var _appr_url = _tbl.attr('payment_approve_url') || '';
		var _blnEdit = false;
		var _blnApprove = false;
		
		_tbl.removeAttr('_COMMIT_URL').removeAttr('_DELETE_URL').removeAttr('_APPROVE_URL');
		if ((_edit_url.trim() != '') && (_delete_url.trim() != '')) {
			_blnEdit = true;
			_tbl.attr('_COMMIT_URL', _edit_url.trim());
			_tbl.attr('_DELETE_URL', _delete_url.trim());
		}
		if (_appr_url.trim() != '') {
			_blnApprove = true;
			_tbl.attr('_APPROVE_URL', _appr_url.trim());
		}
		var _rowid = aData['rowid'];
		var _lst_log = aData['arr_payment_log'];
		if ((typeof _lst_log == 'string') && (_lst_log.trim() != '')) _lst_log = JSON.parse(_lst_log);
		
		_openPaymentListDialog({"order_rowid": _rowid, "order_detail_rowid": null}, _lst_log, _blnEdit, _blnApprove);
	} else if (command.substr(0, 5) == 'chPS_') {
		var _rowid;
		if (CONTROLLER_NAME.search('_premade_') >= 0) {
			_rowid = ('order_detail_rowid' in aData) ? aData['order_detail_rowid'] : false;			
		} else {
			_rowid = ('rowid' in aData) ? aData['rowid'] : false;			
		}
		if (! _rowid) {
			alert('Invalid object value for command "' + command + '"');
			return false;
		}
		var _stc = command.substring(5);
		switch (_stc.toLowerCase()) {
			case 'wip':
				if (confirm('เปลี่ยนแปลงข้อมูลสถานะใบสั่งผลิตเป็น "เริ่มกระบวนการผลิต" กรุณากด OK เพื่อยืนยัน')) __doChangeProcessStatus(_rowid, 30);
				break;
			case 'hld':
				if (confirm('เปลี่ยนแปลงข้อมูลสถานะใบสั่งผลิตเป็น "หยุดพักกระบวนการชั่วคราว" กรุณากด OK เพื่อยืนยัน')) __doChangeProcessStatus(_rowid, 50);
				break;
			case 'uhl':
				if (confirm('เปลี่ยนแปลงข้อมูลสถานะใบสั่งผลิตเป็น "เริ่มกระบวนการต่อ" กรุณากด OK เพื่อยืนยัน')) __doChangeProcessStatus(_rowid, 30);
				break;
			case 'cmp':
				if (confirm('เปลี่ยนแปลงข้อมูลสถานะใบสั่งผลิตเป็น "กระบวนการผลิตเสร็จสิ้น" กรุณากด OK เพื่อยืนยัน')) __doChangeProcessStatus(_rowid, 60);
				break;
			case 'acp':
				if (confirm('เปลี่ยนแปลงข้อมูลสถานะใบสั่งผลิตเป็น "อนุญาตพักการผลิตเพื่อแก้ไขใบเสนอราคา" กรุณากด OK เพื่อยืนยัน')) __doChangeProcessStatus(_rowid, 151);
				break;
			case 'rjt':
				if (confirm('เปลี่ยนแปลงข้อมูลสถานะใบสั่งผลิตเป็น "ปฏิเสธการพักการผลิตเพื่อแก้ไขใบเสนอราคา" กรุณากด OK เพื่อยืนยัน')) __doChangeProcessStatus(_rowid, 30);
				break;
			case 'crb':
				if (confirm('เปลี่ยนแปลงข้อมูลสถานะใบสั่งผลิตเป็น "ยกเลิกการอนุญาตให้แก้ไขใบเสนอราคา" กรุณากด OK เพื่อยืนยัน')) __doChangeProcessStatus(_rowid, 30);
				break;
		}
	}
}

function __doChangeProcessStatus(rowid, ps_rowid, strStatusRemark) {
	var _index = 0;
	var _rowid = rowid || false;
	var _ps_rowid = ps_rowid || false;
	var _status_remark = strStatusRemark || false;
	if (! (_rowid && _ps_rowid)) {
		alert('Invalid parameters to change quotation status ( rowid = ' + rowid + ', status_rowid = ' + status_rowid + ' )');
		return false;
	}
	var _json = { "ps_rowid": _ps_rowid };
	if (CONTROLLER_NAME.search('_premade_') >= 0) {
		_json["detail_rowid"] = _rowid;
	} else {
		_json["rowid"] = _rowid;
	}
	if (_status_remark) _json["status_remark"] = _status_remark;
	_str = JSON.stringify(_json);
	$.ajax({
		type:"POST",
		url:"./" + CONTROLLER_NAME + "/change_status_by_id",
		contentType:"application/json;charset=utf-8",
		dataType:"json",
		data:_str,
		success: function(data, textStatus, jqXHR) {
			if (data.success) {
				doSearch(false);
			} else {
				doDisplayInfo("UnknownError", "ErrorMessage", _index);
			}
			$("#dialog-modal").dialog( "close" );			
		}
		, error: function(jqXHR, textStatus, errorThrown) {
			doDisplayInfo(textStatus + ' : ' + errorThrown, "ErrorMessage", _index);
			$("#dialog-modal").dialog( "close" );
		}, statusCode: {
			404: function() {
				doDisplayInfo("Page not found", "ErrorMessage", _index);
				$("#dialog-modal").dialog( "close" );
			}
		}
	});
}

function doGenPDF(dataRowObj, divEditDlg, index) {
	var _frm = $(divEditDlg).find(".cls-frm-edit").get(0);
	var _index = $(divEditDlg).attr('index');
	if (dataRowObj && _frm) {
		window.open("./" + $(_frm).attr("controller") + "/get_pdf/" + index + '/' + dataRowObj['rowid']);
	}
}

function _doOrderNew(customer_rowid, customer_name) {
	var _cus_rowid = customer_rowid || -1;
	var _cus_name = customer_name || '';
	if ((window.opener == null) && (_cus_rowid > 0) && (window.history.length > 1)) {
		//$('<a href="customer/index/' + _cus_rowid + '">Back</a>')
		$('<a onclick="window.history.back();">Back</a>')
			.button({icons:{primary: 'ui-icon-arrowthick-1-w'}})
			.addClass('cls-navigator')
			.insertBefore($('#frmSearch'));
	}
	var _divEditDlg = $("#divFormEditDialog");
	doInsert(_divEditDlg);
	var _frm = $(_divEditDlg).find(".cls-frm-edit").get(0);
	//$('#sel-customer_rowid', _frm).combobox('setValue', _cus_rowid);
	$('#aac-customer', _frm).val(_cus_name);
	$('#hdn-customer_rowid', _frm).val(_cus_rowid);
}

function _doExtCmdView(rowid, job_number, customer_rowid) {
	var _rowid = rowid || -1;
	var _job_number = job_number || '';
	var _cus_rowid = customer_rowid || -1;
	if ((window.opener == null) && (_cus_rowid > 0) && (window.history.length > 1)) {
		$('<a onclick="window.history.back();">Back</a>')
			.button({icons:{primary: 'ui-icon-arrowthick-1-w'}})
			.addClass('cls-navigator')
			.insertBefore($('#frmSearch'));
	}
	_currentDataString = 'job_number=' + _job_number;
	$('#divDisplayQueryResult').on('load_done', function() {
		$('#divDisplayQueryResult').on('load_done', function() {});
		
		var _divEditDlg = $("#divFormEditDialog");
		var _arrData = _objDataTable.fnGetData()
		var _data;
		if (_arrData.length > 0) {
			for (var _x in _arrData) {
				if (_arrData[_x].rowid == _rowid) {
					_data = _arrData[_x];
					break;
				}
			}
			if ((typeof _data != undefined) && (typeof doView == 'function')) doView(_data, _divEditDlg);
		}
	});
	doSearch(false);
}

function _doExtCmdEdit(rowid, job_number, customer_rowid) {
	var _rowid = rowid || -1;
	var _job_number = job_number || '';
	var _cus_rowid = customer_rowid || -1;
	if ((window.opener == null) && (_cus_rowid > 0) && (window.history.length > 1)) {
		$('<a onclick="window.history.back();">Back</a>')
			.button({icons:{primary: 'ui-icon-arrowthick-1-w'}})
			.addClass('cls-navigator')
			.insertBefore($('#frmSearch'));
	}
	_currentDataString = 'job_number=' + _job_number;
	$('#divDisplayQueryResult').on('load_done', function() {
		$('#divDisplayQueryResult').on('load_done', function() {});
		
		var _divEditDlg = $("#divFormEditDialog");
		var _arrData = _objDataTable.fnGetData()
		var _data;
		if (_arrData.length > 0) {
			for (var _x in _arrData) {
				if (_arrData[_x].rowid == _rowid) {
					_data = _arrData[_x];
					break;
				}
			}
			if ((typeof _data != undefined) && (typeof doEdit == 'function')) doEdit(_data, {}, _divEditDlg);
		}
	});
	doSearch(false);
}

function _doExtCmdClone(rowid, job_number, customer_rowid) {
	var _rowid = rowid || -1;
	var _cus_rowid = customer_rowid || -1;
	if ((window.opener == null) && (_cus_rowid > 0) && (window.history.length > 1)) {
		$('<a onclick="window.history.back();">Back</a>')
			.button({icons:{primary: 'ui-icon-arrowthick-1-w'}})
			.addClass('cls-navigator')
			.insertBefore($('#frmSearch'));
	}
	var _divEditDlg = $("#divFormEditDialog");
	var _frm = $(_divEditDlg).find(".cls-frm-edit").get(0);
	$( _frm ).on('edit_form_loaded', function() {
		var _val = 0;
		$('#sel-ref_number option', _frm).each(function () {
			var _earid = $(this).attr('rowid') || 0;
			if (_rowid == _earid) {
				_val = $(this).val();
				return false;
			}
		});
		if (_val > 0) $('#sel-ref_number', _frm).combobox('setValue', _val);
		$( _frm ).on('edit_form_loaded', function() {});
	});
	doInsert(_divEditDlg);
}

function _fnc_onSelectedCustomer(event, ui) {
	if (! ui.item) return false;
	
	var _aac_text = (ui.item.value || '').toString().trim();
	var _aac_hdn_val = (ui.item.hdn_value || '').toString().trim();
	$('#frm_edit #hdn-customer_rowid').val(_aac_hdn_val);
	$('.cls-customer-detail .data-container').each(function() {
		var _data = _getElemData(this);
		if (_data in ui.item) {
			var _val = (ui.item[_data] || '').toString().trim();
			_setElemValue(this, _val, false);
		}
	});
}

function fnc__DDT_Row_RenderOP(data, type, full) {
	var _str = ('avail_process_status' in full) ? full.avail_process_status + ',' : false;
	var _strReturn = '';
	if (_str) {
		var _arr = _str.split(',');
		for (var _i=0;_i<_arr.length;_i++) {
			var _cmd = _arr[_i];
			switch (_cmd.toLowerCase()) {
				case 'wip':
					_strReturn += '<img class="tblButton" command="chPS_WIP" src="./public/images/edit.png" alt="work in process" title="เริ่มกระบวนการผลิต">';
					break;
				case 'hld':
					_strReturn += '<img class="tblButton" command="chPS_HLD" src="./public/images/icons/16/collaboration.png" alt="hold" title="พักการดำเนินงาน">';
					break;
				case 'uhl':
					_strReturn += '<img class="tblButton" command="chPS_UHL" src="./public/images/edit.png" alt="unhold" title="ดำเนินงานต่อ">';
					break;
				case 'cmp':
					_strReturn += '<img class="tblButton" command="chPS_CMP" src="./public/images/icons/16/file-complete.png" alt="completed" title="กระบวนการผลิตเสร็จสิ้น">';
					break;
				case 'acp':
					_strReturn += '<img class="tblButton" command="chPS_ACP" src="./public/images/ok-green.png" alt="accepted rollback for edit" title="อนุญาตการปลดล็อค เพื่อการแก้ไขข้อมูล">';
					break;
				case 'rjt':
					_strReturn += '<img class="tblButton" command="chPS_RJT" src="./public/images/ok-grey.png" alt="reject rollback for edit" title="ไม่อนุญาตปลดล็อค เพื่อการแก้ไขข้อมูล">';
					break;
				case 'crb':
					_strReturn += '<img class="tblButton" command="chPS_CRB" src="./public/images/details_open.png" alt="cancel rollbacked for edit" title="ยกเลิกการปลดล็อค กลับเข้ากระบวนการ">';
					break;
			}
		}
	}
	return _strReturn;
}